<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.2.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$data = new VI_WOOCOMMERCE_THANK_YOU_PAGE_DATA();
$wtypc = new WTYPC_FUNCTIONS();
if ($order) {
    $order_id = $order->get_id();
    $all_order_status = wc_get_order_statuses();
    $order_status_check = $order->get_status();
    $shortcodes = array(
        'order_number' => $order->get_order_number(),
        'order_status' => isset($all_order_status['wc-' . $order_status_check]) ? $all_order_status['wc-' . $order_status_check] : $order_status_check,
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
    if ($order->has_status('failed')) {
        ob_start();
        ?>
        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php _e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce-thank-you-page-customizer'); ?></p>

        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
            <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>"
               class="button pay"><?php _e('Pay', 'woocommerce-thank-you-page-customizer') ?></a>
            <?php if (is_user_logged_in()) : ?>
                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
                   class="button pay"><?php _e('My account', 'woocommerce-thank-you-page-customizer'); ?></a>
            <?php endif; ?>
        </p>
        <?php
        $content = ob_get_clean();
    } else {
        $language = '';
	    if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
		    $default_lang   = apply_filters( 'wpml_default_language', null );
		    $current_language = apply_filters( 'wpml_current_language', null );
		    if ( $current_language && $current_language !== $default_lang ) {
			    $language = '_'.$current_language;
		    }
	    } else if ( class_exists( 'Polylang' ) ) {
		    $default_lang   = pll_default_language( 'slug' );
		    $current_language = pll_current_language( 'slug' );
		    if ($current_language && $current_language !== $default_lang ) {
			    $language = '_'.$current_language;
		    }
	    }
	    $blocks = json_decode($data->get_params('blocks'));
        $text_editor_id = 0;
        $product_block_id = 0;
        $coupon_element = false;
        $payment_element = false;
        if (is_array($blocks) && count($blocks)) {
	        $text_editor = json_decode($data->get_params('text_editor'), true);
	        $products_blocks = json_decode($data->get_params('products'), true);
	        $order_confirmation_header = $data->get_params('order_confirmation_header',$language);
	        $order_confirmation_order_number_title = $data->get_params('order_confirmation_order_number_title',$language);
	        $order_confirmation_date_title = $data->get_params('order_confirmation_date_title',$language);
	        $order_confirmation_order_total_title = $data->get_params('order_confirmation_order_total_title',$language);
	        $order_confirmation_email_title = $data->get_params('order_confirmation_email_title',$language);
	        $order_confirmation_payment_title = $data->get_params('order_confirmation_payment_title',$language);
	        $order_details_header = $data->get_params('order_details_header',$language);
	        $order_details_product_title_text = $data->get_params('order_details_product_title_text',$language);
	        $order_details_product_value_text = $data->get_params('order_details_product_value_text',$language);
	        $order_details_product_image = $data->get_params('order_details_product_image');
	        $customer_information_header = $data->get_params('customer_information_header',$language);
	        $customer_information_billing_title = $data->get_params('customer_information_billing_title',$language);
	        $customer_information_shipping_title = $data->get_params('customer_information_shipping_title',$language);
	        $thank_you_message_header = $data->get_params('thank_you_message_header',$language);
	        $thank_you_message_message = $data->get_params('thank_you_message_message',$language);
	        if (is_array($shortcodes) && count($shortcodes)) {
		        foreach ($shortcodes as $key => $value) {
			        $order_confirmation_header = str_replace("{{$key}}", $value, $order_confirmation_header);
			        $order_details_header = str_replace("{{$key}}", $value, $order_details_header);
			        $customer_information_header = str_replace("{{$key}}", $value, $customer_information_header);
			        $thank_you_message_header = str_replace("{{$key}}", $value, $thank_you_message_header);
			        $thank_you_message_message = str_replace("{{$key}}", $value, $thank_you_message_message);
		        }
	        }
            $wrap_class = array('container');
	        if (is_rtl()){
		        $wrap_class[]='container-rtl';
	        }
            ?>
            <div class="<?php echo $data->set($wrap_class) ?>">
                <?php
                foreach ($blocks as $row_key => $row_value) {
                    if (is_array($row_value)) {
                        ?>
                        <div class="<?php echo $data->set(array(
                            'container__row',
                            'container__row_' . $row_key,
                            count($row_value) . '-column',
                        )) ?>">
                            <?php
                            if (count($row_value)) {
                                foreach ($row_value as $block_key => $block_value) {
                                    ?>
                                    <div class="<?php echo $data->set(array(
                                        'container__block',
                                        'container__block_' . $block_key,
                                    )) ?>">
                                        <?php
                                        if (is_array($block_value) && count($block_value)) {

                                            foreach ($block_value as $block_value_k => $block_value_v) {
                                                switch ($block_value_v) {
                                                    case 'order_confirmation':
                                                        ?>
                                                        <div class="<?php echo $data->set(array(
                                                            'order_confirmation__container',
                                                            'item__container'
                                                        )) ?>"
                                                             id="<?php echo $data->set('order_confirmation__container') ?>">

                                                            <div class="<?php echo $data->set(array(
                                                                'order_confirmation__header',
                                                                'order_confirmation__detail'
                                                            )) ?>">
                                                                <div class="<?php echo $data->set(array(
                                                                    'order_confirmation-header'
                                                                )) ?>">
                                                                    <div><?php echo trim(strtolower($order_confirmation_header)) === 'order confirmation' ? __('Order confirmation', 'woocommerce-thank-you-page-customizer') : wp_kses_post(nl2br($order_confirmation_header)); ?></div>
                                                                </div>
                                                            </div>
                                                            <div class="<?php echo $data->set(array(
                                                                'order_confirmation__order_number',
                                                                'order_confirmation__detail'
                                                            )) ?>">
                                                                <div class="<?php echo $data->set(array(
                                                                    'order_confirmation__order_number-title',
                                                                    'order_confirmation-title'
                                                                )) ?>">
                                                                    <div><?php echo wp_kses_post(nl2br($order_confirmation_order_number_title)); ?></div>
                                                                </div>
                                                                <div class="<?php echo $data->set(array(
                                                                    'order_confirmation__order_number-value',
                                                                    'order_confirmation-value'
                                                                )) ?>">
                                                                    <div>       <?php echo $order->get_order_number(); ?></div>
                                                                </div>
                                                            </div>

                                                            <div class="<?php echo $data->set(array(
                                                                'order_confirmation__order_date',
                                                                'order_confirmation__detail'
                                                            )) ?>">
                                                                <div class="<?php echo $data->set(array(
                                                                    'order_confirmation__order_date-title',
                                                                    'order_confirmation-title'
                                                                )) ?>">
                                                                    <div><?php echo  wp_kses_post(nl2br($order_confirmation_date_title)); ?></div>
                                                                </div>
                                                                <div class="<?php echo $data->set(array(
                                                                    'order_confirmation__order_date-value',
                                                                    'order_confirmation-value'
                                                                )) ?>">
                                                                    <div>       <?php echo wc_format_datetime($order->get_date_created()); ?></div>
                                                                </div>
                                                            </div>
                                                            <div class="<?php echo $data->set(array(
                                                                'order_confirmation__order_total',
                                                                'order_confirmation__detail'
                                                            )) ?>">
                                                                <div class="<?php echo $data->set(array(
                                                                    'order_confirmation__order_total-title',
                                                                    'order_confirmation-title'
                                                                )) ?>">
                                                                    <div><?php echo  wp_kses_post(nl2br($order_confirmation_order_total_title)); ?></div>
                                                                </div>
                                                                <div class="<?php echo $data->set(array(
                                                                    'order_confirmation__order_total-value',
                                                                    'order_confirmation-value'
                                                                )) ?>">
                                                                    <div>       <?php echo $order->get_formatted_order_total(); ?></div>
                                                                </div>
                                                            </div>
                                                            <?php if (is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $shortcodes['billing_email']) { ?>
                                                                <div class="<?php echo $data->set(array(
                                                                    'order_confirmation__order_email',
                                                                    'order_confirmation__detail'
                                                                )) ?>">
                                                                    <div class="<?php echo $data->set(array(
                                                                        'order_confirmation__order_email-title',
                                                                        'order_confirmation-title'
                                                                    )) ?>">
                                                                        <div><?php echo  wp_kses_post($order_confirmation_email_title); ?></div>
                                                                    </div>
                                                                    <div class="<?php echo $data->set(array(
                                                                        'order_confirmation__order_email-value',
                                                                        'order_confirmation-value'
                                                                    )) ?>">
                                                                        <div title="<?php esc_attr_e($shortcodes['billing_email']); ?>"><?php echo $shortcodes['billing_email']; ?></div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if ($order->get_payment_method_title()) { ?>
                                                                <div class="<?php echo $data->set(array(
                                                                    'order_confirmation__order_payment',
                                                                    'order_confirmation__detail'
                                                                )) ?>">
                                                                    <div class="<?php echo $data->set(array(
                                                                        'order_confirmation__order_payment-title',
                                                                        'order_confirmation-title'
                                                                    )) ?>">
                                                                        <div><?php echo  wp_kses_post(nl2br($order_confirmation_payment_title));?></div>
                                                                    </div>
                                                                    <div class="<?php echo $data->set(array(
                                                                        'order_confirmation__order_payment-value',
                                                                        'order_confirmation-value'
                                                                    )) ?>">
                                                                        <div><?php echo wp_kses_post($order->get_payment_method_title()); ?></div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <?php
                                                        break;
                                                    case 'order_details':
                                                        $order_details_product_quantity_in_image = $data->get_params('order_details_product_quantity_in_image');
                                                        $order_items = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));
                                                        $show_purchase_note = $order->has_status(apply_filters('woocommerce_purchase_note_order_statuses', array(
                                                            'completed',
                                                            'processing'
                                                        )));
                                                        $downloads = $order->get_downloadable_items();
                                                        $show_downloads = $order->has_downloadable_item() && $order->is_download_permitted();

                                                        if ($show_downloads) {
                                                            wc_get_template('order/order-downloads.php', array(
                                                                'downloads' => $downloads,
                                                                'show_title' => true
                                                            ));
                                                        }
                                                        ?>
                                                        <div class="<?php echo $data->set(array(
                                                            'order_details__container',
                                                            'item__container'
                                                        )) ?>"
                                                             id="<?php echo $data->set('order_details__container') ?>">
                                                            <div class="<?php echo $data->set(array(
                                                                'order_details__header',
                                                                'order_details__detail'
                                                            )) ?>">
                                                                <div class="<?php echo $data->set(array(
                                                                    'order_details-header'
                                                                )) ?>">
                                                                    <div><?php echo trim(strtolower($order_details_header)) === 'order details' ? __('Order details', 'woocommerce-thank-you-page-customizer') : wp_kses_post(nl2br($order_details_header)); ?></div>
                                                                </div>
                                                            </div>

                                                            <div class="<?php echo $data->set(array(
                                                                'order_details__header',
                                                                'order_details__detail'
                                                            )) ?>">
                                                                <div class="<?php echo $data->set(array(
                                                                    'order_details__header-title',
                                                                    'order_details-title'
                                                                )) ?>">
                                                                    <div><?php echo  wp_kses_post($order_details_product_title_text); ?></div>
                                                                </div>
                                                                <div class="<?php echo $data->set(array(
                                                                    'order_details__header-value',
                                                                    'order_details-value'
                                                                )) ?>">
                                                                    <div><?php echo  wp_kses_post($order_details_product_value_text); ?></div>
                                                                </div>
                                                            </div>
                                                            <div class="<?php echo $data->set(array(
                                                                'order_details__order_items'
                                                            )) ?>">
                                                                <?php
                                                                foreach ($order_items as $item_id => $item) {
                                                                    $product = $item->get_product();
                                                                    if (!$product) {
                                                                        continue;
                                                                    }
                                                                    $purchase_note = $product->get_purchase_note();
                                                                    ?>
                                                                    <div class="<?php echo $data->set(array(
                                                                        'order_details__product',
                                                                        'order_details__detail'
                                                                    )) ?>">
                                                                        <div class="<?php echo $data->set(array(
                                                                            'order_details__product-title',
                                                                            'order_details-title'
                                                                        )) ?>">
                                                                            <?php
                                                                            $is_visible = $product && $product->is_visible();
                                                                            $product_permalink = apply_filters('woocommerce_order_item_permalink', $is_visible ? $product->get_permalink($item) : '', $item, $order);
                                                                            $product_image_src = wc_placeholder_img_src();
                                                                            $alt = '';
                                                                            $product_quantity_html = ' <strong class="product-quantity">' . sprintf('&times; %s', $item->get_quantity()) . '</strong>';
                                                                            if ($order_details_product_quantity_in_image) {
                                                                                $product_quantity_html = '<span class="' . $data->set('order-item-product-quantity') . '">' . $item->get_quantity() . '</span>';
                                                                            }
                                                                            if ($product->get_image_id()) {
                                                                                $product_image_src = wp_get_attachment_thumb_url($product->get_image_id());
                                                                                $alt = get_post_meta($product->get_id(), '_wp_attachment_image_alt', true);
                                                                            }
                                                                            if ($order_details_product_image && $product_image_src) {
                                                                                echo apply_filters('woo_thank_you_page_order_item_image',
                                                                                    $product_permalink ? sprintf('<div><a href="%s" class="%s"><img class="%s" src="%s" alt="%s">%s</a></div>',
                                                                                        $product_permalink, $data->set('order-item-image-wrap'), $data->set('order-item-image'), $product_image_src, $alt ? $alt : $item->get_name(), $order_details_product_quantity_in_image ? apply_filters('woocommerce_order_item_quantity_html', $product_quantity_html, $item) : '') : $item->get_name(),
                                                                                    $item, $is_visible,$order_details_product_quantity_in_image);
                                                                            }
                                                                            ?>
                                                                            <div>
                                                                                <?php
                                                                                echo apply_filters('woocommerce_order_item_name', $product_permalink ? sprintf('<a href="%s">%s</a>', $product_permalink, $item->get_name()) : $item->get_name(), $item, $is_visible);
                                                                                if (!$order_details_product_quantity_in_image) {
                                                                                    echo apply_filters('woocommerce_order_item_quantity_html', $product_quantity_html, $item);
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                            <?php
                                                                            do_action('woocommerce_order_item_meta_start', $item_id, $item, $order, false);

                                                                            wc_display_item_meta($item);

                                                                            do_action('woocommerce_order_item_meta_end', $item_id, $item, $order, false);
                                                                            ?>
                                                                        </div>
                                                                        <div class="<?php echo $data->set(array(
                                                                            'order_details__product-value',
                                                                            'order_details-value'
                                                                        )) ?>">
                                                                            <?php echo $order->get_formatted_line_subtotal($item); ?>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    if ($show_purchase_note && $purchase_note) {
                                                                        ?>
                                                                        <div class="<?php echo $data->set(array(
                                                                            'order_details__purchase_note',
                                                                            'order_details__detail'
                                                                        )) ?>">
                                                                            <?php echo wpautop(do_shortcode(wp_kses_post($purchase_note))); ?>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                            <div class="<?php echo $data->set(array(
                                                                'order_details__order_item_total'
                                                            )) ?>">
                                                                <?php
                                                                foreach ($order->get_order_item_totals() as $key => $total) {
                                                                    ?>
                                                                    <div class="<?php echo $data->set(array(
                                                                        'order_details__' . $key,
                                                                        'order_details__detail',
                                                                    )) ?>">
                                                                        <div class="<?php echo $data->set(array(
                                                                            'order_details-title'
                                                                        )) ?>">
                                                                            <div><?php echo str_replace(':', '', $total['label']); ?></div>
                                                                        </div>
                                                                        <div class="<?php echo $data->set(array(
                                                                            'order_details-value'
                                                                        )) ?>">
                                                                            <?php
                                                                            if ($key == 'order_total') {
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
                                                            if ($order->get_customer_note()) {
                                                                ?>
                                                                <div class="<?php echo $data->set(array(
                                                                    'order_details__detail'
                                                                )) ?>">
                                                                    <div class="<?php echo $data->set(array(
                                                                        'order_details-value'
                                                                    )) ?>">
                                                                        <div><?php echo wp_kses_post(apply_filters('wtypc_get_customer_note',__('Note', 'woocommerce-thank-you-page-customizer'))); ?></div>
                                                                    </div>
                                                                    <div class="<?php echo $data->set(array(
                                                                        'order_details-value'
                                                                    )) ?>">
                                                                        <div><?php echo wptexturize($order->get_customer_note()); ?></div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                        <?php
                                                        break;
                                                    case 'customer_information':
                                                        $show_shipping = !wc_ship_to_billing_address_only() && $order->needs_shipping_address();
                                                        ?>
                                                        <div class="<?php echo $data->set(array(
                                                            'customer_information__container',
                                                            'item__container'
                                                        )) ?>"
                                                             id="<?php echo $data->set('customer_information__container') ?>">
                                                            <div class="<?php echo $data->set(array(
                                                                'customer_information__header',
                                                                'customer_information__detail',
                                                            )) ?>">
                                                                <div class="<?php echo $data->set('customer_information-header') ?>">
                                                                    <div><?php echo trim(strtolower($customer_information_header)) === 'customer information' ? __('Customer information', 'woocommerce-thank-you-page-customizer') : wp_kses_post(nl2br($customer_information_header)); ?></div>
                                                                </div>
                                                            </div>
                                                            <div class="<?php echo $data->set(array(
                                                                'customer_information__address',
                                                                'customer_information__detail',
                                                            )) ?>">
                                                                <div class="<?php echo $data->set(array(
                                                                    'customer_information__billing_address',
                                                                )) ?>">
                                                                    <div class="<?php echo $data->set(array(
                                                                        'customer_information__billing_address-header',
                                                                    )) ?>">
                                                                        <?php echo wp_kses_post(nl2br($customer_information_billing_title)); ?>
                                                                    </div>
                                                                    <div class="<?php echo $data->set(array(
                                                                        'customer_information__billing_address-address',
                                                                    )) ?>">
                                                                        <?php echo wp_kses_post($order->get_formatted_billing_address(__('N/A', 'woocommerce-thank-you-page-customizer'))); ?>

                                                                        <?php if ($order->get_billing_phone()) : ?>
                                                                            <div><?php echo esc_html($order->get_billing_phone()); ?></div>
                                                                        <?php endif; ?>

                                                                        <?php if ($shortcodes['billing_email']) : ?>
                                                                            <div><?php echo esc_html($shortcodes['billing_email']); ?></div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                if ($show_shipping) {
                                                                    ?>
                                                                    <div class="<?php echo $data->set(array(
                                                                        'customer_information__shipping_address',
                                                                    )) ?>">
                                                                        <div class="<?php echo $data->set(array(
                                                                            'customer_information__shipping_address-header',
                                                                        )) ?>">
                                                                            <?php echo wp_kses_post(nl2br($customer_information_shipping_title)); ?>
                                                                        </div>
                                                                        <div class="<?php echo $data->set(array(
                                                                            'customer_information__shipping_address-address',
                                                                        )) ?>">
                                                                            <?php echo wp_kses_post($order->get_formatted_shipping_address(__('N/A', 'woocommerce-thank-you-page-customizer'))); ?>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        break;
                                                    case 'social_icons':
                                                        $social_icons = array(

                                                            'social_icons_align' => $data->get_params('social_icons_align'),

                                                        );
                                                        $social_icons_target = $data->get_params('social_icons_target');
                                                        $social_icons_header = $data->get_params('social_icons_header',$language);
                                                        $facebook_url = $data->get_params('social_icons_facebook_url');
                                                        $twitter_url = $data->get_params('social_icons_twitter_url');
                                                        $pinterest_url = $data->get_params('social_icons_pinterest_url');
                                                        $instagram_url = $data->get_params('social_icons_instagram_url');
                                                        $dribbble_url = $data->get_params('social_icons_dribbble_url');
                                                        $google_url = $data->get_params('social_icons_google_url');
                                                        $tumblr_url = $data->get_params('social_icons_tumblr_url');
                                                        $vkontakte_url = $data->get_params('social_icons_vkontakte_url');
                                                        $linkedin_url = $data->get_params('social_icons_linkedin_url');
                                                        $youtube_url = $data->get_params('social_icons_youtube_url');

                                                        $facebook_select = $data->get_params('social_icons_facebook_select');
                                                        $twitter_select = $data->get_params('social_icons_twitter_select');
                                                        $pinterest_select = $data->get_params('social_icons_pinterest_select');
                                                        $instagram_select = $data->get_params('social_icons_instagram_select');
                                                        $dribbble_select = $data->get_params('social_icons_dribbble_select');
                                                        $google_select = $data->get_params('social_icons_google_select');
                                                        $tumblr_select = $data->get_params('social_icons_tumblr_select');
                                                        $vkontakte_select = $data->get_params('social_icons_vkontakte_select');
                                                        $linkedin_select = $data->get_params('social_icons_linkedin_select');
                                                        $youtube_select = $data->get_params('social_icons_youtube_select');
                                                        $html = '<div class="' . $data->set(array(
                                                                'social_icons__container',
                                                                'item__container'
                                                            )) . '" id="' . $data->set('social_icons__container') . '">';
                                                        $html .= '<div class="' . $data->set(array(
                                                                'social_icons__header',
                                                            )) . '"><div class="' . $data->set('social_icons-header') . '"><div>' . wp_kses_post(nl2br($social_icons_header)) . '</div></div></div>';
                                                        $html .= '<ul class="wtyp-list-socials wtyp-list-unstyled" id="wtyp-sharing-accounts">';
                                                        if ($facebook_url) {
                                                            ob_start();
                                                            ?>
                                                            <a target="<?php echo $social_icons_target; ?>"
                                                               href="<?php echo esc_url($facebook_url) ?>"
                                                               class="wtyp-social-button wtyp-facebook"
                                                               title="<?php esc_html_e('Follow Facebook', 'woocommerce-thank-you-page-customizer') ?>">
                                                                <span class="wtyp-social-icon <?php esc_attr_e($facebook_select) ?>"></span></a>
                                                            <?php $facebook_html = ob_get_clean();

                                                            $html .= '<li class="wtyp-facebook-follow">' . $facebook_html . '</li>';
                                                        }
                                                        if ($twitter_url) {
                                                            ob_start();
                                                            ?>
                                                            <a target="<?php echo $social_icons_target; ?>"
                                                               href="<?php echo esc_url($twitter_url) ?>"
                                                               class="wtyp-social-button wtyp-twitter"
                                                               title="<?php esc_html_e('Follow Twitter', 'woocommerce-thank-you-page-customizer') ?>">
                                                                <span class="wtyp-social-icon <?php esc_attr_e($twitter_select) ?>"></span>
                                                            </a>
                                                            <?php
                                                            $twitter_html = ob_get_clean();
                                                            $html .= '<li class="wtyp-twitter-follow">' . $twitter_html . '</li>';
                                                        }
                                                        if ($pinterest_url) {
                                                            ob_start();
                                                            ?>
                                                            <a target="<?php echo $social_icons_target; ?>"
                                                               href="<?php echo esc_url($pinterest_url) ?>"
                                                               class="wtyp-social-button wtyp-pinterest"
                                                               title="<?php esc_html_e('Follow Pinterest', 'woocommerce-thank-you-page-customizer') ?>">
                                                                <span class="wtyp-social-icon <?php esc_attr_e($pinterest_select) ?>"></span>
                                                            </a>
                                                            <?php
                                                            $pinterest_html = ob_get_clean();
                                                            $html .= '<li class="wtyp-pinterest-follow">' . $pinterest_html . '</li>';
                                                        }
                                                        if ($instagram_url) {
                                                            ob_start();
                                                            ?>
                                                            <a target="<?php echo $social_icons_target; ?>"
                                                               href="<?php echo esc_url($instagram_url) ?>"
                                                               class="wtyp-social-button wtyp-instagram"
                                                               title="<?php esc_html_e('Follow Instagram', 'woocommerce-thank-you-page-customizer') ?>">
                                                                <span class="wtyp-social-icon <?php esc_attr_e($instagram_select) ?>"></span>
                                                            </a>
                                                            <?php
                                                            $instagram_html = ob_get_clean();
                                                            $html .= '<li class="wtyp-instagram-follow">' . $instagram_html . '</li>';
                                                        }
                                                        if ($dribbble_url) {
                                                            ob_start();
                                                            ?>
                                                            <a target="<?php echo $social_icons_target; ?>"
                                                               href="<?php echo esc_url($dribbble_url) ?>"
                                                               class="wtyp-social-button wtyp-dribbble"
                                                               title="<?php esc_html_e('Follow Dribbble', 'woocommerce-thank-you-page-customizer') ?>">
                                                                <span class="wtyp-social-icon <?php esc_attr_e($dribbble_select) ?>"></span>
                                                            </a>
                                                            <?php
                                                            $dribbble_html = ob_get_clean();
                                                            $html .= '<li class="wtyp-dribbble-follow">' . $dribbble_html . '</li>';
                                                        }
                                                        if ($tumblr_url) {
                                                            ob_start();
                                                            ?>
                                                            <a target="<?php echo $social_icons_target; ?>"
                                                               href="<?php echo esc_url($tumblr_url) ?>"
                                                               class="wtyp-social-button wtyp-tumblr"
                                                               title="<?php esc_html_e('Follow Tumblr', 'woocommerce-thank-you-page-customizer') ?>">
                                                                <span class="wtyp-social-icon <?php esc_attr_e($tumblr_select) ?>"></span>
                                                            </a>
                                                            <?php
                                                            $tumblr_html = ob_get_clean();
                                                            $html .= '<li class="wtyp-tumblr-follow">' . $tumblr_html . '</li>';
                                                        }
                                                        if ($google_url) {
                                                            ob_start();
                                                            ?>
                                                            <a target="<?php echo $social_icons_target; ?>"
                                                               href="<?php echo esc_url($google_url) ?>"
                                                               class="wtyp-social-button wtyp-google-plus"
                                                               title="<?php esc_html_e('Follow Google Plus', 'woocommerce-thank-you-page-customizer') ?>">
                                                                <span class="wtyp-social-icon <?php esc_attr_e($google_select) ?>"></span>
                                                            </a>
                                                            <?php
                                                            $google_html = ob_get_clean();
                                                            $html .= '<li class="wtyp-google-follow">' . $google_html . '</li>';
                                                        }
                                                        if ($vkontakte_url) {
                                                            ob_start();
                                                            ?>
                                                            <a target="<?php echo $social_icons_target; ?>"
                                                               href="<?php echo esc_url($vkontakte_url) ?>"
                                                               class="wtyp-social-button wtyp-vk"
                                                               title="<?php esc_html_e('Follow VK', 'woocommerce-thank-you-page-customizer') ?>">
                                                                <span class="wtyp-social-icon <?php esc_attr_e($vkontakte_select) ?>"></span>
                                                            </a>
                                                            <?php
                                                            $vkontakte_html = ob_get_clean();
                                                            $html .= '<li class="wtyp-vkontakte-follow">' . $vkontakte_html . '</li>';
                                                        }
                                                        if ($linkedin_url) {
                                                            ob_start();
                                                            ?>
                                                            <a target="<?php echo $social_icons_target; ?>"
                                                               href="<?php echo esc_url($linkedin_url) ?>"
                                                               class="wtyp-social-button wtyp-linkedin"
                                                               title="<?php esc_html_e('Follow Linkedin', 'woocommerce-thank-you-page-customizer') ?>">
                                                                <span class="wtyp-social-icon <?php esc_attr_e($linkedin_select) ?>"></span>
                                                            </a>
                                                            <?php
                                                            $linkedin_html = ob_get_clean();
                                                            $html .= '<li class="wtyp-linkedin-follow">' . $linkedin_html . '</li>';
                                                        }

                                                        if ($youtube_url) {
                                                            ob_start();
                                                            ?>
                                                            <a target="<?php echo $social_icons_target; ?>"
                                                               href="<?php echo esc_url($youtube_url) ?>"
                                                               class="wtyp-social-button wtyp-youtube"
                                                               title="<?php esc_html_e('Follow Youtube', 'woocommerce-thank-you-page-customizer') ?>">
                                                                <span class="wtyp-social-icon <?php esc_attr_e($youtube_select) ?>"></span>
                                                            </a>
                                                            <?php
                                                            $youtube_html = ob_get_clean();
                                                            $html .= '<li class="wtyp-youtube-follow">' . $youtube_html . '</li>';
                                                        }
                                                        $html = apply_filters('wtyp_after_socials_html', $html);
                                                        $html .= '</ul></div>';
                                                        echo $html;
                                                        break;
                                                    case 'text_editor':
                                                        if (is_array($text_editor) && count($text_editor)) {
                                                            $text = array_splice($text_editor, 0, 1)[0];
	                                                        if (is_string($text)){
		                                                        $text = base64_decode( $text );
	                                                        }else {
		                                                        $text = base64_decode( $text['0'.$language]??'' );
	                                                        }
	                                                        if (is_array($shortcodes) && count($shortcodes)) {
		                                                        foreach ($shortcodes as $key => $value) {
			                                                        $text = str_replace("{{$key}}", $value, $text);
		                                                        }
	                                                        }
	                                                        $text = do_shortcode($text);
	                                                        if (is_array($shortcodes) && count($shortcodes)) {
		                                                        foreach ($shortcodes as $key => $value) {
			                                                        $text = str_replace("{{$key}}", $value, $text);
		                                                        }
	                                                        }
                                                            ?>
                                                            <div class="<?php echo $data->set(array(
                                                                'text-editor',
                                                                'item__container'
                                                            )) ?>"
                                                                 id="<?php echo $data->set('text-editor-' . $text_editor_id) ?>">
                                                                <div class="<?php echo $data->set('text-editor-content') ?>">
                                                                    <?php
                                                                    echo $text;
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <?php
                                                            $text_editor_id++;
                                                        }
                                                        break;
                                                    case 'products':
                                                        if (is_array($products_blocks) && count($products_blocks)) {
                                                            $products = array_splice($products_blocks, 0, 1)[0];

                                                            $product_ids = isset($products['product_ids']) ? $products['product_ids'] : array();
                                                            $excluded_product_ids = isset($products['excluded_product_ids']) ? $products['excluded_product_ids'] : array();
                                                            $product_categories = isset($products['product_categories']) ? $products['product_categories'] : array();
                                                            $excluded_product_categories = isset($products['excluded_product_categories']) ? $products['excluded_product_categories'] : array();
                                                            $order_by = isset($products['order_by']) ? $products['order_by'] : 'title';
                                                            $visibility = isset($products['visibility']) ? $products['visibility'] : 'visible';
                                                            $order_ = isset($products['order']) ? $products['order'] : 'desc';
                                                            $columns = isset($products['columns']) ? $products['columns'] : '4';
                                                            $limit = isset($products['limit']) ? $products['limit'] : '';
                                                            $product_options = isset($products['product_options']) ? $products['product_options'] : 'none';
                                                            $slider_loop = isset($products['slider_loop']) ? $products['slider_loop'] : '1';
                                                            $slider_move = isset($products['slider_move']) ? $products['slider_move'] : '1';
                                                            $slider_slideshow = isset($products['slider_slideshow']) ? $products['slider_slideshow'] : '1';
                                                            $slider_slideshow_speed = isset($products['slider_slideshow_speed']) ? $products['slider_slideshow_speed'] : '5000';
                                                            $slider_pause_on_hover = isset($products['slider_pause_on_hover']) ? $products['slider_pause_on_hover'] : '1';
                                                            $found_products = array();
                                                            switch ($product_options) {
                                                                case 'best_selling':
                                                                    $args = array(
                                                                        'post_type' => 'product',
                                                                        'post_status' => 'publish',
                                                                        'posts_per_page' => -1,
                                                                        'meta_key' => 'total_sales',
                                                                        'order' => 'DESC',
                                                                        'orderby' => 'meta_value_num',
                                                                    );
                                                                    if (count($product_categories) || count($excluded_product_categories)) {
                                                                        $args['tax_query'] = array(
                                                                            'relation' => 'AND',
                                                                            array(
                                                                                'taxonomy' => 'product_cat',
                                                                                'field' => 'term_id',
                                                                                'terms' => $excluded_product_categories,
                                                                                'operator' => 'NOT IN'
                                                                            )
                                                                        );
                                                                        if (count($product_categories)) {
                                                                            $args['tax_query'][] = array(
                                                                                'taxonomy' => 'product_cat',
                                                                                'field' => 'term_id',
                                                                                'terms' => $product_categories,
                                                                                'operator' => 'IN'
                                                                            );
                                                                        }
                                                                    }
                                                                    if (count($product_ids)) {
                                                                        $args['post__in'] = $product_ids;
                                                                    }
                                                                    $the_query = new WP_Query($args);
                                                                    if ($the_query->have_posts()) {
                                                                        while ($the_query->have_posts()) {
                                                                            $the_query->the_post();
                                                                            $found_products[] = get_the_ID();
                                                                        }
                                                                    }
                                                                    wp_reset_postdata();
                                                                    if (count($excluded_product_ids)) {
                                                                        $found_products = array_diff($found_products, $excluded_product_ids);
                                                                    }
                                                                    break;
                                                                case 'sale':
                                                                    $sale_products = wc_get_product_ids_on_sale();
                                                                    if (count($product_ids)) {
                                                                        $sale_products = array_intersect($sale_products, $product_ids);
                                                                    }
                                                                    $sale_products = array_diff($sale_products, $excluded_product_ids);

                                                                    if (count($sale_products) && count($product_categories) || count($excluded_product_categories)) {
                                                                        $args = array(
                                                                            'post_type' => 'product',
                                                                            'post_status' => 'publish',
                                                                            'posts_per_page' => -1,
                                                                            'post__in' => $sale_products,
                                                                            'tax_query' => array(
                                                                                'relation' => 'AND',
                                                                                array(
                                                                                    'taxonomy' => 'product_cat',
                                                                                    'field' => 'term_id',
                                                                                    'terms' => $excluded_product_categories,
                                                                                    'operator' => 'NOT IN'
                                                                                )
                                                                            )
                                                                        );
                                                                        if (count($product_categories)) {
                                                                            $args['tax_query'][] = array(
                                                                                'taxonomy' => 'product_cat',
                                                                                'field' => 'term_id',
                                                                                'terms' => $product_categories,
                                                                                'operator' => 'IN'
                                                                            );
                                                                        }
                                                                        $the_query = new WP_Query($args);
                                                                        if ($the_query->have_posts()) {
                                                                            while ($the_query->have_posts()) {
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
                                                                    if (count($product_ids)) {
                                                                        $featured_products = array_intersect($featured_products, $product_ids);
                                                                    }
                                                                    $featured_products = array_diff($featured_products, $excluded_product_ids);
                                                                    if (count($featured_products) && count($product_categories) || count($excluded_product_categories)) {
                                                                        $args = array(
                                                                            'post_type' => 'product',
                                                                            'post_status' => 'publish',
                                                                            'posts_per_page' => -1,
                                                                            'post__in' => $featured_products,
                                                                            'tax_query' => array(
                                                                                'relation' => 'AND',
                                                                                array(
                                                                                    'taxonomy' => 'product_cat',
                                                                                    'field' => 'term_id',
                                                                                    'terms' => $excluded_product_categories,
                                                                                    'operator' => 'NOT IN'
                                                                                )
                                                                            )
                                                                        );
                                                                        if (count($product_categories)) {
                                                                            $args['tax_query'][] = array(
                                                                                'taxonomy' => 'product_cat',
                                                                                'field' => 'term_id',
                                                                                'terms' => $product_categories,
                                                                                'operator' => 'IN'
                                                                            );
                                                                        }
                                                                        $the_query = new WP_Query($args);
                                                                        if ($the_query->have_posts()) {
                                                                            while ($the_query->have_posts()) {
                                                                                $the_query->the_post();
                                                                                $found_products[] = get_the_ID();
                                                                            }
                                                                        }
                                                                        wp_reset_postdata();
                                                                    } else {
                                                                        $found_products = $featured_products;
                                                                    }

                                                                    break;
                                                                case 'recent':
                                                                    $args = array(
                                                                        'post_type' => 'product',
                                                                        'post_status' => 'publish',
                                                                        'posts_per_page' => -1,
                                                                        'order' => 'DESC',
                                                                        'orderby' => 'date',
                                                                    );
                                                                    if (count($product_categories) || count($excluded_product_categories)) {
                                                                        $args['tax_query'] = array(
                                                                            'relation' => 'AND',
                                                                            array(
                                                                                'taxonomy' => 'product_cat',
                                                                                'field' => 'term_id',
                                                                                'terms' => $excluded_product_categories,
                                                                                'operator' => 'NOT IN'
                                                                            )
                                                                        );
                                                                        if (count($product_categories)) {
                                                                            $args['tax_query'][] = array(
                                                                                'taxonomy' => 'product_cat',
                                                                                'field' => 'term_id',
                                                                                'terms' => $product_categories,
                                                                                'operator' => 'IN'
                                                                            );
                                                                        }
                                                                    }
                                                                    if (count($product_ids)) {
                                                                        $args['post__in'] = $product_ids;
                                                                    }
                                                                    $the_query = new WP_Query($args);
                                                                    if ($the_query->have_posts()) {
                                                                        while ($the_query->have_posts()) {
                                                                            $the_query->the_post();
                                                                            $found_products[] = get_the_ID();
                                                                        }
                                                                    }

                                                                    wp_reset_postdata();
                                                                    if (count($excluded_product_ids)) {
                                                                        $found_products = array_diff($found_products, $excluded_product_ids);
                                                                    }
                                                                    break;

                                                                case 'recently_viewed':
                                                                    $viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array)explode('|', wp_unslash($_COOKIE['woocommerce_recently_viewed'])) : array(); // @codingStandardsIgnoreLine
                                                                    $viewed_products = array_reverse(array_filter(array_map('absint', $viewed_products)));
                                                                    if (count($product_ids)) {
                                                                        $viewed_products = array_intersect($viewed_products, $product_ids);
                                                                    }
                                                                    $viewed_products = array_diff($viewed_products, $excluded_product_ids);
                                                                    if (count($viewed_products) && count($product_categories) || count($excluded_product_categories)) {
                                                                        $args = array(
                                                                            'post_type' => 'product',
                                                                            'post_status' => 'publish',
                                                                            'posts_per_page' => -1,
                                                                            'post__in' => $viewed_products,
                                                                            'tax_query' => array(
                                                                                'relation' => 'AND',
                                                                                array(
                                                                                    'taxonomy' => 'product_cat',
                                                                                    'field' => 'term_id',
                                                                                    'terms' => $excluded_product_categories,
                                                                                    'operator' => 'NOT IN'
                                                                                )
                                                                            )
                                                                        );
                                                                        if (count($product_categories)) {
                                                                            $args['tax_query'][] = array(
                                                                                'taxonomy' => 'product_cat',
                                                                                'field' => 'term_id',
                                                                                'terms' => $product_categories,
                                                                                'operator' => 'IN'
                                                                            );
                                                                        }
                                                                        $the_query = new WP_Query($args);
                                                                        if ($the_query->have_posts()) {
                                                                            while ($the_query->have_posts()) {
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
                                                                    $order_items = $order->get_items();
                                                                    $order_items_products = array();
                                                                    if (is_array($order_items) && count($order_items)) {
                                                                        foreach ($order_items as $order_item) {
                                                                            $product_id = $order_item->get_product_id();
                                                                            $order_items_products[] = $product_id;
                                                                            if (count($product_ids) && !in_array($product_id, $product_ids)) {
                                                                                continue;
                                                                            }
                                                                            if (count($excluded_product_ids) && in_array($product_id, $product_ids)) {
                                                                                continue;
                                                                            }
                                                                            if (count($product_categories) || count($excluded_product_categories)) {
                                                                                $product = wc_get_product($product_id);
                                                                                if ($product) {
                                                                                    $categories = $product->get_category_ids();
                                                                                    if (count($product_categories) && !count(array_intersect($categories, $product_categories))) {
                                                                                        continue;
                                                                                    }
                                                                                    if (count($excluded_product_categories) && count(array_intersect($categories, $excluded_product_categories))) {
                                                                                        continue;
                                                                                    }
                                                                                }
                                                                            }
                                                                            $p_related = wc_get_related_products($product_id);
                                                                            if (is_array($p_related) && count($p_related)) {
                                                                                $found_products += $p_related;
                                                                            }
                                                                        }
                                                                    }
                                                                    $found_products = array_diff($found_products, $order_items_products);
                                                                    break;
                                                                case 'up_sells':
                                                                    $order_items = $order->get_items();
                                                                    $order_items_products = array();
                                                                    if (is_array($order_items) && count($order_items)) {
                                                                        foreach ($order_items as $order_item) {
                                                                            $product_id = $order_item->get_product_id();
                                                                            $order_items_products[] = $product_id;
                                                                            if (count($product_ids) && !in_array($product_id, $product_ids)) {
                                                                                continue;
                                                                            }
                                                                            if (count($excluded_product_ids) && in_array($product_id, $product_ids)) {
                                                                                continue;
                                                                            }
                                                                            if (count($product_categories) || count($excluded_product_categories)) {
                                                                                $product = wc_get_product($product_id);
                                                                                if ($product) {
                                                                                    $categories = $product->get_category_ids();
                                                                                    if (count($product_categories) && !count(array_intersect($categories, $product_categories))) {
                                                                                        continue;
                                                                                    }
                                                                                    if (count($excluded_product_categories) && count(array_intersect($categories, $excluded_product_categories))) {
                                                                                        continue;
                                                                                    }
                                                                                }
                                                                            }
                                                                            $p_up_sells = get_post_meta($product_id, '_upsell_ids', true);
                                                                            if (is_array($p_up_sells) && count($p_up_sells)) {
                                                                                $found_products += $p_up_sells;
                                                                            }
                                                                        }
                                                                    }
                                                                    $found_products = array_diff($found_products, $order_items_products);
                                                                    break;
                                                                case 'cross_sells':
                                                                    $order_items = $order->get_items();
                                                                    $order_items_products = array();
                                                                    if (is_array($order_items) && count($order_items)) {
                                                                        foreach ($order_items as $order_item) {
                                                                            $product_id = $order_item->get_product_id();
                                                                            $order_items_products[] = $product_id;
                                                                            if (count($product_ids) && !in_array($product_id, $product_ids)) {
                                                                                continue;
                                                                            }
                                                                            if (count($excluded_product_ids) && in_array($product_id, $product_ids)) {
                                                                                continue;
                                                                            }
                                                                            if (count($product_categories) || count($excluded_product_categories)) {
                                                                                $product = wc_get_product($product_id);
                                                                                if ($product) {
                                                                                    $categories = $product->get_category_ids();
                                                                                    if (count($product_categories) && !count(array_intersect($categories, $product_categories))) {
                                                                                        continue;
                                                                                    }
                                                                                    if (count($excluded_product_categories) && count(array_intersect($categories, $excluded_product_categories))) {
                                                                                        continue;
                                                                                    }
                                                                                }
                                                                            }
                                                                            $p_cross_sells = get_post_meta($product_id, '_crosssell_ids', true);
                                                                            if (is_array($p_cross_sells) && count($p_cross_sells)) {
                                                                                $found_products += $p_cross_sells;
                                                                            }
                                                                        }
                                                                    }
                                                                    $found_products = array_diff($found_products, $order_items_products);

                                                                    break;
                                                                case 'same_category':
                                                                    $order_items = $order->get_items();
                                                                    $order_items_products = array();
                                                                    if (is_array($order_items) && count($order_items)) {
                                                                        foreach ($order_items as $order_item) {
                                                                            $product_id = $order_item->get_product_id();
                                                                            $order_items_products[] = $product_id;
                                                                            $product = wc_get_product($product_id);
                                                                            if ($product) {
                                                                                $categories = $product->get_category_ids();
                                                                                if (count($categories)) {
                                                                                    if (count($product_categories)) {
                                                                                        $categories = array_intersect($categories, $product_categories);
                                                                                    }
                                                                                    $args = array(
                                                                                        'post_type' => 'product',
                                                                                        'post_status' => 'publish',
                                                                                        'posts_per_page' => -1,
                                                                                        'tax_query' => array(
                                                                                            'relation' => 'AND',
                                                                                            array(
                                                                                                'taxonomy' => 'product_cat',
                                                                                                'field' => 'term_id',
                                                                                                'terms' => $categories,
                                                                                                'operator' => 'IN'
                                                                                            ),
                                                                                            array(
                                                                                                'taxonomy' => 'product_cat',
                                                                                                'field' => 'term_id',
                                                                                                'terms' => $excluded_product_categories,
                                                                                                'operator' => 'NOT IN'
                                                                                            )
                                                                                        )
                                                                                    );
                                                                                    if (count($product_ids)) {
                                                                                        $args['post__in'] = $product_ids;
                                                                                    }

                                                                                    $the_query = new WP_Query($args);
                                                                                    if ($the_query->have_posts()) {
                                                                                        while ($the_query->have_posts()) {
                                                                                            $the_query->the_post();
                                                                                            $found_products[] = get_the_ID();
                                                                                        }
                                                                                    }
                                                                                    wp_reset_postdata();
                                                                                    /*post__not_in will be ignored if using in the same query as post__in*/
                                                                                    if (count($excluded_product_ids)) {
                                                                                        $found_products = array_diff($found_products, $excluded_product_ids);
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    $found_products = array_diff($found_products, $order_items_products);
                                                                    break;
                                                                case 'none':
                                                                    if (count($product_categories)) {
                                                                        $args = array(
                                                                            'post_type' => 'product',
                                                                            'post_status' => 'publish',
                                                                            'posts_per_page' => -1,
                                                                            'tax_query' => array(
                                                                                'relation' => 'AND',
                                                                                array(
                                                                                    'taxonomy' => 'product_cat',
                                                                                    'field' => 'term_id',
                                                                                    'terms' => $product_categories,
                                                                                    'operator' => 'IN'
                                                                                ),
                                                                                array(
                                                                                    'taxonomy' => 'product_cat',
                                                                                    'field' => 'term_id',
                                                                                    'terms' => $excluded_product_categories,
                                                                                    'operator' => 'NOT IN'
                                                                                )
                                                                            )
                                                                        );
                                                                        $the_query = new WP_Query($args);
                                                                        if ($the_query->have_posts()) {
                                                                            while ($the_query->have_posts()) {
                                                                                $the_query->the_post();
                                                                                $found_products[] = get_the_ID();
                                                                            }
                                                                        }
                                                                        wp_reset_postdata();
                                                                        if (count($product_ids)) {
                                                                            if (count($found_products)) {
                                                                                $found_products = array_intersect($found_products, $product_ids);

                                                                            } else {
                                                                                $found_products = $product_ids;

                                                                            }
                                                                        }
                                                                        if (count($excluded_product_ids)) {
                                                                            $found_products = array_diff($found_products, $excluded_product_ids);
                                                                        }
                                                                    } elseif (count($excluded_product_categories)) {
                                                                        $args = array(
                                                                            'post_type' => 'product',
                                                                            'post_status' => 'publish',
                                                                            'posts_per_page' => -1,
                                                                            'tax_query' => array(
                                                                                array(
                                                                                    'taxonomy' => 'product_cat',
                                                                                    'field' => 'term_id',
                                                                                    'terms' => $excluded_product_categories,
                                                                                    'operator' => 'NOT IN'
                                                                                )
                                                                            )
                                                                        );
                                                                        $the_query = new WP_Query($args);
                                                                        if ($the_query->have_posts()) {
                                                                            while ($the_query->have_posts()) {
                                                                                $the_query->the_post();
                                                                                $found_products[] = get_the_ID();
                                                                            }
                                                                        }
                                                                        wp_reset_postdata();
                                                                        if (count($product_ids)) {
                                                                            if (count($found_products)) {
                                                                                $found_products = array_intersect($found_products, $product_ids);

                                                                            } else {
                                                                                $found_products = $product_ids;

                                                                            }
                                                                        }
                                                                        if (count($excluded_product_ids)) {
                                                                            $found_products = array_diff($found_products, $excluded_product_ids);
                                                                        }
                                                                    } else {
                                                                        if (count($product_ids)) {
                                                                            $found_products = array_diff($product_ids, $excluded_product_ids);
                                                                        } elseif (count($excluded_product_ids)) {
                                                                            $args = array(
                                                                                'post_type' => 'product',
                                                                                'post_status' => 'publish',
                                                                                'posts_per_page' => -1,
                                                                                'post__not_in' => $excluded_product_ids
                                                                            );
                                                                            $the_query = new WP_Query($args);
                                                                            if ($the_query->have_posts()) {
                                                                                while ($the_query->have_posts()) {
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
                                                            $product_block_id++;
                                                            $found_products = array_unique($found_products);
                                                            if (count($found_products)) {
                                                                $show_products = array();
                                                                $args1 = array(
                                                                    'post_type' => 'product',
                                                                    'posts_per_page' => -1,
                                                                    'post__in' => $found_products,
                                                                    'order' => strtoupper($order_),
                                                                    'post_parent' => 0,
                                                                    'meta_query' => array(
                                                                        'relation' => 'AND',
                                                                        array(
                                                                            'key' => '_stock_status',
                                                                            'value' => 'instock',
                                                                            'compare' => 'EQUAL',
                                                                        ),
                                                                    ),
                                                                );
                                                                switch ($order_by) {
                                                                    case 'id':
                                                                        $args1['orderby'] = 'ID';
                                                                        break;
                                                                    case 'rating':
                                                                        $args1['meta_key'] = '_wc_average_rating';
                                                                        $args1['orderby'] = 'meta_value_num';
                                                                        break;
                                                                    case 'popularity':
                                                                        $args1['meta_key'] = 'total_sales';
                                                                        $args1['orderby'] = 'meta_value_num';
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
                                                                $the_query = new WP_Query($args1);
                                                                if ($the_query->have_posts()) {
                                                                    while ($the_query->have_posts()) {
                                                                        $the_query->the_post();
                                                                        $post_id = get_the_ID();
                                                                        $prd = wc_get_product($post_id);
                                                                        if (!in_array($prd->get_type(), array(
                                                                            'simple',
                                                                            'variable',
                                                                            'external',
                                                                            'grouped',
                                                                            'bundle',
                                                                            'composite',
                                                                        ))) {
                                                                            continue;
                                                                        }
                                                                        if ($prd->get_catalog_visibility() != $visibility) {
                                                                            continue;
                                                                        }
                                                                        $show_products[] = $post_id;
                                                                    }
                                                                }
                                                                wp_reset_postdata();
                                                                if (count($show_products)) {
                                                                    ?>
                                                                    <div class="<?php echo $data->set(array( 'products', 'item__container' )) ?> vi-flexslider">
                                                                        <div class="<?php echo $data->set('products-content') ?>"
                                                                             data-wtypc_columns="<?php echo $columns ?>"
                                                                             data-limit="<?php echo $limit ?>"
                                                                             data-slider_loop="<?php echo $slider_loop ?>"
                                                                             data-slider_move="<?php echo $slider_move ?>"
                                                                             data-slider_slideshow="<?php echo $slider_slideshow ?>"
                                                                             data-slider_slideshow_speed="<?php echo $slider_slideshow_speed ?>"
                                                                             data-slider_pause_on_hover="<?php echo $slider_pause_on_hover ?>"
                                                                        >
                                                                            <?php

                                                                            if ($limit > 0) {
                                                                                $num = 0;
                                                                                foreach ($show_products as $show_products_id) {
                                                                                    ?>
                                                                                    <div class="<?php echo $data->set('products-content-item') ?>">
                                                                                        <?php
                                                                                        echo do_shortcode('[products ids="' . $show_products_id . '" limit="' . 1 . '" columns="' . 1 . '" visibility="' . $visibility . '"]');
                                                                                        ?>
                                                                                    </div>
                                                                                    <?php
                                                                                    $num++;
                                                                                    if ($num == $limit) {
                                                                                        break;
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                foreach ($show_products as $show_products_id) {
                                                                                    ?>
                                                                                    <div class="<?php echo $data->set('products-content-item') ?>"
                                                                                         data-product_id="<?php echo $show_products_id ?>">
                                                                                        <?php
                                                                                        echo do_shortcode('[products ids="' . $show_products_id . '" limit="' . 1 . '" columns="' . 1 . '" visibility="' . $visibility . '"]');
                                                                                        ?>
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            }
                                                        }

                                                        break;
                                                    case 'google_map':
                                                        if ($data->get_params('google_map_api')) {
                                                            ?>
                                                            <div class="<?php echo $data->set(array(
                                                                'google_map__container',
                                                                'item__container'
                                                            )) ?>"
                                                                 id="<?php echo $data->set('google_map__container') ?>">
                                                                <div id="<?php echo $data->set(array('google-map')) ?>"></div>
                                                            </div>
                                                            <?php
                                                        }
                                                        break;
                                                    case 'bing_map':
                                                        if ($data->get_params('bing_map_api')) {
                                                            ?>
                                                            <div class="<?php echo $data->set(array(
                                                                'bing_map__container',
                                                                'item__container'
                                                            )) ?>"
                                                                 id="<?php echo $data->set('bing_map__container') ?>">
                                                                <div id="<?php echo $data->set(array('bing-map')) ?>"></div>
                                                            </div>
                                                            <?php
                                                        }
                                                        break;
                                                    case 'thank_you_message':
                                                        ?>
                                                        <div class="<?php echo $data->set(array(
                                                            'thank_you_message__container',
                                                            'item__container'
                                                        )) ?>"
                                                             id="<?php echo $data->set('thank_you_message__container') ?>">
                                                            <div class="<?php echo $data->set('check') ?> wtyp_icons-accept">
                                                                <div class="<?php echo $data->set(array(
                                                                    'thank_you_message__header',
                                                                    'thank_you_message__detail',
                                                                )) ?>">
                                                                    <div class="<?php echo $data->set('thank_you_message-header') ?>">
                                                                        <div><?php echo wp_kses_post(nl2br($thank_you_message_header)); ?></div>
                                                                    </div>
                                                                </div>
                                                                <div class="<?php echo $data->set(array(
                                                                    'thank_you_message__message',
                                                                    'thank_you_message__detail',
                                                                )) ?>">
                                                                    <div class="<?php echo $data->set('thank_you_message-message') ?>">
                                                                        <div><?php echo wp_kses_post(nl2br($thank_you_message_message)); ?></div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <?php
                                                        break;
                                                    case 'coupon':
                                                        $coupon_element = true;

                                                        $coupon_message = $data->get_params('coupon_message',$language);
                                                        $give_coupon = false;
                                                        $send_email = false;
                                                        $coupon_code = '';
                                                        if (metadata_exists('post', $order_id, 'woo_thank_you_page_coupon_code')) {
                                                            $coupon_code = get_post_meta($order_id, 'woo_thank_you_page_coupon_code', true);
                                                        } else {
                                                            $today = getdate();
                                                            $coupon_limit_per_day = intval($data->get_params('coupon_limit_per_day'));
                                                            $coupon_limit_per_week = intval($data->get_params('coupon_limit_per_week'));
                                                            $coupon_limit_per_month = intval($data->get_params('coupon_limit_per_month'));
                                                            $coupon_limit_per_year = intval($data->get_params('coupon_limit_per_year'));
                                                            if ($coupon_limit_per_day > 0) {
                                                                $date_query_args = array(
                                                                    'post_type' => 'shop_order',
                                                                    'post_status' => 'all',
                                                                    'posts_per_page' => -1,
                                                                    'meta_key' => 'woo_thank_you_page_coupon_code',
                                                                    'meta_query' => array(
                                                                        'relation' => 'AND',
                                                                        array(
                                                                            'key' => 'woo_thank_you_page_coupon_code',
                                                                            'value' => '',
                                                                            'compare' => '!='
                                                                        ),
                                                                        array(
                                                                            'key' => '_billing_email',
                                                                            'value' => $shortcodes['billing_email']
                                                                        ),
                                                                    ),
                                                                    'date_query' => array(
                                                                        array(
                                                                            'year' => $today['year'],
                                                                            'month' => $today['mon'],
                                                                            'day' => $today['mday'],
                                                                        ),
                                                                    ),
                                                                );
                                                                $date_query = new WP_Query($date_query_args);
                                                                wp_reset_postdata();
                                                                if (intval($date_query->post_count) >= $coupon_limit_per_day) {
                                                                    update_post_meta($order_id, 'woo_thank_you_page_coupon_code', '');
                                                                    break;
                                                                }
                                                            }
                                                            if ($coupon_limit_per_week > 0) {
                                                                $date_query_args = array(
                                                                    'post_type' => 'shop_order',
                                                                    'post_status' => 'all',
                                                                    'posts_per_page' => -1,
                                                                    'meta_key' => 'woo_thank_you_page_coupon_code',
                                                                    'meta_query' => array(
                                                                        'relation' => 'AND',
                                                                        array(
                                                                            'key' => 'woo_thank_you_page_coupon_code',
                                                                            'value' => '',
                                                                            'compare' => '!='
                                                                        ),
                                                                        array(
                                                                            'key' => '_billing_email',
                                                                            'value' => $shortcodes['billing_email']
                                                                        ),
                                                                    ),
                                                                    'date_query' => array(
                                                                        array(
                                                                            'year' => $today['year'],
                                                                            'week' => date('W'),
                                                                        ),
                                                                    )
                                                                );
                                                                $date_query = new WP_Query($date_query_args);
                                                                wp_reset_postdata();
                                                                if (intval($date_query->post_count) >= $coupon_limit_per_week) {
                                                                    update_post_meta($order_id, 'woo_thank_you_page_coupon_code', '');
                                                                    break;
                                                                }
                                                            }
                                                            if ($coupon_limit_per_month > 0) {
                                                                $date_query_args = array(
                                                                    'post_type' => 'shop_order',
                                                                    'post_status' => 'all',
                                                                    'posts_per_page' => -1,
                                                                    'meta_key' => 'woo_thank_you_page_coupon_code',
                                                                    'meta_query' => array(
                                                                        'relation' => 'AND',
                                                                        array(
                                                                            'key' => 'woo_thank_you_page_coupon_code',
                                                                            'value' => '',
                                                                            'compare' => '!='
                                                                        ),
                                                                        array(
                                                                            'key' => '_billing_email',
                                                                            'value' => $shortcodes['billing_email']
                                                                        ),
                                                                    ),
                                                                    'date_query' => array(
                                                                        array(
                                                                            'year' => $today['year'],
                                                                            'month' => $today['mon'],
                                                                        ),
                                                                    )
                                                                );
                                                                $date_query = new WP_Query($date_query_args);
                                                                wp_reset_postdata();
                                                                if (intval($date_query->post_count) >= $coupon_limit_per_month) {
                                                                    update_post_meta($order_id, 'woo_thank_you_page_coupon_code', '');
                                                                    break;
                                                                }
                                                            }
                                                            if ($coupon_limit_per_year > 0) {
                                                                $date_query_args = array(
                                                                    'post_type' => 'shop_order',
                                                                    'post_status' => 'all',
                                                                    'posts_per_page' => -1,
                                                                    'meta_key' => 'woo_thank_you_page_coupon_code',
                                                                    'meta_query' => array(
                                                                        'relation' => 'AND',
                                                                        array(
                                                                            'key' => 'woo_thank_you_page_coupon_code',
                                                                            'value' => '',
                                                                            'compare' => '!='
                                                                        ),
                                                                        array(
                                                                            'key' => '_billing_email',
                                                                            'value' => $shortcodes['billing_email']
                                                                        ),
                                                                    ),
                                                                    'date_query' => array(
                                                                        array(
                                                                            'year' => $today['year'],
                                                                        ),
                                                                    )
                                                                );
                                                                $date_query = new WP_Query($date_query_args);
                                                                wp_reset_postdata();
                                                                if (intval($date_query->post_count) >= $coupon_limit_per_year) {
                                                                    update_post_meta($order_id, 'woo_thank_you_page_coupon_code', '');
                                                                    break;
                                                                }
                                                            }
                                                            $email = $shortcodes['billing_email'];
                                                            $order_items_categories = array();
                                                            $order_items_products = array();
                                                            $order_items = $order->get_items();
                                                            if (is_array($order_items) && count($order_items)) {
                                                                foreach ($order_items as $order_item) {
                                                                    $product_id = $order_item->get_product_id();
                                                                    if (!in_array($product_id, $order_items_products)) {
                                                                        $order_items_products[] = $product_id;
                                                                        $product = wc_get_product($product_id);
                                                                        if ($product) {
                                                                            $order_items_categories += $product->get_category_ids();
                                                                        }
                                                                    }
                                                                    $variation_id = $order_item->get_variation_id();
                                                                    if ($variation_id) {
                                                                        $order_items_products[] = $variation_id;
                                                                    }
                                                                }
                                                            }
                                                            $order_items_products = array_unique($order_items_products);
                                                            $order_items_categories = array_unique($order_items_categories);
                                                            $order_total = (float)$order->get_total();
                                                            $coupon_type = $data->get_params('coupon_type');
                                                            $coupon_rule_max_total = $data->get_params('coupon_rule_max_total');
                                                            $coupon_rule_min_total = $data->get_params('coupon_rule_min_total');
                                                            $coupon_rule_product_ids = $data->get_params('coupon_rule_product_ids');
                                                            $coupon_rule_excluded_product_ids = $data->get_params('coupon_rule_excluded_product_ids');
                                                            $coupon_rule_product_categories = $data->get_params('coupon_rule_product_categories');
                                                            $coupon_rule_excluded_product_categories = $data->get_params('coupon_rule_excluded_product_categories');
                                                            if (is_array($coupon_type) && count($coupon_type)) {
                                                                foreach ($coupon_type as $key => $value) {
                                                                    if ($order_total < (float)$coupon_rule_min_total[$key]) {
                                                                        continue;
                                                                    }
                                                                    if ($coupon_rule_max_total[$key] > 0 && $order_total > (float)$coupon_rule_max_total[$key]) {
                                                                        continue;
                                                                    }
                                                                    if (is_array($coupon_rule_product_ids[$key]) && count($coupon_rule_product_ids[$key]) && !count(array_intersect($coupon_rule_product_ids[$key], $order_items_products))) {
                                                                        continue;
                                                                    }
                                                                    if (is_array($coupon_rule_excluded_product_ids[$key]) && count($coupon_rule_excluded_product_ids[$key]) && count(array_intersect($coupon_rule_excluded_product_ids[$key], $order_items_products))) {
                                                                        continue;
                                                                    }

                                                                    if (is_array($coupon_rule_product_categories[$key]) && count($coupon_rule_product_categories[$key]) && !count(array_intersect($coupon_rule_product_categories[$key], $order_items_categories))) {
                                                                        continue;
                                                                    }
                                                                    if (is_array($coupon_rule_excluded_product_categories[$key]) && count($coupon_rule_excluded_product_categories[$key]) && count(array_intersect($coupon_rule_excluded_product_categories[$key], $order_items_categories))) {
                                                                        continue;
                                                                    }

                                                                    switch ($data->get_params('coupon_type')[$key]) {
                                                                        case 'existing':
                                                                            $coupon_code = $data->get_params('existing_coupon')[$key];
                                                                            $coupon = new WC_Coupon($coupon_code);
                                                                            if ($data->get_params('coupon_unique_email_restrictions')[$key]) {
                                                                                $er = $coupon->get_email_restrictions();
                                                                                if (!in_array($email, $er)) {
                                                                                    $er[] = $email;
                                                                                    $coupon->set_email_restrictions($er);
                                                                                    $coupon->save();
                                                                                }
                                                                            }
                                                                            $coupon_code = $coupon->get_code();
                                                                            break;
                                                                        case 'unique':
                                                                            $coupon_code = $data->get_params('coupon_unique_prefix')[$key];
                                                                            $characters_array = array_merge(range(0, 9), range('a', 'z'));
                                                                            do {
                                                                                for ($i = 0; $i < 6; $i++) {
                                                                                    $rand = rand(0, count($characters_array) - 1);
                                                                                    $coupon_code .= $characters_array[$rand];
                                                                                }

                                                                                $args = array(
                                                                                    'post_type' => 'shop_coupon',
                                                                                    'post_status' => 'publish',
                                                                                    'posts_per_page' => -1,
                                                                                    'title' => $coupon_code
                                                                                );
                                                                                $the_query = new WP_Query($args);
                                                                                wp_reset_postdata();
                                                                            } while ($the_query->have_posts());

                                                                            $coupon = new WC_Coupon($coupon_code);
                                                                            $today = strtotime(date('Ymd'));
                                                                            $date_expires = ($data->get_params('coupon_unique_date_expires')[$key]) ? (($data->get_params('coupon_unique_date_expires')[$key] + 1) * 86400 + $today) : '';
                                                                            $coupon->set_amount($data->get_params('coupon_unique_amount')[$key]);
                                                                            $coupon->set_date_expires($date_expires);
                                                                            $coupon->set_discount_type($data->get_params('coupon_unique_discount_type')[$key]);
                                                                            $coupon->set_individual_use($data->get_params('coupon_unique_individual_use')[$key]);
                                                                            if ($data->get_params('coupon_unique_product_ids')[$key]) {
                                                                                $coupon->set_product_ids($data->get_params('coupon_unique_product_ids')[$key]);
                                                                            }
                                                                            if ($data->get_params('coupon_unique_excluded_product_ids')[$key]) {
                                                                                $coupon->set_excluded_product_ids($data->get_params('coupon_unique_excluded_product_ids')[$key]);
                                                                            }
                                                                            $coupon->set_usage_limit($data->get_params('coupon_unique_usage_limit')[$key]);
                                                                            $coupon->set_usage_limit_per_user($data->get_params('coupon_unique_usage_limit_per_user')[$key]);
                                                                            $coupon->set_limit_usage_to_x_items($data->get_params('coupon_unique_limit_usage_to_x_items')[$key]);
                                                                            $coupon->set_free_shipping($data->get_params('coupon_unique_free_shipping')[$key]);
                                                                            $coupon->set_product_categories($data->get_params('coupon_unique_product_categories')[$key]);
                                                                            $coupon->set_excluded_product_categories($data->get_params('coupon_unique_excluded_product_categories')[$key]);
                                                                            $coupon->set_exclude_sale_items($data->get_params('coupon_unique_exclude_sale_items')[$key]);
                                                                            $coupon->set_minimum_amount($data->get_params('coupon_unique_minimum_amount')[$key]);
                                                                            $coupon->set_maximum_amount($data->get_params('coupon_unique_maximum_amount')[$key]);
                                                                            if ($data->get_params('coupon_unique_email_restrictions')[$key]) {
                                                                                $coupon->set_email_restrictions(array($email));
                                                                            }
                                                                            $coupon->save();
                                                                            update_post_meta($coupon->get_id(), 'wtypc_unique_coupon', $order_id);
                                                                            $coupon_code = $coupon->get_code();
                                                                        default:
                                                                    }
                                                                    break;
                                                                }
                                                            }

                                                            update_post_meta($order_id, 'woo_thank_you_page_coupon_code', $coupon_code);
                                                            if ($data->get_params('coupon_email_send')) {
                                                                $send_email = true;
                                                            }
                                                        }
                                                        if ($coupon_code) {
                                                            $coupon = new WC_Coupon($coupon_code);
                                                            $coupon_code = strtoupper($coupon_code);
                                                            if ($coupon) {
                                                                $give_coupon = true;
                                                                if ($coupon->get_discount_type() == 'percent') {
                                                                    $coupon_amount = $coupon->get_amount() . '%';
                                                                } else {
                                                                    $coupon_amount = wc_price($coupon->get_amount());
                                                                }
                                                                $date_expires = $coupon->get_date_expires();
                                                                $coupon_date_expires = empty($date_expires) ? esc_html__('never expires', 'woocommerce-thank-you-page-customizer') : date_i18n('F d, Y', strtotime($date_expires));
                                                                $last_valid_date = empty($date_expires) ? '' : date_i18n('F d, Y', strtotime($date_expires) - 86400);
                                                                $coupon_message = str_replace('{coupon_code}', $coupon_code, $coupon_message);
                                                                $coupon_message = str_replace('{coupon_amount}', $coupon_amount, $coupon_message);
                                                                $coupon_message = str_replace('{last_valid_date}', $last_valid_date, $coupon_message);
                                                                $coupon_message = str_replace('{coupon_date_expires}', $coupon_date_expires, $coupon_message);
                                                                if ($send_email) {
                                                                    $wtypc::send_email($shortcodes['billing_email'], $coupon_code, $coupon_date_expires, $last_valid_date, $coupon_amount, $shortcodes,$language);
                                                                }
                                                            }
                                                        }

                                                        if ($give_coupon) {
                                                            ?>
                                                            <div class="<?php echo $data->set(array(
                                                                'coupon__container',
                                                                'item__container'
                                                            )) ?>"
                                                                 id="<?php echo $data->set('coupon__container') ?>">
                                                                <div class="<?php echo $data->set(array(
                                                                    'coupon__message',
                                                                    'coupon__detail',
                                                                )) ?>">
                                                                    <div class="<?php echo $data->set('coupon-message') ?>">
                                                                        <div><?php echo wp_kses_post(nl2br($coupon_message)); ?></div>
                                                                    </div>
                                                                </div>
                                                                <div class="<?php echo $data->set(array(
                                                                    'coupon__code',
                                                                    'coupon__detail',
                                                                )) ?>">
                                                                    <div class="<?php echo $data->set('coupon-code') ?>">
                                                                    <span class="<?php echo $data->set('coupon__code-wrap'); ?> wtyp_icons-scissors">
                                                                        <input type="text" readonly
                                                                               class="<?php echo $data->set('coupon__code-code'); ?>"
                                                                               value="<?php echo $coupon_code; ?>">
	                                                                    <?php
                                                                        if ($data->get_params('coupon_email_enable')) {
                                                                            ?>
                                                                            <span class="<?php echo $data->set('coupon__code-email'); ?>">
                                                                                <span class="<?php echo $data->set('coupon__code-mail-me'); ?> wtyp_icons-opened-email-envelope"
                                                                                      title="<?php echo __('Email me', 'woocommerce-thank-you-page-customizer') ?>"></span>
                                                                                <span class="<?php echo $data->set('coupon__code-copy-code'); ?> wtyp_icons-copy"
                                                                                      title="<?php echo __('Copy code', 'woocommerce-thank-you-page-customizer') ?>"></span>
                                                                            </span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                        break;
                                                    case 'payment_method':
                                                        $payment_element = true;
                                                        do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order_id);
                                                        break;
                                                    case 'order_again':
                                                        ob_start();
                                                        if (function_exists('woocommerce_order_again_button')) {
                                                            woocommerce_order_again_button($order);
                                                        }

                                                        echo ob_get_clean();
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
                remove_action('woocommerce_thankyou', 'woocommerce_order_details_table');
                if (!$payment_element) {
                    ?>
                    <div style="display: none;">
                        <?php
                        do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order_id);
                        ?>
                    </div>
                    <?php
                }
                do_action('woocommerce_thankyou', $order->get_id());
                ?>
            </div>
            <?php
        }
        if (!$coupon_element) {
            update_post_meta($order_id, 'woo_thank_you_page_coupon_code', '');
        }
    }
} else {
    ob_start();
    ?>
    <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters('woocommerce_thankyou_order_received_text', __('Thank you. Your order has been received.', 'woocommerce-thank-you-page-customizer'), null); ?></p>
    <?php
    $content = ob_get_clean();
}