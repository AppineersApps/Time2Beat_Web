<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Backup Controller
 *
 * @category admin
 *            
 * @package tools
 * 
 * @subpackage controllers
 * 
 * @module Backup
 * 
 * @class Backup.php
 * 
 * @path application\admin\tools\controllers\Backup.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Backup extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->db->dbdriver != "mysqli") {
            $this->general->forbidden_message("This feature does not supports for this driver.");
        }
        $this->mod_url_cod = array(
            "backup_index",
            "backup_table_backup",
            "backup_backup_form_a",
            "backup_create_backup",
            "backup_backup_delete",
            "backup_backup_download_a"
        );
        $this->mod_enc_url = $this->general->getCustomEncryptURL($this->mod_url_cod, true);
        $this->load->model('backup_model');
        $this->load->library('filter');
    }

    /**
     * index method is used to intialize grid listing page of database backup.
     */
    public function index()
    {
        try {
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                $access_list = array(
                    "backup_view",
                    "backup_add",
                    "backup_delete"
                );
                list($view_access, $add_access, $del_access) = $this->filter->checkAccessCapability($access_list, TRUE);
            } else {
                list($view_access, $add_access, $del_access) = $this->filter->getModuleWiseAccess("UtilitiesBackup", array("View", "Add", "Delete"), FALSE, TRUE);
            }
            if (!$view_access) {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }

            if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                $this->general->trackCustomNavigation('List', 'Viewed', $this->mod_enc_url['backup_index'], $this->db->protect("m.vCapabilityCode") . " = " . $this->db->escape("backup_view"));
            } else {
                $this->general->trackCustomNavigation('List', 'Viewed', $this->mod_enc_url['backup_index'], $this->db->protect("m.vUniqueMenuCode") . " = " . $this->db->escape("UtilitiesBackup"));
            }

            $enc_loc_module = $this->general->getMD5EncryptString("ListPrefer", "backup_full");
            $final_backup_path = $this->config->item('admin_backup_path');
            $backupFiles = $this->backup_model->listOfBackupDirectoryFiles($final_backup_path);
            $finalArr = $this->backup_model->finalListOfBackupFiles($backupFiles, $final_backup_path);
            $finalBackupArr = $finalArr['rows'];
            $finalBackupJSON = json_encode($finalBackupArr);

            $extra_hstr = '';
            if ($this->input->get_post('type') != "") {
                $extra_hstr = "View";
            }
            $render_arr = array(
                'func_name' => 'index',
                'enc_loc_module' => $enc_loc_module,
                'mod_enc_url' => $this->mod_enc_url,
                'extra_hstr' => $extra_hstr,
                'del_access' => $del_access,
                'add_access' => $add_access,
                'view_access' => $view_access,
                'finalBackupJSON' => $finalBackupJSON
            );
            $this->smarty->assign($render_arr);
            $this->loadView('backup');
        } catch (Exception $e) {
            $render_arr['err_message'] = $e->getMessage();
            $this->smarty->assign($render_arr);
            $this->loadView($this->config->item('ADMIN_FORBIDDEN_TEMPLATE'));
        }
    }

    /**
     * index method is used to intialize grid listing page of table wise backup.
     */
    public function table_backup()
    {
        try {
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                $access_list = array(
                    "backup_view",
                    "backup_add",
                    "backup_delete"
                );
                list($view_access, $add_access, $del_access) = $this->filter->checkAccessCapability($access_list, TRUE);
            } else {
                list($view_access, $add_access, $del_access) = $this->filter->getModuleWiseAccess("UtilitiesBackup", array("View", "Add", "Delete"), FALSE, TRUE);
            }
            if (!$view_access) {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }

            if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                $this->general->trackCustomNavigation('List', 'Viewed', $this->mod_enc_url['backup_table_backup'], $this->db->protect("m.vCapabilityCode") . " = " . $this->db->escape("backup_view"));
            } else {
                $this->general->trackCustomNavigation('List', 'Viewed', $this->mod_enc_url['backup_table_backup'], $this->db->protect("m.vUniqueMenuCode") . " = " . $this->db->escape("UtilitiesBackup"));
            }

            $enc_loc_module = $this->general->getMD5EncryptString("ListPrefer", "backup_table");
            $final_backup_path = $this->config->item('admin_backup_path');
            $tableBackupArr = $this->db->list_tables();
            for ($i = 0; $i < count($tableBackupArr); $i++) {
                $tableBackupFinalArr[$i]['id'] = base64_encode($tableBackupArr[$i]);
                $tableBackupFinalArr[$i]['table_name'] = $tableBackupArr[$i];
            }
            $tableBackupJSON = json_encode($tableBackupFinalArr);
            $extra_hstr = '';

            if ($this->input->get_post('type') == "") {
                $extra_hstr = "View";
            }
            $render_arr = array(
                'func_name' => 'table',
                'enc_loc_module' => $enc_loc_module,
                'mod_enc_url' => $this->mod_enc_url,
                'extra_hstr' => $extra_hstr,
                'del_access' => $del_access,
                'add_access' => $add_access,
                'view_access' => $view_access,
                'tableBackupJSON' => $tableBackupJSON
            );
            $this->smarty->assign($render_arr);
            $this->loadView('table_backup');
        } catch (Exception $e) {
            $render_arr['err_message'] = $e->getMessage();
            $this->smarty->assign($render_arr);
            $this->loadView($this->config->item('ADMIN_FORBIDDEN_TEMPLATE'));
        }
    }

    /**
     * backup_form_a method is used to save backup either whole backup or table wise backup.
     */
    public function backup_form_a()
    {
        set_time_limit(0);
        $id_arr = $this->input->get_post('id_arr');
        $id_arr = explode(',', $id_arr);
        for ($i = 0; $i < count($id_arr); $i++) {
            $table_arr[$i] = base64_decode($id_arr[$i]);
        }

        $btype = $this->input->get_post('btype');
        $bname = $this->input->get_post('bname');
        try {
            $saved_file_name = date("YmdHis") . "_Backup.sql";
            $backup_path = $this->config->item('admin_backup_path');
            if (is_array($table_arr) && (count($table_arr) > 0)) {
                $this->create_backup($backup_path, $saved_file_name, $table_arr);
                if ($btype == 'backup_download') {
                    #Download File
                    if (!headers_sent()) {
                        header('Content-type: application/download');
                        header('Content-Disposition: attachment; filename=' . $saved_file_name);
                        readfile($backup_path . $saved_file_name);
                    }
                    exit;
                }
            } else {
                $this->create_backup($backup_path, $saved_file_name);
            }
            if ($error === false) {
                throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_DATABASE_BACKUP_CREATION_C46_C46_C33'));
            }
            if ($btype == "backup_table") {
                $return_url = $this->mod_enc_url['backup_table_backup'];
            } else {
                $return_url = $this->mod_enc_url['backup_index'];
            }
            if ($this->input->get_post('type') == "") {
                $url = $return_url . "|type|View";
            } else {
                $url = $return_url;
            }

            if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                $this->general->trackCustomNavigation('List', 'Added', $return_url, $this->db->protect("m.vCapabilityCode") . " = " . $this->db->escape("backup_view"));
            } else {
                $this->general->trackCustomNavigation('List', 'Added', $return_url, $this->db->protect("m.vUniqueMenuCode") . " = " . $this->db->escape("UtilitiesBackup"));
            }

            $retArr['return_url'] = $url;
            $retArr['message'] = $this->general->processMessageLabel('ACTION_DATABASE_BACKUP_CREATED_SUCCESSFULLY_C46_C46_C33');
            $retArr['success'] = 1;
        } catch (Exception $e) {
            $msg = $e->getMessage();
            $retArr['message'] = $msg;
            $retArr['success'] = 0;
        }
        echo json_encode($retArr);
        $this->skip_template_view();
    }

    /**
     * create_backup method is used to create backup through backup utility library.
     */
    public function create_backup($file_path = '', $file_name = '', $back_tbls = array(), $ignore_tbls = array())
    {
        $this->load->library('ci_backup');
        $btype = $this->input->get_post('btype');
        $this->ci_backup->setFileLocations(array("file_path" => $file_path, "file_name" => $file_name));
        $this->ci_backup->setKeepDropTable(true);
        $this->ci_backup->setAllowTables(true);
        $this->ci_backup->setAllowViews(true);
        $this->ci_backup->setAllowFunctions(true);
        $this->ci_backup->setAllowProcedures(true);
        $this->ci_backup->setAllowTriggers(true);
        $this->ci_backup->setAllowEvents(true);
        if ($btype == "backup_download" || $btype == "backup_table") {
            $this->ci_backup->setDatabaseTables($back_tbls);
        }
        $this->ci_backup->createDBDump();
//        $this->load->dbutil();        
//        if ($btype == "backup_download" || $btype == "backup_table") {
//            $prefs = array(
//                'tables' => $back_tbls, // Array of tables to backup.
//                'ignore' => $ignore_tbls, // List of tables to omit from the backup
//                'format' => 'txt', // gzip, zip, txt
//                'add_drop' => TRUE, // Whether to add DROP TABLE statements to backup file
//                'add_insert' => TRUE, // Whether to add INSERT data to backup file
//                'newline' => "\n" // Newline character used in backup file
//            );
//        } else {
//            $prefs = array(
//                'ignore' => $ignore_tbls, // List of tables to omit from the backup
//                'format' => 'txt', // gzip, zip, txt
//                'add_drop' => TRUE, // Whether to add DROP TABLE statements to backup file
//                'add_insert' => TRUE, // Whether to add INSERT data to backup file
//                'newline' => "\n" // Newline character used in backup file
//            );
//        }
//        $backup = $this->dbutil->backup($prefs);
        return $file_name;
    }

    /**
     * backup_delete method is used to delete specific backup file from saved directories.
     */
    public function backup_delete()
    {
        $operartor = $this->input->get_post('oper');
        $file_name = $this->input->get_post('id');
        $file_name_arr = array_filter(explode(",", $file_name));
        try {
            if (!is_array($file_name_arr) || count($file_name_arr) == 0) {
                $error_msg = "No files found";
                throw new Exception($error_msg);
            }
            if ($operartor == 'del') {
                if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                    $del_access = $this->filter->checkAccessCapability("backup_delete", TRUE);
                } else {
                    $del_access = $this->filter->getModuleWiseAccess("UtilitiesBackup", "Delete", FALSE, TRUE);
                }
                if (!$del_access) {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_DELETE_THESE_DETAILS_C46_C46_C33'));
                }

                foreach ((array) $file_name_arr as $fileKey => $fileVal) {
                    $file_path = $this->config->item('admin_backup_path') . base64_decode($fileVal);
                    if (is_file($file_path)) {
                        $res = unlink($file_path);
                    }
                }
                if (!$res) {
                    $error_msg = $this->general->processMessageLabel('ACTION_FAILURE_IN_DELETION_OF_BACKUP_FILE_C46_C46_C33');
                    throw new Exception($error_msg);
                }

                if (!$this->input->get_post('type')) {
                    $url = $this->mod_enc_url['backup_index'] . "|type|View";
                } else {
                    $url = $this->mod_enc_url['backup_index'];
                }

                $retArr['return_url'] = $url;
                $retArr['success'] = "true";
                $retArr['message'] = $this->general->processMessageLabel('ACTION_RECORDS_DELETED_SUCCESSFULLY_C46_C46_C33');

                if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                    $this->general->trackCustomNavigation('List', 'Deleted', $this->mod_enc_url['backup_index'], $this->db->protect("m.vCapabilityCode") . " = " . $this->db->escape("backup_view"));
                } else {
                    $this->general->trackCustomNavigation('List', 'Deleted', $this->mod_enc_url['backup_index'], $this->db->protect("m.vUniqueMenuCode") . " = " . $this->db->escape("UtilitiesBackup"));
                }
            }
        } catch (Exception $e) {
            $msg = $e->getMessage();
            $retArr['success'] = "false";
            $retArr['message'] = $msg;
        }
        echo json_encode($retArr);
        $this->skip_template_view();
    }

    /**
     * backup_download_a method is used to download specific backup file from saved directories.
     */
    public function backup_download_a()
    {
        $enc_fname = $this->input->get_post('fname');
        $fname = base64_decode($enc_fname);
        #Download File
        if (!headers_sent()) {
            $file_path = $this->config->item('admin_backup_path') . $fname;
            header('Content-type: text/x-sql; charset=utf-8');
            header('Content-Transfer-Encoding: binary');
            header('Content-Disposition: attachment; filename=' . $fname);
            readfile($file_path);
        }
        exit;
    }
}
