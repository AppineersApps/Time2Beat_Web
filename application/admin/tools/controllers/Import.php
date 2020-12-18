<?php
defined('BASEPATH') || exit('No direct script access allowed');

ini_set('memory_limit', "512M");
ini_set('max_execution_time', 0);

/**
 * Description of Import Controller
 *
 * @category admin
 *            
 * @package tools
 * 
 * @subpackage controllers
 * 
 * @module Import
 * 
 * @class Import.php
 * 
 * @path application\admin\tools\controllers\Import.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Import extends Cit_Controller
{

    private $_loopup_entries = array();
    private $_track_failed = array();
    private $_track_success = array();
    private $_chunk_size = 100;
    private $_info_limit = 100;
    private $_show_limit = 6;
    private $_read_size = 25;
    private $_cols_limit = 200;
    private $_rows_limit = 2500000;
    private $_file_size_org = 204800;
    private $_file_size_txt = "200 MB";
    private $_file_extension = "csv|xls|xlsx|ods";
    private $_media_size_org = 204800;
    private $_media_size_txt = "200 MB";
    private $_media_extension = "zip|tar|bz2|gz|cbz|jar";
    private $_restricted_exts = array(
        "php", "php3", "php4", "php5", "php7", "phtml", "pl", "py", "jsp", "asp", "aspx", "htm",
        "html", "shtml", "sh", "cgi", "cgi-script", "exe", "so", "dll", "bin", "ai", "eps", "ps"
    );
    private $_history_limit = 25;
    private $_gdrive_redirect_uri;
    private $_dropbox_redirect_uri;

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->mod_url_cod = array(
            "import_index",
            "import_upload",
            "import_media",
            "import_read",
            "import_process",
            "import_valid",
            "import_info",
            "import_history",
            "import_media_event",
            "import_media_sample",
            "import_gdrive_manager",
            "import_gdrive_config",
            "import_gdrive_auth",
            "import_get_gdrive_data",
            "import_save_gdrive_data",
            "import_get_weburl_data",
            "import_dropbox_auth",
            "import_get_dropbox_data",
            "import_save_dropbox_data"
        );
        $this->import_settings = array(
            "file_extensions" => $this->_file_extension,
            "file_maxsize_txt" => $this->_file_size_txt,
            "file_maxsize_org" => $this->_file_size_org,
            "media_extensions" => $this->_media_extension,
            "media_maxsize_txt" => $this->_media_size_txt,
            "media_maxsize_org" => $this->_media_size_org,
            "file_maxcols" => $this->_cols_limit,
            "file_maxrows" => $this->_rows_limit,
        );

        $this->mod_enc_url = $this->general->getCustomEncryptURL($this->mod_url_cod, true);
        $this->load->model('import_model');
        $this->load->library('dropdown');
        $this->load->library('filter');
        $this->load->library('csv_import');
        $this->_gdrive_redirect_uri = $this->config->item('admin_url') . $this->mod_enc_url['import_gdrive_auth'];
        $this->_dropbox_redirect_uri = $this->config->item('admin_url') . $this->mod_enc_url['import_dropbox_auth'];
    }

    /**
     * index method is used to intialize import page.
     */
    public function index()
    {
        try {
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                $add_access = $this->filter->checkAccessCapability("import_data", TRUE);
            } else {
                $add_access = $this->filter->getModuleWiseAccess("UtilitiesImport", "Add", FALSE, TRUE);
            }
            if (!$add_access) {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }

            if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                $this->general->trackCustomNavigation('List', 'Viewed', $this->mod_enc_url['import_index'], $this->db->protect("m.vCapabilityCode") . " = " . $this->db->escape("import_data"));
            } else {
                $this->general->trackCustomNavigation('List', 'Viewed', $this->mod_enc_url['import_index'], $this->db->protect("m.vUniqueMenuCode") . " = " . $this->db->escape("UtilitiesImport"));
            }

            $this->config->load('cit_importdata', TRUE);
            $import_modules = $this->config->item('cit_importdata');

            $upload_location_arr = array(
                "local" => "Local Drive",
                "cloud" => "Cloud Drive",
                "web" => "Web URL"
            );
            $respose_format_arr = array(
                "csv" => "CSV",
                "json" => "JSON",
                "xml" => "XML"
            );

            $first_row = array(
                "Yes" => "Yes",
                "No" => "No"
            );
            $this->dropdown->combo("array", "import_first_row", $first_row);

            $columns_separator = array(
                "comma" => "Comma (,)",
                "tab" => "Tab",
                "semicolon" => "Semicolon (;)",
                "space" => "Space"
            );
            $this->dropdown->combo("array", "import_columns_separator", $columns_separator);

            $text_delimiter = array(
                "double" => "Double Quote (\")",
                "single" => "Single Quote (')"
            );
            $this->dropdown->combo("array", "import_text_delimiter", $text_delimiter);

            $decimal_separator = array(
                "dot" => "Dot (.)",
                "comma" => "Comma (,)"
            );
            $this->dropdown->combo("array", "import_decimal_separator", $decimal_separator);

            $thousand_separator = array(
                "none" => "None",
                "comma" => "Comma (,)",
                "dot" => "Dot (.)",
                "space" => "Space",
                "single" => "Single (')"
            );

            $this->dropdown->combo("array", "import_thousand_separator", $thousand_separator);

            $modules_list = $media_modules = array();
            if (is_array($import_modules) && count($import_modules) > 0) {
                foreach ($import_modules as $key => $val) {
                    $title = $this->lang->line($val['name']);
                    $modules_list[$key] = ($title) ? $title : $key;
                    if (isset($val['media']) && $val['media'] == TRUE) {
                        $media_modules[] = $key;
                    }
                }
            }

            $this->dropdown->combo("array", "upload_module", $modules_list);

            $import_folder_path = $this->config->item('import_files_path');
            $histroy_file = $import_folder_path . "import_history.json";
            $history_json = file_get_contents($histroy_file);
            $history_data = json_decode($history_json, TRUE);
            $history_count = 0;
            if (is_array($history_data) && count($history_data) > 0) {
                $history_count = count($history_data);
            }

            $render_arr = array(
                'func_name' => 'index',
                'mod_enc_url' => $this->mod_enc_url,
                'import_settings' => $this->import_settings,
                'media_modules' => json_encode($media_modules),
                'upload_location_arr' => $upload_location_arr,
                'respose_format_arr' => $respose_format_arr,
                'history_count' => $history_count,
                'add_access' => $add_access
            );
            $this->smarty->assign($render_arr);
            $this->loadView('import_index');
        } catch (Exception $e) {
            $render_arr['err_message'] = $e->getMessage();
            $this->smarty->assign($render_arr);
            $this->loadView($this->config->item('ADMIN_FORBIDDEN_TEMPLATE'));
        }
    }

    /**
     * upload method is used to upload file to import data.
     */
    public function upload()
    {
        $this->load->library('upload');
        $old_file = $this->input->get_post('oldFile', TRUE);
        $upload_files = $_FILES['Filedata'];
        list($file_name, $extension) = $this->general->get_file_attributes($upload_files['name']);
        $this->general->createUploadFolderIfNotExists('__temp');
        $temp_folder_path = $this->config->item('admin_upload_temp_path');

        try {

            $file_size = $this->import_settings['file_maxsize_org'];
            if ($upload_files['name'] == "") {
                throw new Exception($this->general->processMessageLabel('ACTION_UPLOAD_FILE_NOT_FOUND_C46_C46_C33'));
            }
            if (!$this->general->validateFileSize($file_size, $upload_files['size'])) {
                throw new Exception($this->general->processMessageLabel('ACTION_FILE_SIZE_NOT_A_VALID_ONE_C46_C46_C33'));
            }
            $upload_config = array(
                'upload_path' => $temp_folder_path,
                'allowed_types' => '*',
                'max_size' => $file_size,
                'file_name' => $file_name,
                'remove_space' => TRUE,
                'overwrite' => FALSE,
            );
            $this->upload->initialize($upload_config);
            if (!$this->upload->do_upload('Filedata')) {
                $upload_error = $this->upload->display_errors('', '');
                throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPLOADING_C46_C46_C33'));
            }
            $file_info = $this->upload->data();
            $file_name = $file_info['file_name'];

            if (!$file_name) {
                throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPLOADING_C46_C46_C33'));
            }

            if (is_file($temp_folder_path . $old_file) && $old_file != '') {
                unlink($temp_folder_path . $old_file);
            }

            $ret_arr = array();
            $ret_arr['is_multiple'] = 1;
            if (in_array(strtolower($extension), array('xlsx', 'xls', 'ods'))) {
                $sheets_arr = $this->csv_import->prepareExcelData($temp_folder_path . $file_name, 5);
                if (is_array($sheets_arr) && count($sheets_arr) > 1) {
                    $ret_arr['is_multiple'] = 1;
                    $ret_arr['sheets_html'] = $this->parser->parse('import_sheets', array("sheets" => $sheets_arr), true);
                }
            }
            $ret_arr['success'] = 1;
            $ret_arr['message'] = $this->general->processMessageLabel('ACTION_FILE_UPLOADED_SUCCESSFULLY_C46_C46_C33');
            $ret_arr['uploadfile'] = $file_name;
            $ret_arr['oldfile'] = $file_name;
        } catch (Exception $e) {
            $ret_arr['success'] = 0;
            $ret_arr['message'] = $e->getMessage();
        }
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    /**
     * media method is used to upload zip to import media files.
     */
    public function media()
    {
        $this->load->library('upload');
        $old_file = $this->input->get_post('oldFile', TRUE);
        $upload_files = $_FILES['Filedata'];
        $file_name = $upload_files['name'];

        $this->general->createUploadFolderIfNotExists('__temp');
        $temp_folder_path = $this->config->item('admin_upload_temp_path');
        $zip_folder_name = date("YmdHis") . "-" . rand(1000, 9999) . "-" . rand(1000, 9999);
        $zip_upload_path = $temp_folder_path . $zip_folder_name . DS;
        if (!is_dir($zip_upload_path)) {
            $this->general->createFolder($zip_upload_path);
        }

        try {

            $file_size = $this->import_settings['media_maxsize_org'];
            if ($upload_files['name'] == "") {
                throw new Exception($this->general->processMessageLabel('ACTION_UPLOAD_FILE_NOT_FOUND_C46_C46_C33'));
            }
            if (!$this->general->validateFileSize($file_size, $upload_files['size'])) {
                throw new Exception($this->general->processMessageLabel('ACTION_FILE_SIZE_NOT_A_VALID_ONE_C46_C46_C33'));
            }
            $upload_config = array(
                'upload_path' => $zip_upload_path,
                'allowed_types' => '*',
                'max_size' => $file_size,
                'file_name' => $file_name,
                'remove_space' => TRUE,
                'overwrite' => TRUE,
            );
            $this->upload->initialize($upload_config);
            if (!$this->upload->do_upload('Filedata')) {
                $upload_error = $this->upload->display_errors('', '');
                throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPLOADING_C46_C46_C33'));
            }

            $zip_extract_path = $zip_upload_path . $file_name;
            $zip_extract_info = pathinfo($zip_extract_path);
            $zip_extract_name = str_replace("." . $zip_extract_info['extension'], "", $file_name);

            require_once($this->config->item('third_party') . 'pclzip/pclzip.lib.php');
            $archive = new PclZip($zip_extract_path);
            $list = $archive->extract(PCLZIP_OPT_PATH, $zip_upload_path . $zip_extract_name);

            if ($list == 0) {
                throw new Exception("Error occurred while extracting file contents.");
            }
            if (is_file($temp_folder_path . $old_file) && $old_file != '') {
                unlink($temp_folder_path . $old_file);
            }

            $ret_arr = array();
            $ret_arr['success'] = 1;
            $ret_arr['message'] = $this->general->processMessageLabel('ACTION_FILE_UPLOADED_SUCCESSFULLY_C46_C46_C33');
            $ret_arr['uploadfile'] = $zip_folder_name . "/" . $zip_extract_name;
            $ret_arr['oldfile'] = $file_name;
        } catch (Exception $e) {
            $ret_arr['success'] = 0;
            $ret_arr['message'] = $e->getMessage();
        }
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    /**
     * read method is used to read imported file contents and displays to user.
     */
    public function read()
    {
        $data = $header = $mapping = array();
        try {

            $upload_module = $this->input->get_post('upload_module', TRUE);
            $upload_location = $this->input->get_post('upload_location', TRUE);
            $upload_csv = $this->input->get_post('upload_csv', TRUE);
            $upload_media = $this->input->get_post('upload_media', TRUE);
            $upload_index = $this->input->get_post('upload_index', TRUE);
            $upload_sheet = $this->input->get_post('upload_sheet', TRUE);
            $web_data_url = $this->input->get_post('web_data_url', TRUE);
            $response_format = $this->input->get_post('response_format', TRUE);
            $first_row = $this->input->get_post('import_first_row', TRUE);
            $columns_separator = $this->input->get_post('import_columns_separator', TRUE);
            $text_delimiter = $this->input->get_post('import_text_delimiter', TRUE);
            $decimal_separator = $this->input->get_post('import_decimal_separator', TRUE);
            $thousand_separator = $this->input->get_post('import_thousand_separator', TRUE);

            $temp_folder_path = $this->config->item('admin_upload_temp_path');
            if ($upload_location == "cloud") {
                $file_name = $upload_sheet;
            } elseif ($upload_location == "web") {
                if ($response_format == "csv") {
                    $file_name = "Web_Csv_" . time() . ".csv";
                    $file_path = $temp_folder_path . $file_name;
                    $fp = fopen($file_path, 'w');
                    fwrite($fp, file_get_contents($web_data_url));
                    fclose($fp);
                } elseif (in_array($response_format, array('xml', 'json'))) {
                    $web_data_url = $this->input->get_post('web_data_url', TRUE);
                    $web_data_url_keypath = $this->input->get_post('web_data_url_keypath', TRUE);
                    $response = "";
                    if ($response_format == "xml") {
                        $response = $this->csv_import->callCurlGet($web_data_url, array(), TRUE);
                    } elseif ($response_format == "json") {
                        $response = $this->csv_import->callCurlGet($web_data_url);
                        $response = json_decode($response, TRUE);
                    }
                    $response = $this->csv_import->getArrayvalueForKeyPath($response, $web_data_url_keypath);
                    $file_name = "Web_" . ucfirst($response_format) . "_" . time() . ".csv";
                    $file_path = $temp_folder_path . $file_name;
                    $fp = fopen($file_path, 'w');
                    if (is_array($response[0])) {
                        fputcsv($fp, array_keys($response[0]));
                    }
                    foreach ($response as $respv) {
                        $temp_row = array();
                        if (is_array($respv)) {
                            foreach ($respv as $respv2) {
                                $temp_row[] = is_array($respv2) ? "" : $respv2;
                            }
                        } else {
                            $temp_row[] = $respv;
                        }
                        fputcsv($fp, $temp_row);
                    }
                    fclose($fp);
                }
            } else {
                $file_name = $upload_csv;
            }
            $file_path = $temp_folder_path . DS . $file_name;
            if (!is_file($file_path)) {
                throw new Exception($this->general->processMessageLabel('ACTION_FILE_NOT_FOUND_C46_C46_C33'));
            }

            $csv_params = array(
                "separator" => $columns_separator,
                "delimiter" => $text_delimiter,
                "sheet" => ($upload_index) ? $upload_index : 0
            );
            //call the library
            $this->csv_import->setChunkRows($this->_show_limit);
            $result = $this->csv_import->readExcelContent($file_path, $csv_params);

            $this->config->load('cit_importdata', TRUE);
            $import_modules = $this->config->item('cit_importdata');
            $current_module = $import_modules[$upload_module];

            if (!is_array($current_module) || count($current_module) == 0) {
                throw new Exception("Import module configuration not found.");
            }

            $import_data_action = array(
                "Replace" => "Replace",
                "Merge" => "Merge"
            );

            $duplicate_data_action = array(
                "Skip" => "Skip",
                "Update" => "Update"
            );

            $import_error_action = array(
                "Skip" => "Skip Row",
                "Empty" => "Make Empty"
            );

            $skip_lookup_action = array(
                "Yes" => "Yes",
                "No" => "No"
            );

            $skip_validation_action = array(
                "Yes" => "Yes",
                "No" => "No"
            );

            $upload_table = $current_module['table'];
            $unique_arr = $current_module['unique'];
            $columns_arr = $current_module['cols'];
            if (!is_array($columns_arr) || count($columns_arr) == 0) {
                throw new Exception("Import module mapping columns not found.");
            }

            $row_count = $this->import_model->getTableRowCount($upload_table);
            foreach ($columns_arr as $key => $val) {
                if ($val['hide'] === TRUE) {
                    continue;
                }
                $title = $this->lang->line($val['name']);
                $mapping[$key] = ($title) ? $title : $key;
            }
            $this->dropdown->combo("array", "map_column", $mapping);

            $unique_fields = array();
            $unique_str = '---';
            $unique_cols = $unique_arr['cols'];
            if (is_array($unique_cols) && count($unique_cols) > 0) {
                foreach ($unique_cols as $key => $val) {
                    $title = $this->lang->line($columns_arr[$val]['name']);
                    $unique_fields[] = ($title) ? $title : $val;
                }
                $unique_type = ($unique_arr['type'] == "OR") ? "|" : "&";
                $unique_str = implode(" " . $unique_type . " ", $unique_fields);
            }

            if (!$result['success']) {
                throw new Exception($result['message']);
            }
            $rows = $result['data'];
            if ($first_row == "Yes") {
                $header = is_array($rows[0]) ? $rows[0] : array();
                $m = 1;
            } else {
                for ($i = 0, $j = 1; $i < count($rows[0]); $i++, $j++) {
                    $header[] = "Column - " . $j;
                }
                $m = 0;
            }

            for ($i = $m; $i < count($rows); $i++) {
                $data[] = is_array($rows[$i]) ? $rows[$i] : array();
            }
            $success = 1;
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        $render_arr = array(
            "success" => $success,
            "message" => $message,
            "module_name" => $upload_module,
            "table_name" => $upload_table,
            "row_count" => $row_count,
            "unique_str" => $unique_str,
            "file_name" => $file_name,
            "media_name" => $upload_media,
            "sheet_index" => $upload_index,
            "first_row" => $first_row,
            "columns_separator" => $columns_separator,
            "text_delimiter" => $text_delimiter,
            "import_action" => $import_data_action,
            "duplicate_action" => $duplicate_data_action,
            "error_action" => $import_error_action,
            "lookup_action" => $skip_lookup_action,
            "validation_action" => $skip_validation_action,
            "mapping" => $mapping,
            "header" => $header,
            "data" => $data
        );
        $this->smarty->assign($render_arr);
        $this->loadView('import_read');
    }

    /**
     * process method is used to insert records into database for selected table.
     */
    public function process()
    {
        set_time_limit(0);
        $import_module_name = $this->input->get_post('import_module_name', TRUE);
        $import_file_name = $this->input->get_post('import_file_name', TRUE);
        $import_media_name = $this->input->get_post('import_media_name', TRUE);
        $import_sheet_index = $this->input->get_post('import_sheet_index', TRUE);
        $import_first_row = $this->input->get_post('import_first_row', TRUE);
        $import_columns_separator = $this->input->get_post('import_columns_separator', TRUE);
        $import_text_delimiter = $this->input->get_post('import_text_delimiter', TRUE);
        $import_data_action = $this->input->get_post('import_data_action', TRUE);
        $duplicate_data_action = $this->input->get_post('duplicate_data_action', TRUE);
        $import_error_action = $this->input->get_post('import_error_action', TRUE);
        $skip_lookup_action = $this->input->get_post('skip_lookup_action', TRUE);
        $skip_validation_action = $this->input->get_post('skip_validation_action', TRUE);
        $skip_top_rows = $this->input->get_post('skip_top_rows', TRUE);
        $check_media_size = $this->input->get_post('check_media_size', TRUE);
        $check_media_ext = $this->input->get_post('check_media_ext', TRUE);
        $map_column_arr = $this->input->get_post('map_column', TRUE);
        $skip_column_arr = $this->input->get_post('skip_column', TRUE);
        $import_type = $this->input->get_post('import_type', TRUE);
        $save_type = $this->input->get_post('save_type', TRUE);

        $skip_top_rows = intval($skip_top_rows);
        $check_media_size = ($check_media_size == "No") ? "No" : "Yes";
        $check_media_ext = ($check_media_ext == "No") ? "No" : "Yes";
        $import_type = ($import_type == "commit") ? "commit" : "preview";
        if ($import_type == "commit") {
            $map_column_arr = explode("@@", $map_column_arr);
            $skip_column_arr = explode("@@", $skip_column_arr);
        }

        try {

            $temp_folder_path = $this->config->item('admin_upload_temp_path');
            $file_path = $temp_folder_path . DS . $import_file_name;
            if (!is_file($file_path)) {
                throw new Exception($this->general->processMessageLabel('ACTION_FILE_NOT_FOUND_C46_C46_C33'));
            }
            if ($import_type == "commit") {
                if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                    $this->general->trackCustomNavigation('Form', 'Added', $return_url, $this->db->protect("m.vCapabilityCode") . " = " . $this->db->escape("import_data"));
                } else {
                    $this->general->trackCustomNavigation('Form', 'Added', $return_url, $this->db->protect("m.vUniqueMenuCode") . " = " . $this->db->escape("UtilitiesImport"));
                }
            }
            $csv_params = array(
                "separator" => $import_columns_separator,
                "delimiter" => $import_text_delimiter,
                "sheet" => $import_sheet_index,
                "skip" => $skip_top_rows
            );
            //call the excel library
            $this->csv_import->setColSize($this->_cols_limit);
            $this->csv_import->setRowSize($this->_rows_limit);
            $result = $this->csv_import->readExcelContent($file_path, $csv_params);

            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            $this->config->load('cit_importdata', TRUE);
            $import_modules = $this->config->item('cit_importdata');
            $current_module = $import_modules[$import_module_name];

            if (!is_array($current_module) || count($current_module) == 0) {
                throw new Exception("Import module configuration not found.");
            }

            $import_table_name = $current_module['table'];
            $primary_key = $current_module['primary'];
            $unique_arr = $current_module['unique'];
            $columns_arr = $current_module['cols'];
            if (!is_array($columns_arr) || count($columns_arr) == 0) {
                throw new Exception("Import module mapping columns not found.");
            }

            $data = $result['data'];
            if (!is_array($data) || count($data) == 0) {
                throw new Exception("No data found to import.");
            }

            $duplicate_arr = $unique_cols = $import_hide_cols = $import_media_cols = $import_media_data = array();
            $track_failed = $track_inserted = $track_updated = $track_duplicate = $track_lookup = $track_valid = array();
            $import_failed = $import_inserted = $import_updated = $import_duplicate = $import_skipped = 0;
            $flush_rows = $total_count = $existed_rec = $duplicate_chk = $media_data_count = 0;

            if ($import_data_action == "Replace") {
                $temp_table_name = "__import_" . $import_table_name;
                $flush_rows = $existed_rec = $this->import_model->getTableRowCount($import_table_name);
                $backup_file = date("YmdHis") . "_" . $import_table_name . "_Backup.sql";
                $backup_path = $this->config->item('admin_backup_path');
                $this->load->library('ci_backup');
                $this->ci_backup->setFileLocations(array("file_path" => $backup_path, "file_name" => $backup_file));
                $this->ci_backup->setKeepDropTable(true);
                $this->ci_backup->setAllowTables(true);
                $this->ci_backup->setDatabaseTables(array($import_table_name));
                $this->ci_backup->createDBDump();
                if ($import_type == "commit") {
                    $this->import_model->truncateTable($import_table_name, $temp_table_name);
                } else {
                    $this->import_model->copyTable($import_table_name, $temp_table_name);
                }
            } else {
                $total_count = $existed_rec = $this->import_model->getTableRowCount($import_table_name);
                $unique_cols = $unique_arr['cols'];
                if (is_array($unique_cols) && count($unique_cols) > 0) {
                    sort($unique_cols);
                    $unique_data = $this->import_model->getTableUniqueRows($import_table_name, $unique_cols);
                    if (is_array($unique_data) && count($unique_data) > 0) {
                        foreach ($unique_data as $key => $val) {
                            $tmp_arr = array_values($val);
                            if ($unique_arr['type'] == "OR") {
                                $duplicate_arr = array_merge($duplicate_arr, $tmp_arr);
                            } else {
                                $duplicate_arr[] = implode("*&&*", $tmp_arr);
                            }
                        }
                    }
                    $duplicate_chk = 1;
                }
            }

            if ($import_type != "commit" && $primary_key != "") {
                $primary_max = $this->import_model->getPrimaryMaximum($import_table_name, $primary_key);
            }

            foreach ($columns_arr as $key => $val) {
                if ($val['hide'] === TRUE && is_array($val['default'])) {
                    $import_hide_cols[$key]['type'] = "hide";
                    $import_hide_cols[$key]['config'] = $val;
                    if ($val['default'][1] != "Function") {
                        $import_hide_cols[$key]['value'] = $this->csv_import->importDefaultValue($val['default']);
                    }
                } elseif (isset($val['phpfn']) && $val['phpfn'] != "") {
                    $import_hide_cols[$key]['type'] = "func";
                    $import_hide_cols[$key]['config'] = $val;
                }
                if (isset($current_module['media']) && $current_module['media'] == TRUE) {
                    if (isset($val['upload']) && isset($val['upload']['server'])) {
                        $import_media_cols[$key] = $val['upload'];
                        $import_media_cols[$key]['name'] = $val['name'];
                    }
                }
            }

            $m = $n = 0;
            if ($import_first_row == "Yes") {
                $m = 1;
            }

            do {

                $import_batch_arr = $import_dupli_arr = array();

                for ($i = $m; $i < count($data); $i++) {

                    $n++;
                    $tmp_arr = $uni_arr = array();
                    $dup_row = false;
                    $row = is_array($data[$i]) ? $data[$i] : array();

                    for ($j = 0; $j < count($map_column_arr); $j++) {
                        if (!in_array($j, $skip_column_arr)) {
                            continue;
                        }
                        $key = trim($map_column_arr[$j]);
                        $val = trim($row[$j]);
                        $col = $columns_arr[$key];
                        $lookup_data = $this->_validate_lookup($key, $val, $row, $col, $current_module);
                        if ($lookup_data !== FALSE && $lookup_data !== TRUE) {
                            $val = $lookup_data;
                        }
                        $valid_result = $lookup_result = TRUE;
                        if ($skip_validation_action == "No") {
                            $valid_result = $this->_validate_column($key, $val, $col);
                        }
                        if ($skip_lookup_action == "No" && $valid_result === TRUE) {
                            $lookup_result = ($lookup_data === FALSE) ? FALSE : TRUE;
                        }
                        if ($valid_result === FALSE || $lookup_result === FALSE) {
                            if ($import_error_action == "Skip") {
                                $import_skipped += 1;
                                if ($valid_result === FALSE && count($track_valid) < $this->_info_limit) {
                                    $track_valid[] = $row;
                                } elseif ($lookup_result === FALSE && count($track_lookup) < $this->_info_limit) {
                                    $track_lookup[] = $row;
                                }
                                continue 2;
                            } else {
                                $val = '';
                            }
                        }
                        if ($val != "" && array_key_exists($key, $import_media_cols)) {
                            $med = $this->_validate_media_file($val, $import_media_cols[$key], $check_media_ext);
                            $import_media_data[$key][] = array('src' => $val, 'dst' => $med);
                            $media_data_count++;
                            $val = $med;
                        }
                        if ($this->csv_import->isDecimalType($col['type'])) {
                            $val = $this->csv_import->convertPriceToFloat($val);
                        } elseif ($this->csv_import->isDateTimeType($col['type'])) {
                            $val = $this->csv_import->convertToDate($val);
                        } elseif ($this->csv_import->isDateType($col['type'])) {
                            $val = $this->csv_import->convertToDateTime($val);
                        } elseif ($this->csv_import->isTimeType($col['type'])) {
                            $val = $this->csv_import->convertToTime($val);
                        }
                        if (isset($col['phpfn']) && $col['phpfn'] != "") {
                            $phpfunc = $col['phpfn'];
                            if (function_exists($phpfunc)) {
                                $val = call_user_func($phpfunc, $val);
                            } elseif (method_exists($this->general, $phpfunc)) {
                                $val = $this->general->$phpfunc($val, $row, $map_column_arr);
                            }
                        }
                        $tmp_arr[$key] = $val;
                        if (in_array($key, $unique_cols)) {
                            if ($unique_arr['type'] == "OR") {
                                if (in_array($val, $duplicate_arr)) {
                                    $dup_row = true;
                                }
                                $duplicate_arr[] = $val;
                            }
                            $uni_arr[$key] = $val;
                        }
                    }

                    //$tmp_arr = array_merge($tmp_arr, $import_hide_cols);
                    if (is_array($import_hide_cols) && count($import_hide_cols) > 0) {
                        foreach ($import_hide_cols as $hKey => $hVal) {
                            $col = $hVal['config'];
                            $val = '';
                            if ($hVal['type'] == "hide") {
                                if ($col['default'][1] == "Function") {
                                    $val = $this->csv_import->importDefaultValue($col['default'], $tmp_arr, $col, $current_module);
                                } else {
                                    $val = $hVal['value'];
                                }
                            } elseif ($hVal['type'] == "func") {
                                if (isset($tmp_arr[$hKey])) {
                                    continue;
                                }
                            }
                            if (isset($col['phpfn']) && $col['phpfn'] != "") {
                                $phpfunc = $col['phpfn'];
                                if (function_exists($phpfunc)) {
                                    $val = call_user_func($phpfunc, $val);
                                } elseif (method_exists($this->general, $phpfunc)) {
                                    $val = $this->general->$phpfunc($val, $tmp_arr, $map_column_arr);
                                }
                            }
                            $tmp_arr[$hKey] = $val;
                            if (in_array($hKey, $unique_cols)) {
                                if ($unique_arr['type'] == "OR") {
                                    if (in_array($val, $duplicate_arr)) {
                                        $dup_row = true;
                                    }
                                    $duplicate_arr[] = $val;
                                }
                                $uni_arr[$key] = $val;
                            }
                        }
                    }

                    if ($duplicate_chk == 1) {
                        if ($unique_arr['type'] == "OR") {
                            if ($dup_row == true) {
                                $import_duplicate += 1;
                                $import_dupli_arr[] = array(
                                    "data" => $tmp_arr,
                                    "where" => $uni_arr
                                );
                            } else {
                                $import_batch_arr[] = $tmp_arr;
                            }
                        } else {
                            ksort($uni_arr);
                            $uni_str = implode("*&&*", $uni_arr);
                            if (in_array($uni_str, $duplicate_arr)) {
                                $import_duplicate += 1;
                                $import_dupli_arr[] = array(
                                    "data" => $tmp_arr,
                                    "where" => $uni_arr
                                );
                            } else {
                                $import_batch_arr[] = $tmp_arr;
                            }
                            $duplicate_arr[] = $uni_str;
                        }
                    } else {
                        $import_batch_arr[] = $tmp_arr;
                    }
                }

                $chunk_size = $this->_chunk_size;
                $import_batch = array_chunk($import_batch_arr, $chunk_size);
                $this->import_model->startTransaction();
                for ($i = 0, $j = 0; $i < count($import_batch); $i++, $j += $chunk_size) {
                    $ressponse = $this->import_model->importBatch($import_table_name, $import_batch[$i]);
                    if ($ressponse === -1) {
                        list($bin_failed, $bin_success) = $this->_do_binary_approach($import_table_name, $import_batch[$i], $j);
                        $import_failed += $bin_failed;
                        $import_inserted += $bin_success;
                        if (count($track_failed) < $this->_info_limit) {
                            $track_failed = is_array($this->_track_failed) ? array_merge($track_failed, $this->_track_failed) : $track_failed;
                        }
                        if (count($track_inserted) < $this->_info_limit) {
                            $track_inserted = is_array($this->_track_success) ? array_merge($track_inserted, $this->_track_success) : $track_inserted;
                        }
                        $this->_track_failed = array();
                        $this->_track_success = array();
                    } else {
                        $chunk_count = count($import_batch[$i]);
                        $import_inserted += $chunk_count;
                        if (count($track_inserted) < $this->_info_limit) {
                            $track_inserted = array_merge($track_inserted, $import_batch[$i]);
                        }
                    }
                }
                if ($duplicate_data_action == "Update") {
                    for ($i = 0; $i < count($import_dupli_arr); $i++) {
                        $ressponse = $this->import_model->updateTable($import_table_name, $import_dupli_arr[$i]['data'], $import_dupli_arr[$i]['where']);
                        if ($ressponse === -1) {
                            $import_failed += 1;
                            if (count($track_failed) < $this->_info_limit) {
                                $track_failed[] = $import_dupli_arr[$i]['data'];
                            }
                        } elseif ($ressponse) {
                            $import_updated += 1;
                            if (count($track_updated) < $this->_info_limit) {
                                $track_updated[] = $import_dupli_arr[$i]['data'];
                            }
                        }
                    }
                    $import_duplicate = 0;
                } else {
                    for ($i = 0; $i < count($import_dupli_arr); $i++) {
                        if (count($track_duplicate) > $this->_info_limit) {
                            break;
                        }
                        $track_duplicate[] = $import_dupli_arr[$i]['data'];
                    }
                }

                if ($import_type == "commit") {
                    $this->import_model->commitTransaction();
                } else {
                    $this->import_model->rollbackTransaction();
                }

                $next_page = intval($result['next']);
                $next_cursor = FALSE;
                if ($next_page > 0) {
                    $result = $this->csv_import->readExcelContent($file_path, $csv_params, $next_page);
                    if ($result['success']) {
                        $data = $result['data'];
                        $m = 0;
                        $next_cursor = TRUE;
                    }
                }
            } while ($next_cursor);

            if ($import_type != "commit") {
                if ($import_data_action == "Replace") {
                    $this->import_model->revertTable($import_table_name, $temp_table_name);
                }
                if ($primary_key != "") {
                    $this->import_model->resetAutoIncrement($import_table_name, $primary_max);
                }
            }

            $message = "Data import done successfully.";
            $success = 1;
        } catch (Exception $e) {
            $message = $e->getMessage();
            $success = 0;
        }


        $total_count += $import_inserted;
        $import_success = $import_inserted + $import_updated;

        if ($import_type == "commit") {
            $inserted_file = $this->input->get_post('track_inserted', TRUE);
            $updated_file = $this->input->get_post('track_updated', TRUE);
            $failed_file = $this->input->get_post('track_failed', TRUE);
            $duplicate_file = $this->input->get_post('track_duplicate', TRUE);
            $valid_file = $this->input->get_post('track_lookup', TRUE);
            $lookup_file = $this->input->get_post('track_valid', TRUE);

            $import_folder_path = $this->config->item('import_files_path');
            copy($file_path, $import_folder_path . $import_file_name);

            $module_title = $this->lang->line($current_module['name']);
            $history_arr = array();
            $history_arr['module'] = ($module_title) ? $module_title : $current_module['name'];
            $history_arr['table'] = $import_table_name;
            $history_arr['file'] = $import_file_name;
            $history_arr['date'] = date("Y-m-d H:i:s");
            $history_arr['existed'] = $existed_rec;
            $history_arr['success'] = $import_success;
            $history_arr['inserted'] = $import_inserted;
            $history_arr['updated'] = $import_updated;
            $history_arr['duplicate'] = $import_duplicate;
            $history_arr['failed'] = $import_failed;
            $history_arr['skipped'] = $import_skipped;
            $history_arr['flushed'] = $flush_rows;
            $history_arr['total'] = $total_count;

            $histroy_file = $import_folder_path . "import_history.json";
            $history_json = file_get_contents($histroy_file);
            $history_data = json_decode($history_json, TRUE);
            if (is_array($history_data) && count($history_data) >= $this->_history_limit) {
                $rename_name = "import_history_1";
                $rename_file = $import_folder_path . $rename_name . ".json";
                $r = 2;
                while (is_file($rename_file_path)) {
                    $rename_file_path = $import_folder_path . $rename_name . "_" . $r . ".json";
                }
                rename($histroy_file, $rename_file);
                $history_data = array();
            }
            if (is_array($import_media_cols) && count($import_media_cols) > 0) {
                $temp_name = date("YmdHis") . "-" . rand(1000, 9999) . "-" . rand(1000, 9999);
                $temp_folder = $temp_folder_path . $temp_name . DS;
                $this->general->createFolder($temp_folder);

                $media_files_arr['dir'] = $import_media_name;
                $media_files_arr['cols'] = $import_media_cols;
                $media_files_arr['data'] = $import_media_data;
                $media_files_arr['count'] = $media_data_count;
                $media_files_arr['folder'] = $temp_folder;
                $media_log_file = $import_module_name . "_media_log_" . time() . ".json";
//                $media_data_file = $import_module_name . "_media_dat_" . time() . ".json";
//                $fp = fopen($temp_folder . $media_data_file, 'w');
//                fwrite($fp, json_encode($media_files_arr));
//                fclose($fp);
//                $history_arr['media']['source'] = $media_data_file;
                $history_arr['media']['logger'] = $media_log_file;
            }
            $history_data[] = $history_arr;
            $fp = fopen($histroy_file, 'w');
            fwrite($fp, json_encode($history_data));
            fclose($fp);

            unlink($file_path);
            unlink($inserted_file);
            unlink($updated_file);
            unlink($failed_file);
            unlink($lookup_file);
            unlink($valid_file);

            $ret_arr = array();
            $ret_arr['success'] = $success;
            $ret_arr['message'] = $message;
            if ($save_type == "view" && $current_module['link'] != "") {
                $ret_arr['red_url'] = $this->general->getAdminEncodeURL($current_module['link']);
            }

            if (is_array($import_media_cols) && count($import_media_cols) > 0) {
                $media_event_file = $this->input->get_post('media_event', TRUE);
                $fp = fopen($temp_folder_path . $media_event_file, 'w');
                fwrite($fp, json_encode($ret_arr));
                fclose($fp);
                $this->csv_import->uploadMediaFiles($media_files_arr, $media_data_file, $media_log_file, $check_media_size);
            }

            echo json_encode($ret_arr);
            $this->skip_template_view();
        } else {
            if (is_array($track_failed) && count($track_failed) > 0) {
                $track_failed_file = $import_module_name . "_falied_" . time() . ".json";
                $fp = fopen($temp_folder_path . $track_failed_file, 'w');
                fwrite($fp, json_encode($track_failed));
                fclose($fp);
            }
            if (is_array($track_inserted) && count($track_inserted) > 0) {
                $track_inserted_file = $import_module_name . "_inserted_" . time() . ".json";
                $fp = fopen($temp_folder_path . $track_inserted_file, 'w');
                fwrite($fp, json_encode($track_inserted));
                fclose($fp);
            }
            if (is_array($track_updated) && count($track_updated) > 0) {
                $track_updated_file = $import_module_name . "_updated_" . time() . ".json";
                $fp = fopen($temp_folder_path . $track_updated_file, 'w');
                fwrite($fp, json_encode($track_updated));
                fclose($fp);
            }
            if (is_array($track_duplicate) && count($track_duplicate) > 0) {
                $track_duplicate_file = $import_module_name . "_duplicate_" . time() . ".json";
                $fp = fopen($temp_folder_path . $track_duplicate_file, 'w');
                fwrite($fp, json_encode($track_duplicate));
                fclose($fp);
            }
            if (is_array($track_lookup) && count($track_lookup) > 0) {
                $track_lookup_file = $import_module_name . "_lookup_" . time() . ".json";
                $fp = fopen($temp_folder_path . $track_lookup_file, 'w');
                fwrite($fp, json_encode($track_lookup));
                fclose($fp);
            }
            if (is_array($track_valid) && count($track_valid) > 0) {
                $track_valid_file = $import_module_name . "_valid_" . time() . ".json";
                $fp = fopen($temp_folder_path . $track_valid_file, 'w');
                fwrite($fp, json_encode($track_valid));
                fclose($fp);
            }
            $module_title = $this->lang->line($current_module['name']);
            $module_title = ($module_title) ? $module_title : $current_module['name'];
            $render_arr = array(
                "success" => $success,
                "message" => $message,
                "module_title" => $module_title,
                "module_name" => $import_module_name,
                "table_name" => $import_table_name,
                "file_name" => $import_file_name,
                "media_name" => $import_media_name,
                "first_row" => $import_first_row,
                "columns_separator" => $import_columns_separator,
                "text_delimiter" => $import_text_delimiter,
                "import_action" => $import_data_action,
                "duplicate_action" => $duplicate_data_action,
                "error_action" => $import_error_action,
                "lookup_action" => $skip_lookup_action,
                "validation_action" => $skip_validation_action,
                "skip_top_rows" => $skip_top_rows,
                "import_duplicate" => $import_duplicate,
                "import_failed" => $import_failed,
                "import_success" => $import_success,
                "import_skipped" => $import_skipped,
                "flush_rows" => $flush_rows,
                "total_count" => $total_count,
                "media_count" => $media_data_count,
                "map_column_arr" => implode("@@", $map_column_arr),
                "skip_column_arr" => implode("@@", $skip_column_arr),
                "track_failed" => $track_failed_file,
                "track_inserted" => $track_inserted_file,
                "track_updated" => $track_updated_file,
                "track_duplicate" => $track_duplicate_file,
                "track_lookup" => $track_lookup_file,
                "track_valid" => $track_valid_file,
            );
            $this->smarty->assign($render_arr);
            $this->loadView('import_process');
        }
    }

    private function _do_binary_approach($table_name = '', $insert_arr = array(), $j = 0)
    {
        $failed = $sucess = 0;
        if (!is_array($insert_arr) || count($insert_arr) == 0) {
            return array($failed, $sucess);
        }
        $array_count = count($insert_arr);
        if ($array_count == 1) {
            $ressponse = $this->import_model->importBatch($table_name, $insert_arr);
            if ($ressponse === -1) {
                $failed += 1;
                $this->_track_failed[] = $insert_arr;
            } else {
                $sucess += 1;
                $this->_track_success[] = $insert_arr;
            }
        } else {
            $chunk_size = round($array_count / 2);
            $batch_arr = array_chunk($insert_arr, $chunk_size);
            for ($i = 0, $k = 0; $i < count($batch_arr); $i++, $k += $chunk_size) {
                $ressponse = $this->import_model->importBatch($table_name, $batch_arr[$i]);
                if ($ressponse === -1) {
                    list($re_failed, $re_success) = $this->_do_binary_approach($table_name, $batch_arr[$i], ($j + $k));
                    $failed += $re_failed;
                    $sucess += $re_success;
                } else {
                    $chunk_count = count($batch_arr[$i]);
                    $sucess += $chunk_count;
                    $this->_track_success = array_merge($this->_track_success, $batch_arr[$i]);
                }
            }
        }
        return array($failed, $sucess);
    }

    private function _validate_column($key = '', $val = '', $config = array())
    {
        $rules = $config['rules'];
        $verified = TRUE;
        if (is_array($rules) && count($rules) > 0) {
            foreach ($rules as $rk => $rv) {
                $check_func = "check_" . $rk;
                if (method_exists($this, $this->csv_import)) {
                    $verified = $this->csv_import->$check_func($val, $rv);
                    if ($verified === FALSE) {
                        break;
                    }
                }
            }
        }
        if (!isset($config['null'])) {
            if ($val == "") {
                $verified = FALSE;
            }
        }
        return $verified;
    }

    private function _validate_lookup($key = '', $val = '', $row = array(), $config = array(), $current = array())
    {
        if (!isset($config['lookup'])) {
            return TRUE;
        }
        if (array_key_exists($this->_loopup_entries, $key)) {
            $data_arr = $this->_loopup_entries[$key];
        } else {
            if ($config['lookup']['type'] == 'table') {
                if ($config['lookup']['table'][0] != "" && $config['lookup']['table'][1] != "" && $config['lookup']['table'][2] != "") {
                    $data_arr = $this->import_model->getTableLookupRows($config['lookup']['table'][0], $config['lookup']['table'][1], $config['lookup']['table'][2]);
                }
            } elseif ($config['lookup']['type'] == 'list') {
                $data_arr = $config['lookup']['list'];
            } elseif ($config['lookup']['type'] == 'phpfn') {
                $module = $current['module'];
                $folder = $current['folder'];
                $phpfunc = $config['lookup']['func'];
                if (substr($phpfunc, 0, 12) == 'controller::' && substr($phpfunc, 12) !== FALSE) {
                    $this->load->module("{$folder}/{$module}");
                    if (is_object($this->$module)) {
                        $phpfunc = substr($phpfunc, 12);
                        if (method_exists($this->$module, $phpfunc)) {
                            $data_arr = $this->$module->$phpfunc($val, "Import", '', $row);
                        }
                    }
                } elseif (substr($phpfunc, 0, 7) == 'model::' && substr($phpfunc, 7) !== FALSE) {
                    $model = $module . "_model";
                    $this->load->model("{$folder}/{$model}");
                    $phpfunc = substr($phpfunc, 7);
                    if (is_object($this->$model)) {
                        if (method_exists($this->$model, $phpfunc)) {
                            $data_arr = $this->$model->$phpfunc($val, "Import", '', $row);
                        }
                    }
                } elseif (method_exists($this->general, $phpfunc)) {
                    $data_arr = $this->general->$phpfunc($val, "Import", '', $row);
                }
            }
            $this->_loopup_entries[$key] = $data_arr;
        }
        $data_arr = is_array($data_arr) ? $data_arr : array();
        if (empty($data_arr)) {
            return TRUE;
        } else {
            if ($config['lookup']['type'] == 'table') {
                if (array_key_exists($val, $data_arr)) {
                    return $val;
                } elseif (in_array($val, $data_arr)) {
                    return array_search($val, $data_arr);
                } else {
                    return FALSE;
                }
            } else {
                if (in_array($val, $data_arr)) {
                    return $val;
                } else {
                    return FALSE;
                }
            }
        }
    }

    private function _validate_media_file($val = '', $config = array(), $check = "Yes")
    {
        if (trim($val) == "") {
            return $val;
        }

        $val = trim($val);
        if ($this->general->isExternalURL($val)) {
            $url = $val;
            $arr = explode("/", $url);
            $val = $arr[count($arr) - 1];
        }

        $fil = explode(".", $val);
        if (!is_array($fil) || count($fil) <= 1) {
            return $val;
        }

        $ext = $fil[count($fil) - 1];
        if (in_array($ext, $this->_restricted_exts)) {
            $val = '';
        } elseif ($check == "Yes") {
            $ext_arr = explode(",", $config['limits'][0]);
            if (!in_array($ext, $ext_arr) && $ext_arr[0] != "*") {
                $val = '';
            }
        }
        if ($val != "") {
            unset($fil[count($fil) - 1]);
            $nam = implode("_", $fil);
            $uni = str_replace(" ", "_", $nam);
            $val = $uni . "-" . time() . "-" . uniqid(rand()) . "." . $ext;
        }
        return $val;
    }

    public function gdrive_manager()
    {
        $apiType = $this->session->userdata('__oauth_api_type');
        $apiType = (($apiType != '') ? $apiType : 'gdrive');
        $render_arr = array(
            'apiType' => $apiType,
            'gdrive_client_id' => $this->config->item('GOOGLE_OAUTH_CLIENT_ID'),
            'gdrive_client_secret' => $this->config->item('GOOGLE_OAUTH_CLIENT_SECRET'),
            'gdrive_redirect_uri' => $this->_gdrive_redirect_uri,
            'dropbox_client_id' => $this->config->item('DROPBOX_OAUTH_CLIENT_ID'),
            'dropbox_client_secret' => $this->config->item('DROPBOX_OAUTH_CLIENT_SECRET'),
            'dropbox_redirect_uri' => $this->_dropbox_redirect_uri
        );
        $this->smarty->assign($render_arr);
        $this->loadView('import_gdrive_manager');
    }

    public function gdrive_config()
    {
        $type = $this->input->get_post('type', TRUE);
        $client_id = $this->input->get_post('client_id', TRUE);
        $client_secret = $this->input->get_post('client_secret', TRUE);
        if ($type == "gdrive") {
            $data = array();
            $data['vValue'] = $client_id;
            $extra_cond = $this->db->protect("vName") . " = " . $this->db->escape('GOOGLE_OAUTH_CLIENT_ID');
            $success = $this->systemsettings->updateSetting($data, $extra_cond);

            $data = array();
            $data['vValue'] = $client_secret;
            $extra_cond = $this->db->protect("vName") . " = " . $this->db->escape('GOOGLE_OAUTH_CLIENT_SECRET');
            $success = $this->systemsettings->updateSetting($data, $extra_cond);
        } elseif ($type == 'dropbox') {
            $data = array();
            $data['vValue'] = $client_id;
            $extra_cond = $this->db->protect("vName") . " = " . $this->db->escape('DROPBOX_OAUTH_CLIENT_ID');
            $success = $this->systemsettings->updateSetting($data, $extra_cond);

            $data = array();
            $data['vValue'] = $client_secret;
            $extra_cond = $this->db->protect("vName") . " = " . $this->db->escape('DROPBOX_OAUTH_CLIENT_SECRET');
            $success = $this->systemsettings->updateSetting($data, $extra_cond);
        }
        $message = ($success) ? "Config setting updated successfully." : "Failure in adding config settings.";
        $return_arr = array();
        $return_arr['success'] = $success;
        $return_arr['message'] = $message;

        echo json_encode($return_arr);
        $this->skip_template_view();
    }

    public function gdrive_auth()
    {
        $_nA = $this->input->get_post('_nA', TRUE);
        $code = $this->input->get_post('code', TRUE);
        if (!is_null($_nA) && $_nA == '1') {
            unset($_SESSION['OAUTH_ACCESS_TOKEN']);
            unset($_SESSION['OAUTH_STATE']);
            unset($_SESSION['__oauth_api_token']);
            $this->session->unset_userdata('__oauth_api_token');
            $this->session->unset_userdata('OAUTH_ACCESS_TOKEN');
            $this->session->unset_userdata('OAUTH_STATE');
        }

        try {
            $scopes = array(
                'https://spreadsheets.google.com/feeds',
                'https://www.googleapis.com/auth/drive'
            );
            require_once($this->config->item('third_party') . 'oauth/vendor/autoload.php');
            $client = new oauth_client_class;

            $client->server = 'Google';
            $client->debug = false;
            $client->offline = true;
            $client->debug_http = true;
            $client->access_type = "offline";
            $client->approval_prompt = "force";
            $client->redirect_uri = $this->_gdrive_redirect_uri;
            $client->client_id = $this->config->item('GOOGLE_OAUTH_CLIENT_ID');
            $client->client_secret = $this->config->item('GOOGLE_OAUTH_CLIENT_SECRET');
            $client->scope = implode(" ", $scopes);
            if (strlen($client->client_id) == 0 || strlen($client->client_secret) == 0) {
                throw new Exception('Please go to Google APIs console page ' .
                '<a href="http://code.google.com/apis/console" target="_blank">http://code.google.com/apis/console</a> in the API access tab, ' .
                'create a new client ID, and set the client_id to Client ID and client_secret. ' .
                'The callback URL must be <b>' . $client->redirect_uri . '</b> but make sure ' .
                'the domain is valid and can be resolved by a public DNS.');
            }

            if ($success = $client->Initialize()) {
                if ($success = $client->Process()) {
                    if (strlen($client->authorization_error)) {
                        throw new Exception($client->authorization_error);
                    }
                }
                $success = $client->Finalize($success);
                $this->session->set_userdata('__oauth_api_token', $client->access_token);
            }
            if (!isset($client->access_token) || trim($client->access_token) == "") {
                throw new Exception($client->error);
            }
            $this->session->set_userdata('__oauth_api_type', 'gdrive');
            $success = 1;
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $render_arr = array(
            "api" => 'gdrive',
            "success" => $success,
            "message" => $message,
        );
        $htm_str = $this->parser->parse('import_gdrive_auth_redirect', $render_arr, true);
        echo $htm_str;
        $this->skip_template_view();
    }

    public function get_gdrive_data()
    {
        $params_arr = array();
        $params_arr['type'] = $this->input->get_post('type', TRUE);
        $params_arr['docId'] = $this->input->get_post('docId', TRUE);
        $params_arr['docName'] = $this->input->get_post('docName', TRUE);
        $params_arr['sheetId'] = $this->input->get_post('sheetId', TRUE);
        $access_token = $this->session->userdata('__oauth_api_token');
        $render_arr = $full_arr = array();
        try {
            if (!isset($access_token) || trim($access_token) == "") {
                throw new Exception("Google authentication required.");
            }
            $header_params = array();
            $header_params[] = "Authorization: Bearer " . $access_token;
            if ($params_arr['type'] == "docs") {
                $header_params[] = "Content-Type: application/atom+xml";
                // Fetching all spreadsheets
                $uri_list_files = "https://spreadsheets.google.com/feeds/spreadsheets/private/full";
                $list_files_response = $this->csv_import->callCurlGet($uri_list_files, $header_params, TRUE);
                $render_arr['list_files_response'] = $list_files_response;
                if (is_array($list_files_response) && count($list_files_response) > 0) {
                    if (isset($list_files_response['feed']['entry']) && is_array($list_files_response['feed']['entry']) && count($list_files_response['feed']['entry']) > 0) {
                        $loop_lf_arr = $list_files_response['feed']['entry'];
                        if (array_keys($loop_lf_arr) !== range(0, count($loop_lf_arr) - 1)) {
                            $loop_lf_arr = array($loop_lf_arr);
                        }
                        foreach ($loop_lf_arr as $lfKey => $lfValue) {
                            $temp_arr = array();
                            $temp_arr['docName'] = $lfValue['title'];
                            $file_id = basename($lfValue['id']);
                            $temp_arr['docId'] = $file_id;
                            $full_arr[] = $temp_arr;
                        }
                    }
                }
            } elseif ($params_arr['type'] == "sheets") {
                $file_id = $params_arr['docId'];
                $file_name = trim($params_arr['docName']);
                $uri_file_path = "https://docs.google.com/spreadsheets/export?id=" . $file_id . "&exportFormat=xlsx";
                $temp_folder_path = $this->config->item('admin_upload_temp_path');
                $file_name = $file_name . ".xlsx";
                list($file_name, $extension) = $this->general->get_file_attributes($file_name);

                $fp = fopen($temp_folder_path . $file_name, "wb");
                $this->csv_import->callCurlGetFile($uri_file_path, $header_params, $fp);
                fclose($fp);

                $sheets_arr = $this->csv_import->prepareExcelData($temp_folder_path . $file_name, 5);
                $params_arr['docFile'] = $file_name;

                $full_arr['sheets'] = $sheets_arr;
            }

            $render_arr['data'] = $full_arr;
            $success = 1;
            $message = "Access granted.";
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $render_arr['success'] = $success;
        $render_arr['message'] = $message;
        $render_arr['params'] = $params_arr;

        echo $this->parser->parse('import_gdrive_sheets', $render_arr, TRUE);
    }

    public function save_gdrive_data()
    {
        $params_arr = array();
        $params_arr['docFile'] = $this->input->get_post('docFile', TRUE);
        $params_arr['sheetId'] = $this->input->get_post('sheetId', TRUE);
        $access_token = $this->session->userdata('__oauth_api_token');
        try {
            if (!isset($access_token) || trim($access_token) == "") {
                throw new Exception("Google authentication failed.");
            }
            $header_params = array();
            $header_params[] = "Authorization: Bearer " . $access_token;

            $sheetId = $params_arr['sheetId'];

            $temp_folder_path = $this->config->item('admin_upload_temp_path');
            $file_name = $params_arr['docFile'];

            $sheet_arr = $this->csv_import->prepareExcelData($temp_folder_path . $file_name, -1, $sheetId);
            unlink($temp_folder_path . $file_name);

            $temp_folder_path = $this->config->item('admin_upload_temp_path');
            $file_name = substr_replace($file_name, ".csv", -5, 5);
            $file_path = $temp_folder_path . $file_name;
            $fp2 = fopen($file_path, 'w');
            foreach ($sheet_arr[0]['rows'] as $v) {
                fputcsv($fp2, $v);
            }
            fclose($fp2);
            $this->skip_template_view();

            $success = 1;
            $message = "Data saved successfully.";
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $ret_arr['success'] = $success;
        $ret_arr['message'] = $message;
        $ret_arr['file_name'] = $file_name;

        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    public function dropbox_auth()
    {
        $_nA = $this->input->get_post('_nA', TRUE);
        $code = $this->input->get_post('code', TRUE);
        if (!is_null($_nA) && $_nA == '1') {
            unset($_SESSION['OAUTH_ACCESS_TOKEN']);
            unset($_SESSION['OAUTH_STATE']);
            unset($_SESSION['__oauth_api_token']);
            $this->session->unset_userdata('__oauth_api_token');
            $this->session->unset_userdata('OAUTH_ACCESS_TOKEN');
            $this->session->unset_userdata('OAUTH_STATE');
        }

        try {

            require_once($this->config->item('third_party') . 'oauth/vendor/autoload.php');
            $client = new oauth_client_class;

            $client->server = 'Dropbox2';
            $client->debug = false;
            $client->debug_http = true;
            $client->configuration_file_path = $this->config->item('third_party') . 'oauth/vendor/phpclasses/oauth-api/';
            $client->redirect_uri = $this->_dropbox_redirect_uri;
            $client->client_id = $this->config->item('DROPBOX_OAUTH_CLIENT_ID');
            $client->client_secret = $this->config->item('DROPBOX_OAUTH_CLIENT_SECRET');

            if (strlen($client->client_id) == 0 || strlen($client->client_secret) == 0) {
                throw new Exception('Please go to Dropbox APIs console page ' .
                'https://www.dropbox.com/developers in the API access link, ' .
                'create a new client ID, and set the client_id to Client ID and client_secret. ' .
                'The callback URL must be ' . $client->redirect_uri . ' but make sure ' .
                'the domain is valid and can be resolved by a public DNS.');
            }

            if ($success = $client->Initialize()) {
                if ($success = $client->Process()) {
                    if (strlen($client->authorization_error)) {
                        throw new Exception($client->authorization_error);
                    }
                }
                $success = $client->Finalize($success);
                $this->session->set_userdata('__oauth_api_token', $client->access_token);
            }
            if (!isset($client->access_token) || trim($client->access_token) == "") {
                throw new Exception($client->error);
            }
            $success = 1;
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        $render_arr = array(
            "api" => 'dropbox',
            "success" => $success,
            "message" => $message,
        );
        $htm_str = $this->parser->parse('import_gdrive_auth_redirect', $render_arr, true);
        echo $htm_str;
        $this->skip_template_view();
    }

    public function get_dropbox_data()
    {
        $render_arr = $params_arr = array();
        $access_token = $this->session->userdata('__oauth_api_token');
        $params_arr['type'] = $this->input->get_post('type', TRUE);
        try {
            if (!isset($access_token) || trim($access_token) == "") {
                throw new Exception("Dropbox authentication required.");
            }
            $header_params = array();
            $header_params[] = "Authorization: Bearer " . $access_token;
            if ($params_arr['type'] == 'files') {
                $culr_exts = explode("|", $this->import_settings["file_extensions"]);
                $culr_parmas = array();
                foreach ($culr_exts as $cxt) {
                    $culr_parmas[] = array('url' => 'https://api.dropbox.com/1/search/auto?query=.' . $cxt, 'headers' => $header_params);
                }
                $files_response = $this->csv_import->callMultiCurlGet($culr_parmas, TRUE);
                $files_list = $this->csv_import->prepareFiles($files_response);
                $render_arr['assets'] = $files_list;
                $success = 1;
                $message = "Access granted.";
            } elseif ($params_arr['type'] == 'content') {
                $params_arr['fileId'] = $this->input->get_post('fileId', TRUE);
                $params_arr['fileRev'] = $this->input->get_post('fileRev', TRUE);

                $fileId = $params_arr['fileId'];
                $fileRev = $params_arr['fileRev'];
                $uri_file_path = "https://api-content.dropbox.com/1/files/auto" . $fileId . "?rev=" . $fileRev;
                $fileName = basename($fileId);
                list($s_file_name, $s_extension) = $this->general->get_file_attributes($fileName);

                $temp_folder_path = $this->config->item('admin_upload_temp_path');
                $file_name = $s_file_name . "." . $s_extension;

                $fp = fopen($temp_folder_path . $file_name, "wb");
                $this->csv_import->callCurlGetFile($uri_file_path, $header_params, $fp);
                fclose($fp);

                $data_arr = $this->csv_import->prepareExcelData($temp_folder_path . $file_name, 5);
                $params_arr['docFile'] = $file_name;
                $render_arr['data'] = $data_arr;
            }
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $render_arr['success'] = $success;
        $render_arr['message'] = $message;
        $render_arr['params'] = $params_arr;

        echo $this->parser->parse('import_dropbox_assetlist', $render_arr, TRUE);
    }

    public function save_dropbox_data()
    {
        $params_arr = array();
        $params_arr['docFile'] = $this->input->get_post('docFile', TRUE);
        $params_arr['fileTabId'] = $this->input->get_post('fileTabId', TRUE);
        $access_token = $this->session->userdata('__oauth_api_token');
        try {
            if (!isset($access_token) || trim($access_token) == "") {
                throw new Exception("Dropbox authentication failed.");
            }
            $header_params = array();
            $header_params[] = "Authorization: Bearer " . $access_token;

            $fileTabId = $params_arr['fileTabId'];

            $temp_folder_path = $this->config->item('admin_upload_temp_path');
            $file_name = $params_arr['docFile'];

            $data_arr = $this->csv_import->prepareExcelData($temp_folder_path . $file_name, -1, $fileTabId);
            unlink($temp_folder_path . $file_name);

            if (substr($file_name, -5) == ".xlsx") {
                $file_name = substr_replace($file_name, ".csv", -5, 5);
            } else {
                $file_name = substr_replace($file_name, ".csv", -4, 4);
            }

            $file_path = $temp_folder_path . $file_name;
            $fp2 = fopen($file_path, 'w');
            foreach ($data_arr[0]['rows'] as $v) {
                fputcsv($fp2, $v);
            }
            fclose($fp2);

            $success = 1;
            $message = "Data saved successfully.";
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $ret_arr['success'] = $success;
        $ret_arr['message'] = $message;
        $ret_arr['file_name'] = $file_name;

        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    public function get_weburl_data()
    {
        $web_url = $this->input->get_post('web_url', TRUE);
        $url_type = $this->input->get_post('url_type', TRUE);
        $response = "";
        if ($url_type == "xml") {
            $response = $this->csv_import->callCurlGet($web_url, array(), TRUE);
            $response = json_encode($response);
        } elseif ($url_type == "json") {
            $response = $this->csv_import->callCurlGet($web_url);
        }
        echo $response;
        $this->skip_template_view();
    }

    public function import_valid()
    {
        $module_name = $this->input->get_post('module_name', TRUE);
        $type = $this->input->get_post('type', TRUE);
        $this->config->load('cit_importdata', TRUE);
        $import_modules = $this->config->item('cit_importdata');
        $current_module = $import_modules[$module_name];
        $render_arr['columns'] = $current_module['cols'];
        $render_arr['type'] = $type;
        $this->smarty->assign($render_arr);
        $this->loadView("import_valid");
    }

    public function import_info()
    {
        $import_module_name = $this->input->get_post('module_name', TRUE);
        $import_type = $this->input->get_post('type', TRUE);

        $info = array();
        try {

            $this->config->load('cit_importdata', TRUE);
            $import_modules = $this->config->item('cit_importdata');
            $current_module = $import_modules[$import_module_name];

            if (!is_array($current_module) || count($current_module) == 0) {
                throw new Exception("Import module configuration not found.");
            }

            $import_table_name = $current_module['table'];
            $columns_arr = $current_module['cols'];
            if (!is_array($columns_arr) || count($columns_arr) == 0) {
                throw new Exception("Import module mapping columns not found.");
            }

            $temp_folder_path = $this->config->item('admin_upload_temp_path');
            if ($import_type == 'success') {
                $inserted_file = $this->input->get_post('inserted', TRUE);
                $updated_file = $this->input->get_post('updated', TRUE);
                if (is_file($temp_folder_path . $inserted_file)) {
                    $inserted_json = file_get_contents($temp_folder_path . $inserted_file);
                    $inserted_arr = json_decode($inserted_json, TRUE);
                    $info = array_merge($info, $inserted_arr);
                }
                if (is_file($temp_folder_path . $updated_file)) {
                    $updated_json = file_get_contents($temp_folder_path . $updated_file);
                    $updated_arr = json_decode($updated_json, TRUE);
                    $info = array_merge($info, $updated_arr);
                }
                $heading = "Successful Records";
            } elseif ($import_type == 'failed') {
                $failed_file = $this->input->get_post('failed', TRUE);
                if (is_file($temp_folder_path . $failed_file)) {
                    $failed_json = file_get_contents($temp_folder_path . $failed_file);
                    $failed_arr = json_decode($failed_json, TRUE);
                    $info = array_merge($info, $failed_arr);
                }
                $heading = "Failed Records";
            } elseif ($import_type == 'duplicate') {
                $duplicate_file = $this->input->get_post('duplicate', TRUE);
                if (is_file($temp_folder_path . $duplicate_file)) {
                    $duplicate_json = file_get_contents($temp_folder_path . $duplicate_file);
                    $duplicate_arr = json_decode($duplicate_json, TRUE);
                    $info = array_merge($info, $duplicate_arr);
                }
                $heading = "Duplicate Records";
            } elseif ($import_type == 'skipped') {
                $valid_file = $this->input->get_post('valid', TRUE);
                $lookup_file = $this->input->get_post('lookup', TRUE);
                if (is_file($temp_folder_path . $valid_file)) {
                    $valid_json = file_get_contents($temp_folder_path . $valid_file);
                    $valid_arr = json_decode($valid_json, TRUE);
                    $info = array_merge($info, $valid_arr);
                }
                if (is_file($temp_folder_path . $lookup_file)) {
                    $lookup_json = file_get_contents($temp_folder_path . $lookup_file);
                    $lookup_arr = json_decode($lookup_json, TRUE);
                    $info = array_merge($info, $lookup_arr);
                }
                $heading = "Skipped Records";
            }

            if (!is_array($info) || count($info) == 0) {
                throw new Exception("No data found to show.");
            }
            $header = $columns = array();
            foreach ($info[0] as $key => $val) {
                $col = $columns_arr[$key];
                $title = $this->lang->line($col['name']);
                $header[] = ($title) ? $title : $col['name'];
                $columns[] = $key;
            }
            $success = 1;
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $render_arr = array(
            "success" => $success,
            "message" => $message,
            "columns" => $columns,
            "header" => $header,
            "data" => $info,
            "title" => $heading,
        );

        $this->smarty->assign($render_arr);
        $this->loadView('import_info');
    }

    public function import_history()
    {
        $import_files_url = $this->config->item('import_files_url');
        $import_folder_path = $this->config->item('import_files_path');
        $histroy_file = $import_folder_path . "import_history.json";
        $history_json = file_get_contents($histroy_file);
        $history_data = json_decode($history_json, TRUE);
        $history_data = is_array($history_data) ? array_reverse($history_data) : array();
        $render_arr = array(
            'data' => $history_data,
            'import_files_url' => $import_files_url
        );
        $this->smarty->assign($render_arr);
        $this->loadView('import_history');
    }

    public function media_event()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        $manual = $this->input->get_post('manual', TRUE);
        $media_event_file = $this->input->get_post('media_event', TRUE);
        $temp_folder_path = $this->config->item('admin_upload_temp_path');
        $call_interval = 30 * 1000;
        if (is_file($temp_folder_path . $media_event_file)) {
            $data = file_get_contents($temp_folder_path . $media_event_file);
            $data = json_decode($data, TRUE);
            $send_arr['success'] = 1;
            $send_arr['content'] = $data;
        } else {
            $send_arr['success'] = 0;
            $send_arr['content'] = array();
        }

        if ($manual == "true") {
            $data_arr = array();
            if ($send_arr['success'] == 0) {
                $data_arr['retry'] = intval($call_interval);
            }
            $data_arr['data'] = $send_arr;
            echo json_encode($data_arr);
        } else {
            if ($send_arr['success'] == 0) {
                echo "retry: " . intval($call_interval) . "\n\n";
            }
            echo "data: " . json_encode($send_arr) . "\n\n";
        }
        flush();
        $this->skip_template_view();
    }

    public function media_sample()
    {
        $upload_module = $this->input->get_post('upload_module', TRUE);
        $this->config->load('cit_importdata', TRUE);
        $import_modules = $this->config->item('cit_importdata');
        $current_module = $import_modules[$upload_module];

        $module_name = ($upload_module) ? $upload_module : "media_files";
        $temp_folder_path = $this->config->item('admin_upload_temp_path');
        $zip_folder_name = date("YmdHis") . "-" . rand(1000, 9999) . "-" . rand(1000, 9999);
        $zip_upload_path = $temp_folder_path . $zip_folder_name . DS;
        if (!is_dir($zip_upload_path)) {
            $this->general->createFolder($zip_upload_path);
        }
        $zip_sample_path = $zip_upload_path . $module_name;
        $this->general->createFolder($zip_sample_path);
        if (is_array($current_module) && count($current_module) > 0) {
            $columns_arr = $current_module['cols'];
            $folders_arr = array();
            foreach ($columns_arr as $key => $val) {
                if (isset($current_module['media']) && $current_module['media'] == TRUE) {
                    if (isset($val['upload']) && isset($val['upload']['server'])) {
                        $folder_name = $val['upload']['server'][0];
                        $this->general->createFolder($zip_upload_path . $module_name . DS . $folder_name);
                        $folders_arr[] = $folder_name;
                    }
                }
            }
        }
        if (is_array($folders_arr) && count($folders_arr) <= 1) {
            $zip_sample_file = $folders_arr[0] . ".zip";
            $zip_sample_path = $zip_sample_path . DS . $folders_arr[0];
            $zip_add_path = $folders_arr[0];
        } else {
            $zip_add_path = $module_name;
        }

        require_once($this->config->item('third_party') . 'pclzip/pclzip.lib.php');
        $archive = new PclZip($zip_sample_file);
        $v_list = $archive->create($zip_sample_path, PCLZIP_OPT_REMOVE_PATH, $zip_sample_path, PCLZIP_OPT_ADD_PATH, $zip_add_path);
        if (ob_get_length() > 0) {
            ob_end_clean();
        }
        ob_start();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Cache-Control: private", FALSE);
        header('Content-Disposition: attachment; filename=' . $zip_sample_file);
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($zip_sample_file));
        flush();
        readfile($zip_sample_file);
        unlink($zip_sample_file);
        $this->skip_template_view();
    }
}
