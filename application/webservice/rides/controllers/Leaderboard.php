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

class Leaderboard extends Cit_Controller
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
            "get_leaderboard",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('leaderboard_model');
        $this->load->model('friends/friends_model');
        $this->load->model('users/get_user_profile_details_model');
    }


    /**
     * start_set_store_review method is used to initiate api execution flow.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_leaderboard($request_arr = array(), $inner_api = FALSE)
    {
        // print_r($request_arr);exit;
        // get the HTTP method, path and body of the request
        $method = $_SERVER['REQUEST_METHOD'];
        $output_response = array();

        switch ($method) {
          case 'GET':
            $output_response =  $this->get_leaderboard($request_arr);     
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
    public function rules_get_leaderboard($request_arr = array())
    {
        
        $valid_arr = array(
            "ride_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "ride_id_required",
                )
            ),
           
            );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "get_leaderboard");

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
public function get_leaderboard($request_arr = array(), $inner_api = FALSE)
    {
       try
        {
            $validation_res = $this->rules_get_leaderboard($request_arr);
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
     
            $input_params = $this->get_ride_information($input_params);
            
            $result_params = $this->get_all_leaderboard($input_params);
            // print_r($result_params); exit; 
            $condition_res = $this->is_posted($result_params);
            if ($condition_res["success"])
            {
               
                $output_response = $this->get_leaderboard_finish_success($input_params,$result_params);
                return $output_response;
            }

            else
            {
 
                $output_response = $this->get_leaderboard_finish_success_1($result_params);
                return $output_response;
            }
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
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


    public function get_ride_information($input_params = array())
    {
        if (!method_exists($this, "getRideInformation"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $input_params = $this->getRideInformation($input_params);
        }
        
        return $input_params;
    }
    

    /**
     * get_review_details method is used to process review block.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
     public function get_all_leaderboard($input_params = array())
    {
        // print_r($input_params); exit;
        $this->block_result = array();
        try
        {
               
            $this->block_result = $this->leaderboard_model->get_leaderboard_details($input_params,$this->settings_params);
            // print_r($this->block_result);exit;
            
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
                    $data = $data_arr["u_profile_image"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = "time_2_beat/user_profile";
                    $data = $this->general->get_image_aws($image_arr);
             
                    $result_arr[$data_key]["u_profile_image"] = $data;
             

                    $user_id = $input_params['user_id'];
                    $other_user_id = $data_arr['u_user_id'];

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
               
                $this->block_result["data"] = $result_arr;
                    // print_r($this->block_result);exit;
            }

        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["get_all_leaderboard"] = $this->block_result["data"];
        
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
    public function get_leaderboard_finish_success($input_params = array(),$result_params = array())
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
            'u_friendship_status',
            'u_block_status',
            'u_address',
            'u_city',
            'u_latitude',
            'u_longitude',
            'u_state_id',
            'u_zip_code',
            'u_state',
            'request_id',

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
            'get_all_leaderboard',
        );
        $ouput_aliases = array(

            "u_user_id" => "user_id",
            "u_first_name" => "first_name",
            "u_last_name" => "last_name",
            "u_user_name" => "user_name",
            "u_email" => "email",
            "u_mobile_no" => "mobile_no",
            "u_profile_image" => "profile_image",
            "u_friendship_status" => "friendship_status",
            "u_block_status" => "block_status",
            "u_address" => "address",
            "u_city" => "city",
            "u_latitude" => "latitude",
            "u_longitude" => "longitude",
            "u_state_id" => "state_id",
            "u_zip_code" => "zip_code",
            "u_state" => "state",
            "request_id" => "request_id",

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
        $i = 0;
        foreach ($responce_arr['data'] as $key=>$value)
        {
            if(false == empty($value['ride_id']))
            {
                $i++;
                $arrSubcategory['data'][$key]['ride_rank'] = $i;
                $arrSubcategory['data'][$key]['rider']['user_id'] = $value['user_id'];
                $arrSubcategory['data'][$key]['rider']['first_name'] = $value['first_name'];
                $arrSubcategory['data'][$key]['rider']['last_name'] = $value['last_name'];
                $arrSubcategory['data'][$key]['rider']['user_name'] = $value['user_name'];
                $arrSubcategory['data'][$key]['rider']['email'] = $value['email'];
                $arrSubcategory['data'][$key]['rider']['mobile_no'] = $value['mobile_no'];
                $arrSubcategory['data'][$key]['rider']['profile_image'] = $value['profile_image'];
                $arrSubcategory['data'][$key]['rider']['friendship_status'] = $value['friendship_status'];
                $arrSubcategory['data'][$key]['rider']['block_status'] = $value['block_status'];
                $arrSubcategory['data'][$key]['rider']['address'] = $value['address'];
                $arrSubcategory['data'][$key]['rider']['city'] = $value['city'];
                $arrSubcategory['data'][$key]['rider']['latitude'] = $value['latitude'];
                $arrSubcategory['data'][$key]['rider']['longitude'] = $value['longitude'];
                $arrSubcategory['data'][$key]['rider']['state_id'] = $value['state_id'];
                $arrSubcategory['data'][$key]['rider']['zip_code'] = $value['zip_code'];
                $arrSubcategory['data'][$key]['rider']['state'] = $value['state'];
                $arrSubcategory['data'][$key]['rider']['request_id'] = $value['request_id'];


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
                $arrSubcategory['data'][$key]['ride_history']['sl_latitide'] = $value['sl_latitide'];
                $arrSubcategory['data'][$key]['ride_history']['sl_longitude'] = $value['sl_longitude'];
                $arrSubcategory['data'][$key]['ride_history']['sl_state'] = $value['sl_state'];
                $arrSubcategory['data'][$key]['ride_history']['sl_zipcode'] = $value['sl_zipcode'];
                $arrSubcategory['data'][$key]['ride_history']['el_address'] = $value['el_address'];
                $arrSubcategory['data'][$key]['ride_history']['el_city'] = $value['el_city'];
                $arrSubcategory['data'][$key]['ride_history']['el_latitide'] = $value['el_latitide'];
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
    public function get_leaderboard_finish_success_1($input_params = array())
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

   

}
