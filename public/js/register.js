Project.modules.login = (function () {
    var objReturn = {}
    
    function init() {
        Common.initValidator();
        setRegisterValidate();
        setProfileValidate();
    }
    function setRegisterValidate() {
        $('#frmregister').validate({
            rules: {
                'first_name': {
                    required: true
                },
                'last_name': {
                    required: true
                },
                'username': {
                    required: true,
                    remote: {
                        url: site_url + "user/user/check_user_email",
                        type: "post"
                    }
                },
                'email': {
                    required: true,
                    email: true,
                    remote: {
                        url: site_url + "user/user/check_user_email",
                        type: "post"
                    }
                },
                'password': {
                    required: true
                }
            },
            messages: {
                'first_name': {
                    required: 'Please enter First Name'
                },
                'last_name': {
                    required: 'Please enter Last Name'
                },
                'username': {
                    required: 'Please enter User Name',
                    email: 'Please enter a valid username',
                    remote: 'Username already exists'
                },
                'email': {
                    required: 'Please enter Email',
                    email: 'Please enter valid email address',
                    remote: 'Email address already exists'
                },
                'password': {
                    required: 'Please enter Password'
                }
            }
        });
    }
    
    function setProfileValidate() {
        $('#frmprofile').validate({
            rules: {
                'first_name': {
                    required: true
                },
                'last_name': {
                    required: true
                },
                'password': {
                    required: true
                }
            },
            messages: {
                'first_name': {
                    required: 'Please enter First Name'
                },
                'last_name': {
                    required: 'Please enter Last Name'
                },
                'password': {
                    required: 'Please enter Password'
                }
            }
        });
    }
    objReturn.init = init;
    return objReturn;
})();
$('#scanqr_modal').on('click', function (e) {
    Project.showUILoader('body', { spinner:true, message:'We are generating QR code. Please wait...'});
    var mod_obj = $('#myModal');
    mod_obj.off('shown.bs.modal');
    mod_obj.modal('toggle');
    mod_obj.modal({backdrop: 'static', keyboard: false});
    mod_obj.on('shown.bs.modal', function (e) {
        var userId = $('#userId').val();
        $.ajax({
            url: site_url + 'auth_setup.html',
            type: 'POST',
            data: {
                'userId': userId
            },
            success: function (response) {
                Project.hideUILoader();
                $("#scanqr-model-content").html(response);
            },
        });
    });
});
