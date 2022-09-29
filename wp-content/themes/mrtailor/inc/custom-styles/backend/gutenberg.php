<?php

$h1_size        = 2.488 * MrTailor_Opt::getOption( 'body_text_font_size', 16 ) . 'px';
$h2_size        = 2.074 * MrTailor_Opt::getOption( 'body_text_font_size', 16 ) . 'px';
$h3_size        = 1.728 * MrTailor_Opt::getOption( 'body_text_font_size', 16 ) . 'px';
$h4_size        = 1.44  * MrTailor_Opt::getOption( 'body_text_font_size', 16 ) . 'px';
$h5_size        = 1.2   * MrTailor_Opt::getOption( 'body_text_font_size', 16 ) . 'px';
$drop_cap_size  = 6.4   * MrTailor_Opt::getOption( 'body_text_font_size', 16 ) . 'px';

$bg_color_rgb   = mrtailor_hex2rgb( MrTailor_Opt::getOption( 'main_bg_color', '#ffffff' ) );

$custom_style .= '
    .editor-styles-wrapper
    {
        font-size: ' . MrTailor_Opt::getOption( 'body_text_font_size', 16 ) . 'px;
        background-color: ' . MrTailor_Opt::getOption( 'main_bg_color', '#ffffff' ) . ';
    }

    .edit-post-visual-editor h1.block-editor-block-list__block,
    .edit-post-visual-editor .block-editor-block-list__block h1,
    .edit-post-visual-editor h2.block-editor-block-list__block,
    .edit-post-visual-editor .block-editor-block-list__block h2,
    .edit-post-visual-editor h3.block-editor-block-list__block,
    .edit-post-visual-editor .block-editor-block-list__block h3,
    .edit-post-visual-editor h4.block-editor-block-list__block,
    .edit-post-visual-editor .block-editor-block-list__block h4,
    .edit-post-visual-editor h5.block-editor-block-list__block,
    .edit-post-visual-editor .block-editor-block-list__block h5,
    .edit-post-visual-editor h6.block-editor-block-list__block,
    .edit-post-visual-editor .block-editor-block-list__block h6,
    .wp-block-cover .wp-block-cover-text,
    .editor-post-title__block .editor-post-title__input,
    .gbt_18_mt_posts_grid .gbt_18_mt_posts_grid_title,
    .wp-block-file .wp-block-file__button,
    .wp-block-button .wp-block-button__link,
    .gbt_18_mt_banner .gbt_18_mt_banner_title,
    .wp-block-button a,
    .wp-block-pullquote p,
    .wp-block-quote__citation,
    .wp-block-pullquote .wp-block-pullquote__citation,
    .wc-block-grid__product-title,
    .wc-block-grid__product-add-to-cart,
    .wp-block-latest-posts li a, .wp-block-latest-posts li a,
    .wp-block-latest-posts li .wp-block-latest-posts__post-date,
    .editor-styles-wrapper .wp-block-pullquote p,
    .edit-post-visual-editor.editor-styles-wrapper a,
    .editor-styles-wrapper .wp-block-file .wp-block-file__textlink,
    .editor-styles-wrapper table tr th,
    .editor-styles-wrapper .wc-block-grid .wc-block-grid__product-onsale,
    .editor-styles-wrapper .block-editor-block-list__block .amount,
    .editor-styles-wrapper .wc-block-product-search .wc-block-product-search__label,
    .editor-styles-wrapper .wc-block-grid .wc-block-grid__product-price,
    .editor-styles-wrapper .wc-block-pagination .wc-block-pagination-page,
    .editor-styles-wrapper .wc-block-sort-select__label,
    .editor-styles-wrapper .wp-block-search .wp-block-search__label,
    .wc-block-attribute-filter .wc-block-attribute-filter-list li label,
    .editor-styles-wrapper ul.wc-block-active-filters-list li.wc-block-active-filters-list-item,
    .editor-styles-wrapper .block-editor-block-list__block[data-type="woocommerce/attribute-filter"] ul li.show-more button,
    .editor-styles-wrapper .block-editor-block-list__block[data-type="woocommerce/active-filters"] .wc-block-active-filters__clear-all,
    .editor-styles-wrapper blockquote.wp-block-quote p,
    .gbt_18_mt_editor_posts_slider .gbt_18_mt_editor_posts_slider_item_text .gbt_18_mt_editor_posts_slider_item_date,
    .editor-styles-wrapper .block-editor-block-list__block[data-type="getbowtied/lookbook-reveal"] .gbt_18_editor_lookbook_product_button,
    .editor-styles-wrapper .block-editor-block-list__block[data-type="getbowtied/lookbook-shop-by-outfit-product"] .gbt_18_editor_lookbook_sts_product_content .gbt_18_lookbook_sts_products_wrapper .gbt_18_lookbook_sts_product_button,
    .editor-styles-wrapper .block-editor-block-list__block[data-type="getbowtied/products-carousel"] ul.gbt_18_carousel_products li.gbt_18_carousel_product
    {
        font-family: ' . MrTailor_Fonts::get_main_font() . ';
    }

    .edit-post-visual-editor .block-editor-block-list__block,
    .edit-post-visual-editor .block-editor-block-list__block p,
    .block-editor-block-list__block .wc-block-product-search__field,
    .block-editor-block-list__block .wc-block-sort-select__select,
    .editor-styles-wrapper .wp-block-search .wp-block-search__input,
    .editor-styles-wrapper .block-editor-block-list__block[data-type="woocommerce/price-filter"] .wc-block-price-filter__controls .wc-block-price-filter__amount,
    div.wc-block-product-categories__dropdown select,
    .block-editor-default-block-appender textarea.block-editor-default-block-appender__content
    {
        font-family: ' . MrTailor_Fonts::get_secondary_font() . ';
    }

    .edit-post-visual-editor .block-editor-block-list__block,
    .edit-post-visual-editor .block-editor-block-list__block a,
    .edit-post-visual-editor pre.wp-block-verse,
    .wp-block-calendar tbody td,
    .editor-styles-wrapper .wc-block-grid ul.wc-block-grid__products li.wc-block-grid__product .wc-block-grid__product-price,
    .editor-styles-wrapper .wc-block-grid ul.wc-block-grid__products li.wc-block-grid__product .wc-block-grid__product-price *,
    .gbt_18_mt_editor_posts_slider .gbt_18_mt_editor_posts_slider_item_text .gbt_18_mt_editor_posts_slider_item_date,
    .editor-styles-wrapper .block-editor-block-list__block[data-type="getbowtied/lookbook-shop-by-outfit-product"] .gbt_18_editor_lookbook_sts_product_content .gbt_18_lookbook_sts_products_wrapper .gbt_18_lookbook_sts_product_price
    {
        color: ' . MrTailor_Opt::getOption( 'body_color', '#222222' ) . ';
    }

    .wp-block-latest-posts li .wp-block-latest-posts__post-date
    {
        color: rgba(' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'body_color', '#222222' ) ) . ', 0.45 );
    }

    .edit-post-visual-editor .editor-post-title__block .editor-post-title__input,
    .block-editor-block-list__block[data-type="getbowtied/products-slider"] .gbt_18_editor_slide_title,
    .block-editor-block-list__block[data-type="getbowtied/lookbook-reveal"] .gbt_18_lookbook_reveal_product_wrapper .gbt_18_editor_lookbook_product_content .gbt_18_editor_lookbook_product_content_left .gbt_18_editor_lookbook_product_content_left_inner_top h2.gbt_18_editor_lookbook_product_title,
    .block-editor-block-list__block[data-type="getbowtied/lookbook-reveal"][data-align="full"] .gbt_18_lookbook_reveal_product_wrapper .gbt_18_editor_lookbook_product_content .gbt_18_editor_lookbook_product_content_left .gbt_18_editor_lookbook_product_content_left_inner_top h2.gbt_18_editor_lookbook_product_title,
    .block-editor-block-list__block[data-type="getbowtied/lookbook-reveal"][data-align="wide"] .gbt_18_lookbook_reveal_product_wrapper .gbt_18_editor_lookbook_product_content .gbt_18_editor_lookbook_product_content_left .gbt_18_editor_lookbook_product_content_left_inner_top h2.gbt_18_editor_lookbook_product_title,
    .editor-styles-wrapper .block-editor-block-list__block[data-type="getbowtied/lookbook-shop-by-outfit"] .gbt_18_hero_section_title
    {
        font-size: ' . MrTailor_Opt::getOption( 'h1_font_size', 55 ) . 'px;
    }

    .edit-post-visual-editor.editor-styles-wrapper .block-editor-block-list__block,
    .wp-block-pullquote .wp-block-pullquote__citation,
    .block-editor-block-list__block .wc-block-product-search__field,
    .block-editor-block-list__block .wc-block-sort-select__select,
    .editor-styles-wrapper .wp-block-search .wp-block-search__input,
    div.wc-block-product-categories__dropdown select,
    .editor-styles-wrapper .block-editor-block-list__block[data-type="woocommerce/price-filter"] .wc-block-price-filter__controls input.wc-block-price-filter__amount,
    .editor-styles-wrapper .block-editor-block-list__block[data-type="getbowtied/products-slider"] .gbt_18_editor_default_slider .gbt_18_editor_slide_link
    {
        font-size: ' . MrTailor_Opt::getOption( 'body_text_font_size', 16 ) . 'px;
    }

    .edit-post-visual-editor.editor-styles-wrapper .block-editor-block-list__block h1,
    .edit-post-visual-editor.editor-styles-wrapper h1.block-editor-block-list__block
    {
        font-size: ' . $h1_size . ';
    }

    .edit-post-visual-editor.editor-styles-wrapper .block-editor-block-list__block h2,
    .edit-post-visual-editor.editor-styles-wrapper h2.block-editor-block-list__block,
    .editor-styles-wrapper .wp-block-quote.is-style-large p,
    .block-editor-block-list__block[data-type="getbowtied/lookbook-reveal"] .gbt_18_lookbook_reveal_product_wrapper .gbt_18_editor_lookbook_product_content .gbt_18_editor_lookbook_product_content_left .gbt_18_editor_lookbook_product_content_left_inner_bottom .gbt_18_editor_lookbook_product_price,
    .block-editor-block-list__block[data-type="getbowtied/scattered-product-list"] .gbt_18_expanding_grid_wrapper ul.gbt_18_expanding_grid_products li.gbt_18_grid_product .gbt_18_grid_product_title
    {
        font-size: ' . $h2_size . ';
    }

    .edit-post-visual-editor.editor-styles-wrapper .block-editor-block-list__block h3,
    .edit-post-visual-editor.editor-styles-wrapper h3.block-editor-block-list__block,
    .editor-styles-wrapper .wp-block-quote p,
    .block-editor-block-list__block[data-type="getbowtied/scattered-product-list"] .gbt_18_expanding_grid_wrapper ul.gbt_18_expanding_grid_products li.gbt_18_grid_product .gbt_18_grid_product_price
    {
        font-size: ' . $h3_size . ';
    }

    .edit-post-visual-editor.editor-styles-wrapper .block-editor-block-list__block h4,
    .edit-post-visual-editor.editor-styles-wrapper h4.block-editor-block-list__block,
    .editor-styles-wrapper .wp-block-pullquote p
    {
        font-size: ' . $h4_size . ';
    }

    .edit-post-visual-editor.editor-styles-wrapper .block-editor-block-list__block h5,
    .edit-post-visual-editor.editor-styles-wrapper h5.block-editor-block-list__block
    {
        font-size: ' . $h5_size . ';
    }

    .edit-post-visual-editor.editor-styles-wrapper p.has-drop-cap:first-letter
    {
        font-size: ' . $drop_cap_size . ';
    }

    @media all and (max-width: 1023px) {
        .editor-post-title__block.editor-styles-wrapper .editor-post-title__input,
        .block-editor-block-list__block[data-type="getbowtied/products-slider"] .gbt_18_editor_slide_title,
        .block-editor-block-list__block[data-type="getbowtied/lookbook-reveal"] .gbt_18_lookbook_reveal_product_wrapper .gbt_18_editor_lookbook_product_content .gbt_18_editor_lookbook_product_content_left .gbt_18_editor_lookbook_product_content_left_inner_top h2.gbt_18_editor_lookbook_product_title,
        .block-editor-block-list__block[data-type="getbowtied/lookbook-reveal"][data-align="full"] .gbt_18_lookbook_reveal_product_wrapper .gbt_18_editor_lookbook_product_content .gbt_18_editor_lookbook_product_content_left .gbt_18_editor_lookbook_product_content_left_inner_top h2.gbt_18_editor_lookbook_product_title,
        .block-editor-block-list__block[data-type="getbowtied/lookbook-reveal"][data-align="wide"] .gbt_18_lookbook_reveal_product_wrapper .gbt_18_editor_lookbook_product_content .gbt_18_editor_lookbook_product_content_left .gbt_18_editor_lookbook_product_content_left_inner_top h2.gbt_18_editor_lookbook_product_title,
        .editor-styles-wrapper .block-editor-block-list__block[data-type="getbowtied/lookbook-shop-by-outfit"] .gbt_18_hero_section_title
        {
            font-size: ' . $h1_size . ';
        }

        .block-editor-block-list__block[data-type="getbowtied/scattered-product-list"] .gbt_18_expanding_grid_wrapper ul.gbt_18_expanding_grid_products li.gbt_18_grid_product .gbt_18_grid_product_title
        {
            font-size: ' . $h3_size . ' !important;
        }
    }

    .edit-post-visual-editor .block-editor-block-list__block a:hover,
    .editor-styles-wrapper .wc-block-grid ul.wc-block-grid__products li.wc-block-grid__product div.wc-block-grid__product-rating .wc-block-grid__product-rating__stars span:before,
    .editor-styles-wrapper .block-editor-block-list__block[data-type="woocommerce/attribute-filter"] ul li.show-more button,
    .editor-styles-wrapper .block-editor-block-list__block[data-type="woocommerce/active-filters"] .wc-block-active-filters__clear-all,
    .wc-block-review-list-item__rating>.wc-block-review-list-item__rating__stars span:before,
    .editor-styles-wrapper .wc-block-grid ul.wc-block-grid__products li.wc-block-grid__product div.wc-block-grid__product-rating .star-rating span:before,
    .editor-styles-wrapper .wc-block-grid ul.wc-block-grid__products li.wc-block-grid__product .wc-block-grid__product-add-to-cart a,
    .editor-styles-wrapper .block-editor-block-list__block[data-type="getbowtied/lookbook-shop-by-outfit-product"] .gbt_18_editor_lookbook_sts_product_content .gbt_18_lookbook_sts_products_wrapper .gbt_18_lookbook_sts_product_button,
    .editor-styles-wrapper .block-editor-block-list__block[data-type="getbowtied/products-carousel"] ul.gbt_18_carousel_products li.gbt_18_carousel_product .gbt_18_carousel_product_button
    {
        color: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' ) . ';
    }

    .wc-block-review-list-item__rating>.wc-block-review-list-item__rating__stars:before
    {
        color: rgba(' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'body_color', '#222222' ) ) . ', 0.35 );
    }

    .edit-post-visual-editor.editor-styles-wrapper a,
    .edit-post-visual-editor.editor-styles-wrapper .editor-post-title__block .editor-post-title__input,
    .edit-post-visual-editor h1.block-editor-block-list__block,
    .edit-post-visual-editor .block-editor-block-list__block h1,
    .edit-post-visual-editor h2.block-editor-block-list__block,
    .edit-post-visual-editor .block-editor-block-list__block h2,
    .edit-post-visual-editor h3.block-editor-block-list__block,
    .edit-post-visual-editor .block-editor-block-list__block h3,
    .edit-post-visual-editor h4.block-editor-block-list__block,
    .edit-post-visual-editor .block-editor-block-list__block h4,
    .edit-post-visual-editor h5.block-editor-block-list__block,
    .edit-post-visual-editor .block-editor-block-list__block h5,
    .edit-post-visual-editor h6.block-editor-block-list__block,
    .edit-post-visual-editor .block-editor-block-list__block h6,
    .wc-block-grid__product-title,
    .wc-block-grid__product-price,
    .editor-styles-wrapper .wp-block-quote p,
    .editor-styles-wrapper .wp-block-file .wp-block-file__textlink,
    .editor-styles-wrapper table tr th,
    .wc-block-grid ul.wc-block-grid__products li.wc-block-grid__product .wc-block-grid__product-add-to-cart a.wp-block-button__link:hover,
    .editor-styles-wrapper .wc-block-pagination .wc-block-pagination-page,
    .gbt_18_mt_editor_posts_slider .swiper-button-next:before,
    .gbt_18_mt_editor_posts_slider .swiper-button-prev:before,
    .gbt_18_mt_posts_grid .gbt_18_mt_editor_posts_grid_wrapper .gbt_18_mt_editor_posts_grid_title
    {
        color: ' . MrTailor_Opt::getOption( 'headings_color', '#000000' ) . ';
    }

    .block-editor-default-block-appender textarea.block-editor-default-block-appender__content
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'headings_color', '#000000' ) ) . ', 0.5 );
    }

    .block-editor-rich-text__editable [data-rich-text-placeholder]
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'headings_color', '#000000' ) ) . ', 0.75 );
    }

    .editor-styles-wrapper .editor-post-title__block .editor-post-title__input::-webkit-input-placeholder
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'headings_color', '#000000' ) ) . ', 0.5 );
    }

    .editor-styles-wrapper .editor-post-title__block .editor-post-title__input:-moz-placeholder /* Firefox 18- */
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'headings_color', '#000000' ) ) . ', 0.5 );
    }

    .editor-styles-wrapper .editor-post-title__block .editor-post-title__input::-moz-placeholder /* Firefox 19+ */
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'headings_color', '#000000' ) ) . ', 0.5 );
    }

    .editor-styles-wrapper .editor-post-title__block .editor-post-title__input:-ms-input-placeholder
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'headings_color', '#000000' ) ) . ', 0.5 );
    }

    .editor-styles-wrapper .editor-post-title__block .editor-post-title__input::placeholder
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'headings_color', '#000000' ) ) . ', 0.5 );
    }

    .gbt_18_mt_editor_posts_slider .swiper-pagination-bullet:first-child
    {
        background-color: ' . MrTailor_Opt::getOption( 'headings_color', '#000000' ) . ';
    }

    .block-editor-block-list__block[data-type="woocommerce/active-filters"] ul.wc-block-active-filters-list li.wc-block-active-filters-list-item,
    .editor-styles-wrapper .wc-block-grid ul.wc-block-grid__products li.wc-block-grid__product .wc-block-grid__product-onsale,
    .editor-styles-wrapper .block-editor-block-list__block[data-type="getbowtied/categories-grid"] .wp-block-getbowtied-categories-grid .gbt_18_editor_category_grid_item:hover .gbt_18_editor_category_grid_item_title
    {
        background-color: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' ) . ';
    }

    .block-editor-block-list__block[data-type="woocommerce/price-filter"] .wc-block-price-filter__range-input-wrapper .wc-block-price-filter__range-input-progress {
        --range-color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'body_color', '#222222' ) ) . ', 0.45 );
    }

    .block-editor-block-list__block[data-type="woocommerce/price-filter"] .wc-block-price-filter__range-input::-webkit-slider-thumb
    {
        background-color: ' . MrTailor_Opt::getOption( 'main_bg_color', '#ffffff' ) . ';
        border-color: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' ) . ' !important;
    }

    .block-editor-block-list__block[data-type="woocommerce/price-filter"] .wc-block-price-filter__range-input::-moz-range-thumb
    {
        background-color: ' . MrTailor_Opt::getOption( 'main_bg_color', '#ffffff' ) . ';
        border-color: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' ) . ' !important;
    }

    .block-editor-block-list__block[data-type="woocommerce/price-filter"] .wc-block-price-filter__range-input::-ms-thumb
    {
        background-color: ' . MrTailor_Opt::getOption( 'main_bg_color', '#ffffff' ) . ';
        border-color: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' ) . ' !important;
    }

    .editor-styles-wrapper .wp-block-quote
    {
        border-left-color:  ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' ) . ';
    }

    .editor-styles-wrapper .block-editor-block-list__block .wp-block-file .wp-block-file__button,
    .editor-styles-wrapper .wp-block-search .wp-block-search__button
    {
        background-color:  ' . MrTailor_Opt::getOption( 'headings_color', '#000000' ) . ';
        color: ' . MrTailor_Opt::getOption( 'main_bg_color', '#ffffff' ) . ';
    }

    .editor-styles-wrapper .block-editor-block-list__block[data-type="getbowtied/categories-grid"] .wp-block-getbowtied-categories-grid .gbt_18_editor_category_grid_item .gbt_18_editor_category_grid_item_title,
    .editor-styles-wrapper .block-editor-block-list__block[data-type="getbowtied/products-slider"] .gbt_18_editor_default_slider .gbt_18_editor_add_to_cart
    {
        background-color:  ' . MrTailor_Opt::getOption( 'body_color', '#222222' ) . ';
        color: ' . MrTailor_Opt::getOption( 'main_bg_color', '#ffffff' ) . ';
    }

    .editor-styles-wrapper .wc-block-grid .wc-block-grid__product-onsale,
    .editor-styles-wrapper .wc-block-pagination .wc-block-pagination-page.wc-block-pagination-page--active
    {
        background-color: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' )  . ';
        color: ' . MrTailor_Opt::getOption( 'main_bg_color', '#ffffff' ) . ' !important;
    }

    .editor-styles-wrapper .wc-block-grid ul.wc-block-grid__products li.wc-block-grid__product .wc-block-grid__product-onsale
    {
        background-image:  ' . MrTailor_Icons::get_icon_url( '24', '24', $bg_color_rgb, 'tag' ) . ';
    }

    .editor-styles-wrapper .block-editor-block-list__block .wc-block-product-search__field,
    .editor-styles-wrapper .block-editor-block-list__block .wc-block-sort-select__select,
    .editor-styles-wrapper .wp-block-search .wp-block-search__input,
    div.wc-block-product-categories__dropdown select,
    .editor-styles-wrapper .block-editor-block-list__block[data-type="woocommerce/price-filter"] .wc-block-price-filter__controls input.wc-block-price-filter__amount
    {
        color: ' . MrTailor_Opt::getOption( 'headings_color', '#000000' ) . ' ;
        background-color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'headings_color', '#000000' ) ) . ', 0.1 );
    }

    .editor-styles-wrapper .block-editor-block-list__block .wc-block-sort-select__select
    {
        color: ' . MrTailor_Opt::getOption( 'headings_color', '#000000' ) . ' !important;
    }
';
