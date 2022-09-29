<?php
/*
Template Name: Full Width Page
*/

get_header();
?>

<div class="full-width-page">
    <div id="primary" class="content-area">
        <div id="content" class="site-content" role="main">

            <?php while ( have_posts() ) : the_post(); ?>

                <?php get_template_part( 'content', 'page' ); ?>

            <?php endwhile; ?>

            <div class="clear"></div>

        </div>
    </div>
</div>

<?php get_footer();
