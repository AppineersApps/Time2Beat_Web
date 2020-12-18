<?php  

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Country Model
 * 
 * @category webservice
 *            
 * @package tools
 *
 * @subpackage models
 *
 * @module Country
 * 
 * @class Country_model.php
 * 
 * @path application\webservice\tools\models\Country_model.php
 * 
 * @version 4.4
 *
 * @author CIT Dev Team
 * 
 * @since 18.09.2019
 */
 
class Country_model extends CI_Model
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
     * get_country_list method is used to execute database queries for Country List API.
     * @created  | 28.01.2016
     * @modified ---
     * @return array $return_arr returns response of query block.
     */
    public function get_country_list()
    {
        try {
            $result_arr = array();
                                
            $this->db->from("mod_country AS mc");
            
            $this->db->select("mc.iCountryId AS mc_country_id");
            $this->db->select("mc.vCountry AS mc_country");
            $this->db->select("mc.vCountryCode AS mc_country_code");
            $this->db->select("mc.vCountryCodeISO_3 AS mc_country_code_iso3");
            $this->db->select("mc.eStatus AS mc_status");
            
            $this->db->order_by("mc.vCountry", "asc");
            
            
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
     * get_country_data method is used to execute database queries for Country With States API.
     * @created  | 28.01.2016
     * @modified ---
     * @param string $country_id country_id is used to process query block.
     * @param array $settings_params settings_params are used for paging parameters.
     * @return array $return_arr returns response of query block.
     */
    public function get_country_data($country_id = '', $page_index = 1, &$settings_params = array())
    {
        try {
            $result_arr = array();
                        
            $this->db->start_cache();
            $this->db->from("mod_country AS mc");
            
            if(isset($country_id) && $country_id != ""){ 
                $this->db->where("mc.iCountryId =", $country_id);
            }
            
            $this->db->stop_cache();
            $total_records = $this->db->count_all_results();
            
            $this->db->select("mc.iCountryId AS mc_country_id");
            $this->db->select("mc.vCountry AS mc_country");
            $this->db->select("mc.vCountryCode AS mc_country_code");
            $this->db->select("mc.vCountryCodeISO_3 AS mc_country_code_iso3");
            $this->db->select("mc.eStatus AS mc_status");

            $settings_params['count'] = $total_records;
            
            $record_limit = 10;
            $current_page = intval($page_index) > 0 ? intval($page_index) : 1;
            $total_pages = getTotalPages($total_records, $record_limit);
            $start_index = getStartIndex($total_records, $current_page, $record_limit);
            $settings_params['per_page'] = $record_limit;
            $settings_params['curr_page'] = $current_page;
            $settings_params['prev_page'] = ($current_page > 1) ? 1 : 0;
            $settings_params['next_page'] = ($current_page + 1 > $total_pages) ? 0 : 1;
            
            $this->db->order_by("mc.vCountry", "asc");
            $this->db->limit($record_limit, $start_index);
            $result_obj = $this->db->get();
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            $this->db->flush_cache();
            
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
    
    
}