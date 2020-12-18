<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-form-container">
    <%include file="languagelabels_add_strip.tpl"%>
    <div class="<%$module_name%>" data-form-name="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
            <input type="hidden" id="projmod" name="projmod" value="languagelabels" />
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Module Tabs & Top Detail View -->
            <div class="top-frm-tab-layout" id="top_frm_tab_layout">
            </div>
            <!-- Middle Content -->
            <div id="scrollable_content" class="scrollable-content top-block-spacing ">
                <div id="languagelabels" class="frm-module-block frm-elem-block frm-stand-view">
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
                        <div class="main-content-block" id="main_content_block">
                            <div style="width:98%" class="frm-block-layout pad-calc-container">
                                <div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
                                    <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('LANGUAGELABELS_LANGUAGE_LABELS')%></h4></div>
                                    <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                        <div class="form-row row-fluid" id="cc_sh_mll_label">
                                            <label class="form-label span3">
                                                <%$form_config['mll_label']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div   ">
                                                <input type="text" placeholder="" value="<%$data['mll_label']|@htmlentities%>" name="mll_label" id="mll_label" title="<%$this->lang->line('LANGUAGELABELS_LANGUAGE_LABEL')%>"  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mll_labelErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid" id="cc_sh_mll_page">
                                            <label class="form-label span3">
                                                <%$form_config['mll_page']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div   ">
                                                <%assign var="opt_selected" value=$data['mll_page']%>
                                                <%$this->dropdown->display("mll_page","mll_page","  title='<%$this->lang->line('LANGUAGELABELS_SELECT_PAGE')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'LANGUAGELABELS_SELECT_PAGE')%>'  ", "|||", "", $opt_selected,"mll_page")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mll_pageErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid" id="cc_sh_mll_module">
                                            <label class="form-label span3">
                                                <%$form_config['mll_module']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div   ">
                                                <%assign var="opt_selected" value=$data['mll_module']%>
                                                <%$this->dropdown->display("mll_module","mll_module","  title='<%$this->lang->line('LANGUAGELABELS_MODULE')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'LANGUAGELABELS_MODULE')%>'  ", "|||", "", $opt_selected,"mll_module")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mll_moduleErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid" id="cc_sh_mllt_value">
                                            <div class="clear prime-lang-block" id="lnpr_mllt_value_<%$prlang%>">
                                                <label class="form-label span3"><%$this->lang->line('LANGUAGELABELS_VALUE')%> <em>*</em> [<%$lang_info[$prlang]['vLangTitle']%>] </label> 
                                                <div class="form-right-div  ">
                                                    <textarea placeholder="" name="mllt_value" id="mllt_value" title="<%$this->lang->line('LANGUAGELABELS_VALUE')%>"  class='elastic frm-size-medium' aria-multi-lingual='parent' aria-lang-parent='mllt_value' aria-lang-code='<%$prlang%>'><%$lang_data[$prlang]['vTitle']|@htmlentities%></textarea>
                                                </div>
                                                <div class="error-msg-form "><label class='error' id='mllt_valueErr'></label></div>
                                            </div>
                                            <%if $exlang_arr|@is_array && $exlang_arr|@count gt 0%>
                                                <%section name=ml loop=$exlang_arr%>
                                                    <%assign var="exlang" value=$exlang_arr[ml]%>
                                                    <div class="clear other-lang-block" id="lnsh_mllt_value_<%$exlang%>" style="<%if $exlang neq $dflang%>display:none;<%/if%>">
                                                        <label class="form-label span3" style="margin-left:0"><%$this->lang->line('LANGUAGELABELS_VALUE')%> <em>*</em>  [<%$lang_info[$exlang]['vLangTitle']%>]</label> 
                                                        <div class="form-right-div">
                                                            <textarea placeholder="" name="langmllt_value[<%$exlang%>]" id="lang_mllt_value_<%$exlang%>" title="<%$this->lang->line('LANGUAGELABELS_VALUE')%>"  class='elastic frm-size-medium' aria-multi-lingual="child" aria-lang-parent='mllt_value' aria-lang-code='<%$exlang%>'><%$lang_data[$exlang]['vTitle']%></textarea>
                                                        </div>
                                                    </div>
                                                <%/section%>
                                                <div class="lang-flag-css">
                                                    <%$this->general->getAdminLangFlagHTML("mllt_value", $exlang_arr, $lang_info)%>
                                                </div>
                                            <%/if%>
                                        </div>
                                        <div class="form-row row-fluid" id="cc_sh_mll_status">
                                            <label class="form-label span3">
                                                <%$form_config['mll_status']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div   ">
                                                <%assign var="opt_selected" value=$data['mll_status']%>
                                                <%$this->dropdown->display("mll_status","mll_status","  title='<%$this->lang->line('LANGUAGELABELS_STATUS')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'LANGUAGELABELS_STATUS')%>'  ", "|||", "", $opt_selected,"mll_status")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mll_statusErr'></label></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="frm-bot-btn <%$rl_theme_arr['frm_stand_action_bar']%> <%$rl_theme_arr['frm_stand_action_btn']%>">
                                <%if $rl_theme_arr['frm_stand_ctrls_view'] eq 'No'%>
                                    <%assign var='rm_ctrl_directions' value=true%>
                                <%/if%>
                                <%include file="languagelabels_add_buttons.tpl"%>
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
    
    el_form_settings['prime_lang_code'] = '<%$prlang%>';
    el_form_settings["default_lang_code"] = '<%$dflang%>';
    el_form_settings['other_lang_JSON'] = '<%$exlang_arr|@json_encode%>';
    intializeLanguageAutoEntry(el_form_settings["prime_lang_code"], el_form_settings["other_lang_JSON"], el_form_settings["default_lang_code"]);
    
    callSwitchToSelf();
<%/javascript%>
<%$this->js->add_js('admin/admin/js_languagelabels.js')%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%>
<%javascript%>
    Project.modules.languagelabels.callEvents();
<%/javascript%>
