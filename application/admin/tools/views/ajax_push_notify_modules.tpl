<%if $sent_type eq 'Modules'%>
    <label class="form-label span3"><%$this->lang->line('GENERIC_PUSH_NOTIFY_SELECT_DEVICE_TOKEN')%><em>*</em> :</label> 
    <div class="form-right-div" id="user_dropdown">
        <select id="vFieldName" name="vFieldName" data-placeholder="<%$this->lang->line('GENERIC_PUSH_NOTIFY_SELECT_DEVICE_TOKEN')%>" class="chosen-select frm-size-large">
            <%if $email_arr|@is_array && $email_arr|@count gt 0 %>
                <%foreach from=$email_arr key=key item=val%>
                    <option value="<%$key%>"><%$val%></option>
                <%/foreach%>
            <%/if%>
        </select>
    </div>
    <div class="error-msg-form" ><label class="error" id="vFieldNameErr"></label></div>
<%else%>
    <label class="form-label span3"><%$this->lang->line('GENERIC_PUSH_NOTIFY_ENTER_DEVICE_ID')%> <em>*</em> :</label> 
    <div class="form-right-div">
        <textarea title="<%$this->lang->line('GENERIC_PUSH_NOTIFY_ENTER_DEVICE_ID')%>" id="iDeviceId" name="iDeviceId" class="elastic frm-size-large"></textarea>
        <a class="tipR" style="text-decoration: none;" href="javascript://" oldtitle="<%$this->lang->line('GENERIC_PUSH_NOTIFY_HELP_DEVICE_ID')%>" title="<%$this->lang->line('GENERIC_PUSH_NOTIFY_HELP_DEVICE_ID')%>" aria-describedby="ui-tooltip-2">
            <span class="icomoon-icon-help"></span>
        </a>
    </div>
    <div class="error-msg-form" ><label class="error" id="iDeviceIdErr"></label></div>
<%/if%>
