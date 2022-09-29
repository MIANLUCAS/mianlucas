<?php
/**
 *Plugin Name: WooCommerce Thank You Page Customizer Premium
 *Plugin URI: https://villatheme.com/extensions/woocommerce-thank-you-page-customizer
 *Description: Drag & drop to customize your thank you page, give coupons for successful orders and repeat sales.
 *Version: 1.0.5
 *Author: VillaTheme
 *Author URI: https://villatheme.com
 *Text Domain: woocommerce-thank-you-page-customizer
 *Domain Path: /languages
 *Copyright 2018-2020 VillaTheme.com. All rights reserved.
 * Requires PHP: 7.0
 * Requires at least: 5.0
 * Tested up to: 5.6
 * WC requires at least: 4.0.0
 * WC tested up to: 4.8
 **/
if (!defined('ABSPATH')) {
    exit;
}
define('VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION', '1.0.5');
/**
 * Detect plugin. For use on Front End only.
 */
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
if (is_plugin_active('woocommerce/woocommerce.php')) {
    $init_file = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woocommerce-thank-you-page-customizer" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "define.php";
    require_once $init_file;
}

/**
 * Class WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER
 */
class WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER
{
    public function __construct()
    {
        add_action('admin_notices', array($this, 'global_note'));
    }

    /**
     * Notify if WooCommerce is not activated
     */
    function global_note()
    {
        if (!is_plugin_active('woocommerce/woocommerce.php')) {
            ?>
            <div id="message" class="error">
                <p><?php _e('Please install and activate WooCommerce to use WooCommerce Thank You Page.', 'woocommerce-thank-you-page-customizer'); ?></p>
            </div>
            <?php
            if (is_plugin_active('woocommerce-thank-you-page-customizer/woocommerce-thank-you-page-customizer.php')) {
                deactivate_plugins('woocommerce-thank-you-page-customizer/woocommerce-thank-you-page-customizer.php');
                unset($_GET['activate']);
            }
        }
    }
}

new WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER();