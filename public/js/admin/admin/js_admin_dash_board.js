//related to charts
$(document).on("click", ".chart-search-icon", function () {
    var bdata, js_grid_id, cid;
    cid = $(this).attr("aria-chart-id");
    if (!DB_pivot_data_JSON[cid]) {
        if (!DB_data_list_JSON[cid]) {
            alert("Search not found..!");
            return false;
        } else {
            bdata = DB_data_list_JSON[cid];
        }
    } else {
        bdata = DB_pivot_data_JSON[cid];
    }

    js_grid_id = bdata['gridID'];
    $("#" + js_grid_id).jqGrid('searchGrid', {
        multipleSearch: true,
        width: 600,
        height: 275,
        closeOnEscape: true,
        modal: true,
        closeAfterSearch: true,
        onSearch: function () {
            DB_block_config_JSON[cid]['ajaxUpdate'] = "No";
            clearInterval(DB_block_config_JSON[cid]['clearID']);
        },
        onReset: function () {
            DB_block_config_JSON[cid]['ajaxUpdate'] = "No";
            clearInterval(DB_block_config_JSON[cid]['clearID']);
        }
    });
});
$(document).on("click", ".chart-refresh-icon", function () {
    var bdata, js_grid_id, cid, post_data;
    cid = $(this).attr("aria-chart-id");
    $("#tfilter_" + cid).attr("data-label-refresh", "true");
    setDateRangeFilterLabel(cid);
    if (DB_block_config_JSON[cid]['ajaxUpdate'] == "Yes") {
        var DASHBAORD_AUTOLOAD_URL = admin_url + cus_enc_url_json["autoload_dashboard"];
        $("#board_loader_icon_" + cid).show();
        $.ajax({
            url: DASHBAORD_AUTOLOAD_URL,
            type: 'POST',
            data: {
                "code": $("#vBoardCode_" + cid).val()
            },
            success: function (data) {
                if (data) {
                    clearInterval(DB_block_config_JSON[cid]['clearID']);
                    initDashBoardAutoLoad(cid);
                    autoLoadDashboardBlock(cid, data, DB_block_config_JSON[cid]['chartType']);
                }
            }
        });
    } else {
        if (!DB_pivot_data_JSON[cid]) {
            if (!DB_data_list_JSON[cid]) {
                alert("Filters not found..!");
                return false;
            } else {
                bdata = DB_data_list_JSON[cid];
            }
        } else {
            bdata = DB_pivot_data_JSON[cid];
        }
        js_grid_id = bdata['gridID'];
        $("#" + js_grid_id).jqGrid('setGridParam', {search: false, resetsearch: true});
        post_data = $("#" + js_grid_id).jqGrid('getGridParam', 'postData');
        $.extend(post_data, {filters: ""});
        $("#" + js_grid_id).trigger("reloadGrid", [{page: 1}]);
        DB_block_config_JSON[cid]['ajaxUpdate'] = "Yes";
    }
});
$(document).on("click", ".chart-back-link", function () {
    var cid = $(this).attr("aria-chart-id");
    var ctype = $("#chart_preview_" + cid).attr("aria-chart-type");
    generateBoardContent(cid, ctype, true);
});
$(document).on("change", ".daggr-filter", function () {
    var caggr = $(this).val();
    var cid = $(this).attr("aria-chart-id");
    var ctype = $("#chart_preview_" + cid).attr("aria-chart-type");
    $("#chart_preview_" + cid).attr("aria-chart-aggr", caggr);
    generateBoardContent(cid, ctype, true);
});
$(document).on("change", ".dpie-filter", function () {
    var cid = $(this).attr("aria-chart-id");
    var crow = $(this).attr("aria-chart-row");
    var ctype = $("#chart_preview_" + cid).attr("aria-chart-type");
    var caggr = $("#chart_preview_" + cid).attr("aria-chart-aggr");
    if (ctype == "donut") {
        var settings_arr = getDonutChartOptions(cid, caggr, crow);
        plotDonutChartDiagram(settings_arr);
    } else {
        var settings_arr = getPieChartOptions(cid, caggr, crow);
        plotPieChartDiagram(settings_arr);
    }
});
$(document).on("click", ".bar-chart-child", function () {
    var cid = $(this).attr("aria-chart-id");
    var crow = $(this).attr("aria-chart-row");
    var caggr = $("#chart_preview_" + cid).attr("aria-chart-aggr");
    $("#chart_preview_" + cid).attr("aria-chart-type", "bar");
    $("#dbacklink_" + cid).show();
    var settings_arr = getBarChartOptions(cid, caggr, crow);
    plotBarChartDiagram(settings_arr);
});
$(document).on("click", ".pie-chart-child", function () {
    var cid = $(this).attr("aria-chart-id");
    var crow = $(this).attr("aria-chart-row");
    var caggr = $("#chart_preview_" + cid).attr("aria-chart-aggr");
    $("#chart_preview_" + cid).attr("aria-chart-type", "pie");
    $("#dbacklink_" + cid).show();
    var settings_arr = getPieChartOptions(cid, caggr, crow);
    plotPieChartDiagram(settings_arr);
});
$(document).on("click", ".donut-chart-child", function () {
    var cid = $(this).attr("aria-chart-id");
    var crow = $(this).attr("aria-chart-row");
    var caggr = $("#chart_preview_" + cid).attr("aria-chart-aggr");
    $("#chart_preview_" + cid).attr("aria-chart-type", "donut");
    $("#dbacklink_" + cid).show();
    var settings_arr = getDonutChartOptions(cid, caggr, crow);
    plotDonutChartDiagram(settings_arr);
});
$(document).on("click", ".area-chart-child", function () {
    var cid = $(this).attr("aria-chart-id");
    var crow = $(this).attr("aria-chart-row");
    var caggr = $("#chart_preview_" + cid).attr("aria-chart-aggr");
    $("#chart_preview_" + cid).attr("aria-chart-type", "area");
    $("#dbacklink_" + cid).show();
    var settings_arr = getAreaChartOptions(cid, caggr, crow);
    plotAreaChartDiagram(settings_arr);
});
$(document).on("click", ".line-chart-child", function () {
    var cid = $(this).attr("aria-chart-id");
    var crow = $(this).attr("aria-chart-row");
    var caggr = $("#chart_preview_" + cid).attr("aria-chart-aggr");
    $("#chart_preview_" + cid).attr("aria-chart-type", "line");
    $("#dbacklink_" + cid).show();
    var settings_arr = getLineChartOptions(cid, caggr, crow);
    plotLineChartDiagram(settings_arr);
});
$(document).on("click", ".horizbar-chart-child", function () {
    var cid = $(this).attr("aria-chart-id");
    var crow = $(this).attr("aria-chart-row");
    var caggr = $("#chart_preview_" + cid).attr("aria-chart-aggr");
    $("#chart_preview_" + cid).attr("aria-chart-type", "horizbar");
    $("#dbacklink_" + cid).show();
    var settings_arr = getHorizontalBarChartOptions(cid, caggr, crow);
    plotHorizontalBarChartDiagram(settings_arr);
});
$(document).on("click", ".stackbar-chart-child", function () {
    var cid = $(this).attr("aria-chart-id");
    var crow = $(this).attr("aria-chart-row");
    var caggr = $("#chart_preview_" + cid).attr("aria-chart-aggr");
    $("#chart_preview_" + cid).attr("aria-chart-type", "stackbar");
    $("#dbacklink_" + cid).show();
    var settings_arr = getStackedBarChartOptions(cid, caggr, crow);
    plotStackedBarChartDiagram(settings_arr);
});
$(document).on("click", ".stackhorizbar-chart-child", function () {
    var cid = $(this).attr("aria-chart-id");
    var crow = $(this).attr("aria-chart-row");
    var caggr = $("#chart_preview_" + cid).attr("aria-chart-aggr");
    $("#chart_preview_" + cid).attr("aria-chart-type", "stackhorizbar");
    $("#dbacklink_" + cid).show();
    var settings_arr = getStackedHorizBarChartOptions(cid, caggr, crow);
    plotStackedHorizBarChartDiagram(settings_arr);
});
$(document).on("click", ".autoupdating-chart-child", function () {
    var cid = $(this).attr("aria-chart-id");
    var crow = $(this).attr("aria-chart-row");
    var caggr = $("#chart_preview_" + cid).attr("aria-chart-aggr");
    $("#chart_preview_" + cid).attr("aria-chart-type", "autoupdating");
    $("#dbacklink_" + cid).show();
    var settings_arr = getAutoUpdatingChartOptions(cid, caggr, crow);
    plotAutoUpdatingChartDiagram(settings_arr);
});
function initDashBoardSettings() {
    var hmrg, vmrg, twd, gwd, cpad, eht, ctime;
    hmrg = 5, vmrg = 5, cpad = 5, eht = 2;
    setTimeout(function () {
        $(".dash-board-item").each(function () {
            var lht, hht, cht, bht;
            lht = $(this).height();
            hht = $(this).find(".dash-board-header").height();
            cht = parseInt(lht) - parseInt(hht) - (2 * cpad) - eht;
            if ($(this).find("[id^='content_block']").hasClass("pivot-detail-view")) {
                bht = parseInt(lht) - parseInt(hht) - (2 * cpad) - eht;
            } else {
                bht = parseInt(lht) - parseInt(hht) - 1 - eht;
            }
            $(this).find("[id^='content_block']").height(bht);
            $(this).find("[id^='content_chart']").height(cht);
            $(this).find("[id^='chart_preview']").height(cht);
        });

        if ($("#sidebar").length) {
            twd = $("#dash_board_list").width();
        } else {
            twd = $("#main_content_div").width() - 35;
        }
        twd = Math.floor(twd / 6);
        gwd = twd - hmrg - vmrg;
        el_general_settings.dashboard_grid = $("#dash_board_list").gridster({
            widget_base_dimensions: [gwd, 50],
            max_cols: 6,
            min_cols: 1,
            max_size_x: 6,
            widget_margins: [hmrg, vmrg],
            draggable: {
                handle: '.dash-board-mover',
                start: function (e, ui, $widget) {
                    if (ctime) {
                        clearTimeout(ctime);
                    }
                },
                stop: function (e, ui, $widget) {
                    $("#widget_position_text").slideDown();
                    ctime = setTimeout(function () {
                        $("#widget_position_text").hide();
                    }, 200000);
                }
            }
        }).data('gridster');

        $(".dash-board-item").each(function () {
            var lht, hht, cht, bht;
            lht = $(this).height();
            hht = $(this).find(".dash-board-header").height();
            cht = parseInt(lht) - parseInt(hht) - (2 * cpad) - eht;
            if ($(this).find("[id^='content_block']").hasClass("pivot-detail-view")) {
                bht = parseInt(lht) - parseInt(hht) - (2 * cpad) - eht;
            } else {
                bht = parseInt(lht) - parseInt(hht) - 1 - eht;
            }
            $(this).find("[id^='content_block']").height(bht);
            $(this).find("[id^='content_chart']").height(cht);
            $(this).find("[id^='chart_preview']").height(cht);
        });

        for (var i in DB_pivot_data_JSON) {
            if (!DB_pivot_data_JSON[i]['dbID']) {
                continue;
            }
            resizeDashboardGrid(DB_pivot_data_JSON[i]['dbID']);
        }
        for (var i in DB_data_list_JSON) {
            if (!DB_data_list_JSON[i]['dbID']) {
                continue;
            }
            resizeDashboardGrid(DB_data_list_JSON[i]['dbID']);
        }
        setTimeout(function () {
            hideMainLoader();
        }, 200);
    }, 500);

    $("#widget_position_save").click(function () {
        var sarr = el_general_settings.dashboard_grid.serialize();
        var cobj = [], col, row, size_x, size_y, bid;
        for (var i in sarr) {
            row = sarr[i]['row'];
            col = sarr[i]['col'];
            size_x = sarr[i]['size_x'];
            size_y = sarr[i]['size_y'];
            bid = $("[id^='board_item'][data-row='" + row + "'][data-col='" + col + "'][data-sizex='" + size_x + "'][data-sizey='" + size_y + "']").find("input[name^='iDashBoardId']").val();
            cobj.push({"chart_id": bid, "chart_sequence": sarr[i]})
        }
        updateDashboardSequence(cobj);
    });

    $(window).bind('resize', function () {
        var chartResize = ['bar', 'pie', 'donut', 'area', 'line', 'horizbar', 'stackbar', 'stackhorizbar', 'autoupdating'];
        $("[id^='board_block']").each(function () {
            var type = $(this).attr("rel");
            var bid = $(this).attr("id").split("_")[2];
            if ($.inArray(type, chartResize) != "-1") {
                //generateBoardContent(bid, type, true);
            }
        });

    }).trigger("resize");

    $('.toggle-extra-options').click(function () {
        $('.wrapper-dropdown').removeClass('active');
        $('.dropdown').hide();
        var $js_wrp_id = $(this).attr('aria-id');
        $('#wrapper_dropdown_' + $js_wrp_id).addClass('active');
        $('#options_dropdown_' + $js_wrp_id).show();
    });

    $(document).click(function () {
        $('.wrapper-dropdown').removeClass('active');
        $('.dropdown').hide();
    });
}
function initDashBoardFilters() {
    for (var i in DB_block_config_JSON) {
        var bid = DB_block_config_JSON[i]['id'];
        if (DB_block_config_JSON[i]['dateFilter'] == "Yes") {
            var fld = DB_block_config_JSON[i]['filterField'];
            $("#dfilter_" + bid).show();
            $("#drefresh_" + bid).show();
            initFilterDSDateRangePicker(bid, fld);
        }
        if (DB_block_config_JSON[i]['autoUpdate'] == "Yes") {
            $("#drefresh_" + bid).show();
            DB_block_config_JSON[i]['clearID'] = initDashBoardAutoLoad(bid);
        }
    }
}
function initDashBoardAutoLoad(bid) {
    var sid = setInterval(function () {
        var code = bid;
        var DASHBAORD_AUTOLOAD_URL = admin_url + cus_enc_url_json["autoload_dashboard"];
        $("#board_loader_icon_" + code).show();
        $.ajax({
            url: DASHBAORD_AUTOLOAD_URL,
            type: 'POST',
            data: {
                "code": $("#vBoardCode_" + code).val()
            },
            success: function (data) {
                if (data) {
                    autoLoadDashboardBlock(code, data, DB_block_config_JSON[code]['chartType']);
                }
            }
        });
    }, el_tpl_settings.dashboard_auto_time);
    return sid;
}
function autoLoadDashboardBlock(bid, data, type) {
    switch (type) {
        case 'Grid List':
            DB_data_list_JSON[bid] = $.parseJSON(data);
            var js_grid_id = DB_data_list_JSON[bid]['gridID'];
            var result = $("#" + js_grid_id).jqGrid().GridDestroy();
            $("#dbgrid2_" + bid).html('<div id="dbpager2_' + bid + '"></div><table id="dblist2_' + bid + '"></table>');
            callDashBoardGridListing(DB_data_list_JSON[bid]);
            break;
        case 'Pivot':
            DB_pivot_data_JSON[bid] = $.parseJSON(data);
            var js_grid_id = DB_pivot_data_JSON[bid]['gridID'];
            var result = $("#" + js_grid_id).jqGrid().GridDestroy();
            $("#dbgrid2_" + bid).html('<div id="dbpager2_' + bid + '"></div><table id="dblist2_' + bid + '"></table>');
            callDashBoardPivotListing(DB_pivot_data_JSON[bid]);
            break;
        case 'Detail View':
            $("#content_block_" + bid).html(data);
            break;
    }
    $("#board_loader_icon_" + bid).hide();    
}
function initFilterDSDateRangePicker(bid, fld) {
    var date_fld, filter_val, filter_arr = [], d_ranges, d_months, d_weeks;
    date_fld = fld;
    filter_val = DB_block_config_JSON[bid]['filterValue'];
    if (filter_val) {
        filter_arr = filter_val.split(" to ");
    }
    d_ranges = getRangePickerQuickList();
    d_months = getRangePickerMonthNames();
    d_weeks = getRangePickerWeekNames();
    var options = {
        ranges: d_ranges,
        opens: 'left',
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        showDropdowns: true,
        locale: {
            format: el_tpl_settings.admin_formats.date.moment,
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
        autoUpdateInput: false
    };
    if (filter_arr[0]) {
        options.startDate = moment(filter_arr[0], "YYYY-MM-DD").format(el_tpl_settings.admin_formats.date.moment);
        if (filter_arr[1]) {
            options.endDate = moment(filter_arr[1], "YYYY-MM-DD").format(el_tpl_settings.admin_formats.date.moment);
        } else {
            options.endDate = moment(filter_arr[0], "YYYY-MM-DD").format(el_tpl_settings.admin_formats.date.moment);
        }     
    }
    $("#dfilter_" + bid).daterangepicker(options);
    if (filter_arr[0]) {
        $("#tfilter_" + bid).attr("data-label-refresh", "true");
        setDateRangeFilterLabel(bid);
    }
    $("#dfilter_" + bid).on('apply.daterangepicker', function (ev, picker) {
        $("#tfilter_" + bid).attr("data-label-refresh", "false");
        filterDSBlockResponse(bid, date_fld, picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));
        var txt_start = picker.startDate.format(el_tpl_settings.admin_formats.date.moment);
        var txt_end = picker.endDate.format(el_tpl_settings.admin_formats.date.moment);
        $("#tfilter_" + bid).html(txt_start + " to " + txt_end).show();
    });
    $("#dfilter_" + bid).on('cancel.daterangepicker', function (ev, picker) {
        $("#tfilter_" + bid).attr("data-label-refresh", "false");
        filterDSBlockResponse(bid, date_fld, '', '');
        $("#tfilter_" + bid).html("").hide();
    });
}
function setDateRangeFilterLabel(bid) {
    var filter_val, filter_arr = [];
    filter_val = DB_block_config_JSON[bid]['filterValue'];
    if (filter_val) {
        filter_arr = filter_val.split(" to ");
    }
    var lable_txt = '';
    if (filter_arr[0]) {
        lable_txt += moment(filter_arr[0], "YYYY-MM-DD").format(el_tpl_settings.admin_formats.date.moment);
        $('#dfilter_' + bid).data('daterangepicker').setStartDate(moment(filter_arr[0], "YYYY-MM-DD").format(el_tpl_settings.admin_formats.date.moment));
    }
    if (filter_arr[1]) {
        lable_txt += " to " + moment(filter_arr[1], "YYYY-MM-DD").format(el_tpl_settings.admin_formats.date.moment);
        $('#dfilter_' + bid).data('daterangepicker').setEndDate(moment(filter_arr[1], "YYYY-MM-DD").format(el_tpl_settings.admin_formats.date.moment));
    }
    $("#tfilter_" + bid).html(lable_txt).show();
}
function filterDSBlockResponse(bid, date_fld, from_date, to_date) {
    DB_block_config_JSON[bid]['ajaxUpdate'] = "Yes";
    clearInterval(DB_block_config_JSON[bid]['clearID']);
    var DASHBAORD_FILTER_URL = admin_url + cus_enc_url_json["filter_dashboard"];
    $("#board_loader_icon_" + bid).show();
    $.ajax({
        url: DASHBAORD_FILTER_URL,
        type: 'POST',
        data: {
            "code": $("#vBoardCode_" + bid).val(),
            "field": date_fld,
            "from_date": from_date,
            "to_date": to_date
        },
        success: function (data) {
            if (data) {
                autoLoadDashboardBlock(bid, data, DB_block_config_JSON[bid]['chartType']);
            }
        }
    });
}
function initSearchDSDatePicker(elem, opt) {
    var date_obj = {}, d_format;
    if (opt && opt['attr'] && opt['attr']['aria-grid-id']) {
        d_format = opt['attr']["aria-date-format"];
    } else {
        d_format = $(elem).attr("aria-date-format");
    }
    date_obj['dateFormat'] = d_format;
    activeDSSearchPicker($(elem), "date", date_obj);
}
function initSearchDSDateTimePicker(elem, opt) {
    var date_obj = {}, d_format, t_format, e_time, e_ampm;
    if (opt && opt['attr'] && opt['attr']['aria-grid-id']) {
        d_format = opt['attr']["aria-date-format"];
        t_format = opt['attr']['aria-time-format'];
        e_time = opt['attr']['aria-enable-sec'];
        e_ampm = opt['attr']['aria-enable-ampm'];
    } else {
        d_format = $(elem).attr("aria-date-format");
        t_format = $(elem).attr('aria-time-format');
        e_time = $(elem).attr('aria-enable-sec');
        e_ampm = $(elem).attr('aria-enable-ampm');
    }
    date_obj['dateFormat'] = d_format;
    date_obj['timeFormat'] = t_format;
    date_obj['showSecond'] = e_time;
    date_obj['ampm'] = e_ampm;
    activeDSSearchPicker($(elem), "dateTime", date_obj);
}
function initSearchDSTimePicker(elem, opt) {
    var date_obj = {}, t_format, e_time, e_ampm;
    if (opt && opt['attr'] && opt['attr']['aria-grid-id']) {
        t_format = opt['attr']['aria-time-format'];
        e_time = opt['attr']['aria-enable-sec'];
        e_ampm = opt['attr']['aria-enable-ampm'];
    } else {
        t_format = $(elem).attr('aria-time-format');
        e_time = $(elem).attr('aria-enable-sec');
        e_ampm = $(elem).attr('aria-enable-ampm');
    }
    date_obj['timeFormat'] = t_format;
    date_obj['showSecond'] = e_time;
    date_obj['ampm'] = e_ampm;
    activeDSSearchPicker($(elem), 'time', date_obj);
}
function activeDSSearchPicker(eleObj, type, jfmtArr) {
    switch (type) {
        case 'date' :
            var min_max_obj = {};
            var base_obj = {
                dateFormat: jfmtArr['dateFormat'],
                showOn: 'focus',
                changeMonth: true,
                changeYear: true,
                yearRange: 'c-100:c+100',
                onClose: function (dateText, inst) {
                    $(eleObj).trigger('change');
                }
            }
            var final_obj = $.extend({}, min_max_obj, base_obj);
            $(eleObj).datepicker(final_obj);
            break;
        case 'time' :
            $(eleObj).timepicker({
                timeFormat: jfmtArr['timeFormat'],
                showSecond: (jfmtArr['showSecond'] == "true" || jfmtArr['showSecond'] == '1' || jfmtArr['showSecond'] === true) ? true : false,
                ampm: (jfmtArr['ampm'] == "true" || jfmtArr['ampm'] == '1' || jfmtArr['ampm'] === true) ? true : '',
                showOn: 'focus',
                onClose: function (dateText, inst) {
                    $(eleObj).trigger('change');
                }
            });
            break;
        case 'dateTime' :
            var min_max_obj = {};
            var base_obj = {
                dateFormat: jfmtArr['dateFormat'],
                timeFormat: jfmtArr['timeFormat'],
                showSecond: (jfmtArr['showSecond'] == "true" || jfmtArr['showSecond'] == '1' || jfmtArr['showSecond'] === true) ? true : false,
                ampm: (jfmtArr['ampm'] == "true" || jfmtArr['ampm'] == '1' || jfmtArr['ampm'] === true) ? true : '',
                showOn: 'focus',
                changeMonth: true,
                changeYear: true,
                yearRange: 'c-100:c+100',
                onClose: function (dateText, inst) {
                    $(eleObj).trigger('change');
                }
            }
            var final_obj = $.extend({}, min_max_obj, base_obj);
            $(eleObj).datetimepicker(final_obj);
            break;
    }
    if (el_general_settings.mobile_platform) {
        $(eleObj).attr('readonly', true);
    }
}
function updateDashboardSequence(cobj) {
    var DASHBAORD_SEQUENCE_URL = admin_url + cus_enc_url_json["dashboard_sequence"];
    $.ajax({
        url: DASHBAORD_SEQUENCE_URL,
        type: 'POST',
        data: {
            "id": $("#iDashBoardPageId").val(),
            "tab": $("#iDashBoardTabId").val(),
            "obj": cobj,
            "type": "block_sequence"
        },
        success: function (data) {
            $("#widget_position_text").slideUp();
            var res_arr = $.parseJSON(data);
            var jmgcls = 1;
            if (res_arr.success == "0") {
                jmgcls = 0;
            }
            Project.setMessage(res_arr.message, jmgcls);
        }
    });
}
function updateDashboardChart(cobj) {
    var DASHBAORD_SEQUENCE_URL = admin_url + cus_enc_url_json["dashboard_sequence"];
    $.ajax({
        url: DASHBAORD_SEQUENCE_URL,
        type: 'POST',
        data: {
            "id": $("#iDashBoardPageId").val(),
            "tab": $("#iDashBoardTabId").val(),
            "obj": cobj,
            "type": "block_type"
        },
        success: function (data) {

        }
    });
}

function toggleBoardContent(bid, type) {
    generateBoardContent(bid, type);
    updateDashboardChart([{"chart_id": bid, "chart_type": type}])
}
function generateBoardContent(bid, type, flag) {
    if (type == $("#board_block_" + bid).attr("rel") && flag !== true) {
        return false;
    }
    $("#board_block_" + bid).attr("rel", type);
    if (!DB_pivot_data_JSON[bid]) {
        $('#chart_preview_' + bid).html('<div class="errormsg" align="center">' + js_lang_label.GENERIC_NO_DATA_FOUND_C46_C46_C33 + '</div>')
        return false;
    }

    $("#dbacklink_" + bid).hide();
    $("#dpiecombo_" + bid).hide();
    if (!$("#dpieselect_" + bid).length) {
        makePieCategoryCombo(bid);
    }
    if (!$("#daggrselect_" + bid).length) {
        makeAggrCategoryCombo(bid);
    }
    if ($.inArray(type, ['bar', 'pie', 'donut', 'area', 'line', 'horizbar', 'stackbar', 'stackhorizbar', 'autoupdating']) != "-1") {
        $("#chart_preview_" + bid).attr("aria-chart-type", type);
        $("#daggrcombo_" + bid).show();
    } else {
        $("#daggrcombo_" + bid).hide();
    }
    if (DB_pivot_data_JSON[bid]['searchMode'] && DB_pivot_data_JSON[bid]['searchMode'] == "Yes") {
        $("#dsearch_" + bid).show();
        $("#drefresh_" + bid).show();
    } else {
        $("#dsearch_" + bid).hide();
        $("#drefresh_" + bid).hide();
    }
    if (DB_pivot_data_JSON[bid]['dateFilter'] && DB_pivot_data_JSON[bid]['dateFilter'] == "Yes") {
        $("#dfilter_" + bid).show();
        $("#drefresh_" + bid).show();
    } else {
        $("#dfilter_" + bid).hide();
        $("#drefresh_" + bid).hide();
    }
    if (DB_block_config_JSON[bid]['autoUpdate'] == "Yes") {
        $("#drefresh_" + bid).show();
    }
    var settings_arr, aggr;
    aggr = $("#chart_preview_" + bid).attr("aria-chart-aggr");
    switch (type) {
        case 'pivot':
            $("#content_chart_" + bid).hide();
            $("#content_block_" + bid).show();
            resizeDashboardGrid(bid);
            break;
        case 'bar':
            $("#content_block_" + bid).hide();
            $("#content_chart_" + bid).show();
            settings_arr = getBarChartOptions(bid, aggr);
            plotBarChartDiagram(settings_arr);
            break;
        case 'pie':
            $("#content_block_" + bid).hide();
            $("#content_chart_" + bid).show();
            settings_arr = getPieChartOptions(bid, aggr);
            plotPieChartDiagram(settings_arr);
            break;
        case 'donut':
            $("#content_block_" + bid).hide();
            $("#content_chart_" + bid).show();
            settings_arr = getDonutChartOptions(bid, aggr);
            plotDonutChartDiagram(settings_arr);
            break;
        case 'area':
            $("#content_block_" + bid).hide();
            $("#content_chart_" + bid).show();
            settings_arr = getAreaChartOptions(bid, aggr);
            plotAreaChartDiagram(settings_arr);
            break;
        case 'line':
            $("#content_block_" + bid).hide();
            $("#content_chart_" + bid).show();
            settings_arr = getLineChartOptions(bid, aggr);
            plotLineChartDiagram(settings_arr);
            break;
        case 'horizbar':
            $("#content_block_" + bid).hide();
            $("#content_chart_" + bid).show();
            settings_arr = getHorizontalBarChartOptions(bid, aggr);
            plotHorizontalBarChartDiagram(settings_arr);
            break;
        case 'stackbar':
            $("#content_block_" + bid).hide();
            $("#content_chart_" + bid).show();
            settings_arr = getStackedBarChartOptions(bid, aggr);
            plotStackedBarChartDiagram(settings_arr);
            break;
        case 'stackhorizbar':
            $("#content_block_" + bid).hide();
            $("#content_chart_" + bid).show();
            settings_arr = getStackedHorizBarChartOptions(bid, aggr);
            plotStackedHorizBarChartDiagram(settings_arr);
            break;
        case 'autoupdating':
            $("#content_block_" + bid).hide();
            $("#content_chart_" + bid).show();
            settings_arr = getAutoUpdatingChartOptions(bid, aggr);
            plotAutoUpdatingChartDiagram(settings_arr);
            break;
    }
}

function makePieCategoryCombo(bid) {
    if (!DB_pivot_data_JSON[bid]) {
        return false;
    }
    var bdata = DB_pivot_data_JSON[bid];
    var js_grid_id = bdata['gridID'];
    var result = $("#" + js_grid_id).jqGrid("getGridParam", "dataSet");
    if (!result) {
        return false;
    }
    if (!result.columns) {
        return false;
    }
    var columns = result.columns;
    var str = '<select name="dpieselect_' + bid + '" id="dpieselect_' + bid + '" class="dfilter-combo dpie-filter" aria-chart-id="' + bid + '">';
    for (var i in columns) {
        str += '<option value="' + columns[i].titleText + '">' + columns[i].titleText + '</option>';
    }
    str += '</select>';
    $("#dpiecombo_" + bid).html(str);
    return true;
}
function makeAggrCategoryCombo(bid) {
    if (!DB_pivot_data_JSON[bid]) {
        return false;
    }
    var bdata = DB_pivot_data_JSON[bid];
    var js_grid_id = bdata['gridID'];
    var result = $("#" + js_grid_id).jqGrid("getGridParam", "dataSet");
    if (!result) {
        return false;
    }
    if (!result.sums || result.sums.length < 2) {
        return false;
    }
    var sums = result.sums;
    var str = '<select name="daggrselect_' + bid + '" id="daggrselect_' + bid + '" class="dfilter-combo daggr-filter" aria-chart-id="' + bid + '">';
    for (var i in sums) {
        str += '<option value="' + i + '">' + sums[i].label + '</option>';
    }
    str += '</select>';
    $("#daggrcombo_" + bid).html(str);
    return true;
}

function getBarChartOptions(bid, aggr, filt) {
    try {
        if (!DB_pivot_data_JSON[bid]) {
            throw '-1';
        }
        var bdata = DB_pivot_data_JSON[bid];
        var js_grid_id = bdata['gridID'];
        var result = $("#" + js_grid_id).jqGrid("getGridParam", "dataSet");
        if (!result) {
            throw '-2';
        }
        if (!result.rows) {
            throw '-3';
        }
        aggr = (aggr == 1) ? aggr : 0;
        var js_pivot_mode = bdata['pivotMode'];
        var js_aggr_mode = bdata['aggrMode'];
        var js_x_dimension = bdata['xDimension'];
        var js_aggregates = bdata['aggregates'];
        var rsums = result.sums;
        var settings = {}, oparams = {}, iparams = {}, data = [], pdata, gdata, title, title_link, value, dkey = false;
        var xaixs_label, yaxis_label, show_legend = true, xaxis_ticks = [];
        if (!chartCtrlActivity("bar", "legend")) {
            show_legend = false;
        }
        if (chartCtrlActivity("bar", "xaxis")) {
            xaixs_label = js_x_dimension[0]['label'];
        }
        if (chartCtrlActivity("bar", "yaxis")) {
            yaxis_label = js_aggregates[aggr]['rowTotalsText'];
        }
        oparams = {
            xaxis: {
                axisLabel: xaixs_label,
                tickLength: 0
            },
            yaxis: {
                axisLabel: yaxis_label,
                fmatter: rsums[aggr]['formatter'],
                tickFormatter: function (item, y) {
                    if (y.options.fmatter) {
                        return getChartDataFormat(y.options.fmatter, item);
                    } else {
                        return item;
                    }
                }
            },
            tooltipOpts: {
                fmatter: rsums[aggr]['formatter']
            },
            legend: {
                position: chartCtrlActivity("bar", "position"),
                show: show_legend
            }
        }

        if (bdata['chartOptions'] && bdata['chartOptions']['barChart']) {
            if ("xaxisAngle" in bdata['chartOptions']['barChart']) {
                oparams.xaxis.rotateTicks = parseInt(bdata['chartOptions']['barChart']['xaxisAngle']);
            }
        }

        if (js_pivot_mode == "2R1C") {
            if (!result.columns) {
                throw '-3';
            }
            gdata = result.columns;
            var ticks = [], temp = [], rc, dd = 0, cd = 1, c, d;
            for (var i in gdata) {
                d = [];
                c = gdata[i].titleText;
                dd = 0;
                if (filt) {
                    if (!result.rows[filt] || !result.rows[filt].children) {
                        continue;
                    }
                    dkey = true;
                    pdata = result.rows[filt].children;
                } else {
                    pdata = result.rows;
                }
                for (var j in pdata) {
                    var rc = pdata[j].columns;
                    value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                    d.push([dd, value]);
                    if (!$.isArray(temp[dd])) {
                        title = (pdata[j].titleText) ? pdata[j].titleText : "";
                        if (dkey === true) {
                            title_link = title;
                        } else {
                            title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + j + '" class="bar-chart-child chart-parent-link">' + title + '</a>';
                        }
                        temp[dd] = [j, title_link];
                    }
                    dd++;
                }
                data.push({
                    label: c,
                    data: d,
                    bars: {order: cd}
                });
                cd++;
            }
            for (var k in temp) {
                ticks.push(temp[k]);
            }
            iparams = {
                bars: {
                    barWidth: 0.2
                },
                xaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return label + " - " + getChartDataFormat(fmt, y);
                    }
                }
            }
        } else if (js_pivot_mode == "2R") {
            if (filt) {
                if (!result.rows[filt] || !result.rows[filt].children) {
                    throw '-3';
                }
                pdata = result.rows[filt].children;
                dkey = true;
            } else {
                pdata = result.rows
            }
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                if (dkey === true) {
                    title_link = title;
                } else {
                    title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + i + '" class="bar-chart-child chart-parent-link">' + title + '</a>';
                }
                //data.push([title_link, value]);
                data.push({
                    "data": [[i, value]]
                });
                xaxis_ticks.push([i, title_link]);
            }
            // data = [data];
            iparams = {
                bars: {
                    barWidth: 0.4
                },
                xaxis: {
                    mode: "categories",
                    ticks: xaxis_ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return getChartDataFormat(fmt, y);
                    }
                }
            }
        } else if (js_pivot_mode == "1R1C") {
            if (!result.columns) {
                throw '-3';
            }
            gdata = result.columns;
            var ticks = [], temp = [], rc, dd = 0, cd = 1, c, d;
            for (var i in gdata) {
                d = [];
                c = gdata[i].titleText;
                dd = 0;
                for (var j in result.rows) {
                    rc = result.rows[j].columns;
                    title = (result.rows[j].titleText) ? result.rows[j].titleText : "";
                    value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                    d.push([dd, value]);
                    if (!$.isArray(temp[dd])) {
                        temp[dd] = [j, title];
                    }
                    dd++;
                }
                data.push({
                    label: c,
                    data: d,
                    bars: {order: cd}
                });
                cd++;
            }
            for (var k in temp) {
                ticks.push(temp[k]);
            }
            iparams = {
                bars: {
                    barWidth: 0.2
                },
                xaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return label + " - " + getChartDataFormat(fmt, y);
                    }
                }
            }
        } else {
            pdata = result.rows;
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                //data.push([title, value]);
                data.push({
                    "data": [[i, value]]
                });
                xaxis_ticks.push([i, title]);
            }
            //data = [data];
            iparams = {
                bars: {
                    barWidth: 0.4
                },
                xaxis: {
                    mode: "categories",
                    ticks: xaxis_ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return getChartDataFormat(fmt, y);
                    }
                }
            }
        }

        settings['params'] = $.extend(true, oparams, iparams);
        settings['colors'] = getDSChartColors(bdata);
        settings['data'] = data;
        settings['id'] = bid;

        return settings;
    } catch (e) {
        $('#chart_preview_' + bid).html('<div class="errormsg" align="center">' + js_lang_label.GENERIC_NO_DATA_FOUND + '</div>')
        return false;
    }
}
function getPieChartOptions(bid, aggr, filt) {
    try {
        if (!DB_pivot_data_JSON[bid]) {
            throw '-1';
        }
        var bdata = DB_pivot_data_JSON[bid];
        var js_grid_id = bdata['gridID'];
        var result = $("#" + js_grid_id).jqGrid("getGridParam", "dataSet");
        if (!result) {
            throw '-2';
        }
        if (!result.rows) {
            throw '-3';
        }
        aggr = (aggr == 1) ? aggr : 0;
        var js_pivot_mode = bdata['pivotMode'];
        var js_aggr_mode = bdata['aggrMode'];
        var js_x_dimension = bdata['xDimension'];
        var js_aggregates = bdata['aggregates'];
        var rsums = result.sums;
        var settings = {}, oparams = {}, iparams = {}, data = [], pdata, gdata, title, title_link, value, dkey = false, pval;
        var show_label = true, label_style, show_legend = true;

        if (!chartCtrlActivity("pie", "legend")) {
            show_legend = false;
        }
        if (!chartCtrlActivity("pie", "label")) {
            show_label = false;
        }

        oparams = {
            tooltipOpts: {
                fmatter: rsums[aggr]['formatter']
            },
            legend: {
                position: chartCtrlActivity("pie", "position"),
                show: show_legend
            },
            series: {
                pie: {
                    label: {
                        show: show_label,
                        radius: (chartCtrlActivity("pie", "style") == "s2") ? 3 / 4 : 1
                    }
                },
                fmatter: rsums[aggr]['formatter']
            }
        }

        if (js_pivot_mode == "2R1C") {
            if (!result.columns) {
                throw '-3';
            }
            var rc, c;
            $("#dpiecombo_" + bid).show();
            $("#dpieselect_" + bid).attr("aria-chart-row", filt);
            c = $("#dpieselect_" + bid).val();
            if (filt) {
                if (!result.rows[filt] || !result.rows[filt].children) {
                    throw '-3';
                }
                dkey = true;
                pdata = result.rows[filt].children;
            } else {
                pdata = result.rows;
            }
            for (var j in pdata) {
                rc = pdata[j].columns;
                value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                title = (pdata[j].titleText) ? pdata[j].titleText : "";
                if (dkey === true) {
                    title_link = title;
                } else {
                    title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + j + '" class="pie-chart-child chart-parent-link">' + title + '</a>';
                }
                data.push({
                    label: title_link,
                    data: value
                });
            }
        } else if (js_pivot_mode == "2R") {
            if (filt) {
                if (!result.rows[filt] || !result.rows[filt].children) {
                    throw '-3';
                }
                pdata = result.rows[filt].children;
                dkey = true;
            } else {
                pdata = result.rows
            }
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                if (dkey === true) {
                    title_link = title;
                } else {
                    title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + i + '" class="pie-chart-child chart-parent-link">' + title + '</a>';
                }
                data.push({
                    "label": title_link,
                    "data": value
                });
            }
        } else if (js_pivot_mode == "1R1C") {
            if (!result.columns) {
                throw '-3';
            }
            var rc, c;
            $("#dpiecombo_" + bid).show();
            $("#dpieselect_" + bid).attr("aria-chart-row", filt);
            c = $("#dpieselect_" + bid).val();
            for (var j in result.rows) {
                rc = result.rows[j].columns;
                title = (result.rows[j].titleText) ? result.rows[j].titleText : "";
                value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                data.push({
                    label: title,
                    data: value
                });
            }
        } else {
            pdata = result.rows;
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                data.push({
                    "label": title,
                    "data": value
                });
            }
        }

        settings['params'] = $.extend(true, oparams, iparams);
        settings['colors'] = getDSChartColors(bdata);
        settings['data'] = data;
        settings['id'] = bid;

        return settings;
    } catch (e) {
        $('#chart_preview_' + bid).html('<div class="errormsg" align="center">' + js_lang_label.GENERIC_NO_DATA_FOUND + '</div>')
        return false;
    }
}
function getDonutChartOptions(bid, aggr, filt) {
    try {
        if (!DB_pivot_data_JSON[bid]) {
            throw '-1';
        }
        var bdata = DB_pivot_data_JSON[bid];
        var js_grid_id = bdata['gridID'];
        var result = $("#" + js_grid_id).jqGrid("getGridParam", "dataSet");
        if (!result) {
            throw '-2';
        }
        if (!result.rows) {
            throw '-3';
        }
        aggr = (aggr == 1) ? aggr : 0;
        var js_pivot_mode = bdata['pivotMode'];
        var js_aggr_mode = bdata['aggrMode'];
        var js_x_dimension = bdata['xDimension'];
        var js_aggregates = bdata['aggregates'];
        var rsums = result.sums;
        var settings = {}, oparams = {}, iparams = {}, data = [], pdata, gdata, title, title_link, value, dkey = false, pval;
        var show_label = true, label_style, show_legend = true;

        if (!chartCtrlActivity("donut", "legend")) {
            show_legend = false;
        }
        if (!chartCtrlActivity("donut", "label")) {
            show_label = false;
        }

        oparams = {
            tooltipOpts: {
                fmatter: rsums[aggr]['formatter']
            },
            legend: {
                position: chartCtrlActivity("donut", "position"),
                show: show_legend
            },
            series: {
                pie: {
                    label: {
                        show: show_label,
                        radius: (chartCtrlActivity("donut", "style") == "s2") ? 3 / 4 : 1
                    }
                },
                fmatter: rsums[aggr]['formatter']
            }
        }

        if (js_pivot_mode == "2R1C") {
            if (!result.columns) {
                throw '-3';
            }
            var rc, c;
            $("#dpiecombo_" + bid).show();
            $("#dpieselect_" + bid).attr("aria-chart-row", filt);
            c = $("#dpieselect_" + bid).val();
            if (filt) {
                if (!result.rows[filt] || !result.rows[filt].children) {
                    throw '-3';
                }
                dkey = true;
                pdata = result.rows[filt].children;
            } else {
                pdata = result.rows;
            }
            for (var j in pdata) {
                rc = pdata[j].columns;
                value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                title = (pdata[j].titleText) ? pdata[j].titleText : "";
                if (dkey === true) {
                    title_link = title;
                } else {
                    title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + j + '" class="donut-chart-child chart-parent-link">' + title + '</a>';
                }
                data.push({
                    label: title_link,
                    data: value
                });
            }
        } else if (js_pivot_mode == "2R") {
            if (filt) {
                if (!result.rows[filt] || !result.rows[filt].children) {
                    throw '-3';
                }
                pdata = result.rows[filt].children;
                dkey = true;
            } else {
                pdata = result.rows
            }
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                if (dkey === true) {
                    title_link = title;
                } else {
                    title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + i + '" class="donut-chart-child chart-parent-link">' + title + '</a>';
                }
                data.push({
                    "label": title_link,
                    "data": value
                });
            }
        } else if (js_pivot_mode == "1R1C") {
            if (!result.columns) {
                throw '-3';
            }
            var rc, c;
            $("#dpiecombo_" + bid).show();
            $("#dpieselect_" + bid).attr("aria-chart-row", filt);
            c = $("#dpieselect_" + bid).val();
            for (var j in result.rows) {
                rc = result.rows[j].columns;
                title = (result.rows[j].titleText) ? result.rows[j].titleText : "";
                value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                data.push({
                    label: title,
                    data: value
                });
            }
        } else {
            pdata = result.rows;
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                data.push({
                    "label": title,
                    "data": value
                });
            }
        }

        settings['params'] = $.extend(true, oparams, iparams);
        settings['colors'] = getDSChartColors(bdata);
        settings['data'] = data;
        settings['id'] = bid;

        return settings;
    } catch (e) {
        $('#chart_preview_' + bid).html('<div class="errormsg" align="center">' + js_lang_label.GENERIC_NO_DATA_FOUND + '</div>')
        return false;
    }
}
function getAreaChartOptions(bid, aggr, filt) {
    try {
        if (!DB_pivot_data_JSON[bid]) {
            throw '-1';
        }
        var bdata = DB_pivot_data_JSON[bid];
        var js_grid_id = bdata['gridID'];
        var result = $("#" + js_grid_id).jqGrid("getGridParam", "dataSet");
        if (!result) {
            throw '-2';
        }
        if (!result.rows) {
            throw '-3';
        }
        aggr = (aggr == 1) ? aggr : 0;
        var js_pivot_mode = bdata['pivotMode'];
        var js_aggr_mode = bdata['aggrMode'];
        var js_x_dimension = bdata['xDimension'];
        var js_aggregates = bdata['aggregates'];
        var rsums = result.sums;
        var settings = {}, oparams = {}, iparams = {}, data = [], ticks = [], pdata, gdata, title, title_link, value, dkey = false;
        var xaixs_label, yaxis_label, show_legend = true;

        if (!chartCtrlActivity("area", "legend")) {
            show_legend = false;
        }
        if (chartCtrlActivity("area", "xaxis")) {
            xaixs_label = js_x_dimension[0]['label'];
        }
        if (chartCtrlActivity("area", "yaxis")) {
            yaxis_label = js_aggregates[aggr]['rowTotalsText'];
        }
        oparams = {
            xaxis: {
                axisLabel: xaixs_label
            },
            yaxis: {
                axisLabel: yaxis_label,
                fmatter: rsums[aggr]['formatter'],
                tickFormatter: function (item, y) {
                    if (y.options.fmatter) {
                        return getChartDataFormat(y.options.fmatter, item);
                    } else {
                        return item;
                    }
                }
            },
            tooltipOpts: {
                fmatter: rsums[aggr]['formatter']
            },
            legend: {
                position: chartCtrlActivity("area", "position"),
                show: show_legend
            }
        }

        if (js_pivot_mode == "2R1C") {
            if (!result.columns) {
                throw '-3';
            }
            gdata = result.columns;
            var temp = [], rc, dd = 0, cd = 1, c, d;
            for (var i in gdata) {
                d = [];
                c = gdata[i].titleText;
                dd = 0;
                if (filt) {
                    if (!result.rows[filt] || !result.rows[filt].children) {
                        continue;
                    }
                    dkey = true;
                    pdata = result.rows[filt].children;
                } else {
                    pdata = result.rows;
                }
                for (var j in pdata) {
                    var rc = pdata[j].columns;
                    value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                    d.push([dd, value]);
                    if (!$.isArray(temp[dd])) {
                        title = (pdata[j].titleText) ? pdata[j].titleText : "";
                        if (dkey === true) {
                            title_link = title;
                        } else {
                            title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + j + '" class="area-chart-child chart-parent-link">' + title + '</a>';
                        }
                        temp[dd] = [j, title_link];
                    }
                    dd++;
                }
                data.push({
                    label: c,
                    data: d
                });
                cd++;
            }
            for (var k in temp) {
                ticks.push(temp[k]);
            }
            iparams = {
                xaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return label + " - " + getChartDataFormat(fmt, y);
                    }
                }
            }
        } else if (js_pivot_mode == "2R") {
            var d = [];
            if (filt) {
                if (!result.rows[filt] || !result.rows[filt].children) {
                    throw '-3';
                }
                pdata = result.rows[filt].children;
                dkey = true;
            } else {
                pdata = result.rows
            }
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                if (dkey === true) {
                    title_link = title;
                } else {
                    title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + i + '" class="area-chart-child chart-parent-link">' + title + '</a>';
                }
                d.push([i, value])
                ticks.push([i, title_link]);
            }
            data = [{
                    data: d
                }];
            iparams = {
                xaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return getChartDataFormat(fmt, y);
                    }
                }
            }
        } else if (js_pivot_mode == "1R1C") {
            if (!result.columns) {
                throw '-3';
            }
            gdata = result.columns;
            var temp = [], rc, dd = 0, cd = 1, c, d;
            for (var i in gdata) {
                d = [];
                c = gdata[i].titleText;
                dd = 0;
                for (var j in result.rows) {
                    rc = result.rows[j].columns;
                    title = (result.rows[j].titleText) ? result.rows[j].titleText : "";
                    value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                    d.push([dd, value]);
                    if (!$.isArray(temp[dd])) {
                        temp[dd] = [j, title];
                    }
                    dd++;
                }
                data.push({
                    label: c,
                    data: d
                });
                cd++;
            }
            for (var k in temp) {
                ticks.push(temp[k]);
            }
            iparams = {
                xaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return label + " - " + getChartDataFormat(fmt, y);
                    }
                }
            }
        } else {
            var d = [];
            pdata = result.rows;
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                d.push([i, value])
                ticks.push([i, title]);
            }
            data = [{
                    data: d
                }];

            iparams = {
                xaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return getChartDataFormat(fmt, y);
                    }
                }
            }
        }

        settings['params'] = $.extend(true, oparams, iparams);
        settings['colors'] = getDSChartColors(bdata);
        settings['data'] = data;
        settings['id'] = bid;

        return settings;
    } catch (e) {
        $('#chart_preview_' + bid).html('<div class="errormsg" align="center">' + js_lang_label.GENERIC_NO_DATA_FOUND + '</div>')
        return false;
    }
}
function getLineChartOptions(bid, aggr, filt) {
    try {
        if (!DB_pivot_data_JSON[bid]) {
            throw '-1';
        }
        var bdata = DB_pivot_data_JSON[bid];
        var js_grid_id = bdata['gridID'];
        var result = $("#" + js_grid_id).jqGrid("getGridParam", "dataSet");
        if (!result) {
            throw '-2';
        }
        if (!result.rows) {
            throw '-3';
        }
        aggr = (aggr == 1) ? aggr : 0;
        var js_pivot_mode = bdata['pivotMode'];
        var js_aggr_mode = bdata['aggrMode'];
        var js_x_dimension = bdata['xDimension'];
        var js_aggregates = bdata['aggregates'];
        var rsums = result.sums;
        var settings = {}, oparams = {}, iparams = {}, data = [], ticks = [], pdata, gdata, title, title_link, value, dkey = false;
        var xaixs_label, yaxis_label, show_legend = true;

        if (!chartCtrlActivity("line", "legend")) {
            show_legend = false;
        }
        if (chartCtrlActivity("line", "xaxis")) {
            xaixs_label = js_x_dimension[0]['label'];
        }
        if (chartCtrlActivity("line", "yaxis")) {
            yaxis_label = js_aggregates[aggr]['rowTotalsText'];
        }
        oparams = {
            xaxis: {
                axisLabel: xaixs_label
            },
            yaxis: {
                axisLabel: yaxis_label,
                fmatter: rsums[aggr]['formatter'],
                tickFormatter: function (item, y) {
                    if (y.options.fmatter) {
                        return getChartDataFormat(y.options.fmatter, item);
                    } else {
                        return item;
                    }
                }
            },
            tooltipOpts: {
                fmatter: rsums[aggr]['formatter']
            },
            legend: {
                position: chartCtrlActivity("line", "position"),
                show: show_legend
            }
        }

        if (js_pivot_mode == "2R1C") {
            if (!result.columns) {
                throw '-3';
            }
            gdata = result.columns;
            var temp = [], rc, dd = 0, cd = 1, c, d;
            for (var i in gdata) {
                d = [];
                c = gdata[i].titleText;
                dd = 0;
                if (filt) {
                    if (!result.rows[filt] || !result.rows[filt].children) {
                        continue;
                    }
                    dkey = true;
                    pdata = result.rows[filt].children;
                } else {
                    pdata = result.rows;
                }
                for (var j in pdata) {
                    var rc = pdata[j].columns;
                    value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                    d.push([dd, value]);
                    if (!$.isArray(temp[dd])) {
                        title = (pdata[j].titleText) ? pdata[j].titleText : "";
                        if (dkey === true) {
                            title_link = title;
                        } else {
                            title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + j + '" class="line-chart-child chart-parent-link">' + title + '</a>';
                        }
                        temp[dd] = [j, title_link];
                    }
                    dd++;
                }
                data.push({
                    label: c,
                    data: d
                });
                cd++;
            }
            for (var k in temp) {
                ticks.push(temp[k]);
            }
            iparams = {
                xaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return label + " - " + getChartDataFormat(fmt, y);
                    }
                }
            }
        } else if (js_pivot_mode == "2R") {
            var d = [];
            if (filt) {
                if (!result.rows[filt] || !result.rows[filt].children) {
                    throw '-3';
                }
                pdata = result.rows[filt].children;
                dkey = true;
            } else {
                pdata = result.rows
            }
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                if (dkey === true) {
                    title_link = title;
                } else {
                    title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + i + '" class="line-chart-child chart-parent-link">' + title + '</a>';
                }
                d.push([i, value])
                ticks.push([i, title_link]);
            }
            data = [{
                    data: d
                }];
            iparams = {
                xaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return getChartDataFormat(fmt, y);
                    }
                }
            }
        } else if (js_pivot_mode == "1R1C") {
            if (!result.columns) {
                throw '-3';
            }
            gdata = result.columns;
            var temp = [], rc, dd = 0, cd = 1, c, d;
            for (var i in gdata) {
                d = [];
                c = gdata[i].titleText;
                dd = 0;
                for (var j in result.rows) {
                    rc = result.rows[j].columns;
                    title = (result.rows[j].titleText) ? result.rows[j].titleText : "";
                    value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                    d.push([dd, value]);
                    if (!$.isArray(temp[dd])) {
                        temp[dd] = [j, title];
                    }
                    dd++;
                }
                data.push({
                    label: c,
                    data: d
                });
                cd++;
            }
            for (var k in temp) {
                ticks.push(temp[k]);
            }
            iparams = {
                xaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return label + " - " + getChartDataFormat(fmt, y);
                    }
                }
            }
        } else {
            var d = [];
            pdata = result.rows;
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                d.push([i, value])
                ticks.push([i, title]);
            }
            data = [{
                    data: d
                }];

            iparams = {
                xaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return getChartDataFormat(fmt, y);
                    }
                }
            }
        }

        settings['params'] = $.extend(true, oparams, iparams);
        settings['colors'] = getDSChartColors(bdata);
        settings['data'] = data;
        settings['id'] = bid;

        return settings;
    } catch (e) {
        $('#chart_preview_' + bid).html('<div class="errormsg" align="center">' + js_lang_label.GENERIC_NO_DATA_FOUND + '</div>')
        return false;
    }
}
function getHorizontalBarChartOptions(bid, aggr, filt) {
    try {
        if (!DB_pivot_data_JSON[bid]) {
            throw '-1';
        }
        var bdata = DB_pivot_data_JSON[bid];
        var js_grid_id = bdata['gridID'];
        var result = $("#" + js_grid_id).jqGrid("getGridParam", "dataSet");
        if (!result) {
            throw '-2';
        }
        if (!result.rows) {
            throw '-3';
        }
        aggr = (aggr == 1) ? aggr : 0;
        var js_pivot_mode = bdata['pivotMode'];
        var js_aggr_mode = bdata['aggrMode'];
        var js_x_dimension = bdata['xDimension'];
        var js_aggregates = bdata['aggregates'];
        var rsums = result.sums;
        var settings = {}, oparams = {}, iparams = {}, data = [], ticks = [], pdata, gdata, title, title_link, value, dkey = false;
        var xaixs_label, yaxis_label, show_legend = true;

        if (!chartCtrlActivity("horizbar", "legend")) {
            show_legend = false;
        }
        if (chartCtrlActivity("horizbar", "xaxis")) {
            xaixs_label = js_aggregates[aggr]['rowTotalsText'];
        }
        if (chartCtrlActivity("horizbar", "yaxis")) {
            yaxis_label = js_x_dimension[0]['label'];
        }
        oparams = {
            xaxis: {
                axisLabel: xaixs_label,
                fmatter: rsums[aggr]['formatter'],
                tickFormatter: function (item, x) {
                    if (x.options.fmatter) {
                        return getChartDataFormat(x.options.fmatter, item);
                    } else {
                        return item;
                    }
                }
            },
            yaxis: {
                axisLabel: yaxis_label,
                tickLength: 0
            },
            tooltipOpts: {
                fmatter: rsums[aggr]['formatter']
            },
            legend: {
                position: chartCtrlActivity("horizbar", "position"),
                show: show_legend
            }
        }

        if (js_pivot_mode == "2R1C") {
            if (!result.columns) {
                throw '-3';
            }
            gdata = result.columns;
            var ticks = [], temp = [], rc, dd = 0, cd = 1, c, d;
            for (var i in gdata) {
                d = [];
                c = gdata[i].titleText;
                dd = 0;
                if (filt) {
                    if (!result.rows[filt] || !result.rows[filt].children) {
                        continue;
                    }
                    dkey = true;
                    pdata = result.rows[filt].children;
                } else {
                    pdata = result.rows;
                }
                for (var j in pdata) {
                    var rc = pdata[j].columns;
                    value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                    d.push([value, dd]);
                    if (!$.isArray(temp[dd])) {
                        title = (pdata[j].titleText) ? pdata[j].titleText : "";
                        if (dkey === true) {
                            title_link = title;
                        } else {
                            title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + j + '" class="horizbar-chart-child chart-parent-link">' + title + '</a>';
                        }
                        temp[dd] = [j, title_link];
                    }
                    dd++;
                }
                data.push({
                    label: c,
                    data: d,
                    bars: {order: cd}
                });
                cd++;
            }
            for (var k in temp) {
                ticks.push(temp[k]);
            }
            iparams = {
                bars: {
                    barWidth: 0.2
                },
                yaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return label + " - " + getChartDataFormat(fmt, x);
                    }
                }
            }
        } else if (js_pivot_mode == "2R") {
            if (filt) {
                if (!result.rows[filt] || !result.rows[filt].children) {
                    throw '-3';
                }
                pdata = result.rows[filt].children;
                dkey = true;
            } else {
                pdata = result.rows
            }
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                if (dkey === true) {
                    title_link = title;
                } else {
                    title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + i + '" class="horizbar-chart-child chart-parent-link">' + title + '</a>';
                }
                //data.push([value, i])
                data.push({
                    "data": [[value, i]]
                });
                ticks.push([i, title_link]);
            }
            //data = [data];
            iparams = {
                bars: {
                    barWidth: 0.5
                },
                yaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return getChartDataFormat(fmt, x);
                    }
                }
            }
        } else if (js_pivot_mode == "1R1C") {
            if (!result.columns) {
                throw '-3';
            }
            gdata = result.columns;
            var ticks = [], temp = [], rc, dd = 0, cd = 1, c, d;
            for (var i in gdata) {
                d = [];
                c = gdata[i].titleText;
                dd = 0;
                for (var j in result.rows) {
                    rc = result.rows[j].columns;
                    title = (result.rows[j].titleText) ? result.rows[j].titleText : "";
                    value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                    d.push([value, dd]);
                    if (!$.isArray(temp[dd])) {
                        temp[dd] = [j, title];
                    }
                    dd++;
                }
                data.push({
                    label: c,
                    data: d,
                    bars: {order: cd}
                });
                cd++;
            }
            for (var k in temp) {
                ticks.push(temp[k]);
            }
            iparams = {
                bars: {
                    barWidth: 0.2
                },
                yaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return label + " - " + getChartDataFormat(fmt, x);
                    }
                }
            }
        } else {
            pdata = result.rows;
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                //data.push([value, i])
                data.push({
                    "data": [[value, i]]
                });
                ticks.push([i, title]);
            }
            //data = [data];
            iparams = {
                bars: {
                    barWidth: 0.5
                },
                yaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return getChartDataFormat(fmt, x);
                    }
                }
            }
        }

        settings['params'] = $.extend(true, oparams, iparams);
        settings['colors'] = getDSChartColors(bdata);
        settings['data'] = data;
        settings['id'] = bid;

        return settings;
    } catch (e) {
        $('#chart_preview_' + bid).html('<div class="errormsg" align="center">' + js_lang_label.GENERIC_NO_DATA_FOUND + '</div>')
        return false;
    }
}
function getStackedBarChartOptions(bid, aggr, filt) {
    try {
        if (!DB_pivot_data_JSON[bid]) {
            throw '-1';
        }
        var bdata = DB_pivot_data_JSON[bid];
        var js_grid_id = bdata['gridID'];
        var result = $("#" + js_grid_id).jqGrid("getGridParam", "dataSet");
        if (!result) {
            throw '-2';
        }
        if (!result.rows) {
            throw '-3';
        }
        aggr = (aggr == 1) ? aggr : 0;
        var js_pivot_mode = bdata['pivotMode'];
        var js_aggr_mode = bdata['aggrMode'];
        var js_x_dimension = bdata['xDimension'];
        var js_aggregates = bdata['aggregates'];
        var rsums = result.sums;
        var settings = {}, oparams = {}, iparams = {}, data = [], pdata, gdata, title, title_link, value, dkey = false;
        var xaixs_label, yaxis_label, show_legend = true, xaxis_ticks = [];

        if (!chartCtrlActivity("stackbar", "legend")) {
            show_legend = false;
        }
        if (chartCtrlActivity("stackbar", "xaxis")) {
            xaixs_label = js_x_dimension[0]['label'];
        }
        if (chartCtrlActivity("stackbar", "yaxis")) {
            yaxis_label = js_aggregates[aggr]['rowTotalsText'];
        }
        oparams = {
            xaxis: {
                axisLabel: xaixs_label,
                tickLength: 0
            },
            yaxis: {
                axisLabel: yaxis_label,
                fmatter: rsums[aggr]['formatter'],
                tickFormatter: function (item, y) {
                    if (y.options.fmatter) {
                        return getChartDataFormat(y.options.fmatter, item);
                    } else {
                        return item;
                    }
                }
            },
            tooltipOpts: {
                fmatter: rsums[aggr]['formatter']
            },
            legend: {
                position: chartCtrlActivity("stackbar", "position"),
                show: show_legend
            }
        }

        if (bdata['chartOptions'] && bdata['chartOptions']['barChart']) {
            if ("xaxisAngle" in bdata['chartOptions']['barChart']) {
                oparams.xaxis.rotateTicks = parseInt(bdata['chartOptions']['barChart']['xaxisAngle']);
            }
        }

        if (js_pivot_mode == "2R1C") {
            if (!result.columns) {
                throw '-3';
            }
            gdata = result.columns;
            var ticks = [], temp = [], rc, dd = 0, cd = 1, c, d;
            for (var i in gdata) {
                d = [];
                c = gdata[i].titleText;
                dd = 0;
                if (filt) {
                    if (!result.rows[filt] || !result.rows[filt].children) {
                        continue;
                    }
                    dkey = true;
                    pdata = result.rows[filt].children;
                } else {
                    pdata = result.rows;
                }
                for (var j in pdata) {
                    var rc = pdata[j].columns;
                    value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                    d.push([dd, value]);
                    if (!$.isArray(temp[dd])) {
                        title = (pdata[j].titleText) ? pdata[j].titleText : "";
                        if (dkey === true) {
                            title_link = title;
                        } else {
                            title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + j + '" class="stackbar-chart-child chart-parent-link">' + title + '</a>';
                        }
                        temp[dd] = [j, title_link];
                    }
                    dd++;
                }
                data.push({
                    label: c,
                    data: d
                });
                cd++;
            }
            for (var k in temp) {
                ticks.push(temp[k]);
            }
            iparams = {
                bars: {
                    barWidth: 0.4
                },
                xaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return label + " - " + getChartDataFormat(fmt, y);
                    }
                }
            }
        } else if (js_pivot_mode == "2R") {
            if (filt) {
                if (!result.rows[filt] || !result.rows[filt].children) {
                    throw '-3';
                }
                pdata = result.rows[filt].children;
                dkey = true;
            } else {
                pdata = result.rows
            }
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                if (dkey === true) {
                    title_link = title;
                } else {
                    title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + i + '" class="stackbar-chart-child chart-parent-link">' + title + '</a>';
                }
                //data.push([title_link, value]);
                data.push({
                    "data": [[i, value]]
                });
                xaxis_ticks.push([i, title_link]);
            }
            //data = [data];
            iparams = {
                bars: {
                    barWidth: 0.4
                },
                xaxis: {
                    mode: "categories",
                    ticks: xaxis_ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return  getChartDataFormat(fmt, y);
                    }
                }
            }
        } else if (js_pivot_mode == "1R1C") {
            if (!result.columns) {
                throw '-3';
            }
            gdata = result.columns;
            var ticks = [], temp = [], rc, dd = 0, cd = 1, c, d;
            for (var i in gdata) {
                d = [];
                c = gdata[i].titleText;
                dd = 0;
                for (var j in result.rows) {
                    rc = result.rows[j].columns;
                    title = (result.rows[j].titleText) ? result.rows[j].titleText : "";
                    value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                    d.push([dd, value]);
                    if (!$.isArray(temp[dd])) {
                        temp[dd] = [j, title];
                    }
                    dd++;
                }
                data.push({
                    label: c,
                    data: d
                });
                cd++;
            }
            for (var k in temp) {
                ticks.push(temp[k]);
            }
            iparams = {
                bars: {
                    barWidth: 0.4
                },
                xaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return label + " - " + getChartDataFormat(fmt, y);
                    }
                }
            }
        } else {
            pdata = result.rows;
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                //data.push([title, value]);
                data.push({
                    "data": [[i, value]]
                });
                xaxis_ticks.push([i, title]);
            }
            //data = [data];
            iparams = {
                bars: {
                    barWidth: 0.4
                },
                xaxis: {
                    mode: "categories",
                    ticks: xaxis_ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return getChartDataFormat(fmt, y);
                    }
                }
            }
        }

        settings['params'] = $.extend(true, oparams, iparams);
        settings['colors'] = getDSChartColors(bdata);
        settings['data'] = data;
        settings['id'] = bid;

        return settings;
    } catch (e) {
        $('#chart_preview_' + bid).html('<div class="errormsg" align="center">' + js_lang_label.GENERIC_NO_DATA_FOUND + '</div>')
        return false;
    }
}
function getStackedHorizBarChartOptions(bid, aggr, filt) {
    try {
        if (!DB_pivot_data_JSON[bid]) {
            throw '-1';
        }
        var bdata = DB_pivot_data_JSON[bid];
        var js_grid_id = bdata['gridID'];
        var result = $("#" + js_grid_id).jqGrid("getGridParam", "dataSet");
        if (!result) {
            throw '-2';
        }
        if (!result.rows) {
            throw '-3';
        }
        aggr = (aggr == 1) ? aggr : 0;
        var js_pivot_mode = bdata['pivotMode'];
        var js_aggr_mode = bdata['aggrMode'];
        var js_x_dimension = bdata['xDimension'];
        var js_aggregates = bdata['aggregates'];
        var rsums = result.sums;
        var settings = {}, oparams = {}, iparams = {}, data = [], ticks = [], pdata, gdata, title, title_link, value, dkey = false;
        var xaixs_label, yaxis_label, show_legend = true;

        if (!chartCtrlActivity("stackhorizbar", "legend")) {
            show_legend = false;
        }
        if (chartCtrlActivity("stackhorizbar", "xaxis")) {
            xaixs_label = js_aggregates[aggr]['rowTotalsText'];
        }
        if (chartCtrlActivity("stackhorizbar", "yaxis")) {
            yaxis_label = js_x_dimension[0]['label'];
        }
        oparams = {
            xaxis: {
                axisLabel: xaixs_label,
                fmatter: rsums[aggr]['formatter'],
                tickFormatter: function (item, x) {
                    if (x.options.fmatter) {
                        return getChartDataFormat(x.options.fmatter, item);
                    } else {
                        return item;
                    }
                }
            },
            yaxis: {
                axisLabel: yaxis_label,
                tickLength: 0
            },
            tooltipOpts: {
                fmatter: rsums[aggr]['formatter']
            },
            legend: {
                position: chartCtrlActivity("stackhorizbar", "position"),
                show: show_legend
            }
        }

        if (js_pivot_mode == "2R1C") {
            if (!result.columns) {
                throw '-3';
            }
            gdata = result.columns;
            var ticks = [], temp = [], rc, dd = 0, cd = 1, c, d;
            for (var i in gdata) {
                d = [];
                c = gdata[i].titleText;
                dd = 0;
                if (filt) {
                    if (!result.rows[filt] || !result.rows[filt].children) {
                        continue;
                    }
                    dkey = true;
                    pdata = result.rows[filt].children;
                } else {
                    pdata = result.rows;
                }
                for (var j in pdata) {
                    var rc = pdata[j].columns;
                    value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                    d.push([value, dd]);
                    if (!$.isArray(temp[dd])) {
                        title = (pdata[j].titleText) ? pdata[j].titleText : "";
                        if (dkey === true) {
                            title_link = title;
                        } else {
                            title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + j + '" class="stackhorizbar-chart-child chart-parent-link">' + title + '</a>';
                        }
                        temp[dd] = [j, title_link];
                    }
                    dd++;
                }
                data.push({
                    label: c,
                    data: d
                });
                cd++;
            }
            for (var k in temp) {
                ticks.push(temp[k]);
            }
            iparams = {
                bars: {
                    barWidth: 0.5
                },
                yaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return label + " - " + getChartDataFormat(fmt, x);
                    }
                }
            }
        } else if (js_pivot_mode == "2R") {
            if (filt) {
                if (!result.rows[filt] || !result.rows[filt].children) {
                    throw '-3';
                }
                pdata = result.rows[filt].children;
                dkey = true;
            } else {
                pdata = result.rows
            }
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                if (dkey === true) {
                    title_link = title;
                } else {
                    title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + i + '" class="stackhorizbar-chart-child chart-parent-link">' + title + '</a>';
                }
                //data.push([value, i])
                data.push({
                    "data": [[value, i]]
                });
                ticks.push([i, title_link]);
            }
            //data = [data];
            iparams = {
                bars: {
                    barWidth: 0.5
                },
                yaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return getChartDataFormat(fmt, x);
                    }
                }
            }
        } else if (js_pivot_mode == "1R1C") {
            if (!result.columns) {
                throw '-3';
            }
            gdata = result.columns;
            var ticks = [], temp = [], rc, dd = 0, cd = 1, c, d;
            for (var i in gdata) {
                d = [];
                c = gdata[i].titleText;
                dd = 0;
                for (var j in result.rows) {
                    rc = result.rows[j].columns;
                    title = (result.rows[j].titleText) ? result.rows[j].titleText : "";
                    value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                    d.push([value, dd]);
                    if (!$.isArray(temp[dd])) {
                        temp[dd] = [j, title];
                    }
                    dd++;
                }
                data.push({
                    label: c,
                    data: d
                });
                cd++;
            }
            for (var k in temp) {
                ticks.push(temp[k]);
            }
            iparams = {
                bars: {
                    barWidth: 0.5
                },
                yaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return label + " - " + getChartDataFormat(fmt, x);
                    }
                }
            }
        } else {
            pdata = result.rows;
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                //data.push([value, i])
                data.push({
                    "data": [[value, i]]
                });
                ticks.push([i, title]);
            }
            //data = [data];
            iparams = {
                bars: {
                    barWidth: 0.5
                },
                yaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return getChartDataFormat(fmt, x);
                    }
                }
            }
        }

        settings['params'] = $.extend(true, oparams, iparams);
        settings['colors'] = getDSChartColors(bdata);
        settings['data'] = data;
        settings['id'] = bid;

        return settings;
    } catch (e) {
        $('#chart_preview_' + bid).html('<div class="errormsg" align="center">' + js_lang_label.GENERIC_NO_DATA_FOUND + '</div>')
        return false;
    }
}
function getAutoUpdatingChartOptions(bid, aggr, filt) {
    try {
        if (!DB_pivot_data_JSON[bid]) {
            throw '-1';
        }
        var bdata = DB_pivot_data_JSON[bid];
        var js_grid_id = bdata['gridID'];
        var result = $("#" + js_grid_id).jqGrid("getGridParam", "dataSet");
        if (!result) {
            throw '-2';
        }
        if (!result.rows) {
            throw '-3';
        }
        aggr = (aggr == 1) ? aggr : 0;
        var js_pivot_mode = bdata['pivotMode'];
        var js_aggr_mode = bdata['aggrMode'];
        var js_x_dimension = bdata['xDimension'];
        var js_aggregates = bdata['aggregates'];
        var rsums = result.sums;
        var settings = {}, oparams = {}, iparams = {}, data = [], ticks = [], pdata, gdata, title, title_link, value, dkey = false;
        var xaixs_label, yaxis_label, show_legend = true;

        if (!chartCtrlActivity("autoupdating", "legend")) {
            show_legend = false;
        }
        if (chartCtrlActivity("autoupdating", "xaxis")) {
            xaixs_label = js_x_dimension[0]['label'];
        }
        if (chartCtrlActivity("autoupdating", "yaxis")) {
            yaxis_label = js_aggregates[aggr]['rowTotalsText'];
        }
        oparams = {
            xaxis: {
                axisLabel: xaixs_label
            },
            yaxis: {
                axisLabel: yaxis_label
            },
            tooltipOpts: {
                fmatter: rsums[aggr]['formatter']
            },
            legend: {
                position: chartCtrlActivity("autoupdating", "position"),
                show: show_legend
            }
        }

        if (js_pivot_mode == "2R1C") {
            if (!result.columns) {
                throw '-3';
            }
            gdata = result.columns;
            var temp = [], rc, dd = 0, cd = 1, c, d;
            for (var i in gdata) {
                d = [];
                c = gdata[i].titleText;
                dd = 0;
                if (filt) {
                    if (!result.rows[filt] || !result.rows[filt].children) {
                        continue;
                    }
                    dkey = true;
                    pdata = result.rows[filt].children;
                } else {
                    pdata = result.rows;
                }
                for (var j in pdata) {
                    var rc = pdata[j].columns;
                    value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                    d.push([dd, value]);
                    if (!$.isArray(temp[dd])) {
                        title = (pdata[j].titleText) ? pdata[j].titleText : "";
                        if (dkey === true) {
                            title_link = title;
                        } else {
                            title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + j + '" class="autoupdating-chart-child chart-parent-link">' + title + '</a>';
                        }
                        temp[dd] = [j, title_link];
                    }
                    dd++;
                }
                data.push({
                    label: c,
                    data: d
                });
                cd++;
            }
            for (var k in temp) {
                ticks.push(temp[k]);
            }
            iparams = {
                xaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return label + " - " + getChartDataFormat(fmt, y);
                    }
                }
            }
        } else if (js_pivot_mode == "2R") {
            var d = [];
            if (filt) {
                if (!result.rows[filt] || !result.rows[filt].children) {
                    throw '-3';
                }
                pdata = result.rows[filt].children;
                dkey = true;
            } else {
                pdata = result.rows
            }
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                if (dkey === true) {
                    title_link = title;
                } else {
                    title_link = '<a href="javascript://" title="' + title + '" aria-chart-id="' + bid + '" aria-chart-row="' + i + '" class="autoupdating-chart-child chart-parent-link">' + title + '</a>';
                }
                d.push([i, value])
                ticks.push([i, title_link]);
            }
            data = [{
                    data: d
                }];
            iparams = {
                xaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return getChartDataFormat(fmt, y);
                    }
                }
            }
        } else if (js_pivot_mode == "1R1C") {
            if (!result.columns) {
                throw '-3';
            }
            gdata = result.columns;
            var temp = [], rc, dd = 0, cd = 1, c, d;
            for (var i in gdata) {
                d = [];
                c = gdata[i].titleText;
                dd = 0;
                for (var j in result.rows) {
                    rc = result.rows[j].columns;
                    title = (result.rows[j].titleText) ? result.rows[j].titleText : "";
                    value = (rc[c] && rc[c][aggr]) ? rc[c][aggr] : 0;
                    d.push([dd, value]);
                    if (!$.isArray(temp[dd])) {
                        temp[dd] = [j, title];
                    }
                    dd++;
                }
                data.push({
                    label: c,
                    data: d
                });
                cd++;
            }
            for (var k in temp) {
                ticks.push(temp[k]);
            }
            iparams = {
                xaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return label + " - " + getChartDataFormat(fmt, y);
                    }
                }
            }
        } else {
            var d = [];
            pdata = result.rows;
            for (var i in pdata) {
                title = (pdata[i].titleText) ? pdata[i].titleText : '';
                value = (pdata[i].values[aggr]) ? pdata[i].values[aggr] : 0;
                d.push([i, value])
                ticks.push([i, title]);
            }
            data = [{
                    data: d
                }];

            iparams = {
                xaxis: {
                    ticks: ticks
                },
                tooltipOpts: {
                    content: function (label, x, y, item, fmt) {
                        return getChartDataFormat(fmt, y);
                    }
                }
            }
        }

        settings['params'] = $.extend(true, oparams, iparams);
        settings['colors'] = getDSChartColors(bdata);
        settings['data'] = data;
        settings['id'] = bid;

        return settings;
    } catch (e) {
        $('#chart_preview_' + bid).html('<div class="errormsg" align="center">' + js_lang_label.GENERIC_NO_DATA_FOUND + '</div>')
        return false;
    }
}

function plotBarChartDiagram(settings) {
    if (!settings) {
        return false;
    }
    var bid = settings.id;
    var dataset = settings.data;
    var custom_params = settings.params;

    var basic_params = {
        grid: {
            show: true,
            aboveData: false,
            color: "#3f3f3f",
            labelMargin: 5,
            axisMargin: 0,
            borderWidth: 1,
            borderColor: "#ccc",
            minBorderMargin: 5,
            clickable: true,
            hoverable: true,
            autoHighlight: false,
            mouseActiveRadius: 20
        },
        bars: {
            show: true,
            //barWidth: 0.4,
            fill: true,
            //order: true,
            lineWidth: 0,
            align: "center",
            fillColor: {
                colors: [{opacity: 1}, {opacity: 1}]
            }
        },
        legend: {
            position: "ne"
        },
        xaxis: {
            //axisLabel: "Category",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: "'Droid Sans',Helvetica,Arial,sans-serif",
            axisLabelPadding: 5,
            //mode: "categories",
            //tickLength: 0,
            rotateTicks: 180
        },
        yaxis: {
            //axisLabel: "Sum of Prices",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: "'Droid Sans',Helvetica,Arial,sans-serif",
            axisLabelPadding: 0
        },
        colors: settings.colors,
        tooltip: true,
        tooltipOpts: {
            content: '%y'
        }
    }
    var final_params = $.extend(true, basic_params, custom_params);
    var holder = $("#chart_preview_" + bid);
    if (holder.length) {
        $.plot(holder, dataset, final_params);
    }
}
function plotPieChartDiagram(settings) {
    if (!settings) {
        return false;
    }
    var bid = settings.id;
    var dataset = settings.data;
    var custom_params = settings.params;
    var basic_params = {
        grid: {
            show: true,
            clickable: true,
            hoverable: true
        },
        series: {
            pie: {
                show: true,
                radius: 1,
                stroke: {
                    width: 2
                },
                label: {
                    show: true,
                    radius: 1,
                    formatter: function (label, series) {
                        if (chartCtrlActivity("pie", "value") == "value") {
                            return '<div class="pie-chart-label">' + label + '&nbsp;' + getChartDataFormat(series.fmatter, series.data[0][1]) + '</div>';
                        } else {
                            return '<div class="pie-chart-label">' + label + '&nbsp;' + Math.round(series.percent) + '%</div>';
                        }
                    }
                }
            }
        },
        legend: {
            show: false
        },
        colors: settings.colors,
        tooltip: true,
        tooltipOpts: {
            content: function (label, x, y, item, fmt) {
                return label + " - " + getChartDataFormat(fmt, y);
            }
        }
    }
    var final_params = $.extend(true, basic_params, custom_params);
    var holder = $("#chart_preview_" + bid);
    if (holder.length) {
        $.plot(holder, dataset, final_params);
    }
}
function plotDonutChartDiagram(settings) {
    if (!settings) {
        return false;
    }
    var bid = settings.id;
    var dataset = settings.data;
    var custom_params = settings.params;
    var basic_params = {
        grid: {
            show: true,
            clickable: true,
            hoverable: true
        },
        series: {
            pie: {
                show: true,
                radius: 1,
                innerRadius: 0.5,
                stroke: {
                    width: 4
                },
                label: {
                    show: true,
                    radius: 1,
                    formatter: function (label, series) {
                        if (chartCtrlActivity("pie", "value") == "value") {
                            return '<div class="pie-chart-label">' + label + '&nbsp;' + getChartDataFormat(series.fmatter, series.data[0][1]) + '</div>';
                        } else {
                            return '<div class="pie-chart-label">' + label + '&nbsp;' + Math.round(series.percent) + '%</div>';
                        }
                    }
                }
            }
        },
        legend: {
            show: false
        },
        colors: settings.colors,
        tooltip: true,
        tooltipOpts: {
            content: function (label, x, y, item, fmt) {
                return label + " - " + getChartDataFormat(fmt, y);
            }
        }
    }
    var final_params = $.extend(true, basic_params, custom_params);
    var holder = $("#chart_preview_" + bid);
    if (holder.length) {
        $.plot(holder, dataset, final_params);
    }
}
function plotAreaChartDiagram(settings) {
    if (!settings) {
        return false;
    }
    var bid = settings.id;
    var dataset = settings.data;
    var custom_params = settings.params;
    var basic_params = {
        grid: {
            show: true,
            aboveData: false,
            color: "#3f3f3f",
            labelMargin: 5,
            axisMargin: 0,
            borderWidth: 1,
            borderColor: "#ccc",
            minBorderMargin: 5,
            clickable: true,
            hoverable: true,
            autoHighlight: false,
            mouseActiveRadius: 20
        },
        series: {
            lines: {
                show: true,
                fill: true,
                lineWidth: 2
            },
            points: {
                show: true,
                radius: 4.5,
                fill: true,
                fillColor: "#ffffff",
                lineWidth: 2.75
            }
        },
        legend: {
            position: "ne"
        },
        xaxis: {
            //axisLabel: "Category",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: "'Droid Sans',Helvetica,Arial,sans-serif",
            axisLabelPadding: 5,
            //ticks: ticks,
            //tickLength:1,
            rotateTicks: 180
        },
        yaxis: {
            //axisLabel: "Sum of Prices",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: "'Droid Sans',Helvetica,Arial,sans-serif",
            axisLabelPadding: 0
        },
        colors: settings.colors,
        tooltip: true,
        tooltipOpts: {
            content: '%y'
        }
    }
    var final_params = $.extend(true, basic_params, custom_params);
    var holder = $("#chart_preview_" + bid);
    if (holder.length) {
        $.plot(holder, dataset, final_params);
    }
}
function plotLineChartDiagram(settings) {
    if (!settings) {
        return false;
    }
    var bid = settings.id;
    var dataset = settings.data;
    var custom_params = settings.params;
    var basic_params = {
        grid: {
            show: true,
            aboveData: false,
            color: "#3f3f3f",
            labelMargin: 5,
            axisMargin: 0,
            borderWidth: 1,
            borderColor: "#ccc",
            minBorderMargin: 5,
            clickable: true,
            hoverable: true,
            autoHighlight: false,
            mouseActiveRadius: 20
        },
        series: {
            lines: {
                show: true,
                fill: false,
                lineWidth: 2
            },
            points: {
                show: true,
                radius: 4.5,
                fill: true,
                fillColor: "#ffffff",
                lineWidth: 2.75
            }
        },
        legend: {
            position: "ne"
        },
        xaxis: {
            //axisLabel: "Category",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: "'Droid Sans',Helvetica,Arial,sans-serif",
            axisLabelPadding: 5,
            //ticks: ticks,
            //tickLength:1,
            rotateTicks: 180
        },
        yaxis: {
            //axisLabel: "Sum of Prices",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: "'Droid Sans',Helvetica,Arial,sans-serif",
            axisLabelPadding: 0
        },
        colors: settings.colors,
        tooltip: true,
        tooltipOpts: {
            content: '%y'
        }
    }
    var final_params = $.extend(true, basic_params, custom_params);
    var holder = $("#chart_preview_" + bid);
    if (holder.length) {
        $.plot(holder, dataset, final_params);
    }
}
function plotHorizontalBarChartDiagram(settings) {
    if (!settings) {
        return false;
    }
    var bid = settings.id;
    var dataset = settings.data;
    var custom_params = settings.params;
    var basic_params = {
        grid: {
            show: true,
            aboveData: false,
            color: "#3f3f3f",
            labelMargin: 5,
            axisMargin: 0,
            borderWidth: 1,
            borderColor: "#ccc",
            minBorderMargin: 5,
            clickable: true,
            hoverable: true,
            autoHighlight: false,
            mouseActiveRadius: 20
        },
        bars: {
            horizontal: true,
            show: true,
            //barWidth: 0.5,
            fill: true,
            //order: true,
            lineWidth: 0,
            align: "center",
            fillColor: {
                colors: [{opacity: 1}, {opacity: 1}]
            }
        },
        legend: {
            position: "ne"
        },
        xaxis: {
            //axisLabel: "Sum of Prices",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: "'Droid Sans',Helvetica,Arial,sans-serif",
            axisLabelPadding: 5
        },
        yaxis: {
            //axisLabel: "Category",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: "'Droid Sans',Helvetica,Arial,sans-serif",
            //tickLength: 0,
            //ticks: ticks
            axisLabelPadding: 0
        },
        colors: settings.colors,
        tooltip: true,
        tooltipOpts: {
            content: '%x'
        }
    }
    var final_params = $.extend(true, basic_params, custom_params);
    var holder = $("#chart_preview_" + bid);
    if (holder.length) {
        $.plot(holder, dataset, final_params);
    }
}
function plotStackedBarChartDiagram(settings) {
    if (!settings) {
        return false;
    }
    var bid = settings.id;
    var dataset = settings.data;
    var custom_params = settings.params;
    var basic_params = {
        grid: {
            show: true,
            aboveData: false,
            color: "#3f3f3f",
            labelMargin: 5,
            axisMargin: 0,
            borderWidth: 1,
            borderColor: "#ccc",
            minBorderMargin: 5,
            clickable: true,
            hoverable: true,
            autoHighlight: false,
            mouseActiveRadius: 20
        },
        series: {
            stack: true
        },
        bars: {
            show: true,
            //barWidth: 0.4,
            fill: true,
            lineWidth: 0,
            align: "center",
            fillColor: {
                colors: [{opacity: 1}, {opacity: 1}]
            }
        },
        legend: {
            position: "ne"
        },
        xaxis: {
            //axisLabel: "Category",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: "'Droid Sans',Helvetica,Arial,sans-serif",
            axisLabelPadding: 5,
            //mode: "categories",
            //tickLength: 0,
            rotateTicks: 180
        },
        yaxis: {
            //axisLabel: "Sum of Prices",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: "'Droid Sans',Helvetica,Arial,sans-serif",
            axisLabelPadding: 0
        },
        colors: settings.colors,
        tooltip: true,
        tooltipOpts: {
            content: '%y'
        }
    }
    var final_params = $.extend(true, basic_params, custom_params);
    var holder = $("#chart_preview_" + bid);
    if (holder.length) {
        $.plot(holder, dataset, final_params);
    }
}
function plotStackedHorizBarChartDiagram(settings) {
    if (!settings) {
        return false;
    }
    var bid = settings.id;
    var dataset = settings.data;
    var custom_params = settings.params;
    var basic_params = {
        grid: {
            show: true,
            aboveData: false,
            color: "#3f3f3f",
            labelMargin: 5,
            axisMargin: 0,
            borderWidth: 1,
            borderColor: "#ccc",
            minBorderMargin: 5,
            clickable: true,
            hoverable: true,
            autoHighlight: false,
            mouseActiveRadius: 20
        },
        series: {
            stack: true
        },
        bars: {
            horizontal: true,
            show: true,
            //barWidth: 0.5,
            fill: true,
            lineWidth: 0,
            align: "center",
            fillColor: {
                colors: [{opacity: 1}, {opacity: 1}]
            }
        },
        legend: {
            position: "ne"
        },
        xaxis: {
            //axisLabel: "Sum of Prices",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: "'Droid Sans',Helvetica,Arial,sans-serif",
            axisLabelPadding: 5
        },
        yaxis: {
            //axisLabel: "Category",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: "'Droid Sans',Helvetica,Arial,sans-serif",
            //tickLength: 0,
            //ticks: ticks,
            axisLabelPadding: 0
        },
        colors: settings.colors,
        tooltip: true,
        tooltipOpts: {
            content: '%x'
        }
    }
    var final_params = $.extend(true, basic_params, custom_params);
    var holder = $("#chart_preview_" + bid);
    if (holder.length) {
        $.plot(holder, dataset, final_params);
    }
}
function plotAutoUpdatingChartDiagram(settings) {
    if (!settings) {
        return false;
    }
    var bid = settings.id;
    var dataset = settings.data;
    var custom_params = settings.params;
    var update_interval = 100;
    var basic_params = {
        grid: {
            show: true,
            aboveData: false,
            color: "#3f3f3f",
            labelMargin: 5,
            axisMargin: 0,
            borderWidth: 1,
            borderColor: "#ccc",
            minBorderMargin: 5,
            clickable: true,
            hoverable: true,
            autoHighlight: false,
            mouseActiveRadius: 20
        },
        series: {
            shadowSize: 0,
            lines: {
                show: true,
                fill: true,
                lineWidth: 2
            },
            points: {
                show: true,
                radius: 4.5,
                fill: true,
                fillColor: "#ffffff",
                lineWidth: 2.75
            }
        },
        legend: {
            position: "ne"
        },
        xaxis: {
            //axisLabel: "Category",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: "'Droid Sans',Helvetica,Arial,sans-serif",
            axisLabelPadding: 5,
            //ticks: ticks,
            //tickLength:1,
            rotateTicks: 180
        },
        yaxis: {
            //axisLabel: "Sum of Prices",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: "'Droid Sans',Helvetica,Arial,sans-serif",
            axisLabelPadding: 0
        },
        colors: settings.colors,
        tooltip: true,
        tooltipOpts: {
            content: '%y'
        }
    }
    var final_params = $.extend(true, basic_params, custom_params);
    var holder = $("#chart_preview_" + bid);
    if (holder.length) {
        $.plot(holder, dataset, final_params);
    }
}

function callDashBoardPivotListing(dataArr) {
    var gdel_width = 0, g_height = 185;
    var bid = dataArr['dbID'];
    var js_x_dimension = dataArr['xDimension'];
    var js_y_dimension = dataArr['yDimension'];
    var js_aggregates = dataArr['aggregates'];
    var js_filters = dataArr['filters'];
    var js_data_json = dataArr['dataModel'];
    var js_link_json = dataArr['linkModel'];
    var js_pivot_mode = dataArr['pivotMode'];
    var js_aggr_mode = dataArr['aggrMode'];
    var js_hide_paging = dataArr['hidePaging'];
    var js_row_number = parseInt(dataArr['rowNumber']);
    var js_frozen_cols = dataArr['frozenCols'];
    var js_search_mode = dataArr['searchMode'];
    var js_default_chart = dataArr['defaultChart'];
    var js_grid_id = dataArr['gridID'];
    var js_pager_id = dataArr['pagerID'];
    var pager_active = (js_hide_paging == "Yes") ? false : true;
    var frozen_active = (js_frozen_cols == "Yes") ? true : false;
    var bwidth = $("#content_block_" + bid).width();
    var js_before_req = true;
    var row_num = ($.inArray(js_row_number, pager_row_list) != "-1") ? js_row_number : parseInt(el_theme_settings.pivot_number_of_record);
    row_num = ($.inArray(row_num, pager_row_list) != "-1") ? row_num : 20;

    jQuery("#" + js_grid_id).jqGrid('jqPivot', js_data_json, {
        xDimension: js_x_dimension,
        yDimension: js_y_dimension,
        aggregates: js_aggregates,
        rowTotals: true,
        colTotals: true,
        rowTotalsText: js_aggregates[0]['rowTotalsText'],
        frozenStaticCols: (frozen_active == true) ? true : false,
        frozenShrinkToFit: true
    },
    // grid options
            {
                linkModel: js_link_json,
                pgbuttons: pager_active,
                pginput: pager_active,
                pgnumbers: (el_theme_settings.grid_pgnumbers) ? true : false,
                pgnumlimit: 1, //parseInt(el_theme_settings.grid_pgnumlimit),
                pagingpos: el_theme_settings.grid_pagingpos,
                rowNum: (!pager_active) ? 1000000 : parseInt(row_num),
                rowList: (pager_active) ? pager_row_list : [],
                viewrecords: true,
                altRows: true,
                altclass: 'evenRow',
                norecmsg: js_lang_label.GENERIC_GRID_NO_RECORDS_FOUND,
                hidegrid: false,
                pager: "",
                toppager: pager_active,
                toppaging: pager_active,
                showpaging: pager_active,
                height: 200,
                width: (frozen_active == true) ? bwidth - gdel_width : 650,
                autowidth: (frozen_active == true) ? false : true,
                shrinkToFit: (frozen_active == true) ? false : true,
                fixed: true,
                loadComplete: function (data) {
                    noDashboardRecordsMessage(js_grid_id, data, "pivot");
                    if (js_before_req === true) {
                        if (frozen_active !== true) {
                            resizeDashboardGrid(bid);
                            generateBoardContent(bid, js_default_chart, true);
                        }
                        js_before_req = false;
                    } else {
                        resizeDashboardGrid(bid);
                    }

                },
                gridComplete: function (data) {
                    if (js_search_mode == "Yes") {
                        $("#dsearch_" + bid).show();
                        $("#drefresh_" + bid).show();
                    }
                    //resizeDashboardGrid(bid);
                },
                frozenComplete: function (data) {
                    if (frozen_active == true) {
                        resizeDashboardGrid(bid);
                        generateBoardContent(bid, js_default_chart, true);
                    }
                    if ($("#gview_" + js_grid_id).find(".frozen-div").length) {
                        var tobj = $("#gview_" + js_grid_id).find(".frozen-div").find(".ui-jqgrid-htable")
                        if ($(tobj).find("tr.jqg-third-row-header").length && $(tobj).find("tr.jqg-first-row-header").find("th[role='gridcell']").length > 1) {
                            if ($(tobj).find("tr.jqg-second-row-header").find("th[role='columnheader']").length < 2) {
                                $(tobj).find("tr.jqg-second-row-header").find("th[role='columnheader']").attr("colspan", "2");
                            }
                        }
                    }
                }
            });
//    jQuery("#" + js_grid_id).jqGrid('jqPivot', "http://192.168.30.37/CIBase_Master/branches/V2/2.0.7/data.json", {
//        xDimension: [
//            {
//                dataName: 'CategoryName',
//                label: 'Category Name',
//                labelClass: "header-align-right",
//                width: 200,
//                align: 'right'
//            },
////            {
////                dataName: 'ProductName',
////                label: 'Product Name',
////                labelClass: "header-align-right",
////                width: 200,
////                align: 'right'
////            }
//        ],
//        yDimension: [
//            {
//                dataName: 'Country',
//                label: 'Country',
//                labelClass: "header-align-right",
//                width: 200,
//                align: 'right'
//            }
//        ],
//        aggregates: [
//            {
//                member: 'Quantity',
//                aggregator: 'sum',
//                summaryType: 'sum',
//                rowTotalsText: "Total Qty",
//                label: 'Qty',
//                labelClass: "header-align-right",
//                width: 100,
//                formatter: 'integer',
//                align: 'right',
//            },
//            {
//                member: 'Price',
//                aggregator: 'sum',
//                width: 100,
//                formatter: 'number',
//                label: 'Price',
//                labelClass: "header-align-right",
//                align: 'right',
//                summaryType: 'sum',
//                rowTotalsText: "Total Price",
//            }
//        ],
//        rowTotals: true,
//        colTotals: true,
//        rowTotalsText: "Sum of Price",
//        //frozenStaticCols: true
//    },
//            {
//                // grid options
//                width: 750,
//                height: 400,
//                rowNum: 50,
//                //shrinkToFit: false,
//                pager: "#" + js_pager_id,
//                //caption: "Amounts of each product category"
//            });
}
function callDashBoardGridListing(dataArr) {
    var js_col_name_arr = [], gdel_width = 0;
    var bid = dataArr['dbID'];
    var js_col_name_json = dataArr['colNames'];
    var js_col_model_json = dataArr['colModel'];
    var js_data_json = dataArr['dataModel'];
    var js_link_json = dataArr['linkModel'];
    var js_hide_paging = dataArr['hidePaging'];
    var js_row_number = parseInt(dataArr['rowNumber']);
    var js_frozen_cols = dataArr['frozenCols'];
    var js_search_mode = dataArr['searchMode'];
    var js_grid_id = dataArr['gridID'];
    var js_pager_id = dataArr['pagerID'];
    var pager_active = (js_hide_paging == "Yes") ? false : true;
    var frozen_active = (js_frozen_cols == "Yes") ? true : false;
    var bwidth = $("#content_block_" + bid).width();
    var calc_width = 0, shrink_to_fit = true;
    var row_num = ($.inArray(js_row_number, pager_row_list) != "-1") ? js_row_number : parseInt(el_theme_settings.pivot_number_of_record);
    row_num = ($.inArray(row_num, pager_row_list) != "-1") ? row_num : 20;
    for (var i in js_col_name_json) {
        js_col_name_arr.push(js_col_name_json[i]['label']);
        calc_width += parseInt(js_col_model_json[i]['width'] || 0);
    }
    if (frozen_active == true && calc_width > (bwidth - gdel_width)) {
        shrink_to_fit = false;
    }

    jQuery("#" + js_grid_id).jqGrid({
        data: js_data_json,
        datatype: "local",
        colNames: js_col_name_arr,
        colModel: js_col_model_json,
        linkModel: js_link_json,
        //cellLayout: 31,
        pgbuttons: pager_active,
        pginput: pager_active,
        pgnumbers: (el_theme_settings.grid_pgnumbers) ? true : false,
        pgnumlimit: 1, //parseInt(el_theme_settings.grid_pgnumlimit),
        pagingpos: el_theme_settings.grid_pagingpos,
        rowNum: (!pager_active) ? 1000000 : parseInt(row_num),
        rowList: (pager_active) ? pager_row_list : [],
        viewrecords: true,
        altRows: true,
        altclass: 'evenRow',
        norecmsg: js_lang_label.GENERIC_GRID_NO_RECORDS_FOUND,
        hidegrid: false,
        pager: "",
        toppager: pager_active,
        toppaging: pager_active,
        showpaging: pager_active,
        height: 200,
        width: (shrink_to_fit == false) ? bwidth - gdel_width : 650,
        autowidth: (shrink_to_fit == false) ? false : true,
        shrinkToFit: (shrink_to_fit == false) ? false : true,
        fixed: true,
        loadComplete: function (data) {
            noDashboardRecordsMessage(js_grid_id, data, "grid");
            resizeDashboardGrid(bid);
        },
        gridComplete: function () {
            if (js_search_mode == "Yes") {
                $("#dsearch_" + bid).show();
                $("#drefresh_" + bid).show();
            }
            //resizeDashboardGrid(bid);
        }
    });
    jQuery("#" + js_grid_id).jqGrid('navGrid', '#' + js_pager_id, {
        cloneToTop: true,
        add: false,
        edit: false,
        del: false,
        search: false,
        refresh: false
    }, {
        // edit options 
    }, {
        // add options
    }, {
        //del options
    }, {
        // search options
    });
//    for (var i in js_col_name_json) {
//        jQuery("#" + js_grid_id).jqGrid('setLabel', js_col_name_json[i]['vAliasName'], js_col_name_json[i]['vDisplayName'], js_col_name_json[i]['eAlignment']);
//    }
    if (shrink_to_fit == false) {
        jQuery("#" + js_grid_id).jqGrid('setFrozenColumns');
    }
}
function resizeDashboardGrid(bid) {
    var gdel_width = -1, gdel_height = 1, bdata, js_grid_id, type;
    if (!DB_pivot_data_JSON[bid]) {
        if (!DB_data_list_JSON[bid]) {
            return false;
        } else {
            bdata = DB_data_list_JSON[bid];
            type = "grid";
        }
    } else {
        type = "pivot";
        bdata = DB_pivot_data_JSON[bid];
    }
    js_grid_id = bdata['gridID'];
    if ($("#" + js_grid_id).jqGrid("getGridParam", "autowidth") == true) {
        $("#" + js_grid_id).jqGrid('setGridWidth', $("#content_block_" + bid).width() - gdel_width, true);
    }
    if ($("#dblist2_" + bid).find(".grid-norec-msg").length) {
        $("#dblist2_" + bid).css("width", "inherit");
    }
    var ctop = $("#content_block_" + bid).offset().top;
    var cheight = $("#content_block_" + bid).height();
    var gtop = $("#" + js_grid_id).offset().top;
    var gheight = parseInt(ctop) + parseInt(cheight) - parseInt(gtop) + parseInt(gdel_height);
    $("#" + js_grid_id).jqGrid('setGridHeight', gheight, true);
}
function formatDashBoardEditLink(cval, opt, rowObj) {
    var link_model = $("#" + opt.gid).jqGrid('getGridParam', 'linkModel');
    var $editId = opt.rowId;
    var $editName = opt.colModel.name;
    if (link_model && link_model[$editId] && link_model[$editId][$editName]) {
        return link_model[$editId][$editName];
    } else {
        return cval;
    }

}
function unformatDashBoardEditLink(cval, opt, cl) {
    return cval;
}
function noDashboardRecordsMessage(grid_id, data, type) {
    var temp = false;
    if (type == "grid") {
        temp = (!data || !data.records || data.records == '0') ? true : false;
    } else if (type == "pivot") {
        if ($.isArray(data)) {
            temp = (!data || !data.length) ? true : false;
        } else {
            temp = (!data || !data.records || data.records == '0') ? true : false;
        }
    }

    if (temp) {
        var nrm = $("#" + grid_id);
        var message = $(nrm).jqGrid("getGridParam", "norecmsg");
        var noc = $(nrm).find("tr.jqgfirstrow").find("td").length;
        var nrr = $("<tr />").html("<td colspan='" + noc + "' align='center'><div class='grid-norec-msg'>" + message + "</div></td>");
        $(nrm).append(nrr);
    }
}

function getChartDataFormat(fmt, val) {
    switch (fmt) {
        case 'number':
            val = $.fn.fmatter.number(val, $.jgrid.formatter);
            break;
        case 'integer':
            val = $.fn.fmatter.integer(val, $.jgrid.formatter);
            break;
        case 'currency':
            val = $.fn.fmatter.currency(val, $.jgrid.formatter);
            break;
        default:
            if (fmt != "" && $.isFunction($.fn.fmatter[fmt])) {
                val = $.fn.fmatter[fmt](val, $.jgrid.formatter);
            }
            break;
    }
    return val;
}
function chartCtrlActivity(chart, type) {
    var ret;
    switch (chart) {
        case 'bar':
            if (type == "legend") {
                ret = el_theme_settings.bar_chart_show_legend;
            } else if (type == "position") {
                ret = el_theme_settings.bar_chart_legend_position;
            } else if (type == "xaxis") {
                ret = el_theme_settings.bar_chart_show_xaxis_label;
            } else if (type == "yaxis") {
                ret = el_theme_settings.bar_chart_show_yaxis_label;
            }
            break;
        case 'pie':
            if (type == "legend") {
                ret = el_theme_settings.pie_chart_show_legend;
            } else if (type == "position") {
                ret = el_theme_settings.pie_chart_legend_position;
            } else if (type == "label") {
                ret = el_theme_settings.pie_chart_show_label;
            } else if (type == "style") {
                ret = el_theme_settings.pie_chart_label_style;
            } else if (type == "value") {
                ret = el_theme_settings.pie_chart_label_value;
            }
            break;
        case 'donut':
            if (type == "legend") {
                ret = el_theme_settings.donut_chart_show_legend;
            } else if (type == "position") {
                ret = el_theme_settings.donut_chart_legend_position;
            } else if (type == "label") {
                ret = el_theme_settings.donut_chart_show_label;
            } else if (type == "style") {
                ret = el_theme_settings.donut_chart_label_style;
            } else if (type == "value") {
                ret = el_theme_settings.donut_chart_label_value;
            }
            break;
        case 'area':
            if (type == "legend") {
                ret = el_theme_settings.area_chart_show_legend;
            } else if (type == "position") {
                ret = el_theme_settings.area_chart_legend_position;
            } else if (type == "xaxis") {
                ret = el_theme_settings.area_chart_show_xaxis_label;
            } else if (type == "yaxis") {
                ret = el_theme_settings.area_chart_show_yaxis_label;
            }
            break;
        case 'line':
            if (type == "legend") {
                ret = el_theme_settings.line_chart_show_legend;
            } else if (type == "position") {
                ret = el_theme_settings.line_chart_legend_position;
            } else if (type == "xaxis") {
                ret = el_theme_settings.line_chart_show_xaxis_label;
            } else if (type == "yaxis") {
                ret = el_theme_settings.line_chart_show_yaxis_label;
            }
            break;
        case 'horizbar':
            if (type == "legend") {
                ret = el_theme_settings.horizontal_chart_show_legend;
            } else if (type == "position") {
                ret = el_theme_settings.horizontal_chart_legend_position;
            } else if (type == "xaxis") {
                ret = el_theme_settings.horizontal_chart_show_xaxis_label;
            } else if (type == "yaxis") {
                ret = el_theme_settings.horizontal_chart_show_yaxis_label;
            }
            break;
        case 'stackbar':
            if (type == "legend") {
                ret = el_theme_settings.stacked_bar_chart_show_legend;
            } else if (type == "position") {
                ret = el_theme_settings.stacked_bar_chart_legend_position;
            } else if (type == "xaxis") {
                ret = el_theme_settings.stacked_bar_chart_show_xaxis_label;
            } else if (type == "yaxis") {
                ret = el_theme_settings.stacked_bar_chart_show_yaxis_label;
            }
            break;
        case 'stackhorizbar':
            if (type == "legend") {
                ret = el_theme_settings.stacked_horizontal_bar_chart_show_legend;
            } else if (type == "position") {
                ret = el_theme_settings.stacked_horizontal_bar_chart_legend_position;
            } else if (type == "xaxis") {
                ret = el_theme_settings.stacked_horizontal_bar_chart_show_xaxis_label;
            } else if (type == "yaxis") {
                ret = el_theme_settings.stacked_horizontal_bar_chart_show_yaxis_label;
            }
            break;
        case 'autoupdating':
            if (type == "legend") {
                ret = el_theme_settings.auto_updating_chart_show_legend;
            } else if (type == "position") {
                ret = el_theme_settings.auto_updating_chart_legend_position;
            } else if (type == "xaxis") {
                ret = el_theme_settings.auto_updating_chart_show_xaxis_label;
            } else if (type == "yaxis") {
                ret = el_theme_settings.auto_updating_chart_show_yaxis_label;
            }
            break;

    }
    return ret;
}
function resizeDSGridWidth() {
    if (!$("#dash_board_list").length || !$("#dash_board_container").length) {
        return true;
    }
    var gridster, hmrg, vmrg, twd, gwd;
    hmrg = 5, vmrg = 5;
    twd = $("#dash_board_container").width();
    twd = Math.floor(twd / 6);
    gwd = twd - hmrg - vmrg;

    gridster = $("#dash_board_list").data("gridster");
    gridster.resize_widget_dimensions({
        widget_base_dimensions: [gwd, 50]
    });
    setTimeout(function () {
        $("[id^=iDashBoardId]").each(function () {
            var bid = $(this).val();
            if ($("#dbgrid2_" + bid)) {
                resizeDashboardGrid(bid);
            }
        });
    }, 250);
}
function getDSChartColors(data) {
    if (!('chartOptions' in data)) {
        return chartColours;
    }
    if (!('colorCodes' in data['chartOptions'])) {
        return chartColours;
    }
    var color_codes = $.trim(data['chartOptions']['colorCodes']);
    if (!color_codes) {
        return chartColours;
    }
    if (!$.isArray(color_codes) && typeof color_codes == "string") {
        color_codes = color_codes.split(",");
    }
    if (color_codes.length < 2) {
        return chartColours;
    }
    return color_codes;
}