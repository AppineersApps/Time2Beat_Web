<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-view-container">
    <%include file="release_notes_add_strip.tpl"%>
    <div class="<%$module_name%>" data-form-name="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
            <input type="hidden" id="projmod" name="projmod" value="release_notes" />
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Module Tabs & Top Detail View -->
            <div class="top-frm-tab-layout" id="top_frm_tab_layout">
            </div>
            <!-- Middle Content -->
            <div id="scrollable_content" class="scrollable-content top-block-spacing ">
                <!-- Module View Block -->
                <div id="release_notes" class="frm-module-block frm-view-block frm-stand-view">
                    <!-- Form Hidden Fields Unit -->
                    <input type="hidden" id="id" name="id" value="<%$enc_id%>" />
                    <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>" />
                    <input type="hidden" id="ctrl_flow" name="ctrl_flow" value="<%$ctrl_flow%>" />
                    <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>" />
                    <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>" />
                    <input type="hidden" name="mrn_date_added" id="mrn_date_added" value="<%$this->general->dateSystemFormat($data['mrn_date_added'])%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
                    <input type="hidden" name="mrn_added_by" id="mrn_added_by" value="<%$data['mrn_added_by']%>"  class='ignore-valid ' />
                    <input type="hidden" name="mrn_date_updated" id="mrn_date_updated" value="<%$this->general->dateSystemFormat($data['mrn_date_updated'])%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
                    <input type="hidden" name="mrn_updated_by" id="mrn_updated_by" value="<%$data['mrn_updated_by']%>"  class='ignore-valid ' />
                    <!-- Form Display Fields Unit -->
                    <div class="main-content-block" id="main_content_block">
                        <div style="width:98%;" class="frm-block-layout pad-calc-container">
                            <div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
                                <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('RELEASE_NOTES_RELEASE_NOTES')%></h4></div>
                                <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                    <div class="form-row row-fluid " id="cc_sh_mrn_version_number">
                                        <label class="form-label span3">
                                            <%$form_config['mrn_version_number']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div ">
                                            <strong><%$data['mrn_version_number']%></strong>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_mrn_release_date">
                                        <label class="form-label span3">
                                            <%$form_config['mrn_release_date']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  input-append text-append-prepend ">
                                            <strong><%$this->general->dateSystemFormat($data['mrn_release_date'])%></strong>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_mrn_release_status">
                                        <label class="form-label span3">
                                            <%$form_config['mrn_release_status']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div ">
                                            <strong><%$this->general->displayKeyValueData($data['mrn_release_status'], $opt_arr['mrn_release_status'])%></strong>
                                        </div>
                                    </div>
                                    <%assign var="child_module_name" value="release_notes_details"%>
                                    <div class="form-row row-fluid" id="child_module_<%$child_module_name%>">
                                        <div class="form-right-div form-child-row" id="child_module_rel_<%$child_module_name%>">
                                            <%assign var="child_data" value=$child_assoc_data[$child_module_name]%>
                                            <%assign var="child_func" value=$child_assoc_func[$child_module_name]%>
                                            <%assign var="child_elem" value=$child_assoc_elem[$child_module_name]%>
                                            <%assign var="child_conf_arr" value=$child_assoc_conf[$child_module_name]%>
                                            <%assign var="child_opt_arr" value=$child_assoc_opt[$child_module_name]%>
                                            <%assign var="child_img_html" value=$child_assoc_img[$child_module_name]%>
                                            <%assign var="child_auto_arr" value=$child_assoc_auto[$child_module_name]%>
                                            <%assign var="child_access_arr" value=$child_assoc_access[$child_module_name]%>
                                            <%if $child_conf_arr["recMode"] eq "Update"%>
                                                <%assign var="child_cnt" value=$child_data|@count%>
                                                <%assign var="recMode" value="Update"%>
                                            <%else%>
                                                <%if $child_data|@count gt 0%>
                                                    <%assign var="child_cnt" value=$child_data|@count%>
                                                <%else%>
                                                    <%assign var="child_cnt" value="1"%>
                                                <%/if%>
                                                <%assign var="recMode" value="Add"%>
                                            <%/if%>
                                            <%assign var="popup" value=$child_conf_arr["popup"]%>
                                            <div class="title">
                                                <input type="hidden" name="childModule[]" id="childModule_<%$child_module_name%>" value="<%$child_module_name%>" />
                                                <input type="hidden" name="childModuleShowHide[<%$child_module_name%>]" id="childModuleShowHide_<%$child_module_name%>" value="No" />
                                                <h4>
                                                    <span class="icon12 icomoon-icon-equalizer-2"></span><span><%$this->lang->line('RELEASE_NOTES_RELEASE_NOTES_DETAILS')%></span>
                                                </h4>
                                                <a href="javascript://" class="minimize" style="display: none;"></a>
                                            </div>
                                            <div class="content" style="display: block;" id="tbl_child_module_<%$child_module_name%>">
                                                <div id="add_child_module_<%$child_module_name%>">
                                                    <%section name=i loop=$child_cnt%>
                                                        <%assign var="row_index" value=$smarty.section.i.index%>
                                                        <%assign var="row_number" value=$smarty.section.i.iteration%>
                                                        <%assign var="child_id" value=$child_data[i]['iReleaseNoteDetailsId']%>
                                                        <%assign var="enc_child_id" value=$this->general->getAdminEncodeURL($child_id)%>
                                                        <%if $row_index gt 0%>
                                                            <hr class="hr-line">
                                                        <%/if%>
                                                        <div id="div_child_row_<%$child_module_name%>_<%$row_index%>">
                                                            <input type="hidden" name="child[release_notes_details][id][<%$row_index%>]" id="child_release_notes_details_id_<%$row_index%>" value="<%$child_id%>" />
                                                            <input type="hidden" name="child[release_notes_details][enc_id][<%$row_index%>]" id="child_release_notes_details_enc_id_<%$row_index%>" value="<%$enc_child_id%>" />
                                                            <input type="hidden" name="child[release_notes_details][mrnd_release_notes_id][<%$row_index%>]" id="child_release_notes_details_mrnd_release_notes_id_<%$row_index%>" value="<%$child_data[i]['mrnd_release_notes_id']%>"  class='ignore-valid ' />
                                                            <input type="hidden" name="child[release_notes_details][mrnd_date_added][<%$row_index%>]" id="child_release_notes_details_mrnd_date_added_<%$row_index%>" value="<%$child_data[i]['mrnd_date_added']%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
                                                            <input type="hidden" name="child[release_notes_details][mrnd_added_by][<%$row_index%>]" id="child_release_notes_details_mrnd_added_by_<%$row_index%>" value="<%$child_data[i]['mrnd_added_by']%>"  class='ignore-valid ' />
                                                            <input type="hidden" name="child[release_notes_details][mrnd_date_updated][<%$row_index%>]" id="child_release_notes_details_mrnd_date_updated_<%$row_index%>" value="<%$child_data[i]['mrnd_date_updated']%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
                                                            <input type="hidden" name="child[release_notes_details][mrnd_updated_by][<%$row_index%>]" id="child_release_notes_details_mrnd_updated_by_<%$row_index%>" value="<%$child_data[i]['mrnd_updated_by']%>"  class='ignore-valid ' />
                                                            <div class="column-view-parent form-row row-fluid tab-focus-element">
                                                                <div class="two-block-view" id="ch_release_notes_details_cc_sh_mrnd_title">
                                                                    <label class="form-label span3">
                                                                        <%$child_conf_arr['form_config']['mrnd_title']['label_lang']%>
                                                                    </label> 
                                                                    <div class="form-right-div  ">
                                                                        <strong><%$child_data[i]['mrnd_title']%></strong>
                                                                    </div>
                                                                </div>
                                                                <div class="two-block-view" id="ch_release_notes_details_cc_sh_mrnd_version_status">
                                                                    <label class="form-label span3">
                                                                        <%$child_conf_arr['form_config']['mrnd_version_status']['label_lang']%>
                                                                    </label> 
                                                                    <div class="form-right-div  ">
                                                                        <strong><%assign var="opt_selected" value=$child_data[i]['mrnd_version_status']%>
                                                                            <%assign var="combo_arr" value=$child_opt_arr["child_release_notes_details_mrnd_version_status_$row_index"]%>
                                                                        <%$this->general->displayKeyValueData($opt_selected, $combo_arr)%></strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="column-view-parent form-row row-fluid tab-focus-element">
                                                                <div class="two-block-view" id="ch_release_notes_details_cc_sh_mrnd_description">
                                                                    <label class="form-label span3">
                                                                        <%$child_conf_arr['form_config']['mrnd_description']['label_lang']%>
                                                                    </label> 
                                                                    <div class="form-right-div frm-editor-layout  ">
                                                                        <strong><%$child_data[i]['mrnd_description']%></strong>
                                                                    </div>
                                                                </div>
                                                                <div class="two-block-view" id="">
                                                                    <label class="form-label span3">
                                                                    </label> 
                                                                    <div class="form-right-div  ">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <%/section%>
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
