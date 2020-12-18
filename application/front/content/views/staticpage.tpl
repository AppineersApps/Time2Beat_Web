<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title"><%$page_title%></div>
    </div>
    <div class="panel-body">
        <div class="col-md-12">
            <%if $display_lang eq 'en'%>
                <%include file="static/`$page_code`.tpl"%>
            <%else%>
                <%$page_content%>
            <%/if%>
        </div>
    </div>
</div>