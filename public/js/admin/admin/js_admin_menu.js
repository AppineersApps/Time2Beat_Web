Project.modules.menu = {
    init: function () {
        console.log(1);
        this.initEvents();
    },
    initEvents: function () {
        if (el_form_settings['edit_title']) {
            $('.edit-menu-title').each(function () {
                var id = $(this).attr("data-id");
                var value = $(this).attr("data-value");
                $(this).editable({
                    showbuttons: true,
                    type: 'text',
                    name: "menu_title",
                    placeholder: js_lang_label.GENERIC_MENU_TITLE,
                    value: value,
                    pk: id,
                    onblur: 'submit',
                    validate: function (value) {
                        var is_valid = isEmptyValue(value);
                        return is_valid;
                    },
                    url: function (params) {
                        Project.modules.menu.saveEvents(this, params);
                    }
                });
            });
            $('.edit-menu-title').on('shown', function (e, editable) {
                var ele_obj = $(editable.$form).find(".editable-input").find("input[type='text']");
                $(ele_obj).addClass("frm-size-medium");
            });
        }
        if (el_form_settings['edit_capability']) {
            $('.edit-menu-capability').each(function () {
                var id = $(this).attr("data-id");
                var value = $(this).attr("data-value");
                $(this).editable({
                    showbuttons: true,
                    type: 'select',
                    name: "menu_capability",
                    source: el_form_settings['capability_list_url'],
                    value: value,
                    pk: id,
                    sourceCache: true,
                    validate: function (value) {
                        var is_valid = isEmptyValue(value);
                        return is_valid;
                    },
                    url: function (params) {
                        setTimeout(function(){
                            Project.modules.menu.saveEvents(this, params);
                        }, 300);
                    }
                });
                $(this).on('shown', function (e, editable) {
                    var that = $(this);
                    var data_val = $(this).attr("data-value");
                    var ele_obj = $(editable.$form).find(".editable-input").find("select");
                    $(ele_obj).addClass("frm-size-medium");
                    $(ele_obj).find("option").removeAttr("selected");
                    $(ele_obj).find("option").each(function () {
                        if ($.trim($(this).text()) == $.trim(data_val)) {
                            $(this).prop("selected", true);
                            return false;
                        }
                    });
                    setTimeout(function () {
                        $(ele_obj).chosen({
                            allow_single_deselect: true
                        });
                    }, 5);

                    $(ele_obj).change(function () {
                        $(that).attr("data-value", $(this).find("option:selected").val());
                    });
                });
            });
        }

        if (el_form_settings['edit_status']) {
            $('.edit-menu-status').each(function () {
                var id = $(this).attr("data-id");
                var value = $(this).attr("data-value");
                $(this).editable({
                    showbuttons: true,
                    type: 'select',
                    name: "menu_status",
                    source: el_form_settings['status_list_url'],
                    value: value,
                    pk: id,
                    sourceCache: true,
                    validate: function (value) {
                        var is_valid = isEmptyValue(value);
                        return is_valid;
                    },
                    url: function (params) {
                        setTimeout(function(){
                            Project.modules.menu.saveEvents(this, params);
                        }, 300);
                    }
                });
                $(this).on('shown', function (e, editable) {
                    var that = $(this);
                    var data_val = $(this).attr("data-value");
                    var ele_obj = $(editable.$form).find(".editable-input").find("select");
                    $(ele_obj).addClass("frm-size-medium");
                    $(ele_obj).find("option").removeAttr("selected");
                    $(ele_obj).find("option").each(function () {
                        if ($.trim($(this).text()) == $.trim(data_val)) {
                            $(this).prop("selected", true);
                            return false;
                        }
                    });
                    setTimeout(function () {
                        $(ele_obj).chosen({
                            allow_single_deselect: true
                        });
                    }, 5);

                    $(ele_obj).change(function () {
                        $(that).attr("data-value", $(this).find("option:selected").val());
                    });
                });
            });

        }
    },
    saveEvents: function (elem, params) {
        var options = {
            "url": el_form_settings['save_menu_data_url'],
            "data": params,
            success: function (obj, config) {
                var res_arr = parseJSONString(obj);
                if (res_arr && !res_arr.success) {
                    var $jq_errmsg = js_lang_label.GENERIC_ERROR_IN_UPDATION;
                    if (res_arr.message != "") {
                        $jq_errmsg = res_arr.message;
                    }
                    $(elem).editable('option', 'value', $(elem).attr("aria-prev-value"));
                    $(elem).editable('show');
                    Project.setMessage(res_arr.success, $jq_errmsg);
                } else {
                    $(elem).attr("aria-prev-value", params.value);
                }
            }
        };
        $(elem).editable("submit", options);
    }
}
Project.modules.menu.init();