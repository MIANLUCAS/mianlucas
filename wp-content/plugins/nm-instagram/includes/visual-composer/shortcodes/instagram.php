<?php
    /*
     *  Shortcode: nm_instagram - Get template directory
     */
    function nm_shortcode_instagram_template_dir( $file ) {
        // Get theme/child-theme directory
        $theme_template = get_stylesheet_directory() . '/' . $file;

        // Does a file exist in the child-theme directory?
        if ( file_exists( $theme_template ) ) {
            return $theme_template;
        } else {
            return NM_INSTAGRAM_DIR . '/templates/' . $file;
        }
    }
    
    
	/*
     *  Shortcode: nm_instagram
     */
	function nm_shortcode_instagram( $atts, $content = NULL ) {
        global $nm_instagram_gallery;
        $nm_instagram_gallery = array(
            'atts'  => array(),
            'items' => array()
        );
        
        $atts = shortcode_atts( array(
			'image_limit'               => 6,
			'images_per_row'            => 6,
			'image_width'               => 320,
            'image_spacing_class'       => '',
            'instagram_user_link'       => ''
		), $atts );
        
        // Get WPZOOM Instagram API
        $wpzoom_api = NM_Wpzoom_Instagram_Widget_API::getInstance();
        // Get Instagram items/images
        $items = $wpzoom_api->get_items( $atts['image_limit'], $atts['image_width'] );
        
        if ( ! is_array( $items ) ) {
            $output = '<p class="nm-instagram-gallery-error">' . esc_html__( 'No results from Instagram. Please check your Access Token on the "Theme Settings > Instagram" page.', 'nm-instagram' ) . '</p>';
		} else {
            if ( isset( $items['items'] ) && ! empty( $items['items'] ) ) {
                $nm_instagram_gallery['atts'] = $atts;
                $nm_instagram_gallery['items'] = $items;

                // Include gallery template
                $gallery_template_dir = nm_shortcode_instagram_template_dir( 'nm-instagram-gallery.php' );
                
                ob_start();
                include( $gallery_template_dir );
                $output = ob_get_clean();
            } else {
                $output = '<p class="nm-instagram-gallery-error">' . esc_html__( 'No images available from Instagram.', 'nm-instagram' ) . '</p>';
            }
		}
        
        return $output;
	}
	
	add_shortcode( 'nm_instagram', 'nm_shortcode_instagram' );