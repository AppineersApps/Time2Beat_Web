<?php  
            
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of delete_api_log Controller
 * 
 * @category webservice
 *            
 * @package misc
 * 
 * @subpackage controllers 
 * 
 * @module delete_api_log
 * 
 * @class Delete_api_log.php
 * 
 * @path application\webservice\misc\controllers\Delete_api_log.php
 * 
 * @version 4.4
 *
 * @author CIT Dev Team
 * 
 * @since 29.09.2020
 */ 
 
class Delete_api_log extends Cit_Controller
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
        $this->single_keys = array("delete_old_api_log_data");
        $this->multiple_keys = array("fetch_log_days","fetch_log_files","delete_log_files_from_server");
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('delete_api_log_model');
        $this->load->model("tools/api_accesslogs_model");
    }
      
    /**
     * rules_delete_api_log method is used to validate api input params.
     * @created Devangi Nirmal | 29.09.2020
     * @modified Devangi Nirmal | 29.09.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_delete_api_log($request_arr = array()){
        $valid_arr = array(
            );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "delete_api_log");
        
        return $valid_res;
    }
    
    /**
     * start_delete_api_log method is used to initiate api execution flow.
     * @created Devangi Nirmal | 29.09.2020
     * @modified Devangi Nirmal | 29.09.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_delete_api_log($request_arr  = array(), $inner_api = FALSE) {
        try {
            $validation_res = $this->rules_delete_api_log($request_arr);
            if ($validation_res["success"] == "-5") {
                if($inner_api === TRUE){
                    return $validation_res;
                } else {
                    $this->wsresponse->sendValidationResponse($validation_res);
                }
            }
            $output_response = array();
            $input_params = $validation_res['input_params'];
            
        
        $input_params = $this->fetch_log_days($input_params);
        
    
        $input_params = $this->fetch_log_files($input_params);
        
    
        $input_params = $this->delete_old_api_log_data($input_params);
        
    
        $input_params = $this->delete_log_files_from_server($input_params);
        
    
        $output_response = $this->api_accesslogs_finish_success($input_params);
        return $output_response;
        
        
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return $output_response;
    }
    
                                
    /**
     * fetch_log_days method is used to process custom function.
     * @created Devangi Nirmal | 29.09.2020
     * @modified Devangi Nirmal | 29.09.2020
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function fetch_log_days($input_params = array())
    {
                    
            if (!method_exists($this, "fetchDays")) {
                $result_arr["data"] = array();
            } else {
                $result_arr["data"] = $this->fetchDays($input_params);
            }
            $format_arr = $result_arr;
            
            $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
            $input_params["fetch_log_days"] = $format_arr;
            
            $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }
                                
    /**
     * fetch_log_files method is used to process query block.
     * @created Devangi Nirmal | 29.09.2020
     * @modified Devangi Nirmal | 29.09.2020
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function fetch_log_files($input_params = array())
    {
        
            $this->block_result = array();
            try {
                
            $days = isset($input_params["days"]) ? $input_params["days"] : "";
            $this->block_result = $this->api_accesslogs_model->fetch_log_files($days);
            
            if(!$this->block_result["success"]){
                throw new Exception("No records found.");
            }
            } catch (Exception $e) {
                $success = 0;
                $this->block_result["data"] = array();
            }
            $input_params["fetch_log_files"] = $this->block_result["data"];
            
        return $input_params;
    }
                                
    /**
     * delete_old_api_log_data method is used to process query block.
     * @created Devangi Nirmal | 29.09.2020
     * @modified Devangi Nirmal | 29.09.2020
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function delete_old_api_log_data($input_params = array())
    {
        
            $this->block_result = array();
            try {
                
            $days = isset($input_params["days"]) ? $input_params["days"] : "";
            $this->block_result = $this->api_accesslogs_model->delete_old_api_log_data($days);
            
            } catch (Exception $e) {
                $success = 0;
                $this->block_result["data"] = array();
            }
            $input_params["delete_old_api_log_data"] = $this->block_result["data"];
            $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
            
        return $input_params;
    }
                                
    /**
     * delete_log_files_from_server method is used to process custom function.
     * @created Devangi Nirmal | 29.09.2020
     * @modified Devangi Nirmal | 29.09.2020
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function delete_log_files_from_server($input_params = array())
    {
                    
            if (!method_exists($this, "deleteLogFiles")) {
                $result_arr["data"] = array();
            } else {
                $result_arr["data"] = $this->deleteLogFiles($input_params);
            }
            $format_arr = $result_arr;
            
            $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
            $input_params["delete_log_files_from_server"] = $format_arr;
            
            $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * api_accesslogs_finish_success method is used to process finish flow.
     * @created Devangi Nirmal | 29.09.2020
     * @modified Devangi Nirmal | 29.09.2020
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function api_accesslogs_finish_success($input_params = array())
    {
        
            $setting_fields = array(
                "success" => "1", 
                "message" => "api_accesslogs_finish_success"
            );
            $output_fields = array('days','aa_file_name','affected_rows');
            $output_keys = array('fetch_log_days','fetch_log_files','delete_old_api_log_data','delete_log_files_from_server');
            
            $output_array["settings"] = $setting_fields;
            $output_array["settings"]["fields"] = $output_fields;
            $output_array["data"] = $input_params;
                        
            $func_array["function"]["name"] = "delete_api_log";
            $func_array["function"]["output_keys"] = $output_keys;
            $func_array["function"]["single_keys"] = $this->single_keys;
            $func_array["function"]["multiple_keys"] = $this->multiple_keys;
            
            $this->wsresponse->setResponseStatus(200);
            
            $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);
            
        return $responce_arr;
    }
    
}