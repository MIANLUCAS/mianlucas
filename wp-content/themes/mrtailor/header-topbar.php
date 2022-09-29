<div id="site-top-bar" class="<?php echo ( '2' === MrTailor_Opt::getOption( 'header_layout' ) ) ? 'full-topbar' : 'default-topbar'; ?> <?php echo ( MrTailor_Opt::getOption( 'sticky_header', true ) && MrTailor_Opt::getOption( 'top_bar_sticky', false ) ) ? 'sticky-topbar' : ''; ?>">
    <div class="topbar-wrapper">

        <div class="topbar-message">
            <?php if (function_exists('icl_get_languages')) { ?>

                <div class="language-and-currency">

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

        	    </div><!--.language-and-currency-->
            <?php } ?>

            <?php if( MrTailor_Opt::getOption( 'top_bar_text' ) != '' ) { ?>
            	<div class="site-top-message">
                    <?php printf( wp_kses_post(__( '%s', 'mr_tailor' )), MrTailor_Opt::getOption( 'top_bar_text', esc_html__( 'Free Shipping on All Orders Over $75!', 'mr_tailor' ) ) ); ?>
                </div>
            <?php } ?>
        </div>

        <?php if( has_nav_menu('top-bar-navigation') ) { ?>
            <nav id="site-navigation-top-bar" class="main-navigation" role="navigation">
        		<?php
                    wp_nav_menu(array(
                        'theme_location'  => 'top-bar-navigation',
                        'fallback_cb'     => false,
                        'container'       => false,
                        'items_wrap'      => '<ul id="%1$s">%3$s</ul>',
                    ));
                ?>
            </nav>
        <?php } ?>

        <?php if ( is_user_logged_in() ) { ?>
            <div class="topbar-logout">
                <ul>
                    <li>
                        <a href="<?php echo get_home_url(); ?>/?<?php echo get_option('woocommerce_logout_endpoint'); ?>=true" class="logout_link"><?php esc_html_e('Logout', 'mr_tailor'); ?></a>
                    </li>
                </ul>
            </div>
        <?php } ?>

        <div class="topbar-social-icons">
            <?php do_action( 'header_socials', MrTailor_Opt::getOption( 'top_bar_typography', '#686868' ) ); ?>
        </div>

    </div>
</div>
