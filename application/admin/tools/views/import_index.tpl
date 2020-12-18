<%if $this->input->is_ajax_request()%>
<%$this->js->clean_js()%>
<%/if%>  
<%$this->js->add_js('admin/admin/js_import_data.js','admin/jsoneditor/jquery.jsoneditor.js')%>
<%$this->css->add_css("import/import.css", "jsoneditor/jsoneditor.css")%>
<div class='custom-import-container'>
    <%include file="import_index_strip.tpl"%>
    <div id="ajax_content_div" class="ajax-content-div top-frm-spacing">
        <div id="ajax_qLoverlay"></div>
        <div id="ajax_qLbar"></div>
        <div id="scrollable_content" class="scrollable-content top-block-spacing">
            <div id="importcsv" class="frm-elem-block frm-stand-view">
                <div style="display:none;" id="upload_sheets_html"></div>
                <form name="frmimportcsvadd" id="frmimportcsvadd" action="" method="post"  enctype="multipart/form-data">
                    <div class="main-content-block" id="main_content_block">
                        <div style="width:98%" class="frm-block-layout pad-calc-container">
                            <div class="box gradient upload-csv-data <%$rl_theme_arr['frm_gener_content_row']%> <%$rl_theme_arr['frm_gener_border_view']%>">
                                <div class="title <%$rl_theme_arr['frm_gener_titles_bar']%>"><h4><%$this->lang->line('GENERIC_IMPORT_DATA')%></h4></div>
                                <div class="content <%$rl_theme_arr['frm_gener_label_align']%>">
                                    <div class="form-row row-fluid">
                                        <label class="form-label span3">
                                            Select Module <em>*</em> 
                                        </label> 
                                        <div class="form-right-div">
                                            <%$this->dropdown->display("upload_module","upload_module","  title='Please Select Module'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='Please Select Module'  ", "|||", "", '', "upload_module")%>
                                        </div>
                                        <div class="error-msg-form "><label class='error' id='upload_moduleErr'></label></div>
                                    </div>
                                    <div class="form-row row-fluid">
                                        <label class="form-label span3">
                                            Select Data Source <em>*</em> 
                                        </label> 
                                        <div class="form-right-div frm-elements-div">
                                            <%foreach name=i from=$upload_location_arr item=v key=k%>
                                            <input type="radio" value="<%$k%>" name="upload_location" id="upload_location_<%$k%>" title="<%$v%>" <%if $k eq 'local' %> checked=true <%/if%>  class='regular-radio' />
                                                   <label for="upload_location_<%$k%>" class="frm-horizon-row frm-column-layout">&nbsp;</label>
                                            <label for="upload_location_<%$k%>" class="frm-horizon-row frm-column-layout"><%$v%></label>&nbsp;&nbsp;
                                            <%/foreach%>
                                        </div>
                                        <div class="error-msg-form "><label class='error' id='upload_locationErr'></label></div>
                                    </div>
                                    <div class="form-row row-fluid local-drive-block">
                                        <label class="form-label span3">
                                            Upload Data File<em>*</em> 
                                        </label> 
                                        <div class="form-right-div">
                                            <div>
                                                <div class="btn-uploadify frm-size-medium">
                                                    <input type="hidden" value="0" name="upload_index" id="upload_index" />
                                                    <input type='hidden' value='' name='upload_csv' id='upload_csv' />
                                                    <input type='hidden' value='' name='temp_upload_csv' id='temp_upload_csv' />
                                                    <div id="upload_drop_zone_upload_csv" class="upload-drop-zone"></div>
                                                    <div class="uploader upload-src-zone">
                                                        <input type='file' name='uploadify_upload_csv' id='uploadify_upload_csv' title="Upload Data File" />
                                                        <span class="filename" id="preview_upload_csv">Upload Data File</span>
                                                        <span class="action">Choose File</span>
                                                    </div>
                                                </div>
                                                <span class="input-comment">
                                                    <a href="javascript://"  class="tipR" title="Valid Extensions : <%$import_settings.file_extensions|replace:'|':', '%>.<br>Valid Size : Less Than (<) <%$import_settings.file_maxsize_txt%>.<br>Max. Columns : <%$import_settings.file_maxcols%>.<br>Max. Rows : <%$import_settings.file_maxrows%>">
                                                        <span class="icomoon-icon-help"></span>
                                                    </a>
                                                </span>
                                                <div class="clear upload-progress" id="progress_upload_csv">
                                                    <div class="upload-progress-bar progress progress-striped active">
                                                        <div class="bar" id="practive_upload_csv"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="error-msg-form" ><label class="error" id="upload_csvErr"></label></div>
                                    </div>
                                    <div class="form-row row-fluid hide-import-options google-drive-block">
                                        <label class="form-label span3">
                                            Select Data Sheet<em>*</em> 
                                        </label> 
                                        <div class="form-right-div input-append text-append-prepend">
                                            <input type="text" placeholder="" value="" name="upload_sheet" id="upload_sheet" readonly=true title="Select Data Sheet"  class='frm-size-medium ctrl-append-prepend google-sheet-container ignore-hidden' />
                                            <span class='add-on text-addon btn pick-google-sheet' id="pick_google_sheet">Browse!</span>
                                        </div>
                                        <div class="error-msg-form "><label class='error' id='upload_sheetErr'></label></div>
                                    </div>
                                    <div class="form-row row-fluid hide-import-options web-url-block">
                                        <label class="form-label span3">
                                            Specify Data URL <em>*</em> 
                                        </label> 
                                        <div class="form-right-div input-append text-append-prepend">
                                            <input type="hidden" value="" name="web_data_url_keypath" id="web_data_url_keypath" />
                                            <input type="text" placeholder="" value="" name="web_data_url" id="web_data_url" title="Specify Data URL"  class='frm-size-medium ctrl-append-prepend  ignore-hidden' />
                                            <span class="add-on text-addon btn" id="web_data_url_browse">Browse!</span>
                                        </div>
                                        <div class="error-msg-form "><label class='error' id='web_data_urlErr'></label></div>
                                    </div>
                                    <div class="form-row row-fluid hide-import-options web-url-block">
                                        <label class="form-label span3">
                                            Response Format <em>*</em> 
                                        </label> 
                                        <div class="form-right-div frm-elements-div">
                                            <%foreach name=i from=$respose_format_arr item=v key=k%>
                                            <input type="radio" value="<%$k%>" name="response_format" id="response_format_<%$k%>" title="<%$v%>" <%if $k eq 'csv' %> checked=true <%/if%>  class='regular-radio' />
                                                   <label for="response_format_<%$k%>" class="frm-horizon-row frm-column-layout">&nbsp;</label>
                                            <label for="response_format_<%$k%>" class="frm-horizon-row frm-column-layout"><%$v%></label>&nbsp;&nbsp;
                                            <%/foreach%>
                                        </div>
                                        <div class="error-msg-form "><label class='error' id='response_formatErr'></label></div>
                                    </div>
                                    <div id="web_url_response_block" class="web-url-response-block">
                                        <div class="title" style="height:40px; line-height:40px;">
                                            <h4>&nbsp;
                                                <span>Web URL</span>
                                            </h4>
                                        </div>
                                        <div class="json-editor" id="web_url_pick_item"></div>
                                        <div class="pick-json-xml-ele">
                                            <input type="button" value="Select" class="btn btn-info" id="btn_pick_json_xml1">
                                        </div>
                                    </div>
                                    <div id="media_files_upload" class="form-row row-fluid" style="display:none;">
                                        <label class="form-label span3">
                                            Upload Media Files Zip
                                        </label> 
                                        <div class="form-right-div">
                                            <div>
                                                <div class="btn-uploadify frm-size-medium">
                                                    <input type='hidden' value='' name='upload_media' id='upload_media' />
                                                    <input type='hidden' value='' name='temp_upload_media' id='temp_upload_media' />
                                                    <div id="upload_drop_zone_upload_media" class="upload-drop-zone"></div>
                                                    <div class="uploader upload-src-zone">
                                                        <input type='file' name='uploadify_upload_media' id='uploadify_upload_media' title="Upload Media File" />
                                                        <span class="filename" id="preview_upload_media">Upload Media File</span>
                                                        <span class="action">Choose File</span>
                                                    </div>
                                                </div>
                                                <span class="input-comment">
                                                    <a href="javascript://"  class="tipR" title="Valid Extensions : <%$import_settings.media_extensions|replace:'|':', '%>.<br>Valid Size : Less Than (<) <%$import_settings.media_maxsize_txt%>.">
                                                        <span class="icomoon-icon-help"></span>
                                                    </a>
                                                    <a href="<%$admin_url%><%$mod_enc_url['import_media_sample']%>?upload_module=sample" hijacked="yes" id="sample_zip_file" class="tipR" title="Sample Media Zip">
                                                        <span class="icon16 icomoon-icon-download"></span>
                                                    </a>
                                                </span>

                                                <div class="clear upload-progress" id="progress_upload_media">
                                                    <div class="upload-progress-bar progress progress-striped active">
                                                        <div class="bar" id="practive_upload_media"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="error-msg-form" ><label class="error" id="upload_mediaErr"></label></div>
                                    </div>
                                    <div class="form-row row-fluid csv-more-settings">
                                        <label class="form-label span3">
                                            <span class="icon14 cut-icon-plus-2 action-more-settings" id="action_settings_span"></span><a href="javascript://" class="action-more-settings" id="action_settings_anchor" title="More Settings">More Settings</a>
                                        </label> 
                                        <div class="form-right-div frm-elements-div">
                                            <a style="display:none;" href="javascript://" class="upload-sheets-event" id="upload_sheets_event" title="Choose Sheet">Choose Sheet</a>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid toggle-more-settings" id="first_row_setting">
                                        <label class="form-label span3">
                                            Consider First Row Contains Column Names
                                        </label> 
                                        <div class="form-right-div">
                                            <%$this->dropdown->display("import_first_row","import_first_row","  title='Consider First Row Contains Column Names'  class='frm-size-medium' ", "", "", '', "import_first_row")%>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid toggle-more-settings hide-settings"  id="columns_separate_setting">
                                        <label class="form-label span3">
                                            Columns Separated By
                                        </label> 
                                        <div class="form-right-div">
                                            <%$this->dropdown->display("import_columns_separator","import_columns_separator","  title='Columns Separated By'  class='frm-size-medium' ", "", "", '', "import_columns_separator")%>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid toggle-more-settings hide-settings" id="text_delimiter_setting">
                                        <label class="form-label span3">
                                            Text Delimiter
                                        </label> 
                                        <div class="form-right-div">
                                            <%$this->dropdown->display("import_text_delimiter","import_text_delimiter","  title='Text Delimiter'  class='frm-size-medium' ", "", "", '', "import_text_delimiter")%>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid toggle-more-settings">
                                        <label class="form-label span3">
                                            Decimal Separator
                                        </label> 
                                        <div class="form-right-div">
                                            <%$this->dropdown->display("import_decimal_separator","import_decimal_separator","  title='Decimal Separator'  class='frm-size-medium' ", "", "", '', "import_decimal_separator")%>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid toggle-more-settings">
                                        <label class="form-label span3">
                                            Thousand Separator
                                        </label> 
                                        <div class="form-right-div">
                                            <%$this->dropdown->display("import_thousand_separator","import_thousand_separator","  title='Thousand Separator'  class='frm-size-medium' ", "", "", '', "import_thousand_separator")%>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="frm-bot-btn <%$rl_theme_arr['frm_gener_action_bar']%> <%$rl_theme_arr['frm_gener_action_btn']%>">
                            <div class="action-btn-align">
                                <input type="button" value="Show History" class='btn import-history' onclick="loadImportHistoryPage()">
                                <%if $history_count gt 0%>
                                <span class="notification history-count"><%$history_count%></span>
                                <%/if%>
                                <input value="Next" name="ctrladd" type="submit" class='btn btn-info' onclick="return Project.modules.importcsv.getValidateAddCSV()"/>&nbsp;&nbsp;
                                <input type="button" value="Discard" class='btn' onclick="loadAdminSiteMapPage()">
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </form>
            </div>
        </div>
    </div>
</div>
<%javascript%>
var $import_valid_ext = '<%$import_settings["file_extensions"]%>';
var $import_valid_size = '<%$import_settings["file_maxsize_org"]%>';
var $import_media_ext = '<%$import_settings["media_extensions"]%>';
var $import_media_size = '<%$import_settings["media_maxsize_org"]%>';
var $import_upload_url = '<%$admin_url%><%$mod_enc_url["import_upload"]%>';
var $import_media_url = '<%$admin_url%><%$mod_enc_url["import_media"]%>';
var $import_read_url = '<%$admin_url%><%$mod_enc_url["import_read"]%>';
var $import_process_url = '<%$admin_url%><%$mod_enc_url["import_process"]%>';
var $import_info_url = '<%$admin_url%><%$mod_enc_url["import_info"]%>?';
var $import_valid_url = '<%$admin_url%><%$mod_enc_url["import_valid"]%>?';
var $import_histroy_url = '<%$admin_url%><%$mod_enc_url["import_history"]%>?';
var $import_gdrive_manage_url = '<%$admin_url%><%$mod_enc_url["import_gdrive_manager"]%>?';
var $import_gdrive_config_url = '<%$admin_url%><%$mod_enc_url["import_gdrive_config"]%>';
var $import_gdrive_auth_url = '<%$admin_url%><%$mod_enc_url["import_gdrive_auth"]%>';
var $import_gdrive_data_url = '<%$admin_url%><%$mod_enc_url["import_get_gdrive_data"]%>';
var $import_gdrive_save_url = '<%$admin_url%><%$mod_enc_url["import_save_gdrive_data"]%>';
var $import_web_data_url = '<%$admin_url%><%$mod_enc_url["import_get_weburl_data"]%>';
var $import_dropbox_data_url = '<%$admin_url%><%$mod_enc_url["import_get_dropbox_data"]%>';
var $import_dropbox_auth_url = '<%$admin_url%><%$mod_enc_url["import_dropbox_auth"]%>';
var $import_dropbox_save_url = '<%$admin_url%><%$mod_enc_url["import_save_dropbox_data"]%>';
var $import_media_event_url = '<%$admin_url%><%$mod_enc_url["import_media_event"]%>';
var $import_media_sample_url = '<%$admin_url%><%$mod_enc_url["import_media_sample"]%>';
var $import_media_modules = $.parseJSON('<%$media_modules%>');
<%/javascript%>
<%if $this->input->is_ajax_request()%>
<%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
<%$this->css->css_src()%>
<%/if%> 
<%javascript%>
Project.modules.importcsv.init();
<%/javascript%>