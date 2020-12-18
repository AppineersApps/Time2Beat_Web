<div class="loginContainer login-form <%if $is_patternlock eq 'yes'%> pattern-form <%/if%>">
    <div class="loginbox-border">
        <div>
            <div id="login_div">
                <div class="login-headbg"><%$this->general->processMessageLabel("GENERIC_2_STEP_VERIFICATION")%><p></p></div>
                <p class="heading-username"><%$username%></p>
                <p><%$title%></p>
                <form name="authentication" id="google_authentication" action="<%$authentication%>" method="post" >
                    <input type="hidden" name="auth_type" id="auth_type" value="<%$auth_type%>" />
                    <div class="bmatter relative">
                        <label for="2fa_code"><span class="icomoon-icon-locked-2 icon-user-pw"></span></label>		
                        <input type="text" name="2fa_code" id="2fa_code" class="login-pass" placeholder="<%$placeholder%>" />
                    </div>
                    <div>
                        <input class="dont-ask-again regular-checkbox" type="checkbox" value="Yes" name="dont_ask_again" id="dont_ask_again"  <%if $dont_ask_again eq "Yes" %>checked="checked"<%/if%> > 
                            <label for="dont_ask_again" class="dont-ask-again-checkbox">&nbsp;</label><label class="dont-ask-again-label" for="dont_ask_again">Don't ask again on this computer / device</label>
                    </div>
                    <div class="normal-login-type">
                        <button type="submit" class="btn btn-info login-btn" id="loginBtn">
                            Verify<span class="icon16 icomoon-icon-enter white right"></span>
                        </button>
                   </div>
                    
                    <div class="login-actions">
                        <div class="show-forgot-pwd left">
                            <a href="<%$login_url%>">Back to Login</a>
                        </div>
                        <div class="show-forgot-pwd right">
                            <a href="<%$try_another_way_url%>" href="">Try another way</a>
                        </div>
                    </div>
                    <%if $auth_type neq "Google"%>
                        <div class="autentication-resend-otp">
                            <a id="resend_otp" href="<%$resend_otp_url%>?type=<%$auth_type%>">Resend OTP</a>
                            <img id="otp_img" style="display:none;" src="<%$this->config->item('admin_images_url')%>loading.gif" />
                        </div>
                    <%/if%>
                </form>
            </div>
        </div>
    </div>
</div>
<%javascript%>
    $(document).ready(function(){
        $( "#resend_otp" ).click(function() {
            $( "#otp_img" ).removeAttr("style")
        });
    });
<%/javascript%>
