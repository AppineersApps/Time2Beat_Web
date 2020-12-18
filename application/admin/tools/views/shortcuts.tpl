<%if $this->input->is_ajax_request()%>
<%$this->js->clean_js()%>
<%/if%>   
<div class="module-custom-container">
    <%include file="shortcuts_preview_strip.tpl"%>
    <div class="shortcuts" data-custom-name="shortcuts">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing">
            <div class="shortcuts grid-data-container pad-calc-container">
                <%if $shortcuts_access eq 'Y'%>
                <div class="shortcut-block">
                    <div class="row">
                        <div class="span5 offset1">
                            <%foreach from=$shortcuts_arr1  key=k item=v%>
                            <div class="span12 shortcut-row<%if $v['status'] eq 'Inactive'%> shortcuts-inactive<%/if%>">
                                <%foreach from=$v['split']  key=key item=val%>
                                <%assign var="num" value=$key+1%>
                                <div class="shortcut<%$num%>">
                                    <%if $val eq 'left'%>
                                    <i class='fa fa-arrow-left shortcut-icon'></i>
                                    <%elseif $val eq 'right'%>
                                    <i class='fa fa-arrow-right shortcut-icon'></i>
                                    <%elseif $val eq 'up'%>
                                    <i class='fa fa-arrow-up shortcut-icon'></i>
                                    <%elseif $val eq 'down'%>
                                    <i class='fa fa-arrow-down shortcut-icon'></i>
                                    <%else%>
                                    <%$val%>
                                    <%/if%>
                                </div> 
                                <%if $v['split']|@count neq $num%>
                                <div class="shortcut-plus">+</div> 
                                <%/if%>
                                <%/foreach%>
                                <div class="shortcut-right"> <b>=</b> <%$v['name']%></div>
                            </div>
                            <%/foreach%>
                        </div>
                        <div class="span5 offset1">
                            <%foreach from=$shortcuts_arr2  key=k item=v%>
                            <div class="span12 shortcut-row">
                                <%foreach from=$v['split']  key=key item=val%>
                                <%assign var="num" value=$key+1%>
                                <div class="shortcut<%$num%>">
                                    <%if $val eq 'left'%>
                                    <i class='fa fa-arrow-left shortcut-icon'></i>
                                    <%elseif $val eq 'right'%>
                                    <i class='fa fa-arrow-right shortcut-icon'></i>
                                    <%elseif $val eq 'up'%>
                                    <i class='fa fa-arrow-up shortcut-icon'></i>
                                    <%elseif $val eq 'down'%>
                                    <i class='fa fa-arrow-down shortcut-icon'></i>
                                    <%else%>
                                    <%$val%>
                                    <%/if%>
                                </div> 
                                <%if $v['split']|@count neq $num%>
                                <div class="shortcut-plus">+</div> 
                                <%/if%>
                                <%/foreach%>
                                <div class="shortcut-right"> <b>=</b> <%$v['name']%></div>
                            </div>
                            <%/foreach%>
                        </div>
                    </div>
                    <div class="shortcut-spotlight">
                        <div class="row">
                            <div class="span11 offset1 tabTitle">
                            <div class="span12 tabTitle">
                                <h2><%$this->lang->line('GENERIC_SPOTLIGHT_SEARCH')%></h2>
                                <%include file="shortcuts_keyboard.tpl"%>
                                <div class="vl1"></div>
                                <div class="dot dot1"></div>
                                <div class="vl2"></div>
                                <div class="dot dot2"></div>
                                <div class="hr1"></div>
                                <div class="dot dot3"></div>
                                <div class="spotlight-content-dummy"> <span>Ctrl  + Space </span>= <%$this->lang->line('GENERIC_SPOTLIGHT_SEARCH')%></div>
                                <div class="clear"></div>
                                <div class="spotlight-dummy">
                                    <div class="navigator-midd-dummy">
                                        <div class="spotlight-heading">
                                            <div class="left-title">
                                                <h4 >Spotlight Search</h4>
                                            </div>
                                            <div class="right-title">
                                                <input type="checkbox" value="Yes" name="spotlight-newtab" id="" class="regular-checkbox" />
                                                <label for="spotlight-newtab" class="frm-column-layout spotlight-checkbox">&nbsp;</label>
                                                <label for="spotlight-newtab" class="frm-column-layout"><%$this->lang->line('GENERIC_OPEN_IN_NEW_TAB')%></label>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="navigator-row">
                                            <div class="spotlight-input-dummy">Static Pages</div>
                                        </div>

                                    </div>
                                    <div class="spotlight-list-dummy">
                                        <ul class="" id="" tabindex="0" style="margin:0px">
                                            <li class="shortcut-opt"><span class=""></span>Tools</li>
                                            <li class="spotlight-list-item-dummy">
                                                <p class="" ><span class="down-child icon13 icomoon-icon-home-7"></span>State</p>
                                            </li>
                                            <li class="spotlight-list-item-dummy" >
                                                <p class="selected" ><span class="down-child icon13 silk-icon-notebook" style="color:#fff"></span>Static Pages</p>
                                            </li>
                                            <li class="shortcut-opt"><span class=""></span>Resources</li>
                                            <li class="spotlight-list-item-dummy" >
                                                <p class="" ><span class="down-child icon13 entypo-icon-email"></span>System Emails</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <%else%>
                    <div class="navigator-midd-dummy shortcuts-inactive-box">
                        <h4><%$this->lang->line('GENERIC_OOPS_SHORTCUTS_ARE_CURRENTLY_INACTIVE')%>. <br><a hijacked='yes' href="<%$shortcuts_activate_link%>"><%$this->lang->line('GENERIC_PLEASE_CLICK_HERE_TO_ACTIVATE')%>.</a></h4>
                    </div>
                <%/if%>
            </div>
        </div>
    </div>
</div>
<%if $this->input->is_ajax_request()%>
<%$this->js->js_src()%>
<%/if%>