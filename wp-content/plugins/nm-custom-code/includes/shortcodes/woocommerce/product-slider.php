<?php
	
	// Shortcode: nm_product_slider
	function nm_shortcode_product_slider( $atts, $content = NULL ) {
		if ( function_exists( 'nm_add_page_include' ) ) {
            nm_add_page_include( 'product-slider' );
        }
		
		extract( shortcode_atts( array(
			'shortcode'  	    => 'recent_products',
            'category'          => '',
			'per_page'          => '12',
			'columns'	        => '4',
            'columns_mobile'    => '1',
			'orderby'	        => 'date',
			'order'		        => 'DESC',
            'arrows'            => ''
		), $atts ) );
		
        $columns_escaped = intval( $columns );
        $columns_mobile_escaped = intval( $columns_mobile );
        $data_settings = 'data-slides-to-show="' . $columns_escaped . '" data-slides-to-scroll="' . $columns_escaped . '" data-slides-to-show-mobile="' . $columns_mobile_escaped . '"';
        
        $category_param = ( $shortcode == 'product_category' ) ? ' category="' . intval( $category ) . '"' : '';
        
        if ( $arrows !== '' ) {
            $data_settings .= ' data-arrows="true"';
        }
        
		$shortcode_string = '[' . $shortcode . ' per_page="' . intval( $per_page ) . '" columns="' . $columns_escaped . '" orderby="' . $orderby . '" order="' . $order . '"' . $category_param . ']';
		
        return '<div class="nm-product-slider col-' . $columns_escaped . '" ' . $data_settings . '>' . do_shortcode( $shortcode_string ) . '</div>';
	}
	
	add_shortcode( 'nm_product_slider', 'nm_shortcode_product_slider' );
	