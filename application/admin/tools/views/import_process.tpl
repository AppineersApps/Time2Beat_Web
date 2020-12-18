<form name="frmimportcsvprocess" id="frmimportcsvprocess" action="" method="post"  enctype="multipart/form-data">
    <div class="main-content-block" id="main_content_block">
        <input type="hidden" name="import_module_name" id="import_module_name" value="<%$module_name%>" />
        <input type="hidden" name="import_file_name" id="import_file_name" value="<%$file_name%>" />
        <input type="hidden" name="import_media_name" id="import_media_name" value="<%$media_name%>" />
        <input type="hidden" name="import_first_row" id="import_first_row" value="<%$first_row%>" />
        <input type="hidden" name="import_columns_separator" id="import_columns_separator" value="<%$columns_separator%>" />
        <input type="hidden" name="import_text_delimiter" id="import_text_delimiter" value="<%$text_delimiter%>" />
        <input type="hidden" name="import_data_action" id="import_data_action" value="<%$import_action%>" />
        <input type="hidden" name="duplicate_data_action" id="duplicate_data_action" value="<%$duplicate_action%>" />
        <input type="hidden" name="import_error_action" id="import_error_action" value="<%$error_action%>" />
        <input type="hidden" name="skip_lookup_action" id="skip_lookup_action" value="<%$lookup_action%>" />
        <input type="hidden" name="skip_validation_action" id="skip_validation_action" value="<%$validation_action%>" />
        <input type="hidden" name="skip_top_rows" id="skip_top_rows" value="<%$skip_top_rows%>" />
        <input type="hidden" name="track_inserted" id="track_inserted" value="<%$track_inserted%>" />
        <input type="hidden" name="track_updated" id="track_updated" value="<%$track_updated%>" />
        <input type="hidden" name="track_failed" id="track_failed" value="<%$track_failed%>" />
        <input type="hidden" name="track_duplicate" id="track_duplicate" value="<%$track_duplicate%>" />
        <input type="hidden" name="track_lookup" id="track_lookup" value="<%$track_lookup%>" />
        <input type="hidden" name="track_valid" id="track_valid" value="<%$track_valid%>" />
        <input type="hidden" name="media_count" id="media_count" value="<%$media_count%>" />
        <input type="hidden" name="media_event" id="media_event" value="<%$smarty.now%><%1000|@rand:9999%>" />
        <textarea name="map_column" id="map_column" style="display:none;"><%$map_column_arr%></textarea>
        <textarea name="skip_column" id="skip_column" style="display:none;"><%$skip_column_arr%></textarea>
        <input type="hidden" name="import_type" id="import_type" value="commit" />
        <div style="width:49%" class="frm-block-layout pad-calc-container">
            <div class="box gradient process-csv-data <%$rl_theme_arr['frm_gener_content_row']%> <%$rl_theme_arr['frm_gener_border_view']%>">
                <div class="title <%$rl_theme_arr['frm_gener_titles_bar']%>"><h4>Sucess Info</h4></div>
                <div class="content <%$rl_theme_arr['frm_gener_label_align']%>">
                    <div class="form-row row-fluid">
                        <label class="form-label span3">Module</label> 
                        <div class="form-right-div frm-elements-div">
                            <span class="import-stats-txt"><%$module_title%></span>
                        </div>
                    </div>
                    <div class="form-row row-fluid">
                        <label class="form-label span3">Table</label> 
                        <div class="form-right-div frm-elements-div">
                            <span class="import-stats-txt"><%$table_name%></span>
                        </div>
                    </div>
                    <div class="form-row row-fluid">
                        <label class="form-label span3">Successful Records</label> 
                        <div class="form-right-div frm-elements-div">
                            <span class="import-stats-txt success"><%$import_success%></span>
                            <%if $import_success gt 0%>
                                <a href="javascript://" hijacked="yes"  title="Info" id="import_info_success" class="import-info"><span class="icon11 icomoon-icon-help"></span></a>
                            <%/if%>
                        </div>
                    </div>
                    <div class="form-row row-fluid">
                        <label class="form-label span3">Total Records</label> 
                        <div class="form-right-div frm-elements-div">
                            <span class="import-stats-txt total"><%$total_count%></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="width:49%" class="frm-block-layout pad-calc-container">
            <div class="box gradient process-csv-data <%$rl_theme_arr['frm_gener_content_row']%> <%$rl_theme_arr['frm_gener_border_view']%>">
                <div class="title <%$rl_theme_arr['frm_gener_titles_bar']%>"><h4>Misc Info</h4></div>
                <div class="content <%$rl_theme_arr['frm_gener_label_align']%>">
                    <div class="form-row row-fluid">
                        <label class="form-label span3">Failed Records</label> 
                        <div class="form-right-div frm-elements-div">
                            <span class="import-stats-txt failed"><%$import_failed%></span>
                            <%if $import_failed gt 0%>
                                <a href="javascript://" hijacked="yes"  title="Info" id="import_info_failed" class="import-info"><span class="icon11 icomoon-icon-help"></span></a>
                            <%/if%>
                        </div>
                    </div>
                    <div class="form-row row-fluid">
                        <label class="form-label span3">Duplicate Found</label> 
                        <div class="form-right-div frm-elements-div">
                            <span class="import-stats-txt duplicate"><%$import_duplicate%></span>
                            <%if $import_duplicate gt 0%>
                                <a href="javascript://" hijacked="yes"  title="Info" id="import_info_duplicate" class="import-info"><span class="icon11 icomoon-icon-help"></span></a>
                            <%/if%>
                        </div>
                    </div>
                    <div class="form-row row-fluid">
                        <label class="form-label span3">Skipped Records</label> 
                        <div class="form-right-div frm-elements-div">
                            <span class="import-stats-txt skipped"><%$import_skipped%></span>
                            <%if $import_skipped gt 0%>
                                <a href="javascript://" hijacked="yes"  title="Info" id="import_info_skipped" class="import-info"><span class="icon11 icomoon-icon-help"></span></a>
                            <%/if%>
                        </div>
                    </div>
                    <div class="form-row row-fluid">
                        <label class="form-label span3">Flushed Records</label> 
                        <div class="form-right-div frm-elements-div">
                            <span class="import-stats-txt flushed"><%$flush_rows%></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="clear"></div>
        <div class="frm-bot-btn <%$rl_theme_arr['frm_gener_action_bar']%> <%$rl_theme_arr['frm_gener_action_btn']%>">
            <div class="action-btn-align">
                <input type="submit" value="Save" name="ctrladd" class='btn btn-info' onclick="return Project.modules.importcsv.getValidateProcessCSV('save')"/>&nbsp;&nbsp;
                <input type="submit" value="Save & View" name="ctrlview" class='btn btn-info' onclick="return Project.modules.importcsv.getValidateProcessCSV('view')"/>&nbsp;&nbsp;
                <input type="button" value="Discard" name="ctrldiscard" class='btn' onclick="loadCSVImportPage()">
            </div>
        </div>
    </div>
    <div class="clear"></div>
</form>