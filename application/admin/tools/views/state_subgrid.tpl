<div class="module-sublist-container">
                
    
    <!-- Module Expand-Listing Block -->
    <div class="jq-subgrid-block">
        <div id="<%$subgrid_pager_id%>"></div>
        <table id="<%$subgrid_table_id%>"></table>
    </div>
</div>
<!-- Module Expand-Listing Javascript -->
<%javascript%>
    var el_subgrid_settings = {}, sub_js_col_name_json = {}, sub_js_col_model_json = {};
           
    el_subgrid_settings['table_id'] = '<%$subgrid_table_id%>';
    el_subgrid_settings['pager_id'] = '<%$subgrid_pager_id%>';
    el_subgrid_settings['module_name'] = '<%$module_name%>';
    el_subgrid_settings['advanced_grid'] = '<%$exp_advanced_grid%>';
    el_subgrid_settings['extra_hstr'] = '<%$extra_hstr%>';
    el_subgrid_settings['extra_qstrs'] = '<%$extra_qstr%>';
    el_subgrid_settings['par_module'] = '<%$exp_module_name%>';
    el_subgrid_settings['par_data'] = '<%$exp_par_id%>';
    el_subgrid_settings['par_field'] = '<%$exp_par_field%>';
    el_subgrid_settings['par_type'] = 'grid';
            
    el_subgrid_settings['index_page_url'] = '<%$mod_enc_url["index"]%>';
    el_subgrid_settings['add_page_url'] = '<%$mod_enc_url["add"]%>'; 
    el_subgrid_settings['edit_page_url'] = admin_url+'<%$mod_enc_url["inline_edit_action"]%>?<%$extra_qstr%>';
    el_subgrid_settings['listing_url'] = admin_url+'<%$mod_enc_url["listing"]%>?<%$extra_qstr%>';
    el_subgrid_settings['export_url'] =  admin_url+'<%$mod_enc_url["export"]%>?<%$extra_qstr%>';
    el_subgrid_settings['print_url'] =  admin_url+'<%$mod_enc_url["print_listing"]%>?<%$extra_qstr%>';
    
    el_subgrid_settings['ajax_data_url'] = admin_url+'<%$mod_enc_url["get_chosen_auto_complete"]%>?<%$extra_qstr%>';
    el_subgrid_settings['auto_complete_url'] = admin_url+'<%$mod_enc_url["get_token_auto_complete"]%>?<%$extra_qstr%>';
    el_subgrid_settings['nesgrid_listing_url'] =  admin_url+'<%$mod_enc_url["get_subgrid_block"]%>?<%$extra_qstr%>';
    
    el_subgrid_settings['admin_rec_arr'] = $.parseJSON('<%$hide_admin_rec|@json_encode%>');;
    el_subgrid_settings['status_arr'] = $.parseJSON('<%$status_array|@json_encode%>');
    el_subgrid_settings['status_lang_arr'] = $.parseJSON('<%$status_label|@json_encode%>');
            
    el_subgrid_settings['hide_add_btn'] = '1';
    el_subgrid_settings['hide_del_btn'] = '1';
    el_subgrid_settings['hide_status_btn'] = '1';
    el_subgrid_settings['hide_export_btn'] = '';
    el_subgrid_settings['hide_columns_btn'] = 'Yes';
    
    el_subgrid_settings['hide_advance_search'] = 'No';
    el_subgrid_settings['hide_search_tool'] = 'No';
    el_subgrid_settings['hide_multi_select'] = 'No';
    el_subgrid_settings['hide_paging_btn'] = 'No';
    el_subgrid_settings['hide_refresh_btn'] = 'Yes';
    
    el_subgrid_settings['popup_add_form'] = 'No';
    el_subgrid_settings['popup_edit_form'] = 'No';
    el_subgrid_settings['popup_add_size'] = ['75%', '75%'];
    el_subgrid_settings['popup_edit_size'] = ['75%', '75%'];
    
    el_subgrid_settings['permit_add_btn'] = '<%$add_access%>';
    el_subgrid_settings['permit_del_btn'] = '<%$del_access%>';
    el_subgrid_settings['permit_edit_btn'] = '<%$edit_access%>';
    el_subgrid_settings['permit_view_btn'] = '<%$view_access%>';
    el_subgrid_settings['permit_expo_btn'] = '<%$expo_access%>';
    el_subgrid_settings['permit_print_btn'] = '<%$print_access%>';
    
    el_subgrid_settings['group_search'] = '';
    el_subgrid_settings['default_sort'] = 'ms_state';
    el_subgrid_settings['sort_order'] = 'asc';
    el_subgrid_settings['footer_row'] = 'No';
    el_subgrid_settings['grouping'] = 'Yes';
    el_subgrid_settings['group_attr'] = {};
    
    el_subgrid_settings['inline_add'] = 'No';
    el_subgrid_settings['rec_position'] = 'Top';
    el_subgrid_settings['auto_width'] = 'Yes';
    el_subgrid_settings['print_rec'] = 'No';
    el_subgrid_settings['print_list'] = 'No';
    
    el_subgrid_settings['nesgrid'] = '<%$exp_nested_grid%>';
    el_subgrid_settings['listview'] = 'list';
    el_subgrid_settings['rating_allow'] = 'No';
    el_subgrid_settings['global_filter'] = 'No';
    
    el_subgrid_settings['top_filter'] = [];
    el_subgrid_settings['buttons_arr'] = [];
    el_subgrid_settings['callbacks'] = [];
    el_subgrid_settings['message_arr'] = {
        "delete_alert" : "<%$this->general->processMessageLabel('ACTION_PLEASE_SELECT_ANY_RECORD')%>",
        "delete_popup" : "<%$this->general->processMessageLabel('ACTION_ARE_YOU_SURE_WANT_TO_DELETE_THIS_RECORD_C63')%>",
        "status_alert" : "<%$this->general->processMessageLabel('ACTION_PLEASE_SELECT_ANY_RECORD_TO__C35STATUS_C35')%>",
        "status_popup" : "<%$this->general->processMessageLabel('ACTION_ARE_YOU_SURE_WANT_TO__C35STATUS_C35_THIS_RECORDS_C63')%>",
    };
    
    sub_js_col_name_json = [{
        "name": "ms_state",
        "label": "<%$list_config['ms_state']['label_lang']%>"
    },
    {
        "name": "mc_country",
        "label": "<%$list_config['mc_country']['label_lang']%>"
    },
    {
        "name": "ms_state_code",
        "label": "<%$list_config['ms_state_code']['label_lang']%>"
    },
    {
        "name": "ms_country_code",
        "label": "<%$list_config['ms_country_code']['label_lang']%>"
    },
    {
        "name": "ms_status",
        "label": "<%$list_config['ms_status']['label_lang']%>"
    }];

    sub_js_col_model_json = [{
        "name": "ms_state",
        "index": "ms_state",
        "label": "<%$list_config['ms_state']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['ms_state']['width']%>",
        "search": <%if $list_config['ms_state']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['ms_state']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['ms_state']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['ms_state']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['ms_state']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['ms_state']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['ms_state']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "text",
        "editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.STATE_STATE)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "state",
                "aria-unique-name": "ms_state",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['ms_state']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "state",
            "aria-unique-name": "ms_state",
            "placeholder": null,
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['ms_state']['default']%>",
        "filterSopt": "bw",
        "formatter": formatAdminModuleEditLink,
        "unformat": unformatAdminModuleEditLink
    },
    {
        "name": "mc_country",
        "index": "mc_country",
        "label": "<%$list_config['mc_country']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['mc_country']['width']%>",
        "search": <%if $list_config['mc_country']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['mc_country']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mc_country']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mc_country']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['mc_country']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mc_country']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mc_country']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "select",
        "editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.STATE_COUNTRY)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "state",
                "aria-unique-name": "ms_country_id",
                "autocomplete": "off",
                "data-placeholder": " ",
                "class": "search-chosen-select",
                "multiple": "multiple"
            },
            "sopt": intSearchOpts,
            "searchhidden": <%if $list_config['mc_country']['search'] eq 'Yes' %>true<%else%>false<%/if%>,
            "dataUrl": <%if $count_arr["mc_country"]["json"] eq "Yes" %>false<%else%>'<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=mc_country&mode=<%$mod_enc_mode["Search"]%>&rformat=html<%$extra_qstr%>'<%/if%>,
            "value": <%if $count_arr["mc_country"]["json"] eq "Yes" %>$.parseJSON('<%$count_arr["mc_country"]["data"]|@addslashes%>')<%else%>null<%/if%>,
            "dataInit": <%if $count_arr['mc_country']['ajax'] eq 'Yes' %>initSearchGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["mc_country"]["ajax"] eq "Yes" %>ajax-call<%/if%>',
            "multiple": true
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "state",
            "aria-unique-name": "ms_country_id",
            "dataUrl": '<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=mc_country&mode=<%$mod_enc_mode["Update"]%>&rformat=html<%$extra_qstr%>',
            "dataInit": <%if $count_arr['mc_country']['ajax'] eq 'Yes' %>initEditGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["mc_country"] eq "Yes" %>ajax-call<%/if%>',
            "data-placeholder": null,
            "class": "inline-edit-row chosen-select"
        },
        "ctrl_type": "dropdown",
        "default_value": "<%$list_config['mc_country']['default']%>",
        "filterSopt": "in",
        "stype": "select"
    },
    {
        "name": "ms_state_code",
        "index": "ms_state_code",
        "label": "<%$list_config['ms_state_code']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['ms_state_code']['width']%>",
        "search": <%if $list_config['ms_state_code']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['ms_state_code']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['ms_state_code']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['ms_state_code']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['ms_state_code']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['ms_state_code']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['ms_state_code']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "text",
        "editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.STATE_STATE_CODE)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "state",
                "aria-unique-name": "ms_state_code",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['ms_state_code']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "state",
            "aria-unique-name": "ms_state_code",
            "placeholder": null,
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['ms_state_code']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "ms_country_code",
        "index": "ms_country_code",
        "label": "<%$list_config['ms_country_code']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['ms_country_code']['width']%>",
        "search": <%if $list_config['ms_country_code']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['ms_country_code']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['ms_country_code']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['ms_country_code']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['ms_country_code']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['ms_country_code']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['ms_country_code']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "state",
                "aria-unique-name": "ms_country_code",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['ms_country_code']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "state",
            "aria-unique-name": "ms_country_code",
            "placeholder": null,
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['ms_country_code']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "ms_status",
        "index": "ms_status",
        "label": "<%$list_config['ms_status']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['ms_status']['width']%>",
        "search": <%if $list_config['ms_status']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['ms_status']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['ms_status']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['ms_status']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['ms_status']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['ms_status']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['ms_status']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "select",
        "editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.STATE_STATUS)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "state",
                "aria-unique-name": "ms_status",
                "autocomplete": "off",
                "data-placeholder": " ",
                "class": "search-chosen-select",
                "multiple": "multiple"
            },
            "sopt": intSearchOpts,
            "searchhidden": <%if $list_config['ms_status']['search'] eq 'Yes' %>true<%else%>false<%/if%>,
            "dataUrl": <%if $count_arr["ms_status"]["json"] eq "Yes" %>false<%else%>'<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=ms_status&mode=<%$mod_enc_mode["Search"]%>&rformat=html<%$extra_qstr%>'<%/if%>,
            "value": <%if $count_arr["ms_status"]["json"] eq "Yes" %>$.parseJSON('<%$count_arr["ms_status"]["data"]|@addslashes%>')<%else%>null<%/if%>,
            "dataInit": <%if $count_arr['ms_status']['ajax'] eq 'Yes' %>initSearchGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["ms_status"]["ajax"] eq "Yes" %>ajax-call<%/if%>',
            "multiple": true
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "state",
            "aria-unique-name": "ms_status",
            "dataUrl": '<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=ms_status&mode=<%$mod_enc_mode["Update"]%>&rformat=html<%$extra_qstr%>',
            "dataInit": <%if $count_arr['ms_status']['ajax'] eq 'Yes' %>initEditGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["ms_status"] eq "Yes" %>ajax-call<%/if%>',
            "data-placeholder": null,
            "class": "inline-edit-row chosen-select"
        },
        "ctrl_type": "dropdown",
        "default_value": "<%$list_config['ms_status']['default']%>",
        "filterSopt": "in",
        "stype": "select"
    }];
                  
    initSubGridListing();
<%/javascript%>
    