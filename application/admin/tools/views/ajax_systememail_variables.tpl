<table width='100%' cellspacing='0' cellpadding='0' border="0" class="responsive table table-bordered  field-sortable">
    <tr id="tr_child_row_<%$row_id%>">
        <td class="row-num-child"  width='3%'><%$dis_no%></td>
        <td width='40%'>
            <div class="">
                <input type="text" class="frm-size-large valid-variable" title="Variable" id="system_email_variable_<%$row_id%>" name="system_email_variable[]" value="">
            </div>
            <div>
                <label id="system_email_variable_<%$row_id%>Err" class="error"></label>
            </div>
        </td>
        <td width='40%'>
            <div class="">
                <input type="text" class="frm-size-large" title="description" id="system_email_description_<%$row_id%>" name="system_email_description[]" value="">
            </div>
            <div>
                <label id="system_email_description_<%$row_id%>Err" class="error"></label>
            </div>
        </td>
        <td align="center" width='17%'>
            <div class="controls center">
                <a href="javascript://" title="Sort Fields" class="field-handle">
                    <span class="icon13 icomoon-icon-move"></span>
                </a>
                &nbsp;
                <a class="tipR" href="javascript://" onclick="Project.modules.systememails.deleteSystemEmailVariableRow('<%$row_id%>')" title="Delete">
                    <span class="icon12 icomoon-icon-remove"></span>
                </a>
            </div>
        </td>
    </tr>
</table>