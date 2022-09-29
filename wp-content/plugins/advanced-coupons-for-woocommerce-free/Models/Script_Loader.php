<?php
namespace ACFWF\Models;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Model_Interface;

if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

/**
 * Model that houses the logic of loading plugin scripts.
 * Private Model.
 *
 * @since 1.0
 */
class Script_Loader implements Model_Interface
{

    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */

    /**
     * Property that holds the single main instance of Bootstrap.
     *
     * @since 1.0
     * @access private
     * @var Bootstrap
     */
    private static $_instance;

    /**
     * Model that houses all the plugin constants.
     *
     * @since 1.0
     * @access private
     * @var Plugin_Constants
     */
    private $_constants;

    /**
     * Property that houses all the helper functions of the plugin.
     *
     * @since 1.0
     * @access private
     * @var Helper_Functions
     */
    private $_helper_functions;

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * Class constructor.
     *
     * @since 1.0
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     */
    public function __construct(Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions)
    {

        $this->_constants        = $constants;
        $this->_helper_functions = $helper_functions;

        $main_plugin->add_to_all_plugin_models($this);

    }

    /**
     * Ensure that only one instance of this class is loaded or can be loaded ( Singleton Pattern ).
     *
     * @since 1.0
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     * @return Bootstrap
     */
    public static function get_instance(Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions)
    {

        if (!self::$_instance instanceof self) {
            self::$_instance = new self($main_plugin, $constants, $helper_functions);
        }

        return self::$_instance;

    }

    /**
     * Register scripts to be used on the backend.
     *
     * @since 1.0
     * @access private
     */
    private function _register_backend_scripts()
    {

        $acfw_backend_styles = apply_filters('acfw_register_backend_styles', array(

            'vex'                       => array(
                'src'   => $this->_constants->JS_ROOT_URL() . 'lib/vex/vex.css',
                'deps'  => array(),
                'ver'   => Plugin_Constants::VERSION,
                'media' => 'all',
            ),

            'vex-theme-plain'           => array(
                'src'   => $this->_constants->JS_ROOT_URL() . 'lib/vex/vex-theme-plain.css',
                'deps'  => array(),
                'ver'   => Plugin_Constants::VERSION,
                'media' => 'all',
            ),

            // edit advanced coupon CSS
            'acfw-edit-advanced-coupon' => array(
                'src'   => $this->_constants->JS_ROOT_URL() . 'app/edit-advanced-coupon/dist/edit-advanced-coupon.css',
                'deps'  => array('jquery-ui-style', 'vex', 'vex-theme-plain'),
                'ver'   => Plugin_Constants::VERSION,
                'media' => 'all',
            ),

            // jQuery timepicker
            'jquery-timepicker'         => array(
                'src'   => $this->_constants->JS_ROOT_URL() . 'lib/timepicker/jquery.timepicker.min.css',
                'deps'  => array(),
                'ver'   => Plugin_Constants::VERSION,
                'media' => 'all',
            ),

            // ACFW reports CSS
            'acfw-settings'             => array(
                'src'   => $this->_constants->CSS_ROOT_URL() . 'acfw-settings.css',
                'deps'  => array('jquery-timepicker'),
                'ver'   => Plugin_Constants::VERSION,
                'media' => 'all',
            ),

            // ACFW Notices
            'acfw-notices'              => array(
                'src'   => $this->_constants->CSS_ROOT_URL() . 'acfw-notices.css',
                'deps'  => array('vex', 'vex-theme-plain'),
                'ver'   => Plugin_Constants::VERSION,
                'media' => 'all',
            ),

        ));

        $acfw_backend_scripts = apply_filters('acfw_register_backend_scripts', array(

            // vex js
            'vex'                       => array(
                'src'    => $this->_constants->JS_ROOT_URL() . 'lib/vex/vex.combined.min.js',
                'deps'   => array('jquery'),
                'ver'    => Plugin_Constants::VERSION,
                'footer' => true,
            ),

            // edit advanced coupon JS
            'acfw-edit-advanced-coupon' => array(
                'src'    => $this->_constants->JS_ROOT_URL() . 'app/edit-advanced-coupon/dist/edit-advanced-coupon.js',
                'deps'   => array('jquery-ui-core', 'jquery-ui-datepicker', 'vex'),
                'ver'    => Plugin_Constants::VERSION,
                'footer' => true,
            ),

            // jQuery timepicker
            'jquery-timepicker'         => array(
                'src'    => $this->_constants->JS_ROOT_URL() . 'lib/timepicker/jquery.timepicker.min.js',
                'deps'   => array('jquery'),
                'ver'    => Plugin_Constants::VERSION,
                'footer' => true,
            ),

            // ACFW Settings JS
            'acfw-settings'             => array(
                'src'    => $this->_constants->JS_ROOT_URL() . 'acfw-settings.js',
                'deps'   => array('jquery', 'jquery-ui-datepicker', 'jquery-timepicker'),
                'ver'    => Plugin_Constants::VERSION,
                'footer' => true,
            ),

            // ACFW Notices
            'acfw-notices'              => array(
                'src'    => $this->_constants->JS_ROOT_URL() . 'acfw-notices.js',
                'deps'   => array('jquery', 'vex'),
                'ver'    => Plugin_Constants::VERSION,
                'footer' => true,
            ),

        ));

        // register backend styles via a loop
        foreach ($acfw_backend_styles as $id => $style) {
            wp_register_style($id, $style['src'], $style['deps'], $style['ver'], $style['media']);
        }

        // register backend scripts via a loop
        foreach ($acfw_backend_scripts as $id => $script) {
            wp_register_script($id, $script['src'], $script['deps'], $script['ver'], $script['footer']);
        }
    }

    /**
     * Load backend js and css scripts.
     *
     * @since 1.0
     * @access public
     *
     * @param string $handle Unique identifier of the current backend page.
     */
    public function load_backend_scripts($handle)
    {

        global $post;

        // register all scripts required in the backend.
        $this->_register_backend_scripts();

        $screen = get_current_screen();
        $locale = localeconv();

        $post_type = get_post_type();
        if (!$post_type && isset($_GET['post_type'])) {
            $post_type = $_GET['post_type'];
        }

        do_action('acfw_before_load_backend_scripts', $screen, $post_type);

        // edit coupon screen
        if ('post' == $screen->base && 'shop_coupon' == $screen->id && 'shop_coupon' == $post_type) {

            $decimal_separator = wc_get_price_decimal_separator();
            $decimal_separator = $decimal_separator ? $decimal_separator : $locale['decimal_point'];

            wp_enqueue_style('vex');
            wp_enqueue_style('acfw-edit-advanced-coupon');

            wp_enqueue_script('acfw-edit-advanced-coupon');
            wp_add_inline_script('acfw-edit-advanced-coupon', 'vex.defaultOptions.className = "vex-theme-plain"', 'after');
            wp_localize_script('acfw-edit-advanced-coupon', 'acfw_edit_coupon',
                apply_filters('acfw_edit_advanced_coupon_localize', array(
                    'modules'                     => $this->_helper_functions->get_active_modules(),
                    'currency_symbol'             => get_woocommerce_currency_symbol(),
                    'empty_cart_conditions_field' => __('This is an empty condition group, just waiting for you to add some conditions to it…', 'advanced-coupons-for-woocommerce-free'),
                    'no_condition_group_msg'      => __('No condition group', 'advanced-coupons-for-woocommerce-free'),
                    'no_products_added'           => __('No products added', 'advanced-coupons-for-woocommerce-free'),
                    'no_categories_added'         => __('No categories added', 'advanced-coupons-for-woocommerce-free'),
                    'remove_cgroup_label'         => __('Remove condition group', 'advanced-coupons-for-woocommerce-free'),
                    'remove_cfield_label'         => __('Remove condition field', 'advanced-coupons-for-woocommerce-free'),
                    'fill_form_propery_error_msg' => __('Please fill the form properly', 'advanced-coupons-for-woocommerce-free'),
                    'cart_conditions_invalid'     => __('The set cart conditions data or part of it is invalid. Please fill the form properly.', 'advanced-coupons-for-woocommerce-free'),
                    'cart_notice_url_invalid'     => __('The value set in "Non-qualifying button URL" is not a valid url.', 'advanced-coupons-for-woocommerce-free'),
                    'unpublished_coupon_save'     => __("You saved the data on an unpublished coupon. To make sure your changes won't be lost, the coupon will need to be saved. The page will now refresh.", 'advanced-coupons-for-woocommerce-free'),
                    'bogo_deals_save_error_msg'   => __("Error on saving. Please fill up the conditions, deals and type properly.", 'advanced-coupons-for-woocommerce-free'),
                    'duplicate_product'           => __("Product selected have already been added.", 'advanced-coupons-for-woocommerce-free'),
                    'duplicate_category'          => __("Category selected have already been added.", 'advanced-coupons-for-woocommerce-free'),
                    'within_a_period_label'       => __('No. of previous days', 'advanced-coupons-for-woocommerce-free'),
                    'number_of_orders_label'      => __('No. of orders', 'advanced-coupons-for-woocommerce-free'),
                    'add_product_label'           => __('Add Product', 'advanced-coupons-for-woocommerce-free'),
                    'add_prod_cat_label'          => __('Add Category', 'advanced-coupons-for-woocommerce-free'),
                    'no_shipping_overrides_added' => __('No shipping overrides added', 'advanced-coupons-for-woocommerce-free'),
                    'no_shipping_overrides_save'  => __('No shipping overrides to save.', 'advanced-coupons-for-woocommerce-free'),
                    'condition_label'             => __('Condition', 'advanced-coupons-for-woocommerce-free'),
                    'shipping_zone_already_added' => __("Selected shipping zone is already added.", 'advanced-coupons-for-woocommerce-free'),
                    'fail_add_condition_field'    => __("Failed to add condition field.", 'advanced-coupons-for-woocommerce-free'),
                    'product_table_buttons'       => array(
                        'add'    => __('Add', 'advanced-coupons-for-woocommerce-free'),
                        'edit'   => __('Edit', 'advanced-coupons-for-woocommerce-free'),
                        'cancel' => __('Cancel', 'advanced-coupons-for-woocommerce-free'),
                    ),
                    'logic_field_options'         => array(
                        'and' => __('AND', 'advanced-coupons-for-woocommerce-free'),
                        'or'  => __('OR', 'advanced-coupons-for-woocommerce-free'),
                    ),
                    'bogo_form_fields'            => array(
                        'product_in_cart'     => __('Product in cart', 'advanced-coupons-for-woocommerce-free'),
                        'product_cat_in_cart' => __('Product category in cart', 'advanced-coupons-for-woocommerce-free'),
                        'type_to_search'      => __('Type to search', 'advanced-coupons-for-woocommerce-free'),
                        'quantity'            => __('Quantity', 'advanced-coupons-for-woocommerce-free'),
                        'product'             => __('Product', 'advanced-coupons-for-woocommerce-free'),
                        'product_cat'         => __('Product Category', 'advanced-coupons-for-woocommerce-free'),
                    ),
                    'condition_field_options'     => array(
                        'exactly'   => __('EXACTLY', 'advanced-coupons-for-woocommerce-free'),
                        'anyexcept' => __('ANYTHING EXCEPT', 'advanced-coupons-for-woocommerce-free'),
                        'morethan'  => __('MORE THAN', 'advanced-coupons-for-woocommerce-free'),
                        'lessthan'  => __('LESS THAN', 'advanced-coupons-for-woocommerce-free'),
                    ),
                    'discount_field_options'      => array(
                        'override'   => __('Override price', 'advanced-coupons-for-woocommerce-free'),
                        'percent'    => __('Percentage discount', 'advanced-coupons-for-woocommerce-free'),
                        'fixed'      => __('Fixed discount', 'advanced-coupons-for-woocommerce-free'),
                        'nodiscount' => __('No discount', 'advanced-coupons-for-woocommerce-free'),
                    ),
                    'decimal_separator'           => $decimal_separator,
                    'price_decimals'              => wc_get_price_decimals(),
                    'post_status'                 => $post ? get_post_status($post) : '',
                    'user_role_options'           => $this->_helper_functions->get_default_allowed_user_roles(),
                    'cart_condition_fields'       => array(),
                ))
            );

        }

        if ('woocommerce_page_wc-settings' === $screen->base && isset($_GET['tab']) && 'acfw_settings' == $_GET['tab']) {

            wp_enqueue_style('acfw-settings');
            wp_enqueue_script('acfw-settings');

        }

        // enqueue script to replace icons in wc-admin marketing.
        if ('woocommerce_page_wc-admin' === $screen->id || 'edit-shop_coupon' === $screen->id) {
            wp_enqueue_script('acfw-wc-admin-icons', $this->_constants->JS_ROOT_URL() . 'acfw-wc-admin-icons.js', array('jquery'), Plugin_Constants::VERSION, true);
            wp_localize_script('acfw-wc-admin-icons', 'acfwAdminIcons', array(
                'imgUrl' => $this->_constants->IMAGES_ROOT_URL(),
            ));
        }

        do_action('acfw_after_load_backend_scripts', $screen, $post_type);
    }

    /**
     * Load frontend js and css scripts.
     *
     * @since 1.0
     * @access public
     */
    public function load_frontend_scripts()
    {
    }

    /**
     * Execute plugin script loader.
     *
     * @inherit ACFWF\Interfaces\Model_Interface
     *
     * @since 1.0
     * @access public
     */
    public function run()
    {

        add_action('admin_enqueue_scripts', array($this, 'load_backend_scripts'), 10, 1);
        add_action('wp_enqueue_scripts', array($this, 'load_frontend_scripts'));

    }

}
