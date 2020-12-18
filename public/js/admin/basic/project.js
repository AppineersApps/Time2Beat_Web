var Project = {
    init: function () {
        this.initModules();
        if ($('.listing-error').html() != '') {
            $('.listing-error').show();
            $(".listing-error").delay(10000).slideUp('slow');
        }
        $("textarea").css('resize', 'none');
        $("checkbox").css('border', 'none');
    },
    timer: 7500,
    modules: {},
    initModules: function () {
        for (var module in Project.modules) {
            var id = (module || "").replace(/([A-Z])/g, '-$1').toLowerCase();
            id = id.substring(0, 1) == '-' ? id.substring(1) : id;
            if ($('#' + id).length && typeof (this.modules[module].init) == 'function') {
                Project.modules[module].init($('#' + id));
            }
        }
    },
    hide_adaxloading_div: function () {
        if ($('#body_ajaxloading_div').length) {
            $('#body_ajaxloading_div').remove();
        }
    },
    show_adaxloading_div: function () {
        var spinner_class = 'fa fa-cog fa-spin fa-32';
        if(typeof admin_spinner_class != 'undefined'){
            spinner_class = admin_spinner_class;
        }
        $('body').append('<div id="body_ajaxloading_div" align="center"><i class="' + spinner_class + '"></i></div>');
        $('#body_ajaxloading_div').css({
            position: 'absolute',
            zIndex: 10000,
            left: (el_general_settings.page_temp_left - 42) + 'px',
            top: (el_general_settings.page_temp_right - 18) + 'px'
        });
        setTimeout(function () {
            Project.hide_adaxloading_div()
        }, 20000);
    },
    setMessage: function (msgText, msgClass, timeOut) {
        var cnt_class, close_class;
        if (msgClass == 0) {
            cnt_class = "alert-error";
            close_class = 'error';
        } else if (msgClass == 1) {
            cnt_class = "alert-success";
            close_class = 'success';
        } else if (msgClass == 2) {
            cnt_class = "";
            close_class = 'success';
        } else {
            cnt_class = "alert-info";
            close_class = 'success';
        }
        $("#closebtn_errorbox").removeClass("success").removeClass("error").addClass(close_class);
        $('#err_msg_cnt').html(msgText).removeClass("alert-success").removeClass("alert-error").removeClass("alert-info").addClass(cnt_class);
        var msg_type = Project.getMessageStyle();
        if (msg_type == "toastr") {
            Project.setFlashToToastr();
        } else {
            if ($.isNumeric(timeOut) && timeOut > 0) {
                setTimeout(function () {
                    $('#var_msg_cnt').fadeIn('slow');
                    setTimeout(function () {
                        Project.closeMessage();
                    }, Project.timer);
                }, timeOut);
            } else {
                $('#var_msg_cnt').fadeIn('slow');
                setTimeout(function () {
                    Project.closeMessage();
                }, Project.timer);
            }
        }
    },
    closeMessage: function () {
        $('#var_msg_cnt').fadeOut('slow');
        return false;
    },
    checkmsg: function () {
        if ($('#err_msg_cnt').length > 0 && $.trim($('#err_msg_cnt').text()) != '') {
            $('#var_msg_cnt').fadeIn('slow');
            setTimeout(function () {
                Project.closeMessage();
            }, Project.timer);
        }
    },
    getMessageStyle: function () {
        var msg_style = 'default';
        console.log(el_tpl_settings['flash_message_style']);
        if (typeof el_tpl_settings != "undefined" && typeof el_tpl_settings == "object") {
            if ('flash_message_style' in el_tpl_settings) {
                if (el_tpl_settings['flash_message_style'] == "toastr") {
                    msg_style = 'toastr';
                }
            }
        }
        return msg_style;
    },
    setFlashToToastr: function () {
        var message, options = {};
        message = $.trim($('#err_msg_cnt').text());
        options["closeButton"] = true;
        options["positionClass"] = "toast-top-right";
        options["timeOut"] = Project.timer;
        if ($("#err_msg_cnt").hasClass("alert-error")) {
            Project.showUIMessage('', message, 0, options);
        } else if ($("#err_msg_cnt").hasClass("alert-warning")) {
            Project.showUIMessage('', message, 2, options);
        } else if ($("#err_msg_cnt").hasClass("alert-success")) {
            Project.showUIMessage('', message, 1, options);
        } else {
            Project.showUIMessage('', message, 3, options);
        }
    },
    showUIMessage: function (title, message, status, options) {
        options = (options) ? options : {};
        if (status == 0) {
            toastr.error(message, title, options);
        } else if (status == 1) {
            toastr.success(message, title, options);
        } else if (status == 2) {
            toastr.warning(message, title, options);
        } else {
            toastr.info(message, title, options);
        }
    }
};
var matched, browser;
jQuery.uaMatch = function (ua) {
    ua = ua.toLowerCase();
    var match = /(chrome)[ \/]([\w.]+)/.exec(ua) ||
            /(webkit)[ \/]([\w.]+)/.exec(ua) ||
            /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
            /(msie) ([\w.]+)/.exec(ua) ||
            ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) ||
            [];
    return {
        browser: match[ 1 ] || "",
        version: match[ 2 ] || "0"
    };
};
matched = jQuery.uaMatch(navigator.userAgent);
browser = {};
if (matched.browser) {
    browser[ matched.browser ] = true;
    browser.version = matched.version;
}
// Chrome is Webkit, but Webkit is also Safari.
if (browser.chrome) {
    browser.webkit = true;
} else if (browser.webkit) {
    browser.safari = true;
}
jQuery.browser = browser;