<?php

/*
 * Sliders output.
 */
function mt_products_slider( $type = '', $products = null, $title = '', $columns = 4 ) {

    $unique = uniqid();
    ?>

    <?php if ( $products->have_posts() ) : ?>

    	<div class="<?php echo $type; ?>-wrapper wc-products-slider mt_ext_products_slider woocommerce" data-columns=<?php echo esc_attr($columns); ?>>

            <div class="mt_items_sliders_header">
                <div class="mt_items_sliders_title">
                    <?php echo $title ?>
                </div>
            </div>

            <div class="swiper-container swiper-<?php echo esc_attr($unique); ?>" data-id="<?php echo esc_attr($unique); ?>">

                <ul class="products swiper-wrapper">

                    <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                        <?php wc_get_template_part( 'content', 'product' ); ?>
                    <?php endwhile; ?>

        		</ul>

                <div class="swiper-pagination"></div>
                
            </div>

        </div>

    <?php endif; ?>

<?php
}

?>
