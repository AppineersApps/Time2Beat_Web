<div class="form-row row-fluid capability-header label-lt-fix">
    <div class='header-title'>
        <%$this->lang->line('GENERIC_CAPABILITIES')%>
    </div>
</div>
<div class='capability-container'>
    <div class="module-capabilities">
        <div class="box">
            <div class="title">
                <h4>
                    <input type="checkbox" class="regular-checkbox parent-category" capabilty-attr="module_capabilities" name="category[module_capabilities]" id="category_module_capabilities" />
                    <label class="right-label-inline" for="category_module_capabilities" class="right-label-inline">&nbsp;</label>
                    <label class="right-label-inline" for="category_module_capabilities"><%$this->lang->line('GENERIC_MODULES')%></label>    
                </h4>
                <span class="capability-show-hide" hide-attr='child_capability_module_capabilities'><i class="fa fa-chevron-up" aria-hidden="true"></i></span>
            </div>
            <div class="content capability-content" id="child_capability_module_capabilities">

                <div class="row module-capability-header" >
                    <div class="span3 module-main-header"></div>
                    <div class="span9 module-list-header">
                        <div class="span1 module-list-item"><%$this->lang->line('GENERIC_LISTING')%></div>
                        <div class="span1 module-list-item"><%$this->lang->line('GENERIC_VIEW')%></div>
                        <div class="span1 module-list-item"><%$this->lang->line('GENERIC_ADD')%></div>
                        <div class="span1 module-list-item"><%$this->lang->line('GENERIC_UPDATE')%></div>
                        <div class="span1 module-list-item"><%$this->lang->line('GENERIC_DELETE')%></div>
                        <div class="span1 module-list-item"><%$this->lang->line('GENERIC_EXPORT')%></div>
                        <div class="span1 module-list-item"><%$this->lang->line('GENERIC_PRINT')%></div>
                    </div>
                </div>
                <%foreach from=$capability_categories key=key item=val%>    
                <%assign var="capabilty_data" value=$capability_masters[$val['iCapabilityCategoryId']]%>
                <%if $capabilty_data|@is_array && capabilty_data|@count gt 0%>
                <%if 'module'|array_key_exists:$capabilty_data %>
                <%if $is_admin_group eq true%>
                <%assign var="is_selected" value=true%>
                <%else%>
                <%assign var="is_selected" value=$group_capabilities[$inval['iCapabilityId']][0]|is_array%>
                <%/if%>
                <div class="row module-capability-row">

                    <div class="span3 module-capability-title">
                        <input type="checkbox" class="regular-checkbox module-parent-category" capabilty-attr="<%$val['iCapabilityCategoryId']%>" name="category[<%$val['iCapabilityCategoryId']%>]" id="module_<%$val['iCapabilityCategoryId']%>" />
                        <label class="right-label-inline" for="module_<%$val['iCapabilityCategoryId']%>" class="right-label-inline">&nbsp;</label>
                        <label class="right-label-inline" for="module_<%$val['iCapabilityCategoryId']%>"><%$val['vCategoryName']%></label>    
                    </div>
                    <div class='span9 module-capability-list' id="module_child_capability_<%$val['iCapabilityCategoryId']%>">
                        <div class="span1 module-capability-list-item">
                            <%if $is_admin_group eq true%>
                            <%assign var="is_selected" value=true%>
                            <%else%>
                            <%assign var="is_selected" value=$group_capabilities[$capabilty_data['module']['List']['iCapabilityId']][0]|is_array%>
                            <%/if%>
                            <input type="checkbox" class="regular-checkbox capability-category" name="capability[<%$capabilty_data['module']['List']['iCapabilityId']%>]" id="capability_<%$capabilty_data['module']['List']['iCapabilityId']%>" <%if $is_selected eq true%>checked=checked<%/if%> />
                            <label class="right-label-inline" for="capability_<%$capabilty_data['module']['List']['iCapabilityId']%>" class="right-label-inline">&nbsp;</label>
                        </div>

                        <div class="span1 module-capability-list-item">
                            <%if $is_admin_group eq true%>
                            <%assign var="is_selected" value=true%>
                            <%else%>
                            <%assign var="is_selected" value=$group_capabilities[$capabilty_data['module']['View']['iCapabilityId']][0]|is_array%>
                            <%/if%>
                            <input type="checkbox" class="regular-checkbox capability-category" name="capability[<%$capabilty_data['module']['View']['iCapabilityId']%>]" id="capability_<%$capabilty_data['module']['View']['iCapabilityId']%>" <%if $is_selected eq true%>checked=checked<%/if%> />
                            <label class="right-label-inline" for="capability_<%$capabilty_data['module']['View']['iCapabilityId']%>" class="right-label-inline">&nbsp;</label>
                        </div>

                        <div class="span1 module-capability-list-item">
                            <%if $is_admin_group eq true%>
                            <%assign var="is_selected" value=true%>
                            <%else%>
                            <%assign var="is_selected" value=$group_capabilities[$capabilty_data['module']['Add']['iCapabilityId']][0]|is_array%>
                            <%/if%>
                            <input type="checkbox" class="regular-checkbox capability-category" name="capability[<%$capabilty_data['module']['Add']['iCapabilityId']%>]" id="capability_<%$capabilty_data['module']['Add']['iCapabilityId']%>" <%if $is_selected eq true%>checked=checked<%/if%> />
                            <label class="right-label-inline" for="capability_<%$capabilty_data['module']['Add']['iCapabilityId']%>" class="right-label-inline">&nbsp;</label>
                        </div>

                        <div class="span1 module-capability-list-item">
                            <%if $is_admin_group eq true%>
                            <%assign var="is_selected" value=true%>
                            <%else%>
                            <%assign var="is_selected" value=$group_capabilities[$capabilty_data['module']['Update']['iCapabilityId']][0]|is_array%>
                            <%/if%>
                            <input type="checkbox" class="regular-checkbox capability-category" name="capability[<%$capabilty_data['module']['Update']['iCapabilityId']%>]" id="capability_<%$capabilty_data['module']['Update']['iCapabilityId']%>" <%if $is_selected eq true%>checked=checked<%/if%> />
                            <label class="right-label-inline" for="capability_<%$capabilty_data['module']['Update']['iCapabilityId']%>" class="right-label-inline">&nbsp;</label>
                        </div>

                        <div class="span1 module-capability-list-item">
                            <%if $is_admin_group eq true%>
                            <%assign var="is_selected" value=true%>
                            <%else%>
                            <%assign var="is_selected" value=$group_capabilities[$capabilty_data['module']['Delete']['iCapabilityId']][0]|is_array%>
                            <%/if%>
                            <input type="checkbox" class="regular-checkbox capability-category" name="capability[<%$capabilty_data['module']['Delete']['iCapabilityId']%>]" id="capability_<%$capabilty_data['module']['Delete']['iCapabilityId']%>" <%if $is_selected eq true%>checked=checked<%/if%> />
                            <label class="right-label-inline" for="capability_<%$capabilty_data['module']['Delete']['iCapabilityId']%>" class="right-label-inline">&nbsp;</label>
                        </div>

                        <div class="span1 module-capability-list-item">
                            <%if $is_admin_group eq true%>
                            <%assign var="is_selected" value=true%>
                            <%else%>
                            <%assign var="is_selected" value=$group_capabilities[$capabilty_data['module']['Export']['iCapabilityId']][0]|is_array%>
                            <%/if%>
                            <input type="checkbox" class="regular-checkbox capability-category" name="capability[<%$capabilty_data['module']['Export']['iCapabilityId']%>]" id="capability_<%$capabilty_data['module']['Export']['iCapabilityId']%>" <%if $is_selected eq true%>checked=checked<%/if%> />
                            <label class="right-label-inline" for="capability_<%$capabilty_data['module']['Export']['iCapabilityId']%>" class="right-label-inline">&nbsp;</label>
                        </div>

                        <div class="span1 module-capability-list-item">
                            <%if $is_admin_group eq true%>
                            <%assign var="is_selected" value=true%>
                            <%else%>
                            <%assign var="is_selected" value=$group_capabilities[$capabilty_data['module']['Print']['iCapabilityId']][0]|is_array%>
                            <%/if%>
                            <input type="checkbox" class="regular-checkbox capability-category" name="capability[<%$capabilty_data['module']['Print']['iCapabilityId']%>]" id="capability_<%$capabilty_data['module']['Print']['iCapabilityId']%>" <%if $is_selected eq true%>checked=checked<%/if%> />
                            <label class="right-label-inline" for="capability_<%$capabilty_data['module']['Print']['iCapabilityId']%>" class="right-label-inline">&nbsp;</label>
                        </div>
                    </div>
                </div>
                <%/if%>
                <%/if%>
                <%/foreach%>
            </div>
        </div>
    </div>   
    <div class='custom-capabilities'>
        <%if $capability_categories|@is_array && $capability_categories|@count gt 0%>
        <%foreach from=$capability_categories key=key item=val%>    
        <%assign var="capabilty_data" value=$capability_masters[$val['iCapabilityCategoryId']]%>
        <%if $capabilty_data|@is_array && capabilty_data|@count gt 0%>
        <%if ($capabilty_data['custom']['others']|is_array && $capabilty_data['custom']['others']|count gt 0) || ($capabilty_data['custom']['settings']|is_array && $capabilty_data['custom']['settings']|count gt 0)%>
        <div class="box">   
            <div class="title">
                <h4>
                    <input type="checkbox" class="regular-checkbox parent-category" capabilty-attr="<%$val['iCapabilityCategoryId']%>" name="category[<%$val['iCapabilityCategoryId']%>]" id="category_<%$val['iCapabilityCategoryId']%>" />
                    <label class="right-label-inline" for="category_<%$val['iCapabilityCategoryId']%>" class="right-label-inline">&nbsp;</label>
                    <label class="right-label-inline" for="category_<%$val['iCapabilityCategoryId']%>"><%$val['vCategoryName']%></label>    
                </h4>
                <span class="capability-show-hide" hide-attr="category_<%$val['vCategoryName']%>"><i class="fa fa-chevron-up" aria-hidden="true"></i></span>
            </div>
            <div class='content capability-content'id="category_<%$val['vCategoryName']%>">
                <div class="form-row row-fluid label-lt-fix capability-block" id="child_capability_<%$val['iCapabilityCategoryId']%>">

                    <%if $capabilty_data['custom']['settings']|is_array && $capabilty_data['custom']['settings']|count gt 0%>
                    <%if $capabilty_data['custom']['settings']['view']|is_array && $capabilty_data['custom']['settings']['view']|count gt 0%>
                    <div class='span6'>
                        <%foreach from=$capabilty_data['custom']['settings']['view'] key=inkey item=inval%>
                        <div class="settings-view-item">
                            <%if $is_admin_group eq true%>
                            <%assign var="is_selected" value=true%>
                            <%else%>
                            <%assign var="is_selected" value=$group_capabilities[$inval['iCapabilityId']][0]|is_array%>
                            <%/if%>
                            <input type="checkbox" class="regular-checkbox capability-category" name="capability[<%$inval['iCapabilityId']%>]" id="capability_<%$inval['iCapabilityId']%>" value="<%$inval['iCapabilityId']%>" <%if $is_selected eq true%>checked=checked<%/if%> />
                            <label class="right-label-inline" for="capability_<%$inval['iCapabilityId']%>" class="right-label-inline">&nbsp;</label>
                            <label class="right-label-inline" for="capability_<%$inval['iCapabilityId']%>"><%$inval['vCapabilityName']%></label>
                            <%if $inval['eCapabilityType'] eq 'FormField'%>
                            <%assign var=cap_json value=$group_capabilities[$inval['iCapabilityId']][0]['tCapabilities']|json_decode:true%>
                            <div id="capability_json_<%$inval['iCapabilityId']%>" class="capabilities-json <%if $is_selected neq true%>hide<%/if%>">
                                <input type="radio" class="regular-radio" name="capability_json[<%$inval['iCapabilityId']%>]" id="capability_json_<%$inval['iCapabilityId']%>_editable" value="Editable" checked='checked' />
                                <label class="right-label-inline" for="capability_json_<%$inval['iCapabilityId']%>_editable" class="right-label-inline">&nbsp;</label>
                                <label class="right-label-inline" for="capability_json_<%$inval['iCapabilityId']%>_editable"><%$this->lang->line('GENERIC_EDITABLE')%></label>
                                &nbsp;&nbsp;
                                <input type="radio" class="regular-radio" name="capability_json[<%$inval['iCapabilityId']%>]" id="capability_json_<%$inval['iCapabilityId']%>_readonly" value="Readonly" <%if $cap_json['access_mode'] eq 'Readonly'%>checked='checked'<%/if%> />
                                <label class="right-label-inline" for="capability_json_<%$inval['iCapabilityId']%>_readonly" class="right-label-inline">&nbsp;</label>
                                <label class="right-label-inline" for="capability_json_<%$inval['iCapabilityId']%>_readonly"><%$this->lang->line('GENERIC_READONLY')%></label>
                            </div>
                            <%/if%>
                        </div>
                        <%/foreach%>
                        <div class='clear'></div>
                    </div>
                    <%/if%>
                    <%if $capabilty_data['custom']['settings']['update']|is_array && $capabilty_data['custom']['settings']['update']|count gt 0%>
                    <div class='span6'>
                        <%foreach from=$capabilty_data['custom']['settings']['update'] key=inkey item=inval%>
                        <div class="settings-update-item">
                            <%if $is_admin_group eq true%>
                            <%assign var="is_selected" value=true%>
                            <%else%>
                            <%assign var="is_selected" value=$group_capabilities[$inval['iCapabilityId']][0]|is_array%>
                            <%/if%>
                            <input type="checkbox" class="regular-checkbox capability-category" name="capability[<%$inval['iCapabilityId']%>]" id="capability_<%$inval['iCapabilityId']%>" value="<%$inval['iCapabilityId']%>" <%if $is_selected eq true%>checked=checked<%/if%> />
                            <label class="right-label-inline" for="capability_<%$inval['iCapabilityId']%>" class="right-label-inline">&nbsp;</label>
                            <label class="right-label-inline" for="capability_<%$inval['iCapabilityId']%>"><%$inval['vCapabilityName']%></label>
                            <%if $inval['eCapabilityType'] eq 'FormField'%>
                            <%assign var=cap_json value=$group_capabilities[$inval['iCapabilityId']][0]['tCapabilities']|json_decode:true%>
                            <div id="capability_json_<%$inval['iCapabilityId']%>" class="capabilities-json <%if $is_selected neq true%>hide<%/if%>">
                                <input type="radio" class="regular-radio" name="capability_json[<%$inval['iCapabilityId']%>]" id="capability_json_<%$inval['iCapabilityId']%>_editable" value="Editable" checked='checked' />
                                <label class="right-label-inline" for="capability_json_<%$inval['iCapabilityId']%>_editable" class="right-label-inline">&nbsp;</label>
                                <label class="right-label-inline" for="capability_json_<%$inval['iCapabilityId']%>_editable"><%$this->lang->line('GENERIC_EDITABLE')%></label>
                                &nbsp;&nbsp;
                                <input type="radio" class="regular-radio" name="capability_json[<%$inval['iCapabilityId']%>]" id="capability_json_<%$inval['iCapabilityId']%>_readonly" value="Readonly" <%if $cap_json['access_mode'] eq 'Readonly'%>checked='checked'<%/if%> />
                                <label class="right-label-inline" for="capability_json_<%$inval['iCapabilityId']%>_readonly" class="right-label-inline">&nbsp;</label>
                                <label class="right-label-inline" for="capability_json_<%$inval['iCapabilityId']%>_readonly"><%$this->lang->line('GENERIC_READONLY')%></label>
                            </div>
                            <%/if%>
                        </div>
                        <%/foreach%>
                        <div class='clear'></div>
                    </div>
                    <%/if%>
                    <%/if%>
                    <%if $capabilty_data['custom']['others']|is_array && $capabilty_data['custom']['others']|count gt 0%>
                    <div class="">
                        <%foreach from=$capabilty_data['custom']['others'] key=inkey item=inval%>
                        <div class="span12" style="margin-left: 0px!important;">
                            <%if $is_admin_group eq true%>
                            <%assign var="is_selected" value=true%>
                            <%else%>
                            <%assign var="is_selected" value=$group_capabilities[$inval['iCapabilityId']][0]|is_array%>
                            <%/if%>
                            <input type="checkbox" class="regular-checkbox capability-category" name="capability[<%$inval['iCapabilityId']%>]" id="capability_<%$inval['iCapabilityId']%>" value="<%$inval['iCapabilityId']%>" <%if $is_selected eq true%>checked=checked<%/if%> />
                            <label class="right-label-inline" for="capability_<%$inval['iCapabilityId']%>" class="right-label-inline">&nbsp;</label>
                            <label class="right-label-inline" for="capability_<%$inval['iCapabilityId']%>"><%$inval['vCapabilityName']%></label>
                            <%if $inval['eCapabilityType'] eq 'FormField'%>
                            <%assign var=cap_json value=$group_capabilities[$inval['iCapabilityId']][0]['tCapabilities']|json_decode:true%>
                            <div id="capability_json_<%$inval['iCapabilityId']%>" class="capabilities-json <%if $is_selected neq true%>hide<%/if%>">
                                <input type="radio" class="regular-radio" name="capability_json[<%$inval['iCapabilityId']%>]" id="capability_json_<%$inval['iCapabilityId']%>_editable" value="Editable" checked='checked' />
                                <label class="right-label-inline" for="capability_json_<%$inval['iCapabilityId']%>_editable" class="right-label-inline">&nbsp;</label>
                                <label class="right-label-inline" for="capability_json_<%$inval['iCapabilityId']%>_editable"><%$this->lang->line('GENERIC_EDITABLE')%></label>
                                &nbsp;&nbsp;
                                <input type="radio" class="regular-radio" name="capability_json[<%$inval['iCapabilityId']%>]" id="capability_json_<%$inval['iCapabilityId']%>_readonly" value="Readonly" <%if $cap_json['access_mode'] eq 'Readonly'%>checked='checked'<%/if%> />
                                <label class="right-label-inline" for="capability_json_<%$inval['iCapabilityId']%>_readonly" class="right-label-inline">&nbsp;</label>
                                <label class="right-label-inline" for="capability_json_<%$inval['iCapabilityId']%>_readonly"><%$this->lang->line('GENERIC_READONLY')%></label>
                            </div>
                            <%/if%>
                        </div>
                        <%/foreach%>
                    </div>
                    <%/if%>
                </div>
                <%*
                <div class="form-row row-fluid label-lt-fix">
                    <div class='text-error'>No capabilities are avialable</div>
                </div>
                *%>
            </div>
        </div>
        <%/if%>
        <%/if%>
        <%/foreach%>
        <%/if%>
    </div>
</div>
    <%javascript%>
    $('.capability-show-hide').on('click', function(){
        $("i", this).toggleClass("fa-chevron-up fa-chevron-down");
        var content_id = $(this).attr('hide-attr');
        if($("i", this).hasClass("fa-chevron-down")){
            $('#'+content_id).hide(500);
        }else{
            $('#'+content_id).show(500);
        }
    });
    <%/javascript%>
