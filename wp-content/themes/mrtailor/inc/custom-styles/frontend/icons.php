<?php

$nav_color_rgb	        = mrtailor_hex2rgb( MrTailor_Opt::getOption( 'navigation_link_color', '#ffffff' ) );
$accent_color_rgb       = mrtailor_hex2rgb( MrTailor_Opt::getOption( 'main_color', '#0d244c' ) );
$footer_color_rgb       = mrtailor_hex2rgb( MrTailor_Opt::getOption( 'footer_texts_color', '#686868' ) );
$footer_links_color_rgb = mrtailor_hex2rgb( MrTailor_Opt::getOption( 'footer_links_color', '#000000' ) );
$header_color_rgb       = mrtailor_hex2rgb( MrTailor_Opt::getOption( 'main_header_font_color', '#000000' ) );
$headings_color_rgb     = mrtailor_hex2rgb( MrTailor_Opt::getOption( 'headings_color', '#000000' ) );
$topbar_color_rgb       = mrtailor_hex2rgb( MrTailor_Opt::getOption( 'top_bar_typography', '#686868' ) );
$topbar_links_color_rgb = mrtailor_hex2rgb( MrTailor_Opt::getOption( 'top_bar_links_color', '#000000' ) );
$color_rgb 		        = mrtailor_hex2rgb( MrTailor_Opt::getOption( 'body_color', '#222222' ) );
$bg_color_rgb           = mrtailor_hex2rgb( MrTailor_Opt::getOption( 'main_bg_color', '#ffffff' ) );

$transparency_light_color_rgb = mrtailor_hex2rgb( MrTailor_Opt::getOption( 'main_header_transparent_light_color', '#ffffff' ) );
$transparency_dark_color_rgb  = mrtailor_hex2rgb( MrTailor_Opt::getOption( 'main_header_transparent_dark_color', '#000000' ) );

$header_menu_icon_size = MrTailor_Opt::getOption( 'main_header_font_size', 16 );

$custom_style .= '
    .trigger-share-list
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '18', '18', $accent_color_rgb, 'share' ) . ';
    }

    .trigger-share-list:hover
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '18', '18', $headings_color_rgb, 'share' ) . ';
    }

    .trigger-footer-widget-icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '34', '34', $footer_color_rgb, 'expand' ) . ';
    }

    .trigger-footer-widget-icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '34', '34', $footer_color_rgb, 'expand' ) . ';
    }

    .wp-block-getbowtied-vertical-slider .product div.quantity .minus
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '22', '22', $color_rgb, 'minus' ) . ';
    }

    .wp-block-getbowtied-vertical-slider .product div.quantity .plus
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '22', '22', $color_rgb, 'plus' ) . ';
    }

    .woocommerce div.quantity .minus
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '22', '22', $accent_color_rgb, 'minus' ) . ';
    }

    .woocommerce div.quantity .plus
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '22', '22', $accent_color_rgb, 'plus' ) . ';
    }

    .woocommerce a.remove
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '14', '14', $headings_color_rgb, 'close' ) . ';
    }

    .woocommerce a.remove:hover
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '14', '14', $accent_color_rgb, 'close' ) . ';
    }

    #site-footer .woocommerce a.remove
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '14', '14', $footer_links_color_rgb, 'close' ) . ';
    }

    #site-footer .woocommerce a.remove:hover
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '14', '14', $footer_color_rgb, 'close' ) . ';
    }

    .woocommerce-store-notice a.woocommerce-store-notice__dismiss-link:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '14', '14', $bg_color_rgb, 'close' ) . ';
    }

    .product-nav-previous a
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $color_rgb, 'arrow-left' ) . ';
    }

    .pswp.pswp--open .pswp__button.pswp__button--arrow--left
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', '0,0,0', 'arrow-left' ) . ' !important;
    }

    .product-nav-next a
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $color_rgb, 'arrow-right' ) . ';
    }

    .pswp.pswp--open .pswp__button.pswp__button--arrow--right
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', '0,0,0', 'arrow-right' ) . ' !important;
    }

    .single .post-navigation .previous-post-nav .nav-post-title:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '18', '18', $headings_color_rgb, 'arrow-left' ) . ';
    }

    .single .post-navigation .next-post-nav .nav-post-title:after
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '18', '18', $headings_color_rgb, 'arrow-right' ) . ';
    }

    .single .post-navigation .previous-post-nav:hover .nav-post-title:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '18', '18', $accent_color_rgb, 'arrow-left' ) . ';
    }

    .single .post-navigation .next-post-nav:hover .nav-post-title:after
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '18', '18', $accent_color_rgb, 'arrow-right' ) . ';
    }

    .comment .comment-reply a:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $headings_color_rgb, 'reply' ) . ';
    }

    .comment .comment-reply a:hover:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $accent_color_rgb, 'reply' ) . ';
    }

    .comment span.comment-edit-link a:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $headings_color_rgb, 'edit' ) . ';
    }

    .comment span.comment-edit-link a:hover:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $accent_color_rgb, 'edit' ) . ';
    }

    .filters_button:hover:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $accent_color_rgb, 'hamburger' ) . ';
    }

    .filters_button:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $headings_color_rgb, 'hamburger' ) . ';
    }

    .woocommerce-checkout .woocommerce-info:before,
    .woocommerce-checkout.woocommerce-page .woocommerce-info:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '20', '20', $color_rgb, 'info' ) . ';
    }

    .woocommerce .cart-collaterals .woocommerce-shipping-calculator .shipping-calculator-button
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $headings_color_rgb, 'arrow-down' ) . ';
    }

    .woocommerce .cart-collaterals .woocommerce-shipping-calculator .shipping-calculator-button:hover,
    .woocommerce .cart-collaterals .woocommerce-shipping-calculator .shipping-calculator-button:focus
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $accent_color_rgb, 'arrow-down' ) . ';
    }

    .widget .recentcomments:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $color_rgb, 'comment' ) . ';
    }

    #site-footer .widget .recentcomments:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $footer_color_rgb, 'comment' ) . ';
    }

    select,
    .content-area .select2-selection__arrow:before,
    .content-area .select2-container .select2-choice .select2-arrow:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $headings_color_rgb, 'arrow-down' ) . ';
    }

    .catalog-page .woocommerce-ordering:hover .select2-selection__arrow:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $accent_color_rgb, 'arrow-down' ) . ';
    }

    .catalog-page .woocommerce-ordering .select2-selection__arrow:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $headings_color_rgb, 'arrow-down' ) . ';
    }

    .language-and-currency .select2-selection__arrow:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $topbar_color_rgb, 'arrow-down' ) . ';
    }

    /*main navigation first child arrow icon*/
    .transparent_header.transparency_light #site-navigation.main-navigation > ul > li.menu-item-has-children > a:after
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( $header_menu_icon_size, $header_menu_icon_size, $transparency_light_color_rgb, 'arrow-down' ) . ';
    }

    .top-headers-wrapper.site-header-sticky.sticky #site-navigation.main-navigation > ul > li.menu-item-has-children > a:not(:hover):after
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( $header_menu_icon_size, $header_menu_icon_size, $header_color_rgb, 'arrow-down' ) . ' !important;
    }

    .top-headers-wrapper.site-header-sticky.sticky #site-navigation.main-navigation > ul > li.menu-item-has-children > a:hover:after
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( $header_menu_icon_size, $header_menu_icon_size, $accent_color_rgb, 'arrow-down' ) . ' !important;
    }

    .transparent_header.transparency_dark #site-navigation.main-navigation > ul > li.menu-item-has-children > a:after
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( $header_menu_icon_size, $header_menu_icon_size, $transparency_dark_color_rgb, 'arrow-down' ) . ';
    }

    #site-navigation.main-navigation > ul > li.menu-item-has-children > a:after
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( $header_menu_icon_size, $header_menu_icon_size, $header_color_rgb, 'arrow-down' ) . ';
    }

    #page:not(.transparent_header) #site-navigation.main-navigation > ul > li.menu-item-has-children > a:hover:after
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( $header_menu_icon_size, $header_menu_icon_size, $accent_color_rgb, 'arrow-down' ) . ';
    }

    .main-navigation ul li a:after
    {
        height: ' . $header_menu_icon_size . 'px;
        width: ' . $header_menu_icon_size . 'px;
    }

    #site-navigation-top-bar.main-navigation > ul > li.menu-item-has-children > a:after
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $topbar_links_color_rgb, 'arrow-down' ) . ';
    }

    #site-navigation-top-bar.main-navigation > ul > li.menu-item-has-children > a:hover:after
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $topbar_color_rgb, 'arrow-down' ) . ';
    }

    /* menu icon */
    .transparent_header.transparency_light .mobile-menu-button span.mobile-menu-text
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $transparency_light_color_rgb, 'hamburger' ) . ';
    }

    .transparent_header.transparency_dark .mobile-menu-button span.mobile-menu-text
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $transparency_dark_color_rgb, 'hamburger' ) . ';
    }

    .transparent_header.transparency_light .mobile-menu-button:hover span.mobile-menu-text,
    .transparent_header.transparency_dark .mobile-menu-button:hover span.mobile-menu-text
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $accent_color_rgb, 'hamburger' ) . ';
    }

    .mobile-menu-button span.mobile-menu-text
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $header_color_rgb, 'hamburger' ) . ';
    }

    .mobile-menu-button a:hover span.mobile-menu-text
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $accent_color_rgb, 'hamburger' ) . ';
    }

    /*cart icon*/
    .transparent_header.transparency_light .site-tools ul li.shopping-bag-button .shopping_cart_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $transparency_light_color_rgb, 'shopping-cart' ) . ';
    }

    .top-headers-wrapper.site-header-sticky.sticky .site-tools ul li.shopping-bag-button:not(:hover) .shopping_cart_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $header_color_rgb, 'shopping-cart' ) . ' !important;
    }

    .top-headers-wrapper.site-header-sticky.sticky .site-tools ul li.shopping-bag-button:hover .shopping_cart_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $accent_color_rgb, 'shopping-cart' ) . ' !important;
    }

    .transparent_header.transparency_dark .site-tools ul li.shopping-bag-button .shopping_cart_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $transparency_dark_color_rgb, 'shopping-cart' ) . ';
    }

    .site-tools ul li.shopping-bag-button .shopping_cart_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $header_color_rgb, 'shopping-cart' ) . ';
    }

    .site-tools ul li.shopping-bag-button:hover .shopping_cart_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $accent_color_rgb, 'shopping-cart' ) . ';
    }

    /*my account icon*/
    .transparent_header.transparency_light .site-tools ul li.myaccount-button .myaccount_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $transparency_light_color_rgb, 'myaccount' ) . ';
    }

    .top-headers-wrapper.site-header-sticky.sticky .site-tools ul li.myaccount-button:not(:hover) .myaccount_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $header_color_rgb, 'myaccount' ) . ' !important;
    }

    .top-headers-wrapper.site-header-sticky.sticky .site-tools ul li.myaccount-button:hover .myaccount_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $accent_color_rgb, 'myaccount' ) . ' !important;
    }

    .transparent_header.transparency_dark .site-tools ul li.myaccount-button .myaccount_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $transparency_dark_color_rgb, 'myaccount' ) . ';
    }

    .site-tools ul li.myaccount-button .myaccount_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $header_color_rgb, 'myaccount' ) . ';
    }

    .site-tools ul li.myaccount-button:hover .myaccount_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $accent_color_rgb, 'myaccount' ) . ';
    }

    /*search icon*/
    .transparent_header.transparency_light .site-tools ul li.search-button .search_icon,
    .transparent_header.transparency_light .site-search .search-but-added .search_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '22', '22', $transparency_light_color_rgb, 'search' ) . ';
    }

    .top-headers-wrapper.site-header-sticky.sticky .site-tools ul li.search-button:not(:hover) .search_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '22', '22', $header_color_rgb, 'search' ) . ' !important;
    }

    .top-headers-wrapper.site-header-sticky.sticky .site-tools ul li.search-button:hover .search_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '22', '22', $accent_color_rgb, 'search' ) . ' !important;
    }

    .transparent_header.transparency_dark .site-tools ul li.search-button .search_icon,
    .transparent_header.transparency_dark .site-search .search-but-added .search_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '22', '22', $transparency_dark_color_rgb, 'search' ) . ';
    }

    .site-tools ul li.search-button .search_icon,
    .site-search .search-but-added .search_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '22', '22', $header_color_rgb, 'search' ) . ';
    }

    .site-tools ul li.search-button:hover .search_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '22', '22', $accent_color_rgb, 'search' ) . ';
    }

    .site-search .widget form button[type="submit"]
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '28', '28', '0,0,0', 'search' ) . ';
    }

    .site-search .widget form button[type="submit"]:hover
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '28', '28', $accent_color_rgb, 'search' ) . ';
    }

    /*wishlist icon*/
    .transparent_header.transparency_dark .site-tools ul li.wishlist-button .wishlist_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $transparency_dark_color_rgb, 'wishlist' ) . ';
    }

    .top-headers-wrapper.site-header-sticky.sticky .site-tools ul li.wishlist-button:not(:hover) .wishlist_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $header_color_rgb, 'wishlist' ) . ' !important;
    }

    .top-headers-wrapper.site-header-sticky.sticky .site-tools ul li.wishlist-button:hover .wishlist_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $accent_color_rgb, 'wishlist' ) . ' !important;
    }

    .transparent_header.transparency_light .site-tools ul li.wishlist-button .wishlist_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $transparency_light_color_rgb, 'wishlist' ) . ';
    }

    .site-tools ul li.wishlist-button .wishlist_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $header_color_rgb, 'wishlist' ) . ';
    }

    .site-tools ul li.wishlist-button:hover .wishlist_icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $accent_color_rgb, 'wishlist' ) . ';
    }

    .add_to_wishlist:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '18', '18', $accent_color_rgb, 'wishlist' ) . ';
    }

    .add_to_wishlist:hover:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '18', '18', $headings_color_rgb, 'wishlist' ) . ';
    }

    .product_infos .yith-wcwl-wishlistaddedbrowse:before,
    .product_infos .yith-wcwl-wishlistexistsbrowse:before,
    .products .yith-wcwl-wishlistaddedbrowse a:before,
    .products .yith-wcwl-wishlistexistsbrowse a:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '18', '18', $accent_color_rgb, 'wishlist-full' ) . ';
        opacity: 1;
    }

    .mobile-navigation .menu-item-has-children .more:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $headings_color_rgb, 'plus' ) . ';
    }

    .mobile-navigation .menu-item-has-children .more:hover:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $accent_color_rgb, 'plus' ) . ';
    }

    .mobile-navigation .menu-item-has-children > .sub-menu.open + .more:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $headings_color_rgb, 'minus' ) . ';
    }


    .mobile-navigation .menu-item-has-children > .sub-menu.open + .more:hover:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $accent_color_rgb, 'minus' ) . ';
    }

    .woocommerce-checkout .woocommerce-form-coupon-toggle,
    .woocommerce-thankyou-order-received,
    .woocommerce-thankyou-order-details,
    .woocommerce-checkout .woocommerce form.woocommerce-checkout .woocommerce-checkout-review-order,
    .woocommerce-checkout .woocommerce form.woocommerce-checkout .woocommerce-checkout-payment
    {
        background-image: url(\'data:image/svg+xml;utf8, <svg viewBox="0 0 200 110" xmlns="http://www.w3.org/2000/svg"><path d="M -15 110 L100 10 L215 110" fill="none" stroke="%23'.str_replace('#','',MrTailor_Opt::getOption( 'body_color', '#222222' )).'" stroke-width="1" vector-effect="non-scaling-stroke"/></svg>\');
    }

    .widget_shopping_cart ul.cart_list.product_list_widget:after,
    .woocommerce .widget_shopping_cart ul.cart_list.product_list_widget:after,
    .mobile-navigation:after
    {
        background-image: url(\'data:image/svg+xml;utf8, <svg viewBox="0 0 200 110" xmlns="http://www.w3.org/2000/svg"><path d="M -15 110 L100 10 L215 110" fill="none" stroke="%23'.str_replace('#','',MrTailor_Opt::getOption( 'headings_color', '#000000' )).'" stroke-width="1" vector-effect="non-scaling-stroke"/></svg>\');
    }

    #site-footer .widget_shopping_cart ul.cart_list.product_list_widget:after,
    #site-footer .woocommerce .widget_shopping_cart ul.cart_list.product_list_widget:after
    {
        background-image: url(\'data:image/svg+xml;utf8, <svg viewBox="0 0 200 110" xmlns="http://www.w3.org/2000/svg"><path d="M -15 110 L100 10 L215 110" fill="none" stroke="%23'.str_replace('#','',MrTailor_Opt::getOption( 'footer_texts_color', '#686868' )).'" stroke-width="1" vector-effect="non-scaling-stroke"/></svg>\');
    }

    .woocommerce .widget_layered_nav_filters ul li a:before,
    .woocommerce .widget_layered_nav ul li.chosen a:before,
    .wc-block-attribute-filter .wc-block-attribute-filter-list li label:before,
    .wp-block-woocommerce-active-filters ul.wc-block-active-filters-list li.wc-block-active-filters-list-item button
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '13', '13', $bg_color_rgb, 'close' ) . ';
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '11', '11', $headings_color_rgb, 'close' ) . ';
    }

    .pswp.pswp--open .pswp__top-bar .pswp__button.pswp__button--close
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '26', '26', '0,0,0', 'close' ) . ' !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '11', '11', $accent_color_rgb, 'close' ) . ';
    }

    span.onsale,
    .woocommerce span.onsale,
    .wc-block-grid ul.wc-block-grid__products li.wc-block-grid__product .wc-block-grid__product-onsale
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $bg_color_rgb, 'tag' ) . ';
    }

    p.out-of-stock,
    .woocommerce p.out-of-stock
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '18', '18', $bg_color_rgb, 'unavailable' ) . ';
    }

    .woocommerce div.product div.images .woocommerce-product-gallery__trigger
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', '0,0,0', 'plus-circled' ) . ';
    }

    .blog-post .entry-title-archive span.arrow-icon,
    .single .post-navigation .post-nav .entry-thumbnail span.arrow-icon,
    .blog .content-area .sticky-posts-container.swiper-container .swiper-slide .thumbnail_container span.arrow-icon,
    .gbt_18_mt_posts_grid .gbt_18_mt_posts_grid_wrapper span.arrow-icon
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '20', '20', $bg_color_rgb, 'arrow-right' ) . ';
    }

    .next-posts-nav .nav-next a:after
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '20', '20', $headings_color_rgb, 'arrow-right' ) . ';
    }

    .next-posts-nav .nav-next a:focus:after,
    .next-posts-nav .nav-next a:active:after,
    .next-posts-nav .nav-next a:hover:after
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '20', '20', $accent_color_rgb, 'arrow-right' ) . ';
    }

    .previous-posts-nav .nav-previous a:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '20', '20', $headings_color_rgb, 'arrow-left' ) . ';
    }

    .previous-posts-nav .nav-previous a:focus:before,
    .previous-posts-nav .nav-previous a:active:before,
    .previous-posts-nav .nav-previous a:hover:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '20', '20', $accent_color_rgb, 'arrow-left' ) . ';
    }

    .content-area .sticky-posts-container.swiper-container .swiper-slide .sticky-post-info .sticky-meta .featured_span:before
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '16', '16', $bg_color_rgb, 'star' ) . ';
    }

    .gbt_18_mt_posts_slider .swiper-button-next
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $headings_color_rgb, 'arrow-right' ) . ';
    }

    .gbt_18_mt_posts_slider .swiper-button-prev
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $headings_color_rgb, 'arrow-left' ) . ';
    }

    #site-top-bar .topbar-wrapper .topbar-logout ul li a
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '14', '14', $topbar_links_color_rgb, 'shutdown' ) . ';
    }

    #site-top-bar .topbar-wrapper .topbar-logout ul li a:hover
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '14', '14', $topbar_color_rgb, 'shutdown' ) . ';
    }

    .wp-block-getbowtied-carousel .swiper-button-prev:before
    {
        content:  ' . MrTailor_Icons::get_icon_url( '24', '24', $headings_color_rgb, 'arrow-left' ) . ';
    }

    .wp-block-getbowtied-carousel .swiper-button-next:before
    {
        content:  ' . MrTailor_Icons::get_icon_url( '24', '24', $headings_color_rgb, 'arrow-right' ) . ';
    }

    .gbt_18_icon_down:before
    {
        content:  ' . MrTailor_Icons::get_icon_url( '24', '24', $headings_color_rgb, 'arrow-alt-down' ) . ';
    }

    .gbt_18_icon_up:before
    {
        content:  ' . MrTailor_Icons::get_icon_url( '24', '24', $headings_color_rgb, 'arrow-alt-up' ) . ';
    }
';
