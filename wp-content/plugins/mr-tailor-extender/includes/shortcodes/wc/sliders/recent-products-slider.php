<?php

// [recent_products_slider]
function mt_ext_shortcode_recent_products_slider($atts, $content = null) {

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

	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'ignore_sticky_posts'   => 1,
		'posts_per_page' => $per_page
	);

    $products = new WP_Query( $args );

	mt_products_slider( 'recent-products', $products, $title, $columns );

	wp_reset_postdata();
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode( "recent_products_slider", "mt_ext_shortcode_recent_products_slider" );
