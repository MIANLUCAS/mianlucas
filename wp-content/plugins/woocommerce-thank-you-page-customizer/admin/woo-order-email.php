<?php

if ( ! defined( 'ABSPATH' ) ) {

    exit;

}


class VI_WOOCOMMERCE_THANK_YOU_PAGE_Admin_Woo_Order_Email {
    protected $settings;

    public function __construct() {
        $this->settings = new VI_WOOCOMMERCE_THANK_YOU_PAGE_DATA();
        add_action( 'woocommerce_email_order_meta', array( $this, 'include_coupon_info' ), 20, 4 );

    }

    public function include_coupon_info( $order, $sent_to_admin, $plain_text, $email ) {
        $email_woo_enable = $this->settings->get_params( 'coupon_woo_email_enable' );
        $email_woo_status = $this->settings->get_params( 'coupon_woo_email_status' );
        if ( ! $email_woo_enable ) {
            return ;
        }
        if ( $email_woo_status && is_array( $email_woo_status ) && in_array( $email->id, $email_woo_status ) ) {
            $order_id = $order->get_id();
            $coupon_code = get_post_meta($order_id, 'woo_thank_you_page_coupon_code', true);
            if (!$coupon_code){
                return ;
            }
            $coupon = new WC_Coupon($coupon_code);
            if (!$coupon){
                return;
            }
            $usage_count  = $coupon->get_usage_count();
            $usage_limit  = $coupon->get_usage_limit();
            $today        = strtotime( 'today' );
            $date_expires = $coupon->get_date_expires();
            $expires      = __( 'Never', 'woocommerce-thank-you-page-customizer' );
            $css='';
            if ( $coupon->get_discount_type() == 'percent' ) {
                $coupon_amount = $coupon->get_amount() . '%';
            } else {
                $coupon_amount = wc_price( $coupon->get_amount() );
            }
            if ( $date_expires ) {
                $expires = $date_expires->date_i18n( 'F d, Y' );
                $expires .= ', GMT '.get_option( 'gmt_offset' );
                if ( $date_expires->getTimestamp() <= $today ) {
                    $css     = 'color:red;';
                    $expires = '<span style="' . $css . '">' . $expires . '</span>';
                }
                if ( $usage_count == $usage_limit ) {
                    $css = 'color:red;';
                }
            }
            $coupon_info = sprintf( __( '(%s - Expires: %s)', 'woocommerce-thank-you-page-customizer' ), $coupon_amount, $expires, 'woocommerce-thank-you-page-customizer' );

            ob_start();
            ?>
            <h2 class="email-upsell-title"><?php esc_html_e( 'Coupon gift', 'woocommerce-thank-you-page-customizer' ) ?></h2>
            <p>
                <span class="woocommerce-thank-you-page-orders-coupon"
                      style="<?php echo $css ?>"><?php echo strtoupper( $coupon_code ) ?></span>
                <?php
                if ( isset( $coupon_info ) ) {
                    ?>
                    <span><?php echo $coupon_info ?></span>
                    <?php
                }
                ?>
            </p>
            <?php
            $html = ob_get_clean();
            echo ent2ncr( $html );
        }
    }

}