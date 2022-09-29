<?php
/*
Class Name: VI_WOOCOMMERCE_THANK_YOU_PAGE_Admin_Settings
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2018 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOOCOMMERCE_THANK_YOU_PAGE_Admin_Settings {
	protected $settings,$prefix;
	protected $default_language, $languages, $languages_data;

	public function __construct() {
		$this->settings = new VI_WOOCOMMERCE_THANK_YOU_PAGE_DATA();
		$this->prefix   = 'woocommerce-thank-you-page-';
		$this->languages        = array();
		$this->languages_data   = array();
		$this->default_language = '';
		add_filter(
			'plugin_action_links_woocommerce-thank-you-page-customizer/woocommerce-thank-you-page-customizer.php', array(
				$this,
				'settings_link'
			)
		);
		add_action( 'admin_menu', array( $this, 'create_options_page' ), 998 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_script' ) );
		add_action( 'admin_init', array( $this, 'save_data' ), 99 );
		add_action( 'admin_init', array( $this, 'check_update' ), 100 );
		/*ajax search*/
		add_action( 'wp_ajax_wtyp_search_coupon', array( $this, 'search_coupon' ) );
		add_action( 'wp_ajax_wtyp_search_product', array( $this, 'search_product' ) );
		add_action( 'wp_ajax_wtyp_search_product_parent', array( $this, 'search_product_parent' ) );
		add_action( 'wp_ajax_wtyp_search_cate', array( $this, 'search_cate' ) );
		/*preview email*/
		add_action( 'media_buttons', array( $this, 'preview_emails_button' ) );
		add_action( 'wp_ajax_wtypc_preview_emails', array( $this, 'preview_emails_ajax' ) );
		add_action( 'admin_footer', array( $this, 'preview_emails_html' ) );
	}

	function preview_emails_html() {
		global $pagenow;
		if ( $pagenow == 'admin.php' && isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'woocommerce_thank_you_page_customizer' ) {
			?>
            <div class="preview-emails-html-container preview-html-hidden">
                <div class="preview-emails-html-overlay"></div>
                <div class="preview-emails-html"></div>
            </div>
			<?php
		}
	}

	public function preview_emails_button( $editor_id ) {
		global $pagenow;
		if ( $pagenow == 'admin.php' && isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'woocommerce_thank_you_page_customizer' ) {
			$editor_ids = array( 'coupon_email_content' );
			if ( count( $this->languages ) ) {
				foreach ( $this->languages as $key => $value ) {
					$editor_ids[] = 'coupon_email_content_' . $value;
				}
			}
			if ( in_array( $editor_id, $editor_ids ) ) {
				?>
                <span class="<?php echo $this->set( 'available-shortcodes-shortcut' ) ?>"><?php esc_html_e( 'Shortcodes', 'woocommerce-thank-you-page-customizer' ) ?></span>

                <span class="<?php echo $this->set( 'preview-emails-button' ) ?> button"
                      data-wtypc_language="<?php echo str_replace( 'coupon_email_content', '', $editor_id ) ?>"><?php esc_html_e( 'Preview emails', 'woocommerce-thank-you-page-customizer' ) ?></span>
				<?php
			}
		}
	}

	public function preview_emails_ajax() {
		$shortcodes          = array(
			'order_number'   => date('Y'),
			'order_status'   => 'processing',
			'order_date'     => date_i18n( 'F d, Y', strtotime( 'today' ) ),
			'order_total'    => 999,
			'order_subtotal' => 990,
			'items_count'    => 3,
			'payment_method' => 'Cash on delivery',

			'shipping_method'            => 'Free shipping',
			'shipping_address'           => 'Thainguyen City',
			'formatted_shipping_address' => 'Thainguyen City, Vietnam',

			'billing_address'           => 'Thainguyen City',
			'formatted_billing_address' => 'Thainguyen City, Vietnam',
			'billing_country'           => 'VN',
			'billing_city'              => 'Thainguyen',

			'billing_first_name'          => 'John',
			'billing_last_name'           => 'Doe',
			'formatted_billing_full_name' => 'John Doe',
			'billing_email'               => 'support@villatheme.com',

			'shop_title' => get_bloginfo(),
			'home_url'   => home_url(),
			'shop_url'   => get_option( 'woocommerce_shop_page_id', '' ) ? get_page_link( get_option( 'woocommerce_shop_page_id' ) ) : '',

		);
		$content             = isset( $_GET['content'] ) ? wp_kses_post( stripslashes( $_GET['content'] ) ) : '';
		$heading             = isset( $_GET['heading'] ) ? ( stripslashes( $_GET['heading'] ) ) : '';
		$coupon_amount       = '10%';
		$coupon_code         = 'HAPPY';
		$coupon_date_expires = date_i18n( 'F d, Y', strtotime( '+30 days' ) );
		$last_valid_date     = date_i18n( 'F d, Y', strtotime( '+31 days' ) );
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
		$heading             = str_replace( array(
			'{coupon_code}',
			'{coupon_date_expires}',
			'{last_valid_date}',
			'{coupon_amount}'
		), array( $coupon_code, $coupon_date_expires, $last_valid_date, $coupon_amount ), $heading );
		if ( is_array( $shortcodes ) && count( $shortcodes ) ) {
			foreach ( $shortcodes as $key => $value ) {
				$content = str_replace( '{' . $key . '}', $value, $content );
				$heading = str_replace( '{' . $key . '}', $value, $heading );
			}
		}

		// load the mailer class
		$mailer = WC()->mailer();

		// create a new email
		$email = new WC_Email();

		// wrap the content with the email template and then add styles
		$message = apply_filters( 'woocommerce_mail_content', $email->style_inline( $mailer->wrap_message( $heading, $content ) ) );

		// print the preview email
		$css = '.woo-thank-you-page-customizer-coupon-input{line-height:46px;display:block;text-align: center;font-size: 24px;width: 100%;height: 46px;vertical-align: middle;margin: 0;color:' . $this->settings->get_params( 'coupon_code_color' ) . ';background-color:' . $this->settings->get_params( 'coupon_code_bg_color' ) . ';border-width:' . $this->settings->get_params( 'coupon_code_border_width' ) . 'px;border-style:' . $this->settings->get_params( 'coupon_code_border_style' ) . ';border-color:' . $this->settings->get_params( 'coupon_code_border_color' ) . ';}';
		wp_send_json(
			array(
				'html' => $message,
				'css'  => $css
			)
		);
	}


	function settings_link( $links ) {
		$settings_link = '<a href="' . admin_url( 'admin.php' ) . '?page=woocommerce_thank_you_page_customizer" title="' . __( 'Settings', 'woocommerce-thank-you-page-customizer' ) . '">' . __( 'Settings', 'woocommerce-thank-you-page-customizer' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	public static function search_coupon( $x = '', $post_types = 'shop_coupon' ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		ob_start();
		$keyword = isset($_GET['keyword'])? sanitize_text_field($_GET['keyword']):'';
		if ( empty( $keyword ) ) {
			die();
		}
		$arg            = array(
			'post_status'    => 'publish',
			'post_type'      => $post_types,
			'posts_per_page' => 50,
			's'              => $keyword,
			'meta_query'     => array(
				'ralation' => 'AND',
				array(
					'key'     => 'wtypc_unique_coupon',
					'compare' => 'NOT EXISTS'
				),
				array(
					'key'     => 'wlwl_unique_coupon',
					'compare' => 'NOT EXISTS'
				),
				array(
					'key'     => 'kt_unique_coupon',
					'compare' => 'NOT EXISTS'
				),
			)
		);
		$the_query      = new WP_Query( $arg );
		$found_products = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$coupon = new WC_Coupon( get_the_ID() );
				if ( $coupon->get_usage_limit() > 0 && $coupon->get_usage_count() >= $coupon->get_usage_limit() ) {
					continue;
				}

				if ( $coupon->get_date_expires() && current_time( 'timestamp', true ) > $coupon->get_date_expires()->getTimestamp() ) {
					continue;
				}
				$existing_coupon_discount_type = $coupon->get_discount_type();
				$existing_coupon_amount        = $coupon->get_amount();
				$product                       = array(
					'id'          => get_the_ID(),
					'text'        => get_the_title(),
					'coupon_data' => array(
						'coupon_amount'        => $existing_coupon_amount,
						'coupon_discount_type' => $existing_coupon_discount_type,
					)
				);
				$found_products[]              = $product;
			}
		}
		wp_reset_postdata();
		wp_send_json( $found_products );
		die;
	}

	public function search_cate() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		ob_start();

		$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );
		if ( ! $keyword ) {
			$keyword = filter_input( INPUT_POST, 'keyword', FILTER_SANITIZE_STRING );
		}
		if ( empty( $keyword ) ) {
			die();
		}
		$categories = get_terms(
			array(
				'taxonomy' => 'product_cat',
				'orderby'  => 'name',
				'order'    => 'ASC',
				'search'   => $keyword,
				'number'   => 100
			)
		);
		$items      = array();
		if ( count( $categories ) ) {
			foreach ( $categories as $category ) {
				$item    = array(
					'id'   => $category->term_id,
					'text' => $category->name
				);
				$items[] = $item;
			}
		}
		wp_send_json( $items );
		die;
	}

	public function search_product( $x = '', $post_types = array( 'product' ) ) {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		ob_start();

		$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );

		if ( empty( $keyword ) ) {
			die();
		}
		$arg            = array(
			'post_status'    => 'publish',
			'post_type'      => $post_types,
			'posts_per_page' => 50,
			's'              => $keyword

		);
		$the_query      = new WP_Query( $arg );
		$found_products = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$product_id    = get_the_ID();
				$product_title = get_the_title();
				$the_product   = wc_get_product( $product_id );
				if ( $the_product->get_sku() ) {
					$product_title .= ' (' . $the_product->get_sku() . ')';
				}
				$product          = array( 'id' => $product_id, 'text' => $product_title );
				$found_products[] = $product;


				if ( $the_product->has_child() && $the_product->is_type( 'variable' ) ) {
					$product_children = $the_product->get_children();
					if ( count( $product_children ) ) {
						foreach ( $product_children as $product_child ) {

							$child_wc = wc_get_product( $product_child );
							if ( woocommerce_version_check() ) {
								$product_title_child = get_the_title( $product_child );
								if ( $child_wc->get_sku() ) {
									$product_title_child .= '(' . $child_wc->get_sku() . ')';
								}
								$product = array(
									'id'   => $product_child,
									'text' => $product_title_child
								);

							} else {
								$get_atts            = $child_wc->get_variation_attributes();
								$attr_name           = array_values( $get_atts )[0];
								$product_title_child = get_the_title() . ' - ' . $attr_name;
								if ( $child_wc->get_sku() ) {
									$product_title_child .= '(' . $child_wc->get_sku() . ')';
								}
								$product = array(
									'id'   => $product_child,
									'text' => $product_title_child
								);
							}
							$found_products[] = $product;
						}

					}
				}


			}
		}
		wp_reset_postdata();
		wp_send_json( $found_products );
		die;
	}

	public function search_product_parent( $x = '', $post_types = array( 'product' ) ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		ob_start();

		$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );
		if ( ! $keyword ) {
			$keyword = filter_input( INPUT_POST, 'keyword', FILTER_SANITIZE_STRING );
		}
		if ( empty( $keyword ) ) {
			die();
		}
		$arg            = array(
			'post_status'    => 'publish',
			'post_type'      => $post_types,
			'posts_per_page' => 50,
			's'              => $keyword

		);
		$the_query      = new WP_Query( $arg );
		$found_products = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$product_id    = get_the_ID();
				$product_title = get_the_title() . ' (' . $product_id . ')';
				$the_product   = wc_get_product( $product_id );
				if ( $the_product->is_type( 'variation' ) ) {
					continue;
				}

				$product          = array( 'id' => $product_id, 'text' => $product_title );
				$found_products[] = $product;
			}
		}
		wp_reset_postdata();
		wp_send_json( $found_products );
		die;
	}


	public function create_options_page() {
		add_menu_page( 'WooCommerce Thank You Page Customizer Premium', 'Thank You Page', 'manage_options', 'woocommerce_thank_you_page_customizer', array(
			$this,
			'settings_callback'
		), VI_WOOCOMMERCE_THANK_YOU_PAGE_IMAGES . 'thank-you.png', 2 );
	}

	public function settings_callback() {
		$this->settings = new VI_WOOCOMMERCE_THANK_YOU_PAGE_DATA();
		$is_rtl = is_rtl();
		?>
        <div class="wrap">
            <h2><?php echo esc_html__( 'WooCommerce Thank You Page Customizer Premium', 'woocommerce-thank-you-page-customizer' ); ?></h2>

            <div class="vi-ui raised">
                <form class="vi-ui form" method="post" action="">
					<?php
					wp_nonce_field( 'woo_thank_you_page_action_nonce', '_woo_thank_you_page_nonce' );
					settings_fields( 'woocommerce-thank-you-page-customizer' );
					do_settings_sections( 'woocommerce-thank-you-page-customizer' );
					?>
                    <div class="vi-ui vi-ui-main top attached tabular menu">
                        <a class="item active"
                           data-tab="general"><?php esc_html_e( 'General', 'woocommerce-thank-you-page-customizer' ) ?></a>
                        <a class="item"
                           data-tab="coupon"><?php esc_html_e( 'Coupon', 'woocommerce-thank-you-page-customizer' ) ?></a>
                        <a class="item"
                           data-tab="email"><?php esc_html_e( 'Email', 'woocommerce-thank-you-page-customizer' ) ?></a>
                        <a class="item"
                           data-tab="woo_order_email"><?php esc_html_e( 'WooCommerce email', 'woocommerce-thank-you-page-customizer' ) ?></a>
                        <a class="item"
                           data-tab="update"><?php esc_html_e( 'Update', 'woocommerce-thank-you-page-customizer' ) ?></a>
                    </div>
                    <div class="vi-ui bottom attached tab segment active" data-tab="general">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">
                                    <label for="enable"><?php esc_html_e( 'Enable', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="enable"
                                               id="enable" <?php checked( $this->settings->get_params( 'enable' ), 1 ); ?>
                                               value="1">
                                        <label for="enable"><?php esc_html_e( 'Enable', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="my_account_coupon_enable"><?php esc_html_e( 'Show coupon gift', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="my_account_coupon_enable"
                                               id="my_account_coupon_enable" <?php checked( $this->settings->get_params( 'my_account_coupon_enable' ), 1 ); ?>
                                               value="1">
                                        <label for="my_account_coupon_enable"></label>
                                    </div>
                                    <p class="description">
										<?php esc_html_e( 'Show coupon gift on the My Account > Orders page', 'woo-thank-you-page-customizer' ) ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="order_status"><?php esc_html_e( 'Order status', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <select name="order_status[]" id="order_status"
                                            class="vi-ui fluid dropdown selection" multiple="">
										<?php
										$order_status = $this->settings->get_params( 'order_status' );
										$statuses     = wc_get_order_statuses();
										foreach ( $statuses as $k => $status ) {
											$selected = '';
											if ( in_array( $k, $order_status ) ) {
												$selected = 'selected="selected"';
											}
											?>
                                            <option <?php echo $selected; ?>
                                                    value="<?php echo esc_attr( $k ) ?>"><?php echo esc_html( $status ) ?></option>
											<?php
										}
										?>
                                    </select>
                                    <p class="description"><?php esc_html_e( 'Usually, order status will be set to "Processing" after checking out and customers will be lead to a thank you page but it could be different for some payments that you are using.', 'woocommerce-thank-you-page-customizer' ) ?></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="google-map-api">
										<?php esc_html_e( 'Google map API key', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" name="google_map_api" id="google-map-api"
                                           value="<?php echo htmlentities( $this->settings->get_params( 'google_map_api' ) ); ?>">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="bing-map-api">
										<?php esc_html_e( 'Bing map API key', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" name="bing_map_api" id="bing-map-api"
                                           value="<?php echo htmlentities( $this->settings->get_params( 'bing_map_api' ) ); ?>">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label><?php esc_html_e( 'Design', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
									<?php
									$url = admin_url( 'customize.php' ) . '?autofocus[section]=woo_thank_you_page_design_general';
									if ( $this->settings->get_params( 'select_order' ) ) {
										$order = wc_get_order( $this->settings->get_params( 'select_order' ) );
										if ( $order ) {
											$url = admin_url( 'customize.php' ) . '?url=' . urlencode( $order->get_checkout_order_received_url() ) . '&autofocus[section]=woo_thank_you_page_design_general';
										}
									}
									?>
                                    <a target="_blank"
                                       href="<?php echo $url ?>"><?php esc_html_e( 'Go to design', 'woocommerce-thank-you-page-customizer' ) ?></a>
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div class="vi-ui bottom attached tab segment" data-tab="coupon">
                        <table class="form-table wtyp-coupon-table">
                            <tbody>
                            <tr>
                                <th>
                                    <label for="coupon_limit_per_day"><?php esc_html_e( 'Limit coupons per day', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <input id="coupon_limit_per_day" name="coupon_limit_per_day" type="number" min="0"
                                           step="1"
                                           value="<?php echo $this->settings->get_params( 'coupon_limit_per_day' ) ?>">
									<?php esc_html_e( 'The maximum number of coupon given to each customer per day(based on email if not logged-in). Set to 0 to not limit this.', 'woocommerce-thank-you-page-customizer' ) ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="coupon_limit_per_week"><?php esc_html_e( 'Limit coupons per week', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <input id="coupon_limit_per_week" name="coupon_limit_per_week" type="number" min="0"
                                           step="1"
                                           value="<?php echo $this->settings->get_params( 'coupon_limit_per_week' ) ?>">
									<?php esc_html_e( 'The maximum number of coupon given to each customer per week(based on email if not logged-in). Set to 0 to not limit this.', 'woocommerce-thank-you-page-customizer' ) ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="coupon_limit_per_month"><?php esc_html_e( 'Limit coupons per month', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <input id="coupon_limit_per_month" name="coupon_limit_per_month" type="number"
                                           min="0" step="1"
                                           value="<?php echo $this->settings->get_params( 'coupon_limit_per_month' ) ?>">
									<?php esc_html_e( 'The maximum number of coupon given to each customer per month(based on email if not logged-in). Set to 0 to not limit this.', 'woocommerce-thank-you-page-customizer' ) ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="coupon_limit_per_year"><?php esc_html_e( 'Limit coupons per year', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <input id="coupon_limit_per_year" name="coupon_limit_per_year" type="number" min="0"
                                           step="1"
                                           value="<?php echo $this->settings->get_params( 'coupon_limit_per_year' ) ?>">
									<?php esc_html_e( 'The maximum number of coupon given to each customer per year(based on email if not logged-in). Set to 0 to not limit this.', 'woocommerce-thank-you-page-customizer' ) ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="form-table wtyp-coupon-table">
                            <tbody>
                            <tr class="wtyp-coupon-header">
                                <th><?php esc_html_e( 'Coupon type', 'woocommerce-thank-you-page-customizer' ) ?></th>
                                <th><?php esc_html_e( 'Discount type', 'woocommerce-thank-you-page-customizer' ) ?></th>
                                <th><?php esc_html_e( 'Coupon amount', 'woocommerce-thank-you-page-customizer' ) ?></th>
                                <th><?php esc_html_e( 'Min order total', 'woocommerce-thank-you-page-customizer' ) ?></th>
                                <th><?php esc_html_e( 'Max order total', 'woocommerce-thank-you-page-customizer' ) ?></th>
                                <th><?php esc_html_e( 'Actions', 'woocommerce-thank-you-page-customizer' ) ?></th>
                            </tr>
                            </tbody>
                            <tbody class="ui-sortable">
							<?php
							$coupon_type = $this->settings->get_params( 'coupon_type' );
							if ( is_array( $coupon_type ) && count( $coupon_type ) ) {
								foreach ( $coupon_type as $key => $value ) {
									if ( $value == 'unique' ) {
										?>
                                        <tr class="wtyp-coupon-content">
                                            <td>
                                                <select class="vi-ui fluid dropdown coupon-select disabled"
                                                        name="coupon_type[]">
                                                    <option value="unique" <?php selected( $this->settings->get_params( 'coupon_type' )[ $key ], 'unique' ) ?>><?php esc_html_e( 'Unique coupon', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                                    <option value="existing" <?php selected( $this->settings->get_params( 'coupon_type' )[ $key ], 'existing' ) ?>><?php esc_html_e( 'Existing coupon', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                                </select>
                                            </td>

                                            <td>
                                                <select class="vi-ui fluid dropdown"
                                                        name="coupon_unique_discount_type[]">
                                                    <option value="percent" <?php selected( $this->settings->get_params( 'coupon_unique_discount_type' )[ $key ], 'percent' ) ?>><?php esc_html_e( 'Percentage discount', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                                    <option value="fixed_cart" <?php selected( $this->settings->get_params( 'coupon_unique_discount_type' )[ $key ], 'fixed_cart' ) ?>><?php esc_html_e( 'Fixed cart discount', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                                    <option value="fixed_product" <?php selected( $this->settings->get_params( 'coupon_unique_discount_type' )[ $key ], 'fixed_product' ) ?>><?php esc_html_e( 'Fixed product discount', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                                </select>
                                            </td>

                                            <td>
                                                <input type="number" name="coupon_unique_amount[]"
                                                       min="0"
                                                       value="<?php echo $this->settings->get_params( 'coupon_unique_amount' )[ $key ] ?>">
                                            </td>
                                            <td>
                                                <input type="number" name="coupon_rule_min_total[]"
                                                       min="0"
                                                       value="<?php echo $this->settings->get_params( 'coupon_rule_min_total' )[ $key ] ?>">
                                            </td>
                                            <td>
                                                <input type="number" name="coupon_rule_max_total[]"
                                                       min="0"
                                                       value="<?php echo $this->settings->get_params( 'coupon_rule_max_total' )[ $key ] ?>">
                                            </td>
                                            <td class="wtyp-coupon-settings-actions">
                                                <div class="wtyp-hidden-item">
                                                    <input type="hidden" name="existing_coupon[]"
                                                           value="<?php echo $this->settings->get_params( 'existing_coupon' )[ $key ] ?>"
                                                           data-coupon_title="">
                                                    <input type="hidden" name="coupon_unique_prefix[]"
                                                           value="<?php echo htmlentities( $this->settings->get_params( 'coupon_unique_prefix' )[ $key ] ); ?>">
                                                    <input type="hidden" name="coupon_unique_date_expires[]" min="0"
                                                           value="<?php echo $this->settings->get_params( 'coupon_unique_date_expires' )[ $key ] ?>">
                                                    <input type="hidden"
                                                           name="coupon_unique_email_restrictions[]"
                                                           value="<?php echo $this->settings->get_params( 'coupon_unique_email_restrictions' )[ $key ] ?>">
                                                    <input type="hidden"
                                                           name="coupon_unique_free_shipping[]"
                                                           value="<?php echo $this->settings->get_params( 'coupon_unique_free_shipping' )[ $key ] ?>">

                                                    <input type="hidden" name="coupon_unique_minimum_amount[]" min="0"
                                                           value="<?php echo $this->settings->get_params( 'coupon_unique_minimum_amount' )[ $key ] ?>">
                                                    <input type="hidden" name="coupon_unique_maximum_amount[]" min="0"
                                                           value="<?php echo $this->settings->get_params( 'coupon_unique_maximum_amount' )[ $key ] ?>">
                                                    <input type="hidden"
                                                           name="coupon_unique_individual_use[]"
                                                           value="<?php echo $this->settings->get_params( 'coupon_unique_individual_use' )[ $key ] ?>">
                                                    <input type="hidden"
                                                           name="coupon_unique_exclude_sale_items[]"
                                                           value="<?php echo $this->settings->get_params( 'coupon_unique_exclude_sale_items' )[ $key ] ?>">
													<?php
													$coupon_unique_product_ids       = $this->settings->get_params( 'coupon_unique_product_ids' )[ $key ];
													$coupon_unique_product_ids_title = array();
													if ( is_array( $coupon_unique_product_ids ) && count( $coupon_unique_product_ids ) ) {
														foreach ( $coupon_unique_product_ids as $k => $v ) {
															$product = wc_get_product( $v );
															if ( $product ) {
																$title       = $product->get_title();
																$product_sku = $product->get_sku();
																if ( $product_sku ) {
																	$title .= '(' . $product_sku . ')';
																}
																if ( $product->is_type( 'variation' ) ) {
																	if ( woocommerce_version_check() ) {
																		$title = get_the_title( $v );
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
																$coupon_unique_product_ids_title[] = $title;
															}
														}
													}
													?>
                                                    <input type="hidden" name="coupon_unique_product_ids[]"
                                                           value="<?php echo htmlentities( json_encode( $coupon_unique_product_ids ) ) ?>"
                                                           data-product_title="<?php echo htmlentities( json_encode( $coupon_unique_product_ids_title ) ) ?>">
													<?php
													$coupon_unique_excluded_product_ids       = $this->settings->get_params( 'coupon_unique_excluded_product_ids' )[ $key ];
													$coupon_unique_excluded_product_ids_title = array();
													if ( is_array( $coupon_unique_excluded_product_ids ) && count( $coupon_unique_excluded_product_ids ) ) {
														foreach ( $coupon_unique_excluded_product_ids as $k => $v ) {
															$product = wc_get_product( $v );
															if ( $product ) {
																$title       = $product->get_title();
																$product_sku = $product->get_sku();
																if ( $product_sku ) {
																	$title .= '(' . $product_sku . ')';
																}
																if ( $product->is_type( 'variation' ) ) {
																	if ( woocommerce_version_check() ) {
																		$title = get_the_title( $v );
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
																$coupon_unique_excluded_product_ids_title[] = $title;
															}
														}
													}
													?>
                                                    <input type="hidden" name="coupon_unique_excluded_product_ids[]"
                                                           value="<?php echo htmlentities( json_encode( $coupon_unique_excluded_product_ids ) ) ?>"
                                                           data-product_title="<?php echo htmlentities( json_encode( $coupon_unique_excluded_product_ids_title ) ) ?>">
													<?php
													$coupon_unique_product_categories       = $this->settings->get_params( 'coupon_unique_product_categories' )[ $key ];
													$coupon_unique_product_categories_title = array();
													if ( is_array( $coupon_unique_product_categories ) && count( $coupon_unique_product_categories ) ) {
														foreach ( $coupon_unique_product_categories as $k => $v ) {
															$category = get_term( $v );
															if ( $category ) {
																$coupon_unique_product_categories_title[] = $category->name;
															}
														}
													}
													?>
                                                    <input type="hidden" name="coupon_unique_product_categories[]"
                                                           value="<?php echo htmlentities( json_encode( $coupon_unique_product_categories ) ) ?>"
                                                           data-product_title="<?php echo htmlentities( json_encode( $coupon_unique_product_categories_title ) ) ?>">
													<?php
													$coupon_unique_excluded_product_categories       = $this->settings->get_params( 'coupon_unique_excluded_product_categories' )[ $key ];
													$coupon_unique_excluded_product_categories_title = array();
													if ( is_array( $coupon_unique_excluded_product_categories ) && count( $coupon_unique_excluded_product_categories ) ) {
														foreach ( $coupon_unique_excluded_product_categories as $k => $v ) {
															$category = get_term( $v );
															if ( $category ) {
																$coupon_unique_excluded_product_categories_title[] = $category->name;
															}
														}
													}
													?>
                                                    <input type="hidden"
                                                           name="coupon_unique_excluded_product_categories[]"
                                                           value="<?php echo htmlentities( json_encode( $coupon_unique_excluded_product_categories ) ) ?>"
                                                           data-product_title="<?php echo htmlentities( json_encode( $coupon_unique_excluded_product_categories_title ) ) ?>">
                                                    <input type="hidden" name="coupon_unique_usage_limit[]" min="0"
                                                           value="<?php echo $this->settings->get_params( 'coupon_unique_usage_limit' )[ $key ] ?>">
                                                    <input type="hidden" name="coupon_unique_limit_usage_to_x_items[]"
                                                           min="0"
                                                           value="<?php echo $this->settings->get_params( 'coupon_unique_limit_usage_to_x_items' )[ $key ] ?>">
                                                    <input type="hidden" name="coupon_unique_usage_limit_per_user[]"
                                                           min="0"
                                                           value="<?php echo $this->settings->get_params( 'coupon_unique_usage_limit_per_user' )[ $key ] ?>">

													<?php
													$coupon_rule_product_ids       = $this->settings->get_params( 'coupon_rule_product_ids' )[ $key ];
													$coupon_rule_product_ids_title = array();
													if ( is_array( $coupon_rule_product_ids ) && count( $coupon_rule_product_ids ) ) {
														foreach ( $coupon_rule_product_ids as $k => $v ) {
															$product = wc_get_product( $v );
															if ( $product ) {
																$title       = $product->get_title();
																$product_sku = $product->get_sku();
																if ( $product_sku ) {
																	$title .= '(' . $product_sku . ')';
																}
																if ( $product->is_type( 'variation' ) ) {
																	if ( woocommerce_version_check() ) {
																		$title = get_the_title( $v );
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
																$coupon_rule_product_ids_title[] = $title;
															}
														}
													}
													?>
                                                    <input type="hidden" name="coupon_rule_product_ids[]"
                                                           value="<?php echo htmlentities( json_encode( $coupon_rule_product_ids ) ) ?>"
                                                           data-product_title="<?php echo htmlentities( json_encode( $coupon_rule_product_ids_title ) ) ?>">
													<?php
													$coupon_rule_excluded_product_ids       = $this->settings->get_params( 'coupon_rule_excluded_product_ids' )[ $key ];
													$coupon_rule_excluded_product_ids_title = array();
													if ( is_array( $coupon_rule_excluded_product_ids ) && count( $coupon_rule_excluded_product_ids ) ) {
														foreach ( $coupon_rule_excluded_product_ids as $k => $v ) {
															$product = wc_get_product( $v );
															if ( $product ) {
																$title       = $product->get_title();
																$product_sku = $product->get_sku();
																if ( $product_sku ) {
																	$title .= '(' . $product_sku . ')';
																}
																if ( $product->is_type( 'variation' ) ) {
																	if ( woocommerce_version_check() ) {
																		$title = get_the_title( $v );
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
																$coupon_rule_excluded_product_ids_title[] = $title;
															}
														}
													}
													?>
                                                    <input type="hidden" name="coupon_rule_excluded_product_ids[]"
                                                           value="<?php echo htmlentities( json_encode( $coupon_rule_excluded_product_ids ) ) ?>"
                                                           data-product_title="<?php echo htmlentities( json_encode( $coupon_rule_excluded_product_ids_title ) ) ?>">
													<?php
													$coupon_rule_product_categories       = $this->settings->get_params( 'coupon_rule_product_categories' )[ $key ];
													$coupon_rule_product_categories_title = array();
													if ( is_array( $coupon_rule_product_categories ) && count( $coupon_rule_product_categories ) ) {
														foreach ( $coupon_rule_product_categories as $k => $v ) {
															$category = get_term( $v );
															if ( $category ) {
																$coupon_rule_product_categories_title[] = $category->name;
															}
														}
													}
													?>
                                                    <input type="hidden" name="coupon_rule_product_categories[]"
                                                           value="<?php echo htmlentities( json_encode( $coupon_rule_product_categories ) ) ?>"
                                                           data-product_title="<?php echo htmlentities( json_encode( $coupon_rule_product_categories_title ) ) ?>">
													<?php
													$coupon_rule_excluded_product_categories       = $this->settings->get_params( 'coupon_rule_excluded_product_categories' )[ $key ];
													$coupon_rule_excluded_product_categories_title = array();
													if ( is_array( $coupon_rule_excluded_product_categories ) && count( $coupon_rule_excluded_product_categories ) ) {
														foreach ( $coupon_rule_excluded_product_categories as $k => $v ) {
															$category = get_term( $v );
															if ( $category ) {
																$coupon_rule_excluded_product_categories_title[] = $category->name;
															}
														}
													}
													?>
                                                    <input type="hidden"
                                                           name="coupon_rule_excluded_product_categories[]"
                                                           value="<?php echo htmlentities( json_encode( $coupon_rule_excluded_product_categories ) ) ?>"
                                                           data-product_title="<?php echo htmlentities( json_encode( $coupon_rule_excluded_product_categories_title ) ) ?>">
                                                </div>

                                                <span class="wtyp-coupon-settings-action-edit vi-ui button"><?php esc_html_e( 'Edit', 'woocommerce-thank-you-page-customizer' ) ?></span>
                                                <span class="wtyp-coupon-settings-action-clone vi-ui button positive"><?php esc_html_e( 'Clone', 'woocommerce-thank-you-page-customizer' ) ?></span>
                                                <span class="wtyp-coupon-settings-action-remove vi-ui button negative"><?php esc_html_e( 'Remove', 'woocommerce-thank-you-page-customizer' ) ?></span>
                                            </td>
                                        </tr>
										<?php
									} else {
										$coupon_id                                   = $this->settings->get_params( 'existing_coupon' )[ $key ];
										$coupon                                      = new WC_Coupon( $coupon_id );
										$coupon_code                                 = '';
										$existing_coupon_discount_type               = '';
										$existing_coupon_amount                      = '';
										$existing_coupon_date_expires                = '';
										$existing_coupon_individual_use              = '';
										$existing_coupon_product_ids                 = array();
										$existing_coupon_excluded_product_ids        = array();
										$existing_coupon_usage_limit                 = '';
										$existing_coupon_usage_limit_per_user        = '';
										$existing_coupon_limit_usage_to_x_items      = '';
										$existing_coupon_free_shipping               = '';
										$existing_coupon_product_categories          = array();
										$existing_coupon_excluded_product_categories = array();
										$existing_coupon_exclude_sale_items          = '';
										$existing_coupon_minimum_amount              = '';
										$existing_coupon_maximum_amount              = '';
										$existing_coupon_email_restrictions          = $this->settings->get_params( 'coupon_unique_email_restrictions' )[ $key ];
										$existing_coupon_prefix                      = '';
										if ( $coupon ) {
											$coupon_code                   = $coupon->get_code();
											$existing_coupon_discount_type = $coupon->get_discount_type();
											$existing_coupon_amount        = $coupon->get_amount();
											$date_expires                  = $coupon->get_date_expires();
											if ( $date_expires ) {
												$existing_coupon_date_expires = ( $date_expires->getTimestamp() - strtotime( date( 'Y-m-d' ), current_time( 'timestamp' ) ) ) / 86400 - 1;
											}
											$existing_coupon_individual_use              = $coupon->get_individual_use();
											$existing_coupon_product_ids                 = $coupon->get_product_ids();
											$existing_coupon_excluded_product_ids        = $coupon->get_excluded_product_ids();
											$existing_coupon_usage_limit                 = $coupon->get_usage_limit();
											$existing_coupon_usage_limit_per_user        = $coupon->get_usage_limit_per_user();
											$existing_coupon_limit_usage_to_x_items      = $coupon->get_limit_usage_to_x_items();
											$existing_coupon_free_shipping               = $coupon->get_free_shipping();
											$existing_coupon_product_categories          = $coupon->get_product_categories();
											$existing_coupon_excluded_product_categories = $coupon->get_excluded_product_categories();
											$existing_coupon_exclude_sale_items          = $coupon->get_exclude_sale_items();
											$existing_coupon_minimum_amount              = $coupon->get_minimum_amount();
											$existing_coupon_maximum_amount              = $coupon->get_maximum_amount();
											$existing_coupon_email_restrictions          = $this->settings->get_params( 'coupon_unique_email_restrictions' )[ $key ];
											$existing_coupon_prefix                      = '';
										}
										?>
                                        <tr class="wtyp-coupon-content">
                                            <td>
                                                <select class="vi-ui fluid dropdown coupon-select disabled"
                                                        name="coupon_type[]">
                                                    <option value="unique" <?php selected( $coupon_type[ $key ], 'unique' ) ?>><?php esc_html_e( 'Unique coupon', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                                    <option value="existing" <?php selected( $coupon_type[ $key ], 'existing' ) ?>><?php esc_html_e( 'Existing coupon', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                                </select>
                                            </td>

                                            <td>
                                                <select class="vi-ui fluid dropdown"
                                                        name="coupon_unique_discount_type[]">
                                                    <option value="percent" <?php selected( $existing_coupon_discount_type, 'percent' ) ?>><?php esc_html_e( 'Percentage discount', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                                    <option value="fixed_cart" <?php selected( $existing_coupon_discount_type, 'fixed_cart' ) ?>><?php esc_html_e( 'Fixed cart discount', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                                    <option value="fixed_product" <?php selected( $existing_coupon_discount_type, 'fixed_product' ) ?>><?php esc_html_e( 'Fixed product discount', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                                </select>
                                            </td>

                                            <td>
                                                <input type="number" name="coupon_unique_amount[]"
                                                       min="0"
                                                       value="<?php echo $existing_coupon_amount ?>">
                                            </td>
                                            <td>
                                                <input type="number" name="coupon_rule_min_total[]"
                                                       min="0"
                                                       value="<?php echo $this->settings->get_params( 'coupon_rule_min_total' )[ $key ] ?>">
                                            </td>
                                            <td>
                                                <input type="number" name="coupon_rule_max_total[]"
                                                       min="0"
                                                       value="<?php echo $this->settings->get_params( 'coupon_rule_max_total' )[ $key ] ?>">
                                            </td>
                                            <td class="wtyp-coupon-settings-actions">
                                                <div class="wtyp-hidden-item">
                                                    <input type="hidden" name="existing_coupon[]"
                                                           value="<?php echo $coupon_id ?>"
                                                           data-coupon_code="<?php echo $coupon_code ?>">
                                                    <input type="hidden" name="coupon_unique_prefix[]"
                                                           value="<?php echo htmlentities( $existing_coupon_prefix ); ?>">
                                                    <input type="hidden" name="coupon_unique_date_expires[]" min="0"
                                                           value="<?php echo $existing_coupon_date_expires ?>">
                                                    <input type="hidden"
                                                           name="coupon_unique_email_restrictions[]"
                                                           value="<?php echo $existing_coupon_email_restrictions ?>">
                                                    <input type="hidden"
                                                           name="coupon_unique_free_shipping[]"
                                                           value="<?php echo $existing_coupon_free_shipping ?>">

                                                    <input type="hidden" name="coupon_unique_minimum_amount[]" min="0"
                                                           value="<?php echo $existing_coupon_minimum_amount ?>">
                                                    <input type="hidden" name="coupon_unique_maximum_amount[]" min="0"
                                                           value="<?php echo $existing_coupon_maximum_amount ?>">
                                                    <input type="hidden"
                                                           name="coupon_unique_individual_use[]"
                                                           value="<?php echo $existing_coupon_individual_use ?>">
                                                    <input type="hidden"
                                                           name="coupon_unique_exclude_sale_items[]"
                                                           value="<?php echo $existing_coupon_exclude_sale_items ?>">
													<?php
													$existing_coupon_product_ids_title = array();
													if ( is_array( $existing_coupon_product_ids ) && count( $existing_coupon_product_ids ) ) {
														foreach ( $existing_coupon_product_ids as $k => $v ) {
															$product = wc_get_product( $v );
															if ( $product ) {
																$title       = $product->get_title();
																$product_sku = $product->get_sku();
																if ( $product_sku ) {
																	$title .= '(' . $product_sku . ')';
																}
																if ( $product->is_type( 'variation' ) ) {
																	if ( woocommerce_version_check() ) {
																		$title = get_the_title( $v );
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
																$existing_coupon_product_ids_title[] = $title;
															}
														}
													}
													?>
                                                    <input type="hidden" name="coupon_unique_product_ids[]"
                                                           value="<?php echo htmlentities( json_encode( $existing_coupon_product_ids ) ) ?>"
                                                           data-product_title="<?php echo htmlentities( json_encode( $existing_coupon_product_ids_title ) ) ?>">
													<?php
													$existing_coupon_excluded_product_ids_title = array();
													if ( is_array( $existing_coupon_excluded_product_ids ) && count( $existing_coupon_excluded_product_ids ) ) {
														foreach ( $existing_coupon_excluded_product_ids as $k => $v ) {
															$product = wc_get_product( $v );
															if ( $product ) {
																$title       = $product->get_title();
																$product_sku = $product->get_sku();
																if ( $product_sku ) {
																	$title .= '(' . $product_sku . ')';
																}
																if ( $product->is_type( 'variation' ) ) {
																	if ( woocommerce_version_check() ) {
																		$title = get_the_title( $v );
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
																$existing_coupon_excluded_product_ids_title[] = $title;
															}
														}
													}
													?>
                                                    <input type="hidden" name="coupon_unique_excluded_product_ids[]"
                                                           value="<?php echo htmlentities( json_encode( $existing_coupon_excluded_product_ids ) ) ?>"
                                                           data-product_title="<?php echo htmlentities( json_encode( $existing_coupon_excluded_product_ids_title ) ) ?>">
													<?php
													$existing_coupon_product_categories_title = array();
													if ( is_array( $existing_coupon_product_categories ) && count( $existing_coupon_product_categories ) ) {
														foreach ( $existing_coupon_product_categories as $k => $v ) {
															$category = get_term( $v );
															if ( $category ) {
																$existing_coupon_product_categories_title[] = $category->name;
															}
														}
													}
													?>
                                                    <input type="hidden" name="coupon_unique_product_categories[]"
                                                           value="<?php echo htmlentities( json_encode( $existing_coupon_product_categories ) ) ?>"
                                                           data-product_title="<?php echo htmlentities( json_encode( $existing_coupon_product_categories_title ) ) ?>">
													<?php
													$existing_coupon_excluded_product_categories_title = array();
													if ( is_array( $existing_coupon_excluded_product_categories ) && count( $existing_coupon_excluded_product_categories ) ) {
														foreach ( $existing_coupon_excluded_product_categories as $k => $v ) {
															$category = get_term( $v );
															if ( $category ) {
																$existing_coupon_excluded_product_categories_title[] = $category->name;
															}
														}
													}
													?>
                                                    <input type="hidden"
                                                           name="coupon_unique_excluded_product_categories[]"
                                                           value="<?php echo htmlentities( json_encode( $existing_coupon_excluded_product_categories ) ) ?>"
                                                           data-product_title="<?php echo htmlentities( json_encode( $existing_coupon_excluded_product_categories_title ) ) ?>">
                                                    <input type="hidden" name="coupon_unique_usage_limit[]" min="0"
                                                           value="<?php echo $existing_coupon_usage_limit ?>">
                                                    <input type="hidden" name="coupon_unique_limit_usage_to_x_items[]"
                                                           min="0"
                                                           value="<?php echo $existing_coupon_limit_usage_to_x_items ?>">
                                                    <input type="hidden" name="coupon_unique_usage_limit_per_user[]"
                                                           min="0"
                                                           value="<?php echo $existing_coupon_usage_limit_per_user ?>">
													<?php
													$coupon_rule_product_ids       = $this->settings->get_params( 'coupon_rule_product_ids' )[ $key ];
													$coupon_rule_product_ids_title = array();
													if ( is_array( $coupon_rule_product_ids ) && count( $coupon_rule_product_ids ) ) {
														foreach ( $coupon_rule_product_ids as $k => $v ) {
															$product = wc_get_product( $v );
															if ( $product ) {
																$title       = $product->get_title();
																$product_sku = $product->get_sku();
																if ( $product_sku ) {
																	$title .= '(' . $product_sku . ')';
																}
																if ( $product->is_type( 'variation' ) ) {
																	if ( woocommerce_version_check() ) {
																		$title = get_the_title( $v );
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
																$coupon_rule_product_ids_title[] = $title;
															}
														}
													}
													?>
                                                    <input type="hidden" name="coupon_rule_product_ids[]"
                                                           value="<?php echo htmlentities( json_encode( $coupon_rule_product_ids ) ) ?>"
                                                           data-product_title="<?php echo htmlentities( json_encode( $coupon_rule_product_ids_title ) ) ?>">
													<?php
													$coupon_rule_excluded_product_ids       = $this->settings->get_params( 'coupon_rule_excluded_product_ids' )[ $key ];
													$coupon_rule_excluded_product_ids_title = array();
													if ( is_array( $coupon_rule_excluded_product_ids ) && count( $coupon_rule_excluded_product_ids ) ) {
														foreach ( $coupon_rule_excluded_product_ids as $k => $v ) {
															$product = wc_get_product( $v );
															if ( $product ) {
																$title       = $product->get_title();
																$product_sku = $product->get_sku();
																if ( $product_sku ) {
																	$title .= '(' . $product_sku . ')';
																}
																if ( $product->is_type( 'variation' ) ) {
																	if ( woocommerce_version_check() ) {
																		$title = get_the_title( $v );
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
																$coupon_rule_excluded_product_ids_title[] = $title;
															}
														}
													}
													?>
                                                    <input type="hidden" name="coupon_rule_excluded_product_ids[]"
                                                           value="<?php echo htmlentities( json_encode( $coupon_rule_excluded_product_ids ) ) ?>"
                                                           data-product_title="<?php echo htmlentities( json_encode( $coupon_rule_excluded_product_ids_title ) ) ?>">
													<?php
													$coupon_rule_product_categories       = $this->settings->get_params( 'coupon_rule_product_categories' )[ $key ];
													$coupon_rule_product_categories_title = array();
													if ( is_array( $coupon_rule_product_categories ) && count( $coupon_rule_product_categories ) ) {
														foreach ( $coupon_rule_product_categories as $k => $v ) {
															$category = get_term( $v );
															if ( $category ) {
																$coupon_rule_product_categories_title[] = $category->name;
															}
														}
													}
													?>
                                                    <input type="hidden" name="coupon_rule_product_categories[]"
                                                           value="<?php echo htmlentities( json_encode( $coupon_rule_product_categories ) ) ?>"
                                                           data-product_title="<?php echo htmlentities( json_encode( $coupon_rule_product_categories_title ) ) ?>">
													<?php
													$coupon_rule_excluded_product_categories       = $this->settings->get_params( 'coupon_rule_excluded_product_categories' )[ $key ];
													$coupon_rule_excluded_product_categories_title = array();
													if ( is_array( $coupon_rule_excluded_product_categories ) && count( $coupon_rule_excluded_product_categories ) ) {
														foreach ( $coupon_rule_excluded_product_categories as $k => $v ) {
															$category = get_term( $v );
															if ( $category ) {
																$coupon_rule_excluded_product_categories_title[] = $category->name;
															}
														}
													}
													?>
                                                    <input type="hidden"
                                                           name="coupon_rule_excluded_product_categories[]"
                                                           value="<?php echo htmlentities( json_encode( $coupon_rule_excluded_product_categories ) ) ?>"
                                                           data-product_title="<?php echo htmlentities( json_encode( $coupon_rule_excluded_product_categories_title ) ) ?>">
                                                </div>

                                                <span class="wtyp-coupon-settings-action-edit vi-ui button"><?php esc_html_e( 'Edit', 'woocommerce-thank-you-page-customizer' ) ?></span>
                                                <span class="wtyp-coupon-settings-action-clone vi-ui button positive"><?php esc_html_e( 'Clone', 'woocommerce-thank-you-page-customizer' ) ?></span>
                                                <span class="wtyp-coupon-settings-action-remove vi-ui button negative"><?php esc_html_e( 'Remove', 'woocommerce-thank-you-page-customizer' ) ?></span>
                                            </td>
                                        </tr>
										<?php
									}
								}
							}

							?>
                            </tbody>
                        </table>
                        <p class="description"><?php esc_html_e( '*When an order is successfully placed, if you use coupons, they will be selected with the priority from top to bottom until the rules are matched. If no rules are matched, no coupon will be given.', 'woocommerce-thank-you-page-customizer' ) ?></p>
                        <p class="description"><?php esc_html_e( '**Click on Edit to view more settings and rules for each coupon.', 'woocommerce-thank-you-page-customizer' ) ?></p>
                        <div class="wtyp-modal-table-container wtyp-hidden-item">
                            <div class="wtyp-modal-table-overlay"></div>
                            <div class="wtyp-modal-table-wrap">

                                <div class="wtyp-modal-table-wrap-1">
                                    <div class="vi-ui vi-ui-coupon top attached tabular menu">
                                        <a class="item active"
                                           data-tab="coupon_settings"><?php esc_html_e( 'Coupon settings', 'woocommerce-thank-you-page-customizer' ) ?></a>
                                        <a class="item"
                                           data-tab="coupon_rule"><?php esc_html_e( 'Rules to give coupon', 'woocommerce-thank-you-page-customizer' ) ?></a>
                                    </div>
                                    <div class="vi-ui vi-ui-coupon bottom attached tab segment active"
                                         data-tab="coupon_settings">
                                        <table class="form-table wtyp-modal-table">
                                            <tbody>
                                            <tr valign="top">
                                                <th scope="row">
                                                    <label for="coupon_type"><?php esc_html_e( 'Select coupon', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                                </th>
                                                <td>
                                                    <select class="vi-ui fluid dropdown coupon-select" id="coupon_type">
                                                        <option value="unique"><?php esc_html_e( 'Unique coupon', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                                        <option value="existing"><?php esc_html_e( 'Existing coupon', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                                    </select>
                                                </td>
                                            </tr>

                                            <tr valign="top" class="coupon-existing">
                                                <th scope="row">
                                                    <label for="existing_coupon"><?php esc_html_e( 'Existing coupon', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                                </th>
                                                <td>
                                                    <select class="search-coupon" id="existing_coupon">
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr valign="top" class="coupon-email-restriction">
                                                <th scope="row">
                                                    <label for="coupon_unique_email_restrictions"><?php esc_html_e( 'Email restriction', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                                </th>
                                                <td>
                                                    <div class="vi-ui toggle checkbox checked">
                                                        <input type="checkbox" id="coupon_unique_email_restrictions"
                                                               value="1">
                                                        <label for="coupon_unique_email_restrictions"><span
                                                                    class="description"><?php esc_html_e( 'Enable to make coupon usable for order billing email only', 'woocommerce-thank-you-page-customizer' ) ?></span></label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr valign="top" class="coupon-unique">
                                                <th scope="row">
                                                    <label for="coupon_unique_discount_type"><?php esc_html_e( 'Discount type', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                                </th>
                                                <td>
                                                    <select class="vi-ui fluid dropdown"
                                                            id="coupon_unique_discount_type">
                                                        <option value="percent"><?php esc_html_e( 'Percentage discount', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                                        <option value="fixed_cart"><?php esc_html_e( 'Fixed cart discount', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                                        <option value="fixed_product"><?php esc_html_e( 'Fixed product discount', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                                    </select>
                                                </td>
                                            </tr>

                                            <tr valign="top" class="coupon-unique">
                                                <th scope="row">
                                                    <label for="coupon_unique_prefix"><?php esc_html_e( 'Coupon code prefix', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                                </th>
                                                <td>
                                                    <input type="text" id="coupon_unique_prefix" value="">
                                                </td>
                                            </tr>
                                            <tr valign="top" class="coupon-unique">
                                                <th scope="row">
                                                    <label for="coupon_unique_amount"><?php esc_html_e( 'Coupon amount', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                                </th>
                                                <td>
                                                    <input type="number" id="coupon_unique_amount" min="0" value="">
                                                </td>
                                            </tr>
                                            <tr valign="top" class="coupon-unique">
                                                <th scope="row">
                                                    <label for="coupon_unique_free_shipping"><?php esc_html_e( 'Allow free shipping', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                                </th>
                                                <td>
                                                    <div class="vi-ui toggle checkbox checked">
                                                        <input type="checkbox" id="coupon_unique_free_shipping"
                                                               value="1">
                                                        <label for="coupon_unique_free_shipping">
                                            <span class="description"><?php printf( esc_html__( 'Enable if the coupon grants free shipping. A free shipping method must be enabled in your shipping zone and be set to require "a valid free shipping coupon" (see the "%s" setting).', 'woocommerce-thank-you-page-customizer' ), '<a href="https://docs.woocommerce.com/document/free-shipping/"
											   target="_blank">' . esc_html__( 'free shipping method', 'woocommerce-thank-you-page-customizer' ) . '</a>' ) ?>
                                            </span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr valign="top" class="coupon-unique">
                                                <th scope="row">
                                                    <label for="coupon_unique_date_expires"><?php esc_html_e( 'Expires after(days)', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                                </th>
                                                <td>
                                                    <input type="number" id="coupon_unique_date_expires" min="0"
                                                           value="">
                                                </td>
                                            </tr>

                                            <tr valign="top" class="coupon-unique">
                                                <th scope="row">
                                                    <label for="coupon_unique_minimum_amount"><?php esc_html_e( 'Minimum spend', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                                </th>
                                                <td>
                                                    <input type="number" id="coupon_unique_minimum_amount" min="0"
                                                           value="">
                                                </td>
                                            </tr>
                                            <tr valign="top" class="coupon-unique">
                                                <th scope="row">
                                                    <label for="coupon_unique_maximum_amount"><?php esc_html_e( 'Maximum spend', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                                </th>
                                                <td>
                                                    <input type="number" id="coupon_unique_maximum_amount" min="0"
                                                           value="">
                                                </td>
                                            </tr>


                                            <tr valign="top" class="coupon-unique">
                                                <th scope="row">
                                                    <label for="coupon_unique_individual_use"><?php esc_html_e( 'Individual use only', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                                </th>
                                                <td>
                                                    <div class="vi-ui toggle checkbox checked">
                                                        <input type="checkbox" id="coupon_unique_individual_use"
                                                               value="1">
                                                        <label for="coupon_unique_individual_use">
                                            <span class="description"><?php esc_html_e( 'Enable if the coupon cannot be used in conjunction with other coupons.', 'woocommerce-thank-you-page-customizer' ) ?>
                                            </span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr valign="top" class="coupon-unique">
                                                <th scope="row">
                                                    <label for="coupon_unique_exclude_sale_items"><?php esc_html_e( 'Exclude sale items', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                                </th>
                                                <td>
                                                    <div class="vi-ui toggle checkbox checked">
                                                        <input type="checkbox" id="coupon_unique_exclude_sale_items"
                                                               value="1">
                                                        <label for="coupon_unique_exclude_sale_items"><span
                                                                    class="description"><?php esc_html_e( 'Enable if the coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are items in the cart that are not on sale.', 'woocommerce-thank-you-page-customizer' ) ?></span></label>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr valign="top" class="coupon-unique">
                                                <th>
                                                    <label for="coupon_unique_product_ids"><?php esc_html_e( 'Products', 'woocommerce-thank-you-page-customizer' ); ?></label>
                                                </th>
                                                <td>
                                                    <select id="coupon_unique_product_ids"
                                                            class="search-product"
                                                            multiple="multiple">
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr valign="top" class="coupon-unique">
                                                <th>
                                                    <label for="coupon_unique_excluded_product_ids"><?php esc_html_e( 'Excluded products', 'woocommerce-thank-you-page-customizer' ); ?></label>
                                                </th>
                                                <td>
                                                    <select id="coupon_unique_excluded_product_ids"
                                                            class="search-product"
                                                            multiple="multiple">
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr valign="top" class="coupon-unique">
                                                <th>
                                                    <label for="coupon_unique_product_categories"><?php esc_html_e( 'Categories', 'woocommerce-thank-you-page-customizer' ); ?></label>
                                                </th>
                                                <td>
                                                    <select id="coupon_unique_product_categories"
                                                            class="search-category"
                                                            multiple="multiple">
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr valign="top" class="coupon-unique">
                                                <th>
                                                    <label for="coupon_unique_excluded_product_categories"><?php esc_html_e( 'Excluded categories', 'woocommerce-thank-you-page-customizer' ); ?></label>
                                                </th>
                                                <td>
                                                    <select id="coupon_unique_excluded_product_categories"
                                                            class="search-category"
                                                            multiple="multiple">
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr valign="top" class="coupon-unique">
                                                <th scope="row">
                                                    <label for="coupon_unique_usage_limit"><?php esc_html_e( 'Usage limit per coupon', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                                </th>
                                                <td>
                                                    <input type="number" id="coupon_unique_usage_limit" min="0"
                                                           value="">
                                                </td>
                                            </tr>
                                            <tr valign="top" class="coupon-unique">
                                                <th scope="row">
                                                    <label for="coupon_unique_limit_usage_to_x_items"><?php esc_html_e( 'Limit usage to X items', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                                </th>
                                                <td>
                                                    <input type="number" id="coupon_unique_limit_usage_to_x_items"
                                                           min="0"
                                                           value="">
                                                </td>
                                            </tr>
                                            <tr valign="top" class="coupon-unique">
                                                <th scope="row">
                                                    <label for="coupon_unique_usage_limit_per_user"><?php esc_html_e( 'Usage limit per user', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                                </th>
                                                <td>
                                                    <input type="number" id="coupon_unique_usage_limit_per_user" min="0"
                                                           value="">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="vi-ui vi-ui-coupon bottom attached tab segment" data-tab="coupon_rule">
                                        <table class="form-table">
                                            <tr>
                                                <th>
                                                    <label for="coupon_rule_min_total">
														<?php esc_html_e( 'Minimum order total', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                                </th>
                                                <td>
                                                    <input type="number" id="coupon_rule_min_total"
                                                           min="0"
                                                           value="">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <label for="coupon_rule_max_total">
														<?php esc_html_e( 'Maximum order total', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                                </th>
                                                <td>
                                                    <input type="number" id="coupon_rule_max_total"
                                                           min="0"
                                                           value="">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <label for="coupon_rule_product_ids"><?php esc_html_e( 'Include products', 'woocommerce-thank-you-page-customizer' ); ?></label>
                                                </th>
                                                <td>
                                                    <select id="coupon_rule_product_ids"
                                                            class="search-product"
                                                            multiple="multiple">
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <label for="coupon_rule_excluded_product_ids"><?php esc_html_e( 'Exclude products', 'woocommerce-thank-you-page-customizer' ); ?></label>
                                                </th>
                                                <td>
                                                    <select id="coupon_rule_excluded_product_ids"
                                                            class="search-product"
                                                            multiple="multiple">
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <label for="coupon_rule_product_categories"><?php esc_html_e( 'Include categories', 'woocommerce-thank-you-page-customizer' ); ?></label>
                                                </th>
                                                <td>
                                                    <select id="coupon_rule_product_categories"
                                                            class="search-category"
                                                            multiple="multiple">
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <label for="coupon_rule_excluded_product_categories"><?php esc_html_e( 'Exclude categories', 'woocommerce-thank-you-page-customizer' ); ?></label>
                                                </th>
                                                <td>
                                                    <select id="coupon_rule_excluded_product_categories"
                                                            class="search-category"
                                                            multiple="multiple">
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                </div>
                                <div class="wtyp-modal-table-buttons">
                                    <div class="wtyp-modal-table-button wtyp-modal-table-button-ok vi-ui positive button"><?php esc_html_e( 'OK', 'woocommerce-thank-you-page-customizer' ) ?></div>
                                    <div class="wtyp-modal-table-button wtyp-modal-table-button-cancel vi-ui negative button"><?php esc_html_e( 'Cancel', 'woocommerce-thank-you-page-customizer' ) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="email">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">
                                    <label for="email-send"><?php esc_html_e( 'Send coupon email', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox" name="coupon_email_send" id="email-send"
                                               value="1" <?php checked( $this->settings->get_params( 'coupon_email_send' ), '1' ) ?>>
                                    </div>
                                    <p class="description"><?php echo __( 'Send coupon email if coupon is given on thank you page', 'woocommerce-thank-you-page-customizer' ) ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th >
                                    <label for="email_template"><?php esc_html_e( 'Email template', 'woocommerce-thank-you-page-customizer' ); ?></label>
                                </th>
                                <td>
                                    <?php
                                    $email_template = $this->settings->get_params( 'email_template' );
                                    $email_templates = $this->settings::get_email_templates();
                                    $this->settings::get_language_flag_html( $this->default_language, $this->languages_data );
                                    ?>
                                    <select name="email_template" class="vi-ui fluid dropdown email_template" id="email_template">
                                        <option value=""><?php esc_html_e( 'None', 'woocommerce-thank-you-page-customizer' ) ?></option>
	                                    <?php
	                                    if ( count( $email_templates ) ) {
		                                    foreach ( $email_templates as $k => $v ) {
		                                        echo sprintf('<option value="%s" %s >%s</option>',
                                                esc_attr($v->ID),
                                                selected($v->ID, $email_template),
                                                esc_html($v->post_title.'(#'.$v->ID.')')
                                                );
		                                    }
	                                    }
	                                    ?>
                                    </select>
                                    <p class="description"><?php _e( 'You can use <a href="https://1.envato.market/BZZv1" target="_blank">WooCommerce Email Template Customizer</a> or <a href="http://bit.ly/woo-email-template-customizer" target="_blank">Email Template Customizer for WooCommerce</a> to create and customize your own email template. If no email template is selected, below email will be used.', 'woocommerce-thank-you-page-customizer') ?></p>
	                                <?php
	                                if ( $this->settings::email_template_customizer_active() ) {
	                                    echo sprintf('<p class="description"><a  href="edit.php?post_type=viwec_template" target="_blank">%s</a> %s <a href="post-new.php?post_type=viwec_template&sample=wtypc_coupon_email&style=basic" target="_blank">%s</a></p>',
                                        __( 'View all Email templates',  'woocommerce-thank-you-page-customizer'),
                                        __( 'or', 'woocommerce-thank-you-page-customizer' ),
                                        __( 'Create a new email template', 'woocommerce-thank-you-page-customizer' )
	                                    );
	                                }
	                                if ( count( $this->languages ) && count($email_templates) ) {
		                                foreach ( $this->languages as $key => $value ) {
			                                $email_template = 'email_template_' . $value;
			                                $email_template_lang = $this->settings->get_params( 'email_template',  $value );
			                                $this->settings::get_language_flag_html( $value, $this->languages_data );
			                                ?>
                                            <select name="<?php echo esc_attr($email_template)?>" class="vi-ui fluid dropdown <?php echo esc_attr($email_template)?>" id="<?php echo esc_attr($email_template)?>">
                                                <option value=""><?php esc_html_e( 'None', 'woocommerce-thank-you-page-customizer' ) ?></option>
				                                <?php
				                                if ( count( $email_templates ) ) {
					                                foreach ( $email_templates as $k => $v ) {
						                                echo sprintf('<option value="%s" %s >%s</option>',
							                                esc_attr($v->ID),
							                                selected($v->ID, $email_template_lang),
							                                esc_html($v->post_title.'(#'.$v->ID.')')
						                                );
					                                }
				                                }
				                                ?>
                                            </select>
                                            <?php
		                                }
	                                }
	                                ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="coupon-email-subject"><?php esc_html_e( 'Email subject', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
	                                <?php $this->settings::get_language_flag_html( $this->default_language, $this->languages_data ); ?>
                                    <input type="text" name="coupon_email_subject" id="coupon-email-subject"
                                           value="<?php echo wp_kses_post( $this->settings->get_params( 'coupon_email_subject' ) ) ?>">
                                    <p class="description"><?php echo __( '', 'woocommerce-thank-you-page-customizer' ) ?></p>
	                                <?php
	                                if ( count( $this->languages ) ) {
		                                foreach ( $this->languages as $key => $value ) {
			                                $this->settings::get_language_flag_html( $value, $this->languages_data );
			                                echo sprintf(
				                                '<input type="text" name="coupon_email_subject_%s" class="vi-wtypc-coupon_email_subject" placeholder="%s  {shop_title}" value="%s">',
				                                $value, __( 'Your coupon from', 'woocommerce-cart-all-in-one' ),
				                                $this->settings->get_params( 'coupon_email_subject','_'.$value )  );
		                                }
	                                }
	                                ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="coupon-email-heading"><?php esc_html_e( 'Email heading', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
	                                <?php $this->settings::get_language_flag_html( $this->default_language, $this->languages_data ); ?>
                                    <input type="text" name="coupon_email_heading" id="coupon-email-heading"
                                           value="<?php echo htmlentities( $this->settings->get_params( 'coupon_email_heading' ) ) ?>">
                                    <p class="description"><?php echo __( '', 'woocommerce-thank-you-page-customizer' ) ?></p>
	                                <?php
	                                if ( count( $this->languages ) ) {
		                                foreach ( $this->languages as $key => $value ) {
			                                $this->settings::get_language_flag_html( $value, $this->languages_data );
			                                echo sprintf(
				                                '<input type="text" name="coupon_email_heading_%s" id="coupon-email-heading_%s" placeholder="{coupon_amount} %s" value="%s">',
				                                $value,$value, __( 'OFF coupon for you', 'woocommerce-cart-all-in-one' ),
				                                $this->settings->get_params( 'coupon_email_heading','_'.$value )  );
		                                }
	                                }
	                                ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="coupon_email_content"><?php esc_html_e( 'Email content', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
	                                <?php $this->settings::get_language_flag_html( $this->default_language, $this->languages_data ); ?>
									<?php wp_editor( wp_kses_post( $this->settings->get_params( 'coupon_email_content' ) ), 'coupon_email_content', array( 'editor_height' => 300 ) ) ?>
                                    <p class="description"><?php echo __( '', 'woocommerce-thank-you-page-customizer' ) ?></p>
	                                <?php
	                                if ( count( $this->languages ) ) {
		                                foreach ( $this->languages as $key => $value ) {
			                                $this->settings::get_language_flag_html( $value, $this->languages_data );
			                                wp_editor( wp_kses_post( $this->settings->get_params( 'coupon_email_content' ,'_'.$value) ), 'coupon_email_content'.'_'.$value, array( 'editor_height' => 300 ) ) ;
		                                }
	                                }
	                                ?>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="woo_order_email">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">
                                    <label for="coupon-woo-enable"><?php esc_html_e( 'Include coupon info', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox" name="coupon_woo_email_enable" id="coupon-woo-enable"
                                               value="1" <?php checked( $this->settings->get_params( 'coupon_woo_email_enable' ), '1' ) ?>>
                                    </div>
                                    <p class="description"><?php echo __( 'Include coupon info in WooCommerce order email if coupon is given on thank you page', 'woocommerce-thank-you-page-customizer' ) ?></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="coupon-woo-status"><?php esc_html_e( 'Order status email', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
									<?php
									$email_woo_status = $this->settings->get_params( 'coupon_woo_email_status' );
									?>
                                    <select name="coupon_woo_email_status[]" id="coupon-woo-status" class="vi-ui fluid dropdown coupon-woo-status" multiple>
                                        <option value="cancelled_order" <?php echo in_array( 'cancelled_order', $email_woo_status ) ? 'selected' : ""; ?> ><?php esc_html_e( 'Cancelled', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                        <option value="customer_completed_order" <?php echo in_array( 'customer_completed_order', $email_woo_status ) ? 'selected' : ""; ?> ><?php esc_html_e( 'Completed', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                        <option value="customer_invoice" <?php echo in_array( 'customer_invoice', $email_woo_status ) ? 'selected' : ""; ?> ><?php esc_html_e( 'Customer Invoice', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                        <option value="customer_note" <?php echo in_array( 'customer_note', $email_woo_status ) ? 'selected' : ""; ?> ><?php esc_html_e( 'Customer Note', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                        <option value="failed_order" <?php echo in_array( 'failed_order', $email_woo_status ) ? 'selected' : ""; ?> ><?php esc_html_e( 'Failed', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                        <option value="customer_on_hold_order" <?php echo in_array( 'customer_on_hold_order', $email_woo_status ) ? 'selected' : ""; ?> ><?php esc_html_e( 'On Hold', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                        <option value="customer_processing_order" <?php echo in_array( 'customer_processing_order', $email_woo_status ) ? 'selected' : ""; ?> ><?php esc_html_e( 'Processing', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                        <option value="customer_refunded_order" <?php echo in_array( 'customer_refunded_order', $email_woo_status ) ? 'selected' : ""; ?> ><?php esc_html_e( 'Refunded', 'woocommerce-thank-you-page-customizer' ) ?></option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="update">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">
                                    <label for="auto-update-key"><?php esc_html_e( 'Auto Update Key', 'woocommerce-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <div class="fields">
                                        <div class="ten wide field">
                                            <input type="text" name="auto_update_key" id="auto-update-key"
                                                   class="villatheme-autoupdate-key-field"
                                                   value="<?php echo htmlentities( $this->settings->get_params( 'auto_update_key' ) ) ?>">
                                        </div>
                                        <div class="six wide field">
                                        <span class="vi-ui button green villatheme-get-key-button"
                                              data-href="https://api.envato.com/authorization?response_type=code&client_id=villatheme-download-keys-6wzzaeue&redirect_uri=https://villatheme.com/update-key"
                                              data-id="22956731"><?php echo esc_html__( 'Get Key', 'woocommerce-thank-you-page-customizer' ) ?></span>
                                        </div>
                                    </div>
									<?php do_action( 'woocommerce-thank-you-page-customizer_key' ) ?>
                                    <p class="description"><?php echo __( 'Please fill your key what you get from <a target="_blank" href="https://villatheme.com/my-download">Villatheme</a>. You can automatically update WooCommerce Thank You Page Customizer plugin. See guide <a target="_blank" href="https://villatheme.com/knowledge-base/how-to-use-auto-update-feature/">here</a>', 'woocommerce-thank-you-page-customizer' ) ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <p>
                        <input type="submit" name="wtyp_save_data" value="Save" class="vi-ui primary button">
                        <button class="vi-ui button"
                                name="wtyp_check_key">
							<?php esc_html_e( 'Save & Check Key', 'woocommerce-thank-you-page-customizer' ); ?>
                        </button>
                    </p>
                </form>
            </div>

        </div>
		<?php
		$shortcodes = array(
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
		?>
        <div class="<?php echo $this->set( $is_rtl ?array( 'available-shortcodes-container', 'available-shortcodes-container-rtl', 'hidden' ): array( 'available-shortcodes-container', 'hidden' ) ) ?>">
            <div class="<?php echo $this->set( 'available-shortcodes-overlay' ) ?>">
            </div>
            <div class="<?php echo $this->set( 'available-shortcodes-items' ) ?>">
                <div class="<?php echo $this->set( 'available-shortcodes-items-header' ) ?>">
					<?php _e( 'Available shortcode', 'woocommerce-thank-you-page-customizer' ) ?>
                    <span class="<?php echo $this->set( 'available-shortcodes-items-close' ) ?> wtyp_icons-cancel"></span>
                </div>
                <div class="<?php echo $this->set( 'available-shortcodes-items-content' ) ?>">
					<?php
					foreach ( $shortcodes as $key => $value ) {
						?>
                        <div class="<?php echo $this->set( 'available-shortcodes-item' ) ?>">
                            <div class="<?php echo $this->set( 'available-shortcodes-item-name' ) ?>"><?php echo $value ?></div>
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

		do_action( 'villatheme_support_woocommerce-thank-you-page-customizer' );
	}

	private function set( $name ) {
		if ( is_array( $name ) ) {
			return implode( ' ', array_map( array( $this, 'set' ), $name ) );

		} else {
			return esc_attr__( $this->prefix . $name );

		}
	}

	public function admin_enqueue_script() {
		$page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';
		if ( $page == 'woocommerce_thank_you_page_customizer' ) {
			global $wp_scripts;
			$scripts = $wp_scripts->registered;
			foreach ( $scripts as $k => $script ) {
				preg_match( '/^\/wp-/i', $script->src, $result );
				if ( count( array_filter( $result ) ) ) {
					preg_match( '/^(\/wp-content\/plugins|\/wp-content\/themes)/i', $script->src, $result1 );
					if ( count( array_filter( $result1 ) ) ) {
						wp_dequeue_script( $script->handle );
					}
				} else {
					if ( $script->handle != 'query-monitor' ) {
						wp_dequeue_script( $script->handle );
					}
				}
			}
			// style
			wp_enqueue_style( 'woocommerce-thank-you-page-form', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'form.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-button', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'button.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-icon', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'icon.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-dropdown', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'dropdown.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-checkbox', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'checkbox.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-transition', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'transition.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-tab', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'tab.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-segment', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'segment.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-menu', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'menu.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-select2', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'select2.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-icons', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'woocommerce-thank-you-page-icons.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-admin', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'admin-style.css' );
			wp_enqueue_style( 'woocommerce-coupon-villatheme-support', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'villatheme-support.css' );
			$css = '.woo-thank-you-page-customizer-coupon-input{line-height:46px;display:block;text-align: center;font-size: 24px;width: 100%;height: 46px;vertical-align: middle;margin: 0;color:' . $this->settings->get_params( 'coupon_code_color' ) . ';background-color:' . $this->settings->get_params( 'coupon_code_bg_color' ) . ';border-width:' . $this->settings->get_params( 'coupon_code_border_width' ) . 'px;border-style:' . $this->settings->get_params( 'coupon_code_border_style' ) . ';border-color:' . $this->settings->get_params( 'coupon_code_border_color' ) . ';}';
			wp_add_inline_style( 'woocommerce-thank-you-page-admin', $css );
			//script
			/*Color picker*/
			wp_enqueue_script(
				'iris', admin_url( 'js/iris.min.js' ), array(
				'jquery-ui-draggable',
				'jquery-ui-slider',
				'jquery-touch-punch'
			), false, 1
			);
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'woocommerce-thank-you-page-form', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'form.min.js', array( 'jquery' ), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION );
			wp_enqueue_script( 'woocommerce-thank-you-page-checkbox', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'checkbox.min.js', array( 'jquery' ), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION );
			wp_enqueue_script( 'woocommerce-thank-you-page-dropdown', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'dropdown.min.js', array( 'jquery' ), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION );
			wp_enqueue_script( 'woocommerce-thank-you-page-transition', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'transition.min.js', array( 'jquery' ), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION );
			wp_enqueue_script( 'woocommerce-thank-you-page-tab', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'tab.js', array( 'jquery' ), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION );
			wp_enqueue_script( 'woocommerce-thank-you-page-address', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'jquery.address-1.6.min.js', array( 'jquery' ), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION );
			wp_enqueue_script( 'woocommerce-thank-you-page-select2', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'select2.js', array( 'jquery' ), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION );
			wp_enqueue_script( 'woocommerce-thank-you-page-admin', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'admin-script.js', array( 'jquery' ), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION );
			wp_localize_script( 'woocommerce-thank-you-page-admin', 'wtypc_params_admin', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
		}
	}

	public function check_update() {
		/**
		 * Check update
		 */
		if ( class_exists( 'VillaTheme_Plugin_Check_Update' ) ) {
			$setting_url = admin_url( 'admin.php?page=woocommerce_thank_you_page_customizer' );
			$key         = $this->settings->get_params( 'auto_update_key' );
			new VillaTheme_Plugin_Check_Update (
				VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION,                    // current version
				'https://villatheme.com/wp-json/downloads/v3',  // update path
				'woocommerce-thank-you-page-customizer/woocommerce-thank-you-page-customizer.php',                  // plugin file slug
				'woocommerce-thank-you-page-customizer', '19195', $key, $setting_url
			);
			new VillaTheme_Plugin_Updater( 'woocommerce-thank-you-page-customizer/woocommerce-thank-you-page-customizer.php', 'woocommerce-thank-you-page-customizer', $setting_url );
		}
	}

	public function save_data() {
		global $woo_thank_you_page_settings, $pagenow;
		$page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';
		if ( $pagenow == 'admin.php' && $page == 'woocommerce_thank_you_page_customizer' ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
				/*wpml*/
				global $sitepress;
				$this->default_language = $sitepress->get_default_language();
				$languages              = icl_get_languages( 'skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str' );
				$this->languages_data   = $languages;
				if ( count( $languages ) ) {
					foreach ( $languages as $key => $language ) {
						if ( $key != $this->default_language ) {
							$this->languages[] = $key;
						}
					}
				}
			} elseif ( class_exists( 'Polylang' ) ) {
				/*Polylang*/
				$languages              = pll_languages_list();
				$this->default_language = pll_default_language( 'slug' );
				foreach ( $languages as $language ) {
					if ( $language == $this->default_language ) {
						continue;
					}
					$this->languages[] = $language;
				}
			}
			if ( ! isset( $_POST['_woo_thank_you_page_nonce'] ) || ! wp_verify_nonce( $_POST['_woo_thank_you_page_nonce'], 'woo_thank_you_page_action_nonce' ) ) {
				return;
			}
			if ( isset( $_POST['wtyp_check_key'] ) ) {
				delete_transient( '_site_transient_update_plugins' );
				delete_transient( 'villatheme_item_19195' );
				delete_option( 'woocommerce-thank-you-page-customizer_messages' );
			}
			$args = array(
				'coupon_type'                               => array( 'unique' ),
				'existing_coupon'                           => array( '' ),
				'coupon_unique_discount_type'               => array( 'percent' ),
				'coupon_unique_amount'                      => array( '10' ),
				'coupon_unique_date_expires'                => array( 30 ),
				'coupon_unique_individual_use'              => array( false ),
				'coupon_unique_product_ids'                 => array( array() ),
				'coupon_unique_excluded_product_ids'        => array( array() ),
				'coupon_unique_usage_limit'                 => array( 0 ),
				'coupon_unique_usage_limit_per_user'        => array( 0 ),
				'coupon_unique_limit_usage_to_x_items'      => array( null ),
				'coupon_unique_free_shipping'               => array( false ),
				'coupon_unique_product_categories'          => array( array() ),
				'coupon_unique_excluded_product_categories' => array( array() ),
				'coupon_unique_exclude_sale_items'          => array( false ),
				'coupon_unique_minimum_amount'              => array( '50' ),
				'coupon_unique_maximum_amount'              => array( '100' ),
				'coupon_unique_email_restrictions'          => array( true ),
				'coupon_unique_prefix'                      => array( '' ),
				'coupon_rule_product_ids'                   => array( array() ),
				'coupon_rule_excluded_product_ids'          => array( array() ),
				'coupon_rule_product_categories'            => array( array() ),
				'coupon_rule_excluded_product_categories'   => array( array() ),
				'coupon_rule_min_total'                     => array( 0 ),
				'coupon_rule_max_total'                     => array( 100 ),
				'coupon_woo_email_status'                   => array(
					'cancelled_order',
					'customer_completed_order',
					'customer_invoice',
					'customer_note',
					'failed_order',
					'customer_on_hold_order',
					'customer_processing_order',
					'customer_refunded_order'
				),
			);
			foreach ( $args as $key => $value ) {
				if ( in_array( $key, array(
					'coupon_unique_product_categories',
					'coupon_unique_excluded_product_categories',
					'coupon_unique_product_ids',
					'coupon_unique_excluded_product_ids',
					'coupon_rule_product_ids',
					'coupon_rule_excluded_product_ids',
					'coupon_rule_product_categories',
					'coupon_rule_excluded_product_categories',
				) ) ) {
					$args[ $key ] = isset( $_POST[ $key ] ) ? array_map( 'wtyp_json_decode', $_POST[ $key ] ) : array();

				} else {
					$args[ $key ] = isset( $_POST[ $key ] ) ? array_map( 'stripslashes', ( $_POST[ $key ] ) ) : array();
				}
			}
			$args['my_account_coupon_enable'] = isset( $_POST['my_account_coupon_enable'] ) ? wc_clean( $_POST['my_account_coupon_enable'] ) : '';
			$args['order_status']             = isset( $_POST['order_status'] ) ? wc_clean( $_POST['order_status'] ) : array();
			$map_args_1 = array(
			        'enable',
			        'auto_update_key',
			        'google_map_api',
			        'bing_map_api',
			        'coupon_email_send',
			        'email_template',
			        'coupon_limit_per_day',
			        'coupon_limit_per_week',
			        'coupon_limit_per_month',
			        'coupon_limit_per_year',
			        'coupon_woo_email_enable',
            );
			$map_args_2 = array(
			       'coupon_email_subject',
			       'coupon_email_heading',
			       'coupon_email_content',
            );
			if ( count( $this->languages ) ) {
				foreach ( $this->languages as $key => $value ) {
					$map_args_1[] = 'email_template_' . $value;
					$map_args_2[] = 'coupon_email_subject_' . $value;
					$map_args_2[] = 'coupon_email_heading_' . $value;
					$map_args_2[] = 'coupon_email_content_' . $value;
				}
			}
			foreach ( $map_args_1 as $item ) {
				$args[ $item ] = isset( $_POST[ $item ] ) ? sanitize_text_field( wp_unslash( $_POST[ $item ] ) ) : '';
			}
			foreach ( $map_args_2 as $item ) {
				$args[ $item ] = isset( $_POST[ $item ] ) ? wp_kses_post( wp_unslash( $_POST[ $item ] ) ) : '';
			}
			$args                             = wp_parse_args( $args, get_option( 'woo_thank_you_page_params', $woo_thank_you_page_settings ) );
			update_option( 'woo_thank_you_page_params', $args );
			$woo_thank_you_page_settings = $args;
		}
	}
}