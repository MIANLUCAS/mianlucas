<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//==============================================================================
//  Enqueue Editor Assets
//==============================================================================
add_action( 'enqueue_block_editor_assets', 'gbt_18_mt_social_media_editor_assets' );
if ( ! function_exists( 'gbt_18_mt_social_media_editor_assets' ) ) {
    function gbt_18_mt_social_media_editor_assets() {

        wp_enqueue_script(
            'gbt_18_mt_social_media_script',
            plugins_url( 'block.js', __FILE__ ),
            array( 'wp-blocks', 'wp-components', 'wp-editor', 'wp-i18n', 'wp-element' )
        );

        wp_enqueue_style(
            'gbt_18_mt_social_media_editor_styles',
            plugins_url( 'assets/css/editor.css', __FILE__ ),
            array( 'wp-edit-blocks' )
        );
    }
}

//==============================================================================
//  Register Block Type
//==============================================================================
if ( function_exists( 'register_block_type' ) ) {
    register_block_type( 'getbowtied/mt-social-media-profiles', array(
    	'attributes'     			=> array(
    		'align'			        => array(
    			'type'				=> 'string',
    			'default'			=> 'left',
    		),
            'fontSize'              => array(
                'type'              => 'number',
                'default'           => '24',
            ),
            'fontColor'             => array(
                'type'              => 'string',
                'default'           => '#000',
            ),
    	),

    	'render_callback' => 'gbt_18_mt_social_media_frontend_output',
    ) );
}

//==============================================================================
//  Frontend Output
//==============================================================================
if ( ! function_exists( 'gbt_18_mt_social_media_frontend_output' ) ) {
    function gbt_18_mt_social_media_frontend_output($attributes) {

        global $mr_tailor_theme_options;

    	extract(shortcode_atts(
    		array(
    			'align'       => 'left',
                'fontSize'    => '24',
                'fontColor'   => '#000',
    		), $attributes));
        ob_start();

        ?>

        <div class="gbt_18_mt_social_media_profiles">
            <?php echo do_shortcode('[social-media items_align="'.$align.'" font_size="'.$fontSize.'" color="'.$fontColor.'"]'); ?>
        </div>

        <?php
    	return ob_get_clean();
    }
}
