<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-form-container">
    <%include file="feedback_management_add_strip.tpl"%>
    <div class="<%$module_name%>" data-form-name="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
            <input type="hidden" id="projmod" name="projmod" value="feedback_management" />
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Module Tabs & Top Detail View -->
            <div class="top-frm-tab-layout" id="top_frm_tab_layout">
            </div>
            <!-- Middle Content -->
            <div id="scrollable_content" class="scrollable-content popup-content top-block-spacing ">
                <div id="feedback_management" class="frm-module-block frm-elem-block frm-stand-view">
                    <!-- Module Form Block -->
                    <form name="frmaddupdate" id="frmaddupdate" action="<%$admin_url%><%$mod_enc_url['add_action']%>?<%$extra_qstr%>" method="post"  enctype="multipart/form-data">
                        <!-- Form Hidden Fields Unit -->
                        <input type="hidden" id="id" name="id" value="<%$enc_id%>" />
                        <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>" />
                        <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>" />
                        <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>" />
                        <input type="hidden" id="draft_uniq_id" name="draft_uniq_id" value="<%$draft_uniq_id%>" />
                        <input type="hidden" id="extra_hstr" name="extra_hstr" value="<%$extra_hstr%>" />
                        <input type="hidden" name="uq_updated_at" id="uq_updated_at" value="<%$this->general->dateSystemFormat($data['uq_updated_at'])%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
                        <!-- Form Dispaly Fields Unit -->
                        <div class="main-content-block " id="main_content_block">
                            <div style="width:98%" class="frm-block-layout pad-calc-container">
                                <div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
                                    <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('FEEDBACK_MANAGEMENT_FEEDBACK_MANAGEMENT')%></h4></div>
                                    <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                        <div class="form-row row-fluid " id="cc_sh_uq_user_id">
                                            <label class="form-label span3 ">
                                                <%$form_config['uq_user_id']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                                                <%assign var="opt_selected" value=$data['uq_user_id']%>
                                                <%if $mode eq "Update"%>
                                                    <input type="hidden" name="uq_user_id" id="uq_user_id" value="<%$data['uq_user_id']%>" class="ignore-valid"/>
                                                    <%assign var="combo_arr" value=$opt_arr["uq_user_id"]%>
                                                    <%assign var="opt_display" value=$this->general->displayKeyValueData($opt_selected, $combo_arr)%>
                                                    <span class="frm-data-label">
                                                        <strong>
                                                            <%if $opt_display neq ""%>
                                                                <%$opt_display%>
                                                            <%else%>
                                                            <%/if%>
                                                        </strong></span>
                                                    <%else%>
                                                        <%$this->dropdown->display("uq_user_id","uq_user_id","  title='<%$this->lang->line('FEEDBACK_MANAGEMENT_FULL_NAME')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'FEEDBACK_MANAGEMENT_FULL_NAME')%>'  ", "|||", "", $opt_selected,"uq_user_id")%>
                                                    <%/if%>
                                                </div>
                                                <div class="error-msg-form "><label class='error' id='uq_user_idErr'></label></div>
                                            </div>
                                            <div class="form-row row-fluid " id="cc_sh_uq_feedback">
                                                <label class="form-label span3 ">
                                                    <%$form_config['uq_feedback']['label_lang']%>
                                                </label> 
                                                <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                                                    <%if $mode eq "Update"%>
                                                        <textarea style="display:none;" class="ignore-valid" name="uq_feedback" id="uq_feedback"><%$data['uq_feedback']%></textarea>
                                                        <span class="frm-data-label">
                                                            <strong>
                                                                <%if $data['uq_feedback'] neq ""%>
                                                                    <%$data['uq_feedback']%>
                                                                <%else%>
                                                                <%/if%>
                                                            </strong></span>
                                                        <%else%>
                                                            <textarea placeholder=""  name="uq_feedback" id="uq_feedback" title="<%$this->lang->line('FEEDBACK_MANAGEMENT_FEEDBACK')%>"  data-ctrl-type='textarea'  class='elastic frm-size-medium'  ><%$data['uq_feedback']%></textarea>
                                                        <%/if%>
                                                    </div>
                                                    <div class="error-msg-form "><label class='error' id='uq_feedbackErr'></label></div>
                                                </div>
                                                <div class="form-row row-fluid " id="cc_sh_uq_added_at">
                                                    <label class="form-label span3 ">
                                                        <%$form_config['uq_added_at']['label_lang']%>
                                                    </label> 
                                                    <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%else%>input-append text-append-prepend<%/if%> ">
                                                        <%if $mode eq "Update"%>
                                                            <input type="hidden" name="uq_added_at" id="uq_added_at" value="<%$this->general->dateSystemFormat($data['uq_added_at'])%>" class="ignore-valid view-label-only"  data-ctrl-type='date'  class='frm-datepicker ctrl-append-prepend frm-size-medium'  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
                                                            <%assign var="display_date" value=$this->general->dateSystemFormat($data['uq_added_at'])%>
                                                            <span class="frm-data-label">
                                                                <strong>
                                                                    <%if $display_date neq ""%>
                                                                        <%$display_date%>
                                                                    <%else%>
                                                                    <%/if%>
                                                                </strong></span>
                                                            <%else%>
                                                                <input type="text" value="<%$this->general->dateSystemFormat($data['uq_added_at'])%>" placeholder="" name="uq_added_at" id="uq_added_at" title="<%$this->lang->line('FEEDBACK_MANAGEMENT_REPORTED_ON')%>"  data-ctrl-type='date'  class='frm-datepicker ctrl-append-prepend frm-size-medium'  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date'  />
                                                                <span class='add-on text-addon date-append-class icomoon-icon-calendar'></span>
                                                            <%/if%>
                                                        </div>
                                                        <div class="error-msg-form "><label class='error' id='uq_added_atErr'></label></div>
                                                    </div>
                                                    <div class="form-row row-fluid " id="cc_sh_uq_device_type">
                                                        <label class="form-label span3 ">
                                                            <%$form_config['uq_device_type']['label_lang']%>
                                                        </label> 
                                                        <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                                                            <%if $mode eq "Update"%>
                                                                <input type="hidden" class="ignore-valid" name="uq_device_type" id="uq_device_type" value="<%$data['uq_device_type']|@htmlentities%>" />
                                                                <span class="frm-data-label">
                                                                    <strong>
                                                                        <%if $data['uq_device_type'] neq ""%>
                                                                            <%$data['uq_device_type']%>
                                                                        <%else%>
                                                                        <%/if%>
                                                                    </strong></span>
                                                                <%else%>
                                                                    <input type="text" placeholder="" value="<%$data['uq_device_type']|@htmlentities%>" name="uq_device_type" id="uq_device_type" title="<%$this->lang->line('FEEDBACK_MANAGEMENT_DEVICE_TYPE')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                                                <%/if%>
                                                            </div>
                                                            <div class="error-msg-form "><label class='error' id='uq_device_typeErr'></label></div>
                                                        </div>
                                                        <div class="form-row row-fluid " id="cc_sh_uq_device_model">
                                                            <label class="form-label span3 ">
                                                                <%$form_config['uq_device_model']['label_lang']%>
                                                            </label> 
                                                            <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                                                                <%if $mode eq "Update"%>
                                                                    <input type="hidden" class="ignore-valid" name="uq_device_model" id="uq_device_model" value="<%$data['uq_device_model']|@htmlentities%>" />
                                                                    <span class="frm-data-label">
                                                                        <strong>
                                                                            <%if $data['uq_device_model'] neq ""%>
                                                                                <%$data['uq_device_model']%>
                                                                            <%else%>
                                                                            <%/if%>
                                                                        </strong></span>
                                                                    <%else%>
                                                                        <input type="text" placeholder="" value="<%$data['uq_device_model']|@htmlentities%>" name="uq_device_model" id="uq_device_model" title="<%$this->lang->line('FEEDBACK_MANAGEMENT_DEVICE_MODEL')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                                                    <%/if%>
                                                                </div>
                                                                <div class="error-msg-form "><label class='error' id='uq_device_modelErr'></label></div>
                                                            </div>
                                                            <div class="form-row row-fluid " id="cc_sh_uq_device_os">
                                                                <label class="form-label span3 ">
                                                                    <%$form_config['uq_device_os']['label_lang']%>
                                                                </label> 
                                                                <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                                                                    <%if $mode eq "Update"%>
                                                                        <input type="hidden" class="ignore-valid" name="uq_device_os" id="uq_device_os" value="<%$data['uq_device_os']|@htmlentities%>" />
                                                                        <span class="frm-data-label">
                                                                            <strong>
                                                                                <%if $data['uq_device_os'] neq ""%>
                                                                                    <%$data['uq_device_os']%>
                                                                                <%else%>
                                                                                <%/if%>
                                                                            </strong></span>
                                                                        <%else%>
                                                                            <input type="text" placeholder="" value="<%$data['uq_device_os']|@htmlentities%>" name="uq_device_os" id="uq_device_os" title="<%$this->lang->line('FEEDBACK_MANAGEMENT_DEVICE_OS')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                                                        <%/if%>
                                                                    </div>
                                                                    <div class="error-msg-form "><label class='error' id='uq_device_osErr'></label></div>
                                                                </div>
                                                                <div class="form-row row-fluid " id="cc_sh_uq_app_version">
                                                                    <label class="form-label span3 ">
                                                                        <%$form_config['uq_app_version']['label_lang']%>
                                                                    </label> 
                                                                    <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                                                                        <%if $mode eq "Update"%>
                                                                            <input type="hidden" class="ignore-valid" name="uq_app_version" id="uq_app_version" value="<%$data['uq_app_version']|@htmlentities%>" />
                                                                            <span class="frm-data-label">
                                                                                <strong>
                                                                                    <%if $data['uq_app_version'] neq ""%>
                                                                                        <%$data['uq_app_version']%>
                                                                                    <%else%>
                                                                                    <%/if%>
                                                                                </strong></span>
                                                                            <%else%>
                                                                                <input type="text" placeholder="" value="<%$data['uq_app_version']|@htmlentities%>" name="uq_app_version" id="uq_app_version" title="<%$this->lang->line('FEEDBACK_MANAGEMENT_APP_VERSION')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                                                            <%/if%>
                                                                        </div>
                                                                        <div class="error-msg-form "><label class='error' id='uq_app_versionErr'></label></div>
                                                                    </div>
                                                                    <%assign var="child_module_name" value="query_images"%>
                                                                    <%if $child_assoc_status[$child_module_name] eq 1%>
                                                                        <%assign var="child_module_name" value="query_images"%>
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
                                                                            <%assign var="child_cnt" value="0"%>
                                                                            <%assign var="recMode" value="Add"%>
                                                                        <%/if%>
                                                                        <%if $child_cnt gt 0%>
                                                                            <%assign var="child_ord" value=$child_cnt%>
                                                                        <%else%>
                                                                            <%assign var="child_ord" value="1"%>
                                                                        <%/if%>
                                                                        <%assign var="child_merge_data" value=[]%>
                                                                        <div class="form-row row-fluid form-inline-child" id="child_module_<%$child_module_name%>">
                                                                            <input type="hidden" name="childModule[]" id="childModule_<%$child_module_name%>" value="<%$child_module_name%>" />
                                                                            <input type="hidden" name="childModuleSingle[<%$child_module_name%>]" id="childModuleSingle_<%$child_module_name%>" value="uqi_query_image"/>
                                                                            <input type="hidden" name="childModuleFileDesc[<%$child_module_name%>]" id="childModuleFileDesc_<%$child_module_name%>" value=""/>
                                                                            <input type="hidden" name="childModuleParField[<%$child_module_name%>]" id="childModuleParField_<%$child_module_name%>" value="iUserQueryId"/>
                                                                            <input type="hidden" name="childModuleParData[<%$child_module_name%>]" id="childModuleParData_<%$child_module_name%>" value="<%$this->general->getAdminEncodeURL($data['iUserQueryId'])%>"/>
                                                                            <input type="hidden" name="childModuleLayout[<%$child_module_name%>]" id="childModuleLayout_<%$child_module_name%>" value="Single"/>
                                                                            <input type="hidden" name="childModuleType[<%$child_module_name%>]" id="childModuleType_<%$child_module_name%>" value="Single"/>
                                                                            <input type="hidden" name="childModuleCnt[<%$child_module_name%>]" id="childModuleCnt_<%$child_module_name%>" value="<%$child_cnt%>" />
                                                                            <input type="hidden" name="childModuleInc[<%$child_module_name%>]" id="childModuleInc_<%$child_module_name%>" value="<%$child_cnt%>" />
                                                                            <input type="hidden" name="childModulePopup[<%$child_module_name%>]" id="childModulePopup_<%$child_module_name%>" value="No" />
                                                                            <input type="hidden" name="childModuleUploadURL[<%$child_module_name%>]" id="childModuleUploadURL_<%$child_module_name%>" value="<%$child_conf_arr['mod_enc_url']['upload_form_file']%>" />
                                                                            <input type="hidden" name="childModuleChosenURL[<%$child_module_name%>]" id="childModuleChosenURL_<%$child_module_name%>" value="<%$child_conf_arr['mod_enc_url']['get_chosen_auto_complete']%>" />
                                                                            <input type="hidden" name="childModuleParentURL[<%$child_module_name%>]" id="childModuleParentURL_<%$child_module_name%>" value="<%$child_conf_arr['mod_enc_url']['parent_source_options']%>" />
                                                                            <input type="hidden" name="childModuleTokenURL[<%$child_module_name%>]" id="childModuleTokenURL_<%$child_module_name%>" value="<%$child_conf_arr['mod_enc_url']['get_token_auto_complete']%>" />
                                                                            <input type="hidden" name="childModuleShowHide[<%$child_module_name%>]" id="childModuleShowHide_<%$child_module_name%>" value="Yes" />
                                                                            <%section name=i loop=$child_ord%>
                                                                                <%assign var="row_index" value=$smarty.section.i.index%>
                                                                                <%assign var="child_id" value=$child_data[i]['iUserQueryImageId']%>
                                                                                <%assign var="enc_child_id" value=$this->general->getAdminEncodeURL($child_id)%>
                                                                                <%assign var="child_id_temp" value=[$child_data[i]['uqi_query_image']]%>
                                                                                <%assign var="child_merge_data" value=$child_merge_data|@array_merge:$child_id_temp%>
                                                                                <input type="hidden" name="child[query_images][id][<%$row_index%>]" id="child_query_images_id_<%$row_index%>" value="<%$child_id%>" />
                                                                                <input type="hidden" name="child[query_images][enc_id][<%$row_index%>]" id="child_query_images_enc_id_<%$row_index%>" value="<%$enc_child_id%>" />
                                                                                <input type="hidden" name="child[query_images][uqi_user_query_id][<%$row_index%>]" id="child_query_images_uqi_user_query_id_<%$row_index%>" value="<%$child_data[i]['uqi_user_query_id']%>"  class='ignore-valid ' />
                                                                                <input type="hidden" name="child[query_images][uqi_added_at][<%$row_index%>]" id="child_query_images_uqi_added_at_<%$row_index%>" value="<%$child_data[i]['uqi_added_at']%>"  class='ignore-valid '  aria-date-format='yy-mm-dd'  aria-format-type='date' />
                                                                                <input type="hidden" name="child[query_images][uqi_status][<%$row_index%>]" id="child_query_images_uqi_status_<%$row_index%>" value="<%$child_data[i]['uqi_status']%>"  class='ignore-valid ' />
                                                                            <%/section%>
                                                                            <label class="form-label span3 inline-module-label"><%$this->lang->line('FEEDBACK_MANAGEMENT_QUERY_IMAGES')%></label>
                                                                            <div class="form-right-div form-inline-child <%if $recMode eq "Update"%>frm-elements-div<%/if%>"  id="child_module_rel_<%$child_module_name%>">
                                                                                <%assign var="cb_uqi_query_image" value=$child_func[0]["uqi_query_image"]%>
                                                                                <%if $recMode eq "Update" && $mode eq "Update"%>
                                                                                    <!-- View Only -->
                                                                                <%else%>
                                                                                    <div  class='btn-uploadify frm-size-medium' >
                                                                                        <div id="upload_drop_zone_child_query_images_uqi_query_image_0" class="upload-drop-zone"></div>
                                                                                        <div class="uploader upload-src-zone">
                                                                                            <input type="file" name="uploadify_child[query_images][uqi_query_image][0]" id="uploadify_child_query_images_uqi_query_image_0" title="<%$child_conf_arr['form_config']['uqi_query_image']['label_lang']%>" multiple=true />
                                                                                            <span class="filename" id="preview_child_query_images_uqi_query_image_0"><%$this->lang->line('GENERIC_DROP_FILES_HERE_OR_CLICK_TO_UPLOAD')%></span>
                                                                                            <span class="action">Choose File</span>
                                                                                        </div>
                                                                                    </div> 
                                                                                <%/if%>
                                                                                <span class="file-viewer">
                                                                                    <a href="javascript://" class="viewer-anchor tipR" style="text-decoration:none;" data-viewer-target="upload_multi_file_query_images" data-viewer-loop="row-upload-file" data-viewer-ext="gif,png,jpg,jpeg,jpe,bmp,ico">
                                                                                        <span class="icon24 minia-icon-eye viewer-icon" aria-hidden="true"></span>
                                                                                    </a>
                                                                                </span>
                                                                                <span class="input-comment">
                                                                                    <a href="javascript://" style="text-decoration: none;" class="tipR" title="<%$this->lang->line('GENERIC_VALID_EXTENSIONS')%> : gif, png, jpg, jpeg, jpe, bmp, ico.<br><%$this->lang->line('GENERIC_VALID_SIZE')%> : <%$this->lang->line('GENERIC_LESS_THAN')%> (<) 100 MB."><span class="icomoon-icon-help"></span></a>
                                                                                </span>
                                                                                <div class='clear upload-progress' id='progress_child_query_images_uqi_query_image_0' >
                                                                                    <div class='upload-progress-bar progress progress-striped active'>
                                                                                        <div class='bar' id='practive_child_query_images_uqi_query_image_0'></div>
                                                                                    </div>
                                                                                    <div class='upload-cancel-div'><a class='upload-cancel' href='javascript://'>Cancel</a></div>
                                                                                    <div class='clear'></div>
                                                                                </div>
                                                                                <div id="upload_multi_file_query_images" class="upload-multi-file frm-size-medium clear">
                                                                                    <%assign var="is_images_exists" value=0%>
                                                                                    <%section name=j loop=$child_cnt%>
                                                                                        <%assign var="row_index" value=$smarty.section.j.index%>
                                                                                        <%if $child_img_html[$row_index]["uqi_query_image"] neq ""%>
                                                                                            <%assign var="is_images_exists" value=1%>
                                                                                            <div class="row-upload-file" id="upload_row_query_images_<%$row_index%>">
                                                                                                <input type="hidden" value="<%$child_data[j]['uqi_query_image']%>" name="child[query_images][old_uqi_query_image][<%$row_index%>]" id="child_query_images_old_uqi_query_image_<%$row_index%>" />
                                                                                                <input type="hidden" value="<%$child_data[j]['uqi_query_image']%>" name="child[query_images][uqi_query_image][<%$row_index%>]" id="child_query_images_uqi_query_image_<%$row_index%>" class="ignore-valid" aria-extensions="gif,png,jpg,jpeg,jpe,bmp,ico" aria-valid-size="<%$this->lang->line('GENERIC_LESS_THAN')%> (<) 100 MB"/>
                                                                                                <div class="">
                                                                                                    <%$child_img_html[$row_index]["uqi_query_image"]%>
                                                                                                </div>
                                                                                            </div>
                                                                                        <%/if%>
                                                                                    <%/section%>
                                                                                    <%if $is_images_exists eq 0%>
                                                                                        <input type="hidden" value="" name="child[query_images][uqi_query_image][0]" id="child_query_images_uqi_query_image_0" class="_upload_req_file"/>
                                                                                    <%/if%>
                                                                                </div>
                                                                                <div class="clear"></div>
                                                                            </div>
                                                                            <div class="error-msg-form inline-module-error">
                                                                                <label class='error' id='child_query_images_uqi_query_image_0Err'></label>
                                                                            </div>
                                                                        </div>
                                                                    <%elseif $child_assoc_status[$child_module_name] eq 2%>
                                                                        <%assign var="child_module_name" value="query_images"%>
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
                                                                            <%assign var="child_cnt" value="0"%>
                                                                            <%assign var="recMode" value="Add"%>
                                                                        <%/if%>
                                                                        <%if $child_cnt gt 0%>
                                                                            <%assign var="child_ord" value=$child_cnt%>
                                                                        <%else%>
                                                                            <%assign var="child_ord" value="1"%>
                                                                        <%/if%>
                                                                        <%assign var="child_merge_data" value=[]%>
                                                                        <div class="form-row row-fluid form-inline-child" id="child_module_<%$child_module_name%>">
                                                                            <input type="hidden" name="childModule[]" id="childModule_<%$child_module_name%>" value="<%$child_module_name%>" />
                                                                            <input type="hidden" name="childModuleShowHide[<%$child_module_name%>]" id="childModuleShowHide_<%$child_module_name%>" value="No" />
                                                                            <%section name=i loop=$child_ord%>
                                                                                <%assign var="row_index" value=$smarty.section.i.index%>
                                                                                <%assign var="child_id" value=$child_data[i]['iUserQueryImageId']%>
                                                                                <%assign var="enc_child_id" value=$this->general->getAdminEncodeURL($child_id)%>
                                                                                <%assign var="child_id_temp" value=[$child_data[i]['uqi_query_image']]%>
                                                                                <%assign var="child_merge_data" value=$child_merge_data|@array_merge:$child_id_temp%>
                                                                                <input type="hidden" name="child[query_images][id][<%$row_index%>]" id="child_query_images_id_<%$row_index%>" value="<%$child_id%>" />
                                                                                <input type="hidden" name="child[query_images][enc_id][<%$row_index%>]" id="child_query_images_enc_id_<%$row_index%>" value="<%$enc_child_id%>" />
                                                                                <input type="hidden" name="child[query_images][uqi_user_query_id][<%$row_index%>]" id="child_query_images_uqi_user_query_id_<%$row_index%>" value="<%$child_data[i]['uqi_user_query_id']%>"  class='ignore-valid ' />
                                                                                <input type="hidden" name="child[query_images][uqi_added_at][<%$row_index%>]" id="child_query_images_uqi_added_at_<%$row_index%>" value="<%$child_data[i]['uqi_added_at']%>"  class='ignore-valid '  aria-date-format='yy-mm-dd'  aria-format-type='date' />
                                                                                <input type="hidden" name="child[query_images][uqi_status][<%$row_index%>]" id="child_query_images_uqi_status_<%$row_index%>" value="<%$child_data[i]['uqi_status']%>"  class='ignore-valid ' />
                                                                            <%/section%>
                                                                            <label class="form-label span3 inline-module-label"><%$this->lang->line('FEEDBACK_MANAGEMENT_QUERY_IMAGES')%></label>
                                                                            <div class="form-right-div form-inline-child <%if $recMode eq "Update"%>frm-elements-div<%/if%>"  id="child_module_rel_<%$child_module_name%>">
                                                                                <div id="upload_multi_file_query_images" class="upload-multi-file frm-size-medium clear">
                                                                                    <%assign var="is_images_exists" value=0%>
                                                                                    <%section name=j loop=$child_cnt%>
                                                                                        <%assign var="row_index" value=$smarty.section.j.index%>
                                                                                        <%if $child_img_html[$row_index]["uqi_query_image"] neq ""%>
                                                                                            <%assign var="is_images_exists" value=1%>
                                                                                            <div class="row-upload-file" id="upload_row_query_images_<%$row_index%>">
                                                                                                <input type="hidden" value="<%$child_data[j]['uqi_query_image']%>" name="child[query_images][old_uqi_query_image][<%$row_index%>]" id="child_query_images_old_uqi_query_image_<%$row_index%>" />
                                                                                                <input type="hidden" value="<%$child_data[j]['uqi_query_image']%>" name="child[query_images][uqi_query_image][<%$row_index%>]" id="child_query_images_uqi_query_image_<%$row_index%>" class="ignore-valid" aria-extensions="gif,png,jpg,jpeg,jpe,bmp,ico" aria-valid-size="<%$this->lang->line('GENERIC_LESS_THAN')%> (<) 100 MB"/>
                                                                                                <div class="">
                                                                                                    <%$child_img_html[$row_index]["uqi_query_image"]%>
                                                                                                </div>
                                                                                            </div>
                                                                                        <%/if%>
                                                                                    <%/section%>
                                                                                    <%if $is_images_exists eq 0%>
                                                                                        <input type="hidden" value="" name="child[query_images][uqi_query_image][0]" id="child_query_images_uqi_query_image_0" class="_upload_req_file"/>
                                                                                    <%/if%>
                                                                                </div>
                                                                                <div class="clear"></div>
                                                                            </div>
                                                                        </div>
                                                                    <%/if%>
                                                                    <div class="form-row row-fluid " id="cc_sh_uq_note">
                                                                        <label class="form-label span3 ">
                                                                            <%$form_config['uq_note']['label_lang']%>
                                                                        </label> 
                                                                        <div class="form-right-div  ">
                                                                            <textarea placeholder=""  name="uq_note" id="uq_note" title="<%$this->lang->line('FEEDBACK_MANAGEMENT_NOTES')%>"  data-ctrl-type='textarea'  class='elastic frm-size-medium'  ><%$data['uq_note']%></textarea>
                                                                        </div>
                                                                        <div class="error-msg-form "><label class='error' id='uq_noteErr'></label></div>
                                                                    </div>
                                                                    <div class="form-row row-fluid " id="cc_sh_uq_status">
                                                                        <label class="form-label span3 ">
                                                                            <%$form_config['uq_status']['label_lang']%>
                                                                        </label> 
                                                                        <div class="form-right-div  ">
                                                                            <%assign var="opt_selected" value=$data['uq_status']%>
                                                                            <%$this->dropdown->display("uq_status","uq_status","  title='<%$this->lang->line('FEEDBACK_MANAGEMENT_STATUS')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'FEEDBACK_MANAGEMENT_STATUS')%>'  ", "|||", "", $opt_selected,"uq_status")%>
                                                                        </div>
                                                                        <div class="error-msg-form "><label class='error' id='uq_statusErr'></label></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="clear"></div>
                                                        <div class="frm-bot-btn <%$rl_theme_arr['frm_stand_action_bar']%> <%$rl_theme_arr['frm_stand_action_btn']%> popup-footer">
                                                            <%if $rl_theme_arr['frm_stand_ctrls_view'] eq 'No'%>
                                                                <%assign var='rm_ctrl_directions' value=true%>
                                                            <%/if%>
                                                            <%include file="feedback_management_add_buttons.tpl"%>
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
        "query_images": {
            "uqi_user_query_id": {
                "type": "dropdown",
                "vUniqueName": "uqi_user_query_id",
                "editrules": {
                    "infoArr": []
                }
            },
            "uqi_query_image": {
                "type": "file",
                "vUniqueName": "uqi_query_image",
                "editrules": {
                    "infoArr": []
                }
            },
            "uqi_added_at": {
                "type": "date",
                "vUniqueName": "uqi_added_at",
                "editrules": {
                    "infoArr": []
                }
            },
            "uqi_status": {
                "type": "dropdown",
                "vUniqueName": "uqi_status",
                "editrules": {
                    "infoArr": []
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
        "query_images" : "<%$this->general->processMessageLabel('ACTION_ARE_YOU_SURE_WANT_TO_DELETE_THIS_RECORD_C63')%>",
            }
        }
    };
    
    callSwitchToSelf();
<%/javascript%>
<%$this->js->add_js('admin/feedback_management_add_js.js')%>

<%$this->js->add_js("admin/custom/feedbackjs.js")%>
<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
<%javascript%>
    Project.modules.feedback_management.callEvents();
<%/javascript%>