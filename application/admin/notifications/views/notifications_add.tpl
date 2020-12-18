<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-form-container">
    <%include file="notifications_add_strip.tpl"%>
    <div class="<%$module_name%>" data-form-name="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
            <input type="hidden" id="projmod" name="projmod" value="notifications" />
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Module Tabs & Top Detail View -->
            <div class="top-frm-tab-layout" id="top_frm_tab_layout">
            </div>
            <!-- Middle Content -->
            <div id="scrollable_content" class="scrollable-content top-block-spacing ">
                <div id="notifications" class="frm-module-block frm-elem-block frm-stand-view">
                    <!-- Module Form Block -->
                    <form name="frmaddupdate" id="frmaddupdate" action="<%$admin_url%><%$mod_enc_url['add_action']%>?<%$extra_qstr%>" method="post"  enctype="multipart/form-data">
                        <!-- Form Hidden Fields Unit -->
                        <input type="hidden" id="id" name="id" value="<%$enc_id%>" />
                        <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>" />
                        <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>" />
                        <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>" />
                        <input type="hidden" id="draft_uniq_id" name="draft_uniq_id" value="<%$draft_uniq_id%>" />
                        <input type="hidden" id="extra_hstr" name="extra_hstr" value="<%$extra_hstr%>" />
                        <input type="hidden" name="men_entity_id" id="men_entity_id" value="<%$data['men_entity_id']|@htmlentities%>"  class='ignore-valid ' />
                        <input type="hidden" name="men_group_id" id="men_group_id" value="<%$data['men_group_id']|@htmlentities%>"  class='ignore-valid ' />
                        <input type="hidden" name="men_entity_type" id="men_entity_type" value="<%$data['men_entity_type']%>"  class='ignore-valid ' />
                        <textarea style="display:none;" name="men_redirect_link" id="men_redirect_link"  class='ignore-valid ' ><%$data['men_redirect_link']%></textarea>
                        <input type="hidden" name="men_send_date_time" id="men_send_date_time" value="<%$this->general->dateSystemFormat($data['men_send_date_time'])%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
                        <!-- Form Dispaly Fields Unit -->
                        <div class="main-content-block" id="main_content_block">
                            <div style="width:98%" class="frm-block-layout pad-calc-container">
                                <div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
                                    <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('NOTIFICATIONS_NOTIFICATIONS')%></h4></div>
                                    <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                        <div class="form-row row-fluid" id="cc_sh_men_receiver">
                                            <label class="form-label span3">
                                                <%$form_config['men_receiver']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div   ">
                                                <input type="text" placeholder="" value="<%$data['men_receiver']|@htmlentities%>" name="men_receiver" id="men_receiver" title="<%$this->lang->line('NOTIFICATIONS_RECEIVER')%>"  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='men_receiverErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid" id="cc_sh_men_notification_type">
                                            <label class="form-label span3">
                                                <%$form_config['men_notification_type']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div   ">
                                                <%assign var="opt_selected" value=$data['men_notification_type']%>
                                                <%$this->dropdown->display("men_notification_type","men_notification_type","  title='<%$this->lang->line('NOTIFICATIONS_NOTIFICATION_TYPE')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'NOTIFICATIONS_NOTIFICATION_TYPE')%>'  ", "|||", "", $opt_selected,"men_notification_type")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='men_notification_typeErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid" id="cc_sh_men_subject">
                                            <label class="form-label span3">
                                                <%$form_config['men_subject']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div   ">
                                                <input type="text" placeholder="" value="<%$data['men_subject']|@htmlentities%>" name="men_subject" id="men_subject" title="<%$this->lang->line('NOTIFICATIONS_SUBJECT')%>"  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='men_subjectErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid" id="cc_sh_men_content">
                                            <label class="form-label span3">
                                                <%$form_config['men_content']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div   ">
                                                <textarea placeholder=""  name="men_content" id="men_content" title="<%$this->lang->line('NOTIFICATIONS_CONTENT')%>"  class='elastic frm-size-medium'  ><%$data['men_content']%></textarea>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='men_contentErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid" id="cc_sh_men_error">
                                            <label class="form-label span3">
                                                <%$form_config['men_error']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div   ">
                                                <textarea placeholder=""  name="men_error" id="men_error" title="<%$this->lang->line('NOTIFICATIONS_ERROR')%>"  class='elastic frm-size-medium'  ><%$data['men_error']%></textarea>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='men_errorErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid" id="cc_sh_men_status">
                                            <label class="form-label span3">
                                                <%$form_config['men_status']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div   ">
                                                <%assign var="opt_selected" value=$data['men_status']%>
                                                <%$this->dropdown->display("men_status","men_status","  title='<%$this->lang->line('NOTIFICATIONS_STATUS')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'NOTIFICATIONS_STATUS')%>'  ", "|||", "", $opt_selected,"men_status")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='men_statusErr'></label></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="frm-bot-btn <%$rl_theme_arr['frm_stand_action_bar']%> <%$rl_theme_arr['frm_stand_action_btn']%>">
                                <%if $rl_theme_arr['frm_stand_ctrls_view'] eq 'No'%>
                                    <%assign var='rm_ctrl_directions' value=true%>
                                <%/if%>
                                <%include file="notifications_add_buttons.tpl"%>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Module Form Javascript -->
<%javascript%>
            
    var el_form_settings = {}, elements_uni_arr = {}, child_rules_arr = {}, google_map_json = {}, pre_cond_code_arr = [];
    el_form_settings['module_name'] = '<%$module_name%>'; 
    el_form_settings['extra_hstr'] = '<%$extra_hstr%>';
    el_form_settings['extra_qstr'] = '<%$extra_qstr%>';
    el_form_settings['upload_form_file_url'] = admin_url+"<%$mod_enc_url['upload_form_file']%>?<%$extra_qstr%>";
    el_form_settings['get_chosen_auto_complete_url'] = admin_url+"<%$mod_enc_url['get_chosen_auto_complete']%>?<%$extra_qstr%>";
    el_form_settings['token_auto_complete_url'] = admin_url+"<%$mod_enc_url['get_token_auto_complete']%>?<%$extra_qstr%>";
    el_form_settings['tab_wise_block_url'] = admin_url+"<%$mod_enc_url['get_tab_wise_block']%>?<%$extra_qstr%>";
    el_form_settings['parent_source_options_url'] = "<%$mod_enc_url['parent_source_options']%>?<%$extra_qstr%>";
    el_form_settings['jself_switchto_url'] =  admin_url+'<%$switch_cit["url"]%>';
    el_form_settings['callbacks'] = [];
    
    google_map_json = $.parseJSON('<%$google_map_arr|@json_encode%>');
    child_rules_arr = {};
            
    <%if $auto_arr|@is_array && $auto_arr|@count gt 0%>
        setTimeout(function(){
            <%foreach name=i from=$auto_arr item=v key=k%>
                if($("#<%$k%>").is("select")){
                    $("#<%$k%>").ajaxChosen({
                        dataType: "json",
                        type: "POST",
                        url: el_form_settings.get_chosen_auto_complete_url+"&unique_name=<%$k%>&mode=<%$mod_enc_mode[$mode]%>&id=<%$enc_id%>"
                        },{
                        loadingImg: admin_image_url+"chosen-loading.gif"
                    });
                }
            <%/foreach%>
        }, 500);
    <%/if%>        
    el_form_settings['jajax_submit_func'] = '';
    el_form_settings['jajax_submit_back'] = '';
    el_form_settings['jajax_action_url'] = '<%$admin_url%><%$mod_enc_url["add_action"]%>?<%$extra_qstr%>';
    el_form_settings['save_as_draft'] = 'No';
    el_form_settings['buttons_arr'] = [];
    el_form_settings['message_arr'] = {
        "delete_message" : "<%$this->general->processMessageLabel('ACTION_ARE_YOU_SURE_WANT_TO_DELETE_THIS_RECORD_C63')%>"
    };
    
    callSwitchToSelf();
<%/javascript%>
<%$this->js->add_js('admin/notifications_add_js.js')%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
<%javascript%>
    Project.modules.notifications.callEvents();
<%/javascript%>