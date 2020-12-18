<%if $type eq validation%>
    <div class="page-header">
        <h4>Validations</h4>
    </div>
    <%if $columns|@is_array && $columns|@count gt 0%>
        <div class="import-valid-info">
            <div class="tabbable tabs-left">
                <ul class="nav nav-tabs" style ="float:left;">
                    <%foreach from=$columns item=col key=h%>
                        <%if $col.rules|@is_array && $col.rules|@count gt 0%>
                            <%assign var=col_name value=$this->lang->line($col.name)%>
                            <li class=""><a href="#tab_<%$h%>" data-toggle="tab"><%if $col_name eq ''%><%$col.name%><%else%><%$col_name%><%/if%></a></li>
                        <%/if%>
                    <%/foreach%>
                </ul>
                <div class="tab-content" >
                    <%foreach from=$columns item=col key=h%>
                        <%if $col.rules|@is_array && $col.rules|@count gt 0%>
                            <%assign var=col_name value=$this->lang->line($col.name)%>
                            <div class="tab-pane" id="tab_<%$h%>">
                                <div class="box">
                                    <div class="title head-valid">
                                        <h4><span><%if $col_name eq ''%><%$col.name%><%else%><%$col_name%><%/if%></span></h4>
                                    </div>
                                    <div class="content noPad" style ="display:block;">
                                        <table class="table table-bordered" >
                                            <tbody>
                                                <%foreach from=$col.rules  key=rule item=value%>
                                                    <tr>
                                                        <td width="50%"><%$rule%></td>
                                                        <td width="50%">
                                                            <%if $value|@is_array && $value|count eq 2%>
                                                                [<%$value[0]%>, <%$value[1]%>]
                                                            <%elseif $value|@is_array && $value|count eq 1%>
                                                                <%$value[0]%>
                                                            <%elseif $value eq true%>
                                                                true
                                                            <%else%>
                                                                <%$value%>
                                                            <%/if%>
                                                        </td>   
                                                    </tr>
                                                <%/foreach%>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <%/if%>
                    <%/foreach%>
                </div>
            </div>
        </div>
    <%else%>
        <div class="errormsg" align="center">No data found</div>
    <%/if%>
<%elseif $type eq lookup%>
    <div class="page-header">
        <h4>Lookup List</h4>
    </div>
    <%if $columns|@is_array && $columns|@count gt 0%>
        <div class="import-valid-info">
            <div class="tabbable tabs-left">
                <ul class="nav nav-tabs" style ="float:left;">
                    <%foreach from=$columns item=col key=h%>
                        <%if $col.lookup|@is_array && $col.lookup|@count gt 0%>
                            <%assign var=col_name value=$this->lang->line($col.name)%>
                            <li class=""><a href="#tab_<%$h%>" data-toggle="tab"><%if $col_name eq ''%><%$col.name%><%else%><%$col_name%><%/if%></a></li>
                        <%/if%>
                    <%/foreach%>
                </ul>
                <div class="tab-content" >
                    <%foreach from=$columns item=col key=h%>
                        <%if $col.lookup|@is_array && $col.lookup|@count gt 0%>
                            <%assign var=col_name value=$this->lang->line($col.name)%>
                            <div class="tab-pane" id="tab_<%$h%>">
                                <div class="box">
                                    <div class="title head-valid">
                                        <h4><span><%if $col_name eq ''%><%$col.name%><%else%><%$col_name%><%/if%></span></h4>
                                    </div>
                                    <div class="content noPad" style ="display:block;">
                                        <table class="table table-bordered" >
                                            <tbody>
                                                <%if $col.lookup.type eq table%>
                                                    <tr>
                                                        <td width="50%">Lookup Table</td>
                                                        <td width="50%"><%$col.lookup.table[0]%></td>   
                                                    </tr>
                                                    <tr>
                                                        <td width="50%">Loopup Column</td>
                                                        <td width="50%"><%$col.lookup.table[1]%></td>   
                                                    </tr>
                                                <%elseif $col.lookup.type eq list%>
                                                    <tr>
                                                        <td width="50%">Lookup List</td>
                                                        <td width="50%">
                                                            <%foreach from=$col.lookup.list item=item%>
                                                                <div><%$item%></div>
                                                            <%/foreach%>
                                                        </td>   
                                                    </tr>
                                                <%/if%>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <%/if%>
                    <%/foreach%>
                </div>
            </div>
        </div>
    <%else%>
        <div class="errormsg" align="center">No data found</div>
    <%/if%>
<%/if%>
<script>
    $(function () {
        if ($(".import-valid-info .nav-tabs li").length) {
            var ul_height = $(".import-valid-info .nav-tabs").height();
            var tbl_height = $(".import-valid-info .tab-content").height();
            if (ul_height - 30 > tbl_height) {
                $(".import-valid-info .tab-content").height(ul_height - 30);
            }
            var li_first = $(".import-valid-info .nav-tabs").find("li").first()[0];
            $(li_first).find("a").trigger('click');
        }
    });
</script>