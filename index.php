<?php
/**
 * AviWPCache
 * 
 */
$cacheenabled = true;
$cachedebug = false;

if ($cacheenabled) {
    require_once('cache_aviwp_class.php');

    $aviwpcache = new AviWPCache($_SERVER, $cachedebug);

    // verificamos si el path hay que cachearlo o no
    if (!$aviwpcache->bypassurl()) {

        // si viene el parametro deletecache=true en la url borramos el archivo ya creado
        if (isset($_GET['deletecache']) && $_GET['deletecache'] == 'true') {
            $aviwpcache->deletecache();
        }

        $aviwpcache->createcache();

        // muestro por pantalla el contenido del cache y cierro
        $data = $aviwpcache->getcontent();
        echo $data;
        die();
    }
}

// original wordpress index code after that

/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require( dirname( __FILE__ ) . '/wp-blog-header.php' );
