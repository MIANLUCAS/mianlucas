declare var jQuery: any;
declare var acfw_edit_coupon: any;
declare var vex: any;

const $: any = jQuery;
let exludeCouponShown = false;

/**
 * Add upsell events script.
 *
 * @since 1.0.0
 */
export default function upsell_events() {

    $( "#usage_limit_coupon_data" ).on( "change" , "#reset_usage_limit_period" , upsell_advance_usage_limits );
    $( "#usage_restriction_coupon_data .exclude_coupon_ids_field" ).on( "click change focus" , "input,select" , upsell_exclude_coupons_restriction );
    $( "#acfw-auto-apply-coupon" ).on( "change" , "#acfw_auto_apply_coupon_field" , upsell_auto_apply );
}

/**
 * Usage limits upsell vex dialog.
 * 
 * @since 1.1
 */
function upsell_advance_usage_limits() {

    const { usage_limits } = acfw_edit_coupon.upsell;

    vex.dialog.alert( { unsafeMessage : `<div class="upsell-alert usage-limits">${ usage_limits }</div>` } );
    $(this).val( 'none' ); 
}

/**
 * Usage restriction for exclude coupons upsell vex dialog.
 * 
 * @since 1.1
 */
function upsell_exclude_coupons_restriction() {

    // prevent duplicate dialogs showing up.
    if ( exludeCouponShown ) return;
    exludeCouponShown = true;

    const { usage_restriction } = acfw_edit_coupon.upsell;

    vex.dialog.alert({
        unsafeMessage : `<div class="upsell-alert exclude-coupon">${ usage_restriction }</div>`,
        afterClose: () => exludeCouponShown = false
    });
    $(this).val( '' );
}

/**
 * Auto apply upsell vex dialog.
 * 
 * @since 1.1
 */
function upsell_auto_apply() {

    const { auto_apply } = acfw_edit_coupon.upsell;

    vex.dialog.alert( { unsafeMessage : `<div class="upsell-alert auto-apply">${ auto_apply }</div>` } );
    $(this).prop( 'checked' , false ); 
}