<?php

$blog_with_sidebar = ( '1' === MrTailor_Opt::getOption( 'sidebar_blog_listing', '2' ) ) ? true : false;
$blog_with_sidebar = ( isset( $_GET["blog_with_sidebar"] ) && 'yes' === $_GET["blog_with_sidebar"] ) ? true : $blog_with_sidebar;

get_header();

?>

<div id="primary" class="content-area archive">

	<div class="row">

		<div class="large-12 columns">
			<header class="page-header text-center archive">
				<h1 class="page-title">
					<?php
						if ( is_category() ) :
							single_cat_title();

						elseif ( is_tag() ) :
							single_tag_title();

						elseif ( is_author() ) :
							/* Queue the first post, that way we know
							 * what author we're dealing with (if that is the case).
							*/
							the_post();
							printf( esc_html__( 'Author: %s', 'mr_tailor' ), '<span class="vcard">' . get_the_author() . '</span>' );
							/* Since we called the_post() above, we need to
							 * rewind the loop back to the beginning that way
							 * we can run the loop properly, in full.
							 */
							rewind_posts();

						elseif ( is_day() ) :
							printf( esc_html__( 'Day: %s', 'mr_tailor' ), '<span>' . get_the_date() . '</span>' );

						elseif ( is_month() ) :
							printf( esc_html__( 'Month: %s', 'mr_tailor' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

						elseif ( is_year() ) :
							printf( esc_html__( 'Year: %s', 'mr_tailor' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

						elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
							_e( 'Asides', 'mr_tailor' );

						elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
							_e( 'Images', 'mr_tailor');

						elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
							_e( 'Videos', 'mr_tailor' );

						elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
							_e( 'Quotes', 'mr_tailor' );

						elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
							_e( 'Links', 'mr_tailor' );

						else :
							_e( 'Archives', 'mr_tailor' );

						endif;
					?>
				</h1>
				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;
				?>
			</header>
		</div>

    	<div class="<?php echo esc_attr($blog_with_sidebar) ? 'large-8 with-sidebar' : 'large-12'; ?> columns">

            <div id="content" class="site-content" role="main">

               	<?php if ( have_posts() ) : ?>

					<!--masonry style-->
					<?php if ( '2' === MrTailor_Opt::getOption( 'sidebar_blog_listing' ) ) : ?>

						<div class="blog-isotop-master-wrapper">
							<div class="blog-isotop-container">
								<div class="blog-isotope">

									<div class="grid-sizer"></div>

									<?php while ( have_posts() ) : the_post(); ?>

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

									<?php endwhile; ?>

								</div>
							</div>

							<div class="large-12 columns">
								<div class="large-6 medium-6 small-12 columns posts-nav previous-posts-nav">
		                            <div class="nav-previous"><?php echo get_previous_posts_link( __( 'Newer posts', 'mr_tailor' ) ); ?></div>
		                        </div>

		                        <div class="large-6 medium-6 small-12 columns posts-nav next-posts-nav">
		                            <div class="nav-next"><?php echo get_next_posts_link( __( 'Older posts', 'mr_tailor' ) ); ?></div>
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
					get_template_part( 'content', 'none' );
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
