<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <body>
        <div id="top_panel_info">
            <%include file="admin_template_js.tpl" tplmode="cache"%>
            <div>
                <%if $this->config->item("NAVIGATION_BAR") eq 'Top'%>
                    <%include file="top/top.tpl"%>
                <%else%>
                    <%include file="top/top_left.tpl"%>
                <%/if%>
            </div>
        </div>
        <div id="bot_panel_info">
            <div>
                <%include file="bottom/bottom.tpl"%>
            </div>
        </div>
    </body>
</html>