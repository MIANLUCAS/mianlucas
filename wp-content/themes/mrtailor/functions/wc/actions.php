<?php

/*
 * WooCommerce Custom Out of Stock.
 */
add_action( 'init', 'mrtailor_out_of_stock' );
function mrtailor_out_of_stock() {
	if ( !empty(MrTailor_Opt::getOption( 'out_of_stock_text' )) ) {
		add_filter( 'woocommerce_get_availability', 'custom_get_availability', 1, 2);
	}
}
function custom_get_availability( $availability, $_product ) {
	if ( !$_product->is_in_stock() ) {
		$availability['availability'] = esc_html(MrTailor_Opt::getOption( 'out_of_stock_text' ), 'mr_tailor');
	}
	return $availability;
}

/*
 * Set woocommerce images sizes.
 */
add_action( 'after_switch_theme', 'mrtailor_woocommerce_image_dimensions', 1 );
function mrtailor_woocommerce_image_dimensions() {
	global $pagenow;

	if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
		return;
	}

  	$catalog = array(
		'width' 	=> '350',	// px
		'height'	=> '435',	// px
		'crop'		=> 1 		// true
	);

	$single = array(
		'width' 	=> '570',	// px
		'height'	=> '708',	// px
		'crop'		=> 1 		// true
	);

	$thumbnail = array(
		'width' 	=> '70',	// px
		'height'	=> '87',	// px
		'crop'		=> 0 		// false
	);

	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
	update_option( 'shop_single_image_size', $single ); 		// Single product image
	update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
}

/**
 * WooCommerce Cart is empty remove notice class.
 */
remove_action('woocommerce_cart_is_empty', 'wc_empty_cart_message', 10);
add_action('woocommerce_cart_is_empty', 'mrtailor_empty_cart_message', 10);
function mrtailor_empty_cart_message() {
	echo '<p class="cart-empty">' . wp_kses_post( apply_filters( 'wc_empty_cart_message', __( 'Your cart is currently empty.', 'woocommerce' ) ) ) . '</p>';
}
