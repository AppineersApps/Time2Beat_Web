<div class="headingfix">
    <!-- Top Header Block -->
    <div class="heading" id="top_heading_fix">
		<!-- Top Strip Title Block -->
        <h3>
            <div class="screen-title">
                <%$this->lang->line('GENERIC_LISTING')%> :: <%$this->lang->line('COUNTRY_COUNTRY')%>
            </div>        
        </h3>
		<!-- Top Strip Dropdown Block -->
        <div class="header-right-drops">
            
            
            <!-- Top Detail View Icons -->
            <%if $top_detail_view["exists"] eq "1"%>
                <div class="frm-detail-view">
                    <%if $top_detail_view["flag"] eq "1"%>
                        <a href="javascript://" class="tipR active hide-top-detail-view" title="Show View" id="hide_top_view" aria-module-name="<%$this->general->getMD5EncryptString('DetailView','country')%>" onclick="return hideShowTopView(this);">
                            <span><i id="top_show_view_content" class="minia-icon-list icon14"></i></span>
                        </a>
                    <%else%>
                        <a href="javascript://" class="tipR" title="Hide View" id="hide_top_view" aria-module-name="<%$this->general->getMD5EncryptString('DetailView','country')%>" onclick="return hideShowTopView(this);">
                            <span><i id="top_show_view_content" class="minia-icon-list icon14"></i></span>
                        </a>
                    <%/if%>
                </div>
            <%/if%>
        </div>
    </div>
</div>    