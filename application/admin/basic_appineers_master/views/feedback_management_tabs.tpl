<ul class="nav nav-tabs module-tab-container">
    <li <%if $module_name eq "feedback_management"%> class="active" <%/if%>>
        <a class="tab-item item-feedback_management" 
        <%if $mode eq "Add" && $module_name eq "feedback_management"%>
            title="<%$this->lang->line('GENERIC_ADD')%> <%$this->lang->line('FEEDBACK_MANAGEMENT_FEEDBACK_MANAGEMENT')%>"
        <%else%>
            title="<%$this->lang->line('GENERIC_EDIT')%> <%$this->lang->line('FEEDBACK_MANAGEMENT_FEEDBACK_MANAGEMENT')%>"
        <%/if%>
        <%if $module_name eq "feedback_management"%> 
            href="javascript://"
        <%else%> 
            href="<%$admin_url%>#<%$this->general->getAdminEncodeURL('basic_appineers_master/feedback_management/add')%>|mode|<%$mod_enc_mode['Update']%>|id|<%$this->general->getAdminEncodeURL($parID)%>" 
        <%/if%>
        >
        <%if $mode eq "Add" && $module_name eq "feedback_management"%>
            <%$this->lang->line('GENERIC_ADD')%> <%$this->lang->line('FEEDBACK_MANAGEMENT_FEEDBACK_MANAGEMENT')%>
        <%else%>
            <%$this->lang->line('GENERIC_EDIT')%> <%$this->lang->line('FEEDBACK_MANAGEMENT_FEEDBACK_MANAGEMENT')%>
        <%/if%>
    </a>
</li>
<li <%if $module_name eq "query_images"%> class="active" <%/if%>>
    <a class="tab-item item-query_images"  title="<%$this->lang->line('FEEDBACK_MANAGEMENT_QUERY_IMAGES')%> <%$this->lang->line('GENERIC_LIST')%>" 
        <%if $module_name eq "query_images"%> 
            href="javascript://"
        <%elseif $module_name eq "feedback_management"%> 
            <%if $mode eq "Update"%>
                href="<%$admin_url%>#<%$this->general->getAdminEncodeURL('basic_appineers_master/query_images/index')%>|parMod|<%$this->general->getAdminEncodeURL('feedback_management')%>|parID|<%$this->general->getAdminEncodeURL($data['iUserQueryId'])%>"
            <%else%>
                href="javascript://" aria-disabled="true" 
            <%/if%>                    
        <%else%> 
            href="<%$admin_url%>#<%$this->general->getAdminEncodeURL('basic_appineers_master/query_images/index')%>|parMod|<%$this->general->getAdminEncodeURL('feedback_management')%>|parID|<%$this->general->getAdminEncodeURL($parID)%>" 
        <%/if%>
        >
        <%$this->lang->line('FEEDBACK_MANAGEMENT_QUERY_IMAGES')%>
    </a>
</li>
</ul>            