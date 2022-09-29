(function($) {

	"use strict";

	$('.mt_ext_products_slider ul.products li.product').addClass('swiper-slide');

	$('.mt_ext_products_slider').each(function() {

		var columns = 4;
		if( $(this).attr( 'data-columns' ) ) {
			columns = $(this).attr( 'data-columns' );
		}

		var medium_slides = 3;
		if( columns < 3 ) {
			medium_slides = columns;
		}

		var data_id = $(this).find('.swiper-container').attr('data-id');

		var mySwiper = new Swiper( '.swiper-' + data_id, {
			slidesPerView: columns,
			loop: false,
			spaceBetween: 30,
			breakpoints: {
				0: {
					slidesPerView: 2,
				},
				640: {
					slidesPerView: medium_slides,
				},
				1024: {
					slidesPerView: columns,
				}
			},
			pagination: {
			    el: '.swiper-' + data_id + ' .swiper-pagination',
			    type: 'bullets',
			    clickable: true
			},
		});

	});

})(jQuery);
