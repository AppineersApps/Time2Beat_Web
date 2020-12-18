<%$this->js->add_js("register.js")%>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title"><%$heading%></div>
    </div>
    <div class="panel-body">
        <div class="col-md-12">
            <form method="post" action="<%if $type eq 'register'%><%base_url('user/register_action')%><%else%><%base_url('user/profile')%><%/if%>" id="frm<%$type%>" class="form-horizontal">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="first_name"><%$this->lang->line("FRONT_FIRST_NAME")%> <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<%$user['firstname']%>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="last_name"><%$this->lang->line("FRONT_LAST_NAME")%> <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<%$user['lastname']%>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="email"><%$this->lang->line("FRONT_EMAIL")%> <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="email" name="email" value="<%$user['email']%>" <%if $type neq 'register'%>readonly=true<%/if%> />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="username"><%$this->lang->line("FRONT_USER_NAME")%> <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="username" name="username" value="<%$user['username']%>" <%if $type neq 'register'%>readonly=true<%/if%> />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="password"><%$this->lang->line("FRONT_PASSWORD")%> <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="password" class="form-control" id="password" autocomplete="off" name="password" value="<%$user['password']%>" />
                        </div>
                    </div>
                    <%if $type neq 'register'%>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="2fa"><%$this->lang->line("FRONT_2_FACTOR_AUTH")%><span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="checkbox" class="custom-control-input" id="googleAuth" name="googleAuth" value='Google' <%if $user['google_auth'] eq 1 %>checked<%/if%> />
                                   <label class="custom-control-label" for="googleAuth"><%$this->lang->line("FRONT_GOOGLE_AUTHENTICATOR")%></label>
                            <span>
                                <a class="scanqr" id='scanqr_modal'><%$this->lang->line("FRONT_SCAN_QR_CODE")%></a>
                            </span>
                            <input type="checkbox" class="custom-control-input" id="emailAuth" name="emailAuth" value='Email' <%if $user['email_auth'] eq 1 %>checked<%/if%> />
                                   <label class="custom-control-label" for="emailAuth"><%$this->lang->line("FRONT_EMAIL")%></label>
                            <input type="checkbox" class="custom-control-input" id="smsAuth" name="smsAuth" value='SMS' <%if $user['sms_auth'] eq 1 %>checked<%/if%> />
                                   <label class="custom-control-label" for="smsAuth"><%$this->lang->line("FRONT_SMS")%></label>
                        </div>
                    </div>
                    <%/if%>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <%if $type eq 'register'%>
                            <button name="submit" type="submit" class="btn btn-success" id="login"><%$this->lang->line("FRONT_REGISTER")%></button>&nbsp;
                            <a href="<%$site_url%>" class="btn btn-danger"><%$this->lang->line("FRONT_CANCEL")%></a>
                            <%else%>
                            <input type="hidden" name="userId" id="userId" value="<%$user['id']%>"/>
                            <input name="update" type="submit" class="btn btn-success" value='<%$this->lang->line("FRONT_UPDATE")%>'/>
                            <a href="<%$site_url%>" class="btn btn-danger"><%$this->lang->line("FRONT_BACK")%></a>
                            <%/if%>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="scanqr-model-content">

            </div>
        </div>
    </div>
</div>