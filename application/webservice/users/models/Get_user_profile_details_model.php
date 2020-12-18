<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Users Model
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage models
 *
 * @module Users
 *
 * @class Users_model.php
 *
 * @path application\webservice\basic_appineers_master\models\Users_model.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 12.02.2020
 */

class Get_user_profile_details_model extends CI_Model
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
     * get_updated_details method is used to execute database queries for Edit Profile API.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param string $user_id user_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user_profile_details($other_user_id='',$user_id = '')
    {
        // print_r($user_id);exit;
        try
        {

            $result_arr = array();
            $this->db->from("users AS u");
           // $this->db->join("blocked_user AS bu", "bu.iBlockedTo = u.iUserId AND bu.iBlockedBy ='".$user_id."'", "left");

           // $this->db->from("users AS u");

            $this->db->select("u.iUserId AS u_user_id");
            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vProfileImage AS u_profile_image");
            $this->db->select("u.tAddress AS u_address");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("u.dLatitude AS u_latitude");
            $this->db->select("u.dLongitude AS u_longitude");
            $this->db->select("u.vStateName AS u_state");
            if(!empty($other_user_id))
            { 
                $this->db->select("(SELECT count(*) as count FROM ride as r where r.iUserId = u.iUserId AND r.iUserId='".$other_user_id."') AS total_rides", FALSE);
                $this->db->select("(SELECT count(*) as count FROM friends as f where 
                    (
                    (f.iUserId = u.iUserId AND f.iUserId='".$other_user_id."')
                     or (f.iFriendUserId = '".$other_user_id."' AND f.iFriendUserId=u.iUserId)
                    )
                    and f.eStatus in('Active') AND f.eFriendshipStatus in('Accepted')) AS total_friends", FALSE);
            }
            else{
                $this->db->select("(SELECT count(*) as count FROM ride as r where r.iUserId = u.iUserId AND r.iUserId='".$user_id."') AS total_rides", FALSE);
                $this->db->select("(SELECT count(*) as count FROM friends as f where ((f.iUserId = u.iUserId AND f.iUserId='".$user_id."') or (f.iFriendUserId = '".$user_id."' AND f.iFriendUserId=u.iUserId) )and f.eStatus in('Active') AND f.eFriendshipStatus in('Accepted')) AS total_friends", FALSE);
            }
            $this->db->select("(".$this->db->escape("").") AS request_id", FALSE);
            $this->db->select("(".$this->db->escape("").") AS u_friendship_status", FALSE);
            $this->db->select("(".$this->db->escape("").") AS u_block_status", FALSE);

            $this->db->select("(".$this->db->escape("").") AS u_ride_history", FALSE);
            
            if(!empty($other_user_id))
            { 
                $this->db->where("u.iUserId =", $other_user_id);
            }
            else
            {
                $this->db->where("u.iUserId =", $user_id);   
            }
            $this->db->limit(1);

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
        //echo $this->db->last_query();exit;
        //print_r($result_arr);exit;
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }


   
    public function get_users_block_details($user_id = '',$other_user_id='')
    {
         try
        {

            $result_arr = array();
           
               $strSql="SELECT 
                        CASE WHEN iBlockedTo= ".$other_user_id." AND iBlockedBy=".$user_id." THEN 'BLOCKED_BY_ME'
                            WHEN iBlockedTo= ".$user_id." AND iBlockedBy=".$other_user_id." THEN 'BLOCKED_BY_OTHER_USER'
                            ELSE 'NO_BLOCK' 
                            END AS u_block_status
       
                        FROM blocked_user WHERE (iBlockedTo = ".$other_user_id." AND iBlockedBy = ".$user_id.") OR 
                        (iBlockedTo = ".$user_id." AND iBlockedBy = ".$other_user_id.")  LIMIT 1 ";

                $result_obj =  $this->db->query($strSql);
           

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
        // echo $this->db->last_query();exit;
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }



    public function get_friend_request_details($user_id = '',$other_user_id='')
    {
         try
        {

            $result_arr = array();
           
               $strSql="SELECT 
                        iFriendListId AS request_id
                        FROM friends 
                        WHERE (iUserId = '".$user_id."' AND iFriendUserId = '".$other_user_id."') OR
                        (iFriendUserId = '".$user_id."' AND iUserId = '".$other_user_id."')
                        LIMIT 1";

                $result_obj =  $this->db->query($strSql);
           

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
        // echo $this->db->last_query();exit;
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

}
