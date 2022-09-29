<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       harshitpeer.com
 * @since      1.0.0
 *
 * @package    Aio_Contact
 * @subpackage Aio_Contact/public/partials
 */

$integrations = array();
?>

<style><?php echo $aio_custom_css ?></style>
<div class="aio-contact-parent">
    <div class="aio-contact-floating <?php esc_attr_e($aio_contact_floating_classes) ?> <?php echo (esc_attr($settings["disable_animation"]) ? '' : 'animate__animated') ?> animate__fadeInUp">
        <div class="aio-contact">
            <?php if(!$settings['single_item']) { ?>
            <div class="aio-contact-back mb-4 <?php echo (esc_attr($settings["disable_animation"]) ? '' : 'animate__animated') ?> animate__fadeInDown">
                <div class="aio-contact-back-icon mr-2">
                    <i class="fas fa-angle-left"></i>
                </div>
                <div class="aio-contact-back-title">
                    <?php _e('Back') ?>
                </div>
            </div>
            <div class="aio-contact-blocks aio-contact-items">
                <?php foreach($items as $item_id => $item) { ?>
                    <?php if(isset($item->agents)) { ?>
                        <div class="aio-contact-block <?php echo (esc_attr($settings["disable_animation"]) ? '' : 'animate__animated') ?> animate__fadeInUp" data-url="<?php esc_attr_e($item->url) ?>" data-agents='<?php esc_attr_e(json_encode($item->agents)) ?>'>
                    <?php } else if(isset($item->form)) { ?>
                        <div class="aio-contact-block <?php echo (esc_attr($settings["disable_animation"]) ? '' : 'animate__animated') ?> animate__fadeInUp" data-id="<?php esc_attr_e($item_id) ?>" data-form="<?php esc_attr_e($item->form) ?>">
                    <?php } else if(isset($item->shortcode)) { ?>
                        <div class="aio-contact-block <?php echo (esc_attr($settings["disable_animation"]) ? '' : 'animate__animated') ?> animate__fadeInUp" data-id="<?php esc_attr_e($item_id) ?>" data-shortcode="<?php esc_attr_e($item->shortcode) ?>">
                    <?php } else if(isset($item->integration)) { ?>
                        <div class="aio-contact-block <?php echo (esc_attr($settings["disable_animation"]) ? '' : 'animate__animated') ?> animate__fadeInUp" data-id="<?php esc_attr_e($item_id) ?>" data-integration="<?php esc_attr_e(json_encode($item->integration)) ?>">
                    <?php } ?>
                    <?php if(isset($item->agents) || isset($item->form) || isset($item->shortcode) || isset($item->integration)) { ?>
                        <?php if(isset($item->integration)) { ?>
                            <div class="aio-contact-block-icon">
                                <i class="integration-<?php _e($item->integration->type) ?>"></i>
                            </div>
                        <?php } else { ?>
                            <div class="aio-contact-block-icon" style="color: <?php esc_attr_e($item->color) ?>">
                                <i class="<?php esc_attr_e($item->icon) ?>"></i>
                            </div>
                        <?php } ?>
                            <div class="aio-contact-block-details">
                                <div class="aio-contact-block-title">
                                    <?php esc_html_e($item->title) ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <?php } ?>
            <div class="aio-contact-blocks aio-contact-agents"></div>
            <?php foreach($items as $item_id => $item) { ?>
                <?php if(isset($item->form)) { ?>
                    <div class="aio-contact-forms <?php echo (esc_attr($settings["disable_animation"]) ? '' : 'animate__animated') ?> animate__fadeIn" id="aio-contact-form-<?php esc_attr_e($item_id) ?>"><?php echo do_shortcode('[contact-form-7 id="'.$item->form.'"]') ?></div>
                <?php } else if(isset($item->shortcode)) { ?>
                    <div class="aio-contact-shortcodes <?php echo (esc_attr($settings["disable_animation"]) ? '' : 'animate__animated') ?> animate__fadeIn" id="aio-contact-shortcode-<?php esc_attr_e($item_id) ?>"><?php echo do_shortcode($item->shortcode) ?></div>
                <?php } else if(isset($item->integration)) { $integrations[$item_id] = $item; } ?>
            <?php } ?>
        </div>
    </div>
    <div class="aio-contact-trigger <?php esc_attr_e($aio_contact_floating_classes) ?> <?php echo (esc_attr($settings["disable_animation"]) ? '' : 'animate__animated') ?> animate__fadeInRight">
        <?php if($settings['single_item']) { ?>
            <?php if(isset($items[0]->agents)) { ?>
                <div class="aio-contact-trigger-front aio-contact-single-trigger <?php echo (strlen($items[0]->url) > 0 && count($items[0]->agents) == 0) ? 'aio-contact-single-trigger-no-agents' : '' ?>" data-url="<?php esc_attr_e($items[0]->url) ?>" data-agents='<?php esc_attr_e(json_encode($items[0]->agents)) ?>'>
            <?php } else if(isset($items[0]->form)) { ?>
                <div class="aio-contact-trigger-front aio-contact-single-trigger" data-id="0" data-form="<?php esc_attr_e($items[0]->form) ?>">
            <?php } else { ?>
                <div class="aio-contact-trigger-front aio-contact-single-trigger" data-id="0" data-shortcode="<?php esc_attr_e($items[0]->shortcode) ?>">
            <?php } ?>
                <i class="<?php esc_attr_e($items[0]->icon) ?>"></i>
            </div>
        <?php } else { ?> 
            <div class="aio-contact-trigger-front">
                <i class="<?php esc_attr_e( $settings['button_icon'] ) ?>"></i>
            </div>
        <?php } ?>
        <div class="aio-contact-trigger-back">
            <i class="fas fa-times"></i>
        </div>
    </div>
</div>
<?php foreach($integrations as $id => $integration) { ?>
    <div class="aio-contact-integration" id="aio-contact-integration-<?php esc_attr_e($id) ?>">
        <?php if($integration->integration->type === 'messenger') { ?>
            <div id="fb-root"></div><script>window.fbAsyncInit=function(){FB.init({xfbml : true, version : 'v8.0'});}; (function(d, s, id){var js, fjs=d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js=d.createElement(s); js.id=id; js.src='https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js'; fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script> <div class="fb-customerchat" attribution="setup_tool" page_id="<?php esc_attr_e($integration->integration->page_id) ?>"></div>
        <?php } else if($integration->integration->type === 'tawk') { ?> 
            <script type="text/javascript">(function(){var s1=document.createElement("script"),s0=document.querySelector('#aio-contact-integration-<?php esc_attr_e($id) ?>');s1.async=true;s1.src='https://embed.tawk.to/<?php esc_attr_e($integration->integration->widget_id) ?>/default';s1.charset='UTF-8';s1.setAttribute('crossorigin','*');s0.parentNode.insertBefore(s1,s0);})();</script>
        <?php } ?>
    </div>
<?php } ?>