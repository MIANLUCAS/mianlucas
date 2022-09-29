<?php

function mr_tailor_product_categories( $atts ) {

	extract( shortcode_atts( array(
		'number'     					=> 9,
		'product_categories_selection' 	=> 'auto',
		'orderby'   					=> 'title',
		'order'      					=> 'ASC',
		'hide_empty' 					=> 1,
		'parent'     					=> '0',
		'ids'		 					=> ''
	), $atts ) );

	if ( isset( $atts[ 'ids' ] ) ) {
		$ids = explode( ',', $atts[ 'ids' ] );
		$ids = array_map( 'trim', $ids );
	} else {
		$ids = array();
	}

	$hide_empty = ( $hide_empty == true || $hide_empty == 1 ) ? 1 : 0;

	// get terms and workaround WP bug with parents/pad counts
	if( $product_categories_selection != "auto" ) {
		$args = array(
			'orderby'    => 'include',
			'hide_empty' => $hide_empty,
			'include'    => $ids,
		);
	} else {
		$args = array(
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $hide_empty,
			'pad_counts' => true,
			'number'	 => $number
		);

		if ($parent == '0') {
			$args['parent']= 0;
		}
	}

	$product_categories = get_terms( 'product_cat', $args );

	if ( $hide_empty ) {
		foreach ( $product_categories as $key => $category ) {
			if ( $category->count == 0 ) {
				unset( $product_categories[ $key ] );
			}
		}
	}

	ob_start();

	$cat_counter = 0;

	$cat_number = count($product_categories);

	if ( $product_categories ) {

		foreach ( $product_categories as $category ) {

			$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
			$image = wp_get_attachment_url( $thumbnail_id );
			$cat_class = "";

			$cat_counter++;

			switch ($cat_number) {
				case 1:
					$cat_class = "one_cat_" . $cat_counter;
					break;
				case 2:
					$cat_class = "two_cat_" . $cat_counter;
					break;
				case 3:
					$cat_class = "three_cat_" . $cat_counter;
					break;
				case 4:
					$cat_class = "four_cat_" . $cat_counter;
					break;
				case 5:
					$cat_class = "five_cat_" . $cat_counter;
					break;
				default:
					if ($cat_counter < 7) {
						$cat_class = $cat_counter;
					} else {
						$cat_class = "more_than_6";
					}
			}

			?>

			<div class="category_<?php echo $cat_class; ?>">
				<div class="category_grid_box">
					<span class="category_item_bkg" style="background-image:url(<?php echo $image; ?>)"></span>
					<a href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>" class="category_item" >
						<h3 class="category_name"><?php echo $category->name; ?></h3>
					</a>
				</div>
			</div>

			<?php

		}

		?>

			<div class="clearfix"></div>

		<?php

	}

	woocommerce_reset_loop();

	return '<div class="row"><div class="categories_grid">' . ob_get_clean() . '</div></div>';
}

add_shortcode("product_categories_grid", "mr_tailor_product_categories");
