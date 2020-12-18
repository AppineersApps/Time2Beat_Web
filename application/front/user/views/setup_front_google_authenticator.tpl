
<input type="hidden" name="enc_id" id="enc_id" value="<%$enc_id%>" />
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">Google Authentication</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-7">
            <h4><%$this->lang->line("FRONT_SETUP_GOOGLE_AUTHENTICATOR_IN_MOBILE")%></h4>
            <div class='google-setup-steps'>
                <span>1. <%$this->lang->line("FRONT_GET_THE_AUTHENTICATOR_APP_FROM_THE_STORE")%></span>
                <span>2. <%$this->lang->line("FRONT_IN_THE_APP_SELECT SET UP_ACCOUNT")%></span>
                <span>3. <%$this->lang->line("FRONT_CHOOSE_SCAN_A_BARCODE")%></span>
            </div>
        </div>
        <div class='col-sm-5' style="text-align: center;">
            <img class="google-qr" src="<%$qr_code_google_url%>">
        </div>
    </div>
    <div class='row'>
        <div class="col-sm-7">
            <h5><%$this->lang->line("FRONT_GET_GOOGLE_AUTHENTICATOR_ON_YOUR_MOBILE")%></h5>
            <a style="float:left;" href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank">
                <img class="app-img" src="<%$this->config->item('admin_images_url')%>iphone.png" />
            </a>
            <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&amp;hl=en" target="_blank">
                <img class="app-img" src="<%$this->config->item('admin_images_url')%>android.png" />
            </a>
        </div>
        <div class="col-sm-5" style="text-align: center;">
            <h5><%$this->lang->line("FRONT_ENTER_GOOGLE_AUTHENTICATOR_CODE")%></h5>
            <input type="text" name="ga_code" id="ga_code" class="auth-code-setup" maxlength="6">
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><%$this->lang->line("FRONT_CLOSE")%></button>
    <button type="button" id='modal-submit' class="btn btn-primary"><%$this->lang->line("FRONT_VERIFY")%></button>
</div>
<%javascript%>
    $('#modal-submit').on('click', function (e) {
        var userId = $('#enc_id').val();
        var code = $('#ga_code').val();
        $.ajax({
            url: site_url + 'auth_verify.html',
            type: 'POST',
            data: {
                'id': userId,
                'code': code
            },
            success: function (response) {
                var data = $.parseJSON(response);
                if(data.success == 1){
                    $('#myModal').modal('toggle');
                }
                Project.showUIMessage('Google Authenticator', data.message, data.success);
            },
        });
    });
<%/javascript%>