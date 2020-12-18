<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-form-container">
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
                <div id="release_notes" class="frm-module-block frm-elem-block frm-stand-view">
                    <!-- Module Form Block -->
                    <form name="frmaddupdate" id="frmaddupdate" action="<%$admin_url%><%$mod_enc_url['add_action']%>?<%$extra_qstr%>" method="post"  enctype="multipart/form-data">
                        <!-- Form Hidden Fields Unit -->
                        <input type="hidden" id="id" name="id" value="<%$enc_id%>" />
                        <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>" />
                        <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>" />
                        <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>" />
                        <input type="hidden" id="draft_uniq_id" name="draft_uniq_id" value="<%$draft_uniq_id%>" />
                        <input type="hidden" id="extra_hstr" name="extra_hstr" value="<%$extra_hstr%>" />
                        <input type="hidden" name="mrn_date_added" id="mrn_date_added" value="<%$this->general->dateSystemFormat($data['mrn_date_added'])%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
                        <input type="hidden" name="mrn_added_by" id="mrn_added_by" value="<%$data['mrn_added_by']%>"  class='ignore-valid ' />
                        <input type="hidden" name="mrn_date_updated" id="mrn_date_updated" value="<%$this->general->dateSystemFormat($data['mrn_date_updated'])%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
                        <input type="hidden" name="mrn_updated_by" id="mrn_updated_by" value="<%$data['mrn_updated_by']%>"  class='ignore-valid ' />
                        <!-- Form Dispaly Fields Unit -->
                        <div class="main-content-block" id="main_content_block">
                            <div style="width:98%" class="frm-block-layout pad-calc-container">
                                <div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
                                    <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('RELEASE_NOTES_RELEASE_NOTES')%></h4></div>
                                    <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                        <div class="form-row row-fluid " id="cc_sh_mrn_version_number">
                                            <label class="form-label span3 ">
                                                <%$form_config['mrn_version_number']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mrn_version_number']|@htmlentities%>" name="mrn_version_number" id="mrn_version_number" title="<%$this->lang->line('RELEASE_NOTES_VERSION_NUMBER')%>"  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mrn_version_numberErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mrn_release_date">
                                            <label class="form-label span3 ">
                                                <%$form_config['mrn_release_date']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  input-append text-append-prepend  ">
                                                <input type="text" value="<%$this->general->dateSystemFormat($data['mrn_release_date'])%>" placeholder="" name="mrn_release_date" id="mrn_release_date" title="<%$this->lang->line('RELEASE_NOTES_RELEASE_DATE')%>"  class='frm-datepicker ctrl-append-prepend frm-size-medium'  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date'  />
                                                <span class='add-on text-addon date-append-class icomoon-icon-calendar'></span>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mrn_release_dateErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mrn_release_status">
                                            <label class="form-label span3 ">
                                                <%$form_config['mrn_release_status']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['mrn_release_status']%>
                                                <%$this->dropdown->display("mrn_release_status","mrn_release_status","  title='<%$this->lang->line('RELEASE_NOTES_RELEASE_STATUS')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'RELEASE_NOTES_RELEASE_STATUS')%>'  ", "|||", "", $opt_selected,"mrn_release_status")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mrn_release_statusErr'></label></div>
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
                                                    <input type="hidden" name="childModuleParField[<%$child_module_name%>]" id="childModuleParField_<%$child_module_name%>" value="iReleaseNotesId"/>
                                                    <input type="hidden" name="childModuleParData[<%$child_module_name%>]" id="childModuleParData_<%$child_module_name%>" value="<%$this->general->getAdminEncodeURL($data['iReleaseNotesId'])%>"/>
                                                    <input type="hidden" name="childModuleType[<%$child_module_name%>]" id="childModuleType_<%$child_module_name%>" value="Table"/>
                                                    <input type="hidden" name="childModuleLayout[<%$child_module_name%>]" id="childModuleLayout_<%$child_module_name%>" value="Row"/>
                                                    <input type="hidden" name="childModuleCnt[<%$child_module_name%>]" id="childModuleCnt_<%$child_module_name%>" value="<%$child_cnt%>" />
                                                    <input type="hidden" name="childModuleInc[<%$child_module_name%>]" id="childModuleInc_<%$child_module_name%>" value="<%$child_cnt%>" />
                                                    <input type="hidden" name="childModulePopup[<%$child_module_name%>]" id="childModulePopup_<%$child_module_name%>" value="<%$popup%>" />
                                                    <input type="hidden" name="childModuleUploadURL[<%$child_module_name%>]" id="childModuleUploadURL_<%$child_module_name%>" value="<%$child_conf_arr['mod_enc_url']['upload_form_file']%>" />
                                                    <input type="hidden" name="childModuleChosenURL[<%$child_module_name%>]" id="childModuleChosenURL_<%$child_module_name%>" value="<%$child_conf_arr['mod_enc_url']['get_chosen_auto_complete']%>" />
                                                    <input type="hidden" name="childModuleParentURL[<%$child_module_name%>]" id="childModuleParentURL_<%$child_module_name%>" value="<%$child_conf_arr['mod_enc_url']['parent_source_options']%>" />
                                                    <input type="hidden" name="childModuleTokenURL[<%$child_module_name%>]" id="childModuleTokenURL_<%$child_module_name%>" value="<%$child_conf_arr['mod_enc_url']['get_token_auto_complete']%>" />
                                                    <input type="hidden" name="childModuleShowHide[<%$child_module_name%>]" id="childModuleShowHide_<%$child_module_name%>" value="Yes" />
                                                    <h4>
                                                        <span class="icon12 icomoon-icon-equalizer-2"></span><span><%$this->lang->line('RELEASE_NOTES_RELEASE_NOTES_DETAILS')%></span>
                                                        <span style="display:none;margin-left:32%" id="ajax_loader_childModule_<%$child_module_name%>"><i class="fa fa-refresh fa-spin-light fa-2x fa-fw"></i></span>
                                                        <%assign var="_ch_label_add_text" value=$this->lang->line('GENERIC_ADD_NEW')%>
                                                        <%assign var="_ch_label_add_icon" value="icon12 icomoon-icon-plus-2"%>
                                                        <%if isset($child_access_arr["labels"]["add"]["text"])%>
                                                            <%assign var="_ch_label_add_text" value=$child_access_arr["labels"]["add"]["text"]%>
                                                        <%/if%>
                                                        <%if isset($child_access_arr["labels"]["add"]["icon"])%>
                                                            <%assign var="_ch_label_add_icon" value=$child_access_arr["labels"]["add"]["icon"]%>
                                                        <%/if%>
                                                        <div class="box-addmore right">
                                                            <a class="btn btn-success" href="javascript://" onclick="getChildModuleAjaxTable('<%$mod_enc_url.child_data_add%>','<%$child_module_name%>', '<%$mode%>')" title="<%$_ch_label_add_text%>">
                                                                <span class="<%$_ch_label_add_icon%>"></span>
                                                                <strong><%$_ch_label_add_text%></strong>
                                                            </a>
                                                        </div>
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
                                                                <%if $child_access_arr["actions"] eq 1%>
                                                                    <div class="col-del controls">
                                                                        <%if $child_access_arr["save"] eq 1%>
                                                                            <%if $mode eq "Update"%>
                                                                                <a onclick="saveChildModuleSingleData('<%$mod_enc_url.child_data_save%>', '<%$mod_enc_url.child_data_add%>', '<%$child_module_name%>', '<%$recMode%>', '<%$row_index%>', '<%$enc_child_id%>', 'No', '<%$mode%>')" href="javascript://" class="tip action-child-module-save" title="<%$this->lang->line('GENERIC_SAVE')%>">
                                                                                    <span class="icon14 icomoon-icon-disk"></span>
                                                                                </a>
                                                                            <%/if%>
                                                                            <%/if%><%if $child_access_arr["delete"] eq 1%>
                                                                            <%if $recMode eq "Update"%>
                                                                                <a onclick="deleteChildModuleSingleData('<%$mod_enc_url.child_data_delete%>','<%$child_module_name%>', '<%$row_index%>','<%$enc_child_id%>')" href="javascript://" class="tip action-child-module-delete" title="<%$this->lang->line('GENERIC_DELETE')%>" >
                                                                                    <span class="icon16 icomoon-icon-remove"></span>
                                                                                </a>
                                                                            <%else%>
                                                                                <a onclick="deleteChildModuleRow('<%$mod_enc_url.child_data_delete%>','<%$child_module_name%>', '<%$row_index%>')" href="javascript://" class="tip action-child-module-delete" title="<%$this->lang->line('GENERIC_DELETE')%>" >
                                                                                    <span class="icon16 icomoon-icon-remove"></span>
                                                                                </a>
                                                                            <%/if%>
                                                                        <%/if%>
                                                                    </div>
                                                                <%/if%>
                                                                <div class="column-view-parent form-row row-fluid tab-focus-element">
                                                                    <div class="two-block-view" id="ch_release_notes_details_cc_sh_mrnd_title">
                                                                        <label class="form-label span3">
                                                                            <%$child_conf_arr['form_config']['mrnd_title']['label_lang']%>  <em>*</em>
                                                                        </label> 
                                                                        <div class="form-right-div  ">
                                                                            <input type="text" placeholder="" value="<%$child_data[i]['mrnd_title']|@htmlentities%>" name="child[release_notes_details][mrnd_title][<%$row_index%>]" id="child_release_notes_details_mrnd_title_<%$row_index%>" title="<%$child_conf_arr['form_config']['mrnd_title']['label_lang']%>"  class='frm-size-full_width'  />  
                                                                        </div>
                                                                        <div class="error-msg-form " >
                                                                            <label class='error' id='child_release_notes_details_mrnd_title_<%$row_index%>Err'></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="two-block-view" id="ch_release_notes_details_cc_sh_mrnd_version_status">
                                                                        <label class="form-label span3">
                                                                            <%$child_conf_arr['form_config']['mrnd_version_status']['label_lang']%>  <em>*</em>
                                                                        </label> 
                                                                        <div class="form-right-div  ">
                                                                            <%assign var="opt_selected" value=$child_data[i]['mrnd_version_status']%>
                                                                            <%$this->dropdown->display("child_release_notes_details_mrnd_version_status_<%$row_index%>","child[release_notes_details][mrnd_version_status][<%$row_index%>]","  title='<%$child_conf_arr['form_config']['mrnd_version_status']['label_lang']%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'RELEASE_NOTES_DETAILS_TYPE')%>'  ","|||","",$opt_selected,"child_release_notes_details_mrnd_version_status_$row_index")%>  
                                                                        </div>
                                                                        <div class="error-msg-form " >
                                                                            <label class='error' id='child_release_notes_details_mrnd_version_status_<%$row_index%>Err'></label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="column-view-parent form-row row-fluid tab-focus-element">
                                                                    <div class="two-block-view" id="ch_release_notes_details_cc_sh_mrnd_description">
                                                                        <label class="form-label span3">
                                                                            <%$child_conf_arr['form_config']['mrnd_description']['label_lang']%>  
                                                                        </label> 
                                                                        <div class="form-right-div frm-editor-layout  ">
                                                                            <textarea name="child[release_notes_details][mrnd_description][<%$row_index%>]" id="child_release_notes_details_mrnd_description_<%$row_index%>" title="<%$child_conf_arr['form_config']['mrnd_description']['label_lang']%>"  style='width:80%;'  class='frm-size-full_width frm-editor-medium'  ><%$child_data[i]['mrnd_description']%></textarea>  
                                                                        </div>
                                                                        <div class="error-msg-form " >
                                                                            <label class='error' id='child_release_notes_details_mrnd_description_<%$row_index%>Err'></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="two-block-view" id="">
                                                                        <label class="form-label span3">
                                                                        </label> 
                                                                        <div class="form-right-div  ">
                                                                        </div>
                                                                        <div class="error-msg-form " >
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <%javascript%>
                                                            <%if $child_auto_arr[$row_index]|@is_array && $child_auto_arr[$row_index]|@count gt 0%>
                                                                var $child_chosen_auto_complete_url = admin_url+""+$("#childModuleChosenURL_<%$child_module_name%>").val()+"?";
                                                                setTimeout(function(){
                                                                <%foreach name=i from=$child_auto_arr[$row_index] item=v key=k%>
                                                                    if($("#child_<%$child_module_name%>_<%$k%>_<%$row_index%>").is("select")){
                                                                    $("#child_<%$child_module_name%>_<%$k%>_<%$row_index%>").ajaxChosen({
                                                                    dataType: "json",
                                                                    type: "POST",
                                                                    url: $child_chosen_auto_complete_url+"&unique_name=<%$k%>&mode=<%$mod_enc_mode[$recMode]%>&id=<%$enc_child_id%>"
                                                                    },{
                                                                    loadingImg: admin_image_url+"chosen-loading.gif"
                                                                    });
                                                                }
                                                            <%/foreach%>
                                                            }, 500);
                                                        <%/if%>
                                                        <%/javascript%>
                                                    <%/section%>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="frm-bot-btn <%$rl_theme_arr['frm_stand_action_bar']%> <%$rl_theme_arr['frm_stand_action_btn']%>">
                            <%if $rl_theme_arr['frm_stand_ctrls_view'] eq 'No'%>
                                <%assign var='rm_ctrl_directions' value=true%>
                            <%/if%>
                            <%include file="release_notes_add_buttons.tpl"%>
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
    child_rules_arr = {
        "release_notes_details": {
            "mrnd_release_notes_id": {
                "type": "dropdown",
                "vUniqueName": "mrnd_release_notes_id",
                "editrules": {
                    "required": true,
                    "infoArr": {
                        "required": {
                            "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.RELEASE_NOTES_DETAILS_RELEASE_NOTES)
                        }
                    }
                }
            },
            "mrnd_title": {
                "type": "textbox",
                "vUniqueName": "mrnd_title",
                "editrules": {
                    "required": true,
                    "infoArr": {
                        "required": {
                            "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.RELEASE_NOTES_DETAILS_TITLE)
                        }
                    }
                }
            },
            "mrnd_description": {
                "type": "wysiwyg",
                "vUniqueName": "mrnd_description",
                "editrules": {
                    "infoArr": []
                }
            },
            "mrnd_date_added": {
                "type": "date",
                "vUniqueName": "mrnd_date_added",
                "editrules": {
                    "infoArr": []
                }
            },
            "mrnd_added_by": {
                "type": "dropdown",
                "vUniqueName": "mrnd_added_by",
                "editrules": {
                    "infoArr": []
                }
            },
            "mrnd_date_updated": {
                "type": "date",
                "vUniqueName": "mrnd_date_updated",
                "editrules": {
                    "infoArr": []
                }
            },
            "mrnd_updated_by": {
                "type": "dropdown",
                "vUniqueName": "mrnd_updated_by",
                "editrules": {
                    "infoArr": []
                }
            },
            "mrnd_version_status": {
                "type": "dropdown",
                "vUniqueName": "mrnd_version_status",
                "editrules": {
                    "required": true,
                    "infoArr": {
                        "required": {
                            "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.RELEASE_NOTES_DETAILS_TYPE)
                        }
                    }
                }
            }
        }
    };
            
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
        "child_messages" : {
            "delete_message" : {
        "release_notes_details" : "<%$this->general->processMessageLabel('ACTION_ARE_YOU_SURE_WANT_TO_DELETE_THIS_RECORD_C63')%>",
            }
        }
    };
    
    callSwitchToSelf();
<%/javascript%>
<%$this->js->add_js('admin/forms/tinymce/tinymce.min.js','admin/release_notes_add_js.js')%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
<%javascript%>
    Project.modules.release_notes.callEvents();
<%/javascript%>

<style>
    #ch_release_notes_details_cc_sh_mrnd_version_status .form-label{text-align: right;}
    #ch_release_notes_details_cc_sh_mrnd_description{width: 98% !important;}
    #ch_release_notes_details_cc_sh_mrnd_description .form-label{width: 11.5%;}
</style>