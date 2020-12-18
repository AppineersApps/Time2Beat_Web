<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-form-container">
    <%include file="pushnotifications_add_strip.tpl"%>
    <div class="<%$module_name%>" data-form-name="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
            <input type="hidden" id="projmod" name="projmod" value="pushnotifications" />
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Module Tabs & Top Detail View -->
            <div class="top-frm-tab-layout" id="top_frm_tab_layout">
            </div>
            <!-- Middle Content -->
            <div id="scrollable_content" class="scrollable-content top-block-spacing ">
                <div id="pushnotifications" class="frm-module-block frm-elem-block frm-stand-view">
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
                        <div class="main-content-block" id="main_content_block">
                            <div style="width:98%" class="frm-block-layout pad-calc-container">
                                <div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
                                    <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('PUSHNOTIFICATIONS_PUSH_NOTIFICATIONS')%></h4></div>
                                    <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                        <div class="form-row row-fluid " id="cc_sh_mpn_unique_id">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_unique_id']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mpn_unique_id']|@htmlentities%>" name="mpn_unique_id" id="mpn_unique_id" title="<%$this->lang->line('PUSHNOTIFICATIONS_UNIQUE_ID')%>"  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_unique_idErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_device_id">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_device_id']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <textarea placeholder=""  name="mpn_device_id" id="mpn_device_id" title="<%$this->lang->line('PUSHNOTIFICATIONS_DEVICE_ID')%>"  class='elastic frm-size-medium'  ><%$data['mpn_device_id']%></textarea>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_device_idErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_mode">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_mode']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['mpn_mode']%>
                                                <%$this->dropdown->display("mpn_mode","mpn_mode","  title='<%$this->lang->line('PUSHNOTIFICATIONS_MODE')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'PUSHNOTIFICATIONS_MODE')%>'  ", "|||", "", $opt_selected,"mpn_mode")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_modeErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_notify_code">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_notify_code']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['mpn_notify_code']%>
                                                <%$this->dropdown->display("mpn_notify_code","mpn_notify_code","  title='<%$this->lang->line('PUSHNOTIFICATIONS_NOTIFY_CODE')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'PUSHNOTIFICATIONS_NOTIFY_CODE')%>'  ", "|||", "", $opt_selected,"mpn_notify_code")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_notify_codeErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_sound">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_sound']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mpn_sound']|@htmlentities%>" name="mpn_sound" id="mpn_sound" title="<%$this->lang->line('PUSHNOTIFICATIONS_SOUND')%>"  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_soundErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_badge">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_badge']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mpn_badge']|@htmlentities%>" name="mpn_badge" id="mpn_badge" title="<%$this->lang->line('PUSHNOTIFICATIONS_BADGE')%>"  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_badgeErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_title">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_title']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mpn_title']|@htmlentities%>" name="mpn_title" id="mpn_title" title="<%$this->lang->line('PUSHNOTIFICATIONS_TITLE')%>"  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_titleErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_silent">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_silent']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['mpn_silent']%>
                                                <%$this->dropdown->display("mpn_silent","mpn_silent","  title='<%$this->lang->line('PUSHNOTIFICATIONS_SILENT')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'PUSHNOTIFICATIONS_SILENT')%>'  ", "|||", "", $opt_selected,"mpn_silent")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_silentErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_uri">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_uri']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mpn_uri']|@htmlentities%>" name="mpn_uri" id="mpn_uri" title="<%$this->lang->line('PUSHNOTIFICATIONS_URI')%>"  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_uriErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_color">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_color']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mpn_color']|@htmlentities%>" name="mpn_color" id="mpn_color" title="<%$this->lang->line('PUSHNOTIFICATIONS_COLOR')%>"  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_colorErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_collapse_key">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_collapse_key']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mpn_collapse_key']|@htmlentities%>" name="mpn_collapse_key" id="mpn_collapse_key" title="<%$this->lang->line('PUSHNOTIFICATIONS_COLLAPSE_KEY')%>"  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_collapse_keyErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_message">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_message']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <textarea placeholder=""  name="mpn_message" id="mpn_message" title="<%$this->lang->line('PUSHNOTIFICATIONS_MESSAGE')%>"  class='elastic frm-size-medium'  ><%$data['mpn_message']%></textarea>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_messageErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_error">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_error']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <textarea placeholder=""  name="mpn_error" id="mpn_error" title="<%$this->lang->line('PUSHNOTIFICATIONS_ERROR')%>"  class='elastic frm-size-medium'  ><%$data['mpn_error']%></textarea>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_errorErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_vars_json">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_vars_json']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <textarea placeholder=""  name="mpn_vars_json" id="mpn_vars_json" title="<%$this->lang->line('PUSHNOTIFICATIONS_VARS_JSON')%>"  class='elastic frm-size-medium'  ><%$data['mpn_vars_json']%></textarea>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_vars_jsonErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_send_json">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_send_json']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <textarea placeholder=""  name="mpn_send_json" id="mpn_send_json" title="<%$this->lang->line('PUSHNOTIFICATIONS_SEND_JSON')%>"  class='elastic frm-size-medium'  ><%$data['mpn_send_json']%></textarea>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_send_jsonErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_device_type">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_device_type']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['mpn_device_type']%>
                                                <%$this->dropdown->display("mpn_device_type","mpn_device_type","  title='<%$this->lang->line('PUSHNOTIFICATIONS_DEVICE_TYPE')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'PUSHNOTIFICATIONS_DEVICE_TYPE')%>'  ", "|||", "", $opt_selected,"mpn_device_type")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_device_typeErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_push_time">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_push_time']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  input-append text-append-prepend  ">
                                                <input type="text" value="<%$this->general->dateDefinedFormat('Y-m-d',$data['mpn_push_time'])%>" placeholder="" name="mpn_push_time" id="mpn_push_time" title="<%$this->lang->line('PUSHNOTIFICATIONS_PUSH_TIME')%>"  class='frm-datepicker ctrl-append-prepend frm-size-medium'  aria-date-format='yy-mm-dd'  aria-format-type='date'  />
                                                <span class='add-on text-addon date-append-class icomoon-icon-calendar'></span>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_push_timeErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_expire_time">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_expire_time']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  input-append text-append-prepend  ">
                                                <input type="text" value="<%$this->general->dateDefinedFormat('Y-m-d',$data['mpn_expire_time'])%>" placeholder="" name="mpn_expire_time" id="mpn_expire_time" title="<%$this->lang->line('PUSHNOTIFICATIONS_EXPIRE_TIME')%>"  class='frm-datepicker ctrl-append-prepend frm-size-medium'  aria-date-format='yy-mm-dd'  aria-format-type='date'  />
                                                <span class='add-on text-addon date-append-class icomoon-icon-calendar'></span>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_expire_timeErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_expire_interval">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_expire_interval']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['mpn_expire_interval']%>
                                                <%$this->dropdown->display("mpn_expire_interval","mpn_expire_interval","  title='<%$this->lang->line('PUSHNOTIFICATIONS_EXPIRE_INTERVAL')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'PUSHNOTIFICATIONS_EXPIRE_INTERVAL')%>'  ", "|||", "", $opt_selected,"mpn_expire_interval")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_expire_intervalErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_add_date_time">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_add_date_time']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  input-append text-append-prepend  ">
                                                <input type="text" value="<%$this->general->dateDefinedFormat('Y-m-d',$data['mpn_add_date_time'])%>" placeholder="" name="mpn_add_date_time" id="mpn_add_date_time" title="<%$this->lang->line('PUSHNOTIFICATIONS_ADD_DATE_TIME')%>"  class='frm-datepicker ctrl-append-prepend frm-size-medium'  aria-date-format='yy-mm-dd'  aria-format-type='date'  />
                                                <span class='add-on text-addon date-append-class icomoon-icon-calendar'></span>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_add_date_timeErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_exe_date_time">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_exe_date_time']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  input-append text-append-prepend  ">
                                                <input type="text" value="<%$this->general->dateDefinedFormat('Y-m-d',$data['mpn_exe_date_time'])%>" placeholder="" name="mpn_exe_date_time" id="mpn_exe_date_time" title="<%$this->lang->line('PUSHNOTIFICATIONS_EXE_DATE_TIME')%>"  class='frm-datepicker ctrl-append-prepend frm-size-medium'  aria-date-format='yy-mm-dd'  aria-format-type='date'  />
                                                <span class='add-on text-addon date-append-class icomoon-icon-calendar'></span>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_exe_date_timeErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mpn_status">
                                            <label class="form-label span3 ">
                                                <%$form_config['mpn_status']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['mpn_status']%>
                                                <%$this->dropdown->display("mpn_status","mpn_status","  title='<%$this->lang->line('PUSHNOTIFICATIONS_STATUS')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'PUSHNOTIFICATIONS_STATUS')%>'  ", "|||", "", $opt_selected,"mpn_status")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mpn_statusErr'></label></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="frm-bot-btn <%$rl_theme_arr['frm_stand_action_bar']%> <%$rl_theme_arr['frm_stand_action_btn']%>">
                                <%if $rl_theme_arr['frm_stand_ctrls_view'] eq 'No'%>
                                    <%assign var='rm_ctrl_directions' value=true%>
                                <%/if%>
                                <%include file="pushnotifications_add_buttons.tpl"%>
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
<%$this->js->add_js('admin/pushnotifications_add_js.js')%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
<%javascript%>
    Project.modules.pushnotifications.callEvents();
<%/javascript%>