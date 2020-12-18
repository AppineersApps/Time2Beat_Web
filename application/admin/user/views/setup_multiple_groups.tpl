<div class="popup-frm-setup">
    <div class="popup-left-block" id="popup-left-block">
        <!-- Top Detail View Block -->
        <div class="popup-left-container">
            <div class="popup-left-main-title">
                <div class="popup-left-main-title-icon"><span><i class="fa fa-users fa-4x" style="color:white" ></i></span></div>
                <span class="popup-left-main-title-content"><%$this->general->processMessageLabel("GENERIC_MULTIPLE_GROUP")%></span>
            </div>
            <div class="popup-label-box">
                <div class="label-box-row">
                    <span class="label-box-row-title"><%$this->general->processMessageLabel("GENERIC_NAME")%></span>
                    <em class="label-box-row-content"><%$name%></em>
                </div>
                <div class="label-box-row">
                    <span class="label-box-row-title"><%$this->general->processMessageLabel("GENERIC_EMAIL")%></span>
                    <em class="label-box-row-content"><%$email%></em>
                </div>
                <div class="label-box-row">
                    <span class="label-box-row-title"><%$this->general->processMessageLabel("GENERIC_GROUP")%></span>
                    <em class="label-box-row-content"><%$curr_group%></em>
                </div>
            </div>
        </div>
    </div>
    <div id="ajax_content_div" class="ajax-content-div top-frm-spacing popup-right-block">
        <div id="ajax_qLoverlay"></div>
        <div id="ajax_qLbar"></div>
        <div id="scrollable_content" class="scrollable-content">
            <div id="changepassword" class="frm-elem-block frm-stand-view">
                <form name="multigroupsfrm" id="multigroupsfrm" action="" method="post"  enctype="multipart/form-data">
                    <input type="hidden" name="id" id="enc_id" value="<%$enc_id%>" />
                    <div>
                        <div class="group-checkbox-block">
                            <%foreach from=$groups key=k item=v%>
                            <%if $k%10 eq 0%>  <div class="group-checkbox-div"> <%/if%>
                            <div class="group-checkbox">
                                <input type="checkbox" value="<%$v['iGroupId']%>" name="multi_group[]" id="multi_group_<%$v['iGroupId']%>" title="<%$v['vGroupName']%>" <%if $v['checked'] eq Yes%> checked=true <%/if%> <%if $v['disabled'] eq Yes%> disabled=true <%/if%> class='regular-checkbox'  />
                                <label for="multi_group_<%$v['iGroupId']%>" class="frm-horizon-row frm-column-layout">&nbsp;</label>
                                <label for="multi_group_<%$v['iGroupId']%>" class="frm-horizon-row frm-column-layout"><%$v['vGroupName']%></label>
                            </div>
                            <%if ($k%10 eq 9) OR ($groups|@count eq $k+1)%> </div> <%/if%>
                            <%/foreach%>
                        </div>
                        <div class="clear"></div>
                        <div class="popup-right-footer">
                            <div class="multi-groups-action-btn">
                                <input value="<%$this->lang->line('GENERIC_SAVE')%>" name="ctrladd" type="submit" class='btn btn-info'onclick="return submitForm()"/>&nbsp;&nbsp; 
                                <input value="<%$this->lang->line('GENERIC_DISCARD')%>" name="ctrldiscard" type="button" class='btn' onclick="closeWindow()">
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </form>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<%javascript%>
    function closeWindow(){
        parent.$.fancybox.close();
    }
    function submitForm(){
        var enc_id = $('#enc_id').val();
        
        var selected_roles = [];
        $('input[name="multi_group[]"]:checked').map(function(_, el) {
            selected_roles.push($(el).val());
        }).get();
        var curr_role = $('input[name="multi_group[]"]:disabled').val();
        var sel_roles = selected_roles.filter(function(e) { return e !== curr_role })
        
        Project.show_adaxloading_div();
        $.ajax({
            type: "POST",
            url: admin_url + cus_enc_url_json.update_multi_groups,
            data: {roles:  sel_roles, enc_id: enc_id},
            success: function(response)
            {
                Project.hide_adaxloading_div();
                var result = parseJSONString(response);
                if (result.success == '1') {
                    parent.$.fancybox.close();
                    parent.Project.setMessage(result.message, result.success);
                    parent.location.reload(true);
                }else{
                    Project.setMessage(result.message, result.success);
                }

            }
        });
        return false;
    }
<%/javascript%>