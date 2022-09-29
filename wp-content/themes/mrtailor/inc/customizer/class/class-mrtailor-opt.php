<?php

class MrTailor_Opt {

	/**
	* Cache each request to prevent duplicate queries
	*
	* @var array
	*/
	protected static $cached = [];

	/**
	*  Constructor
	*/
	private function __construct() {}

	/**
	* Default values for theme options
	*
	* @return array
	*/
	private static function theme_defaults() {
		return array(

			// Header Styles
			'header_layout' 								=> '2',
			'header_paddings' 								=> 20,
			'main_header_background_color' 					=> '#ffffff',
			'main_header_font_size' 						=> 16,
			'main_header_font_color' 						=> '#000000',

			'main_header_wishlist' 							=> true,
			'main_header_wishlist_icon' 					=> '',
			'main_header_shopping_bag' 						=> true,
			'main_header_shopping_bag_icon' 				=> '',
			'main_header_search_bar' 						=> true,
			'main_header_search_bar_icon' 					=> '',
			'main_header_my_account'						=> false,
			'main_header_my_account_icon'					=> '',

			'site_logo' 									=> '',
			'sticky_header_logo' 							=> '',
			'logo_height' 									=> 60,
			'alt_logo_height'								=> 40,

			'main_header_background_transparency' 			=> false,
			'main_header_transparency_scheme' 				=> 'transparency_light',
			'shop_category_header_transparency_scheme'		=> 'inherit',
			'main_header_transparent_light_color' 			=> '#ffffff',
			'light_transparent_header_logo' 				=> '',
			'main_header_transparent_dark_color' 			=> '#000000',
			'dark_transparent_header_logo' 					=> '',

			'top_bar_switch' 								=> true,
			'top_bar_sticky'								=> false,
			'top_bar_background_color' 						=> '#f9f9f9',
			'top_bar_typography' 							=> '#686868',
			'top_bar_links_color'							=> '#000000',
			'top_bar_text' 									=> esc_html__( 'Free Shipping on All Orders Over $75!', 'mr_tailor' ),

			'sticky_header' 								=> true,

			// Footer Styles
			'footer_background_color' 						=> '#ffffff',
			'footer_texts_color' 							=> '#686868',
			'footer_links_color' 							=> '#000000',
			'credit_card_icons' 							=> get_template_directory_uri() . '/images/payment_cards.png',
			'footer_copyright_text'							=> esc_html__( 'Designed with ', 'mr_tailor' ) . '<a href="'.MT_THEME_WEBSITE.'" target="_blank" title="eCommerce WordPress Theme for WooCommerce">'.esc_html__( 'Mr. Tailor', 'mr_tailor' ).'</a>.',
			'footer_highlight_widget'						=> false,
			'expandable_footer' 							=> true,

			// Shop Styles
			'shop_layout' 									=> false,
			'catalog_mode' 									=> false,
			'breadcrumbs' 									=> true,
			'open_minicart'									=> true,
			'add_to_cart_display'							=> '1',
			'shop_pagination'								=> 'classic',
			'products_animation' 							=> 'e2',
			'product_hover_animation' 						=> true,
			'sale_text' 									=> esc_html__( 'Sale!', 'mr_tailor' ),
			'out_of_stock_text' 							=> esc_html__( 'Out of stock', 'mr_tailor' ),

			// Product Styles
			'products_layout' 								=> false,
			'product_gallery_zoom' 							=> true,
			'product_gallery_lightbox' 						=> false,
			'recently_viewed_products' 						=> true,

			// Mini cart
			'minicart_text'									=> '',

			// Blog Styles
			'sidebar_blog_listing' 							=> '2',

			// Styling
			'main_color' 									=> '#0d244c',
			'main_bg_color' 								=> '#ffffff',
			'main_bg_image' 								=> '',
			'navigation_bg' 								=> '#0d244c',
			'navigation_link_color' 						=> '#ffffff',

			// Fonts
			'main_font_source' 								=> '1',
			'main_font' 									=> 'DM Sans',
			'main_font_typekit_kit_id' 						=> '',
			'main_typekit_font_face' 						=> '',
			'secondary_font_source' 						=> '1',
			'secondary_font' 								=> 'DM Sans',
			'main_font_face_display'						=> 'swap',
			'secondary_font_face_display'					=> 'swap',
			'secondary_font_typekit_kit_id' 				=> '',
			'secondary_typekit_font_face' 					=> '',
			'body_text_font_size' 							=> 16,
			'body_color' 									=> '#222222',
			'h1_font_size' 									=> 55,
			'headings_color' 								=> '#000000',
		);
	}

	/**
	* Return the theme option
	*
	* @param  string $option_name
	* @param  string $default
	*
	* @return string
	*/
	public static function getOption( $option_name, $default= '' ) {

		/* Return cached if possible */
		if ( array_key_exists($option_name, self::$cached) && empty($default) )
		return self::$cached[$option_name];
		/* If no default is given, fetch from theme defaults variable */
		if (empty($default)) {
			$default = array_key_exists($option_name, self::theme_defaults())? self::theme_defaults()[$option_name] : '';
		}

		$opt= get_theme_mod($option_name, $default);

		/* Cache the result */
		self::$cached[$option_name]= $opt;

		return self::$cached[$option_name];
	}
}
