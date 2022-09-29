<?php

function mrtailor_theme_scripts() {

	$file_path = get_template_directory_uri() . '/js/';
	$suffix    = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

	/** In Header **/
	wp_enqueue_script( 'modernizr', $file_path . '_vendor/modernizr.custom.js', '', '3.6.0', FALSE);

	if ( MrTailor_Opt::getOption( 'main_font_source' ) == "2" ) {
		if ( MrTailor_Opt::getOption( 'main_font_typekit_kit_id' ) != "" ) {
			wp_enqueue_script(
				'mr_tailor-main_font_typekit',
				'//use.typekit.net/' . MrTailor_Opt::getOption( 'main_font_typekit_kit_id' ) . '.js',
				array(),
				NULL,
				FALSE
			);
		}
	}

	if ( MrTailor_Opt::getOption( 'secondary_font_source' ) == "2" ) {
		if ( MrTailor_Opt::getOption( 'secondary_font_typekit_kit_id' ) != "" ) {
			wp_enqueue_script(
				'mr_tailor-secondary_font_typekit',
				'//use.typekit.net/' . MrTailor_Opt::getOption( 'secondary_font_typekit_kit_id' ) . '.js',
				array(),
				NULL,
				FALSE
			);
		}
	}

	if ( ( MrTailor_Opt::getOption( 'main_font_source' ) == "2" ) && ( MrTailor_Opt::getOption( 'secondary_font_source' ) == "2" ) ) {
		if ( ( MrTailor_Opt::getOption( 'main_font_typekit_kit_id' ) != "" ) || ( MrTailor_Opt::getOption( 'secondary_font_typekit_kit_id' ) != "" ) ) {
			if ( MrTailor_Opt::getOption( 'main_font_typekit_kit_id' ) == MrTailor_Opt::getOption( 'secondary_font_typekit_kit_id' ) ) {
				wp_dequeue_script('mr_tailor-secondary_font_typekit');
			}
		}
	}

	/** In Footer **/
	wp_enqueue_script( 'foundation-init-js', 	 $file_path . '_vendor/foundation.min.js', 			array('jquery'), '5.2.0', 	TRUE );
	wp_enqueue_script( 'isotope-js', 			 $file_path . '_vendor/isotope.pkgd.min.js', 		array('jquery'), 'v3.0.6', 	TRUE );
	wp_enqueue_script( 'imagesloaded', 			 $file_path . '_vendor/imagesloaded.js', 			array('jquery'), 'v4.1.4', 	TRUE );
	wp_enqueue_script( 'swiper', 				 $file_path . '_vendor/swiper-bundle.min.js', 		array('jquery'), '6.4.1', 	TRUE );
	wp_enqueue_script( 'select2', 				 $file_path . '_vendor/select2.min.js', 			array('jquery'), '3.5.1', 	TRUE );

	wp_enqueue_script( 'mr_tailor-scripts', 	 $file_path . 'scripts'.$suffix.'.js', 				array('jquery'), '1.0', 	TRUE );

	$mrtailor_scripts_vars_array = array(
		'stickyHeader'		 => MrTailor_Opt::getOption( 'sticky_header', true ),
		'gallery_lightbox'	 => MrTailor_Opt::getOption( 'product_gallery_lightbox', false ),
		'catalogMode' 		 => MrTailor_Opt::getOption( 'catalog_mode', false ),
		'products_animation' => MrTailor_Opt::getOption( 'products_animation', 'e2' ),
		'shop_pagination'	 => MrTailor_Opt::getOption( 'shop_pagination', 'classic' ),
		'open_minicart'		 => MrTailor_Opt::getOption( 'open_minicart', true )
	);

	wp_localize_script( 'mr_tailor-scripts', 'mrtailor_scripts_vars_array', $mrtailor_scripts_vars_array );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( ( MrTailor_Opt::getOption( 'main_font_source' ) == "2" ) || ( MrTailor_Opt::getOption( 'secondary_font_source' ) == "2" ) ) {
		if( ( MrTailor_Opt::getOption( 'main_font_typekit_kit_id' ) != "" ) || ( MrTailor_Opt::getOption( 'secondary_font_typekit_kit_id' ) != "" ) ) {
			$typekit = 'try{Typekit.load();}catch(e){}';
			wp_add_inline_script('mr_tailor-scripts', $typekit);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'mrtailor_theme_scripts', 99 );
