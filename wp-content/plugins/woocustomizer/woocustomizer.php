<?php

/**
 * Plugin Name: WooCustomizer
 * Version: 1.1.2
 * Plugin URI: https://kairaweb.com/wordpress-plugins/woocustomizer/
 * Description: Customize WooCommerce without code... WooCustomizer has all the WooCommerce hooks & lots more features built into one plugin, to further edit your WooCommerce Shop and Product pages, Cart, Checkout and User Account pages.
 * Author: Kaira
 * Author URI: https://kairaweb.com/
 * Requires at least: 5.0
 * Tested up to: 5.3.2
 *
 * Text Domain: woocustomizer
 * Domain Path: /lang/
 * 
 *
 * @package WordPress
 * @author Kaira
 * @since 1.0.0
 */
define( 'WCD_PLUGIN_VERSION', '1.1.2' );
define( 'WCD_PLUGIN_URL', plugins_url( '', __FILE__ ) );
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'wcz_fs' ) ) {
    wcz_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'wcz_fs' ) ) {
        // Create a helper function for easy SDK access.
        function wcz_fs()
        {
            global  $wcz_fs ;
            
            if ( !isset( $wcz_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $wcz_fs = fs_dynamic_init( array(
                    'id'              => '4668',
                    'slug'            => 'woocustomizer',
                    'premium_slug'    => 'woocustomizer-pro',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_b12a9cb6205ed1d8256a177af56b4',
                    'is_premium'      => false,
                    'premium_suffix'  => 'Pro',
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'has_affiliation' => 'selected',
                    'menu'            => array(
                    'slug'        => 'wcz_settings',
                    'contact'     => false,
                    'support'     => false,
                    'affiliation' => false,
                    'parent'      => array(
                    'slug' => 'woocommerce',
                ),
                ),
                    'is_live'         => true,
                ) );
            }
            
            return $wcz_fs;
        }
        
        // Init Freemius.
        wcz_fs();
        // Signal that SDK was initiated.
        do_action( 'wcz_fs_loaded' );
    }
    
    // Load plugin class files.
    require_once 'includes/class-wcz.php';
    require_once 'includes/class-wcz-settings.php';
    // Load plugin libraries.
    require_once 'includes/lib/class-wcz-admin-api.php';
    require_once 'includes/lib/class-wcz-post-type.php';
    require_once 'includes/lib/class-wcz-taxonomy.php';
    // Load Customizer Library files.
    require_once 'includes/customizer/customizer-options.php';
    require_once 'includes/customizer/customizer-library/customizer-library.php';
    require_once 'includes/customizer/styles.php';
    // require_once 'includes/customizer/mods.php';
    if ( WooCustomizer::wcz_is_plugin_active( 'woocommerce.php' ) ) {
        require_once 'includes/inc/woocommerce.php';
    }
    // Excluded from Pro Version
    
    if ( !WooCustomizer::wcz_is_plugin_active( 'woocommerce.php' ) ) {
        // Admin notice for if WooCOmmerce is not active
        function wcz_no_woocommerce_notice()
        {
            ?>
            <div class="error">
                <p><?php 
            esc_html_e( 'WooCustomizer requires the WooCommerce plugin to be active to work', 'woocustomizer' );
            ?></p>
            </div>
        <?php 
        }
        
        add_action( 'admin_notices', 'wcz_no_woocommerce_notice' );
        return;
    }
    
    /**
     * Returns the main instance of WooCustomizer to prevent the need to use globals.
     *
     * @since  1.0.0
     * @return object WooCustomizer
     */
    function woocustomizer()
    {
        $instance = WooCustomizer::instance( __FILE__, WCD_PLUGIN_VERSION );
        if ( is_null( $instance->settings ) ) {
            $instance->settings = WooCustomizer_Settings::instance( $instance );
        }
        return $instance;
    }
    
    woocustomizer();
}
