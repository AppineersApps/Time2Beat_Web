Project.modules.js_languagelabels_import={init:function(){$("#frmbtn_upload").click(function(d){d.preventDefault();$("#frmlblimport").ajaxSubmit(function(a){a=$.parseJSON(a);"0"==a.success&&Project.setMessage(a.message,0);window.location.hash=a.redHash})});$("#frmbtn_import").click(function(d){d.preventDefault();$("#frmimportaction").ajaxSubmit(function(a){a=$.parseJSON(a);"0"==a.success?Project.setMessage(a.message,0):Project.setMessage(a.message,1);window.location.hash=a.redHash})});$("#uploadify_importfile").length&&
$("#uploadify_importfile").fileupload({url:$upload_form_file,name:"importfile",temp:"temp_importfile",paramName:"Filedata",maxFileSize:"2048542",acceptFileTypes:"csv",dropZone:$("#upload_drop_zone_importfile, #upload_drop_zone_importfile + .upload-src-zone"),formData:{vSettingName:"importfile",actionType:"upload",type:"uploadify"},add:function(d,a){var c=[],b=$(this).fileupload("option","name"),e=$(this).fileupload("option","temp"),h=$(this).fileupload("option","formData"),m=$(this).fileupload("option",
"maxFileSize"),f=$(this).fileupload("option","acceptFileTypes"),g=a.originalFiles[0].name,k=a.originalFiles[0].size;if("*"!=f){var l=g?g.substr(g.lastIndexOf(".")):"",f=new RegExp("(.|/)("+f+")$","i");l&&!f.test(l)&&c.push("ACTION_FILE_TYPE_IS_NOT_ACCEPTABLE")}k&&k>1E3*m&&c.push("ACTION_FILE_SIZE_IS_TOO_LARGE");0<c.length?Project.setMessage(c.join("\n"),0):($("#practive_"+b).css("width","0%"),$("#progress_"+b).show(),h.oldFile=$("#"+e).val(),$(this).fileupload("option","formData",h),$("#preview_"+
b).html(g),a.submit())},done:function(d,a){if(a&&a.result){var c=$(this).fileupload("option","name"),b=$(this).fileupload("option","temp"),e=$.parseJSON(a.result);"0"==e.success?Project.setMessage(e.message,0):($("#"+c).val(e.uploadfile),$("#"+b).val(e.oldfile))}},fail:function(d,a){$.each(a.messages,function(a,b){Project.setMessage(b,0)})},progressall:function(d,a){var c=$(this).fileupload("option","name"),b=parseInt(a.loaded/a.total*100,10);$("#practive_"+c).css("width",b+"%")}})}};Project.modules.js_languagelabels_import.init();