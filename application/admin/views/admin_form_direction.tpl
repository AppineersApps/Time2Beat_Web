<%if $controls_allow eq false || $rm_ctrl_directions eq true%>
    <input value="Stay" id="ctrl_flow_stay" name="ctrl_flow" type="hidden" />
<%else%>
    <div class='action-dir-align'>
        <%if $prev_link_allow eq true%>
            <input value="Prev" id="ctrl_flow_prev" name="ctrl_flow" class="regular-radio" type="radio" <%if $ctrl_flow eq 'Prev' %> checked=true <%/if%> />
            <label for="ctrl_flow_prev">&nbsp;</label><label for="ctrl_flow_prev" class="inline-elem-margin"><%$this->lang->line('GENERIC_PREV_SHORT')%></label>&nbsp;&nbsp;
        <%/if%>
        <%if $next_link_allow eq true || $mode eq 'Add'%>
            <input value="Next" id="ctrl_flow_next" name="ctrl_flow" class="regular-radio" type="radio" <%if $ctrl_flow eq 'Next' %> checked=true <%/if%> />
            <label for="ctrl_flow_next">&nbsp;</label><label for="ctrl_flow_next" class="inline-elem-margin"><%$this->lang->line('GENERIC_NEXT_SHORT')%></label>&nbsp;&nbsp;
        <%/if%>
        <input value="List" id="ctrl_flow_list" name="ctrl_flow" class="regular-radio" type="radio" <%if $ctrl_flow eq 'List' %> checked=true <%/if%> />
        <label for="ctrl_flow_list">&nbsp;</label><label for="ctrl_flow_list" class="inline-elem-margin"><%$this->lang->line('GENERIC_LIST_SHORT')%></label>&nbsp;&nbsp;
        <input value="Stay" id="ctrl_flow_stay" name="ctrl_flow" class="regular-radio" type="radio" <%if $ctrl_flow eq '' || $ctrl_flow eq 'Stay' %> checked=true <%/if%> />
        <label for="ctrl_flow_stay">&nbsp;</label><label for="ctrl_flow_stay" class="inline-elem-margin"><%$this->lang->line('GENERIC_STAY_SHORT')%></label>
    </div>
<%/if%>

