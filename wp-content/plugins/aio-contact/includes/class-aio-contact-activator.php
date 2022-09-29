<?php

/**
 * Fired during plugin activation
 *
 * @link       harshitpeer.com
 * @since      1.0.0
 *
 * @package    Aio_Contact
 * @subpackage Aio_Contact/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Aio_Contact
 * @subpackage Aio_Contact/includes
 * @author     Harshit Peer <harshitpeer@gmail.com>
 */
class Aio_Contact_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$role = get_role( 'administrator' );
		$role->add_cap( 'aio_contact' );
	}

}
