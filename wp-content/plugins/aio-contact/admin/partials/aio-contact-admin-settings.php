<?php

/**
 * AIO Contact Settings Page
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
$default_settings->button_icon_color = '#FFFFFF';
$default_settings->button_color = '#3047EC';
$default_settings->button_box_shadow = true;
$default_settings->hide_not_available_agents = true;
$default_settings->show_dot_status = true;
$default_settings->rounded_avatar = true;
$default_settings->disable_animation = false;
$default_settings->single_item = false;
$default_settings->clear_at_uninstall = false;

foreach($default_settings as $key => $value) {
    if(!isset($settings[$key])) {
        $settings[$key] = $value;
    }
}
?>
<?php settings_errors(); ?>
<div class="aio-contact aio-contact-body">
    <div class="aio-contact-section">
        <div class="aio-contact-section-title">
            <h2><?php _e('Appearance') ?></h2>
            <p class="aio-h-italic-text aio-h-dim-text"><?php _e('You can tweak the settings here to change the look and feel of AIO Contact.') ?></p>
        </div>
        <div class="aio-contact-fields">
            <form action="options.php" method="post">
                <div class="aio-contact-field">
                    <div class="aio-contact-field-label">
                        <label for="orientation"><?php _e('Orientation') ?></label>
                    </div>
                    <div class="aio-contact-field-input">
                        <select name="orientation" id="orientation">
                            <option value="v" <?php esc_attr_e( ($settings['orientation'] == 'v') ? 'selected' : '' ) ?>><?php _e('Vertical') ?></option>
                            <option value="h" <?php esc_attr_e( ($settings['orientation'] == 'h') ? 'selected' : '' ) ?>><?php _e('Horizontal') ?></option>
                        </select>
                        <br>
                        <div class="aio-h-divider"></div>
                        <div class="aio-contact-extra-field">
                            <input type="checkbox" id="disable_animation" name="disable_animation" value="1" <?php esc_attr_e( ($settings['disable_animation']) ? 'checked' : '' ) ?>>
                            <label class="form-check-label" for="disable_animation"><?php _e('Disable Animation') ?></label>
                        </div>
                    </div>
                </div>
                <div class="aio-contact-field">
                    <div class="aio-contact-field-label">
                        <label for="text_color"><?php _e('Text Color') ?></label>
                    </div>
                    <div class="aio-contact-field-input">
                        <input type="text" class="color-picker" name="text_color" id="text_color" value="<?php esc_attr_e( $settings['text_color'] ) ?>">
                        <br>
                        <p class="aio-field-description aio-h-italic-text aio-h-dim-text"><?php _e('This is the text color shown in contact widget') ?></p>
                    </div>
                </div>
                <div class="aio-contact-field">
                    <div class="aio-contact-field-label">
                        <label for="background_color"><?php _e('Background Color') ?></label>
                    </div>
                    <div class="aio-contact-field-input">
                        <input type="text" class="color-picker" name="background_color" id="background_color" value="<?php esc_attr_e( $settings['background_color'] ) ?>">
                        <br>
                        <p class="aio-field-description aio-h-italic-text aio-h-dim-text"><?php _e('This is the background color where all the items and agents are shown') ?></p>
                        <div class="aio-h-divider"></div>
                        <div class="aio-contact-extra-field">
                            <input type="checkbox" id="box_shadow" name="box_shadow" value="1" <?php esc_attr_e( ($settings['box_shadow']) ? 'checked' : '' ) ?>>
                            <label class="form-check-label" for="box_shadow"><?php _e('Show Box Shadow') ?></label>
                        </div>
                    </div>
                </div>
                <div class="aio-contact-field">
                    <div class="aio-contact-field-label">
                        <label for="border"><?php _e('Border') ?></label>
                    </div>
                    <div class="aio-contact-field-input">
                        <div class="aio-contact-extra-field">
                            <input type="checkbox" id="border" name="border" value="1" <?php esc_attr_e( ($settings['border']) ? 'checked' : '' ) ?>>
                            <label class="form-check-label" for="border"><?php _e('Show Border') ?></label>
                        </div>
                        <p class="aio-field-description aio-h-italic-text aio-h-dim-text"><?php _e('Adds a border to items and AIO Contact') ?></p>
                        <div class="aio-h-divider"></div>
                        <label class="d-block" for="border_color"><?php _e('Border Color') ?></label>
                        <input type="text" class="form-control color-picker" name="border_color" id="border_color" value="<?php esc_attr_e( $settings['border_color'] ) ?>">
                        <div class="aio-h-divider"></div>
                        <div class="form-group">
                            <label for="border_radius"><?php _e('Border Radius') ?></label>
                            <div class="form-row align-items-center">
                                <div class="col-auto">
                                    <input type="range" class="custom-range" min="0" max="30" step="1" value="<?php esc_attr_e( $settings['border_radius'] ) ?>" id="border_radius">
                                </div>
                                <div class="col-auto">
                                    <div class="input-group mb-2">
                                        <input type="number" name="border_radius" class="aio-h-number-hide-arrows" value="<?php esc_attr_e( $settings['border_radius'] ) ?>" id="border_radius_value">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><?php _e('px') ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="aio-contact-field">
                    <div class="aio-contact-field-label">
                        <label for="hide_text"><?php _e('Title and Name') ?></label>
                    </div>
                    <div class="aio-contact-field-input">
                        <div class="aio-contact-extra-field">
                            <input type="checkbox" id="hide_text" name="hide_text" value="1" <?php esc_attr_e( ($settings['hide_text']) ? 'checked' : '' ) ?>>
                            <label class="form-check-label" for="hide_text"><?php _e('Hide Text') ?></label>
                        </div>
                        <p class="aio-field-description aio-h-italic-text aio-h-dim-text"><?php _e('If enabled both Item Title and Agent Name will be hidden') ?></p>
                    </div>
                </div>
                <div class="aio-contact-field">
                    <div class="aio-contact-field-label">
                        <label for="position"><?php _e('Position') ?></label>
                    </div>
                    <div class="aio-contact-field-input">
                        <select name="position" id="position">
                            <option value="bl" <?php esc_attr_e( ($settings['position'] == 'bl') ? 'selected' : '' ) ?>><?php _e('Bottom Left') ?></option>
                            <option value="br" <?php esc_attr_e( ($settings['position'] == 'br') ? 'selected' : '' ) ?>><?php _e('Bottom Right') ?></option>
                        </select>
                        <br>
                        <p class="aio-field-description aio-h-italic-text aio-h-dim-text"><?php _e('You can change the position of the AIO Contact button') ?></p>
                    </div>
                </div>
                <div class="aio-contact-field">
                    <div class="aio-contact-field-label">
                        <label for="button_icon"><?php _e('Button Icon') ?></label>
                    </div>
                    <div class="aio-contact-field-input">
                        <button class="btn btn-secondary" id="button_icon" name="button_icon" role="iconpicker" data-icon="<?php esc_attr_e( $settings['button_icon'] ) ?>"></button>
                        <br>
                        <p class="aio-field-description aio-h-italic-text aio-h-dim-text"><?php _e('This is the icon which will be shown the trigger button') ?></p>
                        <div class="aio-h-divider"></div>
                        <input type="text" class="color-picker" name="button_icon_color" id="button_icon_color" value="<?php esc_attr_e( $settings['button_icon_color'] ) ?>">
                        <p class="aio-field-description aio-h-italic-text aio-h-dim-text"><?php _e('You can set the button icon color') ?></p>
                    </div>
                </div>
                <div class="aio-contact-field">
                    <div class="aio-contact-field-label">
                        <label for="button_color"><?php _e('Button Color') ?></label>
                    </div>
                    <div class="aio-contact-field-input">
                        <input type="text" class="color-picker" name="button_color" id="button_color" value="<?php esc_attr_e( $settings['button_color'] ) ?>">
                        <br>
                        <p class="aio-field-description aio-h-italic-text aio-h-dim-text"><?php _e('This is the color of the trigger button') ?></p>
                        <div class="aio-h-divider"></div>
                        <div class="aio-contact-extra-field">
                            <input type="checkbox" id="button_box_shadow" name="button_box_shadow" value="1" <?php esc_attr_e( ($settings['button_box_shadow']) ? 'checked' : '' ) ?>>
                            <label class="form-check-label" for="button_box_shadow"><?php _e('Show Button Box Shadow') ?></label>
                        </div>
                    </div>
                </div>
                <div class="aio-contact-field">
                    <div class="aio-contact-field-label">
                        <label for="background_color"><?php _e('Agents') ?></label>
                    </div>
                    <div class="aio-contact-field-input">
                        <div class="aio-contact-extra-field">
                            <input type="checkbox" id="hide_not_available_agents" name="hide_not_available_agents" value="1" <?php esc_attr_e( ($settings['hide_not_available_agents']) ? 'checked' : '' ) ?>>
                            <label class="form-check-label" for="hide_not_available_agents"><?php _e('Hide NOT Available Agents') ?></label>
                        </div>
                        <div class="aio-h-divider"></div>
                        <div class="aio-contact-extra-field">
                            <input type="checkbox" id="show_dot_status" name="show_dot_status" value="1" <?php esc_attr_e( ($settings['show_dot_status']) ? 'checked' : '' ) ?>>
                            <label class="form-check-label" for="show_dot_status"><?php _e('Show DOT Status in Avatar') ?></label>
                        </div>
                        <div class="aio-h-divider"></div>
                        <div class="aio-contact-extra-field">
                            <input type="checkbox" id="rounded_avatar" name="rounded_avatar" value="1" <?php esc_attr_e( ($settings['rounded_avatar']) ? 'checked' : '' ) ?>>
                            <label class="form-check-label" for="rounded_avatar"><?php _e('Enable Rounded Avatars') ?></label>
                        </div>
                    </div>
                </div>
                <div class="aio-contact-field">
                    <div class="aio-contact-field-label">
                        <label for="background_color"><?php _e('Single Item') ?></label>
                    </div>
                    <div class="aio-contact-field-input">
                        <div class="aio-contact-extra-field">
                            <input type="checkbox" id="single_item" name="single_item" value="1" <?php esc_attr_e( ($settings['single_item']) ? 'checked' : '' ) ?>>
                            <label class="form-check-label" for="single_item"><?php _e('Enable Single Item ONLY') ?></label>
                        </div>
                        <p class="aio-field-description aio-h-italic-text aio-h-dim-text"><?php _e('If this option is enabled then only the first item of the list will be shown with relevant details') ?></p>
                    </div>
                </div>
                <div class="aio-contact-field">
                    <div class="aio-contact-field-label">
                        <label for="background_color"><?php _e('Clear All Data') ?></label>
                    </div>
                    <div class="aio-contact-field-input">
                        <div class="aio-contact-extra-field">
                            <input type="checkbox" id="clear_at_uninstall" name="clear_at_uninstall" value="1" <?php esc_attr_e( ($settings['clear_at_uninstall']) ? 'checked' : '' ) ?>>
                            <label class="form-check-label" for="clear_at_uninstall"><?php _e('Clear All Data at Uninstall') ?></label>
                        </div>
                        <p class="aio-field-description aio-h-italic-text aio-h-dim-text">
                            <?php _e('If this option is enabled, then when you delete this Plugin, all the AIO Contact Data including Items and Settings related to it will be DELETED from your website.') ?>
                        </p>
                    </div>
                </div>
                <div class="aio-contact-field">
                    <?php 
                    settings_fields( $this->plugin_name );
                    do_settings_sections( $this->plugin_name );
                    submit_button(esc_html__('Save Settings', $this->plugin_name), 'primary','settings', TRUE); 
                    ?>
                </div>
            </form>
        </div>
    </div>
</div>
