<%if $status eq 0 || $code eq ''%>
<div class="col-md-6 col-md-offset-3">
  <div class="lock-img ex-pswr"><img src="<%$this->config->item('site_url')%>/public/images/front/password.png"/></div>
</div>

<div class="jumbotron text-xs-center">
<div class="col-md-6 col-md-offset-3">
  <p class="lead ex-pass">
    <%$message%></p>
  </p>
</div>
<%else%>
<div class="new-passwd">
<div class="col-md-4 col-md-offset-4">
  <div class="lock-img"><img src="<%$this->config->item('site_url')%>/public/images/front/password.png"/></div>
</div>
<div class="col-md-4 col-md-offset-4">
  <input type="hidden" id="code_val" value="<%$code%>" />
  <div class="alert alert-danger" style="display:none;font-size: 14px;" role="alert" id="error_alert"> Password not updated successfully </div>
  <div class="alert alert-success" style="display:none;font-size: 14px;" role="alert" id="success_alert"> Password Updated successfully </div>
  <form id="reset_form" method="post"  action="<%$this->config->item('site_url')%>resetpassaction">
    <div class="form-group nw-pass"> 
      <!--<label for="new_password">New Password:</label>-->
      <input type="password" name="new_password" class="form-control" id="new_password" placeholder="New Password">
      <input type="hidden" name="code" value="<%$code%>">
      <div style="color:#c50f0f;" id="new_pass_error"></div>
    </div>
    <div class="form-group cnf-pass"> 
      <!--<label for="confirm_password">Confirm Password:</label>-->
      <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm Password">
      <div style="color:#c50f0f;" id="confirm_pass_error"></div>
    </div>
    <div class="text-center sub-btn">
      <button type="submit" id="forget_submit" class="btn btn-default">Submit</button>
    </div>
  </form>
</div></div>
<%$this->js->add_js("resetpassword.js")%>
<%/if%>
