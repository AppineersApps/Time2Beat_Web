<div class="headingfix">
    <!-- Top Header Block -->
    <div class="heading" id="top_heading_fix">
		<!-- Top Strip Title Block -->
        <h3>
            <div class="screen-title">
                <%$this->lang->line('GENERIC_LISTING')%> :: 
                <%if $parent_switch_combo[$parID] neq ""%>
                    <%$parent_switch_combo[$parID]%> :: 
                <%/if%>
                <%$this->lang->line('STATE_STATE')%>
            </div>        
        </h3>
		<!-- Top Strip Dropdown Block -->
        <div class="header-right-drops">
            
            <!-- Parent Module SwitchTo Dropdown -->
            <%if $parMod neq "" && $parID neq ""%>
                
                <%if $parMod eq "country"%>     
                    <div class="frm-back-to frm-list-back">
                        <a hijacked="yes" href="<%$admin_url%>#<%$this->general->getAdminEncodeURL('tools/country/index')%><%$extra_hstr%>" class="backlisting-link" title="<%$this->general->parseLabelMessage('GENERIC_BACK_TO_MODULE_LISTING','#MODULE_HEADING#','COUNTRY_COUNTRY')%>">
                            <span class="icon16 minia-icon-arrow-left"></span>
                        </a>
                    </div>
                <%/if%>
                <div class="frm-switch-drop frm-list-switch">
                    <%if $parent_switch_combo|is_array && $parent_switch_combo|@count gt 0%>
                        <%assign var="enc_parID" value=$this->general->getAdminEncodeURL($parID)%>
                        <%$this->dropdown->display("vParentSwitchPage","vParentSwitchPage","style='width:100%;' aria-switchto-parent='<%$parent_switch_cit.param%>' class='chosen-select' onchange='return loadAdminModuleListingSwitch(\"<%$mod_enc_url.index%>\", this.value, \"<%$extra_hstr%>\")'","","",$enc_parID)%>
                    <%/if%>
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
        </div>
    </div>
</div>    