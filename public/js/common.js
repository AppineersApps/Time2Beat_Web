var Common = (function () {
    var objReturn = {};
    
    function initValidator() {
        if (typeof $.validator != 'undefined') {
            $.validator.setDefaults({
                errorClass: "help-block",
                errorElement: "span",
                highlight: function (element) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                unhighlight: function (element) {
                    $(element).closest('.form-group').removeClass('has-error');
                }
            });
        }
    }
    
    function stringReplace(label_str, find_str, replace_str) {
        var return_str = "";
        if (label_str) {
            if (find_str) {
                var regExp = new RegExp(find_str);
                replace_str = (replace_str) ? replace_str.toLowerCase() : '';
                label_str = label_str.replace(find_str, replace_str);
                return_str = label_str.replace(regExp, replace_str);
            }
        }
        return return_str;
    }
    
    objReturn.initValidator = initValidator;
    objReturn.stringReplace = stringReplace;
    
    return objReturn;
})();