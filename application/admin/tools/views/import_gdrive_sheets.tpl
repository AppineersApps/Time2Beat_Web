<%if $params.type eq 'docs' || $params.type eq ''%>
    <table class="table table-bordered" align="center">
        <thead>
            <tr><th>Available Documents</th></tr>
        </thead>
        <tbody>
            <%if $data|@is_array && $data|@count gt 0%>
                <%section name=i loop=$data%>
                <tr>
                    <td>
                        <div class="drive-sheet-radio">
                            <input type="radio" class="regular-radio" id="<%$smarty.section.i.index%>docId" name="docId" value="<%$data[i].docId%>">
                            <label for="<%$smarty.section.i.index%>docId"></label>
                        </div>
                        <div class="drive-sheet-label">
                            <label for="<%$smarty.section.i.index%>docId"> &nbsp;<%$data[i].docName%></label>
                        </div>
                    </td>
                </tr>
                <%/section%>
                <tr>
                    <td align="left">
                        <input type="button" class="btn btn-info" value="Continue" id="gd_next_btn">
                    </td>
                </tr>
            <%else%>
                <tr>
                    <td align="center">
                        <div class='errormsg'>Documents not found.</div>
                    </td>
                </tr>
            <%/if%>
        </tbody>
    </table>
<%elseif $params.type eq 'sheets'%>
    <input type="hidden" name="docFile" id="docFile" value="<%$params.docFile%>">
    <table class="table table-bordered" align="center">
        <thead>
            <tr><th>Tabs inside selected file</th></tr>
        </thead>
        <tbody>
            <%assign var="gd_sheets" value=$data['sheets']%>
            <%section name=i loop=$gd_sheets%>
                <tr>
                    <td>
                        <table class="table table-bordered drive-sheet-tab-header" align="center" width="100%">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="drive-sheet-radio">
                                            <input type="radio" class="regular-radio" name="sheetId" id="sheetId_<%$smarty.section.i.index%>" value="<%$smarty.section.i.index%>">
                                            <label for="sheetId_<%$smarty.section.i.index%>"></label>
                                        </div>
                                        <div class="drive-sheet-label">
                                            <label for="sheetId_<%$smarty.section.i.index%>">&nbsp;<%$gd_sheets[i].title%></label>
                                        </div>
                                        <span class="expand-sheet-data"><a class="maximize collapse fetch-sheet-data" href="javascript://" title="<%$this->lang->line('GENERIC_MINIMIZE')%>"><span class="icon14 cut-icon-plus"></span></a></span>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                        <table class="table table-bordered" align="center" width="100%" id="gd_sheet_block_<%$smarty.section.i.index%>" style="display:none;">
                            <%assign var="gd_sheet_content" value=$gd_sheets[i]['rows']%>
                            <%if $gd_sheet_content|@is_array && $gd_sheet_content|@count gt 0%>
                                <%section name=j loop=$gd_sheet_content%>
                                    <tr>
                                        <%section name=k loop=$gd_sheet_content[j]%>
                                            <td><%$gd_sheet_content[j][k]%></td>
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
                    <input type="button" value="Back" id="gd_back_btn" class="btn btn-info">
                    <%if $gd_sheets|@is_array && $gd_sheets|@count gt 0%>
                        <input type="button" value="Select" id="gd_submit_btn" class="btn btn-primary">
                    <%/if%>
                </td>
            </tr>
        </tbody>
    </table>
<%/if%>