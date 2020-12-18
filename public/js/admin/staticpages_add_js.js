/** staticpages module script */
Project.modules.staticpages = {
    init: function() {
        
        valid_more_elements = [];
        
        
    },
    validate: function (){
        
        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore:".ignore-valid, .ignore-show-hide",
            rules : {
		    "mps_page_code": {
		        "required": true
		    },
		    "mps_version": {
		        "required": true
		    }
		},
            messages : {
		    "mps_page_code": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.STATICPAGES_PAGE_CODE)
		    },
		    "mps_version": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.STATICPAGES_VERSION)
		    }
		},
            errorPlacement : function(error, element) {
                switch(element.attr("name")){
                    
                        case 'mps_page_code':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'mps_version':
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
        
            
                        tinyMCE.baseURL = el_tpl_settings.editor_js_url;
                        removeIndividualTinyMCEEditor('mps_content');
                        $('#mps_content').tinymce({
                            body_class : 'notranslate', 
script_url : el_tpl_settings.editor_js_url+'tinymce.min.js', 
content_css : el_tpl_settings.editor_css_url+'style.css', 
valid_elements : '*[*]', 
theme : 'modern', 
skin : 'light', 
height : 250, 
width : '51%', 
resize : 'both', 
relative_urls : false, 
remove_script_host : false, 
image_advtab : true, 
external_filemanager_path : site_url + 'filemanager/', 
filemanager_title : js_lang_label.GENERIC_RESPONSIVE_FILEMANAGER,
                            plugins: tinymce_editor_plugins_premium,
                            toolbar: tinymce_editor_tollbar_premium,
                            external_plugins: {"filemanager": el_tpl_settings.js_libraries_url + "filemanager/plugin.min.js"},
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
                        
            $('#mps_meta_title').elastic();
            $('#mps_meta_keyword').elastic();
            $('#mps_meta_desc').elastic();
    },
    childEvents: function(elem, eleObj){
        
    },
    toggleEvents: function(){
        
    },
    dropdownLayouts:function(elem){
        
    }
}
Project.modules.staticpages.init();
