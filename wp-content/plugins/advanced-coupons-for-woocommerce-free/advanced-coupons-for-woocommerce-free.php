<?php

/**
 * Plugin Name: Advanced Coupons for WooCommerce Free
 * Plugin URI: https://advancedcouponsplugin.com
 * Description: Advanced Coupons for WooCommerce (Free Version) gives WooCommerce store owners extra coupon features so they can market their stores better.
 * Version: 1.3.5
 * Author: Rymera Web Co
 * Author URI: https://rymera.com.au
 * Requires at least: 5.0
 * Tested up to: 5.6
 * WC requires at least: 3.9
 * WC tested up to: 4.9.1
 *
 * Text Domain: advanced-coupons-for-woocommerce-free
 * Domain Path: /languages/
 *
 * @package ACFWF
 * @category Core
 * @author Rymera Web Co
 */

if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Model_Interface;
use ACFWF\Models\Admin_App;
use ACFWF\Models\BOGO_Deals;
use ACFWF\Models\Bootstrap;
use ACFWF\Models\Cart_Conditions;
use ACFWF\Models\Edit_Coupon;
use ACFWF\Models\Notices;
use ACFWF\Models\REST_API\API_Settings;
use ACFWF\Models\Role_Restrictions;
use ACFWF\Models\Script_Loader;
use ACFWF\Models\Third_Party_Integrations\Aelia\Currency_Switcher;
use ACFWF\Models\Third_Party_Integrations\WPML_Support;

// REST API
use ACFWF\Models\Upsell;

// Third party integrations
use ACFWF\Models\URL_Coupons;
use ACFWF\Models\WC_Admin_Notes;

/**
 * Register plugin autoloader.
 *
 * @since 1.0
 *
 * @param $class_name string Name of the class to load.
 */
spl_autoload_register(function ($class_name) {

    if (strpos($class_name, 'ACFWF\\') === 0) { // Only do autoload for our plugin files

        $class_file = str_replace(array('\\', 'ACFWF' . DIRECTORY_SEPARATOR), array(DIRECTORY_SEPARATOR, ''), $class_name) . '.php';

        require_once plugin_dir_path(__FILE__) . $class_file;
    }
});

/**
 * The main plugin class.
 */
class ACFWF extends Abstract_Main_Plugin_Class
{

    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */

    /**
     * Single main instance of Plugin ACFWF plugin.
     *
     * @since 1.0
     * @access private
     * @var ACFWF
     */
    private static $_instance;

    /**
     * Array of missing external plugins that this plugin is depends on.
     *
     * @since 1.0
     * @access private
     * @var array
     */
    private $_failed_dependencies;

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * ACFWF constructor.
     *
     * @since 1.0
     * @access public
     */
    public function __construct()
    {

        register_deactivation_hook(__FILE__, array($this, 'general_deactivation_code'));

        if ($this->_check_plugin_dependencies() !== true) {

            // Display notice that plugin dependency ( WooCommerce ) is not present.
            add_action('admin_notices', array($this, 'missing_plugin_dependencies_notice'));
        } elseif ($this->_check_plugin_dependency_version_requirements() !== true) {

            // Display notice that some dependent plugin did not meet the required version.
            add_action('admin_notices', array($this, 'invalid_plugin_dependency_version_notice'));
        } elseif (!$this->_validate_acfwp_plugin_version()) {

            // Display notice if old ACFW plugin is the one active instead of ACFWP.
            add_action('admin_notices', array($this, 'old_acfw_plugin_is_active_notice'));
        } else {

            // Lock 'n Load
            $this->_initialize_plugin_components();
            $this->_run_plugin();
        }
    }

    /**
     * Ensure that only one instance of Advanced Coupons for WooCommerce is loaded or can be loaded (Singleton Pattern).
     *
     * @since 1.0
     * @access public
     *
     * @return ACFWF
     */
    public static function get_instance()
    {

        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Check for external plugin dependencies.
     *
     * @since 1.0
     * @access private
     *
     * @return mixed Array if there are missing plugin dependencies, True if all plugin dependencies are present.
     */
    private function _check_plugin_dependencies()
    {

        // Makes sure the plugin is defined before trying to use it
        if (!function_exists('is_plugin_active')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $this->failed_dependencies = array();

        if (!is_plugin_active('woocommerce/woocommerce.php')) {

            $this->failed_dependencies[] = array(
                'plugin-key'       => 'woocommerce',
                'plugin-name'      => 'WooCommerce', // We don't translate this coz this is the plugin name
                'plugin-base-name' => 'woocommerce/woocommerce.php',
            );
        }

        return !empty($this->failed_dependencies) ? $this->failed_dependencies : true;
    }

    /**
     * Check plugin dependency version requirements.
     *
     * @since 1.0
     * @access private
     *
     * @return boolean True if plugin dependency version requirement is meet, False otherwise.
     */
    private function _check_plugin_dependency_version_requirements()
    {

        return true;
    }

    /**
     * Validate ACFWP plugin version to make sure at least version 2 or higher is running.
     * When old ACFW plugin is running, modules added by ACFWF will not run at all.
     *
     * @since 1.0
     * @access private
     *
     * @return bool True if valid, false otherwise.
     */
    private function _validate_acfwp_plugin_version()
    {

        if (!function_exists('is_plugin_active')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $acfwp_basename = 'advanced-coupons-for-woocommerce/advanced-coupons-for-woocommerce.php';

        // if ACFWP plugin is not active, then don't proceed validation.
        if (!is_plugin_active($acfwp_basename)) {
            return true;
        }

        if (!function_exists('get_plugin_data')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $acfwp_plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $acfwp_basename);

        return version_compare($acfwp_plugin_data['Version'], '2.0', ">=");
    }

    /**
     * Add notice to notify users that some plugin dependencies of this plugin is missing.
     *
     * @since 1.0
     * @access public
     */
    public function missing_plugin_dependencies_notice()
    {

        if (!empty($this->failed_dependencies)) {

            $admin_notice_msg = '';

            foreach ($this->failed_dependencies as $failed_dependency) {

                $failed_dep_plugin_file = trailingslashit(WP_PLUGIN_DIR) . plugin_basename($failed_dependency['plugin-base-name']);

                if (file_exists($failed_dep_plugin_file)) {
                    $failed_dep_install_text = '<a href="' . wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $failed_dependency['plugin-base-name'] . '&amp;plugin_status=all&amp;s', 'activate-plugin_' . $failed_dependency['plugin-base-name']) . '" title="' . __('Activate this plugin', 'advanced-coupons-for-woocommerce-free') . '" class="edit">' . __('Click here to activate &rarr;', 'advanced-coupons-for-woocommerce-free') . '</a>';
                } else {
                    $failed_dep_install_text = '<a href="' . wp_nonce_url('update.php?action=install-plugin&amp;plugin=' . $failed_dependency['plugin-key'], 'install-plugin_' . $failed_dependency['plugin-key']) . '" title="' . __('Install this plugin', 'advanced-coupons-for-woocommerce-free') . '">' . __('Click here to install from WordPress.org repo &rarr;', 'advanced-coupons-for-woocommerce-free') . '</a>';
                }

                $admin_notice_msg .= sprintf(__('<br/>Please ensure you have the <a href="%1$s" target="_blank">%2$s</a> plugin installed and activated.<br/>', 'advanced-coupons-for-woocommerce-free'), 'http://wordpress.org/plugins/' . $failed_dependency['plugin-key'] . '/', $failed_dependency['plugin-name']);
                $admin_notice_msg .= $failed_dep_install_text . '<br/>';
            }?>

            <div class="notice notice-error">
                <p>
                    <?php _e('<b>Advanced Coupons for WooCommerce Free</b> plugin missing dependency.<br/>', 'advanced-coupons-for-woocommerce-free');?>
                    <?php echo $admin_notice_msg; ?>
                </p>
            </div>

<?php }
    }

    /**
     * Add notice to notify user that some plugin dependencies did not meet the required version for the current version of this plugin.
     *
     * @since 1.0
     * @access public
     */
    public function invalid_plugin_dependency_version_notice()
    {
        // Notice message here...
    }

    /**
     * Add notice to notify users that they are still using the old version of ACFW plugin which is not yet compatible with ACFWF.
     *
     * @since 1.0
     * @access public
     */
    public function old_acfw_plugin_is_active_notice()
    {

        global $wp;

        // only show notice to administrator or equivalent roles.
        if (!current_user_can('activate_plugins')) {
            return;
        }

        $plugin_constants = Plugin_Constants::get_instance($this);
        $plugin_basename  = 'advanced-coupons-for-woocommerce/advanced-coupons-for-woocommerce.php';
        $deactivate_url   = wp_nonce_url('plugins.php?action=deactivate&amp;plugin=' . $plugin_basename . '&amp;plugin_status=all&amp;s', 'deactivate-plugin_' . $plugin_basename);

        if (is_multisite()) {

            $current_url          = add_query_arg($wp->query_string, '?', network_home_url($wp->request));
            $update_url           = wp_nonce_url($current_url . 'wp-admin/network/update.php?action=upgrade-plugin&plugin=' . $plugin_basename, 'upgrade-plugin_' . $plugin_basename);
            $license_settings_url = $current_url . "wp-admin/network/admin.php?page=acfw-ms-license-settings";
            $update_data          = get_site_option('acfw_option_update_data');
            $license_key          = get_site_option('acfw_slmw_license_key');
            $is_license_active    = get_site_option('acfw_license_activated') === 'yes';
            $deactivate_url       = is_plugin_active_for_network($plugin_basename) ? wp_nonce_url($current_url . 'wp-admin/network/plugins.php?action=deactivate&amp;plugin=' . $plugin_basename . '&amp;plugin_status=all&amp;s', 'deactivate-plugin_' . $plugin_basename) : $deactivate_url;
        } else {

            $update_url           = wp_nonce_url('update.php?action=upgrade-plugin&plugin=' . $plugin_basename, 'upgrade-plugin_' . $plugin_basename);
            $license_settings_url = admin_url() . 'admin.php?page=wc-settings&tab=acfw_settings&section=acfw_slmw_settings_section';
            $update_data          = get_option('acfw_option_update_data');
            $license_key          = get_option('acfw_slmw_license_key');
            $is_license_active    = get_option('acfw_license_activated') === 'yes';
        }

        /**
         * If there's no update data available, then we force ping for new version.
         * The code below will trigger \ACFW\Models\SLMW\Update::update_check method of old plugin.
         */
        if ($is_license_active && $license_key && !$update_data) {

            // First we make sure to run wp_update_plugins function to make sure update_plugins transient is present.
            wp_update_plugins();

            // We make sure that the ping is triggered as wp_update_plugins will bail if recently checked.
            set_site_transient('update_plugins', get_site_transient('update_plugins'));

            // force refresh if we got a new update data.
            if (get_option('acfw_option_update_data') || (is_multisite() && get_site_option('acfw_option_update_data'))) {
                header("Refresh:0");
            }

        }

        include $plugin_constants->VIEWS_ROOT_PATH() . 'notices/view-old-acfw-plugin-notice.php';
    }

    /**
     * Function that gets executed always whether dependency are present/valid or not.
     * There will be instances that a plugin is activated but the activation code is not executed, how? if dependencies are not present.
     * WP Plugins doesn't requires an activation and deactivation callbacks. If none is provided ( or none is presented coz of failed dependency ) then it continues activating the plugin.
     * Same can be said with deactivation procedure. That's why we need this function.
     *
     * @since 1.0
     * @access public
     *
     * @global wpdb $wpdb Object that contains a set of functions used to interact with a database.
     *
     * @param boolean $network_wide Flag that determines whether the plugin has been activated network wid ( on multi site environment ) or not.
     */
    public function general_deactivation_code($network_wide)
    {

        // Delete the flag that determines if plugin activation code is triggered
        global $wpdb;

        // check if it is a multisite network
        if (is_multisite()) {

            // check if the plugin has been activated on the network or on a single site
            if ($network_wide) {

                // get ids of all sites
                $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    delete_option('acfwf_activation_code_triggered');
                    delete_site_option(Plugin_Constants::INSTALLED_VERSION);
                }

                restore_current_blog();
            } else {

                delete_option('acfwf_activation_code_triggered'); // activated on a single site, in a multi-site
                delete_site_option(Plugin_Constants::INSTALLED_VERSION);
            }
        } else {

            delete_option('acfwf_activation_code_triggered'); // activated on a single site
            delete_option(Plugin_Constants::INSTALLED_VERSION);
        }
    }

    /**
     * Initialize plugin components.
     *
     * @since 1.0
     * @access private
     */
    private function _initialize_plugin_components()
    {

        // helpers
        $plugin_constants = Plugin_Constants::get_instance($this);
        $helper_functions = Helper_Functions::get_instance($this, $plugin_constants);

        // modules
        $url_coupons      = URL_Coupons::get_instance($this, $plugin_constants, $helper_functions);
        $role_restriction = Role_Restrictions::get_instance($this, $plugin_constants, $helper_functions);
        $cart_conditions  = Cart_Conditions::get_instance($this, $plugin_constants, $helper_functions);
        $settings         = Admin_App::get_instance($this, $plugin_constants, $helper_functions);
        $edit_coupon      = Edit_Coupon::get_instance($this, $plugin_constants, $helper_functions, $cart_conditions);
        $bogo_deals       = BOGO_Deals::get_instance($this, $plugin_constants, $helper_functions);
        $notices          = Notices::get_instance($this, $plugin_constants, $helper_functions);
        $upsell           = Upsell::get_instance($this, $plugin_constants, $helper_functions);
        $admin_notes      = WC_Admin_Notes::get_instance($this, $plugin_constants, $helper_functions);

        // third party integration
        $currency_switcher = Currency_Switcher::get_instance($this, $plugin_constants, $helper_functions);
        WPML_Support::get_instance($this, $plugin_constants, $helper_functions);

        // boostrap args
        $initiables     = array($cart_conditions, $edit_coupon, $bogo_deals, $notices, $upsell, $admin_notes);
        $activatables   = array($edit_coupon, $notices, $admin_notes);
        $deactivatables = array();

        Bootstrap::get_instance($this, $plugin_constants, $helper_functions, $activatables, $initiables, $deactivatables);
        Script_Loader::get_instance($this, $plugin_constants, $helper_functions);

        API_Settings::get_instance($this, $plugin_constants, $helper_functions);
    }

    /**
     * Run the plugin. ( Runs the various plugin components ).
     *
     * @since 1.0
     * @access private
     */
    private function _run_plugin()
    {

        foreach ($this->_all_models as $model) {
            if ($model instanceof Model_Interface) {
                $model->run();
            }
        }

    }
}

/**
 * Returns the main instance of ACFWF to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return ACFWF Main instance of the plugin.
 */
function ACFWF()
{

    return ACFWF::get_instance();
}

// Let's Roll!
$GLOBALS['ACFWF'] = ACFWF();
