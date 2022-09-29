jQuery(document).ready(function ($) {
    'use strict';
    wtypc_flex_silder();
    $('.woocommerce-thank-you-page-coupon__code-code').focus(function () {
        $(this).select();
    });
    $('.woocommerce-thank-you-page-coupon__code-copy-code').on('click', function () {
        $(this).parent().parent().find('.woocommerce-thank-you-page-coupon__code-code').select();
        document.execCommand("copy");
        alert(woocommerce_thank_you_page_customizer_params.copied_message)
    });
    sendCouponButton();

    function sendCouponButton() {
        $('.woocommerce-thank-you-page-coupon__code-mail-me').on('click', function () {
            let button = $(this);
            button.unbind().addClass('wtypc-sending-email');
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: woocommerce_thank_you_page_customizer_params.url,
                data: {
                    action: woocommerce_thank_you_page_customizer_params.action,
                    shortcodes: woocommerce_thank_you_page_customizer_params.shortcodes,
                    language_ajax: woocommerce_thank_you_page_customizer_params.language_ajax,
                    coupon_code: button.parent().parent().find('.woocommerce-thank-you-page-coupon__code-code').val(),
                },
                success: function (response) {
                    button.removeClass('wtypc-sending-email');
                    sendCouponButton();
                    if (response.hasOwnProperty('message') && response.message) {
                        alert(response.message);
                    }
                },
                error: function (err) {
                    button.removeClass('wtypc-sending-email');
                    sendCouponButton();
                    console.log(err);
                }
            })
        })
    }
});

function wtypc_flex_silder() {
    jQuery('.woocommerce-thank-you-page-products').map(function () {
        let items = jQuery(this).find('.woocommerce-thank-you-page-products-content-item').length;
        let data = jQuery(this).find('.woocommerce-thank-you-page-products-content').data();
        let itemWidth ,wrap_width = jQuery(this).innerWidth(),
            colums = parseInt(data['wtypc_columns'] || 4);
        if( jQuery(window).width() > 768){
            itemWidth = (wrap_width - 12*colums)/colums;
        }
        jQuery(this).vi_flexslider({
            namespace: "woocommerce-thank-you-page-customizer-",
            selector: '.woocommerce-thank-you-page-products-content .woocommerce-thank-you-page-products-content-item',
            animation: "slide",
            animationLoop: data['slider_loop'] == 1 ? true : false,
            itemWidth: itemWidth || jQuery(this).innerWidth()/2,
            itemMargin: 12,
            controlNav: false,
            maxItems: colums,
            reverse: false,
            slideshow: data['slider_slideshow'] == 1 ? true : false,
            move: data['slider_move'],
            touch: true,
            slideshowSpeed: data['slider_slideshow_speed']
        })
    })
}

