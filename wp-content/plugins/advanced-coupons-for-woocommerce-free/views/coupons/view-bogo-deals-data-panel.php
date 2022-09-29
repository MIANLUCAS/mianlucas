<?php if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly ?>

<div id="<?php echo esc_attr($panel_id); ?>" class="panel woocommerce_options_panel" data-bogo_deals="<?php echo esc_attr(json_encode($bogo_deals)); ?>">

    <div class="bogo-info">
        <h3><?php _e('Buy One Get One (BOGO)', 'advanced-coupons-for-woocommerce-free');?></h3>
        <p><?php _e('BOGO deals let you define a trigger which activates the deal and what should be applied in the deal. There are three kinds of triggers and three kinds of apply types.', 'advanced-coupons-for-woocommerce-free');?></p>
        <p><?php _e('Trigger & Apply Types:', 'advanced-coupons-for-woocommerce-free');?></p>
        <ul>
            <?php foreach ($trigger_apply_type_descs as $key => $desc): ?>
                <li class="<?php echo $key ?>"><?php echo $desc; ?></li>
            <?php endforeach;?>
        </ul>
    </div>

    <div class="bogo-conditions-wrap">

        <div class="bogo-type-selector">
            <label for="bogo-condition-type"><?php _e('Select Trigger Type:', 'advanced-coupons-for-woocommerce-free');?></label>
            <select id="bogo-condition-type" data-block="conditions">
                <?php foreach ($trigger_apply_type_options as $option => $label): ?>
                    <option value="<?php echo $option; ?>" <?php selected($cond_type, $option);?>>
                        <?php echo $label; ?>
                    </option>
                <?php endforeach;?>
            </select>
            <p class="instruction">
                <?php _e('When all of the following products and quantities are matched, trigger the deal.', 'advanced-coupons-for-woocommerce-free');?>
            </p>
        </div>

        <div class="bogo-conditions-block bogo-block">
        </div>

    </div>

    <div class="bogo-product-deals-wrap">

        <div class="bogo-type-selector">
            <label for="bogo-deals-type"><?php _e('Select Apply Type:', 'advanced-coupons-for-woocommerce-free');?></label>
            <select id="bogo-deals-type" data-block="deals">
                <?php foreach ($trigger_apply_type_options as $option => $label): ?>
                    <option value="<?php echo $option; ?>" <?php selected($deals_type, $option);?>>
                        <?php echo $label; ?>
                    </option>
                <?php endforeach;?>
            </select>
            <p class="instruction">
                <?php _e('Once the deal is triggered, apply the following products to the cart.', 'advanced-coupons-for-woocommerce-free');?>
                <span class="multiple-items-desc">
                    <?php _e('If multiple items are eligible, the cheapest will be given.', 'advanced-coupons-for-woocommerce-free');?>
                </span>
            </p>
        </div>

        <div class="bogo-product-deals-block bogo-block">
        </div>
    </div>

    <div class="bogo-type-block additional-settings-block">
        <h2><?php _e('Additional Settings', 'advanced-coupons-for-woocommerce-free');?></h2>

        <?php do_action('acfw_bogo_before_additional_settings', $bogo_deals, $coupon);?>

        <div class="bogo-type-form bogo-settings-field">
            <label><?php _e('How should the BOGO deal be applied?', 'advanced-coupons-for-woocommerce-free');?></label>
            <div class="radio-group-wrap">
                <label>
                    <input type="radio" name="bogo_type" value="once" <?php checked($type, 'once');?>>
                    <span><?php _e('Only once', 'advanced-coupons-for-woocommerce-free');?></span>
                    <span class="woocommerce-help-tip" data-tip="<?php esc_attr_e('Only apply the coupon once when one of the conditions is met (even multiple times)', 'advanced-coupons-for-woocommerce-free');?>"></span>
                </label>
                <label>
                    <input type="radio" name="bogo_type" value="repeat" <?php checked($type, 'repeat');?>>
                    <span><?php _e('Repeatedly', 'advanced-coupons-for-woocommerce-free');?></span>
                    <span class="woocommerce-help-tip" data-tip="<?php esc_attr_e('Everytime the condition is met, apply the coupon repeatedly.', 'advanced-coupons-for-woocommerce-free');?>"></span>
                </label>
            </div>
        </div>

        <div class="notice-option">
            <div class="bogo-settings-field">
                <label><?php _e('Notice to show customers when they have triggered the BOGO deal but the "Apply products" are not present in the cart:', 'advanced-coupons-for-woocommerce-free');?></label>
                <textarea class="text-input" name="acfw_bogo_notice_message_text" placeholder="<?php echo $global_notice_message; ?>"><?php echo $notice_message; ?></textarea>
                <span class="woocommerce-help-tip" data-tip="<?php esc_attr_e('Custom variables available: {acfw_bogo_remaining_deals_quantity} to display the count of product deals that can be added to the cart, and {acfw_bogo_coupon_code} for displaying the coupon code that offered the deal.', 'advanced-coupons-for-woocommerce-free');?>"></span>
            </div>
            <div class="bogo-settings-field">
                <label><?php _e('Button Text:', 'advanced-coupons-for-woocommerce-free');?></label>
                <input class="text-input" type="text" name="acfw_bogo_notice_button_text" placeholder="<?php echo $global_notice_btn_text; ?>" value="<?php echo $notice_btn_text; ?>">
            </div>
            <div class="bogo-settings-field">
                <label><?php _e('Button URL:', 'advanced-coupons-for-woocommerce-free');?></label>
                <input class="text-input" type="url" name="acfw_bogo_notice_button_url" placeholder="<?php echo $global_notice_btn_url; ?>" value="<?php echo $notice_btn_url; ?>">
            </div>
            <div class="bogo-settings-field">
                <label><?php _e('Notice Type:', 'advanced-coupons-for-woocommerce-free');?></label>
                <select name="acfw_bogo_notice_type">
                    <option value="global" <?php selected('global', $notice_type);?>><?php echo sprintf(__('Global setting (%s)', 'advanced-coupons-for-woocommerce-free'), $globa_notice_type_label); ?></option>
                    <?php foreach ($notice_types as $key => $label): ?>
                        <option value="<?php echo $key; ?>" <?php selected($key, $notice_type);?>><?php echo $label; ?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>

        <?php do_action('acfw_bogo_after_additional_settings', $bogo_deals, $coupon);?>

    </div>

    <div class="bogo-actions-block">
        <button id="save-bogo-deals" class="button-primary" type="button" disabled><?php _e('Save BOGO Deals', 'advanced-coupons-for-woocommerce-free');?></button>
        <button id="clear-bogo-deals" class="button" type="button"
            data-prompt="<?php esc_attr_e('Are you sure you want to do this?', 'advanced-coupons-for-woocommerce-free');?>"
            data-nonce="<?php echo wp_create_nonce('acfw_clear_bogo_deals'); ?>"
            <?php echo empty($bogo_deals) ? 'disabled' : ''; ?>>
            <?php _e('Clear BOGO Deals', 'advanced-coupons-for-woocommerce-free');?>
        </button>
    </div>

    <div class="acfw-overlay" style="background-image:url(<?php echo esc_attr($spinner_img); ?>)"></div>

</div>

<script type="text/javascript">
jQuery(document).ready(function($) {

    $('#acfw_bogo_deals').on( 'mouseenter' , '.notice-option' , function() {
        $('#tiptip_content').css( 'max-width' , '250px' );
    } );

    $('#acfw_bogo_deals').on( 'mouseleave' , '.notice-option' , function() {
        $('#tiptip_content').css( 'max-width' , '150px' );
    } );
});
</script>