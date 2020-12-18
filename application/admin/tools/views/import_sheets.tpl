<table class="table table-bordered sheets-info" align="center">
    <thead>
        <tr><th>Tabs inside selected file</th></tr>
    </thead>
    <tbody>
        <%section name=i loop=$sheets%>
            <tr>
                <td>
                    <table class="table table-bordered drive-sheet-tab-header" align="center" width="100%">
                        <thead>
                            <tr>
                                <th>
                                    <input type="radio" class="regular-radio" name="sheetId" id="sheetId_<%$smarty.section.i.index%>" value="<%$smarty.section.i.index%>">
                                    <label for="sheetId_<%$smarty.section.i.index%>">&nbsp;<%$sheets[i].title%></label>
                                    <span class="expand-sheet-data"><a class="maximize collapse fetch-sheet-data select-sheet-data" href="javascript://" title="<%$this->lang->line('GENERIC_MINIMIZE')%>"><span class="icon14 cut-icon-plus"></span></a></span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <table class="table table-bordered" align="center" width="100%" id="sheet_block_<%$smarty.section.i.index%>" style="display:none;">
                        <%assign var="sheet_content" value=$sheets[i]['rows']%>
                        <%assign var="record_count" value=$sheets[i]['rows'][0]|@count%>
                        <%if $sheet_content|@is_array && $sheet_content|@count gt 0%>
                            <%section name=j loop=$sheet_content%>
                                <tr>
                                    <%section name=k loop=$record_count%>
                                        <td><%$sheet_content[j][k]%></td>
                                    <%/section%>
                                </tr>
                            <%/section%>
                        <%else%>
                            <tr>
                                <td class="errormsg">No data found.</td>
                            </tr>
                        <%/if%>
                    </table>
                </td>
            </tr>
        <%/section%>
        <tr>
            <td align="left">
                <input type="button" value="Select" id="select_btn" class="btn btn-primary">
            </td>
        </tr>
    </tbody>
</table>