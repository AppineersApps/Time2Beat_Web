<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%assign var="mod_label_text" value=$this->general->getDisplayLabel("Generic",$mode,"label")%>
<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <h3>
            <div class="screen-title">
                <%$this->lang->line($mod_label_text)%> :: <%$this->lang->line('GENERIC_SYSTEM_EMAILS')%>
                <%if $mode eq 'Update' && $recName neq ''%> :: <%$recName%> <%/if%>
            </div>
        </h3>
        <div class="header-right-btns">
            <%if $backlink_allow eq true%>
                <div class="frm-back-to">
                    <a hijacked="yes" href="<%$admin_url%>#<%$mod_enc_url['index']%><%$extra_hstr%>"class="backlisting-link" title="<%$this->general->parseLabelMessage('GENERIC_BACK_TO_SYSTEM_EMAILS_LISTING','#MODULE_HEADING#','SYSTEMEMAILS_SYSTEM_EMAILS')%>">
                        <span class="icon16 minia-icon-arrow-left"></span>
                    </a>
                </div>
            <%/if%>
            <%if $next_link_allow eq true%>
                <div class="frm-next-rec">
                    <a hijacked="yes" title="<%$next_prev_records['next']['val']%>" href="<%$admin_url%>#<%$mod_enc_url['add']%>|mode|<%$mod_enc_mode['Update']%>|id|<%$next_prev_records['next']['enc_id']%><%$extra_hstr%>" class='btn next-btn'><%$this->lang->line('GENERIC_NEXT_SHORT')%> <span class='icon12 icomoon-icon-arrow-right'></span></a>
                </div>
            <%/if%>
            <%if $switchto_allow eq true%>
                <div class="frm-switch-drop">
                    <%if $switch_combo|is_array && $switch_combo|@count gt 0%>
                        <%$this->dropdown->display('vSwitchPage',"vSwitchPage","style='width:100%;' class='chosen-select' onchange='return loadAdminModuleAddSwitchPage(\"<%$mod_enc_url.add%>\",this.value, \"<%$extra_hstr%>\")'",'','',$enc_id)%>
                    <%/if%>
                </div>
            <%/if%>
            <%if $prev_link_allow eq true%>
                <div class="frm-prev-rec">
                    <a hijacked="yes" title="<%$next_prev_records['prev']['val']%>" href="<%$admin_url%>#<%$mod_enc_url['add']%>|mode|<%$mod_enc_mode['Update']%>|id|<%$next_prev_records['prev']['enc_id']%><%$extra_hstr%>" class='btn prev-btn'> <span class='icon12 icomoon-icon-arrow-left'></span> <%$this->lang->line('GENERIC_PREV_SHORT')%></a>
                </div>
            <%/if%>
            <div class="clear"></div>
        </div>
        <span style="display:none;position:inherit;" id="ajax_lang_loader"><i class="fa fa-refresh fa-spin-light fa-2x fa-fw"></i></span>
    </div>
</div>
<div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
    <input type="hidden" id="projmod" name="projmod" value="system_emails">
    <div id="ajax_qLoverlay"></div>
    <div id="ajax_qLbar"></div>
    <div class="top-frm-tab-layout" id="top_frm_tab_layout"></div>
    <div id="scrollable_content" class="scrollable-content top-block-spacing">
        <div id="system_emails" class="frm-elem-block frm-stand-view">
            <form name="frmaddupdate" id="frmaddupdate" action="<%$admin_url%><%$mod_enc_url['add_action']%>?<%$extra_qstr%>" method="post"  enctype="multipart/form-data">
                <input type="hidden" id="id" name="id" value="<%$enc_id%>">
                <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>">
                <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>">
                <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>">
                <input type="hidden" id="extra_hstr" name="extra_hstr" value="<%$extra_hstr%>">
                <%if $mode eq 'Update'%>
                    <input type="hidden" name="mse_status" id="mse_status" value="<%$data['mse_status']%>"  class='ignore-valid' />
                <%else%>
                    <input type="hidden" name="mse_status" id="mse_status" value="Active"  class='ignore-valid' />
                <%/if%>
                <div class="main-content-block" id="main_content_block">
                    <div style="width:98%" class="frm-block-layout pad-calc-container">
                        <div class="box gradient <%$rl_theme_arr['frm_gener_content_row']%> <%$rl_theme_arr['frm_gener_border_view']%>">
                            <div class="title <%$rl_theme_arr['frm_gener_titles_bar']%>"><h4><%$this->lang->line('GENERIC_SYSTEM_EMAILS')%></h4></div>
                            <div class="content <%$rl_theme_arr['frm_gener_label_align']%>">
                                <div class="form-row row-fluid" id="cc_sh_mse_email_title">
                                    <div class="clear prime-lang-block" id="lnpr_mse_email_title_<%$prlang%>">
                                        <label class="form-label span3"><%$this->lang->line('GENERIC_EMAIL_TITLE')%> <em>*</em></label> 
                                        <div class="form-right-div  ">
                                            <input type="text" placeholder="" value="<%$data['mse_email_title']|@htmlentities%>" name="mse_email_title" id="mse_email_title" title="<%$this->lang->line('GENERIC_EMAIL_TITLE')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  aria-lang-parent='mse_email_title'  aria-multi-lingual='parent'  aria-lang-code='<%$prlang%>'  />
                                        </div>
                                        <div class="error-msg-form "><label class='error' id='mse_email_titleErr'></label></div>
                                    </div>
                                    <%if $exlang_arr|@is_array && $exlang_arr|@count gt 0%>
                                        <%section name=ml loop=$exlang_arr%>
                                            <%assign var="exlang" value=$exlang_arr[ml]%>
                                            <div class="clear other-lang-block" id="lnsh_mse_email_title_<%$exlang%>" style="<%if $exlang neq $dflang%>display:none;<%/if%>">
                                                <label class="form-label span3" style="margin-left:0">
                                                    <%$form_config['mse_email_title']['label_lang']%> [<%$lang_info[$exlang]['vLangTitle']%>]
                                                </label> 
                                                <div class="form-right-div">
                                                    <input type="text" placeholder="" value="<%$lang_data[$exlang]['vEmailTitle']|@htmlentities%>" name="langmse_email_title[<%$exlang%>]" id="lang_mse_email_title_<%$exlang%>" title="<%$this->lang->line('SYSTEMEMAILS_EMAIL_TITLE')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  aria-lang-parent='mse_email_title'  aria-multi-lingual='child'  aria-lang-code='<%$exlang%>'  />
                                                </div>
                                            </div>
                                        <%/section%>
                                        <div class="lang-flag-css">
                                            <%$this->general->getAdminLangFlagHTML("mse_email_title", $exlang_arr, $lang_info)%>
                                        </div>
                                    <%/if%>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mse_email_code">
                                    <label class="form-label span3"><%$this->lang->line('GENERIC_EMAIL_CODE')%> <em>*</em></label> 
                                    <div class="form-right-div  ">
                                        <input type="text" placeholder="" value="<%$data['mse_email_code']|@htmlentities%>" name="mse_email_code" id="mse_email_code" title="<%$this->lang->line('GENERIC_EMAIL_CODE')%>" data-ctrl-type='textbox'  class='frm-size-medium'  />
                                        <a class="tipR" style="text-decoration:none;" href="javascript://" title="Email Code should not contain spaces <br>Exapmle: DEMO_EMAIL_CODE">
                                            <span class="icomoon-icon-help"></span>
                                        </a>
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mse_email_codeErr'></label></div>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mse_email_subject">
                                    <div class="clear prime-lang-block" id="lnpr_mse_email_subject_<%$prlang%>">
                                        <label class="form-label span3"><%$this->lang->line('GENERIC_EMAIL_SUBJECT')%> <em>*</em></label> 
                                        <div class="form-right-div  ">
                                            <input type="text" placeholder="" value="<%$data['mse_email_subject']|@htmlentities%>" name="mse_email_subject" id="mse_email_subject" title="<%$this->lang->line('GENERIC_EMAIL_SUBJECT')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  aria-lang-parent='mse_email_subject'  aria-multi-lingual='parent'  aria-lang-code='<%$prlang%>'  />
                                        </div>
                                        <div class="error-msg-form "><label class='error' id='mse_email_subjectErr'></label></div>
                                    </div>
                                    <%if $exlang_arr|@is_array && $exlang_arr|@count gt 0%>
                                        <%section name=ml loop=$exlang_arr%>
                                            <%assign var="exlang" value=$exlang_arr[ml]%>
                                            <div class="clear other-lang-block" id="lnsh_mse_email_subject_<%$exlang%>" style="<%if $exlang neq $dflang%>display:none;<%/if%>">
                                                <label class="form-label span3" style="margin-left:0">
                                                    <%$form_config['mse_email_subject']['label_lang']%> [<%$lang_info[$exlang]['vLangTitle']%>]
                                                </label> 
                                                <div class="form-right-div">
                                                    <input type="text" placeholder="" value="<%$lang_data[$exlang]['vEmailSubject']|@htmlentities%>" name="langmse_email_subject[<%$exlang%>]" id="lang_mse_email_subject_<%$exlang%>" title="<%$this->lang->line('SYSTEMEMAILS_EMAIL_SUBJECT')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  aria-lang-parent='mse_email_subject'  aria-multi-lingual='child'  aria-lang-code='<%$exlang%>'  />
                                                </div>
                                            </div>
                                        <%/section%>
                                        <div class="lang-flag-css">
                                            <%$this->general->getAdminLangFlagHTML("mse_email_subject", $exlang_arr, $lang_info)%>
                                        </div>
                                    <%/if%>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mse_from_name">
                                    <div class="clear prime-lang-block" id="lnpr_mse_from_name_<%$prlang%>">
                                        <label class="form-label span3"><%$this->lang->line('GENERIC_FROM_NAME')%></label> 
                                        <div class="form-right-div  ">
                                            <input type="text" placeholder="" value="<%$data['mse_from_name']|@htmlentities%>" name="mse_from_name" id="mse_from_name" title="<%$this->lang->line('GENERIC_FROM_NAME')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  aria-lang-parent='mse_from_name'  aria-multi-lingual='parent'  aria-lang-code='<%$prlang%>'  />
                                        </div>
                                        <div class="error-msg-form "><label class='error' id='mse_from_nameErr'></label></div>
                                    </div>
                                    <%if $exlang_arr|@is_array && $exlang_arr|@count gt 0%>
                                        <%section name=ml loop=$exlang_arr%>
                                            <%assign var="exlang" value=$exlang_arr[ml]%>
                                            <div class="clear other-lang-block" id="lnsh_mse_from_name_<%$exlang%>" style="<%if $exlang neq $dflang%>display:none;<%/if%>">
                                                <label class="form-label span3" style="margin-left:0">
                                                    <%$form_config['mse_from_name']['label_lang']%> [<%$lang_info[$exlang]['vLangTitle']%>]
                                                </label> 
                                                <div class="form-right-div">
                                                    <input type="text" placeholder="" value="<%$lang_data[$exlang]['vFromName']|@htmlentities%>" name="langmse_from_name[<%$exlang%>]" id="lang_mse_from_name_<%$exlang%>" title="<%$this->lang->line('SYSTEMEMAILS_FROM_NAME')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  aria-lang-parent='mse_from_name'  aria-multi-lingual='child'  aria-lang-code='<%$exlang%>'  />
                                                </div>
                                            </div>
                                        <%/section%>
                                        <div class="lang-flag-css">
                                            <%$this->general->getAdminLangFlagHTML("mse_from_name", $exlang_arr, $lang_info)%>
                                        </div>
                                    <%/if%>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mse_from_email">
                                    <label class="form-label span3"><%$this->lang->line('GENERIC_FROM_EMAIL')%></label> 
                                    <div class="form-right-div  ">
                                        <input type="text" placeholder="" value="<%$data['mse_from_email']|@htmlentities%>" name="mse_from_email" id="mse_from_email" title="<%$this->lang->line('GENERIC_FROM_EMAIL')%>"  data-ctrl-type='textbox' class='frm-size-medium'  />
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mse_from_emailErr'></label></div>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mse_bcc_email">
                                    <label class="form-label span3"><%$this->lang->line('GENERIC_ADD_BCC')%></label> 
                                    <div class="form-right-div  ">
                                        <input type="text" placeholder="" value="<%$data['mse_bcc_email']|@htmlentities%>" name="mse_bcc_email" id="mse_bcc_email" title="<%$this->lang->line('GENERIC_ADD_BCC')%>" data-ctrl-type='textbox'  class='frm-size-medium'  />
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mse_bcc_emailErr'></label></div>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mse_cc_email">
                                    <label class="form-label span3"><%$this->lang->line('GENERIC_ADD_CC')%></label> 
                                    <div class="form-right-div  ">
                                        <input type="text" placeholder="" value="<%$data['mse_cc_email']|@htmlentities%>" name="mse_cc_email" id="mse_cc_email" title="<%$this->lang->line('GENERIC_ADD_CC')%>" data-ctrl-type='textbox' class='frm-size-medium'  />
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mse_cc_emailErr'></label></div>
                                </div>
                                <div class="form-row row-fluid">
                                    <div class="box form-child-table" style="margin-bottom:0;">
                                        <div class="title">
                                            <h4>
                                                <span class="icon12 icomoon-icon-equalizer-2"></span>
                                                <span><%$this->lang->line('GENERIC_VARIABLES')%></span>
                                                <span id="ajax_loader_childModle" style="display:none;margin-left:32%"><i class="fa fa-refresh fa-spin-light fa-2x fa-fw"></i></span>
                                                <div class="box-addmore right">
                                                    <a onclick="Project.modules.systememails.getSystemEmailVariableTable()" href="javascript://" class="btn btn-success">
                                                        <span class="icon14 icomoon-icon-plus-2"></span>
                                                        <%$this->lang->line('GENERIC_ADD_NEW')%>
                                                    </a>
                                                </div>
                                            </h4>
                                            <a style="display: none;" class="minimize" href="javascript://"><%$this->lang->line('GENERIC_MINIMIZE')%></a>
                                        </div>
                                        <div class="content noPad system-email-vars">
                                            <table id="tbl_child_module" class="responsive table table-bordered">
                                                <thead>    
                                                    <tr>
                                                        <th width='3%'>#</th>
                                                        <th width='40%'><%$this->lang->line('GENERIC_VARIABLES')%> <em>*</em> 
                                                            <a class="tipR" style="text-decoration:none;" href="javascript://" title="Example : #COMPANY_NAME#">
                                                                <span class="icomoon-icon-help"></span>
                                                            </a>
                                                        </th>
                                                        <th width='40%'><%$this->lang->line('GENERIC_DESCRIPTION')%></th>
                                                        <th width='17%'><div align="center"><%$this->lang->line('GENERIC_ACTIONS')%></div></th>
                                                </tr>
                                                </thead>
                                            </table>
                                            <table width="100%" border="0" cellpadding='0' cellspacing="0">
                                                <tr>
                                                    <td id='mails_fields_list'>
                                                        <%if $mode eq 'Update'%>
                                                            <%assign var="var_count" value=$db_email_vars|@count%>
                                                        <%else%>
                                                            <%assign var="var_count" value=1%>
                                                        <%/if%>
                                                        <%section name="k" loop=$var_count%>
                                                            <%assign var="i" value=$smarty.section.k.index%>
                                                            <table width='100%' cellspacing='0' cellpadding='0' border="0" class="responsive table table-bordered field-sortable">
                                                                <tr id="tr_child_row_<%$i%>">
                                                                    <td class="row-num-child" width='3%'><%$smarty.section.k.iteration%></td>
                                                                    <td width='40%'>
                                                                        <div class="">
                                                                            <input type="hidden" name="iEmailVariableId[]" value="<%$db_email_vars[$i]['iEmailVariableId']%>">
                                                                            <input type="text" class="frm-size-large valid-variable" title="<%$this->lang->line('GENERIC_VARIABLES')%>" id="system_email_variable_<%$i%>" name="system_email_variable[]" value="<%$db_email_vars[$i]['vVarName']%>">
                                                                        </div>
                                                                        <div>
                                                                            <label id="system_email_variable_<%$i%>Err" class="error"></label>
                                                                        </div>
                                                                    </td>
                                                                    <td width='40%'>
                                                                        <div class="">
                                                                            <input type="text" class="frm-size-large" title="<%$this->lang->line('GENERIC_DESCRIPTION')%>" id="system_email_description_<%$i%>" name="system_email_description[]" value="<%$db_email_vars[$i]['vVarDesc']%>">
                                                                        </div>
                                                                        <div>
                                                                            <label id="system_email_description_<%$i%>Err" class="error"></label>
                                                                        </div>
                                                                    </td>
                                                                    <td align="center" width='17%'>
                                                                        <div class="controls center">
                                                                            <a href="javascript://" title="Sort Fields" class="field-handle">
                                                                                <span class="icon13 icomoon-icon-move"></span>
                                                                            </a>
                                                                            &nbsp;
                                                                            <a class="tipR" href="javascript://" onclick="Project.modules.systememails.deleteSystemEmailVariableRow('<%$i%>')" title="<%$this->lang->line('GENERIC_DELETE')%>">
                                                                                <span class="icon12 icomoon-icon-remove"></span>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        <%/section%>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row row-fluid double-row-view" id="cc_sh_mse_email_message" style="margin-top:0; padding-top: 0;">
                                    <div class="clear prime-lang-block" id="lnpr_mse_email_message_<%$prlang%>">
                                        <label class="form-label span3"><%$form_config['mse_email_message']['label_lang']%></label>
                                        <div class="form-right-div">
                                            <textarea name="mse_email_message" id="mse_email_message" title="<%$this->lang->line('GENERIC_EMAIL_MESSAGE')%>"  class='frm-size-medium elastic' aria-lang-parent='mse_email_message'  aria-multi-lingual='parent'  aria-lang-code='<%$prlang%>'  ><%$data['mse_email_message']%></textarea>
                                        </div>
                                        <div class="error-msg-form "><label class='error' id='mse_email_messageErr'></label></div>
                                    </div>
                                    <%if $exlang_arr|@is_array && $exlang_arr|@count gt 0%>
                                        <%section name=ml loop=$exlang_arr%>
                                            <%assign var="exlang" value=$exlang_arr[ml]%>
                                            <div class="clear other-lang-block" id="lnsh_mse_email_message_<%$exlang%>" style="<%if $exlang neq $dflang%>display:none;<%/if%>">
                                                <label class="form-label span3" style="margin-left:0; margin-top: 5px;">
                                                    <%$form_config['mse_email_message']['label_lang']%> [<%$lang_info[$exlang]['vLangTitle']%>]
                                                </label> 
                                                <div class="form-right-div">
                                                    <textarea name="langmse_email_message[<%$exlang%>]" id="lang_mse_email_message_<%$exlang%>" title="<%$this->lang->line('SYSTEMEMAILS_EMAIL_MESSAGE')%>"  class='frm-size-medium frm-editor-small'  aria-lang-parent='mse_email_message'  aria-multi-lingual='child'  aria-lang-code='<%$exlang%>'  ><%$lang_data[$exlang]['tEmailMessage']%></textarea>
                                                </div>
                                            </div>
                                        <%/section%>
                                        <div class="lang-flag-css">
                                            <%$this->general->getAdminLangFlagHTML("mse_email_message", $exlang_arr, $lang_info)%>
                                        </div>
                                    <%/if%>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="frm-bot-btn <%$rl_theme_arr['frm_gener_action_bar']%> <%$rl_theme_arr['frm_gener_action_btn']%>">
                        <%if $rl_theme_arr['frm_gener_ctrls_view'] eq 'No'%>
                            <%assign var='rm_ctrl_directions' value=true%>
                        <%/if%>
                        <%include file="admin_form_direction.tpl"%>
                        <%include file="admin_form_control.tpl"%>
                    </div>
                </div>
                <div class="clear"></div>
            </form>
        </div>
    </div>
</div>
<style>
    body{overflow:visible!important}
</style>
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
    
        el_form_settings["prime_lang_code"] = '<%$prlang%>';
        el_form_settings["default_lang_code"] = '<%$dflang%>';
        el_form_settings["other_lang_JSON"] = '<%$exlang_arr|@json_encode%>';
        intializeLanguageAutoEntry(el_form_settings["prime_lang_code"], el_form_settings["other_lang_JSON"], el_form_settings["default_lang_code"]);
    callSwitchToSelf();
    var inc_no = dis_no = '<%$var_count + 1%>';
<%/javascript%>
<%$this->css->add_css('forms/tinymce.mention.css')%>
<%$this->js->add_js('admin/forms/tinymce/tinymce.min.js', 'admin/admin/js_systememails.js')%>
<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
