<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <base href="<%$this->config->item('site_url')%>" />
        <title><%if $meta_title neq ''%><%$meta_title%><%else%><%$this->systemsettings->getSettings('META_TITLE')%><%/if%></title>
        <link rel="shortcut icon" href="<%$this->general->getCompanyFavIconURL()%>" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
        <meta name="description" content="<%if $meta_description neq ''%><%$meta_description%><%else%><%$this->systemsettings->getSettings('META_DESCRIPTION')%><%/if%>" />
        <meta name="keywords" content="<%if $meta_keyword neq ''%><%$meta_keyword%><%else%><%$this->systemsettings->getSettings('META_KEYWORD')%><%/if%>" />
        <%if $this->systemsettings->getSettings('META_OTHER') neq ''%>
            <%$this->systemsettings->getSettings('META_OTHER')%>
        <%/if%>    
        <%$this->css->add_css("bootstrap3/bootstrap.min.css", "style.css")%>
        <%$this->css->css_src()%>
        <script type='text/javascript'>
            var site_url = '<%$this->config->item("site_url")%>';
        </script>
    </head>
    <body class="<%$page_html_class%>">
        <div id="top-container">
            <!--top-part start here-->
            <%include file="top/top.tpl"%>
            <!--top-part End here-->
        </div>
        <div id="midd-container" class="container <%$page_html_class%>">
            <!-- middle part start here-->
            <%include file=$include_script_template%>
            <!-- middle part end here-->
        </div>
        <div id="bott-container">
            <!--bottom link start here-->
            <%include file="bottom/footer.tpl"%>
            <!--bottom part End here-->
        </div>
        <%if $this->systemsettings->getSettings('GOOGLE_ANALYTICS')|@trim neq ''%>
            <script type="text/javascript">
                <%$this->systemsettings->getSettings('GOOGLE_ANALYTICS')%>
            </script>
        <%/if%>
        <%$this->css->css_src()%>
        <%$this->js->js_src()%>
    </body>
</html>