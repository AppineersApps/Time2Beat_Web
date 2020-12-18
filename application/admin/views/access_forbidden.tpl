<%if $this->input->get_post('iframe') eq 'true'%>
    <div>
        <h1 class="center" style="width:90%"><%$this->lang->line('GENERIC_FORBIDDEN')%></h1>
        <h2 class="center errormsg">
            <%if $err_message neq ''%>
                <%$err_message%>
            <%else%>
                You don't have permissions to access this page.
            <%/if%>
        </h2>
    </div>
<%else%>
    <div class="container-fluid">
        <div class="errorContainer">
            <div class="page-header">
                <h1 class="center">403 <small><%$this->lang->line('GENERIC_FORBIDDEN')%></small></h1>
                <h2 class="center">
                    <%if $err_message neq ''%>
                        <%$err_message%>
                    <%else%>
                            The page you are looking for has not been found.
                    <%/if%>
                </h2>
                <p>The page you are looking for might have been removed, had its name changed, or unavailable. </p>
            </div>
            <div class="error-link-back">
                <a href="javascript://" onclick="loadLastVisitedURL()" class="btn" style="margin-right:10px;"><span class="icon16 icomoon-icon-arrow-left-10"></span><%$this->lang->line('GENERIC_GO_BACK')%></a>
                <a href="javascript://" onclick="loadAdminDashboardPage()" class="btn"><span class="icon16 icomoon-icon-screen"></span><%$this->lang->line('GENERIC_SITEMAP')%></a>
                <!--<p>Please <a href="#">click here</a> to go back to our home page </p>-->
            </div>
        </div>
    </div>
<%/if%>