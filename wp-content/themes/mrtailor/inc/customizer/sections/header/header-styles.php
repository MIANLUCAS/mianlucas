<?php
/**
* The Header Styles section options.
*/

add_action( 'customize_register', 'mrtailor_customizer_header_styles_controls' );
/**
 * Adds controls for header styles section.
 *
 * @param  [object] $wp_customize [customizer object].
 */
function mrtailor_customizer_header_styles_controls( $wp_customize ) {

    // Header Layout.
    $wp_customize->add_setting(
        'header_layout',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'mrtailor_sanitize_select',
            'default'           => '2',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'header_layout',
            array(
                'type'     => 'select',
                'label'    => esc_html__( 'Header Layout', 'mr_tailor' ),
                'section'  => 'header_style',
                'priority' => 10,
                'choices'  => array(
                    '0'        => esc_html__( 'Layout 1', 'mr_tailor' ),
                    '1'        => esc_html__( 'Layout 2', 'mr_tailor' ),
                    '2'        => esc_html__( 'Layout 3', 'mr_tailor' )
                ),
            )
        )
    );

    // Header Spacing.
    $wp_customize->add_setting(
        'header_paddings',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'absint',
            'default'           => 20,
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'header_paddings',
            array(
                'type'        => 'number',
                'label'       => esc_html__( 'Header Spacing', 'mr_tailor' ),
                'description' => esc_html__('Above and below the logo', 'mr_tailor'),
                'section'     => 'header_style',
                'priority'    => 10,
                'description' => esc_html__( "(0px - 200px)", 'mr_tailor' ),
                'input_attrs' => array(
                    'min'  => 0,
                    'max'  => 200,
                    'step' => 1,
                ),
            )
        )
    );

    // Header Background Color.
    $wp_customize->add_setting(
        'main_header_background_color',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#ffffff',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'main_header_background_color',
            array(
                'label'    => esc_html__( 'Header Background Color', 'mr_tailor' ),
                'section'  => 'header_style',
                'priority' => 10,
            )
        )
    );

    // Header Font Size.
    $wp_customize->add_setting(
        'main_header_font_size',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'absint',
            'default'           => 16,
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'main_header_font_size',
            array(
                'type'        => 'number',
                'label'       => esc_html__( 'Header Font Size', 'mr_tailor' ),
                'section'     => 'header_style',
                'priority'    => 10,
                'description' => esc_html__( "(12px - 18px)", 'mr_tailor' ),
                'input_attrs' => array(
                    'min'  => 12,
                    'max'  => 18,
                    'step' => 1,
                ),
            )
        )
    );

    // Header Font Color.
    $wp_customize->add_setting(
        'main_header_font_color',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#000000',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'main_header_font_color',
            array(
                'label'    => esc_html__( 'Header Font Color', 'mr_tailor' ),
                'section'  => 'header_style',
                'priority' => 10,
            )
        )
    );
}

?>
