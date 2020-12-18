<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%javascript%>
    $.jgrid.no_legacy_api = true, $.jgrid.useJSON = true;
    var el_grid_settings = {}, js_col_name_json = {}, js_col_model_json = {}, js_data_json = {};
    
    el_grid_settings['delete_url'] = admin_url+'<%$mod_enc_url['backup_backup_delete']%>?type=<%$extra_hstr%>';
    el_grid_settings['save_url'] =  admin_url+'<%$mod_enc_url['backup_backup_form_a']%>?type=<%$extra_hstr%>';

    el_grid_settings['permit_del_btn'] = '<%$del_access%>';
    el_grid_settings['permit_view_btn'] = '<%$view_access%>';
    el_grid_settings['permit_add_btn'] = '<%$add_access%>';

    el_grid_settings['enc_location'] = '<%$enc_loc_module%>';
    el_grid_settings['default_sort'] = 'table_name';
    el_grid_settings['sort_order'] = 'desc';
    
    js_col_name_json = [{
        "label": "Table Name",
        "name": "table_name"
    }];
    
    var js_col_model_json = [{
        "name": "table_name",
        "index": "table_name",
        "label": "Table Name",
        "labelClass": "header-align-left",
        "align": "left",
        "resizable": true,
        "width": null,
        "sortable": true,
        "search": true,
        "searchoptions": {
            "sopt": ['eq', 'ne', 'bw', 'bn', 'ew', 'en', 'cn', 'nc', 'nu', 'nn']
	},
        "filterSopt":"bw"
    }];
    
    <%if $tableBackupJSON%>
        js_data_json = <%$tableBackupJSON%>;
    <%/if%>;
<%/javascript%>
<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <h3>
            <div class="screen-title">
                <%$this->lang->line('GENERIC_LISTING')%> :: <%$this->lang->line('GENERIC_TABLE_BACKUP')%>
            </div>
        </h3>
        <div class="header-right-drops"></div>
    </div>
</div>
<div id="ajax_content_div" class="ajax-content-div top-frm-tab-spacing box gradient">
    <div id="ajax_qLoverlay"></div>
    <div id="ajax_qLbar"></div>
    <div id="scrollable_content" class="scrollable-content top-list-spacing">
        <div class="grid-data-container pad-calc-container">
            <div class="top-list-tab-layout" id="top_list_grid_layout">
                <%include file="backup_tabs.tpl" %>
            </div>
            <div id="backup">
                <form name="frmbackupsave" id="frmbackupsave" method="post" action="<%$admin_url%><%$mod_enc_url['backup_backup_form_a']%>?<%$extra_hstr%>" style="margin:0px!important">
                    <input type="hidden" name="btype" id="btype" value=""/>
                    <input type='hidden' name='id_arr' id='id_arr' value=""/>
                    <input type="hidden" name="type" id="type" value="<%$extra_hstr%>"/>
                </form>
                <table class="grid-table-view top-list-pager-space" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td id="grid_data_col" class="<%$rl_theme_arr['grid_search_toolbar']%>">
                            <div id="pager2"></div>
                            <table id="list2"></table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="selAllRows" value="" id="selAllRows">
<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%javascript%>
    Project.modules.backup.showTableBackupListing();
<%/javascript%>
