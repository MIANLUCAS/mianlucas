<?php
add_action( 'wp_enqueue_scripts', 'mrtailor_enqueue_styles', 99 );
function mrtailor_enqueue_styles() {

    wp_enqueue_style( 'mr_tailor-styles', get_template_directory_uri() . '/css/styles.css' );
    wp_enqueue_style( 'mr_tailor-default-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'mr_tailor-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'mr_tailor-default-style' ),
        wp_get_theme()->get('Version')
    );
}
