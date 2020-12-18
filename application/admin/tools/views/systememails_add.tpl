<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-form-container">
    <%include file="systememails_add_strip.tpl"%>
    <div class="<%$module_name%>" data-form-name="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
            <input type="hidden" id="projmod" name="projmod" value="systememails" />
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Module Tabs & Top Detail View -->
            <div class="top-frm-tab-layout" id="top_frm_tab_layout">
            </div>
            <!-- Middle Content -->
            <div id="scrollable_content" class="scrollable-content popup-content top-block-spacing ">
                <div id="systememails" class="frm-module-block frm-elem-block frm-stand-view">
                    <!-- Module Form Block -->
                    <form name="frmaddupdate" id="frmaddupdate" action="<%$admin_url%><%$mod_enc_url['add_action']%>?<%$extra_qstr%>" method="post"  enctype="multipart/form-data">
                        <!-- Form Hidden Fields Unit -->
                        <input type="hidden" id="id" name="id" value="<%$enc_id%>" />
                        <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>" />
                        <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>" />
                        <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>" />
                        <input type="hidden" id="draft_uniq_id" name="draft_uniq_id" value="<%$draft_uniq_id%>" />
                        <input type="hidden" id="extra_hstr" name="extra_hstr" value="<%$extra_hstr%>" />
                        <!-- Form Dispaly Fields Unit -->
                        <div class="main-content-block " id="main_content_block">
                            <div style="width:98%" class="frm-block-layout pad-calc-container">
                                <div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
                                    <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('SYSTEMEMAILS_SYSTEM_EMAILS')%></h4></div>
                                    <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                        <div class="form-row row-fluid " id="cc_sh_mse_email_code">
                                            <label class="form-label span3 ">
                                                <%$form_config['mse_email_code']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mse_email_code']|@htmlentities%>" name="mse_email_code" id="mse_email_code" title="<%$this->lang->line('SYSTEMEMAILS_EMAIL_CODE')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mse_email_codeErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mse_email_title">
                                            <label class="form-label span3 ">
                                                <%$form_config['mse_email_title']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mse_email_title']|@htmlentities%>" name="mse_email_title" id="mse_email_title" title="<%$this->lang->line('SYSTEMEMAILS_EMAIL_TITLE')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mse_email_titleErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mse_from_name">
                                            <label class="form-label span3 ">
                                                <%$form_config['mse_from_name']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mse_from_name']|@htmlentities%>" name="mse_from_name" id="mse_from_name" title="<%$this->lang->line('SYSTEMEMAILS_FROM_NAME')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mse_from_nameErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mse_from_email">
                                            <label class="form-label span3 ">
                                                <%$form_config['mse_from_email']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mse_from_email']|@htmlentities%>" name="mse_from_email" id="mse_from_email" title="<%$this->lang->line('SYSTEMEMAILS_FROM_EMAIL')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mse_from_emailErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mse_bcc_email">
                                            <label class="form-label span3 ">
                                                <%$form_config['mse_bcc_email']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mse_bcc_email']|@htmlentities%>" name="mse_bcc_email" id="mse_bcc_email" title="<%$this->lang->line('SYSTEMEMAILS_BCC_EMAIL')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mse_bcc_emailErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mse_cc_email">
                                            <label class="form-label span3 ">
                                                <%$form_config['mse_cc_email']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mse_cc_email']|@htmlentities%>" name="mse_cc_email" id="mse_cc_email" title="<%$this->lang->line('SYSTEMEMAILS_CC_EMAIL')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mse_cc_emailErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mse_email_subject">
                                            <label class="form-label span3 ">
                                                <%$form_config['mse_email_subject']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mse_email_subject']|@htmlentities%>" name="mse_email_subject" id="mse_email_subject" title="<%$this->lang->line('SYSTEMEMAILS_EMAIL_SUBJECT')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mse_email_subjectErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mse_email_message">
                                            <label class="form-label span3 ">
                                                <%$form_config['mse_email_message']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  frm-editor-layout ">
                                                <textarea name="mse_email_message" id="mse_email_message" title="<%$this->lang->line('SYSTEMEMAILS_EMAIL_MESSAGE')%>"  style='width:80%;'  class='frm-size-medium frm-editor-small'  ><%$data['mse_email_message']%></textarea>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mse_email_messageErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mse_status">
                                            <label class="form-label span3 ">
                                                <%$form_config['mse_status']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['mse_status']%>
                                                <%$this->dropdown->display("mse_status","mse_status","  title='<%$this->lang->line('SYSTEMEMAILS_STATUS')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'SYSTEMEMAILS_STATUS')%>'  ", "|||", "", $opt_selected,"mse_status")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mse_statusErr'></label></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="frm-bot-btn <%$rl_theme_arr['frm_stand_action_bar']%> <%$rl_theme_arr['frm_stand_action_btn']%> popup-footer">
                                <%if $rl_theme_arr['frm_stand_ctrls_view'] eq 'No'%>
                                    <%assign var='rm_ctrl_directions' value=true%>
                                <%/if%>
                                <%include file="systememails_add_buttons.tpl"%>
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
        "delete_message" : "<%$this->general->processMessageLabel('ACTION_ARE_YOU_SURE_WANT_TO_DELETE_THIS_RECORD_C63')%>",
    };
    
    callSwitchToSelf();
<%/javascript%>
<%$this->js->add_js('admin/forms/tinymce/tinymce.min.js','admin/systememails_add_js.js')%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
<%javascript%>
    Project.modules.systememails.callEvents();
<%/javascript%>