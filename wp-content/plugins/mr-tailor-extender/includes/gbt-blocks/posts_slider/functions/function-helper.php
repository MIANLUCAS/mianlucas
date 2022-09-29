<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//==============================================================================
//	Post Featured Image Helper
//==============================================================================
add_action('rest_api_init', 'gbt_18_mt_register_rest_post_images' );
if ( ! function_exists( 'gbt_18_mt_register_rest_post_images' ) ) {
    function gbt_18_mt_register_rest_post_images(){
        register_rest_field( array('post'),
            'fimg_url',
            array(
                'get_callback'    => 'gbt_18_mt_get_rest_post_featured_image',
                'update_callback' => null,
                'schema'          => null,
            )
        );
    }
}

if ( ! function_exists( 'gbt_18_mt_get_rest_post_featured_image' ) ) {
    function gbt_18_mt_get_rest_post_featured_image( $object, $field_name, $request ) {
        if( $object['featured_media'] ){
            $img = wp_get_attachment_image_src( $object['featured_media'], 'app-thumb' );
            if( isset( $img[0] ) ) {
                return $img[0];
            }
        }
        return false;
    }
}
