<?php
/**
* The Blog section options.
*/

add_action( 'customize_register', 'mrtailor_customizer_blog_controls' );
/**
 * Adds controls for blog section.
 *
 * @param  [object] $wp_customize [customizer object].
 */
function mrtailor_customizer_blog_controls( $wp_customize ) {

    // Blog Layout.
    $wp_customize->add_setting(
        'sidebar_blog_listing',
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
            'sidebar_blog_listing',
            array(
                'type'     => 'select',
                'label'    => esc_html__( 'Blog Layout', 'mr_tailor' ),
                'section'  => 'panel_blog',
                'priority' => 10,
                'choices'  => array(
                    '0'  => esc_html__( 'Layout 1', 'mr_tailor' ),
                    '1'  => esc_html__( 'Layout 2', 'mr_tailor' ),
                    '2'  => esc_html__( 'Layout 3', 'mr_tailor' ),
                ),
            )
        )
    );
}

?>
