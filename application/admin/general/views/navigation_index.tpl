<div class="box" style="">
    <div class="title">
        <h4>
            <span><%$this->lang->line('GENERIC_NAVIGATION_LOG')%> &nbsp;

                <%if ($admin_user == "Yes" && is_array($user_data)  && count($user_data) > 0)%>
                    <select name="userCombo" id="userCombo" onchange='loadNavigationLogPrint()' class='navigation-user-flush-combo'>
                        <%foreach from=$user_data key=key item=val%>
                            <option value="<%$val['iAdminId']%>" <%if $user_id eq $val['iAdminId']%>selected=true<%/if%>><%$val['vName']%></option>
                        <%/foreach%>
                    </select>
                <%/if%>

                <select name="navigationCombo" id="navigationCombo" onchange="loadNavigationLogPrint()" class='navigation-combo'>
                    <%foreach from=$data_log_arr key=key item=val%>
                    <option value="<%$key%>" <%if $range eq $key%>selected=true<%/if%>><%$val%></option>
                    <%/foreach%>   
                </select>&nbsp;
                <select name="actionCombo" id="actionCombo" onchange="loadNavigationLogPrint()" class='navigation-action-combo'>
                    <option value="All" <%if $action eq 'All'%>selected=true<%/if%>>All</option>
                    <%foreach from=$data_action_arr key=key item=val%>
                    <option value="<%$key%>" <%if $action eq $key%>selected=true<%/if%>><%$val%></option>
                    <%/foreach%>   
                </select> &nbsp;
                <%$this->lang->line('GENERIC_FLUSH')%>&nbsp;
                <select name="flushCombo" id="flushCombo" class='navigation-user-flush-combo'>
                    <%foreach from=$data_flush_arr key=key item=val%> 
                        <option value="<%$key%>"><%$val%></option>
                    <%/foreach%>
                </select>
                <div class="btn btn-primary" onclick="loadFlushLogPrint()">
                    Go
                </div>
            </span> 
            <span class="nv-minimize-log">
                <span class="min" title="<%$this->lang->line('GENERIC_HIDE_NAVIGATION_LOG')%>"></span>
            </span>
        </h4>
    </div>
    <div class="content noPad" style="display: block;">    
    <%if (!is_array($db_navig_data) || count($db_navig_data) == 0)%>
        <div class="alert alert-error center" style="width:96%">
            No results found.
        </div>
    <%else%>
        <table class="responsive table table-bordered display">
            <thead>
                <tr>
                    <th style="display:table-cell">#</th>
                    <th width="25%"><%$this->lang->line('GENERIC_MENU')%></th>
                    <th width="42%"><%$this->lang->line('GENERIC_NAVIGATION')%></th>
                    <th width="15%"><%$this->lang->line('GENERIC_ACTION')%></th>
                    <th width="15%"><%$this->lang->line('GENERIC_TIMESTAMP')%></th>
                </tr>
            </thead>
            <tbody>
                <%foreach from=$db_navig_data key=key  item=val%>
                    <%if $key mod 2 == 0%>
                        <%assign var='class' value='odd'%>
                    <%else%>
                        <%assign var='class' value='even'%>
                    <%/if%>
                    <%assign var="row_no" value=$key + 1%>
                    <tr class="<%$class%>">
                        <td style="display:table-cell"><%$row_no%></td>
                        <td><%$val['vMainMenu']%></td>
                        <td>
                            <%if $val['eNavigAction'] eq "Deleted"%>
                                <div class='errormsg'><%$val['vSubMenu']%> &nbsp; >> &nbsp; <%$val['vRecordName']%></div>
                            <%else%>
                                <%if $val['eNavigType'] eq "Form"%>
                                    <a href='<%$admin_url%>#<%$val["vSupQString"]|strip%>' title="<%$val['vRecordName']%>" ><%$val['vSubMenu']%></a> &nbsp; >> &nbsp; 
                                    <a href='<%$admin_url%>#<%$val["vNavigQString"]|strip%>' title="<%$val['vSubMenu']%>" ><%$val['vRecordName']%></a>
                                <%else%>
                                    <a href='<%$admin_url%>#<%$val["vNavigQString"]|strip%>' title="<%$val['vSubMenu']%>" ><%$val['vSubMenu']%></a>
                                <%/if%>
                            <%/if%>
                        </td>
                        <td> 
                            <%if $val['eNavigAction'] eq "Deleted"%>
                                <div class='errormsg'><%$val['eNavigAction']%></div>
                            <%else%>
                               <%$val['eNavigAction']%>
                            <%/if%>
                        </td>
                        <td>
                            <%$this->general->navigationDateTime($val['dTimeStamp'])%>
                        </td>
                    </tr>
                <%/foreach%>   
            </tbody>
        </table>
     <%/if%>
    </div>
</div>

