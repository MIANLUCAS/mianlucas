<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//==============================================================================
//	Enqueue Editor Assets
//==============================================================================
add_action( 'enqueue_block_editor_assets', 'gbt_18_mt_banner_editor_assets' );
if ( ! function_exists( 'gbt_18_mt_banner_editor_assets' ) ) {
	function gbt_18_mt_banner_editor_assets() {

		wp_enqueue_script(
			'gbt_18_mt_banner_script',
			plugins_url( 'block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-editor', 'wp-i18n', 'wp-element' )
		);

		wp_enqueue_style(
			'gbt_18_mt_banner_editor_styles',
			plugins_url( 'assets/css/editor.css', __FILE__ ),
			array( 'wp-edit-blocks' )
		);
	}
}

//==============================================================================
//	Enqueue Frontend Assets
//==============================================================================
add_action( 'enqueue_block_assets', 'gbt_18_mt_banner_assets' );
if ( ! function_exists( 'gbt_18_mt_banner_assets' ) ) {
	function gbt_18_mt_banner_assets() {
		
		wp_enqueue_style(
			'gbt_18_mt_banner_styles',
			plugins_url( 'assets/css/style.css', __FILE__ ),
			array()
		);
	}
}