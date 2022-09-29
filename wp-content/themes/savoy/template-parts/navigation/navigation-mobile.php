<?php 
    global $nm_theme_options, $nm_globals;
?>
<div id="nm-mobile-menu" class="nm-mobile-menu">
    <div class="nm-mobile-menu-scroll">
        <div class="nm-mobile-menu-content">
            <div class="nm-row">

                <div class="nm-mobile-menu-top col-xs-12">
                    <ul id="nm-mobile-menu-top-ul" class="menu">
                        <?php if ( $nm_globals['cart_link'] ) : ?>
                        <li class="nm-mobile-menu-item-cart menu-item">
                            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" id="nm-mobile-menu-cart-btn">
                                <?php echo nm_get_cart_title(); ?>
                                <?php echo nm_get_cart_contents_count(); ?>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if ( $nm_globals['shop_search_header'] ) : ?>
                        <li class="nm-mobile-menu-item-search menu-item">
                            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                                <input type="text" id="nm-mobile-menu-shop-search-input" class="nm-mobile-menu-search" autocomplete="off" value="" name="s" placeholder="<?php esc_attr_e( 'Search products', 'woocommerce' ); ?>" />
                                <span class="nm-font nm-font-search"></span>
                                <input type="hidden" name="post_type" value="product" />
                            </form>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="nm-mobile-menu-main col-xs-12">
                    <ul id="nm-mobile-menu-main-ul" class="menu">
                        <?php
                            if ( has_nav_menu( 'mobile-menu' ) ) {
                                // Mobile menu
                                wp_nav_menu( array(
                                    'theme_location'	=> 'mobile-menu',
                                    'container'       	=> false,
                                    'fallback_cb'     	=> false,
                                    'after' 	 		=> '<span class="nm-menu-toggle"></span>',
                                    'items_wrap'      	=> '%3$s'
                                ) );
                            } else {
                                // Main menu
                                wp_nav_menu( array(
                                    'theme_location'	=> 'main-menu',
                                    'container'       	=> false,
                                    'fallback_cb'     	=> false,
                                    'after' 	 		=> '<span class="nm-menu-toggle"></span>',
                                    'items_wrap'      	=> '%3$s'
                                ) );

                                // Right menu                        
                                wp_nav_menu( array(
                                    'theme_location'	=> 'right-menu',
                                    'container'       	=> false,
                                    'fallback_cb'     	=> false,
                                    'after' 	 		=> '<span class="nm-menu-toggle"></span>',
                                    'items_wrap'      	=> '%3$s'
                                ) );
                            }
                        ?>
                    </ul>
                </div>

                <div class="nm-mobile-menu-secondary col-xs-12">
                    <ul id="nm-mobile-menu-secondary-ul" class="menu">
                        <?php
                            // Top bar menu
                            if ( $nm_theme_options['top_bar'] ) {
                                wp_nav_menu( array(
                                    'theme_location'	=> 'top-bar-menu',
                                    'container'       	=> false,
                                    'fallback_cb'     	=> false,
                                    'after' 	 		=> '<span class="nm-menu-toggle"></span>',
                                    'items_wrap'      	=> '%3$s'
                                ) );
                            }
                        ?>
                        <?php if ( nm_woocommerce_activated() && $nm_theme_options['menu_login'] ) : ?>
                        <li class="nm-menu-item-login menu-item">
                            <?php echo nm_get_myaccount_link( false ); ?>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>