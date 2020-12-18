/** application_versions module script */
Project.modules.application_versions = {
    init: function() {
                 $(document).off("change", "[name='mav_version_type']");
         
        valid_more_elements = [];
        
        
        cc_json_1 = [
	    {
	        "cond_type": "AND",
	        "show_list": [
	            {
	                "id": "sys_application_file"
	            }
	        ],
	        "hide_list": [
	            {
	                "id": "mav_application_url"
	            }
	        ],
	        "cond_list": [
	            {
	                "id": "mav_version_type",
	                "type": "dropdown",
	                "oper": "eq",
	                "value": [
	                    "File"
	                ]
	            }
	        ]
	    }
	];
        $(document).on("change", "[name='mav_version_type']", function() {
            checkCCEventValues(cc_json_1);
        });
    },
    validate: function (){
        
        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore:".ignore-valid, .ignore-show-hide",
            rules : {
		    "mav_version_name": {
		        "required": true
		    },
		    "mav_version_number": {
		        "required": true
		    },
		    "mav_application_url": {
		        "required": true,
		        "url": true
		    },
		    "sys_application_file": {
		        "required": true
		    },
		    "mav_date_published": {
		        "required": true
		    },
		    "mav_status": {
		        "required": true
		    }
		},
            messages : {
		    "mav_version_name": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.APPLICATION_VERSIONS_VERSION_NAME)
		    },
		    "mav_version_number": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.APPLICATION_VERSIONS_VERSION_NUMBER)
		    },
		    "mav_application_url": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.APPLICATION_VERSIONS_APPLICATION_URL),
		        "url": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_VALID_URL__FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.APPLICATION_VERSIONS_APPLICATION_URL)
		    },
		    "sys_application_file": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.APPLICATION_VERSIONS_APPLICATION_FILE)
		    },
		    "mav_date_published": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.APPLICATION_VERSIONS_PUBLISHED_ON)
		    },
		    "mav_status": {
		        "required": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.APPLICATION_VERSIONS_STATUS)
		    }
		},
            errorPlacement : function(error, element) {
                switch(element.attr("name")){
                    
                        case 'mav_version_name':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'mav_version_number':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'mav_application_url':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'sys_application_file':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'mav_date_published':
                            $('#'+element.attr('id')+'Err').html(error);
                            break;
                        case 'mav_status':
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
        
            
                $('#upload_drop_zone_sys_application_file').width($('#uploadify_sys_application_file').width() + 18);
                $('#uploadify_sys_application_file').fileupload({
                    url : el_form_settings.upload_form_file_url, 
name : 'sys_application_file', 
temp : 'temp_sys_application_file', 
paramName : 'Filedata', 
maxFileSize : '512000', 
acceptFileTypes : 'apk|ipa|zip',
                    dropZone: $('#upload_drop_zone_sys_application_file, #upload_drop_zone_sys_application_file + .upload-src-zone'),
                    formData: {
                        'unique_name' : 'sys_application_file', 
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
                            data.submit();
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
                
            
                        $('#mav_date_published').datetimepicker({
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
                            $('#mav_date_published').attr('readonly', true);
                        }
                        
    },
    childEvents: function(elem, eleObj){
        
    },
    CCEvents: function(){
        
        pre_cond_code_arr.push(cc_json_1);
    }
}
Project.modules.application_versions.init();
