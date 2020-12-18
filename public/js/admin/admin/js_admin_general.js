navigator.getUserMedia = navigator.webkitGetUserMedia || navigator.getUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
window.URL = window.URL || window.webkitURL;
var localStream = null, formSaveAsDraft = null, gridAutoRefresh = null, gridRefreshTime = 0, globalMetaTitle;
var el_general_settings = {
    admin_page_clkele: '',
    page_temp_left: '',
    page_temp_right: '',
    active_tab_index: false,
    having_flash_obj: false,
    grid_subgrid_alias: '',
    grid_main_link_model: '',
    grid_sub_link_model: '',
    dashboard_grid: '',
    mobile_platform: check_user_platform()
}
var pager_row_list = [5, 10, 20, 30, 50, 100, 200, 500];
var searchOpts = ['eq', 'ne', 'lt', 'le', 'gt', 'ge', 'bw', 'bn', 'in', 'ni', 'ew', 'en', 'cn', 'nc', 'nu', 'nn', 'bt', 'nb'];
var numSearchOpts = ['bt', 'nb', 'eq', 'ne', 'lt', 'le', 'gt', 'ge', 'in', 'ni', 'nu', 'nn'];
var strSearchOpts = ['eq', 'ne', 'bw', 'bn', 'ew', 'en', 'cn', 'nc', 'mw', 'in', 'ni', 'nu', 'nn'];
var intSearchOpts = ['in', 'ni', 'nu', 'nn'];
var dateSearchOpts = ['bt', 'nb', 'eq', 'ne', 'lt', 'le', 'gt', 'ge', 'nu', 'nn'];
var chartColours = ['#88bbc8', '#ed7a53', '#9FC569', '#bbdce3', '#9a3b1b', '#5a8022', '#2c7282', '#49BFAE', '#34A8DB', '#428BCA'];
var wcfilters = ['grayscale', 'sepia', 'blur', 'brightness', 'contrast', 'hue-rotate', 'hue-rotate2', 'hue-rotate3', 'saturate', 'invert', ''];
var fancy_params = ['type', 'width', 'height', 'padding', 'margin', "autoSize"];
//basic editor type plugins
var tinymce_editor_plugins_basic = [
    'lists link image charmap print preview anchor',
    'searchreplace code fullscreen',
    'insertdatetime media table wordcount contextmenu paste code'
];
var tinymce_editor_tollbar_basic = "insertfile undo redo | styleselect |  bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image";
//filemanager editor type plugins
var tinymce_editor_plugins_premium = [
    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
    'searchreplace wordcount visualblocks visualchars code fullscreen',
    'insertdatetime media nonbreaking save table contextmenu directionality',
    'emoticons template paste textcolor colorpicker textpattern imagetools responsivefilemanager'
];
var tinymce_editor_tollbar_premium = 'insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media responsivefilemanager | print preview | forecolor backcolor emoticons';
//advanced editor type plugins
var tinymce_editor_plugins = [
    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
    'searchreplace wordcount visualblocks visualchars code fullscreen',
    'insertdatetime media nonbreaking save table contextmenu directionality',
    'emoticons template paste textcolor colorpicker textpattern imagetools'
];
var tinymce_editor_tollbar = 'insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | print preview | forecolor backcolor emoticons';
//editor templates
var tinymce_editor_templates = [{
        title: 'Test template 1',
        content: 'Test 1'
    }, {
        title: 'Test template 2',
        content: 'Test 2'
    }];

if (typeof window.applicationCache == "object") {
    //appcache controls
    var cacheEvent = {
        cacheProgress: 0,
        handleEvent: function (e) {
            switch (e.type) {
                case 'checking':
                    break;
                case 'downloading':
                    showCacheProgress();
                    cacheEvent.cacheProgress = 0;
                    break;
                case 'cached':
                case 'updateready':
                case 'obsolete':
                case 'error':
                    hideCacheProgress();
                    break;
                case 'noupdate':
                    hideCacheProgress();
                    break;
                case 'progress':
                    if (e && e.lengthComputable) {
                        cacheEvent.cacheProgress = Math.round(e.loaded / e.total * 100);
                    } else {
                        cacheEvent.cacheProgress++;
                    }
                    //$('#script_download_input').val(cacheEvent.cacheProgress).trigger('change');
                    $("#script_progress").css("width", cacheEvent.cacheProgress + "%");
                    break;
            }
        }
    };
    // appcache object
    var appCache = window.applicationCache;
    // Fired after the first cache of the manifest.
    appCache.addEventListener('cached', cacheEvent.handleEvent, false);
    // Checking for an update. Always the first event fired in the sequence.
    appCache.addEventListener('checking', cacheEvent.handleEvent, false);
    // An update was found. The browser is fetching resources.
    appCache.addEventListener('downloading', cacheEvent.handleEvent, false);
    // Fired after the first download of the manifest.
    appCache.addEventListener('noupdate', cacheEvent.handleEvent, false);
    // Fired for each resource listed in the manifest as it is being fetched.
    appCache.addEventListener('progress', cacheEvent.handleEvent, false);
    // Fired when the manifest resources have been newly redownloaded.
    appCache.addEventListener('updateready', cacheEvent.handleEvent, false);
    // Fired if the manifest file returns a 404 or 410.
    // This results in the application cache being deleted.
    appCache.addEventListener('obsolete', cacheEvent.handleEvent, false);
    // The manifest returns 404 or 410, the download failed,
    // or the manifest changed while the download was in progress.
    appCache.addEventListener('error', cacheEvent.handleEvent, false);
    // appcache after loading dom
}

window.onload = function () {
    if (!isFancyBoxActive()) {
        if (typeof window.applicationCache == "object") {
            setTimeout(function () {
                var node = document.createElement('iframe');
                node.setAttribute('style', 'display:none;');
                node.setAttribute('id', 'manifest_frame');
                node.setAttribute('src', admin_url + '' + cus_enc_url_json["user_manifest"] + '?_=' + (new Date).getTime());
                document.body.appendChild(node);
                var manifest_frame = document.getElementById('manifest_frame');
                manifest_frame.onload = function () {
                    if ($('#manifest_frame').get(0).contentWindow.logout_ready) {
                        document.location.href = admin_url + "" + cus_enc_url_json["user_sess_expire"];
                        return false;
                    }
                    if ($('#manifest_frame').get(0).contentWindow.cache_status == "Yes") {
                        var node = document.createElement('iframe');
                        node.setAttribute('style', 'display:none;');
                        node.setAttribute('id', 'tbcontent_frame');
                        node.setAttribute('src', admin_url + '' + cus_enc_url_json["user_tbcontent"] + '?_=' + (new Date).getTime());
                        document.body.appendChild(node);
                        var tbcontent_frame = document.getElementById('tbcontent_frame');
                        tbcontent_frame.onload = function () {
                            if ($('#tbcontent_frame').contents().find("#top_panel_info").length) {
                                $("#trtop_template").html($('#tbcontent_frame').contents().find("#top_panel_info").html());
                                $('#tbcontent_frame').contents().find("#top_panel_info").remove();
                                el_tpl_settings.framework_vars = parseJSONString(el_tpl_settings.framework_vars);
                                el_tpl_settings.admin_formats = parseJSONString(el_tpl_settings.admin_formats);
                                el_theme_settings = parseJSONString(el_theme_settings);
                                cus_enc_url_json = parseJSONString(cus_enc_url_json);
                                cus_enc_mode_json = parseJSONString(cus_enc_mode_json);
                                chartColours = el_theme_settings.chart_colors
                            }
                            if ($('#tbcontent_frame').contents().find("#bot_panel_info").length) {
                                $("#trbot_template").html($('#tbcontent_frame').contents().find("#bot_panel_info").html());
                                $('#tbcontent_frame').contents().find("#bot_panel_info").remove();
                            }
                            navigLeftMenuEvents();
                            getResponsiveTopMenu();
                            initializejQueryChosenEvents($("#trtop_template"));
                            if (el_tpl_settings.is_admin_theme_create == '1') {
                                createThemeSettings();
                            } else {
                                removeThemeSettings();
                            }
                            if (el_tpl_settings.is_desk_notify_active == "1" || el_tpl_settings.is_admin_notifications_active == '1') {
                                loadSSEEventWebNotify();
                            }
                        }
                    } else {
                        if (el_tpl_settings.is_desk_notify_active == "1" || el_tpl_settings.is_admin_notifications_active == '1') {
                            loadSSEEventWebNotify();
                        }
                    }
                    chartColours = el_theme_settings.chart_colors;
                    loadHashRequestURLPage(false);
                }
            }, 10);
        } else {
            chartColours = el_theme_settings.chart_colors;
            loadHashRequestURLPage(false);
            if (el_tpl_settings.is_desk_notify_active == "1" || el_tpl_settings.is_admin_notifications_active == '1') {
                loadSSEEventWebNotify();
            }
        }
    }
};

try {
    var tempFlashIE = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
    if (tempFlashIE) {
        el_general_settings.having_flash_obj = true;
    }
} catch (e) {
    if (navigator.mimeTypes ["application/x-shockwave-flash"] != undefined) {
        el_general_settings.having_flash_obj = true;
    }
}
$(document).ready(function () {
    globalMetaTitle = $("title").html();
    initjQueryValidateMethods();
    if (!isFancyBoxActive()) {
        initLeftScrollBar();
        getResponsiveTopMenu();
    }
    $(window).resize(function () {
        if (!isFancyBoxActive()) {
            resizeGridWidth();
            initNiceScrollBar();
        }
    });
    (function ($) {
        $.fn.hasScrollBar = function () {
            if (this && this.get(0)) {
                return this.get(0).scrollHeight > this.height();
            }
        }
    })(jQuery);
    (function ($) {
        $.fn.textWidth = function () {
            var html_org = $(this).html();
            var html_calc = '<span>' + html_org + '</span>';
            $(this).html(html_calc);
            var width = $(this).find('span:first').width();
            $(this).html(html_org);
            return width;
        };
    })(jQuery);
    $.ajaxPrefilter('script', function (options) {
        options.cache = true;
    });
    $(document).bind('ajaxComplete', function (event, request, settings) {
        if (request.getResponseHeader('Cit-auth-requires') === '1') {
            document.location.href = admin_url + "" + cus_enc_url_json["user_sess_expire"];
        }
        if (request.getResponseHeader('Cit-db-error') === '1') {
            getDBErrorNotifyScreen(request.getResponseHeader('Cit-db-efile'));
        }
    });
    $(document).mousemove(function (e) {
        el_general_settings.page_temp_left = e.pageX;
        el_general_settings.page_temp_right = e.pageY;
        if ($('#body_ajaxloading_div')) {
            $('#body_ajaxloading_div').css({
                position: 'absolute',
                zIndex: 10000,
                left: (e.pageX - 42) + 'px',
                top: (e.pageY - 18) + 'px'
            });
        }
    });
    $(document).on("click", "*", function () {
        el_general_settings.admin_page_clkele = $(this);
    });
    $(document).bind('dragenter', function (e) {
        $(".upload-drop-zone").addClass('active').html('<div align="center" class="upload-drop-placeholder">Drop files here</div>');
        $(".upload-src-zone").addClass("drop-active");
    });
    $(document).bind('dragover', function (e) {
        e.preventDefault();
    });
    $(document).bind('drop', function (e) {
        $(".upload-drop-zone").removeClass("active").html('');
        $(".upload-src-zone").removeClass("drop-active");
        e.preventDefault();
    });
    $(window).bind('hashchange', function (e) {
        loadHashRequestURLPage(true);
        e.preventDefault();
    });
    $(document).on("click", ".db-error-click", function (event) {
        $("#db_error_log .box").fadeOut("fast");
        event.preventDefault();
        //maximize  content
        loadDBErrorLogPrint($(this).attr("aria-err-page"));
    });
    $(document).on("click", ".error-minimize-log span", function (event) {
        event.preventDefault();
        //minimize content
        $("#db_error_log .box").fadeOut("slow");
        return false;
    });
    $(document).on("click", ".db-show-hide-log", function (event) {
        $("#ad_navig_log .box").fadeOut("fast");
        event.preventDefault();
        //maximize  content
        loadDBQueryLogPrint();
    });
    $(document).on("click", ".db-minimize-log span", function (event) {
        event.preventDefault();
        //minimize content
        $("#db_query_log .box").fadeOut("slow");
        return false;
    });
    $(document).on("click", ".nv-show-hide-log", function (event) {
        $("#db_query_log .box").fadeOut("fast");
        event.preventDefault();
        //maximize  content
        loadNavigationLogPrint();
    });
    $(document).on("click", ".nv-minimize-log span", function (event) {
        event.preventDefault();
        //minimize content
        $("#ad_navig_log .box").fadeOut("slow");
        return false;
    });
    $(document).on("click", ".qc-show-hide-log", function (event) {
        var clear_cache_query = admin_url + "" + cus_enc_url_json["general_clear_query_cache"];
        if (confirm(js_lang_label.GENERIC_DO_YOU_WANT_TO_CLEAR_CACHE_DATA)) {
            clearLocalStoreCache();
            $.ajax({
                url: clear_cache_query,
                type: 'POST',
                data: {
                    'type': 'cache'
                },
                success: function (response) {
                    var res_arr = parseJSONString(response);
                    var jmgcls = 1;
                    if (res_arr.success == "0") {
                        jmgcls = 0;
                    }
                    Project.setMessage(res_arr.message, jmgcls);
                }
            });
        }
    });
    $(document).on("change", "#logFlushCombo", function () {
        var log_flush_val = $(this).val();
        if (log_flush_val == "All") {
            $("#logFlushPages").hide();
        } else {
            $("#logFlushPages").show();
        }
    });
    $(document).on("change", "#topLangCombo", function () {
        var lang_change_uri = admin_url + "" + cus_enc_url_json["general_language_change"];
        var langVal = $(this).val();
        Project.show_adaxloading_div();
        $.ajax({
            url: lang_change_uri,
            type: 'POST',
            data: {
                'langVal': langVal
            },
            success: function (response) {
                if (response == 1) {
                    document.location.reload();
                }
            }
        });
    });
    $(document).on("change", "#topGroupCombo", function () {
        var group_id = $(this).val();
        Project.show_adaxloading_div();
        $.ajax({
            url: admin_url + "" + cus_enc_url_json["user_switch_group"],
            type: 'POST',
            data: {
                'group_id': group_id
            },
            success: function (response) {
                if (response == 1) {
                    document.location.reload();
                }
            }
        });
    });
    $(document).on("click", "#grid_search_btn", function () {
        toggleLeftSearchPanel($(this))
    });
    $(document).on("click", "#show_full_screen_bottom", function () {
        $("html").requestFullScreen();
        $("#cancel_full_screen_bottom").show();
        $("#show_full_screen_bottom").hide();
    });
    $(document).on("click", "#cancel_full_screen_bottom", function () {
        $.cancelFullScreen();
        $("#cancel_full_screen_bottom").hide();
        $("#show_full_screen_bottom").show();
    });
    $(document).on("click", "#full_screen_mode", function () {
        $("html").requestFullScreen();
        $("#cancel_full_screen").show();
        $("#full_screen_mode").hide();
    });
    $(document).on("click", "#cancel_full_screen", function () {
        $.cancelFullScreen();
        $("#cancel_full_screen").hide();
        $("#full_screen_mode").show();
    });
    $(document).on("click", ".fancybox-image", function (e) {
        e.preventDefault();
    });
    $(document).on("click", ".fancybox-ajax", function (e) {
        if (isFancyBoxActive() && 0) {
            var href_url_arr = $(this).attr("href").split("#");
            loadTargetRequestURLPage(href_url_arr[1]);
        } else {
            var href_url_arr = $(this).attr("href").split("#");
            var params_obj = getHASHToFancyParams(href_url_arr[1]);
            var req_uri = convertHASHToURL(href_url_arr[1]);
            openAjaxURLFancyBox(req_uri, params_obj);
        }
        e.preventDefault();
        return false;
    });
    $(document).on("click", ".fancybox-popup", function (e) {
        if (isFancyBoxActive() && 0) {
            var href_url_arr = $(this).attr("href").split("#");
            loadTargetRequestURLPage(href_url_arr[1]);
        } else {
            var href_url_arr = $(this).attr("href").split("#");
            var params_obj = getHASHToFancyParams(href_url_arr[1]);
            var req_uri = convertHASHToURL(href_url_arr[1])
            openCustomURLFancyBox(req_uri, params_obj);
        }
        e.preventDefault();
        return false;
    });
    $(document).on("click", ".fancybox-hash-iframe", function (e) {
        if (isFancyBoxActive() && 0) {
            var href_url_arr = $(this).attr("href").split("#");
            loadTargetRequestURLPage(href_url_arr[1]);
        } else {
            var href_url_arr = $(this).attr("href").split("#");
            var params_obj = getHASHToFancyParams(href_url_arr[1]);
            var req_uri = convertHASHToURL(href_url_arr[1]);
            openURLFancyBox(req_uri, params_obj);
        }
        e.preventDefault();
        return false;
    });
    $(document).on("click", ".fancybox-restrict-hash", function (e) {
        if (isFancyBoxActive()) {
            var href_url_arr = $(this).attr("href").split("#");
            loadTargetRequestURLPage(href_url_arr[1]);
            e.preventDefault();
            return false;
        }
    });
    $(document).on("click", ".admin-link-logout", function () {
        var admin_logout_req_uri = admin_url + "" + cus_enc_url_json['user_login_logout'] + "?_=" + (new Date().getTime());
        Project.show_adaxloading_div();
        var curhash = window.location.hash;
        curhash = (curhash) ? curhash.toString().substr(1) : '';
        $.ajax({
            url: admin_logout_req_uri,
            type: 'POST',
            data: {hashVal: curhash},
            success: function (response) {
                var auto_res = parseJSONString(response);
                if (auto_res.success == '1') {
                    Project.hide_adaxloading_div();
                    document.location.href = admin_url + "" + cus_enc_url_json["user_login_entry"] + "?_=" + (new Date().getTime());
                }
            }
        });
    });
    $(document).on('click', '.date-append-class', function () {
        $(this).parent().find('.ctrl-append-prepend').datepicker('show');
    });
    $(document).on('click', '.date-time-append-class', function () {
        $(this).parent().find('.ctrl-append-prepend').datetimepicker('show');
    });
    $(document).on('click', '.time-append-class', function () {
        $(this).parent().find('.ctrl-append-prepend').timepicker('show');
    });
    $(document).on('click', '.print-rec-restrict', function () {
        Project.setMessage(js_lang_label.ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33, 2);
    });
});
function showCacheProgress() {
    $("body").addClass("appcache-state");
    //$("#script_download").show();
    //$("#script_download_input").knob();
    $("#script_progress").show();
}
function hideCacheProgress() {
    $("body").removeClass("appcache-state");
    //$("#script_download").hide();
    $("#script_progress").hide();
}
//related jquery validation methods
function initjQueryValidateMethods() {
    //  jquery validator additional methods
    jQuery.validator.addMethod("validate_editor", function (value, element) {
        return check_editor(value, element);
    }, js_lang_label.GENERIC_PLEASE_ENTER_PROPER_DATA);

    // date realted
    jQuery.validator.addMethod("dateEqualTo", function (value, element, params) {
        if (isEmptyValue(value) || isEmptyValue($(params).val())) {
            return true;
        }
        var src = getDatePickerDateString(element, value);
        var tar = getDatePickerDateString(params, $(params).val());
        if (!/Invalid|NaN/.test(new Date(src))) {
            return new Date(src).getTime() == new Date(tar).getTime();
        }
    }, js_lang_label.GENERIC_MUST_BE_EQUAL_TO + ' {0}.');
    jQuery.validator.addMethod("dateGreaterThan", function (value, element, params) {
        if (isEmptyValue(value) || isEmptyValue($(params).val())) {
            return true;
        }
        var src = getDatePickerDateString(element, value);
        var tar = getDatePickerDateString(params, $(params).val());
        if (!/Invalid|NaN/.test(new Date(src))) {
            return new Date(src) > new Date(tar);
        }
    }, js_lang_label.GENERIC_MUST_BE_GREATER_THAN + ' {0}.');
    jQuery.validator.addMethod("dateLessThan", function (value, element, params) {
        if (isEmptyValue(value) || isEmptyValue($(params).val())) {
            return true;
        }
        var src = getDatePickerDateString(element, value);
        var tar = getDatePickerDateString(params, $(params).val());
        if (!/Invalid|NaN/.test(new Date(src))) {
            return new Date(src) < new Date(tar);
        }
    }, js_lang_label.GENERIC_MUST_BE_LESS_THAN + ' {0}.');
    jQuery.validator.addMethod("dateGreaterEqual", function (value, element, params) {
        if (isEmptyValue(value) || isEmptyValue($(params).val())) {
            return true;
        }
        var src = getDatePickerDateString(element, value);
        var tar = getDatePickerDateString(params, $(params).val());
        if (!/Invalid|NaN/.test(new Date(src))) {
            return new Date(src) >= new Date(tar);
        }
    }, js_lang_label.GENERIC_MUST_BE_GREATER_THAN_OR_EQUAL_TO + ' {0}.');

    jQuery.validator.addMethod("dateLessEqual", function (value, element, params) {
        if (isEmptyValue(value) || isEmptyValue($(params).val())) {
            return true;
        }
        var src = getDatePickerDateString(element, value);
        var tar = getDatePickerDateString(params, $(params).val());
        if (!/Invalid|NaN/.test(new Date(src))) {
            return new Date(src) <= new Date(tar);
        }
    }, js_lang_label.GENERIC_MUST_BE_LESS_THAN_OR_EQUAL_TO + ' {0}.');

    // numbers realted
    jQuery.validator.addMethod("numEqualTo", function (value, element, params) {
        if (isEmptyValue(value) || isEmptyValue($(params).val())) {
            return true;
        }
        return isNaN(value) && isNaN($(params).val()) || (Number(value) == Number($(params).val()));
    }, js_lang_label.GENERIC_MUST_BE_EQUAL_TO + ' {0}.');

    jQuery.validator.addMethod("numGreaterThan", function (value, element, params) {
        if (isEmptyValue(value) || isEmptyValue($(params).val())) {
            return true;
        }
        return isNaN(value) && isNaN($(params).val()) || (Number(value) > Number($(params).val()));
    }, js_lang_label.GENERIC_MUST_BE_GREATER_THAN + ' {0}.');

    jQuery.validator.addMethod("numLessThan", function (value, element, params) {
        if (isEmptyValue(value) || isEmptyValue($(params).val())) {
            return true;
        }
        return isNaN(value) && isNaN($(params).val()) || (Number(value) < Number($(params).val()));
    }, js_lang_label.GENERIC_MUST_BE_LESS_THAN + ' {0}.');

    jQuery.validator.addMethod("numLessEqual", function (value, element, params) {
        if (isEmptyValue(value) || isEmptyValue($(params).val())) {
            return true;
        }
        return isNaN(value) && isNaN($(params).val()) || (Number(value) <= Number($(params).val()));
    }, js_lang_label.GENERIC_MUST_BE_LESS_THAN_OR_EQUAL_TO + ' {0}.');

    jQuery.validator.addMethod("numGreaterEqual", function (value, element, params) {
        if (isEmptyValue(value) || isEmptyValue($(params).val())) {
            return true;
        }
        return isNaN(value) && isNaN($(params).val()) || (Number(value) >= Number($(params).val()));
    }, js_lang_label.GENERIC_MUST_BE_GREATER_THAN_OR_EQUAL_TO + ' {0}.');
}
function getDatePickerDateString(element, value) {
    var fmt, tmt, act_date;
    if ($(element).data("datepicker")) {
        fmt = $(element).datepicker("option", "dateFormat");
    } else if ($(element).attr("aria-date-format")) {
        fmt = $(element).attr("aria-date-format");
    }
    if (fmt) {
        if ($(element).attr("aria-format-type") == "datetime") {
            if ($(element).data("datepicker")) {
                tmt = $(element).datepicker("option", "timeFormat");
            } else if ($(element).attr("aria-time-format")) {
                tmt = $(element).attr("aria-time-format");
            }
            if (tmt) {
                act_date = $.datepicker.parseDateTime(fmt, tmt, value, {}, {timeFormat: tmt});
            } else {
                act_date = $.datepicker.parseDate(fmt, value);
            }
        } else {
            act_date = $.datepicker.parseDate(fmt, value);
        }
    } else {
        act_date = value;
    }
    return act_date;
}
function initializeTopHeading() {
    if ($('#left_search_panel').length) {
        var $head_width = $(window).width() - 220;
        $('.heading').width($head_width);
        $('.heading').css('left', '220px');
    } else {
        var $head_width = $(window).width();
        $('.heading').width($head_width);
        $('.heading').css('left', '0px');
    }
}
function FancyBoxcloseButton(){
    if ( $('#popup-left-block').length > 0 ){
        parent.$('.fancybox-item').removeClass('fancybox-close-1').addClass('fancybox-close-2');
    }
}
//related to fancy-box calling
function openCustomURLFancyBox(req_uri, write_obj) {
    var base_obj = {
        href: req_uri + "&iframe=true",
        type: 'iframe',
        autoScale: false,
        //autoSize: false,
        openEffect: 'elastic',
        closeEffect: 'elastic',
        width: '75%',
        height: '75%',
        padding: 0,
        helpers: {
            overlay: {
                closeClick: false
            } // prevents closing when clicking OUTSIDE fancybox
        },
        afterShow: function () {
            if ($("iframe[id^='fancybox-frame']")[0] && $("iframe[id^='fancybox-frame']")[0].contentWindow) {
                setTimeout(function () {
                    $("iframe[id^='fancybox-frame']")[0].contentWindow.initializeFancyAjaxEvents();
                }, 250);
                $("iframe[id^='fancybox-frame']")[0].contentWindow.FancyBoxcloseButton();
            }
        }
    }
    var tmpl_obj = getFancyboxTPLParams();
    write_obj = ($.isPlainObject(write_obj)) ? write_obj : {};
    var final_obj = $.extend({}, base_obj, tmpl_obj, write_obj);
    $.fancybox.open(final_obj);
    return false;
}
function openURLFancyBox(req_uri, write_obj) {
    var base_obj = {
        href: req_uri + "&iframe=true",
        type: 'iframe',
        autoScale: false,
        //autoSize: false,
        openEffect: 'elastic',
        closeEffect: 'elastic',
        width: '75%',
        height: '75%',
        padding: 0,
        helpers: {
            overlay: {
                closeClick: false
            } // prevents closing when clicking OUTSIDE fancybox
        },
        afterShow: function () {
            if ($("iframe[id^='fancybox-frame']")[0] && $("iframe[id^='fancybox-frame']")[0].contentWindow) {
                setTimeout(function () {
                    $("iframe[id^='fancybox-frame']")[0].contentWindow.initializeFancyAjaxEvents();
                }, 250);
                $("iframe[id^='fancybox-frame']")[0].contentWindow.FancyBoxcloseButton();
            }
        }
    }
    var tmpl_obj = getFancyboxTPLParams();
    write_obj = ($.isPlainObject(write_obj)) ? write_obj : {};
    var final_obj = $.extend({}, base_obj, tmpl_obj, write_obj);
    $.fancybox.open(final_obj);
    return false;
}
function openAjaxURLFancyBox(req_uri, write_obj) {
    var base_obj = {
        href: req_uri + "&iframe=true",
        type: 'ajax',
        autoScale: false,
        //autoSize: false,
        openEffect: 'elastic',
        closeEffect: 'elastic',
        width: '75%',
        height: '75%',
        padding: 0,
        helpers: {
            overlay: {
                closeClick: false
            } // prevents closing when clicking OUTSIDE fancybox
        },
        afterShow: function () {
            if ($("iframe[id^='fancybox-frame']")[0] && $("iframe[id^='fancybox-frame']")[0].contentWindow) {
                $("iframe[id^='fancybox-frame']")[0].contentWindow.FancyBoxcloseButton();
            }
        }
    }
    var tmpl_obj = getFancyboxTPLParams();
    write_obj = ($.isPlainObject(write_obj)) ? write_obj : {};
    var final_obj = $.extend({}, base_obj, tmpl_obj, write_obj);
    $.fancybox.open(final_obj);
    return false;
}
function allowCloseFancyBox() {
    if (el_tpl_settings.page_iframe == "true" && el_tpl_settings.close_iframe == "true") {
        return true;
    } else {
        return false;
    }
}
function isFancyBoxActive() {
    if (el_tpl_settings.page_iframe == "true") {
        return true;
    } else {
        return false;
    }
}
function restrictFancyBoxClass() {
    if (el_tpl_settings.page_iframe == "true") {
        return "fancybox-restrict-hash";
    } else {
        return "";
    }
}
//related to messages
function closeMessage() {
    $('#var_msg_cnt').fadeOut('slow');
    return false;
}
function setMessage(msgText, msgClass, timeOut) {
    var timer = 5000, cnt_class, close_class;
    if (msgClass == 0) {
        cnt_class = "alert-error";
        close_class = 'error';
    } else {
        cnt_class = "alert-success";
        close_class = 'success';
    }
    $("#closebtn_errorbox").removeClass("success").removeClass("error").addClass(close_class);
    $('#err_msg_cnt').html(msgText).removeClass("alert-success").removeClass("alert-error").addClass(cnt_class);
    if (!isNaN(parseInt(timeOut))) {
        setTimeout(function () {
            $('#var_msg_cnt').fadeIn('slow');
            setTimeout("closeMessage()", 5000);
        }, timeOut);
    } else {
        $('#var_msg_cnt').fadeIn('slow');
        setTimeout("closeMessage()", timer);
    }
}
//related to left search panel
function toggleLeftSearchPanel(eleObj) {
    $this = $(eleObj);
    //left sidbar clicked
    if ($(eleObj).hasClass('left-hide')) {
        $("#left_search_panel").show('slide', {
            direction: 'left'
        }, 500);
        if ($('#collapse_btn').length == 0 || $('#collapse_btn').is(':hidden')) {
            var pjson = animateHeaderContent(true, false, false, false, true);
        } else if ($('#sidebar').hasClass('semi-collapse-menu')) {
            var pjson = animateHeaderContent(false, true, false, false, true);
        } else if ($('#collapse_btn').hasClass('hide')) {
            var pjson = animateHeaderContent(false, false, true, false, true);
        } else {
            var pjson = animateHeaderContent(false, false, false, true, true);
        }
        $($this).animate({
            top: pjson.satop,
            left: pjson.salef
        }, 500, '', function () {
            // Animation complete.
            $($this).removeClass('left-hide').addClass("left-show");
            resizeGridWidth();
            initNiceScrollBar();
            adjustMainGridColumnWidth();
        });
        $("#top_heading_fix > h3").animate({
            'paddingLeft': pjson.fhpad + 'px'
        }, 500);

        $this.children('a').attr('title', js_lang_label.GENERIC_HIDE_LEFT_SEARCH_PANEL);
        $this.children('a').find("span").attr('class', 'icomoon-icon-arrow-left-7');
    } else {
        $("#left_search_panel").hide('slide', {
            direction: 'left'
        }, 500);
        //hide sidebar
        if ($('#collapse_btn').length == 0 || $('#collapse_btn').is(':hidden')) {
            var pjson = animateHeaderContent(true, false, false, false, false);
        } else if ($('#sidebar').hasClass('semi-collapse-menu')) {
            var pjson = animateHeaderContent(false, true, false, false, false);
        } else if ($('#collapse_btn').hasClass('hide')) {
            var pjson = animateHeaderContent(false, false, true, false, false);
        } else {
            var pjson = animateHeaderContent(false, false, false, true, false);
        }
        $($this).animate({
            top: pjson.satop,
            left: pjson.salef
        }, 500, '', function () {
            // Animation complete.
            $($this).addClass('left-hide').removeClass("left-show");
            resizeGridWidth();
            adjustMainGridColumnWidth();
        });
        $("#top_heading_fix > h3").animate({
            'paddingLeft': pjson.fhpad + 'px'
        }, 500);
        $this.children('a').attr('title', js_lang_label.GENERIC_SHOW_LEFT_SEARCH_PANEL);
        $this.children('a').find("span").attr('class', 'icomoon-icon-arrow-right-7');
        hideNiceScrollBar();
    }

}
function getDBErrorNotifyScreen(page) {
    if (("Notification" in window)) {//!$.browser.mozilla
        getDBErrorWebNotify(page)
    } else {
        getDBErrorPinNotify(page);
    }
}
function getDBErrorPinNotify(page) {
    $.pnotify({
        type: "error",
        title: js_lang_label.GENERIC_DATABASE_ERRORS_OCCURRED,
        text: '<a href="javascript://" class="db-error-click" aria-err-page="' + page + '" >' + js_lang_label.GENERIC_CLICK_HERE_TO_VIEW + '</a>',
        icon: 'picon icon24 typ-icon-cancel white',
        opacity: 0.95,
        delay: 30000,
        history: false,
        sticker: false,
        animation: 'show'
    });
}
function getDBErrorWebNotify(page) {
    if (Notification.permission !== "granted") {
        Notification.requestPermission(function (permission) {
            // If the user is okay, let's create a notification
            if (permission === "granted") {
                getDBErrorWebNotify(page);
            } else {
                getDBErrorPinNotify(page);
                return;
            }
        });
    }
    var db_error_notify = new Notification(js_lang_label.GENERIC_DATABASE_ERRORS_OCCURRED, {
        body: js_lang_label.GENERIC_CLICK_HERE_TO_VIEW,
        icon: admin_image_url + "blocked-red.png"
    });
    db_error_notify.onclick = function () {
        $("#db_error_log .box").fadeOut("fast");
        //maximize  content
        loadDBErrorLogPrint(page);
    };
}
//related to notification script
function getDesktopNotifyScreen(msg_obj) {
    if (("Notification" in window)) {//!$.browser.mozilla
        getDesktopWebNotify(msg_obj);
    } else {
        getDesktopPinNotify(msg_obj);
    }
}
function getDesktopPinNotify(msg_obj) {
    var notify_list = $(window).data("pnotify"), cnt = 0;
    for (var i in notify_list) {
        if (!notify_list[i].is(":hidden")) {
            cnt++;
        }
    }
    var subject = msg_obj.subject;
    if (msg_obj.link != "") {
        subject = '<a class="notification-link" href="' + msg_obj.link + '" target="_blank">' + msg_obj.subject + '</a>';
    }
    $.pnotify({
        type: msg_obj.type,
        title: subject,
        text: msg_obj.message,
        icon: 'picon icon16 iconic-icon-check-alt white',
        opacity: 0.95,
        history: true,
        shown: (cnt > 3) ? false : true,
        sticker: false,
        animation: 'show'
    });
}
function getDesktopWebNotify(msg_obj) {
    if (Notification.permission !== "granted") {
        Notification.requestPermission(function (permission) {
            // If the user is okay, let's create a notification
            if (permission === "granted") {
                getDesktopWebNotify(msg_obj);
            } else {
                getDesktopPinNotify(msg_obj);
                return;
            }
        });
    }
    var desktop_notify = new Notification(msg_obj.subject, {
        body: msg_obj.message,
        icon: admin_image_url + "desktop-notify.png"
    });
    if (msg_obj.link != "") {
        desktop_notify.onclick = function () {
            window.open(msg_obj.link);
        };
    }
}
function loadSSEEventWebNotify() {
    setTimeout(function () {
        var notify_obj = new EventSource(admin_url + "" + cus_enc_url_json["user_notify_events"] + "?");
        notify_obj.onmessage = function (event) {
            if (event.data != "") {
                var event_res = parseJSONString(event.data);
                if (event_res.success == '0') {
                    notify_obj.close();
                } else {
                    if (event_res.content && event_res.content.length > 0) {
                        var cnt_arr = event_res.content;
                        for (var i in cnt_arr) {
                            getDesktopNotifyScreen(cnt_arr[i]);
                        }
                    }
                    if (event_res.notifications && event_res.notifications.notify) {
                        var cnt_arr = event_res.notifications.notify;
                        for (var i in cnt_arr) {
                            getDesktopNotifyScreen(cnt_arr[i]);
                        }
                        
                        if($('#notification-menu-item').length && $('.top-notification-heading').length){
                            var notification = '';
                            $.each(event_res.notifications.data, function (key, value) {
                                var message = '';
                                if(value.is_read == "Yes"){
                                    message = '<span class="message"><strong>'+value.message+'</strong></span>';
                                }else{
                                    message = '<span class="message">'+value.message+'</span>';
                                }
                                notification += '<li class="top-notification-content">\n\
<a hijacked="yes" href="'+value.url+'" class="fancybox-popup view-notifications">\n\
    <span class="'+value.icon+'"></span>' + message + '<span class="time">'+value.time+'</span></a></li>';
                            });
                            
                            $.each(event_res.notifications.desktop, function (key, value) {
                                notification += '<li class="top-notification-content top-notification-desktop">\n\
<a hijacked="yes" href="'+value.url+'" class="fancybox-popup view-notifications">\n\
    <span class="'+value.icon+'"></span><span class="message">' + value.message + '</span><span class="time">'+value.time+'</span></a></li>';
                            });
                            
                            $('.top-notification-badge').html(event_res.notifications.count);
                            $('.no-not-content').remove();
                            $('.top-notification-heading').after(notification);
                            $('.top-notification-content:gt(10)').remove();
                            $('.top-notification-desktop:gt(10)').remove();
                            
                        }
                    }
                }
            }
        };
    }, 500);
}
function loadLastVisitedURL() {
    window.history.back();
}
function loadAdminDashboardPage() {
    window.location.hash = cus_enc_url_json['dashboard_sitemap'];
}
function loadAdminSiteMapPage() {
    window.location.hash = cus_enc_url_json['dashboard_sitemap'];
}
function callAdminSessionExpired() {
    document.location.href = admin_url + cus_enc_url_json["user_login_entry"];
}
function loadDBErrorLogPrint(page) {
    var log_req_uri = admin_url + cus_enc_url_json["general_error_log"] + "?page=" + page;
    $.ajax({
        url: log_req_uri,
        type: 'POST',
        data: {},
        success: function (response) {
            $("#db_error_log").html(response);
            var tot_ht = $(window).innerHeight();
            var dbtop = tot_ht - 150;
            $("#db_error_log").css({
                "top": dbtop + "px"
            }).fadeIn(250);
            $("#db_error_log .box").fadeIn(250);
            setTimeout(function () {
                var cnt_ht = $("#db_error_log div.content").height();
                if (cnt_ht >= 250) {
                    dbtop = tot_ht - 325;
                    $("#db_error_log div.content").animate({
                        "height": "275px"
                    }, 100);
                    $("#db_error_log").animate({
                        "top": dbtop + "px"
                    }, 100);
                } else {
                    dbtop -= 150;
                    dbtop += (250 - cnt_ht);
                    $("#db_error_log").animate({
                        "top": dbtop + "px"
                    }, 100);
                }
            }, 251);
            return false;
        }
    });
}
function loadDBQueryLogPrint() {
    $("#db_query_log").hide();
    var log_req_uri = admin_url + cus_enc_url_json["general_query_log"];
    $.ajax({
        url: log_req_uri,
        type: 'POST',
        data: {},
        success: function (response) {
            $("#db_query_log").html(response);
            var tot_ht = $(window).innerHeight();
            var dbtop = tot_ht - 150;
            $("#db_query_log").css({
                "top": dbtop + "px"
            }).fadeIn(250);
            $("#db_query_log .box").fadeIn(250);
            setTimeout(function () {
                var cnt_ht = $("#db_query_log div.content").height();
                if (cnt_ht >= 250) {
                    dbtop = tot_ht - 325;
                    $("#db_query_log div.content").animate({
                        "height": "275px"
                    }, 100);
                    $("#db_query_log").animate({
                        "top": dbtop + "px"
                    }, 100);
                } else {
                    dbtop -= 150;
                    dbtop += (250 - cnt_ht);
                    $("#db_query_log").animate({
                        "top": dbtop + "px"
                    }, 100);
                }
                initQueryLogPaging();
            }, 251);
            return false;
        }
    });
}
function loadNavigationLogPrint() {
    var nvrange = $("#navigationCombo").val();
    var nvaction = $("#actionCombo").val();
    var fluser = $("#userCombo").val();
    if (fluser != "undefined" && fluser != "") {
        user_id = fluser;
    } else {
        user_id = "";
    }
    var log_req_uri = admin_url + cus_enc_url_json["general_navigation_index"] + "?range=" + nvrange + "&action=" + nvaction + "&user_id=" + user_id;
    $.ajax({
        url: log_req_uri,
        type: 'POST',
        data: {},
        success: function (response) {
            $("#ad_navig_log").html(response);
            var tot_ht = $(window).innerHeight();
            var dbtop = tot_ht - 325;
            $("#ad_navig_log").css("top", dbtop + "px");
            $("#ad_navig_log .box").fadeIn(250);
            setTimeout(function () {
                $("#navigationCombo").chosen();
                $("#actionCombo").chosen();
                $("#flushCombo").chosen();
                $("#userCombo").chosen();
            }, 251);
            return false;
        }
    });
}
function loadFlushLogPrint() {
    if (confirm(js_lang_label.GENERIC_ARE_YOU_SURE_TO_FLUSH_THE_LOGS)) {
        var nvrange = $("#navigationCombo").val();
        var flrange = $("#flushCombo").val();
        var fluser = $("#userCombo").val();
        if (fluser != "undefined" && fluser != "") {
            user_id = fluser;
        } else {
            user_id = "";
        }
        var log_req_uri = admin_url + cus_enc_url_json["general_navigation_flush"] + "?type=flush&flush=" + flrange + "&range=" + nvrange + "&user_id=" + user_id;
        $.ajax({
            url: log_req_uri,
            type: 'POST',
            data: {},
            success: function (response) {
                $("#ad_navig_log").html(response);
                var tot_ht = $(window).innerHeight();
                var dbtop = tot_ht - 325;
                $("#ad_navig_log").css("top", dbtop + "px");
                $("#ad_navig_log .box").fadeIn(250);
                setTimeout(function () {
                    $("#navigationCombo").chosen();
                    $("#flushCombo").chosen();
                    $("#userCombo").chosen();
                    $("#actionCombo").chosen();
                }, 251);
                return false;
            }
        });
    }
}
function initQueryLogPaging() {
    if (!$("#query_log_paging").length) {
        return false;
    }
    $("#query_log_paging").paginate({
        count: $("#query_log_paging_count").val(),
        start: 1,
        display: 6,
        border: false,
        text_color: '#888',
        background_color: '#EEE',
        text_hover_color: 'black',
        background_hover_color: '#CFCFCF',
        onChange: function (page) {
            $(".query-log-loader").show();
            var log_req_uri = admin_url + cus_enc_url_json["general_query_log_page"];
            $.ajax({
                url: log_req_uri,
                type: 'POST',
                data: {
                    "type": "paging",
                    "page": page
                },
                success: function (response) {
                    $("#query_log_block").html(response);
                    $(".query-log-loader").hide();
                    return false;
                }
            });
        }
    });
}
function logFlushLogPages() {
    var clear_query_log = admin_url + cus_enc_url_json["general_clear_query_log"];
    $.ajax({
        url: clear_query_log,
        type: 'POST',
        data: {
            'type': 'querylog',
            'flush_type': $("#logFlushCombo").val(),
            'flush_page': $("#logFlushPages").val()
        },
        success: function (response) {
            var res_arr = parseJSONString(response);
            var jmgcls = 1;
            if (res_arr.success == "0") {
                jmgcls = 0;
            }
            Project.setMessage(res_arr.message, jmgcls);
            loadDBQueryLogPrint();
        }
    });
}
function showAdminAjaxRequest(formData, jqForm, options) {
    Project.show_adaxloading_div();
}
function appendPopupAddedRecord(popup_arr) {
    if (!popup_arr || !popup_arr.type) {
        return false;
    }
    var frm_field_id = popup_arr.html_id;
    switch (popup_arr.type) {
        case 'dropdown':
            var opt_str = "<option value='" + popup_arr.id + "'>" + popup_arr.val + "</option>";
            parent.$("#" + frm_field_id).append(opt_str);
            if (parent.$("#" + frm_field_id).attr("multiple")) {
                var selarr = parent.$("#" + frm_field_id).val();
                if (!$.isArray(selarr)) {
                    selarr = [];
                }
                selarr.push(popup_arr.id);
                parent.$("#" + frm_field_id).val(selarr);
            } else {
                parent.$("#" + frm_field_id).val(popup_arr.id);

            }
            parent.$("#" + frm_field_id).trigger("chosen:updated").trigger("change");
            break;
        case 'multi_select_dropdown':
            var opt_str = "<option value='" + popup_arr.id + "'>" + popup_arr.val + "</option>";
            parent.$("#" + frm_field_id).append(opt_str);
            var selarr = parent.$("#" + frm_field_id).val();
            if (!$.isArray(selarr)) {
                selarr = [];
            }
            selarr.push(popup_arr.id);
            parent.$("#" + frm_field_id).val(selarr);
            parent.$("#" + frm_field_id).trigger("chosen:updated").trigger("change");
            break;
        case 'autocomplete':
            if (popup_arr.is_multiple != "Yes") {
                parent.$("#" + frm_field_id).tokenInput("clear");
            }
            if (!$.isPlainObject(popup_arr)) {
                popup_arr = {};
            }
            parent.$("#" + frm_field_id).tokenInput("add", popup_arr).trigger("change");
            break;
    }
}
function getAdminFormValidate() {
    var retVal = true;
    if (el_form_settings.jajax_submit_func != "" && $.isFunction(window[el_form_settings.jajax_submit_func])) {
        retVal = window[el_form_settings.jajax_submit_func]();
    }
    if (retVal) {
        $("._upload_req_file").remove();
        var options = {
            url: el_form_settings.jajax_action_url,
            beforeSubmit: showAdminAjaxRequest,
            success: function (respText, statText, xhr, $form) {
                var resArr = parseJSONString(respText);
                responseAjaxDataSubmission(resArr);
                if (resArr.success == "0") {
                    if (el_form_settings.jajax_submit_back != "" && $.isFunction(window[el_form_settings.jajax_submit_back])) {
                        window[el_form_settings.jajax_submit_back](resArr);
                    }
                    return false;
                } else {
                    if (allowCloseFancyBox()) {
                        if (el_form_settings.jajax_submit_back != "" && $.isFunction(window[el_form_settings.jajax_submit_back])) {
                            window[el_form_settings.jajax_submit_back](resArr);
                        }
                        if (resArr.popup_data) {
                            appendPopupAddedRecord(resArr.popup_data);
                        } else if (resArr.load_grid) {
                            if (parent.$("#" + resArr.load_grid).length) {
                                var sort_mode = (resArr.sort_mode) ? resArr.sort_mode : 1;
                                parent.reloadListGrid(resArr.load_grid, null, sort_mode);
                            }
                        } else if (resArr.rmPopup == "true") {
                            parent.appendChildModuleContent(resArr);
                        }
                        if (resArr.load_form == "true" && resArr.load_url != "") {
                            $("body").addClass("ajaxstate");
                            window.location.href = resArr.load_url;
                        } else {
                            parent.Project.setMessage(resArr.message, 1, 200);
                            if (resArr.red_hash) {
                                parent.loadAdminAddUpdateControl(resArr);
                            }
                            parent.$.fancybox.close();
                        }
                    } else {
                        if (el_form_settings.jajax_submit_back != "" && $.isFunction(window[el_form_settings.jajax_submit_back])) {
                            window[el_form_settings.jajax_submit_back](resArr);
                        }
                        loadAdminAddUpdateControl(resArr);
                    }
                }
            }
        };
        $('#frmaddupdate').ajaxSubmit(options);
    }
    return false;
}
function getAdminTabLevelFormValidate(col_row) {
    var retVal = true;
    var exp_arr = col_row.split("_");
    var col = exp_arr[0], row = exp_arr[1];
    if (el_form_settings.jajax_submit_func != "" && $.isFunction(window[el_form_settings.jajax_submit_func])) {
        retVal = window[el_form_settings.jajax_submit_func]();
    }
    if (retVal) {
        $("._upload_req_file").remove();
        var $currObj = $("[id^='tabheading_" + col + "_" + row + "'].active");
        var curr_height_tab = $($($currObj)).outerHeight();
        $("#tabcontent_" + col + "_" + row).animate({
            "height": curr_height_tab + "px"
        }, 500);
        var js_curr_tab = $($currObj).find("input[name='load_tab']").val();
        var blk = $("#tab_id_" + col + "_" + row).val();
        var options = {
            url: el_form_settings.jajax_action_url,
            beforeSubmit: showAdminAjaxRequest,
            success: function (respText, statText, xhr, $form) {
                var resArr = parseJSONString(respText);
                responseAjaxDataSubmission(resArr);
                if (resArr.success == "0") {
                    return false;
                } else {
                    if (resArr.success == "3" || resArr.success == "4") {
                        if (isRedirectEqualHash(resArr.red_hash)) {
                            window.location.hash = resArr.red_hash;
                            window.location.reload();
                        } else {
                            window.location.hash = resArr.red_hash;
                        }
                    } else if (resArr.success == "5") {
                        window.location.href = resArr.red_hash;
                    } else {
                        var sendArr = [];
                        sendArr.push({
                            "curr_tab": js_curr_tab,
                            "col": col,
                            "row": row,
                            "blk": blk
                        });
                        getActivateAdminTabContent(sendArr);
                    }
                }
            }
        };
        $("#frmaddupdate_" + col + "_" + row).ajaxSubmit(options);
    }
    return true;
}
function responseAjaxDataSubmission(resArr) {
    Project.hide_adaxloading_div();
    Project.setMessage(resArr.message, resArr.success, 200);
}
function loadAdminAddUpdateControl(ctrlArr) {
    if (ctrlArr.success == "3" || ctrlArr.success == "4") {
        if (isRedirectEqualHash(ctrlArr.red_hash)) {
            window.location.hash = ctrlArr.red_hash;
            window.location.reload();
        } else {
            window.location.hash = ctrlArr.red_hash;
        }
        return false;
    } else if (ctrlArr.success == "5") {
        window.location.href = ctrlArr.red_hash;
        return false;
    }
    switch (ctrlArr.red_type) {
        case 'List':
            loadAdminModuleListing(ctrlArr.mod_enc_url.index, ctrlArr.extra_hstr);
            break;
        case 'Prev':
            loadAdminModuleAddUpdate(ctrlArr.mod_enc_url.add, ctrlArr.red_mode, ctrlArr.red_id, ctrlArr.extra_hstr);
            break;
        case 'Next':
            loadAdminModuleAddUpdate(ctrlArr.mod_enc_url.add, ctrlArr.red_mode, ctrlArr.red_id, ctrlArr.extra_hstr);
            break;
        default:
            loadAdminModuleAddUpdate(ctrlArr.mod_enc_url.add, ctrlArr.red_mode, ctrlArr.red_id, ctrlArr.extra_hstr);
            break;
    }
}
function getAnimateNextDIV() {
    if (el_tpl_settings.container_div == "content") {
        return "content_slide";
    } else {
        return "content";
    }
}
function loadAdminModuleListing(module_url, extra_hstr) {
    if (allowCloseFancyBox()) {
        parent.$.fancybox.close();
    } else {
        var $load_url = module_url;
        if (extra_hstr) {
            $load_url += extra_hstr;
        }
        window.location.hash = $load_url;
    }
}
function loadAdminModuleAddUpdate(module_url, mode, id, extra_hstr) {
    var $load_url = module_url;
    if (mode == "Add") {
        //        var hash_url = window.location.hash
        //        if (hash_url) {
        //            var hash_arr = hash_url.split("|");
        //            if ($.isArray(hash_arr) && $.inArray("id", hash_arr) != "-1") {
        //                mode_url = "";
        //            } else {
        //                mode_url = "|id|";
        //            }
        //        }
        mode_url = "|mode|" + cus_enc_mode_json['Add'];
    } else {
        mode_url = "|mode|" + cus_enc_mode_json['Update'] + "|id|" + id;
    }
    $final_url = $load_url + "" + mode_url
    if (extra_hstr) {
        $final_url += extra_hstr;
    }
    window.location.hash = $final_url;
}
function loadAdminModuleAddSwitchPage(module_url, id, extra_hstr) {
    var $load_url = module_url;
    $final_url = $load_url + "|mode|" + cus_enc_mode_json['Update'] + "|id|" + id;
    if (extra_hstr) {
        $final_url += extra_hstr;
    }
    window.location.hash = $final_url;
}
function loadAdminModuleListingSwitch(module_url, par_id, extra_hstr) {
    extra_hstr = changeSpecificHASHValue(extra_hstr, "parID", par_id);
    window.location.hash = module_url + "" + extra_hstr;
}
function loadHashRequestURLPage(pl) {
    var js_curr_hash = window.location.hash;
    if (window.location.hash == "") {
        if (Project.modules.ajaxNavigate.findHASHURL()) {
            loadTargetRequestURLPage(js_curr_hash, pl);
        }
    } else {
        loadTargetRequestURLPage(js_curr_hash, pl);
    }
    return false;
}
function loadTargetRequestURLPage(req_hash, pl) {
    var ajax_url = convertHASHToURL(req_hash);
    callBeforeAjaxCalling();
    var hash_val = (req_hash) ? req_hash.split('|').slice(1).join('|') : '';
    $.ajax({
        url: ajax_url,
        cache: false,
        data: {
            'hashValue': ((hash_val) ? hash_val.toString() : ''),
            'newRequest': "true"
        },
        success: function (data) {
            callAfterAjaxCalling();
            hideNiceScrollBar();
            applyAnimationLogic(data, pl);
        },
        error: function (xhr, txt) {
            var result = appendErrorPage(xhr.status, ajax_url);
            applyAnimationLogic(result, pl);
        }
    });
}
function appendErrorPage(code) {
    var ctxt, mtxt, stxt;
    if (code == 500) {
        ctxt = '500 <small>Internal Server Error</small>';
        mtxt = 'Opps, Something went wrong.';
        stxt = 'The page you are looking for might have some internal issues';
    } else if (code == 503) {
        ctxt = '503 <small>Service Unavailable</small>';
        mtxt = 'Opps, Something went wrong.';
        stxt = 'The page you are looking for is unavailable';
    } else {
        ctxt = '404 <small>Page Not Found</small>';
        mtxt = 'We can not find the page you are looking for.';
        stxt = 'The page you are looking for might have been removed, had its name changed, or unavailable';
    }
    var str = '<div class="container-fluid"><div class="errorContainer"><div class="page-header">';
    str += '<h1 class="center">' + ctxt + '</h1>';
    str += '<h2 class="center">' + mtxt + '</h2>';
    str += '<p>' + stxt + '</p>';
    str += '</div>';
    str += '<div class="error-link-back">';
    str += '<a href="javascript://" onclick="loadLastVisitedURL()" class="btn" style="margin-right:10px;"><span class="icon16 icomoon-icon-arrow-left-10"></span>' + js_lang_label.GENERIC_GO_BACK + '</a>';
    str += '<a href="javascript://" onclick="loadAdminDashboardPage()" class="btn"><span class="icon16 icomoon-icon-screen"></span>' + js_lang_label.GENERIC_SITEMAP + '</a>';
    str += '</div></div></div>';
    return str;
}
function callBeforeAjaxCalling() {
    displayAjaxLoader();
    stopAutoRefreshGrid();
    stopFormSaveAsDraft();
    if (typeof executeBeforePageLoad == "function") {
        if ($('div[data-list-name]').length) {
            executeBeforePageLoad("list", $('div[data-list-name]').attr("data-list-name"));
        } else if ($('div[data-form-name]').length) {
            executeBeforePageLoad("form", $('div[data-form-name]').attr("data-form-name"));
        } else {
            executeBeforePageLoad("custom");
        }
    }
}
function callAfterAjaxCalling() {
    removeAllTinyMCEEditors();
    removeAllDataBindEvents();
    removeAllPreloadCCEvents();
}
function displayAjaxLoader() {
    showMainLoader();
    $("ul.sub.children-clicked").addClass("children-hide").removeClass("children-clicked");
    if (el_general_settings.mobile_platform && !$("#collapse_btn").hasClass('hide')) {
        $("#collapse_btn").click();
    }
}
function showMainLoader() {
    if ($("#sidebar").length) {
        if ($("#sidebar").hasClass("semi-collapse-menu")) {
            $("body").addClass("semi-left-menu").removeClass("full-left-menu");
        } else {
            $("body").addClass("full-left-menu").removeClass("semi-left-menu");
        }
    }
    $("body").addClass("loadstate");
}
function hideMainLoader() {
    $("body").removeClass("loadstate");
}
function applyAnimationLogic(response, pl) {
    $("#content,#content_slide").off('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd');
    var winWidth = $(window).innerWidth();
    var mainWidth = $("#main_content_div").offset().left;
    $("#" + el_tpl_settings.container_div).css({
        "left": "-" + winWidth + "px"
    });
    $('#' + getAnimateNextDIV()).show();
    el_tpl_settings.container_div = getAnimateNextDIV();
    $('#' + getAnimateNextDIV()).html("").css({
        "left": (winWidth - mainWidth) + "px"
    });
    if (el_tpl_settings.page_animation) {
        $('#' + el_tpl_settings.container_div).on('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd', function () {
            callAfterPageRendering();
            $('#' + el_tpl_settings.container_div).off('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd');
        });
    }
    $('#' + el_tpl_settings.container_div).html(response);
    callBeforePageRendering();
    $('#' + el_tpl_settings.container_div).css({
        "left": "0px"
    });
    hideMainLoader();
    if (!el_tpl_settings.page_animation || pl === false) {
        callAfterPageRendering();
    }
}
function callBeforePageRendering() {
    getResizedSubTabs();
    initializeMenuCollpaseEvents("before");
    Project.modules.ajaxNavigate.hijackAnchor();
    getSetTopViewHeight();
    niceScrollHomePageBlocks();
    getAdminImageTooltip();
}
function callAfterPageRendering() {
    Project.hide_adaxloading_div();
    initializeBasicAjaxEvents();
    getSetTopViewHeight();
    $('#' + getAnimateNextDIV()).hide();
    resizeGridWidth();
    setPageMetaTitleData();
    setCustomDesignGridster();
    executePageCallbacks();
}
function initializeFancyAjaxEvents() {
    //initializeTopHeading();
    initializejQueryChosenEvents();
    initializeTooltipsEvents();
    initializePopoverEvents();
    initializePatternPwdEvents();
    initializeFancyBoxEvents();
    applyInputTextCase();
    adjustAppendPrependText();
    initPreloadCCEvents();
    getSetTopViewHeight();
    renderResizeBlocks();
    getResizedSubTabs();
    initializeTabFocusIndex($(".scrollable-content, .settings-class"));

    resizeFancyGridWidth();
    setCustomDesignGridster();
    executePageCallbacks()
    
    setTimeout(function () {
        renderResizeBlocks();
    }, 300);
}
function initializeBasicAjaxEvents() {
    //initializeTopHeading();
    initializeMenuCollpaseEvents("after");
    initNiceScrollBar();
    initializejQueryChosenEvents();
    initializeTooltipsEvents();
    initializePopoverEvents();
    initializePatternPwdEvents();
    initializeFancyBoxEvents();
    applyInputTextCase();
    initPreloadCCEvents();
    adjustAppendPrependText();
    renderResizeBlocks();
    initializeTabFocusIndex($(".scrollable-content, .settings-class"));
    
    setTimeout(function () {
        renderResizeBlocks();
    }, 300);
}
function initializeBasicAjaxEvents_1(eleObj) {
    initializejQueryChosenEvents(eleObj);
    initializeTooltipsEvents(eleObj);
    initializePopoverEvents(eleObj);
    initializePatternPwdEvents(eleObj);
    initializeFancyBoxEvents(eleObj);
    applyInputTextCase(eleObj);
    adjustAppendPrependText(eleObj);
    initializeTabFocusIndex(eleObj);
}
function initializeSubgridEvents(eleObj) {
    initializeFancyBoxEvents(eleObj);
}
function initializeNesgridEvents(eleObj) {
    initializeFancyBoxEvents(eleObj);
}
function setPageMetaTitleData() {
    if ($("#txt_module_meta").length) {
        var meta_title;
        if ($("#txt_module_meta").is("textarea")) {
            meta_title = $.trim($("#txt_module_meta").val());
        } else {
            meta_title = $.trim($("#txt_module_meta").html());
        }
        if (meta_title) {
            $("title").html(meta_title);
        } else {
            $("title").html(globalMetaTitle);
        }
    } else {
        $("title").html(globalMetaTitle);
    }
}
function executePageCallbacks() {
    if ($('div[data-form-name]').length) {
        if (typeof executeAfterChildRecAdd == "function") {
            if ($.isFunction(executeAfterChildRecAdd)) {
                $("table[id^='tbl_child_module_']").each(function () {
                    var child_id = $(this).attr("id");
                    child_id = child_id.replace("tbl_child_module_", "");
                    $("tr[id^='tr_child_row_" + child_id + "_']").each(function () {
                        executeAfterChildRecAdd(child_id, $(this).attr("id").split("_").pop());
                    });
                });
            }
        }
    }
    if (typeof executeAfterPageLoad == "function") {
        if ($('div[data-list-name]').length) {
            executeAfterPageLoad("list", $('div[data-list-name]').attr("data-list-name"));
        } else if ($('div[data-form-name]').length) {
            executeAfterPageLoad("form", $('div[data-form-name]').attr("data-form-name"));
        } else {
            executeAfterPageLoad("custom");
        }
    }
}
function convertHASHToURL(hash) {
    var ajax_url;
    if (!hash) {
        return '';
    }
    hash = hash.toString().replace(/%7c/gi, '|');
    var array = hash.toString().split("|");
    if (array[0].toString().substring(0, 1) == "#") {
        var ajax_url = admin_url + array[0].toString().substr(1) + "?";
    } else {
        var ajax_url = admin_url + array[0].toString() + "?";
    }

    for (var i = 1; i < array.length; i++) {
        if (i % 2 == 0) {
            ajax_url += "=" + array[i];
        } else {
            ajax_url += "&" + array[i];
        }
    }
    return ajax_url;

}
function getHASHToFancyParams(hash) {
    var params_obj = {};
    if (!hash) {
        return params_obj;
    }
    hash = hash.toString().replace(/%7c/gi, '|');
    var array = hash.toString().split("|");
    for (var i = 1; i < array.length; i++) {
        if ($.inArray(array[i], fancy_params) != "-1") {
            var ind_val = array[parseInt(i) + 1];
            if (ind_val == "true" || ind_val == "false") {
                params_obj[array[i]] = (ind_val == "false") ? false : true;
            } else {
                params_obj[array[i]] = ind_val;
            }
        }
    }
    return params_obj;
}
function getQueryToFancyParams(hash) {
    var params_obj = {};
    if (!hash) {
        return params_obj;
    }
    var array = hash.toString().split("&");
    for (var i = 1; i < array.length; i++) {
        if ($.inArray(array[i], fancy_params) != "-1") {
            var ind_val = array[parseInt(i) + 1];
            if (ind_val == "true" || ind_val == "false") {
                params_obj[array[i]] = (ind_val == "false") ? false : true;
            } else {
                params_obj[array[i]] = ind_val;
            }
        }
    }
    return params_obj;
}
function removeAllTinyMCEEditors() {
    if (typeof tinyMCE == "undefined") {
        return false;
    }
    if (tinyMCE.editors.length) {
        for (var i in tinyMCE.editors) {
            tinyMCE.editors[i].remove(); // or destroy() ?
        }
    }
}
function removeAllDataBindEvents() {
    $(".groupfilter-list").remove();
    $(".daterangepicker").remove();
    $(".listgrid-block").remove();
    $(".listsort-block").remove();
}
function removeAllPreloadCCEvents() {
    pre_cond_code_arr = [];
}
function removeIndividualTinyMCEEditor(editor_id) {
    if (editor_id != "" && typeof tinymce != "undefined") {
        if (typeof tinymce.EditorManager != "undefined" && typeof tinymce.EditorManager.editors[editor_id] != "undefined") {
            tinymce.EditorManager.editors[editor_id].remove();
        }
    }
}
function removeCodeMarkupProperties(ele_id) {
    if (ele_id == "" || typeof CodeMirror == "undefined") {
        return;
    }
    if ($("#" + ele_id).data("cm")) {
        $("#" + ele_id).data("cm").toTextArea();
    }
}
function initPreloadCCEvents() {
    if (typeof pre_cond_code_arr == "undefined" || !pre_cond_code_arr.length) {
        return false;
    }
    for (var i = 0; i < pre_cond_code_arr.length; i++) {
        checkCCEventValues(pre_cond_code_arr[i]);
    }
}
function adjustAppendPrependText(eleObj) {
    var addon_ele, pwidth, twidth, cwidth, fwidth, cheight;
    if (eleObj) {
        $(eleObj).find(".ctrl-append-prepend").each(function () {
            var is_hidden = 0, display_css, visibility_css;
            if($(this).closest(".form-child-table").is(":hidden"))
            {
                is_hidden = 1;
                visibility_css = $(this).closest(".form-child-table").css("visibility");
                display_css = $(this).closest(".form-child-table").css("display");
                $(this).closest(".form-child-table").css("visibility", "hidden");
                $(this).closest(".form-child-table").css("display", "block");
            }
            addon_ele = $(this).parent().find(".text-addon");
            pwidth = $(this).parent().width();
            twidth = 0;
            $.each(addon_ele, function () {
                twidth += $(this).outerWidth();
            });
            cwidth = $(this).width();
            cheight = $(this).height();
            fwidth = Math.round(((cwidth - twidth) * 100 / pwidth) * 100) / 100;
            $(this).attr('style', $(this).attr('style') + ';' + 'width: ' + fwidth + '% !important;');
            $(addon_ele).css({'height': cheight + 'px', 'line-height': cheight + 'px'});
            if(is_hidden)
            {
                $(this).closest(".form-child-table").css("display", display_css);
                $(this).closest(".form-child-table").css("visibility", visibility_css);
            }
        });
    } else {
        $(".ctrl-append-prepend").each(function () {
            var is_hidden = 0, display_css, visibility_css;
            if($(this).closest(".form-child-table").is(":hidden"))
            {
                is_hidden = 1;
                visibility_css = $(this).closest(".form-child-table").css("visibility");
                display_css = $(this).closest(".form-child-table").css("display");
                $(this).closest(".form-child-table").css("visibility", "hidden");
                $(this).closest(".form-child-table").css("display", "block");
            }
            addon_ele = $(this).parent().find(".text-addon");
            pwidth = $(this).parent().width();
            twidth = 0;
            $.each(addon_ele, function () {
                twidth += $(this).outerWidth();
            });
            cwidth = $(this).width();
            cheight = $(this).height();
            fwidth = Math.round(((cwidth - twidth) * 100 / pwidth) * 100) / 100;
            $(this).attr('style', $(this).attr('style') + ';' + 'width: ' + fwidth + '% !important;');
            $(addon_ele).css({'height': cheight + 'px', 'line-height': cheight + 'px'});
            if(is_hidden)
            {
                $(this).closest(".form-child-table").css("display", display_css);
                $(this).closest(".form-child-table").css("visibility", visibility_css);
            }
        });
    }
}
function initializeMenuCollpaseEvents(type) {
    // for side menu collpase
    var window_width = $(window).width();
    var m_left_width = parseFloat(window_width);

    var sbtn = -1, pjson = {};
    if ($("#grid_search_btn").length) {
        $('#grid_search_btn').hide();
        sbtn = ($("#grid_search_btn").hasClass("left-show") && !$("#grid_search_btn").hasClass("hide-left-search")) ? true : false;
    }
    if ($('#sidebar').length == 0 || $('#sidebar').is(':hidden')) {
        pjson = animateHeaderContent(true, false, false, false, sbtn);
    } else if ($('#sidebar').hasClass('semi-collapse-menu')) {
        pjson = animateHeaderContent(false, true, false, false, sbtn);
    } else if ($('#collapse_btn').hasClass('hide')) {
        pjson = animateHeaderContent(false, false, true, false, sbtn);
    } else {
        pjson = animateHeaderContent(false, false, false, true, sbtn);
    }
    $('#main_content_div').css({
        'margin-left': pjson.mdmgn + 'px'
    });
    $("#top_heading_fix").width(m_left_width);
    $("#top_heading_fix > h3").css({
        'paddingLeft': pjson.fhpad + 'px'
    });
    if ($('#grid_search_btn').length) {
        $('#grid_search_btn').css({
            top: pjson.satop,
            left: pjson.salef
        });
        if (type == "after") {
            $('#grid_search_btn').show();
            if ($("#grid_search_btn").hasClass("hide-left-search")) {
                $("#grid_search_btn").removeClass("hide-left-search");
                $("#grid_search_btn").addClass("left-hide").removeClass("left-show");
                $("#grid_search_btn").children('a').find("span").attr('class', 'icomoon-icon-arrow-right-7');
            }
        }
    }
    if ($('.top-frm-tab-layout').length) {
        var tlef = $('.top-frm-tab-layout').offset().left;
        $('.top-frm-tab-layout').width(m_left_width - pjson.tfmin - parseFloat(tlef));
    } else if ($(".top-list-tab-layout").length) {
        var tlef = $('.top-list-tab-layout').offset().left;
        $('.top-list-tab-layout').width(m_left_width - pjson.tlmin - parseFloat(tlef));
    }
    if (type == "before") {
        $("#top_heading_fix > h3").css({"width": "600px"});
    } else {
        $("#top_heading_fix > h3").css({"width": pjson.fhwid + "px"});
    }
}
function initializejQueryChosenEvents(eleObj) {
    // for chosen jquery
    if (eleObj) {
        $(eleObj).find(".chosen-select:not([data-template=true])").chosen({
            allow_single_deselect: true
        });
        $(eleObj).find(".chosen-select[data-template=true]").chosen({
            allow_single_deselect: true,
            template: function (text, value, data, elem) {
                var module;
                if ($('div[data-list-name]').length) {
                    module = $('div[data-list-name]').attr("data-list-name");
                } else if ($('div[data-form-name]').length) {
                    module = $('div[data-form-name]').attr("data-form-name");
                }
                if (module) {
                    var tmpl = Project.modules[module].dropdownLayouts($(elem).attr("name"));
                    if (tmpl) {
                        if (typeof Mustache != 'undefined') {
                            data['text'] = text;
                            data['value'] = value;
                            tmpl = Mustache.render(tmpl, data);
                        } else {
                            var list = tmpl.match(/{{\s*[\w\.]+\s*}}/g).map(function (x) {
                                return x.match(/[\w\.]+/)[0];
                            });
                            if ($.isArray(list) && list.length > 0) {
                                for (var i in list) {
                                    var key = list[i], search_txt, replace_txt;
                                    search_txt = "{{" + key + "}}";
                                    if (key == "text") {
                                        replace_txt = text;
                                    } else if (list[i] == "value") {
                                        replace_txt = value;
                                    } else {
                                        replace_txt = data[key];
                                    }
                                    if (replace_txt != undefined) {
                                        tmpl = tmpl.replace(search_txt, replace_txt);
                                    }
                                }
                            }
                        }
                        return tmpl;
                    } else {
                        return text;
                    }
                } else {
                    return text;
                }
            }
        });
    } else {
        if ($(".chosen-select").length) {
            $(".chosen-select:not([data-template=true])").chosen({
                allow_single_deselect: true
            });
            $(".chosen-select[data-template=true]").chosen({
                allow_single_deselect: true,
                template: function (text, value, data, elem) {
                    var module;
                    if ($('div[data-list-name]').length) {
                        module = $('div[data-list-name]').attr("data-list-name");
                    } else if ($('div[data-form-name]').length) {
                        module = $('div[data-form-name]').attr("data-form-name");
                    }
                    if (module) {
                        var tmpl = Project.modules[module].dropdownLayouts($(elem).attr("name"));
                        if (tmpl) {
                            var list = tmpl.match(/{{\s*[\w\.]+\s*}}/g).map(function (x) {
                                return x.match(/[\w\.]+/)[0];
                            });
                            if ($.isArray(list) && list.length > 0) {
                                for (var i in list) {
                                    var key = list[i], search_txt, replace_txt;
                                    search_txt = "{{" + key + "}}";
                                    if (key == "text") {
                                        replace_txt = text;
                                    } else if (list[i] == "value") {
                                        replace_txt = value;
                                    } else {
                                        replace_txt = data[key];
                                    }
                                    if (replace_txt != undefined) {
                                        tmpl = tmpl.replace(search_txt, replace_txt);
                                    }
                                }
                            }
                            return tmpl;
                        } else {
                            return text;
                        }
                    } else {
                        return text;
                    }
                }
            });
        }
    }
}
function initializeTooltipsEvents(eleObj) {
    //------------- Tooltips -------------//
    //top tooltip
    if (eleObj) {
        $(eleObj).find('.tip').qtip({
            content: false,
            position: {
                my: 'bottom center',
                at: 'top center',
                viewport: $(window)
            },
            style: {
                classes: 'ui-tooltip-tipsy'
            }
        });
        //tooltip in right
        $(eleObj).find('.tipR').qtip({
            content: false,
            position: {
                my: 'left center',
                at: 'right center',
                viewport: $(window)
            },
            style: {
                classes: 'ui-tooltip-tipsy'
            }
        });
        //tooltip in bottom
        $(eleObj).find('.tipB').qtip({
            content: false,
            position: {
                my: 'top center',
                at: 'bottom center',
                viewport: $(window)
            },
            style: {
                classes: 'ui-tooltip-tipsy'
            }
        });
        //tooltip in left
        $(eleObj).find('.tipL').qtip({
            content: false,
            position: {
                my: 'right center',
                at: 'left center',
                viewport: $(window)
            },
            style: {
                classes: 'ui-tooltip-tipsy'
            }
        });
    } else {
        $('.tip').qtip({
            content: false,
            position: {
                my: 'bottom center',
                at: 'top center',
                viewport: $(window)
            },
            style: {
                classes: 'ui-tooltip-tipsy'
            }
        });
        //tooltip in right
        $('.tipR').qtip({
            content: false,
            position: {
                my: 'left center',
                at: 'right center',
                viewport: $(window)
            },
            style: {
                classes: 'ui-tooltip-tipsy'
            }
        });
        //tooltip in bottom
        $('.tipB').qtip({
            content: false,
            position: {
                my: 'top center',
                at: 'bottom center',
                viewport: $(window)
            },
            style: {
                classes: 'ui-tooltip-tipsy'
            }
        });
        //tooltip in left
        $('.tipL').qtip({
            content: false,
            position: {
                my: 'right center',
                at: 'left center',
                viewport: $(window)
            },
            style: {
                classes: 'ui-tooltip-tipsy'
            }
        });
    }
}
function initializePopoverEvents(eleObj) {
    //------------- Popover tips -------------//
    //top Popover
    if (eleObj) {
        $(eleObj).find('a[rel=popover]').popover().click(function (e) {
            e.preventDefault();
        });
    } else {
        $("a[rel=popover]").popover().click(function (e) {
            e.preventDefault();
        })
    }
}
function initializePatternPwdEvents(eleObj) {
    if (eleObj) {
        $(eleObj).find("input[role='patternlock'][role-complete!='yes']").each(function () {
            var ele_id = $(this).attr("id");
            var pattern_container_div = document.createElement('div');
            var pattern_reset_span = document.createElement('span');
            var el;
            $(pattern_container_div).attr('id', 'pattern_container_' + ele_id);
            $(pattern_container_div).addClass('pattern_container');
            $(pattern_reset_span).attr('id', 'pattern_reset_' + ele_id);
            $(pattern_reset_span).addClass('gray-bg right pattern_reset');
            $(pattern_reset_span).html("<strong>Old Pattern</strong>");
            pattern_container_div.appendChild(pattern_reset_span);
            el = $(this)[0];
            el.style.display = 'none';
            $(el).before(pattern_container_div);
            $('#' + pattern_container_div.id).pattern({
                preDefinedPattern: $(el).val().split("") || [],
                stop: function (event, ui) {
                    if (ui.pattern.length) {
                        $(el).val(ui.pattern.join(""));
                    }
                }
            });
            if (parseInt($(el).val()) > 0) {
            } else {
                $(pattern_reset_span).hide();
            }
            $(pattern_reset_span).on('click', function () {
                $('#' + pattern_container_div.id).pattern('drawPredefinedPattern');
                var val = $('#' + pattern_container_div.id).pattern('getPreDefinedPattern')
                val = val.join("");
                $(el).val(val);
            });
            $(this).attr("role-complete", 'yes');
        });
    } else {
        $("input[role='patternlock'][role-complete!='yes']").each(function () {
            var ele_id = $(this).attr("id");
            var pattern_container_div = document.createElement('div');
            var pattern_reset_span = document.createElement('span');
            var el;
            $(pattern_container_div).attr('id', 'pattern_container_' + ele_id);
            $(pattern_container_div).addClass('pattern_container');
            $(pattern_reset_span).attr('id', 'pattern_reset_' + ele_id);
            $(pattern_reset_span).addClass('gray-bg right pattern_reset');
            $(pattern_reset_span).html("<strong>" + js_lang_label.GENERIC_OLD_PATTERN + "</strong>");
            pattern_container_div.appendChild(pattern_reset_span);
            el = $(this)[0];
            el.style.display = 'none';
            $(el).before(pattern_container_div);
            $('#' + pattern_container_div.id).pattern({
                preDefinedPattern: $(el).val().split("") || [],
                stop: function (event, ui) {
                    if (ui.pattern.length) {
                        $(el).val(ui.pattern.join(""));
                    }
                }
            });
            if (parseInt($(el).val()) > 0) {
            } else {
                $(pattern_reset_span).hide();
            }
            $(pattern_reset_span).on('click', function () {
                $('#' + pattern_container_div.id).pattern('drawPredefinedPattern');
                var val = $('#' + pattern_container_div.id).pattern('getPreDefinedPattern')
                val = val.join("");
                $(el).val(val);
            });
            $(this).attr("role-complete", 'yes');
        });
    }
}
function initializeFancyBoxEvents(eleObj) {
    if (eleObj) {
        $(eleObj).find(".fancybox-image").each(function () {
            $(this).fancybox({
                padding: 10,
                openEffect: 'elastic',
                openSpeed: 150,
                closeEffect: 'elastic',
                closeSpeed: 150,
                closeClick: true,
                helpers: {
                    title: {
                        type: 'float'
                    }
                }
            });
        });
    } else {
        $(".fancybox-image").each(function () {
            $(this).fancybox({
                padding: 10,
                openEffect: 'elastic',
                openSpeed: 150,
                closeEffect: 'elastic',
                closeSpeed: 150,
                closeClick: true,
                helpers: {
                    title: {
                        type: 'float'
                    }
                }
            });
        });
    }

}
function applyInputTextCase(eleObj) {
    if (eleObj) {
        $(eleObj).find(".apply-text-upper_case").Setcase({
            caseValue: 'upper',
            changeonFocusout: false,
            changebyDefault: true
        });
        $(eleObj).find(".apply-text-lower_case").Setcase({
            caseValue: 'lower',
            changeonFocusout: false,
            changebyDefault: true
        });
        $(eleObj).find(".apply-text-uc_first").Setcase({
            caseValue: 'uc_first',
            changeonFocusout: false,
            changebyDefault: true
        });
        $(eleObj).find(".apply-text-uc_word").Setcase({
            caseValue: 'uc_word',
            changeonFocusout: false,
            changebyDefault: true
        });
    } else {
        $(".apply-text-upper_case").Setcase({
            caseValue: 'upper',
            changeonFocusout: false,
            changebyDefault: true
        });
        $(".apply-text-lower_case").Setcase({
            caseValue: 'lower',
            changeonFocusout: false,
            changebyDefault: true
        });
        $(".apply-text-uc_first").Setcase({
            caseValue: 'uc_first',
            changeonFocusout: false,
            changebyDefault: true
        });
        $(".apply-text-uc_word").Setcase({
            caseValue: 'uc_word',
            changeonFocusout: false,
            changebyDefault: true
        });
    }
}
function jqueryUIalertBox(msg, title, btn, width, height) {
    var label_elem = '<div class="dialog-alert-box"></div>';
    var label_text = msg;
    var option_params = {
        title: (title) ? title : js_lang_label.GENERIC_GRID_WARNING,
        width: (width) ? width : 300,
        height: (height) ? height : "auto",
        buttons: [{
                text: (btn) ? btn : js_lang_label.GENERIC_OK,
                btn_alert: 'ok',
                click: function () {
                    $(this).remove();
                }
            }]
    }
    jqueryUIdialogBox(label_elem, label_text, option_params);
}
function validateViewInlineEdit(name, val, view_rules) {
    if (!view_rules || !view_rules['editrules']) {
        return false;
    }
    val = (val == undefined) ? '' : val;
    var valid_rules = view_rules['editrules'];
    if (valid_rules.required === true) {
        if (isEmptyValue(val)) {
            return valid_rules.infoArr.required.message;
        }
    }
    // force required
    var filter, rqfield = valid_rules.required === true ? true : false;
    if (valid_rules.email === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            filter = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;
            if (!filter.test(val)) {
                return valid_rules.infoArr.email.message;
            }
        }
    }
    if (valid_rules.number === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            if (isNaN(val)) {
                return valid_rules.infoArr.number.message;
            }
        }
    }
    if (valid_rules.integer === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            if (isNaN(val)) {
                return valid_rules.infoArr.integer.message;
            }
            if ((val % 1 !== 0) || (val.indexOf('.') != -1)) {
                return valid_rules.infoArr.integer.message;
            }
        }
    }
    if (typeof valid_rules.minValue != 'undefined' && !isNaN(valid_rules.minValue)) {
        if (parseFloat(val) < parseFloat(valid_rules.minValue)) {
            return valid_rules.infoArr.minValue.message;
        }
    }
    if (typeof valid_rules.maxValue != 'undefined' && !isNaN(valid_rules.maxValue)) {
        if (parseFloat(val) > parseFloat(valid_rules.maxValue)) {
            return valid_rules.infoArr.maxValue.message;
        }
    }
    if (valid_rules.range === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            if (val < valid_rules.infoArr.range.lrange || val > valid_rules.infoArr.range.hrange) {
                return valid_rules.infoArr.range.message;
            }
        }
    }
    if (valid_rules.minlength === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            var len = val.length;
            if (len < valid_rules.infoArr.minlength.minvalue) {
                return valid_rules.infoArr.minlength.message;
            }
        }
    }
    if (valid_rules.maxlength === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            var len = val.length;
            if (len > valid_rules.infoArr.maxlength.maxvalue) {
                return valid_rules.infoArr.maxlength.message;
            }
        }
    }
    if (valid_rules.rangelength === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            var len = val.length;
            if (len < valid_rules.infoArr.rangelength.lrange || len > valid_rules.infoArr.rangelength.hrange) {
                return valid_rules.infoArr.rangelength.message;
            }
        }
    }
    if (valid_rules.nowhitespace === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            filter = /^\S+$/i;
            if (!filter.test(val)) {
                return valid_rules.infoArr.nowhitespace.message;
            }
        }
    }
    if (valid_rules.alpha_with_spaces === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            filter = /^[a-zA-Z ]+$/;
            if (!filter.test(val)) {
                return valid_rules.infoArr.alpha_with_spaces.message;
            }
        }
    }
    if (valid_rules.alpha_without_spaces === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            filter = /^[a-zA-Z]+$/;
            if (!filter.test(val)) {
                return valid_rules.infoArr.alpha_without_spaces.message;
            }
        }
    }
    if (valid_rules.alpha_numeric_with_spaces === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            filter = /^[0-9a-zA-Z ]+$/;
            if (!filter.test(val)) {
                return valid_rules.infoArr.alpha_numeric_with_spaces.message;
            }
        }
    }
    if (valid_rules.alpha_numeric_without_spaces === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            filter = /^[0-9a-zA-Z]+$/;
            if (!filter.test(val)) {
                return valid_rules.infoArr.alpha_numeric_without_spaces.message;
            }
        }
    }
    if (valid_rules.alpha_without_special_chars === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            filter = /^[a-zA-Z _-]+$/;
            if (!filter.test(val)) {
                return valid_rules.infoArr.alpha_without_special_chars.message;
            }
        }
    }
    if (valid_rules.alpha_numeric_without_special_chars === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            filter = /^[0-9a-zA-Z _-]+$/;
            if (!filter.test(val)) {
                return valid_rules.infoArr.alpha_numeric_without_special_chars.message;
            }
        }
    }
    if (valid_rules.phone_number === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            filter = /^([(]{1}[0-9]{3}[)]{1}[.| |-]{0,1}|^[0-9]{3}[.|-| ]?)?[0-9]{3}(.|-| )?[0-9]{4}$/;
            if (!filter.test(val)) {
                return valid_rules.infoArr.phone_number.message;
            }
        }
    }
    if (valid_rules.zip_code === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            filter = /^(?:[A-Z0-9]+([- ]?[A-Z0-9]+)*)?$/;
            if (!filter.test(val)) {
                return valid_rules.infoArr.zip_code.message;
            }
        }
    }
    if (valid_rules.ip_address === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            filter = /^(1?d{1,2}|2([0-4]d|5[0-5]))(.(1?d{1,2}|2([0-4]d|5[0-5]))){3}$/;
            if (!filter.test(val)) {
                return valid_rules.infoArr.ip_address.message;
            }
        }
    }
    if (valid_rules.ipv4 === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            filter = /^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)$/i;
            if (!filter.test(val)) {
                return valid_rules.infoArr.ipv4.message;
            }
        }
    }
    if (valid_rules.ipv6 === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            filter = /^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/i;
            if (!filter.test(val)) {
                return valid_rules.infoArr.ipv6.message;
            }
        }
    }
    if (valid_rules.credit_card === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            // taken from $ Validate plugin
            filter = /^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35d{3})d{11})$/;
            if (!filter.test(val)) {
                return valid_rules.infoArr.credit_card.message;
            }
        }
    }
    if (valid_rules.url === true) {
        if (!(rqfield === false && isEmptyValue(val))) {
            filter = /^(((https?)|(ftp)):\/\/([\-\w]+\.)+\w{2,3}(\/[%\-\w]+(\.\w{2,})?)*(([\w\-\.\?\\\/+@&#;`~=%!]*)(\.\w{2,})?)*\/?)/i;
            if (!filter.test(val)) {
                return valid_rules.infoArr.url.message;
            }
        }
    }
    return false;
}
function initNiceScrollBar() {
    if ($("#left_search_items").length) {
        $("#left_search_items").height($(window).height() - 170);
        $("#tbl_left_search").width($("#left_search_items").width())
        $("#left_search_items").niceScroll({
            cursoropacitymax: 0.7,
            cursorborderradius: 6,
            cursorwidth: "4px",
            zindex: 97,
            railalign: "left"
        });
    }
}
function hideNiceScrollBar() {
    if ($("#left_search_items").length) {
        $("#left_search_items").getNiceScroll().remove();
    }
}
function initLeftScrollBar(width) {
    $("#left_mainnav").height($(window).height() - 130);
    if ($("#left_mainnav").length) {
        var lm_wdth = (width) ? width : 4;
        $("#left_mainnav").niceScroll({
            cursoropacitymax: 0.7,
            cursorborderradius: 6,
            cursorwidth: lm_wdth + "px",
            horizrailenabled: false
        });
    }
}
function hideLeftScrollBar() {
    $("#left_mainnav").getNiceScroll().remove();
}
function check_editor(value, element) {
    tinyMCE.triggerSave();
    var newtext = value;
    newtext = str_replace('&nbsp;', '', newtext);
    newtext = strip_tags(newtext);
    newtext = newtext.trim();
    if (newtext.length === 0) {
        return false;
    } else {
        return true;
    }
}
function strip_tags(input, allowed) {
    allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi, commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
    input = ($.isArray(input)) ? input.join(", ") : input;
    return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}
function str_replace(search, replace, subject, count) {
    var i = 0, j = 0, temp = '', repl = '', sl = 0, fl = 0, f = [].concat(search), r = [].concat(replace), s = subject, s = [].concat(s);
    var ra = Object.prototype.toString.call(r) === '[object Array]', sa = Object.prototype.toString.call(s) === '[object Array]';
    if (count) {
        this.window[count] = 0;
    }
    for (i = 0, sl = s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }
        for (j = 0, fl = f.length; j < fl; j++) {
            temp = s[i] + '';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp).split(f[j]).join(repl);
            if (count && s[i] !== temp) {
                this.window[count] += (temp.length - s[i].length) / f[j].length;
            }
        }
    }
    return sa ? s : s[0];
}
function loadPageAdminHashRedirect(passed_hash) {
    window.location.hash = passed_hash;
}
function getGeneralPrice(price_p) {
    return price_p + " &#8377;";
}
function detectCaptureCameraAllow(ele_id, unique_name, module_url) {
    if (!navigator.getUserMedia) {
        $("#capture_" + ele_id).hide();
        return false;
    } else {
        var video_html = renderCaptureHTML(ele_id, unique_name, module_url);
        $("#webcamframe_" + ele_id).html(video_html);
        return true;
    }
}
function renderCaptureHTML(ele_id, unique_name, module_url) {
    var video_html = '';
    //video_html += renderCaptureVideoHTML(ele_id, mod_id, mod_add_id)
    video_html += '<div id="rendervideo_' + ele_id + '"></div>\n\
                    <div class="clear"></div>\n\
                    <div class="capture-buttons">\n\
                        <input value="' + js_lang_label.GENERIC_CAPTURE + '" name="ctrladd" type="button" class="btn btn-info" onclick="return captureCameraPhoto(\'' + ele_id + '\', \'' + unique_name + '\', \'' + module_url + '\')">\n\
                        <br /><br />\n\
                        <input value="' + js_lang_label.GENERIC_SAVE + '" name="ctrldiscard" type="button" class="btn" onclick="return saveCameraPhoto(\'' + ele_id + '\', \'' + unique_name + '\', \'' + module_url + '\')">\n\
                        <textarea name="camencimg_' + ele_id + '" id="camencimg_' + ele_id + '" style="display:none;"></textarea>\n\
                    </div>\n\
                    <div class="display-gallery" id="displaygallery_' + ele_id + '"></div>';
    return video_html;
}
function renderCaptureVideoHTML(ele_id, unique_name, module_url) {
    var video_html = '<section id="webapp_' + ele_id + '" aria-photo-filter="0">\n\
                        <div class="cam-container" id="cam_container' + ele_id + '">\n\
                            <span class="map-live" id="maplive_' + ele_id + '">' + js_lang_label.GENERIC_LIVE + '</span>\n\
                            <video id="showmonitor_' + ele_id + '" autoplay onclick="changePhotoFilter(this,\'' + ele_id + '\')" title="' + js_lang_label.GENERIC_CLICK_ME_TO_SEE_DIFFERENT_FILTERS + '"></video>\n\
                        </div>\n\
                        <p>' + js_lang_label.GENERIC_CLICK_THE_VIDEO_TO_SEE_DIFFERENT_CSS_FILTERS + '</p>\n\
                    </section>\n\
                    <canvas class="draw-cam-img" id="drawcamimg_' + ele_id + '"></canvas>';
    return video_html;
}
function initCameraPhoto(ele_id, unique_name, module_url) {
    var video_html = renderCaptureVideoHTML(ele_id, unique_name, module_url)
    $("#rendervideo_" + ele_id).html(video_html);
    $("#camencimg_" + ele_id).val("");
    $("body").attr("aria-cam-photo", ele_id);
    $.fancybox({
        content: $("#webcamframe_" + ele_id).show(),
        showCloseButton: true
    });
    var navUserMedia = navigator.getUserMedia({
        video: true
    }, gotStream, noStream);
}
function changePhotoFilter(elem, ele_id) {
    elem.className = '';
    var idx = $("#webapp_" + ele_id).attr("aria-photo-filter");
    var effect = wcfilters[idx++ % wcfilters.length];
    if (effect) {
        elem.classList.add(effect);
        $("#webapp_" + ele_id).attr("aria-photo-filter", idx);
    } else {
        $("#webapp_" + ele_id).attr("aria-photo-filter", "0");
    }
}
function gotStream(stream) {
    var cam_ele_id = $("body").attr("aria-cam-photo");
    var video = document.getElementById('showmonitor_' + cam_ele_id);
    var canvas = document.getElementById('drawcamimg_' + cam_ele_id);
    if (window.URL) {
        video.src = window.URL.createObjectURL(stream);
    } else {
        video.src = stream; // Opera.
    }
    video.onerror = function (e) {

    };
    localStream = stream; // Opera.
    stream.onended = noStream;
    video.onloadedmetadata = function (e) { // Not firing in Chrome. See crbug.com/110938.
        $('#webapp_' + cam_ele_id).css("display", "block");
    };
    // Since video.onloadedmetadata isn't firing for getUserMedia video, we have
    // to fake it.
    setTimeout(function () {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        $('#webapp_' + cam_ele_id).css("display", "block");
        $('#drawcamimg_' + cam_ele_id).css("display", "none");
    }, 50);
}
function noStream(e) {
    var msg = js_lang_label.GENERIC_NO_CAMERA_AVAILABLE_FOR_CAPTURING_PICTURES;
    if (e == "PERMISSION_DENIED" || (e.name && e.name == "PERMISSION_DENIED")) {
        msg = js_lang_label.GENERIC_USER_DENIED_ACCESS_TO_USE_CAMERA_FOR_CAPTURING_PICTURES_C46_C46_C33;
    } else if (e == "HARDWARE_UNAVAILABLE" || (e.name && e.name == "HARDWARE_UNAVAILABLE")) {
        msg = js_lang_label.GENERIC_SYSTEM_HARDWARE_DOES_NOT_SUPPORT_FOR_CAPTURING_PICTURES_C46_C46_C33;
    }
    $.fancybox.close();
    Project.setMessage(msg, 0);
}
function captureCameraPhoto(ele_id) {
    var video = document.getElementById('showmonitor_' + ele_id);
    var canvas = document.getElementById('drawcamimg_' + ele_id);
    var ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0);
    var img = document.createElement('img');
    img.src = canvas.toDataURL('image/png');
    $("#camencimg_" + ele_id).val(img.src);
    $("#displaygallery_" + ele_id).html(img);
}
function saveCameraPhoto(ele_id, unique_name, module_url) {
    if ($.trim($("#camencimg_" + ele_id).val()) == "") {
        Project.setMessage(js_lang_label.GENERIC_PLEASE_CAPTURE_PHOTO_FOR_SAVING_C46_C46_C33, 0);
        return false;
    }
    $.fancybox.close();
    var req_uri = admin_url + module_url + "?";
    $.ajax({
        url: req_uri,
        type: 'POST',
        data: {
            'unique_name': unique_name,
            'type': 'webcam',
            'oldFile': $('#temp_' + ele_id).val(),
            'newFile': $("#camencimg_" + ele_id).val()
        },
        success: function (response) {
            $("#camencimg_" + ele_id).val("");
            var jparse_data = parseJSONString(response);
            if (jparse_data.success == '0') {
                Project.setMessage(jparse_data.message, 0);
            } else {
                $('#' + ele_id).val(jparse_data.uploadfile);
                $('#temp_' + ele_id).val(jparse_data.oldfile);
                displayAdminOntheFlyImage(ele_id, jparse_data);
            }
        }
    });
}
function getSetTopViewHeight() {
    if ($(".module-navigation-tabs .nav-tabs").length) {
        $(".frm-elem-block").addClass("frm-elem-tabs");
    } else {
        $(".frm-elem-block").removeClass("frm-elem-tabs");
    }
    if ($('.top-frm-tab-layout').length) {
        var $top_view_height = $('.top-frm-tab-layout').height();
        var tht = parseInt($top_view_height);
        if (!isNaN(tht) && tht > 0) {
            if (!$(".module-navigation-tabs .nav-tabs").length && !$(".frm-custm-view").length) {
                tht -= 16;
            }
            $('.top-block-spacing').css('margin-top', tht + 'px');
        } else {
            $('.top-block-spacing').css('margin-top', '0px');
        }
        return true;
    } else if ($('.top-list-tab-layout').length) {
        var $top_view_height = $('.top-list-tab-layout').height();
        var tht = parseInt($top_view_height);
        if (!isNaN(tht) && tht > 0) {
            $('.top-list-pager-space').css('margin-top', (tht - 6) + 'px');
        } else {
            $('.top-list-pager-space').css('margin-top', '0px');
        }
        return true;
    }
    return false;
}
function getCaptureDetailScript(html_id, unique_name, module_url) {
    var capture_str = "<span class='capture-webcam-photo' id='capture_" + html_id + "'>";
    capture_str += "<a href='javascript://' onclick='initCameraPhoto(\"" + html_id + "\", \"" + unique_name + "\", \"" + module_url + "\")' title='" + js_lang_label.GENERIC_CAPTURE_PHOTO + "'><span class='icon32 entypo-icon-camera'></span></a>";
    capture_str += "<div class='web-cam-frame' id='webcamframe_" + html_id + "' style='display:none'></div>";
    capture_str += "</span>";
    return capture_str;
}
function getGeneralHASHParams() {
    var hash = window.location.hash, params_obj = {};
    if (!hash) {
        return params_obj;
    }
    hash = hash.toString().replace(/%7c/gi, '|');
    var array = hash.toString().split("|");
    for (var i = 1; i < array.length; i += 2) {
        params_obj[array[i]] = array[parseInt(i) + 1];
    }
    return params_obj;
}
function isEmptyValue(val) {
    if (typeof val == "object") {
        if (val) {
            return false;
        } else {
            return true;
        }
    } else {
        if (val.match(/^\s+$/) || val === "") {
            return true;
        } else {
            return false;
        }
    }
}
function hideShowTopView($ele) {
    var mod_hash_name = $($ele).attr("aria-module-name");
    if ($($ele).hasClass('hide-top-detail-view')) {
        $('#div_main_top_detail_view').slideDown(750);
        $($ele).attr('title', js_lang_label.GENERIC_HIDE_VIEW);
        $($ele).removeClass('hide-top-detail-view').removeClass('active');
        $.cookie(mod_hash_name, null, {path: '/', expires: -1});
    } else {
        $('#div_main_top_detail_view').slideUp(750);
        $($ele).attr('title', js_lang_label.GENERIC_SHOW_VIEW);
        $($ele).addClass('hide-top-detail-view').addClass('active');
        $.cookie(mod_hash_name, '1', {path: '/', expires: 100});
    }
    setTimeout(function () {
        getSetTopViewHeight();
    }, 1000);
}
function initializeTabFocusIndex(eleObj) {
    var frm_first_box = $(eleObj).find("form:first *:input[type=text]:not('.hasDatepicker'),textarea").filter(":visible:first");
    if ($(frm_first_box).length && !$(frm_first_box).hasClass("restrict-focus") && !$(frm_first_box).hasClass("multi-container-search") && !$(frm_first_box).closest(".token-input-input-token").length) {
        if ($(frm_first_box).offset().top <= $(window).height()) {
            $(frm_first_box).focus();
        }
    }
//    //Do not remove the below code
//    if (!$(".tab-focus-parent").length || !el_general_settings.active_tab_index) {
//        return false;
//    }
//    var tabinx = 1, elecnt = 1;
//    $(".tab-focus-parent").each(function () {
//        var elecnt = $(this).find(".tab-focus-child:eq(0)").find(".tab-focus-element").length;
//        for (var i = 0; i < elecnt; i++) {
//            $(this).find(".tab-focus-child").each(function () {
//                $(this).find(".tab-focus-element:eq(" + i + ")").find("input, select, textarea").each(function () {
//                    if (!$(this).is(":hidden") && !$(this).attr("tabindex")) {
//                        $(this).attr("tabindex", tabinx);
//                        tabinx++;
//                    }
//                });
//            });
//        }
//    });
}
function printThisElementContent(ele_id, write_obj) {
    if (!$("#" + ele_id).length) {
        return false;
    }
    var print_arr = [];
    $("link[rel='stylesheet']").each(function () {
        print_arr.push($(this).attr("href"));
    });
    var base_obj = {
        overrideElementCSS: print_arr
    }
    write_obj = ($.isPlainObject(write_obj)) ? write_obj : {};
    var final_obj = $.extend({}, base_obj, write_obj);
    $("#" + ele_id).printElement(final_obj);
}
function isRedirectEqualHash(red_hash) {
    var curhash = window.location.hash;
    if (!curhash) {
        return true;
    }
    curhash = curhash.toString().substr(1);
    if (curhash == "" && red_hash == "") {
        return true;
    } else if (curhash == red_hash) {
        return true;
    }
    return false;
}
function changeSpecificHASHValue(hash, param, value) {
    if (!hash) {
        return '';
    }
    if (hash.charAt(0) == "|") {
        hash = hash.substring(1);
    }
    if (hash.charAt(hash.length - 1) == "|") {
        hash = hash.slice(1, -1)
    }
    var ajax_hash = '', temp_param = false;
    hash = hash.toString().replace(/%7c/gi, '|');
    var array = hash.toString().split("|");
    for (i = 0; i < array.length; i++) {
        if (i % 2 == 0) {
            if (array[i] == param && param != "") {
                temp_param = param;
            }
            ajax_hash += "|" + array[i];
        } else {
            if (param == temp_param && temp_param !== false) {
                ajax_hash += "|" + value;
                temp_param = false;
            } else {
                ajax_hash += "|" + array[i];
            }
        }
    }
    return ajax_hash;
}
function uploadifyFlashError() {
    var nd, cd = new Date();
    if ($.cookie(el_tpl_settings.enc_usr_var + "_uploadifyRemainder")) {
        nd = new Date($.cookie(el_tpl_settings.enc_usr_var + "_uploadifyRemainder"));
    }
    if (!(/Invalid|NaN/.test(new Date(nd))) && nd) {
        if (new Date(nd.getFullYear(), nd.getMonth(), nd.getDate(), 0, 0, 0, 0) >= new Date(cd.getFullYear(), cd.getMonth(), cd.getDate(), 0, 0, 0, 0)) {
            return false;
        }
    }
    var msg = 'Please install flash in your browser to upload files/images.';
    $("#uploadifyErrDialog").remove();

    var label_elem = '<div id="uploadifyErrDialog"></div>';
    var label_text = msg;
    var option_params = {
        title: "Flash Settings",
        width: 420,
        buttons: {
            'Remind Me Later': function () {
                $.cookie(el_tpl_settings.enc_usr_var + "_uploadifyRemainder", (new Date()));
                $(this).remove();
            },
            'Skip': function () {
                $(this).remove();
            }
        }
    }
    jqueryUIdialogBox(label_elem, label_text, option_params);
}
function getAdminEncodedURL(req_url, whole_url) {
    var ret_url = req_url, url_t;
    if (el_tpl_settings.is_enc_active == "1") {
        if (req_url != "") {
            url_t = req_url.replace(admin_url, "");
            if (url_t != "") {
                url_t = Project.modules.ajaxNavigate.encrypt(url_t);
            }
            if (whole_url == "1") {
                ret_url = admin_url + url_t;
            } else {
                ret_url = url_t;
            }
        }
    }
    return ret_url;
}
function getAdminDecodedURL(req_url, whole_url) {
    var ret_url = req_url, url_t;
    if (el_tpl_settings.is_enc_active == "1") {
        if (req_url != "") {
            url_t = req_url.replace(admin_url, "");
            if (url_t != "") {
                url_t = Project.modules.ajaxNavigate.decrypt(url_t);
            }
            if (whole_url == "1") {
                ret_url = admin_url + url_t;
            } else {
                ret_url = url_t;
            }
        }
    }
    return ret_url;
}
function callSwitchToSelf() {
    if ($("select[id='vSwitchPage'][aria-switchto-self='true']").length) {
        setTimeout(function () {
            $('#vSwitchPage').ajaxChosen({
                dataType: "json",
                type: "POST",
                url: el_form_settings.jself_switchto_url
            }, {
                loadingImg: admin_image_url + "chosen-loading.gif"
            });
        }, 1000);
    }
}
function callSwitchToParent() {
    if ($("select[id='vParentSwitchPage'][aria-switchto-parent='true']").length) {
        setTimeout(function () {
            $('#vParentSwitchPage').ajaxChosen({
                dataType: "json",
                type: "POST",
                url: el_grid_settings.jparent_switchto_url
            }, {
                loadingImg: admin_image_url + "chosen-loading.gif"
            });
        }, 1000);
    }
}
function getResponsiveTopMenu() {
    if (!$('#navTopMenu').length) {
        return false;
    }
    var js_top_width = 0;
    var js_top_width_arr = [];
    $('#navTopMenu li.top').each(function () {
        js_top_width = js_top_width + parseFloat($(this).outerWidth());
        js_top_width_arr.push($(this).outerWidth());
    })
    var js_remain_width = $(window).width() - $('.top-model-view').outerWidth() - 100;
    var js_check_top_value = 0;
    var js_new_arr = [];
    if ($('div.lang-combo').length) {
        js_check_top_value += parseFloat($('div.lang-combo').outerWidth());
    }
    if ($('#notification-menu-item').length) {
        js_check_top_value += parseFloat($('#notification-menu-item').outerWidth());
    }
    if ($('#task_notification').length) {
        js_check_top_value += parseFloat($('#task_notification').outerWidth());
    }
    if ($('#profile-menu-item').length) {
        js_check_top_value += parseFloat($('#profile-menu-item').outerWidth());
    }
    
    for (i = 0; i < js_top_width_arr.length; i++) {
        js_check_top_value = parseFloat(js_check_top_value) + parseFloat(js_top_width_arr[i]);
        if (parseFloat(js_check_top_value) > parseFloat(js_remain_width)) {
            break;
        }
        js_new_arr.push(js_top_width_arr[i]);
    }

    if (js_new_arr.length > 0 && js_top_width_arr.length != js_new_arr.length) {
        var $index_val = parseFloat(js_new_arr.length) - 3;
        $index_val = ($index_val >= 0) ? $index_val : 0; 
        var js_html_top;
        
        if($('#profile-menu-item').length){
            js_html_top = $('.top-navigation-bar').find('li.top:gt(' + $index_val + '):not(.fixed-top-menu-item)');
            $('.top-navigation-bar').find('li.top:gt(' + $index_val + '):not(.fixed-top-menu-item)').remove();
            
            if($('#notification-menu-item').length){
                $('#notification-menu-item').before('<li class="top li-dropdown" id="li_more_menus"><a id="show_more_menus" class="show-more-menus top_link hasUl show" href="javascript://"><i class="minia-icon-list-3"></i><i class="icon16 icomoon-icon-arrow-down-2"></i></a></li>');
            } else if ($('#task_notification').length){
                $('#task_notification').before('<li class="top li-dropdown" id="li_more_menus"><a id="show_more_menus" class="show-more-menus top_link hasUl show" href="javascript://"><i class="minia-icon-list-3"></i><i class="icon16 icomoon-icon-arrow-down-2"></i></a></li>');
            } else {
                $('#profile-menu-item').before('<li class="top li-dropdown" id="li_more_menus"><a id="show_more_menus" class="show-more-menus top_link hasUl show" href="javascript://"><i class="minia-icon-list-3"></i><i class="icon16 icomoon-icon-arrow-down-2"></i></a></li>');
            }
        }else{
            js_html_top = $('.top-navigation-bar').find('li.top:gt(' + $index_val + ')');
            $('.top-navigation-bar').find('li.top:gt(' + $index_val + ')').remove()
            $('#navTopMenu').append('<li class="top li-dropdown" id="li_more_menus"><a id="show_more_menus" class="show-more-menus top_link hasUl show" href="javascript://"><i class="minia-icon-list-3"></i><i class="icon16 icomoon-icon-arrow-down-2"></i></a></li>')
        }
        
        $('.top-menu').append('<ul id="vertical_dropdown_menu" class="vertical-dropdown-menu" style="display:none"></ul>')
        $('#vertical_dropdown_menu').html(js_html_top)
        $('#show_more_menus').click(function () {
            if ($('#show_more_menus').hasClass('show')) {
                $('#show_more_menus').removeClass('show');
                $('#vertical_dropdown_menu').show();
            } else {
                $('#show_more_menus').addClass('show');
                $('#vertical_dropdown_menu').hide();
            }
        });
        $(document).on('touchstart', '.parent-menu-li', function (e) {
            $(this).children("ul.sub").removeClass("children-hide").addClass("children-clicked");
        });
        $(document).on('touchmove', function (e) {
            if (!$(e.target).parents().hasClass('li-dropdown') && !$('#show_more_menus').hasClass('show') && (!$(e.target).parents().hasClass('parent-menu-li') || $(e.target).parents().hasClass('sub'))) {
                $('#show_more_menus').addClass('show');
                $('#vertical_dropdown_menu').hide();
            }
        });
        $(document).click(function (e) {
            if (!$(e.target).parents().hasClass('li-dropdown') && !$('#show_more_menus').hasClass('show') && (!$(e.target).parents().hasClass('parent-menu-li') || $(e.target).parents().hasClass('sub'))) {
                $('#show_more_menus').addClass('show');
                $('#vertical_dropdown_menu').hide();
            }
        });
        $(document).on('click', 'li[id^="parent_menu"]', function (e) {
            $('.top-navigation-bar li a.active').removeClass('active');
            $('.top-navigation-bar li a.current').removeClass('current');
            $(this).find('a:first').addClass('active');
            $(e.target).addClass("current");
        });
        $(document).on('click', 'li[id^="parent_menu"] .sub li', function (e) {
            $('.top-navigation-bar li a.active').removeClass('active');
            $('.top-navigation-bar li a.current').removeClass('current');
            $(this).parents().find('li[id^="parent_menu"]').find('a:first').addClass('active');
            $(e.target).addClass("current");
        });
        $(document).on('hover', '#navTopMenu li[id^="parent_menu"]', function () {
            if (!$('#show_more_menus').hasClass('show')) {
                $('#show_more_menus').addClass('show');
                $('#vertical_dropdown_menu').hide();
            }
        });
    }
}
function renderResizeBlocks() {
    if (!el_theme_settings.frm_resizeblock) {
        return false;
    }
    var resize_boxes = $(".frm-resize-block").length, resize_height;
    if (resize_boxes <= 1) {
        return  false;
    }
    $(".frm-resize-block:eq(0)").find(".resize-box").each(function (j) {
        resize_height = [];
        for (var i = 0; i < resize_boxes; i++) {
            resize_height[i] = $(".frm-resize-block:eq(" + i + ")").find(".resize-box:eq(" + j + ")").find(".resize-content").height();
        }
        var max_height = Math.max.apply(null, resize_height);
        for (var i = 0; i < resize_boxes; i++) {
            $(".frm-resize-block:eq(" + i + ")").find(".resize-box:eq(" + j + ")").find(".resize-content").css({"min-height": max_height + "px"});
        }
    });
}
function animateHeaderContent(a, b, c, d, e) {
    var animate_json = {};
    //a => collapse button none
    //b => collapse button semi hide
    //c => collapse button full hide
    //d => collapse button show
    //e => grid search active or not
    var conta_left = 0;
    if ($(".pad-calc-container").length) {
        conta_left = parseInt($(".pad-calc-container").css("margin-left"));
    }
    var win_wid = $(window).width();
    switch (el_tpl_settings.admin_theme) {
        case "cit":
            if (e == true) {
                animate_json['satop'] = -1;
                animate_json['salef'] = 171;
            } else {
                animate_json['satop'] = -37;
            }
            if (a == true) {
                if (e == true || e == false) {
                    if (e == true) {
                        animate_json['fhpad'] = conta_left;
                    } else {
                        animate_json['salef'] = 17;
                        animate_json['fhpad'] = conta_left + 30;
                    }
                } else {
                    animate_json['fhpad'] = conta_left;
                }
                animate_json['mdmgn'] = 0;
            } else if (b == true) {
                if (e == true || e == false) {
                    if (e == true) {
                        animate_json['fhpad'] = conta_left + 50;
                    } else {
                        animate_json['salef'] = 17;
                        animate_json['fhpad'] = conta_left + 30 + 50;
                    }
                } else {
                    animate_json['fhpad'] = conta_left + 50;
                }
                animate_json['mdmgn'] = 50;
            } else if (c == true) {
                if (e == true || e == false) {
                    if (e == true) {
                        animate_json['fhpad'] = conta_left + 30;
                    } else {
                        animate_json['salef'] = 46;
                        animate_json['fhpad'] = conta_left + 30 + 30;
                    }
                } else {
                    animate_json['fhpad'] = conta_left + 32;
                }
                animate_json['mdmgn'] = 0;
            } else if (d == true) {
                if (e == true || e == false) {
                    if (e == true) {
                        animate_json['fhpad'] = conta_left + 210;
                    } else {
                        animate_json['salef'] = 17;
                        animate_json['fhpad'] = conta_left + 210 + 30;
                    }
                } else {
                    animate_json['fhpad'] = conta_left + 210;
                }
                animate_json['mdmgn'] = 210;
            }
            animate_json['tfmin'] = parseFloat(0.91 / 100 * win_wid).toFixed(2);
            animate_json['tlmin'] = 17;
            break;
        default:
            //metronic & default
            conta_left = 2;
            if (e == true) {
                animate_json['satop'] = 2;
                animate_json['salef'] = 152;
            } else {
                animate_json['satop'] = -33;
            }
            if (a == true) {
                if (e == true || e == false) {
                    if (e == true) {
                        animate_json['fhpad'] = conta_left;
                    } else {
                        animate_json['salef'] = 2;
                        animate_json['fhpad'] = conta_left + 30;
                    }
                } else {
                    animate_json['fhpad'] = conta_left;
                }
                animate_json['mdmgn'] = conta_left;
            } else if (b == true) {
                if (e == true || e == false) {
                    if (e == true) {
                        animate_json['fhpad'] = conta_left + 55;
                    } else {
                        animate_json['salef'] = 7;
                        animate_json['fhpad'] = conta_left + 30 + 55;
                    }
                } else {
                    animate_json['fhpad'] = conta_left + 55;
                }
                animate_json['mdmgn'] = 50;
            } else if (c == true) {
                if (e == true || e == false) {
                    if (e == true) {
                        animate_json['fhpad'] = conta_left + 40;
                    } else {
                        animate_json['salef'] = 42;
                        animate_json['fhpad'] = conta_left + 30 + 40;
                    }
                } else {
                    animate_json['fhpad'] = conta_left + 40;
                }
                animate_json['mdmgn'] = 0;
            } else if (d == true) {
                if (e == true || e == false) {
                    if (e == true) {
                        animate_json['fhpad'] = conta_left + 215;
                    } else {
                        animate_json['salef'] = 7;
                        animate_json['fhpad'] = conta_left + 215 + 30;
                    }
                } else {
                    animate_json['fhpad'] = conta_left + 215;
                }
                animate_json['mdmgn'] = 210;
            }
            if ($(".top-list-tab-layout").length) {
                animate_json['tfmin'] = 0;
                animate_json['tlmin'] = 0;
            } else {
                animate_json['tfmin'] = (parseFloat(0.91 / 100 * win_wid)).toFixed(2);
                animate_json['tlmin'] = 17;
            }
            break;
    }
    var hrt_wt = 0;
    if ($(".header-right-drops").length) {
        hrt_wt = $(".header-right-drops").width();
    } else if ($(".header-right-btns").length) {
        hrt_wt = $(".header-right-btns").width();
    }
    animate_json['fhwid'] = win_wid - hrt_wt - animate_json['fhpad'] - animate_json['tfmin'] - 40;

    return animate_json;
}
function getResizedSubTabs() {
    if (!$('.module-navigation-tabs .nav-tabs').length) {
        return false;
    }
    var js_top_width = 0;
    var js_top_width_arr = [];
    $('.module-navigation-tabs .nav-tabs li').each(function () {
        js_top_width = js_top_width + parseFloat($(this).outerWidth());
        js_top_width_arr.push($(this).outerWidth());
    })
    var js_remain_width = parseFloat($(window).width()) - 100;
    if ($('#left_mainnav').length) {
        js_remain_width = parseFloat(js_remain_width) - parseFloat($('#left_mainnav').outerWidth());
    }
    /*if($('#left_search_panel').length){
     js_remain_width = parseFloat(js_remain_width) - parseFloat($('#left_search_panel').outerWidth());
     }*/
    var js_check_top_value = 0;
    var js_new_arr = [];
    for (i = 0; i < js_top_width_arr.length; i++) {
        js_check_top_value = parseFloat(js_check_top_value) + parseFloat(js_top_width_arr[i]);
        if (parseFloat(js_check_top_value) > parseFloat(js_remain_width)) {
            break;
        }
        js_new_arr.push(js_top_width_arr[i]);
    }

    if (js_new_arr.length > 0 && js_top_width_arr.length != js_new_arr.length) {
        var $index_val = parseFloat(js_new_arr.length) - 1;
        var js_html_top = $('.module-navigation-tabs .nav-tabs').find('li:gt(' + $index_val + ')');
        $('.module-navigation-tabs .nav-tabs').find('li:gt(' + $index_val + ')').remove()
        $('.module-navigation-tabs .nav-tabs').append('<li class="vertical-li-more-tabs" id="li_more_tabs"><a id="show_more_tabs" class="show" href="javascript://"><i class="minia-icon-list-3"></i><i class="icon16 icomoon-icon-arrow-down-2"></i></a></li>')
        $('#li_more_tabs').append('<ul id="new_tabs_dropdown" class="vertical-navigation-tabs" style="display:none"></ul>')
        $('#new_tabs_dropdown').html(js_html_top)
        $('#show_more_tabs').click(function () {
            if ($('#show_more_tabs').hasClass('show')) {
                $('#show_more_tabs').removeClass('show');
                $('#new_tabs_dropdown').show();
            } else {
                $('#show_more_tabs').addClass('show');
                $('#new_tabs_dropdown').hide();
            }
        });
        $(document).click(function (e) {
            if (!$(e.target).parents().hasClass('vertical-li-more-tabs') && !$('#show_more_tabs').hasClass('show')) {
                $('#show_more_tabs').addClass('show');
                $('#new_tabs_dropdown').hide();
            }
        });
    }
}
function getAdminJSFormat(type, key) {
    var fmt = '';
    switch (type) {
        case 'date':
            fmt = el_tpl_settings.admin_formats['date']['format']['dateFormat'];
            break;
        case 'date_and_time':
            if (key && key != '') {
                fmt = el_tpl_settings.admin_formats['date_and_time']['format'][key];
            } else {
                fmt = el_tpl_settings.admin_formats['date_and_time']['format']['dateFormat'];
            }
            break;
        case 'time':
            if (key && key != '') {
                fmt = el_tpl_settings.admin_formats['time']['format'][key];
            } else {
                fmt = el_tpl_settings.admin_formats['time']['format']['timeFormat'];
            }
            break;
        case 'phone':
            fmt = el_tpl_settings.admin_formats['phone_format'];
            break;
    }
    return fmt;
}
function getAdminJSMoment(type) {
    var fmt = '';
    switch (type) {
        case 'date':
            fmt = el_tpl_settings.admin_formats['date']['moment'];
            break;
        case 'date_and_time':
            fmt = el_tpl_settings.admin_formats['date_and_time']['moment'];
            break;
        case 'time':
            fmt = el_tpl_settings.admin_formats['time']['moment'];
            break;
    }
    return fmt;
}
function prepareHASHParamsURL(params) {
    var str = '';
    if (!params || !$.isPlainObject(params) || $.isEmptyObject(params)) {
        return str;
    }
    str = '';
    for (var i in params) {
        str += "|" + i + '|' + params[i];
    }
    return str;
}
function prepareQueryParamsURL(params) {
    var str = '';
    if (!params || !$.isPlainObject(params) || $.isEmptyObject(params)) {
        return str;
    }
    str = '';
    for (var i in params) {
        str += "&" + i + '=' + params[i];
    }
    return str;
}
function handleAjaxSubmitErrors(xhr) {
    if (xhr.getResponseHeader('Cit-auth-requires') === '1') {
        if (!isFancyBoxActive()) {
            document.location.href = admin_url + "" + cus_enc_url_json["user_sess_expire"];
        } else {
            parent.document.location.href = admin_url + "" + cus_enc_url_json["user_sess_expire"];
        }
    }
    if (xhr.getResponseHeader('Cit-db-error') === '1') {
        getDBErrorNotifyScreen(xhr.getResponseHeader('Cit-db-efile'));
    }
}
function parseJSONString(plain_str) {
    var json_str;
    try {
        json_str = JSON.parse(plain_str);
    } catch (err) {

    }
    return json_str;
}

function getFancyboxTPLParams() {
    var error_1 = js_lang_label.GENERIC_THE_REQUESTED_CONTENT_CANNOT_BE_LOADED;
    if (!error_1) {
        error_1 = "The requested content cannot be loaded.";
    }
    var error_2 = js_lang_label.GENERIC_PLEASE_TRY_AGAIN_LATER;
    if (!error_2) {
        error_2 = "Please try again later.";
    }
    var close_btn = js_lang_label.GENERIC_JS_CLOSE;
    if (!close_btn) {
        close_btn = "Close";
    }
    var prev_btn = js_lang_label.GENERIC_JS_PREVIOUS;
    if (!prev_btn) {
        prev_btn = "Previous";
    }
    var next_btn = js_lang_label.GENERIC_JS_NEXT;
    if (!next_btn) {
        next_btn = "Next";
    }
    var tmpl_obj = {
        tpl: {
            error: '<p class="fancybox-error">' + error_1 + '<br/>' + error_2 + '</p>',
            closeBtn: '<a title="' + close_btn + '" class="fancybox-item fancybox-close fancybox-close-1" href="javascript:;"><i class="typ-icon-cross fancybox-close-icon"></i></a>',
            next: '<a title="' + next_btn + '" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
            prev: '<a title="' + prev_btn + '" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
        }
    }
    return tmpl_obj;
}
function setCustomDesignGridster() {
    var hmrg, vmrg, twd, gwd, cd_formGridster;
    hmrg = 5, vmrg = 5;
    twd = $('#scrollable_content:visible').width();
    twd = Math.floor(twd / 6);
    gwd = twd - hmrg - vmrg;
    
    if($('.custom-design-grid').length){
        cd_formGridster = $(".custom-design-grid > div.custom-design-container").gridster({
            widget_base_dimensions: [gwd, 50],
            widget_selector: 'div.custom-design-block',
            max_rows: 5000,   
            widget_margins: [hmrg, vmrg],
            resize: {
                enabled: false,
                min_size: [2, 4],
            }
        }).data('gridster').disable();
    }
}
