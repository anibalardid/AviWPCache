<?php
/**
 * AviWPCache
 * Methods and functions
 * 
 */

require_once('mobile_detect.php');

class AviWPCache {

    private $server;
    private $serverdata;
    private $device;
    private $cachefilename;
    private $debugcache;

    function __construct($server, $debug) {
        $this->server = $server;

        $schema    = (@$server["HTTPS"] == "on") ? "https://" : "http://";
        $host      = $schema . $server["SERVER_NAME"];
        $port      = ($server['SERVER_PORT'] != 80) ? ':' . $server['SERVER_PORT'] : '';
        $path      = $server["REQUEST_URI"];
        $url       = $host . $port . $path;
        $useragent = $server["HTTP_USER_AGENT"];

        $this->serverdata = [
            'schema'    => $schema,
            'host'      => $host,
            'port'      => $port,
            'path'      => $path,
            'url'       => $url,
            'useragent' => $useragent,
        ];

        $this->setdevice();

        $this->createfilename();

        $this->debugcache = $debug;
    }

    public function getserverdata() {
        return $this->serverdata;
    }

    public function getdevice() {
        return $this->device;
    }

    public function getpath() {
        return $this->serverdata['path'];
    }

    public function geturl() {
        return $this->serverdata['url'];
    }

    public function getuseragent() {
        return $this->serverdata['useragent'];
    }

    public function getcachefilename() {
        return $this->cachefilename;
    }

    // funcion que valida si la url no debe ser tomada del cache
    public function bypassurl() {
        $path = $this->getpath();

        $bypass = [
            'bypasstruecache',
            'wordfence',
            '.php',
            'wp-admin',
            '.ico',
            '.jpg',
            '.gif',
            '.png',
            '.css'
        ];

        foreach ($bypass as $bp) {
            if (stristr($path, $bp) !== FALSE) { 
                $this->debugcache("bypassing: " . $path);
                return true;
            }
        }
        $this->debugcache("not bypass: " . $path);
        return false;
    }

    // creacion de archivo en disco
    public function createfilename() {
        $path = $this->getpath();

        $path = ($path == '' || $path == '/') ? 'index' : $path;
        $path = str_replace("/", "", $path);
        $path = date('Y-m-d') . '_' . $path;
        $filename = 'aviwpcache/cache_' . $this->device . '_' . urlencode($path) . '.html';
        $this->debugcache("filename: ". $filename);

        $this->cachefilename = $filename;
    }

    public function debugcache($debugtext) {
        $debugcache = $this->debugcache;

        if ($debugcache) {
            error_log($debugtext);
        }
    }

    public function geturlcontent($url, $useragent) {
        $this->debugcache("user agent:" . $useragent);

        $ch=curl_init();
        $timeout=5;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);

        // Get URL content
        $lines_string=curl_exec($ch);
        // close handle to release resources
        curl_close($ch);
        //output, you can also save it locally on the server
        return $lines_string;
    }

    public function savecontenttocachefile($data, $file) {
        $this->debugcache("filename:" . $file);

        $handle = fopen($file, 'w') or die('Cannot open file: '.$file); 
        fwrite($handle, $data);
        fclose($handle);
    }

    private function setdevice() {
        $detect = new Mobile_Detect;
     
        if ( $detect->isMobile() ) {
            $this->device = 'mobile';
        } elseif( $detect->isTablet() ) {
            $this->device = 'tablet';
        } else {
            $this->device = 'desktop';
        }

        $this->debugcache("device:" . $this->getdevice());

        return;
    }

    public function deletecache() {
        $url = $this->geturl();
        $filename = $this->getcachefilename();

        $this->debugcache("borrando cache de url: " . $url);
        // borramos cache
        if(file_exists($filename)) {
            unlink($filename);
        }
    }

    public function createcache() {
        $filename = $this->getcachefilename();
        $url = $this->geturl();

        if (!file_exists($filename)) {
            $this->debugcache("creando cache para url: " . $url);

            // se agrega un parametro de bypass para poder llamar a esta misma web pero sin pasar x el cache y quedar en un loop infinito
            $urlfirstget = $url . '?bypasstruecache';

            // obtenemos el contenido de la url enviando el useragent correcto
            $data = $this->geturlcontent($urlfirstget, $this->getuseragent());

            // luego creo un archivo con el data
            $this->savecontenttocachefile($data, $filename);  
        } else {
            $this->debugcache("ya existia cache para url: " . $this->geturl());
        }

    }

    public function getcontent() {
        $filename = $this->getcachefilename();

        return file_get_contents($filename);
    }

}