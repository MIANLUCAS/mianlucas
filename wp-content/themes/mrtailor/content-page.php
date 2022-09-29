<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-content">
        <?php
        the_content( esc_html__( 'Continue reading', 'mr_tailor' ) . ' <span class="meta-nav">&rarr;</span>' );
        wp_link_pages(
            array(
                'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'mr_tailor' ) . '</span>',
                'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>'
            )
        );
        ?>
    </div>
</article>

<?php
// If comments are open or we have at least one comment, load up the comment template
if ( comments_open() || '0' != get_comments_number() ) {
    comments_template();
}
?>
