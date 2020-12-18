<div class="import-history-block">
    <div class="box">
        <div class="title">
            <h4>
                <span class="icon12 icomoon-icon-equalizer-2"></span>
                <span>Import History</span>
            </h4>
            <a style="display:none;" class="minimize" href="javascript://"><%$this->lang->line('GENERIC_MINIMIZE')%></a>
        </div>
        <div class="content noPad">
            <table class="table table-bordered">
                <thead> 
                    <tr>
                        <th width='2%'>&nbsp;</th>
                        <th width='36%'>Module</th>
                        <th width='32%'>Table</th>
                        <th width='20%'>Date</th>
                        <th width='10%'>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <%section name=i loop=$data%>
                    <%assign var="itr" value=$smarty.section.i.iteration%>
                    <tr>
                        <td><span title="Expand" attr-key="<%$itr%>" class="history-toggle icon16 <%if $itr gt 2%>entypo-icon-plus-2<%else%>entypo-icon-minus-2<%/if%>"></span></td>
                        <td><div><%$data[i]['module']%></div></td>
                        <td><div><%$data[i]['table']%></div></td>
                        <td><div><%$this->general->dateSystemFormat($data[i]['date'])%></div></td>
                        <td>
                            <%assign var="import_file" value=$import_files_url|@cat:$data[i]['file']%>
                            <%if $import_file|@is_file%>
                            <div align="center"><span class='icon14 brocco-icon-blocked'></span></div>
                            <%else%>
                            <div align="center"><a href='<%$import_file%>' title="Download" class="tipR"><span class='icon14 cut-icon-download'></span></a></div>
                            <%/if%>
                        </td>
                    </tr>
                    <tr style="<%if $itr gt 2%>display:none<%/if%>" id="exp_<%$itr%>">
                        <td>&nbsp;</td>
                        <td colspan="4">
                            <div class="additional-info records-info">
                                <div class="history-additional">
                                    <div class="history-left">Successful Records</div>
                                    <div class="history-right success"><%$data[i]['success']%></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="history-additional">
                                    <div class="history-left">Failed Records</div>
                                    <div class="history-right failed"><%$data[i]['failed']%></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="history-additional">
                                    <div class="history-left">Duplicate Found</div>
                                    <div class="history-right duplicate"><%$data[i]['duplicate']%></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="history-additional">
                                    <div class="history-left">Skipped Records</div>
                                    <div class="history-right skipped"><%$data[i]['skipped']%></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="history-additional">
                                    <div class="history-left">Flushed Records</div>
                                    <div class="history-right flushed"><%$data[i]['flushed']%></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="history-additional">
                                    <div class="history-left">Total Records</div>
                                    <div class="history-right"><%$data[i]['total']%></div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div class="additional-info media-info">
                                <%assign var="log_arr" value=$this->csv_import->getLogActivity($data[i])%>
                                <%if $log_arr|@is_array && $log_arr|@count gt 0%>
                                <div class="history-additional">
                                    <div class="history-left">Media Uploads</div>
                                    <div class="history-right"><%$log_arr['percent']%>%&nbsp;<span class="<%$log_arr['status']%>"></span></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="history-additional">
                                    <div class="history-left left-space">Total</div>
                                    <div class="history-right"><%$log_arr['total']%></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="history-additional">
                                    <div class="history-left left-space">Success</div>
                                    <div class="history-right success"><%$log_arr['done']%></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="history-additional">
                                    <div class="history-left left-space">Failed</div>
                                    <div class="history-right failed"><%$log_arr['fail']%></div>
                                    <div class="clear"></div>
                                </div>
                                <%/if%>
                            </div>
                            <div class="clear"></div>
                        </td>
                    </tr>
                    <%sectionelse%>
                    <tr>
                        <td colspan="5"><div class="errormsg" align="center">No history found.</div></td>
                    </tr>
                    <%/section%>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('.history-toggle').click(function () {
            var exp_key = $(this).attr("attr-key");
            if ($("#exp_" + exp_key).is(":hidden")) {
                $("#exp_" + exp_key).show();
                $(this).addClass("entypo-icon-minus-2").removeClass("entypo-icon-plus-2").attr("title", "Collapse");
            } else {
                $("#exp_" + exp_key).hide();
                $(this).addClass("entypo-icon-plus-2").removeClass("entypo-icon-minus-2").attr("title", "Expand");
            }
        });
    });
</script>