/** release_notes module script */
Project.modules.release_notes = {
    init: function() {
        
        valid_more_elements = ["child[release_notes_details][mrnd_title]","child[release_notes_details][mrnd_description]","child[release_notes_details][mrnd_version_status]"];
        
        
    },
    validate: function (){
        
        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore:".ignore-valid, .ignore-show-hide",
            rules : {
		    "mrn_version_number": {
		        "required": true
		    },
		    "mrn_release_date": {
		        "required": true
		    }
		},
            messages : {
		    "mrn_version_number": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.RELEASE_NOTES_VERSION_NUMBER)
		    },
		    "mrn_release_date": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.RELEASE_NOTES_RELEASE_DATE)
		    }
		},
            errorPlacement : function(error, element) {
                switch(element.attr("name")){
                    
                        case 'mrn_version_number':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'mrn_release_date':
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
        
            this.childEvents("release_notes_details", "#child_module_release_notes_details");
        callGoogleMapEvents();
    },
    initEvents: function(elem){
        
            
                        $('#mrn_release_date').datepicker({
                            dateFormat : getAdminJSFormat('date'), 
showOn : 'focus', 
changeMonth : true, 
changeYear : true, 
yearRange : 'c-100:c+100',
                            beforeShow: function(input, inst) {
                                var cal = inst.dpDiv;
                                var left = ($(this).offset().left + $(this).outerWidth()) - cal.outerWidth();
                                setTimeout(function() {
                                    cal.css({
                                        'left': left
                                    });
                                }, 10);
                            }
                        });
                        if(el_general_settings.mobile_platform){
                            $('#mrn_release_date').attr('readonly', true);
                        }
                        
            this.childEvents("release_notes_details", "#child_module_release_notes_details");
    },
    childEvents: function(elem, eleObj){
        switch(elem){
                
                case "release_notes_details" :
                    var is_popup = $("#childModulePopup_release_notes_details").val();
                    if(is_popup != "Yes"){
                        
                if($("[name^='child[release_notes_details][mrnd_title]']").length){
                    $("[name^='child[release_notes_details][mrnd_title]']").each(function(){
                        $(this).rules("add", {
		    "required": true,
		    "messages": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.RELEASE_NOTES_DETAILS_TITLE)
		    }
		}
                        );
                    });
                }
                if($("[name^='child[release_notes_details][mrnd_version_status]']").length){
                    $("[name^='child[release_notes_details][mrnd_version_status]']").each(function(){
                        $(this).rules("add", {
		    "required": true,
		    "messages": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.RELEASE_NOTES_DETAILS_TYPE)
		    }
		}
                        );
                    });
                }
                
            
                    tinyMCE.baseURL = el_tpl_settings.editor_js_url;
                    $(eleObj).find("[name^='child[release_notes_details][mrnd_description]']").each(function(){
                        $(this).tinymce({
                            body_class : 'notranslate', 
script_url : el_tpl_settings.editor_js_url+'tinymce.min.js', 
content_css : el_tpl_settings.editor_css_url+'style.css', 
valid_elements : '*[*]', 
theme : 'modern', 
height : 200, 
width : '91%', 
resize : 'both', 
skin : 'light',
                            plugins: tinymce_editor_plugins,
                            toolbar: tinymce_editor_tollbar,
                            templates: tinymce_editor_templates,
                            setup: function(ed) {
                                ed.on('change', function(e) {
                                    tinyMCE.triggerSave();
                                });
                                ed.on('click', function(e) {
                                    tinyMCE.get(ed.id).focus();
                                });
                            }
                        });
                    });
                    
                    }
                    break;
            }
    },
    CCEvents: function(){
        
    }
}
Project.modules.release_notes.init();
