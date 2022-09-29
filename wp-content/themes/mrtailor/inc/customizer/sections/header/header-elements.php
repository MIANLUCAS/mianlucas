<?php
/**
* The Header Elements section options.
*/

/**
 * Checks if wishlist icon is enabled.
 */
function mrtailor_wishlist_icon_enabled(){

    return MrTailor_Opt::getOption( 'main_header_wishlist', true );
}

/**
 * Checks if shopping cart icon is enabled.
 */
function mrtailor_shopping_icon_enabled(){

    return MrTailor_Opt::getOption( 'main_header_shopping_bag', true );
}

/**
 * Checks if search cart icon is enabled.
 */
function mrtailor_search_icon_enabled(){

    return MrTailor_Opt::getOption( 'main_header_search_bar', true );
}

/**
 * Checks if my account icon is enabled.
 */
function mrtailor_myaccount_icon_enabled(){

    return MrTailor_Opt::getOption( 'main_header_my_account', true );
}

add_action( 'customize_register', 'mrtailor_customizer_header_elements_controls' );
/**
 * Adds controls for header elements section.
 *
 * @param  [object] $wp_customize [customizer object].
 */
function mrtailor_customizer_header_elements_controls( $wp_customize ) {

    // My Account Icon.
    $wp_customize->add_setting(
    	'main_header_my_account',
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
    		'main_header_my_account',
    		array(
    			'type'          => 'checkbox',
    			'label'         => esc_html__( 'My Account Icon', 'mr_tailor' ),
    			'section'       => 'header_elements',
    			'priority'      => 10,
    		)
    	)
    );

    $wp_customize->selective_refresh->add_partial( 'main_header_my_account', array(
        'selector' => '.site-header .site-tools',
        'settings' => 'main_header_my_account',
        'render_callback' => function() {
            echo mrtailor_get_header_tool_icons();
        },
    ) );

    // Custom My Account Icon.
    $wp_customize->add_setting(
        'main_header_my_account_icon',
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
            'main_header_my_account_icon',
            array(
                'type'        => 'image',
                'label'       => esc_html__( 'Custom My Account Icon', 'mr_tailor' ),
                'section'     => 'header_elements',
                'priority'    => 10,
                'active_callback' => 'mrtailor_myaccount_icon_enabled',
            )
        )
    );

    // Wishlist Icon.
    $wp_customize->add_setting(
    	'main_header_wishlist',
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
    		'main_header_wishlist',
    		array(
    			'type'          => 'checkbox',
    			'label'         => esc_html__( 'Wishlist Icon', 'mr_tailor' ),
                'description'   => '<span class="dashicons dashicons-editor-help"></span>Requires the <a target="_blank" href="https://wordpress.org/plugins/yith-woocommerce-wishlist/">YITH WooCommerce Wishlist</a> plugin.',
    			'section'       => 'header_elements',
    			'priority'      => 10,
    		)
    	)
    );

    // Custom Wishlist Icon.
    $wp_customize->add_setting(
        'main_header_wishlist_icon',
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
            'main_header_wishlist_icon',
            array(
                'type'        => 'image',
                'label'       => esc_html__( 'Custom Wishlist Icon', 'mr_tailor' ),
                'section'     => 'header_elements',
                'priority'    => 10,
                'active_callback' => 'mrtailor_wishlist_icon_enabled',
            )
        )
    );

    // Shopping Cart Icon.
    $wp_customize->add_setting(
    	'main_header_shopping_bag',
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
    		'main_header_shopping_bag',
    		array(
    			'type'          => 'checkbox',
    			'label'         => esc_html__( 'Shopping Cart Icon', 'mr_tailor' ),
    			'section'       => 'header_elements',
    			'priority'      => 10,
    		)
    	)
    );

    // Custom Shopping Cart Icon.
    $wp_customize->add_setting(
        'main_header_shopping_bag_icon',
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
            'main_header_shopping_bag_icon',
            array(
                'type'        => 'image',
                'label'       => esc_html__( 'Custom Shopping Cart Icon', 'mr_tailor' ),
                'section'     => 'header_elements',
                'priority'    => 10,
                'active_callback' => 'mrtailor_shopping_icon_enabled',
            )
        )
    );

    // Search Icon.
    $wp_customize->add_setting(
    	'main_header_search_bar',
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
    		'main_header_search_bar',
    		array(
    			'type'          => 'checkbox',
    			'label'         => esc_html__( 'Search Icon', 'mr_tailor' ),
    			'section'       => 'header_elements',
    			'priority'      => 10,
    		)
    	)
    );

    // Custom Search Icon.
    $wp_customize->add_setting(
        'main_header_search_bar_icon',
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
            'main_header_search_bar_icon',
            array(
                'type'        => 'image',
                'label'       => esc_html__( 'Custom Search Icon', 'mr_tailor' ),
                'section'     => 'header_elements',
                'priority'    => 10,
                'active_callback' => 'mrtailor_search_icon_enabled',
            )
        )
    );
}

?>
