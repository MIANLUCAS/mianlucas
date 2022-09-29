<?php

$blog_with_sidebar = ( '1' === MrTailor_Opt::getOption( 'sidebar_blog_listing', '2' ) ) ? true : false;
$blog_with_sidebar = ( isset( $_GET["blog_with_sidebar"] ) && 'yes' === $_GET["blog_with_sidebar"] ) ? true : $blog_with_sidebar;

get_header();

?>

<div id="primary" class="content-area">

	<div class="row">
		<div class="<?php echo esc_attr($blog_with_sidebar) ? 'large-8 with-sidebar columns' : 'large-12'; ?>">
			<div id="content" class="site-content" role="main">
			<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'content', get_post_format() );

					?>

					<div class="large-12 post-navigation">
						<?php
						$post_navigation = array(
							'previous' => array(
								'class' => 'previous',
								'post' 	=> get_previous_post(),
								'text'	=> 'Previous Reading'
							),
							'next' => array(
								'class' => 'next',
								'post' 	=> get_next_post(),
								'text' 	=> 'Next Reading'
							)
						);
						foreach( $post_navigation as $post_nav ) :
						?>
							<div class="large-6 medium-6 small-12 columns post-nav <?php echo wp_kses_post( $post_nav['class'] ); ?>-post-nav">
								<?php if( !empty($post_nav['post']) ) { ?>
									<a href="<?php echo esc_url(get_permalink($post_nav['post']->ID)); ?>" rel="prev">
										<div class="nav-post-title">
											<?php esc_html_e( $post_nav['text'], 'mr_tailor' ); ?>
										</div>

										<div class="previous-post-info">
											<div class="entry-thumbnail">
												<?php echo get_the_post_thumbnail( $post_nav['post']->ID, 'large' ); ?>
												<span class="more-link"><?php esc_html_e('Continue reading', 'mr_tailor'); ?><span class="arrow-icon"></span></span>
											</div>

											<h2 class="post-title">
												<?php echo esc_attr($post_nav['post']->post_title); ?>
											</h2>
										</div>
									</a>

									<div class="post_header_date">
										<?php echo get_the_date( 'F j, Y', $post_nav['post']->ID ); ?>
									</div>

									<div class="post_excerpt">
										<p><?php echo get_the_excerpt( $post_nav['post']->ID ); ?></p>
									</div>
								<?php } ?>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="comments-content <?php echo esc_attr($blog_with_sidebar) ? 'large-12' : 'large-8 large-centered columns'; ?>">
						<?php
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || '0' != get_comments_number() ) {
							comments_template();
						}
						?>
					</div>

				<?php endwhile; ?>
			</div>
		</div>

		<?php if ( $blog_with_sidebar ) { ?>
			<div class="large-4 post-sidebar columns">
				<?php get_sidebar(); ?>
			</div>
		<?php } ?>
	</div>
</div>

<?php

get_footer();
