<?php
/**
 * Customizer controls.
 */
function mt_get_section_url() {

     switch($_POST['page']) {
         case 'shop':
             echo get_permalink( wc_get_page_id( 'shop' ) );
             break;
         case 'blog':
             echo get_permalink( get_option( 'page_for_posts' ) );
             break;
         case 'product':
             $args = array('orderby' => 'rand', 'limit' => 1);
             $product = wc_get_products($args);
             echo get_permalink( $product[0]->get_id() );
             break;
         default:
             echo get_home_url();
             break;
     }
     exit();
}

add_action( 'wp_ajax_mt_get_section_url', 'mt_get_section_url' );
