<?php

// Helpers.
require_once( get_template_directory() . '/functions/helpers/helpers.php');

// Customizer.
include_once( get_template_directory() . '/inc/customizer/config.php' );
include_once( get_template_directory() . '/inc/customizer/class/class-mrtailor-fonts.php' );
include_once( get_template_directory() . '/inc/customizer/class/class-mrtailor-opt.php' );

// Custom Styles.
require_once( get_template_directory() . '/inc/custom-styles/custom-styles.php');

// Post Meta.
require_once( get_template_directory() . '/inc/templates/post-meta.php');

// Template Tags.
require_once( get_template_directory() . '/inc/templates/template-tags.php');

// Include Metaboxes.
require_once( get_template_directory() . '/inc/metaboxes/page.php');
require_once( get_template_directory() . '/inc/metaboxes/post.php');

// Theme Settings
include_once( get_template_directory() . '/functions/theme-setup.php' );

// Admin Settings
include_once( get_template_directory() . '/functions/admin-setup.php' );

// Enqueue Styles.
include_once( get_template_directory() . '/functions/enqueue/admin-styles.php' );
include_once( get_template_directory() . '/functions/enqueue/styles.php' );

// Enqueue Scripts.
include_once( get_template_directory() . '/functions/enqueue/admin-scripts.php' );
include_once( get_template_directory() . '/functions/enqueue/scripts.php' );

// Widget Areas
include_once( get_template_directory() . '/functions/wp/header-functions.php' );
include_once( get_template_directory() . '/functions/wp/widget-areas.php' );

// WPBakery Page Builder.
if( MT_WPBAKERY_IS_ACTIVE ) {
	include_once( get_template_directory() . '/functions/wb/wpbakery-setup.php' );
}

if( MT_WOOCOMMERCE_IS_ACTIVE ) {
	include_once( get_template_directory() . '/functions/wc/actions.php' );
	include_once( get_template_directory() . '/functions/wc/filters.php' );
	include_once( get_template_directory() . '/functions/wc/custom.php' );
}
