<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_show_product_sale_flash', 9 );
add_action( 'woocommerce_single_product_notices', 'woocommerce_output_all_notices', 5 );
add_action( 'woocommerce_single_product_summary_single_title', 'woocommerce_template_single_title', 5 );
add_action( 'woocommerce_single_product_summary_single_rating', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_template_single_meta', 11 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_template_single_sharing', 12 );
add_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display', 10 );
add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 15 );

$product_page_has_sidebar = true;
$viewed_products_column = false;
$product_classes = 'single-product with-sidebar';
$product_content_class = 'large-9 columns';
$viewed_products = array();

if ( '0' === MrTailor_Opt::getOption( 'products_layout', false ) || !MrTailor_Opt::getOption( 'products_layout', false ) ) {
	$product_page_has_sidebar = false;
	$product_classes = 'single-product without-sidebar';
	$product_content_class = 'large-12 columns';

	if( !empty( $_COOKIE['woocommerce_recently_viewed'] ) ) {
		$viewed_products = (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] );
		if ( sizeof( $viewed_products ) > 4 ) {
			array_shift( $viewed_products );
		}
		$viewed_products = array_filter( array_map( 'absint', $viewed_products ) );
		if ( sizeof( $viewed_products ) > 4 ) {
			$viewed_products = array_slice( $viewed_products, -4, 4, true );
		}
	}
}

if( !empty( $viewed_products ) && MrTailor_Opt::getOption( 'recently_viewed_products' ) && !$product_page_has_sidebar ) {
	$viewed_products_column = true;
}

if( MrTailor_Opt::getOption( 'catalog_mode', false ) ) {
	$product_classes .= ' catalog-mode';
}

?>

<div class="row">

	<div id="product-<?php the_ID(); ?>" <?php wc_product_class( $product_classes, $product ); ?>>

		<?php
		/**
		 * Hook: woocommerce_before_single_product.
		 *
		 */
		do_action( 'woocommerce_before_single_product' );
		?>

		<div class="product_summary_top">
            <?php

            do_action( 'woocommerce_single_product_summary_single_title' );
            do_action( 'woocommerce_single_product_summary_single_rating' );

            if ( post_password_required() ) {
                echo get_the_password_form(); // WPCS: XSS ok.
                echo '</div></div></div>';
                return;
            }
            ?>
        </div>

		<?php
		if( $product_page_has_sidebar && is_active_sidebar('catalog-widget-area') ) {
			?>
			<div class="large-3 columns show-for-large-up">
				<div class="wpb_widgetised_column">
					<?php dynamic_sidebar('catalog-widget-area'); ?>
				</div>
			</div>
			<?php
		}
		?>

		<div class="<?php echo esc_attr($product_content_class); ?>">

			<?php
			/**
			 * Hook: woocommerce_single_product_notices.
			 *
			 * @hooked woocommerce_output_all_notices - 5
			 */
			do_action( 'woocommerce_single_product_notices' );
			?>

			<div class="product_summary">

				<div class="large-6 columns product_images">
					<?php
					/**
					 * Hook: woocommerce_before_single_product_summary.
					 *
					 * @hooked woocommerce_show_product_images - 20
					 */
					do_action( 'woocommerce_before_single_product_summary' );
					?>
				</div>

				<div class="<?php echo esc_attr($viewed_products_column) ? 'large-5': 'large-6'; ?> columns product_info">
					<div class="summary entry-summary product_infos">
						<?php
						/**
						 * Hook: woocommerce_single_product_summary.
						 *
						 * @hooked woocommerce_template_single_title - 5
						 * @hooked woocommerce_show_product_sale_flash - 9
						 * @hooked woocommerce_template_single_rating - 10
						 * @hooked woocommerce_template_single_price - 10
						 * @hooked woocommerce_template_single_excerpt - 20
						 * @hooked woocommerce_template_single_add_to_cart - 30
						 * @hooked WC_Structured_Data::generate_product_data() - 60
						 */
						do_action( 'woocommerce_single_product_summary' );

						do_action( 'product_sharing_options' );

						?>
					</div>
				</div>

				<?php if( $viewed_products_column ) { ?>
					<div class="large-1 columns recently_viewed_in_single_wrapper">
						<div class="recently_viewed_in_single">
							<h2><?php esc_html_e( 'Recently Viewed Products', 'woocommerce' ); ?></h2>
							<ul>
								<?php foreach ( $viewed_products as $vproduct ) { ?>
									<?php $the_product = wc_get_product($vproduct); ?>
									<?php if( $the_product ) { ?>
										<li>
											<a href="<?php echo esc_url( get_permalink( $the_product->get_id() ) ); ?>" title="<?php echo esc_attr( $the_product->get_title() ); ?>">
												<?php echo wp_kses_post( $the_product->get_image() ); ?>
											</a>
										</li>
									<?php } ?>
								<?php } ?>
							</ul>
						</div>
					</div>
				<?php } ?>

				<div class="clear"></div>

			</div>

			<div class="product_tabs small-12 large-9 columns">
				<?php
				/**
				 * Hook: woocommerce_after_single_product_summary.
				 *
				 * @hooked woocommerce_output_product_data_tabs - 10
				 * @hooked woocommerce_template_single_meta - 11
				 * @hooked woocommerce_template_single_sharing - 12
				 */
				do_action( 'woocommerce_after_single_product_summary' );
				?>

				<div class="product_navigation">
					<?php mrtailor_product_nav( 'nav-below' ); ?>
				</div>

			</div>
		</div>

		<div class="large-12 columns">
			<?php
			/**
			 * Hook: woocommerce_after_single_product.
			 *
			 * @hooked woocommerce_upsell_display - 10
			 * @hooked woocommerce_output_related_products - 15
			 */
			do_action( 'woocommerce_after_single_product' );
			?>
		</div>

	</div>
</div>

<?php

if( !in_array( $post->ID, $viewed_products ) ) {
	$viewed_products[] = $post->ID;
}
