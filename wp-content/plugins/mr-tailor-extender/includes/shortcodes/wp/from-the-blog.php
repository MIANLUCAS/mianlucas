<?php

// [from_the_blog]
function mt_ext_shortcode_from_the_blog($atts, $content = null) {

    wp_enqueue_style('swiper');
    wp_enqueue_script('swiper');

	wp_enqueue_script( 'mrtailor-from-the-blog-slider-script' );
	wp_enqueue_style('mrtailor-from-the-blog-slider-shortcode-styles');

    extract(shortcode_atts(array(
		"posts" => '9',
		"category" => ''
    ), $atts));

    ob_start();

    $args = array(
		'post_status' => 'publish',
		'post_type' => 'post',
		'category_name' => $category,
		'posts_per_page' => $posts
    );

    $recentPosts = new WP_Query( $args );

    $unique = uniqid();

    ?>

    <div class="from-the-blog-wrapper swiper-container swiper-<?php echo esc_attr($unique); ?>" data-id="<?php echo esc_attr($unique); ?>">

        <div class="swiper-wrapper">

            <?php if ( $recentPosts->have_posts() ) : ?>

                <?php while ( $recentPosts->have_posts() ) : $recentPosts->the_post(); ?>

                    <?php $post_format = get_post_format(get_the_ID()); ?>

                    <div class="swiper-slide from_the_blog_item <?php if ( !has_post_thumbnail()) : ?>no_thumb<?php endif; ?>">

						<a class="from_the_blog_img_link" href="<?php the_permalink() ?>">
							<span class="from_the_blog_overlay"></span>

							<?php if ( has_post_thumbnail()) :
								$image_id = get_post_thumbnail_id();
								$image_url = wp_get_attachment_image_src($image_id,'large', true);
							?>
								<span class="from_the_blog_img" style="background-image: url(<?php echo $image_url[0]; ?> );"></span>
							<?php else : ?>
								<span class="from_the_blog_noimg"></span>
							<?php endif;  ?>

						</a>

                        <div class="from_the_blog_content">

                        	<h3><a class="from_the_blog_title" href="<?php the_permalink() ?>"><?php echo get_the_title(); ?></a></h3>
                        	<div class="post_header_date">

                        		<?php
                        			if ( has_post_format( array( 'chat', 'status' ) ) )
										$format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'mr_tailor' );
									else
										$format_prefix = '%2$s';
                        		?>

                        		<a href="<?php echo esc_url( get_permalink() ); ?>"
                        			title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'mr_tailor' ), the_title_attribute( 'echo=0' ) ) ); ?>"
                        			rel="bookmark">
                        			<time class="entry-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                        				<?php echo esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) ); ?>
                        			</time>
                        		</a>

                        	</div>

                        </div>

                    </div>

                <?php endwhile; ?>

            <?php endif; ?>

        </div>

        <div class="swiper-pagination"></div>

    </div>

    <?php
    wp_reset_postdata();
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

add_shortcode("from_the_blog", "mt_ext_shortcode_from_the_blog");
