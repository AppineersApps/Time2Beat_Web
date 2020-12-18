<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<%assign var="app_cache_active" value=$this->general->getAppCacheStatus()%>
<html xmlns="http://www.w3.org/1999/xhtml" <%if $app_cache_active eq "Yes"%> manifest="<%$this->config->item('site_url')%><%$this->config->item('ADMIN_APPCACHE_FILE')%>" <%/if%> > 
      <head>
        <meta charset="utf-8" />
        <base href="<%$admin_url%>" />
        <title><%$this->config->item('CPANEL_TITLE')%></title>
        <link rel="shortcut icon" href="<%$this->general->getCompanyFavIconURL()%>" />
        <meta http-equiv="content-type" content="text/cache-manifest; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
        <%include file="admin_template_js.tpl" tplmode="main"%>
        <%include file="admin_include_css.tpl"%>
        <%$this->css->css_common_src("common")%>
        <%$this->general->getJSLanguageLables()%>
        <%include file="admin_include_js.tpl"%>
        <%$this->js->js_common_src("common")%>
        <script type='text/javascript'>
            var cus_enc_url_json = $.parseJSON('<%$this->general->getCustomEncryptURL()%>');
            var cus_enc_mode_json = $.parseJSON('<%$this->general->getCustomEncryptMode()%>');
            var cus_shortcuts_json = $.parseJSON('<%$this->general->getShortcutsList()%>');
        </script>
    </head>
    <body>
        <div id="script_overlay"></div>
        <div id="script_progress" class="script-progress" style="display:none;"></div>
        <div id="script_download" class="circular-item script-download" title="Site Average Load Time" style="display:none;">
            <span class="icon icomoon-icon-busy"></span>
            <input type="text" value="Loading....." class="script-download-input" id="script_download_input" data-width="200" data-displayprevious=true data-readOnly=true/>
        </div>
        <div id="qLoverlay"></div>
        <div id="qLbar" class="<%$rl_theme_arr['theme_loader_class']%>"></div>
        <div id='grid_wrapper'>
            <div id="ajax-navigate">
                <div id="trtop_template">
                    <div>
                        <%if $this->config->item("NAVIGATION_BAR") eq 'Top'%>
                        <%include file="top/top.tpl"%>
                        <%else%>
                        <%include file="top/top_left.tpl"%>
                        <%/if%>
                    </div>
                </div>
                <div id="trmid_template">
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
                    <%/if%>    
                    <div class="errorbox-position" id="var_msg_cnt" style="<%$msg_box_style%>">
                        <div class="closebtn-errorbox <%$msg_box_close%>" id="closebtn_errorbox">
                            <a href="javascript://" onclick="Project.closeMessage();"><button class="close" type="button">×</button></a>
                        </div>
                        <div class="content-errorbox alert <%$msg_box_class%>" id="err_msg_cnt"><%$msg_box_text%></div>
                    </div>
                    <div id="main_content_div" class="main-content-div">
                        <div class="clearfix content-loader <%$this->config->item('ADMIN_THEME_PATTERN_BODY')%>" id="content">
                            <%if $include_script_template|@file_exists%>
                            <%include file=$include_script_template%>
                            <%else%>
                            <div id="ajax_content_div" class="ajax-content-div box gradient">
                                <div id="ajax_qLoverlay"></div>
                                <div id="ajax_qLbar"></div>
                                <div id="scrollable_content" class="scrollable-content"></div>
                            </div>
                            <%/if%>
                        </div>
                        <div class="clearfix content-loader cslide <%$this->config->item('ADMIN_THEME_PATTERN_BODY')%>" id="content_slide"></div>
                    </div>
                </div>
                <div id="trbot_template">
                    <div>
                        <%include file="bottom/bottom.tpl"%>
                    </div>
                </div>
            </div>
            <div class="navigator hide" id="module_navigator">
                <div class="navigator-midd">
                    <div class="spotlight-heading">
                        <div class="left-title">
                            <h4 ><%$this->lang->line('GENERIC_SPOTLIGHT_SEARCH')%></h4>
                        </div>
                        <div class="right-title">
                            <input type="checkbox" value="Yes" name="spotlight-newtab" id="spotlight-newtab" class="regular-checkbox" />
                            <label for="spotlight-newtab" class="frm-column-layout spotlight-checkbox">&nbsp;</label>
                            <label for="spotlight-newtab" class="frm-column-layout"><%$this->lang->line('GENERIC_OPEN_IN_NEW_TAB')%></label>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="navigator-row">
                        <input type="text" id="navigation_search" name="search" placeholder="<%$this->lang->line('GENERIC_SEARCH_MODULE_NAME')%>" class="autocompleter-node" autocomplete="off" />
                        <span class="spotlight-search-loader loader-hide"><i class="fa fa-spinner fa-spin fa-18" aria-hidden="true"></i></span>    
                    </div>    
                </div>
            </div>
            <script type='text/javascript'>
                $(document).ready(function () {
                    $.fn.raty.defaults.path = '<%$rl_theme_arr["gen_rating_master"]%>';
                    if ($(".chosen-select").length) {
                        initializejQueryChosenEvents();
                    }
                    Project.checkmsg();
                });
            </script>
        </div>
        <div id="ad_navig_log" class="ad-navig-log"></div>
        <div id="db_query_log" class="db-query-log"></div>
        <div id="db_error_log" class="db-error-log"></div>
    </body>
</html>