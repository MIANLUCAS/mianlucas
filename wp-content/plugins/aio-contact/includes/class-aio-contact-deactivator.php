<?php

/**
 * Fired during plugin deactivation
 *
 * @link       harshitpeer.com
 * @since      1.0.0
 *
 * @package    Aio_Contact
 * @subpackage Aio_Contact/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Aio_Contact
 * @subpackage Aio_Contact/includes
 * @author     Harshit Peer <harshitpeer@gmail.com>
 */
class Aio_Contact_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$role = get_role( 'administrator' );
		$role->remove_cap( 'aio_contact' );
	}

}
