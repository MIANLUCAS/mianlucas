<?php
/**
* The Product Page section options.
*/

add_action( 'customize_register', 'mrtailor_customizer_product_page_controls' );
/**
 * Adds controls for product page section.
 *
 * @param  [object] $wp_customize [customizer object].
 */
function mrtailor_customizer_product_page_controls( $wp_customize ) {

    // Product Layout.
    $wp_customize->add_setting(
        'products_layout',
        array(
            'type'                 => 'theme_mod',
            'capability'           => 'edit_theme_options',
            'sanitize_callback'    => 'mrtailor_sanitize_checkbox',
            'default'              => false,
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'products_layout',
            array(
                'type'          => 'checkbox',
                'label'         => esc_html__( 'Sidebar', 'mr_tailor' ),
                'section'       => 'panel_product',
                'priority'      => 10,
            )
        )
    );

    // Product Gallery Zoom.
    $wp_customize->add_setting(
        'product_gallery_zoom',
        array(
            'type'                 => 'theme_mod',
            'capability'           => 'edit_theme_options',
            'sanitize_callback'    => 'mrtailor_sanitize_checkbox',
            'default'              => true,
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'product_gallery_zoom',
            array(
                'type'          => 'checkbox',
                'label'         => esc_html__( 'Product Gallery Zoom', 'mr_tailor' ),
                'section'       => 'panel_product',
                'priority'      => 10,
            )
        )
    );

    // Product Gallery Lightbox.
    $wp_customize->add_setting(
        'product_gallery_lightbox',
        array(
            'type'                 => 'theme_mod',
            'capability'           => 'edit_theme_options',
            'sanitize_callback'    => 'mrtailor_sanitize_checkbox',
            'default'              => false,
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'product_gallery_lightbox',
            array(
                'type'          => 'checkbox',
                'label'         => esc_html__( 'Product Gallery Lightbox', 'mr_tailor' ),
                'section'       => 'panel_product',
                'priority'      => 10,
            )
        )
    );

    // Recently viewed.
    $wp_customize->add_setting(
        'recently_viewed_products',
        array(
            'type'                 => 'theme_mod',
            'capability'           => 'edit_theme_options',
            'sanitize_callback'    => 'mrtailor_sanitize_checkbox',
            'default'              => true,
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'recently_viewed_products',
            array(
                'type'          => 'checkbox',
                'label'         => esc_html__( 'Recently viewed', 'mr_tailor' ),
                'section'       => 'panel_product',
                'priority'      => 10,
            )
        )
    );
}

?>
