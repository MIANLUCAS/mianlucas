<?php

// Helpers
include_once( dirname( __FILE__ ) . '/wc/helpers.php' );

//Include shortcodes
include_once( dirname( __FILE__ ) . '/wp/from-the-blog.php' );
include_once( dirname( __FILE__ ) . '/wp/from-the-blog-listing.php' );
include_once( dirname( __FILE__ ) . '/wp/banner.php' );

//Mixed shortcodes
include_once( dirname( __FILE__ ) . '/wp/mixed/blog-posts-mixed.php' );

if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

	//Include shortcodes
	include_once( dirname( __FILE__ ) . '/wc/product-categories.php' );
	include_once( dirname( __FILE__ ) . '/wc/single-product.php' );
	include_once( dirname( __FILE__ ) . '/wc/add-to-cart.php' );

	//Mixed shortcodes
	include_once( dirname( __FILE__ ) . '/wc/mixed/recent-products-mixed.php' );
	include_once( dirname( __FILE__ ) . '/wc/mixed/featured-products-mixed.php' );
	include_once( dirname( __FILE__ ) . '/wc/mixed/sale-products-mixed.php' );
	include_once( dirname( __FILE__ ) . '/wc/mixed/best-selling-products-mixed.php' );
	include_once( dirname( __FILE__ ) . '/wc/mixed/top-rated-products-mixed.php' );
	include_once( dirname( __FILE__ ) . '/wc/mixed/product-category-mixed.php' );
	include_once( dirname( __FILE__ ) . '/wc/mixed/products-mixed.php' );
	include_once( dirname( __FILE__ ) . '/wc/mixed/products-by-attribute-mixed.php' );
	include_once( dirname( __FILE__ ) . '/wc/mixed/lookbook-mixed.php' );

	//Sliders shortcodes
	include_once( dirname( __FILE__ ) . '/wc/sliders/recent-products-slider.php' );
	include_once( dirname( __FILE__ ) . '/wc/sliders/featured-products-slider.php' );
	include_once( dirname( __FILE__ ) . '/wc/sliders/sale-products-slider.php' );
	include_once( dirname( __FILE__ ) . '/wc/sliders/best-selling-products-slider.php' );
	include_once( dirname( __FILE__ ) . '/wc/sliders/top-rated-products-slider.php' );
	include_once( dirname( __FILE__ ) . '/wc/sliders/product-category-slider.php' );
	include_once( dirname( __FILE__ ) . '/wc/sliders/products-slider.php' );
	include_once( dirname( __FILE__ ) . '/wc/sliders/products-by-attribute-slider.php' );
}

add_action( 'wp_enqueue_scripts', 'getbowtied_mt_shortcodes_styles', 99 );
function getbowtied_mt_shortcodes_styles() {
	wp_register_style('mrtailor-banner-shortcode-styles', plugins_url( 'assets/css/banner.css', __FILE__ ), NULL );
	wp_register_style('mrtailor-from-the-blog-list-shortcode-styles', plugins_url( 'assets/css/from-the-blog-list.css', __FILE__ ), NULL );
	wp_register_style('mrtailor-from-the-blog-slider-shortcode-styles',	plugins_url( 'assets/css/from-the-blog-slider.css', __FILE__ ), NULL );
	wp_register_style('mrtailor-single-product-shortcode-styles',	plugins_url( 'assets/css/single-product.css', __FILE__ ), NULL );
	wp_register_style('mrtailor-lookbook-shortcode-styles',	plugins_url( 'assets/css/lookbook.css', __FILE__ ), NULL );
	wp_register_style('mrtailor-wc-products-slider-styles',	plugins_url( 'assets/css/wc-products-slider.css', __FILE__ ), NULL );
}

add_action( 'wp_enqueue_scripts', 'getbowtied_mt_shortcodes_scripts', 99 );
function getbowtied_mt_shortcodes_scripts() {
	wp_register_script('mrtailor-lookbook-shortcode-script', 	plugins_url( 'assets/js/lookbook.js', __FILE__ ), array('jquery') );

	wp_register_script(
		'mrtailor-wc-products-slider-script',
		plugins_url( 'assets/js/wc-products-slider.js', __FILE__ ),
		array('jquery')
	);

	wp_register_script(
		'mrtailor-from-the-blog-slider-script',
		plugins_url( 'assets/js/from-the-blog.js', __FILE__ ),
		array('jquery')
	);
}

function getbowtied_mt_wb_shortcodes() {
	include_once( dirname( __FILE__ ) . '/wb/blog-posts.php' );
	include_once( dirname( __FILE__ ) . '/wb/banner.php' );
	include_once( dirname( __FILE__ ) . '/wb/title.php' );
	include_once( dirname( __FILE__ ) . '/wb/output/title.php' );

	if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		include_once( dirname( __FILE__ ) . '/wb/wc-recent-products.php' );
		include_once( dirname( __FILE__ ) . '/wb/wc-featured-products.php' );
		include_once( dirname( __FILE__ ) . '/wb/wc-products-by-category.php' );
		include_once( dirname( __FILE__ ) . '/wb/wc-products-by-attribute.php' );
		include_once( dirname( __FILE__ ) . '/wb/wc-product-by-id-sku.php' );
		include_once( dirname( __FILE__ ) . '/wb/wc-products-by-ids-skus.php' );
		include_once( dirname( __FILE__ ) . '/wb/wc-sale-products.php' );
		include_once( dirname( __FILE__ ) . '/wb/wc-top-rated-products.php' );
		include_once( dirname( __FILE__ ) . '/wb/wc-best-selling-products.php' );
		include_once( dirname( __FILE__ ) . '/wb/wc-add-to-cart-button-custom.php' );
		include_once( dirname( __FILE__ ) . '/wb/wc-product-categories.php' );
		include_once( dirname( __FILE__ ) . '/wb/wc-product-categories-grid.php' );
		include_once( dirname( __FILE__ ) . '/wb/lookbook.php' );
	}
}

// // Add Shortcodes to WP Bakery
add_action( 'init', function() {
	if ( class_exists( 'Vc_Manager' ) ) {
		getbowtied_mt_wb_shortcodes();
	}
}, 500);
