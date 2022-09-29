<?php
/*
Class Name: VI_WOOCOMMERCE_THANK_YOU_PAGE_Admin_Admin
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2018 villatheme.com. All rights reserved.
*/
if (!defined('ABSPATH')) {
    exit;
}

class VI_WOOCOMMERCE_THANK_YOU_PAGE_Admin_Designa1
{
    protected $settings;
    protected $order_id;
    protected $key;
    protected $prefix;
    protected $text_editor;
    protected $shortcodes;

    public function __construct()
    {
        $this->settings = new VI_WOOCOMMERCE_THANK_YOU_PAGE_DATA();
        $this->prefix = 'woocommerce-thank-you-page-';
        $this->shortcodes = array(
            'order_number' => '',
            'order_status' => '',
            'order_date' => '',
            'order_total' => '',
            'order_subtotal' => '',
            'items_count' => '',
            'payment_method' => '',

            'shipping_method' => '',
            'shipping_address' => '',
            'formatted_shipping_address' => '',

            'billing_address' => '',
            'formatted_billing_address' => '',
            'billing_country' => '',
            'billing_city' => '',

            'billing_first_name' => '',
            'billing_last_name' => '',
            'formatted_billing_full_name' => '',
            'billing_email' => '',

            'shop_title' => '',
            'home_url' => '',
            'shop_url' => '',
            'store_address' => '',
        );
        add_action('customize_register', array($this, 'design_option_customizer'));
        add_action('wp_print_styles', array($this, 'customize_controls_print_styles'));
        add_action('customize_preview_init', array($this, 'customize_preview_init'));
        add_action('customize_controls_print_scripts', array($this, 'customize_controls_print_scripts'), 99);
        add_action('customize_controls_enqueue_scripts', array($this, 'customize_controls_enqueue_scripts'), 30);
        add_action('wp_ajax_woo_thank_you_page_get_available_shortcodes', array($this, 'get_available_shortcodes'));
    }

    public function get_available_shortcodes()
    {
        $order_id = isset($_POST['order_id']) ? sanitize_text_field($_POST['order_id']) : '';
        $order = wc_get_order($order_id);
        if ($order) {
            $shortcodes = array(
                'order_number' => $order_id,
                'order_status' => $order->get_status(),
                'order_date' => $order->get_date_created() ? $order->get_date_created()->date_i18n('F d, Y') : '',
                'order_total' => $order->get_formatted_order_total(),
                'order_subtotal' => $order->get_subtotal_to_display(),
                'items_count' => $order->get_item_count(),
                'payment_method' => $order->get_payment_method_title(),

                'shipping_method' => $order->get_shipping_method(),
                'shipping_address' => $order->get_shipping_address_1(),
                'formatted_shipping_address' => $order->get_formatted_shipping_address(),

                'billing_address' => $order->get_billing_address_1(),
                'formatted_billing_address' => $order->get_formatted_billing_address(),
                'billing_country' => $order->get_billing_country(),
                'billing_city' => $order->get_billing_city(),

                'billing_first_name' => ucwords($order->get_billing_first_name()),
                'billing_last_name' => ucwords($order->get_billing_last_name()),
                'formatted_billing_full_name' => ucwords($order->get_formatted_billing_full_name()),
                'billing_email' => $order->get_billing_email(),

                'shop_title' => get_bloginfo(),
                'home_url' => home_url(),
                'shop_url' => get_option('woocommerce_shop_page_id', '') ? get_page_link(get_option('woocommerce_shop_page_id')) : '',

            );
            $country = new WC_Countries();
            $store_address = $country->get_base_address() ? $country->get_base_address() : $country->get_base_address_2();
            if ($country->get_base_city()) {
                $store_address .= ', ' . $country->get_base_city();
            }
            if ($country->get_base_state()) {
                $store_address .= ', ' . $country->get_base_state();
            }
            if ($country->get_base_country()) {
                $store_address .= ', ' . $country->get_base_country();
            }
            $shortcodes['store_address'] = $store_address;
            wp_send_json(array(
                'shortcodes' => $shortcodes
            ));
        }
        die;
    }

    public function customize_controls_print_styles()
    {
        if (!is_customize_preview()) {
            return;
        }
        /*order confirmation*/
        $this->add_preview_style('order_confirmation_bg', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'background-color');
        $this->add_preview_style('order_confirmation_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'padding', 'px');
        $this->add_preview_style('order_confirmation_border_radius', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-radius', 'px');
        $this->add_preview_style('order_confirmation_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-width', 'px');
        $this->add_preview_style('order_confirmation_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-style');
        $this->add_preview_style('order_confirmation_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-color');

        $this->add_preview_style('order_confirmation_vertical_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'border-right-width', 'px');
        $this->add_preview_style('order_confirmation_vertical_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'border-right-style');
        $this->add_preview_style('order_confirmation_vertical_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'border-right-color');

        $this->add_preview_style('order_confirmation_horizontal_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:last-child) .woocommerce-thank-you-page-order_confirmation-title div,.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:last-child) .woocommerce-thank-you-page-order_confirmation-value div', 'border-bottom-width', 'px');
        $this->add_preview_style('order_confirmation_horizontal_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:last-child) .woocommerce-thank-you-page-order_confirmation-title div,.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:last-child) .woocommerce-thank-you-page-order_confirmation-value div', 'border-bottom-style');
        $this->add_preview_style('order_confirmation_horizontal_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:last-child) .woocommerce-thank-you-page-order_confirmation-title div,.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:last-child) .woocommerce-thank-you-page-order_confirmation-value div', 'border-bottom-color');

        $this->add_preview_style('order_confirmation_header_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'color');
        $this->add_preview_style('order_confirmation_header_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'background-color');
        $this->add_preview_style('order_confirmation_header_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'font-size', 'px');
        $this->add_preview_style('order_confirmation_header_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'text-align');

        $this->add_preview_style('order_confirmation_title_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'color');
        $this->add_preview_style('order_confirmation_title_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'background-color');
        $this->add_preview_style('order_confirmation_title_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'font-size', 'px');
        $this->add_preview_style('order_confirmation_title_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'text-align');

        $this->add_preview_style('order_confirmation_value_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'color');
        $this->add_preview_style('order_confirmation_value_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'background-color');
        $this->add_preview_style('order_confirmation_value_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'font-size', 'px');
        $this->add_preview_style('order_confirmation_value_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'text-align');

        /*order details*/
        $this->add_preview_style('order_details_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'color');
        $this->add_preview_style('order_details_bg', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'background-color');
        $this->add_preview_style('order_details_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'padding', 'px');
        $this->add_preview_style('order_details_border_radius', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'border-radius', 'px');
        $this->add_preview_style('order_details_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'border-width', 'px');
        $this->add_preview_style('order_details_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'border-style');
        $this->add_preview_style('order_details_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'border-color');

        $this->add_preview_style('order_details_horizontal_width', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total .woocommerce-thank-you-page-order_details__detail:last-child,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_items', 'border-top-width', 'px');
        $this->add_preview_style('order_details_horizontal_style', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total .woocommerce-thank-you-page-order_details__detail:last-child,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_items', 'border-top-style');
        $this->add_preview_style('order_details_horizontal_color', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total .woocommerce-thank-you-page-order_details__detail:last-child,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_items', 'border-top-color');

        $this->add_preview_style('order_details_header_color', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', 'color');
        $this->add_preview_style('order_details_header_bg_color', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', 'background-color');
        $this->add_preview_style('order_details_header_font_size', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', 'font-size', 'px');
        $this->add_preview_style('order_details_header_text_align', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', 'text-align');

        $this->add_preview_style('order_details_product_image_width', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-title a.woocommerce-thank-you-page-order-item-image-wrap', 'width', 'px');

        /*customer information*/
        $this->add_preview_style('customer_information_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'color');
        $this->add_preview_style('customer_information_bg', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'background-color');
        $this->add_preview_style('customer_information_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'padding', 'px');
        $this->add_preview_style('customer_information_border_radius', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-radius', 'px');
        $this->add_preview_style('customer_information_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-width', 'px');
        $this->add_preview_style('customer_information_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-style');
        $this->add_preview_style('customer_information_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-color');

        $this->add_preview_style('customer_information_vertical_width', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address .woocommerce-thank-you-page-customer_information__shipping_address', 'border-left-width', 'px');
        $this->add_preview_style('customer_information_vertical_style', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address .woocommerce-thank-you-page-customer_information__shipping_address', 'border-left-style');
        $this->add_preview_style('customer_information_vertical_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address .woocommerce-thank-you-page-customer_information__shipping_address', 'border-left-color');

        $this->add_preview_style('customer_information_header_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', 'color');
        $this->add_preview_style('customer_information_header_bg_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', 'background-color');
        $this->add_preview_style('customer_information_header_font_size', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', 'font-size', 'px');
        $this->add_preview_style('customer_information_header_text_align', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', 'text-align');

        $this->add_preview_style('customer_information_address_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', 'color');
        $this->add_preview_style('customer_information_address_bg_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', 'background-color');
        $this->add_preview_style('customer_information_address_font_size', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', 'font-size', 'px');
        $this->add_preview_style('customer_information_address_text_align', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', 'text-align');

        /*social icons*/
        $this->add_preview_style('social_icons_header_color', '.woocommerce-thank-you-page-social_icons__container .woocommerce-thank-you-page-social_icons__header', 'color');
        $this->add_preview_style('social_icons_header_font_size', '.woocommerce-thank-you-page-social_icons__container .woocommerce-thank-you-page-social_icons__header', 'font-size', 'px');
        $this->add_preview_style('social_icons_align', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials', 'text-align');
        $this->add_preview_style('social_icons_space', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials li:not(:last-child)', 'margin-right', 'px');
        $this->add_preview_style('social_icons_size', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials li .wtyp-social-button span', 'font-size', 'px');
        $this->add_preview_style('social_icons_facebook_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-facebook-follow .wtyp-social-button span:before', 'color');
        $this->add_preview_style('social_icons_twitter_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-twitter-follow .wtyp-social-button span:before', 'color');
        $this->add_preview_style('social_icons_pinterest_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-pinterest-follow .wtyp-social-button span:before', 'color');
        $this->add_preview_style('social_icons_instagram_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-instagram-follow .wtyp-social-button span:before', 'color');
        $this->add_preview_style('social_icons_dribbble_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-dribbble-follow .wtyp-social-button span:before', 'color');
        $this->add_preview_style('social_icons_tumblr_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-tumblr-follow .wtyp-social-button span:before', 'color');
        $this->add_preview_style('social_icons_google_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-google-follow .wtyp-social-button span:before', 'color');
        $this->add_preview_style('social_icons_vkontakte_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-vkontakte-follow .wtyp-social-button span:before', 'color');
        $this->add_preview_style('social_icons_linkedin_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-linkedin-follow .wtyp-social-button span:before', 'color');
        $this->add_preview_style('social_icons_youtube_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-youtube-follow .wtyp-social-button span:before', 'color');

        /*thank you message*/
        $this->add_preview_style('thank_you_message_color', '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail', 'color');
        $this->add_preview_style('thank_you_message_padding', '.woocommerce-thank-you-page-thank_you_message__container', 'padding', 'px');
        $this->add_preview_style('thank_you_message_text_align', '.woocommerce-thank-you-page-thank_you_message__container', 'text-align');
        $this->add_preview_style('thank_you_message_header_font_size', '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail .woocommerce-thank-you-page-thank_you_message-header', 'font-size', 'px');
        $this->add_preview_style('thank_you_message_message_font_size', '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail .woocommerce-thank-you-page-thank_you_message-message', 'font-size', 'px');

        /*coupon*/
        $this->add_preview_style('coupon_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container', 'text-align');
        $this->add_preview_style('coupon_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container', 'padding', 'px');
        $this->add_preview_style('coupon_message_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__message', 'color');
        $this->add_preview_style('coupon_message_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__message', 'font-size', 'px');
        $this->add_preview_style('coupon_code_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'color');
        $this->add_preview_style('coupon_code_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'background-color');
        $this->add_preview_style('coupon_code_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'border-width', 'px');
        $this->add_preview_style('coupon_code_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'border-style');
        $this->add_preview_style('coupon_code_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'border-color');
        /*google map*/
        if ($this->get_params('google_map_width')) {
            $this->add_preview_style('google_map_width', '#woocommerce-thank-you-page-google-map', 'width', 'px');

        } else {
            ?>
            <style type="text/css" id="<?php echo $this->set('preview-google-map-width') ?>">
                #woocommerce-thank-you-page-google-map {
                    width: 100%;
                }
            </style>
            <?php
        }
        $this->add_preview_style('google_map_height', '#woocommerce-thank-you-page-google-map', 'height', 'px');
        ?>
        <style type="text/css" id="<?php echo $this->set('coupon-scissors-color-css') ?>">
            .woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-wrap:before {
                color: <?php echo $this->set( 'coupon_scissors_color' ) ?>;
            }
        </style>
        <style type="text/css"
               id="<?php echo $this->set('preview-custom-css') ?>"><?php echo $this->get_params('custom_css') ?></style>
        <?php
        /*bing map*/
        if ($this->get_params('bing_map_width')) {
            $this->add_preview_style('bing_map_width', '#woocommerce-thank-you-page-bing-map', 'width', 'px');

        } else {
            ?>
            <style type="text/css" id="<?php echo $this->set('preview-bing-map-width') ?>">
                #woocommerce-thank-you-page-bing-map {
                    width: 100%;
                }
            </style>
            <?php
        }
        $this->add_preview_style('bing_map_height', '#woocommerce-thank-you-page-bing-map', 'height', 'px');
    }

    public function customize_controls_enqueue_scripts()
    {
        if (!wp_script_is('jquery-vi_flexslider')) {
            wp_enqueue_style('woocommerce-thank-you-page-jquery-vi_flexslider', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'vi_flexslider.min.css', array());
            wp_enqueue_script('jquery-vi_flexslider', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'jquery.vi_flexslider.js', array('jquery'), '', true);
        }
        wp_enqueue_style('woocommerce-thank-you-page-social-icons', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'social_icons.css', array(), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION);
        wp_enqueue_style('woocommerce-thank-you-page-available-components-icons', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'available-components-icons.css', array(), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION);
        wp_enqueue_style('woocommerce-thank-you-page-customize-preview-style', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'customize-preview.css', array(), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION);
        wp_enqueue_style('woocommerce-thank-you-page-nav-icons', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'wtypc_nav_icons.css', array(), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION);
    }


    public function customize_preview_init()
    {
        if (isset($_REQUEST['key'])) {
            $this->key = wc_clean($_REQUEST['key']);
            $this->order_id = wc_get_order_id_by_order_key($this->key);
        }
        if (!wp_script_is('jquery-vi_flexslider')) {
            wp_enqueue_style('woocommerce-thank-you-page-jquery-vi_flexslider', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'vi_flexslider.min.css', array());
            wp_enqueue_script('jquery-vi_flexslider', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'jquery.vi_flexslider.js', array('jquery'), '', true);
        }
//	    $theme = wp_get_theme()->get_stylesheet();
	    wp_enqueue_style('woocommerce-thank-you-page-select2', VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS . 'select2.min.css');
	    wp_enqueue_script('woocommerce-thank-you-page-select2', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'select2.js', array('jquery'), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION);
	    wp_enqueue_script( 'woocommerce-thank-you-page-customize-preview-js', VI_WOOCOMMERCE_THANK_YOU_PAGE_JS . 'customize-preview.js', array(
		    'jquery',
		    'customize-preview',
		    'woocommerce-thank-you-page-select2',
	    ), VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION, true );
        $google_map_styles = array(
            'ultra-light-with-labels' => '[
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
            'subtle-grayscale' => '[
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
            'shades-of-grey' => '[
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
            'blue-water' => '[
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
            'wy' => '[
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
            'black-and-white' => '[
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
            'light-dream' => '[
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
            'blue-essence' => '[
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
            'pale-dawn' => '[
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
            'unsaturated-browns' => '[
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
            'midnight-commander' => '[
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
            'light-monochrome' => '[
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
            'light-gray' => '[
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
        $google_map_style = $this->get_params('google_map_style');
        $map_styles = '';
        if ($google_map_style == 'custom') {
            $map_styles = $this->get_params('google_map_custom_style');
        } elseif (isset($google_map_styles[$google_map_style])) {
            $map_styles = $google_map_styles[$google_map_style];
        }
        $order = wc_get_order($this->order_id);
        $google_map_address = $this->get_params('google_map_address');
        if ($order) {
            $this->shortcodes['order_number'] = $this->order_id;
            $this->shortcodes['order_status'] = $order->get_status();
            $this->shortcodes['order_date'] = $order->get_date_created() ? $order->get_date_created()->date_i18n() : '';
            $this->shortcodes['order_total'] = $order->get_formatted_order_total();
            $this->shortcodes['order_subtotal'] = $order->get_subtotal_to_display();
            $this->shortcodes['items_count'] = $order->get_item_count();
            $this->shortcodes['payment_method'] = $order->get_payment_method_title();

            $this->shortcodes['shipping_method'] = $order->get_shipping_method();
            $this->shortcodes['formatted_shipping_address'] = $order->get_formatted_shipping_address();

            $this->shortcodes['formatted_billing_address'] = $order->get_formatted_billing_address();
            $this->shortcodes['billing_country'] = $order->get_billing_country();
            $this->shortcodes['billing_city'] = $order->get_billing_city();

            $this->shortcodes['billing_first_name'] = ucwords($order->get_billing_first_name());
            $this->shortcodes['billing_last_name'] = ucwords($order->get_billing_last_name());
            $this->shortcodes['formatted_billing_full_name'] = ucwords($order->get_formatted_billing_full_name());
            $this->shortcodes['billing_email'] = $order->get_billing_email();

            $this->shortcodes['shop_title'] = get_bloginfo();
            $this->shortcodes['home_url'] = home_url();
            $this->shortcodes['shop_url'] = get_option('woocommerce_shop_page_id', '') ? get_page_link(get_option('woocommerce_shop_page_id')) : '';
            $billing_address = WC()->countries->get_formatted_address(array(
                'address_1' => $order->get_billing_address_1(),
                'address_2' => $order->get_billing_address_2(),
                'city' => $order->get_billing_city(),
                'state' => $order->get_billing_state(),
                'country' => $order->get_billing_country(),
            ), ', ');
            $this->shortcodes['billing_address'] = ucwords($billing_address);
            $shipping_address = WC()->countries->get_formatted_address(array(
                'address_1' => $order->get_shipping_address_1(),
                'address_2' => $order->get_shipping_address_2(),
                'city' => $order->get_billing_city(),
                'state' => $order->get_billing_state(),
                'country' => $order->get_billing_country(),
            ), ', ');
            $this->shortcodes['shipping_address'] = ucwords($shipping_address);

            $country = new WC_Countries();
            $store_address = $country->get_base_address() ? $country->get_base_address() : $country->get_base_address_2();
            $store_address = WC()->countries->get_formatted_address(array(
                'address_1' => $store_address,
                'city' => $country->get_base_city(),
                'state' => $country->get_base_state(),
                'country' => $country->get_base_country(),
            ), ', ');
            $this->shortcodes['store_address'] = ucwords($store_address);
            $google_map_address = str_replace(array(
                '{store_address}',
                '{shipping_address}',
                '{billing_address}'
            ), array(
                $this->shortcodes['store_address'],
                $this->shortcodes['shipping_address'],
                $this->shortcodes['billing_address']
            ), $google_map_address);
        }
        wp_localize_script('woocommerce-thank-you-page-customize-preview-js', 'woo_thank_you_page_params', array(
            'url' => admin_url('admin-ajax.php'),
            'google_map_label' => str_replace(array(
                '{address}',
                '{store_address}',
                '{shipping_address}',
                '{billing_address}'
            ), array(
                $google_map_address,
                $this->shortcodes['store_address'],
                $this->shortcodes['shipping_address'],
                $this->shortcodes['billing_address']
            ), $this->get_params('google_map_label')),
            'google_map_api' => $this->get_params('google_map_api'),
            'google_map_marker' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . $this->get_params('google_map_marker') . '.png',
            'map_styles' => $map_styles,
            'google_map_styles' => $google_map_styles,
            'shortcodes' => $this->shortcodes,
            'markers_url' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS,
            'bing_map_api' => $this->get_params('bing_map_api'),
            'bing_map_view' => $this->get_params('bing_map_view'),
            'bing_map_marker' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . $this->get_params('bing_map_marker') . '.png',
        ));
    }

    public function customize_controls_print_scripts()
    {
        if (!is_customize_preview()) {
            return;
        }
        $order_id = $this->settings->get_params('select_order');
        ?>
        <script type="text/javascript">
            var vi_wtyp_order_id = <?php echo $order_id ? $order_id : ''; ?>;
            if (typeof wp.customize !== 'undefined') {
                wp.customize.bind('ready', function () {
                    let submenu = [
                        'thank_you_message',
                        'order_confirmation',
                        'order_details',
                        'customer_information',
                        'coupon',
                        'social_icons',
                        'google_map',
                        'order_again',
                    ];
                    jQuery('.customize-section-back').on('click', function () {
                        let id = jQuery(this).parent().parent().parent().prop('id').replace('sub-accordion-section-woo_thank_you_page_design_', '');
                        if (submenu.indexOf(id) > -1) {
                            wp.customize.section('woo_thank_you_page_design_general').expanded(true);
                        }
                    });
                    jQuery('.woocommerce-thank-you-page-available-shortcodes-shortcut').on('click', function () {
                        wp.customize.previewer.send('wtyp_shortcut_to_available_shortcodes', 'show');
                    });
                    wp.customize.previewer.bind('wtyp_open_latest_added_item', function (message) {
                        jQuery('.woocommerce-thank-you-page-latest-item').find('.woocommerce-thank-you-page-edit').click();
                        jQuery('.woocommerce-thank-you-page-item').removeClass('woocommerce-thank-you-page-latest-item');
                    });
                    wp.customize.previewer.bind('wtyp_update_text_editor', function (message) {
                        wp.customize('woo_thank_you_page_params[text_editor]').set(message);
                    });
                    wp.customize.previewer.bind('wtyp_update_products', function (message) {
                        wp.customize('woo_thank_you_page_params[products]').set(message);
                    });
                    wp.customize.previewer.bind('wtyp_handle_overlay_processing', function (message) {
                        if (message === 'show') {
                            jQuery('.woocommerce-thank-you-page-control-processing').show();
                        } else {
                            jQuery('.woocommerce-thank-you-page-control-processing').hide();
                        }
                    });
                    wp.customize.previewer.bind('wtyp_update_url', function (message) {
                        location.href = message;
                    });
                    wp.customize.previewer.bind('wtyp_shortcut_edit', function (message) {
                        wp.customize.section('woo_thank_you_page_design_' + message).expanded(true);
                    });
                    for (let i in submenu) {
                        focus_on_editing_item_send(submenu[i]);
                    }

                    function focus_on_editing_item_send(name) {
                        wp.customize.section('woo_thank_you_page_design_' + name, function (section) {
                            section.expanded.bind(function (isExpanded) {
                                if (isExpanded) {
                                    wp.customize.previewer.send('wtyp_focus_on_editing_item', 'woocommerce-thank-you-page-' + name + '__container');
                                }
                            })
                        });
                    }
                    // if (!vi_wtyp_order_id) {
                        wp.customize.section('woo_thank_you_page_design_general', function (section) {
                            section.expanded.bind(function (isExpanded) {
                                if (isExpanded) {

                                    jQuery.ajax({
                                        type: 'POST',
                                        dataType: 'json',
                                        url: '<?php echo admin_url( 'admin-ajax.php' )?>',
                                        data: {
                                            action: 'woo_thank_you_page_select_order',
                                            order_id: wp.customize('woo_thank_you_page_params[select_order]').get(),
                                        },
                                        success: function (response) {
                                            if (response && response.hasOwnProperty('url') && response.url) {
                                                wp.customize.previewer.send('wtyp_update_url', response.url);
                                            }
                                        },
                                        error: function (err) {
                                            console.log(err);
                                        }
                                    })
                                } else {
                                }
                            })
                        });
                    // }
                    /*edit item*/
                    jQuery('body').on('click', '.woocommerce-thank-you-page-container__block .woocommerce-thank-you-page-edit', function (event) {
                        event.stopPropagation();
                        let parent = jQuery(this).parent();
                        let item = parent.data()['block_item'];
                        if (item == 'text_editor' || item == 'products') {
                            let position = jQuery('.woocommerce-thank-you-page-container__block .woocommerce-thank-you-page-' + item).index(parent);
                            wp.customize.previewer.send('wtyp_shortcut_edit_' + item + '_from_section', position);
                        } else {
                            wp.customize.previewer.send('wtyp_shortcut_edit_item_from_section', 'woocommerce-thank-you-page-edit-item-shortcut[data-edit_section="' + item + '"]');
                        }
                    });
                    jQuery('.wtyp-button-update-changes-google-map').on('click', function () {
                        let address = wp.customize('woo_thank_you_page_params[google_map_address]').get();
                        wp.customize.previewer.send('wtyp_update_google_map_address', address);
                    });
                    jQuery('.wtyp-button-update-changes-bing-map').on('click', function () {
                        let address = wp.customize('woo_thank_you_page_params[bing_map_address]').get();
                        wp.customize.previewer.send('wtyp_update_bing_map_address', address);
                    });
                });
            }


            jQuery(document).ready(function ($) {
                /* Pane, enqueue w/ customize-controls dependency at customize_controls_enqueue_scripts */
                wp.customize.bind('ready', function () {
                    let submenu = [
                        'thank_you_message',
                        'order_confirmation',
                        'order_details',
                        'customer_information',
                        'coupon',
                        'social_icons',
                        'google_map',
                        'order_again',
                    ];
                    jQuery('.customize-section-back').on('click', function () {
                        let id = jQuery(this).parent().parent().parent().prop('id').replace('sub-accordion-section-woo_thank_you_page_design_', '');
                        if (submenu.indexOf(id) > -1) {
                            wp.customize.section('woo_thank_you_page_design_general').expanded(true);
                        }
                    })
                    jQuery('.woocommerce-thank-you-page-available-shortcodes-shortcut').on('click', function () {
                        wp.customize.previewer.send('wtyp_shortcut_to_available_shortcodes', 'show');
                    });
                    let url = location.href;
                    // if (!vi_wtyp_order_id) {
                        wp.customize.section('woo_thank_you_page_design_general', function (section) {
                            section.expanded.bind(function (isExpanded) {
                                if (isExpanded) {
                                    jQuery.ajax({
                                        type: 'POST',
                                        dataType: 'json',
                                        url: '<?php echo admin_url( 'admin-ajax.php' )?>',
                                        data: {
                                            action: 'woo_thank_you_page_select_order',
                                            order_id: wp.customize('woo_thank_you_page_params[select_order]').get(),
                                        },
                                        success: function (response) {
                                            if (response && response.hasOwnProperty('url') && response.url) {
                                                wp.customize.previewer.send('wtyp_update_url', response.url);
                                            }
                                        },
                                        error: function (err) {
                                            console.log(err);
                                        }
                                    })
                                } else {
                                }
                            })
                        });
                    // }
                    wp.customize.previewer.bind('wtyp_open_latest_added_item', function (message) {
                        jQuery('.woocommerce-thank-you-page-latest-item').find('.woocommerce-thank-you-page-edit').click();
                        jQuery('.woocommerce-thank-you-page-item').removeClass('woocommerce-thank-you-page-latest-item');
                    });
                    wp.customize.previewer.bind('wtyp_update_text_editor', function (message) {
                        wp.customize('woo_thank_you_page_params[text_editor]').set(message);
                    });
                    wp.customize.previewer.bind('wtyp_handle_overlay_processing', function (message) {
                        if (message === 'show') {
                            jQuery('.woocommerce-thank-you-page-control-processing').show();
                        } else {
                            jQuery('.woocommerce-thank-you-page-control-processing').hide();
                        }
                    });
                    wp.customize.previewer.bind('wtyp_update_products', function (message) {
                        wp.customize('woo_thank_you_page_params[products]').set(message);
                    });
                    wp.customize.previewer.bind('wtyp_update_url', function (message) {
                        location.href = message;
                    });
                    wp.customize.previewer.bind('wtyp_shortcut_edit', function (message) {
                        wp.customize.section('woo_thank_you_page_design_' + message).expanded(true);
                    });
                    for (let i in submenu) {
                        focus_on_editing_item_send(submenu[i]);
                    }

                    function focus_on_editing_item_send(name) {
                        wp.customize.section('woo_thank_you_page_design_' + name, function (section) {
                            section.expanded.bind(function (isExpanded) {
                                if (isExpanded) {
                                    wp.customize.previewer.send('wtyp_focus_on_editing_item', 'woocommerce-thank-you-page-' + name + '__container');
                                }
                            })
                        });
                    }

                    /*edit item*/
                    jQuery('body').on('click', '.woocommerce-thank-you-page-container__block .woocommerce-thank-you-page-edit', function (event) {
                        event.stopPropagation();
                        let parent = jQuery(this).parent();
                        let item = parent.data()['block_item'];
                        if (item == 'text_editor' || item == 'products') {
                            let position = jQuery('.woocommerce-thank-you-page-container__block .woocommerce-thank-you-page-' + item).index(parent);
                            wp.customize.previewer.send('wtyp_shortcut_edit_' + item + '_from_section', position);
                        } else {
                            wp.customize.previewer.send('wtyp_shortcut_edit_item_from_section', 'woocommerce-thank-you-page-edit-item-shortcut[data-edit_section="' + item + '"]');
                        }
                    });
                    jQuery('.wtyp-button-update-changes-google-map').on('click', function () {
                        let address = wp.customize('woo_thank_you_page_params[google_map_address]').get();
                        wp.customize.previewer.send('wtyp_update_google_map_address', address);
                    });

                    jQuery('.wtyp-button-update-changes-bing-map').on('click', function () {
                        let address = wp.customize('woo_thank_you_page_params[bing_map_address]').get();
                        wp.customize.previewer.send('wtyp_update_bing_map_address', address);
                    });
                });
            })
        </script>
        <?php
    }

    public function design_option_customizer($wp_customize)
    {
        $this->add_section_design_general($wp_customize);
        $this->add_section_design_thank_you_message($wp_customize);
        $this->add_section_design_coupon($wp_customize);
        $this->add_section_design_order_confirmation($wp_customize);
        $this->add_section_design_order_details($wp_customize);
        $this->add_section_design_customer_information($wp_customize);
        $this->add_section_design_social_icons($wp_customize);
        $this->add_section_design_google_map($wp_customize);
        $this->add_section_design_bing_map($wp_customize);
    }

    protected function add_section_design_general($wp_customize)
    {
        $wp_customize->add_section('woo_thank_you_page_design_general', array(
            'priority' => 200,
            'capability' => 'manage_options',
            'theme_supports' => '',
            'title' => __('WooCommerce Thank You Page', 'woocommerce-thank-you-page-customizer'),
        ));
        $default_order_id = '';
        $args = array(
            'post_type' => 'shop_order',
            'post_status' => 'wc-completed,wc-on-hold,wc-processing,wc-pending',
            'posts_per_page' => 20,
            'order' => 'DESC'
        );
        $the_query = new WP_Query($args);
        $select_orders = array();
        if ($this->get_params('select_order')) {
            $select_orders[$this->get_params('select_order')] = sprintf(__('Order #%s', 'woocommerce-thank-you-page-customizer'), $this->get_params('select_order'));
        }
        if ($the_query->have_posts()) {
            while ($the_query->have_posts()) {
                $the_query->the_post();
                $order_id = get_the_ID();
                if (!$default_order_id) {
                    $default_order_id = $order_id;
                }
                $select_orders[$order_id] = sprintf(__('Order #%s', 'woocommerce-thank-you-page-customizer'), $order_id);
            }
        } else {
            wp_reset_postdata();
            $args = array(
                'post_type' => 'product',
                'post_status' => 'public',
                'posts_per_page' => 1,
            );
            $the_query = new WP_Query($args);
            if ($the_query->have_posts()) {
                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    $product_id = get_the_ID();
                    $product = wc_get_product($product_id);
                    $user = wp_get_current_user();
                    $order = new WC_Order();
                    $address = array(
                        'first_name' => $user->user_firstname,
                        'last_name' => $user->user_lastname,
                        'company' => '',
                        'email' => $user->user_email,
                        'phone' => '',
                        'address_1' => 'Thai Nguyen city',
                        'address_2' => '',
                        'city' => 'Thai Nguyen',
                        'state' => '',
                        'postcode' => '25000',
                        'country' => 'VN'
                    );
                    $order->add_product($product, '2');
                    $order->set_address($address, 'billing');
                    $order->set_address($address, 'shipping');
                    $order->calculate_totals();
                    $order->set_total(0);
                    $order->update_status('completed');
                    $order->save();
                    $order_id = $order->get_id();
                    $default_order_id = $order_id;
                    $select_orders[$order_id] = sprintf(__('Order #%s', 'woocommerce-thank-you-page-customizer'), $order_id);
                    break;
                }
            }
        }
        if (!$this->settings->get_params('select_order') && $default_order_id) {
            $data = $this->settings->get_params();
            $data['select_order'] = $default_order_id;
            update_option('woo_thank_you_page_params', $data);
        }
        wp_reset_postdata();
        $wp_customize->add_setting('woo_thank_you_page_params[select_order]', array(
            'default' => $this->settings->get_default('select_order'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[select_order]', array(
            'type' => 'select',
            'priority' => 10,
            'section' => 'woo_thank_you_page_design_general',
            'label' => __('Select order to preview', 'woocommerce-thank-you-page-customizer'),
            'choices' => $select_orders,
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[blocks]', array(
            'default' => $this->settings->get_default('blocks'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'wtyp_sanitize_block',
            'sanitize_js_callback' => 'wtyp_sanitize_block',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Blocks_Control(
                $wp_customize,
                'woo_thank_you_page_params[blocks]',
                array(
                    'label' => 'Layout',
                    'section' => 'woo_thank_you_page_design_general',
                )
            )
        );

        $wp_customize->add_setting('woo_thank_you_page_params[text_editor]', array(
            'default' => $this->settings->get_default('text_editor'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'wtyp_sanitize_block',
            'sanitize_js_callback' => 'wtyp_sanitize_block',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Text_Editor_Control(
                $wp_customize,
                'woo_thank_you_page_params[text_editor]',
                array(
                    'section' => 'woo_thank_you_page_design_general',
                )
            )
        );
        $wp_customize->add_setting('woo_thank_you_page_params[products]', array(
            'default' => $this->settings->get_default('products'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'wtyp_sanitize_block',
            'sanitize_js_callback' => 'wtyp_sanitize_block',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Products_Control(
                $wp_customize,
                'woo_thank_you_page_params[products]',
                array(
                    'section' => 'woo_thank_you_page_design_general',
                )
            )
        );
        $wp_customize->add_setting('woo_thank_you_page_params[custom_css]', array(
            'default' => $this->settings->get_default('custom_css'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[custom_css]', array(
            'type' => 'textarea',
            'priority' => 10,
            'section' => 'woo_thank_you_page_design_general',
            'label' => __('Custom CSS', 'woocommerce-thank-you-page-customizer')
        ));
    }


    protected function add_section_design_order_confirmation($wp_customize)
    {

        $wp_customize->add_section('woo_thank_you_page_design_order_confirmation', array(
            'priority' => 20,
            'capability' => 'manage_options',
            'theme_supports' => '',
            'title' => __('Order Confirmation', 'woocommerce-thank-you-page-customizer'),

        ));

        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_bg]', array(
            'default' => $this->settings->get_default('order_confirmation_bg'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[order_confirmation_bg]',
                array(
                    'label' => __('Background Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_order_confirmation',
                    'settings' => 'woo_thank_you_page_params[order_confirmation_bg]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_padding]', array(
            'default' => $this->settings->get_default('order_confirmation_padding'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_confirmation_padding]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_order_confirmation',
            'label' => __('Padding(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_border_radius]', array(
            'default' => $this->settings->get_default('order_confirmation_border_radius'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_confirmation_border_radius]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_order_confirmation',
            'label' => __('Rounded Corner(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_border_width]', array(
            'default' => $this->settings->get_default('order_confirmation_border_width'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_confirmation_border_width]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_order_confirmation',
            'label' => __('Border Width(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_border_style]', array(
            'default' => $this->settings->get_default('order_confirmation_border_style'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_confirmation_border_style]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_order_confirmation', // Add a default or your own section
            'label' => __('Border Style', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'solid' => __('Solid', 'woocommerce-thank-you-page-customizer'),
                'dotted' => __('Dotted', 'woocommerce-thank-you-page-customizer'),
                'dashed' => __('Dashed', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_border_color]', array(
            'default' => $this->settings->get_default('order_confirmation_border_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[order_confirmation_border_color]',
                array(
                    'label' => __('Border Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_order_confirmation',
                    'settings' => 'woo_thank_you_page_params[order_confirmation_border_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_vertical_width]', array(
            'default' => $this->settings->get_default('order_confirmation_vertical_width'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_confirmation_vertical_width]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_order_confirmation',
            'label' => __('Vertical Separator Width(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_vertical_style]', array(
            'default' => $this->settings->get_default('order_confirmation_vertical_style'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_confirmation_vertical_style]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_order_confirmation', // Add a default or your own section
            'label' => __('Vertical Separator Style', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'solid' => __('Solid', 'woocommerce-thank-you-page-customizer'),
                'dotted' => __('Dotted', 'woocommerce-thank-you-page-customizer'),
                'dashed' => __('Dashed', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_vertical_color]', array(
            'default' => $this->settings->get_default('order_confirmation_vertical_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[order_confirmation_vertical_color]',
                array(
                    'label' => __('Vertical Separator Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_order_confirmation',
                    'settings' => 'woo_thank_you_page_params[order_confirmation_vertical_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_horizontal_width]', array(
            'default' => $this->settings->get_default('order_confirmation_horizontal_width'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_confirmation_horizontal_width]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_order_confirmation',
            'label' => __('Horizontal Separator Width(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_horizontal_style]', array(
            'default' => $this->settings->get_default('order_confirmation_horizontal_style'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_confirmation_horizontal_style]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_order_confirmation', // Add a default or your own section
            'label' => __('Horizontal Separator Style', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'solid' => __('Solid', 'woocommerce-thank-you-page-customizer'),
                'dotted' => __('Dotted', 'woocommerce-thank-you-page-customizer'),
                'dashed' => __('Dashed', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_horizontal_color]', array(
            'default' => $this->settings->get_default('order_confirmation_horizontal_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[order_confirmation_horizontal_color]',
                array(
                    'label' => __('Horizontal Separator Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_order_confirmation',
                    'settings' => 'woo_thank_you_page_params[order_confirmation_horizontal_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_header]', array(
            'default' => $this->settings->get_default('order_confirmation_header'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_confirmation_header]', array(
            'type' => 'textarea',
            'section' => 'woo_thank_you_page_design_order_confirmation',
            'label' => __('Header Text', 'woocommerce-thank-you-page-customizer'),
            'description' => __('<span class="' . $this->set('available-shortcodes-shortcut') . '">Shortcodes list</span>', 'woocommerce-thank-you-page-customizer'),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_header_font_size]', array(
            'default' => $this->settings->get_default('order_confirmation_header_font_size'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_confirmation_header_font_size]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_order_confirmation',
            'label' => __('Header Font Size(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_header_text_align]', array(
            'default' => $this->settings->get_default('order_confirmation_header_text_align'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_confirmation_header_text_align]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_order_confirmation', // Add a default or your own section
            'label' => __('Header Text Align', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'left' => __('Left', 'woocommerce-thank-you-page-customizer'),
                'center' => __('Center', 'woocommerce-thank-you-page-customizer'),
                'right' => __('Right', 'woocommerce-thank-you-page-customizer'),
                'justify' => __('Justify', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_header_color]', array(
            'default' => $this->settings->get_default('order_confirmation_header_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[order_confirmation_header_color]',
                array(
                    'label' => __('Header Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_order_confirmation',
                    'settings' => 'woo_thank_you_page_params[order_confirmation_header_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_header_bg_color]', array(
            'default' => $this->settings->get_default('order_confirmation_header_bg_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[order_confirmation_header_bg_color]',
                array(
                    'label' => __('Header Background Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_order_confirmation',
                    'settings' => 'woo_thank_you_page_params[order_confirmation_header_bg_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_title_font_size]', array(
            'default' => $this->settings->get_default('order_confirmation_title_font_size'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_confirmation_title_font_size]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_order_confirmation',
            'label' => __('Title Font Size(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_title_text_align]', array(
            'default' => $this->settings->get_default('order_confirmation_title_text_align'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_confirmation_title_text_align]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_order_confirmation', // Add a default or your own section
            'label' => __('Title Text Align', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'left' => __('Left', 'woocommerce-thank-you-page-customizer'),
                'center' => __('Center', 'woocommerce-thank-you-page-customizer'),
                'right' => __('Right', 'woocommerce-thank-you-page-customizer'),
                'justify' => __('Justify', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_title_color]', array(
            'default' => $this->settings->get_default('order_confirmation_title_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[order_confirmation_title_color]',
                array(
                    'label' => __('Title Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_order_confirmation',
                    'settings' => 'woo_thank_you_page_params[order_confirmation_title_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_title_bg_color]', array(
            'default' => $this->settings->get_default('order_confirmation_title_bg_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[order_confirmation_title_bg_color]',
                array(
                    'label' => __('Title Background Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_order_confirmation',
                    'settings' => 'woo_thank_you_page_params[order_confirmation_title_bg_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_value_font_size]', array(
            'default' => $this->settings->get_default('order_confirmation_value_font_size'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_confirmation_value_font_size]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_order_confirmation',
            'label' => __('Value Font Size(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_value_text_align]', array(
            'default' => $this->settings->get_default('order_confirmation_value_text_align'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_confirmation_value_text_align]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_order_confirmation', // Add a default or your own section
            'label' => __('Value Text Align', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'left' => __('Left', 'woocommerce-thank-you-page-customizer'),
                'center' => __('Center', 'woocommerce-thank-you-page-customizer'),
                'right' => __('Right', 'woocommerce-thank-you-page-customizer'),
                'justify' => __('Justify', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_value_color]', array(
            'default' => $this->settings->get_default('order_confirmation_value_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[order_confirmation_value_color]',
                array(
                    'label' => __('Value Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_order_confirmation',
                    'settings' => 'woo_thank_you_page_params[order_confirmation_value_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[order_confirmation_value_bg_color]', array(
            'default' => $this->settings->get_default('order_confirmation_value_bg_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[order_confirmation_value_bg_color]',
                array(
                    'label' => __('Value Background Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_order_confirmation',
                    'settings' => 'woo_thank_you_page_params[order_confirmation_value_bg_color]',
                ))
        );

    }

    protected function add_section_design_thank_you_message($wp_customize)
    {

        $wp_customize->add_section('woo_thank_you_page_design_thank_you_message', array(
            'priority' => 20,
            'capability' => 'manage_options',
            'theme_supports' => '',
            'title' => __('Thank You Message', 'woocommerce-thank-you-page-customizer'),

        ));
        $wp_customize->add_setting('woo_thank_you_page_params[thank_you_message_color]', array(
            'default' => $this->settings->get_default('thank_you_message_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[thank_you_message_color]',
                array(
                    'label' => __('Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_thank_you_message',
                    'settings' => 'woo_thank_you_page_params[thank_you_message_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[thank_you_message_text_align]', array(
            'default' => $this->settings->get_default('thank_you_message_text_align'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[thank_you_message_text_align]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_thank_you_message', // Add a default or your own section
            'label' => __('Text Align', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'left' => __('Left', 'woocommerce-thank-you-page-customizer'),
                'center' => __('Center', 'woocommerce-thank-you-page-customizer'),
                'right' => __('Right', 'woocommerce-thank-you-page-customizer'),
                'justify' => __('Justify', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[thank_you_message_padding]', array(
            'default' => $this->settings->get_default('thank_you_message_padding'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[thank_you_message_padding]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_thank_you_message',
            'label' => __('Padding(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[thank_you_message_header]', array(
            'default' => $this->settings->get_default('thank_you_message_header'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[thank_you_message_header]', array(
            'type' => 'textarea',
            'section' => 'woo_thank_you_page_design_thank_you_message',
            'label' => __('Header Text', 'woocommerce-thank-you-page-customizer'),
            'description' => __('<span class="' . $this->set('available-shortcodes-shortcut') . '">Shortcodes list</span>', 'woocommerce-thank-you-page-customizer'),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[thank_you_message_header_font_size]', array(
            'default' => $this->settings->get_default('thank_you_message_header_font_size'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[thank_you_message_header_font_size]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_thank_you_message',
            'label' => __('Header Font Size(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[thank_you_message_message]', array(
            'default' => $this->settings->get_default('thank_you_message_message'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[thank_you_message_message]', array(
            'type' => 'textarea',
            'section' => 'woo_thank_you_page_design_thank_you_message',
            'label' => __('Message Text', 'woocommerce-thank-you-page-customizer'),
            'description' => __('<span class="' . $this->set('available-shortcodes-shortcut') . '">Shortcodes list</span>', 'woocommerce-thank-you-page-customizer'),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[thank_you_message_message_font_size]', array(
            'default' => $this->settings->get_default('thank_you_message_message_font_size'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[thank_you_message_message_font_size]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_thank_you_message',
            'label' => __('Message Font Size(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));

    }

    protected function add_section_design_coupon($wp_customize)
    {

        $wp_customize->add_section('woo_thank_you_page_design_coupon', array(
            'priority' => 20,
            'capability' => 'manage_options',
            'theme_supports' => '',
            'title' => __('Coupon', 'woocommerce-thank-you-page-customizer'),

        ));
        $wp_customize->add_setting('woo_thank_you_page_params[coupon_text_align]', array(
            'default' => $this->settings->get_default('coupon_text_align'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[coupon_text_align]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_coupon', // Add a default or your own section
            'label' => __('Text Align', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'left' => __('Left', 'woocommerce-thank-you-page-customizer'),
                'center' => __('Center', 'woocommerce-thank-you-page-customizer'),
                'right' => __('Right', 'woocommerce-thank-you-page-customizer'),
                'justify' => __('Justify', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[coupon_padding]', array(
            'default' => $this->settings->get_default('coupon_padding'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[coupon_padding]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_coupon',
            'label' => __('Padding(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[coupon_message]', array(
            'default' => $this->settings->get_default('coupon_message'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[coupon_message]', array(
            'type' => 'textarea',
            'section' => 'woo_thank_you_page_design_coupon',
            'label' => __('Message Text', 'woocommerce-thank-you-page-customizer'),
            'description' => __('Available shortcode: {coupon_amount}, {coupon_date_expires}, {last_valid_date}, {coupon_code}<p><span class="' . $this->set('available-shortcodes-shortcut') . '">Shortcodes list</span></p>', 'woocommerce-thank-you-page-customizer'),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[coupon_message_color]', array(
            'default' => $this->settings->get_default('coupon_message_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[coupon_message_color]',
                array(
                    'label' => __('Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_coupon',
                    'settings' => 'woo_thank_you_page_params[coupon_message_color]',
                ))
        );

        $wp_customize->add_setting('woo_thank_you_page_params[coupon_message_font_size]', array(
            'default' => $this->settings->get_default('coupon_message_font_size'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[coupon_message_font_size]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_coupon',
            'label' => __('Message Font Size(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));

        $wp_customize->add_setting('woo_thank_you_page_params[coupon_code_border_width]', array(
            'default' => $this->settings->get_default('coupon_code_border_width'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[coupon_code_border_width]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_coupon',
            'label' => __('Border Width(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[coupon_code_border_style]', array(
            'default' => $this->settings->get_default('coupon_code_border_style'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[coupon_code_border_style]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_coupon', // Add a default or your own section
            'label' => __('Border Style', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'solid' => __('Solid', 'woocommerce-thank-you-page-customizer'),
                'dotted' => __('Dotted', 'woocommerce-thank-you-page-customizer'),
                'dashed' => __('Dashed', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[coupon_code_border_color]', array(
            'default' => $this->settings->get_default('coupon_code_border_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[coupon_code_border_color]',
                array(
                    'label' => __('Border Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_coupon',
                    'settings' => 'woo_thank_you_page_params[coupon_code_border_color]',
                ))
        );

        $wp_customize->add_setting('woo_thank_you_page_params[coupon_code_color]', array(
            'default' => $this->settings->get_default('coupon_code_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[coupon_code_color]',
                array(
                    'label' => __('Coupon Code Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_coupon',
                    'settings' => 'woo_thank_you_page_params[coupon_code_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[coupon_code_bg_color]', array(
            'default' => $this->settings->get_default('coupon_code_bg_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[coupon_code_bg_color]',
                array(
                    'label' => __('Coupon Code Background Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_coupon',
                    'settings' => 'woo_thank_you_page_params[coupon_code_bg_color]',
                ))
        );

        $wp_customize->add_setting('woo_thank_you_page_params[coupon_scissors_color]', array(
            'default' => $this->settings->get_default('coupon_scissors_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[coupon_scissors_color]',
                array(
                    'label' => __('Scissors color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_coupon',
                    'settings' => 'woo_thank_you_page_params[coupon_scissors_color]',
                ))
        );

        $wp_customize->add_setting('woo_thank_you_page_params[coupon_email_enable]', array(
            'default' => $this->settings->get_default('coupon_email_enable'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[coupon_email_enable]', array(
            'type' => 'checkbox',
            'section' => 'woo_thank_you_page_design_coupon',
            'label' => __('Show button to handle coupon code', 'woocommerce-thank-you-page-customizer'),
            'description' => __('If enabled, when hovering the coupon field, 2 buttons will show to copy coupon code or send code to billing email.', 'woocommerce-thank-you-page-customizer'),
        ));

    }

    protected function add_section_design_order_details($wp_customize)
    {

        $wp_customize->add_section('woo_thank_you_page_design_order_details', array(
            'priority' => 20,
            'capability' => 'manage_options',
            'theme_supports' => '',
            'title' => __('Order Details', 'woocommerce-thank-you-page-customizer'),

        ));

        $wp_customize->add_setting('woo_thank_you_page_params[order_details_color]', array(
            'default' => $this->settings->get_default('order_details_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[order_details_color]',
                array(
                    'label' => __('Text Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_order_details',
                    'settings' => 'woo_thank_you_page_params[order_details_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[order_details_bg]', array(
            'default' => $this->settings->get_default('order_details_bg'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[order_details_bg]',
                array(
                    'label' => __('Background Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_order_details',
                    'settings' => 'woo_thank_you_page_params[order_details_bg]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[order_details_padding]', array(
            'default' => $this->settings->get_default('order_details_padding'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_details_padding]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_order_details',
            'label' => __('Padding(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_details_border_radius]', array(
            'default' => $this->settings->get_default('order_details_border_radius'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_details_border_radius]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_order_details',
            'label' => __('Rounded Corner(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_details_border_width]', array(
            'default' => $this->settings->get_default('order_details_border_width'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_details_border_width]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_order_details',
            'label' => __('Border Width(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_details_border_style]', array(
            'default' => $this->settings->get_default('order_details_border_style'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_details_border_style]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_order_details', // Add a default or your own section
            'label' => __('Border Style', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'solid' => __('Solid', 'woocommerce-thank-you-page-customizer'),
                'dotted' => __('Dotted', 'woocommerce-thank-you-page-customizer'),
                'dashed' => __('Dashed', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_details_border_color]', array(
            'default' => $this->settings->get_default('order_details_border_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[order_details_border_color]',
                array(
                    'label' => __('Border Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_order_details',
                    'settings' => 'woo_thank_you_page_params[order_details_border_color]',
                ))
        );

        $wp_customize->add_setting('woo_thank_you_page_params[order_details_horizontal_width]', array(
            'default' => $this->settings->get_default('order_details_horizontal_width'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_details_horizontal_width]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_order_details',
            'label' => __('Horizontal Separator Width(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_details_horizontal_style]', array(
            'default' => $this->settings->get_default('order_details_horizontal_style'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_details_horizontal_style]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_order_details', // Add a default or your own section
            'label' => __('Horizontal Separator Style', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'solid' => __('Solid', 'woocommerce-thank-you-page-customizer'),
                'dotted' => __('Dotted', 'woocommerce-thank-you-page-customizer'),
                'dashed' => __('Dashed', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_details_horizontal_color]', array(
            'default' => $this->settings->get_default('order_details_horizontal_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[order_details_horizontal_color]',
                array(
                    'label' => __('Horizontal Separator Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_order_details',
                    'settings' => 'woo_thank_you_page_params[order_details_horizontal_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[order_details_header]', array(
            'default' => $this->settings->get_default('order_details_header'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_details_header]', array(
            'type' => 'textarea',
            'section' => 'woo_thank_you_page_design_order_details',
            'label' => __('Header Text', 'woocommerce-thank-you-page-customizer'),
            'description' => __('<span class="' . $this->set('available-shortcodes-shortcut') . '">Shortcodes list</span>', 'woocommerce-thank-you-page-customizer'),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_details_header_font_size]', array(
            'default' => $this->settings->get_default('order_details_header_font_size'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_details_header_font_size]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_order_details',
            'label' => __('Header Font Size(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_details_header_text_align]', array(
            'default' => $this->settings->get_default('order_details_header_text_align'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_details_header_text_align]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_order_details', // Add a default or your own section
            'label' => __('Header Text Align', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'left' => __('Left', 'woocommerce-thank-you-page-customizer'),
                'center' => __('Center', 'woocommerce-thank-you-page-customizer'),
                'right' => __('Right', 'woocommerce-thank-you-page-customizer'),
                'justify' => __('Justify', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_details_header_color]', array(
            'default' => $this->settings->get_default('order_details_header_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[order_details_header_color]',
                array(
                    'label' => __('Header Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_order_details',
                    'settings' => 'woo_thank_you_page_params[order_details_header_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[order_details_header_bg_color]', array(
            'default' => $this->settings->get_default('order_details_header_bg_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[order_details_header_bg_color]',
                array(
                    'label' => __('Header Background Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_order_details',
                    'settings' => 'woo_thank_you_page_params[order_details_header_bg_color]',
                ))
        );

        $wp_customize->add_setting('woo_thank_you_page_params[order_details_product_image]', array(
            'default' => $this->settings->get_default('order_details_product_image'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_details_product_image]', array(
            'type' => 'checkbox',
            'section' => 'woo_thank_you_page_design_order_details', // Add a default or your own section
            'label' => __('Display Product Image', 'woocommerce-thank-you-page-customizer'),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[order_details_product_image_width]', array(
            'default' => $this->settings->get_default('order_details_product_image_width'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[order_details_product_image_width]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_order_details',
            'label' => __('Product Image width(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
    }

    protected function add_section_design_customer_information($wp_customize)
    {

        $wp_customize->add_section('woo_thank_you_page_design_customer_information', array(
            'priority' => 20,
            'capability' => 'manage_options',
            'theme_supports' => '',
            'title' => __('Customer Information', 'woocommerce-thank-you-page-customizer'),

        ));
//		$wp_customize->selective_refresh->add_partial( 'woo_thank_you_page_params[customer_information_color]', array(
//			'selector'            => '.woocommerce-thank-you-page-customer_information__container',
//			'container_inclusive' => true,
//			'fallback_refresh'    => false, // Pre
//		) );
        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_color]', array(
            'default' => $this->settings->get_default('customer_information_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[customer_information_color]',
                array(
                    'label' => __('Text Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_customer_information',
                    'settings' => 'woo_thank_you_page_params[customer_information_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_bg]', array(
            'default' => $this->settings->get_default('customer_information_bg'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[customer_information_bg]',
                array(
                    'label' => __('Background Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_customer_information',
                    'settings' => 'woo_thank_you_page_params[customer_information_bg]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_padding]', array(
            'default' => $this->settings->get_default('customer_information_padding'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[customer_information_padding]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_customer_information',
            'label' => __('Padding(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_border_radius]', array(
            'default' => $this->settings->get_default('customer_information_border_radius'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[customer_information_border_radius]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_customer_information',
            'label' => __('Rounded Corner(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_border_width]', array(
            'default' => $this->settings->get_default('customer_information_border_width'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[customer_information_border_width]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_customer_information',
            'label' => __('Border Width(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_border_style]', array(
            'default' => $this->settings->get_default('customer_information_border_style'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[customer_information_border_style]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_customer_information', // Add a default or your own section
            'label' => __('Border Style', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'solid' => __('Solid', 'woocommerce-thank-you-page-customizer'),
                'dotted' => __('Dotted', 'woocommerce-thank-you-page-customizer'),
                'dashed' => __('Dashed', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_border_color]', array(
            'default' => $this->settings->get_default('customer_information_border_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[customer_information_border_color]',
                array(
                    'label' => __('Border Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_customer_information',
                    'settings' => 'woo_thank_you_page_params[customer_information_border_color]',
                ))
        );

        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_vertical_width]', array(
            'default' => $this->settings->get_default('customer_information_vertical_width'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[customer_information_vertical_width]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_customer_information',
            'label' => __('Vertical Separator Width(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_vertical_style]', array(
            'default' => $this->settings->get_default('customer_information_vertical_style'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[customer_information_vertical_style]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_customer_information', // Add a default or your own section
            'label' => __('Vertical Separator Style', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'solid' => __('Solid', 'woocommerce-thank-you-page-customizer'),
                'dotted' => __('Dotted', 'woocommerce-thank-you-page-customizer'),
                'dashed' => __('Dashed', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_vertical_color]', array(
            'default' => $this->settings->get_default('customer_information_vertical_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[customer_information_vertical_color]',
                array(
                    'label' => __('Vertical Separator Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_customer_information',
                    'settings' => 'woo_thank_you_page_params[customer_information_vertical_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_header]', array(
            'default' => $this->settings->get_default('customer_information_header'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[customer_information_header]', array(
            'type' => 'textarea',
            'section' => 'woo_thank_you_page_design_customer_information',
            'label' => __('Header Text', 'woocommerce-thank-you-page-customizer'),
            'description' => __('<span class="' . $this->set('available-shortcodes-shortcut') . '">Shortcodes list</span>', 'woocommerce-thank-you-page-customizer'),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_header_font_size]', array(
            'default' => $this->settings->get_default('customer_information_header_font_size'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[customer_information_header_font_size]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_customer_information',
            'label' => __('Header Font Size(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_header_text_align]', array(
            'default' => $this->settings->get_default('customer_information_header_text_align'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[customer_information_header_text_align]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_customer_information', // Add a default or your own section
            'label' => __('Header Text Align', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'left' => __('Left', 'woocommerce-thank-you-page-customizer'),
                'center' => __('Center', 'woocommerce-thank-you-page-customizer'),
                'right' => __('Right', 'woocommerce-thank-you-page-customizer'),
                'justify' => __('Justify', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_header_color]', array(
            'default' => $this->settings->get_default('customer_information_header_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[customer_information_header_color]',
                array(
                    'label' => __('Header Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_customer_information',
                    'settings' => 'woo_thank_you_page_params[customer_information_header_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_header_bg_color]', array(
            'default' => $this->settings->get_default('customer_information_header_bg_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[customer_information_header_bg_color]',
                array(
                    'label' => __('Header Background Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_customer_information',
                    'settings' => 'woo_thank_you_page_params[customer_information_header_bg_color]',
                ))
        );

        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_address_font_size]', array(
            'default' => $this->settings->get_default('customer_information_address_font_size'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[customer_information_address_font_size]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_customer_information',
            'label' => __('Address Font Size(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_address_text_align]', array(
            'default' => $this->settings->get_default('customer_information_address_text_align'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[customer_information_address_text_align]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_customer_information', // Add a default or your own section
            'label' => __('Address Text Align', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'left' => __('Left', 'woocommerce-thank-you-page-customizer'),
                'center' => __('Center', 'woocommerce-thank-you-page-customizer'),
                'right' => __('Right', 'woocommerce-thank-you-page-customizer'),
                'justify' => __('Justify', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_address_color]', array(
            'default' => $this->settings->get_default('customer_information_address_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[customer_information_address_color]',
                array(
                    'label' => __('Address Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_customer_information',
                    'settings' => 'woo_thank_you_page_params[customer_information_address_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[customer_information_address_bg_color]', array(
            'default' => $this->settings->get_default('customer_information_address_bg_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[customer_information_address_bg_color]',
                array(
                    'label' => __('Address Background Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_customer_information',
                    'settings' => 'woo_thank_you_page_params[customer_information_address_bg_color]',
                ))
        );
    }

    private function set($name)
    {
        if (is_array($name)) {
            return implode(' ', array_map(array($this, 'set'), $name));

        } else {
            return esc_attr__($this->prefix . $name);

        }
    }

    private function get_params($name = '')
    {
        return $this->settings->get_params($name);
    }

    private function add_preview_style($name, $element, $style, $suffix = '', $echo = true)
    {
        ob_start();
        ?>
        <style type="text/css"
               id="<?php echo $this->set('preview-') . str_replace('_', '-', $name) ?>"><?php echo $element . '{' . (($this->get_params($name) === '') ? '' : ($style . ':' . $this->get_params($name) . $suffix)) . '}' ?></style>
        <?php
        $return = ob_get_clean();
        if ($echo) {
            echo $return;
        }

        return $return;
    }

    protected function add_section_design_social_icons($wp_customize)
    {

        $wp_customize->add_section('woo_thank_you_page_design_social_icons', array(
            'priority' => 20,
            'capability' => 'manage_options',
            'theme_supports' => '',
            'title' => __('Social Media', 'woocommerce-thank-you-page-customizer'),

        ));
        $icons = array(
            "wtyp_social_icons-facebook-circular-logo",
            "wtyp_social_icons-facebook-logo-1",
            "wtyp_social_icons-facebook-square-social-logo",
            "wtyp_social_icons-facebook-app-logo",
            "wtyp_social_icons-facebook-logo",
            "wtyp_social_icons-internet",
            "wtyp_social_icons-twitter-logo-button",
            "wtyp_social_icons-twitter-logo-silhouette",
            "wtyp_social_icons-twitter",
            "wtyp_social_icons-twitter-1",
            "wtyp_social_icons-twitter-logo-on-black-background",
            "wtyp_social_icons-twitter-sign",
            "wtyp_social_icons-pinterest",
            "wtyp_social_icons-pinterest-logo",
            "wtyp_social_icons-pinterest-1",
            "wtyp_social_icons-pinterest-2",
            "wtyp_social_icons-pinterest-social-logo",
            "wtyp_social_icons-pinterest-logo-1",
            "wtyp_social_icons-pinterest-sign",
            "wtyp_social_icons-instagram-logo",
            "wtyp_social_icons-instagram-social-network-logo-of-photo-camera-1",
            "wtyp_social_icons-instagram-1",
            "wtyp_social_icons-social-media",
            "wtyp_social_icons-instagram",
            "wtyp_social_icons-instagram-social-network-logo-of-photo-camera",
            "wtyp_social_icons-instagram-logo-1",
            "wtyp_social_icons-instagram-2",
            "wtyp_social_icons-dribbble-logo",
            "wtyp_social_icons-dribble-logo-button",
            "wtyp_social_icons-dribbble",
            "wtyp_social_icons-dribbble-logo-1",
            "wtyp_social_icons-dribbble-2",
            "wtyp_social_icons-dribbble-1",
            "wtyp_social_icons-tumblr-logo-1",
            "wtyp_social_icons-tumblr-logo-button",
            "wtyp_social_icons-tumblr",
            "wtyp_social_icons-tumblr-logo-2",
            "wtyp_social_icons-tumblr-logo",
            "wtyp_social_icons-tumblr-1",
            "wtyp_social_icons-google-plus-logo",
            "wtyp_social_icons-google-plus-symbol",
            "wtyp_social_icons-google-plus-social-logotype",
            "wtyp_social_icons-google-plus",
            "wtyp_social_icons-google-plus-social-logotype-1",
            "wtyp_social_icons-google-plus-logo-on-black-background",
            "wtyp_social_icons-social-google-plus-square-button",
            "wtyp_social_icons-vk-social-network-logo",
            "wtyp_social_icons-vk-social-logotype",
            "wtyp_social_icons-vk",
            "wtyp_social_icons-vk-social-logotype-1",
            "wtyp_social_icons-vk-reproductor",
            "wtyp_social_icons-vkontakte-logo",
            "wtyp_social_icons-linkedin-logo",
            "wtyp_social_icons-linkedin-button",
            "wtyp_social_icons-linkedin-1",
            "wtyp_social_icons-linkedin-logo-1",
            "wtyp_social_icons-linkedin-sign",
            "wtyp_social_icons-linkedin",
            "wtyp_social_icons-youtube-logo-2",
            "wtyp_social_icons-youtube-logotype-1",
            "wtyp_social_icons-youtube",
            "wtyp_social_icons-youtube-logotype",
            "wtyp_social_icons-youtube-logo",
            "wtyp_social_icons-youtube-logo-1"
        );


        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_header]', array(
            'default' => $this->settings->get_default('social_icons_header'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[social_icons_header]', array(
            'type' => 'textarea',
            'priority' => 10,
            'section' => 'woo_thank_you_page_design_social_icons',
            'label' => __('Text Follow Social Network', 'woocommerce-thank-you-page-customizer'),
            'description' => __('<span class="' . $this->set('available-shortcodes-shortcut') . '">Shortcodes list</span>', 'woocommerce-thank-you-page-customizer'),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_header_color]', array(
            'default' => $this->settings->get_default('social_icons_header_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_header_color]',
                array(
                    'label' => __('Header Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_social_icons',
                    'settings' => 'woo_thank_you_page_params[social_icons_header_color]',
                ))
        );
        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_header_font_size]', array(
            'default' => $this->settings->get_default('social_icons_header_font_size'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[social_icons_header_font_size]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_social_icons',
            'label' => __('Header Font Size(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_size]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => $this->settings->get_default('social_icons_size'),
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[social_icons_size]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_social_icons',
            'label' => __('Icons size (px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_align]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => $this->settings->get_default('social_icons_align'),
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[social_icons_align]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_social_icons',
            'label' => __('Icons align', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'left' => __('Left', 'woocommerce-thank-you-page-customizer'),
                'center' => __('Center', 'woocommerce-thank-you-page-customizer'),
                'right' => __('Right', 'woocommerce-thank-you-page-customizer'),
                'justify' => __('Justify', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_space]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => $this->settings->get_default('social_icons_space'),
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[social_icons_space]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_social_icons',
            'label' => __('Spaces Between Icons (px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_target]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => $this->settings->get_default('social_icons_target'),
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[social_icons_target]', array(
            'type' => 'select',
            'section' => 'woo_thank_you_page_design_social_icons',
            'label' => __('When click on social icons', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                '_blank' => __('Open link in new tab', 'woocommerce-thank-you-page-customizer'),
                '_self' => __('Open link in current tab', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $facebook = $twitter = $pinterest = $instagram = $dribbble = $tumblr = $google = $vkontakte = $linkedin = $youtube = array();
        for ($i = 0; $i < sizeof($icons); $i++) {
            if ($i < 6) {
                $facebook[$icons[$i]] = $icons[$i];
            } elseif ($i < 12) {
                $twitter[$icons[$i]] = $icons[$i];
            } elseif ($i < 19) {
                $pinterest[$icons[$i]] = $icons[$i];
            } elseif ($i < 27) {
                $instagram[$icons[$i]] = $icons[$i];
            } elseif ($i < 33) {
                $dribbble[$icons[$i]] = $icons[$i];
            } elseif ($i < 39) {
                $tumblr[$icons[$i]] = $icons[$i];
            } elseif ($i < 46) {
                $google[$icons[$i]] = $icons[$i];
            } elseif ($i < 52) {
                $vkontakte[$icons[$i]] = $icons[$i];
            } elseif ($i < 58) {
                $linkedin[$icons[$i]] = $icons[$i];
            } else {
                $youtube[$icons[$i]] = $icons[$i];
            }
        }
        /*facebook*/
        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_facebook_url]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => $this->settings->get_default('social_icons_facebook_url'),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control('woo_thank_you_page_params[social_icons_facebook_url]', array(
            'type' => 'url',
            'section' => 'woo_thank_you_page_design_social_icons',
            'label' => __('Facebook URL', 'woocommerce-thank-you-page-customizer'),
            'description' => __('Your Facebook URL Eg: https://www.facebook.com/villatheme', 'woocommerce-thank-you-page-customizer'),
        ));

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_facebook_select]', array(
            'default' => $this->settings->get_default('social_icons_facebook_select'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_facebook_select]',
                array(
                    'label' => 'Icons',
                    'section' => 'woo_thank_you_page_design_social_icons',
                    'choices' => $facebook
                )
            )
        );


        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_facebook_color]', array(
            'default' => $this->settings->get_default('social_icons_facebook_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_facebook_color]',
                array(
                    'label' => __('Icon Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_social_icons',
                ))
        );


        /*twitter*/
        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_twitter_url]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => $this->settings->get_default('social_icons_twitter_url'),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control('woo_thank_you_page_params[social_icons_twitter_url]', array(
            'type' => 'url',
            'section' => 'woo_thank_you_page_design_social_icons',
            'label' => __('Twitter URL', 'woocommerce-thank-you-page-customizer'),
            'description' => __('Your Twitter URL Eg: https://www.twitter.com/villatheme', 'woocommerce-thank-you-page-customizer'),
        ));

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_twitter_select]', array(
            'default' => $this->settings->get_default('social_icons_twitter_select'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_twitter_select]',
                array(
                    'label' => 'Icons',
                    'section' => 'woo_thank_you_page_design_social_icons',
                    'choices' => $twitter
                )
            )
        );

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_twitter_color]', array(
            'default' => $this->settings->get_default('social_icons_twitter_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_twitter_color]',
                array(
                    'label' => __('Icon Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_social_icons',
                ))
        );

        /*pinterest*/
        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_pinterest_url]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => $this->settings->get_default('social_icons_pinterest_url'),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control('woo_thank_you_page_params[social_icons_pinterest_url]', array(
            'type' => 'url',
            'section' => 'woo_thank_you_page_design_social_icons',
            'label' => __('Pinterest URL', 'woocommerce-thank-you-page-customizer'),
            'description' => __('Your Pinterest URL', 'woocommerce-thank-you-page-customizer'),
        ));

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_pinterest_select]', array(
            'default' => $this->settings->get_default('social_icons_pinterest_select'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_pinterest_select]',
                array(
                    'label' => 'Icons',
                    'section' => 'woo_thank_you_page_design_social_icons',
                    'choices' => $pinterest
                )
            )
        );

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_pinterest_color]', array(
            'default' => $this->settings->get_default('social_icons_pinterest_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_pinterest_color]',
                array(
                    'label' => __('Icon Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_social_icons',
                ))
        );

        /*instagram*/
        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_instagram_url]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => $this->settings->get_default('social_icons_instagram_url'),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control('woo_thank_you_page_params[social_icons_instagram_url]', array(
            'type' => 'url',
            'section' => 'woo_thank_you_page_design_social_icons',
            'label' => __('Instagram URL', 'woocommerce-thank-you-page-customizer'),
            'description' => __('Your Instagram URL', 'woocommerce-thank-you-page-customizer'),
        ));

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_instagram_select]', array(
            'default' => $this->settings->get_default('social_icons_instagram_select'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_instagram_select]',
                array(
                    'label' => 'Icons',
                    'section' => 'woo_thank_you_page_design_social_icons',
                    'choices' => $instagram
                )
            )
        );

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_instagram_color]', array(
            'default' => $this->settings->get_default('social_icons_instagram_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_instagram_color]',
                array(
                    'label' => __('Icon Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_social_icons',
                ))
        );

        /*dribbble*/
        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_dribbble_url]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => $this->settings->get_default('social_icons_dribbble_url'),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control('woo_thank_you_page_params[social_icons_dribbble_url]', array(
            'type' => 'url',
            'section' => 'woo_thank_you_page_design_social_icons',
            'label' => __('Dribbble URL', 'woocommerce-thank-you-page-customizer'),
            'description' => __('Your Dribbble URL', 'woocommerce-thank-you-page-customizer'),
        ));

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_dribbble_select]', array(
            'default' => $this->settings->get_default('social_icons_dribbble_select'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_dribbble_select]',
                array(
                    'label' => 'Icons',
                    'section' => 'woo_thank_you_page_design_social_icons',
                    'choices' => $dribbble
                )
            )
        );

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_dribbble_color]', array(
            'default' => $this->settings->get_default('social_icons_dribbble_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_dribbble_color]',
                array(
                    'label' => __('Icon Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_social_icons',
                ))
        );

        /*tumblr*/
        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_tumblr_url]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => $this->settings->get_default('social_icons_tumblr_url'),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control('woo_thank_you_page_params[social_icons_tumblr_url]', array(
            'type' => 'url',
            'section' => 'woo_thank_you_page_design_social_icons',
            'label' => __('Tumblr URL', 'woocommerce-thank-you-page-customizer'),
            'description' => __('Your Tumblr URL', 'woocommerce-thank-you-page-customizer'),
        ));

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_tumblr_select]', array(
            'default' => $this->settings->get_default('social_icons_tumblr_select'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_tumblr_select]',
                array(
                    'label' => 'Icons',
                    'section' => 'woo_thank_you_page_design_social_icons',
                    'choices' => $tumblr
                )
            )
        );

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_tumblr_color]', array(
            'default' => $this->settings->get_default('social_icons_tumblr_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_tumblr_color]',
                array(
                    'label' => __('Icon Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_social_icons',
                ))
        );

        /*google*/
        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_google_url]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => $this->settings->get_default('social_icons_google_url'),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control('woo_thank_you_page_params[social_icons_google_url]', array(
            'type' => 'url',
            'section' => 'woo_thank_you_page_design_social_icons',
            'label' => __('Google Plus ID', 'woocommerce-thank-you-page-customizer'),
            'description' => __('Your Google Plus URL', 'woocommerce-thank-you-page-customizer'),
        ));

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_google_select]', array(
            'default' => $this->settings->get_default('social_icons_google_select'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_google_select]',
                array(
                    'label' => 'Icons',
                    'section' => 'woo_thank_you_page_design_social_icons',
                    'choices' => $google
                )
            )
        );

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_google_color]', array(
            'default' => $this->settings->get_default('social_icons_google_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_google_color]',
                array(
                    'label' => __('Icon Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_social_icons',
                ))
        );

        /*vkontakte*/
        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_vkontakte_url]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => $this->settings->get_default('social_icons_vkontakte_url'),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control('woo_thank_you_page_params[social_icons_vkontakte_url]', array(
            'type' => 'url',
            'section' => 'woo_thank_you_page_design_social_icons',
            'label' => __('VKontakte URL', 'woocommerce-thank-you-page-customizer'),
            'description' => __('Your VKontakte URL', 'woocommerce-thank-you-page-customizer'),
        ));

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_vkontakte_select]', array(
            'default' => $this->settings->get_default('social_icons_vkontakte_select'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_vkontakte_select]',
                array(
                    'label' => 'Icons',
                    'section' => 'woo_thank_you_page_design_social_icons',
                    'choices' => $vkontakte
                )
            )
        );

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_vkontakte_color]', array(
            'default' => $this->settings->get_default('social_icons_vkontakte_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_vkontakte_color]',
                array(
                    'label' => __('Icon Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_social_icons',
                ))
        );

        /*linkedin*/

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_linkedin_select]', array(
            'default' => $this->settings->get_default('social_icons_linkedin_select'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_linkedin_select]',
                array(
                    'label' => 'Icons',
                    'section' => 'woo_thank_you_page_design_social_icons',
                    'choices' => $linkedin
                )
            )
        );
        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_linkedin_url]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => $this->settings->get_default('social_icons_linkedin_url'),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control('woo_thank_you_page_params[social_icons_linkedin_url]', array(
            'type' => 'url',
            'section' => 'woo_thank_you_page_design_social_icons',
            'label' => __('Linkedin URL', 'woocommerce-thank-you-page-customizer'),
            'description' => __('Your Linkedin URL', 'woocommerce-thank-you-page-customizer'),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_linkedin_color]', array(
            'default' => $this->settings->get_default('social_icons_linkedin_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_linkedin_color]',
                array(
                    'label' => __('Icon Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_social_icons',
                ))
        );

        /*youtube*/

        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_youtube_select]', array(
            'default' => $this->settings->get_default('social_icons_youtube_select'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_youtube_select]',
                array(
                    'label' => 'Icons',
                    'section' => 'woo_thank_you_page_design_social_icons',
                    'choices' => $youtube
                )
            )
        );
        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_youtube_url]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => $this->settings->get_default('social_icons_youtube_url'),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control('woo_thank_you_page_params[social_icons_youtube_url]', array(
            'type' => 'url',
            'section' => 'woo_thank_you_page_design_social_icons',
            'label' => __('Youtube URL', 'woocommerce-thank-you-page-customizer'),
            'description' => __('Your Youtube URL. Eg: https://www.youtube.com/channel/UCbCfnjbtBZIQfzLvXgNpbKw', 'woocommerce-thank-you-page-customizer'),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[social_icons_youtube_color]', array(
            'default' => $this->settings->get_default('social_icons_youtube_color'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'woo_thank_you_page_params[social_icons_youtube_color]',
                array(
                    'label' => __('Icon Color', 'woocommerce-thank-you-page-customizer'),
                    'section' => 'woo_thank_you_page_design_social_icons',
                ))
        );


    }

    protected function add_section_design_google_map($wp_customize)
    {
        $wp_customize->add_section('woo_thank_you_page_design_google_map', array(
            'priority' => 20,
            'capability' => 'manage_options',
            'theme_supports' => '',
            'title' => __('Google map', 'woocommerce-thank-you-page-customizer'),

        ));
        $wp_customize->add_setting('woo_thank_you_page_params[google_map_width]', array(
            'default' => $this->settings->get_default('google_map_width'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[google_map_width]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_google_map',
            'label' => __('Width(px)', 'woocommerce-thank-you-page-customizer'),
            'description' => __('If set 0, width will be 100%.', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[google_map_height]', array(
            'default' => $this->settings->get_default('google_map_height'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[google_map_height]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_google_map',
            'label' => __('Height(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[google_map_style]', array(
            'default' => $this->get_params('google_map_style'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[google_map_style]', array(
            'type' => 'select',
            'priority' => 10,
            'section' => 'woo_thank_you_page_design_google_map',
            'label' => __('Google map style', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'default' => __('Default', 'woocommerce-thank-you-page-customizer'),
                'ultra-light-with-labels' => __('Ultra light with labels', 'woocommerce-thank-you-page-customizer'),
                'subtle-grayscale' => __('Subtle Grayscale', 'woocommerce-thank-you-page-customizer'),
                'shades-of-grey' => __('Shades of grey', 'woocommerce-thank-you-page-customizer'),
                'blue-water' => __('Blue water', 'woocommerce-thank-you-page-customizer'),
                'wy' => __('WY', 'woocommerce-thank-you-page-customizer'),
                'vintage-old-golden-brown' => __('Vintage old golden brown', 'woocommerce-thank-you-page-customizer'),
                'black-and-white' => __('Black and white', 'woocommerce-thank-you-page-customizer'),
                'light-dream' => __('Light dream', 'woocommerce-thank-you-page-customizer'),
                'blue-essence' => __('Blue essence', 'woocommerce-thank-you-page-customizer'),
                'pale-dawn' => __('Pale dawn', 'woocommerce-thank-you-page-customizer'),
                'unsaturated-browns' => __('Unsaturated browns', 'woocommerce-thank-you-page-customizer'),
                'midnight-commander' => __('Midnight commander', 'woocommerce-thank-you-page-customizer'),
                'light-monochrome' => __('Light monochrome', 'woocommerce-thank-you-page-customizer'),
                'light-gray' => __('Light gray', 'woocommerce-thank-you-page-customizer'),
                'custom' => __('Custom', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[google_map_custom_style]', array(
            'default' => $this->settings->get_default('google_map_custom_style'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[google_map_custom_style]', array(
            'type' => 'textarea',
            'priority' => 10,
            'section' => 'woo_thank_you_page_design_google_map',
            'label' => __('Custom style', 'woocommerce-thank-you-page-customizer'),
            'description' => __('You can find more style at <a href="https://snazzymaps.com/" target="_blank">Snazzy Maps</a> by copying Javascript style array and paste it here.', 'woocommerce-thank-you-page-customizer')
        ));

        $wp_customize->add_setting('woo_thank_you_page_params[google_map_marker]', array(
            'default' => $this->settings->get_default('google_map_marker'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $google_map_marker_choices = array();
        for ($i = 1; $i <= 12; $i++) {
            $google_map_marker_choices['if-marker-' . $i] = array(
                'name' => __('Marker ' . $i, 'woocommerce-thank-you-page-customizer'),
                'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'if-marker-' . $i . '.png'
            );
        }
        $google_map_marker_choices['blue'] = array(
            'name' => __('Blue', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'blue.png'
        );
        $google_map_marker_choices['blue-dot'] = array(
            'name' => __('Blue dot', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'blue-dot.png'
        );
        $google_map_marker_choices['blue-pushpin'] = array(
            'name' => __('Blue pushpin', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'blue-pushpin.png'
        );

        $google_map_marker_choices['yellow'] = array(
            'name' => __('Yellow', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'yellow.png'
        );
        $google_map_marker_choices['yellow-dot'] = array(
            'name' => __('Yellow dot', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'yellow-dot.png'
        );
        $google_map_marker_choices['yellow-pushpin'] = array(
            'name' => __('Yellow pushpin', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'yellow-pushpin.png'
        );


        $google_map_marker_choices['green'] = array(
            'name' => __('Green', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'green.png'
        );
        $google_map_marker_choices['green-dot'] = array(
            'name' => __('Green dot', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'green-dot.png'
        );
        $google_map_marker_choices['green-pushpin'] = array(
            'name' => __('Green pushpin', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'green-pushpin.png'
        );


        $google_map_marker_choices['orange'] = array(
            'name' => __('Orange', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'orange.png'
        );
        $google_map_marker_choices['orange-dot'] = array(
            'name' => __('Orange dot', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'orange-dot.png'
        );


        $google_map_marker_choices['pink'] = array(
            'name' => __('Pink', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'pink.png'
        );
        $google_map_marker_choices['pink-dot'] = array(
            'name' => __('Pink dot', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'pink-dot.png'
        );
        $google_map_marker_choices['pink-pushpin'] = array(
            'name' => __('Pink pushpin', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'pink-pushpin.png'
        );


        $google_map_marker_choices['purple'] = array(
            'name' => __('Purple', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'purple.png'
        );
        $google_map_marker_choices['purple-dot'] = array(
            'name' => __('Purple dot', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'purple-dot.png'
        );
        $google_map_marker_choices['purple-pushpin'] = array(
            'name' => __('Purple pushpin', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'purple-pushpin.png'
        );


        $google_map_marker_choices['red'] = array(
            'name' => __('Red', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'red.png'
        );
        $google_map_marker_choices['red-dot'] = array(
            'name' => __('Red', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'red-dot.png'
        );
        $google_map_marker_choices['red-pushpin'] = array(
            'name' => __('Red pushpin', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'red-pushpin.png'
        );


        $google_map_marker_choices['default'] = array(

            'name' => __('Default', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'default.png'
        );

        $wp_customize->add_control(
            new WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Image_Radio_Button_Custom_Control(
                $wp_customize,
                'woo_thank_you_page_params[google_map_marker]',
                array(
                    'section' => 'woo_thank_you_page_design_google_map',
                    'label' => __('Marker', 'woocommerce-thank-you-page-customizer'),
                    'choices' => $google_map_marker_choices,
                ))
        );

        $wp_customize->add_setting('woo_thank_you_page_params[google_map_address]', array(
            'default' => $this->settings->get_default('google_map_address'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[google_map_address]', array(
            'type' => 'textarea',
            'priority' => 10,
            'section' => 'woo_thank_you_page_design_google_map',
            'label' => __('Address', 'woocommerce-thank-you-page-customizer'),
            'description' => __('Can be either {billing_address}, {shipping_address}, {store_address} or a specific address. Click "Update map" after modifying address to apply changes to your preview map.<p><span class="wtyp-button-update-changes-google-map">Update map</span></p>', 'woocommerce-thank-you-page-customizer')
        ));


        $wp_customize->add_setting('woo_thank_you_page_params[google_map_label]', array(
            'default' => $this->settings->get_default('google_map_label'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[google_map_label]', array(
            'type' => 'textarea',
            'priority' => 10,
            'section' => 'woo_thank_you_page_design_google_map',
            'label' => __('Marker label', 'woocommerce-thank-you-page-customizer'),
            'description' => __('Use {address} to refer to the address that you enter above.', 'woocommerce-thank-you-page-customizer')
        ));

        $wp_customize->add_setting('woo_thank_you_page_params[google_map_zoom_level]', array(
            'default' => $this->settings->get_default('google_map_zoom_level'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[google_map_zoom_level]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_google_map',
            'label' => __('Zoom level', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
    }

    protected function add_section_design_bing_map($wp_customize)
    {
        $wp_customize->add_section('woo_thank_you_page_design_bing_map', array(
            'priority' => 20,
            'capability' => 'manage_options',
            'theme_supports' => '',
            'title' => __('Bing map', 'woocommerce-thank-you-page-customizer'),

        ));
        $wp_customize->add_setting('woo_thank_you_page_params[bing_map_width]', array(
            'default' => $this->settings->get_default('bing_map_width'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[bing_map_width]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_bing_map',
            'label' => __('Width(px)', 'woocommerce-thank-you-page-customizer'),
            'description' => __('If set 0, width will be 100%.', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[bing_map_height]', array(
            'default' => $this->settings->get_default('bing_map_height'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[bing_map_height]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_bing_map',
            'label' => __('Height(px)', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[bing_map_view]', array(
            'default' => $this->get_params('bing_map_view'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[bing_map_view]', array(
            'type' => 'select',
            'priority' => 10,
            'section' => 'woo_thank_you_page_design_bing_map',
            'label' => __('Bing map view', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'aerial' => __('Aerial', 'woocommerce-thank-you-page-customizer'),
                'canvasDark' => __('CanvasDark', 'woocommerce-thank-you-page-customizer'),
                'canvasLight' => __('CanvasLight', 'woocommerce-thank-you-page-customizer'),
                'grayscale' => __('Grayscale', 'woocommerce-thank-you-page-customizer'),
                'road' => __('Road', 'woocommerce-thank-you-page-customizer'),
            ),
        ));
        $wp_customize->add_setting('woo_thank_you_page_params[bing_map_navbarmode]', array(
            'default' => $this->get_params('bing_map_navbarmode'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[bing_map_navbarmode]', array(
            'type' => 'select',
            'priority' => 10,
            'section' => 'woo_thank_you_page_design_bing_map',
            'label' => __('Navigation Bar Mode', 'woocommerce-thank-you-page-customizer'),
            'choices' => array(
                'compact' => __('Compact', 'woocommerce-thank-you-page-customizer'),
                'default' => __('Default', 'woocommerce-thank-you-page-customizer'),
                'minified' => __('Minified', 'woocommerce-thank-you-page-customizer'),
            ),
        ));

        $wp_customize->add_setting('woo_thank_you_page_params[bing_map_marker]', array(
            'default' => $this->settings->get_default('bing_map_marker'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $bing_map_marker_choices = array();
        for ($i = 1; $i <= 12; $i++) {
            $bing_map_marker_choices['if-marker-' . $i] = array(
                'name' => __('Marker ' . $i, 'woocommerce-thank-you-page-customizer'),
                'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'if-marker-' . $i . '.png'
            );
        }
        $bing_map_marker_choices['blue'] = array(
            'name' => __('Blue', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'blue.png'
        );
        $bing_map_marker_choices['blue-dot'] = array(
            'name' => __('Blue dot', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'blue-dot.png'
        );
        $bing_map_marker_choices['blue-pushpin'] = array(
            'name' => __('Blue pushpin', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'blue-pushpin.png'
        );

        $bing_map_marker_choices['yellow'] = array(
            'name' => __('Yellow', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'yellow.png'
        );
        $bing_map_marker_choices['yellow-dot'] = array(
            'name' => __('Yellow dot', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'yellow-dot.png'
        );
        $bing_map_marker_choices['yellow-pushpin'] = array(
            'name' => __('Yellow pushpin', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'yellow-pushpin.png'
        );


        $bing_map_marker_choices['green'] = array(
            'name' => __('Green', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'green.png'
        );
        $bing_map_marker_choices['green-dot'] = array(
            'name' => __('Green dot', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'green-dot.png'
        );
        $bing_map_marker_choices['green-pushpin'] = array(
            'name' => __('Green pushpin', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'green-pushpin.png'
        );


        $bing_map_marker_choices['orange'] = array(
            'name' => __('Orange', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'orange.png'
        );
        $bing_map_marker_choices['orange-dot'] = array(
            'name' => __('Orange dot', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'orange-dot.png'
        );


        $bing_map_marker_choices['pink'] = array(
            'name' => __('Pink', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'pink.png'
        );
        $bing_map_marker_choices['pink-dot'] = array(
            'name' => __('Pink dot', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'pink-dot.png'
        );
        $bing_map_marker_choices['pink-pushpin'] = array(
            'name' => __('Pink pushpin', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'pink-pushpin.png'
        );


        $bing_map_marker_choices['purple'] = array(
            'name' => __('Purple', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'purple.png'
        );
        $bing_map_marker_choices['purple-dot'] = array(
            'name' => __('Purple dot', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'purple-dot.png'
        );
        $bing_map_marker_choices['purple-pushpin'] = array(
            'name' => __('Purple pushpin', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'purple-pushpin.png'
        );


        $bing_map_marker_choices['red'] = array(
            'name' => __('Red', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'red.png'
        );
        $bing_map_marker_choices['red-dot'] = array(
            'name' => __('Red', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'red-dot.png'
        );
        $bing_map_marker_choices['red-pushpin'] = array(
            'name' => __('Red pushpin', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'red-pushpin.png'
        );


        $bing_map_marker_choices['default'] = array(

            'name' => __('Default', 'woocommerce-thank-you-page-customizer'),
            'image' => VI_WOOCOMMERCE_THANK_YOU_PAGE_MARKERS . 'default.png'
        );

        $wp_customize->add_control(
            new WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER_Image_Radio_Button_Custom_Control(
                $wp_customize,
                'woo_thank_you_page_params[bing_map_marker]',
                array(
                    'section' => 'woo_thank_you_page_design_bing_map',
                    'label' => __('Marker', 'woocommerce-thank-you-page-customizer'),
                    'choices' => $bing_map_marker_choices,
                ))
        );

        $wp_customize->add_setting('woo_thank_you_page_params[bing_map_address]', array(
            'default' => $this->settings->get_default('bing_map_address'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[bing_map_address]', array(
            'type' => 'textarea',
            'priority' => 10,
            'section' => 'woo_thank_you_page_design_bing_map',
            'label' => __('Address', 'woocommerce-thank-you-page-customizer'),
            'description' => __('Can be either {billing_address}, {shipping_address}, {store_address} or a specific address. Click "Update map" after modifying address to apply changes to your preview map.<p><span class="wtyp-button-update-changes-bing-map">Update map</span></p>', 'woocommerce-thank-you-page-customizer')
        ));
//
//
//		$wp_customize->add_setting( 'woo_thank_you_page_params[google_map_label]', array(
//			'default'           => $this->settings->get_default( 'google_map_label' ),
//			'type'              => 'option',
//			'capability'        => 'manage_options',
//			'sanitize_callback' => 'wp_kses_post',
//			'transport'         => 'postMessage',
//		) );
//		$wp_customize->add_control( 'woo_thank_you_page_params[google_map_label]', array(
//			'type'        => 'textarea',
//			'priority'    => 10,
//			'section'     => 'woo_thank_you_page_design_bing_map',
//			'label'       => __( 'Marker label', 'woocommerce-thank-you-page-customizer' ),
//			'description' => __( 'Use {address} to refer to the address that you enter above.', 'woocommerce-thank-you-page-customizer' )
//		) );

        $wp_customize->add_setting('woo_thank_you_page_params[bing_map_zoom_level]', array(
            'default' => $this->settings->get_default('bing_map_zoom_level'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control('woo_thank_you_page_params[bing_map_zoom_level]', array(
            'type' => 'number',
            'section' => 'woo_thank_you_page_design_bing_map',
            'label' => __('Zoom level', 'woocommerce-thank-you-page-customizer'),
            'input_attrs' => array(
                'min' => 0,
                'step' => 1
            ),
        ));
    }
}
