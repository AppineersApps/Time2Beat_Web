<%if $pPage eq "true"%>
    <%$this->js->add_common_js("jquery/jquery-ui.min.js","admin/misc/hash/jquery.ba-hashchange.js","admin/misc/hash/jquery.ba-hashchange.js","admin/misc/navigate/ajax_navigate.js")%>
    <%$this->js->add_common_js("admin/bootstrap/bootstrap.js","admin/general/general.js","admin/admin/js_admin_general.js","admin/admin/js_admin_listing.js")%>
    <%$this->js->add_common_js("admin/misc/qtip/jquery.qtip.min.js","admin/misc/totop/jquery.ui.totop.min.js","admin/misc/cookie/jquery.cookie.js","admin/misc/mouse/jquery.mousewheel.js")%>
    <%$this->js->add_common_js("admin/validate/jquery.validate.js","admin/validate/addon.validation.js","admin/misc/pattern/jquery.ui.pattern.js", "admin/misc/textcase/change_text_case.js")%>
    <%$this->js->add_common_js("admin/forms/watermark/jquery.watermark.min.js","admin/printElement/jquery.printElement.js","admin/rating-master/jquery.raty.js")%>
    <%$this->js->add_common_js("admin/misc/touch/jquery.ui.touch-punch.js","admin/bootstrap/main.js","admin/theme/`$this->config->item('ADMIN_THEME_DISPLAY')`/theme.js","admin/global/global.js")%>
<%else%>
    <%$this->js->add_common_js("jquery/jquery-ui.min.js","admin/misc/hash/jquery.ba-hashchange.js","admin/misc/navigate/ajax_navigate.js")%>
    <%$this->js->add_common_js("admin/bootstrap/bootstrap.js","admin/general/general.js","admin/admin/js_admin_general.js","admin/admin/js_admin_listing.js","admin/admin/js_admin_add_form.js")%>
    <%$this->js->add_common_js("admin/admin/js_admin_grid.js","admin/admin/js_admin_backup_grid.js","admin/admin/js_admin_dash_board.js","admin/chosen/chosen.jquery.js")%>
    <%$this->js->add_common_js("admin/chosen/chosen.ajaxaddition.jquery.js","admin/misc/qtip/jquery.qtip.min.js")%>
    <%$this->js->add_common_js("admin/misc/totop/jquery.ui.totop.min.js","admin/misc/nicescroll/jquery.nicescroll.js","admin/misc/cookie/jquery.cookie.js","admin/misc/mouse/jquery.mousewheel.js")%>
    <%$this->js->add_common_js("admin/forms/maskedinput/jquery.maskedinput-1.3.1.min.js","admin/datepicker/jquery-ui-timepicker-addon.js")%>
    <%$this->js->add_common_js("admin/datepicker/date.js","admin/datepicker/moment.js","admin/datepicker/daterangepicker.js")%>
    <%$this->js->add_common_js("admin/datepicker/combodate.js","admin/jqGrid/i18n/grid.locale-en.js","admin/jqGrid/jquery.jqGrid.src.js")%>
    <%$this->js->add_common_js("admin/jqGrid/jquery.multiselect.min.js","admin/jqGrid/jquery.multiselect.filter.min.js","admin/jqGrid/jquery-jqgrid-formatter.js")%>
    <%$this->js->add_common_js("admin/validate/jquery.validate.js","admin/validate/addon.validation.js","admin/forms/elastic/jquery.elastic.js","admin/forms/watermark/jquery.watermark.min.js")%>
    <%$this->js->add_common_js("admin/forms/inputlimiter/jquery.inputlimiter.1.3.min.js","admin/misc/pattern/jquery.ui.pattern.js")%>
    <%$this->js->add_common_js("admin/forms/ajax-form/jquery.form.js","admin/colorpicker/colpick.js","admin/paginate/jquery.paginate.js")%>
    <%$this->js->add_common_js("admin/file-upload/jquery.fileupload.js","admin/autocomplete_token/jquery.tokeninput.js","admin/fullscreen/fullscreen-jquery.js","admin/x-editable/bootstrap-editable.js")%>
    <%$this->js->add_common_js("admin/misc/textcase/change_text_case.js","admin/fancybox/jquery.mousewheel-3.0.6.pack.js","admin/fancybox/jquery.fancybox.js")%>
    <%$this->js->add_common_js("admin/misc/pnotify/jquery.pnotify.min.js","admin/printElement/jquery.printElement.js","admin/rating-master/jquery.raty.js")%>
    <%$this->js->add_common_js("admin/flot/jquery.flot.js","admin/flot/jquery.flot.resize.js","admin/flot/jquery.flot.tooltip.js","admin/flot/jquery.flot.categories.js","admin/flot/jquery.flot.tickrotor.js")%>
    <%$this->js->add_common_js("admin/flot/jquery.flot.orderBars.js","admin/flot/jquery.flot.pie.js","admin/flot/jquery.flot.stack.js","admin/flot/jquery.flot.axislabels.js","admin/gridster/jquery.gridster.js")%>
    <%$this->js->add_common_js("admin/fileviewer/FileViewerPlugin.js", "admin/fileviewer/jquery.csv-0.71.min.js")%>
    <%$this->js->add_common_js("admin/mustache/mustache.min.js","admin/misc/hotkeys/hotkeys.js", "admin/admin/js_admin_shortcuts.js")%>
    <%$this->js->add_common_js("admin/event-source/EventSource.js","admin/misc/touch/jquery.ui.touch-punch.js","admin/initials/initial.js","toastr/toastr.min.js")%>    
    <%$this->js->add_common_js("admin/bootstrap/main.js","admin/theme/`$this->config->item('ADMIN_THEME_DISPLAY')`/theme.js","admin/global/global.js")%>    
<%/if%>
