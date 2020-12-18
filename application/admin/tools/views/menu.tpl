<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div>
    <div class="headingfix">
        <div class="heading" id="top_heading_fix" style="width: 1351px;">
            <h3 style="padding-left: 221px; width: 1077.71px;">
                <div class="screen-title">
                    <%$this->lang->line('GENERIC_MENU')%>
                </div>
            </h3>
            <div class="header-right-drops"></div>
        </div>
    </div>
    <div id="ajax_content_div" class="ajax-content-div top-frm-spacing">
        <div id="ajax_qLoverlay"></div>
        <div id="ajax_qLbar"></div>
        <div id="scrollable_content" class="scrollable-content top-block-spacing">
            <div id="bulkmail" class="frm-elem-block frm-stand-view">
                <div class="main-content-block" id="main_content_block">
                    <div style="width:98%" class="frm-block-layout pad-calc-container">
                        <div class="box gradient single-row-view ">
                            <div class="title frm-title-none"></div>
                            <div class="content label-lt-align menu-content">
                                <table class='responsive table table-bordered menu-table'>
                                    <thead>
                                        <tr>
                                            <th width="40%">
                                                <div align="left" class="global-extended-col-name"><%$this->lang->line('GENERIC_MENU_TITLE')%></div>
                                            </th>
                                            <th width="40%">
                                                <div align="left"><%$this->lang->line('GENERIC_MENU_CAPABILITY')%></div>
                                            </th>
                                            <th width="20%">
                                                <div align="left"><%$this->lang->line('GENERIC_MENU_STATUS')%></div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <%foreach from=$menu_data item=data%>
                                        <tr class="menu-parent-item">
                                            <td width="40%" class="menu-parent-title" colspan="2">
                                                <a href="javascript://" class="<%if $edit_title eq true%>editable editable-click edit-menu-title<%/if%>" hijacked="yes" 
                                                   data-value="<%$data['parent_menu_display']%>" data-id="<%$this->general->getAdminEncodeURL($data['parent_admin_menu_id'])%>">
                                                    <%$data['parent_menu_display']%>
                                                </a>
                                            </td>
                                            <%*
                                            <td width="40%">
                                                <%$data['parent_capability_code']%>
                                            </td>
                                            *%>
                                            <td width="20%">
                                                <a href="javascript://" class="<%if $edit_status eq true%>editable editable-click edit-menu-status<%/if%>" hijacked="yes" 
                                                    data-value="<%$data['parent_status']%>" data-id="<%$this->general->getAdminEncodeURL($data['parent_admin_menu_id'])%>">
                                                    <%$data['parent_status']%>
                                                </a>
                                            </td>
                                        </tr>
                                        <%foreach from=$data['children_arr'] item=$child%>
                                        <tr class="menu-child-item">
                                            <td width="40%" class='menu-child-title'>
                                                <a href="javascript://" class="<%if $edit_title eq true%>editable editable-click edit-menu-title<%/if%>" hijacked="yes" 
                                                   data-value="<%$child['child_menu_display']%>" data-id="<%$this->general->getAdminEncodeURL($child['child_admin_menu_id'])%>">
                                                    <%$child['child_menu_display']%>
                                                </a>
                                            </td>
                                            <td width="40%">
                                                <a href="javascript://" class="<%if $edit_capability eq true && $child['child_capability_added_by'] neq 'System'%>editable editable-click edit-menu-capability<%/if%>" hijacked="yes" 
                                                   data-value="<%$child['child_capability_code']%>" data-id="<%$this->general->getAdminEncodeURL($child['child_admin_menu_id'])%>" >
                                                    <%$child['child_capability_code']%>
                                                </a>
                                            </td>
                                            <td width="20%">
                                                <a href="javascript://" class="<%if $edit_status eq true%>editable editable-click edit-menu-status<%/if%>" hijacked="yes" 
                                                   data-value="<%$child['child_status']%>" data-id="<%$this->general->getAdminEncodeURL($child['child_admin_menu_id'])%>">
                                                    <%$child['child_status']%>
                                                </a>
                                            </td>
                                        </tr>
                                        <%/foreach%>
                                    <%/foreach%>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
<%javascript%>
    $.fn.editable.defaults.mode = 'inline', $.fn.editable.defaults.clear = false;
    var el_form_settings = {};
    el_form_settings['edit_title'] = '<%$edit_title%>';
    el_form_settings['edit_capability'] = '<%$edit_capability%>';
    el_form_settings['edit_status'] = '<%$edit_status%>';
    el_form_settings['status_list_url'] = admin_url+'<%$mod_enc_url['menu_status_list']%>';
    el_form_settings['capability_list_url'] = admin_url+'<%$mod_enc_url['menu_capability_list']%>';
    el_form_settings['save_menu_data_url'] = admin_url+'<%$mod_enc_url['menu_save_menu_data']%>';
<%/javascript%>

<%$this->js->add_js('admin/admin/js_admin_menu.js')%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%>
