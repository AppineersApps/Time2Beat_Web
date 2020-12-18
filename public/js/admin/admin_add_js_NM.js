/** admin module script */
Project.modules.admin = {
    init: function () {

        valid_more_elements = [];


    },
    validate: function () {

        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore: ".ignore-valid, .ignore-show-hide",
            rules: {
                "ma_name": {
                    "required": true
                },
                "ma_email": {
                    "required": true,
                    "email": true
                },
                "ma_user_name": {
                    "required": true
                },
                "ma_password": {
                    "required": true
                },
                "ma_phonenumber": {
                    "required": true
                },
                "ma_group_id": {
                    "required": true
                },
                "ma_status": {
                    "required": true
                }
            },
            messages: {
                "ma_name": {
                    "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46, "#FIELD#", js_lang_label.ADMIN_NAME)
                },
                "ma_email": {
                    "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46, "#FIELD#", js_lang_label.ADMIN_EMAIL),
                    "email": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_VALID_EMAIL_ADDRESS_FOR_THE__C35FIELD_C35_FIELD_C46, "#FIELD#", js_lang_label.ADMIN_EMAIL)
                },
                "ma_user_name": {
                    "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46, "#FIELD#", js_lang_label.ADMIN_USER_NAME)
                },
                "ma_password": {
                    "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46, "#FIELD#", js_lang_label.ADMIN_PASSWORD)
                },
                "ma_phonenumber": {
                    "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46, "#FIELD#", js_lang_label.ADMIN_PHONENUMBER)
                },
                "ma_group_id": {
                    "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46, "#FIELD#", js_lang_label.ADMIN_GROUP)
                },
                "ma_status": {
                    "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46, "#FIELD#", js_lang_label.ADMIN_STATUS)
                }
            },
            errorPlacement: function (error, element) {
                switch (element.attr("name")) {

                    case 'ma_name':
                        $('#' + element.attr('id') + 'Err').html(error);
                        break;
                    case 'ma_email':
                        $('#' + element.attr('id') + 'Err').html(error);
                        break;
                    case 'ma_user_name':
                        $('#' + element.attr('id') + 'Err').html(error);
                        break;
                    case 'ma_password':
                        $('#' + element.attr('id') + 'Err').html(error);
                        break;
                    case 'ma_phonenumber':
                        $('#' + element.attr('id') + 'Err').html(error);
                        break;
                    case 'ma_group_id':
                        $('#' + element.attr('id') + 'Err').html(error);
                        break;
                    case 'ma_status':
                        $('#' + element.attr('id') + 'Err').html(error);
                        break;
                    default:
                        printErrorMessage(element, valid_more_elements, error);
                        break;
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

        $('#ma_phonenumber').mask(getAdminJSFormat('phone'));
    },
    childEvents: function (elem, eleObj) {

    },
    CCEvents: function () {

    }
}
Project.modules.admin.init();
if ("form_editable" in el_form_settings && el_form_settings['form_editable'] == "Yes") {
    Project.modules.admin.callChilds(el_form_settings['form_child_evt']);
} else {
    Project.modules.admin.callEvents();
}
