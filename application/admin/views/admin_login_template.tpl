<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <base href="<%$admin_url%>" />
        <title><%$this->systemsettings->getSettings('CPANEL_TITLE')%></title>
        <link rel="shortcut icon" href="<%$this->general->getCompanyFavIconURL()%>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="pragma" content="no-cache" />
        <%$this->css->add_css("forms/validate.css","admin/style.css","admin/icons.css","admin/font-awesome.css","bootstrap/bootstrap.css","misc/jquery.ui.pattern.css","bootstrap/main.css")%>
        <%$this->css->add_css("theme/`$this->config->item('ADMIN_THEME_DISPLAY')`/theme.css","theme/`$this->config->item('ADMIN_THEME_DISPLAY')`/`$this->config->item('ADMIN_THEME_PATTERN')`","theme/`$this->config->item('ADMIN_THEME_CUSTOMIZE')`")%>
        <%$this->css->add_css("global/global.css")%>
        <%$this->css->css_src("login")%>
        <%$this->js->add_js("admin/basic/jquery-ui-1.9.2.min.js", "admin/bootstrap/bootstrap.js","admin/misc/cookie/jquery.cookie.js","admin/misc/mouse/jquery.mousewheel.js")%>
        <%$this->js->add_js("admin/validate/jquery.validate.js","admin/misc/pattern/jquery.ui.pattern.js")%>
        <%$this->js->add_js("admin/global/global.js")%>
        <script type="text/javascript">
            var site_url = "<%$this->config->item('site_url')%>", admin_url = "<%$this->config->item('admin_url')%>";
            var style_url = "<%$this->config->item('css_url')%>", admin_style_url = "<%$this->config->item('admin_style_url')%>";
            var admin_js_url = "<%$this->config->item('admin_js_url')%>", admin_image_url = "<%$this->config->item('admin_images_url')%>";
            var admin_spinner_class = "<%$rl_theme_arr['theme_spinner_class']%>";
            var js_lang_label = {
                GENERIC_FORGOT_PASSWORD_USERNAME_ERR : "<%$this->general->parseTPLMessage('GENERIC_FORGOT_PASSWORD_USERNAME_ERR')%>",
                GENERIC_LOGIN_USERNAME_ERR : "<%$this->general->parseTPLMessage('GENERIC_LOGIN_USERNAME_ERR')%>",
                GENERIC_LOGIN_PASSWORD_ERR : "<%$this->general->parseTPLMessage('GENERIC_LOGIN_PASSWORD_ERR')%>",
                GENERIC_RESET_PASSWORD_ERR : "<%$this->general->parseTPLMessage('GENERIC_RESET_PASSWORD_ERR')%>",
                GENERIC_RESET_RETYPE_PASSWORD_ERR : "<%$this->general->parseTPLMessage('GENERIC_RESET_RETYPE_PASSWORD_ERR')%>",
                GENERIC_RESET_RETYPE_SAME_ERR : "<%$this->general->parseTPLMessage('GENERIC_RESET_RETYPE_NOT_MATCH_ERR')%>",
                GENERIC_RESET_SECURITY_CODE_ERR : "<%$this->general->parseTPLMessage('GENERIC_RESET_SECURITY_CODE_ERR')%>"
            }
        </script>
    </head>
    <body>
        <div class="top-bg <%$this->config->item('ADMIN_THEME_PATTERN_HEAD')%>">
            <%include file="top/top_login.tpl"%>
        </div>
        <div class="login-main-page custom-login-bg">
            <%assign var="error_box" value="display:none;"%>
            <%assign var="error_class" value=""%>
            <%assign var="error_close" value=""%>
            <%assign var="message_box" value=""%>
            <%if $this->session->flashdata('success') neq ''%>
                <%assign var="error_class" value="alert-success"%>
                <%assign var="error_close" value="success"%>
                <%assign var="error_box" value="display:block;"%>
                <%assign var="message_box" value=$this->session->flashdata('success')%>
            <%elseif $this->session->flashdata('failure') neq ''%>   
                <%assign var="error_class" value="alert-error"%>
                <%assign var="error_close" value="error"%>
                <%assign var="error_box" value="display:block;"%>
                <%assign var="message_box" value=$this->session->flashdata('failure')%>
            <%/if%> 
            <div class="errorbox-position" id="var_msg_cnt" style="<%$error_box%>">
                <div class="closebtn-errorbox <%$error_close%>" id="closebtn_errorbox">
                    <a href="javascript://" onClick="Project.closeMessage();"><button class="close" type="button">×</button></a>
                </div>
                <div class="content-errorbox alert <%$error_class%>" id="err_msg_cnt"><%$message_box%></div>
            </div>            
            <div class="content-login" id="content_login" class="<%$this->config->item('ADMIN_THEME_PATTERN_BODY')%>">
                <%include file=$include_script_template%>
            </div>
        </div>
        <div class="login-bottom-page">
            <div>
                <%include file="bottom/bottom.tpl"%>
            </div>
        </div>
        <%$this->js->js_src("login")%>
        <script type='text/javascript'>
            $(document).ready(function() {
                Project.checkmsg();
            });
        </script>
    </body>
</html>
