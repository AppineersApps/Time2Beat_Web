<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <%assign var="dash_heading_label" value=$this->general->getDisplayLabel("Dashboard",$data['vPageName'],"label")%>
        <h3>
            <div class="screen-title">
                <%$this->lang->line($dash_heading_label)%> <%if $module_arr['rec_name'] neq ''%> :: <%$module_arr['rec_name']%> <%/if%>
            </div>
        </h3>
        <%if $backlink_allow eq true %>
        <div class="frm-back-to">
            <a href="<%$admin_url%>#<%$module_arr['mod_index_url']%><%$extra_hstr%>"class="backlisting-link" title="<%$this->general->parseLabelMessage('GENERIC_BACK_TO_MODULE_LISTING','#MODULE_HEADING#', $module_arr['module_heading_label'])%>">
                <span class="icon16 minia-icon-arrow-left"></span>
            </a>
        </div>
        <%/if%>
        <%if $top_detail_view["exists"] eq "1"%>
        <div class="frm-detail-view">
            <%if $top_detail_view["flag"] eq "1"%>
            <a href="javascript://" class="tipR active hide-top-detail-view" title="<%$this->lang->line('GENERIC_SHOW_VIEW')%>" id="hide_top_view"  onclick="return hideShowTopView(this);">
                <span><i id="top_show_view_content" class="minia-icon-list"></i></span>
            </a>
            <%else%>
            <a href="javascript://" class="tipR" title="<%$this->lang->line('GENERIC_HIDE_VIEW')%>" id="hide_top_view" onclick="return hideShowTopView(this);">
                <span><i id="top_show_view_content" class="minia-icon-list"></i></span>
            </a>
            <%/if%>
        </div>
        <%/if%>
    </div>
</div>