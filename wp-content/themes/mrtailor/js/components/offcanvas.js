(function($) {

	"use strict";

	// open offcanvas right
	if( mrtailor_scripts_vars_array.open_minicart ) {
		$("ul.products li.product.product-type-simple a.add_to_cart_button, .wc-block-grid ul.wc-block-grid__products li.wc-block-grid__product a.ajax_add_to_cart").on('click',function(e) {
			$(".st-container").addClass("st-menu-open");
			$(".st-menu.slide-from-right").addClass("open");
		});
	}

	$(".shopping-bag-button").on('click',function(e) {
		$(".st-container").addClass("st-menu-open");
		$(".st-menu.slide-from-right").addClass("open");
	});

	// open offcanvas left
	$(".mobile-menu-button").on('click', function() {
		$(".st-container").addClass("st-menu-open");
		$(".st-menu.slide-from-left").addClass("open");
		$(".st-menu.slide-from-left #filters-offcanvas").hide();
	});

	$("#button_offcanvas_sidebar_left").on('click', function() {
		$(".st-container").addClass("st-menu-open");
		$(".st-menu.slide-from-left").addClass("open");
		$(".st-menu.slide-from-left #mobiles-menu-offcanvas").hide();
	});

	// close offcanvas
	$("#st-container .st-pusher-after").on( 'click', function(e) {
		$(".st-container").removeClass("st-menu-open");
		$(".st-menu").removeClass("open");
		$('.site-search').removeClass("open");
		setTimeout( function() {
			$(".st-menu.slide-from-left #filters-offcanvas").show();
			$(".st-menu.slide-from-left #mobiles-menu-offcanvas").show();
		}, 600);
	});

	// open header search
	$(".site-tools ul li.search-button a").on('click', function() {
		$(".st-container").addClass("st-menu-open");
		$('.site-search').addClass("open");
		setTimeout( function() {
			$('.site-search .widget form .search-field').focus();
		}, 50);
	});

})(jQuery);
