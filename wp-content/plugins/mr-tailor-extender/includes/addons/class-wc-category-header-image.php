<?php

if ( ! class_exists( 'MTCategoryHeaderImage' ) ) :

	/**
	 * MTCategoryHeaderImage class.
	 *
	 * Adds a Header Image to WooCommerce Product Category.
	 *
	 * @since 1.3
	*/
	class MTCategoryHeaderImage {

		/**
		 * The single instance of the class.
		 *
		 * @since 1.3
		 * @var MTCategoryHeaderImage
		*/
		protected static $_instance = null;

		/**
		 * MTCategoryHeaderImage constructor.
		 *
		 * @since 1.3
		*/
		public function __construct() {

			$this->enqueue_styles();
			$this->enqueue_scripts();

			add_action( 'product_cat_add_form_fields', array( $this, 'woocommerce_add_category_header_img' ) );
			add_action( 'product_cat_edit_form_fields', array( $this, 'woocommerce_edit_category_header_img' ), 10,2 );
			add_action( 'created_term', array( $this, 'woocommerce_category_header_img_save' ), 10,3 );
			add_action( 'edit_term', array( $this, 'woocommerce_category_header_img_save' ), 10,3 );
			add_filter( 'manage_edit-product_cat_columns', array( $this, 'woocommerce_product_cat_header_columns' ) );
			add_filter( 'manage_product_cat_custom_column', array( $this, 'woocommerce_product_cat_header_column' ), 10, 3 );

			add_filter( 'mrtailor_get_category_header_image', function() {
				return $this->woocommerce_get_header_image_url();
			} );
		}

		/**
		 * Ensures only one instance of MTCategoryHeaderImage is loaded or can be loaded.
		 *
		 * @since 1.3
		 *
		 * @return MTCategoryHeaderImage
		*/
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Enqueues styles.
		 *
		 * @since 1.0
		 * @return void
		 */
		public function enqueue_styles() {
			add_action( 'admin_enqueue_scripts', function() {
				wp_enqueue_style('mrtailor-category-header-admin-styles', plugins_url( 'assets/css/admin-wc-category-header-image.css', __FILE__ ), NULL );
			});

			add_action( 'wp_enqueue_scripts', function() {
				wp_enqueue_style('mrtailor-category-header-styles', plugins_url( 'assets/css/wc-category-header-image.css', __FILE__ ), NULL );
			});
		}

		/**
		 * Enqueues scripts.
		 *
		 * @since 1.0
		 * @return void
		 */
		public function enqueue_scripts() {

			add_action( 'admin_enqueue_scripts', function() {
				wp_enqueue_script(
					'mrtailor-category-header-image-admin-scripts',
					plugins_url( 'assets/js/admin-wc-category-header-image.js', __FILE__ ),
					array('jquery')
				);
			});
		}

		/**
		 * Category Header fields.
		 *
		 * @since 1.3
		 * @return void
		*/
		public function woocommerce_add_category_header_img() { ?>

			<div class="form-field">
				<label> <?php esc_html_e( 'Header', 'mrtailor-extender' ); ?> </label>
				<div id="product_cat_header" style="float:left; margin-right:10px; ">
					<img src="<?php echo wc_placeholder_img_src(); ?>" width="60px" height="60px" />
				</div>
				<div style="line-height:60px;">
					<input type="hidden" id="product_cat_header_id" name="product_cat_header_id" />
					<button type="submit" class="upload_header_button button"><?php esc_html_e( 'Upload/Add image', 'mrtailor-extender' ); ?></button>
					<button type="submit" class="remove_header_image_button button"><?php esc_html_e( 'Remove image', 'mrtailor-extender' ); ?></button>
				</div>
				<div class="clear"></div>
			</div>

		<?php
		}

		/**
		 * Edit category header field.
		 *
		 * @since 1.3
		 *
		 * @param mixed $term Term (category) being edited
		 * @param mixed $taxonomy Taxonomy of the term being edited
		 *
		 * @return void
		*/
		public function woocommerce_edit_category_header_img( $term, $taxonomy ) {
			global $woocommerce;

			$image 			= '';
			$header_id 	= absint( get_term_meta( $term->term_id, 'header_id', true ) );
			if ($header_id) :
				$image = wp_get_attachment_url( $header_id );
			else :
				$image = wc_placeholder_img_src();
			endif;

			?>

			<tr class="form-field">
				<th scope="row" valign="top"><label><?php esc_html_e( 'Header', 'mrtailor-extender' ); ?></label></th>
				<td>
					<div id="product_cat_header" style="background-image:url(<?php echo $image; ?>);"></div>
					<div style="line-height:60px;">
						<input type="hidden" id="product_cat_header_id" name="product_cat_header_id" value="<?php echo $header_id; ?>" />
						<button type="submit" class="upload_header_button button"><?php esc_html_e( 'Upload/Add image', 'mrtailor-extender' ); ?></button>
						<button type="submit" class="remove_header_image_button button"><?php esc_html_e( 'Remove image', 'mrtailor-extender' ); ?></button>
					</div>

					<div class="clear"></div>
				</td>
			</tr>

		<?php
		}

		/**
		 * Save category header image.
		 *
		 * @since 1.3
		 *
		 * @param mixed $term_id Term ID being saved
		 * @param mixed $tt_id
		 * @param mixed $taxonomy Taxonomy of the term being saved
		 *
		 * @return void
		 */
		public function woocommerce_category_header_img_save( $term_id, $tt_id, $taxonomy ) {
			if ( isset( $_POST['product_cat_header_id'] ) ) {
				update_woocommerce_term_meta( $term_id, 'header_id', absint( $_POST['product_cat_header_id'] ) );
			}

			delete_transient( 'wc_term_counts' );
		}

		/**
		 * Header column added to category admin.
		 *
		 * @since 1.3
		 *
		 * @param mixed $columns
		 *
		 * @return void
		 */
		public function woocommerce_product_cat_header_columns( $columns ) {
			$new_columns = array();
			$new_columns['thumb'] = esc_html__( 'Image', 'mrtailor-extender' );
			$new_columns['header'] = esc_html__( 'Header', 'mrtailor-extender' );
			unset( $columns['thumb'] );

			return array_merge( $new_columns, $columns );
		}

		/**
		 * Thumbnail column value added to category admin.
		 *
		 * @since 1.3
		 *
		 * @param mixed $columns
		 * @param mixed $column
		 * @param mixed $id
		 *
		 * @return void
		 */

		public function woocommerce_product_cat_header_column( $columns, $column, $id ) {
			global $woocommerce;

			if ( $column == 'header' ) {

				$image 			= '';
				$thumbnail_id 	= get_term_meta( $id, 'header_id', true );

				if ($thumbnail_id)
					$image = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail' )[0];
				else
					$image = wc_placeholder_img_src();

				$columns .= '<img src="' . $image . '" alt="Thumbnail" class="wp-post-image" height="40" width="40" />';

			}

			return $columns;
		}

		/**
		 * Get category header image url.
		 *
		 * @since 1.3
		 *
		 * @param mixed $cat_ID -image's product category ID (if empty/false auto loads curent product category ID)
		 *
		 * @return mixed (string|false)
		 */
		public function woocommerce_get_header_image_url($cat_ID = false) {
			if ($cat_ID==false && is_product_category()){
				global $wp_query;

				// get the query object
				$cat = $wp_query->get_queried_object();

				// get the thumbnail id user the term_id
				$cat_ID = $cat->term_id;
			}

		    $thumbnail_id = get_term_meta($cat_ID, 'header_id', true );

		    // get the image URL
		   return wp_get_attachment_url( $thumbnail_id );
		}
	}

endif;

$mt_wc_cat_header = new MTCategoryHeaderImage;
