jQuery(function($) {

	"use strict";

    $('.gbt_18_snap_look_book').each(function() {

		if( $(this).index() == 0 ) {

			var windowHeight = $(window).height();
			var offsetTop = $(this).offset().top;
			var fullHeight = 100-offsetTop/(windowHeight/100);

			if( windowHeight && fullHeight ) {
				$(this).find('.gbt_18_hero_look_book_item').css('min-height', fullHeight+"vh");
				$(this).find('.gbt_18_hero_look_book_item').css('max-height', fullHeight+"vh");
			}
		} else {
            $(this).find('.gbt_18_hero_look_book_item').css('min-height', '90vh');
            $(this).find('.gbt_18_hero_look_book_item').css('max-height', '90vh');
        }
	});

    $('.gbt_18_default_slider').each(function() {

        if( $(window).width() >= 992 ) {

    		if( $(this).index() == 0 ) {

    			var windowHeight = $(window).height();
    			var offsetTop = $(this).offset().top;
    			var fullHeight = 100-offsetTop/(windowHeight/100);

    			if( windowHeight && fullHeight ) {
    				$(this).css('min-height', fullHeight+"vh");
    				$(this).css('max-height', fullHeight+"vh");
    			}
    		} else {
                $(this).css('min-height', '90vh');
                $(this).css('max-height', '90vh');
            }
        }
	});
});
