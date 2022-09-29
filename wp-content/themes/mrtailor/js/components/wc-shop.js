(function($) {

	"use strict";

    var ua = window.navigator.userAgent;
	var msie = ua.indexOf("MSIE ");

    function mrtailor_catalog_mode() {
		if (mrtailor_scripts_vars_array.catalogMode == 1) {
			$("form.cart div.quantity").empty();
			$("form.cart button.single_add_to_cart_button").remove();
		}
	}

    function replace_img_source(selector) {
		var data_src = $(selector).attr('data-src');
		$(selector).one('load', function() {
		}).each(function() {
			$(selector).attr('src', data_src);
			$(selector).css("opacity", "1");
		});
	}

	mrtailor_catalog_mode();

    //product animation (thanks Sam Sehnert)
	$('ul.products').addClass('effect-' + mrtailor_scripts_vars_array.products_animation);

	$.fn.visible = function(partial) {

		var $t            = $(this),
		$w            = $(window),
		viewTop       = $w.scrollTop(),
		viewBottom    = viewTop + $w.height(),
		_top          = $t.offset().top,
		_bottom       = _top + $t.height(),
		compareTop    = partial === true ? _bottom : _top,
		compareBottom = partial === true ? _top : _bottom;

		return ((compareBottom <= viewBottom) && (compareTop >= viewTop));

	};

	$("ul.products li").each(function(i, el) {
		if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
			$(el).addClass("shown");
		}
		else {
			if ($(el).visible(true)) {
				$(el).addClass("shown");
			}
		}
	});

    $('ul.products li img').each(function(){
		replace_img_source(this);
	});

	setTimeout(function() {
		$(".product_thumbnail.with_second_image .product_thumbnail_background").css("background-size", "cover");
		$(".product_thumbnail.with_second_image").addClass("second_image_loaded");
	}, 300);

    $(window).on( 'scroll', function() {
        //animate products
		if ($(window).innerWidth() > 640 ) {
			$(".products li").each(function(i, el) {
				if ($(el).visible(true)) {
					$(el).addClass("animate");
				}
			});
		}
    });

    $(document).ajaxComplete(function(event, request, settings) {
		$(".products li").addClass("animate");
		$(".product_thumbnail.with_second_image .product_thumbnail_background").css("background-size", "cover");
		$(".product_thumbnail.with_second_image").addClass("second_image_loaded");
	});

	var product_categories = $('.product-categories ul.products');
	if( product_categories.hasClass('columns-4') || product_categories.hasClass('columns-5') || product_categories.hasClass('columns-6') ) {
		if( product_categories.find('li.product-category').length === 3 ) {
			product_categories.removeClass('columns-4').removeClass('columns-5').removeClass('columns-6');
			product_categories.addClass('columns-3');
		}
	}
	if( product_categories.hasClass('columns-3') || product_categories.hasClass('columns-4') || product_categories.hasClass('columns-5') || product_categories.hasClass('columns-6') ) {
		if( product_categories.find('li.product-category').length === 2 ) {
			product_categories.removeClass('columns-3').removeClass('columns-4').removeClass('columns-5').removeClass('columns-6');
			product_categories.addClass('columns-2');
		}
	}

})(jQuery);
