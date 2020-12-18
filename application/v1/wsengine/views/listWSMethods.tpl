<%$this->css->add_css("ws_validate.css")%>
<%$this->js->add_js("crypto-md5.js","application_ws.js")%>

<script type="text/javascript">
    var api_key = '<%$this->config->item("API_KEY")%>';
    var api_secret = '<%$this->config->item("API_SECRET")%>';
    var ws_base = "<%$ws_url%>";
</script>

<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">Welcome to API Console</div>
    </div>
    <div class="panel-body">
        <%foreach name="j" from=$all_methods key=key item=item%>    
            <div class="col-md-12 api-block">
                <div class="method-name">
                    <%$smarty.foreach.j.iteration%>. <%$item.title%>
                </div>
                <div class="inputparams">
                    <form action="<%$key%>?ws_debug=1" method="post" class="form-horizontal ws">
                        <%assign var="ws_params" value=$item.params%>
                        <%section name="i" loop=$ws_params%>
                            <%if $ws_params[i] neq ''%>
                                <div class="form-group">
                                    <label class="col-xs-1 control-label">
                                        <%$ws_params[i]%>
                                    </label>
                                    <div class="col-xs-4">
                                        <input class="form-control" id="<%$ws_params[i]%>" name="<%$ws_params[i]%>" value="">          
                                    </div>
                                </div>
                            <%/if%>
                        <%/section%>  
                        <div class="form-group">
                            <label class="col-xs-1 control-label"></label>
                            <div class="col-xs-10">
                                <button type="submit" class="btn btn-default">Send Request</button>
                            </div>
                        </div>
                    </form>
                    <pre class="code"></pre>
                    <iframe class="text"></iframe>
                </div>
            </div>
        <%/foreach%>
    </div>
</div>


