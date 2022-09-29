<?php

/**
 * Defines customizer options
 *
 * @package Customizer Library Overlay
 */
function woocustomizer_customizer_library_options()
{
    $primary_color = '#4e87d8';
    $secondary_color = '#0b53b9';
    // Stores all the controls that will be added
    $options = array();
    // Stores all the sections to be added
    $sections = array();
    // Stores all the panels to be added
    $panels = array();
    // Font Selections
    // $font_websafe_choices = array( 'Arial' => 'Arial', 'Arial Black' => 'Arial Black', 'Helvetica' => 'Helvetica', 'Verdana' => 'Verdana', 'Georgia' => 'Georgia', 'Palatino' => 'Palatino', 'Garamond' => 'Garamond', 'Bookman' => 'Bookman', 'Courier' => 'Courier', 'Courier New' => 'Courier New', 'Times New Roman' => 'Times New Roman', 'Times' => 'Times' );
    // $font_google_choices = woocustomizer_library_get_font_choices();
    // Adds the sections to the $options array
    $options['sections'] = $sections;
    
    if ( WooCustomizer::wcz_is_plugin_active( 'woocommerce.php' ) ) {
        // is WooCommerce Activated
        // ---------------- PANEL - Theme Settings
        $panel = 'wcz-panel-settings';
        $panels[] = array(
            'id'       => $panel,
            'title'    => __( 'WooCustomizer', 'woocustomizer' ),
            'priority' => '10',
        );
        // --------------------------------------------------------------------------------------------------------------------------------- WC Panel
        // ----------------------------------------------------------------------------------------------- WooCustomizer Main Panel
        $section = 'wcz-panel-woocustomizer';
        $sections[] = array(
            'id'       => $section,
            'title'    => __( 'WooCustomizer', 'woocustomizer' ),
            'priority' => '10',
            'panel'    => $panel,
        );
        $options['wcz-wc-remove-breadcrumbs'] = array(
            'id'      => 'wcz-wc-remove-breadcrumbs',
            'label'   => __( 'Remove All WooCommerce Breadcrumbs', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        // Product Design Settings
        $options['wcz-heading-wcc-btn'] = array(
            'id'      => 'wcz-heading-wcc-btn',
            'label'   => __( 'Button Design', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'url',
        );
        $choices = array(
            'wcz-btn-style-default'  => 'Theme Style / Default',
            'wcz-btn-style-plain'    => 'Plain',
            'wcz-btn-style-detailed' => 'Detailed',
        );
        $options['wcz-btn-style'] = array(
            'id'      => 'wcz-btn-style',
            'label'   => __( 'Button Style', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'select',
            'choices' => $choices,
            'default' => 'wcz-btn-style-default',
        );
        // Customize - Button
        $options['wcz-wc-edit-btns'] = array(
            'id'      => 'wcz-wc-edit-btns',
            'label'   => __( 'Customize Button Design', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $wcz_bfsval = get_option( 'wcz-btn-fsize', 16 );
        $options['wcz-btn-fsize'] = array(
            'id'          => 'wcz-btn-fsize',
            'label'       => __( 'Font Size', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'range',
            'input_attrs' => array(
            'min'  => 10,
            'max'  => 34,
            'step' => 1,
        ),
            'description' => '<i>10</i> <span><b class="wcz-rangeval">' . esc_html( $wcz_bfsval ) . '</b></span> <i>34</i>',
            'default'     => 16,
        );
        $options['wcz-btn-fweight'] = array(
            'id'      => 'wcz-btn-fweight',
            'label'   => __( 'Bold', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-btn-bgcolor'] = array(
            'id'      => 'wcz-btn-bgcolor',
            'label'   => __( 'Button Color', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'color',
            'default' => '#e8e8e8',
        );
        $options['wcz-btn-hovercolor'] = array(
            'id'      => 'wcz-btn-hovercolor',
            'label'   => __( 'Button Hover Color', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'color',
            'default' => '#757575',
        );
        $wcz_bbrval = get_option( 'wcz-btn-br', 3 );
        $options['wcz-btn-br'] = array(
            'id'          => 'wcz-btn-br',
            'label'       => __( 'Border Radius', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'range',
            'input_attrs' => array(
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ),
            'description' => '<i>0</i> <span><b class="wcz-rangeval">' . esc_html( $wcz_bbrval ) . '</b></span> <i>50</i>',
            'default'     => 3,
        );
        $wcz_bpval = get_option( 'wcz-btn-padding', 10 );
        $options['wcz-btn-padding'] = array(
            'id'          => 'wcz-btn-padding',
            'label'       => __( 'Padding', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'range',
            'input_attrs' => array(
            'min'  => 5,
            'max'  => 40,
            'step' => 1,
        ),
            'description' => '<i>5</i> <span><b class="wcz-rangeval">' . esc_html( $wcz_bpval ) . '</b></span> <i>40</i>',
            'default'     => 10,
        );
        // Customize - Sale Banner
        $options['wcz-wc-edit-sale'] = array(
            'id'      => 'wcz-wc-edit-sale',
            'label'   => __( 'Customize Sale Banner', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $wcz_sfsval = get_option( 'wcz-sale-fsize', 15 );
        $options['wcz-sale-fsize'] = array(
            'id'          => 'wcz-sale-fsize',
            'label'       => __( 'Font Size', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'range',
            'input_attrs' => array(
            'min'  => 10,
            'max'  => 32,
            'step' => 1,
        ),
            'description' => '<i>10</i> <span><b class="wcz-rangeval">' . esc_html( $wcz_sfsval ) . '</b></span> <i>32</i>',
            'default'     => 15,
        );
        $options['wcz-sale-fweight'] = array(
            'id'      => 'wcz-sale-fweight',
            'label'   => __( 'Bold', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-sale-bgcolor'] = array(
            'id'      => 'wcz-sale-bgcolor',
            'label'   => __( 'Sale Banner Color', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'color',
            'default' => '#e8e8e8',
        );
        $wcz_sbbrval = get_option( 'wcz-sale-br', 3 );
        $options['wcz-sale-br'] = array(
            'id'          => 'wcz-sale-br',
            'label'       => __( 'Border Radius', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'range',
            'input_attrs' => array(
            'min'  => 0,
            'max'  => 20,
            'step' => 1,
        ),
            'description' => '<i>0</i> <span><b class="wcz-rangeval">' . esc_html( $wcz_sbbrval ) . '</b></span> <i>20</i>',
            'default'     => 3,
        );
        $wcz_sbpval = get_option( 'wcz-sale-padding', 5 );
        $options['wcz-sale-padding'] = array(
            'id'          => 'wcz-sale-padding',
            'label'       => __( 'Padding', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'range',
            'input_attrs' => array(
            'min'  => 2,
            'max'  => 20,
            'step' => 1,
        ),
            'description' => '<i>2</i> <span><b class="wcz-rangeval">' . esc_html( $wcz_sbpval ) . '</b></span> <i>20</i>',
            'default'     => 5,
        );
        // Admin Only Settings
        $options['wcz-heading-wcc-admin'] = array(
            'id'      => 'wcz-heading-wcc-admin',
            'label'   => __( 'Admin Only Settings', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'url',
        );
        $options['wcz-admin-product-stats'] = array(
            'id'      => 'wcz-admin-product-stats',
            'label'   => __( 'Turn on Front-End Product Statistics', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        // Login or Logout Menu Item Settings
        $options['wcz-heading-login-logout'] = array(
            'id'      => 'wcz-heading-login-logout',
            'label'   => __( 'Login / Logout Menu Item', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'url',
        );
        // Get Theme Menu Locations for Menu Cart
        $wcz_menus = get_registered_nav_menus();
        $nav_choices = array(
            'none' => 'None',
        );
        foreach ( $wcz_menus as $location => $description ) {
            $nav_choices[$location] = $description;
        }
        $options['wcz-login-logout-menu'] = array(
            'id'          => 'wcz-login-logout-menu',
            'label'       => __( 'Add Login / Logout to Navigation', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'select',
            'choices'     => $nav_choices,
            'description' => __( 'This will add a Login navigation item, and will change to Logout if the user is logged in.', 'woocustomizer' ),
            'default'     => 'none',
        );
        $options['wcz-login-redirect-page'] = array(
            'id'          => 'wcz-login-redirect-page',
            'label'       => __( 'Login Redirect Page', 'overlay' ),
            'section'     => $section,
            'type'        => 'dropdown-pages',
            'description' => __( 'Select which page to redirect the user to after LOGGING IN. Defaults to Home.', 'woocustomizer' ),
            'default'     => '',
        );
        $options['wcz-logout-redirect-page'] = array(
            'id'          => 'wcz-logout-redirect-page',
            'label'       => __( 'Logout Redirect Page', 'overlay' ),
            'section'     => $section,
            'type'        => 'dropdown-pages',
            'description' => __( 'Select which page to redirect the user to adter LOGGING OUT. Defaults to Home.', 'woocustomizer' ),
            'default'     => '',
        );
        $options['wcz-login-text'] = array(
            'id'      => 'wcz-login-text',
            'label'   => __( 'Login Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Login', 'woocustomizer' ),
        );
        $options['wcz-logout-text'] = array(
            'id'      => 'wcz-logout-text',
            'label'   => __( 'Logout Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Logout', 'woocustomizer' ),
        );
        // ----------------------------------------------------------------------------------------------- WooCustomizer Main Panel
        // ----------------------------------------------------------------------------------------------- WooCommerce Shop Page
        $section = 'wcz-panel-shop';
        $sections[] = array(
            'id'       => $section,
            'title'    => __( 'WooCommerce Shop Page', 'woocustomizer' ),
            'priority' => '10',
            'panel'    => $panel,
        );
        $options['wcz-shop-remove-breadcrumbs'] = array(
            'id'      => 'wcz-shop-remove-breadcrumbs',
            'label'   => __( 'Remove Shop Page Breadcrumbs', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-shop-remove-title'] = array(
            'id'      => 'wcz-shop-remove-title',
            'label'   => __( 'Remove Shop Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-shop-remove-sorting'] = array(
            'id'      => 'wcz-shop-remove-sorting',
            'label'   => __( 'Remove Shop Sorting Dropdown', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-shop-remove-result'] = array(
            'id'      => 'wcz-shop-remove-result',
            'label'   => __( 'Remove Shop Sorting Results', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $wcz_rangeval = get_option( 'wcz-shop-pppage', 12 );
        $options['wcz-shop-pppage'] = array(
            'id'          => 'wcz-shop-pppage',
            'label'       => __( 'Products Per Page', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'range',
            'input_attrs' => array(
            'min'  => 2,
            'max'  => 48,
            'step' => 1,
        ),
            'description' => '<i>2</i> <span><b class="wcz-rangeval">' . esc_html( $wcz_rangeval ) . '</b></span> <i>48</i>',
            'default'     => 12,
        );
        $options['wcz-shop-pprow'] = array(
            'id'          => 'wcz-shop-pprow',
            'label'       => __( 'Products Per Row', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'range',
            'input_attrs' => array(
            'min'  => 1,
            'max'  => 5,
            'step' => 1,
        ),
            'description' => __( '<i>1</i> <b>|</b> 2 <b>|</b> 3 <b>|</b> 4 <b>|</b> <i>5</i>', 'woocustomizer' ),
            'default'     => 4,
        );
        $options['wcz-shop-sale-txt'] = array(
            'id'      => 'wcz-shop-sale-txt',
            'label'   => __( 'Sale Banner Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Sale!', 'woocustomizer' ),
        );
        $options['wcz-shop-add-soldout'] = array(
            'id'      => 'wcz-shop-add-soldout',
            'label'   => __( 'Add a banner to Sold Out products', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $choices = array(
            'wcz-soldout-style-plain' => 'Plain Text',
            'wcz-soldout-style-angle' => 'Angle Over Image',
        );
        $options['wcz-soldout-style'] = array(
            'id'      => 'wcz-soldout-style',
            'label'   => __( 'Sold Out Banner Style', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'select',
            'choices' => $choices,
            'default' => 'wcz-soldout-style-plain',
        );
        $options['wcz-shop-soldout-txt'] = array(
            'id'      => 'wcz-shop-soldout-txt',
            'label'   => __( 'Sold Out Banner Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'SOLD OUT', 'woocustomizer' ),
        );
        $options['wcz-shop-show-stock'] = array(
            'id'          => 'wcz-shop-show-stock',
            'label'       => __( 'Show stock amount for Users', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'checkbox',
            'description' => __( 'This uses the stock set at product level.<br /><br />Use "[no]" in the text to display the stock amount.', 'woocustomizer' ),
            'default'     => 0,
        );
        $options['wcz-shop-stock-lowamnt-txt'] = array(
            'id'          => 'wcz-shop-stock-lowamnt-txt',
            'label'       => __( 'Low Stock Amount Text', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'text',
            'description' => __( 'If the product stock is 3 or less', 'woocustomizer' ),
            'default'     => __( 'Only [no] left in stock!', 'woocustomizer' ),
        );
        $options['wcz-shop-stock-amnt-txt'] = array(
            'id'      => 'wcz-shop-stock-amnt-txt',
            'label'   => __( 'Stock Amount Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( '[no] left in stock!', 'woocustomizer' ),
        );
        $options['wcz-shop-edit-btns'] = array(
            'id'      => 'wcz-shop-edit-btns',
            'label'   => __( 'Edit Shop Button Texts', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-shoplist-button-txt-simple'] = array(
            'id'      => 'wcz-shoplist-button-txt-simple',
            'label'   => __( 'Simple Product Button Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Add to cart', 'woocustomizer' ),
        );
        $options['wcz-shop-button-txt-variable'] = array(
            'id'      => 'wcz-shop-button-txt-variable',
            'label'   => __( 'Variable Product Button Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Select Options', 'woocustomizer' ),
        );
        $options['wcz-shop-button-txt-grouped'] = array(
            'id'      => 'wcz-shop-button-txt-grouped',
            'label'   => __( 'Grouped Product Button Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'View products', 'woocustomizer' ),
        );
        // New badge for recent products
        $options['wcz-shop-new-badge'] = array(
            'id'      => 'wcz-shop-new-badge',
            'label'   => __( 'Add a "New" badge for recently added products', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-shop-new-product-days'] = array(
            'id'          => 'wcz-shop-new-product-days',
            'label'       => __( 'Days to determine if product is "New"', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'number',
            'description' => __( 'The "New" badge will only display on products where the "Published On" date is less that the number of days specified here.', 'woocustomizer' ),
            'default'     => 7,
        );
        $options['wcz-shop-new-product-badge-text'] = array(
            'id'      => 'wcz-shop-new-product-badge-text',
            'label'   => __( 'New Product Badge Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'New Product', 'woocustomizer' ),
        );
        $choices = array(
            'topleft'    => 'Top Left',
            'topright'   => 'Top Right',
            'abovetitle' => 'Above Product Title',
            'belowtitle' => 'Below Product Title',
        );
        $options['wcz-shop-new-badge-pos'] = array(
            'id'      => 'wcz-shop-new-badge-pos',
            'label'   => __( 'Badge Position', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'select',
            'choices' => $choices,
            'default' => 'topleft',
        );
        $options['wcz-shop-new-badge-color'] = array(
            'id'      => 'wcz-shop-new-badge-color',
            'label'   => __( 'Badge Color', 'overlay' ),
            'section' => $section,
            'type'    => 'color',
            'default' => '#2f79ff',
        );
        // Archives Settings
        $options['wcz-heading-archives'] = array(
            'id'      => 'wcz-heading-archives',
            'label'   => __( 'Shop Archive / Categories Pages', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'url',
        );
        $options['wcz-shop-remove-catcount'] = array(
            'id'      => 'wcz-shop-remove-catcount',
            'label'   => __( 'Remove Category Count', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-shop-archives-remove-breadcrumbs'] = array(
            'id'      => 'wcz-shop-archives-remove-breadcrumbs',
            'label'   => __( 'Remove Shop Archive Breadcrumbs', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-shop-archives-remove-title'] = array(
            'id'      => 'wcz-shop-archives-remove-title',
            'label'   => __( 'Remove Archives Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        // ----------------------------------------------------------------------------------------------- WooCommerce Shop Page
        // ----------------------------------------------------------------------------------------------- WooCommerce Product Page
        $section = 'wcz-panel-product';
        $sections[] = array(
            'id'       => $section,
            'title'    => __( 'WooCommerce Product Page', 'woocustomizer' ),
            'priority' => '10',
            'panel'    => $panel,
        );
        $options['wcz-remove-product-breadcrumbs'] = array(
            'id'      => 'wcz-remove-product-breadcrumbs',
            'label'   => __( 'Remove Product Breadcrumbs', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-remove-product-zoom'] = array(
            'id'      => 'wcz-remove-product-zoom',
            'label'   => __( 'Remove Image Zoom', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-remove-product-lightbox'] = array(
            'id'      => 'wcz-remove-product-lightbox',
            'label'   => __( 'Remove Image Lightbox', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-remove-product-slider'] = array(
            'id'      => 'wcz-remove-product-slider',
            'label'   => __( 'Remove Image Slider', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-product-sale-txt'] = array(
            'id'      => 'wcz-product-sale-txt',
            'label'   => __( 'Sale Banner Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Sale!', 'woocustomizer' ),
        );
        $options['wcz-remove-product-title'] = array(
            'id'      => 'wcz-remove-product-title',
            'label'   => __( 'Remove Product Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-add-price-suffix'] = array(
            'id'      => 'wcz-add-price-suffix',
            'label'   => __( 'Add Price Suffix', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-add-price-suffix-txt'] = array(
            'id'      => 'wcz-add-price-suffix-txt',
            'label'   => __( 'Product Price Suffix Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Incl. VAT', 'woocustomizer' ),
        );
        $options['wcz-product-edit-btn'] = array(
            'id'      => 'wcz-product-edit-btn',
            'label'   => __( 'Edit Product Button Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-product-button-txt-simple'] = array(
            'id'      => 'wcz-product-button-txt-simple',
            'label'   => __( 'Product Button Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Add to cart', 'woocustomizer' ),
        );
        $options['wcz-remove-product-sku'] = array(
            'id'      => 'wcz-remove-product-sku',
            'label'   => __( 'Remove SKU', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-remove-product-cats'] = array(
            'id'      => 'wcz-remove-product-cats',
            'label'   => __( 'Remove Product Categories', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-remove-product-tags'] = array(
            'id'      => 'wcz-remove-product-tags',
            'label'   => __( 'Remove Product Tags', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-product-show-unitsold'] = array(
            'id'      => 'wcz-product-show-unitsold',
            'label'   => __( 'Show Amount Sold', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-product-unitsold-txt'] = array(
            'id'          => 'wcz-product-unitsold-txt',
            'label'       => __( 'Amount Sold Text', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'text',
            'description' => __( 'Use "[no]" in the text to display the amount sold.', 'woocustomizer' ),
            'default'     => __( 'Items Sold: [no]', 'woocustomizer' ),
        );
        $options['wcz-product-outofstock-txt'] = array(
            'id'      => 'wcz-product-outofstock-txt',
            'label'   => __( 'Out Of Stock Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Out Of Stock', 'woocustomizer' ),
        );
        $options['wcz-show-other-stockstatus'] = array(
            'id'      => 'wcz-show-other-stockstatus',
            'label'   => __( 'Always Show All Stock Status Types', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-product-instock-txt'] = array(
            'id'      => 'wcz-product-instock-txt',
            'label'   => __( 'In Stock Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Stock Available', 'woocustomizer' ),
        );
        $options['wcz-product-onbackorder-txt'] = array(
            'id'      => 'wcz-product-onbackorder-txt',
            'label'   => __( 'On Back Order Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'On Back Order', 'woocustomizer' ),
        );
        $options['wcz-add-shop-button'] = array(
            'id'      => 'wcz-add-shop-button',
            'label'   => __( 'Add button to \'Continue Shopping\'', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-add-shop-button-txt'] = array(
            'id'      => 'wcz-add-shop-button-txt',
            'label'   => __( 'Continue Shopping Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Continue Shopping', 'woocustomizer' ),
        );
        // EXCLUDED FROM FREE VERSION -- This "if" block will be auto removed from the Free version.
        $options['wcz-set-cart-increment-vals'] = array(
            'id'      => 'wcz-set-cart-increment-vals',
            'label'   => __( 'Set \'Add To Cart\' Increment values', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-set-cart-inc-min'] = array(
            'id'      => 'wcz-set-cart-inc-min',
            'label'   => __( 'Minimum Value Allowed', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'number',
            'default' => 3,
        );
        $options['wcz-set-cart-inc-max'] = array(
            'id'      => 'wcz-set-cart-inc-max',
            'label'   => __( 'Minimum Value Allowed', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'number',
            'default' => 10,
        );
        $options['wcz-set-cart-inc-by'] = array(
            'id'      => 'wcz-set-cart-inc-by',
            'label'   => __( 'Increment / Decrement By', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'number',
            'default' => 1,
        );
        // Product Settings - Tabs
        $options['wcz-heading-product-tabs'] = array(
            'id'      => 'wcz-heading-product-tabs',
            'label'   => __( 'Product Page Tabs', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'url',
        );
        $choices = array(
            'reset'                         => '',
            'wcz-wcproduct-desc-tab-edit'   => 'Edit Tab Text',
            'wcz-wcproduct-desc-tab-remove' => 'Remove Tab',
        );
        $options['wcz-wcproduct-desc-tab'] = array(
            'id'      => 'wcz-wcproduct-desc-tab',
            'label'   => __( 'Description Tab', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'radio',
            'choices' => $choices,
            'default' => '',
        );
        $options['wcz-wcproduct-desc-tab-title'] = array(
            'id'      => 'wcz-wcproduct-desc-tab-title',
            'label'   => __( 'Tab Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Description', 'woocustomizer' ),
        );
        $options['wcz-wcproduct-desc-head'] = array(
            'id'      => 'wcz-wcproduct-desc-head',
            'label'   => __( 'Tab Heading', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Description', 'woocustomizer' ),
        );
        // Edit/Remove Description Tab
        $choices = array(
            'reset'                            => '',
            'wcz-wcproduct-addinfo-tab-edit'   => 'Edit Tab Text',
            'wcz-wcproduct-addinfo-tab-remove' => 'Remove Tab',
        );
        $options['wcz-wcproduct-addinfo-tab'] = array(
            'id'      => 'wcz-wcproduct-addinfo-tab',
            'label'   => __( 'Additional Info Tab', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'radio',
            'choices' => $choices,
            'default' => '',
        );
        $options['wcz-wcproduct-addinfo-tab-title'] = array(
            'id'      => 'wcz-wcproduct-addinfo-tab-title',
            'label'   => __( 'Tab Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Additional Information', 'woocustomizer' ),
        );
        $options['wcz-wcproduct-addinfo-head'] = array(
            'id'      => 'wcz-wcproduct-addinfo-head',
            'label'   => __( 'Tab Heading', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Additional Information', 'woocustomizer' ),
        );
        // Edit/Remove Additional Info Tab
        $choices = array(
            'reset'                            => '',
            'wcz-wcproduct-reviews-tab-edit'   => 'Edit Tab Text',
            'wcz-wcproduct-reviews-tab-remove' => 'Remove Tab',
        );
        $options['wcz-wcproduct-reviews-tab'] = array(
            'id'      => 'wcz-wcproduct-reviews-tab',
            'label'   => __( 'Reviews Tab', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'radio',
            'choices' => $choices,
            'default' => '',
        );
        $options['wcz-wcproduct-reviews-tab-title'] = array(
            'id'      => 'wcz-wcproduct-reviews-tab-title',
            'label'   => __( 'Tab Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Reviews', 'woocustomizer' ),
        );
        // Edit/Remove Reviews Tab
        $options['wcz-add-product-long-desc'] = array(
            'id'      => 'wcz-add-product-long-desc',
            'label'   => __( 'Add Product Long Description after Tabs', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        // Product Settings - Related & Recommended
        $options['wcz-heading-product-relrec'] = array(
            'id'      => 'wcz-heading-product-relrec',
            'label'   => __( 'Related & Recommended Products', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'url',
        );
        $choices = array(
            'reset'                       => '',
            'wcz-wcproduct-recomm-edit'   => 'Edit Title',
            'wcz-wcproduct-recomm-remove' => 'Remove Section',
        );
        $options['wcz-wcproduct-recomm'] = array(
            'id'      => 'wcz-wcproduct-recomm',
            'label'   => __( 'Product Recommendations', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'radio',
            'choices' => $choices,
            'default' => '',
        );
        $options['wcz-wcproduct-recomm-title'] = array(
            'id'      => 'wcz-wcproduct-recomm-title',
            'label'   => __( 'Recommendations Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'You may also like&hellip;', 'woocustomizer' ),
        );
        // Edit/Remove Product Recommedations
        $choices = array(
            'reset'                        => '',
            'wcz-wcproduct-related-edit'   => 'Edit Title',
            'wcz-wcproduct-related-remove' => 'Remove Section',
        );
        $options['wcz-wcproduct-related'] = array(
            'id'      => 'wcz-wcproduct-related',
            'label'   => __( 'Related Products', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'radio',
            'choices' => $choices,
            'default' => '',
        );
        $options['wcz-wcproduct-related-title'] = array(
            'id'      => 'wcz-wcproduct-related-title',
            'label'   => __( 'Related Products Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Related products', 'woocustomizer' ),
        );
        // Edit/Remove Related Products
        // ----------------------------------------------------------------------------------------------- WooCommerce Product Page
        // ----------------------------------------------------------------------------------------------- WooCommerce Account Page
        $section = 'wcz-panel-account';
        $sections[] = array(
            'id'       => $section,
            'title'    => __( 'Account Page', 'woocustomizer' ),
            'priority' => '10',
            'panel'    => $panel,
        );
        $choices = array(
            'wcz-tabstyle-none'       => 'None',
            'wcz-tabstyle-side'       => 'Side Tab Styling',
            'wcz-tabstyle-horizontal' => 'Horizontal Styling',
        );
        $options['wcz-tab-style'] = array(
            'id'          => 'wcz-tab-style',
            'label'       => __( 'Account Tab Design', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'select',
            'choices'     => $choices,
            'description' => __( 'Account Tab Design is only for if your theme has not added custom styling to your WooCommerce Account Page.', 'woocustomizer' ),
            'default'     => 'wcz-tabstyle-none',
        );
        // Account Tabs
        $options['wcz-heading-account-tabs'] = array(
            'id'      => 'wcz-heading-account-tabs',
            'label'   => __( 'Account Tabs', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'url',
        );
        $choices = array(
            'reset'                        => '',
            'wcz-account-dashboard-edit'   => 'Edit Tab Text',
            'wcz-account-dashboard-remove' => 'Remove Tab',
        );
        $options['wcz-account-dashboard-tab'] = array(
            'id'      => 'wcz-account-dashboard-tab',
            'label'   => __( 'Account Dashboard Tab', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'radio',
            'choices' => $choices,
            'default' => '',
        );
        $options['wcz-account-tab-dash-tab'] = array(
            'id'      => 'wcz-account-tab-dash-tab',
            'label'   => __( 'Dashboard Tab Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Dashboard', 'woocustomizer' ),
        );
        // Edit/Remove Dashboard Tab
        $choices = array(
            'reset'                     => '',
            'wcz-account-orders-edit'   => 'Edit Tab Text',
            'wcz-account-orders-remove' => 'Remove Tab',
        );
        $options['wcz-account-orders-tab'] = array(
            'id'      => 'wcz-account-orders-tab',
            'label'   => __( 'Orders Tab', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'radio',
            'choices' => $choices,
            'default' => '',
        );
        $options['wcz-account-tab-orders-tab'] = array(
            'id'      => 'wcz-account-tab-orders-tab',
            'label'   => __( 'Tab Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Orders', 'woocustomizer' ),
        );
        $options['wcz-account-tab-orders-title'] = array(
            'id'      => 'wcz-account-tab-orders-title',
            'label'   => __( 'Page Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Orders', 'woocustomizer' ),
        );
        // Edit/Remove Orders Tab
        $choices = array(
            'reset'                        => '',
            'wcz-account-downloads-edit'   => 'Edit Tab Text',
            'wcz-account-downloads-remove' => 'Remove Tab',
        );
        $options['wcz-account-downloads-tab'] = array(
            'id'      => 'wcz-account-downloads-tab',
            'label'   => __( 'Downloads Tab', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'radio',
            'choices' => $choices,
            'default' => '',
        );
        $options['wcz-account-tab-downloads-tab'] = array(
            'id'      => 'wcz-account-tab-downloads-tab',
            'label'   => __( 'Tab Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Downloads', 'woocustomizer' ),
        );
        $options['wcz-account-tab-downloads-title'] = array(
            'id'      => 'wcz-account-tab-downloads-title',
            'label'   => __( 'Page Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Downloads', 'woocustomizer' ),
        );
        // Edit/Remove Downloads Tab
        $choices = array(
            'reset'                      => '',
            'wcz-account-address-edit'   => 'Edit Tab Text',
            'wcz-account-address-remove' => 'Remove Tab',
        );
        $options['wcz-account-address-tab'] = array(
            'id'      => 'wcz-account-address-tab',
            'label'   => __( 'Address Tab', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'radio',
            'choices' => $choices,
            'default' => '',
        );
        $options['wcz-account-tab-address-tab'] = array(
            'id'      => 'wcz-account-tab-address-tab',
            'label'   => __( 'Tab Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Addresses', 'woocustomizer' ),
        );
        $options['wcz-account-tab-address-title'] = array(
            'id'      => 'wcz-account-tab-address-title',
            'label'   => __( 'Page Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Addresses', 'woocustomizer' ),
        );
        // Edit/Remove Addresses Tab
        $choices = array(
            'reset'                      => '',
            'wcz-account-details-edit'   => 'Edit Tab Text',
            'wcz-account-details-remove' => 'Remove Tab',
        );
        $options['wcz-account-details-tab'] = array(
            'id'      => 'wcz-account-details-tab',
            'label'   => __( 'Account Details Tab', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'radio',
            'choices' => $choices,
            'default' => '',
        );
        $options['wcz-account-tab-details-tab'] = array(
            'id'      => 'wcz-account-tab-details-tab',
            'label'   => __( 'Tab Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Account Details', 'woocustomizer' ),
        );
        $options['wcz-account-tab-details-title'] = array(
            'id'      => 'wcz-account-tab-details-title',
            'label'   => __( 'Page Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Account Details', 'woocustomizer' ),
        );
        // Edit/Remove Account Details Tab
        $choices = array(
            'reset'                     => '',
            'wcz-account-logout-edit'   => 'Edit Tab Text',
            'wcz-account-logout-remove' => 'Remove Tab',
        );
        $options['wcz-account-logout-tab'] = array(
            'id'      => 'wcz-account-logout-tab',
            'label'   => __( 'Logout Tab', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'radio',
            'choices' => $choices,
            'default' => '',
        );
        $options['wcz-account-tab-logout-tab'] = array(
            'id'      => 'wcz-account-tab-logout-tab',
            'label'   => __( 'Tab Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Logout', 'woocustomizer' ),
        );
        // EXCLUDED FROM FREE VERSION -- This "if" block will be auto removed from the Free version.
        // ----------------------------------------------------------------------------------------------- WooCommerce Account Page
        // ----------------------------------------------------------------------------------------------- WooCommerce Cart Page
        $section = 'wcz-panel-cart';
        $sections[] = array(
            'id'       => $section,
            'title'    => __( 'Cart Page', 'woocustomizer' ),
            'priority' => '10',
            'panel'    => $panel,
        );
        $options['wcz-cart-remove-coupons'] = array(
            'id'      => 'wcz-cart-remove-coupons',
            'label'   => __( 'Remove Coupons form', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-cart-ajax-update'] = array(
            'id'          => 'wcz-cart-ajax-update',
            'label'       => __( 'Auto Update Cart Amount', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'checkbox',
            'description' => __( 'Users will not need to click \'Update Cart\' anymore, this will remove the update button and will auto update the cart as users change the product amounts.', 'woocustomizer' ),
            'default'     => 0,
        );
        $options['wcz-heading-final'] = array(
            'id'      => 'wcz-heading-final',
            'label'   => __( 'Cross Sells & Cart Totals', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'url',
        );
        $options['wcz-cart-move-crollsells-below'] = array(
            'id'      => 'wcz-cart-move-crollsells-below',
            'label'   => __( 'Move Cross Sells to below Cart Totals', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-cart-remove-cross-sells'] = array(
            'id'      => 'wcz-cart-remove-cross-sells',
            'label'   => __( 'Remove Cross Sell Section', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $choices = array(
            'reset'                    => '',
            'wcz-wccart-recomm-edit'   => 'Edit Title',
            'wcz-wccart-recomm-remove' => 'Remove Title',
        );
        $options['wcz-wccart-recomm'] = array(
            'id'      => 'wcz-wccart-recomm',
            'label'   => __( 'Cross Sells Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'radio',
            'choices' => $choices,
            'default' => '',
        );
        $options['wcz-wccart-recomm-title'] = array(
            'id'      => 'wcz-wccart-recomm-title',
            'label'   => __( 'Cross Sells Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'You may be interested in&hellip;', 'woocustomizer' ),
        );
        // Edit/Remove Cart Cross Sells Title
        $options['wcz-cart-crosssells-ppr'] = array(
            'id'          => 'wcz-cart-crosssells-ppr',
            'label'       => __( 'Products Per Row', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'range',
            'input_attrs' => array(
            'min'  => 1,
            'max'  => 5,
            'step' => 1,
        ),
            'description' => __( '<i>1</i> <b>|</b> 2 <b>|</b> 3 <b>|</b> 4 <b>|</b> <i>5</i>', 'woocustomizer' ),
            'default'     => 2,
        );
        $choices = array(
            'reset'                    => '',
            'wcz-wccart-totals-edit'   => 'Edit Title',
            'wcz-wccart-totals-remove' => 'Remove Title',
        );
        $options['wcz-wccart-totals'] = array(
            'id'      => 'wcz-wccart-totals',
            'label'   => __( 'Cart Totals Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'radio',
            'choices' => $choices,
            'default' => '',
        );
        $options['wcz-wccart-totals-title'] = array(
            'id'      => 'wcz-wccart-totals-title',
            'label'   => __( 'Cart Totals Title', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Cart totals', 'woocustomizer' ),
        );
        // Edit/Remove Cart Totals Title
        // Empty Cart Page Settings
        $options['wcz-heading-emptycart'] = array(
            'id'      => 'wcz-heading-emptycart',
            'label'   => __( 'Empty Cart Page', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'url',
        );
        $options['wcz-cart-add-custom-text'] = array(
            'id'      => 'wcz-cart-add-custom-text',
            'label'   => __( 'Add Custom Text to Empty Cart Page', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-cart-empty-txt'] = array(
            'id'          => 'wcz-cart-empty-txt',
            'label'       => __( 'Page Text', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'textarea',
            'description' => __( 'This will only show on the Empty Cart Page', 'woocustomizer' ),
            'default'     => __( 'Some Extra Text', 'woocustomizer' ),
        );
        // ----------------------------------------------------------------------------------------------- WooCommerce Cart Page
        // ----------------------------------------------------------------------------------------------- WooCommerce Checkout Page
        $section = 'wcz-panel-checkout';
        $sections[] = array(
            'id'       => $section,
            'title'    => __( 'Checkout Page', 'woocustomizer' ),
            'priority' => '10',
            'panel'    => $panel,
        );
        $options['wcz-checkout-edit-coupon-txt'] = array(
            'id'      => 'wcz-checkout-edit-coupon-txt',
            'label'   => __( 'Edit Coupon Section Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-checkout-coupon-text'] = array(
            'id'      => 'wcz-checkout-coupon-text',
            'label'   => __( 'Coupon Code Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Have a coupon?', 'woocustomizer' ),
        );
        $options['wcz-checkout-coupon-link-text'] = array(
            'id'      => 'wcz-checkout-coupon-link-text',
            'label'   => __( 'Coupon Code Link Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Click here to enter your code', 'woocustomizer' ),
        );
        $options['wcz-checkout-coupon-instruction-text'] = array(
            'id'      => 'wcz-checkout-coupon-instruction-text',
            'label'   => __( 'Coupon Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'If you have a coupon code, please apply it below.', 'woocustomizer' ),
        );
        $options['wcz-checkout-edit-ordernotes-txt'] = array(
            'id'      => 'wcz-checkout-edit-ordernotes-txt',
            'label'   => __( 'Edit Order Notes Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-checkout-ordernotes-label'] = array(
            'id'      => 'wcz-checkout-ordernotes-label',
            'label'   => __( 'Label Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Order notes', 'woocustomizer' ),
        );
        $options['wcz-checkout-ordernotes-placeholder'] = array(
            'id'      => 'wcz-checkout-ordernotes-placeholder',
            'label'   => __( 'Placeholder Text', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'text',
            'default' => __( 'Notes about your order, e.g. special notes for delivery.', 'woocustomizer' ),
        );
        $options['wcz-checkout-add-img'] = array(
            'id'          => 'wcz-checkout-add-img',
            'label'       => __( 'Add Custom Image', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'checkbox',
            'description' => __( 'This can be used to ensure that the site is secure for your users.', 'woocustomizer' ),
            'default'     => 0,
        );
        $options['wcz-checkout-img'] = array(
            'id'          => 'wcz-checkout-img',
            'label'       => __( 'Upload Image', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'upload',
            'description' => __( 'Upload this image at the correct size.', 'woocustomizer' ),
            'default'     => '',
        );
        $options['wcz-checkout-img-center'] = array(
            'id'      => 'wcz-checkout-img-center',
            'label'   => __( 'Center Image', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-checkout-add-po-txt'] = array(
            'id'      => 'wcz-checkout-add-po-txt',
            'label'   => __( 'Add Text under Place Order button', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-checkout-po-txt'] = array(
            'id'          => 'wcz-checkout-po-txt',
            'label'       => __( 'Custom Text', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'textarea',
            'description' => __( 'This will only show up once you refresh and/or go to the Checkout page.', 'woocustomizer' ),
            'default'     => __( 'Your personal data will help us create your account and to support your user experience throughout this website. Please have a look at our Privacy Policy for more information on how we use your personal data', 'woocustomizer' ),
        );
        $options['wcz-heading-remove-checkout-fields'] = array(
            'id'      => 'wcz-heading-remove-checkout-fields',
            'label'   => __( 'Remove Checkout Fields', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'url',
        );
        $options['wcz-checkout-remove-lastname'] = array(
            'id'      => 'wcz-checkout-remove-lastname',
            'label'   => __( 'Remove Last Name', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-checkout-remove-company'] = array(
            'id'      => 'wcz-checkout-remove-company',
            'label'   => __( 'Remove Company Name', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-checkout-remove-address'] = array(
            'id'      => 'wcz-checkout-remove-address',
            'label'   => __( 'Remove Address', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-checkout-remove-towncity'] = array(
            'id'      => 'wcz-checkout-remove-towncity',
            'label'   => __( 'Remove Town / City', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-checkout-remove-provstate'] = array(
            'id'      => 'wcz-checkout-remove-provstate',
            'label'   => __( 'Remove State / Province & Zip Code', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-checkout-remove-phone'] = array(
            'id'      => 'wcz-checkout-remove-phone',
            'label'   => __( 'Remove Phone Number', 'woocustomizer' ),
            'section' => $section,
            'type'    => 'checkbox',
            'default' => 0,
        );
        $options['wcz-checkout-remove-order-notes'] = array(
            'id'          => 'wcz-checkout-remove-order-notes',
            'label'       => __( 'Remove Order Notes', 'woocustomizer' ),
            'section'     => $section,
            'type'        => 'checkbox',
            'description' => __( 'Edit <a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=shipping&section=options' ) ) . '" target="_blank">Shipping destination</a> to remove the Shipping Address details.', 'woocustomizer' ),
            'default'     => 0,
        );
        // EXCLUDED FROM FREE VERSION -- This "if" block will be auto removed from the Free version.
        // --------------------------------------------------------------------------------------------------------------------------------- WC Panel
    }
    
    // is WooCommerce Activated - end
    // Adds the sections to the $options array
    $options['sections'] = $sections;
    // Adds the panels to the $options array
    $options['panels'] = $panels;
    $customizer_library = WooCustomizer_Library::Instance();
    $customizer_library->add_options( $options );
    // To delete custom mods use: woocustomizer_library_remove_theme_mods();
}

add_action( 'init', 'woocustomizer_customizer_library_options' );