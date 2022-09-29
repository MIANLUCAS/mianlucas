<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//==============================================================================
//	Enqueue Editor Assets
//==============================================================================
add_action( 'enqueue_block_editor_assets', 'gbt_18_mt_posts_grid_editor_assets' );
if ( ! function_exists( 'gbt_18_mt_posts_grid_editor_assets' ) ) {
	function gbt_18_mt_posts_grid_editor_assets() {
		
		wp_enqueue_script(
			'gbt_18_mt_posts_grid_script',
			plugins_url( 'block.js', dirname(__FILE__) ),
			array( 'wp-api-request', 'wp-blocks', 'wp-components', 'wp-editor', 'wp-i18n', 'wp-element' )
		);

		$language = isset($_GET['lang']) ? $_GET['lang'] : get_locale();

		wp_localize_script( 'gbt_18_mt_posts_grid_script', 'posts_grid_vars', array(
			'language' => $language
		) );

		wp_enqueue_style(
			'gbt_18_mt_posts_grid_editor_styles',
			plugins_url( 'assets/css/backend/editor.css', dirname(__FILE__) ),
			array( 'wp-edit-blocks' )
		);
	}
}

//==============================================================================
//	Enqueue Frontend Assets
//==============================================================================
add_action( 'enqueue_block_assets', 'gbt_18_mt_posts_grid_assets' );
if ( ! function_exists( 'gbt_18_mt_posts_grid_assets' ) ) {
	function gbt_18_mt_posts_grid_assets() {
		
		wp_enqueue_style(
			'gbt_18_mt_posts_grid_styles',
			plugins_url( 'assets/css/frontend/style.css', dirname(__FILE__) ),
			array()
		);
	}
}

//==============================================================================
//	Register Block Type
//==============================================================================
if ( function_exists( 'register_block_type' ) ) {
	register_block_type( 'getbowtied/mt-posts-grid', array(
		'attributes'      					=> array(
			'number'						=> array(
				'type'						=> 'number',
				'default'					=> '12',
			),
			'categoriesSavedIDs'			=> array(
				'type'						=> 'string',
				'default'					=> '',
			),
			'align'							=> array(
				'type'						=> 'string',
				'default'					=> 'center',
			),
			'orderby'						=> array(
				'type'						=> 'string',
				'default'					=> 'date_desc',
			),
			'columns'						=> array(
				'type'						=> 'number',
				'default'					=> '3'
			),
			'className'                     => array(
                'type'                      => 'string',
                'default'                   => 'is-style-default',
            ),
		),

		'render_callback' => 'gbt_18_mt_render_frontend_posts_grid',
	) );
}