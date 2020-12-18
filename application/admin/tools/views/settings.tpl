<%assign var="upload_event_arr" value=[]%>
<%assign var="editor_event_arr" value=[]%>
<%assign var="upload_event_str" value=""%>
<%assign var="editor_event_str" value=""%>
<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class='custom-settings-container'>
    <%include file="settings_strip.tpl"%>
    <div id="ajax_content_div" class="ajax-content-div top-frm-spacing settings-class">
        <div id="ajax_qLoverlay"></div>
        <div id="ajax_qLbar"></div>
        <div class="frm-elem-block">
            <form id="frmsettingslist" name="frmsettingslist" method="post" action="<%$admin_url%><%$action_url%>">
                <input type="hidden" name="mode" value="Update" />
                <input type="hidden" name="type" value="<%$type%>" />
                <%if $db_total|@is_array && $db_total|@count gt 0%>            
                    <div style="width:98%" class="frm-block-layout pad-calc-container">
                    <%assign var=db_group value=$db_total%>
                        <div class="box">
                            <div class="title"><h4><span><%$type%></span></h4></div>
                            <div class="content clearfix">
                            <%foreach $db_group as $key => $value%>
                                <%assign var=db_res value=$value%>
                                <%if $group_count gt 1%>
                                    <fieldset class="settings-fieldset-border">
                                    <legend class="settings-legend-border"><%$key%></legend>                                        
                                <%elseif !$db_res|is_array || $db_res|count eq 0%>
                                    <%assign var=db_res value=$db_group['General']%>
                                <%/if%>
                                <%section name="i" loop=$db_res%>
                                    <%assign var='fieldName' value=$db_res[i]["vName"]%>
                                    <%assign var='fieldDesc' value=$db_res[i]["vDesc"]%>
                                    <%assign var='fieldType' value=$db_res[i]["eDisplayType"]%>
                                    <%if $lang_data[$prlang][$fieldName]|@trim neq ''%>
                                        <%assign var='fieldValue' value=$lang_data[$prlang][$fieldName]%>
                                    <%else%>
                                        <%assign var='fieldValue' value=$db_res[i]["vValue"]%>
                                    <%/if%>
                                    <%if $fieldType eq 'hidden'%>  
                                        <input type="hidden" name="<%$fieldName%>" class="settings-width" value="<%$fieldValue%>">
                                    <%else%>          
                                        <div class="settings-main <%$db_res[i]['_parentClass']%>">
                                            <div class="settings-left form-label">
                                                <%$fieldDesc%>
                                            </div>
                                            <div class="settings-right <%if $fieldType eq 'editor'%>frm-editor-layout<%/if%>">
                                                <%if $fieldType eq 'readonly'%>
                                                    <%$fieldValue%>
                                                <%elseif $db_res[i]["eConfigType"] eq "Prices"%>    
                                                    <select style="width:12%" name="<%$fieldName%>[eSelectType]"  title="Increase / Decrease">
                                                        <option value="Plus" <%if $db_res[i]["eSelectType"] eq 'Plus'%>selected=true <%/if%> >+</option>
                                                        <option value="Minus"  <%if $db_res[i]["eSelectType"] eq 'Minus'%>selected=true <%/if%> >-</option>
                                                    </select>
                                                    &nbsp;&nbsp;
                                                    <input style="width:25%" type="text" name="<%$fieldName%>[Value]" title="<%$fieldDesc%>" value="<%$fieldValue|@trim%>" class="<%$db_res[i]['_fieldClass']%>" <%$db_res[i]['_fieldAttr']%> />
                                                    &nbsp;&nbsp;
                                                    <select style="width:25%" name="<%$fieldName%>[eSource]"  title="Value / Percent">
                                                        <option value="Value" <%if $db_res[i]["eSource"] eq 'Value'%>selected=true <%/if%> >Value (Eg. 10)</option>
                                                        <option value="Percent" <%if $db_res[i]["eSource"] eq 'Percent'%>selected=true <%/if%> >Percent (Eg. 10%)</option>
                                                    </select>
                                                <%elseif $fieldType eq 'text'%>
                                                    <input type="text" id="<%$fieldName%>" name="<%$fieldName%>" class="settings-width <%$db_res[i]['_fieldClass']%>" title="<%$fieldDesc%>" value="<%($fieldValue|@trim)|@htmlentities%>" <%$db_res[i]['_langAttribute']%> <%$db_res[i]['_fieldAttr']%> />
                                                <%elseif $fieldType eq 'textarea'%>   
                                                    <textarea row="3"  id="<%$fieldName%>" name="<%$fieldName%>" class="settings-width elastic <%$db_res[i]['_fieldClass']%>" title="<%$fieldDesc%>" cols="5" <%$db_res[i]['_langAttribute']%> <%$db_res[i]['_fieldAttr']%>><%$fieldValue|@trim%></textarea>
                                                <%elseif $fieldType eq 'checkbox'%>  
                                                    <input type="checkbox" id="<%$fieldName%>" name="<%$fieldName%>" class="noinput regular-checkbox <%$db_res[i]['_fieldClass']%>" <%if $fieldValue == 'Y'%>checked="true"<%/if%> <%$db_res[i]['_fieldAttr']%> />
                                                    <label for="<%$fieldName%>">&nbsp;</label>
                                                <%elseif $fieldType eq 'password'%>
                                                    <input type="password" name="<%$fieldName%>" class="settings-width <%$db_res[i]['_fieldClass']%>" title="<%$fieldDesc%>"  value="<%$fieldValue|@htmlentities%>" <%$db_res[i]['_fieldAttr']%> />
                                                <%elseif $fieldType eq 'selectbox'%> 
                                                    <%assign var="multiAttr" value=$db_res[i]._multiAttr%>
                                                    <%assign var="nameAttr" value=$db_res[i]._nameAttr%>
                                                    <%assign var="pairValue" value='|'|@explode:$fieldValue%>
                                                    <%if $db_res[i]["eSource"] eq 'List'%>
                                                        <%assign var="sourceValue" value=$db_res[i]._listSourceValue%>
                                                        <select name="<%$nameAttr%>" class="settings-width chosen-select <%$db_res[i]['_fieldClass']%>" <%$multiAttr%> data-placeholder='<< Select <%$fieldDesc%> >>' <%$db_res[i]['_fieldAttr']%>>
                                                            <option value="-9"></option>
                                                            <%section name="j" loop=$sourceValue%>
                                                                <%assign var="listPairs" value='::'|@explode:$sourceValue[j]%>
                                                                <%assign var="listPairKey" value= $listPairs[0]%>
                                                                <%assign var="listPairVal" value= $listPairs[1]%>
                                                                <%assign var="selectedVal" value=""%>

                                                                <%if ($db_res[i]['eConfigType'] eq 'Formats' && $fieldName|@in_array:$date_format_config) %>
                                                                    <%assign var="listPairVal" value=$this->general->getDateTimeDropdownLabel($fieldName, $listPairKey)%>
                                                                <%/if%>
                                                                <%if $listPairVal eq ""%>
                                                                    <%assign var="listPairVal" value=$listPairKey%>
                                                                <%/if%>

                                                                <%if $db_res[i]["eSelectType"] eq 'Multiple' && $listPairKey|@in_array:$pairValue%>
                                                                    <%assign var="selectedVal" value="selected='selected'"%>
                                                                <%elseif $listPairKey eq $fieldValue%>
                                                                    <%assign var="selectedVal" value="selected='selected'"%>
                                                                <%/if%>   
                                                                <option value="<%$listPairKey%>" <%$selectedVal%>><%$listPairVal%></option>
                                                            <%/section%>   
                                                        </select>
                                                    <%/if%>
                                                    <%if $db_res[i]["eSource"] eq 'Query'%>
                                                        <%assign var="querySourceValue" value=$db_res[i]['_querySourceValue']%>
                                                        <select name="<%$nameAttr%>" class="settings-width chosen-select <%$db_res[i]['_fieldClass']%>" <%$multiAttr%> data-placeholder='<< Select <%$fieldDesc%> >>' <%$db_res[i]['_fieldAttr']%>>
                                                            <option value="-9"></option>
                                                            <%section name="j" loop=$querySourceValue%>
                                                                <%assign var="selectedVal" value=""%>
                                                                <%if $db_res[i]["eSelectType"] eq 'Multiple' && $querySourceValue[j]['id']|@in_array:$pairValue%>
                                                                    <%assign var="selectedVal" value="selected='selected'"%>
                                                                <%elseif $querySourceValue[j]['id'] eq $fieldValue%>
                                                                    <%assign var="selectedVal" value="selected='selected'"%>
                                                                <%/if%> 
                                                                <option value="<%$querySourceValue[j]['id']%>" <%$selectedVal%>><%$querySourceValue[j]['val']%></option>
                                                            <%/section%>    
                                                        </select>
                                                    <%/if%>
                                                <%elseif $fieldType eq 'editor'%>
                                                    <%assign var="editorName" value="editor_"|@cat:$fieldName%>
                                                    <%assign var="editor_event_arr" value=$editor_event_arr|@array_merge:array($editorName)%>
                                                    <textarea title="<%$fieldDesc%>" id="<%$editorName%>" value="" name="<%$fieldName%>" class="<%$db_res[i]['_fieldClass']%>" style="width:100%;min-height:300px;" <%$db_res[i]['_fieldAttr']%>><%$fieldValue|@stripslashes%></textarea>
                                                <%elseif $fieldType eq 'file'%>
                                                    <%assign var="fileData" value=$db_res[i]["_fileData"]%>
                                                    <%assign var="upload_event_arr" value=$upload_event_arr|@array_merge:array($fileData)%>
                                                    <div>
                                                        <div class="btn-uploadify frm-size-small <%$db_res[i]['_fieldClass']%>" <%$db_res[i]['_fieldAttr']%>>
                                                            <input type='hidden' value='<%$fieldValue%>' name='old_<%$fieldName%>' id='old_<%$fieldName%>' />
                                                            <input type='hidden' value='<%$fieldValue%>' name='<%$fieldName%>' id='<%$fieldName%>' />
                                                            <input type='hidden' value='<%$fieldValue%>' name='temp_<%$fieldName%>' id='temp_<%$fieldName%>' />
                                                            <div id="upload_drop_zone_<%$fieldName%>" class="upload-drop-zone"></div>
                                                            <div class="uploader upload-src-zone">
                                                                <input type='file' name='uploadify_<%$fieldName%>' id='uploadify_<%$fieldName%>' title='<%$fieldDesc%>' />
                                                                <span class="filename" id="preview_<%$fieldName%>">
                                                                    <%if $fieldValue neq ''%>
                                                                        <%$fieldValue%>
                                                                    <%else%>
                                                                        <%$this->lang->line('GENERIC_UPLOAD_FILE')%>
                                                                    <%/if%>
                                                                </span>
                                                                <span class="action">Choose File</span>
                                                            </div>
                                                        </div>
                                                        <div class="upload-image-btn">
                                                            <div id="img_buttons_<%$fieldName%>" class="img-inline-display">
                                                                <div id="img_view_<%$fieldName%>" class="img-view-section"></div>
                                                                <div id="img_del_<%$fieldName%>" class="img-del-section"></div>
                                                            </div>
                                                        </div>
                                                        <div class="clear upload-progress" id="progress_<%$fieldName%>">
                                                            <div class="upload-progress-bar progress progress-striped active">
                                                                <div class="bar" id="practive_<%$fieldName%>"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <%/if%>   
                                                <%if $db_res[i]['tHelpText']|trim neq ""%>
                                                    <span class="input-comment">
                                                        <a href="javascript://"  class="tipR" title="<%$db_res[i]['tHelpText']%>">
                                                            <span class="icomoon-icon-help"></span>
                                                        </a>
                                                    </span>
                                                <%/if%>
                                                <label id="<%$fieldName%>Err" class="error"></label>
                                            </div>
                                            <%if $lang_fields|@is_array && $lang_fields|@count gt 0%>
                                                <%if $fieldName|@in_array:$lang_fields%>
                                                    <%if $exlang_arr|@is_array && $exlang_arr|@count gt 0%>
                                                        <%section name=ml loop=$exlang_arr%>
                                                            <%assign var="exlang" value=$exlang_arr[ml]%>
                                                            <div class="clear <%$db_res[i]['_langClass']%>" id="lnsh_<%$fieldName%>_<%$exlang%>" style="<%if $exlang neq $dflang%>display:none;<%/if%>">
                                                                <div class="settings-left form-label">
                                                                    <%$fieldDesc%> [<%$lang_info[$exlang]['vLangTitle']%>]
                                                                </div>
                                                                <div class="settings-right <%if $fieldType eq 'editor'%>frm-editor-layout<%/if%>">
                                                                    <%if $fieldType eq 'text'%>
                                                                        <input type="text" name="lang<%$fieldName%>[<%$exlang%>]" id="lang_<%$fieldName%>_<%$exlang%>" class="settings-width <%$db_res[i]['_fieldClass']%>" value="<%$lang_data[$exlang][$fieldName]|@trim%>" <%$db_res[i]['_fieldAttr']%> />
                                                                    <%elseif $fieldType eq 'textarea'%>   
                                                                        <textarea placeholder="" name="lang<%$fieldName%>[<%$exlang%>]" id="lang_<%$fieldName%>_<%$exlang%>" class="elastic settings-width <%$db_res[i]['_fieldClass']%>" <%$db_res[i]['_fieldAttr']%>><%$lang_data[$exlang][$fieldName]%></textarea>
                                                                    <%elseif $fieldType eq 'editor'%>
                                                                        <%assign var="temp_elename" value=$fieldName|@cat:$exlang%>
                                                                        <%assign var="lang_elename" value="lang_editor_"|@cat:$temp_elename%>
                                                                        <%assign var="editor_event_arr" value=$editor_event_arr|@array_merge:array($lang_elename)%>
                                                                        <textarea id="<%$lang_elename%>" name="lang<%$fieldName%>[<%$exlang%>]" style="width:100%;min-height:300px;" class="<%$db_res[i]['_fieldClass']%>" <%$db_res[i]['_fieldAttr']%>><%$lang_data[$exlang][$fieldName]%></textarea>
                                                                    <%/if%>    
                                                                </div>
                                                            </div>
                                                        <%/section%>
                                                        <div class="clear none"></div>
                                                        <div class="lang-flag-css" style="margin-left:35%!important">
                                                            <%$this->general->getAdminLangFlagHTML($fieldName, $exlang_arr, $lang_info)%>
                                                        </div>
                                                    <%/if%>
                                                <%/if%>
                                            <%/if%> 
                                            <div class="clear"></div>
                                        </div>
                                    <%/if%>       
                                <%/section%>
                            <%if $group_count gt 1%>
                            </fieldset>
                            <%/if%>
                        <%/foreach%> 
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <%if $edit_access eq '1'%>
                        <div class="frm-bot-btn <%$rl_theme_arr['frm_gener_action_bar']%> <%$rl_theme_arr['frm_gener_action_btn']%>">
                            <div class="action-btn-align">
                                <input type="submit" value="Save" onclick="return Project.modules.settings.getAdminSettingsValidate()" class='btn btn-info'>&nbsp;&nbsp;
                                <input type="button" value="Discard" class='btn' onclick="loadAdminSiteMapPage()">
                            </div>
                        </div>
                    <%/if%>
                <%else%>
                    <div class="errormsg" align="center">No records found</div>
                <%/if%>
            </form>
        </div>
    </div>
</div>
<%if $editor_event_arr|@is_array && $editor_event_arr|@count gt 0%>
    <%assign var="editor_event_str" value=$editor_event_arr|@json_encode%>
    <%$this->js->add_js('admin/forms/tinymce/tinymce.min.js')%>
<%/if%>
<%if $upload_event_arr|@is_array && $upload_event_arr|@count gt 0%>
    <%assign var="upload_event_str" value=$upload_event_arr|@json_encode%>
<%/if%>

<%javascript%> 
    var el_form_settings = {};
    var editor_events_arr = "", upload_events_arr = "";
    var $upload_form_file = admin_url+'<%$upload_url%>';
    <%if $editor_event_str|trim neq ''%>   
        editor_events_arr = $.parseJSON('<%$editor_event_str%>');
    <%/if%>

    <%if $upload_event_str|trim neq ''%>   
        upload_events_arr = $.parseJSON('<%$upload_event_str%>');
    <%/if%>

    $(function(){
        $('#frmsettingslist').validate({
            <%$validate_rules%>
        });
    });
    
    el_form_settings["prime_lang_code"] = '<%$prlang%>';
    el_form_settings["default_lang_code"] = '<%$dflang%>';
    el_form_settings["other_lang_JSON"] = '<%$exlang_arr|@json_encode%>';
    intializeLanguageAutoEntry(el_form_settings["prime_lang_code"], el_form_settings["other_lang_JSON"], el_form_settings["default_lang_code"]);
<%/javascript%>

<%$this->js->add_js('admin/admin/js_settings_page.js')%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
    <%$this->css->css_src()%>
<%/if%>

<%javascript%>
    Project.modules.settings.init();
<%/javascript%>