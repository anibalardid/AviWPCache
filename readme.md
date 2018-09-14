# AviWPCache

Codigo libre de descargar, modificar, distribuir, etc etc


## Introduccion

### Porque hice este código para Wordpress de Cache

Un día un cliente me contactó para optimizar su página web hecha en Wordpress, la cual tenia un theme muy pesado, muchos plugins, una home muy larga con demasiadas entradas, y ademas cargada de anuncios...

En ese momento probé todos los plugins de cache disponibles (W3 Total Cache, Wp Fastest Cache, WP Rocket, y varios mas).

Todos tenian sus pros y contras, algunos no tenian version separada para mobile, otros no cacheaban las queries a la base de datos, etc.

Entonces vi que simepre sea cual sea el plugin tiene que pasar por el codigo core de Wordpress, siempre hace alguna consulta a la DB, y todo eso incrementa la carga, mas aún en este sitio que tiene entre 200 a 400 usuarios simultaneos en linea todo el tiempo, y mas de 1 milllón de visitas al mes.

Y una buena noche se me ocurrió hacer una modificación en Wordpress, que no sea usar un plugin, que antes de cargar el código de Wordpress verifique la url y cree un archivo html para servir de manera mucho mas velóz al browser del visitante.

## Instalacion

### Advertencia

Antes que nada HACER UN BACKUP del sitio, minimamente una copia de seguridad del archivo index.php .
Este es un codigo que está en desarrollo y debe probarse bien antes de implementar en producción.


### A meter mano

1. Necesitas descargarte los archivos:
	* index.php
	* cache_functions.php
	* mobile_detect.php

2. En tu instalación de Wordpress, en el raíz, copiar el archivo index.php como index.php.bak, para respaldarlo por las dudas

3. crear una carpeta/directorio con el nombre "aviwpcache" y darle permisos de escritura

3. Copiar los archivos descargados en el directorio raíz de Wordpress (reemplazando el index.php)

4. Editando el archivo index.php se puede encender o no el cache, y encender o no el modo debug.

5. Listo, ya se puede probar

### Configuración

1. En el archivo index.php se puede encender el cache y/o el debug

	$cacheenabled = true;
	$cachedebug = false;

2. Si en la url agregamos al final ?deletecache=true borra el cache viejo y crea uno nuevo



## Dependencia de otra libreria

Para que el cache pueda obtener la web correcta según el dispositivo que visita utilicé una librería de github de codigo abierto. 

Aquí pongo sus links:
 * Homepage: http://mobiledetect.net
 * GitHub: https://github.com/serbanghita/Mobile-Detect
 * README: https://github.com/serbanghita/Mobile-Detect/blob/master/README.md
 * CONTRIBUTING: https://github.com/serbanghita/Mobile-Detect/blob/master/docs/CONTRIBUTING.md
 * KNOWN LIMITATIONS: https://github.com/serbanghita/Mobile-Detect/blob/master/docs/KNOWN_LIMITATIONS.md
 * EXAMPLES: https://github.com/serbanghita/Mobile-Detect/wiki/Code-examples


## Mis datos de contacto

Página web: https://ardid.com.ar
Linkedin: https://www.linkedin.com/in/anibalardid/
GitHub: https://github.com/anibalardid/
Este Código: https://github.com/anibalardid/AviWPCache

