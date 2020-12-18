<div class="headingfix">
    <!-- Top Header Block -->
    <div class="heading" id="top_heading_fix">
		<!-- Top Strip Title Block -->
        <h3>
            <div class="screen-title">
                <%$this->lang->line('GENERIC_LISTING')%> :: <%$this->lang->line('SHORTCUTS_SHORTCUTS')%>
            </div>        
        </h3>
		<!-- Top Strip Dropdown Block -->
        <div class="header-right-btns">
            <a hijacked="yes" class="btn btn-info notes-switch-btn" href="<%$this->config->item('admin_url')%>#<%$this->general->getAdminEncodeURL('tools/shortcuts/preview')%>" title="<%$this->lang->line('GENERIC_PRIEVIEW_SHORTCUTS')%>"><%$this->lang->line('GENERIC_PRIEVIEW_SHORTCUTS')%></a>
        </div>
    </div>
</div>    