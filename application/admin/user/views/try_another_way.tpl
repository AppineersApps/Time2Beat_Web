<div class="loginContainer login-form">
    <div class="loginbox-border">
        <div>
            <div id="login_div">
                <div class="login-headbg"><%$this->general->processMessageLabel("GENERIC_TRY_ANOTHER_WAY_TO_SIGN_IN")%><p></p></div>
                <p class="heading-username"><%$username%></p>
                <div class="try-another-block">
                    <%foreach from=$options key=k item=v%>
                        <div class="try-another">
                            <a class="try-another-options" href="<%$try_another_url%>?auth_type=<%$k%>"><%$v%></a>
                        </div>
                    <%/foreach%>
                </div>
            </div>
        </div>
    </div>
</div>


                
                
                
                