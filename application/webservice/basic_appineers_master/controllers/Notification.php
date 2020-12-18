<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Notification List Controller
 *
 * @category webservice
 *
 * @package notifications
 *
 * @subpackage controllers
 *
 * @module Notification List
 *
 * @class Notification_list.php
 *
 * @path application\webservice\notifications\controllers\Notification_list.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 27.06.2019
 */

class Notification extends Cit_Controller
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

        $this->multiple_keys = array(
            "get_notification_details",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model("notification_model");
    }

    /**
     * rules_notification_list method is used to validate api input params.
     * @created priyanka chillakuru | 04.06.2019
     * @modified Devangi Nirmal | 27.06.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_notification_list($request_arr = array())
    {
        $valid_arr = array(
            "user_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "user_id_required",
                )
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "notification_list");

        return $valid_res;
    }
    /**
     * start_set_store_item method is used to initiate api execution flow.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_notification($request_arr = array(), $inner_api = FALSE)
    {

    // get the HTTP method, path and body of the request
        $method = $_SERVER['REQUEST_METHOD'];
        $output_response = array();
        switch ($method) {
          case 'GET':
            
            $output_response =  $this->get_notification($request_arr);

            return  $output_response;
            break;
         case 'DELETE':
            //print_r($request_arr);exit;
            $output_response =  $this->delete_notification($request_arr);

            return  $output_response;
            break;
        }
    }

    /**
     * start_notification_list method is used to initiate api execution flow.
     * @created priyanka chillakuru | 04.06.2019
     * @modified Devangi Nirmal | 27.06.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function get_notification($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_notification_list($request_arr);
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

            $input_params = $this->get_notification_details($input_params);

            $condition_res = $this->is_notify_found($input_params);
            if ($condition_res["success"])
            {

                $input_params = $this->get_image($input_params);

                //$input_params = $this->read_notifications($input_params);

                $output_response = $this->notification_finish_success_1($input_params);
                return $output_response;
            }

            else
            {

                $output_response = $this->notification_finish_success_3($input_params);
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
     * get_notification_details method is used to process query block.
     * @created CIT Dev Team
     * @modified Devangi Nirmal | 11.06.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_notification_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $user_id = isset($input_params["user_id"]) ? $input_params["user_id"] : "";
            
            $this->block_result = $this->notification_model->get_notification_details($user_id, $page_name);
    
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
        $input_params["get_notification_details"] = $this->block_result["data"];

        return $input_params;
    }


    /**
     * is_notify_found method is used to process conditions.
     * @created CIT Dev Team
     * @modified ---
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_notify_found($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["get_notification_details"]) ? 0 : 1);
            $cc_ro_0 = 1;

            $cc_fr_0 = ($cc_lo_0 == $cc_ro_0) ? TRUE : FALSE;
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
     * get_image method is used to process query block.
     * @created Devangi Nirmal | 11.06.2019
     * @modified Devangi Nirmal | 27.06.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_image($input_params = array())
    {
        $this->block_result = array();
        try
        {

            //$from_user_id = isset($input_params["from_user_id"]) ? $input_params["from_user_id"] : "";
           // $this->block_result = $this->user_images_ws_model->get_image($from_user_id);
            //if (!$this->block_result["success"])
           // {
            //    throw new Exception("No records found.");
            //}
            $result_arr = $input_params["get_notification_details"];

            if (is_array($result_arr) && count($result_arr) > 0)
            {
                $i = 0;
                foreach ($result_arr as $data_key => $data_arr)
                {
                   
                    $data =array();
                    $data = $data_arr["user_image"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    //$p_key = ($data_arr["ui_users_id"] != "") ? $data_arr["ui_users_id"] : $input_params["ui_users_id"];
                    //$image_arr["pk"] = $p_key;
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = "time_2_beat/user_profile";
                    $data = $this->general->get_image_aws($image_arr);
                    $result_arr[$data_key]["user_image"] = $data;

                    $i++;
                }

                $this->block_result["data"] = $result_arr;
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        //print_r($this->block_result["data"]);
        $input_params["get_notification_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params["get_notification_details"],$input_params);
        return $input_params;
    }

   

/**
     * delete_notification method is used to initiate api execution flow.
     * @created aditi billore | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function delete_notification($request_arr = array())
    {
      try
        {
           
            $output_response = array();
            $output_array = $func_array = array();
            $input_params = $request_arr;
           

	if(isset($input_params["notification_id"])){
 	  $arrCondition = $this->check_notification_exist($input_params);

            if (false == empty($arrCondition["checknotificationtatus"]["status"]))
            {
               
               $input_params = $this->delete_selected_notification($input_params);
               if ($input_params["affected_rows"])
                {
                    $output_response = $this->delete_notification_finish_success($input_params);
                    return $output_response;
                }else{
                    $output_response = $this->delete_notification_finish_success_1($input_params);
                    return $output_response;
                }
              
            }

            else
            {
                $output_response = $this->delete_notification_finish_success_1($input_params);
                return $output_response;
            }
          }else{
		$input_params = $this->delete_all_notification($request_arr);
               if ($input_params["affected_rows"])
                {
                    $output_response = $this->delete_notification_finish_success($input_params);
                    return $output_response;
                }else{
                    $output_response = $this->delete_notification_finish_success_1($input_params);
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
     * delete review method is used to process review block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function delete_selected_notification($input_params = array())
    {
      $this->block_result = array();
        try
        {
            $arrResult = array();
           
            $where_arr['notification_id']  = isset($input_params["notification_id"]) ? $input_params["notification_id"] : "";
            $this->block_result = $this->notification_model->delete_notification($where_arr);

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
        $input_params["delete_notification"] = $result_arr;
        
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
       
       return $input_params;

    }

    public function delete_all_notification($input_params = array())
    {
      $this->block_result = array();
        try
        {
            $arrResult = array();
           
            $where_arr['user_id']  = isset($input_params["user_id"]) ? $input_params["user_id"] : "";
            $this->block_result = $this->notification_model->delete_notification_by_user($where_arr);

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
        $input_params["delete_notification"] = $result_arr;
        
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
       
       return $input_params;

    }

    /**
     * notification_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified Devangi Nirmal | 11.06.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function notification_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "Succesfully fetched"
        );
        $output_fields = array(
            'notification_id',
            'notification_type',
            'message',
            'notification_user_id',
            'user_name',
            'user_image',
            'request_id',
            'user_status'
        );
        $output_keys = array(
            'get_notification_details',
        );

        $output_array["settings"] = array_merge($this->settings_params, $setting_fields);
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "notification_list";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * notification_finish_success_3 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified Devangi Nirmal | 04.06.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function notification_finish_success_3($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "notification_finish_success_3",
        );
        $output_fields = array();

        $output_array["settings"] = array_merge($this->settings_params, $setting_fields);
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "notification_list";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * messages_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function messages_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "messages_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "send_message";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

  
     /**
     * check_notification_exist method is used to process custom function.
     * @created priyanka chillakuru | 25.09.2019
     * @modified saikumar anantham | 08.10.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function check_notification_exist($input_params = array())
    {

        if (!method_exists($this, "checkNotificationExist"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->checkNotificationExist($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        //print_r($format_arr);exit;
        $input_params["checknotificationtatus"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        //print_r($input_params);
        return $input_params;
    }

    /**
     * delete_notification_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function delete_notification_finish_success_1($input_params = array())
    {
     $setting_fields = array(
            "success" => "0",
            "message" => "Id not exists",

        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "delete_notification";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }


    /**
     * delete_notification_finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function delete_notification_finish_success($input_params = array())
    {
     $setting_fields = array(
            "success" => "1",
            "message" => "Deleted successfully",

        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "delete_notification";
        $func_array["function"]["single_keys"] = $this->single_keys;


        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

}
