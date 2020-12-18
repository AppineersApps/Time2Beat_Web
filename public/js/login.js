Project.modules.login = (function () {
    var objReturn = {};
    
    function init() {
        Common.initValidator();
        setLoginValidate();
    }
    function setLoginValidate() {
        $('#login-form-normal').validate({
            rules: {
                'username': {
                    required: true,
                },
                'password': {
                    required: true
                }
            },
            messages: {
                'username': {
                    required: 'Please enter User Name'
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