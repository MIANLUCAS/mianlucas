<?php

if ( ! isset( $content_width ) ) $content_width = 640;

/*
 * Theme Setup.
 */
add_action( 'after_setup_theme', 'mrtailor_theme_setup' );
function mrtailor_theme_setup() {

    // Theme textdomain.
    load_theme_textdomain( 'mr_tailor', get_template_directory() . '/languages' );

    // Register menus.
	register_nav_menus( array(
		'top-bar-navigation'  => esc_html__( 'Top Bar Navigation', 'mr_tailor' ),
		'main-navigation'     => esc_html__( 'Main Navigation', 'mr_tailor' ),
	) );

	// Theme support.
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );

	// Gutenberg.
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );

    add_theme_support( 'wc-product-gallery-slider' );

    // Restore the classic widget editor.
    remove_theme_support( 'widgets-block-editor' );

    if( MrTailor_Opt::getOption( 'product_gallery_lightbox', false ) ) {
        add_theme_support( 'wc-product-gallery-lightbox' );
    }

    if( MrTailor_Opt::getOption( 'product_gallery_zoom', true ) ) {
        add_theme_support( 'wc-product-gallery-zoom' );
    }

    add_editor_style( 'css/admin/editor-styles.css' );

	// WooCommerce.
	add_theme_support( 'woocommerce', array(
	    // Product grid theme settings
	    'product_grid'        => array(
	        'default_rows'    => get_option('woocommerce_catalog_rows', 5),
	        'min_rows'        => 2,
	        'max_rows'        => '',

	        'default_columns' => get_option('woocommerce_catalog_columns', 5),
	        'min_columns'     => 2,
	        'max_columns'     => 6,
	    ),
	) );
}

/*
 * Post Type - Enable Excerpts.
 */
add_action('init', 'mrtailor_post_type_support');
function mrtailor_post_type_support() {
	add_post_type_support( 'page', 'excerpt' );
}

/*
 * Fix empty title on homepage.
 */
add_filter( 'wp_title', 'mrtailor_wp_title', 10, 2 );
function mrtailor_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( esc_html__( 'Page %s', 'mr_tailor' ), max( $paged, $page ) );
	}

	return $title;
}

/*
 * Revolution Slider set as Theme.
 */
if( function_exists( 'set_revslider_as_theme' ) ) {
	set_revslider_as_theme();
}

/*
 * Post excerpt more text
 */
add_filter( 'excerpt_more', 'mrtailor_excerpt_more' );
function mrtailor_excerpt_more( $more ) {
    return '...';
}
/*
 * Filter except length to 35 words.
 */
add_filter( 'excerpt_length', 'mrtailor_custom_excerpt_length', 999 );
function mrtailor_custom_excerpt_length( $length ) {
    if( '2' === MrTailor_Opt::getOption( 'sidebar_blog_listing' ) ) {
        $length = 20;
    }

    return $length;
}

/**
 * Add default favicon
 */
function mrtailor_favicon(){
	if (has_site_icon() == false)
	    echo '<link rel="icon" href="' . get_stylesheet_directory_uri() . '/favicon.png" />';
}
add_action( 'wp_head', 'mrtailor_favicon' );
add_action( 'admin_head', 'mrtailor_favicon' );
