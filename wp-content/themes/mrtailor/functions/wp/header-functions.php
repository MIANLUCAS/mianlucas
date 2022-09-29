<?php

/*
 * Get header tool icons
 */
function mrtailor_get_header_tool_icons() {
    global $yith_wcwl, $woocommerce;
    ?>
    <ul>

        <?php if ( MrTailor_Opt::getOption( 'main_header_search_bar' ) ) { ?>
            <li class="search-button">
                <a href="javascript:void(0)">
                    <?php if ( MrTailor_Opt::getOption( 'main_header_search_bar_icon' ) != "" ) { ?>
                        <img class="getbowtied-icon-search" src="<?php echo esc_url(MrTailor_Opt::getOption( 'main_header_search_bar_icon' )); ?>">
                    <?php } else { ?>
                        <span class="search_icon"></span>
                    <?php } ?>
                </a>
            </li>
        <?php } ?>

        <?php if ( MrTailor_Opt::getOption( 'main_header_my_account' ) ) { ?>
            <li class="myaccount-button">
                <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>">
                    <?php if ( MrTailor_Opt::getOption( 'main_header_my_account_icon' ) != "" ) { ?>
                        <img class="getbowtied-icon-myaccount" src="<?php echo esc_url(MrTailor_Opt::getOption( 'main_header_my_account_icon' )); ?>">
                    <?php } else { ?>
                        <span class="myaccount_icon"></span>
                    <?php } ?>
                </a>
            </li>
        <?php } ?>

        <?php if( MT_WISHLIST_IS_ACTIVE && MrTailor_Opt::getOption( 'main_header_wishlist' ) ) { ?>
            <li class="wishlist-button">
                <a href="<?php echo esc_url($yith_wcwl->get_wishlist_url()); ?>">
                    <?php if ( MrTailor_Opt::getOption( 'main_header_wishlist_icon' ) != "" ) { ?>
                        <img src="<?php echo esc_url(MrTailor_Opt::getOption( 'main_header_wishlist_icon' )); ?>">
                    <?php } else { ?>
                        <span class="wishlist_icon"></span>
                    <?php } ?>
                    <span class="wishlist_items_number"><?php echo yith_wcwl_count_products(); ?></span>
                </a>
            </li>
        <?php } ?>

        <?php if( MT_WOOCOMMERCE_IS_ACTIVE && MrTailor_Opt::getOption( 'main_header_shopping_bag' ) && !MrTailor_Opt::getOption( 'catalog_mode' ) ) { ?>
            <li class="shopping-bag-button" class="right-off-canvas-toggle">
                <a href="javascript:void(0)">
                    <?php if ( MrTailor_Opt::getOption( 'main_header_shopping_bag_icon' ) != "" ) { ?>
                        <img src="<?php echo esc_url(MrTailor_Opt::getOption( 'main_header_shopping_bag_icon' )); ?>">
                    <?php } else { ?>
                        <span class="shopping_cart_icon"></span>
                    <?php } ?>
                    <span class="shopping_bag_items_number"><?php echo esc_html($woocommerce->cart->cart_contents_count); ?></span>
                </a>
            </li>
        <?php } ?>

    </ul>
    <?php

    return;
}


/*
 * Get header logos
 */
function mrtailor_get_logo() {
    $transparency = mrtailor_get_transparency_options();
    $site_logo = ( '' != MrTailor_Opt::getOption( 'site_logo' ) ) ? MrTailor_Opt::getOption( 'site_logo' ) : '';
    if ($transparency['header_transparency_class'] == "transparent_header")	{
        if ( ($transparency['transparency_scheme'] == "transparency_light") && (MrTailor_Opt::getOption( 'light_transparent_header_logo' ) != "") ) {
            $site_logo = MrTailor_Opt::getOption( 'light_transparent_header_logo' );
        }
        if ( ($transparency['transparency_scheme'] == "transparency_dark") && (MrTailor_Opt::getOption( 'dark_transparent_header_logo' ) != "") ) {
            $site_logo = MrTailor_Opt::getOption( 'dark_transparent_header_logo' );
        }
    }

    if (is_ssl()) {
        $site_logo = str_replace("http://", "https://", $site_logo);
    }

    if( !empty($site_logo) ) { ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
            <img class="site-logo" src="<?php echo esc_url($site_logo); ?>" title="<?php bloginfo( 'description' ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
        </a>
    <?php } else { ?>
        <div class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></div>
    <?php }

    return;
}

function mrtailor_get_alt_logo() {
    $site_logo = ( '' != MrTailor_Opt::getOption( 'site_logo' ) ) ? MrTailor_Opt::getOption( 'site_logo' ) : '';
    $site_alt_logo = ( '' != MrTailor_Opt::getOption( 'sticky_header_logo' ) ) ? MrTailor_Opt::getOption( 'sticky_header_logo' ) : $site_logo;

    if (is_ssl()) {
        $site_alt_logo = str_replace("http://", "https://", $site_alt_logo);
    }

    if( !empty($site_alt_logo) ) { ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
            <img class="site-alt-logo" src="<?php echo esc_url($site_alt_logo); ?>" title="<?php bloginfo( 'description' ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
        </a>
    <?php } else { ?>
        <div class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></div>
    <?php }

    return;
}

/*
 * Get header menu
 */
function mrtailor_get_header_menu() {
    if( has_nav_menu('main-navigation') ) {
        ?>
        <nav id="site-navigation" class="main-navigation" role="navigation">
            <?php

                $args = array(
                    'theme_location'  => 'main-navigation',
                    'fallback_cb'     => false,
                    'container'       => false,
                    'items_wrap'      => '<ul id="%1$s">%3$s</ul>'
                );

                if ( class_exists('rc_scm_walker')) {
                    $walker = new rc_scm_walker;
                    $args['walker'] = $walker;
                }

                wp_nav_menu( $args );

            ?>
        </nav>
        <?php
    }

    return;
}

/*
 * Get header transparency info
 */
function mrtailor_get_transparency_options() {

    $header_transparency_class = "normal_header";
    $transparency_scheme = "";

    if( '2' !== MrTailor_Opt::getOption( 'header_layout' ) ) {

        if ( MrTailor_Opt::getOption( 'main_header_background_transparency' ) ) {
            $header_transparency_class = "transparent_header";
        }

        $transparency_scheme = MrTailor_Opt::getOption( 'main_header_transparency_scheme' );

        $page_id = "";
        if ( is_single() || is_page() ) {
            $page_id = get_the_ID();
        } else if ( is_home() ) {
            $page_id = get_option('page_for_posts');
        }

        if ( (get_post_meta($page_id, 'page_header_transparency', true)) && (get_post_meta($page_id, 'page_header_transparency', true) != "inherit") ) {
            $header_transparency_class = "transparent_header";
            $transparency_scheme = get_post_meta( $page_id, 'page_header_transparency', true );
        }

        if ( (get_post_meta($page_id, 'page_header_transparency', true)) && (get_post_meta($page_id, 'page_header_transparency', true) == "no_transparency") ) {
            $header_transparency_class = "normal_header";
            $transparency_scheme = "";
        }

        if (class_exists('WooCommerce'))
        {
            if (is_shop())
            {
                if ( (get_post_meta(get_option( 'woocommerce_shop_page_id' ), 'page_header_transparency', true)) && (get_post_meta(get_option( 'woocommerce_shop_page_id' ), 'page_header_transparency', true) != "inherit") ) {
                    $header_transparency_class = "transparent_header";
                    $transparency_scheme = get_post_meta( get_option( 'woocommerce_shop_page_id' ), 'page_header_transparency', true );

                } else {
                    $header_transparency_class = "normal_header";
                    $transparency_scheme = "";
                }

                if ( (get_post_meta(get_option( 'woocommerce_shop_page_id' ), 'page_header_transparency', true)) && (get_post_meta(get_option( 'woocommerce_shop_page_id' ), 'page_header_transparency', true) == "no_transparency") ) {
                    $header_transparency_class = "normal_header";
                    $transparency_scheme = "";
                }
            }

            if ( is_product_category() && is_woocommerce() )
            {
                if ( MrTailor_Opt::getOption( 'shop_category_header_transparency_scheme' ) == 'inherit' )
                {
                    // do nothing, inherit
                }
                else if ( MrTailor_Opt::getOption( 'shop_category_header_transparency_scheme' ) == 'no_transparency' )
                {
                    $header_transparency_class = "";
                    $transparency_scheme = "";
                }
                else
                {
                    $header_transparency_class = "transparent_header";
                    $transparency_scheme = MrTailor_Opt::getOption( 'shop_category_header_transparency_scheme' );
                }
            }
        }
    }

    return array( 'header_transparency_class' => $header_transparency_class, 'transparency_scheme' => $transparency_scheme );
}
