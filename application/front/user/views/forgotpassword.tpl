<%$this->js->add_js("forgotpassword.js")%>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title"><%$this->lang->line("FRONT_FORGOT_PASSWORD")%></div>
    </div>
    <div class="panel-body">
        <div class="col-md-12">
            <form method="post" action="<%$site_url%>user/user/forgotpassword_action" id="forgotpassword-form-normal" class="form-horizontal">
                <div class="form-group">
                    <label for="user_name" class="col-sm-2 control-label"><%$this->lang->line("FRONT_USER_NAME")%> <span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="user_name" name="user_name" >
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button name="submit" type="submit" class="btn btn-success" id="forgotpassword"><%$this->lang->line("FRONT_SUBMIT")%></button>
                        <a href="<%$site_url%>login.html" class="btn btn-danger"><%$this->lang->line("FRONT_CANCEL")%></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
