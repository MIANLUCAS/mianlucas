<?php
/**
* The Header Sticky section options.
*/

/**
 * Checks if header is sticky.
 */
function mrtailor_sticky_header_enabled(){

    return MrTailor_Opt::getOption( 'sticky_header', true ) && MrTailor_Opt::getOption( 'top_bar_switch', true );
}

add_action( 'customize_register', 'mrtailor_customizer_header_sticky_controls' );
/**
 * Adds controls for header sticky section.
 *
 * @param  [object] $wp_customize [customizer object].
 */
function mrtailor_customizer_header_sticky_controls( $wp_customize ) {

    // Sticky Header.
    $wp_customize->add_setting(
		'sticky_header',
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
			'sticky_header',
			array(
				'type'          => 'checkbox',
				'label'         => esc_html__( 'Sticky Header', 'mr_tailor' ),
				'section'       => 'header_sticky',
				'priority'       => 10,
			)
		)
	);

    // Include Top Bar.
    $wp_customize->add_setting(
    	'top_bar_sticky',
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
    		'top_bar_sticky',
    		array(
    			'type'            => 'checkbox',
    			'label'           => esc_html__( 'Include Top Bar', 'mr_tailor' ),
    			'section'         => 'header_sticky',
    			'priority'        => 10,
                'active_callback' => 'mrtailor_sticky_header_enabled',
    		)
    	)
    );
}

?>
