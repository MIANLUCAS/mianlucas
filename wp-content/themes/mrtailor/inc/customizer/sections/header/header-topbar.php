<?php
/**
* The Header Top Bar section options.
*/

/**
 * Checks if topbar is enabled.
 */
function mrtailor_topbar_enabled(){

    return MrTailor_Opt::getOption( 'top_bar_switch', true );
}

add_action( 'customize_register', 'mrtailor_customizer_header_topbar_controls' );
/**
 * Adds controls for header topbar section.
 *
 * @param  [object] $wp_customize [customizer object].
 */
function mrtailor_customizer_header_topbar_controls( $wp_customize ) {

    // Top Bar.
    $wp_customize->add_setting(
    	'top_bar_switch',
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
    		'top_bar_switch',
    		array(
    			'type'          => 'checkbox',
    			'label'         => esc_html__( 'Top Bar', 'mr_tailor' ),
    			'section'       => 'header_topbar',
    			'priority'       => 10,
    		)
    	)
    );

    // Top Bar Background Color.
    $wp_customize->add_setting(
        'top_bar_background_color',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#f9f9f9',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'top_bar_background_color',
            array(
                'label'    => esc_html__( 'Top Bar Background Color', 'mr_tailor' ),
                'section'  => 'header_topbar',
                'priority' => 10,
                'active_callback' => 'mrtailor_topbar_enabled',
            )
        )
    );

    // Top Bar Text Color.
    $wp_customize->add_setting(
        'top_bar_typography',
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
            'top_bar_typography',
            array(
                'label'    => esc_html__( 'Top Bar Text Color', 'mr_tailor' ),
                'section'  => 'header_topbar',
                'priority' => 10,
                'active_callback' => 'mrtailor_topbar_enabled',
            )
        )
    );

    // Top Bar Links Color.
    $wp_customize->add_setting(
        'top_bar_links_color',
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
            'top_bar_links_color',
            array(
                'label'    => esc_html__( 'Top Bar Links Color', 'mr_tailor' ),
                'section'  => 'header_topbar',
                'priority' => 10,
                'active_callback' => 'mrtailor_topbar_enabled',
            )
        )
    );

    // Top Bar Text.
    $wp_customize->add_setting(
		'top_bar_text',
		array(
			'type'               => 'theme_mod',
			'capability'         => 'edit_theme_options',
            'sanitize_callback'  => 'wp_kses',
            'transport'          => 'postMessage',
			'default'            => esc_html__( 'Free Shipping on All Orders Over $75!', 'mr_tailor' ),
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'top_bar_text',
			array(
				'type'        => 'textarea',
				'label'       => esc_html__( 'Top Bar Text', 'mr_tailor' ),
				'section'     => 'header_topbar',
				'priority'    => 10,
                'active_callback' => 'mrtailor_topbar_enabled',
			)
		)
	);

    // Abort if selective refresh is not available.
    if ( ! isset( $wp_customize->selective_refresh ) ) {
        return;
    }

    $wp_customize->selective_refresh->add_partial( 'top_bar_text', array(
        'selector' => '.site-top-message',
        'settings' => 'top_bar_text',
        'render_callback' => function() {
            return MrTailor_Opt::getOption( 'top_bar_text', esc_html__( 'Free Shipping on All Orders Over $75!', 'mr_tailor' ) );
        },
    ) );
}

?>
