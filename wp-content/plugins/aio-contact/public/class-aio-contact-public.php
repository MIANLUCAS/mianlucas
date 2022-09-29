<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       harshitpeer.com
 * @since      1.0.0
 *
 * @package    Aio_Contact
 * @subpackage Aio_Contact/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Aio_Contact
 * @subpackage Aio_Contact/public
 * @author     Harshit Peer <harshitpeer@gmail.com>
 */
class Aio_Contact_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( 'animate.css', plugins_url( $this->plugin_name ) . '/vendor/animate/animate.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'fontawesome-free', plugins_url( $this->plugin_name ) . '/vendor/fontawesome-free/all.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . '/css/aio-contact-public.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . '/js/aio-contact-public.min.js', array(), $this->version, false );

	}

	public function load_aio() {
		
		$items = get_option( $this->plugin_name . '-items' );
		//Remove Below 3 Lines in v2.0
		if(!is_array($items)) {
			$items = json_decode($items);
		}
		$settings = get_option( $this->plugin_name . '-settings' );

		//Setting Default Values
		if(!is_array($settings)) {
			$settings = array();
		}

		$default_settings = new \stdClass;
		$default_settings->orientation = 'v';
		$default_settings->border = false;
		$default_settings->border_radius = '10';
		$default_settings->border_color = '#EEEEEE';
		$default_settings->text_color = '#111111';
		$default_settings->background_color = '#FFFFFF';
		$default_settings->box_shadow = true;
		$default_settings->hide_text = false;
		$default_settings->position = 'br';
		$default_settings->button_icon = 'fas fa-comment-alt';
		$default_settings->button_color = '#3047EC';
		$default_settings->button_icon_color = '#FFFFFF';
		$default_settings->button_box_shadow = true;
		$default_settings->hide_not_available_agents = true;
		$default_settings->show_dot_status = true;
		$default_settings->rounded_avatar = true;
		$default_settings->disable_animation = false;
		$default_settings->single_item = false;

		foreach($default_settings as $key => $value) {
			if(!isset($settings[$key])) {
				$settings[$key] = $value;
			}
		}

		$aio_contact_floating_classes = '';
		if(esc_attr($settings['show_dot_status'])) {
			$aio_contact_floating_classes .= ' aio-contact-show-dot-status';
		}
		if(esc_attr($settings['orientation'] == 'h')) {
			$aio_contact_floating_classes .= ' aio-contact-inline';
		}
		if(esc_attr($settings['border'])) {
			$aio_contact_floating_classes .= ' aio-contact-bordered';
		}
		if(esc_attr($settings['box_shadow'])) {
			$aio_contact_floating_classes .= ' aio-contact-box-shadow';
		}
		if(esc_attr($settings['hide_not_available_agents'])) {
			$aio_contact_floating_classes .= ' aio-contact-hide-not-available-agent';
		}
		if(esc_attr($settings['hide_text'])) {
			$aio_contact_floating_classes .= ' aio-contact-no-text';
		}
		if(esc_attr($settings['rounded_avatar'])) {
			$aio_contact_floating_classes .= ' aio-contact-rounded-avatar';
		}

		$aio_contact_trigger_classes = '';
		if(esc_attr($settings['button_box_shadow'])) {
			$aio_contact_trigger_classes .= ' aio-contact-box-shadow';
		}

		$aio_custom_css = "
		.aio-contact-parent .aio-contact-floating { border-color: ".esc_attr($settings['border_color'])." }
		.aio-contact-parent .aio-contact-floating { color: ".esc_attr($settings['text_color'])." }
		.aio-contact-parent .aio-contact-floating .aio-contact-block-title { color: ".esc_attr($settings['text_color'])." }
		.aio-contact-parent .aio-contact-floating { background: ".esc_attr($settings['background_color'])." }
		.aio-contact-parent .aio-contact-floating { border-radius: ".esc_attr($settings['border_radius'])."px }
		.aio-contact-parent .aio-contact-trigger { background: ".esc_attr($settings['button_color'])." }
		.aio-contact-parent .aio-contact-trigger i { color: ".esc_attr($settings['button_icon_color'])." }
		";

		if(esc_attr($settings['position']) == 'bl') {
			$aio_custom_css .= '
			.aio-contact-parent .aio-contact-trigger { left: 20px; }
			.aio-contact-parent .aio-contact-floating { left: 30px; right: unset; }
			.aio-contact-parent .aio-contact-floating.aio-contact-no-text { left: 7px; right: unset; }
			';
		}
		if(count($items) > 0) {
			include_once('partials/aio-contact-public-display.php');
		}
	}

}
