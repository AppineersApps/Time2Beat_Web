<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <h3>
            <div class="screen-title">
                <%$this->lang->line('GENERIC_VIEW')%> :: <%$this->lang->line('DB_CHANGE_LOG_DB_CHANGE_LOG')%>
            </div>
        </h3>
        <div class="header-right-btns"></div>
    </div>
</div>
<div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
    <div id="scrollable_content" class="scrollable-content top-block-spacing">
        <%if $db_log|is_array && $db_log|count gt 0%>
        <div class="box">
            <div class="title">
                <h4>
                    <span class="icon12 icomoon-icon-equalizer-2"></span>
                    <span>Table :: <%$db_log['vTableName']%></span>
                </h4>
            </div>
            <div class="content">
                <%if $db_log['eOperation'] eq 'Added' || $db_log['eOperation'] eq 'Deleted'%>
                <table  class="responsive table table-bordered">
                    <thead>    
                        <tr>
                            <th width='5%'>#</th>
                            <th width='25%'>Field Name</th>
                            <th width='30%'>Value</th>

                        </tr>
                    </thead>
                    <tbody>
                        <%foreach from=$db_log['tFieldData'] key=k item=v%>
                        <tr>
                            <td></td>
                            <td><%$k%></td>
                            <td><%$v%></td>
                        </tr>
                        <%/foreach%>
                    </tbody>
                </table>    
                <%elseif $db_log['eOperation'] eq 'Modified'%>
                <table  class="responsive table table-bordered">
                    <thead>    
                        <tr>
                            <th width='5%'>#</th>
                            <th width='25%'>Field Name</th>
                            <th width='30%'>Old Value</th>
                            <th width='30%'>New Value</th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody> 
                        <%foreach from=$db_log['tFieldData'] key=k item=v%>
                        <tr>
                            <td></td>
                            <td><%$k%></td>
                            <td><%$v['oldValue']%></td>
                            <td><%$v['newValue']%></td>
                        </tr>
                        <%/foreach%>

                    </tbody>
                </table>   
                <%else%>
                No Operation Found.
                <%/if%>
            </div>
        </div>
        <%else%>
        <div align="center" class="text-error">
            No DB log added yet.
        </div>
        <%/if%>
    </div>
</div>
