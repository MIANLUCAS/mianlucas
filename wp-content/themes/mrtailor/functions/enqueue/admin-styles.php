<?php

/**
 * Enqueue admin stylesheet.
 */
function mrtailor_admin_styles() {
    if ( is_admin() ) {

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
            'mr_tailor_admin_styles',
            get_template_directory_uri() . '/css/admin/wp-admin-custom.css',
            false,
            mrtailor_theme_version(),
            'all'
        );

		if ( MT_WPBAKERY_IS_ACTIVE ) {
			wp_enqueue_style(
                'mr_tailor_visual_composer',
                get_template_directory_uri() . '/css/admin/visual-composer.css',
                false,
                mrtailor_theme_version(),
                'all'
            );
		}
    }
}
add_action( 'admin_enqueue_scripts', 'mrtailor_admin_styles' );
