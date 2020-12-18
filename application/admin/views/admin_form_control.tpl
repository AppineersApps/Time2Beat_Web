<%if $controls_allow eq false%>
    <div class="clear">&nbsp;</div>
<%/if%>
<div class="action-btn-align" id="action_btn_container">
    <%if $mode eq 'Update'%>
        <%if $update_allow eq true%>
            <input value="<%$this->lang->line('GENERIC_UPDATE')%>" name="ctrlupdate" type="submit" id="frmbtn_update" class='btn btn-info'/>&nbsp;&nbsp;
        <%/if%>
        <%if $delete_allow eq true%>
            <input value="<%$this->lang->line('GENERIC_DELETE')%>" name="ctrldelete" type="button" id="frmbtn_delete" class='btn btn-danger' onclick="return deleteAdminRecordData('<%$enc_id%>', '<%$mod_enc_url.index%>','<%$mod_enc_url.inline_edit_action%>', '<%$extra_qstr%>', '<%$extra_hstr%>');" />&nbsp;&nbsp;
        <%/if%>
    <%else%>
    <input value="<%$this->lang->line('GENERIC_SAVE')%>" name="ctrladd" type="submit" id="frmbtn_add" class='btn btn-info'/>&nbsp;&nbsp;
    <%/if%>
    <%if $discard_allow eq true%>
        <input value="<%$this->lang->line('GENERIC_DISCARD')%>" name="ctrldiscard" type="button" id="frmbtn_discard" class='btn' onclick="return loadAdminModuleListing('<%$mod_enc_url.index%>', '<%$extra_hstr%>')">
    <%/if%>
</div>


