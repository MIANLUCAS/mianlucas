<?php

// [product_attribute_slider]
function mt_ext_shortcode_product_attribute_slider($atts, $content = null) {

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
        'order' => 'desc',
		'attribute' => '',
		'filter'    => ''
	), $atts));

	ob_start();

	$attribute 	= strstr( $attribute, 'pa_' ) ? sanitize_title( $attribute ) : 'pa_' . sanitize_title( $attribute );

	$args = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page'      => $per_page,
		'orderby'             => $orderby,
		'order'               => $order,
		'tax_query' 			=> array(
			array(
				'taxonomy' 	=> $attribute,
				'terms'     => array_map( 'sanitize_title', explode( ",", $filter ) ),
				'field' 	=> 'slug'
			),
			array(
		        'taxonomy'  => 'product_visibility',
		        'terms'     => array( 'exclude-from-catalog' ),
		        'field'     => 'name',
		        'operator'  => 'NOT IN',
		    )
		)
	);

    $products = new WP_Query( $args );

	mt_products_slider( 'attribute-products', $products, $title, $columns );

	wp_reset_postdata();
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode( "product_attribute_slider", "mt_ext_shortcode_product_attribute_slider" );
