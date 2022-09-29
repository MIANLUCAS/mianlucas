jQuery(document).ready(function ($) {
    'use strict';
    $('.vi-ui.vi-ui-coupon.tabular.menu .item').vi_tab();
    $('.vi-ui.vi-ui-main.tabular.menu .item').vi_tab({
        history: true,
        historyType: 'hash'
    });

    /*Setup tab*/
    let tabs,
        tabEvent = false,
        initialTab = 'general',
        navSelector = '.vi-ui.vi-ui-main.menu',
        navFilter = function (el) {
            // return $(el).attr('href').replace(/^#/, '');
        },
        panelSelector = '.vi-ui.vi-ui-main.tab',
        panelFilter = function () {
            $(panelSelector + ' a').filter(function () {
                return $(navSelector + ' a[title=' + $(this).attr('title') + ']').size() != 0;
            });
        };

    // Initializes plugin features
    $.address.strict(false).wrap(true);

    if ($.address.value() == '') {
        $.address.history(false).value(initialTab).history(true);
    }

    // Address handler
    $.address.init(function (event) {

        // Adds the ID in a lazy manner to prevent scrolling
        $(panelSelector).attr('id', initialTab);

        panelFilter();

        // Tabs setup
        tabs = $('.vi-ui.vi-ui-main.menu')
            .vi_tab({
                history: true,
                historyType: 'hash'
            })

        // Enables the plugin for all the tabs
        $(navSelector + ' a').on('click',function (event) {
            tabEvent = true;
            // $.address.value(navFilter(event.target));
            tabEvent = false;
            return true;
        });

    });
    $('.ui-sortable').sortable({
        placeholder: 'wtyp-place-holder',
    });
    $('.vi-ui.checkbox').checkbox();
    $('.vi-ui.dropdown').dropdown();

    handleDropdownSelect();
    handleReadonlyMain();

    function handleReadonlyMain() {
        $('.wtyp-coupon-content').map(function () {
            if ($(this).find('[name="coupon_type[]"]').val() == 'unique') {
                $(this).find('[name="coupon_unique_discount_type[]"]').parent().removeClass('disabled');
                $(this).find('[name="coupon_unique_amount[]"]').attr('readonly', false).removeClass('wtyp-readonly-item');
            } else {
                $(this).find('[name="coupon_unique_discount_type[]"]').parent().addClass('disabled');
                $(this).find('[name="coupon_unique_amount[]"]').attr('readonly', 'readonly').addClass('wtyp-readonly-item');
            }
        });
    }

    function handleDropdownSelect() {
        $('.coupon-select').dropdown({
            onChange: function (val) {
                let modal = $('.wtyp-modal-table');
                let coupon_unique = $('.coupon-unique');
                switch (val) {
                    case 'unique':
                        $('.coupon-existing').hide();
                        // modal.find('.vi-ui.dropdown').not('.coupon-select').removeClass('disabled');
                        // modal.find('input').not('#coupon_unique_email_restrictions').attr('readonly', false);
                        // modal.find('.search-product').prop('disabled', false);
                        // modal.find('.search-category').prop('disabled', false);
                        // coupon_unique.css({opacity: '1'});
                        coupon_unique.show();
                        break;
                    case 'existing':
                        $('.coupon-existing').show();
                        // modal.find('.vi-ui.dropdown').not('.coupon-select').addClass('disabled');
                        // modal.find('input').not('#coupon_unique_email_restrictions').attr('readonly', 'readonly');
                        // modal.find('.search-product').prop('disabled', true);
                        // modal.find('.search-category').prop('disabled', true);
                        // coupon_unique.css({opacity: '.4'});
                        coupon_unique.hide();
                        break;
                    default:
                }
            }
        });

    }

    /*ajax search*/
    $(".search-product-parent").select2({
        closeOnSelect: false,
        placeholder: "Please fill in your  product title",
        ajax: {
            url: "admin-ajax.php?action=wtyp_search_product_parent",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 2
    });
    $(".search-product").select2({
        closeOnSelect: false,
        placeholder: "Please fill in your  product title",
        ajax: {
            url: "admin-ajax.php?action=wtyp_search_product",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 2
    });
    $(".search-category").select2({
        closeOnSelect: false,
        placeholder: "Please fill in your category title",
        ajax: {
            url: "admin-ajax.php?action=wtyp_search_cate",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 2
    });
    $(".search-coupon").select2({
        placeholder: "Type coupon code here",
        ajax: {
            url: "admin-ajax.php?action=wtyp_search_coupon",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 2
    });
    $('#existing_coupon').on("select2:selecting", function (e) {
        $('#coupon_unique_amount').val(e.params.args.data.coupon_data.coupon_amount);
        $('#coupon_unique_discount_type').parent().dropdown('set selected', e.params.args.data.coupon_data.coupon_discount_type);
    });
    $('body').on('click',function () {
        $('.iris-picker').hide();
    });
    $('body').on('click', '.wtyp-coupon-settings-action-clone', function () {
        let current = $(this).parent().parent();
        let newRow = current.clone();
        newRow.find('.vi-ui.checkbox').unbind().checkbox();
        for (let i = 0; i < newRow.find('.vi-ui.dropdown').length; i++) {
            let selected = current.find('.vi-ui.dropdown').eq(i).dropdown('get value');
            newRow.find('.vi-ui.dropdown').eq(i).dropdown('set selected', selected);
        }
        newRow.insertAfter(current);
    });
    $('body').on('click', '.wtyp-coupon-settings-action-remove', function () {
        let current = $(this).parent().parent();
        if ($('.wtyp-coupon-content').length == 1) {
            alert('Can not remove last item.');
        } else {
            if (confirm('Do you really want to remove this item?')) {

                current.remove();
            }
        }
        return false;
    });
    $('body').on('click', '.wtyp-coupon-settings-action-edit', function () {
        wtypc_disable_scroll()
        let modal = $('.wtyp-modal-table-container');
        let modal_table = modal.find('.wtyp-modal-table');
        let coupon_unique = $('.coupon-unique');

        modal.removeClass('wtyp-hidden-item');
        $(this).parent().parent().addClass('wtyp-coupon-content-editing');
        let current = $('.wtyp-coupon-content-editing');
        $('#coupon_type').parent().dropdown('set selected', current.find('[name="coupon_type[]"]').val());

        let existing_coupon = current.find('[name="existing_coupon[]"]').val();
        $('#existing_coupon').val(null).trigger('change');
        if (existing_coupon) {
            if ($('#existing_coupon').find("option[value='" + existing_coupon + "']").length) {
                $('#existing_coupon').val(existing_coupon).trigger('change');
            } else {
                let data = {
                    id: existing_coupon,
                    text: current.find('[name="existing_coupon[]"]').data()['coupon_code']
                };

                let newOption = new Option(data.text, data.id, false, false);
                $('#existing_coupon').append(newOption).trigger('change');
            }
        }

        $('#coupon_unique_discount_type').parent().dropdown('set selected', current.find('[name="coupon_unique_discount_type[]"]').val());
        $('#coupon_unique_prefix').val(current.find('[name="coupon_unique_prefix[]"]').val());
        $('#coupon_unique_amount').val(current.find('[name="coupon_unique_amount[]"]').val());
        $('#coupon_unique_date_expires').val(current.find('[name="coupon_unique_date_expires[]"]').val());
        $('#coupon_unique_email_restrictions').prop('checked', current.find('[name="coupon_unique_email_restrictions[]"]').val() == 1 ? true : false);
        $('#coupon_unique_free_shipping').prop('checked', current.find('[name="coupon_unique_free_shipping[]"]').val() == 1 ? true : false);
        $('#coupon_unique_minimum_amount').val(current.find('[name="coupon_unique_minimum_amount[]"]').val());
        $('#coupon_unique_maximum_amount').val(current.find('[name="coupon_unique_maximum_amount[]"]').val());
        $('#coupon_unique_individual_use').prop('checked', current.find('[name="coupon_unique_individual_use[]"]').val() == 1 ? true : false);
        $('#coupon_unique_exclude_sale_items').prop('checked', current.find('[name="coupon_unique_exclude_sale_items[]"]').val() == 1 ? true : false);

        let coupon_unique_product_ids = JSON.parse(current.find('[name="coupon_unique_product_ids[]"]').val().toString());
        let coupon_unique_product_ids_title = current.find('[name="coupon_unique_product_ids[]"]').data()['product_title'];
        $('#coupon_unique_product_ids').val(null).trigger('change');
        if (coupon_unique_product_ids.length) {
            for (let i = 0; i < coupon_unique_product_ids.length; i++) {
                if ($('#coupon_unique_product_ids').find("option[value='" + coupon_unique_product_ids[i] + "']").length) {
                    $('#coupon_unique_product_ids').val(coupon_unique_product_ids[i]).trigger('change');
                } else {
                    let data = {
                        id: coupon_unique_product_ids[i],
                        text: coupon_unique_product_ids_title[i]
                    };

                    let newOption = new Option(data.text, data.id, false, false);
                    $('#coupon_unique_product_ids').append(newOption).trigger('change');
                }

            }
            $('#coupon_unique_product_ids').val(coupon_unique_product_ids).trigger('change');
        }

        let coupon_unique_excluded_product_ids = JSON.parse(current.find('[name="coupon_unique_excluded_product_ids[]"]').val().toString());
        let coupon_unique_excluded_product_ids_title = current.find('[name="coupon_unique_excluded_product_ids[]"]').data()['product_title'];
        $('#coupon_unique_excluded_product_ids').val(null).trigger('change');
        if (coupon_unique_excluded_product_ids.length) {
            for (let i = 0; i < coupon_unique_excluded_product_ids.length; i++) {
                if ($('#coupon_unique_excluded_product_ids').find("option[value='" + coupon_unique_excluded_product_ids[i] + "']").length) {
                    $('#coupon_unique_excluded_product_ids').val(coupon_unique_excluded_product_ids[i]).trigger('change');
                } else {
                    let data = {
                        id: coupon_unique_excluded_product_ids[i],
                        text: coupon_unique_excluded_product_ids_title[i]
                    };

                    let newOption = new Option(data.text, data.id, false, false);
                    $('#coupon_unique_excluded_product_ids').append(newOption).trigger('change');
                }

            }
            $('#coupon_unique_excluded_product_ids').val(coupon_unique_excluded_product_ids).trigger('change');
        }

        let coupon_unique_product_categories = JSON.parse(current.find('[name="coupon_unique_product_categories[]"]').val().toString());
        let coupon_unique_product_categories_title = current.find('[name="coupon_unique_product_categories[]"]').data()['product_title'];
        $('#coupon_unique_product_categories').val(null).trigger('change');
        if (coupon_unique_product_categories.length) {
            for (let i = 0; i < coupon_unique_product_categories.length; i++) {
                if ($('#coupon_unique_product_categories').find("option[value='" + coupon_unique_product_categories[i] + "']").length) {
                    $('#coupon_unique_product_categories').val(coupon_unique_product_categories[i]).trigger('change');
                } else {
                    let data = {
                        id: coupon_unique_product_categories[i],
                        text: coupon_unique_product_categories_title[i]
                    };

                    let newOption = new Option(data.text, data.id, false, false);
                    $('#coupon_unique_product_categories').append(newOption).trigger('change');
                }

            }
            $('#coupon_unique_product_categories').val(coupon_unique_product_categories).trigger('change');
        }
        let coupon_unique_excluded_product_categories = JSON.parse(current.find('[name="coupon_unique_excluded_product_categories[]"]').val().toString());
        let coupon_unique_excluded_product_categories_title = current.find('[name="coupon_unique_excluded_product_categories[]"]').data()['product_title'];
        $('#coupon_unique_excluded_product_categories').val(null).trigger('change');
        if (coupon_unique_excluded_product_categories.length) {
            for (let i = 0; i < coupon_unique_excluded_product_categories.length; i++) {
                if ($('#coupon_unique_excluded_product_categories').find("option[value='" + coupon_unique_excluded_product_categories[i] + "']").length) {
                    $('#coupon_unique_excluded_product_categories').val(coupon_unique_excluded_product_categories[i]).trigger('change');
                } else {
                    let data = {
                        id: coupon_unique_excluded_product_categories[i],
                        text: coupon_unique_excluded_product_categories_title[i]
                    };

                    let newOption = new Option(data.text, data.id, false, false);
                    $('#coupon_unique_excluded_product_categories').append(newOption).trigger('change');
                }

            }
            $('#coupon_unique_excluded_product_categories').val(coupon_unique_excluded_product_categories).trigger('change');
        }
        $('#coupon_unique_usage_limit').val(current.find('[name="coupon_unique_usage_limit[]"]').val());
        $('#coupon_unique_limit_usage_to_x_items').val(current.find('[name="coupon_unique_limit_usage_to_x_items[]"]').val());
        $('#coupon_unique_usage_limit_per_user').val(current.find('[name="coupon_unique_usage_limit_per_user[]"]').val());
        switch ($('#coupon_type').val()) {
            case 'unique':
                $('.coupon-existing').hide();
                // modal_table.find('.vi-ui.dropdown').not('.coupon-select').removeClass('disabled');
                // modal_table.find('input').not('#coupon_unique_email_restrictions').attr('readonly', false);
                // modal_table.find('.search-product').prop('disabled', false);
                // modal_table.find('.search-category').prop('disabled', false);
                // coupon_unique.css({opacity: '1'});
                coupon_unique.show();
                break;
            case 'existing':
                $('.coupon-existing').show();
                // modal_table.find('.vi-ui.dropdown').not('.coupon-select').addClass('disabled');
                // modal_table.find('input').not('#coupon_unique_email_restrictions').attr('readonly', 'readonly');
                // modal_table.find('.search-product').prop('disabled', true);
                // modal_table.find('.search-category').prop('disabled', true);
                // coupon_unique.css({opacity: '.4'});
                coupon_unique.hide();
                break;
            default:
        }
        /*coupon rule*/
        $('#coupon_rule_min_total').val(current.find('[name="coupon_rule_min_total[]"]').val());
        $('#coupon_rule_max_total').val(current.find('[name="coupon_rule_max_total[]"]').val());
        let coupon_rule_product_ids = JSON.parse(current.find('[name="coupon_rule_product_ids[]"]').val().toString());
        let coupon_rule_product_ids_title = current.find('[name="coupon_rule_product_ids[]"]').data()['product_title'];
        $('#coupon_rule_product_ids').val(null).trigger('change');
        if (coupon_rule_product_ids.length) {
            for (let i = 0; i < coupon_rule_product_ids.length; i++) {
                if ($('#coupon_rule_product_ids').find("option[value='" + coupon_rule_product_ids[i] + "']").length) {
                    $('#coupon_rule_product_ids').val(coupon_rule_product_ids[i]).trigger('change');
                } else {
                    let data = {
                        id: coupon_rule_product_ids[i],
                        text: coupon_rule_product_ids_title[i]
                    };

                    let newOption = new Option(data.text, data.id, false, false);
                    $('#coupon_rule_product_ids').append(newOption).trigger('change');
                }

            }
            $('#coupon_rule_product_ids').val(coupon_rule_product_ids).trigger('change');
        }

        let coupon_rule_excluded_product_ids = JSON.parse(current.find('[name="coupon_rule_excluded_product_ids[]"]').val().toString());
        let coupon_rule_excluded_product_ids_title = current.find('[name="coupon_rule_excluded_product_ids[]"]').data()['product_title'];
        $('#coupon_rule_excluded_product_ids').val(null).trigger('change');
        if (coupon_rule_excluded_product_ids.length) {
            for (let i = 0; i < coupon_rule_excluded_product_ids.length; i++) {
                if ($('#coupon_rule_excluded_product_ids').find("option[value='" + coupon_rule_excluded_product_ids[i] + "']").length) {
                    $('#coupon_rule_excluded_product_ids').val(coupon_rule_excluded_product_ids[i]).trigger('change');
                } else {
                    let data = {
                        id: coupon_rule_excluded_product_ids[i],
                        text: coupon_rule_excluded_product_ids_title[i]
                    };

                    let newOption = new Option(data.text, data.id, false, false);
                    $('#coupon_rule_excluded_product_ids').append(newOption).trigger('change');
                }

            }
            $('#coupon_rule_excluded_product_ids').val(coupon_rule_excluded_product_ids).trigger('change');
        }

        let coupon_rule_product_categories = JSON.parse(current.find('[name="coupon_rule_product_categories[]"]').val().toString());
        let coupon_rule_product_categories_title = current.find('[name="coupon_rule_product_categories[]"]').data()['product_title'];
        $('#coupon_rule_product_categories').val(null).trigger('change');
        if (coupon_rule_product_categories.length) {
            for (let i = 0; i < coupon_rule_product_categories.length; i++) {
                if ($('#coupon_rule_product_categories').find("option[value='" + coupon_rule_product_categories[i] + "']").length) {
                    $('#coupon_rule_product_categories').val(coupon_rule_product_categories[i]).trigger('change');
                } else {
                    let data = {
                        id: coupon_rule_product_categories[i],
                        text: coupon_rule_product_categories_title[i]
                    };

                    let newOption = new Option(data.text, data.id, false, false);
                    $('#coupon_rule_product_categories').append(newOption).trigger('change');
                }

            }
            $('#coupon_rule_product_categories').val(coupon_rule_product_categories).trigger('change');
        }
        let coupon_rule_excluded_product_categories = JSON.parse(current.find('[name="coupon_rule_excluded_product_categories[]"]').val().toString());
        let coupon_rule_excluded_product_categories_title = current.find('[name="coupon_rule_excluded_product_categories[]"]').data()['product_title'];
        $('#coupon_rule_excluded_product_categories').val(null).trigger('change');
        if (coupon_rule_excluded_product_categories.length) {
            for (let i = 0; i < coupon_rule_excluded_product_categories.length; i++) {
                if ($('#coupon_rule_excluded_product_categories').find("option[value='" + coupon_rule_excluded_product_categories[i] + "']").length) {
                    $('#coupon_rule_excluded_product_categories').val(coupon_rule_excluded_product_categories[i]).trigger('change');
                } else {
                    let data = {
                        id: coupon_rule_excluded_product_categories[i],
                        text: coupon_rule_excluded_product_categories_title[i]
                    };

                    let newOption = new Option(data.text, data.id, false, false);
                    $('#coupon_rule_excluded_product_categories').append(newOption).trigger('change');
                }

            }
            $('#coupon_rule_excluded_product_categories').val(coupon_rule_excluded_product_categories).trigger('change');
        }
        handleDropdownSelect();
    });
    $('.wtyp-modal-table-button-ok').on('click', function (e) {
        e.stopPropagation();
        if ($('#coupon_type').val() == 'existing' && $('#existing_coupon').val() == null) {
            alert('Please select an existing coupon');
            return false;
        }
        let current = $('.wtyp-coupon-content-editing');
        current.find('[name="coupon_type[]"]').parent().dropdown('set selected', $('#coupon_type').val());
        current.find('[name="existing_coupon[]"]').val($('#existing_coupon').val());
        current.find('[name="coupon_unique_discount_type[]"]').parent().dropdown('set selected', $('#coupon_unique_discount_type').val());
        current.find('[name="coupon_unique_prefix[]"]').val($('#coupon_unique_prefix').val());
        current.find('[name="coupon_unique_amount[]"]').val($('#coupon_unique_amount').val());
        current.find('[name="coupon_unique_date_expires[]"]').val($('#coupon_unique_date_expires').val());

        current.find('[name="coupon_unique_email_restrictions[]"]').val($('#coupon_unique_email_restrictions').prop('checked') == true ? 1 : 0);
        current.find('[name="coupon_unique_free_shipping[]"]').val($('#coupon_unique_free_shipping').prop('checked') == true ? 1 : 0);

        current.find('[name="coupon_unique_minimum_amount[]"]').val($('#coupon_unique_minimum_amount').val());
        current.find('[name="coupon_unique_maximum_amount[]"]').val($('#coupon_unique_maximum_amount').val());
        current.find('[name="coupon_unique_individual_use[]"]').val($('#coupon_unique_individual_use').prop('checked') == true ? 1 : 0);
        current.find('[name="coupon_unique_exclude_sale_items[]"]').val($('#coupon_unique_exclude_sale_items').prop('checked') == true ? 1 : 0);
        /*product ids*/
        let coupon_unique_product_ids = $('#coupon_unique_product_ids').select2('data'),
            temp_coupon_unique_product_ids = [],
            temp_coupon_unique_product_title = [];
        if (coupon_unique_product_ids.length) {
            for (let i = 0; i < coupon_unique_product_ids.length; i++) {
                temp_coupon_unique_product_ids.push(coupon_unique_product_ids[i]['id']);
                temp_coupon_unique_product_title.push(coupon_unique_product_ids[i]['text']);
            }
        }
        current.find('[name="coupon_unique_product_ids[]"]').val(JSON.stringify(temp_coupon_unique_product_ids)).data()['product_title'] = temp_coupon_unique_product_title;

        /*excluded product ids*/
        let coupon_unique_excluded_product_ids = $('#coupon_unique_excluded_product_ids').select2('data'),
            temp_coupon_unique_excluded_product_ids = [],
            temp_coupon_unique_excluded_product_title = [];
        if (coupon_unique_excluded_product_ids.length) {
            for (let i = 0; i < coupon_unique_excluded_product_ids.length; i++) {
                temp_coupon_unique_excluded_product_ids.push(coupon_unique_excluded_product_ids[i]['id']);
                temp_coupon_unique_excluded_product_title.push(coupon_unique_excluded_product_ids[i]['text']);
            }
        }
        current.find('[name="coupon_unique_excluded_product_ids[]"]').val(JSON.stringify(temp_coupon_unique_excluded_product_ids)).data()['product_title'] = temp_coupon_unique_excluded_product_title;

        /*product cate*/
        let coupon_unique_product_categories = $('#coupon_unique_product_categories').select2('data'),
            temp_coupon_unique_product_categories = [],
            temp_coupon_unique_product_categories_title = [];
        if (coupon_unique_product_categories.length) {
            for (let i = 0; i < coupon_unique_product_categories.length; i++) {
                temp_coupon_unique_product_categories.push(coupon_unique_product_categories[i]['id']);
                temp_coupon_unique_product_categories_title.push(coupon_unique_product_categories[i]['text']);
            }
        }
        current.find('[name="coupon_unique_product_categories[]"]').val(JSON.stringify(temp_coupon_unique_product_categories)).data()['product_title'] = temp_coupon_unique_product_categories_title;

        /*excluded product cate*/
        let coupon_unique_excluded_product_categories = $('#coupon_unique_excluded_product_categories').select2('data'),
            temp_coupon_unique_excluded_product_categories = [],
            temp_coupon_unique_excluded_product_categories_title = [];
        if (coupon_unique_excluded_product_categories.length) {
            for (let i = 0; i < coupon_unique_excluded_product_categories.length; i++) {
                temp_coupon_unique_excluded_product_categories.push(coupon_unique_excluded_product_categories[i]['id']);
                temp_coupon_unique_excluded_product_categories_title.push(coupon_unique_excluded_product_categories[i]['text']);
            }
        }
        current.find('[name="coupon_unique_excluded_product_categories[]"]').val(JSON.stringify(temp_coupon_unique_excluded_product_categories)).data()['product_title'] = temp_coupon_unique_excluded_product_categories_title;

        current.find('[name="coupon_unique_usage_limit[]"]').val($('#coupon_unique_usage_limit').val());
        current.find('[name="coupon_unique_limit_usage_to_x_items[]"]').val($('#coupon_unique_limit_usage_to_x_items').val());
        current.find('[name="coupon_unique_usage_limit_per_user[]"]').val($('#coupon_unique_usage_limit_per_user').val());

        /*coupon rule*/
        /*product ids*/
        let coupon_rule_product_ids = $('#coupon_rule_product_ids').select2('data'),
            temp_coupon_rule_product_ids = [],
            temp_coupon_rule_product_title = [];
        if (coupon_rule_product_ids.length) {
            for (let i = 0; i < coupon_rule_product_ids.length; i++) {
                temp_coupon_rule_product_ids.push(coupon_rule_product_ids[i]['id']);
                temp_coupon_rule_product_title.push(coupon_rule_product_ids[i]['text']);
            }
        }
        current.find('[name="coupon_rule_product_ids[]"]').val(JSON.stringify(temp_coupon_rule_product_ids)).data()['product_title'] = temp_coupon_rule_product_title;

        /*excluded product ids*/
        let coupon_rule_excluded_product_ids = $('#coupon_rule_excluded_product_ids').select2('data'),
            temp_coupon_rule_excluded_product_ids = [],
            temp_coupon_rule_excluded_product_title = [];
        if (coupon_rule_excluded_product_ids.length) {
            for (let i = 0; i < coupon_rule_excluded_product_ids.length; i++) {
                temp_coupon_rule_excluded_product_ids.push(coupon_rule_excluded_product_ids[i]['id']);
                temp_coupon_rule_excluded_product_title.push(coupon_rule_excluded_product_ids[i]['text']);
            }
        }
        current.find('[name="coupon_rule_excluded_product_ids[]"]').val(JSON.stringify(temp_coupon_rule_excluded_product_ids)).data()['product_title'] = temp_coupon_rule_excluded_product_title;

        /*product cate*/
        let coupon_rule_product_categories = $('#coupon_rule_product_categories').select2('data'),
            temp_coupon_rule_product_categories = [],
            temp_coupon_rule_product_categories_title = [];
        if (coupon_rule_product_categories.length) {
            for (let i = 0; i < coupon_rule_product_categories.length; i++) {
                temp_coupon_rule_product_categories.push(coupon_rule_product_categories[i]['id']);
                temp_coupon_rule_product_categories_title.push(coupon_rule_product_categories[i]['text']);
            }
        }
        current.find('[name="coupon_rule_product_categories[]"]').val(JSON.stringify(temp_coupon_rule_product_categories)).data()['product_title'] = temp_coupon_rule_product_categories_title;

        /*excluded product cate*/
        let coupon_rule_excluded_product_categories = $('#coupon_rule_excluded_product_categories').select2('data'),
            temp_coupon_rule_excluded_product_categories = [],
            temp_coupon_rule_excluded_product_categories_title = [];
        if (coupon_rule_excluded_product_categories.length) {
            for (let i = 0; i < coupon_rule_excluded_product_categories.length; i++) {
                temp_coupon_rule_excluded_product_categories.push(coupon_rule_excluded_product_categories[i]['id']);
                temp_coupon_rule_excluded_product_categories_title.push(coupon_rule_excluded_product_categories[i]['text']);
            }
        }
        current.find('[name="coupon_rule_excluded_product_categories[]"]').val(JSON.stringify(temp_coupon_rule_excluded_product_categories)).data()['product_title'] = temp_coupon_rule_excluded_product_categories_title;

        current.find('[name="coupon_rule_min_total[]"]').val($('#coupon_rule_min_total').val());
        current.find('[name="coupon_rule_max_total[]"]').val($('#coupon_rule_max_total').val());
        $('.wtyp-modal-table-overlay').trigger('click');
        handleReadonlyMain();
        return false;
    });
    $('.wtyp-modal-table-button-cancel').on('click', function (e) {
        e.stopPropagation();
        $('.wtyp-modal-table-overlay').trigger('click');
        return false;
    });
    $('.wtyp-modal-table-overlay').on('click', function () {
        wtypc_enable_scroll()
        $('.wtyp-modal-table-container').addClass('wtyp-hidden-item');
        $('.wtyp-coupon-content').removeClass('wtyp-coupon-content-editing');
    })
    /*email shortcodes list*/
    $('.woocommerce-thank-you-page-available-shortcodes-shortcut').on('click', function () {
        wtypc_disable_scroll()
        $('.woocommerce-thank-you-page-available-shortcodes-container').removeClass('woocommerce-thank-you-page-hidden');
    });
    $('.woocommerce-thank-you-page-available-shortcodes-items-close').on('click', function () {
        $('.woocommerce-thank-you-page-available-shortcodes-overlay').trigger('click');
    });
    $('.woocommerce-thank-you-page-available-shortcodes-overlay').on('click', function () {
        wtypc_enable_scroll()
        $('.woocommerce-thank-you-page-available-shortcodes-container').addClass('woocommerce-thank-you-page-hidden');
    });
    $('.woocommerce-thank-you-page-available-shortcodes-item-copy').on('click', function () {
        $(this).parent().find('input').select();
        document.execCommand("copy");
    });
    /**
     * Start Get download key
     */
    jQuery('.villatheme-get-key-button').one('click', function (e) {
        let v_button = jQuery(this);
        v_button.addClass('loading');
        let data = v_button.data();
        let item_id = data.id;
        let app_url = data.href;
        let main_domain = window.location.hostname;
        main_domain = main_domain.toLowerCase();
        let popup_frame;
        e.preventDefault();
        let download_url = v_button.attr('data-download');
        popup_frame = window.open(app_url, "myWindow", "width=380,height=600");
        window.addEventListener('message', function (event) {
            /*Callback when data send from child popup*/
            let obj = jQuery.parseJSON(event.data);
            let update_key = '';
            let message = obj.message;
            let support_until = '';
            let check_key = '';
            if (obj['data'].length > 0) {
                for (let i = 0; i < obj['data'].length; i++) {
                    if (obj['data'][i].id == item_id && (obj['data'][i].domain == main_domain || obj['data'][i].domain == '' || obj['data'][i].domain == null)) {
                        if (update_key == '') {
                            update_key = obj['data'][i].download_key;
                            support_until = obj['data'][i].support_until;
                        } else if (support_until < obj['data'][i].support_until) {
                            update_key = obj['data'][i].download_key;
                            support_until = obj['data'][i].support_until;
                        }
                        if (obj['data'][i].domain == main_domain) {
                            update_key = obj['data'][i].download_key;
                            break;
                        }
                    }
                }
                if (update_key) {
                    check_key = 1;
                    jQuery('.villatheme-autoupdate-key-field').val(update_key);
                }
            }
            v_button.removeClass('loading');
            if (check_key) {
                jQuery('<p><strong>' + message + '</strong></p>').insertAfter(".villatheme-autoupdate-key-field");
                jQuery(v_button).closest('form').submit();
            } else {
                jQuery('<p><strong> Your key is not found. Please contact support@villatheme.com </strong></p>').insertAfter(".villatheme-autoupdate-key-field");
            }
        });
    });
    /**
     * End get download key
     */
    /*preview email*/
    $('.preview-emails-html-overlay').on('click', function () {
        $('.preview-emails-html-container').addClass('preview-html-hidden');
        wtypc_enable_scroll()
    })
    $('.woocommerce-thank-you-page-preview-emails-button').on('click', function () {
        $(this).html('Please wait...');
        let language = jQuery(this).data('wtypc_language');
        console.log('language: '+language)
        $.ajax({
            url: wtypc_params_admin.url,
            type: 'GET',
            dataType: 'JSON',
            data: {
                action: 'wtypc_preview_emails',
                heading: $('#coupon-email-heading'+ language).val(),
                content: tinyMCE.get('coupon_email_content'+ language) ? tinyMCE.get('coupon_email_content'+ language).getContent() : $('#coupon_email_content'+ language).val(),
            },
            success: function (response) {
                $('.woocommerce-thank-you-page-preview-emails-button').html('Preview emails');
                if (response) {
                    $('.preview-emails-html').html(response.html);
                    wtypc_disable_scroll()
                    $('.preview-emails-html-container').removeClass('preview-html-hidden');
                    if (response.css) {
                        $('#woocommerce-thank-you-page-admin-inline-css').html(response.css);
                    }
                }
            },
            error: function (err) {
                $('.woocommerce-thank-you-page-preview-emails-button').html('Preview emails');
            }
        })
    });

    function wtypc_enable_scroll() {
        let scrollTop = parseInt($('html').css('top'));
        $('html').removeClass('wtypc-noscroll');
        $('html,body').scrollTop(-scrollTop);
    }

    function wtypc_disable_scroll() {
        if ($(document).height() > $(window).height()) {
            let scrollTop = ($('html').scrollTop()) ? $('html').scrollTop() : $('body').scrollTop(); // Works for Chrome, Firefox, IE...
            $('html').addClass('wtypc-noscroll').css('top', -scrollTop);
        }
    }
});
