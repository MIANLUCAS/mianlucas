<?php

/**
 * Implements styles set in the theme customizer
 *
 * @package Customizer Library WooCommerce Designer
 */
if ( !function_exists( 'woocustomizer_customizer_library_build_styles' ) && class_exists( 'WooCustomizer_Library_Styles' ) ) {
    /**
     * Process user options to generate CSS needed to implement the choices.
     *
     * @since  1.0.0.
     *
     * @return void
     */
    function woocustomizer_customizer_library_build_styles()
    {
        // ----------------------------------------------------------------------------------------------- WCD Panel Settings
        // Remove All WooCommerce Breadcrumbs
        $setting = 'wcz-wc-remove-breadcrumbs';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        
        if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
            $wcz_shop_title = esc_attr( $mod );
            WooCustomizer_Library_Styles()->add( array(
                'selectors'    => array( 'body.woocommerce .woocommerce-breadcrumb' ),
                'declarations' => array(
                'display' => 'none !important',
            ),
            ) );
        }
        
        
        if ( 'wcz-btn-style-default' != get_option( 'wcz-btn-style', woocustomizer_library_get_default( 'wcz-btn-style' ) ) && get_option( 'wcz-wc-edit-btns', woocustomizer_library_get_default( 'wcz-wc-edit-btns' ) ) ) {
            // Button - Font Size
            $setting = 'wcz-btn-fsize';
            $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
            
            if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
                $wcz_btn_fsize = esc_attr( $mod );
                WooCustomizer_Library_Styles()->add( array(
                    'selectors'    => array( 'body.wcz-btn-style-plain.woocommerce ul.products li.product a.button,
					body.wcz-btn-style-detailed.woocommerce ul.products li.product a.button,
					body.wcz-btn-style-detailed.woocommerce .related.products ul.products li.product a.button,
					body.wcz-btn-style-plain.woocommerce.single-product div.product form.cart .button,
					body.wcz-btn-style-detailed.woocommerce.single-product div.product form.cart .button' ),
                    'declarations' => array(
                    'font-size' => $wcz_btn_fsize . 'px',
                ),
                ) );
            }
            
            // Button - Font Weight
            $setting = 'wcz-btn-fweight';
            $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
            
            if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
                $wcz_btn_fweight = esc_attr( $mod );
                WooCustomizer_Library_Styles()->add( array(
                    'selectors'    => array( 'body.wcz-btn-style-plain.woocommerce ul.products li.product a.button,
					body.wcz-btn-style-detailed.woocommerce ul.products li.product a.button,
					body.wcz-btn-style-detailed.woocommerce .related.products ul.products li.product a.button,
					body.wcz-btn-style-plain.woocommerce.single-product div.product form.cart .button,
					body.wcz-btn-style-detailed.woocommerce.single-product div.product form.cart .button' ),
                    'declarations' => array(
                    'font-weight' => '700',
                ),
                ) );
            }
            
            // Button - Color
            $setting = 'wcz-btn-bgcolor';
            $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
            
            if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
                $wcz_btn_bgcolor = wcz_sanitize_hex_color( $mod );
                $wcz_btn_bgrgb = woocustomizer_library_hex_to_rgb( $wcz_btn_bgcolor );
                WooCustomizer_Library_Styles()->add( array(
                    'selectors'    => array( 'body.wcz-btn-style-plain.woocommerce ul.products li.product a.button,
					body.wcz-btn-style-detailed.woocommerce ul.products li.product a.button,
					body.wcz-btn-style-detailed.woocommerce .related.products ul.products li.product a.button,
					body.wcz-btn-style-plain.woocommerce.single-product div.product form.cart .button,
					body.wcz-btn-style-detailed.woocommerce.single-product div.product form.cart .button' ),
                    'declarations' => array(
                    'background-color' => $wcz_btn_bgcolor,
                    'color'            => wcz_getContrastColor( $wcz_btn_bgcolor ) . ' !important',
                    'text-shadow'      => 'none',
                ),
                ) );
            }
            
            // Button - Hover Color
            $setting = 'wcz-btn-hovercolor';
            $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
            
            if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
                $wcz_btn_hovercolor = wcz_sanitize_hex_color( $mod );
                WooCustomizer_Library_Styles()->add( array(
                    'selectors'    => array( 'body.wcz-btn-style-plain.woocommerce ul.products li.product a.button:hover,
					body.wcz-btn-style-detailed.woocommerce ul.products li.product a.button:hover,
					body.wcz-btn-style-detailed.woocommerce .related.products ul.products li.product a.button:hover,
					body.wcz-btn-style-plain.woocommerce.single-product div.product form.cart .button:hover,
					body.wcz-btn-style-detailed.woocommerce.single-product div.product form.cart .button:hover' ),
                    'declarations' => array(
                    'background-color' => $wcz_btn_hovercolor,
                    'color'            => wcz_getContrastColor( $wcz_btn_hovercolor ) . ' !important',
                ),
                ) );
            }
            
            // Button - Border Radius
            $setting = 'wcz-btn-br';
            $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
            
            if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
                $wcz_btn_br = esc_attr( $mod );
                WooCustomizer_Library_Styles()->add( array(
                    'selectors'    => array( 'body.wcz-btn-style-plain.woocommerce ul.products li.product a.button,
					body.wcz-btn-style-detailed.woocommerce ul.products li.product a.button,
					body.wcz-btn-style-detailed.woocommerce .related.products ul.products li.product a.button,
					body.wcz-btn-style-plain.woocommerce.single-product div.product form.cart .button,
					body.wcz-btn-style-detailed.woocommerce.single-product div.product form.cart .button' ),
                    'declarations' => array(
                    'border-radius' => $wcz_btn_br . 'px',
                ),
                ) );
            }
            
            // Button - Padding
            $setting = 'wcz-btn-padding';
            $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
            
            if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
                $wcz_btn_pad = esc_attr( $mod );
                WooCustomizer_Library_Styles()->add( array(
                    'selectors'    => array( 'body.wcz-btn-style-plain.woocommerce ul.products li.product a.button,
					body.wcz-btn-style-detailed.woocommerce ul.products li.product a.button,
					body.wcz-btn-style-detailed.woocommerce .related.products ul.products li.product a.button,
					body.wcz-btn-style-plain.woocommerce.single-product div.product form.cart .button,
					body.wcz-btn-style-detailed.woocommerce.single-product div.product form.cart .button' ),
                    'declarations' => array(
                    'padding' => $wcz_btn_pad . 'px ' . $wcz_btn_pad * 2 . 'px ' . ($wcz_btn_pad + 1) . 'px',
                ),
                ) );
            }
        
        }
        
        
        if ( get_option( 'wcz-wc-edit-sale', woocustomizer_library_get_default( 'wcz-wc-edit-sale' ) ) ) {
            // Sale Banner - Font Size
            $setting = 'wcz-sale-fsize';
            $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
            
            if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
                $wcz_sale_fsize = esc_attr( $mod );
                WooCustomizer_Library_Styles()->add( array(
                    'selectors'    => array( 'body.wcz-edit-sale.woocommerce ul.products li.product span.onsale,
					body.wcz-edit-sale.single-product span.onsale,
					body.wcz-edit-sale .wcz-popup span.onsale' ),
                    'declarations' => array(
                    'font-size' => $wcz_sale_fsize . 'px',
                ),
                ) );
            }
            
            // Sale Banner - Font Weight
            $setting = 'wcz-sale-fweight';
            $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
            
            if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
                $wcz_sale_fweight = esc_attr( $mod );
                WooCustomizer_Library_Styles()->add( array(
                    'selectors'    => array( 'body.wcz-edit-sale.woocommerce ul.products li.product span.onsale,
					body.wcz-edit-sale.single-product span.onsale,
					body.wcz-edit-sale .wcz-popup span.onsale' ),
                    'declarations' => array(
                    'font-weight' => '700',
                ),
                ) );
            }
            
            // Sale Banner - Color
            $setting = 'wcz-sale-bgcolor';
            $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
            
            if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
                $wcz_sale_bgcolor = wcz_sanitize_hex_color( $mod );
                $wcz_sale_bgrgb = woocustomizer_library_hex_to_rgb( $wcz_sale_bgcolor );
                WooCustomizer_Library_Styles()->add( array(
                    'selectors'    => array( 'body.wcz-edit-sale.woocommerce ul.products li.product span.onsale,
					body.wcz-edit-sale.single-product span.onsale,
					.woocommerce span.wcz-ajaxsearch-result-sale,
					body.wcz-edit-sale .wcz-popup span.onsale' ),
                    'declarations' => array(
                    'background-color' => $wcz_sale_bgcolor,
                    'color'            => wcz_getContrastColor( $wcz_sale_bgcolor ),
                    'text-shadow'      => 'none',
                ),
                ) );
            }
            
            // Sale Banner - Border Radius
            $setting = 'wcz-sale-br';
            $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
            
            if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
                $wcz_sale_br = esc_attr( $mod );
                WooCustomizer_Library_Styles()->add( array(
                    'selectors'    => array( 'body.wcz-edit-sale.woocommerce ul.products li.product span.onsale,
					body.wcz-edit-sale.single-product span.onsale,
					body.wcz-edit-sale .wcz-popup span.onsale' ),
                    'declarations' => array(
                    'border-radius' => $wcz_sale_br . 'px',
                ),
                ) );
            }
            
            // Sale Banner - Padding
            $setting = 'wcz-sale-padding';
            $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
            
            if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
                $wcz_sale_pad = esc_attr( $mod );
                WooCustomizer_Library_Styles()->add( array(
                    'selectors'    => array( 'body.wcz-edit-sale.woocommerce ul.products li.product span.onsale,
					body.wcz-edit-sale.single-product span.onsale,
					body.wcz-edit-sale .wcz-popup span.onsale' ),
                    'declarations' => array(
                    'padding' => $wcz_sale_pad . 'px ' . $wcz_sale_pad * 2 . 'px ' . ($wcz_sale_pad + 1) . 'px',
                ),
                ) );
            }
        
        }
        
        // ----------------------------------------------------------------------------------------------- WCD Panel Settings
        // ----------------------------------------------------------------------------------------------- WCD Shop Page Settings
        // Remove Shop Page Breadcrumbs
        $setting = 'wcz-shop-remove-breadcrumbs';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        
        if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
            $wcz_shop_bc = esc_attr( $mod );
            WooCustomizer_Library_Styles()->add( array(
                'selectors'    => array( 'body.post-type-archive-product .woocommerce-breadcrumb' ),
                'declarations' => array(
                'display' => 'none !important',
            ),
            ) );
        }
        
        // Remove Shop Page Title
        $setting = 'wcz-shop-remove-title';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        
        if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
            $wcz_shop_title = esc_attr( $mod );
            WooCustomizer_Library_Styles()->add( array(
                'selectors'    => array( 'body.post-type-archive-product header.woocommerce-products-header' ),
                'declarations' => array(
                'display' => 'none !important',
            ),
            ) );
        }
        
        // Remove Shop Page Sorting
        $setting = 'wcz-shop-remove-sorting';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        
        if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
            $wcz_shop_sort = esc_attr( $mod );
            WooCustomizer_Library_Styles()->add( array(
                'selectors'    => array( 'body.woocommerce form.woocommerce-ordering' ),
                'declarations' => array(
                'display' => 'none !important',
            ),
            ) );
        }
        
        // Remove Shop Page Results
        $setting = 'wcz-shop-remove-result';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        
        if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
            $wcz_shop_result = esc_attr( $mod );
            WooCustomizer_Library_Styles()->add( array(
                'selectors'    => array( 'body.woocommerce p.woocommerce-result-count' ),
                'declarations' => array(
                'display' => 'none !important',
            ),
            ) );
        }
        
        // Remove Shop Archive Breadcrumbs
        $setting = 'wcz-shop-archives-remove-breadcrumbs';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        
        if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
            $wcz_arch_bc = esc_attr( $mod );
            WooCustomizer_Library_Styles()->add( array(
                'selectors'    => array( 'body.tax-product_cat .woocommerce-breadcrumb,
				body.tax-product_tag .woocommerce-breadcrumb' ),
                'declarations' => array(
                'display' => 'none !important',
            ),
            ) );
        }
        
        // Remove Shop Page Title
        $setting = 'wcz-shop-archives-remove-title';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        
        if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
            $wcz_arch_title = esc_attr( $mod );
            WooCustomizer_Library_Styles()->add( array(
                'selectors'    => array( 'body.tax-product_cat header.woocommerce-products-header,
				body.tax-product_tag header.woocommerce-products-header' ),
                'declarations' => array(
                'display' => 'none !important',
            ),
            ) );
        }
        
        
        if ( get_option( 'wcz-shop-new-badge', woocustomizer_library_get_default( 'wcz-shop-new-badge' ) ) ) {
            // "New" Badge Color
            $setting = 'wcz-shop-new-badge-color';
            $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
            
            if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
                $wcz_badge_bgcolor = wcz_sanitize_hex_color( $mod );
                $wcz_badge_bgrgb = woocustomizer_library_hex_to_rgb( $wcz_badge_bgcolor );
                WooCustomizer_Library_Styles()->add( array(
                    'selectors'    => array( 'div.wcz-new-product-badge span' ),
                    'declarations' => array(
                    'background-color' => $wcz_badge_bgcolor,
                    'color'            => wcz_getContrastColor( $wcz_badge_bgcolor ) . ' !important',
                ),
                ) );
            }
        
        }
        
        // ----------------------------------------------------------------------------------------------- WCD Shop Page Settings
        // ----------------------------------------------------------------------------------------------- WooCommerce Product Page Settings
        // Remove Product Breadcrumbs
        $setting = 'wcz-remove-product-breadcrumbs';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        
        if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
            $wcz_prod_bc = esc_attr( $mod );
            WooCustomizer_Library_Styles()->add( array(
                'selectors'    => array( 'body.single-product .woocommerce-breadcrumb' ),
                'declarations' => array(
                'display' => 'none !important',
            ),
            ) );
        }
        
        // Remove Product SKU, Cats & Tags
        $setting = 'wcz-remove-product-sku';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        
        if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
            $wcz_prod_sku = esc_attr( $mod );
            WooCustomizer_Library_Styles()->add( array(
                'selectors'    => array( 'body.single-product .product_meta .sku_wrapper' ),
                'declarations' => array(
                'display' => 'none !important',
            ),
            ) );
        }
        
        $setting = 'wcz-remove-product-cats';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        
        if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
            $wcz_prod_cats = esc_attr( $mod );
            WooCustomizer_Library_Styles()->add( array(
                'selectors'    => array( 'body.single-product .product_meta .posted_in' ),
                'declarations' => array(
                'display' => 'none !important',
            ),
            ) );
        }
        
        $setting = 'wcz-remove-product-tags';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        
        if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
            $wcz_prod_tags = esc_attr( $mod );
            WooCustomizer_Library_Styles()->add( array(
                'selectors'    => array( 'body.single-product .product_meta .tagged_as' ),
                'declarations' => array(
                'display' => 'none !important',
            ),
            ) );
        }
        
        // Remove Product Recommendations
        $setting = 'wcz-wcproduct-recomm';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        
        if ( $mod == 'wcz-wcproduct-recomm-remove' ) {
            $wcz_prod_recom = esc_attr( $mod );
            WooCustomizer_Library_Styles()->add( array(
                'selectors'    => array( 'body.single-product section.upsells' ),
                'declarations' => array(
                'display' => 'none !important',
            ),
            ) );
        }
        
        // Remove Product Related Products
        $setting = 'wcz-wcproduct-related';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        
        if ( $mod == 'wcz-wcproduct-related-remove' ) {
            $wcz_prod_rel = esc_attr( $mod );
            WooCustomizer_Library_Styles()->add( array(
                'selectors'    => array( '.single-product section.related.products' ),
                'declarations' => array(
                'display' => 'none !important',
            ),
            ) );
        }
        
        $setting = 'wcz-add-shop-button';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        
        if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
            $wcz_prod_shop_btn = esc_attr( $mod );
            WooCustomizer_Library_Styles()->add( array(
                'selectors'    => array( 'body.single-product a.wcz-continue' ),
                'declarations' => array(
                'margin-bottom' => '20px',
            ),
            ) );
        }
        
        // EXCLUDED FROM FREE VERSION -- This "if" above block will be auto removed from the Free version.
        // ----------------------------------------------------------------------------------------------- WooCommerce Product Page Settings
        // ----------------------------------------------------------------------------------------------- WooCommerce Account Settings
        // ----------------------------------------------------------------------------------------------- WooCommerce Account Settings
        // ----------------------------------------------------------------------------------------------- WooCommerce Cart Settings
        // Remove Update Button from Cart Page
        $setting = 'wcz-cart-ajax-update';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        if ( $mod !== woocustomizer_library_get_default( $setting ) ) {
            WooCustomizer_Library_Styles()->add( array(
                'selectors'    => array( '.woocommerce button[name="update_cart"],
				.woocommerce input[name="update_cart"]' ),
                'declarations' => array(
                'display' => 'none !important',
            ),
            ) );
        }
        // Remove Cross Sells heading
        $setting = 'wcz-wccart-recomm';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        
        if ( $mod == 'wcz-wccart-recomm-remove' ) {
            $wcz_cart_recom = esc_attr( $mod );
            WooCustomizer_Library_Styles()->add( array(
                'selectors'    => array( 'body.woocommerce-cart .cross-sells h2' ),
                'declarations' => array(
                'display' => 'none !important',
            ),
            ) );
        }
        
        // Remove Cart Totals heading
        $setting = 'wcz-wccart-totals';
        $mod = get_option( $setting, woocustomizer_library_get_default( $setting ) );
        
        if ( $mod == 'wcz-wccart-totals-remove' ) {
            $wcz_cart_recom = esc_attr( $mod );
            WooCustomizer_Library_Styles()->add( array(
                'selectors'    => array( 'body.woocommerce-cart .cart_totals h2' ),
                'declarations' => array(
                'display' => 'none !important',
            ),
            ) );
        }
        
        // EXCLUDED FROM FREE VERSION -- This "if" above block will be auto removed from the Free version.
    }

}
add_action( 'customizer_library_styles', 'woocustomizer_customizer_library_build_styles' );
if ( !function_exists( 'woocustomizer_customizer_library_styles' ) ) {
    /**
     * Generates the style tag and CSS needed for the theme options.
     *
     * By using the "WooCustomizer_Library_Styles" filter, different components can print CSS in the header.
     * It is organized this way to ensure there is only one "style" tag.
     *
     * @since  1.0.0.
     *
     * @return void
     */
    function woocustomizer_customizer_library_styles()
    {
        do_action( 'customizer_library_styles' );
        // Echo the rules
        $css = WooCustomizer_Library_Styles()->build();
        
        if ( !empty($css) ) {
            wp_register_style( 'wcz-customizer-custom-css', false );
            wp_enqueue_style( 'wcz-customizer-custom-css' );
            wp_add_inline_style( 'wcz-customizer-custom-css', $css );
        }
    
    }

}
add_action( 'wp_enqueue_scripts', 'woocustomizer_customizer_library_styles', 11 );
function wcz_getContrastColor( $hexColor )
{
    // hexColor RGB
    $R1 = hexdec( substr( $hexColor, 1, 2 ) );
    $G1 = hexdec( substr( $hexColor, 3, 2 ) );
    $B1 = hexdec( substr( $hexColor, 5, 2 ) );
    // Black RGB
    $blackColor = "#000000";
    $R2BlackColor = hexdec( substr( $blackColor, 1, 2 ) );
    $G2BlackColor = hexdec( substr( $blackColor, 3, 2 ) );
    $B2BlackColor = hexdec( substr( $blackColor, 5, 2 ) );
    // Calc contrast ratio
    $L1 = 0.2126 * pow( $R1 / 255, 2.2 ) + 0.7151999999999999 * pow( $G1 / 255, 2.2 ) + 0.0722 * pow( $B1 / 255, 2.2 );
    $L2 = 0.2126 * pow( $R2BlackColor / 255, 2.2 ) + 0.7151999999999999 * pow( $G2BlackColor / 255, 2.2 ) + 0.0722 * pow( $B2BlackColor / 255, 2.2 );
    $contrastRatio = 0;
    
    if ( $L1 > $L2 ) {
        $contrastRatio = (int) (($L1 + 0.05) / ($L2 + 0.05));
    } else {
        $contrastRatio = (int) (($L2 + 0.05) / ($L1 + 0.05));
    }
    
    // If contrast is more than 5, return black color
    
    if ( $contrastRatio > 5 ) {
        return '#000000';
    } else {
        // if not, return white color.
        return '#FFFFFF';
    }

}
