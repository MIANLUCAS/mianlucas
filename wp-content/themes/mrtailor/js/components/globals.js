(function($) {

	"use strict";

    $(document).foundation();

    //gallery caption
	$('.gallery-item').each(function(){

		var that = $(this);

		if ( that.find('.gallery-caption').length > 0 ) {
			that.append('<span class="gallery-caption-trigger">i</span>')
		}
	})

	$('.gallery-caption-trigger').on('mouseenter',function(){
		$(this).siblings('.gallery-caption').addClass('show');
	});

	$('.gallery-caption-trigger').on('mouseleave',function(){
		$(this).siblings('.gallery-caption').removeClass('show');
	});

    $(window).on( 'resize', function(){
		//do something on end resize
		var window_resizeTo = this.resizeTO;
		function resizeIsotopeEnd() {
			if(window_resizeTo) clearTimeout(window_resizeTo);
			window_resizeTo = setTimeout(function() {
				$(this).trigger('onEndResizingIsotope');
			}, 100);
		}
	});

    //do something, window hasn't changed size in 100ms
	$(window).on( 'bind', 'onEndResizingIsotope', function() {
		$('.filters-group .filter-item').each(function(){
			if ( $(this).attr('data-filter') == $filterValue ){
				$(this).trigger('click');
			}
		})
	});

})(jQuery);
