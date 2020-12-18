var time = parseInt(Number(new Date()) / (3600 * 24 * 1000));
var api_token = null;
$(function () {
    // login
    $('form.ws').submit(function (e) {
        var el = $(this);
        e.preventDefault();
        el.next().hide();
        el.next().next().hide();
        call($(this).attr('action'), $(this).serialize(), function (a, b, c) {
            wsresponse(el, a, b, c);
        }, function (a, b, c) {
            wsresponse(el, a, b, c);
        });
    });
});
function call(method, data, success_callback, error_callback) {
    if (typeof (data) == 'undefined')
        data = {};
    if (typeof (data) == 'string') {
        data += "&api_key=" + api_key + "&api_token=" + api_token;
    } else {
        data.api_key = api_key;
        data.api_token = api_token;
    }

    $.ajax({
        url: ws_base + method,
        type: "POST",
        dataType: 'json',
        data: data,
        crossDomain: true,
        error: function (jqXHR, textStatus, errorThrown) {
            if (typeof (error_callback) == 'function')
                error_callback(textStatus, errorThrown, jqXHR);
        },
        success: function (data, status, jqXHR) {
            if (typeof (success_callback) == 'function')
                success_callback(data, status, jqXHR);
        }
    });
}
function wsresponse(el, a, b, c) {
    var response;
    try {
        response = $.parseJSON(c.responseText)
        response = getwsjson(response);
        el.next().show().html(response);
    } catch (err) {
        response = c.responseText;
        el.next().next().show().contents().find("body").html(response);
        var fr_height = el.next().next().contents().find("html").outerHeight();
        el.next().next().height(fr_height + 12);
    }
}
function getwsjson(json) {
    if (typeof json != 'string') {
        json = JSON.stringify(json, undefined, 2);
    }
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}