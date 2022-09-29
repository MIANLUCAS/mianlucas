<?php

$h1_size        = 2.488 * MrTailor_Opt::getOption( 'body_text_font_size', 16 ) . 'px';
$h2_size        = 2.074 * MrTailor_Opt::getOption( 'body_text_font_size', 16 ) . 'px';
$h3_size        = 1.728 * MrTailor_Opt::getOption( 'body_text_font_size', 16 ) . 'px';
$h4_size        = 1.44  * MrTailor_Opt::getOption( 'body_text_font_size', 16 ) . 'px';
$h5_size        = 1.2   * MrTailor_Opt::getOption( 'body_text_font_size', 16 ) . 'px';
$drop_cap_size  = 6.4   * MrTailor_Opt::getOption( 'body_text_font_size', 16 ) . 'px';

$custom_style .= '

    .entry-title,
    .page-title,
    .content-area h1.entry-title,
    .entry-content h1.entry-title,
    .content-area h1.page-title,
    .entry-content h1.page-title,
    .main-slider h2,
    .lookbook-first-slide-wrapper h2,
    .content-area h2.nothing-found-title,
    .content-area h2.entry-title.blog-post-title,
    .wp-block-getbowtied-vertical-slider .gbt_18_slide_title a,
    .wp-block-getbowtied-lookbook-reveal .gbt_18_content_top h2,
    .gbt_18_snap_look_book .gbt_18_hero_section_content .gbt_18_hero_title
    {
        font-size: ' . MrTailor_Opt::getOption( 'h1_font_size', 55 ) . 'px;
    }

    p,
    .content-area h6,
    .entry-content h6,
    input,
    textarea,
    select,
    .select2-selection__placeholder,
    .select2-selection__rendered,
    .woocommerce #reviews #comments ol.commentlist li .woocommerce-review__author,
    .comments-area ul.comment-list li .comment-author,
    .site-content p:not([class*="has-"]),
    .content-area,
    .content-area h6,
    .content-area ul,
    .content-area ol,
    .content-area dl,
    table tr td,
    table tbody tr td,
    table tfoot tr th,
    table tfoot tr td,
    .woocommerce table.shop_attributes td,
    .wp-block-quote cite,
    .wp-block-pullquote cite,
    .wishlist_table.mobile li .item-details table.item-details-table td.value,
    .content-area .blog-isotop-master-wrapper .blog-isotop-container .blog-isotope .blog-post .more-link,
    .gbt_18_mt_posts_grid .gbt_18_mt_posts_grid_wrapper .more-link,
    .content-area .sticky-posts-container.swiper-container .swiper-slide .thumbnail_container .more-link,
    .gbt_18_mt_posts_slider .swiper-container .swiper-slide .more-link,
    .single .post-navigation .post-nav .entry-thumbnail .more-link,
    .wp-block-getbowtied-vertical-slider .gbt_18_slide_link a
    {
        font-size: ' . MrTailor_Opt::getOption( 'body_text_font_size', 16 ) . 'px;
    }

    .content-area h1,
    .entry-content h1,
    .product_title,
    .woocommerce div.product .product_infos p.price,
    .woocommerce div.product .product_infos span.price,
    .woocommerce div.product .product_infos p.price ins,
    .woocommerce div.product .product_infos span.price ins,
    .content-area .search-results h2.search-item-title
    {
        font-size: ' . $h1_size . ';
    }

    .content-area h2,
    .entry-content h2,
    .edit-account legend,
    #minicart-offcanvas .widget_shopping_cart .total .amount,
    .woocommerce-cart .cart-collaterals .cart_totals table.shop_table tr.order-total .amount,
    .woocommerce-checkout table.woocommerce-checkout-review-order-table tfoot tr.order-total td .amount,
    .woocommerce-cart p.cart-empty,
    .woocommerce-wishlist .wishlist-empty,
    .content-area blockquote.wp-block-quote.is-style-large p,
    .gbt_18_default_slider .gbt_18_content .gbt_18_content_wrapper .gbt_18_slide_content .gbt_18_slide_content_item .gbt_18_slide_content_wrapper .summary .price,
    .gbt_18_lookbook_reveal_wrapper .gbt_18_distorsion_lookbook .gbt_18_distorsion_lookbook_item .gbt_18_distorsion_lookbook_content .gbt_18_text_wrapper .gbt_18_product_price
    {
        font-size: ' . $h2_size . ';
    }

    .content-area h3,
    .entry-content h3,
    .woocommerce-Reviews #review_form #reply-title,
    #minicart-offcanvas .woocommerce-mini-cart__empty-message,
    .woocommerce div.product .product_infos p.price del,
    .woocommerce div.product .product_infos span.price del,
    .content-area blockquote.wp-block-quote p,
    .gbt_18_expanding_grid .gbt_18_grid .gbt_18_expanding_grid_item .gbt_18_product_price,
    .main-navigation ul > li.menu-item-info-column .menu-item-title
    {
        font-size: ' . $h3_size . ';
    }

    .content-area h4,
    .entry-content h4,
    .woocommerce div.product .woocommerce-tabs ul.tabs li a,
    section.products.upsells > h2,
    section.products.related > h2,
    .cross-sells > h2,
    .content-area .wp-block-pullquote p
    {
        font-size: ' . $h4_size . ';
    }

    .content-area h5,
    .entry-content h5,
    .woocommerce-cart table.shop_table.cart tbody tr td.product-subtotal
    {
        font-size: ' . $h5_size . ';
    }

    .content-area p.has-drop-cap:first-letter,
    .entry-content p.has-drop-cap:first-letter
    {
        font-size: ' . $drop_cap_size . ';
    }

    @media all and (max-width: 1023px) {
        .entry-title,
        .page-title,
        .content-area h1.entry-title,
        .entry-content h1.entry-title,
        .content-area h1.page-title,
        .entry-content h1.page-title,
        .main-slider h2,
        .lookbook-first-slide-wrapper h2,
        .content-area h2.nothing-found-title,
        .content-area h2.entry-title.blog-post-title,
        .wp-block-getbowtied-vertical-slider .gbt_18_slide_title a,
        .wp-block-getbowtied-lookbook-reveal .gbt_18_content_top h2,
        .gbt_18_snap_look_book .gbt_18_hero_section_content .gbt_18_hero_title
        {
            font-size: ' . $h1_size . ';
        }

        .woocommerce div.product p.price,
        .woocommerce div.product span.price,
        .woocommerce div.product p.price ins,
        .woocommerce div.product span.price ins
        {
            font-size: ' . $h2_size . ';
        }

        .gbt_18_expanding_grid .gbt_18_grid .gbt_18_expanding_grid_item h2
        {
            font-size: ' . $h3_size . ' !important;
        }

        .woocommerce div.product p.price del,
        .woocommerce div.product span.price del
        {
            font-size: ' . $h4_size . ';
        }
    }
';
