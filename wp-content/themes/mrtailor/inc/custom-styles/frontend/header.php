<?php

$content_padding_top = MrTailor_Opt::getOption( 'logo_height', 60 ) + 2 * MrTailor_Opt::getOption( 'header_paddings', 20 ) + 70 + 30;
if ( '1' === MrTailor_Opt::getOption( 'header_layout', '2' ) ) {
    $content_padding_top += 70;
}

$custom_style .= '
    #site-top-bar,
    #site-navigation-top-bar .sf-menu ul
    {
        background: ' . MrTailor_Opt::getOption( 'top_bar_background_color', '#f9f9f9' ) . ';
    }

    #site-top-bar,
    #site-top-bar a:hover,
    #site-navigation-top-bar.main-navigation > ul > li > a:hover,
    #site-top-bar .select2-container--default .select2-selection--single span.select2-selection__rendered
    {
        color: ' . MrTailor_Opt::getOption( 'top_bar_typography', '#686868' ) . ';
    }

    #site-top-bar a
    {
        color: ' . MrTailor_Opt::getOption( 'top_bar_links_color', '#000000' ) . ';
    }

    #site-top-bar .site-social-icons-shortcode ul.mt_social_icons_list li.mt_social_icon a svg
    {
        fill: ' . MrTailor_Opt::getOption( 'top_bar_links_color', '#000000' ) . ';
    }

    #site-top-bar .site-social-icons-shortcode ul.mt_social_icons_list li.mt_social_icon a:hover svg
    {
        fill: ' . MrTailor_Opt::getOption( 'top_bar_typography', '#686868' ) . ';
    }

    .site-branding .site-logo-link img
    {
        max-height: ' . MrTailor_Opt::getOption( 'logo_height', 60 ) . 'px;
    }

    .top-headers-wrapper .site-branding .site-logo-alt-link img
    {
        max-height: ' . MrTailor_Opt::getOption( 'alt_logo_height', 40 ) . 'px;
    }

    .site-header
    {
        padding: ' . MrTailor_Opt::getOption( 'header_paddings', 20 ) . 'px 0;
    }

    .site-header,
    .site-header-sticky.sticky,
    #site-navigation,
    .shortcode_banner_simple_height_bullet span,
    .site-tools ul li.mobile-menu-button span.mobile-menu-text
    {
        font-size: ' . MrTailor_Opt::getOption( 'main_header_font_size', 16 ) . 'px;
    }

    .site-header,
    .site-header-sticky.sticky
    {
        background: ' . MrTailor_Opt::getOption( 'main_header_background_color', '#ffffff' ) . ';
    }

    .site-header,
    #site-navigation a,
    .site-header-sticky.sticky,
    .site-header-sticky.sticky a,
    .transparent_header.transparency_light .site-header-sticky.sticky #site-navigation a,
    .transparent_header.transparency_dark .site-header-sticky.sticky #site-navigation a,
    .top-headers-wrapper.sticky .site-header .site-title a,
    .transparent_header .top-headers-wrapper.sticky .site-header .site-title a,
    .site-tools ul li a,
    .shopping_bag_items_number,
    .wishlist_items_number,
    .site-title a,
    .widget_product_search .search-but-added,
    .widget_search .search-but-added
    {
        color: ' . MrTailor_Opt::getOption( 'main_header_font_color', '#000000' ) . ';
    }

    .site-branding
    {
        border-color: ' . MrTailor_Opt::getOption( 'main_header_font_color', '#000000' ) . ';
    }

    .blog .transparent_header .content-area,
    .single:not(.single-portfolio) .transparent_header .content-area,
    .archive .transparent_header .content-area,
    .page-template-default .transparent_header .content-area,
    .error404 .transparent_header .content-area
    {
        padding-top: ' . esc_html($content_padding_top) . 'px;
    }

    .transparent_header.transparency_light .site-header,
    .transparent_header.transparency_light #site-navigation a,
    .transparent_header.transparency_light .site-tools ul li a,
    .transparent_header.transparency_light .top-headers-wrapper:not(.sticky) .site-tools li span,
    .transparent_header.transparency_light .site-title a,
    .transparent_header.transparency_light .widget_product_search .search-but-added,
    .transparent_header.transparency_light .widget_search .search-but-added
    {
        color: ' . MrTailor_Opt::getOption( 'main_header_transparent_light_color', '#ffffff' ) . ';
    }

    .transparent_header.transparency_light .site-tools .site-branding
    {
        border-color:  ' . MrTailor_Opt::getOption( 'main_header_transparent_light_color', '#ffffff' ) . ';
    }

    .transparent_header.transparency_dark .site-header,
    .transparent_header.transparency_dark #site-navigation a,
    .transparent_header.transparency_dark .site-tools ul li a,
    .transparent_header.transparency_dark .top-headers-wrapper:not(.sticky) .site-tools li span,
    .transparent_header.transparency_dark .site-title a,
    .transparent_header.transparency_dark .widget_product_search .search-but-added,
    .transparent_header.transparency_dark .widget_search .search-but-added
    {
        color: ' . MrTailor_Opt::getOption( 'main_header_transparent_dark_color', '#000000' ) . ';
    }

    .transparent_header.transparency_dark .site-tools .site-branding
    {
        border-color:  ' . MrTailor_Opt::getOption( 'main_header_transparent_dark_color', '#000000' ) . ';
    }

    .main-navigation ul ul,
    .main-navigation ul ul ul,
    .main-navigation ul ul ul ul,
    .main-navigation ul ul ul ul ul
    {
        background: ' . MrTailor_Opt::getOption( 'navigation_bg', '#0d244c' ) . ';
    }

    .main-navigation ul ul li a,
    .main-navigation ul > li.menu-item-info-column .menu-item-title,
    .main-navigation ul > li.menu-item-info-column .menu-item-description {
        color: ' . MrTailor_Opt::getOption( 'navigation_link_color', '#ffffff' ) . ' !important;
    }

    .main-navigation ul ul li a:hover, .box-share-link:hover span
    {
        border-bottom-color: ' . MrTailor_Opt::getOption( 'navigation_link_color', '#ffffff' ) . ';
    }

    .top-headers-wrapper .site-header.full-header
    {
        border-bottom-color: rgba(' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'main_header_font_color', '#000000' ) ) . ', 0.13 );
    }
';
