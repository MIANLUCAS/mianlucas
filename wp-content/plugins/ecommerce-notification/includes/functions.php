<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Function include all files in folder
 *
 * @param $path   Directory address
 * @param $ext    array file extension what will include
 * @param $prefix string Class prefix
 */
if ( ! function_exists( 'vi_include_folder' ) ) {
	function vi_include_folder( $path, $prefix = '', $ext = array( 'php' ) ) {

		/*Include all files in payment folder*/
		if ( ! is_array( $ext ) ) {
			$ext = explode( ',', $ext );
			$ext = array_map( 'trim', $ext );
		}
		$sfiles = scandir( $path );
		foreach ( $sfiles as $sfile ) {
			if ( $sfile != '.' && $sfile != '..' ) {
				if ( is_file( $path . "/" . $sfile ) ) {
					$ext_file  = pathinfo( $path . "/" . $sfile );
					$file_name = $ext_file['filename'];
					if ( $ext_file['extension'] ) {
						if ( in_array( $ext_file['extension'], $ext ) ) {
							$class = preg_replace( '/\W/i', '_', $prefix . ucfirst( $file_name ) );

							if ( ! class_exists( $class ) ) {
								require_once $path . $sfile;
								if ( class_exists( $class ) ) {
									new $class;
								}
							}
						}
					}
				}
			}
		}
	}
}
if ( ! function_exists( 'ecommerce_notification_prefix' ) ) {
	function ecommerce_notification_prefix() {
		$prefix = get_option( '_ecommerce_notification_prefix', date( "Ymd" ) );

		return $prefix . '_products_' . date( "Ymd" );
	}
}

if ( ! function_exists( 'vi_ecommerce_notification_background_images' ) ) {
	function vi_ecommerce_notification_background_images( $key = false ) {
		$prefix   = $key ? 'bg_' : '';
		$b_images = array(
			'black'        => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'black.png',
			'red'          => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'red.png',
			'pink'         => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'pink.png',
			'yellow'       => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'yellow.png',
			'violet'       => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'violet.png',
			'blue'         => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'blue.png',
			'grey'         => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'grey.png',
			'orange'       => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'orange.png',
			'spring'       => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'spring.png',
			'summer'       => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'summer.png',
			'autumn'       => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'autumn.png',
			'winter'       => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'winter.png',
			'black_friday' => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'black_friday.png',
			'new_year'     => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'new_year.png',
			'valentine'    => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'valentine.png',
			'halloween'    => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'halloween.png',
			'kids'         => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'kids.png',
			'father_day'   => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'father_day.png',
			'mother_day'   => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'mother_day.png',
			'shoes'        => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'shoes.png',
			't_shirt'      => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 't_shirt.png',
			'christmas'    => VI_ECOMMERCE_NOTIFICATION_BACKGROUND_IMAGES . $prefix . 'christmas.png',
		);
		if ( $key ) {
			return isset( $b_images[ $key ] ) ? $b_images[ $key ] : false;
		} else {
			return $b_images;
		}
	}
}
