<?php
/**
 * The template for displaying product content within loops
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

//woocommerce_after_shop_loop_item_title
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

add_action( 'woocommerce_after_shop_loop_item_title_loop_price', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item_title_loop_rating', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_product_link_open', 'woocommerce_template_loop_product_link_open', 10 );
add_action( 'woocommerce_product_link_close', 'woocommerce_template_loop_product_link_close', 10 );

//woocommerce_before_shop_loop_item_title
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

if( !MrTailor_Opt::getOption( 'catalog_mode', false ) ) {
	add_action( 'woocommerce_after_shop_loop_item_title_loop_rating', 'woocommerce_show_product_loop_sale_flash', 10 );
	add_action( 'woocommerce_after_shop_loop_item_title_loop_rating', 'mrtailor_out_of_stock_badge', 15);
}

$catalog_mode_class = MrTailor_Opt::getOption( 'catalog_mode', false ) ? 'catalog_mode' : '';

?>
<li <?php wc_product_class( $catalog_mode_class, $product ); ?>>

	<div class="product_wrapper">
		<?php

		$attachment_ids = $product->get_gallery_image_ids();
		if( $attachment_ids ) {
			$loop = 0;
			foreach( $attachment_ids as $attachment_id ) {
				$image_link = wp_get_attachment_url( $attachment_id );
				if(!$image_link) continue;
				$loop++;
				$product_thumbnail_second = wp_get_attachment_image_src($attachment_id, 'shop_catalog');
				if($loop == 1) break;
			}
		}

		$style = $class = '';
		if( isset($product_thumbnail_second[0]) && MrTailor_Opt::getOption( 'product_hover_animation', true ) ) {
			$style = 'background-image:url(' . $product_thumbnail_second[0] . ')';
			$class = 'with_second_image';
		}

		/**
		 * Hook: woocommerce_before_shop_loop_item.
		 */
		do_action( 'woocommerce_before_shop_loop_item' );

		/**
		 * Hook: woocommerce_before_shop_loop_item_title.
		 */
		do_action( 'woocommerce_before_shop_loop_item_title' );
		?>

		<div class="product_thumbnail_wrapper">

			<div class="product_thumbnail <?php echo esc_attr($class); ?>">

				<?php
				/**
				 * Hook: woocommerce_product_link_open.
				 *
				 * @hooked woocommerce_template_loop_product_link_open - 10
				 */
				do_action( 'woocommerce_product_link_open' );
				?>

				<span class="product_thumbnail_background" style="<?php echo esc_attr($style); ?>"></span>

				<?php

				if ( has_post_thumbnail( $post->ID ) ) {
					echo  get_the_post_thumbnail( $post->ID, 'shop_catalog');
				} else {
					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', wc_placeholder_img_src() ), $post->ID );
				}

				/**
				 * Hook: woocommerce_product_link_close.
				 *
				 * @hooked woocommerce_template_loop_product_link_close - 10
				 */
				do_action( 'woocommerce_product_link_close' );
				?>

			</div>

			<?php if( MT_WISHLIST_IS_ACTIVE ) {
				echo do_shortcode('[yith_wcwl_add_to_wishlist]');
			} ?>

		</div>

		<?php
		/**
		 * Hook: woocommerce_product_link_open.
		 *
		 * @hooked woocommerce_template_loop_product_link_open - 10
		 */
		do_action( 'woocommerce_product_link_open' );

		/**
		 * Hook: woocommerce_shop_loop_item_title.
		 *
		 * @hooked woocommerce_template_loop_product_title - 10
		 */
		do_action( 'woocommerce_shop_loop_item_title' );

		/**
		 * Hook: woocommerce_product_link_close.
		 *
		 * @hooked woocommerce_template_loop_product_link_close - 10
		 */
		do_action( 'woocommerce_product_link_close' );
		?>

	</div>

	<?php
	/**
	 * Hook: woocommerce_after_shop_loop_item_title_loop_rating.
	 *
	 * @hooked woocommerce_template_loop_rating - 5
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked mrtailor_out_of_stock_badge - 15
	 */
	do_action( 'woocommerce_after_shop_loop_item_title_loop_rating' );
	?>

	<div class="product_after_shop_loop <?php echo ( '0' === MrTailor_Opt::getOption( 'add_to_cart_display', '1' )) ? 'always_visible_price': ''; ?>">

		<?php
		/**
		 * Hook: woocommerce_after_shop_loop_item_title.
		 *
		 * @hooked woocommerce_template_loop_price - 10
		 */
        do_action( 'woocommerce_after_shop_loop_item_title' );
		?>

		<div class="product_after_shop_loop_switcher">

            <div class="product_after_shop_loop_price">
                <?php
				/**
				 * Hook: woocommerce_after_shop_loop_item_title_loop_price.
				 *
				 * @hooked woocommerce_template_loop_price - 10
				 */
				do_action( 'woocommerce_after_shop_loop_item_title_loop_price' );
				?>
            </div>

            <div class="product_after_shop_loop_buttons">
                <?php
				/**
				 * Hook: woocommerce_after_shop_loop_item.
				 *
				 * @hooked woocommerce_template_loop_add_to_cart - 10
				 */
				do_action( 'woocommerce_after_shop_loop_item' );
				?>
            </div>

        </div>

    </div>
</li>
