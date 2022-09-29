<?php

function mrtailor_widgets_init() {

	//default sidebar
	register_sidebar(array(
		'name'          => esc_html__( 'Sidebar', 'mr_tailor' ),
		'id'            => 'default-sidebar',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

	//footer widget area
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget Area', 'mr_tailor' ),
		'id'            => 'footer-widget-area',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	//catalog widget area
	register_sidebar( array(
		'name'          => esc_html__( 'Shop Sidebar', 'mr_tailor' ),
		'id'            => 'catalog-widget-area',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'mrtailor_widgets_init' );
