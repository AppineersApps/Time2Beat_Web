<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-form-container">
    <%include file="db_change_log_add_strip.tpl"%>
    <div class="<%$module_name%>" data-form-name="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
            <input type="hidden" id="projmod" name="projmod" value="db_change_log" />
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Module Tabs & Top Detail View -->
            <div class="top-frm-tab-layout" id="top_frm_tab_layout">
            </div>
            <!-- Middle Content -->
            <div id="scrollable_content" class="scrollable-content top-block-spacing ">
                <div id="db_change_log" class="frm-module-block frm-elem-block frm-stand-view">
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
                                    <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('DB_CHANGE_LOG_DB_CHANGE_LOG')%></h4></div>
                                    <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                        <div class="form-row row-fluid " id="cc_sh_mdc_table_name">
                                            <label class="form-label span3 ">
                                                <%$form_config['mdc_table_name']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mdc_table_name']|@htmlentities%>" name="mdc_table_name" id="mdc_table_name" title="<%$this->lang->line('DB_CHANGE_LOG_TABLE_NAME')%>"  class='frm-size-medium'  readonly='true'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mdc_table_nameErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mdc_operation">
                                            <label class="form-label span3 ">
                                                <%$form_config['mdc_operation']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['mdc_operation']%>
                                                <%$this->dropdown->display("mdc_operation","mdc_operation","  title='<%$this->lang->line('DB_CHANGE_LOG_OPERATION')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  disabled='true'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'DB_CHANGE_LOG_OPERATION')%>'  ", "|||", "", $opt_selected,"mdc_operation")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mdc_operationErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mdc_primary_key">
                                            <label class="form-label span3 ">
                                                <%$form_config['mdc_primary_key']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mdc_primary_key']|@htmlentities%>" name="mdc_primary_key" id="mdc_primary_key" title="<%$this->lang->line('DB_CHANGE_LOG_PRIMARY_KEY')%>"  class='frm-size-medium'  readonly='true'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mdc_primary_keyErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mdc_field_data">
                                            <label class="form-label span3 ">
                                                <%$form_config['mdc_field_data']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <textarea placeholder=""  name="mdc_field_data" id="mdc_field_data" title="<%$this->lang->line('DB_CHANGE_LOG_FIELD_DATA')%>"  class='elastic frm-size-medium'  readonly='true'  ><%$data['mdc_field_data']%></textarea>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mdc_field_dataErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mdc_source">
                                            <label class="form-label span3 ">
                                                <%$form_config['mdc_source']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['mdc_source']%>
                                                <%$this->dropdown->display("mdc_source","mdc_source","  title='<%$this->lang->line('DB_CHANGE_LOG_SOURCE')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  disabled='true'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'DB_CHANGE_LOG_SOURCE')%>'  ", "|||", "", $opt_selected,"mdc_source")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mdc_sourceErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mdc_logged_by_id">
                                            <label class="form-label span3 ">
                                                <%$form_config['mdc_logged_by_id']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mdc_logged_by_id']|@htmlentities%>" name="mdc_logged_by_id" id="mdc_logged_by_id" title="<%$this->lang->line('DB_CHANGE_LOG_LOGGED_BY_ID')%>"  class='frm-size-medium'  readonly='true'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mdc_logged_by_idErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mdc_logged_name">
                                            <label class="form-label span3 ">
                                                <%$form_config['mdc_logged_name']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mdc_logged_name']|@htmlentities%>" name="mdc_logged_name" id="mdc_logged_name" title="<%$this->lang->line('DB_CHANGE_LOG_LOGGED_NAME')%>"  class='frm-size-medium'  readonly='true'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mdc_logged_nameErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mdc_date_added">
                                            <label class="form-label span3 ">
                                                <%$form_config['mdc_date_added']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%else%>input-append text-append-prepend<%/if%> ">
                                                <%if $mode eq "Update"%>
                                                    <input type="hidden" name="mdc_date_added" id="mdc_date_added" value="<%$this->general->dateTimeSystemFormat($data['mdc_date_added'])%>" class="ignore-valid view-label-only"  class='frm-datepicker ctrl-append-prepend frm-size-medium'  readonly='true'  aria-date-format='<%$this->general->getAdminJSFormats('date_and_time', 'dateFormat')%>'  aria-time-format='<%$this->general->getAdminJSFormats('date_and_time', 'timeFormat')%>'  aria-format-type='datetime' />
                                                    <%assign var="display_date_time" value=$this->general->dateTimeSystemFormat($data['mdc_date_added'])%>
                                                    <strong>
                                                        <%if $display_date_time neq ""%>
                                                            <%$display_date_time%>
                                                        <%else%>
                                                        <%/if%>
                                                    </strong>
                                                <%else%>
                                                    <input type="text" value="<%$this->general->dateTimeSystemFormat($data['mdc_date_added'])%>" name="mdc_date_added" placeholder=""  id="mdc_date_added" title="<%$this->lang->line('DB_CHANGE_LOG_DATE_ADDED')%>"  class='frm-datepicker ctrl-append-prepend frm-size-medium'  readonly='true'  aria-date-format='<%$this->general->getAdminJSFormats('date_and_time', 'dateFormat')%>'  aria-time-format='<%$this->general->getAdminJSFormats('date_and_time', 'timeFormat')%>'  aria-format-type='datetime'  />
                                                    <span class='add-on text-addon date-time-append-class icomoon-icon-calendar'></span>
                                                <%/if%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mdc_date_addedErr'></label></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="frm-bot-btn <%$rl_theme_arr['frm_stand_action_bar']%> <%$rl_theme_arr['frm_stand_action_btn']%>">
                                <%if $rl_theme_arr['frm_stand_ctrls_view'] eq 'No'%>
                                    <%assign var='rm_ctrl_directions' value=true%>
                                <%/if%>
                                <%include file="db_change_log_add_buttons.tpl"%>
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
<%$this->js->add_js('admin/db_change_log_add_js.js')%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
<%javascript%>
    Project.modules.db_change_log.callEvents();
<%/javascript%>