<?php

// Frontend Styles
add_action( 'wp_enqueue_scripts', 'mrtailor_custom_styles', 100 );
function mrtailor_custom_styles() {

	$path = get_template_directory() . '/inc/custom-styles/frontend/';

	$custom_style = '';

    include_once( $path . 'globals.php' );
	include_once( $path . 'fonts.php' );
	include_once( $path . 'base-font.php' );
    include_once( $path . 'body-color.php' );
    include_once( $path . 'headings-color.php' );
    include_once( $path . 'accent-color.php' );
    include_once( $path . 'header.php' );
    include_once( $path . 'icons.php' );
    include_once( $path . 'footer.php' );

	$custom_style = mrtailor_compress_styles($custom_style);

	wp_add_inline_style( 'mr_tailor-default-style', $custom_style );
}

// Backend Styles
add_action( 'admin_enqueue_scripts', 'mrtailor_custom_admin_styles', 99 );
function mrtailor_custom_admin_styles() {

	$path = get_template_directory() . '/inc/custom-styles/backend/';

	$current_screen = get_current_screen();
	if ( method_exists($current_screen, 'is_block_editor') && $current_screen->is_block_editor() ) {

		$custom_style = '';

		include_once( $path . 'gutenberg.php' );

		$custom_style = mrtailor_compress_styles($custom_style);

		wp_add_inline_style( 'mr_tailor_admin_styles', $custom_style );
	}
}

/**
 * Compress custom styles.
 */
function mrtailor_compress_styles( $minify ) {
	$minify = preg_replace('/\/\*((?!\*\/).)*\*\//','',$minify); // negative look ahead
	$minify = preg_replace('/\s{2,}/',' ',$minify);
	$minify = preg_replace('/\s*([:;{}])\s*/','$1',$minify);
	$minify = preg_replace('/;}/','}',$minify);

	return $minify;
}
