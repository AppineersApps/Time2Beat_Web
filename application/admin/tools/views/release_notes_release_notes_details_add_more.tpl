<%section name=i loop=1%>
    <div id="div_child_row_<%$child_module_name%>_<%$row_index%>">
        <input type="hidden" name="child[release_notes_details][id][<%$row_index%>]" id="child_release_notes_details_id_<%$row_index%>" value="<%$child_id%>" />
        <input type="hidden" name="child[release_notes_details][enc_id][<%$row_index%>]" id="child_release_notes_details_enc_id_<%$row_index%>" value="<%$enc_child_id%>" />
        <input type="hidden" name="child[release_notes_details][mrnd_release_notes_id][<%$row_index%>]" id="child_release_notes_details_mrnd_release_notes_id_<%$row_index%>" value="<%$child_data[i]['mrnd_release_notes_id']%>"  class='ignore-valid ' />
        <input type="hidden" name="child[release_notes_details][mrnd_date_added][<%$row_index%>]" id="child_release_notes_details_mrnd_date_added_<%$row_index%>" value="<%$child_data[i]['mrnd_date_added']%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
        <input type="hidden" name="child[release_notes_details][mrnd_added_by][<%$row_index%>]" id="child_release_notes_details_mrnd_added_by_<%$row_index%>" value="<%$child_data[i]['mrnd_added_by']%>"  class='ignore-valid ' />
        <input type="hidden" name="child[release_notes_details][mrnd_date_updated][<%$row_index%>]" id="child_release_notes_details_mrnd_date_updated_<%$row_index%>" value="<%$child_data[i]['mrnd_date_updated']%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
        <input type="hidden" name="child[release_notes_details][mrnd_updated_by][<%$row_index%>]" id="child_release_notes_details_mrnd_updated_by_<%$row_index%>" value="<%$child_data[i]['mrnd_updated_by']%>"  class='ignore-valid ' />
        <%if $child_access_arr["actions"] eq 1%>
            <div class="col-del controls">
                <%if $child_access_arr["save"] eq 1%>
                    <%if $mode eq "Update"%>
                        <a onclick="saveChildModuleSingleData('<%$mod_enc_url.child_data_save%>', '<%$mod_enc_url.child_data_add%>', '<%$child_module_name%>', '<%$recMode%>', '<%$row_index%>', '<%$enc_child_id%>', 'No', '<%$mode%>')" href="javascript://" class="tip action-child-module-save" title="<%$this->lang->line('GENERIC_SAVE')%>">
                            <span class="icon14 icomoon-icon-disk"></span>
                        </a>
                    <%/if%>
                    <%/if%><%if $child_access_arr["delete"] eq 1%>
                    <%if $recMode eq "Update"%>
                        <a onclick="deleteChildModuleSingleData('<%$mod_enc_url.child_data_delete%>','<%$child_module_name%>', '<%$row_index%>','<%$enc_child_id%>')" href="javascript://" class="tip action-child-module-delete" title="<%$this->lang->line('GENERIC_DELETE')%>" >
                            <span class="icon16 icomoon-icon-remove"></span>
                        </a>
                    <%else%>
                        <a onclick="deleteChildModuleRow('<%$mod_enc_url.child_data_delete%>','<%$child_module_name%>', '<%$row_index%>')" href="javascript://" class="tip action-child-module-delete" title="<%$this->lang->line('GENERIC_DELETE')%>" >
                            <span class="icon16 icomoon-icon-remove"></span>
                        </a>
                    <%/if%>
                <%/if%>
            </div>
        <%/if%>
        <div class="column-view-parent form-row row-fluid tab-focus-element">
            <div class="two-block-view" id="ch_release_notes_details_cc_sh_mrnd_title">
                <label class="form-label span3">
                    <%$child_conf_arr['form_config']['mrnd_title']['label_lang']%>  <em>*</em>
                </label> 
                <div class="form-right-div  ">
                    <input type="text" placeholder="" value="<%$child_data[i]['mrnd_title']|@htmlentities%>" name="child[release_notes_details][mrnd_title][<%$row_index%>]" id="child_release_notes_details_mrnd_title_<%$row_index%>" title="<%$child_conf_arr['form_config']['mrnd_title']['label_lang']%>"  class='frm-size-full_width'  />  
                </div>
                <div class="error-msg-form " >
                    <label class='error' id='child_release_notes_details_mrnd_title_<%$row_index%>Err'></label>
                </div>
            </div>
            <div class="two-block-view" id="ch_release_notes_details_cc_sh_mrnd_version_status">
                <label class="form-label span3">
                    <%$child_conf_arr['form_config']['mrnd_version_status']['label_lang']%>  <em>*</em>
                </label> 
                <div class="form-right-div  ">
                    <%assign var="opt_selected" value=$child_data[i]['mrnd_version_status']%>
                    <%$this->dropdown->display("child_release_notes_details_mrnd_version_status_<%$row_index%>","child[release_notes_details][mrnd_version_status][<%$row_index%>]","  title='<%$child_conf_arr['form_config']['mrnd_version_status']['label_lang']%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'RELEASE_NOTES_DETAILS_TYPE')%>'  ","|||","",$opt_selected,"child_release_notes_details_mrnd_version_status_$row_index")%>  
                </div>
                <div class="error-msg-form " >
                    <label class='error' id='child_release_notes_details_mrnd_version_status_<%$row_index%>Err'></label>
                </div>
            </div>
        </div>
        <div class="column-view-parent form-row row-fluid tab-focus-element">
            <div class="two-block-view" id="ch_release_notes_details_cc_sh_mrnd_description">
                <label class="form-label span3">
                    <%$child_conf_arr['form_config']['mrnd_description']['label_lang']%>  
                </label> 
                <div class="form-right-div frm-editor-layout  ">
                    <textarea name="child[release_notes_details][mrnd_description][<%$row_index%>]" id="child_release_notes_details_mrnd_description_<%$row_index%>" title="<%$child_conf_arr['form_config']['mrnd_description']['label_lang']%>"  style='width:80%;'  class='frm-size-full_width frm-editor-medium'  ><%$child_data[i]['mrnd_description']%></textarea>  
                </div>
                <div class="error-msg-form " >
                    <label class='error' id='child_release_notes_details_mrnd_description_<%$row_index%>Err'></label>
                </div>
            </div>
            <div class="two-block-view">
                <label class="form-label span3">
                </label> 
                <div class="form-right-div  ">
                </div>
                <div class="error-msg-form " >
                </div>
            </div>
        </div>
    </div>
<%javascript%>
    <%if $child_auto_arr[$row_index]|@is_array && $child_auto_arr[$row_index]|@count gt 0%>
        var $child_chosen_auto_complete_url = admin_url+""+$("#childModuleChosenURL_<%$child_module_name%>").val()+"?";
        setTimeout(function(){
            <%foreach name=i from=$child_auto_arr[$row_index] item=v key=k%>
                if($("#child_<%$child_module_name%>_<%$k%>_<%$row_index%>").is("select")){
                    $("#child_<%$child_module_name%>_<%$k%>_<%$row_index%>").ajaxChosen({
                        dataType: "json",
                        type: "POST",
                        url: $child_chosen_auto_complete_url+"&unique_name=<%$k%>&mode=<%$mod_enc_mode[$recMode]%>&id=<%$enc_child_id%>"
                        },{
                        loadingImg: admin_image_url+"chosen-loading.gif"
                    });
                }
            <%/foreach%>
        }, 500);
    <%/if%>
<%/javascript%>
<%/section%>
<hr class="hr-line">
<%javascript%>
var google_map_json = $.parseJSON('<%$google_map_arr|@json_encode%>');
function initChildRenderJSScript(){
Project.modules.release_notes.childEvents("release_notes_details", "#div_child_row_<%$child_module_name%>_<%$row_index%>");
callGoogleMapEvents();
}
<%/javascript%>