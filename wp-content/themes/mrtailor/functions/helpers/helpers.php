<?php

/**
 * Define constants.
 */
define( 'MT_THEME_WEBSITE', 			'https://mr-tailor.getbowtied.com' );
define( 'MT_GOOGLE_FONTS_WEBSITE', 		'https://fonts.google.com' );
define( 'MT_SAFE_FONTS_WEBSITE', 		'https://www.w3schools.com/cssref/css_websafe_fonts.asp' );
define( 'MT_THEME_DOCS_WEBSITE', 		'https://www.getbowtied.com/docs/mr-tailor/' );

define( 'MT_WOOCOMMERCE_IS_ACTIVE', 	class_exists( 'WooCommerce' ) );
define( 'MT_WPBAKERY_IS_ACTIVE', 		class_exists( 'Vc_Manager' ) );
define( 'MT_WISHLIST_IS_ACTIVE', 		class_exists( 'YITH_WCWL' ) );

// -----------------------------------------------------------------------------
// String to Slug
// -----------------------------------------------------------------------------

if ( ! function_exists( 'getbowtied_string_to_slug' ) ) :
function getbowtied_string_to_slug($str) {
	$str = strtolower(trim($str));
	$str = preg_replace('/[^a-z0-9-]/', '_', $str);
	$str = preg_replace('/-+/', "_", $str);
	return $str;
}
endif;

// -----------------------------------------------------------------------------
// Theme Name
// -----------------------------------------------------------------------------

if ( ! function_exists( 'getbowtied_theme_name' ) ) :
function getbowtied_theme_name() {
	$theme = wp_get_theme();
	if ( $theme->parent() !== false ) {
		$theme_name = $theme->parent()->get('Name');
	} else {
		$theme_name = $theme->get('Name');
	}

	return $theme_name;
}
endif;

// -----------------------------------------------------------------------------
// Theme Slug
// -----------------------------------------------------------------------------

if ( ! function_exists( 'getbowtied_theme_slug' ) ) :
function getbowtied_theme_slug() {
	$getbowtied_theme = wp_get_theme();
	return $getbowtied_theme->template;
}
endif;

// -----------------------------------------------------------------------------
// Theme Author
// -----------------------------------------------------------------------------

if ( ! function_exists( 'getbowtied_theme_author' ) ) :
function getbowtied_theme_author() {
	$getbowtied_theme = wp_get_theme();
	return $getbowtied_theme->get('Author');
}
endif;

// -----------------------------------------------------------------------------
// Theme Description
// -----------------------------------------------------------------------------

if ( ! function_exists( 'getbowtied_theme_description' ) ) :
function getbowtied_theme_description() {
	$getbowtied_theme = wp_get_theme();
	return $getbowtied_theme->get('Description');
}
endif;

// -----------------------------------------------------------------------------
// Convert hex to rgb
// -----------------------------------------------------------------------------

if ( ! function_exists( 'getbowtied_hex2rgb' ) ) :
function getbowtied_hex2rgb($hex) {
	$hex = str_replace("#", "", $hex);

	if(strlen($hex) == 3) {
		$r = hexdec(substr($hex,0,1).substr($hex,0,1));
		$g = hexdec(substr($hex,1,1).substr($hex,1,1));
		$b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
	}
	$rgb = array($r, $g, $b);
	return implode(",", $rgb); // returns the rgb values separated by commas
	//return $rgb; // returns an array with the rgb values
}
endif;

// -----------------------------------------------------------------------------
// Theme Version
// -----------------------------------------------------------------------------

function mrtailor_theme_version() {
	$getbowtied_theme = wp_get_theme(get_template());
	return $getbowtied_theme->get('Version');
}

/**
 * Sanitizes select controls
 *
 * @param string $input [the input].
 * @param string $setting [the settings].
 *
 * @return string
 */
function mrtailor_sanitize_select( $input, $setting ) {
	$input   = sanitize_key( $input );
	$choices = isset($setting->manager->get_control( $setting->id )->choices) ? $setting->manager->get_control( $setting->id )->choices : '';

	return ( $choices && array_key_exists( $input, $choices ) ) ? $input : $setting->default;
}

/**
 * Sanitizes image upload.
 *
 * @param string $input potentially dangerous data.
 */
function mrtailor_sanitize_image( $input ) {
	$filetype = wp_check_filetype( $input );
	if ( $filetype['ext'] && ( wp_ext2type( $filetype['ext'] ) === 'image' || $filetype['ext'] === 'svg' ) ) {
		return esc_url( $input );
	}
	return '';
}

/**
 * Converts string to bool
 *
 * @param string $string [the input].
 *
 * @return boolean
 */
function mrtailor_string_to_bool( $string ) {
    return is_bool( $string ) ? $string : ( 'yes' === $string || 1 === $string || 'true' === $string || '1' === $string );
}

/**
 * Sanitizes checkbox controls
 * Returns true if checkbox is checked
 *
 * @param string $input [the input].
 *
 * @return boolean
 */
function mrtailor_sanitize_checkbox( $input ){

	return mrtailor_string_to_bool($input);
}

/**
 * Converts HEX color to RGB color
 *
 * @param string $hex [the input].
 *
 * @return string rgb color.
 */
function mrtailor_hex2rgb( $hex ) {
	$hex = str_replace("#", "", $hex);

	if(strlen($hex) == 3) {
		$r = hexdec(substr($hex,0,1).substr($hex,0,1));
		$g = hexdec(substr($hex,1,1).substr($hex,1,1));
		$b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
	}
	$rgb = array($r, $g, $b);

	return implode(",", $rgb);
}
