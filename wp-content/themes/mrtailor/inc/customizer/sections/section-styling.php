<?php
/**
* The Styling section options.
*/

add_action( 'customize_register', 'mrtailor_customizer_styling_controls' );
/**
 * Adds controls for styling section.
 *
 * @param  [object] $wp_customize [customizer object].
 */
function mrtailor_customizer_styling_controls( $wp_customize ) {

    // Main Theme Color.
    $wp_customize->add_setting(
        'main_color',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#0d244c',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'main_color',
            array(
                'label'    => esc_html__( 'Main Theme Color', 'mr_tailor' ),
                'section'  => 'panel_styling',
                'priority' => 10,
            )
        )
    );

    // Background Color.
    $wp_customize->add_setting(
        'main_bg_color',
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
            'main_bg_color',
            array(
                'label'    => esc_html__( 'Background Color', 'mr_tailor' ),
                'section'  => 'panel_styling',
                'priority' => 10,
            )
        )
    );

    // Background Image.
    $wp_customize->add_setting(
        'main_bg_image',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'mrtailor_sanitize_image',
            'default'	        => '',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'main_bg_image',
            array(
                'type'        => 'image',
                'label'       => esc_html__( 'Background Image', 'mr_tailor' ),
                'section'     => 'panel_styling',
                'priority'    => 10,
            )
        )
    );

    // Navigation Dropdown Background.
    $wp_customize->add_setting(
        'navigation_bg',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#0d244c',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'navigation_bg',
            array(
                'label'    => esc_html__( 'Navigation Dropdown Background', 'mr_tailor' ),
                'section'  => 'panel_styling',
                'priority' => 10,
            )
        )
    );

    // Navigation Dropdown Links Color.
    $wp_customize->add_setting(
        'navigation_link_color',
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
            'navigation_link_color',
            array(
                'label'    => esc_html__( 'Navigation Dropdown Links Color', 'mr_tailor' ),
                'section'  => 'panel_styling',
                'priority' => 10,
            )
        )
    );
}

?>
