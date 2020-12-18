/** mobile_applications module script */
Project.modules.mobile_applications = {
    init: function() {
        
        valid_more_elements = [];
        
        
    },
    validate: function (){
        
        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore:".ignore-valid, .ignore-show-hide",
            rules : {
		    "mam_application_name": {
		        "required": true
		    },
		    "mam_device_type": {
		        "required": true
		    },
		    "mam_status": {
		        "required": true
		    }
		},
            messages : {
		    "mam_application_name": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.MOBILE_APPLICATIONS_APPLICATION_NAME)
		    },
		    "mam_device_type": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.MOBILE_APPLICATIONS_DEVICE_TYPE)
		    },
		    "mam_status": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.MOBILE_APPLICATIONS_STATUS)
		    }
		},
            errorPlacement : function(error, element) {
                switch(element.attr("name")){
                    
                        case 'mam_application_name':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'mam_device_type':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'mam_status':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                    default:
                        printErrorMessage(element, valid_more_elements, error);
                        break;
                }
                
            },
            invalidHandler: function(form, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {                    
                    validator.errorList[0].element.focus();
                }
            },
            submitHandler: function (form) {
                getAdminFormValidate();
                return false;
            }
        });
        
    },
    callEvents: function() {
        this.validate();
        this.initEvents();
        this.CCEvents();
        callGoogleMapEvents();
        
    },
    callChilds: function(){
        
        callGoogleMapEvents();
    },
    initEvents: function(elem){
        
    },
    childEvents: function(elem, eleObj){
        
    },
    CCEvents: function(){
        
    }
}
Project.modules.mobile_applications.init();
