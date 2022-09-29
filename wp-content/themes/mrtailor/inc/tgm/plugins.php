<?php


function getbowtied_theme_register_required_plugins() {


    $plugins = array(
        'woocommerce' => array(
            'name'               => 'WooCommerce',
            'slug'               => 'woocommerce',
            'required'           => false,
            'description'        => 'The eCommerce engine of your WordPress site.',
            'demo_required'      => true
        ),
        'product-blocks-for-woocommerce' => array(
          'name'               => 'Product Blocks for WooCommerce',
          'slug'               => 'product-blocks-for-woocommerce',
          'required'           => true,
          'description'        => 'Create beautiful product displays for your WooCommerce store.',
          'demo_required'      => true
        ),
        'one-click-demo-import'=> array(
            'name'               => 'One Click Demo Import',
            'slug'               => 'one-click-demo-import',
            'required'           => false,
            'description'        => 'Adds easy-to-use demo import functionality.',
            'demo_required'      => true
        ),
        'envato-market'        => array(
            'name'               => 'Envato Market',
            'slug'               => 'envato-market',
            'required'           => false,
            'source'             => 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
            'description'        => 'Enables updates for all your Envato purchases.',
            'demo_required'      => false
        ),
        'mr-tailor-extender'   => array(
            'name'               => 'Mr. Tailor Extender',
            'slug'               => 'mr-tailor-extender',
            'source'             => 'https://github.com/getbowtied/mr-tailor-extender/zipball/master',
            'required'           => true,
            'external_url'       => 'https://github.com/getbowtied/mr-tailor-extender',
            'description'        => 'Extends the functionality of with theme-specific features.',
            'demo_required'      => true,
        ),
        'hookmeup'             => array(
            'name'               => 'HookMeUp – Additional Content for WooCommerce',
            'slug'               => 'hookmeup',
            'required'           => false,
            'description'        => 'Customize WooCommerce templates without coding.',
            'demo_required'      => false
        ),
    );

    $config = array(
        'id'               => 'getbowtied',
        'default_path'      => '',
        'parent_slug'       => 'themes.php',
        'menu'              => 'tgmpa-install-plugins',
        'has_notices'       => true,
        'is_automatic'      => false,
    );

    tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'getbowtied_theme_register_required_plugins' );
