<?php
/**
 * GBT_InstallWizard
 *
 * Install wizard for the theme
 *
 * @class 		GBT_InstallWizard
 * @version		2.0
 * @category	Class
 * @author 		GetBowtied
 */

if ( ! class_exists( 'GBT_InstallWizard' ) ) {

	class GBT_InstallWizard {

		private $steps;
		private $step;

		public function __construct() {

			add_action( 'admin_menu', array( $this, 'initWizard' ) );
			add_action( 'admin_init', array( $this, 'setupWizard' ) );
			add_filter( 'tgmpa_load', array( $this, 'tgmpa_load' ), 10, 1 );
			add_action( 'wp_ajax_gbt_get_wizard_plugins', array( $this, 'ajax_wizard_plugins' ) );
			add_action( 'vc_activation_hook', array( $this, 'vc_page_welcome_redirect' ), 99 );
			add_filter('woocommerce_enable_setup_wizard', array($this, 'wc_install_wizard_redirect'), 10, 1);
		}

		public function initWizard() {
			add_theme_page(
				__('Theme Setup', 'mr_tailor'),
				__('Theme Setup', 'mr_tailor'),
				'administrator',
				'gbt-setup',
				array($this, 'thisisempty')
			);
		}

		public function thisisempty() {
			return;
		}

		public function setupWizard() {

			if ( empty( $_GET['page'] ) || 'gbt-setup' !== $_GET['page'] ) {
				return;
			}

			$default_steps = array(
				'introduction' => array(
					'name'    => esc_html__( 'Introduction', 'mr_tailor' ),
					'view'    => array( $this, 'gbt_setup_introduction' ),
					'handler' => '',
				),
				'plugins' => array(
					'name'    => esc_html__( 'Plugins', 'woocommerce' ),
					'view'    => array( $this, 'gbt_setup_plugins' ),
					'handler' => '',
				),
				'demo' => array(
					'name'    => esc_html__( 'Demo Import', 'woocommerce' ),
					'view'    => array( $this, 'gbt_setup_demo' ),
					'handler' => '',
				),
				'final' => array(
					'name'    => esc_html__( 'Ready!', 'woocommerce' ),
					'view'    => array( $this, 'gbt_setup_final' ),
					'handler' => '',
				),
			);

			$this->steps = $default_steps;
			$this->step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );

			wp_enqueue_style( 'gbt-wizard-css', get_template_directory_uri() .'/inc/admin/wizard/css/wizard.css', array(), mrtailor_theme_version() );
			wp_register_script( 'gbt-wizard-js', get_template_directory_uri() .'/inc/admin/wizard/js/wizard.js', array( 'jquery' ), mrtailor_theme_version() );
			wp_localize_script( 'gbt-wizard-js', 'gbtStrings',
				array(
					'ajax_nonce'       => wp_create_nonce( 'ocdi-ajax-verification' ),
					'ajaxurl'		   => admin_url( 'admin-ajax.php' )
				)
			);
			$this->setup_wizard_steps();
			$this->setup_wizard_header();
			$this->content();
			$this->setup_wizard_footer();
			exit;
		}

		/**
		 * Disable VC redirect
		 *
		 * @return [type] [description]
		 */
		public function vc_page_welcome_redirect() {
			delete_transient( '_vc_page_welcome_redirect' );
		}

		/**
		 * Disable WC wizard redierct
		 *
		 */
		public function wc_install_wizard_redirect( $bool) {
			if ( !empty( $_GET['page'] ) && 'tgmpa-install-plugins' == $_GET['page'] )
				return false;
			return true;
		}

		/**
		 * Get the URL for the next step's screen.
		 *
		 * @param string step   slug (default: current step)
		 * @return string       URL for next step if a next step exists.
		 *                      Admin URL if it's the last step.
		 *                      Empty string on failure.
		 */
		public function get_next_step_link( $step = '' ) {
			if ( ! $step ) {
				$step = $this->step;
			}

			$keys = array_keys( $this->steps );
			if ( end( $keys ) === $step ) {
				return admin_url();
			}

			$step_index = array_search( $step, $keys );
			if ( false === $step_index ) {
				return '';
			}

			return add_query_arg( 'step', $keys[ $step_index + 1 ] );
		}

		public function get_prev_step_link( $step = '' ) {
			if ( ! $step ) {
				$step = $this->step;
			}

			$keys = array_keys( $this->steps );
			if ( end( $keys ) === $step ) {
				return admin_url();
			}

			$step_index = array_search( $step, $keys );
			if ( false === $step_index ) {
				return '';
			}

			return add_query_arg( 'step', $keys[ $step_index - 1 ] );
		}

		/**
		 * Output the steps.
		 */
		public function setup_wizard_steps() {
			$ouput_steps = $this->steps;
			array_shift( $ouput_steps );
			?>

			<div class="gbt-wizard-logo <?php echo getbowtied_theme_slug(); ?>">
				<a href="#">
					<img src="<?php echo esc_url( get_template_directory_uri() .'/inc/admin/wizard/images/mrtailor-logo.png' ); ?>" <?php echo file_exists( esc_url( get_template_directory() .'/inc/admin/wizard/images/mrtailor-logo@2x.png' ) )? 'srcset="'.esc_url( get_template_directory_uri() .'/inc/admin/wizard/images/mrtailor-logo@2x.png 2x'.'"' ) : '' ; ?> alt="Logo">
				</a>
			</div>

			<ol class="gtb-wizard-menu">
				<?php foreach ( $ouput_steps as $step_key => $step ) : ?>
					<li class="<?php
					if ( $step_key === $this->step ) {
						echo 'active';
					} elseif ( array_search( $this->step, array_keys( $this->steps ) ) > array_search( $step_key, array_keys( $this->steps ) ) ) {
						echo 'done';
					}
					?>"><span><?php echo esc_html( $step['name'] ); ?></span></li>
				<?php endforeach; ?>
			</ol>
			<?php
		}

		/**
		 * Setup Wizard Header.
		 */
		public function setup_wizard_header() {
			?>
			<!DOCTYPE html>
			<html <?php language_attributes(); ?>>
			<head>
				<meta name="viewport" content="width=device-width" />
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title><?php wp_title(); ?></title>
				<?php wp_print_scripts( 'gbt-wizard-js' ); ?>
				<?php do_action( 'admin_print_styles' ); ?>
				<?php do_action( 'admin_print_scripts' ); ?>
			</head>
			<body class="gbt-setup-wizard wp-core-ui">


			<?php
			        update_option( 'gbt_' . getbowtied_theme_name() . '_wizard_redirect', 0 );
		}

		/**
		 * Setup Wizard Footer.
		 */
		public function setup_wizard_footer() {
			?>

					<a class="wc-return-to-dashboard" href="<?php echo esc_url( admin_url() ); ?>"><span class="dashicons dashicons-arrow-left-alt"></span><?php esc_html_e( 'Return to the WordPress Dashboard', 'woocommerce' ); ?></a>

				</body>
			</html>
			<?php
		}

		/**
		 * Load the view for the current step
		 */
		public function content() {
			if ( array_key_exists( $this->step, $this->steps ) ) :
				call_user_func( $this->steps[ $this->step ]['view'], $this );
			endif;
		}

		/**
		 * Step Introduction view
		 */
		public function gbt_setup_introduction() {
			?>
				<div class="wrapper wizard-introduction" style="background-image:url(<?php echo esc_url( get_template_directory_uri() .'/inc/admin/wizard/images/mr-tailor-prev.jpg' ); ?>);">
					<div class="center">
						<h1><?php esc_html_e( getbowtied_theme_name() . '\'s', 'mr_tailor');?> <br/> <?php esc_html_e('Theme Setup Wizard', 'mr_tailor' ); ?></h1>
						<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>"
							class="button-primary button button-large button-next">
							<?php esc_html_e( 'Get Started', 'woocommerce' ); ?>
						</a>
						<p><?php esc_html_e( 'The quickest way to setup the theme', 'mr_tailor'); ?> <br /> <?php esc_html_e('and start working on your site.', 'mr_tailor' );?></p>
					</div>
				</div>

			<?php
		}

		/**
		 * Step Plugins View
		 */
		public function gbt_setup_plugins() {
			?>
				<div class="wrapper wizard-plugins">

					<div class="content-info">
						<h1><?php esc_html_e( 'Plugin Installation', 'mr_tailor' ); ?></h1>
						<p><?php esc_html_e( 'Install the required plugins before importing the demo content.', 'mr_tailor' ); ?></p>
					</div>

					<ul class="plugins">
						<?php $plugins = $this->_get_plugins(); ?>

						<?php $this->_get_plugins_list($plugins, true); // required plugins ?>
						<li class="recommended">
							<h3><?php esc_html_e( 'Recommended', 'mr_tailor' ); ?></h3>
						</li>
						<?php $this->_get_plugins_list($plugins, false); // recommended plugins ?>
					</ul>

					<div class="buttons">
						<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button skip"><?php esc_html_e( 'Skip', 'mr_tailor' ); ?></a>
						<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button install "><?php esc_html_e( 'Install', 'mr_tailor' ); ?></a>
					</div>
				</div>

			<?php
		}

		public function _get_plugins_list( $plugins, $required ) {
			foreach ( $plugins as $slug => $plugin ) { ?>
				<?php if( isset($plugin['demo_required']) && $plugin['demo_required'] === $required ) : ?>
					<li class="plugin <?php echo esc_attr( $slug ); ?>">
						<div class="plugin-overlay"></div>
						<input
							id="<?php echo esc_attr($plugin['slug']); ?>"
							class="checkbox <?php echo ( isset($plugin['demo_required']) && $plugin['demo_required'] === true ) ? 'required' : 'optional' ?>"
							type="checkbox"
							name="<?php echo esc_attr($plugin['slug']); ?>"
							value="yes"
							checked
							<?php echo ( isset($plugin['demo_required']) && $plugin['demo_required'] === true ) ? 'disabled="disabled"' : '' ?>
						/>
						<label for="<?php echo esc_attr($plugin['slug']); ?>" class="<?php echo ( isset($plugin['demo_required']) && $plugin['demo_required'] === true ) ? 'required' : 'optional' ?>">
							<?php if (file_exists(get_template_directory() .'/inc/admin/wizard/images/plugin-icons/'. $plugin['slug'] .'.jpg')): ?>
								<img class="plugin-image" src="<?php echo esc_url( get_template_directory_uri() .'/inc/admin/wizard/images/plugin-icons/'. $plugin['slug'] .'.jpg' ); ?>" alt="<?php echo esc_attr($plugin['slug']); ?>" />
							<?php else: ?>
								<img class="plugin-image" src="<?php echo esc_url( get_template_directory_uri() . '/images/placeholder.png' ); ?>" alt="<?php echo esc_attr($plugin['slug']); ?>" />
							<?php endif; ?>
							<div class="plugin-description-container">
								<h3><?php echo esc_html( $plugin['name'] ); ?></h3>
								<p><?php echo isset( $plugin['description'] )? esc_html_e( $plugin['description'], 'mr_tailor' ) : ''; ?></p>
							</div>

							<div class="plugin-install">
								<div class="plugin-status">
									<span class="state"></span>
								</div>

								<div class="action-links" style="display:none">
									<?php
									$url = wp_nonce_url(
										add_query_arg(
											array(
												'plugin'   		   			 => urlencode( $slug ),
												'tgmpa-' . $plugin['status'] => $plugin['status'] . '-plugin',
											),
											admin_url( 'themes.php?page=tgmpa-install-plugins' )
										),
										'tgmpa-' . $plugin['status'],
										'tgmpa-nonce'
									);

									$activate_url = wp_nonce_url(
										add_query_arg(
											array(
												'plugin'   		   			 => urlencode( $slug ),
												'tgmpa-activate' => 'activate-plugin',
											),
											admin_url( 'themes.php?page=tgmpa-install-plugins' )
										),
										'tgmpa-activate',
										'tgmpa-nonce'
									);
								?>

								<?php if ( ! empty( $plugin['status'] ) && ($plugin['status'] != 'no-action') ) : ?>
										<a  class="button ajax-request <?php echo esc_html( $plugin['status'] ); ?>-now"
											href="<?php echo esc_url( $url ); ?>"
											data-plugin="<?php echo esc_attr( $slug ); ?>"
											data-verify="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
											data-action="<?php echo esc_attr( $plugin['status'] ); ?>"
											data-activateurl="<?php echo esc_url($activate_url); ?>">
											<?php echo ( isset($plugin['status']) && $plugin['status'] == 'install' ) ? esc_html_e( 'Install Now', 'mr_tailor' ) : ''; ?>
											<?php echo ( isset($plugin['status']) && $plugin['status'] == 'update' ) ? esc_html_e( 'Update Now', 'mr_tailor' ) : ''; ?>
											<?php echo ( isset($plugin['status']) && $plugin['status'] == 'activate' ) ? esc_html_e( 'Activate', 'mr_tailor' ) : ''; ?>
										</a>

									<?php else : ?>
										<a class="button button-disabled">
											<?php  esc_html_e( 'Active', 'mr_tailor' ); ?>
										</a>
									<?php endif; ?>
								</div>
							</div>
						</label>
					</li>
				<?php
				endif;
			}
		}

		/**
		 * Step Demo View
		 */
		public function gbt_setup_demo() {
			?>

			<div class="wrapper wizard-demo-import">

				<?php $is_required_plugins = ( class_exists( 'WooCommerce' ) &&  class_exists( 'OCDI_Plugin' ) ) ? true : false; ?>

				<div class="content-info">
					<h1><?php esc_html_e( 'Demo Content Import', 'mr_tailor' ); ?></h1>
					<p><?php esc_html_e( 'Start with pre-built page layouts, dummy product pages,', 'mr_tailor');?><br/> <?php esc_html_e('blog posts and widgets.', 'mr_tailor' ); ?></p>
				</div>

				<div class="demo-icon <?php echo ( ! $is_required_plugins === true) ? 'error' : '' ?>">
					<?php if ( $is_required_plugins ) : ?>
						<img src="<?php echo esc_url( get_template_directory_uri() .'/inc/admin/wizard/images/install-demo-import-white.png' ); ?>" alt="Demo Import">
					<?php else : ?>
						<p class="error-info"><?php printf(esc_html__( 'Please make sure %s is installed and activated before importing the demo content.', 'mr_tailor' ), '<b>WooCommerce</b>') ; ?></p>
					<?php endif; ?>
				</div>

				<div class="buttons">
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button skip"><?php esc_html_e( 'Skip', 'mr_tailor' ); ?></a>
					<?php if ( $is_required_plugins ) : ?>
						<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button install"><?php esc_html_e( 'Install', 'mr_tailor' ); ?></a>
					<?php else : ?>
						<a href="<?php echo esc_url( $this->get_prev_step_link() ); ?>" class="button "><?php esc_html_e( 'Go back', 'mr_tailor' ); ?></a>
					<?php endif; ?>
				</div>

				<div class="ocdi__response  js-ocdi-ajax-response"></div>

			</div>


			<?php
		}

		/**
		 * Step Final View
		 */
		public function gbt_setup_final() {
			?>

			<div class="wrapper wizard-ready">
				<div class="content-done" style="background-image:url(<?php echo esc_url( get_template_directory_uri() .'/inc/admin/wizard/images/mr-tailor-prev.jpg' ); ?>);">
					<div class="center">
						<h1><?php printf(esc_html__( 'Setup has been %s completed successfully!', 'mr_tailor' ), '<br/>'); ?></h1>
						<a href="<?php echo esc_url( home_url() ); ?>" class="button button-primary"><?php esc_html_e( 'View Site', 'mr_tailor' );?></a>
						<p><?php esc_html_e('You should be able to start working on your site now.','mr_tailor');?> <br/>
						<?php esc_html_e('Best of luck with your project!', 'mr_tailor'); ?></p>
					</div>
				</div>

				<div class="further-info">
					<h2><?php esc_html_e( 'What\'s next?', 'mr_tailor' );?></h2>
					<div class="column">

						<ul>
							<li>
								<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>">
									<span class="dashicons dashicons-admin-appearance"></span>
									<?php esc_html_e( 'Customize the theme', 'mr_tailor' ); ?>
								</a>
							</li>
							<li>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-admin&path=%2Fsetup-wizard' ) );?>">
									<span class="dashicons dashicons-admin-settings"></span>
									<?php esc_html_e( 'WooCommerce Setup Wizard', 'mr_tailor' ); ?>
								</a>
							</li>
						</ul>
					</div>
					<div class="column last">
						<ul>
							<li>
								<?php
									if ( class_exists( 'Envato_Market' ) ) {
										$activate = esc_url( admin_url('admin.php?page=envato-market'));
									} else {
										$activate = esc_url( admin_url('themes.php?page=tgmpa-install-plugins'));
									}
								?>
								<a href="<?php print esc_url($activate); ?>">
									<span class="dashicons dashicons-update"></span><?php esc_html_e('Activate Theme Updates', 'mr_tailor' ); ?>
								</a>
							</li>
							<li><a href="<?php echo esc_url(MT_THEME_DOCS_WEBSITE); ?>" target="_blank"><span class="dashicons dashicons-editor-help"></span><?php esc_html_e( 'Help Center / Support', 'mr_tailor' ); ?></a></li>
						</ul>
					</div>
				</div>

			</div>

			<?php
		}

		public function tgmpa_load() {
			return is_admin() || current_user_can( 'install_themes' );
		}

		public function _get_plugins() {
			$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );

			$installed_plugins = get_plugins();
			$plugins  = array();

			foreach ( $instance->plugins as $slug => $plugin ) {
				if ( $slug == 'getbowtied-tools' ) { continue;
				}
				$plugins[ $slug ] = $plugin;

				if ( isset( $installed_plugins[ $plugin['file_path'] ]['Version'] ) ) :
					$plugins[ $slug ]['version'] = $installed_plugins[ $plugin['file_path'] ]['Version'];
					endif;

				if ( ! $instance->is_plugin_installed( $slug ) ) {
					$plugins[ $slug ]['status'] = 'install';
				} else {
					if ( false !== $instance->does_plugin_have_update( $slug ) ) {
						$plugins[ $slug ]['status'] = 'update';
					} elseif ( $instance->can_plugin_activate( $slug ) ) {
						$plugins[ $slug ]['status'] = 'activate';
					} else {
						$plugins[ $slug ]['status'] = 'no-action';
					}
				}
			}

			$newplugins = array();
			$newplugins['woocommerce'] = $plugins['woocommerce'];
			$newplugins['product-blocks-for-woocommerce'] = $plugins['product-blocks-for-woocommerce'];
			$newplugins['one-click-demo-import'] = $plugins['one-click-demo-import'];
			$newplugins['envato-market'] = $plugins['envato-market'];
			$newplugins['mr-tailor-extender'] = $plugins['mr-tailor-extender'];
			$newplugins['hookmeup'] = $plugins['hookmeup'];

			return $newplugins;
		}

		public function ajax_wizard_plugins() {
			$plugins = $this->_get_plugins();
			if ( $plugins[ $_POST['gbt_plugin'] ]['status'] === 'activate' ) {
				wp_send_json('activate');
				return;
			}
			wp_send_json( ($plugins[ $_POST['gbt_plugin'] ]['status'] === 'no-action') || ($plugins[ $_POST['gbt_plugin'] ]['status'] === 'update') );
			return;
		}

	}

	new GBT_InstallWizard();
}
