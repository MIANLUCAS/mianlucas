<?php

/**
 * AIO Contact Instructions Page
 *
 * @link       harshitpeer.com
 * @since      1.0.0
 *
 * @package    Aio_Contact
 * @subpackage Aio_Contact/admin/partials
 */

$settings = get_option( $this->plugin_name . '-settings');
if(!is_array($settings)) {
    $settings = array();
}
$items = get_option( $this->plugin_name . '-items');
if($items == null) {
    $items = array();
}
?>

<div class="aio-contact aio-contact-body">
    <div class="aio-contact-section">
        <div class="aio-contact-section-title">
            <h2><?php _e('Import or Export') ?></h2>
            <p class="aio-h-italic-text aio-h-dim-text"><?php _e('You can use this section to import or export your AIO Contact Data') ?></p>
        </div>
        <div class="aio-contact-fields">
            <form method="post" enctype="multipart/form-data">
                <div class="aio-contact-field">
                    <div class="aio-contact-field-label">
                        <label for="import"><?php _e('Import JSON File') ?></label>
                    </div>
                    <div class="aio-contact-field-input">
                        <input type="file" name="file" id="import">
                        <p class="aio-field-description aio-h-italic-text aio-h-dim-text mb-3"><?php _e('PROCEED WITH CAUTION! This action will ERASE all the current data.') ?></p>
                        <?php 
                        submit_button(esc_html__('Import Data', $this->plugin_name), 'primary','import_data', TRUE); 
                        ?>
                    </div>
                </div>
            </form>
            <form>
                <div class="aio-contact-field">
                    <div class="aio-contact-field-label">
                        <label><?php _e('Export as JSON File') ?></label>
                    </div>
                    <div class="aio-contact-field-input">
                        <input type="checkbox" id="export_items" name="export" value="items">
                        <label class="form-check-label" for="export_items"><?php _e('Items') ?></label>
                        <br>
                        <input type="checkbox" id="export_settings" name="export" value="settings">
                        <label class="form-check-label" for="export_settings"><?php _e('Settings') ?></label>
                        <br>
                        <button type="button" name="export" id="export" class="button button-primary btn-sm mt-3"><?php _e('Export Data') ?></button>
                    </div>
                </div>
            </form>
         </div>
    </div>
</div>
<span id="data_items" class="d-none"><?php _e(json_encode($items)) ?></span>
<span id="data_settings" class="d-none"><?php _e(json_encode($settings)) ?></span>
