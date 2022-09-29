<?php

function shortcode_lookbook_mixed($atts, $content = null) {

	wp_enqueue_style( 'swiper' );
	wp_enqueue_script( 'swiper' );

	wp_enqueue_style('mrtailor-lookbook-shortcode-styles');
	wp_enqueue_script('mrtailor-lookbook-shortcode-script');

	global $woocommerce;
	$sliderrandomid = rand();
    extract(shortcode_atts(array(
		'title' => '',
		'subtitle' => '',
		'bg_image' => '',
		'columns' => 3,
        'orderby' => 'date',
        'order' => 'desc',
		'ids' => ''
	), $atts));
	ob_start();

	$unique = uniqid();
    ?>

    <div class="woocommerce">

	    <div class="lookbook">

	        <div class="swiper-container column-<?php echo $columns; ?> swiper-<?php echo esc_attr($unique); ?>" data-id="<?php echo esc_attr($unique); ?>">

	            <div class="swiper-wrapper">

	                <?php

	                $args = array(
	                    'post_type'				=> 'product',
	                    'post_status' 			=> 'publish',
	                    'ignore_sticky_posts'	=> 1,
	                    'orderby' 				=> $orderby,
	                    'order' 				=> $order,
	                    'posts_per_page' 		=> -1,
						'tax_query' 			=> array(
	                    	array(
	                           'taxonomy' => 'product_visibility',
	                           'field'    => 'name',
	                           'terms'    => 'exclude-from-catalog',
	                           'operator' => 'NOT IN',

	                    	)
	                	)
	                );

	                if ( isset( $atts['ids'] ) ) {
	                    $ids = explode( ',', $atts['ids'] );
	                    $ids = array_map( 'trim', $ids );
	                    $args['post__in'] = $ids;
	                }

	                $products = new WP_Query( $args );

	                ?>

						<?php
						$first_slide_style = '';

						if (is_numeric($bg_image)) {
							$bg_image = wp_get_attachment_url($bg_image);
							$first_slide_style = 'background-image:url(' . $bg_image . ')';
						}
						?>

						<div class="swiper-slide first" style="<?php echo $first_slide_style; ?>">
							<div class="lookbook-first-slide-wrapper">
								<h2 class="lookbook-title"><?php echo $title; ?></h2>
								<h3 class="lookbook-subtitle"><?php echo $subtitle; ?></h3>
							</div>
						</div>

						<?php while ( $products->have_posts() ) : $products->the_post(); ?>

						<?php

						$product_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID ()), 'full' );

						$style = '';

						if (isset($product_thumbnail[0])) {
							$style = 'background-image:url(' . $product_thumbnail[0] . ')';
						}

						?>

							<div class="swiper-slide">
								<div class="lookbook_product_wrapper">
									<a href="<?php the_permalink(); ?>" class="lookbook_product_wrapper_inside" style="<?php echo $style; ?>"></a>
									<div class="lookbook_product_infos">
										<h4 class="lookbook_product_price"><?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?></h4>
										<h3 class="lookbook_product_title"><?php the_title(); ?></h3>
									</div>
									<a href="<?php the_permalink(); ?>" class="lookbook_product_overlay"></a>
								</div>
							</div>

						<?php endwhile; // end of the loop. ?>

	            </div>

				 <!-- Add Pagination -->
				<div class="pagination"></div>
				<span class="lookbook-arrow-left" href="#"></span>
	            <span class="lookbook-arrow-right" href="#"></span>

	        </div>

	    </div>

    </div>

	<?php

	wp_reset_query();
	$content = ob_get_contents();
	ob_end_clean();
	return $content;

}

add_shortcode("lookbook_mixed", "shortcode_lookbook_mixed");
