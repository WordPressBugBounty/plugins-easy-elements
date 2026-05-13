(function($) {
    'use strict';

    var EasyElementsLoginRegister = {
        init: function() {
            this.login();
            this.register();
        },
        login: function () {
            $(document).on('submit', '.eel-login-form', function(e) {
                e.preventDefault();

                const form = $(this);
                const user = form.find('input#eel_username').val();
                const pwd = form.find('input#eel_password').val();
                const remember = form.find('input#eel_rememberme').val();
                const ajaxLoader = form.find('.eel-form-ajax-loader');
                const errEl = form.find('.eel-form-status');
                const loginRedirectUrl = form.find('input[name="login_redirect_link"]').val();

                errEl.find('p').css('display', 'none');
                ajaxLoader.addClass('show');
                

                const data = {
                    action: 'eel_login',
                    user,
                    pwd,
                    remember,
                    nonce: eelLoginRegister.nonce
                };

                
                $.post(eelLoginRegister.ajaxurl, data)
                .done(function(response) {
                    console.log('response', response);

                    ajaxLoader.removeClass('show');
                    if (!response.success) {
                        errEl.find('.eel-form-error-msg').css('display', 'block');
                    }else{
                        errEl.find('.eel-form-success-msg').css('display', 'block');
                        setTimeout(() => {
                           location.replace(loginRedirectUrl);
                        }, 2000);
                    }
                })
                .fail(function() {
                    ajaxLoader.removeClass('show');
                    errEl.find('.eel-form-error-msg').css('display', 'block');
                });
            });
        },
        register: function () {

            // Prevent default form submit
            $(document).on('submit', '.eel-register-form', function(e) {
                e.preventDefault();
            });

            // On click submit button
            $(document).on('click', '.eel-register-form .eel-submit-button', function(e) {
                e.preventDefault();

                const form = $(this).closest('.eel-register-form');
                
                const ajaxLoader = form.find('.eel-form-ajax-loader');
                const errEl = form.find('.eel-form-status');
                const registerRedirectUrl = form.find('input[name="register_redirect_link"]').val();
                const auto_login = form.find('input[name="auto_login_after_registration"]').val();
                
                const empty_error_msg = form.find('input[name="empty_error_msg"]').val(); // This field is required.
                const min_length_error_msg = form.find('input[name="min_length_error_msg"]').val(); // Minimum count characters required.
                const max_length_error_msg = form.find('input[name="max_length_error_msg"]').val(); // Maximum count characters required.
                const confirm_pass_error_msg = form.find('input[name="confirm_pass_error_msg"]').val(); // Maximum count characters required.
                const pass = form.find('input[name="user_pass"]').val();
                const confirm_pass_field = form.find('input[name="confirm_password"]');
                const confirm_pass = confirm_pass_field.length ? confirm_pass_field.val() : null;

                form.find('.input-error').removeClass('input-error');
                form.find('.eel-error-msg').text('');
                form.find('.eel-error-msg[data-error-for="eel_confirm_password"]').text('').hide();

                let hasError = false;

                // Run validation manually
                form.find('input, textarea, select').each(function() {
                    const input = $(this);
                    const type = input.attr('type');
                    const name = input.attr('name');
                    if (!name) return;
                    if (type === 'hidden') return;

                    let value = input.val();
                    const identifier = input.attr('id') || name;

                    // error container
                    let errorEl = form.find('.eel-error-msg[data-error-for="'+identifier+'"]');
                    if (!errorEl.length) {
                        errorEl = $('<div/>', { class: 'eel-error-msg', 'data-error-for': identifier }).insertAfter(input);
                    }

                    // REQUIRED validation
                    const isRequired = input.prop('required') || input.attr('required') !== undefined;

                    if (type === 'checkbox') {
                        const groupName = name; // same name => group
                        const checked = form.find('input[name="'+groupName+'"]:checked');
                        if (isRequired && checked.length === 0) {
                            input.addClass('input-error');
                            errorEl.text(empty_error_msg).show();
                            hasError = true;
                            return;
                        }
                    } else {
                        value = (value || '').trim();
                        if (isRequired && value === '') {
                            input.addClass('input-error');
                            errorEl.text(empty_error_msg).show();
                            hasError = true;
                            return;
                        }
                    }

                    // MIN / MAX LENGTH (text/email/password etc)
                    const min = input.attr('min');
                    const max = input.attr('max');
                    if (type !== 'checkbox') {
                        if (min && value.length > 0 && value.length < parseInt(min,10)) {
                            const msg = min_length_error_msg.replace('{count}', min);
                            input.addClass('input-error');
                            errorEl.text(msg).show();
                            hasError = true;
                            return;
                        }
                        if (max && value.length > parseInt(max,10)) {
                            const msg = max_length_error_msg.replace('{count}', max);
                            input.addClass('input-error');
                            errorEl.text(msg).show();
                            hasError = true;
                            return;
                        }

                        
                    }

                   
                    // hide error if passed
                    input.removeClass('input-error');
                    errorEl.hide();
                });


                if(confirm_pass !== null && pass !== confirm_pass) {
                    confirm_pass_field.addClass('input-error');
                    form.find('.eel-error-msg[data-error-for="eel_confirm_password"]').text(confirm_pass_error_msg).show();
                    return;
                }


                if (hasError) {
                    console.log("Validation failed");
                    return;
                }

                console.log("Validation passed → sending AJAX");

                // AJAX submit
                const data = { 
                    action: 'eel_register', 
                    auto_login,
                    nonce: eelLoginRegister.nonce
                 };

                // Loop through all fields and collect values
                form.find('input, textarea, select').each(function() {
                    const input = $(this);
                    const name = input.attr('name');
                    if (!name) return;

                    const value = input.val();

                    // Handle custom_meta array inputs
                    if (name.startsWith('custom_meta[')) {
                        // Get all keys for nested inputs
                        const keys = [...name.matchAll(/\[([^\]]+)\]/g)].map(m => m[1]); // ["phone"] or ["address","city"]


                        // Assign recursively
                        if (!data.custom_meta) data.custom_meta = {};
                        let ref = data.custom_meta;
                        keys.forEach(function(k, idx) {
                            if (idx === keys.length - 1) {
                                ref[k] = value; // last key -> assign value
                            } else {
                                if (!ref[k]) ref[k] = {};
                                ref = ref[k];
                            }
                        });
                    } else {
                        // normal input
                        data[name] = value;
                    }
                });

                console.log('data: ', data);
                errEl.find('p').css('display', 'none');
                ajaxLoader.addClass('show');

                
                $.post(eelLoginRegister.ajaxurl, data)
                .done(function(response) {
                    console.log('response', response);

                    ajaxLoader.removeClass('show');
                    if (!response.success) {
                        errEl.find('.eel-form-error-msg').css('display', 'block');
                    }else{
                        errEl.find('.eel-form-success-msg').css('display', 'block');
                        setTimeout(() => {
                           location.replace(registerRedirectUrl);
                        }, 2000);
                    }
                })
                .fail(function() {
                    ajaxLoader.removeClass('show');
                    errEl.find('.eel-form-error-msg').css('display', 'block');
                });
            });

        }
    };

    $(document).ready(function() {
        EasyElementsLoginRegister.init();
    });
})(jQuery);
