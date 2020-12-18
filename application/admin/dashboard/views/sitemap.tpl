<%assign var="menu_arr" value=$total_arr['menu']%>
<%assign var="home_arr" value=$total_arr['home']%>
<%assign var="profile_arr" value=$total_arr['profile']%>
<%assign var="password_arr" value=$total_arr['password']%>
<%assign var="logout_arr" value=$total_arr['logout']%>
<%assign var="parent_arr" value=$menu_arr[0]%>
<div class="contentwrapper custom-sitemap-container">
    <%include file="sitemap_strip.tpl"%>
    <div id="ajax_content_div" class="ajax-content-div row-fluid home-page-boxes">
        <div id="ajax_qLoverlay"></div>
        <div id="ajax_qLbar"></div>
        <div id="scrollable_content" class="scrollable-content">
            <div class="sitemap-blocks pad-calc-container">
                <%assign var="close_map_block" value='1'%>
                <%section name=i loop=$parent_arr%>
                    <%assign var="child_arr" value=$menu_arr[$parent_arr[i]['id']]%>
                    <%assign var="k" value=$smarty.section.i.index%>
                    <%if $k%4 eq 0%> 
                    <div class="sitemap-items">
                    <%/if%>
                        <div class="span3 box">
                            <div class="title">
                                <h4><span class="icon14 <%$parent_arr[i]['icon']%>"></span><%$parent_arr[i]['label_lang']%></h4>
                            </div>
                            <div class="content box-height">
                                <%if $child_arr|@is_array && $child_arr|@count gt 0%>
                                    <ul class="sitemap">
                                        <%section name="j" loop=$child_arr%>
                                            <li>
                                                <a hijacked="yes" aria-nav-code="<%$child_arr[j]['code']%>" href="<%$child_arr[j]['url']%>" target="<%$child_arr[j]['target']%>" 
                                                   title="<%$child_arr[j]['label_lang']%>" class="nav-active-link <%$child_arr[j]['class']%>">
                                                    <span class="icon12 <%$child_arr[j]['icon']%>"></span>
                                                    <%$child_arr[j]['label_lang']%>
                                                </a>
                                            </li>
                                        <%/section%>
                                        <%if $parent_arr[i]['code'] == 'home'%>
                                            <li>
                                                <a hijacked="yes" aria-nav-code="<%$profile_arr['code']%>" href="<%$profile_arr['url']%>" title="<%$profile_arr['label_lang']%>">
                                                    <span class="icon12 <%$profile_arr['icon']%>"></span>
                                                    <%$profile_arr['label_lang']%>
                                                </a>
                                            </li>
                                            <li>
                                                <a hijacked="yes" aria-nav-code="<%$password_arr['code']%>" href="<%$password_arr['url']%>" title="<%$password_arr['label_lang']%>" class="fancybox-popup nav-active-link">
                                                    <span class="icon12 <%$password_arr['icon']%>"></span> 
                                                    <%$password_arr['label_lang']%>
                                                </a>
                                            </li>
                                        <%/if%>
                                    </ul>
                                <%elseif $parent_arr[i]['code'] == 'home'%>
                                    <ul class="sitemap">
                                        <li>
                                            <a hijacked="yes" aria-nav-code="<%$profile_arr['code']%>" href="<%$profile_arr['url']%>" title="<%$profile_arr['label_lang']%>">
                                                <span class="icon12 <%$profile_arr['icon']%>"></span>
                                                <%$profile_arr['label_lang']%>
                                            </a>
                                        </li>
                                        <li>
                                            <a hijacked="yes" aria-nav-code="<%$password_arr['code']%>" href="<%$password_arr['url']%>" title="<%$password_arr['label_lang']%>" class="fancybox-popup nav-active-link">
                                                <span class="icon12 <%$password_arr['icon']%>"></span> 
                                                <%$password_arr['label_lang']%>
                                            </a>
                                        </li>
                                    </ul>
                                <%/if%>
                            </div>
                        </div>
                        <%if $k%4 eq 3%> 
                        <div class="clear"></div>
                    </div>  
                        <%/if%>
                    <%if $parent_arr|@count eq ($k+1)%>
                        <%assign var="close_map_block" value='0'%>
                    <%/if%>
                <%sectionelse%>
                    <div align="center" class="errormsg"><%$this->lang->line('GENERIC_NO_SITEMAP_ITEMS_ADDED_YET')%></div>
                <%/section%>
                <%if $close_map_block eq '0'%>
                <div class="sitemap-items">
                <%/if%>
                <%*
                <div class="span3 box">
                    <div class="title"><h4><span class="icon14 icomoon-icon-exit"></span><%$logout_arr['label_lang']%></h4></div>
                    <div class="content box-height">
                        <ul class="sitemap">
                            <li>
                                <a hijacked="yes" href="javascript://" title="<%$logout_arr['label_lang']%>" class="admin-link-logout">
                                    <span class="icon12 <%$logout_arr['icon']%>"></span> <%$logout_arr['label_lang']%>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                *%>
                <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
</div>

