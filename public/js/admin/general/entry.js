$(document).ready(function() {
    $('#login_name').focus();
    var js_curr_hash = window.location.hash;
    $('#handle_url').val(js_curr_hash);
    $('#username').keyup(function(e) {
        if ($(this).val() != '') {
            $('#usernameErr').html('');
            $('#username').addClass('forgot-valid');
            $('#username').removeClass('forgot-err');
        } else {
            $('#usernameErr').html('<div class="err">' + js_lang_label.GENERIC_FORGOT_PASSWORD_USERNAME_ERR + '</div>');
            $('#username').removeClass('forgot-valid');
            $('#username').addClass('forgot-err');
        }
        if (e.which == '13') {
            validateSendForgotPassword();
        }
    });
    if (is_pattern == "yes") {
        $("#secretlogin").change(function() {
            if ($("#secretlogin").is(":checked")) {
                $('#passwd-div').pattern('clearPattern', true);
                $('#passwd-div').pattern({
                    showPattern: false
                });
            } else {
                $('#passwd-div').pattern({
                    showPattern: true
                });
            }
        })
        function setPattern(pattern) {
            if (pattern.length) {
                $("#passwd").val(pattern.join(""));
                if (!login(document.frmlogin)) {
                    $('#passwd-div').pattern('clearPattern', true)
                }
            }
        }
        $('#passwd-div').pattern({
            stop: function(event, ui) {
                setPattern(ui.pattern);
            }
        });
        $("#passwd").css('display', 'none');
    }
    $("#frmlogin").validate({
        onfocusout: false,
        ignore: ".ignore-valid, .ignore-show-hide",
        rules: {
            "login_name": {
                "required": true
            },
            "passwd": {
                "required": true
            }
        },
        messages: {
            "login_name": {
                "required": js_lang_label.GENERIC_LOGIN_USERNAME_ERR
            },
            "passwd": {
                "required": js_lang_label.GENERIC_LOGIN_PASSWORD_ERR
            }
        },
        errorPlacement: function(error, element) {
            if (element.attr("name")) {
                $('#' + element.attr('id') + 'Err').html(error);
            }
        },
        invalidHandler: function(form, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {
                validator.errorList[0].element.focus();
            }
        }
    })
        
    $('#pwd_show_hide').on( "click", function() {
        var status = $('#pwd_icon').attr('status');
        if(status == 'hide'){
            $('#pwd_icon').removeClass('fa-eye-slash').addClass('fa-eye').attr('status', 'show');
            $('#passwd').attr('type', 'text');
        }else{
            $('#pwd_icon').removeClass('fa-eye').addClass('fa-eye-slash').attr('status', 'hide');
            $('#passwd').attr('type', 'password');
        }
    });
});
$('#frmlogin').keyup(function (e) {
    if (e.keyCode === 13) {
        login();
    }
});
function login(frm) {
    if (!$("#frmlogin").valid()) {
        return false;
    } else {
        $("#frmlogin").submit();
        return true;
    }
}
function hideForgotPassword() {
    $('#username').val('');
    $('#forgot_div').hide();
    $('#login_div').show();
    $('#login_name').focus();
}
function showForgotPassword() {
    $('#login_div').hide();
    $('#forgot_div').show();
    $('#username').focus();

}
function validateSendForgotPassword() {
    var js_username = $('#username').val();
    if (js_username == '') {
        $('#username').removeClass('forgot-valid');
        $('#username').addClass('forgot-err');
        $('#usernameErr').html('<div class="err">' + js_lang_label.GENERIC_FORGOT_PASSWORD_USERNAME_ERR + '</div>');
        return false;
    } else {
        $('#username').addClass('forgot-valid');
        $('#username').removeClass('forgot-err');
        $('#usernameErr').html('');
        $('#send_button').hide();
        $('#loader_img').show();
        $.post(forgot_pwd_url, {
            'username': js_username
        }, function(response) {
            var resArr = $.parseJSON(response);
            if (resArr.success == "1") {
                hideForgotPassword();
            }
            $('#send_button').show();
            $('#loader_img').hide();
            alert(resArr.message);
        });
    }
    return false;
}