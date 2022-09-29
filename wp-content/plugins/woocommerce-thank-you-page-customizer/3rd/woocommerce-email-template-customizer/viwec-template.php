<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOOCOMMERCE_THANK_YOU_PAGE_3RD_Viwec_Template {
	protected $settings;

	public function __construct() {
		$this->settings = new VI_WOOCOMMERCE_THANK_YOU_PAGE_DATA();
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_filter( 'viwec_register_element_for_email_type_has_order', array( $this, 'viwec_register_element_for_email_type_has_order' ) );
		add_filter( 'viwec_register_email_type', array( $this, 'register_email_type' ) );
		add_filter( 'viwec_sample_subjects', array( $this, 'register_email_sample_subject' ) );
		add_filter( 'viwec_sample_templates', array( $this, 'register_email_sample_template' ) );
		add_filter( 'viwec_live_edit_shortcodes', array( $this, 'register_render_preview_shortcode' ) );
		add_filter( 'viwec_register_preview_shortcode', array( $this, 'register_render_preview_shortcode' ) );
		add_action( 'viwec_render_content', array( $this, 'render_review_reminder' ), 10, 3 );
	}

	public function admin_enqueue_scripts() {
		global $pagenow, $post_type, $viwec_params;
		if ( ( $pagenow === 'post.php' || $pagenow === 'post-new.php' ) && $post_type === 'viwec_template' && $viwec_params !== null ) {
			wp_enqueue_script( 'woocommerce-thank-you-page-3rd-viwec-template', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . '3rd-viwec-template.js',
				array( 'jquery', 'woocommerce-email-template-customizer-components' ),
				VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION
			);
			wp_localize_script( 'woocommerce-photo-reviews-email-template-customizer', 'wtypc_3rd_viwec_template', array(
				'coupon_gift' => array(
					'category' => 'wtypc_woo_email',
					'type'     => 'wtypc_coupon_on_woo_email',
					'name'     => __( 'Coupon gift for the order', 'woocommerce-thank-you-page-customizer' ),
					'icon'     => 'coupon',
					'html'     => $this->coupon_on_woo_email_html(),
				),
			) );
		}
	}

	public function render_review_reminder( $type, $props, $render ) {
		if ( $type === 'wtypc_coupon_on_woo_email' && ! empty( $order = $render->order ) ) {
			$order_id = $order->get_id();
			if ( ! $order_id || ! $order->get_date_created() ) {
				return;
			}
			if ( metadata_exists( 'post', $order_id, 'woo_thank_you_page_coupon_code' ) ) {
				$coupon_code = get_post_meta( $order_id, 'woo_thank_you_page_coupon_code', true );
			}
			if ( ! $coupon_code ) {
				return;
			}
			$coupon              = new WC_Coupon( $coupon_code );
			$coupon_code         = strtoupper( $coupon_code );
			$date_expires        = $coupon->get_date_expires();
			$coupon_date_expires = empty( $date_expires ) ? esc_html__( 'never expires', 'woocommerce-thank-you-page-customizer' ) : date_i18n( 'F d, Y', strtotime( $date_expires ) );
			$last_valid_date     = empty( $date_expires ) ? '' : date_i18n( 'F d, Y', strtotime( $date_expires ) - 86400 );
			if ( $coupon->get_discount_type() == 'percent' ) {
				$coupon_amount = $coupon->get_amount() . '%';
			} else {
				$coupon_amount = wc_price( $coupon->get_amount() );
			}
			$shortcodes     = array(
				'order_number'   => $order->get_order_number(),
				'order_status'   => $order->get_status(),
				'order_date'     => $order->get_date_created() ? $order->get_date_created()->date_i18n( 'F d, Y' ) : '',
				'order_total'    => $order->get_formatted_order_total(),
				'order_subtotal' => $order->get_subtotal_to_display(),
				'items_count'    => $order->get_item_count(),
				'payment_method' => $order->get_payment_method_title(),

				'shipping_method'            => $order->get_shipping_method(),
				'shipping_address'           => $order->get_shipping_address_1(),
				'formatted_shipping_address' => $order->get_formatted_shipping_address(),

				'billing_address'           => $order->get_billing_address_1(),
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

				'coupon_amount'       => $coupon_amount,
				'coupon_code'         => $coupon_code,
				'coupon_date_expires' => $coupon_date_expires,
				'last_valid_date'     => $last_valid_date,
			);
			$before_style   = ! empty( $props['childStyle']['.viwtypc-woo-email-coupon-before-wrap'] ) ? viwec_parse_styles( $props['childStyle']['.viwtypc-woo-email-coupon-before-wrap'] ) : '';
			$content_style  = ! empty( $props['childStyle']['.viwtypc-woo-email-coupon-content-wrap'] ) ? viwec_parse_styles( $props['childStyle']['.viwtypc-woo-email-coupon-content-wrap'] ) : '';
			$content_style1 = ! empty( $props['childStyle']['.viwtypc-woo-email-coupon-content'] ) ? viwec_parse_styles( $props['childStyle']['.viwtypc-woo-email-coupon-content'] ) : '';
			$after_style    = ! empty( $props['childStyle']['.viwtypc-woo-email-coupon-after-wrap'] ) ? viwec_parse_styles( $props['childStyle']['.viwtypc-woo-email-coupon-after-wrap'] ) : '';
			$before_text    = $props['content']['viwtypc_woo_email_coupon_before_text'] ?? '';
			$content_text   = $props['content']['viwtypc_woo_email_coupon_content_text'] ?? '';
			$after_text     = $props['content']['viwtypc_woo_email_coupon_after_text'] ?? '';
			$content_style1 .= 'display: inline-block;text-decoration: none;';
			foreach ( $shortcodes as $key => $value ) {
				$before_text  = str_replace( '{wtypc_' . $key . '}', $value, $before_text );
				$content_text = str_replace( '{wtypc_' . $key . '}', $value, $content_text );
				$after_text   = str_replace( '{wtypc_' . $key . '}', $value, $after_text );
			}
			?>
            <div class="viwtypc-woo-email-coupon-wrap" style="width: 100%">
                <div class="viwtypc-woo-email-coupon-before-wrap" style="<?php echo esc_attr( $before_style ); ?>">
                    <div class="viwtypc-woo-email-coupon-before">
						<?php echo wp_kses_post( $before_text ); ?>
                    </div>
                </div>
                <div class="viwtypc-woo-email-coupon-content-wrap" style="<?php echo esc_attr( $content_style ); ?>">
                    <div class="viwtypc-woo-email-coupon-content" style="<?php echo esc_attr( $content_style1 ); ?>">
                        <div class="viwtypc-woo-email-coupon-content1">
							<?php echo wp_kses_post( $content_text ); ?>
                        </div>
                    </div>
                </div>
                <div class="viwtypc-woo-email-coupon-after-wrap" style="<?php echo esc_attr( $after_style ); ?>">
                    <div class="viwtypc-woo-email-coupon-after">
						<?php echo wp_kses_post( $after_text ); ?>
                    </div>
                </div>
            </div>
			<?php
		}
	}

	public function viwec_register_element_for_email_type_has_order( $element ) {
		$element[] = 'wtypc_coupon_on_woo_email';
		return $element;
	}

	public function coupon_on_woo_email_html() {
		ob_start();
		?>
        <div class="viwtypc-woo-email-coupon-wrap">
            <div class="viwtypc-woo-email-coupon-before-wrap" style="font-size: 20px;">
                <div class="viwtypc-woo-email-coupon-before">Coupon gift</div>
            </div>
            <div class="viwtypc-woo-email-coupon-content-wrap" style="text-align: center;">
                <div class="viwtypc-woo-email-coupon-content"
                     style="display: inline-block;text-decoration: none;padding-top: 10px;padding-left: 20px;padding-bottom: 10px;padding-right: 20px;background-color: #fff;color: #222;border-width: 2px;border-color: #162447;border-style: dashed;margin-top: 15px;margin-bottom: 15px;">
                    <div class="viwtypc-woo-email-coupon-content1">{wtypc_coupon_code}</div>
                </div>
            </div>
            <div class="viwtypc-woo-email-coupon-after-wrap">
                <div class="viwtypc-woo-email-coupon-after">This coupon will expire on {wtypc_coupon_date_expires}</div>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}

	public function register_render_preview_shortcode( $sc ) {
		$date_expires             = strtotime( '+30 days' );
		$sc['wtypc_coupon_email'] = array(
			'{wtypc_order_number}'   => date( 'Y' ),
			'{wtypc_order_status}'   => 'processing',
			'{wtypc_order_date}'     => date_i18n( 'F d, Y', strtotime( 'today' ) ),
			'{wtypc_order_total}'    => 999,
			'{wtypc_order_subtotal}' => 990,
			'{wtypc_items_count}'    => 3,
			'{wtypc_payment_method}' => 'Cash on delivery',

			'{wtypc_shipping_method}'            => 'Free shipping',
			'{wtypc_shipping_address}'           => 'Thainguyen City',
			'{wtypc_formatted_shipping_address}' => 'Thainguyen City, Vietnam',

			'{wtypc_billing_address}'           => 'Thainguyen City',
			'{wtypc_formatted_billing_address}' => 'Thainguyen City, Vietnam',
			'{wtypc_billing_country}'           => 'VN',
			'{wtypc_billing_city}'              => 'Thainguyen',

			'{wtypc_billing_first_name}'          => 'John',
			'{wtypc_billing_last_name}'           => 'Doe',
			'{wtypc_formatted_billing_full_name}' => 'John Doe',
			'{wtypc_billing_email}'               => 'support@villatheme.com',

			'{wtypc_shop_title}' => get_bloginfo(),
			'{wtypc_home_url}'   => home_url(),
			'{wtypc_shop_url}'   => get_option( 'woocommerce_shop_page_id', '' ) ? get_page_link( get_option( 'woocommerce_shop_page_id' ) ) : '',

			'{wtypc_coupon_amount}'       => '10%',
			'{wtypc_coupon_code}'         => 'HAPPY',
			'{wtypc_coupon_date_expires}' => date_i18n( 'F d, Y', $date_expires ),
			'{wtypc_last_valid_date}'     => date_i18n( 'F d, Y', $date_expires - 86400 ),
		);

		return $sc;
	}

	public function register_email_sample_template( $samples ) {
		$samples['wtypc_coupon_email'] = [
			'basic' => [
				'name' => esc_html__( 'Basic', 'woocommerce-thank-you-page-customizer' ),
				'data' => '{"style_container":{"background-color":"transparent","background-image":"none"},"rows":{"0":{"props":{"style_outer":{"padding":"15px 35px","background-image":"none","background-color":"#162447","border-color":"transparent","border-style":"solid","border-width":"0px","border-radius":"0px","width":"600px"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"30px","background-image":"none","padding":"0px","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px"},"content":{"text":"<p style=\"text-align: center;\"><span style=\"color: #ffffff;\">{site_title}</span></p>"},"attrs":{},"childStyle":{}}}}}},"1":{"props":{"style_outer":{"padding":"25px","background-image":"none","background-color":"#f9f9f9","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px","width":"600px"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px","width":"550px"}},"elements":{"0":{"type":"html/text","style":{"width":"550px","line-height":"28px","background-image":"none","padding":"0px","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px"},"content":{"text":"<p style=\"text-align: center;\"><span style=\"color: #444444; font-size: 24px;\">{wtypc_coupon_amount} OFF coupon for you</span></p>"},"attrs":{},"childStyle":{}}}}}},"2":{"props":{"style_outer":{"padding":"10px 35px","background-image":"none","background-color":"#ffffff","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px","width":"600px"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","padding":"0px","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px"},"content":{"text":"<p>Hello {wtypc_billing_first_name},</p>"},"attrs":{},"childStyle":{}},"1":{"type":"html/spacer","style":{"width":"530px"},"content":{},"attrs":{},"childStyle":{".viwec-spacer":{"padding":"10px 0px 0px"}}},"2":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","padding":"0px","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px"},"content":{"text":"<p>Thank you for your purchase, this coupon is for you {wtypc_coupon_code}. Please apply it the next time you shop with us.</p>"},"attrs":{},"childStyle":{}},"3":{"type":"html/spacer","style":{"width":"530px"},"content":{},"attrs":{},"childStyle":{".viwec-spacer":{"padding":"10px 0px 0px"}}},"4":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","padding":"0px","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px"},"content":{"text":"<p>We look forward to seeing you again. Have a great day!</p>"},"attrs":{},"childStyle":{}},"5":{"type":"html/button","style":{"width":"530px","font-size":"15px","font-weight":"400","color":"#1de712","line-height":"22px","text-align":"center","padding":"20px 0px 20px 1px"},"content":{"text":"{wtypc_coupon_code}"},"attrs":{"href":"{shop_url}"},"childStyle":{"a":{"border-width":"2px","border-radius":"0px","border-color":"#162447","border-style":"dashed","background-color":"#ffffff","width":"141px","padding":"10px 20px"}}},"6":{"type":"html/spacer","style":{"width":"530px"},"content":{},"attrs":{},"childStyle":{".viwec-spacer":{"padding":"10px 0px 0px"}}},"7":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","padding":"0px","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px"},"content":{"text":"<p>Yours sincerely!</p>\n<p>{wtypc_shop_title}</p>"},"attrs":{},"childStyle":{}}}}}},"3":{"props":{"style_outer":{"padding":"10px 35px","background-image":"none","background-color":"#ffffff","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px","width":"600px"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","padding":"0px","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px"},"content":{"text":"<p>You might want to take a look at our latest products:</p>"},"attrs":{},"childStyle":{}},"1":{"type":"html/spacer","style":{"width":"530px"},"content":{},"attrs":{},"childStyle":{".viwec-spacer":{"padding":"10px 0px 0px"}}},"2":{"type":"html/suggest_product","style":{"width":"530px","padding":"0px","background-image":"none","background-color":"transparent"},"content":{},"attrs":{"data-product_type":"newest","data-max_row":1,"data-column":"4","data-auto-atc":"","character-limit":"30"},"childStyle":{".viwec-product-name":{"font-size":"15px","font-weight":"400","color":"#444444","line-height":"22px"},".viwec-product-price":{"font-size":"15px","font-weight":"400","color":"#444444","line-height":"22px"},".viwec-product-distance":{"padding":"0px 0px 0px 10px"},".viwec-product-h-distance":{}}}}}}},"4":{"props":{"style_outer":{"padding":"25px 35px","background-image":"none","background-color":"#162447","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px","width":"600px"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","padding":"0px","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px"},"content":{"text":"<p style=\"text-align: center;\"><span style=\"color: #f5f5f5; font-size: 20px;\">Get in Touch</span></p>"},"attrs":{},"childStyle":{}},"1":{"type":"html/social","style":{"width":"530px","text-align":"center","padding":"20px 0px 0px","background-image":"none"},"content":{},"attrs":{"facebook":"' . VIWEC_IMAGES . 'fb-blue-white.png","facebook_url":"#","twitter":"' . VIWEC_IMAGES . 'twi-cyan-white.png","twitter_url":"#","instagram":"' . VIWEC_IMAGES . 'ins-white-color.png","instagram_url":"#","youtube":"' . VIWEC_IMAGES . 'yt-color-white.png","youtube_url":"","linkedin":"' . VIWEC_IMAGES . 'li-color-white.png","linkedin_url":"","whatsapp":"' . VIWEC_IMAGES . 'wa-color-white.png","whatsapp_url":"","direction":"","data-width":""},"childStyle":{}},"2":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","padding":"20px 0px","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px"},"content":{"text":"<p style=\"text-align: center;\"><span style=\"color: #f5f5f5; font-size: 12px;\">This email was sent by : <span style=\"color: #ffffff;\"><a style=\"color: #ffffff;\" href=\"{admin_email}\">{admin_email}</a></span></span></p>\n<p style=\"text-align: center;\"><span style=\"color: #f5f5f5; font-size: 12px;\">For any questions please send an email to <span style=\"color: #ffffff;\"><a style=\"color: #ffffff;\" href=\"{admin_email}\">{admin_email}</a></span></span></p>"},"attrs":{},"childStyle":{}},"3":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","padding":"0px","border-color":"#444444","border-style":"solid","border-width":"0px","border-radius":"0px"},"content":{"text":"<p style=\"text-align: center;\"><span style=\"color: #f5f5f5;\"><span style=\"color: #f5f5f5;\"><span style=\"font-size: 12px;\"><a style=\"color: #f5f5f5;\" href=\"#\">Privacy Policy</a>&nbsp; |&nbsp; <a style=\"color: #f5f5f5;\" href=\"#\">Help Center</a></span></span></span></p>"},"attrs":{},"childStyle":{}}}}}}}}'
			]
		];

		return $samples;
	}

	public function register_email_sample_subject( $subjects ) {
		$subjects['wtypc_coupon_email'] = __( 'Coupon gift for your order!', 'woocommerce-thank-you-page-customizer' );

		return $subjects;
	}

	public function register_email_type( $types ) {
		$types['wtypc_coupon_email'] = array(
			'name'       => esc_html__( 'WooCommerce Thank You Page Customizer', 'woocommerce-thank-you-page-customizer' ),
			'hide_rules' => array( 'country', 'category', 'min_order', 'max_order' ),
		);

		return $types;
	}
}