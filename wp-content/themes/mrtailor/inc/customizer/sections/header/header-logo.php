<?php
/**
* The Header Logo section options.
*/

add_action( 'customize_register', 'mrtailor_customizer_header_logo_controls' );
/**
 * Adds controls for header logo section.
 *
 * @param  [object] $wp_customize [customizer object].
 */
function mrtailor_customizer_header_logo_controls( $wp_customize ) {

    // Logo.
    $wp_customize->add_setting(
        'site_logo',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'mrtailor_sanitize_image',
            'transport'         => 'postMessage',
            'default'	        => '',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'site_logo',
            array(
                'type'        => 'image',
                'label'       => esc_html__( 'Logo', 'mr_tailor' ),
                'section'     => 'header_logo',
                'priority'    => 10,
            )
        )
    );

    $wp_customize->selective_refresh->add_partial( 'site_logo', array(
        'selector' => '.normal_header:not(.transparent_header) .site-branding .site-logo-link',
        'settings' => 'site_logo',
        'render_callback' => function() {
            mrtailor_get_logo();
        },
    ) );

    // Alternative Logo.
    $wp_customize->add_setting(
        'sticky_header_logo',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'mrtailor_sanitize_image',
            'transport'         => 'postMessage',
            'default'	        => '',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'sticky_header_logo',
            array(
                'type'        => 'image',
                'label'       => esc_html__( 'Alternative Logo', 'mr_tailor' ),
                'section'     => 'header_logo',
                'priority'    => 10,
            )
        )
    );

    $wp_customize->selective_refresh->add_partial( 'sticky_header_logo', array(
        'selector' => '.site-branding .site-logo-alt-link',
        'settings' => 'sticky_header_logo',
        'render_callback' => function() {
            mrtailor_get_alt_logo();
        },
    ) );

    // Logo Height.
    $wp_customize->add_setting(
        'logo_height',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'absint',
            'default'           => 60,
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'logo_height',
            array(
                'type'        => 'number',
                'label'       => esc_html__( 'Logo Max Height', 'mr_tailor' ),
                'section'     => 'header_logo',
                'priority'    => 10,
                'description' => esc_html__( "(0px - 300px)", 'mr_tailor' ),
                'input_attrs' => array(
                    'min'  => 0,
                    'max'  => 300,
                    'step' => 1,
                ),
            )
        )
    );

    // Alternative Logo Height.
    $wp_customize->add_setting(
        'alt_logo_height',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'absint',
            'default'           => 40,
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'alt_logo_height',
            array(
                'type'        => 'number',
                'label'       => esc_html__( 'Alternative Logo Max Height', 'mr_tailor' ),
                'section'     => 'header_logo',
                'priority'    => 10,
                'description' => esc_html__( "(0px - 300px)", 'mr_tailor' ),
                'input_attrs' => array(
                    'min'  => 0,
                    'max'  => 300,
                    'step' => 1,
                ),
            )
        )
    );
}

?>
