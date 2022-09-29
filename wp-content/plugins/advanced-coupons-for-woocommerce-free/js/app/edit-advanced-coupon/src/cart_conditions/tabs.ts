declare var jQuery: any;
declare var acfw_edit_coupon: any;

const $: any = jQuery;
const { cart_condition_fields } = acfw_edit_coupon;

export function navigate_tabs() : void {
    
    const $tab: JQuery = $(this),
        $module_block  = $tab.closest( ".woocommerce" ).find( "#acfw_cart_conditions" ),
        tab: string    = $tab.data( "tab" );
    
    let panel: string = '';

    switch (tab) {

        case "settings" :
            panel = ".additional-settings";
            break;
    
        case "rules" :
        default :
            panel = ".condition-data-wrap";
            break;
    }

    $module_block.find( ".panel" ).hide();
    $module_block.find( panel ).show();
    set_active_tab( $tab );
}

function set_active_tab( $tab : JQuery ) : void {

    $( ".acfw-cart-condition-tabs li" ).removeClass( "active" );
    $tab.closest( "li" ).addClass( "active" );
}