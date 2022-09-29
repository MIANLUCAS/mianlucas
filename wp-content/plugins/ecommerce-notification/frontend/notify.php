<?php

/**
 * Class ECOMMERCE_NOTIFICATION_Frontend_Notify
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ECOMMERCE_NOTIFICATION_Frontend_Notify {
	protected $settings;

	public function __construct() {
		$this->settings = new ECOMMERCE_NOTIFICATION_Admin_Settings();
		add_action( 'wp_enqueue_scripts', array( $this, 'init_scripts' ),9999999999 );

		add_action( 'wp_ajax_nopriv_woonotification_get_product', array( $this, 'product_html' ) );
		add_action( 'wp_ajax_woonotification_get_product', array( $this, 'product_html' ) );
	}

	public function product_html() {
		$enable = $this->settings->get_field( 'enable' );
		if ( $enable ) {
			$products = $this->get_product();
			if ( is_array( $products ) && count( $products ) ) {
				echo json_encode( $products );
				die;
			}
		}
		echo json_encode( array() );
		die;
	}

	public function wp_footer() {
		$sound_enable = $this->settings->get_field( 'sound_enable' );
		$sound        = $this->settings->get_field( 'sound' );

		echo $this->show_product();

		if ( $sound_enable ) {
			?>
            <audio id="ecommerce-notification-audio">
                <source src="<?php echo esc_url( ECOMMERCE_NOTIFICATION_SOUNDS_URL . $sound ) ?>">
            </audio>
			<?php
		}
	}

	public function show_product() {
		$image_position = $this->settings->get_field( 'image_position' );
		$position       = $this->settings->get_field( 'position' );

		$background_image = $this->settings->get_field( 'background_image' );
		$class[]          = $image_position ? 'img-right' : '';

		switch ( $position ) {
			case  1:
				$class[] = 'bottom_right';
				break;
			case  2:
				$class[] = 'top_left';
				break;
			case  3:
				$class[] = 'top_right';
				break;
		}
		if ( $background_image ) {
			$class[] = 'wn-background-template-type-2';
			$class[] = 'wn-extended';
			$class[] = 'wn-' . $background_image;
		}
		$item_id = 0;
		if ( is_single() ) {
			global $post;
			$post_type = $this->settings::get_field( 'post_type' );
			$item_id   = $post->post_type == $post_type ? $post->ID : '';
		}
		ob_start();

		?>
        <div id="message-purchased" class=" <?php echo implode( ' ', $class ) ?>" style="display: none;" data-product_id="<?php echo esc_attr( $item_id ); ?>">

        </div>
		<?php


		return ob_get_clean();
	}

	protected function get_product() {
		$prefix                = ecommerce_notification_prefix();
		$enable_single_product = $this->settings::get_field( 'enable_single_product' );
		$product_thumb         = $this->settings::get_field( 'product_sizes', 'thumbnail' );
		$item_id               = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT );
		$post_type             = $this->settings::get_field( 'post_type' );
		$non_ajax              = $this->settings::get_field( 'non_ajax' );
		if ( $enable_single_product ) {
			if ( is_single() ) {
				global $post;
				$item_id = $post->post_type == $post_type ? $post->ID : '';
			}
			if ( $item_id ) {
				$products = get_transient( $prefix . 'wn_product_child' . $item_id );
				if ( is_array( $products ) && count( $products ) ) {
					return $products;
				}
				$item     = get_post( $item_id );
				$p_id     = $item->ID;
				$link     = get_permalink( $p_id );
				$link     = wp_nonce_url( $link, 'wocommerce_notification_click', 'link' );
				$products = array(
					array(
						'title' => get_the_title( $p_id ),
						'url'   => $link,
						'thumb' => has_post_thumbnail( $p_id ) ? get_the_post_thumbnail_url( $p_id, $product_thumb ) : '',
					)
				);
				if ( $non_ajax ) {
					set_transient( $prefix . 'wn_product_child' . $item_id, $products, 3600 );
				}

				return $products;
			}
		}
		/*Params from Settings*/
		$products = get_transient( $prefix );
		if ( is_array( $products ) && count( $products ) ) {
			return $products;
		} else {
			$products = array();
		}
		$archive_products = $this->settings::get_field( 'archive_products' );
		$archive_products = is_array( $archive_products ) ? $archive_products : array();
		if ( count( array_filter( $archive_products ) ) < 1 ) {
			$args = array(
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'posts_per_page' => 50,
				'orderby'        => 'date',
				'order'          => 'DESC'
			);

		} else {
			$args = array(
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'posts_per_page' => '50',
				'orderby'        => 'date',
				'post__in'       => $archive_products,
				'order'          => 'DESC'
			);
		}
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$item_id     = get_the_ID();
				$link        = get_permalink( $item_id );
				$link        = wp_nonce_url( $link, 'wocommerce_notification_click', 'link' );
				$product_tmp = array(
					'title' => get_the_title( $item_id ),
					'url'   => $link,
					'thumb' => has_post_thumbnail( $item_id ) ? get_the_post_thumbnail_url( $item_id, $product_thumb ) : '',
				);
				$products[]  = $product_tmp;
			}
			// Reset Post Data
			wp_reset_postdata();
		}
		if ( count( $products ) ) {
			if ( $non_ajax ) {
				set_transient( $prefix, $products, 3600 );
			}

			return $products;
		} else {
			return false;
		}
	}

	public function init_scripts() {
		$enable = $this->settings->get_field( 'enable' );
		if ( ! $enable ) {
			return;
		}
		$is_home     = $this->settings->get_field( 'is_home' );
		$is_checkout = $this->settings->get_field( 'is_checkout' );
		$is_cart     = $this->settings->get_field( 'is_cart' );
		/*Conditional tags*/
		$logic_value = $this->settings->get_field( 'conditional_tags' );
		/*Assign page*/
		if ( $is_home && (is_home() || is_front_page()) ) {
			return;
		}
		if ( $is_checkout && is_checkout() ) {
			return;
		}
		if ( $is_cart && is_cart() ) {
			return;
		}
		if ( $logic_value ) {
			if ( stristr( $logic_value, "return" ) === false ) {
				$logic_value = "return (" . $logic_value . ");";
			}
			if ( ! eval( $logic_value ) ) {
				return;
			}
		}
		$detect = new VillaTheme_Mobile_Detect;

		// Any mobile device (phones or tablets).
		if ( $detect->isMobile() && ! $this->settings::get_field( 'enable_mobile' ) ) {
			return false;
		}
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );
		if ( WP_DEBUG ) {
			wp_enqueue_style( 'ecommerce-notification', ECOMMERCE_NOTIFICATION_CSS . 'ecommerce-notification.css', array(), ECOMMERCE_NOTIFICATION_VERSION );

			wp_enqueue_script( 'ecommerce-notification', ECOMMERCE_NOTIFICATION_JS . 'ecommerce-notification.js', array( 'jquery' ), ECOMMERCE_NOTIFICATION_VERSION );
		} else {
			wp_enqueue_style( 'ecommerce-notification', ECOMMERCE_NOTIFICATION_CSS . 'ecommerce-notification.min.css', array(), ECOMMERCE_NOTIFICATION_VERSION );

			wp_enqueue_script( 'ecommerce-notification', ECOMMERCE_NOTIFICATION_JS . 'ecommerce-notification.min.js', array( 'jquery' ), ECOMMERCE_NOTIFICATION_VERSION );
		}
		if ( $this->settings->get_field( 'background_image' ) ) {
			wp_enqueue_style( 'ecommerce-notification-templates', ECOMMERCE_NOTIFICATION_CSS . 'ecommerce-notification-templates.css', array(), ECOMMERCE_NOTIFICATION_VERSION );
		}
		$prefix        = ecommerce_notification_prefix();
		$non_ajax      = $this->settings::get_field( 'non_ajax' );
		$options_array = get_transient( $prefix . '_head' );
		if ( ! is_array( $options_array ) || empty( $options_array ) ) {
			$options_array     = array(
				'loop'                  => $this->settings::get_field( 'loop' ),
				'display_time'          => $this->settings::get_field( 'display_time' ),
				'next_time'             => $this->settings::get_field( 'next_time' ),
				'notification_per_page' => $this->settings::get_field( 'notification_per_page' ),
				'display_effect'        => $this->settings::get_field( 'message_display_effect' ),
				'hidden_effect'         => $this->settings::get_field( 'message_hidden_effect' ),
				'show_close'            => $this->settings::get_field( 'show_close_icon' ),
			);
			$message_purchased = $this->settings::get_field( 'message_purchased' );
			if ( ! is_array( $message_purchased ) ) {
				$message_purchased = array( $message_purchased );
			}
			$options_array['messages']           = $message_purchased;
			$options_array['message_custom']     = $this->settings::get_field( 'custom_shortcode' );
			$options_array['message_number_min'] = $this->settings::get_field( 'min_number', 0 );
			$options_array['message_number_max'] = $this->settings::get_field( 'max_number', 0 );
			$options_array['time']               = $this->settings::get_field( 'virtual_time' );
			$options_array['detect']             = $this->settings::get_field( 'country' );
			$virtual_name                        = $this->settings::get_field( 'virtual_name' );
			$virtual_name                        = $virtual_name ? explode( "\n", $virtual_name ) : '';
			if ( is_array( $virtual_name ) && count( $virtual_name ) ) {
				$options_array['names'] = array_map( 'base64_encode', $virtual_name );
			}
			if ( $detect ) {
				/*Change city*/
				$cities = $this->settings::get_field( 'virtual_city' );
				$cities = $cities ? explode( "\n", $cities ) : '';
				if ( is_array( $cities ) && count( $cities ) ) {
					$options_array['cities'] = array_map( 'base64_encode', $cities );
				}
				$options_array['country'] = $this->settings::get_field( 'virtual_country' );
			}
			if ( $non_ajax ) {
				set_transient( $prefix . '_head', $options_array, 86400 );
			}
		}

		$options_array = array_merge( array(
			'str_about'   => __( 'About', 'ecommerce-notification' ),
			'str_ago'     => __( 'ago', 'ecommerce-notification' ),
			'str_day'     => __( 'day', 'ecommerce-notification' ),
			'str_days'    => __( 'days', 'ecommerce-notification' ),
			'str_hour'    => __( 'hour', 'ecommerce-notification' ),
			'str_hours'   => __( 'hours', 'ecommerce-notification' ),
			'str_min'     => __( 'minute', 'ecommerce-notification' ),
			'str_mins'    => __( 'minutes', 'ecommerce-notification' ),
			'str_secs'    => __( 'secs', 'ecommerce-notification' ),
			'str_few_sec' => __( 'a few seconds', 'ecommerce-notification' ),
		),
			$options_array );
		/*Notification options*/
		$initial_delay        = $this->settings::get_field( 'initial_delay' );
		$initial_delay_random = $this->settings::get_field( 'initial_delay_random' );
		if ( $initial_delay_random ) {
			$initial_delay_min = $this->settings::get_field( 'initial_delay_min' );
			$initial_delay     = rand( $initial_delay_min, $initial_delay );
		}
		$options_array['initial_delay'] = $initial_delay;
		/*Load products*/
		if ( $non_ajax ) {
			$options_array['ajax_url'] = '';
			$products                  = $this->get_product();
		} else {
			$options_array['ajax_url'] = admin_url( 'admin-ajax.php' );
			$products                  = array();
		}
		if ( is_array( $products ) && count( $products ) ) {
			$options_array['products'] = $products;
		}
		wp_localize_script( 'ecommerce-notification', '_vi_ecommerce_notification_params', $options_array );
		$highlight_color  = $this->settings::get_field( 'highlight_color' );
		$text_color       = $this->settings::get_field( 'text_color' );
		$background_color = $this->settings::get_field( 'background_color' );
		$custom_css       = "
                #message-purchased{
                        background-color: {$background_color} !important;
                        color:{$text_color} !important;
                }
                 #message-purchased a{
                        color:{$highlight_color} !important;
                }
                ";
		$background_image = $this->settings::get_field( 'background_image' );
		if ( $background_image ) {
			$border_radius        = 0;
			$background_image_url = vi_ecommerce_notification_background_images( $background_image );

			$custom_css .= "#message-purchased.wn-extended::before{
				background-image: url('{$background_image_url}');  
				 border-radius:{$border_radius};
			}";
		}
		$custom_css .= $this->settings::get_field( 'custom_css', '' );
		wp_add_inline_style( 'ecommerce-notification', $custom_css );
	}
}