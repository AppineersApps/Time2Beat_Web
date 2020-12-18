<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of States List Controller
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage controllers
 *
 * @module States List
 *
 * @class States_list.php
 *
 * @path application\webservice\basic_appineers_master\controllers\States_list.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 18.09.2019
 */

class Get_user_profile_details extends Cit_Controller
{
    public $settings_params;
    public $output_params;
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
            "get_user_profile_details_v1",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('get_user_profile_details_model');
        $this->load->model('friends/friends_model');
        $this->load->model('rides/rides_model');
    }

    /**
     * rules_states_list method is used to validate api input params.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_get_user_profile_details($request_arr = array())
    {
        $valid_arr = array();
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "get_user_profile_details");

        return $valid_res;
    }

    /**
     * start_states_list method is used to initiate api execution flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_get_user_profile_details($request_arr = array(), $inner_api = FALSE)
    {
        try
        {

            $validation_res = $this->rules_get_user_profile_details($request_arr);
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
            
            $input_params = $this->get_user_profile_details($input_params);
            $condition_res = $this->condition($input_params);
            if ($condition_res["success"])
            {

                $output_response = $this->get_user_profile_details_finish_success($input_params);
                return $output_response;
            }

            else
            {

                $output_response = $this->get_user_profile_details_finish_success_1($input_params);
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
     * get_user_profile_details method is used to process query block.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_user_profile_details($input_params = array())
    {
    	 
        $this->block_result = array();
        try
        {
            $other_user_id = isset($input_params["other_user_id"]) ? $input_params["other_user_id"] : "";
            $user_id = isset($input_params["user_id"]) ? $input_params["user_id"] : "";
            //print_r($user_id); exit;
            $this->block_result = $this->get_user_profile_details_model->get_user_profile_details($other_user_id,$user_id);
            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
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
                    if( false == empty($data)){
                       $result_arr[$data_key]["u_profile_image"] = $data; 
                    }
                    

                    
                    if(isset($input_params["other_user_id"]))
                    { 
                        $user_id = $input_params['user_id'];
                        $other_user_id = $input_params['other_user_id'];

                        $friendship_status = $this->get_users_friendship_details($user_id, $other_user_id);
                        if($friendship_status[0]['u_friendship_status'] == ''){
                         $result_arr[$data_key]["u_friendship_status"] = "NO_FRIEND";
                        }
                        else{
                            $result_arr[$data_key]["u_friendship_status"] = $friendship_status[0]['u_friendship_status'];    
                        }

                        $block_status = $this->get_users_block_details($user_id, $other_user_id);
                        if($block_status[0]['u_block_status'] == ''){
                         $result_arr[$data_key]["u_block_status"] = "NO_BLOCK";
                        }
                        else{
                            $result_arr[$data_key]["u_block_status"] = $block_status[0]['u_block_status'];    
                        }


                        $friend_data = $this->get_friend_request_details($user_id, $other_user_id);
                        // print_r($friend_data);exit;
                        $result_arr[$data_key]["request_id"] = $friend_data[0]['request_id'];    
                        
                    }
                    $this->ride_result = array();

                    $this->ride_result = $this->rides_model->get_ride_details($input_params);
        // print_r($this->ride_result);exit;
                    
                    $result_arr[$data_key]['ride_history'] = $this->ride_result["data"];
                   
                    $i++;
                }
                $this->block_result["data"] = $result_arr;
            }
        // print_r($this->block_result);exit;
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["get_user_profile_details_v1"] = $this->block_result["data"];

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

    /**
     * condition method is used to process conditions.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function condition($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["get_user_profile_details_v1"]) ? 0 : 1);
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
     * mod_state_finish_success method is used to process finish flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function get_user_profile_details_finish_success($input_params = array())
    {
    	 //print_r($input_params);exit;
        $setting_fields = array(
            "success" => "1",
            "message" => "get_user_profile_details_finish_success",
        );
        $output_fields = array(
            'u_user_id',
            'u_first_name',
            'u_last_name',
            'u_email',
            'u_mobile_no',
            'u_profile_image',
            'u_address',
            'u_city',
            'u_latitude',
            'u_longitude',
            'u_state_id',
            'u_zip_code',
            'u_state',
            'total_rides',
            'total_friends',
            'u_friendship_status',
            'u_block_status',
            'u_ride_history',
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

            "request_id",

        );
        $output_keys = array(
            'get_user_profile_details_v1',
        );
        $ouput_aliases = array(
            //"get_updated_details" => "get_user_details",
           //"get_user_profile_details_v1" => "get_user_profile_details",
            "u_user_id" => "user_id",
            "u_first_name" => "first_name",
            "u_last_name" => "last_name",
            "u_user_name" => "user_name",
            "u_email" => "email",
            "u_mobile_no" => "mobile_no",
            "u_profile_image" => "profile_image",
            "u_address" => "address",
            "u_city" => "city",
            "u_latitude" => "latitude",
            "u_longitude" => "longitude",
            "u_state_id" => "state_id",
            "u_zip_code" => "zip_code",
            "u_state" => "state",
            "total_rides" => "total_rides",
            "total_friends" => "total_friends",
            "u_friendship_status" => "friendship_status",
            "u_block_status" => "block_status",
            "u_ride_history" => "ride_history",

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
            "request_id"=> "request_id",
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_user_profile_details";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);


        $arrSubcategory = array();
        
        $arrSubcategory["settings"] = $responce_arr["settings"];
        
        $arrSubcategory['data']['user_id'] = $responce_arr['data']['0']['user_id'];
        $arrSubcategory['data']['first_name'] = $responce_arr['data']['0']['first_name'];
        $arrSubcategory['data']['last_name'] = $responce_arr['data']['0']['last_name'];
        $arrSubcategory['data']['user_name'] = $responce_arr['data']['0']['user_name'];
        $arrSubcategory['data']['email'] = $responce_arr['data']['0']['email'];
        $arrSubcategory['data']['mobile_no'] = $responce_arr['data']['0']['mobile_no'];
        $arrSubcategory['data']['profile_image'] = $responce_arr['data']['0']['profile_image'];
        $arrSubcategory['data']['address'] = $responce_arr['data']['0']['address'];
        $arrSubcategory['data']['city'] = $responce_arr['data']['0']['city'];
        $arrSubcategory['data']['latitude'] = $responce_arr['data']['0']['latitude'];
        $arrSubcategory['data']['longitude'] = $responce_arr['data']['0']['longitude'];
        $arrSubcategory['data']['state_id'] = $responce_arr['data']['0']['state_id'];
        $arrSubcategory['data']['zip_code'] = $responce_arr['data']['0']['zip_code'];
        $arrSubcategory['data']['state'] = $responce_arr['data']['0']['state'];
        $arrSubcategory['data']['friendship_status'] = $responce_arr['data']['0']['friendship_status'];
        $arrSubcategory['data']['block_status'] = $responce_arr['data']['0']['block_status'];
        $arrSubcategory['data']['total_rides'] = $responce_arr['data']['0']['total_rides'];
        $arrSubcategory['data']['total_friends'] = $responce_arr['data']['0']['total_friends'];
        $arrSubcategory['data']['request_id'] = $responce_arr['data']['0']['request_id'];
        $arrSubcategory['data']['ride_history'] = array();
            // print_r($input_params['get_user_profile_details_v1'][0]['ride_history']);exit;
        foreach ($input_params['get_user_profile_details_v1'][0]['ride_history'] as $key=>$value)
        {
            if(false == empty($value['ride_id']))
            {
              $arrSubcategory['data']['ride_history'][$key]['ride_id'] = $value['ride_id'];
              $arrSubcategory['data']['ride_history'][$key]['note'] = $value['note'];
              $arrSubcategory['data']['ride_history'][$key]['start_loc_id'] = $value['start_loc_id'];
              $arrSubcategory['data']['ride_history'][$key]['end_loc_id'] = $value['end_loc_id'];
              $arrSubcategory['data']['ride_history'][$key]['start_date_time'] = $value['start_date_time'];
              $arrSubcategory['data']['ride_history'][$key]['end_date_time'] = $value['end_date_time'];
              $arrSubcategory['data']['ride_history'][$key]['eta'] = $value['eta'];
              $arrSubcategory['data']['ride_history'][$key]['ride_time'] = $value['ride_time'];
              $arrSubcategory['data']['ride_history'][$key]['sl_address'] = $value['sl_address'];
              $arrSubcategory['data']['ride_history'][$key]['sl_city'] = $value['sl_city'];
              $arrSubcategory['data']['ride_history'][$key]['sl_latitude'] = $value['sl_latitude'];
              $arrSubcategory['data']['ride_history'][$key]['sl_longitude'] = $value['sl_longitude'];
              $arrSubcategory['data']['ride_history'][$key]['sl_state'] = $value['sl_state'];
              $arrSubcategory['data']['ride_history'][$key]['sl_zipcode'] = $value['sl_zipcode'];
              $arrSubcategory['data']['ride_history'][$key]['el_address'] = $value['el_address'];
              $arrSubcategory['data']['ride_history'][$key]['el_city'] = $value['el_city'];
              $arrSubcategory['data']['ride_history'][$key]['el_latitude'] = $value['el_latitude'];
              $arrSubcategory['data']['ride_history'][$key]['el_longitude'] = $value['el_longitude'];
              $arrSubcategory['data']['ride_history'][$key]['el_state'] = $value['el_state'];
              $arrSubcategory['data']['ride_history'][$key]['el_zipcode'] = $value['el_zipcode'];
            }

        }        



        return $arrSubcategory;





        // return $responce_arr;
    }

    /**
     * mod_state_finish_success_1 method is used to process finish flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function get_user_profile_details_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "get_user_profile_details_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_user_profile_details";
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
