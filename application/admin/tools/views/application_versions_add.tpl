<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-form-container">
    <%include file="application_versions_add_strip.tpl"%>
    <div class="<%$module_name%>" data-form-name="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div <%if $parMod neq ''%>top-frm-tab-spacing<%else%>top-frm-spacing<%/if%>" >
            <input type="hidden" id="projmod" name="projmod" value="application_versions" />
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Module Tabs & Top Detail View -->
            <div class="top-frm-tab-layout" id="top_frm_tab_layout">
                <!-- Relational Module Tabs -->
                <div id="ad_form_outertab" class="module-navigation-tabs">
                    <%if $tabing_allow eq true%>
                        <%if $parMod eq "mobile_applications" && $parID neq ""%>
                            <%assign var="extend_tab_view" value=$smarty.const.APPPATH|@cat:"admin/tools/views/cit/mobile_applications_tabs.tpl"%>
                            <%if $extend_tab_view|@is_file%>
                                <%include file="../../tools/views/cit/mobile_applications_tabs.tpl"%>
                            <%else%>
                                <%include file="../../tools/views/mobile_applications_tabs.tpl"%>
                            <%/if%>
                        <%/if%>
                    <%/if%>
                </div>
            </div>
            <!-- Middle Content -->
            <div id="scrollable_content" class="scrollable-content top-block-spacing top-frm-block-spacing">
                <div id="application_versions" class="frm-module-block frm-elem-block frm-stand-view">
                    <!-- Module Form Block -->
                    <form name="frmaddupdate" id="frmaddupdate" action="<%$admin_url%><%$mod_enc_url['add_action']%>?<%$extra_qstr%>" method="post"  enctype="multipart/form-data">
                        <!-- Form Hidden Fields Unit -->
                        <input type="hidden" id="id" name="id" value="<%$enc_id%>" />
                        <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>" />
                        <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>" />
                        <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>" />
                        <input type="hidden" id="draft_uniq_id" name="draft_uniq_id" value="<%$draft_uniq_id%>" />
                        <input type="hidden" id="extra_hstr" name="extra_hstr" value="<%$extra_hstr%>" />
                        <input type="hidden" name="mav_date_added" id="mav_date_added" value="<%$this->general->dateTimeSystemFormat($data['mav_date_added'])%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date_and_time', 'dateFormat')%>'  aria-time-format='<%$this->general->getAdminJSFormats('date_and_time', 'timeFormat')%>'  aria-format-type='datetime' />
                        <input type="hidden" name="mav_added_by" id="mav_added_by" value="<%$data['mav_added_by']%>"  class='ignore-valid ' />
                        <input type="hidden" name="mav_date_updated" id="mav_date_updated" value="<%$this->general->dateTimeSystemFormat($data['mav_date_updated'])%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date_and_time', 'dateFormat')%>'  aria-time-format='<%$this->general->getAdminJSFormats('date_and_time', 'timeFormat')%>'  aria-format-type='datetime' />
                        <input type="hidden" name="mav_updated_by" id="mav_updated_by" value="<%$data['mav_updated_by']%>"  class='ignore-valid ' />
                        <!-- Form Dispaly Fields Unit -->
                        <div class="main-content-block" id="main_content_block">
                            <div style="width:98%" class="frm-block-layout pad-calc-container">
                                <div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
                                    <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('APPLICATION_VERSIONS_APPLICATION_VERSIONS')%></h4></div>
                                    <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                        <%if $parMod eq "mobile_applications"%>
                                            <%if $mode eq "Add"%>
                                                <input type="hidden" value="<%$parID%>" name="mav_application_master_id" id="mav_application_master_id"  class="ignore-valid"/>
                                            <%else%>
                                                <input type="hidden" value="<%$data['mav_application_master_id']%>" name="mav_application_master_id" id="mav_application_master_id"  class="ignore-valid"/>
                                            <%/if%>
                                        <%else%>
                                            <div class="form-row row-fluid " id="cc_sh_mav_application_master_id">
                                                <label class="form-label span3 ">
                                                    <%$form_config['mav_application_master_id']['label_lang']%>
                                                </label> 
                                                <div class="form-right-div  ">
                                                    <%assign var="opt_selected" value=$data['mav_application_master_id']%>
                                                    <%$this->dropdown->display("mav_application_master_id","mav_application_master_id","  title='<%$this->lang->line('APPLICATION_VERSIONS_APPLICATION_MASTER')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'APPLICATION_VERSIONS_APPLICATION_MASTER')%>'  ", "|||", "", $opt_selected,"mav_application_master_id")%>
                                                </div>
                                                <div class="error-msg-form "><label class='error' id='mav_application_master_idErr'></label></div>
                                            </div>
                                        <%/if%>
                                        <div class="form-row row-fluid " id="cc_sh_mav_version_name">
                                            <label class="form-label span3 ">
                                                <%$form_config['mav_version_name']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mav_version_name']|@htmlentities%>" name="mav_version_name" id="mav_version_name" title="<%$this->lang->line('APPLICATION_VERSIONS_VERSION_NAME')%>"  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mav_version_nameErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mav_version_number">
                                            <label class="form-label span3 ">
                                                <%$form_config['mav_version_number']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mav_version_number']|@htmlentities%>" name="mav_version_number" id="mav_version_number" title="<%$this->lang->line('APPLICATION_VERSIONS_VERSION_NUMBER')%>"  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mav_version_numberErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mav_version_type">
                                            <label class="form-label span3 ">
                                                <%$form_config['mav_version_type']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['mav_version_type']%>
                                                <%$this->dropdown->display("mav_version_type","mav_version_type","  title='<%$this->lang->line('APPLICATION_VERSIONS_VERSION_TYPE')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'APPLICATION_VERSIONS_VERSION_TYPE')%>'  ", "", "", $opt_selected,"mav_version_type")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mav_version_typeErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mav_application_url">
                                            <label class="form-label span3 ">
                                                <%$form_config['mav_application_url']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mav_application_url']|@htmlentities%>" name="mav_application_url" id="mav_application_url" title="<%$this->lang->line('APPLICATION_VERSIONS_APPLICATION_URL')%>"  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mav_application_urlErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_sys_application_file">
                                            <label class="form-label span3 ">
                                                <%$form_config['sys_application_file']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <div  class='btn-uploadify frm-size-medium' >
                                                    <input type="hidden" value="<%$data['sys_application_file']%>" name="old_sys_application_file" id="old_sys_application_file" />
                                                    <input type="hidden" value="<%$data['sys_application_file']%>" name="sys_application_file" id="sys_application_file"  aria-extensions="apk,ipa,zip" aria-valid-size="<%$this->lang->line('GENERIC_LESS_THAN')%> (<) 500 MB"/>
                                                    <input type="hidden" value="<%$data['sys_application_file']%>" name="temp_sys_application_file" id="temp_sys_application_file"  />
                                                    <div id="upload_drop_zone_sys_application_file" class="upload-drop-zone"></div>
                                                    <div class="uploader upload-src-zone">
                                                        <input type="file" name="uploadify_sys_application_file" id="uploadify_sys_application_file" title="<%$this->lang->line('APPLICATION_VERSIONS_APPLICATION_FILE')%>" />
                                                        <span class="filename" id="preview_sys_application_file">
                                                            <%if $data['sys_application_file'] neq ''%>
                                                                <%$data['sys_application_file']%>
                                                            <%else%>
                                                                <%$this->lang->line('GENERIC_DROP_FILES_HERE_OR_CLICK_TO_UPLOAD')%>
                                                            <%/if%>
                                                        </span>
                                                        <span class="action">Choose File</span>
                                                    </div>
                                                </div>
                                                <div class='upload-image-btn'>
                                                    <%$img_html['sys_application_file']%>
                                                </div>
                                                <span class="input-comment">
                                                    <a href="javascript://" style="text-decoration: none;" class="tipR" title="<%$this->lang->line('GENERIC_VALID_EXTENSIONS')%> : apk, ipa, zip.<br><%$this->lang->line('GENERIC_VALID_SIZE')%> : <%$this->lang->line('GENERIC_LESS_THAN')%> (<) 500 MB."><span class="icomoon-icon-help"></span></a>
                                                </span>
                                                <div class='clear upload-progress' id='progress_sys_application_file'>
                                                    <div class='upload-progress-bar progress progress-striped active'>
                                                        <div class='bar' id='practive_sys_application_file'></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='sys_application_fileErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mav_date_published">
                                            <label class="form-label span3 ">
                                                <%$form_config['mav_date_published']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  input-append text-append-prepend  ">
                                                <input type="text" value="<%$this->general->dateTimeSystemFormat($data['mav_date_published'])%>" name="mav_date_published" placeholder=""  id="mav_date_published" title="<%$this->lang->line('APPLICATION_VERSIONS_PUBLISHED_ON')%>"  class='frm-datepicker ctrl-append-prepend frm-size-medium'  aria-date-format='<%$this->general->getAdminJSFormats('date_and_time', 'dateFormat')%>'  aria-time-format='<%$this->general->getAdminJSFormats('date_and_time', 'timeFormat')%>'  aria-format-type='datetime'  />
                                                <span class='add-on text-addon date-time-append-class icomoon-icon-calendar'></span>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mav_date_publishedErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mav_release_notes_id">
                                            <label class="form-label span3 ">
                                                <%$form_config['mav_release_notes_id']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['mav_release_notes_id']%>
                                                <%$this->dropdown->display("mav_release_notes_id","mav_release_notes_id","  title='<%$this->lang->line('APPLICATION_VERSIONS_RELEASE_NOTES')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'APPLICATION_VERSIONS_RELEASE_NOTES')%>'  ", "|||", "", $opt_selected,"mav_release_notes_id")%>
                                                <a class="tipR fancybox-hash-iframe add-new-record-popup" style="text-decoration: none;" target="_blank" href="<%$admin_url%>#<%$this->general->getAdminEncodeURL('tools/release_notes/add')%>|mode|<%$mod_enc_mode['Add']%>|hideCtrl|true|rfMod|<%$this->general->getAdminEncodeURL('application_versions')%>|rfFod|<%$this->general->getAdminEncodeURL('tools')%>|rfField|mav_release_notes_id|rfhtmlID|mav_release_notes_id" title="<%$this->lang->line('GENERIC_ADD_NEW_RECORD')%>">
                                                    <span class="icon16 minia-icon-file-add"></span>
                                                </a>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mav_release_notes_idErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mav_status">
                                            <label class="form-label span3 ">
                                                <%$form_config['mav_status']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['mav_status']%>
                                                <%$this->dropdown->display("mav_status","mav_status","  title='<%$this->lang->line('APPLICATION_VERSIONS_STATUS')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'APPLICATION_VERSIONS_STATUS')%>'  ", "|||", "", $opt_selected,"mav_status")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mav_statusErr'></label></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="frm-bot-btn <%$rl_theme_arr['frm_stand_action_bar']%> <%$rl_theme_arr['frm_stand_action_btn']%>">
                                <%if $rl_theme_arr['frm_stand_ctrls_view'] eq 'No'%>
                                    <%assign var='rm_ctrl_directions' value=true%>
                                <%/if%>
                                <%include file="application_versions_add_buttons.tpl"%>
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
<%$this->js->add_js('admin/application_versions_add_js.js')%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
<%javascript%>
    Project.modules.application_versions.callEvents();
<%/javascript%>