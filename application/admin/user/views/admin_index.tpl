<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-list-container">
    <%include file="admin_index_strip.tpl"%>
    <!-- Grid View HTML Block -->
    <div id="layout_grid_list2" class="perm-elem-hide">                        
        <div class="grid-item-main">  
            <repeat>  
                <div class="grid-item">  
                    <div class="card grid-card">
                        <div style="display:none;" class="grid-login-as">{%login_as_btn%}</div>
                        <div class="grid-heading white">
                            <div class="grid-heading-left">{%mgm_group_name%}</div>
                            <div class="grid-heading-right">{%ma_last_access%}</div>
                            <div class="clear"></div>
                            <div class="grid-heading-initial">
                                {{#extra_fields}}
                                <img data-name="{%ma_user_name%}" data-color="#fff" class="grid-profile" width="75px" height="75px" data-char-count="2" data-text-color='{{theme_color}}' data-font-size="32" />
                                {{/extra_fields}}
                            </div>
                        </div>
                        <div class="grid-card-body">
                            <h3 class="grid-edit">{%ma_name%}</h3>
                            <h4 class="style-name" title="{%ma_user_name%}"> {%ma_user_name%}</h4>
                            <hr class="grid-hr">
                            <ul>
                                <li title="{%ma_email%}">
                                    <i class="fa fa-envelope" ></i> {%ma_email%}
                                </li>
                                <li title="{%ma_phonenumber%}">
                                    <i class="icomoon-icon-mobile"></i> {%ma_phonenumber%}
                                </li>
                            </ul>
                        </div>
                        <div class="grid-footer">
                            {{#extra_fields}}
                            <div class="footer-icon">
                                <a class="grid-footer-edit" hijacked="yes" href="#" title="Edit"><i class="{{edit.icon}}"></i></a>
                            </div>
                            <div class="footer-icon">
                                <a class="grid-footer-message {{notification_class}}" hijacked="yes" href="#" title="Send Message"><i class="{{message.icon}}"></i></a>
                            </div>
                            <div class="footer-icon">
                                <a class="grid-footer-notification" hijacked="yes" href="{{notification_url}}" title="Notifications">
                                    <i class="{{notification.icon}}"></i>
                                    {{#notification.count}}
                                    <span class="grid-notification-badge">{{notification.count}}</span>
                                    {{/notification.count}}
                                </a>
                            </div>
                            <div class="footer-icon">
                                <a class="grid-footer-login-as {{login_as_class}}" hijacked="yes" href="#" title="Login As"><i class="{{login_as.icon}}"></i></a>
                            </div>
                            <div class="clear"></div>        
                            {{/extra_fields}}
                        </div>
                        <div class="grid-message-popup showHide">
                            {{#extra_fields}}
                            <input type="hidden" class="admin_id" value="{{id}}" />
                            <input type="hidden" class="curr_user" value="{{curr_user}}" />
                            {{/extra_fields}}
                            <textarea class="grid-message-content" rows="5" placeholder="Enter your message here"></textarea>
                            <div class="btn btn-info grid-send-message"><span class="fa fa-paper-plane-o"></span></div>
                        </div>
                    </div>
                </div>  
            </repeat>  
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
                            <td class="left-search-td">
                                <span class="side-btn left-show <%$rl_theme_arr['grid_left_search']%>" id="grid_search_btn">
                                    <a class="side-menu-hide" href="javascript://" title="Hide Left Search Panel"><span class="icomoon-icon-arrow-left-7"></span></a>
                                </span>
                                <div id="left_search_panel" class="left-search-panel <%$rl_theme_arr['grid_left_search']%>">
                                    <!-- Global Search Textbox -->
                                    <div class="left-search-box"> 
                                        <input type="text" name="input_left_search" id="input_left_search" class="input-left-search" />
                                    </div>
                                    <div id="left_search_items">
                                        <%include file="admin_search.tpl" %>
                                    </div>
                                </div>
                            </td>
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
                
    el_grid_settings['hide_add_btn'] = '1';
    el_grid_settings['hide_del_btn'] = '1';
    el_grid_settings['hide_status_btn'] = '1';
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
    el_grid_settings['callbacks'] = {
        "after_data_load": "adminDataAfterLoad"
    };
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
        "name": "ma_email",
        "label": "<%$list_config['ma_email']['label_lang']%>"
    },
    {
        "name": "ma_phonenumber",
        "label": "<%$list_config['ma_phonenumber']['label_lang']%>"
    },
    {
        "name": "ma_user_name",
        "label": "<%$list_config['ma_user_name']['label_lang']%>"
    },
    {
        "name": "mgm_group_name",
        "label": "<%$list_config['mgm_group_name']['label_lang']%>"
    },
    {
        "name": "ma_last_access",
        "label": "<%$list_config['ma_last_access']['label_lang']%>"
    },
    {
        "name": "login_as_btn",
        "label": "<%$list_config['login_as_btn']['label_lang']%>"
    },
    {
        "name": "ma_status",
        "label": "<%$list_config['ma_status']['label_lang']%>"
    },
    {
        "name": "extra_fields",
        "label": "<%$list_config['extra_fields']['label_lang']%>"
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
        "edittype": "text",
        "editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.ADMIN_NAME)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "admin",
                "aria-unique-name": "ma_name",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['ma_name']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "admin",
            "aria-unique-name": "ma_name",
            "placeholder": null,
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['ma_name']['default']%>",
        "filterSopt": "bw",
        "formatter": formatAdminModuleEditLink,
        "unformat": unformatAdminModuleEditLink
    },
    {
        "name": "ma_email",
        "index": "ma_email",
        "label": "<%$list_config['ma_email']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['ma_email']['width']%>",
        "search": <%if $list_config['ma_email']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['ma_email']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['ma_email']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['ma_email']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['ma_email']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['ma_email']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['ma_email']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "text",
        "editrules": {
            "required": true,
            "email": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.ADMIN_EMAIL)
                },
                "email": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_VALID_EMAIL_ADDRESS_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.ADMIN_EMAIL)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "admin",
                "aria-unique-name": "ma_email",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['ma_email']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "admin",
            "aria-unique-name": "ma_email",
            "placeholder": null,
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['ma_email']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "ma_phonenumber",
        "index": "ma_phonenumber",
        "label": "<%$list_config['ma_phonenumber']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['ma_phonenumber']['width']%>",
        "search": <%if $list_config['ma_phonenumber']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['ma_phonenumber']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['ma_phonenumber']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['ma_phonenumber']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['ma_phonenumber']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['ma_phonenumber']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['ma_phonenumber']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "text",
        "editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.ADMIN_PHONE_NUMBER)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "admin",
                "aria-unique-name": "ma_phonenumber",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['ma_phonenumber']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "admin",
            "aria-unique-name": "ma_phonenumber",
            "aria-phone-format": "<%$this->general->getAdminPHPFormats('phone')%>",
            "dataInit": initEditGridMaskingEvent,
            "class": "inline-edit-row phoneNumber"
        },
        "ctrl_type": "phone_number",
        "default_value": "<%$list_config['ma_phonenumber']['default']%>",
        "filterSopt": "cn"
    },
    {
        "name": "ma_user_name",
        "index": "ma_user_name",
        "label": "<%$list_config['ma_user_name']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['ma_user_name']['width']%>",
        "search": <%if $list_config['ma_user_name']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['ma_user_name']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['ma_user_name']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['ma_user_name']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['ma_user_name']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['ma_user_name']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['ma_user_name']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "text",
        "editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.ADMIN_USER_NAME)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "admin",
                "aria-unique-name": "ma_user_name",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['ma_user_name']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "admin",
            "aria-unique-name": "ma_user_name",
            "placeholder": null,
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['ma_user_name']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "mgm_group_name",
        "index": "mgm_group_name",
        "label": "<%$list_config['mgm_group_name']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['mgm_group_name']['width']%>",
        "search": <%if $list_config['mgm_group_name']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['mgm_group_name']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mgm_group_name']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mgm_group_name']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['mgm_group_name']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mgm_group_name']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mgm_group_name']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "select",
        "editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.ADMIN_GROUP)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "admin",
                "aria-unique-name": "ma_group_id",
                "autocomplete": "off",
                "data-placeholder": " ",
                "class": "search-chosen-select",
                "multiple": "multiple"
            },
            "sopt": intSearchOpts,
            "searchhidden": <%if $list_config['mgm_group_name']['search'] eq 'Yes' %>true<%else%>false<%/if%>,
            "dataUrl": <%if $count_arr["mgm_group_name"]["json"] eq "Yes" %>false<%else%>'<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=mgm_group_name&mode=<%$mod_enc_mode["Search"]%>&rformat=html<%$extra_qstr%>'<%/if%>,
            "value": <%if $count_arr["mgm_group_name"]["json"] eq "Yes" %>$.parseJSON('<%$count_arr["mgm_group_name"]["data"]|@addslashes%>')<%else%>null<%/if%>,
            "dataInit": <%if $count_arr['mgm_group_name']['ajax'] eq 'Yes' %>initSearchGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["mgm_group_name"]["ajax"] eq "Yes" %>ajax-call<%/if%>',
            "multiple": true
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "admin",
            "aria-unique-name": "ma_group_id",
            "dataUrl": '<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=mgm_group_name&mode=<%$mod_enc_mode["Update"]%>&rformat=html<%$extra_qstr%>',
            "dataInit": <%if $count_arr['mgm_group_name']['ajax'] eq 'Yes' %>initEditGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["mgm_group_name"] eq "Yes" %>ajax-call<%/if%>',
            "data-placeholder": null,
            "class": "inline-edit-row chosen-select"
        },
        "ctrl_type": "dropdown",
        "default_value": "<%$list_config['mgm_group_name']['default']%>",
        "filterSopt": "in",
        "stype": "select"
    },
    {
        "name": "ma_last_access",
        "index": "ma_last_access",
        "label": "<%$list_config['ma_last_access']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['ma_last_access']['width']%>",
        "search": <%if $list_config['ma_last_access']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['ma_last_access']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['ma_last_access']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['ma_last_access']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['ma_last_access']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['ma_last_access']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['ma_last_access']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "text",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "admin",
                "aria-unique-name": "ma_last_access",
                "autocomplete": "off",
                "class": "search-inline-date",
                "aria-date-format": "<%$this->general->getAdminJSMoments('date_and_time')%>",
                "aria-enable-time": "<%$this->general->getAdminJSMoments('date_and_time','ampm')%>"
            },
            "sopt": dateSearchOpts,
            "searchhidden": <%if $list_config['ma_last_access']['search'] eq 'Yes' %>true<%else%>false<%/if%>,
            "dataInit": initSearchGridDateTimePicker
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "admin",
            "aria-unique-name": "ma_last_access",
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
        "default_value": "<%$list_config['ma_last_access']['default']%>",
        "filterSopt": "bt"
    },
    {
        "name": "login_as_btn",
        "index": "login_as_btn",
        "label": "<%$list_config['login_as_btn']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['login_as_btn']['width']%>",
        "search": <%if $list_config['login_as_btn']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['login_as_btn']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['login_as_btn']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['login_as_btn']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['login_as_btn']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['login_as_btn']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['login_as_btn']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "select",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "admin",
                "aria-unique-name": null,
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['login_as_btn']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "admin",
            "aria-unique-name": null,
            "placeholder": null,
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['login_as_btn']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "ma_status",
        "index": "ma_status",
        "label": "<%$list_config['ma_status']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['ma_status']['width']%>",
        "search": <%if $list_config['ma_status']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['ma_status']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['ma_status']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['ma_status']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['ma_status']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['ma_status']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['ma_status']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "select",
        "editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE__C35FIELD_C35_FIELD_C46 ,"#FIELD#",js_lang_label.ADMIN_STATUS)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "admin",
                "aria-unique-name": "ma_status",
                "autocomplete": "off",
                "data-placeholder": " ",
                "class": "search-chosen-select",
                "multiple": "multiple"
            },
            "sopt": intSearchOpts,
            "searchhidden": <%if $list_config['ma_status']['search'] eq 'Yes' %>true<%else%>false<%/if%>,
            "dataUrl": <%if $count_arr["ma_status"]["json"] eq "Yes" %>false<%else%>'<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=ma_status&mode=<%$mod_enc_mode["Search"]%>&rformat=html<%$extra_qstr%>'<%/if%>,
            "value": <%if $count_arr["ma_status"]["json"] eq "Yes" %>$.parseJSON('<%$count_arr["ma_status"]["data"]|@addslashes%>')<%else%>null<%/if%>,
            "dataInit": <%if $count_arr['ma_status']['ajax'] eq 'Yes' %>initSearchGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["ma_status"]["ajax"] eq "Yes" %>ajax-call<%/if%>',
            "multiple": true
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "admin",
            "aria-unique-name": "ma_status",
            "dataUrl": '<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=ma_status&mode=<%$mod_enc_mode["Update"]%>&rformat=html<%$extra_qstr%>',
            "dataInit": <%if $count_arr['ma_status']['ajax'] eq 'Yes' %>initEditGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["ma_status"] eq "Yes" %>ajax-call<%/if%>',
            "data-placeholder": null,
            "class": "inline-edit-row chosen-select"
        },
        "ctrl_type": "dropdown",
        "default_value": "<%$list_config['ma_status']['default']%>",
        "filterSopt": "in",
        "stype": "select"
    },
    {
        "name": "extra_fields",
        "index": "extra_fields",
        "label": "<%$list_config['extra_fields']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['extra_fields']['width']%>",
        "search": <%if $list_config['extra_fields']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "export": <%if $list_config['extra_fields']['export'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['extra_fields']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['extra_fields']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "hideme": <%if $list_config['extra_fields']['hideme'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['extra_fields']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['extra_fields']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "select",
        "editrules": {
            "infoArr": []
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "admin",
                "aria-unique-name": null,
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['extra_fields']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "admin",
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

<%$this->js->add_js("admin/custom/admin_list_custom.js")%>
<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 