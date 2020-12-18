<!-- Module Form Page -->
<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <!-- Top Strip Title Block -->
        <h3>
            <div class="screen-title">
                <%assign var="mod_label_text" value=$this->general->getDisplayLabel("Generic",$mode,"label")%>
                <%$this->lang->line($mod_label_text)%> :: 
                <%if $parent_switch_combo[$parID] neq ""%>
                    <%$parent_switch_combo[$parID]%> :: 
                <%/if%>
                <%$this->lang->line('STATE_STATE')%>
                <%if $mode eq 'Update' && $recName neq ''%>
                    :: <%$recName%> 
                <%/if%>
            </div>
        </h3>
        <!-- Top Strip Buttons Block -->
        <div class="header-right-btns">
            <!-- BackLink Icon -->
            <%if $backlink_allow eq true%>
                <div class="frm-back-to">
                    <a hijacked="yes" href="<%$admin_url%>#<%$mod_enc_url['index']%><%$extra_hstr%>"class="backlisting-link" title="<%$this->general->parseLabelMessage('GENERIC_BACK_TO_MODULE_LISTING','#MODULE_HEADING#','STATE_STATE')%>">
                        <span class="icon16 minia-icon-arrow-left"></span>
                    </a>
                </div>
            <%/if%>
            <!-- Next Button -->
            <%if $next_link_allow eq true%>
                <div class="frm-next-rec">
                    <a hijacked="yes" title="<%$next_prev_records['next']['val']%>" href="<%$admin_url%>#<%$mod_enc_url['add']%>|mode|<%$mod_enc_mode['Update']%>|id|<%$next_prev_records['next']['enc_id']%><%$extra_hstr%>" class='btn next-btn'>
                        <%$this->lang->line('GENERIC_NEXT_SHORT')%> <span class='icon12 icomoon-icon-arrow-right'></span>
                    </a>
                </div>
            <%/if%>
            <!-- SwitchTo Dropdown -->
            <%if $switchto_allow eq true%>
                <div class="frm-switch-drop">
                     <%if $switch_combo|is_array && $switch_combo|@count gt 0%>
                        <%$this->dropdown->display('vSwitchPage',"vSwitchPage","style='width:100%;' aria-switchto-self='<%$switch_cit.param%>' class='chosen-select' onchange='return loadAdminModuleAddSwitchPage(\"<%$mod_enc_url.add%>\",this.value, \"<%$extra_hstr%>\")' ",'','',$enc_id)%>
                    <%/if%>
                </div>
            <%/if%>
            <!-- Previous Button -->
            <%if $prev_link_allow eq true%>  
                <div class="frm-prev-rec">
                    <a hijacked="yes" title="<%$next_prev_records['prev']['val']%>" href="<%$admin_url%>#<%$mod_enc_url['add']%>|mode|<%$mod_enc_mode['Update']%>|id|<%$next_prev_records['prev']['enc_id']%><%$extra_hstr%>" class='btn prev-btn'>
                        <span class='icon12 icomoon-icon-arrow-left'></span> <%$this->lang->line('GENERIC_PREV_SHORT')%>
                    </a>            
                </div>
            <%/if%>
            
            <!-- Top Detail View Icons -->
            <%if $top_detail_view["exists"] eq "1"%>
                <div class="frm-detail-view">
                    <%if $top_detail_view["flag"] eq "1"%>
                        <a href="javascript://" class="tipR active hide-top-detail-view" title="Show View" id="hide_top_view" aria-module-name="<%$this->general->getMD5EncryptString('DetailView','state')%>" onclick="return hideShowTopView(this);">
                            <span><i id="top_show_view_content" class="minia-icon-list icon14"></i></span>
                        </a>
                    <%else%>
                        <a href="javascript://" class="tipR" title="Hide View" id="hide_top_view" aria-module-name="<%$this->general->getMD5EncryptString('DetailView','state')%>" onclick="return hideShowTopView(this);">
                            <span><i id="top_show_view_content" class="minia-icon-list icon14"></i></span>
                        </a>
                    <%/if%>
                </div>
            <%/if%>
            
            <div class="clear"></div>
        </div>
        <span style="display:none;position:inherit;" id="ajax_lang_loader"><i class="fa fa-refresh fa-spin-light fa-2x fa-fw"></i></span>
    </div>
</div>