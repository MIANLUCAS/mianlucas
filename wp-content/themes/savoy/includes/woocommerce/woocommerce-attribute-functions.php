<?php

/* 
 * WooCommerce - Attribute functions
=============================================================== */

global $nm_theme_options, $nm_globals;



/*
 * Product attribute: Get properties
 *
 * Note: Code from "get_tax_attribute()" function in the "../variation-swatches-for-woocommerce.php" file of the "Variation Swatches for WooCommerce" plugin
 */
function nm_woocommerce_get_taxonomy_attribute( $taxonomy ) {
    global $wpdb, $nm_globals;

    // Returned cached data if available
    if ( isset( $nm_globals['pa_cache'][$taxonomy] ) ) {
        return $nm_globals['pa_cache'][$taxonomy];
    }

    $attr = substr( $taxonomy, 3 );
    $attr = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '$attr'" );

    // Save data to avoid multiple database calls
    $nm_globals['pa_cache'][$taxonomy] = $attr;

    return $attr;
}



/*
 *  Widget: Layered nav (color) - Include color element
 */
if ( $nm_theme_options['shop_filters_custom_controls'] ) {
    function nm_woocommerce_layered_nav_count( $term_html, $term, $link, $count ) {
        global $nm_globals;

        // Get attribute type
        $attr = nm_woocommerce_get_taxonomy_attribute( $term->taxonomy );
        $attr_type = ( $attr ) ? $attr->attribute_type : '';

        if ( 'color' == $attr_type || 'pa_' . $nm_globals['pa_color_slug'] == $term->taxonomy ) {
            // Save data in global variable to avoid getting the "nm_pa_colors" option multiple times
            if ( ! isset( $nm_globals['pa_colors'] ) ) {
                $nm_globals['pa_colors'] = get_option( 'nm_pa_colors' );
            }

            $id = $term->term_id;

            $color = ( isset( $nm_globals['pa_colors'][$id] ) ) ? $nm_globals['pa_colors'][$id] : '#c0c0c0';
            $color_html = '<i style="background:' . esc_attr( $color ) . ';" class="nm-pa-color nm-pa-color-' . esc_attr( strtolower( $term->slug ) ) . '"></i>';
            
            // Code from "layered_nav_list()" function in "../plugins/woocommerce/includes/widgets/class-wc-widget-layered-nav.php" file
            if ( $count > 0 ) {
                $term_html = '<a rel="nofollow" href="' . $link . '">' . $color_html . esc_html( $term->name ) . '</a>';
			} else {
				$term_html = '<span>' . $color_html . esc_html( $term->name ) . '</span>';
			}
        }

        return $term_html;
    }
    add_filter( 'woocommerce_layered_nav_term_html', 'nm_woocommerce_layered_nav_count', 1, 4 );
}



/*
 *  Product page: Variation controls - Code from "wc_dropdown_variation_attribute_options()" function in "../woocommerce/includes/wc-template-functions.php"
 */
if ( $nm_theme_options['product_custom_controls'] ) {
    function nm_variation_attribute_options( $html, $args ) {
        global $nm_globals;

        $attr = nm_woocommerce_get_taxonomy_attribute( $args['attribute'] );
        $variation_type = ( $attr ) ? $attr->attribute_type : null;

        // Is this a custom variation-control attribute?
        if ( ! $variation_type || ! array_key_exists( $variation_type, $nm_globals['pa_variation_controls'] ) ) {
            return $html;
        }

        $options      = $args['options'];
        $product      = $args['product'];
        $attribute    = $args['attribute'];

        if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
            $attributes = $product->get_variation_attributes();
            $options    = $attributes[ $attribute ];
        }

        // Hide default select-box
        $html = '<div class="nm-select-hidden">' . $html . '</div>';

        $html .= '<ul class="nm-variation-control nm-variation-control-'. esc_attr( $variation_type ) .'">';

        if ( ! empty( $options ) ) {
            $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

            switch ( $variation_type ) {
                case 'color' :

                    // Save data in global variable to avoid getting the "nm_pa_colors" option multiple times
                    if ( ! isset( $nm_globals['pa_colors'] ) ) {
                        $nm_globals['pa_colors'] = get_option( 'nm_pa_colors' );
                    }

                    foreach ( $terms as $term ) {
                        if ( in_array( $term->slug, $options, true ) ) {
                            $selected_class_attr = ( $args['selected'] === $term->slug ) ? ' class="selected"' : '';
                            $color = ( isset( $nm_globals['pa_colors'][$term->term_id] ) ) ? $nm_globals['pa_colors'][$term->term_id] : '#ccc';

                            $html .= '<li'. $selected_class_attr .' data-value="' . esc_attr( $term->slug ) . '">';
                            $html .= '<i style="background:' . esc_attr( $color ) . ';" class="nm-pa-color nm-pa-color-' . esc_attr( strtolower( $term->slug ) ) . '"></i>';
                            $html .= '<span>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</span>';
                            $html .= '</li>';
                        }
                    }

                    break;
                case 'image' :
                    
                    foreach ( $terms as $term ) {
                        if ( in_array( $term->slug, $options, true ) ) {
                            $selected_class_attr = ( $args['selected'] === $term->slug ) ? ' class="selected"' : '';
                            $image_id = absint( get_term_meta( $term->term_id, 'nm_pa_image_thumbnail_id', true ) );
                            $image_url = ( $image_id ) ? wp_get_attachment_url( $image_id ) : '';
                            
                            $html .= '<li'. $selected_class_attr .' data-value="' . esc_attr( $term->slug ) . '">';
                            $html .= '<div class="nm-pa-image-thumbnail-wrap"><img src="' . esc_url( $image_url ) . '" class="nm-pa-image-thumbnail"></div>';
                            $html .= '<span>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</span>';
                            $html .= '</li>';
                        }
                    }
                    
                    break;
                default :

                    foreach ( $terms as $term ) {
                        if ( in_array( $term->slug, $options, true ) ) {
                            $selected_class_attr = ( $args['selected'] === $term->slug ) ? ' class="selected"' : '';

                            $html .= '<li'. $selected_class_attr .' data-value="' . esc_attr( $term->slug ) . '">';
                            $html .= '<span>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</span>';
                            $html .= '</li>';
                        }
                    }
            }
        }

        $html .= '</ul>';

        return $html;
    }
    add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'nm_variation_attribute_options', 10, 2 );
}
