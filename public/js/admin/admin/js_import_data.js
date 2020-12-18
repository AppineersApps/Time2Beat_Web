Project.modules.importcsv = {
    init: function () {
        this.initEvents();
        this.validateAdd();
    },
    validateAdd: function () {
        $('#frmimportcsvadd').validate({
            ignore: ".ignore-hidden",
            rules: {
                upload_module: {
                    required: true
                },
                upload_csv: {
                    required: true
                },
                upload_sheet: {
                    required: true
                },
                web_data_url: {
                    required: true
                }
            },
            messages: {
                upload_module: {
                    required: "Please select module name."
                },
                upload_csv: {
                    required: "Please upload data file."
                },
                upload_sheet: {
                    required: "Please select data sheet."
                },
                web_data_url: {
                    required: "Please enter data url."
                }
            },
            errorPlacement: function (error, element) {
                error.appendTo("#" + element.attr("name") + "Err");
            }
        });
    },
    validateImport: function () {
        $('#frmimportcsvread').validate({
            ignore: ".ignore-hidden",
            rules: {
                "map_column[]": {
                    notRepeatedVariable: true
                }
            },
            messages: {
                'map_column[]': {
                    notRepeatedVariable: "Please map different column names"
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") == "map_column[]") {
                    $("#import_error_msg").html(error);
                }
            }
        });
    },
    initEvents: function () {
        $("#upload_module").change(function () {
            var mod_val = $(this).val();
            if ($.inArray(mod_val, $import_media_modules) != -1) {
                $("#media_files_upload").slideDown('slow');
            } else {
                $("#media_files_upload").slideUp('slow');
            }
            $("#sample_zip_file").attr("href", $import_media_sample_url + "?upload_module=" + mod_val);
        });
        $("input[name='upload_location']").click(function () {
            var check_val = $("input[name='upload_location']:checked").val();
            $("#columns_separate_setting").addClass("hide-settings");
            $("#text_delimiter_setting").addClass("hide-settings");
            if (check_val == "cloud") {
                $(".local-drive-block").hide();
                $(".google-drive-block").show();
                $(".web-url-block").hide();
                $("#upload_csv").addClass("ignore-hidden");
                $("#upload_sheet").removeClass("ignore-hidden");
                $("#web_data_url").addClass("ignore-hidden");
            } else if (check_val == "web") {
                $(".local-drive-block").hide();
                $(".google-drive-block").hide();
                $(".web-url-block").show();
                $("#upload_csv").addClass("ignore-hidden");
                $("#upload_sheet").addClass("ignore-hidden");
                $("#web_data_url").removeClass("ignore-hidden");
                if ($("input[name='response_format']:checked").val() == "csv") {
                    $("#columns_separate_setting").removeClass("hide-settings");
                    $("#text_delimiter_setting").removeClass("hide-settings");
                }
            } else {
                $(".local-drive-block").show();
                $(".google-drive-block").hide();
                $(".web-url-block").hide();
                $("#upload_csv").removeClass("ignore-hidden");
                $("#upload_sheet").addClass("ignore-hidden");
                $("#web_data_url").addClass("ignore-hidden");
            }
        });
        $("#pick_google_sheet").click(function () {
            openAjaxURLFancyBox($import_gdrive_manage_url, {width: "85%", height: "85%", padding: 0});
        });
        $(".action-more-settings").click(function () {
            if (!$('#action_settings_anchor').hasClass("active")) {
                $(".toggle-more-settings").addClass("show-settings");
                $("#action_settings_anchor").addClass("active").attr("title", js_lang_label.GENERIC_LESS_SETTINGS).html(js_lang_label.GENERIC_LESS_SETTINGS);
                $("#action_settings_span").addClass("cut-icon-minus-2").removeClass("cut-icon-plus-2");
            } else {
                $(".toggle-more-settings").removeClass("show-settings");
                $("#action_settings_anchor").removeClass("active").attr("title", js_lang_label.GENERIC_MORE_SETTINGS).html(js_lang_label.GENERIC_MORE_SETTINGS);
                $("#action_settings_span").removeClass("cut-icon-minus-2").addClass("cut-icon-plus-2");
            }
        });
        $('#uploadify_upload_csv').fileupload({
            url: $import_upload_url,
            name: "upload_csv",
            temp: "temp_upload_csv",
            paramName: 'Filedata',
            maxFileSize: $import_valid_size,
            acceptFileTypes: $import_valid_ext,
            formData: {
                'mode': 'add'
            },
            add: function (e, data) {
                var upload_errors = [];
                var _input_name = $(this).fileupload('option', 'name');
                var _temp_name = $(this).fileupload('option', 'temp');
                var _form_data = $(this).fileupload('option', 'formData');
                var _file_size = $(this).fileupload('option', 'maxFileSize');
                var _file_type = $(this).fileupload('option', 'acceptFileTypes');

                var _input_val = data.originalFiles[0]['name'];
                var _input_size = data.originalFiles[0]['size'];
                $("#columns_separate_setting").addClass("hide-settings");
                $("#text_delimiter_setting").addClass("hide-settings");
                $("#upload_sheets_event").hide();
                if (_file_type != '*') {
                    var _input_ext = (_input_val) ? _input_val.substr(_input_val.lastIndexOf('.')) : "";
                    var accept_file_types = new RegExp('(\.|\/)(' + _file_type + ')$', 'i');
                    if (_input_ext && !accept_file_types.test(_input_ext)) {
                        upload_errors.push(js_lang_label.ACTION_FILE_TYPE_IS_NOT_ACCEPTABLE);
                    }
                    if (_input_ext && _input_ext.toLowerCase() == ".csv") {
                        $("#columns_separate_setting").removeClass("hide-settings");
                        $("#text_delimiter_setting").removeClass("hide-settings");
                    }
                }
                _file_size = _file_size * 1000;
                if (_input_size && _input_size > _file_size) {
                    upload_errors.push(js_lang_label.ACTION_FILE_SIZE_IS_TOO_LARGE);
                }
                if (upload_errors.length > 0) {
                    Project.setMessage(upload_errors.join('\n'), 0);
                } else {
                    $('#practive_' + _input_name).css('width', '0%');
                    $('#progress_' + _input_name).show();
                    _form_data['oldFile'] = $('#' + _temp_name).val();
                    $(this).fileupload('option', 'formData', _form_data);
                    $('#preview_' + _input_name).html(_input_val);
                    data.submit();
                }
            },
            done: function (e, data) {
                if (data && data.result) {
                    var _input_name = $(this).fileupload('option', 'name');
                    var _temp_name = $(this).fileupload('option', 'temp');
                    var jparse_data = $.parseJSON(data.result);
                    if (jparse_data.success == '0') {
                        Project.setMessage(jparse_data.message, 0);
                    } else {
                        $('#' + _input_name).val(jparse_data.uploadfile);
                        $('#' + _temp_name).val(jparse_data.oldfile);
                        displaySettingOntheFlyImage(_input_name, jparse_data);
                        setTimeout(function () {
                            $('#progress_' + _input_name).hide();
                        }, 1000);
                        if (jparse_data.is_multiple == '1' && jparse_data.sheets_html) {
                            $("#upload_sheets_html").html(jparse_data.sheets_html);
                            $("#upload_sheets_event").show().click();
                            Project.modules.importcsv.initUploadData();
                        }
                    }
                }
            },
            fail: function (e, data) {
                $.each(data.messages, function (index, error) {
                    Project.setMessage(error, 0);
                });
            },
            progressall: function (e, data) {
                var _input_name = $(this).fileupload('option', 'name');
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#practive_' + _input_name).css('width', progress + '%');
            }
        });
        $('#uploadify_upload_media').fileupload({
            url: $import_media_url,
            name: "upload_media",
            temp: "temp_upload_media",
            paramName: 'Filedata',
            maxFileSize: $import_media_size,
            acceptFileTypes: $import_media_ext,
            formData: {
                'mode': 'add'
            },
            add: function (e, data) {
                var upload_errors = [];
                var _input_name = $(this).fileupload('option', 'name');
                var _temp_name = $(this).fileupload('option', 'temp');
                var _form_data = $(this).fileupload('option', 'formData');
                var _file_size = $(this).fileupload('option', 'maxFileSize');
                var _file_type = $(this).fileupload('option', 'acceptFileTypes');

                var _input_val = data.originalFiles[0]['name'];
                var _input_size = data.originalFiles[0]['size'];
                if (_file_type != '*') {
                    var _input_ext = (_input_val) ? _input_val.substr(_input_val.lastIndexOf('.')) : "";
                    var accept_file_types = new RegExp('(\.|\/)(' + _file_type + ')$', 'i');
                    if (_input_ext && !accept_file_types.test(_input_ext)) {
                        upload_errors.push(js_lang_label.ACTION_FILE_TYPE_IS_NOT_ACCEPTABLE);
                    }
                }
                _file_size = _file_size * 1000;
                if (_input_size && _input_size > _file_size) {
                    upload_errors.push(js_lang_label.ACTION_FILE_SIZE_IS_TOO_LARGE);
                }
                if (upload_errors.length > 0) {
                    Project.setMessage(upload_errors.join('\n'), 0);
                } else {
                    $('#practive_' + _input_name).css('width', '0%');
                    $('#progress_' + _input_name).show();
                    _form_data['oldFile'] = $('#' + _temp_name).val();
                    $(this).fileupload('option', 'formData', _form_data);
                    $('#preview_' + _input_name).html(_input_val);
                    data.submit();
                }
            },
            done: function (e, data) {
                if (data && data.result) {
                    var _input_name = $(this).fileupload('option', 'name');
                    var _temp_name = $(this).fileupload('option', 'temp');
                    var jparse_data = $.parseJSON(data.result);
                    if (jparse_data.success == '0') {
                        Project.setMessage(jparse_data.message, 0);
                    } else {
                        $('#' + _input_name).val(jparse_data.uploadfile);
                        $('#' + _temp_name).val(jparse_data.oldfile);
                        displaySettingOntheFlyImage(_input_name, jparse_data);
                        setTimeout(function () {
                            $('#progress_' + _input_name).hide();
                        }, 1000);
                    }
                }
            },
            fail: function (e, data) {
                $.each(data.messages, function (index, error) {
                    Project.setMessage(error, 0);
                });
            },
            progressall: function (e, data) {
                var _input_name = $(this).fileupload('option', 'name');
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#practive_' + _input_name).css('width', progress + '%');
            }
        });
        $("#upload_sheets_event").fancybox({
            href: '#upload_sheets_html',
            type: 'inline',
            autoScale: false,
            //autoSize: false,
            openEffect: 'elastic',
            closeEffect: 'elastic',
            width: '75%',
            height: '75%',
            padding: 5
        });
        Project.modules.importcsv.initJsonView();
    },
    validlist: function () {
        $('.validation_info').click(function (e) {
            var valid_show_url = $import_valid_url + "&module_name=" + $("#import_module_name").val() + "&type=" + $(this).attr('aria-type');
            openAjaxURLFancyBox(valid_show_url, {width: "85%", height: "85%", padding: 10});
        });

    },
    getValidateAddCSV: function () {
        var retVal = $("#frmimportcsvadd").valid();
        if (retVal) {
            $('#import_error_msg').html('');
            var options = {
                url: $import_read_url,
                beforeSubmit: showAdminAjaxRequest,
                success: function (respText, statText, xhr, $form) {
                    Project.hide_adaxloading_div();
                    $("#importcsv").html(respText);
                    initializeBasicAjaxEvents();
                    Project.modules.importcsv.validateImport();
                    Project.modules.importcsv.initImportReadPage();
                    Project.modules.importcsv.validlist();

                }
            };
            $('#frmimportcsvadd').ajaxSubmit(options);
        }
        return false;
    },
    getValidateImportCSV: function () {
        if (!$("input[name^=skip_column]:checked").length) {
            alert("Please check atleast one column.");
            return false;
        }
        var retVal = $("#frmimportcsvread").valid();
        if (retVal) {
            $("#ajax_content_div").addClass("ajaxstate");
            var options = {
                url: $import_process_url,
                beforeSubmit: showAdminAjaxRequest,
                success: function (respText, statText, xhr, $form) {
                    $("#ajax_content_div").removeClass("ajaxstate");
                    Project.hide_adaxloading_div();
                    $("#importcsv").html(respText);
                    Project.modules.importcsv.initImportProcessPage();
                }
            };
            $('#frmimportcsvread').ajaxSubmit(options);
        }
        return false;
    },
    getValidateProcessCSV: function (type) {
        if (confirm("Are you sure want to perform these changes?")) {
            Project.show_adaxloading_div();
            $("#ajax_content_div").addClass("ajaxstate");
            var media_obj, media_fnd = 0;
            var mcnt = $("#media_count").val();
            var mevt = $("#media_event").val();
            var options = {
                url: $import_process_url + "?save_type=" + type,
                beforeSubmit: showAdminAjaxRequest,
                success: function (respText, statText, xhr, $form) {
                    $("#ajax_content_div").removeClass("ajaxstate");
                    Project.hide_adaxloading_div();
                    var res_arr = $.parseJSON(respText);
                    if (!mcnt || media_fnd == 0) {
                        if ($.isPlainObject(media_obj)) {
                            media_obj.close();
                        }
                        Project.modules.importcsv.loadFinishPage(res_arr);
                    }
                }
            };
            $('#frmimportcsvprocess').ajaxSubmit(options);
            if (mcnt > 0) {
                media_obj = new EventSource($import_media_event_url + "?media_event=" + mevt);
                media_obj.onmessage = function (event) {
                    if (event.data != "") {
                        var event_res = $.parseJSON(event.data);
                        if (event_res.success == '1') {
                            Project.hide_adaxloading_div();
                            var res_arr = event_res.content;
                            media_fnd = 1;
                            media_obj.close();
                            Project.modules.importcsv.loadFinishPage(res_arr);
                        }
                    }
                };
            }
        }
        return false;
    },
    loadFinishPage: function (res_arr) {
        var jmgcls = 1;
        if (res_arr.success == "0") {
            jmgcls = 0;
        }
        Project.setMessage(res_arr.message, jmgcls);
        if (res_arr.red_url && res_arr.red_url != "") {
            window.location.hash = res_arr.red_url;
        } else {
            loadCSVImportPage();
        }
    },
    initUploadData: function () {
        $(".select-sheet-data").click(function () {
            Project.show_adaxloading_div();
            var sheetObj = $(this).closest('th').find("input[name='sheetId']")
            var attr_id = $(sheetObj).attr("id");
            var attr_id_arr = (attr_id) ? attr_id.split("_") : [];
            if ($(this).hasClass("expand")) {
                $("#sheet_block_" + attr_id_arr[1]).hide();
                $(this).addClass("collapse").removeClass("expand").addClass("maximize").removeClass("minimize");
            } else if ($(this).hasClass("collapse")) {
                $("#sheet_block_" + attr_id_arr[1]).show();
                $(this).addClass("expand").removeClass("collapse").addClass("minimize").removeClass("maximize");
            }
            Project.hide_adaxloading_div();
        });
        $('#select_btn').click(function () {
            var sheetId = $("input[name='sheetId']:checked").val();
            if ($.trim($("input[name='sheetId']:checked").val()) == "") {
                alert("Please select any sheet.");
                return false;
            }
            $("#upload_index").val(sheetId);
            $.fancybox.close();
        });
    },
    initImportReadPage: function () {
        $("input[name^='skip_column']").click(function () {
            var ind = $(this).closest('.import-column-width').index();
            var dis = $(this).is(":checked") ? false : true;
            if (dis == true) {
                $(this).closest('.import-column-width').find('.import-map-column').addClass("column-disable");
            } else {
                $(this).closest('.import-column-width').find('.import-map-column').removeClass("column-disable");
            }
            $(".import-data-container").find("tr").each(function () {
                if (dis == true) {
                    $(this).find('.import-column-width').eq(ind).addClass("column-disable");
                } else {
                    $(this).find('.import-column-width').eq(ind).removeClass("column-disable");
                }
            });
        });
        $('#import_scroll_vertical').scroll(function () {
            $("#import_scroll_horizontal").scrollLeft($('#import_scroll_vertical').scrollLeft());
        });
    },
    initImportProcessPage: function () {
        $("#import_info_success").click(function () {
            var data = {
                type: "success",
                inserted: $("#track_inserted").val(),
                updated: $("#track_updated").val()
            }
            initImportInfoHelper($("#import_info_success"), data);
        });
        $("#import_info_failed").click(function () {
            var data = {
                type: "failed",
                failed: $("#track_failed").val()
            }
            initImportInfoHelper($("#import_info_failed"), data);
        });
        $("#import_info_duplicate").click(function () {
            var data = {
                type: "duplicate",
                duplicate: $("#track_duplicate").val()
            }
            initImportInfoHelper($("#import_info_duplicate"), data);
        });
        $("#import_info_skipped").click(function () {
            var data = {
                type: "skipped",
                valid: $("#track_valid").val(),
                lookup: $("#track_lookup").val()
            }
            initImportInfoHelper($("#import_info_skipped"), data);
        });
    },
    initDriveJSEvents: function () {
        $("#drive_auth_config").click(function () {
            $("#drive_config_containter").show();
            $("#drive_config_help").show();
            $("#drive_auth_help").hide();
        });
        $("#dropbox_auth_config").click(function () {
            $("#dropbox_config_containter").show();
            $("#dropbox_config_help").show();
            $("#dropbox_auth_help").hide();
        });
        $("#save_drive_config").click(function () {
            Project.show_adaxloading_div();
            $.ajax({
                type: 'POST',
                url: $import_gdrive_config_url,
                data: {
                    "type": "gdrive",
                    "client_id": $("#gdrive_client_id").val(),
                    "client_secret": $("#gdrive_client_secret").val()
                },
                success: function (resp) {
                    Project.hide_adaxloading_div();
                    var res_arr = $.parseJSON(resp);
                    if (res_arr.success == 0) {
                        alert(res_arr.message);
                    } else {
                        $("#drive_config_containter").hide();
                        $("#drive_config_help").hide();
                        $("#drive_auth_help").show();
                        $("#drive_auth_span").show();
                    }
                }
            });
        });
        $("#save_dropbox_config").click(function () {
            Project.show_adaxloading_div();
            $.ajax({
                type: 'POST',
                url: $import_gdrive_config_url,
                data: {
                    "type": "dropbox",
                    "client_id": $("#dropbox_client_id").val(),
                    "client_secret": $("#dropbox_client_secret").val()
                },
                success: function (resp) {
                    Project.hide_adaxloading_div();
                    var res_arr = $.parseJSON(resp);
                    if (res_arr.success == 0) {
                        alert(res_arr.message);
                    } else {
                        $("#dropbox_config_containter").hide();
                        $("#dropbox_config_help").hide();
                        $("#dropbox_auth_help").show();
                        $("#dropbox_auth_span").show();
                    }
                }
            });
        });
        $("#discard_drive_config").click(function () {
            $("#drive_config_containter").hide();
        });
        $("#discard_dropbox_config").click(function () {
            $("#dropbox_config_containter").hide();
        });
        $(".dlp-links a").click(function (e) {
            e.preventDefault();
            $(".dlp-links").removeClass("active");
            $(this).parent().addClass("active");

            // show hide auth related blocks
            var v_onitem = $(this).attr("onitem");
            $(".drp-auth-block").hide();
            $(".drp-content-area").hide();
            $(".drp-change-area").hide();
            if ($("input[name='tokenEnable']").val() == "Yes" && $("input[name='apiTypeHide']").val() == v_onitem) {
                $(".drp-change-area[onitem='" + v_onitem + "']").show();
                $(".drp-content-area[onitem='" + v_onitem + "']").show();
            } else {
                $(".drp-auth-block[onitem='" + v_onitem + "']").show();
            }
        });

        // Google drive Authenticate
        $("#drive_auth_btn").click(function () {
            var gd_auth_uri = $import_gdrive_auth_url + "?_nA=1";
            var gd_auth_pop_opts = "width=1000, height=600, location=no, menubar=no, resizable=no, scrollbars=no, top=100, left=100";
            window.open(gd_auth_uri, "_blank", gd_auth_pop_opts);
        });
        // Dropbox Authenticate
        $("#dropbox_auth_btn").click(function () {
            var gd_auth_uri = $import_dropbox_auth_url + "?_nA=1";
            var gd_auth_pop_opts = "width=1000, height=600, location=no, menubar=no, resizable=no, scrollbars=no, top=100, left=100";
            window.open(gd_auth_uri, "_blank", gd_auth_pop_opts);
        });
        // Change drive accounts
        $(".drp-change-user").click(function () {
            var ongitem = $(this).parent().attr("onitem");
            $(".drp-auth-block[onitem='" + ongitem + "']").show();
            $(".drp-change-area[onitem='" + ongitem + "']").hide();
            $(".drp-content-area[onitem='" + ongitem + "']").hide();
        });
    },
    initGDrive: function () {
        if ($(".dlp-links.active a").length && $(".dlp-links.active a").attr("onitem") == "gdrive") {
            if ($("input[name='tokenEnable']").val() == "Yes") {
                setTimeout(function () {
                    Project.modules.importcsv.getGDriveData({'type': 'docs'});
                }, 500);
            } else {
                $(".drp-auth-block[onitem='gdrive']").show();
                $(".dlp-links.active a").trigger("click");
            }
        }
    },
    initGdriveJSEvents: function () {
        $('#gd_next_btn').click(function (e) {
            var docId = $.trim($("input[name='docId']:checked").val());
            var docFor = $("input[name='docId']:checked").attr("id");
            if (docId == "") {
                alert("Please select any document.");
                return false;
            }
            Project.modules.importcsv.getGDriveData({'type': 'sheets', 'docId': docId, "docName": $("label[for='" + docFor + "']").text()});
        });
    },
    getGDriveData: function (opts) {
        Project.show_adaxloading_div();
        $.ajax({
            type: 'POST',
            url: $import_gdrive_data_url,
            data: opts,
            success: function (resp) {
                Project.hide_adaxloading_div();
                $(".dlp-links.active a").trigger("click");
                $(".drp-auth-block[onitem='gdrive']").hide();
                $("div.drp-change-area[onitem='gdrive']").show();
                $("div.drp-content-area[onitem='gdrive']").show();
                $(".drp-content-area[onitem='gdrive'] .drp-content-block").html(resp);
                if (opts.type == "sheets") {
                    Project.modules.importcsv.initSheetData();
                } else {
                    Project.modules.importcsv.initGdriveJSEvents();
                }
            }
        });
    },
    initSheetData: function () {
        $(".fetch-sheet-data").click(function () {
            Project.show_adaxloading_div();
            var sheetObj = $(this).closest('th').find("input[name='sheetId']")
            var attr_id = $(sheetObj).attr("id");
            var attr_id_arr = (attr_id) ? attr_id.split("_") : [];
            if ($(this).hasClass("expand")) {
                $("#gd_sheet_block_" + attr_id_arr[1]).hide();
                $(this).addClass("collapse").removeClass("expand").addClass("maximize").removeClass("minimize");
            } else if ($(this).hasClass("collapse")) {
                $("#gd_sheet_block_" + attr_id_arr[1]).show();
                $(this).addClass("expand").removeClass("collapse").addClass("minimize").removeClass("maximize");
            }
            Project.hide_adaxloading_div();
        });
        $('#gd_submit_btn').click(function () {
            var sheetId = $("input[name='sheetId']:checked").val();
            if ($.trim($("input[name='sheetId']:checked").val()) == "") {
                alert("Please select any sheet.");
                return false;
            }
            var docId = $("input[name='docId']").val();
            var docFile = $("input[name='docFile']").val();
            Project.show_adaxloading_div();
            $.ajax({
                type: 'POST',
                url: $import_gdrive_save_url,
                data: {sheetId: sheetId, docId: docId, docFile: docFile},
                success: function (resp) {
                    Project.hide_adaxloading_div();
                    var res_arr = $.parseJSON(resp);
                    if (res_arr.success == 0) {
                        alert(res_arr.message);
                    } else {
                        parent.$("#upload_sheet").val(res_arr.file_name);
                        $.fancybox.close();
                    }
                }
            });
        });
        $("#gd_back_btn").click(function () {
            Project.modules.importcsv.getGDriveData({'type': 'docs'});
        });
    },
    initJsonView: function () {
        $("#web_data_url_browse").show();
        $("input[name='response_format']").click(function () {
            var rfVal = $("input[name='response_format']:checked").val();
            if (rfVal == "csv") {
                $("#first_row_setting").removeClass("hide-settings");
                $("#columns_separate_setting").removeClass("hide-settings");
                $("#text_delimiter_setting").removeClass("hide-settings");
            } else {
                $("#first_row_setting").addClass("hide-settings");
                $("#import_first_row").val("Yes");
                $("#columns_separate_setting").addClass("hide-settings");
                $("#text_delimiter_setting").addClass("hide-settings");
            }
        });
        $("#btn_pick_json_xml").click(function () {
            var rkvVal = $("input[name='responsekeyvalue']:checked").val();
            if ($.trim(rkvVal) != "") {
                $("#web_data_url_keypath").val(rkvVal);
                parent.$.fancybox.close();
            } else {
                alert("Please select any array value.");
            }
        });
        $("#web_data_url_browse").click(function () {
            var json_uri = $("#web_data_url").val();
            var rfVal = $("input[name='response_format']:checked").val();
            if (json_uri == "") {
                alert("Please enter web url");
                return false;
            }
            if ($.inArray(rfVal, ["json", "xml"]) == -1) {
                alert("Selection will available for JSON or XML only.");
                return false;
            }
            Project.show_adaxloading_div();
            if ($.trim(json_uri) == "") {
                jqueryUIalertBox("Please enter data url.");
                Project.hide_adaxloading_div();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: $import_web_data_url,
                dataType: 'json',
                data: {'web_url': json_uri, 'url_type': rfVal},
                success: function (resp) {
                    Project.hide_adaxloading_div();
                    if ($.trim(resp) == "") {
                        jqueryUIalertBox("Something went wrong. Please enter proper data.");
                        return false;
                    }
                    $("#web_url_pick_item").jsonEditor(resp, {'viewOnly': true, 'arrayLimit': 4});
                    var base_obj = {
                        'type': 'inline',
                        'href': '#web_url_response_block',
                        'width': '70%',
                        'height': '70%',
                        autoSize: false,
                        'padding': 0
                    };
                    var tmpl_obj = getFancyboxTPLParams();
                    var final_obj = $.extend({}, base_obj, tmpl_obj);
                    $.fancybox.open(final_obj);
                }
            });
        });
    },
    initDropbox: function () {
        if ($(".dlp-links.active a").length && $(".dlp-links.active a").attr("onitem") == "dropbox") {
            if ($("input[name='tokenEnable']").val() == "Yes") {
                setTimeout(function () {
                    Project.modules.importcsv.getDropboxData({'type': 'files'});
                }, 500);
            } else {
                $(".drp-auth-block[onitem='dropbox']").show();
                $(".dlp-links.active a").trigger("click");
            }
        }
    },
    initDropboxJSEvents: function () {
        $("input[name='fileId']").click(function () {
            $("input[name='fileRev']").val($(this).attr('arev'));
        });
        $('#dbx_next_btn').click(function () {
            var fileId = $("input[name='fileId']:checked").val();
            if ($.trim(fileId) == "") {
                alert("Please select any file.");
                return false;
            }
            Project.modules.importcsv.getDropboxData({
                'type': 'content', 'fileId': fileId, 'fileRev': $("input[name='fileRev']").val()
            });
        });
    },
    getDropboxData: function (opts) {
        Project.show_adaxloading_div();
        $.ajax({
            type: 'POST',
            url: $import_dropbox_data_url,
            data: opts,
            success: function (resp) {
                Project.hide_adaxloading_div();
                $(".dlp-links.active a").trigger("click");
                $(".drp-auth-block[onitem='dropbox']").hide();
                $("div.drp-change-area[onitem='dropbox']").show();
                $("div.drp-content-area[onitem='dropbox']").show();
                $(".drp-content-area[onitem='dropbox'] .drp-content-block").html(resp);
                if (opts.type == 'content') {
                    Project.modules.importcsv.initDropboxContentEvents();
                } else {
                    Project.modules.importcsv.initDropboxJSEvents();
                }
            }
        });
    },
    initDropboxContentEvents: function () {
        $(".fetch-sheet-data").click(function () {
            Project.show_adaxloading_div();
            var sheetObj = $(this).closest('th').find("input[name='fileTabId']")
            var attr_id = $(sheetObj).attr("id");
            var attr_id_arr = (attr_id) ? attr_id.split("_") : [];
            if ($(this).hasClass("expand")) {
                $("#gd_sheet_block_" + attr_id_arr[1]).hide();
                $(this).addClass("collapse").removeClass("expand").addClass("maximize").removeClass("minimize");
            } else if ($(this).hasClass("collapse")) {
                $("#gd_sheet_block_" + attr_id_arr[1]).show();
                $(this).addClass("expand").removeClass("collapse").addClass("minimize").removeClass("maximize");
            }
            Project.hide_adaxloading_div();
        });

        $('#dbx_submit_btn').click(function () {
            var fileTabId = $("input[name='fileTabId']:checked").val();
            if ($.trim(fileTabId) == "") {
                alert("Please select any sheet.");
                return false;
            }
            var docFile = $("input[name='docFile']").val();
            Project.show_adaxloading_div();
            $.ajax({
                type: 'POST',
                url: $import_dropbox_save_url,
                data: {'fileTabId': fileTabId, 'docFile': docFile},
                success: function (resp) {
                    Project.hide_adaxloading_div();
                    var res_arr = $.parseJSON(resp);
                    if (res_arr.success == 0) {
                        alert(res_arr.message);
                    } else {
                        parent.$("#upload_sheet").val(res_arr.file_name);
                        $.fancybox.close();
                    }
                }
            });
        });
        $("#dbx_back_btn").click(function () {
            Project.modules.importcsv.getDropboxData({'type': 'files'});
        });
    },
    setDriveHiddenParams: function (vdrive) {
        $("input[name='apiTypeHide']").val(vdrive);
        $("input[name='tokenEnable']").val("Yes");
    }
}
function loadCSVImportPage() {
    var curr_time = new Date().getTime();
    window.location.hash = 'tools/import/index|tLoadFP|' + curr_time;
}
function loadImportHistoryPage() {
    openAjaxURLFancyBox($import_histroy_url, {width: "75%", height: "75%"});
    return false;
}
function initImportInfoHelper(ele, data) {
    data['module_name'] = $("#import_module_name").val();
    var base_obj = {
        href: $import_info_url + "&iframe=true",
        type: 'ajax',
        autoScale: false,
        //autoSize: false,
        openEffect: 'elastic',
        closeEffect: 'elastic',
        width: '80%',
        height: '80%',
        padding: 5,
        beforeLoad: function () {
            this.ajax.type = "POST";
            this.ajax.data = data;
        }
    }
    var tmpl_obj = getFancyboxTPLParams();
    var final_obj = $.extend({}, base_obj, tmpl_obj);
    $.fancybox.open(final_obj);
    return false;
}
jQuery.validator.addMethod("notRepeatedVariable", function (value, element) {
    var myArray = [];
    var i = 1, fal = true;
    if ($(element).hasClass("column-disable")) {
        return true;
    }
    $('select[id^="map_column"]').not(".column-disable").each(function () {
        var js_map_col = $(this).val();
        if (jQuery.inArray(js_map_col, myArray) == '-1') {
            myArray[i] = js_map_col;
            i++;
        } else {
            fal = false;
            return false;
        }
    });
    return fal;
}, "");