jQuery(function($) {

	"use strict";

	$('.trigger-share-list').on('click',function(){

		var share_list_height = $('.box-share-list-inner').outerHeight();

		$('.box-share-container').toggleClass('open');

		$("body").on('click',function(e) {
			if ( $('.box-share-container').hasClass('open') ) {

				if ( $(e.target).attr('class') == 'box-share-list-inner' ) {
					return;
				} else {
					$('.box-share-container').removeClass('open');
					$('body').off('click');
				}
			}
		});

		return false;
	})
});
