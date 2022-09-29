<?php

$custom_style .= '
    #site-footer
    {
        background: ' . MrTailor_Opt::getOption( 'footer_background_color', '#ffffff' ) . ';
    }

    #site-footer,
    #site-footer .widget-title,
    #site-footer a:hover,
    #site-footer .star-rating span:before,
    #site-footer .star-rating span:before,
    #site-footer .widget_shopping_cart .buttons a:first-child:hover
    {
        color: ' . MrTailor_Opt::getOption( 'footer_texts_color', '#686868' ) . ';
    }

    #site-footer input[type="text"],
    #site-footer input[type="password"],
    #site-footer input[type="date"],
    #site-footer input[type="datetime"],
    #site-footer input[type="datetime-local"],
    #site-footer input[type="month"],
    #site-footer input[type="week"],
    #site-footer input[type="email"],
    #site-footer input[type="number"],
    #site-footer input[type="search"],
    #site-footer input[type="tel"],
    #site-footer input[type="time"],
    #site-footer input[type="url"],
    #site-footer textarea,
    #site-footer select,
    #site-footer input[type="checkbox"],
    #site-footer input[type="radio"],
    #site-footer .select2-container .select2-selection__rendered,
    #site-footer .select2-container--default span.select2-selection--multiple li.select2-selection__choice,
    #site-footer span.select2-container--default li.select2-results__option[aria-selected=true]
    {
        color: ' . MrTailor_Opt::getOption( 'footer_texts_color', '#686868' ) . ';
        background-color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'footer_texts_color', '#686868' ) ) . ', 0.1 );
    }

    #site-footer input[type="text"]:hover,
    #site-footer input[type="password"]:hover,
    #site-footer input[type="date"]:hover,
    #site-footer input[type="datetime"]:hover,
    #site-footer input[type="datetime-local"]:hover,
    #site-footer input[type="month"]:hover,
    #site-footer input[type="week"]:hover,
    #site-footer input[type="email"]:hover,
    #site-footer input[type="number"]:hover,
    #site-footer input[type="search"]:hover,
    #site-footer input[type="tel"]:hover,
    #site-footer input[type="time"]:hover,
    #site-footer input[type="url"]:hover,
    #site-footer textarea:hover,
    #site-footer select:hover,
    #site-footer input[type="checkbox"]:hover,
    #site-footer input[type="radio"]:hover,
    #site-footer .select2-container .select2-selection__rendered:hover
    {
        background-color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'footer_texts_color', '#686868' ) ) . ', 0.2 );
    }

    #site-footer a,
    #site-footer .widget_shopping_cart .buttons a:first-child
    {
        color: ' . MrTailor_Opt::getOption( 'footer_links_color', '#000000' ) . ';
    }

    #site-footer .widget_shopping_cart a.remove
    {
        color: ' . MrTailor_Opt::getOption( 'footer_links_color', '#000000' ) . ' !important;
    }

    #site-footer .widget_shopping_cart .buttons a:first-child
    {
        border-color: ' . MrTailor_Opt::getOption( 'footer_links_color', '#000000' ) . ';
    }

    #site-footer ul.product_list_widget li,
    footer#site-footer .site-footer-widget-area .widget-grid .widget
    {
        border-color: rgba(' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'footer_links_color', '#000000' ) ) . ', 0.13 ) !important;
    }

    #site-footer input::-webkit-input-placeholder,
    #site-footer textarea::-webkit-input-placeholder
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'footer_texts_color', '#686868' ) ) . ', 0.5 );
    }

    #site-footer input:-moz-placeholder,
    #site-footer textarea:-moz-placeholder /* Firefox 18- */
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'footer_texts_color', '#686868' ) ) . ', 0.5 );
    }

    #site-footer input::-moz-placeholder,
    #site-footer textarea::-moz-placeholder /* Firefox 19+ */
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'footer_texts_color', '#686868' ) ) . ', 0.5 );
    }

    #site-footer input:-ms-input-placeholder,
    #site-footer textarea:-ms-input-placeholder
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'footer_texts_color', '#686868' ) ) . ', 0.5 );
    }

    #site-footer input::placeholder,
    #site-footer textarea::placeholder
    {
        color: rgba( ' . mrtailor_hex2rgb( MrTailor_Opt::getOption( 'footer_texts_color', '#686868' ) ) . ', 0.5 );
    }
';
