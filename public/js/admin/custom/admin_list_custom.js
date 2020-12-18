function adminDataAfterLoad() {
    if($('.grid-profile').length)
    {
        $('.grid-profile').initial();
    }
}
$(document).off("click", ".grid-footer-edit");
$(document).on("click", ".grid-footer-edit", function (e) {
    var edit = $(this).parents('.grid-card').find('a.inline-edit-link').attr('href');
    $(this).attr('href', edit);
});
$(document).off("click", ".grid-footer-login-as");
$(document).on("click",".grid-footer-login-as", function (e) {
    var login_as = $(this).parents('.grid-card').find('.grid-login-as a').attr('href');
    if (typeof login_as != 'undefined') {
        $(this).attr('href', login_as);
    } else {
        e.preventDefault();
    }
});
$(document).off("click", ".grid-footer-message");
$(document).on("click",".grid-footer-message", function (e) {
    e.preventDefault();
    var curr_user = $(this).parents('.grid-card').find('.curr_user').val();
    if (curr_user == 0) {
        var message = $(this).parents('.grid-card').find('.grid-message-popup');
        $(message).toggleClass('showHide');
        $(this).toggleClass('active');
        $(this).parents('.grid-card').find('.grid-message-content').focus();
    }
});
$(document).off("click", ".grid-footer-notification");
$(document).on("click",".grid-footer-notification", function (e) {
    var curr_user = $(this).parents('.grid-card').find('.curr_user').val();
    if (curr_user == 0) {
        e.preventDefault();
    }
});
$(document).off("click", ".grid-send-message");
$(document).on("click",".grid-send-message", function (e) {
    e.preventDefault();
    var message_element = $(this).parents('.grid-card').find('.grid-message-popup');
    var admin_id = $(this).parent().find('.admin_id').val();
    var message = $(this).parent().find('.grid-message-content').val();
    if (message == '') {
        alert('Please enter message');
    } else {
        $.ajax({
            url: admin_url + cus_enc_url_json.send_message,
            type: 'POST',
            data: {
                'admin_id': admin_id,
                'message': message
            },
            success: function (response) {
                var res_arr = parseJSONString(response);
                $(message_element).toggleClass('showHide');
                Project.setMessage(res_arr.message, res_arr.success);
            }
        });
    }
    $(this).parent().find('.grid-message-content').val('');
});
