<%if $paging eq 'paging'%>
    <%if $file_found eq '1'%>
        <%section name=i loop=$query_log_data%>
            <tr>
                <td style="display:table-cell"><%$query_log_data[i]['query']%></td>
                <td><%$query_log_data[i]['time']%></td>
                <td><%$query_log_data[i]['ip']%></td>
                <td>
                    <%if $query_log_data[i]['mode'] eq 'cache'%>
                        <%$this->lang->line('GENERIC_YES')%>
                    <%else%>
                        <%$this->lang->line('GENERIC_NO')%>
                    <%/if%>
                </td>
            </tr>
        <%/section%>
    <%else%>
        <div class="errormsg" align="center" style="height:50px;"><%$this->lang->line('GENERIC_QUERY_LOG_FILE_NOT_FOUND')%>.!</div>
    <%/if%>
<%else%>
<div class="box" style="display:none">
    <input type="hidden" name="query_log_paging_count" id="query_log_paging_count" value=<%$log_files|@count%> />
    <div class="title">
        <h4>
            <%$this->lang->line('GENERIC_DATABASE_QUERY_LOG')%>
            <span id="query_log_paging" class="query-log-paging"></span>
                <span class="query-log-flush">
                    <%$this->lang->line('GENERIC_FLUSH')%> &nbsp;
                    <select name="logFlushCombo" id="logFlushCombo" class="log-flush-combo">
                        <option value="All"><%$this->lang->line('GENERIC_ALL')%></option>
                        <option value="First"><%$this->lang->line('GENERIC_FIRST')%></option>
                        <option value="Last"><%$this->lang->line('GENERIC_LAST')%></option>
                    </select>
                    <input type="text" name="logFlushPages" id="logFlushPages" class="log-flush-pages" style="display: none;" value="1"/>
                    <%$this->lang->line('GENERIC_PAGES')%> &nbsp;
                    <div class="btn btn-primary" onclick="logFlushLogPages()">
                        <%$this->lang->line('GENERIC_GO')%> 
                    </div>
                </span>
            <span class="db-minimize-log">
                <span class="min" title="<%$this->lang->line('GENERIC_HIDE_DB_QUERIES_LOG')%>"></span>
            </span>
        </h4>
    </div>
    <div align="center" class="query-log-loader" style="display:none;"><i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i></div>
    <div class="content noPad" style="display: block;">
        <%if $file_found eq '1'%>
        <table class="responsive table table-bordered display ">
            <thead>
                <tr>
                    <th style="display:table-cell"><%$this->lang->line('GENERIC_DB_QUERY')%></th>
                    <th width="15%"><%$this->lang->line('GENERIC_EXECUTION_TIME_MS')%></th>
                    <th width="15%"><%$this->lang->line('GENERIC_FROM_IP')%></th>
                    <th width="15%"><%$this->lang->line('GENERIC_FROM_CACHE')%></th>
                </tr>
            </thead>
            <tbody id="query_log_block">
                <%section name=i loop=$query_log_data%>
                    <tr>
                        <td style="display:table-cell"><%$query_log_data[i]['query']%></td>
                        <td><%$query_log_data[i]['time']%></td>
                        <td><%$query_log_data[i]['ip']%></td>
                        <td>
                            <%if $query_log_data[i]['mode'] eq 'cache'%>
                                <%$this->lang->line('GENERIC_YES')%>
                            <%else%>
                                <%$this->lang->line('GENERIC_NO')%>
                            <%/if%>
                        </td>
                    </tr>
                <%/section%>
            </tbody>
        </table>
        <%else%>
            <div class="errormsg" align="center" style="height:50px;"><%$this->lang->line('GENERIC_QUERY_LOG_FILE_NOT_FOUND')%>.!</div>
        <%/if%>
    </div>
</div>
<%/if%>

