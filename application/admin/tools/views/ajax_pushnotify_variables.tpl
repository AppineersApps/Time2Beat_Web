<%if $select_fields eq 'Yes'%>
    <%if $field_arr|@is_array && $field_arr|@count gt 0 %>
        <optgroup label="List Fields">
            <%foreach from=$field_arr key=K item=V%>
                <option value='<%$V["name"]%>'><%$V['label']%></option>
            <%/foreach%>
        </optgroup>
    <%/if%>
    <optgroup label="Other">
        <option selected="selected" value="Other">Other</option>
    </optgroup>
<%else%>
    <table width='100%' cellspacing='0' cellpadding='0' border="0" class="responsive table table-bordered field-sortable">
        <tr id="tr_child_row_<%$row_id%>">
            <td class="row-num-child" width='3%'><%$dis_no%></td>
            <td width='25%'>
                <div class="">                                                                    
                    <input type="text" class="frm-size-large valid-variable" title="<%$this->lang->line('GENERIC_VARIABLES')%>" id="push_notify_variable_<%$row_id%>" name="push_notify_variable[<%$row_id%>]" value="<%$db_email_vars[k]['vVarName']%>">
                </div>
                <div>
                    <label id="push_notify_variable_<%$row_id%>Err" class="error"></label>
                </div>
            </td>
            <td width='50%'>
                <div style="float:left;width:52%">
                    <select class='chosen-select frm-size-full_width' name='push_notify_value[]' id='push_notify_value_<%$row_id%>' onchange='changeParameters(this)'>
                        <%if $field_arr|@is_array && $field_arr|@count gt 0 %>
                            <optgroup label="List Fields">
                                <%foreach from=$field_arr key=K item=V%>
                                    <option value='<%$V["name"]%>'><%$V['label']%></option>
                                <%/foreach%>
                            </optgroup>
                        <%/if%>
                        <optgroup label="Other">
                            <option selected="selected" value="Other">Other</option>
                        </optgroup>
                    </select>
                </div>
                <div style="float:left;width:44%;">
                    <input type="text" class="frm-size-full_width" id="push_notify_value_<%$row_id%>_Other" name='push_notify_value_other[<%$row_id%>]'>
                </div>
                <div>
                    <label id="push_notify_value_<%$row_id%>Err"></label>
                </div>
            </td>
            <td align="center" width='12%'>
                <div class="center">
                    <input type="checkbox" title="<%$this->lang->line('GENERIC_COMPULSORY')%>" class="regular-checkbox" id="push_notify_value_compulsory_<%$row_id%>" name="push_notify_compulsory[<%$row_id%>]" value="Yes">
                    <label for="push_notify_value_compulsory_<%$row_id%>">&nbsp;</label>
                </div>
            </td>
            <td align="center" width='10%'>
                <div class="controls center">                                                                    
                    <a class="tipR" href="javascript://" title="<%$this->lang->line('GENERIC_DELETE')%>" onclick="Project.modules.pushnotify.deletePushnotifyVariableRow('<%$row_id%>')">
                        <span class="icon12 icomoon-icon-remove"></span>
                    </a>
                </div>
            </td>
        </tr>
    </table>
<%/if%>