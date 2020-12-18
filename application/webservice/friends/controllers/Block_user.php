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

class Block_user extends Cit_Controller
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
            "set_block_user",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('block_user_model');
        $this->load->model('friends/friends_model');
      }


    /**
     * start_set_store_review method is used to initiate api execution flow.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_block_user($request_arr = array(), $inner_api = FALSE)
    {
        // get the HTTP method, path and body of the request
            // print_r($request_arr);exit;
        $method = $_SERVER['REQUEST_METHOD'];
        $output_response = array();

        switch ($method) {
          case 'GET':
                $output_response =  $this->get_blocked_users($request_arr);           
                return  $output_response;
             break;
          case 'POST':
                $output_response =  $this->block_user($request_arr);
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
    public function rules_block_user($request_arr = array())
    {       
        $valid_arr = array(
            "other_user_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "other_user_id_required",
                )
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "block_user");

        return $valid_res;
    }


    public function block_user($input_params){
        
        try
        {
        
            $validation_res = $this->rules_block_user($input_params);
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
            
            
            $input_params = $this->check_is_user_blocked($input_params);
            // print_r($input_params);exit;
            if ($input_params["checkuserblocked"]["status"])
            {
                    $input_params = $this->delete_block_user($input_params);
                    

                    if ($input_params["affected_rows"])
                    {

                        $output_response = $this->user_friend_unblock_finish_success($input_params);
                        return $output_response;
                    }
                    else
                    {

                        $output_response = $this->user_friend_unblock_finish_success_1($input_params);
                        return $output_response;
                    }
            }
           else
            {
                    //check friendship status if friend then remove friend connection
                
                    $input_params = $this->check_is_user_friend($input_params);
                    // print_r($input_params);exit;
                    if ($input_params["checkuserfriend"]["status"])
                    {
                        $input_params = $this->delete_request($input_params);
                    }


                    $input_params = $this->set_block_user($input_params);
                    $condition_res = $this->is_posted($input_params);
                    // print_r($input_params);exit;
                    
                    if ($condition_res["success"])
                    {

                        $output_response = $this->user_friend_block_finish_success($input_params);
                        return $output_response;
                    }
                    else
                    {

                        $output_response = $this->user_friend_block_finish_success_1($input_params);
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


    public function delete_block_user($input_params = array())
    {
        //print_r($input_params); exit;
        $this->block_result = array();
        try
        {
            $arrResult = array();
           
            $arrResult['blocked_user_id']  = isset($input_params["blocked_user_id"]) ? $input_params["blocked_user_id"] : "";
            $arrResult['dtUpdatedAt']  = "NOW()";
            
            $this->block_result = $this->block_user_model->delete_block_user($arrResult);
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
        $input_params["delete_block_user"] = $this->block_result["data"];
        
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
    public function user_friend_block_finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "user_friend_block_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        // $output_array["settings"]["fields"] = $output_fields;
        // $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "block_user";
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
    public function user_friend_block_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "user_friend_block_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        // $output_array["settings"]["fields"] = $output_fields;
        // $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "block_user";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
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
    public function check_is_user_friend($input_params = array())
    {
        if (!method_exists($this, "checkIsUserFriend"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->checkIsUserFriend($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["checkuserfriend"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        // print_r($input_params);
        return $input_params;
    }


    public function check_is_user_blocked($input_params = array())
    {
        if (!method_exists($this, "checkIsUserBlocked"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->checkIsUserBlocked($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["checkuserblocked"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        // print_r($input_params);
        return $input_params;
    }


    public function user_friend_unblock_finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "user_friend_unblock_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "block_user";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    public function user_friend_unblock_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "user_friend_unblock_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "block_user";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }



 
    /**
     * set_store_review method is used to process review block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function set_block_user($input_params = array())
    {
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
            $params_arr["_dtaddedat"] = "NOW()";
            $params_arr["_dtupdatedat"] = "NOW()";
       
            $this->block_result = $this->block_user_model->set_block_user($params_arr);

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
        $input_params["block_user"] = $this->block_result["data"];
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
            $cc_lo_0 = (is_array($input_params["blocked_user_id"])) ? count($input_params["blocked_user_id"]):$input_params["blocked_user_id"];
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
    public function is_posted_block($input_params = array())
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
     * rules_set_store_review method is used to validate api input params.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_get_blocked_users($request_arr = array())
    {
        
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "get_blocked_users");

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
public function get_blocked_users($request_arr = array(), $inner_api = FALSE)
    {
       try
        {
            $validation_res = $this->rules_get_blocked_users($request_arr);
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
            
            $result_params = $this->get_all_blocked_users($input_params);
            // print_r($result_params); exit; 
            $condition_res = $this->is_posted_block($result_params);
            if ($condition_res["success"])
            {
               
                $output_response = $this->get_block_user_finish_success($input_params,$result_params);
                return $output_response;
            }

            else
            {
 
                $output_response = $this->get_block_user_finish_success_1($result_params);
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
     * get_review_details method is used to process review block.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
     public function get_all_blocked_users($input_params = array())
    {
        // print_r($input_params); exit;
        $this->block_result = array();
        try
        {
               
            $this->block_result = $this->block_user_model->get_all_blocked_users($input_params,$this->settings_params);
            
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
        $input_params["get_all_blocked_users"] = $this->block_result["data"];
        
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
       public function get_block_user_finish_success($input_params = array(),$result_params = array())
    {
       // print_r($result_params); exit;
        $setting_fields = array(
            "success" => "1",
            "message" => "get_block_user_finish_success",
        );
      
        $output_fields = array(
            "u_user_id",
            "u_first_name",
            "u_last_name",
            "u_address",
            "u_city",
            "u_state",
            "u_profile_image",

        );
        $output_keys = array(
            'get_all_blocked_users',
        );
        $ouput_aliases = array(

            "u_user_id"=> "user_id",
            "u_first_name"=> "first_name",
            "u_last_name"=> "last_name",
            "u_address"=> "address",
            "u_city"=> "city",
            "u_state"=> "state",
            "u_profile_image"=> "profile_image",
        );

        $output_array["settings"] = array_merge($this->settings_params, $setting_fields);
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $result_params;
        //print_r($input_params);exit;

        $func_array["function"]["name"] = "get_blocked_users";
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
    public function get_block_user_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "get_block_user_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_blocked_users";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
   

}
