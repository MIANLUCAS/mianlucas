<?php

/*
 * WooCommerce Custom Sale.
 */
add_filter( 'woocommerce_sale_flash', 'mrtailor_custom_sale_tag_sale_flash', 10, 3 );
function mrtailor_custom_sale_tag_sale_flash( $original, $post, $product ) {

	if ( MrTailor_Opt::getOption( 'sale_text' ) != '' ) {
		echo '<span class="onsale">'. esc_html(MrTailor_Opt::getOption( 'sale_text' ), 'mr_tailor') .'</span>';
	} else {
		echo '';
	}

	return;
}

/*
 * Udpate cart counter.
 */
add_filter('woocommerce_add_to_cart_fragments', 'mrtailor_shopping_bag_items_number');
function mrtailor_shopping_bag_items_number( $fragments ) {
	global $woocommerce;
	ob_start(); ?>

    <span class="shopping_bag_items_number"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
	<?php
	$fragments['.shopping_bag_items_number'] = ob_get_clean();

	return $fragments;
}

add_filter( 'woocommerce_widget_cart_is_hidden', 'mrtailor_always_show_cart_widget', 40, 0 );
function mrtailor_always_show_cart_widget() {

    return false;
}

/*
 * Limit number of cross-sells.
 */
add_filter('woocommerce_cross_sells_total', 'mrtailor_cart_cross_sell_total');
function mrtailor_cart_cross_sell_total( $total ) {
	$total = '2';

	return $total;
}

/**
 * Breaks the product name into title & separate variations
 *
 * @param  [string] $product_name
 *
 * @return [string] Html of product name
 */
add_filter( 'woocommerce_cart_item_name', 'mrtailor_modify_cart_title' );
function mrtailor_modify_cart_title( $product_name ) {

	$product_info = explode('&ndash;', $product_name);

	if ( sizeOf( $product_info ) == 1 ){

		return $product_name;

	} else {

		$product_variations = array_pop($product_info);

		$product_info = array(implode('&ndash;', $product_info), $product_variations);

		$output = $product_info[0]; //product name

	    $output .= "</a><div class='product-variations'>";

		$product_variations = explode(',', $product_variations);

		foreach( $product_variations as $variation ) {

			$variation = explode(': ', $variation);

			if( sizeOf( $variation ) > 1 ) {

				$output .= "<span class='product-variation'><b>" . $variation[0] . ":</b> " . strtoupper( $variation[1] ) . "</span>";

			} else {

				return $product_name;
			}
		}

		$output .= "</div>";

		return $output;
	}
}

/**
 * Change the breadcrumb separator
 */
add_filter( 'woocommerce_breadcrumb_defaults', 'mrtailor_change_breadcrumb_delimiter' );
function mrtailor_change_breadcrumb_delimiter( $defaults ) {
	$defaults['delimiter'] = '<span class="breadcrumb-del">/</span>';
	return $defaults;
}

add_filter( 'woocommerce_cross_sells_columns', function() { return 4; } );

/**
 * Change Product Gallery Thumbnails Size.
 */
add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
    return array(
        'width' => 300,
        'height' => 300,
        'crop' => 1,
    );
} );
