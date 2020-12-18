<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>    
<%$this->js->add_js('admin/admin/js_change_password.js')%>

<div id="ajax_qLbar"></div>
<div class="popup-frm-setup">
    <div class="popup-left-block" id="popup-left-block">
        <!-- Top Detail View Block -->
        <div class="popup-left-container">
            <div class="popup-left-main-title">
                <div class="popup-left-main-title-icon"><span><i class="fa fa-lock" style="font-size:80px;color: #ffffff;"></i></span></div>
                <span class="popup-left-main-title-content"><%$this->general->processMessageLabel("GENERIC_CHANGE_PASSWORD")%></span>
            </div>
            <div class="popup-label-box">
                <div class="label-box-row">
                    <span class="label-box-row-title"><%$this->general->processMessageLabel("GENERIC_NAME")%></span>
                    <em class="label-box-row-content"><%$name%></em>
                </div>
                <div class="label-box-row">
                    <span class="label-box-row-title"><%$this->general->processMessageLabel("GENERIC_EMAIL")%></span>
                    <em class="label-box-row-content"><%$email%></em>
                </div>
            </div>
        </div>
    </div>   
    <div id="ajax_content_div" class="ajax-content-div top-frm-spacing popup-right-block">
        <div id="ajax_qLoverlay"></div>
        <div id="ajax_qLbar"></div>
        <div id="scrollable_content" class="scrollable-content">
            <div id="changepassword" class="frm-elem-block frm-stand-view">
                <form name="frmchangepassword" id="frmchangepassword" action="<%$changepassword_url%>" method="post"  enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<%$enc_id%>">
                    <input type="hidden" id="patternLock" name="patternLock" value="<%$is_patternlock%>" > 
                    <div class="main-content-block" id="main_content_block">
                        <div style="width:98%" class="frm-block-layout" >
                            <div class="box gradient <%$rl_theme_arr['frm_gener_content_row']%> <%$rl_theme_arr['frm_gener_border_view']%>">
                                <div class="title <%$rl_theme_arr['frm_gener_titles_bar']%>"><h4><%$this->lang->line('GENERIC_CHANGE_PASSWORD')%></h4></div>
                                <div class="content <%$rl_theme_arr['frm_gener_label_align']%>">
                                    <%if $is_patternlock eq "yes" %>
                                        <div class="form-row row-fluid">
                                            <label class="form-label span3"><%$this->lang->line('GENERIC_OLD_PASSWORD')%> <em>*</em></label> 
                                            <div class="form-right-div">
                                                <input type="password" id="vOldPassword" value="" name="vOldPassword" class="frm-size-medium" size="25" maxlength="50" >
                                                <div id='old_passwd_div' class="pattern_container"></div>
                                            </div>
                                            <div class="error-msg-form" ><label class="error" id="vOldPasswordErr"></label></div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <label class="form-label span3"><%$this->lang->line('GENERIC_NEW_PASSWORD')%> <em>*</em></label> 
                                            <div class="form-right-div">
                                                <input type="password"  title="<%$this->lang->line('GENERIC_NEW_PASSWORD')%>" name="vPassword" id="vPassword" class="frm-size-medium" value="" size="25" maxlength="50"/>                                    
                                                <div id='passwd_div' class="pattern_container"></div>
                                            </div>
                                            <div class="error-msg-form" ><label class="error" id="vPasswordErr"></label></div>
                                        </div>
                                    <%else%>
                                        <div class="form-row row-fluid">
                                            <label class="form-label span3"><%$this->lang->line('GENERIC_OLD_PASSWORD')%>  <em>*</em></label> 
                                            <div class="form-right-div">
                                                <input type="password" title="<%$this->lang->line('GENERIC_PLEASE_ENTER_OLD_PASSWORD')%>" id="vOldPassword" value="" name="vOldPassword" class="frm-size-medium" autocomplete="off">
                                            </div>
                                            <div class="error-msg-form" ><label class="error" id="vOldPasswordErr"></label></div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <label class="form-label span3"><%$this->lang->line('GENERIC_NEW_PASSWORD')%> <em>*</em></label> 
                                            <div class="form-right-div">
                                                <input type="password" title="<%$this->lang->line('GENERIC_PLEASE_ENTER_NEW_PASSWORD')%>" id="vPassword" value="" name="vPassword" class="frm-size-medium" autocomplete="off">
                                            </div>
                                            <div class="error-msg-form" ><label class="error" id="vPasswordErr"></label></div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <label class="form-label span3"><%$this->lang->line('GENERIC_CONFIRM_PASSWORD')%> <em>*</em></label> 
                                            <div class="form-right-div">
                                                <input type="password"  title="<%$this->lang->line('GENERIC_PLEASE_REENTER_PASSWORD')%>" id="vConfirmPassword" value="" name="vConfirmPassword" class="frm-size-medium" autocomplete="off">
                                            </div>
                                            <div class="error-msg-form" ><label class="error" id="vConfirmPasswordErr"></label></div>
                                        </div>
                                    <%/if%>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="popup-right-footer frm-bot-btn <%$rl_theme_arr['frm_gener_action_bar']%> <%$rl_theme_arr['frm_gener_action_btn']%>">
                            <div class="action-btn-align">
                                <input value="<%$this->lang->line('GENERIC_SAVE')%>" name="ctrladd" type="submit" class='btn btn-info' onclick="return getValidateField()"/>&nbsp;&nbsp; 
                                <input value="<%$this->lang->line('GENERIC_DISCARD')%>" name="ctrldiscard" type="button" class='btn' onclick="closeWindow()">
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var jajax_action_url = '<%$changepassword_url%>';
</script>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 