<?php

$custom_style .= '
    body,
    pre,
    hr,
    label,
    blockquote,
    abbr,
    acronym,
    table tr td,
    .woocommerce .recently_viewed_in_single h2,
    .product-nav-previous a,
    .product-nav-next a,
    #shipping_method .check_label_radio,
    .cart-collaterals table tr th,
    .woocommerce-checkout .woocommerce-info:before,
    .woocommerce-checkout .woocommerce-info,
    .payment_methods .check_label_radio,
    .order_details.bacs_details li strong,
    .thank_you_header .order_details li strong,
    .woocommerce-thankyou-order-details li,
    .woocommerce #content div.product p.stock.in-stock,
    .woocommerce div.product p.stock.in-stock,
    .woocommerce-page #content div.product p.stock.in-stock,
    .woocommerce-page div.product p.stock.in-stock,
    .woocommerce-checkout .woocommerce-terms-and-conditions *,
    label span,
    .wp-block-pullquote,
    .widget_shopping_cart .buttons a:first-child,
    .woocommerce ul.products li.product .price,
    .woocommerce div.product p.price del,
    .woocommerce-wishlist ul.shop_table.wishlist_table li table td,
    .woocommerce-account table.account-orders-table tbody tr td a.button:after,
    #add_payment_method #payment div.payment_box,
    .woocommerce-cart #payment div.payment_box,
    .woocommerce-checkout #payment div.payment_box,
    .blog .content-area .sticky-posts-container .sticky-post-info .sticky-meta .post_header_date,
    .gbt_18_mt_posts_slider .swiper-container .swiper-slide .gbt_18_mt_posts_slider_date
    {
        color: ' . MrTailor_Opt::getOption( 'body_color', '#222222' ) . ';
    }

    .st-content .widget_shopping_cart a.remove,
    .wc-block-grid__product-price,
    .wc-block-grid__product-price *
    {
        color: ' . MrTailor_Opt::getOption( 'body_color', '#222222' ) . ' !important;
    }

    .wc-block-grid__product-title:hover,
    .wc-block-grid__product-title a:hover,
    .wc-block-grid__product-link:hover .wc-block-grid__product-title
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'body_color', '#222222' ) ) . ', 0.80 );
    }

    .nav-previous-title,
    .nav-next-title,
    .woocommerce #content div.product .woocommerce-tabs ul.tabs li a:hover,
    .woocommerce div.product .woocommerce-tabs ul.tabs li a:hover,
    .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li a:hover,
    .woocommerce-page div.product .woocommerce-tabs ul.tabs li a:hover,
    .woocommerce table.shop_table th,
    .woocommerce-page table.shop_table th,
    .woocommerce-page #payment div.payment_box,
    .woocommerce-checkout .order_details.bacs_details li,
    .woocommerce-thankyou-order-details li,
    .thank_you_header .order_details li,
    .customer_details dt,
    .product_after_shop_loop .price del,
    .wpb_widgetised_column,
    .wpb_widgetised_column .widget_layered_nav ul li small.count,
    .post_header_date a:hover,
    .comment-metadata,
    .post_meta_archive a:hover,
    .products li:hover .add_to_wishlist:before,
    .wc-layered-nav-rating a,
    .woocommerce table.my_account_orders .woocommerce-orders-table__cell-order-actions .button:hover,
    .wcppec-checkout-buttons__separator,
    .woocommerce-MyAccount-content .woocommerce-pagination .woocommerce-button:hover,
    .wp-block-woocommerce-attribute-filter ul li label .wc-block-attribute-filter-list-count,
    .woocommerce div.product .woocommerce-tabs ul.tabs li a,
    .woocommerce #reviews #comments ol.commentlist li .meta,
    .comments-area ul.comment-list li .comment-metadata a
    {
        color: ' . MrTailor_Opt::getOption( 'body_color', '#222222' ) . ';
    }

    .widget.widget_price_filter .price_slider_amount .button:hover,
    .woocommerce a.remove:hover
    {
        color: ' . MrTailor_Opt::getOption( 'body_color', '#222222' ) . ' !important;
    }

    .required,
    .woocommerce form .form-row .required,
    .wp-caption-text,
    .woocommerce .woocommerce-breadcrumb,
    .woocommerce-page .woocommerce-breadcrumb,
    .woocommerce .woocommerce-result-count,
    .woocommerce-page .woocommerce-result-count
    .product_list_widget .wishlist-out-of-stock,
    .woocommerce #reviews #comments ol.commentlist li .comment-text .verified,
    .woocommerce-page #reviews #comments ol.commentlist li .comment-text .verified,
    .yith-wcwl-add-button:before,
    .post_header_date a,
    .comment-metadata,
    .post_meta_archive a,
    .wp-block-latest-posts li .wp-block-latest-posts__post-date
    {
        color: rgba(' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'body_color', '#222222' ) ) . ', 0.45 );
    }

    .woocommerce .star-rating:before,
    .woocommerce-page .star-rating:before,
    .woocommerce p.stars,
    .woocommerce-page p.stars,
    .wc-block-review-list-item__rating>.wc-block-review-list-item__rating__stars:before,
    .wp-block-getbowtied-carousel .swiper-wrapper .swiper-slide ul.products li.product .star-rating:before
    {
        color: rgba(' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'body_color', '#222222' ) ) . ', 0.35 );
    }


    hr,
    .woocommerce div.product .product_tabs .woocommerce-tabs ul.tabs li,
    .woocommerce-page div.product .product_tabs .woocommerce-tabs ul.tabs li,
    .wpb_widgetised_column .tagcloud a,
    .catalog-page .shop_header .filters_button
    {
        border-color: rgba(' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'body_color', '#222222' ) ) . ', 0.13 );
    }

    .woocommerce table.shop_table tbody th,
    .woocommerce table.shop_table tbody td,
    .woocommerce table.shop_table tbody tr:first-child td,
    .woocommerce table.shop_table tfoot th,
    .woocommerce table.shop_table tfoot td,
    .woocommerce .shop_table.customer_details tbody tr:first-child th,
    .woocommerce .cart-collaterals .cart_totals tr.order-total td,
    .woocommerce .cart-collaterals .cart_totals tr.order-total th,
    .woocommerce-page .cart-collaterals .cart_totals tr.order-total td,
    .woocommerce-page .cart-collaterals .cart_totals tr.order-total th,
    .woocommerce .my_account_container table.shop_table.order_details tr:first-child td,
    .woocommerce-page .my_account_container table.shop_table.order_details tr:first-child td,
    .woocommerce .my_account_container table.shop_table order_details_footer tr:last-child td,
    .woocommerce-page .my_account_container table.shop_table.order_details_footer tr:last-child td,
    .payment_methods li:first-child,
    .woocommerce-checkout .entry-content .woocommerce form.woocommerce-checkout #payment.woocommerce-checkout-payment ul.payment_methods li
    {
        border-top-color: rgba(' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'body_color', '#222222' ) ) . ', 0.13 );
    }

    abbr,
    acronym
    {
        border-bottom-color: rgba(' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'body_color', '#222222' ) ) . ', 1 );
    }

    table tr,
    .woocommerce .my_account_container table.shop_table.order_details tr:last-child td,
    .woocommerce-page .my_account_container table.shop_table.order_details tr:last-child td,
    .payment_methods li,
    .slide-from-left.filters aside,
    .woocommerce .shop_table.customer_details tbody tr:last-child th,
    .woocommerce .shop_table.customer_details tbody tr:last-child td,
    .woocommerce-cart form.woocommerce-cart-form,
    .woocommerce ul.product_list_widget li,
    .search .content-area .search-results .search-item
    {
        border-bottom-color: rgba(' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'body_color', '#222222' ) ) . ', 0.13 );
    }

    .woocommerce ul.products li.product-category .woocommerce-loop-category__title,
    .wp-block-getbowtied-categories-grid.gbt_18_categories_grid_wrapper .gbt_18_categories_grid .gbt_18_category_grid_item .gbt_18_category_grid_item_title
    {
        background-color: ' . MrTailor_Opt::getOption( 'body_color', '#222222' ) . ';
    }

    .woocommerce .widget_price_filter .ui-slider .ui-slider-range,
    .woocommerce-page .widget_price_filter .ui-slider .ui-slider-range
    {
        background: rgba(' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'body_color', '#222222' ) ) . ', 0.35 );
    }

    .woocommerce-checkout .thank_you_bank_details h3:after,
    .woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content,
    .woocommerce-page .widget_price_filter .price_slider_wrapper .ui-widget-content
    {
        background: rgba(' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'body_color', '#222222' ) ) . ', 0.13 );
    }

    .st-content .widget_shopping_cart ul.cart_list.product_list_widget li,
    .woocommerce-terms-and-conditions
    {
        border-color: rgba(' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'body_color', '#222222' ) ) . ', 0.13 ) !important;
    }

    .wc-block-price-filter .wc-block-price-filter__range-input-wrapper .wc-block-price-filter__range-input-progress
    {
        --range-color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'body_color', '#222222' ) ) . ', 0.45 );
    }

    .wp-block-woocommerce-price-filter .wc-block-price-filter__range-input-wrapper,
    .woocommerce-terms-and-conditions
    {
        background-color: rgba( ' . mrtailor_hex2rgb(MrTailor_Opt::getOption( 'body_color', '#222222' ) ) . ', 0.13 );
    }

    .woocommerce div.product div.images .flex-control-thumbs li img.flex-active
    {
        border-color: ' . MrTailor_Opt::getOption( 'body_color', '#222222' ) . ';
    }
';
