<%assign var="logo_file_url" value=$this->general->getCompanyLogoURL()%>
<div class="top-model-view logo container-fluid navbar">
    <a href="<%$this->config->item('admin_url')%>" class="brand">
        <%if $logo_file_url neq ''%>
            <img alt="<%$this->config->item('COMPANY_NAME')%>" class="admin-logo-top" src="<%$logo_file_url%>" title="<%$this->config->item('COMPANY_NAME')%>">
        <%else%>
            <div class='brand-logo-icon'></div>
        <%/if%>
    </a>
</div>
<!-- <div class="toprightarea">
    <div class="date-right">
        <%assign var="now_date_time" value=$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"%>
        <span><%$this->general->dateTimeSystemFormat($now_date_time)%></span>
    </div>
</div> -->