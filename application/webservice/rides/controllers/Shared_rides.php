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

class Shared_rides extends Cit_Controller
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
            "set_shared_ride",
            "get_shared_ride_details",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('shared_rides_model');
        $this->load->model("users/get_user_profile_details_model");
        $this->load->model("notifications/notification_model");
    }


    /**
     * start_set_store_review method is used to initiate api execution flow.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_shared_rides($request_arr = array(), $inner_api = FALSE)
    {
        // print_r($request_arr);exit;
        // get the HTTP method, path and body of the request
        $method = $_SERVER['REQUEST_METHOD'];
        $output_response = array();

        switch ($method) {
          case 'GET':
            if(isset($request_arr['ride_id'])){
                $output_response =  $this->get_share_ride_by_me($request_arr);
            }
            else{
                $output_response =  $this->get_share_ride($request_arr);    
            }
                 
            return  $output_response;
             break;
          case 'POST':
            $output_response =  $this->add_share_ride($request_arr);   
            
            
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
    public function rules_add_share_ride($request_arr = array())
    {       
        $valid_arr = array(
            "ride_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "ride_id_required",
                )
            ),
            "shared_with_ids" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "shared_with_ids_required",
                )
            ),
           
            );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "add_share_ride");

        return $valid_res;
    }

    public function add_share_ride($input_params){
        
        try
        {
        
            $validation_res = $this->rules_add_share_ride($input_params);
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
            $input_params = $validation_res['input_params'];
            
            

            $output_response = array();
           
            $output_array = $func_array = array();
            $arrRideCheck = $this->check_ride_exist($input_params);
            if (false == empty($arrRideCheck['status']))
            {
                $input_params = $this->check_ride_details($input_params);
                //print_r($input_params);exit; 
                if (true == empty($input_params['checkrideexist']['0']['status']))
                { 
                    $input_params = $this->set_shared_ride($input_params);

                    $condition_res = $this->is_posted($input_params);

                    if ($condition_res["success"])
                    {   
                        $input_params = $this->get_user_details($input_params);

                        $input_params = $this->custom_function($input_params);

                        $input_params = $this->post_notification($input_params);

                        $ride_id = $input_params['ride_id'];
                        $notification_message = $input_params['notification_message'];
                        // print_r($input_params);exit;
                        foreach($input_params["get_user_details"] as $key=>$value){
                            $condition_res = $this->check_receiver_device_token($value);
                            if ($condition_res["success"])
                            {

                                $input_params = $this->push_notification($value, $ride_id, $notification_message);
                            }
                        }
                        $output_response = $this->user_shared_ride_finish_success($input_params);
                        return $output_response;
                    }
                }else{
                 //
                    $input_params = $this->update_shared_ride($input_params);
                    $output_response = $this->user_shared_ride_finish_success($input_params);
                    return $output_response;

                }
            }
            else
            {
                $output_response = $this->user_shared_ride_finish_success_1($input_params);
                return $output_response;
            }
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
    }



    public function push_notification($input_params = array(),$ride_id, $notification_message)
    {
        // print_r($input_params);

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
                    "key" => "ride_id",
                    "value" => $ride_id,
                    "send" => "Yes",
                )
            );
            $push_msg = "".$notification_message."";
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
        // print_r($input_params);exit;
        $this->block_result = array();
        try
        {

            $params_arr = array();
            if (isset($input_params["notification_message"]))
            {
                $params_arr["notification_message"] = $input_params["notification_message"];
            }
            if (isset($input_params["other_user_ids"]))
            {
                $params_arr["receiver_ids"] = $input_params["other_user_ids"];
            }
            $params_arr["_enotificationtype"] = "Ride";
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
             $input_params["other_user_ids"] = explode(',',$input_params["shared_with_ids"]);
            $this->block_result = $this->shared_rides_model->get_user_details($input_params);
            

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
        // print_r($input_params);exit;
        return $input_params;
    }

    /**
     * set_store_review method is used to process review block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function set_shared_ride($input_params = array())
    {
        $this->block_result = array();
        try
        {
            $params_arr = array();
            if (isset($input_params["timestamp"]))
            {
                $params_arr["_dtaddedat"] = $input_params["timestamp"];
            }
            $params_arr["_estatus"] = "Active";
            if (isset($input_params["user_id"]))
            {
                $params_arr["user_id"] = $input_params["user_id"];
            }
            if (isset($input_params["ride_id"]))
            {
                $params_arr["ride_id"] = $input_params["ride_id"];
            }
            if (isset($input_params["shared_with_ids"]))
            {

                $params_arr["other_user_ids"] = explode(',',$input_params["shared_with_ids"]);
            }
       
            $this->block_result = $this->shared_rides_model->set_shared_ride($params_arr);

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
        $input_params["set_shared_ride"] = $this->block_result["data"];
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
    public function update_shared_ride($input_params = array())
    {
       
        $this->block_result = array();
        try
        {
            $params_arr = array();
            if (isset($input_params["timestamp"]))
            {
                $params_arr["dtAddedAt"] = $input_params["timestamp"];
            }
            $params_arr["_estatus"] = "Active";
            if (isset($input_params["user_id"]))
            {
                $params_arr["user_id"] = $input_params["user_id"];
            }
            if (isset($input_params["share_ride_id"]))
            {
                $params_arr["share_ride_id"] = $input_params["share_ride_id"];
            }
            if (isset($input_params["shared_with_ids"]))
            {
                $params_arr["other_user_ids"] = explode(',',$input_params["shared_with_ids"]);
            }
          
           foreach($params_arr["other_user_ids"] as $friend_id){
             $this->block_result = $this->shared_rides_model->update_shared_ride($params_arr,$friend_id);
           }
            

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
        $input_params["set_shared_ride"] = $this->block_result["data"];
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
    public function is_posted($input_params = array())
    {

        $this->block_result = array();
        try
        {
            $cc_lo_0 = (is_array($input_params["shared_ride_id"])) ? count($input_params["shared_ride_id"]):$input_params["shared_ride_id"];
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
            $cc_lo_0 = $input_params["ride_id"];
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
    public function user_shared_ride_finish_success($input_params = array())
    {
        $setting_fields = array(
            "success" => "1",
            "message" => "user_shared_ride_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "add_share_ride";
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
    public function user_shared_ride_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "user_shared_ride_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "add_share_ride";
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
    public function rules_get_share_ride_by_me($request_arr = array())
    {
        
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "get_share_ride_by_me");

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
    public function get_share_ride_by_me($request_arr = array(), $inner_api = FALSE)
    {
        // print_r($request_arr);exit;
       try
        {
            $validation_res = $this->rules_get_share_ride_by_me($request_arr);
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
            $arrRideCheck = $this->check_shared_ride_exist($input_params);
            //print_r($arrRideCheck);exit;

            if (false == empty($arrRideCheck['status']))
            {
                $result_params = $this->get_all_share_by_me_ride($input_params);

                // print_r($result_params); exit; 
                $condition_res = $this->is_posted($result_params);
                if ($condition_res["success"])
                {
                   
                    $output_response = $this->get_share_ride_by_me_finish_success($input_params,$result_params);
                    return $output_response;
                }

                else
                {
     
                    $output_response = $this->get_share_ride_by_me_finish_success_1($result_params);
                    return $output_response;
                }
            }else{
              
                $result_params = $this->get_all_ride($input_params);
               

                $condition_res = $this->is_fetched($result_params);
                //print_r($condition_res);exit;
                if (false == empty($condition_res["success"]))
                {
                    //print_r($result_params);exit;
                    $output_response = $this->get_share_ride_by_me_finish_success($input_params,$result_params);
                    return $output_response;
                }

                else
                {
                    $output_response = $this->get_share_ride_by_me_finish_success_1($result_params);
                    return $output_response;
                }

            }
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
    }
    /**
     * get_review_details method is used to process review block.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
     public function get_all_ride($input_params = array())
    {
        // print_r($input_params); exit;
        $this->block_result = array();
        try
        {
           
            $input_params["ride_id"] = isset($input_params["ride_id"]) ? $input_params["ride_id"] : "";
            
            $this->block_result = $this->shared_rides_model->get_ride_details($input_params);
           //print_r($this->block_result);exit;
           $result_arr = $this->block_result["data"];
            // print_r($result_arr);exit;
            if (is_array($result_arr) && count($result_arr) > 0)
            {

               $i = 0;
                foreach ($result_arr as $data_key => $data_arr)
                {

                    $ride_id = $data_arr['ride_id'];
                   
                    $result_arr[$data_key]["u_user_details"] = array();
                    $data = $data_arr["u_profile_image"];
                   //  print_r($data);exit;
                    if(false == empty($data)){
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = "time_2_beat/user_profile";
                   // $image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $data = $this->general->get_image_aws($image_arr);
                   //echo  $data; exit;
                    if(false == empty($data)){
                        $result_arr[$data_key]["u_profile_image"] = $data;
                    }
                    }  
                    
                }
                
            }
            $this->block_result["data"] = $result_arr;
          
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["get_all_share_by_me_ride"] = $this->block_result["data"];
        
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
        //print_r($input_params);exit;
       return $input_params;
    }

/**
     * get_review_details method is used to process review block.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
     public function get_all_share_by_me_ride($input_params = array())
    {
        // print_r($input_params); exit;
        $this->block_result = array();
        try
        {
               
            $this->block_result = $this->shared_rides_model->get_ride_share_by_me_details($input_params,$this->settings_params);
            
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

                    $ride_id = $data_arr['ride_id'];
                    $user_details = array();
                    $data = $data_arr["u_profile_image"];
                   //  print_r($data);exit;
                    if(false == empty($data)){
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = "time_2_beat/user_profile";
                   // $image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $data = $this->general->get_image_aws($image_arr);
                   //echo  $data; exit;
                    if(false == empty($data)){
                        $result_arr[$data_key]["u_profile_image"] = $data;
                    }
                    } 
                    $user_details = $this->shared_rides_model->get_shared_ride_user_details($ride_id);
                    // print_r($user_details);exit;
                    if (!$user_details["success"])
                    {
                        throw new Exception("No records found.");
                    }
                
                    $user_details_arr = array();
                    $user_details_arr = $user_details["data"];
                    // print_r($user_details_arr);exit;
                    if (is_array($user_details_arr) && count($user_details_arr) > 0)
                    {
                        foreach ($user_details_arr as $user_data_key => $user_data_arr)
                        {
                            // print_r($user_data_arr);exit;
                            $data = $user_data_arr["u_profile_image"];
                            if(false == empty($data)){
                            $image_arr = array();
                            $image_arr["image_name"] = $data;
                            $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                            $image_arr["color"] = "FFFFFF";
                            $image_arr["no_img"] = FALSE;
                            $image_arr["path"] = "time_2_beat/user_profile";
                           // $image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                            $data = $this->general->get_image_aws($image_arr);
                           
                            if(false == empty($data)){
                                $user_details_arr[$user_data_key]["u_profile_image"] = $data;
                            }
                        }
                     
                        }
                    }
                    $result_arr[$data_key]["u_user_details"] = $user_details_arr;    
                    
                }
                $this->block_result["data"] = $result_arr;
            }
          
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["get_all_share_by_me_ride"] = $this->block_result["data"];
        
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
       return $input_params;
    }



     /**
     * user_review_finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
       public function get_share_ride_by_me_finish_success($input_params = array(),$result_params = array())
    {
        //print_r($result_params); exit;
        $setting_fields = array(
            "success" => "1",
            "message" => "get_share_ride_by_me_finish_success",
        );
      
        $output_fields = array(
            'u_user_details',
            'u_user_id',
            'u_first_name',
            'u_last_name',
            'u_email',
            'u_mobile_no',
            'u_profile_image',
            'u_block_status',
            'u_address',
            'u_city',
            'u_latitude',
            'u_longitude',
            'u_state_id',
            'u_zip_code',
            'u_state',

            "ride_id",
            "user_id",
            "note",
            "start_loc_id",
            "end_loc_id",
            "start_date_time",
            "end_date_time",
            "eta",
            "ride_time",
            "sl_address", 
            "sl_city",
            "sl_latitude",
            "sl_longitude",
            "sl_state", 
            "sl_zipcode",

           
            "el_address", 
            "el_city",
            "el_latitude",
            "el_longitude",
            "el_state" ,
            "el_zipcode",
            "best_time"
            

        );
        $output_keys = array(
            'get_all_share_by_me_ride',
        );
        $ouput_aliases = array(

            "u_user_details" => "user_details",
            "u_user_id" => "user_id",
            "u_first_name" => "first_name",
            "u_last_name" => "last_name",
            "u_user_name" => "user_name",
            "u_email" => "email",
            "u_mobile_no" => "mobile_no",
            "u_profile_image" => "profile_image",
            "u_block_status" => "block_status",
            "u_address" => "address",
            "u_city" => "city",
            "u_latitude" => "latitude",
            "u_longitude" => "longitude",
            "u_state_id" => "state_id",
            "u_zip_code" => "zip_code",
            "u_state" => "state",

            "ride_id" => "ride_id",
            "user_id"=> "user_id",
            "note"=> "note",
            "start_loc_id"=> "start_loc_id",
            "end_loc_id"=> "end_loc_id",
            "start_date_time"=> "start_date_time",
            "end_date_time"=> "end_date_time",
            "eta"=> "eta",
            "ride_time"=> "ride_time",
            "sl_address" => "sl_address", 
            "sl_city"=> "sl_city",
            "sl_latitude"=> "sl_latitude",
            "sl_longitude"=> "sl_longitude",
            "sl_state"=> "sl_state",
            "sl_zipcode"=> "sl_zipcode",
            "el_address"=>"el_address",
            "el_city"=>"el_city",
            "el_latitude"=>"el_latitude",
            "el_longitude"=>"el_longitude",
            "el_state"=> "el_state" ,
            "el_zipcode"=> "el_zipcode",
            "best_time"=>"best_time"
        );

        $output_array["settings"] = array_merge($this->settings_params, $setting_fields);
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $result_params;
       //print_r($output_array["data"]);exit;

        $func_array["function"]["name"] = "get_share_ride_by_me";
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
    public function get_share_ride_by_me_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "get_share_ride_by_me_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_share_ride_by_me";
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
    public function rules_get_share_ride($request_arr = array())
    {
        
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "get_share_ride");

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
public function get_share_ride($request_arr = array(), $inner_api = FALSE)
    {
       try
        {
            $validation_res = $this->rules_get_share_ride($request_arr);
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
           
            $result_params = $this->get_all_share_ride($input_params);
            //print_r($result_params); exit; 
            $condition_res = $this->is_posted($result_params);
            if ($condition_res["success"])
            {
               
                $output_response = $this->get_share_ride_finish_success($input_params,$result_params);
                return $output_response;
            }

            else
            {
 
                $output_response = $this->get_share_ride_finish_success_1($result_params);
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
     * checkuniqueusername method is used to process custom function.
     * @created priyanka chillakuru | 25.09.2019
     * @modified saikumar anantham | 08.10.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function check_ride_details($input_params = array())
    {
        if (!method_exists($this, "checkRideDetails"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $this->block_result["data"] = $this->checkRideDetails($input_params);
        }
        
       $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
       return $input_params;
    }
    /**
     * check_shared_ride_exist method is used to process custom function.
     * @created priyanka chillakuru | 25.09.2019
     * @modified saikumar anantham | 08.10.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function check_shared_ride_exist($input_params = array())
    {
        if (!method_exists($this, "checkShareRideExist"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $input_params = $this->checkShareRideExist($input_params);
        }
        //print_r($input_params);exit;
        return $input_params;
    }

    /**
     * get_review_details method is used to process review block.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
     public function get_all_share_ride($input_params = array())
    {
        // print_r($input_params); exit;
        $this->block_result = array();
        try
        {
               
            $this->block_result = $this->shared_rides_model->get_ride_share_details($input_params,$this->settings_params);
        
            $result_arr = $this->block_result["data"];

            if (is_array($result_arr) && count($result_arr) > 0)
            {
               $i = 0;
                foreach ($result_arr as $data_key => $data_arr)
                {
                    $result_arr[$data_key]["u_profile_image"] = "";
                    $data = $data_arr["u_profile_image"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = "time_2_beat/user_profile";
                   // $image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $data = $this->general->get_image_aws($image_arr);
                    //print_r($data); exit;
                    if(false == empty($data)){
                     $result_arr[$data_key]["u_profile_image"] = $data;   
                    }
                    
             

                    /*$user_id = $input_params['user_id'];
                    $other_user_id = $data_arr['u_user_id'];
                   

                    $block_status = $this->get_users_block_details($user_id, $other_user_id);
                    if(false == empty$block_status) && $block_status[0]['u_block_status'] == ''){
                     $result_arr[$data_key]["u_block_status"] = "NO_BLOCK";
                    }
                    else{
                        $result_arr[$data_key]["u_block_status"] = $block_status[0]['u_block_status'];    
                    }*/
                }
               
            }
            // print_r($result_arr);
            $this->block_result["data"] = $result_arr;
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
       // print_r($this->block_result["data"]);exit;
        $input_params["get_all_share_ride"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
       return $input_params;
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
       public function get_share_ride_finish_success($input_params = array(),$result_params = array())
    {
       // print_r($input_params); exit;
        $setting_fields = array(
            "success" => "1",
            "message" => "get_share_ride_finish_success",
        );
      
        $output_fields = array(
            'u_user_id',
            'u_first_name',
            'u_last_name',
            'u_email',
            'u_mobile_no',
            'u_profile_image',
            'u_block_status',
            'u_address',
            'u_city',
            'u_latitude',
            'u_longitude',
            'u_state_id',
            'u_zip_code',
            'u_state',

            "ride_id",
            "user_id",
            "note",
            "start_loc_id",
            "end_loc_id",
            "start_date_time",
            "end_date_time",
            "eta",
            "ride_time",
            "sl_address", 
            "sl_city",
            "sl_latitude",
            "sl_longitude",
            "sl_state", 
            "sl_zipcode",

           
            "el_address", 
            "el_city",
            "el_latitude",
            "el_longitude",
            "el_state" ,
            "el_zipcode",
            "best_time"
            

        );
        $output_keys = array(
            'get_all_share_ride',
        );
        $ouput_aliases = array(

            "u_user_id" => "user_id",
            "u_first_name" => "first_name",
            "u_last_name" => "last_name",
            "u_user_name" => "user_name",
            "u_email" => "email",
            "u_mobile_no" => "mobile_no",
            "u_profile_image" => "profile_image",
            "u_block_status" => "block_status",
            "u_address" => "address",
            "u_city" => "city",
            "u_latitude" => "latitude",
            "u_longitude" => "longitude",
            "u_state_id" => "state_id",
            "u_zip_code" => "zip_code",
            "u_state" => "state",

            "ride_id" => "ride_id",
            "user_id"=> "user_id",
            "note"=> "note",
            "start_loc_id"=> "start_loc_id",
            "end_loc_id"=> "end_loc_id",
            "start_date_time"=> "start_date_time",
            "end_date_time"=> "end_date_time",
            "eta"=> "eta",
            "ride_time"=> "ride_time",
            "sl_address" => "sl_address", 
            "sl_city"=> "sl_city",
            "sl_latitude"=> "sl_latitude",
            "sl_longitude"=> "sl_longitude",
            "sl_state"=> "sl_state",
            "sl_zipcode"=> "sl_zipcode",
            "el_address"=>"el_address",
            "el_city"=>"el_city",
            "el_latitude"=>"el_latitude",
            "el_longitude"=>"el_longitude",
            "el_state"=> "el_state" ,
            "el_zipcode"=> "el_zipcode",
            "best_time"=>"best_time"
        );

        $output_array["settings"] = array_merge($this->settings_params, $setting_fields);
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $result_params;
        //print_r($input_params);exit;

        $func_array["function"]["name"] = "get_share_ride";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);
        
        // return $responce_arr;
        $arrSubcategory = array();
        
        $arrSubcategory["settings"] = $responce_arr["settings"];
        
       
        // $arrSubcategory['data']['rider']['ride_history'] = array();
            // print_r($responce_arr);exit;
        foreach ($responce_arr['data'] as $key=>$value)
        {
            if(false == empty($value['ride_id']))
            {
                $arrSubcategory['data'][$key]['rider']['user_id'] = $value['user_id'];
                $arrSubcategory['data'][$key]['rider']['first_name'] = $value['first_name'];
                $arrSubcategory['data'][$key]['rider']['last_name'] = $value['last_name'];
                $arrSubcategory['data'][$key]['rider']['user_name'] = $value['user_name'];
                $arrSubcategory['data'][$key]['rider']['email'] = $value['email'];
                $arrSubcategory['data'][$key]['rider']['mobile_no'] = $value['mobile_no'];
                $arrSubcategory['data'][$key]['rider']['profile_image'] = $value['profile_image'];
                $arrSubcategory['data'][$key]['rider']['block_status'] = $value['block_status'];
                $arrSubcategory['data'][$key]['rider']['address'] = $value['address'];
                $arrSubcategory['data'][$key]['rider']['city'] = $value['city'];
                $arrSubcategory['data'][$key]['rider']['latitude'] = $value['latitude'];
                $arrSubcategory['data'][$key]['rider']['longitude'] = $value['longitude'];
                $arrSubcategory['data'][$key]['rider']['state_id'] = $value['state_id'];
                $arrSubcategory['data'][$key]['rider']['zip_code'] = $value['zip_code'];
                $arrSubcategory['data'][$key]['rider']['state'] = $value['state'];
                $arrSubcategory['data'][$key]['ride_history']['ride_id'] = $value['ride_id'];
                $arrSubcategory['data'][$key]['ride_history']['note'] = $value['note'];
                $arrSubcategory['data'][$key]['ride_history']['start_loc_id'] = $value['start_loc_id'];
                $arrSubcategory['data'][$key]['ride_history']['end_loc_id'] = $value['end_loc_id'];
                $arrSubcategory['data'][$key]['ride_history']['start_date_time'] = $value['start_date_time'];
                $arrSubcategory['data'][$key]['ride_history']['end_date_time'] = $value['end_date_time'];
                $arrSubcategory['data'][$key]['ride_history']['eta'] = $value['eta'];
                $arrSubcategory['data'][$key]['ride_history']['ride_time'] = $value['ride_time'];
                $arrSubcategory['data'][$key]['ride_history']['sl_address'] = $value['sl_address'];
                $arrSubcategory['data'][$key]['ride_history']['sl_city'] = $value['sl_city'];
                $arrSubcategory['data'][$key]['ride_history']['sl_latitude'] = $value['sl_latitude'];
                $arrSubcategory['data'][$key]['ride_history']['sl_longitude'] = $value['sl_longitude'];
                $arrSubcategory['data'][$key]['ride_history']['sl_state'] = $value['sl_state'];
                $arrSubcategory['data'][$key]['ride_history']['sl_zipcode'] = $value['sl_zipcode'];
                $arrSubcategory['data'][$key]['ride_history']['el_address'] = $value['el_address'];
                $arrSubcategory['data'][$key]['ride_history']['el_city'] = $value['el_city'];
                $arrSubcategory['data'][$key]['ride_history']['el_latitude'] = $value['el_latitude'];
                $arrSubcategory['data'][$key]['ride_history']['el_longitude'] = $value['el_longitude'];
                $arrSubcategory['data'][$key]['ride_history']['el_state'] = $value['el_state'];
                $arrSubcategory['data'][$key]['ride_history']['el_zipcode'] = $value['el_zipcode'];
            }

        }        



        return $arrSubcategory;
    }


    /**
     * user_review_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function get_share_ride_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "get_share_ride_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_share_ride";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    public function check_ride_exist($input_params = array())
    {
        if (!method_exists($this, "checkRideExist"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->checkRideExist($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["checkrideexist"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
         //print_r($input_params);exit;
        return $input_params;
    }
    

}
