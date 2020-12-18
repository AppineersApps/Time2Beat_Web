$(document).ready(function () {
    if ($("#patternLock").val() == "yes") {
        $("#vConfirmPassword").addClass('ignore-valid');
    } else {
        $("#vConfirmPassword").removeClass('ignore-valid');
    }

    if ($("#patternLock").val() == "yes") {
        function setOldPattern(pattern) {
            if (pattern.length) {
                $("#vOldPassword").val(pattern.join(""));
            }
        }
        $('#old_passwd_div').pattern({
            stop: function (event, ui) {
                setOldPattern(ui.pattern);
            }
        });
        $("#vOldPassword").css('display', 'none');


        function setPattern(pattern) {
            if (pattern.length) {
                $("#vPassword").val(pattern.join(""));
            }
        }
        $('#passwd_div').pattern({
            stop: function (event, ui) {
                setPattern(ui.pattern);
            }
        });
        $("#vPassword").css('display', 'none');
    }
});

function getValidateField() {
    $('#frmchangepassword').validate({
        ignore: '.ignore-valid',
        rules: {
            vOldPassword: {
                required: true
            },
            vPassword: {
                required: true
            },
            vConfirmPassword: {
                required: true,
                equalTo: "#vPassword"
            }
        },
        messages: {
            vOldPassword: {
                required: js_lang_label.GENERIC_PLEASE_ENTER_OLD_PASSWORD
            },
            vPassword: {
                required: js_lang_label.GENERIC_PLEASE_ENTER_NEW_PASSWORD
            },
            vConfirmPassword: {
                required: js_lang_label.GENERIC_PLEASE_REENTER_NEW_PASSWORD,
                equalTo: js_lang_label.GENERIC_PASSWORD_DOES_NOT_MATCH
            }
        },
        errorPlacement: function (error, element) {
            if (element.attr("name") == "vOldPassword") {
                var jd_id = element.attr("id");
                error.appendTo("#" + jd_id + "Err");
            }
            if (element.attr("name") == "vPassword") {
                var jd_id = element.attr("id");
                error.appendTo("#" + jd_id + "Err");
            }
            if (element.attr("name") == "vConfirmPassword") {
                var jd_id = element.attr("id");
                error.appendTo("#" + jd_id + "Err");
            }
        },
        submitHandler: function () {
            var options = {
                url: jajax_action_url,
                beforeSubmit: showAdminAjaxRequest,
                success: function (respText, statText, xhr, $form) {
                    var resArr = $.parseJSON(respText);
                    if (resArr.success == "0") {
                        responseAjaxDataSubmission(resArr);
                        return false;
                    } else {
                        if (isFancyBoxActive()) {
                            parent.responseAjaxDataSubmission(resArr);
                            parent.$.fancybox.close();
                        } else {
                            responseAjaxDataSubmission(resArr);
                            window.location.hash = cus_enc_url_json['dashboard_sitemap'];
                        }
                    }
                }
            };
            $('#frmchangepassword').ajaxSubmit(options);
        }
    });
}

function closeWindow() {
    if (isFancyBoxActive()) {
        parent.$.fancybox.close();
    } else {
        window.location.hash = cus_enc_url_json['dashboard_sitemap'];
    }
}