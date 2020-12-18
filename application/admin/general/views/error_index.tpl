<div class="box" style="display:none">
    <div class="title">
        <h4>
            <%$this->lang->line('GENERIC_DATABASE_ERRORS_OCCURRED')%>
            <span class="error-minimize-log">
                <span class="min" title="<%$this->lang->line('GENERIC_HIDE_DB_QUERIES_LOG')%>"></span>
            </span>
        </h4>
    </div>
    <div class="content noPad" style="display: block;">
        <%if $file_found eq '1'%>
        <table class="responsive table table-bordered display ">
            <thead>
                <tr>
                    <th style="display:table-cell"><%$this->lang->line('GENERIC_DB_QUERY')%></th>
                    <th width="15%"><%$this->lang->line('GENERIC_FROM_IP')%></th>
                </tr>
            </thead>
            <tbody id="error_log_block">
                    <%include file=$error_log_file%>
            </tbody>
        </table>
        <%else%>
            <div class="errormsg" align="center" style="height:50px;"><%$this->lang->line('GENERIC_QUERY_LOG_FILE_NOT_FOUND')%>.!</div>
        <%/if%>
    </div>
</div>