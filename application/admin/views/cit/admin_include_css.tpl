<%if $pPage eq "true"%>
    <%$this->css->add_common_css("admin/style.css","admin/icons.css","admin/font-awesome.css","bootstrap/bootstrap.css","bootstrap/bootstrap-responsive.css","misc/jquery.ui.pattern.css")%>
    <%$this->css->add_common_css("jqueryui/jquery-ui-1.9.2.custom.min.css","forms/validate.css","misc/jquery.qtip.css","rating-master/jquery.raty.css","bootstrap/main.css")%>
    <%$this->css->add_common_css("theme/`$this->config->item('ADMIN_THEME_DISPLAY')`/theme.css","theme/`$this->config->item('ADMIN_THEME_DISPLAY')`/`$this->config->item('ADMIN_THEME_PATTERN')`")%>
    <%$this->css->add_common_css("theme/`$this->config->item('ADMIN_THEME_CUSTOMIZE')`","admin/cform_generate.css","global/global.css","custom/custom.css")%>
<%else%>
    <%$this->css->add_common_css("admin/style.css","admin/icons.css","admin/font-awesome.css","bootstrap/bootstrap.css","bootstrap/bootstrap-responsive.css","misc/jquery.ui.pattern.css")%>
    <%$this->css->add_common_css("jqueryui/jquery-ui-1.9.2.custom.min.css","chosen/chosen.css","jqGrid/jquery.multiselect.css","jqGrid/jquery.multiselect.filter.css","jqGrid/ui.jqgrid.css")%>
    <%$this->css->add_common_css("datepicker/jquery.ui.datepicker.css","datepicker/jquery-ui-timepicker-addon.css","datepicker/daterangepicker.css")%>
    <%$this->css->add_common_css("codemirror/codemirror.css","forms/validate.css","forms/jquery.inputlimiter.css","misc/jquery.qtip.css")%>
    <%$this->css->add_common_css("misc/jquery.pnotify.default.css","stuhover/stuhover.css","x-editable/bootstrap-editable.css")%>
    <%$this->css->add_common_css("colorpicker/colpick.css", "paginate/jquery.paginate.css","rating-master/jquery.raty.css","autocomplete_token/token-input.css")%>
    <%$this->css->add_common_css("autocomplete_token/token-input-facebook.css","autocomplete_token/token-input-mac.css","autocomplete_token/token-input-simple.css")%>
    <%$this->css->add_common_css("fancybox/jquery.fancybox.css","gridster/jquery.gridster.css","toastr/toastr.min.css","bootstrap/main.css")%>
    <%$this->css->add_common_css("theme/`$this->config->item('ADMIN_THEME_DISPLAY')`/theme.css","theme/`$this->config->item('ADMIN_THEME_DISPLAY')`/`$this->config->item('ADMIN_THEME_PATTERN')`")%>
    <%$this->css->add_common_css("theme/`$this->config->item('ADMIN_THEME_CUSTOMIZE')`","admin/cform_generate.css","global/global.css","custom/custom.css")%>
<%/if%>