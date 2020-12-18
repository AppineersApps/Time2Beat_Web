Project.modules.forgotpassword = (function () {
    var objReturn = {};
    
    function init() {
        Common.initValidator();
        setForgotPasswordValidate();
    }
    function setForgotPasswordValidate() {
        $('#forgotpassword-form-normal').validate({
            rules: {
                'user_name': {
                    required: true
                }
            },
            messages: {
                'user_name': {
                    required: 'Please enter User Name'
                }
            }
        });
    }
    
    objReturn.init = init;
    return objReturn;
})();