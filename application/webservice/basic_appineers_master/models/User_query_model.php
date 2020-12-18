<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of User Query Model
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage models
 *
 * @module User Query
 *
 * @class User_query_model.php
 *
 * @path application\webservice\basic_appineers_master\models\User_query_model.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 11.12.2019
 */

class User_query_model extends CI_Model
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
     * @modified priyanka chillakuru | 11.12.2019
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function post_a_feedback($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }

            $this->db->set($this->db->protect("dtAddedAt"), $params_arr["_dtaddedat"], FALSE);
            $this->db->set("eStatus", $params_arr["_estatus"]);
            if (isset($params_arr["user_id"]))
            {
                $this->db->set("iUserId", $params_arr["user_id"]);
            }
            if (isset($params_arr["feedback"]))
            {
                $this->db->set("tFeedback", $params_arr["feedback"]);
            }
            $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
            if (isset($params_arr["device_type"]))
            {
                $this->db->set("eDeviceType", $params_arr["device_type"]);
            }
            if (isset($params_arr["device_model"]))
            {
                $this->db->set("vDeviceModel", $params_arr["device_model"]);
            }
            if (isset($params_arr["device_os"]))
            {
                $this->db->set("vDeviceOS", $params_arr["device_os"]);
            }
            if (isset($params_arr["app_version"]))
            {
                $this->db->set("vAppVersion", $params_arr["app_version"]);
            }
            $this->db->insert("user_query");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "query_id";
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
     * get_query_details method is used to execute database queries for Post a Feedback API.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 11.12.2019
     * @param string $query_id query_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_query_details($query_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("user_query AS uq");

            $this->db->select("uq.tFeedback AS uq_feedback");
            $this->db->select("uq.iUserId AS uq_user_id");
            $this->db->select("uq.tNote AS uq_note");
            $this->db->select("uq.eDeviceType AS uq_device_type");
            $this->db->select("uq.vDeviceModel AS uq_device_model");
            $this->db->select("uq.vDeviceOS AS uq_device_os");
            $this->db->select("uq.eStatus AS uq_status");
            $this->db->select("uq.dtAddedAt AS uq_added_at");
            $this->db->select("uq.dtUpdatedAt AS uq_updated_at");
            $this->db->select("(".$this->db->escape("").") AS images", FALSE);
            $this->db->select("uq.vAppVersion AS uq_app_version");
            if (isset($query_id) && $query_id != "")
            {
                $this->db->where("uq.iUserQueryId =", $query_id);
            }

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
