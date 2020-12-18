<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-form-container">
    <%include file="api_access_log_add_strip.tpl"%>
    <div class="<%$module_name%>" data-form-name="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
            <input type="hidden" id="projmod" name="projmod" value="api_access_log" />
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Module Tabs & Top Detail View -->
            <div class="top-frm-tab-layout" id="top_frm_tab_layout">
            </div>
            <!-- Middle Content -->
            <div id="scrollable_content" class="scrollable-content top-block-spacing ">
                <div id="api_access_log" class="frm-module-block frm-elem-block frm-stand-view">
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
                                    <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('API_ACCESS_LOG_API_ACCESS_LOG')%></h4></div>
                                    <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                        <div class="form-row row-fluid " id="cc_sh_maa_request_uri">
                                            <label class="form-label span3 ">
                                                <%$form_config['maa_request_uri']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['maa_request_uri']|@htmlentities%>" name="maa_request_uri" id="maa_request_uri" title="<%$this->lang->line('API_ACCESS_LOG_REQUEST_URI')%>"  class='frm-size-medium'  readonly='true'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='maa_request_uriErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_maa_access_date">
                                            <label class="form-label span3 ">
                                                <%$form_config['maa_access_date']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  input-append text-append-prepend  ">
                                                <input type="text" value="<%$this->general->dateDefinedFormat('Y-m-d',$data['maa_access_date'])%>" placeholder="" name="maa_access_date" id="maa_access_date" title="<%$this->lang->line('API_ACCESS_LOG_ACCESS_DATE')%>"  class='frm-datepicker ctrl-append-prepend frm-size-medium'  readonly='true'  aria-date-format='yy-mm-dd'  aria-format-type='date'  />
                                                <span class='add-on text-addon date-append-class icomoon-icon-calendar'></span>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='maa_access_dateErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_maa_i_paddress">
                                            <label class="form-label span3 ">
                                                <%$form_config['maa_i_paddress']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['maa_i_paddress']|@htmlentities%>" name="maa_i_paddress" id="maa_i_paddress" title="<%$this->lang->line('API_ACCESS_LOG_IP_ADDRESS')%>"  class='frm-size-medium'  readonly='true'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='maa_i_paddressErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_maa_platform">
                                            <label class="form-label span3 ">
                                                <%$form_config['maa_platform']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['maa_platform']|@htmlentities%>" name="maa_platform" id="maa_platform" title="<%$this->lang->line('API_ACCESS_LOG_PLATFORM')%>"  class='frm-size-medium'  readonly='true'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='maa_platformErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_maa_browser">
                                            <label class="form-label span3 ">
                                                <%$form_config['maa_browser']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['maa_browser']|@htmlentities%>" name="maa_browser" id="maa_browser" title="<%$this->lang->line('API_ACCESS_LOG_BROWSER')%>"  class='frm-size-medium'  readonly='true'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='maa_browserErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_maa_input_params">
                                            <label class="form-label span3 ">
                                                <%$form_config['maa_input_params']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <textarea placeholder=""  name="maa_input_params" id="maa_input_params" title="<%$this->lang->line('API_ACCESS_LOG_INPUT_PARAMS')%>"  class='elastic frm-size-medium'  readonly='true'  ><%$data['maa_input_params']%></textarea>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='maa_input_paramsErr'></label></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="frm-bot-btn <%$rl_theme_arr['frm_stand_action_bar']%> <%$rl_theme_arr['frm_stand_action_btn']%>">
                                <%if $rl_theme_arr['frm_stand_ctrls_view'] eq 'No'%>
                                    <%assign var='rm_ctrl_directions' value=true%>
                                <%/if%>
                                <%include file="api_access_log_add_buttons.tpl"%>
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
<%$this->js->add_js('admin/api_access_log_add_js.js')%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
<%javascript%>
    Project.modules.api_access_log.callEvents();
<%/javascript%>