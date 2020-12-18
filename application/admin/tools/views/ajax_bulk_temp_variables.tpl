<div class="form-row row-fluid">
    <div class="box">
        <div class="content noPad">
            <table id="tbl_child_module" class="responsive table table-bordered">
                <thead>    
                    <tr>
                        <th width="5%">#</th>
                        <th width="25%"><%$this->lang->line('GENERIC_VARIABLES')%> <em>*</em></th>
                        <th width="25%"><%$this->lang->line('GENERIC_DESCRIPTION')%></th>
                        <th><%$this->lang->line('GENERIC_SELECT_FIELDS')%></th>
                    </tr>
                </thead>
                <tbody id="add_child_module">
                    <%if $email_temp_arr|@is_array && $email_temp_arr|@count gt 0 %>
                        <%foreach from=$email_temp_arr key=emailKey item=emailVal%>
                        <%assign var="innerkey" value=$emailKey+1%>
                            <tr id="tr_child_row_<%$innerkey%>">
                                <td class="row-num-child"><%$innerkey%></td>
                                <td><label><%$emailVal['vVarName']%></label></td>
                                <td><label><%$emailVal['vVarDesc']%></label></td>
                                <td>
                                    <div>
                                        <div style="float:left;width:52%">
                                            <select class='chosen-select frm-size-full_width' name='vParameterFieldName[<%$emailVal["vVarName"]|trim:"#"%>]' id='vParameterFieldName_<%$innerkey%>' onchange='changeParameters(this)'>
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
                                            <input type="text" class="frm-size-full_width" id="vParameterFieldName_<%$innerkey%>_Other" name='vParameterFieldNameOther[<%$emailVal["vVarName"]|trim:"#"%>]'>
                                        </div>
                                    </div>
                                    <div class="error-msg-form" ><label class="error" id="vParameterFieldNameErr<%$innerkey%>"></label></div>
                                </td>
                            </tr>
                        <%/foreach%>
                    <%/if%>
                </tbody>
            </table>
        </div>
    </div>
</div>