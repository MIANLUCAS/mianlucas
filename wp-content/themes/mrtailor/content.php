<?php

$blog_with_sidebar = ( '1' === MrTailor_Opt::getOption( 'sidebar_blog_listing', '2' ) ) ? true : false;
$blog_with_sidebar = ( isset( $_GET["blog_with_sidebar"] ) && 'yes' === $_GET["blog_with_sidebar"] ) ? true : $blog_with_sidebar;

$featured_post_meta = get_post_meta( $post->ID, 'post_featured_image_meta_box_check', true );
$post_featured_image_option = !empty($featured_post_meta) ? $featured_post_meta : 'on';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header">
        <div class="<?php echo ( '1' !== MrTailor_Opt::getOption( 'sidebar_blog_listing' ) ) ? 'large-8 large-centered columns' : 'large-12'; ?>">
            <?php if ( is_single() ) { ?>
                <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php } else { ?>
                <h2 class="entry-title blog-post-title">
                    <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                </h2>
            <?php } ?>

            <div class="post_header_date">
                <?php mr_tailor_post_header_entry_date(); ?>
            </div>
        </div>
        <?php if ( ( !is_single() || ( is_single() && 'on' === $post_featured_image_option ) ) && has_post_thumbnail() && ! post_password_required() ) { ?>
            <div class="entry-thumbnail">
                <?php the_post_thumbnail(); ?>
            </div>
        <?php } ?>
    </header>

    <div class="entry-content">
        <div class="post-content <?php echo esc_attr($blog_with_sidebar) ? 'large-12' : 'large-8 large-centered columns without-sidebar'; ?>">
            <?php
                if( !is_single() ) {
                    the_excerpt();
                    ?>
                    <a href="<?php the_permalink(); ?>" class="more-link"><?php esc_html_e('Continue reading &rarr;', 'mr_tailor'); ?></a>
                    <?php
                } else {
                    the_content( esc_html__( 'Continue reading &rarr;', 'mr_tailor' ) );
                }

                wp_link_pages(
                    array(
                        'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'mr_tailor' ) . '</span>',
                        'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' )
                    );
            ?>

            <?php if ( is_single() ) { ?>

                <?php do_action( 'post_sharing_options' ); ?>

                <footer class="entry-meta">
                    <?php mr_tailor_entry_meta(); echo "."; ?>
                </footer>

            <?php } ?>
        </div>
    </div>

</article>
