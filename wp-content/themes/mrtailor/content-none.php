<section class="no-results not-found">
	<h2 class="nothing-found-title"><?php esc_html_e( 'Nothing Found', 'mr_tailor' ); ?></h2>

	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p>
				<?php esc_html_e( 'Ready to publish your first post?', 'mr_tailor' ); ?>
				<a href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><?php esc_html_e( 'Get started here.', 'mr_tailor' ); ?></a>
			</p>

		<?php else : ?>

			<p>
				<?php
				if ( is_search() ) {
					esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'mr_tailor' );
				} else {
					esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'mr_tailor' );
				}
				?>
			</p>
			<?php get_search_form(); ?>

		<?php endif; ?>

	</div>
</section>
