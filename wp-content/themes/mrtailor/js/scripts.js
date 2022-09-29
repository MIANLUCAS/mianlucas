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

(function($) {

	"use strict";

	/*
	**	Yith wishlist counter
	*/
	function getCookie(name) {
		var dc = document.cookie;
		var prefix = name + "=";
		var begin = dc.indexOf("; " + prefix);
		if (begin == -1) {
			begin = dc.indexOf(prefix);
			if (begin != 0) return null;
		}
		else
		{
			begin += 2;
			var end = document.cookie.indexOf(";", begin);
			if (end == -1) {
				end = dc.length;
			}
		}

		return decodeURI(dc.substring(begin + prefix.length, end));
	}

	function getbowtied_update_wishlist_count(count) {
		if ( ( typeof count === "number" && isFinite(count) && Math.floor(count) === count ) && count >=0 ) {
			$('.wishlist_items_number').html(count);
		}
	}

	if ($('.wishlist_items_number').length ) {

		var wishlistCounter = 0;

		/*
		**	Check for Yith cookie
		*/
		var wlCookie = getCookie("yith_wcwl_products");
		if ( wlCookie != null ) {
			// wlCookie = wlCookie.slice(0, wlCookie.indexOf(']') + 1);
			wlCookie = wlCookie.split('%3A').join(':');
			wlCookie = wlCookie.split('%2C').join(',');
			var products = JSON.parse(wlCookie);
			wishlistCounter =  Object.keys(products).length;
		} else 	{
			wishlistCounter = Number($('.wishlist_items_number').html());
		}

		/*
		**	Increment counter on add
		*/
		$('body').on( 'added_to_wishlist' , function(){
			wishlistCounter++;
			getbowtied_update_wishlist_count(wishlistCounter);
		});

		/*
		**	Decrement counter on remove
		*/
		$('body').on( 'removed_from_wishlist' , function(){
			wishlistCounter--;
			getbowtied_update_wishlist_count(wishlistCounter);
		});

		getbowtied_update_wishlist_count(wishlistCounter);
	}

})(jQuery);

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

(function($) {

	"use strict";

	// open offcanvas right
	if( mrtailor_scripts_vars_array.open_minicart ) {
		$("ul.products li.product.product-type-simple a.add_to_cart_button, .wc-block-grid ul.wc-block-grid__products li.wc-block-grid__product a.ajax_add_to_cart").on('click',function(e) {
			$(".st-container").addClass("st-menu-open");
			$(".st-menu.slide-from-right").addClass("open");
		});
	}

	$(".shopping-bag-button").on('click',function(e) {
		$(".st-container").addClass("st-menu-open");
		$(".st-menu.slide-from-right").addClass("open");
	});

	// open offcanvas left
	$(".mobile-menu-button").on('click', function() {
		$(".st-container").addClass("st-menu-open");
		$(".st-menu.slide-from-left").addClass("open");
		$(".st-menu.slide-from-left #filters-offcanvas").hide();
	});

	$("#button_offcanvas_sidebar_left").on('click', function() {
		$(".st-container").addClass("st-menu-open");
		$(".st-menu.slide-from-left").addClass("open");
		$(".st-menu.slide-from-left #mobiles-menu-offcanvas").hide();
	});

	// close offcanvas
	$("#st-container .st-pusher-after").on( 'click', function(e) {
		$(".st-container").removeClass("st-menu-open");
		$(".st-menu").removeClass("open");
		$('.site-search').removeClass("open");
		setTimeout( function() {
			$(".st-menu.slide-from-left #filters-offcanvas").show();
			$(".st-menu.slide-from-left #mobiles-menu-offcanvas").show();
		}, 600);
	});

	// open header search
	$(".site-tools ul li.search-button a").on('click', function() {
		$(".st-container").addClass("st-menu-open");
		$('.site-search').addClass("open");
		setTimeout( function() {
			$('.site-search .widget form .search-field').focus();
		}, 50);
	});

})(jQuery);

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

(function($) {

	"use strict";

    //wishlist
	$('.add_to_wishlist').on('click',function(){
		$(this).parents('.yith-wcwl-add-button').addClass('show_overlay');
	});

	function handleSelect() {
		if ($(window).innerWidth() > 1024 ) {

			$(".orderby, .big-select, select.topbar-language-switcher, select.wcml_currency_switcher").select2({
				minimumResultsForSearch: Infinity
			});
		}
	}

	handleSelect();

    $('.variations').on("click", ".reset_variations", function(){
		$('.big-select').select2("val", "");
	});

    //category parallax
	function parallax_engine(cat_parallax_pos) {
		if ($(window).innerWidth() > 1200 ) {
			$(".category_header").css('background-position', 'center '+parseInt(-200+cat_parallax_pos/1.5)+'px'); // this 200 value can be found in styles.css also
			$(".entry-header").css('background-position', 'center '+parseInt(-200+cat_parallax_pos/1.5)+'px'); // this 200 value can be found in styles.css also
		} else {
			$(".category_header").css('background-position','center center');
			$(".entry-header").css('background-position','center center');
		}
	}

	parallax_engine($(this).scrollTop());

    $(window).on( 'resize', function(){

		//category parallax
		parallax_engine($(this).scrollTop());
    });

    $(window).on( 'scroll', function() {
        //category parallax
		parallax_engine($(this).scrollTop());

        //mark this selector as visible
		$("#site-footer").each(function(i, el) {
			if ($(el).visible(true)) {
				$(el).addClass("on_screen");
			} else {
				$(el).removeClass("on_screen");
			}
		});
    });

    if ( ('form#register').length > 0 ) {
		var hash = window.location.hash;
		if (hash)
		{
			$('.account-tab-link').removeClass('current');
			$('a[href="'+hash+'"]').addClass('current');

			hash = hash.substring(1);
			$('.account-forms > form').hide();
			$('form#'+hash).show();
		}
	}

    // remove character '-' from recently viewed widget
	$(".recently_viewed_in_single ul li").contents().filter(function () {
		return this.nodeType === 3; // Text nodes only
	}).remove();

	$('a.add_to_wishlist').removeClass('button');

})(jQuery);

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

(function($) {

	"use strict";

    var ua = window.navigator.userAgent;
	var msie = ua.indexOf("MSIE ");

    function mrtailor_catalog_mode() {
		if (mrtailor_scripts_vars_array.catalogMode == 1) {
			$("form.cart div.quantity").empty();
			$("form.cart button.single_add_to_cart_button").remove();
		}
	}

    function replace_img_source(selector) {
		var data_src = $(selector).attr('data-src');
		$(selector).one('load', function() {
		}).each(function() {
			$(selector).attr('src', data_src);
			$(selector).css("opacity", "1");
		});
	}

	mrtailor_catalog_mode();

    //product animation (thanks Sam Sehnert)
	$('ul.products').addClass('effect-' + mrtailor_scripts_vars_array.products_animation);

	$.fn.visible = function(partial) {

		var $t            = $(this),
		$w            = $(window),
		viewTop       = $w.scrollTop(),
		viewBottom    = viewTop + $w.height(),
		_top          = $t.offset().top,
		_bottom       = _top + $t.height(),
		compareTop    = partial === true ? _bottom : _top,
		compareBottom = partial === true ? _top : _bottom;

		return ((compareBottom <= viewBottom) && (compareTop >= viewTop));

	};

	$("ul.products li").each(function(i, el) {
		if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
			$(el).addClass("shown");
		}
		else {
			if ($(el).visible(true)) {
				$(el).addClass("shown");
			}
		}
	});

    $('ul.products li img').each(function(){
		replace_img_source(this);
	});

	setTimeout(function() {
		$(".product_thumbnail.with_second_image .product_thumbnail_background").css("background-size", "cover");
		$(".product_thumbnail.with_second_image").addClass("second_image_loaded");
	}, 300);

    $(window).on( 'scroll', function() {
        //animate products
		if ($(window).innerWidth() > 640 ) {
			$(".products li").each(function(i, el) {
				if ($(el).visible(true)) {
					$(el).addClass("animate");
				}
			});
		}
    });

    $(document).ajaxComplete(function(event, request, settings) {
		$(".products li").addClass("animate");
		$(".product_thumbnail.with_second_image .product_thumbnail_background").css("background-size", "cover");
		$(".product_thumbnail.with_second_image").addClass("second_image_loaded");
	});

	var product_categories = $('.product-categories ul.products');
	if( product_categories.hasClass('columns-4') || product_categories.hasClass('columns-5') || product_categories.hasClass('columns-6') ) {
		if( product_categories.find('li.product-category').length === 3 ) {
			product_categories.removeClass('columns-4').removeClass('columns-5').removeClass('columns-6');
			product_categories.addClass('columns-3');
		}
	}
	if( product_categories.hasClass('columns-3') || product_categories.hasClass('columns-4') || product_categories.hasClass('columns-5') || product_categories.hasClass('columns-6') ) {
		if( product_categories.find('li.product-category').length === 2 ) {
			product_categories.removeClass('columns-3').removeClass('columns-4').removeClass('columns-5').removeClass('columns-6');
			product_categories.addClass('columns-2');
		}
	}

})(jQuery);

(function($) {

	"use strict";

    String.prototype.getDecimals || (String.prototype.getDecimals = function() {
        var a = this,
            b = ("" + a).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
        return b ? Math.max(0, (b[1] ? b[1].length : 0) - (b[2] ? +b[2] : 0)) : 0
    });

    $(document).on("click", ".plus, .minus", function() {
        var a = $(this).closest(".quantity").find(".qty"),
            b = parseFloat(a.val()),
            c = parseFloat(a.attr("max")),
            d = parseFloat(a.attr("min")),
            e = a.attr("step");
        b && "" !== b && "NaN" !== b || (b = 0), "" !== c && "NaN" !== c || (c = ""), "" !== d && "NaN" !== d || (d = 0), "any" !== e && "" !== e && void 0 !== e && "NaN" !== parseFloat(e) || (e = 1), $(this).is(".plus") ? c && b >= c ? a.val(c) : a.val((b + parseFloat(e)).toFixed(e.getDecimals())) : d && b <= d ? a.val(d) : b > 0 && a.val((b - parseFloat(e)).toFixed(e.getDecimals())), a.trigger("change")
    });

})(jQuery);

jQuery(function($) {

	"use strict";

	var pagination_type 		= mrtailor_scripts_vars_array.shop_pagination;
    var buffer_pixels           = Math.abs(0);

	if ( pagination_type == 'infinite' ) {

		if ( $('.woocommerce-pagination').length && $('body').hasClass( 'archive' ) ) {

            $('.woocommerce-pagination').hide();

			$('.woocommerce-pagination').before( '<div class="mrtailor_products_load_button" mrtailor_load_more_processing="0"></div>' );

			if ( pagination_type == 'infinite' ) {
				$( '.mrtailor_products_load_button' ).addClass( 'mrtailor_load_more_hidden' );
			}

			if ( $('.woocommerce-pagination a.next').length == 0 ) {
				$('.mrtailor_products_load_button').addClass( 'mrtailor_load_more_hidden' );
			}
		}

		$('body').on( 'click', '.mrtailor_products_load_button', function(e) {

			e.preventDefault();

			if ( $('.woocommerce-pagination a.next').length ) {

				$( '.mrtailor_products_load_button' ).attr( 'mrtailor_load_more_processing', '1' );
				var href = $( '.woocommerce-pagination a.next' ).attr( 'href' );

				$( '.mrtailor_products_load_button' ).fadeOut( 200, function() {
					$( '.woocommerce-pagination' ).before( '<div class="mrtailor_products_load_more_loader"></div>' );
				});

				$.get(href, function(response) {

					$( '.woocommerce-pagination' ).html( $(response).find( '.woocommerce-pagination' ).html() );

					var i= 0;

					$( response ).find( '.content-area .product-list-wrapper ul.products > li' ).each( function() {

						i++;
						$(this).addClass( "ajax-loaded delay-" + i );
						$('.content-area .product-list-wrapper ul.products > li:last').after($(this));
					});

					$('.mrtailor_products_load_more_loader').fadeOut(200, function(){
						$('.mrtailor_products_load_button').fadeIn(200);
						$('.mrtailor_products_load_button').attr('mrtailor_load_more_processing', '0');
					});

					setTimeout(function(){
						$('.mrtailor_products_load_more_loader').remove();
					}, 250 );

					$(document).trigger('post-load');

					setTimeout(function(){

						$('.content-area .product-list-wrapper ul.products > li').each( function(){
							//lazy loading tweak
							var image = $(this).find('.product_thumbnail > img.jetpack-lazy-image');
							if( image ) {
								if( image.attr('data-lazy-srcset') ) {
									image.attr('srcset', image.attr('data-lazy-srcset'));
								} else {
									image.attr('srcset', image.attr('src'));
								}
							}
						});

						$('.content-area .product-list-wrapper ul.products > li.hidden').removeClass('hidden').addClass('animate');
					}, 500);

					if ($('.woocommerce-pagination a.next').length == 0) {
						$('.mrtailor_products_load_button').remove();
					}

				});

			} else {

				$('.mrtailor_products_load_button').remove();
			}
		});

        $(window).on( 'scroll', function() {

			if ($('.content-area .product-list-wrapper ul.products').length) {

				var a = $('.content-area .product-list-wrapper ul.products').offset().top + $('.content-area .product-list-wrapper ul.products').outerHeight();
				var b = a - $(window).scrollTop();

				if ((b - buffer_pixels) < $(window).height()) {
					if ($('.mrtailor_products_load_button').attr('mrtailor_load_more_processing') == '0') {
						$('.mrtailor_products_load_button').trigger('click');
					}
				}
			}
		});
	}
});

(function( $ ) {

	'use strict';

	function gbt_cn_onElementInserted(containerSelector, selector, childSelector, callback) {
		if ("MutationObserver" in window) {
			var onMutationsObserved = function (mutations) {
				mutations.forEach(function (mutation) {
					if (mutation.addedNodes.length) {
						if ($(mutation.addedNodes).length) {
							var finalSelector = selector;
							var ownElement = $(mutation.addedNodes).filter(selector);
							if (childSelector != '') {
								ownElement = ownElement.find(childSelector);
								finalSelector = selector + ' ' + childSelector;
							}
							ownElement.each(function (index) {
								callback($(this), index + 1, ownElement.length, finalSelector,true);
							});
							if (!ownElement.length) {
								var childElements = $(mutation.addedNodes).find(finalSelector);
								childElements.each(function (index) {
									callback($(this), index + 1, childElements.length, finalSelector,true);
								});
							}
						}
					}
				});
			};

			var target = $(containerSelector)[0];
			var config = {childList: true, subtree: true};
			var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
			var observer = new MutationObserver(onMutationsObserved);
			observer.observe(target, config);
		}
	}

	var gbt_cn = {
		messages: [],
		open: false,
		init: function () {
			gbt_cn_onElementInserted('body', '.woocommerce-error', 		'', gbt_cn.checkForButtons);
			gbt_cn_onElementInserted('body', '.woocommerce-message', 	'', gbt_cn.checkForButtons);
			gbt_cn_onElementInserted('body', '.woocommerce-info', 		'', gbt_cn.checkForButtons);
			gbt_cn_onElementInserted('body', '.woocommerce-notice', 	'', gbt_cn.checkForButtons);

			gbt_cn.checkExistingElements('.woocommerce-error');
			gbt_cn.checkExistingElements('.woocommerce-message');
			gbt_cn.checkExistingElements('.woocommerce-info');
			gbt_cn.checkExistingElements('.woocommerce-notice');
		},
		checkExistingElements: function (selector) {
			var element = $(selector);
			if (element.length) {
				element.each(function (index) {
					gbt_cn.checkForButtons($(this), index + 1, element.length, selector,false);
				});
			}
		},
		checkForButtons: function (element, index, total, selector, dynamic) {
            if( ( element.find('a').length == 0 ) && ( element.find('button').length == 0 ) ) {
        		element.addClass('no-button');
        	}
		}
	};

	document.addEventListener('DOMContentLoaded', function () {
		gbt_cn.init();
		$('body').trigger({
			type: 'gbt_cn',
			obj: gbt_cn
		});
	});

} )( jQuery );

jQuery(function($) {

	"use strict";

    $('.gbt_18_snap_look_book').each(function() {

		if( $(this).index() == 0 ) {

			var windowHeight = $(window).height();
			var offsetTop = $(this).offset().top;
			var fullHeight = 100-offsetTop/(windowHeight/100);

			if( windowHeight && fullHeight ) {
				$(this).find('.gbt_18_hero_look_book_item').css('min-height', fullHeight+"vh");
				$(this).find('.gbt_18_hero_look_book_item').css('max-height', fullHeight+"vh");
			}
		} else {
            $(this).find('.gbt_18_hero_look_book_item').css('min-height', '90vh');
            $(this).find('.gbt_18_hero_look_book_item').css('max-height', '90vh');
        }
	});

    $('.gbt_18_default_slider').each(function() {

        if( $(window).width() >= 992 ) {

    		if( $(this).index() == 0 ) {

    			var windowHeight = $(window).height();
    			var offsetTop = $(this).offset().top;
    			var fullHeight = 100-offsetTop/(windowHeight/100);

    			if( windowHeight && fullHeight ) {
    				$(this).css('min-height', fullHeight+"vh");
    				$(this).css('max-height', fullHeight+"vh");
    			}
    		} else {
                $(this).css('min-height', '90vh');
                $(this).css('max-height', '90vh');
            }
        }
	});
});

//@prepros-prepend components/globals.js
//@prepros-prepend components/wc-counters.js
//@prepros-prepend components/wc-product-gallery.js
//@prepros-prepend components/offcanvas.js
//@prepros-prepend components/header.js
//@prepros-prepend components/blog.js
//@prepros-prepend components/footer.js
//@prepros-prepend components/woocommerce.js
//@prepros-prepend components/wpbakery.js
//@prepros-prepend components/wc-shop.js
//@prepros-prepend components/wc-quantity.js
//@prepros-prepend components/wc-products-ajax.js
//@prepros-prepend components/notifications.js
//@prepros-prepend components/product-blocks.js

