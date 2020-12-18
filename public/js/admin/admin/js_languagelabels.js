                
Project.modules.languagelabels = {
    init: function() {
        
        $.validator.addMethod("alpha_numeric_without_spaces", function(value, element) {
                return this.optional(element) || /^[0-9a-zA-Z_]+$/.test(value);
        }, ci_js_validation_message(js_lang_label.LANGUAGELABELS_PLEASE_ONLY_ENTER_LETTERS_AND_NUMBERS_WITHOUT_SPACE_FOR_THE_FIELD_FIELD ,"#FIELD#",js_lang_label.LANGUAGELABELS_LANGUAGE_LABEL));
        
    },
    validate: function (){
        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore:".ignore-valid, .ignore-show-hide",
            
            rules : {
		"mllt_label": {
				"required": true,
				"alpha_numeric_without_spaces": true
		},
		"mllt_page": {
				"required": true
		},
		"mllt_module": {
				"required": true
		},
		"mllt_value": {
				"required": true
		},
		"mllt_status": {
				"required": true
		}
},
            messages : {
		"mllt_label": {
				"required": ci_js_validation_message(js_lang_label.LANGUAGELABELS_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD ,
				"#FIELD#",
				js_lang_label.LANGUAGELABELS_LANGUAGE_LABEL),
				"alpha_numeric_without_spaces": ci_js_validation_message(js_lang_label.LANGUAGELABELS_PLEASE_ONLY_ENTER_LETTERS_AND_NUMBERS_WITHOUT_SPACE_FOR_THE_FIELD_FIELD ,
				"#FIELD#",
				js_lang_label.LANGUAGELABELS_LANGUAGE_LABEL)
		},
		"mllt_page": {
				"required": ci_js_validation_message(js_lang_label.LANGUAGELABELS_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD ,
				"#FIELD#",
				js_lang_label.LANGUAGELABELS_SELECT_PAGE)
		},
		"mllt_module": {
				"required": ci_js_validation_message(js_lang_label.LANGUAGELABELS_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD ,
				"#FIELD#",
				js_lang_label.LANGUAGELABELS_MODULE)
		},
		"mllt_value": {
				"required": ci_js_validation_message(js_lang_label.LANGUAGELABELS_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD ,
				"#FIELD#",
				js_lang_label.LANGUAGELABELS_VALUE)
		},
		"mllt_status": {
				"required": ci_js_validation_message(js_lang_label.LANGUAGELABELS_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD ,
				"#FIELD#",
				js_lang_label.LANGUAGELABELS_STATUS)
		}
},
            errorPlacement: function(error, element) { 
                if (element.attr('name') == 'mllt_label') { 
                    $('#'+element.attr('id')+'Err').html(error);
                }
                if (element.attr('name') == 'mllt_page') { 
                    $('#'+element.attr('id')+'Err').html(error);
                }
                if (element.attr('name') == 'mllt_module') { 
                    $('#'+element.attr('id')+'Err').html(error);
                }
                if (element.attr('name') == 'mllt_value') { 
                    $('#'+element.attr('id')+'Err').html(error);
                }
                if (element.attr('name') == 'mllt_status') { 
                    $('#'+element.attr('id')+'Err').html(error);
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
        this.initEvents();
        this.validate();
        this.CCEvents();
    },
    callChilds: function(){
        
    },
    initEvents: function(elem){
        
            $('#mllt_value').elastic();
            $('[id^="lang_mllt_value"]').elastic();
    },
    childEvents: function(elem, eleObj){
        
    },
    CCEvents: function(){
        
    }
}
Project.modules.languagelabels.init();