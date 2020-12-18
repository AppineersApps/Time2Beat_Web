<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><%$this->systemsettings->getSettings('CPANEL_TITLE')%></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
        <base href="<%$admin_url%>">
        <%$this->css->add_css("admin/style.css","admin/icons.css","bootstrap/bootstrap.css","bootstrap/main.css","theme/`$this->config->item('ADMIN_THEME_DISPLAY')`/theme.css")%>
        <%$this->css->add_css("theme/`$this->config->item('ADMIN_THEME_DISPLAY')`/`$this->config->item('ADMIN_THEME_PATTERN')`","theme/`$this->config->item('ADMIN_THEME_CUSTOMIZE')`")%>
        <%$this->css->css_src()%>
        <%$this->js->add_js("admin/bootstrap/bootstrap.js","admin/misc/cookie/jquery.cookie.js","admin/misc/mouse/jquery.mousewheel.js")%>
        <script type="text/javascript">
            var admin_url = "<%$admin_url%>";
        </script>
    </head>
    <body>
        <div>
            <%include file=$include_script_template%>
        </div>
        <%$this->css->css_src()%>
        <%$this->js->js_src()%>
    </body>
</html>
