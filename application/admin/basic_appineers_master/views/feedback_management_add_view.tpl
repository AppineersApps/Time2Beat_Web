<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-view-container">
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
                <!-- Module View Block -->
                <div id="feedback_management" class="frm-module-block frm-view-block frm-stand-view">
                    <!-- Form Hidden Fields Unit -->
                    <input type="hidden" id="id" name="id" value="<%$enc_id%>" />
                    <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>" />
                    <input type="hidden" id="ctrl_flow" name="ctrl_flow" value="<%$ctrl_flow%>" />
                    <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>" />
                    <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>" />
                    <input type="hidden" name="uq_updated_at" id="uq_updated_at" value="<%$this->general->dateSystemFormat($data['uq_updated_at'])%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
                    <!-- Form Display Fields Unit -->
                    <div class="main-content-block " id="main_content_block">
                        <div style="width:98%;" class="frm-block-layout pad-calc-container">
                            <div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
                                <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('FEEDBACK_MANAGEMENT_FEEDBACK_MANAGEMENT')%></h4></div>
                                <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                    <div class="form-row row-fluid " id="cc_sh_uq_user_id">
                                        <label class="form-label span3">
                                            <%$form_config['uq_user_id']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>">
                                            <span class="frm-data-label"><strong><%$this->general->displayKeyValueData($data['uq_user_id'], $opt_arr['uq_user_id'])%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_uq_feedback">
                                        <label class="form-label span3">
                                            <%$form_config['uq_feedback']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>">
                                            <span class="frm-data-label"><strong><%$data['uq_feedback']%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_uq_added_at">
                                        <label class="form-label span3">
                                            <%$form_config['uq_added_at']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%else%>input-append text-append-prepend<%/if%>">
                                            <span class="frm-data-label"><strong><%$this->general->dateSystemFormat($data['uq_added_at'])%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_uq_device_type">
                                        <label class="form-label span3">
                                            <%$form_config['uq_device_type']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>">
                                            <span class="frm-data-label"><strong><%$data['uq_device_type']%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_uq_device_model">
                                        <label class="form-label span3">
                                            <%$form_config['uq_device_model']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>">
                                            <span class="frm-data-label"><strong><%$data['uq_device_model']%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_uq_device_os">
                                        <label class="form-label span3">
                                            <%$form_config['uq_device_os']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>">
                                            <span class="frm-data-label"><strong><%$data['uq_device_os']%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_uq_app_version">
                                        <label class="form-label span3">
                                            <%$form_config['uq_app_version']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>">
                                            <span class="frm-data-label"><strong><%$data['uq_app_version']%></strong></span>
                                        </div>
                                    </div>
                                    <%assign var="child_module_name" value="query_images"%>
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
                                    <div class="form-row row-fluid " id="cc_sh_uq_note">
                                        <label class="form-label span3">
                                            <%$form_config['uq_note']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div ">
                                            <span class="frm-data-label"><strong><%$data['uq_note']%></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_uq_status">
                                        <label class="form-label span3">
                                            <%$form_config['uq_status']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div ">
                                            <span class="frm-data-label"><strong><%$this->general->displayKeyValueData($data['uq_status'], $opt_arr['uq_status'])%></strong></span>
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

<%$this->js->add_js("admin/custom/feedbackjs.js")%>
<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
