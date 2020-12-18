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

class Shared_rides_model extends CI_Model
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
     * post_a_feedback method is used to execute database queries for Post a Feedback API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $params_arr params_arr array to process review block.
     * @return array $return_arr returns response of review block.
     */
    public function set_shared_ride($params_arr = array())
    {
        try
        {
            $result_arr = $insert_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }
            foreach($params_arr["other_user_ids"] as $key=>$value){
                $insert_arr[$key]['iRideId'] = $params_arr["ride_id"];
                $insert_arr[$key]['dtAddedAt'] = $params_arr["_dtaddedat"];
                $insert_arr[$key]['eStatus'] = $params_arr["_estatus"];
                $insert_arr[$key]['iFriendUserId'] = $value;

            }
            if(is_array($insert_arr) && !empty($insert_arr))
            {
                $this->db->insert_batch("shared_rides",$insert_arr);
            }
            $insert_id = $this->db->insert_id();

            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "shared_ride_id";
            $result_arr[0][$result_param] = $insert_id;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->_reset_all();
        // echo $this->db->last_query();exit;
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * update_shared_ride method is used to execute database queries for Edit Profile API.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    
    public function update_shared_ride($params_arr = array(), $friend_id = array())
    {
        try
        {
            $result_arr = array();
            $this->db->start_cache();
            if (isset($params_arr["share_ride_id"]) && $params_arr["share_ride_id"] != "")
            {
                $this->db->where("iShareRideId =", $params_arr["share_ride_id"]);
            }
            $this->db->stop_cache();
            if (isset($params_arr["dtAddedAt"]))
            {
                $this->db->set("dtAddedAt", $params_arr["dtAddedAt"]);
            }
            
            $res = $this->db->update("shared_rides"); 
           // echo $this->db->last_query();exit;
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;

        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    public function get_user_details($arrResult)
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
            $this->db->from("users AS s");
            $this->db->from("users AS r");
            $this->db->where("s.iUserId",$arrResult['user_id']);
            $this->db->where_in("r.iUserId",$arrResult['other_user_ids']);
            $this->db->stop_cache();

            
            $this->db->select("s.iUserId AS s_user_id");
            $this->db->select("s.vFirstName AS s_first_name");
            $this->db->select("s.vLastName AS s_last_name");
            $this->db->select("(concat(s.vFirstName,' ',s.vLastName)) AS s_name", FALSE);
            $this->db->select("s.vCity AS s_city");
            $this->db->select("s.vProfileImage AS s_profile_image");
            $this->db->select("r.iUserId AS r_user_id");
            $this->db->select("r.vFirstName AS r_first_name");
            $this->db->select("r.vLastName AS r_last_name");
            $this->db->select("(concat(r.vFirstName,' ',r.vLastName)) AS r_name", FALSE);
            $this->db->select("r.vProfileImage AS r_profile_image");
            $this->db->select("r.vDeviceToken AS r_device_token");
            
            $this->db->limit($record_limit, $start_index);
            $result_obj = $this->db->get();

           // echo $this->db->last_query();exit;
            
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
          
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
      // print_r($return_arr["data"]); exit;
        return $return_arr;
    }

/**
     * get_review_details method is used to execute database queries for Post a Feedback API.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param string $review_id review_id is used to process review block.
     * @return array $return_arr returns response of review block.
     */
   public function get_shared_ride_user_details($ride_id)
    {
        // print_r($ride_id);exit;
        try
        { 
            $result_arr = array();
            if(true == empty($ride_id)){
                return false;
            }
            $strWhere ='';

            $this->db->start_cache();
            $this->db->from("shared_rides AS sr");
            $this->db->join("users AS u", "u.iUserId = sr.iFriendUserId AND u.eStatus ='Active'", "left");
            
            if(isset($ride_id)){
              $this->db->where("sr.iRideId",$ride_id ); 
            }
            
            $this->db->select("sr.iShareRideId AS shared_ride_id");
            //user details
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
            
            $this->db->limit($record_limit, $start_index);
            $result_obj = $this->db->get();

            // echo $this->db->last_query();exit;
            
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
          
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
      // print_r($return_arr["data"]); exit;
        return $return_arr;
    }

 

   /**
     * get_review_details method is used to execute database queries for Post a Feedback API.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param string $review_id review_id is used to process review block.
     * @return array $return_arr returns response of review block.
     */
   public function get_ride_share_by_me_details($arrResult)
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
            $this->db->from("shared_rides AS sr");
            $this->db->join("ride AS r", "sr.iRideId = r.iRideId", "left");
            $this->db->join("users AS u", "u.iUserId = r.iUserId AND u.eStatus ='Active'", "left");
            $this->db->join("location AS sl", "sl.iLocationId = r.iStartLocId", "left");
            $this->db->join("location AS el", "el.iLocationId = r.iEndLocId", "left");
            


            if(isset($arrResult['ride_id'])){
              $this->db->where("r.iRideId",$arrResult['ride_id'] ); 
            }            
            else if(isset($arrResult['user_id'])){
              $this->db->where("r.iUserId",$arrResult['user_id'] ); 
            }
            
            $this->db->select("sr.iShareRideId AS shared_ride_id");
            $this->db->select("(".$this->db->escape("").") AS u_user_details", FALSE);

            $this->db->select("sr.iShareRideId AS shared_ride_id");
            //user details
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
            $this->db->select("(".$this->db->escape("").") AS u_block_status", FALSE);

            //ride details
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

             $this->db->select("(SELECT MIN(tRideTime) FROM ride WHERE iStartLocId = r.iStartLocId AND iEndLocId = r.iEndLocId) AS best_time");
            
            $this->db->order_by("sr.iShareRideId","desc");
            $this->db->group_by("r.iRideId","desc");

            $this->db->limit($record_limit, $start_index);
            $result_obj = $this->db->get();

            //echo $this->db->last_query();exit; 
            
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
          
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
      // print_r($return_arr["data"]); exit;
        return $return_arr;
    }

 
    /**
     * get_review_details method is used to execute database queries for Post a Feedback API.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param string $review_id review_id is used to process review block.
     * @return array $return_arr returns response of review block.
     */
   public function get_ride_share_details($arrResult)
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
            $this->db->from("shared_rides AS sr");
            $this->db->join("ride AS r", "r.iRideId = sr.iRideId", "left");
            $this->db->join("users AS u", "u.iUserId = r.iUserId AND u.eStatus ='Active'", "left");
            $this->db->join("location AS sl", "sl.iLocationId = r.iStartLocId", "left");
            $this->db->join("location AS el", "el.iLocationId = r.iEndLocId", "left");
            


            if(isset($arrResult['other_user_id'])){
              $this->db->where("sr.iFriendUserId",$arrResult['other_user_id'] ); 
            }            
            else if(isset($arrResult['user_id'])){
              $this->db->where("sr.iFriendUserId",$arrResult['user_id'] ); 
            }
            
            $this->db->select("sr.iShareRideId AS shared_ride_id");
            //user details
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
            $this->db->select("(".$this->db->escape("").") AS u_block_status", FALSE);

            //ride details
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
             $this->db->select("(SELECT MIN(tRideTime) FROM ride WHERE iStartLocId = r.iStartLocId AND iEndLocId = r.iEndLocId) AS best_time");
            
            $this->db->order_by("r.dtStartDateTime","desc");
            $this->db->limit($record_limit, $start_index);
            $result_obj = $this->db->get();

            //echo $this->db->last_query();exit;
            
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
          
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
      // print_r($return_arr["data"]); exit;
        return $return_arr;
    }
 /**
     * get_review_details method is used to execute database queries for Post a Feedback API.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param string $review_id review_id is used to process review block.
     * @return array $return_arr returns response of review block.
     */

   public function get_ride_details($arrResult)
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

            
            if(isset($arrResult['ride_id'])){
              $this->db->where("iRideId",$arrResult['ride_id'] ); 
            }

            $this->db->select("(".$this->db->escape("").") AS u_user_details", FALSE);

            //$this->db->select("sr.iShareRideId AS shared_ride_id");
            //user details
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
            $this->db->select("(".$this->db->escape("").") AS u_block_status", FALSE);
            
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
            $this->db->select("(SELECT MIN(tRideTime) FROM ride WHERE iStartLocId = r.iStartLocId AND iEndLocId = r.iEndLocId) AS best_time"); 
            
            $this->db->order_by("r.dtStartDateTime","desc");
            $this->db->limit($record_limit, $start_index);
            $result_obj = $this->db->get();

           //echo $this->db->last_query();exit;
            
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
          
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
      // print_r($return_arr["data"]); exit;
        return $return_arr;
    }
  

    

   
}
