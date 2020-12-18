<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-list-container">
    <%include file="loghistory_index_strip.tpl"%>
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
    el_grid_settings['popup_add_size'] = ['', ''];
    el_grid_settings['popup_edit_size'] = ['', ''];
    
    el_grid_settings['permit_add_btn'] = '<%$add_access%>';
    el_grid_settings['permit_del_btn'] = '<%$del_access%>';
    el_grid_settings['permit_edit_btn'] = '<%$edit_access%>';
    el_grid_settings['permit_view_btn'] = '<%$view_access%>';
    el_grid_settings['permit_expo_btn'] = '<%$expo_access%>';
    el_grid_settings['permit_print_btn'] = '<%$print_access%>';
        
    el_grid_settings['group_search'] = '';
    el_grid_settings['default_sort'] = 'ma_name';
    el_grid_settings['sort_order'] = 'asc';
    el_grid_settings['footer_row'] = 'No';
    el_grid_settings['grouping'] = 'Yes';
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
    el_grid_settings['listview'] = 'list';
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
        "name": "ma_name",
        "label": "<%$list_config['ma_name']['label_lang']%>"
    },
    {
        "name": "mlh_i_p",
        "label": "<%$list_config['mlh_i_p']['label_lang']%>"
    },
    {
        "name": "mlh_login_date",
        "label": "<%$list_config['mlh_login_date']['label_lang']%>"
    },
    {
        "name": "mlh_logout_date",
        "label": "<%$list_config['mlh_logout_date']['label_lang']%>"
    }];
    
    js_col_model_json = [{
        "name": "ma_name",
        "index": "ma_name",
        "label": "<%$list_config['ma_name']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['ma_name']['width']%>",
        "search": <%if $list_config['ma_name']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['ma_name']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['ma_name']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['ma_name']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['ma_name']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['ma_name']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['ma_name']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "select",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "loghistory",
                "aria-unique-name": "mlh_user_id",
                "autocomplete": "off",
                "data-placeholder": " ",
                "class": "search-chosen-select",
                "multiple": "multiple"
            },
            "sopt": intSearchOpts,
            "searchhidden": <%if $list_config['ma_name']['search'] eq 'Yes' %>true<%else%>false<%/if%>,
            "dataUrl": <%if $count_arr["ma_name"]["json"] eq "Yes" %>false<%else%>'<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=ma_name&mode=<%$mod_enc_mode["Search"]%>&rformat=html<%$extra_qstr%>'<%/if%>,
            "value": <%if $count_arr["ma_name"]["json"] eq "Yes" %>$.parseJSON('<%$count_arr["ma_name"]["data"]|@addslashes%>')<%else%>null<%/if%>,
            "dataInit": <%if $count_arr['ma_name']['ajax'] eq 'Yes' %>initSearchGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["ma_name"]["ajax"] eq "Yes" %>ajax-call<%/if%>',
            "multiple": true
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "loghistory",
            "aria-unique-name": "mlh_user_id",
            "dataUrl": '<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=ma_name&mode=<%$mod_enc_mode["Update"]%>&rformat=html<%$extra_qstr%>',
            "dataInit": <%if $count_arr['ma_name']['ajax'] eq 'Yes' %>initEditGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["ma_name"] eq "Yes" %>ajax-call<%/if%>',
            "data-placeholder": null,
            "class": "inline-edit-row chosen-select"
        },
        "ctrl_type": "dropdown",
        "default_value": "<%$list_config['ma_name']['default']%>",
        "filterSopt": "in",
        "stype": "select"
    },
    {
        "name": "mlh_i_p",
        "index": "mlh_i_p",
        "label": "<%$list_config['mlh_i_p']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['mlh_i_p']['width']%>",
        "search": <%if $list_config['mlh_i_p']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['mlh_i_p']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mlh_i_p']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mlh_i_p']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['mlh_i_p']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mlh_i_p']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mlh_i_p']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "textarea",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "loghistory",
                "aria-unique-name": "mlh_i_p",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['mlh_i_p']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "loghistory",
            "aria-unique-name": "mlh_i_p",
            "rows": "1",
            "placeholder": null,
            "dataInit": initEditGridElasticEvent,
            "class": "inline-edit-row inline-textarea-edit "
        },
        "ctrl_type": "textarea",
        "default_value": "<%$list_config['mlh_i_p']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "mlh_login_date",
        "index": "mlh_login_date",
        "label": "<%$list_config['mlh_login_date']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['mlh_login_date']['width']%>",
        "search": <%if $list_config['mlh_login_date']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['mlh_login_date']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mlh_login_date']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mlh_login_date']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['mlh_login_date']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mlh_login_date']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mlh_login_date']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "loghistory",
                "aria-unique-name": "mlh_login_date",
                "autocomplete": "off",
                "class": "search-inline-date",
                "aria-date-format": "<%$this->general->getAdminJSMoments('date_and_time')%>",
                "aria-enable-time": "<%$this->general->getAdminJSMoments('date_and_time','ampm')%>"
            },
            "sopt": dateSearchOpts,
            "searchhidden": <%if $list_config['mlh_login_date']['search'] eq 'Yes' %>true<%else%>false<%/if%>,
            "dataInit": initSearchGridDateTimePicker
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "loghistory",
            "aria-unique-name": "mlh_login_date",
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
        "default_value": "<%$list_config['mlh_login_date']['default']%>",
        "filterSopt": "bt"
    },
    {
        "name": "mlh_logout_date",
        "index": "mlh_logout_date",
        "label": "<%$list_config['mlh_logout_date']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['mlh_logout_date']['width']%>",
        "search": <%if $list_config['mlh_logout_date']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['mlh_logout_date']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mlh_logout_date']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mlh_logout_date']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['mlh_logout_date']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mlh_logout_date']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mlh_logout_date']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "loghistory",
                "aria-unique-name": "mlh_logout_date",
                "autocomplete": "off",
                "class": "search-inline-date",
                "aria-date-format": "<%$this->general->getAdminJSMoments('date_and_time')%>",
                "aria-enable-time": "<%$this->general->getAdminJSMoments('date_and_time','ampm')%>"
            },
            "sopt": dateSearchOpts,
            "searchhidden": <%if $list_config['mlh_logout_date']['search'] eq 'Yes' %>true<%else%>false<%/if%>,
            "dataInit": initSearchGridDateTimePicker
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "loghistory",
            "aria-unique-name": "mlh_logout_date",
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
        "default_value": "<%$list_config['mlh_logout_date']['default']%>",
        "filterSopt": "bt"
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