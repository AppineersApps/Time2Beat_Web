Project.modules.syncparse = {
    syncParseData: function () {
        var that = this, total_rows, js_col_name_arr = [], grid_comp_time = true, load_comp_time = true;
        var grid_id = el_tpl_settings.main_grid_id, pager_id = el_tpl_settings.main_pager_id, wrapper_id = el_tpl_settings.main_wrapper_id;
        for (var i in js_col_name_json) {
            js_col_name_arr.push(js_col_name_json[i]['label']);
        }
        var force_width = $("#main_content_div").width() - 30;
        getColumnsWidth(el_grid_settings.enc_location + '_cw', grid_id, js_col_model_json);

        jQuery("#list2").jqGrid({
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
            del: false,
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
                caption: (el_theme_settings.grid_icons.add) ? '' : js_lang_label.GENERIC_SYNC_PARSE,
                title: js_lang_label.GENERIC_SYNC_PARSE,
                buttonicon: "ui-icon-plus",
                buttonicon_p: (el_theme_settings.grid_icons.add) ? 'uigrid-add-btn add-icon-only' : 'uigrid-add-btn',
                onClickButton: function () {
                    that.syncParseRecords();
                },
                position: "first"
            });
            jQuery("#" + grid_id).navButtonAdd('#' + grid_id + '_toppager_left', {
                caption: (el_theme_settings.grid_icons.add) ? '' : js_lang_label.GENERIC_SYNC_PARSE,
                title: js_lang_label.GENERIC_SYNC_PARSE,
                buttonicon: "ui-icon-plus",
                buttonicon_p: (el_theme_settings.grid_icons.add) ? 'uigrid-add-btn add-icon-only' : 'uigrid-add-btn',
                onClickButton: function () {
                    that.syncParseRecords();
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
    },
    syncParseRecords: function () {
        if (confirm('Do you really want to sync parse data!!')) {
            Project.show_adaxloading_div()
            $.ajax({
                type: 'POST',
                url: el_grid_settings.sync_parse_url,
                success: function (response) {
                    Project.hide_adaxloading_div()
                    var data = $.parseJSON(response);
                    if (data.success) {
                        Project.setMessage(response.mesasge);
                        if (response.return_url != "") {
                            window.location.hash = resArr.return_url;
                        }
                    } else {
                        Project.setMessage(response.mesasge);
                    }
                }
            });
            return false;
        }
    },
    showParseSyncDetails: function (ele) {
        var file_name = $(ele).attr('file_name');
        var parse_url = el_grid_settings.sync_details_url + "|file|" + file_name + "|width|75%|height|75%";
        var href_url_arr = parse_url.split("#");
        var params_obj = getHASHToFancyParams(href_url_arr[1]);
        var req_uri = convertHASHToURL(href_url_arr[1]);
        openAjaxURLFancyBox(req_uri, params_obj);

    },
    showParseLogDetails: function (ele) {
        var log_file_name = $(ele).attr('log_file_name');
        var parse_url = el_grid_settings.log_details_url + "|file|" + log_file_name + "|width|75%|height|75%";
        var href_url_arr = parse_url.split("#");
        var params_obj = getHASHToFancyParams(href_url_arr[1]);
        var req_uri = convertHASHToURL(href_url_arr[1]);
        openAjaxURLFancyBox(req_uri, params_obj);
    }
}
