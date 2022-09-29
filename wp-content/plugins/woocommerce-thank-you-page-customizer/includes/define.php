<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'VI_WOOCOMMERCE_THANK_YOU_PAGE_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woocommerce-thank-you-page-customizer" . DIRECTORY_SEPARATOR );
define( 'VI_WOOCOMMERCE_THANK_YOU_PAGE_ADMIN', VI_WOOCOMMERCE_THANK_YOU_PAGE_DIR . "admin" . DIRECTORY_SEPARATOR );
define( 'VI_WOOCOMMERCE_THANK_YOU_PAGE_FRONTEND', VI_WOOCOMMERCE_THANK_YOU_PAGE_DIR . "frontend" . DIRECTORY_SEPARATOR );
define( 'VI_WOOCOMMERCE_THANK_YOU_PAGE_LANGUAGES', VI_WOOCOMMERCE_THANK_YOU_PAGE_DIR . "languages" . DIRECTORY_SEPARATOR );
define( 'VI_WOOCOMMERCE_THANK_YOU_PAGE_INCLUDES', VI_WOOCOMMERCE_THANK_YOU_PAGE_DIR . "includes" . DIRECTORY_SEPARATOR );
define( 'VI_WOOCOMMERCE_THANK_YOU_PAGE_TEMPLATES', VI_WOOCOMMERCE_THANK_YOU_PAGE_DIR . "templates" . DIRECTORY_SEPARATOR );
define( 'VI_WOOCOMMERCE_THANK_YOU_PAGE_3RD', VI_WOOCOMMERCE_THANK_YOU_PAGE_DIR . "3rd" . DIRECTORY_SEPARATOR );
$plugin_url = plugins_url( 'woocommerce-thank-you-page-customizer' );
//$plugin_url = plugins_url( '', __FILE__ );
$plugin_url = str_replace( '/includes', '', $plugin_url );
define( 'VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS', $plugin_url . "/css/" );
define( 'VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS_DIR', VI_WOOCOMMERCE_THANK_YOU_PAGE_DIR . "css" . DIRECTORY_SEPARATOR );
define( 'VI_WOOCOMMERCE_THANK_YOU_PAGE_JS', $plugin_url . "/js/" );
define( 'VI_WOOCOMMERCE_THANK_YOU_PAGE_JS_DIR', VI_WOOCOMMERCE_THANK_YOU_PAGE_DIR . "js" . DIRECTORY_SEPARATOR );
define( 'VI_WOOCOMMERCE_THANK_YOU_PAGE_IMAGES', $plugin_url . "/images/" );
define( 'VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS', VI_WOOCOMMERCE_THANK_YOU_PAGE_IMAGES . "/markers/" );

/*Include functions file*/
if ( is_file( VI_WOOCOMMERCE_THANK_YOU_PAGE_INCLUDES . "functions.php" ) ) {
	require_once VI_WOOCOMMERCE_THANK_YOU_PAGE_INCLUDES . "functions.php";
}
if ( is_file( VI_WOOCOMMERCE_THANK_YOU_PAGE_INCLUDES . "update.php" ) ) {
	require_once VI_WOOCOMMERCE_THANK_YOU_PAGE_INCLUDES . "update.php";
}
if ( is_file( VI_WOOCOMMERCE_THANK_YOU_PAGE_INCLUDES . "check_update.php" ) ) {
	require_once VI_WOOCOMMERCE_THANK_YOU_PAGE_INCLUDES . "check_update.php";
}
if ( is_file( VI_WOOCOMMERCE_THANK_YOU_PAGE_INCLUDES . "support.php" ) ) {
	require_once VI_WOOCOMMERCE_THANK_YOU_PAGE_INCLUDES . "support.php";
}
if ( is_file( VI_WOOCOMMERCE_THANK_YOU_PAGE_INCLUDES . "data.php" ) ) {
	require_once VI_WOOCOMMERCE_THANK_YOU_PAGE_INCLUDES . "data.php";
}
if ( is_file( VI_WOOCOMMERCE_THANK_YOU_PAGE_INCLUDES . "class-wtypc-functions.php" ) ) {
	require_once VI_WOOCOMMERCE_THANK_YOU_PAGE_INCLUDES . "class-wtypc-functions.php";
}
if ( is_file( VI_WOOCOMMERCE_THANK_YOU_PAGE_INCLUDES . "custom-controls.php" ) ) {
	require_once VI_WOOCOMMERCE_THANK_YOU_PAGE_INCLUDES . "custom-controls.php";
}
vi_include_folder( VI_WOOCOMMERCE_THANK_YOU_PAGE_ADMIN, 'VI_WOOCOMMERCE_THANK_YOU_PAGE_Admin_' );
vi_include_folder( VI_WOOCOMMERCE_THANK_YOU_PAGE_FRONTEND, 'VI_WOOCOMMERCE_THANK_YOU_PAGE_Frontend_' );
vi_include_folder( VI_WOOCOMMERCE_THANK_YOU_PAGE_3RD.'woocommerce-email-template-customizer'.DIRECTORY_SEPARATOR, 'VI_WOOCOMMERCE_THANK_YOU_PAGE_3RD_' );
