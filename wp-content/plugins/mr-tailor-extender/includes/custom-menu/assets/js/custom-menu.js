jQuery(function($) {

	"use strict";

    $('.main-navigation > ul > li.menu-item-megamenu').each( function() {

        // If number of children is bigger than the available columns (max 4), wrap them up in one column
        var menuColumns = 4;
        var menuChildrenNumber = $(this).find('> .sub-menu > li.menu-item').length;
        var that = $(this);

        if( $(this).find('.menu-item-info-column').length ) menuColumns--;
        if( $(this).find('.menu-item-image-column').length ) menuColumns--;

        if( menuChildrenNumber > menuColumns ) {
            if( $(this).find('> .sub-menu').hasClass('megamenu-with-info-column') ) {
                $(this).find('.menu-item-info-column').after('<li class="menu-item-inner-wrapper"><ul class="menu-item-inner-submenu"></ul></li>');
            } else {
                $(this).find('> .sub-menu').prepend('<li class="menu-item-inner-wrapper"><ul class="menu-item-inner-submenu"></ul></li>');
            }
            $(this).find('> .sub-menu > li.menu-item').each( function() {
                $(this).detach().appendTo( that.find('.menu-item-inner-submenu') );
            });
        }

        // determine the column megamenu class
        var childrenNumber = $(this).find('> .sub-menu > li').length;
        $(this).addClass('megamenu-'+childrenNumber+'-col');

        // assign parent image to image column
		if( $(this).find('> .sub-menu .menu-item-inner-wrapper').length ) {
			$(this).find('> .sub-menu .menu-item-image-column').css( 'max-height', $(this).find('> .sub-menu .menu-item-inner-wrapper').outerHeight() );
		} else {
			$(this).find('> .sub-menu .menu-item-image-column').css( 'max-height', $(this).find('> .sub-menu > .menu-item').outerHeight() );
		}
        if( $(this).attr('data-item-image') && $(this).find('> .sub-menu').hasClass('megamenu-with-image-column') ) {
            $(this).find('> .sub-menu .menu-item-image-column').empty().append( '<img src="' + $(this).attr('data-item-image') + '" />');
        }
    });

    // change image on hover
    $('.main-navigation > ul > li.menu-item-has-children .sub-menu li.menu-item a').on( 'mouseenter', function() {
        if( $(this).parents('.menu-item-parent').find('> .sub-menu').hasClass('megamenu-with-image-column') ) {
            if( $(this).parent().attr('data-item-image') ) {
                $(this).parents('.menu-item-parent').find('.sub-menu .menu-item-image-column').empty().append( '<img class="animate" src="' + $(this).parent().attr('data-item-image') + '" />');
            } else {
				if( $(this).parents('.menu-item-parent').attr('data-item-image') && ( $(this).parents('.menu-item-parent').attr('data-item-image') != $(this).parents('.menu-item-parent').find('.sub-menu .menu-item-image-column img').attr('src') ) ) {
                	$(this).parents('.menu-item-parent').find('.sub-menu .menu-item-image-column').empty().append( '<img class="animate" src="' + $(this).parents('.menu-item-parent').attr('data-item-image') + '" />');
				}
			}
        }
    });

    // set megamenu dropdown's max width and height
	if( $('.main-navigation > ul > li.menu-item-megamenu > .sub-menu').length ) {
	    $('.main-navigation > ul > li.menu-item-megamenu > .sub-menu').css( 'max-height', $(window).height() - $('.main-navigation > ul > li.menu-item-megamenu > .sub-menu').offset().top - 100 );
	    $('.main-navigation > ul > li.menu-item-megamenu > .sub-menu').css( 'max-width', $(window).width() );

	    $('.main-navigation > ul > li.menu-item-megamenu .sub-menu li.menu-item-inner-wrapper').each(function() {
	        $(this).find('ul.menu-item-inner-submenu').css( 'max-height', $(window).height() - $('.main-navigation > ul > li.menu-item-megamenu > .sub-menu').offset().top - 100 );
	    });
	}

});
