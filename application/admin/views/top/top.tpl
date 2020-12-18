<%assign var="logo_file_url" value=$this->general->getCompanyLogoURL()%>
<%assign var="admin_groups" value=$this->general->getAdminUserGroups()%>
<%assign var="total_arr" value=$this->systemsettings->getMenuArray("Top")%>
<%assign var="menu_arr" value=$total_arr['menu']%>
<%assign var="home_arr" value=$total_arr['home']%>
<%assign var="profile_arr" value=$total_arr['profile']%>
<%assign var="password_arr" value=$total_arr['password']%>
<%assign var="logout_arr" value=$total_arr['logout']%>
<%assign var="color_code" value=$total_arr['color_code']%>
<%assign var="notifications" value=$this->general->getNotifications()%>
<%assign var="notifications_active" value=$notifications['notifications_active']%>
<%assign var="notifications_count" value=$notifications['notifications_count']%>


<div class="top-bg <%$this->config->item('ADMIN_THEME_PATTERN_HEAD')%>" id="logo_template">
    <div class="container-fluid navbar">
        <div class="top-model-view logo">
            <div class="logo-left">
                <a hijacked="yes" href="<%$home_arr['url']%>" class="brand">
                    <%if $logo_file_url neq ''%>
                        <img alt="<%$this->config->item('COMPANY_NAME')%>" class="admin-logo-top" src="<%$logo_file_url%>" title="<%$this->config->item('COMPANY_NAME')%>">            
                    <%else%>
                        <div class='brand-logo-icon'></div>
                    <%/if%>
                </a>
            </div>
            <%* <div class="date-right">
                <div class="user-block">
                    <span class="loggedname">
                        <span class="icon16 icomoon-icon-user-2"></span>
                        <span id="logged_name" class='display' title="<%$this->session->userdata('vName')%>"><%$this->general->truncateChars($this->session->userdata("vName"), 21)%></span>
                    </span>
                </div>
                <div class="date-block">
                    <%assign var="now_date_time" value=$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"%>
                    <span><%$this->general->dateTimeSystemFormat($now_date_time)%></span>
                </div>
            </div> *%>
        </div>
        <div class="top-navigation-bar">
            <div class="translate-box-top-menu">
                <%if $this->config->item("ENABLE_PAGE_TRANSLATION") eq 1%>
                    <%include file="admin_page_translate.tpl"%>
                <%/if%>
            </div>
            <%if $admin_groups|@is_array && $admin_groups|@count gt 0%>
                <%assign var="default_group" value=$this->session->userdata('iGroupId')%>
                <div class="lang-combo">
                    <span class="lang-box">
                        <select name="topGroupCombo" id="topGroupCombo" class="chosen-select lang-combo">
                            <%html_options options=$admin_groups selected=$default_group%>
                        </select>
                    </span>
                </div>
            <%/if%>
            <%if $this->config->item('MULTI_LINGUAL_PROJECT') eq 'Yes'%>
                <%assign var='topDefLang' value=$this->config->item('DEFAULT_LANG')%>
                <%assign var='topPrimeLang' value=$this->config->item('PRIME_LANG')%>
                <%assign var='topOtherLang' value=$this->config->item('OTHER_LANG')%>
                <%assign var='top_lang_data' value=$this->config->item('LANG_INFO')%>
                <div class="lang-combo">
                    <span class="lang-box">
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
                    </span>
                </div>
            <%/if%>
            <div class="top-menu <%$this->config->item('ADMIN_THEME_PATTERN_LEFT')%>">
                <ul id="navTopMenu">
                    <%assign var="parent_arr" value=$menu_arr[0][1]%>
                    <%if $menu_arr|@is_array && $menu_arr|@count gt 0%>
                        <%section name="i" loop=$parent_arr%>
                            <%assign var="child_arr" value=$menu_arr[$parent_arr[i]['id']]%>
                            <li id="parent_menu_<%$parent_arr[i]['id']%>" class="top parent-menu-li">
                                <a hijacked="yes" class="top_link top-parent-menu" href="javascript://" title="<%$parent_arr[i]['label_lang']%>"> 
                                    <span class="down">
                                        <i class="icon15 <%$parent_arr[i]['icon']%>"></i>
                                        <%$parent_arr[i]['label_lang']%>
                                    </span>
                                    <i class="icon16 icomoon-icon-arrow-down-2"></i>
                                </a>
                                <%if $child_arr|@is_array && $child_arr|@count gt 0%>
                                    <ul class="sub top-menu-<%$parent_arr[i]['code']%> menu-style-list-1" >
                                        <%section name="j" loop=$child_arr[1]%>
                                            <%assign var="nested_arr" value=$menu_arr[$child_arr[1][j]['id']]%>
                                            <li class="child-menu-li">
                                                <a hijacked="yes" aria-nav-code="<%$child_arr[1][j]['code']%>" href="<%$child_arr[1][j]['url']%>" target="<%$child_arr[1][j]['target']%>" 
                                                   title="<%$child_arr[1][j]['label_lang']%>" class="top-child-menu <%$child_arr[1][j]['class']%>">
                                                    <span class="down-child icon13 <%$child_arr[1][j]['icon']%>"></span> 
                                                    <%$child_arr[1][j]['label_lang']%>
                                                    <%if $nested_arr|@is_array && $nested_arr|@count gt 0%>
                                                        <i class="icon16 icomoon-icon-arrow-down-2 child-arrow"></i>
                                                    <%/if%>
                                                </a>
                                                <%if $nested_arr|@is_array && $nested_arr|@count gt 0%>
                                                    <ul class="nes top-menu-<%$child_arr[1][j]['code']%> menu-style-list-4" >
                                                        <%section name="k" loop=$nested_arr[1]%>
                                                            <li class="nested-menu-li">
                                                                <a hijacked="yes" aria-nav-code="<%$nested_arr[1][k]['code']%>" href="<%$nested_arr[1][k]['url']%>" target="<%$nested_arr[1][k]['target']%>" 
                                                                   title="<%$nested_arr[1][k]['label_lang']%>" class="top-nested-menu <%$nested_arr[1][k]['class']%>">
                                                                    <span class="down-child icon13 <%$nested_arr[1][k]['icon']%>"></span> 
                                                                    <%$nested_arr[1][k]['label_lang']%>
                                                                </a>
                                                            </li>
                                                        <%/section%>
                                                    </ul>
                                                <%/if%>
                                            </li>
                                        <%/section%>
                                        <%if $parent_arr[i]['code'] == 'home'%>
                                            <li class="child-menu-li">
                                                <a hijacked="yes" aria-nav-code="<%$profile_arr['code']%>" href="<%$profile_arr['url']%>" title="<%$profile_arr['label_lang']%>">
                                                    <span class="down-child icon13 <%$profile_arr['icon']%>"></span>
                                                    <%$profile_arr['label_lang']%>
                                                </a>
                                            </li>
                                            <li class="child-menu-li">
                                                <a hijacked="yes" aria-nav-code="<%$password_arr['code']%>" href="<%$password_arr['url']%>" title="<%$password_arr['label_lang']%>" class="fancybox-popup">
                                                    <span class="down-child icon13 <%$password_arr['icon']%>"></span>
                                                    <%$password_arr['label_lang']%>
                                                </a>
                                            </li>
                                        <%/if%>
                                    </ul>
                                    <%if $child_arr[2]|@is_array && $child_arr[2]|@count gt 0%>
                                        <ul class="sub top-menu-<%$parent_arr[i]['code']%> menu-style-list-2" >
                                            <%section name="k" loop=$child_arr[2]%>
                                                <li class="child-menu-li">
                                                    <a hijacked="yes" aria-nav-code="<%$child_arr[2][j]['code']%>" href="<%$child_arr[2][k]['url']%>" target="<%$child_arr[2][k]['target']%>" 
                                                       title="<%$child_arr[2][k]['label_lang']%>" class="<%$child_arr[2][j]['class']%>">
                                                        <span class="down-child icon13 <%$child_arr[2][k]['icon']%>"></span> 
                                                        <%$child_arr[2][k]['label_lang']%>
                                                    </a>
                                                </li>
                                            <%/section%>
                                        </ul>
                                        <%if $child_arr[3]|@is_array && $child_arr[3]|@count gt 0%>
                                            <ul class="sub top-menu-<%$parent_arr[i]['code']%> menu-style-list-3" >
                                                <%section name="l" loop=$child_arr[3]%>
                                                    <li class="child-menu-li">
                                                        <a hijacked="yes" aria-nav-code="<%$child_arr[3][j]['code']%>" href="<%$child_arr[3][l]['url']%>" target="<%$child_arr[3][l]['target']%>" 
                                                           title="<%$child_arr[3][l]['label_lang']%>" class="<%$child_arr[3][j]['class']%>">
                                                            <span class="down-child icon13 <%$child_arr[3][l]['icon']%>"></span> 
                                                            <%$child_arr[3][l]['label_lang']%>
                                                        </a>
                                                    </li>
                                                <%/section%>
                                            </ul>
                                        <%/if%>
                                    <%/if%>
                                <%elseif $parent_arr[i]['code'] == 'home'%>
                                    <ul class="sub top-menu-<%$parent_arr[i]['code']%>" >
                                        <li class="child-menu-li">
                                            <a hijacked="yes" aria-nav-code="<%$profile_arr['code']%>" href="<%$profile_arr['url']%>" title="<%$profile_arr['label_lang']%>">
                                                <span class="down-child icon13 <%$profile_arr['icon']%>"></span>
                                                <%$profile_arr['label_lang']%>
                                            </a>
                                        </li>
                                        <li class="child-menu-li">
                                            <a hijacked="yes" aria-nav-code="<%$password_arr['code']%>" href="<%$password_arr['url']%>" title="<%$password_arr['label_lang']%>" class="fancybox-popup">
                                                <span class="down-child icon13 <%$password_arr['icon']%>"></span>
                                                <%$password_arr['label_lang']%>
                                            </a>
                                        </li>
                                    </ul>
                                <%/if%>
                            </li>
                        <%/section%>
                    <%/if%>    
                    <%if $notifications['notifications_active'] eq 'Y'%>
                    <li class="top parent-menu top-notification fixed-top-menu-item" id="notification-menu-item">
                        <a hijacked="yes" href="#" class="top_link top-parent-menu-li" >
                            <span class="icon14  icomoon-icon-bell-2" ></span>
                            <%if $notifications['count'] gt 0%>
                                <span class="top-notification-badge"><%$notifications['count']%></span>
                            <%/if%>
                        </a>
                        <ul class="sub top-menu- menu-style-list-1 notification-list">
                            <li class="top-notification-heading">
                                <%if $notifications_count eq '1'%>
                                    <%assign var="fullTab" value='selected full-tab'%>
                                <%/if%>
                                <%if $notifications['admin_notifications_active'] eq 'Y'%>
                                    <%assign var="hide_desktop" value='Style="display:none;"'%>
                                    <div class="top-notification-heading-left selected <%$fullTab%>" <%$notifications_count%>>General<a class="viewall" href="<%$notifications['admin_listing_url']%>"><i class="fa fa-arrow-circle-right fa-16"></i></a></div>
                                <%/if%>
                                <%if $notifications['desktop_notifications_active'] eq 'Y'%>
                                    <div class="top-notification-heading-right <%$fullTab%>">Desktop <a class="viewall" href="<%$notifications['desktop_listing_url']%>"><i class="fa fa-arrow-circle-right fa-16"></i></a></div>
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
                                                <a hijacked="yes" href="<%$val['url']%>" class="fancybox-popup view-notifications" >
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
                    <li class="top initials fixed-top-menu-item" id="profile-menu-item">
                        <a hijacked="yes" href="javascript://"  class="top_link top-parent-menu-li" title="Profile"> 
                            <img data-name="<%$this->session->userdata('vName')%>" data-color="#fff" class="profile" width="35px" height="35px" data-radius='50' data-char-count='2' data-text-color='<%$color_code%>' data-font-size='40' />
                        </a>
                        <ul class="sub top-menu- menu-style-list-1 initials-list" style="height: auto;">
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
        </div>
    </div>
    <div class="clear"></div>
</div>
