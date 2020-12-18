/** customers module script */
Project.modules.customers = {
    init: function() {
        
        valid_more_elements = [];
        
        
    },
    validate: function (){
        
        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore:".ignore-valid, .ignore-show-hide",
            rules : {
		    "mc_first_name": {
		        "required": true
		    },
		    "mc_last_name": {
		        "required": true
		    },
		    "mc_email": {
		        "required": true,
		        "email": true
		    },
		    "mc_user_name": {
		        "required": true
		    },
		    "mc_password": {
		        "required": true
		    },
		    "mc_status": {
		        "required": true
		    }
		},
            messages : {
		    "mc_first_name": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.CUSTOMERS_FIRST_NAME)
		    },
		    "mc_last_name": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.CUSTOMERS_LAST_NAME)
		    },
		    "mc_email": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.CUSTOMERS_EMAIL),
		        "email": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_VALID_EMAIL_ADDRESS_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.CUSTOMERS_EMAIL)
		    },
		    "mc_user_name": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.CUSTOMERS_USER_NAME)
		    },
		    "mc_password": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.CUSTOMERS_PASSWORD)
		    },
		    "mc_status": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.CUSTOMERS_STATUS)
		    }
		},
            errorPlacement : function(error, element) {
                switch(element.attr("name")){
                    
                        case 'mc_first_name':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'mc_last_name':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'mc_email':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'mc_user_name':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'mc_password':
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
        
            
                $('#upload_drop_zone_mc_profile_image').width($('#uploadify_mc_profile_image').width() + 18);
                $('#uploadify_mc_profile_image').fileupload({
                    url : el_form_settings.upload_form_file_url, 
name : 'mc_profile_image', 
temp : 'temp_mc_profile_image', 
paramName : 'Filedata', 
maxFileSize : '2048', 
acceptFileTypes : 'gif|png|jpg|jpeg',
                    dropZone: $('#upload_drop_zone_mc_profile_image, #upload_drop_zone_mc_profile_image + .upload-src-zone'),
                    formData: {
                        'unique_name' : 'mc_profile_image', 
                        'id' : $('#id').val(),
                        'type' : 'uploadify'
                    },
                    add: function(e, data) {
                        var upload_errors = [];
                        var _input_name = $(this).fileupload('option', 'name');
                        var _temp_name = $(this).fileupload('option', 'temp');
                        var _form_data = $(this).fileupload('option', 'formData');
                        var _file_size = $(this).fileupload('option', 'maxFileSize');
                        var _file_type = $(this).fileupload('option', 'acceptFileTypes');
                        
                        var _input_val = data.originalFiles[0]['name'];
                        var _input_size = data.originalFiles[0]['size'];
                        if(_file_type != '*'){
                            var _input_ext = (_input_val) ? _input_val.substr(_input_val.lastIndexOf('.')) : '';
                            var accept_file_types = new RegExp('(\.|\/)(' + _file_type + ')$', 'i');
                            if (_input_ext && !accept_file_types.test(_input_ext)) {
                                upload_errors.push(js_lang_label.ACTION_FILE_TYPE_IS_NOT_ACCEPTABLE);
                                var valid_ext = $('#' + _input_name).attr('aria-extensions');
                                if(valid_ext){
                                    upload_errors.push(js_lang_label.GENERIC_VALID_EXTENSIONS + ' : ' + valid_ext);
                                }
                            }
                        }
                        _file_size = _file_size * 1000;
                        if (_input_size && _input_size > _file_size) {
                            if(!upload_errors.length){
                                upload_errors.push(js_lang_label.ACTION_FILE_SIZE_IS_TOO_LARGE);
                                var valid_size = $('#' + _input_name).attr('aria-valid-size');
                                if(valid_size){
                                    upload_errors.push(js_lang_label.GENERIC_VALID_SIZE + ' : ' + valid_size);
                                }
                            }
                        }
                        if (upload_errors.length > 0) {
                            Project.setMessage(upload_errors.join('\n'), 0);
                        } else {
                            $('#practive_' + _input_name).css('width', '0%');
                            $('#progress_' + _input_name).show();
                            _form_data['oldFile'] = $('#' + _temp_name).val();
                            $(this).fileupload('option', 'formData', _form_data);
                            $('#preview_' + _input_name).html(data.originalFiles[0]['name']);
                            var xhr = data.submit();
                            $('#progress_' + _input_name + ' .upload-cancel').click(function (e) {
                                e.preventDefault();
                                xhr.abort();
                            });
                        }
                    },
                    done: function(e, data) {
                        if (data && data.result) {
                            var _input_name = $(this).fileupload('option', 'name');
                            var _temp_name = $(this).fileupload('option', 'temp');
                            var jparse_data = $.parseJSON(data.result);
                            if (jparse_data.success == '0') {
                                Project.setMessage(jparse_data.message, 0);
                            } else {
                                $('#' + _input_name).val(jparse_data.uploadfile);
                                $('#' + _temp_name).val(jparse_data.oldfile);
                                displayAdminOntheFlyImage(_input_name, jparse_data);
                                setTimeout(function() {
                                    $('#progress_' + _input_name).hide();
                                }, 1000);
                            }
                            
                        }
                    },
                    fail: function(e, data) {
                        if(data.textStatus == 'abort'){
                            data.messages.uploadedBytes = 'File Upload Cancelled';
                            var _input_name = $(this).fileupload('option', 'name');
                            $('#progress_' + _input_name).hide();
                        }
                        $.each(data.messages, function(index, error) {
                            Project.setMessage(error, 0);
                        });
                    },
                    progressall: function(e, data) {
                        var _input_name = $(this).fileupload('option', 'name');
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        $('#practive_' + _input_name).css('width', progress + '%');
                    }
                });
                
            
                        $('#mc_registered_date').datetimepicker({
                            dateFormat : getAdminJSFormat('date_and_time'), 
timeFormat : getAdminJSFormat('date_and_time','timeFormat'), 
showSecond : getAdminJSFormat('date_and_time','showSecond'), 
ampm : getAdminJSFormat('date_and_time','ampm'), 
controlType : 'slider', 
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
                            $('#mc_registered_date').attr('readonly', true);
                        }
                        
    },
    childEvents: function(elem, eleObj){
        
    },
    toggleEvents: function(){
        
    },
    dropdownLayouts:function(elem){
        
    }
}
Project.modules.customers.init();
