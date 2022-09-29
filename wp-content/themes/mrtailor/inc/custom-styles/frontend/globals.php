<?php

$custom_style .= '
    .st-content
    {
        background-color: ' . MrTailor_Opt::getOption( 'main_bg_color', '#ffffff' ) . ';
    }

    @media only screen and (min-width: 40.063em) {
        .st-content {
            background-image: url( ' . MrTailor_Opt::getOption( 'main_bg_image', '' ) . ' );
        }
    }

    .slide-from-right,
    .slide-from-left,
    .woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
    .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle
    {
        background: ' . MrTailor_Opt::getOption( 'main_bg_color', '#ffffff' ) . ';
    }

    .wc-block-price-filter .wc-block-price-filter__range-input::-webkit-slider-thumb
    {
        background-color: ' . MrTailor_Opt::getOption( 'main_bg_color', '#ffffff' ) . ';
        border-color: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' ) . ' !important;
    }

    .wc-block-price-filter .wc-block-price-filter__range-input::-moz-range-thumb
    {
        background-color: ' . MrTailor_Opt::getOption( 'main_bg_color', '#ffffff' ) . ';
        border-color: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' ) . ' !important;
    }

    .wc-block-price-filter .wc-block-price-filter__range-input::-ms-thumb
    {
        background-color: ' . MrTailor_Opt::getOption( 'main_bg_color', '#ffffff' ) . ';
        border-color: ' . MrTailor_Opt::getOption( 'main_color', '#0d244c' ) . ' !important;
    }

    .woocommerce ul.products li.product-category .woocommerce-loop-category__title,
    .woocommerce ul.products li.product-category .woocommerce-loop-category__title mark,
    .wp-block-getbowtied-categories-grid.gbt_18_categories_grid_wrapper .gbt_18_categories_grid .gbt_18_category_grid_item .gbt_18_category_grid_item_title,
    .woocommerce .widget_layered_nav_filters ul li a,
    .woocommerce .widget_layered_nav ul li.chosen a,
    span.onsale,
    .woocommerce span.onsale,
    .wc-block-grid__product-onsale
    {
        color: ' . MrTailor_Opt::getOption( 'main_bg_color', '#ffffff' ) . ';
    }
';

if ( !MrTailor_Opt::getOption( 'breadcrumbs', true ) ) {
    $custom_style .= '
        .woocommerce .woocommerce-breadcrumb,
        .woocommerce-page .woocommerce-breadcrumb
        {
            display: none;
        }
    ';
}

if ( MrTailor_Opt::getOption( 'catalog_mode', false ) ) {
    $custom_style .= '
        form.cart div.quantity,
        form.cart button.single_add_to_cart_button,
        .archive .product_after_shop_loop_buttons
        {
            display: none !important;
        }
    ';
}
