Project.modules.group = {
    init: function () {
        this.initEvents();
        this.validate();
        this.CCEvents();
    },
    validate: function () {

        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore: ".ignore-valid, .ignore-show-hide",
            rules: {
                "mgm_group_name": {
                    "required": true
                },
                "mgm_group_code": {
                    "required": true
                },
                "mgm_status": {
                    "required": true
                }
            },
            messages: {
                "mgm_group_name": {
                    "required": js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE_GROUP_NAME_FIELD
                },
                "mgm_group_code": {
                    "required": js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE_GROUP_CODE_FIELD
                },
                "mgm_status": {
                    "required": js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE_STATUS_FIELD
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr('name') == 'mgm_group_name') {
                    $('#' + element.attr('id') + 'Err').html(error);
                }
                if (element.attr('name') == 'mgm_group_code') {
                    $('#' + element.attr('id') + 'Err').html(error);
                }
                if (element.attr('name') == 'mgm_status') {
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
                $("input[type='checkbox'][name^='iAdminMenuId']").removeAttr("disabled");
                $("input[type='checkbox'][name^='eList']").removeAttr("disabled");
                $("input[type='checkbox'][name^='eView']").removeAttr("disabled");
                $("input[type='checkbox'][name^='eAdd']").removeAttr("disabled");
                $("input[type='checkbox'][name^='eUpdate']").removeAttr("disabled");
                $("input[type='checkbox'][name^='eDelete']").removeAttr("disabled");
                $("input[type='checkbox'][name^='eExport']").removeAttr("disabled");
                $("input[type='checkbox'][name^='ePrint']").removeAttr("disabled");
                $("#mgm_grouping_func").removeAttr("disabled");
                var options = {
                    beforeSubmit: showAdminAjaxRequest,
                    success: function (respText, statText, xhr, $form) {
                        var resArr = $.parseJSON(respText);
                        responseAjaxDataSubmission(resArr);
                        if (resArr.success == "0") {
                            return false;
                        } else {
                            // Project.modules.AjaxNavigate.LoadPage(resArr.return_url);
                            loadAdminAddUpdateControl(resArr);
                        }
                    }
                };
                $("#frmaddupdate").ajaxSubmit(options);
                return false;
            }
        });

    },
    initEvents: function (elem) {
        var that = this;
        $("input[type='checkbox'][id^='iAdminMenuId']").each(function () {
            var jlist = $("input[type='checkbox'][id='eList_" + $(this).val() + "']").is(":checked");
            var jview = $("input[type='checkbox'][id='eView_" + $(this).val() + "']").is(":checked");
            var jadd = $("input[type='checkbox'][id='eAdd_" + $(this).val() + "']").is(":checked");
            var jupdate = $("input[type='checkbox'][id='eUpdate_" + $(this).val() + "']").is(":checked");
            var jdel = $("input[type='checkbox'][id='eDelete_" + $(this).val() + "']").is(":checked");
            if (jlist && jview && jadd && jupdate && jdel) {
                $(this).prop("checked", true);
            }
        });
        $("input[type='checkbox'][id='all_eList']").on('click', function () {
            if ($(this).is(":checked") == true) {
                $("input[type='checkbox'][id^='eList']").prop("checked", true);
            } else {
                $("input[type='checkbox'][id^='eList']:not(:disabled)").prop("checked", false);
            }
        });
        $("input[type='checkbox'][id='all_eView']").on('click', function () {
            if ($(this).is(":checked") == true) {
                $("input[type='checkbox'][id^='eView']").prop("checked", true);
            } else {
                $("input[type='checkbox'][id^='eView']:not(:disabled)").prop("checked", false);
            }
        });
        $("input[type='checkbox'][id='all_eAdd']").on('click', function () {
            if ($(this).is(":checked") == true) {
                $("input[type='checkbox'][id^='eAdd']").prop("checked", true);
            } else {
                $("input[type='checkbox'][id^='eAdd']:not(:disabled)").prop("checked", false);
            }
        });
        $("input[type='checkbox'][id='all_eUpdate']").on('click', function () {
            if ($(this).is(":checked") == true) {
                $("input[type='checkbox'][id^='eUpdate']").prop("checked", true);
            } else {
                $("input[type='checkbox'][id^='eUpdate']:not(:disabled)").prop("checked", false);
            }
        });
        $("input[type='checkbox'][id='all_eDelete']").on('click', function () {
            if ($(this).is(":checked") == true) {
                $("input[type='checkbox'][id^='eDelete']").prop("checked", true);
            } else {
                $("input[type='checkbox'][id^='eDelete']:not(:disabled)").prop("checked", false);
            }
        });
        $("input[type='checkbox'][id='all_eExport']").on('click', function () {
            if ($(this).is(":checked") == true) {
                $("input[type='checkbox'][id^='eExport']").prop("checked", true);
            } else {
                $("input[type='checkbox'][id^='eExport']:not(:disabled)").prop("checked", false);
            }
        });
        $("input[type='checkbox'][id='all_ePrint']").on('click', function () {
            if ($(this).is(":checked") == true) {
                $("input[type='checkbox'][id^='ePrint']").prop("checked", true);
            } else {
                $("input[type='checkbox'][id^='ePrint']:not(:disabled)").prop("checked", false);
            }
        });
        $("input[type='checkbox'][id^='iAdminMenuId']").on('click', function () {
            if ($(this).is(":checked") == true) {
                $("input[type='checkbox'][id='eList_" + $(this).val() + "']").prop("checked", true);
                $("input[type='checkbox'][id='eView_" + $(this).val() + "']").prop("checked", true);
                $("input[type='checkbox'][id='eAdd_" + $(this).val() + "']").prop("checked", true);
                $("input[type='checkbox'][id='eUpdate_" + $(this).val() + "']").prop("checked", true);
                $("input[type='checkbox'][id='eDelete_" + $(this).val() + "']").prop("checked", true);
                $("input[type='checkbox'][id='eExport_" + $(this).val() + "']").prop("checked", true);
                $("input[type='checkbox'][id='ePrint_" + $(this).val() + "']").prop("checked", true);
                that.getCheckParentAdminMenu($(this), 'all');
                that.checkChildAdminMenu($(this).val(), true);
            } else {
                $("input[type='checkbox'][id='eList_" + $(this).val() + "']").prop("checked", false);
                $("input[type='checkbox'][id='eView_" + $(this).val() + "']").prop("checked", false);
                $("input[type='checkbox'][id='eAdd_" + $(this).val() + "']").prop("checked", false);
                $("input[type='checkbox'][id='eUpdate_" + $(this).val() + "']").prop("checked", false);
                $("input[type='checkbox'][id='eDelete_" + $(this).val() + "']").prop("checked", false);
                $("input[type='checkbox'][id='eExport_" + $(this).val() + "']").prop("checked", false);
                $("input[type='checkbox'][id='ePrint_" + $(this).val() + "']").prop("checked", false);
                that.checkChildAdminMenu($(this).val(), false);
            }
        });
        $("input[type='checkbox'][id^='eList']").on('click', function () {
            if ($(this).is(":checked") == true) {
                that.getCheckParentAdminMenu($(this), "list");
            }
        });
        $("input[type='checkbox'][id^='eView']").on('click', function () {
            if ($(this).is(":checked") == true) {
                that.getCheckParentAdminMenu($(this), "view");
            }
        });
        $("input[type='checkbox'][id^='eAdd']").on('click', function () {
            if ($(this).is(":checked") == true) {
                that.getCheckParentAdminMenu($(this), "add");
            }
        });
        $("input[type='checkbox'][id^='eUpdate']").on('click', function () {
            if ($(this).is(":checked") == true) {
                that.getCheckParentAdminMenu($(this), "update");
            }
        });
        $("input[type='checkbox'][id^='eDelete']").on('click', function () {
            if ($(this).is(":checked") == true) {
                that.getCheckParentAdminMenu($(this), "delete");
            }
        });
        $("input[type='checkbox'][id^='eExport']").on('click', function () {
            if ($(this).is(":checked") == true) {
                that.getCheckParentAdminMenu($(this), "export");
            }
        });
        $("input[type='checkbox'][id^='ePrint']").on('click', function () {
            if ($(this).is(":checked") == true) {
                that.getCheckParentAdminMenu($(this), "print");
            }
        });
        $(document).on('change', ".parent-category", function () {
            var capability_id = $(this).attr('capabilty-attr');
            if ($(this).is(":checked") == true) {
                $("#child_capability_" + capability_id).find('.regular-checkbox').prop('checked', true);
                $("#child_capability_" + capability_id).find('.capabilities-json').removeClass('hide');
            } else {
                $("#child_capability_" + capability_id).find('.regular-checkbox').prop('checked', false);
                $("#child_capability_" + capability_id).find('.capabilities-json').addClass('hide');
            }
        });
        
        $(document).on('change', ".module-parent-category", function () {
            var capability_id = $(this).attr('capabilty-attr');
            if ($(this).is(":checked") == true) {
                $("#module_child_capability_" + capability_id).find('.regular-checkbox').prop('checked', true);
            } else {
                $("#module_child_capability_" + capability_id).find('.regular-checkbox').prop('checked', false);
            }
        });
        
        $(document).on('change', ".capability-category", function () {
            var capability_id = $(this).val();
            if ($(this).is(":checked") == true) {
                $("#capability_json_" + capability_id).removeClass('hide');
            } else {
                $("#capability_json_" + capability_id).addClass('hide');
            }
        });
    },
    getCheckParentAdminMenu: function (eleObj, mode) {
        if ($(eleObj).attr("rel")) {
            var jrelArr = $(eleObj).attr("rel").split("_");
            var jrel_id = jrelArr[1];
            if (jrelArr[0] == "parent") {
                $("#iAdminMenuId_" + jrel_id).prop("checked", true);
                if (mode == "list") {
                    $("#eList_" + jrel_id).prop("checked", true);
                } else if (mode == "view") {
                    $("#eView_" + jrel_id).prop("checked", true);
                } else if (mode == "add") {
                    $("#eAdd_" + jrel_id).prop("checked", true);
                } else if (mode == "update") {
                    $("#eUpdate_" + jrel_id).prop("checked", true);
                } else if (mode == "delete") {
                    $("#eDelete_" + jrel_id).prop("checked", true);
                } else if (mode == "export") {
                    $("#eExport_" + jrel_id).prop("checked", true);
                } else if (mode == "print") {
                    $("#ePrint_" + jrel_id).prop("checked", true);
                } else {
                    $("#eList_" + jrel_id).prop("checked", true);
                    $("#eView_" + jrel_id).prop("checked", true);
                    $("#eAdd_" + jrel_id).prop("checked", true);
                    $("#eUpdate_" + jrel_id).prop("checked", true);
                    $("#eDelete_" + jrel_id).prop("checked", true);
                    $("#eExport_" + jrel_id).prop("checked", true);
                    $("#ePrint_" + jrel_id).prop("checked", true);
                }
            }
        }
    },
    checkChildAdminMenu: function (jval, jmod) {
        $("input[type='checkbox'][name^='iAdminMenuId'][rel='parent_" + jval + "']:not(:disabled)").prop("checked", jmod);
        $("input[type='checkbox'][name^='eList'][rel='parent_" + jval + "']:not(:disabled)").prop("checked", jmod);
        $("input[type='checkbox'][name^='eView'][rel='parent_" + jval + "']:not(:disabled)").prop("checked", jmod);
        $("input[type='checkbox'][name^='eAdd'][rel='parent_" + jval + "']:not(:disabled)").prop("checked", jmod);
        $("input[type='checkbox'][name^='eUpdate'][rel='parent_" + jval + "']:not(:disabled)").prop("checked", jmod);
        $("input[type='checkbox'][name^='eDelete'][rel='parent_" + jval + "']:not(:disabled)").prop("checked", jmod);
        $("input[type='checkbox'][name^='eExport'][rel='parent_" + jval + "']:not(:disabled)").prop("checked", jmod);
        $("input[type='checkbox'][name^='ePrint'][rel='parent_" + jval + "']:not(:disabled)").prop("checked", jmod);
    },
    childEvents: function (elem, eleObj) {

    },
    CCEvents: function () {

    }
}
Project.modules.group.init();
