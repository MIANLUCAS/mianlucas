					<?php global $woocommerce, $yith_wcwl, $page_id; ?>

                    <?php

                    $page_id = "";
                    if ( is_single() || is_page() ) {
                        $page_id = get_the_ID();
                    } else if ( is_home() ) {
                        $page_id = get_option('page_for_posts');
                    }

					if (get_post_meta( $page_id, 'footer_meta_box_check', true )) {
						$page_footer_option = get_post_meta( $page_id, 'footer_meta_box_check', true );
					} else {
						$page_footer_option = "off";
					}

					?>

                    <?php if ( $page_footer_option == "off" ) : ?>

	                    <footer id="site-footer" role="contentinfo">

							 <?php if( is_active_sidebar( 'footer-widget-area' ) ) : ?>

	                            <?php if( MrTailor_Opt::getOption( 'expandable_footer', true ) ) { ?>
	    							<div class="trigger-footer-widget-area">
	    								<span class="trigger-footer-widget-icon"></span>
	    							</div>
							    <?php } ?>

								<div class="site-footer-widget-area <?php echo MrTailor_Opt::getOption( 'expandable_footer', true ) ? 'expandable' : ''; ?>">
									<?php
									$sidebars_widgets = wp_get_sidebars_widgets();
									$footer_area_widgets_counter = isset($sidebars_widgets['footer-widget-area']) ? count($sidebars_widgets['footer-widget-area']) : 1;
									if( $footer_area_widgets_counter > 5 ) { $footer_area_widgets_counter = 5; }
									if( MrTailor_Opt::getOption( 'footer_highlight_widget', false ) ) { $footer_area_widgets_counter++; }
									?>

									<div class="row widget-grid footer-columns-<?php echo esc_attr($footer_area_widgets_counter);?> <?php echo MrTailor_Opt::getOption( 'footer_highlight_widget', false ) ? 'highlight-widget' : ''; ?>">
										<?php dynamic_sidebar( 'footer-widget-area' ); ?>
									</div>
								</div>

							<?php endif; ?>

							<?php
							$icons_enabled = ( '' != MrTailor_Opt::getOption( 'credit_card_icons' ) ) ? true : false;
							$text_enabled  = ( '' != MrTailor_Opt::getOption( 'footer_copyright_text' ) ) ? true : false;

							$icons_class = $text_class = 'medium-12';
							if( $icons_enabled && $text_enabled ) {
								$icons_class = 'medium-5 large-4';
								$text_class  = 'medium-7 large-8';
							}
							?>

							<?php if( $icons_enabled || $text_enabled ) { ?>

		                        <div class="site-footer-copyright-area">
		                            <div class="row">

										<?php if( $icons_enabled ) { ?>
			                                <div class="small-12 <?php echo esc_attr($icons_class); ?> columns">
			                                    <div class="payment_methods">

			                                        <?php
			                                        if ( MrTailor_Opt::getOption( 'credit_card_icons' ) != "" ) {
			                                            if (is_ssl()) {
			                                                $credit_card_icons = str_replace( "http://", "https://", MrTailor_Opt::getOption( 'credit_card_icons' ) );
			                                            } else {
			                                                $credit_card_icons = MrTailor_Opt::getOption( 'credit_card_icons' );
			                                            }
			                                        ?>

			                                        <img src="<?php echo esc_url($credit_card_icons); ?>" alt="<?php esc_attr_e( 'Payment methods', 'mr_tailor' )?>" />

			                                        <?php } ?>

			                                    </div>
			                                </div>
										<?php } ?>

										<?php if( $text_enabled ) { ?>
			                                <div class="small-12 <?php echo esc_attr($text_class); ?> columns">
			                                    <div class="copyright_text">
			                                        <?php if ( !empty( MrTailor_Opt::getOption( 'footer_copyright_text' ) ) ) { ?>
														<?php printf( wp_kses_post(__( '%s', 'mr_tailor' )), MrTailor_Opt::getOption( 'footer_copyright_text' ) ); ?>
			                                        <?php } ?>
			                                    </div>
			                                </div>
										<?php } ?>

		                            </div>
		                        </div>

							<?php } ?>

	                    </footer>

                    <?php endif; ?>

                </div><!-- #page -->

            </div><!-- /st-content -->
        </div><!-- /st-pusher -->

        <nav class="st-menu slide-from-left">

            <div id="mobiles-menu-offcanvas" class="offcanvas-left-content">

                <nav id="mobile-main-navigation" class="mobile-navigation" role="navigation">
				<?php
					wp_nav_menu(array(
						'theme_location'  => 'main-navigation',
						'fallback_cb'     => false,
						'container'       => false,
						'items_wrap'      => '<ul id="%1$s">%3$s</ul>',
					));
				?>
                </nav>

                <?php

				$theme_locations  = get_nav_menu_locations();
				if (isset($theme_locations['top-bar-navigation'])) {
					$menu_obj = get_term($theme_locations['top-bar-navigation'], 'nav_menu');
				}

				if ( (isset($menu_obj->count) && ($menu_obj->count > 0)) || (is_user_logged_in()) ) {
				?>

                    <nav id="mobile-top-bar-navigation" class="mobile-navigation" role="navigation">
                    <?php
                        wp_nav_menu(array(
                            'theme_location'  => 'top-bar-navigation',
                            'fallback_cb'     => false,
                            'container'       => false,
                            'items_wrap'      => '<ul id="%1$s">%3$s</ul>',
                        ));
                    ?>

                    <?php if ( is_user_logged_in() ) { ?>
                        <ul><li><a href="<?php echo get_home_url(); ?>/?<?php echo get_option('woocommerce_logout_endpoint'); ?>=true" class="logout_link"><?php esc_html_e('Logout', 'mr_tailor'); ?></a></li></ul>
                    <?php } ?>
                    </nav>

                <?php } ?>

                <?php if ( function_exists('icl_get_languages') || class_exists('woocommerce_wpml') ) { ?>

                <div class="language-and-currency-offcanvas hide-for-large-up">

                    <?php $additional_languages = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str'); ?>

                    <select class="topbar-language-switcher">
                        <option><?php echo ICL_LANGUAGE_NAME; ?></option>
                        <?php

                        if (count($additional_languages) > 1) {
                            foreach($additional_languages as $additional_language){
                              if(!$additional_language['active']) $langs[] = '<option value="'.$additional_language['url'].'">'.$additional_language['native_name'].'</option>';
                            }
                            echo join(', ', $langs);
                        }

                        ?>
                    </select>

                    <?php if (class_exists('woocommerce_wpml')) { ?>
                        <?php echo(do_shortcode('[currency_switcher]')); ?>
                    <?php } ?>

                </div>

                <?php } ?>

                <?php do_action( 'footer_socials', MrTailor_Opt::getOption( 'headings_color', '#000000' ) ); ?>

            </div>
            <div id="filters-offcanvas" class="offcanvas-left-content wpb_widgetised_column">
				<?php if ( is_active_sidebar( 'catalog-widget-area' ) ) : ?>
                    <?php dynamic_sidebar( 'catalog-widget-area' ); ?>
                <?php endif; ?>
            </div>

        </nav>

        <nav class="st-menu slide-from-right">
			<?php if ( class_exists( 'WC_Widget_Cart' ) ) { ?>
				<div id="minicart-offcanvas" class="offcanvas-right-content">
					<?php the_widget( 'WC_Widget_Cart' ); ?>
					<?php if( !empty( MrTailor_Opt::getOption( 'minicart_text', '' ) ) ) { ?>
						<div class="minicart_text">
							<?php printf( esc_html__( '%s', 'mr_tailor' ), MrTailor_Opt::getOption( 'minicart_text', '' ) ); ?>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
        </nav>

		<?php if ( MrTailor_Opt::getOption( 'main_header_search_bar' ) ) { ?>
			<div class="site-search">
				<?php if( MT_WOOCOMMERCE_IS_ACTIVE ) {
					the_widget( 'WC_Widget_Product_Search', 'title=' );
				} else {
					the_widget( 'WP_Widget_Search', 'title=' );
				} ?>
			</div>
		<?php } ?>

    </div><!-- /st-container -->

    <?php do_action('mrtailor_footer_action'); ?>

    <!-- ******************************************************************** -->
    <!-- * WP Footer() ****************************************************** -->
    <!-- ******************************************************************** -->

	<div class="login_header">
		<a class="go_home" href="<?php echo esc_url( home_url() ); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a>
	</div>

<?php wp_footer(); ?>
</body>

</html>
