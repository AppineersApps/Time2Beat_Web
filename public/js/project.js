var Project = (function () {
    var objReturn = {}, widgets = {}, modules = {}, plugins = {}, timer = 7500;

    function init() {
        initWidgets();
        initModules();
        if ($('.listing-error').html() != '') {
            $('.listing-error').show();
            $(".listing-error").delay(10000).slideUp('slow');
        }
        $("textarea").css('resize', 'none');
        $("checkbox").css('border', 'none');
        $(document).on('click', '#cit-captcha-refresh', function () {
            refreshCaptcha();
        });
        checkmsg();
    }
    function initWidgets() {
        for (var widget in Project.widgets) {
            if (typeof (Project.widgets[widget].init) == 'function') {
                Project.widgets[widget].init();
            }
        }
    }
    function initModules() {
        for (var module in Project.modules) {
            if (typeof _module_vars == "object") {
                Project.modules[module].variables = _module_vars;
            }
            if (typeof (Project.modules[module].init) == 'function') {
                Project.modules[module].init();
            }
        }
        if (Project.modules.processAjax && Project.modules.processAjax.init && typeof Project.modules.processAjax.init == "function") {
            Project.modules.processAjax.init();
        }
    }
    function setMessage(msgText, msgClass, timeOut) {
        var cnt_class, close_class;
        if (msgClass == 0) {
            cnt_class = "alert-danger";
            close_class = 'error';
        } else if (msgClass == 1) {
            cnt_class = "alert-success";
            close_class = 'success';
        } else if (msgClass == 2) {
            cnt_class = "alert-info";
            close_class = 'success';
        } else {
            cnt_class = "";
            close_class = 'success';
        }
        $("#closebtn_errorbox").removeClass("success").removeClass("error").addClass(close_class);
        $('#err_msg_cnt').html(msgText).removeClass("alert-success").removeClass("alert-danger").removeClass("alert-info").addClass(cnt_class);
        var msg_type = getMessageStyle();
        if (msg_type == "toastr") {
            setFlashToToastr();
        } else {
            if ($.isNumeric(timeOut) && timeOut > 0) {
                setTimeout(function () {
                    $('#var_msg_cnt').fadeIn('slow');
                    setTimeout(function () {
                        closeMessage();
                    }, timer);
                }, timeOut);
            } else {
                $('#var_msg_cnt').fadeIn('slow');
                setTimeout(function () {
                    closeMessage();
                }, timer);
            }
        }
    }
    function closeMessage() {
        $('#var_msg_cnt').fadeOut('slow');
    }
    function checkmsg() {
        var message = $.trim($('#err_msg_cnt').text());
        if ($('#err_msg_cnt').length > 0 && message != '') {
            var msg_type = getMessageStyle();
            if (msg_type == "toastr") {
                setFlashToToastr();
            } else {
                $('#var_msg_cnt').fadeIn('slow');
                setTimeout(function () {
                    closeMessage();
                }, timer);
            }
        }
    }
    function getMessageStyle() {
        var msg_style = 'default';
        if (typeof front_tpl_settings != "undefined" && typeof front_tpl_settings == "object") {
            if ('flash_message_style' in front_tpl_settings) {
                if (front_tpl_settings['flash_message_style'] == "toastr") {
                    msg_style = 'toastr';
                }
            }
        }
        return msg_style;
    }

    function setFlashToToastr() {
        var message, options = {};
        message = $.trim($('#err_msg_cnt').text());
        options["closeButton"] = true;
        options["positionClass"] = "toast-top-right";
        options["timeOut"] = timer;
        if ($("#err_msg_cnt").hasClass("alert-error")) {
            showUIMessage('', message, 0, options);
        } else if ($("#err_msg_cnt").hasClass("alert-warning")) {
            showUIMessage('', message, 2, options);
        } else if ($("#err_msg_cnt").hasClass("alert-success")) {
            showUIMessage('', message, 1, options);
        } else {
            showUIMessage('', message, 3, options);
        }
    }
    function showUILoader(target, options) {
        var message, text, defaultCSS = {}, overlayCSS = {};
        options = (options) ? options : {};
        text = ($.trim(options.message)) ? options.message : "";
        if (options.style == "black") {
            if (options.spinner) {
                message = '<i class="fa fa-circle-o-notch bg-loader fa-spin fa-2x fa-fw"></i> ' + text
            } else {
                message = text
            }
            defaultCSS = {
                color: '#fff',
                border: 'none',
                backgroundColor: '#717171',
                padding: "8px 5px 8px 5px",
                'border-radius': '5px',
                '-webkit-border-radius': '5px',
                '-moz-border-radius': '5px'
            };
            overlayCSS = {
                backgroundColor: '#fff',
            }
        } else {
            if (options.spinner) {
                message = '<i class="fa fa-circle-o-notch bg-loader fa-spin fa-2x fa-fw margin-bottom"></i> ' + text;
            } else {
                message = text
            }
            defaultCSS = {
                color: '#000',
                border: '1px solid #aaaaaa',
                backgroundColor: '#fff',
                padding: "8px 5px 8px 5px",
                'border-radius': '5px',
                '-webkit-border-radius': '5px',
                '-moz-border-radius': '5px'
            };
        }
        delete options.style;
        delete options.spinner;

        options.message = message;
        if (options.css) {
            options.css = $.extend({}, defaultCSS, options.css);
        } else {
            options.css = defaultCSS;
        }
        if (options.overlayCSS) {
            options.overlayCSS = $.extend({}, overlayCSS, options.overlayCSS);
        } else {
            options.overlayCSS = overlayCSS;
        }
        if ($(target).length == 0) {
            target = 'body';
        }
        $(target).block(options);
    }
    function hideUILoader(target) {
        if ($(target).length == 0) {
            target = 'body';
        }
        $(target).unblock();
    }
    function showUIMessage(title, message, status, options) {
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
    function showSWMessage(title, message, status, options, data) {
        toastr.remove();
        var defOptions = {
            "closeButton": true,
            "positionClass": "toast-top-right"
        };
        options = (options) ? $.extend({}, defOptions, options) : $.extend({}, defOptions);
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
    function refreshCaptcha() {
        $('#cit-captcha-icon').addClass("fa-spin");
        $.ajax({
            url: site_url + "captcha.html",
            type: 'POST',
            data: {},
            success: function (response) {
                $('#cit-captcha-image').replaceWith(response);
            },
            complete: function () {
                $('#cit-captcha-icon').removeClass("fa-spin");
            }
        });
    }
    function setObjectKeyData(result, key, data) {
        if (!result) {
            result = {};
        }
        if (!$.isPlainObject(result)) {
            result = {};
        }
        if (!(key in result)) {
            result[key] = {};
        }
        result[key] = data;
        return result;
    }

    objReturn.widgets = widgets;
    objReturn.modules = modules;
    objReturn.plugins = plugins;
    objReturn.timer = timer;
    objReturn.init = init;
    objReturn.initWidgets = initWidgets;
    objReturn.initModules = initModules;
    objReturn.setMessage = setMessage;
    objReturn.closeMessage = closeMessage;
    objReturn.checkmsg = checkmsg;
    objReturn.showUILoader = showUILoader;
    objReturn.hideUILoader = hideUILoader;
    objReturn.showUIMessage = showUIMessage;
    objReturn.showSWMessage = showSWMessage;
    objReturn.refreshCaptcha = refreshCaptcha;
    objReturn.setObjectKeyData = setObjectKeyData;
    return objReturn;
})();
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
//service worker related
try {
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.addEventListener('message', function (event) {
            var data = event.data;
            Project.showSWMessage(data.title, data.message, data.status, data.options, data);
        });
    }
} catch (err) {
    console.log("[SW] is not available");
}