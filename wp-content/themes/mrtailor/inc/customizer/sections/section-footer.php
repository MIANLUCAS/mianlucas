<?php
/**
* The Footer section options.
*/

add_action( 'customize_register', 'mrtailor_customizer_footer_controls' );
/**
 * Adds controls for footer section.
 *
 * @param  [object] $wp_customize [customizer object].
 */
function mrtailor_customizer_footer_controls( $wp_customize ) {

    // Footer Background Color.
    $wp_customize->add_setting(
        'footer_background_color',
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
            'footer_background_color',
            array(
                'label'    => esc_html__( 'Footer Background Color', 'mr_tailor' ),
                'section'  => 'panel_footer',
                'priority' => 10,
            )
        )
    );

    // Footer Text Color.
    $wp_customize->add_setting(
        'footer_texts_color',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#686868',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'footer_texts_color',
            array(
                'label'    => esc_html__( 'Footer Text Color', 'mr_tailor' ),
                'section'  => 'panel_footer',
                'priority' => 10,
            )
        )
    );

    // Footer Links Color.
    $wp_customize->add_setting(
        'footer_links_color',
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
            'footer_links_color',
            array(
                'label'    => esc_html__( 'Footer Links Color', 'mr_tailor' ),
                'section'  => 'panel_footer',
                'priority' => 10,
            )
        )
    );

    // Credit Card Icons / Image.
    $wp_customize->add_setting(
        'credit_card_icons',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'mrtailor_sanitize_image',
            'transport'         => 'postMessage',
            'default'	        => get_template_directory_uri() . '/images/payment_cards.png',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'credit_card_icons',
            array(
                'type'        => 'image',
                'label'       => esc_html__( 'Credit Card Icons / Image', 'mr_tailor' ),
                'section'     => 'panel_footer',
                'priority'    => 10,
            )
        )
    );

    $wp_customize->selective_refresh->add_partial( 'credit_card_icons', array(
        'selector' => '#site-footer .payment_methods',
        'settings' => 'credit_card_icons',
        'render_callback' => function() {
            $credit_card_icons = (is_ssl()) ? str_replace( "http://", "https://", MrTailor_Opt::getOption( 'credit_card_icons' ) ) : MrTailor_Opt::getOption( 'credit_card_icons' );
            echo '<img src="' . esc_url($credit_card_icons) . '" alt="' . esc_html( 'Payment methods', 'mr_tailor' ) . '" />';
        },
    ) );

    // Copyright Text.
    $wp_customize->add_setting(
    	'footer_copyright_text',
    	array(
    		'type'               => 'theme_mod',
    		'capability'         => 'edit_theme_options',
            'sanitize_callback'  => 'wp_kses',
            'transport'          => 'postMessage',
    		'default'            => esc_html__( 'Designed with ', 'mr_tailor' ) . '<a href="'.MT_THEME_WEBSITE.'" target="_blank" title="eCommerce WordPress Theme for WooCommerce">'.esc_html__( 'Mr. Tailor', 'mr_tailor' ).'</a>.',
    	)
    );

    $wp_customize->add_control(
    	new WP_Customize_Control(
    		$wp_customize,
    		'footer_copyright_text',
    		array(
    			'type'        => 'textarea',
    			'label'       => esc_html__( 'Copyright Text', 'mr_tailor' ),
    			'section'     => 'panel_footer',
    			'priority'    => 10,
    		)
    	)
    );

    $wp_customize->selective_refresh->add_partial( 'footer_copyright_text', array(
        'selector' => '#site-footer .copyright_text',
        'settings' => 'footer_copyright_text',
        'render_callback' => function() {
            if ( MrTailor_Opt::getOption( 'footer_copyright_text' ) != "" ) {
                printf( wp_kses_post(__( '%s', 'mr_tailor' )), MrTailor_Opt::getOption( 'footer_copyright_text' ) );
            }
        },
    ) );

    // Highlight first widget.
    $wp_customize->add_setting(
        'footer_highlight_widget',
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
            'footer_highlight_widget',
            array(
                'type'          => 'checkbox',
                'label'         => esc_html__( 'Highlight First Widget', 'mr_tailor' ),
                'section'       => 'panel_footer',
                'priority'      => 10,
            )
        )
    );

    // Expandable Footer on Mobiles.
    $wp_customize->add_setting(
        'expandable_footer',
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
            'expandable_footer',
            array(
                'type'          => 'checkbox',
                'label'         => esc_html__( 'Expandable Footer on Mobiles', 'mr_tailor' ),
                'section'       => 'panel_footer',
                'priority'      => 10,
            )
        )
    );
}

?>
