<?php

/**
 * AIO Contact Common Header Section
 *
 * @link       harshitpeer.com
 * @since      1.0.0
 *
 * @package    Aio_Contact
 * @subpackage Aio_Contact/admin/partials
 */
?>

<div class="aio-contact aio-contact-header">
    <div class="aio-contact-logo">
        <img src="<?php echo $this->plugin_admin_dir() . 'images/logo.svg' ?>" alt="AIO Contact Logo">	
    </div>
    <div class="aio-contact-menu">
        <a href="<?php echo admin_url( 'admin.php?page=' . $this->plugin_name ) ?>" class="aio-contact-menu-item"><?php _e('Items') ?></a>
        <a href="<?php echo admin_url( 'admin.php?page=' . $this->plugin_name . '-settings' ) ?>" class="aio-contact-menu-item"><?php _e('Settings') ?></a>
        <a href="<?php echo admin_url( 'admin.php?page=' . $this->plugin_name . '-import-export' ) ?>" class="aio-contact-menu-item"><?php _e('Import / Export') ?></a>
        <a href="<?php echo admin_url( 'admin.php?page=' . $this->plugin_name . '-instructions' ) ?>" class="aio-contact-menu-item"><?php _e('Instructions') ?></a>
    </div>	
</div>