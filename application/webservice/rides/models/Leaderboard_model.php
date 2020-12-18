<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of User review Model
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage models
 *
 * @module User review
 *
 * @class User_review_model.php
 *
 * @path application\webservice\basic_appineers_master\models\User_review_model.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 18.09.2019
 */

class Leaderboard_model extends CI_Model
{
    public $default_lang = 'EN';

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('listing');
        $this->default_lang = $this->general->getLangRequestValue();
    }

   


    /**
     * get_review_details method is used to execute database queries for Post a Feedback API.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param string $review_id review_id is used to process review block.
     * @return array $return_arr returns response of review block.
     */
   public function get_leaderboard_details($arrResult)
    {
        // print_r($arrResult);exit;
        try
        { 
            $result_arr = array();
            if(true == empty($arrResult)){
                return false;
            }
            $strWhere ='';

            $this->db->start_cache();
            $this->db->from("ride AS r");
            $this->db->join("users AS u", "u.iUserId = r.iUserId AND u.eStatus ='Active'", "left");
            $this->db->join("location AS sl", "sl.iLocationId = r.iStartLocId", "left");
            $this->db->join("location AS el", "el.iLocationId = r.iEndLocId", "left");

            
            // if(isset($arrResult['ride_id'])){
            //   $this->db->where_not_in("iRideId",$arrResult['ride_id'] ); 
            // }
            if(isset($arrResult['start_loc_id'])){
              $this->db->where("r.iStartLocId",$arrResult['start_loc_id'] ); 
            }
            if(isset($arrResult['end_loc_id'])){
              $this->db->where("r.iEndLocId",$arrResult['end_loc_id'] ); 
            }
            

            $this->db->select("u.iUserId AS u_user_id");
            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vProfileImage AS u_profile_image");
            $this->db->select("u.tAddress AS u_address");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.dLatitude AS u_latitude");
            $this->db->select("u.dLongitude AS u_longitude");
            $this->db->select("u.vStateName AS state");
            $this->db->select("(".$this->db->escape("").") AS u_friendship_status", FALSE);
            $this->db->select("(".$this->db->escape("").") AS u_block_status", FALSE);
            $this->db->select("(".$this->db->escape("").") AS request_id", FALSE);

            $this->db->select("r.iRideId AS ride_id");
            $this->db->select("r.iUserId AS user_id");
            $this->db->select("r.vNote AS note");
            $this->db->select("r.iStartLocId AS start_loc_id"); 
            $this->db->select("r.iEndLocId AS end_loc_id");
            $this->db->select("r.dtStartDateTime AS start_date_time");
            $this->db->select("r.dtEndDateTime AS end_date_time");
            $this->db->select("r.tEta AS eta"); 
            $this->db->select("r.tRideTime AS ride_time");

            //Start Location Details
            $this->db->select("sl.tAddress AS sl_address"); 
            $this->db->select("sl.vCity AS sl_city");
            $this->db->select("sl.dLatitude AS sl_latitude");
            $this->db->select("sl.dLongitude AS sl_longitude");
            $this->db->select("sl.vState AS sl_state"); 
            $this->db->select("sl.vZipcode AS sl_zipcode");

            //End Location Details
            $this->db->select("el.tAddress AS el_address"); 
            $this->db->select("el.vCity AS el_city");
            $this->db->select("el.dLatitude AS el_latitude");
            $this->db->select("el.dLongitude AS el_longitude");
            $this->db->select("el.vState AS el_state"); 
            $this->db->select("el.vZipcode AS el_zipcode");
            
            $this->db->order_by("r.tRideTime","asc");
            $this->db->limit($record_limit, $start_index);
            $result_obj = $this->db->get();

            // echo $this->db->last_query();exit;
            
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            // print_r(count($result_arr)); exit;
            if (!is_array($result_arr) || count($result_arr) == 0)
            {
                throw new Exception('No records found.');
            }
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->_reset_all();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
      // print_r($return_arr); exit;
        return $return_arr;
    }

  
    

   
}
