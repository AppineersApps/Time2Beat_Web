// related to tab type forms
$(function () {
    $(document).on("click", "a[id^='tabanchor']", function () {
        if (!$(this).parent("li").hasClass("active")) {
            if ($(this).attr("isdone") == "1") {
                var sendArr = [];
                var jbArr = $(this).attr("id").split("_");
                var jcurr_tab = $(this).attr("aria-curr");
                sendArr.push({"curr_tab": jcurr_tab, "col": jbArr[1], "row": jbArr[2], "blk": jbArr[3]});
                $("#tabcontent_" + jbArr[1] + "_" + jbArr[2]).addClass("hide-overflow").removeClass("show-overflow");
                getActivateAdminTabContent(sendArr);
            } else {
                return false;
            }
        } else {
            return false;
        }
    });
//    $(document).on("keyup", ".content-animate textarea.elastic", function() {
//        adjustTabHeightOnFly(this, 10);
//    })
    $(document).on("click", "[aria-chosen-select]", function () {
        var eleID = $(this).attr("aria-chosen-select");
        var eleType = $(this).attr("aria-chosen-type");
        if (eleType == "select") {
            $("#" + eleID).find("option").prop("selected", true);
            $("#" + eleID).trigger("chosen:updated");
            $(this).attr("aria-chosen-type", "deselect");
            $(this).find("span.arrow-image").removeClass("silk-icon-arrow-left").addClass("silk-icon-arrow-right");
            $(this).attr("title", js_lang_label.GENERIC_DESELECT_ALL);
        } else {
            $("#" + eleID).find("option").prop("selected", false);
            $("#" + eleID).trigger("chosen:updated");
            $(this).attr("aria-chosen-type", "select");
            $(this).find("span.arrow-image").removeClass("silk-icon-arrow-right").addClass("silk-icon-arrow-left");
            $(this).attr("title", js_lang_label.GENERIC_SELECT_ALL)
        }
        setTimeout(function () {
            $("#" + eleID).change();
        }, 10);
    });
    $(document).on("change", "[aria-chosen-valid]", function (e, d) {
        if ($.isPlainObject(d) && typeof d.init == "boolean" && d.init == true) {
            return false;
        }
        if ($(this).parents("form").length) {
            $(this).valid();
        }
    });
    $(document).on("click", ".popup-rel-mod-add", function (e) {
        var ar_relmod = $(this).attr("aria-relmod");
        var incNo = $("#childModuleInc_" + ar_relmod).val();
        var pid = $("#childModuleParData_" + ar_relmod).val();
        var href_url_arr = $(this).attr("href").split("#");
        var params_uri = convertHASHToURL(href_url_arr[1]);
        var params_obj = getHASHToFancyParams(href_url_arr[1]);
        incNo++;
        pid = (pid) ? pid : -1;
        params_uri += "&rmNum=" + incNo + "&parID=" + pid;
        $("#childModuleInc_" + ar_relmod).val(incNo);
        var req_uri = params_uri;
        openURLFancyBox(req_uri, params_obj);
        e.preventDefault();
        return false;
    });
    $(document).on("click", ".popup-rel-mod-edit", function (e) {
        var ar_relmod = $(this).attr("aria-relmod");
        var incNo = $(this).attr("aria-incno");
        var pid = $("#childModuleParData_" + ar_relmod).val();
        var md = $("#childModuleLayout_" + ar_relmod).val();
        var href_url_arr = $(this).attr("href").split("#");
        var params_uri = convertHASHToURL(href_url_arr[1]);
        var params_obj = getHASHToFancyParams(href_url_arr[1]);
        var extra_uri = makeChildPopupQueryString(md, ar_relmod, incNo);
        pid = (pid) ? pid : -1;
        params_uri += "&rmNum=" + incNo + "&parID=" + pid + extra_uri;
        var req_uri = params_uri;
        openURLFancyBox(req_uri, params_obj);
        e.preventDefault();
        return false;
    });
    $(document).on("click", ".ctrl-custom-btn", function () {
        var btn_name = $(this).attr("aria-btn-name");
        if (!el_form_settings || !$.isPlainObject(el_form_settings['buttons_arr'])) {
            return;
        }
        if (!$.isPlainObject(el_form_settings['buttons_arr'][btn_name])) {
            return;
        }
        var btn_obj = el_form_settings['buttons_arr'][btn_name];
        adminCustomButtonAction([$("#id").val()], btn_obj.alert, btn_obj.confirm, '', el_form_settings['extra_qstr']);
        return false;
    });

    $(document).on("click", "[data-viewer-target]", function () {
        var gallery_source = $(this).attr("data-viewer-target");
        var gallery_loop = $(this).attr("data-viewer-loop");
        var gallery_ext = $(this).attr("data-viewer-ext");
        var gallery_items = [], found_ext = [];
        $("#" + gallery_source).find("." + gallery_loop).each(function () {
            var url = $(this).find(".viewer-item-src").attr("data-item-url");
            var title = $(this).find(".viewer-item-lbl").val();
            var ext = url.split('.').pop();
            if ($.inArray(ext, found_ext) == -1) {
                found_ext.push(ext);
            }
            gallery_items.push({
                'href': url,
                'title': title,
                'extension': ext
            });
        });
        initFormFileViewer(gallery_items, found_ext, gallery_ext);
        return false;
    });
});
function getLoadAdminTab(col, row, blk, code) {
    var jbArr = [];
    jbArr.push({"curr_tab": code, "col": col, "row": row, "blk": blk});
    getActivateAdminTabContent(jbArr);
    return false;
}
function getSaveAndLoadAdminTab(col, row, blk, code) {
    $("#load_tab_" + col + "_" + row).val(code);
    $("#tab_id_" + col + "_" + row).val(blk);
    $("#frmaddupdate_" + col + "_" + row).submit();
    return false;
}
function getNextAdminTab(col, row, blk, nxtblk, code) {
    var jbArr = [];
    blk = parseInt(blk) + 1;
    jbArr.push({"type": "load", "col": col, "row": row, "blk": blk, "curr_tab": code});
    $("#tabcontent_" + col + "_" + row).addClass("hide-overflow").removeClass("show-overflow");
    getActivateAdminTabContent(jbArr);
    return false;
}
function getActivateAdminTabContent(tabArr) {
    Project.show_adaxloading_div();
    var col = tabArr[0]['col'];
    var row = tabArr[0]['row'];
    var blk = tabArr[0]['blk'];
    var curr_tab = tabArr[0]['curr_tab'];

    var js_id = $("#id").val();
    var js_proj_md = $("#projmod").val();
    var $activeObj = $("[id^='tabheading_" + col + "_" + row + "'].active");
    var $currObj = $("[id^='tabheading_" + col + "_" + row + "'].inactive");
    var tab_load = true;
    if (el_form_settings['tab_before_load_func']) {
        if ($.isFunction(window[el_form_settings['tab_before_load_func']])) {
            var tab_callback = window[el_form_settings['tab_before_load_func']](curr_tab, col, row);
            if (tab_callback == false) {
                tab_load = false;
            }
        }
    }
    if (!tab_load) {
        Project.hide_adaxloading_div();
        return false;
    }
    $.ajax({
        url: el_form_settings.tab_wise_block_url,
        type: 'POST',
        data: {
            "tab_code": curr_tab,
            "id": js_id,
            "col": col,
            "row": row,
            "blk": blk
        },
        success: function (response) {
            Project.hide_adaxloading_div();
            $($currObj).html(response).attr("class", "tab-fade active");
            $($activeObj).attr("class", "tab-fade inactive");

            $("li[id^='headinglist_" + col + "_" + row + "']").removeClass("active");
            $("li[id='headinglist_" + col + "_" + row + "_" + blk + "']").addClass("active");
            var tab_ht = $("#tabcontent_" + col + "_" + row).outerHeight();
            var tab_off = $("#tabcontent_" + col + "_" + row).offset();

            var act_tab_top = parseInt(tab_ht) + 40;
            var curr_height_tab = $($($currObj)).outerHeight();
            var tab_top_ht = parseInt(tab_off.top) + parseInt(tab_ht);

            $($currObj).animate({
                "top": "0px"
            }, 500);
            $($activeObj).animate({
                "top": "-" + act_tab_top + "px"
            }, 500);
            var animObj = $("#tabcontent_" + col + "_" + row);
            setTimeout(function () {
                $(animObj).animate({
                    "minHeight": curr_height_tab + "px"
                }, 500);
            }, 500);
            setTimeout(function () {
                $("html,body").animate({scrollTop: 0}, 300);
                $($activeObj).html("").css("top", tab_top_ht + "px");
                $(animObj).addClass("show-overflow").removeClass("hide-overflow");
                initializeBasicAjaxEvents_1($($currObj));
                /*
                 if (js_proj_md != "") {
                 var tab_code = $($currObj).find("input[name='tab_code']").val();
                 var func_dec_main = "Project.modules." + js_proj_md + ".initEvents";
                 if ($.isFunction(eval(func_dec_main))) {
                 eval(func_dec_main + "('" + tab_code + "')");
                 }
                 }
                 */
                getTabJSCallEvents($($currObj), curr_tab, col, row);
                initPreloadCCEvents();
            }, 1000);
        }
    });
}
function adjustTabAnimateHeight() {
//    $(".content-animate").each(function() {
//        var curArr = $(this).attr("id").split("_");
//        var first_height = $("[id^='tabheading_" + curArr[1] + "_" + curArr[2] + "_1']").outerHeight();
//        $(this).height(first_height);
//
//        var cnt_off = $(this).offset();
//        var tot_top = parseInt(cnt_off.top) + parseInt(first_height);
//        $("[id^='tabheading_" + curArr[1] + "_" + curArr[2] + "_2']").css("top", tot_top + "px");
//    });
}
function getTabJSCallEvents(eleObj, currTab, col, row) {
    if (typeof initAdminTabRenderJSScript == "function") {
        if ($.isFunction(initAdminTabRenderJSScript)) {
            initAdminTabRenderJSScript(eleObj);
        }
    }
    if (typeof initAdminTabCustomJSScript == "function") {
        if ($.isFunction(initAdminTabCustomJSScript)) {
            initAdminTabCustomJSScript(eleObj, currTab, col, row);
        }
    }
    if (el_form_settings['tab_after_load_func']) {
        if ($.isFunction(window[el_form_settings['tab_after_load_func']])) {
            window[el_form_settings['tab_after_load_func']](currTab, col, row);
        }
    }
}
// related to conditional coding
function isContainsCCAnyTrue(boolArr) {
    for (var i = 0; i < boolArr.length; i++) {
        if (boolArr[i] == true) {
            return true;
        }
    }
    return false;
}
function isContainsCCAllTrue(boolArr) {
    for (var i = 0; i < boolArr.length; i++) {
        if (boolArr[i] == false) {
            return false;
        }
    }
    return true;
}
function checkCCEventValues(cond_arr) {
    if (!$.isArray(cond_arr) || !cond_arr) {
        return false;
    }
    var json_event_arr = {}, json_final_arr = {}, json_skip_arr = [];
    var js_cond_type, js_cond_list, js_show_list, js_hide_list, cond_check_arr, cond_flag, js_cur_flag, js_cur_val, js_spc_val, js_oper, loop_check_arr;
    var temp_1, temp_2, js_ele_id, js_uni_id, js_ele_type, js_ele_mode, ele_id, ele_mode, ele_type, ele_name;
    json_event_arr = cond_arr;
    for (var i = 0; i < json_event_arr.length; i++) {
        if (!json_event_arr[i].cond_list) {
            continue;
        }
        js_cond_type = json_event_arr[i].cond_type;
        js_cond_list = json_event_arr[i].cond_list;
        js_show_list = json_event_arr[i].show_list;
        js_hide_list = json_event_arr[i].hide_list;
        cond_check_arr = [];
        for (var m in js_cond_list) {
            js_cur_flag = isShowHideElement(js_cond_list[m].id, js_cond_list[m].type);
            if (js_cur_flag && $.inArray(js_cond_list[m].id, json_skip_arr) == "-1") {
                cond_check_arr.push("false");
            } else {
                js_cur_val = getCCElementValue(js_cond_list[m].id, js_cond_list[m].type);
                js_spc_val = js_cond_list[m].value;
                js_oper = js_cond_list[m].oper;
                if ($.isArray(js_spc_val)) {
                    loop_check_arr = [];
                    for (var n in js_spc_val) {
                        temp_1 = getCCMatchStatus(js_oper, js_spc_val[n], js_cur_val);
                        temp_2 = (temp_1) ? "true" : "false";
                        loop_check_arr.push(temp_2);
                    }
                    if ($.inArray("true", loop_check_arr) != "-1") {
                        cond_check_arr.push("true");
                    } else {
                        cond_check_arr.push("false");
                    }
                } else {
                    temp_1 = getCCMatchStatus(js_oper, js_spc_val, js_cur_val);
                    temp_2 = (temp_1) ? "true" : "false";
                    cond_check_arr.push(temp_2);
                }
            }
        }
        if (!$.isArray(cond_check_arr) || !cond_check_arr.length) {
            continue;
        }
        if (js_cond_type == "OR") {
            cond_flag = ($.inArray("true", cond_check_arr) != "-1") ? true : false;
        } else {
            cond_flag = ($.inArray("false", cond_check_arr) == "-1") ? true : false;
        }
        for (var j in js_show_list) {
            js_ele_id = js_show_list[j].id;
            js_ele_type = (js_show_list[j].type && js_show_list[j].type == "module") ? "module" : "field";
            if (js_ele_type == "module") {
                js_uni_id = "child_module_" + js_ele_id;
            } else {
                js_uni_id = "cc_sh_" + js_ele_id;
            }
            if (cond_flag) {
                json_skip_arr.push(js_ele_id);
                js_ele_mode = "show";
            } else {
                js_ele_mode = "hide";
            }
            if (!$.isPlainObject(json_final_arr[js_uni_id])) {
                json_final_arr[js_uni_id] = {};
                json_final_arr[js_uni_id] = {"id": js_uni_id, "mode": js_ele_mode, "type": js_ele_type, "name": js_ele_id};
            } else if (js_ele_mode == "show") {
                json_final_arr[js_uni_id] = {"id": js_uni_id, "mode": js_ele_mode, "type": js_ele_type, "name": js_ele_id};
            }
        }
        for (var j in js_hide_list) {
            js_ele_id = js_hide_list[j].id;
            js_ele_type = (js_hide_list[j].type && js_hide_list[j].type == "module") ? "module" : "field";
            if (js_ele_type == "module") {
                js_uni_id = "child_module_" + js_ele_id;
            } else {
                js_uni_id = "cc_sh_" + js_ele_id;
            }
            if (cond_flag) {
                js_ele_mode = "hide";
            } else {
                json_skip_arr.push(js_ele_id);
                js_ele_mode = "show";
            }
            if (!$.isPlainObject(json_final_arr[js_uni_id])) {
                json_final_arr[js_uni_id] = {};
                json_final_arr[js_uni_id] = {"id": js_uni_id, "mode": js_ele_mode, "type": js_ele_type, "name": js_ele_id};
            } else if (js_ele_mode == "hide") {
                json_final_arr[js_uni_id] = {"id": js_uni_id, "mode": js_ele_mode, "type": js_ele_type, "name": js_ele_id};
            }
        }
    }
    for (var i in json_final_arr) {
        ele_id = json_final_arr[i]['id'];
        ele_mode = json_final_arr[i]['mode'];
        ele_type = json_final_arr[i]['type'];
        ele_name = json_final_arr[i]['name'];
        if (ele_mode == "hide") {
            //$('#' + ele_id).slideUp('slow');
            $('#' + ele_id).hide();
            $('#' + ele_id).find("input,select,textarea").addClass("ignore-show-hide");
            if (ele_type == "module") {
                $("#childModuleShowHide_" + ele_name).val("No");
            }
        } else {
            //$('#' + ele_id).slideDown('slow');
            $('#' + ele_id).show();
            $('#' + ele_id).find("input,select,textarea").removeClass("ignore-show-hide");
            if (ele_type == "module") {
                $("#childModuleShowHide_" + ele_name).val("Yes");
            }
        }
    }
}
function isShowHideElement(eleName, type) {
    var eleObj = "[name='" + eleName + "']";
    if (type == "multi_select_dropdown") {
        eleObj = "[name='" + eleName + "[]']";
    }
    if ($(eleObj).hasClass("ignore-show-hide") && !$(eleObj).hasClass("ignore-valid")) {
        return true;
    } else {
        return false;
    }
}
function getCCElementValue(eleName, type) {
    if (type == "radio_buttons") {
        var curVal = [];
        if ($("input[name='" + eleName + "'][type='hidden']").length) {
            curVal.push($("input[name='" + eleName + "'][type='hidden']").val());
        } else {
            $("input[name='" + eleName + "']:checked").each(function () {
                curVal.push($(this).val());
            });
        }
    } else if (type == "checkboxes") {
        var curVal = [];
        if ($("input[name='" + eleName + "'][type='hidden']").length) {
            curVal.push($("input[name='" + eleName + "'][type='hidden']").val());
        } else {
            $("input[name='" + eleName + "[]']:checked").each(function () {
                curVal.push($(this).val());
            });
        }
    } else if (type == "multi_select_dropdown") {
        var eleObj = "[name='" + eleName + "[]']";
        if (!$("[name='" + eleName + "[]']").length) {
            eleObj = "[name='" + eleName + "']";
        }
        var curVal = $(eleObj).val();
    } else {
        var eleObj = "[name='" + eleName + "']";
        var curVal = $(eleObj).val();
    }
    return curVal;
}
function getCCMatchStatus(oper, spcVal, curVal) {
    var temp = true;
    switch (oper) {
        case 'nu' :
            temp = (curVal == '' || curVal == null || curVal == undefined) ? true : false;
            break;
        case 'nn' :
            temp = (curVal != '' && curVal != null && curVal != undefined) ? true : false;
            break;
        case 'in' :
            if ($.isArray(curVal)) {
                temp = ($.inArray(spcVal, curVal) != "-1") ? true : false;
            } else {
                if (!$.isArray(spcVal) && typeof spcVal != undefined) {
                    spcVal = spcVal.split(",");
                } else {
                    spcVal = [];
                }
                temp = ($.inArray(curVal, spcVal) != "-1") ? true : false;
            }
            break;
        case 'ni' :
            if ($.isArray(curVal)) {
                temp = ($.inArray(spcVal, curVal) == "-1") ? true : false;
            } else {
                if (!$.isArray(spcVal) && typeof spcVal != undefined) {
                    spcVal = spcVal.split(",");
                } else {
                    spcVal = [];
                }
                temp = ($.inArray(curVal, spcVal) == "-1") ? true : false;
            }
            break;
        case 'gt' :
            if ($.isArray(curVal)) {
                temp = (curVal[0] > spcVal) ? true : false;
            } else {
                temp = (curVal > spcVal) ? true : false;
            }
            break;
        case 'ge' :
            if ($.isArray(curVal)) {
                temp = (curVal[0] >= spcVal) ? true : false;
            } else {
                temp = (curVal >= spcVal) ? true : false;
            }
            break;
        case 'lt' :
            if ($.isArray(curVal)) {
                temp = (curVal[0] < spcVal) ? true : false;
            } else {
                temp = (curVal < spcVal) ? true : false;
            }
            break;
        case 'le' :
            if ($.isArray(curVal)) {
                temp = (curVal[0] <= spcVal) ? true : false;
            } else {
                temp = (curVal <= spcVal) ? true : false;
            }
            break;
        case 'ne' :
            if ($.isArray(curVal)) {
                temp = ($.inArray(spcVal, curVal) == "-1") ? true : false;
            } else {
                temp = (curVal != spcVal) ? true : false;
            }
            break;
        default :
            if ($.isArray(curVal)) {
                temp = ($.inArray(spcVal, curVal) != "-1") ? true : false;
            } else {
                temp = (curVal == spcVal) ? true : false;
            }
            break;
    }
    return temp;
}
// related to file type deletion
function deleteInlineFileDocs(ele_id) {
    var label_elem = '<div />';
    var label_text = js_lang_label.GENERIC_ARE_YOU_SURE_WANT_TO_DELETE_THIS;
    var option_params = {
        title: js_lang_label.GENERIC_GRID_DELETE,
        dialogClass: "dialog-confirm-box form-delete-file-cnf",
        buttons: [{
                text: js_lang_label.GENERIC_DELETE,
                bt_type: 'delete',
                click: function () {
                    var multi_upload = false, multi_parent;
                    if ($("#" + ele_id).closest(".upload-multi-file").length) {
                        multi_parent = $("#" + ele_id).closest(".upload-multi-file");
                        multi_upload = true;
                    }
                    $("#" + ele_id).remove();
                    if (multi_upload) {
                        if (!multi_parent.find(".row-upload-file").length && multi_parent.attr("aria-required") == "true") {
                            var module_name = multi_parent.attr("aria-module");
                            var field_name = multi_parent.attr("aria-field");
                            multi_parent.append('<input type="hidden" value="" name="child[' + module_name + '][' + field_name + '][0]" id="child_' + module_name + '_' + field_name + '_0" class="_upload_req_file"/>')
                        }
                    }
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
function deleteFileTypeDocs(id, unique_name, module_url, file_folder, htmlID, langID, file_name) {
    var label_elem = '<div />';
    var label_text = js_lang_label.GENERIC_ARE_YOU_SURE_WANT_TO_DELETE_THIS;
    var option_params = {
        title: js_lang_label.GENERIC_GRID_DELETE,
        dialogClass: "dialog-confirm-box form-delete-file-cnf",
        buttons: [{
                text: js_lang_label.GENERIC_DELETE,
                bt_type: 'delete',
                click: function () {
                    $.ajax({
                        url: admin_url + module_url + '?' + el_form_settings['extra_qstr'],
                        type: 'POST',
                        data: {
                            'id': id,
                            'lang_id': langID,
                            'folder': file_folder,
                            'file': file_name,
                            'unique_name': unique_name
                        },
                        success: function (response) {
                            var respData = parseJSONString(response);
                            var jmgcls = '', jmgtxt = '';
                            if (respData.success == 1) {
                                var old_ele;
                                if (unique_name == htmlID) {
                                    old_ele = "old_" + htmlID;
                                } else {
                                    var split_arr = htmlID.split(unique_name);
                                    var last_id = split_arr.pop();
                                    split_arr.push("old_" + unique_name);
                                    split_arr.push(last_id);
                                    old_ele = split_arr.join('');
                                }
                                $('#anc_imgview_' + htmlID).remove();
                                $('#anc_imgdel_' + htmlID).remove();
                                $('#' + old_ele).val('');
                                $('#' + htmlID).val('');
                                $("#preview_" + htmlID).html(js_lang_label.GENERIC_DROP_FILES_HERE_OR_CLICK_TO_UPLOAD);
                                if ($("#img_buttons_" + htmlID).closest(".row-upload-file").length) {
                                    var multi_upload = false, multi_parent;
                                    if ($("#img_buttons_" + htmlID).closest(".row-upload-file").closest(".upload-multi-file").length) {
                                        multi_parent = $("#img_buttons_" + htmlID).closest(".row-upload-file").closest(".upload-multi-file");
                                        multi_upload = true;
                                    }
                                    $("#img_buttons_" + htmlID).closest(".row-upload-file").remove();
                                    if (multi_upload) {
                                        if (!multi_parent.find(".row-upload-file").length && multi_parent.attr("aria-required") == "true") {
                                            var module_name = multi_parent.attr("aria-module");
                                            var field_name = multi_parent.attr("aria-field");
                                            multi_parent.append('<input type="hidden" value="" name="child[' + module_name + '][' + field_name + '][0]" id="child_' + module_name + '_' + field_name + '_0" class="_upload_req_file"/>')
                                        }
                                    }
                                }
                                jmgcls = 1;
                                if (respData.message != "") {
                                    jmgtxt = respData.message;
                                } else {
                                    jmgtxt = js_lang_label.ACTION_FILE_DELETED_SUCCESSFULLY_C46_C46_C33;
                                }
                            } else {
                                jmgcls = 0;
                                if (respData.message != "") {
                                    jmgtxt = respData.message;
                                } else {
                                    jmgtxt = js_lang_label.GENERIC_ERROR_IN_FILE_DELETION;
                                }
                            }
                            Project.setMessage(jmgtxt, jmgcls, 300);
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
// related to record deletion
function deleteAdminRecordData(id, mod_index_url, mod_edit_url, extra_qstr, extra_hstr) {
    var label_elem = '<div />';
    var label_text = js_lang_label.GENERIC_ARE_YOU_SURE_WANT_TO_DELETE_THIS_RECORD;
    if ('message_arr' in el_form_settings) {
        if ('delete_message' in el_form_settings.message_arr) {
            if (el_form_settings.message_arr.delete_message) {
                label_text = el_form_settings.message_arr.delete_message;
            }
        }
    }
    var option_params = {
        title: js_lang_label.GENERIC_GRID_DELETE,
        dialogClass: "dialog-confirm-box form-delete-rec-cnf",
        buttons: [{
                text: js_lang_label.GENERIC_DELETE,
                bt_type: 'delete',
                click: function () {
                    $.ajax({
                        url: admin_url + mod_edit_url + "?" + extra_qstr,
                        type: 'POST',
                        data: {
                            'id': id,
                            'oper': 'del'
                        },
                        success: function (response) {
                            var respData = parseJSONString(response);
                            var jmgcls = '', jmgtxt = '';
                            if (respData.success == 'true') {
                                jmgcls = 1;
                                if (respData.message != "") {
                                    jmgtxt = respData.message;
                                } else {
                                    jmgtxt = js_lang_label.GENERIC_RECORD_DELETED_SUCCESSFULLY;
                                }
                            } else {
                                jmgcls = 0;
                                if (respData.message != "") {
                                    jmgtxt = respData.message;
                                } else {
                                    jmgtxt = js_lang_label.GENERIC_ERROR_IN_DELETION_OF_RECORD;
                                }
                            }
                            Project.setMessage(jmgtxt, jmgcls, 300);
                            if (respData.success == 'true') {
                                if (allowCloseFancyBox()) {
                                    parent.$.fancybox.close();
                                } else {
                                    loadAdminModuleListing(mod_index_url, extra_hstr);
                                }
                            }
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
    return false;
}
//realted to google maps intialization
function callGoogleMapEvents() {
    if (!$.isArray(google_map_json) || !google_map_json.length) {
        return false;
    }
    var google_api_key = el_tpl_settings.google_maps_key, extra_param;
    if (google_api_key) {
        extra_param = "&key=" + google_api_key;
    }

    if (typeof google == "object") {
        loadGoogleMapEvents();
    } else {
        $.getScript('https://www.google.com/jsapi', function () {
            google.load('maps', '3', {
                other_params: 'sensor=false&libraries=places' + extra_param,
                callback: function () {
                    loadGoogleMapEvents();
                }
            });
        });
    }
}
function loadGoogleMapEvents() {
    if ($.isArray(google_map_json) && google_map_json.length) {
        for (var i in google_map_json) {
            if (google_map_json[i] && google_map_json[i].name) {
                var map_id = google_map_json[i].name;
                initializeGoogleMap(map_id, $("#" + map_id), google_map_json[i]);
            }
        }
    }
}
//related to google maps calling
function initializeGoogleMap(fldName, fldObj, configObj) {
    if (typeof google == "object") {
        activateGoogleMap(fldName, fldObj, configObj);
    } else {
        addGoogleMapsObject(fldName, fldObj, configObj);
    }
}
function addGoogleMapsObject(fldName, fldObj, configObj) {
//    var gscript = document.createElement('script');
//    gscript.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&callback=callGoogleMap';
//    gscript.async = true;
//    document.body.appendChild(gscript);
    var google_api_key = el_tpl_settings.google_maps_key, extra_param;
    if (google_api_key) {
        extra_param = "&key=" + google_api_key;
    }

    $.getScript('https://www.google.com/jsapi', function () {
        google.load('maps', '3', {
            other_params: 'sensor=false&libraries=places' + extra_param,
            callback: function () {
                activateGoogleMap(fldName, fldObj, configObj);
            }
        });
    });
}
function activateGoogleMap(fldName, fldObj, configObj) {
    if ($.isEmptyObject(google)) {
        return false;
    }
    var latfield = '', lonfield = '', zoomfield = '', loadtype = 'No', addrtype = '';
    var country_field = '', state_field = '', city_field = '', zipcode_field = '', callback;

    if ("lat" in configObj) {
        latfield = configObj.lat;
    }
    if ("lng" in configObj) {
        lonfield = configObj.lng;
    }
    if ("zoom" in configObj) {
        zoomfield = configObj.zoom;
    }
    if ("load" in configObj) {
        loadtype = configObj.load;
    }

    if ("country" in configObj) {
        country_field = configObj.country;
    }
    if ("state" in configObj) {
        state_field = configObj.state;
    }
    if ("city" in configObj) {
        city_field = configObj.city;
    }
    if ("zipcode" in configObj) {
        zipcode_field = configObj.zipcode;
    }
    if ("addrtype" in configObj) {
        addrtype = configObj.addrtype;
    }
    if ("callback" in configObj) {
        callback = configObj.callback;
    }

    geocoderMap = new google.maps.Geocoder();
    if ($("#map_canvas_" + fldName).length && $("#" + fldName).length) {
        var onload_map = true, onload_latlng = false, latlng_obj = {};
        var mapOptions = {
            center: new google.maps.LatLng(-33.8688, 151.2195),
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            /*
             panControl: true,
             panControlOptions: {
             position: google.maps.ControlPosition.TOP_LEFT
             },
             */
            mapTypeControl: true,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            zoomControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE
            }
        };
        var map = new google.maps.Map(document.getElementById('map_canvas_' + fldName), mapOptions);
        var input = document.getElementById('gmf_autocomplete_' + fldName);
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        var image = new google.maps.MarkerImage(
                admin_image_url + 'marker.png',
                new google.maps.Size(50, 50),
                new google.maps.Point(0, 0),
                new google.maps.Point(50 / 6, 50 / 1.6)
                );
        var marker = new google.maps.Marker({
            map: map,
            draggable: true,
            icon: image,
            animation: google.maps.Animation.DROP
        });

        function geocodePosition(pos) {
            geocoderMap.geocode({latLng: pos}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    $(fldObj).val(results[0].formatted_address);
                    $('#gmf_autocomplete_' + fldName).val(results[0].formatted_address);
                    $('#gmf_addr_label_' + fldName).html(results[0].formatted_address);
                    if ($('div#show_lat_lng_' + fldName).length) {
                        if (results[0].geometry.location) {
                            $('div#show_lat_lng_' + fldName).html(' Lat / Lng : ' + results[0].geometry.location.lat() + ' , ' + results[0].geometry.location.lng());
                        }
                    }
                    if (latfield != '' && $('#' + latfield).length) {
                        $('#' + latfield).val(results[0].geometry.location.lat());
                    }
                    if (lonfield != '' && $('#' + lonfield).length) {
                        $('#' + lonfield).val(results[0].geometry.location.lng());
                    }
                    var components = getGoogleAddressComponents(results[0].address_components);
                    if ('country' in components) {
                        if (country_field != '' && $('#' + country_field).length) {
                            if (addrtype == 'short') {
                                $('#' + country_field).val(components['country']['short_name']);
                            } else {
                                $('#' + country_field).val(components['country']['long_name']);
                            }
                        }
                    }
                    if ('state' in components) {
                        if (state_field != '' && $('#' + state_field).length) {
                            if (addrtype == 'short') {
                                $('#' + state_field).val(components['state']['short_name']);
                            } else {
                                $('#' + state_field).val(components['state']['long_name']);
                            }
                        }
                    }
                    if ('city' in components) {
                        if (city_field != '' && $('#' + city_field).length) {
                            $('#' + city_field).val(components['city']['long_name']);
                        }
                    }
                    if ('zipcode' in components) {
                        if (zipcode_field != '' && $('#' + zipcode_field).length) {
                            $('#' + zipcode_field).val(components['zipcode']['long_name']);
                        }
                    }
                    if (callback) {
                        if($.isFunction(callback))
                        {
                            callback(results[0]);
                        } else if($.isFunction(window[callback]))
                        {
                            window[callback](results[0]);
                        }
                    }
                }
            });
        }

        function setupClickListener(id, types) {
            var radioButton = document.getElementById(id);
            google.maps.event.addDomListener(radioButton, 'click', function () {
                autocomplete.setTypes(types);
            });
        }

        function moveMarker(placeName, latlng) {
            marker.setIcon(image);
            marker.setPosition(latlng);
            infowindow.setContent(placeName);
            infowindow.open(map, marker);
        }

        function setupAutoComplete(map_json) {
            if (typeof geocoderMap == "object" && map_json != "") {
                geocoderMap.geocode(map_json, function (res, stat) {
                    if (stat == 'OK') {
                        if (loadtype == "Yes" || $('#gmf_autocomplete_' + fldName).val() == "") {
                            $('#gmf_autocomplete_' + fldName).val(res[0].formatted_address);
                        }
                        autocomplete.set('place', {
                            geometry: {
                                location: res[0].geometry.location
                            }
                        });
                        map.panTo(marker.getPosition());
                    }
                });
            }
        }

        function setupLatLngPoniter(map_json) {
            if (typeof geocoderMap == "object" && map_json != "") {
                onload_latlng = true;
                latlng_obj = map_json;
                geocoderMap.geocode(map_json, function (res, stat) {
                    if (stat == 'OK') {
                        autocomplete.set('place', {
                            geometry: {
                                location: res[0].geometry.location
                            }
                        });
                        map.panTo(marker.getPosition());
                    }
                });
            }
        }

        function populateZoomLevel(zoomfield) {
            var zoom_level = 0
            if (zoomfield == "localStorage") {
                var zoom_str = localStorage.getItem(el_tpl_settings.enc_usr_var + "_" + el_form_settings.module_name + "_" + fldName);
                var zoom_json = parseJSONString(zoom_str);
                if (zoom_json && zoom_json.zoom) {
                    zoom_level = zoom_json.zoom;
                }
            } else if ($("#" + zoomfield).length) {
                zoom_level = $("#" + zoomfield).val();
            }
            if (zoom_level >= 1 && zoom_level <= 21) {
                map.setZoom(zoom_level);
            } else {
                map.setZoom(17);  // Why 17? Because it looks good.
            }
        }

        google.maps.event.addListener(marker, 'dragend', function () {
            geocodePosition(marker.getPosition());
            map.panTo(marker.getPosition());
        });

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
                marker.setMap(null);
            }
            if (onload_latlng) {
                marker.setPosition(latlng_obj.latLng);
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    populateZoomLevel(zoomfield);
                }
                var address = $('#gmf_autocomplete_' + fldName).val();
                $(fldObj).val(address);
                $('#gmf_addr_label_' + fldName).html(address);
                if ($('div#show_lat_lng_' + fldName).length) {
                    if (place.geometry.location) {
                        $('div#show_lat_lng_' + fldName).html(' Lat / Lng : ' + latlng_obj.latLng.lat() + ' , ' + latlng_obj.latLng.lng());
                    }
                }
                onload_latlng = onload_map = false;
            } else {
                marker.setPosition(place.geometry.location);
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    if (onload_map) {
                        populateZoomLevel(zoomfield);
                        onload_map = false;
                    } else {
                        map.setZoom(17);  // Why 17? Because it looks good.
                    }
                }
                var address = $('#gmf_autocomplete_' + fldName).val();
                $(fldObj).val(address);
                $('#gmf_addr_label_' + fldName).html(address);
                if ($('div#show_lat_lng_' + fldName).length) {
                    if (place.geometry.location) {
                        $('div#show_lat_lng_' + fldName).html(' Lat / Lng : ' + place.geometry.location.lat() + ' , ' + place.geometry.location.lng());
                    }
                }
                if (latfield != '' && $('#' + latfield).length) {
                    $('#' + latfield).val(place.geometry.location.lat());
                }
                if (lonfield != '' && $('#' + lonfield).length) {
                    $('#' + lonfield).val(place.geometry.location.lng());
                }
                var components = getGoogleAddressComponents(place.address_components);
                if ('country' in components) {
                    if (country_field != '' && $('#' + country_field).length) {
                        if (addrtype == 'short') {
                            $('#' + country_field).val(components['country']['short_name']);
                        } else {
                            $('#' + country_field).val(components['country']['long_name']);
                        }
                    }
                }
                if ('state' in components) {
                    if (state_field != '' && $('#' + state_field).length) {
                        if (addrtype == 'short') {
                            $('#' + state_field).val(components['state']['short_name']);
                        } else {
                            $('#' + state_field).val(components['state']['long_name']);
                        }
                    }
                }
                if ('city' in components) {
                    if (city_field != '' && $('#' + city_field).length) {
                        $('#' + city_field).val(components['city']['long_name']);
                    }
                }
                if ('zipcode' in components) {
                    if (zipcode_field != '' && $('#' + zipcode_field).length) {
                        $('#' + zipcode_field).val(components['zipcode']['long_name']);
                    }
                }
                if (callback) {
                    if($.isFunction(callback))
                    {
                        callback(place);
                    } else if($.isFunction(window[callback]))
                    {
                        window[callback](place);
                    }
                }
            }
            //moveMarker(place.name, place.geometry.location);
        });

        google.maps.event.addListener(map, 'zoom_changed', function (event) {
            if (zoomfield == "localStorage") {
                localStorage.setItem(el_tpl_settings.enc_usr_var + "_" + el_form_settings.module_name + "_" + fldName, JSON.stringify({zoom: map.zoom}));
            } else if ($("#" + zoomfield).length) {
                $("#" + zoomfield).val(map.zoom);
            }
        });

        var txt_value = "", map_json = {};
        if (typeof inline_settings_arr != "object" || !inline_settings_arr) {
            txt_value = $.trim($("#" + fldName).val());
        } else {
            txt_value = $.trim($("#" + fldName).text());
            txt_value = (txt_value !== "Empty") ? txt_value : "";

        }

        if (loadtype == "Yes" && $('#' + latfield).length && $('#' + lonfield).length) {
            var lat_val = $('#' + latfield).val();
            var lon_val = $('#' + lonfield).val();
            if ($.isNumeric(lat_val) && $.isNumeric(lon_val)) {
                map_json = {
                    latLng: new google.maps.LatLng(lat_val, lon_val)
                };
                setupLatLngPoniter(map_json);
            }
            $('#' + latfield + ', #' + lonfield).off("input");
            $('#' + latfield + ', #' + lonfield).on("input", function () {
                setupAutoComplete({latLng: new google.maps.LatLng($('#' + latfield).val(), $('#' + lonfield).val())})
            });
        } else {
            if (txt_value != "") {
                map_json = {address: txt_value};
                setupAutoComplete(map_json);
            }
        }

        $('#gmf_autocomplete_' + fldName).on("input", function () {
            $(fldObj).val($(this).val());
            $('#gmf_addr_label_' + fldName).html($(this).val());
        });

        setupClickListener(fldName + '-changetype-all', []);
        setupClickListener(fldName + '-changetype-establishment', ['establishment']);
        setupClickListener(fldName + '-changetype-geocode', ['geocode']);
    }
}
//realted to google places autocomplete
function callPlacesAutocomplete() {
    if (!$.isArray(google_places_json) || !google_places_json.length) {
        return false;
    }
    var google_api_key = el_tpl_settings.google_maps_key, extra_param;
    if (google_api_key) {
        extra_param = "&key=" + google_api_key;
    }

    if (typeof google == "object") {
        loadPlacesAutocomplete();
    } else {
        $.getScript('https://www.google.com/jsapi', function () {
            google.load('maps', '3', {
                other_params: 'sensor=false&libraries=places' + extra_param,
                callback: function () {
                    loadPlacesAutocomplete();
                }
            });
        });
    }
}
function loadPlacesAutocomplete() {
    if ($.isArray(google_places_json) && google_places_json.length) {
        for (var i in google_places_json) {
            if (google_places_json[i] && google_places_json[i].id) {
                var map_id = google_places_json[i].id;
                initPlacesAutocomplete(map_id, google_places_json[i]);
            }
        }
    }
}
function initPlacesAutocomplete(id, params) {
    if ($.isEmptyObject(google)) {
        return false;
    }

    var latfield = params.lat;
    var lonfield = params.lng;
    var callback = params.callback;

    var input = document.getElementById(id);
    var autocomplete = new google.maps.places.Autocomplete(input);

    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            return;
        }

        if (latfield != '' && $('#' + latfield).length) {
            $('#' + latfield).val(place.geometry.location.lat());
        }
        if (lonfield != '' && $('#' + lonfield).length) {
            $('#' + lonfield).val(place.geometry.location.lng());
        }

        if (callback) {
            if($.isFunction(callback))
            {
                callback(place);
            } else if($.isFunction(window[callback]))
            {
                window[callback](place);
            }
        }
    });
}
function getGoogleAddressComponents(address) {
    var result = {};
    if (!$.isArray(address) || address.length == 0) {
        return result;
    }
    for (var i in address) {
        if ("types" in address[i] && $.isArray(address[i]['types'])) {
            if (address[i]['types'][0] == "country") {
                result['country'] = address[i];
            } else if (address[i]['types'][0] == "administrative_area_level_1") {
                result['state'] = address[i];
            } else if (address[i]['types'][0] == "administrative_area_level_2") {
                result['district'] = address[i];
            } else if (address[i]['types'][0] == "locality") {
                result['city'] = address[i];
            } else if (address[i]['types'][0] == "postal_code") {
                result['zipcode'] = address[i];
            }
        }
    }
    return result;
}
//related to child module through ajax
function getChildModuleAjaxTable(module_url, chid, mode) {
    var pid = $("#childModuleParData_" + chid).val();
    var md = $("#childModuleLayout_" + chid).val();
    var incNo = $("#childModuleInc_" + chid).val();
    var child_add_rec = true;
    if (el_form_settings['callbacks'] && el_form_settings['callbacks']['child_rec_before_add']) {
        if ($.isFunction(window[el_form_settings['callbacks']['child_rec_before_add']])) {
            var child_callback = window[el_form_settings['callbacks']['child_rec_before_add']](chid, incNo);
            if (child_callback == false) {
                child_add_rec = false;
            }
        }
    }
    if (!child_add_rec) {
        return false;
    }
    incNo++;
    $("#childModuleInc_" + chid).val(incNo);
    $("#ajax_loader_childModule_" + chid).show();
    $.ajax({
        url: admin_url + module_url + '?' + el_form_settings['extra_qstr'],
        type: 'POST',
        data: {
            "mode": cus_enc_mode_json[mode],
            "child_module": chid,
            "incNo": incNo,
            'parID': pid
        },
        success: function (data) {
            $("#ajax_loader_childModule_" + chid).hide();
            if (md == "Row") {
                if (el_theme_settings.frm_rel_rec_pos == "append") {
                    $("#tbl_child_module_" + chid).find("[id='add_child_module_" + chid + "']").append(data);
                } else {
                    $("#tbl_child_module_" + chid).find("[id='add_child_module_" + chid + "']").prepend(data);
                }
                initializeBasicAjaxEvents_1($("#div_child_row_" + chid + "_" + incNo));
            } else {
                if (el_theme_settings.frm_rel_rec_pos == "append") {
                    $("#tbl_child_module_" + chid).find("[id='add_child_module_" + chid + "']").append(data);
                } else {
                    $("#tbl_child_module_" + chid).find("[id='add_child_module_" + chid + "']").find(".ch-mod-firstrow").after(data);
                }
                initializeBasicAjaxEvents_1($("#tr_child_row_" + chid + "_" + incNo));
            }
            getChildJSCallEvents("add", chid, incNo);
            //setChildModuleHeight(chid);
            calcChildTotalEntries(chid, md);
        }
    });
    return false;
}
function getChildJSCallEvents(mode, chid, incNo, id) {
    if (typeof initChildRenderJSScript == "function") {
        if ($.isFunction(initChildRenderJSScript)) {
            initChildRenderJSScript();
        }
    }
    if (typeof executeAfterChildRecAdd == "function") {
        if ($.isFunction(executeAfterChildRecAdd)) {
            executeAfterChildRecAdd(chid, incNo);
        }
    }
    if (mode == 'add') {
        if (el_form_settings['callbacks'] && el_form_settings['callbacks']['child_rec_after_add']) {
            if ($.isFunction(window[el_form_settings['callbacks']['child_rec_after_add']])) {
                window[el_form_settings['callbacks']['child_rec_after_add']](chid, incNo);
            }
        }
    } else if (mode == 'save') {
        if (el_form_settings['callbacks'] && el_form_settings['callbacks']['child_rec_after_save']) {
            if ($.isFunction(window[el_form_settings['callbacks']['child_rec_after_save']])) {
                window[el_form_settings['callbacks']['child_rec_after_save']](chid, incNo, id);
            }
        }
    }
}
// popup data updation
function appendChildModuleContent(res) {
    var chid = res.child_module, incNo = res.rmNum, chContent = res.rmContent, rec_mode = res.recMode;
    var md = $("#childModuleLayout_" + chid).val();
    if (md == "Row") {
        if ($("[id='div_child_row_" + chid + "_" + incNo + "']").length) {
            $("[id='div_child_row_" + chid + "_" + incNo + "']").replaceWith(chContent);
        } else {
            $("#tbl_child_module_" + chid).find("[id='add_child_module_" + chid + "']").prepend(chContent);
        }
        initializeBasicAjaxEvents_1($("#div_child_row_" + chid + "_" + incNo));
    } else {
        if ($("[id='tr_child_row_" + chid + "_" + incNo + "']").length) {
            $("[id='tr_child_row_" + chid + "_" + incNo + "']").replaceWith(chContent);
        } else {
            $("#tbl_child_module_" + chid).find("[id='add_child_module_" + chid + "']").find(".ch-mod-firstrow").after(chContent);
        }
        initializeBasicAjaxEvents_1($("#tr_child_row_" + chid + "_" + incNo));
    }
    //setChildModuleHeight(chid);
    calcChildTotalEntries(chid, md);
}
function populateRelationModuleData(module_name, field_arr, inc_no) {
    if (!module_name || !field_arr || !inc_no) {
        return false;
    }
    var uni_name, uni_type, hidden_val, hidden_arr, chk_val, omit_arr = ["dropdown", "multi_select_dropdown", "autocomplete", "file"];
    for (var i in field_arr) {
        uni_name = field_arr[i].name;
        uni_type = field_arr[i].type;
        hidden_val = parent.$("#child_" + module_name + "_" + uni_name + "_" + inc_no).val();
        if ($.trim(hidden_val) != "" && $.inArray(uni_type, omit_arr) == "-1") {
            if ($("#" + uni_name).is('select')) {
                hidden_arr = $.isArray(hidden_val) ? hidden_val : hidden_val.split(",");
                $("#" + uni_name).val(hidden_arr);
            } else if ($("[id^='" + uni_name + "']").is('input') && $("[id^='" + uni_name + "']").attr('type') == "radio") {
                chk_val = $.trim(hidden_val);
                $("#" + uni_name + "_" + chk_val).prop("checked", true);
            } else if ($("[id^='" + uni_name + "']").is('input') && $("[id^='" + uni_name + "']").attr('type') == "checkbox") {
                hidden_arr = $.isArray(hidden_val) ? hidden_val : hidden_val.split(",");
                for (var j = 0; j < hidden_arr.length; j++) {
                    chk_val = $.trim(hidden_arr);
                    $("#" + uni_name + "_" + chk_val).prop("checked", true);
                }
            } else {
                $("#" + uni_name).val(hidden_val);
            }
        }

    }
}
function makeChildPopupQueryString(md, rel_mod, inc_no) {
    var params_uri = '';
    if (md == "Row") {
        var popObj = $("#div_child_row_" + rel_mod + "_" + inc_no);

    } else {
        var popObj = $("#tr_child_row_" + rel_mod + "_" + inc_no);
    }
    $(popObj).find("[aria-popup-data='Yes']").each(function () {
        var key_name = $(this).attr("aria-unique-name");
        params_uri += "&" + key_name + "=" + $(this).val();
    });
    return params_uri;
}
// to set the child module height after cloning and deletion
function setChildModuleHeight(chid) {
    //adjustTabHeightOnFly($("#tbl_child_module_" + chid), 200);
}
//related to child module deletion
function deleteChildModuleRow(module_url, chid, rid) {
    var md = $("#childModuleLayout_" + chid).val();
    var child_del_rec = true;
    if (el_form_settings['callbacks'] && el_form_settings['callbacks']['child_rec_before_delete']) {
        if ($.isFunction(window[el_form_settings['callbacks']['child_rec_before_delete']])) {
            var child_callback = window[el_form_settings['callbacks']['child_rec_before_delete']](chid, rid, 0, "Add");
            if (child_callback == false) {
                child_del_rec = false;
            }
        }
    }

    if (!child_del_rec) {
        return false;
    }

    var label_elem = '<div />';
    var label_text = js_lang_label.GENERIC_ARE_YOU_SURE_WANT_TO_DELETE_THIS_ROW;
    if ('message_arr' in el_form_settings) {
        if ('child_messages' in el_form_settings.message_arr) {
            if ('delete_message' in el_form_settings.message_arr.child_messages) {
                if (chid in el_form_settings.message_arr.child_messages.delete_message) {
                    if (el_form_settings.message_arr.child_messages.delete_message[chid]) {
                        label_text = el_form_settings.message_arr.child_messages.delete_message[chid];
                    }
                }
            }
        }
    }
    var option_params = {
        title: js_lang_label.GENERIC_GRID_DELETE,
        dialogClass: "dialog-confirm-box form-delete-child-cnf",
        buttons: [{
                text: js_lang_label.GENERIC_DELETE,
                bt_type: 'delete',
                click: function () {
                    if (md == "Row") {
                        $("#div_child_row_" + chid + "_" + rid).remove();
                    } else {
                        $("#tr_child_row_" + chid + "_" + rid).remove();
                    }
                    calcChildTotalEntries(chid, md);
                    //setChildModuleHeight(chid)
                    if (el_form_settings['callbacks'] && el_form_settings['callbacks']['child_rec_after_delete']) {
                        if ($.isFunction(window[el_form_settings['callbacks']['child_rec_after_delete']])) {
                            window[el_form_settings['callbacks']['child_rec_after_delete']](chid, rid, 0, {}, "Add");
                        }
                    }
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
    return false;
}
//related to child module saving
function saveChildModuleSingleData(module_save_url, module_render_url, chid, recMode, rid, cid, popup, mode) {
    var chParData = $("#childModuleParData_" + chid).val();
    var md = $("#childModuleLayout_" + chid).val();
    var vfalg = true, vid, ael, oel, vval, ilv, igv, ihv, vres, curr_valiation;
    if (child_rules_arr && child_rules_arr[chid]) {
        curr_valiation = child_rules_arr[chid]
        for (var i in curr_valiation) {
            vid = "child_" + chid + "_" + i + "_" + rid;
            ael = $("[id^='" + vid + "']:first");
            if ($.inArray($(ael).attr("type"), ["checkbox", "radio"]) != "-1") {
                oel = $(ael).attr("name");
                vval = $("[name='" + oel + "']:checked").val();
                ilv = $("[name='" + oel + "']").length;
            } else {
                vval = $("#" + vid).val();
                ilv = $("#" + vid).length;
            }
            igv = $("#" + vid).hasClass("ignore-valid");
            ihv = $("#" + vid).is("hidden");
            if (!ilv || igv || ihv) {
                continue;
            }
            vres = validateViewInlineEdit(vid, vval, curr_valiation[i]);
            if (vres === false) {
                $("#" + vid + "Err").html("");
            } else {
                if (vfalg) {
                    $("#" + vid).focus();
                }
                vfalg = false;
                $("#" + vid + "Err").html(vres);
            }
        }
    }
    if (!vfalg) {
        return false;
    }

    var child_save_rec = true;
    if (el_form_settings['callbacks'] && el_form_settings['callbacks']['child_rec_before_save']) {
        if ($.isFunction(window[el_form_settings['callbacks']['child_rec_before_save']])) {
            var child_callback = window[el_form_settings['callbacks']['child_rec_before_save']](chid, rid, cid);
            if (child_callback == false) {
                child_save_rec = false;
            }
        }
    }

    if (!child_save_rec) {
        return false;
    }

    //$("#add_child_module_" + chid).hide();
    var child_save_url = module_save_url + "?mode=" + cus_enc_mode_json[recMode] + "&parID=" + chParData + "&child_module=" + chid + "&index=" + rid + "&id=" + cid;
    var resArr, jmgcls = '', jmgtxt = '';
    var options = {
        url: child_save_url,
        beforeSubmit: showAdminAjaxRequest,
        success: function (respText, statText, xhr, $form) {
            Project.hide_adaxloading_div();
            resArr = parseJSONString(respText);
            if (!resArr.success) {
                jmgcls = 0;
                if (resArr.message != "") {
                    jmgtxt = resArr.message;
                } else {
                    jmgtxt = js_lang_label.GENERIC_ERROR_IN_DELETION_OF_RECORD;
                }
                if (md == "Row") {
                    $('#' + chform_name).replaceWith($("#div_child_row_" + chid + "_" + rid));
                } else {
                    $('#' + chform_name).replaceWith($("#tr_child_row_" + chid + "_" + rid));
                    $("#tr_child_row_" + chid + "_" + rid).show();
                }
            } else {
                jmgcls = 1;
                if (resArr.message != "") {
                    jmgtxt = resArr.message;
                } else {
                    jmgtxt = js_lang_label.GENERIC_RECORD_SAVED_SUCCESSFULLY;
                }
                $.ajax({
                    url: module_render_url,
                    type: 'POST',
                    data: {
                        "mode": cus_enc_mode_json[mode],
                        "child_module": chid,
                        "incNo": rid,
                        'parID': chParData,
                        "recMode": "Update",
                        "rmPopup": popup,
                        "id": resArr.id
                    },
                    success: function (data) {
                        //$("#add_child_module_" + chid).show();
                        $('#' + chform_name).replaceWith(data);
                        if (md == "Row") {
                            initializeBasicAjaxEvents_1($("#div_child_row_" + chid + "_" + rid));
                        } else {
                            $("#tr_child_row_" + chid + "_" + rid).show();
                            initializeBasicAjaxEvents_1($("#tr_child_row_" + chid + "_" + rid));
                        }
                        getChildJSCallEvents("save", chid, rid, cid);
                        calcChildTotalEntries(chid, md);
                        //setChildModuleHeight(chid);
                    }
                });
            }
            Project.setMessage(jmgtxt, jmgcls);
        }
    };
    var chform_name = "frmchild_module_save_" + chid;
    if (md == "Row") {
        $("#div_child_row_" + chid + "_" + rid).wrap("<form name='" + chform_name + "' id='" + chform_name + "' action='" + child_save_url + "' method='post'  enctype='multipart/form-data'>");
    } else {
        $("#tr_child_row_" + chid + "_" + rid).hide();
        $("#tr_child_row_" + chid + "_" + rid).wrap("<form name='" + chform_name + "' id='" + chform_name + "' action='" + child_save_url + "' method='post'  enctype='multipart/form-data'>");
    }
    $('#' + chform_name).ajaxSubmit(options);
    return false;
}
//related to child module deletion from database
function deleteChildModuleSingleData(module_url, chid, rid, cid) {
    var md = $("#childModuleLayout_" + chid).val();
    var child_del_rec = true;
    if (el_form_settings['callbacks'] && el_form_settings['callbacks']['child_rec_before_delete']) {
        if ($.isFunction(window[el_form_settings['callbacks']['child_rec_before_delete']])) {
            var child_callback = window[el_form_settings['callbacks']['child_rec_before_delete']](chid, rid, cid, "Update");
            if (child_callback == false) {
                child_del_rec = false;
            }
        }
    }

    if (!child_del_rec) {
        return false;
    }
    var label_elem = '<div />';
    var label_text = js_lang_label.GENERIC_ARE_YOU_SURE_WANT_TO_DELETE_THIS_RECORD_FROM_DATABASE;
    if ('message_arr' in el_form_settings) {
        if ('child_messages' in el_form_settings.message_arr) {
            if ('delete_message' in el_form_settings.message_arr.child_messages) {
                if (chid in el_form_settings.message_arr.child_messages.delete_message) {
                    if (el_form_settings.message_arr.child_messages.delete_message[chid]) {
                        label_text = el_form_settings.message_arr.child_messages.delete_message[chid];
                    }
                }
            }
        }
    }
    var option_params = {
        title: js_lang_label.GENERIC_GRID_DELETE,
        dialogClass: "dialog-confirm-box form-delete-child-cnf",
        buttons: [{
                text: js_lang_label.GENERIC_DELETE,
                bt_type: 'delete',
                click: function () {
                    $.ajax({
                        type: 'POST',
                        url: admin_url + module_url + '?' + el_form_settings['extra_qstr'],
                        data: {
                            'child_module': chid,
                            'id': cid,
                            'oper': 'del'
                        },
                        success: function (response) {
                            var respData = parseJSONString(response);
                            var jmgcls = '', jmgtxt = '';
                            if (respData.success == '1') {
                                jmgcls = 1;
                                if (respData.message != "") {
                                    jmgtxt = respData.message;
                                } else {
                                    jmgtxt = js_lang_label.GENERIC_RECORD_DELETED_SUCCESSFULLY;
                                }
                                if (md == "Row") {
                                    $("#div_child_row_" + chid + "_" + rid).remove();
                                } else {
                                    $("#tr_child_row_" + chid + "_" + rid).remove();
                                }
                                calcChildTotalEntries(chid, md);
                                //setChildModuleHeight(chid);
                            } else {
                                jmgcls = 0;
                                if (respData.message != "") {
                                    jmgtxt = respData.message;
                                } else {
                                    jmgtxt = js_lang_label.GENERIC_ERROR_IN_DELETION_OF_RECORD;
                                }
                            }
                            Project.setMessage(jmgtxt, jmgcls);
                            if (el_form_settings['callbacks'] && el_form_settings['callbacks']['child_rec_after_delete']) {
                                if ($.isFunction(window[el_form_settings['callbacks']['child_rec_after_delete']])) {
                                    window[el_form_settings['callbacks']['child_rec_after_delete']](chid, rid, cid, respData, "Update");
                                }
                            }
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
    return false;
}
//related to child module calculation
function calcChildTotalEntries(chid, md) {
    if (md == "Row") {
        var tot_count = $("div[id='tbl_child_module_" + chid + "'] div[id^='div_child_row_" + chid + "_']").length;
        $("#childModuleCount_" + chid).val(tot_count);
        $("div[id='tbl_child_module_" + chid + "'] div[id^='div_child_row_" + chid + "_']:first").each(function (i) {
            //$(this).find("hr.hr-line").remove();
        });
    } else {
        var tot_count = $("table[id='tbl_child_module_" + chid + "'] tr[id^='tr_child_row_" + chid + "_']").length;
        $("#childModuleCount_" + chid).val(tot_count);
        $("table[id='tbl_child_module_" + chid + "'] tr[id^='tr_child_row_" + chid + "_']").each(function (i) {
            var row_num = parseInt(i) + 1;
            $(this).find("[class='row-num-span']").html(row_num);
        });
    }
}
//related to place holder changes
function placeErrorMessage(element, eleid, error) {
    $('#' + eleid + 'Err').html(error);
}
//related to event calling on chnage
function adminAjaxChangeEventData(eleObj, replace_id, module_url, unique_name, mode, value, id, params) {
    var sel_val = $(eleObj).val();
    if ($("#" + replace_id + "_chosen").length) {
        $("#" + replace_id + "_chosen").hide();
    } else {
        $("#" + replace_id).hide();
    }
    $("#ajax_loader_" + replace_id).show();
    var data = {
        'parent_src[]': sel_val,
        "unique_name": unique_name,
        'mode': cus_enc_mode_json[mode],
        'id': id
    };
    var after_callback;
    if ($.isPlainObject(params)) {
        data = $.extend(params, data);
        if ("_callback" in params) {
            after_callback = params._callback;
            delete params._callback;
        }
    }
    $.ajax({
        url: admin_url + module_url,
        type: 'POST',
        data: data,
        success: function (data) {
            $("#ajax_loader_" + replace_id).hide();
            if ($("#" + replace_id + "_chosen").length) {
                $("#" + replace_id + "_chosen").show();
            } else {
                $("#" + replace_id).show();
            }
            var jdata_arr = parseJSONString(data);
            var old_val = $("#" + replace_id).val();
            if (jdata_arr.status == true) {
                $("#" + replace_id).html(jdata_arr.content);
                $("#" + replace_id).val(old_val);
                $("#" + replace_id).trigger("chosen:updated").trigger("change");
            }
            if (after_callback && $.isFunction(window[after_callback])) {
                window[after_callback]();
            }
        }
    });
}
//related to image/file on the fly diaply
function displayAdminOntheFlyImage(hid, rarr) {
    if (("resized" in rarr && "imgURL" in rarr) || (!("resized" in rarr) && "fileURL" in rarr)) {
        var $img_attr = '', $img_src = '';
        if (!rarr['resized']) {
            $img_src = rarr['fileURL'];
            $img_attr = "width='" + rarr['width'] + "' height='" + rarr['height'] + "'";
        } else {
            $img_src = rarr['imgURL'];
        }

        var $img_str = $("<a />");
        $($img_str).attr("id", "anc_imgview_" + hid)
                .attr("href", rarr['fileURL'])
                .addClass("fancybox-image")
                .html("<img src='" + $img_src + "' alt='Image' " + $img_attr + "/>");
        $("#img_view_" + hid).html($img_str);
        $("#img_hover_" + hid).html("");
        initializeFancyBoxEvents($("#img_view_" + hid));
        /*
         $('#anc_imgview_' + hid).qtip({
         content: "<img src='" + rarr['fileURL'] + "' alt='Image' />"
         });
         */
    } else if (rarr['success']) {
        var icon_class = "fa-file-text-o";
        if (rarr['iconclass']) {
            icon_class = rarr['iconclass'];
        }
        var $img_str = $("<a />");
        $($img_str).attr("id", "anc_imgview_" + hid)
                .attr("href", "javascript://")
                .html("<i class='fa " + icon_class + " fa-3x'></i>");
        $("#img_view_" + hid).html($img_str);
        var $eye_str = $("<div />");
        $($eye_str).attr("title", rarr['iconfile'])
                .addClass("tip")
                .html("<i class='icon18 minia-icon-eye icon-red no-margin'></i>");
        $("#img_hover_" + hid).html($eye_str).addClass("view-mode");
        initializeTooltipsEvents($("#img_hover_" + hid));
    }
    if (!$('#anc_imgdel_' + hid).length && $("#img_del_" + hid).length) {
        var $del_str = $("<a />");
        $($del_str).attr("id", "anc_imgdel_" + hid)
                .attr("href", 'javascript://')
                .attr("title", 'Delete')
                .attr("onclick", 'deleteAdminOntheFlyImage("' + hid + '")')
                .html("<i class='icon16 entypo-icon-close icon-red no-margin'></i>");
        $("#img_del_" + hid).html($del_str);
    }
    try {
        if ($("#" + hid).parents("form").length) {
            $("#" + hid).valid();
        }
    } catch (e) {

    }
}
function deleteAdminOntheFlyImage($js_html_id) {
    var label_elem = '<div />';
    var label_text = js_lang_label.GENERIC_ARE_YOU_SURE_WANT_TO_DELETE_THIS;
    var option_params = {
        title: js_lang_label.GENERIC_GRID_DELETE,
        dialogClass: "dialog-confirm-box form-delete-img-cnf",
        buttons: [{
                text: js_lang_label.GENERIC_DELETE,
                bt_type: 'delete',
                click: function () {
                    $("#img_view_" + $js_html_id).html("<span><img src='" + el_tpl_settings.noimage_url + "' width='50' height='50'></span>");
                    $("#img_del_" + $js_html_id).html("");
                    $("#preview_" + $js_html_id).html(js_lang_label.GENERIC_DROP_FILES_HERE_OR_CLICK_TO_UPLOAD);
                    $("#" + $js_html_id).val("");
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
function addAdminOntheFlyImage(mid, name, rarr) {
    var last_obj = $("#upload_multi_file_" + mid).find("[id^='upload_row_" + mid + "']:last");
    var new_id = '0', $img_str = '', $desc_str = '', $view_str = '', $eye_str = '', img_view_cls = '';
    if (last_obj && last_obj.length > 0 && last_obj.attr("id")) {
        var last_id_arr = last_obj.attr("id").split("upload_row_" + mid + "_");
        new_id = last_id_arr[1];
        new_id++;
    }
    var crea_id = 'upload_row_' + mid + "_" + new_id;
    var input_str = '<input type="hidden" value="' + rarr.oldfile + '" name="child[' + mid + '][old_' + name + '][]" id="child_' + mid + '_old_' + name + '_' + new_id + '" /> <input type="hidden" value="' + rarr.uploadfile + '" name="child[' + mid + '][' + name + '][]" id="child_' + mid + '_' + name + '_' + new_id + '" class="ignore-valid"/>';
    if (("resized" in rarr && "imgURL" in rarr) || (!("resized" in rarr) && "fileURL" in rarr)) {
        var $img_attr = '', $img_src = '';
        if (!rarr['resized']) {
            $img_src = rarr['fileURL'];
            $img_attr = "width='" + rarr['width'] + "' height='" + rarr['height'] + "'";
        } else {
            $img_src = rarr['imgURL'];
        }
        $view_str = $("<a />")
                .attr("id", "anc_imgview_child_" + mid + "_" + name + "_" + new_id)
                .attr("href", rarr['fileURL'])
                .attr("data-item-url", rarr['fileURL'])
                .addClass("fancybox-image form-image-anchor viewer-item-src")
                .html("<img src='" + $img_src + "' alt='Image' " + $img_attr + "/>");
        img_view_cls = "";
    } else {
        var icon_class = "fa-file-text-o";
        if (rarr['iconclass']) {
            icon_class = rarr['iconclass'];
        }
        $view_str = $("<a />")
                .attr("id", "anc_imgview_child_" + mid + "_" + name + "_" + new_id)
                .attr("href", "javascript://")
                .attr("data-item-url", rarr['fileURL'])
                .addClass("fancybox-image form-image-view viewer-item-src")
                .html("<i class='fa " + icon_class + " fa-3x'></i>");
        img_view_cls = "";//doc-view-section
        $eye_str = $("<div />");
        $($eye_str).attr("title", rarr['iconfile'])
                .addClass("tip")
                .html("<i class='icon18 minia-icon-eye icon-red no-margin'></i>");
    }

    var $del_str = $("<a />")
            .attr("id", "anc_imgdel_child_" + mid + "_" + name + "_" + new_id)
            .attr("href", "javascript://")
            .attr("title", js_lang_label.GENERIC_DELETE)
            .attr("hijacked", "yes")
            .attr("onclick", "deleteInlineFileDocs('upload_row_" + mid + "_" + new_id + "')")
            .html("<i class='icon16 entypo-icon-close icon-red no-margin'></i>");

    var $img_view_str = $("<div />")
            .attr("id", "img_view_child_" + mid + "_" + name + "_" + new_id)
            .addClass("img-view-section")
            .append($view_str);//.addClass("doc-view-section")

    var $img_del_str = $("<div />")
            .attr("id", "img_del_child_" + mid + "_" + name + "_" + new_id)
            .addClass("img-del-section")
            .append($del_str);

    if ($eye_str != "") {
        var $img_eye_str = $("<div />")
                .attr("id", "img_hover_child_" + mid + "_" + name + "_" + new_id)
                .addClass("img-hover-section view-mode")
                .append($eye_str);
    }

    var $whole_img_str = $("<div />")
            .attr("id", "img_buttons_child_" + mid + "_" + name + "_" + new_id)
            .addClass("img-inline-display")
            .append($($img_view_str))
            .append($($img_del_str));
    if ($eye_str != "") {
        $whole_img_str.append($img_eye_str).addClass("file-inline-display");
    }
    $img_str = '<div class="row-file-block">' + $($whole_img_str)[0].outerHTML + '</div>';
    if ($("#upload_multi_file_" + mid).hasClass("multi-file-desc-block")) {
        var desc_elem_name = $("#childModuleFileDesc_" + mid).val();
        var old_desc_elem = $("#upload_multi_file_" + mid).find(".row-upload-file:eq(0)").find(".row-desc-block .multi-file-desc-elem");
        var new_desc_elem = $("<input />")
                .prop("type", "text")
                .attr("value", rarr['filename'])
                .attr("name", "child[" + mid + "][" + desc_elem_name + "][]")
                .attr("id", "child_" + mid + "_" + desc_elem_name + "_" + new_id)
                .attr("title", $(old_desc_elem).attr("title"))
                .attr("placeholder", $(old_desc_elem).attr("placeholder"))
                .addClass("multi-file-desc-elem viewer-item-lbl");
        var $desc_str = '<div class="row-desc-block">' + $(new_desc_elem)[0].outerHTML + '</div>';
    }
    var new_str = '<div class="row-upload-file" id="' + crea_id + '">' + input_str + '' + $img_str + $desc_str + '</div>';
    $("#upload_multi_file_" + mid).append(new_str);
    initializeFancyBoxEvents($("#img_view_child_" + mid + "_" + name + "_" + new_id));
    initializeTooltipsEvents($("#img_hover_child_" + mid + "_" + name + "_" + new_id));
    if ($("#upload_multi_file_" + mid).find("._upload_req_file").length) {
        $("#upload_multi_file_" + mid).find("._upload_req_file").remove();
    }
    /*
     $("#anc_imgview_child_" + mid + "_" + name + "_" + new_id).qtip({
     content: "<img src='" + rarr['fileURL'] + "' alt='Image' />"
     });
     */
}
// showing password type data for a while 
function adminShowHidePasswordField(js_id) {
    $('#' + js_id).prop('type', 'text');
    $('#span_password_' + js_id).removeClass('iconic-icon-lock-fill').addClass('iconic-icon-unlock-fill');
    $('#a_password_' + js_id).attr('title', 'Password displayed');
    setTimeout(function () {
        $('#' + js_id).prop('type', 'password');
        $('#span_password_' + js_id).removeClass('iconic-icon-unlock-fill').addClass('iconic-icon-lock-fill');
        $('#a_password_' + js_id).attr('title', 'Click this to show password');
    }, 5000);
}
// related language translations
function showAdminLanguageArea(eleObj, type, field_name, lang_code) {
    if (type == 'single') {
        $("[id^=lnsh_" + field_name + "]").slideUp(100);
        $("#lnsh_" + field_name + "_" + lang_code).slideDown(200);
    } else if (type == 'all') {
        var disp_flag = $(eleObj).attr("aria-display");
        if (disp_flag == "hide") {
            $("[id^=lnsh_" + field_name + "]").slideDown(200);
            $(eleObj).attr("aria-display", "show");
            $(eleObj).attr("title", js_lang_label.GENERIC_HIDE_ALL);
            $(eleObj).find("span").removeClass("cut-icon-expand").addClass("cut-icon-shrink");
        } else {
            $("[id^=lnsh_" + field_name + "]").slideUp(100);
            $(eleObj).attr("aria-display", "hide");
            $(eleObj).attr("title", js_lang_label.GENERIC_SHOW_ALL);
            $(eleObj).find("span").removeClass("cut-icon-shrink").addClass("cut-icon-expand");
        }
    }
//    setTimeout(function() {
//        adjustTabHeightOnFly(eleObj, 200);
//    }, 301)
    return false;
}
function adjustTabHeightOnFly(eleObj, timeLimit) {
//    var headingObj = $(eleObj).closest('div[id^="tabheading"]');
//    var contentObj = $(eleObj).closest('div[id^="tabcontent"]');
//    if ($(headingObj).length > 0 && $(contentObj).hasClass("content-animate")) {
//        var tab_ht = $(headingObj).outerHeight();
//        $(contentObj).animate({
//            "height": tab_ht + "px"
//        }, timeLimit);
//    }
}
//related to multilingual
function multilingualEditorContent(editorText, eleInstance) {
    var srcLang = el_form_settings.prime_lang_code;
    var defaultLang = el_form_settings.default_lang_code;
    var destLangJSON = el_form_settings.other_lang_JSON;
    var destLang = parseJSONString(destLangJSON);
    if (!srcLang || !destLang) {
        return false;
    }
    if ("multi_lingual_trans" in el_tpl_settings && !el_tpl_settings.multi_lingual_trans) {
        Project.setMessage(js_lang_label.GENERIC_LANGUAGE_TRANSLATION_IS_TURNED_OFF, 2, 200);
        return false;
    }
    if ("multi_lingual_trans" in el_form_settings && !el_form_settings.multi_lingual_trans) {
        Project.setMessage(js_lang_label.GENERIC_LANGUAGE_TRANSLATION_IS_TURNED_OFF, 2, 200);
        return false;
    }

    var eleObj = $("#" + eleInstance.id);
    if ($(eleObj).attr("aria-multi-call") == "0") {
        return false;
    }

    var objLang = eleObj.attr("aria-lang-code");
    if ("other_lingual_trans" in el_tpl_settings && el_tpl_settings.other_lingual_trans) {
        if (objLang != srcLang && objLang != defaultLang) {
            return false;
        }
    } else {
        if (srcLang != objLang) {
            return false;
        }
    }

    var objVal = editorText;
    if ($.trim(objVal) != "") {
        var objID = eleObj.attr("aria-lang-parent");
        objLang = (objLang) ? objLang : srcLang;
        var othLang = destLang.slice();
        if (objLang != srcLang) {
            var objInd = othLang.indexOf(objLang);
            if (objInd !== -1) {
                othLang.splice(objInd, 1);
            }
            if ($.inArray(srcLang, othLang) == -1) {
                othLang.push(srcLang);
            }
        }
        showhide_inline_loading(eleObj, 'show');
        $.ajax({
            url: admin_url + cus_enc_url_json['general_language_conversion'],
            type: 'POST',
            data: {
                'text': objVal,
                'type': 'html',
                "src": objLang,
                'dest[]': othLang
            },
            success: function (data) {
                showhide_inline_loading(eleObj, 'hide');
                var res_data_arr = parseJSONString(data);
                if (res_data_arr) {
                    for (var langCode in res_data_arr) {
                        if ($.trim(res_data_arr[langCode]) != "") {
                            var langElem;
                            if (langCode == srcLang) {
                                langElem = objID;
                            } else {
                                langElem = "lang_" + objID + "_" + langCode;
                            }
                            $("#" + langElem).val(res_data_arr[langCode]);
                        }
                    }
                    if ("success" in res_data_arr && res_data_arr.success == 0) {
                        Project.setMessage(res_data_arr.message, 0);
                    }
                }
            }
        });
    }
}
function intializeLanguageAutoEntry(srcLang, destLangJSON, defaultLang) {
    var destLang = parseJSONString(destLangJSON);
    if (!srcLang || !destLang) {
        return false;
    }
    if ("multi_lingual_trans" in el_tpl_settings && !el_tpl_settings.multi_lingual_trans) {
        Project.setMessage(js_lang_label.GENERIC_LANGUAGE_TRANSLATION_IS_TURNED_OFF, 2, 200);
        return false;
    }
    if ("multi_lingual_trans" in el_form_settings && !el_form_settings.multi_lingual_trans) {
        Project.setMessage(js_lang_label.GENERIC_LANGUAGE_TRANSLATION_IS_TURNED_OFF, 2, 200);
        return false;
    }

    var changeSelectors = "[aria-multi-lingual='parent']";
    if ("other_lingual_trans" in el_tpl_settings && el_tpl_settings.other_lingual_trans) {
        if (srcLang != defaultLang) {
            changeSelectors += ",[aria-multi-lingual='child'][aria-lang-code='" + defaultLang + "']";
        }
    }
    $(document).off("change", changeSelectors);
    $(document).off("focus", changeSelectors);
    $(document).off("blur", changeSelectors);
    $(document).on("change", changeSelectors, function () {
        var eleObj = $(this);
        var objVal = eleObj.val();
        var objID = eleObj.attr("aria-lang-parent");
        var objLang = eleObj.attr("aria-lang-code");
        objLang = (objLang) ? objLang : srcLang;
        var othLang = destLang.slice();
        if (objLang != srcLang) {
            var objInd = othLang.indexOf(objLang);
            if (objInd !== -1) {
                othLang.splice(objInd, 1);
            }
            if ($.inArray(srcLang, othLang) == -1) {
                othLang.push(srcLang);
            }
        }
        if ($.trim(objVal) != "") {
            showhide_inline_loading(eleObj, 'show');
            $.ajax({
                url: admin_url + cus_enc_url_json['general_language_conversion'],
                type: 'POST',
                data: {
                    'text': objVal,
                    'type': 'plain',
                    "src": objLang,
                    'dest[]': othLang
                },
                success: function (data) {
                    showhide_inline_loading(eleObj, 'hide');
                    var res_data_arr = parseJSONString(data);
                    if (res_data_arr) {
                        for (var langCode in res_data_arr) {
                            if ($.trim(res_data_arr[langCode]) != "") {
                                var langElem;
                                if (langCode == srcLang) {
                                    langElem = objID;
                                } else {
                                    langElem = "lang_" + objID + "_" + langCode;
                                }
                                $("#" + langElem).val(res_data_arr[langCode]);
                            }
                        }
                        if ("success" in res_data_arr && res_data_arr.success == 0) {
                            Project.setMessage(res_data_arr.message, 0);
                        }
                    }
                }
            });
        }
        $(this).attr("aria-multi-call", "1");
    }).on('focus', changeSelectors, function () {
        $(this).attr("aria-multi-call", "0");
    }).on('blur', changeSelectors, function () {
        if ($(this).attr("aria-multi-call") == '0') {
            var c_trig = false;
            var currId = $(this).attr("id");
            var objID = $(this).attr("aria-lang-parent");
            var objType = $(this).attr("aria-multi-lingual");
            $("[id^='lang_" + objID + "']").each(function () {
                if ($(this).val() == "" && currId != $(this).attr("id")) {
                    c_trig = true;
                    return false;
                }
            });
            if (objType == "child") {
                if ($("#" + objID).val() == "") {
                    c_trig = true;
                }
            }
            if (c_trig) {
                $(this).trigger('change');
            }
        }
    })
}
//related multilingual loader
function showhide_inline_loading(eleObj, type) {
    if (type == "show") {
        var objOffset = $(eleObj).offset();
        var objLeft = objOffset.left;
        var objTop = objOffset.top;
        var objWidth = $(eleObj).width();
        var finalLeft = 25 + parseInt(objWidth) + parseInt(objLeft);
        var finalTop = 4 + parseInt(objTop);
        $("#ajax_lang_loader").css({
            "top": finalTop + "px",
            "left": finalLeft + "px"
        }).show();
    } else {
        $("#ajax_lang_loader").hide();
    }
}
//related x-editable form
function saveFormInlineEditble(name, value, id, extra) {
    var data = {
        "name": name,
        "value": value,
        "id": id
    };
    var obj_prop = inline_settings_arr[name];
    if (typeof extra == 'object') {
        data = $.extend({}, data, extra);
    }
    var options = {
        "url": el_form_settings.jajax_action_url,
        "data": data,
        success: function (obj, config) {
            var res_arr = parseJSONString(obj);
            if (res_arr && res_arr.success == 'false') {
                var $jq_errmsg = js_lang_label.GENERIC_ERROR_IN_UPDATION;
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
                    case 'wysiwyg':
                    case 'code_markup_field':
                        $('#' + name).html(value);
                        break;
                    case 'multi_select_dropdown':
                    case 'checkboxes':
                        $('#' + name).html($('#' + name).attr("data-value"));
                        break;
                    case 'autocomplete':
                        var par_obj = obj_prop.editevents.token.params;
                        if (token_pre_populates[name].length > 0) {
                            $('#' + name).html($('#' + name).attr("data-value"));
                        }
                        par_obj.prePopulate = token_pre_populates[name];
                        break;
                    case 'password':
                        $('#' + name).html("*****");
                        break;
                    case 'file':
                        inline_settings_arr[name]['dbval'] = value;
                        break;
                    case 'rating_master':
                        $("#rshow_" + obj_prop.name).raty('set', {score: value});
                        $("#rscore_" + obj_prop.name).text(value);
                        $("#" + obj_prop.name).html('<span class="icon16 icomoon-icon-pencil-5"><b>' + js_lang_label.GENERIC_EDIT + '</b></span>');
                        break;
                    case 'google_maps':
                        inline_settings_arr[name]['value'] = value;
                        $('#' + name).attr("aria-prev-value", value);
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
function addElementProperties(eleObj, params) {
    if (!params) {
        return;
    }
    for (var i in params) {
        $(eleObj).attr(i, params[i]);
    }
}
function assignEventParams(params) {
    var assign_params = {};
    if (params) {
        for (var i in params) {
            assign_params[i] = (params[i] == "true") ? true : ((params[i] == "false") ? false : params[i]);
        }
    }
    return assign_params;
}
function makeEditableDropdown(i_name) {
    if (!inline_settings_arr[i_name].editoptions || !inline_settings_arr[i_name].editoptions.dataUrl) {
        return;
    }
    var i_value = inline_settings_arr[i_name]['value'];
    $('#' + i_name).attr("data-value", i_value);
    var i_dataUrl = inline_settings_arr[i_name].editoptions.dataUrl;
    $('#' + i_name).editable({
        showbuttons: true,
        type: 'select',
        source: i_dataUrl,
        pk: el_form_settings.form_edit_id,
        sourceCache: false,
        validate: function (value) {
            var vid = $(this).attr("id");
            return validateViewInlineEdit(vid, value, inline_settings_arr[vid])
        },
        url: function (params) {
            saveFormInlineEditble(params.name, params.value, params.id);
        }
    });
    $('#' + i_name).on('shown', function (e, editable) {
        var ele_obj = $(editable.$form).find(".editable-input").find("select");
        $(editable.$form).find(".editable-input").append(inline_settings_arr[i_name].add_content);
        var ref_url = $(editable.$form).find('.fancybox-hash-iframe').attr('href');
        $(ele_obj).find("option").removeAttr("selected");
        var ele_name = $(this).attr("id");
        var data_val = $(this).attr("data-value");
        var obj_pro = inline_settings_arr[ele_name];
        var parent_attr = obj_pro.parentattr;
        var edit_attr = obj_pro.editattr;
        switch (obj_pro.type) {
            case 'checkboxes':
                $(ele_obj).attr("multiple", true);
                break;
            case 'multi_select_dropdown':
                $(ele_obj).attr("multiple", true);
                break;
        }
        addElementProperties($(ele_obj), edit_attr);
        ele_obj = $(editable.$form).find(".editable-input").find("select");
        if (obj_pro.type == "checkboxes" || obj_pro.type == "multi_select_dropdown") {
            var data_val_arr = [];
            $.each(data_val.split(","), function () {
                data_val_arr.push($.trim(this));
            });
            $(ele_obj).find("option").each(function () {
                if ($.inArray($.trim($(this).text()), data_val_arr) != -1) {
                    $(this).prop("selected", true);
                }
            });
        } else {
            $(ele_obj).find("option").each(function () {
                if ($.trim($(this).text()) == $.trim(data_val)) {
                    $(this).prop("selected", true);
                    return false;
                }
            });
        }
        setTimeout(function () {
            $(ele_obj).chosen({
                allow_single_deselect: true
            });
            $(editable.$form).find('.fancybox-hash-iframe').attr('href', ref_url + '|rfhtmlID|' + $(ele_obj).attr("id"));
            $('#' + $(ele_obj).attr("id") + '_chosen').trigger('mousedown');
            $('#' + $(ele_obj).attr("id") + '_chosen').find("input[type='text']").focus();
            if (obj_pro.editoptions.ajaxCall == "ajax-call") {
                var inlineID = obj_pro.editoptions.rel;
                var $queryStr = "&mode=" + cus_enc_mode_json['Update'] + "&&unique_name=" + inlineID + "&id=" + el_form_settings.form_edit_id;
                var $ajaxSendURL = el_grid_settings.ajax_data_url + $queryStr;
                $(ele_obj).ajaxChosen({
                    dataType: 'json',
                    type: 'POST',
                    url: $ajaxSendURL
                }, {
                    loadingImg: admin_image_url + "chosen-loading.gif"
                });
            }
        }, 5);

        $(ele_obj).change(function () {
            if (obj_pro.type == "checkboxes" || obj_pro.type == "multi_select_dropdown") {
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
        });
    });

}
function makeEditableTextArea(i_name) {
    if (!inline_settings_arr[i_name]) {
        return;
    }
    var i_value = inline_settings_arr[i_name]['value'];
    $('#' + i_name).editable({
        showbuttons: true,
        type: 'textarea',
        name: i_name,
        placeholder: inline_settings_arr[i_name].place_holder,
        value: i_value,
        pk: el_form_settings.form_edit_id,
        onblur: 'submit',
        rows: 3,
        validate: function (value) {
            var vid = $(this).attr("id");
            return validateViewInlineEdit(vid, value, inline_settings_arr[vid]);
        },
        url: function (params) {
            saveFormInlineEditble(params.name, params.value, params.id);
        }
    });
    $('#' + i_name).on('shown', function (e, editable) {
        var ele_name = $(this).attr("id");
        var ele_obj = $(editable.$form).find(".editable-input").find("textarea");
        var obj_pro = inline_settings_arr[ele_name];
        var parent_attr = obj_pro.parentattr;
        var edit_attr = obj_pro.editattr;
        switch (obj_pro.type) {
            case 'textarea':
                addElementProperties($(ele_obj), edit_attr);
                appendTextAreaProperties(ele_obj, obj_pro);
                applyInputTextCase($(editable.$form).find(".editable-input"));
                break;
            case 'code_markup_field':
                appendCodeMarkupProperties(ele_obj, obj_pro);
                break;
            case 'google_maps':
                appendGoogleMapsProperties(ele_obj, obj_pro);
                break;
            case 'wysiwyg':
                appendEditorProperties(ele_obj, obj_pro);
                break;
        }
    });
}
function appendEditorProperties(ele_obj, obj_pro) {
    if (!obj_pro) {
        return;
    }
    var ele_name = obj_pro.name;
    var ele_events = obj_pro.editevents;
    if (ele_events.tinymce) {
        var basic_params = assignEventParams(ele_events.tinymce.params);
        var toolbar_params = {};
        if (ele_events.tinymce.toolbar == "advanced") {
            toolbar_params = {
                plugins: tinymce_editor_plugins,
                toolbar: tinymce_editor_tollbar
            };
        } else if (ele_events.tinymce.toolbar == "premium") {
            toolbar_params = {
                plugins: tinymce_editor_plugins_premium,
                toolbar: tinymce_editor_tollbar_premium,
                external_plugins: {"filemanager": el_tpl_settings.js_libraries_url + "filemanager/plugin.min.js"},
            };

        } else {
            toolbar_params = {
                plugins: tinymce_editor_plugins_basic,
                toolbar: tinymce_editor_tollbar_basic
            };
        }
        var function_params = {
            templates: tinymce_editor_templates,
            setup: function (ed) {
                ed.on('change', function (e) {
                    tinyMCE.triggerSave();
                });
            }
        }
        var final_params = $.extend({}, basic_params, toolbar_params, function_params);
        tinyMCE.baseURL = el_tpl_settings.editor_js_url;
        $(ele_obj).tinymce(final_params);
    }
}
function appendGoogleMapsProperties(ele_obj, obj_pro) {
    if (!obj_pro) {
        return;
    }
    var ele_name = obj_pro.name;
    var ele_label = obj_pro.label;
    var ele_events = obj_pro.editevents
    var ele_val = obj_pro.value;
    var parent_attr = obj_pro.parentattr;
    var edit_attr = obj_pro.editattr;
    if (ele_events.maps) {
        $(ele_obj).hide();
        var map_str = "<div>";
        map_str += "<div class='frm-gmf-address-label'>";
        map_str += "<span id='gmf_addr_label_" + ele_name + "'>" + ele_val + "</span>";
        map_str += "</div>";
        map_str += "<span " + edit_attr + ">";
        map_str += "<textarea class='frm-gmf-address elastic' name='gmf_autocomplete_" + ele_name + "' id='gmf_autocomplete_" + ele_name + "' title='" + ele_label + "'>" + ele_val + "</textarea>";
        map_str += "</span>";
        map_str += "</div>";

        map_str += "<div class='frm-gmf-options'>";
        map_str += "<input type='radio' name='type' id='" + ele_name + "-changetype-all' checked='checked'>";
        map_str += "<label for='" + ele_name + "-changetype-all'>" + js_lang_label.GENERIC_ALL + "</label>&nbsp;&nbsp;";
        map_str += "<input type='radio' name='type' id='" + ele_name + "-changetype-establishment'>";
        map_str += "<label for='" + ele_name + "-changetype-establishment'>" + js_lang_label.GENERIC_ESTABLISHMENTS + "</label>&nbsp;&nbsp;";
        map_str += "<input type='radio' name='type' id='" + ele_name + "-changetype-geocode'>";
        map_str += "<label for='" + ele_name + "-changetype-geocode'>" + js_lang_label.GENERIC_GEOCODES + "</lable>";
        map_str += "</div>";

        if (ele_events.maps.showlatlng == "Yes") {
            map_str += "<div id='show_lat_lng_" + ele_name + "' class='frm-gmf-latlng'></div>";
        }
        map_str += "<span class='canvas_map'><div id='map_canvas_" + ele_name + "' " + edit_attr + "></div></span>";
        var par_obj = $(ele_obj).closest('.editable-container');
        $(par_obj).wrap('<span />');
        $(ele_obj).after(map_str);
        setTimeout(function () {
            $('#gmf_autocomplete_' + ele_name).elastic();
            initializeGoogleMap(ele_name, $(ele_obj), {});
        }, 100)

    }

}
function appendCodeMarkupProperties(ele_obj, obj_pro) {
    if (!obj_pro) {
        return;
    }
    var ele_name = obj_pro.name;
    var ele_events = obj_pro.editevents;
    if (ele_events.CodeMirror) {
        var ele_id = ele_name + "_cm";
        $(ele_obj).attr("id", ele_id);
        var basic_params = assignEventParams(ele_events.CodeMirror.params);
        var function_params = {
            onCursorActivity: function (instance) {
                $(ele_obj).val(instance.getValue());
            }
        }
        var final_params = $.extend({}, basic_params, function_params);
        removeCodeMarkupProperties(ele_id);
        var editor = CodeMirror.fromTextArea(document.getElementById(ele_id), final_params);
        $('#' + ele_id).data('cm', editor);
    }
}
function appendTextAreaProperties(ele_obj, obj_pro) {
    if (!obj_pro) {
        return;
    }
    var ele_name = obj_pro.name;
    var ele_events = obj_pro.editevents;
    if (ele_events.elastic) {
        $(ele_obj).elastic();
    }
    if (ele_events.inputlimiter) {
        var basic_params = assignEventParams(ele_events.inputlimiter.params);
        $(ele_obj).inputlimiter(basic_params);
    }
}
function appendUploadifyProperties(ele_obj, obj_pro) {
    if (!obj_pro) {
        return;
    }
    var ele_name = obj_pro.name;
    var ele_val = obj_pro.dbval;
    var ele_label = obj_pro.label;
    var ele_parent = obj_pro.parentattr;
    var ele_events = obj_pro.editevents;

//    var $fileStr = "<input type='hidden' value='" + ele_val + "' name='temp_" + ele_name + "' id='temp_" + ele_name + "' />";
//    $fileStr += "<input type='file' name='uploadify_" + ele_name + "' id='uploadify_" + ele_name + "' title='" + ele_label + "'/>";
//    var par_obj = $(ele_obj).closest('.editable-container')
//    $(par_obj).wrap('<span ' + ele_parent + ' id="btn_file_' + ele_name + '"/>');
//    $(ele_obj).after($fileStr);
//    if (ele_events.uploadify) {
//        var upload_data = ele_events.uploadify;
//        /*
//         if (upload_data.capture && upload_data.capture == "Yes") {
//         var cptscr = getCaptureDetailScript(ele_name, upload_data['unique_name'], upload_data['iModuleAddId']);
//         $(ele_obj).before(cptscr);
//         detectCaptureCameraAllow(ele_name, upload_data['unique_name'], upload_data['uploader']);
//         }
//         */
//        var upload_params = upload_data.params;
//        var basic_params = assignEventParams(upload_params);
//        var function_params = {
//            'formData': {
//                'unique_name': upload_data['unique_name'],
//                'id': upload_data['id'],
//                'type': 'uploadify'
//            },
//            'onSelect': function(file) {
//                var joldval = $('#temp_' + ele_name).val();
//                $('#uploadify_' + ele_name).uploadify('settings', 'formData', {
//                    'oldFile': joldval
//                });
//                $('#uploadify_' + ele_name).uploadify('upload');
//            },
//            'onUploadSuccess': function(file, data, response) {
//                if (data) {
//                    var jparse_data = parseJSONString(data);
//                    if (jparse_data.success == "0") {
//                        Project.setMessage(jparse_data.message, 0);
//                    } else {
//                        $('#' + ele_name).val(jparse_data.uploadfile);
//                        $('#temp_' + ele_name).val(jparse_data.oldfile);
//                        displayAdminOntheFlyImage(ele_name, jparse_data);
//                        var old_data = {
//                            "old_file": ele_val
//                        };
//                        saveFormInlineEditble(ele_name, jparse_data.uploadfile, el_form_settings.form_edit_id, old_data);
//                        setTimeout(function() {
//                            $('.frm-block-layout').click();
//                        }, 200);
//                    }
//                }
//            }
//        };
//        var final_params = $.extend({}, basic_params, function_params);
//        $('#uploadify_' + ele_name).uploadify(final_params);
//    }

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


    var upload_data = ele_events.fileupload;
    /*
     if (upload_data.capture && upload_data.capture == "Yes") {
     var cptscr = getCaptureDetailScript(ele_name, upload_data['unique_name'], upload_data['iModuleAddId']);
     $(ele_obj).before(cptscr);
     detectCaptureCameraAllow(ele_name, upload_data['unique_name'], upload_data['uploader']);
     }
     */
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
//                    $('#practive_' + _input_name).css('width', '0%');
//                    $('#progress_' + _input_name).show();
                _form_data['oldFile'] = $('#' + _temp_name).val();
                $(this).fileupload('option', 'formData', _form_data);
                $('#preview_' + _input_name).html(data.originalFiles[0]['name']);
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
                    displayAdminOntheFlyImage(_input_name, jparse_data);
//                        setTimeout(function() {
//                            $('#progress_' + _input_name).hide();
//                        }, 1000);
                    var old_data = {
                        "old_file": ele_val
                    };
                    saveFormInlineEditble(_input_name, jparse_data.uploadfile, el_form_settings.form_edit_id, old_data);
                    setTimeout(function () {
                        $('.frm-block-layout').click();
                    }, 200);
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
function makeEditableTextBox(i_name) {
    if (!inline_settings_arr[i_name]) {
        return;
    }
    var i_value = inline_settings_arr[i_name]['value'];
    var show_btn = true;
    if (inline_settings_arr[i_name]['type'] == "file") {
        i_value = inline_settings_arr[i_name]['dbval'];
        show_btn = false;
    } else if (inline_settings_arr[i_name]['type'] == "rating_master") {
        i_value = inline_settings_arr[i_name]['dbval'];
    }
    $('#' + i_name).editable({
        showbuttons: show_btn,
        type: 'text',
        name: i_name,
        placeholder: inline_settings_arr[i_name].place_holder,
        value: i_value,
        pk: el_form_settings.form_edit_id,
        onblur: 'submit',
        validate: function (value) {
            var vid = $(this).attr("id");
            return validateViewInlineEdit(vid, value, inline_settings_arr[vid]);
        },
        url: function (params) {
            saveFormInlineEditble(params.name, params.value, params.id);
        }
    });
    $('#' + i_name).on('shown', function (e, editable) {
        var ele_name = $(this).attr("id");
        var ele_obj = $(editable.$form).find(".editable-input").find("input[type='text']");
        var obj_pro = inline_settings_arr[ele_name];
        var parent_attr = obj_pro.parentattr;
        var edit_attr = obj_pro.editattr;
        switch (obj_pro.type) {
            case 'date':
                $(ele_obj).addClass("date-picker-icon");
                activeDateTimePicker(ele_obj, "date", obj_pro.editevents.datepicker.params);
                addElementProperties($(ele_obj), edit_attr);
                break;
            case 'date_and_time':
                $(ele_obj).addClass("date-picker-icon")
                activeDateTimePicker(ele_obj, "dateTime", obj_pro.editevents.datetimepicker.params);
                addElementProperties($(ele_obj), edit_attr);
                break;
            case 'time':
                $(ele_obj).addClass("date-picker-icon")
                activeDateTimePicker(ele_obj, "time", obj_pro.editevents.timepicker.params);
                addElementProperties($(ele_obj), edit_attr);
                break;
            case 'phone_number':
                $(ele_obj).mask(obj_pro.editoptions.format);
                addElementProperties($(ele_obj), edit_attr);
                break;
            case 'file':
                $(ele_obj).hide();
                appendUploadifyProperties(ele_obj, obj_pro);
//                if (el_general_settings.having_flash_obj) {
//                    appendUploadifyProperties(ele_obj, obj_pro);
//                } else {
//                    uploadifyFlashError();
//                }
                break;
            case 'textbox':
                addElementProperties($(ele_obj), edit_attr);
                applyInputTextCase($(editable.$form).find(".editable-input"));
                applyAddonElementHTML($(editable.$form).find(".editable-input"), obj_pro);
                break;
            case 'color_picker':
                activateColorPicker(ele_obj, edit_attr.color_preview);
                addElementProperties($(ele_obj), edit_attr);
                break;
            case 'autocomplete':
                var $ele_rand_id = Math.floor((Math.random() * 100000) + 1);
                $(ele_obj).attr('id', $ele_rand_id);
                $(ele_obj).wrap('<div class="frm-token-autocomplete frm-size-medium" />');
                activateAutoComplete(ele_obj, obj_pro, ele_name);
                addElementProperties($(ele_obj), edit_attr);
                setTimeout(function () {
                    $(editable.$form).find(".frm-token-autocomplete").append(inline_settings_arr[i_name].add_content);
                    var ref_url = $(editable.$form).find('.fancybox-hash-iframe').attr('href');
                    $(editable.$form).find('.fancybox-hash-iframe').attr('href', ref_url + '|rfhtmlID|' + $ele_rand_id);
                }, 5);
                break;
            case 'rating_master':
                $(ele_obj).hide();
                appendRatingProperties(ele_obj, obj_pro);
                break;
        }
    });
    if (inline_settings_arr[i_name]['type'] == "rating_master") {
        $('#' + i_name).on('hidden', function (e, reason) {
            var ele_name = $(this).attr("id");
            $("#rshow_" + ele_name).show();
        });
    }
}
function activateAutoComplete(ele_obj, obj_pro, ele_name) {
    var par_obj = obj_pro.editevents.token.params;
    token_pre_populates[ele_name] = par_obj.prePopulate;
    getAutoCompDataValueArr(ele_name);
    token_input_assign = $(ele_obj).tokenInput(obj_pro.editevents.serviceUrl, {
        minChars: par_obj.minChars,
        multi: obj_pro.editevents.multi,
        propertyToSearch: par_obj.propertyToSearch,
        theme: par_obj.theme,
        tokenLimit: par_obj.tokenLimit,
        hintText: par_obj.hintText,
        noResultsText: par_obj.noResultsText,
        searchingText: par_obj.searchingText,
        preventDuplicates: par_obj.preventDuplicates,
        prePopulate: par_obj.prePopulate,
        onAdd: function (item) {
            token_pre_populates[ele_name] = token_input_assign.tokenInput('get');
            getAutoCompDataValueArr(ele_name);
        },
        onDelete: function (item) {
            token_pre_populates[ele_name] = token_input_assign.tokenInput('get');
            getAutoCompDataValueArr(ele_name);
        }
    });
}
function getAutoCompDataValueArr(ele_name) {
    var $data_arr = [];
    for (i in token_pre_populates[ele_name]) {
        $data_arr[i] = token_pre_populates[ele_name][i]['val'];
    }
    $("#" + ele_name).attr("data-value", $data_arr.join(","));
}
function appendRatingProperties(eleObj, obj_pro) {
    var raty_elem, rv_elem, rh_elem, txt;
    raty_elem = $('<span />', {"id": "rstar_" + obj_pro.name, "aria-raty-name": obj_pro.name}).addClass("rating-icons-block");
    rh_elem = $("#rshow_" + obj_pro.name);
    rv_elem = $("#rscore_" + obj_pro.name);
    txt = $(rv_elem).text();
    var raty_params = $.extend({}, obj_pro.editevents.raty.params);
    raty_params.target = eleObj;
    activateRatingMasterEvent(raty_elem, raty_params, obj_pro.editevents.raty.hints, txt)
    $(eleObj).after(raty_elem);
    $(rh_elem).hide();
}
function makeEditablePassword(i_name) {
    if (!inline_settings_arr[i_name]) {
        return;
    }
    var i_value = inline_settings_arr[i_name]['value'];
    $('#' + i_name).editable({
        showbuttons: true,
        type: 'password',
        placeholder: inline_settings_arr[i_name].place_holder,
        name: i_name,
        value: i_value,
        pk: el_form_settings.form_edit_id,
        onblur: 'submit',
        validate: function (value) {
            var vid = $(this).attr("id");
            return validateViewInlineEdit(vid, value, inline_settings_arr[vid])
        },
        url: function (params) {
            saveFormInlineEditble(params.name, params.value, params.id);
        }
    });
    $('#' + i_name).on('shown', function (e, editable) {
        var ele_name = $(this).attr("id");
        var ele_obj = $(editable.$form).find(".editable-input").find("input[type='password']");
        var obj_pro = inline_settings_arr[ele_name];
        var parent_attr = obj_pro.parentattr;
        var edit_attr = obj_pro.editattr;
        addElementProperties($(ele_obj), edit_attr);
        if (obj_pro.patternlock) {
            $(ele_obj).attr("role", "patternlock");
            initializePatternPwdEvents($(editable.$form).find(".editable-input"));
        }
    });
}
function intializeInlineEditble(eleObj) {
    if (!inline_settings_arr) {
        return;
    }
    var inline_settings = inline_settings_arr;
    for (var i in inline_settings) {
        var v_editable = inline_settings[i]['editable'];
        if (!v_editable) {
            continue;
        }
        var v_type = inline_settings[i]['type'];
        var v_name = inline_settings[i]['name'];
        if (eleObj && $(eleObj).length) {
            if (!$(eleObj).find("#" + v_name).length) {
                continue;
            }
        }
        switch (v_type) {
            case 'checkboxes' :
                makeEditableDropdown(v_name);
                break;
            case 'code_markup_field' :
                makeEditableTextArea(v_name);
                break;
            case 'date' :
                makeEditableTextBox(v_name);
                break;
            case 'date_and_time' :
                makeEditableTextBox(v_name);
                break;
            case 'dropdown' :
                makeEditableDropdown(v_name);
                break;
            case 'file' :
                makeEditableTextBox(v_name);
                break;
            case 'google_maps' :
                makeEditableTextArea(v_name);
                break;
            case 'multi_select_dropdown' :
                makeEditableDropdown(v_name);
                break;
            case 'password' :
                makeEditablePassword(v_name);
                break;
            case 'phone_number' :
                makeEditableTextBox(v_name);
                break;
            case 'radio_buttons' :
                makeEditableDropdown(v_name);
                break;
            case 'textarea' :
                makeEditableTextArea(v_name);
                break;
            case 'time' :
                makeEditableTextBox(v_name);
                break;
            case 'color_picker' :
                makeEditableTextBox(v_name);
                break;
            case 'autocomplete' :
                makeEditableTextBox(v_name);
                break;
            case 'rating_master' :
                makeEditableTextBox(v_name);
                break;
            case 'wysiwyg' :
                makeEditableTextArea(v_name);
                break;
            default :
                //for textbox
                makeEditableTextBox(v_name);
                break;
        }
    }
}
function displaySettingOntheFlyImage(hid, rarr) {
    if (rarr['fileURL']) {
        var del_btn = "<a title='" + js_lang_label.GENERIC_GRID_DELETE + "' style='text-decoration:none;' href='javascript://' onclick='deleteSettingFileTypeDocs(\"" + hid + "\")' id='anc_imgdel_" + hid + "' >";
        del_btn += "<i class='icon16 entypo-icon-close icon-red no-margin'></i>";
        del_btn += "</a>";

        var $img_str = $("<a />");
        if (rarr['fileType'] == 'file') {
            $($img_str).attr("id", "anc_imgview_" + hid)
                    .attr("href", rarr['fileURL'])
                    .attr("target", "_blank")
                    .html("<i class='fa fa-file-text-o fa-2x'></i>");
            $("#img_view_" + hid).html($img_str);
        } else {
            var $img_attr = '';
            if (!rarr['resized']) {
                $img_attr = "width='" + rarr['width'] + "' height='" + rarr['height'] + "'";
            }
            $($img_str).attr("id", "anc_imgview_" + hid)
                    .attr("href", rarr['fileURL'])
                    .addClass("fancybox-image")
                    .html("<img src='" + rarr['fileURL'] + "' alt='Image' " + $img_attr + "'/>");
            $("#img_view_" + hid).html($img_str);
            /*
             $('#anc_imgview_' + hid).qtip({
             content: "<img src='" + rarr['fileURL'] + "' alt='Image' />"
             });
             */
            initializeFancyBoxEvents($("#img_view_" + hid));
        }
        $("#img_del_" + hid).html(del_btn);
    }
}
function deleteSettingFileTypeDocs(htmlID) {
    var label_elem = '<div />';
    var label_text = js_lang_label.GENERIC_ARE_YOU_SURE_WANT_TO_DELETE_THIS;
    var option_params = {
        title: js_lang_label.GENERIC_GRID_DELETE,
        dialogClass: "dialog-confirm-box form-delete-file-cnf",
        buttons: [{
                text: js_lang_label.GENERIC_DELETE,
                bt_type: 'delete',
                click: function () {
                    $.ajax({
                        url: $upload_form_file,
                        type: 'POST',
                        data: {
                            'vSettingName': htmlID,
                            'actionType': 'delete',
                            'vValue': $("#" + htmlID).val()
                        },
                        success: function (response) {
                            var res_arr = parseJSONString(response);
                            if (res_arr.success == '1') {
                                $('#img_view_' + htmlID).html("");
                                $("#img_del_" + htmlID).html("");
                                $('#old_' + htmlID).val('');
                                $("#" + htmlID).val('');
                            } else {
                                Project.setMessage(js_lang_label.GENERIC_ERROR_IN_FILE_DELETION, 0);
                            }
                        }
                    });
                    $(this).remove();
                }}, {
                text: js_lang_label.GENERIC_CANCEL,
                bt_type: 'cancel',
                click: function () {
                    $(this).remove();
                }
            }]
    }
    jqueryUIdialogBox(label_elem, label_text, option_params);
}
function applyAddonElementHTML(parObj, params) {
    if (params && params.addon_apply) {
        if (params.addon_html) {
            if (params.addon_pos && params.addon_pos == "prefix") {
                $(parObj).prepend(params.addon_html);
            } else {
                $(parObj).append(params.addon_html);
            }
        } else {
            if (params.addon_prefix) {
                $(parObj).prepend(params.addon_prefix);
            }
            if (params.addon_suffix) {
                $(parObj).append(params.addon_suffix)
            }
        }
    }
}
function initDetailViewEditable() {
    if (!detail_view_colmodel_json || !el_topview_settings.permit_edit_btn) {
        return;
    }
    var detail_settings_obj = {};
    for (i in detail_view_colmodel_json) {
        var v_type = detail_view_colmodel_json[i]['type'];
        var v_editable = detail_view_colmodel_json[i]['editable'];
        var v_edittype = detail_view_colmodel_json[i]['edittype'];
        var v_name = detail_view_colmodel_json[i]['htmlID'];
        detail_settings_obj[v_name] = detail_view_colmodel_json[i];

        if (v_type == "rating_master") {
            displayDetailViewRatingProperties(v_name, detail_settings_obj[v_name]);
        }
        if (!v_editable) {
            continue;
        }
        switch (v_type) {
            case 'checkboxes' :
                makeDetailViewEditableDropdown(v_name, detail_settings_obj);
                break;
            case 'code_markup_field' :
                makeDetailViewEditableTextArea(v_name, detail_settings_obj);
                break;
            case 'date' :
                makeDetailViewEditableTextBox(v_name, detail_settings_obj);
                break;
            case 'date_and_time' :
                makeDetailViewEditableTextBox(v_name, detail_settings_obj);
                break;
            case 'dropdown' :
                makeDetailViewEditableDropdown(v_name, detail_settings_obj);
                break;
            case 'file' :
                makeDetailViewEditableTextBox(v_name, detail_settings_obj);
                break;
            case 'google_maps' :
                makeDetailViewEditableTextArea(v_name, detail_settings_obj);
                break;
            case 'multi_select_dropdown' :
                makeDetailViewEditableDropdown(v_name, detail_settings_obj);
                break;
            case 'password' :
                makeDetailViewEditablePassword(v_name, detail_settings_obj);
                break;
            case 'phone_number' :
                makeDetailViewEditableTextBox(v_name, detail_settings_obj);
                break;
            case 'radio_buttons' :
                makeDetailViewEditableDropdown(v_name, detail_settings_obj);
                break;
            case 'textarea' :
                makeDetailViewEditableTextArea(v_name, detail_settings_obj);
                break;
            case 'time' :
                makeDetailViewEditableTextBox(v_name, detail_settings_obj);
                break;
            case 'color_picker':
                makeDetailViewEditableTextBox(v_name, detail_settings_obj);
                break;
            case 'autocomplete' :
                makeDetailViewEditableTextBox(v_name, detail_settings_obj);
                break;
            case 'rating_master' :
                makeDetailViewEditableTextBox(v_name, detail_settings_obj);
                break;
            case 'wysiwyg' :
                makeDetailViewEditableTextArea(v_name, detail_settings_obj);
                break;
            default :
                //for textbox
                makeDetailViewEditableTextBox(v_name, detail_settings_obj);
                break;
        }
    }

}
function saveDetailInlineEdit(name, value, id, extra) {
    var obj_prop = detail_view_colmodel_json[name];
    var data = {
        "name": obj_prop.name,
        "value": value,
        "id": id
    };
    if (typeof extra == 'object') {
        data = $.extend({}, data, extra);
    }
    var options = {
        "url": el_topview_settings.edit_page_url,
        "data": data,
        success: function (obj, config) {
            var res_arr = parseJSONString(obj);
            if (res_arr && res_arr.success == 'false') {
                var $jq_errmsg = js_lang_label.GENERIC_ERROR_IN_UPDATION;
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
                    case "multi_select_dropdown" :
                    case "checkboxes" :
                        if ($('#' + name).attr("data-value") != '') {
                            $('#' + name).html($('#' + name).attr("data-value"));
                        }
                        break;
                    case "autocomplete" :
                        var par_obj = obj_prop.editoptions.token.params;
                        if (detail_token_pre_populates[name].length > 0) {
                            $('#' + name).html($('#' + name).attr("data-value"));
                        }
                        par_obj.prePopulate = detail_token_pre_populates[name];
                        break;
                    case 'password':
                        $('#' + name).html("*****");
                        break;
                    case "file" :
                        detail_view_colmodel_json[name]['dbval'] = value;
                        displayAdminFormFlyImage(name, id, obj_prop.editoptions.uploadify, res_arr);
                        break;
                    case "rating_master":
                        $("#rshow_" + obj_prop.htmlID).raty('set', {score: value});
                        $("#rscore_" + obj_prop.htmlID).text(value);
                        $("#" + obj_prop.htmlID).html('<span class="icon16 icomoon-icon-pencil-5"><b>' + js_lang_label.GENERIC_EDIT + '</b></span>');
                        break;
                    default:
                        $('#' + name).attr("aria-prev-value", value);
                        break;
                }
            }
        }
    };
    $('#' + name).editable("submit", options);
}
function adminAjaxAutoCompChangeEvent(eleObj, replaceId, init, params) {
    var tokenObject = $("#" + replaceId).data("tokenInputObject");
    if (!tokenObject) {
        return false;
    }
    var ele_child = $("#" + replaceId).data("tokenInputObject").getManualsettings();
    ele_child.extraParams = "parent_id=" + $(eleObj).val();
    if ($.isPlainObject(params)) {
        for (var i in params) {
            ele_child.extraParams += "&" + i + "=" + params[i];
        }
    }
    if (!init) {
        ele_child.prePopulate = {};
    }
    $("#" + replaceId).data("tokenInputObject").destroy();
    if ($("#autocomp_" + replaceId).find(".token-input-list-facebook").length) {
        $("#autocomp_" + replaceId).find(".token-input-list-facebook").remove();
    }
    if ($("#autocomp_" + replaceId).find(".token-input-list").length) {
        $("#autocomp_" + replaceId).find(".token-input-list").remove();
    }
    $("#" + replaceId).tokenInput(ele_child.url, ele_child);
}
function makeDetailViewEditableTextBox(v_name, detail_settings_obj) {
    var v_value = detail_settings_obj[v_name]['value'];
    var show_btn = true;
    if (detail_settings_obj[v_name]['type'] == "file") {
        var i_value = detail_settings_obj[v_name]['dbval'];
        show_btn = false;
    }
    $('#' + v_name).editable({
        showbuttons: show_btn,
        placeholder: detail_settings_obj[v_name].editoptions.placeholder,
        type: 'text',
        name: v_name,
        value: i_value,
        pk: el_topview_settings.edit_id,
        onblur: 'submit',
        validate: function (value) {
            var vid = $(this).attr("id");
            return validateViewInlineEdit(vid, value, detail_settings_obj[vid]);
        },
        url: function (params) {
            saveDetailInlineEdit(params.name, params.value, params.id);
        }
    });
    $('#' + v_name).on('shown', function (e, editable) {
        var ele_name = $(this).attr("id");
        var ele_obj = $(editable.$form).find(".editable-input").find("input[type='text']");
        var obj_pro = detail_settings_obj[ele_name];
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
//                if (el_general_settings.having_flash_obj) {
                //                    appendDetailViewUploadifyProperties(ele_obj, obj_pro);
                //                } else {
//                    uploadifyFlashError();
                //                }
                appendDetailViewUploadifyProperties(ele_obj, obj_pro);
                break;
            case 'autocomplete' :
                var $ele_rand_id = Math.floor((Math.random() * 100000) + 1);
                $(ele_obj).attr('id', $ele_rand_id);
                $(ele_obj).wrap('<div class="frm-token-autocomplete frm-size-medium" />');
                activateDetailViewAutoComplete(ele_obj, obj_pro, v_name);
                addElementProperties(ele_obj, obj_pro.editoptions);
                setTimeout(function () {
                    $(editable.$form).find(".frm-token-autocomplete").append(detail_settings_obj[v_name].add_content);
                    var ref_url = $(editable.$form).find('.fancybox-hash-iframe').attr('href');
                    $(editable.$form).find('.fancybox-hash-iframe').attr('href', ref_url + '|rfhtmlID|' + $ele_rand_id);
                }, 5);
                break;
            case 'rating_master':
                $(ele_obj).hide();
                appendDetailViewRatingProperties(ele_obj, obj_pro);
                break;
        }
        if (parseInt(obj_pro.editoptions.width) > 0) {
            $(ele_obj).css("width", obj_pro.editoptions.width);
        }
    });
    if (detail_settings_obj[v_name]['type'] == "rating_master") {
        $('#' + v_name).on('hidden', function (e, reason) {
            var ele_name = $(this).attr("id");
            $("#rshow_" + ele_name).show();
        });
    }
}
function activateDetailViewAutoComplete(ele_obj, obj_pro, ele_name) {
    var par_obj = obj_pro.editoptions.token.params;
    detail_token_pre_populates[ele_name] = par_obj.prePopulate;
    getDetailAutoCompDataValueArr(ele_name);
    detail_token_input_assign = $(ele_obj).tokenInput(obj_pro.editoptions.serviceUrl, {
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
            detail_token_pre_populates[ele_name] = detail_token_input_assign.tokenInput('get');
            getDetailAutoCompDataValueArr(ele_name);
        },
        onDelete: function (item) {
            detail_token_pre_populates[ele_name] = detail_token_input_assign.tokenInput('get');
            getDetailAutoCompDataValueArr(ele_name);
        }
    });
}
function getDetailAutoCompDataValueArr(ele_name) {
    var $data_arr = [];
    for (i in detail_token_pre_populates[ele_name]) {
        $data_arr[i] = detail_token_pre_populates[ele_name][i]['val'];
    }
    $("#" + ele_name).attr("data-value", $data_arr.join(","));
}
function displayDetailViewRatingProperties(v_name, obj_pro) {
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
function appendDetailViewRatingProperties(eleObj, obj_pro) {
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
function makeDetailViewEditableTextArea(v_name, detail_settings_obj) {
    $('#' + v_name).editable({
        showbuttons: true,
        type: 'textarea',
        placeholder: detail_settings_obj[v_name].editoptions.placeholder, name: v_name,
        pk: el_topview_settings.edit_id,
        onblur: 'submit',
        rows: 3,
        validate: function (value) {
            var vid = $(this).attr("id");
            return validateViewInlineEdit(vid, value, detail_settings_obj[vid]);
        },
        url: function (params) {
            saveDetailInlineEdit(params.name, params.value, params.id);
        }
    });
    $('#' + v_name).on('shown', function (e, editable) {
        var ele_name = $(this).attr("id");
        var ele_obj = $(editable.$form).find(".editable-input").find("textarea");
        var obj_pro = detail_settings_obj[ele_name];
        if (obj_pro.editoptions.text_case) {
            $(ele_obj).addClass(obj_pro.editoptions.text_case);
            applyInputTextCase($(editable.$form).find(".editable-input"));
        }
    });

}
function makeDetailViewEditablePassword(v_name, detail_settings_obj) {
    $('#' + v_name).editable({
        showbuttons: true,
        type: 'password',
        placeholder: detail_settings_obj[v_name].editoptions.placeholder,
        name: v_name,
        pk: el_topview_settings.edit_id,
        onblur: 'submit',
        validate: function (value) {
            var vid = $(this).attr("id");
            return validateViewInlineEdit(vid, value, detail_settings_obj[vid])
        },
        url: function (params) {
            saveDetailInlineEdit(params.name, params.value, params.id);
        }
    });
}
function makeDetailViewEditableDropdown(v_name, detail_settings_obj) {
    if (detail_settings_obj[v_name].editoptions && detail_settings_obj[v_name].editoptions.dataUrl) {
        var v_dataUrl = detail_settings_obj[v_name].editoptions.dataUrl;
        $('#' + v_name).editable({
            showbuttons: true,
            type: 'select',
            source: v_dataUrl,
            pk: el_topview_settings.edit_id,
            sourceCache: false,
            validate: function (value) {
                var vid = $(this).attr("id");
                return validateViewInlineEdit(vid, value, detail_settings_obj[vid])
            },
            url: function (params) {
                saveDetailInlineEdit(params.name, params.value, params.id);
            }
        });
        $('#' + v_name).on('shown', function (e, editable) {
            var $that = $(editable.$form).find(".editable-input").find("select");
            $($that).attr('data-placeholder', detail_settings_obj[v_name].editoptions.data_placeholder);
            $($that).find("option").removeAttr("selected");
            var data_val = $(this).attr("data-value")
            $($that).find("option").each(function () {
                if ($(this).text() == data_val) {
                    $(this).prop("selected", true);
                    return false;
                }
            });
            var ele_name = $(this).attr("id");
            var obj_pro_cc = detail_settings_obj[ele_name];
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
                var obj_pro = detail_settings_obj[ele_name];
                if (obj_pro.editoptions.ajaxCall == "ajax-call") {
                    var inlineID = obj_pro.editoptions.rel;
                    var $queryStr = "&mode=" + cus_enc_mode_json['Update'] + "&&unique_name=" + inlineID + "&id=" + el_topview_settings.edit_id;
                    var $ajaxSendURL = el_topview_settings.ajax_data_url + $queryStr;
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
function appendDetailViewUploadifyProperties(ele_obj, obj_pro) {
    if (!obj_pro) {
        return;
    }
    var ele_name = obj_pro.htmlID;
    var ele_val = obj_pro.dbval;
    var ele_label = obj_pro.label;
    var ele_parent = obj_pro.parentattr;
    var ele_events = obj_pro.editoptions;

//    var $fileStr = "<input type='hidden' value='" + ele_val + "' name='temp_" + ele_name + "' id='temp_" + ele_name + "' />";
//    $fileStr += "<input type='file' name='uploadify_" + ele_name + "' id='uploadify_" + ele_name + "' title='" + ele_label + "'/>";
//    var par_obj = $(ele_obj).closest('.editable-container')
//    $(par_obj).wrap('<span ' + ele_parent + ' />');
//    $(ele_obj).after($fileStr);
//    if (ele_events.uploadify) {
//        var upload_data = ele_events.uploadify;
//        var upload_params = upload_data.params;
//        var basic_params = assignEventParams(upload_params);
//        var function_params = {
//            'formData': {
    //                'unique_name': upload_data['unique_name'],
    //                'id': upload_data['id'],
    //                'type': 'uploadify'
//            },
    //            'onSelect': function(file) { //                var joldval = $('#temp_' + ele_name).val();
//                $('#uploadify_' + ele_name).uploadify('settings', 'formData', {
    //                    'oldFile': joldval
//                });
//                $('#uploadify_' + ele_name).uploadify('upload');
//            },
    //            'onUploadSuccess': function(file, data, response) {
    //                if (data) {
    //                    var jparse_data = parseJSONString(data);
    //                    if (jparse_data.success == "0") {
    //                        Project.setMessage(jparse_data.message, 0);
    //                    } else {
    //                        $('#' + ele_name).val(jparse_data.uploadfile);
//                        $('#temp_' + ele_name).val(jparse_data.oldfile);
    //                        displayAdminFormFlyImage(ele_name, upload_data['id'], upload_data, jparse_data);
    //                        var old_data = {
    //                            "old_file": ele_val
//                        };
    //                        saveDetailInlineEdit(ele_name, jparse_data.uploadfile, el_topview_settings.edit_id, old_data);
    //                        setTimeout(function() {
    //                            $('.jqgrid-subview').click();
    //                        }, 200)
    //                    }
//                }
//            }
//        };
    //        var final_params = $.extend({}, basic_params, function_params);
//        $('#uploadify_' + ele_name).uploadify(final_params);
    //    }


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

    var upload_data = ele_events.fileupload;
    /*
     if (upload_data.capture && upload_data.capture == "Yes") {
     var cptscr = getCaptureDetailScript(ele_name, upload_data['unique_name'], upload_data['iModuleAddId']);
     $(ele_obj).before(cptscr);
     detectCaptureCameraAllow(ele_name, upload_data['unique_name'], upload_data['uploader']);
     }
     */
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
                //                    $('#practive_' + _input_name).css('width', '0%');
                //                    $('#progress_' + _input_name).show();
                _form_data['oldFile'] = $('#' + _temp_name).val();
                $(this).fileupload('option', 'formData', _form_data);
                $('#preview_' + _input_name).html(_input_val);
                data.submit();
            }
        }, done: function (e, data) {
            if (data && data.result) {
                var _input_name = $(this).fileupload('option', 'name');
                var _temp_name = $(this).fileupload('option', 'temp');
                var jparse_data = parseJSONString(data.result);
                if (jparse_data.success == '0') {
                    Project.setMessage(jparse_data.message, 0);
                } else {
                    $('#' + _input_name).val(jparse_data.uploadfile);
                    $('#' + _temp_name).val(jparse_data.oldfile);
                    displayAdminFormFlyImage(_input_name, upload_data['id'], upload_data, jparse_data);
                    //                        setTimeout(function() {
                    //                            $('#progress_' + _input_name).hide();
                    //                        }, 1000);
                    var old_data = {
                        "old_file": ele_val
                    };
                    saveDetailInlineEdit(_input_name, jparse_data.uploadfile, el_topview_settings.edit_id, old_data);
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
//related image/file on the fly display
function displayAdminFormFlyImage(hid, id, upload_data, rarr) {
    if (rarr['fileURL']) {
        var del_btn = "<a title='" + js_lang_label.GENERIC_GRID_DELETE + "' style='text-decoration:none;' href='javascript://' onclick='deleteFileTypeDocs(\"" + id + "\",\"" + upload_data.unique_name + "\",\"" + upload_data.delete_file_url + "\",\"" + upload_data.folder + "\",\"" + hid + "\",\"EN\")' id='anc_imgdel_" + hid + "' >";
        del_btn += "<i class='icon16 entypo-icon-close icon-red no-margin'></i>";
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
            var $img_attr = "width='" + rarr['width'] + "' height='" + rarr['height'] + "'";
            $($img_str).attr("id", "anc_imgview_" + hid)
                    .attr("href", rarr['fileURL'])
                    .addClass("fancybox-image").html("<img src='" + rarr['fileURL'] + "' alt='Image' " + $img_attr + "/>");
            $("#img_view_" + hid).html($img_str);
            /*
             $('#anc_imgview_' + hid).qtip({
             content: "<img src='" + rarr['file_url'] + "' alt='Image' />"
             });
             */
        }
        $("#img_del_" + hid).html(del_btn);
        initializeFancyBoxEvents($("#img_view_" + hid));
    }
}
function printErrorMessage(target, source, error) {
    var tar_name = $(target).attr("name");
    if ($.isArray(source)) {
        for (var i = 0; i < source.length; i++) {
            var mat_res = isStringMatched(tar_name, source[i]);
            if (mat_res) {
                if ($.inArray($(target).attr("type"), ["checkbox", "radio"]) != "-1") {
                    var spec_id = $(target).attr('name');
                    if ($(target).attr("type") == "checkbox") {
                        spec_id = spec_id.replace(/\[]/g, '');
                    }
                    spec_id = spec_id.replace(/\[/g, '_').replace(/\]/g, '');
                    $('#' + spec_id + 'Err').html(error);
                } else {
                    $('#' + $(target).attr('id') + 'Err').html(error);
                }
                break;
            }
        }
    } else {
        var mat_res = isStringMatched(tar_name, source);
        if (mat_res) {
            $('#' + $(target).attr('id') + 'Err').html(error);
        }
    }
}
function isStringMatched(tar, src) {
    if (!src || !tar) {
        return false;
    }
    src = src.replace(/\[/g, '\\[').replace(/\]/g, '\\]')
    var src_reg = new RegExp(src);
    var src_mat = tar.match(src_reg);
    if ($.isArray(src_mat) && src_mat.length > 0) {
        return true;
    } else {
        return false;
    }
}
function initFormSaveAsDraft(module, draft) {
    if (draft != "Yes" || !$("#frmaddupdate").length || !$("[data-form-name='" + module + "']").length) {
        stopFormSaveAsDraft();
        return;
    }

    var interval = el_tpl_settings.form_save_draft_interval;
    formSaveAsDraft = setInterval(function () {
        if ($("#frmaddupdate").length) {
            saveFormDraftContents(module);
        } else {
            stopFormSaveAsDraft();
        }
    }, interval);
}
function saveFormDraftContents(module) {
    $.ajax({
        url: admin_url + cus_enc_url_json["general_form_save_draft_action"],
        type: 'POST',
        data: {
            "draft_module": module,
            "form_data": $("#frmaddupdate").serializeArray(),
            "mode": $("#frmaddupdate").find("#mode").val(),
            "id": $("#frmaddupdate").find("#id").val()
        },
        success: function (data) {
            var response = parseJSONString(data);
            if (response && response.draft_id) {
                $("#draft_uniq_id").val(response.draft_id);
            }
        },
        complete: function () {

        }
    });
}
function stopFormSaveAsDraft() {
    try {
        clearInterval(formSaveAsDraft);
    } catch (err) {

    }
}
function initFormFileViewer(items, saved_ext, allowed_ext) {
    var global_ext = 'jpg,jpeg,jpe,png,gif,bmp,ico', local_ext = [];
    if (el_tpl_settings.admin_formats.image_extensions) {
        global_ext = el_tpl_settings.admin_formats.image_extensions;
    }
    global_ext = global_ext.split(",");
    if (saved_ext) {
        local_ext = $.isArray(saved_ext) ? saved_ext : saved_ext.split(",");
    }
    var other_ext = local_ext.filter(function (obj) {
        return global_ext.indexOf(obj) == -1;
    });
    if ($.isArray(other_ext) && other_ext.length > 0) {
        startCustomFileViewer(items);
    } else {
        startCustomImageGallery(items);
    }
}
function startCustomFileViewer(items) {
    createFileViewer();
    FileViewerPlugin.init(items);
    var base_obj = {
        'padding': 0,
        'width': '95%',
        'height': '95%',
        'autoSize': false,
        'helpers': {
            overlay: {
                closeClick: false
            } // prevents closing when clicking OUTSIDE fancybox
        },
        'wrapCSS': 'file-viewer-fancybox'
    };
    var tmpl_obj = getFancyboxTPLParams();
    var final_obj = $.extend({}, base_obj, tmpl_obj);
    $.fancybox.open("#fileViewerBox", final_obj);
}
function startCustomImageGallery(items) {
    var base_obj = {
        'padding': 0,
        'width': '90%',
        'height': '90%',
        'helpers': {
            overlay: {
                closeClick: false
            } // prevents closing when clicking OUTSIDE fancybox
        }
    };
    var tmpl_obj = getFancyboxTPLParams();
    var final_obj = $.extend({}, base_obj, tmpl_obj);
    $.fancybox.open(items, final_obj);
}

function createFileViewer() {
    var div = $('<div/>').attr('id', 'fileViewerBox').addClass('file-viewer-div');
    $('.module-form-container').after(div);

    var header = $('<div/>').addClass('file-viewer-top');
    $('#fileViewerBox').append(header);

    var title = $('<h3/>').attr('id', 'page-title').addClass('file-viewer-title');
    var nav = $('<div/>').addClass('file-viewer-nav');
    $('.file-viewer-top')
            .append(title)
            .append(nav);

    var previous = $('<a/>').attr({'id': 'btn-prev', 'title': 'Previous', 'href': 'javascript:;'})
            .append($('<i/>').addClass('fa fa-arrow-circle-left fa-2x'));

    var next = $('<a/>').attr({'id': 'btn-next', 'title': 'Previous', 'href': 'javascript:;'})
            .append($('<i/>').addClass('fa fa-arrow-circle-right fa-2x'));

    $('.file-viewer-nav')
            .append(previous)
            .append(next);

    var loader1 = $('<div/>').attr('id', 'fileviewer_qLoverlay');
    var loader2 = $('<div/>').attr('id', 'fileviewer_qLbar');
    var iframe = $('<div/>').attr('id', 'iframeFile').addClass('file-viewer-iframe').append($('<iframe/>'));
    $('#fileViewerBox')
            .append(loader1)
            .append(loader2)
            .append(iframe);

}
