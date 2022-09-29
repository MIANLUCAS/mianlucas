/**
 * WooCustomizer Custom JS
 */
( function( $ ) {
    $( document ).ready( function () {

        // Show values for Range Inputs
        $(document).on('input change', 'input[type="range"]', function() {
            $(this).prev().find( '.wcz-rangeval' ).html( $(this).val() );
        });

        // Show / Hide Breadcrumbs
        wcz_wc_bcrumbs();
        $( '#customize-control-wcz-wc-remove-breadcrumbs input[type=checkbox]' ).on( 'change', function() {
            wcz_wc_bcrumbs();
        });
        function wcz_wc_bcrumbs() {
            if ( $( '#customize-control-wcz-wc-remove-breadcrumbs input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-remove-breadcrumbs' ).hide();
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-remove-product-breadcrumbs' ).hide();
            } else {
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-remove-breadcrumbs' ).show();
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-remove-product-breadcrumbs' ).show();
            }
        }

        // Show / Hide Button Design Settings
        var wcz_btn_style = $( '#customize-control-wcz-btn-style select' ).val();
        wcz_btn_style_value_check( wcz_btn_style );
        $( '#customize-control-wcz-btn-style select' ).on( 'change', function() {
            var wcz_btn_style_value = $( this ).val();
            wcz_btn_style_value_check( wcz_btn_style_value );
        } );
        function wcz_btn_style_value_check( wcz_btn_style_value ) {
            if ( wcz_btn_style_value == 'wcz-btn-style-default' ) {
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-wc-edit-btns' ).hide();
            } else {
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-wc-edit-btns' ).show();
            }
        }
        wcz_wc_btn_design();
        $( '#customize-control-wcz-wc-edit-btns input[type=checkbox], #customize-control-wcz-btn-style select' ).on( 'change', function() {
            wcz_wc_btn_design();
        });
        function wcz_wc_btn_design() {
            if ( $( '#customize-control-wcz-btn-style select' ).val() != 'wcz-btn-style-default' && $( '#customize-control-wcz-wc-edit-btns input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-btn-fsize' ).show();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-btn-fweight' ).show();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-btn-bgcolor' ).show();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-btn-hovercolor' ).show();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-btn-br' ).show();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-btn-padding' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-btn-fsize' ).hide();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-btn-fweight' ).hide();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-btn-bgcolor' ).hide();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-btn-hovercolor' ).hide();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-btn-br' ).hide();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-btn-padding' ).hide();
            }
        }

        // Show / Hide Sale Banner Design Settings
        wcz_wc_sale_design();
        $( '#customize-control-wcz-wc-edit-sale input[type=checkbox]' ).on( 'change', function() {
            wcz_wc_sale_design();
        });
        function wcz_wc_sale_design() {
            if ( $( '#customize-control-wcz-wc-edit-sale input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-sale-fsize' ).show();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-sale-fweight' ).show();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-sale-bgcolor' ).show();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-sale-br' ).show();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-sale-padding' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-sale-fsize' ).hide();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-sale-fweight' ).hide();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-sale-bgcolor' ).hide();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-sale-br' ).hide();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-sale-padding' ).hide();
            }
        }

        var wcz_loginout = $( '#customize-control-wcz-login-logout-menu select' ).val();
        wcz_loginout_value_check( wcz_loginout );
        $( '#customize-control-wcz-login-logout-menu select' ).on( 'change', function() {
            var wcz_loginout_value = $( this ).val();
            wcz_loginout_value_check( wcz_loginout_value );
        } );
        function wcz_loginout_value_check( wcz_loginout_value ) {
            if ( wcz_loginout_value == 'none' ) {
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-login-redirect-page' ).hide();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-logout-redirect-page' ).hide();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-login-text' ).hide();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-logout-text' ).hide();
            } else {
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-login-redirect-page' ).show();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-logout-redirect-page' ).show();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-login-text' ).show();
                $( '#sub-accordion-section-wcz-panel-woocustomizer #customize-control-wcz-logout-text' ).show();
            }
        }

        // Show / Hide Sold Out Banner Text
        wcz_shop_soldout_banner();
        $( '#customize-control-wcz-shop-add-soldout input[type=checkbox]' ).on( 'change', function() {
            wcz_shop_soldout_banner();
        });
        function wcz_shop_soldout_banner() {
            if ( $( '#customize-control-wcz-shop-add-soldout input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-soldout-txt' ).show();
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-soldout-style' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-soldout-txt' ).hide();
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-soldout-style' ).hide();
            }
        }

        // Show / Hide Sold Out Banner Text
        wcz_shop_show_stock_amount();
        $( '#customize-control-wcz-shop-show-stock input[type=checkbox]' ).on( 'change', function() {
            wcz_shop_show_stock_amount();
        });
        function wcz_shop_show_stock_amount() {
            if ( $( '#customize-control-wcz-shop-show-stock input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-stock-lowamnt-txt' ).show();
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-stock-amnt-txt' ).show();
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-show-stock .customize-control-description' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-stock-lowamnt-txt' ).hide();
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-stock-amnt-txt' ).hide();
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-show-stock .customize-control-description' ).hide();
            }
        }
        
        // Show / Hide Product Status Texts
        wcz_product_status_design();
        $( '#customize-control-wcz-show-other-stockstatus input[type=checkbox]' ).on( 'change', function() {
            wcz_product_status_design();
        });
        function wcz_product_status_design() {
            if ( $( '#customize-control-wcz-show-other-stockstatus input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-product-instock-txt' ).show();
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-product-onbackorder-txt' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-product-instock-txt' ).hide();
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-product-onbackorder-txt' ).hide();
            }
        }

        // Show / Hide Edit Button Texts
        wcz_shop_edit_btns();
        $( '#customize-control-wcz-shop-edit-btns input[type=checkbox]' ).on( 'change', function() {
            wcz_shop_edit_btns();
        });
        function wcz_shop_edit_btns() {
            if ( $( '#customize-control-wcz-shop-edit-btns input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shoplist-button-txt-simple' ).show();
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-button-txt-variable' ).show();
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-button-txt-grouped' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shoplist-button-txt-simple' ).hide();
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-button-txt-variable' ).hide();
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-button-txt-grouped' ).hide();
            }
        }

        // Show / Hide Product Continue Shopping Button Text
        wcz_product_shop_btn_design();
        $( '#customize-control-wcz-add-shop-button input[type=checkbox]' ).on( 'change', function() {
            wcz_product_shop_btn_design();
        });
        function wcz_product_shop_btn_design() {
            if ( $( '#customize-control-wcz-add-shop-button input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-add-shop-button-txt' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-add-shop-button-txt' ).hide();
            }
        }

        // Show / Hide Price Suffix
        wcz_product_price_suffix();
        $( '#customize-control-wcz-add-price-suffix input[type=checkbox]' ).on( 'change', function() {
            wcz_product_price_suffix();
        });
        function wcz_product_price_suffix() {
            if ( $( '#customize-control-wcz-add-price-suffix input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-add-price-suffix-txt' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-add-price-suffix-txt' ).hide();
            }
        }

        // Show / Hide Product Status Texts
        wcz_product_btn_text();
        $( '#customize-control-wcz-product-edit-btn input[type=checkbox]' ).on( 'change', function() {
            wcz_product_btn_text();
        });
        function wcz_product_btn_text() {
            if ( $( '#customize-control-wcz-product-edit-btn input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-product-button-txt-simple' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-product-button-txt-simple' ).hide();
            }
        }

        // Show / Hide New Product Badge details
        wcz_checkout_new_badge();
        $( '#customize-control-wcz-shop-new-badge input[type=checkbox]' ).on( 'change', function() {
            wcz_checkout_new_badge();
        });
        function wcz_checkout_new_badge() {
            if ( $( '#customize-control-wcz-shop-new-badge input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-new-product-days' ).show();
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-new-product-badge-text' ).show();
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-new-badge-pos' ).show();
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-new-badge-color' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-new-product-days' ).hide();
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-new-product-badge-text' ).hide();
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-new-badge-pos' ).hide();
                $( '#sub-accordion-section-wcz-panel-shop #customize-control-wcz-shop-new-badge-color' ).hide();
            }
        }

        // Show / Hide Product Amount Sold
        wcz_product_amount_sold();
        $( '#customize-control-wcz-product-show-unitsold input[type=checkbox]' ).on( 'change', function() {
            wcz_product_amount_sold();
        });
        function wcz_product_amount_sold() {
            if ( $( '#customize-control-wcz-product-show-unitsold input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-product-unitsold-txt' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-product-unitsold-txt' ).hide();
            }
        }

        // Radio Options for Product Page
        wcz_product_recomsection_tab();
        $( '#customize-control-wcz-wcproduct-recomm input[type=radio]' ).on( 'change', function() {
            wcz_product_recomsection_tab();
        });
        function wcz_product_recomsection_tab() {
            if ( $( '#customize-control-wcz-wcproduct-recomm input[type=radio]:checked' ).val() == 'wcz-wcproduct-recomm-edit' ) {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-wcproduct-recomm-title' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-wcproduct-recomm-title' ).hide();
            }
        }

        wcz_product_relatedsection_tab();
        $( '#customize-control-wcz-wcproduct-related input[type=radio]' ).on( 'change', function() {
            wcz_product_relatedsection_tab();
        });
        function wcz_product_relatedsection_tab() {
            if ( $( '#customize-control-wcz-wcproduct-related input[type=radio]:checked' ).val() == 'wcz-wcproduct-related-edit' ) {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-wcproduct-related-title' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-wcproduct-related-title' ).hide();
            }
        }

        // Show / Hide Add To Cart Increment Values
        wcz_product_inc_values();
        $( '#customize-control-wcz-set-cart-increment-vals input[type=checkbox]' ).on( 'change', function() {
            wcz_product_inc_values();
        });
        function wcz_product_inc_values() {
            if ( $( '#customize-control-wcz-set-cart-increment-vals input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-set-cart-inc-min' ).show();
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-set-cart-inc-max' ).show();
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-set-cart-inc-by' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-set-cart-inc-min' ).hide();
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-set-cart-inc-max' ).hide();
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-set-cart-inc-by' ).hide();
            }
        }

        // Product Page Tabs
        wcz_product_desc_tab();
        $( '#customize-control-wcz-wcproduct-desc-tab input[type=radio]' ).on( 'change', function() {
            wcz_product_desc_tab();
        });
        function wcz_product_desc_tab() {
            if ( $( '#customize-control-wcz-wcproduct-desc-tab input[type=radio]:checked' ).val() == 'wcz-wcproduct-desc-tab-edit' ) {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-wcproduct-desc-tab-title' ).show();
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-wcproduct-desc-head' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-wcproduct-desc-tab-title' ).hide();
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-wcproduct-desc-head' ).hide();
            }
        }

        wcz_product_addinfo_tab();
        $( '#customize-control-wcz-wcproduct-addinfo-tab input[type=radio]' ).on( 'change', function() {
            wcz_product_addinfo_tab();
        });
        function wcz_product_addinfo_tab() {
            if ( $( '#customize-control-wcz-wcproduct-addinfo-tab input[type=radio]:checked' ).val() == 'wcz-wcproduct-addinfo-tab-edit' ) {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-wcproduct-addinfo-tab-title' ).show();
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-wcproduct-addinfo-head' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-wcproduct-addinfo-tab-title' ).hide();
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-wcproduct-addinfo-head' ).hide();
            }
        }

        wcz_product_reviews_tab();
        $( '#customize-control-wcz-wcproduct-reviews-tab input[type=radio]' ).on( 'change', function() {
            wcz_product_reviews_tab();
        });
        function wcz_product_reviews_tab() {
            if ( $( '#customize-control-wcz-wcproduct-reviews-tab input[type=radio]:checked' ).val() == 'wcz-wcproduct-reviews-tab-edit' ) {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-wcproduct-reviews-tab-title' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-product #customize-control-wcz-wcproduct-reviews-tab-title' ).hide();
            }
        }

        // Radio Options for Account Page - Dashboard Tab
        wcz_account_dashboard_tab();
        $( '#customize-control-wcz-account-dashboard-tab input[type=radio]' ).on( 'change', function() {
            wcz_account_dashboard_tab();
        });
        function wcz_account_dashboard_tab() {
            if ( $( '#customize-control-wcz-account-dashboard-tab input[type=radio]:checked' ).val() == 'wcz-account-dashboard-edit' ) {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-dash-tab' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-dash-tab' ).hide();
            }
        }

        // Orders Tab
        wcz_account_orders_tab();
        $( '#customize-control-wcz-account-orders-tab input[type=radio]' ).on( 'change', function() {
            wcz_account_orders_tab();
        });
        function wcz_account_orders_tab() {
            if ( $( '#customize-control-wcz-account-orders-tab input[type=radio]:checked' ).val() == 'wcz-account-orders-edit' ) {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-orders-tab' ).show();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-orders-title' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-orders-tab' ).hide();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-orders-title' ).hide();
            }
        }

        // Downloads Tab
        wcz_account_downloads_tab();
        $( '#customize-control-wcz-account-downloads-tab input[type=radio]' ).on( 'change', function() {
            wcz_account_downloads_tab();
        });
        function wcz_account_downloads_tab() {
            if ( $( '#customize-control-wcz-account-downloads-tab input[type=radio]:checked' ).val() == 'wcz-account-downloads-edit' ) {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-downloads-tab' ).show();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-downloads-title' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-downloads-tab' ).hide();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-downloads-title' ).hide();
            }
        }

        // Address Tab
        wcz_account_address_tab();
        $( '#customize-control-wcz-account-address-tab input[type=radio]' ).on( 'change', function() {
            wcz_account_address_tab();
        });
        function wcz_account_address_tab() {
            if ( $( '#customize-control-wcz-account-address-tab input[type=radio]:checked' ).val() == 'wcz-account-address-edit' ) {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-address-tab' ).show();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-address-title' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-address-tab' ).hide();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-address-title' ).hide();
            }
        }

        // Account Details Tab
        wcz_account_details_tab();
        $( '#customize-control-wcz-account-details-tab input[type=radio]' ).on( 'change', function() {
            wcz_account_details_tab();
        });
        function wcz_account_details_tab() {
            if ( $( '#customize-control-wcz-account-details-tab input[type=radio]:checked' ).val() == 'wcz-account-details-edit' ) {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-details-tab' ).show();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-details-title' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-details-tab' ).hide();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-details-title' ).hide();
            }
        }

        // Logout Tab
        wcz_account_logout_tab();
        $( '#customize-control-wcz-account-logout-tab input[name=_customize-radio-wcz-account-logout-tab]' ).on( 'change', function() {
            wcz_account_logout_tab();
        });
        function wcz_account_logout_tab() {
            if ( $( '#customize-control-wcz-account-logout-tab input[name=_customize-radio-wcz-account-logout-tab]:checked' ).val() == 'wcz-account-logout-edit' ) {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-logout-tab' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-logout-tab' ).hide();
            }
        }

        // New Custom Tab - One
        wcz_account_custom_tab_one();
        $( '#customize-control-wcz-accendpoint-one input[type=checkbox]' ).on( 'change', function() {
            wcz_account_custom_tab_one();
        });
        function wcz_account_custom_tab_one() {
            if ( $( '#customize-control-wcz-accendpoint-one input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-one-tab' ).show();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-one-title' ).show();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-one-pageid' ).show();
                $( '#customize-control-wcz-accendpoint-one .customize-control-description' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-one-tab' ).hide();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-one-title' ).hide();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-one-pageid' ).hide();
                $( '#customize-control-wcz-accendpoint-one .customize-control-description' ).hide();
            }
        }

        // New Custom Tab - Two
        wcz_account_custom_tab_two();
        $( '#customize-control-wcz-accendpoint-two input[type=checkbox]' ).on( 'change', function() {
            wcz_account_custom_tab_two();
        });
        function wcz_account_custom_tab_two() {
            if ( $( '#customize-control-wcz-accendpoint-two input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-two-tab' ).show();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-two-title' ).show();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-two-pageid' ).show();
                $( '#customize-control-wcz-accendpoint-two .customize-control-description' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-two-tab' ).hide();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-two-title' ).hide();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-two-pageid' ).hide();
                $( '#customize-control-wcz-accendpoint-two .customize-control-description' ).hide();
            }
        }
        
        // New Custom Tab - Three
        wcz_account_custom_tab_three();
        $( '#customize-control-wcz-accendpoint-three input[type=checkbox]' ).on( 'change', function() {
            wcz_account_custom_tab_three();
        });
        function wcz_account_custom_tab_three() {
            if ( $( '#customize-control-wcz-accendpoint-three input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-three-tab' ).show();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-three-title' ).show();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-three-pageid' ).show();
                $( '#customize-control-wcz-accendpoint-three .customize-control-description' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-three-tab' ).hide();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-three-title' ).hide();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-three-pageid' ).hide();
                $( '#customize-control-wcz-accendpoint-three .customize-control-description' ).hide();
            }
        }

        // New Custom Tab - Four
        wcz_account_custom_tab_four();
        $( '#customize-control-wcz-accendpoint-four input[type=checkbox]' ).on( 'change', function() {
            wcz_account_custom_tab_four();
        });
        function wcz_account_custom_tab_four() {
            if ( $( '#customize-control-wcz-accendpoint-four input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-four-tab' ).show();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-four-title' ).show();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-four-pageid' ).show();
                $( '#customize-control-wcz-accendpoint-four .customize-control-description' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-four-tab' ).hide();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-four-title' ).hide();
                $( '#sub-accordion-section-wcz-panel-account #customize-control-wcz-account-tab-four-pageid' ).hide();
                $( '#customize-control-wcz-accendpoint-four .customize-control-description' ).hide();
            }
        }

        // Show / Hide Add Custom Empty Cart Text
        wcz_empty_cart_txt();
        $( '#customize-control-wcz-cart-add-custom-text input[type=checkbox]' ).on( 'change', function() {
            wcz_empty_cart_txt();
        });
        function wcz_empty_cart_txt() {
            if ( $( '#customize-control-wcz-cart-add-custom-text input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-cart #customize-control-wcz-cart-empty-txt' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-cart #customize-control-wcz-cart-empty-txt' ).hide();
            }
        }
        
        // Show / Hide Edit Coupon Text
        wcz_checkout_edit_coupon();
        $( '#customize-control-wcz-checkout-edit-coupon-txt input[type=checkbox]' ).on( 'change', function() {
            wcz_checkout_edit_coupon();
        });
        function wcz_checkout_edit_coupon() {
            if ( $( '#customize-control-wcz-checkout-edit-coupon-txt input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-checkout #customize-control-wcz-checkout-coupon-text' ).show();
                $( '#sub-accordion-section-wcz-panel-checkout #customize-control-wcz-checkout-coupon-link-text' ).show();
                $( '#sub-accordion-section-wcz-panel-checkout #customize-control-wcz-checkout-coupon-instruction-text' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-checkout #customize-control-wcz-checkout-coupon-text' ).hide();
                $( '#sub-accordion-section-wcz-panel-checkout #customize-control-wcz-checkout-coupon-link-text' ).hide();
                $( '#sub-accordion-section-wcz-panel-checkout #customize-control-wcz-checkout-coupon-instruction-text' ).hide();
            }
        }
        // Show / Hide Edit Order Notes Text
        wcz_checkout_edit_ordernotes();
        $( '#customize-control-wcz-checkout-edit-ordernotes-txt input[type=checkbox]' ).on( 'change', function() {
            wcz_checkout_edit_ordernotes();
        });
        function wcz_checkout_edit_ordernotes() {
            if ( $( '#customize-control-wcz-checkout-edit-ordernotes-txt input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-checkout #customize-control-wcz-checkout-ordernotes-label' ).show();
                $( '#sub-accordion-section-wcz-panel-checkout #customize-control-wcz-checkout-ordernotes-placeholder' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-checkout #customize-control-wcz-checkout-ordernotes-label' ).hide();
                $( '#sub-accordion-section-wcz-panel-checkout #customize-control-wcz-checkout-ordernotes-placeholder' ).hide();
            }
        }

        // Show / Hide Empty Cart Page Text
        wcz_checkout_po_txt();
        $( '#customize-control-wcz-checkout-add-po-txt input[type=checkbox]' ).on( 'change', function() {
            wcz_checkout_po_txt();
        });
        function wcz_checkout_po_txt() {
            if ( $( '#customize-control-wcz-checkout-add-po-txt input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-checkout #customize-control-wcz-checkout-po-txt' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-checkout #customize-control-wcz-checkout-po-txt' ).hide();
            }
        }

        // Show / Hide Empty Cart Page Text
        wcz_checkout_secureimage();
        $( '#customize-control-wcz-checkout-add-img input[type=checkbox]' ).on( 'change', function() {
            wcz_checkout_secureimage();
        });
        function wcz_checkout_secureimage() {
            if ( $( '#customize-control-wcz-checkout-add-img input[type=checkbox]' ).is( ':checked' ) ) {
                $( '#sub-accordion-section-wcz-panel-checkout #customize-control-wcz-checkout-img' ).show();
                $( '#sub-accordion-section-wcz-panel-checkout #customize-control-wcz-checkout-img-center' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-checkout #customize-control-wcz-checkout-img' ).hide();
                $( '#sub-accordion-section-wcz-panel-checkout #customize-control-wcz-checkout-img-center' ).hide();
            }
        }

        // Radio Options for Product Page
        wcz_cart_crosssellssection_tab();
        $( '#customize-control-wcz-wccart-recomm input[type=radio]' ).on( 'change', function() {
            wcz_cart_crosssellssection_tab();
        });
        function wcz_cart_crosssellssection_tab() {
            if ( $( '#customize-control-wcz-wccart-recomm input[type=radio]:checked' ).val() == 'wcz-wccart-recomm-edit' ) {
                $( '#sub-accordion-section-wcz-panel-cart #customize-control-wcz-wccart-recomm-title' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-cart #customize-control-wcz-wccart-recomm-title' ).hide();
            }
        }
        // Radio Options for Product Page
        wcz_cart_totalssection_tab();
        $( '#customize-control-wcz-wccart-totals input[type=radio]' ).on( 'change', function() {
            wcz_cart_totalssection_tab();
        });
        function wcz_cart_totalssection_tab() {
            if ( $( '#customize-control-wcz-wccart-totals input[type=radio]:checked' ).val() == 'wcz-wccart-totals-edit' ) {
                $( '#sub-accordion-section-wcz-panel-cart #customize-control-wcz-wccart-totals-title' ).show();
            } else {
                $( '#sub-accordion-section-wcz-panel-cart #customize-control-wcz-wccart-totals-title' ).hide();
            }
        }

    });
} )( jQuery );

(function ( api ) {

    var api_inter_link = wp.customize;
    api_inter_link.bind('ready', function() {
        jQuery(['control', 'section', 'panel']).each(function(i, type) {
            jQuery('a[rel="wcz-'+type+'"]').click(function(e) {
                e.preventDefault();
                var id = jQuery(this).attr('href').replace('#', '');
                if(api_inter_link[type].has(id)) {
                    api_inter_link[type].instance(id).focus();
                }
            });
        });
    });

    // Auto Change to Page in Customizer
    const pageUrls = Object.entries( page_urls );

    for ( const [page, pageurl] of pageUrls ) {

        api.section( `wcz-panel-${page}`, function( section ) {
            var previousUrl, clearPreviousUrl, previewUrlValue;
            previewUrlValue = api.previewer.previewUrl;
            clearPreviousUrl = function() {
                previousUrl = null;
            };
     
            section.expanded.bind( function( isExpanded ) {
                var url;
                if ( isExpanded ) {
                    url = pageurl;
                    previousUrl = previewUrlValue.get();
                    previewUrlValue.set( url );
                    previewUrlValue.bind( clearPreviousUrl );
                }
            } );
        } );

    }
    
} ( wp.customize ) );
