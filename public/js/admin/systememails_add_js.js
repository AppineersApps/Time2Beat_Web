/** systememails module script */
Project.modules.systememails = {
    init: function() {
        
        valid_more_elements = [];
        
        
    },
    validate: function (){
        
        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore:".ignore-valid, .ignore-show-hide",
            
            errorPlacement : function(error, element) {
                switch(element.attr("name")){
                    
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
                        removeIndividualTinyMCEEditor('mse_email_message');
                        $('#mse_email_message').tinymce({
                            body_class : 'notranslate', 
script_url : el_tpl_settings.editor_js_url+'tinymce.min.js', 
content_css : el_tpl_settings.editor_css_url+'style.css', 
valid_elements : '*[*]', 
theme : 'modern', 
skin : 'light', 
height : 200, 
width : '51%', 
resize : 'both',
                            plugins: tinymce_editor_plugins_basic,
                            toolbar: tinymce_editor_tollbar_basic,
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
    toggleEvents: function(){
        
    },
    dropdownLayouts:function(elem){
        
    }
}
Project.modules.systememails.init();
