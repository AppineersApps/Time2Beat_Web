<%$this->js->add_js("login.js")%>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title"><%$heading%><%$this->lang->line("FRONT_TWO_FACTOR_VERIFICATION_BY")%> <%$ren_arr['type']%></div>
    </div>
    <div class="panel-body">
        <div class="col-md-12">
            <%if $ren_arr['username'] neq ''%>
            <form method="post" action="<%$site_url%>user/twofactor_verification" id="login-form-normal" class="form-horizontal">
                <input value="<%$ren_arr['type']%>" type="hidden" id="auth_type" name="auth_type" />
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="2faCode"><%$this->lang->line("FRONT_SECURITY_CODE")%><span class="required text-danger">*</span></label>
                    <div class="col-sm-4">
                        <input value="" type="text" class="form-control" id="2faCode" name="2faCode" />
                    </div>
                    <span id="2faCodeErr"></span>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-4">
                        <input type="checkbox" class="" id="dont_ask_again" name="dont_ask_again" value="Yes">
                        <label class="" for="dont_ask_again"><%$this->lang->line("FRONT_DONT_ASK_FOR_THIS_DEVICE")%></label>
                        <a class="" id="" href="try-another.html" title="" style="float:right;margin-top:7px;"><%$this->lang->line("FRONT_TRY_ANOTHER_WAY")%></a>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-2">
                        <button name="submit" type="submit" class="btn btn-success" id="login"><%$this->lang->line("FRONT_VERIFY")%></button>&nbsp;
                        <a href="<%$site_url%>" class="btn btn-danger"><%$this->lang->line("FRONT_CANCEL")%></a>
                    </div>
                    <%if $ren_arr['type'] neq 'Google'%>
                        <div class="col-sm-2">
                            <a class="" id="" href="resend.html?type=<%$ren_arr['type']%>" title="" style="float:right;margin-top:10px;"><%$this->lang->line("FRONT_RESEND_OTP")%></a>
                        </div>
                    <%/if%>
                </div>
            </form> 
            <%/if%>
        </div>
    </div>
</div>
