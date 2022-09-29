(function () {
    'use strict';
    let shortcodes = woo_thank_you_page_params.shortcodes,
        border_right_rtl = woo_thank_you_page_params.is_rtl ?'left':'right',
        border_left_rtl = woo_thank_you_page_params.is_rtl ?'right':'left',
        languages = woo_thank_you_page_params.languages;
    let payment_method_html;
    if (!shortcodes['order_number']) {
        let order_id = jQuery('.wtyp-order-id').val();
        if (order_id) {
            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: woo_thank_you_page_params.url,
                data: {
                    action: 'woo_thank_you_page_get_available_shortcodes',
                    order_id: order_id
                },
                success: function (response) {
                    if (response && response.hasOwnProperty('shortcodes')) {
                        shortcodes = response.shortcodes;
                    }
                },
                error: function (err) {

                }
            })
        }
    }
    wp.customize.bind('preview-ready', function () {
        wtypc_flex_silder();
        jQuery(".search-product-parent").select2(wtypc_select2_params('Please fill in your product title','wtyp_search_product_parent'));
        jQuery(".search-category").select2(wtypc_select2_params('Please fill in your category title','wtyp_search_cate'));
        wp.customize.preview.bind('wtyp_update_url', function ( url) {
            wp.customize.preview.send('wtyp_update_url', url);
        });
        wp.customize.preview.bind('wtyp_get_url', function ( order_id) {
           if (!order_id && !jQuery('.woocommerce-thank-you-page-customize-preview').length){
               order_id = wp.customize('woo_thank_you_page_params[select_order]').get();
           }
           if (order_id){
               jQuery.ajax({
                   type: 'POST',
                   dataType: 'json',
                   url: woo_thank_you_page_params.url,
                   data: {
                       action: 'wtypc_get_order_received_url',
                       wtypc_order_id: order_id,
                   },
                   beforeSend:function(){
                       jQuery( 'body' ).addClass( 'wp-customizer-unloading' );
                   },
                   success: function (response) {
                       if (response && response.status === 'success') {
                           wp.customize.preview.send('wtyp_update_url', response.url);
                       }else {
                           jQuery('body').removeClass('wp-customizer-unloading');
                       }
                   },
                   error: function (err) {
                       console.log(err);
                       jQuery( 'body' ).removeClass( 'wp-customizer-unloading' );
                   }
               });
           }
        });
        wp.customize.preview.bind('wtyp_shortcut_edit_item_from_section', function (item) {
            jQuery('.woocommerce-thank-you-page-wp-editor-overlay').trigger('click');
            jQuery('.woocommerce-thank-you-page-products-modal-overlay').trigger('click');
            jQuery('.' + item).trigger('click');
        });
        wp.customize.preview.bind('wtyp_shortcut_edit_text_editor_from_section', function (position) {
            jQuery('.woocommerce-thank-you-page-wp-editor-overlay').trigger('click');
            jQuery('.woocommerce-thank-you-page-products-modal-overlay').trigger('click');
            let item = jQuery('.woocommerce-thank-you-page-text-editor').eq(position);
            if (item.length) {
                item.find('.woocommerce-thank-you-page-text-editor-edit:not(.woocommerce-thank-you-page-text-editor-edit-language)').trigger('click');
                let top = item.offset().top;
                if (top > 200) {
                    top = top - 200;
                }
                jQuery('html, body').animate({scrollTop: top}, 'slow');
            }
        });
        wp.customize.preview.bind('wtyp_shortcut_edit_products_from_section', function (position) {
            jQuery('.woocommerce-thank-you-page-wp-editor-overlay').trigger('click');
            jQuery('.woocommerce-thank-you-page-products-modal-overlay').trigger('click');
            let item = jQuery('.woocommerce-thank-you-page-products').eq(position);
            if (item.length) {
                item.find('.woocommerce-thank-you-page-products-edit').trigger('click');
                let top = item.offset().top;
                if (top > 200) {
                    top = top - 200;
                }
                jQuery('html, body').animate({scrollTop: top}, 'slow');
            }
        });
        wp.customize.preview.bind('wtyp_focus_on_editing_item', function (message) {
            let item = jQuery('#' + message);
            if (item.length) {
                let top = item.offset().top;
                if (top > 200) {
                    top = top - 200;
                }
                jQuery('html, body').animate({scrollTop: top}, 'slow');
                let count = 6;
                let setHighLight = setInterval(function () {
                    if (count == 0) {
                        clearInterval(setHighLight);
                        item.css({'outline': 'none'});
                    } else {
                        if (count % 2 == 1) {
                            item.css({'outline': 'none'});
                        } else {
                            item.css({'outline': '1px solid rgba(1,1,1,1)'});
                        }
                        count--;
                    }
                }, 500)
            }
        });
        wp.customize.preview.bind('wtyp_shortcut_to_available_shortcodes', function () {
            if (jQuery('.woocommerce-thank-you-page-available-shortcodes-container').hasClass('woocommerce-thank-you-page-hidden')) {
                jQuery('.woocommerce-thank-you-page-available-shortcodes-container').removeClass('woocommerce-thank-you-page-hidden');
            } else {
                jQuery('.woocommerce-thank-you-page-available-shortcodes-container').addClass('woocommerce-thank-you-page-hidden');
            }
        });
        wp.customize.preview.bind('wtyp_update_google_map_address', function (addr) {
            let zoom_level = parseInt(wp.customize('woo_thank_you_page_params[google_map_zoom_level]').get());
            let selected_style = wp.customize('woo_thank_you_page_params[google_map_style]').get();
            let map_style;
            if (selected_style === 'custom') {
                map_style = wp.customize('woo_thank_you_page_params[google_map_custom_style]').get();
            } else if (selected_style !== 'default') {
                map_style = woo_thank_you_page_params['google_map_styles'][selected_style];
            }
            initGoogleMap(zoom_level, addr, map_style);
        });
        wp.customize.preview.bind('wtyp_update_bing_map_address', function (addr) {
            let zoom_level = parseInt(wp.customize('woo_thank_you_page_params[bing_map_zoom_level]').get());
            let map_view = wp.customize('woo_thank_you_page_params[bing_map_view]').get();
            let map_nav_bar = wp.customize('woo_thank_you_page_params[bing_map_navbarmode]').get();
            initBingMap(zoom_level, addr, map_view, map_nav_bar);
        });
        wp.customize.preview.bind('active', function () {
            jQuery(document).on('click', '.woocommerce-thank-you-page-edit-item-shortcut', function () {
                wp.customize.preview.send('wtyp_shortcut_edit', jQuery(this).data()['edit_section']);
            });
            jQuery(document).on('click','.woocommerce-thank-you-page-available-shortcodes-item-copy', function () {
                jQuery(this).parent().find('input').select();
                document.execCommand("copy");
            });
            jQuery(document).on('focus','.woocommerce-thank-you-page-coupon__code-code', function () {
                jQuery(this).select();
            });
            jQuery(document).on('click','.woocommerce-thank-you-page-available-shortcodes-shortcut', function () {
                jQuery('.woocommerce-thank-you-page-available-shortcodes-container').removeClass('woocommerce-thank-you-page-hidden');
            });
            jQuery(document).on('click', '.woocommerce-thank-you-page-available-shortcodes-items-close',function () {
                jQuery('.woocommerce-thank-you-page-available-shortcodes-overlay').trigger('click');
            });
            jQuery(document).on('click','.woocommerce-thank-you-page-available-shortcodes-overlay', function () {
                jQuery('.woocommerce-thank-you-page-available-shortcodes-container').addClass('woocommerce-thank-you-page-hidden');
            });
            jQuery(document).on('click', '.woocommerce-thank-you-page-products-edit', function () {
                wtypc_disable_scroll();
                jQuery('.woocommerce-thank-you-page-products-modal-container').addClass('woocommerce-thank-you-page-products-active');
                jQuery('.woocommerce-thank-you-page-products-modal-overlay').addClass('woocommerce-thank-you-page-products-active');
                let editing = jQuery(this).parent();
                editing.addClass('woocommerce-thank-you-page-products-editing');
                let data = editing.find('.woocommerce-thank-you-page-products-content').data();
                /*product ids*/
                let product_ids = (data['product_ids']);
                let product_ids_field = jQuery('#specific-products-product-ids');
                product_ids_field.val(null).trigger('change');
                let product_ids_values = [];
                for (let i in product_ids) {
                    if (product_ids.hasOwnProperty(i)) {
                        product_ids_values.push(i);
                        if (product_ids_field.find("option[value='" + i + "']").length) {
                            product_ids_field.val(i).trigger('change');
                        } else {
                            let product_ids_data = {
                                id: i,
                                text: product_ids[i]
                            };
                            let newOption = new Option(product_ids_data.text, product_ids_data.id, false, false);
                            product_ids_field.append(newOption).trigger('change');
                        }
                    }
                }
                product_ids_field.val(product_ids_values).trigger('change');
                
                /*excluded product ids*/
                let excluded_product_ids = (data['excluded_product_ids']);
                let excluded_product_ids_field = jQuery('#specific-products-excluded-product-ids');
                excluded_product_ids_field.val(null).trigger('change');
                let excluded_product_ids_values = [];
                for (let i in excluded_product_ids) {
                    if (excluded_product_ids.hasOwnProperty(i)) {
                        excluded_product_ids_values.push(i);
                        if (excluded_product_ids_field.find("option[value='" + i + "']").length) {
                            excluded_product_ids_field.val(i).trigger('change');
                        } else {
                            let excluded_product_ids_data = {
                                id: i,
                                text: excluded_product_ids[i]
                            };
                            let newOption = new Option(excluded_product_ids_data.text, excluded_product_ids_data.id, false, false);
                            excluded_product_ids_field.append(newOption).trigger('change');
                        }
                    }
                }
                excluded_product_ids_field.val(excluded_product_ids_values).trigger('change');

                /*product ids*/
                let product_categories = (data['product_categories']);
                let product_categories_field = jQuery('#specific-products-product-categories');
                product_categories_field.val(null).trigger('change');
                let product_categories_values = [];
                for (let i in product_categories) {
                    if (product_categories.hasOwnProperty(i)) {
                        product_categories_values.push(i);
                        if (product_categories_field.find("option[value='" + i + "']").length) {
                            product_categories_field.val(i).trigger('change');
                        } else {
                            let product_categories_data = {
                                id: i,
                                text: product_categories[i]
                            };
                            let newOption = new Option(product_categories_data.text, product_categories_data.id, false, false);
                            product_categories_field.append(newOption).trigger('change');
                        }
                    }
                }
                product_categories_field.val(product_categories_values).trigger('change');

                /*excluded product ids*/
                let excluded_product_categories = (data['excluded_product_categories']);
                let excluded_product_categories_field = jQuery('#specific-products-excluded-product-categories');
                excluded_product_categories_field.val(null).trigger('change');
                let excluded_product_categories_values = [];
                for (let i in excluded_product_categories) {
                    if (excluded_product_categories.hasOwnProperty(i)) {
                        excluded_product_categories_values.push(i);
                        if (excluded_product_categories_field.find("option[value='" + i + "']").length) {
                            excluded_product_categories_field.val(i).trigger('change');
                        } else {
                            let excluded_product_categories_data = {
                                id: i,
                                text: excluded_product_categories[i]
                            };
                            let newOption = new Option(excluded_product_categories_data.text, excluded_product_categories_data.id, false, false);
                            excluded_product_categories_field.append(newOption).trigger('change');
                        }
                    }
                }
                excluded_product_categories_field.val(excluded_product_categories_values).trigger('change');

                /*others*/
                jQuery('#specific-products-order-by').val(data['order_by']);
                jQuery('#specific-products-visibility').val(data['visibility']);
                jQuery('#specific-products-order').val(data['order']);
                jQuery('#specific-products-product-options').val(data['product_options']);
                jQuery('#specific-products-columns').val(data['wtypc_columns']);
                jQuery('#specific-products-limit').val(data['limit']);
                jQuery('#specific-products-slider-move').val(data['slider_move']);
                jQuery('#specific-products-slider-slideshow-speed').val(data['slider_slideshow_speed']);
                if (data['slider_loop'] === '1') {
                    jQuery('#specific-products-slider-loop').prop('checked', true);
                } else {
                    jQuery('#specific-products-slider-loop').prop('checked', false);
                }
                if (data['slider_slideshow'] === '1') {
                    jQuery('#specific-products-slider-slideshow').prop('checked', true);
                } else {
                    jQuery('#specific-products-slider-slideshow').prop('checked', false);
                }
                if (data['slider_pause_on_hover'] === '1') {
                    jQuery('#specific-products-slider-pause-on-hover').prop('checked', true);
                } else {
                    jQuery('#specific-products-slider-pause-on-hover').prop('checked', false);
                }
            });
            jQuery(document).on('click','.woocommerce-thank-you-page-products-modal-items-close, .woocommerce-thank-you-page-products-modal-cancel', function () {
                jQuery('.woocommerce-thank-you-page-products-modal-overlay').trigger('click');
            });
            jQuery(document).on('click','.woocommerce-thank-you-page-products-modal-overlay', function () {
                wtypc_enable_scroll();
                jQuery('.woocommerce-thank-you-page-products-modal-container').removeClass('woocommerce-thank-you-page-products-active');
                jQuery('.woocommerce-thank-you-page-products-modal-overlay').removeClass('woocommerce-thank-you-page-products-active');
                jQuery('.woocommerce-thank-you-page-products').removeClass('woocommerce-thank-you-page-products-editing');
            });
            jQuery(document).on('click','.woocommerce-thank-you-page-products-modal-save', function () {
                jQuery('.woocommerce-thank-you-page-preview-processing-overlay').show();
                let order_by = jQuery('#specific-products-order-by').val(),
                    visibility = jQuery('#specific-products-visibility').val(),
                    order = jQuery('#specific-products-order').val(),
                    columns = jQuery('#specific-products-columns').val(),
                    limit = jQuery('#specific-products-limit').val(),
                    product_options = jQuery('#specific-products-product-options').val(),
                    slider_loop = jQuery('#specific-products-slider-loop').prop('checked') ? '1' : '',
                    slider_move = jQuery('#specific-products-slider-move').val(),
                    slider_slideshow = jQuery('#specific-products-slider-slideshow').prop('checked') ? '1' : '',
                    slider_slideshow_speed = jQuery('#specific-products-slider-slideshow-speed').val(),
                    slider_pause_on_hover = jQuery('#specific-products-slider-pause-on-hover').prop('checked') ? '1' : '',
                    editing = jQuery('.woocommerce-thank-you-page-products-editing');
                let products = {
                    'product_ids': [],
                    'excluded_product_ids': [],
                    'product_categories': [],
                    'excluded_product_categories': [],
                    'order_by': order_by,
                    'visibility': visibility,
                    'order': order,
                    'columns': columns,
                    'limit': limit,
                    'product_options': product_options,
                    'slider_loop': slider_loop,
                    'slider_move': slider_move,
                    'slider_slideshow': slider_slideshow,
                    'slider_slideshow_speed': slider_slideshow_speed,
                    'slider_pause_on_hover': slider_pause_on_hover,
                };
                let order_id = wp.customize('woo_thank_you_page_params[select_order]').get();

                let product_ids_data = jQuery('#specific-products-product-ids').select2('data');
                let product_ids = {};
                for (let i in product_ids_data) {
                    if (product_ids_data.hasOwnProperty(i)) {
                        product_ids[product_ids_data[i]['id']] = product_ids_data[i]['text'];
                        products['product_ids'].push(product_ids_data[i]['id']);
                    }
                }

                let excluded_product_ids_data = jQuery('#specific-products-excluded-product-ids').select2('data');
                let excluded_product_ids = {};
                for (let i in excluded_product_ids_data) {
                    if (excluded_product_ids_data.hasOwnProperty(i)) {
                        excluded_product_ids[excluded_product_ids_data[i]['id']] = excluded_product_ids_data[i]['text'];
                        products['excluded_product_ids'].push(excluded_product_ids_data[i]['id']);
                    }
                }

                let product_categories_data = jQuery('#specific-products-product-categories').select2('data');
                let product_categories = {};
                for (let i in product_categories_data) {
                    if (product_categories_data.hasOwnProperty(i)) {
                        product_categories[product_categories_data[i]['id']] = product_categories_data[i]['text'];
                        products['product_categories'].push(product_categories_data[i]['id']);
                    }
                }

                let excluded_product_categories_data = jQuery('#specific-products-excluded-product-categories').select2('data');
                let excluded_product_categories = {};
                for (let i in excluded_product_categories_data) {
                    if (excluded_product_categories_data.hasOwnProperty(i)) {
                        excluded_product_categories[excluded_product_categories_data[i]['id']] = excluded_product_categories_data[i]['text'];
                        products['excluded_product_categories'].push(excluded_product_categories_data[i]['id']);
                    }
                }
                jQuery.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: woo_thank_you_page_params.url,
                    data: {
                        action: 'woo_thank_you_page_get_products_shortcode',
                        order_id: order_id,
                        product_ids: JSON.stringify(product_ids),
                        excluded_product_ids: JSON.stringify(excluded_product_ids),
                        product_categories: JSON.stringify(product_categories),
                        excluded_product_categories: JSON.stringify(excluded_product_categories),
                        order_by: order_by,
                        visibility: visibility,
                        order: order,
                        columns: columns,
                        limit: limit,
                        product_options: product_options,
                        slider_loop: slider_loop,
                        slider_move: slider_move,
                        slider_slideshow: slider_slideshow,
                        slider_slideshow_speed: slider_slideshow_speed,
                        slider_pause_on_hover: slider_pause_on_hover,
                    },
                    success: function (response) {
                        jQuery('.woocommerce-thank-you-page-preview-processing-overlay').hide();
                        let products_old = JSON.parse(wp.customize('woo_thank_you_page_params[products]').get());
                        let i = jQuery('.woocommerce-thank-you-page-products').index(editing);
                        if (i > -1) {
                            products_old[i] = products;
                            wp.customize.preview.send('wtyp_update_products', JSON.stringify(products_old));
                        }
                        editing.html(response.html);
                        wtypc_flex_silder();
                    },
                    error: function (err) {
                        handleOverlayProcessing('hide');
                    }
                });
                jQuery('.woocommerce-thank-you-page-products-modal-overlay').trigger('click');
            });
            jQuery(document).on('click', '.woocommerce-thank-you-page-text-editor-edit', function () {
                wtypc_disable_scroll();
                let item = jQuery(this).closest('.woocommerce-thank-you-page-text-editor');
                let index = jQuery('.woocommerce-thank-you-page-text-editor').index(item);
                let textEditor = JSON.parse(wp.customize('woo_thank_you_page_params[text_editor]').get());
                if (index > -1) {
                    jQuery('.woocommerce-thank-you-page-wp-editor-container').addClass('woocommerce-thank-you-page-active');
                    jQuery('.woocommerce-thank-you-page-wp-editor-overlay').addClass('woocommerce-thank-you-page-active');
                    item.addClass('woocommerce-thank-you-page-editing');
                    jQuery(this).addClass('woocommerce-thank-you-page-text-editor-edit-editing');
                    let content ='',index_t = 0;
                    if (typeof textEditor[index] ==='string'){
                        content= wtypc_b64DecodeUnicode(textEditor[index]);
                    }else {
                        if (jQuery(this).data('wtypc_language')) {
                            index_t += '_' + jQuery(this).data('wtypc_language');
                        }
                        content= wtypc_b64DecodeUnicode(textEditor[index][index_t] || textEditor[index][0]);
                    }
                    if (tinyMCE.get('woocommerce-thank-you-page-wp-editor')) {
                        tinyMCE.get('woocommerce-thank-you-page-wp-editor').setContent(content);
                    } else {
                        jQuery('#woocommerce-thank-you-page-wp-editor').val(content);
                    }
                }
            });
            jQuery(document).on('click','.woocommerce-thank-you-page-wp-editor-overlay', function () {
                jQuery('.woocommerce-thank-you-page-wp-editor-container').removeClass('woocommerce-thank-you-page-active');
                jQuery('.woocommerce-thank-you-page-wp-editor-overlay').removeClass('woocommerce-thank-you-page-active');
                jQuery('.woocommerce-thank-you-page-text-editor').removeClass('woocommerce-thank-you-page-editing');
                jQuery('.woocommerce-thank-you-page-text-editor-edit-editing').removeClass('woocommerce-thank-you-page-text-editor-edit-editing');
                if (tinyMCE.get('woocommerce-thank-you-page-wp-editor')) {
                    tinyMCE.get('woocommerce-thank-you-page-wp-editor').setContent('');
                } else {
                    jQuery('#woocommerce-thank-you-page-wp-editor').val('');
                }
                wtypc_enable_scroll();
            });
            jQuery(document).on('click','.woocommerce-thank-you-page-wp-editor-cancel', function () {
                jQuery('.woocommerce-thank-you-page-wp-editor-overlay').trigger('click');
            });
            jQuery(document).on('click','.woocommerce-thank-you-page-wp-editor-save', function () {
                jQuery('.woocommerce-thank-you-page-preview-processing-overlay').show();
                let content;
                if (tinyMCE.get('woocommerce-thank-you-page-wp-editor')) {
                    content = tinyMCE.get('woocommerce-thank-you-page-wp-editor').getContent();
                } else {
                    content = jQuery('#woocommerce-thank-you-page-wp-editor').val();
                }
                jQuery.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: woo_thank_you_page_params.url,
                    data: {
                        action: 'woo_thank_you_page_get_text_editor_content',
                        shortcodes: shortcodes,
                        content: content,
                    },
                    success: function (response) {
                        jQuery('.woocommerce-thank-you-page-preview-processing-overlay').hide();
                        let editing = jQuery('.woocommerce-thank-you-page-editing');
                        let index = jQuery('.woocommerce-thank-you-page-text-editor').index(editing);
                        let content_t='',index_t = 0,textEditor = JSON.parse(wp.customize('woo_thank_you_page_params[text_editor]').get());
                        if (index > -1) {
                            if (typeof textEditor[index] ==='string'){
                                content_t=textEditor[index];
                                textEditor[index]={};
                                textEditor[index][0]=content_t;
                            }
                            if (editing.find('.woocommerce-thank-you-page-text-editor-edit-editing').data('wtypc_language')) {
                                index_t +='_'+editing.find('.woocommerce-thank-you-page-text-editor-edit-editing').data('wtypc_language');
                            }
                            textEditor[index][index_t] = wtypc_b64EncodeUnicode(content);
                            wp.customize.preview.send('wtyp_update_text_editor', JSON.stringify(textEditor));
                        }
                        editing.find('.woocommerce-thank-you-page-text-editor-content').html(response.html)
                        jQuery('.woocommerce-thank-you-page-wp-editor-overlay').trigger('click');
                    },
                    error: function (err) {
                        handleOverlayProcessing('hide');
                        alert('Cannot save content.')
                    }
                })
            });
            wtypc_init_map();
        });
        payment_method_html = jQuery('#woocommerce-thank-you-page-payment-method-html-hold').html();
    });
    wp.customize('woo_thank_you_page_params[select_order]', function (value) {
        value.bind(function (newval) {
            wp.customize.preview.send('wtyp_get_url', newval);
        });
    });
    wp.customize('woo_thank_you_page_params[blocks]', function (value) {
        value.bind(function (newval) {
            handleOverlayProcessing('show');
            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: woo_thank_you_page_params.url,
                data: wtypc_get_data_layout(newval),
                success: function (response) {
                    handleOverlayProcessing('hide');
                    if (response.hasOwnProperty('blocks')) {
                        jQuery('.woocommerce-thank-you-page-container').html(response.blocks);
                        if (response.hasOwnProperty('shortcodes')) {
                            shortcodes = response.shortcodes;
                        }
                        wp.customize.preview.send('wtyp_open_latest_added_item', '');
                    }
                    wtypc_init_map();
                    wtypc_flex_silder();
                },
                error: function (err) {
                    handleOverlayProcessing('hide');
                    console.log(err);
                }
            });
        });
    });

    /*thank you message*/
    addPreviewControl('thank_you_message_color', '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail', 'color');
    addPreviewControl('thank_you_message_padding', '.woocommerce-thank-you-page-thank_you_message__container', 'padding', 'px');
    addPreviewControl('thank_you_message_text_align', '.woocommerce-thank-you-page-thank_you_message__container', 'text-align');
    addPreviewControl(
        'thank_you_message_header_font_size',
        '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail .woocommerce-thank-you-page-thank_you_message-header',
        'font-size', 'px');
    addPreviewControl(
        'thank_you_message_message_font_size',
        '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail .woocommerce-thank-you-page-thank_you_message-message',
        'font-size', 'px');
    wp.customize('woo_thank_you_page_params[thank_you_message_header]', function (value) {
        value.bind(function (newval) {
            if (shortcodes['order_number']) {
                for (let i in shortcodes) {
                    let reg_string = '{' + i + '}';
                    let reg = new RegExp(reg_string, 'g');
                    newval = newval.replace(reg, shortcodes[i]);
                }
            }
            jQuery('.woocommerce-thank-you-page-thank_you_message-header>div').html(newval.replace(/\n/g, '<\/br>'));
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_thank_you_page_params[thank_you_message_header_' + v + ']', function (value) {
            value.bind(function (newval) {
                if (shortcodes['order_number']) {
                    for (let i in shortcodes) {
                        let reg_string = '{' + i + '}';
                        let reg = new RegExp(reg_string, 'g');
                        newval = newval.replace(reg, shortcodes[i]);
                    }
                }
                jQuery('.woocommerce-thank-you-page-thank_you_message-header>div').html(newval.replace(/\n/g, '<\/br>'));
            });
        });
    });
    wp.customize('woo_thank_you_page_params[thank_you_message_message]', function (value) {
        value.bind(function (newval) {
            if (shortcodes['order_number']) {
                for (let i in shortcodes) {
                    let reg_string = '{' + i + '}';
                    let reg = new RegExp(reg_string, 'g');
                    newval = newval.replace(reg, shortcodes[i]);
                }
            }
            jQuery('.woocommerce-thank-you-page-thank_you_message-message>div').html(newval.replace(/\n/g, '<\/br>'));
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_thank_you_page_params[thank_you_message_message_' + v + ']', function (value) {
            value.bind(function (newval) {
                if (shortcodes['order_number']) {
                    for (let i in shortcodes) {
                        let reg_string = '{' + i + '}';
                        let reg = new RegExp(reg_string, 'g');
                        newval = newval.replace(reg, shortcodes[i]);
                    }
                }
                jQuery('.woocommerce-thank-you-page-thank_you_message-message>div').html(newval.replace(/\n/g, '<\/br>'));
            });
        });
    });

    /*coupon*/
    addPreviewControl('coupon_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container', 'text-align');
    addPreviewControl('coupon_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container', 'padding', 'px');
    addPreviewControl('coupon_message_color', 
        '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__message', 'color');
    addPreviewControl('coupon_message_font_size', 
        '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__message', 'font-size', 'px');
    addPreviewControl('coupon_code_color', 
        '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'color');
    addPreviewControl('coupon_code_bg_color', 
        '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code',
        'background-color');
    addPreviewControl('coupon_code_border_width', 
        '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 
        'border-width', 'px');
    addPreviewControl('coupon_code_border_style', 
        '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 
        'border-style');
    addPreviewControl('coupon_code_border_color', 
        '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 
        'border-color');
    addPreviewControl('coupon_scissors_color',
        '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-wrap:before',
        'color');
    wp.customize('woo_thank_you_page_params[coupon_email_enable]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                jQuery('.woocommerce-thank-you-page-coupon__code-email').removeClass('woocommerce-thank-you-page-hidden');
            } else {
                jQuery('.woocommerce-thank-you-page-coupon__code-email').addClass('woocommerce-thank-you-page-hidden');
            }
        })
    });
    wp.customize('woo_thank_you_page_params[coupon_message]', function (value) {
        value.bind(function (newval) {
            if (shortcodes['order_number']) {
                for (let i in shortcodes) {
                    let reg_string = '{' + i + '}';
                    let reg = new RegExp(reg_string, 'g');
                    newval = newval.replace(reg, shortcodes[i]);
                }
            }
            let coupon_code = jQuery('.woocommerce-thank-you-page-coupon-code').val(),
                coupon_amount = jQuery('.woocommerce-thank-you-page-coupon-amount').val(),
                coupon_date_expires = jQuery('.woocommerce-thank-you-page-coupon-date-expires').val(),
                last_valid_date = jQuery('.woocommerce-thank-you-page-last-valid-date').val();
            newval = newval.replace(/{coupon_code}/g, coupon_code);
            newval = newval.replace(/{coupon_amount}/g, coupon_amount);
            newval = newval.replace(/{coupon_date_expires}/g, coupon_date_expires);
            newval = newval.replace(/{last_valid_date}/g, last_valid_date);
            jQuery('.woocommerce-thank-you-page-coupon-message>div').html(newval.replace(/\n/g, '<\/br>'));
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_thank_you_page_params[coupon_message_' + v + ']', function (value) {
            value.bind(function (newval) {
                if (shortcodes['order_number']) {
                    for (let i in shortcodes) {
                        let reg_string = '{' + i + '}';
                        let reg = new RegExp(reg_string, 'g');
                        newval = newval.replace(reg, shortcodes[i]);
                    }
                }
                let coupon_code = jQuery('.woocommerce-thank-you-page-coupon-code').val(),
                    coupon_amount = jQuery('.woocommerce-thank-you-page-coupon-amount').val(),
                    coupon_date_expires = jQuery('.woocommerce-thank-you-page-coupon-date-expires').val(),
                    last_valid_date = jQuery('.woocommerce-thank-you-page-last-valid-date').val();
                newval = newval.replace(/{coupon_code}/g, coupon_code);
                newval = newval.replace(/{coupon_amount}/g, coupon_amount);
                newval = newval.replace(/{coupon_date_expires}/g, coupon_date_expires);
                newval = newval.replace(/{last_valid_date}/g, last_valid_date);
                jQuery('.woocommerce-thank-you-page-coupon-message>div').html(newval.replace(/\n/g, '<\/br>'));
            });
        });
    });

    /*order confirmation*/
    addPreviewControl('order_confirmation_bg', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'background-color');
    addPreviewControl('order_confirmation_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'padding', 'px');
    addPreviewControl('order_confirmation_border_radius', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-radius', 'px');
    addPreviewControl('order_confirmation_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-width', 'px');
    addPreviewControl('order_confirmation_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-style');
    addPreviewControl('order_confirmation_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-color');

    addPreviewControl('order_confirmation_vertical_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'border-'+border_right_rtl+'-width', 'px');
    addPreviewControl('order_confirmation_vertical_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'border-'+border_right_rtl+'-style');
    addPreviewControl('order_confirmation_vertical_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'border-'+border_right_rtl+'-color');
    addPreviewControl('order_confirmation_horizontal_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:first-child) .woocommerce-thank-you-page-order_confirmation-title div,.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:first-child) .woocommerce-thank-you-page-order_confirmation-value div', 'border-top-width', 'px');
    addPreviewControl('order_confirmation_horizontal_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:first-child) .woocommerce-thank-you-page-order_confirmation-title div,.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:first-child) .woocommerce-thank-you-page-order_confirmation-value div', 'border-top-style');
    addPreviewControl('order_confirmation_horizontal_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:first-child) .woocommerce-thank-you-page-order_confirmation-title div,.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:first-child) .woocommerce-thank-you-page-order_confirmation-value div', 'border-top-color');

    wp.customize('woo_thank_you_page_params[order_confirmation_header]', function (value) {
        value.bind(function (newval) {
            if (shortcodes['order_number']) {
                for (let i in shortcodes) {
                    let reg_string = '{' + i + '}';
                    let reg = new RegExp(reg_string, 'g');
                    newval = newval.replace(reg, shortcodes[i]);
                }
            }
            jQuery('.woocommerce-thank-you-page-order_confirmation-header>div').html(newval.replace(/\n/g, '<\/br>'));
        })
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_thank_you_page_params[order_confirmation_header_' + v + ']', function (value) {
            value.bind(function (newval) {
                if (shortcodes['order_number']) {
                    for (let i in shortcodes) {
                        let reg_string = '{' + i + '}';
                        let reg = new RegExp(reg_string, 'g');
                        newval = newval.replace(reg, shortcodes[i]);
                    }
                }
                jQuery('.woocommerce-thank-you-page-order_confirmation-header>div').html(newval.replace(/\n/g, '<\/br>'));
            });
        });
    });
    addPreviewControl('order_confirmation_header_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'color');
    addPreviewControl('order_confirmation_header_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'background-color');
    addPreviewControl('order_confirmation_header_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'font-size', 'px');
    addPreviewControl('order_confirmation_header_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'text-align');

    wp.customize('woo_thank_you_page_params[order_confirmation_order_number_title]', function (value) {
        value.bind(function (newval) {
            jQuery('.woocommerce-thank-you-page-order_confirmation__order_number-title>div').html(newval);
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_thank_you_page_params[order_confirmation_order_number_title_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.woocommerce-thank-you-page-order_confirmation__order_number-title>div').html(newval);
            });
        });
    });
    wp.customize('woo_thank_you_page_params[order_confirmation_date_title]', function (value) {
        value.bind(function (newval) {
            jQuery('.woocommerce-thank-you-page-order_confirmation__order_date-title>div').html(newval);
        })
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_thank_you_page_params[order_confirmation_date_title_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.woocommerce-thank-you-page-order_confirmation__order_date-title>div').html(newval);
            });
        });
    });
    wp.customize('woo_thank_you_page_params[order_confirmation_order_total_title]', function (value) {
        value.bind(function (newval) {
            jQuery('.woocommerce-thank-you-page-order_confirmation__order_total-title>div').html(newval);
        })
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_thank_you_page_params[order_confirmation_order_total_title_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.woocommerce-thank-you-page-order_confirmation__order_total-title>div').html(newval);
            });
        });
    });
    wp.customize('woo_thank_you_page_params[order_confirmation_email_title]', function (value) {
        value.bind(function (newval) {
            jQuery('.woocommerce-thank-you-page-order_confirmation__order_email-title>div').html(newval);
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_thank_you_page_params[order_confirmation_email_title_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.woocommerce-thank-you-page-order_confirmation__order_email-title>div').html(newval);
            });
        });
    });
    wp.customize('woo_thank_you_page_params[order_confirmation_payment_title]', function (value) {
        value.bind(function (newval) {
            jQuery('.woocommerce-thank-you-page-order_confirmation__order_payment-title>div').html(newval);
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_thank_you_page_params[order_confirmation_payment_title_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.woocommerce-thank-you-page-order_confirmation__order_payment-title>div').html(newval);
            });
        });
    });
    addPreviewControl('order_confirmation_title_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'color');
    addPreviewControl('order_confirmation_title_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'background-color');
    addPreviewControl('order_confirmation_title_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'font-size', 'px');
    addPreviewControl('order_confirmation_title_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'text-align');

    addPreviewControl('order_confirmation_value_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'color');
    addPreviewControl('order_confirmation_value_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'background-color');
    addPreviewControl('order_confirmation_value_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'font-size', 'px');
    addPreviewControl('order_confirmation_value_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'text-align');

    /*customer information*/
    addPreviewControl('customer_information_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'color');
    addPreviewControl('customer_information_bg', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'background-color');
    addPreviewControl('customer_information_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'padding', 'px');
    addPreviewControl('customer_information_border_radius', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-radius', 'px');
    addPreviewControl('customer_information_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-width', 'px');
    addPreviewControl('customer_information_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-style');
    addPreviewControl('customer_information_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-color');

    addPreviewControl('customer_information_vertical_width', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address .woocommerce-thank-you-page-customer_information__shipping_address', 'border-'+border_left_rtl+'-width', 'px');
    addPreviewControl('customer_information_vertical_style', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address .woocommerce-thank-you-page-customer_information__shipping_address', 'border-'+border_left_rtl+'-style');
    addPreviewControl('customer_information_vertical_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address .woocommerce-thank-you-page-customer_information__shipping_address', 'border-'+border_left_rtl+'-color');

    addPreviewControl('customer_information_header_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', 'color');
    addPreviewControl('customer_information_header_bg_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', 'background-color');
    addPreviewControl('customer_information_header_font_size', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', 'font-size', 'px');
    addPreviewControl('customer_information_header_text_align', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', 'text-align');

    addPreviewControl('customer_information_address_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', 'color');
    addPreviewControl('customer_information_address_bg_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', 'background-color');
    addPreviewControl('customer_information_address_font_size', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', 'font-size', 'px');
    addPreviewControl('customer_information_address_text_align', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', 'text-align');
    wp.customize('woo_thank_you_page_params[customer_information_header]', function (value) {
        value.bind(function (newval) {
            if (shortcodes['order_number']) {
                for (let i in shortcodes) {
                    let reg_string = '{' + i + '}';
                    let reg = new RegExp(reg_string, 'g');
                    newval = newval.replace(reg, shortcodes[i]);
                }
            }
            jQuery('.woocommerce-thank-you-page-customer_information-header>div').html(newval.replace(/\n/g, '<\/br>'));
        })
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_thank_you_page_params[customer_information_header_' + v + ']', function (value) {
            value.bind(function (newval) {
                if (shortcodes['order_number']) {
                    for (let i in shortcodes) {
                        let reg_string = '{' + i + '}';
                        let reg = new RegExp(reg_string, 'g');
                        newval = newval.replace(reg, shortcodes[i]);
                    }
                }
                jQuery('.woocommerce-thank-you-page-customer_information-header>div').html(newval.replace(/\n/g, '<\/br>'));
            });
        });
    });
    wp.customize('woo_thank_you_page_params[customer_information_billing_title]', function (value) {
        value.bind(function (newval) {
            jQuery('.woocommerce-thank-you-page-customer_information__billing_address-header').html(newval);
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_thank_you_page_params[customer_information_billing_title_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.woocommerce-thank-you-page-customer_information__billing_address-header').html(newval);
            });
        });
    });
    wp.customize('woo_thank_you_page_params[customer_information_shipping_title]', function (value) {
        value.bind(function (newval) {
            jQuery('.woocommerce-thank-you-page-customer_information__shipping_address-header').html(newval);
        });
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_thank_you_page_params[customer_information_shipping_title_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.woocommerce-thank-you-page-customer_information__shipping_address-header').html(newval);
            });
        });
    });

    /*order details*/
    addPreviewControl('order_details_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'color');
    addPreviewControl('order_details_bg', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'background-color');
    addPreviewControl('order_details_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'padding', 'px');
    addPreviewControl('order_details_border_radius', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'border-radius', 'px');
    addPreviewControl('order_details_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'border-width', 'px');
    addPreviewControl('order_details_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'border-style');
    addPreviewControl('order_details_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'border-color');

    addPreviewControl('order_details_horizontal_width', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total .woocommerce-thank-you-page-order_details__detail:last-child,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_items', 'border-top-width', 'px');
    addPreviewControl('order_details_horizontal_style', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total .woocommerce-thank-you-page-order_details__detail:last-child,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_items', 'border-top-style');
    addPreviewControl('order_details_horizontal_color', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total .woocommerce-thank-you-page-order_details__detail:last-child,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_items', 'border-top-color');

    addPreviewControl('order_details_header_color', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', 'color');
    addPreviewControl('order_details_header_bg_color', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', 'background-color');
    addPreviewControl('order_details_header_font_size', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', 'font-size', 'px');
    addPreviewControl('order_details_header_text_align', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', 'text-align');

    addPreviewControl('order_details_product_image_width', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-title a.woocommerce-thank-you-page-order-item-image-wrap', 'width', 'px');
    wp.customize('woo_thank_you_page_params[order_details_header]', function (value) {
        value.bind(function (newval) {
            if (shortcodes['order_number']) {
                for (let i in shortcodes) {
                    let reg_string = '{' + i + '}';
                    let reg = new RegExp(reg_string, 'g');
                    newval = newval.replace(reg, shortcodes[i]);
                }
            }
            jQuery('.woocommerce-thank-you-page-order_details-header>div').html(newval.replace(/\n/g, '<\/br>'));
        })
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_thank_you_page_params[order_details_header_' + v + ']', function (value) {
            value.bind(function (newval) {
                if (shortcodes['order_number']) {
                    for (let i in shortcodes) {
                        let reg_string = '{' + i + '}';
                        let reg = new RegExp(reg_string, 'g');
                        newval = newval.replace(reg, shortcodes[i]);
                    }
                }
                jQuery('.woocommerce-thank-you-page-order_details-header>div').html(newval.replace(/\n/g, '<\/br>'));
            });
        });
    });
    wp.customize('woo_thank_you_page_params[order_details_product_title_text]', function (value) {
        value.bind(function (newval) {
            jQuery('.woocommerce-thank-you-page-order_details__header-title>div').html(newval);
        })
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_thank_you_page_params[order_details_product_title_text_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.woocommerce-thank-you-page-order_details__header-title>div').html(newval);
            });
        });
    });
    wp.customize('woo_thank_you_page_params[order_details_product_value_text]', function (value) {
        value.bind(function (newval) {
            jQuery('.woocommerce-thank-you-page-order_details__header-value>div').html(newval);
        })
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_thank_you_page_params[order_details_product_value_text_' + v + ']', function (value) {
            value.bind(function (newval) {
                jQuery('.woocommerce-thank-you-page-order_details__header-value>div').html(newval);
            });
        });
    });
    wp.customize('woo_thank_you_page_params[order_details_product_image]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                jQuery('.woocommerce-thank-you-page-order-item-image-container').addClass('woocommerce-thank-you-page-active');
            } else {
                jQuery('.woocommerce-thank-you-page-order-item-image-container').removeClass('woocommerce-thank-you-page-active');
            }
        })
    });

    /*social icons*/
    addPreviewControl('social_icons_header_color', '.woocommerce-thank-you-page-social_icons__container .woocommerce-thank-you-page-social_icons__header', 'color');
    addPreviewControl('social_icons_header_font_size', '.woocommerce-thank-you-page-social_icons__container .woocommerce-thank-you-page-social_icons__header', 'font-size', 'px');
    addPreviewControl('social_icons_align', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials', 'text-align');
    addPreviewControl('social_icons_space', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials li:not(:last-child)', 'margin-right', 'px');
    addPreviewControl('social_icons_size', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials li .wtyp-social-button span', 'font-size', 'px');
    addPreviewControl('social_icons_facebook_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-facebook-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_twitter_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-twitter-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_pinterest_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-pinterest-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_instagram_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-instagram-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_dribbble_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-dribbble-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_tumblr_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-tumblr-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_google_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-google-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_vkontakte_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-vkontakte-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_linkedin_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-linkedin-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_youtube_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-youtube-follow .wtyp-social-button span:before', 'color');
    wp.customize('woo_thank_you_page_params[social_icons_header]', function (value) {
        value.bind(function (newval) {
            if (shortcodes['order_number']) {
                for (let i in shortcodes) {
                    let reg_string = '{' + i + '}';
                    let reg = new RegExp(reg_string, 'g');
                    newval = newval.replace(reg, shortcodes[i]);
                }
            }
            jQuery('.woocommerce-thank-you-page-social_icons-header>div').html(newval.replace(/\n/g, '<\/br>'));
        })
    });
    jQuery.each(languages, function (k, v) {
        wp.customize('woo_thank_you_page_params[social_icons_header_' + v + ']', function (value) {
            value.bind(function (newval) {
                if (shortcodes['order_number']) {
                    for (let i in shortcodes) {
                        let reg_string = '{' + i + '}';
                        let reg = new RegExp(reg_string, 'g');
                        newval = newval.replace(reg, shortcodes[i]);
                    }
                }
                jQuery('.woocommerce-thank-you-page-social_icons-header>div').html(newval.replace(/\n/g, '<\/br>'));
            });
        });
    });
    wp.customize('woo_thank_you_page_params[social_icons_target]', function (value) {
        value.bind(function (newval) {
            jQuery('.wtyp-social-button').attr('target', newval);
        });
    });
    let social_icons = ['facebook','twitter','pinterest','instagram','dribbble','tumblr','google','vkontakte','linkedin','youtube'];
    jQuery.each(social_icons,function (k, v) {
        addPreviewControlSocialIcon(v);
        addPreviewControlSocialUrl(v);
    });
    
    /*google map*/
    wp.customize('woo_thank_you_page_params[google_map_width]', function (value) {
        value.bind(function (newval) {
            if (newval !== '0') {
                jQuery('#woocommerce-thank-you-page-preview-google_map_width').html('#woocommerce-thank-you-page-google-map{width:' + newval + 'px}');
            } else {
                jQuery('#woocommerce-thank-you-page-preview-google_map_width').html('#woocommerce-thank-you-page-google-map{width:100%}');
            }
        })
    });
    addPreviewControl('google_map_height', '#woocommerce-thank-you-page-google-map', 'height', 'px');

    /*bing map*/
    wp.customize('woo_thank_you_page_params[bing_map_width]', function (value) {
        value.bind(function (newval) {
            if (newval !== '0') {
                jQuery('#woocommerce-thank-you-page-preview-bing_map_width').html('#woocommerce-thank-you-page-bing-map{width:' + newval + 'px}');
            } else {
                jQuery('#woocommerce-thank-you-page-preview-bing_map_width').html('#woocommerce-thank-you-page-bing-map{width:100%}');
            }
        })
    });
    addPreviewControl('bing_map_height', '#woocommerce-thank-you-page-bing-map', 'height', 'px');
    function wtypc_init_map() {
        if (jQuery('#woocommerce-thank-you-page-google-map').length) {
            let address = wp.customize('woo_thank_you_page_params[google_map_address]').get();
            let zoom_level = parseInt(wp.customize('woo_thank_you_page_params[google_map_zoom_level]').get());
            let selected_style = wp.customize('woo_thank_you_page_params[google_map_style]').get();
            let map_style;
            if (selected_style === 'custom') {
                map_style = wp.customize('woo_thank_you_page_params[google_map_custom_style]').get();
            } else if (selected_style !== 'default') {
                map_style = woo_thank_you_page_params['google_map_styles'][selected_style];
            }
            initGoogleMap(zoom_level, address, map_style);
        }
        if (jQuery('#woocommerce-thank-you-page-bing-map').length) {
            initBingMap(
                wp.customize('woo_thank_you_page_params[bing_map_zoom_level]').get(),
                wp.customize('woo_thank_you_page_params[bing_map_address]').get(),
                wp.customize('woo_thank_you_page_params[bing_map_view]').get(),
                wp.customize('woo_thank_you_page_params[bing_map_navbarmode]').get()
            );
        }
    }
    function wtypc_get_data_layout(block, order_id=''){
        let data = {
            action: 'woo_thank_you_page_layout',
            order_id: order_id ? order_id : wp.customize('woo_thank_you_page_params[select_order]').get(),
            block: block ? block : wp.customize('woo_thank_you_page_params[blocks]').get(),
            text_editor: wp.customize('woo_thank_you_page_params[text_editor]').get(),
            products: wp.customize('woo_thank_you_page_params[products]').get(),
            order_confirmation:{
                order_confirmation_header: wp.customize('woo_thank_you_page_params[order_confirmation_header]').get(),
                order_confirmation_order_number_title: wp.customize('woo_thank_you_page_params[order_confirmation_order_number_title]').get(),
                order_confirmation_date_title: wp.customize('woo_thank_you_page_params[order_confirmation_date_title]').get(),
                order_confirmation_order_total_title: wp.customize('woo_thank_you_page_params[order_confirmation_order_total_title]').get(),
                order_confirmation_email_title: wp.customize('woo_thank_you_page_params[order_confirmation_email_title]').get(),
                order_confirmation_payment_title: wp.customize('woo_thank_you_page_params[order_confirmation_payment_title]').get(),
            },
            order_details_header: wp.customize('woo_thank_you_page_params[order_details_header]').get(),
            order_details_product_title_text: wp.customize('woo_thank_you_page_params[order_details_product_title_text]').get(),
            order_details_product_value_text: wp.customize('woo_thank_you_page_params[order_details_product_value_text]').get(),
            order_details_product_image: wp.customize('woo_thank_you_page_params[order_details_product_image]').get(),
            customer_information: {
                customer_information_header: wp.customize('woo_thank_you_page_params[customer_information_header]').get(),
                customer_information_billing_title: wp.customize('woo_thank_you_page_params[customer_information_billing_title]').get(),
                customer_information_shipping_title: wp.customize('woo_thank_you_page_params[customer_information_shipping_title]').get(),
            },
            thank_you_message_header: wp.customize('woo_thank_you_page_params[thank_you_message_header]').get(),
            thank_you_message_message: wp.customize('woo_thank_you_page_params[thank_you_message_message]').get(),
            social_icons: {
                'social_icons_header': wp.customize('woo_thank_you_page_params[social_icons_header]').get(),
                'social_icons_target': wp.customize('woo_thank_you_page_params[social_icons_target]').get(),
                'social_icons_facebook_url': wp.customize('woo_thank_you_page_params[social_icons_facebook_url]').get(),
                'social_icons_facebook_select': wp.customize('woo_thank_you_page_params[social_icons_facebook_select]').get(),
                'social_icons_twitter_url': wp.customize('woo_thank_you_page_params[social_icons_twitter_url]').get(),
                'social_icons_twitter_select': wp.customize('woo_thank_you_page_params[social_icons_twitter_select]').get(),
                'social_icons_pinterest_url': wp.customize('woo_thank_you_page_params[social_icons_pinterest_url]').get(),
                'social_icons_pinterest_select': wp.customize('woo_thank_you_page_params[social_icons_pinterest_select]').get(),
                'social_icons_instagram_url': wp.customize('woo_thank_you_page_params[social_icons_instagram_url]').get(),
                'social_icons_instagram_select': wp.customize('woo_thank_you_page_params[social_icons_instagram_select]').get(),
                'social_icons_dribbble_url': wp.customize('woo_thank_you_page_params[social_icons_dribbble_url]').get(),
                'social_icons_dribbble_select': wp.customize('woo_thank_you_page_params[social_icons_dribbble_select]').get(),
                'social_icons_tumblr_url': wp.customize('woo_thank_you_page_params[social_icons_tumblr_url]').get(),
                'social_icons_tumblr_select': wp.customize('woo_thank_you_page_params[social_icons_tumblr_select]').get(),
                'social_icons_google_url': wp.customize('woo_thank_you_page_params[social_icons_google_url]').get(),
                'social_icons_google_select': wp.customize('woo_thank_you_page_params[social_icons_google_select]').get(),
                'social_icons_vkontakte_url': wp.customize('woo_thank_you_page_params[social_icons_vkontakte_url]').get(),
                'social_icons_vkontakte_select': wp.customize('woo_thank_you_page_params[social_icons_vkontakte_select]').get(),
                'social_icons_linkedin_url': wp.customize('woo_thank_you_page_params[social_icons_linkedin_url]').get(),
                'social_icons_linkedin_select': wp.customize('woo_thank_you_page_params[social_icons_linkedin_select]').get(),
                'social_icons_youtube_url': wp.customize('woo_thank_you_page_params[social_icons_youtube_url]').get(),
                'social_icons_youtube_select': wp.customize('woo_thank_you_page_params[social_icons_youtube_select]').get(),
            },
            payment_method_html: wtypc_b64EncodeUnicode(payment_method_html),
            google_map_address: wp.customize('woo_thank_you_page_params[google_map_address]').get(),
            google_map_label: wp.customize('woo_thank_you_page_params[google_map_label]').get(),
            bing_map_address: wp.customize('woo_thank_you_page_params[bing_map_address]').get(),
            wtypc_languages: languages,
        };
        return data;
    }
    function addPreviewControl(name, element, style, suffix = '') {
        wp.customize('woo_thank_you_page_params[' + name + ']', function (value) {
            value.bind(function (newval) {
                jQuery('#woocommerce-thank-you-page-preview-' + name).html(element + '{' + style + ':' + newval + suffix + '}');
            });
        });
    }
    function addPreviewControlSocialIcon(name) {
        wp.customize('woo_thank_you_page_params[social_icons_' + name + '_select]', function (value) {
            value.bind(function (newval) {
                jQuery('.wtyp-' + name + '-follow span').attr('class', 'wtyp-social-icon ' + newval);
            });
        });
    }
    function addPreviewControlSocialUrl(name) {
        wp.customize('woo_thank_you_page_params[social_icons_' + name + '_url]', function (value) {
            value.bind(function (newval) {
                jQuery('.wtyp-' + name + '-follow a').attr('href', newval);
                if (newval) {
                    jQuery('.wtyp-' + name + '-follow').fadeIn(300);
                } else {
                    jQuery('.wtyp-' + name + '-follow').fadeOut(300);
                }
            });
        });
    }
    function wtypc_flex_silder() {
        jQuery('.woocommerce-thank-you-page-products-sliders:not(.woocommerce-thank-you-page-products-sliders-init)').map(function () {
            let data = jQuery(this).find('.woocommerce-thank-you-page-products-content').data();
            let itemWidth ,wrap_width = jQuery(this).innerWidth(),
                colums = parseInt(data['wtypc_columns'] || 4);
            itemWidth = (wrap_width - 12*colums)/colums;
            jQuery(this).addClass('woocommerce-thank-you-page-products-sliders-init').vi_flexslider({
                namespace: "woocommerce-thank-you-page-customizer-",
                selector: '.woocommerce-thank-you-page-products-content .woocommerce-thank-you-page-products-content-item',
                animation: "slide",
                animationLoop: data['slider_loop'] == 1 ? true : false,
                itemWidth: itemWidth || 145,
                itemMargin: 12,
                controlNav: false,
                maxItems: colums,
                reverse: false,
                slideshow: data['slider_slideshow'] == 1 ? true : false,
                move: data['slider_move'],
                touch: true,
                slideshowSpeed: data['slider_slideshow_speed']
            })
        })
    }
    function wtypc_disable_scroll() {
        if (jQuery(document).height() > jQuery(window).height()) {
            let scrollTop = (jQuery('html').scrollTop()) ? jQuery('html').scrollTop() : jQuery('body').scrollTop(); // Works for Chrome, Firefox, IE...
            jQuery('html').addClass('wtypc-noscroll').css('top', -scrollTop);
        }
    }
    function wtypc_enable_scroll() {
        let scrollTop = parseInt(jQuery('html').css('top'));
        jQuery('html').removeClass('wtypc-noscroll');
        jQuery('html,body').scrollTop(-scrollTop);
    }
    function wtypc_select2_params(placeholder, action, close_on_select=false, min_input=2) {
        let result = {
            closeOnSelect: close_on_select,
            placeholder: placeholder,
            cache: true
        };
        if (action) {
            result['minimumInputLength'] = min_input;
            result['escapeMarkup'] = function (markup) {
                return markup;
            };
            result['ajax'] = {
                url: woo_thank_you_page_params.url,
                dataType: 'json',
                type: "GET",
                quietMillis: 50,
                delay: 250,
                data: function (params) {
                    return {
                        keyword: params.term,
                        action: action
                    };
                },
                processResults: function (data) {
                    return {
                        results: data ? data : []
                    };
                },
                cache: false
            };
        }
        return result;
    }
    function handleOverlayProcessing(action) {
        wp.customize.preview.send('wtyp_handle_overlay_processing', action);
        jQuery('.woocommerce-thank-you-page-wp-editor-overlay').trigger('click');
        jQuery('.woocommerce-thank-you-page-products-modal-overlay').trigger('click');
        if (action === 'show') {
            jQuery('.woocommerce-thank-you-page-preview-processing-overlay').show();
        } else {
            jQuery('.woocommerce-thank-you-page-preview-processing-overlay').hide();
        }
    }
    function wtypc_b64EncodeUnicode(str) {
        // first we use encodeURIComponent to get percent-encoded UTF-8,
        // then we convert the percent encodings into raw bytes which
        // can be fed into btoa.
        return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
            function toSolidBytes(match, p1) {
                return String.fromCharCode('0x' + p1);
            }));
    }
    function wtypc_b64DecodeUnicode(str) {
        // Going backwards: from bytestream, to percent-encoding, to original string.
        return decodeURIComponent(atob(str).split('').map(function (c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));
    }
    function initGoogleMap(google_map_zoom_level, address, map_styles) {
        if (woo_thank_you_page_params.google_map_api && jQuery('#woocommerce-thank-you-page-google-map').length ) {
            let map = new google.maps.Map(document.getElementById('woocommerce-thank-you-page-google-map'), {
                zoom: google_map_zoom_level,
            });
            if (IsJsonString(map_styles)) {
                let styledMapType = new google.maps.StyledMapType(JSON.parse(map_styles));
                map.mapTypes.set('styled_map', styledMapType);
                map.setMapTypeId('styled_map');
            }
            wp.customize('woo_thank_you_page_params[google_map_custom_style]', function (value) {
                value.bind(function (newval) {
                    if (wp.customize('woo_thank_you_page_params[google_map_style]').get() === 'custom') {
                        if (newval) {
                            if (IsJsonString(newval)) {
                                let styledMapType = new google.maps.StyledMapType(JSON.parse(newval));
                                map.mapTypes.set('styled_map', styledMapType);
                                map.setMapTypeId('styled_map');
                            } else {
                                let styledMapType = new google.maps.StyledMapType('');
                                map.mapTypes.set('styled_map', styledMapType);
                                map.setMapTypeId('styled_map');
                            }
                        } else {
                            let styledMapType = new google.maps.StyledMapType('');
                            map.mapTypes.set('styled_map', styledMapType);
                            map.setMapTypeId('styled_map');
                        }

                    }
                })
            })
            wp.customize('woo_thank_you_page_params[google_map_style]', function (value) {
                value.bind(function (newval) {
                    if (newval === 'default') {
                        let styledMapType = new google.maps.StyledMapType([]);
                        map.mapTypes.set('styled_map', styledMapType);
                        map.setMapTypeId('styled_map');
                    } else if (newval === 'custom') {
                        if (IsJsonString(wp.customize('woo_thank_you_page_params[google_map_custom_style]').get())) {
                            let styledMapType = new google.maps.StyledMapType(JSON.parse(wp.customize('woo_thank_you_page_params[google_map_custom_style]').get()));
                            map.mapTypes.set('styled_map', styledMapType);
                            map.setMapTypeId('styled_map');
                        } else {
                            let styledMapType = new google.maps.StyledMapType('');
                            map.mapTypes.set('styled_map', styledMapType);
                            map.setMapTypeId('styled_map');
                        }
                    } else {
                        let styledMapType = new google.maps.StyledMapType(JSON.parse(woo_thank_you_page_params.google_map_styles[newval]));
                        map.mapTypes.set('styled_map', styledMapType);
                        map.setMapTypeId('styled_map');
                    }
                })
            });
            var geocoder = new google.maps.Geocoder();
            address = address.replace(/\n/g, '<\/br>');
            address = address.replace('{store_address}', shortcodes['store_address']);
            address = address.replace('{billing_address}', shortcodes['billing_address']);
            address = address.replace('{shipping_address}', shortcodes['shipping_address']);
            geocodeAddress(geocoder, map, address);
        } else if (jQuery('#woocommerce-thank-you-page-google-map').length ) {
            jQuery('#woocommerce-thank-you-page-google-map').html('<div class="woocommerce-thank-you-page-google-map-not-available"><h3>Google Map</h3> Please enter your Google API key, your map will show here.</div>');
        }
    }
    function geocodeAddress(geocoder, resultsMap, address) {
        let infowindow , markers_url = woo_thank_you_page_params.markers_url;
        geocoder.geocode({'address': address}, function (results, status) {
            if (status === 'OK') {
                resultsMap.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: resultsMap,
                    position: results[0].geometry.location,
                    title: address,
                    icon: woo_thank_you_page_params.google_map_marker
                });
                wp.customize('woo_thank_you_page_params[google_map_zoom_level]', function (value) {
                    value.bind(function (newval) {
                        resultsMap.setZoom(parseInt(newval));
                    })
                });
                wp.customize('woo_thank_you_page_params[google_map_marker]', function (value) {
                    value.bind(function (newval) {
                        marker.setIcon(markers_url + newval + '.png');
                    })
                });
                if (typeof infowindow === 'undefined') {
                    infowindow = new google.maps.InfoWindow({});
                }
                let map_label = wp.customize('woo_thank_you_page_params[google_map_label]').get().replace(/\n/g, '<\/br>');
                map_label = map_label.replace('{address}', wp.customize('woo_thank_you_page_params[google_map_address]').get());
                map_label = map_label.replace('{store_address}', shortcodes['store_address']);
                map_label = map_label.replace('{billing_address}', shortcodes['billing_address']);
                map_label = map_label.replace('{shipping_address}', shortcodes['shipping_address']);
                infowindow.setContent(map_label);
                infowindow.open(resultsMap, marker);
                marker.addListener('click', function () {
                    infowindow.open(resultsMap, marker);
                });
                wp.customize('woo_thank_you_page_params[google_map_label]', function (value) {
                    value.bind(function (newval) {
                        newval = newval.replace(/\n/g, '<\/br>');
                        newval = newval.replace('{address}', wp.customize('woo_thank_you_page_params[google_map_address]').get());
                        newval = newval.replace('{store_address}', shortcodes['store_address']);
                        newval = newval.replace('{billing_address}', shortcodes['billing_address']);
                        newval = newval.replace('{shipping_address}', shortcodes['shipping_address']);
                        infowindow.setContent(newval);
                    })
                });
                jQuery.each(languages, function (k, v) {
                    wp.customize('woo_thank_you_page_params[google_map_label_' + v + ']', function (value) {
                        value.bind(function (newval) {
                            newval = newval.replace(/\n/g, '<\/br>');
                            newval = newval.replace('{address}', wp.customize('woo_thank_you_page_params[google_map_address]').get());
                            newval = newval.replace('{store_address}', shortcodes['store_address']);
                            newval = newval.replace('{billing_address}', shortcodes['billing_address']);
                            newval = newval.replace('{shipping_address}', shortcodes['shipping_address']);
                            infowindow.setContent(newval);
                        });
                    });
                });
            }
        });
    }
    function initBingMap(zoom_level, address, map_view, map_nav_bar) {
        if (woo_thank_you_page_params.bing_map_api && jQuery('#woocommerce-thank-you-page-bing-map:not(.woocommerce-thank-you-page-bing-map-init)').length ) {
            jQuery('#woocommerce-thank-you-page-bing-map').addClass('woocommerce-thank-you-page-bing-map-init');
            map_view = get_bing_map_style(map_view);
            map_nav_bar = get_bing_map_nav_bar_mode(map_nav_bar);
            var bing_map, bing_loc;
            address = address.replace(/\n/g, '<\/br>');
            address = address.replace('{store_address}', shortcodes['store_address']);
            address = address.replace('{billing_address}', shortcodes['billing_address']);
            address = address.replace('{shipping_address}', shortcodes['shipping_address']);
            if (address) {
                jQuery.ajax({
                    url: 'https://dev.virtualearth.net/REST/v1/Locations',
                    type: 'GET',
                    dataType: "jsonp",
                    jsonp: "jsonp",
                    data: {
                        query: address,
                        key: woo_thank_you_page_params.bing_map_api,
                    },
                    success: function (result) {
                        bing_loc = result.resourceSets[0].resources[0].point.coordinates;
                        bing_map = new Microsoft.Maps.Map(document.getElementById('woocommerce-thank-you-page-bing-map'), {
                            center: new Microsoft.Maps.Location(bing_loc[0], bing_loc[1]),
                            mapTypeId: map_view,
                            zoom: zoom_level,
                            navigationBarMode: map_nav_bar,
                        });
                        set_bing_map(bing_map, bing_loc, address);
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            }
        } else if (jQuery('#woocommerce-thank-you-page-bing-map:not(.woocommerce-thank-you-page-bing-map-init)').length ) {
            jQuery('#woocommerce-thank-you-page-bing-map').addClass('woocommerce-thank-you-page-bing-map-init').html('<div class="woocommerce-thank-you-page-bing-map-not-available"><h3>Bing Map</h3> Please enter your Bing API key, your map will show here.</div>');
        }
    }
    function get_bing_map_style(bing_map_view='') {
        let map_style;
        switch (bing_map_view) {
            case 'aerial':
                map_style = Microsoft.Maps.MapTypeId.aerial;
                break;
            case 'canvasDark':
                map_style = Microsoft.Maps.MapTypeId.canvasDark;
                break;
            case 'canvasLight':
                map_style = Microsoft.Maps.MapTypeId.canvasLight;
                break;
            case 'grayscale':
                map_style = Microsoft.Maps.MapTypeId.grayscale;
                break;
            default:
                map_style = Microsoft.Maps.MapTypeId.road;
        }
        return map_style;
    }
    function get_bing_map_nav_bar_mode(mode='') {
        let nav_mode;
        switch (mode) {
            case 'compact':
                nav_mode = Microsoft.Maps.NavigationBarMode.compact;
                break;
            case 'minified':
                nav_mode = Microsoft.Maps.NavigationBarMode.minified;
                break;
            default:
                nav_mode = Microsoft.Maps.NavigationBarMode.default;
                break;
        }
        return nav_mode;
    }
    function set_bing_map(map, loc, address) {
        let markers_url = woo_thank_you_page_params.markers_url;
        var map_center = map.getCenter();
        var map_infobox = new Microsoft.Maps.Infobox(map_center, {
            visible: false
        });
        map_infobox.setMap(map);
        let map_custom, bing_map_marker;
        bing_map_marker = wp.customize('woo_thank_you_page_params[bing_map_marker]').get() ? markers_url + wp.customize('woo_thank_you_page_params[bing_map_marker]').get() + '.png' : woo_thank_you_page_params.bing_map_marker;
        if (woo_thank_you_page_params.bing_map_marker) {
            map_custom = new Microsoft.Maps.Pushpin(new Microsoft.Maps.Location(loc[0], loc[1]), {
                icon: bing_map_marker,
                title: address
            });
        } else {
            map_custom = new Microsoft.Maps.Pushpin(new Microsoft.Maps.Location(loc[0], loc[1]), {});
        }
        map.entities.push(map_custom);
        jQuery('#woocommerce-thank-you-page-bing-map').removeClass('woocommerce-thank-you-page-bing-map-init');
        wp.customize('woo_thank_you_page_params[bing_map_marker]',function (value) {
            value.bind(function (newval) {
                bing_map_marker =newval ? markers_url + newval + '.png' : woo_thank_you_page_params.bing_map_marker;
                map_custom.setOptions({icon:bing_map_marker});
            });
        });
        let map_update =[
            'bing_map_view',
            'bing_map_navbarmode',
            'bing_map_zoom_level',
        ];
        jQuery.each(map_update,function (k, v) {
            wp.customize('woo_thank_you_page_params['+v+']', function (value) {
                value.bind(function (newval) {
                    wp.customize.preview.send('wtyp_update_bing_map_address',  wp.customize('woo_thank_you_page_params[bing_map_address]').get());
                });
            });
        });
    }
    function IsJsonString(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }
})();