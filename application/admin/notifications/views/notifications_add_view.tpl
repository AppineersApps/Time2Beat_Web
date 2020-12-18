<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-view-container">
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
                <!-- Module View Block -->
                <div id="notifications" class="frm-module-block frm-view-block frm-stand-view">
                    <!-- Form Hidden Fields Unit -->
                    <input type="hidden" id="id" name="id" value="<%$enc_id%>" />
                    <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>" />
                    <input type="hidden" id="ctrl_flow" name="ctrl_flow" value="<%$ctrl_flow%>" />
                    <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>" />
                    <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>" />
                    <input type="hidden" name="men_entity_id" id="men_entity_id" value="<%$data['men_entity_id']|@htmlentities%>"  class='ignore-valid ' />
                    <input type="hidden" name="men_group_id" id="men_group_id" value="<%$data['men_group_id']|@htmlentities%>"  class='ignore-valid ' />
                    <input type="hidden" name="men_entity_type" id="men_entity_type" value="<%$data['men_entity_type']%>"  class='ignore-valid ' />
                    <textarea style="display:none;" name="men_redirect_link" id="men_redirect_link"  class='ignore-valid ' ><%$data['men_redirect_link']%></textarea>
                    <input type="hidden" name="men_send_date_time" id="men_send_date_time" value="<%$this->general->dateSystemFormat($data['men_send_date_time'])%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
                    <!-- Form Display Fields Unit -->
                    <div class="main-content-block" id="main_content_block">
                        <div style="width:98%;" class="frm-block-layout pad-calc-container">
                            <div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
                                <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('NOTIFICATIONS_NOTIFICATIONS')%></h4></div>
                                <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                    <div class="form-row row-fluid" id="cc_sh_men_receiver">
                                        <label class="form-label span3">
                                            <%$form_config['men_receiver']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  ">
                                            <strong><%$data['men_receiver']%></strong>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid" id="cc_sh_men_notification_type">
                                        <label class="form-label span3">
                                            <%$form_config['men_notification_type']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  ">
                                            <strong><%$this->general->displayKeyValueData($data['men_notification_type'], $opt_arr['men_notification_type'])%></strong>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid" id="cc_sh_men_subject">
                                        <label class="form-label span3">
                                            <%$form_config['men_subject']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  ">
                                            <strong><%$data['men_subject']%></strong>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid" id="cc_sh_men_content">
                                        <label class="form-label span3">
                                            <%$form_config['men_content']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  ">
                                            <strong><%$data['men_content']%></strong>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid" id="cc_sh_men_error">
                                        <label class="form-label span3">
                                            <%$form_config['men_error']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  ">
                                            <strong><%$data['men_error']%></strong>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid" id="cc_sh_men_status">
                                        <label class="form-label span3">
                                            <%$form_config['men_status']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  ">
                                            <strong><%$this->general->displayKeyValueData($data['men_status'], $opt_arr['men_status'])%></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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

    callSwitchToSelf();
<%/javascript%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
