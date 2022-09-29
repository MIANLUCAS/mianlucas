<?php

/**
 * AIO Contact Items Page
 *
 * @link       harshitpeer.com
 * @since      1.0.0
 *
 * @package    Aio_Contact
 * @subpackage Aio_Contact/admin/partials
 */

$items = get_option( $this->plugin_name . '-items' );
//Remove Below 3 Lines in v2.0
if(!is_array($items)) {
    $items = json_decode($items);
}
$cf7s = get_posts(array('post_type' => 'wpcf7_contact_form'));
if($items == null) {
    $items = array();
}
$days = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
?>
<div class="aio-contact-preloader">
    <i class="fas fa-sync-alt fa-spin"></i>
</div>
<div id="aio-contact-items" class="d-none"><?php _e(json_encode($items)) ?></div>
<div class="aio-contact aio-contact-body">
    <div class="row aio-contact-items" id="sortable">
        <?php foreach($items as $item_id => $item) { ?>
        <div class="col-xl-3 col-lg-4 col-md-6" id="aio-contact-item-<?php esc_attr_e($item_id) ?>">
            <div class="aio-contact-item">
                <div class="aio-contact-item-header">
                    <div class="aio-contact-item-text">
                        <div class="aio-contact-item-icon" style="color: <?php esc_attr_e($item->color) ?>"> 
                            <i class="<?php esc_attr_e($item->icon) ?>"></i>
                        </div>
                        <div class="aio-contact-item-title">
                            <?php esc_html_e($item->title) ?>
                        </div>
                    </div>
                    <div class="aio-contact-item-actions">
                        <div class="aio-contact-item-action aio-contact-item-action-edit text-success hvr-bob" data-type="<?php echo (isset($item->agents)) ? 'aio' : (isset($item->form) ? 'cf7' : (isset($item->shortcode) ? 'sc' : 'integration')) ?>" data-id="<?php esc_attr_e($item_id) ?>">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="aio-contact-item-action aio-contact-item-action-delete text-danger hvr-bob" data-id="<?php esc_attr_e($item_id) ?>">
                            <i class="fas fa-trash"></i>
                        </div>
                    </div>
                </div>
                <?php if(isset($item->agents)) { ?>
                <div class="aio-contact-item-footer">
                    <div class="aio-contact-item-agents">
                        <?php if(count($item->agents) > 0) { ?>
                            <?php foreach($item->agents as $agent_id => $agent) { ?>
                                <div class="aio-contact-item-agent" id="aio-contact-item-agent-<?php esc_attr_e($agent_id) ?>" data-item-id="<?php esc_attr_e($item_id) ?>" data-id="<?php esc_attr_e($agent_id) ?>">
                                    <div class="aio-contact-item-agent-avatar">
                                        <img src="<?php echo esc_url($agent->avatar) ?>" width="30px" height="30px" alt="<?php esc_attr_e($agent->name) ?>">
                                    </div>
                                    <div class="aio-contact-item-agent-name">
                                        <?php esc_html_e($agent->name) ?>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>   
                            <div class="aio-contact-item-no-agents aio-h-dim-text">
                                <em><?php _e('No Agents') ?></em>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="aio-contact-item-actions">
                        <div class="aio-contact-item-action aio-contact-agent-add aio-h-spin-hover" data-id="<?php esc_attr_e($item_id) ?>">
                            <div class="aio-contact-item-action-icon">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="aio-contact-item-action-name">
                                <?php _e('Add Agent') ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } else if(isset($item->form)) { ?>
                <div class="aio-contact-item-footer aio-contact-item-cf7">
                    <div class="aio-contact-item-cf7-logo"></div>
                </div>
                <?php } else if(isset($item->shortcode)) { ?>
                <div class="aio-contact-item-footer aio-contact-item-sc">
                    <div class="aio-contact-item-sc-logo"></div>
                </div>
                <?php } else if(isset($item->integration)) { ?>
                <div class="aio-contact-item-footer aio-contact-item-integration">
                    <div class="aio-contact-item-<?php esc_attr_e($item->integration->type) ?>-logo"></div>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
        <div class="col-lg-2 col-md-3">
            <div class="aio-contact-item-add aio-contact-item-add-options aio-h-spin-hover" data-toggle="modal" data-target="#itemOptions">
                <div class="aio-contact-item-add-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="aio-contact-item-add-text">
                    <?php _e('Add Item') ?>
                </div>
            </div>
        </div>
        <?php if(count($items) == 0) { ?>
        <div class="col-lg-3 col-md-4">
            <div class="aio-contact-item-add aio-contact-import-sample-items aio-h-spin-hover">
                <div class="aio-contact-item-add-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="aio-contact-item-add-text">
                    <?php _e('Import Sample Items') ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>


<!-- Item Options Modal --> 
<div class="modal fade" id="itemOptions" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php _e('Select to Add Item') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="aio-contact-item-add aio-contact-item-add-trigger aio-h-spin-hover" data-type="aio">
                            <div class="aio-contact-item-add-icon">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="aio-contact-item-add-text">
                                <?php _e('Add AIO Item') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="aio-contact-item-add aio-contact-item-add-trigger aio-h-spin-hover" data-type="cf7">
                            <div class="aio-contact-item-add-icon">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="aio-contact-item-add-text">
                                <?php _e('Add Contact Form 7') ?>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="aio-contact-item-add aio-contact-item-add-trigger aio-h-spin-hover" data-type="sc">
                            <div class="aio-contact-item-add-icon">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="aio-contact-item-add-text">
                                <?php _e('Add Shortcode') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="aio-contact-item-add aio-contact-item-add-trigger aio-h-spin-hover" data-type="integration">
                            <div class="aio-contact-item-add-icon">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="aio-contact-item-add-text">
                                <?php _e('Add Live Chat') ?>
                            </div>
                        </div>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Item Modal --> 
<div class="modal fade" id="addEditItem" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="item_modal_title"><?php _e('New Item') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="item_title"><?php _e('Title') ?></label>
                    <input type="text" class="form-control" id="item_title" required>
                </div>
                <div class="form-group" id="icon_field">
                    <label for="item_icon"><?php _e('Icon') ?></label>
                    <button class="btn btn-secondary form-control" id="item_icon" name="item_icon" role="iconpicker"></button>
                </div>
                <div class="form-group" id="color_field">
                    <label for="item_color"><?php _e('Color') ?></label><br>
                    <input type="text" class="color-picker" name="item_color" id="item_color">
                </div>
                <div class="form-group aio_contact_custom_fields" id="aio_field">
                    <label for="item_url"><?php _e('URL') ?></label>
                    <input type="url" class="form-control" id="item_url">
                    <small id="url_help" class="form-text text-muted"><?php _e('If you\'re going to add Agents, you can skip this') ?></small>
                </div>
                <div class="form-group aio_contact_custom_fields" id="cf7_field">
                    <label for="item_form"><?php _e('Contact Form 7') ?></label>
                    <select class="form-control" name="item_form" id="item_form">
                        <?php foreach($cf7s as $cf7) { ?> 
                        <option value="<?php esc_attr_e($cf7->ID) ?>"><?php esc_html_e($cf7->post_title) ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group aio_contact_custom_fields" id="sc_field">
                    <label for="item_shortcode"><?php _e('Shortcode') ?></label>
					<textarea name="item_shortcode" id="item_shortcode" class="form-control" cols="30" rows="3"></textarea>
                    <small class="d-block mt-2"><?php _e('Only Shortcodes. Don\'t add any other type of Code') ?></small>
                </div>
                <div class="form-group aio_contact_custom_fields" id="integration_field">
                    <label for="item_integration"><?php _e('Integration') ?></label>
                    <select class="form-control" id="item_integration">
                        <option disabled selected><?php _e('Select Integration Platform') ?></option>
                        <option value="messenger"><?php _e('Messenger') ?></option>
                        <option value="tawk"><?php _e('Tawk.to') ?></option>
                    </select>
                </div>
                <div class="form-group aio_contact_custom_fields aio_contact_integration_fields" id="messenger_field">
                    <label for="messenger_page_id"><?php _e('Page ID') ?></label>
                    <input type="text" class="form-control" id="messenger_page_id">
                    <small class="d-block mt-2"><a href="https://harshitpeer.ticksy.com/article/16324" target="_blank"><?php _e('Click here') ?></a> <?php _e('to know how to setup Facebook Messenger') ?></small>
                </div>
                <div class="form-group aio_contact_custom_fields aio_contact_integration_fields" id="tawk_field">
                    <label for="tawk_widget_id"><?php _e('Widget ID / Property ID') ?></label>
                    <input type="text" class="form-control" id="tawk_widget_id">
                    <small class="d-block mt-2"><a href="https://harshitpeer.ticksy.com/article/16328" target="_blank"><?php _e('Click here') ?></a> <?php _e('to know how to setup Tawk.to Live Chat') ?></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php _e('Close') ?></button>
                <button type="button" class="btn btn-primary" id="item_modal_action"><?php _e('Add Item') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Agents Modal --> 
<div class="modal fade" id="addEditAgent" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agent_modal_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="agent_item_id" id="agent_item_id">
                <div class="form-group">
                    <label for="agent_name"><?php _e('Name') ?></label>
                    <input type="text" class="form-control" id="agent_name" required>
                </div>
                <div class="form-group">
                    <label for="agent_avatar"><?php _e('Avatar') ?></label>
                    <img id="agent_avatar_preview" <?php _e(($is_subscriber) ? 'src="https://aio.thehp.in/avatar.png"' : '') ?>>
                    <input type="hidden" name="agent_avatar_id" id="agent_avatar_id">
                    <button class="btn btn-secondary form-control" <?php _e(($is_subscriber) ? 'disabled' : 'id="agent_avatar"') ?>><?php _e('Upload') ?><?php _e(($is_subscriber) ? ' - Disabled in DEMO' : '') ?></button>
                </div>
                <div class="form-group">
                    <label class="mb-3"><?php _e('Availability') ?></label><br>
                    <?php foreach($days as $day) { ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="availability" type="checkbox" id="avail_<?php _e(strtolower($day)) ?>" value="<?php _e(strtolower($day)) ?>">
                        <label class="form-check-label" for="avail_<?php _e(strtolower($day)) ?>"><?php _e($day) ?></label>
                    </div>
                    <?php } ?>
                    <div class="d-flex mt-2">
                        <div class="mr-2">
                            <label for="avail_start"><small><?php _e('Start Time') ?></small></label><br>
                            <input type="time" id="avail_start" name="avail_start">
                        </div>
                        <div class="mr-2">
                            <label for="avail_end"><small><?php _e('End Time') ?></small></label><br>
                            <input type="time" id="avail_end" name="avail_end">
                        </div>
                        <div class="flex-grow-1">
                            <label for="avail_timezone"><small><?php _e('Timezone') ?></small></label><br>
                            <select name="avail_timezone" id="avail_timezone">
                                <option value="-12"><?php _e('(GMT -12:00)') ?></option>
                                <option value="-11"><?php _e('(GMT -11:00)') ?></option>
                                <option value="-10"><?php _e('(GMT -10:00)') ?></option>
                                <option value="-9.5"><?php _e('(GMT -9:30)') ?></option>
                                <option value="-9"><?php _e('(GMT -9:00)') ?></option>
                                <option value="-8"><?php _e('(GMT -8:00)') ?></option>
                                <option value="-7"><?php _e('(GMT -7:00)') ?></option>
                                <option value="-6"><?php _e('(GMT -6:00)') ?></option>
                                <option value="-5"><?php _e('(GMT -5:00)') ?></option>
                                <option value="-4.5"><?php _e('(GMT -4:30)') ?></option>
                                <option value="-4"><?php _e('(GMT -4:00)') ?></option>
                                <option value="-3.5"><?php _e('(GMT -3:30)') ?></option>
                                <option value="-3"><?php _e('(GMT -3:00)') ?></option>
                                <option value="-2"><?php _e('(GMT -2:00)') ?></option>
                                <option value="-1"><?php _e('(GMT -1:00)') ?></option>
                                <option value="0"><?php _e('(GMT)') ?></option>
                                <option value="1"><?php _e('(GMT +1:00)') ?></option>
                                <option value="2"><?php _e('(GMT +2:00)') ?></option>
                                <option value="3"><?php _e('(GMT +3:00)') ?></option>
                                <option value="3.5"><?php _e('(GMT +3:30)') ?></option>
                                <option value="4"><?php _e('(GMT +4:00)') ?></option>
                                <option value="4.5"><?php _e('(GMT +4:30)') ?></option>
                                <option value="5"><?php _e('(GMT +5:00)') ?></option>
                                <option value="5.5"><?php _e('(GMT +5:30)') ?></option>
                                <option value="5.75"><?php _e('(GMT +5:45)') ?></option>
                                <option value="6"><?php _e('(GMT +6:00)') ?></option>
                                <option value="6.5"><?php _e('(GMT +6:30)') ?></option>
                                <option value="7"><?php _e('(GMT +7:00)') ?></option>
                                <option value="8"><?php _e('(GMT +8:00)') ?></option>
                                <option value="8.75"><?php _e('(GMT +8:45)') ?></option>
                                <option value="9"><?php _e('(GMT +9:00)') ?></option>
                                <option value="9.5"><?php _e('(GMT +9:30)') ?></option>
                                <option value="10"><?php _e('(GMT +10:00)') ?></option>
                                <option value="10.5"><?php _e('(GMT +10:30)') ?></option>
                                <option value="11"><?php _e('(GMT +11:00)') ?></option>
                                <option value="11.5"><?php _e('(GMT +11:30)') ?></option>
                                <option value="12"><?php _e('(GMT +12:00)') ?></option>
                                <option value="12.75"><?php _e('(GMT +12:45)') ?></option>
                                <option value="13"><?php _e('(GMT +13:00)') ?></option>
                                <option value="14"><?php _e('(GMT +14:00)') ?></option>
                            </select>
                        </div>
                    </div>
                </div>     
                <div class="form-group">
                    <label for="agent_url"><?php _e('URL') ?></label>
                    <input type="url" class="form-control" id="agent_url">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php _e('Close') ?></button>
                <button type="button" class="btn btn-danger aio-contact-agent-delete d-none"><?php _e('Delete') ?></button>
                <button type="button" class="btn btn-primary" id="agent_modal_action"></button>
            </div>
        </div>
    </div>
</div>

<input type="button" class="d-none" value="Save" id="aio-contact-save-items">