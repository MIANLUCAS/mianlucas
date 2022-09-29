<?php
/**
* The Shop section options.
*/

add_action( 'customize_register', 'mrtailor_customizer_shop_controls' );
/**
 * Adds controls for shop section.
 *
 * @param  [object] $wp_customize [customizer object].
 */
function mrtailor_customizer_shop_controls( $wp_customize ) {

    // Catalog Mode.
    $wp_customize->add_setting(
    	'catalog_mode',
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
    		'catalog_mode',
    		array(
    			'type'          => 'checkbox',
    			'label'         => esc_html__( 'Catalog Mode', 'mr_tailor' ),
                'description'   => esc_html__('When enabled, the feature Turns Off the shopping functionality of WooCommerce.', 'mr_tailor'),
    			'section'       => 'panel_shop',
    			'priority'      => 10,
    		)
    	)
    );

    // Shop Layout.
    $wp_customize->add_setting(
    	'shop_layout',
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
    		'shop_layout',
    		array(
    			'type'          => 'checkbox',
    			'label'         => esc_html__( 'Shop Sidebar', 'mr_tailor' ),
    			'section'       => 'panel_shop',
    			'priority'      => 10,
    		)
    	)
    );

    // Breadcrumbs.
    $wp_customize->add_setting(
    	'breadcrumbs',
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
    		'breadcrumbs',
    		array(
    			'type'          => 'checkbox',
    			'label'         => esc_html__( 'Breadcrumbs', 'mr_tailor' ),
    			'section'       => 'panel_shop',
    			'priority'      => 10,
    		)
    	)
    );

    // Add to Cart Button Display.
    $wp_customize->add_setting(
        'add_to_cart_display',
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
            'add_to_cart_display',
            array(
                'type'     => 'select',
                'label'    => esc_html__( 'Add to Cart Button Display', 'mr_tailor' ),
                'section'  => 'panel_shop',
                'priority' => 10,
                'choices'  => array(
                    '0'	=> esc_html__( 'At All Times', 'mr_tailor' ),
                    '1' => esc_html__( 'When Hovering', 'mr_tailor' ),
                ),
            )
        )
    );

    // Product Animation.
    $wp_customize->add_setting(
        'products_animation',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'mrtailor_sanitize_select',
            'default'           => 'e2',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'products_animation',
            array(
                'type'     => 'select',
                'label'    => esc_html__( 'Product Animation', 'mr_tailor' ),
                'section'  => 'panel_shop',
                'priority' => 10,
                'choices'  => array(
                    'e0' => esc_html__( 'No Animation', 'mr_tailor' ),
                    'e1' => esc_html__( 'Fade', 'mr_tailor' ),
                    'e2' => esc_html__( 'Move Up', 'mr_tailor' ),
                    'e3' => esc_html__( 'Scale Up', 'mr_tailor' ),
                    'e4' => esc_html__( 'Fall Perspective', 'mr_tailor' ),
                    'e5' => esc_html__( 'Fly', 'mr_tailor' ),
                    'e6' => esc_html__( 'Flip', 'mr_tailor' ),
                    'e7' => esc_html__( 'Helix', 'mr_tailor' ),
                    'e8' => esc_html__( 'PopUp', 'mr_tailor' ),
                ),
            )
        )
    );

    // Product Hover Animation.
    $wp_customize->add_setting(
        'product_hover_animation',
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
            'product_hover_animation',
            array(
                'type'          => 'checkbox',
                'label'         => esc_html__( 'Product Hover Animation', 'mr_tailor' ),
                'section'       => 'panel_shop',
                'priority'      => 10,
            )
        )
    );

    // Sale Text.
    $wp_customize->add_setting(
        'sale_text',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => esc_html__( 'Sale!', 'mr_tailor' ),
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'sale_text',
            array(
                'type'        => 'text',
                'label'       => esc_attr__( 'Sale Text', 'mr_tailor' ),
                'section'     => 'panel_shop',
                'priority'    => 10,
            )
        )
    );

    // Out of Stock Text.
    $wp_customize->add_setting(
        'out_of_stock_text',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => esc_html__( 'Out of stock', 'mr_tailor' ),
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'out_of_stock_text',
            array(
                'type'        => 'text',
                'label'       => esc_attr__( 'Out of Stock Text', 'mr_tailor' ),
                'section'     => 'panel_shop',
                'priority'    => 10,
            )
        )
    );

    // Shop Pagination.
    $wp_customize->add_setting(
        'shop_pagination',
        array(
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'mrtailor_sanitize_select',
            'default'           => 'classic',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'shop_pagination',
            array(
                'type'     => 'select',
                'label'    => esc_html__( 'Pagination', 'mr_tailor' ),
                'section'  => 'panel_shop',
                'priority' => 10,
                'choices'  => array(
                    'classic'	=> esc_html__( 'Classic Pagination', 'mr_tailor' ),
                    'infinite' => esc_html__( 'Infinite Loading', 'mr_tailor' ),
                ),
            )
        )
    );
}

?>
