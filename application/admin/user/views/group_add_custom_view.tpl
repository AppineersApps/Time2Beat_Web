<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>    
<%$this->js->add_js('admin/admin/js_admin_group.js')%>
<%if $is_admin_group == true%> 
    <%assign var='disabled' value='disabled=true'%>
<%else%>
    <%assign var='disabled' value=''%>
<%/if%>
<div id="ajax_qLbar"></div>
<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <h3>
            <div class="screen-title">
                <div align="left">
                    <%$this->lang->line('GENERIC_GROUP')%><%if $mode eq 'Update' && $recName neq ''%> :: <%$recName%> <%/if%>
                </div>
            </div>        
        </h3>
        <div class="header-right-btns">
            <div class="frm-back-to">
                <a href="<%$admin_url%>#<%$mod_enc_url['index']%><%$extra_hstr%>"class="backlisting-link" title="<%$this->lang->line('GENERIC_BACK_TO_GROUP_LISTING')%>">
                    <span class="icon16 minia-icon-arrow-left"></span>
                </a>
            </div>
            <%if $mode eq 'Update'%>        
                <%if $next_prev_records['next']['id'] neq ''%>
                <div class="frm-next-rec">
                    <a title="<%$next_prev_records['next']['val']%>" href="<%$admin_url%>#<%$mod_enc_url['add']%>|mode|<%$mod_enc_mode['Update']%>|id|<%$next_prev_records['next']['enc_id']%><%$extra_hstr%>" class='btn next-btn'><%$this->lang->line('GENERIC_NEXT_SHORT')%> <span class='icon12 icomoon-icon-arrow-right'></span></a>            
                </div>
                <%/if%>
                <div class="frm-switch-drop">
                    <%if $switch_combo|is_array && $switch_combo|@count gt 0%>
                        <%$this->dropdown->display('vSwitchPage',"vSwitchPage","style='width:100%;' class='chosen-select' onchange='return loadAdminModuleAddSwitchPage(\"<%$mod_enc_url.add%>\",this.value, \"<%$extra_hstr%>\")'",'','',$enc_id)%>
                    <%/if%>
                </div>
                <%if $next_prev_records['prev']['id'] neq ''%>
                    <div class="frm-prev-rec">
                        <a title="<%$next_prev_records['prev']['val']%>" href="<%$admin_url%>#<%$mod_enc_url['add']%>|mode|<%$mod_enc_mode['Update']%>|id|<%$next_prev_records['prev']['enc_id']%><%$extra_hstr%>" class='btn prev-btn'> <span class='icon12 icomoon-icon-arrow-left'></span> <%$this->lang->line('GENERIC_PREV_SHORT')%></a>            
                    </div>
                <%/if%>
            <%/if%>
        </div>
    </div>
</div>
<div id="ajax_content_div" class="ajax-content-div top-frm-spacing group-form group-add-page" >
    <div id="ajax_qLoverlay"></div>
    <div id="ajax_qLbar"></div>
    <div class="top-frm-tab-layout" id="top_frm_tab_layout"></div>
    <div id="scrollable_content" class="scrollable-content top-block-spacing">
        <div id="group" class="frm-view-block frm-stand-view">
            <div class="main-content-block" id="main_content_block">
                <div style="width:98%;" class="frm-block-layout pad-calc-container">
                    <div class="box gradient <%$rl_theme_arr['frm_gener_content_row']%> <%$rl_theme_arr['frm_gener_border_view']%>">
                        <div class="title <%$rl_theme_arr['frm_gener_titles_bar']%>"><h4><%$this->lang->line('GENERIC_GROUP')%></h4></div>
                        <div class="content <%$rl_theme_arr['frm_gener_label_align']%>">
                            <div class="form-row row-fluid label-lt-fix" id="cc_sh_mgm_group_name">
                                <label class="form-label span3"><%$this->lang->line('GENERIC_GROUP_NAME')%>  <em>*</em> </label> 
                                <div class="form-right-div ">
                                    <strong><%$data['mgm_group_name']%></strong>
                                </div>
                            </div>
                            <div class="form-row row-fluid label-lt-fix" id="cc_sh_mgm_group_code">
                                <label class="form-label span3"><%$this->lang->line('GENERIC_GROUP_CODE')%>  <em>*</em> </label> 
                                <div class="form-right-div ">
                                    <strong><%$data['mgm_group_code']%></strong>
                                </div>                            
                            </div>
                            <div class="form-row row-fluid label-lt-fix" id="cc_sh_mgm_grouping_attr">
                                <label class="form-label span3"><%$this->lang->line('GENERIC_GROUP_LANDING_PAGE')%></label> 
                                <div class="form-right-div ">
                                    <strong><%$data['mgm_grouping_attr']['menuItem']%></strong>
                                </div>
                            </div>
                            <div class="form-row row-fluid label-lt-fix" id="cc_sh_mgm_grouping_func" <%if $data['mgm_grouping_attr']['phpFunc']|trim eq ''%>style="display:none;"<%/if%>>
                                <label class="form-label span3"><%$this->lang->line('GENERIC_GROUP_PHP_FUNCTION')%></label> 
                                <div class="form-right-div ">
                                    <strong><%$data['mgm_grouping_attr']['phpFunc']%></strong> 
                                </div>
                            </div>
                            <div class="form-row row-fluid label-lt-fix" id="cc_sh_mgm_status">
                                <label class="form-label span3"><%$this->lang->line('GENERIC_STATUS')%>  <em>*</em> </label> 
                                <div class="form-right-div ">
                                    <strong><%$data['mgm_status']%></strong> 
                                </div>
                            </div>
                            <div class="form-row row-fluid label-lt-fix">
                                <label class="form-label span3"><%$this->lang->line('GENERIC_SELECT_MODULES')%> <em>*</em> :</label> 
                                <div class="form-right-div">
                                    <%if $is_admin_group eq true%>
                                        <%assign var="selected" value="disabled=true checked=true" %>
                                    <%else%> 
                                        <%assign var="selected" value="disabled=true" %>
                                    <%/if%> 
                                    <%foreach from=$action_arr key=ackey item=acval%>
                                        <div class="margin-equilize">
                                            <input type="checkbox" class="regular-checkbox" name="all_<%$ackey%>" id="all_<%$ackey%>" <%$selected%>/>
                                            <label for="all_<%$ackey%>">&nbsp;</label>
                                        </div>
                                    <%/foreach%>
                                </div>
                            </div>
                            <%if $this->config->item('ENABLE_ROLES_CAPABILITIES')%>
                                <%include file="group_add_custom_advanced.tpl"%>
                            <%else%>
                                <%include file="group_add_custom_basic.tpl"%>
                            <%/if%>
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