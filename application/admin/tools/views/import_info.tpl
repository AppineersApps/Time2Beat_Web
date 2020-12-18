<div class="content <%$rl_theme_arr['frm_gener_label_align']%>">
    <%if $success eq 1 && $data|@is_array && $data|@count gt 0%>
        <div class="form-row row-fluid">
            <div class="box form-child-table import-info-recs">
                <div class="title">
                    <h4>
                        <span class="icon12 icomoon-icon-equalizer-2"></span>
                        <span><%$title%></span>
                        <span class="errormsg import-error-msg" id="import_error_msg"></span>
                    </h4>
                    <a style="display:none;" class="minimize" href="javascript://"><%$this->lang->line('GENERIC_MINIMIZE')%></a>
                </div>
                <div class="content noPad">
                    <div class="import-data-container">
                        <table class="table table-bordered">
                            <thead> 
                                <tr>
                                    <%section name=i loop=$header%>
                                        <th class="import-info-width"><%$header[i]%></th>
                                    <%/section%>
                                </tr>
                            </thead>
                            <tbody>
                                <%section name=i loop=$data%>
                                <tr>
                                    <%section name=j loop=$columns%>
                                        <td class="import-info-width"><div class="import-info-cell"><%$data[i][$columns[j]]%></div></td>
                                    <%/section%>
                                </tr>
                                <%/section%>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <%elseif $success  eq 0%>
        <div class="errormsg" align="center"><%$message%></div>
    <%else%>
        <div class="errormsg" align="center">No records found.</div>
    <%/if%>
</div>
            