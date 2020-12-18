<%$this->js->add_js("login.js")%>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title"><%$heading%>Try Another Way</div>
    </div>
    <div class="panel-body">
        <div class="col-md-12">
            <div class="container">
                <%foreach from=$ren_arr['options'] key=k item=v%>
                <div class="try-options">
                    <a class='btn try-btn' href='two-factor.html?type=<%$k%>'><%$v%></a>
                </div>
                <%/foreach%>
            </div>
        </div>
    </div>
</div>
