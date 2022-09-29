<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WTYPC_FUNCTIONS {
	public static $params;

	/**
	 * WTYPC_FUNCTIONS constructor.
	 * Init setting
	 */
	public function __construct() {
		self::$params = new VI_WOOCOMMERCE_THANK_YOU_PAGE_DATA();
	}

	public static function email_style( $css ) {
		$css .= '.woo-thank-you-page-customizer-coupon-input{line-height:46px;display:block;text-align: center;font-size: 24px;width: 100%;height: 46px;vertical-align: middle;margin: 0;color:' . self::$params->get_params( 'coupon_code_color' ) . ';background-color:' . self::$params->get_params( 'coupon_code_bg_color' ) . ';border-width:' . self::$params->get_params( 'coupon_code_border_width' ) . 'px;border-style:' . self::$params->get_params( 'coupon_code_border_style' ) . ';border-color:' . self::$params->get_params( 'coupon_code_border_color' ) . ';}';

		return $css;
	}

	public static function send_email( $user_email, $coupon_code, $coupon_date_expires = '', $last_valid_date = '', $coupon_amount = '', $shortcodes = array(),$language='', $return = false ) {
		$headers             = "Content-Type: text/html\r\n";
		$mailer  = WC()->mailer();
		$email   = new WC_Email();
		if (self::$params::email_template_customizer_active() && ($email_template=  self::$params->get_params('email_template',$language))){
			$viwec_email  = new VIWEC_Render_Email_Template( array( 'template_id' => $email_template) );
			$subject = $viwec_email->get_subject();
			$subject = str_replace( array(
				'{wtypc_coupon_code}',
				'{wtypc_coupon_date_expires}',
				'{wtypc_last_valid_date}',
				'{wtypc_coupon_amount}'
			), array( $coupon_code, $coupon_date_expires, $last_valid_date, $coupon_amount ) ,$subject );
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
		}else {
			$content             = stripslashes( self::$params->get_params( 'coupon_email_content' ,$language) );
			$subject             = stripslashes( self::$params->get_params( 'coupon_email_subject' ,$language) );
			$heading             = stripslashes( self::$params->get_params( 'coupon_email_heading',$language ) );
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
			add_filter( 'woocommerce_email_styles', array( __CLASS__, 'email_style' ) );
			$content = $email->style_inline( $mailer->wrap_message( $heading, $content ) );
		}
		$send    = $email->send( $user_email, $subject, $content, $headers, array() );
		remove_filter( 'woocommerce_email_styles', array( __CLASS__, 'email_style' ) );
		if ( $return ) {
			return $send;
		}
	}
	public static function woocommerce_valid_order_statuses_for_order_again( $order_status ) {
		self::$params = new VI_WOOCOMMERCE_THANK_YOU_PAGE_DATA();
		$status = self::$params->get_params( 'order_status' );
		if ( is_array( $status ) && count( $status ) ) {
			$order_status = array();
			foreach ( $status as $key => $value ) {
				$order_status[] = str_replace( 'wc-', '', $value );
			}
		}

		return $order_status;
	}
}
