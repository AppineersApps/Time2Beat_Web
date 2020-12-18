<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Internal Server Error</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
        <base href="<%$admin_url%>">
        <%$this->css->add_css("admin/icons.css","bootstrap/bootstrap.css","bootstrap/main.css", "style.css")%>
        <%$this->css->css_src()%>
    </head>
    <body>
        <div>
            <div class="container-fluid connection-container">
                <div class="error-container">
                    <div class="page-header">
                        <h1 class="center">500 <small>Internal Server Error</small></h1>
                    </div>
                    <h2 class="center errormsg">Opps, Something went wrong. We are fixing it.</h2>
                    <h2 class = "center">Please come back in a while</h2>
                </div>
            </div>         
        </div>
    </body>
</html>