(function($) {

	"use strict";

	$('.from-the-blog-wrapper.swiper-container').each(function() {

		var data_id = $(this).attr('data-id');

		var mySwiper = new Swiper( '.swiper-' + data_id, {
			slidesPerView: 3,
			loop: false,
			spaceBetween: 30,
			breakpoints: {
				0: {
					slidesPerView: 2,
				},
				640: {
					slidesPerView: 3,
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
