<?php

function mrtailor_admin_scripts() {
    if ( is_admin() ) {
        wp_enqueue_script( 'mr_tailor_admin_notice', get_template_directory_uri() . "/js/admin/wp-admin-notice.js", array('jquery'), false, '1.0' );
		wp_enqueue_script( 'mr_tailor-go-to-page', 	 get_template_directory_uri() . "/js/admin/wp-go-to-page.js", 	array('jquery'), true, '1.0' );
    }
}
add_action( 'admin_enqueue_scripts', 'mrtailor_admin_scripts' );
