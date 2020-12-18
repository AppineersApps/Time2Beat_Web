<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
    <head>
        <%strip%>
        <meta charset="utf-8" />
        <base href="<%$this->config->item('site_url')%>" />
        <%/strip%>
        <title><%if $meta_info|is_array && $meta_info['title'] neq ''%><%$meta_info['title']%><%else%><%$this->systemsettings->getSettings('META_TITLE')%><%/if%></title>
        <link rel="shortcut icon" href="<%$this->general->getCompanyFavIconURL()%>" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
        <meta name="description" content="<%if $meta_info|is_array && $meta_info['description'] neq ''%><%$meta_info['description']%><%else%><%$this->systemsettings->getSettings('META_DESCRIPTION')%><%/if%>" />
        <meta name="keywords" content="<%if $meta_info|is_array && $meta_info['keywords'] neq ''%><%$meta_info['keywords']%><%else%><%$this->systemsettings->getSettings('META_KEYWORD')%><%/if%>" />
        <%if $meta_info|is_array && $meta_info['other']|is_array%>
            <%assign var="meta_other" value=$meta_info['other']%>
            <%section name=i loop=$meta_other%>
                <meta <%$meta_other[i]['key']%>="<%$meta_other[i]['value']%>" content="<%$meta_other[i]['content']%>" />
            <%/section%>
        <%else%>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <%if $this->systemsettings->getSettings('META_OTHER') neq ''%>
                <%$this->systemsettings->getSettings('META_OTHER')%>
            <%/if%>
        <%/if%>
        <%$this->css->add_css("bootstrap3/bootstrap.min.css","font-awesome/icons.css")%>
        <%$this->css->add_css("font-awesome/font-awesome.min.css","toastr/toastr.min.css","style.css","media.css")%>
        <%$this->css->css_src()%>
        <script type='text/javascript'>
            var site_url = '<%$this->config->item("site_url")%>';
        </script>
        <%$this->general->getJSLanguageLables()%>
        <%$this->js->add_js("bootstrap3/bootstrap.min.js")%>
        <%$this->js->add_js("validate/jquery.validate.min.js","validate/additional-methods.min.js","common.js")%>
        <%$this->js->add_js("blockui/jquery.blockUI.min.js","bootbox/bootbox.min.js","toastr/toastr.min.js")%>
    </head>
    <body class="<%$page_html_class%>">
        <%assign var="msg_box_style" value="display:none;"%>
        <%assign var="msg_box_class" value=""%>
        <%assign var="msg_box_close" value=""%>
        <%assign var="msg_box_text" value=""%>
        <%if $this->session->flashdata('success') neq ''%>
            <%assign var="msg_box_style" value="display:block;"%>
            <%assign var="msg_box_class" value="alert-success"%>
            <%assign var="msg_box_close" value="success"%>
            <%assign var="msg_box_text" value=$this->session->flashdata('success')%>
        <%elseif $this->session->flashdata('failure') neq ''%>   
            <%assign var="msg_box_style" value="display:block;"%>
            <%assign var="msg_box_class" value="alert-error"%>
            <%assign var="msg_box_close" value="error"%>
            <%assign var="msg_box_text" value=$this->session->flashdata('failure')%>
        <%elseif $this->session->flashdata('warning') neq ''%>   
            <%assign var="msg_box_style" value="display:block;"%>
            <%assign var="msg_box_class" value="alert-warning"%>
            <%assign var="msg_box_close" value="warning"%>
            <%assign var="msg_box_text" value=$this->session->flashdata('warning')%>
        <%elseif $this->session->flashdata('info') neq ''%>   
            <%assign var="msg_box_style" value="display:block;"%>
            <%assign var="msg_box_class" value="alert-info"%>
            <%assign var="msg_box_close" value="info"%>
            <%assign var="msg_box_text" value=$this->session->flashdata('info')%>
        <%/if%>
        <div class="errorbox-position" id="var_msg_cnt" style="<%$msg_box_style%>">
            <div class="closebtn-errorbox <%$msg_box_close%>" id="closebtn_errorbox">
                <a href="javascript:void(0);" onClick="Project.closeMessage();"><button class="close" type="button">×</button></a>
            </div>
            <div class="content-errorbox alert <%$msg_box_class%>" id="err_msg_cnt"><%$msg_box_text%></div>
        </div>
        <div id="top-container">
            <!--top-part start here-->
            <%include file="top/top.tpl"%>
            <!--top-part End here-->
        </div>
        <div id="midd-container" class="container">
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
        <script type='text/javascript'>
            $(document).ready(function () {
                Project.init();
            });
        </script>
    </body>
</html>