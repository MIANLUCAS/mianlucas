<?php

function mrtailor_remove_vc_teaser() {
	remove_meta_box( 'vc_teaser', '' , 'side' );
}

add_action( 'init', 'mrtailor_wpbakery_setup' );
function mrtailor_wpbakery_setup() {

	//enable vc on post types
	if( function_exists('vc_set_default_editor_post_types') ) {
		vc_set_default_editor_post_types( array('post','page','product') );
	}

	// Remove vc_teaser
	if (is_admin()) {
		add_action( 'admin_head', 'mrtailor_remove_vc_teaser' );
	}
}

add_action( 'vc_before_init', 'mrtailor_vcSetAsTheme' );
function mrtailor_vcSetAsTheme() {
	vc_manager()->disableUpdater(true);
	vc_set_as_theme();
}
