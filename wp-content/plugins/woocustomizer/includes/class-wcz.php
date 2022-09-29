<?php

/**
 * Main plugin class file.
 *
 * @package WooCustomizer/Includes
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Main plugin class.
 */
class WooCustomizer
{
    /**
     * The single instance of WooCustomizer.
     *
     * @var     object
     * @access  private
     * @since   1.0.0
     */
    private static  $_instance = null ;
    //phpcs:ignore
    /**
     * Local instance of WooCustomizer_Admin_API
     *
     * @var WooCustomizer_Admin_API|null
     */
    public  $admin = null ;
    /**
     * Settings class object
     *
     * @var     object
     * @access  public
     * @since   1.0.0
     */
    public  $settings = null ;
    /**
     * The version number.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public  $_version ;
    //phpcs:ignore
    /**
     * The token.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public  $_token ;
    //phpcs:ignore
    /**
     * The main plugin file.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public  $file ;
    /**
     * The main plugin directory.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public  $dir ;
    /**
     * The plugin assets directory.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public  $assets_dir ;
    /**
     * The plugin assets URL.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public  $assets_url ;
    /**
     * Suffix for JavaScripts.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public  $script_suffix ;
    /**
     * Constructor funtion.
     *
     * @param string $file File constructor.
     * @param string $version Plugin version.
     */
    public function __construct( $file = '', $version = WCD_PLUGIN_VERSION )
    {
        $this->_version = $version;
        $this->_token = 'wcz';
        // Load plugin environment variables.
        $this->file = $file;
        $this->dir = dirname( $this->file );
        $this->assets_dir = trailingslashit( $this->dir ) . 'assets';
        $this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );
        $this->includes_url = esc_url( trailingslashit( plugins_url( '/includes/', $this->file ) ) );
        // $this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        register_activation_hook( $this->file, array( $this, 'install' ) );
        add_action( 'admin_init', array( $this, 'wcz_feedback_notice_ignore' ), 0 );
        add_action( 'admin_notices', array( $this, 'wcz_feedback_notice' ) );
        // Load frontend JS & CSS.
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
        // Load admin JS & CSS.
        add_action(
            'admin_enqueue_scripts',
            array( $this, 'admin_enqueue_scripts' ),
            10,
            1
        );
        add_action(
            'admin_enqueue_scripts',
            array( $this, 'admin_enqueue_styles' ),
            10,
            1
        );
        // Load Customizer JS & CSS.
        
        if ( WooCustomizer::wcz_is_plugin_active( 'woocommerce.php' ) ) {
            add_action( 'customize_controls_enqueue_scripts', array( $this, 'customizer_enqueue_styles' ), 10 );
            add_action( 'customize_controls_enqueue_scripts', array( $this, 'customizer_enqueue_scripts' ), 10 );
        }
        
        // Load API for generic admin functions.
        if ( is_admin() ) {
            $this->admin = new WooCustomizer_Admin_API();
        }
        // Handle localisation.
        $this->load_plugin_textdomain();
        add_action( 'init', array( $this, 'load_localisation' ), 0 );
    }
    
    // End __construct ()
    /**
     * Register post type function.
     *
     * @param string $post_type Post Type.
     * @param string $plural Plural Label.
     * @param string $single Single Label.
     * @param string $description Description.
     * @param array  $options Options array.
     *
     * @return bool|string|WooCustomizer_Post_Type
     */
    public function register_post_type(
        $post_type = '',
        $plural = '',
        $single = '',
        $description = '',
        $options = array()
    )
    {
        if ( !$post_type || !$plural || !$single ) {
            return false;
        }
        $post_type = new WooCustomizer_Post_Type(
            $post_type,
            $plural,
            $single,
            $description,
            $options
        );
        return $post_type;
    }
    
    /**
     * Wrapper function to register a new taxonomy.
     *
     * @param string $taxonomy Taxonomy.
     * @param string $plural Plural Label.
     * @param string $single Single Label.
     * @param array  $post_types Post types to register this taxonomy for.
     * @param array  $taxonomy_args Taxonomy arguments.
     *
     * @return bool|string|WooCustomizer_Taxonomy
     */
    public function register_taxonomy(
        $taxonomy = '',
        $plural = '',
        $single = '',
        $post_types = array(),
        $taxonomy_args = array()
    )
    {
        if ( !$taxonomy || !$plural || !$single ) {
            return false;
        }
        $taxonomy = new WooCustomizer_Taxonomy(
            $taxonomy,
            $plural,
            $single,
            $post_types,
            $taxonomy_args
        );
        return $taxonomy;
    }
    
    /**
     * Load frontend CSS.
     *
     * @access  public
     * @return void
     * @since   1.0.0
     */
    public function enqueue_styles()
    {
        wp_register_style(
            $this->_token . '-frontend',
            esc_url( $this->assets_url ) . 'css/frontend.css',
            array(),
            $this->_version
        );
        wp_enqueue_style( $this->_token . '-frontend' );
    }
    
    // End enqueue_styles ()
    /**
     * Load frontend Javascript.
     *
     * @access  public
     * @return  void
     * @since   1.0.0
     */
    public function enqueue_scripts()
    {
        
        if ( get_option( 'wcz-cart-ajax-update', woocustomizer_library_get_default( 'wcz-cart-ajax-update' ) ) ) {
            wp_register_script(
                $this->_token . '-cart-update',
                esc_url( $this->assets_url ) . 'js/wcz-cart-update.js',
                array( 'jquery' ),
                $this->_version
            );
            wp_enqueue_script( $this->_token . '-cart-update' );
        }
        
        
        if ( get_option( 'wcz-admin-product-stats', woocustomizer_library_get_default( 'wcz-admin-product-stats' ) ) ) {
            wp_register_script(
                $this->_token . '-frontend',
                esc_url( $this->assets_url ) . 'js/frontend' . $this->script_suffix . '.js',
                array( 'jquery' ),
                $this->_version,
                true
            );
            wp_localize_script( $this->_token . '-frontend', 'wcz_admin_stats', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
            ) );
            wp_enqueue_script( $this->_token . '-frontend' );
        }
    
    }
    
    // End enqueue_scripts ()
    /**
     * Admin enqueue style.
     *
     * @param string $hook Hook parameter.
     *
     * @return void
     */
    public function admin_enqueue_styles( $hook = '' )
    {
        wp_register_style(
            $this->_token . '-admin',
            esc_url( $this->assets_url ) . 'css/admin.css',
            array(),
            $this->_version
        );
        wp_enqueue_style( $this->_token . '-admin' );
    }
    
    // End admin_enqueue_styles ()
    /**
     * Load admin Javascript.
     *
     * @access  public
     *
     * @param string $hook Hook parameter.
     *
     * @return  void
     * @since   1.0.0
     */
    public function admin_enqueue_scripts( $hook = '' )
    {
        wp_register_script(
            $this->_token . '-admin',
            esc_url( $this->assets_url ) . 'js/admin' . $this->script_suffix . '.js',
            array( 'jquery' ),
            $this->_version,
            true
        );
        wp_enqueue_script( $this->_token . '-admin' );
    }
    
    // End admin_enqueue_scripts ()
    /**
     * Customizer enqueue style.
     *
     * @param string $hook Hook parameter.
     *
     * @return void
     */
    public function customizer_enqueue_styles( $hook = '' )
    {
        wp_register_style(
            $this->_token . '-customizer',
            esc_url( $this->includes_url ) . 'customizer/customizer-library/css/customizer.css',
            array(),
            $this->_version
        );
        wp_enqueue_style( $this->_token . '-customizer' );
        wp_register_style(
            $this->_token . '-customizer-tour',
            esc_url( $this->includes_url ) . 'customizer/customizer-library/css/customizer-tour.css',
            array(),
            $this->_version
        );
        wp_enqueue_style( $this->_token . '-customizer-tour' );
    }
    
    // End customizer_enqueue_styles ()
    /**
     * Load Customizer Javascript scripts.
     *
     * @access  public
     *
     * @param string $hook Hook parameter.
     *
     * @return  void
     * @since   1.0.0
     */
    public function customizer_enqueue_scripts( $hook = '' )
    {
        $shop_url = get_permalink( wc_get_page_id( 'shop' ) );
        $cart_url = get_permalink( wc_get_page_id( 'cart' ) );
        $checkout_url = get_permalink( wc_get_page_id( 'checkout' ) );
        $account_url = get_permalink( wc_get_page_id( 'myaccount' ) );
        // Get product ID for any product url
        $query = array(
            'limit'   => 1,
            'orderby' => 'rand',
            'return'  => 'ids',
        );
        $productid = wc_get_products( $query );
        $product_url = get_permalink( $productid[0] );
        // Customizer Controls JS
        wp_register_script(
            $this->_token . '-customizer',
            esc_url( $this->includes_url ) . 'customizer/customizer-library/js/customizer-custom.js',
            array( 'jquery', 'customize-controls' ),
            $this->_version,
            true
        );
        wp_localize_script( $this->_token . '-customizer', 'page_urls', array(
            'shop'     => esc_url( $shop_url ),
            'checkout' => esc_url( $checkout_url ),
            'cart'     => esc_url( $cart_url ),
            'account'  => esc_url( $account_url ),
            'product'  => esc_url( $product_url ),
        ) );
        wp_enqueue_script( $this->_token . '-customizer' );
        $wcz_texts = array(
            'show'         => __( 'Show The Tour', 'linkt' ),
            'hide_tour'    => __( 'Turn Tour Off', 'linkt' ),
            'prev'         => __( 'Previous Tip', 'linkt' ),
            'next'         => __( 'Next Tip', 'linkt' ),
            'premium_set'  => __( 'Turn on the Premium settings', 'linkt' ),
            'contact'      => __( 'Contact Us', 'linkt' ),
            'premium_link' => admin_url( 'admin.php?page=wcz_settings' ),
        );
        // Turn off WC Tour button
        if ( 'on' == get_option( 'wcz_set_general_tour', woocustomizer_library_get_default( 'wcz_set_general_tour' ) ) ) {
            return;
        }
        // Customizer Tour JS
        wp_register_script(
            $this->_token . '-customizer-tour',
            esc_url( $this->includes_url ) . 'customizer/customizer-library/js/customizer-tour.js',
            array( 'jquery', 'customize-controls' ),
            $this->_version,
            true
        );
        wp_localize_script( $this->_token . '-customizer-tour', 'wcz_tour', array(
            'steps' => $this->wcz_tour_steps(),
            'texts' => $wcz_texts,
        ) );
        wp_enqueue_script( $this->_token . '-customizer-tour' );
    }
    
    // End customizer_enqueue_scripts ()
    /**
     * Guided tour steps.
     *
     * @since 2.2.0
     */
    public function wcz_tour_steps()
    {
        $steps = array();
        $steps[] = array(
            'title'   => __( 'Welcome to WooCustomizer!', 'woocustomizer' ),
            'message' => __( 'We hope you enjoy using the plugin to further customize your WooCommerce pages. - PLEASE NOTE... Some settings might not display perfectly, depending on which theme you\'re using. - You can always contact us for help on this.', 'woocustomizer' ),
            'top'     => esc_attr( '98' ),
        );
        $steps[] = array(
            'title'   => __( 'Customize the WooCommerce Pages', 'woocustomizer' ),
            'message' => __( 'In this group of Customizer panels you can customize the each of the WooCommerce pages by editing the settings within each section.', 'woocustomizer' ),
            'top'     => esc_attr( '150' ),
        );
        $steps[] = array(
            'title'   => __( 'Plus Account, Cart & Checkout Pages', 'woocustomizer' ),
            'message' => __( 'After editing the Shop & Product pages, you may want to edit your user account, cart or checkout pages too.', 'woocustomizer' ),
            'top'     => esc_attr( '236' ),
        );
        // Only displayed in the Premium version
        // Only displayed in the free version
        
        if ( !wcz_fs()->can_use_premium_code() ) {
            $steps[] = array(
                'title'   => __( 'Premium Settings get added here', 'woocustomizer' ),
                'message' => __( 'Once you\'ve turned on the pro settings that you want, you\'ll then see the new panels appear here for Catalogue Mode, Product Quick View, Menu Cart and / or Ajax Search', 'woocustomizer' ),
                'top'     => esc_attr( '352' ),
            );
            $steps[] = array(
                'title'   => __( 'Turn on the Pro Settings', 'woocustomizer' ),
                'message' => __( 'Follow this link to turn on the pro settings. - Or Navigate to WooCommerce -> WooCustomizer in your Dashboard.', 'woocustomizer' ),
                'top'     => esc_attr( '374' ),
            );
            $steps[] = array(
                'title'   => __( 'Need Help?', 'woocustomizer' ),
                'message' => __( 'Get in Contact here - Premium users get prioritized support.', 'woocustomizer' ),
                'top'     => esc_attr( '451' ),
            );
        }
        
        // Only displayed in the free version
        $steps[] = array(
            'title'   => __( 'Don\'t forget to save!', 'woocustomizer' ),
            'message' => __( 'Once you\'ve  finished configuring your WooCustomizer settings, make sure you Publish the new settings.', 'woocustomizer' ),
            'top'     => esc_attr( '-15' ),
        );
        return $steps;
    }
    
    /**
     * Admin notice to ask for feedback
     */
    function wcz_feedback_notice()
    {
        global  $current_user ;
        $wcz_user_id = $current_user->ID;
        
        if ( !get_user_meta( $wcz_user_id, 'wcz_feedback_notice_dismiss' ) ) {
            ?>
			<div class="notice notice-info wcz-admin-notice">
				<h4><?php 
            esc_html_e( 'Thank you for trying out WooCustomizer !', 'woocustomizer' );
            ?></h4>

				<div class="wcz-notice-columns">
					<div class="wcz-notice-col">
						<h5><?php 
            esc_html_e( 'Sshhhh! We\'re in soft launch phase.', 'woocustomizer' );
            ?></h5>
						<div class="wcz-notice-cont">
							<p><?php 
            esc_html_e( 'This means you\'re the first to find and use the WooCustomizer plugin... We\'re adding lots of options and wanted features as we go.', 'woocustomizer' );
            ?></p>
						</div>
						<a href="<?php 
            echo  esc_url( 'https://woocustomizer.com/go/home/' ) ;
            ?>" target="_blank" class="wcz-notice-btn">
							<?php 
            esc_html_e( 'WooCustomizer & Features', 'woocustomizer' );
            ?>
						</a><br /><br />
						<a href="<?php 
            echo  esc_url( 'https://woocustomizer.com/go/woocustomizer-settings/' ) ;
            ?>" target="_blank" class="wcz-notice-btn">
							<?php 
            esc_html_e( 'Try out the WooCustomizer Settings', 'woocustomizer' );
            ?>
						</a>
						<p class="wcz-note"><?php 
            esc_html_e( 'This includes the WooCustomizer Pro settings', 'woocustomizer' );
            ?></p>
					</div>
					<div class="wcz-notice-col">
						<h5><?php 
            esc_html_e( 'Found a bug or want something added?', 'woocustomizer' );
            ?></h5>
						<div class="wcz-notice-cont">
							<p><?php 
            esc_html_e( 'Please take it easy on us if something isn\'t working, I\'ll try to fix it right away for you.', 'woocustomizer' );
            ?></p>
							<p>
								<?php 
            /* translators: 1: 'giving us a review'. */
            printf( esc_html__( '%1$s, am trying to fix any bugs that come up, manage support & marketing, and also add the features you want.', 'woocustomizer' ), wp_kses( '<a href="https://woocustomizer.com/go/about/" class="wcz-admin-notice-a" target="_blank">' . __( 'I, Zack', 'woocustomizer' ) . '</a>', array(
                'a' => array(
                'href'   => array(),
                'class'  => array(),
                'target' => array(),
            ),
            ) ) );
            ?>
							</p>
						</div>

						<a href="<?php 
            echo  esc_url( 'https://woocustomizer.com/go/contact/' ) ;
            ?>" target="_blank" class="wcz-notice-btn">
							<?php 
            esc_html_e( 'Bug / Feature Requests', 'woocustomizer' );
            ?>
						</a>
					</div>
					<div class="wcz-notice-col">
						<h5><?php 
            esc_html_e( 'Like WooCustomizer? Give it 5 stars :)', 'woocustomizer' );
            ?></h5>
						<div class="wcz-notice-cont">
							<p><?php 
            esc_html_e( 'If the plugin works well for you or our support was good... giving it a nice review would definitely help with motivation & giving other users trust in my work.', 'woocustomizer' );
            ?></p>
						</div>
						<a href="<?php 
            echo  esc_url( 'https://woocustomizer.com/go/rating/' ) ;
            ?>" target="_blank" class="wcz-notice-btn">
							<?php 
            esc_html_e( 'Ok, you deserve it', 'woocustomizer' );
            ?>
						</a>
					</div>
				</div>

				
				<a href="?wcz_feedback_notice_ignore=" class="wcz-notice-close"><?php 
            esc_html_e( 'Dismiss Notice', 'woocustomizer' );
            ?></a>
			</div><?php 
        }
    
    }
    
    function wcz_feedback_notice_ignore()
    {
        global  $current_user ;
        $wcz_user_id = $current_user->ID;
        if ( isset( $_GET['wcz_feedback_notice_ignore'] ) ) {
            update_user_meta( $wcz_user_id, 'wcz_feedback_notice_dismiss', true );
        }
    }
    
    /**
     * Load plugin localisation
     *
     * @access  public
     * @return  void
     * @since   1.0.0
     */
    public function load_localisation()
    {
        load_plugin_textdomain( 'woocustomizer', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
    }
    
    // End load_localisation ()
    /**
     * Load plugin textdomain
     *
     * @access  public
     * @return  void
     * @since   1.0.0
     */
    public function load_plugin_textdomain()
    {
        $domain = 'woocustomizer';
        $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
        load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
        load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
    }
    
    // End load_plugin_textdomain ()
    /**
     * Main WooCustomizer Instance
     *
     * Ensures only one instance of WooCustomizer is loaded or can be loaded.
     *
     * @param string $file File instance.
     * @param string $version Version parameter.
     *
     * @return Object WooCustomizer instance
     * @see WooCustomizer()
     * @since 1.0.0
     * @static
     */
    public static function instance( $file = '', $version = '1.0.0' )
    {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self( $file, $version );
        }
        return self::$_instance;
    }
    
    // End instance ()
    /**
     * Function to determine whether a plugin is active.
     *
     * @param string $plugin_name plugin name, as the plugin-filename.php
     * @return boolean true if the named plugin is installed and active
     * @since 1.0.0
     */
    public static function wcz_is_plugin_active( $plugin_name )
    {
        $active_plugins = (array) get_option( 'active_plugins', array() );
        if ( is_multisite() ) {
            $active_plugins = array_merge( $active_plugins, array_keys( get_site_option( 'active_sitewide_plugins', array() ) ) );
        }
        $plugin_filenames = array();
        foreach ( $active_plugins as $plugin ) {
            
            if ( false !== strpos( $plugin, '/' ) ) {
                // normal plugin name (plugin-dir/plugin-filename.php)
                list( , $filename ) = explode( '/', $plugin );
            } else {
                // no directory, just plugin file
                $filename = $plugin;
            }
            
            $plugin_filenames[] = $filename;
        }
        return in_array( $plugin_name, $plugin_filenames );
    }
    
    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone()
    {
        _doing_it_wrong( __FUNCTION__, esc_html( __( 'Cloning of WooCustomizer is forbidden' ) ), esc_attr( $this->_version ) );
    }
    
    // End __clone ()
    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong( __FUNCTION__, esc_html( __( 'Unserializing instances of WooCustomizer is forbidden' ) ), esc_attr( $this->_version ) );
    }
    
    // End __wakeup ()
    /**
     * Installation. Runs on activation.
     *
     * @access  public
     * @return  void
     * @since   1.0.0
     */
    public function install()
    {
        $this->_log_version_number();
    }
    
    // End install ()
    /**
     * Log the plugin version number.
     *
     * @access  public
     * @return  void
     * @since   1.0.0
     */
    private function _log_version_number()
    {
        //phpcs:ignore
        update_option( $this->_token . '_version', $this->_version );
    }

}