<?php
    
	// Shortcode: nm_product_categories
	function nm_shortcode_product_categories( $atts, $content = NULL ) {
		if ( function_exists( 'nm_add_page_include' ) ) {
            nm_add_page_include( 'product_categories' );
        }
		
		global $nm_globals, $woocommerce_loop;
		
        // Global settings
        $nm_globals['is_categories_shortcode'] = true;
        $nm_globals['categories_shortcode_heading_tag'] = isset( $atts['title_tag'] ) ? $atts['title_tag'] : 'h1'; // Categories heading tag
        
		// Set column sizes via the $woocommerce_loop global (large column is set via shortcode attribute)
		$woocommerce_loop['columns_xsmall'] = '1';
        $woocommerce_loop['columns_small'] = '1';
		$woocommerce_loop['columns_medium'] = '2';
		
        $class = '';
        
        if ( isset( $atts['packery'] ) && $atts['packery'] === '1' ) {
			if ( function_exists( 'nm_add_page_include' ) ) {
                nm_add_page_include( 'product_categories_masonry' );
            }
			
			$class = 'masonry-enabled nm-loader';
		}
		
        if ( class_exists( 'WC_Shortcodes' ) ) {
            return '<div class="nm-product-categories ' . esc_attr( $class ) . '">' . WC_Shortcodes::product_categories( $atts ) . '</div>';
        }
	}
	
	add_shortcode( 'nm_product_categories', 'nm_shortcode_product_categories' );
	