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

class Rides_model extends CI_Model
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
    public function set_ride($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }
            if (isset($params_arr["user_id"]))
            {
                $this->db->set("iUserId", $params_arr["user_id"]);
            }
            if (isset($params_arr["notes"]))
            {
                $this->db->set("vNote", $params_arr["notes"]);
            }
            if (isset($params_arr["start_location_id"]))
            {
                $this->db->set("iStartLocId", $params_arr["start_location_id"]);
            } 
            if (isset($params_arr["end_location_id"]))
            {
                $this->db->set("iEndLocId", $params_arr["end_location_id"]);
            } 
            if (isset($params_arr["start_time"]))
            {
                $this->db->set("dtStartDateTime", $params_arr["start_time"]);
            } 
            if (isset($params_arr["end_time"]))
            {
                $this->db->set("dtEndDateTime", $params_arr["end_time"]);
            } 
            if (isset($params_arr["eta"]))
            {
                $this->db->set("tEta", $params_arr["eta"]);
            } 
            if (isset($params_arr["ride_time"]))
            {
                $this->db->set("tRideTime", $params_arr["ride_time"]);
            } 
            if (isset($params_arr["distance_bias"]))
            {
                $this->db->set("iDistanceBias", $params_arr["distance_bias"]);
            } 

            
            $this->db->insert("ride");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "ride_id";
            $result_arr[0][$result_param] = $insert_id;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->_reset_all();
//         echo $this->db->last_query();exit;
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
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
            if(isset($arrResult['other_user_id'])){
              $this->db->where("r.iUserId",$arrResult['other_user_id'] ); 
            }                    
            else if(isset($arrResult['user_id']) && true == empty($arrResult['radius'])){
              $this->db->where("r.iUserId",$arrResult['user_id'] ); 
            } 
            if (isset($arrResult['radius']) && $arrResult['radius'] != "")
            {
                $this->db->where("FLOOR(".$arrResult['distance'].") <=", $arrResult['radius']); 
            }
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
            
            $this->db->order_by("r.dtStartDateTime","desc");
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
     * update_profile method is used to execute database queries for Edit Profile API.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    
    public function update_ride($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            $this->db->start_cache();
            if (isset($where_arr["ride_id"]) && $where_arr["ride_id"] != "")
            {
                $this->db->where("iRideId =", $where_arr["ride_id"]);
            }
            $this->db->stop_cache();
            if (isset($params_arr["notes"]))
            {
                $this->db->set("vNote", $params_arr["notes"]);
            }
            
            $res = $this->db->update("ride");
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

       public function delete_ride($params_arr = array())
    {
       // print_r($params_arr); exit;
       try
        {
            $result_arr = array();
            $this->db->start_cache();
            if (isset($params_arr["ride_id"]))
            {
                $this->db->where("iRideId =", $params_arr["ride_id"]);
            }
            $this->db->stop_cache();
           
            $res = $this->db->delete("shared_rides");

            if (isset($params_arr["ride_id"]))
            {
                $this->db->where("iRideId =", $params_arr["ride_id"]);
            }
            $this->db->stop_cache();
           
            $res = $this->db->delete("ride");

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

    

   
}
