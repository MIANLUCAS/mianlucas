<?php
if ( post_password_required() ) return;
?>

<div class="comments_section">
	<div id="comments" class="comments-area">

		<?php if ( have_comments() ) : ?>

			<h2 class="comments-title">
			<?php
				printf( _nx( 'One reply on &ldquo;%2$s&rdquo;', '%1$s replies on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'mr_tailor' ),
				number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
			</h2>

			<?php
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
				paginate_comments_links();
			}
			?>

			<ul class="comment-list">
				<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				* to use mr_tailor_comment() to format the comments.
				* If you want to override this in a child theme, then you can
				* define mr_tailor_comment() and that will be used instead.
				* See mr_tailor_comment() in inc/template-tags.php for more.
				*/
				wp_list_comments( array( 'callback' => 'mr_tailor_comment' ) );
				?>
			</ul>

			<?php
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
				paginate_comments_links();
			}
			?>

		<?php endif; // have_comments() ?>

		<!-- If comments are closed and there are comments, let's leave a little note, shall we? -->
		<?php if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) { ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'mr_tailor' ); ?></p>
		<?php } ?>

		<?php comment_form(); ?>

	</div>
</div>
