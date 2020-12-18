<?php

   
/**
 * Description of delete_api_log Extended Controller
 *
 * @category webservice
 *
 * @package misc
 *
 * @subpackage controllers
 *
 * @module Extended delete_api_log
 *
 * @class Cit_Delete_api_log.php
 *
 * @path application\webservice\misc\controllers\Cit_Delete_api_log.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 29.09.2020
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Delete_api_log extends Delete_api_log {
        public function __construct()
{
    parent::__construct();
}
public function fetchDays($input_params = array())
{
    if($this->config->item('DELETE_API_LOG_DAYS')){
        $return_arr[0]['days'] = $this->config->item('DELETE_API_LOG_DAYS');
    }else{
        $return_arr[0]['days'] = "30";
    }
    return $return_arr;
}
public function deleteLogFiles($input_params = array())
{
    $access_log_folder = $this->config->item('admin_access_log_path');
    $org_path = $access_log_folder . "api_logs" . DS;

    if(!empty($input_params['fetch_log_files'])){
        foreach ($input_params['fetch_log_files'] as $value){
            $file = $org_path . $value['aa_file_name'];
            unlink($file);
        }
    }
}
}
