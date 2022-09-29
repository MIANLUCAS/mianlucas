(function($) {

	"use strict";

	// Default Gallery - Scroll thumbnails
	$(document).on('click touchend', '.woocommerce.single-product ol.flex-control-thumbs li img', function() {
		if ($(window).width() >= 1025) {

			var product_thumbnails 				= $('ol.flex-control-thumbs');
			var product_thumbnails_cells 		= product_thumbnails.find('li');
			var product_thumbnails_height 		= product_thumbnails.height();
			var product_thumbnails_cells_height	= product_thumbnails_cells.outerHeight();
			var product_images					= $('.woocommerce-product-gallery__wrapper');
			var index 							= $('.woocommerce-product-gallery__wrapper .woocommerce-product-gallery__image.flex-active-slide').index();

			var scrollY = (index * product_thumbnails_cells_height) - ( (product_thumbnails_height - product_thumbnails_cells_height) / 2) - 10;

			product_thumbnails.animate({
				scrollTop: scrollY
			}, 300);
		}
	});

})(jQuery);
