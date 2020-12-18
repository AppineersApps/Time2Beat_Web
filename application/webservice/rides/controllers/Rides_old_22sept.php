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

class Rides extends Cit_Controller
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
            "set_ride",
            "get_ride_details",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('rides_model');
    }


    /**
     * start_set_store_review method is used to initiate api execution flow.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_rides($request_arr = array(), $inner_api = FALSE)
    {
        // print_r($request_arr);exit;
        // get the HTTP method, path and body of the request
        $method = $_SERVER['REQUEST_METHOD'];
        $output_response = array();

        switch ($method) {
          case 'GET':
            $output_response =  $this->get_ride($request_arr);     
            return  $output_response;
             break;
          case 'PUT':
                $output_response =  $this->update_ride($request_arr);
            return $output_response;
            break;
          case 'POST':
            $output_response =  $this->add_ride($request_arr);   
            
            
            return  $output_response;
            break;

            case 'DELETE':
            $output_response = $this->get_deleted_ride($request_arr);
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
    public function rules_add_ride($request_arr = array())
    {       
        $valid_arr = array(
            "start_location" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "start_location_required",
                )
            ),
            "end_location" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "end_location_required",
                )
            ),
            "start_time" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "start_time_required",
                )
            ),
            "end_time" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "end_time_required",
                )
            ),
            "eta" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "eta_required",
                )
            ),
            "ride_time" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "ride_time_required",
                )
            ),
            );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "add_ride");

        return $valid_res;
    }

    public function add_ride($input_params){
        
        try
        {
        
            $validation_res = $this->rules_add_ride($input_params);
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
            // $input_params = $validation_res['input_params'];
            
            

            $output_response = array();
           
            $output_array = $func_array = array();
            $input_params = $this->check_ride_details($input_params);

            $input_params = $this->set_ride($input_params);

            $condition_res = $this->is_posted($input_params);

            if ($condition_res["success"])
            {
                $input_params = $this->get_all_ride($input_params);
                
                $output_response = $this->user_ride_finish_success($input_params);
                return $output_response;
            }

            else
            {

                $output_response = $this->user_ride_finish_success_1($input_params);
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
     * custom_function method is used to process custom function.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 31.10.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function custom_image_function($input_params = array())
    {
       if (!method_exists($this, "uploadQueryImages"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->uploadQueryImages($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["custom_image_function"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * set_store_review method is used to process review block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function set_ride($input_params = array())
    {
        $this->block_result = array();
        try
        {
            $params_arr = array();
            
            if (isset($input_params["user_id"]))
            {
                $params_arr["user_id"] = $input_params["user_id"];
            }
            if (isset($input_params["notes"]))
            {
                $params_arr["notes"] = $input_params["notes"];
            }
            if (isset($input_params["start_time"]))
            {
                $params_arr["start_time"] = $input_params["start_time"];
            }
            if (isset($input_params["end_time"]))
            {
                $params_arr["end_time"] = $input_params["end_time"];
            }
            if (isset($input_params["eta"]))
            {
                $params_arr["eta"] = $input_params["eta"];
            }
            if (isset($input_params["ride_time"]))
            {
                $params_arr["ride_time"] = $input_params["ride_time"];
            }
            if (isset($input_params["start_location_id"]))
            {
                $params_arr["start_location_id"] = $input_params["start_location_id"];
            }
            if (isset($input_params["distance_bias"]))
            {
                $params_arr["distance_bias"] = $input_params["distance_bias"];
            }
            if (isset($input_params["end_location_id"]))
            {
                $params_arr["end_location_id"] = $input_params["end_location_id"];
            }
       
            $this->block_result = $this->rides_model->set_ride($params_arr);

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
        $input_params["set_ride"] = $this->block_result["data"];
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
            $cc_lo_0 = (is_array($input_params["ride_id"])) ? count($input_params["ride_id"]):$input_params["ride_id"];
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
    public function user_ride_finish_success($input_params = array())
    {
        $output_arr['settings']['success'] = "1";
        $output_arr['settings']['message'] = "Ride added successfully";
        // $output_arr['data'] = "";

        $output_fields = array(
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
            

        );
        $output_keys = array(
            'get_all_ride',
        );
        $ouput_aliases = array(

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
        );

        // $output_array["settings"] = array_merge($this->settings_params, $setting_fields);
        // $output_array["settings"]["fields"] = $output_fields;
        $output_arr['data'] = $input_params['get_all_ride'];


        $responce_arr = $this->wsresponse->sendWSResponse($output_arr, array(), "add_ride");

        return $responce_arr;
    }

    /**
     * user_review_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function user_ride_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "user_ride_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "add_ride";
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
    public function rules_update_ride($request_arr = array())
    {
        
         $valid_arr = array(            
            "ride_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "ride_id_required",
                )
            )
            );
        
        
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "update_ride");

        return $valid_res;
    }
    /**
     * rules_set_store_review method is used to validate api input params.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_get_ride($request_arr = array())
    {
        
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "get_ride");

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
public function get_ride($request_arr = array(), $inner_api = FALSE)
    {
       try
        {
            $validation_res = $this->rules_get_ride($request_arr);
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
            if(isset($input_params['radius']) && $input_params['radius'] != '' && false == empty($input_params['latitude']) && false == empty($input_params['longitude'])){
               $input_params = $this->prepare_distance($input_params);
            }
           
            $result_params = $this->get_all_ride($input_params);
            //print_r($result_params); exit; 
            $condition_res = $this->is_posted($result_params);
            if ($condition_res["success"])
            {
               
                $output_response = $this->get_ride_finish_success($input_params,$result_params);
                return $output_response;
            }

            else
            {
 
                $output_response = $this->get_ride_finish_success_1($result_params);
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
     * prepare_distance method is used to process custom function.
     * @created priyanka chillakuru | 11.06.2019
     * @modified priyanka chillakuru | 21.06.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function prepare_distance($input_params = array())
    {
        if (!method_exists($this, "prepareDistanceQuery"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->prepareDistanceQuery($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["prepare_distance"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

     /**
     * start_edit_profile method is used to initiate api execution flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
   public function update_ride($request_arr = array(), $inner_api = FALSE)
    {

        try
        {
            $validation_res = $this->rules_update_ride($request_arr);
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

            $input_params = $this->check_ride_exist($input_params);
            if ($input_params["checkrideexist"]["status"])
            {


                $input_params = $this->update_exist_ride($input_params);
                if ($input_params["affected_rows"])
                {
                    $output_response = $this->get_update_finish_success($input_params);
                    return $output_response;
                    
                }else{
                    $output_response = $this->get_update_finish_success_1($input_params);
                    return $output_response;
                }
            }
            else
            {

                $output_response = $this->get_update_finish_success_1($input_params);
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
     * get_updated_details method is used to process query block.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_helper_image($input_params = array())
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
            //print_r( $result_arr); exit;
                $data =array();
                $folder_name="side_jobs/helper_image/".$arrResult['helper_id']."/";
                $insert_arr = array();
                $new_file_name="helper_image";
                  if(false == empty($_FILES[$new_file_name]['name']))
                  {
                    if(false == empty($result_arr['0'][$new_file_name]))
                    {
                        $file_name = $result_arr['0'][$new_file_name];
                        $res = $this->general->deleteAWSFileData($folder_name,$file_name);
                        if($res)
                        {
                          $insert_arr['vProfileImage'] = '';
                        }
                    }
                  
                  }                       
                
                if(is_array($insert_arr) && false == empty($insert_arr))
                {
                  $this->db->where('iHelperId', $arrResult['helper_id']);
                  $this->db->update("helper",$insert_arr);
                }
                $this->block_result["data"] = $result_arr;
            
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        //$input_params["get_updated_details"] = $this->block_result["data"];
        //$input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
        return $input_params;
    }



     /**
     * update_profile method is used to process query block.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function update_exist_ride($input_params = array())
    {
        $this->block_result = array();
        try
        {
            $params_arr = array();

            if (isset($input_params["ride_id"]))
            {
                 $where_arr["ride_id"] = $input_params["ride_id"];
            }
           
            if (isset($input_params["notes"]))
            {
                $params_arr["notes"] = $input_params["notes"];
            }
            $this->block_result = $this->rides_model->update_ride($params_arr, $where_arr);
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
        $input_params["update_ride"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
        return $input_params;
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
            $input_params = $this->checkRideDetails($input_params);
        }
        
        return $input_params;
    }
    

 /**
     * checkuniqueusername method is used to process custom function.
     * @created priyanka chillakuru | 25.09.2019
     * @modified saikumar anantham | 08.10.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_provider_for_service($input_params = array())
    {

        if (!method_exists($this, "getProviderForService"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->getProviderForService($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["provider_id"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        // print_r($input_params);
        return $input_params;
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
               
            // if(isset($input_params['ride_id'])){
            //     if(isset($input_params['user_id'])){
            //         $input_params = $this->get_rides_for_user($input_params);
            //     }
            // }
             $input_params["radius"] = isset($input_params["radius"]) ? $input_params["radius"] : "";
             $input_params["distance"] = isset($input_params["distance"]) ? $input_params["distance"] : "";
            $this->block_result = $this->rides_model->get_ride_details($input_params,$this->settings_params);
        
            $result_arr = $this->block_result["data"];

            if (is_array($result_arr) && count($result_arr) > 0)
            {
               
                $this->block_result["data"] = $result_arr;
            }
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
        $input_params["get_all_ride"] = $this->block_result["data"];
        
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
       public function get_ride_finish_success($input_params = array(),$result_params = array())
    {
       // print_r($input_params); exit;
        $setting_fields = array(
            "success" => "1",
            "message" => "get_ride_finish_success",
        );
      
        $output_fields = array(
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
            

        );
        $output_keys = array(
            'get_all_ride',
        );
        $ouput_aliases = array(

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
        );

        $output_array["settings"] = array_merge($this->settings_params, $setting_fields);
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $result_params;
        //print_r($input_params);exit;

        $func_array["function"]["name"] = "get_ride";
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
    public function get_ride_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "get_ride_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_ride";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

     /**
     * user_review_finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function get_update_finish_success($input_params = array())
    {
       
        $setting_fields = array(
            "success" => "1",
            "message" => "get_update_finish_success"
        );

        $output_array["settings"] = $setting_fields;
        //print_r($input_params);exit;

        $func_array["function"]["name"] = "update_ride";
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
    public function get_update_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "get_update_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "update_ride";
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
        // print_r($input_params);
        return $input_params;
    }
    
    public function get_deleted_ride($request_arr = array())
    {
        // print_r($request_arr); exit;
      try
        {
            $output_response = array();
            $output_array = $func_array = array();
            $input_params = $request_arr;

            $condition_res = $this->check_ride_exist($input_params);
           // print_r($condition_res); exit;
           
            if ($condition_res["checkrideexist"]["status"])
            {

               $input_params = $this->delete_ride($input_params);
               //print_r($input_params); exit;
               $output_response = $this->delete_ride_finish_success($input_params);
                return $output_response;
            }

            else
            {
                $output_response = $this->delete_ride_finish_success_1($input_params);
                return $output_response;
            }
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
    }

    public function delete_ride($input_params = array())
    {
        //print_r($input_params); exit;
        $this->block_result = array();
        try
        {
            $arrResult = array();
           
            $arrResult['ride_id']  = isset($input_params["ride_id"]) ? $input_params["ride_id"] : "";
            $arrResult['dtUpdatedAt']  = "NOW()";
            
            $this->block_result = $this->rides_model->delete_ride($arrResult);
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
        $input_params["delete_ride"] = $this->block_result["data"];
        
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
    public function delete_ride_finish_success($input_params = array())
    {
     $setting_fields = array(
            "success" => "1",
            "message" => "delete_ride_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "delete_ride";
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
    public function delete_ride_finish_success_1($input_params = array())
    {
     $setting_fields = array(
            "success" => "0",
            "message" => "delete_ride_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "delete_ride";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
   
   

}
