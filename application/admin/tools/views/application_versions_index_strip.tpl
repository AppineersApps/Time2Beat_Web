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
                <%$this->lang->line('APPLICATION_VERSIONS_APPLICATION_VERSIONS')%>
            </div>        
        </h3>
		<!-- Top Strip Dropdown Block -->
        <div class="header-right-drops">
            
            <!-- Parent Module SwitchTo Dropdown -->
            <%if $parMod neq "" && $parID neq ""%>
                <div class="frm-switch-drop frm-list-switch">
                    <%if $parent_switch_combo|is_array && $parent_switch_combo|@count gt 0%>
                        <%assign var="enc_parID" value=$this->general->getAdminEncodeURL($parID)%>
                        <%$this->dropdown->display("vParentSwitchPage","vParentSwitchPage","style='width:100%;' aria-switchto-parent='<%$parent_switch_cit.param%>' class='chosen-select' onchange='return loadAdminModuleListingSwitch(\"<%$mod_enc_url.index%>\", this.value, \"<%$extra_hstr%>\")'","","",$enc_parID)%>
                    <%/if%>
                </div>
            <%/if%>
            
        </div>
    </div>
</div>    