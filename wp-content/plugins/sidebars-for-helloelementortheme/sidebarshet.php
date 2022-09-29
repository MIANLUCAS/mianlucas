<?php
/**
 * Plugin Name: Sidebars for Hello Elementor Theme
 * Description: 6 responsive sidebars for just about everything you need to use anywhere within your website built using Elementor and Hello Elementor theme.
 * Plugin URI: https://ameralire.com/en/producets/sidebars-hello-elementor-theme/
 * Author: Amer Ali
 * Version: 1.0.0
 * Author URI: https://ameralire.com/
 *
 * Text Domain: sidebarshet
 * Domain Path: /languages
 */

 
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


/**
 * Defining plugin constants.
 *
 * @since 1.0.0
 */
define('SIDEBARSHET_PLUGIN_FILE', __FILE__);
define('SIDEBARSHET_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('SIDEBARSHET_PLUGIN_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('SIDEBARSHET_PLUGIN_URL', trailingslashit(plugins_url('/', __FILE__)));
define('SIDEBARSHET_PLUGIN_VERSION', '1.0.0');
define('SIDEBARSHET_ASSET_PATH', wp_upload_dir()['basedir'] . '/sidebars-for-hello-elementor-theme');
define('SIDEBARSHET_ASSET_URL', wp_upload_dir()['baseurl'] . '/sidebars-for-hello-elementor-theme');

//Register Sidebars
if (function_exists('register_sidebar')) {
	register_sidebar(array(	
		'name'=> 'Global Sidebar ',
		'id' => 'sshetglobalsidebar',
		'description' => '',
		'before_widget' => '<div>', // '%1$s' adds a unique class for each widget area added
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>'
	)
	);		
}
if (function_exists('register_sidebar')) {
	register_sidebar(array(	
		'name'=> 'Blog Sidebar ',
		'id' => 'sshetblogsidebar',
		'description' => '',
		'before_widget' => '<div>', // '%1$s' adds a unique class for each widget area added
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>'
	)
	);		
}

if ( function_exists ('register_sidebar')) {
	register_sidebar(array(
		'name' => 'Footer 1',
		'id' => 'sshetfooter1',
		'description' => '',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>'
		)
	);
}
if ( function_exists ('register_sidebar')) {
	register_sidebar(array(
		'name' => 'Footer 2',
		'id' => 'sshetfooter2',
		'description' => '',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>'
		)
	);
}
if ( function_exists ('register_sidebar')) {
	register_sidebar(array(
		'name' => 'Footer 3',
		'id' => 'sshetfooter3',
		'description' => '',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>'
		)
	);
}
if ( function_exists ('register_sidebar')) {
	register_sidebar(array(
		'name' => 'Footer 4',
		'id' => 'sshetfooter4',
		'description' => '',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>'
		)
	);
}

