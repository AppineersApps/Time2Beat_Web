$(function () {
    tinyMCE.baseURL = el_tpl_settings.editor_js_url;
    $('#tNotificationContent').tinymce({
        body_class: "notranslate",
        script_url: el_tpl_settings.editor_js_url + 'tinymce.min.js',
        content_css: el_tpl_settings.editor_css_url + 'style.css',
        toolbar: false,
        menubar: false,
        statusbar: false,
        theme: 'modern',
        resize: 'both',
        skin: 'light',
        readonly: true
    });

    $("#resend").click(function () {
        Project.show_adaxloading_div();
        var rensend_id = $('#notification_log_id').val();
        $.ajax({
            type: "POST",
            url: cus_enc_url_json.resend_email,
            data: {
                rensend_id: rensend_id
            },
            success: function (response) {
                var result = JSON.parse(response);
                alert(result.message);
            },
            complete: function () {
                Project.hide_adaxloading_div();
            }
        });
    });
});
