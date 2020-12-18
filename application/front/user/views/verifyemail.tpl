<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title"><%$this->lang->line("FRONT_WELCOME")%></div>
    </div>
    <div class="panel-body">
        <div class="col-md-12">
            <%if $status eq 1 %>
                <h3 class="text-success"><%$this->lang->line("FRONT_EMAIL_VERIFIED_SUCCESSFULLY")%></h3>
            <%elseif $status eq 2 %>
                <h3 class="text-info"><%$this->lang->line("FRONT_EMAIL_ALREADY_VERIFIED")%></h3>
            <%else%>
                <h3 class="text-danger"><%$this->lang->line("FRONT_NO_ACCOUNT_LINKED_TO_EMIAL")%></h3>
            <%/if%>
        </div>
    </div>
</div>
