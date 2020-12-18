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

class Friends_model extends CI_Model
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
    public function set_friend_request($params_arr = array())
    {
        // print_r($params_arr);exit;
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
            if (isset($params_arr["other_user_id"]))
            {
                $this->db->set("iFriendUserId", $params_arr["other_user_id"]);
            }
            if (isset($params_arr["timestamp"]))
            {
                $this->db->set("dtAddedAt", $params_arr["timestamp"]);
            } 
            $this->db->set("eFriendshipStatus", $params_arr["_estatus"]);
            $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
        

            
            $this->db->insert("friends");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "friend_id";
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


    public function update_request_status($params_arr = array(), $where_arr = array())
    {
        // print_r($where_arr);exit;
        try
        {
            $result_arr = array();
            $this->db->start_cache();
            if (isset($where_arr["request_id"]) && $where_arr["request_id"] != "")
            {
                $this->db->where("iFriendListId =", $where_arr["request_id"]);
            }
            $this->db->where_in("eStatus", array('Active'));
            $this->db->stop_cache();
            if (isset($params_arr["_eFriendshipstatus"]))
            {
                $this->db->set("eFriendshipStatus", $params_arr["_eFriendshipstatus"]);
            }
            $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
          
            $res = $this->db->update("friends");
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



    /**
     * get_review_details method is used to execute database queries for Post a Feedback API.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param string $review_id review_id is used to process review block.
     * @return array $return_arr returns response of review block.
     */

   public function get_user_details($arrResult)
    {
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
            $this->db->where("r.iUserId",$arrResult['other_user_id']);
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
 

   public function get_friend_details($arrResult)
    {
        // print_r($arrResult);exit;
        try
        { 
            $result_arr = array();
            if(true == empty($arrResult)){
                return false;
            }
            $strWhere ='';
            //search all my friends
            if(isset($arrResult['my_friend_search']) && $arrResult['my_friend_search'] == '1'){
                $this->db->start_cache();
                $this->db->from("friends AS f");
                $this->db->join("users AS u", "(u.iUserId = f.iFriendUserId AND f.iUserId = '".$arrResult['user_id']."') OR (u.iUserId = f.iUserId AND f.iFriendUserId = '".$arrResult['user_id']."') AND f.eFriendshipStatus = 'Accepted'","left");

                if(isset($arrResult['user_id'])){
                  $this->db->where_not_in("u.iUserId",$arrResult['user_id'] ); 
                }                    
                if(isset($arrResult['search_text'])){
                  $this->db->where("((u.vFirstName like '%".$arrResult['search_text']."%') OR (u.vLastName like '%".$arrResult['search_text']."%'))"); 
                }                    
                $this->db->where("f.eFriendshipStatus","Accepted" );
                $this->db->stop_cache();
   
            }
            else if (isset($arrResult['my_friend_search']) && ($arrResult['my_friend_search'] == '0')){

                $this->db->start_cache();
                $this->db->from("users AS u");

                if(isset($arrResult['user_id'])){
                  $this->db->where_not_in("u.iUserId",$arrResult['user_id'] ); 
                }                    
                if(isset($arrResult['search_text'])){
                  $this->db->where("((u.vFirstName like '%".$arrResult['search_text']."%') OR (u.vLastName like '%".$arrResult['search_text']."%'))"); 
                }                    
                $this->db->stop_cache();
                       
            }        

            if(isset($arrResult['my_friend_search']) && $arrResult['my_friend_search'] == '1'){

            }            
            $this->db->select("u.iUserId AS u_user_id");
            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.tAddress AS u_address");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.vStateName AS u_state");
            $this->db->select("u.vProfileImage AS u_profile_image");
            $this->db->select("(".$this->db->escape("").") AS u_friendship_status", FALSE);
            $this->db->select("(".$this->db->escape("").") AS request_id", FALSE);
            
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


     public function get_users_friendship_details($user_id = '',$other_user_id='')
    {
         try
        {

            $result_arr = array();
           
               $strSql=
               "SELECT 
                        CASE WHEN iUserId = '".$user_id."' AND iFriendUserId = '".$other_user_id."' AND eFriendshipStatus= 'Pending' THEN 'REQUEST_SEND_BY_ME'
                            WHEN iUserId = '".$other_user_id."' AND iFriendUserId = '".$user_id."' AND eFriendshipStatus= 'Pending' THEN 'REQUEST_RECEIVED'
                             WHEN eFriendshipStatus= 'Accepted' THEN 'FRIEND'
                             -- WHEN eFriendshipStatus= 'Declined' THEN 'REQUEST_DECLINED'
                             ELSE 'NO_FRIEND' 
                            END AS u_friendship_status
       
                        FROM friends 
                        WHERE (iUserId = '".$user_id."' AND iFriendUserId = '".$other_user_id."') OR
                        (iFriendUserId = '".$user_id."' AND iUserId = '".$other_user_id."')
                        LIMIT 1";



                $result_obj =  $this->db->query($strSql);
            
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            // print_r($other_user_id);exit;
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
       public function delete_request($params_arr = array())
    {
       // print_r($params_arr); exit;
       try
        {
            $result_arr = array();
            $this->db->start_cache();
            if (isset($params_arr["request_id"]))
            {
                $this->db->where("iFriendListId =", $params_arr["request_id"]);
            }
            $this->db->stop_cache();
           
            $res = $this->db->delete("friends");

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
