<?php

// frontend
function mrtailor_vendor_styles() {

	wp_enqueue_style( 'swiper', get_template_directory_uri() . '/css/_vendor/swiper/swiper.min.css', array(), '6.4.1', 	'all' );
}
add_action( 'wp_enqueue_scripts', 'mrtailor_vendor_styles', 98 );

function mrtailor_theme_styles() {

	// Enqueue Main Font.
    if( '1' === MrTailor_Opt::getOption( 'main_font_source', '1' ) ) {
        $main_font = MrTailor_Opt::getOption( 'main_font', 'DM Sans' );
		$font_display = MrTailor_Opt::getOption( 'main_font_face_display', 'swap' );
        $google_font_url = MrTailor_Fonts::get_google_font_url( $main_font, $font_display );
        if ( $google_font_url ) {
            wp_enqueue_style( 'mrtailor-google-main-font', $google_font_url, false, mrtailor_theme_version(), 'all' );
        }
    }

	// Enqueue Secondary Font.
    if( '1' === MrTailor_Opt::getOption( 'secondary_font_source', '1' ) ) {
        $secondary_font = MrTailor_Opt::getOption( 'secondary_font', 'DM Sans' );
		$font_display = MrTailor_Opt::getOption( 'secondary_font_face_display', 'swap' );
        $google_font_url = MrTailor_Fonts::get_google_font_url( $secondary_font, $font_display );
        if ( $google_font_url ) {
            wp_enqueue_style( 'mrtailor-google-secondary-font', $google_font_url, false, mrtailor_theme_version(), 'all' );
        }
    }

	wp_enqueue_style(
		'mr_tailor-styles',
		get_template_directory_uri() . '/css/styles.css',
		array(),
		mrtailor_theme_version(),
		'all'
	);

	wp_enqueue_style( 'mr_tailor-default-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'mrtailor_theme_styles', 99 );
