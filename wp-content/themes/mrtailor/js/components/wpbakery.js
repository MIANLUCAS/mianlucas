(function($) {

	"use strict";

    var window_width = $(window).innerWidth();

    //columns height adjustment
	function columns_height_adjustment() {

		$('.adjust_cols_height').each(function(){

			var column_min_height = 0;

			var that = $(this);

			that.imagesLoaded('always',function(){

				that.find('.vc_column_container, .vc_vc_column').first().siblings().addBack().css('min-height',0).each(function(){
					if ( $(this).outerHeight(true) > column_min_height ) {
						column_min_height = $(this).outerHeight(true);
					}
				})

				that.addClass('height_adjusted')
				.find('.vc_column_container, .vc_vc_column').first().siblings().addBack().css('min-height',column_min_height);

			});
		});
	};

	if ( $('.vc_row').hasClass('adjust_cols_height') )  {
		if ( window_width > 640 ) {
			setTimeout(function(){
				columns_height_adjustment();
			},1)
		} else {
			$('.adjust_cols_height').addClass('height_adjusted');
		}
	}

    //scroll top tour section on next,prev slides
	$('.wpb_next_slide,.wpb_prev_slide').on('click',function(){

		var wpb_tour_top = $('.wpb_tour.wpb_content_element').offset().top;
		var window_width = $(window).width();

		if ( window_width > 1024 ) {
			$("html, body").animate(
				{ scrollTop: wpb_tour_top - 200 }
			);
		}else if ( window_width < 640 )  {
			$("html, body").animate(
				{ scrollTop: wpb_tour_top - 50 }
			);
		} else {
			$("html, body").animate(
				{ scrollTop: wpb_tour_top - 100 }
			);
		}
	});

    $(window).on( 'load', function(){

		// visible products on vc tabs
		$(".wpb_tour_tabs_wrapper").find(".products li").addClass("animate");

		$('.ui-tabs-anchor').on('click', function(){
			$(this).parents(".wpb_tour_tabs_wrapper").find(".products li").addClass("animate");
		});

		// visible products on vc tour
		$('.wpb_prev_slide a, .wpb_next_slide a, .wpb_tabs_nav a').on('click', function(){
			$(this).parents('.wpb_tour_tabs_wrapper').find(".products li").addClass("animate");
		});
	});

    $(window).on( 'resize', function(){

		//columns height adjustment
		if ( $('.vc_row').hasClass('adjust_cols_height') )  {
			if ( $(window).width() > 640 ) {
				columns_height_adjustment();
			} else {
				$('.adjust_cols_height').find('.vc_column_container').css('min-height',300);
			}
		}
	});
})(jQuery);
