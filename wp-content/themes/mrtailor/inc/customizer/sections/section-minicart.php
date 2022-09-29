<?php
/**
* The Minicart section options.
*/

add_action( 'customize_register', 'mrtailor_customizer_minicart_controls' );
/**
 * Adds controls for minicart section.
 *
 * @param  [object] $wp_customize [customizer object].
 */
function mrtailor_customizer_minicart_controls( $wp_customize ) {

    // Open Minicart on Add to Cart.
    $wp_customize->add_setting(
    	'open_minicart',
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
    		'open_minicart',
    		array(
    			'type'          => 'checkbox',
    			'label'         => esc_html__( 'Open \'Mini Cart\' after clicking \'Add to Cart\'', 'mr_tailor' ),
    			'section'       => 'panel_minicart',
    			'priority'      => 10,
    		)
    	)
    );

    // Copyright Text.
    $wp_customize->add_setting(
    	'minicart_text',
    	array(
    		'type'               => 'theme_mod',
    		'capability'         => 'edit_theme_options',
            'sanitize_callback'  => 'wp_kses',
    		'default'            => '',
    	)
    );

    $wp_customize->add_control(
    	new WP_Customize_Control(
    		$wp_customize,
    		'minicart_text',
    		array(
    			'type'        => 'textarea',
    			'label'       => esc_html__( 'Mini Cart Text', 'mr_tailor' ),
    			'section'     => 'panel_minicart',
    			'priority'    => 10,
    		)
    	)
    );
}

?>
