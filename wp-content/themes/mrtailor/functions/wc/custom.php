<?php

/*
 * Track recent products.
 */
add_action( 'template_redirect', 'mrtailor_custom_track_product_view', 20 );
function mrtailor_custom_track_product_view() {
    if ( ! is_singular( 'product' ) ) {
        return;
    }

    global $post;

    if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) )
        $viewed_products = array();
    else
        $viewed_products = (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] );

    if ( ! in_array( $post->ID, $viewed_products ) ) {
        $viewed_products[] = $post->ID;
    }

    if ( sizeof( $viewed_products ) > 4 ) {
        array_shift( $viewed_products );
    }

    // Store for session only
    wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );

    return;
}

/*
 * Out of stock badge.
 */
function mrtailor_out_of_stock_badge() {

    global $product;

    if( !$product->is_in_stock() && !empty(MrTailor_Opt::getOption( 'out_of_stock_text' )) ) {
        ?>
        <p class="out-of-stock">
            <?php esc_html_e( MrTailor_Opt::getOption( 'out_of_stock_text', esc_html__( 'Out of stock', 'mr_tailor' ) ), 'mr_tailor' ); ?>
        </p>
        <?php
    }

    return;
}
