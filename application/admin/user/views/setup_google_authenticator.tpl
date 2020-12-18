<div class="popup-frm-setup google-setup">
    <div class="popup-left-block" id="popup-left-block">
        <!-- Top Detail View Block -->
        <div class="popup-left-container">
            <div class="popup-left-main-title">
                <div class="popup-left-main-title-icon"><img class='app' src="<%$this->config->item('admin_images_url')%>google_authenticator.png" /></div>
                <span class="popup-left-main-title-content"><%$this->general->processMessageLabel("GENERIC_GOOGLE_AUTHENTICATION")%></span>
            </div>
            <div class="popup-label-box">
                <div class="label-box-row">
                    <span class="label-box-row-title"><%$this->general->processMessageLabel("GENERIC_SET_UP_AUTHENTICATOR")%></span>
                    <em class="label-box-row-content">1. <%$this->general->processMessageLabel("GENERIC_GET_THE_AUTHENTICATOR_APP_FROM_THE_STORE")%></em>
                    <em class="label-box-row-content">2. <%$step2%></em>
                    <em class="label-box-row-content">3. <%$step3%></em>
                </div>
            </div>
        </div>
    </div>
    <div id="ajax_content_div" class="ajax-content-div top-frm-spacing popup-right-block">
        <div id="ajax_qLoverlay"></div>
        <div id="ajax_qLbar"></div>
        <div id="scrollable_content" class="scrollable-content">
            <div id="google_authentication" class="google-authentication">
                <input type="hidden" name="id" id="enc_id" value="<%$enc_id%>" />
                <div class="autenticator-content"><%$main_label%></div>
                <div id="img"><img src='<%$qr_code_google_url%>' /></div>

                <label><%$this->general->processMessageLabel("GENERIC_ENTER_GOOGLE_AUTHENTICATOR_CODE")%></label>
                <input type="text" name="ga_code" id="ga_code" class="ga_code" maxlength="6"/>
                <input type="button" id="verify_google_auth" class="btn btn-info" value="Verify"/>

                <div id="autenticator_app" class="autenticatorApp">
                    <div class="autenticator-content"><%$label%></div>
                    <a href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank"><img class='app' src="<%$this->config->item('admin_images_url')%>iphone.png" /></a>
                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank"><img class="app" src="<%$this->config->item('admin_images_url')%>android.png" /></a>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>

<%javascript%>

$('#verify_google_auth').on( "click", function() {
    var code = $('#ga_code').val();
    var enc_id = $('#enc_id').val();
    if(code == ''){
        alert('Please enter code');
    }else{
        Project.show_adaxloading_div();
        $.ajax({
            type: "POST",
            url: admin_url + cus_enc_url_json.verify_google_auth,
            data: {code:  code, enc_id: enc_id},
            success: function(response)
            {
                Project.hide_adaxloading_div();
                var result = parseJSONString(response);
                if (result.success == '1') {
                    parent.$.fancybox.close();
                    parent.Project.setMessage(result.message, result.success);
                }else{
                    Project.setMessage(result.message, result.success);
                }
            }
        });
    }
});

<%/javascript%>