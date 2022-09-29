<?php get_header(); ?>

<div id="primary" class="content-area archive">
	<div class="row">

		<div class="large-8 large-centered columns">

			<header class="page-header archive">
				<h1 class="page-title search-title">
					<?php printf( esc_html__( 'Search Results for: %s', 'mr_tailor' ), '<span>' . get_search_query() . '</span>' ); ?>
				</h1>
			</header>

            <div id="content" class="site-content" role="main">
                <?php if ( have_posts() ) { ?>

					<div class="search-results">

	                    <?php while ( have_posts() ) : the_post(); ?>

							<div class="search-item">

								<h2 class="search-item-title">
					                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
					            </h2>

								<div class="post_header_date">
						            <?php mr_tailor_post_header_entry_date(); ?>
						        </div>

							</div>

		                <?php endwhile; ?>

					</div>

					<div class="large-6 medium-6 small-12 columns posts-nav previous-posts-nav">
						<div class="nav-previous"><?php echo get_previous_posts_link( __( 'Newer posts', 'mr_tailor' ) ); ?></div>
					</div>

					<div class="large-6 medium-6 small-12 columns posts-nav next-posts-nav">
						<div class="nav-next"><?php echo get_next_posts_link( __( 'Older posts', 'mr_tailor' ) ); ?></div>
					</div>

					<?php
				} else {
					get_template_part( 'content', 'none' );
				}
				?>
            </div>
	    </div>
	</div>
</div>

<?php

get_footer();
