<?php
global $nm_globals, $nm_theme_options;

// Search link
if ( $nm_globals['shop_search_header'] ) :
?>
<li class="nm-menu-search menu-item"><a href="#" id="nm-menu-search-btn"><i class="nm-font nm-font-search"></i></a></li>
<?php endif; ?>
<?php
// Wishlist link
if ( $nm_globals['wishlist_enabled'] && $nm_theme_options['menu_wishlist'] ) :
?>
<li class="nm-menu-wishlist menu-item"><?php echo ( function_exists( 'nm_wishlist_get_header_link' ) ) ? nm_wishlist_get_header_link() : ''; ?></li>
<?php endif; ?>
<?php
// Login/My Account link
if ( nm_woocommerce_activated() && $nm_theme_options['menu_login'] ) :
?>
<li class="nm-menu-account menu-item"><?php echo nm_get_myaccount_link( true ); // Args: $is_header ?></li>
<?php endif; ?>
<?php
// Cart link
if ( $nm_globals['cart_link'] ) :
    $cart_menu_class = ( $nm_theme_options['menu_cart_icon'] ) ? 'has-icon' : 'no-icon';
    $cart_url = ( $nm_globals['cart_panel'] ) ? '#' : wc_get_cart_url();
?>
<li class="nm-menu-cart menu-item <?php echo esc_attr( $cart_menu_class ); ?>">
    <a href="<?php echo esc_url( $cart_url ); ?>" id="nm-menu-cart-btn">
        <?php echo nm_get_cart_title(); ?>
        <?php echo nm_get_cart_contents_count(); ?>
    </a>
</li>
<?php endif; ?>