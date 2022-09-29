<?php
/**
 * Customizer controls.
 */

/**
* Sets the customizer sections
*
* @param  [object] $wp_customize [customizer object].
*/
add_action( 'customize_register','mrtailor_customizer_sections' );
function mrtailor_customizer_sections( $wp_customize ) {

    $wp_customize->add_panel( 'panel_header', array(
        'title'          => esc_html__( 'Header', 'mr_tailor' ),
        'priority'       => 5,
        'capability'     => 'edit_theme_options',
    ) );

    $wp_customize->add_section( 'header_style', array(
        'title'          => esc_attr__('Header Styles', 'mr_tailor' ),
        'priority'       => 10,
        'capability'     => 'edit_theme_options',
        'panel'          => 'panel_header',
    ) );

    $wp_customize->add_section( 'header_elements', array(
        'title'          => esc_attr__('Header Elements', 'mr_tailor' ),
        'priority'       => 10,
        'capability'     => 'edit_theme_options',
        'panel'          => 'panel_header',
    ) );

    $wp_customize->add_section( 'header_logo', array(
        'title'          => esc_attr__('Logo', 'mr_tailor' ),
        'priority'       => 10,
        'capability'     => 'edit_theme_options',
        'panel'          => 'panel_header',
    ) );

    $wp_customize->add_section( 'header_transparency', array(
        'title'          => esc_attr__('Header Transparency', 'mr_tailor' ),
        'priority'       => 10,
        'capability'     => 'edit_theme_options',
        'panel'          => 'panel_header',
    ) );

    $wp_customize->add_section( 'header_topbar', array(
        'title'          => esc_attr__('Top Bar', 'mr_tailor' ),
        'priority'       => 10,
        'capability'     => 'edit_theme_options',
        'panel'          => 'panel_header',
    ) );

    $wp_customize->add_section( 'header_sticky', array(
        'title'          => esc_attr__('Sticky Header', 'mr_tailor' ),
        'priority'       => 10,
        'capability'     => 'edit_theme_options',
        'panel'          => 'panel_header',
    ) );

    $wp_customize->add_section( 'panel_footer', array(
        'title'          => esc_html__( 'Footer', 'mr_tailor' ),
        'priority'       => 5,
        'capability'     => 'edit_theme_options',
    ) );

    $wp_customize->add_section( 'panel_shop', array(
        'title'          => esc_html__( 'Shop', 'mr_tailor' ),
        'priority'       => 6,
        'capability'     => 'edit_theme_options',
    ) );

    $wp_customize->add_section( 'panel_product', array(
        'title'          => esc_html__( 'Product Page', 'mr_tailor' ),
        'priority'       => 7,
        'capability'     => 'edit_theme_options',
    ) );

    $wp_customize->add_section( 'panel_minicart', array(
        'title'          => esc_html__( 'Mini Cart', 'mr_tailor' ),
        'priority'       => 8,
        'capability'     => 'edit_theme_options',
    ) );

    $wp_customize->add_section( 'panel_blog', array(
        'title'          => esc_html__( 'Blog', 'mr_tailor' ),
        'priority'       => 9,
        'capability'     => 'edit_theme_options',
    ) );

    $wp_customize->add_section( 'panel_styling', array(
        'title'          => esc_html__( 'Styling', 'mr_tailor' ),
        'priority'       => 10,
        'capability'     => 'edit_theme_options',
    ) );

    $wp_customize->add_panel( 'panel_fonts', array(
        'title'          => esc_html__( 'Fonts', 'mr_tailor' ),
        'priority'       => 11,
        'capability'     => 'edit_theme_options',
    ) );

    $wp_customize->add_section( 'main_font', array(
        'title'          => esc_html__( 'Headings Font', 'mr_tailor' ),
        'priority'       => 12,
        'capability'     => 'edit_theme_options',
        'panel'          => 'panel_fonts',
    ) );

    $wp_customize->add_section( 'secondary_font', array(
        'title'          => esc_html__( 'Base Font', 'mr_tailor' ),
        'priority'       => 13,
        'capability'     => 'edit_theme_options',
        'panel'          => 'panel_fonts',
    ) );
}

/*
* Go To page
*/
include_once( get_template_directory() . '/inc/customizer/go-to-page.php' );

/**
* Header.
*/
include_once( get_template_directory() . '/inc/customizer/sections/header/header-styles.php' );
include_once( get_template_directory() . '/inc/customizer/sections/header/header-transparency.php' );
include_once( get_template_directory() . '/inc/customizer/sections/header/header-elements.php' );
include_once( get_template_directory() . '/inc/customizer/sections/header/header-logo.php' );
include_once( get_template_directory() . '/inc/customizer/sections/header/header-sticky.php' );
include_once( get_template_directory() . '/inc/customizer/sections/header/header-topbar.php' );

/**
* Footer.
*/
include_once( get_template_directory() . '/inc/customizer/sections/section-footer.php' );

/**
* Styling.
*/
include_once( get_template_directory() . '/inc/customizer/sections/section-styling.php' );

/**
* Blog.
*/
include_once( get_template_directory() . '/inc/customizer/sections/section-blog.php' );

/**
* Fonts.
*/
include_once( get_template_directory() . '/inc/customizer/sections/fonts/fonts-main-font.php' );
include_once( get_template_directory() . '/inc/customizer/sections/fonts/fonts-secondary-font.php' );

if( MT_WOOCOMMERCE_IS_ACTIVE ) {

    /**
    * Shop Page.
    */
    include_once( get_template_directory() . '/inc/customizer/sections/section-shop.php' );

    /**
    * Product Page.
    */
    include_once( get_template_directory() . '/inc/customizer/sections/section-product.php' );

    /**
    * Mini Cart.
    */
    include_once( get_template_directory() . '/inc/customizer/sections/section-minicart.php' );
}

/*
* Font options check
*/
function mrtailor_update_font_options() {

    $main_font = MrTailor_Opt::getOption( 'main_font', 'DM Sans' );
    $secondary_font = MrTailor_Opt::getOption( 'secondary_font', 'DM Sans' );

    if( is_array($main_font) && isset($main_font['font-family']) ) {
        set_theme_mod( 'main_font', $main_font['font-family'] );
    }

    if( is_array($secondary_font) && isset($secondary_font['font-family']) ) {
        set_theme_mod( 'secondary_font', $secondary_font['font-family'] );
    }

    update_option( 'mt_update_font_options', true );

    return;
}

if( !get_option( 'mt_update_font_options', false ) ) {
	add_action( 'init', 'mrtailor_update_font_options' );
}


/*
* New color options default on update check
*/
function mrtailor_update_color_options() {

    set_theme_mod( 'top_bar_links_color', MrTailor_Opt::getOption( 'top_bar_typography' ) );
    update_option( 'mt_update_color_options', true );

    return;
}

if( !get_option( 'mt_update_color_options', false ) ) {
	add_action( 'init', 'mrtailor_update_color_options' );
}
