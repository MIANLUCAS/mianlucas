<?php
/*
Template Name: Narrow Page
*/

$page_id = '';
if ( is_single() || is_page() ) {
    $page_id = get_the_ID();
} else if ( is_home() ) {
    $page_id = get_option('page_for_posts');
}

$page_header_src = has_post_thumbnail() ? wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ) : '' ;
$page_header_style = ( '' != $page_header_src ) ? 'style="background-image:url(' . esc_url($page_header_src) . ')"' : '';
$page_title_option = ( ( 'off' === get_post_meta( $page_id, 'page_title_meta_box_check', true ) ) || ( '' === get_post_meta( $page_id, 'page_title_meta_box_check', true ) ) ) ? true : false;

get_header();
?>

<div id="primary" class="content-area <?php echo esc_attr($page_title_option) ? '' : 'hidden-title'; ?>">
    <div id="content" class="site-content" role="main">

        <header class="entry-header <?php echo ( '' != $page_header_src ) ? 'with_featured_img' : ''; ?>" <?php echo printf($page_header_style); ?>>
            <div class="page_header_overlay"></div>
            <div class="row">
                <div class="large-8 large-centered columns">
                    <?php if ( $page_title_option ) { ?>
                        <h1 class="entry-title">
                            <?php the_title(); ?>
                        </h1>
                    <?php } ?>

                    <?php if( $post->post_excerpt ) { ?>
                        <div class="page-description">
                            <?php the_excerpt(); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </header>

        <div class="row">
            <div class="large-8 large-centered columns">

                <?php while ( have_posts() ) : the_post(); ?>

                    <?php get_template_part( 'content', 'page' ); ?>

                <?php endwhile; ?>

                <div class="clear"></div>

            </div>
        </div>

    </div>
</div>

<?php get_footer();
