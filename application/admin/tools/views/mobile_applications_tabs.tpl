<ul class="nav nav-tabs">
    <li <%if $module_name eq "mobile_applications"%> class="active" <%/if%>>
        <a title="<%$this->lang->line('GENERIC_EDIT')%> <%$this->lang->line('MOBILE_APPLICATIONS_MOBILE_APPLICATIONS')%>" 
            <%if $module_name eq "mobile_applications"%> 
                href="javascript://"
            <%else%> 
                href="<%$admin_url%>#<%$this->general->getAdminEncodeURL('tools/mobile_applications/add')%>|mode|<%$mod_enc_mode['Update']%>|id|<%$this->general->getAdminEncodeURL($parID)%>" 
            <%/if%>
            >
            <%$this->lang->line('GENERIC_EDIT')%> <%$this->lang->line('MOBILE_APPLICATIONS_MOBILE_APPLICATIONS')%>
        </a>
    </li>
    <li <%if $module_name eq "application_versions"%> class="active" <%/if%>>
        <a title="<%$this->lang->line('MOBILE_APPLICATIONS_APPLICATION_VERSIONS')%> <%$this->lang->line('GENERIC_LIST')%>" 
            <%if $module_name eq "application_versions"%> 
                href="javascript://"
            <%elseif $module_name eq "mobile_applications"%> 
                href="<%$admin_url%>#<%$this->general->getAdminEncodeURL('tools/application_versions/index')%>|parMod|<%$this->general->getAdminEncodeURL('mobile_applications')%>|parID|<%$this->general->getAdminEncodeURL($data['iApplicationMasterId'])%>"
            <%else%> 
                href="<%$admin_url%>#<%$this->general->getAdminEncodeURL('tools/application_versions/index')%>|parMod|<%$this->general->getAdminEncodeURL('mobile_applications')%>|parID|<%$this->general->getAdminEncodeURL($parID)%>" 
            <%/if%>
            >
            <%$this->lang->line('MOBILE_APPLICATIONS_APPLICATION_VERSIONS')%>
        </a>
    </li>
</ul>            