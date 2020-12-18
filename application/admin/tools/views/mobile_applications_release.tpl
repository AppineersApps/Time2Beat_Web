<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-custom-container">
    <%include file="mobile_applications_release_strip.tpl"%>
    <div class="<%$module_name%>" data-custom-name="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing">
            <div class="grid-data-container pad-calc-container">
                <%if $applications|@is_array && $applications|@count gt 0%>
                    <div class="l-section">
                        <div class="c-flex is--hotandnew hotandnew-block" id="mobile_apps">
                            <%section name=i loop=$applications%>
                                <%assign var="version_data" value=$applications[i]['versions_list']%>
                                <div class="c-flex__item is--contrast w-inline-block">
                                    <h3 class="c-tool-title">
                                        <%$applications[i]['mam_application_name']%>
                                        <span class="application-icons">
                                            <%if $applications[i]['mam_device_type'] eq 'Android'%>
                                                <a href="javascript://"><i class="fa fa-android app-icon-fonts"></i></a>
                                            <%else%>
                                                <a href="javascript://"><i class="fa fa-apple app-icon-fonts"></i></a>
                                            <%/if%>
                                        </span>
                                    </h3>
                                    <ul class="nav nav-tabs"></ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade in active scrollbars" id="list-<%$applications[i]['mam_application_master_id']%>">
                                            <ul>
                                                <%section name=j loop=$version_data%>
                                                    <li>
                                                        <a href="<%$version_data[j]['application_url']%>" target="_blank">
                                                            <span class="rversionno"><%$version_data[j]['mav_version_number']%></span>
                                                            <span class="date"><%$this->general->dateSystemFormat($version_data[j]['mav_date_published'])%></span> 
                                                        </a>
                                                    </li>
                                                <%/section%>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <%/section%>
                        </div>
                    </div>
                <%else%>
                    <div align="center" class="text-error">
                        No mobile application released yet.
                    </div>
                <%/if%>
            </div>
        </div>
    </div>
</div>
<%$this->css->add_css("admin/mobile_applications.css")%>
<%$this->js->add_js("admin/admin/js_mobile_applications.js")%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 