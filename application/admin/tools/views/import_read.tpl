<form name="frmimportcsvread" id="frmimportcsvread" action="" method="post"  enctype="multipart/form-data">
    <div class="main-content-block" id="main_content_block">
        <input type="hidden" name="import_module_name" id="import_module_name" value="<%$module_name%>" />
        <input type="hidden" name="import_file_name" id="import_file_name" value="<%$file_name%>" />
        <input type="hidden" name="import_media_name" id="import_media_name" value="<%$media_name%>" />
        <input type="hidden" name="import_sheet_index" id="import_sheet_index" value="<%$sheet_index%>" />
        <input type="hidden" name="import_first_row" id="import_first_row" value="<%$first_row%>" />
        <input type="hidden" name="import_columns_separator" id="import_columns_separator" value="<%$columns_separator%>" />
        <input type="hidden" name="import_text_delimiter" id="import_text_delimiter" value="<%$text_delimiter%>" />
        <input type="hidden" name="import_type" id="import_type" value="preview" />
        <div class="import-data-config">
            <div style="width:32%;" class="frm-block-layout frm-resize-block pad-calc-container">
                <div class="box gradient resize-box <%$rl_theme_arr['frm_thblk_content_row']%> <%$rl_theme_arr['frm_thblk_border_view']%>">
                    <div class="title <%$rl_theme_arr['frm_thblk_titles_bar']%>"><h4>Table Stats</h4></div>
                    <div class="content resize-content <%$rl_theme_arr['frm_thblk_label_align']%>">  
                        <div class="form-row row-fluid">
                            <label class="form-label span3">Table Name</label> 
                            <div class="form-right-div frm-elements-div"><%$table_name%></div>
                        </div>
                        <div class="form-row row-fluid">
                            <label class="form-label span3">Existing Records</label> 
                            <div class="form-right-div frm-elements-div"><%$row_count%></div>
                        </div>
                        <div class="form-row row-fluid">
                            <label class="form-label span3">Unique On</label> 
                            <div class="form-right-div frm-elements-div"><%$unique_str%></div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="width:36%;" class="frm-block-layout frm-resize-block pad-calc-container">
                <div class="box gradient resize-box <%$rl_theme_arr['frm_thblk_content_row']%> <%$rl_theme_arr['frm_thblk_border_view']%>">
                    <div class="title <%$rl_theme_arr['frm_thblk_titles_bar']%>"><h4>Action Config</h4></div>
                    <div class="content resize-content <%$rl_theme_arr['frm_thblk_label_align']%>">  
                        <div class="form-row row-fluid">
                            <label class="form-label span3">Import Action</label>
                            <div class="form-right-div frm-elements-div">
                                <%foreach name=i from=$import_action item=v key=k%>
                                    <input type="radio" value="<%$k%>" name="import_data_action" id="import_data_action_<%$k%>" title="<%$v%>" <%if $k eq 'Merge' %> checked=true <%/if%>  class='regular-radio' />
                                    <label for="import_data_action_<%$k%>" class="frm-horizon-row frm-column-layout">&nbsp;</label>
                                    <label for="import_data_action_<%$k%>" class="frm-horizon-row frm-column-layout"><%$v%></label>
                                    &nbsp;
                                <%/foreach%>
                                <a href="javascript://" class="icon11 tipR" title="'Replace' will remove all records and import to table.<br>'Merge' will append or update records to table."><span class="icomoon-icon-help"></span></a>
                            </div>
                        </div>
                        <div class="form-row row-fluid">
                            <label class="form-label span3">Duplicate Data</label>
                            <div class="form-right-div frm-elements-div">
                                <%foreach name=i from=$duplicate_action item=v key=k%>
                                    <input type="radio" value="<%$k%>" name="duplicate_data_action" id="duplicate_data_action_<%$k%>" title="<%$v%>" <%if $k eq 'Skip' %> checked=true <%/if%>  class='regular-radio' />
                                    <label for="duplicate_data_action_<%$k%>" class="frm-horizon-row frm-column-layout">&nbsp;</label>
                                    <label for="duplicate_data_action_<%$k%>" class="frm-horizon-row frm-column-layout"><%$v%></label>
                                    &nbsp;
                                <%/foreach%>
                                <a href="javascript://" class="icon11 tipR" title="'Skip' will skip that particular row if duplicate found.<br>'Update' will update that particular row."><span class="icomoon-icon-help"></span></a>
                            </div>
                        </div>
                        <div class="form-row row-fluid">
                            <label class="form-label span3">On Import Error</label>
                            <div class="form-right-div frm-elements-div">
                                <%foreach name=i from=$error_action item=v key=k%>
                                    <input type="radio" value="<%$k%>" name="import_error_action" id="import_error_action_<%$k%>" title="<%$v%>" <%if $k eq 'Empty' %> checked=true <%/if%>  class='regular-radio' />
                                    <label for="import_error_action_<%$k%>" class="frm-horizon-row frm-column-layout">&nbsp;</label>
                                    <label for="import_error_action_<%$k%>" class="frm-horizon-row frm-column-layout"><%$v%></label>
                                <%/foreach%>
                                <a href="javascript://" class="icon11 tipR" title="'Skip Row' will skip that particular row if any validation errors found.<br>'Make Empty' will set empty value to that cell if any validation errors found."><span class="icomoon-icon-help"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="width:28%;" class="frm-block-layout frm-resize-block pad-calc-container">
                <div class="box gradient resize-box <%$rl_theme_arr['frm_thblk_content_row']%> <%$rl_theme_arr['frm_thblk_border_view']%>">
                    <div class="title <%$rl_theme_arr['frm_thblk_titles_bar']%>"><h4>Misc Config</h4></div>
                    <div class="content resize-content <%$rl_theme_arr['frm_thblk_label_align']%>">  
                        <div class="form-row row-fluid">
                            <label class="form-label span3">Skip Lookup</label>
                            <div class="form-right-div frm-elements-div">
                                <%foreach name=i from=$lookup_action item=v key=k%>
                                    <input type="radio" value="<%$k%>" name="skip_lookup_action" id="skip_lookup_action_<%$k%>" title="<%$v%>" <%if $k eq 'Yes' %> checked=true <%/if%>  class='regular-radio' />
                                    <label for="skip_lookup_action_<%$k%>" class="frm-horizon-row frm-column-layout">&nbsp;</label>
                                    <label for="skip_lookup_action_<%$k%>" class="frm-horizon-row frm-column-layout"><%$v%></label>&nbsp;&nbsp;
                                <%/foreach%>
                                <a class="validation_info tipR" href="javascript://" aria-type="lookup" title="Click Here"><span class="icon11 icomoon-icon-help"></span></a>
                            </div>
                        </div>
                        <div class="form-row row-fluid">
                            <label class="form-label span3">Skip Validations</label>
                            <div class="form-right-div frm-elements-div">
                                <%foreach name=i from=$validation_action item=v key=k%>
                                    <input type="radio" value="<%$k%>" name="skip_validation_action" id="skip_validation_action_<%$k%>" title="<%$v%>" <%if $k eq 'No' %> checked=true <%/if%>  class='regular-radio' />
                                    <label for="skip_validation_action_<%$k%>" class="frm-horizon-row frm-column-layout">&nbsp;</label>
                                    <label for="skip_validation_action_<%$k%>" class="frm-horizon-row frm-column-layout"><%$v%></label>&nbsp;&nbsp;
                                <%/foreach%>
                                <a class="validation_info tipR" href="javascript://" aria-type="validation" title="Click Here"><span class="icon11 icomoon-icon-help"></span></a>
                            </div>

                        </div>
                        <div class="form-row row-fluid">
                            <label class="form-label span3">Skip Rows</label>
                            <div class="form-right-div frm-elements-div">
                                <input type="text" placeholder="" value="0" name="skip_top_rows" id="skip_top_rows" title="Skip Rows"  class='import-skip-box' />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div style="width:98%" class="frm-block-layout pad-calc-container">
            <div class="box gradient import-csv-data <%$rl_theme_arr['frm_gener_content_row']%> <%$rl_theme_arr['frm_gener_border_view']%>">
                <div class="title <%$rl_theme_arr['frm_gener_titles_bar']%>"><h4>Data Sheet</h4></div>
                <div class="content <%$rl_theme_arr['frm_gener_label_align']%>">
                    <%if $success eq 1 && $data|@is_array && $data|@count gt 0%>
                        <div class="form-row row-fluid">
                            <div class="box form-child-table import-data-recs">
                                <div class="title">
                                    <h4>
                                        <span class="icon12 icomoon-icon-equalizer-2"></span>
                                        <span>Data</span>
                                        <span class="errormsg import-error-msg" id="import_error_msg"></span>
                                        <span class="import-toprec-msg">Shows Top 5 Records</span>
                                    </h4>
                                    <a style="display:none;" class="minimize" href="javascript://"><%$this->lang->line('GENERIC_MINIMIZE')%></a>
                                </div>
                                <div class="content noPad">
                                    <div class="import-header-container" id="import_scroll_horizontal">
                                        <table class="table table-bordered">
                                            <thead> 
                                                <tr>
                                                    <%section name=i loop=$header%>
                                                        <%assign var="j" value=$smarty.section.i.index%>
                                                        <%assign var="opt_selected" value=$this->csv_import->autoDetectMapping($header[i], $mapping)%>
                                                        <th class="import-column-width">
                                                            <div>
                                                                <div class="import-column-info">
                                                                    <div class="import-header-cell"><%$header[i]%></div>
                                                                    <div class="import-skip-cell">
                                                                        <input type="checkbox" value="<%$j%>" name="skip_column[<%$j%>]" id="skip_column_<%$j%>" title="Skip Column" class='regular-checkbox' checked="true" value="Yes"/>
                                                                        <label for="skip_column_<%$j%>" class="frm-horizon-row frm-column-layout">&nbsp;</label>
                                                                    </div>
                                                                    <div class="clear"></div>
                                                                </div>
                                                                <div>
                                                                    <%$this->dropdown->display("map_column","map_column[]","  title='Map Column'  class='import-map-column chosen-select1' aria-parent-overflow='true' ", "", "", $opt_selected, "map_column_`$smarty.section.i.iteration`")%>
                                                                </div>
                                                            </div>
                                                        </th>
                                                    <%/section%>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="import-data-container" id="import_scroll_vertical">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <%section name=i loop=$data%>
                                                    <tr>
                                                        <%section name=j loop=$header%>
                                                            <td class="import-column-width">
                                                                <div class="import-data-cell"><%$data[i][j]%></div>
                                                            </td>
                                                        <%/section%>
                                                    </tr>
                                                <%/section%>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <%elseif $success  eq 0%>
                        <div class="errormsg" align="center"><%$message%></div>
                    <%else%>
                        <div class="errormsg" align="center">No records found.</div>
                    <%/if%>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="frm-bot-btn <%$rl_theme_arr['frm_gener_action_bar']%> <%$rl_theme_arr['frm_gener_action_btn']%>">
            <div class="action-btn-align">
                <input type="submit" value="Import" name="ctrladd" class='btn btn-info' onclick="return Project.modules.importcsv.getValidateImportCSV()"/>&nbsp;&nbsp;
                <input type="button" value="Back" class='btn' onclick="loadCSVImportPage()">
            </div>
        </div>
    </div>
    <div class="clear"></div>
</form>