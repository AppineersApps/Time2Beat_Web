/** ws_messages module script */
Project.modules.ws_messages = {
    init: function () {

        $.validator.addMethod("alpha_numeric_without_spaces", function (value, element) {
            return this.optional(element) || /^[0-9a-zA-Z_]+$/.test(value);
        }, ci_js_validation_message(js_lang_label.LANGUAGELABELS_PLEASE_ONLY_ENTER_LETTERS_AND_NUMBERS_WITHOUT_SPACE_FOR_THE_FIELD_FIELD, "#FIELD#", js_lang_label.WS_MESSAGES_API_NAME));


    },
    validate: function () {
        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore: ".ignore-valid, .ignore-show-hide",
            rules: {
                "mwm_apiname": {
                    "required": true,
                    "alpha_numeric_without_spaces": true
                },
                "mwm_message_code": {
                    "required": true
                },
                "mwml_message": {
                    "required": true
                },
                "mwm_type": {
                    "required": true
                },
                "mwm_status": {
                    "required": true
                }
            },
            messages: {
                "mwm_apiname": {
                    "required": ci_js_validation_message(js_lang_label.WS_MESSAGES_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD,
                            "#FIELD#",
                            js_lang_label.WS_MESSAGES_API_NAME),
                    "alpha_numeric_without_spaces": ci_js_validation_message(js_lang_label.WS_MESSAGES_PLEASE_ONLY_ENTER_LETTERS_AND_NUMBERS_WITHOUT_SPACE_FOR_THE_FIELD_FIELD,
                            "#FIELD#",
                            js_lang_label.WS_MESSAGES_API_NAME)
                },
                "mwm_message_code": {
                    "required": ci_js_validation_message(js_lang_label.WS_MESSAGES_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD,
                            "#FIELD#",
                            js_lang_label.WS_MESSAGES_CODE)
                },
                "mwml_message": {
                    "required": ci_js_validation_message(js_lang_label.WS_MESSAGES_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD,
                            "#FIELD#",
                            js_lang_label.WS_MESSAGES_MESSAGE)
                },
                "mwm_type": {
                    "required": ci_js_validation_message(js_lang_label.WS_MESSAGES_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD,
                            "#FIELD#",
                            js_lang_label.WS_MESSAGES_TYPE)
                },
                "mwm_status": {
                    "required": ci_js_validation_message(js_lang_label.WS_MESSAGES_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD,
                            "#FIELD#",
                            js_lang_label.WS_MESSAGES_STATUS)
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr('name') == 'mwm_apiname') {
                    $('#' + element.attr('id') + 'Err').html(error);
                }
                if (element.attr('name') == 'mwm_message_code') {
                    $('#' + element.attr('id') + 'Err').html(error);
                }
                if (element.attr('name') == 'mwml_message') {
                    $('#' + element.attr('id') + 'Err').html(error);
                }
                if (element.attr('name') == 'mwm_type') {
                    $('#' + element.attr('id') + 'Err').html(error);
                }
                if (element.attr('name') == 'mwm_status') {
                    $('#' + element.attr('id') + 'Err').html(error);
                }
            },
            invalidHandler: function (form, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    validator.errorList[0].element.focus();
                }
            },
            submitHandler: function (form) {
                getAdminFormValidate();
                return false;
            }
        });
    },
    callEvents: function () {
        this.validate();
        this.initEvents();
        this.CCEvents();
        callGoogleMapEvents();
    },
    callChilds: function () {

        callGoogleMapEvents();
    },
    initEvents: function (elem) {
        $('#mwml_message').elastic();
        $('[id^="lang_mwml_message"]').elastic();
    },
    childEvents: function (elem, eleObj) {

    },
    CCEvents: function () {

    }
}
Project.modules.ws_messages.init();