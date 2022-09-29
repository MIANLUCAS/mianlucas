(function($) {

	"use strict";

    //blog isotope - adjust wrapper width, return blog_grid
	function blogIsotopeWrapper () {

		if ( $(window).innerWidth() > 1024 ) {
			$blog_grid = 3;
		} else if ( $(window).innerWidth() <= 640 ) {
			$blog_grid = 1;
		} else {
			$blog_grid = 2;
		}

		$blog_wrapper_width = $('.blog-isotop-container').width();

		if ( $blog_wrapper_width % $blog_grid > 0 ) {
			$blog_wrapper_width = $blog_wrapper_width + ( $blog_grid - $blog_wrapper_width%$blog_grid);
		};

		$('.blog-isotope').css('width',$blog_wrapper_width);

		return $blog_grid;
	} // end blogIsotopeWrapper

	//blog isotope
	if ( $('.blog-isotop-container').length ) {

		var $blog_wrapper_inner,
		$blog_wrapper_width,
		$blog_grid,
		$filterValue;

		$filterValue = $('.filters-group .is-checked').attr('data-filter');

		$blog_grid =  blogIsotopeWrapper();
		blogIsotopeWrapper();

		var afterBlogIsotope = function(){
			setTimeout(function(){
				//$('.preloader_isotope').remove();
				$(".blog-post").removeClass('hidden');
				$(".blog-isotope").addClass('isotope-ready');
			},200);
		}

		var blogIsotope=function(){
			var imgLoad = imagesLoaded($('.blog-isotope'));

			imgLoad.on('done',function(){

				$blog_wrapper_inner = $('.blog-isotope').isotope({
					itemSelector: '.blog-post',
					masonry: { columnWidth: '.grid-sizer' }
				});

				afterBlogIsotope()

			})

			imgLoad.on('fail',function(){

				$blog_wrapper_inner = $('.blog-isotope').isotope({
					itemSelector: '.blog-post',
					masonry: { columnWidth: '.grid-sizer' }
				});

				afterBlogIsotope()
			})

		}

		blogIsotope();

		// filter items on button click
		$('.filters-group').on( 'click', 'filter-item', function() {

			$filterValue = $(this).attr('data-filter');
			$blog_wrapper_inner.isotope({ filter: $filterValue });

		});
	}//endif blog isotope

    $(window).on( 'resize', function(){
        //blog isotope
		if ( $('.blog-isotop-container').length ) {

			var $blog_grid_on_resize;

			blogIsotopeWrapper()
			$blog_grid_on_resize =  blogIsotopeWrapper();

			if ( $blog_grid != $blog_grid_on_resize ) {

				$('.filters-group .filter-item').each(function(){
					if ( $(this).attr('data-filter') == $filterValue ){
						$(this).trigger('click');
					}
				})

				$blog_grid = $blog_grid_on_resize;

				resizeIsotopeEnd();

			}
		}
    });

	// sticky posts slider
	if( $('.sticky-posts-container.swiper-container .swiper-slide').length > 1 ) {
		var stickySwiper = new Swiper ( '.sticky-posts-container.swiper-container', {
			slidesPerView: 1,
			loop: true,
			speed: 800,
			effect: 'fade',
			fadeEffect: {
				crossFade: true
			},
			pagination: {
				el: '.sticky-posts-container.swiper-container .sticky-pagination',
				type: 'bullets',
				clickable: true
			},
		});
	}

})(jQuery);
