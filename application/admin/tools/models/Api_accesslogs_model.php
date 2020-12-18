<?php  

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Api Accesslogs Model
 * 
 * @category webservice
 *            
 * @package tools
 *
 * @subpackage models
 *
 * @module Api Accesslogs
 * 
 * @class Api_accesslogs_model.php
 * 
 * @path application\webservice\tools\models\Api_accesslogs_model.php
 * 
 * @version 4.4
 *
 * @author CIT Dev Team
 * 
 * @since 29.09.2020
 */
 
class Api_accesslogs_model extends CI_Model
{
    public $default_lang = 'EN';
    
    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper('listing');
        $this->default_lang = $this->general->getLangRequestValue();
    }
    
    /**
     * fetch_log_files method is used to execute database queries for delete_api_log API.
     * @created Devangi Nirmal | 29.09.2020
     * @modified Devangi Nirmal | 29.09.2020
     * @param string $days days is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function fetch_log_files($days = '')
    {
        try {
            $result_arr = array();
                                
            $this->db->from("api_accesslogs AS aa");
            
            $this->db->select("aa.vFileName AS aa_file_name");
            $this->db->where("DATE_FORMAT(dAccessDate,\"%Y-%m-%d\") < NOW() - INTERVAL ".$days." DAY;", FALSE, FALSE);
            
            
            
            $result_obj = $this->db->get();
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            
            if(!is_array($result_arr) || count($result_arr) == 0){
                throw new Exception('No records found.');
            }
            $success = 1;
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }
    
    
    /**
     * delete_old_api_log_data method is used to execute database queries for delete_api_log API.
     * @created Devangi Nirmal | 29.09.2020
     * @modified Devangi Nirmal | 29.09.2020
     * @param string $days days is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function delete_old_api_log_data($days = '')
    {
        try {
            $result_arr = array();
                                
            
            $this->db->where("DATE_FORMAT(dAccessDate,\"%Y-%m-%d\") < NOW() - INTERVAL ".$days." DAY;", FALSE, FALSE);
            $res = $this->db->delete("api_accesslogs");
            if(!$res){
                 throw new Exception("Failure in deletion.");
            }
            $affected_rows = $this->db->affected_rows();
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }
    
    
}