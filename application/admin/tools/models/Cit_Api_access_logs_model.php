<?php


/**
 * Description of API access logs Extended Model
 * 
 * @module Extended API access logs
 * 
 * @class Cit_Api_access_logs_model.php
 * 
 * @path application\admin\tools\models\Cit_Api_access_logs_model.php
 * 
 * @author CIT Dev Team
 * 
 * @date 28.09.2020
 */
   
Class Cit_Api_access_logs_model extends Api_access_logs_model {
        public function __construct()
{
    parent::__construct();
}

public function get_input_params($id='', $field='')
{
    $this->db->select($field);
    $this->db->where('iAccessLogId',$id);
    $db_rec_obj = $this->db->from('api_accesslogs')->get();
    $result = is_object($db_rec_obj) ? $db_rec_obj->row_array() : array();
    if(!empty($result)){
        return $result;
    }else{
        $result = array();
        return $result;
    }

}
}
