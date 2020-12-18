            

<div class="jq-subgrid-block">
    <div id="<%$subgrid_pager_id%>"></div>
    <table id="<%$subgrid_table_id%>"></table>
</div>
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
            
    el_subgrid_settings['add_page_url'] = '<%$mod_enc_url["add"]%>'; 
    el_subgrid_settings['edit_page_url'] = admin_url+'<%$mod_enc_url["inline_edit_action"]%>?<%$extra_qstr%>';
    el_subgrid_settings['listing_url'] = admin_url+'<%$mod_enc_url["listing"]%>?<%$extra_qstr%>';
    el_subgrid_settings['ajax_data_url'] = admin_url+'<%$mod_enc_url["get_chosen_auto_complete"]%>?<%$extra_qstr%>';
    el_subgrid_settings['auto_complete_url'] = admin_url+'<%$mod_enc_url["get_token_auto_complete"]%>?<%$extra_qstr%>';
    el_subgrid_settings['nesgrid_listing_url'] =  admin_url+'<%$mod_enc_url["get_subgrid_block"]%>?<%$extra_qstr%>';
    
    el_subgrid_settings['admin_rec_arr'] = {};
    el_subgrid_settings['status_arr'] = $.parseJSON('<%$status_array|@json_encode%>');
    el_subgrid_settings['status_lang_arr'] = $.parseJSON('<%$status_label|@json_encode%>');
            
    el_subgrid_settings['hide_add_btn'] = '1';
    el_subgrid_settings['hide_del_btn'] = '1';
    el_subgrid_settings['hide_status_btn'] = '1';
    
    el_subgrid_settings['hide_advance_search'] = 'No';
    el_subgrid_settings['hide_search_tool'] = 'No';
    el_subgrid_settings['hide_multi_select'] = 'No';
    el_subgrid_settings['popup_add_form'] = 'No';
    el_subgrid_settings['popup_edit_form'] = 'No';
    el_subgrid_settings['popup_add_size'] = ['75%', '75%'];
    el_subgrid_settings['popup_edit_size'] = ['75%', '75%'];
    el_subgrid_settings['hide_paging_btn'] = 'No';
    el_subgrid_settings['hide_refresh_btn'] = 'No';
    el_subgrid_settings['group_search'] = '';
    
    el_subgrid_settings['permit_add_btn'] = '<%$add_access%>';
    el_subgrid_settings['permit_del_btn'] = '<%$del_access%>';
    el_subgrid_settings['permit_edit_btn'] = '<%$view_access%>';
    
    el_subgrid_settings['default_sort'] = 'mc_country';
    el_subgrid_settings['sort_order'] = 'asc';
    
    el_subgrid_settings['footer_row'] = 'No';
    el_subgrid_settings['grouping'] = 'No';
    el_subgrid_settings['group_attr'] = {};
    
    el_subgrid_settings['inline_add'] = 'No';
    el_subgrid_settings['rec_position'] = 'Top';
    el_subgrid_settings['auto_width'] = 'Yes';
    el_subgrid_settings['nesgrid'] = '<%$exp_nested_grid%>';
    el_subgrid_settings['rating_allow'] = 'No';
    el_subgrid_settings['listview'] = 'list';
    el_subgrid_settings['top_filter'] = [];
    el_subgrid_settings['buttons_arr'] = [];
    
    sub_js_col_name_json = [{
        "name": "mc_country",
        "label": "<%$this->lang->line('COUNTRY_COUNTRY')%>"
    },
    {
        "name": "mc_country_code",
        "label": "<%$this->lang->line('COUNTRY_COUNTRY_CODE')%>"
    },
    {
        "name": "mc_country_code_i_s_o_3",
        "label": "<%$this->lang->line('COUNTRY_COUNTRY_CODE_ISO_C453')%>"
    },
    {
        "name": "sys_no_of_states",
        "label": "<%$this->lang->line('COUNTRY_NUMBER_OF_STATES')%>"
    },
    {
        "name": "mc_status",
        "label": "<%$this->lang->line('COUNTRY_STATUS')%>"
    }];

    sub_js_col_model_json = [{
        "name": "mc_country",
        "index": "mc_country",
        "label": "<%$list_config['mc_country']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['mc_country']['width']%>",
        "search": <%if $list_config['mc_country']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mc_country']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mc_country']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mc_country']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mc_country']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "text",
        "editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.COUNTRY_COUNTRY)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "country",
                "aria-unique-name": "mc_country",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['mc_country']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "country",
            "aria-unique-name": "mc_country",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['mc_country']['default']%>",
        "filterSopt": "bw",
        "formatter": formatAdminModuleEditLink,
        "unformat": unformatAdminModuleEditLink
    },
    {
        "name": "mc_country_code",
        "index": "mc_country_code",
        "label": "<%$list_config['mc_country_code']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['mc_country_code']['width']%>",
        "search": <%if $list_config['mc_country_code']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mc_country_code']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mc_country_code']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mc_country_code']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mc_country_code']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "text",
        "editrules": {
            "required": true,
            "minlength": true,
            "maxlength": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.COUNTRY_COUNTRY_CODE)
                },
                "minlength": {
                    "minvalue": "2",
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_MINIMUM_LENGTH_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.COUNTRY_COUNTRY_CODE)
                },
                "maxlength": {
                    "maxvalue": "2",
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_MAXIMUM_LENGTH_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.COUNTRY_COUNTRY_CODE)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "country",
                "aria-unique-name": "mc_country_code",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['mc_country_code']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "country",
            "aria-unique-name": "mc_country_code",
            "placeholder": null,
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['mc_country_code']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "mc_country_code_i_s_o_3",
        "index": "mc_country_code_i_s_o_3",
        "label": "<%$list_config['mc_country_code_i_s_o_3']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['mc_country_code_i_s_o_3']['width']%>",
        "search": <%if $list_config['mc_country_code_i_s_o_3']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mc_country_code_i_s_o_3']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mc_country_code_i_s_o_3']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mc_country_code_i_s_o_3']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mc_country_code_i_s_o_3']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "text",
        "editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.COUNTRY_COUNTRY_CODE_ISO_C453)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "country",
                "aria-unique-name": "mc_country_code_i_s_o_3",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['mc_country_code_i_s_o_3']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "country",
            "aria-unique-name": "mc_country_code_i_s_o_3",
            "placeholder": null,
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['mc_country_code_i_s_o_3']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "sys_no_of_states",
        "index": "sys_no_of_states",
        "label": "<%$list_config['sys_no_of_states']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['sys_no_of_states']['width']%>",
        "search": <%if $list_config['sys_no_of_states']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['sys_no_of_states']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['sys_no_of_states']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['sys_no_of_states']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['sys_no_of_states']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "select",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "country",
                "aria-unique-name": null,
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['sys_no_of_states']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "country",
            "aria-unique-name": null,
            "placeholder": null,
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['sys_no_of_states']['default']%>",
        "filterSopt": "bw",
        "expandrow": 1,
        "formatter": formatExpandableLink,
        "unformat": "unformatExpandableLink"
    },
    {
        "name": "mc_status",
        "index": "mc_status",
        "label": "<%$list_config['mc_status']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['mc_status']['width']%>",
        "search": <%if $list_config['mc_status']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mc_status']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mc_status']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mc_status']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mc_status']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "select",
        "editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.COUNTRY_STATUS)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "country",
                "aria-unique-name": "mc_status",
                "autocomplete": "off",
                "data-placeholder": " ",
                "class": "search-chosen-select",
                "multiple": "multiple"
            },
            "sopt": intSearchOpts,
            "searchhidden": <%if $list_config['mc_status']['search'] eq 'Yes' %>true<%else%>false<%/if%>,
            "dataUrl": <%if $count_arr["mc_status"]["json"] eq "Yes" %>false<%else%>'<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=mc_status&mode=<%$mod_enc_mode["Search"]%>&rformat=html'<%/if%>,
            "value": <%if $count_arr["mc_status"]["json"] eq "Yes" %>$.parseJSON('<%$count_arr["mc_status"]["data"]|@addslashes%>')<%else%>null<%/if%>,
            "dataInit": <%if $count_arr['mc_status']['ajax'] eq 'Yes' %>initSearchGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["mc_status"]["ajax"] eq "Yes" %>ajax-call<%/if%>',
            "multiple": true
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "country",
            "aria-unique-name": "mc_status",
            "dataUrl": '<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=mc_status&mode=<%$mod_enc_mode["Update"]%>&rformat=html',
            "dataInit": <%if $count_arr['mc_status']['ajax'] eq 'Yes' %>initEditGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["mc_status"] eq "Yes" %>ajax-call<%/if%>',
            "data-placeholder": null,
            "class": "inline-edit-row chosen-select"
        },
        "ctrl_type": "dropdown",
        "default_value": "<%$list_config['mc_status']['default']%>",
        "filterSopt": "in",
        "stype": "select"
    }];
                  
    initSubGridListing();
<%/javascript%>
    