<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              harshitpeer.com
 * @since             1.0.0
 * @package           Aio_Contact
 *
 * @wordpress-plugin
 * Plugin Name:       AIO Contact 
 * Plugin URI:        https://harshitpeer.com/aio-contact
 * Description:       The All-in-One Contact is a simple yet super usefull plugin which allows you to add multiple contact option for your end users
 * Version:           1.1.0
 * Author:            Harshit Peer
 * Author URI:        harshitpeer.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       aio-contact
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AIO_CONTACT_VERSION', '1.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-aio-contact-activator.php
 */
function aio_contact_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aio-contact-activator.php';
	Aio_Contact_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-aio-contact-deactivator.php
 */
function aio_contact_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aio-contact-deactivator.php';
	Aio_Contact_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'aio_contact_activate' );
register_deactivation_hook( __FILE__, 'aio_contact_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-aio-contact.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function aio_contact_run() {

	$plugin = new Aio_Contact();
	$plugin->run();

}
aio_contact_run();
