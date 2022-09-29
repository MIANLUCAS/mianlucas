<?php
global $woocommerce, $wp_version;
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php do_action('mrtailor_header_start'); ?>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php wp_body_open(); ?>

	<div id="st-container" class="st-container">

		<div class="st-pusher">

			<div class="st-pusher-after"></div>

			<div class="st-content">

				<?php $transparency = mrtailor_get_transparency_options(); ?>

				<div id="page" class="<?php echo esc_attr($transparency['header_transparency_class']); ?> <?php echo esc_html($transparency['transparency_scheme']); ?>">

					<?php do_action( 'before' ); ?>

					<div class="top-headers-wrapper <?php echo MrTailor_Opt::getOption( 'sticky_header' ) ? 'site-header-sticky' : ''; ?>">

						<?php

						if ( MrTailor_Opt::getOption( 'top_bar_switch' ) ) {
							get_template_part( 'header-topbar' );
						}

						if ( '2' === MrTailor_Opt::getOption( 'header_layout' ) ) {
							get_template_part( 'header-full' );
						} else {
							get_template_part( 'header-default' );
						}

						?>

					</div>
