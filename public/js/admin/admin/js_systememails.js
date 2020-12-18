$(function () {
    $(document).on("blur", "[name^='system_email_variable']", function (e) {
        Project.modules.systememails.addAutoOptions();
    });
});
Project.modules.systememails = {
    init: function () {
        this.initEvents();
        this.validate();
        this.CCEvents();
        this.sortRowsFunction();
    },
    validate: function () {
        $('#frmaddupdate').validate({
            rules: {
                mse_email_code: {
                    required: true
                },
                mse_email_title: {
                    required: true
                },
                mse_email_subject: {
                    required: true
                },
                mse_from_email: {
                    email: true
                },
                'system_email_variable[]': {
                    required: true,
                    notRepeatedVariable: true,
                    defaultSYSVariable: true
                },
                'system_email_description[]': {
                    required: true
                }
            },
            messages: {
                mse_email_code: {
                    required: js_lang_label.GENERIC_PLEASE_ENTER_EMAIL_CODE
                },
                mse_email_title: {
                    required: js_lang_label.GENERIC_PLEASE_ENTER_EMAIL_TITLE
                },
                mse_email_subject: {
                    required: js_lang_label.GENERIC_PLEASE_ENTER_EMAIL_SUBJECT

                },
                mse_from_email: {
                    email: js_lang_label.GENERIC_PLEASE_ENTER_VALID_EMAIL
                },
                'system_email_variable[]': {
                    required: js_lang_label.GENERIC_PLEASE_ENTER_VARIABLE,
                    notRepeatedVariable: js_lang_label.GENERIC_PLEASE_ENTER_DIFFERENT_VARIABLE,
                    defaultSYSVariable: js_lang_label.GENERIC_SYSTEM_VARIABLE_NOT_ALLOWED
                },
                'system_email_description[]': {
                    required: js_lang_label.GENERIC_PLEASE_ENTER_DESCRIPTION
                }

            },
            errorPlacement: function (error, element) {
                if (element.attr("name") == "mse_email_code") {
                    error.appendTo("#mse_email_codeErr");
                }
                if (element.attr("name") == "mse_email_title") {
                    error.appendTo("#mse_email_titleErr");
                }
                if (element.attr("name") == "mse_email_subject") {
                    error.appendTo("#mse_email_subjectErr");
                }
                if (element.attr("name") == "mse_from_name") {
                    error.appendTo("#mse_from_nameErr");
                }
                if (element.attr("name") == "mse_from_email") {
                    error.appendTo("#mse_from_emailErr");
                }
                if (element.attr("name") == "system_email_variable[]") {
                    var je_id = element.attr("id");
                    error.appendTo("#" + je_id + "Err");
                }
                if (element.attr("name") == "system_email_description[]") {
                    var jd_id = element.attr("id");
                    error.appendTo("#" + jd_id + "Err");
                }
            }, submitHandler: function (form) {
                var options = {
                    beforeSubmit: showAdminAjaxRequest,
                    success: function (respText, statText, xhr, $form) {
                        var resArr = $.parseJSON(respText);
                        responseAjaxDataSubmission(resArr);
                        if (resArr.success == '0') {
                            return false;
                        } else {
                            loadAdminAddUpdateControl(resArr);
                        }
                    }
                };
                $("#frmaddupdate").ajaxSubmit(options);
                return false;
            }
        });
    },
    initEvents: function (eleObj) {
        var temp_editor_toolbar = tinymce_editor_tollbar;
        var temp_editor_plugins = tinymce_editor_plugins;
        temp_editor_plugins[3] = temp_editor_plugins[3] + " mention";
        tinyMCE.baseURL = el_tpl_settings.editor_js_url;
        removeIndividualTinyMCEEditor('mse_email_message');
        $('#mse_email_message').tinymce({
            body_class: "notranslate",
            script_url: el_tpl_settings.editor_js_url + 'tinymce.min.js',
            content_css: el_tpl_settings.editor_css_url + 'style.css',
            plugins: temp_editor_plugins,
            toolbar: temp_editor_toolbar,
            templates: tinymce_editor_templates,
            valid_elements: "*[*]",
            theme: "modern",
            resize: "both",
            skin: "light",
            height: 300,
            image_advtab: true,
            relative_urls: false,
            remove_script_host: false,
            external_filemanager_path: site_url + "filemanager/",
            filemanager_title: "Filemanager",
            external_plugins: {"filemanager": el_tpl_settings.js_libraries_url + "filemanager/plugin.min.js"},
            mentions: {
                source: [],
                delimiter: "#",
                insert: function (item) {
                    return '#' + item.name + '#';
                }
            },
            setup: function (ed) {
                ed.on('change', function (e) {
                    $('#' + $(this).attr('id')).attr('aria-multi-call', '1');
                    tinyMCE.triggerSave();
                });
                ed.on('click', function (e) {
                    $('#' + $(this).attr('id')).attr('aria-multi-call', '1');
                    tinyMCE.get(ed.id).focus();
                });
                ed.on('blur', function (e) {
                    var editorText = tinyMCE.get('mse_email_message').getContent({format: 'html'});
                    multilingualEditorContent(editorText, this);
                    $('#' + $(this).attr('id')).attr('aria-multi-call', '0');
                });
            },
            init_instance_callback: function (editor) {
                Project.modules.systememails.addAutoOptions();
            }
        });
        $('[id^="lang_mse_email_message"]').tinymce({
            body_class: "notranslate",
            script_url: el_tpl_settings.editor_js_url + 'tinymce.min.js',
            content_css: el_tpl_settings.editor_css_url + 'style.css',
            plugins: temp_editor_plugins,
            toolbar: temp_editor_toolbar,
            templates: tinymce_editor_templates,
            valid_elements: "*[*]",
            theme: "modern",
            resize: "both",
            skin: "light",
            height: 300,
            image_advtab: true,
            relative_urls: false,
            remove_script_host: false,
            external_filemanager_path: site_url + "filemanager/",
            filemanager_title: "Filemanager",
            external_plugins: {"filemanager": el_tpl_settings.js_libraries_url + "filemanager/plugin.min.js"},
            mentions: {
                source: [],
                delimiter: "#",
                insert: function (item) {
                    return '#' + item.name + '#';
                }
            },
            setup: function (ed) {
                ed.on('change', function (e) {
                    $('#' + $(this).attr('id')).attr('aria-multi-call', '1');
                    tinyMCE.triggerSave();
                });
                ed.on('click', function (e) {
                    $('#' + $(this).attr('id')).attr('aria-multi-call', '1');
                    tinyMCE.get(ed.id).focus();
                });
                ed.on('blur', function (e) {
                    var editorText = tinyMCE.get($(this).attr('id')).getContent({format: 'html'});
                    multilingualEditorContent(editorText, this);
                    $('#' + $(this).attr('id')).attr('aria-multi-call', '0');
                });
            },
            init_instance_callback: function (editor) {
                Project.modules.systememails.addAutoOptions();
            }
        });
    },
    getSystemEmailVariableTable: function () {
        var req_url = admin_url + cus_enc_url_json["systememail_variables"];
        $.post(req_url, {
            'row_id': inc_no,
            'dis_no': dis_no
        }, function (response) {
            $('#mails_fields_list').append(response);
            initializeTooltipsEvents();
            inc_no++;
            dis_no++;
        });
    },
    deleteSystemEmailVariableRow: function (js_iID) {
        var $dialog = $('<div></div>').html(js_lang_label.GENERIC_ARE_YOU_SURE_TO_DELETE_THIS_VARIABLE).dialog({
            title: js_lang_label.GENERIC_DELETE_VARIABLE,
            autoOpen: true,
            bgiframe: true,
            buttons: [{
                    'text': js_lang_label.GENERIC_OK,
                    click: function () {
                        $('#tr_child_row_' + js_iID).remove();
                        var dec_no = 1;
                        dis_no--;
                        $('.row-num-child').each(function () {
                            $(this).html(dec_no);
                            dec_no++;
                        });
                        Project.modules.systememails.addAutoOptions();
                        $(this).remove();
                    }
                }, {
                    'text': js_lang_label.GENERIC_CANCEL,
                    click: function () {
                        $(this).remove();
                    }
                }],
            modal: true
        });
    },
    sortRowsFunction: function () {
        var colorArr = new Array();
        colorArr[0] = 'td-dark';
        colorArr[1] = 'td-light';
        $("#mails_fields_list").sortable({
            items: '.field-sortable',
            containment: 'parent',
            cursor: "move",
            forcePlaceholderSize: true,
            placeholder: "ajax-sortable-placeholder",
            start: function (event, ui) {
                $(ui.item).css({
                    "background-color": "#009DDB"
                });
                $('parent').css('position', 'absolute');
            },
            stop: function (event, ui) {
                $(ui.item).css({
                    "background-color": ""
                });
                $('parent').css('position', 'relative');
                var fnc = 1;
                $(".field-sortable").each(function (i) {
                    var j = i % 2;
                    var k = (j == 0) ? 1 : 0;
                    $(this).find("tr.color-chnage").removeClass(colorArr[k]).addClass(colorArr[j]);
                    $(this).find("td.row-num-child").html(fnc);
                    fnc++;
                });
            }
        });
    },
    addAutoOptions: function () {
        var opt_arr = [];
        $("[name^='system_email_variable']").each(function () {
            var vname = $.trim($(this).val());
            if (vname) {
                vname = vname.toString();
                if (vname && vname.substring(0, 1) == "#") {
                    vname = vname.toString().substr(1);
                }
                if (vname && vname.substring(vname.length - 1) == "#") {
                    vname = vname.toString().substr(0, vname.length - 1);
                }
            }
            if (vname) {
                opt_arr.push({name: vname});
            }
        });
        tinyMCE.get("mse_email_message").settings.mentions.source = opt_arr;
    },
    CCEvents: function () {

    }
}
Project.modules.systememails.init();

jQuery.validator.addMethod("notRepeatedVariable", function (value, element) {
    var myArray = [], fal = true;
    $('input[id^="system_email_variable"]').each(function () {
        var js_sysVariable = $(this).val();
        if (jQuery.inArray(value, myArray) == '-1') {
            myArray.push(js_sysVariable);
            fal = true;
        } else if (js_sysVariable == value) {
            fal = false;
            return false;
        }
    });
    return fal;
}, "");

jQuery.validator.addMethod("defaultSYSVariable", function (value, element) {
    var sysArray = ['#vEmail#', '#vSubject#', '#vFromName#', '#vFromEmail#', '#vCCEmail#', '#vBCCEmail#'], fal = true;
    if (jQuery.inArray(value, sysArray) == '-1') {
        fal = true;
    } else {
        fal = false;
        return false;
    }
    return fal;
}, "");