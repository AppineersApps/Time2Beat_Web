<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-form-container">
    <%include file="shortcuts_add_strip.tpl"%>
    <div class="<%$module_name%>" data-form-name="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
            <input type="hidden" id="projmod" name="projmod" value="shortcuts" />
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Module Tabs & Top Detail View -->
            <div class="top-frm-tab-layout" id="top_frm_tab_layout">
            </div>
            <!-- Middle Content -->
            <div id="scrollable_content" class="scrollable-content top-block-spacing ">
                <div id="shortcuts" class="frm-module-block frm-elem-block frm-stand-view">
                    <!-- Module Form Block -->
                    <form name="frmaddupdate" id="frmaddupdate" action="<%$admin_url%><%$mod_enc_url['add_action']%>?<%$extra_qstr%>" method="post"  enctype="multipart/form-data">
                        <!-- Form Hidden Fields Unit -->
                        <input type="hidden" id="id" name="id" value="<%$enc_id%>" />
                        <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>" />
                        <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>" />
                        <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>" />
                        <input type="hidden" id="draft_uniq_id" name="draft_uniq_id" value="<%$draft_uniq_id%>" />
                        <input type="hidden" id="extra_hstr" name="extra_hstr" value="<%$extra_hstr%>" />
                        <input type="hidden" name="ms_shortcut_mode" id="ms_shortcut_mode" value="<%$data['ms_shortcut_mode']%>"  class='ignore-valid ' />
                        <textarea style="display:none;" name="ms_navigate_json" id="ms_navigate_json"  class='ignore-valid ' ><%$data['ms_navigate_json']%></textarea>
                        <input type="hidden" name="ms_added_date" id="ms_added_date" value="<%$this->general->dateTimeSystemFormat($data['ms_added_date'])%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date_and_time', 'dateFormat')%>'  aria-time-format='<%$this->general->getAdminJSFormats('date_and_time', 'timeFormat')%>'  aria-format-type='datetime' />
                        <input type="hidden" name="ms_modified_date" id="ms_modified_date" value="<%$this->general->dateTimeSystemFormat($data['ms_modified_date'])%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date_and_time', 'dateFormat')%>'  aria-time-format='<%$this->general->getAdminJSFormats('date_and_time', 'timeFormat')%>'  aria-format-type='datetime' />
                        <!-- Form Dispaly Fields Unit -->
                        <div class="main-content-block" id="main_content_block">
                            <div style="width:98%" class="frm-block-layout pad-calc-container">
                                <div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
                                    <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('SHORTCUTS_SHORTCUTS')%></h4></div>
                                    <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                        <div class="form-row row-fluid " id="cc_sh_ms_shortcut_key">
                                            <label class="form-label span3 ">
                                                <%$form_config['ms_shortcut_key']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['ms_shortcut_key']|@htmlentities%>" name="ms_shortcut_key" id="ms_shortcut_key" title="<%$this->lang->line('SHORTCUTS_SHORTCUT_KEY')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='ms_shortcut_keyErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_ms_shortcut_name">
                                            <label class="form-label span3 ">
                                                <%$form_config['ms_shortcut_name']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['ms_shortcut_name']|@htmlentities%>" name="ms_shortcut_name" id="ms_shortcut_name" title="<%$this->lang->line('SHORTCUTS_SHORTCUT_NAME')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='ms_shortcut_nameErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_ms_shortcut_type">
                                            <label class="form-label span3 ">
                                                <%$form_config['ms_shortcut_type']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div frm-elements-div ">
                                                <%assign var="opt_selected" value=$data['ms_shortcut_type']%>
                                                <%assign var="combo_arr" value=$opt_arr["ms_shortcut_type"]%>
                                                <%if $combo_arr|@is_array && $combo_arr|@count gt 0 %>
                                                    <%foreach name=i from=$combo_arr item=v key=k%>
                                                        <input type="radio" value="<%$k%>" name="ms_shortcut_type" id="ms_shortcut_type_<%$k%>" title="<%$v%>" <%if $opt_selected eq  $k %> checked=true <%/if%>  class='regular-radio'  />
                                                        <label for="ms_shortcut_type_<%$k%>" class="frm-horizon-row frm-column-layout">&nbsp;</label>
                                                        <label for="ms_shortcut_type_<%$k%>" class="frm-horizon-row frm-column-layout"><%$v%></label>&nbsp;&nbsp;
                                                    <%/foreach%>
                                                <%/if%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='ms_shortcut_typeErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_sys_custom_menu">
                                            <label class="form-label span3 ">
                                                <%$form_config['sys_custom_menu']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['sys_custom_menu']%>
                                                <%$this->dropdown->display("sys_custom_menu","sys_custom_menu","  title='<%$this->lang->line('SHORTCUTS_MENU')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'SHORTCUTS_MENU')%>'  ", "|||", "", $opt_selected,"sys_custom_menu")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='sys_custom_menuErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_sys_custom_module">
                                            <label class="form-label span3 ">
                                                <%$form_config['sys_custom_module']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['sys_custom_module']%>
                                                <%$this->dropdown->display("sys_custom_module","sys_custom_module","  title='<%$this->lang->line('SHORTCUTS_MODULE')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'SHORTCUTS_MODULE')%>'  ", "|||", "", $opt_selected,"sys_custom_module")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='sys_custom_moduleErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_sys_custom_code">
                                            <label class="form-label span3 ">
                                                <%$form_config['sys_custom_code']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['sys_custom_code']|@htmlentities%>" name="sys_custom_code" id="sys_custom_code" title="<%$this->lang->line('SHORTCUTS_CUSTOM_CODE')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='sys_custom_codeErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_sys_custom_general">
                                            <label class="form-label span3 ">
                                                <%$form_config['sys_custom_general']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['sys_custom_general']%>
                                                <%$this->dropdown->display("sys_custom_general","sys_custom_general","  title='<%$this->lang->line('SHORTCUTS_GENERAL')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'SHORTCUTS_GENERAL')%>'  ", "|||", "", $opt_selected,"sys_custom_general")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='sys_custom_generalErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_ms_status">
                                            <label class="form-label span3 ">
                                                <%$form_config['ms_status']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['ms_status']%>
                                                <%$this->dropdown->display("ms_status","ms_status","  title='<%$this->lang->line('SHORTCUTS_STATUS')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'SHORTCUTS_STATUS')%>'  ", "|||", "", $opt_selected,"ms_status")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='ms_statusErr'></label></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="frm-bot-btn <%$rl_theme_arr['frm_stand_action_bar']%> <%$rl_theme_arr['frm_stand_action_btn']%>">
                                <%if $rl_theme_arr['frm_stand_ctrls_view'] eq 'No'%>
                                    <%assign var='rm_ctrl_directions' value=true%>
                                <%/if%>
                                <%include file="shortcuts_add_buttons.tpl"%>
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
<%$this->js->add_js('admin/shortcuts_add_js.js')%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
<%javascript%>
    Project.modules.shortcuts.callEvents();
<%/javascript%>