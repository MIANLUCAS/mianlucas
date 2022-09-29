(function($) {

	"use strict";

    $('.trigger-footer-widget-icon').on('click', function(){

		var trigger = $(this).parent();

		trigger.fadeOut('1000',function(){
			trigger.remove();
			$('.site-footer-widget-area').fadeIn();
		});
	});

})(jQuery);
