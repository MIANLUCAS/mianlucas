<?php

/**
 * Plugin Name:       		Mr. Tailor Extender
 * Plugin URI:        		https://mrtailor.wp-theme.design/
 * Description:       		Extends the functionality of Mr. Tailor with theme specific features.
 * Version:           		1.4.6
 * Author:            		GetBowtied
 * Author URI:        		https://getbowtied.com
 * Requires at least: 		5.0
 * Tested up to: 			5.8
 * Text Domain:             mrtailor-extender
 *
 * @package  Mr. Tailor Extender
 * @author   GetBowtied
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

// Plugin Updater
require 'core/updater/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/getbowtied/mr-tailor-extender/master/core/updater/assets/plugin.json',
	__FILE__,
	'mr-tailor-extender'
);

$version = ( isset(get_plugin_data( __FILE__ )['Version']) && !empty(get_plugin_data( __FILE__ )['Version']) ) ? get_plugin_data( __FILE__ )['Version'] : '1.0';
define ( 'MT_EXT_VERSION', $version );

if ( ! class_exists( 'MrTailorExtender' ) ) :

	/**
	 * MrTailorExtender class.
	*/
	class MrTailorExtender {

		/**
		 * The single instance of the class.
		 *
		 * @var MrTailorExtender
		*/
		protected static $_instance = null;

		/**
		 * MrTailorExtender constructor.
		 *
		*/
		public function __construct() {

			// Helpers
			include_once( dirname( __FILE__ ) . '/includes/helpers/helpers.php' );

			// Vendor
			include_once( dirname( __FILE__ ) . '/includes/vendor/enqueue.php' );

            // Customizer
			include_once( dirname( __FILE__ ) . '/includes/customizer/repeater/class-mt-ext-repeater-control.php' );

			// Shortcodes
			include_once( dirname( __FILE__ ) . '/includes/shortcodes/index.php' );

			// Social Media
			include_once( dirname( __FILE__ ) . '/includes/social-media/class-social-media.php' );

			// Gutenberg Blocks
            include_once( dirname( __FILE__ ) . '/includes/gbt-blocks/index.php' );

            // Mr. Tailor Dependent Components
			if( function_exists('mrtailor_theme_version') ) {

                // Addons
    			if ( is_plugin_active( 'woocommerce/woocommerce.php') ) {
    				include_once( dirname( __FILE__ ) . '/includes/addons/class-wc-category-header-image.php' );
    			}

				// Social Sharing Buttons
				if ( is_plugin_active( 'woocommerce/woocommerce.php') ) {
					include_once( dirname( __FILE__ ) . '/includes/social-sharing/class-social-sharing.php' );
				}

				// Custom Code
				include_once( dirname( __FILE__ ) . '/includes/custom-code/class-custom-code.php' );

				// VC Templates
				if ( defined(  'WPB_VC_VERSION' ) ) {

					// Modify and remove existing shortcodes from VC
					include_once( dirname( __FILE__ ) . '/includes/wpbakery/custom_vc.php' );

					// VC Templates
					$vc_templates_dir = dirname(__FILE__) . '/includes/wpbakery/vc_templates/';
					vc_set_shortcodes_templates_dir($vc_templates_dir);
				}

                // Custom Menu
				include_once( dirname( __FILE__ ) . '/includes/custom-menu/custom-menu.php' );
				include_once( dirname( __FILE__ ) . '/includes/custom-menu/edit_custom_walker.php' );
				include_once( dirname( __FILE__ ) . '/includes/custom-menu/custom_walker.php' );
			}
		}

		/**
		 * Ensures only one instance of MrTailorExtender is loaded or can be loaded.
		 *
		 * @return MrTailorExtender
		*/
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
	}
endif;

add_action( 'after_setup_theme', function() {
    $mrtailor_extender = new MrTailorExtender;
} );
