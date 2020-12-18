<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>  
<%$this->js->add_js('admin/misc/sew/jquery.caretposition.min.js')%>
<%$this->js->add_js('admin/misc/sew/jquery.sew.min.js')%>
<%$this->js->add_js('admin/admin/js_push_notify.js')%>
<div class='custom-bulkemail-container'>
    <%include file="pushnotify_strip.tpl"%>
    <div id="ajax_content_div" class="ajax-content-div top-frm-spacing">
        <div id="ajax_qLoverlay"></div>
        <div id="ajax_qLbar"></div>
        <div id="scrollable_content" class="scrollable-content">
            <div id="pushnotify" class="frm-elem-block frm-stand-view">
                <form name="frmpushnotifyadd" id="frmpushnotifyadd" action="<%$admin_url%><%$mod_enc_url['pushnotify_action']%>" method="post"  enctype="multipart/form-data">
                    <input type="hidden" id="mode" name="mode" value="<%$this->input->get_post('mode')%>">
                    <div class="main-content-block" id="main_content_block">
                        <div style="width:98%" class="frm-block-layout pad-calc-container">
                            <div class="box gradient <%$rl_theme_arr['frm_gener_content_row']%> <%$rl_theme_arr['frm_gener_border_view']%>">
                                <div class="title <%$rl_theme_arr['frm_gener_titles_bar']%>"><h4><%$this->lang->line('GENERIC_PUSH_NOTIFY')%></h4></div>
                                <div class="content <%$rl_theme_arr['frm_gener_label_align']%>">
                                    <div class="form-row row-fluid">
                                        <label class="form-label span3"><%$this->lang->line('GENERIC_PUSH_NOTIFY_SENDTO')%> <em>*</em> :</label> 
                                        <div class="form-right-div">
                                            <select id="vSendTo" name="vSendTo" class="frm-size-large chosen-select">
                                                <optgroup label="Other">
                                                    <option selected="selected" value="Other">Other</option>
                                                </optgroup>
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
                                        </div>
                                        <div class="error-msg-form" ><label class="error" id="vSendToErr"></label></div>
                                    </div>
                                    <div class="form-row row-fluid" id="div_module_other">
                                        <label class="form-label span3"><%$this->lang->line('GENERIC_PUSH_NOTIFY_ENTER_DEVICE_ID')%> <em>*</em> :</label> 
                                        <div class="form-right-div">
                                            <textarea title="<%$this->lang->line('GENERIC_PUSH_NOTIFY_ENTER_DEVICE_ID')%>" id="iDeviceId" name="iDeviceId" class="elastic frm-size-large"></textarea>
                                            <a class="tipR" style="text-decoration: none;" href="javascript://" oldtitle="<%$this->lang->line('GENERIC_PUSH_NOTIFY_HELP_DEVICE_ID')%>" title="<%$this->lang->line('GENERIC_PUSH_NOTIFY_HELP_DEVICE_ID')%>" aria-describedby="ui-tooltip-2">
                                                <span class="icomoon-icon-help"></span>
                                            </a>
                                        </div>
                                        <div class="error-msg-form"><label class="error" id="iDeviceIdErr"></label></div>
                                    </div>
                                    <div class="form-row row-fluid">
                                        <label class="form-label span3"><%$this->lang->line('GENERIC_PUSH_NOTIFY_CODE')%> <em>*</em> :</label> 
                                        <div class="form-right-div">
                                            <select id="vCode" name="vCode" class="frm-size-large chosen-select">
                                                <%if $db_notify_code|@is_array && $db_notify_code|@count gt 0 %>
                                                    <%foreach from=$db_notify_code key=k item=v%>
                                                        <option value="<%$v%>"><%$v%></option>
                                                    <%/foreach%>
                                                <%/if%>
                                            </select>
                                        </div>
                                        <div class="error-msg-form" ><label class="error" id="vCodeErr"></label></div>
                                    </div>
                                    <div class="form-row row-fluid">
                                        <label class="form-label span3"><%$this->lang->line('GENERIC_PUSH_NOTIFY_SOUND')%> <em>*</em> :</label> 
                                        <div class="form-right-div">
                                            <select id="vSound" name="vSound" class="frm-size-large chosen-select">
                                                <%if $sound_arr|@is_array && $sound_arr|@count gt 0 %>
                                                    <%foreach from=$sound_arr key=k item=v%>
                                                        <option value="<%$k%>"><%$v%></option>
                                                    <%/foreach%>
                                                <%/if%>
                                            </select>
                                        </div>
                                        <div class="error-msg-form" ><label class="error" id="vSoundErr"></label></div>
                                    </div>
                                    <div class="form-row row-fluid">
                                        <label class="form-label span3"><%$this->lang->line('GENERIC_PUSH_NOTIFY_BADGE')%> :</label> 
                                        <div class="form-right-div">
                                            <input type="text" class="frm-size-large"  title="<%$this->lang->line('GENERIC_PUSH_NOTIFY_BADGE')%>" id="vBadge" value="" name="vBadge">
                                        </div>
                                        <div class="error-msg-form" ><label class="error" id="vBadgeErr"></label></div>
                                    </div>
                                    <div class="form-row row-fluid">
                                        <label class="form-label span3"><%$this->lang->line('GENERIC_PUSH_NOTIFY_BUTTON_TITLE')%> <em>*</em> :</label> 
                                        <div class="form-right-div">
                                            <input type="text" class="frm-size-large"  title="<%$this->lang->line('GENERIC_PUSH_NOTIFY_BUTTON_TITLE')%>" id="vButtonTitle" value="" name="vButtonTitle">
                                        </div>
                                        <div class="error-msg-form" ><label class="error" id="vButtonTitleErr"></label></div>
                                    </div>
                                    <div class="form-row row-fluid">
                                        <label class="form-label span3"><%$this->lang->line('GENERIC_PUSH_NOTIFY_MESSAGE')%> <em>*</em> :</label> 
                                        <div class="form-right-div">
                                            <textarea title="<%$this->lang->line('GENERIC_PUSH_NOTIFY_MESSAGE')%>" id="vMessage" name="vMessage" class="elastic frm-size-large"></textarea>
                                            <a class="tipR" style="text-decoration: none;" href="javascript://" oldtitle="Response parameters should be precede and followed by '#' symbol <br> Example:- #field_name#" title="Response parameters should be precede and followed by '#' symbol <br> Example:- #field_name#" aria-describedby="ui-tooltip-2">
                                                <span class="icomoon-icon-help"></span>
                                            </a>
                                        </div>
                                        <div class="error-msg-form" ><label class="error" id="vMessageErr"></label></div>
                                    </div>
                                    <div class="form-row row-fluid">
                                        <div class="box form-child-table">
                                            <div class="title">
                                                <h4>
                                                    <span class="icon12 icomoon-icon-equalizer-2"></span>
                                                    <span><%$this->lang->line('GENERIC_VARIABLES')%></span>
                                                    <div class="box-addmore right">
                                                        <a onclick="Project.modules.pushnotify.getVariableDescriptionTable()" href="javascript://" class="btn btn-success">
                                                            <span class="icon14 icomoon-icon-plus-2"></span>
                                                            <%$this->lang->line('GENERIC_ADD_NEW')%>
                                                        </a>
                                                    </div>
                                                </h4>
                                                <a style="display: none;" class="minimize" href="javascript://"><%$this->lang->line('GENERIC_MINIMIZE')%></a>
                                            </div>
                                            <div class="content noPad push-notify-vars">
                                                <table id="tbl_child_module" class="responsive table table-bordered">
                                                    <thead>    
                                                        <tr>
                                                            <th width='3%'>#</th>
                                                            <th width='25%'><%$this->lang->line('GENERIC_VARIABLES')%></th>
                                                            <th width='50%'><%$this->lang->line('GENERIC_DESCRIPTION')%></th>
                                                            <th width='12%'><div align="center"><%$this->lang->line('GENERIC_COMPULSORY')%></div></th>
                                                            <th width='10%'><div align="center"><%$this->lang->line('GENERIC_ACTIONS')%></div></th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                                <table width="100%" border="0" cellpadding='0' cellspacing="0">
                                                    <tr>
                                                        <td id='mails_fields_list'>
                                                            <table width='100%' cellspacing='0' cellpadding='0' border="0" class="responsive table table-bordered field-sortable">
                                                                <tr id="tr_child_row_0">
                                                                    <td class="row-num-child" width='3%'>1</td>
                                                                    <td width='25%'>
                                                                        <div class="">                                                                    
                                                                            <input type="text" class="frm-size-large valid-variable" title="<%$this->lang->line('GENERIC_VARIABLES')%>" id="push_notify_variable_0" name="push_notify_variable[0]" value="<%$db_email_vars[k]['vVarName']%>">
                                                                        </div>
                                                                        <div>
                                                                            <label id="push_notify_variable_0Err" class="error"></label>
                                                                        </div>
                                                                    </td>
                                                                    <td width='50%'>
                                                                        <div style="float:left;width:52%">
                                                                            <select class='chosen-select frm-size-full_width' name='push_notify_value[0]' id='push_notify_value_0' onchange='changeParameters(this)'>
                                                                                <%if $field_arr|@is_array && $field_arr|@count gt 0 %>
                                                                                    <optgroup label="List Fields">
                                                                                        <%foreach from=$field_arr key=K item=V%>
                                                                                            <option value='<%$V["name"]%>'><%$V['label']%></option>
                                                                                        <%/foreach%>
                                                                                    </optgroup>
                                                                                <%/if%>
                                                                                <optgroup label="Other">
                                                                                    <option selected="selected" value="Other">Other</option>
                                                                                </optgroup>
                                                                            </select>

                                                                        </div>
                                                                        <div style="float:left;width:44%;">
                                                                            <input type="text" class="frm-size-full_width" id="push_notify_value_0_Other" name='push_notify_value_other[]'>
                                                                        </div>
                                                                        <div>
                                                                            <label id="push_notify_value_0Err" class="error"></label>
                                                                        </div>
                                                                    </td>
                                                                    <td align="center" width='12%'>
                                                                        <div class="center">
                                                                            <input type="checkbox" class="regular-checkbox" title="<%$this->lang->line('GENERIC_COMPULSORY')%>" id="push_notify_value_compulsory_0" name="push_notify_compulsory[0]" value="Yes">
                                                                            <label for="push_notify_value_compulsory_0">&nbsp;</label>
                                                                        </div>
                                                                    </td>
                                                                    <td align="center" width='10%'>
                                                                        <div class="controls center">                                                                    
                                                                            <a class="tipR" href="javascript://" title="<%$this->lang->line('GENERIC_DELETE')%>" onclick="Project.modules.pushnotify.deletePushnotifyVariableRow('1')">
                                                                                <span class="icon12 icomoon-icon-remove"></span>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="frm-bot-btn <%$rl_theme_arr['frm_gener_action_bar']%> <%$rl_theme_arr['frm_gener_action_btn']%>">
                            <div class="action-btn-align">
                                <input value="<%$this->lang->line('GENERIC_PUSH_NOTIFY_BUTTON')%>" name="ctrladd" type="submit" class='btn btn-info' onclick="return Project.modules.pushnotify.getValidatePushNotify()"/>&nbsp;&nbsp;
                                <input type="button" value="<%$this->lang->line('GENERIC_PUSH_NOTIFY_DISCARD')%>" class='btn' onclick="loadAdminSiteMapPage()">
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
    var $push_notify_variables = '<%$mod_enc_url["pushnotify_variables"]%>';
    var $pushnotify_module_fields = '<%$mod_enc_url["pushnotify_module_fields"]%>';
    var $pushnotify_select_fields = '<%$mod_enc_url["pushnotify_select_fields"]%>';
    var inc_no = '1', dis_no = '2';
<%/javascript%> 
<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 