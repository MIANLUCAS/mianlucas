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
