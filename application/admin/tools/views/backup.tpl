<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class='custom-backup-container'>
    <%include file="backup_strip.tpl"%>
    <div id="ajax_content_div" class="ajax-content-div top-frm-tab-spacing box gradient">
        <div id="ajax_qLoverlay"></div>
        <div id="ajax_qLbar"></div>
        <div id="scrollable_content" class="scrollable-content top-list-spacing">

            <div class="grid-data-container pad-calc-container">
                <div class="top-list-tab-layout" id="top_list_grid_layout">
                    <%include file="backup_tabs.tpl" %>
                </div>
                <div id="backup">
                    <form name="frmbackupsave" id="frmbackupsave" method="post" action="<%$admin_url%><%$mod_enc_url['backup_backup_form_a']%>"  style="margin:0px!important">
                        <input type="hidden" name="mode" id="mode" value="<%$this->input->get_post('mode')%>"/>
                        <input type="hidden" name="btype" id="btype" value="full"/>
                        <input type="hidden" name="type" id="type" value="<%$extra_hstr%>"/>
                    </form>
                    <form name="frmbackupdwnd" id="frmbackupdwnd" method="post" action="<%$admin_url%><%$mod_enc_url['backup_backup_download_a']%>"  style="margin:0px!important">
                        <input type="hidden" name="fname" id="fname" value=""/>
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
</div>
<%javascript%>
    $.jgrid.no_legacy_api = true, $.jgrid.useJSON = true;
    var el_grid_settings = {}, js_col_name_json = {}, js_col_model_json = {}, js_data_json = {};
    
    el_grid_settings['delete_url'] = admin_url+'<%$mod_enc_url['backup_backup_delete']%>?type=<%$extra_hstr%>';
    el_grid_settings['save_url'] =  admin_url+'<%$mod_enc_url['backup_backup_form_a']%>?type=<%$extra_hstr%>';
    
    el_grid_settings['permit_del_btn'] = '<%$del_access%>';
    el_grid_settings['permit_view_btn'] = '<%$view_access%>';
    el_grid_settings['permit_add_btn'] = '<%$add_access%>';
    
    el_grid_settings['enc_location'] = '<%$enc_loc_module%>';
    el_grid_settings['default_sort'] = 'month';
    el_grid_settings['sort_order'] = 'desc';
    
    var js_col_name_json = [{
        "label": "DataBase File",
        "name": "data_base_file"
    },
    {
        "label": "Created Date",
        "name": "created_date",
    },
    {
        "label": "Download",
        "name": "download"
    },
    {
        "label": "Data Size",
        "name": "data_size"
    },
    {
        "label": "Month",
        "name": "month"
    }];

    js_col_model_json = [{
        "name": "data_base_file",
        "index": "data_base_file",
        "label": "DataBase File",
        "labelClass": "header-align-left",
        "align": "left",
        "resizable": true,
        "width": null,
        "sortable": true,
        "summaryType": "count",
        "summaryTpl": "<b>{0}Item(s)</b>"
    },
    {
        "name": "created_date",
        "index": "created_date",
        "label": "Created Date",
        "labelClass": "header-align-center",
        "align": "center",
        "resizable": true,
        "width": null,
        "sortable": true
    },
    {
        "name": "download",
        "index": "download",
        "label": "Download",
        "labelClass": "header-align-center",
        "align": "center",
        "resizable": true,
        "width": null,
        "sortable": false,
        "search": false,
        "formatter": formatBackupDownloadLink,
        "summaryType": "sum",
        "summaryTpl": "Total"
    },
    {
        "name": "data_size",
        "index": "data_size",
        "label": "Data Size",
        "labelClass": "header-align-center",
        "align": "center",
        "resizable": true,
        "width": null,
        "sortable": true,
        "formatter": formatBackupFileSize,
        "summaryType": "sum",
        "summaryTpl": "<b>{0}</b>"
    },
    {
        "name": "month",
        "index": "month",
        "label": "Month",
        "labelClass": "header-align-center",
        "align": "center",
        "resizable": true,
        "width": null,
        "sortable": true,
        "sorttype": "date",
        "datefmt": "F, Y"
    }];
    
    <%if $finalBackupJSON%>
        js_data_json = <%$finalBackupJSON%>;
    <%/if%>

<%/javascript%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%javascript%>
    Project.modules.backup.showFullBackupListing();
<%/javascript%>