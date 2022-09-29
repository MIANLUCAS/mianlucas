<?php
/**
* Configuración básica de WordPress.
*
* Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
* claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
* visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
* wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
*
* This file is used by the wp-config.php creation script during the
* installation. You don't have to use the web site, you can just copy this file
* to "wp-config.php" and fill in the values.
*
* @package WordPress
*/

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define( 'DB_NAME', 'dbs258470' );

/** Tu nombre de usuario de MySQL */
define( 'DB_USER', 'dbu157514' );

/** Tu contraseña de MySQL */
define( 'DB_PASSWORD', 'Jlk78&5gzdMpu/rZ65%' );

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define( 'DB_HOST', 'db5000264851.hosting-data.io' );

/** Codificación de caracteres para la base de datos. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
* Claves únicas de autentificación.
*
* Define cada clave secreta con una frase aleatoria distinta.
* Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
* Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
*
* @since 2.6.0
*/
define( 'AUTH_KEY', 'F!<,:M90s,CWhN/vmSQR={K`TcQGvi06yEFAa7#bs_u86b:5@Gc+&TElv/|!j49V' );
define( 'SECURE_AUTH_KEY', ':3 mae4b.?3P0dt=cZ3<N8W}u~!+6}nA[J/n|wd7LD;WlH[|T<q?[aO~[tq5ad}F' );
define( 'LOGGED_IN_KEY', '$?]tK]v&x*chf@fO^OiOi79LH=(&i N0v.:VYOqKbehxAs2?fM:SOU)S~ONq$PQi' );
define( 'NONCE_KEY', 'cOpf^:ac77!YE+la.n)2v:O,sEqVZ57+X?,E_JNk=Mrj`.Usq;ub]y`n+CTg%| l' );
define( 'AUTH_SALT', 'xe}CKJ`0i<`YjB<x*NtU5-:zn:T3Mi(iV%_mMUFPO_!<,XN5KB>~HT_#NF$h#),=' );
define( 'SECURE_AUTH_SALT', ':e}AFYKXSQkup/C>jlso*2q499xK`Mz*DuJGDvCaFS59S o++1]KPK~g%Qi<[y4l' );
define( 'LOGGED_IN_SALT', '9yYWmvDjxY`d:9yoA{E6_zAG-J^08Z,70a6ViQX>Cd3.b`ASLB* ;.7y b#C,kvJ' );
define( 'NONCE_SALT', '+_K)o(oNmD(F%|MgrMQ07s97]>?1B^pF0f4`<;E~^~G: $eaLO97E<}epMuQwDDA' );

/**#@-*/

/**
* Prefijo de la base de datos de WordPress.
*
* Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
* Emplea solo números, letras y guión bajo.
*/
$table_prefix = 'wp_';


/**
* Para desarrolladores: modo debug de WordPress.
*
* Cambia esto a true para activar la muestra de avisos durante el desarrollo.
* Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
* en sus entornos de desarrollo.
*/
define('WP_MEMORY_LIMIT', '128M');
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

ini_set('memory_limit','256M');

