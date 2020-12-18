/** release_notes_details module script */
Project.modules.release_notes_details = {
    init: function() {
        
        valid_more_elements = [];
        
        
    },
    validate: function (){
        
        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore:".ignore-valid, .ignore-show-hide",
            rules : {
		    "mrnd_release_notes_id": {
		        "required": true
		    },
		    "mrnd_title": {
		        "required": true
		    },
		    "mrnd_version_status": {
		        "required": true
		    }
		},
            messages : {
		    "mrnd_release_notes_id": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.RELEASE_NOTES_DETAILS_RELEASE_NOTES)
		    },
		    "mrnd_title": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.RELEASE_NOTES_DETAILS_TITLE)
		    },
		    "mrnd_version_status": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.RELEASE_NOTES_DETAILS_TYPE)
		    }
		},
            errorPlacement : function(error, element) {
                switch(element.attr("name")){
                    
                        case 'mrnd_release_notes_id':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'mrnd_title':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'mrnd_version_status':
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
        
            
                        tinyMCE.baseURL = el_tpl_settings.editor_js_url;
                        removeIndividualTinyMCEEditor('mrnd_description');
                        $('#mrnd_description').tinymce({
                            body_class : 'notranslate', 
script_url : el_tpl_settings.editor_js_url+'tinymce.min.js', 
content_css : el_tpl_settings.editor_css_url+'style.css', 
valid_elements : '*[*]', 
theme : 'modern', 
skin : 'light', 
height : 200, 
width : '91%', 
resize : 'both',
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
                        
    },
    childEvents: function(elem, eleObj){
        
    },
    CCEvents: function(){
        
    }
}
Project.modules.release_notes_details.init();
