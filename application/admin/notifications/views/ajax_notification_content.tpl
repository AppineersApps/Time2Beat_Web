<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <h3>
            <div class="screen-title">
                <%$this->lang->line('GENERIC_VIEW')%> :: <%$this->lang->line('NOTIFICATIONS_NOTIFICATIONS')%> :: <%$data[0]['vSubject']%> 

            </div>
        </h3>
        <div class="header-right-btns">
            <input type="hidden" id="notification_log_id" name="notification_log_id" value="<%$data[0]['iExecutedNotificationId']%>" />
        </div>
    </div>
</div>
<div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
    <div id="ajax_qLoverlay"></div>
    <div id="ajax_qLbar"></div>
    <div class="top-frm-tab-layout" id="top_frm_tab_layout"></div>
    <div id="scrollable_content" class="scrollable-content top-block-spacing">
        <div style="width:98%;margin-bottom: 30px;margin-top: 20px;" class="frm-block-layout" >
            <textarea id="tNotificationContent" name="tNotificationContent" style='width:100%;min-height:300px;'  class='frm-size-medium elastic'><%$data[0]['tContent']%></textarea>
        </div>
        <div id="answers"></div>
    </div>
</div>
<div class="popup-footer frm-bot-btn  bot-btn-cen">
    <div class="action-btn-align">
        <button class="btn btn-info" id="resend"><%$this->lang->line('GENERIC_RESEND')%></button>
        <input value="Discard" name="ctrldiscard" type="button" class="btn" onclick="closeWindow()">
    </div>
</div>
<%$this->js->add_js('admin/forms/tinymce/tinymce.min.js','admin/admin/js_notification_content.js')%>