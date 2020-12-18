<%$this->css->add_css("ns_validate.css")%>
<%$this->js->add_js("crypto-md5.js","application_ns.js")%>
<script type="text/javascript">
        var api_key = '<%$this->config->item("API_KEY")%>';
        var api_secret = '<%$this->config->item("API_SECRET")%>';     
        var ns_base = "<%$ns_url%>";
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">Welcome to Notification Console</div>
    </div>
    <div class="panel-body">
        <%foreach name="j" from=$all_methods key=key item=item%>    
            <div class="col-md-12 api-block">
                <div class="method-name">
                    <%$smarty.foreach.j.iteration%>. <%$item.title%>
                </div>
                <div class="inputparams">
                    <form action="<%$key%>?ns_debug=1" method="post" class="form-horizontal ns">
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