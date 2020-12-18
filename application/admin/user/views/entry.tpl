<div class="loginContainer login-form <%if $is_patternlock eq 'yes'%> pattern-form <%/if%>">
    <div class="loginbox-border">
        <div>
            <div id="login_div">
                <div class="login-headbg"><%$this->lang->line('GENERIC_ADMIN_LOGIN')%>
                    <p><%$this->lang->line('GENERIC_ADMIN_LOGIN_INFO')%></p>
                </div>
                <form name="frmlogin" id="frmlogin" action="<%$enc_url['entry_action_url']%>" method="post" >
                    <input type="hidden" name="handle_url" id="handle_url" value=""/>
                    <div width="28%" class="bmatter relative">
                        <label for="login_name"><%*$this->lang->line('GENERIC_USERNAME')*%> <span class="icon16 icomoon-icon-user-3 icon-user-login"></span></label>
                        <input type="text"  title="<%$this->lang->line('GENERIC_LOGIN')%>" name="login_name" id="login_name"  class="text login-user" value="<%$login_name%>" size="25" maxlength="50" placeholder="Username" />
                    </div>
                    <div class="clear"></div>
                    <div class="error-msg login-error-msg" id='login_nameErr'>                    
                    </div>
                    <div class="bmatter relative">
                        <label for="passwd"><%*$this->lang->line('GENERIC_PASSWORD')*%> <span class="icomoon-icon-locked-2 icon-user-pw"></span></label>			
                        <input type="password" title="<%$this->lang->line('GENERIC_PASSWORD')%>" name="passwd" id="passwd" size="25" value="<%$passwd%>" class="login-pass" maxlength="50" placeholder="Password" />
                        <a id='pwd_show_hide' class='login-pwd-icon' href='javascript://'><i class='fa fa-eye-slash fa-18' id='pwd_icon' status='hide'></i></a>
                        <%if $is_patternlock eq "yes" %>
                            <div id='passwd-div' class="pwd-pattern"></div>
                        <%/if%>
                    </div>
                    <div class="clear"></div>
                    <div class="error-msg login-error-msg" id='passwdErr'></div>
                    <%if $is_patternlock eq "yes" %>
                        <div class="relative secret-login-part">
                            <input id='secretlogin' type="checkbox" name="secrectlogin" class="secrectlogin regular-checkbox" value="yes">
                            <label for='secretlogin'>&nbsp;</label><label for='secretlogin'><%$this->lang->line('GENERIC_MAKE_IT_SECRET')%> ?</label>
                        </div>
                    <%else%>
                        <div class="normal-login-type">
                            <button type="button" class="btn btn-info login-btn" id="loginBtn" onclick="return login(document.frmlogin)">
                                Login<span class="icon16 icomoon-icon-enter white right"></span>
                            </button>
                       </div>
                    <%/if%>
                    <div class="login-actions">
                        <%if $is_patternlock neq "yes" %>
                            <div class="login-remember-me left">
                                <input class="remember-me-check regular-checkbox" type="checkbox" value="Yes" name="remember_me" id="remember_me"  <%if $remember_me eq "Yes" %>checked="checked"<%/if%> > 
                                <label for="remember_me">&nbsp;</label><label class="remember-me-label" for="remember_me"><%$this->lang->line('GENERIC_REMEMBER_ME')%></label>
                            </div>
                        <%/if %>
                        <div class="show-forgot-pwd right">
                            <a href="javascript://" onclick="return showForgotPassword();"><%$this->lang->line('GENERIC_FORGOT_YOUR_PASSWORD')%>?</a>
                        </div>
                    </div>
                </form>
            </div>
            <div id="forgot_div" class="forgot-pwd">
                <div class="login-headbg"><%$this->lang->line('GENERIC_ADMIN_FORGOT_PASSWORD')%>
                    <p><%$this->lang->line('GENERIC_ADMIN_FORGOT_PASSWORD_INFO')%></p>
                </div>
                <div width="28%" class="relative">
                    <label for="username"><%*$this->lang->line('GENERIC_USERNAME')*%><span class="icon16 icomoon-icon-user-3 icon-user-login"></span></label>
                    <input type="text" placeholder="<%$this->lang->line('GENERIC_USERNAME')%>" title="<%$this->lang->line('GENERIC_USERNAME')%>" name="username" id="username"  class="text login-forgot" value="" size="25" maxlength="50" style="float:left" />
                    <div class="error-msg login-error-msg" id='usernameErr'></div>
                    <div class="forgot-pwd-btns">
                        <input id="send_button" type="button" class="forgot-send-btn btn btn-info right"  value="Send Password" onclick="return validateSendForgotPassword();">
                        <span id="loader_img" class="forgot-loader-img right"><i class="fa fa-refresh fa-spin-light fa-2x fa-fw"></i></span>
                    </div>
                    <div class="forgot-backlink">
                        Back to <span><a href="javascript://" onclick="return hideForgotPassword();"> login</a></span>
                        <%*<input id="send_cancel" type="button" class="forgot-cancel-btn btn right"  value="Cancel" >*%>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<%javascript%>
    var forgot_pwd_url = '<%$enc_url['forgot_pwd_url']%>';
    var is_pattern = '<%$is_patternlock%>';
<%/javascript%>
<%$this->js->add_js("admin/general/entry.js")%>