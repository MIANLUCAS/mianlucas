<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );

// Category Header Image.
$category_header_src = '';
if( is_shop() ) {
    $shop_page_id = get_option( 'woocommerce_shop_page_id' );
    $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $shop_page_id ), 'large' );
    if ( !empty( $large_image_url[0] ) ) {
        $category_header_src = $large_image_url[0];
    }
} else {
    $category_header_src = apply_filters( 'mrtailor_get_category_header_image', '' );
}

// Sidebar.
$shop_page_has_sidebar = false;
if( ( '1' === MrTailor_Opt::getOption( 'shop_layout', false ) || MrTailor_Opt::getOption( 'shop_layout', false ) ) && is_active_sidebar( 'catalog-widget-area' ) ) {
    $shop_page_has_sidebar = true;
}

// Page Title.
$page_title_option = 'off';
if( is_shop() && get_post_meta( get_option( 'woocommerce_shop_page_id' ), 'page_title_meta_box_check', true ) ) {
    $page_title_option = ( ( 'off' === get_post_meta( get_option( 'woocommerce_shop_page_id' ), 'page_title_meta_box_check', true ) ) || ( '' === get_post_meta( get_option( 'woocommerce_shop_page_id' ), 'page_title_meta_box_check', true ) ) ) ? true : false;

}

get_header( 'shop' );

?>

<div id="primary" class="content-area">

    <div class="woocommerce-products-header category_header <?php echo (esc_url($category_header_src) != "") ? 'with_featured_img' : ''; ?>" <?php echo (esc_url($category_header_src) != "") ? 'style="background-image:url('.$category_header_src.')"' : ''; ?>>
        <div class="row">
            <div class="large-8 large-centered columns">
                <?php
                /**
                 * Hook: woocommerce_before_main_content.
                 *
                 * @hooked woocommerce_breadcrumb - 20
                 * @hooked WC_Structured_Data::generate_website_data() - 30
                 */
                do_action( 'woocommerce_before_main_content' );
                ?>

                <?php if ( $page_title_option ) : ?>
                    <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
                <?php endif; ?>

                <?php
                /**
                 * Hook: woocommerce_archive_description.
                 *
                 * @hooked woocommerce_taxonomy_archive_description - 10
                 * @hooked woocommerce_product_archive_description - 10
                 */
                do_action( 'woocommerce_archive_description' );
                ?>
            </div>
        </div>
    </div>

    <div class="catalog-page <?php echo esc_attr($shop_page_has_sidebar) ? 'with-sidebar' : 'without-sidebar'; ?>">

        <div class="row">

            <?php if( 'products' != woocommerce_get_loop_display_mode() ) { ?>
                <div class="product-categories large-12 columns">
                    <?php

                    // Product categories.
                    woocommerce_product_loop_start();
                    echo woocommerce_maybe_show_product_subcategories();
                    woocommerce_product_loop_end();

                    ?>
                </div>
            <?php } ?>

            <?php if ( woocommerce_product_loop() ) { ?>

                <?php if ( wc_get_loop_prop( 'total' ) ) { ?>
                    <div class="shop_header large-12 columns">
                        <?php if ( is_active_sidebar( 'catalog-widget-area' ) ) { ?>
                            <a id="button_offcanvas_sidebar_left" class="filters_button">
                                <?php esc_html_e( 'Filter', 'woocommerce' )?>
                            </a>
                        <?php } ?>

                        <?php
                    	/**
                    	 * Hook: woocommerce_before_shop_loop.
                    	 *
                    	 * @hooked woocommerce_output_all_notices - 10
                    	 * @hooked woocommerce_result_count - 20
                    	 * @hooked woocommerce_catalog_ordering - 30
                    	 */
                    	do_action( 'woocommerce_before_shop_loop' );
                        ?>

                        <hr class="catalog_top_sep" />
                    </div>

                    <?php if( $shop_page_has_sidebar ) { ?>
                        <div class="large-3 columns show-for-large-up">
                            <div class="shop_sidebar wpb_widgetised_column">
                                <?php dynamic_sidebar( 'catalog-widget-area' ); ?>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="<?php echo esc_attr($shop_page_has_sidebar) ? 'large-9' : 'large-12'; ?> columns product-list-wrapper">

                        <div class="active_filters_ontop">
                            <?php the_widget( 'WC_Widget_Layered_Nav_Filters', 'title=' ); ?>
                        </div>

                        <?php

                        // Products
                    	woocommerce_product_loop_start();

                		while ( have_posts() ) {
                			the_post();

                			/**
                			 * Hook: woocommerce_shop_loop.
                			 */
                			do_action( 'woocommerce_shop_loop' );

                			wc_get_template_part( 'content', 'product' );
                		}

                    	woocommerce_product_loop_end();

                    	/**
                    	 * Hook: woocommerce_after_shop_loop.
                    	 *
                    	 * @hooked woocommerce_pagination - 10
                    	 */
                    	do_action( 'woocommerce_after_shop_loop' );
                        ?>
                    </div>
                <?php } ?>

            <?php
            } else {
            	/**
            	 * Hook: woocommerce_no_products_found.
            	 *
            	 * @hooked wc_no_products_found - 10
            	 */
            	do_action( 'woocommerce_no_products_found' );
            }

            /**
             * Hook: woocommerce_after_main_content.
             */
            do_action( 'woocommerce_after_main_content' );

            /**
             * Hook: woocommerce_sidebar.
             *
             * @hooked woocommerce_get_sidebar - 10
             */
            do_action( 'woocommerce_sidebar' );
            ?>

        </div>
    </div>
</div>

<?php

get_footer( 'shop' );
