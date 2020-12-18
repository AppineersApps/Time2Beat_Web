$(document).keyup(function (e) {
    if (e.keyCode === 27) {
        if ($('#module_navigator').length > 0 && $('#module_navigator').is(':visible')) {
            $('#module_navigator').addClass('hide');
        }
        if($('#shortcut-btn').attr('class') == 'shortcut-btn toggle'){
            $('#shortcut-btn').click();
        }
        if($('#switchBtn').attr('class') == 'switch-btn toggle'){
            $('#switchBtn').click();
        }
    }
});

$(function () {
    var shortcut_keys = [];
    $.each(cus_shortcuts_json, function (key, value) {
        shortcut_keys.push(key);
    });
    hotkeys(shortcut_keys.join(','),
            function (event, handler) {
                event.preventDefault();
                var key = handler.key;
                if (cus_shortcuts_json[key].type == 'url') {
                    window.location.href = admin_url + cus_shortcuts_json[key].value;
                } else if (cus_shortcuts_json[key].type == 'code') {
                    var code = cus_shortcuts_json[key].value;
                    var code_arr = [];
                    code_arr['add_record'] = 'add_list2_top';
//                    code_arr['delete_record'] = 'frmbtn_delete';
                    code_arr['refresh_list'] = 'refresh_list2_top';
                    code_arr['export_list'] = 'export_list2_top';
                    code_arr['advance_search'] = 'search_list2_top';
                    code_arr['qucik_search'] = 'listsearch_list2_top';
                    code_arr['save_search'] = 'savesearch_list2_top';
                    code_arr['search_list'] = 'showsearch_list2_top';
                    code_arr['print_list'] = 'print_list2_top';
                    code_arr['toggle_columns'] = 'columns_list2_top';
                    code_arr['save_record'] = 'frmbtn_update';
                    code_arr['select_all'] = 'cb_list2';
                    code_arr['first_page'] = 'first_pager2';
                    code_arr['last_page'] = 'last_pager2';
                    
                    if (code == 'close_popup') {
                        parent.$.fancybox.close();
                    }
                    if (!isFancyBoxActive()) {
                        if (code == 'spotlight_search') {
                            if ($('#module_navigator').length > 0 && !$('#module_navigator').is(':visible')) {
                                $('#module_navigator').removeClass('hide');
                                $('#navigation_search').val('');
                                $('#navigation_search').focus();
                            }
                        } else if (code == 'sitemap') {
                            loadAdminDashboardPage();
                        } else if (code == 'go_back') {
                            loadLastVisitedURL();
                        } else if (code == 'discard_record') {
                            if ($('#frmbtn_discard').length > 0) {
                                confirmDiscard();
                            };
                        } else if (code in code_arr) {
                            if ($('#' + code_arr[code]).length > 0) {
                                $('#' + code_arr[code]).click();
                            } else if (code == 'save_record') {
                                if ($('#frmbtn_add').length > 0) {
                                    $('#frmbtn_add').click();
                                }
                            }
                        } else if (code == 'delete_record') {
                            if($('#frmbtn_delete').length > 0 && $('.form-delete-rec-cnf').length == 0){
                                $('#frmbtn_delete').click();
                            }
                        } else if (code == 'table_view') {
                            if ($('#listgrid_list2_top').length > 0) {
                                $("a[aria-list-type='list']").click();
                            }
                        } else if (code == 'list_view') {
                            if ($('#listgrid_list2_top').length > 0) {
                                $("a[aria-list-type='view']").click();
                            }
                        } else if (code == 'grid_view') {
                            if ($('#listgrid_list2_top').length > 0) {
                                $("a[aria-list-type='grid']").click();
                            }
                        } else if (code == 'next_record' || code == 'next_page') {
                            if ($('.frm-next-rec').length > 0) {
                                window.location.href = $("a", '.frm-next-rec').attr('href');
                            } else {
                                $('#next_pager2').click();
                            }
                        } else if (code == 'prev_record' || code == 'prev_page') {
                            if ($('.frm-prev-rec').length > 0) {
                                window.location.href = $("a", '.frm-prev-rec').attr('href');
                            } else {
                                $('#prev_pager2').click();
                            }
                        } else if (code == 'shortcuts'){
                            $('#shortcut-btn').click();
                        }
                    } else {
                        if (code in code_arr) {
                            if ($('#' + code_arr[code]).length > 0) {
                                $('#' + code_arr[code]).click();
                            } else if (code == 'save_record') {
                                if ($('#frmbtn_add').length > 0) {
                                    $('#frmbtn_add').click();
                                }
                            }
                        } else if (code == 'delete_record') {
                            if($('#frmbtn_delete').length > 0 && $('.form-delete-rec-cnf').length == 0){
                                $('#frmbtn_delete').click();
                            }
                        } else if (code == 'discard_record') {
                            if ($('#frmbtn_discard').length > 0) {
                                confirmDiscard();
                            }
                        }
                    }
                } else if (cus_shortcuts_json[key].type == 'func') {
                    if (cus_shortcuts_json[key].value != '' && $.isFunction(window[cus_shortcuts_json[key].value])) {
                        window[cus_shortcuts_json[key].value]();
                    }
                }
            });
    // hotkeys start
    hotkeys.filter = function (event) {
        return true;
    }
    //How to add the filter to edit labels. <div contentEditable="true"></div>
    //"contentEditable" Older browsers that do not support drops
    hotkeys.filter = function (event) {
        var tagName = (event.target || event.srcElement).tagName;
        return !(tagName.isContentEditable || tagName == 'INPUT' || tagName == 'SELECT' || tagName == 'TEXTAREA');
    }

    hotkeys.filter = function (event) {
        var tagName = (event.target || event.srcElement).tagName;
        hotkeys.setScope(/^(INPUT|TEXTAREA|SELECT)$/.test(tagName) ? 'input' : 'other');
        return true;
    }

    //spotlight search start 
    $('#navigation_search').on('click', function () {
        if ($('#navigation_search').val() != '') {
            $('.spotlight-search-list').show();
        }
    });
    $.widget("custom.catcomplete", $.ui.autocomplete, {
        _create: function () {
            this._super();
            this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)");
        },
        _renderMenu: function (ul, items) {
            var that = this, currentCategory = "";
            $.each(items, function (index, item) {
                var li;
                if (item.category != currentCategory) {
                    ul.addClass('spotlight-search-list')
                    ul.append("<li class='ui-autocomplete-category shortcut-opt'><span class='down-child icon13 " + item.cat_icon + "'></span>" + item.category + "</li>");
                    currentCategory = item.category;
                }
                li = that._renderItemData(ul, item);
                if (item.category) {
                    li.addClass('shortcut-item');
                    li.attr("aria-label", item.category + " : " + item.label);
                }
            });
        },
        _renderItem: function (ul, item) {
            return $("<li></li>").data("item.autocomplete", item)
                    .append("<a><span class='down-child icon13 " + item.icon + "'></span>" + item.label + "</a>")
                    .appendTo(ul);
        }
    });

    $("#navigation_search").catcomplete({
        minLength: 2,
        source: function (request, response) {
            $('.spotlight-search-loader').removeClass('loader-hide');
            $.ajax({
                url: admin_url + cus_enc_url_json.spotlight_search,
                type: "POST",
                data: {request: request.term},
                success: function (data) {
                    var result = JSON.parse(data);
                    response($.map(result.data, function (item) {
                        return {
                            label: item.label,
                            category: item.type,
                            url: item.url,
                            icon: item.icon,
                            cat_icon: item.cat_icon,
                        }
                    }));
                    $('.spotlight-search-loader').addClass('loader-hide');
                }
            });
        },
        select: function (event, ui) {
            
            if (ui.item.url != 0) {
                if ($('#spotlight-newtab').is(':checked')) {
                    window.open(ui.item.url, '_blank');
                } else {
                    $('#module_navigator').addClass('hide');
                    window.location.href = ui.item.url;
                }
            }
        }
    });
});

function confirmDiscard() {
    if($('#shortcur_discard_popup').length > 0){
        return false;
    }
    var label_elem = '<div id="shortcur_discard_popup" />';
    var label_text = js_lang_label.GENERIC_ARE_YOU_SURE_WANT_TO_DISCARD_THIS;
    var option_params = {
        title: js_lang_label.GENERIC_DISCARD,
        dialogClass: "dialog-confirm-box form-delete-img-cnf",
        buttons: [{
                text: js_lang_label.GENERIC_OK,
                bt_type: 'ok',
                click: function () {
                    if ($('#frmbtn_discard').length > 0) {
                        $('#frmbtn_discard').click();
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