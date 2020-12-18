<%if $sent_type eq 'Groups'%>
    <label class="form-label span3">Select User(s) <em>*</em> :</label> 
    <div class="form-right-div" id="user_dropdown">
        <%if $email_arr|@is_array && $email_arr|@count gt 0%>
            <select id="vUser" name="vUser[]" multiple="multiple" data-placeholder="Select User(s)" class="chosen-select frm-size-large">
                <%section name="i" loop=$email_arr%>
                <option value="<%$email_arr[i].vEmail%>"><%$email_arr[i].vEmail%></option>
                <%/section%>
            </select>
            <a class="tipR" style="text-decoration: none;" href="javascript://" title="Select All" aria-chosen-select='vUser' aria-chzn-type='select'>
                <span class='silk-icon-arrow-left arrow-image'></span>
            </a>
            <a class="tipR" style="text-decoration: none;" href="javascript://" title="Deselect All" aria-chosen-select='vUser' aria-chzn-type='deselect'>
                <span class="silk-icon-arrow-right arrow-image" style="display: none;"></span>
            </a>
        <%else%>
            <span class="error">No users present in this group</span>
        <%/if%>
    </div>
    <div class="error-msg-form" ><label class="error" id="vUserErr"></label></div>
<%elseif $sent_type eq 'Modules'%>
    <label class="form-label span3">Select Email Field<em>*</em> :</label> 
    <div class="form-right-div" id="user_dropdown">
        <%if $email_arr|@is_array && $email_arr|@count gt 0%>
            <select id="vFieldName" name="vFieldName" data-placeholder="Select Email Field" class="chosen-select frm-size-large">
            <%foreach from=$email_arr key=key item=val%>
                <option value="<%$key%>"><%$val%></option>
            <%/foreach%>
            </select>
        <%else%>
            <span class="errormsg">No email fields present in this module listing. Please add any one field to send mail..!</span>
        <%/if%>
    </div>
    <div class="error-msg-form" ><label class="error" id="vFieldNameErr"></label></div>
<%else%>
    <label class="form-label span3">Enter Email Address <em>*</em> :</label> 
    <div class="form-right-div">
        <textarea title="Please Enter From Name" id="vEmailAddress" name="vEmailAddress" class="elastic frm-size-large"></textarea>
        <a class="tipR" style="text-decoration: none;" href="javascript://" title="Enter multiple email address with (,) seperated.">
            <span class="icomoon-icon-help"></span>
        </a>
    </div>
    <div class="error-msg-form" ><label class="error" id="vEmailAddressErr"></label></div>
<%/if%>
