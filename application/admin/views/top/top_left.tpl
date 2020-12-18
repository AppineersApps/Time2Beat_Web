<%assign var="logo_file_url" value=$this->general->getCompanyLogoURL()%>
<%assign var="admin_groups" value=$this->general->getAdminUserGroups()%>
<%assign var="total_arr" value=$this->systemsettings->getMenuArray("Left")%>
<%assign var="menu_arr" value=$total_arr['menu']%>
<%assign var="home_arr" value=$total_arr['home']%>
<%assign var="profile_arr" value=$total_arr['profile']%>
<%assign var="password_arr" value=$total_arr['password']%>
<%assign var="logout_arr" value=$total_arr['logout']%>
<%assign var="parent_arr" value=$menu_arr[0]%>
<%assign var="color_code" value=$total_arr['color_code']%>
<%assign var="notifications" value=$this->general->getNotifications()%>
<%assign var="notifications_active" value=$notifications['notifications_active']%>

<div class="top-bg left-model-view <%$this->config->item('ADMIN_THEME_PATTERN_HEAD')%>" id="logo_template">
    <div class="logo container-fluid navbar">
        <a hijacked="yes" href="<%$home_arr['url']%>" class="brand">
            <%if $logo_file_url neq ''%>
                <img alt="<%$this->config->item('COMPANY_NAME')%>" class="admin-logo-top" src="<%$logo_file_url%>" title="<%$this->config->item('COMPANY_NAME')%>">
            <%else%>
                <div class='brand-logo-icon'></div>
            <%/if%>
        </a>
    </div>
    <div class="profile-menu top-left-initials">
        <ul>
            <%if $notifications['notifications_active'] eq 'Y'%>
                <li class="top parent-menu-li top-notification top-left-notification">
                <a href="#" class="top_link">
                    <span class="icon14  icomoon-icon-bell-2" ></span>
                    <%if $notifications['count'] gt 0%>
                        <span class="top-left-notification-badge"><%$notifications['count']%></span>
                    <%/if%>
                </a>
                <ul class="sub profile-menu-container menu-style-list-1 top-left-notification-list" style="height: auto;">
                    <li class="top-notification-heading">
                        <%if $notifications_count eq '1'%>
                            <%assign var="fullTab" value='selected full-tab'%>
                        <%/if%>
                        <%if $notifications['admin_notifications_active'] eq 'Y'%>
                            <%assign var="hide_desktop" value='Style="display:none;"'%>
                            <div class="top-notification-heading-left selected <%$fullTab%> ">General<a class="viewall"  href="<%$notifications['admin_listing_url']%>"><i class="fa fa-arrow-circle-right fa-16"></i></a></div>
                        <%/if%>
                        <%if $notifications['desktop_notifications_active'] eq 'Y'%>
                            <div class="top-notification-heading-right <%$fullTab%> ">Desktop<a class="viewall" href="<%$notifications['desktop_listing_url']%>"><i class="fa fa-arrow-circle-right fa-16"></i></a></div>
                        <%/if%>
                        <div class="clear"></div>
                    </li> 
                    <%if $notifications|@is_array && $notifications|@count gt 0%>
                        <%if $notifications['admin_notifications_active'] eq 'Y'%>
                            <%if $notifications['data']|@count eq 0%>
                                <li class="top-notification-content no-not-content">
                                    <a hijacked="yes" href="#" class="view-notifications" >
                                        <span class="down-child icon13 icomoon-icon-error"></span>
                                        <span class="message">No Notifications</span>
                                    </a>
                                </li>
                            <%else%>
                                <%foreach from=$notifications['data'] item=val%>
                                    <li class="top-notification-content">
                                        <a hijacked="yes" href="<%$val['url']%>" class="fancybox-popup">
                                            <span class="<%$val['icon']%>"></span>
                                            <%if $val['is_read'] eq 'No'%>
                                                <span class="message"><strong><%$val['message']%></strong></span>
                                            <%else%>
                                                <span class="message"><%$val['message']%></span>
                                            <%/if%>
                                            <span class="time"><%$val['time']%></span>
                                        </a>
                                    </li>
                                <%/foreach%>
                            <%/if%>   
                        <%/if%>    
                        <%if $notifications['desktop_notifications_active'] eq 'Y'%>    
                            <%if $notifications['desktop']|@count eq 0%>
                                <li class="top-notification-content top-notification-desktop no-not-content" <%$hide_desktop%> >
                                    <a hijacked="yes" href="#" class="view-notifications" >
                                        <span class="down-child icon13 icomoon-icon-error"></span>
                                        <span class="message">No Notifications</span>
                                    </a>
                                </li>
                            <%else%>       
                                <%foreach from=$notifications['desktop'] item=val%>
                                    <li class="top-notification-content top-notification-desktop" <%$hide_desktop%> >
                                        <a hijacked="yes" href="<%$val['url']%>" class="fancybox-popup view-notifications" >
                                            <span class="<%$val['icon']%>"></span>
                                            <span class="message"><%$val['message']%></span>
                                            <span class="time"><%$val['time']%></span>
                                        </a>
                                    </li>
                                <%/foreach%>
                            <%/if%>  
                        <%/if%>    
                    <%/if%>
                </ul>
            </li>
            <%/if%>
            <li class="top parent-menu-li top-left-initials-li">
                <a hijacked="yes" class="top_link top-parent-menu-li" href="javascript://" title="Profile"> 
                    <img data-name="<%$this->session->userdata('vName')%>" data-color="#fff" class="left-profile" width="35px" height="35px" data-radius='50' data-char-count='2' data-text-color='<%$color_code%>' data-font-size='40' />
                </a>
                <ul class="sub profile-menu-container menu-style-list-1 initials-list" style="height: auto;">
                    <%if $profile_arr|@is_array && $menu_arr|@count gt 0%>
                        <li class="child-menu-li">
                            <a hijacked="yes" aria-nav-code="<%$profile_arr['code']%>" href="<%$profile_arr['url']%>" title="<%$profile_arr['label_lang']%>">
                                <span class="down-child icon13 <%$profile_arr['icon']%>"></span>
                                <%$profile_arr['label_lang']%>
                            </a>
                        </li>
                    <%/if%>    
                    <%if $password_arr|@is_array && $menu_arr|@count gt 0%>
                        <li class="child-menu-li">
                            <a hijacked="yes" aria-nav-code="<%$password_arr['code']%>" href="<%$password_arr['url']%>" title="<%$password_arr['label_lang']%>" class="fancybox-popup">
                                <span class="down-child icon13 <%$password_arr['icon']%>"></span>
                                <%$password_arr['label_lang']%>
                            </a>
                        </li>
                    <%/if%>    
                    <li class="child-menu-li">
                        <a hijacked="yes" href="javascript://" title="<%$logout_arr['label_lang']%>" class="top-child-menu admin-link-logout"> 
                            <span class="icon15 icomoon-icon-exit"></span> <%$logout_arr['label_lang']%>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>

    <div class="toprightarea">
        <%* <div class="date-right">
            <%assign var="now_date_time" value=$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"%>
            <span><%$this->general->dateTimeSystemFormat($now_date_time)%></span>
        </div> *%>
        
        <%if $this->config->item('MULTI_LINGUAL_PROJECT') eq 'Yes'%>
            <%assign var='topDefLang' value=$this->config->item('DEFAULT_LANG')%>
            <%assign var='topPrimeLang' value=$this->config->item('PRIME_LANG')%>
            <%assign var='topOtherLang' value=$this->config->item('OTHER_LANG')%>
            <%assign var='top_lang_data' value=$this->config->item('LANG_INFO')%>
           <span class="lang-box gray-bg right">
                
                <%if $admin_groups|@is_array && $admin_groups|@count gt 0%>
                    <%assign var="default_group" value=$this->session->userdata('iGroupId')%>
                    <div class="lang-drop">
                        <select name="topGroupCombo" id="topGroupCombo" class="chosen-select lang-combo">
                            <%html_options options=$admin_groups selected=$default_group%>
                        </select>
                    </div>
                <%/if%>
                <div class="lang-drop">
                    <select name="topLangCombo" id="topLangCombo" class="chosen-select lang-combo">
                        <option value="<%$topPrimeLang%>" <%if $topDefLang eq $topPrimeLang%> selected= true <%/if%>>
                            <%$top_lang_data[$topPrimeLang]['vLangTitle']%>
                        </option>
                        <%if (is_array($topOtherLang)) && ($topOtherLang|@count gt 0)%>
                            <%section name=i loop=$topOtherLang %>
                            <option value="<%$topOtherLang[i]%>" <%if $topDefLang eq $topOtherLang[i]%> selected=true <%/if%>>
                                <%$top_lang_data[$topOtherLang[i]]['vLangTitle']%> 
                            </option>
                            <%/section%>
                        <%/if%>
                    </select>
                </div>
            </span>
        <%/if%>
        <div class="translate-box-left-menu">
            <%if $this->config->item("ENABLE_PAGE_TRANSLATION") eq 1%>
                <%include file="admin_page_translate.tpl"%>
            <%/if%>
        </div>
    </div>
</div>
<div class="clear"></div>

<div class="collapseBtn leftbar" id="collapse_btn">
    <a class="left-menu-hide tipR" href="javascript://" title="<%$this->lang->line('GENERIC_HIDE_SIDEBAR')%>"><span class="icon14 minia-icon-list-3"></span></a>
</div>

<div id="sidebarbg" class="sidebarbg-main <%$this->config->item('ADMIN_THEME_PATTERN_LEFT')%>"></div>
<div id="sidebar" class="sidebar-main">
    <div class="sidenav">
        <%if $menu_arr|@is_array && $menu_arr|@count gt 0%>
            <div id="sidebar_widget" class="sidebar-widget">
                <h5 class="title"><span class="sidebar-navigation"><%$this->lang->line('GENERIC_NAVIGATION')%></span></h5>
            </div>
            <div class="clear"></div>
            <div id="left_mainnav" class="mainnav">
                <ul>
                    <%section name="i" loop=$parent_arr%>
                        <%assign var="child_arr" value=$menu_arr[$parent_arr[i]['id']]%>
                        <li id="parent_menu_<%$parent_arr[i]['id']%>" class="parent-menu-li">
                            <a class="menu-parent-anchor" href="javascript://" title="<%$parent_arr[i]['label_lang']%>" >
                                <span class="menu-parent-anchor-span icon16 <%$parent_arr[i]['icon']%>"></span>
                                <%$parent_arr[i]['label_lang']%>
                            </a>
                            <%if $child_arr|@is_array && $child_arr|@count gt 0%>
                                <ul class="sub">
                                    <%section name="j" loop=$child_arr%>
                                        <li class="child-menu-li">
                                            <a hijacked="yes" class="menu-child-anchor <%$child_arr[j]['class']%>" aria-nav-code="<%$child_arr[j]['code']%>" href="<%$child_arr[j]['url']%>" 
                                               target="<%$child_arr[j]['target']%>" title="<%$child_arr[j]['label_lang']%>">
                                                <span class="menu-child-anchor-span icon14 <%$child_arr[j]['icon']%>"></span> 
                                                <%$child_arr[j]['label_lang']%>
                                            </a>
                                            <%assign var="nested_arr" value=$menu_arr[$child_arr[j]['id']]%>
                                            <%if $nested_arr|@is_array && $nested_arr|@count gt 0%>
                                                <ul class="sub nes">
                                                    <%section name="k" loop=$nested_arr%>
                                                        <li class="nested-menu-li">
                                                            <a hijacked="yes" class="menu-nested-anchor <%$nested_arr[k]['class']%>" aria-nav-code="<%$nested_arr[j]['code']%>" href="<%$nested_arr[k]['url']%>" 
                                                               target="<%$nested_arr[k]['target']%>" title="<%$nested_arr[k]['label_lang']%>">
                                                                <span class="menu-child-anchor-span icon14 <%$nested_arr[k]['icon']%>"></span> 
                                                                <%$nested_arr[k]['label_lang']%>
                                                            </a>
                                                        </li>
                                                    <%/section%>
                                                </ul>
                                            <%/if%>
                                        </li>
                                    <%/section%>
                                    <%if $parent_arr[i]['code'] == 'home'%>
                                        <li class="child-menu-li">
                                            <a hijacked="yes" class="menu-child-anchor" aria-nav-code="<%$profile_arr['code']%>" href="<%$profile_arr['url']%>" title="<%$profile_arr['label_lang']%>">
                                                <span class="menu-child-anchor-span icon14 <%$profile_arr['icon']%>"></span>
                                                <%$profile_arr['label_lang']%>
                                            </a>
                                        </li>
                                        <li class="child-menu-li">
                                            <a hijacked="yes" class="menu-child-anchor fancybox-popup" aria-nav-code="<%$password_arr['code']%>" href="<%$password_arr['url']%>" title="<%$password_arr['label_lang']%>">
                                                <span class="menu-child-anchor-span icon14 <%$password_arr['icon']%>"></span>
                                                <%$password_arr['label_lang']%>
                                            </a>
                                        </li>
                                    <%/if%>
                                </ul>
                            <%elseif $parent_arr[i]['code'] == 'home'%>
                                <ul class="sub">
                                    <li class="child-menu-li">
                                        <a hijacked="yes" class="menu-child-anchor" href="<%$profile_arr['url']%>" title="<%$profile_arr['label_lang']%>">
                                            <span class="menu-child-anchor-span icon14 <%$profile_arr['icon']%>"></span>
                                            <%$profile_arr['label_lang']%>
                                        </a>
                                    </li>
                                    <li class="child-menu-li">
                                        <a hijacked="yes" aria-nav-code="<%$password_arr['code']%>" href="<%$password_arr['url']%>" title="<%$password_arr['label_lang']%>" class="menu-child-anchor fancybox-popup">
                                            <span class="menu-child-anchor-span icon14 <%$password_arr['icon']%>"></span>
                                            <%$password_arr['label_lang']%>
                                        </a>
                                    </li>
                                </ul>
                            <%/if%>
                        </li>
                    <%/section%>
                </ul>
            </div>
        </div>
    <%/if%>         
</div>
