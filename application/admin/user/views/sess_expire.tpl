<div class="container-fluid">
    <div class="errorContainer">
        <div class="page-header">
            <h1 class="center">Session <small>expired</small></h1>
        </div>
        <h2 class="center errormsg">Your session is expired. Please login again.</h2>
        <div class="center">
            <a href="<%$login_entry_url%>?_=<%$smarty.now|date_format:'%Y%m%d%H%M%S'%>"  class="btn btn-default"><span class="icon16 icomoon-icon-enter"></span>Login here</a>
        </div>
    </div>
</div>