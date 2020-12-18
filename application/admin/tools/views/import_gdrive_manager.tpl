<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%if $this->session->userdata('token')|@trim neq "" %>
    <%assign var="tokenEnable" value="Yes"%>
<%else%>
    <%assign var="tokenEnable" value="No"%>
<%/if%>
<%assign var="driveItems" value=["gdrive","dropbox"]%>
<div class="title" style="height:40px; line-height:40px;">
    <h4>&nbsp;
        <span>Cloud Drive</span>
    </h4>
</div>
<div class="drive-auth-container">
    <input type="hidden" name="tokenEnable" value="<%$tokenEnable%>">
    <input type="hidden" name="apiTypeHide" value="<%$apiType%>">
    <div class="drive-left-part">
        <ul>
            <%section name=k loop=$driveItems%>
                <li class="dlp-links <%if $apiType eq $driveItems[k]%>active<%/if%>"><a class="<%$driveItems[k]%>" onitem="<%$driveItems[k]%>"></a></li>
            <%/section%>
        </ul>
    </div>
    <div class="drive-right-part">
        <div class="drp-auth-block" onitem="dropbox" style="<%if $apiType neq 'dropbox'%>display:none;<%/if%>">
            <%if $dropbox_client_id eq '' || $dropbox_client_secret eq ''%>
                <%assign var="dropbox_auth_help" value="display:none;"%>
                <%assign var="dropbox_config_help" value=""%>
            <%else%>
                <%assign var="dropbox_auth_help" value=""%>
                <%assign var="dropbox_config_help" value="display:none;"%>
            <%/if%>
            <p style="<%$dropbox_config_help%>" id="dropbox_config_help">
                You must configure your Dropbox application client id and client secret details to view the documents. <a href="https://www.dropbox.com/developers" target="_blank">Click here</a><br>
                Please click on "OAuth Config" button to set client details. For more details <a href="https://www.dropbox.com/developers/reference/oauth-guide" target="_blank">click here</a>
            </p>
            <p style="<%$dropbox_auth_help%>" id="dropbox_auth_help">
                You must authenticate yourself with your Dropbox account to view the documents.<br>
                Please click on "Authenticate Dropbox" button and you'll be redirected to Dropbox Docs page to fetch your documents.
            </p>
            <div class="auth-btn-container">
                <span class="import-oauth-auth" id="dropbox_auth_span" style="<%$dropbox_auth_help%>">
                    <input type="button" class="btn btn-primary" value="Authenticate Dropbox" id="dropbox_auth_btn">
                </span>
                <span class="import-oauth-config">
                    <input type="button" class="btn btn-primary" value="OAuth Config" id="dropbox_auth_config">
                </span>
                <div class="clear"></div>
            </div>
            <div class="auth-config-container" id="dropbox_config_containter" style="display:none;">
                <div class="config-row">
                    <div class="config-left">Client ID</div>
                    <div class="config-right">
                        <input type="text" name="dropbox_client_id" id="dropbox_client_id" value="<%$dropbox_client_id%>" class="config-txt" />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="config-row">
                    <div class="config-left">Client Secret</div>
                    <div class="config-right">
                        <input type="text" name="dropbox_client_secret" id="dropbox_client_secret" value="<%$dropbox_client_secret%>" class="config-txt" />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="config-row">
                    <div class="config-left">Redirect URI</div>
                    <div class="config-right">
                        <span><%$dropbox_redirect_uri%></span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="config-row action">
                    <div class="config-left">&nbsp;</div>
                    <div class="config-right">
                        <input type="button" value="Save" name="save_dropbox_config" id="save_dropbox_config" class="btn btn-info">
                        &nbsp;
                        <input type="button" value="Discard" name="discard_dropbox_config" id="discard_dropbox_config" class="btn">
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <div class="drp-auth-block" onitem="gdrive" style="<%if $apiType eq 'dropbox'%>display:none;<%/if%>">
            <%if $gdrive_client_id eq '' || $gdrive_client_secret eq ''%>
                <%assign var="drive_auth_help" value="display:none;"%>
                <%assign var="drive_config_help" value=""%>
            <%else%>
                <%assign var="drive_auth_help" value=""%>
                <%assign var="drive_config_help" value="display:none;"%>
            <%/if%>
            <p style="<%$drive_config_help%>" id="drive_config_help">
                You must configure your Google application client id and client secret details to view the documents. <a href="http://code.google.com/apis/console" target="_blank">Click here</a><br>
                Please click on "OAuth Config" button to set client details. For more details <a href="https://developers.google.com/identity/protocols/OAuth2?hl=en" target="_blank">click here</a>
            </p>
            <p style="<%$drive_auth_help%>" id="drive_auth_help">
                You must authenticate yourself with your Google account to view the documents.<br>
                Please click on "Authenticate Google" button and you'll be redirected to Google Drive page to fetch your documents.
            </p>
            <div class="auth-btn-container">
                <span class="import-oauth-auth" id="drive_auth_span" style="<%$drive_auth_help%>">
                    <input type="button" class="btn btn-primary" value="Authenticate Google" id="drive_auth_btn">
                </span>
                <span class="import-oauth-config">
                    <input type="button" class="btn btn-primary" value="OAuth Config" id="drive_auth_config">
                </span>
                <div class="clear"></div>
            </div>
            <div class="auth-config-container" id="drive_config_containter" style="display:none;">
                <div class="config-row">
                    <div class="config-left">Client ID</div>
                    <div class="config-right">
                        <input type="text" name="gdrive_client_id" id="gdrive_client_id" value="<%$gdrive_client_id%>" class="config-txt" />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="config-row">
                    <div class="config-left">Client Secret</div>
                    <div class="config-right">
                        <input type="text" name="gdrive_client_secret" id="gdrive_client_secret" value="<%$gdrive_client_secret%>" class="config-txt" />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="config-row">
                    <div class="config-left">Redirect URI</div>
                    <div class="config-right">
                        <span><%$gdrive_redirect_uri%></span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="config-row action">
                    <div class="config-left">&nbsp;</div>
                    <div class="config-right">
                        <input type="button" value="Save" name="save_drive_config" id="save_drive_config" class="btn btn-info">
                        &nbsp;
                        <input type="button" value="Discard" name="discard_drive_config" id="discard_drive_config" class="btn">
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <%section name=k loop=$driveItems%>
            <div class="drp-change-area" onitem="<%$driveItems[k]%>">
                <a href="javascript://" class="drp-change-user">Sign in with a different account</a>
            </div>
        <%/section%>
        <div class="clear"></div>
        <%section name=k loop=$driveItems%>
            <div class="drp-content-area" onitem="<%$driveItems[k]%>">
                <div class="drp-content-block"></div>
            </div>
        <%/section%>
    </div>
</div>
<%javascript%>
    Project.modules.importcsv.initDriveJSEvents();
    Project.modules.importcsv.initGDrive();
    Project.modules.importcsv.initDropbox();
<%/javascript%>