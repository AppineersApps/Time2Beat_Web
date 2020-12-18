<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Post a Feedback Controller
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage controllers
 *
 * @module Set store review
 *
 * @class set_store_review.php
 *
 * @path application\webservice\basic_appineers_master\controllers\Set_store_review.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 18.09.2019
 */

class Friends extends Cit_Controller
{
    public $settings_params;
    public $output_params;
    public $single_keys;
    public $multiple_keys;
    public $block_result;

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->settings_params = array();
        $this->output_params = array();
        $this->single_keys = array(
            "set_friend",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('friends_model');
        $this->load->model("notifications/notification_model");
        $this->load->model("users/get_user_profile_details_model");
      }


    /**
     * start_set_store_review method is used to initiate api execution flow.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_friends($request_arr = array(), $inner_api = FALSE)
    {
        // get the HTTP method, path and body of the request
            // print_r($request_arr);exit;
        $method = $_SERVER['REQUEST_METHOD'];
        $output_response = array();

        switch ($method) {
          case 'GET':
            if (isset($request_arr['my_friend_search'])){
                $output_response =  $this->get_friends($request_arr);    
            }
                 
            return  $output_response;
             break;
          case 'POST':
            if (isset($request_arr['other_user_id']) && isset($request_arr['timestamp'])){

                $output_response =  $this->send_friend_request($request_arr);
            }
            if (isset($request_arr['request_id']) && isset($request_arr['option'])){
        
                $output_response =  $this->accept_reject_request($request_arr);
            }
            return  $output_response;
            break;

            case 'DELETE':
            $output_response = $this->get_deleted_request($request_arr);
            return  $output_response;
            break;
        }
    }

/**
     * rules_set_store_review method is used to validate api input params.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_accept_reject_request($request_arr = array())
    {       
        $valid_arr = array();
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "accept_reject_request");

        return $valid_res;
    }


    public function accept_reject_request($input_params){
        
        try
        {
        
            $validation_res = $this->rules_accept_reject_request($input_params);
            if ($validation_res["success"] == "-5")
            {
                if ($inner_api === TRUE)
                {
                    return $validation_res;
                }
                else
                {
                    $this->wsresponse->sendValidationResponse($validation_res);
                }
            }
            $output_response = array();
           
            $output_array = $func_array = array();
            
            
            $input_params = $this->check_request_exist($input_params);

            if ($input_params["checkrequestexist"]["status"])
            {

                $input_params = $this->remove_friend_notification($input_params);

                //if friend request is accepted
                if($input_params['option']=='1'){
                   $input_params = $this->update_request_status($input_params);
 
                    $input_params = $this->get_user_details($input_params);
                    $input_params = $this->custom_function($input_params);


                    
                    $input_params = $this->post_notification($input_params);

                    $condition_res = $this->check_receiver_device_token($input_params);

                    if ($condition_res["success"])
                    {

                        $input_params = $this->push_notification($input_params);
                    }
                    $output_response = $this->user_friend_accept_finish_success($input_params);
                    return $output_response;
             



                }
                //if the friend request is rejected
                else{

                    $input_params = $this->delete_request($input_params);

                    $output_response = $this->user_friend_reject_finish_success($input_params);
                    return $output_response;
                } 

                
            }
           else
            {

                $output_response = $this->user_friend_exist_finish_success_1($input_params);
                return $output_response;
            }
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
    }


    public function remove_friend_notification($input_params = array())
    {
        //print_r($input_params); exit;
        $this->block_result = array();
        try
        {
            $arrResult = array();
           
            $arrResult['friend_request_id']  = isset($input_params["request_id"]) ? $input_params["request_id"] : "";
            $arrResult['dtUpdatedAt']  = "NOW()";
            
            $this->block_result = $this->notification_model->remove_friend_notification($arrResult);
            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
            $result_arr = $this->block_result["data"];
           
          $this->block_result["data"] = $result_arr;
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["remove_friend_notification"] = $this->block_result["data"];
        
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
       return $input_params;

    }

    
/**
     * user_review_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function user_friend_exist_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "user_friend_exist_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "accept_reject_request";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }




    public function update_request_status($input_params = array())
    {
        // print_r($input_params);exit;
        $this->block_result = array();
        try
        {
            $params_arr = array();

            if (isset($input_params["request_id"]))
            {
                 $where_arr["request_id"] = $input_params["request_id"];
            }
            
            $params_arr["_dtupdatedat"] = "NOW()";            
            $params_arr["_eFriendshipstatus"] = "Accepted";

            $this->block_result = $this->friends_model->update_request_status($params_arr, $where_arr);
            if (!$this->block_result["success"])
            {
                throw new Exception("updation failed.");
            }

            
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["update_friend_request"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
        return $input_params;
    }


    public function check_request_exist($input_params = array())
    {
        if (!method_exists($this, "checkRequestExist"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->checkRequestExist($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["checkrequestexist"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        // print_r($input_params);
        return $input_params;
    }

    public function user_friend_reject_finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "user_friend_reject_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "accept_reject_request";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    public function user_friend_accept_finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "user_friend_accept_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "accept_reject_request";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    public function user_friend_accept_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "user_friend_accept_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "accept_reject_request";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }


    /**
     * rules_set_store_review method is used to validate api input params.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_send_friend_request($request_arr = array())
    {       
        $valid_arr = array(
            "other_user_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "other_user_id_required",
                )
            ),
            "timestamp" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "timestamp_required",
                )
            ),
            );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "send_friend_request");

        return $valid_res;
    }

    public function send_friend_request($input_params){
        
        try
        {
        
            $validation_res = $this->rules_send_friend_request($input_params);
            if ($validation_res["success"] == "-5")
            {
                if ($inner_api === TRUE)
                {
                    return $validation_res;
                }
                else
                {
                    $this->wsresponse->sendValidationResponse($validation_res);
                }
            }
            $output_response = array();
           
            $output_array = $func_array = array();
            
            $input_params = $this->set_friend_request($input_params);

            
            $condition_res = $this->is_posted_friend($input_params);

            if ($condition_res["success"])
            {
                $input_params = $this->get_user_details($input_params);
                // print_r($input_params);exit;

                $input_params = $this->custom_function($input_params);

                $input_params = $this->post_notification($input_params);

                $condition_res = $this->check_receiver_device_token($input_params);
                if ($condition_res["success"])
                {

                    $input_params = $this->push_notification($input_params);
                 
                }
                $output_response = $this->user_friend_finish_success($input_params);
                return $output_response;
                
            }
           else
            {

                $output_response = $this->user_friend_finish_success_1($input_params);
                return $output_response;
            }
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
    }


    public function push_notification($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $device_id = $input_params["r_device_token"];
            $code = "USER";
            $sound = "";
            $badge = "";
            $title = "";
            $send_vars = array(
                array(
                    "key" => "type",
                    "value" => "Message",
                    "send" => "Yes",
                ),
                array(
                    "key" => "receiver_id",
                    "value" => $input_params["other_user_id"],
                    "send" => "Yes",
                ),
                array(
                    "key" => "user_id",
                    "value" => $input_params["user_id"],
                    "send" => "Yes",
                ),
                array(
                    "key" => "user_name",
                    "value" => $input_params["s_name"],
                    "send" => "Yes",
                ),
                array(
                    "key" => "user_profile",
                    "value" => $input_params["s_profile_image"],
                    "send" => "Yes",
                ),
                array(
                    "key" => "friend_request_id",
                    "value" => $input_params["friend_id"],
                    "send" => "Yes",
                )
            );
            $push_msg = "".$input_params["notification_message"]."";
            $push_msg = $this->general->getReplacedInputParams($push_msg, $input_params);
            $send_mode = "runtime";

            $send_arr = array();
            $send_arr['device_id'] = $device_id;
            $send_arr['code'] = $code;
            $send_arr['sound'] = $sound;
            $send_arr['badge'] = intval($badge);
            $send_arr['title'] = $title;
            $send_arr['message'] = $push_msg;
            $send_arr['variables'] = json_encode($send_vars);
            $send_arr['send_mode'] = $send_mode;
            $uni_id = $this->general->insertPushNotification($send_arr);
            if (!$uni_id)
            {
                throw new Exception('Failure in insertion of push notification batch entry.');
            }

            $success = 1;
            $message = "Push notification send succesfully.";
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->block_result["success"] = $success;
        $this->block_result["message"] = $message;
        $input_params["push_notification"] = $this->block_result["success"];

        return $input_params;
    }


    public function check_receiver_device_token($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = $input_params["r_device_token"];

            $cc_fr_0 = (!is_null($cc_lo_0) && !empty($cc_lo_0) && trim($cc_lo_0) != "") ? TRUE : FALSE;
            if (!$cc_fr_0)
            {
                throw new Exception("Some conditions does not match.");
            }
            
            $success = 1;
            $message = "Conditions matched.";
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->block_result["success"] = $success;
        $this->block_result["message"] = $message;
        return $this->block_result;
    }


     public function post_notification($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = array();
            if (isset($input_params["notification_message"]))
            {
                $params_arr["notification_message"] = $input_params["notification_message"];
            }
            if (isset($input_params["other_user_id"]))
            {
                $params_arr["receiver_id"] = $input_params["other_user_id"];
            }

            if (isset($input_params["set_friend_request"][0]['friend_id']))
            {
                $params_arr["friend_request_id"] = $input_params["set_friend_request"][0]['friend_id'];
            }
            if (isset($input_params['option'])){
                $params_arr["_enotificationtype"] = "General";

            }else{
                $params_arr["_enotificationtype"] = "Friend";    
            }
            
            $params_arr["_dtaddedat"] = "NOW()";
            $params_arr["_dtupdatedat"] = "NOW()";
            $params_arr["_estatus"] = "Unread";
            if (isset($input_params["user_id"]))
            {
                $params_arr["user_id"] = $input_params["user_id"];
            }

            $this->block_result = $this->notification_model->post_notification($params_arr);
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["post_notification"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }
    public function delete_notification($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = array();
            if (isset($input_params["user_id"]))
            {
                $params_arr["sender_id"] = $input_params["user_id"];
            }
            if (isset($input_params["other_user_id"]))
            {
                $params_arr["receiver_id"] = $input_params["other_user_id"];
            }

            $this->block_result = $this->notification_model->delete_notification($params_arr);
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["post_notification"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    public function custom_function($input_params = array())
    {
        if (!method_exists($this, "PrepareHelperMessage"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->PrepareHelperMessage($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["custom_function"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }
    public function get_user_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

            // $insert_id = isset($input_params["other_user_id"]) ? $input_params["other_user_id"] : "";
            $this->block_result = $this->friends_model->get_user_details($input_params);
            

            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }

            $result_arr = $this->block_result["data"];
            // print_r($result_arr);exit;
            
            if (is_array($result_arr) && count($result_arr) > 0)
            {
                $i = 0;
                foreach ($result_arr as $data_key => $data_arr)
                {

                    $data = $data_arr["s_profile_image"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = "time_2_beat/user_profile";
                    // $image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $data = $this->general->get_image_aws($image_arr);

                    $result_arr[$data_key]["s_profile_image"] = $data;

                    $data = $data_arr["r_profile_image"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = "time_2_beat/user_profile";
                    // $image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $data = $this->general->get_image_aws($image_arr);

                    $result_arr[$data_key]["r_profile_image"] = $data;
                }
            }

        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["get_user_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }
     public function get_friend_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

            // $insert_id = isset($input_params["other_user_id"]) ? $input_params["other_user_id"] : "";
            $this->block_result = $this->friends_model->get_friend_details($input_params);
            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["get_friend_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }
 
    /**
     * set_store_review method is used to process review block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function set_friend_request($input_params = array())
    {
        // print_r($input_params);exit;
        $this->block_result = array();
        try
        {
            $params_arr = array();
            
            if (isset($input_params["user_id"]))
            {
                $params_arr["user_id"] = $input_params["user_id"];
            }
            if (isset($input_params["other_user_id"]))
            {
                $params_arr["other_user_id"] = $input_params["other_user_id"];
            }
            if (isset($input_params["timestamp"]))
            {
                $params_arr["timestamp"] = $input_params["timestamp"];
            }
            $params_arr["_estatus"] = "Pending";
            $params_arr["_dtupdatedat"] = "NOW()";
       
            $this->block_result = $this->friends_model->set_friend_request($params_arr);

            if (!$this->block_result["success"])
            {
                throw new Exception("Insertion failed.");
            }
            
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["set_friend_request"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
        return $input_params;
    }

    /**
     * is_posted method is used to process conditions.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_posted_friend($input_params = array())
    {

        $this->block_result = array();
        try
        {
            $cc_lo_0 = (is_array($input_params["friend_id"])) ? count($input_params["friend_id"]):$input_params["friend_id"];
            $cc_ro_0 = 0;

            $cc_fr_0 = ($cc_lo_0 > $cc_ro_0) ? TRUE : FALSE;
            if (!$cc_fr_0)
            {
                throw new Exception("Some conditions does not match.");
            }
            $success = 1;
            $message = "Conditions matched.";
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->block_result["success"] = $success;
        $this->block_result["message"] = $message;
        return $this->block_result;
    }
    public function is_posted($input_params = array())
    {

        $this->block_result = array();
        try
        {
            $cc_lo_0 = (is_array($input_params["u_user_id"])) ? count($input_params["u_user_id"]):$input_params["u_user_id"];
            $cc_ro_0 = 0;

            $cc_fr_0 = ($cc_lo_0 > $cc_ro_0) ? TRUE : FALSE;
            if (!$cc_fr_0)
            {
                throw new Exception("Some conditions does not match.");
            }
            $success = 1;
            $message = "Conditions matched.";
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->block_result["success"] = $success;
        $this->block_result["message"] = $message;
        return $this->block_result;
    }

    /**
     * is_posted method is used to process conditions.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_fetched($input_params = array())
    {
        $this->block_result = array();
        try
        {
            $cc_lo_0 = $input_params["helper_id"];
            $cc_ro_0 = 0;

            $cc_fr_0 = ($cc_lo_0 > $cc_ro_0) ? TRUE : FALSE;
            if (!$cc_fr_0)
            {
                throw new Exception("Some conditions does not match.");
            }
            $success = 1;
            $message = "Conditions matched.";
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->block_result["success"] = $success;
        $this->block_result["message"] = $message;
        return $this->block_result;
    }


    /**
     * user_review_finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function user_friend_finish_success($input_params = array())
    {
        /*$output_arr['settings']['success'] = "1";
        $output_arr['settings']['message'] = "user_friend_finish_success";*/
        // $output_arr['data'] = "";

       /* $responce_arr = $this->wsresponse->sendWSResponse($output_arr, array(), "send_friend_request");

        return $responce_arr;*/

        $setting_fields = array(
            "success" => "1",
            "message" => "user_friend_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "send_friend_request";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * user_review_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function user_friend_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "user_friend_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "send_friend_request";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }




     
    /**
     * rules_set_store_review method is used to validate api input params.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_get_friends($request_arr = array())
    {
        
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "get_friends");

        return $valid_res;
    }

    /**
     * start_set_store_review method is used to initiate api execution flow.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
public function get_friends($request_arr = array(), $inner_api = FALSE)
    {
       try
        {
            $validation_res = $this->rules_get_friends($request_arr);
            if ($validation_res["success"] == "-5")
            {
                if ($inner_api === TRUE)
                {
                    return $validation_res;
                }
                else
                {
                    $this->wsresponse->sendValidationResponse($validation_res);
                }
            }
            $output_response = array();
            $input_params = $validation_res['input_params'];
            $output_array = $func_array = array();
            
            $result_params = $this->get_all_friends($input_params);
            //print_r($result_params); exit; 
            $condition_res = $this->is_posted($result_params);
            if ($condition_res["success"])
            {
               
                $output_response = $this->get_friend_finish_success($input_params,$result_params);
                return $output_response;
            }

            else
            {
 
                $output_response = $this->get_friend_finish_success_1($result_params);
                return $output_response;
            }
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
    }

    
  /**
     * get_updated_details method is used to process query block.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_image_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $arrResult['helper_id'] = isset($input_params["helper_id"]) ? $input_params["helper_id"] : "";
            $this->block_result = $this->helpers_model->get_helper_details($arrResult);

            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
            $result_arr = $this->block_result["data"];
            
            $arrImageArray =array();
            if (is_array($result_arr) && count($result_arr) > 0)
            {
             
                $data_1 = $data_arr["helper_image"];
                $arrImageArray[$data_key]["helper_image"] = (false == empty($data_1))?$data_1:'';
         
                $this->block_result["data"] = $arrImageArray;
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["get_image_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
        return $input_params;
    }






    /**
     * get_review_details method is used to process review block.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
     public function get_all_friends($input_params = array())
    {
        // print_r($input_params); exit;
        $this->block_result = array();
        try
        {
               
            $this->block_result = $this->friends_model->get_friend_details($input_params,$this->settings_params);
            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }


            $result_arr = $this->block_result["data"];
            // print_r($result_arr);exit;
            
            if (is_array($result_arr) && count($result_arr) > 0)
            {
                $i = 0;
                foreach ($result_arr as $data_key => $data_arr)
                {

                    $data = $data_arr["u_profile_image"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = "time_2_beat/user_profile";
                    // $image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $data = $this->general->get_image_aws($image_arr);

                    $result_arr[$data_key]["u_profile_image"] = $data;



                    $user_id = $input_params['user_id'];
                    $other_user_id = $data_arr['u_user_id'];

                    $block_status = $this->get_users_block_details($user_id, $other_user_id);
                        if($block_status[0]['u_block_status'] == ''){
                         $result_arr[$data_key]["u_block_status"] = "NO_BLOCK";
                        }
                        else{
                            $result_arr[$data_key]["u_block_status"] = $block_status[0]['u_block_status'];    
                        }

                    $friendship_status = $this->get_users_friendship_details($user_id, $other_user_id);
                    if($friendship_status[0]['u_friendship_status'] == ''){
                     $result_arr[$data_key]["u_friendship_status"] = "NO_FRIEND";
                    }
                    else{
                        $result_arr[$data_key]["u_friendship_status"] = $friendship_status[0]['u_friendship_status'];    
                    }

                    $friend_data = $this->get_friend_request_details($user_id, $other_user_id);
                    // print_r($friend_data);exit;
                    $result_arr[$data_key]["request_id"] = $friend_data[0]['request_id'];  
                    $i++;
                }
                $this->block_result["data"] = $result_arr;
            }

            if (is_array($result_arr) && count($result_arr) > 0)
            {                  
                $this->block_result["data"] = $result_arr;
            }


          
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["get_all_friends"] = $this->block_result["data"];
        
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
       return $input_params;
    }

    public function get_friend_request_details($user_id = '',$other_user_id='')
    {

        $this->block_result = array();
        try
        {
            
            $this->block_result = $this->get_user_profile_details_model->get_friend_request_details($user_id,$other_user_id);
            
            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
            $result_arr = $this->block_result["data"];
            }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        return $result_arr;
    }


    public function get_users_friendship_details($user_id = '',$other_user_id='')
    {

        $this->block_result = array();
        try
        {
            
            $this->block_result = $this->friends_model->get_users_friendship_details($user_id,$other_user_id);
            
            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
            $result_arr = $this->block_result["data"];
            }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        return $result_arr;
    }
    public function get_users_block_details($user_id = '',$other_user_id='')
    {

        $this->block_result = array();
        try
        {
            
            $this->block_result = $this->get_user_profile_details_model->get_users_block_details($user_id,$other_user_id);
            
            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
            $result_arr = $this->block_result["data"];
            }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        return $result_arr;
    }

     /**
     * user_review_finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
       public function get_friend_finish_success($input_params = array(),$result_params = array())
    {
       // print_r($result_params); exit;
       // "message" => "get_friend_finish_success",
        $setting_fields = array(
            "success" => "1",
            "message" => "Friend request sent successfully",
            
            "count" => count($result_params)
        );
      
        $output_fields = array(
            "u_user_id",
            "u_first_name",
            "u_last_name",
            "u_address",
            "u_city",
            "u_state",
            "u_profile_image",
            "u_friendship_status",
            "request_id",
            "u_block_status"

        );
        $output_keys = array(
            'get_all_friends',
        );
        $ouput_aliases = array(

            "u_user_id"=> "user_id",
            "u_first_name"=> "first_name",
            "u_last_name"=> "last_name",
            "u_address"=> "address",
            "u_city"=> "city",
            "u_state"=> "state",
            "u_profile_image"=> "profile_image",
            "u_friendship_status" => "friendship_status",
            "request_id" => "request_id",
            "u_block_status"=>"u_block_status"
        );

        $output_array["settings"] = array_merge($this->settings_params, $setting_fields);
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $result_params;
        //print_r($input_params);exit;

        $func_array["function"]["name"] = "get_friends";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);
        
        return $responce_arr;
    }


    /**
     * user_review_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function get_friend_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "get_friend_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_friends";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    
    public function get_deleted_request($request_arr = array())
    {
        //print_r($request_arr); exit;
      try
        {
            $output_response = array();
            $output_array = $func_array = array();
            $input_params = $request_arr;

            $input_params = $this->get_friend_request_id($input_params);    
        
           $input_params = $this->delete_request($input_params);
           if ($input_params["affected_rows"])
            {
                if(false == empty($input_params["frienship_status"]) && "Pending" == $input_params["frienship_status"]){
                    $input_params = $this->delete_notification($input_params);
                }
                $output_response = $this->delete_friend_finish_success($input_params);
                return $output_response;
            }

            else
            {
                $output_response = $this->delete_friend_finish_success_1($input_params);
                return $output_response;
            }
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
    }

    public function get_friend_request_id($input_params = array()){

        if (!method_exists($this, "getFriendRequestId"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->getFriendRequestId($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["getfriendrequestid"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        // print_r($input_params);
        return $input_params;
    }

    public function delete_request($input_params = array())
    {
        //print_r($input_params); exit;
        $this->block_result = array();
        try
        {
            $arrResult = array();
           
            $arrResult['request_id']  = isset($input_params["request_id"]) ? $input_params["request_id"] : "";
            $arrResult['dtUpdatedAt']  = "NOW()";
            
            $this->block_result = $this->friends_model->delete_request($arrResult);
            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
            $result_arr = $this->block_result["data"];
           
          $this->block_result["data"] = $result_arr;
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["delete_request"] = $this->block_result["data"];
        
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
       return $input_params;

    }


     /**
     * delete_review_finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function delete_friend_finish_success($input_params = array())
    {
     $setting_fields = array(
            "success" => "1",
            "message" => "delete_friend_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "delete_request";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
    /**
     * delete_review_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function delete_friend_finish_success_1($input_params = array())
    {
     $setting_fields = array(
            "success" => "0",
            "message" => "delete_friend_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "delete_request";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
   
   

}
