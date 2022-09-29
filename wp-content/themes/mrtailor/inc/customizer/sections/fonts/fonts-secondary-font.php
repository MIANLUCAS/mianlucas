<?php
/**
* The Secondary Font section options.
*/

/**
 * Checks if secondary font google is enabled.
 */
function mrtailor_secondary_font_google_enabled(){

    return ( '1' === MrTailor_Opt::getOption( 'secondary_font_source', '1' ) );
}

/**
 * Checks if main font adobe is enabled.
 */
function mrtailor_secondary_font_adobe_enabled(){

    return ( '2' === MrTailor_Opt::getOption( 'secondary_font_source', '1' ) );
}

add_action( 'customize_register', 'mrtailor_customizer_secondary_font_controls' );
/**
 * Adds controls for secondary font section.
 *
 * @param  [object] $wp_customize [customizer object].
 */
function mrtailor_customizer_secondary_font_controls( $wp_customize ) {

    // Font Source.
    $wp_customize->add_setting(
        'secondary_font_source',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'mrtailor_sanitize_select',
            'default'           => '1',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'secondary_font_source',
            array(
                'type'     => 'select',
                'label'    => esc_html__( 'Font Source', 'mr_tailor' ),
                'section'  => 'secondary_font',
                'priority' => 10,
                'choices'  => array(
                    '1'       => esc_html__( 'Standard + Google Webfonts', 'mr_tailor' ),
                    '2'      => esc_html__( 'Adobe Typekit', 'mr_tailor' ),
                ),
            )
        )
    );

    // Secondary Font Family.
    $wp_customize->add_setting(
    	'secondary_font',
    	array(
    		'default' 			=> 'DM Sans',
    		'capability' 		=> 'edit_theme_options',
    		'sanitize_callback' => 'wp_filter_nohtml_kses',
    		'type'				=> 'theme_mod',
    	)
    );

    $wp_customize->add_control(
    	new WP_Customize_Control(
    		$wp_customize,
    		'secondary_font',
    		array(
    			'type'			=> 'text',
    			'label' 		=> __( 'Secondary Font Family', 'mr_tailor' ),
    			'description'	=> MrTailor_Fonts::get_suggested_fonts_list() . 'Used for titles and Headings. Mr. Tailor supports all fonts on <a href="'.MT_GOOGLE_FONTS_WEBSITE.'" target="_blank">Google Fonts</a> and all <a href="'.MT_SAFE_FONTS_WEBSITE.'" target="_blank">web safe fonts</a>.',
    			'section' 		=> 'secondary_font',
    			'input_attrs' 	=> array(
    				'placeholder' 		=> __( 'Enter the font name', 'mr_tailor' ),
    				'class'				=> 'mrtailor-font-suggestions',
    				'list'  			=> 'mrtailor-suggested-fonts',
    				'autocapitalize'	=> 'off',
    				'autocomplete'		=> 'off',
    				'autocorrect'		=> 'off',
    				'spellcheck'		=> 'false',
    			),
                'active_callback' => 'mrtailor_secondary_font_google_enabled',
    		)
    	)
    );

    // Typekit Kit ID.
    $wp_customize->add_setting(
        'secondary_font_typekit_kit_id',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => '',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'secondary_font_typekit_kit_id',
            array(
                'type'        => 'text',
                'label'       => esc_attr__( 'Typekit Kit ID', 'mr_tailor' ),
                'section'     => 'secondary_font',
                'priority'    => 10,
                'active_callback' => 'mrtailor_secondary_font_adobe_enabled',
            )
        )
    );

    // Typekit Font Family.
    $wp_customize->add_setting(
        'secondary_typekit_font_face',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => '',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'secondary_typekit_font_face',
            array(
                'type'        => 'text',
                'label'       => esc_attr__( 'Typekit Font Family', 'mr_tailor' ),
                'section'     => 'secondary_font',
                'priority'    => 10,
                'active_callback' => 'mrtailor_secondary_font_adobe_enabled',
            )
        )
    );

    // Font Display.
    $wp_customize->add_setting(
        'secondary_font_face_display',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'mrtailor_sanitize_select',
            'default'           => 'swap',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'secondary_font_face_display',
            array(
                'type'     => 'select',
                'label'    => esc_html__( 'Font Display', 'mr_tailor' ),
                'section'  => 'secondary_font',
                'description' => '<ul><li>'.esc_html__( 'Swap - uses fallback font until the fonts area loaded', 'mr_tailor' ).'</li><li>'.esc_html__( 'Block - briefly hides the text until the font is fully loaded', 'mr_tailor' ).'</li></ul>',
                'priority' => 10,
                'choices'  => array(
                    'swap'       => esc_html__( 'Use fallback font (swap)', 'mr_tailor' ),
                    'block'      => esc_html__( 'Hide text while loading (block)', 'mr_tailor' ),
                ),
                'active_callback' => 'mrtailor_secondary_font_google_enabled',
            )
        )
    );

    // Base Font Size.
    $wp_customize->add_setting(
        'body_text_font_size',
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
            'body_text_font_size',
            array(
                'type'        => 'number',
                'label'       => esc_html__( 'Base Font Size', 'mr_tailor' ),
                'section'     => 'secondary_font',
                'priority'    => 10,
                'description' => esc_html__( "(1px - 150px)", 'mr_tailor' ),
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 150,
                    'step' => 1,
                ),
            )
        )
    );

    // Text Color.
    $wp_customize->add_setting(
        'body_color',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#222222',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'body_color',
            array(
                'label'    => esc_html__( 'Text Color', 'mr_tailor' ),
                'section'  => 'secondary_font',
                'priority' => 10,
            )
        )
    );
}

?>
