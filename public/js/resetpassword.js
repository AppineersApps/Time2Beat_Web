Project.modules.resetpassword = (function () {
    var objReturn = {}
    
    function init() {
        initialize();
    }

    function initialize()
    {
        var code = document.getElementById('code_val').value;

        var form = document.getElementById('reset_form');
        var new_password = document.getElementById('new_password');
        var confirm_password = document.getElementById('confirm_password');
        var new_pass_error = document.getElementById('new_pass_error');
        var confirm_pass_error = document.getElementById('confirm_pass_error');

        $("#code_val").remove();

        function validateNewPass()
        {
            var retval = true;
            var regularExpression=/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).{6,15}$/;
            if(new_password.value == '') 
            {
               
              new_pass_error.innerHTML = 'Please enter new password';
              retval = false;
            }
            else if(!regularExpression.test(new_password.value)){
                
                new_pass_error.innerHTML='Password must contain at least 6 to 15 characters, including lower case, upper case and atleast one number.';
                retval = false;
            }
            else
            {
             
              new_pass_error.innerHTML = '';
            }

            return retval;
        }

          
        function validateConfirmPass()
        {
            var retval = true;

            if(confirm_password.value == '') 
            {
              confirm_pass_error.innerHTML = 'Please enter confirm password';
              retval = false;
            }
            else if(confirm_password.value != new_password.value)
            {
              confirm_pass_error.innerHTML = 'Password and confirm password should be same';
              retval = false;
            }
            else
            {
              confirm_pass_error.innerHTML = '';
            }

            return retval;
        }


        new_password.onblur = validateNewPass;
        confirm_password.onblur = validateConfirmPass;

        form.onsubmit = function(e) {
            e.preventDefault();
            var submit = true;
            if(validateNewPass() == false) {
              submit = false;
            }
            if(validateConfirmPass() == false){
              submit = false;
            }
            if(submit == true)
            {
                
                 form.submit();
            }
        }
    }
    
    objReturn.init = init;
    return objReturn;
})();