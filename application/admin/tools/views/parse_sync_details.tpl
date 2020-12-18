<div class="content">
    <div class="form-row row-fluid">
        <div class="box form-child-table import-info-recs">
            <div class="title">
                <h4>
                    <span class="icon12 icomoon-icon-equalizer-2"></span>
                    <span><%$this->lang->line('GENERIC_PARSE_DETAILS')%></span>
                    <span class="errormsg import-error-msg" id="import_error_msg"></span>
                </h4>
            </div>
            <div class="content noPad">
                <div class="import-data-container">
                    <%if $file_data['table_total_count']|is_array && $file_data['table_total_count']|@count gt 0%>
                        <table class="table table-bordered">
                            <thead> 
                                <tr>
                                    <th width='3%'>S.No</th>
                                    <th width='30%'><%$this->lang->line('GENERIC_TABLE_NAME')%></th>
                                    <th width='12%'><div align="center"><%$this->lang->line('GENERIC_INSERTED_COUNT')%></div></th>
                                    <th width='12%'><div align="center"><%$this->lang->line('GENERIC_UPDATED_COUNT')%></div></th>
                                    <th width='12%'><div align="center"><%$this->lang->line('GENERIC_TOTAL_RECORD_COUNT')%></div></th>
                                    <th width='21%'><div align="center"><%$this->lang->line('GENERIC_SYNC_STATUS')%></div></th>
                                </tr>
                            </thead>    
                            <tbody>
                                <%assign var="dis_no" value=1%>
                                <%foreach from=$file_data['table_total_count'] key=K item=V%>
                                <tr>
                                    <td class="row-num-child" width='3%'><%$dis_no++%></td>
                                    <td width='30%'><%$K%></td>
                                    <td width='12%'><div align="center"><%$file_data['table_insert_count'][$K]|intval%></div></td>
                                    <td width='12%'><div align="center"><%$file_data['table_update_count'][$K]|intval%></div></td>
                                    <td width='12%'><div align="center"><%$file_data['table_total_count'][$K]|intval%></div></td>
                                    <td width='21%'>
                                        <div align="center">
                                            <%if $file_data['error_msg_arr'][$K]|trim eq ""%>
                                            Processed
                                            <%else%>
                                            <%$file_data['error_msg_arr'][$K]%>
                                            <%/if%>
                                        </div>
                                    </td>
                                </tr>
                                <%/foreach%>
                            </tbody>
                        </table>     
                    <%else%>
                        <div align="center" class="errormsg">No details available.</div>
                    <%/if%>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .errormsg{color:#d45d57;}
    .successmsg{color:#198263;}
    .warningmsg{color:#a08226;}
</style>