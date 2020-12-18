<div class="form-row row-fluid label-lt-fix">
    <label class="form-label span3"><%$this->lang->line('GENERIC_SELECT_MODULES')%> <em>*</em> :</label> 
    <div class="form-right-div">
        <%if $is_admin_group eq true%>
            <%assign var="selected" value="disabled=true checked=true" %>
        <%else%> 
            <%assign var="selected" value="" %>
        <%/if%> 
        <%foreach from=$action_arr key=ackey item=acval%>
        <div class="margin-equilize">
            <input type="checkbox" class="regular-checkbox" name="all_<%$ackey%>" id="all_<%$ackey%>" <%$selected%>/>
            <label for="all_<%$ackey%>">&nbsp;</label>
        </div>
        <%/foreach%>
    </div>
</div>
<%assign var="homeData" value=array("HomeSitemap")%>
<%if $db_parent_menus|@is_array && $db_parent_menus|@count gt 0%>
    <%foreach from=$db_parent_menus key=key item=val%>    
        <%assign var="parentMenuId" value=$val['iAdminMenuId']%>
        <%assign var="parentCode" value=$val['vUniqueMenuCode']%>
        <%assign var="rightsData" value=$db_group_rights[$parentMenuId][0]%>
        <%if $is_admin_group eq true%>
            <%assign var="selected" value="disabled=true selected = 'selected'" %>
        <%else%> 
            <%assign var="selected" value="" %>
        <%/if%> 
        <div class="form-row row-fluid label-lt-fix">
            <label class="form-label span3">
                <%assign var="menu_label" value=$this->general->getDisplayLabel("Generic",$val['vMenuDisplay'],"label")%>
                <input class="left-label-checkbox regular-checkbox" type="checkbox" name="iAdminMenuId[<%$parentMenuId%>]" id="iAdminMenuId_<%$parentMenuId%>" value="<%$parentMenuId%>" <%$selected%> />
                <lable for="iAdminMenuId_<%$parentMenuId%>" class="right-label-inline">&nbsp;</lable><lable for="iAdminMenuId_<%$parentMenuId%>" class="right-label-inline"><%$this->lang->line($menu_label)%></lable>
            </label> 
            <div class="form-right-div">
                <%foreach from=$action_arr key=ackey item=acval%>
                    <%assign var="checked_attr" value=''%>
                    <%if $rightsData|@is_array && $rightsData|@count gt 0%>
                        <%if $ackey eq "eView"%>
                            <%if $rightsData['eView'] eq "Yes"%>
                                <%assign var="checked_attr" value='checked=true'%>
                            <%/if%>
                        <%elseif $ackey eq "eList"%>
                            <%if $rightsData['eList'] eq "Yes"%>
                                <%assign var="checked_attr" value='checked=true'%>
                            <%/if%>
                        <%elseif $ackey eq "eAdd"%>
                            <%if $rightsData['eAdd'] eq "Yes"%>
                                <%assign var="checked_attr" value='checked=true'%>
                            <%/if%>
                        <%elseif $ackey eq "eUpdate"%>
                            <%if $rightsData['eUpdate'] eq "Yes"%>
                                <%assign var="checked_attr" value='checked=true'%>
                            <%/if%>
                        <%elseif $ackey eq "eDelete"%>
                            <%if $rightsData['eDelete'] eq "Yes"%>
                                <%assign var="checked_attr" value='checked=true'%>
                            <%/if%>
                        <%elseif $ackey eq "eExport"%>
                            <%if $rightsData['eExport'] eq "Yes"%>
                                <%assign var="checked_attr" value='checked=true'%>
                            <%/if%>
                        <%elseif $ackey eq "ePrint"%>
                            <%if $rightsData['ePrint'] eq "Yes"%>
                                <%assign var="checked_attr" value='checked=true'%>
                            <%/if%>
                        <%/if%>
                    <%/if%>
                    <%if $is_admin_group eq true || $parentCode|@in_array:$homeData%>
                        <%assign var="checked_attr" value="checked=true"%>
                    <%/if%>
                    <%if $is_admin_group eq true%>
                        <%assign var="disabled_attr" value="disabled=true"%>
                    <%else%>   
                        <%assign var="disabled_attr" value=''%>
                    <%/if%>
                    <%assign var="view_type" value=$this->general->getDisplayLabel("Generic",$acval,"label")%>
                    <div class="margin-equilize">
                        <input type="checkbox" name="<%$ackey%>[<%$parentMenuId%>]" class="regular-checkbox" id="<%$ackey%>_<%$parentMenuId%>" value="Yes" <%$disabled_attr%> <%$checked_attr%> />
                        <label class="right-label-inline" for="<%$ackey%>_<%$parentMenuId%>">&nbsp;</label><label class="right-label-inline" for="<%$ackey%>_<%$parentMenuId%>"><%$this->lang->line($view_type)%></label>
                    </div>
                <%/foreach%>
            </div>
        </div>
        <%assign var="db_child_menus" value=$db_child_assoc_menus[$parentMenuId]%>
        <%if $db_child_menus|@is_array && $db_child_menus|@count gt 0%>
            <%foreach from=$db_child_menus key=chkey item=chval%> 
                <%assign var="childMenuId" value=$chval['iAdminMenuId']%>
                <%assign var="childCode" value=$chval['vUniqueMenuCode']%>
                <%assign var="rightsData" value=$db_group_rights[$childMenuId][0]%>
                <%if $is_admin_group eq true || $childCode|@in_array:$homeData%>
                    <%assign var="disabled_attr" value="disabled=true checked=true"%>
                <%else%> 
                    <%assign var="disabled_attr" value=""%>
                <%/if%>
                <div class="form-row row-fluid label-lt-fix">
                <label class="form-label span3" style="padding-left:2%;">
                    <%assign var="sub_menu_label" value=$this->general->getDisplayLabel("Generic",$chval['vMenuDisplay'],"label")%>
                    <input class="left-label-checkbox regular-checkbox" type="checkbox" name="iAdminMenuId[<%$childMenuId%>]" id="iAdminMenuId_<%$childMenuId%>" value="<%$childMenuId%>" <%$disabled_attr%> rel="parent_<%$parentMenuId%>" />
                    <label class="right-label-inline" for="iAdminMenuId_<%$childMenuId%>">&nbsp;</label><label for="iAdminMenuId_<%$childMenuId%>" class="right-label-inline"><%$this->lang->line($sub_menu_label)%></label>
                </label> 
                    <div class="form-right-div">
                        <%foreach from=$action_arr key=ackey item=acval%>
                            <%assign var="checked_attr" value=''%>
                            <%if $rightsData|@is_array && $rightsData|@count gt 0%>
                                <%if $ackey eq "eView"%>
                                    <%if $rightsData['eView'] == "Yes"%>
                                        <%assign var="checked_attr" value="checked=true"%>
                                    <%/if%>
                                <%elseif $ackey eq "eList"%>
                                    <%if $rightsData['eList'] == "Yes"%>
                                        <%assign var="checked_attr" value="checked=true"%>
                                    <%/if%>
                                <%elseif $ackey eq "eAdd"%>
                                    <%if $rightsData['eAdd'] == "Yes"%>
                                        <%assign var="checked_attr" value="checked=true"%>
                                    <%/if%>
                                <%elseif $ackey eq "eUpdate"%>
                                    <%if $rightsData['eUpdate'] == "Yes"%>
                                        <%assign var="checked_attr" value="checked=true"%>
                                    <%/if%>
                                <%elseif $ackey eq "eDelete"%>
                                    <%if $rightsData['eDelete'] == "Yes"%>
                                        <%assign var="checked_attr" value="checked=true"%>
                                    <%/if%>
                                <%elseif $ackey eq "eExport"%>
                                    <%if $rightsData['eExport'] == "Yes"%>
                                        <%assign var="checked_attr" value="checked=true"%>
                                    <%/if%>
                                <%elseif $ackey eq "ePrint"%>
                                    <%if $rightsData['ePrint'] == "Yes"%>
                                        <%assign var="checked_attr" value="checked=true"%>
                                    <%/if%>
                                <%/if%>
                                <%if $is_admin_group eq true || $childCode|@in_array:$homeData%>
                                    <%assign var="checked_attr" value="checked=true"%>
                                    <%assign var="disabled_attr" value="disabled=true"%>
                                <%else%>   
                                    <%assign var="disabled_attr" value=''%>
                                <%/if%>
                            <%/if%>
                            <%assign var="child_view_type" value=$this->general->getDisplayLabel("Generic",$acval,"label")%>
                            <div class="margin-equilize">
                                <input type="checkbox" name="<%$ackey%>[<%$childMenuId%>]" class="regular-checkbox" id="<%$ackey%>_<%$childMenuId%>" value="Yes" <%$disabled_attr%> <%$checked_attr%> rel="parent_<%$parentMenuId%>" />
                                <label class="right-label-inline" for="<%$ackey%>_<%$childMenuId%>">&nbsp;</label><label class="right-label-inline" for="<%$ackey%>_<%$childMenuId%>"><%$this->lang->line($child_view_type)%></label>
                            </div>
                        <%/foreach%>
                    </div>
                </div>
            <%/foreach%>
        <%/if%>
    <%/foreach%>
<%/if%>