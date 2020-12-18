/** feedback_management module script */
Project.modules.feedback_management = {
    init: function() {
        
        valid_more_elements = ["child[query_images][uqi_query_image]"];
        
        
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
        
            this.childEvents("query_images", "#child_module_query_images");
        callGoogleMapEvents();
    },
    initEvents: function(elem){
        
            $('#uq_feedback').elastic();
            
                        $('#uq_added_at').datepicker({
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
                            $('#uq_added_at').attr('readonly', true);
                        }
                        
            $('#uq_note').elastic();
            this.childEvents("query_images", "#child_module_query_images");
    },
    childEvents: function(elem, eleObj){
        switch(elem){
                
                case "query_images" :
                    var is_popup = $("#childModulePopup_query_images").val();
                    if(is_popup != "Yes"){
                        
                
            
            var tarObj = $(eleObj).find("[id='uploadify_child_query_images_uqi_query_image_0']");
            if(tarObj && tarObj.length){
                var ele_id = $(tarObj).attr('id');
                var last_id = ele_id.split('_').pop();
                var act_id = ele_id.split('_').slice(1).join('_');
                var temp_id  = 'child_query_images_temp_uqi_query_image_'+last_id;
                var id_val = '';
                if($('#child_query_images_enc_id_'+last_id).length){
                    id_val = $('#child_query_images_enc_id_'+last_id).val();
                }
                $('#upload_drop_zone_' + act_id).width($(tarObj).width() + 18);
                $(tarObj).fileupload({
                    name: act_id,
                    temp: temp_id,
                    url : admin_url+''+$('#childModuleUploadURL_query_images').val()+'?', 
paramName : 'Filedata', 
maxFileSize : '102400', 
acceptFileTypes : 'gif|png|jpg|jpeg|jpe|bmp|ico',
                    dropZone: $('#upload_drop_zone_' + act_id + ', #upload_drop_zone_' + act_id + ' + .upload-src-zone'),
                    formData: {
                        'unique_name' : 'uqi_query_image', 
                        'id' : id_val,
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
                            $(this).fileupload('option', 'total', data.originalFiles.length);
                            $('#practive_' + _input_name).css('width', '0%');
                            $('#progress_' + _input_name).show();
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
                            var jparse_data = $.parseJSON(data.result);
                            if (jparse_data.success == '0') {
                                Project.setMessage(jparse_data.message, 0);
                            } else {
                                addAdminOntheFlyImage('query_images', 'uqi_query_image', jparse_data);
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
                        var tot = $(this).fileupload('option', 'total');
                        var cnt = $(this).fileupload('option', 'count');
                        cnt = (cnt) ? cnt + 1 : 1;
                        $(this).fileupload('option', 'count', cnt);
                        
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        $('#practive_' + _input_name).css('width', progress + '%');
                        if(cnt >= tot){
                            setTimeout(function() {
                                $('#progress_' + _input_name).hide();
                            }, 1000);
                        }
                    }
                });
            }
            
                    }
                    break;
            }
    },
    toggleEvents: function(){
        
    },
    dropdownLayouts:function(elem){
        
    }
}
Project.modules.feedback_management.init();
