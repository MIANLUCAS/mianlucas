(function($) {

	"use strict";

	// adjust dropdowns' position to avoid offscreen display
	function adjust_dropdown_position() {
		setTimeout( function() {
			if( $(window).width() >= 1024 ) {
			    $('.site-header .main-navigation > ul#menu-main-navigation > li.menu-item-has-children').each( function() {

					var submenuWidth = $(this).find('> .sub-menu').outerWidth();

					var submenuOffset = $(this).offset().left;
					var totalSubMenuWidth = submenuWidth + submenuOffset;

			        if ( ( totalSubMenuWidth - $(window).width() ) > 0 ) {
						$(this).children('ul').css('margin-left', -( totalSubMenuWidth - $(window).width() - 15 ) );
					}
			    });
			} else {
				$('.site-header .main-navigation > ul > li.menu-item-has-children').each( function() {
					$(this).children('ul').css('margin-left', '' );
				});
			}
		}, 300);
	}

	adjust_dropdown_position();
	$(window).on( 'resize', function() {
		$('.main-navigation > ul > li.menu-item-megamenu > .sub-menu').css( 'max-width', $(window).width() );
		adjust_dropdown_position();
	});

	$(".site-tools").data("top", $(".site-tools").offset().top); // set original position on load

    //Language Switcher
	$('.topbar-language-switcher').on( 'change', function(){
		window.location = $(this).val();
	});

	$('body:not(.rtl) .site-header.header-default nav.main-navigation').css('margin-right', $('.site-header .site-tools').width() + 20 + 'px' );
	$('body:not(.rtl) .site-header-sticky-inner nav.main-navigation').css('margin-right', $('.site-header-sticky-inner .site-tools').width() + 20 + 'px' );

	$('body.rtl .site-header.header-default nav.main-navigation').css('margin-left', $('.site-header .site-tools').width() + 20 + 'px' );
	$('body.rtl .site-header-sticky-inner nav.main-navigation').css('margin-left', $('.site-header-sticky-inner .site-tools').width() + 20 + 'px' );

    //mobile menu
	$(".mobile-navigation .menu-item-has-children").append('<div class="more"></div>');

	$(".mobile-navigation").on("click", ".more", function(e) {
		e.stopPropagation();
		$(this).parent().children(".sub-menu").toggleClass("open");
	});

	// sticky header
	if( mrtailor_scripts_vars_array.stickyHeader ) {

		var headerHeight = $('.top-headers-wrapper').outerHeight();

	    $(window).on( 'scroll', function() {
			var that = $('.site-header-sticky');

			if ( $(this).scrollTop() > headerHeight && !that.hasClass('sticky') ) {
				$('#page:not(.transparent_header)').css('padding-top', headerHeight);
				that.addClass('sticky');
			} else if ( $(this).scrollTop() <= headerHeight ) {
				that.removeClass('sticky');
				$('#page:not(.transparent_header)').css('padding-top', '');
			}
	    });
	}

	if( $('.woocommerce-store-notice').length > 0 && $('.top-headers-wrapper').length > 0 ) {
		$('.woocommerce-store-notice').prependTo( $('.top-headers-wrapper') );
	}

	if( $('.transparent_header').length > 0 ) {
		$('.transparent_header .content-area').css( 'padding-top', $('#site-top-bar').outerHeight() + 'px' );
		var store_notice_display = 'none';
		setTimeout( function() {
			store_notice_display = $('.woocommerce-store-notice').css('display');
			if( store_notice_display == 'block' ) {
				$('.transparent_header .content-area').css( 'padding-top', $('.woocommerce-store-notice').outerHeight() + $('#site-top-bar').outerHeight() + 'px' );
				$('.woocommerce-store-notice__dismiss-link').on( 'click', function() {
					$('.transparent_header .content-area').css( 'padding-top', $('#site-top-bar').outerHeight() + 'px' );
				});
			}
		},10);
	}

	// menu dropdown apply scrollbar when longer than the screen
	if( $('.top-headers-wrapper .site-header .main-navigation > ul > li > .sub-menu').length ) {
		var menu_dropdown_offset = $('.top-headers-wrapper .site-header .main-navigation > ul > li > .sub-menu').offset().top;
		var menu_height = $(window).height() - menu_dropdown_offset;
		$('.top-headers-wrapper .site-header .main-navigation > ul > li > .sub-menu').each( function () {
			if( $(this).outerHeight() > menu_height ) {
				$(this).css( {'max-height': menu_height - 100, 'overflow-y': 'auto' });
			}
		});
	}

})(jQuery);
