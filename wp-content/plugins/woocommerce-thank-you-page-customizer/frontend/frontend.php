<?php

/**
 * Class VI_WOOCOMMERCE_THANK_YOU_PAGE_Frontend_Frontend
 *
 */

//wp_print_scripts-1046, apply_layout,enqueue_scripts -3121
class VI_WOOCOMMERCE_THANK_YOU_PAGE_Frontend_Frontend {
	protected $settings, $customize_preview_data, $is_rtl;
	protected $order_id, $key;
	protected $prefix;
	protected $products;
	protected $text_editor;
	protected $text_editor_id;
	protected $products_id;
	protected $billing_first_name, $billing_last_name;
	protected $billing_full_name;
	protected $billing_address, $shipping_address;
	protected $coupon_select, $coupon_code, $coupon_amount, $coupon_date_expires, $last_valid_date;
	protected $is_customize_preview;
	protected $characters_array;
	protected $enable;
	protected $google_map_address;
	protected $order_items_products;
	protected $order_items_products_categories;
	protected $active_components;
	protected $active_product_options;
	protected $shortcodes;
	protected $include_google_api;
	protected $payment_method_html;
	protected $include_bing_api, $bing_map_address;
	protected $language;

	public function __construct() {
		$this->settings               = new VI_WOOCOMMERCE_THANK_YOU_PAGE_DATA();
		$this->language               = '';
		$this->prefix                 = 'woocommerce-thank-you-page-';
		$this->text_editor_id         = 0;
		$this->products_id            = 0;
		$this->active_components      = array();
		$this->active_product_options = array();
		$this->shortcodes             = array(
			'order_number'   => '',
			'order_status'   => '',
			'order_date'     => '',
			'order_total'    => '',
			'order_subtotal' => '',
			'items_count'    => '',
			'payment_method' => '',

			'shipping_method'            => '',
			'shipping_address'           => '',
			'formatted_shipping_address' => '',

			'billing_address'           => '',
			'formatted_billing_address' => '',
			'billing_country'           => '',
			'billing_city'              => '',

			'billing_first_name'          => '',
			'billing_last_name'           => '',
			'formatted_billing_full_name' => '',
			'billing_email'               => '',

			'shop_title' => '',
			'home_url'   => '',
			'shop_url'   => '',
		);
		$this->payment_method_html    = '';
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_print_scripts', array( $this, 'wp_print_scripts' ) );
		add_action( 'template_redirect', array( $this, 'track_product_view' ), 21 );
		add_filter( 'the_content', array( $this, 'the_content' ));
		add_action( 'wp_ajax_woo_thank_you_page_layout', array( $this, 'apply_layout' ) );
//        add_action('wp_ajax_woo_thank_you_page_select_order', array($this, 'select_order'));
		add_action( 'wp_ajax_woo_thank_you_page_get_products_shortcode', array( $this, 'get_products_shortcode' ) );
		add_action( 'wp_ajax_woo_thank_you_page_get_text_editor_content', array( $this, 'get_text_editor_content' ) );
		add_action( 'media_buttons', array( $this, 'shortcut_to_shortcodes' ) );
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );
		add_action( 'wp_footer', array( $this, 'payment_method_html_hold' ) );
		add_action( 'wp_ajax_woocommerce_thank_you_page_customizer_send_email', array( $this, 'send_email_action' ) );
		add_action( 'wp_ajax_nopriv_woocommerce_thank_you_page_customizer_send_email', array( $this, 'send_email_action' ) );
		add_filter( 'wc_get_template', array( $this, 'wc_get_template' ), PHP_INT_MAX, 5 );
//		add_action( 'woocommerce_thankyou', array( $this,'my_custom_tracking' ));

		add_filter( 'woocommerce_valid_order_statuses_for_order_again', array(
			'WTYPC_FUNCTIONS',
			'woocommerce_valid_order_statuses_for_order_again'
		) );
	}

	public function my_custom_tracking( $order_id ) {
		// Lets grab the order
		$order = new WC_Order( $order_id );

		// Ohne Mehrwertsteuer und Liefergebuehr
		$subtotal = $order->get_total() - $order->get_total_shipping() - $order->get_total_tax();
		print_r( $order->get_subtotal() );
	}

	public function wc_get_template( $located, $template_name, $args, $template_path, $default_path ) {
		if ( $this->enable && $template_name === 'checkout/thankyou.php' ) {
			$located = VI_WOOCOMMERCE_THANK_YOU_PAGE_TEMPLATES . 'thankyou.php';
		}

		return $located;
	}

	public function get_text_editor_content() {
		$shortcodes = isset( $_POST['shortcodes'] ) ? array_map( 'stripslashes', $_POST['shortcodes'] ) : array();
		$content    = isset( $_POST['content'] ) ? wp_kses_post( stripslashes( $_POST['content'] ) ) : array();
		if ( is_array( $shortcodes ) && count( $shortcodes ) ) {
			foreach ( $shortcodes as $key => $value ) {
				$content = str_replace( "{{$key}}", $value, $content );
			}
		}
		wp_send_json( array( 'html' => do_shortcode( $content ) ) );
		die;
	}

	public function send_email_action() {
		$language_ajax = isset( $_POST['language_ajax'] ) ? sanitize_text_field( $_POST['language_ajax'] ) : '';
		$shortcodes    = isset( $_POST['shortcodes'] ) ? array_map( 'sanitize_text_field', $_POST['shortcodes'] ) : '';
		$coupon_code   = isset( $_POST['coupon_code'] ) ? sanitize_text_field( $_POST['coupon_code'] ) : '';
		$order_id      = isset( $shortcodes['order_number'] ) ? $shortcodes['order_number'] : '';
		$email         = isset( $shortcodes['billing_email'] ) ? sanitize_email( $shortcodes['billing_email'] ) : '';
		$message_fail  = __( 'There was problem sending email but you can always view your coupon gift by going to Account settings/Orders', 'woocommerce-thank-you-page-customizer' );
		if ( $order_id && $email && $coupon_code ) {
			if ( get_transient( 'woocommerce_thank_you_page_customizer_send_email_' . $order_id ) ) {
				wp_send_json( array(
					'message' => __( 'Coupon code was sent to your billing email. If you did not receive any email, please go to Account settings/Orders to view your coupon gift anytime.', 'woocommerce-thank-you-page-customizer' ),
				) );
				die;
			} else {
				$coupon = new WC_Coupon( $coupon_code );
				if ( $coupon ) {
					if ( $coupon->get_discount_type() == 'percent' ) {
						$coupon_amount = $coupon->get_amount() . '%';
					} else {
						$coupon_amount = $this->wc_price( $coupon->get_amount() );
					}
					$coupon_date_expires = $coupon->get_date_expires();
					$last_valid_date     = empty( $coupon_date_expires ) ? '' : date_i18n( 'F d, Y', strtotime( $coupon_date_expires ) - 86400 );
					$coupon_date_expires = empty( $coupon_date_expires ) ? esc_html__( 'never expires', 'woocommerce-thank-you-page-customizer' ) : date_i18n( 'F d, Y', strtotime( $coupon_date_expires ) );
					$send                = $this->send_email( $email, $coupon_code, $coupon_date_expires, $last_valid_date, $coupon_amount, $shortcodes, $language_ajax, true );
					if ( $send ) {
						set_transient( 'woocommerce_thank_you_page_customizer_send_email_' . $order_id, time(), 86400 );
						wp_send_json( array(
							'message' => __( 'Coupon code was sent to your billing email.', 'woocommerce-thank-you-page-customizer' ),
						) );
						die;
					} else {
						wp_send_json( array(
							'message' => $message_fail,
						) );
						die;
					}

				}
			}

		}
		wp_send_json( array(
			'message' => $message_fail,
		) );
		die;
	}

	public function email_style( $css ) {
		$css .= '.woo-thank-you-page-customizer-coupon-input{line-height:46px;display:block;text-align: center;font-size: 24px;width: 100%;height: 46px;vertical-align: middle;margin: 0;color:' . $this->get_params( 'coupon_code_color' ) . ';background-color:' . $this->get_params( 'coupon_code_bg_color' ) . ';border-width:' . $this->get_params( 'coupon_code_border_width' ) . 'px;border-style:' . $this->get_params( 'coupon_code_border_style' ) . ';border-color:' . $this->get_params( 'coupon_code_border_color' ) . ';}';

		return $css;
	}

	public function send_email( $user_email, $coupon_code, $coupon_date_expires = '', $last_valid_date = '', $coupon_amount = '', $shortcodes = array(), $language = '', $return = false ) {
		$language = $language ? '_' . $language : '';
		$headers  = "Content-Type: text/html\r\n";
		$mailer   = WC()->mailer();
		$email    = new WC_Email();
		if ( VI_WOOCOMMERCE_THANK_YOU_PAGE_DATA::email_template_customizer_active() && ( $email_template = $this->get_params( 'email_template', $language ) ) ) {
			$viwec_email = new VIWEC_Render_Email_Template( array( 'template_id' => $email_template ) );
			$subject     = $viwec_email->get_subject();
			$subject     = str_replace( array(
				'{wtypc_coupon_code}',
				'{wtypc_coupon_date_expires}',
				'{wtypc_last_valid_date}',
				'{wtypc_coupon_amount}'
			), array( $coupon_code, $coupon_date_expires, $last_valid_date, $coupon_amount ), $subject );
			ob_start();
			$viwec_email->get_content();
			$content = ob_get_clean();
			$content = str_replace( array(
				'{wtypc_coupon_code}',
				'{wtypc_coupon_date_expires}',
				'{wtypc_last_valid_date}',
				'{wtypc_coupon_amount}'
			), array( $coupon_code, $coupon_date_expires, $last_valid_date, $coupon_amount ), $content );
			if ( is_array( $shortcodes ) && count( $shortcodes ) ) {
				foreach ( $shortcodes as $key => $value ) {
					$content = str_replace( '{wtypc_' . $key . '}', $value, $content );
					$subject = str_replace( '{wtypc_' . $key . '}', $value, $subject );
				}
			}
		} else {
			$content             = stripslashes( $this->get_params( 'coupon_email_content', $language ) );
			$subject             = stripslashes( $this->get_params( 'coupon_email_subject', $language ) );
			$heading             = stripslashes( $this->get_params( 'coupon_email_heading', $language ) );
			$coupon_code_style_1 = '<div class="woo-thank-you-page-customizer-coupon-input">' . $coupon_code . '</div>';
			$content             = str_replace( '{coupon_code_style_1}', $coupon_code_style_1, $content );
			$content             = str_replace( array(
				'{coupon_code}',
				'{coupon_date_expires}',
				'{last_valid_date}',
				'{coupon_amount}',
			), array(
				$coupon_code,
				$coupon_date_expires,
				$last_valid_date,
				$coupon_amount,
			), $content );
			$subject             = str_replace( array(
				'{coupon_code}',
				'{coupon_date_expires}',
				'{last_valid_date}',
				'{coupon_amount}'
			), array( $coupon_code, $coupon_date_expires, $last_valid_date, $coupon_amount ), $subject );
			$heading             = str_replace( array(
				'{coupon_code}',
				'{coupon_date_expires}',
				'{last_valid_date}',
				'{coupon_amount}'
			), array( $coupon_code, $coupon_date_expires, $last_valid_date, $coupon_amount ), $heading );
			if ( is_array( $shortcodes ) && count( $shortcodes ) ) {
				foreach ( $shortcodes as $key => $value ) {
					$content = str_replace( '{' . $key . '}', $value, $content );
					$subject = str_replace( '{' . $key . '}', $value, $subject );
					$heading = str_replace( '{' . $key . '}', $value, $heading );
				}
			}
			add_filter( 'woocommerce_email_styles', array( $this, 'email_style' ) );
			$content = $email->style_inline( $mailer->wrap_message( $heading, $content ) );
		}
		$send = $email->send( $user_email, $subject, $content, $headers, array() );
		remove_filter( 'woocommerce_email_styles', array( $this, 'email_style' ) );
		if ( $return ) {
			return $send;
		}
	}

	public function shortcut_to_shortcodes( $editor_id ) {
		if ( $editor_id == 'woocommerce-thank-you-page-wp-editor' ) {
			ob_start();
			?>
            <span class="<?php echo $this->set( 'available-shortcodes-shortcut' ) ?>"><?php esc_html_e( 'Shortcodes', 'woocommerce-thank-you-page-customizer' ) ?></span>
			<?php
			echo ob_get_clean();
		}
	}

	public function track_product_view() {
		if ( ! is_singular( 'product' ) ) {
			return;
		}
		if ( is_active_widget( false, false, 'woocommerce_recently_viewed_products', true ) ) {
			return;
		}
		$products = json_decode( $this->get_params( 'products' ), true );
		if ( is_array( $products ) && count( $products ) ) {
			foreach ( $products as $products_data ) {
				if ( is_array( $products_data ) ) {
					if ( isset( $products_data['product_options'] ) && ! in_array( $products_data['product_options'], $this->active_product_options ) ) {
						$this->active_product_options[] = $products_data['product_options'];
					}
				}
			}
		}
		if ( ! current_user_can( 'manage_options' ) && ! is_array( $this->active_product_options ) || ! in_array( 'recently_viewed', $this->active_product_options ) ) {
			return;
		}
		global $post;

		if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) { // @codingStandardsIgnoreLine.
			$viewed_products = array();
		} else {
			$viewed_products = wp_parse_id_list( (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) ); // @codingStandardsIgnoreLine.
		}

		// Unset if already in viewed products list.
		$keys = array_flip( $viewed_products );

		if ( isset( $keys[ $post->ID ] ) ) {
			unset( $viewed_products[ $keys[ $post->ID ] ] );
		}

		$viewed_products[] = $post->ID;

		if ( count( $viewed_products ) > 15 ) {
			array_shift( $viewed_products );
		}
		// Store for session only.
		wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
	}

	public function get_products_shortcode() {
		$product_ids_data                 = isset( $_POST['product_ids'] ) ? json_decode( stripslashes( $_POST['product_ids'] ), true ) : array();//array('id'=>'title')
		$excluded_product_ids_data        = isset( $_POST['excluded_product_ids'] ) ? json_decode( stripslashes( $_POST['excluded_product_ids'] ), true ) : array();
		$product_categories_data          = isset( $_POST['product_categories'] ) ? json_decode( stripslashes( $_POST['product_categories'] ), true ) : array();
		$excluded_product_categories_data = isset( $_POST['excluded_product_categories'] ) ? json_decode( stripslashes( $_POST['excluded_product_categories'] ), true ) : array();

		$order_id               = isset( $_POST['order_id'] ) ? sanitize_text_field( $_POST['order_id'] ) : '';
		$order_by               = isset( $_POST['order_by'] ) ? sanitize_text_field( $_POST['order_by'] ) : 'title';
		$visibility             = isset( $_POST['visibility'] ) ? $_POST['visibility'] : 'visible';
		$order_                 = isset( $_POST['order'] ) ? sanitize_text_field( $_POST['order'] ) : 'desc';
		$product_options        = isset( $_POST['product_options'] ) ? sanitize_text_field( $_POST['product_options'] ) : 'none';
		$limit                  = isset( $_POST['limit'] ) ? intval( sanitize_text_field( $_POST['limit'] ) ) : '4';
		$columns                = isset( $_POST['columns'] ) ? intval( sanitize_text_field( $_POST['columns'] ) ) : '';
		$slider_loop            = isset( $_POST['slider_loop'] ) ? sanitize_text_field( $_POST['slider_loop'] ) : '1';
		$slider_move            = isset( $_POST['slider_move'] ) ? sanitize_text_field( $_POST['slider_move'] ) : '1';
		$slider_slideshow       = isset( $_POST['slider_slideshow'] ) ? sanitize_text_field( $_POST['slider_slideshow'] ) : '1';
		$slider_slideshow_speed = isset( $_POST['slider_slideshow_speed'] ) ? sanitize_text_field( $_POST['slider_slideshow_speed'] ) : '5000';
		$slider_pause_on_hover  = isset( $_POST['slider_pause_on_hover'] ) ? sanitize_text_field( $_POST['slider_pause_on_hover'] ) : '1';

		$product_ids                 = array_keys( $product_ids_data );
		$excluded_product_ids        = array_keys( $excluded_product_ids_data );
		$product_categories          = array_keys( $product_categories_data );
		$excluded_product_categories = array_keys( $excluded_product_categories_data );
		$html                        = '';
		$found_products              = array();
		switch ( $product_options ) {
			case 'best_selling':
				$args = array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'posts_per_page' => - 1,
					'meta_key'       => 'total_sales',
					'order'          => 'DESC',
					'orderby'        => 'meta_value_num',
				);
				if ( count( $product_categories ) || count( $excluded_product_categories ) ) {
					$args['tax_query'] = array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $excluded_product_categories,
							'operator' => 'NOT IN'
						)
					);
					if ( count( $product_categories ) ) {
						$args['tax_query'][] = array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $product_categories,
							'operator' => 'IN'
						);
					}
				}
				if ( count( $product_ids ) ) {
					$args['post__in'] = $product_ids;
				}
				$the_query = new WP_Query( $args );
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$found_products[] = get_the_ID();
					}
				}
				wp_reset_postdata();
				if ( count( $excluded_product_ids ) ) {
					$found_products = array_diff( $found_products, $excluded_product_ids );
				}
				break;
			case 'sale':
				$sale_products = wc_get_product_ids_on_sale();
				if ( count( $product_ids ) ) {
					$sale_products = array_intersect( $sale_products, $product_ids );
				}
				$sale_products = array_diff( $sale_products, $excluded_product_ids );
				if ( count( $sale_products ) && count( $product_categories ) || count( $excluded_product_categories ) ) {
					$args = array(
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'posts_per_page' => - 1,
						'post__in'       => $sale_products,
						'tax_query'      => array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => $excluded_product_categories,
								'operator' => 'NOT IN'
							)
						)
					);
					if ( count( $product_categories ) ) {
						$args['tax_query'][] = array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $product_categories,
							'operator' => 'IN'
						);
					}
					$the_query = new WP_Query( $args );
					if ( $the_query->have_posts() ) {
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							$found_products[] = get_the_ID();
						}
					}
					wp_reset_postdata();
				} else {
					$found_products = $sale_products;
				}
				break;
			case 'featured':
				$featured_products = wc_get_featured_product_ids();
				if ( count( $product_ids ) ) {
					$featured_products = array_intersect( $featured_products, $product_ids );
				}
				$featured_products = array_diff( $featured_products, $excluded_product_ids );
				if ( count( $featured_products ) ) {
					if ( count( $featured_products ) && count( $product_categories ) || count( $excluded_product_categories ) ) {
						$args = array(
							'post_type'      => 'product',
							'post_status'    => 'publish',
							'posts_per_page' => - 1,
							'post__in'       => $featured_products,
							'tax_query'      => array(
								'relation' => 'AND',
								array(
									'taxonomy' => 'product_cat',
									'field'    => 'term_id',
									'terms'    => $excluded_product_categories,
									'operator' => 'NOT IN'
								)
							)
						);
						if ( count( $product_categories ) ) {
							$args['tax_query'][] = array(
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => $product_categories,
								'operator' => 'IN'
							);
						}
						$the_query = new WP_Query( $args );
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								$found_products[] = get_the_ID();
							}
						}
						wp_reset_postdata();
					} else {
						$found_products = $featured_products;
					}
				}
				break;
			case 'recent':
				$args = array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'posts_per_page' => - 1,
					'order'          => 'DESC',
					'orderby'        => 'date',
				);
				if ( count( $product_categories ) || count( $excluded_product_categories ) ) {
					$args['tax_query'] = array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $excluded_product_categories,
							'operator' => 'NOT IN'
						)
					);
					if ( count( $product_categories ) ) {
						$args['tax_query'][] = array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $product_categories,
							'operator' => 'IN'
						);
					}
				}
				if ( count( $product_ids ) ) {
					$args['post__in'] = $product_ids;
				}
				$the_query = new WP_Query( $args );
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$found_products[] = get_the_ID();
					}
				}

				wp_reset_postdata();
				if ( count( $excluded_product_ids ) ) {
					$found_products = array_diff( $found_products, $excluded_product_ids );
				}
				break;
			case 'recently_viewed':
				$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) : array(); // @codingStandardsIgnoreLine
				$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
				if ( count( $product_ids ) ) {
					$viewed_products = array_intersect( $viewed_products, $product_ids );
				}
				$viewed_products = array_diff( $viewed_products, $excluded_product_ids );
				if ( count( $viewed_products ) && count( $product_categories ) || count( $excluded_product_categories ) ) {
					$args = array(
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'posts_per_page' => - 1,
						'post__in'       => $viewed_products,
						'tax_query'      => array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => $excluded_product_categories,
								'operator' => 'NOT IN'
							)
						)
					);
					if ( count( $product_categories ) ) {
						$args['tax_query'][] = array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $product_categories,
							'operator' => 'IN'
						);
					}
					$the_query = new WP_Query( $args );
					if ( $the_query->have_posts() ) {
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							$found_products[] = get_the_ID();
						}
					}
					wp_reset_postdata();
				} else {
					$found_products = $viewed_products;
				}
				break;
			case 'related':
				$order = function_exists( 'wc_get_order' ) ? wc_get_order( $order_id ) : new WC_Order( $order_id );;
				if ( $order ) {
					$order_items          = $order->get_items();
					$order_items_products = array();
					if ( is_array( $order_items ) && count( $order_items ) ) {
						foreach ( $order_items as $order_item ) {
							$product_id             = $order_item->get_product_id();
							$order_items_products[] = $product_id;
							if ( count( $product_ids ) && ! in_array( $product_id, $product_ids ) ) {
								continue;
							}
							if ( count( $excluded_product_ids ) && in_array( $product_id, $product_ids ) ) {
								continue;
							}
							if ( count( $product_categories ) || count( $excluded_product_categories ) ) {
								$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : new WC_Product( $product_id );;
								if ( $product ) {
									$categories = $product->get_category_ids();
									if ( count( $product_categories ) && ! count( array_intersect( $categories, $product_categories ) ) ) {
										continue;
									}
									if ( count( $excluded_product_categories ) && count( array_intersect( $categories, $excluded_product_categories ) ) ) {
										continue;
									}
								}
							}
							$p_related = wc_get_related_products( $product_id );
							if ( is_array( $p_related ) && count( $p_related ) ) {
								$found_products += $p_related;
							}
						}
					}
					$found_products = array_diff( $found_products, $order_items_products );
				}

				break;
			case 'up_sells':
				$order = function_exists( 'wc_get_order' ) ? wc_get_order( $order_id ) : new WC_Order( $order_id );;
				if ( $order ) {
					$order_items          = $order->get_items();
					$order_items_products = array();
					if ( is_array( $order_items ) && count( $order_items ) ) {
						foreach ( $order_items as $order_item ) {
							$product_id             = $order_item->get_product_id();
							$order_items_products[] = $product_id;
							if ( count( $product_ids ) && ! in_array( $product_id, $product_ids ) ) {
								continue;
							}
							if ( count( $excluded_product_ids ) && in_array( $product_id, $product_ids ) ) {
								continue;
							}
							if ( count( $product_categories ) || count( $excluded_product_categories ) ) {
								$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : new WC_Product( $product_id );;
								if ( $product ) {
									$categories = $product->get_category_ids();
									if ( count( $product_categories ) && ! count( array_intersect( $categories, $product_categories ) ) ) {
										continue;
									}
									if ( count( $excluded_product_categories ) && count( array_intersect( $categories, $excluded_product_categories ) ) ) {
										continue;
									}
								}
							}
							$p_up_sells = get_post_meta( $product_id, '_upsell_ids', true );
							if ( is_array( $p_up_sells ) && count( $p_up_sells ) ) {
								$found_products += $p_up_sells;
							}
						}
					}
					$found_products = array_diff( $found_products, $order_items_products );
				}

				break;
			case 'cross_sells':
				$order = function_exists( 'wc_get_order' ) ? wc_get_order( $order_id ) : new WC_Order( $order_id );;
				if ( $order ) {
					$order_items          = $order->get_items();
					$order_items_products = array();
					if ( is_array( $order_items ) && count( $order_items ) ) {
						foreach ( $order_items as $order_item ) {
							$product_id             = $order_item->get_product_id();
							$order_items_products[] = $product_id;
							if ( count( $product_ids ) && ! in_array( $product_id, $product_ids ) ) {
								continue;
							}
							if ( count( $excluded_product_ids ) && in_array( $product_id, $product_ids ) ) {
								continue;
							}
							if ( count( $product_categories ) || count( $excluded_product_categories ) ) {
								$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : new WC_Product( $product_id );;
								if ( $product ) {
									$categories = $product->get_category_ids();
									if ( count( $product_categories ) && ! count( array_intersect( $categories, $product_categories ) ) ) {
										continue;
									}
									if ( count( $excluded_product_categories ) && count( array_intersect( $categories, $excluded_product_categories ) ) ) {
										continue;
									}
								}
							}
							$p_cross_sells = get_post_meta( $product_id, '_crosssell_ids', true );
							if ( is_array( $p_cross_sells ) && count( $p_cross_sells ) ) {
								$found_products += $p_cross_sells;
							}
						}
					}
					$found_products = array_diff( $found_products, $order_items_products );
				}
				break;
			case 'same_category':
				$order = function_exists( 'wc_get_order' ) ? wc_get_order( $order_id ) : new WC_Order( $order_id );;
				if ( $order ) {
					$order_items          = $order->get_items();
					$order_items_products = array();
					if ( is_array( $order_items ) && count( $order_items ) ) {
						foreach ( $order_items as $order_item ) {
							$product_id             = $order_item->get_product_id();
							$order_items_products[] = $product_id;
							$product                = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : new WC_Product( $product_id );;
							if ( $product ) {
								$categories = $product->get_category_ids();
								if ( count( $categories ) ) {
									if ( count( $product_categories ) ) {
										$categories = array_intersect( $categories, $product_categories );
									}
									$args = array(
										'post_type'      => 'product',
										'post_status'    => 'publish',
										'posts_per_page' => - 1,
										'tax_query'      => array(
											'relation' => 'AND',
											array(
												'taxonomy' => 'product_cat',
												'field'    => 'term_id',
												'terms'    => $categories,
												'operator' => 'IN'
											),
											array(
												'taxonomy' => 'product_cat',
												'field'    => 'term_id',
												'terms'    => $excluded_product_categories,
												'operator' => 'NOT IN'
											)
										)
									);
									if ( count( $product_ids ) ) {
										$args['post__in'] = $product_ids;
									}

									$the_query = new WP_Query( $args );
									if ( $the_query->have_posts() ) {
										while ( $the_query->have_posts() ) {
											$the_query->the_post();
											$found_products[] = get_the_ID();
										}
									}
									wp_reset_postdata();
									/*post__not_in will be ignored if using in the same query as post__in*/
									if ( count( $excluded_product_ids ) ) {
										$found_products = array_diff( $found_products, $excluded_product_ids );
									}
								}
							}
						}
					}
					$found_products = array_diff( $found_products, $order_items_products );
				}
				break;
			case 'none':
				if ( count( $product_categories ) ) {
					$args      = array(
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'posts_per_page' => - 1,
						'tax_query'      => array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => $product_categories,
								'operator' => 'IN'
							),
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => $excluded_product_categories,
								'operator' => 'NOT IN'
							)
						)
					);
					$the_query = new WP_Query( $args );
					if ( $the_query->have_posts() ) {
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							$found_products[] = get_the_ID();
						}
					}
					wp_reset_postdata();
					if ( count( $product_ids ) ) {
						if ( count( $found_products ) ) {
							$found_products = array_intersect( $found_products, $product_ids );

						} else {
							$found_products = $product_ids;

						}
					}
					if ( count( $excluded_product_ids ) ) {
						$found_products = array_diff( $found_products, $excluded_product_ids );
					}
				} elseif ( count( $excluded_product_categories ) ) {
					$args      = array(
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'posts_per_page' => - 1,
						'tax_query'      => array(
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => $excluded_product_categories,
								'operator' => 'NOT IN'
							)
						)
					);
					$the_query = new WP_Query( $args );
					if ( $the_query->have_posts() ) {
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							$found_products[] = get_the_ID();
						}
					}
					wp_reset_postdata();
					if ( count( $product_ids ) ) {
						if ( count( $found_products ) ) {
							$found_products = array_intersect( $found_products, $product_ids );

						} else {
							$found_products = $product_ids;

						}
					}
					if ( count( $excluded_product_ids ) ) {
						$found_products = array_diff( $found_products, $excluded_product_ids );
					}
				} else {
					if ( count( $product_ids ) ) {
						$found_products = array_diff( $product_ids, $excluded_product_ids );
					} elseif ( count( $excluded_product_ids ) ) {
						$args      = array(
							'post_type'      => 'product',
							'post_status'    => 'publish',
							'posts_per_page' => - 1,
							'post__not_in'   => $excluded_product_ids
						);
						$the_query = new WP_Query( $args );
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								$found_products[] = get_the_ID();
							}
						}
						wp_reset_postdata();
					}
				}
				break;
			default:
		}
		$found_products = array_unique( $found_products );
		ob_start();
		?>
        <div class="<?php echo $this->set( array( 'products-sliders' ) ) ?> vi-flexslider">
            <div class="<?php echo $this->set( 'products-content' ) ?>"
                 data-product_ids="<?php echo htmlentities( json_encode( $product_ids_data ) ) ?>"
                 data-excluded_product_ids="<?php echo htmlentities( json_encode( $excluded_product_ids_data ) ) ?>"
                 data-product_categories="<?php echo htmlentities( json_encode( $product_categories_data ) ) ?>"
                 data-excluded_product_categories="<?php echo htmlentities( json_encode( $excluded_product_categories_data ) ) ?>"
                 data-order_by="<?php echo $order_by ?>"
                 data-visibility="<?php echo $visibility ?>"
                 data-order="<?php echo $order_ ?>"
                 data-wtypc_columns="<?php echo $columns ?>"
                 data-limit="<?php echo $limit ?>"
                 data-product_options="<?php echo $product_options ?>"
                 data-slider_loop="<?php echo $slider_loop ?>"
                 data-slider_move="<?php echo $slider_move ?>"
                 data-slider_slideshow="<?php echo $slider_slideshow ?>"
                 data-slider_slideshow_speed="<?php echo $slider_slideshow_speed ?>"
                 data-slider_pause_on_hover="<?php echo $slider_pause_on_hover ?>"
            >
				<?php
				if ( count( $found_products ) ) {
					$show_products = array();
					$args1         = array(
						'post_type'      => 'product',
						'posts_per_page' => - 1,
						'post__in'       => $found_products,
						'order'          => strtoupper( $order_ ),
						'post_parent'    => 0,
					);
					switch ( $order_by ) {
						case 'id':
							$args1['orderby'] = 'ID';
							break;
						case 'rating':
							$args1['meta_key'] = '_wc_average_rating';
							$args1['orderby']  = 'meta_value_num';
							break;
						case 'popularity':
							$args1['meta_key'] = 'total_sales';
							$args1['orderby']  = 'meta_value_num';
							break;
						case 'title':
							$args1['orderby'] = $order_by;
							break;
						case 'rand':
						case 'date':
						case 'menu_order':
							$args1['orderby'] = $order_by;
							break;
						default:
					}
					$the_query = new WP_Query( $args1 );
					if ( $the_query->have_posts() ) {
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							$post_id = get_the_ID();
							$prd     = function_exists( 'wc_get_product' ) ? wc_get_product( $post_id ) : new WC_Product( $post_id );
							if ( ! in_array( $prd->get_type(), array(
								'simple',
								'variable',
								'external',
								'grouped',
								'bundle'
							) ) ) {
								continue;
							}
							if ( $prd->get_catalog_visibility() != $visibility ) {
								continue;
							}
							$show_products[] = $post_id;
						}
					}
					wp_reset_postdata();
					if ( count( $show_products ) ) {
						if ( $limit > 0 ) {
							$num = 0;
							foreach ( $show_products as $show_products_id ) {
								$shortcode = do_shortcode( '[products ids="' . $show_products_id . '" limit="' . 1 . '" columns="' . 1 . '" visibility="' . $visibility . '"]' );
								if ( $shortcode ) {
									?>
                                    <div class="<?php echo $this->set( 'products-content-item' ) ?>">
										<?php
										echo $shortcode;
										?>
                                    </div>
									<?php
									$num ++;
								}
								if ( $num == $limit ) {
									break;
								}
							}
						} else {
							foreach ( $show_products as $show_products_id ) {
								$shortcode = do_shortcode( '[products ids="' . $show_products_id . '" limit="' . 1 . '" columns="' . 1 . '" visibility="' . $visibility . '"]' );
								if ( $shortcode ) {
									?>
                                    <div class="<?php echo $this->set( 'products-content-item' ) ?>">
										<?php
										echo $shortcode;
										?>
                                    </div>
									<?php
								}
							}
						}

					} else {
						esc_html_e( 'No matched products found', 'woocommerce-thank-you-page-customizer' );
					}

				} else {
					esc_html_e( 'No matched products found', 'woocommerce-thank-you-page-customizer' );
				}
				?>
            </div>
        </div>
        <span class="<?php echo $this->set( 'products-edit' ) ?> wtyp_icons-edit"><?php esc_html_e( 'Edit', 'woocommerce-thank-you-page-customizer' ) ?></span>
		<?php
		$html = ob_get_clean();

		wp_send_json( array(
			'html' => $html,
		) );
		die;
	}

	public function select_order() {
		$order_id = isset( $_POST['order_id'] ) ? sanitize_text_field( $_POST['order_id'] ) : '';
		if ( $order_id ) {
			$order = function_exists( 'wc_get_order' ) ? wc_get_order( $order_id ) : new WC_Order( $order_id );;
			if ( $order ) {
				$data                   = new VI_WOOCOMMERCE_THANK_YOU_PAGE_DATA();
				$option                 = $data->get_params();
				$option['select_order'] = $order->get_id();
				update_option( 'woo_thank_you_page_params', $option );
				wp_send_json( array(
					'url' => admin_url( 'customize.php' ) . '?url=' . urlencode( $order->get_checkout_order_received_url() ) . '&autofocus[section]=woo_thank_you_page_design_general',
				) );
			}
		}
		die;
	}

	public function apply_layout() {
		$this->is_customize_preview = true;
		$order_id                   = isset( $_POST['order_id'] ) ? $_POST['order_id'] : '';
		$change_url                 = isset( $_POST['change_url'] ) ? sanitize_text_field( $_POST['change_url'] ) : '';
		$this->payment_method_html  = isset( $_POST['payment_method_html'] ) ? wp_kses_post( base64_decode( $_POST['payment_method_html'] ) ) : '';
		$this->google_map_address   = isset( $_POST['google_map_address'] ) ? wp_kses_post( base64_decode( $_POST['google_map_address'] ) ) : '';
		$this->bing_map_address     = isset( $_POST['bing_map_address'] ) ? wp_kses_post( base64_decode( $_POST['bing_map_address'] ) ) : '';
		if ( $change_url && $order_id ) {
			$order_received_url = '';
			$order              = function_exists( 'wc_get_order' ) ? wc_get_order( $order_id ) : new WC_Order( $order_id );;
			if ( $order ) {
				$data                   = new VI_WOOCOMMERCE_THANK_YOU_PAGE_DATA();
				$option                 = $data->get_params();
				$option['select_order'] = $order->get_id();
				update_option( 'woo_thank_you_page_params', $option );
				$order_received_url = admin_url( 'customize.php' ) . '?url=' . urlencode( $order->get_checkout_order_received_url() ) . '&autofocus[section]=woo_thank_you_page_design_general';
			}
			wp_send_json( array(
				'url' => $order_received_url,
			) );
		} else {
			$order = function_exists( 'wc_get_order' ) ? wc_get_order( $order_id ) : new WC_Order( $order_id );;
			if ( $order ) {
				$shortcodes                     = array(
					'order_number'   => $order_id,
					'order_status'   => $order->get_status(),
					'order_date'     => $order->get_date_created() ? $order->get_date_created()->date_i18n( 'F d, Y' ) : '',
					'order_total'    => $order->get_formatted_order_total(),
					'order_subtotal' => $order->get_subtotal_to_display(),
					'items_count'    => $order->get_item_count(),
					'payment_method' => $order->get_payment_method_title(),

					'shipping_method'            => $order->get_shipping_method(),
					'formatted_shipping_address' => $order->get_formatted_shipping_address(),

					'formatted_billing_address' => $order->get_formatted_billing_address(),
					'billing_country'           => $order->get_billing_country(),
					'billing_city'              => $order->get_billing_city(),

					'billing_first_name'          => ucwords( $order->get_billing_first_name() ),
					'billing_last_name'           => ucwords( $order->get_billing_last_name() ),
					'formatted_billing_full_name' => ucwords( $order->get_formatted_billing_full_name() ),
					'billing_email'               => $order->get_billing_email(),

					'shop_title' => get_bloginfo(),
					'home_url'   => home_url(),
					'shop_url'   => get_option( 'woocommerce_shop_page_id', '' ) ? get_page_link( get_option( 'woocommerce_shop_page_id' ) ) : '',
				);
				$billing_address                = WC()->countries->get_formatted_address( array(
					'address_1' => $order->get_billing_address_1(),
					'city'      => $order->get_billing_city(),
					'state'     => $order->get_billing_state(),
					'country'   => $order->get_billing_country(),
				), ', ' );
				$shortcodes['billing_address']  = ucwords( $billing_address );
				$shipping_address               = WC()->countries->get_formatted_address( array(
					'address_1' => $order->get_shipping_address_1(),
					'city'      => $order->get_billing_city(),
					'state'     => $order->get_billing_state(),
					'country'   => $order->get_billing_country(),
				), ', ' );
				$shortcodes['shipping_address'] = ucwords( $shipping_address );
				$country                        = new WC_Countries();
				$store_address                  = $country->get_base_address() ? $country->get_base_address() : $country->get_base_address_2();
				$store_address                  = WC()->countries->get_formatted_address( array(
					'address_1' => $store_address,
					'city'      => $country->get_base_city(),
					'state'     => $country->get_base_state(),
					'country'   => $country->get_base_country(),
				), ', ' );
				$shortcodes['store_address']    = ucwords( $store_address );
				$blocks                         = isset( $_POST['block'] ) ? json_decode( stripslashes( $_POST['block'] ) ) : array();
				$text_editor                    = isset( $_POST['text_editor'] ) ? json_decode( stripslashes( $_POST['text_editor'] ), true ) : array();
				$products                       = isset( $_POST['products'] ) ? json_decode( stripslashes( $_POST['products'] ), true ) : array();
				$meta                           = array(
					'order_confirmation'                      => isset( $_POST['order_confirmation'] ) ? wc_clean( $_POST['order_confirmation'] ) : array(),
					'order_details_header'                    => isset( $_POST['order_details_header'] ) ? sanitize_text_field( $_POST['order_details_header'] ) : '',
					'order_details_product_title_text'        => isset( $_POST['order_details_product_title_text'] ) ? sanitize_text_field( $_POST['order_details_product_title_text'] ) : '',
					'order_details_product_value_text'        => isset( $_POST['order_details_product_value_text'] ) ? sanitize_text_field( $_POST['order_details_product_value_text'] ) : '',
					'order_details_product_image'             => isset( $_POST['order_details_product_image'] ) ? sanitize_text_field( $_POST['order_details_product_image'] ) : false,
					'order_details_product_quantity_in_image' => isset( $_POST['order_details_product_quantity_in_image'] ) ? sanitize_text_field( $_POST['order_details_product_quantity_in_image'] ) : true,
					'customer_information'                    => isset( $_POST['customer_information'] ) ? wc_clean( $_POST['customer_information'] ) : array(),
					'thank_you_message_header'                => isset( $_POST['thank_you_message_header'] ) ? sanitize_text_field( $_POST['thank_you_message_header'] ) : '',
					'thank_you_message_message'               => isset( $_POST['thank_you_message_message'] ) ? sanitize_text_field( $_POST['thank_you_message_message'] ) : '',
					'social_icons'                            => isset( $_POST['social_icons'] ) ? array_map( 'stripslashes', $_POST['social_icons'] ) : array(),
				);
				wp_send_json( array(
					'blocks'     => $this->get_content( $blocks, $order, $text_editor, $products, $meta, $shortcodes ),
					'shortcodes' => $shortcodes
				) );
			}
		}
		die;
	}

	public function wp_print_scripts() {
		if ( ! $this->is_customize_preview && ! $this->enable ) {
			return;
		}
		$google_map_api = $this->get_params( 'google_map_api' );
		if ( $google_map_api && $this->include_google_api === null ) {
			$this->include_google_api = 1;
			if ( $this->is_customize_preview ) {
				?>
                <script async defer
                        src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_map_api ?>">
                </script>
				<?php
			} else if ( in_array( 'google_map', $this->active_components ) ) {
				?>
                <script async defer
                        src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_map_api ?>">
                </script>
				<?php
			}

		}
		$bing_map_api = $this->get_params( 'bing_map_api' );
		if ( $bing_map_api && $this->include_bing_api === null ) {
			$this->include_bing_api = 1;
			if ( $this->is_customize_preview ) {
				?>
                <script type='text/javascript'
                        src='https://www.bing.com/api/maps/mapcontrol?key=<?php echo $bing_map_api ?>' async
                        defer></script>
				<?php
			} else if ( in_array( 'bing_map', $this->active_components ) ) {
				?>
                <script type='text/javascript'
                        src='https://www.bing.com/api/maps/mapcontrol?key=<?php echo $bing_map_api ?>' async
                        defer></script>

				<?php
			}
		}
	}

	public function get_active_components( $value, $key ) {
		if ( ! in_array( $value, $this->active_components ) ) {
			$this->active_components[] = $value;
		}
	}

	public function enqueue_scripts() {

		global $post, $wp;

		if ( is_checkout() && ! empty( $wp->query_vars['order-received'] ) && isset( $_GET['key'] ) ) {
			$this->order_id = absint( $wp->query_vars['order-received'] );
			$this->key      = wc_clean( $_GET['key'] );
		} else {
			return;
		}
		$blocks = json_decode( $this->get_params( 'blocks' ), true );
		array_walk_recursive( $blocks, array( $this, 'get_active_components' ) );
		$products = json_decode( $this->get_params( 'products' ), true );
		if ( is_array( $products ) && count( $products ) ) {
			wp_enqueue_style( 'woocommerce-thank-you-page-nav-icons', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'wtypc_nav_icons.css', array() );
			wp_enqueue_style( 'woocommerce-thank-you-page-jquery-vi_flexslider', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'vi_flexslider.min.css', array() );
			if ( ! wp_script_is( 'jquery-vi_flexslider' ) ) {
				wp_enqueue_script( 'jquery-vi_flexslider', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'jquery.vi_flexslider.js', array( 'jquery' ), '', true );
			}
			foreach ( $products as $products_data ) {
				if ( is_array( $products_data ) ) {
					if ( isset( $products_data['product_options'] ) && ! in_array( $products_data['product_options'], $this->active_product_options ) ) {
						$this->active_product_options[] = $products_data['product_options'];
					}
				}
			}
		}

		$order = function_exists( 'wc_get_order' ) ? wc_get_order( $this->order_id ) : new WC_Order( $this->order_id );;
		if ( $order ) {
			$this->shortcodes['order_number']   = $this->order_id;
			$this->shortcodes['order_status']   = $order->get_status();
			$this->shortcodes['order_date']     = $order->get_date_created() ? $order->get_date_created()->date_i18n( 'F d, Y' ) : '';
			$this->shortcodes['order_total']    = $order->get_formatted_order_total();
			$this->shortcodes['order_subtotal'] = $order->get_subtotal_to_display();
			$this->shortcodes['items_count']    = $order->get_item_count();
			$this->shortcodes['payment_method'] = $order->get_payment_method_title();

			$this->shortcodes['shipping_method']            = $order->get_shipping_method();
			$this->shortcodes['shipping_address']           = $order->get_shipping_address_1();
			$this->shortcodes['formatted_shipping_address'] = $order->get_formatted_shipping_address();

			$this->shortcodes['billing_address']           = $order->get_billing_address_1();
			$this->shortcodes['formatted_billing_address'] = $order->get_formatted_billing_address();
			$this->shortcodes['billing_country']           = $order->get_billing_country();
			$this->shortcodes['billing_city']              = $order->get_billing_city();

			$this->shortcodes['billing_first_name']          = ucwords( $order->get_billing_first_name() );
			$this->shortcodes['billing_last_name']           = ucwords( $order->get_billing_last_name() );
			$this->shortcodes['formatted_billing_full_name'] = ucwords( $order->get_formatted_billing_full_name() );
			$this->shortcodes['billing_email']               = $order->get_billing_email();

			$this->shortcodes['shop_title'] = get_bloginfo();
			$this->shortcodes['home_url']   = home_url();
			$this->shortcodes['shop_url']   = get_option( 'woocommerce_shop_page_id', '' ) ? get_page_link( get_option( 'woocommerce_shop_page_id' ) ) : '';
		}
		$this->is_rtl = is_rtl();
		if ( is_customize_preview() && ! empty( $_REQUEST['customize_messenger_channel'] ) ) {
			$this->is_customize_preview = true;
			global $wp_customize;
			$this->customize_preview_data = $wp_customize;
			$this->language = array();
			if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
				/*wpml*/
				global $sitepress;
				$default_language = $sitepress->get_default_language();
				$languages              = icl_get_languages( 'skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str' );
				if ( count( $languages ) ) {
					foreach ( $languages as $key => $language ) {
						if ( $key != $default_language ) {
							$this->language[] = $key;
						}
					}
				}
			} elseif ( class_exists( 'Polylang' ) ) {
				/*Polylang*/
				$languages              = pll_languages_list();
				$default_language = pll_default_language( 'slug' );
				foreach ( $languages as $language ) {
					if ( $language == $default_language ) {
						continue;
					}
					$this->language[] = $language;
				}
			}
			wp_enqueue_style( 'woocommerce-thank-you-page-style', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'woocommerce-thank-you-page.css', array(), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION );
			wp_enqueue_media();
			wp_enqueue_style( 'woocommerce-thank-you-page-social-icons', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'social_icons.css', array(), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION );
			wp_enqueue_style( 'woocommerce-thank-you-page-icons', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'woocommerce-thank-you-page-icons.css', array(), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION );
			$google_map_address = $this->get_params( 'google_map_address' );
			$bing_map_address   = $this->get_params( 'bing_map_address' );
			if ( $order ) {
				$billing_address = $order->get_billing_address_1();
				if ( $order->get_billing_city() ) {
					$billing_address .= ', ' . $order->get_billing_city();
				}
				if ( $order->get_billing_state() ) {
					$billing_address .= ', ' . $order->get_billing_state();
				}
				if ( $order->get_billing_country() ) {
					$billing_address .= ', ' . $order->get_billing_country();
				}
				$shipping_address = $order->get_shipping_address_1();
				if ( $order->get_shipping_city() ) {
					$shipping_address .= ', ' . $order->get_shipping_city();
				}
				if ( $order->get_shipping_state() ) {
					$shipping_address .= ', ' . $order->get_shipping_state();
				}
				if ( $order->get_shipping_country() ) {
					$shipping_address .= ', ' . $order->get_shipping_country();
				}
				$country       = new WC_Countries();
				$store_address = $country->get_base_address() ? $country->get_base_address() : $country->get_base_address_2();
				if ( $country->get_base_city() ) {
					$store_address .= ', ' . $country->get_base_city();
				}
				if ( $country->get_base_state() ) {
					$store_address .= ', ' . $country->get_base_state();
				}
				if ( $country->get_base_country() ) {
					$store_address .= ', ' . $country->get_base_country();
				}
				$google_map_address       = str_replace( '{billing_address}', $billing_address, $google_map_address );
				$google_map_address       = str_replace( '{shipping_address}', $shipping_address, $google_map_address );
				$google_map_address       = str_replace( '{store_address}', $store_address, $google_map_address );
				$this->google_map_address = $google_map_address;
				$bing_map_address         = str_replace( '{billing_address}', $billing_address, $bing_map_address );
				$bing_map_address         = str_replace( '{shipping_address}', $shipping_address, $bing_map_address );
				$bing_map_address         = str_replace( '{store_address}', $store_address, $bing_map_address );
				$this->bing_map_address   = $bing_map_address;
			}
		} elseif ( $this->get_params( 'enable' ) ) {
			if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
				$default_lang     = apply_filters( 'wpml_default_language', null );
				$current_language = apply_filters( 'wpml_current_language', null );
				if ( $current_language && $current_language !== $default_lang ) {
					$this->language = $current_language;
				}
			} else if ( class_exists( 'Polylang' ) ) {
				$default_lang     = pll_default_language( 'slug' );
				$current_language = pll_current_language( 'slug' );
				if ( $current_language && $current_language !== $default_lang ) {
					$this->language = $current_language;
				}
			}
			$order_status = $this->get_params( 'order_status' );
			if ( $order && is_array( $order_status ) && count( $order_status ) && in_array( 'wc-' . $order->get_status(), $order_status ) ) {
				$this->enable = true;
				if ( $bing_map_api = $this->get_params( 'bing_map_api' ) ) {
					if ( is_array( $this->active_components ) && in_array( 'bing_map', $this->active_components ) ) {
						$bing_map_view    = $this->get_params( 'bing_map_view' );
						$bing_map_address = $this->get_params( 'bing_map_address' );
						$billing_address  = WC()->countries->get_formatted_address( array(
							'address_1' => $order->get_billing_address_1(),
							'city'      => $order->get_billing_city(),
							'state'     => $order->get_billing_state(),
							'country'   => WC()->countries->countries[ $order->get_billing_country() ],
						), ', ' );
						$billing_address  = ucwords( $billing_address );
						$shipping_address = WC()->countries->get_formatted_address( array(
							'address_1' => $order->get_shipping_address_1(),
							'city'      => $order->get_billing_city(),
							'state'     => $order->get_billing_state(),
							'country'   => WC()->countries->countries[ $order->get_billing_country() ],
						), ', ' );
						$shipping_address = ucwords( $shipping_address );

						$country          = new WC_Countries();
						$store_address    = $country->get_base_address() ? $country->get_base_address() : $country->get_base_address_2();
						$store_address    = WC()->countries->get_formatted_address( array(
							'address_1' => $store_address,
							'city'      => $country->get_base_city(),
							'state'     => $country->get_base_state(),
							'country'   => WC()->countries->countries[ $country->get_base_country() ],
						), ', ' );
						$store_address    = ucwords( $store_address );
						$bing_map_address = str_replace( '{billing_address}', $billing_address, $bing_map_address );
						$bing_map_address = str_replace( '{billing_address}', $billing_address, $bing_map_address );
						$bing_map_address = str_replace( '{shipping_address}', $shipping_address, $bing_map_address );
						$bing_map_address = str_replace( '{store_address}', $store_address, $bing_map_address );
						wp_enqueue_script( 'woocommerce-thank-you-page-bing-map-script', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'woocommerce-thank-you-page-bing-map.js', array( 'jquery' ), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION, true );
						wp_localize_script( 'woocommerce-thank-you-page-bing-map-script', 'wtyp_front_end_bing_map_params', array(
							'bing_map_zoom_level' => $this->get_params( 'bing_map_zoom_level' ),
							'bing_map_navbarmode' => $this->get_params( 'bing_map_navbarmode' ),
							'bing_map_api'        => $bing_map_api,
							'bing_map_label'      => str_replace( array(
								'{address}',
								'{store_address}',
								'{shipping_address}',
								'{billing_address}'
							), array(
								$bing_map_address,
								$store_address,
								$shipping_address,
								$billing_address
							), nl2br( $this->get_params( 'bing_map_label' ) ) ),
							'bing_map_address'    => $bing_map_address,
							'bing_map_view'       => $bing_map_view,
//                            'map_styles'            => $map_styles,
							'bing_map_marker'     => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . $this->get_params( 'bing_map_marker' ) . '.png'
						) );
						$this->bing_map_address = $bing_map_address;
					}

				}
				if ( $this->get_params( 'google_map_api' ) ) {
					if ( is_array( $this->active_components ) && in_array( 'google_map', $this->active_components ) ) {
						$google_map_styles = array(
							'ultra-light-with-labels'  => '[
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#e9e9e9"
            },
            {
                "lightness": 17
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#f5f5f5"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 17
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 29
            },
            {
                "weight": 0.2
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 18
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#f5f5f5"
            },
            {
                "lightness": 21
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#dedede"
            },
            {
                "lightness": 21
            }
        ]
    },
    {
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#ffffff"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "saturation": 36
            },
            {
                "color": "#333333"
            },
            {
                "lightness": 40
            }
        ]
    },
    {
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#f2f2f2"
            },
            {
                "lightness": 19
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#fefefe"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#fefefe"
            },
            {
                "lightness": 17
            },
            {
                "weight": 1.2
            }
        ]
    }
]',
							'subtle-grayscale'         => '[
    {
        "featureType": "administrative",
        "elementType": "all",
        "stylers": [
            {
                "saturation": "-100"
            }
        ]
    },
    {
        "featureType": "administrative.province",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [
            {
                "saturation": -100
            },
            {
                "lightness": 65
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "all",
        "stylers": [
            {
                "saturation": -100
            },
            {
                "lightness": "50"
            },
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "all",
        "stylers": [
            {
                "saturation": "-100"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "all",
        "stylers": [
            {
                "lightness": "30"
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "all",
        "stylers": [
            {
                "lightness": "40"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "all",
        "stylers": [
            {
                "saturation": -100
            },
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
            {
                "hue": "#ffff00"
            },
            {
                "lightness": -25
            },
            {
                "saturation": -97
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "labels",
        "stylers": [
            {
                "lightness": -25
            },
            {
                "saturation": -100
            }
        ]
    }
]',
							'shades-of-grey'           => '[
    {
        "featureType": "all",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "saturation": 36
            },
            {
                "color": "#000000"
            },
            {
                "lightness": 40
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#000000"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 17
            },
            {
                "weight": 1.2
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 21
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 17
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 29
            },
            {
                "weight": 0.2
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 18
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 19
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 17
            }
        ]
    }
]',
							'blue-water'               => '[
    {
        "featureType": "administrative",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#444444"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [
            {
                "color": "#f2f2f2"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "all",
        "stylers": [
            {
                "saturation": -100
            },
            {
                "lightness": 45
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {
                "color": "#46bcec"
            },
            {
                "visibility": "on"
            }
        ]
    }
]',
							'wy'                       => '[
    {
        "featureType": "all",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "weight": "2.00"
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#9c9c9c"
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.text",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [
            {
                "color": "#f2f2f2"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "landscape.man_made",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "all",
        "stylers": [
            {
                "saturation": -100
            },
            {
                "lightness": 45
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#eeeeee"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#7b7b7b"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {
                "color": "#46bcec"
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#c8d7d4"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#070707"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    }
]',
							'vintage-old-golden-brown' => '[
    {
        "featureType": "all",
        "elementType": "all",
        "stylers": [
            {
                "color": "#ff7000"
            },
            {
                "lightness": "69"
            },
            {
                "saturation": "100"
            },
            {
                "weight": "1.17"
            },
            {
                "gamma": "2.04"
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#cb8536"
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels",
        "stylers": [
            {
                "color": "#ffb471"
            },
            {
                "lightness": "66"
            },
            {
                "saturation": "100"
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "gamma": 0.01
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "saturation": -31
            },
            {
                "lightness": -33
            },
            {
                "weight": 2
            },
            {
                "gamma": 0.8
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [
            {
                "lightness": "-8"
            },
            {
                "gamma": "0.98"
            },
            {
                "weight": "2.45"
            },
            {
                "saturation": "26"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [
            {
                "lightness": 30
            },
            {
                "saturation": 30
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
            {
                "saturation": 20
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "geometry",
        "stylers": [
            {
                "lightness": 20
            },
            {
                "saturation": -20
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry",
        "stylers": [
            {
                "lightness": 10
            },
            {
                "saturation": -30
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "saturation": 25
            },
            {
                "lightness": 25
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {
                "lightness": -20
            },
            {
                "color": "#ecc080"
            }
        ]
    }
]',
							'black-and-white'          => '[
    {
        "featureType": "road",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "poi",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "administrative",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "weight": 1
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "weight": 0.8
            }
        ]
    },
    {
        "featureType": "landscape",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "water",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "transit",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "elementType": "labels.text",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#000000"
            }
        ]
    },
    {
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    }
]',
							'light-dream'              => '[
    {
        "featureType": "landscape",
        "stylers": [
            {
                "hue": "#FFBB00"
            },
            {
                "saturation": 43.400000000000006
            },
            {
                "lightness": 37.599999999999994
            },
            {
                "gamma": 1
            }
        ]
    },
    {
        "featureType": "road.highway",
        "stylers": [
            {
                "hue": "#FFC200"
            },
            {
                "saturation": -61.8
            },
            {
                "lightness": 45.599999999999994
            },
            {
                "gamma": 1
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "stylers": [
            {
                "hue": "#FF0300"
            },
            {
                "saturation": -100
            },
            {
                "lightness": 51.19999999999999
            },
            {
                "gamma": 1
            }
        ]
    },
    {
        "featureType": "road.local",
        "stylers": [
            {
                "hue": "#FF0300"
            },
            {
                "saturation": -100
            },
            {
                "lightness": 52
            },
            {
                "gamma": 1
            }
        ]
    },
    {
        "featureType": "water",
        "stylers": [
            {
                "hue": "#0078FF"
            },
            {
                "saturation": -13.200000000000003
            },
            {
                "lightness": 2.4000000000000057
            },
            {
                "gamma": 1
            }
        ]
    },
    {
        "featureType": "poi",
        "stylers": [
            {
                "hue": "#00FF6A"
            },
            {
                "saturation": -1.0989010989011234
            },
            {
                "lightness": 11.200000000000017
            },
            {
                "gamma": 1
            }
        ]
    }
]',
							'blue-essence'             => '[
    {
        "featureType": "landscape.natural",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#e0efef"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "hue": "#1900ff"
            },
            {
                "color": "#c0e8e8"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry",
        "stylers": [
            {
                "lightness": 100
            },
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "transit.line",
        "elementType": "geometry",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "lightness": 700
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {
                "color": "#7dcdcd"
            }
        ]
    }
]',
							'pale-dawn'                => '[
    {
        "featureType": "administrative",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "lightness": 33
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [
            {
                "color": "#f2e5d4"
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#c5dac6"
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "all",
        "stylers": [
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#c5c6c6"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#e4d7c6"
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#fbfaf7"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#acbcc9"
            }
        ]
    }
]',
							'unsaturated-browns'       => '[
    {
        "elementType": "geometry",
        "stylers": [
            {
                "hue": "#ff4400"
            },
            {
                "saturation": -68
            },
            {
                "lightness": -4
            },
            {
                "gamma": 0.72
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.icon"
    },
    {
        "featureType": "landscape.man_made",
        "elementType": "geometry",
        "stylers": [
            {
                "hue": "#0077ff"
            },
            {
                "gamma": 3.1
            }
        ]
    },
    {
        "featureType": "water",
        "stylers": [
            {
                "hue": "#00ccff"
            },
            {
                "gamma": 0.44
            },
            {
                "saturation": -33
            }
        ]
    },
    {
        "featureType": "poi.park",
        "stylers": [
            {
                "hue": "#44ff00"
            },
            {
                "saturation": -23
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "hue": "#007fff"
            },
            {
                "gamma": 0.77
            },
            {
                "saturation": 65
            },
            {
                "lightness": 99
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "gamma": 0.11
            },
            {
                "weight": 5.6
            },
            {
                "saturation": 99
            },
            {
                "hue": "#0091ff"
            },
            {
                "lightness": -86
            }
        ]
    },
    {
        "featureType": "transit.line",
        "elementType": "geometry",
        "stylers": [
            {
                "lightness": -48
            },
            {
                "hue": "#ff5e00"
            },
            {
                "gamma": 1.2
            },
            {
                "saturation": -23
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "saturation": -64
            },
            {
                "hue": "#ff9100"
            },
            {
                "lightness": 16
            },
            {
                "gamma": 0.47
            },
            {
                "weight": 2.7
            }
        ]
    }
]',
							'midnight-commander'       => '[
    {
        "featureType": "all",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 13
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#000000"
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#144b53"
            },
            {
                "lightness": 14
            },
            {
                "weight": 1.4
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [
            {
                "color": "#08304b"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#0c4152"
            },
            {
                "lightness": 5
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#000000"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#0b434f"
            },
            {
                "lightness": 25
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#000000"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#0b3d51"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "all",
        "stylers": [
            {
                "color": "#146474"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {
                "color": "#021019"
            }
        ]
    }
]',
							'light-monochrome'         => '[
    {
        "featureType": "administrative.locality",
        "elementType": "all",
        "stylers": [
            {
                "hue": "#2c2e33"
            },
            {
                "saturation": 7
            },
            {
                "lightness": 19
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [
            {
                "hue": "#ffffff"
            },
            {
                "saturation": -100
            },
            {
                "lightness": 100
            },
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "all",
        "stylers": [
            {
                "hue": "#ffffff"
            },
            {
                "saturation": -100
            },
            {
                "lightness": 100
            },
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry",
        "stylers": [
            {
                "hue": "#bbc0c4"
            },
            {
                "saturation": -93
            },
            {
                "lightness": 31
            },
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels",
        "stylers": [
            {
                "hue": "#bbc0c4"
            },
            {
                "saturation": -93
            },
            {
                "lightness": 31
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "labels",
        "stylers": [
            {
                "hue": "#bbc0c4"
            },
            {
                "saturation": -93
            },
            {
                "lightness": -2
            },
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [
            {
                "hue": "#e9ebed"
            },
            {
                "saturation": -90
            },
            {
                "lightness": -8
            },
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "all",
        "stylers": [
            {
                "hue": "#e9ebed"
            },
            {
                "saturation": 10
            },
            {
                "lightness": 69
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {
                "hue": "#e9ebed"
            },
            {
                "saturation": -78
            },
            {
                "lightness": 67
            },
            {
                "visibility": "simplified"
            }
        ]
    }
]',
							'light-gray'               => '[
    {
        "featureType": "water",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#d3d3d3"
            }
        ]
    },
    {
        "featureType": "transit",
        "stylers": [
            {
                "color": "#808080"
            },
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#b3b3b3"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#ffffff"
            },
            {
                "weight": 1.8
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#d7d7d7"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#ebebeb"
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#a7a7a7"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#efefef"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#696969"
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#737373"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#d6d6d6"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {},
    {
        "featureType": "poi",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#dadada"
            }
        ]
    }
]'
						);
						$google_map_style  = $this->get_params( 'google_map_style' );
						$map_styles        = '';
						if ( $google_map_style == 'custom' ) {
							$map_styles = $this->get_params( 'google_map_custom_style' );
						} elseif ( isset( $google_map_styles[ $google_map_style ] ) ) {
							$map_styles = $google_map_styles[ $google_map_style ];
						}
						$google_map_address = $this->get_params( 'google_map_address' );
						$billing_address    = WC()->countries->get_formatted_address( array(
							'address_1' => $order->get_billing_address_1(),
							'city'      => $order->get_billing_city(),
							'state'     => $order->get_billing_state(),
							'country'   => $order->get_billing_country(),
						), ', ' );
						$billing_address    = ucwords( $billing_address );
						$shipping_address   = WC()->countries->get_formatted_address( array(
							'address_1' => $order->get_shipping_address_1(),
							'city'      => $order->get_billing_city(),
							'state'     => $order->get_billing_state(),
							'country'   => $order->get_billing_country(),
						), ', ' );
						$shipping_address   = ucwords( $shipping_address );
						$country            = new WC_Countries();
						$store_address      = $country->get_base_address() ? $country->get_base_address() : $country->get_base_address_2();
						$store_address      = WC()->countries->get_formatted_address( array(
							'address_1' => $store_address,
							'city'      => $country->get_base_city(),
							'state'     => $country->get_base_state(),
							'country'   => $country->get_base_country(),
						), ', ' );
						$store_address      = ucwords( $store_address );
						$google_map_address = str_replace( '{billing_address}', $billing_address, $google_map_address );
						$google_map_address = str_replace( '{billing_address}', $billing_address, $google_map_address );
						$google_map_address = str_replace( '{shipping_address}', $shipping_address, $google_map_address );
						$google_map_address = str_replace( '{store_address}', $store_address, $google_map_address );
						wp_enqueue_script( 'woocommerce-thank-you-page-google-map-script', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'woocommerce-thank-you-page-google-map.js', array( 'jquery' ), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION, true );
						wp_localize_script( 'woocommerce-thank-you-page-google-map-script', 'woo_thank_you_page_front_end_params', array(
							'google_map_zoom_level' => $this->get_params( 'google_map_zoom_level' ),
							'google_map_label'      => str_replace( array(
								'{address}',
								'{store_address}',
								'{shipping_address}',
								'{billing_address}'
							), array(
								$google_map_address,
								$store_address,
								$shipping_address,
								$billing_address
							), nl2br( $this->get_params( 'google_map_label' ) ) ),
							'google_map_address'    => $google_map_address,
							'google_map_style'      => $google_map_style,
							'map_styles'            => $map_styles,
							'google_map_marker'     => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . $this->get_params( 'google_map_marker' ) . '.png'
						) );
						$this->google_map_address = $google_map_address;
					}

				}
				/*custom css*/
				$css = $this->get_params( 'custom_css' );
				if ( is_array( $this->active_components ) ) {
					if ( in_array( 'order_confirmation', $this->active_components ) ) {
						/*order confirmation*/
						$css        .= $this->add_inline_style( array(
							'order_confirmation_bg',
							'order_confirmation_padding',
							'order_confirmation_border_radius',
							'order_confirmation_border_width',
							'order_confirmation_border_style',
							'order_confirmation_border_color'
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', array(
							'background-color',
							'padding',
							'border-radius',
							'border-width',
							'border-style',
							'border-color'
						), array(
							'',
							'px',
							'px',
							'px',
							'',
							''
						) );
						$border_rtl = $this->get_data_style_rtl( 'right' );
						$css        .= $this->add_inline_style( array(
							'order_confirmation_vertical_width',
							'order_confirmation_vertical_style',
							'order_confirmation_vertical_color',
							'order_confirmation_title_color',
							'order_confirmation_title_bg_color',
							'order_confirmation_title_font_size',
							'order_confirmation_title_text_align',
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', array(
							'border-' . $border_rtl . '-width',
							'border-' . $border_rtl . '-style',
							'border-' . $border_rtl . '-color',
							'color',
							'background-color',
							'font-size',
							'text-align',
						), array(
							'px',
							'',
							'',
							'',
							'',
							'px',
							'',
						) );
						$css        .= $this->add_inline_style( array(
							'order_confirmation_horizontal_width',
							'order_confirmation_horizontal_style',
							'order_confirmation_horizontal_color',
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:last-child) .woocommerce-thank-you-page-order_confirmation-title div,.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:last-child) .woocommerce-thank-you-page-order_confirmation-value div', array(
							'border-bottom-width',
							'border-bottom-style',
							'border-bottom-color',
						), array(
							'px',
							'',
							'',
						) );
						$css        .= $this->add_inline_style( array(
							'order_confirmation_header_color',
							'order_confirmation_header_bg_color',
							'order_confirmation_header_font_size',
							'order_confirmation_header_text_align',
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', array(
							'color',
							'background-color',
							'font-size',
							'text-align',
						), array(
							'',
							'',
							'px',
							'',
						) );

						$css .= $this->add_inline_style( array(
							'order_confirmation_value_color',
							'order_confirmation_value_bg_color',
							'order_confirmation_value_font_size',
							'order_confirmation_value_text_align',
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', array(
							'color',
							'background-color',
							'font-size',
							'text-align',
						), array(
							'',
							'',
							'px',
							'',
						) );
					}

					if ( in_array( 'order_details', $this->active_components ) ) {
						/*order details*/

						$css .= $this->add_inline_style( array(
							'order_details_color',
							'order_details_bg',
							'order_details_padding',
							'order_details_border_radius',
							'order_details_border_width',
							'order_details_border_style',
							'order_details_border_color'
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', array(
							'color',
							'background-color',
							'padding',
							'border-radius',
							'border-width',
							'border-style',
							'border-color'
						), array(
							'',
							'',
							'px',
							'px',
							'px',
							'',
							''
						) );
						$css .= $this->add_inline_style( array(
							'order_details_horizontal_width',
							'order_details_horizontal_style',
							'order_details_horizontal_color',
						), '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total .woocommerce-thank-you-page-order_details__detail:last-child,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_items', array(
							'border-top-width',
							'border-top-style',
							'border-top-color',
						), array(
							'px',
							'',
							'',
						) );

						$css .= $this->add_inline_style( array(
							'order_details_header_color',
							'order_details_header_bg_color',
							'order_details_header_font_size',
							'order_details_header_text_align',
						), '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', array(
							'color',
							'background-color',
							'font-size',
							'text-align',
						), array(
							'',
							'',
							'px',
							'',
						) );
						$css .= $this->add_inline_style( array(
							'order_details_product_image_width',
						), '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-title a.woocommerce-thank-you-page-order-item-image-wrap', array(
							'width',
						), array(
							'px',
						) );
					}
					if ( in_array( 'customer_information', $this->active_components ) ) {
						/*customer information*/
						$css        .= $this->add_inline_style( array(
							'customer_information_color',
							'customer_information_bg',
							'customer_information_padding',
							'customer_information_border_radius',
							'customer_information_border_width',
							'customer_information_border_style',
							'customer_information_border_color'
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', array(
							'color',
							'background-color',
							'padding',
							'border-radius',
							'border-width',
							'border-style',
							'border-color'
						), array(
							'',
							'',
							'px',
							'px',
							'px',
							'',
							''
						) );
						$border_rtl = $this->get_data_style_rtl( 'left' );
						$css        .= $this->add_inline_style( array(
							'customer_information_vertical_width',
							'customer_information_vertical_style',
							'customer_information_vertical_color',
						), '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address .woocommerce-thank-you-page-customer_information__shipping_address', array(
							'border-' . $border_rtl . '-width',
							'border-' . $border_rtl . '-style',
							'border-' . $border_rtl . '-color',
						), array(
							'px',
							'',
							'',
						) );
						$css        .= $this->add_inline_style( array(
							'customer_information_header_color',
							'customer_information_header_bg_color',
							'customer_information_header_font_size',
							'customer_information_header_text_align',
						), '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', array(
							'color',
							'background-color',
							'font-size',
							'text-align',
						), array(
							'',
							'',
							'px',
							'',
						) );
						$css        .= $this->add_inline_style( array(
							'customer_information_address_color',
							'customer_information_address_bg_color',
							'customer_information_address_font_size',
							'customer_information_address_text_align',
						), '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', array(
							'color',
							'background-color',
							'font-size',
							'text-align',
						), array(
							'',
							'',
							'px',
							'',
						) );
					}
					if ( in_array( 'social_icons', $this->active_components ) ) {
						/*social icons*/
						$css .= $this->add_inline_style( array(
							'social_icons_header_color',
							'social_icons_header_font_size',
						), '.woocommerce-thank-you-page-social_icons__container .woocommerce-thank-you-page-social_icons__header', array(
							'color',
							'font-size',
						), array(
							'',
							'px',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_align',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials', array(
							'text-align',
						), array(
							'',
						) );

						$css .= $this->add_inline_style( array(
							'social_icons_space',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials li:not(:last-child)', array(
							'margin-right',
						), array(
							'px',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_size',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials li .wtyp-social-button span', array(
							'font-size',
						), array(
							'px',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_facebook_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-facebook-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_twitter_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-twitter-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_pinterest_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-pinterest-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_instagram_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-instagram-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_dribbble_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-dribbble-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_tumblr_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-tumblr-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_google_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-google-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_vkontakte_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-vkontakte-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_linkedin_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-linkedin-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_youtube_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-youtube-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
					}
					if ( in_array( 'thank_you_message', $this->active_components ) ) {
						/*thank you message*/
						$css .= $this->add_inline_style( array(
							'thank_you_message_color',
						), '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail', array(
							'color',
						), array(
							'',
						) );

						$css .= $this->add_inline_style( array(
							'thank_you_message_padding',
							'thank_you_message_text_align',
						), '.woocommerce-thank-you-page-thank_you_message__container', array(
							'padding',
							'text-align',
						), array(
							'px',
							'',
						) );
						$css .= $this->add_inline_style( array(
							'thank_you_message_header_font_size',
						), '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail .woocommerce-thank-you-page-thank_you_message-header', array(
							'font-size',
						), array(
							'px',
						) );
						$css .= $this->add_inline_style( array(
							'thank_you_message_message_font_size',
						), '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail .woocommerce-thank-you-page-thank_you_message-message', array(
							'font-size',
						), array(
							'px',
						) );
					}
					if ( in_array( 'coupon', $this->active_components ) ) {
						/*coupon*/
						$css .= $this->add_inline_style( array(
							'coupon_padding',
							'coupon_text_align',
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container', array(
							'padding',
							'text-align',
						), array(
							'px',
							'',
						) );
						$css .= $this->add_inline_style( array(
							'coupon_message_color',
							'coupon_message_font_size',
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__message', array(
							'color',
							'font-size',
						), array(
							'',
							'px',
						) );
						$css .= $this->add_inline_style( array(
							'coupon_code_color',
							'coupon_code_bg_color',
							'coupon_code_border_width',
							'coupon_code_border_style',
							'coupon_code_border_color',
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', array(
							'color',
							'background-color',
							'border-width',
							'border-style',
							'border-color',
						), array(
							'',
							'',
							'px',
							'',
							'',
						) );
						$css .= '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-wrap:before{color:' . $this->get_params( 'coupon_scissors_color' ) . ';}';
					}
					if ( in_array( 'google_map', $this->active_components ) ) {
						/*google map*/
						if ( $this->get_params( 'google_map_width' ) ) {
							$css .= $this->add_inline_style( array(
								'google_map_height',
								'google_map_width',
							), '#woocommerce-thank-you-page-google-map', array(
								'height',
								'width',
							), array(
								'px',
								'px',
							) );
						} else {
							$css .= '#woocommerce-thank-you-page-google-map{width:100%;height:' . $this->get_params( 'google_map_height' ) . 'px;}';
						}
					}
					if ( in_array( 'bing_map', $this->active_components ) ) {
						/*google map*/
						if ( $this->get_params( 'bing_map_width' ) ) {
							$css .= $this->add_inline_style( array(
								'bing_map_height',
								'bing_map_width',
							), '#woocommerce-thank-you-page-bing-map', array(
								'height',
								'width',
							), array(
								'px',
								'px',
							) );
						} else {
							$css .= '#woocommerce-thank-you-page-bing-map{width:100%;height:' . $this->get_params( 'bing_map_height' ) . 'px;}';
						}
					}
				}
				wp_enqueue_style( 'woocommerce-thank-you-page-style', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'woocommerce-thank-you-page.css', array(), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION );
				wp_add_inline_style( 'woocommerce-thank-you-page-style', $css );
				wp_enqueue_style( 'woocommerce-thank-you-page-social-icons', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'social_icons.css', array(), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION );
				wp_enqueue_style( 'woocommerce-thank-you-page-icons', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'woocommerce-thank-you-page-icons.css', array(), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION );
				wp_enqueue_script( 'woocommerce-thank-you-page-script', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'woocommerce-thank-you-page.js', array( 'jquery' ), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION, true );
				wp_localize_script( 'woocommerce-thank-you-page-script', 'woocommerce_thank_you_page_customizer_params', array(
					'url'            => admin_url( 'admin-ajax.php' ),
					'action'         => 'woocommerce_thank_you_page_customizer_send_email',
					'shortcodes'     => $this->shortcodes,
					'language_ajax'  => $this->language,
					'copied_message' => __( 'Coupon code is copied to clipboard.', 'woocommerce-thank-you-page-customizer' ),
				) );

			}
		}
	}

	private function get_data_style_rtl( $value ) {
		if ( ! $this->is_rtl ) {
			return $value;
		}
		if ( $value === 'left' ) {
			$result = 'right';
		} elseif ( $value === 'right' ) {
			$result = 'left';
		}

		return $result ?? $value;
	}

	private function add_inline_style( $name, $element, $style, $suffix = '', $echo = false ) {
		$return = $element . '{';
		if ( is_array( $name ) && count( $name ) ) {
			foreach ( $name as $key => $value ) {
				$return .= $style[ $key ] . ':' . $this->get_params( $name[ $key ] ) . $suffix[ $key ] . ';';
			}
		}
		$return .= '}';
		if ( $echo ) {
			echo $return;
		}

		return $return;
	}

	public function payment_method_html_hold() {
		?>
        <div id="<?php echo $this->set( 'payment-method-html-hold' ) ?>" style="display: none">
			<?php echo $this->payment_method_html ?>
        </div>
		<?php
	}

	public function wp_footer() {
		if ( ! $this->is_customize_preview ) {
			return;
		}
		$shortcode_titles = array(
			'coupon_code'         => __( 'Coupon code', 'woocommerce-thank-you-page-customizer' ),
			'coupon_code_style_1' => __( 'Coupon code style 1', 'woocommerce-thank-you-page-customizer' ),
			'coupon_date_expires' => __( 'Coupon\'s date expires', 'woocommerce-thank-you-page-customizer' ),
			'last_valid_date'     => __( 'Coupon\'s last valid date', 'woocommerce-thank-you-page-customizer' ),
			'coupon_amount'       => __( 'Coupon amount', 'woocommerce-thank-you-page-customizer' ),
			'shop_title'          => __( 'Shop title', 'woocommerce-thank-you-page-customizer' ),
			'home_url'            => __( 'Home url', 'woocommerce-thank-you-page-customizer' ),
			'shop_url'            => __( 'Shop url', 'woocommerce-thank-you-page-customizer' ),
			'order_number'        => __( 'Order number', 'woocommerce-thank-you-page-customizer' ),
			'order_status'        => __( 'Order status', 'woocommerce-thank-you-page-customizer' ),
			'order_date'          => __( 'Order date', 'woocommerce-thank-you-page-customizer' ),
			'order_total'         => __( 'Order total', 'woocommerce-thank-you-page-customizer' ),
			'order_subtotal'      => __( 'Order subtotal', 'woocommerce-thank-you-page-customizer' ),
			'items_count'         => __( 'Items count', 'woocommerce-thank-you-page-customizer' ),
			'payment_method'      => __( 'Payment method', 'woocommerce-thank-you-page-customizer' ),

			'shipping_method'            => __( 'Shipping method', 'woocommerce-thank-you-page-customizer' ),
			'shipping_address'           => __( 'Shipping address', 'woocommerce-thank-you-page-customizer' ),
			'formatted_shipping_address' => __( 'Formatted shipping address', 'woocommerce-thank-you-page-customizer' ),

			'billing_address'           => __( 'Billing address', 'woocommerce-thank-you-page-customizer' ),
			'formatted_billing_address' => __( 'Formatted billing address', 'woocommerce-thank-you-page-customizer' ),
			'billing_country'           => __( 'Billing country', 'woocommerce-thank-you-page-customizer' ),
			'billing_city'              => __( 'Billing city', 'woocommerce-thank-you-page-customizer' ),

			'billing_first_name'          => __( 'Billing first name', 'woocommerce-thank-you-page-customizer' ),
			'billing_last_name'           => __( 'Billing last name', 'woocommerce-thank-you-page-customizer' ),
			'formatted_billing_full_name' => __( 'Formatted billing full name', 'woocommerce-thank-you-page-customizer' ),
			'billing_email'               => __( 'Billing email', 'woocommerce-thank-you-page-customizer' ),
		);
		if ( is_array( $this->shortcodes ) && count( $this->shortcodes ) ) {
			?>
            <div class="<?php echo $this->set( $this->is_rtl ? array( 'available-shortcodes-container', 'available-shortcodes-container-rtl', 'hidden' ) : array(
				'available-shortcodes-container',
				'hidden'
			) ) ?>">
                <div class="<?php echo $this->set( 'available-shortcodes-overlay' ) ?>">
                </div>
                <div class="<?php echo $this->set( 'available-shortcodes-items' ) ?>">
                    <div class="<?php echo $this->set( 'available-shortcodes-items-header' ) ?>">
						<?php _e( 'Available shortcode', 'woocommerce-thank-you-page-customizer' ) ?>
                        <span class="<?php echo $this->set( 'available-shortcodes-items-close' ) ?> wtyp_icons-cancel"></span>
                    </div>
                    <div class="<?php echo $this->set( 'available-shortcodes-items-content' ) ?>">
						<?php
						foreach ( $this->shortcodes as $key => $value ) {
							?>
                            <div class="<?php echo $this->set( 'available-shortcodes-item' ) ?>">
                                <div class="<?php echo $this->set( 'available-shortcodes-item-name' ) ?>"><?php echo isset( $shortcode_titles[ $key ] ) ? $shortcode_titles[ $key ] : __( ucwords( str_replace( '_', ' ', $key ) ), 'woocommerce-thank-you-page-customizer' ) ?></div>
                                <div class="<?php echo $this->set( 'available-shortcodes-item-syntax' ) ?>">
                                    <input readonly value="<?php echo "{{$key}}" ?>">
                                    <span class="wtyp_icons-copy <?php echo $this->set( 'available-shortcodes-item-copy' ) ?>"></span>
                                </div>
                            </div>
							<?php
						}
						?>
                    </div>
                </div>
            </div>
			<?php
		}
		?>
        <div class="<?php echo $this->set( 'wp-editor-overlay' ) ?>"></div>
        <div class="<?php echo $this->set( 'wp-editor-container' ) ?>">
			<?php wp_editor( '', $this->set( 'wp-editor' ), array(
				'editor_height' => 300,
				'media_buttons' => true
			) ) ?>
            <div class="<?php echo $this->set( 'wp-editor-handle' ) ?>">
                <span class="<?php echo $this->set( 'wp-editor-save' ) ?>"><?php esc_html_e( 'OK', 'woocommerce-thank-you-page-customizer' ) ?></span>
                <span class="<?php echo $this->set( 'wp-editor-cancel' ) ?>"><?php esc_html_e( 'Cancel', 'woocommerce-thank-you-page-customizer' ) ?></span>
            </div>
        </div>
        <input type="hidden" class="<?php echo $this->set( 'google-map-address' ) ?>"
               value="<?php echo $this->google_map_address ?>">
        <input type="hidden" class="<?php echo $this->set( 'bing-map-address' ) ?>"
               value="<?php echo $this->bing_map_address ?>">
        <input type="hidden" class="<?php echo $this->set( 'coupon-code' ) ?>"
               value="<?php echo $this->coupon_code ?>">
        <input type="hidden" class="<?php echo $this->set( 'coupon-amount' ) ?>"
               value="<?php echo $this->coupon_amount ?>">
        <input type="hidden" class="<?php echo $this->set( 'coupon-date-expires' ) ?>"
               value="<?php echo $this->coupon_date_expires ?>">
        <input type="hidden" class="<?php echo $this->set( 'last-valid-date' ) ?>"
               value="<?php echo $this->last_valid_date ?>">
        <div class="<?php echo $this->set( 'preview-processing-overlay' ) ?>"></div>
        <div class="<?php echo $this->set( $this->is_rtl ? array( 'products-modal-container', 'products-modal-container-rtl' ) : 'products-modal-container' ) ?>">
            <div class="<?php echo $this->set( 'products-modal-overlay' ) ?>">
            </div>
            <div class="<?php echo $this->set( 'products-modal-items' ) ?>">
                <div class="<?php echo $this->set( 'products-modal-items-header' ) ?>">
					<?php _e( 'Products shortcode settings', 'woocommerce-thank-you-page-customizer' ) ?>
                    <span class="<?php echo $this->set( 'products-modal-items-close' ) ?> wtyp_icons-cancel"></span>
                </div>
                <div class="<?php echo $this->set( 'products-modal-items-content' ) ?>">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <label for="specific-products-product-options">
									<?php esc_html_e( 'Product options', 'woocommerce-thank-you-page-customizer' ) ?></label>
                            </th>
                            <td>
                                <select id="specific-products-product-options">
                                    <option value="none"><?php esc_html_e( 'None', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option value="sale"><?php esc_html_e( 'On sale', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option value="best_selling"><?php esc_html_e( 'Best selling products', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option value="recent"><?php esc_html_e( 'Recently published products', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option value="related"><?php esc_html_e( 'Related products of products in the order', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option value="recently_viewed"><?php esc_html_e( 'Recently viewed products', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option value="up_sells"><?php esc_html_e( 'Up-sells of products in the order', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option value="cross_sells"><?php esc_html_e( 'Cross-sells of products in the order', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option value="same_category"><?php esc_html_e( 'Products in the same categories of products in the order', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option value="featured"><?php esc_html_e( 'Featured products', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr valign="top" class="<?php echo $this->set( 'product-options-dependence' ) ?>">
                            <th scope="row">
                                <label for="specific-products-product-ids">
									<?php esc_html_e( 'Included products', 'woocommerce-thank-you-page-customizer' ) ?></label>
                            </th>
                            <td>
                                <select id="specific-products-product-ids"
                                        class="search-product-parent"
                                        multiple="multiple">
                                </select>
                            </td>
                        </tr>
                        <tr valign="top" class="<?php echo $this->set( 'product-options-dependence' ) ?>">
                            <th scope="row">
                                <label for="specific-products-excluded-product-ids">
									<?php esc_html_e( 'Excluded products', 'woocommerce-thank-you-page-customizer' ) ?></label>
                            </th>
                            <td>
                                <select id="specific-products-excluded-product-ids"
                                        class="search-product-parent"
                                        multiple="multiple">

                                </select>
                            </td>
                        </tr>
                        <tr valign="top" class="<?php echo $this->set( 'product-options-dependence' ) ?>">
                            <th scope="row">
                                <label for="specific-products-product-categories">
									<?php esc_html_e( 'Included categories', 'woocommerce-thank-you-page-customizer' ) ?></label>
                            </th>
                            <td>
                                <select id="specific-products-product-categories" class="search-category"
                                        multiple="multiple">

                                </select>
                            </td>
                        </tr>
                        <tr valign="top" class="<?php echo $this->set( 'product-options-dependence' ) ?>">
                            <th scope="row">
                                <label for="specific-products-excluded-product-categories">
									<?php esc_html_e( 'Excluded categories', 'woocommerce-thank-you-page-customizer' ) ?></label>
                            </th>
                            <td>
                                <select id="specific-products-excluded-product-categories" class="search-category"
                                        multiple="multiple">
                                </select>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="specific-products-visibility">
									<?php esc_html_e( 'Product visibility', 'woocommerce-thank-you-page-customizer' ) ?></label>
                            </th>
                            <td>
                                <select id="specific-products-visibility">
                                    <option title="<?php _e( 'Products visible on shop and search results', 'woocommerce-thank-you-page-customizer' ) ?>"
                                            value="visible"><?php esc_html_e( 'Visible', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option title="<?php _e( 'Products visible on the shop only, but not search results', 'woocommerce-thank-you-page-customizer' ) ?>"
                                            value="catalog"><?php esc_html_e( 'Catalog', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option title="<?php _e( 'Products visible in search results only, but not on the shop', 'woocommerce-thank-you-page-customizer' ) ?>"
                                            value="search"><?php esc_html_e( 'Search', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option title="<?php _e( 'Products that are hidden from both shop and search, accessible only by direct URL', 'woocommerce-thank-you-page-customizer' ) ?>"
                                            value="hidden"><?php esc_html_e( 'Hidden', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="specific-products-order-by">
									<?php esc_html_e( 'Order products by', 'woocommerce-thank-you-page-customizer' ) ?></label>
                            </th>
                            <td>
                                <select id="specific-products-order-by">
                                    <option title="<?php _e( 'The product title', 'woocommerce-thank-you-page-customizer' ) ?>"
                                            value="title"><?php esc_html_e( 'Title', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option title="<?php _e( 'The date the product was published', 'woocommerce-thank-you-page-customizer' ) ?>"
                                            value="date"><?php esc_html_e( 'Date', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option title="<?php _e( 'The post ID of the product', 'woocommerce-thank-you-page-customizer' ) ?>"
                                            value="id"><?php esc_html_e( 'ID', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option title="<?php _e( 'The Menu Order, if set (lower numbers display first)', 'woocommerce-thank-you-page-customizer' ) ?>"
                                            value="menu_order"><?php esc_html_e( 'Menu order', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option title="<?php _e( 'The number of purchases', 'woocommerce-thank-you-page-customizer' ) ?>"
                                            value="popularity"><?php esc_html_e( 'Popularity', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option title="<?php _e( 'Randomly order the products on page load (may not work with sites that use caching, as it could save a specific order)', 'woocommerce-thank-you-page-customizer' ) ?>"
                                            value="rand"><?php esc_html_e( 'Random', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option title="<?php _e( 'The average product rating', 'woocommerce-thank-you-page-customizer' ) ?>"
                                            value="rating"><?php esc_html_e( 'Rating', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                </select>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">
                                <label for="specific-products-order">
									<?php esc_html_e( 'Order', 'woocommerce-thank-you-page-customizer' ) ?></label>
                            </th>
                            <td>
                                <select id="specific-products-order">
                                    <option title="<?php _e( 'Ascending order from lowest to highest values (1, 2, 3; a, b, c).', 'woocommerce-thank-you-page-customizer' ) ?>"
                                            value="asc"><?php esc_html_e( 'ASC', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    <option title="<?php _e( 'Descending order from highest to lowest values (3, 2, 1; c, b, a).', 'woocommerce-thank-you-page-customizer' ) ?>"
                                            value="desc"><?php esc_html_e( 'DESC', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                </select>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">
                                <label for="specific-products-columns">
									<?php esc_html_e( 'Number of columns', 'woocommerce-thank-you-page-customizer' ) ?></label>
                            </th>
                            <td>
                                <input type="number" min="0"
                                       id="specific-products-columns">
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="specific-products-limit">
									<?php esc_html_e( 'Product limit', 'woocommerce-thank-you-page-customizer' ) ?></label>
                            </th>
                            <td>
                                <input type="number" min="0" id="specific-products-limit">
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="specific-products-slider-loop">
									<?php esc_html_e( 'Infinite loop', 'woocommerce-thank-you-page-customizer' ) ?></label>
                            </th>
                            <td>
                                <input type="checkbox" id="specific-products-slider-loop" value="1">
                                <label for="specific-products-slider-loop">
									<?php esc_html_e( 'Start over when reaching the end of slide', 'woocommerce-thank-you-page-customizer' ) ?>
                                </label>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="specific-products-slider-move">
									<?php esc_html_e( 'Number of carousel items that should move on animation', 'woocommerce-thank-you-page-customizer' ) ?></label>
                            </th>
                            <td>
                                <input type="number" min="0" id="specific-products-slider-move">
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="specific-products-slider-slideshow">
									<?php esc_html_e( 'Autoplay', 'woocommerce-thank-you-page-customizer' ) ?></label>
                            </th>
                            <td>
                                <input type="checkbox" id="specific-products-slider-slideshow" value="1">
                                <label for="specific-products-slider-slideshow">
									<?php esc_html_e( 'Auto play slideshow with settings below', 'woocommerce-thank-you-page-customizer' ) ?>
                                </label>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="specific-products-slider-slideshow-speed">
									<?php esc_html_e( 'Slideshow speed(milliseconds)', 'woocommerce-thank-you-page-customizer' ) ?></label>
                            </th>
                            <td>
                                <input type="number" min="0" id="specific-products-slider-slideshow-speed">
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">
                                <label for="specific-products-slider-pause-on-hover">
									<?php esc_html_e( 'Pause on hover', 'woocommerce-thank-you-page-customizer' ) ?></label>
                            </th>
                            <td>
                                <input type="checkbox" id="specific-products-slider-pause-on-hover" value="1">
                                <label for="specific-products-slider-pause-on-hover">
									<?php esc_html_e( 'Pause the slideshow when hovering and resume when no longer hovering', 'woocommerce-thank-you-page-customizer' ) ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="<?php echo $this->set( 'products-modal-handle' ) ?>">
                    <span class="<?php echo $this->set( 'products-modal-save' ) ?>"><?php esc_html_e( 'OK', 'woocommerce-thank-you-page-customizer' ) ?></span>
                    <span class="<?php echo $this->set( 'products-modal-cancel' ) ?>"><?php esc_html_e( 'Cancel', 'woocommerce-thank-you-page-customizer' ) ?></span>
                </div>
            </div>
        </div>
		<?php
	}

	private function get_params( $name = '', $language = '' ) {
		if ( $this->customize_preview_data && $name && $setting = $this->customize_preview_data->get_setting( 'woo_thank_you_page_params[' . $name . ']' ) ) {
			return $this->customize_preview_data->post_value( $setting, $this->settings->get_params( $name ) );
		} else {
			return $this->settings->get_params( $name, $language );
		}
	}

	private function set( $name ) {
		if ( is_array( $name ) ) {
			return implode( ' ', array_map( array( $this, 'set' ), $name ) );

		} else {
			return esc_attr__( $this->prefix . $name );

		}
	}

	public function wc_price( $price, $args = array() ) {
		extract(
			apply_filters(
				'wc_price_args', wp_parse_args(
					$args, array(
						'ex_tax_label'       => false,
						'currency'           => get_option( 'woocommerce_currency' ),
						'decimal_separator'  => get_option( 'woocommerce_price_decimal_sep' ),
						'thousand_separator' => get_option( 'woocommerce_price_thousand_sep' ),
						'decimals'           => get_option( 'woocommerce_price_num_decimals', 2 ),
						'price_format'       => get_woocommerce_price_format(),
					)
				)
			)
		);
		$currency_pos = get_option( 'woocommerce_currency_pos' );
		$price_format = '%1$s%2$s';

		switch ( $currency_pos ) {
			case 'left' :
				$price_format = '%1$s%2$s';
				break;
			case 'right' :
				$price_format = '%2$s%1$s';
				break;
			case 'left_space' :
				$price_format = '%1$s&nbsp;%2$s';
				break;
			case 'right_space' :
				$price_format = '%2$s&nbsp;%1$s';
				break;
		}

		$negative = $price < 0;
		$price    = apply_filters( 'raw_woocommerce_price', floatval( $negative ? $price * - 1 : $price ) );
		$price    = apply_filters( 'formatted_woocommerce_price', number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );

		if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $decimals > 0 ) {
			$price = wc_trim_zeros( $price );
		}

		$formatted_price = ( $negative ? '-' : '' ) . sprintf( $price_format, $currency, $price );

		return $formatted_price;
	}

	public function get_content( $blocks, $order, $text_editor, $products, $meta, $shortcodes ) {
		$order_confirmation                      = $meta['order_confirmation'] ?? array();
		$order_details_header                    = $meta['order_details_header'] ?? '';
		$order_details_product_title_text        = $meta['order_details_product_title_text'] ?? '';
		$order_details_product_value_text        = $meta['order_details_product_value_text'] ?? '';
		$order_details_product_image             = isset( $meta['order_details_product_image'] ) ? $meta['order_details_product_image'] : false;
		$order_details_product_quantity_in_image = isset( $meta['order_details_product_quantity_in_image'] ) ? $meta['order_details_product_quantity_in_image'] : false;
		$customer_information                    = $meta['customer_information'] ?? array();
		$thank_you_message_header                = $meta['thank_you_message_header'] ?? '';
		$thank_you_message_message               = $meta['thank_you_message_message'] ?? '';
		$social_icons                            = $meta['social_icons'] ?? array();

		$this->text_editor = $text_editor;
		$this->products    = $products;
		ob_start();
		if ( $order ) {
			?>
            <input type="hidden" value="<?php echo $order->get_id(); ?>" class="wtyp-order-id">
			<?php

			if ( is_array( $blocks ) && count( $blocks ) ) {
				foreach ( $blocks as $row_key => $row_value ) {
					if ( is_array( $row_value ) ) {
						?>
                        <div class="<?php echo $this->set( array(
							'container__row',
							'container__row_' . $row_key,
							count( $row_value ) . '-column',
						) ) ?>">
							<?php
							if ( count( $row_value ) ) {
								foreach ( $row_value as $block_key => $block_value ) {
									?>
                                    <div class="<?php echo $this->set( array(
										'container__block',
										'container__block_' . $block_key,
									) ) ?>">
										<?php
										if ( is_array( $block_value ) && count( $block_value ) ) {

											foreach ( $block_value as $block_value_k => $block_value_v ) {
												switch ( $block_value_v ) {
													case 'order_confirmation':
														echo $this->order_confirmation_html( $order, $order_confirmation );
														break;
													case 'order_details':
														echo $this->order_details_html( $order, $order_details_header, $order_details_product_title_text, $order_details_product_value_text, $order_details_product_image, $order_details_product_quantity_in_image );
														break;
													case 'customer_information':
														echo $this->customer_information_html( $order, $customer_information );
														break;
													case 'social_icons':
														echo $this->social_icons_html( $social_icons );
														break;
													case 'text_editor':
														echo $this->text_editor_html( $order );
														break;
													case 'products':
														echo $this->products_html( $order );
														break;
													case 'google_map':
														echo $this->google_map_html( $order, $this->google_map_address );
														break;
													case 'bing_map':
														echo $this->bing_map_html( $order, $this->bing_map_address );
														break;
													case 'thank_you_message':
														echo $this->thank_you_message_html( $order, $thank_you_message_header, $thank_you_message_message );
														break;
													case 'coupon':
														echo $this->coupon_html( $order );
														break;
													case 'payment_method':
														echo $this->payment_method_html;
														break;
													case 'order_again':
														echo $this->order_again( $order );
														break;
													default:

												}

											}

										}
										?>
                                    </div>
									<?php
								}
							}
							?>
                        </div>
						<?php
					}
				}
			}
		}

		$content = ob_get_clean();
		if ( is_array( $shortcodes ) && count( $shortcodes ) ) {
			foreach ( $shortcodes as $shortcodes_key => $shortcodes_value ) {
				$content = str_replace( '{' . $shortcodes_key . '}', '<span class="wtypc_shortcodes_' . $shortcodes_key . '">' . $shortcodes_value . '</span>', $content );
			}
		}

		return $content;
	}

	public function woocommerce_valid_order_statuses_for_order_again( $order_status ) {
		$status = $this->settings->get_params( 'order_status' );
		if ( is_array( $status ) && count( $status ) ) {
			$order_status = array();
			foreach ( $status as $key => $value ) {
				$order_status[] = str_replace( 'wc-', '', $value );
			}
		}

		return $order_status;
	}

	protected function rand() {
		if ( $this->characters_array === null ) {
			$this->characters_array = array_merge( range( 0, 9 ), range( 'a', 'z' ) );
		}
		$rand = rand( 0, count( $this->characters_array ) - 1 );

		return $this->characters_array[ $rand ];
	}

	protected function create_code() {

		wp_reset_postdata();

		$code = $this->get_params( 'coupon_unique_prefix' )[ $this->coupon_select ];
		for ( $i = 0; $i < 6; $i ++ ) {
			$code .= $this->rand();
		}

		return $code;

	}

	public function create_coupon( $order ) {
		$order_id = $order->get_id();
		$code     = '';
		if ( $order ) {
			$email                  = $order->get_billing_email();
			$order_items_categories = array();
			$order_items_products   = array();
			$order_items            = $order->get_items();
			if ( is_array( $order_items ) && count( $order_items ) ) {
				foreach ( $order_items as $order_item ) {
					$product_id = $order_item->get_product_id();
					if ( ! in_array( $product_id, $order_items_products ) ) {
						$order_items_products[] = $product_id;
						$product                = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : new WC_Product( $product_id );;
						if ( $product ) {
							$order_items_categories += $product->get_category_ids();
						}
					}
					$variation_id = $order_item->get_variation_id();
					if ( $variation_id ) {
						$order_items_products[] = $variation_id;
					}
				}
			}
			$order_total                             = $order->get_total();
			$coupon_type                             = $this->get_params( 'coupon_type' );
			$coupon_rule_max_total                   = $this->get_params( 'coupon_rule_max_total' );
			$coupon_rule_min_total                   = $this->get_params( 'coupon_rule_min_total' );
			$coupon_rule_product_ids                 = $this->get_params( 'coupon_rule_product_ids' );
			$coupon_rule_excluded_product_ids        = $this->get_params( 'coupon_rule_excluded_product_ids' );
			$coupon_rule_product_categories          = $this->get_params( 'coupon_rule_product_categories' );
			$coupon_rule_excluded_product_categories = $this->get_params( 'coupon_rule_excluded_product_categories' );
			if ( is_array( $coupon_type ) && count( $coupon_type ) ) {
				foreach ( $coupon_type as $key => $value ) {
					if ( $order_total < $coupon_rule_min_total[ $key ] ) {
						continue;
					}
					if ( $coupon_rule_max_total[ $key ] > 0 && $order_total > $coupon_rule_max_total[ $key ] ) {
						continue;
					}
					if ( is_array( $coupon_rule_product_ids[ $key ] ) && count( $coupon_rule_product_ids[ $key ] ) && ! count( array_intersect( $coupon_rule_product_ids[ $key ], $order_items_products ) ) ) {
						continue;
					}
					if ( is_array( $coupon_rule_excluded_product_ids[ $key ] ) && count( $coupon_rule_excluded_product_ids[ $key ] ) && count( array_intersect( $coupon_rule_excluded_product_ids[ $key ], $order_items_products ) ) ) {
						continue;
					}

					if ( is_array( $coupon_rule_product_categories[ $key ] ) && count( $coupon_rule_product_categories[ $key ] ) && ! count( array_intersect( $coupon_rule_product_categories[ $key ], $order_items_categories ) ) ) {
						continue;
					}
					if ( is_array( $coupon_rule_excluded_product_categories[ $key ] ) && count( $coupon_rule_excluded_product_categories[ $key ] ) && count( array_intersect( $coupon_rule_excluded_product_categories[ $key ], $order_items_categories ) ) ) {
						continue;
					}
					$this->coupon_select = $key;
					switch ( $this->get_params( 'coupon_type' )[ $key ] ) {
						case 'existing':
							$code   = $this->get_params( 'existing_coupon' )[ $key ];
							$coupon = new WC_Coupon( $code );
							if ( $this->get_params( 'coupon_unique_email_restrictions' )[ $key ] ) {
								$er = $coupon->get_email_restrictions();
								if ( ! in_array( $email, $er ) ) {
									$er[] = $email;
									$coupon->set_email_restrictions( $er );
									$coupon->save();
								}
							}
							$code = $coupon->get_code();
							break;
						case 'unique':
							$code = $this->create_code();
						default:
					}
					break;
				}
			}

		}

		return $code;
	}

	public function the_content( $content ) {
		global $post, $wp_query;
		if ( ! $this->is_customize_preview ) {
			return $content;
		}
		if ( ! $this->order_id || ! $this->key ) {
			return $content;
		}
		if ( did_action( 'wp_footer' ) ) {
			return $content;
		}
		$blocks      = json_decode( $this->get_params( 'blocks' ) );
		$text_editor = json_decode( $this->get_params( 'text_editor' ), true );
		$products    = json_decode( $this->get_params( 'products' ), true );
		$meta        = array(
			'order_confirmation'                      => array(
				'order_confirmation_header'             => $this->get_params( 'order_confirmation_header' ),
				'order_confirmation_order_number_title' => $this->get_params( 'order_confirmation_order_number_title' ),
				'order_confirmation_date_title'         => $this->get_params( 'order_confirmation_date_title' ),
				'order_confirmation_order_total_title'  => $this->get_params( 'order_confirmation_order_total_title' ),
				'order_confirmation_email_title'        => $this->get_params( 'order_confirmation_email_title' ),
				'order_confirmation_payment_title'      => $this->get_params( 'order_confirmation_payment_title' ),
			),
			'order_details_header'                    => $this->get_params( 'order_details_header' ),
			'order_details_product_title_text'        => $this->get_params( 'order_details_product_title_text' ),
			'order_details_product_value_text'        => $this->get_params( 'order_details_product_value_text' ),
			'order_details_product_image'             => $this->get_params( 'order_details_product_image' ),
			'order_details_product_quantity_in_image' => $this->get_params( 'order_details_product_quantity_in_image' ),
			'customer_information'                    => array(
				'customer_information_header'         => $this->get_params( 'customer_information_header'),
				'customer_information_billing_title'  => $this->get_params( 'customer_information_billing_title' ),
				'customer_information_shipping_title' => $this->get_params( 'customer_information_shipping_title'),
			),
			'thank_you_message_header'                => $this->get_params( 'thank_you_message_header' ),
			'thank_you_message_message'               => $this->get_params( 'thank_you_message_message' ),
			'coupon_message'                          => $this->get_params( 'coupon_message' ),
			'social_icons'                            => array(
				'social_icons_header'           => $this->get_params( 'social_icons_header'),
				'social_icons_target'           => $this->get_params( 'social_icons_target' ),
				'social_icons_align'            => $this->get_params( 'social_icons_align' ),
				'social_icons_facebook_url'     => $this->get_params( 'social_icons_facebook_url' ),
				'social_icons_facebook_select'  => $this->get_params( 'social_icons_facebook_select' ),
				'social_icons_twitter_url'      => $this->get_params( 'social_icons_twitter_url' ),
				'social_icons_twitter_select'   => $this->get_params( 'social_icons_twitter_select' ),
				'social_icons_pinterest_url'    => $this->get_params( 'social_icons_pinterest_url' ),
				'social_icons_pinterest_select' => $this->get_params( 'social_icons_pinterest_select' ),
				'social_icons_instagram_url'    => $this->get_params( 'social_icons_instagram_url' ),
				'social_icons_instagram_select' => $this->get_params( 'social_icons_instagram_select' ),
				'social_icons_dribbble_url'     => $this->get_params( 'social_icons_dribbble_url' ),
				'social_icons_dribbble_select'  => $this->get_params( 'social_icons_dribbble_select' ),
				'social_icons_tumblr_url'       => $this->get_params( 'social_icons_tumblr_url' ),
				'social_icons_tumblr_select'    => $this->get_params( 'social_icons_tumblr_select' ),
				'social_icons_google_url'       => $this->get_params( 'social_icons_google_url' ),
				'social_icons_google_select'    => $this->get_params( 'social_icons_google_select' ),
				'social_icons_vkontakte_url'    => $this->get_params( 'social_icons_vkontakte_url' ),
				'social_icons_vkontakte_select' => $this->get_params( 'social_icons_vkontakte_select' ),
				'social_icons_linkedin_url'     => $this->get_params( 'social_icons_linkedin_url' ),
				'social_icons_linkedin_select'  => $this->get_params( 'social_icons_linkedin_select' ),
				'social_icons_youtube_url'      => $this->get_params( 'social_icons_youtube_url' ),
				'social_icons_youtube_select'   => $this->get_params( 'social_icons_youtube_select' ),
			),
		);
		$order       = function_exists( 'wc_get_order' ) ? wc_get_order( $this->order_id ) : new WC_Order( $this->order_id );;
		if ( $order ) {
			if ( $order->has_status( 'failed' ) ) {
				ob_start();
				?>
                <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce-thank-you-page-customizer' ); ?></p>

                <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
                    <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>"
                       class="button pay"><?php _e( 'Pay', 'woocommerce-thank-you-page-customizer' ) ?></a>
					<?php if ( is_user_logged_in() ) : ?>
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"
                           class="button pay"><?php _e( 'My account', 'woocommerce-thank-you-page-customizer' ); ?></a>
					<?php endif; ?>
                </p>
				<?php
				$content = ob_get_clean();
			} else {
				ob_start();
				do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
				$this->payment_method_html = ob_get_clean();
				$class                     = array( 'container', 'customize-preview' );
				if ( $this->is_rtl ) {
					$class[] = 'container-rtl';
				}
				$content = '<div class="' . $this->set( $class ) . '">' . $this->get_content( $blocks, $order, $text_editor, $products, $meta, $this->shortcodes ) . '</div>';
			}
		} else {
			ob_start();
			?>
            <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce-thank-you-page-customizer' ), null ); ?></p>
			<?php
			$content = ob_get_clean();
		}

		return $content;
	}

	private function social_icons_html( $icons_text ) {
		$social_icons_target = $icons_text['social_icons_target'] ?? '';
		$social_icons_header = $icons_text['social_icons_header'] ?? '';
		$facebook_url        = $icons_text['social_icons_facebook_url'] ?? '';
		$twitter_url         = $icons_text['social_icons_twitter_url'] ?? '';
		$pinterest_url       = $icons_text['social_icons_pinterest_url'] ?? '';
		$instagram_url       = $icons_text['social_icons_instagram_url'] ?? '';
		$dribbble_url        = $icons_text['social_icons_dribbble_url'] ?? '';
		$google_url          = $icons_text['social_icons_google_url'] ?? '';
		$tumblr_url          = $icons_text['social_icons_tumblr_url'] ?? '';
		$vkontakte_url       = $icons_text['social_icons_vkontakte_url'] ?? '';
		$linkedin_url        = $icons_text['social_icons_linkedin_url'] ?? '';
		$youtube_url         = $icons_text['social_icons_youtube_url'] ?? '';

		$facebook_select  = $icons_text['social_icons_facebook_select'] ?? '';
		$twitter_select   = $icons_text['social_icons_twitter_select'] ?? '';
		$pinterest_select = $icons_text['social_icons_pinterest_select'] ?? '';
		$instagram_select = $icons_text['social_icons_instagram_select'] ?? '';
		$dribbble_select  = $icons_text['social_icons_dribbble_select'] ?? '';
		$google_select    = $icons_text['social_icons_google_select'] ?? '';
		$tumblr_select    = $icons_text['social_icons_tumblr_select'] ?? '';
		$vkontakte_select = $icons_text['social_icons_vkontakte_select'] ?? '';
		$linkedin_select  = $icons_text['social_icons_linkedin_select'] ?? '';
		$youtube_select   = $icons_text['social_icons_youtube_select'] ?? '';
		$html             = '<div class="' . $this->set( array(
				'social_icons__container',
				'item__container'
			) ) . '" id="' . $this->set( 'social_icons__container' ) . '">';
		$html             .= '<div class="' . $this->set( array(
				'social_icons__header',
			) ) . '"><div class="' . $this->set( 'social_icons-header' ) . '"><div>' . wp_kses_post( nl2br( $social_icons_header ) ) . '</div></div></div>';
		$html             .= '<span class="' . $this->set( 'edit-item-shortcut' ) . ' wtyp_icons-edit" data-edit_section="social_icons">' . __( 'Edit', 'woocommerce-thank-you-page-customizer' ) . '</span><ul class="wtyp-list-socials wtyp-list-unstyled" id="wtyp-sharing-accounts">';
		ob_start();
		?>
        <a target="<?php echo $social_icons_target; ?>" href="<?php echo esc_url( $facebook_url ) ?>"
           class="wtyp-social-button wtyp-facebook">
            <span class="wtyp-social-icon <?php esc_attr_e( $facebook_select ) ?>"></span></a>
		<?php $facebook_html = ob_get_clean();

		$html .= '<li style="' . ( ! $facebook_url ? 'display:none' : '' ) . '" class="wtyp-facebook-follow">' . $facebook_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo $social_icons_target; ?>" href="<?php echo esc_url( $twitter_url ) ?>"
           class="wtyp-social-button wtyp-twitter">
            <span class="wtyp-social-icon <?php esc_attr_e( $twitter_select ) ?>"></span>
        </a>
		<?php
		$twitter_html = ob_get_clean();
		$html         .= '<li style="' . ( ! $twitter_url ? 'display:none' : '' ) . '" class="wtyp-twitter-follow">' . $twitter_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo $social_icons_target; ?>" href="<?php echo esc_url( $pinterest_url ) ?>"
           class="wtyp-social-button wtyp-pinterest"
           data-pin-do="buttonFollow">
            <span class="wtyp-social-icon <?php esc_attr_e( $pinterest_select ) ?>"></span>
        </a>
		<?php
		$pinterest_html = ob_get_clean();
		$html           .= '<li style="' . ( ! $pinterest_url ? 'display:none' : '' ) . '" class="wtyp-pinterest-follow">' . $pinterest_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo $social_icons_target; ?>" href="<?php echo esc_url( $instagram_url ) ?>"
           class="wtyp-social-button wtyp-instagram">
            <span class="wtyp-social-icon <?php esc_attr_e( $instagram_select ) ?>"></span>
        </a>
		<?php
		$instagram_html = ob_get_clean();
		$html           .= '<li style="' . ( ! $instagram_url ? 'display:none' : '' ) . '" class="wtyp-instagram-follow">' . $instagram_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo $social_icons_target; ?>" href="<?php echo esc_url( $dribbble_url ) ?>"
           class="wtyp-social-button wtyp-dribbble">
            <span class="wtyp-social-icon <?php esc_attr_e( $dribbble_select ) ?>"></span>
        </a>
		<?php
		$dribbble_html = ob_get_clean();
		$html          .= '<li style="' . ( ! $dribbble_url ? 'display:none' : '' ) . '" class="wtyp-dribbble-follow">' . $dribbble_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo $social_icons_target; ?>" href="<?php echo esc_url( $tumblr_url ) ?>"
           class="wtyp-social-button wtyp-tumblr">
            <span class="wtyp-social-icon <?php esc_attr_e( $tumblr_select ) ?>"></span>
        </a>
		<?php
		$tumblr_html = ob_get_clean();
		$html        .= '<li style="' . ( ! $tumblr_url ? 'display:none' : '' ) . '" class="wtyp-tumblr-follow">' . $tumblr_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo $social_icons_target; ?>" href="<?php echo esc_url( $google_url ) ?>"
           class="wtyp-social-button wtyp-google-plus">
            <span class="wtyp-social-icon <?php esc_attr_e( $google_select ) ?>"></span>
        </a>
		<?php
		$google_html = ob_get_clean();
		$html        .= '<li style="' . ( ! $google_url ? 'display:none' : '' ) . '" class="wtyp-google-follow">' . $google_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo $social_icons_target; ?>" href="<?php echo esc_url( $vkontakte_url ) ?>"
           class="wtyp-social-button wtyp-vk">
            <span class="wtyp-social-icon <?php esc_attr_e( $vkontakte_select ) ?>"></span>
        </a>
		<?php
		$vkontakte_html = ob_get_clean();
		$html           .= '<li style="' . ( ! $vkontakte_url ? 'display:none' : '' ) . '" class="wtyp-vkontakte-follow">' . $vkontakte_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo $social_icons_target; ?>" href="<?php echo esc_url( $linkedin_url ) ?>"
           class="wtyp-social-button wtyp-linkedin">
            <span class="wtyp-social-icon <?php esc_attr_e( $linkedin_select ) ?>"></span>
        </a>
		<?php
		$linkedin_html = ob_get_clean();
		$html          .= '<li style="' . ( ! $linkedin_url ? 'display:none' : '' ) . '" class="wtyp-linkedin-follow">' . $linkedin_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo $social_icons_target; ?>" href="<?php echo esc_url( $youtube_url ) ?>"
           class="wtyp-social-button wtyp-youtube">
            <span class="wtyp-social-icon <?php esc_attr_e( $youtube_select ) ?>"></span>
        </a>
		<?php
		$youtube_html = ob_get_clean();
		$html         .= '<li style="' . ( ! $youtube_url ? 'display:none' : '' ) . '" class="wtyp-youtube-follow">' . $youtube_html . '</li>';

		$html = apply_filters( 'wtyp_after_socials_html', $html );
		$html .= '</ul></div>';

		return $html;
	}

	private function thank_you_message_html( $order, $thank_you_message_header, $thank_you_message_message ) {
		ob_start();
		?>
        <div class="<?php echo $this->set( array( 'thank_you_message__container', 'item__container' ) ) ?>"
             id="<?php echo $this->set( 'thank_you_message__container' ) ?>">

                <span class="<?php echo $this->set( 'edit-item-shortcut' ) ?> wtyp_icons-edit"
                      data-edit_section="thank_you_message"><?php echo __( 'Edit', 'woocommerce-thank-you-page-customizer' ) ?></span>

            <div class="<?php echo $this->set( 'check' ) ?> wtyp_icons-accept">
                <div class="<?php echo $this->set( array(
					'thank_you_message__header',
					'thank_you_message__detail',
				) ) ?>">
                    <div class="<?php echo $this->set( 'thank_you_message-header' ) ?>">
                        <div><?php echo wp_kses_post( nl2br( $thank_you_message_header ) ); ?></div>
                    </div>
                </div>
                <div class="<?php echo $this->set( array(
					'thank_you_message__message',
					'thank_you_message__detail',
				) ) ?>">
                    <div class="<?php echo $this->set( 'thank_you_message-message' ) ?>">
                        <div><?php echo wp_kses_post( nl2br( $thank_you_message_message ) ); ?></div>
                    </div>
                </div>
            </div>

        </div>
		<?php
		return ob_get_clean();
	}

	private function order_again( $order ) {
		ob_start();
		if ( function_exists( 'woocommerce_order_again_button' ) ) {
			woocommerce_order_again_button( $order );
		}

		return ob_get_clean();
	}

	private function coupon_html( $order ) {
		$order_id          = $order->get_id();
		$coupon_message    = $this->get_params( 'coupon_message' );
		$give_coupon       = false;
		$give_coupon       = true;
		$coupon_code       = $this->create_coupon( $order );
		$coupon_code       = strtoupper( $coupon_code );
		$this->coupon_code = $coupon_code;
		if ( $coupon_code ) {
			if ( $this->get_params( 'coupon_type' )[ $this->coupon_select ] == 'existing' ) {
				$coupon = new WC_Coupon( $coupon_code );
				if ( $coupon ) {
					if ( $coupon->get_discount_type() == 'percent' ) {
						$this->coupon_amount = $coupon->get_amount() . '%';
					} else {
						$this->coupon_amount = $this->wc_price( $coupon->get_amount() );
					}
					$coupon_date_expires       = $coupon->get_date_expires();
					$this->last_valid_date     = empty( $coupon_date_expires ) ? '' : date_i18n( 'F d, Y', strtotime( $coupon_date_expires ) - 86400 );
					$this->coupon_date_expires = empty( $coupon_date_expires ) ? esc_html__( 'never expires', 'woocommerce-thank-you-page-customizer' ) : date_i18n( 'F d, Y', strtotime( $coupon_date_expires ) );
					$coupon_message            = str_replace( '{coupon_code}', $this->coupon_code, $coupon_message );
					$coupon_message            = str_replace( '{coupon_amount}', $this->coupon_amount, $coupon_message );
					$coupon_message            = str_replace( '{last_valid_date}', $this->last_valid_date, $coupon_message );
					$coupon_message            = str_replace( '{coupon_date_expires}', $this->coupon_date_expires, $coupon_message );
				}
			} else {
				if ( $this->get_params( 'coupon_unique_discount_type' )[ $this->coupon_select ] == 'percent' ) {
					$this->coupon_amount = $this->get_params( 'coupon_unique_amount' )[ $this->coupon_select ] . '%';
				} else {
					$this->coupon_amount = $this->wc_price( $this->get_params( 'coupon_unique_amount' )[ $this->coupon_select ] );
				}
				$coupon_date_expires       = $this->get_params( 'coupon_unique_date_expires' )[ $this->coupon_select ];
				$this->last_valid_date     = empty( $coupon_date_expires ) ? '' : date_i18n( 'F d, Y', strtotime( date_i18n( 'F d, Y' ) ) + $coupon_date_expires * 86400 );
				$this->coupon_date_expires = empty( $coupon_date_expires ) ? esc_html__( 'never expires', 'woocommerce-thank-you-page-customizer' ) : date_i18n( 'F d, Y', strtotime( date_i18n( 'F d, Y' ) ) + ( $coupon_date_expires + 1 ) * 86400 );
				$coupon_message            = str_replace( '{coupon_code}', $this->coupon_code, $coupon_message );
				$coupon_message            = str_replace( '{coupon_amount}', $this->coupon_amount, $coupon_message );
				$coupon_message            = str_replace( '{last_valid_date}', $this->last_valid_date, $coupon_message );
				$coupon_message            = str_replace( '{coupon_date_expires}', $this->coupon_date_expires, $coupon_message );
			}
		}


		if ( ! $give_coupon ) {
			return '';
		}
		ob_start();
		?>
        <div class="<?php echo $this->set( array( 'coupon__container', 'item__container' ) ) ?>"
             id="<?php echo $this->set( 'coupon__container' ) ?>">

                <span class="<?php echo $this->set( 'edit-item-shortcut' ) ?> wtyp_icons-edit"
                      data-edit_section="coupon"><?php echo __( 'Edit', 'woocommerce-thank-you-page-customizer' ) ?></span>
            <div class="<?php echo $this->set( array(
				'coupon__message',
				'coupon__detail',
			) ) ?>">
                <div class="<?php echo $this->set( 'coupon-message' ) ?>">
                    <div><?php echo wp_kses_post( nl2br( $coupon_message ) ); ?></div>
                </div>
            </div>
            <div class="<?php echo $this->set( array(
				'coupon__code',
				'coupon__detail',
			) ) ?>">
                <div class="<?php echo $this->set( 'coupon-code' ) ?>">
                    <span class="<?php echo $this->set( 'coupon__code-wrap' ); ?> wtyp_icons-scissors">
                        <input type="text" readonly class="<?php echo $this->set( 'coupon__code-code' ); ?>"
                               value="<?php echo $coupon_code; ?>">
                            <span class="<?php echo $this->set( 'coupon__code-email' ); ?> <?php echo $this->get_params( 'coupon_email_enable' ) ? '' : 'woocommerce-thank-you-page-hidden' ?>">
                                <span class="<?php echo $this->set( 'coupon__code-mail-me' ); ?> wtyp_icons-opened-email-envelope"
                                      title="<?php echo __( 'Email me', 'woocommerce-thank-you-page-customizer' ) ?>"></span>
                                <span class="<?php echo $this->set( 'coupon__code-copy-code' ); ?> wtyp_icons-copy"
                                      title="<?php echo __( 'Copy code', 'woocommerce-thank-you-page-customizer' ) ?>"></span>
                            </span>

                    </span>
                </div>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}

	private function google_map_html( $order, $google_map_address ) {
		ob_start();
		?>
        <div class="<?php echo $this->set( array( 'google_map__container', 'item__container' ) ) ?>"
             id="<?php echo $this->set( 'google_map__container' ) ?>">
                    <span class="<?php echo $this->set( 'edit-item-shortcut' ) ?> wtyp_icons-edit"
                          data-edit_section="google_map"><?php echo __( 'Edit', 'woocommerce-thank-you-page-customizer' ) ?></span>
            <div id="<?php echo $this->set( array( 'google-map' ) ) ?>"></div>
            <input type="hidden" id="<?php echo $this->set( array( 'google-map-address' ) ) ?>"
                   value="<?php echo $google_map_address ?>">
        </div>
		<?php


		return ob_get_clean();
	}

	private function bing_map_html( $order, $bing_map_address ) {
		ob_start();
		?>
        <div class="<?php echo $this->set( array( 'bing_map__container', 'item__container' ) ) ?>"
             id="<?php echo $this->set( 'bing_map__container' ) ?>">
                    <span class="<?php echo $this->set( 'edit-item-shortcut' ) ?> wtyp_icons-edit"
                          data-edit_section="bing_map"><?php echo __( 'Edit', 'woocommerce-thank-you-page-customizer' ) ?></span>
            <div id="<?php echo $this->set( array( 'bing-map' ) ) ?>"></div>
            <input type="hidden" id="<?php echo $this->set( array( 'bing-map-address' ) ) ?>"
                   value="<?php echo $bing_map_address ?>">
        </div>
		<?php


		return ob_get_clean();
	}

	private function products_html( $order ) {
		$html = '';
		if ( is_array( $this->products ) && count( $this->products ) ) {
			$products                    = array_splice( $this->products, 0, 1 )[0];
			$product_ids                 = isset( $products['product_ids'] ) ? $products['product_ids'] : array();
			$excluded_product_ids        = isset( $products['excluded_product_ids'] ) ? $products['excluded_product_ids'] : array();
			$product_categories          = isset( $products['product_categories'] ) ? $products['product_categories'] : array();
			$excluded_product_categories = isset( $products['excluded_product_categories'] ) ? $products['excluded_product_categories'] : array();
			$order_by                    = isset( $products['order_by'] ) ? $products['order_by'] : 'title';
			$visibility                  = isset( $products['visibility'] ) ? $products['visibility'] : 'visible';
			$order_                      = isset( $products['order'] ) ? $products['order'] : 'desc';
			$columns                     = isset( $products['columns'] ) ? $products['columns'] : '4';
			$limit                       = isset( $products['limit'] ) ? $products['limit'] : '';
			$product_options             = isset( $products['product_options'] ) ? $products['product_options'] : 'none';
			$slider_loop                 = isset( $products['slider_loop'] ) ? $products['slider_loop'] : '1';
			$slider_move                 = isset( $products['slider_move'] ) ? $products['slider_move'] : '1';
			$slider_slideshow            = isset( $products['slider_slideshow'] ) ? $products['slider_slideshow'] : '1';
			$slider_slideshow_speed      = isset( $products['slider_slideshow_speed'] ) ? $products['slider_slideshow_speed'] : '2000';
			$slider_pause_on_hover       = isset( $products['slider_pause_on_hover'] ) ? $products['slider_pause_on_hover'] : '1';
			$product_ids_data            = array();
			$found_products              = array();
			if ( count( $product_ids ) ) {
				foreach ( $product_ids as $product_id ) {
					$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : new WC_Product( $product_id );;
					if ( $product ) {
						$title       = $product->get_title();
						$product_sku = $product->get_sku();
						if ( $product_sku ) {
							$title .= '(' . $product_sku . ')';
						}
						if ( $product->is_type( 'variation' ) ) {
							if ( woocommerce_version_check() ) {
								$title = get_the_title( $product_id );
								if ( $product_sku ) {
									$title .= '(' . $product_sku . ')';
								}
							} else {
								$get_atts  = $product->get_variation_attributes();
								$attr_name = array_values( $get_atts )[0];
								$title     = get_the_title() . ' - ' . $attr_name;
								if ( $product_sku ) {
									$title .= '(' . $product_sku . ')';
								}

							}
						}
						$product_ids_data[ $product_id ] = $title;
					}
				}
			}
			$excluded_product_ids_data = array();
			if ( count( $excluded_product_ids ) ) {
				foreach ( $excluded_product_ids as $product_id ) {
					$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : new WC_Product( $product_id );;
					if ( $product ) {
						$title       = $product->get_title();
						$product_sku = $product->get_sku();
						if ( $product_sku ) {
							$title .= '(' . $product_sku . ')';
						}
						if ( $product->is_type( 'variation' ) ) {
							if ( woocommerce_version_check() ) {
								$title = get_the_title( $product_id );
								if ( $product_sku ) {
									$title .= '(' . $product_sku . ')';
								}
							} else {
								$get_atts  = $product->get_variation_attributes();
								$attr_name = array_values( $get_atts )[0];
								$title     = get_the_title() . ' - ' . $attr_name;
								if ( $product_sku ) {
									$title .= '(' . $product_sku . ')';
								}

							}
						}
						$excluded_product_ids_data[ $product_id ] = $title;
					}
				}
			}
			$product_categories_data = array();
			if ( count( $product_categories ) ) {
				foreach ( $product_categories as $category_id ) {
					$category                                = get_term( $category_id );
					$product_categories_data[ $category_id ] = $category->name;
				}
			}
			$excluded_product_categories_data = array();
			if ( count( $excluded_product_categories ) ) {
				foreach ( $excluded_product_categories as $category_id ) {
					$category                                         = get_term( $category_id );
					$excluded_product_categories_data[ $category_id ] = $category->name;
				}
			}
			switch ( $product_options ) {
				case 'best_selling':
					$args = array(
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'posts_per_page' => - 1,
						'meta_key'       => 'total_sales',
						'order'          => 'DESC',
						'orderby'        => 'meta_value_num',
					);
					if ( count( $product_categories ) || count( $excluded_product_categories ) ) {
						$args['tax_query'] = array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => $excluded_product_categories,
								'operator' => 'NOT IN'
							)
						);
						if ( count( $product_categories ) ) {
							$args['tax_query'][] = array(
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => $product_categories,
								'operator' => 'IN'
							);
						}
					}
					if ( count( $product_ids ) ) {
						$args['post__in'] = $product_ids;
					}
					$the_query = new WP_Query( $args );
					if ( $the_query->have_posts() ) {
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							$found_products[] = get_the_ID();
						}
					}
					wp_reset_postdata();
					if ( count( $excluded_product_ids ) ) {
						$found_products = array_diff( $found_products, $excluded_product_ids );
					}

					break;
				case 'sale':
					$sale_products = wc_get_product_ids_on_sale();
					if ( count( $product_ids ) ) {
						$sale_products = array_intersect( $sale_products, $product_ids );
					}
					$sale_products = array_diff( $sale_products, $excluded_product_ids );
					if ( count( $sale_products ) && count( $product_categories ) || count( $excluded_product_categories ) ) {
						$args = array(
							'post_type'      => 'product',
							'post_status'    => 'publish',
							'posts_per_page' => - 1,
							'post__in'       => $sale_products,
							'tax_query'      => array(
								'relation' => 'AND',
								array(
									'taxonomy' => 'product_cat',
									'field'    => 'term_id',
									'terms'    => $excluded_product_categories,
									'operator' => 'NOT IN'
								)
							)
						);
						if ( count( $product_categories ) ) {
							$args['tax_query'][] = array(
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => $product_categories,
								'operator' => 'IN'
							);
						}
						$the_query = new WP_Query( $args );
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								$found_products[] = get_the_ID();
							}
						}
						wp_reset_postdata();
					} else {
						$found_products = $sale_products;
					}
					break;
				case 'featured':
					$featured_products = wc_get_featured_product_ids();
					if ( count( $product_ids ) ) {
						$featured_products = array_intersect( $featured_products, $product_ids );
					}
					$featured_products = array_diff( $featured_products, $excluded_product_ids );
					if ( count( $featured_products ) ) {
						if ( count( $featured_products ) && count( $product_categories ) || count( $excluded_product_categories ) ) {
							$args = array(
								'post_type'      => 'product',
								'post_status'    => 'publish',
								'posts_per_page' => - 1,
								'post__in'       => $featured_products,
								'tax_query'      => array(
									'relation' => 'AND',
									array(
										'taxonomy' => 'product_cat',
										'field'    => 'term_id',
										'terms'    => $excluded_product_categories,
										'operator' => 'NOT IN'
									)
								)
							);
							if ( count( $product_categories ) ) {
								$args['tax_query'][] = array(
									'taxonomy' => 'product_cat',
									'field'    => 'term_id',
									'terms'    => $product_categories,
									'operator' => 'IN'
								);
							}
							$the_query = new WP_Query( $args );
							if ( $the_query->have_posts() ) {
								while ( $the_query->have_posts() ) {
									$the_query->the_post();
									$found_products[] = get_the_ID();
								}
							}
							wp_reset_postdata();
						} else {
							$found_products = $featured_products;
						}
					}
					break;
				case 'recent':
					$args = array(
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'posts_per_page' => - 1,
						'order'          => 'DESC',
						'orderby'        => 'date',
					);
					if ( count( $product_categories ) || count( $excluded_product_categories ) ) {
						$args['tax_query'] = array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => $excluded_product_categories,
								'operator' => 'NOT IN'
							)
						);
						if ( count( $product_categories ) ) {
							$args['tax_query'][] = array(
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => $product_categories,
								'operator' => 'IN'
							);
						}
					}
					if ( count( $product_ids ) ) {
						$args['post__in'] = $product_ids;
					}
					$the_query = new WP_Query( $args );
					if ( $the_query->have_posts() ) {
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							$found_products[] = get_the_ID();
						}
					}

					wp_reset_postdata();
					if ( count( $excluded_product_ids ) ) {
						$found_products = array_diff( $found_products, $excluded_product_ids );
					}
					break;

				case 'recently_viewed':
					$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) : array(); // @codingStandardsIgnoreLine
					$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
					if ( count( $product_ids ) ) {
						$viewed_products = array_intersect( $viewed_products, $product_ids );
					}
					$viewed_products = array_diff( $viewed_products, $excluded_product_ids );
					if ( count( $viewed_products ) && count( $product_categories ) || count( $excluded_product_categories ) ) {
						$args = array(
							'post_type'      => 'product',
							'post_status'    => 'publish',
							'posts_per_page' => - 1,
							'post__in'       => $viewed_products,
							'tax_query'      => array(
								'relation' => 'AND',
								array(
									'taxonomy' => 'product_cat',
									'field'    => 'term_id',
									'terms'    => $excluded_product_categories,
									'operator' => 'NOT IN'
								)
							)
						);
						if ( count( $product_categories ) ) {
							$args['tax_query'][] = array(
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => $product_categories,
								'operator' => 'IN'
							);
						}
						$the_query = new WP_Query( $args );
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								$found_products[] = get_the_ID();
							}
						}
						wp_reset_postdata();
					} else {
						$found_products = $viewed_products;
					}
					break;
				case 'related':
					$order_items          = $order->get_items();
					$order_items_products = array();
					if ( is_array( $order_items ) && count( $order_items ) ) {
						foreach ( $order_items as $order_item ) {
							$product_id             = $order_item->get_product_id();
							$order_items_products[] = $product_id;
							if ( count( $product_ids ) && ! in_array( $product_id, $product_ids ) ) {
								continue;
							}
							if ( count( $excluded_product_ids ) && in_array( $product_id, $product_ids ) ) {
								continue;
							}
							if ( count( $product_categories ) || count( $excluded_product_categories ) ) {
								$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : new WC_Product( $product_id );;
								if ( $product ) {
									$categories = $product->get_category_ids();
									if ( count( $product_categories ) && ! count( array_intersect( $categories, $product_categories ) ) ) {
										continue;
									}
									if ( count( $excluded_product_categories ) && count( array_intersect( $categories, $excluded_product_categories ) ) ) {
										continue;
									}
								}
							}
							$p_related = wc_get_related_products( $product_id );
							if ( is_array( $p_related ) && count( $p_related ) ) {
								$found_products += $p_related;
							}
						}
					}
					$found_products = array_diff( $found_products, $order_items_products );
					break;
				case 'up_sells':
					$order_items          = $order->get_items();
					$order_items_products = array();
					if ( is_array( $order_items ) && count( $order_items ) ) {
						foreach ( $order_items as $order_item ) {
							$product_id             = $order_item->get_product_id();
							$order_items_products[] = $product_id;
							if ( count( $product_ids ) && ! in_array( $product_id, $product_ids ) ) {
								continue;
							}
							if ( count( $excluded_product_ids ) && in_array( $product_id, $product_ids ) ) {
								continue;
							}
							if ( count( $product_categories ) || count( $excluded_product_categories ) ) {
								$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : new WC_Product( $product_id );;
								if ( $product ) {
									$categories = $product->get_category_ids();
									if ( count( $product_categories ) && ! count( array_intersect( $categories, $product_categories ) ) ) {
										continue;
									}
									if ( count( $excluded_product_categories ) && count( array_intersect( $categories, $excluded_product_categories ) ) ) {
										continue;
									}
								}
							}
							$p_up_sells = get_post_meta( $product_id, '_upsell_ids', true );
							if ( is_array( $p_up_sells ) && count( $p_up_sells ) ) {
								$found_products += $p_up_sells;
							}
						}
					}
					$found_products = array_diff( $found_products, $order_items_products );
					break;
				case 'cross_sells':
					$order_items          = $order->get_items();
					$order_items_products = array();
					if ( is_array( $order_items ) && count( $order_items ) ) {
						foreach ( $order_items as $order_item ) {
							$product_id             = $order_item->get_product_id();
							$order_items_products[] = $product_id;
							if ( count( $product_ids ) && ! in_array( $product_id, $product_ids ) ) {
								continue;
							}
							if ( count( $excluded_product_ids ) && in_array( $product_id, $product_ids ) ) {
								continue;
							}
							if ( count( $product_categories ) || count( $excluded_product_categories ) ) {
								$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : new WC_Product( $product_id );;
								if ( $product ) {
									$categories = $product->get_category_ids();
									if ( count( $product_categories ) && ! count( array_intersect( $categories, $product_categories ) ) ) {
										continue;
									}
									if ( count( $excluded_product_categories ) && count( array_intersect( $categories, $excluded_product_categories ) ) ) {
										continue;
									}
								}
							}
							$p_cross_sells = get_post_meta( $product_id, '_crosssell_ids', true );
							if ( is_array( $p_cross_sells ) && count( $p_cross_sells ) ) {
								$found_products += $p_cross_sells;
							}
						}
					}
					$found_products = array_diff( $found_products, $order_items_products );
					break;
				case 'same_category':
					$order_items          = $order->get_items();
					$order_items_products = array();
					if ( is_array( $order_items ) && count( $order_items ) ) {
						foreach ( $order_items as $order_item ) {
							$product_id             = $order_item->get_product_id();
							$order_items_products[] = $product_id;
							$product                = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : new WC_Product( $product_id );;
							if ( $product ) {
								$categories = $product->get_category_ids();
								if ( count( $categories ) ) {
									if ( count( $product_categories ) ) {
										$categories = array_intersect( $categories, $product_categories );
									}
									$args = array(
										'post_type'      => 'product',
										'post_status'    => 'publish',
										'posts_per_page' => - 1,
										'tax_query'      => array(
											'relation' => 'AND',
											array(
												'taxonomy' => 'product_cat',
												'field'    => 'term_id',
												'terms'    => $categories,
												'operator' => 'IN'
											),
											array(
												'taxonomy' => 'product_cat',
												'field'    => 'term_id',
												'terms'    => $excluded_product_categories,
												'operator' => 'NOT IN'
											)
										)
									);
									if ( count( $product_ids ) ) {
										$args['post__in'] = $product_ids;
									}

									$the_query = new WP_Query( $args );
									if ( $the_query->have_posts() ) {
										while ( $the_query->have_posts() ) {
											$the_query->the_post();
											$found_products[] = get_the_ID();
										}
									}
									wp_reset_postdata();
									/*post__not_in will be ignored if using in the same query as post__in*/
									if ( count( $excluded_product_ids ) ) {
										$found_products = array_diff( $found_products, $excluded_product_ids );
									}
								}
							}
						}
					}
					$found_products = array_diff( $found_products, $order_items_products );
					break;
				case 'none':
					if ( count( $product_categories ) ) {
						$args      = array(
							'post_type'      => 'product',
							'post_status'    => 'publish',
							'posts_per_page' => - 1,
							'tax_query'      => array(
								'relation' => 'AND',
								array(
									'taxonomy' => 'product_cat',
									'field'    => 'term_id',
									'terms'    => $product_categories,
									'operator' => 'IN'
								),
								array(
									'taxonomy' => 'product_cat',
									'field'    => 'term_id',
									'terms'    => $excluded_product_categories,
									'operator' => 'NOT IN'
								)
							)
						);
						$the_query = new WP_Query( $args );
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								$found_products[] = get_the_ID();
							}
						}
						wp_reset_postdata();
						if ( count( $product_ids ) ) {
							if ( count( $found_products ) ) {
								$found_products = array_intersect( $found_products, $product_ids );

							} else {
								$found_products = $product_ids;

							}
						}
						if ( count( $excluded_product_ids ) ) {
							$found_products = array_diff( $found_products, $excluded_product_ids );
						}
					} elseif ( count( $excluded_product_categories ) ) {
						$args      = array(
							'post_type'      => 'product',
							'post_status'    => 'publish',
							'posts_per_page' => - 1,
							'tax_query'      => array(
								array(
									'taxonomy' => 'product_cat',
									'field'    => 'term_id',
									'terms'    => $excluded_product_categories,
									'operator' => 'NOT IN'
								)
							)
						);
						$the_query = new WP_Query( $args );
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								$found_products[] = get_the_ID();
							}
						}
						wp_reset_postdata();
						if ( count( $product_ids ) ) {
							if ( count( $found_products ) ) {
								$found_products = array_intersect( $found_products, $product_ids );

							} else {
								$found_products = $product_ids;

							}
						}
						if ( count( $excluded_product_ids ) ) {
							$found_products = array_diff( $found_products, $excluded_product_ids );
						}
					} else {
						if ( count( $product_ids ) ) {
							$found_products = array_diff( $product_ids, $excluded_product_ids );
						} elseif ( count( $excluded_product_ids ) ) {
							$args      = array(
								'post_type'      => 'product',
								'post_status'    => 'publish',
								'posts_per_page' => - 1,
								'post__not_in'   => $excluded_product_ids
							);
							$the_query = new WP_Query( $args );
							if ( $the_query->have_posts() ) {
								while ( $the_query->have_posts() ) {
									$the_query->the_post();
									$found_products[] = get_the_ID();
								}
							}
							wp_reset_postdata();
						}
					}
					break;
				default:
			}
			$this->products_id ++;

			$found_products = array_unique( $found_products );
			ob_start();
			?>
            <div class="<?php echo $this->set( array( 'products', 'item__container' ) ) ?>">
                <div class="<?php echo $this->set( array( 'products-sliders' ) ) ?> vi-flexslider">
                    <div class="<?php echo $this->set( 'products-content' ) ?>"
                         data-product_ids="<?php echo htmlentities( json_encode( $product_ids_data ) ) ?>"
                         data-excluded_product_ids="<?php echo htmlentities( json_encode( $excluded_product_ids_data ) ) ?>"
                         data-product_categories="<?php echo htmlentities( json_encode( $product_categories_data ) ) ?>"
                         data-excluded_product_categories="<?php echo htmlentities( json_encode( $excluded_product_categories_data ) ) ?>"
                         data-order_by="<?php echo $order_by ?>"
                         data-visibility="<?php echo $visibility ?>"
                         data-order="<?php echo $order_ ?>"
                         data-wtypc_columns="<?php echo $columns ?>"
                         data-limit="<?php echo $limit ?>"
                         data-product_options="<?php echo $product_options ?>"
                         data-slider_loop="<?php echo $slider_loop ?>"
                         data-slider_move="<?php echo $slider_move ?>"
                         data-slider_slideshow="<?php echo $slider_slideshow ?>"
                         data-slider_slideshow_speed="<?php echo $slider_slideshow_speed ?>"
                         data-slider_pause_on_hover="<?php echo $slider_pause_on_hover ?>"
                    >

						<?php
						if ( count( $found_products ) ) {
							$show_products = array();
							$args1         = array(
								'post_type'      => 'product',
								'posts_per_page' => - 1,
								'post__in'       => $found_products,
								'order'          => strtoupper( $order_ ),
								'post_parent'    => 0,
							);
							switch ( $order_by ) {
								case 'id':
									$args1['orderby'] = 'ID';
									break;
								case 'rating':
									$args1['meta_key'] = '_wc_average_rating';
									$args1['orderby']  = 'meta_value_num';
									break;
								case 'popularity':
									$args1['meta_key'] = 'total_sales';
									$args1['orderby']  = 'meta_value_num';
									break;
								case 'title':
									$args1['orderby'] = $order_by;
									break;
								case 'rand':
								case 'date':
								case 'menu_order':
									$args1['orderby'] = $order_by;
									break;
								default:
							}
							$the_query = new WP_Query( $args1 );
							if ( $the_query->have_posts() ) {
								while ( $the_query->have_posts() ) {
									$the_query->the_post();
									$post_id = get_the_ID();
									$prd     = function_exists( 'wc_get_product' ) ? wc_get_product( $post_id ) : new WC_Product( $post_id );
									if ( ! in_array( $prd->get_type(), array(
										'simple',
										'variable',
										'external',
										'grouped',
										'bundle',
										'composite',
									) ) ) {
										continue;
									}
									if ( $prd->get_catalog_visibility() != $visibility ) {
										continue;
									}
									$show_products[] = $post_id;
								}
							}
							wp_reset_postdata();
							if ( count( $show_products ) ) {
								if ( $limit > 0 ) {
									$num = 0;
									foreach ( $show_products as $show_products_id ) {
										$shortcode = do_shortcode( '[products ids="' . $show_products_id . '" limit="' . 1 . '" columns="' . 1 . '" visibility="' . $visibility . '"]' );
										if ( $shortcode ) {
											?>
                                            <div class="<?php echo $this->set( 'products-content-item' ) ?>">
												<?php
												echo $shortcode;
												?>
                                            </div>
											<?php
											$num ++;
										}
										if ( $num == $limit ) {
											break;
										}
									}
								} else {
									foreach ( $show_products as $show_products_id ) {
										$shortcode = do_shortcode( '[products ids="' . $show_products_id . '" limit="' . 1 . '" columns="' . 1 . '" visibility="' . $visibility . '"]' );
										if ( $shortcode ) {
											?>
                                            <div class="<?php echo $this->set( 'products-content-item' ) ?>">
												<?php
												echo $shortcode;
												?>
                                            </div>
											<?php
										}
									}
								}

							} else {
								esc_html_e( 'No matched products found', 'woocommerce-thank-you-page-customizer' );
							}
						} else {
							esc_html_e( 'No matched products found', 'woocommerce-thank-you-page-customizer' );
						}
						?>
                    </div>
                </div>

                <span class="<?php echo $this->set( 'products-edit' ) ?> wtyp_icons-edit"><?php esc_html_e( 'Edit', 'woocommerce-thank-you-page-customizer' ) ?></span>
            </div>
			<?php
			$html = ob_get_clean();
		}

		return $html;
	}

	private function text_editor_html( $order ) {
		ob_start();
		if ( is_array( $this->text_editor ) && count( $this->text_editor ) ) {
			$text = array_splice( $this->text_editor, 0, 1 )[0];
			if (is_string($text)){
				$text = base64_decode( $text );
            }else {
				$text = base64_decode( $text[0]??'' );
			}
			?>
            <div class="<?php echo $this->set( array( 'text-editor', 'item__container' ) ) ?>"
                 id="<?php echo $this->set( 'text-editor-' . $this->text_editor_id ) ?>">
                <div class="<?php echo $this->set( 'text-editor-content' ) ?>">
					<?php
					echo do_shortcode( $text );
					?>
                </div>
                <?php
                $languages = $this->language ?: (isset($_POST['wtypc_languages'])? wc_clean($_POST['wtypc_languages']):'');
                if ($languages && is_array($languages) && count($languages)){
                    echo sprintf('<span class="%s"><span class="%s wtyp_icons-edit">%s</span>',
                        $this->set( 'text-editor-edit-wrap' ),
                        $this->set( 'text-editor-edit' ),
                        __( 'Edit', 'woocommerce-thank-you-page-customizer' )
                    );
	                foreach ( $languages as $key => $value ) {
	                    echo sprintf('<span class="%s wtyp_icons-edit" data-wtypc_language="%s">%s</span>',
		                    $this->set(array('text-editor-edit','text-editor-edit-language','text-editor-edit-'.$value)),
                            $value,
		                    __( 'Edit', 'woocommerce-thank-you-page-customizer' ) . "( {$value} )");
	                }
                    echo sprintf('</span>');
                }else{
                    ?>
                    <span class="<?php echo $this->set( 'text-editor-edit' ) ?> wtyp_icons-edit"><?php esc_html_e( 'Edit', 'woocommerce-thank-you-page-customizer' ) ?></span>
                    <?php
                }
                ?>

            </div>
			<?php
			$this->text_editor_id ++;
		}

		return ob_get_clean();
	}

	private function customer_information_html( $order, $customer_information ) {
		$customer_information_header         = $customer_information['customer_information_header'] ?? '';
		$customer_information_billing_title  = $customer_information['customer_information_billing_title'] ?? '';
		$customer_information_shipping_title = $customer_information['customer_information_shipping_title'] ?? '';
		ob_start();
		$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
		?>
        <div class="<?php echo $this->set( array( 'customer_information__container', 'item__container' ) ) ?>"
             id="<?php echo $this->set( 'customer_information__container' ) ?>">

                <span class="<?php echo $this->set( 'edit-item-shortcut' ) ?> wtyp_icons-edit"
                      data-edit_section="customer_information"><?php echo __( 'Edit', 'woocommerce-thank-you-page-customizer' ) ?></span>

            <div class="<?php echo $this->set( array(
				'customer_information__header',
				'customer_information__detail',
			) ) ?>">
                <div class="<?php echo $this->set( 'customer_information-header' ) ?>">
                    <div><?php echo trim( strtolower( $customer_information_header ) ) === 'customer information' ? __( 'Customer information', 'woocommerce-thank-you-page-customizer' ) : wp_kses_post( nl2br( $customer_information_header ) ); ?></div>
                </div>
            </div>
            <div class="<?php echo $this->set( array(
				'customer_information__address',
				'customer_information__detail',
			) ) ?>">
                <div class="<?php echo $this->set( array(
					'customer_information__billing_address',
				) ) ?>">
                    <div class="<?php echo $this->set( array(
						'customer_information__billing_address-header',
					) ) ?>">
						<?php echo wp_kses_post( nl2br( $customer_information_billing_title ) ); ?>
                    </div>
                    <div class="<?php echo $this->set( array(
						'customer_information__billing_address-address',
					) ) ?>">
						<?php echo wp_kses_post( $order->get_formatted_billing_address( __( 'N/A', 'woocommerce-thank-you-page-customizer' ) ) ); ?>

						<?php if ( $order->get_billing_phone() ) : ?>
                            <div><?php echo esc_html( $order->get_billing_phone() ); ?></div>
						<?php endif; ?>

						<?php if ( $order->get_billing_email() ) : ?>
                            <div><?php echo esc_html( $order->get_billing_email() ); ?></div>
						<?php endif; ?>
                    </div>
                </div>
				<?php
				if ( $show_shipping ) {
					?>
                    <div class="<?php echo $this->set( array(
						'customer_information__shipping_address',
					) ) ?>">
                        <div class="<?php echo $this->set( array(
							'customer_information__shipping_address-header',
						) ) ?>">
							<?php echo wp_kses_post( nl2br( $customer_information_shipping_title ) ); ?>
                        </div>
                        <div class="<?php echo $this->set( array(
							'customer_information__shipping_address-address',
						) ) ?>">
							<?php echo wp_kses_post( $order->get_formatted_shipping_address( __( 'N/A', 'woocommerce-thank-you-page-customizer' ) ) ); ?>
                        </div>
                    </div>
					<?php
				}
				?>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}

	private function order_details_html( $order, $order_details_header, $order_details_product_title_text, $order_details_product_value_text, $order_details_product_image, $order_details_product_quantity_in_image ) {
		ob_start();

		$order_items        = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
		$show_purchase_note = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array(
			'completed',
			'processing'
		) ) );
		$downloads          = $order->get_downloadable_items();
		$show_downloads     = $order->has_downloadable_item() && $order->is_download_permitted();

		if ( $show_downloads ) {
			wc_get_template( 'order/order-downloads.php', array( 'downloads' => $downloads, 'show_title' => true ) );
		}
		?>
        <div class="<?php echo $this->set( array( 'order_details__container', 'item__container' ) ) ?>"
             id="<?php echo $this->set( 'order_details__container' ) ?>">

                <span class="<?php echo $this->set( 'edit-item-shortcut' ) ?> wtyp_icons-edit"
                      data-edit_section="order_details"><?php echo __( 'Edit', 'woocommerce-thank-you-page-customizer' ) ?></span>

            <div class="<?php echo $this->set( array(
				'order_details__header',
				'order_details__detail'
			) ) ?>">
                <div class="<?php echo $this->set( array(
					'order_details-header'
				) ) ?>">
                    <div><?php echo trim( strtolower( $order_details_header ) ) === 'order details' ? __( 'Order details', 'woocommerce-thank-you-page-customizer' ) : wp_kses_post( nl2br( $order_details_header ) ); ?></div>
                </div>
            </div>

            <div class="<?php echo $this->set( array(
				'order_details__header',
				'order_details__detail'
			) ) ?>">
                <div class="<?php echo $this->set( array(
					'order_details__header-title',
					'order_details-title'
				) ) ?>">
                    <div><?php echo wp_kses_post( nl2br( $order_details_product_title_text ) ); ?></div>
                </div>
                <div class="<?php echo $this->set( array(
					'order_details__header-value',
					'order_details-value'
				) ) ?>">
                    <div><?php echo wp_kses_post( nl2br( $order_details_product_value_text ) ); ?></div>
                </div>
            </div>
            <div class="<?php echo $this->set( array(
				'order_details__order_items'
			) ) ?>">
				<?php
				foreach ( $order_items as $item_id => $item ) {
					$product = $item->get_product();
					if ( ! $product ) {
						continue;
					}
					$purchase_note = $product->get_purchase_note();
					?>
                    <div class="<?php echo $this->set( array(
						'order_details__product',
						'order_details__detail'
					) ) ?>">
                        <div class="<?php echo $this->set( array(
							'order_details__product-title',
							'order_details-title'
						) ) ?>">
							<?php
							$is_visible                    = $product && $product->is_visible();
							$product_permalink             = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );
							$product_image_src             = wc_placeholder_img_src();
							$alt                           = '';
							$product_quantity_html_default = ' <strong class="product-quantity ' . $this->set( 'order-item-product-quantity-default' ) . '">' . sprintf( '&times; %s', $item->get_quantity() ) . '</strong>';
							//							if ( $order_details_product_quantity_in_image ) {
							//								$product_quantity_html = '<span class="' . $this->set( 'order-item-product-quantity' ) . '">' . $item->get_quantity() . '</span>';
							//							}
							if ( $product->get_image_id() ) {
								$product_image_src = wp_get_attachment_thumb_url( $product->get_image_id() );
								$alt               = get_post_meta( $product->get_id(), '_wp_attachment_image_alt', true );
							}
							echo apply_filters( 'woo_thank_you_page_order_item_image', $product_permalink ? sprintf( '<div class="%s"><a href="%s" class="%s"><img class="%s" src="%s" alt="%s"></a></div>', ( $order_details_product_image ? $this->set( array(
								'order-item-image-container',
								'active'
							) ) : $this->set( 'order-item-image-container' ) ), $product_permalink, $this->set( 'order-item-image-wrap' ), $this->set( 'order-item-image' ), $product_image_src, $alt ? $alt : $item->get_name() ) : $item->get_name(), $item, $is_visible );

							?>
                            <div>
								<?php
								echo apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a href="%s">%s</a>', $product_permalink, $item->get_name() ) : $item->get_name(), $item, $is_visible );
								echo apply_filters( 'woocommerce_order_item_quantity_html', $product_quantity_html_default, $item );
								?>
                            </div>
							<?php
							do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false );

							wc_display_item_meta( $item );

							do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false );
							?>
                        </div>
                        <div class="<?php echo $this->set( array(
							'order_details__product-value',
							'order_details-value'
						) ) ?>">
							<?php echo $order->get_formatted_line_subtotal( $item ); ?>
                        </div>
                    </div>
					<?php
					if ( $show_purchase_note && $purchase_note ) {
						?>
                        <div class="<?php echo $this->set( array(
							'order_details__purchase_note',
							'order_details__detail'
						) ) ?>">
							<?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?>
                        </div>
						<?php
					}
				}
				?>
            </div>
            <div class="<?php echo $this->set( array(
				'order_details__order_item_total'
			) ) ?>">
				<?php
				foreach ( $order->get_order_item_totals() as $key => $total ) {
					?>
                    <div class="<?php echo $this->set( array(
						'order_details__' . $key,
						'order_details__detail',
					) ) ?>">
                        <div class="<?php echo $this->set( array(
							'order_details-title'
						) ) ?>">
                            <div><?php echo str_replace( ':', '', $total['label'] ); ?></div>
                        </div>
                        <div class="<?php echo $this->set( array(
							'order_details-value'
						) ) ?>">
							<?php
							if ( $key == 'order_total' ) {
								?>
                                <div><?php echo get_woocommerce_currency(); ?></div>
								<?php
							}
							?>
                            <div><?php echo $total['value']; ?></div>
                        </div>
                    </div>
					<?php
				}
				?>
            </div>
			<?php
			if ( $order->get_customer_note() ) {
				?>
                <div class="<?php echo $this->set( array(
					'order_details__detail'
				) ) ?>">
                    <div class="<?php echo $this->set( array(
						'order_details-value'
					) ) ?>">
                        <div><?php echo wp_kses_post(apply_filters('wtypc_get_customer_note',__('Note', 'woocommerce-thank-you-page-customizer'))); ?></div>
                    </div>
                    <div class="<?php echo $this->set( array(
						'order_details-value'
					) ) ?>">
                        <div><?php echo wptexturize( $order->get_customer_note() ); ?></div>
                    </div>
                </div>
				<?php
			}
			?>
        </div>
		<?php
		return ob_get_clean();
	}

	private function order_confirmation_html( $order, $order_confirmation ) {
		$order_confirmation_header             = $order_confirmation['order_confirmation_header'] ?? '';
		$order_confirmation_order_number_title = $order_confirmation['order_confirmation_order_number_title'] ?? '';
		$order_confirmation_date_title         = $order_confirmation['order_confirmation_date_title'] ?? '';
		$order_confirmation_order_total_title  = $order_confirmation['order_confirmation_order_total_title'] ?? '';
		$order_confirmation_email_title        = $order_confirmation['order_confirmation_email_title'] ?? '';
		$order_confirmation_payment_title      = $order_confirmation['order_confirmation_payment_title'] ?? '';
		ob_start();
		?>
        <div class="<?php echo $this->set( array( 'order_confirmation__container', 'item__container' ) ) ?>"
             id="<?php echo $this->set( 'order_confirmation__container' ) ?>">

                <span class="<?php echo $this->set( 'edit-item-shortcut' ) ?> wtyp_icons-edit"
                      data-edit_section="order_confirmation"><?php echo __( 'Edit', 'woocommerce-thank-you-page-customizer' ) ?></span>

            <div class="<?php echo $this->set( array(
				'order_confirmation__header',
				'order_confirmation__detail'
			) ) ?>">
                <div class="<?php echo $this->set( array(
					'order_confirmation-header'
				) ) ?>">
                    <div><?php echo trim( strtolower( $order_confirmation_header ) ) === 'order confirmation' ? __( 'Order confirmation', 'woocommerce-thank-you-page-customizer' ) : wp_kses_post( nl2br( $order_confirmation_header ) ); ?></div>
                </div>
            </div>
            <div class="<?php echo $this->set( array(
				'order_confirmation__order_number',
				'order_confirmation__detail'
			) ) ?>">
                <div class="<?php echo $this->set( array(
					'order_confirmation__order_number-title',
					'order_confirmation-title'
				) ) ?>">
                    <div><?php echo wp_kses_post( nl2br( $order_confirmation_order_number_title ) ); ?></div>
                </div>
                <div class="<?php echo $this->set( array(
					'order_confirmation__order_number-value',
					'order_confirmation-value'
				) ) ?>">
                    <div>       <?php echo $order->get_order_number(); ?></div>
                </div>
            </div>

            <div class="<?php echo $this->set( array(
				'order_confirmation__order_date',
				'order_confirmation__detail'
			) ) ?>">
                <div class="<?php echo $this->set( array(
					'order_confirmation__order_date-title',
					'order_confirmation-title'
				) ) ?>">
                    <div><?php echo wp_kses_post( nl2br( $order_confirmation_date_title ) ); ?></div>
                </div>
                <div class="<?php echo $this->set( array(
					'order_confirmation__order_date-value',
					'order_confirmation-value'
				) ) ?>">
                    <div>       <?php echo wc_format_datetime( $order->get_date_created() ); ?></div>
                </div>
            </div>
            <div class="<?php echo $this->set( array(
				'order_confirmation__order_total',
				'order_confirmation__detail'
			) ) ?>">
                <div class="<?php echo $this->set( array(
					'order_confirmation__order_total-title',
					'order_confirmation-title'
				) ) ?>">
                    <div><?php echo wp_kses_post( nl2br( $order_confirmation_order_total_title ) ); ?></div>
                </div>
                <div class="<?php echo $this->set( array(
					'order_confirmation__order_total-value',
					'order_confirmation-value'
				) ) ?>">
                    <div>       <?php echo $order->get_formatted_order_total(); ?></div>
                </div>
            </div>
			<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) { ?>
                <div class="<?php echo $this->set( array(
					'order_confirmation__order_email',
					'order_confirmation__detail'
				) ) ?>">
                    <div class="<?php echo $this->set( array(
						'order_confirmation__order_email-title',
						'order_confirmation-title'
					) ) ?>">
                        <div><?php echo wp_kses_post( nl2br( $order_confirmation_email_title ) ); ?></div>
                    </div>
                    <div class="<?php echo $this->set( array(
						'order_confirmation__order_email-value',
						'order_confirmation-value'
					) ) ?>">
                        <div title="<?php esc_attr_e( $order->get_billing_email() ); ?>"><?php echo $order->get_billing_email(); ?></div>
                    </div>
                </div>
			<?php } ?>
			<?php if ( $order->get_payment_method_title() ) { ?>
                <div class="<?php echo $this->set( array(
					'order_confirmation__order_payment',
					'order_confirmation__detail'
				) ) ?>">
                    <div class="<?php echo $this->set( array(
						'order_confirmation__order_payment-title',
						'order_confirmation-title'
					) ) ?>">
                        <div><?php echo wp_kses_post( nl2br( $order_confirmation_payment_title ) ); ?></div>
                    </div>
                    <div class="<?php echo $this->set( array(
						'order_confirmation__order_payment-value',
						'order_confirmation-value'
					) ) ?>">
                        <div><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></div>
                    </div>
                </div>
			<?php } ?>
        </div>
		<?php
		return ob_get_clean();
	}

}