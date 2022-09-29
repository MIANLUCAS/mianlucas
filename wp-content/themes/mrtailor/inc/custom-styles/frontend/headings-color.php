<?php

$custom_style .= '
    h1, h2, h3, h4, h5, h6,
    table tr th,
    a,
    .st-menu h2,
    .select2-container,
    select.big-select,
    .woocommerce div.product span.price,
    .woocommerce-page div.product span.price,
    .woocommerce #content div.product span.price,
    .woocommerce-page #content div.product span.price,
    .woocommerce div.product p.price,
    .woocommerce-page div.product p.price,
    .woocommerce #content div.product p.price,
    .woocommerce-page #content div.product p.price,
    .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active a,
    .woocommerce div.product .woocommerce-tabs ul.tabs li.active a,
    .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active a,
    .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active a,
    ul.products h3 a,
    .products ul h3 a,
    ul.products h2 a,
    .products ul h2 a,
    .edit-account legend,
    .wc-block-grid__product-title,
    .wc-block-grid__product-title a,
    .catalog-page .woocommerce-ordering select.orderby,
    #minicart-offcanvas ul.cart_list.product_list_widget li *,
    #minicart-offcanvas .widget_shopping_cart .total,
    #minicart-offcanvas .minicart_text,
    .woocommerce-Reviews #review_form #reply-title,
    .woocommerce #reviews #comments ol.commentlist li .woocommerce-review__author,
    .select2-container--default .select2-selection--single span.select2-selection__placeholder,
    #minicart-offcanvas .woocommerce-mini-cart__empty-message,
    .mobile-navigation a,
    .select2-container--default .select2-selection--multiple span.select2-selection__choice__remove,
    .select2-container--default .select2-selection--single span.select2-selection__rendered,
    .filters_button,
    .add_to_wishlist:hover,
    .wc-block-grid ul.wc-block-grid__products li.wc-block-grid__product .wc-block-grid__product-add-to-cart a.wp-block-button__link:hover,
    .wc-block-grid ul.wc-block-grid__products li.wc-block-grid__product .wc-block-grid__product-add-to-cart button:hover,
    .woocommerce .woocommerce-breadcrumb a:hover,
    .woocommerce-checkout ul.order_details.woocommerce-thankyou-order-details li strong,
    .woocommerce-account .woocommerce-MyAccount-content > p mark,
    .woocommerce-form-coupon-toggle a.showcoupon,
    .woocommerce-form-login-toggle a.showlogin,
    .content-area blockquote.wp-block-quote p,
    .wc-block-pagination .wc-block-pagination-page,
    .wc-block-pagination-ellipsis,
    .wc-block-checkbox-list li.show-more button:hover,
    .content-area a.trigger-share-list:hover,
    .content-area .box-share-link span
    {
        color:  ' . MrTailor_Opt::getOption( 'headings_color', '#000000' ) . ';
    }

    .wpb_widgetised_column .widget-title,
    .wp-block-woocommerce-price-filter h3,
    .wp-block-woocommerce-attribute-filter h3,
    .wp-block-woocommerce-active-filters h3,
    .products a.button:hover
    {
        color:  ' . MrTailor_Opt::getOption( 'headings_color', '#000000' ) . ' !important;
    }

    .mrtailor_products_load_more_loader span
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'headings_color', '#000000' ) ) . ', 0.8 );
    }

    input[type="text"],
    input[type="password"],
    input[type="date"],
    input[type="datetime"],
    input[type="datetime-local"],
    input[type="month"],
    input[type="week"],
    input[type="email"],
    input[type="number"],
    input[type="search"],
    input[type="tel"],
    input[type="time"],
    input[type="url"],
    textarea,
    select,
    input[type="checkbox"],
    input[type="radio"],
    .select2-container .select2-selection__rendered,
    .select2-container--default span.select2-selection--multiple li.select2-selection__choice,
    span.select2-container--default li.select2-results__option[aria-selected=true]
    {
        color: ' . MrTailor_Opt::getOption( 'headings_color', '#000000' ) . ' ;
        background-color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'headings_color', '#000000' ) ) . ', 0.1 );
    }

    input[type="text"]:hover,
    input[type="password"]:hover,
    input[type="date"]:hover,
    input[type="datetime"]:hover,
    input[type="datetime-local"]:hover,
    input[type="month"]:hover,
    input[type="week"]:hover,
    input[type="email"]:hover,
    input[type="number"]:hover,
    input[type="search"]:hover,
    input[type="tel"]:hover,
    input[type="time"]:hover,
    input[type="url"]:hover,
    textarea:hover,
    select:hover,
    input[type="checkbox"]:hover,
    input[type="radio"]:hover,
    .select2-container .select2-selection__rendered:hover
    {
        background-color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'headings_color', '#000000' ) ) . ', 0.2 );
    }

    input::-webkit-input-placeholder,
    textarea::-webkit-input-placeholder
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'headings_color', '#000000' ) ) . ', 0.5 );
    }

    input:-moz-placeholder,
    textarea:-moz-placeholder /* Firefox 18- */
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'headings_color', '#000000' ) ) . ', 0.5 );
    }

    input::-moz-placeholder,
    textarea::-moz-placeholder /* Firefox 19+ */
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'headings_color', '#000000' ) ) . ', 0.5 );
    }

    input:-ms-input-placeholder,
    textarea:-ms-input-placeholder
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'headings_color', '#000000' ) ) . ', 0.5 );
    }

    input::placeholder,
    textarea::placeholder
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'headings_color', '#000000' ) ) . ', 0.5 );
    }

    .woocommerce div.product .woocommerce-tabs ul.tabs li.active,
    .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active,
    .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active,
    .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active
    {
        border-bottom-color:  ' . MrTailor_Opt::getOption( 'headings_color', '#000000' ) . ';
    }

    .label,
    button,
    .button,
    input[type="button"],
    input[type="reset"],
    input[type="submit"],
    .alert-box,
    .woocommerce-page #content input.button[type="submit"],
    .woocommerce-page #content input.button[type="submit"],
    .woocommerce #respond input#submit.alt.disabled,
    .woocommerce #respond input#submit.alt.disabled:hover,
    .woocommerce #respond input#submit.alt:disabled,
    .woocommerce #respond input#submit.alt:disabled:hover,
    .woocommerce #respond input#submit.alt:disabled[disabled],
    .woocommerce #respond input#submit.alt:disabled[disabled]:hover,
    .woocommerce #respond input#submit.disabled,
    .woocommerce #respond input#submit:disabled,
    .woocommerce #respond input#submit:disabled[disabled],
    .woocommerce a.button.disabled,
    .woocommerce a.button:disabled,
    .woocommerce a.button:disabled[disabled],
    .woocommerce button.button.disabled,
    .woocommerce button.button:disabled,
    .woocommerce button.button:disabled[disabled],
    .woocommerce input.button.disabled,
    .woocommerce input.button:disabled,
    .woocommerce input.button:disabled[disabled],
    .woocommerce a.button.alt.disabled,
    .woocommerce a.button.alt.disabled:hover,
    .woocommerce a.button.alt:disabled,
    .woocommerce a.button.alt:disabled:hover,
    .woocommerce a.button.alt:disabled[disabled],
    .woocommerce a.button.alt:disabled[disabled]:hover,
    .woocommerce button.button.alt.disabled,
    .woocommerce button.button.alt.disabled:hover,
    .woocommerce button.button.alt:disabled,
    .woocommerce button.button.alt:disabled:hover,
    .woocommerce button.button.alt:disabled[disabled],
    .woocommerce button.button.alt:disabled[disabled]:hover,
    .woocommerce input.button.alt.disabled,
    .woocommerce input.button.alt.disabled:hover,
    .woocommerce input.button.alt:disabled,
    .woocommerce input.button.alt:disabled:hover,
    .woocommerce input.button.alt:disabled[disabled],
    .woocommerce input.button.alt:disabled[disabled]:hover,
    .woocommerce #respond input#submit,
    .woocommerce a.button,
    .woocommerce button.button,
    .woocommerce input.button,
    .woocommerce #respond input#submit.alt,
    .woocommerce a.button.alt,
    .woocommerce button.button.alt,
    .woocommerce input.button.alt,
    span.select2-container--default li.select2-results__option--highlighted[aria-selected],
    p.out-of-stock,
    p.stock.available-on-backorder,
    .woocommerce p.out-of-stock,
    .woocommerce div.product .out-of-stock,
    .woocommerce div.product p.stock.available-on-backorder,
    .wp-block-file .wp-block-file__button,
    .wp-block-woocommerce-attribute-filter ul li input:checked + label:hover,
    .wp-block-woocommerce-active-filters ul.wc-block-active-filters-list li.wc-block-active-filters-list-item:hover,
    .woocommerce .mrtailor_products_load_button.finished a.button:hover
    {
        background-color:  ' . MrTailor_Opt::getOption( 'headings_color', '#000000' ) . ';
        color: ' . MrTailor_Opt::getOption( 'main_bg_color', '#ffffff' ) . ';
    }

    .woocommerce-checkout .entry-title:after,
    .woocommerce-account .entry-title:after,
    .woocommerce .widget_layered_nav_filters ul li a:hover,
    .woocommerce .widget_layered_nav ul li.chosen a:hover,
    .sticky-posts-container.swiper-container .sticky-pagination .swiper-pagination-bullet-active
    {
        background:  ' . MrTailor_Opt::getOption( 'headings_color', '#000000' ) . ';
    }

    input[type="radio"]:checked:before,
    .gbt_18_mt_posts_slider .swiper-pagination-bullet-active
    {
        background-color:  ' . MrTailor_Opt::getOption( 'headings_color', '#000000' ) . ';
    }

    input[type="checkbox"]:checked:before
    {
        border-color:  ' . MrTailor_Opt::getOption( 'headings_color', '#000000' ) . ';
    }

    .content-area .box-share-container.open .box-share-link svg path
    {
        fill: ' . MrTailor_Opt::getOption( 'headings_color', '#000000' )  . ';
    }
';
