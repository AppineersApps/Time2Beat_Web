<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%assign var="vNum" value=$data["mrn_version_number"]%>
<%assign var="release_notes" value=$this->release_notes_model->printReleaseNotesRec($vNum)%>
<!-- Print Record Block -->
<div id="print_this_content" class="print-view-container">
    <!-- Header Block -->
    <div class="heading" id="top_heading_fix">
        <!-- Top Strip -->
        <h3>
            <%$this->lang->line('GENERIC_PRINT_RECORD')%> :: <%$this->lang->line('RELEASE_NOTES_RELEASE_NOTES')%>
            <%if $recName neq ''%>
                :: <%$recName%>
            <%/if%>
        </h3>
        <!-- Print Layouts -->
        <%if $layout_combo|is_array && $layout_combo|@count gt 0%>
            <div class="frm-switch-drop frm-print-drop">
                <%$this->dropdown->display('vSwitchPrint',"vSwitchPrint","style='width:100%;' class='chosen-select' onchange='return switchModulePrintPage(\"<%$mod_enc_url.print_record%>\",this.value, \"<%$extra_qstr%>\")' ",'','',$layout)%>
            </div>
        <%/if%>
    </div>
    <div class="<%$module_name%>" data-list-name="<%$module_name%>">
        <div class="ajax-content-div top-frm-spacing ajax-print-block">
            <div id="scrollable_content" class="scrollable-content popup-content">
                <div id="release_notes" class="frm-view-block ">
                    <!-- Print Content Body -->
                    <div class="main-content-block " id="main_content_block">
                        <div style="width:98%;" class="frm-block-layout">
                            <div class="box gradient">
                                <!--<div class="title"></div>-->
                                <div class="content print-preview-padding print-listing-block" id="print_container">
                                    <div class="release-notes grid-data-container pad-calc-container" style="margin-right:16px;">
                                        <div class="margin_horizontal release-notes-dis">
                                            <div class="release-note" id="">
                                                <h2 style="padding-top:10px;"><%$vNum%><span class="date"><%$release_notes[0]['release_date']%></span></h2>
                                                <ul class="note_list">
                                                    <%foreach from=$release_notes key=k item=v%>
                                                    <li>
                                                        <%if $v['version_status'] eq 'New Feature'%>
                                                            <%assign var="tag_name" value="tag-new"%>
                                                        <%elseif $v['version_status'] eq 'Improvement'%>
                                                            <%assign var="tag_name" value="tag-changed"%>
                                                        <%else%>
                                                            <%assign var="tag_name" value="tag-fixed"%>
                                                        <%/if%>
                                                        <h4><%$v['title']%> <span class="tag <%$tag_name%>"><i class="fa fa-star"></i><%$v['version_status']%></span></h4>
                                                        <div class="note_dtl">
                                                            <%$v['description']%>
                                                        </div>    
                                                    </li>
                                                    <%/foreach%>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="popup-footer frm-bot-btn  bot-btn-cen">
                <div class="action-btn-align">
                    <button class="btn btn-info" id="footer_print_rec" onclick="printThisElementContent('scrollable_content', {'pageTitle': '<%$this->config->item("CPANEL_TITLE")%>'})">Print</button>
                    <input value="Discard" name="ctrldiscard" type="button" class="btn" onclick="closeWindow()">
                </div>
            </div>
        </div>
    </div>
</div>
<%$this->css->add_css("admin/release_notes.css")%>                    