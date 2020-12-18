<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <base href="<%$admin_url%>" />
        <title><%$this->config->item('CPANEL_TITLE')%></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <%include file="admin_template_js.tpl" tplmode="frame"%>
        <%include file="admin_include_css.tpl"%>
        <%if $pPage eq "true"%>
            <%$this->css->css_common_src("print")%>
        <%else%>
            <%$this->css->css_common_src("common")%>
        <%/if%>
        <%$this->general->getJSLanguageLables()%>
        <%include file="admin_include_js.tpl"%>
        <%if $pPage eq "true"%>
            <%$this->js->js_common_src("print")%>
        <%else%>
            <%$this->js->js_common_src("common")%>
        <%/if%>
        <script type='text/javascript'>
            var cus_enc_url_json = $.parseJSON('<%$this->general->getCustomEncryptURL()%>');
            var cus_enc_mode_json = $.parseJSON('<%$this->general->getCustomEncryptMode()%>');
            var cus_shortcuts_json = $.parseJSON('<%$this->general->getShortcutsList()%>');
         </script>
    </head>
    <body class="admin-fancy-body">
        <div id='grid_wrapper' class="grid-fancy-wrapper">
            <div id="ajax-navigate">
                <div id="trmid_template">
                    <%assign var="error_class" value=""%>
                    <%assign var="style_val" value="display:none;"%>
                    <%if $this->session->flashdata('success') neq ''%>
                    <%assign var="error_class" value="alert-success"%>
                    <%assign var="style_val" value=""%>
                    <%elseif $this->session->flashdata('failure') neq ''%>   
                    <%assign var="error_class" value="alert-error"%>
                    <%assign var="style_val" value=""%>
                    <%/if%>    
                    <div class="errorbox-position" id="var_msg_cnt" style="<%$style_val%>">
                        <div class="closebtn-errorbox">
                            <a href="javascript:void(0);" onclick="Project.closeMessage();"><button class="close" type="button">×</button></a>
                        </div>
                        <div class="content-errorbox alert <%$error_class%>" id="err_msg_cnt">
                            <%if $this->session->flashdata('success') neq ''%>
                            <%$this->session->flashdata('success')%>
                            <%/if%>   
                            <%if $this->session->flashdata('failure') neq ''%>   
                            <%$this->session->flashdata('failure')%>
                            <%/if%>
                        </div>
                    </div>
                    <div id="main_content_div" class="main-content-div">
                        <div class="clearfix content-loader <%$this->config->item('ADMIN_THEME_PATTERN_BODY')%>" id="content">
                            <%include file=$include_script_template%>
                        </div>
                        <div class="clearfix content-loader cslide <%$this->config->item('ADMIN_THEME_PATTERN_BODY')%>" id="content_slide">

                        </div>
                    </div>
                </div>
            </div>
            <%$this->css->css_src()%>
            <%$this->js->js_src()%>
        </div>
    </body>
</html>