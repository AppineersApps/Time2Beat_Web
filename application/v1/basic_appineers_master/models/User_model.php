<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of User Sign Up Email Model
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage models
 *
 * @module User Sign Up Email
 *
 * @class User_sign_up_email_model.php
 *
 * @path application\webservice\basic_appineers_master\models\User_sign_up_email_model.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 12.02.2020
 */

class User_model extends CI_Model
{
    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
    }
      /**
     * create_user method is used to execute database queries for User Sign Up Email API.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function business_create_user($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }
            if (isset($params_arr["first_name"]))
            {
                $this->db->set("vFirstName", $params_arr["first_name"]);
            }
            if (isset($params_arr["last_name"]))
            {
                $this->db->set("vLastName", $params_arr["last_name"]);
            }
            if (isset($params_arr["user_name"]))
            {
                $this->db->set("vUserName", $params_arr["user_name"]);
            }
            if (isset($params_arr["email"]))
            {
                $this->db->set("vEmail", $params_arr["email"]);
            }
            if (isset($params_arr["mobile_number"]))
            {
                $this->db->set("vMobileNo", $params_arr["mobile_number"]);
            }
            if (isset($params_arr["user_profile"]) && !empty($params_arr["user_profile"]))
            {
                $this->db->set("vProfileImage", $params_arr["user_profile"]);
            }
            if (isset($params_arr["dob"]))
            {
                $this->db->set("dDob", $params_arr["dob"]);
            }
            if (isset($params_arr["password"]))
            {
                $this->db->set("vPassword", $params_arr["password"]);
            }
            if (isset($params_arr["address"]))
            {
                $this->db->set("tAddress", $params_arr["address"]);
            }
            if (isset($params_arr["city"]))
            {
                $this->db->set("vCity", $params_arr["city"]);
            }
            if (isset($params_arr["latitude"]))
            {
                $this->db->set("dLatitude", $params_arr["latitude"]);
            }
            if (isset($params_arr["longitude"]))
            {
                $this->db->set("dLongitude", $params_arr["longitude"]);
            }
            if (isset($params_arr["state_id"]))
            {
                $this->db->set("iStateId", $params_arr["state_id"]);
            }
            if (isset($params_arr["zipcode"]))
            {
                $this->db->set("vZipCode", $params_arr["zipcode"]);
            }
            $this->db->set("eStatus", $params_arr["status"]);
            $this->db->set($this->db->protect("dtAddedAt"), $params_arr["_dtaddedat"], FALSE);
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
            if (isset($params_arr["device_token"]))
            {
                $this->db->set("vDeviceToken", $params_arr["device_token"]);
            }
            $this->db->set("eEmailVerified", $params_arr["_eemailverified"]);
            if (isset($params_arr["email_confirmation_code"]))
            {
                $this->db->set("vEmailVerificationCode", $params_arr["email_confirmation_code"]);
            }
            $this->db->set("vTermsConditionsVersion", $params_arr["_vtermsconditionsversion"]);
            $this->db->set("vPrivacyPolicyVersion", $params_arr["_vprivacypolicyversion"]);
            if (isset($params_arr["business_type_id"]))
            {
                $this->db->set("iBusinessTypeId", $params_arr["business_type_id"]);
            }
            if (isset($params_arr["business_name"]))
            {
                $this->db->set("vBusinessName", $params_arr["business_name"]);
            }
            if (isset($params_arr["position"]))
            {
                $this->db->set("vPosition", $params_arr["position"]);
            }
            $this->db->insert("users");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "insert_id";
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
     * update_profile method is used to execute database queries for Edit Profile API.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_profile($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();

            $this->db->start_cache();
            if (isset($where_arr["user_id"]) && $where_arr["user_id"] != "")
            {
                $this->db->where("iUserId =", $where_arr["user_id"]);
            }
            $this->db->where_in("eStatus", array('Active'));
            $this->db->stop_cache();
            if (isset($params_arr["first_name"]))
            {
                $this->db->set("vFirstName", $params_arr["first_name"]);
            }
            if (isset($params_arr["last_name"]))
            {
                $this->db->set("vLastName", $params_arr["last_name"]);
            }
            if (isset($params_arr["user_profile"]) && !empty($params_arr["user_profile"]))
            {
                $this->db->set("vProfileImage", $params_arr["user_profile"]);
            }
            if (isset($params_arr["dob"]))
            {
                $this->db->set("dDob", $params_arr["dob"]);
            }
            if (isset($params_arr["address"]))
            {
                $this->db->set("tAddress", $params_arr["address"]);
            }
            if (isset($params_arr["city"]))
            {
                $this->db->set("vCity", $params_arr["city"]);
            }
            if (isset($params_arr["latitude"]))
            {
                $this->db->set("dLatitude", $params_arr["latitude"]);
            }
            if (isset($params_arr["longitude"]))
            {
                $this->db->set("dLongitude", $params_arr["longitude"]);
            }
            if (isset($params_arr["state_id"]))
            {
                $this->db->set("iStateId", $params_arr["state_id"]);
            }
            if (isset($params_arr["zipcode"]))
            {
                $this->db->set("vZipCode", $params_arr["zipcode"]);
            }
            $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
            if (isset($params_arr["user_name"]))
            {
                $this->db->set("vUserName", $params_arr["user_name"]);
            }
            if (isset($params_arr["mobile_number"]))
            {
                $this->db->set("vMobileNo", $params_arr["mobile_number"]);
            }
            if (isset($params_arr["business_type_id"]))
            {
                $this->db->set("iBusinessTypeId", $params_arr["business_type_id"]);
            }
            if (isset($params_arr["business_name"]))
            {
                $this->db->set("vBusinessName", $params_arr["business_name"]);
            }
            if (isset($params_arr["position"]))
            {
                $this->db->set("vPosition", $params_arr["position"]);
            }
            $res = $this->db->update("users");
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
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * get_user_details method is used to execute database queries for User Sign Up Email API.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 01.10.2019
     * @param string $insert_id insert_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user_details($insert_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");

            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vMobileNo AS u_mobile_no");
            $this->db->select("u.vProfileImage AS u_profile_image");
            $this->db->select("u.dDob AS u_dob");
            $this->db->select("u.tAddress AS u_address");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.dLatitude AS u_latitude");
            $this->db->select("u.dLongitude AS u_longitude");
            $this->db->select("u.iStateId AS u_state_id");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("u.vPosition AS u_position");
            $this->db->select("u.vBusinessName AS u_businessname");
            $this->db->select("u.iBusinessTypeId AS u_businesstypeid");
            $this->db->select("(concat(u.vFirstName,' ',u.vLastName)) AS email_user_name", FALSE);
            if (isset($insert_id) && $insert_id != "")
            {
                $this->db->where("u.iUserId =", $insert_id);
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
    /**
     * get_updated_details method is used to execute database queries for Edit Profile API.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param string $user_id user_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_updated_details($user_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("users AS u");
            $this->db->join("mod_state AS ms", "u.iStateId = ms.iStateId", "left");

            $this->db->select("u.iUserId AS u_user_id");
            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vMobileNo AS u_mobile_no");
            $this->db->select("u.vProfileImage AS u_profile_image");
            $this->db->select("u.dDob AS u_dob");
            $this->db->select("u.eSocialLoginType AS u_social_login_type");
            $this->db->select("u.vSocialLoginId AS u_social_login_id");
            $this->db->select("u.tAddress AS u_address");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.dLatitude AS u_latitude");
            $this->db->select("u.dLongitude AS u_longitude");
            $this->db->select("u.iStateId AS u_state_id");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("u.ePushNotify AS u_push_notify");
            $this->db->select("u.vAccessToken AS u_access_token");
            $this->db->select("u.eDeviceType AS u_device_type");
            $this->db->select("u.vDeviceModel AS u_device_model");
            $this->db->select("u.vDeviceOS AS u_device_os");
            $this->db->select("u.vDeviceToken AS u_device_token");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("u.dtAddedAt AS u_added_at");
            $this->db->select("u.dtUpdatedAt AS u_updated_at");
            $this->db->select("u.eEmailVerified AS u_email_verified");
            $this->db->select("ms.vState AS ms_state");
            $this->db->select("u.eOneTimeTransaction AS e_one_time_transaction");
            $this->db->select("u.tOneTimeTransaction AS t_one_time_transaction");
            $this->db->select("u.vTermsConditionsVersion AS u_terms_conditions_version");
            $this->db->select("u.vPrivacyPolicyVersion AS u_privacy_policy_version");
             $this->db->select("u.vPosition AS u_position");
            $this->db->select("u.vBusinessName AS u_businessname");
             $this->db->select("u.iBusinessTypeId AS u_businesstypeid");
            if (isset($user_id) && $user_id != "")
            {
                $this->db->where("u.iUserId =", $user_id);
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
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }
}
