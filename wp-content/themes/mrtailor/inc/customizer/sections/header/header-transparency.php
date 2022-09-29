<?php
/**
* The Header Transparency section options.
*/

/**
 * Checks if header layout is not 3.
 */
function mrtailor_header_3_not_enabled(){

    return !( '2' === MrTailor_Opt::getOption( 'header_layout', '2' ) );
}

/**
 * Checks if header layout is 3.
 */
function mrtailor_header_3_enabled(){

    return ( '2' === MrTailor_Opt::getOption( 'header_layout', '2' ) );
}

add_action( 'customize_register', 'mrtailor_customizer_header_transparency_controls' );
/**
 * Adds controls for header transparency section.
 *
 * @param  [object] $wp_customize [customizer object].
 */
function mrtailor_customizer_header_transparency_controls( $wp_customize ) {

    // Header Layout 3 - Transparency not available message.
    $wp_customize->add_setting(
		'header_transparency_not_available',
		array(
			'type'                 => 'theme_mod',
			'capability'           => 'edit_theme_options',
			'sanitize_callback'    => 'wp_kses',
			'default'              => esc_html__( 'The selected header layout does not support header transparency.', 'mr_tailor' ),
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'header_transparency_not_available',
			array(
				'type'          => 'textarea',
				'label'         => '',
				'section'       => 'header_transparency',
				'priority'       => 10,
                'active_callback' => 'mrtailor_header_3_enabled'
			)
		)
	);

    // Transparent Header.
    $wp_customize->add_setting(
		'main_header_background_transparency',
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
			'main_header_background_transparency',
			array(
				'type'          => 'checkbox',
				'label'         => esc_html__( 'Transparent Header', 'mr_tailor' ),
				'section'       => 'header_transparency',
				'priority'       => 10,
                'active_callback' => 'mrtailor_header_3_not_enabled'
			)
		)
	);

    // Default Color Scheme.
    $wp_customize->add_setting(
        'main_header_transparency_scheme',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'mrtailor_sanitize_select',
            'default'           => 'transparency_light',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'main_header_transparency_scheme',
            array(
                'type'          => 'select',
                'label'         => esc_html__( 'Default Color Scheme', 'mr_tailor' ),
                'section'       => 'header_transparency',
                'priority'      => 10,
                'choices'       => array(
                    'transparency_light'	=> esc_html__( 'Light', 'mr_tailor' ),
                    'transparency_dark' 	=> esc_html__( 'Dark', 'mr_tailor' ),
                ),
                'active_callback' => 'mrtailor_header_3_not_enabled'
            )
        )
    );

    // Default Color Scheme.
    $wp_customize->add_setting(
        'shop_category_header_transparency_scheme',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'mrtailor_sanitize_select',
            'default'           => 'inherit',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'shop_category_header_transparency_scheme',
            array(
                'type'          => 'select',
                'label'         => esc_html__( 'Shop Category Page Color Scheme', 'mr_tailor' ),
                'section'       => 'header_transparency',
                'priority'      => 10,
                'choices'       => array(
                    'inherit'               => esc_html__( 'Inherit', 'mr_tailor' ),
                    'no_transparency'       => esc_html__( 'No Transparency', 'mr_tailor' ),
                    'transparency_light'    => esc_html__( 'Light', 'mr_tailor' ),
                    'transparency_dark'     => esc_html__( 'Dark', 'mr_tailor' ),
                ),
                'active_callback' => 'mrtailor_header_3_not_enabled'
            )
        )
    );

    // Transparent Header Light Color.
    $wp_customize->add_setting(
        'main_header_transparent_light_color',
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
            'main_header_transparent_light_color',
            array(
                'label'          => esc_html__( 'Transparent Header Light Color', 'mr_tailor' ),
                'section'        => 'header_transparency',
                'priority'       => 10,
                'active_callback' => 'mrtailor_header_3_not_enabled'
            )
        )
    );

    // Logo for Light Transparent Header.
    $wp_customize->add_setting(
        'light_transparent_header_logo',
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
            'light_transparent_header_logo',
            array(
                'type'        => 'image',
                'label'       => esc_html__( 'Logo for Light Transparent Header', 'mr_tailor' ),
                'section'     => 'header_transparency',
                'priority'    => 10,
                'active_callback' => 'mrtailor_header_3_not_enabled'
            )
        )
    );

    $wp_customize->selective_refresh->add_partial( 'light_transparent_header_logo', array(
        'selector' => '.transparent_header.transparency_light .site-branding .site-logo-link',
        'settings' => 'light_transparent_header_logo',
        'render_callback' => function() {
            $transparency = mrtailor_get_transparency_options();
            echo mrtailor_get_header_logos($transparency['header_transparency_class'], $transparency['transparency_scheme']);

            return;
        },
    ) );

    // Transparent Header Dark Color.
    $wp_customize->add_setting(
        'main_header_transparent_dark_color',
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
            'main_header_transparent_dark_color',
            array(
                'label'          => esc_html__( 'Transparent Header Dark Color', 'mr_tailor' ),
                'section'        => 'header_transparency',
                'priority'       => 10,
                'active_callback' => 'mrtailor_header_3_not_enabled'
            )
        )
    );

    // Logo for Dark Transparent Header.
    $wp_customize->add_setting(
        'dark_transparent_header_logo',
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
            'dark_transparent_header_logo',
            array(
                'type'        => 'image',
                'label'       => esc_html__( 'Logo for Dark Transparent Header', 'mr_tailor' ),
                'section'     => 'header_transparency',
                'priority'    => 10,
                'active_callback' => 'mrtailor_header_3_not_enabled'
            )
        )
    );

    $wp_customize->selective_refresh->add_partial( 'dark_transparent_header_logo', array(
        'selector' => '.transparent_header.transparency_dark .site-branding .site-logo-link',
        'settings' => 'dark_transparent_header_logo',
        'render_callback' => function() {
            $transparency = mrtailor_get_transparency_options();
            echo mrtailor_get_header_logos($transparency['header_transparency_class'], $transparency['transparency_scheme']);

            return;
        },
    ) );
}

?>
