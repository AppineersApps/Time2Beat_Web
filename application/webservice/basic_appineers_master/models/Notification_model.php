<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Notification Model
 *
 * @category webservice
 *
 * @package notifications
 *
 * @subpackage models
 *
 * @module Notification
 *
 * @class Notification_model.php
 *
 * @path application\webservice\notifications\models\Notification_model.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 31.07.2019
 */

class Notification_model extends CI_Model
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

    public function get_notification_details($user_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->start_cache();
            $this->db->from("notification AS n");
            $this->db->join("users AS u", "n.iSenderId = u.iUserId", "left");
            $this->db->select("n.iNotificationId AS notification_id");

            $this->db->select("n.iFriendReqId  AS request_id");
            $this->db->select("n.vNotificationType  AS notification_type");
            $this->db->select("n.vNotificationMessage AS message");
             $this->db->select("u.iUserId AS notification_user_id");
            $this->db->select("concat(u.vFirstName,' ',u.vLastName) AS user_name");
            $this->db->select("u.vProfileImage AS user_image");
            $this->db->select("u.eStatus AS user_status");
            if (isset($user_id) && $user_id != "")
            {
                $this->db->where("n.iReceiverId =", $user_id);
            }

            $this->db->stop_cache();

            $this->db->order_by("n.dtUpdatedAt", "desc");
            $result_obj = $this->db->get();
            //echo $this->db->last_query();exit;
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            $this->db->flush_cache();
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
        return $return_arr;
    }
  public function delete_notification($where_arr = array())
  {
    try
        {
            $result_arr = array();
            if (isset($where_arr["notification_id"]) && $where_arr["notification_id"] != "")
            {
                $this->db->where("iNotificationId =", $where_arr["notification_id"]);
            }
            $res = $this->db->delete("notification");
           // echo $this->db->last_query();exit;
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success =1;
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
        //print_r($return_arr);exit;
        return $return_arr;
    }

public function delete_notification_by_user($where_arr = array())
  {
    try
        {
            $result_arr = array();

            if (isset($where_arr["user_id"]) && $where_arr["user_id"] != "")
            {
                $this->db->where("iReceiverId =", $where_arr["user_id"]);

		$this->db->where("vNotificationType !=","Friend");
		//$array = array('iReceiverId' => $where_arr["user_id"], 'vNotificationType' => "Friend");

		//$this->db->where($array); 
            }
            $res = $this->db->delete("notification");
            //echo $this->db->last_query();exit;
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success =1;
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
        //print_r($return_arr);exit;
        return $return_arr;
    }


}
