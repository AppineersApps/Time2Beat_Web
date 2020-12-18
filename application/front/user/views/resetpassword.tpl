<%$this->js->add_js("resetpassword.js")%>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title"><%$this->lang->line("FRONT_RESET_PASSWORD")%></div>
    </div>
    <div class="panel-body">
        <div class="col-md-12">
            <form method="post" action="<%$site_url%>user/user/resetpassword_action" id="resetpassword" class="form-horizontal">
                <input type="hidden" name="userid" id="userid" value="<%$id%>">
                <input type="hidden" name="time" id="time" value="<%$time%>">
                <input type="hidden" name="code" id="code" value="<%$code%>">
                <input type="hidden" name="rsp" id="code" value="<%$rsp%>">
                
                <div class="form-group">
                    <label for="new_password" class="col-sm-2 control-label"><%$this->lang->line("FRONT_NEW_PASSWORD")%> <span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                        <input type="password" class="form-control" id="new_password" name="new_password" >
                    </div>
                </div>
                <div class="form-group">    
                    <label for="retype_password" class="col-sm-2 control-label"><%$this->lang->line("FRONT_RETYPE_PASSWORD")%> <span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                        <input type="password" class="form-control" id="retype_password" name="retype_password" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="reset_code" class="col-sm-2 control-label"><%$this->lang->line("FRONT_RESET_CODE")%> <span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="reset_code" name="reset_code" >
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button name="submit" type="submit" class="btn btn-success" id="resetpassword"><%$this->lang->line("FRONT_SUBMIT")%></button>
                        <a href="<%$site_url%>login.html" class="btn btn-danger"><%$this->lang->line("FRONT_CANCEL")%></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
