// Resizing Grid Function
function resizeGridWidth() {
    if (typeof el_tpl_settings.main_grid_id == "undefined" || !el_tpl_settings.main_grid_id) {
        return false;
    }
    if (typeof el_tpl_settings.main_pager_id == "undefined" || !el_tpl_settings.main_pager_id) {
        return false;
    }
    if (typeof el_tpl_settings.main_wrapper_id == "undefined" || !el_tpl_settings.main_wrapper_id) {
        return false;
    }

    if (!$("#" + el_tpl_settings.main_grid_id).length) {
        return false;
    }

    var grid_id = el_tpl_settings.main_grid_id, pager_id = el_tpl_settings.main_pager_id, wrapper_id = el_tpl_settings.main_wrapper_id, del_width = 0, del_pager = 0;
    var auto_width = $("#" + el_tpl_settings.main_grid_id).jqGrid('getGridParam', '_autowidth');
    var main_width = $('#' + wrapper_id).width();

    var grid_left = $("#" + grid_id).offset().left;
    if (grid_left > 410) {
        grid_left = $("#main_content_div").offset().left;
        if ($("#left_search_panel").length && !$("#left_search_panel").is(":hidden")) {
            grid_left += $("#left_search_panel").width();
        }
    }

    del_width += grid_left + el_theme_settings.grid_width_dec;
    del_pager += grid_left + el_theme_settings.grid_width_dec;
    var set_grid_div_width = main_width - del_width;

    //pager width
    var pager_width = $(window).width() - del_pager;
    var top_width = main_width - 25;
    $("#" + pager_id).width(pager_width);
    $("#" + grid_id + "_toppager").width(pager_width);
    if ($("#detail_view_block").length) {
        $("#detail_view_block").width(top_width);
    }

    // grid height
    var adjustArr = [];
    if ($("#" + grid_id).offset().top > 0) {
        adjustArr[0] = ($("#" + grid_id).length) ? $("#" + grid_id).offset().top : 0;
    } else {
        adjustArr[0] = ($('.ui-jqgrid-bdiv:first').length) ? $('.ui-jqgrid-bdiv:first').offset().top : 0;
    }
    adjustArr[1] = ($("#bot_copyright").length) ? $("#bot_copyright").height() : 0;
    adjustArr[2] = ($("#" + pager_id).length) ? $("#" + pager_id).height() : 0;
    adjustArr[3] = ($(".ui-jqgrid-sdiv").find("#hbox_" + grid_id + "_jqgrid").length) ? parseInt($(".ui-jqgrid-sdiv").find("#hbox_" + grid_id + "_jqgrid").height()) : 0;

    var jtot_ht = el_theme_settings.grid_height_dec;
    $.each(adjustArr, function () {
        jtot_ht += parseInt(this) || 0;
    });
    var javail_ht = ($(window).innerHeight() - jtot_ht);
    if (javail_ht > 75) {
        jQuery("#" + grid_id).setGridHeight(javail_ht, true);
    } else {
        jQuery("#" + grid_id).setGridHeight(75, true);
    }

    var cm = $("#" + grid_id).jqGrid("getGridParam", "colModel");
    var final_div_width, col_width = 0, col_total = 0;
    for (var i in cm) {
        if (!cm[i] || !cm[i]['name']) {
            continue;
        }
        if ($.inArray(cm[i]['name'], ['cb', 'rn', 'subgrid', 'prec']) == -1 && cm[i]['hidden'] != true) {
            col_total++;
            if (!auto_width) {
                if ('widthOrg' in cm[i])
                {
                    col_width += parseInt(cm[i]['widthOrg']);
                } else
                {
                    col_width += parseInt(cm[i]['width']);
                }
            }
        }
    }
    if (auto_width) {
        col_width = el_tpl_settings.grid_column_width * col_total;
    }

    // grid width
    var $scroll_body = $(".ui-jqgrid-bdiv:first div:first");
    if (col_width > set_grid_div_width) {
        if (auto_width) {
            $("#" + el_tpl_settings.main_grid_id).setGridParam({'autowidth': false, 'shrinkToFit': false});
        }
        final_div_width = set_grid_div_width - 18;
        if ($($scroll_body).hasScrollBar()) {
            final_div_width = final_div_width - 18;
        }
        jQuery("#" + grid_id).setGridWidth(col_width, true);
        $("#hbox_" + grid_id + "_jqgrid").width(final_div_width).addClass("horizon-header-scroll");
        $("#hbox_" + grid_id + "_jqgrid").find("select").attr("aria-parent-overflow", "true");
        $($scroll_body).width(set_grid_div_width).addClass("horizon-data-scroll");
        $($scroll_body).scroll(function () {
            $("#hbox_" + grid_id + "_jqgrid").scrollLeft($($scroll_body).scrollLeft());
        });
        $("#" + pager_id).width(pager_width);
        $("#" + grid_id + "_toppager").width(pager_width);
    } else {
        if (!auto_width) {
            $("#" + el_tpl_settings.main_grid_id).setGridParam({'autowidth': true, 'shrinkToFit': true});
        }
        jQuery("#" + grid_id).setGridWidth(set_grid_div_width, true);
    }
    if (el_theme_settings.grid_searchopt) {
        var search_toolbar = $("#hbox_" + grid_id + "_jqgrid").find(".ui-search-toolbar");
        var swt;
        $(search_toolbar).find(".ui-search-input").each(function () {
            $(this).siblings(".ui-search-combo").css({"width": "auto"});
            if ($(this).find("select[id^='gs_']").length) {
                if ($(this).find(".chosen-container[id^='gs_']").length) {
                    swt = $(this).find(".chosen-container[id^='gs_']").outerWidth();
                } else {
                    swt = $(this).find("select[id^='gs_']").outerWidth();
                }
            } else {
                swt = $(this).find("input[id^='gs_']").outerWidth();
            }
            $(this).siblings(".ui-search-combo").outerWidth(swt);
        });
    }
}
// Resizing Fancy Grid Function
function resizeFancyGridWidth() {
    if (typeof el_tpl_settings.main_grid_id == "undefined" || !el_tpl_settings.main_grid_id) {
        return false;
    }
    if (typeof el_tpl_settings.main_pager_id == "undefined" || !el_tpl_settings.main_pager_id) {
        return false;
    }
    if (typeof el_tpl_settings.main_wrapper_id == "undefined" || !el_tpl_settings.main_wrapper_id) {
        return false;
    }

    if (!$("#" + el_tpl_settings.main_grid_id).length) {
        return false;
    }

    var grid_id = el_tpl_settings.main_grid_id, pager_id = el_tpl_settings.main_pager_id, wrapper_id = el_tpl_settings.main_wrapper_id, del_width = 0, del_pager = 0;
    var auto_width = $("#" + el_tpl_settings.main_grid_id).jqGrid('getGridParam', '_autowidth');
    var main_width = $('#' + wrapper_id).width();

    var grid_left = $("#" + grid_id).offset().left;
    if (grid_left > 410) {
        grid_left = $("#main_content_div").offset().left;
        if ($("#left_search_panel").length && !$("#left_search_panel").is(":hidden")) {
            grid_left += $("#left_search_panel").width();
        }
    }

    del_width += grid_left + el_theme_settings.grid_width_dec;
    del_pager += grid_left + el_theme_settings.grid_width_dec;
    var set_grid_div_width = main_width - del_width;

    //pager width
    var pager_width = $(window).width() - del_pager;
    var top_width = main_width - 25;
    $("#" + pager_id).width(pager_width);
    $("#" + grid_id + "_toppager").width(pager_width);
    if ($("#detail_view_block").length) {
        $("#detail_view_block").width(top_width);
    }
    jQuery("#" + grid_id).setGridHeight(350, true);

    var cm = $("#" + grid_id).jqGrid("getGridParam", "colModel");
    var final_div_width, col_width, col_total = 0;
    for (var i in cm) {
        if (!cm[i] || !cm[i]['name']) {
            continue;
        }
        if ($.inArray(cm[i]['name'], ['cb', 'rn', 'subgrid', 'prec']) == -1 && cm[i]['hidden'] != true) {
            col_total++;
            if (!auto_width) {
                if ('widthOrg' in cm[i])
                {
                    col_width += parseInt(cm[i]['widthOrg']);
                } else
                {
                    col_width += parseInt(cm[i]['width']);
                }
            }
        }
    }
    if (auto_width) {
        col_width = el_tpl_settings.grid_column_width * col_total;
    }

    // grid width
    var $scroll_body = $(".ui-jqgrid-bdiv:first div:first");
    if (col_width > set_grid_div_width) {
        if (auto_width) {
            $("#" + el_tpl_settings.main_grid_id).setGridParam({'autowidth': false, 'shrinkToFit': false});
        }
        final_div_width = set_grid_div_width - 18;
        if ($($scroll_body).hasScrollBar()) {
            final_div_width = final_div_width - 18;
        }
        jQuery("#" + grid_id).setGridWidth(col_width, true);
        $("#hbox_" + grid_id + "_jqgrid").width(final_div_width).addClass("horizon-header-scroll");
        $("#hbox_" + grid_id + "_jqgrid").find("select").attr("aria-parent-overflow", "true");
        $($scroll_body).width(set_grid_div_width).addClass("horizon-data-scroll");
        $($scroll_body).scroll(function () {
            $("#hbox_" + grid_id + "_jqgrid").scrollLeft($($scroll_body).scrollLeft());
        });
    } else {
        if (!auto_width) {
            $("#" + el_tpl_settings.main_grid_id).setGridParam({'autowidth': true, 'shrinkToFit': true});
        }
        jQuery("#" + grid_id).setGridWidth(set_grid_div_width, true);
    }
    if (el_theme_settings.grid_searchopt) {
        var search_toolbar = $("#hbox_" + grid_id + "_jqgrid").find(".ui-search-toolbar");
        var swt;
        $(search_toolbar).find(".ui-search-input").each(function () {
            $(this).siblings(".ui-search-combo").css({"width": "auto"});
            if ($(this).find("select[id^='gs_']").length) {
                if ($(this).find(".chosen-container[id^='gs_']").length) {
                    swt = $(this).find(".chosen-container[id^='gs_']").outerWidth();
                } else {
                    swt = $(this).find("select[id^='gs_']").outerWidth();
                }
            } else {
                swt = $(this).find("input[id^='gs_']").outerWidth();
            }
            $(this).siblings(".ui-search-combo").outerWidth(swt);
        });
    }
}
// Resizing Grid Function
function resizeSubGridWidth(sub_grid_id) {
    if (typeof el_tpl_settings.main_grid_id == "undefined" || !el_tpl_settings.main_grid_id) {
        return false;
    }
    if (typeof sub_grid_id == "undefined" || !sub_grid_id) {
        return false;
    }
    var grid_id = el_tpl_settings.main_grid_id, del_width = 85;
    var sub_auto_width = $("#" + sub_grid_id).jqGrid('getGridParam', 'autowidth');
    var main_auto_width = $("#" + el_tpl_settings.main_grid_id).jqGrid('getGridParam', 'autowidth');

    if (!sub_auto_width && !main_auto_width) {
        var set_sub_grid_width = $("#hbox_" + sub_grid_id + "_jqgrid .ui-jqgrid-htable:first").width();
        $("#gbox_" + sub_grid_id).width(set_sub_grid_width);
        $("#gview_" + sub_grid_id).width(set_sub_grid_width);
        $("#" + sub_grid_id + "_toppager").width(set_sub_grid_width);
        $("#gview_" + sub_grid_id).find(".ui-jqgrid-hdiv").width(set_sub_grid_width);
        $("#gview_" + sub_grid_id).find(".ui-jqgrid-bdiv").width(set_sub_grid_width);
    } else {
        var main_width = $("#gbox_" + el_tpl_settings.main_grid_id).width();
        var set_sub_grid_width = main_width - del_width;
        jQuery("#" + sub_grid_id).setGridWidth(set_sub_grid_width, true);
    }
}
// Select Multiple rows using shift keys //
function multiSelectHandler(sid, e) {
    var grid = $(e.target).closest("table.ui-jqgrid-btable");
    var ts = grid[0], em = e.target, sb = false;
    if (em.tagName == "LABEL" && $(em).siblings(".cbox").length) {
        if (e.shiftKey) {
            sb = true;
        }
    } else if (em.tagName == "TD" && $(em).attr("role") == "gridcell") {
        sb = true;
    } else if ($(em).hasClass("cbox")) {
        sb = true;
    }
    if (!sb) {
        return false;
    }
    var sel = grid.getGridParam('selarrrow');
    var selected = $.inArray(sid, sel) >= 0;
    if (!e.shiftKey) {
        grid.setSelection(sid, true);
    } else {
        if (e.shiftKey) {
            var six = grid.getInd(sid);
            var min = six, max = six;
            $.each(sel, function () {
                var ix = grid.getInd(this);
                if (ix < min)
                    min = ix;
                if (ix > max)
                    max = ix;
            });
            while (min <= max) {
                var row = ts.rows[min++];
                var rid = row.id;
                if (rid != sid && $.inArray(rid, sel) < 0) {
                    grid.setSelection(row.id, false);
                }
            }
        } else if (!navigator.userAgent.match(/Mobile/g) && !selected) {
            grid.resetSelection();
        }
        if (!selected) {
            grid.setSelection(sid, true);
        } else {
            grid.setSelection(sid, false);
            var osr = grid.getGridParam('onSelectRow');
            if ($.isFunction(osr)) {
                osr(sid, true);
            }
        }
    }
    return true;
}
function formatExpandableLink(cval, opt, rowObj) {
    var sub_mod = $("#" + opt.gid).jqGrid('getGridParam', 'isSubMod');
    var $retLink = cval;
    if (sub_mod == "1") {
        $retLink = '<a href="javascript://" class="expand-nesview" aria-rowid="' + opt.rowId + '" aria-alias="' + opt.colModel.name + '" >' + cval + '</a>';
    } else {
        $retLink = '<a href="javascript://" class="expand-subview" aria-rowid="' + opt.rowId + '" aria-alias="' + opt.colModel.name + '" >' + cval + '</a>';
    }
    return $retLink;
}
// unformat for edit link inline editing
function unformatExpandableLink(cval, opt, cl) {
    return cval;
}
// format for edit link
function formatAdminModuleEditLink(cval, opt, rowObj) {
    var module_url = $("#" + opt.gid).jqGrid('getGridParam', 'curModule');
    var extra = $("#" + opt.gid).jqGrid('getGridParam', 'extraHash');
    var sub_mod = $("#" + opt.gid).jqGrid('getGridParam', 'isSubMod');
    var form_edit_link = false, form_view_link = false, is_edit_popup = false, width = "75%", height = "75%";
    if (sub_mod == "1") {
        if (typeof el_subgrid_settings.permit_edit_btn != "undefined" && el_subgrid_settings.permit_edit_btn == "1") {
            form_edit_link = true;
        } else if (typeof el_subgrid_settings.permit_view_btn != "undefined" && el_subgrid_settings.permit_view_btn == "1") {
            form_view_link = true;
        }
        if (typeof el_subgrid_settings.popup_edit_form != "undefined" && el_subgrid_settings.popup_edit_form == "Yes") {
            if (el_subgrid_settings.popup_edit_size && el_subgrid_settings.popup_edit_size[0]) {
                width = el_subgrid_settings.popup_edit_size[0];
            }
            if (el_subgrid_settings.popup_edit_size && el_subgrid_settings.popup_edit_size[1]) {
                height = el_subgrid_settings.popup_edit_size[1];
            }
            is_edit_popup = true;
        }
    } else if (sub_mod == "2") {
        if (typeof el_nesgrid_settings.permit_edit_btn != "undefined" && el_nesgrid_settings.permit_edit_btn == "1") {
            form_edit_link = true;
        } else if (typeof el_nesgrid_settings.permit_view_btn != "undefined" && el_nesgrid_settings.permit_view_btn == "1") {
            form_view_link = true;
        }
        if (typeof el_nesgrid_settings.popup_edit_form != "undefined" && el_nesgrid_settings.popup_edit_form == "Yes") {
            if (el_nesgrid_settings.popup_edit_size && el_nesgrid_settings.popup_edit_size[0]) {
                width = el_nesgrid_settings.popup_edit_size[0];
            }
            if (el_nesgrid_settings.popup_edit_size && el_nesgrid_settings.popup_edit_size[1]) {
                height = el_nesgrid_settings.popup_edit_size[1];
            }
            is_edit_popup = true;
        }
    } else {
        if (opt.gid == el_tpl_settings.main_grid_id && el_grid_settings.permit_edit_btn == "1") {
            form_edit_link = true;
        } else if (opt.gid == el_tpl_settings.main_grid_id && el_grid_settings.permit_view_btn == "1") {
            form_view_link = true;
        }
        if (el_grid_settings.popup_edit_form == "Yes") {
            if (el_grid_settings.popup_edit_size && el_grid_settings.popup_edit_size[0]) {
                width = el_grid_settings.popup_edit_size[0];
            }
            if (el_grid_settings.popup_edit_size && el_grid_settings.popup_edit_size[1]) {
                height = el_grid_settings.popup_edit_size[1];
            }
            is_edit_popup = true;
        }
    }
    var $retLink = cval;
    if (form_edit_link) {
        var $editId = opt.rowId;
        var $load_url = admin_url + "#" + module_url + "|mode|" + cus_enc_mode_json['Update'] + "|id|" + $editId;
        if (extra) {
            $load_url += extra;
        }
        var popup_class = "";
        if (isFancyBoxActive() && 0) {
            popup_class = restrictFancyBoxClass();
        } else if (is_edit_popup) {
            popup_class = " fancybox-hash-iframe";
            $load_url += "|width|" + width + "|height|" + height + "|hideCtrl|true|loadGrid|" + opt.gid;
        }
        var title_attr = '';
        if (!isHTML(cval)) {
            title_attr = 'title="' + cval + '"';
        }
        $retLink = '<a class="inline-edit-link ' + popup_class + '" href="' + $load_url + '" ' + title_attr + '>' + cval + '</a>';
    } else if (form_view_link) {
        var $editId = opt.rowId;
        var $load_url = admin_url + "#" + module_url + "|mode|" + cus_enc_mode_json['View'] + "|id|" + $editId;
        if (extra) {
            $load_url += extra;
        }
        var popup_class = "";
        if (isFancyBoxActive() && 0) {
            popup_class = restrictFancyBoxClass();
        } else if (is_edit_popup) {
            popup_class = " fancybox-hash-iframe";
            $load_url += "|width|" + width + "|height|" + height + "|hideCtrl|true|loadGrid|" + opt.gid;
        }
        var title_attr = '';
        if (!isHTML(cval)) {
            title_attr = 'title="' + cval + '"';
        }
        $retLink = '<a class="inline-edit-link ' + popup_class + '" href="' + $load_url + '" ' + title_attr + '>' + cval + '</a>';
    }
    return $retLink;
}
// unformat for edit link inline editing
function unformatAdminModuleEditLink(cval, opt, cl) {
    return cval;
}
// format for edit link
function formatAdminModuleCustomEditLink(cval, opt, rowObj) {
    var sub_mod = $("#" + opt.gid).jqGrid('getGridParam', 'isSubMod');
    var $editId = opt.rowId;
    var $editname = opt.colModel.name;
    var $retLink = cval;
    if (sub_mod == "0" && el_general_settings.grid_main_link_model) {
        if (el_general_settings.grid_main_link_model[$editId] && el_general_settings.grid_main_link_model[$editId][$editname]) {
            var temp = $("<b />").html(cval);
            var $final_val = cval;
            if ($(temp).find("a").hasClass("inline-edit-link")) {
                var $final_val = $(cval).html();
            }
            var title_attr = '';
            if (!isHTML($final_val)) {
                title_attr = 'title="' + $final_val + '"';
            }
            var $load_url = el_general_settings.grid_main_link_model[$editId][$editname].link
            var $extra_attr_str = el_general_settings.grid_main_link_model[$editId][$editname].extra_attr_str;
            $retLink = '<a href="' + $load_url + '" ' + $extra_attr_str + ' ' + title_attr + '>' + $final_val + '</a>';
        }
    } else if (sub_mod == "1" && el_general_settings.grid_sub_link_model) {
        if (el_general_settings.grid_sub_link_model[$editId] && el_general_settings.grid_sub_link_model[$editId][$editname]) {
            var temp = $("<b />").html(cval);
            var $final_val = cval;
            if ($(temp).find("a").hasClass("inline-edit-link")) {
                var $final_val = $(cval).html();
            }
            var title_attr = '';
            if (!isHTML($final_val)) {
                title_attr = 'title="' + $final_val + '"';
            }
            var $load_url = el_general_settings.grid_sub_link_model[$editId][$editname].link
            var $extra_attr_str = el_general_settings.grid_sub_link_model[$editId][$editname].extra_attr_str;
            $retLink = '<a href="' + $load_url + '" ' + $extra_attr_str + ' ' + title_attr + '>' + $final_val + '</a>';
        }
    } else if (sub_mod == "2" && el_general_settings.grid_nes_link_model) {
        if (el_general_settings.grid_nes_link_model[$editId] && el_general_settings.grid_nes_link_model[$editId][$editname]) {
            var temp = $("<b />").html(cval);
            var $final_val = cval;
            if ($(temp).find("a").hasClass("inline-edit-link")) {
                var $final_val = $(cval).html();
            }
            var title_attr = '';
            if (!isHTML($final_val)) {
                title_attr = 'title="' + $final_val + '"';
            }
            var $load_url = el_general_settings.grid_nes_link_model[$editId][$editname].link
            var $extra_attr_str = el_general_settings.grid_nes_link_model[$editId][$editname].extra_attr_str;
            $retLink = '<a href="' + $load_url + '" ' + $extra_attr_str + ' ' + title_attr + '>' + $final_val + '</a>';
        }
    }

    if ($retLink == null) {
        $retLink = '';
    }
    return $retLink;
}
// unformat for edit link inline editing
function unformatAdminModuleCustomEditLink(cval, opt, cl) {
    return cval;
}
// format for lisitng data
function formatAdminListingData(cval, opt, rowObj) {
    return cval;
}
// unformat for lisitng data
function unformatAdminListingData(cval, opt, cl) {
    switch (opt.colModel.ctrl_type) {
        case 'phone_number' :
            if (!cval) {
                return cval;
            }
            var split_val = cval.split("");
            var ret_val = '';
            var test_reg = /^[a-zA-Z0-9]+$/;
            for (var i = 0; i < split_val.length; i++) {
                if (test_reg.test(split_val[i])) {
                    ret_val += split_val[i];
                }
            }
            cval = ret_val;
            break;
    }
    return cval;
}
// format for edit link
function formatAdminModuleRatingLink(cval, opt, rowObj) {
    if (!opt.colModel.ratyallow || !opt.colModel.ratyevents) {
        return cval;
    }
    var raty_events = opt.colModel.ratyevents;
    var raty_elem = $('<span />').addClass("rating-icons-block");
    activateRatingMasterEvent(raty_elem, opt.colModel.ratyevents.raty.params, opt.colModel.ratyevents.raty.hints, cval)
    var $retLink = $(raty_elem)[0].outerHTML;
    return $retLink;
}
// unformat for edit link inline editing
function unformatAdminModuleRatingLink(cval, opt, cl) {
    return cval;
}
// inline date picker integration
function inlineDateTimePicker(rowid, cellName, type) {
    var jfmtStr = $("#" + rowid + "_" + cellName).attr("format");
    var date_obj = {};
    switch (type) {
        case "date":
            date_obj['dateFormat'] = $("#" + rowid + "_" + cellName).attr("aria-date-format");
            date_obj['minDate'] = $("#" + rowid + "_" + cellName).attr("aria-min-date");
            date_obj['maxDate'] = $("#" + rowid + "_" + cellName).attr("aria-max-date");
            break;
        case "dateTime":
            date_obj['dateFormat'] = $("#" + rowid + "_" + cellName).attr("aria-date-format");
            date_obj['timeFormat'] = $("#" + rowid + "_" + cellName).attr("aria-time-format");
            date_obj['showSecond'] = $("#" + rowid + "_" + cellName).attr("aria-enable-sec");
            date_obj['ampm'] = $("#" + rowid + "_" + cellName).attr("aria-enable-ampm");
            date_obj['minDate'] = $("#" + rowid + "_" + cellName).attr("aria-min-date");
            date_obj['maxDate'] = $("#" + rowid + "_" + cellName).attr("aria-max-date");
            break;
        case "time":
            date_obj['timeFormat'] = $("#" + rowid + "_" + cellName).attr("aria-time-format");
            date_obj['showSecond'] = $("#" + rowid + "_" + cellName).attr("aria-enable-sec");
            date_obj['ampm'] = $("#" + rowid + "_" + cellName).attr("aria-enable-ampm");
            break;
    }

    activeDateTimePicker($("#" + rowid + "_" + cellName), type, date_obj);
}
function activeDateTimePicker(eleObj, type, jfmtArr) {
    switch (type) {
        case 'date' :
            var min_max_obj = {};
            if (jfmtArr['minDate'] && jfmtArr['minDate'] !== "") {
                min_max_obj["minDate"] = (jfmtArr['minDate'] == "0") ? 0 : jfmtArr['minDate'];
            }
            if (jfmtArr['maxDate'] && jfmtArr['maxDate'] !== "") {
                min_max_obj["maxDate"] = (jfmtArr['maxDate'] == "0") ? 0 : jfmtArr['maxDate'];
            }
            var base_obj = {
                dateFormat: jfmtArr['dateFormat'],
                showOn: 'focus',
                changeMonth: true,
                changeYear: true,
                yearRange: 'c-100:c+100',
                onClose: function (dateText, inst) {
                    $(this).focus();
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
                    $(this).focus();
                }
            });
            break;
        case 'dateTime' :
            var min_max_obj = {};
            if (jfmtArr['minDate'] && jfmtArr['minDate'] !== "") {
                min_max_obj["minDate"] = (jfmtArr['minDate'] == "0") ? 0 : jfmtArr['minDate'];
            }
            if (jfmtArr['maxDate'] && jfmtArr['maxDate'] !== "") {
                min_max_obj["maxDate"] = (jfmtArr['maxDate'] == "0") ? 0 : jfmtArr['maxDate'];
            }
            var base_obj = {
                //controlType: 'select',
                dateFormat: jfmtArr['dateFormat'],
                timeFormat: jfmtArr['timeFormat'],
                showSecond: (jfmtArr['showSecond'] == "true" || jfmtArr['showSecond'] == '1' || jfmtArr['showSecond'] === true) ? true : false,
                ampm: (jfmtArr['ampm'] == "true" || jfmtArr['ampm'] == '1' || jfmtArr['ampm'] === true) ? true : '',
                showOn: 'focus',
                changeMonth: true,
                changeYear: true,
                yearRange: 'c-100:c+100',
                onClose: function (dateText, inst) {
                    $(this).focus();
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
// date picker format json
function getInlineDatePickerFormat(type, jfmtStr) {
    jfmtStr = $.trim(jfmtStr);
    var jaccArr = ["dateFormat", "timeFormat", "showSecond", "ampm"];
    var jsfmt = {}
    switch (type) {
        case 'date' :
            jfmtStr = (jfmtStr) ? jfmtStr : "yy-mm-dd";
            jsfmt['dateFormat'] = jfmtStr;
            break;
        case 'dateTime' :
            jsfmt['dateFormat'] = "yy-mm-dd";
            jsfmt['timeFormat'] = 'HH:mm:ss';
            jsfmt['showSecond'] = false;
            jsfmt['ampm'] = false;
            if (jfmtStr) {
                var jsplArr = jfmtStr.split("|||");
                jsfmt['dateFormat'] = jsplArr[0];
                jsfmt['timeFormat'] = jsplArr[1];
                var timeStr = $.trim(jsplArr[2]);
                if (timeStr) {
                    var jtimeArr = timeStr.split("@");
                    if (jtimeArr[0] == 'ampm' || jtimeArr[0] == 'showSecond') {
                        jsfmt[jtimeArr[0]] = (jtimeArr[1] == true) ? true : false;
                    }
                }
            }
            break;
        case 'time' :
            jsfmt['timeFormat'] = 'HH:mm:ss';
            jsfmt['showSecond'] = false;
            jsfmt['ampm'] = false;
            if (jfmtStr) {
                var jsplArr = jfmtStr.split("|||");
                jsfmt['timeFormat'] = jsplArr[0];
                var timeStr_1 = $.trim(jsplArr[1]);
                var timeStr_2 = $.trim(jsplArr[2]);
                if (timeStr_1) {
                    var jtimeArr_1 = timeStr_1.split("@");
                    if (jtimeArr_1[0] == 'ampm' || jtimeArr_1[0] == 'showSecond') {
                        jsfmt[jtimeArr_1[0]] = jtimeArr_1[1];
                    }
                }
                if (timeStr_2) {
                    var jtimeArr_2 = timeStr_2.split("@");
                    if (jtimeArr_2[0] == 'ampm' || jtimeArr_2[0] == 'showSecond') {
                        jsfmt[jtimeArr_2[0]] = jtimeArr_2[1];
                    }
                }
            }
            break;
    }
    return jsfmt;
}
// filtration for date wise
function filterDateWiseResult(grid_id) {
    var sfilter_grid = $("#" + grid_id);
    sfilter_grid[0].triggerToolbar();
}
function inlineColorPicker(rowid, cellName, type) {
    var color_preview = $("#" + rowid + "_" + cellName).attr("color_preview");
    activateColorPicker($("#" + rowid + "_" + cellName), color_preview);
}
function activateColorPicker(colorobj, color_preview_param) {
    $(colorobj).colpick({
        eventName: 'click',
        onShow: function (colpkr) {
            $(colpkr).fadeIn(500);
            if (color_preview_param == "Yes")
                $(this).css('backgroundColor', $(this).val());
            return false;
        },
        onHide: function (colpkr) {
            $(colpkr).fadeOut(500);
            return false;
        },
        onChange: function (hsb, hex, rgb, el, bySetColor) {
            //$('#".$htmlID."').css('backgroundColor', '#' + hex);
        },
        onSubmit: function (hsb, hex, rgb, el) {
            $(el).val('#' + hex);
            //$(el).val("rgb("+rgb.r+","+rgb.g+","+rgb.b+")");
            if (color_preview_param == "Yes")
                $(el).css('backgroundColor', '#' + hex);

            $(el).unbind("focus");
            $(el).colpickHide();
            $(el).focus();
        }
    }).bind('keyup', function () {
        $(this).colpickSetColor(this.value);
    });

    $(colorobj).bind('focus', function () {
        $(this).colpickShow();
        $(this).colpickSetColor(this.value);
        //$(this).colpickSetColor(getColorValObject(this.value,"rgb"));
    });
    if (el_general_settings.mobile_platform) {
        $(colorobj).attr('readonly', true);
    }
}
// for export functionality
function getColumnsDropDown(grid_id, id, name, cls, extra) {
    var column_arr = $.grep($("#" + grid_id).getGridParam("colModel"), function (n, i) {
        if (n.name == 'subgrid' || n.name == 'cb' || n.name == 'prec') {
            return false;
        }
        return true;
    });
    var str = "<select id='" + id + "' name='" + name + "' class='" + cls + "' " + extra + " >";
    for (var j in column_arr) {
        if ('export' in column_arr[j] && column_arr[j]['export'] == false) {
            continue;
        }
        if (column_arr[j]['hidden']) {
            str += "<option value=" + column_arr[j]['name'] + ">" + column_arr[j]['label'] + "</option>";
        } else {
            str += "<option value=" + column_arr[j]['name'] + " selected=true>" + column_arr[j]['label'] + "</option>";
        }
    }
    str += "</select>";
    return str;
}
function exportData(grid_id, option, export_url) {
    var export_len = $("input[name='export_type']:checked").length;
    var export_mode = $("input[name='export_mode']:checked").val();
    if (export_mode == "selected" && $("#export_columns_list").multiselect("getChecked").length <= 0) {
        jQuery.jgrid.info_dialog(js_lang_label.GENERIC_GRID_EXPORT, js_lang_label.GENERIC_GRID_PLEASE_SELECT_ATLEAST_ONE_COLUMN, js_lang_label.GENERIC_GRID_OK);
        return false;
    } else if (export_mode == "selected" && !export_len) {
        jQuery.jgrid.info_dialog(js_lang_label.GENERIC_GRID_EXPORT, js_lang_label.GENERIC_PLEASE_SELECT_EXPORT_TYPE, js_lang_label.GENERIC_GRID_OK);
        return false;
    } else {
        var columns = base64_encode((JSON.stringify($("#export_columns_list").val())));
        var options = '';
        if (option == 'thispage') {
            options = 'page=' + $('#' + grid_id).getGridParam('page') + '&rowlimit=' + $("select[role='listbox']").val();
        } else if (option == 'selected') {
            var selids = $('#' + grid_id).getGridParam('selarrrow');
            if ($.isArray(selids) && selids.length > 0) {
                options = 'id=' + selids.join(",") + "&selected=true";
            } else {
                var unselected_msg = (js_lang_label.GENERIC_GRID_PLEASE_SELECT_ATLEAST_ONE_RECORD) ? js_lang_label.GENERIC_GRID_PLEASE_SELECT_ATLEAST_ONE_RECORD : "Please select atleast one record";
                jQuery.jgrid.info_dialog(js_lang_label.GENERIC_GRID_EXPORT, unselected_msg, js_lang_label.GENERIC_GRID_OK);
                return false;
            }
        }
        if ($("#" + grid_id + " tr").length <= 0) {
            jQuery.jgrid.info_dialog(js_lang_label.GENERIC_GRID_EXPORT, js_lang_label.GENERIC_THERE_IS_NO_DATA_TO_EXPORT, js_lang_label.GENERIC_GRID_OK);
        } else {
            var filters = base64_encode($("#" + grid_id).getGridParam("postData").filters);
            var sidx = $("#" + grid_id).getGridParam("postData").sidx;
            var sord = $("#" + grid_id).getGridParam("postData").sord;
            var sdef = $("#" + grid_id).getGridParam("postData").sdef;
            if (filters == undefined) {
                filters = '';
            }
            var export_type = $("input[name='export_type']:checked").val();
            var orientation = $("input[name='orientation_type']:checked").val();

            export_url += '&filters=' + filters + '&fields=' + columns + '&' + options + '&sidx=' + sidx + '&sord=' + sord + '&sdef=' + sdef;
            export_url += '&export_type=' + export_type + '&export_mode=' + export_mode + '&orientation=' + orientation;
            window.location = export_url;
        }
        $("#exportmod_" + grid_id).dialog("destroy").remove();
    }
}
function printData(grid_id, option, print_url, params) {
    var options = '';
    if (option == 'thispage') {
        options = 'page=' + $('#' + grid_id).getGridParam('page') + '&rowlimit=' + $("select[role='listbox']").val();
    } else if (option == 'selected') {
        var selids = $('#' + grid_id).getGridParam('selarrrow');
        if ($.isArray(selids) && selids.length > 0) {
            options = 'id=' + selids.join(",") + "&selected=true";
        } else {
            var unselected_msg = (js_lang_label.GENERIC_GRID_PLEASE_SELECT_ATLEAST_ONE_RECORD) ? js_lang_label.GENERIC_GRID_PLEASE_SELECT_ATLEAST_ONE_RECORD : "Please select atleast one record";
            jQuery.jgrid.info_dialog(js_lang_label.GENERIC_GRID_PRINT, unselected_msg, js_lang_label.GENERIC_GRID_OK);
            return false;
        }
    }
    if ($("#" + grid_id + " tr").length <= 0) {
        jQuery.jgrid.info_dialog(js_lang_label.GENERIC_GRID_PRINT, js_lang_label.GENERIC_THERE_IS_NO_DATA_TO_PRINT, js_lang_label.GENERIC_GRID_OK);
    } else {
        var filters = base64_encode($("#" + grid_id).getGridParam("postData").filters);
        var sidx = $("#" + grid_id).getGridParam("postData").sidx;
        var sord = $("#" + grid_id).getGridParam("postData").sord;
        var sdef = $("#" + grid_id).getGridParam("postData").sdef;
        if (filters == undefined) {
            filters = '';
        }
        var final_url = print_url + '&filters=' + filters + '&' + options + '&sidx=' + sidx + '&sord=' + sord + '&sdef=' + sdef;
        openCustomURLFancyBox(final_url, params);
    }
    $("#printmod_" + grid_id).dialog("destroy").remove();
}
// restoring all editable cells before editing any cell
function restoreBeforeEditedCell(eleObj, jrow, jcol, jsave) {
    if (jrow == 0 || jcol == 0) {
        return;
    }
    if (eleObj.p.savedRow.length == 0) {
        return;
    }
    var cc = $("td:eq(" + jcol + ")", eleObj.rows[jrow]);
    $(cc).empty().attr("tabindex", "-1");
    $(eleObj).jqGrid("setCell", eleObj.rows[jrow].id, jcol, jsave, false, false, true);
}
// Add new record from url
function adminAddNewRecord(module_url, jextra, is_popup, grid_id, size) {
    var $load_url = module_url + "|mode|" + cus_enc_mode_json["Add"];
    if (jextra) {
        $load_url += jextra;
    }
    var $add_form_url = $load_url;
    if (is_popup == "Yes") {
        var width = "75%", height = "75%";
        if (size && size[0]) {
            width = size[0]
        }
        if (size && size[1]) {
            height = size[1]
        }
        $add_form_url += "|width|" + width + "|height|" + height + "|hideCtrl|true|loadGrid|" + grid_id;
        var params_obj = getHASHToFancyParams($add_form_url);
        var req_uri = convertHASHToURL($add_form_url, true);
        openURLFancyBox(req_uri, params_obj);
    } else {
        window.location.hash = $add_form_url;
    }
}
// Status change
function adminStatusChange(grid_id, status, ids, mod_edit_url, status_lang, callbacks, messages) {
    if (!$.isArray(ids) || !ids.length) {
        var alert_msg = js_lang_label.GENERIC_PLEASE_SELECT_REC_TO_CHANGE_STATUS;
        if ('status_alert' in messages && messages['status_alert'] != "") {
            alert_msg = messages.status_alert;
        }
        jqueryUIalertBox(ci_js_validation_message(alert_msg, '#STATUS#', status_lang), status_lang);
        return false;
    }
    var popup_msg = js_lang_label.GENERIC_ARE_YOU_WANT_CHANGE_STATUS;
    if ('status_popup' in messages && messages['status_popup'] != "") {
        popup_msg = messages.status_popup;
    }
    var label_elem = '<div />';
    var label_text = ci_js_validation_message(popup_msg, '#STATUS#', status_lang);
    var postdata = {
        "oper": "status",
        "status": status,
        "id": ids.join(","),
        "AllRowSelected": $('#selAllRows').val(),
        "filters": $("#" + grid_id).getGridParam("postData").filters
    };
    if (callbacks['before_status_change'] && $.isFunction(window[callbacks['before_status_change']])) {
        var addpost = window[callbacks['before_status_change']](postdata);
        if ($.isPlainObject(addpost)) {
            postdata = $.extend(addpost, postdata);
        }
    }
    var option_params = {
        title: status_lang,
        dialogClass: "dialog-confirm-box grid-status-btn-cnf",
        buttons: [
            {
                text: js_lang_label.GENERIC_OK,
                bt_type: 'ok',
                click: function () {
                    $.ajax({
                        url: mod_edit_url,
                        type: 'POST',
                        data: postdata,
                        success: function (response) {
                            var resdata = parseJSONString(response);
                            if (resdata.success == 'true') {
                                reloadListGrid(grid_id);
                                var $jq_errmsg = ci_js_validation_message(js_lang_label.GENERIC_RECORDS_STATUS_CHANGED_SUCCESS, '#STATUS#', status_lang);
                                if (resdata.message != "") {
                                    $jq_errmsg = resdata.message;
                                }
                                refreshLeftSearchPanel(grid_id);
                            } else {
                                var $jq_errmsg = ci_js_validation_message(js_lang_label.GENERIC_ERROR_IN_UPDATE_STATUS, '#STATUS#', status_lang);
                                if (resdata.message != "") {
                                    $jq_errmsg = resdata.message;
                                }
                            }
                            gridReportMessage(resdata.success, $jq_errmsg);
                            if (callbacks['after_status_change'] && $.isFunction(window[callbacks['after_status_change']])) {
                                window[callbacks['after_status_change']](response);
                            }
                        }
                    });
                    $(this).remove();
                }
            },
            {
                text: js_lang_label.GENERIC_CANCEL,
                bt_type: 'cancel',
                click: function () {
                    $(this).remove();
                }
            }
        ]
    }
    jqueryUIdialogBox(label_elem, label_text, option_params);
}
function gridReportMessage(success, message) {
    var jmgcls = 2;
    if (success === "true") {
        jmgcls = 1;
    } else if (success === "false") {
        jmgcls = 0;
    } else {
        jmgcls = (!success) ? 0 : 1;
    }    
    Project.setMessage(message, jmgcls, 10);
}
function hideAdminDataCheckBox(grid_id, js_list_arr) {
    for (var i in js_list_arr) {
        if (!js_list_arr[i]) {
            continue;
        }
        var ad_chkid = "jqg_" + grid_id + "_" + js_list_arr[i];
        $("#" + grid_id).find("input[type='checkbox'][id='" + ad_chkid + "']").remove();
    }
}
function getAdminImageTooltip(grid_id) {
    if (grid_id) {
        if ($("#" + grid_id).find('.inline-image-jip').length) {
            $("#" + grid_id).find('.inline-image-jip').each(function () {
                var js_image_src = $(this).attr('src');
                $(this).qtip({
                    content: "<img src='" + js_image_src + "' alt='Image' />"
                });
            });
        } else {
            $("#" + grid_id).find('.anc-image-jip').each(function () {
                var js_image_src = $(this).attr('href');
                $(this).qtip({
                    content: "<img src='" + js_image_src + "' alt='Image' />"
                });
            });
        }
    } else {
        if ($('.inline-image-jip').length) {
            $('.inline-image-jip').each(function () {
                var js_image_src = $(this).attr('src');
                $(this).qtip({
                    content: "<img src='" + js_image_src + "' alt='Image' />"
                });
            });
        } else {
            $('.anc-image-jip').each(function () {
                var js_image_src = $(this).attr('href');
                $(this).qtip({
                    content: "<img src='" + js_image_src + "' alt='Image' />"
                });
            });
        }
    }
}
function getColorValObject(val, type) {
    var obj = "";
    if (val != "" && type != "") {
        if (type == "rgb") {
            val = val.replace('rgb(', '');
            val = val.replace(')', '');
            if (val != "") {
                obj = {};
                var val_arr = val.split(",");
                obj.r = (val_arr[0] !== undefined) ? val_arr[0] : 0;
                obj.g = (val_arr[1] !== undefined) ? val_arr[1] : 0;
                obj.b = (val_arr[2] !== undefined) ? val_arr[2] : 0;
            }
        }
    }
    return obj;
}
//tooltip for listing
function createTooltipHeading(id, url, params) {
    if (!$("#anc_module_help").length) {
        return;
    }
    var txt = $("#txt_module_help").val();
    $("#anc_module_help").popover({
        placement: "bottom",
        html: 'true',
        content: txt
    }).click(function (e) {
        e.preventDefault();
    });
}
//grid cell editing events
function initGridChosenEvent(elem, opt) {
    $(elem).chosen({
        allow_single_deselect: true
    });
}
function initEditGridAjaxChosenEvent(elem) {
    $(elem).chosen({
        allow_single_deselect: true
    });
    var add_id = $(elem).attr("aria-unique-name");
    var update_id = $(elem).attr("aria-update-id");
    var g_id = $(elem).attr("aria-grid-id");
    var sub_mod = $("#" + g_id).jqGrid('getGridParam', 'isSubMod');
    var ajax_chosen_url = el_grid_settings.ajax_data_url;
    if (sub_mod == "1") {
        ajax_chosen_url = el_subgrid_settings.ajax_data_url;
    } else if (sub_mod == "2") {
        ajax_chosen_url = el_nesgrid_settings.ajax_data_url;
    }
    $(elem).ajaxChosen({
        dataType: 'json',
        type: 'POST',
        url: ajax_chosen_url + "&mode=" + cus_enc_mode_json['Update'] + "&unique_name=" + add_id + "&id=" + update_id
    }, {
        loadingImg: admin_image_url + "chosen-loading.gif"
    });
}
function initEditGridMaskingEvent(elem) {
    var ph_fmt = $(elem).attr("aria-phone-format");
    $(elem).mask(ph_fmt);
}
function initEditGridElasticEvent(elem) {
    setTimeout(function () {
        $(elem).elastic().css('height', '20px');
    }, 50);
}
//grid search events
function initSearchGridAjaxChosenEvent(elem, opt) {
    var g_id, add_id;
    $(elem).chosen({
        allow_single_deselect: true
    });
    if (opt && opt['attr'] && opt['attr']['aria-grid-id']) {
        add_id = opt['attr']["aria-unique-name"];
        g_id = opt['attr']["aria-grid-id"];
    } else {
        add_id = $(elem).attr("aria-unique-name");
        g_id = $(elem).attr("aria-grid-id");
    }
    var sub_mod = $("#" + g_id).jqGrid('getGridParam', 'isSubMod');
    var ajax_chosen_url = el_grid_settings.ajax_data_url;
    if (sub_mod == "1") {
        ajax_chosen_url = el_subgrid_settings.ajax_data_url;
    } else if (sub_mod == "2") {
        ajax_chosen_url = el_nesgrid_settings.ajax_data_url;
    }
    $(elem).ajaxChosen({
        dataType: 'json',
        type: 'POST',
        url: ajax_chosen_url + "&mode=" + cus_enc_mode_json['Search'] + "&unique_name=" + add_id
    }, {
        loadingImg: admin_image_url + "chosen-loading.gif"
    });
}
function initSearchGridDateRangePicker(elem, opt) {
    var g_id, d_format, d_ranges, d_months, d_weeks;
    if (opt && opt['attr'] && opt['attr']['aria-grid-id']) {
        g_id = opt['attr']["aria-grid-id"]
        d_format = opt['attr']["aria-date-format"];
    } else {
        g_id = $(elem).attr("aria-grid-id");
        d_format = $(elem).attr("aria-date-format");
    }
    d_ranges = getRangePickerQuickList();
    d_months = getRangePickerMonthNames();
    d_weeks = getRangePickerWeekNames();
    $(elem).daterangepicker({
        ranges: d_ranges,
        opens: 'left',
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        showDropdowns: true,
        locale: {
            format: d_format,
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
    $(elem).on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format(picker.locale.format) + picker.locale.separator + picker.endDate.format(picker.locale.format));
        if ($(this).attr('id').substr(0, 3) === 'gs_') {
            filterDateWiseResult(g_id);
        } else {
            $(this).trigger('change');
        }
    });
    $(elem).on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        if ($(this).attr('id').substr(0, 3) === 'gs_') {
            filterDateWiseResult(g_id);
        } else {
            $(this).trigger('change');
        }
    });
    $(elem).on('hidden', function (e) {
        $(e.target).trigger('change');
    });
    if (el_general_settings.mobile_platform) {
        $(elem).attr('readonly', true);
    }
}
function initSearchGridDateTimePicker(elem, opt) {
    var g_id, d_format, d_ranges, d_months, d_weeks, e_time;
    if (opt && opt['attr'] && opt['attr']['aria-grid-id']) {
        g_id = opt['attr']["aria-grid-id"]
        d_format = opt['attr']["aria-date-format"];
        e_time = opt['attr']['aria-enable-time'];
    } else {
        g_id = $(elem).attr("aria-grid-id");
        d_format = $(elem).attr("aria-date-format");
        e_time = $(elem).attr('aria-enable-time');
    }
    d_ranges = getRangePickerQuickList();
    d_months = getRangePickerMonthNames();
    d_weeks = getRangePickerWeekNames();
    $(elem).daterangepicker({
        ranges: d_ranges,
        opens: 'left',
        timePicker: true,
        timePickerIncrement: 1,
        timePicker12Hour: (e_time == 'false') ? false : true,
        showDropdowns: true,
        locale: {
            format: d_format,
            separator: ' to ',
            applyLabel: js_lang_label.GENERIC_APPLY,
            cancelLabel: js_lang_label.GENERIC_CLEAR,
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
    $(elem).on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format(picker.locale.format) + picker.locale.separator + picker.endDate.format(picker.locale.format));
        if ($(this).attr('id').substr(0, 3) === 'gs_') {
            filterDateWiseResult(g_id);
        } else {
            $(this).trigger('change');
        }
    });
    $(elem).on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        if ($(this).attr('id').substr(0, 3) === 'gs_') {
            filterDateWiseResult(g_id);
        } else {
            $(this).trigger('change');
        }
    });
    $(elem).on('hidden', function (e) {
        $(e.target).trigger('change');
    });
    if (el_general_settings.mobile_platform) {
        $(elem).attr('readonly', true);
    }
}
function initSearchGridTimePicker(elem, opt) {
    var g_id, t_format, e_sec, e_ampm;
    if (opt && opt['attr'] && opt['attr']['aria-grid-id']) {
        g_id = opt['attr']["aria-grid-id"]
        t_format = opt['attr']["aria-time-format"];
        e_sec = opt['attr']['aria-enable-sec'];
        e_ampm = opt['attr']['aria-enable-ampm'];
    } else {
        g_id = $(elem).attr("aria-grid-id");
        t_format = $(elem).attr('aria-time-format');
        e_sec = $(elem).attr('aria-enable-sec');
        e_ampm = $(elem).attr('aria-enable-ampm');
    }
    $(elem).timepicker({
        timeFormat: t_format,
        showSecond: (e_sec == "false") ? false : true,
        ampm: (e_ampm == "false") ? false : true,
        showOn: 'focus',
        onClose: function (dateText, inst) {
            if ($(elem).attr('id').substr(0, 3) === 'gs_') {
                filterDateWiseResult(g_id);
            } else {
                $(elem).trigger('change');
            }
        }
    });
    if (el_general_settings.mobile_platform) {
        $(elem).attr('readonly', true);
    }
}
function initSearchGridColorPickerEvent(elem, opt) {
    var g_id;
    if (opt && opt['attr'] && opt['attr']['aria-grid-id']) {
        g_id = opt['attr']["aria-grid-id"]
    } else {
        g_id = $(elem).attr("aria-grid-id");
    }
    $(elem).colpick({
        onShow: function (colpkr) {
            $(colpkr).fadeIn(500);
            return false;
        },
        onHide: function (colpkr) {
            $(colpkr).fadeOut(500);
            //return false;
        },
        onSubmit: function (hsb, hex, rgb, el) {
            $(el).val('#' + hex);
            $(el).colpickHide();
            $(el).focus();
            if ($(elem).attr('id').substr(0, 3) === 'gs_') {
                $("#" + g_id).get(0).triggerToolbar();
            } else {
                $(elem).trigger('change');
            }
        }
    }).bind('keyup', function () {
        $(this).colpickSetColor(this.value);
    }).bind('focus', function () {
        $(this).colpickSetColor(this.value);
    });
    if (el_general_settings.mobile_platform) {
        $(elem).attr('readonly', true);
    }
}
function initSearchGridAutoCompleteEvent(elem, opt) {
    var g_id, add_id;
    if (opt && opt['attr'] && opt['attr']['aria-grid-id']) {
        g_id = opt['attr']["aria-grid-id"];
        add_id = opt['attr']["aria-unique-name"];
        if (!$(elem).attr("name")) {
            $(elem).attr("name", $(elem).attr("id"));
        }
    } else {
        g_id = $(elem).attr("aria-grid-id");
        add_id = $(elem).attr("aria-unique-name");
    }
    var sub_mod = $("#" + g_id).jqGrid('getGridParam', 'isSubMod');
    var auto_comp_url = el_grid_settings.auto_complete_url;
    if (sub_mod == "1") {
        auto_comp_url = el_subgrid_settings.auto_complete_url;
    } else if (sub_mod == "2") {
        auto_comp_url = el_nesgrid_settings.auto_complete_url;
    }
    setTimeout(function () {
        $(elem).tokenInput(auto_comp_url + "&mode=" + cus_enc_mode_json['Search'] + "&unique_name=" + add_id, {
            minChars: '1',
            propertyToSearch: 'val',
            theme: 'facebook',
            //tokenLimit:'1',
            hintText: js_lang_label.GENERIC_TYPE_IN_A_SEARCH_TERM,
            noResultsText: js_lang_label.GENERIC_NO_RESULTS,
            searchingText: js_lang_label.GENERIC_SEARCHING,
            preventDuplicates: true,
            //prePopulate:par_obj.prePopulate,
            onAdd: function (item) {
                if ($(elem).attr('id').substr(0, 3) === 'gs_') {
                    $("#" + g_id).get(0).triggerToolbar();
                } else {
                    $(elem).trigger('change');
                }
            },
            onDelete: function (item) {
                if ($(elem).attr('id').substr(0, 3) === 'gs_') {
                    $("#" + g_id).get(0).triggerToolbar();
                } else {
                    $(elem).trigger('change');
                }
            }
        });
    }, 500);
}
function initSearchRatingMasterEvent(elem, opt) {
    var g_id, rnumber, rhalf, rprecision, ricons, rhints, rhints_arr;
    if (opt && opt['attr'] && opt['attr']['aria-grid-id']) {
        g_id = opt['attr']["aria-grid-id"];
        rnumber = opt['attr']["aria-raty-number"];
        rhalf = opt['attr']["aria-raty-half"];
        rprecision = opt['attr']["aria-raty-precision"];
        ricons = opt['attr']["aria-raty-icons"];
        rhints = opt['attr']["aria-raty-hints"];
        rhints_arr = rhints.split(",");
    } else {
        g_id = $(elem).attr("aria-grid-id");
        rnumber = $(elem).attr("aria-raty-number");
        rhalf = $(elem).attr("aria-raty-half");
        rprecision = $(elem).attr("aria-raty-precision");
        ricons = $(elem).attr("aria-raty-icons");
        rhints = $(elem).attr("aria-raty-hints");
        rhints_arr = rhints.split(",");
    }
    var raty_elem = $('<span />').addClass("rating-icons-block");
    $(raty_elem).raty({
        number: rnumber,
        half: (rhalf == '1') ? true : false,
        precision: (rprecision == '1') ? true : false,
        cancel: true,
        cancelPlace: 'right',
        cancelHint: '',
        cancelOff: 'cancel-custom-off.png',
        cancelOn: 'cancel-custom-on.png',
        starOff: (ricons == "bulbs") ? 'bulb-off.png' : 'star-off.png',
        starOn: (ricons == "bulbs") ? 'bulb-on.png' : 'star-on.png',
        starHalf: 'star-half.png',
        hints: rhints_arr,
        target: elem,
        targetKeep: true,
        click: function (score, evt) {
            if ($(elem).attr('id').substr(0, 3) === 'gs_') {
                $("#" + g_id).get(0).triggerToolbar();
            } else {
                $(elem).trigger('change');
            }
        }
    });
    setTimeout(function () {
        $(elem).after(raty_elem);
        $(elem).hide();
    }, 500);
}
function formatChosenAjaxResults(dataset) {
    var results = [];
    var data = dataset.results;
    if (dataset.type == "optgroup") {
        $.each(data, function (i, grp) {
            var group = {// here's a group object:
                group: true,
                text: grp.text, // label for the group
                items: [] // individual options within the group
            };
            $.each(grp.items, function (j, val) {
                group.items.push({
                    value: val.id,
                    text: val.text
                });
            });
            results.push(group);
        });
    } else {
        $.each(data, function (i, val) {
            results.push({
                value: val.id,
                text: val.text
            });
        });
    }
    return results;
}
function activateRatingMasterEvent(eleobj, base, hints, score) {
    var basic_params = assignEventParams(base);
    var function_params = {
        score: score,
        hints: ($.isArray(hints)) ? hints : hints.split(",")
    }
    var final_params = $.extend({}, basic_params, function_params);
    $(eleobj).raty(final_params);
}
function niceScrollHomePageBlocks() {
    if (!$(".home-page-boxes").length) {
        return;
    }
    $(".box-height").each(function () {
        //$(this).getNiceScroll().remove();
        $(this).niceScroll({
            cursoropacitymax: 0.7,
            cursorborderradius: 6,
            cursorwidth: "4px",
            zindex: 97
        });
    })
}
function check_user_platform() {
    var js_user_agent = navigator.userAgent.match(/(iPad|iPhone|iPod|android|blackberry|webos)/i) ? true : false;
    if (js_user_agent) {
        return true;
    } else {
        return false;
    }
}
function initTopGridAjaxOptions(elem, opt, add_id, u_id, e_id, g_id) {
    if (opt.dataUrl !== false && opt.dataUrl != "") {
        $.ajax({
            url: opt.dataUrl,
            type: 'POST',
            success: function (data) {
                $(elem).html(data);
                if (typeof opt.selected == "string" && opt.selected != "") {
                    var selected = parseJSONString(opt.selected);
                    $(elem).val(selected);
                }
                $(elem).trigger("chosen:updated");
            }
        });
    }
}
function initTopGridChosenEvent(elem, opt, add_id, u_id, e_id, g_id) {
    $(elem).chosen({
        allow_single_deselect: true,
        placeholder_text_multiple: " "
    });
    if (opt.dataUrl !== false && opt.dataUrl != "") {
        initTopGridAjaxOptions(elem, opt, add_id, u_id, e_id, g_id);
    } else {
        if (typeof opt.selected == "string" && opt.selected != "") {
            var selected = parseJSONString(opt.selected);
            $(elem).val(selected);
            $(elem).trigger("chosen:updated");
        }
    }
}
function initTopGridAjaxChosenEvent(elem, opt, add_id, u_id, e_id, g_id) {
    var g_id, add_id;
    $(elem).chosen({
        allow_single_deselect: true
    });
    var sub_mod = $("#" + g_id).jqGrid('getGridParam', 'isSubMod');
    var ajax_chosen_url = el_grid_settings.ajax_data_url;
    if (sub_mod == "1") {
        ajax_chosen_url = el_subgrid_settings.ajax_data_url;
    } else if (sub_mod == "2") {
        ajax_chosen_url = el_nesgrid_settings.ajax_data_url;
    }
    $(elem).ajaxChosen({
        dataType: 'json',
        type: 'POST',
        url: ajax_chosen_url + "&mode=" + cus_enc_mode_json['Search'] + "&unique_name=" + add_id
    }, {
        loadingImg: admin_image_url + "chosen-loading.gif"
    });
    if (opt.dataUrl !== false && opt.dataUrl != "") {
        initTopGridAjaxOptions(elem, opt, add_id, u_id, e_id, g_id);
    } else {
        if (typeof opt.selected == "string" && opt.selected != "") {
            var selected = parseJSONString(opt.selected);
            $(elem).val(selected);
            $(elem).trigger("chosen:updated");
        }
    }
}
function initTopGridDateRangePicker(elem, opt, add_id, u_id, e_id, g_id) {
    var d_format = opt.format, d_ranges, d_months, d_weeks;
    d_format = (d_format) ? d_format : "MMMM DD, YYYY";
    d_ranges = getRangePickerQuickList();
    d_months = getRangePickerMonthNames();
    d_weeks = getRangePickerWeekNames();
    $(elem).daterangepicker({
        ranges: d_ranges,
        opens: 'left',
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        showDropdowns: true,
        locale: {
            format: d_format,
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
    $(elem).on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format(picker.locale.format) + picker.locale.separator + picker.endDate.format(picker.locale.format));
        triggerTopFilterEvent(g_id, add_id, picker.startDate.format('YYYY-MM-DD') + picker.locale.separator + picker.endDate.format('YYYY-MM-DD'));
    });
    $(elem).on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        triggerTopFilterEvent(g_id, add_id, '');
    });
}
function initTopGridDateTimePicker(elem, opt, add_id, u_id, e_id, g_id) {
    var d_format = opt.format, e_time = opt.time, d_ranges, d_months, d_weeks;
    d_format = (d_format) ? d_format : "MMMM DD, YYYY HH:mm:ss";
    d_ranges = getRangePickerQuickList();
    d_months = getRangePickerMonthNames();
    d_weeks = getRangePickerWeekNames();
    $(elem).daterangepicker({
        ranges: d_ranges,
        opens: 'left',
        timePicker: true,
        timePickerIncrement: 1,
        timePicker12Hour: (e_time == 'false') ? false : true,
        showDropdowns: true,
        locale: {
            format: d_format,
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
    $(elem).on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format(picker.locale.format) + picker.locale.separator + picker.endDate.format(picker.locale.format));
        triggerTopFilterEvent(g_id, add_id, picker.startDate.format('YYYY-MM-DD HH:mm:ss') + picker.locale.separator + picker.endDate.format('YYYY-MM-DD HH:mm:ss'));
    });
    $(elem).on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        triggerTopFilterEvent(g_id, add_id, '');
    });
}
function initTopGridColorPickerEvent(elem, opt, add_id, u_id, e_id, g_id) {
    $(elem).colpick({
        onShow: function (colpkr) {
            $(colpkr).fadeIn(500);
            return false;
        },
        onHide: function (colpkr) {
            $(colpkr).fadeOut(500);
            //return false;
        },
        onSubmit: function (hsb, hex, rgb, el) {
            $(el).val('#' + hex);
            $(el).colpickHide();
            $(el).focus();
            triggerTopFilterEvent(g_id, add_id, $(elem).val());
        }
    }).bind('keyup', function () {
        $(this).colpickSetColor(this.value);
    }).bind('focus', function () {
        $(this).colpickSetColor(this.value);
    });
    if (el_general_settings.mobile_platform) {
        $(elem).attr('readonly', true);
    }
}
function initTopGridAutoCompleteEvent(elem, opt, add_id, u_id, e_id, g_id) {
    var sub_mod = $("#" + g_id).jqGrid('getGridParam', 'isSubMod');
    var auto_comp_url = el_grid_settings.auto_complete_url;
    if (sub_mod == "1") {
        auto_comp_url = el_subgrid_settings.auto_complete_url;
    } else if (sub_mod == "2") {
        auto_comp_url = el_nesgrid_settings.auto_complete_url;
    }
    var prePopulate;
    if (typeof opt.selected == "string" && opt.selected != "") {
        var prePopulate = JSON.parse(opt.selected);
    }
    setTimeout(function () {
        $(elem).tokenInput(auto_comp_url + "&mode=" + cus_enc_mode_json['Search'] + "&unique_name=" + u_id, {
            minChars: '1',
            propertyToSearch: 'val',
            theme: 'facebook',
            //tokenLimit:'1',
            hintText: js_lang_label.GENERIC_TYPE_IN_A_SEARCH_TERM,
            noResultsText: js_lang_label.GENERIC_NO_RESULTS,
            searchingText: js_lang_label.GENERIC_SEARCHING,
            preventDuplicates: true,
            prePopulate: prePopulate,
            onAdd: function (item) {
                triggerTopFilterEvent(g_id, add_id, $(elem).val(), $(elem).data("tokenInputObject").getTokens());
            },
            onDelete: function (item) {
                triggerTopFilterEvent(g_id, add_id, $(elem).val(), $(elem).data("tokenInputObject").getTokens());
            }
        });
    }, 500);
}
function triggerTopFilterEvent(grid_id, key, val, txt) {
    var post_data = $("#" + grid_id).jqGrid("getGridParam", "postData");
    var filters = (post_data && post_data.filters) ? parseJSONString(post_data.filters) : {};
    var grid = $("#" + grid_id), filt, ranges = {};

    ranges['key'] = key;
    ranges['val'] = val;
    ranges['txt'] = txt;
//    if($("select.topfilter-container").length){
//        $("select.topfilter-container").find("option:selected").each(function(){
//            ranges['txt'].push($(this).text());
//        });
//    }
    filt = {
        groupOp: "AND",
        rules: (filters && filters.rules) ? filters.rules : [],
        entrys: (filters && filters.entrys) ? filters.entrys : "",
        range: ranges
    };
    grid[0].p.search = true;
    $.extend(grid[0].p.postData, {
        filters: JSON.stringify(filt)
    });
    reloadListGrid(grid_id, [{
            page: 1,
            current: true
        }]);
}
function adminCustomButtonAction(ids, alert_json, confirm_json, grid_id, ext_uri, evtObj, gridObj) {
    if (alert_json && $.isPlainObject(alert_json)) {
        if (!$.isArray(ids) || !ids.length) {
            jqueryUIalertBox(alert_json.body, alert_json.title, alert_json.button[0], alert_json.width, alert_json.height);
            return false;
        }
    }
    if (confirm_json.type == "redirect" || confirm_json.type == "module") {
        var id_param, btn_url, href_url_arr, params_obj, req_uri
        id_param = (confirm_json.id) ? confirm_json.id : 'id';
        btn_url = confirm_json.url;
        if (confirm_json.ext) {
            if (btn_url.indexOf("?") != -1) {
                btn_url += "&" + confirm_json.id + "=" + ids.join(',');
            } else {
                btn_url += "?" + confirm_json.id + "=" + ids.join(',');
            }
            btn_url += prepareQueryParamsURL(confirm_json.params);
        } else {
            btn_url += "|" + confirm_json.id + "|" + ids.join(',');
            btn_url += prepareHASHParamsURL(confirm_json.params);
        }
        if (confirm_json.open == "same") {
            window.location.href = btn_url;
        } else if (confirm_json.open == "new") {
            window.open(btn_url, '_blank');
        } else if (confirm_json.open == "popup") {
            if (confirm_json.ext) {
                href_url_arr = btn_url.split("?");
                params_obj = getQueryToFancyParams(href_url_arr[1]);
                req_uri = href_url_arr[1];
            } else {
                href_url_arr = btn_url.split("#");
                params_obj = getHASHToFancyParams(href_url_arr[1]);
                req_uri = convertHASHToURL(href_url_arr[1]);
            }
            if (confirm_json.width) {
                params_obj['width'] = confirm_json.width;
            }
            if (confirm_json.height) {
                params_obj['height'] = confirm_json.height;
            }
            if (confirm_json.type == "redirect") {
                openAjaxURLFancyBox(req_uri, params_obj);
            } else {
                openCustomURLFancyBox(req_uri, params_obj);
            }
        }
    } else if (confirm_json.type == "callback") {
        if (confirm_json.callback && $.isFunction(window[confirm_json.callback])) {
            window[confirm_json.callback](ids, evtObj, gridObj);
        }
    } else if (confirm_json.type == "confirm") {
        if (confirm_json.body.type == "general" || confirm_json.body.type == "extended") {
            $.ajax({
                url: admin_url + cus_enc_url_json["general_grid_render_action"] + '?' + ext_uri,
                type: 'POST',
                data: {
                    id: ids.join(','),
                    render_module: confirm_json.module,
                    render_type: confirm_json.body.type,
                    render_value: confirm_json.body.value
                },
                success: function (data) {
                    adminCustomButtonRender(ids, confirm_json, data, grid_id, ext_uri);
                }
            });
        } else {
            adminCustomButtonRender(ids, confirm_json, confirm_json.body.value, grid_id, ext_uri);
        }
    }
}
function adminCustomButtonRender(ids, confirm_json, rhtml, grid_id, ext_uri) {
    var content = '<form name="frmcustomdialog" id="frmcustomdialog" method="post" enctype="multipart/form-data" onsubmit="return false">';
    content += '<input type="hidden" name="id" value="' + (ids.join(',')) + '"/>';
    content += '<input type="hidden" name="action_module" value="' + (confirm_json.module) + '"/>';
    content += '<input type="hidden" name="action_type" value="' + (confirm_json.action.type) + '"/>';
    content += '<input type="hidden" name="action_value" value="' + (confirm_json.action.value) + '"/>';
    content += rhtml;
    content += '</form>';

    var label_elem = '<div />';
    var label_text = content;
    var option_params = {
        title: confirm_json.title,
        width: (confirm_json.width) ? confirm_json.width : 300,
        height: (confirm_json.height) ? confirm_json.height : "auto",
        closeOnEscape: false,
        dialogClass: "dialog-confirm-box grid-confirm-popup grid-custom-btn-popup",
        buttons: [{
                text: (confirm_json.button[0]) ? confirm_json.button[0] : js_lang_label.GENERIC_GRID_SUBMIT,
                bt_type: 'ok',
                click: function () {
                    Project.show_adaxloading_div();
                    $.ajax({
                        url: admin_url + cus_enc_url_json["general_grid_submit_action"] + '?' + ext_uri,
                        type: 'POST',
                        data: $("#frmcustomdialog").serialize(),
                        success: function (data) {
                            var respData = parseJSONString(data), $dialog_msg;
                            if (respData.load_grid) {
                                if ($("#" + respData.load_grid).length) {
                                    var sort_mode = (respData.sort_mode) ? respData.sort_mode : 1;
                                    reloadListGrid(respData.load_grid, null, sort_mode);
                                }
                            }
                            if (respData.red_hash) {
                                loadAdminAddUpdateControl(respData);
                            }
                            if (respData.callback) {
                                if ($.isFunction(window[respData.callback])) {
                                    window[respData.callback](respData);
                                }
                            }
                            gridReportMessage(respData.success, respData.message);
                        },
                        complete: function () {
                            Project.hide_adaxloading_div();
                        }
                    });
                    $(this).remove();
                }
            }, {
                text: (confirm_json.button[1]) ? confirm_json.button[1] : js_lang_label.GENERIC_GRID_CANCEL,
                bt_type: 'cancel',
                click: function () {
                    $(this).remove();
                }
            }]
    }
    $(".grid-confirm-popup").remove();
    $("#frmcustomdialog").parent().remove();
    jqueryUIdialogBox(label_elem, label_text, option_params);
}
function setHideColumnSettings(grid_id, col_model, top_model) {
    var i, j, t_name;
    for (i = 0; i < col_model.length; i++) {
        t_name = col_model[i]['name'];
        if ($.inArray(t_name, ['cb', 'rn', 'subgrid', 'prec']) != -1) {
            continue;
        }
        if ("hideme" in col_model[i]) {
            if (col_model[i]['hideme'] == true) {
                col_model[i]['search'] = false;
                col_model[i]['export'] = false;
                if ($.isArray(top_model) && top_model.length > 0) {
                    for (j = 0; j < top_model.length; j++) {
                        if (top_model[j]['name'] == t_name) {
                            top_model.splice(j, 1);
                            break;
                        }
                    }
                }
            }
        }
    }
}
function setSavedSearchSettings(id, code, list, slug) {
    var is_found = false;
    if (!list || !$.isArray(list)) {
        return is_found;
    }
    if (!slug) {
        for (var i in list) {
            if (list[i]['default'] == "Yes") {
                slug = list[i]['slug'];
                break;
            }
        }
        if (!slug) {
            return is_found;
        }
    }
    for (var i in list) {
        if (list[i]['slug'] == slug) {
            var data = list[i]['value'];
            if (data && $.isPlainObject(data)) {
                is_found = true;
                if (data.grid_view) {
                    setLocalStore(code + "_gv", data.grid_view);
                }
                if (data.quick_search) {
                    setLocalStore(code + "_st", data.quick_search);
                }
                if (data.col_positions) {
                    setLocalStore(code + "_cp", JSON.stringify(data.col_positions), true);
                }
                if (data.col_selection) {
                    setLocalStore(code + "_cs", JSON.stringify(data.col_selection), true);
                }
                if (data.col_widths) {
                    setLocalStore(code + "_cw", JSON.stringify(data.col_widths), true);
                }
                if (data.search_filters) {
                    data['search_filters']['postData']['filters'] = JSON.stringify(data['search_filters']['postData']['filters']);
                    setLocalStore(code + "_sh", JSON.stringify(data.search_filters), true);
                }
                if (data.left_search) {
                    setLocalStore(code + "_sv", JSON.stringify(data.left_search), true);
                }
            }
        }
    }
    return is_found;
}
function triggerSavedSearchForm(id, code) {
    var search_code = code + '_manual';
    var content = '<form name="frmsavesearchdialog" id="frmsavesearchdialog" method="post" enctype="multipart/form-data" onsubmit="return false">';
    content += '<input type="hidden" name="search_code" value="' + search_code + '"/>';
    content += '<div class="save-search-form">';
    content += '<div><div>Title <em class="errormsg">*</em></div><div><input type="text" name="save_search_title" id="save_search_title" class="save-search-title"/></div></div>';
    content += '<div><div>Comments</div><div><textarea name="save_search_comments" id="save_search_comments" class="save-search-comments"></textarea></div></div>';
    content += '<div><textarea style="display:none;" name="save_search_preferences" id="save_search_preferences" class="save-search-preferences"></textarea></div>';
    content += '<div><div class="save-mark-container"><input type="checkbox" name="save_search_default" id="save_search_default" value="Yes" class="regular-checkbox save-search-default"/>';
    content += '<label for="save_search_default"></label><label for="save_search_default" class="save-mark-label">Make As Default</label></div></div>';
    content += '</div>';
    content += '</form>';

    var label_elem = '<div id="dialog-savesearch" class="dialog-savesearch" />';
    var label_text = content;
    var option_params = {
        title: js_lang_label.GENERIC_GRID_SAVE_SEARCH,
        width: 450,
        height: "auto",
        closeOnEscape: true,
        dialogClass: "dialog-confirm-box grid-confirm-popup grid-savesearch-popup",
        buttons: [{
                text: js_lang_label.GENERIC_GRID_SAVE,
                bt_type: 'ok',
                click: function () {
                    if ($("#save_search_title").val() != "") {
                        Project.show_adaxloading_div();
                        $("#save_search_title").removeClass("error");
                        var that = this, preferences = {};
                        preferences['grid_view'] = getLocalStore(code + "_gv");
                        preferences['quick_search'] = getLocalStore(code + "_st");
                        preferences['col_positions'] = parseJSONString(getLocalStore(code + "_cp"));
                        preferences['col_selection'] = parseJSONString(getLocalStore(code + "_cs"));
                        preferences['col_widths'] = parseJSONString(getLocalStore(code + "_cw"));
                        preferences['search_filters'] = parseJSONString(getLocalStore(code + "_sh"));
                        preferences['left_search'] = parseJSONString(getLocalStore(code + "_sv"));
                        if (!$.isPlainObject(preferences['search_filters'])) {
                            preferences['search_filters'] = {
                                "postData": {}
                            };
                        }
                        preferences['search_filters']['postData']['filters'] = parseJSONString(preferences['search_filters']['postData']['filters']);
                        preferences['search_filters']['postData']['columns'] = parseJSONString(preferences['search_filters']['postData']['columns']);
                        $("#save_search_preferences").val(JSON.stringify(preferences));
                        $.ajax({
                            url: admin_url + cus_enc_url_json["general_grid_save_search_action"],
                            type: 'POST',
                            data: $("#frmsavesearchdialog").serialize(),
                            success: function (data) {
                                var respData = parseJSONString(data);
                                gridReportMessage(respData.success, respData.message);
                                if (respData.success) {
                                    $(that).remove();
                                    reloadCurrentListPage("add", respData.data);
                                }
                            },
                            complete: function () {
                                Project.hide_adaxloading_div();
                            }
                        });
                    } else {
                        $("#save_search_title").addClass("error").focus();
                    }
                }
            }, {
                text: js_lang_label.GENERIC_GRID_CANCEL,
                bt_type: 'cancel',
                click: function () {
                    $(this).remove();
                }
            }],
        close: function (event, ui)
        {
            $(this).dialog('destroy').remove()
        }
    }
    destroySaveSearchDialogs();
    jqueryUIdialogBox(label_elem, label_text, option_params);
}
function triggerSavedSearchList(id, code, settings) {
    var list, slug, chk_attr, btn_class, index_url, extra_hstr, slug_url, search_code;
    search_code = code + '_manual';
    list = settings['search_list'];
    slug = settings['search_slug'];
    index_url = settings['index_page_url'];
    if (settings['extra_hstr'] != "") {
        index_url += settings['extra_hstr'];
        if (index_url.charAt(index_url.length - 1) == "|") {
            index_url = index_url.slice(0, -1);
        }
    }

    $(document).off('click', '.search-list-row');
    $(document).on('click', '.search-list-row', function (e) {
        var search_slug = $(this).attr("data-search-url");
        window.location.hash = search_slug;
        $(".grid-searchlist-popup").remove();
    });

    $(document).off('click', '.btn-search-view');
    $(document).on('click', '.btn-search-view', function (e) {
        $(".grid-searchlist-popup").remove();
    });

    $(document).off('click', '.btn-search-del');
    $(document).on('click', '.btn-search-del', function (e) {
        deleteSavedSearchItem($(this).attr("data-id"), $(this).attr("data-code"), this);
    });

    $(document).off('click', '.search-make-default');
    $(document).on('click', '.search-make-default', function (e) {
        updateSavedSearchItem($(this).attr("data-id"), $(this).attr("data-code"), this);
    });

    var content = '<div class="save-search-container">';
    content += '<div class="save-search-list">';
    if ($.isArray(list) && list.length) {
        content += '<table class="responsive table table-bordered save-search-tbl">';
        for (var i in list) {
            btn_class = '', chk_attr = '';
            if (list[i]['slug'] == slug) {
                btn_class = 'active';
            }
            if (list[i]['default'] == "Yes") {
                chk_attr = 'checked=true';
            }
            slug_url = index_url + '|search|' + list[i]['slug'];
            content += '<tr>';
            content += '<td width="70%" class="search-list-row" data-search-url="' + slug_url + '"><div class="search-list-title">' + list[i]['name'] + '</div><div class="search-list-comment">' + list[i]['comment'] + '<div></td>';
            content += '<td width="10%"><div align="center"><input type="checkbox" data-id="' + list[i]['id'] + '" data-code="' + search_code + '" name="search_make_default" id="' + list[i]['slug'] + '" class="regular-checkbox search-make-default" title="' + js_lang_label.GENERIC_MAKE_AS_DEFAULT + '" ' + chk_attr + '><label for="' + list[i]['slug'] + '"></label></div></td>';
            content += '<td width="10%"><div align="center"><a href="' + admin_url + '#' + slug_url + '" class="btn ' + btn_class + ' btn-search-view" title="' + js_lang_label.GENERIC_GRID_VIEW + '"><span class="icon18 iconic-icon-eye"></span></div></td>';
            content += '<td width="10%"><div align="center"><a href="javascript://" data-id="' + list[i]['id'] + '" data-code="' + search_code + '" class="btn btn-search-del" title="' + js_lang_label.GENERIC_GRID_DELETE + '"><span class="icon15 brocco-icon-trashcan"></span</div></td>';
            content += '</tr>';
        }
        content += '</table>';
    } else {
        content += '<div align="center" class="errormsg">' + js_lang_label.GENERIC_NO_SEARCH_PREFERENCES_SAVED_YET + '</div>';
    }
    content += '</div>';
    content += '</div>';

    var label_elem = '<div id="dialog-searchlist" class="dialog-searchlist" />';
    var label_text = content;
    var option_params = {
        title: js_lang_label.GENERIC_GRID_SEARCH_LIST,
        width: 500,
        height: "auto",
        closeOnEscape: true,
        dialogClass: "dialog-confirm-box grid-confirm-popup grid-searchlist-popup",
        buttons: false,
        open: function (event, ui) {
            $('.save-search-tbl a:first').blur();
        },
        close: function (event, ui)
        {
            $(this).dialog('destroy').remove();
        }
    }
    destroySaveSearchDialogs();
    jqueryUIdialogBox(label_elem, label_text, option_params);
}
function updateSavedSearchItem(id, code, obj) {
    var label_elem = '<div />';
    var label_text = js_lang_label.GENERIC_ARE_YOU_WANT_MAKE_THIS_AS_DEFAULT;
    var value = $(obj).is(":checked") ? "Yes" : "No";
    if (value == "No") {
        label_text = "Are you sure want to remove from default?";
    }
    var option_params = {
        title: js_lang_label.GENERIC_MAKE_AS_DEFAULT,
        dialogClass: "dialog-confirm-box grid-confirm-popup grid-setsearch-popup",
        buttons: [{
                text: js_lang_label.GENERIC_GRID_OK,
                bt_type: 'ok',
                click: function () {
                    if (value == "Yes") {
                        if ($("input[name=search_make_default]:checked").length > 1) {
                            $("input[name=search_make_default]:checked").removeAttr("checked");
                            $(obj).prop("checked", "checked");
                        }
                    }
                    Project.show_adaxloading_div();
                    $.ajax({
                        url: admin_url + cus_enc_url_json["general_grid_update_search_action"],
                        type: 'POST',
                        data: {
                            'search_code': code,
                            'search_id': id,
                            'value': value,
                            'type': 'default'
                        },
                        success: function (data) {
                            var respData = parseJSONString(data);
                            gridReportMessage(respData.success, respData.message);
                            if (respData.success) {
                                reloadCurrentListPage("edit", {"id": id});
                            }
                        },
                        complete: function () {
                            Project.hide_adaxloading_div();
                            destroySaveSearchDialogs();
                        }
                    });
                    $(this).remove();
                }
            }, {
                text: js_lang_label.GENERIC_CANCEL,
                bt_type: 'cancel',
                click: function () {
                    $(this).remove();
                    if (value == "No") {
                        $(obj).attr("checked", "checked");
                    } else {
                        if ($("input[name=search_make_default]:checked").length > 1) {
                            $(obj).removeAttr("checked");
                        }
                    }
                }
            }]
    }
    jqueryUIdialogBox(label_elem, label_text, option_params);
}
function deleteSavedSearchItem(id, code, obj) {
    var label_elem = '<div />';
    var label_text = js_lang_label.GENERIC_ARE_YOU_SURE_WANT_TO_DELETE_THIS;
    var option_params = {
        title: js_lang_label.GENERIC_GRID_DELETE,
        dialogClass: "dialog-confirm-box grid-confirm-popup grid-delsearch-popup",
        buttons: [{
                text: js_lang_label.GENERIC_GRID_DELETE,
                bt_type: 'delete',
                click: function () {
                    Project.show_adaxloading_div();
                    $.ajax({
                        url: admin_url + cus_enc_url_json["general_grid_delete_search_action"],
                        type: 'POST',
                        data: {
                            'search_code': code,
                            'search_id': id
                        },
                        success: function (data) {
                            var respData = parseJSONString(data);
                            gridReportMessage(respData.success, respData.message);
                            if (respData.success) {
                                reloadCurrentListPage("delete", {"id": id});
                            }
                        },
                        complete: function () {
                            Project.hide_adaxloading_div();
                            destroySaveSearchDialogs();
                        }
                    });
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
function destroySaveSearchDialogs() {
    if ($("#dialog-savesearch").data("dialog")) {
        $("#dialog-savesearch").data("dialog").destroy();
    }
    if ($("#dialog-searchlist").data("dialog")) {
        $("#dialog-searchlist").data("dialog").destroy();
    }
    $("#dialog-savesearch").remove();
    $("#dialog-searchlist").remove();
}
function reloadCurrentListPage(mode, data) {
    var index_hash;
    index_hash = el_grid_settings['index_page_url'];
    if (el_grid_settings['extra_hstr'] != "") {
        index_hash += el_grid_settings['extra_hstr'];
        if (index_hash.charAt(index_hash.length - 1) == "|") {
            index_hash = index_hash.slice(0, -1);
        }
    }
    index_hash += '|_|' + (new Date().getTime());
    if (mode == "add") {
        if (data && $.isPlainObject(data) && "slug" in data) {
            index_hash += '|search|' + data.slug;
        }
    } else if (mode == "delete") {
        setLocalStore(el_grid_settings.enc_location + '_sh', '{}', true);
    }
    window.location.hash = index_hash;
}
function initAutoRefreshGrid(module, refresh) {
    if (refresh != "Yes" || !$(".grid-table-view").length || !$("[data-list-name='" + module + "']").length) {
        $("[data-list-name='" + module + "']").off('mousemove');
        $("[data-list-name='" + module + "']").off('keypress');
        $("[data-list-name='" + module + "']").off('click');
        return;
    }
    $("[data-list-name='" + module + "']").on('mousemove', function (e) {
        gridRefreshTime = 0;
    });
    $("[data-list-name='" + module + "']").on('keypress', function (e) {
        gridRefreshTime = 0;
    });
    $("[data-list-name='" + module + "']").on('click', function (e) {
        gridRefreshTime = 0;
    });

    var interval = el_tpl_settings.list_refresh_interval / 2;
    gridAutoRefresh = setInterval(function () {
        gridRefreshTime += interval;
        if (gridRefreshTime > el_tpl_settings.list_refresh_interval) {
            gridRefreshTime = 0;
            reloadListGrid(el_tpl_settings.main_grid_id);
        }
    }, interval);
}
function stopAutoRefreshGrid() {
    try {
        clearInterval(gridAutoRefresh);
    } catch (err) {

    }
}
function switchModulePrintPage(module_url, code, extra_qstr) {
    Project.show_adaxloading_div();
    $.ajax({
        url: module_url + "?" + extra_qstr,
        type: 'POST',
        data: {
            'layout': code
        },
        success: function (data) {
            $("#print_container").html(data);
        },
        complete: function () {
            Project.hide_adaxloading_div();
        }
    });
}
function applyGridScrollPosition(module, grid_id) {
    var old_pos = parseJSONString(getLocalStore(el_grid_settings.enc_location + '_sp'));
    if (old_pos && "top" in old_pos && "left" in old_pos) {
        if ($("#gview_" + grid_id + " .ui-jqgrid-bdiv").length) {
            $("#gview_" + grid_id + " .ui-jqgrid-bdiv")[0].scrollTo(old_pos.left, old_pos.top);
        }
    }
    $("#gview_" + grid_id + " .ui-jqgrid-bdiv").scroll(function () {
        var posInfo = {};
        posInfo.top = $(this).scrollTop();
        posInfo.left = $(this).scrollLeft();
        setLocalStore(el_grid_settings.enc_location + '_sp', JSON.stringify(posInfo), true);
    });
}
function imageLoadingError(e) {
    $(e).attr("src", el_tpl_settings.noimage_url);
}
function isHTML(str) {
    var a = document.createElement('div');
    a.innerHTML = str;

    for (var c = a.childNodes, i = c.length; i--; ) {
        if (c[i].nodeType == 1)
            return true;
    }

    return false;
}
function getRangePickerQuickList() {
    var defaultList = [
        {
            'key': 'Today',
            'label': js_lang_label.GENERIC_GRID_TODAY,
            'value': [moment().startOf('days'), moment().endOf('days')]
        },
        {
            'key': 'Yesterday',
            'label': js_lang_label.GENERIC_GRID_YESTERDAY,
            'value': [moment().subtract('days', 1).startOf('days'), moment().subtract('days', 1).endOf('days')]
        },
        {
            'key': 'Last 7 Days',
            'label': js_lang_label.GENERIC_GRID_LAST_7_DAYS,
            'value': [moment().subtract('days', 6), moment()]
        },
        {
            'key': 'Next 7 Days',
            'label': js_lang_label.GENERIC_GRID_NEXT_7_DAYS,
            'value': [moment(), moment().add('days', 6)]
        },
        {
            'key': 'Last 30 Days',
            'label': js_lang_label.GENERIC_GRID_LAST_30_DAYS,
            'value': [moment().subtract('days', 29), moment()]
        },
        {
            'key': 'Next 30 Days',
            'label': js_lang_label.GENERIC_GRID_NEXT_30_DAYS,
            'value': [moment(), moment().add('days', 29)]
        },
        {
            'key': 'This Month',
            'label': js_lang_label.GENERIC_GRID_THIS_MONTH,
            'value': [moment().startOf('month'), moment().endOf('month')]
        },
        {
            'key': 'Last Month',
            'label': js_lang_label.GENERIC_GRID_LAST_MONTH,
            'value': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
        },
        {
            'key': 'Next Month',
            'label': js_lang_label.GENERIC_GRID_NEXT_MONTH,
            'value': [moment().add('month', 1).startOf('month'), moment().add('month', 1).endOf('month')]
        }
    ];

    var quickList = {};
    $.each(defaultList, function (idx, data) {
        if (data['label']) {
            quickList[data['label']] = data['value'];
        } else {
            quickList[data['key']] = data['value'];
        }
    });
    return quickList;
}
function getRangePickerMonthNames() {
    var defaultList = [
        {
            'key': 'January',
            'label': js_lang_label.GENERIC_GRID_JANUARY
        },
        {
            'key': 'February',
            'label': js_lang_label.GENERIC_GRID_FEBRUARY
        },
        {
            'key': 'March',
            'label': js_lang_label.GENERIC_GRID_MARCH
        },
        {
            'key': 'April',
            'label': js_lang_label.GENERIC_GRID_APRIL
        },
        {
            'key': 'May',
            'label': js_lang_label.GENERIC_GRID_MAY
        },
        {
            'key': 'June',
            'label': js_lang_label.GENERIC_GRID_JUNE
        },
        {
            'key': 'July',
            'label': js_lang_label.GENERIC_GRID_JULY
        },
        {
            'key': 'August',
            'label': js_lang_label.GENERIC_GRID_AUGUST
        },
        {
            'key': 'September',
            'label': js_lang_label.GENERIC_GRID_SEPTEMBER
        },
        {
            'key': 'October',
            'label': js_lang_label.GENERIC_GRID_OCTOBER
        },
        {
            'key': 'November',
            'label': js_lang_label.GENERIC_GRID_NOVEMBER
        },
        {
            'key': 'December',
            'label': js_lang_label.GENERIC_GRID_DECEMBER
        }
    ];
    var monthNames = [];
    $.each(defaultList, function (idx, data) {
        if (data['label']) {
            monthNames.push(data['label']);
        } else {
            monthNames.push(data['key']);
        }
    });
    return monthNames;
}
function getRangePickerWeekNames() {
    var defaultList = [
        {
            'key': 'Sun',
            'label': js_lang_label.GENERIC_GRID_SUN
        },
        {
            'key': 'Mon',
            'label': js_lang_label.GENERIC_GRID_MON
        },
        {
            'key': 'Tue',
            'label': js_lang_label.GENERIC_GRID_TUE
        },
        {
            'key': 'Wed',
            'label': js_lang_label.GENERIC_GRID_WED
        },
        {
            'key': 'Thu',
            'label': js_lang_label.GENERIC_GRID_THU
        },
        {
            'key': 'Fri',
            'label': js_lang_label.GENERIC_GRID_FRI
        },
        {
            'key': 'Sat',
            'label': js_lang_label.GENERIC_GRID_SAT
        }
    ];
    var weekNames = [];
    $.each(defaultList, function (idx, data) {
        if (data['label']) {
            weekNames.push(data['label']);
        } else {
            weekNames.push(data['key']);
        }
    });
    return weekNames;
}