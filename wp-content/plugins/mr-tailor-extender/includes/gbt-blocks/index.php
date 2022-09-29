<?php

global $theme;

//==============================================================================
//	Main Editor Styles
//==============================================================================
add_action( 'enqueue_block_editor_assets', function() {
    wp_enqueue_style(
    	'getbowtied-mt-product-blocks-editor-styles',
    	plugins_url( 'assets/css/editor.css', __FILE__ ),
    	array( 'wp-edit-blocks' )
    );
});

//==============================================================================
//	Main JS
//==============================================================================
add_action( 'admin_init', 'getbowtied_mt_product_blocks_scripts' );
if ( ! function_exists( 'getbowtied_mt_product_blocks_scripts' ) ) {
	function getbowtied_mt_product_blocks_scripts() {

		wp_enqueue_script(
			'getbowtied-mt-product-blocks-editor-scripts',
			plugins_url( 'assets/js/main.js', __FILE__ ),
			array( 'wp-blocks', 'jquery' )
		);

	}
}

$theme = wp_get_theme();

//==============================================================================
//  Load Blocks
//==============================================================================

// Mr. Tailor Dependent Blocks
if ( $theme->template == 'mrtailor') {
    include_once 'social_media_profiles/block.php';
}

include_once 'posts_grid/block.php';
include_once 'posts_slider/block.php';
include_once 'banner/block.php';
