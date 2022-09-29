<?php

if ( ! is_admin() ) {

	function grab_ids_from_gallery() {

		global $post;

	    if ( !isset($post) ) return;

		$attachment_ids = array();
		$pattern = get_shortcode_regex();
		$ids = array();

		if (preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches ) ) {   //finds the "gallery" shortcode and puts the image ids in an associative array at $matches[3]
			//$count = count($matches[3]); //in case there is more than one gallery in the post.
			$count = 1;
			for ($i = 0; $i < $count; $i++){
				$atts = shortcode_parse_atts( $matches[3][$i] );
				if ( isset( $atts['ids'] ) ){
					$attachment_ids = explode( ',', $atts['ids'] );
					$ids = array_merge($ids, $attachment_ids);
				}
			}
		}

		return $ids;

	}
	add_action( 'wp', 'grab_ids_from_gallery' );
}

if ( ! function_exists( 'mrtailor_product_nav' ) ) :
function mrtailor_product_nav( $nav_id ) {

	global $wp_query, $post;

	// Don't print empty markup on single pages if there's nowhere to navigate.
	if ( is_single() ) {
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;
	}

	// Don't print empty markup in archives if there's only one page.
	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;

	$nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';

	?>
	<nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo esc_attr($nav_class); ?>">

        <div class="product-nav-previous"><?php previous_post_link( '%link', '' ); ?></div>
        <div class="product-nav-next"><?php next_post_link( '%link', '' ); ?></div>

	</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->

    <?php

}
endif; // mrtailor_product_nav

if ( ! function_exists( 'mr_tailor_comment' ) ) :
function mr_tailor_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php esc_html_e( 'Pingback:', 'mr_tailor' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( 'Edit', 'mr_tailor' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">

			<div class="comment-content">

				<div class="comment-author-avatar">
					<?php echo get_avatar( $comment, 140 ); ?>
				</div><!-- .comment-author-avatar -->

				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'mr_tailor' ); ?></p>
				<?php endif; ?>

				<h3 class="comment-author"><?php echo get_comment_author_link(); ?></h3>

                <div class="comment-metadata">
                    <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                        <time datetime="<?php comment_time( 'c' ); ?>">
                            <?php printf( esc_html__( '%1$s at %2$s', 'mr_tailor' ), get_comment_date(), get_comment_time() ); ?>
                        </time>
                    </a>
                </div><!-- .comment-metadata -->

				<div class="comment-text"><?php comment_text(); ?></div><!-- .comment-text -->

                <?php
					comment_reply_link( array_merge( $args, array(
						'add_below' => 'div-comment',
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'before'    => '<span class="comment-reply">',
						'after'     => '</span>',
					) ) );
				?>

				<?php edit_comment_link( esc_html__( 'Edit', 'mr_tailor' ), '<span class="comment-edit-link">', '</span>' ); ?>

			</div><!-- .comment-content -->

		</article><!-- .comment-body -->

	<?php
	endif;
}
endif; // ends check for mr_tailor_comment()

/**
 * Returns true if a blog has more than 1 category.
 */
function mr_tailor_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so mr_tailor_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so mr_tailor_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in mr_tailor_categorized_blog.
 */
function mr_tailor_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'mr_tailor_category_transient_flusher' );
add_action( 'save_post',     'mr_tailor_category_transient_flusher' );
