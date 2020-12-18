<div id="refer_tabs_height">
    <ul class="nav nav-tabs">
        <li <%if $func_name eq "index"%> class="active" <%/if%>>
            <a title="<%$this->lang->line('GENERIC_FULL_BACKUP')%>" 
                <%if $func_name eq "index"%> 
                    href="javascript://"
                <%else%> 
                    href="<%$admin_url%>#<%$mod_enc_url['backup_index']%>" 
                <%/if%>
                >
                <%$this->lang->line('GENERIC_FULL_BACKUP')%>
            </a>
        </li>
        <li <%if $func_name eq "table"%> class="active" <%/if%>>
            <a title="<%$this->lang->line('GENERIC_TABLE_BACKUP')%>" 
                <%if $func_name eq "table"%> 
                    href="javascript://" 
                <%else%> 
                    href="<%$admin_url%>#<%$mod_enc_url['backup_table_backup']%>" 
                <%/if%>
                >
                <%$this->lang->line('GENERIC_TABLE_BACKUP')%>
            </a>
        </li>
    </ul>            
</div>