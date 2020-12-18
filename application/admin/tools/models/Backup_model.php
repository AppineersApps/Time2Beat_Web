<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Backup Model
 *
 * @category admin
 * 
 * @package tools
 *  
 * @subpackage models
 * 
 * @module Backup
 * 
 * @class Backup_model.php
 * 
 * @path application\admin\tools\models\Backup_model.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Backup_model extends CI_Model
{

    public $main_table = "";

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * listOfBackupDirectoryFiles method is used to get all backup files.
     * @return array $list_file_names returns backup list data records array.
     */
    public function listOfBackupDirectoryFiles($file = '')
    {
        $list_file_names = array();
        if (is_dir($file)) {
            $handle = opendir($file);
            while (false !== ($file1 = readdir($handle))) {
                $files[] = $file1;
            }
            natcasesort($files);
            reset($files);
            foreach ($files as $filename) {
                if ($filename == "." || $filename == "..") {
                    continue;
                }
                $list_file_names[] = $filename;
            }
        }
        closedir($handle);
        return $list_file_names;
    }

    /**
     * finalListOfBackupFiles method is used to get final list of backup files.
     * @return array $ret_files returns backup list final data records array.
     */
    public function finalListOfBackupFiles($backup_files = array(), $final_backup_path = '')
    {
        $db_total_files = array();
        $totla_size = 0;
        if (is_array($backup_files) && count($backup_files) > 0) {
            foreach ((array) $backup_files as $filekey => $fileval) {
                $filePath = $final_backup_path . $fileval;
                $idStr = base64_encode($fileval);
                if (is_file($filePath)) {
                    $fileArr = explode("_", $fileval);
                    $key_date = date("YmdHis", strtotime($fileArr[0]));
                    $created_date = date("M d, Y h:i A", strtotime($fileArr[0]));
                    $month_date = date("F, Y", strtotime($key_date));
                    $fileSize = (filesize($filePath) / 1024);
                    $fileSize = round($fileSize, 2);
                    $db_total_files[$key_date]['id'] = $idStr;
                    $db_total_files[$key_date]['data_base_file'] = $fileval;
                    $db_total_files[$key_date]['created_date'] = $created_date;
                    $db_total_files[$key_date]['download'] = $idStr;
                    $db_total_files[$key_date]['data_size'] = $fileSize;
                    $db_total_files[$key_date]['month'] = $month_date;
                    $totla_size += $fileSize;
                }
            }
        }
        krsort($db_total_files);
        $db_total_files = array_values($db_total_files);

        return array(
            'rows' => $db_total_files,
            'total' => $totla_size
        );
    }
}
