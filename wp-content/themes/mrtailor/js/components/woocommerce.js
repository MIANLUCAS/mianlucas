(function($) {

	"use strict";

    //wishlist
	$('.add_to_wishlist').on('click',function(){
		$(this).parents('.yith-wcwl-add-button').addClass('show_overlay');
	});

	function handleSelect() {
		if ($(window).innerWidth() > 1024 ) {

			$(".orderby, .big-select, select.topbar-language-switcher, select.wcml_currency_switcher").select2({
				minimumResultsForSearch: Infinity
			});
		}
	}

	handleSelect();

    $('.variations').on("click", ".reset_variations", function(){
		$('.big-select').select2("val", "");
	});

    //category parallax
	function parallax_engine(cat_parallax_pos) {
		if ($(window).innerWidth() > 1200 ) {
			$(".category_header").css('background-position', 'center '+parseInt(-200+cat_parallax_pos/1.5)+'px'); // this 200 value can be found in styles.css also
			$(".entry-header").css('background-position', 'center '+parseInt(-200+cat_parallax_pos/1.5)+'px'); // this 200 value can be found in styles.css also
		} else {
			$(".category_header").css('background-position','center center');
			$(".entry-header").css('background-position','center center');
		}
	}

	parallax_engine($(this).scrollTop());

    $(window).on( 'resize', function(){

		//category parallax
		parallax_engine($(this).scrollTop());
    });

    $(window).on( 'scroll', function() {
        //category parallax
		parallax_engine($(this).scrollTop());

        //mark this selector as visible
		$("#site-footer").each(function(i, el) {
			if ($(el).visible(true)) {
				$(el).addClass("on_screen");
			} else {
				$(el).removeClass("on_screen");
			}
		});
    });

    if ( ('form#register').length > 0 ) {
		var hash = window.location.hash;
		if (hash)
		{
			$('.account-tab-link').removeClass('current');
			$('a[href="'+hash+'"]').addClass('current');

			hash = hash.substring(1);
			$('.account-forms > form').hide();
			$('form#'+hash).show();
		}
	}

    // remove character '-' from recently viewed widget
	$(".recently_viewed_in_single ul li").contents().filter(function () {
		return this.nodeType === 3; // Text nodes only
	}).remove();

	$('a.add_to_wishlist').removeClass('button');

})(jQuery);
