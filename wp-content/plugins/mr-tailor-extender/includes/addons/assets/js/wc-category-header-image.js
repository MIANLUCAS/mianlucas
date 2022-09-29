jQuery(function($) {

	"use strict";

	setTimeout(function(){
		if ($(window).outerWidth() > 1024) {
			$(window).stellar({
				horizontalScrolling: false,
			});
		}
	},500);
});
