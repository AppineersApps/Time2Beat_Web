<?php  
            
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of User Email Confirmation Controller
 * 
 * @category webservice
 *            
 * @package basic_appineers_master
 * 
 * @subpackage controllers 
 * 
 * @module User Email Confirmation
 * 
 * @class User_email_confirmation.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\User_email_confirmation.php
 * 
 * @version 4.4
 *
 * @author CIT Dev Team
 * 
 * @since 18.09.2019
 */ 
 
class User_email_confirmation extends Cit_Controller
{
    public $settings_params;
    public $output_params;
    public $single_keys;
    public $multiple_keys;
    public $block_result;
      
    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct() {
        parent::__construct();
        $this->settings_params = array();
        $this->output_params = array();
        $this->single_keys = array("get_user","update_confirmation");
        $this->multiple_keys = array("custom_function");
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('user_email_confirmation_model');
        $this->load->model("basic_appineers_master/users_model");
    }
      
    /**
     * rules_user_email_confirmation method is used to validate api input params.
     * @created priyanka chillakuru | 10.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_user_email_confirmation($request_arr = array()){
        $valid_arr = array(
                "confirmation_code" => array(
                    array(
                        "rule" => "required",
                        "value" => TRUE,
                        "message" => "confirmation_code_required"
                    )
                )
            );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "user_email_confirmation");
        
        return $valid_res;
    }
    
    /**
     * start_user_email_confirmation method is used to initiate api execution flow.
     * @created priyanka chillakuru | 10.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_user_email_confirmation($request_arr  = array(), $inner_api = FALSE) {
        try {
            $validation_res = $this->rules_user_email_confirmation($request_arr);
            if ($validation_res["success"] == "-5") {
                if($inner_api === TRUE){
                    return $validation_res;
                } else {
                    $this->wsresponse->sendValidationResponse($validation_res);
                }
            }
            $output_response = array();
            $input_params = $validation_res['input_params'];
            $output_array = $func_array = array();
            
        
        $input_params = $this->custom_function($input_params);
        
    
        $input_params = $this->get_user($input_params);
        
    
        $condition_res = $this->is_user_found($input_params);
        
        if($condition_res["success"]) {
        
    
        $output_response = $this->users_finish_success_2($input_params);
        return $output_response;
        
    
        }
    
        else {
        
    
        $condition_res = $this->check_for_activation($input_params);
        
        if($condition_res["success"]) {
        
    
        $output_response = $this->users_finish_success($input_params);
        return $output_response;
        
    
        }
    
        else {
        
    
        $input_params = $this->update_confirmation($input_params);
        
    
        $output_response = $this->users_finish_success_1($input_params);
        return $output_response;
        
    
        }
        
    
        }
        
        
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return $output_response;
    }
    
                                
    /**
     * custom_function method is used to process custom function.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 12.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function custom_function($input_params = array())
    {
                    
            if (!method_exists($this, "prepareDecodeEmailVerification")) {
                $result_arr["data"] = array();
            } else {
                $result_arr["data"] = $this->prepareDecodeEmailVerification($input_params);
            }
            $format_arr = $result_arr;
            
            $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
            $input_params["custom_function"] = $format_arr;
            
            $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }
                                
    /**
     * get_user method is used to process query block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 12.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_user($input_params = array())
    {
        
            $this->block_result = array();
            try {
                
            $email = isset($input_params["email"]) ? $input_params["email"] : "";
            $this->block_result = $this->users_model->get_user($email);
            
            if(!$this->block_result["success"]){
                throw new Exception("No records found.");
            }
            } catch (Exception $e) {
                $success = 0;
                $this->block_result["data"] = array();
            }
            $input_params["get_user"] = $this->block_result["data"];
            $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
            
        return $input_params;
    }

    /**
     * is_user_found method is used to process conditions.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 12.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_user_found($input_params = array())
    {
        
            $this->block_result = array();
            try {
                
            $cc_lo_0 = (empty($input_params["get_user"]) ? 0 : 1);
            $cc_ro_0 = 1;
            
            $cc_fr_0 = ($cc_lo_0 == $cc_ro_0) ? TRUE : FALSE;    
            
            if(!$cc_fr_0){
                throw new Exception("Some conditions does not match.");
            }
            $cc_lo_1 = $input_params["u_status"];
            $cc_ro_1 = "Active";
            
            $cc_in_1 = (is_array($cc_ro_1)) ? $cc_ro_1 : explode(",", $cc_ro_1);
            $cc_fr_1 = (in_array($cc_lo_1, $cc_in_1)) ? TRUE : FALSE;    
            
            if(!$cc_fr_1){
                throw new Exception("Some conditions does not match.");
            }
                $success = 1;
                $message = "Conditions matched.";
            } catch (Exception $e) {
                $success = 0;
                $message = $e->getMessage();
            }
            $this->block_result["success"] = $success;
            $this->block_result["message"] = $message;
            return $this->block_result;
            
    }

    /**
     * users_finish_success_2 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 12.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success_2($input_params = array())
    {
        
            $setting_fields = array(
                "success" => "0", 
                "message" => "users_finish_success_2"
            );
            $output_fields = array();
            
            $output_array["settings"] = $setting_fields;
            $output_array["settings"]["fields"] = $output_fields;
            $output_array["data"] = $input_params;
                        
            $func_array["function"]["name"] = "user_email_confirmation";
            $func_array["function"]["single_keys"] = $this->single_keys;
            $func_array["function"]["multiple_keys"] = $this->multiple_keys;
            
            $this->wsresponse->setResponseStatus(200);
            
            $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);
            
        return $responce_arr;
    }

    /**
     * check_for_activation method is used to process conditions.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 12.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function check_for_activation($input_params = array())
    {
        
            $this->block_result = array();
            try {
                
            $cc_lo_0 = (empty($input_params["get_user"]) ? 0 : 1);
            $cc_ro_0 = 0;
            
            $cc_fr_0 = ($cc_lo_0 == $cc_ro_0) ? TRUE : FALSE;    
            
            if(!$cc_fr_0){
                throw new Exception("Some conditions does not match.");
            }
                $success = 1;
                $message = "Conditions matched.";
            } catch (Exception $e) {
                $success = 0;
                $message = $e->getMessage();
            }
            $this->block_result["success"] = $success;
            $this->block_result["message"] = $message;
            return $this->block_result;
            
    }

    /**
     * users_finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 12.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success($input_params = array())
    {
        
            $setting_fields = array(
                "success" => "0", 
                "message" => "users_finish_success"
            );
            $output_fields = array();
            
            $output_array["settings"] = $setting_fields;
            $output_array["settings"]["fields"] = $output_fields;
            $output_array["data"] = $input_params;
                        
            $func_array["function"]["name"] = "user_email_confirmation";
            $func_array["function"]["single_keys"] = $this->single_keys;
            $func_array["function"]["multiple_keys"] = $this->multiple_keys;
            
            $this->wsresponse->setResponseStatus(200);
            
            $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);
            
        return $responce_arr;
    }
                                
    /**
     * update_confirmation method is used to process query block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 12.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function update_confirmation($input_params = array())
    {
        
            $this->block_result = array();
            try {
                
            $params_arr = $where_arr = array();
            if(isset($input_params["u_user_id"])){
                $where_arr["u_user_id"] = $input_params["u_user_id"];
            }
            if(isset($input_params["confirmation_code"])){
                $where_arr["confirmation_code"] = $input_params["confirmation_code"];
            }
            $params_arr["_estatus"] = "Active";
            $params_arr["_eemailverified"] = "Yes";
            $params_arr["_dtupdatedat"] = "NOW()";
            $this->block_result = $this->users_model->update_confirmation($params_arr, $where_arr);
            
            } catch (Exception $e) {
                $success = 0;
                $this->block_result["data"] = array();
            }
            $input_params["update_confirmation"] = $this->block_result["data"];
            $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
            
        return $input_params;
    }

    /**
     * users_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 12.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success_1($input_params = array())
    {
        
            $setting_fields = array(
                "success" => "1", 
                "message" => "users_finish_success_1"
            );
            $output_fields = array();
            
            $output_array["settings"] = $setting_fields;
            $output_array["settings"]["fields"] = $output_fields;
            $output_array["data"] = $input_params;
                        
            $func_array["function"]["name"] = "user_email_confirmation";
            $func_array["function"]["single_keys"] = $this->single_keys;
            $func_array["function"]["multiple_keys"] = $this->multiple_keys;
            
            $this->wsresponse->setResponseStatus(200);
            
            $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);
            
        return $responce_arr;
    }
    
}