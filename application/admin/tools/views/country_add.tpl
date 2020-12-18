<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-form-container<%if $top_detail_view["exists"] eq "1"%> has-detail-view<%/if%>">
    <%include file="country_add_strip.tpl"%>
    <div class="<%$module_name%>" data-form-name="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-tab-spacing" >
            <input type="hidden" id="projmod" name="projmod" value="country" />
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Module Tabs & Top Detail View -->
            <div class="top-frm-tab-layout" id="top_frm_tab_layout">
                <!-- Top Detail View Block -->
                <%if $top_detail_view["exists"] eq "1"%>
                    <%$top_detail_view["html"]%>
                <%/if%>
                <!-- Relational Module Tabs -->
                <div id="ad_form_outertab" class="module-navigation-tabs">
                    <%if $tabing_allow eq true%>
                        <%include file="country_tabs.tpl" %>
                    <%/if%>
                </div>
            </div>
            <!-- Middle Content -->
            <div id="scrollable_content" class="scrollable-content popup-content top-block-spacing top-frm-block-spacing">
                <div id="country" class="frm-module-block frm-elem-block frm-stand-view">
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
                                    <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('COUNTRY_COUNTRY')%></h4></div>
                                    <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                        <div class="form-row row-fluid " id="cc_sh_mc_country">
                                            <label class="form-label span3 ">
                                                <%$form_config['mc_country']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mc_country']|@htmlentities%>" name="mc_country" id="mc_country" title="<%$this->lang->line('COUNTRY_COUNTRY')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mc_countryErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mc_country_code">
                                            <label class="form-label span3 ">
                                                <%$form_config['mc_country_code']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mc_country_code']|@htmlentities%>" name="mc_country_code" id="mc_country_code" title="<%$this->lang->line('COUNTRY_COUNTRY_CODE')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mc_country_codeErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mc_country_code_i_s_o_3">
                                            <label class="form-label span3 ">
                                                <%$form_config['mc_country_code_i_s_o_3']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <input type="text" placeholder="" value="<%$data['mc_country_code_i_s_o_3']|@htmlentities%>" name="mc_country_code_i_s_o_3" id="mc_country_code_i_s_o_3" title="<%$this->lang->line('COUNTRY_COUNTRY_CODE_ISO_C453')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mc_country_code_i_s_o_3Err'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mc_description">
                                            <label class="form-label span3 ">
                                                <%$form_config['mc_description']['label_lang']%>
                                            </label> 
                                            <div class="form-right-div  ">
                                                <textarea placeholder=""  name="mc_description" id="mc_description" title="<%$this->lang->line('COUNTRY_DESCRIPTION')%>"  data-ctrl-type='textarea'  class='elastic frm-size-medium'  ><%$data['mc_description']%></textarea>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mc_descriptionErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_mc_status">
                                            <label class="form-label span3 ">
                                                <%$form_config['mc_status']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['mc_status']%>
                                                <%$this->dropdown->display("mc_status","mc_status","  title='<%$this->lang->line('COUNTRY_STATUS')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'COUNTRY_STATUS')%>'  ", "|||", "", $opt_selected,"mc_status")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mc_statusErr'></label></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="frm-bot-btn <%$rl_theme_arr['frm_stand_action_bar']%> <%$rl_theme_arr['frm_stand_action_btn']%> popup-footer">
                                <%if $rl_theme_arr['frm_stand_ctrls_view'] eq 'No'%>
                                    <%assign var='rm_ctrl_directions' value=true%>
                                <%/if%>
                                <%include file="country_add_buttons.tpl"%>
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
<%$this->js->add_js('admin/country_add_js.js')%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
<%javascript%>
    Project.modules.country.callEvents();
<%/javascript%>