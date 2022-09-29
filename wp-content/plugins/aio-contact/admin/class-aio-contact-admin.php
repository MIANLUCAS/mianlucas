<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       harshitpeer.com
 * @since      1.0.0
 *
 * @package    Aio_Contact
 * @subpackage Aio_Contact/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Aio_Contact
 * @subpackage Aio_Contact/admin
 * @author     Harshit Peer <harshitpeer@gmail.com>
 */
class Aio_Contact_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Aio_Contact_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Aio_Contact_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if (strpos(get_current_screen()->id, $this->plugin_name) !== false) {
			wp_enqueue_style( 'wp-color-picker' ); 
			wp_enqueue_style( 'bootstrap', plugins_url( $this->plugin_name ) . '/vendor/bootstrap/bootstrap.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'bootstrap-iconpicker', plugins_url( $this->plugin_name ) . '/vendor/bootstrap-iconpicker/bootstrap-iconpicker.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'fontawesome-free', plugins_url( $this->plugin_name ) . '/vendor/fontawesome-free/all.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'hover', plugins_url( $this->plugin_name ) . '/vendor/hover/hover.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . '/css/aio-contact-admin.min.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Aio_Contact_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Aio_Contact_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if (strpos(get_current_screen()->id, $this->plugin_name) !== false) {
			wp_enqueue_media();
			wp_enqueue_script( 'wp-color-picker');
			wp_enqueue_script( 'bootstrap-bundle', plugins_url( $this->plugin_name ) . '/vendor/bootstrap/bootstrap.bundle.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'bootstrap-iconpicker-bundle', plugins_url( $this->plugin_name ) . '/vendor/bootstrap-iconpicker/bootstrap-iconpicker.bundle.min.js#defer', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'sweetalert2', plugins_url( $this->plugin_name ) . '/vendor/sweetalert2/sweetalert2.all.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'jquery-ui', plugins_url( $this->plugin_name ) . '/vendor/jquery-ui/jquery-ui-1.12.1.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . '/js/aio-contact-admin.min.js', array(), $this->version, false );
			wp_localize_script( $this->plugin_name, 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
			wp_enqueue_script( $this->plugin_name . '-jquery', plugin_dir_url( __FILE__ ) . '/js/aio-contact-admin-jquery.min.js', array( 'jquery' ), $this->version, false );
		}

	}

	/**
	 * Add the AIO Contact Pages
	 * 
	 * @since	1.0.0
	 */
	public function add_plugin_admin_menu() {
		/*
		* Add a settings page for this plugin to the Settings menu.
		*
		* NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		*
		*        Administration Menus: http://codex.wordpress.org/Administration_Menus
		*
		*/
		add_menu_page('AIO Contact', 'AIO Contact', 'aio_contact', $this->plugin_name, array($this, 'display_aio_contact_items_page'), 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9IiM5ZWEzYTgiIHdpZHRoPSI2NCIgaGVpZ2h0PSI2NCIgdmlld0JveD0iMCAwIDI1IDI1Ij48cGF0aCBkPSJNMTcuMDE2IDE3LjAxNnYtMS41cTAtMS4xMjUtMS43MTEtMS44Mjh0LTMuMzA1LTAuNzAzLTMuMzA1IDAuNzAzLTEuNzExIDEuODI4djEuNWgxMC4wMzF6TTEyIDYuNzVxLTAuOTM4IDAtMS41OTQgMC42NTZ0LTAuNjU2IDEuNTk0IDAuNjU2IDEuNTk0IDEuNTk0IDAuNjU2IDEuNTk0LTAuNjU2IDAuNjU2LTEuNTk0LTAuNjU2LTEuNTk0LTEuNTk0LTAuNjU2ek0yMC4wMTYgMy45ODRxMC43OTcgMCAxLjM4MyAwLjYwOXQwLjU4NiAxLjQwNnYxMnEwIDAuNzk3LTAuNTg2IDEuNDA2dC0xLjM4MyAwLjYwOWgtMTYuMDMxcS0wLjc5NyAwLTEuMzgzLTAuNjA5dC0wLjU4Ni0xLjQwNnYtMTJxMC0wLjc5NyAwLjU4Ni0xLjQwNnQxLjM4My0wLjYwOWgxNi4wMzF6TTMuOTg0IDI0di0yLjAxNmgxNi4wMzF2Mi4wMTZoLTE2LjAzMXpNMjAuMDE2IDB2Mi4wMTZoLTE2LjAzMXYtMi4wMTZoMTYuMDMxeiI+PC9wYXRoPjwvc3ZnPg==');
		add_submenu_page( $this->plugin_name, 'Items', 'Items', 'aio_contact', $this->plugin_name, array($this, 'display_aio_contact_items_page'));
		add_submenu_page( $this->plugin_name, 'Settings', 'Settings', 'aio_contact', $this->plugin_name . '-settings', array($this, 'display_aio_contact_settings_page'));
		add_submenu_page( $this->plugin_name, 'Import / Export', 'Import / Export', 'aio_contact', $this->plugin_name . '-import-export', array($this, 'display_aio_contact_import_export_page'));
		add_submenu_page( $this->plugin_name, 'Instructions', 'Instructions', 'aio_contact', $this->plugin_name . '-instructions', array($this, 'display_aio_contact_instructions_page'));
	}

	/**
	 * Render the Instructions Page
	 * 
	 * @since	1.0.0
	 */
	public function display_aio_contact_instructions_page() {
		include_once('partials/aio-contact-admin-instructions.php');
	}

	/**
	 * Render the Import Export Page
	 * 
	 * @since	1.1.0
	 */
	public function display_aio_contact_import_export_page() {
		include_once('partials/aio-contact-admin-import-export.php');
	}

	/**
	 * Importing the File
	 * 
	 * @since	1.1.0
	 */
	public function import_aio_contact_data() {
		if(filter_input(INPUT_POST, 'import_data', FILTER_SANITIZE_STRING) !== null) {
			$file = $_FILES['file']['tmp_name'];
			if(empty($file)) {
				add_action('admin_notices', function(){
					echo $this->show_admin_notice('error', __('Please choose a file to import'));
				});
			} else {
				$data = json_decode(file_get_contents($file));
				if(isset($data->items)) {
					update_option( $this->plugin_name . '-items', $data->items );
				} 
				if(isset($data->settings)) {
					update_option( $this->plugin_name . '-settings', (array) $data->settings );
				}
				add_action('admin_notices', function(){
					echo $this->show_admin_notice('success', __('Successfully Imported!'));
				});
			}
		}
	}

	/**
	 * Show Success Message
	 * 
	 * @since	1.1.0
	 */
	public function show_admin_notice($type = 'success', $message = '') {
		?>
		<div id="setting-error-settings_updated" class="notice notice-<?php esc_attr_e($type) ?> is-dismissible">
			<p><?php _e($message) ?></p>
		</div>
		<?php
	}

	/**
	 * Render the Items Page for AIO Contact
	 * 
	 * @since	1.0.0
	 */
	public function display_aio_contact_items_page() {
		$is_subscriber = true;
		if(current_user_can('upload_files')) {
			$is_subscriber = false;
		}
		include_once('partials/aio-contact-admin-items.php');
	}

	/** 
	 * Saving Items Data
	 * 
	 * @since	1.0.0
	 */
	public function save_aio_contact_items() {
		$data = json_decode(stripslashes($_POST['data']));
		if(current_user_can( 'aio_contact' ) && $data) {
			update_option( $this->plugin_name . '-items', $data );
		} else {
			wp_send_json_error('', 400);
		}
		wp_die();
	}

	/**
	 * Render the Settings Page for AIO Contact
	 * 
	 * @since	1.0.0
	 */
	public function display_aio_contact_settings_page() {
		include_once('partials/aio-contact-admin-settings.php');
	}

	/**
	 * Saving Settings Data
	 * 
	 * @since	1.0.0
	 */
	public function save_aio_contact_settings() {
		if(filter_input(INPUT_POST, 'settings', FILTER_SANITIZE_STRING) !== null)
		register_setting( $this->plugin_name, $this->plugin_name . '-settings', array($this, 'validate_aio_contact_settings') );
	}

	/**
	 * Validating Settings Data
	 * 
	 * @since	1.0.0
	 */
	public function validate_aio_contact_settings() {
		$input = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
		$bool_options = array('border', 'hide_text', 'button_box_shadow', 'box_shadow', 'hide_not_available_agents', 'show_dot_status', 'rounded_avatar', 'disable_animation', 'single_item', 'clear_at_uninstall');
		$options = array('orientation', 'border_radius', 'position', 'button_icon', 'button_icon_color', 'button_color', 'text_color', 'border_color', 'background_color');
		$data = array();
		foreach($bool_options as $bool_option) {
			if(isset($input[$bool_option])) {
				$data[$bool_option] = 1;
			} else {
				$data[$bool_option] = 0;
			}
		}
		foreach($options as $option) {
			$data[$option] = $input[$option];
		}
		return $data;
	}

	/**
	 * Check if current page is a AIO Contact Plugin Page
	 * 
	 * @since	1.0.0
	 */
	public function is_plugin_page() {
		$current_page_slug = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';
		if(strpos($current_page_slug, 'aio-contact') !== false) {
			return true;
		}
		return false;
	}

	/**
	 * Get Admin Plugin directory
	 * 
	 * @since	1.0.0
	 */
	public function plugin_admin_dir() {
		return plugin_dir_url( __FILE__ );
	}

	/**
	 * Adding Plugin Header
	 * 
	 * @since	1.0.0
	 */
	public function add_plugin_header() {
		if($this->is_plugin_page()) {
			include_once('partials/common/aio-contact-admin-header.php');
		}
	}

	/**
	 * Changing the Footer Text for Plugin Pages
	 * 
	 * @since	1.0.0
	 */

	public function change_plugin_footer() {
		$url = "https://codecanyon.net/item/aio-contact-all-in-one-contact-widget/28060681";
		$text = sprintf(
			wp_kses(
				__( '<span class="aio-contact">Please rate <strong>AIO Contact</strong> <a href="%1$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%2$s" target="_blank" rel="noopener noreferrer">CodeCanyon.net</a> to give a super boost to our confidence in keep innovating. Thank you from the AIO Contact Dev team!</span>', $this->plugin_name ),
				array(
					'strong' => array(),
					'span' => array(
						'class' => array(),
					),
					'a'      => array(
						'href'   => array(),
						'target' => array(),
						'rel'    => array(),
					),
				)
			),
			$url,
			$url
		);
		return $text;
	}

	/**
	 * Adding DEFER to script
	 * 
	 * @since	1.0.0
	 */
	public function add_defer($link) {
		if(strpos($link, '#defer') === false) {
			return $link;
		}
		return str_replace('#defer', '', $link) . '\' defer=\'true';
	}

}
