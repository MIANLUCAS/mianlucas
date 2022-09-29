<?php
/*
Class Name: VI_WOOCOMMERCE_THANK_YOU_PAGE_Admin_Admin
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2018 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOOCOMMERCE_THANK_YOU_PAGE_Admin_Admin {
	protected $settings;
	protected $active_components;

	public function __construct() {
		$this->settings          = new VI_WOOCOMMERCE_THANK_YOU_PAGE_DATA();
		$this->active_components = array();
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'init', array( $this, 'init' ) );
	}

	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce-thank-you-page-customizer' );
		load_textdomain( 'woocommerce-thank-you-page-customizer', VI_WOOCOMMERCE_THANK_YOU_PAGE_LANGUAGES . "woocommerce-thank-you-page-customizer-$locale.mo" );
		load_plugin_textdomain( 'woocommerce-thank-you-page-customizer', false, VI_WOOCOMMERCE_THANK_YOU_PAGE_LANGUAGES );
	}

	public function init() {
		$this->load_plugin_textdomain();
		if (class_exists('VillaTheme_Support_Pro')){
			new VillaTheme_Support_Pro(
				array(
					'support'   => 'https://villatheme.com/supports/forum/plugins/woocommerce-thank-you-page-customizer/',
					'docs'      => 'http://docs.villatheme.com/?item=woocommerce-thank-you-page-customizer',
					'review'    => 'https://codecanyon.net/downloads',
					'css'       => VI_WOOCOMMERCE_THANK_YOU_PAGE_CSS,
					'image'     => VI_WOOCOMMERCE_THANK_YOU_PAGE_IMAGES,
					'slug'      => 'woocommerce-thank-you-page-customizer',
					'menu_slug' => 'woocommerce_thank_you_page_customizer',
					'version'   => VI_WOOCOMMERCE_THANK_YOU_PAGE_VERSION
				)
			);
        }
	}

	public function admin_notices( $links ) {
		if ( ! get_option( 'woocommerce_checkout_page_id' ) ) {
			?>
            <div id="message" class="error">
                <p><?php _e( 'Checkout page is not set yet, WooCommerce Thank You Page Customizer is not working. Please set it <a target="_blank" href="' . admin_url( 'admin.php' ) . '?page=wc-settings&tab=advanced">here</a>.', 'woocommerce-thank-you-page-customizer' ); ?></p>
            </div>
			<?php
		}
		if ( isset( $_REQUEST['woocommerce_thank_you_page_customizer_items_removed_notice_hide'] ) && $_REQUEST['woocommerce_thank_you_page_customizer_items_removed_notice_hide'] ) {
			set_transient( 'woocommerce_thank_you_page_customizer_items_removed_notice', 'hide' );
		}
		if ( ! get_transient( 'woocommerce_thank_you_page_customizer_items_removed_notice' ) ) {
			$blocks = json_decode( $this->settings->get_params( 'blocks' ), true );
			array_walk_recursive( $blocks, array( $this, 'get_active_components' ) );
			$removed_components = array(
				'sale_products',
				'best_selling_products',
				'recent_products',
				'recently_viewed_products',
				'featured_products',
				'up_sells_products',
				'cross_sells_products',
				'related_products',
				'top_rated_products',
			);
			if ( count( array_intersect( $this->active_components, $removed_components ) ) ) {
				$url = admin_url( 'customize.php' ) . '?autofocus[section]=woo_thank_you_page_design_general';
				if ( $this->settings->get_params( 'select_order' ) ) {
					$order = wc_get_order( $this->settings->get_params( 'select_order' ) );
					if ( $order ) {
						$url = admin_url( 'customize.php' ) . '?url=' . urlencode( $order->get_checkout_order_received_url() ) . '&autofocus[section]=woo_thank_you_page_design_general';
					}
				}
				?>
                <div id="message" class="error">
                    <p><?php _e( 'Thank you for using WooCommerce Thank You Page Customizer. We replaced some products items with the new item that have more options in the latest update; therefore those old products items will not work any more. Please go to <a target="_blank" href="' . $url . '">cutomize</a> to update your Thank you page. We are sorry for this inconvenience.', 'woocommerce-thank-you-page-customizer' ); ?></p>
                    <p>
                        <a href="<?php echo add_query_arg( array( 'woocommerce_thank_you_page_customizer_items_removed_notice_hide' => 1 ) ) ?>"
                           class="button"><?php _e( 'Got it', 'woocommerce-thank-you-page-customizer' ); ?></a></p>
                </div>
				<?php
			}
		}

	}

	public function get_active_components( $value, $key ) {
		if ( ! in_array( $value, $this->active_components ) ) {
			$this->active_components[] = $value;
		}
	}

}