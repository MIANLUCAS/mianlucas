
(function ($) {
    'use strict';
    let markers_url = woo_thank_you_page_params.markers_url;
    let shortcodes = woo_thank_you_page_params.shortcodes;
    let payment_method_html;
    let infowindow;
    if (!shortcodes['order_number']) {
        let order_id = $('.wtyp-order-id').val();
        if (order_id) {
            $.ajax({
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
    /*general*/
    wp.customize.bind('preview-ready', function () {
        wtypc_flex_silder();
        /*ajax search*/
        jQuery(".search-product-parent").select2({
            closeOnSelect: false,
            placeholder: "Please fill in your  product title",
            ajax: {
                url: woo_thank_you_page_params.url,
                dataType: 'json',
                quietMillis: 50,
                delay: 250,
                data: function (params) {
                    return {
                        keyword: params.term,
                        action: 'wtyp_search_product_parent',
                    };
                },
                type: "GET",
                processResults: function (data) {
                    return {
                        results: data ? data : []
                    };
                },
                cache: false
            },
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 2
        });
        jQuery(".search-category").select2({
            closeOnSelect: false,
            placeholder: "Please fill in your category title",
            ajax: {
                url: woo_thank_you_page_params.url,
                dataType: 'json',
                quietMillis: 50,
                delay: 250,
                data: function (params) {
                    return {
                        keyword: params.term,
                        action: 'wtyp_search_cate',
                    };
                },
                type: "GET",
                processResults: function (data) {
                    return {
                        results: data ? data : []
                    };
                },
                cache: false
            },
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 2
        });

        wp.customize.preview.bind('active', function () {
            $('.woocommerce-thank-you-page-available-shortcodes-item-copy').on('click', function () {
                $(this).parent().find('input').select();
                document.execCommand("copy");
            });
            $('.woocommerce-thank-you-page-coupon__code-code').focus(function () {
                $(this).select();
            })
            $('body').on('click', '.woocommerce-thank-you-page-edit-item-shortcut', function () {
                wp.customize.preview.send('wtyp_shortcut_edit', $(this).data()['edit_section']);
            });
            $('.woocommerce-thank-you-page-available-shortcodes-shortcut').on('click', function () {
                $('.woocommerce-thank-you-page-available-shortcodes-container').removeClass('woocommerce-thank-you-page-hidden');
            });
            wp.customize.preview.bind('wtyp_shortcut_to_available_shortcodes', function () {
                if ($('.woocommerce-thank-you-page-available-shortcodes-container').hasClass('woocommerce-thank-you-page-hidden')) {
                    $('.woocommerce-thank-you-page-available-shortcodes-container').removeClass('woocommerce-thank-you-page-hidden');
                } else {
                    $('.woocommerce-thank-you-page-available-shortcodes-container').addClass('woocommerce-thank-you-page-hidden');
                }

            });
            $('.woocommerce-thank-you-page-available-shortcodes-items-close').on('click', function () {
                $('.woocommerce-thank-you-page-available-shortcodes-overlay').trigger('click');
            });
            $('.woocommerce-thank-you-page-available-shortcodes-overlay').on('click', function () {
                $('.woocommerce-thank-you-page-available-shortcodes-container').addClass('woocommerce-thank-you-page-hidden');
            });
            $('.woocommerce-thank-you-page-available-shortcodes-item-syntax').find('input').on('click', function () {
                $(this).select()
            });

            $('body').on('click', '.woocommerce-thank-you-page-products-edit', function () {
                wtypc_disable_scroll()
                $('.woocommerce-thank-you-page-products-modal-container').addClass('woocommerce-thank-you-page-products-active');
                $('.woocommerce-thank-you-page-products-modal-overlay').addClass('woocommerce-thank-you-page-products-active');
                let editing = $(this).parent();
                editing.addClass('woocommerce-thank-you-page-products-editing');
                let data = editing.find('.woocommerce-thank-you-page-products-content').data();
                /*product ids*/
                let product_ids = (data['product_ids']);
                let product_ids_field = $('#specific-products-product-ids');
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
                let excluded_product_ids_field = $('#specific-products-excluded-product-ids');
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
                let product_categories_field = $('#specific-products-product-categories');
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
                let excluded_product_categories_field = $('#specific-products-excluded-product-categories');
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
                $('#specific-products-order-by').val(data['order_by']);
                $('#specific-products-visibility').val(data['visibility']);
                $('#specific-products-order').val(data['order']);
                $('#specific-products-product-options').val(data['product_options']);
                $('#specific-products-columns').val(data['wtypc_columns']);
                $('#specific-products-limit').val(data['limit']);
                $('#specific-products-slider-move').val(data['slider_move']);
                $('#specific-products-slider-slideshow-speed').val(data['slider_slideshow_speed']);
                if (data['slider_loop'] == '1') {
                    $('#specific-products-slider-loop').prop('checked', true);
                } else {
                    $('#specific-products-slider-loop').prop('checked', false);
                }
                if (data['slider_slideshow'] == '1') {
                    $('#specific-products-slider-slideshow').prop('checked', true);
                } else {
                    $('#specific-products-slider-slideshow').prop('checked', false);
                }
                if (data['slider_pause_on_hover'] == '1') {
                    $('#specific-products-slider-pause-on-hover').prop('checked', true);
                } else {
                    $('#specific-products-slider-pause-on-hover').prop('checked', false);
                }
            });
            $('.woocommerce-thank-you-page-products-modal-items-close').on('click', function () {
                $('.woocommerce-thank-you-page-products-modal-overlay').trigger('click');
            })
            $('.woocommerce-thank-you-page-products-modal-overlay').on('click', function () {
                wtypc_enable_scroll()
                $('.woocommerce-thank-you-page-products-modal-container').removeClass('woocommerce-thank-you-page-products-active');
                $('.woocommerce-thank-you-page-products-modal-overlay').removeClass('woocommerce-thank-you-page-products-active');
                $('.woocommerce-thank-you-page-products').removeClass('woocommerce-thank-you-page-products-editing');

            });
            $('body').on('click', '.woocommerce-thank-you-page-products-modal-cancel', function () {
                $('.woocommerce-thank-you-page-products-modal-overlay').trigger('click');
            });
            $('body').on('click', '.woocommerce-thank-you-page-products-modal-save', function () {
                $('.woocommerce-thank-you-page-preview-processing-overlay').show();
                let order_by = $('#specific-products-order-by').val(),
                    visibility = $('#specific-products-visibility').val(),
                    order = $('#specific-products-order').val(),
                    columns = $('#specific-products-columns').val(),
                    limit = $('#specific-products-limit').val(),
                    product_options = $('#specific-products-product-options').val(),
                    slider_loop = $('#specific-products-slider-loop').prop('checked') ? '1' : '',
                    slider_move = $('#specific-products-slider-move').val(),
                    slider_slideshow = $('#specific-products-slider-slideshow').prop('checked') ? '1' : '',
                    slider_slideshow_speed = $('#specific-products-slider-slideshow-speed').val(),
                    slider_pause_on_hover = $('#specific-products-slider-pause-on-hover').prop('checked') ? '1' : '',
                    editing = $('.woocommerce-thank-you-page-products-editing');
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

                let product_ids_data = $('#specific-products-product-ids').select2('data');
                let product_ids = {};
                for (let i in product_ids_data) {
                    if (product_ids_data.hasOwnProperty(i)) {
                        product_ids[product_ids_data[i]['id']] = product_ids_data[i]['text'];
                        products['product_ids'].push(product_ids_data[i]['id']);
                    }
                }
                let excluded_product_ids_data = $('#specific-products-excluded-product-ids').select2('data');
                let excluded_product_ids = {};
                for (let i in excluded_product_ids_data) {
                    if (excluded_product_ids_data.hasOwnProperty(i)) {
                        excluded_product_ids[excluded_product_ids_data[i]['id']] = excluded_product_ids_data[i]['text'];
                        products['excluded_product_ids'].push(excluded_product_ids_data[i]['id']);
                    }
                }
                let product_categories_data = $('#specific-products-product-categories').select2('data');
                let product_categories = {};
                for (let i in product_categories_data) {
                    if (product_categories_data.hasOwnProperty(i)) {
                        product_categories[product_categories_data[i]['id']] = product_categories_data[i]['text'];
                        products['product_categories'].push(product_categories_data[i]['id']);
                    }
                }
                let excluded_product_categories_data = $('#specific-products-excluded-product-categories').select2('data');
                let excluded_product_categories = {};
                for (let i in excluded_product_categories_data) {
                    if (excluded_product_categories_data.hasOwnProperty(i)) {
                        excluded_product_categories[excluded_product_categories_data[i]['id']] = excluded_product_categories_data[i]['text'];
                        products['excluded_product_categories'].push(excluded_product_categories_data[i]['id']);
                    }
                }

                $.ajax({
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
                        $('.woocommerce-thank-you-page-preview-processing-overlay').hide();
                        let products_old = JSON.parse(wp.customize('woo_thank_you_page_params[products]').get());
                        let i = $('.woocommerce-thank-you-page-products').index(editing);
                        if (i > -1) {
                            products_old[i] = products;
                            wp.customize.preview.send('wtyp_update_products', JSON.stringify(products_old));
                        }
                        editing.html(response.html);
                        editing.find('.woocommerce-thank-you-page-products-sliders').vi_flexslider({
                            namespace: "woocommerce-thank-you-page-customizer-",
                            selector: '.woocommerce-thank-you-page-products-content > .woocommerce-thank-you-page-products-content-item',
                            animation: "slide",
                            animationLoop: slider_loop == 1 ? true : false,
                            itemWidth: 145,
                            itemMargin: 12,
                            controlNav: false,
                            maxItems: columns,
                            reverse: false,
                            slideshow: slider_slideshow == 1 ? true : false,
                            move: slider_move,
                            touch: true,
                            slideshowSpeed: slider_slideshow_speed
                        })
                    },
                    error: function (err) {
                        handleOverlayProcessing('hide');
                    }
                })
                $('.woocommerce-thank-you-page-products-modal-overlay').trigger('click');
            });


            $('body').on('click', '.woocommerce-thank-you-page-text-editor', function () {
                wtypc_disable_scroll();
                let index = $('.woocommerce-thank-you-page-text-editor').index($(this));
                let textEditor = JSON.parse(wp.customize('woo_thank_you_page_params[text_editor]').get());
                if (index > -1) {
                    $('.woocommerce-thank-you-page-wp-editor-container').addClass('woocommerce-thank-you-page-active');
                    $('.woocommerce-thank-you-page-wp-editor-overlay').addClass('woocommerce-thank-you-page-active');
                    $(this).addClass('woocommerce-thank-you-page-editing');
                    let content = wtypc_b64DecodeUnicode(textEditor[index]);
                    if (tinyMCE.get('woocommerce-thank-you-page-wp-editor')) {
                        tinyMCE.get('woocommerce-thank-you-page-wp-editor').setContent(content);
                    } else {
                        $('#woocommerce-thank-you-page-wp-editor').val(content);
                    }
                }
            });
            $('.woocommerce-thank-you-page-wp-editor-save').on('click', function () {
                $('.woocommerce-thank-you-page-preview-processing-overlay').show();
                let content;
                if (tinyMCE.get('woocommerce-thank-you-page-wp-editor')) {
                    content = tinyMCE.get('woocommerce-thank-you-page-wp-editor').getContent();
                } else {
                    content = $('#woocommerce-thank-you-page-wp-editor').val();
                }
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: woo_thank_you_page_params.url,
                    data: {
                        action: 'woo_thank_you_page_get_text_editor_content',
                        shortcodes: shortcodes,
                        content: content,
                    },
                    success: function (response) {
                        $('.woocommerce-thank-you-page-preview-processing-overlay').hide();
                        let editing = $('.woocommerce-thank-you-page-editing');
                        let index = $('.woocommerce-thank-you-page-text-editor').index(editing);
                        let textEditor = JSON.parse(wp.customize('woo_thank_you_page_params[text_editor]').get());
                        if (index > -1) {
                            textEditor[index] = wtypc_b64EncodeUnicode(content);
                            wp.customize.preview.send('wtyp_update_text_editor', JSON.stringify(textEditor));
                        }
                        editing.find('.woocommerce-thank-you-page-text-editor-content').html(response.html)
                        $('.woocommerce-thank-you-page-wp-editor-overlay').trigger('click');
                    },
                    error: function (err) {
                        handleOverlayProcessing('hide');
                        alert('Cannot save content.')
                    }
                })
            });

            $('.woocommerce-thank-you-page-wp-editor-overlay').on('click', function () {
                $('.woocommerce-thank-you-page-wp-editor-container').removeClass('woocommerce-thank-you-page-active');
                $('.woocommerce-thank-you-page-wp-editor-overlay').removeClass('woocommerce-thank-you-page-active');
                $('.woocommerce-thank-you-page-text-editor').removeClass('woocommerce-thank-you-page-editing');
                if (tinyMCE.get('woocommerce-thank-you-page-wp-editor')) {
                    tinyMCE.get('woocommerce-thank-you-page-wp-editor').setContent('');
                } else {
                    $('#woocommerce-thank-you-page-wp-editor').val('');
                }
                wtypc_enable_scroll()
            });
            $('.woocommerce-thank-you-page-wp-editor-cancel').on('click', function () {
                $('.woocommerce-thank-you-page-wp-editor-overlay').trigger('click');
            });
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
            initBingMap(wp.customize('woo_thank_you_page_params[bing_map_zoom_level]').get(), wp.customize('woo_thank_you_page_params[bing_map_address]').get(), wp.customize('woo_thank_you_page_params[bing_map_view]').get(), wp.customize('woo_thank_you_page_params[bing_map_navbarmode]').get());
        });
        wp.customize.preview.bind('wtyp_shortcut_edit_item_from_section', function (item) {
            $('.woocommerce-thank-you-page-wp-editor-overlay').trigger('click');
            $('.woocommerce-thank-you-page-products-modal-overlay').trigger('click');
            $('.' + item).trigger('click');
        });
        wp.customize.preview.bind('wtyp_shortcut_edit_text_editor_from_section', function (position) {
            $('.woocommerce-thank-you-page-wp-editor-overlay').trigger('click');
            $('.woocommerce-thank-you-page-products-modal-overlay').trigger('click');
            let item = $('.woocommerce-thank-you-page-text-editor').eq(position);
            if (item.length) {
                item.trigger('click');
                let top = item.offset().top;
                if (top > 200) {
                    top = top - 200;
                }
                $('html, body').animate({scrollTop: top}, 'slow');
            }
        });
        wp.customize.preview.bind('wtyp_shortcut_edit_products_from_section', function (position) {
            $('.woocommerce-thank-you-page-wp-editor-overlay').trigger('click');
            $('.woocommerce-thank-you-page-products-modal-overlay').trigger('click');
            let item = $('.woocommerce-thank-you-page-products').eq(position);
            if (item.length) {
                item.find('.woocommerce-thank-you-page-products-edit').trigger('click');
                let top = item.offset().top;
                if (top > 200) {
                    top = top - 200;
                }
                $('html, body').animate({scrollTop: top}, 'slow');
            }
        });
        wp.customize.preview.bind('wtyp_focus_on_editing_item', function (message) {
            let item = $('#' + message);
            if (item.length) {
                let top = item.offset().top;
                if (top > 200) {
                    top = top - 200;
                }
                $('html, body').animate({scrollTop: top}, 'slow');
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
        wp.customize.preview.bind('wtyp_update_url', function (message) {
            if ($('.woocommerce-thank-you-page-customize-preview').length == 0) {
                wp.customize.preview.send('wtyp_update_url', message);
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
        payment_method_html = jQuery('#woocommerce-thank-you-page-payment-method-html-hold').html();
    });

    function initBingMap(zoom_level, address, map_view, map_nav_bar) {
        if (woo_thank_you_page_params.bing_map_api && $('#woocommerce-thank-you-page-bing-map').length > 0) {
            map_view = get_map_style(map_view);
            map_nav_bar = get_map_nav_bar_mode(map_nav_bar);
            var bing_map, bing_loc;
            address = address.replace(/\n/g, '<\/br>');
            address = address.replace('{store_address}', shortcodes['store_address']);
            address = address.replace('{billing_address}', shortcodes['billing_address']);
            address = address.replace('{shipping_address}', shortcodes['shipping_address']);
            if (address) {
                $.ajax({
                    url: 'https://dev.virtualearth.net/REST/v1/Locations',
                    type: 'GET',
                    dataType: "jsonp",
                    jsonp: "jsonp",
                    data: {
                        query: address,
                        key: woo_thank_you_page_params.bing_map_api,
                    },
                    beforeSend: function () {
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

            // wp.customize('woo_thank_you_page_params[bing_map_view]', function (value) {
            //     value.bind(function (newval) {
            //         bing_map.setView({
            //             mapTypeId: get_map_style(newval)
            //         });
            //     })
            // });


        } else if ($('#woocommerce-thank-you-page-bing-map').length > 0) {
            $('#woocommerce-thank-you-page-bing-map').html('<div class="woocommerce-thank-you-page-bing-map-not-available"><h3>Bing Map</h3> Please enter your Bing API key, your map will show here.</div>');
        }
    }

    function get_map_nav_bar_mode(mode) {
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

    function get_map_style(bing_map_view) {
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

    function set_bing_map(map, loc, address) {
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

    }

    function initGoogleMap(google_map_zoom_level, address, map_styles) {
        if (woo_thank_you_page_params.google_map_api && $('#woocommerce-thank-you-page-google-map').length > 0) {
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
                    if (newval == 'default') {
                        let styledMapType = new google.maps.StyledMapType([]);
                        map.mapTypes.set('styled_map', styledMapType);
                        map.setMapTypeId('styled_map');
                    } else if (newval == 'custom') {
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
            // let address = $('.woocommerce-thank-you-page-google-map-address').val();
            geocodeAddress(geocoder, map, address);
        } else if ($('#woocommerce-thank-you-page-google-map').length > 0) {
            $('#woocommerce-thank-you-page-google-map').html('<div class="woocommerce-thank-you-page-google-map-not-available"><h3>Google Map</h3> Please enter your Google API key, your map will show here.</div>');
        }
    }

    function geocodeAddress(geocoder, resultsMap, address) {
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
                })
            }
        });
    }

    function pinSymbol(color, border = '#000') {
        if (!color) {
            color = '#dd3333';
        }
        return {
            path: 'm327.83334,1158.78052c-38.76586,-190.30109 -107.11596,-348.6651 -189.90295,-495.44c-61.40698,-108.87207 -132.54394,-209.36304 -198.36401,-314.93793c-21.97199,-35.24402 -40.93396,-72.47705 -62.04702,-109.05408c-42.21594,-73.13696 -76.44397,-157.935 -74.26898,-267.93201c2.125,-107.47299 33.20801,-193.68402 78.02997,-264.17202c73.71907,-115.93497 197.20107,-210.98899 362.88405,-235.96897c135.46606,-20.42401 262.4751,14.08203 352.543,66.74799c73.6001,43.03799 130.59607,100.52701 173.92004,168.28c45.22003,70.716 76.35901,154.26001 78.97101,263.23202c1.33703,55.83002 -7.80499,107.53198 -20.68402,150.41797c-13.03394,43.40906 -33.99597,79.69501 -52.646,118.45404c-36.406,75.659 -82.04895,144.98194 -127.85498,214.34595c-136.43704,206.60596 -264.49612,417.30994 -320.58011,706.02704z',
            fillColor: color,
            fillOpacity: 1,
            strokeColor: border,
            strokeWeight: 1,
            scale: 0.02,
        };
    }

    function handleOverlayProcessing(action) {
        wp.customize.preview.send('wtyp_handle_overlay_processing', action);
        $('.woocommerce-thank-you-page-wp-editor-overlay').trigger('click');
        $('.woocommerce-thank-you-page-products-modal-overlay').trigger('click');

        if (action === 'show') {
            $('.woocommerce-thank-you-page-preview-processing-overlay').show();
        } else {
            $('.woocommerce-thank-you-page-preview-processing-overlay').hide();
        }
    }

    wp.customize('woo_thank_you_page_params[select_order]', function (value) {
        value.bind(function (newval) {
            let container = $('.woocommerce-thank-you-page-container');
            handleOverlayProcessing('show');
            if (container.length > 0) {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: woo_thank_you_page_params.url,
                    data: {
                        action: 'woo_thank_you_page_layout',
                        order_id: newval,
                        block: wp.customize('woo_thank_you_page_params[blocks]').get(),
                        text_editor: wp.customize('woo_thank_you_page_params[text_editor]').get(),
                        products: wp.customize('woo_thank_you_page_params[products]').get(),
                        order_confirmation_header: wp.customize('woo_thank_you_page_params[order_confirmation_header]').get(),
                        order_details_header: wp.customize('woo_thank_you_page_params[order_details_header]').get(),
                        order_details_product_image: wp.customize('woo_thank_you_page_params[order_details_product_image]').get(),
                        customer_information_header: wp.customize('woo_thank_you_page_params[customer_information_header]').get(),
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
                    },
                    success: function (response) {
                        handleOverlayProcessing('hide');
                        if (response.hasOwnProperty('shortcodes')) {
                            shortcodes = response.shortcodes;
                        }
                        if (response.hasOwnProperty('blocks')) {
                            $('.woocommerce-thank-you-page-container').html(response.blocks);
                        }
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
                        initBingMap(wp.customize('woo_thank_you_page_params[bing_map_zoom_level]').get(), wp.customize('woo_thank_you_page_params[bing_map_address]').get(), wp.customize('woo_thank_you_page_params[bing_map_view]').get(), wp.customize('woo_thank_you_page_params[bing_map_navbarmode]').get());
                        wtypc_flex_silder()
                    },
                    error: function (err) {
                        handleOverlayProcessing('hide');
                        console.log(err);
                    }
                })
            } else {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: woo_thank_you_page_params.url,
                    data: {
                        action: 'woo_thank_you_page_layout',
                        order_id: newval,
                        change_url: true
                    },
                    success: function (response) {
                        wp.customize.preview.send('wtyp_update_url', response.url);
                    },
                    error: function (err) {
                        console.log(err);
                    }
                })
            }

        });
    });
    wp.customize('woo_thank_you_page_params[blocks]', function (value) {
        value.bind(function (newval) {
            handleOverlayProcessing('show');
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: woo_thank_you_page_params.url,
                data: {
                    action: 'woo_thank_you_page_layout',
                    order_id: wp.customize('woo_thank_you_page_params[select_order]').get(),
                    block: newval,
                    text_editor: wp.customize('woo_thank_you_page_params[text_editor]').get(),
                    products: wp.customize('woo_thank_you_page_params[products]').get(),
                    order_confirmation_header: wp.customize('woo_thank_you_page_params[order_confirmation_header]').get(),
                    order_details_header: wp.customize('woo_thank_you_page_params[order_details_header]').get(),
                    order_details_product_image: wp.customize('woo_thank_you_page_params[order_details_product_image]').get(),
                    customer_information_header: wp.customize('woo_thank_you_page_params[customer_information_header]').get(),
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
                },
                success: function (response) {
                    handleOverlayProcessing('hide');
                    if (response.hasOwnProperty('blocks')) {
                        $('.woocommerce-thank-you-page-container').html(response.blocks);
                        if (response.hasOwnProperty('shortcodes')) {
                            shortcodes = response.shortcodes;
                        }
                        wp.customize.preview.send('wtyp_open_latest_added_item', '');
                    }
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
                    initBingMap(wp.customize('woo_thank_you_page_params[bing_map_zoom_level]').get(), wp.customize('woo_thank_you_page_params[bing_map_address]').get(), wp.customize('woo_thank_you_page_params[bing_map_view]').get(), wp.customize('woo_thank_you_page_params[bing_map_navbarmode]').get());
                    wtypc_flex_silder()
                },
                error: function (err) {
                    handleOverlayProcessing('hide');
                    console.log(err);
                }
            })
        });
    });

    /*order confirmation*/
    addPreviewControl('order_confirmation_bg', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'background-color');
    addPreviewControl('order_confirmation_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'padding', 'px');
    addPreviewControl('order_confirmation_border_radius', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-radius', 'px');
    addPreviewControl('order_confirmation_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-width', 'px');
    addPreviewControl('order_confirmation_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-style');
    addPreviewControl('order_confirmation_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-color');

    addPreviewControl('order_confirmation_vertical_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'border-right-width', 'px');
    addPreviewControl('order_confirmation_vertical_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'border-right-style');
    addPreviewControl('order_confirmation_vertical_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'border-right-color');

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
            $('.woocommerce-thank-you-page-order_confirmation-header>div').html(newval.replace(/\n/g, '<\/br>'));
        })
    });
    addPreviewControl('order_confirmation_header_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'color');
    addPreviewControl('order_confirmation_header_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'background-color');
    addPreviewControl('order_confirmation_header_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'font-size', 'px');
    addPreviewControl('order_confirmation_header_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'text-align');

    addPreviewControl('order_confirmation_title_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'color');
    addPreviewControl('order_confirmation_title_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'background-color');
    addPreviewControl('order_confirmation_title_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'font-size', 'px');
    addPreviewControl('order_confirmation_title_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'text-align');

    addPreviewControl('order_confirmation_value_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'color');
    addPreviewControl('order_confirmation_value_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'background-color');
    addPreviewControl('order_confirmation_value_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'font-size', 'px');
    addPreviewControl('order_confirmation_value_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'text-align');

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
            $('.woocommerce-thank-you-page-order_details-header>div').html(newval.replace(/\n/g, '<\/br>'));
        })
    });
    wp.customize('woo_thank_you_page_params[order_details_product_image]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                $('.woocommerce-thank-you-page-order-item-image-container').addClass('woocommerce-thank-you-page-active');
            } else {
                $('.woocommerce-thank-you-page-order-item-image-container').removeClass('woocommerce-thank-you-page-active');
            }
        })
    });


    /*customer information*/
    addPreviewControl('customer_information_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'color');
    addPreviewControl('customer_information_bg', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'background-color');
    addPreviewControl('customer_information_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'padding', 'px');
    addPreviewControl('customer_information_border_radius', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-radius', 'px');
    addPreviewControl('customer_information_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-width', 'px');
    addPreviewControl('customer_information_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-style');
    addPreviewControl('customer_information_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-color');

    addPreviewControl('customer_information_vertical_width', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address .woocommerce-thank-you-page-customer_information__shipping_address', 'border-left-width', 'px');
    addPreviewControl('customer_information_vertical_style', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address .woocommerce-thank-you-page-customer_information__shipping_address', 'border-left-style');
    addPreviewControl('customer_information_vertical_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address .woocommerce-thank-you-page-customer_information__shipping_address', 'border-left-color');

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
            $('.woocommerce-thank-you-page-customer_information-header>div').html(newval.replace(/\n/g, '<\/br>'));
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
            $('.woocommerce-thank-you-page-social_icons-header>div').html(newval.replace(/\n/g, '<\/br>'));
        })
    });
    wp.customize('woo_thank_you_page_params[social_icons_target]', function (value) {
        value.bind(function (newval) {
            jQuery('.wtyp-social-button').attr('target', newval);
        });
    });
    addPreviewControlSocialIcon('facebook');
    addPreviewControlSocialUrl('facebook');
    addPreviewControlSocialIcon('twitter');
    addPreviewControlSocialUrl('twitter');
    addPreviewControlSocialIcon('pinterest');
    addPreviewControlSocialUrl('pinterest');
    addPreviewControlSocialIcon('instagram');
    addPreviewControlSocialUrl('instagram');
    addPreviewControlSocialIcon('dribbble');
    addPreviewControlSocialUrl('dribbble');
    addPreviewControlSocialIcon('tumblr');
    addPreviewControlSocialUrl('tumblr');
    addPreviewControlSocialIcon('google');
    addPreviewControlSocialUrl('google');
    addPreviewControlSocialIcon('vkontakte');
    addPreviewControlSocialUrl('vkontakte');
    addPreviewControlSocialIcon('linkedin');
    addPreviewControlSocialUrl('linkedin');
    addPreviewControlSocialIcon('youtube');
    addPreviewControlSocialUrl('youtube');

    /*thank you message*/
    addPreviewControl('thank_you_message_color', '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail', 'color');
    addPreviewControl('thank_you_message_padding', '.woocommerce-thank-you-page-thank_you_message__container', 'padding', 'px');
    addPreviewControl('thank_you_message_text_align', '.woocommerce-thank-you-page-thank_you_message__container', 'text-align');
    addPreviewControl('thank_you_message_header_font_size', '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail .woocommerce-thank-you-page-thank_you_message-header', 'font-size', 'px');
    addPreviewControl('thank_you_message_message_font_size', '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail .woocommerce-thank-you-page-thank_you_message-message', 'font-size', 'px');
    wp.customize('woo_thank_you_page_params[thank_you_message_header]', function (value) {
        value.bind(function (newval) {
            if (shortcodes['order_number']) {
                for (let i in shortcodes) {
                    let reg_string = '{' + i + '}';
                    let reg = new RegExp(reg_string, 'g');
                    newval = newval.replace(reg, shortcodes[i]);
                }
            }
            $('.woocommerce-thank-you-page-thank_you_message-header>div').html(newval.replace(/\n/g, '<\/br>'));
        })
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
            $('.woocommerce-thank-you-page-thank_you_message-message>div').html(newval.replace(/\n/g, '<\/br>'));
        })
    });
    /*coupon*/
    addPreviewControl('coupon_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container', 'text-align');
    addPreviewControl('coupon_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container', 'padding', 'px');
    addPreviewControl('coupon_message_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__message', 'color');
    addPreviewControl('coupon_message_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__message', 'font-size', 'px');
    addPreviewControl('coupon_code_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'color');
    addPreviewControl('coupon_code_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'background-color');
    addPreviewControl('coupon_code_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'border-width', 'px');
    addPreviewControl('coupon_code_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'border-style');
    addPreviewControl('coupon_code_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'border-color');
    wp.customize('woo_thank_you_page_params[coupon_scissors_color]', function (value) {
        value.bind(function (newval) {
            $('#woocommerce-thank-you-page-coupon-scissors-color-css').html('.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-wrap:before{color:' + newval + ';}');
        })
    })
    wp.customize('woo_thank_you_page_params[coupon_email_enable]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                $('.woocommerce-thank-you-page-coupon__code-email').removeClass('woocommerce-thank-you-page-hidden');
            } else {
                $('.woocommerce-thank-you-page-coupon__code-email').addClass('woocommerce-thank-you-page-hidden');
            }
        })
    })
    wp.customize('woo_thank_you_page_params[coupon_message]', function (value) {
        value.bind(function (newval) {
            if (shortcodes['order_number']) {
                for (let i in shortcodes) {
                    let reg_string = '{' + i + '}';
                    let reg = new RegExp(reg_string, 'g');
                    newval = newval.replace(reg, shortcodes[i]);
                }
            }
            let coupon_code = $('.woocommerce-thank-you-page-coupon-code').val(),
                coupon_amount = $('.woocommerce-thank-you-page-coupon-amount').val(),
                coupon_date_expires = $('.woocommerce-thank-you-page-coupon-date-expires').val(),
                last_valid_date = $('.woocommerce-thank-you-page-last-valid-date').val();
            newval = newval.replace(/{coupon_code}/g, coupon_code);
            newval = newval.replace(/{coupon_amount}/g, coupon_amount);
            newval = newval.replace(/{coupon_date_expires}/g, coupon_date_expires);
            newval = newval.replace(/{last_valid_date}/g, last_valid_date);
            $('.woocommerce-thank-you-page-coupon-message>div').html(newval.replace(/\n/g, '<\/br>'));
        })
    });
    /*google map*/
    wp.customize('woo_thank_you_page_params[google_map_width]', function (value) {
        value.bind(function (newval) {
            if (newval != '0') {
                $('#woocommerce-thank-you-page-preview-google-map-width').html('#woocommerce-thank-you-page-google-map{width:' + newval + 'px}');
            } else {
                $('#woocommerce-thank-you-page-preview-google-map-width').html('#woocommerce-thank-you-page-google-map{width:100%}');
            }
        })
    });
    addPreviewControl('google_map_height', '#woocommerce-thank-you-page-google-map', 'height', 'px');
    /*custom css*/
    wp.customize('woo_thank_you_page_params[custom_css]', function (value) {
        value.bind(function (newval) {
            $('#woocommerce-thank-you-page-preview-custom-css').html(newval);
        })
    });

    function addPreviewControl(name, element, style, suffix = '') {
        wp.customize('woo_thank_you_page_params[' + name + ']', function (value) {
            value.bind(function (newval) {
                $('#woocommerce-thank-you-page-preview-' + name.replace(/_/g, '-')).html(element + '{' + style + ':' + newval + suffix + '}');
            })
        })
    }

    function addPreviewControlSocialIcon(name) {
        wp.customize('woo_thank_you_page_params[social_icons_' + name + '_select]', function (value) {
            value.bind(function (newval) {
                jQuery('.wtyp-' + name + '-follow span').attr('class', 'wtyp-social-icon ' + newval);
            })
        })
    }

    function addPreviewControlSocialUrl(name) {
        wp.customize('woo_thank_you_page_params[social_icons_' + name + '_url]', function (value) {
            value.bind(function (newval) {
                jQuery('.wtyp-' + name + '-follow a').attr('href', newval);
                if (newval) {
                    $('.wtyp-' + name + '-follow').fadeIn(300);
                } else {
                    $('.wtyp-' + name + '-follow').fadeOut(300);
                }
            })
        })
    }
})(jQuery);

function wtypc_flex_silder() {
    jQuery('.woocommerce-thank-you-page-products-sliders').map(function () {
        let items = jQuery(this).find('.woocommerce-thank-you-page-products-content-item').length;
        let data = jQuery(this).find('.woocommerce-thank-you-page-products-content').data();
        jQuery(this).vi_flexslider({
            namespace: "woocommerce-thank-you-page-customizer-",
            selector: '.woocommerce-thank-you-page-products-content .woocommerce-thank-you-page-products-content-item',
            animation: "slide",
            animationLoop: data['slider_loop'] == 1 ? true : false,
            itemWidth: 145,
            itemMargin: 12,
            controlNav: false,
            maxItems: data['wtypc_columns'],
            reverse: false,
            slideshow: data['slider_slideshow'] == 1 ? true : false,
            move: data['slider_move'],
            touch: true,
            slideshowSpeed: data['slider_slideshow_speed']
        })
    })
}

function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function wtypc_enable_scroll() {
    let scrollTop = parseInt(jQuery('html').css('top'));
    jQuery('html').removeClass('wtypc-noscroll');
    jQuery('html,body').scrollTop(-scrollTop);
}

function wtypc_disable_scroll() {
    if (jQuery(document).height() > jQuery(window).height()) {
        let scrollTop = (jQuery('html').scrollTop()) ? jQuery('html').scrollTop() : jQuery('body').scrollTop(); // Works for Chrome, Firefox, IE...
        jQuery('html').addClass('wtypc-noscroll').css('top', -scrollTop);
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