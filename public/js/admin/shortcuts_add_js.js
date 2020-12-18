/** shortcuts module script */
Project.modules.shortcuts = {
    init: function() {
                $(document).off("click", "[name='ms_shortcut_type']");
         
        valid_more_elements = [];
        
        
        cc_json_1 = [
	    {
	        "cond_type": "AND",
	        "show_list": [
	            {
	                "id": "sys_custom_menu"
	            }
	        ],
	        "hide_list": [
	            {
	                "id": "sys_custom_module"
	            },
	            {
	                "id": "sys_custom_code"
	            },
	            {
	                "id": "sys_custom_general"
	            }
	        ],
	        "cond_list": [
	            {
	                "id": "ms_shortcut_type",
	                "type": "radio_buttons",
	                "oper": "eq",
	                "value": [
	                    "Menu"
	                ]
	            }
	        ]
	    }
	];
        cc_json_2 = [
	    {
	        "cond_type": "AND",
	        "show_list": [
	            {
	                "id": "sys_custom_module"
	            }
	        ],
	        "hide_list": [
	            {
	                "id": "sys_custom_menu"
	            },
	            {
	                "id": "sys_custom_code"
	            },
	            {
	                "id": "sys_custom_general"
	            }
	        ],
	        "cond_list": [
	            {
	                "id": "ms_shortcut_type",
	                "type": "radio_buttons",
	                "oper": "eq",
	                "value": [
	                    "Module"
	                ]
	            }
	        ]
	    }
	];
        cc_json_3 = [
	    {
	        "cond_type": "AND",
	        "show_list": [
	            {
	                "id": "sys_custom_code"
	            }
	        ],
	        "hide_list": [
	            {
	                "id": "sys_custom_menu"
	            },
	            {
	                "id": "sys_custom_module"
	            },
	            {
	                "id": "sys_custom_general"
	            }
	        ],
	        "cond_list": [
	            {
	                "id": "ms_shortcut_type",
	                "type": "radio_buttons",
	                "oper": "eq",
	                "value": [
	                    "Custom"
	                ]
	            }
	        ]
	    }
	];
        cc_json_4 = [
	    {
	        "cond_type": "AND",
	        "show_list": [
	            {
	                "id": "sys_custom_general"
	            }
	        ],
	        "hide_list": [
	            {
	                "id": "sys_custom_menu"
	            },
	            {
	                "id": "sys_custom_module"
	            },
	            {
	                "id": "sys_custom_code"
	            }
	        ],
	        "cond_list": [
	            {
	                "id": "ms_shortcut_type",
	                "type": "radio_buttons",
	                "oper": "eq",
	                "value": [
	                    "General"
	                ]
	            }
	        ]
	    }
	];
        $(document).on("click", "[name='ms_shortcut_type']", function() {
            checkCCEventValues((cc_json_1).concat(cc_json_2).concat(cc_json_3).concat(cc_json_4));
        });
    },
    validate: function (){
        
        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore:".ignore-valid, .ignore-show-hide",
            rules : {
		    "ms_shortcut_key": {
		        "required": true
		    },
		    "ms_shortcut_name": {
		        "required": true
		    },
		    "ms_shortcut_type": {
		        "required": true
		    },
		    "sys_custom_menu": {
		        "required": true
		    },
		    "sys_custom_module": {
		        "required": true
		    },
		    "sys_custom_code": {
		        "required": true
		    },
		    "sys_custom_general": {
		        "required": true
		    }
		},
            messages : {
		    "ms_shortcut_key": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.SHORTCUTS_SHORTCUT_KEY)
		    },
		    "ms_shortcut_name": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.SHORTCUTS_SHORTCUT_NAME)
		    },
		    "ms_shortcut_type": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.SHORTCUTS_SHORTCUT_TYPE)
		    },
		    "sys_custom_menu": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.SHORTCUTS_MENU)
		    },
		    "sys_custom_module": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.SHORTCUTS_MODULE)
		    },
		    "sys_custom_code": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.SHORTCUTS_CUSTOM_CODE)
		    },
		    "sys_custom_general": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.SHORTCUTS_GENERAL)
		    }
		},
            errorPlacement : function(error, element) {
                switch(element.attr("name")){
                    
                        case 'ms_shortcut_key':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'ms_shortcut_name':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'ms_shortcut_type':
                            $('#ms_shortcut_typeErr').html(error);
                            break;
                        case 'sys_custom_menu':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'sys_custom_module':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'sys_custom_code':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'sys_custom_general':
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
        
    },
    childEvents: function(elem, eleObj){
        
    },
    toggleEvents: function(){
        
        pre_cond_code_arr.push((cc_json_1).concat(cc_json_2).concat(cc_json_3).concat(cc_json_4));
    },
    dropdownLayouts:function(elem){
        
    }
}
Project.modules.shortcuts.init();
