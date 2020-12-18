<%javascript%>
    $.fn.editable.defaults.mode = 'inline', $.fn.editable.defaults.clear = false;
    var el_topview_settings = {}, detail_view_colmodel_json = {}, detail_token_input_assign = {}, detail_token_pre_populates = {};
              
    el_topview_settings['extra_hstr'] = '<%$extra_hstr%>';
    el_topview_settings['extra_qstr'] = '<%$extra_qstr%>';
                    
    el_topview_settings['layout_view_id'] = '<%$extra_hstr%>';
    el_topview_settings['edit_id'] = '<%$enc_detail_primary_id%>';
    el_topview_settings['module_name'] = '<%$module_name%>';
                    
                    
    el_topview_settings['edit_page_url'] = admin_url+'<%$mod_enc_url["inline_edit_action"]%>'+'?&oper=edit&<%$extra_qstr%>';
    el_topview_settings['ajax_data_url'] = admin_url+'<%$mod_enc_url["get_chosen_auto_complete"]%>'+'?<%$extra_qstr%>';
    el_topview_settings['permit_edit_btn'] = '<%$edit_access%>';
    
    detail_view_colmodel_json = {
        "dv_mc_country": {
            "htmlID": "dv_mc_country",
            "name": "mc_country",
            "label": "Country",
            "label_lang": "<%$this->lang->line('COUNTRY_COUNTRY')%>",
            "type": "textbox",
            "ctrl_type": "textbox",
            "editable": <%if $list_config['mc_country']['viewedit'] eq 'Yes' %>true<%else%>false<%/if%>,
            "value": "<%$list_config['mc_country']['value']%>",
            "dbval": "<%$list_config['mc_country']['dbval']%>",
            "edittype": "text",
            "editrules": {
                "required": true,
                "infoArr": {
                    "required": {
                        "message": "04e9217fe43e67f9476cc9b37b475114"
                    }
                }
            },
            "editoptions": {
                "text_case": "",
                "placeholder": ""
            },
            "extra_class": ""
        },
        "dv_mc_country_code": {
            "htmlID": "dv_mc_country_code",
            "name": "mc_country_code",
            "label": "Country Code",
            "label_lang": "<%$this->lang->line('COUNTRY_COUNTRY_CODE')%>",
            "type": "textbox",
            "ctrl_type": "textbox",
            "editable": <%if $list_config['mc_country_code']['viewedit'] eq 'Yes' %>true<%else%>false<%/if%>,
            "value": "<%$list_config['mc_country_code']['value']%>",
            "dbval": "<%$list_config['mc_country_code']['dbval']%>",
            "edittype": "text",
            "editrules": {
                "required": true,
                "minlength": true,
                "maxlength": true,
                "infoArr": {
                    "required": {
                        "message": "1e4be48994d1028ce356e6847d4b2bf7"
                    },
                    "minlength": {
                        "minvalue": "2",
                        "message": "0b35c0825df0b4237d38923aa156046c"
                    },
                    "maxlength": {
                        "maxvalue": "2",
                        "message": "0599fc0d6992137fa1d2e24beeb6c33e"
                    }
                }
            },
            "editoptions": {
                "text_case": "",
                "placeholder": null
            },
            "extra_class": ""
        },
        "dv_mc_country_code_i_s_o_3": {
            "htmlID": "dv_mc_country_code_i_s_o_3",
            "name": "mc_country_code_i_s_o_3",
            "label": "Country Code ISO-3",
            "label_lang": "<%$this->lang->line('COUNTRY_COUNTRY_CODE_ISO_C453')%>",
            "type": "textbox",
            "ctrl_type": "textbox",
            "editable": <%if $list_config['mc_country_code_i_s_o_3']['viewedit'] eq 'Yes' %>true<%else%>false<%/if%>,
            "value": "<%$list_config['mc_country_code_i_s_o_3']['value']%>",
            "dbval": "<%$list_config['mc_country_code_i_s_o_3']['dbval']%>",
            "edittype": "text",
            "editrules": {
                "required": true,
                "infoArr": {
                    "required": {
                        "message": "94de2616d733b83bfdba715936067e7b"
                    }
                }
            },
            "editoptions": {
                "text_case": "",
                "placeholder": null
            },
            "extra_class": ""
        },
        "dv_sys_no_of_states": {
            "htmlID": "dv_sys_no_of_states",
            "name": "sys_no_of_states",
            "label": "Number Of States",
            "label_lang": "<%$this->lang->line('COUNTRY_NUMBER_OF_STATES')%>",
            "type": "textbox",
            "ctrl_type": "textbox",
            "editable": <%if $list_config['sys_no_of_states']['viewedit'] eq 'Yes' %>true<%else%>false<%/if%>,
            "value": "<%$list_config['sys_no_of_states']['value']%>",
            "dbval": "<%$list_config['sys_no_of_states']['dbval']%>",
            "editoptions": {
                "text_case": "",
                "placeholder": null
            },
            "extra_class": ""
        },
        "dv_mc_status": {
            "htmlID": "dv_mc_status",
            "name": "mc_status",
            "label": "Status",
            "label_lang": "<%$this->lang->line('COUNTRY_STATUS')%>",
            "type": "dropdown",
            "ctrl_type": "dropdown",
            "editable": <%if $list_config['mc_status']['viewedit'] eq 'Yes' %>true<%else%>false<%/if%>,
            "value": "<%$list_config['mc_status']['value']%>",
            "dbval": "<%$list_config['mc_status']['dbval']%>",
            "edittype": "select",
            "editrules": {
                "required": true,
                "infoArr": {
                    "required": {
                        "message": "7d6eae2fa12c9a33538c2d225ee651d3"
                    }
                }
            },
            "editoptions": {
                "ajaxCall": '<%if $count_arr["mc_status"]["ajax"] eq "Yes" %>ajax-call<%/if%>',
                "dataUrl": '<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=mc_status&id=<%$enc_detail_primary_id%>&par_field=<%$top_par_field%>&par_data=<%$top_par_id%>&mode=<%$mod_enc_mode["Update"]%>&rformat=json<%$extra_qstr%>',
                "rel": "mc_status",
                "data_placeholder": null
            }
        }
    };

    initDetailViewEditable();
<%/javascript%>


<div id="div_main_top_detail_view" class="div-main-top-detail-view" style="<%if $_toggle_flag eq '1' %>display:none;<%/if%>">
    <table id="<%$detail_layout_view_id%>" class="jqgrid-subview" width="100%" cellpadding="2" cellspacing="2">
        <tr>
            <td width="12%"><strong><%$this->lang->line('COUNTRY_COUNTRY')%>: </strong></td>
            <td width="20%"><%$data['mc_country']%></td>
            <td width="12%"><strong><%$this->lang->line('COUNTRY_COUNTRY_CODE')%>: </strong></td>
            <td width="20%"><%$data['mc_country_code']%></td>
        </tr>
        <tr>
            <td width="12%"><strong><%$this->lang->line('COUNTRY_COUNTRY_CODE_ISO_C453')%>: </strong></td>
            <td width="20%"><%$data['mc_country_code_i_s_o_3']%></td>
            <td width="12%"><strong><%$this->lang->line('COUNTRY_NUMBER_OF_STATES')%>: </strong></td>
            <td width="20%"><%$data['sys_no_of_states']%></td>
        </tr>
        <tr>
            <td width="12%"><strong><%$this->lang->line('COUNTRY_STATUS')%>: </strong></td>
            <td width="20%"><%$data['mc_status']%></td>
        </tr>
         
    </table>
</div>