(function($) {
    'use strict';

    var EasyElementsAdmin = {
        
        init: function() {
            this.initWidgetToggles();
            this.initSearchFunctionality();
            this.initBulkActions();
            this.initAllExtensions();
            this.easyEltab();
            this.easyElFilter();
            this.easyelVideoPopuo();
            this.easyelFaq();
            this.initSettings();
            this.EasyelWidgetGroupEnable();
        },

       initWidgetToggles: function() {

            $('.widget-toggle-checkbox').on('change', function () {

                var checkbox   = $(this);
                var mainWrap   = $('.easyel-plugin-main-settings-page');
                var indicator  = mainWrap.find('.easyel-saving-indicator');

                var widgetKey = checkbox.data('widget-key');
                var status    = checkbox.is(':checked') ? '1' : '0';

                if (!widgetKey) return;

                indicator.text('Saving...');
                mainWrap.addClass('is-saving');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'easy_elements_save_widget_setting',
                        widget_key: widgetKey,
                        status: status,
                        nonce: easyElementsData.widget_settings_nonce
                    },

                    success: function (response) {

                        if (response.success) {

                            indicator.text('Saved');

                            setTimeout(function () {
                                mainWrap.removeClass('is-saving');
                                indicator.text('Saving...');
                            }, 500 );

                        } else {
                            // Pro-locked items never reach this branch in
                            // normal use (click is intercepted at the
                            // upgrade-modal handler). On any other save
                            // failure just revert silently.
                            checkbox.prop('checked', !checkbox.is(':checked'));
                            mainWrap.removeClass('is-saving');
                        }
                    },

                    error: function () {
                        mainWrap.removeClass('is-saving');
                        checkbox.prop('checked', !checkbox.is(':checked'));
                    }
                });
            });




        },

        initSearchFunctionality: function() {

            function hideEmptyGroups() {
                $('.easyel-widgets-grid').each(function () {
                    var wrapper = $(this);
                    var items = wrapper.find('.easy-widget-item');

                    var visibleItems = items.filter(function () {
                        return $(this).css('display') !== 'none';
                    }).length;

                    var headingGroup = wrapper.prev('.easyel-widget-heading-group');

                    if (visibleItems === 0) {
                        headingGroup.hide();
                        wrapper.hide();
                    } else {
                        headingGroup.show();
                        wrapper.show();
                    }
                });
            }

            $('#element-search').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();

                $('.easy-widget-item').each(function() {
                    var widgetTitle = $(this).find('.widget-header strong').text().toLowerCase();
                    var widgetDesc  = $(this).find('.widget-description').text().toLowerCase();

                    if (widgetTitle.includes(searchTerm) || widgetDesc.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                hideEmptyGroups();
            });

            hideEmptyGroups();
        },


        initBulkActions: function() {
            $('#activate-all-btn').on('click', function() {
                var currentTab = $('.easyel-nav-tab-active').data('tab');
                EasyElementsAdmin.performBulkAction('activate_all', currentTab);
            });

            $('#deactivate-all-btn').on('click', function() {
                EasyElementsAdmin.performBulkAction('deactivate_all');
            });
        },

        performBulkAction: function(action) {

            var currentTab = $('.easyel-nav-tab-active').data('tab');
            var currentFilter = $('.easyel-action-btn.active').data('filter'); 
            var btn = action === 'activate_all' ? $('#activate-all-btn') : $('#deactivate-all-btn');
            var originalText = btn.text();

            let mainWrap   = $('.easyel-plugin-main-settings-page');
            let indicator  = mainWrap.find('.easyel-saving-indicator');

            btn.prop('disabled', true).text(easyElementsData.strings.processing);

            indicator.text('Saving...');
            mainWrap.addClass('is-saving');

            $.post(ajaxurl, {
                action: 'easy_elements_bulk_action',
                bulk_action: action,
                tab: currentTab,
                filter: currentFilter,
                nonce: easyElementsData.bulk_action_nonce
            })
            .done(function(response) {

                if (response.success) {

                    $('.widget-toggle-checkbox').each(function() {
                        var $checkbox = $(this);
                        let item = $checkbox.closest('.easy-widget-item');

                        if ($checkbox.data('tab') !== currentTab) {
                            return;
                        }

                        let isProWidget = item.hasClass('easyel-pro-widget');

                        if (currentFilter === 'easyel_free' && isProWidget) {
                            return; 
                        }

                        if (currentFilter === 'easyel_pro' && !isProWidget) {
                            return; 
                        }

                        // Pro-only widgets aren't registered when the
                        // premium add-on is inactive, so a bulk "activate"
                        // request to the server is rejected for them. Mirror
                        // that here: leave them unchecked rather than
                        // pretending the bulk action affected them.
                        if (isProWidget && !response.data.is_pro_active) {
                            $checkbox.prop('checked', false);
                        } else {
                            $checkbox.prop('checked', action === 'activate_all');
                        }

                    });

                    $('.easyel-group-toggle-widget').each(function() {
                        let groupCheckbox = $(this);
                        let groupSlug = groupCheckbox.data('group');
                        let groupWrapper = $('.easyel-widgets-grid[data-group="' + groupSlug + '"]');

                        let allChecked = true;
                        groupWrapper.find('.widget-toggle-checkbox').each(function() {
                            if (!$(this).prop('checked') && !$(this).closest('.easyel-widget-item').hasClass('easyel-pro-enable')) {
                                allChecked = false;
                                return false;
                            }
                        });

                        groupCheckbox.prop('checked', allChecked);
                        groupCheckbox.closest('.easyel-toggle-switch-widget').find('.easyel-enable-all-widget').text(allChecked ? 'Disable All' : 'Enable All');
                    });

                    indicator.text('Saved');

                    setTimeout(function() {
                        mainWrap.removeClass('is-saving');
                        indicator.text('Saving...');
                    }, 700);
                }
            })
            .fail(function() {
                // Error fallback
                mainWrap.removeClass('is-saving');
            })
            .always(function() {
                btn.prop('disabled', false).text(originalText);
            });
        },

        EasyelWidgetGroupEnable: function() {

            function EasyupdateEnableAllText2(groupCheckbox, groupWrapper) {
                let allChecked = true;
                let availableWidgets = groupWrapper.find('.widget-toggle-checkbox').not(':disabled').closest('.easy-widget-item').not('.easyel-pro-enable');

                if (availableWidgets.length === 0) {
                    allChecked = false;
                } else {
                    availableWidgets.each(function() {
                        let checkbox = $(this).find('.widget-toggle-checkbox');
                        if (!checkbox.prop('checked')) {
                            allChecked = false;
                            return false; 
                        }
                    });
                }

                let textSpan = groupCheckbox.closest('.easyel-toggle-switch-widget').find('.easyel-enable-all-widget');

                textSpan.text(allChecked ? 'Disable All' : 'Enable All');
                groupCheckbox.prop('checked', allChecked); 
            }

            let groupTabEnable = $(".easyel-tab-panel.widget");

            groupTabEnable.find('.easyel-group-toggle-widget').each(function() {
                let groupCheckbox = $(this);
                let groupSlug = groupCheckbox.data('group');
                let groupWrapper = $('.easyel-widgets-grid[data-group="' + groupSlug + '"]');

                EasyupdateEnableAllText2(groupCheckbox, groupWrapper);
            });

            groupTabEnable.on('change', '.easyel-group-toggle-widget', function () {

                let groupCheckbox = $(this);
                let groupSlug = groupCheckbox.data('group');
                let groupWrapper = $('.easyel-widgets-grid[data-group="' + groupSlug + '"]');
                let hiddenField = $('input.easyel-group-hidden[name="easy_element_group_' + groupSlug + '"]');

                let status = groupCheckbox.is(':checked') ? 1 : 0;

                groupWrapper.find('.widget-toggle-checkbox').each(function () {

                    let checkbox = $(this);
                    let widgetItem = checkbox.closest('.easy-widget-item');

                    if (widgetItem.hasClass('easyel-pro-enable')) return;
                    checkbox.prop('checked', status === 1);
                });


                hiddenField.val(status);

                EasyupdateEnableAllText2( groupCheckbox, groupWrapper );

                let keys = [];
                groupWrapper.find('.widget-toggle-checkbox').each(function() { keys.push($(this).data('key')); });
                if (keys.length) {
                    $.post(ajaxurl, {
                        action: 'easy_elements_bulk_group_action',
                        group: groupSlug,
                        status: status,
                        nonce: easyElementsData.bulk_action_nonce
                    });
                }
            });
        },

        initAllExtensions: function() {
            let $extensionsTab = $('.easyel-tab-panel.extensions');

            // On page load: hide settings icon for unchecked extensions
            $('.easyel-extension-toggle').each(function () {
                var $item = $(this).closest('.easyel-extension-item');
                var $gear = $item.find('.dashicons-admin-generic');
                if ($gear.length) {
                    if (!$(this).is(':checked')) {
                        $gear.hide();
                    }
                }
            });

            $('.easyel-extension-toggle').on('change', function () {
                let checkbox = $(this);
                let key = checkbox.data('key');
                let tab = checkbox.data('tab');
                let status = checkbox.is(':checked') ? 1 : 0;

                // Show/hide settings icon based on toggle
                let $item = checkbox.closest('.easyel-extension-item');
                let $gear = $item.find('.dashicons-admin-generic');
                if ($gear.length) {
                    status ? $gear.show() : $gear.hide();
                }

                let mainWrap   = $('.easyel-plugin-main-settings-page');
                let indicator  = mainWrap.find('.easyel-saving-indicator');

                indicator.text('Saving...');
                mainWrap.addClass('is-saving');

                const featureWidgetMap = {
                    enable_megamenu_builder: 'mega_menu_widget'
                };


                $.post(ajaxurl, {
                    action: 'easy_elements_save_global_extensions',
                    tab: tab,
                    key: key,
                    status: status,
                    nonce: easyElementsData.widget_settings_nonce
                })
                .done(function(response) {
                    if (response.success) {
                        indicator.text('Saved');

                        if (featureWidgetMap[key]) {
                            const widgetKey = featureWidgetMap[key];
                            const widgetEl  = $('.easy-widget-item[data-widget-key="' + widgetKey + '"]');

                            if (status === 0) {

                                widgetEl.slideUp(200, function () {
                                    $(this).remove();
                                });
                            } else {
                                indicator.text('Reloading…');

                                setTimeout(function () {
                                    location.reload();
                                }, 300);
                            }
                        }


                    } else {
                        // Pro-locked items never reach this branch in
                        // normal use (click is intercepted at the
                        // upgrade-modal handler). On any other save
                        // failure just revert silently.
                        checkbox.prop('checked', !checkbox.is(':checked'));
                        mainWrap.removeClass('is-saving');
                        return;
                    }

                    setTimeout(function() {
                        mainWrap.removeClass('is-saving');
                        indicator.text('Saving...');
                    }, 500);
                })
                .fail(function () {
                    mainWrap.removeClass('is-saving');
                    checkbox.prop('checked', !checkbox.is(':checked'));
                });
            });

            function EasyupdateEnableAllText(groupCheckbox) {
                let status = groupCheckbox.is(':checked') ? 1 : 0;
                let label = groupCheckbox.closest('.easyel-toggle-switch-extension');
                let textSpan = label.find('.easyel-enable-all');
                let groupSlug = groupCheckbox.data('group');
                let groupWrapper = $('.easyel-extension-wrapper[data-group="' + groupSlug + '"]');

                let totalItems = groupWrapper.find('.easyel-extension-item').length;
                let proItems = groupWrapper.find('.easyel-extension-item.easyel-pro-enable').length;

                if (totalItems > 0 && totalItems === proItems) {
                    groupCheckbox.prop('disabled', true); 
                    label.css({'opacity': '1','border':'none',  'cursor': 'not-allowed'});
                
                    return; 
                }

                if (status === 1) {
                    textSpan.text('Disable All');
                } else {
                    textSpan.text('Enable All');
                }
            }

            // Extension tab initialize hobar shomoy run hobe
            $extensionsTab.find('.easyel-group-toggle').each(function() {
                EasyupdateEnableAllText($(this));
            });

            $extensionsTab.on('change', '.easyel-group-toggle', function () {
                let groupCheckbox = $(this);
                let groupSlug = groupCheckbox.data('group');
                let groupWrapper = $('.easyel-extension-wrapper[data-group="' + groupSlug + '"]');
                let hiddenField = $('input.easyel-group-hidden[name="easy_element_group_' + groupSlug + '"]');

                let status = groupCheckbox.is(':checked') ? 1 : 0;

                EasyupdateEnableAllText(groupCheckbox);


                groupWrapper.find('.easyel-extension-toggle').each(function () {
                    let checkbox = $(this);
                    if (checkbox.closest('.easyel-extension-item').hasClass('easyel-pro-enable')) return;
                    checkbox.prop('checked', status === 1);

                    // Show/hide settings icon
                    let $gear = checkbox.closest('.easyel-extension-item').find('.dashicons-admin-generic');
                    if ($gear.length) {
                        status ? $gear.show() : $gear.hide();
                    }
                });

                hiddenField.val(status);

                let mainWrap   = $('.easyel-plugin-main-settings-page');
                let indicator  = mainWrap.find('.easyel-saving-indicator');

                indicator.text('Saving...');
                mainWrap.addClass('is-saving');

                let keys = [];
                groupWrapper.find('.easyel-extension-toggle').each(function() { keys.push($(this).data('key')); });
                if (keys.length) {
                    $.post(ajaxurl, {
                        action: 'easy_elements_save_global_extensions_bulk',
                        tab: 'extensions',
                        keys: keys,
                        status: status,
                        group: groupSlug, 
                        nonce: easyElementsData.widget_settings_nonce
                    }).done(function(response) {
                        if (response.success) {
                            indicator.text('Saved');
                        } else {
                            indicator.text('Failed');
                        }

                        setTimeout(function() {
                            mainWrap.removeClass('is-saving');
                            indicator.text('Saving...'); 
                        }, 500 );
                    })
                    .fail(function() {
                        indicator.text('Failed');
                        setTimeout(function() {
                            mainWrap.removeClass('is-saving');
                            indicator.text('Saving...');
                        }, 700);
                    });
                }
                else {
                    setTimeout(function() {
                        mainWrap.removeClass('is-saving');
                        indicator.text('Saving...');
                    }, 500);
                }
            });
        },

        easyEltab: function() {
            $('#toplevel_page_easy-elements-dashboard ul.wp-submenu a').on('click', function (e) {
                const href = $(this).attr('href');
                const isDashboardPage = window.location.href.includes('page=easy-elements-dashboard');

                if (isDashboardPage && href.includes('#')) {
                    e.preventDefault();

                    let tab = 'overview';
                    if (href.includes('#widget')) tab = 'widget';
                    if (href.includes('#extensions')) tab = 'extensions';
                    if (href.includes('#activate_license')) tab = 'activate_license'; 

                    activateTab(tab);
                    history.replaceState(null, null, '#' + tab);
                }
            });

            let hash = window.location.hash.substring(1);
            if (hash) activateTab(hash);
            else activateTab('overview');

            $('.easyel-nav-tab').click(function (e) {
                const tab = $(this).data('tab');

                // No data-tab → treat as a real link (e.g. Starter Templates page).
                if (!tab) return;

                e.preventDefault();
                activateTab(tab);
                history.replaceState(null, null, '#' + tab);
            });

            function activateTab(tab) {
                $('.easyel-nav-tab').removeClass('easyel-nav-tab-active');
                $('.easyel-nav-tab[data-tab="' + tab + '"]').addClass('easyel-nav-tab-active');
                $('.easyel-tab-panel').hide();
                $('#tab-' + tab).show();
                $('#toplevel_page_easy-elements-dashboard ul.wp-submenu li').removeClass('current');
                $('#toplevel_page_easy-elements-dashboard ul.wp-submenu a[href*="#' + tab + '"]').parent().addClass('current');
            }
        },

        easyElFilter: function() {
            $(".easyel-action-btn").on("click", function() {
                var filter = $(this).data("filter");
                $(".easyel-action-btn").removeClass("active");
                $(this).addClass("active");

                $(".easy-widget-item,.easyel-extension-item").each(function() {
                    var $widget = $(this);

                    if (filter === "easyel_all") $widget.show();
                    else if (filter === "easyel_free") {
                        if ($widget.hasClass("easyel-pro-widget")) $widget.hide();
                        else $widget.show();
                    } else if (filter === "easyel_pro") {
                        if ($widget.hasClass("easyel-pro-widget")) $widget.show();
                        else $widget.hide();
                    }
                });
            });
        },

        easyelVideoPopuo: function() {
            if ($(".easyel-video-popup").length ) {
                var $popup = $('#easyel-popup-video-area'),
                    $videoContainer = $popup.find('.easyel-popup-video');

                $('.easyel-video-popup').on('click', function(e){
                    e.preventDefault();
                    var videoId = $(this).attr('href').split('v=')[1].split('&')[0];
                    $videoContainer.html('<iframe src="https://www.youtube.com/embed/' + videoId + '?autoplay=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>');
                    $popup.fadeIn();
                });

                $popup.on('click', '.easyel-popup-close, #easyel-popup-video-area', function(e){
                    if($(e.target).is('.easyel-popup-close') || $(e.target).is('#easyel-popup-video-area')){
                        $popup.fadeOut();
                        $videoContainer.html('');
                    }
                });
            };
        },

        easyelFaq: function() {
            if ($(".easyel-faq-item").length ) {
                $(".easyel-faq-item.active").find(".easyel-faq-item-content").show();
                $(".easyel-faq-item-heading").click(function(){
                    var faqItem = $(this).closest(".easyel-faq-item");
                    var content = faqItem.children(".easyel-faq-item-content");

                    if(faqItem.hasClass("active")){
                        faqItem.removeClass("active");
                        content.slideUp(300);
                    } else {
                        $(".easyel-faq-item.active").removeClass("active").children(".easyel-faq-item-content").slideUp(300);
                        faqItem.addClass("active");
                        content.slideDown(300);
                    }
                });
            };
        },

        easyel_notify: function(message, type = 'success', duration = 3000) {
            const toast = document.createElement('div');
            toast.className = `easyel-toast ${type}`;
            toast.innerText = message;

            document.body.appendChild(toast);

            // Show animation
            setTimeout(() => toast.classList.add('show'), 50);

            // Auto remove after duration
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        },

        initSettings: function() {
            $('#easyel-settings-save').click(function (e) {
                e.preventDefault();

                let dataArr = $('#easyel-settings-form').serializeArray();
                let settings = {};

                dataArr.forEach(field => {
                    let name = field.name;
                    let value = field.value;

                    // Detect nested name e.g. fb_config[page_id]
                    if (name.includes('[')) {
                        let mainKey = name.split('[')[0];                     // fb_config
                        let subKey = name.match(/\[(.*?)\]/)[1];              // page_id

                        if (!settings[mainKey]) settings[mainKey] = {};       // init object
                        settings[mainKey][subKey] = value;                    // assign value
                    } else {
                        // simple field (no nested)
                        settings[name] = value;
                    }
                });
                        
                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data: {
                        action: "easyel_save_user_data",
                        security: easyElementsData.nonce,
                        settings: settings
                    },
                    beforeSend: function () {
                        $('#easyel-settings-save')
                            .text("Saving...")
                            .prop("disabled", true);
                    },
                    success: function(res){
                        $('#easyel-settings-save')
                            .text('Save Settings')
                            .prop('disabled', false);

                        if(res.success){
                            EasyElementsAdmin.easyel_notify(res.data.message, 'success');
                        } else {
                            EasyElementsAdmin.easyel_notify('Error saving settings.', 'error');
                        }
                    },
                    error: function(){
                        EasyElementsAdmin.easyel_notify('AJAX error!', 'error');
                        $('#easyel-settings-save').text('Save Settings').prop('disabled', false);
                    }
                });

            });
        },
    };

    $(document).ready(function() {
        EasyElementsAdmin.init();
    });

    window.EasyElementsAdmin = EasyElementsAdmin;

    $(document).ready(function ($) {

        function hideEmptyGroups() {

            $('.easyel-widgets-grid').each(function () {
                var wrapper = $(this);

                var items = wrapper.find('.easy-widget-item');

                var visibleItems = items.filter(function () {
                    return $(this).css('display') !== 'none';
                }).length;

                var headingGroup = wrapper.prev('.easyel-widget-heading-group');

                if (visibleItems === 0) {
                    headingGroup.hide(); 
                } else {
                    headingGroup.show(); 
                }
            });
        }

        hideEmptyGroups();

        $(document).on('click', '.easyel-action-btn', function () {
            setTimeout(hideEmptyGroups, 50);
        });

    });


    $(document).ready(function() {
       
        $('.easyel-scroll-smoother-popup-setting').on('click', function(e) {
            e.preventDefault();

            $('.easyel-smooth-scroll-popup').addClass('active');
            $('html').addClass('popup-opened');
        });

        $('.easyel-smooth-scroll-popup-close-icon').on('click', function(e) {
            e.preventDefault();
            $('.easyel-smooth-scroll-popup').removeClass('active');
            $('html').removeClass('popup-opened');
        });

        if ( $('.easyel-smooth-scroll-color').length > 0 ) {
            $('.easyel-smooth-scroll-color').wpColorPicker();
        }
        
    });

    $(document).ready(function() {
       
        $('.easyel-readingprogress-bar-popup-setting').on('click', function(e) {
            e.preventDefault();

            $('.easyel-reading-progressbar-popup').addClass('active');
            $('html').addClass('popup-opened');
        });

        $('.easyel-reading-progressbar-popup-close-icon').on('click', function(e) {
            e.preventDefault();
            $('.easyel-reading-progressbar-popup').removeClass('active');
            $('html').removeClass('popup-opened');
        });
    });

    $(document).ready(function() {
        var scrollTopColorInitialized = false;

        $('.easyel-scroll-top-popup-setting').on('click', function(e) {
            e.preventDefault();
            $('.easyel-scroll-top-popup').addClass('active');
            $('html').addClass('popup-opened');

            // Init color pickers inside popup on first open
            if (!scrollTopColorInitialized) {
                scrollTopColorInitialized = true;
                $('.easyel-scroll-top-popup .easyel-color-picker').each(function() {
                    var $input = $(this);
                    // Destroy existing wpColorPicker if any
                    if ($input.closest('.wp-picker-container').length) {
                        $input.closest('.wp-picker-container').replaceWith($input);
                    }
                    $input.wpColorPicker({
                        change: function(event, ui) {
                            $(event.target).val(ui.color.toString());
                            $('.scroll_top_popup_item_save').prop('disabled', false).val('Save Changes');
                        },
                        clear: function() {
                            $('.scroll_top_popup_item_save').prop('disabled', false).val('Save Changes');
                        }
                    });
                });
            }
        });

        $('.easyel-scroll-top-popup-close-icon').on('click', function(e) {
            e.preventDefault();
            $('.easyel-scroll-top-popup').removeClass('active');
            $('html').removeClass('popup-opened');
        });

        // Icon type toggle — show/hide custom icon / image fields
        $(document).on('change', '#scroll_top_icon_select', function() {
            var val = $(this).val();
            $('.easyel-scroll-top-custom-icon-row').toggle(val === 'custom_icon');
            $('.easyel-scroll-top-custom-image-row').toggle(val === 'custom_image');
        });

        // Image upload via WP media
        $(document).on('click', '.easyel-scroll-top-upload-btn', function(e) {
            e.preventDefault();
            var frame = wp.media({
                title: 'Select Scroll Top Image',
                multiple: false,
                library: { type: 'image' }
            });
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#scroll_top_custom_image').val(attachment.url);
                $('.easyel-scroll-top-image-preview').attr('src', attachment.url).show();
                $('.easyel-scroll-top-remove-btn').show();
                $('.scroll_top_popup_item_save').prop('disabled', false).val('Save Changes');
            });
            frame.open();
        });

        // Remove image
        $(document).on('click', '.easyel-scroll-top-remove-btn', function(e) {
            e.preventDefault();
            $('#scroll_top_custom_image').val('');
            $('.easyel-scroll-top-image-preview').attr('src', '').hide();
            $(this).hide();
            $('.scroll_top_popup_item_save').prop('disabled', false).val('Save Changes');
        });
    });

    function easyelDisplayMessage(message, type = 'success') {
        let messageBox = $('#easyel-message-box');
        
        if (type === 'success') {
            messageBox.html('<p class="easyel-saved-success-message">' + message + '</p>').fadeIn().delay(1500).fadeOut();
        } 
    }

    $(document).ready(function($){

        var $form = $('.easyel-smooth-scroll-settings-main-wrapper');
        var $saveButton = $('.smooth_scroll_popup_item_save');

        $saveButton.prop('disabled', true);

        $form.on('input change', 'input, select', function() {
            $saveButton.prop('disabled', false).val('Save Changes');
        });

        if ( $('.easyel-smooth-scroll-color').length > 0 ) {

            $('.easyel-smooth-scroll-color').wpColorPicker({
                change: function(event, ui) {
                    $saveButton.prop('disabled', false).val('Save Changes');
                },
                clear: function() {
                    $saveButton.prop('disabled', false).val('Save Changes');
                }
            });
        }

        $(document).on('click', '.smooth_scroll_popup_item_save', function(event){
            event.preventDefault();

            var saveButton = $(this);

            saveButton.prop('disabled', true).val('Saving...');

            var settings = {
                smooth_scroll_speed: $('input[name="smooth_scroll_speed"]').val(),
                smooth_scroll_normalize: $('input[name="smooth_scroll_normalize"]').is(':checked') ? 1 : 0,
                smooth_scroll_enable_mobile: $('input[name="smooth_scroll_enable_mobile"]').is(':checked') ? 1 : 0,
                smooth_scroll_disable_editor_mode: $('input[name="smooth_scroll_disable_editor_mode"]').is(':checked') ? 1 : 0,
                enable_smooth_scroller: $('input[data-key="enable_smooth_scroller"]').is(':checked') ? 1 : 0,
                smooth_scroll_width: $('input[name="smooth_scroll_width"]').val(),
                smooth_scroll_normal_color: $('input[name="smooth_scroll_normal_color"]').val(),
                smooth_scroll_highlight_color: $('input[name="smooth_scroll_highlight_color"]').val(),
            };

            // AJAX call
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'save_smooth_scroll_settings',
                    nonce: easyElementsData.nonce,
                    settings: settings
                },
                success: function(response){
                    if(response.success){
                        easyelDisplayMessage('All settings saved', 'success');
                        saveButton.val('Saved All Data').prop('disabled', true);
                    } else {
                        easyelDisplayMessage('Error saving settings', 'error');
                        saveButton.val('Save Changes').prop('disabled', false);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.log('AJAX error:', textStatus, errorThrown);
                    easyelDisplayMessage('AJAX error: ' + textStatus, 'error');
                    saveButton.val('Save Changes').prop('disabled', false);
                }
            });
        });
    });

    $(document).ready(function($){ 

        var $form = $('.easyel-reading-progressbar-settings-main-wrapper');
        var $saveButton = $('.reading_progressbar_popup_item_save');

        $saveButton.prop('disabled', true);

        // Enable save button on input/change
        $form.on('input change', 'input, select', function() {
            $saveButton.prop('disabled', false).val('Save Changes');
        });

        // Initialize color picker safely
        if ( typeof wp !== 'undefined' && wp.colorPicker ) {
            if ( $('.easyel-color-picker').length ) {
                $('.easyel-color-picker').wpColorPicker();
            }
        }

        // Save via AJAX
        $(document).on('click', '.reading_progressbar_popup_item_save', function(event){
            event.preventDefault();

            var saveButton = $(this);
            saveButton.prop('disabled', true).val('Saving...');

            var settings = {
                reading_progressbar_position: $('select[name="reading_progressbar_position"]').val(),
                reading_progressbar_display: $('input[name="reading_progressbar_display[]"]:checked').map(function(){ return this.value; }).get(),
                reading_progressbar_color: $('input[name="reading_progressbar_color"]').val(),
                reading_progressbar_height: $('input[name="reading_progressbar_height"]').val(),
                reading_progressbar_specific_page: $('select[name="reading_progressbar_specific_page[]"]').map(function(){ return $(this).val(); }).get(),
            };

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'save_reading_progressbar_settings',
                    nonce: easyElementsData.nonce,
                    settings: settings
                },
                success: function(response){
                    if(response.success){
                        easyelDisplayMessage('Settings Saved', 'success');
                        saveButton.val('Saved All Data').prop('disabled', true);
                    } else {
                        easyelDisplayMessage('Error saving settings', 'error');
                        saveButton.val('Save Changes').prop('disabled', false);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.log('AJAX error:', textStatus, errorThrown);
                    easyelDisplayMessage('AJAX error: ' + textStatus, 'error');
                    saveButton.val('Save Changes').prop('disabled', false);
                }
            });
        });
    });

    // open close popup modal 

    function easyelAdminToggleOpenProModal() {
        $('#easyel-admin-toggle-pro-upgrade-modal').addClass('active');
        $('body').addClass('easyel-admin-toggle-modal-open');
    }

    function easyelAdminToggleCloseProModal() {
        $('#easyel-admin-toggle-pro-upgrade-modal').removeClass('active');
        $('body').removeClass('easyel-admin-toggle-modal-open');
    }

    /**
     * Pro-locked items (widgets and extensions) — clicking the toggle
     * area opens the upgrade modal instead of flipping the checkbox.
     * Capture-phase + stopImmediatePropagation so the click never
     * reaches the input's native toggle or the regular change handler
     * that would otherwise fire an AJAX save.
     */
    document.addEventListener(
        'click',
        function (e) {
            var trigger = e.target && e.target.closest
                ? e.target.closest('.easyel-pro-enable .easyel-widget-card-switcher')
                : null;
            if ( ! trigger ) {
                return;
            }
            e.preventDefault();
            e.stopImmediatePropagation();
            easyelAdminToggleOpenProModal();
        },
        true
    );

    // Close modal
    $(document).on(
        'click',
        '.easyel-admin-toggle-pro-modal-close, \
        .easyel-admin-toggle-pro-modal-cancel, \
        .easyel-admin-toggle-pro-modal-overlay',
        function () {
            easyelAdminToggleCloseProModal();
        }
    );

    // reading progressbar popup color js (exclude scroll-top + preloader popups — initialized separately)
    if ( $('.easyel-color-picker').not('.easyel-scroll-top-popup .easyel-color-picker').not('.easyel-preloader-popup .easyel-color-picker').length ) {
        $('.easyel-color-picker').not('.easyel-scroll-top-popup .easyel-color-picker').not('.easyel-preloader-popup .easyel-color-picker').wpColorPicker();
    }

    if( $('.easyel-readingprogress-barselect2').length ) {
        $('.easyel-readingprogress-barselect2').select2({
            width: '100%',
            placeholder: 'Select a page',
            multiple: true,
        });
    }

    if ( $('.easyel-color-picker').not('.easyel-scroll-top-popup .easyel-color-picker').not('.easyel-preloader-popup .easyel-color-picker').length ) {
        $('.easyel-color-picker').not('.easyel-scroll-top-popup .easyel-color-picker').not('.easyel-preloader-popup .easyel-color-picker').wpColorPicker({
            change: function (event, ui) {
                var color = ui.color.toString();

                $(event.target).val(color);

                $('.reading_progressbar_popup_item_save')
                    .prop('disabled', false)
                    .val('Save Changes');
            },
            clear: function () {
                $('.reading_progressbar_popup_item_save')
                    .prop('disabled', false)
                    .val('Save Changes');
            }
        });
    }

    // Scroll Top settings save
    $(document).ready(function($){

        var $popup = $('.easyel-scroll-top-popup');
        var $scrollTopSave = $('.scroll_top_popup_item_save');

        $scrollTopSave.prop('disabled', true);

        $popup.on('input change', 'input, select', function() {
            $scrollTopSave.prop('disabled', false).val('Save Changes');
        });

        $(document).on('click', '.scroll_top_popup_item_save', function(event){
            event.preventDefault();

            var saveButton = $(this);
            saveButton.prop('disabled', true).val('Saving...');

            // Get color values from wpColorPicker hidden inputs
            var bgColor = $popup.find('input[name="scroll_top_bg_color"]').val();
            var iconColor = $popup.find('input[name="scroll_top_icon_color"]').val();

            var settings = {
                scroll_top_icon: $popup.find('select[name="scroll_top_icon"]').val(),
                scroll_top_position: $popup.find('select[name="scroll_top_position"]').val(),
                scroll_top_bg_color: bgColor || '#333333',
                scroll_top_icon_color: iconColor || '#ffffff',
                scroll_top_size: $popup.find('input[name="scroll_top_size"]').val() || '45',
                scroll_top_radius: $popup.find('input[name="scroll_top_radius"]').val() || '50',
                scroll_top_offset: $popup.find('input[name="scroll_top_offset"]').val() || '300',
                scroll_top_border_width: $popup.find('input[name="scroll_top_border_width"]').val() || '0',
                scroll_top_border_color: $popup.find('input[name="scroll_top_border_color"]').val() || '#cccccc',
                scroll_top_hover_bg_color: $popup.find('input[name="scroll_top_hover_bg_color"]').val() || '#000000',
                scroll_top_hover_icon_color: $popup.find('input[name="scroll_top_hover_icon_color"]').val() || '#ffffff',
                scroll_top_hover_border_color: $popup.find('input[name="scroll_top_hover_border_color"]').val() || '#cccccc',
                scroll_top_custom_icon: $popup.find('input[name="scroll_top_custom_icon"]').val() || '',
                scroll_top_custom_image: $popup.find('input[name="scroll_top_custom_image"]').val() || '',
            };

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'save_scroll_top_settings',
                    nonce: easyElementsData.nonce,
                    settings: settings
                },
                success: function(response){
                    if(response.success){
                        easyelDisplayMessage('Settings Saved', 'success');
                        saveButton.val('Saved All Data').prop('disabled', true);
                    } else {
                        easyelDisplayMessage('Error saving settings', 'error');
                        saveButton.val('Save Changes').prop('disabled', false);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.log('AJAX error:', textStatus, errorThrown);
                    easyelDisplayMessage('AJAX error: ' + textStatus, 'error');
                    saveButton.val('Save Changes').prop('disabled', false);
                }
            });
        });
    });

    // Preloader: open popup + init color pickers + media uploader (mirrors Scroll Top extension pattern)
    $(document).ready(function() {
        var preloaderColorInitialized = false;

        // Force the popup hidden on initial load so it never flashes before a click.
        $('.easyel-preloader-popup').removeClass('active').hide();

        function easyelPreloaderToggleFields() {
            var $popup = $('.easyel-preloader-popup');
            var style  = $popup.find('select[name="preloader_style"]').val();

            var hasSecondary = (style === 'circle' || style === 'custom_logo');
            var isCustomLogo = (style === 'custom_logo');

            $popup.find('.easyel-preloader-field-logo-only').toggle(isCustomLogo);
            $popup.find('.easyel-preloader-field-secondary-only').toggle(hasSecondary);
        }

        $(document).on('click', '.easyel-preloader-popup-setting', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('.easyel-preloader-popup').show().addClass('active');
            $('html').addClass('popup-opened');

            easyelPreloaderToggleFields();

            if (!preloaderColorInitialized && typeof $.fn.wpColorPicker === 'function') {
                preloaderColorInitialized = true;
                $('.easyel-preloader-popup .easyel-color-picker').each(function() {
                    var $input = $(this);
                    if ($input.closest('.wp-picker-container').length) {
                        return;
                    }
                    $input.wpColorPicker({
                        change: function(event, ui) {
                            $(event.target).val(ui.color.toString());
                            $('.easyel_preloader_popup_item_save').prop('disabled', false).val('Save Changes');
                        },
                        clear: function() {
                            $('.easyel_preloader_popup_item_save').prop('disabled', false).val('Save Changes');
                        }
                    });
                });
            }
        });

        $(document).on('click', '.easyel-preloader-popup-close-icon', function(e) {
            e.preventDefault();
            var $popup = $('.easyel-preloader-popup');
            $popup.removeClass('active');
            $('html').removeClass('popup-opened');
            setTimeout(function() {
                if ( ! $popup.hasClass('active') ) {
                    $popup.hide();
                }
            }, 300);
        });

        // Style change toggles fields
        $(document).on('change', '.easyel-preloader-popup select[name="preloader_style"]', function() {
            easyelPreloaderToggleFields();
        });

        // Logo upload via WP media
        $(document).on('click', '.easyel-preloader-upload-btn', function(e) {
            e.preventDefault();
            var frame = wp.media({
                title: 'Select Preloader Logo',
                multiple: false,
                library: { type: 'image' }
            });
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('.easyel-preloader-logo-input').val(attachment.url);
                $('.easyel-preloader-remove-btn').show();
                $('.easyel_preloader_popup_item_save').prop('disabled', false).val('Save Changes');
            });
            frame.open();
        });

        // Remove logo
        $(document).on('click', '.easyel-preloader-remove-btn', function(e) {
            e.preventDefault();
            $('.easyel-preloader-logo-input').val('');
            $(this).hide();
            $('.easyel_preloader_popup_item_save').prop('disabled', false).val('Save Changes');
        });
    });

    // Preloader: AJAX save
    $(document).ready(function($){

        var $popup = $('.easyel-preloader-popup');

        function easyelPreloaderEnableSaveBtn() {
            $('.easyel_preloader_popup_item_save')
                .prop('disabled', false)
                .removeAttr('disabled')
                .val('Save Changes');
        }

        function easyelPreloaderDisableSaveBtn() {
            $('.easyel_preloader_popup_item_save')
                .prop('disabled', true);
        }

        // Initial state — disabled
        easyelPreloaderDisableSaveBtn();

        // Native delegate for text/number/select/checkbox
        $popup.on('input change', 'input, select', easyelPreloaderEnableSaveBtn);

        // Snapshot baseline color values; poll for color changes (wpColorPicker doesn't fire native events reliably).
        var preloaderColorBaseline = {};
        function easyelPreloaderCaptureColors() {
            $popup.find('.easyel-color-picker').each(function() {
                var $i = $(this);
                preloaderColorBaseline[ $i.attr('name') ] = $i.val();
            });
        }

        // Capture baseline once popup opens (after wpColorPicker init has set initial values)
        $(document).on('click', '.easyel-preloader-popup-setting', function() {
            setTimeout(easyelPreloaderCaptureColors, 200);
        });

        // Poll every 250ms while popup is open
        setInterval(function() {
            if ( ! $popup.hasClass('active') ) {
                return;
            }
            $popup.find('.easyel-color-picker').each(function() {
                var $i = $(this);
                var name = $i.attr('name');
                var current = $i.val();
                if ( preloaderColorBaseline[ name ] !== undefined && preloaderColorBaseline[ name ] !== current ) {
                    preloaderColorBaseline[ name ] = current;
                    easyelPreloaderEnableSaveBtn();
                }
            });
        }, 250);

        $(document).on('click', '.easyel_preloader_popup_item_save', function(event){
            event.preventDefault();

            var saveButton = $(this);
            saveButton.prop('disabled', true).val('Saving...');

            var settings = {
                preloader_style:           $popup.find('select[name="preloader_style"]').val() || 'circle',
                preloader_bg_color:        $popup.find('input[name="preloader_bg_color"]').val() || '#ffffff',
                preloader_color:           $popup.find('input[name="preloader_color"]').val() || '#5933ff',
                preloader_secondary_color: $popup.find('input[name="preloader_secondary_color"]').val() || '#e0e0e0',
                preloader_size:            $popup.find('input[name="preloader_size"]').val() || '60',
                preloader_speed:           $popup.find('input[name="preloader_speed"]').val() || '1',
                preloader_min_time:        $popup.find('input[name="preloader_min_time"]').val() || '500',
                preloader_fadeout_time:    $popup.find('input[name="preloader_fadeout_time"]').val() || '600',
                preloader_logo:            $popup.find('input[name="preloader_logo"]').val() || '',
                preloader_logo_width:      $popup.find('input[name="preloader_logo_width"]').val() || '36',
                preloader_logo_height:     $popup.find('input[name="preloader_logo_height"]').val() || '36',
                preloader_disable_mobile:  $popup.find('input[name="preloader_disable_mobile"]').is(':checked') ? 1 : 0
            };

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'easyel_save_preloader_settings',
                    nonce: easyElementsData.nonce,
                    settings: settings
                },
                success: function(response){
                    if (response.success) {
                        easyelDisplayMessage('Settings Saved', 'success');
                        saveButton.val('Saved All Data').prop('disabled', true);
                    } else {
                        easyelDisplayMessage('Error saving settings', 'error');
                        saveButton.val('Save Changes').prop('disabled', false);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.log('AJAX error:', textStatus, errorThrown);
                    easyelDisplayMessage('AJAX error: ' + textStatus, 'error');
                    saveButton.val('Save Changes').prop('disabled', false);
                }
            });
        });
    });


})(jQuery);
