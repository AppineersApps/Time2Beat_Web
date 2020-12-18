<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-view-container">
    <%include file="users_management_add_strip.tpl"%>
    <div class="<%$module_name%>" data-form-name="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
            <input type="hidden" id="projmod" name="projmod" value="users_management" />
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Module Tabs & Top Detail View -->
            <div class="top-frm-tab-layout" id="top_frm_tab_layout">
            </div>
            <!-- Middle Content -->
            <div id="scrollable_content" class="scrollable-content popup-content top-block-spacing ">
                <!-- Module View Block -->
                <div id="users_management" class="frm-module-block frm-view-block frm-stand-view">
                    <!-- Form Hidden Fields Unit -->
                    <input type="hidden" id="id" name="id" value="<%$enc_id%>" />
                    <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>" />
                    <input type="hidden" id="ctrl_flow" name="ctrl_flow" value="<%$ctrl_flow%>" />
                    <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>" />
                    <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>" />
                    <input type="hidden" name="u_password" id="u_password" value="<%$data['u_password']|@htmlentities%>"  class='ignore-valid ' />
                    <input type="hidden" name="u_latitude" id="u_latitude" value="<%$data['u_latitude']|@htmlentities%>"  class='ignore-valid ' />
                    <input type="hidden" name="u_longitude" id="u_longitude" value="<%$data['u_longitude']|@htmlentities%>"  class='ignore-valid ' />
                    <input type="hidden" name="u_push_notify" id="u_push_notify" value="<%$data['u_push_notify']%>"  class='ignore-valid ' />
                    <input type="hidden" name="u_one_time_transaction" id="u_one_time_transaction" value="<%$data['u_one_time_transaction']%>"  class='ignore-valid ' />
                    <input type="hidden" name="u_access_token" id="u_access_token" value="<%$data['u_access_token']|@htmlentities%>"  class='ignore-valid ' />
                    <input type="hidden" name="u_reset_password_code" id="u_reset_password_code" value="<%$data['u_reset_password_code']|@htmlentities%>"  class='ignore-valid ' />
                    <input type="hidden" name="u_email_verification_code" id="u_email_verification_code" value="<%$data['u_email_verification_code']|@htmlentities%>"  class='ignore-valid ' />
                    <input type="hidden" name="u_email_verified" id="u_email_verified" value="<%$data['u_email_verified']%>"  class='ignore-valid ' />
                    <input type="hidden" name="u_social_login_type" id="u_social_login_type" value="<%$data['u_social_login_type']%>"  class='ignore-valid ' />
                    <input type="hidden" name="u_social_login_id" id="u_social_login_id" value="<%$data['u_social_login_id']|@htmlentities%>"  class='ignore-valid ' />
                    <input type="hidden" name="u_device_type" id="u_device_type" value="<%$data['u_device_type']%>"  class='ignore-valid ' />
                    <input type="hidden" name="u_device_token" id="u_device_token" value="<%$data['u_device_token']|@htmlentities%>"  class='ignore-valid ' />
                    <input type="hidden" name="u_added_at" id="u_added_at" value="<%$this->general->dateSystemFormat($data['u_added_at'])%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
                    <input type="hidden" name="u_updated_at" id="u_updated_at" value="<%$this->general->dateSystemFormat($data['u_updated_at'])%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
                    <input type="hidden" name="u_device_model" id="u_device_model" value="<%$data['u_device_model']|@htmlentities%>"  class='ignore-valid ' />
                    <input type="hidden" name="u_device_os" id="u_device_os" value="<%$data['u_device_os']|@htmlentities%>"  class='ignore-valid ' />
                    <!-- Form Display Fields Unit -->
                    <div class="main-content-block " id="main_content_block">
                        <div style="width:98%;" class="frm-block-layout pad-calc-container">
                            <div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
                                <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('USERS_MANAGEMENT_USERS_MANAGEMENT')%></h4></div>
                                <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                    <div class="form-row row-fluid " id="cc_sh_u_profile_image">
                                        <label class="form-label span3">
                                            <%$form_config['u_profile_image']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>">
                                            <%$img_html['u_profile_image']%>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_u_first_name">
                                        <label class="form-label span3">
                                            <%$form_config['u_first_name']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>">
                                            <span class="frm-data-label"><strong><%$data['u_first_name']%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_u_last_name">
                                        <label class="form-label span3">
                                            <%$form_config['u_last_name']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>">
                                            <span class="frm-data-label"><strong><%$data['u_last_name']%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_u_user_name">
                                        <label class="form-label span3">
                                            <%$form_config['u_user_name']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>">
                                            <span class="frm-data-label"><strong><%$data['u_user_name']%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_u_email">
                                        <label class="form-label span3">
                                            <%$form_config['u_email']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>">
                                            <span class="frm-data-label"><strong><%$data['u_email']%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_u_mobile_no">
                                        <label class="form-label span3">
                                            <%$form_config['u_mobile_no']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>">
                                            <span class="frm-data-label"><strong><%$data['u_mobile_no']%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_u_dob">
                                        <label class="form-label span3">
                                            <%$form_config['u_dob']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%else%>input-append text-append-prepend<%/if%>">
                                            <span class="frm-data-label"><strong><%$this->general->dateSystemFormat($data['u_dob'])%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_u_address">
                                        <label class="form-label span3">
                                            <%$form_config['u_address']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div ">
                                            <span class="frm-data-label"><strong><%$this->general->processQuotes($data['u_address'])%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_u_city">
                                        <label class="form-label span3">
                                            <%$form_config['u_city']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>">
                                            <span class="frm-data-label"><strong><%$data['u_city']%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_u_state_id">
                                        <label class="form-label span3">
                                            <%$form_config['u_state_id']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>">
                                            <span class="frm-data-label"><strong><%$this->general->displayKeyValueData($data['u_state_id'], $opt_arr['u_state_id'])%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_u_zip_code">
                                        <label class="form-label span3">
                                            <%$form_config['u_zip_code']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>">
                                            <span class="frm-data-label"><strong><%$data['u_zip_code']%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_u_terms_conditions_version">
                                        <label class="form-label span3">
                                            <%$form_config['u_terms_conditions_version']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>">
                                            <span class="frm-data-label"><strong><%$data['u_terms_conditions_version']%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_u_privacy_policy_version">
                                        <label class="form-label span3">
                                            <%$form_config['u_privacy_policy_version']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>">
                                            <span class="frm-data-label"><strong><%$data['u_privacy_policy_version']%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_u_deleted_at">
                                        <label class="form-label span3">
                                            <%$form_config['u_deleted_at']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%else%>input-append text-append-prepend<%/if%>">
                                            <span class="frm-data-label"><strong><%$this->general->dateSystemFormat($data['u_deleted_at'])%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_u_status">
                                        <label class="form-label span3">
                                            <%$form_config['u_status']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div ">
                                            <span class="frm-data-label"><strong><%$this->general->displayKeyValueData($data['u_status'], $opt_arr['u_status'])%></strong></span>
                                        </div>
                                    </div>
                                     <div class="form-row row-fluid " id="cc_sh_u_status">
                                        <label class="form-label span3">
                                            <%$form_config['u_log_status_updated']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div ">
                                            <span class="frm-data-label"><strong><%$this->general->displayKeyValueData($data['u_log_status_updated'], $opt_arr['u_log_status_updated'])%></strong></span>
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

<%$this->js->add_js("admin/custom/hideDraggableOption.js")%>
<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
