Project.modules.backup = {
    init: function () {

    },
    showFullBackupListing: function () {
        var that = this, total_rows, js_col_name_arr = [], grid_comp_time = true, load_comp_time = true;
        var grid_id = el_tpl_settings.main_grid_id, pager_id = el_tpl_settings.main_pager_id, wrapper_id = el_tpl_settings.main_wrapper_id;
        for (var i in js_col_name_json) {
            js_col_name_arr.push(js_col_name_json[i]['label']);
        }
        var force_width = $("#main_content_div").width() - 30;
        getColumnsWidth(el_grid_settings.enc_location + '_cw', grid_id, js_col_model_json);
        jQuery("#list2").jqGrid({
            editurl: el_grid_settings.delete_url,
            data: js_data_json,
            datatype: "local",
            colNames: js_col_name_arr,
            colModel: js_col_model_json,
            rowNum: el_tpl_settings.grid_rec_limit,
            pgnumbers: (el_theme_settings.grid_pgnumbers) ? true : false,
            pgnumlimit: parseInt(el_theme_settings.grid_pgnumlimit),
            pagingpos: el_theme_settings.grid_pagingpos,
            rowList: [10, 20, 30, 50, 100, 200, 500],
            sortname: el_grid_settings.default_sort,
            sortorder: el_grid_settings.sort_order,
            altRows: true,
            altclass: 'evenRow',
            multiselectWidth: 30,
            viewrecords: true,
            multiselect: true,
            multiboxonly: true,
            caption: false,
            hidegrid: false,
            pager: (el_tpl_settings.grid_bot_menu == 'Y') ? "#pager2" : "",
            toppager: (el_tpl_settings.grid_top_menu == 'Y') ? true : false,
            toppaging: (el_tpl_settings.grid_top_menu == 'Y') ? true : false,
            sortable: {
                update: function (permutation) {
                    setColumnsPosition(el_grid_settings.enc_location + '_cp', permutation, grid_id, js_col_model_json);
                }
            },
            searchGrid: {
                multipleSearch: true
            },
            forceApply: true,
            forceWidth: force_width,
            width: force_width,
            height: 400,
            autowidth: true,
            shrinkToFit: 800,
            fixed: true,
            grouping: true,
            groupingView: {
                groupField: ['month'],
                groupOrder: ['desc'],
                groupText: ['<b>{0}</b>'],
                groupColumnShow: [false],
                groupSummary: [true],
                showSummaryOnHide: true,
                groupCollapse: false,
                groupDataSorted: true
            },
            //footerrow: true, 
            //userDataOnFooter: true,
            beforeRequest: function () {
                getColumnsPosition(el_grid_settings.enc_location + '_cp', grid_id);
            },
            loadComplete: function (data) {
                $("#" + grid_id + "_messages_html").remove();
                $("#selAllRows").val('false');
                if (data) {
                    total_rows = data.records;
                }
                // Resizing Grid
                if (load_comp_time) {
                    load_comp_time = false;
                } else {
                    resizeGridWidth();
                    checkColumnsWidth(el_grid_settings.enc_location + '_cw', grid_id);
                }
            },
            gridComplete: function () {
                $(".ui-jqgrid-sortable").mousedown(function () {
                    $(this).css('cursor', 'crosshair');
                });
                $(".ui-jqgrid-sortable").mouseup(function () {
                    $(this).css({
                        cursor: 'pointer'
                    });
                });
                // Resizing Grid
                if (grid_comp_time) {
                    grid_comp_time = false;
                } else {
                    resizeGridWidth();
                }
            },
            onSortCol: function (index, iCol, sortorder) {

            },
            resizeStop: function (newwidth, index) {
                setColumnsWidth(el_grid_settings.enc_location + '_cw', grid_id);
            },
            beforeSelectRow: function (rowid, e) {
                multiSelectHandler(rowid, e);
            }
        });
        jQuery("#" + grid_id).jqGrid('navGrid', '#' + pager_id, {
            cloneToTop: true,
            add: false,
            edit: false,
            search: false,
            del: (el_grid_settings.permit_del_btn == '1') ? true : false,
            delicon_p: (el_theme_settings.grid_icons.del) ? 'uigrid-del-btn del-icon-only' : "uigrid-del-btn",
            deltext: (el_theme_settings.grid_icons.del) ? '' : js_lang_label.GENERIC_GRID_DELETE,
            alerttext: js_lang_label.GENERIC_PLEASE_SELECT_ANY_RECORD,
            refreshicon_p: (el_theme_settings.grid_icons.refresh) ? 'uigrid-refresh-btn refresh-icon-only' : "uigrid-refresh-btn",
            refreshtext: (el_theme_settings.grid_icons.refresh) ? '' : js_lang_label.GENERIC_GRID_SHOW_ALL,
            refreshtitle: js_lang_label.GENERIC_REFRESH_LISTING,
            afterRefresh: function () {
                $(".search-chosen-select").find("option").removeAttr("selected");
                $(".search-chosen-select").trigger("chosen:updated");
            }
        }, {
            // edit options
        }, {
            // add options
        }, {
            // delete options
            width: 350,
            caption: js_lang_label.GENERIC_GRID_DELETE,
            msg: js_lang_label.GENERIC_ARE_YOU_SURE_WANT_TO_DELETE_SELECTED_RECORDS,
            bSubmit: js_lang_label.GENERIC_GRID_DELETE,
            bCancel: js_lang_label.GENERIC_CANCEL,
            modal: true,
            closeOnEscape: true
        }, {
            //del options
        });
        if (el_grid_settings.permit_add_btn == '1') {
            jQuery("#" + grid_id).navButtonAdd('#' + pager_id, {
                caption: (el_theme_settings.grid_icons.add) ? '' : js_lang_label.GENERIC_CREATE_BACKUP,
                title: js_lang_label.GENERIC_CREATE_BACKUP,
                buttonicon: "ui-icon-plus",
                buttonicon_p: (el_theme_settings.grid_icons.add) ? 'uigrid-add-btn add-icon-only' : 'uigrid-add-btn',
                onClickButton: function () {
                    that.createNewBackup();
                },
                position: "first"
            });
            jQuery("#" + grid_id).navButtonAdd('#' + grid_id + '_toppager_left', {
                caption: (el_theme_settings.grid_icons.add) ? '' : js_lang_label.GENERIC_CREATE_BACKUP,
                title: js_lang_label.GENERIC_CREATE_BACKUP,
                buttonicon: "ui-icon-plus",
                buttonicon_p: (el_theme_settings.grid_icons.add) ? 'uigrid-add-btn add-icon-only' : 'uigrid-add-btn',
                onClickButton: function () {
                    that.createNewBackup();
                },
                position: "first"
            });
        }
        jQuery("#" + grid_id).navButtonAdd('#' + pager_id, {
            caption: (el_theme_settings.grid_icons.columns) ? '' : js_lang_label.GENERIC_GRID_COLUMNS,
            title: js_lang_label.GENERIC_GRID_HIDESHOW_COLUMNS,
            buttonicon: "ui-icon-columns",
            buttonicon_p: (el_theme_settings.grid_icons.columns) ? 'uigrid-col-btn col-icon-only' : 'uigrid-col-btn',
            onClickButton: function () {
                jQuery("#" + grid_id).jqGrid('columnChooser', {
                    'classname': 'grid-columns-picker',
                    'msel_opts': {
                        'autoOpen': true,
                        'beforeopen': function (event, ui) {
                            applyUIButtonCSS();
                        }
                    },
                    "beforeSubmit": function (div_id) {
                        if ($("#" + div_id).find('select').val() != null) {
                            return true;
                        } else {
                            jQuery.jgrid.info_dialog(js_lang_label.GENERIC_GRID_ERROR, js_lang_label.GENERIC_GRID_PLEASE_SELECT_ATLEAST_ONE_COLUMN, js_lang_label.GENERIC_GRID_OK);
                            return false;
                        }
                    },
                    "done": function (perm) {
                        $("#" + grid_id).trigger('reloadGrid');
                    }
                });
            },
            position: "last"
        });
        jQuery("#" + grid_id).navButtonAdd('#' + grid_id + '_toppager_left', {
            caption: (el_theme_settings.grid_icons.columns) ? '' : js_lang_label.GENERIC_GRID_COLUMNS,
            title: js_lang_label.GENERIC_GRID_HIDESHOW_COLUMNS,
            buttonicon: "ui-icon-columns",
            buttonicon_p: (el_theme_settings.grid_icons.columns) ? 'uigrid-col-btn col-icon-only' : 'uigrid-col-btn',
            onClickButton: function () {
                jQuery("#" + grid_id).jqGrid('columnChooser', {
                    'classname': 'grid-columns-picker',
                    'msel_opts': {
                        'autoOpen': true,
                        'beforeopen': function (event, ui) {
                            applyUIButtonCSS();
                        }
                    },
                    "beforeSubmit": function (div_id) {
                        if ($("#" + div_id).find('select').val() != null) {
                            return true;
                        } else {
                            jQuery.jgrid.info_dialog(js_lang_label.GENERIC_GRID_ERROR, js_lang_label.GENERIC_GRID_PLEASE_SELECT_ATLEAST_ONE_COLUMN, js_lang_label.GENERIC_GRID_OK);
                            return false;
                        }
                    },
                    "done": function (perm) {
                        $("#" + grid_id).trigger('reloadGrid');
                    }
                });
            },
            position: "last"
        });
        var orgViewModal = $.jgrid.viewModal;
        $.extend($.jgrid, {
            viewModal: function (selector, o) {
                if (selector == '#searchmodfbox_' + o.gid || selector == '#alertmod' || selector == "#delmod" + o.gid || selector == "#info_dialog") {
                    var of = jQuery("#gbox_" + el_tpl_settings.main_grid_id).offset();
                    var w = jQuery("#gbox_" + el_tpl_settings.main_grid_id).width();
                    var h = jQuery("#gbox_" + el_tpl_settings.main_grid_id).height();
                    var w1 = $(selector).width();
                    var h1 = $(selector).height();
                    $(selector).css({
                        'top': of.top + ((h - h1) / 2) - 40,
                        'left': 'calc(50% - ' + w1 / 2 + 'px)'
                    });
                }
                orgViewModal.call(this, selector, o);
            }
        });
        var oldInfoDialog = $.jgrid.info_dialog;
        $.extend($.jgrid, {
            info_dialog: function (caption, content, c_b, modalopt) {
                return oldInfoDialog.call(this, caption, content, c_b, modalopt);
            }
        });
    },
    showTableBackupListing: function () {
        var that = this, total_rows, js_col_name_arr = [], grid_comp_time = true, load_comp_time = true;
        var grid_id = el_tpl_settings.main_grid_id, pager_id = el_tpl_settings.main_pager_id, wrapper_id = el_tpl_settings.main_wrapper_id;
        for (var i in js_col_name_json) {
            js_col_name_arr.push(js_col_name_json[i]['label']);
        }
        var force_width = $("#main_content_div").width() - 30;
        getColumnsWidth(el_grid_settings.enc_location + '_cw', grid_id, js_col_model_json);
        jQuery("#list2").jqGrid({
            editurl: el_grid_settings.delete_url,
            data: js_data_json,
            datatype: "local",
            colNames: js_col_name_arr,
            colModel: js_col_model_json,
            rowNum: el_tpl_settings.grid_rec_limit,
            pgnumbers: (el_theme_settings.grid_pgnumbers) ? true : false,
            pgnumlimit: parseInt(el_theme_settings.grid_pgnumlimit),
            pagingpos: el_theme_settings.grid_pagingpos,
            rowList: [10, 20, 30, 50, 100, 200, 500],
            sortname: el_grid_settings.default_sort,
            sortorder: el_grid_settings.sort_order,
            altRows: true,
            altclass: 'evenRow',
            multiselectWidth: 30,
            viewrecords: true,
            multiselect: true,
            multiboxonly: true,
            caption: false,
            hidegrid: false,
            pager: (el_tpl_settings.grid_bot_menu == 'Y') ? "#pager2" : "",
            toppager: (el_tpl_settings.grid_top_menu == 'Y') ? true : false,
            toppaging: (el_tpl_settings.grid_top_menu == 'Y') ? true : false,
            sortable: {
                update: function (permutation) {
                    setColumnsPosition(el_grid_settings.enc_location + '_cp', permutation, grid_id, js_col_model_json);
                }
            },
            searchGrid: {
                multipleSearch: true
            },
            forceApply: true,
            forceWidth: force_width,
            width: force_width,
            height: 400,
            autowidth: true,
            shrinkToFit: 800,
            fixed: true,
            grouping: false,
            beforeRequest: function () {
                getColumnsPosition(el_grid_settings.enc_location + '_cp', grid_id);
            },
            loadComplete: function (data) {
                $("#" + grid_id + "_messages_html").remove();
                $("#selAllRows").val('false');
                if (data) {
                    total_rows = data.records;
                }
                // Resizing Grid
                if (load_comp_time) {
                    load_comp_time = false;
                } else {
                    resizeGridWidth();
                    checkColumnsWidth(el_grid_settings.enc_location + '_cw', grid_id);
                }
            },
            gridComplete: function () {
                $(".ui-jqgrid-sortable").mousedown(function () {
                    $(this).css('cursor', 'crosshair');
                });
                $(".ui-jqgrid-sortable").mouseup(function () {
                    $(this).css({
                        cursor: 'pointer'
                    });
                });
                // Resizing Grid
                if (grid_comp_time) {
                    grid_comp_time = false;
                } else {
                    resizeGridWidth();
                }
            },
            onSortCol: function (index, iCol, sortorder) {

            },
            resizeStop: function (newwidth, index) {
                setColumnsWidth(el_grid_settings.enc_location + '_cw', grid_id);
            },
            beforeSelectRow: function (rowid, e) {
                multiSelectHandler(rowid, e);
            }
        });

        jQuery("#" + grid_id).jqGrid('filterToolbar', {
            stringResult: true,
            searchOnEnter: false,
            searchOperators: (el_theme_settings.grid_searchopt) ? true : false,
            operandTitle: js_lang_label.GENERIC_CLICK_TO_SELECT_SEARCH_OPERATION,
            clearTitle: js_lang_label.GENERIC_CLEAR_SEARCH_VALUE
        });

        jQuery("#" + grid_id).jqGrid('navGrid', '#' + pager_id, {
            cloneToTop: true,
            add: false,
            edit: false,
            search: false,
            del: false,
            alerttext: js_lang_label.GENERIC_PLEASE_SELECT_ANY_RECORD,
            refreshicon_p: (el_theme_settings.grid_icons.refresh) ? 'uigrid-refresh-btn refresh-icon-only' : "uigrid-refresh-btn",
            refreshtext: (el_theme_settings.grid_icons.refresh) ? '' : js_lang_label.GENERIC_GRID_SHOW_ALL,
            refreshtitle: js_lang_label.GENERIC_REFRESH_LISTING,
            afterRefresh: function () {
                $(".search-chosen-select").find("option").removeAttr("selected");
                $(".search-chosen-select").trigger("chosen:updated");
            }
        }, {
            // edit options
        }, {
            // add options
        }, {
            // delete options
            width: 350,
            caption: js_lang_label.GENERIC_GRID_DELETE,
            msg: js_lang_label.GENERIC_ARE_YOU_SURE_WANT_TO_DELETE_SELECTED_RECORDS,
            bSubmit: js_lang_label.GENERIC_GRID_DELETE,
            bCancel: js_lang_label.GENERIC_CANCEL,
            modal: true,
            closeOnEscape: true
        }, {
            //del options
        });

        if (el_grid_settings.permit_add_btn == '1') {
            jQuery("#" + grid_id).navButtonAdd('#' + pager_id, {
                caption: (el_theme_settings.grid_icons.add) ? '' : js_lang_label.GENERIC_CREATE_BACKUP,
                title: js_lang_label.GENERIC_CREATE_BACKUP,
                buttonicon: "ui-icon-plus",
                buttonicon_p: (el_theme_settings.grid_icons.add) ? 'uigrid-add-btn add-icon-only' : 'uigrid-add-btn',
                onClickButton: function () {
                    var id_list_arr = $(this).getGridParam('selarrrow');
                    if (!id_list_arr || id_list_arr.length == 0) {
                        var label_elem = '<div />';
                        var label_text = js_lang_label.GENERIC_PLEASE_SELECT_ANY_RECORD_TO_TAKE_BACKUP;
                        var option_params = {
                            title: "Backup",
                            dialogClass: "dialog-confirm-box grid-backup-cnf",
                            buttons: [{
                                    text: js_lang_label.GENERIC_BACKUP,
                                    bt_type: 'backup',
                                    click: function () {
                                        $(this).remove();
                                    }
                                }]
                        }
                        jqueryUIdialogBox(label_elem, label_text, option_params);
                    } else {
                        var id_str = id_list_arr.join(",");
                        that.createTableBackup(id_str, 'backup_table');
                    }
                },
                position: "first"
            });
            jQuery("#" + grid_id).navButtonAdd('#' + grid_id + '_toppager_left', {
                caption: (el_theme_settings.grid_icons.add) ? '' : js_lang_label.GENERIC_CREATE_BACKUP,
                title: js_lang_label.GENERIC_CREATE_BACKUP,
                buttonicon: "ui-icon-plus",
                buttonicon_p: (el_theme_settings.grid_icons.add) ? 'uigrid-add-btn add-icon-only' : 'uigrid-add-btn',
                onClickButton: function () {
                    var id_list_arr = $(this).getGridParam('selarrrow');
                    if (!id_list_arr || id_list_arr.length == 0) {
                        var label_elem = '<div />';
                        var label_text = js_lang_label.GENERIC_PLEASE_SELECT_ANY_RECORD_TO_TAKE_BACKUP;
                        var option_params = {
                            title: "Backup",
                            dialogClass: "dialog-confirm-box grid-backup-cnf",
                            buttons: [{
                                    text: js_lang_label.GENERIC_BACKUP,
                                    bt_type: 'backup',
                                    click: function () {
                                        $(this).remove();
                                    }
                                }]
                        }
                        jqueryUIdialogBox(label_elem, label_text, option_params);
                    } else {
                        var id_str = id_list_arr.join(",");
                        that.createTableBackup(id_str, 'backup_table');
                    }
                },
                position: "first",
                id: 'btn_top_backup'
            });
        }
        jQuery("#" + grid_id).navButtonAdd('#' + pager_id, {
            caption: (el_theme_settings.grid_icons.export) ? '' : js_lang_label.GENERIC_BACKUP_AND_DOWNLOAD,
            title: js_lang_label.GENERIC_BACKUP_AND_DOWNLOAD,
            buttonicon: "ui-icon-export",
            buttonicon_p: (el_theme_settings.grid_icons.export) ? 'uigrid-export-btn export-icon-only' : 'uigrid-export-btn',
            onClickButton: function () {
                var id_list_arr = $(this).getGridParam('selarrrow');
                if (!id_list_arr || id_list_arr.length == 0) {
                    var label_elem = '<div />';
                    var label_text = js_lang_label.GENERIC_PLEASE_SELECT_ANY_RECORD_TO_BACKUP_AND_DOWNLOAD;
                    var option_params = {
                        title: "Backup",
                        dialogClass: "dialog-confirm-box grid-backup-cnf",
                        buttons: [{
                                text: js_lang_label.GENERIC_BACKUP,
                                bt_type: 'backup',
                                click: function () {
                                    $(this).remove();
                                }
                            }]
                    }
                    jqueryUIdialogBox(label_elem, label_text, option_params);
                } else {
                    var id_str = id_list_arr.join(",");
                    that.createTableBackup(id_str, 'backup_download');
                }
            },
            position: "last",
            id: 'btn_bot_download',
            afterButtonId: "btn_bot_backup"
        });
        jQuery("#" + grid_id).navButtonAdd('#' + grid_id + '_toppager_left', {
            caption: (el_theme_settings.grid_icons.export) ? '' : js_lang_label.GENERIC_BACKUP_AND_DOWNLOAD,
            title: js_lang_label.GENERIC_BACKUP_AND_DOWNLOAD,
            buttonicon: "ui-icon-export",
            buttonicon_p: (el_theme_settings.grid_icons.export) ? 'uigrid-export-btn export-icon-only' : 'uigrid-export-btn',
            onClickButton: function () {
                var id_list_arr = $(this).getGridParam('selarrrow');
                if (!id_list_arr || id_list_arr.length == 0) {
                    var label_elem = '<div />';
                    var label_text = js_lang_label.GENERIC_PLEASE_SELECT_ANY_RECORD_TO_BACKUP_AND_DOWNLOAD;
                    var option_params = {
                        title: "Backup",
                        dialogClass: "dialog-confirm-box grid-backup-cnf",
                        buttons: [{
                                text: js_lang_label.GENERIC_BACKUP,
                                bt_type: 'backup',
                                click: function () {
                                    $(this).remove();
                                }
                            }]
                    }
                    jqueryUIdialogBox(label_elem, label_text, option_params);
                } else {
                    var id_str = id_list_arr.join(",");
                    that.createTableBackup(id_str, 'backup_download');
                }
            },
            position: "last",
            id: 'btn_top_download',
            afterButtonId: "btn_top_backup"
        });
        var orgViewModal = $.jgrid.viewModal;
        $.extend($.jgrid, {
            viewModal: function (selector, o) {
                if (selector == '#searchmodfbox_' + o.gid || selector == '#alertmod' || selector == "#delmod" + o.gid || selector == "#info_dialog") {
                    var of = jQuery("#gbox_" + el_tpl_settings.main_grid_id).offset();
                    var w = jQuery("#gbox_" + el_tpl_settings.main_grid_id).width();
                    var h = jQuery("#gbox_" + el_tpl_settings.main_grid_id).height();
                    var w1 = $(selector).width();
                    var h1 = $(selector).height();
                    $(selector).css({
                        'top': of.top + ((h - h1) / 2) - 40,
                        'left': 'calc(50% - ' + w1 / 2 + 'px)'
                    });
                }
                orgViewModal.call(this, selector, o);
            }
        });
        var oldInfoDialog = $.jgrid.info_dialog;
        $.extend($.jgrid, {
            info_dialog: function (caption, content, c_b, modalopt) {
                return oldInfoDialog.call(this, caption, content, c_b, modalopt);
            }
        });
    },
    createNewBackup: function () {
        $("#btype").val("full");
        var options = {
            url: el_grid_settings.save_url,
            beforeSubmit: showAdminAjaxRequest,
            success: function (respText, statText, xhr, $form) {
                var resArr = $.parseJSON(respText);
                responseAjaxDataSubmission(resArr);
                if (resArr.success == "0") {
                    return false;
                } else if (resArr.return_url != "") {
                    window.location.hash = resArr.return_url;
                }
            }
        };
        $('#frmbackupsave').ajaxSubmit(options);
        return false;
    },
    createTableBackup: function (id_str, type) {
        $("#id_arr").val(id_str);
        $("#btype").val(type);
        if (type == "backup_download") {
            $("#frmbackupsave").submit();
        } else if (type == "backup_table") {
            var options = {
                url: el_grid_settings.save_url,
                beforeSubmit: showAdminAjaxRequest,
                success: function (respText, statText, xhr, $form) {
                    var resArr = $.parseJSON(respText);
                    responseAjaxDataSubmission(resArr);
                    if (resArr.success == "0") {
                        return false;
                    } else if (resArr.return_url != "") {
                        window.location.hash = resArr.return_url;
                    }
                }
            };
            $('#frmbackupsave').ajaxSubmit(options);
            return false;
        }
    },
    downloadDBBackupFile: function (fname) {
        $("#fname").val(fname);
        $("#frmbackupdwnd").submit();
    }
}
function formatBackupFileSize(cval, opt, rowObj) {
    var size_str = cval + " KB";
    return size_str;
}
function formatBackupDownloadLink(cval, opt, rowObj) {
    var down_str = '';
    if (el_grid_settings.permit_view_btn == '1') {
        down_str = "<a href='javascript://' title='" + js_lang_label.GENERIC_DOWNLOAD_FILE + "' onclick='Project.modules.backup.downloadDBBackupFile(\"" + cval + "\");return false;'>"
        down_str += "<span class='icon16 entypo-icon-download'></span>";
        down_str += "</a>";
    } else {
        down_str = '<div class="errormsg"> N/A </div>';
    }
    return down_str;
}
Project.modules.backup.init();