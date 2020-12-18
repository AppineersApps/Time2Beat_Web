<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-form-container">
    <%include file="customers_add_strip.tpl"%>
    <div class="<%$module_name%>" data-form-name="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
            <input type="hidden" id="projmod" name="projmod" value="customers" />
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Module Tabs & Top Detail View -->
            <div class="top-frm-tab-layout" id="top_frm_tab_layout">
            </div>
            <!-- Middle Content -->
            <div id="scrollable_content" class="scrollable-content popup-content top-block-spacing ">
                <div id="customers" class="frm-module-block frm-elem-block frm-stand-view">
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
                                    <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('CUSTOMERS_CUSTOMERS')%></h4></div>
                                    <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                        <div class="form-row row-fluid " id="cc_sh_mc_first_name">
                                            <label class="form-label span3 ">
                                                <%$form_config['mc_first_name']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mc_first_name']|@htmlentities%>" name="mc_first_name" id="mc_first_name" title="<%$this->lang->line('CUSTOMERS_FIRST_NAME')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mc_first_nameErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mc_last_name">
                                            <label class="form-label span3 ">
                                                <%$form_config['mc_last_name']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mc_last_name']|@htmlentities%>" name="mc_last_name" id="mc_last_name" title="<%$this->lang->line('CUSTOMERS_LAST_NAME')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mc_last_nameErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mc_email">
                                            <label class="form-label span3 ">
                                                <%$form_config['mc_email']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                                                <%if $mode eq "Update"%>
                                                    <input type="hidden" class="ignore-valid" name="mc_email" id="mc_email" value="<%$data['mc_email']|@htmlentities%>" />
                                                    <span class="frm-data-label">
                                                        <strong>
                                                            <%if $data['mc_email'] neq ""%>
                                                                <%$data['mc_email']%>
                                                            <%else%>
                                                            <%/if%>
                                                        </strong></span>
                                                    <%else%>
                                                        <input type="text" placeholder="" value="<%$data['mc_email']|@htmlentities%>" name="mc_email" id="mc_email" title="<%$this->lang->line('CUSTOMERS_EMAIL')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                                    <%/if%>
                                                </div>
                                                <div class="error-msg-form "><label class='error' id='mc_emailErr'></label></div>
                                            </div>
                                            <div class="form-row row-fluid " id="cc_sh_mc_user_name">
                                                <label class="form-label span3 ">
                                                    <%$form_config['mc_user_name']['label_lang']%> <em>*</em> 
                                                </label> 
                                                <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                                                    <%if $mode eq "Update"%>
                                                        <input type="hidden" class="ignore-valid" name="mc_user_name" id="mc_user_name" value="<%$data['mc_user_name']|@htmlentities%>" />
                                                        <span class="frm-data-label">
                                                            <strong>
                                                                <%if $data['mc_user_name'] neq ""%>
                                                                    <%$data['mc_user_name']%>
                                                                <%else%>
                                                                <%/if%>
                                                            </strong></span>
                                                        <%else%>
                                                            <input type="text" placeholder="" value="<%$data['mc_user_name']|@htmlentities%>" name="mc_user_name" id="mc_user_name" title="<%$this->lang->line('CUSTOMERS_USER_NAME')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                                        <%/if%>
                                                    </div>
                                                    <div class="error-msg-form "><label class='error' id='mc_user_nameErr'></label></div>
                                                </div>
                                                <div class="form-row row-fluid " id="cc_sh_mc_password">
                                                    <label class="form-label span3 ">
                                                        <%$form_config['mc_password']['label_lang']%> <em>*</em> 
                                                    </label> 
                                                    <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                                                        <%if $mode eq "Update"%>
                                                            <input type="hidden" name="mc_password" id="mc_password" value="<%$data['mc_password']%>" class="ignore-valid"/>
                                                            <span class="frm-data-label">
                                                                <strong>
                                                                    <%if $data['mc_password'] neq ""%>
                                                                        <%$data['mc_password']%>
                                                                    <%else%>
                                                                    <%/if%>
                                                                </strong></span>
                                                            <%else%>
                                                                <input placeholder="" autocomplete="off" type="password" value="<%$data['mc_password']%>" name="mc_password" id="mc_password" title="<%$this->lang->line('CUSTOMERS_PASSWORD')%>"  class='frm-size-medium'  />
                                                                <%if $mode eq "Add"%>
                                                                    <a href="javascript://" id="a_password_mc_password" class="tipR pwd-show-icon" onclick="adminShowHidePasswordField('mc_password');" title="<%$this->lang->line('GENERIC_CLICK_THIS_TO_SHOW_PASSWORD')%>"><span id="span_password_mc_password" class="icon16 iconic-icon-lock-fill" ></span></a>
                                                                <%/if%>
                                                            <%/if%>
                                                        </div>
                                                        <div class="error-msg-form "><label class='error' id='mc_passwordErr'></label></div>
                                                    </div>
                                                    <div class="form-row row-fluid " id="cc_sh_mc_profile_image">
                                                        <label class="form-label span3 ">
                                                            <%$form_config['mc_profile_image']['label_lang']%>
                                                        </label> 
                                                        <div class="form-right-div  ">
                                                            <div  class='btn-uploadify frm-size-medium' >
                                                                <input type="hidden" value="<%$data['mc_profile_image']%>" name="old_mc_profile_image" id="old_mc_profile_image" />
                                                                <input type="hidden" value="<%$data['mc_profile_image']%>" name="mc_profile_image" id="mc_profile_image"  aria-extensions="gif,png,jpg,jpeg" aria-valid-size="<%$this->lang->line('GENERIC_LESS_THAN')%> (<) 2 MB"/>
                                                                <input type="hidden" value="<%$data['mc_profile_image']%>" name="temp_mc_profile_image" id="temp_mc_profile_image"  />
                                                                <div id="upload_drop_zone_mc_profile_image" class="upload-drop-zone"></div>
                                                                <div class="uploader upload-src-zone">
                                                                    <input type="file" name="uploadify_mc_profile_image" id="uploadify_mc_profile_image" title="<%$this->lang->line('CUSTOMERS_PROFILE_IMAGE')%>" />
                                                                    <span class="filename" id="preview_mc_profile_image">
                                                                        <%if $data['mc_profile_image'] neq ''%>
                                                                            <%$data['mc_profile_image']%>
                                                                        <%else%>
                                                                            <%$this->lang->line('GENERIC_DROP_FILES_HERE_OR_CLICK_TO_UPLOAD')%>
                                                                        <%/if%>
                                                                    </span>
                                                                    <span class="action">Choose File</span>
                                                                </div>
                                                            </div>
                                                            <div class='upload-image-btn'>
                                                                <%$img_html['mc_profile_image']%>
                                                            </div>
                                                            <span class="input-comment">
                                                                <a href="javascript://" style="text-decoration: none;" class="tipR" title="<%$this->lang->line('GENERIC_VALID_EXTENSIONS')%> : gif, png, jpg, jpeg.<br><%$this->lang->line('GENERIC_VALID_SIZE')%> : <%$this->lang->line('GENERIC_LESS_THAN')%> (<) 2 MB."><span class="icomoon-icon-help"></span></a>
                                                            </span>
                                                            <div class='clear upload-progress' id='progress_mc_profile_image'>
                                                                <div class='upload-progress-bar progress progress-striped active'>
                                                                    <div class='bar' id='practive_mc_profile_image'></div>
                                                                </div>
                                                                <div class='upload-cancel-div'><a class='upload-cancel' href='javascript://'>Cancel</a></div>
                                                                <div class='clear'></div>
                                                            </div>
                                                            <div class='clear'></div>
                                                        </div>
                                                        <div class="error-msg-form "><label class='error' id='mc_profile_imageErr'></label></div>
                                                    </div>
                                                    <div class="form-row row-fluid " id="cc_sh_mc_status">
                                                        <label class="form-label span3 ">
                                                            <%$form_config['mc_status']['label_lang']%> <em>*</em> 
                                                        </label> 
                                                        <div class="form-right-div  ">
                                                            <%assign var="opt_selected" value=$data['mc_status']%>
                                                            <%$this->dropdown->display("mc_status","mc_status","  title='<%$this->lang->line('CUSTOMERS_STATUS')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'CUSTOMERS_STATUS')%>'  ", "|||", "", $opt_selected,"mc_status")%>
                                                        </div>
                                                        <div class="error-msg-form "><label class='error' id='mc_statusErr'></label></div>
                                                    </div>
                                                    <%if $mode eq "Update"%>
                                                        <div class="form-row row-fluid " id="cc_sh_mc_email_verified">
                                                            <label class="form-label span3 ">
                                                                <%$form_config['mc_email_verified']['label_lang']%>
                                                            </label> 
                                                            <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                                                                <%assign var="opt_selected" value=$data['mc_email_verified']%>
                                                                <%if $mode eq "Update"%>
                                                                    <input type="hidden" name="mc_email_verified" id="mc_email_verified" value="<%$data['mc_email_verified']%>" class="ignore-valid"/>
                                                                    <%assign var="combo_arr" value=$opt_arr["mc_email_verified"]%>
                                                                    <%assign var="opt_display" value=$this->general->displayKeyValueData($opt_selected, $combo_arr)%>
                                                                    <span class="frm-data-label">
                                                                        <strong>
                                                                            <%if $opt_display neq ""%>
                                                                                <%$opt_display%>
                                                                            <%else%>
                                                                            <%/if%>
                                                                        </strong></span>
                                                                    <%else%>
                                                                        <%$this->dropdown->display("mc_email_verified","mc_email_verified","  title='<%$this->lang->line('CUSTOMERS_EMAIL_VERIFIED')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'CUSTOMERS_EMAIL_VERIFIED')%>'  ", "|||", "", $opt_selected,"mc_email_verified")%>
                                                                    <%/if%>
                                                                </div>
                                                                <div class="error-msg-form "><label class='error' id='mc_email_verifiedErr'></label></div>
                                                            </div>
                                                        <%else%>
                                                            <input type="hidden" name="mc_email_verified" id="mc_email_verified" value="<%$data['mc_email_verified']%>"  class='ignore-valid'  />
                                                        <%/if%>
                                                        <%if $mode eq "Update"%>
                                                            <div class="form-row row-fluid " id="cc_sh_mc_registered_date">
                                                                <label class="form-label span3 ">
                                                                    <%$form_config['mc_registered_date']['label_lang']%>
                                                                </label> 
                                                                <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%else%>input-append text-append-prepend<%/if%> ">
                                                                    <%if $mode eq "Update"%>
                                                                        <input type="hidden" name="mc_registered_date" id="mc_registered_date" value="<%$this->general->dateTimeSystemFormat($data['mc_registered_date'])%>" class="ignore-valid view-label-only"  data-ctrl-type='date_and_time'  class='frm-datepicker ctrl-append-prepend frm-size-medium'  aria-date-format='<%$this->general->getAdminJSFormats('date_and_time', 'dateFormat')%>'  aria-time-format='<%$this->general->getAdminJSFormats('date_and_time', 'timeFormat')%>'  aria-format-type='datetime' />
                                                                        <%assign var="display_date_time" value=$this->general->dateTimeSystemFormat($data['mc_registered_date'])%>
                                                                        <span class="frm-data-label">
                                                                            <strong>
                                                                                <%if $display_date_time neq ""%>
                                                                                    <%$display_date_time%>
                                                                                <%else%>
                                                                                <%/if%>
                                                                            </strong></span>
                                                                        <%else%>
                                                                            <input type="text" value="<%$this->general->dateTimeSystemFormat($data['mc_registered_date'])%>" name="mc_registered_date" placeholder=""  id="mc_registered_date" title="<%$this->lang->line('CUSTOMERS_REGISTERED_DATE')%>"  data-ctrl-type='date_and_time'  class='frm-datepicker ctrl-append-prepend frm-size-medium'  aria-date-format='<%$this->general->getAdminJSFormats('date_and_time', 'dateFormat')%>'  aria-time-format='<%$this->general->getAdminJSFormats('date_and_time', 'timeFormat')%>'  aria-format-type='datetime'  />
                                                                            <span class='add-on text-addon date-time-append-class icomoon-icon-calendar'></span>
                                                                        <%/if%>
                                                                    </div>
                                                                    <div class="error-msg-form "><label class='error' id='mc_registered_dateErr'></label></div>
                                                                </div>
                                                            <%else%>
                                                                <input type="hidden" name="mc_registered_date" id="mc_registered_date" value="<%$this->general->dateTimeSystemFormat($data['mc_registered_date'])%>"  class='ignore-valid'  aria-date-format='<%$this->general->getAdminJSFormats('date_and_time', 'dateFormat')%>'  aria-time-format='<%$this->general->getAdminJSFormats('date_and_time', 'timeFormat')%>'  aria-format-type='datetime'  />
                                                            <%/if%>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clear"></div>
                                                <div class="frm-bot-btn <%$rl_theme_arr['frm_stand_action_bar']%> <%$rl_theme_arr['frm_stand_action_btn']%> popup-footer">
                                                    <%if $rl_theme_arr['frm_stand_ctrls_view'] eq 'No'%>
                                                        <%assign var='rm_ctrl_directions' value=true%>
                                                    <%/if%>
                                                    <%include file="customers_add_buttons.tpl"%>
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
<%$this->js->add_js('admin/customers_add_js.js')%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
<%javascript%>
    Project.modules.customers.callEvents();
<%/javascript%>