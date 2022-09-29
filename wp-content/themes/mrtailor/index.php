<?php

$blog_with_sidebar = ( '1' === MrTailor_Opt::getOption( 'sidebar_blog_listing', '2' ) ) ? true : false;
$blog_with_sidebar = ( isset( $_GET["blog_with_sidebar"] ) && 'yes' === $_GET["blog_with_sidebar"] ) ? true : $blog_with_sidebar;

get_header();

?>

<div id="primary" class="content-area">

    <div class="row">
        <div class="<?php echo esc_attr($blog_with_sidebar) ? 'large-8 with-sidebar' : 'large-12'; ?> columns">
            <div id="content" class="site-content" role="main">

				<?php if ( have_posts() ) : ?>

					<!--masonry style-->
					<?php if ( '2' === MrTailor_Opt::getOption( 'sidebar_blog_listing' ) ) : ?>

                        <?php $sticky_posts = get_option( 'sticky_posts' ); rsort( $sticky_posts ); ?>
                        <?php if( !empty($sticky_posts) ) { ?>
                            <div class="swiper-container sticky-posts-container">
                                <div class="swiper-wrapper">
                                    <?php foreach( $sticky_posts as $sticky_post ) { ?>
                                        <div class="swiper-slide">
                                            <a href="<?php echo esc_url(get_permalink($sticky_post)); ?>" rel="prev">
                                                <?php if( has_post_thumbnail($sticky_post) ) { ?>
                                                    <div class="thumbnail_container" style="background-image:url(<?php echo get_the_post_thumbnail_url( $sticky_post ); ?>)">
                                                        <span class="more-link"><?php esc_html_e('Continue reading', 'mr_tailor'); ?><span class="arrow-icon"></span></span>
                                                    </div>
                                                <?php } ?>
                                                <div class="sticky-post-info">
                                                    <h2 class="entry-title blog-post-title"><?php echo get_the_title($sticky_post);  ?></h2>
                                                    <div class="sticky-meta">
                                                        <div class="post_header_date">
                    										<?php echo get_the_date( 'F j, Y', $sticky_post ); ?>
                    									</div>
                                                        <span class="featured_span"><?php esc_html_e( 'Featured', 'mr_tailor' ); ?></span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="swiper-pagination sticky-pagination"></div>
                            </div>
                        <?php } ?>

						<div class="blog-isotop-master-wrapper">
							<div class="blog-isotop-container">
								<div class="blog-isotope">

									<div class="grid-sizer"></div>

									<?php while ( have_posts() ) : the_post(); ?>

                                        <?php if( !is_sticky() ) { ?>

    										<div class="blog-post small-12 medium-6 large-4 columns hidden <?php echo get_post_format(); ?>">
    											<div class="blog-post-inner">

    												<h2 class="entry-title-archive">
    													<a href="<?php the_permalink(); ?>" class="thumbnail_archive">
                                                            <?php if( has_post_thumbnail() ) { ?>
        														<div class="thumbnail_archive_container">
        															<?php the_post_thumbnail('large'); ?>
                                                                    <span class="more-link"><?php esc_html_e('Continue reading', 'mr_tailor'); ?><span class="arrow-icon"></span></span>
        														</div>
                                                            <?php } ?>
    														<span class="entry-title-archive-text"><?php the_title(); ?></span>
    													</a>
    												</h2>

    												<div class="post_meta_archive"><?php mr_tailor_post_header_entry_date(); ?></div>

    												<div class="entry-content-archive">
    													<?php the_excerpt(); ?>
    												</div>
    											</div>
    										</div>
                                        <?php } ?>

									<?php endwhile; ?>

								</div>

                                <div class="large-12 columns pagination">
                                    <div class="large-6 medium-6 small-12 columns posts-nav previous-posts-nav">
                                        <div class="nav-previous"><?php echo get_previous_posts_link( __( 'Newer posts', 'mr_tailor' ) ); ?></div>
                                    </div>

                                    <div class="large-6 medium-6 small-12 columns posts-nav next-posts-nav">
                                        <div class="nav-next"><?php echo get_next_posts_link( __( 'Older posts', 'mr_tailor' ) ); ?></div>
                                    </div>
                                </div>
                            </div>
						</div>

					<!--default style-->
					<?php else :

						while ( have_posts() ) {
                            the_post();
							get_template_part( 'content', get_post_format() );
						}
                        ?>

                        <div class="large-6 medium-6 small-12 columns posts-nav previous-posts-nav">
                            <div class="nav-previous"><?php echo get_previous_posts_link( __( 'Newer posts', 'mr_tailor' ) ); ?></div>
                        </div>

                        <div class="large-6 medium-6 small-12 columns posts-nav next-posts-nav">
                            <div class="nav-next"><?php echo get_next_posts_link( __( 'Older posts', 'mr_tailor' ) ); ?></div>
                        </div>

                        <?php
					endif;

				// no posts found
                else :
                    get_template_part( 'no-results', 'index' );
                endif;
                ?>
            </div>
    	</div>

		<?php if ( $blog_with_sidebar ) { ?>
			<div class="large-4 columns">
				<?php get_sidebar(); ?>
            </div>
        <?php } ?>

    </div>
</div>

<?php

get_footer();
