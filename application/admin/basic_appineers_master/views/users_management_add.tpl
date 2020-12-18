<%if $this->input->is_ajax_request()%>
<%$this->js->clean_js()%>
<%/if%>
<div class="module-form-container">
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
<div id="users_management" class="frm-module-block frm-elem-block frm-stand-view">
<!-- Module Form Block -->
<form name="frmaddupdate" id="frmaddupdate" action="<%$admin_url%><%$mod_enc_url['add_action']%>?<%$extra_qstr%>" method="post"  enctype="multipart/form-data">
<!-- Form Hidden Fields Unit -->
<input type="hidden" id="id" name="id" value="<%$enc_id%>" />
<input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>" />
<input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>" />
<input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>" />
<input type="hidden" id="draft_uniq_id" name="draft_uniq_id" value="<%$draft_uniq_id%>" />
<input type="hidden" id="extra_hstr" name="extra_hstr" value="<%$extra_hstr%>" />
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
<!-- Form Dispaly Fields Unit -->
<div class="main-content-block " id="main_content_block">
<div style="width:98%" class="frm-block-layout pad-calc-container">
<div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
<div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('USERS_MANAGEMENT_USERS_MANAGEMENT')%></h4></div>
<div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
<div class="form-row row-fluid " id="cc_sh_u_profile_image">
<label class="form-label span3 ">
<%$form_config['u_profile_image']['label_lang']%>
</label>
<div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
<%if $mode eq "Add"%>
<div  class='btn-uploadify frm-size-medium' >
<input type="hidden" value="<%$data['u_profile_image']%>" name="old_u_profile_image" id="old_u_profile_image" />
<input type="hidden" value="<%$data['u_profile_image']%>" name="u_profile_image" id="u_profile_image"  aria-extensions="gif,png,jpg,jpeg,jpe,bmp,ico" aria-valid-size="<%$this->lang->line('GENERIC_LESS_THAN')%> (<) 100 MB"/>
<input type="hidden" value="<%$data['u_profile_image']%>" name="temp_u_profile_image" id="temp_u_profile_image"  />
<div id="upload_drop_zone_u_profile_image" class="upload-drop-zone"></div>
<div class="uploader upload-src-zone">
<input type="file" name="uploadify_u_profile_image" id="uploadify_u_profile_image" title="<%$this->lang->line('USERS_MANAGEMENT_PROFILE_IMAGE')%>" />
<span class="filename" id="preview_u_profile_image">
<%if $data['u_profile_image'] neq ''%>
<%$data['u_profile_image']%>
<%else%>
<%$this->lang->line('GENERIC_DROP_FILES_HERE_OR_CLICK_TO_UPLOAD')%>
<%/if%>
</span>
<span class="action">Choose File</span>
</div>
</div>
<%else%>
<input type="hidden" value="<%$data['u_profile_image']%>" name="u_profile_image" id="u_profile_image"  />
<%/if%>
<div class='upload-image-btn'>
<%$img_html['u_profile_image']%>
</div>
<span class="input-comment">
<a href="javascript://" style="text-decoration: none;" class="tipR" title="<%$this->lang->line('GENERIC_VALID_EXTENSIONS')%> : gif, png, jpg, jpeg, jpe, bmp, ico.<br><%$this->lang->line('GENERIC_VALID_SIZE')%> : <%$this->lang->line('GENERIC_LESS_THAN')%> (<) 100 MB."><span class="icomoon-icon-help"></span></a>
</span>
<div class='clear upload-progress' id='progress_u_profile_image'>
<div class='upload-progress-bar progress progress-striped active'>
<div class='bar' id='practive_u_profile_image'></div>
</div>
<div class='upload-cancel-div'><a class='upload-cancel' href='javascript://'>Cancel</a></div>
<div class='clear'></div>
</div>
<div class='clear'></div>
</div>
<div class="error-msg-form "><label class='error' id='u_profile_imageErr'></label></div>
</div>
<div class="form-row row-fluid " id="cc_sh_u_first_name">
<label class="form-label span3 ">
<%$form_config['u_first_name']['label_lang']%>
</label>
<div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
<%if $mode eq "Update"%>
<input type="hidden" class="ignore-valid" name="u_first_name" id="u_first_name" value="<%$data['u_first_name']|@htmlentities%>" />
<span class="frm-data-label">
<strong>
<%if $data['u_first_name'] neq ""%>
<%$data['u_first_name']%>
<%else%>
<%/if%>
</strong></span>
<%else%>
<input type="text" placeholder="" value="<%$data['u_first_name']|@htmlentities%>" name="u_first_name" id="u_first_name" title="<%$this->lang->line('USERS_MANAGEMENT_FIRST_NAME')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
<%/if%>
</div>
<div class="error-msg-form "><label class='error' id='u_first_nameErr'></label></div>
</div>
<div class="form-row row-fluid " id="cc_sh_u_last_name">
<label class="form-label span3 ">
<%$form_config['u_last_name']['label_lang']%>
</label>
<div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
<%if $mode eq "Update"%>
<input type="hidden" class="ignore-valid" name="u_last_name" id="u_last_name" value="<%$data['u_last_name']|@htmlentities%>" />
<span class="frm-data-label">
<strong>
<%if $data['u_last_name'] neq ""%>
<%$data['u_last_name']%>
<%else%>
<%/if%>
</strong></span>
<%else%>
<input type="text" placeholder="" value="<%$data['u_last_name']|@htmlentities%>" name="u_last_name" id="u_last_name" title="<%$this->lang->line('USERS_MANAGEMENT_LAST_NAME')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
<%/if%>
</div>
<div class="error-msg-form "><label class='error' id='u_last_nameErr'></label></div>
</div>
<div class="form-row row-fluid " id="cc_sh_u_email">
<label class="form-label span3 ">
<%$form_config['u_email']['label_lang']%>
</label>
<div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
<%if $mode eq "Update"%>
<input type="hidden" class="ignore-valid" name="u_email" id="u_email" value="<%$data['u_email']|@htmlentities%>" />
<span class="frm-data-label">
<strong>
<%if $data['u_email'] neq ""%>
<%$data['u_email']%>
<%else%>
<%/if%>
</strong></span>
<%else%>
<input type="text" placeholder="" value="<%$data['u_email']|@htmlentities%>" name="u_email" id="u_email" title="<%$this->lang->line('USERS_MANAGEMENT_EMAIL')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
<%/if%>
</div>
<div class="error-msg-form "><label class='error' id='u_emailErr'></label></div>
</div>
<div class="form-row row-fluid " id="cc_sh_u_mobile_no">
<label class="form-label span3 ">
<%$form_config['u_mobile_no']['label_lang']%>
</label>
<div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
<%if $mode eq "Update"%>
<input type="hidden" class="ignore-valid" name="u_mobile_no" id="u_mobile_no" value="<%$data['u_mobile_no']|@htmlentities%>" />
<span class="frm-data-label">
<strong>
<%if $data['u_mobile_no'] neq ""%>
<%$data['u_mobile_no']%>
<%else%>
<%/if%>
</strong></span>
<%else%>
<input type="text" placeholder="" value="<%$data['u_mobile_no']|@htmlentities%>" name="u_mobile_no" id="u_mobile_no" title="<%$this->lang->line('USERS_MANAGEMENT_MOBILE_NUMBER')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
<%/if%>
</div>
<div class="error-msg-form "><label class='error' id='u_mobile_noErr'></label></div>
</div>
<div class="form-row row-fluid " id="cc_sh_u_address">
<label class="form-label span3 ">
<%$form_config['u_address']['label_lang']%> 
</label>
<div class="form-right-div  ">
<span>
<div>
<div class="frm-gmf-address-label">
<span id="gmf_addr_label_u_address"><%$data['u_address']%></span>
<textarea style="display:none;" class="ignore-valid" name="u_address" id="u_address"><%$data['u_address']%></textarea>
</div>
<span  class='frm-gmf-medium frm-gmf-height-tiny' >
<textarea class="frm-gmf-address elastic" name="gmf_autocomplete_u_address" id="gmf_autocomplete_u_address" title="<%$this->lang->line('USERS_MANAGEMENT_ADDRESS')%>"><%$data['u_address']%></textarea>
</span>
</div>
<div class="frm-gmf-options">
<input type="radio" name="type_u_address" id="u_address-changetype-all" class="regular-radio" checked=true />
<label for="u_address-changetype-all">&nbsp;</label>
<label for="u_address-changetype-all">
<%if $this->lang->line("GENERIC_ALL") neq ""%>
<%$this->lang->line("GENERIC_ALL")%>
<%else%>
All
<%/if%>
</label>&nbsp;&nbsp;
<input type="radio" name="type_u_address" id="u_address-changetype-establishment" class="regular-radio" />
<label for="u_address-changetype-establishment">&nbsp;</label>
<label for="u_address-changetype-establishment">
<%if $this->lang->line("GENERIC_ESTABLISHMENTS") neq ""%>
<%$this->lang->line("GENERIC_ESTABLISHMENTS")%>
<%else%>
Establishments
<%/if%>
</label>&nbsp;&nbsp;
<input type="radio" name="type_u_address" id="u_address-changetype-geocode" class="regular-radio" />
<label for="u_address-changetype-geocode">&nbsp;</label>
<label for="u_address-changetype-geocode">
<%if $this->lang->line("GENERIC_GEOCODES") neq ""%>
<%$this->lang->line("GENERIC_GEOCODES")%>
<%else%>
Geocodes
<%/if%>
</label>
</div>
<!-- <span class='canvas_map'><div id='map_canvas_u_address'  class='frm-gmf-medium frm-gmf-height-tiny' ></div></span> -->
</span>
<%assign var=temp_map_arr value=[['name'=>'u_address','lat'=>'u_latitude','lng'=>'u_longitude','load'=>'No']]%>
<%if $google_map_arr|@is_array%>
<%assign var=google_map_arr value=$google_map_arr|@array_merge:$temp_map_arr%>
<%else%>
<%assign var=google_map_arr value=$temp_map_arr%>
<%/if%>
</div>
<div class="error-msg-form "><label class='error' id='u_addressErr'></label></div>
</div>
<div class="form-row row-fluid " id="cc_sh_u_city">
<label class="form-label span3 ">
<%$form_config['u_city']['label_lang']%>
</label>
<div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
<%if $mode eq "Update"%>
<input type="hidden" class="ignore-valid" name="u_city" id="u_city" value="<%$data['u_city']|@htmlentities%>" />
<span class="frm-data-label">
<strong>
<%if $data['u_city'] neq ""%>
<%$data['u_city']%>
<%else%>
<%/if%>
</strong></span>
<%else%>
<input type="text" placeholder="" value="<%$data['u_city']|@htmlentities%>" name="u_city" id="u_city" title="<%$this->lang->line('USERS_MANAGEMENT_CITY')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
<%/if%>
</div>
<div class="error-msg-form "><label class='error' id='u_cityErr'></label></div>
</div>
<div class="form-row row-fluid " id="cc_sh_u_state_name">
<label class="form-label span3 ">
<%$form_config['u_state_name']['label_lang']%>
</label>
<div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
<%if $mode eq "Update"%>
<input type="hidden" class="ignore-valid" name="u_state_name" id="u_state_name" value="<%$data['u_state_name']|@htmlentities%>" />
<span class="frm-data-label">
<strong>
<%if $data['u_state_name'] neq ""%>
<%$data['u_state_name']%>
<%else%>
<%/if%>
</strong></span>
<%else%>
<input type="text" placeholder="" value="<%$data['u_state_name']|@htmlentities%>" name="u_state_name" id="u_state_name" title="<%$this->lang->line('USERS_MANAGEMENT_STATE_NAME')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
<%/if%>
</div>
<div class="error-msg-form "><label class='error' id='u_state_nameErr'></label></div>
</div>
<div class="form-row row-fluid " id="cc_sh_u_zip_code">
<label class="form-label span3 ">
<%$form_config['u_zip_code']['label_lang']%>
</label>
<div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
<%if $mode eq "Update"%>
<input type="hidden" class="ignore-valid" name="u_zip_code" id="u_zip_code" value="<%$data['u_zip_code']|@htmlentities%>" />
<span class="frm-data-label">
<strong>
<%if $data['u_zip_code'] neq ""%>
<%$data['u_zip_code']%>
<%else%>
<%/if%>
</strong></span>
<%else%>
<input type="text" placeholder="" value="<%$data['u_zip_code']|@htmlentities%>" name="u_zip_code" id="u_zip_code" title="<%$this->lang->line('USERS_MANAGEMENT_ZIP_CODE')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
<%/if%>
</div>
<div class="error-msg-form "><label class='error' id='u_zip_codeErr'></label></div>
</div>
<div class="form-row row-fluid " id="cc_sh_u_terms_conditions_version">
<label class="form-label span3 ">
<%$form_config['u_terms_conditions_version']['label_lang']%>
</label>
<div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
<%if $mode eq "Update"%>
<input type="hidden" class="ignore-valid" name="u_terms_conditions_version" id="u_terms_conditions_version" value="<%$data['u_terms_conditions_version']|@htmlentities%>" />
<span class="frm-data-label">
<strong>
<%if $data['u_terms_conditions_version'] neq ""%>
<%$data['u_terms_conditions_version']%>
<%else%>
<%/if%>
</strong></span>
<%else%>
<input type="text" placeholder="" value="<%$data['u_terms_conditions_version']|@htmlentities%>" name="u_terms_conditions_version" id="u_terms_conditions_version" title="<%$this->lang->line('USERS_MANAGEMENT_TERMS_CONDITIONS_VERSION')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
<%/if%>
</div>
<div class="error-msg-form "><label class='error' id='u_terms_conditions_versionErr'></label></div>
</div>
<div class="form-row row-fluid " id="cc_sh_u_privacy_policy_version">
<label class="form-label span3 ">
<%$form_config['u_privacy_policy_version']['label_lang']%>
</label>
<div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
<%if $mode eq "Update"%>
<input type="hidden" class="ignore-valid" name="u_privacy_policy_version" id="u_privacy_policy_version" value="<%$data['u_privacy_policy_version']|@htmlentities%>" />
<span class="frm-data-label">
<strong>
<%if $data['u_privacy_policy_version'] neq ""%>
<%$data['u_privacy_policy_version']%>
<%else%>
<%/if%>
</strong></span>
<%else%>
<input type="text" placeholder="" value="<%$data['u_privacy_policy_version']|@htmlentities%>" name="u_privacy_policy_version" id="u_privacy_policy_version" title="<%$this->lang->line('USERS_MANAGEMENT_PRIVACY_POLICY_VERSION')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
<%/if%>
</div>
<div class="error-msg-form "><label class='error' id='u_privacy_policy_versionErr'></label></div>
</div>
<div class="form-row row-fluid " id="cc_sh_u_deleted_at">
<label class="form-label span3 ">
<%$form_config['u_deleted_at']['label_lang']%>
</label>
<div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%else%>input-append text-append-prepend<%/if%> ">
<%if $mode eq "Update"%>
<input type="hidden" name="u_deleted_at" id="u_deleted_at" value="<%$this->general->dateSystemFormat($data['u_deleted_at'])%>" class="ignore-valid view-label-only"  data-ctrl-type='date'  class='frm-datepicker ctrl-append-prepend frm-size-medium'  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
<%assign var="display_date" value=$this->general->dateSystemFormat($data['u_deleted_at'])%>
<span class="frm-data-label">
<strong>
<%if $display_date neq ""%>
<%$display_date%>
<%else%>
<%/if%>
</strong></span>
<%else%>
<input type="text" value="<%$this->general->dateSystemFormat($data['u_deleted_at'])%>" placeholder="" name="u_deleted_at" id="u_deleted_at" title="<%$this->lang->line('USERS_MANAGEMENT_DELETED_AT')%>"  data-ctrl-type='date'  class='frm-datepicker ctrl-append-prepend frm-size-medium'  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date'  />
<span class='add-on text-addon date-append-class icomoon-icon-calendar'></span>
<%/if%>
</div>
<div class="error-msg-form "><label class='error' id='u_deleted_atErr'></label></div>
</div>
<div class="form-row row-fluid " id="cc_sh_u_status">
<label class="form-label span3">
<%$form_config['u_status']['label_lang']%> <em>*</em>
</label>
<div class="form-right-div  ">
<%assign var="opt_selected" value=$data['u_status']%>
<%$this->dropdown->display("u_status","u_status","  title='<%$this->lang->line('USERS_MANAGEMENT_STATUS')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'USERS_MANAGEMENT_STATUS')%>'  ", "|||", "", $opt_selected,"u_status")%>
</div>
<div class="error-msg-form "><label class='error' id='u_statusErr'></label></div>
</div>

								<div class="form-row row-fluid " id="cc_sh_u_log_status_updated">
                                    <label class="form-label span3 ">
                                        <%$form_config['u_log_status_updated']['label_lang']%> <em>*</em> 
                                    </label> 
                                    <div class="form-right-div  ">
                                        <%assign var="opt_selected" value=$data['u_log_status_updated']%>
                                        <%$this->dropdown->display("u_log_status_updated","u_log_status_updated","  title='<%$this->lang->line('USERS_MANAGEMENT_LOG_STATUS')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'USERS_MANAGEMENT_LOG_STATUS')%>'  ", "|||", "", $opt_selected,"u_log_status_updated")%>
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='u_log_status_updatedErr'></label></div>
                                </div>
</div>
</div>
</div>
<div class="clear"></div>
<div class="frm-bot-btn <%$rl_theme_arr['frm_stand_action_bar']%> <%$rl_theme_arr['frm_stand_action_btn']%> popup-footer">
<%if $rl_theme_arr['frm_stand_ctrls_view'] eq 'No'%>
<%assign var='rm_ctrl_directions' value=true%>
<%/if%>
<%include file="users_management_add_buttons.tpl"%>
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
<%$this->js->add_js('admin/users_management_add_js.js')%>

<%$this->js->add_js("admin/custom/hideDraggableOption.js")%>
<%if $this->input->is_ajax_request()%>
<%$this->js->js_src()%>
<%/if%>
<%if $this->input->is_ajax_request()%>
<%$this->css->css_src()%>
<%/if%>
<%javascript%>
Project.modules.users_management.callEvents();
<%/javascript%>
