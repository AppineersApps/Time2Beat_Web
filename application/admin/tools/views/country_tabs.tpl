<ul class="nav nav-tabs module-tab-container">
    <li <%if $module_name eq "country"%> class="active" <%/if%>>
        <a class="tab-item item-country" 
        <%if $mode eq "Add" && $module_name eq "country"%>
            title="<%$this->lang->line('GENERIC_ADD')%> <%$this->lang->line('COUNTRY_COUNTRY')%>"
        <%else%>
            title="<%$this->lang->line('GENERIC_EDIT')%> <%$this->lang->line('COUNTRY_COUNTRY')%>"
        <%/if%>
        <%if $module_name eq "country"%> 
            href="javascript://"
        <%else%> 
            href="<%$admin_url%>#<%$this->general->getAdminEncodeURL('tools/country/add')%>|mode|<%$mod_enc_mode['Update']%>|id|<%$this->general->getAdminEncodeURL($parID)%>" 
        <%/if%>
        >
        <%if $mode eq "Add" && $module_name eq "country"%>
            <%$this->lang->line('GENERIC_ADD')%> <%$this->lang->line('COUNTRY_COUNTRY')%>
        <%else%>
            <%$this->lang->line('GENERIC_EDIT')%> <%$this->lang->line('COUNTRY_COUNTRY')%>
        <%/if%>
    </a>
</li>
<li <%if $module_name eq "state"%> class="active" <%/if%>>
    <a class="tab-item item-state"  title="<%$this->lang->line('COUNTRY_STATE')%> <%$this->lang->line('GENERIC_LIST')%>" 
        <%if $module_name eq "state"%> 
            href="javascript://"
        <%elseif $module_name eq "country"%> 
            <%if $mode eq "Update"%>
                href="<%$admin_url%>#<%$this->general->getAdminEncodeURL('tools/state/index')%>|parMod|<%$this->general->getAdminEncodeURL('country')%>|parID|<%$this->general->getAdminEncodeURL($data['iCountryId'])%>"
            <%else%>
                href="javascript://" aria-disabled="true" 
            <%/if%>                    
        <%else%> 
            href="<%$admin_url%>#<%$this->general->getAdminEncodeURL('tools/state/index')%>|parMod|<%$this->general->getAdminEncodeURL('country')%>|parID|<%$this->general->getAdminEncodeURL($parID)%>" 
        <%/if%>
        >
        <%$this->lang->line('COUNTRY_STATE')%>
    </a>
</li>
</ul>            