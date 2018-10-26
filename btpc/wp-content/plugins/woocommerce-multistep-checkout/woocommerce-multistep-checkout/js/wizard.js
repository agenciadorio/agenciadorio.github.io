/****WooCommerce Checkout Wizard **/

jQuery(document).ready(function ($) {

    jQuery('form.checkout').show();
    jQuery("form.checkout .validate-required :input").attr("required", "required");
    jQuery("form.checkout .validate-email .input-text").addClass("email");

    if (wmc_wizard.isAuthorizedUser == false && wmc_wizard.include_login != "false" && wmc_wizard.woo_include_login != "no") {
        var nextButtonTitle = wmc_wizard.no_account_btn
    } else {
        var nextButtonTitle = wmc_wizard.next
    }
    var nextButtonTitle

    if (wmc_wizard.wmc_remove_numbers == 'true') {
        jQuery("#wizard").steps({
            transitionEffect: wmc_wizard.transitionEffect,
            stepsOrientation: wmc_wizard.stepsOrientation,
            enableAllSteps: false,
            enablePagination: wmc_wizard.enablePagination,
            titleTemplate: '#title#',
            labels: {
                next: nextButtonTitle,
                previous: wmc_wizard.previous,
                finish: wmc_wizard.finish
            },
            onInit: function (event, current) {
                $('.actions > ul > li:first-child').attr('style', 'display:none');
            },
            onStepChanging: function (event, currentIndex, newIndex)
            {
                if ((currentIndex == 0 && wmc_wizard.isAuthorizedUser == false && wmc_wizard.include_login != "false" && wmc_wizard.woo_include_login != "no") || currentIndex > newIndex || isCouponForm()) {

                    return true
                } else {
                    return validate_checkoutform();
                }
            },
            onStepChanged: function (event, currentIndex, priorIndex)
            {
                if (currentIndex > 0) {
                    $('.actions > ul > li:first-child').attr('style', '');
                } else {
                    $('.actions > ul > li:first-child').attr('style', 'display:none');
                }
                if (currentIndex == 0 && wmc_wizard.isAuthorizedUser == false && wmc_wizard.include_login != "false" && wmc_wizard.woo_include_login != "no") {
                    jQuery('form.checkout a[href="#next"]').html(wmc_wizard.no_account_btn);
                    jQuery('form.checkout a[href="#previous"]').hide();
                } else {
                    jQuery('form.checkout a[href="#next"]').html(wmc_wizard.next);
                    jQuery('form.checkout a[href="#previous"]').show();
                }
            }
        });
    } else {
        jQuery("#wizard").steps({
            transitionEffect: wmc_wizard.transitionEffect,
            stepsOrientation: wmc_wizard.stepsOrientation,
            enableAllSteps: false,
            enablePagination: wmc_wizard.enablePagination,
            labels: {
                next: nextButtonTitle,
                previous: wmc_wizard.previous,
                finish: wmc_wizard.finish
            },
            onInit: function (event, current) {
                $('.actions > ul > li:first-child').attr('style', 'display:none');
            },
            onStepChanging: function (event, currentIndex, newIndex)
            {
                if ((currentIndex == 0 && wmc_wizard.isAuthorizedUser == false && wmc_wizard.include_login != "false" && wmc_wizard.woo_include_login != "no") || currentIndex > newIndex || isCouponForm()) {
                    return true
                } else {
                    return validate_checkoutform();
                }
            },
            onStepChanged: function (event, currentIndex, priorIndex)
            {
                if (currentIndex > 0) {
                    $('.actions > ul > li:first-child').attr('style', '');
                } else {
                    $('.actions > ul > li:first-child').attr('style', 'display:none');
                }
                if (currentIndex == 0 && wmc_wizard.isAuthorizedUser == false && wmc_wizard.include_login != "false" && wmc_wizard.woo_include_login != "no") {
                    jQuery('form.checkout a[href="#next"]').html(wmc_wizard.no_account_btn);
                    jQuery('form.checkout a[href="#previous"]').hide();
                } else {
                    jQuery('form.checkout a[href="#next"]').html(wmc_wizard.next);
                    jQuery('form.checkout a[href="#previous"]').show();
                }
            }
        });
    }


    jQuery(".actions > ul li:last a").addClass("finish-btn");

    jQuery(".finish-btn").click(function () {

        if (jQuery('input[name="legal"]').length) {
            if (jQuery('input[name="legal"]').is(":checked")) {
                jQuery("#place_order").trigger("click");
                return true
            } else {
                jQuery('input[name="legal"]').attr("required", "required");
                jQuery('.terms').css('border', '1px solid #8a1f11');
                return false;
            }
        } else {
            if (jQuery('input[name="terms"]').length) {
                if (jQuery('input[name="terms"]').is(":checked")) {
                    jQuery("#place_order").trigger("click");
                    return true
                } else {
                    jQuery('input[name="terms"]').attr("required", "required");
                    jQuery('input[name="terms"]').parent().css('border', '1px solid #8a1f11');
                    return false;
                }
            }
        }
        jQuery("#place_order").trigger("click");

    });


    //customize form error message
    jQuery.extend(jQuery.validator.messages, {
        required: wmc_wizard.error_msg,
        email: wmc_wizard.email_error_msg
    });

    //add class based on step
    var total_steps = jQuery("#wizard ul[role='tablist'] > li").length;
    if (total_steps == 5) {
        jQuery("#wizard").addClass("five-steps");
    }

    if (total_steps == 3) {
        jQuery("#wizard").addClass("three-steps");
    }

    /*** Adjustments of Tab Width **/
    if (wmc_wizard.stepsOrientation != "vertical") {
        var total_steps = jQuery("#wizard ul[role='tablist'] > li").length;
        var step_width = 100 / total_steps;
        $("#wizard .steps ul li").css("width", step_width + "%");
    }


    jQuery('body').on('change', '#billing_country', function ()
    {
        if ($("#billing_country").is(":visible")) {
            checkPostCode('billing');
        }
    });

    jQuery('body').on('blur', '#billing_postcode', function ()
    {
        if ($("#billing_postcode").is(":visible")) {
            checkPostCode('billing');
        }
    });


    jQuery('body').on('change', '#shipping_country', function ()
    {
        if ($("#shipping_country").is(":visible")) {
            checkPostCode('shipping');
        }

    });

    jQuery('body').on('blur', '#shipping_postcode', function ()
    {
        if ($("#shipping_postcode").is(":visible")) {
            checkPostCode('shipping');
        }

    });


    function checkPostCode(type)
    {

        result = jQuery(".form-row#" + type + "_postcode_field").length > 0 && jQuery("#" + type + "_postcode").val() != false
                && jQuery("#" + type + "_country").length > 0 && jQuery("#" + type + "_country").val() != false;

        if (result) {


            var data = {
                action: 'valid_post_code',
                country: jQuery("#" + type + "_country").val(),
                postCode: jQuery("#" + type + "_postcode").val()
            };

            $(document).ajaxStart($.blockUI(
                    {
                        message: null,
                        overlayCSS:
                                {
                                    background: "#fff url('" + wmc_wizard.loading_img + "') no-repeat center center",
                                    opacity: 0.6
                                }
                    }
            )).ajaxStop($.unblockUI);

            jQuery.post(wmc_wizard.ajaxurl, data, function (response) {
                if (response == false) {
                    jQuery("#" + type + "_postcode").parent().removeClass("woocommerce-validated").addClass("woocommerce-invalid woocommerce-invalid-required-field");
                    return false;
                } else {
                    return true;
                }



            });
        }
    }

    //validate checkout form
    function validate_checkoutform() {
        //       
        var form_valid = false;
        /// jQuery("#wizard").validate().settings.ignore = ":disabled,:hidden";

        if (jQuery("form.checkout").valid()) {
            form_valid = true;
        }




        if (wmc_wizard.isAuthorizedUser == false) {
            if ($("#shipping_state_field").is(":visible")) {
                if ($("#shipping_state").is(['required'])) {
                    if (!$("#shipping_state_field").hasClass("woocommerce-validated")) {
                        if (!$('#shipping_state_field').has('label.error-class').length) {
                            $("#s2id_shipping_state").addClass("invalid-state");
                            $('#shipping_state_field').append('<label class="error-class">' + wmc_wizard.error_msg + '</label>');
                        }
                        form_valid = false
                    } else {
                        $('#shipping_state_field').find('label.error-class').remove();
                        $("#s2id_shipping_state").removeClass("invalid-state");
                    }
                }

            }

            if ($("#billing_state_field").is(":visible")) {
                if ($("#billing_state").is(['required'])) {
                    if (!$("#billing_state_field").hasClass("woocommerce-validated")) {
                        if (!$('#billing_state_field').has('label.error-class').length) {
                            $("#s2id_billing_state").addClass("invalid-state");
                            $('#billing_state_field').append('<label class="error-class">' + wmc_wizard.error_msg + '</label>');
                        }
                        form_valid = false
                    } else {
                        $('#billing_state_field').find('label.error-class').remove();
                        $("#s2id_billing_state").removeClass("invalid-state");
                    }
                }

            }
        }

        if (wmc_wizard.isAuthorizedUser) {
            if ($("#billing_state_field").is(":visible")) {
                if ($("#billing_state").is(['required'])) {
                    if ($.trim($("#billing_state").val()) == "") {
                        $("#s2id_billing_state").addClass("invalid-state");
                        if (!$("#billing_state_field").has(".error-class").length) {
                            if (!$("#billing_state_field").has("label.error").length && !$("#billing_state_field label.error").is(":visible")) {
                                $('#billing_state_field').append('<label class="error-class">' + wmc_wizard.error_msg + '</label>');
                            }
                        }
                        form_valid = false
                    } else {
                        $('#billing_state_field').find('label.error-class').remove();
                        $("#s2id_billing_state").removeClass("invalid-state");
                    }
                }

            }

            if ($("#shipping_state_field").is(":visible")) {
                if ($("#shipping_state").is(['required'])) {
                    if ($.trim($("#shipping_state").val()) == "") {
                        $("#s2id_shipping_state").addClass("invalid-state");
                        if (!$("#shipping_state_field").has("label.error").length && !$("#shipping_state_field label.error").is(":visible")) {
                            $('#shipping_state_field').append('<label class="error-class">' + wmc_wizard.error_msg + '</label>');
                        }
                        form_valid = false
                    }
                } else {
                    $('#shipping_state_field').find('label.error-class').remove();
                    $("#s2id_shipping_state").removeClass("invalid-state");
                }
            }

        }



        if (jQuery("#billing_postcode").closest("#billing_postcode_field").hasClass("woocommerce-invalid")) {

            form_valid = false;
        }

        //validating billing phone
        if ($("#billing_phone").length) {
            var phone = jQuery('#billing_phone').val();
            phone = phone.replace(/[\s\#0-9_\-\+\(\)]/g, '');
            phone = jQuery.trim(phone);

            if (phone.length > 0) {
                form_valid = false;
                jQuery("#billing_phone_field").removeClass("woocommerce-validated").addClass("woocommerce-invalid woocommerce-invalid-required-field");
                if (!$('#billing_phone_field').has('label.error-class').length) {
                    $('#billing_phone_field').append('<label class="error-class">invalid phone number</label>');
                }
            }
        }


        if (jQuery("#ship-to-different-address-checkbox").closest("h1.title").hasClass("current")) {
            if (jQuery("#ship-to-different-address-checkbox").is(":checked")) {
                if (jQuery("#shipping_postcode").closest("#shipping_postcode_field").hasClass("woocommerce-invalid")) {

                    form_valid = false;
                }
            }
        }

        return form_valid;

    }

    function validate_phone() {
        var data = {
            action: 'validate_phone',
            phone: jQuery("#billing_phone").val()
        };

        $(document).ajaxStart($.blockUI(
                {
                    message: null,
                    overlayCSS:
                            {
                                background: "#fff url('" + wmc_wizard.loading_img + "') no-repeat center center",
                                opacity: 0.6
                            }
                }
        )).ajaxStop($.unblockUI);

        jQuery.post(wmc_wizard.ajaxurl, data, function (response) {

            if (response == false) {
                jQuery("#billing_phone").parent().removeClass("woocommerce-validated").addClass("woocommerce-invalid woocommerce-invalid-required-field");
                return false;
            } else {
                return true;
            }

        });
    }

    //Login form

    jQuery('form.login').submit(function (evt)
    {
        if (wmc_wizard.include_login != "false") {

            evt.preventDefault();
            var form = 'form.login';
            var error = false;

            if (jQuery(form + ' input#username').val() == false) {
                error = true;
                addRequiredClasses('username');
            }

            if (jQuery(form + ' input#password').val() == false) {
                error = true;
                addRequiredClasses('password');
            }

            if (error != false)
            {
                return false;
            }

            var formSelector = this;

            if (jQuery(form + ' input#rememberme').is(':checked') == false) {
                rememberme = false;
            } else {
                rememberme = true;
            }

            $(document).ajaxStart($.blockUI(
                    {
                        message: null,
                        overlayCSS:
                                {
                                    background: "#fff url('" + wmc_wizard.loading_img + "') no-repeat center center",
                                    opacity: 0.6
                                }
                    }
            )).ajaxStop($.unblockUI);

            var data = {
                action: 'wmc_check_user_login',
                username: jQuery(form + ' input#username').val(),
                password: jQuery(form + ' input#password').val(),
                rememberme: rememberme,
                _ajax_nonce: wmc_wizard.login_nonce
            };

            jQuery.post(wmc_wizard.ajaxurl, data, function (response) {
                if (response == 'successfully') {
                    location.reload();
                } else {
                    if (!$("form.login > .error-msg").length) {
                        jQuery('form.login').prepend(response);
                    }
                }
            })
        }



    });

    function addRequiredClasses(selector)
    {
        jQuery('form.login input#' + selector).parent().removeClass("woocommerce-validated");
        jQuery('form.login input#' + selector).parent().addClass("woocommerce-invalid woocommerce-invalid-required-field");
        jQuery('form.login input#' + selector).parent().addClass("validate-required");
    }

    $("#billing_phone").on('blur input change', function () {
        if ($("#billing_phone").length) {
            if ($(this).prop('required')) {
                var phone = jQuery('#billing_phone').val();
                phone = phone.replace(/[\s\#0-9_\-\+\(\)]/g, '');
                phone = jQuery.trim(phone);

                if ($(this).val() != "") {
                    $("#billing_phone").next("label.error").remove();
                }
                if (phone.length > 0) {
                    jQuery("#billing_phone_field").removeClass("woocommerce-validated").addClass("woocommerce-invalid woocommerce-invalid-required-field");
                    if (!$('#billing_phone_field').has('label.error-class').length) {
                        $('#billing_phone_field').append('<label class="error-class">' + wmc_wizard.phone_error_msg + '</label>');
                    }
                } else {

                    if ($('#billing_phone_field').has('label.error-class').length) {
                        $('#billing_phone').next().remove();
                    }

                }
            }
        }

    });

    function isCouponForm() {
        validate = false;
        if ($("#wizard div.current").find("form.checkout_coupon").length) {
            validate = true;
        }

        return validate;
    }

    /**Disable form submission through Keyboard enter **/
    $("form.checkout").on('submit', function (e) {

        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
});