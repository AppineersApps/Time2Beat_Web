<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-custom-container">
    <%include file="release_notes_preview_strip.tpl"%>
    <div class="<%$module_name%>" data-custom-name="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing">
            <div class="release-notes grid-data-container pad-calc-container">
                <div class="centerdiv">
                    <%if $release_notes|is_array && $release_notes|count gt 0%>
                    <div class="wrapper cf"> 
                        <!--sidebar-->
                        <div id="notes-sidebar" class="notes-sidebar">
                            <div class="sidebar-inside">
                                <div class="sticky-wrapper">
                                    <div id="left-content-list" class="left-menu release-note-menu">
                                        <ul class="navigation" id="left-content-block" data-default-id="<%$release_id%>">
                                            <%foreach from=$release_notes item=note name=menu%>	
                                            <li class='left-content-item'> 
                                                <a href="javascript://" title="<%$note.version_number%>" class="left-content-anc <%if $smarty.foreach.menu.index eq 0%>active<%/if%>" data-note-id="<%$note.notes_id%>"> 
                                                    <span class="date">
                                                        <%if $note.release_status eq 'Draft'%>
                                                            Not Released
                                                        <%else%>
                                                            <%$note.release_date%>
                                                        <%/if%>
                                                    </span> 
                                                    <span class="rversionno"><%$note.version_number%></span> 
                                                </a>
                                            </li>
                                            <%/foreach%>  
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end sidebar--> 
                        <!--main content-->
                        <div class="main_content" id="content">
                            <div id="content" class="content">
                                <div class="margin_horizontal cont_main release-notes-dis" id="helpdata">
                                <%foreach from=$release_details key=version item=details%>	
                                    <div id="note-content-<%$release_notes[$version]['notes_id']%>" class="release-note">
                                        <h2>
                                            <%$version%>
                                            <span class="date">
                                                <%if $release_notes[$version]['release_status'] eq 'Draft'%>
                                                    Not Released
                                                <%else%>
                                                    <%$release_notes[$version]['release_date']%>
                                                <%/if%>
                                            </span>
                                            <%if $release_notes[$version]['app_versions']|is_array && $release_notes[$version]['app_versions']|count gt 0%>
                                            <span class="app-image-block">
                                            <%foreach from=$release_notes[$version]['app_versions'] key=app_version item=app_value%>
                                                <%if $app_value['type'] eq 'Android'%>
                                                    <a href="<%$app_value['app_url']%>" class="app-image-anc tip android" hijacked="yes" title="<%$app_value['app_name']%>" target="blank"><i class="fa fa-android app-image-fonts"></i></a>
                                                <%else%>
                                                    <a href="<%$app_value['app_url']%>" class="app-image-anc tip ios" hijacked="yes" title="<%$app_value['app_name']%>" target="blank"><i class="fa fa-apple app-image-fonts"></i></a>
                                                <%/if%>
                                            <%/foreach%>
                                            </span>
                                            <%/if%>
                                        </h2>
                                        <ul class="note_list">
                                            <%foreach from=$details item=item%>
                                            <li>
                                                <h4>
                                                    <%$item.title%>
                                                    <span class="tag <%$item.class_name%>"><i class="fa fa-star"></i>
                                                        <%$item.status%>	  
                                                    </span>
                                                </h4>
                                                <div class="note_dtl">
                                                    <p><%$item.description%></p>
                                                </div>
                                            </li>
                                            <%/foreach%>	  
                                        </ul>
                                    </div>
                                <%/foreach%>
                                </div>
                            </div>
                        </div>
                        <!--end main content--> 
                    </div>
                    <%else%>
                        <div align="center" class="text-error">
                            No release notes added yet.
                        </div>
                    <%/if%>
                </div>
            </div>
         </div>
    </div>
</div>

<%$this->css->add_css("admin/release_notes.css")%>
<%$this->js->add_js("admin/admin/js_release_notes.js")%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 

