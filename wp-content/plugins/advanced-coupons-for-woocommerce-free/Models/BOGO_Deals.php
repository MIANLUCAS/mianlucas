<?php
namespace ACFWF\Models;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Initializable_Interface;
use ACFWF\Interfaces\Model_Interface;
use ACFWF\Models\Objects\Advanced_Coupon;

if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

/**
 * Model that houses the logic of extending the coupon system of woocommerce.
 * It houses the logic of handling coupon url.
 * Public Model.
 *
 * @since 1.0
 */
class BOGO_Deals implements Model_Interface, Initializable_Interface
{

    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */

    /**
     * Property that holds the single main instance of URL_Coupon.
     *
     * @since 1.0
     * @access private
     * @var BOGO_Deals
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

    /**
     * Boolean property that tells if cart totals has been calculated already or not.
     *
     * @since 1.0
     * @access private
     * @var bool
     */
    private $_totals_calculated = false;

    /**
     * List of coupon conditions.
     *
     * @since 1.0
     * @access private
     * @var array
     */
    private $_conditions = array();

    /**
     * List of condition ids.
     *
     * @since 1.0
     * @access private
     * @var array
     */
    private $_cond_ids = array();

    /**
     * List of coupon deal cart item keys.
     *
     * @since 1.0
     * @access private
     * @var array
     */
    private $_deals = array();

    /**
     * List of products to display on coupon cart total row.
     *
     * @since 1.0
     * @access private
     * @var array
     */
    private $_price_display = array();

    /**
     * List of item quantity entries for either condition (trigger) and deal (apply) items.
     * Acts as a temporary database during calculation for all items marked for either condition or deal and its relative quantity.
     *
     * @since 1.3.5
     * @access private
     * @var array
     */
    private $_item_quantity_entries = array();

    /**
     * List of allowed item quantity entries but not yet added to the cart. This is similar to $this->_item_quantity_entries property data.
     *
     * @since 1.3.5
     * @access private
     * @var array
     */
    private $_allowed_item_entries = array();

    /**
     * Holds the current coupon being processed on implementation.
     *
     * @since 1.3.5
     * @access private
     * @var string
     */
    private $_current_coupon = '';

    private $_coupons = array();

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
        $main_plugin->add_to_public_models($this);

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
     * @return BOGO_Deals
     */
    public static function get_instance(Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions)
    {

        if (!self::$_instance instanceof self) {
            self::$_instance = new self($main_plugin, $constants, $helper_functions);
        }

        return self::$_instance;

    }

    /**
     * Sanitize conditions/deals product data.
     *
     * @since 1.0
     * @access private
     *
     * @param array $data Product data.
     * @return array Sanitized product data.
     */
    private function _sanitize_product_data($data, $type)
    {

        $sanitized = apply_filters('acfw_sanitize_bogo_deals_data', array(), $data, $type);

        // if sanitized via filter then return early.
        if (is_array($sanitized) && !empty($sanitized)) {
            return $sanitized;
        }

        // default sanitization script.
        if (is_array($data)) {

            foreach ($data as $key => $row) {

                if (!isset($row['product_id']) || !isset($row['quantity'])) {
                    continue;
                }

                $sanitized[$key] = array(
                    'product_id'    => intval($row['product_id']),
                    'quantity'      => intval($row['quantity']) > 1 ? absint($row['quantity']) : 1,
                    'product_label' => sanitize_text_field($row['product_label']),
                );

                if (isset($row['discount_type'])) {
                    $sanitized[$key]['discount_type'] = sanitize_text_field($row['discount_type']);
                }

                if (isset($row['discount_value'])) {
                    $sanitized[$key]['discount_value'] = (float) wc_format_decimal($row['discount_value']);
                }

                if (isset($row['condition'])) {
                    $sanitized[$key]['condition'] = sanitize_text_field($row['condition']);
                }

                if (isset($row['condition_label'])) {
                    $sanitized[$key]['condition_label'] = sanitize_text_field($row['condition_label']);
                }

            }
        }

        return $sanitized;
    }

    /**
     * Save BOGO Deals.
     *
     * @since 1.0
     * @access private
     *
     * @param int   $coupon_id  Coupon ID.
     * @param array $conditions Conditions list.
     * @param array $deals      Dealslist.
     * @return mixed WP_Error on failure, otherwise the coupon id.
     */
    private function _save_bogo_deals($coupon_id, $bogo_deals)
    {

        return update_post_meta($coupon_id, Plugin_Constants::META_PREFIX . 'bogo_deals', $bogo_deals);
    }

    /**
     * Get valid item quantities of given product IDs that are currently present in the cart.
     *
     * @since 1.0
     * @access private
     *
     * @param array  $product_ids Product IDs list.
     * @param string $cond_id     Condition id.
     * @param bool   $variation   True if ids can be variation, false otherwise.
     * @return array Cart item quantities.
     */
    public function get_quantities_of_condition_products_in_cart($product_ids, $cond_id, $variation = false)
    {

        $cart_quantities = array();

        // get quantities of each product in the cart that is present in the condition.
        foreach (WC()->cart->get_cart_contents() as $cart_item) {

            if (!$this->is_item_valid($cart_item)) {
                continue;
            }

            $cart_id = $cart_item['key'];
            $id      = $variation && isset($cart_item['variation_id']) && $cart_item['variation_id'] ? $cart_item['variation_id'] : $cart_item['product_id'];
            $id      = apply_filters('acfw_filter_cart_item_product_id', $id);
            $key     = array_search($id, $product_ids);

            if (false === $key || isset($cart_item['acfw_add_product']) || isset($cart_item['acfw_bogo_deals'])) {
                continue;
            }

            if (!isset($cart_quantities[$id])) {
                $cart_quantities[$id] = 0;
            }

            $cart_quantities[$id] += $this->calculate_cart_item_spare_quantity($cart_item);

            $this->create_trigger_entry($cart_id, $cond_id);
        }

        return $cart_quantities;
    }

    /**
     * Set default values to BOGO notice settings.
     *
     * @since 1.1.0
     * @access private
     */
    private function _set_notice_settings_default_values()
    {

        // set notice url to shop page. NOTE: remove this on next update.
        if (version_compare(get_option(Plugin_Constants::INSTALLED_VERSION, false), Plugin_Constants::VERSION, '!=')) {
            update_option(Plugin_Constants::BOGO_DEALS_NOTICE_BTN_URL, get_permalink(wc_get_page_id('shop')));
        }

        if (!get_option(Plugin_Constants::BOGO_DEALS_DEFAULT_VALUES) === 'yes') {
            return;
        }

        if (get_option(Plugin_Constants::BOGO_DEALS_NOTICE_MESSAGE, 'no_value') === 'no_value') {
            update_option(Plugin_Constants::BOGO_DEALS_NOTICE_MESSAGE, __("Your current cart is eligible to redeem deals", 'advanced-coupons-for-woocommerce-free'));
        }

        if (get_option(Plugin_Constants::BOGO_DEALS_NOTICE_BTN_TEXT, 'no_value') === 'no_value') {
            update_option(Plugin_Constants::BOGO_DEALS_NOTICE_BTN_TEXT, __("View Deals", 'advanced-coupons-for-woocommerce-free'));
        }

        if (get_option(Plugin_Constants::BOGO_DEALS_NOTICE_TYPE, 'no_value') === 'no_value') {
            update_option(Plugin_Constants::BOGO_DEALS_NOTICE_TYPE, 'notice');
        }

        update_option('acfw_bogo_deals_default_values_set', 'yes');
    }

    /**
     * Apply deals list shortcode.
     *
     * @since 1.3
     * @since 1.0 Disable the shortcode by returning an empty string.
     *
     * @param array $atts List of shortcode attributes.
     * @return string Products shortcode content.
     */
    public function apply_deals_list_shortcode($atts)
    {
        return '';
    }

    /*
    |--------------------------------------------------------------------------
    | Implementation related functions.
    |--------------------------------------------------------------------------
     */

    /**
     * Implement BOGO Deals for all applied coupon in the cart.
     *
     * @since 1.0
     * @access public
     */
    public function implement_bogo_deals()
    {
        $processed_coupons = array_map(function ($c) {
            return $c->get_code();
        }, $this->_coupons);

        // Loop through each applied coupon and get matched trigger and apply products for each.
        // Items are then added as entries on $this->_item_quantity_entries property.
        if (empty($processed_coupons) || count(WC()->cart->get_applied_coupons()) > count($processed_coupons)) {

            foreach (WC()->cart->get_applied_coupons() as $code) {
                $this->_current_coupon = $code;
                $this->_apply_coupon_bogo_deal($code);
            }
        }

        // Loop through each cart item, calculate the BOGO discount and set the price
        if (!empty($this->_item_quantity_entries)) {

            foreach (WC()->cart->get_cart_contents() as $item) {

                $key   = $item['key'];
                $deals = array_filter($this->_item_quantity_entries, function ($e) use ($key) {
                    return $e['key'] === $key && 'deal' === $e['type'];
                });

                // don't proceed if there are no deal entries for the current item.
                if (empty($deals)) {
                    continue;
                }

                // calculate the total discount for the item.
                $discount = array_reduce($deals, function ($c, $d) {
                    $temp = $d['discount'] * $d['quantity'];
                    return $c + $temp;
                }, 0);

                // calculate new item price based on the total discount and set it.
                if ($discount) {
                    $price      = $this->_helper_functions->get_price($item['data']);
                    $line_total = $price * $item['quantity'];
                    $new_price  = ($line_total - $discount) / $item['quantity'];
                    $item['data']->set_price($new_price);

                    // add details to $this->_price_display property price differences on cart table.
                    $this->_price_display[$key] = array(
                        'name'      => $item['data']->get_name(),
                        'price'     => $price,
                        'new_price' => $new_price,
                    );
                }
            }
        }

        if (!empty($this->_allowed_item_entries)) {

            // Remove all eligible for deals notice that were already added on the previous run.
            $this->_remove_eligible_for_deals_notices();

            // Loop through all BOGO coupons and display eligible for deals notice if necessary.
            array_map(function ($coupon) {

                $entries = array_filter($this->_allowed_item_entries, function ($e) use ($coupon) {
                    return $coupon->get_code() == $e['coupon'];
                });

                if (!empty($entries)) {

                    $allowed_quantity = 0;

                    foreach ($entries as $entry) {
                        $cart_item = isset($entry['key']) ? WC()->cart->get_cart_item($entry['key']) : null;
                        $quantity  = $cart_item ? $this->calculate_cart_item_spare_quantity($cart_item) : $entry['quantity'];

                        if ('condition' === $entry['type'] && $quantity < $entry['quantity']) {
                            $allowed_quantity = 0;
                            break;
                        }

                        if ('deal' === $entry['type']) {
                            $allowed_quantity += $quantity;
                        }
                    }

                    if ($allowed_quantity) {
                        $this->_add_eligigible_for_deals_notice($coupon->get_code(), $allowed_quantity, $coupon);
                    }

                }

            }, $this->_coupons);
        }

    }

    /**
     * Apply BOGO Deals for coupon.
     *
     * @since 1.0
     * @access private
     *
     * @param int|string|WC_Coupon $coupon Coupon code.
     */
    private function _apply_coupon_bogo_deal($coupon)
    {

        $coupon     = $coupon instanceof Advanced_Coupon ? $coupon : new Advanced_Coupon($coupon);
        $bogo_deals = $coupon->get_advanced_prop('bogo_deals');
        $conditions = isset($bogo_deals['conditions']) ? $bogo_deals['conditions'] : array();
        $deals      = isset($bogo_deals['deals']) ? $bogo_deals['deals'] : array();
        $conds_type = isset($bogo_deals['conditions_type']) ? $bogo_deals['conditions_type'] : 'specific-products';
        $deals_type = isset($bogo_deals['deals_type']) ? $bogo_deals['deals_type'] : 'specific-products';
        $type       = isset($bogo_deals['type']) ? $bogo_deals['type'] : null;

        // skip if conditions is empty or invalid.
        if (!is_array($conditions) || empty($conditions) || !$type) {
            return;
        }

        $this->_coupons[] = $coupon;

        $matched = $this->_get_matched_deals_in_cart($deals, $deals_type, $coupon);

        // verify cart conditions validity and get condition concurrence.
        if (!$data = $this->_verify_cart_condition($conditions, $conds_type, $matched)) {
            return;
        }

        if (!$data['cond_concurrence']) {
            return;
        }

        // calculate concurrence based on type.
        $concurrence = 'once' === $type ? 1 : $data['concurrence'];

        // apply deals discount to cart.
        $this->_apply_deals_discount_to_cart($matched, $data['matched_conditions'], $concurrence, $coupon);
    }

    /**
     * Get the matched deals in the cart items.
     *
     * @since 1.0
     * @access private
     *
     * @param array           $deals      Deals data.
     * @param string          $deals_type Deal type.
     * @param Advanced_Coupon $coupon     Advanced coupon object.
     * @return array Matched deals in cart.
     */
    private function _get_matched_deals_in_cart($deals, $deals_type, $coupon)
    {

        $cart_items = WC()->cart->get_cart_contents();
        $matched    = array();

        switch ($deals_type) {

            case 'specific-products':

                foreach ($cart_items as $item) {

                    if (!$this->is_item_valid($item)) {
                        continue;
                    }

                    $key      = $item['key'];
                    $item_id  = isset($item['variation_id']) && $item['variation_id'] ? $item['variation_id'] : $item['product_id'];
                    $item_id  = apply_filters('acfw_filter_cart_item_product_id', $item_id);
                    $filtered = array_values(array_filter($deals, function ($deal) use ($item_id) {
                        return $deal['product_id'] == $item_id;
                    }));

                    if (!isset($filtered[0])) {
                        continue;
                    }

                    $deal_id  = $filtered[0]['product_id'];
                    $quantity = $filtered[0]['quantity'];

                    if (isset($matched[$deal_id])) {
                        continue;
                    }

                    $matched[$deal_id] = array(
                        'items'    => array($item_id => $this->calculate_cart_item_spare_quantity($item)),
                        'quantity' => $filtered[0]['quantity'],
                        'discount' => $filtered[0]['discount_value'],
                        'type'     => $filtered[0]['discount_type'],
                    );

                    $this->create_apply_entry($key, $deal_id);
                }

                // add missing deals in cart to matched deals list.
                foreach ($deals as $deal) {

                    if (in_array($deal['product_id'], array_column($this->_deals, 'id'))) {
                        continue;
                    }

                    $deal_id           = $deal['product_id'];
                    $matched[$deal_id] = array(
                        'items'    => array(),
                        'quantity' => $deal['quantity'],
                        'discount' => $deal['discount_value'],
                        'type'     => $deal['discount_type'],
                    );
                }

                break;

            case 'combination-products':
            case 'product-categories':
            default:
                $matched = apply_filters('acfw_get_matched_bogo_deals_in_cart', $matched, $deals, $deals_type, $coupon);
                break;
        }

        return $matched;
    }

    /**
     * Verify BOGO Deals cart condition.
     *
     * @since 1.0
     * @since 1.1.0 Add support for combination products and product categories condition types.
     * @since 1.0 Revamp implementation.
     * @access private
     *
     * @param array  $conditions     BOGO cart condition.
     * @param string $condition_type Type of condition.
     * @param array  $matched        Matched deals in cart.
     * @return int Number of concurrence.
     */
    private function _verify_cart_condition($conditions, $condition_type, $matched)
    {

        $concurrence        = 0;
        $cond_concurrence   = 0;
        $matched_conditions = array(); // reset for every coupon
        $data               = array();

        switch ($condition_type) {

            case 'specific-products':

                $product_ids = array_column($conditions, 'product_id');
                $quantities  = array_column($conditions, 'quantity', 'product_id');
                $temp        = array();
                $cc_temp     = array();

                // count the concurrence of each product listed in the conditions from the actual cart.
                foreach ($quantities as $prod_id => $quantity) {
                    $cart_quantity = array_sum($this->get_quantities_of_condition_products_in_cart(array($prod_id), $prod_id, true));
                    $temp[]        = $this->calculate_concurrence(array($prod_id), $quantity, $cart_quantity, $matched);
                    $cc_temp[]     = floor($cart_quantity / $quantity);

                    $matched_conditions[$prod_id] = array(
                        'ids'      => array($prod_id),
                        'quantity' => $quantity,
                    );
                }

                // get the minimum concurrence of both condtions and deals in cart.
                if (!empty($temp)) {
                    $concurrence = min($temp);
                }

                // get condition concurrence.
                if (!empty($cc_temp)) {
                    $cond_concurrence = min($cc_temp);
                }

                $data = array(
                    'concurrence'        => $concurrence,
                    'cond_concurrence'   => $cond_concurrence,
                    'matched_conditions' => $matched_conditions,
                );

                break;

            case 'combination-products':
            case 'product-categories':
            default:
                $data = apply_filters('acfw_bogo_deals_verify_cart_condition', array(
                    'concurrence'        => $concurrence,
                    'cond_concurrence'   => $cond_concurrence,
                    'matched_conditions' => $matched_conditions,
                ), $conditions, $condition_type, $matched);
                break;
        }

        return $data;
    }

    /**
     * Calculate condition concurrence.
     *
     * @since 1.0
     * @access public
     *
     * @param array $product_ids   Condition product ids.
     * @param int   $quantity      Condition quantity.
     * @param int   $cart_quantity Quantity of condition items in the cart.
     * @param array $matched       Matched deals in cart.
     * @return int Condition concurrence.
     */
    public function calculate_concurrence($product_ids, $quantity, $cart_quantity, $matched)
    {

        $cc              = floor($cart_quantity / $quantity); // condition concurrence.
        $min_concurrence = array();

        $quantities = array();
        foreach ($matched as $match) {

            // get ids that are shared with conditions and deals.
            $intersect = array_intersect($product_ids, array_keys($match['items']));

            // ge the total quantity of shared ids.
            $shared_q = array_reduce($intersect, function ($carry, $i) use ($match) {
                return $carry + $match['items'][$i];
            }, 0);

            $dc    = floor(array_sum($match['items']) / $match['quantity']); // discount concurrence.
            $diff  = array_sum($match['items']) - $shared_q;
            $total = $cart_quantity + $diff; // sum of condition and not shared deal quantities.

            // decrement condition and deal concurrences while calculated is greater than total.
            while ($total < ($cc * $quantity) + ($dc * $match['quantity'])) {
                if ($dc >= $cc) {
                    $dc--;
                } else {
                    $cc--;
                }

            }

            $min_concurrence[] = $cc;
        }

        return !empty($min_concurrence) ? min($min_concurrence) : 0;
    }

    /**
     * Apply deals discount in cart session.
     *
     * @since 1.0
     * @access private
     *
     * @param array $matched            Matched deals in cart.
     * @param array matched_conditions  Matched conditions in cart.
     * @param array $concurrence Number of concurrency of the deal.
     * @param Advanced_Coupon $coupon   Advanced coupon object.
     */
    private function _apply_deals_discount_to_cart($matched_deals, $matched_conditions, $concurrence, $coupon)
    {

        $cart_items          = WC()->cart->get_cart();
        $allowed             = array();
        $needed              = array();
        $condition_confirmed = false;
        $deal_confirmed      = false;

        // sort cart items by highest to lowest in terms of price.
        usort($cart_items, function ($a, $b) {
            if ($a['key'] == $b['key']) {
                return 0;
            }

            $a_price = $this->_helper_functions->get_price($a['data']);
            $b_price = $this->_helper_functions->get_price($b['data']);
            return ($a_price > $b_price) ? -1 : 1;
        });

        // get condition only items in the cart.
        $condition_only_items = array_filter($cart_items, function ($item, $key) {
            return !in_array($item['key'], array_column($this->_deals, 'key'));
        }, ARRAY_FILTER_USE_BOTH);

        // make sure condition only items are listed first.
        $cart_items = array_unique(array_merge($condition_only_items, $cart_items), SORT_REGULAR);

        // loop through all cart items and count item quantities set for condition.
        foreach ($cart_items as $item) {

            if (!$this->is_item_valid($item)) {
                continue;
            }

            $key               = $item['key'];
            $item_quantity     = $this->calculate_cart_item_spare_quantity($item); // $item['quantity'];
            $condition_entries = $this->get_item_trigger_entries($key, $coupon->get_code());

            if ($item_quantity && !empty($condition_entries)) {
                foreach ($condition_entries as $condition_entry) {

                    $cond_id   = $condition_entry['id'];
                    $condition = isset($matched_conditions[$cond_id]) ? $matched_conditions[$cond_id] : false;

                    if (!$condition) {
                        continue;
                    }

                    if (!isset($needed[$cond_id])) {
                        $needed[$cond_id] = $condition['quantity'] * $concurrence;
                    }

                    $temp                = $needed[$cond_id] - $item_quantity;
                    $quantity            = $temp <= 0 ? $needed[$cond_id] : $item_quantity;
                    $needed[$cond_id]    = max(0, $temp);
                    $condition_confirmed = true;

                    $this->add_item_quantities_entry($key, $coupon->get_code(), $cond_id, 'condition', $quantity);
                }
            }

        }

        // if there are no confirmed condition items on cart, then don't proceed applying discounts.
        if (!$condition_confirmed) {
            return;
        }

        // get condition item quantities.
        $cond_quantities = $this->_get_condition_quantities_backwards_compatibility($coupon);

        // filter matched deals data before applying discounts.
        $matched_deals = apply_filters('acfw_filter_bogo_matched_deals', $matched_deals, $cond_quantities, $concurrence, $coupon);

        // get fresh list of cart items to make sure products added via hook are included.
        $cart_items = WC()->cart->get_cart();

        // sort cart items by lowest to highest in terms of price.
        usort($cart_items, function ($a, $b) {
            if ($a['key'] == $b['key']) {
                return 0;
            }

            $a_price = $this->_helper_functions->get_price($a['data']);
            $b_price = $this->_helper_functions->get_price($b['data']);
            return ($a_price < $b_price) ? -1 : 1;
        });

        // loop through all cart items again and applicable quantities as deal discount.
        foreach ($cart_items as $item) {

            if (!$this->is_item_valid($item)) {
                continue;
            }

            $key           = $item['key'];
            $item_quantity = $this->calculate_cart_item_spare_quantity($item);
            $apply_entries = $this->get_item_apply_entries($key, $coupon->get_code());

            foreach ($apply_entries as $apply_entry) {

                $deal_id = isset($apply_entry['id']) ? $apply_entry['id'] : '';
                $deal    = $deal_id && isset($matched_deals[$deal_id]) ? $matched_deals[$deal_id] : null;

                // Process deal data when deal is matched and there is left quantity to process as deal product.
                if ($deal && $item_quantity) {

                    if (!isset($allowed[$deal_id])) {
                        $allowed[$deal_id] = $deal['quantity'] * $concurrence;
                    }

                    $temp     = $allowed[$deal_id] - $item_quantity;
                    $quantity = $temp <= 0 ? $allowed[$deal_id] : $item_quantity;
                    $price    = $this->_helper_functions->get_price($item['data']);
                    $discount = $this->_helper_functions->calculate_discount_by_type($deal['type'], $deal['discount'], $price);

                    if ($quantity && $discount) {
                        $this->add_item_quantities_entry($key, $coupon->get_code(), $deal_id, 'deal', $quantity, $discount);
                        $deal_confirmed = true;
                    }

                    $allowed[$deal_id] = max(0, $temp);
                }

                // when deal is matched but no deal products to process, we set allowed to the deal quantity multiplied by concurrence.
                if ($deal && !$item_quantity) {
                    $this->add_item_quantities_entry($key, $coupon->get_code(), $deal_id, 'deal', $deal['quantity'] * $concurrence, 0, true);
                }
            }
        }

        // if no deal items were confirmed, then unset the item quantity entries registered earlier.
        if (!$deal_confirmed) {
            $this->_unset_coupon_quantity_entries($coupon->get_code());
        }

        // loop through $allowed variable and add cart items that have less quantity needed to the allowed count.
        foreach ($allowed as $deal_id => $allowed_quantity) {
            if ($allowed_quantity) {
                $this->add_item_quantities_entry('', $coupon->get_code(), $deal_id, 'deal', $allowed_quantity, 0, true);
            }
        }

        // loop through all matched deals and add product quantities that are not yet present in cart to allowed count.
        foreach ($matched_deals as $deal_id => $matched_deal) {
            if (!isset($allowed[$deal_id])) {
                $this->add_item_quantities_entry('', $coupon->get_code(), $deal_id, 'deal', $matched_deal['quantity'] * $concurrence, 0, true);
            }
        }
    }

    /**
     * Get cart item key and quantity pairs for condition items for backwards compatibility to support
     * ACFWP version 2.4.1 and lower.
     *
     * @since 1.3.5
     * @access private
     *
     * @param Advanced_Coupon $coupon Coupon object.
     * @return array Condition quantities list.
     */
    private function _get_condition_quantities_backwards_compatibility($coupon)
    {
        if ($this->_helper_functions->is_acfwp_older_than('2.4.2')) {
            $condition_quantites = array();
            $entries             = array_filter($this->_item_quantity_entries, function ($e) use ($coupon) {
                return $coupon->get_code() === $e['coupon'] && 'condition' === $e['type'];
            });

            foreach ($entries as $entry) {
                $condition_quantites[$entry['key']] = $entry['quantity'];
            }

            return $condition_quantites;
        }

        return array();
    }

    /**
     * Display coupon session notice to inform users of eligible deals.
     *
     * @since 1.0
     * @access private
     *
     * @param string $code      Coupon code.
     * @param int    $remaining Remaining quantity allowed.
     */
    private function _add_eligigible_for_deals_notice($code, $remaining, $coupon)
    {

        // only show notice if on the cart and on checkout fragments refresh.
        $check = $this->_helper_functions->is_apply_coupon() || $this->_helper_functions->is_cart() || $this->_helper_functions->is_checkout_fragments();
        if (!apply_filters('acfw_bogo_deals_is_eligible_notice', $check, $remaining, $coupon)) {
            return;
        }

        $settings    = $coupon->get_bogo_notice_settings();
        $message     = isset($settings['message']) && $settings['message'] ? $settings['message'] : __('Your current cart is eligible to redeem deals.', 'advanced-coupons-for-woocommerce-free');
        $message     = str_replace(array('{acfw_bogo_remaining_deals_quantity}', '{acfw_bogo_coupon_code}'), array($remaining, $code), $message);
        $notice_type = isset($settings['notice_type']) && $settings['notice_type'] ? $settings['notice_type'] : 'notice';
        $button_url  = isset($settings['button_url']) && $settings['button_url'] ? $settings['button_url'] : get_permalink(wc_get_page_id('shop'));
        $button_text = isset($settings['button_text']) && $settings['button_text'] ? $settings['button_text'] : __('View Deals', 'advanced-coupons-for-woocommerce-free');
        $notice_text = sprintf('<span class="acfw-bogo-notice-text">%s <a href="%s" class="button">%s</a></span>', $message, $button_url, $button_text);

        wc_add_notice($notice_text, $notice_type, array('acfw-bogo' => true, 'coupon' => $code));
    }

    /**
     * Remove all eligible for deals notices.
     *
     * @since 1.3.5
     * @access private
     */
    private function _remove_eligible_for_deals_notices()
    {
        $all_notices = wc_get_notices();

        if (empty($all_notices)) {
            return;
        }

        foreach ($all_notices as $notice_type => $notices) {
            $all_notices[$notice_type] = array_filter($notices, function ($n) {
                return !isset($n['data']['acfw-bogo']);
            });
        }

        wc_set_notices($all_notices);
    }

    /**
     * Display discounted price on cart price column.
     *
     * @since 1.0
     * @access public
     *
     * @param string $price Item price.
     * @param array  $item  Cart item data.
     * @param string $key   Cart item key.
     * @return string Filtered item price.
     */
    public function display_discounted_price($price, $item)
    {

        $key = $item['key'];
        if (isset($this->_price_display[$key])) {
            $data  = $this->_price_display[$key];
            $price = sprintf('<del>%s</del> <span>%s</span>', wc_price($data['price']), $price);
        }

        return $price;
    }

    /**
     * Display BOGO discounts summary on the coupons cart total row.
     *
     * @since 1.0
     * @access public
     *
     * @param string    $coupon_html Coupon row html.
     * @param WC_Coupon $coupon      Coupon object.
     * @return string Filtered Coupon row html.
     */
    public function display_bogo_discount_summary($coupon_html, $coupon, $discount_amount_html)
    {

        if (!is_array($this->_price_display) || empty($this->_price_display)) {
            return $coupon_html;
        }

        $amount = WC()->cart->get_coupon_discount_amount($coupon->get_code(), WC()->cart->display_cart_ex_tax);
        if (0 == $amount) {
            $coupon_html = str_replace($discount_amount_html, '', $coupon_html);
        }

        $code    = $coupon->get_code();
        $summary = '';
        $deals   = array_filter($this->_item_quantity_entries, function ($e) use ($code) {
            return $e['coupon'] === $code && 'deal' === $e['type'];
        });

        foreach ($deals as $deal) {
            $item     = WC()->cart->get_cart_item($deal['key']);
            $template = '<li><span class="label">%s x %s:</span> <span class="discount">%s</span></li>';
            $discount = wc_price($deal['discount'] * $deal['quantity'] * -1);
            $summary .= sprintf($template, $item['data']->get_name(), $deal['quantity'], $discount);
        }

        if ($summary) {
            $coupon_html .= sprintf('<ul class="acfw-bogo-summary %s-bogo-summary" style="margin: 10px;">%s</ul>', $code, $summary);
        }

        return $coupon_html;
    }

    /**
     * Save bogo discounts to order.
     *
     * @since 1.0
     * @access public
     *
     * @param int $order_id Order id.
     */
    public function save_bogo_discounts_to_order($order_id)
    {

        if (!is_array($this->_price_display) || empty($this->_price_display)) {
            return;
        }

        update_post_meta($order_id, Plugin_Constants::ORDER_BOGO_DISCOUNTS, array_values($this->_price_display));
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX Functions
    |--------------------------------------------------------------------------
     */

    /**
     * AJAX Save Cart Conditions.
     *
     * @since 1.0
     * @access public
     */
    public function ajax_save_bogo_deals()
    {

        if (!defined('DOING_AJAX') || !DOING_AJAX) {
            $response = array('status' => 'fail', 'error_msg' => __('Invalid AJAX call', 'advanced-coupons-for-woocommerce-free'));
        } elseif (!current_user_can(apply_filters('acfw_ajax_save_bogo_deals', 'manage_woocommerce'))) {
            $response = array('status' => 'fail', 'error_msg' => __('You are not allowed to do this', 'advanced-coupons-for-woocommerce-free'));
        } elseif (!isset($_POST['coupon_id']) || !isset($_POST['conditions']) || !isset($_POST['deals']) || !isset($_POST['type'])) {
            $response = array('status' => 'fail', 'error_msg' => __('Missing required post data', 'advanced-coupons-for-woocommerce-free'));
        } else {

            // get function to use for sanitizing data.
            $conditions_type = isset($_POST['conditions_type']) ? sanitize_text_field($_POST['conditions_type']) : '';
            $deals_type      = isset($_POST['deals_type']) ? sanitize_text_field($_POST['deals_type']) : '';
            $notice_settings = isset($_POST['notice_settings']) && is_array($_POST['notice_settings']) ? $_POST['notice_settings'] : array();

            // prepare bogo deals data.
            $coupon_id  = intval($_POST['coupon_id']);
            $bogo_deals = array(
                'conditions'      => $this->_sanitize_product_data($_POST['conditions'], $conditions_type),
                'deals'           => $this->_sanitize_product_data($_POST['deals'], $deals_type),
                'conditions_type' => $conditions_type,
                'deals_type'      => $deals_type,
                'type'            => sanitize_text_field($_POST['type']),
                'notice_settings' => array(
                    'message'     => isset($notice_settings['message']) && $notice_settings['message'] ? sanitize_text_field($notice_settings['message']) : '',
                    'button_text' => isset($notice_settings['button_text']) && $notice_settings['button_text'] ? sanitize_text_field($notice_settings['button_text']) : '',
                    'button_url'  => isset($notice_settings['button_url']) && $notice_settings['button_url'] ? esc_url_raw($notice_settings['button_url']) : '',
                    'notice_type' => isset($notice_settings['notice_type']) && $notice_settings['notice_type'] ? $this->_helper_functions->sanitize_notice_type($notice_settings['notice_type']) : '',
                ),
            );

            // save bogo deals.
            $save_check = $this->_save_bogo_deals($coupon_id, $bogo_deals);

            if ($save_check) {
                $response = array('status' => 'success', 'message' => __('BOGO deals has been saved successfully!', 'advanced-coupons-for-woocommerce-free'));
            } else {
                $response = array('status' => 'fail');
            }

        }

        @header('Content-Type: application/json; charset=' . get_option('blog_charset'));
        echo wp_json_encode($response);
        wp_die();
    }

    /**
     * AJAX clear bogo deals.
     *
     * @since 1.0
     * @access public
     */
    public function ajax_clear_bogo_deals()
    {

        if (!defined('DOING_AJAX') || !DOING_AJAX) {
            $response = array('status' => 'fail', 'error_msg' => __('Invalid AJAX call', 'advanced-coupons-for-woocommerce-free'));
        } elseif (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'acfw_clear_bogo_deals') || !current_user_can(apply_filters('acfw_ajax_clear_bogo_deals', 'manage_woocommerce'))) {
            $response = array('status' => 'fail', 'error_msg' => __('You are not allowed to do this', 'advanced-coupons-for-woocommerce-free'));
        } elseif (!isset($_POST['coupon_id'])) {
            $response = array('status' => 'fail', 'error_msg' => __('Missing required post data', 'advanced-coupons-for-woocommerce-free'));
        } else {

            $coupon_id  = intval($_POST['coupon_id']);
            $bogo_deals = array();

            $save_check = $this->_save_bogo_deals($coupon_id, $bogo_deals);

            if ($save_check) {
                $response = array('status' => 'success', 'message' => __('BOGO deals has been cleared successfully!', 'advanced-coupons-for-woocommerce-free'));
            } else {
                $response = array('status' => 'fail', 'error_msg' => __('Failed on clearing or there were no changes to save.', 'advanced-coupons-for-woocommerce-free'));
            }

        }

        @header('Content-Type: application/json; charset=' . get_option('blog_charset'));
        echo wp_json_encode($response);
        wp_die();
    }

    /*
    |--------------------------------------------------------------------------
    | Utility Functions
    |--------------------------------------------------------------------------
     */

    /**
     * Create trigger entry.
     *
     * @since 1.3.5
     * @access public
     *
     * @param string $cart_item_key Cart item key.
     * @param string $condition_id  Condition ID.
     */
    public function create_trigger_entry($cart_item_key, $condition_id)
    {
        $this->_conditions[] = array(
            'key'    => $cart_item_key,
            'id'     => $condition_id,
            'coupon' => $this->_current_coupon,
        );
    }

    /**
     * Get trigger entries for a cart item key and matching coupon code.
     *
     * @since 1.3.5
     * @access public
     *
     * @param string $cart_item_key Cart item key.
     * @param string $coupon_code   Coupon code.
     * @return array List of trigger entries.
     */
    public function get_item_trigger_entries($cart_item_key, $coupon_code)
    {
        return array_filter($this->_conditions, function ($c) use ($cart_item_key, $coupon_code) {
            return $c['key'] === $cart_item_key && $c['coupon'] === $coupon_code;
        });
    }

    /**
     * Create apply entry.
     *
     * @since 1.3.5
     * @access public
     *
     * @param string $cart_item_key Cart item key.
     * @param string $deal_id       Deal ID.
     */
    public function create_apply_entry($cart_item_key, $deal_id)
    {
        $this->_deals[] = array(
            'key'    => $cart_item_key,
            'id'     => $deal_id,
            'coupon' => $this->_current_coupon,
        );
    }

    /**
     * Get apply entries for a cart item key and matching coupon code.
     *
     * @since 1.3.5
     * @access public
     *
     * @param string $cart_item_key Cart item key.
     * @param string $coupon_code   Coupon code.
     * @return array List of apply entries.
     */
    public function get_item_apply_entries($cart_item_key, $coupon_code)
    {
        return array_filter($this->_deals, function ($d) use ($cart_item_key, $coupon_code) {
            return $d['key'] === $cart_item_key && $d['coupon'] === $coupon_code;
        });
    }

    /**
     * Set deals property.
     *
     * @since 1.0
     * @access public
     *
     * @deprecated 1.3.5
     *
     * @param string $key   Property key.
     * @param mixed  $value Property value.
     */
    public function set_deals_prop($key, $value)
    {
        wc_doing_it_wrong(__METHOD__, __('BOGO_Deals::set_deals_prop method is now deprecated. Please use BOGO_Deals::create_apply_entry method instead.', 'advanced-coupons-for-woocommerce-free'), '1.3.5');

        $this->create_apply_entry($key, $value);
    }

    /**
     * Get all deals data.
     *
     * @since 1.0
     * @since 1.3.5 Modify return data to return deal ids for provided coupon. Add backwards compatibility for previous ACFWP version.
     * @access public
     *
     * @return array List of deals data.
     */
    public function get_deals_data($coupon_code = '')
    {
        $coupon_code = $coupon_code ? $coupon_code : $this->_current_coupon;
        $deals       = array_filter($this->_deals, function ($d) use ($coupon_code) {
            return $coupon_code === $d['coupon'];
        });

        // ACFWP backwards compatibility.
        if ($this->_helper_functions->is_acfwp_older_than('2.4.2')) {
            return array_column($deals, 'id', 'key');
        }

        return $deals;
    }

    /**
     * Set price display prop.
     *
     * @since 1.0
     * @access public
     *
     * @param string $key   Property key.
     * @param mixed  $value Property value.
     */
    public function set_price_display($key, $value)
    {
        $this->_price_display[$key] = $value;
    }

    /**
     * Check if the provided key is already present in price display data.
     *
     * @since 1.0
     * @since 1.3.5 Add backwards compatibility for version 2.4.1 and lower.
     * @access public
     *
     * @param string $key Property key.
     * @return bool True if exists, false otherwise.
     */
    public function isset_price_display($key)
    {
        // ACFWP backwards compatibility.
        if ($this->_helper_functions->is_acfwp_older_than('2.4.2')) {
            $entries = $this->get_item_quantity_entries($key);
            return !empty($entries);
        }

        return isset($this->_price_display[$key]);
    }

    /**
     * Get price display prop base on provided key.
     *
     * @since 1.2
     * @since 1.3.5 Add backwards compatibility for version 2.4.1 and lower.
     * @access public
     *
     * @param string $key Property key.
     * @return mixed Property value.
     */
    public function get_price_display($key)
    {
        // ACFWP backwards compatibility. Calculate quantity of the cart item already matched for conditions/deals.
        if ($this->_helper_functions->is_acfwp_older_than('2.4.2')) {
            $entries  = $this->get_item_quantity_entries($key);
            $quantity = array_sum(array_column($entries, 'quantity'));

            return array('key' => $key, 'quantity' => $quantity);
        }

        return $this->_price_display[$key];
    }

    /**
     * Check if the cart item is valid as a deal or trigger.
     *
     * @since 1.3.1
     * @access public
     *
     * @param WC_Cart_item $item Cart item object.
     * @return bool True if valid, false otherwise.
     */
    public function is_item_valid($item)
    {
        return apply_filters('acfw_bogo_is_item_valid', true, $item);
    }

    /**
     * Add item quantity entry.
     *
     * @since 1.3.5
     * @access public
     *
     * @param string $cart_item_key Cart item key.
     * @param string $coupon_code   Coupon code.
     * @param string $entry_id      Condition/Deal ID.
     * @param int    $quantity      Item quantity for condition/deal.
     * @param float  $discount      Discount value.
     * @param bool   $is_for_allowed Toggle if data needs to be added to allowed property instead.
     */
    public function add_item_quantities_entry($cart_item_key, $coupon_code, $entry_id, $type, $quantity, $discount = 0, $is_for_allowed = false)
    {
        $entry = array(
            'key'      => $cart_item_key,
            'coupon'   => $coupon_code,
            'entry_id' => $entry_id,
            'type'     => $type,
            'quantity' => $quantity,
            'discount' => $discount,
        );

        if ($is_for_allowed) {
            $this->_allowed_item_entries[] = $entry;
        } else {
            $this->_item_quantity_entries[] = $entry;
        }

    }

    /**
     * Get item quantity entries for a single cart item.
     *
     * @since 1.3.5
     * @access public
     *
     * @param string $cart_item_key Cart item key.
     * @return array List of item quantity entries.
     */
    public function get_item_quantity_entries($cart_item_key, $is_for_allowed = false)
    {
        $entries = $is_for_allowed ? $this->_allowed_item_entries : $this->_item_quantity_entries;

        if (empty($entries)) {
            return array();
        }

        return array_filter($entries, function ($e) use ($cart_item_key) {
            return $e['key'] === $cart_item_key;
        });
    }

    /**
     * Calculate the spare quantity of a cart item by the deducting the sum of all item quantity entries.
     *
     * @since 1.3.5
     * @access public
     *
     * @param array $cart_item Cart item data.
     * @return int Cart item spare quantity.
     */
    public function calculate_cart_item_spare_quantity($cart_item)
    {

        $key      = $cart_item['key'];
        $quantity = $cart_item['quantity'];
        $entries  = $this->get_item_quantity_entries($cart_item['key']);

        return max(0, $quantity - array_sum(array_column($entries, 'quantity')));
    }

    /**
     * Remove all item quantity entries that was added with the provided coupon code.
     *
     * @since 1.3.5
     * @access public
     *
     * @param string $coupon_code Coupon code.
     */
    public function _unset_coupon_quantity_entries($coupon_code)
    {
        $entries = array_filter($this->_item_quantity_entries, function ($e) use ($coupon_code) {
            return $coupon_code === $e['coupon'];
        });

        if (empty($entries)) {
            return;
        }

        $this->_allowed_item_entries  = array_merge($this->_allowed_item_entries, $entries);
        $this->_item_quantity_entries = array_filter($this->_item_quantity_entries, function ($e) use ($coupon_code) {
            return $coupon_code !== $e['coupon'];
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
     */

    /**
     * Execute codes that needs to run plugin activation.
     *
     * @since 1.0
     * @access public
     * @implements ACFWF\Interfaces\Initializable_Interface
     */
    public function initialize()
    {

        if (!$this->_helper_functions->is_module(Plugin_Constants::BOGO_DEALS_MODULE)) {
            return;
        }

        $this->_set_notice_settings_default_values();

        add_action('wp_ajax_acfw_save_bogo_deals', array($this, 'ajax_save_bogo_deals'));
        add_action('wp_ajax_acfw_clear_bogo_deals', array($this, 'ajax_clear_bogo_deals'));
    }

    /**
     * Execute BOGO_Deals class.
     *
     * @since 1.0
     * @access public
     * @inherit ACFWF\Interfaces\Model_Interface
     */
    public function run()
    {

        if (!$this->_helper_functions->is_module(Plugin_Constants::BOGO_DEALS_MODULE)) {
            return;
        }

        add_shortcode('acfw_bogo_deals', array($this, 'apply_deals_list_shortcode'));
        add_action('woocommerce_before_calculate_totals', array($this, 'implement_bogo_deals'), 11);
        add_filter('woocommerce_cart_item_price', array($this, 'display_discounted_price'), 10, 2);
        add_filter('woocommerce_cart_totals_coupon_html', array($this, 'display_bogo_discount_summary'), 10, 3);
        add_action('woocommerce_checkout_order_processed', array($this, 'save_bogo_discounts_to_order'));
    }

}
