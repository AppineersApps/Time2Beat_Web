<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>  
<div class='custom-bulkemail-container'>
    <%include file="bulkemail_strip.tpl"%>
    <div id="ajax_content_div" class="ajax-content-div top-frm-spacing">
        <div id="ajax_qLoverlay"></div>
        <div id="ajax_qLbar"></div>
        <div id="scrollable_content" class="scrollable-content top-block-spacing">
            <div id="bulkmail" class="frm-elem-block frm-stand-view">
                <form name="frmbulkmailadd" id="frmbulkmailadd" action="<%$admin_url%><%$mod_enc_url['bulkmail_action']%>" method="post"  enctype="multipart/form-data">
                    <input type="hidden" id="mode" name="mode" value="<%$this->input->get_post('mode')%>">
                    <div class="main-content-block" id="main_content_block">
                        <div style="width:98%" class="frm-block-layout pad-calc-container">
                            <div class="box gradient <%$rl_theme_arr['frm_gener_content_row']%> <%$rl_theme_arr['frm_gener_border_view']%>">
                                <div class="title <%$rl_theme_arr['frm_gener_titles_bar']%>"><h4><%$this->lang->line('GENERIC_BULK_EMAIL')%></h4></div>
                                <div class="content <%$rl_theme_arr['frm_gener_label_align']%>">
                                    <div class="form-row row-fluid">
                                        <label class="form-label span3"><%$this->lang->line('GENERIC_SEND_TO')%> <em>*</em> :</label> 
                                        <div class="form-right-div">
                                            <%if $db_email|@is_array && $db_email|@count gt 0 %>
                                                <select id="vSendTo" name="vSendTo" class="frm-size-large  chosen-select">
                                                    <optgroup label="Other">
                                                        <option selected="selected" value="Other">Other</option>
                                                    </optgroup>
                                                    <%foreach from=$db_email key=k item=v%>
                                                        <optgroup label="<%$k%>">
                                                            <%assign var="innerArr" value=$v%>
                                                            <%section name="i" loop=$innerArr%>
                                                                <option value="Grp@@<%$innerArr[i]['Id']%>"><%$innerArr[i]['Val']%></option>
                                                            <%/section%>
                                                        </optgroup>
                                                    <%/foreach%>
                                                    <%if $db_module|@is_array && $db_module|@count gt 0 %>
                                                        <%foreach from=$db_module key=k item=v%>
                                                            <optgroup label="<%$k%>">
                                                                <%foreach from=$v key=key item=val%>
                                                                    <option value="Mod@@<%$key%>"><%$val%></option>
                                                                <%/foreach%>
                                                            </optgroup>
                                                        <%/foreach%>
                                                    <%/if%>
                                                </select>
                                            <%else%>
                                                <span class="error"><%$this->lang->line('GENERIC_NO_GROUP_PRESENT')%></span>
                                            <%/if%>
                                        </div>
                                        <div class="error-msg-form" ><label class="error" id="vSendToErr"></label></div>
                                    </div>
                                    <div class="form-row row-fluid" id="div_user">
                                        <label class="form-label span3"><%$this->lang->line('GENERIC_ENTER_EMAIL_ADDRESS')%> <em>*</em> :</label> 
                                        <div class="form-right-div">
                                            <textarea title="<%$this->lang->line('GENERIC_PLEASE_ENTER_FROM_NAME')%>" id="vEmailAddress" name="vEmailAddress" class="elastic frm-size-large"></textarea>
                                            <a class="tipR" style="text-decoration: none;" href="javascript://" oldtitle="Enter multiple email address with (,) seperated." title="Enter multiple email address with (,) seperated." aria-describedby="ui-tooltip-2">
                                                <span class="icomoon-icon-help"></span>
                                            </a>
                                        </div>
                                        <div class="error-msg-form" ><label class="error" id="vEmailAddressErr"></label></div>
                                    </div>
                                    <div class="form-row row-fluid">
                                        <label class="form-label span3"><%$this->lang->line('GENERIC_EMAIL_SUBJECT')%> <em>*</em> :</label> 
                                        <div class="form-right-div">
                                            <input type="text" class="frm-size-large"  title="<%$this->lang->line('GENERIC_PLEASE_ENTER_EMAIL_SUBJECT')%>" id="vEmailSubject" value="" name="vEmailSubject">
                                        </div>
                                        <div class="error-msg-form" ><label class="error" id="vEmailSubjectErr"></label></div>
                                    </div>
                                    <div class="form-row row-fluid">
                                        <label class="form-label span3"><%$this->lang->line('GENERIC_FROM_EMAIL')%> <em>*</em> :</label> 
                                        <div class="form-right-div">
                                            <input type="text" class="frm-size-large" title="<%$this->lang->line('GENERIC_PLEASE_ENTER_FROM_EMAIL')%>" id="vFromEmail" value="<%$EMAIL_ADMIN%>" name="vFromEmail">
                                        </div>
                                        <div class="error-msg-form" ><label class="error" id="vFromEmailErr"></label></div>
                                    </div>
                                    <div class="form-row row-fluid">
                                        <label class="form-label span3"><%$this->lang->line('GENERIC_EMAIL_TEMPLATE')%> <em>*</em> :</label> 
                                        <div class="form-right-div">
                                            <%if $email_temp_data|@is_array && $email_temp_data|@count gt 0 %>
                                                <select id="vEmailTemplate" name="vEmailTemplate" class="frm-size-large  chosen-select">
                                                    <option value="">Other</option>
                                                        <%foreach from=$email_temp_data key=em_key item=em_val%>
                                                            <option value="<%$em_val['vEmailCode']%>"><%$em_val['vEmailTitle']%></option>
                                                        <%/foreach%>
                                                </select>
                                            <%/if%>
                                        </div>
                                    </div>
                                    <div id="templateVars">
                                    </div>
                                    <div id="div_editor" class="form-row row-fluid">
                                        <span><textarea title="<%$this->lang->line('GENERIC_EMAIL_CONTENT')%>" id="vEmailContent" value="" name="vEmailContent" style="width:100%;min-height:300px;"></textarea></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="frm-bot-btn <%$rl_theme_arr['frm_gener_action_bar']%> <%$rl_theme_arr['frm_gener_action_btn']%>">
                            <div class="action-btn-align">
                                <input value="<%$this->lang->line('GENERIC_SEND_MAIL')%>" name="ctrladd" type="submit" class='btn btn-info' onclick="return Project.modules.bulkmail.getValidateSystemEmail()"/>&nbsp;&nbsp;
                                <input type="button" value="Discard" class='btn' onclick="loadAdminSiteMapPage()">
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </form>
            </div>
        </div>
    </div>
</div>
<%javascript%>                                               
    var $bulkmail_sendto = '<%$admin_url%><%$mod_enc_url["bulkmail_sendto"]%>';
    var $bulkmail_variables = '<%$admin_url%><%$mod_enc_url["bulkmail_variables"]%>';
<%/javascript%> 
<%$this->js->add_js('admin/forms/tinymce/tinymce.min.js','admin/admin/js_bulk_mail.js')%>
<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 