(function($) {
    
	'use strict';
    
    function NM_Setup() {
        var self = this;
        
        // Form button callbacks
        self.callbacks = {
            do_next_step: function(btn) {
                self.stepShowNext(btn);
            },
            plugins_install: function(btn) {
                self.pluginsInstall();
            },
            content_install: function(btn) {
                self.contentInstall();
            }
        };
        
        self.init();
    }
    
    NM_Setup.prototype = {
    
        /**
         * Initialize
         */
        init: function() {
            var self = this;
            
            // Show first step
            $('.nm-setup-steps li.step:first-child').addClass('active');

            // Bind: Setup buttons
            $('.nm-setup-button').on('click', function(e) {
                e.preventDefault();
                self.setupNoticeHide();
                $('.nm-setup-view').addClass('loading');
                self.callbacks[$(this).data('callback')](this);
            });
        },

        
        /**
         * Setup notice: Show
         */
        setupNoticeShow( notice, type ) {
            var $notice = $('#nm-setup-notice');
            $notice.children('p').html('Setup error: '+notice);
            $notice.removeClass().addClass('notice notice-'+type);
            
            console.log('NM Setup - '+notice);
        },
        
        
        /**
         * Setup notice: Hide
         */
        setupNoticeHide() {
            $('#nm-setup-notice').addClass('hide');
        },
        
        
        /**
         * Step: Show next step
         */
        stepShowNext: function(btn) {
            var self = this;
            setTimeout(function() {
                // Set active breadcrumb
                var $stepActive = $('.nm-setup-steps').children('.active');
                $stepActive.removeClass('active');
                $stepActive.next().addClass('active');
                // Set active step
                var $breadcrumbsActive = $('.nm-setup-breadcrumbs').children('.active');
                if ($breadcrumbsActive.length) {
                    $breadcrumbsActive.removeClass('active');
                    $breadcrumbsActive.prev().addClass('complete');
                    $breadcrumbsActive.next().addClass('active');
                } else {
                    $('.nm-setup-breadcrumbs').children().first().addClass('active');
                }
                // Hide loader
                $('.nm-setup-view').removeClass('loading');
            }, 250);
        },
        
        
        /**
         * Step: Hide "loader"
         */
        stepHideLoader: function() {
            $('.nm-setup-view').removeClass('loading');
        },
        
        
        /**
         * Plugins: Install
         */
        pluginsInstall: function() {
            var self = this;
                
            var complete,
                items_completed = 0,
                current_item = '',
                $current_node,
                current_item_hash = '';

            function _ajaxCallback(response) {
                if (typeof response == 'object' && typeof response.message != 'undefined') {
                    $current_node.find('span').text(response.message);
                    if (typeof response.url != 'undefined') {
                        // we have an ajax url action to perform.

                        if (response.hash == current_item_hash) {
                            $current_node.find('span').text('Failed');
                            _findNext();
                        } else {
                            current_item_hash = response.hash;
                            jQuery.post(response.url, response, function(response2) {
                                _processCurrent();
                                //NM: $current_node.find('span').text(response.message + ' verifying');
                                $current_node.find('span').text('Verifying');
                            }).fail(_ajaxCallback);
                        }
                    } else if (typeof response.done != 'undefined') {
                        // Finished processing this plugin, move onto next
                        _findNext();
                    } else {
                        // Error processing this plugin
                        _findNext();
                    }
                } else {
                    // Error - try again with next plugin
                    $current_node.find('span').text('Ajax error');
                    _findNext();
                }
            };
            function _processCurrent() {
                if (current_item) {
                    // Query our ajax handler to get the ajax to send to TGM
                    // If we don't get a reply we can assume everything worked and continue onto the next one
                    jQuery.post(nm_setup_params.ajaxurl, {
                        action: 'plugins_install',
                        wpnonce: nm_setup_params.wpnonce,
                        slug: current_item
                    }, _ajaxCallback).fail(_ajaxCallback);
                }
            };
            function _findNext() {
                var do_next = false;
                if ($current_node) {
                    if (!$current_node.data('done_item')) {
                        items_completed++;
                        $current_node.data('done_item', 1);
                    }
                    $current_node.find('.spinner').css('visibility', 'hidden');
                    // NM: Hide plugin from list
                    $current_node.slideUp(200);
                }
                var $li = $('.nm-setup-tasks-plugins li');
                $li.each(function() {
                    if (current_item == '' || do_next) {
                        current_item = $(this).data('slug');
                        $current_node = $(this);
                        _processCurrent();
                        do_next = false;
                    } else if($(this).data('slug') == current_item) {
                        do_next = true;
                    }
                });
                if (items_completed >= $li.length) {
                    // finished all plugins!
                    _complete();
                }
            };
            function _complete() {
                self.stepShowNext();
            };
            
            $('.envato-wizard-plugins').addClass('installing');
            _findNext();
        },
        
        
        /**
         * Content: Install
         */
        contentInstall: function() {
            var self = this;
            
                /* Set progress messages */
            var _setProgressMessage = function(selector, message, hide) {
                    var $taskElement = $(selector);
                    $taskElement.find('span').html(message);
                    if (hide) { $taskElement.slideUp(200); }
                },
                
                /* AJAX Callback */
                _ajaxCallback = function(response, taskComplete) {
                    console.log('NM Setup - Task complete: '+taskComplete);
                    console.log('NM Setup - Response: '+response);
                },
                
                /* AJAX: Install settings */
                _ajaxInstallSettings = function() {
                    $.ajax({
                        type: 'POST',
                        url: nm_setup_params.ajaxurl,
                        data: {
                            action: 'content_install',
                            wpnonce: nm_setup_params.wpnonce,
                            task: 'settings'
                        },
                        beforeSend: function() {
                            _setProgressMessage('.nm-setup-task-settings', 'Configuring Settings...');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            self.setupNoticeShow('_ajaxInstallSettings(): '+errorThrown, 'error');
                            self.stepHideLoader();
                            _setProgressMessage('.nm-setup-task-settings', '<em class="error">Failed, please try again</em>');
                        },
                        success: function(response) {
                            _setProgressMessage('.nm-setup-task-settings', 'Done', true);
                            
                            _ajaxCallback(response, 'settings');
                            self.stepShowNext();
                        }
                    });
                },
                
                /* AJAX: Install widgets */
                _ajaxInstallWidgets = function() {
                    $.ajax({
                        type: 'POST',
                        url: nm_setup_params.ajaxurl,
                        data: {
                            action: 'content_install',
                            wpnonce: nm_setup_params.wpnonce,
                            task: 'widgets'
                        },
                        beforeSend: function() {
                            _setProgressMessage('.nm-setup-task-content', 'Importing Widgets...');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            self.setupNoticeShow('_ajaxInstallWidgets(): '+errorThrown, 'error');
                            self.stepHideLoader();
                            _setProgressMessage('.nm-setup-task-content', '<em class="error">Failed, please try again</em>');
                        },
                        success: function(response) {
                            _setProgressMessage('.nm-setup-task-content', 'Done', true);
                            
                            _ajaxCallback(response, 'widgets');
                            _ajaxInstallSettings();
                        }
                    });
                },
                
                /* AJAX: Install content */
                _ajaxInstallContent = function() {
                    $.ajax({
                        type: 'POST',
                        url: nm_setup_params.ajaxurl,
                        data: {
                            action: 'content_install',
                            wpnonce: nm_setup_params.wpnonce,
                            task: 'content'
                        },
                        beforeSend: function() {
                            _setProgressMessage('.nm-setup-task-content', 'Importing Content...');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            self.setupNoticeShow('_ajaxInstallContent(): '+errorThrown, 'error');
                            self.stepHideLoader();
                            _setProgressMessage('.nm-setup-task-content', '<em class="error">Failed, please try again</em>');
                        },
                        success: function(response) {
                            _setProgressMessage('.nm-setup-task-content', 'Done');
                            
                            _ajaxCallback(response, 'content');
                            _ajaxInstallWidgets();
                        }
                    });
                },
                
                /* AJAX: Install WooCommerce taxonomies */
                _ajaxInstallWooCommerceTaxonomies = function() {
                    $.ajax({
                        type: 'POST',
                        url: nm_setup_params.ajaxurl,
                        data: {
                            action: 'content_install',
                            wpnonce: nm_setup_params.wpnonce,
                            task: 'woocommerce_taxonomies'
                        },
                        beforeSend: function() {
                            _setProgressMessage('.nm-setup-task-content', 'Installing WooCommerce Taxonomies...');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            self.setupNoticeShow('_ajaxInstallWooCommerceTaxonomies(): '+errorThrown, 'error');
                            self.stepHideLoader();
                            _setProgressMessage('.nm-setup-task-content', '<em class="error">Failed, please try again</em>');
                        },
                        success: function(response) {
                            // Make sure WooCommerce plugin is activated before continuing
                            if (response == 'woocommerce na') {
                                console.log('NM Setup - Error: WooCommerce not installed, stopping.');
                                _setProgressMessage('.nm-setup-task-content', '<em class="error">WooCommerce plugin not activated, please try again</em>');
                                setTimeout(function() { location.reload(); }, 2000); // Reload setup page
                                return;
                            }
                            
                            _setProgressMessage('.nm-setup-task-content', 'Done');
                            
                            _ajaxCallback(response, 'woocommerce_taxonomies');
                            _ajaxInstallContent();
                        }
                    });
                };            
            
            // Start installation
            _ajaxInstallWooCommerceTaxonomies();
        }
    
    };
    
    
    $(document).ready(function() {
		new NM_Setup();
	});
	
	
})(jQuery);