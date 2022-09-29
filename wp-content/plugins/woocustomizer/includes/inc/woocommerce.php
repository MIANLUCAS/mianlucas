<?php

/**
 * WooCommerce Compatibility File
 *
 * @package WooCustomizer
 */
/*
 * Admin Stats function.
 */
function wcz_admin_stats_ajax()
{
    add_action( 'wp_ajax_wcz_admin_get_product_stats', 'wcz_admin_get_product_stats' );
    // add_action( 'wp_ajax_nopriv_wcz_admin_get_product_stats', 'wcz_admin_get_product_stats' );
}

add_filter( 'init', 'wcz_admin_stats_ajax' );
/**
 * ------------------------------------------------------------------------------------ Add 'wcz-woocommerce' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function wcz_woocommerce_active_body_class( $classes )
{
    $classes[] = sanitize_html_class( 'wcz-woocommerce' );
    $classes[] = sanitize_html_class( get_option( 'wcz-btn-style', woocustomizer_library_get_default( 'wcz-btn-style' ) ) );
    if ( get_option( 'wcz-wc-edit-sale', woocustomizer_library_get_default( 'wcz-wc-edit-sale' ) ) ) {
        $classes[] = sanitize_html_class( 'wcz-edit-sale' );
    }
    if ( is_account_page() && 'wcz-tabstyle-none' !== get_option( 'wcz-tab-style', woocustomizer_library_get_default( 'wcz-tab-style' ) ) ) {
        $classes[] = sanitize_html_class( get_option( 'wcz-tab-style', woocustomizer_library_get_default( 'wcz-tab-style' ) ) );
    }
    if ( get_option( 'wcz-shop-add-soldout', woocustomizer_library_get_default( 'wcz-shop-add-soldout' ) ) ) {
        $classes[] = sanitize_html_class( get_option( 'wcz-soldout-style', woocustomizer_library_get_default( 'wcz-soldout-style' ) ) );
    }
    $classes[] = sanitize_html_class( get_option( 'wcz-btn-style', woocustomizer_library_get_default( 'wcz-btn-style' ) ) );
    return $classes;
}

add_filter( 'body_class', 'wcz_woocommerce_active_body_class' );
/**
 * ------------------------------------------------------------------------------------ Add 'wcz-woocommerce' class to the body tag.
 */
/**
 * Products per page.
 *
 * @return integer number of products.
 */
function wcz_woocommerce_products_per_page()
{
    return esc_attr( get_option( 'wcz-shop-pppage', woocustomizer_library_get_default( 'wcz-shop-pppage' ) ) );
}

add_filter( 'loop_shop_per_page', 'wcz_woocommerce_products_per_page', 9999 );
/**
 * Products per page.
 *
 * @return integer number of products.
 */
function wcz_woocommerce_cart_crosssells_cols()
{
    return esc_attr( get_option( 'wcz-cart-crosssells-ppr', woocustomizer_library_get_default( 'wcz-cart-crosssells-ppr' ) ) );
}

add_filter( 'woocommerce_cross_sells_columns', 'wcz_woocommerce_cart_crosssells_cols', 9999 );
/**
 * Product gallery thumnbail columns.
 *
 * @return integer number of columns.
 */
// function wcz_woocommerce_thumbnail_columns() {
// 	return esc_attr( 3 );
// }
// add_filter( 'woocommerce_product_thumbnails_columns', 'wcz_woocommerce_thumbnail_columns', 9999 );
/**
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */
function wcz_woocommerce_loop_columns()
{
    return esc_attr( get_option( 'wcz-shop-pprow', woocustomizer_library_get_default( 'wcz-shop-pprow' ) ) );
}

add_filter( 'loop_shop_columns', 'wcz_woocommerce_loop_columns', 9999 );
/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function wcz_woocommerce_related_products_args( $args )
{
    $defaults = array(
        'posts_per_page' => esc_attr( 4 ),
        'columns'        => esc_attr( 4 ),
    );
    $args = wp_parse_args( $defaults, $args );
    return $args;
}

add_filter( 'woocommerce_output_related_products_args', 'wcz_woocommerce_related_products_args' );
/**
 * ------------------------------------------------------------------------------------ Edit WooCommerce Text.
 */
function wcz_wc_texts()
{
    // Single Product Button Text
    
    if ( get_option( 'wcz-product-edit-btn', woocustomizer_library_get_default( 'wcz-product-edit-btn' ) ) ) {
        $setting = 'wcz-product-button-txt-simple';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
            add_filter( 'woocommerce_product_single_add_to_cart_text', 'wcz_wc_texts_simple_button' );
        }
    }
    
    
    if ( is_woocommerce() ) {
        // Variable Product Button Text
        if ( get_option( 'wcz-shop-edit-btns', woocustomizer_library_get_default( 'wcz-shop-edit-btns' ) ) ) {
            add_filter( 'woocommerce_product_add_to_cart_text', 'wcz_wc_texts_variable_button' );
        }
        // Out of Stock Text
        add_filter(
            'woocommerce_get_availability',
            'wcz_stock_availability_text',
            1,
            2
        );
        // Edit Sale Banner text
        add_filter(
            'woocommerce_sale_flash',
            'wcz_sale_banner_text',
            10,
            3
        );
    }
    
    // Remove Shop Sorting
    
    if ( get_option( 'wcz-shop-remove-sorting', woocustomizer_library_get_default( 'wcz-shop-remove-sorting' ) ) ) {
        remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
        remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
    }
    
    // Remove Shop Results text
    
    if ( get_option( 'wcz-shop-remove-result', woocustomizer_library_get_default( 'wcz-shop-remove-result' ) ) ) {
        remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
        remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
    }
    
    // Remove Shop Page Title
    if ( get_option( 'wcz-shop-remove-title', woocustomizer_library_get_default( 'wcz-shop-remove-title' ) ) ) {
        add_filter( 'woocommerce_show_page_title', 'wcz_remove_shop_title' );
    }
    // Add a new 'Continue Shopping' button to the product page
    if ( get_option( 'wcz-add-shop-button', woocustomizer_library_get_default( 'wcz-add-shop-button' ) ) ) {
        add_action( 'woocommerce_single_product_summary', 'wcz_add_product_shopping_button', 31 );
    }
    // Remove Product SKU
    if ( get_option( 'wcz-remove-product-sku', woocustomizer_library_get_default( 'wcz-remove-product-sku' ) ) ) {
        add_filter( 'wc_product_sku_enabled', 'wcz_remove_product_sku' );
    }
    if ( get_option( 'wcz-shop-show-stock', woocustomizer_library_get_default( 'wcz-shop-show-stock' ) ) ) {
        add_action( 'woocommerce_after_shop_loop_item', 'wcz_show_stock_amount_loop', 31 );
    }
    // Edit Coupon Code block text
    
    if ( is_checkout() && get_option( 'wcz-checkout-edit-coupon-txt', woocustomizer_library_get_default( 'wcz-checkout-edit-coupon-txt' ) ) ) {
        add_filter( 'woocommerce_checkout_coupon_message', 'wcz_coupon_message' );
        add_filter( 'gettext', 'woocommerce_edit_checkout_coupon_instruction_text' );
    }
    
    // Edit Order Notes Text
    if ( is_checkout() && get_option( 'wcz-checkout-edit-ordernotes-txt', woocustomizer_library_get_default( 'wcz-checkout-edit-ordernotes-txt' ) ) ) {
        add_filter( 'woocommerce_checkout_fields', 'wcz_edit_checkout_ordernotes_txt' );
    }
    // Remove Catgory Number Count
    if ( get_option( 'wcz-shop-remove-catcount', woocustomizer_library_get_default( 'wcz-shop-remove-catcount' ) ) ) {
        add_filter( 'woocommerce_subcategory_count_html', '__return_null' );
    }
    // Add Sold Out banner to sold out products
    if ( get_option( 'wcz-shop-add-soldout', woocustomizer_library_get_default( 'wcz-shop-add-soldout' ) ) ) {
        
        if ( 'wcz-soldout-style-angle' == get_option( 'wcz-soldout-style', woocustomizer_library_get_default( 'wcz-soldout-style' ) ) ) {
            add_action( 'woocommerce_after_shop_loop_item', 'wcz_add_soldout_to_shop' );
        } else {
            add_action( 'woocommerce_after_shop_loop_item', 'wcz_add_soldout_to_shop' );
        }
    
    }
    // Shop List "New" Product Badge
    if ( get_option( 'wcz-shop-new-badge', woocustomizer_library_get_default( 'wcz-shop-new-badge' ) ) ) {
        
        if ( 'abovetitle' == get_option( 'wcz-shop-new-badge-pos', woocustomizer_library_get_default( 'wcz-shop-new-badge-pos' ) ) ) {
            add_action( 'woocommerce_shop_loop_item_title', 'wcz_add_new_product_badge', 3 );
        } elseif ( 'belowtitle' == get_option( 'wcz-shop-new-badge-pos', woocustomizer_library_get_default( 'wcz-shop-new-badge-pos' ) ) ) {
            add_action( 'woocommerce_after_shop_loop_item', 'wcz_add_new_product_badge', 3 );
        } else {
            add_action( 'woocommerce_before_shop_loop_item', 'wcz_add_new_product_badge', 3 );
        }
    
    }
    // Remove Product Page Title
    if ( get_option( 'wcz-remove-product-title', woocustomizer_library_get_default( 'wcz-remove-product-title' ) ) ) {
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
    }
    // Add Admin Stats button to products
    
    if ( get_option( 'wcz-admin-product-stats', woocustomizer_library_get_default( 'wcz-admin-product-stats' ) ) ) {
        add_action( 'woocommerce_after_shop_loop_item', 'wcz_add_admin_stats_btn' );
        // Footer Modal
        add_action( 'wp_footer', 'wcz_admin_stats_modal' );
    }
    
    // Remove Order Notes on Checkout Page
    if ( get_option( 'wcz-checkout-remove-order-notes', woocustomizer_library_get_default( 'wcz-checkout-remove-order-notes' ) ) ) {
        add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
    }
    // EXCLUDED FROM FREE VERSION -- This "if" block will be auto removed from the Free version.
    // Set Increment values for Product single page Add To Cart
    if ( get_option( 'wcz-set-cart-increment-vals', woocustomizer_library_get_default( 'wcz-set-cart-increment-vals' ) ) ) {
        add_filter(
            'woocommerce_quantity_input_args',
            'wcz_set_product_single_min_max_values',
            10,
            2
        );
    }
}

add_filter( 'template_redirect', 'wcz_wc_texts' );
// EXCLUDED FROM FREE VERSION -- This "if" block will be auto removed from the Free version.
// Single Product - Set min and max values allowed
function wcz_set_product_single_min_max_values( $args, $product )
{
    $arg_min = get_option( 'wcz-set-cart-inc-min', woocustomizer_library_get_default( 'wcz-set-cart-inc-min' ) );
    $arg_max = get_option( 'wcz-set-cart-inc-max', woocustomizer_library_get_default( 'wcz-set-cart-inc-max' ) );
    $arg_step = get_option( 'wcz-set-cart-inc-by', woocustomizer_library_get_default( 'wcz-set-cart-inc-by' ) );
    
    if ( !is_cart() ) {
        $args['min_value'] = esc_attr( $arg_min );
        // Min quantity
        $args['max_value'] = esc_attr( $arg_max );
        // Max quantity (default -1)
        // $args['input_value'] = 4; // Start at
        $args['step'] = $arg_step;
        // Increment by
    } else {
        // 'min_value' is already 0
        $args['min_value'] = esc_attr( $arg_min );
        $args['max_value'] = esc_attr( $arg_max );
        // Max quantity
        $args['step'] = $arg_step;
    }
    
    return $args;
}

// Simple Product Button function
function wcz_wc_texts_simple_button()
{
    $setting = 'wcz-product-button-txt-simple';
    $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
    return esc_html( $mod );
}

// Variable Product Button function
function wcz_wc_texts_variable_button()
{
    global  $product ;
    if ( !isset( $product ) ) {
        return;
    }
    $product_type = $product->get_type();
    switch ( $product_type ) {
        case "variable":
            $setting = 'wcz-shop-button-txt-variable';
            $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
            return esc_html( $mod );
            break;
        case "grouped":
            $setting = 'wcz-shop-button-txt-grouped';
            $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
            return esc_html( $mod );
            break;
        case "external":
            return esc_html( $product->get_button_text() );
            break;
        default:
            $setting = 'wcz-shoplist-button-txt-simple';
            $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
            return esc_html( $mod );
    }
}

// Out Of Stock function on Product Page
function wcz_stock_availability_text( $availability )
{
    global  $product ;
    if ( !isset( $product ) ) {
        return;
    }
    // Change Out Of Stock Text
    
    if ( 'outofstock' == $product->get_stock_status() ) {
        $setting = 'wcz-product-outofstock-txt';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        $availability['availability'] = esc_html( $mod );
    }
    
    
    if ( get_option( 'wcz-show-other-stockstatus', woocustomizer_library_get_default( 'wcz-show-other-stockstatus' ) ) ) {
        // Change On Back Order Text
        
        if ( 'onbackorder' == $product->get_stock_status() ) {
            $setting = 'wcz-product-onbackorder-txt';
            $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
            $availability['availability'] = esc_html( $mod );
        }
        
        // Change In Stock Text
        
        if ( 'instock' == $product->get_stock_status() ) {
            $setting = 'wcz-product-instock-txt';
            $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
            $availability['availability'] = esc_html( $mod );
        }
    
    }
    
    return $availability;
}

// Edit Sale Banner text for shop / product pages
function wcz_sale_banner_text()
{
    
    if ( is_product() ) {
        $setting = 'wcz-product-sale-txt';
    } else {
        $setting = 'wcz-shop-sale-txt';
    }
    
    $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
    return '<span class="onsale">' . esc_html( $mod ) . '</span>';
}

// Remove Shop Page Title
function wcz_remove_shop_title( $title )
{
    if ( is_shop() ) {
        $title = false;
    }
    return esc_html( $title );
}

// Add a new 'Continue Shopping' button to the product page
function wcz_add_product_shopping_button()
{
    if ( wp_get_referer() ) {
        echo  '<a class="button wcz-continue" href="' . wp_get_referer() . '">' . get_option( 'wcz-add-shop-button-txt', woocustomizer_library_get_default( 'wcz-add-shop-button-txt' ) ) . '</a>' ;
    }
}

// Remove Product SKU
function wcz_remove_product_sku( $enabled )
{
    if ( !is_admin() && is_product() ) {
        return false;
    }
    return $enabled;
}

// Edit Coupon Code block text
function wcz_coupon_message()
{
    return esc_html( get_option( 'wcz-checkout-coupon-text', woocustomizer_library_get_default( 'wcz-checkout-coupon-text' ) ) ) . ' <a href="#" class="showcoupon">' . esc_html( get_option( 'wcz-checkout-coupon-link-text', woocustomizer_library_get_default( 'wcz-checkout-coupon-link-text' ) ) ) . '</a>';
}

// Edit Coupon Code Instruction text
function woocommerce_edit_checkout_coupon_instruction_text( $translated )
{
    $translated = str_ireplace( 'If you have a coupon code, please apply it below.', get_option( 'wcz-checkout-coupon-instruction-text', woocustomizer_library_get_default( 'wcz-checkout-coupon-instruction-text' ) ), $translated );
    return $translated;
}

// Add Sold Out banner to sold out products
function wcz_add_soldout_to_shop()
{
    global  $product ;
    if ( !isset( $product ) ) {
        return;
    }
    if ( !$product->is_in_stock() ) {
        echo  '<span class="wcz-soldout">' . get_option( 'wcz-shop-soldout-txt', woocustomizer_library_get_default( 'wcz-shop-soldout-txt' ) ) . '</span>' ;
    }
}

// New Product badge
function wcz_add_new_product_badge()
{
    global  $product ;
    $wcz_product_created = strtotime( $product->get_date_created() );
    $wcz_product_days = get_option( 'wcz-shop-new-product-days', woocustomizer_library_get_default( 'wcz-shop-new-product-days' ) );
    $wcz_badge_txt = get_option( 'wcz-shop-new-product-badge-text', woocustomizer_library_get_default( 'wcz-shop-new-product-badge-text' ) );
    if ( time() - 60 * 60 * 24 * $wcz_product_days < $wcz_product_created ) {
        echo  '<div class="wcz-new-product-badge wcz-badge-pos-' . sanitize_html_class( get_option( 'wcz-shop-new-badge-pos', woocustomizer_library_get_default( 'wcz-shop-new-badge-pos' ) ) ) . '"><span class="">' . esc_html( $wcz_badge_txt ) . '</span></div>' ;
    }
}

function wcz_show_stock_amount_loop()
{
    global  $product ;
    
    if ( $product->get_stock_quantity() ) {
        // if manage stock is enabled
        $wcz_pstock = number_format(
            $product->get_stock_quantity(),
            0,
            '',
            ''
        );
        
        if ( $wcz_pstock <= 3 ) {
            // if stock is low
            $wcz_stocktxt = esc_html( get_option( 'wcz-shop-stock-lowamnt-txt', woocustomizer_library_get_default( 'wcz-shop-stock-lowamnt-txt' ) ) );
            echo  '<div class="wcz-stock-remaining">' . str_ireplace( '[no]', $wcz_pstock, $wcz_stocktxt ) . '</div>' ;
        } else {
            $wcz_stocktxt = esc_html( get_option( 'wcz-shop-stock-amnt-txt', woocustomizer_library_get_default( 'wcz-shop-stock-amnt-txt' ) ) );
            echo  '<div class="wcz-stock-remaining">' . str_ireplace( '[no]', $wcz_pstock, $wcz_stocktxt ) . '</div>' ;
        }
    
    }

}

function wcz_remove_checkout_fields( $fields )
{
    
    if ( get_option( 'wcz-checkout-remove-lastname', woocustomizer_library_get_default( 'wcz-checkout-remove-lastname' ) ) ) {
        $fields['billing']['billing_first_name']['class'][0] = 'form-row-wide';
        $fields['shipping']['shipping_first_name']['class'][0] = 'form-row-wide';
        $fields['billing']['billing_first_name']['label'] = __( 'Full Name', 'woocustomizer' );
        $fields['shipping']['shipping_first_name']['label'] = __( 'Full Name', 'woocustomizer' );
        unset( $fields['billing']['billing_last_name'] );
        unset( $fields['shipping']['shipping_last_name'] );
        unset( $fields['billing']['billing_last_name']['validate'] );
        unset( $fields['shipping']['shipping_last_name']['validate'] );
    }
    
    
    if ( get_option( 'wcz-checkout-remove-company', woocustomizer_library_get_default( 'wcz-checkout-remove-company' ) ) ) {
        unset( $fields['billing']['billing_company'] );
        unset( $fields['shipping']['shipping_company'] );
        unset( $fields['billing']['billing_company']['validate'] );
        unset( $fields['shipping']['shipping_company']['validate'] );
    }
    
    
    if ( get_option( 'wcz-checkout-remove-address', woocustomizer_library_get_default( 'wcz-checkout-remove-address' ) ) ) {
        unset( $fields['billing']['billing_address_1'] );
        unset( $fields['billing']['billing_address_2'] );
        unset( $fields['shipping']['shipping_address_1'] );
        unset( $fields['shipping']['shipping_address_2'] );
        unset( $fields['billing']['billing_address_1']['validate'] );
        unset( $fields['billing']['billing_address_2']['validate'] );
        unset( $fields['shipping']['shipping_address_1']['validate'] );
        unset( $fields['shipping']['shipping_address_2']['validate'] );
    }
    
    
    if ( get_option( 'wcz-checkout-remove-towncity', woocustomizer_library_get_default( 'wcz-checkout-remove-towncity' ) ) ) {
        unset( $fields['billing']['billing_city'] );
        unset( $fields['shipping']['shipping_city'] );
        unset( $fields['billing']['billing_city']['validate'] );
        unset( $fields['shipping']['shipping_city']['validate'] );
    }
    
    
    if ( get_option( 'wcz-checkout-remove-provstate', woocustomizer_library_get_default( 'wcz-checkout-remove-provstate' ) ) ) {
        unset( $fields['billing']['billing_state'] );
        unset( $fields['billing']['billing_postcode'] );
        unset( $fields['shipping']['shipping_state'] );
        unset( $fields['shipping']['shipping_postcode'] );
        unset( $fields['billing']['billing_state']['validate'] );
        unset( $fields['billing']['billing_postcode']['validate'] );
        unset( $fields['shipping']['shipping_state']['validate'] );
        unset( $fields['shipping']['shipping_postcode']['validate'] );
    }
    
    
    if ( get_option( 'wcz-checkout-remove-phone', woocustomizer_library_get_default( 'wcz-checkout-remove-phone' ) ) ) {
        unset( $fields['billing']['billing_phone'] );
        unset( $fields['shipping']['shipping_phone'] );
        unset( $fields['billing']['billing_phone']['validate'] );
        unset( $fields['shipping']['shipping_phone']['validate'] );
    }
    
    return $fields;
}

// Remove Checkout Page Billing Fields
if ( get_option( 'wcz-checkout-remove-firstname', woocustomizer_library_get_default( 'wcz-checkout-remove-firstname' ) ) || get_option( 'wcz-checkout-remove-firstname', woocustomizer_library_get_default( 'wcz-checkout-remove-firstname' ) ) || get_option( 'wcz-checkout-remove-lastname', woocustomizer_library_get_default( 'wcz-checkout-remove-lastname' ) ) || get_option( 'wcz-checkout-remove-company', woocustomizer_library_get_default( 'wcz-checkout-remove-company' ) ) || get_option( 'wcz-checkout-remove-address', woocustomizer_library_get_default( 'wcz-checkout-remove-address' ) ) || get_option( 'wcz-checkout-remove-citystate', woocustomizer_library_get_default( 'wcz-checkout-remove-citystate' ) ) || get_option( 'wcz-checkout-remove-phone', woocustomizer_library_get_default( 'wcz-checkout-remove-phone' ) ) ) {
    add_filter( 'woocommerce_checkout_fields', 'wcz_remove_checkout_fields' );
}
// Edit Checkout page Order Notes text
function wcz_edit_checkout_ordernotes_txt( $fields )
{
    $fields['order']['order_comments']['label'] = get_option( 'wcz-checkout-ordernotes-label', woocustomizer_library_get_default( 'wcz-checkout-ordernotes-label' ) );
    $fields['order']['order_comments']['placeholder'] = get_option( 'wcz-checkout-ordernotes-placeholder', woocustomizer_library_get_default( 'wcz-checkout-ordernotes-placeholder' ) );
    return $fields;
}

// Add Product Admin Stats Button
function wcz_add_admin_stats_btn()
{
    global  $product ;
    
    if ( current_user_can( 'manage_options' ) ) {
        ?>
        <button class="wcz-adminstats-btn" title="<?php 
        esc_attr_e( 'View Product Statistics', 'woocustomizer' );
        ?>" data-productid="<?php 
        echo  esc_attr( $product->get_id() ) ;
        ?>"></button>
    <?php 
    }

}

// Add Footer Modal
function wcz_admin_stats_modal()
{
    echo  '<div id="wcz-adminstats" class="wcz-adminstats-modal wcz-modal-loading wcz-hide"><button class="wcz-adminstats-close"></button><div class="wcz-adminstats-modal-inner"></div></div>' ;
}

// Footer Modal AJAX function
function wcz_admin_get_product_stats()
{
    // Get $product ID from ajax
    $product_id = $_POST['product_id'];
    $product = wc_get_product( $product_id );
    $product_limit = 4;
    ob_start();
    ?>
		<div class="wcz-adminstats-modal-inner">
			<h4><span><?php 
    esc_html_e( $product->get_name() );
    ?></span><span><?php 
    esc_html_e( $product->get_type() );
    ?> <?php 
    esc_html_e( 'product', 'woocustomizer' );
    ?></span></h4>

			<div class="wcz-adminstats-block">
				<div class="wcz-adminstats-title">
					<?php 
    esc_html_e( 'Total Sales', 'woocustomizer' );
    ?>
				</div>
				<div class="wcz-adminstats-stat">
					<?php 
    esc_attr_e( $product->get_total_sales() );
    ?>
				</div>
			</div>
			<?php 
    // global $product;
    $orders = get_posts( array(
        'post_type'   => 'shop_order',
        'post_status' => 'wc-completed',
    ) );
    
    if ( !empty($orders) ) {
        $loop = 0;
        foreach ( $orders as $order ) {
            $order = new WC_Order( $order->ID );
            $items = $order->get_items();
            if ( $items ) {
                foreach ( $items as $item ) {
                    // var_dump( $item );
                    $product_item_id = $item['product_id'];
                    
                    if ( $product_id == $product_item_id ) {
                        if ( $loop == 0 ) {
                            echo  '<h5>' . esc_html__( 'Recent Sales', 'woocustomizer' ) . '</h5>' ;
                        }
                        ?>
								<div class="wcz-adminstats-block">
									<div class="wcz-adminstats-date">
										<?php 
                        echo  $order->get_date_completed()->format( 'Y-m-d' ) ;
                        ?>
									</div>
									<div class="wcz-adminstats-title">
										<?php 
                        
                        if ( $order->get_billing_first_name() || $order->get_billing_last_name() ) {
                            echo  esc_html( $order->get_billing_first_name() ) . ' ' . esc_html( $order->get_billing_last_name() ) ;
                        } else {
                            esc_html_e( $order->get_billing_email() );
                        }
                        
                        ?>
										<span><?php 
                        echo  '(#' . $order->get_id() . ')' ;
                        ?></span>
									</div>
									<div class="wcz-adminstats-stat">
										<a href="<?php 
                        echo  esc_url( $order->get_edit_order_url() ) ;
                        ?>"><?php 
                        esc_html_e( 'View Order', 'woocustomizer' );
                        ?></a>
									</div>
								</div>
								<?php 
                        $loop++;
                    }
                
                }
            }
            if ( $loop == $product_limit ) {
                break;
            }
        }
    }
    
    ?>

			<div class="wcz-adminstats-edit">
				<a href="<?php 
    echo  esc_url( get_edit_post_link( $product_id ) ) ;
    ?>"><?php 
    esc_html_e( 'Edit Product', 'woocustomizer' );
    ?></a>
			</div>
		</div>
	<?php 
    echo  ob_get_clean() ;
    die;
}

/**
 * ------------------------------------------------------------------------------------ Edit WooCommerce Text.
 */
/**
 * ------------------------------------------------------------------------------------ Remove WooCommerce Functionality.
 */
function wcz_wc_extras()
{
    // Remove Breadcrumbs
    if ( is_woocommerce() && get_option( 'wcz-wc-remove-breadcrumbs', woocustomizer_library_get_default( 'wcz-wc-remove-breadcrumbs' ) ) ) {
        remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb' );
    }
    // Remove Product Gallery Zoom
    if ( is_woocommerce() && get_option( 'wcz-remove-product-zoom', woocustomizer_library_get_default( 'wcz-remove-product-zoom' ) ) ) {
        remove_theme_support( 'wc-product-gallery-zoom' );
    }
    // Remove Product Gallery Lightbox
    if ( is_woocommerce() && get_option( 'wcz-remove-product-lightbox', woocustomizer_library_get_default( 'wcz-remove-product-lightbox' ) ) ) {
        remove_theme_support( 'wc-product-gallery-lightbox' );
    }
    // Remove Product Gallery Slider
    if ( is_woocommerce() && get_option( 'wcz-remove-product-slider', woocustomizer_library_get_default( 'wcz-remove-product-slider' ) ) ) {
        remove_theme_support( 'wc-product-gallery-slider' );
    }
    // Edit Product Tabs
    add_filter( 'woocommerce_product_tabs', 'wcz_product_tabs', 98 );
    if ( 'wcz-wcproduct-desc-tab-edit' == get_option( 'wcz-wcproduct-desc-tab', woocustomizer_library_get_default( 'wcz-wcproduct-desc-tab' ) ) ) {
        add_filter( 'woocommerce_product_description_heading', 'wcz_rename_desctab_headings' );
    }
    if ( 'wcz-wcproduct-addinfo-tab-edit' == get_option( 'wcz-wcproduct-addinfo-tab', woocustomizer_library_get_default( 'wcz-wcproduct-addinfo-tab' ) ) ) {
        add_filter( 'woocommerce_product_additional_information_heading', 'wcz_rename_addinfotab_headings' );
    }
    // Rename Account Page Titles/Endpoints
    add_filter(
        'the_title',
        'wcz_account_endpoint_title',
        10,
        2
    );
    if ( is_product() && get_option( 'wcz-add-price-suffix', woocustomizer_library_get_default( 'wcz-add-price-suffix' ) ) ) {
        add_filter(
            'woocommerce_get_price_suffix',
            'wcz_product_price_price_suffix',
            99,
            4
        );
    }
    if ( is_product() && get_option( 'wcz-add-product-long-desc', woocustomizer_library_get_default( 'wcz-add-product-long-desc' ) ) ) {
        add_action( 'woocommerce_after_single_product_summary', 'wcz_add_product_long_desc', 10 );
    }
    if ( is_product() && get_option( 'wcz-product-show-unitsold', woocustomizer_library_get_default( 'wcz-product-show-unitsold' ) ) ) {
        add_action( 'woocommerce_single_product_summary', 'wcz_product_amount_sold', 11 );
    }
    // Product Recommendations Title
    
    if ( is_woocommerce() && 'wcz-wcproduct-recomm-edit' == get_option( 'wcz-wcproduct-recomm', woocustomizer_library_get_default( 'wcz-wcproduct-recomm' ) ) ) {
        add_filter( 'gettext', 'wcz_product_recomtxt' );
        add_filter( 'ngettext', 'wcz_product_recomtxt' );
    }
    
    // Remove Related Products
    
    if ( is_woocommerce() && 'wcz-wcproduct-related-remove' == get_option( 'wcz-wcproduct-related', woocustomizer_library_get_default( 'wcz-wcproduct-related' ) ) ) {
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 1 );
    } elseif ( is_woocommerce() && 'wcz-wcproduct-related-edit' == get_option( 'wcz-wcproduct-related', woocustomizer_library_get_default( 'wcz-wcproduct-related' ) ) ) {
        add_filter( 'gettext', 'wcz_product_relatedtxt' );
        add_filter( 'ngettext', 'wcz_product_relatedtxt' );
    }
    
    if ( is_cart() && get_option( 'wcz-cart-add-custom-text', woocustomizer_library_get_default( 'wcz-cart-add-custom-text' ) ) ) {
        add_action( 'woocommerce_cart_is_empty', 'wcz_add_textto_empty_cart_page' );
    }
    if ( is_cart() && get_option( 'wcz-cart-remove-coupons', woocustomizer_library_get_default( 'wcz-cart-remove-coupons' ) ) ) {
        add_filter( 'woocommerce_coupons_enabled', 'wcz_remove_cart_coupons' );
    }
    // Move Cross Sells section
    
    if ( get_option( 'wcz-cart-remove-cross-sells', woocustomizer_library_get_default( 'wcz-cart-remove-cross-sells' ) ) && get_option( 'wcz-cart-move-crollsells-below', woocustomizer_library_get_default( 'wcz-cart-move-crollsells-below' ) ) || get_option( 'wcz-cart-remove-cross-sells', woocustomizer_library_get_default( 'wcz-cart-remove-cross-sells' ) ) ) {
        remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
    } elseif ( !get_option( 'wcz-cart-remove-cross-sells', woocustomizer_library_get_default( 'wcz-cart-remove-cross-sells' ) ) && get_option( 'wcz-cart-move-crollsells-below', woocustomizer_library_get_default( 'wcz-cart-move-crollsells-below' ) ) ) {
        remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
        add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );
    }
    
    // Cart Crosss Sells Title - Cart Page
    
    if ( is_cart() && 'wcz-wccart-recomm-edit' == get_option( 'wcz-wccart-recomm', woocustomizer_library_get_default( 'wcz-wccart-recomm' ) ) ) {
        add_filter( 'gettext', 'wcz_cart_recomtxt' );
        add_filter( 'ngettext', 'wcz_cart_recomtxt' );
    }
    
    // Cart Totals Title - Cart Page
    
    if ( is_cart() && 'wcz-wccart-totals-edit' == get_option( 'wcz-wccart-totals', woocustomizer_library_get_default( 'wcz-wccart-totals' ) ) ) {
        add_filter( 'gettext', 'wcz_cart_totalstxt' );
        add_filter( 'ngettext', 'wcz_cart_totalstxt' );
    }
    
    if ( is_checkout() && get_option( 'wcz-checkout-add-img', woocustomizer_library_get_default( 'wcz-checkout-add-img' ) ) ) {
        add_action( 'woocommerce_after_checkout_form', 'wcz_checkout_custom_secureimg' );
    }
}

add_action( 'template_redirect', 'wcz_wc_extras', 10 );
/**
 * ------------------------------------------------------------------------------------ Remove WooCommerce Functionality.
 */
// Rename Product Description Tab
function wcz_product_tabs( $tabs )
{
    
    if ( 'wcz-wcproduct-desc-tab-remove' == get_option( 'wcz-wcproduct-desc-tab', woocustomizer_library_get_default( 'wcz-wcproduct-desc-tab' ) ) ) {
        unset( $tabs['description'] );
    } elseif ( 'wcz-wcproduct-desc-tab-edit' == get_option( 'wcz-wcproduct-desc-tab', woocustomizer_library_get_default( 'wcz-wcproduct-desc-tab' ) ) ) {
        $tabs['description']['title'] = esc_html( get_option( 'wcz-wcproduct-desc-tab-title', woocustomizer_library_get_default( 'wcz-wcproduct-desc-tab-title' ) ) );
    }
    
    
    if ( 'wcz-wcproduct-addinfo-tab-remove' == get_option( 'wcz-wcproduct-addinfo-tab', woocustomizer_library_get_default( 'wcz-wcproduct-addinfo-tab' ) ) ) {
        unset( $tabs['additional_information'] );
    } elseif ( 'wcz-wcproduct-addinfo-tab-edit' == get_option( 'wcz-wcproduct-addinfo-tab', woocustomizer_library_get_default( 'wcz-wcproduct-addinfo-tab' ) ) ) {
        $tabs['additional_information']['title'] = esc_html( get_option( 'wcz-wcproduct-addinfo-tab-title', woocustomizer_library_get_default( 'wcz-wcproduct-addinfo-tab-title' ) ) );
    }
    
    
    if ( 'wcz-wcproduct-reviews-tab-remove' == get_option( 'wcz-wcproduct-reviews-tab', woocustomizer_library_get_default( 'wcz-wcproduct-reviews-tab' ) ) ) {
        unset( $tabs['reviews'] );
    } elseif ( 'wcz-wcproduct-reviews-tab-edit' == get_option( 'wcz-wcproduct-reviews-tab', woocustomizer_library_get_default( 'wcz-wcproduct-reviews-tab' ) ) ) {
        $tabs['reviews']['title'] = esc_html( get_option( 'wcz-wcproduct-reviews-tab-title', woocustomizer_library_get_default( 'wcz-wcproduct-reviews-tab-title' ) ) );
    }
    
    return $tabs;
}

function wcz_rename_desctab_headings()
{
    return esc_html( get_option( 'wcz-wcproduct-desc-head', woocustomizer_library_get_default( 'wcz-wcproduct-desc-head' ) ) );
}

function wcz_rename_addinfotab_headings()
{
    return esc_html( get_option( 'wcz-wcproduct-addinfo-head', woocustomizer_library_get_default( 'wcz-wcproduct-addinfo-head' ) ) );
}

function wcz_product_price_price_suffix(
    $html,
    $product,
    $price,
    $qty
)
{
    $html .= ' <small>' . get_option( 'wcz-add-price-suffix-txt', woocustomizer_library_get_default( 'wcz-add-price-suffix-txt' ) ) . '</small>';
    return $html;
}

function wcz_add_product_long_desc()
{
    ?>
	<div class="wcz-product-long-desc">
		<?php 
    the_content();
    ?>
	</div>
<?php 
}

function wcz_add_textto_empty_cart_page()
{
    echo  '<div class="wcz-cart-empty-txt">' . esc_html( get_option( 'wcz-cart-empty-txt', woocustomizer_library_get_default( 'wcz-cart-empty-txt' ) ) ) . '</div>' ;
}

function wcz_remove_cart_coupons( $enabled )
{
    if ( is_cart() ) {
        $enabled = false;
    }
    return $enabled;
}

function wcz_checkout_custom_secureimg()
{
    ?>
	<div class="wcz-checkout-secureimg <?php 
    echo  ( get_option( 'wcz-checkout-img-center', woocustomizer_library_get_default( 'wcz-checkout-img-center' ) ) ? sanitize_html_class( 'wcz-checkout-centerimg' ) : '' ) ;
    ?>">
		<?php 
    
    if ( get_option( 'wcz-checkout-img', woocustomizer_library_get_default( 'wcz-checkout-img' ) ) ) {
        ?>
			<img src="<?php 
        echo  esc_url( get_option( 'wcz-checkout-img', woocustomizer_library_get_default( 'wcz-checkout-img' ) ) ) ;
        ?>" />
		<?php 
    } else {
        ?>
			<?php 
        esc_html_e( 'Please Upload an Image', 'woocustomizer' );
        ?>
		<?php 
    }
    
    ?>
	</div>
<?php 
}

function wcz_checkout_text_below_placeorder()
{
    ?>
	<div class="wcz-checkout-potxt">
		<small>
			<?php 
    echo  esc_html( get_option( 'wcz-checkout-po-txt', woocustomizer_library_get_default( 'wcz-checkout-po-txt' ) ) ) ;
    ?>
		</small>
	</div>
<?php 
}

function wcz_product_amount_sold()
{
    global  $product ;
    $wcz_amntsold = get_post_meta( $product->get_id(), 'total_sales', true );
    $wcz_stocktxt = esc_html( get_option( 'wcz-product-unitsold-txt', woocustomizer_library_get_default( 'wcz-product-unitsold-txt' ) ) );
    if ( $wcz_amntsold ) {
        echo  '<div class="wcz-stock-sold">' . str_ireplace( '[no]', $wcz_amntsold, $wcz_stocktxt ) . '</div>' ;
    }
}

// Product Recommendations Title
function wcz_product_recomtxt( $translated )
{
    $wcz_new_recomtitle = esc_html( get_option( 'wcz-wcproduct-recomm-title', woocustomizer_library_get_default( 'wcz-wcproduct-recomm-title' ) ) );
    $translated = str_ireplace( 'You may also like&hellip;', $wcz_new_recomtitle, $translated );
    return $translated;
}

// Related Products Title
function wcz_product_relatedtxt( $translated )
{
    $wcz_new_reltitle = esc_html( get_option( 'wcz-wcproduct-related-title', woocustomizer_library_get_default( 'wcz-wcproduct-related-title' ) ) );
    $translated = str_ireplace( 'Related products', $wcz_new_reltitle, $translated );
    return $translated;
}

// Cart Cross Sells Title
function wcz_cart_recomtxt( $translated )
{
    $wcz_new_recomtitle = esc_html( get_option( 'wcz-wccart-recomm-title', woocustomizer_library_get_default( 'wcz-wccart-recomm-title' ) ) );
    $translated = str_ireplace( 'You may be interested in&hellip;', $wcz_new_recomtitle, $translated );
    return $translated;
}

// Cart Totals Title
function wcz_cart_totalstxt( $translated )
{
    $wcz_new_totalstitle = esc_html( get_option( 'wcz-wccart-totals-title', woocustomizer_library_get_default( 'wcz-wccart-totals-title' ) ) );
    $translated = str_ireplace( 'Cart totals', $wcz_new_totalstitle, $translated );
    return $translated;
}

/**
 * ------------------------------------------------------------------------------------ Remove/Edit selected My Account Tabs & Titles.
 */
/**
 * Edit the Account Page tab titles or remove the tab
 */
if ( !function_exists( 'wcz_remove_account_links' ) ) {
    function wcz_remove_account_links( $menu_links )
    {
        
        if ( 'wcz-account-dashboard-remove' == get_option( 'wcz-account-dashboard-tab', woocustomizer_library_get_default( 'wcz-account-dashboard-tab' ) ) ) {
            unset( $menu_links['dashboard'] );
            // Remove Dashboard
        } elseif ( 'wcz-account-dashboard-edit' == get_option( 'wcz-account-dashboard-tab', woocustomizer_library_get_default( 'wcz-account-dashboard-tab' ) ) ) {
            $menu_links['dashboard'] = esc_html( get_option( 'wcz-account-tab-dash-tab', woocustomizer_library_get_default( 'wcz-account-tab-dash-tab' ) ) );
        } else {
            $menu_links['dashboard'] = esc_html__( 'Dashboard', 'woocustomizer' );
        }
        
        // Unset Links for Ordering
        
        if ( 'wcz-account-orders-edit' == get_option( 'wcz-account-orders-tab', woocustomizer_library_get_default( 'wcz-account-orders-tab' ) ) || 'wcz-account-downloads-edit' == get_option( 'wcz-account-downloads-tab', woocustomizer_library_get_default( 'wcz-account-downloads-tab' ) ) || 'wcz-account-address-edit' == get_option( 'wcz-account-address-tab', woocustomizer_library_get_default( 'wcz-account-address-tab' ) ) || 'wcz-account-details-edit' == get_option( 'wcz-account-details-tab', woocustomizer_library_get_default( 'wcz-account-details-tab' ) ) ) {
            unset( $menu_links['orders'] );
            unset( $menu_links['downloads'] );
            unset( $menu_links['edit-address'] );
            unset( $menu_links['edit-account'] );
        }
        
        // Only Available in WooCustomizer Pro
        
        if ( 'wcz-account-orders-remove' == get_option( 'wcz-account-orders-tab', woocustomizer_library_get_default( 'wcz-account-orders-tab' ) ) ) {
            unset( $menu_links['orders'] );
            // Remove Orders
        } elseif ( 'wcz-account-orders-edit' == get_option( 'wcz-account-orders-tab', woocustomizer_library_get_default( 'wcz-account-orders-tab' ) ) ) {
            $menu_links['orders'] = esc_html( get_option( 'wcz-account-tab-orders-tab', woocustomizer_library_get_default( 'wcz-account-tab-orders-tab' ) ) );
        } else {
            $menu_links['orders'] = esc_html__( 'Orders', 'woocustomizer' );
        }
        
        
        if ( 'wcz-account-downloads-remove' == get_option( 'wcz-account-downloads-tab', woocustomizer_library_get_default( 'wcz-account-downloads-tab' ) ) ) {
            unset( $menu_links['downloads'] );
            // Remove Downloads
        } elseif ( 'wcz-account-downloads-edit' == get_option( 'wcz-account-downloads-tab', woocustomizer_library_get_default( 'wcz-account-downloads-tab' ) ) ) {
            $menu_links['downloads'] = esc_html( get_option( 'wcz-account-tab-downloads-tab', woocustomizer_library_get_default( 'wcz-account-tab-downloads-tab' ) ) );
        } else {
            $menu_links['downloads'] = esc_html__( 'Downloads', 'woocustomizer' );
        }
        
        
        if ( 'wcz-account-address-remove' == get_option( 'wcz-account-address-tab', woocustomizer_library_get_default( 'wcz-account-address-tab' ) ) ) {
            unset( $menu_links['edit-address'] );
            // Addresses
        } elseif ( 'wcz-account-address-edit' == get_option( 'wcz-account-address-tab', woocustomizer_library_get_default( 'wcz-account-address-tab' ) ) ) {
            $menu_links['edit-address'] = esc_html( get_option( 'wcz-account-tab-address-tab', woocustomizer_library_get_default( 'wcz-account-tab-address-tab' ) ) );
        } else {
            $menu_links['edit-address'] = esc_html__( 'Addresses', 'woocustomizer' );
        }
        
        
        if ( 'wcz-account-details-remove' == get_option( 'wcz-account-details-tab', woocustomizer_library_get_default( 'wcz-account-details-tab' ) ) ) {
            unset( $menu_links['edit-account'] );
            // Remove Account details tab
        } elseif ( 'wcz-account-details-edit' == get_option( 'wcz-account-details-tab', woocustomizer_library_get_default( 'wcz-account-details-tab' ) ) ) {
            $menu_links['edit-account'] = esc_html( get_option( 'wcz-account-tab-details-tab', woocustomizer_library_get_default( 'wcz-account-tab-details-tab' ) ) );
        } else {
            $menu_links['edit-account'] = esc_html__( 'Account details', 'woocustomizer' );
        }
        
        // Remove Logout to add back after custom tabs
        unset( $menu_links['customer-logout'] );
        // Only Available in WooCustomizer Pro
        // $menu_links['customer-logout'] = $logout;
        
        if ( 'wcz-account-logout-remove' == get_option( 'wcz-account-logout-tab', woocustomizer_library_get_default( 'wcz-account-logout-tab' ) ) ) {
            unset( $menu_links['customer-logout'] );
            // Remove Logout link
        } elseif ( 'wcz-account-logout-edit' == get_option( 'wcz-account-logout-tab', woocustomizer_library_get_default( 'wcz-account-logout-tab' ) ) ) {
            $menu_links['customer-logout'] = esc_html( get_option( 'wcz-account-tab-logout-tab', woocustomizer_library_get_default( 'wcz-account-tab-logout-tab' ) ) );
        }
        
        return $menu_links;
    }

}
add_filter( 'woocommerce_account_menu_items', 'wcz_remove_account_links' );
/**
 * Edit the tabs Page Titles
 */
function wcz_account_endpoint_title( $title, $id )
{
    
    if ( is_wc_endpoint_url( 'orders' ) && !is_admin() && in_the_loop() && is_account_page() ) {
        // add your endpoint urls
        $title = esc_html( get_option( 'wcz-account-tab-orders-title', woocustomizer_library_get_default( 'wcz-account-tab-orders-title' ) ) );
        // change your entry-title
    } elseif ( is_wc_endpoint_url( 'downloads' ) && !is_admin() && in_the_loop() && is_account_page() ) {
        $title = esc_html( get_option( 'wcz-account-tab-downloads-title', woocustomizer_library_get_default( 'wcz-account-tab-downloads-title' ) ) );
    } elseif ( is_wc_endpoint_url( 'edit-address' ) && !is_admin() && in_the_loop() && is_account_page() ) {
        $title = esc_html( get_option( 'wcz-account-tab-address-title', woocustomizer_library_get_default( 'wcz-account-tab-address-title' ) ) );
    } elseif ( is_wc_endpoint_url( 'edit-account' ) && !is_admin() && in_the_loop() && is_account_page() ) {
        $title = esc_html( get_option( 'wcz-account-tab-details-title', woocustomizer_library_get_default( 'wcz-account-tab-details-title' ) ) );
    }
    
    return $title;
}

/**
 * ------------------------------------------------------------------------------------ Remove/Edit selected My Account Tabs & Titles.
 */
/**
 * Add Menu Login / Logout Navigation Item.
 */
if ( !function_exists( 'wcz_add_menu_login_logout' ) ) {
    function wcz_add_menu_login_logout( $items, $args )
    {
        
        if ( $args->theme_location == get_option( 'wcz-login-logout-menu', woocustomizer_library_get_default( 'wcz-login-logout-menu' ) ) ) {
            $wcz_login_redirecturl = get_option( 'wcz-login-redirect-page', get_option( 'page_on_front' ) );
            $wcz_login_url = ( !empty(get_option( 'woocommerce_myaccount_page_id' )) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url( get_page_link( $wcz_login_redirecturl ) ) );
            $wcz_logout_redirecturl = get_page_link( get_option( 'wcz-logout-redirect-page', get_option( 'page_on_front' ) ) );
            $wcz_login_txt = get_option( 'wcz-login-text', woocustomizer_library_get_default( 'wcz-login-text' ) );
            $wcz_logout_txt = get_option( 'wcz-logout-text', woocustomizer_library_get_default( 'wcz-logout-text' ) );
            $items .= '<li class="wcz-login-logout">';
            ob_start();
            
            if ( is_user_logged_in() ) {
                ?>
					<a href="<?php 
                echo  esc_url( wp_logout_url( $wcz_logout_redirecturl ) ) ;
                ?>"><?php 
                echo  esc_html( $wcz_logout_txt ) ;
                ?></a>
				<?php 
            } else {
                ?>
					<a href="<?php 
                echo  esc_url( $wcz_login_url ) ;
                ?>"><?php 
                echo  esc_html( $wcz_login_txt ) ;
                ?></a>
				<?php 
            }
            
            $items .= ob_get_clean();
            $items .= '</li>';
        }
        
        return $items;
    }

}
add_filter(
    'wp_nav_menu_items',
    'wcz_add_menu_login_logout',
    10,
    2
);
// Login Redirect
function wcz_menu_login_redirect( $redirect )
{
    $wcz_redirect_page_id = url_to_postid( $redirect );
    $wcz_checkout_page_id = wc_get_page_id( 'checkout' );
    $wcz_new_redirect = get_option( 'wcz-login-redirect-page', get_option( 'page_on_front' ) );
    if ( $wcz_redirect_page_id == $wcz_checkout_page_id ) {
        return $redirect;
    }
    return get_page_link( $wcz_new_redirect );
}

if ( 'none' != get_option( 'wcz-login-logout-menu', woocustomizer_library_get_default( 'wcz-login-logout-menu' ) ) ) {
    add_filter( 'woocommerce_login_redirect', 'wcz_menu_login_redirect' );
}
// Only Available in WooCustomizer Pro