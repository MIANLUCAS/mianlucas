<?php
	// VC element: nm_instagram
	vc_map( array(
	   'name'			=> __( 'Instagram', 'nm-instagram' ),
	   'category'		=> __( 'Content', 'nm-instagram' ),
	   'description'	=> __( 'Instagram gallery', 'nm-instagram' ),
	   'base'			=> 'nm_instagram',
       'icon'			=> 'nm_instagram',
	   'params'			=> array(
           array(
                'type' 			=> 'textfield',
                'heading' 		=> __( 'Image Limit', 'nm-instagram' ),
                'param_name' 	=> 'image_limit',
                'description'	=> __( 'Number of images to display.', 'nm-instagram' ),
                'std'			=> '6'
            ),
            array(
                'type' 			=> 'dropdown',
                'heading' 		=> __( 'Images per Row', 'nm-instagram' ),
                'param_name' 	=> 'images_per_row',
                'description'   => __( 'Select number of images to display per row.', 'nm-instagram' ),
                'value' 		=> array(
                    '2' => '2',
                    '4' => '4',
                    '6' => '6',
                    '8' => '8'
                ),
                'std'			=> '6'
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __( 'Image Spacing', 'nm-instagram' ),
                'param_name' 	=> 'image_spacing_class',
                'description'	=> __( 'Display spacing between images.', 'nm-instagram' ),
                'value'			=> array(
                    __( 'Enable', 'nm-instagram' ) => 'has-spacing'
                ),
                'std'			=> '0'
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __( 'User Link', 'nm-instagram' ),
                'param_name' 	=> 'instagram_user_link',
                'description'	=> __( 'Display link to the Instagram user.', 'nm-instagram' ),
                'value'			=> array(
                    __( 'Enable', 'nm-instagram' ) => '1'
                ),
                'std'			=> '0'
            )
	   )
	) );