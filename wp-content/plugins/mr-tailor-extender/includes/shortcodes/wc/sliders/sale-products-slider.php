<?php

// [sale_products_slider]
function mt_ext_shortcode_sale_products_slider($atts, $content = null) {

	wp_enqueue_style('swiper');
    wp_enqueue_script('swiper');

	wp_enqueue_script( 'mrtailor-wc-products-slider-script' );
	wp_enqueue_style( 'mrtailor-wc-products-slider-styles' );

	extract(shortcode_atts(array(
		'title' => '',
		'per_page'  => '12',
		'columns'  => '4',
		'layout'  => 'listing',
        'orderby' => 'date',
        'order' => 'desc'
	), $atts));

	ob_start();

	// Get products on sale
	$product_ids_on_sale = wc_get_product_ids_on_sale();
	$product_ids_on_sale[] = 0;

	$meta_query = WC()->query->get_meta_query();

	$args = array(
		'posts_per_page'	=> $per_page,
		'no_found_rows' 	=> 1,
		'post_status' 		=> 'publish',
		'post_type' 		=> 'product',
		'orderby' 			=> $orderby,
		'order' 			=> $order,
		'meta_query' 		=> $meta_query,
		'post__in'			=> $product_ids_on_sale
	);

    $products = new WP_Query( $args );

	mt_products_slider( 'sale-products', $products, $title, $columns );

	wp_reset_postdata();
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode( "sale_products_slider", "mt_ext_shortcode_sale_products_slider" );
