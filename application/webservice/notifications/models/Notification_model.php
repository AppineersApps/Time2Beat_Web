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

    /**
     * post_notification method is used to execute database queries for Send Message API.
     * @created CIT Dev Team
     * @modified ---
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function post_notification($params_arr = array())
    {
        // print_r($params_arr);exit;
        try
        {
                $result_arr = array();

                if(isset($params_arr['receiver_ids'])){
                    foreach($params_arr["receiver_ids"] as $key=>$value){
                        $insert_arr[$key]['iSenderId'] = $params_arr["user_id"];
                        $insert_arr[$key]['vNotificationMessage'] = $params_arr["notification_message"];
                        $insert_arr[$key]['vNotificationType'] = $params_arr["_enotificationtype"];
                        $insert_arr[$key]['dtAddedAt'] = date('Y-m-d H:i:s');
                        $insert_arr[$key]['dtUpdatedAt'] = date('Y-m-d H:i:s');
                        $insert_arr[$key]['eNotificationStatus'] = "Active";
                        $insert_arr[$key]['iReceiverId'] = $value;

                    }
                    if(is_array($insert_arr) && !empty($insert_arr))
                    {
                        $this->db->insert_batch("notification",$insert_arr);
                    }
   
                }
                else{
                    if (!is_array($params_arr) || count($params_arr) == 0)
                    {
                        throw new Exception("Insert data not found.");
                    }
                    if (isset($params_arr["notification_message"]))
                    {
                        $this->db->set("vNotificationMessage", $params_arr["notification_message"]);
                    }
                    if (isset($params_arr["receiver_id"]))
                    {
                        $this->db->set("iReceiverId", $params_arr["receiver_id"]);
                    }
                    if (isset($params_arr["friend_request_id"]))
                    {
                        $this->db->set("iFriendReqId", $params_arr["friend_request_id"]);
                    }


                    $this->db->set("vNotificationType", $params_arr["_enotificationtype"]);
                    $this->db->set($this->db->protect("dtAddedAt"), $params_arr["_dtaddedat"], FALSE);
                    $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
                    $this->db->set("eNotificationStatus", "Active");
                    if (isset($params_arr["user_id"]))
                    {
                        $this->db->set("iSenderId", $params_arr["user_id"]);
                    }
                    $this->db->insert("notification");
                        
                }
                $insert_id = $this->db->insert_id();
                if (!$insert_id)
                {
                    throw new Exception("Failure in insertion.");
                }
                $result_param = "insert_id1";
                $result_arr[0][$result_param] = $insert_id;
                $success = 1;   
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->_reset_all();
        // echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }


    public function remove_friend_notification($params_arr = array())
    {
       // print_r($params_arr); exit;
       try
        {
            $result_arr = array();
            $this->db->start_cache();
            
            if (isset($params_arr["friend_request_id"]))
            {
                $this->db->where("iFriendReqId =", $params_arr["friend_request_id"]);
            }
            $this->db->stop_cache();
           
            $res = $this->db->delete("notification");

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

    public function delete_notification($params_arr = array())
    {
       // print_r($params_arr); exit;
       try
        {
            $result_arr = array();
            $this->db->start_cache();
            
            if (isset($params_arr["sender_id"]))
            {
                $this->db->where("iSenderId =", $params_arr["sender_id"]);
            }
            if (isset($params_arr["receiver_id"]))
            {
                $this->db->where("iReceiverId =", $params_arr["receiver_id"]); 
            }
            $this->db->stop_cache();
           
            $res = $this->db->delete("notification");

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
     * notification_entry method is used to execute database queries for Like User Profile API.
     * @created Devangi Nirmal | 05.06.2019
     * @modified Devangi Nirmal | 19.06.2019
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function notification_entry($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }

            $this->db->set($this->db->protect("vMessage"), $params_arr["_vmessage"], FALSE);
            if (isset($params_arr["liked_id"]))
            {
                $this->db->set("iUsersId", $params_arr["liked_id"]);
            }
            $this->db->set("eNotificationType", $params_arr["_enotificationtype"]);
            $this->db->set($this->db->protect("dtAddedAt"), $params_arr["_dtaddedat"], FALSE);
            $this->db->set("eNotificationStatus", $params_arr["_estatus"]);
            if (isset($params_arr["user_id"]))
            {
                $this->db->set("iFromId", $params_arr["user_id"]);
            }
            $this->db->insert("notification");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "insert_id1";
            $result_arr[0][$result_param] = $insert_id;
            $success = 1;
        }
        catch(Exception $e)
        {
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
     * entry_for_match method is used to execute database queries for Like User Profile API.
     * @created Devangi Nirmal | 05.06.2019
     * @modified Devangi Nirmal | 21.06.2019
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function entry_for_match($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }

            $this->db->set($this->db->protect("vMessage"), $params_arr["_vmessage"], FALSE);
            if (isset($params_arr["liked_id"]))
            {
                $this->db->set("iUsersId", $params_arr["liked_id"]);
            }
            $this->db->set("eNotificationType", $params_arr["_enotificationtype"]);
            $this->db->set($this->db->protect("dtAddedAt"), $params_arr["_dtaddedat"], FALSE);
            $this->db->set("eStatus", $params_arr["_estatus"]);
            if (isset($params_arr["user_id"]))
            {
                $this->db->set("iFromId", $params_arr["user_id"]);
            }
            $this->db->insert("notification");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "insert_id2";
            $result_arr[0][$result_param] = $insert_id;
            $success = 1;
        }
        catch(Exception $e)
        {
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
     * read_notifications method is used to execute database queries for Notification List API.
     * @created CIT Dev Team
     * @modified Devangi Nirmal | 04.06.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function read_notifications($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["user_id"]) && $where_arr["user_id"] != "")
            {
                $this->db->where("iUsersId =", $where_arr["user_id"]);
            }
            $this->db->where_in("eStatus", array('Unread'));

            $this->db->set("eStatus", $params_arr["_estatus"]);
            $res = $this->db->update("notification");
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
     * get_notification_details method is used to execute database queries for Notification List API.
     * @created CIT Dev Team
     * @modified Devangi Nirmal | 11.06.2019
     * @param string $user_id user_id is used to process query block.
     * @param array $settings_params settings_params are used for paging parameters.
     * @return array $return_arr returns response of query block.
     */
    public function get_notification_details($user_id = '', $page_index = 1, &$settings_params = array())
    {
        try
        {
            $result_arr = array();

            $this->db->start_cache();
            $this->db->from("notification AS n");
            $this->db->join("users AS u", "n.iFromId = u.iUsersId", "left");

            $this->db->select("n.iNotificationId AS notification_id");
            $this->db->select("n.vMessage AS message");
            $this->db->select("n.eNotificationType AS notification_type");
            $this->db->select("u.vFirstName AS from_first_name");
            $this->db->select("u.vLastName AS from_last_name");
            $this->db->select("u.iUsersId AS from_user_id");
            $this->db->select("n.dtAddedAt AS notification_datetime");
            $this->db->select("(".$this->db->escape("").") AS from_image", FALSE);
            if (isset($user_id) && $user_id != "")
            {
                $this->db->where("n.iUsersId =", $user_id);
            }
            $this->db->where_in("n.eNotificationType", array('Like', 'Match'));

            $this->db->stop_cache();
            $total_records = $this->db->count_all_results();

            $settings_params['count'] = $total_records;

            $record_limit = 20;
            $current_page = intval($page_index) > 0 ? intval($page_index) : 1;
            $total_pages = getTotalPages($total_records, $record_limit);
            $start_index = getStartIndex($total_records, $current_page, $record_limit);
            $settings_params['per_page'] = $record_limit;
            $settings_params['curr_page'] = $current_page;
            $settings_params['prev_page'] = ($current_page > 1) ? 1 : 0;
            $settings_params['next_page'] = ($current_page+1 > $total_pages) ? 0 : 1;

            $this->db->order_by("n.dtAddedAt", "desc");
            $this->db->limit($record_limit, $start_index);
            $result_obj = $this->db->get();
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
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * get_count method is used to execute database queries for notification_count API.
     * @created Devangi Nirmal | 27.06.2019
     * @modified Devangi Nirmal | 16.07.2019
     * @param string $user_id user_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_count($user_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("notification AS n");

            $this->db->select("(count(*)) AS notification_count", FALSE);
            if (isset($user_id) && $user_id != "")
            {
                $this->db->where("n.iUsersId =", $user_id);
            }
            $this->db->where_in("n.eStatus", array('Unread'));
            $this->db->where_in("n.eNotificationType", array('Like', 'Match'));

            $this->db->limit(1);

            $result_obj = $this->db->get();
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
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }
}
