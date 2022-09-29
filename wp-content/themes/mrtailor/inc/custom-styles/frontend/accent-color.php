<?php

$custom_style .= '
    .widget .tagcloud a:hover,
    span.onsale,
    .woocommerce span.onsale,
    .wc-block-grid__product-onsale,
    .woocommerce .widget_layered_nav_filters ul li a,
    .woocommerce-page .widget_layered_nav_filters ul li a,
    .woocommerce .widget_layered_nav ul li.chosen a,
    .woocommerce-page .widget_layered_nav ul li.chosen a,
    .nl-field ul,
    .nl-form .nl-submit,
    .select2-results .select2-highlighted,
    .with_thumb_icon,
    ul.pagination li.current a,
    ul.pagination li.current a:hover, ul.pagination li.current a:focus,
    .progress .meter,
    .sub-nav dt.active a,
    .sub-nav dd.active a,
    .sub-nav li.active a,
    .top-bar-section ul li > a.button, .top-bar-section ul .woocommerce-page li > a.button, .woocommerce-page .top-bar-section ul li > a.button,
    .top-bar-section ul .woocommerce-page li > a.button.alt,
    .woocommerce-page .top-bar-section ul li > a.button.alt,
    .top-bar-section ul li.active > a,
    .no-js .top-bar-section ul li:active > a,
    .woocommerce-edit-address #content .woocommerce input.button
    {
        background-color: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' )  . ';
    }

    .blog_list_img,
    .wc-block-pagination .wc-block-pagination-page.wc-block-pagination-page--active,
    .wc-block-pagination .wc-block-pagination-page:hover,
    .woocommerce ul.products li.product-category:hover .woocommerce-loop-category__title,
    .wp-block-getbowtied-categories-grid.gbt_18_categories_grid_wrapper .gbt_18_categories_grid .gbt_18_category_grid_item:hover .gbt_18_category_grid_item_title
    {
        background-color: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' )  . ';
    }

    #minicart-offcanvas .widget_shopping_cart .buttons a.checkout:hover
    {
        background-color: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' )  . '!important;
    }

    .select2-dropdown-open.select2-drop-above .select2-choice,
    .select2-dropdown-open.select2-drop-above .select2-choices,
    .select2-container .select2-selection,
    .yith-wcwl-add-button,
    .yith-wcwl-wishlistaddedbrowse .feedback,
    .yith-wcwl-wishlistexistsbrowse .feedback,
    .woocommerce .star-rating span:before,
    .woocommerce-page .star-rating span:before,
    .woocommerce .woocommerce-breadcrumb a,
    .woocommerce-page .woocommerce-breadcrumb a,
    .panel.callout a,
    .side-nav li a,
    .has-tip:hover, .has-tip:focus,
    a:hover,
    a:focus,
    .edit-link,
    .woocommerce p.stars a.active:after,
    .woocommerce p.stars a:hover:after,
    .woocommerce-page p.stars a.active:after,
    .woocommerce-page p.stars a:hover:after,
    .yith-wcwl-wishlistaddedbrowse,
    .yith-wcwl-wishlistexistsbrowse,
    .woocommerce-page #content .actions .coupon input.button,
    .woocommerce-page #content .actions .coupon input.button.alt,
    .woocommerce-page #content .actions > input.button,
    .woocommerce-page #content .actions > input.button.alt,
    .woocommerce table.my_account_orders .button,
    .wc-block-grid ul.wc-block-grid__products li.wc-block-grid__product .wc-block-grid__product-rating .star-rating span:before,
    .wc-block-grid__product-rating .wc-block-grid__product-rating__stars span:before,
    .woocommerce-form-coupon-toggle .woocommerce-info a:hover,
    .woocommerce-form-login-toggle .woocommerce-info a:hover,
    .wp-block-woocommerce-active-filters .wc-block-active-filters__clear-all,
    a.add_to_wishlist,
    .woocommerce div.quantity .minus,
    .woocommerce div.quantity .plus,
    .woocommerce div.quantity input.qty,
    .widget_shopping_cart .buttons a:first-child:hover,
    .widget_shopping_cart ul.cart_list.product_list_widget li a:hover,
    #minicart-offcanvas ul.cart_list.product_list_widget li a:hover,
    .woocommerce p.stars a:hover,
    .woocommerce p.stars a:focus,
    .comments-area ul.comment-list li .comment-metadata a:hover,
    .site-tools ul li:hover span,
    #page:not(.transparent_header) #site-navigation.main-navigation > ul > li > a:hover,
    .top-headers-wrapper.site-header-sticky.sticky #site-navigation.main-navigation > ul > li > a:hover,
    .site-tools ul li.mobile-menu-button:hover span.mobile-menu-text,
    .mobile-navigation .current-menu-item > a,
    .mobile-navigation .current-menu-ancestor > a,
    .mobile-navigation .current_page_item > a,
    .mobile-navigation .current_page_ancestor > a,
    .select2-container--default .select2-selection--multiple span.select2-selection__choice__remove:hover,
    .filters_button:hover,
    .catalog-page .woocommerce-ordering:hover .select2-container .select2-selection__rendered,
    .wc-block-grid ul.wc-block-grid__products li.wc-block-grid__product .wc-block-grid__product-add-to-cart a.wp-block-button__link,
    .wc-block-grid ul.wc-block-grid__products li.wc-block-grid__product .wc-block-grid__product-add-to-cart button,
    .stars a,
    .wc-block-checkbox-list li.show-more button,
    .wc-block-review-list-item__rating>.wc-block-review-list-item__rating__stars span:before,
    a.trigger-share-list,
    .content-area .box-share-link span:hover,
    .single .post-navigation .post-nav:hover .post-title,
    .gbt_18_mt_posts_slider .swiper-container .swiper-slide .gbt_18_mt_posts_slider_link:hover .gbt_18_mt_posts_slider_title,
    .gbt_18_mt_posts_grid .gbt_18_mt_posts_grid_item:hover .gbt_18_mt_posts_grid_title,
    .wp-block-getbowtied-carousel .swiper-wrapper .swiper-slide ul.products li.product .star-rating span:before
    {
        color: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' )  . ';
    }

    .products a.button,
    .widget.widget_price_filter .price_slider_amount .button,
    #wishlist-offcanvas .button,
    #wishlist-offcanvas input[type="button"],
    #wishlist-offcanvas input[type="reset"],
    #wishlist-offcanvas input[type="submit"],
    .tooltip.opened
    {
        color: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' )  . ' !important;
    }

    .shortcode_products_slider .products a.button:hover
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'main_color', '#0d244c' ) ) . ', 0.8 ) !important;
    }

    .main-navigation ul ul li a:hover,
    .box-share-link:hover span
    {
        border-bottom-color: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' )  . ';
    }

    .login_header
    {
        border-top-color: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' )  . ';
    }

    .widget .tagcloud a:hover,
    .woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
    .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle,
    .woocommerce-page #content .actions .coupon input.button,
    .woocommerce-page #content .actions .coupon input.button.alt,
    .woocommerce-page #content .actions > input.button,
    .woocommerce-page #content .actions > input.button.alt,
    .woocommerce div.quantity
    {
        border-color: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' )  . ';
    }

    .label:hover,
    button:hover,
    .button:hover,
    input[type="button"]:hover,
    input[type="reset"]:hover,
    input[type="submit"]:hover,
    .alert-box:hover,
    .woocommerce-page #content input.button[type="submit"]:hover,
    .woocommerce-page #content input.button[type="submit"]:hover,
    .woocommerce #respond input#submit:hover,
    .woocommerce a.button:hover,
    .woocommerce button.button:hover,
    .woocommerce input.button:hover,
    .woocommerce #respond input#submit.alt:hover,
    .woocommerce a.button.alt:hover,
    .woocommerce button.button.alt:hover,
    .woocommerce input.button.alt:hover,
    .woocommerce nav.woocommerce-pagination ul li span.current,
    .woocommerce nav.woocommerce-pagination ul li a:hover,
    .woocommerce nav.woocommerce-pagination ul li a:focus,
    .woocommerce #respond input#submit.disabled:hover,
    .woocommerce #respond input#submit:disabled:hover,
    .woocommerce #respond input#submit:disabled[disabled]:hover,
    .woocommerce a.button.disabled:hover,
    .woocommerce a.button:disabled:hover,
    .woocommerce a.button:disabled[disabled]:hover,
    .woocommerce button.button.disabled:hover,
    .woocommerce button.button:disabled:hover,
    .woocommerce button.button:disabled[disabled]:hover,
    .woocommerce input.button.disabled:hover,
    .woocommerce input.button:disabled:hover,
    .woocommerce input.button:disabled[disabled]:hover,
    .wp-block-file .wp-block-file__button:hover,
    .wp-block-woocommerce-attribute-filter ul li input:checked + label,
    .wc-block-active-filters .wc-block-active-filters-list li,
    .content-area .blog-isotop-master-wrapper .blog-isotop-container .blog-isotope .blog-post .more-link,
    .gbt_18_mt_posts_grid .gbt_18_mt_posts_grid_wrapper .more-link,
    .content-area .sticky-posts-container.swiper-container .swiper-slide .thumbnail_container .more-link,
    .gbt_18_mt_posts_slider .swiper-container .swiper-slide .more-link,
    .single .post-navigation .post-nav .entry-thumbnail .more-link,
    .content-area .sticky-posts-container .sticky-post-info .sticky-meta .featured_span,
    .woocommerce-store-notice, p.demo_store
    {
        background-color:  ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' ) . ';
        color: ' . MrTailor_Opt::getOption( 'main_bg_color', '#ffffff' ) . ';
    }

    .quantity input.qty::-webkit-input-placeholder
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'main_color', '#0d244c' ) ) . ', 0.5 );
    }

    .quantity input.qty:-moz-placeholder /* Firefox 18- */
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'main_color', '#0d244c' ) ) . ', 0.5 );
    }

    .quantity input.qty::-moz-placeholder /* Firefox 19+ */
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'main_color', '#0d244c' ) ) . ', 0.5 );
    }

    .quantity input.qty:-ms-input-placeholder
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'main_color', '#0d244c' ) ) . ', 0.5 );
    }

    .quantity input.qty::placeholder
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'main_color', '#0d244c' ) ) . ', 0.5 );
    }

    .wp-block-quote
    {
        border-left-color:  ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' ) . ';
    }

    .content-area .box-share-container.open .box-share-link:hover svg path
    {
        fill: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' )  . ';
    }
';
