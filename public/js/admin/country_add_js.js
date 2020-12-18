/** country module script */
Project.modules.country = {
    init: function() {
        
        valid_more_elements = [];
        
        
    },
    validate: function (){
        
        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore:".ignore-valid, .ignore-show-hide",
            rules : {
		    "mc_country": {
		        "required": true
		    },
		    "mc_country_code": {
		        "required": true,
		        "minlength": "2",
		        "maxlength": "2"
		    },
		    "mc_country_code_i_s_o_3": {
		        "required": true
		    },
		    "mc_status": {
		        "required": true
		    }
		},
            messages : {
		    "mc_country": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.COUNTRY_COUNTRY)
		    },
		    "mc_country_code": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.COUNTRY_COUNTRY_CODE),
		        "minlength": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_MINIMUM_LENGTH_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.COUNTRY_COUNTRY_CODE),
		        "maxlength": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_MAXIMUM_LENGTH_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.COUNTRY_COUNTRY_CODE)
		    },
		    "mc_country_code_i_s_o_3": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.COUNTRY_COUNTRY_CODE_ISO_C453)
		    },
		    "mc_status": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.COUNTRY_STATUS)
		    }
		},
            errorPlacement : function(error, element) {
                switch(element.attr("name")){
                    
                        case 'mc_country':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'mc_country_code':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'mc_country_code_i_s_o_3':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'mc_status':
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
        this.toggleEvents();
        callGoogleMapEvents();
        
    },
    callChilds: function(){
        
        callGoogleMapEvents();
    },
    initEvents: function(elem){
        
            $('#mc_description').elastic();
    },
    childEvents: function(elem, eleObj){
        
    },
    toggleEvents: function(){
        
    },
    dropdownLayouts:function(elem){
        
    }
}
Project.modules.country.init();
