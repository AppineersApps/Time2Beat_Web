<div class="loginContainer login-form reset-page<%if $is_patternlock eq 'yes' %> reset-pattern<%/if%>">
    <div class="login-headbg"><%$this->lang->line('GENERIC_RESET_PASSWORD')%></div>
    <div class="loginbox-border">
        <div>
            <form name="frmresetpwd" id="frmresetpwd" action="<%$resetpassword_url%>" method="post" >
                <input type="hidden" name="userid" id="userid" value="<%$id%>">
                <input type="hidden" name="time" id="time" value="<%$time%>">
                <input type="hidden" name="code" id="code" value="<%$code%>">
                <input type="hidden" name="is_pattern" id="is_pattern" value="<%$is_patternlock%>">
                <%if $is_patternlock eq "yes" %>
                    <div width="28%" class="bmatter1 relative">
                        <label for="password"><%$this->lang->line('GENERIC_PASSWORD')%> </label>
                        <input type="password"  title="<%$this->lang->line('GENERIC_PASSWORD')%>" name="password" id="password"  class="text" value="" size="25" maxlength="50" />
                        <div id='passwd-div'></div>
                    </div>
                    <div class="clear"></div>
                    <div class="error-msg login-error-msg" id='passwordErr' style="margin-top: 10px;"></div>
                <%else%>
                    <div width="28%" class="bmatter1 relative">
                        <label for="passwd"><span class="icomoon-icon-locked-2 icon-user-pw"></span></label>
                        <input type="password"  title="<%$this->lang->line('GENERIC_NEW_PASSWORD')%>" name="password" id="password"  class="text" value="" size="25" maxlength="50"  placeholder="<%$this->lang->line('GENERIC_NEW_PASSWORD')%>"/>
                    </div>
                    <div class="clear"></div>
                    <div class="error-msg login-error-msg" id='passwordErr'></div>
                    <div class="bmatter relative">
                        <label for="passwd"><span class="icomoon-icon-locked-2 icon-user-pw"></span></label>
                        <input type="password" title="<%$this->lang->line('GENERIC_RETYPE_PASSWORD')%>" name="retypepasswd" id="retypepasswd" size="25" value="" maxlength="50" placeholder="<%$this->lang->line('GENERIC_RETYPE_PASSWORD')%>"/>
                    </div>
                    <div class="clear"></div>
                    <div class="error-msg login-error-msg" id='retypepasswdErr'></div>
                <%/if%>
                <div class="bmatter relative">
                    <label for="passwd"><span class=" icomoon-icon-refresh icon-user-pw"></span></label>
                    <input type="text" title="<%$this->lang->line('GENERIC_RESET_CODE')%>" name="securitycode" id="securitycode" size="25" value="" maxlength="50" autocomplete="off" placeholder="<%$this->lang->line('GENERIC_RESET_CODE')%>"/>
                </div>
                <div class="clear"></div>
                <div class="error-msg login-error-msg" id='securitycodeErr'></div>
                <div class="reset-button-div">
                    <input type="submit" class="btn btn-info right"  value="Reset Password" onclick="return resetpassword();">
                </div>
            </form>
        </div>
    </div>
</div>
<%$this->js->add_js("admin/admin/js_reset_password.js")%>