<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-list-container">
    <%include file="db_change_log_index_strip.tpl"%>
    <%$this->js->add_js("admin/mustache/mustache.min.js")%>
    <!-- Grid View HTML Block -->
    <div id="layout_grid_list2" class="perm-elem-hide">                        
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-timeline">
                        <repeat>
                            <div class="timeline">
                                {{#extra_fields}}
                                <div class="timeline-content log-{{class}}">
                                    <div class="timeline-icon">
                                        <i class="{{icon}}"></i>
                                    </div>
                                    <div class="inner-content">
                                        <h3 class="title">{%mdc_date_added%}</h3>
                                        <div class="pk">#{%mdc_primary_key%}</div>
                                        <p class="description">
                                            {{description}} -- {%mdc_field_data%}
                                        </p>
                                    </div>
                                </div>
                                {{/extra_fields}}
                            </div>
                        </repeat>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="<%$module_name%>" data-list-name="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing box gradient">
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Middle Content -->
            <div id="scrollable_content" class="scrollable-content top-list-spacing">
                <div class="grid-data-container pad-calc-container">
                    <div class="top-list-tab-layout" id="top_list_grid_layout">
                    </div>
                    <table class="grid-table-view " width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <!-- Module Listing Block -->
                            <td id="grid_data_col" class="<%$rl_theme_arr['grid_search_toolbar']%>">
                                <div id="pager2"></div>
                                <table id="list2"></table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <input type="hidden" name="selAllRows" value="" id="selAllRows" />
    </div>
</div>
<!-- Module Listing Javascript -->
<%javascript%>
    $.jgrid.no_legacy_api = true; $.jgrid.useJSON = true;
    var el_grid_settings = {}, js_col_model_json = {}, js_col_name_json = {}; 
                    
    el_grid_settings['module_name'] = '<%$module_name%>';
    el_grid_settings['extra_hstr'] = '<%$extra_hstr%>';
    el_grid_settings['extra_qstr'] = '<%$extra_qstr%>';
    el_grid_settings['enc_location'] = '<%$enc_loc_module%>';
    el_grid_settings['par_module '] = '<%$this->general->getAdminEncodeURL($parMod)%>';
    el_grid_settings['par_data'] = '<%$this->general->getAdminEncodeURL($parID)%>';
    el_grid_settings['par_field'] = '<%$parField%>';
    el_grid_settings['par_type'] = 'parent';

    el_grid_settings['index_page_url'] = '<%$mod_enc_url["index"]%>';
    el_grid_settings['add_page_url'] = '<%$mod_enc_url["add"]%>'; 
    el_grid_settings['edit_page_url'] =  admin_url+'<%$mod_enc_url["inline_edit_action"]%>?<%$extra_qstr%>';
    el_grid_settings['listing_url'] = admin_url+'<%$mod_enc_url["listing"]%>?<%$extra_qstr%>';
    el_grid_settings['export_url'] =  admin_url+'<%$mod_enc_url["export"]%>?<%$extra_qstr%>';
    el_grid_settings['print_url'] =  admin_url+'<%$mod_enc_url["print_listing"]%>?<%$extra_qstr%>';
        
    el_grid_settings['search_refresh_url'] = admin_url+'<%$mod_enc_url["get_left_search_content"]%>?<%$extra_qstr%>';
    el_grid_settings['search_autocomp_url'] = admin_url+'<%$mod_enc_url["get_search_auto_complete"]%>?<%$extra_qstr%>';
    el_grid_settings['ajax_data_url'] = admin_url+'<%$mod_enc_url["get_chosen_auto_complete"]%>?<%$extra_qstr%>';
    el_grid_settings['auto_complete_url'] = admin_url+'<%$mod_enc_url["get_token_auto_complete"]%>?<%$extra_qstr%>';
    el_grid_settings['subgrid_listing_url'] =  admin_url+'<%$mod_enc_url["get_subgrid_block"]%>?<%$extra_qstr%>';
    el_grid_settings['jparent_switchto_url'] = admin_url+'<%$parent_switch_cit["url"]%>?<%$extra_qstr%>';
    
    el_grid_settings['admin_rec_arr'] = $.parseJSON('<%$hide_admin_rec|@json_encode%>');;
    el_grid_settings['status_arr'] = $.parseJSON('<%$status_array|@json_encode%>');
    el_grid_settings['status_lang_arr'] = $.parseJSON('<%$status_label|@json_encode%>');
                
    el_grid_settings['hide_add_btn'] = '';
    el_grid_settings['hide_del_btn'] = '';
    el_grid_settings['hide_status_btn'] = '';
    el_grid_settings['hide_export_btn'] = '1';
    el_grid_settings['hide_columns_btn'] = 'No';
    
    el_grid_settings['show_saved_search'] = 'No';
    el_grid_settings['hide_advance_search'] = 'No';
    el_grid_settings['hide_search_tool'] = 'No';
    el_grid_settings['hide_multi_select'] = 'No';
    el_grid_settings['hide_paging_btn'] = 'No';
    el_grid_settings['hide_refresh_btn'] = 'No';
    
    el_grid_settings['popup_add_form'] = 'No';
    el_grid_settings['popup_edit_form'] = 'No';
    el_grid_settings['popup_add_size'] = ['75%', '75%'];
    el_grid_settings['popup_edit_size'] = ['75%', '75%'];
    
    el_grid_settings['permit_add_btn'] = '<%$add_access%>';
    el_grid_settings['permit_del_btn'] = '<%$del_access%>';
    el_grid_settings['permit_edit_btn'] = '<%$edit_access%>';
    el_grid_settings['permit_view_btn'] = '<%$view_access%>';
    el_grid_settings['permit_expo_btn'] = '<%$expo_access%>';
    el_grid_settings['permit_print_btn'] = '<%$print_access%>';
        
    el_grid_settings['group_search'] = '';
    el_grid_settings['default_sort'] = 'mdc_date_added';
    el_grid_settings['sort_order'] = 'desc';
    el_grid_settings['footer_row'] = 'No';
    el_grid_settings['grouping'] = 'No';
    el_grid_settings['group_attr'] = {};
    
    el_grid_settings['inline_add'] = 'No';
    el_grid_settings['rec_position'] = 'Top';
    el_grid_settings['auto_width'] = 'Yes';
    el_grid_settings['auto_refresh'] = 'No';
    el_grid_settings['lazy_loading'] = 'No';
    el_grid_settings['print_rec'] = 'No';
    el_grid_settings['print_list'] = 'No';
    
    el_grid_settings['subgrid'] = 'No';
    el_grid_settings['colgrid'] = 'No';
    el_grid_settings['listview'] = 'grid';
    el_grid_settings['rating_allow'] = 'No';
    el_grid_settings['global_filter'] = 'No';
    
    el_grid_settings['search_slug'] = '<%$search_slug%>';
    el_grid_settings['search_list'] = $.parseJSON('<%$search_preferences|@json_encode%>');
    el_grid_settings['filters_arr'] = $.parseJSON('<%$default_filters|@json_encode%>');
    el_grid_settings['top_filter'] = [];
    el_grid_settings['buttons_arr'] = [];
    el_grid_settings['callbacks'] = [];
    el_grid_settings['message_arr'] = {
        "delete_alert" : "<%$this->general->processMessageLabel('ACTION_PLEASE_SELECT_ANY_RECORD')%>",
        "delete_popup" : "<%$this->general->processMessageLabel('ACTION_ARE_YOU_SURE_WANT_TO_DELETE_THIS_RECORD_C63')%>",
        "status_alert" : "<%$this->general->processMessageLabel('ACTION_PLEASE_SELECT_ANY_RECORD_TO__C35STATUS_C35')%>",
        "status_popup" : "<%$this->general->processMessageLabel('ACTION_ARE_YOU_SURE_WANT_TO__C35STATUS_C35_THIS_RECORDS_C63')%>",
    };
    
    js_col_name_json = [{
        "name": "mdc_table_name",
        "label": "<%$list_config['mdc_table_name']['label_lang']%>"
    },
    {
        "name": "mdc_operation",
        "label": "<%$list_config['mdc_operation']['label_lang']%>"
    },
    {
        "name": "mdc_primary_key",
        "label": "<%$list_config['mdc_primary_key']['label_lang']%>"
    },
    {
        "name": "mdc_field_data",
        "label": "<%$list_config['mdc_field_data']['label_lang']%>"
    },
    {
        "name": "mdc_source",
        "label": "<%$list_config['mdc_source']['label_lang']%>"
    },
    {
        "name": "mdc_entity_name",
        "label": "<%$list_config['mdc_entity_name']['label_lang']%>"
    },
    {
        "name": "mdc_logged_name",
        "label": "<%$list_config['mdc_logged_name']['label_lang']%>"
    },
    {
        "name": "mdc_date_added",
        "label": "<%$list_config['mdc_date_added']['label_lang']%>"
    },
    {
        "name": "extra_fields",
        "label": "<%$list_config['extra_fields']['label_lang']%>"
    }];
    
    js_col_model_json = [{
        "name": "mdc_table_name",
        "index": "mdc_table_name",
        "label": "<%$list_config['mdc_table_name']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['mdc_table_name']['width']%>",
        "search": <%if $list_config['mdc_table_name']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['mdc_table_name']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mdc_table_name']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mdc_table_name']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['mdc_table_name']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mdc_table_name']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mdc_table_name']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "db_change_log",
                "aria-unique-name": "mdc_table_name",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['mdc_table_name']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "db_change_log",
            "aria-unique-name": "mdc_table_name",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['mdc_table_name']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "mdc_operation",
        "index": "mdc_operation",
        "label": "<%$list_config['mdc_operation']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['mdc_operation']['width']%>",
        "search": <%if $list_config['mdc_operation']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['mdc_operation']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mdc_operation']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mdc_operation']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['mdc_operation']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mdc_operation']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mdc_operation']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "select",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "db_change_log",
                "aria-unique-name": "mdc_operation",
                "autocomplete": "off",
                "data-placeholder": " ",
                "class": "search-chosen-select",
                "multiple": "multiple"
            },
            "sopt": intSearchOpts,
            "searchhidden": <%if $list_config['mdc_operation']['search'] eq 'Yes' %>true<%else%>false<%/if%>,
            "dataUrl": <%if $count_arr["mdc_operation"]["json"] eq "Yes" %>false<%else%>'<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=mdc_operation&mode=<%$mod_enc_mode["Search"]%>&rformat=html<%$extra_qstr%>'<%/if%>,
            "value": <%if $count_arr["mdc_operation"]["json"] eq "Yes" %>$.parseJSON('<%$count_arr["mdc_operation"]["data"]|@addslashes%>')<%else%>null<%/if%>,
            "dataInit": <%if $count_arr['mdc_operation']['ajax'] eq 'Yes' %>initSearchGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["mdc_operation"]["ajax"] eq "Yes" %>ajax-call<%/if%>',
            "multiple": true
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "db_change_log",
            "aria-unique-name": "mdc_operation",
            "dataUrl": '<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=mdc_operation&mode=<%$mod_enc_mode["Update"]%>&rformat=html<%$extra_qstr%>',
            "dataInit": <%if $count_arr['mdc_operation']['ajax'] eq 'Yes' %>initEditGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["mdc_operation"] eq "Yes" %>ajax-call<%/if%>',
            "data-placeholder": "<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'DB_CHANGE_LOG_OPERATION')%>",
            "class": "inline-edit-row chosen-select"
        },
        "ctrl_type": "dropdown",
        "default_value": "<%$list_config['mdc_operation']['default']%>",
        "filterSopt": "in",
        "stype": "select"
    },
    {
        "name": "mdc_primary_key",
        "index": "mdc_primary_key",
        "label": "<%$list_config['mdc_primary_key']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['mdc_primary_key']['width']%>",
        "search": <%if $list_config['mdc_primary_key']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['mdc_primary_key']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mdc_primary_key']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mdc_primary_key']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['mdc_primary_key']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mdc_primary_key']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mdc_primary_key']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "db_change_log",
                "aria-unique-name": "mdc_primary_key",
                "autocomplete": "off"
            },
            "sopt": numSearchOpts,
            "searchhidden": <%if $list_config['mdc_primary_key']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "db_change_log",
            "aria-unique-name": "mdc_primary_key",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['mdc_primary_key']['default']%>",
        "filterSopt": "eq"
    },
    {
        "name": "mdc_field_data",
        "index": "mdc_field_data",
        "label": "<%$list_config['mdc_field_data']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['mdc_field_data']['width']%>",
        "search": <%if $list_config['mdc_field_data']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['mdc_field_data']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mdc_field_data']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mdc_field_data']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['mdc_field_data']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mdc_field_data']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mdc_field_data']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "textarea",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "db_change_log",
                "aria-unique-name": "mdc_field_data",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['mdc_field_data']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "db_change_log",
            "aria-unique-name": "mdc_field_data",
            "rows": "1",
            "placeholder": "",
            "dataInit": initEditGridElasticEvent,
            "class": "inline-edit-row inline-textarea-edit "
        },
        "ctrl_type": "textarea",
        "default_value": "<%$list_config['mdc_field_data']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "mdc_source",
        "index": "mdc_source",
        "label": "<%$list_config['mdc_source']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['mdc_source']['width']%>",
        "search": <%if $list_config['mdc_source']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['mdc_source']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mdc_source']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mdc_source']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['mdc_source']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mdc_source']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mdc_source']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "select",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "db_change_log",
                "aria-unique-name": "mdc_source",
                "autocomplete": "off",
                "data-placeholder": " ",
                "class": "search-chosen-select",
                "multiple": "multiple"
            },
            "sopt": intSearchOpts,
            "searchhidden": <%if $list_config['mdc_source']['search'] eq 'Yes' %>true<%else%>false<%/if%>,
            "dataUrl": <%if $count_arr["mdc_source"]["json"] eq "Yes" %>false<%else%>'<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=mdc_source&mode=<%$mod_enc_mode["Search"]%>&rformat=html<%$extra_qstr%>'<%/if%>,
            "value": <%if $count_arr["mdc_source"]["json"] eq "Yes" %>$.parseJSON('<%$count_arr["mdc_source"]["data"]|@addslashes%>')<%else%>null<%/if%>,
            "dataInit": <%if $count_arr['mdc_source']['ajax'] eq 'Yes' %>initSearchGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["mdc_source"]["ajax"] eq "Yes" %>ajax-call<%/if%>',
            "multiple": true
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "db_change_log",
            "aria-unique-name": "mdc_source",
            "dataUrl": '<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=mdc_source&mode=<%$mod_enc_mode["Update"]%>&rformat=html<%$extra_qstr%>',
            "dataInit": <%if $count_arr['mdc_source']['ajax'] eq 'Yes' %>initEditGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["mdc_source"] eq "Yes" %>ajax-call<%/if%>',
            "data-placeholder": "<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'DB_CHANGE_LOG_SOURCE')%>",
            "class": "inline-edit-row chosen-select"
        },
        "ctrl_type": "dropdown",
        "default_value": "<%$list_config['mdc_source']['default']%>",
        "filterSopt": "in",
        "stype": "select"
    },
    {
        "name": "mdc_entity_name",
        "index": "mdc_entity_name",
        "label": "<%$list_config['mdc_entity_name']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['mdc_entity_name']['width']%>",
        "search": <%if $list_config['mdc_entity_name']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['mdc_entity_name']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mdc_entity_name']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mdc_entity_name']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['mdc_entity_name']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mdc_entity_name']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mdc_entity_name']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "select",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "db_change_log",
                "aria-unique-name": null,
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['mdc_entity_name']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "db_change_log",
            "aria-unique-name": null,
            "placeholder": null,
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['mdc_entity_name']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "mdc_logged_name",
        "index": "mdc_logged_name",
        "label": "<%$list_config['mdc_logged_name']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['mdc_logged_name']['width']%>",
        "search": <%if $list_config['mdc_logged_name']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['mdc_logged_name']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mdc_logged_name']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mdc_logged_name']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['mdc_logged_name']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mdc_logged_name']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mdc_logged_name']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "db_change_log",
                "aria-unique-name": "mdc_logged_name",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['mdc_logged_name']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "db_change_log",
            "aria-unique-name": "mdc_logged_name",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['mdc_logged_name']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "mdc_date_added",
        "index": "mdc_date_added",
        "label": "<%$list_config['mdc_date_added']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['mdc_date_added']['width']%>",
        "search": <%if $list_config['mdc_date_added']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['mdc_date_added']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mdc_date_added']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mdc_date_added']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['mdc_date_added']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mdc_date_added']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mdc_date_added']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "db_change_log",
                "aria-unique-name": "mdc_date_added",
                "autocomplete": "off",
                "class": "search-inline-date",
                "aria-date-format": "<%$this->general->getAdminJSMoments('date_and_time')%>",
                "aria-enable-time": "<%$this->general->getAdminJSMoments('date_and_time','ampm')%>"
            },
            "sopt": dateSearchOpts,
            "searchhidden": <%if $list_config['mdc_date_added']['search'] eq 'Yes' %>true<%else%>false<%/if%>,
            "dataInit": initSearchGridDateTimePicker
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "db_change_log",
            "aria-unique-name": "mdc_date_added",
            "aria-date-format": "<%$this->general->getAdminJSFormats('date_and_time', 'dateFormat')%>",
            "aria-time-format": "<%$this->general->getAdminJSFormats('date_and_time', 'timeFormat')%>",
            "aria-enable-sec": "<%$this->general->getAdminJSFormats('date_and_time', 'showSecond')%>",
            "aria-enable-ampm": "<%$this->general->getAdminJSFormats('date_and_time', 'ampm')%>",
            "aria-min-date": "",
            "aria-max-date": "",
            "placeholder": "",
            "class": "inline-edit-row inline-date-edit date-picker-icon dateTime"
        },
        "ctrl_type": "date_and_time",
        "default_value": "<%$list_config['mdc_date_added']['default']%>",
        "filterSopt": "bt"
    },
    {
        "name": "extra_fields",
        "index": "extra_fields",
        "label": "<%$list_config['extra_fields']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['extra_fields']['width']%>",
        "search": <%if $list_config['extra_fields']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['extra_fields']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['extra_fields']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['extra_fields']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['extra_fields']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['extra_fields']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['extra_fields']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "select",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "db_change_log",
                "aria-unique-name": null,
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['extra_fields']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "db_change_log",
            "aria-unique-name": null,
            "placeholder": null,
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['extra_fields']['default']%>",
        "filterSopt": "bw"
    }];
         
    initMainGridListing();
    createTooltipHeading();
    callSwitchToParent();
<%/javascript%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 