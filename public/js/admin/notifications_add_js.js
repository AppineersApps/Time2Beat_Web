/** notifications module script */
Project.modules.notifications = {
    init: function() {
        
        valid_more_elements = [];
        
        
    },
    validate: function (){
        
        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore:".ignore-valid, .ignore-show-hide",
            rules : {
		    "men_receiver": {
		        "required": true
		    },
		    "men_notification_type": {
		        "required": true
		    },
		    "men_subject": {
		        "required": true
		    },
		    "men_content": {
		        "required": true
		    },
		    "men_error": {
		        "required": true
		    },
		    "men_status": {
		        "required": true
		    }
		},
            messages : {
		    "men_receiver": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.NOTIFICATIONS_RECEIVER)
		    },
		    "men_notification_type": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.NOTIFICATIONS_NOTIFICATION_TYPE)
		    },
		    "men_subject": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.NOTIFICATIONS_SUBJECT)
		    },
		    "men_content": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.NOTIFICATIONS_CONTENT)
		    },
		    "men_error": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.NOTIFICATIONS_ERROR)
		    },
		    "men_status": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.NOTIFICATIONS_STATUS)
		    }
		},
            errorPlacement : function(error, element) {
                switch(element.attr("name")){
                    
                        case 'men_receiver':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'men_notification_type':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'men_subject':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'men_content':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'men_error':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'men_status':
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
        
            $('#men_content').elastic();
            $('#men_error').elastic();
    },
    childEvents: function(elem, eleObj){
        
    },
    CCEvents: function(){
        
    }
}
Project.modules.notifications.init();