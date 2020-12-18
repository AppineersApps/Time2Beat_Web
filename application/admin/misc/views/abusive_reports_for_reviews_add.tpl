<%if $this->input->is_ajax_request()%>
<%$this->js->clean_js()%>
<%/if%>
<div class="module-form-container">
<%include file="abusive_reports_for_reviews_add_strip.tpl"%>
<div class="<%$module_name%>" data-form-name="<%$module_name%>">
<div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
<input type="hidden" id="projmod" name="projmod" value="abusive_reports_for_posts" />
<!-- Page Loader -->
<div id="ajax_qLoverlay"></div>
<div id="ajax_qLbar"></div>
<!-- Module Tabs & Top Detail View -->
<div class="top-frm-tab-layout" id="top_frm_tab_layout">
</div>
<!-- Middle Content -->
<div id="scrollable_content" class="scrollable-content popup-content top-block-spacing ">
    <div id="abusive_reports_for_posts" class="frm-module-block frm-elem-block frm-stand-view">
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
                        <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('ABUSIVE_REPORTS_FOR_REVIEWS_ABUSIVE_REPORTS_FOR_REVIEWS')%></h4></div>
                        <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                            <div class="form-row row-fluid " id="cc_sh_arfp_reported_by">
                                <label class="form-label span3 ">
                                    <%$form_config['arfp_reported_by']['label_lang']%>
                                </label> 
                                <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                                    <%assign var="opt_selected" value=$data['arfp_reported_by']%>
                                    <%if $mode eq "Update"%>
                                        <input type="hidden" name="arfp_reported_by" id="arfp_reported_by" value="<%$data['arfp_reported_by']%>" class="ignore-valid"/>
                                        <%assign var="combo_arr" value=$opt_arr["arfp_reported_by"]%>
                                        <%assign var="opt_display" value=$this->general->displayKeyValueData($opt_selected, $combo_arr)%>
                                        <span class="frm-data-label">
                                            <strong>
                                                <%if $opt_display neq ""%>
                                                    <%$opt_display%>
                                                <%else%>
                                                <%/if%>
                                            </strong></span>
                                        <%else%>
                                            <%$this->dropdown->display("arfp_reported_by","arfp_reported_by","  title='<%$this->lang->line('ABUSIVE_REPORTS_FOR_REVIEWS_REPORTED_BY')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'ABUSIVE_REPORTS_FOR_REVIEWS_REPORTED_BY')%>'  ", "|||", "", $opt_selected,"arfp_reported_by")%>
                                        <%/if%>
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='arfp_reported_byErr'></label></div>
                                </div>
                                <div class="form-row row-fluid " id="cc_sh_arfp_post_id">
                                    <!-- <label class="form-label span3 ">
                                        <%$form_config['arfp_review_id']['label_lang']%>
                                    </label> --> 
                                    <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                                        <%if $mode eq "Update"%>
                                            <input type="hidden" class="ignore-valid" name="arfp_review_id" id="arfp_review_id" value="<%$data['arfp_review_id']|@htmlentities%>" />
                                            <!-- <span class="frm-data-label">
                                                <strong>
                                                    <%if $data['arfp_review_id'] neq ""%>
                                                        <%$data['arfp_review_id']%>
                                                    <%else%>
                                                    <%/if%>
                                                </strong></span> -->
                                            <%else%>
                                                <input type="text" placeholder="" value="<%$data['arfp_review_id']|@htmlentities%>" name="arfp_review_id" id="arfp_abusive_reports_for_review_id" title="<%$this->lang->line('ABUSIVE_REPORTS_FOR_REVIEW_REVIEW')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                            <%/if%>
                                        </div>
                                        <div class="error-msg-form "><label class='error' id='arfp_abusive_reports_for_review_idErr'></label></div>
                                    </div>
                                    <%assign var="child_module_name" value="user_review_images"%>
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
                                                                        <input  type="hidden" name="childModule[]" id="childModule_<%$child_module_name%>" value="<%$child_module_name%>" />
                                                                        <input  type="hidden" name="childModuleShowHide[<%$child_module_name%>]" id="childModuleShowHide_<%$child_module_name%>" value="No" />
                                                                        <%section name=i loop=$child_ord%>
                                                                            <%assign var="row_index" value=$smarty.section.i.index%>
                                                                            <%assign var="child_id" value=$child_data[i]['iUserReviewImageId']%>
                                                                            <%assign var="enc_child_id" value=$this->general->getAdminEncodeURL($child_id)%>
                                                                            <%assign var="child_id_temp" value=[$child_data[i]['uri_review_image']]%>
                                                                            <%assign var="child_merge_data" value=$child_merge_data|@array_merge:$child_id_temp%>
                                                                            <input type="hidden" name="child[user_review_images][id][<%$row_index%>]" id="child_user_review_images_id_<%$row_index%>" value="<%$child_id%>" />
                                                                            <input type="hidden" name="child[user_review_images][enc_id][<%$row_index%>]" id="child_user_review_images_enc_id_<%$row_index%>" value="<%$enc_child_id%>" />
                                                                            <input type="hidden" name="child[user_review_images][uri_user_review_id][<%$row_index%>]" id="child_user_review_images_uri_user_review_id_<%$row_index%>" value="<%$child_data[i]['uri_user_review_id']%>"  class='ignore-valid ' />
                                                                            <input type="hidden" name="child[user_review_images][uri_added_at][<%$row_index%>]" id="child_user_review_images_uri_added_at_<%$row_index%>" value="<%$child_data[i]['uri_added_at']%>"  class='ignore-valid '  aria-date-format='yy-mm-dd'  aria-format-type='date' />
                                                                            <input type="hidden" name="child[user_review_images][uri_status][<%$row_index%>]" id="child_user_review_images_uri_status_<%$row_index%>" value="<%$child_data[i]['uri_status']%>"  class='ignore-valid ' />
                                                                        <%/section%>
                                                                        <label class="form-label span3 inline-module-label"><%$this->lang->line('USER_STORE_REVIEW_USER_REVIEW_IMAGES')%></label>
                                                                        <div class="form-right-div form-inline-child <%if $recMode eq "Update"%>frm-elements-div<%/if%>"  id="child_module_rel_<%$child_module_name%>">
                                                                            <div id="upload_multi_file_user_review_images" class="upload-multi-file frm-size-medium clear">
                                                                                <%assign var="is_images_exists" value=0%>
                                                                                <%section name=j loop=$child_cnt%>
                                                                                    <%assign var="row_index" value=$smarty.section.j.index%>
                                                                                    <%if $child_img_html[$row_index]["uri_review_image"] neq ""%>
                                                                                        <%assign var="is_images_exists" value=1%>
                                                                                        <div class="row-upload-file" id="upload_row_user_review_images_<%$row_index%>">
                                                                                            <input type="hidden" value="<%$child_data[j]['uri_review_image']%>" name="child[user_review_images][old_uri_review_image][<%$row_index%>]" id="child_user_review_images_old_uri_review_image_<%$row_index%>" />
                                                                                            <input type="hidden" value="<%$child_data[j]['uri_review_image']%>" name="child[user_review_images][uri_review_image][<%$row_index%>]" id="child_user_review_images_uri_review_image_<%$row_index%>" class="ignore-valid" aria-extensions="gif,png,jpg,jpeg,jpe,bmp,ico" aria-valid-size="<%$this->lang->line('GENERIC_LESS_THAN')%> (<) 100 MB"/>
                                                                                            <div class="">
                                                                                                <%$child_img_html[$row_index]["uri_review_image"]%>
                                                                                            </div>
                                                                                        </div>
                                                                                    <%/if%>
                                                                                <%/section%>
                                                                                <%if $is_images_exists eq 0%>
                                                                                    <input type="hidden" value="" name="child[user_review_images][uri_review_image][0]" id="child_user_review_images_uri_review_image_0" class="_upload_req_file"/>
                                                                                <%/if%>
                                                                            </div>
                                                                            <div class="clear"></div>
                                                                        </div>
                                                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_arfp_post_id">
                                    <label class="form-label span3 ">
                                        <%$form_config['p_post_title']['label_lang']%>
                                    </label> 
                                    <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                                       <input type="text" placeholder="" value="<%$data['p_post_title']|@htmlentities%>" name="p_post_title" id="p_post_title" title="<%$this->lang->line('ABUSIVE_REPORTS_FOR_REVIEWS_MESSAGE')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                         
                                    </div>
                                        <div class="error-msg-form "><label class='error' id='arfp_abusive_reports_for_review_idErr'></label></div>
                                    </div>
                                    <div class="form-row row-fluid " id="cc_sh_arfp_message">
                                        <label class="form-label span3 ">
                                            <%$form_config['arfp_message']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                                            <%if $mode eq "Update"%>
                                                <input type="hidden" class="ignore-valid" name="arfp_message" id="arfp_message" value="<%$data['arfp_message']|@htmlentities%>" />
                                                <span class="frm-data-label">
                                                    <strong>
                                                        <%if $data['arfp_message'] neq ""%>
                                                            <%$data['arfp_message']%>
                                                        <%else%>
                                                        <%/if%>
                                                    </strong></span>
                                                <%else%>
                                                    <input type="text" placeholder="" value="<%$data['arfp_message']|@htmlentities%>" name="arfp_message" id="arfp_message" title="<%$this->lang->line('ABUSIVE_REPORTS_FOR_REVIEW_MESSAGE')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                                <%/if%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='arfp_messageErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_arfp_added_at">
                                            <label class="form-label span3 ">
                                                <%$form_config['arfp_added_at']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%else%>input-append text-append-prepend<%/if%> ">
                                                <%if $mode eq "Update"%>
                                                    <input type="hidden" name="arfp_added_at" id="arfp_added_at" value="<%$this->general->dateSystemFormat($data['arfp_added_at'])%>" class="ignore-valid view-label-only"  data-ctrl-type='date'  class='frm-datepicker ctrl-append-prepend frm-size-medium'  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
                                                    <%assign var="display_date" value=$this->general->dateSystemFormat($data['arfp_added_at'])%>
                                                    <span class="frm-data-label">
                                                        <strong>
                                                            <%if $display_date neq ""%>
                                                                <%$display_date%>
                                                            <%else%>
                                                            <%/if%>
                                                        </strong></span>
                                                    <%else%>
                                                        <input type="text" value="<%$this->general->dateSystemFormat($data['arfp_added_at'])%>" placeholder="" name="arfp_added_at" id="arfp_added_at" title="<%$this->lang->line('ABUSIVE_REPORTS_FOR_REVIEW_REPORTED_ON')%>"  data-ctrl-type='date'  class='frm-datepicker ctrl-append-prepend frm-size-medium'  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date'  />
                                                        <span class='add-on text-addon date-append-class icomoon-icon-calendar'></span>
                                                    <%/if%>
                                                </div>
                                                <div class="error-msg-form "><label class='error' id='arfp_added_atErr'></label></div>
                                            </div>
                                            <div class="form-row row-fluid " id="cc_sh_status">
                                                <label class="form-label span3 ">
                                                    <%$form_config['status']['label_lang']%>
                                                </label> 
                                                <div class="form-right-div  ">
                                                    <%assign var="opt_selected" value=$data['status']%>
                                                    <%$this->dropdown->display("status","status","  title='<%$this->lang->line('ABUSIVE_REPORTS_FOR_REVIEWS_STATUS')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'ABUSIVE_REPORTS_FOR_REVIEWS_STATUS')%>'  ", "|||", "", $opt_selected,"status")%>
                                                </div>
                                                <div class="error-msg-form "><label class='error' id='statusErr'></label></div>
                                            </div>

                                             </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="frm-bot-btn <%$rl_theme_arr['frm_stand_action_bar']%> <%$rl_theme_arr['frm_stand_action_btn']%> popup-footer">
                                    <%if $rl_theme_arr['frm_stand_ctrls_view'] eq 'No'%>
                                        <%assign var='rm_ctrl_directions' value=true%>
                                    <%/if%>
                                    <%include file="abusive_reports_for_reviews_add_buttons.tpl"%>
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
<%$this->js->add_js('admin/abusive_reports_for_posts_add_js.js')%>

<%$this->js->add_js("admin/custom/hide_form_buttons.js")%>
<%if $this->input->is_ajax_request()%>
<%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
<%$this->css->css_src()%>
<%/if%> 
<%javascript%>
Project.modules.abusive_reports_for_posts.callEvents();
<%/javascript%>