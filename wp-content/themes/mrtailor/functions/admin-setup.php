<?php

require_once( get_template_directory() . '/inc/tgm/class-tgm-plugin-activation.php' );
require_once( get_template_directory() . '/inc/tgm/plugins.php' );

require_once( get_template_directory() . '/inc/customizer/class/class-mrtailor-icons.php');

require_once( get_template_directory() . '/inc/admin/wizard/class-gbt-install-wizard.php' );

require_once( get_template_directory() . '/inc/demo/ocdi-setup.php');


/**
 * On theme activation redirect to splash page.
 */
add_action( 'after_switch_theme', 'mrtailor_redirect_to_splash_page' );
function mrtailor_redirect_to_splash_page() {
	global $pagenow;
	if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
		wp_redirect(admin_url("themes.php?page=gbt-setup")); // Your admin page URL
	}
}

/**
 * HookMeUp admin notification
 */
add_action( 'admin_notices', 'mrtailor_hookmeup_notification' );
function mrtailor_hookmeup_notification() {

	if ( !get_option('dismissed-hookmeup-notice', FALSE ) && !class_exists('HookMeUp') ) { ?>
		<div class="notice-warning settings-error notice is-dismissible hookmeup_notice">
			<p>
				<strong>
					<span>This theme recommends the following plugin: <em><a href="https://wordpress.org/plugins/hookmeup/" target="_blank">HookMeUp – Additional Content for WooCommerce</a></em>.</span>
				</strong>
			</p>
		</div>
	<?php }
}

function mrtailor_dismiss_dashboard_notice() {
	if( $_POST['notice'] == 'hookmeup' ) {
		update_option('dismissed-hookmeup-notice', TRUE );
	}

	if( $_POST['notice'] == 'extender' ) {
		update_option('dismissed-extender-notice', TRUE );
	}

	if( $_POST['notice'] == 'portfolio' ) {
		update_option('dismissed-portfolio-notice', TRUE );
	}
}
add_action( 'wp_ajax_mrtailor_dismiss_dashboard_notice', 'mrtailor_dismiss_dashboard_notice' );

/**
 * Block editor layout class
 *
 * @param string $classes
 * @return string
 */
function mrtailor_editor_layout_class( $classes ) {
	global $post;

	$screen = get_current_screen();
	if( ! $screen->is_block_editor() )
		return $classes;

	if ( isset( $post ) && get_post_type($post->ID) == 'page' ) {
		$pagetemplate = get_post_meta( $post->ID, '_wp_page_template', true );
		if ( !empty( $pagetemplate ) ) {
			switch ( $pagetemplate ) {
				case 'page-full-width.php':
					$classes .= ' page-template-full ';
					break;
				case 'page-narrow.php':
					$classes .= ' page-template-narrow ';
					break;
				case 'page-blank.php':
					$classes .= ' page-template-full ';
					break;
				default:
					$classes .= ' page-template-default ';
					break;
			}
		} else {
			$classes .= ' page-template-default ';
		}
	}

	if ( isset( $post ) && get_post_type($post->ID) == 'post' ) {
		if( '1' === MrTailor_Opt::getOption( 'sidebar_blog_listing', '2' ) ) {
			$classes .= ' post-layout-with-sidebar';
		} else {
			$classes .= ' post-layout-default';
		}
	}

	return $classes;
}
add_filter( 'admin_body_class', 'mrtailor_editor_layout_class' );
