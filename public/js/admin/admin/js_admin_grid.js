$(document).ready(function () {
    $(function () {
        $(document).on("click", "[aria-save-row]", function () {
            var grid_id = $(this).attr("aria-save-row");
            var row_id = $(this).attr("aria-row-id");
            saveInlineAddRecord(grid_id, row_id);
        });
        $(document).on("click", "[aria-cancel-row]", function () {
            var grid_id = $(this).attr("aria-cancel-row");
            var row_id = $(this).attr("aria-row-id");
            cancelInlineAddRecord(grid_id, row_id);
        });
        $(document).on("click", "[aria-iadd-new]", function () {
            var grid_id = $(this).attr("aria-iadd-new");
            addNewInlineRecord(grid_id);
        });
        $(document).on("click", "[aria-isave-all]", function () {
            var grid_id = $(this).attr("aria-isave-all");
            saveAllInlineRecords(grid_id);
        });
        $(document).on("click", "[aria-icancel-all]", function () {
            var grid_id = $(this).attr("aria-icancel-all");
            cancelAllInlineRecords(grid_id);
        });
        $(document).on("click", "[aria-search-table]", function () {
            var tbl_id = $(this).attr("aria-search-table");
            if ($("#tbl_search_records_" + tbl_id).is(':visible')) {
                $("#tbl_search_records_" + tbl_id).hide();
                $(this).removeClass("minimize-search").addClass("maximize-search");
            } else {
                $("#tbl_search_records_" + tbl_id).show();
                $(this).addClass("minimize-search").removeClass("maximize-search");
            }
        });
    });
});
//related to grid listing
function initMainGridListing() {
    delete el_grid_settings.grid_subgrid_alias;
    el_grid_settings.load_post = {};
    el_grid_settings.load_page = 1;
    var grid_id = el_tpl_settings.main_grid_id, pager_id = el_tpl_settings.main_pager_id, wrapper_id = el_tpl_settings.main_wrapper_id;
    var js_prev_key = '', js_assign_btn_id = '', js_next_btn_id = '', jsave = '', saved_obj = '';
    var js_col_name_arr = [], js_sort_count = 0, jrow = 0, jcol = 0, total_rows = 0, total_pages = 1;
    var apply_hash_filter = false, js_before_req = true, grid_comp_time = true, load_comp_time = true, data_scroll_pos = true;
    var show_paging_var = true, show_toolbar_label = false, show_listview_label = false, saved_search_label = false;
    if (window.location.hash) {
        var req_hash_var_arr = getHashParams(window.location.hash, el_tpl_settings.framework_vars);
    } else {
        var req_hash_var_arr = getQueryParams(window.location.search, el_tpl_settings.framework_vars);
    }
    var load_save_search = setSavedSearchSettings(grid_id, el_grid_settings.enc_location, el_grid_settings['search_list'], el_grid_settings['search_slug']);
    if (el_theme_settings.grid_filteropt) {
        if (el_theme_settings.grid_filteropt != "none" && Object.keys(req_hash_var_arr).length > 0) {
            apply_hash_filter = true;
        }
    } else if (Object.keys(req_hash_var_arr).length > 0) {
        apply_hash_filter = true;
    }
    if (!el_tpl_settings.page_animation) {
        load_comp_time = false;
    }
    if ('grid_toolbar_search_icon' in el_theme_settings) {
        if (!el_theme_settings.grid_toolbar_search_icon) {
            show_toolbar_label = true;
        }
    }
    if ('grid_view_listing_icon' in el_theme_settings) {
        if (!el_theme_settings.grid_view_listing_icon) {
            show_listview_label = true;
        }
    }
    if ('grid_saved_search_icon' in el_theme_settings) {
        if (!el_theme_settings.grid_saved_search_icon) {
            saved_search_label = true;
        }
    }

    var sub_grid_row = (el_grid_settings.subgrid == 'Yes' || el_grid_settings.colgrid == 'Yes') ? true : false;
    var row_numbers = (!sub_grid_row && el_grid_settings.inline_add == "Yes") ? true : false;
    var pager_active = (el_grid_settings.hide_paging_btn == "Yes") ? false : true;

    var add_permit = (el_grid_settings.hide_add_btn == '1' && el_grid_settings.permit_add_btn == "1") ? true : false;
    var del_permit = (el_grid_settings.hide_del_btn == '1' && el_grid_settings.permit_del_btn == '1') ? true : false;
    var status_permit = (el_grid_settings.hide_status_btn == '1' && el_grid_settings.permit_edit_btn == '1') ? true : false;
    var export_permit = (el_grid_settings.hide_export_btn == '1' && el_grid_settings.permit_expo_btn == '1' && !el_general_settings.mobile_platform) ? true : false;
    var print_permit = (el_grid_settings.print_list == 'Yes' && el_grid_settings.permit_print_btn == '1' && !el_general_settings.mobile_platform) ? true : false;

    var columns_permit = (el_grid_settings.hide_columns_btn == 'Yes' || el_general_settings.mobile_platform) ? false : true;
    var adv_search_permit = (el_grid_settings.hide_advance_search == 'Yes') ? false : true;
    var refresh_permit = (el_grid_settings.hide_refresh_btn == 'Yes') ? false : true;
    var inline_add_permit = (el_grid_settings.inline_add == "Yes" && el_grid_settings.permit_add_btn == "1") ? true : false;
    var search_tool_permit = (el_grid_settings.hide_search_tool == "Yes") ? false : true;
    var saved_search_permit = (el_tpl_settings.grid_saved_search_enable == "1") ? true : false;
    if ("show_saved_search" in el_grid_settings) {
        saved_search_permit = (el_grid_settings.show_saved_search == "Yes") ? true : false;
    }

    var viewtemplate = '#layout_view_' + grid_id; //custom
    var gridtemplate = '#layout_grid_' + grid_id; //custom
    var lazy_loading = (el_grid_settings.lazy_loading == "Yes") ? true : false;
    var global_filter = (el_grid_settings.global_filter == "Yes") ? true : false;
    var top_filter_arr = $.isArray(el_grid_settings.top_filter) ? el_grid_settings.top_filter : [];
    var action_callbacks = $.isPlainObject(el_grid_settings['callbacks']) ? el_grid_settings['callbacks'] : {};
    var list_message_arr = $.isPlainObject(el_grid_settings['message_arr']) ? el_grid_settings['message_arr'] : {};

    var grid_button_arr = ($.isArray(el_grid_settings.buttons_arr)) ? el_grid_settings.buttons_arr : [];
    var grid_button_ids = {
        "add": "add_" + grid_id,
        "del": "del_" + grid_id,
        "search": "search_" + grid_id,
        "refresh": "refresh_" + grid_id,
        "columns": "columns_" + grid_id,
        "export": "export_" + grid_id,
        "print": "print_" + grid_id,
    }

    if (el_general_settings.mobile_platform) {
        //el_grid_settings.auto_width = "No";
    }

    if (typeof executeBeforeGridInit == "function") {
        executeBeforeGridInit(el_grid_settings['module_name'], "main");
    }
    if (action_callbacks['before_grid_init'] && $.isFunction(window[action_callbacks['before_grid_init']])) {
        window[action_callbacks['before_grid_init']](el_grid_settings, js_col_model_json, js_col_name_json);
    }

    for (var i in js_col_name_json) {
        js_col_name_arr.push(js_col_name_json[i]['label']);
    }
    $(document).off("click", "input[name='export_mode']");
    $(document).on("click", "input[name='export_mode']", function () {
        if ($(this).val() == "all") {
            $("#export_columns_div").hide();
        } else {
            $("#export_columns_div").show();
        }
    });
    $(document).off("click", "input[name='export_type']");
    $(document).on("click", "input[name='export_type']", function () {
        if ($(this).val() == "pdf") {
            $("#orientation_columns_div").show();
        } else {
            $("#orientation_columns_div").hide();
        }
    });
    setTimeout(function () {
        initLeftPanelSearch(grid_id);
        initLeftPanelAutocomplete(grid_id);
    }, 200);

    if (!apply_hash_filter) {
        getAdminPreferenceLocal("before", grid_id);
    } else {
        if (!getToolbarHashFilters()) {
            getAdminPreferenceLocal("before", grid_id);
        }
    }

    if (!add_permit && !del_permit && !status_permit && !adv_search_permit && !columns_permit && !refresh_permit &&
            !export_permit && !print_permit && !inline_add_permit && !search_tool_permit && !saved_search_permit &&
            !global_filter && !($.isArray(top_filter_arr) && top_filter_arr.length > 0) &&
            !($(viewtemplate).length || $(gridtemplate).length) && el_tpl_settings.grid_top_menu == 'N') {
        show_paging_var = false;
    }
    if (lazy_loading) {
        data_scroll_pos = false;
    }

    var listview = findGridViewParam(window.location.hash, grid_id, el_grid_settings.listview, el_grid_settings.enc_location + '_gv');
    var show_search = getLocalStore(el_grid_settings.enc_location + '_st');
    var force_width = $("#main_content_div").width() - 30;
    var force_height = 400;
    if (isFancyBoxActive()) {
        load_comp_time = grid_comp_time = true;
        force_width = $(parent.window).width() * 75 / 100 - 100;
    }

    setHideColumnSettings(grid_id, js_col_model_json, top_filter_arr);
    getColumnsWidth(el_grid_settings.enc_location + '_cw', grid_id, js_col_model_json);
    if (show_search == 1) {
        $("#grid_data_col").removeClass("hide-search-toolbar")
    } else if (show_search == 0) {
        $("#grid_data_col").addClass("hide-search-toolbar")
    }
    initAutoRefreshGrid(el_grid_settings['module_name'], el_grid_settings['auto_refresh']);

    jQuery("#" + grid_id).jqGrid({
        postData: el_grid_settings.load_post.post,
        url: el_grid_settings.listing_url,
        editurl: el_grid_settings.edit_page_url,
        mtype: 'POST',
        datatype: "json",
        colNames: js_col_name_arr,
        colModel: js_col_model_json,
        page: el_grid_settings.load_page,
        pgbuttons: pager_active,
        pginput: pager_active,
        pgnumbers: (el_theme_settings.grid_pgnumbers) ? true : false, //custom
        pgnumlimit: (el_general_settings.mobile_platform) ? 2 : parseInt(el_theme_settings.grid_pgnumlimit), //custom
        pagingpos: el_theme_settings.grid_pagingpos, //custom
        rowNum: (el_grid_settings.hide_paging_btn == "Yes") ? 1000000 : parseInt(el_tpl_settings.grid_rec_limit),
        rowList: (pager_active) ? pager_row_list : [],
        sortname: el_grid_settings.default_sort,
        sortorder: el_grid_settings.sort_order,
        altRows: true,
        altclass: 'evenRow',
        multiselectWidth: 30,
        multiselect: (el_grid_settings.hide_multi_select == "Yes") ? false : true,
        multiboxonly: true,
        hiderecords: el_grid_settings.admin_rec_arr,
        viewrecords: true,
        scroll: lazy_loading,
        norecmsg: js_lang_label.GENERIC_GRID_NO_RECORDS_FOUND,
        caption: false,
        hidegrid: false,
        listview: listview, //custom 
        showListViewLabel: show_listview_label, //custom 
        viewtemplate: '#layout_view_' + grid_id, //custom
        gridtemplate: '#layout_grid_' + grid_id, //custom
        viewCallback: function (id, type) {
            //custom
            reloadListGrid(grid_id, null, 2, el_grid_settings);
            setLocalStore(el_grid_settings.enc_location + '_gv', type);
        },
        listtags: ['{%', '%}'], //custom
        inlineadd: (el_grid_settings.inline_add == "Yes") ? true : false, //custom
        inlinerecpos: (el_grid_settings.rec_position == "Bottom") ? true : false, //custom
        isSubMod: 0, //custom
        curModule: el_grid_settings.add_page_url, //custom
        parModule: el_grid_settings.par_module, //custom
        parData: el_grid_settings.par_data, //custom
        parField: el_grid_settings.par_field, //custom
        parType: el_grid_settings.par_type, //custom
        extraHash: el_grid_settings.extra_hstr, //custom
        ratingAllow: (el_grid_settings.rating_allow == "Yes") ? true : false, //custom
        preSearch: el_grid_settings.load_post.search, //custom
        pager: (el_tpl_settings.grid_bot_menu == 'Y') ? pager_id : "",
        toppager: (el_tpl_settings.grid_top_menu == 'Y') ? true : false,
        toppaging: (el_tpl_settings.grid_top_menu == 'Y') ? true : false, //custom
        showpaging: show_paging_var, //custom
        cellurl: el_grid_settings.edit_page_url,
        cellsubmit: 'remote',
        sortable: {
            update: function (permutation) {
                setColumnsPosition(el_grid_settings.enc_location + '_cp', permutation, grid_id, js_col_model_json);
            }
        },
        searchGrid: {
            multipleSearch: true,
            savedSearch: (saved_search_permit) ? true : false,
            savedSearchLabel: (saved_search_label) ? true : false,
            savedSearchForm: function (id) {
                triggerSavedSearchForm(id, el_grid_settings.enc_location);
            },
            savedSearchList: function (id) {
                triggerSavedSearchList(id, el_grid_settings.enc_location, el_grid_settings);
            },
            searchToolbar: (search_tool_permit) ? true : false,
            showToolbarLabel: show_toolbar_label, //custom
            globalFilter: (global_filter) ? true : false,
            topFilters: top_filter_arr,
            topDataInit: triggerTopFilterEvent
        },
        afterSearchToggle: function (id) {
            //custom
            var type = 1;
            if ($("#hbox_" + id + "_jqgrid").find(".ui-search-toolbar").is(":hidden")) {
                $("#listsearch_" + id + "_top").removeClass("active");
                type = 0;
            }
            setLocalStore(el_grid_settings.enc_location + '_st', type);
            resizeGridWidth();
        },
        forceApply: true,
        forceWidth: force_width,
        width: force_width,
        height: force_height,
        autowidth: (el_grid_settings.auto_width == "No") ? false : true,
        _autowidth: (el_grid_settings.auto_width == "No") ? false : true,
        shrinkToFit: (el_grid_settings.auto_width == "No") ? false : true,
        fixed: true,
        //rownumbers: row_numbers,
        multiSort: (el_tpl_settings.grid_multiple_sorting) ? true : false,
        subGrid: sub_grid_row,
        subGridWidth: 18,
        subGridRowExpanded: function (subgrid_id, row_id) {
            var subgrid_table_id, add_params = '', t_did = "";
            subgrid_table_id = subgrid_id + "_sub";
            if (el_grid_settings.subgrid == "Yes" && !el_grid_settings.grid_subgrid_alias) {
                add_params = "&SGType=main";
            } else {
                if (el_grid_settings.grid_subgrid_alias) {
                    add_params = "&SGType=each&SGAlias=" + el_grid_settings.grid_subgrid_alias;
                } else {
                    for (var i in js_col_model_json) {
                        if (js_col_model_json[i]['expandrow']) {
                            el_grid_settings.grid_subgrid_alias = js_col_model_json[i]['index'];
                            break;
                        }
                    }
                    add_params = "&SGType=each&SGAlias=" + el_grid_settings.grid_subgrid_alias;
                }
            }
            delete el_grid_settings.grid_subgrid_alias;
            initGirdLoadingOverlay(grid_id);
            $("#" + subgrid_id).html("<div id='" + subgrid_table_id + "' class='scroll subgird-block'></div>");
            $("#" + subgrid_table_id).html('<div class="subgrid-loader"><i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i></div>');
            $.ajax({
                url: el_grid_settings.subgrid_listing_url + '&SGRender=sub' + add_params,
                type: 'POST',
                data: {
                    "SGID": row_id,
                    "SGridID": subgrid_id
                },
                success: function (data) {
                    $("#" + subgrid_table_id).addClass("subgrid_view_div_display");
                    $("#" + subgrid_table_id).html(data);
                    hideGirdLoadingOverlay(grid_id);
                    initializeSubgridEvents($("#" + subgrid_table_id));
                }
            });
        },
        subGridRowColapsed: function (subgrid_id, row_id) {
            // this function is called before removing the data
            var subgrid_table_id = subgrid_id + "_sub";
            jQuery("#" + subgrid_table_id).remove();
            resizeGridWidth();
        },
        grouping: (el_grid_settings.grouping == 'Yes') ? true : false,
        groupingView: {
            groupField: ($.isArray(el_grid_settings.group_attr['field'])) ? el_grid_settings.group_attr['field'] : [],
            groupOrder: ($.isArray(el_grid_settings.group_attr['order'])) ? el_grid_settings.group_attr['order'] : [],
            groupText: ($.isArray(el_grid_settings.group_attr['text'])) ? el_grid_settings.group_attr['text'] : [],
            groupColumnShow: ($.isArray(el_grid_settings.group_attr['column'])) ? el_grid_settings.group_attr['column'] : [],
            groupSummary: ($.isArray(el_grid_settings.group_attr['summary'])) ? el_grid_settings.group_attr['summary'] : [],
            showSummaryOnHide: ($.isArray(el_grid_settings.group_attr['summary'])) ? el_grid_settings.group_attr['summary'] : [],
            groupCollapse: false,
            groupDataSorted: true
        },
        footerrow: (el_grid_settings.footer_row == 'Yes') ? true : false,
        userDataOnFooter: true,
        beforeRequest: function () {
            $(".horizon-data-scroll").scrollLeft(0);
            if (js_before_req) {
                js_before_req = false;
                if (!apply_hash_filter) {
                    getAdminPreferenceLocal("after", grid_id);
                } else {
                    getHashFilterConditions(el_tpl_settings.main_grid_id);
                }
                getColumnsPosition(el_grid_settings.enc_location + '_cp', grid_id);
                getColumnsChoosen(el_grid_settings.enc_location + '_cs', grid_id);
                //activateGridSortColumns(grid_id);
            } else {
                if (el_theme_settings.grid_filteropt) {
                    if (el_theme_settings.grid_filteropt == "always") {
                        getHashFilterConditions(el_tpl_settings.main_grid_id);
                    }
                } else {
                    getHashFilterConditions(el_tpl_settings.main_grid_id);
                }
            }
        },
        beforeProcessing: function (data) {
            total_rows = 0;
            total_pages = 1;
            delete el_general_settings.grid_main_link_model;
            if (data && data.total) {
                total_pages = data.total;
            }
            if (data && data.records) {
                total_rows = data.records;
            }
            if (data && data.links) {
                el_general_settings.grid_main_link_model = data.links;
            }
        },
        loadError: function (xhr, status, error) {
            hideGirdLoadingOverlay(grid_id);
        },
        loadComplete: function (data) {
            hideGirdLoadingOverlay(grid_id);
            Project.hide_adaxloading_div();
            $("#" + grid_id + "_messages_html").remove();
            $("#selAllRows").val('false');
            // No Records Message
            noRecordsMessage(grid_id, data);
            // nowrap width adjusting
            //adjustWrappedWidth(grid_id);
            // Resizing Grid
            if (load_comp_time) {
                load_comp_time = false;
            } else {
                resizeGridWidth();
                checkColumnsWidth(el_grid_settings.enc_location + '_cw', grid_id);
            }
            // Apply Search Active
            applySearchCriteria(grid_id);
            // Add new record
            //addNewInlineRecord(grid_id);
            // Apply row colors
            applyGridRowColors(grid_id, data);
            // Rating Events
            //applyRatingEvents(grid_id);
            // fancybox image events
            initializeFancyBoxEvents();
            setTimeout(function () {
                initNiceScrollBar();
            }, 100);
            if (data_scroll_pos) {
                applyGridScrollPosition(el_grid_settings.enc_location + '_sp', grid_id);
                data_scroll_pos = false;
            }
            if (typeof executeAfterGridLoad == "function") {
                executeAfterGridLoad(el_grid_settings['module_name'], "main");
            }
            if (action_callbacks['after_data_load'] && $.isFunction(window[action_callbacks['after_data_load']])) {
                window[action_callbacks['after_data_load']](data);
            }
        },
        gridComplete: function () {
            var $this = $(this);
            $(".ui-jqgrid-sortable").mousedown(function () {
                $(this).css('cursor', 'crosshair');
            });
            $(".ui-jqgrid-sortable").mouseup(function () {
                $(this).css({
                    cursor: 'pointer'
                });
            });
            if (!$("#grid_data_col").hasClass("hide-search-toolbar") && $("#hbox_" + grid_id + "_jqgrid").find(".ui-search-toolbar").is(":visible")) {
                $("#listsearch_" + grid_id + "_top").addClass("active");
            }

            // Resizing Grid
            if (grid_comp_time) {
                grid_comp_time = false;
                loadTopFilterData(grid_id);
            } else {
                resizeGridWidth();
            }
            // hide admin data checkboxes
            hideAdminDataCheckBox(grid_id, el_grid_settings.admin_rec_arr);
            // image data tooltips
            getAdminImageTooltip(grid_id);
            if (!apply_hash_filter) {
                setAdminPreferenceLocal(grid_id);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e) {
            var $this = $(this);
            var view = $this.jqGrid('getGridParam', 'listview');
            if (view == "view" || view == "grid") {
                return;
            }
            var ac = $(e.srcElement).hasClass("add-cell") ? 1 : 0
            var ai = ($(e.srcElement).attr("aria-newrow") == "inline-add-row") ? 1 : 0;
            var bc = ($(e.srcElement).find(".inline-edit-row").length > 0) ? 1 : 0
            var cf = ($(e.srcElement).hasClass("inline-edit-row")) ? 1 : 0
            var sf = ($(e.srcElement).closest("td[role='gridcell']").hasClass('edit-cell')) ? 1 : 0
            if (ac || ai || bc || cf || sf) {
                e.stopPropagation();
            } else {
                $this.jqGrid('setGridParam', {
                    cellEdit: true
                });
                $this.jqGrid('editCell', iRow, iCol, true);
                $this.jqGrid('setGridParam', {
                    cellEdit: false
                });
            }
        },
        beforeEditCell: function (rowid, cellName, cellValue, iRow, iCol) {
            restoreBeforeEditedCell(this, jrow, jcol, jsave);
            if ($(".colpick").length) {
                $(".colpick").hide();
            }
        },
        afterEditCell: function (rowid, cellName, cellValue, iRow, iCol) {
            var cellDOM = this.rows[iRow].cells[iCol], oldKeydown;
            var $cellInput = $("#" + iRow + "_" + cellName, cellDOM);
            var events = $._data($cellInput.eq(0), "events"), cselector = $cellInput["selector"];
            var $this = $(this), date_flag = false, colorpicker_flag = false, phone_flag = false;

            if ($cellInput.hasClass("dateOnly")) {
                inlineDateTimePicker(iRow, cellName, 'date');
                var date_flag = true;
            } else if ($cellInput.hasClass("timeOnly")) {
                inlineDateTimePicker(iRow, cellName, 'time');
                var date_flag = true;
            } else if ($cellInput.hasClass("dateTime")) {
                inlineDateTimePicker(iRow, cellName, 'dateTime');
                var date_flag = true;
            } else if ($cellInput.hasClass("colorPicker")) {
                inlineColorPicker(iRow, cellName, 'colorPicker');
                var colorpicker_flag = true;
            } else if ($cellInput.hasClass("phoneNumber")) {
                var phone_flag = true;
            } else if ($cellInput.hasClass("inline-textarea-edit")) {
                var txt = $($cellInput).val();
                txt = txt.replace(/<br>/g, "");
                txt = txt.replace(/<BR>/g, "");
                $($cellInput).val(txt);
            }

            jrow = iRow;
            jcol = iCol;
            jsave = ($.isArray(this.p.savedRow)) ? this.p.savedRow[this.p.savedRow.length - 1].v : "";
            if ($(cellDOM).find("select[role='select']").length) {
                $cellDrop = $(cellDOM).find("select[role='select']");
                $($cellDrop).attr("aria-update-id", rowid);
                saved_obj = this.p.savedRow;
                $($cellDrop).on('change', function (e) {
                    $this.jqGrid('setGridParam', {
                        cellEdit: true
                    });
                    $this.jqGrid('setGridParam', {
                        savedRow: saved_obj
                    });
                    $this.jqGrid('saveCell', iRow, iCol);
                    $this.jqGrid('restoreCell', iRow, iCol, true);
                    $(cellDOM).removeClass("ui-state-highlight");
                    jrow = 0, jcol = 0, jsave = '';
                    saved_obj = $this.jqGrid('getGridParam', 'savedRow');
                });
                var autoChznInterval = setInterval(function () {
                    if ($(cselector).hasClass("chosen-select") && $(cselector + "_chosen").length) {
                        $(cselector + "_chosen").on('keydown', function (e) {
                            if (e.keyCode == 27) {
                                if ($(cselector + "_chosen").find(".chosen-drop").css("left") == "-9999px") {
                                    $this.jqGrid('setGridParam', {
                                        cellEdit: true
                                    });
                                    $this.jqGrid('setGridParam', {
                                        savedRow: saved_obj
                                    });
                                    $this.jqGrid('restoreCell', iRow, iCol, true);
                                    $(cellDOM).removeClass("ui-state-highlight");
                                    jrow = 0, jcol = 0, jsave = '';
                                }
                            } else if (e.keyCode == 9) {
                                $this.jqGrid('setGridParam', {
                                    cellEdit: true
                                });
                                $this.jqGrid('setGridParam', {
                                    savedRow: saved_obj
                                });
                                if (e.shiftKey) {
                                    $this.jqGrid("prevCell", iRow, iCol);
                                } //Shift TAb
                                else {
                                    $this.jqGrid("nextCell", iRow, iCol);
                                } //Tab
                            }
                        });
                        clearInterval(autoChznInterval);
                    }
                }, 250);
            } else {
                applyInputTextCase($(cellDOM));
                saved_obj = this.p.savedRow;
                setTimeout(function () {
                    if (events && events.keydown && events.keydown.length) {
                        $this.jqGrid('setGridParam', {
                            savedRow: saved_obj
                        });
                        oldKeydown = events.keydown[0].handler;
                        $cellInput.unbind('keydown', oldKeydown);
                        $cellInput.bind('keydown', function (e) {
                            $this.jqGrid('setGridParam', {
                                cellEdit: true
                            });
                            $this.jqGrid('setGridParam', {
                                savedRow: saved_obj
                            });
                            if ($cellInput.hasClass("inline-textarea-edit")) {
                                if (e.keyCode === 13) {
                                    if (e.shiftKey) {
                                        e.stopPropagation();
                                    } else {
                                        $this.jqGrid('saveCell', iRow, iCol);
                                        $this.jqGrid('restoreCell', iRow, iCol, true);
                                        $(cellDOM).removeClass("ui-state-highlight");
                                        jrow = 0, jcol = 0, jsave = '';
                                    }
                                } else {
                                    oldKeydown.call(this, e);
                                }
                            } else if ($cellInput.hasClass("colorPicker")) {
                                if (e.keyCode === 9 || e.keyCode === 13 || e.keyCode === 27) {
                                    if ($(".colpick").length) {
                                        $(".colpick").hide();
                                    }
                                }
                                oldKeydown.call(this, e);
                            } else {
                                oldKeydown.call(this, e);
                            }
                            $this.jqGrid('setGridParam', {
                                cellEdit: false
                            });
                        }).bind('focusout', function (e) {
                            $this.jqGrid('setGridParam', {
                                savedRow: saved_obj
                            });
                            if (date_flag) {
                                if ($(".ui-datepicker").is(":hidden")) {
                                    $this.jqGrid('setGridParam', {
                                        cellEdit: true
                                    });
                                    //$this.jqGrid('saveCell', iRow, iCol);
                                    $this.jqGrid('restoreCell', iRow, iCol, true);
                                    $(cellDOM).removeClass("ui-state-highlight");
                                    jrow = 0, jcol = 0, jsave = '';
                                }
                            } else if (colorpicker_flag) {
                                if ($(".colpick" + "#" + $cellInput.attr("colorpickerid")).is(":hidden")) {
                                    $this.jqGrid('setGridParam', {
                                        cellEdit: true
                                    });
                                    //$this.jqGrid('saveCell', iRow, iCol);
                                    $this.jqGrid('restoreCell', iRow, iCol, true);
                                    $(cellDOM).removeClass("ui-state-highlight");
                                    jrow = 0, jcol = 0, jsave = '';
                                }
                            } else {
                                $this.jqGrid('setGridParam', {
                                    cellEdit: true
                                });
                                if (phone_flag == true) {
                                    $this.jqGrid('saveCell', iRow, iCol);
                                }
                                $this.jqGrid('restoreCell', iRow, iCol, true);
                                $(cellDOM).removeClass("ui-state-highlight");
                                jrow = 0, jcol = 0, jsave = '';
                            }
                        });
                    }
                }, 100);
            }
        },
        beforeSubmitCell: function (rowid, cellName, cellValue, iRow, iCol) {
            if (action_callbacks['before_rec_edit'] && $.isFunction(window[action_callbacks['before_rec_edit']])) {
                return window[action_callbacks['before_rec_edit']](rowid, cellName, cellValue, iRow, iCol);
            }
        },
        afterSubmitCell: function (response, rowid, cellname, value, iRow, iCol) {
            var $c_flag, $c_msg;
            if (response.responseText != 1) {
                var res = parseJSONString(response.responseText);
                var columnNames = $("#" + grid_id).jqGrid('getGridParam', 'colNames');
                $c_flag = true;
                $c_msg = res.message;
                if (res.success == 'false') {
                    $c_flag = false;
                    $c_msg += " : " + columnNames[iCol];
                } else if (res.success == '2') {
                    reloadListGrid(grid_id);
                } else if (res.success == '3' || res.success == '4') {
                    if (isRedirectEqualHash(res.red_hash)) {
                        window.location.hash = res.red_hash;
                        window.location.reload();
                    } else {
                        window.location.hash = res.red_hash;
                    }
                } else if (res.success == '5') {
                    window.location.href = res.red_hash;
                }
                gridReportMessage($c_flag, $c_msg);
            } else {
                $c_flag = true;
            }
            if (action_callbacks['after_rec_edit'] && $.isFunction(window[action_callbacks['after_rec_edit']])) {
                return window[action_callbacks['after_rec_edit']](response, rowid, cellname, value, iRow, iCol);
            }
            return [$c_flag, res.message];
        },
        beforeSaveCell: function (rowid, cellname, value, iRow, iCol) {

        },
        afterSaveCell: function (rowid, cellname, value, iRow, iCol) {

        },
        onSortCol: function (index, iCol, sortorder) {
            $("#" + grid_id).setGridParam({defaultsort: "No"});
            setGridViewSortLayout(index, sortorder, js_col_name_json);
            activateGridSortColumns(grid_id);
        },
        resizeStop: function (newwidth, index) {
            setColumnsWidth(el_grid_settings.enc_location + '_cw', grid_id);
        },
        beforeSelectRow: function (rowid, e) {
            multiSelectHandler(rowid, e);
        }
    });

    if (search_tool_permit) {
        jQuery("#" + grid_id).jqGrid('filterToolbar', {
            stringResult: true,
            searchOnEnter: false,
            searchOperators: (el_theme_settings.grid_searchopt) ? true : false
        });
    }

    var createDelSearchRefreshBtn = function (order_arr, label_arr) {
        var del_icon, del_text = '', del_title;
        del_icon = (el_theme_settings.grid_icons_del || (label_arr['del'] && label_arr['del']['icon_only'] == "Yes")) ? true : false;
        if (!del_icon) {
            del_text = (label_arr['del'] && label_arr['del']['text']) ? label_arr['del']['text'] : js_lang_label.GENERIC_GRID_DELETE;
        }
        del_title = (label_arr['del'] && label_arr['del']['title']) ? label_arr['del']['title'] : js_lang_label.GENERIC_GRID_DELETE_SELECTED_ROW;

        var search_icon, search_text = '', search_title;
        search_icon = (el_theme_settings.grid_icons_search || (label_arr['search'] && label_arr['search']['icon_only'] == "Yes")) ? true : false;
        if (!search_icon) {
            search_text = (label_arr['search'] && label_arr['search']['text']) ? label_arr['search']['text'] : js_lang_label.GENERIC_GRID_SEARCH;
        }
        search_title = (label_arr['search'] && label_arr['search']['title']) ? label_arr['search']['title'] : js_lang_label.GENERIC_GRID_ADVANCE_SEARCH;

        var refresh_icon, refresh_text = '', refresh_title;
        refresh_icon = (el_theme_settings.grid_icons_refresh || (label_arr['refresh'] && label_arr['refresh']['icon_only'] == "Yes")) ? true : false;
        if (!refresh_icon) {
            refresh_text = (label_arr['refresh'] && label_arr['refresh']['text']) ? label_arr['refresh']['text'] : js_lang_label.GENERIC_GRID_SHOW_ALL;
        }
        refresh_title = (label_arr['refresh'] && label_arr['refresh']['title']) ? label_arr['refresh']['title'] : js_lang_label.GENERIC_GRID_SHOW_ALL_LISTING_RECORDS;

        jQuery("#" + grid_id).jqGrid('navGrid', '#' + pager_id, {
            cloneToTop: true,
            add: false,
            addicon: "ui-icon-plus",
            edit: false,
            editicon: "ui-icon-pencil",
            del: del_permit,
            delicon: "ui-icon-trash",
            delicon_p: (del_icon) ? 'uigrid-del-btn del-icon-only' : "uigrid-del-btn",
            deltext: del_text,
            deltitle: del_title,
            search: adv_search_permit,
            searchicon: "ui-icon-search",
            searchicon_p: (search_icon) ? 'uigrid-search-btn search-icon-only' : "uigrid-search-btn",
            searchtext: search_text,
            searchtitle: search_title,
            refresh: refresh_permit,
            refreshicon: "ui-icon-refresh",
            refreshicon_p: (refresh_icon) ? 'uigrid-refresh-btn refresh-icon-only' : "uigrid-refresh-btn",
            refreshtext: refresh_text,
            refreshtitle: refresh_title,
            alertbutton: js_lang_label.GENERIC_GRID_OK,
            alerttext: js_lang_label.GENERIC_GRID_PLEASE_SELECT_ANY_RECORD,
            alertmodal: {},
            beforeRefresh: function () {
                refreshLeftSearchPanel(grid_id);
                $("#" + grid_id).setGridParam({sortname: el_grid_settings.default_sort, sortorder: el_grid_settings.sort_order, defaultsort: "Yes"});
                activateGridSortColumns(grid_id);
            },
            afterRefresh: function () {
                $("#hbox_" + grid_id + "_jqgrid").find(".search-chosen-select").find("option").removeAttr("selected");
                $("#hbox_" + grid_id + "_jqgrid").find(".search-chosen-select").trigger("chosen:updated");
                if ($("#hbox_" + grid_id + "_jqgrid").find(".search-token-autocomplete").length) {
                    $("#hbox_" + grid_id + "_jqgrid").find(".search-token-autocomplete").each(function () {
                        $(this).tokenInput("clear");
                    });
                }
                $("#hbox_" + grid_id + "_jqgrid").find(".top-filter-chosen").find("option").removeAttr("selected");
                $("#hbox_" + grid_id + "_jqgrid").find(".top-filter-chosen").trigger("chosen:updated");
                if ($("#hbox_" + grid_id + "_jqgrid").find(".top-filter-autocomplete").length) {
                    $("#hbox_" + grid_id + "_jqgrid").find(".top-filter-autocomplete").each(function () {
                        $(this).tokenInput("clear");
                    });
                }
                var sort_name = $("#" + grid_id).getGridParam("sortname");
                var sort_order = $("#" + grid_id).getGridParam("sortorder");
                setGridViewSortLayout(sort_name, sort_order, js_col_name_json);
            }
        }, {
            // edit options
        }, {
            // add options
        }, {
            // delete options
            id: grid_button_ids.del,
            width: 320,
            caption: js_lang_label.GENERIC_GRID_DELETE,
            msg: js_lang_label.GENERIC_GRID_ARE_YOU_SURE_WANT_TO_DELETE_SELECTED_RECORDS,
            bSubmit: js_lang_label.GENERIC_GRID_DELETE,
            bCancel: js_lang_label.GENERIC_GRID_CANCEL,
            modal: true,
            closeOnEscape: true,
            serializeDelData: function (postdata) {
                var selAllRows = jQuery('#selAllRows').val();
                // append postdata with any information 
                return {
                    "id": postdata.id,
                    "oper": postdata.oper,
                    "AllRowSelected": selAllRows,
                    "filters": $('#' + grid_id).getGridParam('postData').filters
                }
            },
            beforeSubmit: function (postdata) {
                if (action_callbacks['before_rec_delete'] && $.isFunction(window[action_callbacks['before_rec_delete']])) {
                    return window[action_callbacks['before_rec_delete']](postdata);
                } else {
                    return [true, ""];
                }
            },
            afterSubmit: function (response, postdata) {
                var resdata = {}, $del_flag, $jq_errmsg;
                resdata = parseJSONString(response.responseText);
                if (resdata.success == 'true') {
                    $jq_errmsg = js_lang_label.GENERIC_GRID_RECORDS_DELETED_SUCCESSFULLY;
                    if (resdata.message != "") {
                        $jq_errmsg = resdata.message;
                    }
                    $del_flag = true;
                    refreshLeftSearchPanel(grid_id);
                } else {
                    $jq_errmsg = js_lang_label.GENERIC_GRID_ERROR_IN_DELETION;
                    if (resdata.message != "") {
                        $jq_errmsg = resdata.message;
                    }
                    $del_flag = false;
                }
                gridReportMessage($del_flag, $jq_errmsg);
                if (action_callbacks['after_rec_delete'] && $.isFunction(window[action_callbacks['after_rec_delete']])) {
                    window[action_callbacks['after_rec_delete']](response, postdata);
                }
                return [true, $jq_errmsg];
            }
        }, {
            // search options
            id: grid_button_ids.search,
            multipleSearch: true,
            multipleGroup: (el_grid_settings.group_search == "1") ? true : false,
            showQuery: false,
            Find: js_lang_label.GENERIC_GRID_FIND,
            Reset: js_lang_label.GENERIC_GRID_RESET,
            width: 700,
            height: 275,
            closeOnEscape: true,
            modal: true,
            closeAfterSearch: true
        }, {
            // view options
        }, {
            // refresh options
            id: grid_button_ids.refresh
        }, {
            // order options array
            order: order_arr
        });
    }
    var createAddButton = function (afterId, label_arr) {
        var add_icon, add_text = '', add_title;
        add_icon = (el_theme_settings.grid_icons_add || (label_arr['icon_only'] == "Yes")) ? true : false;
        if (!add_icon) {
            add_text = (label_arr['text']) ? label_arr['text'] : js_lang_label.GENERIC_GRID_ADD_NEW;
        }
        add_title = (label_arr['title']) ? label_arr['title'] : js_lang_label.GENERIC_GRID_ADD_NEW;

        js_assign_btn_id = grid_button_ids.add;
        jQuery("#" + grid_id).navButtonAdd('#' + pager_id, {
            caption: add_text,
            title: add_title,
            buttonicon: "ui-icon-plus",
            buttonicon_p: (add_icon) ? 'uigrid-add-btn add-icon-only' : 'uigrid-add-btn',
            onClickButton: function () {
                adminAddNewRecord(el_grid_settings.add_page_url, el_grid_settings.extra_hstr, el_grid_settings.popup_add_form, grid_id, el_grid_settings.popup_add_size);
            },
            id: js_assign_btn_id,
            afterButtonId: afterId,
            position: "first"
        });
        jQuery("#" + grid_id).navButtonAdd("#" + grid_id + "_toppager_left", {
            caption: add_text,
            title: add_title,
            buttonicon: "ui-icon-plus",
            buttonicon_p: (add_icon) ? 'uigrid-add-btn add-icon-only' : 'uigrid-add-btn',
            onClickButton: function () {
                adminAddNewRecord(el_grid_settings.add_page_url, el_grid_settings.extra_hstr, el_grid_settings.popup_add_form, grid_id, el_grid_settings.popup_add_size);
            },
            id: js_assign_btn_id + "_top",
            afterButtonId: (afterId) ? afterId + "_top" : "",
            position: "first"
        });
    }
    var createStatusButton = function (afterId) {
        var jstatus_btn, jstatus_lbl, status_icon;
        for (var i in el_grid_settings.status_arr) {
            if (!el_grid_settings.status_arr[i]) {
                continue;
            }
            jstatus_btn = el_grid_settings.status_arr[i];
            jstatus_lbl = eval(el_grid_settings.status_lang_arr[i]);
            status_icon = (jstatus_btn || "").replace(/(\s)/g, "").toLowerCase();

            js_assign_btn_id = "status_" + i + "_" + grid_id;
            js_next_btn_id = (i == 0) ? afterId : "status_" + js_prev_key + "_" + grid_id;

            jQuery("#" + grid_id).navButtonAdd('#' + pager_id, {
                caption: jstatus_lbl,
                title: jstatus_lbl,
                lang: jstatus_btn,
                buttonicon: 'ui-icon-newwin',
                buttonicon_p: "uigrid-status-common uigrid-status-btn-" + status_icon,
                onClickButton: function (e, p) {
                    var fids = filterGridSelectedIDs(this);
                    adminStatusChange(grid_id, p.lang, fids, el_grid_settings.edit_page_url, p.title, action_callbacks, list_message_arr);
                },
                id: js_assign_btn_id,
                afterButtonId: js_next_btn_id,
                position: "first"
            });
            jQuery("#" + grid_id).navButtonAdd("#" + grid_id + "_toppager_left", {
                caption: jstatus_lbl,
                title: jstatus_lbl,
                lang: jstatus_btn,
                buttonicon: 'ui-icon-newwin',
                buttonicon_p: "uigrid-status-common uigrid-status-btn-" + status_icon,
                onClickButton: function (e, p) {
                    var fids = filterGridSelectedIDs(this);
                    adminStatusChange(grid_id, p.lang, fids, el_grid_settings.edit_page_url, p.title, action_callbacks, list_message_arr);
                },
                id: js_assign_btn_id + "_top",
                afterButtonId: (js_next_btn_id) ? js_next_btn_id + "_top" : "",
                position: "first"
            });
            js_prev_key = i;
        }
    }
    var createColumnsButton = function (afterId, label_arr) {
        var col_icon, col_text = '', col_title;
        col_icon = (el_theme_settings.grid_icons_columns || (label_arr['icon_only'] == "Yes")) ? true : false;
        if (!col_icon) {
            col_text = (label_arr['text']) ? label_arr['text'] : js_lang_label.GENERIC_GRID_COLUMNS;
        }
        col_title = (label_arr['title']) ? label_arr['title'] : js_lang_label.GENERIC_GRID_HIDESHOW_COLUMNS;

        js_assign_btn_id = grid_button_ids.columns;
        jQuery("#" + grid_id).navButtonAdd('#' + pager_id, {
            caption: col_text,
            title: col_title,
            buttonicon: "ui-icon-columns",
            buttonicon_p: (col_icon) ? 'uigrid-col-btn col-icon-only' : 'uigrid-col-btn',
            onClickButton: function () {
                jQuery("#" + grid_id).jqGrid('columnChooser', {
                    'classname': 'grid-columns-picker',
                    'dialog_opts': {
                        modal: true,
                        minWidth: 460
                    },
                    'msel_opts': {
                        'autoOpen': true,
                        'checkAllText': (js_lang_label.GENERIC_CHECK_ALL ? js_lang_label.GENERIC_CHECK_ALL : "Check all"),
                        'uncheckAllText': (js_lang_label.GENERIC_UNCHECK_ALL ? js_lang_label.GENERIC_UNCHECK_ALL : "Uncheck all"),
                        'noneSelectedText': (js_lang_label.GENERIC_GRID_SELECT_COLUMNS ? js_lang_label.GENERIC_GRID_SELECT_COLUMNS : "Select Columns"),
                        'selectedText': "# " + (js_lang_label.GENERIC_SELECTED ? js_lang_label.GENERIC_SELECTED : "Selected"),
                        'filterPlaceholder': (js_lang_label.GENERIC_GRID_SEARCH_HERE ? js_lang_label.GENERIC_GRID_SEARCH_HERE : "Search here"),
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
                        if (perm) {
                            setColumnsChoosen(el_grid_settings.enc_location + '_cs', grid_id);
                            reloadListGrid(grid_id);
                        }
                    }
                });
            },
            position: "last",
            id: js_assign_btn_id,
            afterButtonId: afterId
        });
        jQuery("#" + grid_id).navButtonAdd("#" + grid_id + "_toppager_left", {
            caption: col_text,
            title: col_title,
            buttonicon: "ui-icon-columns",
            buttonicon_p: (col_icon) ? 'uigrid-col-btn col-icon-only' : 'uigrid-col-btn',
            onClickButton: function () {
                jQuery("#" + grid_id).jqGrid('columnChooser', {
                    'classname': 'grid-columns-picker',
                    'dialog_opts': {
                        modal: true,
                        minWidth: 460
                    },
                    'msel_opts': {
                        'autoOpen': true,
                        'checkAllText': (js_lang_label.GENERIC_CHECK_ALL ? js_lang_label.GENERIC_CHECK_ALL : "Check all"),
                        'uncheckAllText': (js_lang_label.GENERIC_UNCHECK_ALL ? js_lang_label.GENERIC_UNCHECK_ALL : "Uncheck all"),
                        'noneSelectedText': (js_lang_label.GENERIC_GRID_SELECT_COLUMNS ? js_lang_label.GENERIC_GRID_SELECT_COLUMNS : "Select Columns"),
                        'selectedText': "# " + (js_lang_label.GENERIC_SELECTED ? js_lang_label.GENERIC_SELECTED : "Selected"),
                        'filterPlaceholder': (js_lang_label.GENERIC_GRID_SEARCH_HERE ? js_lang_label.GENERIC_GRID_SEARCH_HERE : "Search here"),
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
                        if (perm) {
                            setColumnsChoosen(el_grid_settings.enc_location + '_cs', grid_id);
                            reloadListGrid(grid_id);
                        }
                    }
                });
            },
            position: "last",
            id: js_assign_btn_id + "_top",
            afterButtonId: (afterId) ? afterId + "_top" : ""
        });
    }
    var createExportButton = function (afterId, label_arr) {
        var exp_icon, exp_text = '', exp_title;
        exp_icon = (el_theme_settings.grid_icons_export || (label_arr['icon_only'] == "Yes")) ? true : false;
        if (!exp_icon) {
            exp_text = (label_arr['text']) ? label_arr['text'] : js_lang_label.GENERIC_GRID_EXPORT;
        }
        exp_title = (label_arr['title']) ? label_arr['title'] : js_lang_label.GENERIC_GRID_EXPORT;

        js_assign_btn_id = grid_button_ids.export;
        var export_html = "<div class='data-export-main'>";
        export_html += "<div class='export-row'>\n\
                            <div class='export-left'><strong>" + js_lang_label.GENERIC_GRID_EXPORT_MODE + ":</strong></div>\n\
                            <div class='export-right'>\n\
                                <input type='radio' class='regular-radio export-mode-selected' name='export_mode' value='selected' id='export_mode_selected' checked=true><label for='export_mode_selected'>&nbsp;</label>\n\
                                <label for='export_mode_selected' class='export-label-modes'>" + js_lang_label.GENERIC_GRID_SELECTED + "</label>&nbsp;&nbsp;\n\
                                <input type='radio' class='regular-radio export-mode-all' name='export_mode' value='all' id='export_mode_all'><label for='export_mode_all'>&nbsp;</label>\n\
                                <label for='export_mode_all' class='export-label-modes'>" + js_lang_label.GENERIC_GRID_ALL + "</label>\n\
                            </div>\n\
                        </div>";
        export_html += "<div class='export-row' id='export_columns_div'>\n\
                            <div class='export-left'><strong>" + js_lang_label.GENERIC_GRID_SELECT_COLUMNS + ":</strong></div>\n\
                            <div class='export-right'>" + getColumnsDropDown(grid_id, "export_columns_list", "export_columns_list", '', 'multiple') + " </div>\n\
                        </div>";
        export_html += "<div class='export-row'>\n\
                            <div class='export-left'><strong>" + js_lang_label.GENERIC_GRID_EXPORT_TO + ":</strong></div>\n\
                            <div class='export-right'>\n\
                                <input type='radio' class='regular-radio export-type-csv' name='export_type' value='csv' id='export_type_csv' checked=true><label for='export_type_csv'>&nbsp;</label>\n\
                                <label for='export_type_csv' class='export-label-modes' title='" + js_lang_label.GENERIC_GRID_EXPORT_TO_CSV + "'><i class='fa fa-file-excel-o fa-2x icon-csv'></i></label>\n\
                                <input type='radio' class='regular-radio export-type-pdf' name='export_type' value='pdf' id='export_type_pdf'><label for='export_type_pdf'>&nbsp;</label>\n\
                                <label for='export_type_pdf' class='export-label-modes' title='" + js_lang_label.GENERIC_GRID_EXPORT_TO_PDF + "'><i class='fa fa-file-pdf-o fa-2x icon-pdf'></i></label>\n\
                            </div>\n\
                            <div class='clear'></div>\n\
                        </div>";
        export_html += "<div class='export-row' id='orientation_columns_div' style='display:none'>\n\
                            <div class='export-left'><strong>" + (js_lang_label.GENERIC_GRID_PDF_ORIENTATION ? js_lang_label.GENERIC_GRID_PDF_ORIENTATION : "PDF Orientation") + ":</strong></div>\n\
                            <div class='export-right'>\n\
                                <input type='radio' class='regular-radio export-type-pdf' name='orientation_type' value='portrait' id='orientation_type_portrait' checked=true><label for='orientation_type_portrait'>&nbsp;</label>\n\
                                <label for='orientation_type_portrait' class='export-label-modes'>" + (js_lang_label.GENERIC_GRID_PORTRAIT ? js_lang_label.GENERIC_GRID_PORTRAIT : "Portrait") + "</label>\n\
                                <input type='radio' class='regular-radio orientation-type-landscape' name='orientation_type' value='landscape' id='orientation_type_landscape'><label for='orientation_type_landscape'>&nbsp;</label>\n\
                                <label for='orientation_type_landscape' class='export-label-modes'>" + (js_lang_label.GENERIC_GRID_LANDSCAPE ? js_lang_label.GENERIC_GRID_LANDSCAPE : "Landscape") + "</label>\n\
                            </div>\n\
                            <div class='clear'></div>\n\
                        </div>";
        export_html += "<div class='clear'></div>";
        export_html += "</div>";

        var selected_btn_title = (js_lang_label.GENERIC_GRID_EXPORT_SELECTED_RECORDS) ? js_lang_label.GENERIC_GRID_EXPORT_SELECTED_RECORDS : "Export selected records";
        jQuery("#" + grid_id).navButtonAdd('#' + pager_id, {
            caption: exp_text,
            title: exp_title,
            buttonicon: 'ui-icon-export',
            buttonicon_p: (exp_icon) ? 'uigrid-export-btn export-icon-only' : 'uigrid-export-btn',
            onClickButton: function () {
                var export_elem = '<div />';
                var export_btns = [
                    {
                        text: js_lang_label.GENERIC_GRID_EXPORT_ALL + ' ' + total_rows + ' ' + js_lang_label.GENERIC_GRID_RECORDS,
                        id: "btn_all",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            exportData(grid_id, 'all', el_grid_settings.export_url);
                        }
                    },
                    {
                        text: js_lang_label.GENERIC_GRID_EXPORT_CURRENT_PAGE_RECORDS,
                        id: "btn_page",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            exportData(grid_id, 'thispage', el_grid_settings.export_url);
                        }
                    }
                ];
                if ($('#' + grid_id).jqGrid("getGridParam", "listview") != 'grid' && $.isArray($('#' + grid_id).getGridParam('selarrrow')) && $('#' + grid_id).getGridParam('selarrrow').length > 0) {
                    export_btns.push({
                        text: selected_btn_title,
                        id: "btn_selected",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            exportData(grid_id, 'selected', el_grid_settings.export_url);
                        }
                    });
                }
                $(export_elem).attr("id", "exportmod_" + grid_id).html(export_html).dialog({
                    title: exp_title,
                    //height: 195,
                    width: 600,
                    resize: true,
                    modal: true,
                    buttons: export_btns,
                    "close": function () {
                        $(this).dialog("destroy").remove();
                    }
                });
                $("#export_columns_list").multiselect({
                    'minWidth': 300,
                    'checkAllText': (js_lang_label.GENERIC_CHECK_ALL ? js_lang_label.GENERIC_CHECK_ALL : "Check all"),
                    'uncheckAllText': (js_lang_label.GENERIC_UNCHECK_ALL ? js_lang_label.GENERIC_UNCHECK_ALL : "Uncheck all"),
                    'noneSelectedText': (js_lang_label.GENERIC_GRID_SELECT_COLUMNS ? js_lang_label.GENERIC_GRID_SELECT_COLUMNS : "Select Columns"),
                    'selectedText': "# " + (js_lang_label.GENERIC_SELECTED ? js_lang_label.GENERIC_SELECTED : "Selected")
                }).multiselectfilter({placeholder: js_lang_label.GENERIC_GRID_SEARCH_HERE});
            },
            position: "last",
            id: js_assign_btn_id,
            afterButtonId: afterId
        })
        jQuery("#" + grid_id).navButtonAdd('#' + grid_id + '_toppager_left', {
            caption: exp_text,
            title: exp_title,
            buttonicon: 'ui-icon-export',
            buttonicon_p: (exp_icon) ? 'uigrid-export-btn export-icon-only' : 'uigrid-export-btn',
            onClickButton: function () {
                var export_elem = '<div />';
                var export_btns = [
                    {
                        text: js_lang_label.GENERIC_GRID_EXPORT_ALL + ' ' + total_rows + ' ' + js_lang_label.GENERIC_GRID_RECORDS,
                        id: "btn_all",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            exportData(grid_id, 'All', el_grid_settings.export_url);
                        }
                    },
                    {
                        text: js_lang_label.GENERIC_GRID_EXPORT_CURRENT_PAGE_RECORDS,
                        id: "btn_page",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            exportData(grid_id, 'thispage', el_grid_settings.export_url);
                        }
                    }
                ];
                if ($('#' + grid_id).jqGrid("getGridParam", "listview") != 'grid' && $.isArray($('#' + grid_id).getGridParam('selarrrow')) && $('#' + grid_id).getGridParam('selarrrow').length > 0) {
                    export_btns.push({
                        text: selected_btn_title,
                        id: "btn_selected",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            exportData(grid_id, 'selected', el_grid_settings.export_url);
                        }
                    });
                }
                $(export_elem).attr("id", "exportmod_" + grid_id).html(export_html).dialog({
                    title: exp_title,
                    //height: 195,
                    width: 600,
                    resize: true,
                    modal: true,
                    buttons: export_btns,
                    "close": function () {
                        $(this).dialog("destroy").remove();
                    }
                });
                $("#export_columns_list").multiselect({
                    'minWidth': 300,
                    'checkAllText': (js_lang_label.GENERIC_CHECK_ALL ? js_lang_label.GENERIC_CHECK_ALL : "Check all"),
                    'uncheckAllText': (js_lang_label.GENERIC_UNCHECK_ALL ? js_lang_label.GENERIC_UNCHECK_ALL : "Uncheck all"),
                    'noneSelectedText': (js_lang_label.GENERIC_GRID_SELECT_COLUMNS ? js_lang_label.GENERIC_GRID_SELECT_COLUMNS : "Select Columns"),
                    'selectedText': "# " + (js_lang_label.GENERIC_SELECTED ? js_lang_label.GENERIC_SELECTED : "Selected"),
                }).multiselectfilter({placeholder: js_lang_label.GENERIC_GRID_SEARCH_HERE});
            },
            position: "last",
            id: js_assign_btn_id + "_top",
            afterButtonId: (afterId) ? afterId + "_top" : ""
        });
    }
    var createPrintButton = function (afterId, label_arr) {
        var prnt_icon, prnt_text = '', prnt_title;
        prnt_icon = (el_theme_settings.grid_icons_print || (label_arr['icon_only'] == "Yes")) ? true : false;
        if (!prnt_icon) {
            prnt_text = (label_arr['text']) ? label_arr['text'] : js_lang_label.GENERIC_GRID_PRINT;
        }
        prnt_title = (label_arr['title']) ? label_arr['title'] : js_lang_label.GENERIC_GRID_PRINT;

        js_assign_btn_id = grid_button_ids.print;
        var print_html = "<div class='data-print-main'>";
        print_html += "<div class='print-row'>\n\
                            <strong>" + js_lang_label.GENERIC_PLEASE_CHOOSE_THE_BELOW_RECORDS_SELECTION_FOR_PRINTING + "</strong>\n\
                        </div>";
        print_html += "</div>";

        jQuery("#" + grid_id).navButtonAdd('#' + pager_id, {
            caption: prnt_text,
            title: prnt_title,
            buttonicon: 'ui-icon-print',
            buttonicon_p: (prnt_icon) ? 'uigrid-print-btn print-icon-only' : 'uigrid-print-btn',
            onClickButton: function () {
                var print_elem = '<div />';
                var print_btns = [
                    {
                        text: js_lang_label.GENERIC_GRID_PRINT_ALL + ' ' + total_rows + ' ' + js_lang_label.GENERIC_GRID_RECORDS,
                        id: "btn_all",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            printData(grid_id, 'all', el_grid_settings.print_url, {});
                        }
                    },
                    {
                        text: js_lang_label.GENERIC_GRID_PRINT_CURRENT_PAGE_RECORDS,
                        id: "btn_page",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            printData(grid_id, 'thispage', el_grid_settings.print_url, {});
                        }
                    }
                ];
                if ($('#' + grid_id).jqGrid("getGridParam", "listview") != 'grid' && $.isArray($('#' + grid_id).getGridParam('selarrrow')) && $('#' + grid_id).getGridParam('selarrrow').length > 0) {
                    print_btns.push({
                        text: js_lang_label.GENERIC_GRID_PRINT_SELECTED_RECORDS,
                        id: "btn_selected",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            printData(grid_id, 'selected', el_grid_settings.print_url, {});
                        }
                    });
                }
                $(print_elem).attr("id", "printmod_" + grid_id).html(print_html).dialog({
                    title: prnt_title,
                    //height: 195,
                    width: 600,
                    resize: true,
                    modal: true,
                    buttons: print_btns,
                    "close": function () {
                        $(this).dialog("destroy").remove();
                    }
                });
            },
            position: "last",
            id: js_assign_btn_id,
            afterButtonId: afterId
        })
        jQuery("#" + grid_id).navButtonAdd('#' + grid_id + '_toppager_left', {
            caption: prnt_text,
            title: prnt_title,
            buttonicon: 'ui-icon-print',
            buttonicon_p: (prnt_icon) ? 'uigrid-print-btn print-icon-only' : 'uigrid-print-btn',
            onClickButton: function () {
                var print_elem = '<div />';
                var print_btns = [
                    {
                        text: js_lang_label.GENERIC_GRID_PRINT_ALL + ' ' + total_rows + ' ' + js_lang_label.GENERIC_GRID_RECORDS,
                        id: "btn_all",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            printData(grid_id, 'all', el_grid_settings.print_url, {});
                        }
                    },
                    {
                        text: js_lang_label.GENERIC_GRID_PRINT_CURRENT_PAGE_RECORDS,
                        id: "btn_page",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            printData(grid_id, 'thispage', el_grid_settings.print_url, {});
                        }
                    }
                ];
                if ($('#' + grid_id).jqGrid("getGridParam", "listview") != 'grid' && $.isArray($('#' + grid_id).getGridParam('selarrrow')) && $('#' + grid_id).getGridParam('selarrrow').length > 0) {
                    print_btns.push({
                        text: js_lang_label.GENERIC_GRID_PRINT_SELECTED_RECORDS,
                        id: "btn_selected",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            printData(grid_id, 'selected', el_grid_settings.print_url, {});
                        }
                    });
                }
                $(print_elem).attr("id", "printmod_" + grid_id).html(print_html).dialog({
                    title: prnt_title,
                    //height: 195,
                    width: 600,
                    resize: true,
                    modal: true,
                    buttons: print_btns,
                    "close": function () {
                        $(this).dialog("destroy").remove();
                    }
                });
            },
            position: "last",
            id: js_assign_btn_id + "_top",
            afterButtonId: (afterId) ? afterId + "_top" : ""
        });
    }
    var createInlineAddSaveDelBtn = function () {
        jQuery("#" + grid_id).navButtonAdd('#' + pager_id, {
            caption: "",
            title: js_lang_label.GENERIC_GRID_ADD_NEW,
            buttonicon: 'icon16 iconic-icon-plus-alt',
            buttonicon_p: "uigrid-inlineadd-btn",
            buttonname: "addnew",
            onClickButton: function (e) {
                addNewInlineRecord(grid_id);
            },
            id: 'inlineadd_' + grid_id,
            position: "last"
        });
        jQuery("#" + grid_id).navButtonAdd("#" + grid_id + "_toppager_left", {
            caption: "",
            title: js_lang_label.GENERIC_GRID_ADD_NEW,
            buttonicon: 'icon16 iconic-icon-plus-alt',
            buttonicon_p: "uigrid-inlineadd-btn",
            buttonname: "addnew",
            onClickButton: function (e) {
                addNewInlineRecord(grid_id);
            },
            id: 'inlineadd_' + grid_id + '_top',
            position: "last"
        });

        jQuery("#" + grid_id).navButtonAdd('#' + pager_id, {
            caption: "",
            title: js_lang_label.GENERIC_GRID_SAVE_ALL,
            buttonicon: 'icon16 iconic-icon-check-alt',
            buttonicon_p: "uigrid-inlinesave-btn",
            buttonname: "saveall",
            onClickButton: function (e) {
                saveAllInlineRecords(grid_id);
            },
            id: 'saveall_' + grid_id,
            position: "last"
        });
        jQuery("#" + grid_id).navButtonAdd("#" + grid_id + "_toppager_left", {
            caption: "",
            title: js_lang_label.GENERIC_GRID_SAVE_ALL,
            buttonicon: 'icon16 iconic-icon-check-alt',
            buttonicon_p: "uigrid-inlinesave-btn",
            buttonname: "saveall",
            onClickButton: function (e) {
                saveAllInlineRecords(grid_id);
            },
            id: 'saveall_' + grid_id + '_top',
            position: "last"
        });

        jQuery("#" + grid_id).navButtonAdd('#' + pager_id, {
            caption: "",
            title: js_lang_label.GENERIC_GRID_CANCEL_ALL,
            buttonicon: 'icon16 icomoon-icon-cancel-2',
            buttonicon_p: "uigrid-cancelall-btn",
            buttonname: "cancelall",
            onClickButton: function (e) {
                cancelAllInlineRecords(grid_id);
            },
            id: 'cancelall_' + grid_id,
            position: "last"
        });
        jQuery("#" + grid_id).navButtonAdd("#" + grid_id + "_toppager_left", {
            caption: "",
            title: js_lang_label.GENERIC_GRID_CANCEL_ALL,
            buttonicon: 'icon16 icomoon-icon-cancel-2',
            buttonicon_p: "uigrid-cancelall-btn",
            buttonname: "cancelall",
            onClickButton: function (e) {
                cancelAllInlineRecords(grid_id);
            },
            id: 'cancelall_' + grid_id + '_top',
            position: "last"
        });
    }

    if (grid_button_arr.length > 0) {
        var ord_dsr_arr = [], btn_dsr_arr = {}, btn_name;
        for (var b = 0; b < grid_button_arr.length; b++) {
            btn_name = grid_button_arr[b]['name'];
            if ($.inArray(btn_name, ["del", "search", "refresh"]) != -1) {
                ord_dsr_arr.push(btn_name);
                btn_dsr_arr[btn_name] = grid_button_arr[b];
            }
            if (ord_dsr_arr.length >= 3) {
                break;
            }
        }
        createDelSearchRefreshBtn(ord_dsr_arr, btn_dsr_arr);
        for (var b = 0; b < grid_button_arr.length; b++) {
            if (grid_button_arr[b]['type'] == "custom") {
                js_assign_btn_id = createCustomGridButton(grid_button_arr[b], grid_id, pager_id, js_assign_btn_id);
            } else {
                btn_name = grid_button_arr[b]['name'];
                if (btn_name && btn_name.substring(0, 6) == "status") {
                    if (status_permit) {
                        createStatusButton(js_assign_btn_id);
                    }
                } else {
                    switch (btn_name) {
                        case "del":
                            if (del_permit) {
                                js_assign_btn_id = grid_button_ids.del;
                            }
                            break;
                        case "search":
                            if (adv_search_permit) {
                                js_assign_btn_id = grid_button_ids.search;
                            }
                            break;
                        case "refresh":
                            if (refresh_permit) {
                                js_assign_btn_id = grid_button_ids.refresh;
                            }
                            break;
                        case "add":
                            if (add_permit) {
                                createAddButton(js_assign_btn_id, grid_button_arr[b]);
                            }
                            break;
                        case "columns":
                            if (columns_permit) {
                                createColumnsButton(js_assign_btn_id, grid_button_arr[b]);
                            }
                            break;
                        case "export":
                            if (export_permit) {
                                createExportButton(js_assign_btn_id, grid_button_arr[b]);
                            }
                            break;
                        case "print":
                            if (print_permit) {
                                createPrintButton(js_assign_btn_id, grid_button_arr[b]);
                            }
                            break;
                    }
                }
            }
        }
    } else {
        createDelSearchRefreshBtn([], {});
        if (add_permit) {
            createAddButton(js_assign_btn_id, {});
        }
        if (del_permit) {
            js_assign_btn_id = grid_button_ids.del;
        }
        if (status_permit) {
            createStatusButton(js_assign_btn_id);
        }
        if (columns_permit) {
            createColumnsButton(js_assign_btn_id, {});
        }
        if (export_permit) {
            createExportButton(js_assign_btn_id, {});
        }
        if (print_permit) {
            createPrintButton(js_assign_btn_id, {});
        }
    }
    if (inline_add_permit) {
        createInlineAddSaveDelBtn();
    }

    $(".cbox").change(function () {
        setTimeout(function () {
            if ($("#cb_" + grid_id).is(':checked') && total_pages > 1) {
                if (!$("#" + grid_id + "_messages_html").length) {
                    $("#" + grid_id + "_toppager").after("<div id='" + grid_id + "_messages_html' style='text-align:center;'><a id='" + grid_id + "_messages' href='javascript:void(0);'> " + js_lang_label.GENERIC_GRID_SELECT_ALL + " " + total_rows + " " + js_lang_label.GENERIC_GRID_RECORDS + "</a></span></div>");
                }
                $("#" + grid_id + "_messages").off('click');
                $("#" + grid_id + "_messages").on('click', function () {
                    $("#selAllRows").val('true');
                    $("#" + grid_id + "_messages_html").html(js_lang_label.GENERIC_GRID_ALL + " " + total_rows + ' ' + js_lang_label.GENERIC_GRID_RECORDS_ARE_SELECTED + ' <a id="clearSelections" href="javascript:void(0);">' + js_lang_label.GENERIC_GRID_CLEAR_SELECTIONS + '</a>');
                    $("#clearSelections").unbind();
                    $("#clearSelections").click(function () {
                        $("#" + grid_id).resetSelection();
                        $("#" + grid_id + "_messages_html").remove();
                    });
                });
            } else {
                $("#" + grid_id + "_messages_html").remove();
                $("#selAllRows").val('false');
            }
        }, 100);
    });

    $(document).off("click", ".expand-subview");
    $(document).on("click", ".expand-subview", function () {
        var curr_alias = $(this).attr("aria-alias");
        var curr_row_id = $(this).attr("aria-rowid");
        if (el_grid_settings.grid_subgrid_alias && curr_alias == el_grid_settings.grid_subgrid_alias) {
            el_grid_settings.grid_subgrid_alias = curr_alias;
            $("#" + grid_id).jqGrid('toggleSubGridRow', curr_row_id);
        } else {
            el_grid_settings.grid_subgrid_alias = curr_alias;
            $("#" + grid_id).jqGrid('toggleSubGridRow', curr_row_id);
            setTimeout(function () {
                $("#" + grid_id).jqGrid('expandSubGridRow', curr_row_id);
            }, 100)
        }
    });

    var orgViewModal = $.jgrid.viewModal;
    $.extend($.jgrid, {
        viewModal: function (selector, o) {
            if (selector == '#searchmodfbox_' + o.gid || selector == "#delmod" + o.gid ||
                    selector == "#exportmod_" + o.gid || selector == "#printmod_" + o.gid ||
                    selector == '#alertmod' || selector == "#info_dialog") {
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
}
//related to local storage
function setAdminPreferenceLocal(grid_id) {
    if (!isLocalStorageAllow()) {
        return false;
    }
    var gridInfo = {}, grid = $('#' + grid_id);
    gridInfo.sortname = grid.jqGrid('getGridParam', 'sortname');
    gridInfo.sortorder = grid.jqGrid('getGridParam', 'sortorder');
    gridInfo.page = grid.jqGrid('getGridParam', 'page');
    gridInfo.rowNum = grid.jqGrid('getGridParam', 'rowNum');
    gridInfo.postData = grid.jqGrid('getGridParam', 'postData');
    setLocalStore(el_grid_settings.enc_location + '_sh', JSON.stringify(gridInfo), true);
}
function getAdminPreferenceLocal(type, grid_id) {
    if (!isLocalStorageAllow()) {
        if (type == "before") {
            el_grid_settings.load_post['post'] = {};
            el_grid_settings.load_post['search'] = {};
        }
        return false;
    }
    var gridParams = getLocalStore(el_grid_settings.enc_location + '_sh', true);
    var gridInfo = parseJSONString(gridParams);
    var filt;
    if (type == "before") {
        el_grid_settings.load_post['post'] = {};
        if (el_grid_settings.filters_arr && el_grid_settings.filters_arr.groups) {
            var js_groupOp = el_grid_settings.filters_arr.groupOp;
            var js_rules = el_grid_settings.filters_arr.rules;
            var js_d_groups = el_grid_settings.filters_arr.groups;
            apply_default_filter = false;
            filt = {groupOp: js_groupOp, rules: [], groups: []};
            if ($.isArray(js_d_groups) && js_d_groups.length > 0) {
                var js_group, js_group_rules, inner_rules, data_set;
                for (var req_g in js_d_groups) {
                    js_group = js_d_groups[req_g];
                    js_group_rules = js_group.rules;
                    inner_rules = [];
                    if ($.isArray(js_group_rules) && js_group_rules.length > 0) {
                        for (var req_i in js_group_rules) {
                            if (typeof js_group_rules[req_i].field != "undefined" && js_group_rules[req_i].field && js_group_rules[req_i].data) {
                                data_set = js_group_rules[req_i].data;
                                inner_rules.push({field: js_group_rules[req_i].field, op: js_group_rules[req_i].op, data: data_set});
                                apply_default_filter = true;
                            }
                        }
                        if (el_grid_settings.group_search == '1') {
                            filt.groups.push({groupOp: js_group.groupOp, rules: inner_rules, groups: []});
                        } else {
                            filt.rules = inner_rules;
                            delete filt.groups;
                            break;
                        }
                    }
                }
            }
            if (apply_default_filter) {
                el_grid_settings.load_post['post'] = {filters: JSON.stringify(filt)};
            }
        } else if (gridParams && gridInfo && gridInfo.postData && gridInfo.postData.filters) {
            el_grid_settings.load_post['post'] = {
                filters: gridInfo.postData.filters
            }
        }
        var sort_flag = true, sortname, sortorder, page, rowNum, sort_arr, sort_ord;
        el_grid_settings.load_post['post']['sdef'] = "Yes";
        if (gridInfo && gridInfo.sortname) {
            sort_arr = gridInfo.sortname.split(",");
            sort_ord = gridInfo.sortorder.split(",");
            if (el_grid_settings.grouping == 'Yes' && $.isArray(el_grid_settings.group_attr['field'])) {
                if (el_grid_settings.group_attr['field'][1]) {
                    sort_arr.unshift(el_grid_settings.group_attr['field'][1]);
                    sort_ord.unshift(el_grid_settings.group_attr['order'][1] || "asc");
                }
                sort_arr.unshift(el_grid_settings.group_attr['field'][0]);
                sort_ord.unshift(el_grid_settings.group_attr['order'][0] || "asc");
            }
            for (var i in js_col_model_json) {
                if ($.inArray(js_col_model_json[i]['index'], sort_arr) == -1) {
                    sort_flag = false;
                    break;
                }
            }
            if (sort_flag) {
                el_grid_settings.load_post['post']['sidx'] = sort_arr.join(',');
                el_grid_settings.load_post['post']['sord'] = sort_ord.join(',');
            }
        }
        if (gridInfo && gridInfo.page) {
            el_grid_settings.load_post['post']['page'] = gridInfo.page;
        }
        if (gridInfo && gridInfo.rowNum) {
            el_grid_settings.load_post['post']['rows'] = parseInt(gridInfo.rowNum);
        }
        if (el_grid_settings.load_post && el_grid_settings.load_post.post && el_grid_settings.load_post.post.filters) {
            keepSearchToolbarValue(grid_id, js_col_model_json, el_grid_settings.load_post.post.filters, "local");
        }
    } else if (type == "after") {
        var pD = $("#" + grid_id).getGridParam("postData");
        pD = $.isPlainObject(pD) ? pD : {};
        if (gridParams && gridInfo) {
            var sort_flag = true, sortname, sortorder, page, rowNum, sort_arr, sort_ord;
            if (gridInfo.sortname) {
                sort_arr = gridInfo.sortname.split(",");
                sort_ord = gridInfo.sortorder.split(",");
                if (el_grid_settings.grouping == 'Yes' && $.isArray(el_grid_settings.group_attr['field'])) {
                    if (el_grid_settings.group_attr['field'][1]) {
                        sort_arr.unshift(el_grid_settings.group_attr['field'][1]);
                        sort_ord.unshift(el_grid_settings.group_attr['order'][1] || "asc");
                    }
                    sort_arr.unshift(el_grid_settings.group_attr['field'][0]);
                    sort_ord.unshift(el_grid_settings.group_attr['order'][0] || "asc");
                }
                for (var i in js_col_model_json) {
                    if ($.inArray(js_col_model_json[i]['index'], sort_arr) == -1) {
                        sort_flag = false;
                        break;
                    }
                }
                if (sort_flag) {
                    sortname = sort_arr.join(",");
                    $("#" + grid_id).setGridParam({sortname: sortname});
                    pD['sidx'] = sortname;
                    if ($.isArray(sort_ord) && sort_ord.length) {
                        sortorder = sort_ord.join(",");
                        $("#" + grid_id).setGridParam({sortorder: sortorder});
                        pD['sord'] = sortorder;
                    }
                }
            }
            if (gridInfo.page) {
                page = gridInfo.page
                $("#" + grid_id).setGridParam({page: page});
                pD['page'] = page;
            }
            if (gridInfo.rowNum) {
                rowNum = parseInt(gridInfo.rowNum);
                $("#" + grid_id).setGridParam({rowNum: rowNum});
                $("select[class='ui-pg-selbox'][role='listbox']").val(rowNum);
                pD['rows'] = rowNum;
            }
            $("#" + grid_id).setGridParam("postData", pD);
        }
    }
    return true;
}
function keepSearchToolbarValue(grid_id, gridModel, filters, type) {
    if (!filters) {
        return;
    }
    filters = parseJSONString(filters);
    if (!filters || !filters.rules) {
        return;
    }
    var sfilt = filters.rules, found_arr = [], cmodel_obj = {}, smodel_obj = {}, data_val, temp_val;
    for (var i in gridModel) {
        if (gridModel[i].name == "cb" || gridModel[i].name == "subgrid" || gridModel[i].name == "prec") {
            continue;
        }
        cmodel_obj[gridModel[i].name] = gridModel[i];
    }
    for (var i = 0; i < sfilt.length; i++) {
        var field = sfilt[i].field;
        var fcm = cmodel_obj[field];
        if (!fcm) {
            continue;
        }
        if (sfilt[i].op == fcm.filterSopt && sfilt[i].data != "" && ($.inArray(field, found_arr) == "-1" || fcm.filterSopt == "in")) {
            if (!$.isPlainObject(smodel_obj[field])) {
                smodel_obj[field] = {};
            }
            if (!$.isArray(smodel_obj[field]['data'])) {
                smodel_obj[field]['data'] = [];
            }
            data_val = sfilt[i].data;
            if (fcm.filterSopt == "in") {
                temp_val = smodel_obj[field]['data']
                if ($.isArray(temp_val)) {
                    data_val = ($.isArray(data_val)) ? data_val : data_val.split(",");
                    data_val.concat(temp_val);
                }
                smodel_obj[field]['data'] = data_val;
            } else {
                smodel_obj[field]['data'].push(data_val);
            }
            found_arr.push(field);
        }
    }
    el_grid_settings.load_post['search'] = smodel_obj;
}
//related to filters
function getToolbarHashFilters(grid_id) {
    if (window.location.hash) {
        var req_hash_var_arr = getHashParams(window.location.hash, el_tpl_settings.framework_vars);
    } else {
        var req_hash_var_arr = getQueryParams(window.location.search, el_tpl_settings.framework_vars);
    }
    if (!Object.keys(req_hash_var_arr).length) {
        return false;
    }
    var alias_name_arr = [];
    if (js_col_model_json.length > 1) {
        for (var i in js_col_model_json) {
            if (js_col_model_json[i].name) {
                alias_name_arr.push(js_col_model_json[i].name);
            }
        }
    }
    if (alias_name_arr.length > 0) {
        var filt = {}, req_i, col_i;
        filt.rules = [];
        for (req_i in req_hash_var_arr) {
            if (req_i != "" && $.inArray(req_i, alias_name_arr) >= 0) {
                col_i = alias_name_arr.indexOf(req_i);
                if (js_col_model_json[col_i]['stype'] && js_col_model_json[col_i]['stype'] == "select") {
                    js_col_model_json[col_i]['filterSopt'] = 'in';
                    filt.rules.push({field: req_i, op: "in", data: req_hash_var_arr[req_i]});
                } else {
                    js_col_model_json[col_i]['filterSopt'] = 'eq';
                    filt.rules.push({field: req_i, op: "eq", data: req_hash_var_arr[req_i]});
                }
            }
        }
        if (filt.rules.length > 0) {
            keepSearchToolbarValue(grid_id, js_col_model_json, JSON.stringify(filt), "hash");
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function getHashFilterConditions(grid_id) {
    if (window.location.hash) {
        var req_hash_var_arr = getHashParams(window.location.hash, el_tpl_settings.framework_vars);
    } else {
        var req_hash_var_arr = getQueryParams(window.location.search, el_tpl_settings.framework_vars);
    }
    if (!Object.keys(req_hash_var_arr).length) {
        return;
    }
    var loc_grid, colModel, alias_name_arr = [];
    loc_grid = $('#' + grid_id);
    colModel = loc_grid.jqGrid('getGridParam', 'colModel');
    if (colModel.length > 1) {
        for (var i in colModel) {
            if (colModel[i].name) {
                alias_name_arr.push(colModel[i].name);
            }
        }
    }
    if (alias_name_arr.length > 0) {
        var filt, post_data, filters, req_i;
        post_data = loc_grid.jqGrid("getGridParam", "postData");
        filters = (post_data && post_data.filters) ? parseJSONString(post_data.filters) : {};
        filt = {
            groupOp: "AND",
            rules: (filters && filters.rules) ? filters.rules : [],
            entrys: (filters && filters.entrys) ? filters.entrys : "",
            range: (filters && filters.range) ? filters.range : ""
        };
        for (req_i in req_hash_var_arr) {
            if (req_i != "" && $.inArray(req_i, alias_name_arr) >= 0) {
                filt.rules.push({field: req_i, op: "eq", data: req_hash_var_arr[req_i]});
            }
        }
        loc_grid[0].p.search = true;
        $.extend(loc_grid[0].p.postData, {
            filters: JSON.stringify(filt)
        });
    }
}
function getHashParams(query, avoid_params) {
    var params = {};
    var neg_params = ($.isArray(avoid_params)) ? avoid_params : [];
    if (!query) {
        return params;
    }// return empty object
    query = query.toString().replace(/%7c/gi, '|');
    //var pairs = query.split(/[;|]/);
    var pairs = query.split("|");
    for (var i = 1; i < pairs.length; i += 2) {
        if (pairs[i] && $.inArray(pairs[i], neg_params) == "-1") {
            var key = unescape(pairs[i]);
            var val = (pairs[i + 1] != undefined) ? unescape(pairs[i + 1]) : "";
            val.replace(/\+/g, ' ');
            params[key] = val;
        }
    }
    return params;
}
function getQueryParams(query, avoid_params) {
    var params = {};
    var neg_params = ($.isArray(avoid_params)) ? avoid_params : [];
    if (!query) {
        return params;
    }// return empty object
    var queryStr = query.substring(1);
    if (!queryStr) {
        return params;
    }// return empty object
    //var pairs = query.split(/[;|]/);
    var pairs = query.split("&");
    for (var i = 0; i < pairs.length; i++) {
        var keyset = pairs[i].split("=");
        if (keyset[0] && $.inArray(keyset[0], neg_params) == "-1") {
            var key = unescape(keyset[0]);
            var val = (keyset[1] != undefined) ? unescape(keyset[1]) : "";
            val.replace(/\+/g, ' ');
            params[key] = val;
        }
    }
    return params;
}
function findGridViewParam(query, grid_id, type, key) {
    if (!query) {
        return type;
    }
    var arr = ["list", "view", "grid"];
    if (!$("#layout_view_" + grid_id).length && !$("#layout_grid_" + grid_id).length) {
        return type;
    }
    query = query.toString().replace(/%7c/gi, '|');
    //var pairs = query.split(/[;|]/);
    var pairs = query.split("|"), p1, mode;
    for (var i = 1; i < pairs.length; i += 2) {
        if (pairs[i] == "view") {
            p1 = (pairs[i + 1]) ? unescape(pairs[i + 1]) : "";
            if (p1 == "list") {
                mode = "view";
            } else if (p1 == "grid") {
                mode = "grid";
            } else {
                mode = "list";
            }
            break;
        }
    }
    if (!mode || $.inArray(mode, arr) == -1) {
        mode = getLocalStore(key);
    }
    if ($.inArray(mode, arr) == -1) {
        mode = type;
    } else {
        type = mode;
    }
    return type;
}
//related to subgrid listing
function initSubGridListing() {
    delete el_subgrid_settings.grid_nesgrid_alias;

    var subgrid_id = el_subgrid_settings.table_id, sub_pager_id = el_subgrid_settings.pager_id;
    var sub_js_prev_key = '', sub_js_assign_btn_id = '', sub_js_next_btn_id = '', sub_jsave = '', sub_saved_obj = '';
    var sub_js_col_name_arr = [], sub_js_sort_count = 0, sub_jrow = 0, sub_jcol = 0, sub_total_rows = 0, sub_total_pages = 1;
    var sub_js_before_req = true, sub_show_paging_var = true;

    var nes_grid_row = (el_subgrid_settings.nesgrid == 'Yes') ? true : false;
    var sub_row_numbers = (el_subgrid_settings.inline_add == "Yes") ? true : false;
    var sub_pager_active = (el_subgrid_settings.hide_paging_btn == "Yes") ? false : true;

    var sub_add_permit = (el_subgrid_settings.hide_add_btn == '1' && el_subgrid_settings.permit_add_btn == "1" && el_subgrid_settings.advanced_grid == '1') ? true : false;
    var sub_del_permit = (el_subgrid_settings.hide_del_btn == '1' && el_subgrid_settings.permit_del_btn == '1' && el_subgrid_settings.advanced_grid == '1') ? true : false;
    var sub_status_permit = (el_subgrid_settings.hide_status_btn == '1' && el_subgrid_settings.permit_edit_btn == '1' && el_subgrid_settings.advanced_grid == '1') ? true : false;
    var sub_export_permit = (el_subgrid_settings.hide_export_btn == '1' && el_subgrid_settings.permit_expo_btn == '1' && !el_general_settings.mobile_platform) ? true : false;
    var sub_print_permit = (el_subgrid_settings.print_list == 'Yes' && el_subgrid_settings.permit_print_btn == '1' && !el_general_settings.mobile_platform) ? true : false;

    var sub_columns_permit = (el_subgrid_settings.hide_columns_btn == 'Yes' || el_general_settings.mobile_platform) ? false : true;
    var sub_adv_search_permit = (el_subgrid_settings.hide_advance_search == 'Yes' || el_subgrid_settings.advanced_grid != '1') ? false : true;
    var sub_refresh_permit = (el_subgrid_settings.hide_refresh_btn == 'Yes' || el_subgrid_settings.advanced_grid != '1') ? false : true;

    var sub_inline_add_permit = (el_subgrid_settings.inline_add == "Yes" && el_subgrid_settings.permit_add_btn == "1" && el_subgrid_settings.advanced_grid == '1') ? true : false;
    var sub_search_tool_permit = (el_subgrid_settings.hide_search_tool == "Yes") ? false : true;

    var sub_global_filter = (el_subgrid_settings.global_filter == "Yes") ? true : false;
    var sub_top_filter_arr = $.isPlainObject(el_subgrid_settings.top_filter) ? el_subgrid_settings.top_filter : [];
    var sub_action_callbacks = $.isPlainObject(el_subgrid_settings['callbacks']) ? el_subgrid_settings['callbacks'] : {};
    var sub_list_message_arr = $.isPlainObject(el_subgrid_settings['message_arr']) ? el_subgrid_settings['message_arr'] : {};

    var sub_viewtemplate = '#layout_view_' + subgrid_id;
    var sub_gridtemplate = '#layout_grid' + subgrid_id;

    var sub_grid_button_arr = ($.isArray(el_subgrid_settings.buttons_arr)) ? el_subgrid_settings.buttons_arr : [];
    var sub_grid_button_ids = {
        "add": "add_" + subgrid_id,
        "del": "del_" + subgrid_id,
        "search": "search_" + subgrid_id,
        "refresh": "refresh_" + subgrid_id,
        "columns": "columns_" + subgrid_id,
        "export": "export_" + subgrid_id,
        "print": "print_" + subgrid_id
    }

    if (el_general_settings.mobile_platform) {
        //el_subgrid_settings.auto_width = "Yes";
    }

    if (typeof executeBeforeGridInit == "function") {
        executeBeforeGridInit(el_subgrid_settings['module_name'], "sub");
    }
    if (sub_action_callbacks['before_grid_init'] && $.isFunction(window[sub_action_callbacks['before_grid_init']])) {
        window[sub_action_callbacks['before_grid_init']](el_subgrid_settings, sub_js_col_model_json, sub_js_col_name_json);
    }

    for (var i in sub_js_col_name_json) {
        sub_js_col_name_arr.push(sub_js_col_name_json[i]['label']);
    }

    if (!sub_add_permit && !sub_del_permit && !sub_status_permit && !sub_adv_search_permit && !sub_columns_permit &&
            !sub_refresh_permit && !sub_inline_add_permit && !sub_search_tool_permit && !sub_global_filter &&
            !($.isArray(sub_top_filter_arr) && sub_top_filter_arr.length > 0) &&
            !($(sub_viewtemplate).length || $(sub_gridtemplate).length) && el_tpl_settings.grid_top_menu == 'N') {
        sub_show_paging_var = false;
    }
    var listview = findGridViewParam(window.location.hash, subgrid_id, el_subgrid_settings.listview, el_grid_settings.enc_location + '_sg_gv');
    setHideColumnSettings(subgrid_id, sub_js_col_model_json, sub_top_filter_arr);
    getColumnsWidth(el_grid_settings.enc_location + '_sg_cw', subgrid_id, sub_js_col_model_json);

    jQuery("#" + subgrid_id).jqGrid({
        url: el_subgrid_settings.listing_url,
        editurl: el_subgrid_settings.edit_page_url,
        mtype: 'POST',
        datatype: "json",
        colNames: sub_js_col_name_arr,
        colModel: sub_js_col_model_json,
        page: 1,
        pgbuttons: sub_pager_active,
        pginput: sub_pager_active,
        pgnumbers: (el_theme_settings.grid_sub_pgnumbers) ? true : false, //custom
        pgnumlimit: (el_general_settings.mobile_platform) ? 2 : parseInt(el_theme_settings.grid_sub_pgnumlimit), //custom
        pagingpos: el_theme_settings.grid_sub_pagingpos, //custom
        rowNum: (el_subgrid_settings.hide_paging_btn == "Yes") ? 1000000 : parseInt(el_tpl_settings.grid_rec_limit),
        rowList: (sub_pager_active) ? pager_row_list : [],
        sortname: el_subgrid_settings.default_sort,
        sortorder: el_subgrid_settings.sort_order,
        altRows: true,
        altclass: 'evenRow',
        multiselectWidth: 30,
        multiselect: (el_subgrid_settings.hide_multi_select == "Yes") ? false : true,
        multiboxonly: true,
        hiderecords: el_subgrid_settings.admin_rec_arr,
        viewrecords: true,
        norecmsg: js_lang_label.GENERIC_GRID_NO_RECORDS_FOUND,
        caption: false,
        hidegrid: false,
        listview: listview, //custom 
        viewtemplate: '#layout_view_' + subgrid_id, //custom
        gridtemplate: '#layout_grid' + subgrid_id, //custom
        viewCallback: function (id, type) {
            //custom
            reloadListGrid(subgrid_id, null, 2, el_subgrid_settings);
            setLocalStore(el_grid_settings.enc_location + '_sg_gv', type);
        },
        listtags: ['{', '}'], //custom
        inlineadd: (el_subgrid_settings.inline_add == "Yes") ? true : false, //custom
        inlinerecpos: (el_subgrid_settings.rec_position == "Bottom") ? true : false, //custom
        isSubMod: 1, //custom
        curModule: el_subgrid_settings.add_page_url, //custom
        parModule: el_subgrid_settings.par_module, //custom
        parData: el_subgrid_settings.par_data, //custom
        parField: el_subgrid_settings.par_field, //custom
        parType: el_subgrid_settings.par_type, //custom
        extraHash: el_subgrid_settings.extra_hstr, //custom
        ratingAllow: (el_subgrid_settings.rating_allow == "Yes") ? true : false, //custom
        pager: (el_tpl_settings.grid_bot_menu == 'Y') ? sub_pager_id : "",
        toppager: (el_tpl_settings.grid_top_menu == 'Y') ? true : false,
        toppaging: (el_tpl_settings.grid_top_menu == 'Y') ? true : false, //custom
        showpaging: sub_show_paging_var, //custom
        cellurl: el_subgrid_settings.edit_page_url,
        cellsubmit: 'remote',
        sortable: {
            update: function (permutation) {
                setColumnsPosition(el_grid_settings.enc_location + '_sg_cp', permutation, subgrid_id, sub_js_col_model_json);
            }
        },
        searchGrid: {
            multipleSearch: true,
            searchToolbar: (sub_search_tool_permit) ? true : false,
            globalFilter: (sub_global_filter) ? true : false,
            topFilters: sub_top_filter_arr,
            topDataInit: triggerTopFilterEvent
        },
        afterSearchToggle: function (id) {
            //custom
            if ($("#hbox_" + id + "_jqgrid").find(".ui-search-toolbar").is(":hidden")) {
                $("#listsearch_" + id + "_top").removeClass("active");
            }
            resizeGridWidth();
        },
        height: '100%',
        autowidth: (el_subgrid_settings.auto_width == "No") ? false : true,
        _autowidth: (el_subgrid_settings.auto_width == "No") ? false : true,
        shrinkToFit: (el_subgrid_settings.auto_width == "No") ? false : true,
        fixed: true,
        //rownumbers: sub_row_numbers,
        multiSort: (el_tpl_settings.grid_multiple_sorting) ? true : false,
        subGrid: nes_grid_row,
        subGridWidth: 18,
        subGridRowExpanded: function (nesgrid_id, row_id) {
            var nesgrid_table_id, add_params = '', t_did = "";
            nesgrid_table_id = nesgrid_id + "_nes";
            if (el_subgrid_settings.nesgrid == "Yes") {
                add_params = "&SGType=main";
            } else {
                if (!el_subgrid_settings.grid_nesgrid_alias) {
                    if (el_subgrid_settings.nesgrid == "Yes") {
                        add_params = "&SGType=main";
                    } else {
                        for (var i in sub_js_col_model_json) {
                            if (sub_js_col_model_json[i]['expandrow']) {
                                sub_js_col_model_json.grid_nesgrid_alias = sub_js_col_model_json[i]['index'];
                                break;
                            }
                        }
                        add_params = "&SGType=each&SGAlias=" + el_subgrid_settings.grid_nesgrid_alias;
                    }
                } else {
                    add_params = "&SGType=each&SGAlias=" + el_subgrid_settings.grid_nesgrid_alias;
                }
            }
            delete el_subgrid_settings.grid_nesgrid_alias;
            initGirdLoadingOverlay(el_tpl_settings.main_grid_id);
            $("#" + nesgrid_id).html("<div id='" + nesgrid_table_id + "' class='scroll subgird-block'></div>");
            $("#" + nesgrid_table_id).html('<div class="subgrid-loader"><i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i></div>');
            $.ajax({
                url: el_subgrid_settings.nesgrid_listing_url + '&SGRender=nested' + add_params,
                type: 'POST',
                data: {
                    "SGID": row_id,
                    "SGridID": nesgrid_id
                },
                success: function (data) {
                    $("#" + nesgrid_table_id).addClass("subgrid_view_div_display");
                    $("#" + nesgrid_table_id).html(data);
                    hideGirdLoadingOverlay(el_tpl_settings.main_grid_id);
                    initializeNesgridEvents($("#" + nesgrid_table_id));
                }
            });
        },
        subGridRowColapsed: function (nesgrid_id, row_id) {
            // this function is called before removing the data
            var nesgrid_table_id = nesgrid_id + "_nes";
            jQuery("#" + nesgrid_table_id).remove();
            resizeGridWidth();
        },
        grouping: (el_subgrid_settings.grouping == 'Yes') ? true : false,
        groupingView: {
            groupField: ($.isArray(el_subgrid_settings.group_attr['field'])) ? el_subgrid_settings.group_attr['field'] : [],
            groupOrder: ($.isArray(el_subgrid_settings.group_attr['order'])) ? el_subgrid_settings.group_attr['order'] : [],
            groupText: ($.isArray(el_subgrid_settings.group_attr['text'])) ? el_subgrid_settings.group_attr['text'] : [],
            groupColumnShow: ($.isArray(el_subgrid_settings.group_attr['column'])) ? el_subgrid_settings.group_attr['column'] : [],
            groupSummary: ($.isArray(el_subgrid_settings.group_attr['summary'])) ? el_subgrid_settings.group_attr['summary'] : [],
            showSummaryOnHide: ($.isArray(el_subgrid_settings.group_attr['summary'])) ? el_subgrid_settings.group_attr['summary'] : [],
            groupCollapse: false,
            groupDataSorted: true
        },
        footerrow: (el_subgrid_settings.footer_row == 'Yes') ? true : false,
        userDataOnFooter: true,
        beforeRequest: function () {
            if (sub_js_before_req) {
                sub_js_before_req = false;
                getColumnsPosition(el_grid_settings.enc_location + '_sg_cp', subgrid_id);
            }
        },
        beforeProcessing: function (data) {
            sub_total_rows = 0;
            sub_total_pages = 1;
            delete el_general_settings.grid_sub_link_model;
            if (data && data.total) {
                sub_total_pages = data.total;
            }
            if (data && data.records) {
                sub_total_rows = data.records;
            }
            if (data && data.links) {
                el_general_settings.grid_sub_link_model = data.links;
            }
        },
        loadError: function (xhr, status, error) {
            hideGirdLoadingOverlay(el_tpl_settings.main_grid_id);
        },
        loadComplete: function (data) {
            setTimeout(function () {
                hideGirdLoadingOverlay(el_tpl_settings.main_grid_id);
                Project.hide_adaxloading_div();
            }, 2);
            $("#" + el_tpl_settings.main_grid_id + "_messages_html").remove();
            $("#selAllRows").val('false');
            // No Records Message
            noRecordsMessage(subgrid_id, data);
            // Add new record
            //addNewInlineRecord(subgrid_id);
            // Row colors
            applyGridRowColors(subgrid_id, data);
            // Rating Events
            //applyRatingEvents(subgrid_id);
            // Resizing Sub Grid
            resizeSubGridWidth(subgrid_id);
            // adjust main grid width
            adjustMainGridColumnWidth();
            // fancybox image events
            initializeFancyBoxEvents();
            //set columns widths
            checkColumnsWidth(el_grid_settings.enc_location + '_sg_cw', subgrid_id);
            if (typeof executeAfterGridLoad == "function") {
                executeAfterGridLoad(el_subgrid_settings['module_name'], "sub");
            }
            if (sub_action_callbacks['after_data_load'] && $.isFunction(window[sub_action_callbacks['after_data_load']])) {
                window[sub_action_callbacks['after_data_load']](data);
            }
        },
        gridComplete: function () {
            // Resizing Sub Grid
            resizeSubGridWidth(subgrid_id);
            hideAdminDataCheckBox(subgrid_id, el_subgrid_settings.admin_rec_arr);
            getAdminImageTooltip(subgrid_id);
        },
        ondblClickRow: function (rowid, iRow, iCol, e) {
            var ac = $(e.srcElement).hasClass("add-cell") ? 1 : 0
            var ai = ($(e.srcElement).attr("aria-newrow") == "inline-add-row") ? 1 : 0;
            var bc = ($(e.srcElement).find(".inline-edit-row").length > 0) ? 1 : 0
            var cf = ($(e.srcElement).hasClass(".inline-edit-row")) ? 1 : 0
            var sf = ($(e.srcElement).closest("td[role='gridcell']").hasClass('edit-cell')) ? 1 : 0
            if (ac || ai || bc || cf || sf) {
                e.stopPropagation();
            } else {
                $("#" + el_tpl_settings.main_grid_id).jqGrid('setGridParam', {
                    cellEdit: false
                });
                var $this = $(this);
                $this.jqGrid('setGridParam', {
                    cellEdit: true
                });
                $this.jqGrid('editCell', iRow, iCol, true);
                $this.jqGrid('setGridParam', {
                    cellEdit: false
                });
                e.stopPropagation();
            }
        },
        beforeEditCell: function (rowid, cellName, cellValue, iRow, iCol) {
            restoreBeforeEditedCell(this, sub_jrow, sub_jcol, sub_jsave);
            if ($(".colpick").length) {
                $(".colpick").hide();
            }
        },
        afterEditCell: function (rowid, cellName, cellValue, iRow, iCol) {
            var cellDOM = this.rows[iRow].cells[iCol], oldKeydown;
            var $cellInput = $("#" + iRow + "_" + cellName, cellDOM);
            var events = $._data($cellInput.eq(0), "events"), cselector = $cellInput["selector"];
            var $this = $(this), date_flag = false, colorpicker_flag = false, phone_flag = false;
            if ($cellInput.hasClass("dateOnly")) {
                inlineDateTimePicker(iRow, cellName, 'date');
                var date_flag = true;
            } else if ($cellInput.hasClass("timeOnly")) {
                inlineDateTimePicker(iRow, cellName, 'time');
                var date_flag = true;
            } else if ($cellInput.hasClass("dateTime")) {
                inlineDateTimePicker(iRow, cellName, 'dateTime');
                var date_flag = true;
            } else if ($cellInput.hasClass("colorPicker")) {
                inlineColorPicker(iRow, cellName, 'colorPicker');
                var colorpicker_flag = true;
            } else if ($cellInput.hasClass("phoneNumber")) {
                var phone_flag = true;
            } else if ($cellInput.hasClass("inline-textarea-edit")) {
                var txt = $($cellInput).val();
                txt = txt.replace(/<br>/g, "");
                txt = txt.replace(/<BR>/g, "");
                $($cellInput).val(txt);
            }
            sub_jrow = iRow;
            sub_jcol = iCol;
            sub_jsave = ($.isArray(this.p.savedRow)) ? this.p.savedRow[this.p.savedRow.length - 1].v : "";
            if ($(cellDOM).find("select[role='select']").length) {
                $cellDrop = $(cellDOM).find("select[role='select']");
                $($cellDrop).attr("aria-update-id", rowid);
                sub_saved_obj = this.p.savedRow;
                $($cellDrop).on('change', function (e) {
                    $this.jqGrid('setGridParam', {
                        cellEdit: true
                    });
                    $this.jqGrid('setGridParam', {
                        savedRow: sub_saved_obj
                    });
                    $this.jqGrid('saveCell', iRow, iCol);
                    $this.jqGrid('restoreCell', iRow, iCol, true);
                    $(cellDOM).removeClass("ui-state-highlight");
                    sub_jrow = 0, sub_jcol = 0, sub_jsave = '';
                    sub_saved_obj = $this.jqGrid('getGridParam', 'savedRow');
                });
                var autoChznInterval = setInterval(function () {
                    if ($(cselector).hasClass("chosen-select") && $(cselector + "_chosen").length) {
                        $(cselector + "_chosen").on('keydown', function (e) {
                            if (e.keyCode == 27) {
                                if ($(cselector + "_chosen").find(".chosen-drop").css("left") == "-9999px") {
                                    $this.jqGrid('setGridParam', {
                                        cellEdit: true
                                    });
                                    $this.jqGrid('setGridParam', {
                                        savedRow: sub_saved_obj
                                    });
                                    $this.jqGrid('restoreCell', iRow, iCol, true);
                                    $(cellDOM).removeClass("ui-state-highlight");
                                    sub_jrow = 0, sub_jcol = 0, sub_jsave = '';
                                }
                            }
                        });
                        clearInterval(autoChznInterval);
                    }
                }, 250);
            } else {
                applyInputTextCase($(cellDOM));
                sub_saved_obj = this.p.savedRow;
                setTimeout(function () {
                    if (events && events.keydown && events.keydown.length) {
                        $this.jqGrid('setGridParam', {
                            savedRow: sub_saved_obj
                        });
                        oldKeydown = events.keydown[0].handler;
                        $cellInput.unbind('keydown', oldKeydown);
                        $cellInput.bind('keydown', function (e) {
                            $this.jqGrid('setGridParam', {
                                cellEdit: true
                            });
                            $this.jqGrid('setGridParam', {
                                savedRow: sub_saved_obj
                            });
                            if ($cellInput.hasClass("inline-textarea-edit")) {
                                if (e.keyCode === 13) {
                                    if (e.shiftKey) {
                                        e.stopPropagation();
                                    } else {
                                        $this.jqGrid('saveCell', iRow, iCol);
                                        $this.jqGrid('restoreCell', iRow, iCol, true);
                                        $(cellDOM).removeClass("ui-state-highlight");
                                        sub_jrow = 0, sub_jcol = 0, sub_jsave = '';
                                    }
                                } else {
                                    oldKeydown.call(this, e);
                                }
                            } else if ($cellInput.hasClass("colorPicker")) {
                                if (e.keyCode === 9 || e.keyCode === 13 || e.keyCode === 27) {
                                    if ($(".colpick").length) {
                                        //$(".colpick").remove();
                                        $(".colpick").hide();

                                    }
                                }
                                oldKeydown.call(this, e);
                            } else {
                                oldKeydown.call(this, e);
                                $this.jqGrid('setGridParam', {
                                    cellEdit: false
                                });
                            }
                        }).bind('focusout', function (e) {
                            $this.jqGrid('setGridParam', {
                                savedRow: sub_saved_obj
                            });
                            if (date_flag) {
                                if ($(".ui-datepicker").is(":hidden")) {
                                    $this.jqGrid('setGridParam', {
                                        cellEdit: true
                                    });
                                    //$this.jqGrid('saveCell', iRow, iCol);
                                    $this.jqGrid('restoreCell', iRow, iCol, true);
                                    $(cellDOM).removeClass("ui-state-highlight");
                                    sub_jrow = 0, sub_jcol = 0, sub_jsave = '';
                                }
                            } else if (colorpicker_flag) {
                                if ($(".colpick" + "#" + $cellInput.attr("colorpickerid")).is(":hidden")) {
                                    $this.jqGrid('setGridParam', {
                                        cellEdit: true
                                    });
                                    //$this.jqGrid('saveCell', iRow, iCol);
                                    $this.jqGrid('restoreCell', iRow, iCol, true);
                                    $(cellDOM).removeClass("ui-state-highlight");
                                    sub_jrow = 0, sub_jcol = 0, sub_jsave = '';
                                }
                            } else {
                                var save_flag = true;
                                if (save_flag) {
                                    $this.jqGrid('setGridParam', {
                                        cellEdit: true
                                    });
                                    if (phone_flag == true) {
                                        $this.jqGrid('saveCell', iRow, iCol);
                                    }
                                    $this.jqGrid('restoreCell', iRow, iCol, true);
                                    $(cellDOM).removeClass("ui-state-highlight");
                                    sub_jrow = 0, sub_jcol = 0, sub_jsave = '';
                                }
                            }
                        });
                    }
                }, 100);
            }
        },
        beforeSubmitCell: function (rowid, cellName, cellValue, iRow, iCol) {
            if (sub_action_callbacks['before_rec_edit'] && $.isFunction(window[sub_action_callbacks['before_rec_edit']])) {
                return window[sub_action_callbacks['before_rec_edit']](rowid, cellName, cellValue, iRow, iCol);
            }
        },
        afterSubmitCell: function (response, rowid, cellname, value, iRow, iCol) {
            var $c_flag, $c_msg;
            if (response.responseText != 1) {
                var res = parseJSONString(response.responseText);
                var columnNames = $("#" + subgrid_id).jqGrid('getGridParam', 'colNames');
                $c_flag = true;
                $c_msg = res.message;
                if (res.success == 'false') {
                    $c_flag = false;
                    $c_msg += " : " + columnNames[iCol];
                } else if (res.success == '2') {
                    reloadListGrid(subgrid_id);
                } else if (res.success == '3' || res.success == '4') {
                    if (isRedirectEqualHash(res.red_hash)) {
                        window.location.hash = res.red_hash;
                        window.location.reload();
                    } else {
                        window.location.hash = res.red_hash;
                    }
                } else if (res.success == '5') {
                    window.location.href = res.red_hash;
                }
                gridReportMessage($c_flag, $c_msg);
            } else {
                $c_flag = true;
            }
            if (sub_action_callbacks['after_rec_edit'] && $.isFunction(window[sub_action_callbacks['after_rec_edit']])) {
                return window[sub_action_callbacks['after_rec_edit']](response, rowid, cellname, value, iRow, iCol);
            }
            return [$c_flag, res.message];
        },
        afterSaveCell: function (rowid, cellname, value, iRow, iCol) {

        },
        onSortCol: function (index, iCol, sortorder) {
            $("#" + subgrid_id).setGridParam({defaultsort: "No"});
            activateGridSortColumns(subgrid_id);
        },
        resizeStop: function (newwidth, index) {
            setColumnsWidth(el_grid_settings.enc_location + '_sg_cw', subgrid_id);
        },
        beforeSelectRow: function (rowid, e) {
            multiSelectHandler(rowid, e);
        }
    });

    if (sub_search_tool_permit) {
        jQuery("#" + subgrid_id).jqGrid('filterToolbar', {
            stringResult: true,
            searchOnEnter: false,
            searchOperators: (el_theme_settings.grid_sub_searchopt) ? true : false
        });
    }

    var createDelSearchRefreshBtn = function (order_arr, label_arr) {
        var del_icon, del_text = '', del_title;
        del_icon = (el_theme_settings.grid_sub_icons_del || (label_arr['del'] && label_arr['del']['icon_only'] == "Yes")) ? true : false;
        if (!del_icon) {
            del_text = (label_arr['del'] && label_arr['del']['text']) ? label_arr['del']['text'] : js_lang_label.GENERIC_GRID_DELETE;
        }
        del_title = (label_arr['del'] && label_arr['del']['title']) ? label_arr['del']['title'] : js_lang_label.GENERIC_GRID_DELETE_SELECTED_ROW;

        var search_icon, search_text = '', search_title;
        search_icon = (el_theme_settings.grid_sub_icons_search || (label_arr['search'] && label_arr['search']['icon_only'] == "Yes")) ? true : false;
        if (!search_icon) {
            search_text = (label_arr['search'] && label_arr['search']['text']) ? label_arr['search']['text'] : js_lang_label.GENERIC_GRID_SEARCH;
        }
        search_title = (label_arr['search'] && label_arr['search']['title']) ? label_arr['search']['title'] : js_lang_label.GENERIC_GRID_ADVANCE_SEARCH;

        var refresh_icon, refresh_text = '', refresh_title;
        refresh_icon = (el_theme_settings.grid_sub_icons_refresh || (label_arr['refresh'] && label_arr['refresh']['icon_only'] == "Yes")) ? true : false;
        if (!refresh_icon) {
            refresh_text = (label_arr['refresh'] && label_arr['refresh']['text']) ? label_arr['refresh']['text'] : js_lang_label.GENERIC_GRID_SHOW_ALL;
        }
        refresh_title = (label_arr['refresh'] && label_arr['refresh']['title']) ? label_arr['refresh']['title'] : js_lang_label.GENERIC_GRID_SHOW_ALL_LISTING_RECORDS;

        jQuery("#" + subgrid_id).jqGrid('navGrid', '#' + sub_pager_id, {
            cloneToTop: true,
            add: false,
            addicon: "ui-icon-plus",
            edit: false,
            editicon: "ui-icon-pencil",
            del: sub_del_permit,
            delicon: "ui-icon-trash",
            delicon_p: (del_icon) ? 'uigrid-del-btn del-icon-only' : "uigrid-del-btn",
            deltext: del_text,
            deltitle: del_title,
            search: sub_adv_search_permit,
            searchicon: "ui-icon-search",
            searchicon_p: (search_icon) ? 'uigrid-search-btn search-icon-only' : "uigrid-search-btn",
            searchtext: search_text,
            searchtitle: search_title,
            refresh: sub_refresh_permit,
            refreshicon: "ui-icon-refresh",
            refreshicon_p: (refresh_icon) ? 'uigrid-refresh-btn refresh-icon-only' : "uigrid-refresh-btn",
            refreshtext: refresh_text,
            refreshtitle: refresh_title,
            alerttext: js_lang_label.GENERIC_GRID_PLEASE_SELECT_ANY_RECORD,
            beforeRefresh: function () {
                $("#" + subgrid_id).setGridParam({sortname: el_subgrid_settings.default_sort, sortorder: el_subgrid_settings.sort_order, defaultsort: "Yes"});
                activateGridSortColumns(subgrid_id);
            },
            afterRefresh: function () {
                $("#hbox_" + subgrid_id + "_jqgrid").find(".search-chosen-select").find("option").removeAttr("selected");
                $("#hbox_" + subgrid_id + "_jqgrid").find(".search-chosen-select").trigger("chosen:updated");
                if ($("#hbox_" + subgrid_id + "_jqgrid").find(".search-token-autocomplete").length) {
                    $("#hbox_" + subgrid_id + "_jqgrid").find(".search-token-autocomplete").each(function () {
                        $(this).tokenInput("clear");
                    });
                }
                $("#hbox_" + subgrid_id + "_jqgrid").find(".top-filter-chosen").find("option").removeAttr("selected");
                $("#hbox_" + subgrid_id + "_jqgrid").find(".top-filter-chosen").trigger("chosen:updated");
                if ($("#hbox_" + subgrid_id + "_jqgrid").find(".top-filter-autocomplete").length) {
                    $("#hbox_" + subgrid_id + "_jqgrid").find(".top-filter-autocomplete").each(function () {
                        $(this).tokenInput("clear");
                    });
                }
            }
        }, {
            // edit options
        }, {
            // add options
        }, {
            // delete options
            id: sub_grid_button_ids.del,
            width: 320,
            caption: js_lang_label.GENERIC_GRID_DELETE,
            msg: js_lang_label.GENERIC_GRID_ARE_YOU_SURE_WANT_TO_DELETE_SELECTED_RECORDS,
            bSubmit: js_lang_label.GENERIC_GRID_DELETE,
            bCancel: js_lang_label.GENERIC_GRID_CANCEL,
            modal: true,
            closeOnEscape: true,
            serializeDelData: function (postdata) {
                var selAllRows = jQuery('#selAllRows').val();
                // append postdata with any information 
                return {
                    "id": postdata.id,
                    "oper": postdata.oper,
                    "AllRowSelected": selAllRows,
                    "filters": $('#' + subgrid_id).getGridParam('postData').filters
                }
            },
            beforeSubmit: function (postdata) {
                if (sub_action_callbacks['before_rec_delete'] && $.isFunction(window[sub_action_callbacks['before_rec_delete']])) {
                    return window[sub_action_callbacks['before_rec_delete']](postdata);
                } else {
                    return [true, ""];
                }
            },
            afterSubmit: function (response, postdata) {
                var resdata = parseJSONString(response.responseText), $del_flag, $jq_errmsg;
                if (resdata.success == 'true') {
                    $jq_errmsg = js_lang_label.GENERIC_GRID_RECORDS_DELETED_SUCCESSFULLY;
                    if (resdata.message != "") {
                        $jq_errmsg = resdata.message;
                    }
                    $del_flag = true;
                } else {
                    $jq_errmsg = js_lang_label.GENERIC_GRID_ERROR_IN_DELETION;
                    if (resdata.message != "") {
                        $jq_errmsg = resdata.message;
                    }
                    $del_flag = false;
                }
                gridReportMessage($del_flag, $jq_errmsg);
                if (sub_action_callbacks['after_rec_delete'] && $.isFunction(window[sub_action_callbacks['after_rec_delete']])) {
                    window[sub_action_callbacks['after_rec_delete']](response, postdata);
                }
                return [true, $jq_errmsg];
            }
        }, {
            // search options
            id: sub_grid_button_ids.search,
            multipleSearch: true,
            multipleGroup: (el_subgrid_settings.group_search == "1") ? true : false,
            showQuery: false,
            Find: js_lang_label.GENERIC_GRID_FIND,
            Reset: js_lang_label.GENERIC_GRID_RESET,
            width: 700,
            height: 275,
            closeOnEscape: true,
            modal: true,
            closeAfterSearch: true
        }, {
            // view options
        }, {
            // refresh options
            id: sub_grid_button_ids.refresh
        }, {
            // order options array
            order: order_arr
        });
    }

    var createAddButton = function (afterId, label_arr) {
        var add_icon, add_text = '', add_title;
        add_icon = (el_theme_settings.grid_sub_icons_add || (label_arr['icon_only'] == "Yes")) ? true : false;
        if (!add_icon) {
            add_text = (label_arr['text']) ? label_arr['text'] : js_lang_label.GENERIC_GRID_ADD_NEW;
        }
        add_title = (label_arr['title']) ? label_arr['title'] : js_lang_label.GENERIC_GRID_ADD_NEW;

        sub_js_assign_btn_id = sub_grid_button_ids.add;
        jQuery("#" + subgrid_id).navButtonAdd('#' + sub_pager_id, {
            caption: add_text,
            title: add_title,
            buttonicon: "ui-icon-plus",
            buttonicon_p: (add_icon) ? 'uigrid-add-btn add-icon-only' : 'uigrid-add-btn',
            onClickButton: function () {
                adminAddNewRecord(el_subgrid_settings.add_page_url, el_subgrid_settings.extra_hstr, el_subgrid_settings.popup_add_form, subgrid_id, el_subgrid_settings.popup_add_size);
            },
            id: sub_js_assign_btn_id,
            afterButtonId: afterId,
            position: "first"
        });
        jQuery("#" + subgrid_id).navButtonAdd("#" + subgrid_id + "_toppager_left", {
            caption: add_text,
            title: add_title,
            buttonicon: "ui-icon-plus",
            buttonicon_p: (add_icon) ? 'uigrid-add-btn add-icon-only' : 'uigrid-add-btn',
            onClickButton: function () {
                adminAddNewRecord(el_subgrid_settings.add_page_url, el_subgrid_settings.extra_hstr, el_subgrid_settings.popup_add_form, subgrid_id, el_subgrid_settings.popup_add_size);
            },
            id: sub_js_assign_btn_id + "_top",
            afterButtonId: (afterId) ? afterId + "_top" : "",
            position: "first"
        });
    }
    var createStatusButton = function (afterId) {
        var sub_jstatus_btn, sub_jstatus_lbl, sub_status_icon;
        for (var i in el_subgrid_settings.status_arr) {
            if (!el_subgrid_settings.status_arr[i]) {
                continue;
            }
            sub_jstatus_btn = el_subgrid_settings.status_arr[i];
            sub_jstatus_lbl = eval(el_subgrid_settings.status_lang_arr[i]);
            sub_status_icon = (sub_jstatus_btn || "").replace(/(\s)/g, "").toLowerCase();

            sub_js_assign_btn_id = "status_" + i + "_" + subgrid_id;
            sub_js_next_btn_id = (i == 0) ? afterId : "status_" + sub_js_prev_key + "_" + subgrid_id;

            jQuery("#" + subgrid_id).navButtonAdd('#' + sub_pager_id, {
                caption: sub_jstatus_lbl,
                title: sub_jstatus_lbl,
                lang: sub_jstatus_btn,
                buttonicon: "ui-icon-newwin",
                buttonicon_p: "uigrid-status-common uigrid-status-btn-" + sub_status_icon,
                onClickButton: function (e, p) {
                    var fids = filterGridSelectedIDs(this);
                    adminStatusChange(subgrid_id, p.lang, fids, el_subgrid_settings.edit_page_url, p.title, sub_action_callbacks, sub_list_message_arr);
                },
                id: sub_js_assign_btn_id,
                afterButtonId: sub_js_next_btn_id,
                position: "first"
            });
            jQuery("#" + subgrid_id).navButtonAdd("#" + subgrid_id + "_toppager_left", {
                caption: "" + sub_jstatus_lbl,
                title: sub_jstatus_lbl,
                lang: sub_jstatus_btn,
                buttonicon: "ui-icon-newwin",
                buttonicon_p: "uigrid-status-common uigrid-status-btn-" + sub_status_icon,
                onClickButton: function (e, p) {
                    var fids = filterGridSelectedIDs(this);
                    adminStatusChange(subgrid_id, p.lang, fids, el_subgrid_settings.edit_page_url, p.title, sub_action_callbacks, sub_list_message_arr);
                },
                id: sub_js_assign_btn_id + "_top",
                afterButtonId: (sub_js_next_btn_id) ? sub_js_next_btn_id + "_top" : "",
                position: "first"
            });
            sub_js_prev_key = i;
        }
    }
    var createColumnsButton = function (afterId, label_arr) {
        var col_icon, col_text = '', col_title;
        col_icon = (el_theme_settings.grid_sub_icons_columns || (label_arr['icon_only'] == "Yes")) ? true : false;
        if (!col_icon) {
            col_text = (label_arr['text']) ? label_arr['text'] : js_lang_label.GENERIC_GRID_COLUMNS;
        }
        col_title = (label_arr['title']) ? label_arr['title'] : js_lang_label.GENERIC_GRID_HIDESHOW_COLUMNS;

        sub_js_assign_btn_id = sub_grid_button_ids.columns;
        jQuery("#" + subgrid_id).navButtonAdd('#' + sub_pager_id, {
            caption: col_text,
            title: col_title,
            buttonicon: "ui-icon-columns",
            buttonicon_p: (col_icon) ? 'uigrid-col-btn col-icon-only' : 'uigrid-col-btn',
            onClickButton: function () {
                jQuery("#" + subgrid_id).jqGrid('columnChooser', {
                    'classname': 'grid-columns-picker',
                    'dialog_opts': {
                        modal: true,
                        minWidth: 460
                    },
                    'msel_opts': {
                        'autoOpen': true,
                        'checkAllText': (js_lang_label.GENERIC_CHECK_ALL ? js_lang_label.GENERIC_CHECK_ALL : "Check all"),
                        'uncheckAllText': (js_lang_label.GENERIC_UNCHECK_ALL ? js_lang_label.GENERIC_UNCHECK_ALL : "Uncheck all"),
                        'noneSelectedText': (js_lang_label.GENERIC_GRID_SELECT_COLUMNS ? js_lang_label.GENERIC_GRID_SELECT_COLUMNS : "Select Columns"),
                        'selectedText': "# " + (js_lang_label.GENERIC_SELECTED ? js_lang_label.GENERIC_SELECTED : "Selected"),
                        'filterPlaceholder': (js_lang_label.GENERIC_GRID_SEARCH_HERE ? js_lang_label.GENERIC_GRID_SEARCH_HERE : "Search here"),
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
                        if (perm) {
                            setColumnsChoosen(el_grid_settings.enc_location + '_sg_cs', subgrid_id);
                            reloadListGrid(subgrid_id);
                        }
                    }
                });
            },
            position: "last",
            id: sub_js_assign_btn_id,
            afterButtonId: afterId
        });
        jQuery("#" + subgrid_id).navButtonAdd("#" + subgrid_id + "_toppager_left", {
            caption: col_text,
            title: col_title,
            buttonicon: "ui-icon-columns",
            buttonicon_p: (col_icon) ? 'uigrid-col-btn col-icon-only' : 'uigrid-col-btn',
            onClickButton: function () {
                jQuery("#" + subgrid_id).jqGrid('columnChooser', {
                    'classname': 'grid-columns-picker',
                    'dialog_opts': {
                        modal: true,
                        minWidth: 460
                    },
                    'msel_opts': {
                        'autoOpen': true,
                        'checkAllText': (js_lang_label.GENERIC_CHECK_ALL ? js_lang_label.GENERIC_CHECK_ALL : "Check all"),
                        'uncheckAllText': (js_lang_label.GENERIC_UNCHECK_ALL ? js_lang_label.GENERIC_UNCHECK_ALL : "Uncheck all"),
                        'noneSelectedText': (js_lang_label.GENERIC_GRID_SELECT_COLUMNS ? js_lang_label.GENERIC_GRID_SELECT_COLUMNS : "Select Columns"),
                        'selectedText': "# " + (js_lang_label.GENERIC_SELECTED ? js_lang_label.GENERIC_SELECTED : "Selected"),
                        'filterPlaceholder': (js_lang_label.GENERIC_GRID_SEARCH_HERE ? js_lang_label.GENERIC_GRID_SEARCH_HERE : "Search here"),
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
                        if (perm) {
                            setColumnsChoosen(el_grid_settings.enc_location + '_sg_cs', subgrid_id);
                            reloadListGrid(subgrid_id);
                        }
                    }
                });
            },
            position: "last",
            id: sub_js_assign_btn_id + "_top",
            afterButtonId: (afterId) ? afterId + "_top" : ""
        });
    }
    var createExportButton = function (afterId, label_arr) {
        var exp_icon, exp_text = '', exp_title;
        exp_icon = (el_theme_settings.grid_sub_icons_export || (label_arr['icon_only'] == "Yes")) ? true : false;
        if (!exp_icon) {
            exp_text = (label_arr['text']) ? label_arr['text'] : js_lang_label.GENERIC_GRID_EXPORT;
        }
        exp_title = (label_arr['title']) ? label_arr['title'] : js_lang_label.GENERIC_GRID_EXPORT;

        sub_js_assign_btn_id = sub_grid_button_ids.export;
        var export_html = "<div class='data-export-main'>";
        export_html += "<div class='export-row'>\n\
                            <div class='export-left'><strong>" + js_lang_label.GENERIC_GRID_EXPORT_MODE + ":</strong></div>\n\
                            <div class='export-right'>\n\
                                <input type='radio' class='regular-radio export-mode-selected' name='export_mode' value='selected' id='export_mode_selected' checked=true><label for='export_mode_selected'>&nbsp;</label>\n\
                                <label for='export_mode_selected' class='export-label-modes'>" + js_lang_label.GENERIC_GRID_SELECTED + "</label>&nbsp;&nbsp;\n\
                                <input type='radio' class='regular-radio export-mode-all' name='export_mode' value='all' id='export_mode_all'><label for='export_mode_all'>&nbsp;</label>\n\
                                <label for='export_mode_all' class='export-label-modes'>" + js_lang_label.GENERIC_GRID_ALL + "</label>\n\
                            </div>\n\
                        </div>";
        export_html += "<div class='export-row' id='export_columns_div'>\n\
                            <div class='export-left'><strong>" + js_lang_label.GENERIC_GRID_SELECT_COLUMNS + ":</strong></div>\n\
                            <div class='export-right'>" + getColumnsDropDown(subgrid_id, "export_columns_list", "export_columns_list", '', 'multiple') + " </div>\n\
                        </div>";
        export_html += "<div class='export-row'>\n\
                            <div class='export-left'><strong>" + js_lang_label.GENERIC_GRID_EXPORT_TO + ":</strong></div>\n\
                            <div class='export-right'>\n\
                                <input type='radio' class='regular-radio export-type-csv' name='export_type' value='csv' id='export_type_csv' checked=true><label for='export_type_csv'>&nbsp;</label>\n\
                                <label for='export_type_csv' class='export-label-modes' title='" + js_lang_label.GENERIC_GRID_EXPORT_TO_CSV + "'><i class='fa fa-file-excel-o fa-2x icon-csv'></i></label>\n\
                                <input type='radio' class='regular-radio export-type-pdf' name='export_type' value='pdf' id='export_type_pdf'><label for='export_type_pdf'>&nbsp;</label>\n\
                                <label for='export_type_pdf' class='export-label-modes' title='" + js_lang_label.GENERIC_GRID_EXPORT_TO_PDF + "'><i class='fa fa-file-pdf-o fa-2x icon-pdf'></i></label>\n\
                            </div>\n\
                            <div class='clear'></div>\n\
                        </div>";
        export_html += "<div class='export-row' id='orientation_columns_div' style='display:none'>\n\
                            <div class='export-left'><strong>" + (js_lang_label.GENERIC_GRID_PDF_ORIENTATION ? js_lang_label.GENERIC_GRID_PDF_ORIENTATION : "PDF Orientation") + ":</strong></div>\n\
                            <div class='export-right'>\n\
                                <input type='radio' class='regular-radio export-type-pdf' name='orientation_type' value='portrait' id='orientation_type_portrait' checked=true><label for='orientation_type_portrait'>&nbsp;</label>\n\
                                <label for='orientation_type_portrait' class='export-label-modes'>" + (js_lang_label.GENERIC_GRID_PORTRAIT ? js_lang_label.GENERIC_GRID_PORTRAIT : "Portrait") + "</label>\n\
                                <input type='radio' class='regular-radio orientation-type-landscape' name='orientation_type' value='landscape' id='orientation_type_landscape'><label for='orientation_type_landscape'>&nbsp;</label>\n\
                                <label for='orientation_type_landscape' class='export-label-modes'>" + (js_lang_label.GENERIC_GRID_LANDSCAPE ? js_lang_label.GENERIC_GRID_LANDSCAPE : "Landscape") + "</label>\n\
                            </div>\n\
                            <div class='clear'></div>\n\
                        </div>";
        export_html += "<div class='clear'></div>";
        export_html += "</div>";

        var selected_btn_title = (js_lang_label.GENERIC_GRID_EXPORT_SELECTED_RECORDS) ? js_lang_label.GENERIC_GRID_EXPORT_SELECTED_RECORDS : "Export selected records";
        jQuery("#" + subgrid_id).navButtonAdd('#' + sub_pager_id, {
            caption: exp_text,
            title: exp_title,
            buttonicon: 'ui-icon-export',
            buttonicon_p: (exp_icon) ? 'uigrid-export-btn export-icon-only' : 'uigrid-export-btn',
            onClickButton: function () {
                var export_elem = '<div />';
                var export_btns = [
                    {
                        text: js_lang_label.GENERIC_GRID_EXPORT_ALL + ' ' + sub_total_rows + ' ' + js_lang_label.GENERIC_GRID_RECORDS,
                        id: "btn_all",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            exportData(subgrid_id, 'all', el_subgrid_settings.export_url);
                        }
                    },
                    {
                        text: js_lang_label.GENERIC_GRID_EXPORT_CURRENT_PAGE_RECORDS,
                        id: "btn_page",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            exportData(subgrid_id, 'thispage', el_subgrid_settings.export_url);
                        }
                    }
                ];
                if ($('#' + subgrid_id).jqGrid("getGridParam", "listview") != 'grid' && $.isArray($('#' + subgrid_id).getGridParam('selarrrow')) && $('#' + subgrid_id).getGridParam('selarrrow').length > 0) {
                    export_btns.push({
                        text: selected_btn_title,
                        id: "btn_selected",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            exportData(subgrid_id, 'selected', el_subgrid_settings.export_url);
                        }
                    });
                }
                $(export_elem).attr("id", "exportmod_" + subgrid_id).html(export_html).dialog({
                    title: exp_title,
                    //height: 195,
                    width: 600,
                    resize: true,
                    modal: true,
                    buttons: export_btns,
                    "close": function () {
                        $(this).dialog("destroy").remove();
                    }
                });
                $("#export_columns_list").multiselect({
                    'minWidth': 300,
                    'checkAllText': (js_lang_label.GENERIC_CHECK_ALL ? js_lang_label.GENERIC_CHECK_ALL : "Check all"),
                    'uncheckAllText': (js_lang_label.GENERIC_UNCHECK_ALL ? js_lang_label.GENERIC_UNCHECK_ALL : "Uncheck all"),
                    'noneSelectedText': (js_lang_label.GENERIC_GRID_SELECT_COLUMNS ? js_lang_label.GENERIC_GRID_SELECT_COLUMNS : "Select Columns"),
                    'selectedText': "# " + (js_lang_label.GENERIC_SELECTED ? js_lang_label.GENERIC_SELECTED : "Selected")
                }).multiselectfilter({placeholder: js_lang_label.GENERIC_GRID_SEARCH_HERE});
            },
            position: "last",
            id: sub_js_assign_btn_id,
            afterButtonId: afterId
        })
        jQuery("#" + subgrid_id).navButtonAdd('#' + subgrid_id + '_toppager_left', {
            caption: exp_text,
            title: exp_title,
            buttonicon: 'ui-icon-export',
            buttonicon_p: (exp_icon) ? 'uigrid-export-btn export-icon-only' : 'uigrid-export-btn',
            onClickButton: function () {
                var export_elem = '<div />';
                var export_btns = [
                    {
                        text: js_lang_label.GENERIC_GRID_EXPORT_ALL + ' ' + sub_total_rows + ' ' + js_lang_label.GENERIC_GRID_RECORDS,
                        id: "btn_all",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            exportData(subgrid_id, 'All', el_subgrid_settings.export_url);
                        }
                    },
                    {
                        text: js_lang_label.GENERIC_GRID_EXPORT_CURRENT_PAGE_RECORDS,
                        id: "btn_page",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            exportData(subgrid_id, 'thispage', el_subgrid_settings.export_url);
                        }
                    }
                ];
                if ($('#' + subgrid_id).jqGrid("getGridParam", "listview") != 'grid' && $.isArray($('#' + subgrid_id).getGridParam('selarrrow')) && $('#' + subgrid_id).getGridParam('selarrrow').length > 0) {
                    export_btns.push({
                        text: selected_btn_title,
                        id: "btn_selected",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            exportData(subgrid_id, 'selected', el_subgrid_settings.export_url);
                        }
                    });
                }
                $(export_elem).attr("id", "exportmod_" + subgrid_id).html(export_html).dialog({
                    title: exp_title,
                    //height: 195,
                    width: 600,
                    resize: true,
                    modal: true,
                    buttons: export_btns,
                    "close": function () {
                        $(this).dialog("destroy").remove();
                    }
                });
                $("#export_columns_list").multiselect({
                    'minWidth': 300,
                    'checkAllText': (js_lang_label.GENERIC_CHECK_ALL ? js_lang_label.GENERIC_CHECK_ALL : "Check all"),
                    'uncheckAllText': (js_lang_label.GENERIC_UNCHECK_ALL ? js_lang_label.GENERIC_UNCHECK_ALL : "Uncheck all"),
                    'noneSelectedText': (js_lang_label.GENERIC_GRID_SELECT_COLUMNS ? js_lang_label.GENERIC_GRID_SELECT_COLUMNS : "Select Columns"),
                    'selectedText': "# " + (js_lang_label.GENERIC_SELECTED ? js_lang_label.GENERIC_SELECTED : "Selected"),
                }).multiselectfilter({placeholder: js_lang_label.GENERIC_GRID_SEARCH_HERE});
            },
            position: "last",
            id: sub_js_assign_btn_id + "_top",
            afterButtonId: (afterId) ? afterId + "_top" : ""
        });
    }
    var createPrintButton = function (afterId, label_arr) {
        var prnt_icon, prnt_text = '', prnt_title;
        prnt_icon = (el_theme_settings.grid_sub_icons_print || (label_arr['icon_only'] == "Yes")) ? true : false;
        if (!prnt_icon) {
            prnt_text = (label_arr['text']) ? label_arr['text'] : js_lang_label.GENERIC_GRID_PRINT;
        }
        prnt_title = (label_arr['title']) ? label_arr['title'] : js_lang_label.GENERIC_GRID_PRINT;

        sub_js_assign_btn_id = sub_grid_button_ids.print;
        var print_html = "<div class='data-print-main'>";
        print_html += "<div class='print-row'>\n\
                            <strong>" + js_lang_label.GENERIC_PLEASE_CHOOSE_THE_BELOW_RECORDS_SELECTION_FOR_PRINTING + "</strong>\n\
                        </div>";
        print_html += "</div>";

        jQuery("#" + subgrid_id).navButtonAdd('#' + sub_pager_id, {
            caption: prnt_text,
            title: prnt_title,
            buttonicon: 'ui-icon-print',
            buttonicon_p: (prnt_icon) ? 'uigrid-print-btn print-icon-only' : 'uigrid-print-btn',
            onClickButton: function () {
                var print_elem = '<div />';
                var print_btns = [
                    {
                        text: js_lang_label.GENERIC_GRID_PRINT_ALL + ' ' + sub_total_rows + ' ' + js_lang_label.GENERIC_GRID_RECORDS,
                        id: "btn_all",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            printData(subgrid_id, 'all', el_sub_grid_settings.print_url, {});
                        }
                    },
                    {
                        text: js_lang_label.GENERIC_GRID_PRINT_CURRENT_PAGE_RECORDS,
                        id: "btn_page",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            printData(subgrid_id, 'thispage', el_sub_grid_settings.print_url, {});
                        }
                    }
                ];
                if ($('#' + subgrid_id).jqGrid("getGridParam", "listview") != 'grid' && $.isArray($('#' + subgrid_id).getGridParam('selarrrow')) && $('#' + subgrid_id).getGridParam('selarrrow').length > 0) {
                    print_btns.push({
                        text: js_lang_label.GENERIC_GRID_PRINT_SELECTED_RECORDS,
                        id: "btn_selected",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            printData(subgrid_id, 'selected', el_sub_grid_settings.print_url, {});
                        }
                    });
                }
                $(print_elem).attr("id", "printmod_" + subgrid_id).html(print_html).dialog({
                    title: prnt_title,
                    //height: 195,
                    width: 600,
                    resize: true,
                    modal: true,
                    buttons: print_btns,
                    "close": function () {
                        $(this).dialog("destroy").remove();
                    }
                });
            },
            position: "last",
            id: sub_js_assign_btn_id,
            afterButtonId: afterId
        })
        jQuery("#" + subgrid_id).navButtonAdd('#' + subgrid_id + '_toppager_left', {
            caption: prnt_text,
            title: prnt_title,
            buttonicon: 'ui-icon-print',
            buttonicon_p: (prnt_icon) ? 'uigrid-print-btn print-icon-only' : 'uigrid-print-btn',
            onClickButton: function () {
                var print_elem = '<div />';
                var print_btns = [
                    {
                        text: js_lang_label.GENERIC_GRID_PRINT_ALL + ' ' + sub_total_rows + ' ' + js_lang_label.GENERIC_GRID_RECORDS,
                        id: "btn_all",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            printData(subgrid_id, 'all', el_sub_grid_settings.print_url, {});
                        }
                    },
                    {
                        text: js_lang_label.GENERIC_GRID_PRINT_CURRENT_PAGE_RECORDS,
                        id: "btn_page",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            printData(subgrid_id, 'thispage', el_sub_grid_settings.print_url, {});
                        }
                    }
                ];
                if ($('#' + subgrid_id).jqGrid("getGridParam", "listview") != 'grid' && $.isArray($('#' + subgrid_id).getGridParam('selarrrow')) && $('#' + subgrid_id).getGridParam('selarrrow').length > 0) {
                    print_btns.push({
                        text: js_lang_label.GENERIC_GRID_PRINT_SELECTED_RECORDS,
                        id: "btn_selected",
                        "class": 'fm-button ui-state-default ui-corner-all',
                        click: function () {
                            printData(subgrid_id, 'selected', el_sub_grid_settings.print_url, {});
                        }
                    });
                }
                $(print_elem).attr("id", "printmod_" + subgrid_id).html(print_html).dialog({
                    title: prnt_title,
                    //height: 195,
                    width: 600,
                    resize: true,
                    modal: true,
                    buttons: print_btns,
                    "close": function () {
                        $(this).dialog("destroy").remove();
                    }
                });
            },
            position: "last",
            id: sub_js_assign_btn_id + "_top",
            afterButtonId: (afterId) ? afterId + "_top" : ""
        });
    }
    var createInlineAddSaveDelBtn = function () {
        jQuery("#" + subgrid_id).navButtonAdd('#' + sub_pager_id, {
            caption: "",
            title: js_lang_label.GENERIC_GRID_ADD_NEW,
            buttonicon: 'icon16 iconic-icon-plus-alt',
            buttonicon_p: "uigrid-inlineadd-btn",
            buttonname: "addnew",
            onClickButton: function (e) {
                addNewInlineRecord(subgrid_id);
            },
            id: 'inlineadd_' + subgrid_id,
            position: "last"
        });

        jQuery("#" + subgrid_id).navButtonAdd("#" + subgrid_id + "_toppager_left", {
            caption: "",
            title: js_lang_label.GENERIC_GRID_ADD_NEW,
            buttonicon: 'icon16 iconic-icon-plus-alt',
            buttonicon_p: "uigrid-inlineadd-btn",
            buttonname: "addnew",
            onClickButton: function (e) {
                addNewInlineRecord(subgrid_id);
            },
            id: 'inlineadd_' + subgrid_id + '_top',
            position: "last"
        });
        jQuery("#" + subgrid_id).navButtonAdd('#' + sub_pager_id, {
            caption: "",
            title: js_lang_label.GENERIC_GRID_SAVE_ALL,
            buttonicon: 'icon16 iconic-icon-check-alt',
            buttonicon_p: "uigrid-inlinesave-btn",
            buttonname: "saveall",
            onClickButton: function (e) {
                saveAllInlineRecords(subgrid_id);
            },
            id: 'saveall_' + subgrid_id,
            position: "last"
        });

        jQuery("#" + subgrid_id).navButtonAdd("#" + subgrid_id + "_toppager_left", {
            caption: "",
            title: js_lang_label.GENERIC_GRID_SAVE_ALL,
            buttonicon: 'icon16 iconic-icon-check-alt',
            buttonicon_p: "uigrid-inlinesave-btn",
            buttonname: "saveall",
            onClickButton: function (e) {
                saveAllInlineRecords(subgrid_id);
            },
            id: 'saveall_' + subgrid_id + '_top',
            position: "last"
        });
        jQuery("#" + subgrid_id).navButtonAdd('#' + sub_pager_id, {
            caption: "",
            title: js_lang_label.GENERIC_GRID_CANCEL_ALL,
            buttonicon: 'icon16 icomoon-icon-cancel-2',
            buttonicon_p: "uigrid-cancelall-btn",
            buttonname: "cancelall",
            onClickButton: function (e) {
                cancelAllInlineRecords(subgrid_id);
            },
            id: 'cancelall_' + subgrid_id,
            position: "last"
        });

        jQuery("#" + subgrid_id).navButtonAdd("#" + subgrid_id + "_toppager_left", {
            caption: "",
            title: js_lang_label.GENERIC_GRID_CANCEL_ALL,
            buttonicon: 'icon16 icomoon-icon-cancel-2',
            buttonicon_p: "uigrid-cancelall-btn",
            buttonname: "cancelall",
            onClickButton: function (e) {
                cancelAllInlineRecords(subgrid_id);
            },
            id: 'cancelall_' + subgrid_id + '_top',
            position: "last"
        });
    }

    if (sub_grid_button_arr.length > 0) {
        var sub_ord_dsr_arr = [], sub_btn_dsr_arr = {}, btn_name;
        for (var b = 0; b < sub_grid_button_arr.length; b++) {
            btn_name = sub_grid_button_arr[b]['name'];
            if ($.inArray(btn_name, ["del", "search", "refresh"]) != -1) {
                sub_ord_dsr_arr.push(btn_name);
                sub_btn_dsr_arr[btn_name] = sub_grid_button_arr[b];
            }
            if (sub_ord_dsr_arr.length >= 3) {
                break;
            }
        }
        createDelSearchRefreshBtn(sub_ord_dsr_arr, sub_btn_dsr_arr);
        for (var b = 0; b < sub_grid_button_arr.length; b++) {
            if (sub_grid_button_arr[b]['type'] == "custom") {
                sub_js_assign_btn_id = createCustomGridButton(sub_grid_button_arr[b], subgrid_id, sub_pager_id, sub_js_assign_btn_id);
            } else {
                btn_name = sub_grid_button_arr[b]['name'];
                if (btn_name && btn_name.substring(0, 6) == "status") {
                    if (sub_status_permit) {
                        createStatusButton(sub_js_assign_btn_id);
                    }
                } else {
                    switch (btn_name) {
                        case "del":
                            if (sub_del_permit) {
                                sub_js_assign_btn_id = sub_grid_button_ids.del;
                            }
                            break;
                        case "search":
                            if (sub_adv_search_permit) {
                                sub_js_assign_btn_id = sub_grid_button_ids.search;
                            }
                            break;
                        case "refresh":
                            if (sub_refresh_permit) {
                                sub_js_assign_btn_id = sub_grid_button_ids.refresh;
                            }
                            break;
                        case "add":
                            if (sub_add_permit) {
                                createAddButton(sub_js_assign_btn_id, sub_grid_button_arr[b]);
                            }
                            break;
                        case "columns":
                            if (sub_columns_permit) {
                                createColumnsButton(sub_js_assign_btn_id, sub_grid_button_arr[b]);
                            }
                            break;
                        case "export":
                            if (sub_export_permit) {
                                createExportButton(sub_js_assign_btn_id, sub_grid_button_arr[b]);
                            }
                            break;
                        case "print":
                            if (sub_print_permit) {
                                createPrintButton(sub_js_assign_btn_id, sub_grid_button_arr[b]);
                            }
                    }
                }
            }
        }
    } else {
        createDelSearchRefreshBtn([], {});
        if (sub_add_permit) {
            createAddButton(sub_js_assign_btn_id, {});
        }
        if (sub_del_permit) {
            sub_js_assign_btn_id = sub_grid_button_ids.del;
        }
        if (sub_status_permit) {
            createStatusButton(sub_js_assign_btn_id);
        }
        if (sub_columns_permit) {
            createColumnsButton(sub_js_assign_btn_id, {});
        }
        if (sub_export_permit) {
            createExportButton(sub_js_assign_btn_id, {});
        }
        if (sub_print_permit) {
            createPrintButton(sub_js_assign_btn_id, {});
        }
    }
    if (sub_inline_add_permit) {
        createInlineAddSaveDelBtn();
    }

    $(document).off("click", ".expand-nesview");
    $(document).on("click", ".expand-nesview", function () {
        var curr_alias = $(this).attr("aria-alias");
        var curr_row_id = $(this).attr("aria-rowid");
        if (el_subgrid_settings.grid_nesgrid_alias && curr_alias == el_subgrid_settings.grid_nesgrid_alias) {
            el_subgrid_settings.grid_nesgrid_alias = curr_alias;
            $("#" + subgrid_id).jqGrid('toggleSubGridRow', curr_row_id);
        } else {
            el_subgrid_settings.grid_nesgrid_alias = curr_alias;
            $("#" + subgrid_id).jqGrid('toggleSubGridRow', curr_row_id);
            setTimeout(function () {
                $("#" + subgrid_id).jqGrid('expandSubGridRow', curr_row_id);
            }, 100)
        }
    });
}
//related to subgrid detail view
function initSubGridDetailView() {
    if (!view_js_col_model_json || !el_subview_settings.permit_edit_btn) {
        return;
    }
    var view_settings_obj = {};
    for (i in view_js_col_model_json) {
        var v_editable = view_js_col_model_json[i]['editable'];
        var v_type = view_js_col_model_json[i]['type'];
        var v_edittype = view_js_col_model_json[i]['edittype'];
        var v_name = view_js_col_model_json[i]['htmlID'];
        view_settings_obj[v_name] = view_js_col_model_json[i];
        if (v_type == "rating_master") {
            displayAddListRatingProperties(v_name, view_settings_obj[v_name]);
        }
        if (!v_editable) {
            continue;
        }

        switch (v_type) {
            case 'checkboxes' :
                makeAddListEditableDropdown(v_name, view_settings_obj);
                break;
            case 'code_markup_field' :
                makeAddListEditableTextArea(v_name, view_settings_obj);
                break;
            case 'date' :
                makeAddListEditableTextBox(v_name, view_settings_obj);
                break;
            case 'date_and_time' :
                makeAddListEditableTextBox(v_name, view_settings_obj);
                break;
            case 'dropdown' :
                makeAddListEditableDropdown(v_name, view_settings_obj);
                break;
            case 'file' :
                makeAddListEditableTextBox(v_name, view_settings_obj);
                break;
            case 'google_maps' :
                makeAddListEditableTextArea(v_name, view_settings_obj);
                break;
            case 'multi_select_dropdown' :
                makeAddListEditableDropdown(v_name, view_settings_obj);
                break;
            case 'password' :
                makeAddListEditablePassword(v_name, view_settings_obj);
                break;
            case 'phone_number' :
                makeAddListEditableTextBox(v_name, view_settings_obj);
                break;
            case 'radio_buttons' :
                makeAddListEditableDropdown(v_name, view_settings_obj);
                break;
            case 'textarea' :
                makeAddListEditableTextArea(v_name, view_settings_obj);
                break;
            case 'time' :
                makeAddListEditableTextBox(v_name, view_settings_obj);
                break;
            case 'color_picker':
                makeAddListEditableTextBox(v_name, view_settings_obj);
                break;
            case 'autocomplete' :
                makeAddListEditableTextBox(v_name, view_settings_obj);
                break;
            case 'rating_master' :
                makeAddListEditableTextBox(v_name, view_settings_obj);
                break;
            case 'wysiwyg' :
                makeAddListEditableTextArea(v_name, view_settings_obj);
                break;
            default :
                //for textbox
                makeAddListEditableTextBox(v_name, view_settings_obj);
                break;
        }
    }
}
//related to x-editable forms
function saveViewInlineEdit(name, value, id, extra) {
    var obj_prop = view_js_col_model_json[name];
    var data = {
        "name": obj_prop.name,
        "value": value,
        "id": id
    };
    if (typeof extra == 'object') {
        data = $.extend({}, data, extra);
    }
    var options = {
        "url": el_subview_settings.edit_page_url,
        "data": data,
        success: function (obj, config) {
            var res_arr = parseJSONString(obj);
            if (res_arr && res_arr.success == 'false') {
                var $jq_errmsg = js_lang_label.GENERIC_GRID_ERROR_IN_UPDATION;
                if (res_arr.message != "") {
                    $jq_errmsg = res_arr.message;
                }
                $('#' + name).editable('option', 'value', $('#' + name).attr("aria-prev-value"));
                $('#' + name).editable('show');
                gridReportMessage(false, $jq_errmsg);
            } else if (res_arr.success == '3' || res_arr.success == '4') {
                if (isRedirectEqualHash(res_arr.red_hash)) {
                    window.location.hash = res_arr.red_hash;
                    window.location.reload();
                } else {
                    window.location.hash = res_arr.red_hash;
                }
            } else if (res_arr.success == '5') {
                window.location.href = res_arr.red_hash;
            } else {
                switch (obj_prop.type) {
                    case "multi_select_dropdown":
                    case "checkboxes":
                        if ($('#' + name).attr("data-value") != '') {
                            $('#' + name).html($('#' + name).attr("data-value"));
                        }
                        break;
                    case "autocomplete":
                        var par_obj = obj_prop.editoptions.token.params;
                        if (view_token_pre_populates[name].length > 0) {
                            $('#' + name).html($('#' + name).attr("data-value"));
                        }
                        par_obj.prePopulate = view_token_pre_populates[name];
                    case 'password':
                        $('#' + name).html("*****");
                        break;
                    case "file":
                        view_js_col_model_json[name]['dbval'] = value;
                        displayAdminListFlyImage(name, obj_prop.editoptions.uploadify, res_arr);
                        break;
                    case "rating_master":
                        $("#rshow_" + obj_prop.htmlID).raty('set', {score: value});
                        $("#rscore_" + obj_prop.htmlID).text(value);
                        $("#" + obj_prop.htmlID).html('<span class="icon16 icomoon-icon-pencil-5"><b>Edit</b></span>');
                        break;
                    default :
                        $('#' + name).attr("aria-prev-value", value);
                        break;
                }
            }
        }
    };
    $('#' + name).editable("submit", options);
}
//related to nested grid listing
function initNesGridListing() {
    var nesgrid_id = el_nesgrid_settings.table_id, nes_pager_id = el_nesgrid_settings.pager_id;
    var nes_js_prev_key = '', nes_js_assign_btn_id = '', nes_js_next_btn_id = '', nes_jsave = '', nes_saved_obj = '';
    var nes_js_col_name_arr = [], nes_js_sort_count = 0, nes_jrow = 0, nes_jcol = 0;
    var nes_js_before_req = true, nes_show_paging_var = true;

    var nes_row_numbers = (el_nesgrid_settings.inline_add == "Yes") ? true : false;
    var nes_pager_active = (el_nesgrid_settings.hide_paging_btn == "Yes") ? false : true;

    var nes_add_permit = (el_nesgrid_settings.hide_add_btn == '1' && el_nesgrid_settings.permit_add_btn == "1" && el_nesgrid_settings.advanced_grid == '1') ? true : false;
    var nes_del_permit = (el_nesgrid_settings.hide_del_btn == '1' && el_nesgrid_settings.permit_del_btn == '1' && el_nesgrid_settings.advanced_grid == '1') ? true : false;
    var nes_status_permit = (el_nesgrid_settings.hide_status_btn == '1' && el_nesgrid_settings.permit_edit_btn == '1' && el_nesgrid_settings.advanced_grid == '1') ? true : false;

    var nes_adv_search_permit = (el_nesgrid_settings.hide_advance_search == 'Yes' || el_nesgrid_settings.advanced_grid != '1') ? false : true;
    var nes_refresh_permit = (el_nesgrid_settings.hide_refresh_btn == 'Yes' || el_nesgrid_settings.advanced_grid != '1') ? false : true;

    var nes_inline_add_permit = (el_nesgrid_settings.inline_add == "Yes" && el_nesgrid_settings.permit_add_btn == "1" && el_nesgrid_settings.advanced_grid == '1') ? true : false;
    var nes_search_tool_permit = (el_nesgrid_settings.hide_search_tool == "Yes") ? false : true;

    var nes_global_filter = (el_nesgrid_settings.global_filter == "Yes") ? true : false;
    var nes_top_filter_arr = $.isPlainObject(el_nesgrid_settings.top_filter) ? el_nesgrid_settings.top_filter : [];
    var nes_action_callbacks = $.isPlainObject(el_nesgrid_settings['callbacks']) ? el_nesgrid_settings['callbacks'] : {};
    var nes_list_message_arr = $.isPlainObject(el_nesgrid_settings['message_arr']) ? el_nesgrid_settings['message_arr'] : {};
    
    var nes_viewtemplate = '#layout_view_' + nesgrid_id;
    var nes_gridtemplate = '#layout_grid' + nesgrid_id;
    
    var nes_grid_button_arr = ($.isArray(el_nesgrid_settings.buttons_arr)) ? el_nesgrid_settings.buttons_arr : [];
    var nes_grid_button_ids = {
        "add": "add_" + nesgrid_id,
        "del": "del_" + nesgrid_id,
        "search": "search_" + nesgrid_id,
        "refresh": "refresh_" + nesgrid_id
    }

    if (el_general_settings.mobile_platform) {
        //el_subgrid_settings.auto_width = "Yes";
    }

    if (typeof executeBeforeGridInit == "function") {
        executeBeforeGridInit(el_nesgrid_settings['module_name'], "nes");
    }
    if (nes_action_callbacks['before_grid_init'] && $.isFunction(window[nes_action_callbacks['before_grid_init']])) {
        window[nes_action_callbacks['before_grid_init']](el_nesgrid_settings, nes_js_col_model_json, nes_js_col_name_json);
    }

    for (var i in nes_js_col_name_json) {
        nes_js_col_name_arr.push(nes_js_col_name_json[i]['label']);
    }

    if (!nes_add_permit && !nes_del_permit && !nes_status_permit && !nes_adv_search_permit &&  !nes_refresh_permit && !nes_inline_add_permit && 
            !nes_search_tool_permit && !nes_global_filter && !($.isArray(nes_top_filter_arr) && nes_top_filter_arr.length > 0) &&
            !($(nes_viewtemplate).length || $(nes_gridtemplate).length) && el_tpl_settings.grid_top_menu == 'N') {
        nes_show_paging_var = false;
    }
    var listview = findGridViewParam(window.location.hash, nesgrid_id, el_nesgrid_settings.listview, el_grid_settings.enc_location + '_ng_gv');
    setHideColumnSettings(nesgrid_id, nes_js_col_model_json, nes_top_filter_arr);
    getColumnsWidth(el_grid_settings.enc_location + '_ng_cw', nesgrid_id, nes_js_col_model_json);

    jQuery("#" + nesgrid_id).jqGrid({
        url: el_nesgrid_settings.listing_url,
        editurl: el_nesgrid_settings.edit_page_url,
        mtype: 'POST',
        datatype: "json",
        colNames: nes_js_col_name_arr,
        colModel: nes_js_col_model_json,
        page: 1,
        pgbuttons: nes_pager_active,
        pginput: nes_pager_active,
        pgnumbers: (el_theme_settings.grid_sub_pgnumbers) ? true : false, //custom
        pgnumlimit: (el_general_settings.mobile_platform) ? 2 : parseInt(el_theme_settings.grid_sub_pgnumlimit), //custom
        pagingpos: el_theme_settings.grid_sub_pagingpos, //custom
        rowNum: (el_nesgrid_settings.hide_paging_btn == "Yes") ? 1000000 : parseInt(el_tpl_settings.grid_rec_limit),
        rowList: (nes_pager_active) ? pager_row_list : [],
        sortname: el_nesgrid_settings.default_sort,
        sortorder: el_nesgrid_settings.sort_order,
        altRows: true,
        altclass: 'evenRow',
        multiselectWidth: 30,
        multiselect: (el_nesgrid_settings.hide_multi_select == "Yes") ? false : true,
        multiboxonly: true,
        hiderecords: el_nesgrid_settings.admin_rec_arr,
        viewrecords: true,
        norecmsg: js_lang_label.GENERIC_GRID_NO_RECORDS_FOUND,
        caption: false,
        hidegrid: false,
        listview: listview, //custom 
        viewtemplate: '#layout_view_' + nesgrid_id, //custom
        gridtemplate: '#layout_grid' + nesgrid_id, //custom
        viewCallback: function (id, type) {
            //custom
            reloadListGrid(nesgrid_id, null, 2, el_nesgrid_settings);
            setLocalStore(el_grid_settings.enc_location + '_ng_gv', type);
        },
        listtags: ['{', '}'], //custom
        inlineadd: (el_nesgrid_settings.inline_add == "Yes") ? true : false, //custom
        inlinerecpos: (el_nesgrid_settings.rec_position == "Bottom") ? true : false, //custom
        isSubMod: 2, //custom
        curModule: el_nesgrid_settings.add_page_url, //custom
        parModule: el_nesgrid_settings.par_module, //custom
        parData: el_nesgrid_settings.par_data, //custom
        parField: el_nesgrid_settings.par_field, //custom
        parType: el_nesgrid_settings.par_type, //custom
        extraHash: el_nesgrid_settings.extra_hstr, //custom
        ratingAllow: (el_nesgrid_settings.rating_allow == "Yes") ? true : false, //custom
        pager: (el_tpl_settings.grid_bot_menu == 'Y') ? nes_pager_id : "",
        toppager: (el_tpl_settings.grid_top_menu == 'Y') ? true : false,
        toppaging: (el_tpl_settings.grid_top_menu == 'Y') ? true : false, //custom
        showpaging: nes_show_paging_var, //custom
        cellurl: el_nesgrid_settings.edit_page_url,
        cellsubmit: 'remote',
        sortable: {
            update: function (permutation) {
                setColumnsPosition(el_grid_settings.enc_location + '_ng_cp', permutation, nesgrid_id, nes_js_col_model_json);
            }
        },
        searchGrid: {
            multipleSearch: true,
            searchToolbar: (nes_search_tool_permit) ? true : false,
            globalFilter: (nes_global_filter) ? true : false,
            topFilters: nes_top_filter_arr,
            topDataInit: triggerTopFilterEvent
        },
        afterSearchToggle: function (id) {
            //custom
            if ($("#hbox_" + id + "_jqgrid").find(".ui-search-toolbar").is(":hidden")) {
                $("#listsearch_" + id + "_top").removeClass("active");
            }
            resizeGridWidth();
        },
        height: '100%',
        autowidth: (el_nesgrid_settings.auto_width == "No") ? false : true,
        _autowidth: (el_nesgrid_settings.auto_width == "No") ? false : true,
        shrinkToFit: (el_nesgrid_settings.auto_width == "No") ? false : true,
        fixed: true,
        //rownumbers: nes_row_numbers,
        multiSort: (el_tpl_settings.grid_multiple_sorting) ? true : false,
        grouping: (el_nesgrid_settings.grouping == 'Yes') ? true : false,
        groupingView: {
            groupField: ($.isArray(el_nesgrid_settings.group_attr['field'])) ? el_nesgrid_settings.group_attr['field'] : [],
            groupOrder: ($.isArray(el_nesgrid_settings.group_attr['order'])) ? el_nesgrid_settings.group_attr['order'] : [],
            groupText: ($.isArray(el_nesgrid_settings.group_attr['text'])) ? el_nesgrid_settings.group_attr['text'] : [],
            groupColumnShow: ($.isArray(el_nesgrid_settings.group_attr['column'])) ? el_nesgrid_settings.group_attr['column'] : [],
            groupSummary: ($.isArray(el_nesgrid_settings.group_attr['summary'])) ? el_nesgrid_settings.group_attr['summary'] : [],
            showSummaryOnHide: ($.isArray(el_nesgrid_settings.group_attr['summary'])) ? el_nesgrid_settings.group_attr['summary'] : [],
            groupCollapse: false,
            groupDataSorted: true
        },
        footerrow: (el_nesgrid_settings.footer_row == 'Yes') ? true : false,
        userDataOnFooter: true,
        beforeRequest: function () {
            if (nes_js_before_req) {
                nes_js_before_req = false;
                getColumnsPosition(el_grid_settings.enc_location + '_ng_cp', nesgrid_id);
            }
        },
        beforeProcessing: function (data) {
            delete el_general_settings.grid_nes_link_model;
            if (data && data.links) {
                el_general_settings.grid_nes_link_model = data.links;
            }
        },
        loadError: function (xhr, status, error) {
            hideGirdLoadingOverlay(el_tpl_settings.main_grid_id);
        },
        loadComplete: function (data) {
            setTimeout(function () {
                hideGirdLoadingOverlay(el_tpl_settings.main_grid_id);
                Project.hide_adaxloading_div();
            }, 2);
            $("#" + el_tpl_settings.main_grid_id + "_messages_html").remove();
            $("#selAllRows").val('false');
            // No Records Message
            noRecordsMessage(nesgrid_id, data);
            // Add new record
            //addNewInlineRecord(subgrid_id);
            // Row colors
            applyGridRowColors(nesgrid_id, data);
            // Rating Events
            //applyRatingEvents(nesgrid_id);
            // Resizing Sub Grid
            resizeSubGridWidth(nesgrid_id);
            // adjust main grid width
            adjustMainGridColumnWidth();
            // fancybox image events
            initializeFancyBoxEvents();
            //set columns widths
            checkColumnsWidth(el_grid_settings.enc_location + '_ng_cw', nesgrid_id);
            if (typeof executeAfterGridLoad == "function") {
                executeAfterGridLoad(el_nesgrid_settings['module_name'], "nested");
            }
            if (nes_action_callbacks['after_data_load'] && $.isFunction(window[nes_action_callbacks['after_data_load']])) {
                window[nes_action_callbacks['after_data_load']](data);
            }
        },
        gridComplete: function () {
            // Resizing Sub Grid
            resizeSubGridWidth(nesgrid_id);
            hideAdminDataCheckBox(nesgrid_id, el_nesgrid_settings.admin_rec_arr);
            getAdminImageTooltip(nesgrid_id);
        },
        ondblClickRow: function (rowid, iRow, iCol, e) {
            var ac = $(e.srcElement).hasClass("add-cell") ? 1 : 0
            var ai = ($(e.srcElement).attr("aria-newrow") == "inline-add-row") ? 1 : 0;
            var bc = ($(e.srcElement).find(".inline-edit-row").length > 0) ? 1 : 0
            var cf = ($(e.srcElement).hasClass(".inline-edit-row")) ? 1 : 0
            var sf = ($(e.srcElement).closest("td[role='gridcell']").hasClass('edit-cell')) ? 1 : 0
            if (ac || ai || bc || cf || sf) {
                e.stopPropagation();
            } else {
                $("#" + el_tpl_settings.main_grid_id).jqGrid('setGridParam', {
                    cellEdit: false
                });
                var $this = $(this);
                $this.jqGrid('setGridParam', {
                    cellEdit: true
                });
                $this.jqGrid('editCell', iRow, iCol, true);
                $this.jqGrid('setGridParam', {
                    cellEdit: false
                });
                e.stopPropagation();
            }
        },
        beforeEditCell: function (rowid, cellName, cellValue, iRow, iCol) {
            restoreBeforeEditedCell(this, nes_jrow, nes_jcol, nes_jsave);
            if ($(".colpick").length) {
                $(".colpick").hide();
            }
        },
        afterEditCell: function (rowid, cellName, cellValue, iRow, iCol) {
            var cellDOM = this.rows[iRow].cells[iCol], oldKeydown;
            var $cellInput = $("#" + iRow + "_" + cellName, cellDOM);
            var events = $._data($cellInput.eq(0), "events"), cselector = $cellInput["selector"];
            var $this = $(this), date_flag = false, colorpicker_flag = false, phone_flag = false;
            if ($cellInput.hasClass("dateOnly")) {
                inlineDateTimePicker(iRow, cellName, 'date');
                var date_flag = true;
            } else if ($cellInput.hasClass("timeOnly")) {
                inlineDateTimePicker(iRow, cellName, 'time');
                var date_flag = true;
            } else if ($cellInput.hasClass("dateTime")) {
                inlineDateTimePicker(iRow, cellName, 'dateTime');
                var date_flag = true;
            } else if ($cellInput.hasClass("colorPicker")) {
                inlineColorPicker(iRow, cellName, 'colorPicker');
                var colorpicker_flag = true;
            } else if ($cellInput.hasClass("phoneNumber")) {
                var phone_flag = true;
            } else if ($cellInput.hasClass("inline-textarea-edit")) {
                var txt = $($cellInput).val();
                txt = txt.replace(/<br>/g, "");
                txt = txt.replace(/<BR>/g, "");
                $($cellInput).val(txt);
            }
            nes_jrow = iRow;
            nes_jcol = iCol;
            nes_jsave = ($.isArray(this.p.savedRow)) ? this.p.savedRow[this.p.savedRow.length - 1].v : "";
            if ($(cellDOM).find("select[role='select']").length) {
                $cellDrop = $(cellDOM).find("select[role='select']");
                $($cellDrop).attr("aria-update-id", rowid);
                nes_saved_obj = this.p.savedRow;
                $($cellDrop).on('change', function (e) {
                    $this.jqGrid('setGridParam', {
                        cellEdit: true
                    });
                    $this.jqGrid('setGridParam', {
                        savedRow: nes_saved_obj
                    });
                    $this.jqGrid('saveCell', iRow, iCol);
                    $this.jqGrid('restoreCell', iRow, iCol, true);
                    $(cellDOM).removeClass("ui-state-highlight");
                    nes_jrow = 0, nes_jcol = 0, nes_jsave = '';
                    nes_saved_obj = $this.jqGrid('getGridParam', 'savedRow');
                });
                var autoChznInterval = setInterval(function () {
                    if ($(cselector).hasClass("chosen-select") && $(cselector + "_chosen").length) {
                        $(cselector + "_chosen").on('keydown', function (e) {
                            if (e.keyCode == 27) {
                                if ($(cselector + "_chosen").find(".chosen-drop").css("left") == "-9999px") {
                                    $this.jqGrid('setGridParam', {
                                        cellEdit: true
                                    });
                                    $this.jqGrid('setGridParam', {
                                        savedRow: nes_saved_obj
                                    });
                                    $this.jqGrid('restoreCell', iRow, iCol, true);
                                    $(cellDOM).removeClass("ui-state-highlight");
                                    nes_jrow = 0, nes_jcol = 0, nes_jsave = '';
                                }
                            }
                        });
                        clearInterval(autoChznInterval);
                    }
                }, 250);
            } else {
                applyInputTextCase($(cellDOM));
                nes_saved_obj = this.p.savedRow;
                setTimeout(function () {
                    if (events && events.keydown && events.keydown.length) {
                        $this.jqGrid('setGridParam', {
                            savedRow: nes_saved_obj
                        });
                        oldKeydown = events.keydown[0].handler;
                        $cellInput.unbind('keydown', oldKeydown);
                        $cellInput.bind('keydown', function (e) {
                            $this.jqGrid('setGridParam', {
                                cellEdit: true
                            });
                            $this.jqGrid('setGridParam', {
                                savedRow: nes_saved_obj
                            });
                            if ($cellInput.hasClass("inline-textarea-edit")) {
                                if (e.keyCode === 13) {
                                    if (e.shiftKey) {
                                        e.stopPropagation();
                                    } else {
                                        $this.jqGrid('saveCell', iRow, iCol);
                                        $this.jqGrid('restoreCell', iRow, iCol, true);
                                        $(cellDOM).removeClass("ui-state-highlight");
                                        nes_jrow = 0, nes_jcol = 0, nes_jsave = '';
                                    }
                                } else {
                                    oldKeydown.call(this, e);
                                }
                            } else if ($cellInput.hasClass("colorPicker")) {
                                if (e.keyCode === 9 || e.keyCode === 13 || e.keyCode === 27) {
                                    if ($(".colpick").length) {
                                        //$(".colpick").remove();
                                        $(".colpick").hide();

                                    }
                                }
                                oldKeydown.call(this, e);
                            } else {
                                oldKeydown.call(this, e);
                                $this.jqGrid('setGridParam', {
                                    cellEdit: false
                                });
                            }
                        }).bind('focusout', function (e) {
                            $this.jqGrid('setGridParam', {
                                savedRow: nes_saved_obj
                            });
                            if (date_flag) {
                                if ($(".ui-datepicker").is(":hidden")) {
                                    $this.jqGrid('setGridParam', {
                                        cellEdit: true
                                    });
                                    //$this.jqGrid('saveCell', iRow, iCol);
                                    $this.jqGrid('restoreCell', iRow, iCol, true);
                                    $(cellDOM).removeClass("ui-state-highlight");
                                    nes_jrow = 0, nes_jcol = 0, nes_jsave = '';
                                }
                            } else if (colorpicker_flag) {
                                if ($(".colpick" + "#" + $cellInput.attr("colorpickerid")).is(":hidden")) {
                                    $this.jqGrid('setGridParam', {
                                        cellEdit: true
                                    });
                                    //$this.jqGrid('saveCell', iRow, iCol);
                                    $this.jqGrid('restoreCell', iRow, iCol, true);
                                    $(cellDOM).removeClass("ui-state-highlight");
                                    nes_jrow = 0, nes_jcol = 0, nes_jsave = '';
                                }
                            } else {
                                var save_flag = true;
                                if (save_flag) {
                                    $this.jqGrid('setGridParam', {
                                        cellEdit: true
                                    });
                                    if (phone_flag == true) {
                                        $this.jqGrid('saveCell', iRow, iCol);
                                    }
                                    $this.jqGrid('restoreCell', iRow, iCol, true);
                                    $(cellDOM).removeClass("ui-state-highlight");
                                    nes_jrow = 0, nes_jcol = 0, nes_jsave = '';
                                }
                            }
                        });
                    }
                }, 100);
            }
        },
        beforeSubmitCell: function (rowid, cellName, cellValue, iRow, iCol) {
            if (nes_action_callbacks['before_rec_edit'] && $.isFunction(window[nes_action_callbacks['before_rec_edit']])) {
                return window[nes_action_callbacks['before_rec_edit']](rowid, cellName, cellValue, iRow, iCol);
            }
        },
        afterSubmitCell: function (response, rowid, cellname, value, iRow, iCol) {
            var $c_flag, $c_msg;
            if (response.responseText != 1) {
                var res = parseJSONString(response.responseText);
                var columnNames = $("#" + nesgrid_id).jqGrid('getGridParam', 'colNames');
                $c_flag = true;
                $c_msg = res.message;
                if (res.success == 'false') {
                    $c_flag = false;
                    $c_msg += " : " + columnNames[iCol];
                } else if (res.success == '2') {
                    reloadListGrid(nesgrid_id);
                } else if (res.success == '3' || res.success == '4') {
                    if (isRedirectEqualHash(res.red_hash)) {
                        window.location.hash = res.red_hash;
                        window.location.reload();
                    } else {
                        window.location.hash = res.red_hash;
                    }
                } else if (res.success == '5') {
                    window.location.href = res.red_hash;
                }
                gridReportMessage($c_flag, $c_msg);
                if (nes_action_callbacks['after_rec_edit'] && $.isFunction(window[nes_action_callbacks['after_rec_edit']])) {
                    return window[nes_action_callbacks['after_rec_edit']](response, rowid, cellname, value, iRow, iCol);
                }
            } else {
                $c_flag = true;
            }
            return [$c_flag, res.message];
        },
        afterSaveCell: function (rowid, cellname, value, iRow, iCol) {

        },
        onSortCol: function (index, iCol, sortorder) {
            $("#" + nesgrid_id).setGridParam({defaultsort: "No"});
            activateGridSortColumns(nesgrid_id);
        },
        resizeStop: function (newwidth, index) {
            setColumnsWidth(el_grid_settings.enc_location + '_ng_cw', nesgrid_id);
        },
        beforeSelectRow: function (rowid, e) {
            multiSelectHandler(rowid, e);
        }
    });

    if (nes_search_tool_permit) {
        jQuery("#" + nesgrid_id).jqGrid('filterToolbar', {
            stringResult: true,
            searchOnEnter: false,
            searchOperators: (el_theme_settings.grid_sub_searchopt) ? true : false
        });
    }

    var createDelSearchRefreshBtn = function (order_arr, label_arr) {
        var del_icon, del_text = '', del_title;
        del_icon = (el_theme_settings.grid_sub_icons_del || (label_arr['del'] && label_arr['del']['icon_only'] == "Yes")) ? true : false;
        if (!del_icon) {
            del_text = (label_arr['del'] && label_arr['del']['text']) ? label_arr['del']['text'] : js_lang_label.GENERIC_GRID_DELETE;
        }
        del_title = (label_arr['del'] && label_arr['del']['title']) ? label_arr['del']['title'] : js_lang_label.GENERIC_GRID_DELETE_SELECTED_ROW;

        var search_icon, search_text = '', search_title;
        search_icon = (el_theme_settings.grid_sub_icons_search || (label_arr['search'] && label_arr['search']['icon_only'] == "Yes")) ? true : false;
        if (!search_icon) {
            search_text = (label_arr['search'] && label_arr['search']['text']) ? label_arr['search']['text'] : js_lang_label.GENERIC_GRID_SEARCH;
        }
        search_title = (label_arr['search'] && label_arr['search']['title']) ? label_arr['search']['title'] : js_lang_label.GENERIC_GRID_ADVANCE_SEARCH;

        var refresh_icon, refresh_text = '', refresh_title;
        refresh_icon = (el_theme_settings.grid_sub_icons_refresh || (label_arr['refresh'] && label_arr['refresh']['icon_only'] == "Yes")) ? true : false;
        if (!refresh_icon) {
            refresh_text = (label_arr['refresh'] && label_arr['refresh']['text']) ? label_arr['refresh']['text'] : js_lang_label.GENERIC_GRID_SHOW_ALL;
        }
        refresh_title = (label_arr['refresh'] && label_arr['refresh']['title']) ? label_arr['refresh']['title'] : js_lang_label.GENERIC_GRID_SHOW_ALL_LISTING_RECORDS;

        jQuery("#" + nesgrid_id).jqGrid('navGrid', '#' + nes_pager_id, {
            cloneToTop: true,
            add: false,
            addicon: "ui-icon-plus",
            edit: false,
            editicon: "ui-icon-pencil",
            del: nes_del_permit,
            delicon: "ui-icon-trash",
            delicon_p: (del_icon) ? 'uigrid-del-btn del-icon-only' : "uigrid-del-btn",
            deltext: del_text,
            deltitle: del_title,
            search: nes_adv_search_permit,
            searchicon: "ui-icon-search",
            searchicon_p: (search_icon) ? 'uigrid-search-btn search-icon-only' : "uigrid-search-btn",
            searchtext: search_text,
            searchtitle: search_title,
            refresh: nes_refresh_permit,
            refreshicon: "ui-icon-refresh",
            refreshicon_p: (refresh_icon) ? 'uigrid-refresh-btn refresh-icon-only' : "uigrid-refresh-btn",
            refreshtext: refresh_text,
            refreshtitle: refresh_title,
            alerttext: js_lang_label.GENERIC_GRID_PLEASE_SELECT_ANY_RECORD,
            beforeRefresh: function () {
                $("#" + nesgrid_id).setGridParam({sortname: el_nesgrid_settings.default_sort, sortorder: el_nesgrid_settings.sort_order, defaultsort: "Yes"});
                activateGridSortColumns(nesgrid_id);
            },
            afterRefresh: function () {
                $("#hbox_" + nesgrid_id + "_jqgrid").find(".search-chosen-select").find("option").removeAttr("selected");
                $("#hbox_" + nesgrid_id + "_jqgrid").find(".search-chosen-select").trigger("chosen:updated");
                if ($("#hbox_" + nesgrid_id + "_jqgrid").find(".search-token-autocomplete").length) {
                    $("#hbox_" + nesgrid_id + "_jqgrid").find(".search-token-autocomplete").each(function () {
                        $(this).tokenInput("clear");
                    });
                }
                $("#hbox_" + nesgrid_id + "_jqgrid").find(".top-filter-chosen").find("option").removeAttr("selected");
                $("#hbox_" + nesgrid_id + "_jqgrid").find(".top-filter-chosen").trigger("chosen:updated");
                if ($("#hbox_" + nesgrid_id + "_jqgrid").find(".top-filter-autocomplete").length) {
                    $("#hbox_" + nesgrid_id + "_jqgrid").find(".top-filter-autocomplete").each(function () {
                        $(this).tokenInput("clear");
                    });
                }
            }
        }, {
            // edit options
        }, {
            // add options
        }, {
            // delete options
            id: nes_grid_button_ids.del,
            width: 320,
            caption: js_lang_label.GENERIC_GRID_DELETE,
            msg: js_lang_label.GENERIC_GRID_ARE_YOU_SURE_WANT_TO_DELETE_SELECTED_RECORDS,
            bSubmit: js_lang_label.GENERIC_GRID_DELETE,
            bCancel: js_lang_label.GENERIC_GRID_CANCEL,
            modal: true,
            closeOnEscape: true,
            serializeDelData: function (postdata) {
                var selAllRows = jQuery('#selAllRows').val();
                // append postdata with any information 
                return {
                    "id": postdata.id,
                    "oper": postdata.oper,
                    "AllRowSelected": selAllRows,
                    "filters": $('#' + nesgrid_id).getGridParam('postData').filters
                }
            },
            beforeSubmit: function (postdata) {
                if (nes_action_callbacks['before_rec_delete'] && $.isFunction(window[nes_action_callbacks['before_rec_delete']])) {
                    return window[nes_action_callbacks['before_rec_delete']](postdata);
                } else {
                    return [true, ""];
                }
            },
            afterSubmit: function (response, postdata) {
                var resdata = parseJSONString(response.responseText), $del_flag, $jq_errmsg;
                if (resdata.success == 'true') {
                    $jq_errmsg = js_lang_label.GENERIC_GRID_RECORDS_DELETED_SUCCESSFULLY;
                    if (resdata.message != "") {
                        $jq_errmsg = resdata.message;
                    }
                    $del_flag = true;
                } else {
                    $jq_errmsg = js_lang_label.GENERIC_GRID_ERROR_IN_DELETION;
                    if (resdata.message != "") {
                        $jq_errmsg = resdata.message;
                    }
                    $del_flag = false;
                }
                gridReportMessage($del_flag, $jq_errmsg);
                if (nes_action_callbacks['after_rec_delete'] && $.isFunction(window[nes_action_callbacks['after_rec_delete']])) {
                    window[nes_action_callbacks['after_rec_delete']](response, postdata);
                }
                return [true, $jq_errmsg];
            }
        }, {
            // search options
            id: nes_grid_button_ids.search,
            multipleSearch: true,
            multipleGroup: (el_nesgrid_settings.group_search == "1") ? true : false,
            showQuery: false,
            Find: js_lang_label.GENERIC_GRID_FIND,
            Reset: js_lang_label.GENERIC_GRID_RESET,
            width: 700,
            height: 275,
            closeOnEscape: true,
            modal: true,
            closeAfterSearch: true
        }, {
            // view options
        }, {
            // refresh options
            id: nes_grid_button_ids.refresh
        }, {
            // order options array
            order: order_arr
        });
    }

    var createAddButton = function (afterId, label_arr) {
        var add_icon, add_text = '', add_title;
        add_icon = (el_theme_settings.grid_sub_icons_add || (label_arr['icon_only'] == "Yes")) ? true : false;
        if (!add_icon) {
            add_text = (label_arr['text']) ? label_arr['text'] : js_lang_label.GENERIC_GRID_ADD_NEW;
        }
        add_title = (label_arr['title']) ? label_arr['title'] : js_lang_label.GENERIC_GRID_ADD_NEW;

        nes_js_assign_btn_id = nes_grid_button_ids.add;
        jQuery("#" + nesgrid_id).navButtonAdd('#' + nes_pager_id, {
            caption: add_text,
            title: add_title,
            buttonicon: "ui-icon-plus",
            buttonicon_p: (add_icon) ? 'uigrid-add-btn add-icon-only' : 'uigrid-add-btn',
            onClickButton: function () {
                adminAddNewRecord(el_nesgrid_settings.add_page_url, el_nesgrid_settings.extra_hstr, el_nesgrid_settings.popup_add_form, nesgrid_id, el_nesgrid_settings.popup_add_size);
            },
            id: nes_js_assign_btn_id,
            afterButtonId: afterId,
            position: "first"
        });
        jQuery("#" + nesgrid_id).navButtonAdd("#" + nesgrid_id + "_toppager_left", {
            caption: add_text,
            title: add_title,
            buttonicon: "ui-icon-plus",
            buttonicon_p: (add_icon) ? 'uigrid-add-btn add-icon-only' : 'uigrid-add-btn',
            onClickButton: function () {
                adminAddNewRecord(el_nesgrid_settings.add_page_url, el_nesgrid_settings.extra_hstr, el_nesgrid_settings.popup_add_form, nesgrid_id, el_nesgrid_settings.popup_add_size);
            },
            id: nes_js_assign_btn_id + "_top",
            afterButtonId: (afterId) ? afterId + "_top" : "",
            position: "first"
        });
    }

    var createStatusButton = function (afterId) {
        var nes_jstatus_btn, nes_jstatus_lbl, nes_status_icon;
        for (var i in el_nesgrid_settings.status_arr) {
            if (!el_nesgrid_settings.status_arr[i]) {
                continue;
            }
            nes_jstatus_btn = el_grid_settings.status_arr[i];
            nes_jstatus_lbl = eval(el_grid_settings.status_lang_arr[i]);
            nes_status_icon = (nes_jstatus_btn || "").replace(/(\s)/g, "").toLowerCase();

            nes_js_assign_btn_id = "status_" + i + "_" + nesgrid_id;
            nes_js_next_btn_id = (i == 0) ? afterId : "status_" + nes_js_prev_key + "_" + nesgrid_id;

            jQuery("#" + nesgrid_id).navButtonAdd('#' + nes_pager_id, {
                caption: nes_jstatus_lbl,
                title: nes_jstatus_lbl,
                lang: nes_jstatus_btn,
                buttonicon: "ui-icon-newwin",
                buttonicon_p: "uigrid-status-common uigrid-status-btn-" + nes_status_icon,
                onClickButton: function (e, p) {
                    var fids = filterGridSelectedIDs(this);
                    adminStatusChange(nesgrid_id, p.lang, fids, el_nesgrid_settings.edit_page_url, p.title, nes_action_callbacks, nes_list_message_arr);
                },
                id: nes_js_assign_btn_id,
                afterButtonId: nes_js_next_btn_id,
                position: "first"
            });
            jQuery("#" + nesgrid_id).navButtonAdd("#" + nesgrid_id + "_toppager_left", {
                caption: "" + nes_jstatus_lbl,
                title: nes_jstatus_lbl,
                lang: nes_jstatus_btn,
                buttonicon: "ui-icon-newwin",
                buttonicon_p: "uigrid-status-common uigrid-status-btn-" + nes_status_icon,
                onClickButton: function (e, p) {
                    var fids = filterGridSelectedIDs(this);
                    adminStatusChange(nesgrid_id, p.lang, fids, el_nesgrid_settings.edit_page_url, p.title, nes_action_callbacks, nes_list_message_arr);
                },
                id: nes_js_assign_btn_id + "_top",
                afterButtonId: (nes_js_next_btn_id) ? nes_js_next_btn_id + "_top" : "",
                position: "first"
            });
            nes_js_prev_key = i;
        }
    }

    var createInlineAddSaveDelBtn = function () {
        jQuery("#" + nesgrid_id).navButtonAdd('#' + nes_pager_id, {
            caption: "",
            title: js_lang_label.GENERIC_GRID_ADD_NEW,
            buttonicon: 'icon16 iconic-icon-plus-alt',
            buttonicon_p: "uigrid-inlineadd-btn",
            buttonname: "addnew",
            onClickButton: function (e) {
                addNewInlineRecord(nesgrid_id);
            },
            id: 'inlineadd_' + nesgrid_id,
            position: "last"
        });

        jQuery("#" + nesgrid_id).navButtonAdd("#" + nesgrid_id + "_toppager_left", {
            caption: "",
            title: js_lang_label.GENERIC_GRID_ADD_NEW,
            buttonicon: 'icon16 iconic-icon-plus-alt',
            buttonicon_p: "uigrid-inlineadd-btn",
            buttonname: "addnew",
            onClickButton: function (e) {
                addNewInlineRecord(nesgrid_id);
            },
            id: 'inlineadd_' + nesgrid_id + '_top',
            position: "last"
        });
        jQuery("#" + nesgrid_id).navButtonAdd('#' + nes_pager_id, {
            caption: "",
            title: js_lang_label.GENERIC_GRID_SAVE_ALL,
            buttonicon: 'icon16 iconic-icon-check-alt',
            buttonicon_p: "uigrid-inlinesave-btn",
            buttonname: "saveall",
            onClickButton: function (e) {
                saveAllInlineRecords(nesgrid_id);
            },
            id: 'saveall_' + nesgrid_id,
            position: "last"
        });

        jQuery("#" + nesgrid_id).navButtonAdd("#" + nesgrid_id + "_toppager_left", {
            caption: "",
            title: js_lang_label.GENERIC_GRID_SAVE_ALL,
            buttonicon: 'icon16 iconic-icon-check-alt',
            buttonicon_p: "uigrid-inlinesave-btn",
            buttonname: "saveall",
            onClickButton: function (e) {
                saveAllInlineRecords(nesgrid_id);
            },
            id: 'saveall_' + nesgrid_id + '_top',
            position: "last"
        });
        jQuery("#" + nesgrid_id).navButtonAdd('#' + nes_pager_id, {
            caption: "",
            title: js_lang_label.GENERIC_GRID_CANCEL_ALL,
            buttonicon: 'icon16 icomoon-icon-cancel-2',
            buttonicon_p: "uigrid-cancelall-btn",
            buttonname: "cancelall",
            onClickButton: function (e) {
                cancelAllInlineRecords(nesgrid_id);
            },
            id: 'cancelall_' + nesgrid_id,
            position: "last"
        });

        jQuery("#" + nesgrid_id).navButtonAdd("#" + nesgrid_id + "_toppager_left", {
            caption: "",
            title: js_lang_label.GENERIC_GRID_CANCEL_ALL,
            buttonicon: 'icon16 icomoon-icon-cancel-2',
            buttonicon_p: "uigrid-cancelall-btn",
            buttonname: "cancelall",
            onClickButton: function (e) {
                cancelAllInlineRecords(nesgrid_id);
            },
            id: 'cancelall_' + nesgrid_id + '_top',
            position: "last"
        });
    }

    if (nes_grid_button_arr.length > 0) {
        var nes_ord_dsr_arr = [], nes_btn_dsr_arr = {}, btn_name;
        for (var b = 0; b < nes_grid_button_arr.length; b++) {
            btn_name = nes_grid_button_arr[b]['name'];
            if ($.inArray(btn_name, ["del", "search", "refresh"]) != -1) {
                nes_ord_dsr_arr.push(btn_name);
                nes_btn_dsr_arr[btn_name] = nes_grid_button_arr[b];
            }
            if (nes_ord_dsr_arr.length >= 3) {
                break;
            }
        }
        createDelSearchRefreshBtn(nes_ord_dsr_arr, nes_btn_dsr_arr);
        for (var b = 0; b < nes_grid_button_arr.length; b++) {
            if (nes_grid_button_arr[b]['type'] == "custom") {
                nes_js_assign_btn_id = createCustomGridButton(nes_grid_button_arr[b], nesgrid_id, nes_pager_id, nes_js_assign_btn_id);
            } else {
                btn_name = nes_grid_button_arr[b]['name'];
                if (btn_name && btn_name.substring(0, 6) == "status") {
                    if (nes_status_permit) {
                        createStatusButton(nes_js_assign_btn_id);
                    }
                } else {
                    switch (btn_name) {
                        case "del":
                            if (nes_del_permit) {
                                nes_js_assign_btn_id = nes_grid_button_ids.del;
                            }
                            break;
                        case "search":
                            if (nes_adv_search_permit) {
                                nes_js_assign_btn_id = nes_grid_button_ids.search;
                            }
                            break;
                        case "refresh":
                            if (nes_refresh_permit) {
                                nes_js_assign_btn_id = nes_grid_button_ids.refresh;
                            }
                            break;
                        case "add":
                            if (nes_add_permit) {
                                createAddButton(nes_js_assign_btn_id);
                            }
                            break;
                    }
                }
            }
        }
    } else {
        createDelSearchRefreshBtn([], {});
        if (nes_add_permit) {
            createAddButton(nes_js_assign_btn_id);
        }
        if (nes_del_permit) {
            nes_js_assign_btn_id = nes_grid_button_ids.del;
        }
        if (nes_status_permit) {
            createStatusButton(nes_js_assign_btn_id);
        }
    }
    if (nes_inline_add_permit) {
        createInlineAddSaveDelBtn();
    }
}
//related to left search filters
function initLeftPanelSearch(grid_id) {
    if (!$("#left_search_panel").length) {
        return;
    }
    $("#input_left_search").bind("keypress", function (e) {
        if (e.which == 13) {
            filterCharacterSearchData(grid_id);
        }
    });
    $(document).off("click", ".left-data-row");
    $(document).on("click", ".left-data-row", function () {
        if ($(this).find(".data-left-anchor").hasClass("selected")) {
            $(this).find(".data-right-align").removeClass("active");
            $(this).find(".data-left-anchor").removeClass("selected");
        } else {
            $(this).find(".data-left-anchor").addClass("selected");
            $(this).find(".data-right-align").addClass("active");
        }
        filterLeftSearchPanelData(grid_id)
    });
    loadLeftSearchPanelData(grid_id);
}
function filterCharacterSearchData(grid_id) {
    var search_val = $("#input_left_search").val();
    var grid = $("#" + grid_id), filt;
    filt = {
        groupOp: "OR",
        rules: [],
        entrys: "",
        range: ""
    };
    if (search_val != "") {
        var gridModel = $("#" + grid_id).jqGrid('getGridParam', 'colModel');
        for (var i = 0; i < gridModel.length; i++) {
            if (gridModel[i].name == "cb" || gridModel[i].name == "subgrid" || gridModel[i].name == "prec") {
                continue;
            }
            var searchable = (typeof gridModel[i].search === 'undefined') ? true : gridModel[i].search;
            var hidden = (gridModel[i].hidden === true);
            var ignoreHiding = (gridModel[i].searchoptions && gridModel[i].searchoptions.searchhidden === true);
            if ((ignoreHiding && searchable) || (searchable && !hidden)) {
                filt.rules.push({
                    field: gridModel[i].name,
                    op: "bw",
                    data: search_val
                });
            }
        }
    }
    grid[0].p.search = true;
    $.extend(grid[0].p.postData, {
        filters: JSON.stringify(filt)
    });
    reloadListGrid(grid_id, [{
            page: 1,
            current: true
        }]);
    $("#tbl_left_search").find(".data-right-align").removeClass("active");
    $("#tbl_left_search").find(".data-left-anchor").removeClass("selected");
}
function filterLeftSearchPanelData(grid_id) {
    //Project.show_adaxloading_div();
//    if (!$(".left-data-row").length) {
//        return false;
//    }
    var _left_search_obj = {}, _left_search_txt = {};
    $(".left-data-row").each(function () {
        var left_anc = $(this).find(".data-left-anchor");
        if ($(left_anc).hasClass("selected")) {
            var search_type = $(left_anc).attr("aria-search-type");
            var search_key = $(left_anc).attr("aria-search-field");
            var search_val = '', search_max = '';
            if (search_type == "range") {
                var search_min = $(left_anc).attr("aria-search-min");
                var search_level = $(left_anc).attr("aria-search-level");
                if ($.inArray(search_level, ['below', 'above']) >= 0) {
                    search_max = search_level;
                    if (search_level == "below") {
                        search_min = $(left_anc).attr("aria-search-max");
                    }
                } else {
                    search_max = $(left_anc).attr("aria-search-max");
                }
                search_val = search_min + ' to ' + search_max;
            } else {
                search_val = $(left_anc).attr("aria-search-value");
            }
            if (!$.isArray(_left_search_obj[search_key])) {
                _left_search_obj[search_key] = [];
            }
            _left_search_obj[search_key].push(search_val);
        }
    });
    $('.lsac-input-left-filter').each(function () {
        var js_sc_type = $(this).attr("aria-field-type");
        var search_key = $(this).attr("aria-search-field");
        var search_val = $(this).val();
        if (search_val) {
            if (!$.isArray(_left_search_obj[search_key])) {
                _left_search_obj[search_key] = [];
            }
            if ($.inArray(js_sc_type, ["date", "date_and_time", "time"]) >= 0) {
                _left_search_obj[search_key].push(search_val);
            } else {
                var search_arr = search_val.split(",");
                $.merge(_left_search_obj[search_key], search_arr);
                if (!$.isArray(_left_search_txt[search_key])) {
                    _left_search_txt[search_key] = [];
                }
                if ($(this).data("tokenInputObject")) {
                    var tokens = $(this).data("tokenInputObject").getTokens();
                    _left_search_txt[search_key].push(tokens);
                } else {
                    _left_search_txt[search_key].push(search_val);
                }
            }
        }
    });
    $('.lsac-select-left-filter').each(function () {
        var js_sc_type = $(this).attr("aria-field-type");
        var search_key = $(this).attr("aria-search-field");
        var search_val = $(this).val();
        if (search_val) {
            if (!$.isArray(_left_search_obj[search_key])) {
                _left_search_obj[search_key] = [];
            }
            var search_arr = search_val;
            if (!$.isArray(search_val)) {
                search_arr = search_val.split(",");
            }
            $.merge(_left_search_obj[search_key], search_arr);
            if (!$.isArray(_left_search_txt[search_key])) {
                _left_search_txt[search_key] = [];
            }
            var keyvals = [], idtxt, valtxt;
            for (var i in search_arr) {
                try {
                    idtxt = search_arr[i];
                    valtxt = $(this).find("option[value='" + idtxt + "']").text();
                    keyvals.push({
                        "id": idtxt,
                        "val": valtxt
                    });
                } catch (err) {

                }
            }
            _left_search_txt[search_key].push(keyvals);
        }
    });
    $('.search-range-icon').each(function () {
        if ($(this).hasClass("active")) {
            var search_key = $(this).attr("aria-search-field");
            var search_val = $(this).val();
            var search_min = $("#lsrange_min_" + search_key).val();
            var search_max = $("#lsrange_max_" + search_key).val();
            if (search_min != "" || search_max != "") {
                if (!$.isArray(_left_search_obj[search_key])) {
                    _left_search_obj[search_key] = [];
                }
                if (search_min == "") {
                    search_val = search_max + " to above";
                } else if (search_max == "") {
                    search_val = search_min + " to below";
                } else {
                    search_val = search_min + " to " + search_max;
                }
                _left_search_obj[search_key].push(search_val);
            }
        }
    });
    for (var i in _left_search_obj) {
        if ($.isArray(_left_search_obj[i])) {
            _left_search_obj[i] = _left_search_obj[i].filter(function (itm, i, a) {
                return i == a.indexOf(itm);
            });
        }
    }
    var grid = $("#" + grid_id), filt;
    var post_data = $("#" + grid_id).jqGrid("getGridParam", "postData");
    var filters = (post_data && post_data.filters) ? parseJSONString(post_data.filters) : {};
    filt = {
        groupOp: "AND",
        rules: (filters && filters.rules) ? filters.rules : [],
        entrys: _left_search_obj,
        range: (filters && filters.range) ? filters.range : ""
    };
    grid[0].p.search = true;
    $.extend(grid[0].p.postData, {
        filters: JSON.stringify(filt)
    });
    reloadListGrid(grid_id, [
        {
            page: 1,
            current: true
        }
    ]);
    setLocalStore(el_grid_settings.enc_location + '_sv', JSON.stringify(_left_search_txt), true);
}
function loadLeftSearchPanelData(grid_id) {
    var post_data, save_data, save_json = {};
    var post_data = $("#" + grid_id).jqGrid("getGridParam", "postData");
    var save_data = getLocalStore(el_grid_settings.enc_location + '_sv');
    if (save_data) {
        save_json = parseJSONString(save_data);
    }
    if (post_data) {
        var filter_json = (post_data && post_data.filters) ? parseJSONString(post_data.filters) : {};
        if (filter_json && filter_json.entrys) {
            var entrys = filter_json.entrys, name, text_elem, range_elem, select_list;
            var d_val, anc_obj, range_arr, rmin_obj, rmax_obj;
            for (var i in entrys) {
                if (!$.isArray(entrys[i])) {
                    continue;
                }
//                if (!$.isArray(_left_search_obj[i])) {
//                    _left_search_obj[i] = [];
//                }
                select_list = [];
                text_elem = $("#lsac_" + i);
                range_elem = $("#search_range_slider_" + i);
                for (var j = 0; j < entrys[i].length; j++) {
                    d_val = entrys[i][j];
                    anc_obj = $(".data-left-anchor[aria-search-field='" + i + "'][aria-search-value='" + d_val + "']");
                    if ($(anc_obj).length) {
                        $(anc_obj).closest(".left-data-row").find(".data-left-anchor").addClass("selected");
                        $(anc_obj).closest(".left-data-row").find(".data-right-align").addClass("active");
                    } else {
                        if (text_elem.length) {
                            if (text_elem.attr("aria-search-mode") == "dropdown") {
                                select_list.push(d_val);
                            } else {
                                $(text_elem).val(d_val);
                            }
                        } else if (range_elem.length) {
                            if (d_val) {
                                range_arr = d_val.split(" to ");
                                rmin_obj = $(".data-left-anchor[aria-search-field='" + i + "'][aria-search-min='" + range_arr[0] + "']");
                                rmax_obj = $(".data-left-anchor[aria-search-field='" + i + "'][aria-search-max='" + range_arr[1] + "']");
                                if (rmin_obj.length && rmax_obj.length) {
                                    $(rmin_obj).closest(".left-data-row").find(".data-left-anchor").addClass("selected");
                                    $(rmin_obj).closest(".left-data-row").find(".data-right-align").addClass("active");
                                } else {
                                    $(range_elem).attr("aria-range-values", JSON.stringify(range_arr));
                                }
                            }
                        }
                    }
//                    _left_search_obj[i].push(d_val);
                }
                if (text_elem.attr("aria-search-mode") == "auto") {
                    if (i in save_json && save_json[i].length > 0 && save_json[i][0].length > 0) {
                        $(text_elem).attr("aria-token-json", JSON.stringify(save_json[i][0]));
                    }
                } else if (text_elem.attr("aria-search-mode") == "dropdown") {
                    $(text_elem).val(select_list).trigger("chosen:updated");
                }
            }
        }
    }
}
function initLeftPanelAutocomplete(grid_id) {
    if ($('.lsac-input-left-filter').length) {
        $('.lsac-input-left-filter').each(function () {
            var js_left_type = $(this).attr('aria-field-type');
            switch (js_left_type) {
                case 'date':
                    activateLSDateRangePicker($(this), grid_id);
                    break;
                case 'date_and_time':
                    activateLSDateTimePicker($(this), grid_id);
                    break;
                case 'time':
                    activateLSTimePicker($(this), grid_id);
                    break;
                default:
                    if ($(this).attr('aria-search-mode') == "text") {
                        $(this).bind("keypress", function (e) {
                            if (e.which == 13) {
                                filterLeftSearchPanelData(grid_id);
                            }
                        });
                    } else {
                        activateLSAutoComplete($(this), grid_id);
                    }
                    break;
            }
        });
    }
    if ($('.lsac-select-left-filter').length) {
        $('.lsac-select-left-filter').each(function () {
            $(this).bind("change", function (e) {
                filterLeftSearchPanelData(grid_id);
            });
        });
    }
    if ($('.search-range-slider').length) {
        $('.search-range-slider').each(function () {
            var range_key = $(this).attr("aria-range-key");
            var range_min = $(this).attr("aria-range-min");
            var range_max = $(this).attr("aria-range-max");
            var range_values = $(this).attr("aria-range-values");
            range_min = (range_min != "") ? parseInt(range_min) : 0;
            range_max = (range_max != "") ? parseInt(range_max) : 100;
            var slider_values = [range_min, range_max];
            if (range_values) {
                var parsed_values = parseJSONString(range_values);
                if ($.isArray(parsed_values)) {
                    var pmin = parseInt(parsed_values[0]);
                    var pmax = parseInt(parsed_values[1]);
                    slider_values = [pmin, pmax];
                    $("#lsrange_min_" + range_key).val(pmin);
                    $("#lsrange_max_" + range_key).val(pmax);
                }
            }
            $(this).slider({
                range: true,
                min: range_min,
                max: range_max,
                values: slider_values,
                slide: function (event, ui) {
                    var range_key = $(this).attr("aria-range-key");
                    $("#lsrange_min_" + range_key).val(ui.values[0]);
                    $("#lsrange_max_" + range_key).val(ui.values[1]);
                }
            });
        });
        $(document).on("click", ".search-range-icon", function () {
            if ($(this).hasClass("active")) {
                $(this).removeClass("active");
            } else {
                $(this).addClass("active");
            }
            filterLeftSearchPanelData(grid_id);
        });
    }
}
//related to left search filter events
function activateLSDateRangePicker(eleObj, grid_id) {
    var d_ranges, d_months, d_weeks;
    d_ranges = getRangePickerQuickList();
    d_months = getRangePickerMonthNames();
    d_weeks = getRangePickerWeekNames();
    $(eleObj).daterangepicker({
        ranges: d_ranges,
        opens: 'left',
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        showDropdowns: true,
        locale: {
            format: $(eleObj).attr('aria-date-format'),
            separator: ' to ',
            applyLabel: js_lang_label.GENERIC_GRID_SUBMIT,
            fromLabel: js_lang_label.GENERIC_FROM,
            toLabel: js_lang_label.GENERIC_TO,
            customRangeLabel: js_lang_label.GENERIC_CUSTOM_RANGE,
            daysOfWeek: d_weeks,
            monthNames: d_months,
            firstDay: 1
        },
        dateLimit: false,
        autoUpdateInput: false,
    });
    $(eleObj).on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format(picker.locale.format) + picker.locale.separator + picker.endDate.format(picker.locale.format));
        $(this).trigger('change');
    });
    $(eleObj).on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('').trigger('change');
    });
    activateLSSearchChangeEvent(eleObj, grid_id);
}
function activateLSDateTimePicker(eleObj, grid_id) {
    var d_ranges, d_months, d_weeks;
    d_ranges = getRangePickerQuickList();
    d_months = getRangePickerMonthNames();
    d_weeks = getRangePickerWeekNames();
    $(eleObj).daterangepicker({
        ranges: d_ranges,
        opens: 'left',
        timePicker: true,
        timePickerIncrement: 1,
        timePicker12Hour: ($(eleObj).attr('aria-enable-time') == 'false') ? false : true,
        showDropdowns: true,
        locale: {
            format: $(eleObj).attr('aria-date-format'),
            separator: ' to ',
            applyLabel: js_lang_label.GENERIC_GRID_SUBMIT,
            fromLabel: js_lang_label.GENERIC_FROM,
            toLabel: js_lang_label.GENERIC_TO,
            customRangeLabel: js_lang_label.GENERIC_CUSTOM_RANGE,
            daysOfWeek: d_weeks,
            monthNames: d_months,
            firstDay: 1
        },
        dateLimit: false,
        autoUpdateInput: false,
        linkedCalendars: false
    });
    $(eleObj).on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format(picker.locale.format) + picker.locale.separator + picker.endDate.format(picker.locale.format));
        $(this).trigger('change');
    });
    $(eleObj).on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('').trigger('change');
    });
    activateLSSearchChangeEvent(eleObj, grid_id);
}
function activateLSTimePicker(eleObj, grid_id) {
    $(eleObj).timepicker({
        timeFormat: $(eleObj).attr('aria-time-format'),
        showSecond: ($(eleObj).attr('aria-enable-sec') == "false") ? false : true,
        ampm: ($(eleObj).attr('aria-enable-ampm') == "false") ? false : true,
        showOn: 'focus',
        onClose: function (dateText, inst) {
            $(eleObj).trigger('change');
        }
    });
    activateLSSearchChangeEvent(eleObj, grid_id);
}
function activateLSAutoComplete(eleObj, grid_id) {
    var $js_list_id = $(eleObj).attr('aria-search-field');
    var js_url_action = el_grid_settings.search_autocomp_url + '&alias_name=' + $js_list_id;
    var pre_populate = parseJSONString($(eleObj).attr('aria-token-json'));
    $(eleObj).tokenInput(js_url_action, {
        'minChars': 1,
        'hintText': js_lang_label.GENERIC_TYPE_IN_A_SEARCH_TERM,
        'noResultsText': js_lang_label.GENERIC_NO_RESULTS,
        'searchingText': js_lang_label.GENERIC_SEARCHING,
        'preventDuplicates': true,
        'propertyToSearch': 'val',
        'theme': 'facebook',
        'prePopulate': pre_populate,
        'resultsFormatter': function (item) {
            if (item.count && item.count > 0) {
                return "<li>" + item.val + " <b style='float:right;width:20px;text-align:right;'>" + item.count + "</b></li>";
            } else {
                return "<li>" + item.val + "</li>";
            }
        },
        'tokenFormatter': function (item) {
            return "<li>" + item.val + "</li>"
        },
        'onAdd': function (item) {
            filterLeftSearchPanelData(grid_id);
        },
        'onDelete': function (item) {
            filterLeftSearchPanelData(grid_id);
        }
    });
}
function activateLSSearchChangeEvent(eleObj, grid_id) {
    $(eleObj).change(function () {
        filterLeftSearchPanelData(grid_id);
    });
}
//related to inline add new records
function getAddNewRecordContent(grid_id) {
    var $addgrid = $("#" + grid_id);

    var add_col_model = $addgrid.jqGrid("getGridParam", "colModel");
    if (!add_col_model) {
        return;
    }
    var tot_row = 0;
    $($addgrid).find("tr[aria-rec-row]").each(function () {
        var new_id = $(this).attr("id");
        if (new_id && parseInt(new_id) < tot_row) {
            tot_row = parseInt(new_id);
        }
    });
    tot_row--;
    var add_row = '', pos = 0, event_arr = [], sico = false, ffoc = true, ffoc_name;
    var new_row = $("<tr />").attr("role", "row").attr("id", tot_row).attr("tabindex", "-1").attr("class", "add-row ui-widget-content jqgrow ui-row-ltr").attr("aria-rec-row", tot_row);
    for (var i in add_col_model) {
        var cel_mod = add_col_model[i];
        var headers = $("#" + grid_id)[0].grid.headers;
        var tdwidth = headers[pos].width;
        if (cel_mod.hidden) {
            continue;
        }
        if ((cel_mod.name == "cb" || cel_mod.name == "subgrid" || cel_mod.name == "prec") && !sico) {
            var save_anc = '<a href="javascript://" title="' + js_lang_label.GENERIC_CANCEL + '" aria-cancel-row="' + grid_id + '" aria-row-id="' + tot_row + '">';
            save_anc += '<span aria-hidden="true" class="icomoon-icon-cancel-2 icon16"></span></a>';
            add_row = $("<td />").attr("role", "gridcell").attr("style", "text-align:center;vertical-align:middle;padding-left:0px;").attr("aria-describedby", grid_id + "_" + cel_mod.name).html(save_anc);
            sico = true;
        } else if (cel_mod.name == "subgrid" || cel_mod.name == "prec") {
            var cancel_anc = '<a href="javascript://" title="' + js_lang_label.GENERIC_SAVE + '" aria-save-row="' + grid_id + '" aria-row-id="' + tot_row + '">';
            cancel_anc += '<span aria-hidden="true" class="iconic-icon-check-alt icon16"></span></a>';
            add_row = $("<td />").attr("role", "gridcell").attr("style", "text-align:center;vertical-align:middle").attr("aria-describedby", grid_id + "_" + cel_mod.name);//.html(cancel_anc)
        } else {
            if (!cel_mod.addable) {
                add_row = $("<td />").attr("class", "add-cell ui-state-highlight").bind("dblclick", function (e) {
                    e.stopPropagation();
                });
            } else {
                var cel_name = cel_mod.name;
                if (!cel_mod.edittype) {
                    cel_mod.edittype = "text";
                }
                var opt = $.extend({
                    "aria-newrow": "inline-add-row"
                }, cel_mod.editoptions || {}, {
                    id: tot_row + "_" + cel_name,
                    name: cel_name
                });
                var elc = $.jgrid.createEl.call($addgrid, cel_mod.edittype, opt, '', true, $.extend({}, $.jgrid.ajaxOptions, {}), false);
                var funtype = "";
                if ($(elc).hasClass("dateOnly")) {
                    funtype = "date";
                } else if ($(elc).hasClass("timeOnly")) {
                    funtype = "time";
                } else if ($(elc).hasClass("dateTime")) {
                    funtype = "dateTime";
                } else if ($(elc).hasClass("colorPicker")) {
                    funtype = "colorPicker";
                }
                event_arr.push({
                    "row": tot_row,
                    "cell": cel_name,
                    "type": funtype
                });
                if ($(elc).attr('role') == "select") {
                    $(elc).attr("aria-default-val", cel_mod.default_value);
                } else {
                    $(elc).val(cel_mod.default_value);
                }
                var prp = formatAddCol(grid_id, cel_mod, pos, tot_row, '', '', 0, true);
                add_row = $("<td " + prp + "/>").attr("class", "add-cell ui-state-highlight").attr("role", "gridcell").append(elc).bind("dblclick", function (e) {
                    e.stopPropagation();
                });
                if (ffoc) {
                    ffoc_name = cel_mod.name;
                    ffoc = false;
                }
                $(add_row).find("input[type='text'], input[type='password'], textarea").bind("keydown", function (e) {
                    if ((e.ctrlKey || e.metaKey) && e.keyCode == 83) {
                        saveInlineAddRecord(grid_id, tot_row);
                        e.stopPropagation();
                        e.preventDefault();
                    }
                });
            }
        }
        $(new_row).append(add_row);
        pos++;
    }
    var ret_row = {
        "data": new_row,
        "events": event_arr,
        "row_id": tot_row,
        "focus_name": ffoc_name
    };
    return ret_row;
}
function addNewInlineRecord(grid_id) {
    var $addgrid = $("#" + grid_id);
    var inline_add = $addgrid.jqGrid("getGridParam", "inlineadd");
    if (!inline_add) {
        return;
    }
    var record_pos = $addgrid.jqGrid("getGridParam", "recordpos");
    var new_row = getAddNewRecordContent(grid_id);
    if (new_row) {
        if (record_pos == "Bottom") {
            $("#" + grid_id).append(new_row.data);
        } else {
            if ($("#" + grid_id).find("tr[aria-rec-row]").length) {
                $("#" + grid_id).find("tr[aria-rec-row]:last").after(new_row.data);
            } else {
                $("#" + grid_id).find("tr.jqgfirstrow").after(new_row.data);
            }
        }
        $("#" + grid_id).find("tr[id='" + new_row.row_id + "']").find("[name='" + new_row.focus_name + "']").focus();
        setTimeout(function () {
            activateAddRowEvents(new_row.events);
            applyInputTextCase($("#" + grid_id).find("tr[id='" + new_row.row_id + "']"));
        }, 100);
    }
}
function saveInlineAddRecord(grid_id, row_id) {
    var $savegrid = $("#" + grid_id);
    var row_obj = $($savegrid).find("tr[aria-rec-row='" + row_id + "']");
    if (!$(row_obj).find("[aria-newrow='inline-add-row']").length) {
        jqueryUIalertBox(js_lang_label.GENERIC_GRID_NO_DATA_FIELDS_FOUND_FOR_SAVING, js_lang_label.GENERIC_GRID_NO_DATA_FIELDS)
        return;
    }
    var iparams_obj = {}, ivalid_obj = {}, vfalg = true;
    var cmv = $savegrid.jqGrid("getGridParam", "colModel");
    for (var i in cmv) {
        if (cmv[i]['addable'] && cmv[i]['editrules']) {
            ivalid_obj[cmv[i]['name']] = {};
            ivalid_obj[cmv[i]['name']]['editrules'] = cmv[i]['editrules'];
        }
    }
    $(row_obj).find("[aria-newrow='inline-add-row']").each(function () {
        var vname = $(this).attr('name');
        var vval = $(this).val();
        if (ivalid_obj[vname] && ivalid_obj[vname]['editrules']) {
            var vres = validateViewInlineEdit(vname, vval, ivalid_obj[vname]);
            if (vres === false) {
                $(this).removeClass("inline-input-error");
                $(this).parent().find("div.inline-msg-error").remove();
            } else {
                if (vfalg) {
                    $(this).focus();
                }
                vfalg = false;
                $(this).addClass("inline-input-error");
                if ($(this).parent().find("div.inline-msg-error").length) {
                    $(this).parent().find("div.inline-msg-error").html(vres);
                } else {
                    var error_div = $("<div />").addClass("inline-msg-error").html(vres);
                    $(this).parent().append(error_div);
                }
            }
        }
        iparams_obj[vname] = vval;
    });
    if (!vfalg) {
        return false;
    }
    Project.show_adaxloading_div();
    iparams_obj["oper"] = "add";
    if ($savegrid.jqGrid("getGridParam", "parModule")) {
        iparams_obj["parMod"] = $savegrid.jqGrid("getGridParam", "parModule");
        iparams_obj["parID"] = $savegrid.jqGrid("getGridParam", "parData");
        iparams_obj["parField"] = $savegrid.jqGrid("getGridParam", "parField");
        iparams_obj["parType"] = $savegrid.jqGrid("getGridParam", "parType");
    }
    $.ajax({
        url: $savegrid.jqGrid("getGridParam", "editurl"),
        type: 'POST',
        data: iparams_obj,
        success: function (data) {
            Project.hide_adaxloading_div();
            var resp_data = parseJSONString(data);
            if (resp_data.success == 'true') {
                $(row_obj).remove();
                reloadListGrid(grid_id, null, 1);
                var $jq_errmsg = js_lang_label.GENERIC_GRID_RECORD_ADDED_SUCCESSFULLY;
                if (resp_data.message != "") {
                    $jq_errmsg = resp_data.message;
                }
                gridReportMessage(true, $jq_errmsg);
            } else {
                var $jq_errmsg = js_lang_label.GENERIC_GRID_ERROR_IN_ADDING_RECORD;
                if (resp_data.message != "") {
                    $jq_errmsg = resp_data.message;
                }
                gridReportMessage(false, $jq_errmsg);
            }

        }
    });
}
function saveAllInlineRecords(grid_id) {
    var $savegrid = $("#" + grid_id);
    if (!$($savegrid).find("tr[aria-rec-row]").length) {
        jqueryUIalertBox(js_lang_label.GENERIC_GRID_NO_DATA_FIELDS_FOUND_FOR_SAVING, js_lang_label.GENERIC_GRID_NO_RECORDS)
        return;
    }
    var fflag = true, sflag = true;
    $($savegrid).find("tr[aria-rec-row]").each(function () {
        var row_obj = $(this);
        if (!$(row_obj).find("[aria-newrow='inline-add-row']").length) {
            fflag = false;
        }
        return false;
    });
    if (!fflag) {
        jqueryUIalertBox(js_lang_label.GENERIC_GRID_NO_DATA_FIELDS_FOUND_FOR_SAVING, js_lang_label.GENERIC_GRID_NO_DATA_FIELDS);
        return false;
    }
    var ivalid_obj = {};
    var cmv = $savegrid.jqGrid("getGridParam", "colModel");
    for (var i in cmv) {
        if (cmv[i]['addable'] && cmv[i]['editrules']) {
            ivalid_obj[cmv[i]['name']] = {};
            ivalid_obj[cmv[i]['name']]['editrules'] = cmv[i]['editrules'];
        }
    }
    Project.show_adaxloading_div();
    $($savegrid).find("tr[aria-rec-row]").each(function () {
        var iparams_obj = {}, vfalg = true;
        var row_obj = $(this);
        $(row_obj).find("[aria-newrow='inline-add-row']").each(function () {
            var vname = $(this).attr('name');
            var vval = $(this).val();
            if (ivalid_obj[vname] && ivalid_obj[vname]['editrules']) {
                var vres = validateViewInlineEdit(vname, vval, ivalid_obj[vname]);
                if (vres === false) {
                    $(this).removeClass("inline-input-error");
                    $(this).parent().find("div.inline-msg-error").remove();
                } else {
                    if (vfalg) {
                        $(this).focus();
                    }
                    vfalg = false;
                    $(this).addClass("inline-input-error");
                    if ($(this).parent().find("div.inline-msg-error").length) {
                        $(this).parent().find("div.inline-msg-error").html(vres);
                    } else {
                        var error_div = $("<div />").addClass("inline-msg-error").html(vres);
                        $(this).parent().append(error_div);
                    }
                }
            }
            iparams_obj[vname] = vval;
        });
        if (!vfalg) {
            sflag = false;
            return false;
        }
        iparams_obj["oper"] = "add";
        if ($savegrid.jqGrid("getGridParam", "parModule")) {
            iparams_obj["parMod"] = $savegrid.jqGrid("getGridParam", "parModule");
            iparams_obj["parID"] = $savegrid.jqGrid("getGridParam", "parData");
            iparams_obj["parField"] = $savegrid.jqGrid("getGridParam", "parField");
            iparams_obj["parType"] = $savegrid.jqGrid("getGridParam", "parType");
        }
        $.ajax({
            url: $savegrid.jqGrid("getGridParam", "editurl"),
            type: 'POST',
            async: false,
            data: iparams_obj,
            success: function (data) {
                var resp_data = parseJSONString(data);
                if (resp_data.success == 'true') {
                    $(row_obj).remove();
                    var $jq_errmsg = js_lang_label.GENERIC_GRID_RECORD_ADDED_SUCCESSFULLY;
                    if (resp_data.message != "") {
                        $jq_errmsg = resp_data.message;
                    }
                    gridReportMessage(true, $jq_errmsg);
                } else {
                    var $jq_errmsg = js_lang_label.GENERIC_GRID_ERROR_IN_ADDING_RECORD;
                    if (resp_data.message != "") {
                        $jq_errmsg = resp_data.message;
                    }
                    gridReportMessage(false, $jq_errmsg);
                    sflag = false;
                    return false;
                }
            }
        });
    });
    Project.hide_adaxloading_div();
    if (sflag) {
        reloadListGrid(grid_id, null, 1);
    }
}
function cancelInlineAddRecord(grid_id, row_id) {
    var label_elem = '<div />';
    var label_text = js_lang_label.GENERIC_GRID_ARE_YOU_SURE_WANT_TO_DELETE_ADDED_RECORD;
    var option_params = {
        title: js_lang_label.GENERIC_GRID_DELETE,
        dialogClass: "dialog-confirm-box grid-inline-rec-cnf",
        buttons: [
            {
                text: js_lang_label.GENERIC_DELETE,
                bt_type: 'delete',
                click: function () {
                    $("#" + grid_id).find("tr[aria-rec-row='" + row_id + "']").remove();
                    $(this).remove();
                }
            },
            {
                text: js_lang_label.GENERIC_CANCEL,
                bt_type: 'cancel',
                click: function () {
                    $(this).remove();
                }
            }]
    }
    jqueryUIdialogBox(label_elem, label_text, option_params);
}
function cancelAllInlineRecords(grid_id) {
    if (!$("#" + grid_id).find("tr[aria-rec-row]").length) {
        jqueryUIalertBox(js_lang_label.GENERIC_GRID_NO_NEW_RECORDS_FOUND_FOR_DELETE, js_lang_label.GENERIC_GRID_NO_RECORDS)
        return;
    }
    var label_elem = '<div />';
    var label_text = js_lang_label.GENERIC_GRID_ARE_YOU_SURE_WANT_TO_DELETE_ALL_NEW_RECORDS;
    var option_params = {
        title: js_lang_label.GENERIC_GRID_DELETE,
        dialogClass: "dialog-confirm-box grid-inline-all-cnf",
        buttons: [{
                text: js_lang_label.GENERIC_DELETE,
                bt_type: 'delete',
                click: function () {
                    $("#" + grid_id).find("tr[aria-rec-row]").remove();
                    $(this).remove();
                }
            }, {
                text: js_lang_label.GENERIC_CANCEL,
                bt_type: 'cancel',
                click: function () {
                    $(this).remove();
                }
            }]
    }
    jqueryUIdialogBox(label_elem, label_text, option_params);
}
function activateAddRowEvents(events) {
    if (events) {
        for (i in events) {
            if (events[i]['type'] == "date") {
                inlineDateTimePicker(events[i]['row'], events[i]['cell'], 'date');
            } else if (events[i]['type'] == "time") {
                inlineDateTimePicker(events[i]['row'], events[i]['cell'], 'time');
            } else if (events[i]['type'] == "dateTime") {
                inlineDateTimePicker(events[i]['row'], events[i]['cell'], 'dateTime');
            } else if (events[i]['type'] == "colorPicker") {
                inlineColorPicker(events[i]['row'], events[i]['cell'], "colorPicker");
            }
        }
    }
}
function formatAddCol(grid_id, cm, pos, rowInd, tv, rawObject, rowId, rdata) {
    var $addgrid = $("#" + grid_id);
    var ral = cm.align, result = "style=\"", clas = cm.classes, nm = cm.name;
    if (ral) {
        result += "text-align:" + ral + ";";
    }
    if (cm.hidden === true) {
        result += "display:none;";
    }
    result += (clas !== undefined ? (" class=\"" + clas + "\"") : "") + ((cm.title && tv) ? ("") : "");// title=\""+$.jgrid.stripHtml(tv)+"\"
    result += " aria-describedby=\"" + grid_id + "_" + nm + "\"";
    return result;
}
function applySearchCriteria(grid_id) {
    var pD, fR, sA;
    pD = $("#" + grid_id).getGridParam("postData");
    if (pD.filters) {
        fR = parseJSONString(pD.filters);
        if (fR && fR.rules) {
            if ($.isArray(fR.rules) && fR.rules.length) {
                sA = 1;
            }
        }
    }
    if (sA == 1) {
        $("#search_" + grid_id + "_top").find("div.btn").addClass("active");
    } else {
        $("#search_" + grid_id + "_top").find("div.btn").removeClass("active");
    }
}
//related to grid BG colors
function applyGridRowColors(grid_id, data) {
    var $color_grid = $("#" + grid_id);
    if (!data || !data.records || !data.colors) {
        return false;
    }
    var ccm = $color_grid.jqGrid("getGridParam", "colModel");
    var lv = $color_grid.jqGrid("getGridParam", "listview");
    var color_arr = data.colors, n;

    for (var i in color_arr) {
        if (!color_arr[i]) {
            continue;
        }
        for (var j in color_arr[i]) {
            var ca = color_arr[i][j];
            if (lv == "grid") {
                if (ca['fill'] == "cell" || ca['fill'] == "text") {
                    n = ca['cell'].toLowerCase();
                    n = n.replace(new RegExp("_", 'gi'), "-");
                    if (ca['fill'] == "text") {
                        $($color_grid).find(".item-grid[id='" + i + "']").find('.' + n).css("color", ca['color']);
                    } else {
                        $($color_grid).find(".item-grid[id='" + i + "']").find('.' + n).css("background-color", ca['color']);
                    }
                } else {
                    $($color_grid).find(".item-grid[id='" + i + "']").css("background-color", ca['color']);
                }
            } else if (lv == "view") {
                if (ca['fill'] == "cell" || ca['fill'] == "text") {
                    n = ca['cell'].toLowerCase();
                    n = n.replace(new RegExp("_", 'gi'), "-");
                    if (ca['fill'] == "text") {
                        $($color_grid).find("tr[id='" + i + "'] .item-list").find('.' + n).css("color", ca['color']);
                    } else {
                        $($color_grid).find("tr[id='" + i + "'] .item-list").find('.' + n).css("background-color", ca['color']);
                    }
                } else {
                    $($color_grid).find("tr[id='" + i + "'] td").css("background-color", ca['color']);
                }
            } else {
                if (ca['fill'] == "cell" || ca['fill'] == "text") {
                    n = 0;
                    for (var k in ccm) {
                        if (ccm[k].name == ca['cell']) {
                            break;
                        }
                        n++;
                    }
                    if (ca['fill'] == "text") {
                        $($color_grid).find("tr[id='" + i + "'] td:eq(" + n + ")").css("color", ca['color']);
                    } else {
                        $($color_grid).find("tr[id='" + i + "'] td:eq(" + n + ")").css("background-color", ca['color']);
                    }
                } else {
                    $($color_grid).find("tr[id='" + i + "'] td").css("background-color", ca['color']);
                }
            }
        }
    }
}
//related to grid rating icons
function applyRatingEvents(grid_id) {
    var $rating_grid = $("#" + grid_id);
    var ccrating = $rating_grid.jqGrid("getGridParam", "ratingAllow");
    if (!ccrating) {
        return false;
    }
    var ccm = $rating_grid.jqGrid("getGridParam", "colModel");
    var row_ids = $rating_grid.getDataIDs();
    var t, raty_events, raty_elem, gr_elem, txt;
    for (var i in row_ids) {
        t = row_ids[i];
        for (var k in ccm) {
            if (ccm[k].ctrl_type == "rating_master" && ccm[k].ratyallow && ccm[k].ratyevents) {
                raty_events = ccm[k].ratyevents;
                gr_elem = $($rating_grid).find("tr[id='" + t + "'] td:eq(" + k + ")");
                txt = $(gr_elem).text();
                raty_elem = $('<span />').addClass("rating-icons-block");
                activateRatingMasterEvent(raty_elem, ccm[k].ratyevents.raty.params, ccm[k].ratyevents.raty.hints, txt)
                $(gr_elem).text("");
                $(gr_elem).append(raty_elem);
            }
        }
    }
}
//related to grid widths
function adjustMainGridColumnWidth() {
    var main_auto_width = $("#" + el_tpl_settings.main_grid_id).jqGrid('getGridParam', '_autowidth');
    if (main_auto_width) {
        return false;
    }
    var head_obj = $("#hbox_" + el_tpl_settings.main_grid_id + "_jqgrid").find(".ui-jqgrid-htable:first");
    var data_obj = $("#" + el_tpl_settings.main_grid_id);
    var label_obj = $(head_obj).find(".ui-jqgrid-labels");
    var first_obj = $(data_obj).find(".jqgfirstrow:first");

    var head_wt = $(head_obj).get(0).scrollWidth;
    var data_wt = $(data_obj).get(0).scrollWidth;
    var diff_wt = data_wt - head_wt, items = 0;

    $(label_obj).find("th").each(function () {
        if (!$(this).is(":hidden")) {
            items++;
        }
    });
    if (diff_wt > 8 && items > 0) {
        var split_wt = Math.round(diff_wt / items);
        $(label_obj).find("th").each(function (i) {
            if (!$(this).is(":hidden")) {
                var cur_wt = $(this).width() + parseInt(split_wt);
                $(this).width(cur_wt);
                $(first_obj).find("td:eq(" + i + ")").width(cur_wt);
            }
        });
    }
}
function initGirdLoadingProgress(grid_id) {
    $("#load_" + grid_id).show();
}
function hideGirdLoadingProgress(grid_id) {
    $("#load_" + grid_id).hide();
}
function initGirdLoadingOverlay(grid_id) {
    $("#lui_" + grid_id).show();
}
function hideGirdLoadingOverlay(grid_id) {
    $("#lui_" + grid_id).hide();
}
function noRecordsMessage(grid_id, data) {
    if (!data || !data.records || data.records == '0') {
        var nrm = $("#" + grid_id);
        var message = (data.no_records_msg) ? data.no_records_msg : $(nrm).jqGrid("getGridParam", "norecmsg");
        var noc = $(nrm).find("tr.jqgfirstrow").find("td").length;
        var nrr = $("<tr />").html("<td colspan='" + noc + "' align='center'><div class='grid-norec-msg'>" + message + "</div></td>");
        $(nrm).append(nrr);
    }
}
function adjustWrappedWidth(grid_id) {
    var $wrap_grid = $("#" + grid_id);
    var autowidth = $($wrap_grid).jqGrid("getGridParam", "_autowidth");
    var cm = $($wrap_grid).jqGrid("getGridParam", "colModel");
    var row_ids = $wrap_grid.getDataIDs();
    if (!row_ids || !row_ids.length || autowidth == true) {
        return false;
    }
    $("#" + grid_id).find("tr.ui-row-ltr td").css("white-space", "nowrap");
    var wrap_arr = {};
    for (var i in row_ids) {
        var row_obj = $("#" + grid_id).find("tr[id='" + row_ids[i] + "']");
        $(row_obj).find("td").each(function (i) {
            if (!$.isArray(wrap_arr[i])) {
                wrap_arr[i] = [];
            }
            wrap_arr[i].push($(this)[0].scrollWidth);
            //wrap_arr[i].push($(this).textWidth());
        });
    }
    var head_obj = $("#hbox_" + grid_id + "_jqgrid").find(".ui-jqgrid-htable:first");
    var data_obj = $("#" + grid_id);
    var label_obj = $(head_obj).find(".ui-jqgrid-labels");
    var first_obj = $(data_obj).find(".jqgfirstrow:first");
    for (var i in wrap_arr) {
        var is_hide = $(label_obj).find("th:eq(" + i + ")").is(":hidden");
        var is_spec = (cm[i] && cm[i]['name'] && $.inArray(cm[i]['name'], ['cb', 'rn', 'subgrid', 'prec']) == -1) ? false : true;
        if (is_hide) {
            $(label_obj).find("th:eq(" + i + ")").width(0);
            $(first_obj).find("td:eq(" + i + ")").width(0);
        } else if (is_spec) {
            var maxWidth = (cm[i] && cm[i]['width']) ? cm[i]['width'] : 0;
            $(label_obj).find("th:eq(" + i + ")").width(maxWidth);
            $(first_obj).find("td:eq(" + i + ")").width(maxWidth);
        } else {
            var maxWidth = Math.max.apply(null, wrap_arr[i]);
            maxWidth += 2;
            maxWidth = (maxWidth > el_tpl_settings.grid_column_width) ? maxWidth : el_tpl_settings.grid_column_width;
            $(label_obj).find("th:eq(" + i + ")").width(maxWidth);
            $(first_obj).find("td:eq(" + i + ")").width(maxWidth);
        }
    }
    //$("#" + grid_id).find("tr.ui-row-ltr td").css("white-space", "none");
    return true;
}
//related to x-editable events
function makeAddListEditableTextBox(v_name, view_settings_obj) {
    var v_value = view_settings_obj[v_name]['value'];
    var show_btn = true;
    if (view_settings_obj[v_name]['type'] == "file") {
        var i_value = view_settings_obj[v_name]['dbval'];
        show_btn = false;
    }
    $('#' + v_name).editable({
        showbuttons: show_btn,
        placeholder: view_settings_obj[v_name].editoptions.placeholder,
        type: 'text',
        name: v_name,
        value: i_value,
        pk: el_subview_settings.edit_id,
        onblur: 'submit',
        validate: function (value) {
            var vid = $(this).attr("id");
            return validateViewInlineEdit(vid, value, view_settings_obj[vid]);
        },
        url: function (params) {
            saveViewInlineEdit(params.name, params.value, params.id);
        }
    });
    $('#' + v_name).on('shown', function (e, editable) {
        var ele_name = $(this).attr("id");
        var ele_obj = $(editable.$form).find(".editable-input").find("input[type='text']");
        var obj_pro = view_settings_obj[ele_name];
        switch (obj_pro.type) {
            case 'date':
                $(ele_obj).addClass("date-picker-icon");
                activeDateTimePicker(ele_obj, "date", obj_pro.editoptions);
                break;
            case 'date_and_time':
                $(ele_obj).addClass("date-picker-icon")
                activeDateTimePicker(ele_obj, "dateTime", obj_pro.editoptions);
                break;
            case 'time':
                $(ele_obj).addClass("date-picker-icon")
                activeDateTimePicker(ele_obj, "time", obj_pro.editoptions);
                break;
            case 'phone_number':
                $(ele_obj).mask(obj_pro.editoptions.format);
                break;
            case 'textbox':
                if (obj_pro.editoptions.text_case) {
                    $(ele_obj).addClass(obj_pro.editoptions.text_case);
                    applyInputTextCase($(editable.$form).find(".editable-input"));
                }
                applyAddonElementHTML($(editable.$form).find(".editable-input"), obj_pro);
                break;
            case 'color_picker':
                activateColorPicker(ele_obj, obj_pro.editoptions.color_preview);
                addElementProperties(ele_obj, obj_pro.editoptions);
                break;
            case 'file':
                $(ele_obj).hide();
                appendAddListUploadifyProperties(ele_obj, obj_pro);
//                if (el_general_settings.having_flash_obj) {
//                    appendAddListUploadifyProperties(ele_obj, obj_pro);
//                } else {
//                    uploadifyFlashError();
//                }
                break;
            case 'autocomplete' :
                var $ele_rand_id = Math.floor((Math.random() * 100000) + 1);
                $(ele_obj).attr('id', $ele_rand_id);
                $(ele_obj).wrap('<div class="frm-token-autocomplete frm-size-medium" />');
                activateAddListAutoComplete(ele_obj, obj_pro, v_name);
                addElementProperties(ele_obj, obj_pro.editoptions);
                setTimeout(function () {
                    $(editable.$form).find(".frm-token-autocomplete").append(view_settings_obj[v_name].add_content);
                    var ref_url = $(editable.$form).find('.fancybox-hash-iframe').attr('href');
                    $(editable.$form).find('.fancybox-hash-iframe').attr('href', ref_url + '|rfhtmlID|' + $ele_rand_id);
                }, 5);
                break;
            case 'rating_master':
                $(ele_obj).hide();
                appendAddListRatingProperties(ele_obj, obj_pro);
                break;
        }
        if (parseInt(obj_pro.editoptions.width) > 0) {
            $(ele_obj).css("width", obj_pro.editoptions.width);
        }
    });
    if (view_settings_obj[v_name]['type'] == "rating_master") {
        $('#' + v_name).on('hidden', function (e, reason) {
            var ele_name = $(this).attr("id");
            $("#rshow_" + ele_name).show();
        });
    }
}
function activateAddListAutoComplete(ele_obj, obj_pro, ele_name) {
    var par_obj = obj_pro.editoptions.token.params;
    view_token_pre_populates[ele_name] = par_obj.prePopulate;
    getAddListAutoCompDataValueArr(ele_name);
    view_token_input_assign = $(ele_obj).tokenInput(obj_pro.editoptions.serviceUrl, {
        minChars: par_obj.minChars,
        multi: obj_pro.editoptions.multi,
        propertyToSearch: par_obj.propertyToSearch,
        theme: par_obj.theme,
        tokenLimit: par_obj.tokenLimit,
        hintText: par_obj.hintText,
        noResultsText: par_obj.noResultsText,
        searchingText: par_obj.searchingText,
        preventDuplicates: par_obj.preventDuplicates,
        prePopulate: par_obj.prePopulate,
        onAdd: function (item) {
            view_token_pre_populates[ele_name] = view_token_input_assign.tokenInput('get');
            getAddListAutoCompDataValueArr(ele_name);
        },
        onDelete: function (item) {
            view_token_pre_populates[ele_name] = view_token_input_assign.tokenInput('get');
            getAddListAutoCompDataValueArr(ele_name);
        }
    });
}
function getAddListAutoCompDataValueArr(ele_name) {
    var $data_arr = [];
    for (i in view_token_pre_populates[ele_name]) {
        $data_arr[i] = view_token_pre_populates[ele_name][i]['val'];
    }
    $("#" + ele_name).attr("data-value", $data_arr.join(","));
}
function displayAddListRatingProperties(v_name, obj_pro) {
    var raty_elem, rv_elem, txt;
    raty_elem = $('<span />').attr("id", "rshow_" + v_name).addClass("rating-icons-block");
    rv_elem = $("#rscore_" + v_name);
    txt = $(rv_elem).text();
    var raty_params = $.extend({}, obj_pro.editoptions.raty.params);
    raty_params.cancel = false;
    raty_params.readOnly = true;
    activateRatingMasterEvent(raty_elem, raty_params, obj_pro.editoptions.raty.hints, txt)
    $(rv_elem).after(raty_elem);
    $(rv_elem).hide();
}
function appendAddListRatingProperties(eleObj, obj_pro) {
    var raty_elem, rv_elem, rh_elem, txt;
    raty_elem = $('<span />', {"id": "rstar_" + obj_pro.htmlID, "aria-raty-name": obj_pro.htmlID}).addClass("rating-icons-block");
    rh_elem = $("#rshow_" + obj_pro.htmlID);
    rv_elem = $("#rscore_" + obj_pro.htmlID);
    txt = $(rv_elem).text();
    var raty_params = $.extend({}, obj_pro.editoptions.raty.params);
    raty_params.target = eleObj;
    activateRatingMasterEvent(raty_elem, raty_params, obj_pro.editoptions.raty.hints, txt)
    $(eleObj).after(raty_elem);
    $(rh_elem).hide();
}
function makeAddListEditableTextArea(v_name, view_settings_obj) {
    $('#' + v_name).editable({
        showbuttons: true,
        type: 'textarea',
        placeholder: view_settings_obj[v_name].editoptions.placeholder,
        name: v_name,
        pk: el_subview_settings.edit_id,
        onblur: 'submit',
        rows: 3,
        validate: function (value) {
            var vid = $(this).attr("id");
            return validateViewInlineEdit(vid, value, view_settings_obj[vid]);
        },
        url: function (params) {
            saveViewInlineEdit(params.name, params.value, params.id);
        }
    });
    $('#' + v_name).on('shown', function (e, editable) {
        var ele_name = $(this).attr("id");
        var ele_obj = $(editable.$form).find(".editable-input").find("textarea");
        var obj_pro = view_settings_obj[ele_name];
        if (obj_pro.editoptions.text_case) {
            $(ele_obj).addClass(obj_pro.editoptions.text_case);
            applyInputTextCase($(editable.$form).find(".editable-input"));
        }
    });

}
function makeAddListEditablePassword(v_name, view_settings_obj) {
    $('#' + v_name).editable({
        showbuttons: true,
        type: 'password',
        placeholder: view_settings_obj[v_name].editoptions.placeholder,
        name: v_name,
        pk: el_subview_settings.edit_id,
        onblur: 'submit',
        validate: function (value) {
            var vid = $(this).attr("id");
            return validateViewInlineEdit(vid, value, view_settings_obj[vid])
        },
        url: function (params) {
            saveViewInlineEdit(params.name, params.value, params.id);
        }
    });
}
function makeAddListEditableDropdown(v_name, view_settings_obj) {
    if (view_settings_obj[v_name].editoptions && view_settings_obj[v_name].editoptions.dataUrl) {
        var v_dataUrl = view_settings_obj[v_name].editoptions.dataUrl;
        $('#' + v_name).editable({
            showbuttons: true,
            type: 'select',
            source: v_dataUrl,
            pk: el_subview_settings.edit_id,
            sourceCache: false,
            validate: function (value) {
                var vid = $(this).attr("id");
                return validateViewInlineEdit(vid, value, view_settings_obj[vid])
            },
            url: function (params) {
                saveViewInlineEdit(params.name, params.value, params.id);
            }
        });
        $('#' + v_name).on('shown', function (e, editable) {
            var $that = $(editable.$form).find(".editable-input").find("select");
            $($that).attr('data-placeholder', view_settings_obj[v_name].editoptions.data_placeholder);
            $($that).find("option").removeAttr("selected");
            var data_val = $(this).attr("data-value")
            $($that).find("option").each(function () {
                if ($(this).text() == data_val) {
                    $(this).prop("selected", true);
                    return false;
                }
            });
            var ele_name = $(this).attr("id");
            var obj_pro_cc = view_settings_obj[ele_name];
            switch (obj_pro_cc.type) {
                case 'checkboxes':
                    $($that).attr("multiple", true);
                    break;
                case 'multi_select_dropdown':
                    $($that).attr("multiple", true);
                    break;
            }
            if (parseInt(obj_pro_cc.editoptions.width) > 0) {
                $($that).attr('style', function (i, s) {
                    return 'width: ' + obj_pro_cc.editoptions.width + 'px !important;' + s
                });
            }
            if (obj_pro_cc.type == "checkboxes" || obj_pro_cc.type == "multi_select_dropdown") {
                var data_val_arr = [];
                $.each(data_val.split(","), function () {
                    data_val_arr.push($.trim(this));
                });
                $($that).find("option").each(function () {
                    if ($.inArray($.trim($(this).text()), data_val_arr) != -1) {
                        $(this).prop("selected", true);
                    }
                });
            } else {
                $($that).find("option").each(function () {
                    if ($.trim($(this).text()) == $.trim(data_val)) {
                        $(this).prop("selected", true);
                        return false;
                    }
                });
            }
            setTimeout(function () {
                $($that).chosen({
                    allow_single_deselect: true
                });
                var that_id = $($that).attr("id");
                $('#' + that_id + '_chosen').trigger('mousedown');
                $('#' + that_id + '_chosen').find("input[type='text']").focus();
                var obj_pro = view_settings_obj[ele_name];
                if (obj_pro.editoptions.ajaxCall == "ajax-call") {
                    var inlineID = obj_pro.editoptions.rel;
                    var $queryStr = "&mode=" + cus_enc_mode_json['Update'] + "&unique_name=" + inlineID;
                    var $ajaxSendURL = el_subview_settings.ajax_data_url + '' + $queryStr;
                    $($that).ajaxChosen({
                        dataType: 'json',
                        type: 'POST',
                        url: $ajaxSendURL
                    }, {
                        loadingImg: admin_image_url + "chosen-loading.gif"
                    });
                }
            }, 5)
            $($that).change(function () {
                if (obj_pro_cc.type == "checkboxes" || obj_pro_cc.type == "multi_select_dropdown") {
                    var mlist_val = [];
                    $(this).find("option:selected").each(function () {
                        mlist_val.push($(this).text());
                    });
                    if (mlist_val && mlist_val.length > 0) {
                        $("#" + ele_name).attr("data-value", mlist_val.join(","));
                    } else {
                        $("#" + ele_name).attr("data-value", "");
                    }
                } else {
                    $("#" + ele_name).attr("data-value", $(this).find("option:selected").val());
                }
            })
        });
    }

}
function appendAddListUploadifyProperties(ele_obj, obj_pro) {
    if (!obj_pro) {
        return;
    }
    var ele_name = obj_pro.htmlID;
    var ele_val = obj_pro.dbval;
    var ele_label = obj_pro.label;
    var ele_parent = obj_pro.parentattr;
    var ele_events = obj_pro.editoptions;

    if (!ele_events.fileupload) {
        return false;
    }

    var $fileStr = "<div class='uploader'>";
    $fileStr += "<input type='hidden' value='" + ele_val + "' name='temp_" + ele_name + "' id='temp_" + ele_name + "' />";
    $fileStr += "<input type='file' name='uploadify_" + ele_name + "' id='uploadify_" + ele_name + "' title='" + ele_label + "'/>";
    $fileStr += "<span class='filename' id='preview_caf_file'>" + ele_events.fileupload.placeholder + "</span><span class='action'>Choose File</span>";
    $fileStr += "</div>";

    var par_obj = $(ele_obj).closest('.editable-container')
    $(par_obj).wrap('<div ' + ele_parent + ' id="btn_file_' + ele_name + '"/>');
    $(ele_obj).after($fileStr);

    if (ele_events.fileupload) {
        var upload_data = ele_events.fileupload;
        var upload_params = upload_data.params;
        var basic_params = assignEventParams(upload_params);
        var function_params = {
            formData: {
                'unique_name': upload_data['unique_name'],
                'id': upload_data['id'],
                'type': 'uploadify'
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
                    var jparse_data = parseJSONString(data.result);
                    if (jparse_data.success == '0') {
                        Project.setMessage(jparse_data.message, 0);
                    } else {
                        $('#' + _input_name).val(jparse_data.uploadfile);
                        $('#' + _temp_name).val(jparse_data.oldfile);
                        displayAdminListFlyImage(_input_name, upload_data, jparse_data);
                        var old_data = {
                            "old_file": ele_val
                        };
                        saveViewInlineEdit(_input_name, jparse_data.uploadfile, el_subview_settings.edit_id, old_data);
                        setTimeout(function () {
                            $('.jqgrid-subview').click();
                        }, 200)
                    }
                }
            },
            fail: function (e, data) {
                $.each(data.messages, function (index, error) {
                    Project.setMessage(error, 0);
                });
            }
        };
        var final_params = $.extend({}, basic_params, function_params);
        $('#uploadify_' + ele_name).fileupload(final_params);
    }
}
//related to image/file on the fly display
function displayAdminListFlyImage(hid, upload_data, rarr) {
    if (rarr['fileURL']) {
        var del_btn = "<a title='" + js_lang_label.GENERIC_GRID_DELETE + "' style='text-decoration:none;' href='javascript://' onclick='deleteFileTypeDocs(\"" + upload_data.id + "\",\"" + upload_data.unique_name + "\",\"" + upload_data.delete_file_url + "\",\"" + upload_data.folder + "\",\"" + hid + "\")' id='anc_imgdel_" + hid + "' >";
        del_btn += "<span class='icon16 minia-icon-trashcan'></span>";
        del_btn += "</a>";

        var $img_str = $("<a />");
        if (rarr['fileType'] == 'file') {
            $($img_str).attr("id", "anc_imgview_" + hid)
                    .attr("href", rarr['fileURL'])
                    .attr("target", "_blank")
                    .addClass("btn btn-success btn-mini")
                    .html("View");
            $("#img_view_" + hid).html($img_str);
        } else {
            $($img_str).attr("id", "anc_imgview_" + hid)
                    .attr("href", rarr['fileURL'])
                    .addClass("fancybox-image")
                    .html("<img src='" + rarr['fileURL'] + "' alt='Image' width='" + rarr['width'] + "' height='" + rarr['height'] + "'/>");
            $("#img_view_" + hid).html($img_str);
            /*
             $('#anc_imgview_' + hid).qtip({
             content: "<img src='" + rarr['fileURL'] + "' alt='Image' />"
             });
             */
        }
        $("#img_del_" + hid).html(del_btn);
        initializeFancyBoxEvents($("#img_view_" + hid));
    }
}
//related to grid sort columns activation
function activateGridSortColumns(grid_id) {
    var sortname = $("#" + grid_id).jqGrid('getGridParam', "sortname");
    var sortorder = $("#" + grid_id).jqGrid('getGridParam', "sortorder");
    if ($("#" + grid_id).jqGrid('getGridParam', "multiSort")) {
        $("div[id^='jqgh_" + grid_id + "'] span.s-ico").css("display", "none");
        if (sortname && sortorder) {
            var sortname_arr = sortname.split(",");
            var sortorder_arr = sortorder.split(",");
            for (var i in sortname_arr) {
                var $show_sort_div = $("div[id='jqgh_" + grid_id + "_" + sortname_arr[i] + "'] span.s-ico");
                if (!$show_sort_div) {
                    return false;
                }
                $($show_sort_div).css("display", "inline");
                if (sortorder_arr[i] == "asc") {
                    $($show_sort_div).find("span[sort='asc']").removeClass("ui-state-disabled");
                    $($show_sort_div).find("span[sort='desc']").addClass("ui-state-disabled");
                } else {
                    $($show_sort_div).find("span[sort='asc']").addClass("ui-state-disabled");
                    $($show_sort_div).find("span[sort='desc']").removeClass("ui-state-disabled");
                }
            }
        }
    } else {
        $("div[id^='jqgh_" + grid_id + "'] span.s-ico").css("display", "none");
        var $show_sort_div = $("div[id='jqgh_" + grid_id + "_" + sortname + "'] span.s-ico");
        if (!$show_sort_div) {
            return false;
        }
        $($show_sort_div).css("display", "inline");
        if (sortorder == "asc") {
            $($show_sort_div).find("span[sort='asc']").removeClass("ui-state-disabled");
            $($show_sort_div).find("span[sort='desc']").addClass("ui-state-disabled");
        } else {
            $($show_sort_div).find("span[sort='asc']").addClass("ui-state-disabled");
            $($show_sort_div).find("span[sort='desc']").removeClass("ui-state-disabled");
        }
    }
}
function getColumnsPosition(key, grid_id) {
    var data = getLocalStore(key, true);
    if (!data) {
        return false;
    }
    try {
        var data = parseJSONString(data);
        if (!data.columns) {
            return false;
        }
        var arr = data.columns;
        if (!$.isArray(arr) || arr.length == 0) {
            return false;
        }
        $("#" + grid_id).jqGrid("remapColumns", arr, true);
    } catch (e) {

    }
}
function setColumnsPosition(key, data, grid_id, tcm) {
    var gcm = $("#" + grid_id).jqGrid('getGridParam', 'colModel');
    var arr = [], g_name, t_name, ind = 0, i, j;
    for (i = 0; i < gcm.length; i++) {
        g_name = gcm[i]['name'];
        if ($.inArray(g_name, ['cb', 'rn', 'subgrid', 'prec']) != -1) {
            arr.push(i);
            ind++;
            continue;
        }
        for (j = 0; j < tcm.length; j++) {
            t_name = tcm[j]['name'];
            if (t_name == g_name) {
                arr.push(j + ind);
                break;
            }
        }
    }
    var str = {"columns": arr};
    setLocalStore(key, JSON.stringify(str), true);
}
function getColumnsChoosen(key, grid_id) {
    var data = getLocalStore(key, true);
    if (!data) {
        return false;
    }
    try {
        var data = parseJSONString(data);
        if (!data.columns) {
            return false;
        }
        var arr = data.columns;
        if (!$.isArray(arr) || arr.length == 0) {
            return false;
        }
        var gcm = $("#" + grid_id).jqGrid('getGridParam', 'colModel');
        for (var i = 0; i < gcm.length; i++) {
            if ($.inArray(gcm[i]['name'], arr) != -1) {
                if (gcm[i]['hidden'] !== true) {
                    $("#" + grid_id).jqGrid('hideCol', gcm[i]['name']);
                }
            } else {
                if (gcm[i]['hidden'] === true) {
                    $("#" + grid_id).jqGrid('showCol', gcm[i]['name']);
                }
            }
        }
    } catch (e) {

    }
}
function setColumnsChoosen(key, grid_id) {
    var gcm = $("#" + grid_id).jqGrid('getGridParam', 'colModel');
    var arr = [], g_name, i;
    for (i = 0; i < gcm.length; i++) {
        g_name = gcm[i]['name'];
        if ($.inArray(g_name, ['cb', 'rn', 'subgrid', 'prec']) != -1) {
            continue;
        }
        if (gcm[i]['hidden'] === true) {
            arr.push(g_name);
        }
    }
    var str = {"columns": arr};
    setLocalStore(key, JSON.stringify(str), true);
}
function getColumnsWidth(key, grid_id, tcm) {
    var data = getLocalStore(key, true);
    if (!data) {
        return false;
    }
    try {
        var data = parseJSONString(data);
        if (!data.columns) {
            return false;
        }
        var arr = data.columns;
        if (!$.isPlainObject(arr)) {
            return false;
        }
        var i, t_name, n_width;
        for (i = 0; i < tcm.length; i++) {
            t_name = tcm[i]['name'];
            if ($.inArray(t_name, ['cb', 'rn', 'subgrid', 'prec']) != -1) {
                continue;
            }
            n_width = parseInt(arr[t_name]);
            if (isNaN(n_width) || !n_width) {
                continue;
            }
            if (n_width > el_tpl_settings.grid_column_width) {
                tcm[i]['width'] = n_width;
            } else {
                tcm[i]['width'] = el_tpl_settings.grid_column_width;
            }
        }
    } catch (e) {

    }
}
function setColumnsWidth(key, grid_id) {
    var gcm = $("#" + grid_id).jqGrid('getGridParam', 'colModel');
    var arr = {}, g_name, i;
    for (i = 0; i < gcm.length; i++) {
        g_name = gcm[i]['name'];
        if ($.inArray(g_name, ['cb', 'rn', 'subgrid', 'prec']) != -1) {
            continue;
        }
        arr[g_name] = gcm[i]['width'];
    }
    var str = {"columns": arr};
    setLocalStore(key, JSON.stringify(str), true);
}
function checkColumnsWidth(key, grid_id) {
    var data = getLocalStore(key, true);
    if (!data) {
        setColumnsWidth(key, grid_id);
    }
    try {
        var data = parseJSONString(data);
        var arr = data.columns;
        if (!$.isPlainObject(arr)) {
            setColumnsWidth(key, grid_id);
        }
    } catch (e) {

    }
}
function createCustomGridButton(settings, grid_id, pager_id, afterId) {
    if (typeof settings.callback != "undefined" && settings.callback == "0") {
        return afterId;
    }
    var custom_btn_id = settings.name + "_" + grid_id;
    jQuery("#" + grid_id).navButtonAdd('#' + pager_id, {
        caption: settings.text,
        title: settings.title,
        alert: settings.alert,
        confirm: settings.confirm,
        buttonicon: 'ico-cutom-btn ' + ((settings.icon) ? settings.icon : 'ui-icon-newwin'),
        buttonicon_p: "uigrid-custom-common uigrid-custom-btn",
        onClickButton: function (e, p) {
            var fids = filterGridSelectedIDs(this);
            adminCustomButtonAction(fids, p.alert, p.confirm, grid_id, el_grid_settings['extra_qstr'], e, p);
        },
        position: "last",
        id: custom_btn_id,
        afterButtonId: afterId
    });
    jQuery("#" + grid_id).navButtonAdd("#" + grid_id + "_toppager_left", {
        caption: settings.text,
        title: settings.title,
        alert: settings.alert,
        confirm: settings.confirm,
        buttonicon: 'ico-cutom-btn ' + ((settings.icon) ? settings.icon : 'ui-icon-newwin'),
        buttonicon_p: "uigrid-custom-common uigrid-custom-btn",
        onClickButton: function (e, p) {
            var fids = filterGridSelectedIDs(this);
            adminCustomButtonAction(fids, p.alert, p.confirm, grid_id, el_grid_settings['extra_qstr'], e, p);
        },
        position: "last",
        id: custom_btn_id + "_top",
        afterButtonId: (afterId) ? afterId + "_top" : ""
    });
    return custom_btn_id;
}
function filterGridSelectedIDs(obj) {
    var sids = $(obj).getGridParam('selarrrow')
    var hids = $(obj).getGridParam('hiderecords');
    var fids = $.isArray(hids) ? $(sids).not(hids).get() : sids;
    return fids;
}
function reloadListGrid(grid_id, options, defsort, grid_settings) {
    if (defsort == 1) {
        $("#" + grid_id).setGridParam({defaultsort: "Yes"});
    } else if (defsort == 2) {
        $("#" + grid_id).setGridParam({sortname: grid_settings.default_sort, sortorder: grid_settings.sort_order, defaultsort: "Yes"});
    }
    if (options) {
        $("#" + grid_id).trigger('reloadGrid', options);
    } else {
        $("#" + grid_id).trigger('reloadGrid');
    }
}
function setGridViewSortLayout(index, sortorder, col_names) {
    for (var i in col_names) {
        if (col_names[i]['name'] == index) {
            $(".listsort-container").find(".sort-item-label").html(col_names[i]['label']);
            var sobj = $(".listsort-container").find(".sort-item-icon");
            if (sortorder == 'desc') {
                $(sobj).addClass("fa-sort-amount-desc").removeClass("fa-sort-amount-asc");
            } else {
                $(sobj).addClass("fa-sort-amount-asc").removeClass("fa-sort-amount-desc");
            }
            break;
        }
    }
}
function loadTopFilterData(grid_id) {
    var pD, fR;
    pD = $("#" + grid_id).getGridParam("postData");
    if (pD.filters) {
        fR = parseJSONString(pD.filters);
        if (fR && fR.range) {
            if ($("select.topfilter-ctrl").length) {
                if ($.isArray(fR.range.val)) {
                    $("select.topfilter-ctrl").attr("aria-top-val", JSON.stringify(fR.range.val));
                } else {
                    $("select.topfilter-ctrl").attr("aria-top-val", fR.range.val);
                }
                if ($.isArray(fR.range.txt)) {
                    $("select.topfilter-ctrl").attr("aria-top-txt", JSON.stringify(fR.range.txt));
                }
                $("select.topfilter-ctrl option[aria-filter-name='" + fR.range.key + "']").attr("selected", true);
                $("select.topfilter-ctrl").trigger("change");
            }
        }
    }
}
function refreshLeftSearchPanel(grid_id) {
    if (!$('#left_search_panel').length) {
        return false;
    }
    $("#input_left_search").val("");
    $('#left_search_items').html('<div align="center" class="left-search-loader"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></div>');
    $.ajax({
        url: el_grid_settings.search_refresh_url,
        type: 'POST',
        data: {"tempalte": "Yes"},
        success: function (response) {
            $('#left_search_items').html(response);
            initLeftPanelAutocomplete(grid_id);
            initializejQueryChosenEvents($("#left_search_panel"));
        }
    });
    return true;
}