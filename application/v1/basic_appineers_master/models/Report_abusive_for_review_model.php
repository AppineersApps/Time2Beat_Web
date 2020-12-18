<?php  

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Abusive Reports For reviews Model
 * 
 * @category webservice
 *            
 * @package misc
 *
 * @subpackage models
 *
 * @module Abusive Reports For reviews
 * 
 * @class Abusive_reports_for_posts_model.php
 * 
 * @path application\webservice\misc\models\Abusive_reports_for_posts_model.php
 * 
 * @version 4.4
 *
 * @author CIT Dev Team
 * 
 * @since 03.05.2019
 */
 
class Report_abusive_for_review_model extends CI_Model
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
     * post_report_abusive method is used to execute database queries for Report Abusive For Post API.
     * @created priyanka chillakuru | 02.05.2019
     * @modified priyanka chillakuru | 02.05.2019
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function insert_report($params_arr = array())
    {

        try {
            $result_arr = array();
                        
            if(!is_array($params_arr) || count($params_arr) == 0){
                throw new Exception("Insert data not found.");
            }
            
            if(isset($params_arr["user_id"])){
                $this->db->set("iReportedBy", $params_arr["user_id"]);
            }
            if(isset($params_arr["review_id"])){
                $this->db->set("iReviewId", $params_arr["review_id"]);
            }
            if(isset($params_arr["message"])){
                $this->db->set("vMessage", $params_arr["message"]);
            }
            $this->db->set($this->db->protect("dtAddedAt"), $params_arr["_dtaddedat"], FALSE);
            $this->db->insert("abusive_reports_for_reviews");
            $insert_id = $this->db->insert_id();
            if(!$insert_id){
                 throw new Exception("Failure in insertion.");
            }
            $result_param = "insert_id";
            $result_arr[0][$result_param] = $insert_id;
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