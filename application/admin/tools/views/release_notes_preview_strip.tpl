<div class="headingfix">
    <!-- Top Header Block -->
    <div class="heading" id="top_heading_fix">
        <!-- Top Strip Title Block -->
        <h3>
            <div class="screen-title">
                <%$this->lang->line('GENERIC_VIEW')%> :: <%$this->lang->line('RELEASE_NOTES_RELEASE_NOTES')%>
            </div>        
        </h3>
        <!-- Top Strip Dropdown Block -->
        <div class="header-right-btns">
            <!-- BackLink Icon -->
            <a hijacked="yes" class="btn btn-info notes-switch-btn" href="<%$this->config->item('admin_url')%>#<%$this->general->getAdminEncodeURL('tools/release_notes/index')%>" title="<%$this->lang->line('GENERIC_MANAGE_NOTES')%>"><%$this->lang->line('GENERIC_MANAGE_NOTES')%></a>
        </div>
    </div>
</div>  