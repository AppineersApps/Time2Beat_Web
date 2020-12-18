<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%$this->js->add_js('admin/admin/js_systememails.js')%>
<%assign var="mod_label_text" value=$this->general->getDisplayLabel("Generic",$mode,"label")%>
<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <h3>
            <div class="screen-title">
                <%$this->lang->line('GENERIC_SYSTEM_EMAILS')%><%if $mode eq 'Update' && $recName neq ''%> :: <%$recName%> <%/if%>
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
    <div id="ajax_qLoverlay"></div>
    <div id="ajax_qLbar"></div>
    <div class="top-frm-tab-layout" id="top_frm_tab_layout"></div>
   <div id="scrollable_content" class="scrollable-content top-block-spacing">
        <div id="system_emails" class="frm-view-block frm-stand-view">
            <div class="main-content-block" id="main_content_block">
                <div style="width:98%" class = "frm-block-layout pad-calc-container">
                    <div class="box gradient <%$rl_theme_arr['frm_gener_content_row']%> <%$rl_theme_arr['frm_gener_border_view']%>">
                        <div class="title <%$rl_theme_arr['frm_gener_titles_bar']%>"><h4><%$this->lang->line('GENERIC_SYSTEM_EMAILS')%></h4></div>
                        <div class="content <%$rl_theme_arr['frm_gener_label_align']%>">
                            <div class="form-row row-fluid" id="cc_sh_mse_email_title">
                                <label class="form-label span3"><%$this->lang->line('GENERIC_EMAIL_TITLE')%></label> 
                                <div class="form-right-div  ">
                                    <strong><%$data['mse_email_title']%></strong>
                                </div>
                            </div>
                            <div class="form-row row-fluid" id="cc_sh_mse_email_code">
                                <label class="form-label span3"><%$this->lang->line('GENERIC_EMAIL_CODE')%></label> 
                                <div class="form-right-div  ">
                                    <strong><%$data['mse_email_code']%></strong>
                                </div>
                            </div>
                            <div class="form-row row-fluid" id="cc_sh_mse_email_subject">
                                <label class="form-label span3"><%$this->lang->line('GENERIC_EMAIL_SUBJECT')%></label> 
                                <div class="form-right-div  ">
                                    <strong><%$data['mse_email_subject']%></strong>
                                </div>
                            </div>
                            <div class="form-row row-fluid" id="cc_sh_mse_from_name">
                                <label class="form-label span3"><%$this->lang->line('GENERIC_FROM_NAME')%></label> 
                                <div class="form-right-div  ">
                                    <strong><%$data['mse_from_name']%></strong>
                                </div>
                            </div>
                            <div class="form-row row-fluid" id="cc_sh_mse_from_email">
                                <label class="form-label span3"><%$this->lang->line('GENERIC_FROM_EMAIL')%></label> 
                                <div class="form-right-div  ">
                                    <strong><%$data['mse_from_email']%></strong>
                                </div>
                            </div>
                            <div class="form-row row-fluid" id="cc_sh_mse_bcc_email">
                                <label class="form-label span3"><%$this->lang->line('GENERIC_ADD_BCC')%></label> 
                                <div class="form-right-div  ">
                                    <strong><%if $data['mse_bcc_email'] neq ''%><%$data['mse_bcc_email']%><%else%>---<%/if%></strong>
                                </div>
                            </div>
                            <div class="form-row row-fluid" id="cc_sh_mse_cc_email">
                                <label class="form-label span3"><%$this->lang->line('GENERIC_ADD_CC')%></label> 
                                <div class="form-right-div  ">
                                    <strong><%if $data['mse_cc_email'] neq ''%><%$data['mse_cc_email']%><%else%>---<%/if%></strong>
                                </div>
                            </div>
                            <div class="form-row row-fluid">
                                <div class="box">
                                    <div class="title">
                                        <h4>
                                            <span class="icon12 icomoon-icon-equalizer-2"></span>
                                            <span><%$this->lang->line('GENERIC_VARIABLES')%></span>
                                            <span id="ajax_loader_childModle" style="display:none;margin-left:32%"><i class="fa fa-refresh fa-spin-light fa-2x fa-fw"></i></span>
                                        </h4>
                                        <a style="display: none;" class="minimize" href="javascript://"><%$this->lang->line('GENERIC_MINIMIZE')%></a>
                                    </div>
                                    <div style="display: block;" class="content noPad">
                                        <table id="tbl_child_module" class="responsive table table-bordered">
                                            <thead>    
                                                <tr>
                                                    <th>#</th>
                                                    <th><%$this->lang->line('GENERIC_VARIABLES')%> <em>*</em> </th>
                                                    <th><%$this->lang->line('GENERIC_DESCRIPTION')%></th>
                                            </tr>
                                            </thead>
                                            <tbody id="add_child_module">
                                                <%if $mode eq 'Update'%>
                                                    <%assign var="var_count" value=$db_email_vars|@count%>
                                                <%else%>
                                                    <%assign var="var_count" value=1%>
                                                <%/if%>
                                                <%section name="k" loop=$var_count%>
                                                    <tr id="tr_child_row_<%k+1%>">
                                                        <td class="row-num-child"><%$smarty.section.k.iteration%></td>
                                                        <td><%$db_email_vars[k]['vVarName']%></td>
                                                        <td><%$db_email_vars[k]['vVarDesc']%></td>
                                                    </tr>
                                                <%/section%>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row row-fluid" id="cc_sh_mse_email_message">
                                <div class="form-row row-fluid">
                                    <textarea name="mse_email_message" id="mse_email_message" title="<%$this->lang->line('GENERIC_EMAIL_MESSAGE')%>"  style='width:100%;min-height:300px;'  class='frm-size-medium elastic'  ><%$data['mse_email_message']%></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 