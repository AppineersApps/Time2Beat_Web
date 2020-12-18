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

class Reviews extends Cit_Controller
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
            "set_review",
            "get_review_details",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('reviews_model');
    }

    /**
     * rules_set_store_review method is used to validate api input params.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_add_review($request_arr = array())
    {        $valid_arr = array(
            "first_name" => array(
                array(
                    "rule" => "minlength",
                    "value" => 1,
                    "message" => "first_name_minlength",
                ),
                array(
                    "rule" => "maxlength",
                    "value" => 80,
                    "message" => "first_name_maxlength",
                ),
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "first_name_required",
                )
            ),
            "last_name" => array(
                array(
                    "rule" => "minlength",
                    "value" => 1,
                    "message" => "last_name_minlength",
                ),
                array(
                    "rule" => "maxlength",
                    "value" => 80,
                    "message" => "last_name_maxlength",
                ),
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "last_name_required",
                )
            ),

            "mobile_number" => array(
                array(
                    "rule" => "number",
                    "value" => TRUE,
                    "message" => "mobile_number_number",
                ),
                array(
                    "rule" => "minlength",
                    "value" => 10,
                    "message" => "mobile_number_minlength",
                ),
                array(
                    "rule" => "maxlength",
                    "value" => 13,
                    "message" => "mobile_number_maxlength",
                )
            ),
            "email" => array(
                array(
                    "rule" => "email",
                    "value" => TRUE,
                    "message" => "email_email",
                )
            ),
            "position" => array(
                array(
                    "rule" => "regex",
                    "value" => "/^[a-zA-Z]([\w -]*[a-zA-Z])?$/",
                    "message" => "position_character_only",
                )
            ),
              "zipcode" => array(
                array(
                    "rule" => "minlength",
                    "value" => 5,
                    "message" => "zipcode_minlength",
                ),
                array(
                    "rule" => "maxlength",
                    "value" => 10,
                    "message" => "zipcode_maxlength",
                )
            ),
            "city" => array(
                array(
                    "rule" => "regex",
                    "value" => "/^[a-zA-Z]([\w -]*[a-zA-Z])?$/",
                    "message" => "city_character_only",
                )
            ),
            "business_name" => array(
                array(
                    "rule" => "regex",
                    "value" => "/^[a-zA-Z]([\w -]*[a-zA-Z])?$/",
                    "message" => "business_name_character_only",
                )
            ),
            "review_stars" => array(
                array(
                    "rule" => "regex",
                    "value" => "/^[0-9](\.[0-9]+)?$/",
                    "message" => "review_stars_decimal_only",
                )
            ),
            "user_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "user_id_required",
                )
            )
            );
        if(true == empty($request_arr['mobile_number']) && true == empty($request_arr['email']))
        {
            $valid_arr = array(            
            "email" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "email_required",
                )
            )
            );

        }
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "add_review");

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
    public function start_reviews($request_arr = array(), $inner_api = FALSE)
    {
        // get the HTTP method, path and body of the request
        $method = $_SERVER['REQUEST_METHOD'];
        $output_response = array();

        switch ($method) {
          case 'GET':
           $output_response =  $this->get_reviews($request_arr);
           return  $output_response;
             break;
          case 'PUT':
           $output_response =  $this->update_review($request_arr);
           return  $output_response;
             break;
          case 'POST':
           $output_response =  $this->add_review($request_arr);
           return  $output_response;
             break;
          case 'DELETE':
            $output_response = $this->get_deleted_review($request_arr);
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
    public function rules_update_review($request_arr = array())
    {
        
         $valid_arr = array(            
            "review_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "review_id_required",
                )
            )
            );
        
        
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "update_review");

        return $valid_res;
    }
    /**
     * rules_set_store_review method is used to validate api input params.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_get_review($request_arr = array())
    {
       // print_r($request_arr); exit;
        if(true == empty($request_arr['page_name'])){
            $valid_arr = array(            
                "page_name" => array(
                    array(
                        "rule" => "required",
                        "value" => TRUE,
                        "message" => "page_name_required",
                    )
                )
            );

        }elseif("home" == strtolower($request_arr['page_name'])){
            $valid_arr = array(            
            "page_number" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "page_number_required",
                )
            )
            );
        }elseif("search" == strtolower($request_arr['page_name'])){
            $valid_arr = array(            
            "first_name" => array(
                array(
                    "rule" => "minlength",
                    "value" => 1,
                    "message" => "first_name_minlength",
                ),
                array(
                    "rule" => "maxlength",
                    "value" => 80,
                    "message" => "first_name_maxlength",
                )
            ),
            "last_name" => array(
                array(
                    "rule" => "minlength",
                    "value" => 1,
                    "message" => "last_name_minlength",
                ),
                array(
                    "rule" => "maxlength",
                    "value" => 80,
                    "message" => "last_name_maxlength",
                )
            ),

            "mobile_number" => array(
                array(
                    "rule" => "number",
                    "value" => TRUE,
                    "message" => "mobile_number_number",
                ),
                array(
                    "rule" => "minlength",
                    "value" => 10,
                    "message" => "mobile_number_minlength",
                ),
                array(
                    "rule" => "maxlength",
                    "value" => 13,
                    "message" => "mobile_number_maxlength",
                )
            ),
              "zipcode" => array(
                array(
                    "rule" => "minlength",
                    "value" => 5,
                    "message" => "zipcode_minlength",
                ),
                array(
                    "rule" => "maxlength",
                    "value" => 10,
                    "message" => "zipcode_maxlength",
                )
            ),
            "city" => array(
                array(
                    "rule" => "regex",
                    "value" => "/^[a-zA-Z]([\w -]*[a-zA-Z])?$/",
                    "message" => "city_character_only",
                )
            )
            );
        }elseif("consumer_listing" == strtolower($request_arr['page_name']) && (true == empty($request_arr['mobile_number']) || true == empty($request_arr['email_address']))){
            $valid_arr = array(            
            "consumer_full_name" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "consumer_full_name_required",
                )
            ),
            'page_number'=>array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "page_number_required",
                )
             ),
             "email_address" => array(
                        array(
                            "rule" => "required",
                            "value" => TRUE,
                            "message" => "email_address_required",
                        )
             )
            );
        }elseif("my_review" == strtolower($request_arr['page_name']))
        {
            $valid_arr = array(            
            "reviewer_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "reviewer_id_required",
                )
            )
        );

        }elseif("review_for_me" == strtolower($request_arr['page_name']) && (true == empty($request_arr['mobile_number']) || true == empty($request_arr['email_address']))){
            $valid_arr = array(            
            "user_full_name" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "user_full_name_required",
                )
            ),
            'page_number'=>array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "page_number_required",
                )
             ),
             "email_address" => array(
                        array(
                            "rule" => "required",
                            "value" => TRUE,
                            "message" => "email_address_required",
                        )
             )
            );
        }elseif("new_user_listing" == strtolower($request_arr['page_name'])){
            $valid_arr = array(
            "email_address" => array(
                        array(
                            "rule" => "required",
                            "value" => TRUE,
                            "message" => "email_address_required",
                        )
             )
            );
        }

        
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "get_review");

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
    public function get_reviews($request_arr = array(), $inner_api = FALSE)
    {
       try
        {
            $validation_res = $this->rules_get_review($request_arr);
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
           // print_r($input_params); exit;

            $input_params = $this->get_all_reviews($input_params);

            $condition_res = $this->is_posted($input_params);
            if ($condition_res["success"])
            {
               
                $output_response = $this->get_review_finish_success($input_params);
                return $output_response;
            }

            else
            {
 
                $output_response = $this->get_review_finish_success_1($input_params);
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
     * start_edit_profile method is used to initiate api execution flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function update_review($request_arr = array(), $inner_api = FALSE)
    {

        try
        {
            $validation_res = $this->rules_update_review($request_arr);
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

            $input_params = $this->check_review_exist($input_params);
           // echo __LINE__;
            //print_r($input_params); exit;
            //$condition_res = $this->is_posted($input_params);
            if ($input_params["checkreviewexist"]["status"])
            {
                $input_params = $this->update_exist_review($input_params);
                if ($input_params["affected_rows"])
                {
                    $input_params = $this->get_updated_reviews($request_arr);
                    if($input_params["claimed_email"])
                    {
                         $input_params = $this->format_email_v1($input_params);
                         $input_params = $this->custom_function($input_params);
                         if(false == empty($input_params['registered_user_id']))
                            {
                                $input_params = $this->get_updated_rating($input_params);
                                $input_params = $this->get_updated_reviews($request_arr);
                                if (false == empty($input_params['get_all_reviews']))
                                {
                                    
                                    $output_response = $this->get_update_finish_success($input_params);
                                    return $output_response;
                                }
                                else
                                {
                                    $output_response = $this->get_update_finish_success_1($input_params);
                                    return $output_response;
                                }
                            }

                    }
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
     * update_profile method is used to process query block.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function update_exist_review($input_params = array())
    {
        $this->block_result = array();
        try
        {
            $params_arr = array();

            if (isset($input_params["review_id"]) && false == empty($input_params["claimed_email"]))
            {
                $params_arr["review_id"] = $input_params["review_id"];
                $params_arr["is_claimed"] = "true";
                if (isset($input_params["claimed_email"]))
                {
                    $params_arr["claimed_email"] = $input_params["claimed_email"];
                }
            }else{
                 $where_arr["review_id"] = $input_params["review_id"];
            }
            if (isset($_FILES["profile_image"]["name"]) && isset($_FILES["profile_image"]["tmp_name"]))
            {
                $sent_file = $_FILES["profile_image"]["name"];
            }
            else
            {
                $sent_file = "";
            }
            if (!empty($sent_file))
            {
                list($file_name, $ext) = $this->general->get_file_attributes($sent_file);
                $images_arr["profile_image"]["ext"] = implode(',', $this->config->item('IMAGE_EXTENSION_ARR'));
                $images_arr["profile_image"]["size"] = "102400";
                if ($this->general->validateFileFormat($images_arr["profile_image"]["ext"], $_FILES["profile_image"]["name"]))
                {
                    if ($this->general->validateFileSize($images_arr["profile_image"]["size"], $_FILES["profile_image"]["size"]))
                    {
                        $images_arr["profile_image"]["name"] = $file_name;
                    }
                }
            }
            $params_arr["_dtupdatedat"] = "NOW()";            
            $params_arr["_estatus"] = "Active";
            
            
            if (isset($input_params["first_name"]))
            {
                $params_arr["first_name"] = $input_params["first_name"];
            }
            
            if (isset($input_params["last_name"]))
            {
                $params_arr["last_name"] = $input_params["last_name"];
            }
            if (isset($input_params["mobile_number"]))
            {
                $params_arr["mobile_number"] = $input_params["mobile_number"];
            }
            if (isset($input_params["email"]))
            {
                $params_arr["email"] = $input_params["email"];
            }
            if (isset($input_params["position"]))
            {
                $params_arr["position"] = $input_params["position"];
            }
            if (isset($input_params["street_address"]))
            {
                $params_arr["street_address"] = $input_params["street_address"];
            }
            if (isset($input_params["city"]))
            {
                $params_arr["city"] = $input_params["city"];
            }
            if (isset($input_params["state"]))
            {
                $params_arr["state"] = $input_params["state"];
            }
            if (isset($input_params["zipcode"]))
            {
                $params_arr["zipcode"] = $input_params["zipcode"];
            }
            if (isset($input_params["google_placeid"]))
            {
                $params_arr["google_placeid"] = $input_params["google_placeid"];
            }
            if (isset($input_params["business_name"]))
            {
                $params_arr["business_name"] = $input_params["business_name"];
            }
            if (isset($input_params["business_typeid"]))
            {
                $params_arr["business_typeid"] = $input_params["business_typeid"];
            }
            if (isset($input_params["review_stars"]))
            {
                $params_arr["review_stars"] = $input_params["review_stars"];
            }
            if (isset($input_params["description"]))
            {
                $params_arr["description"] = $input_params["description"];
            }
            if (isset($input_params["review_type"]))
            {
                $params_arr["review_type"] = $input_params["review_type"];
            }
            if (isset($images_arr["profile_image"]["name"]))
            {
                $params_arr["profile_image"] = $images_arr["profile_image"]["name"];
            }

            $this->block_result = $this->reviews_model->update_review($params_arr, $where_arr);
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
        $input_params["update_profile"] = $this->block_result["data"];
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
    public function check_review_exist($input_params = array())
    {

        if (!method_exists($this, "checkReviewExist"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->checkReviewExist($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["checkreviewexist"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        //print_r($input_params);
        return $input_params;
    }
    /**
     * get_review_details method is used to process review block.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_updated_reviews($input_params = array())
    {
//print_r($input_params); exit;
        $this->block_result = array();
        try
        {
            $arrResult = array();
            $arrResult['updated_review_id']  = isset($input_params["review_id"]) ? $input_params["review_id"] : "";   
            $this->block_result = $this->reviews_model->get_updated_reviews($arrResult);
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
                    $data = $data_arr["review_description"];
                    if (method_exists($this, "get_Limit_characters_feedback"))
                    {
                        $data = $this->get_Limit_characters_feedback($data, $result_arr[$data_key], $i, $input_params);
                    }
                    $result_arr[$data_key]["review_description"] = $data;
                  
                    
                    /*profile images */
                    $data = $data_arr["consumer_profile_image"];
                    //echo  $data;exit;
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                   
                    $p_key = ($data_arr["review_id"] != "") ? $data_arr["review_id"] : $input_params["review_id"];
                    $dest_path = "consumer_profile_image";
                    $image_arr["pk"] = $p_key;
                    $image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $data = $this->general->get_image($image_arr);

                    $result_arr[$data_key]["consumer_profile_image"] = $data;
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
        $input_params["get_all_reviews"] = $this->block_result["data"];
        
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
    public function get_updated_rating($input_params = array())
    {

        $this->block_result = array();
        try
        {
            $arrResult = array();
            $arrResult['updated_review_id']  = isset($input_params["review_id"]) ? $input_params["review_id"] : "";   
            $this->block_result = $this->reviews_model->get_updated_reviews($arrResult);
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
                  if(false == empty($data_arr['claimed_email']))
                    { 
                        $params_arr = array();
                        $where_arr= array();

                        $params_arr["is_claimed"] = true;
                        $params_arr["claimed_email"] = $data_arr['claimed_email'];
                        //$where_arr["user_id"] = $input_params["user_id"];
                        $params_arr["_dtupdatedat"] = "NOW()";
                        $where_arr['registered_user_id'] = $input_params["registered_user_id"];
                        
                        $this->block_result = $this->reviews_model->update_users_after_review_update($params_arr,$where_arr);
                       if (!$this->block_result["success"])
                        {
                            throw new Exception("Insertion failed.");
                        }
                    }
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
        $input_params["get_all_reviews"] = $this->block_result["data"];
        
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
    public function get_all_reviews($input_params = array())
    {
//print_r($input_params); exit;
        $this->block_result = array();
        try
        {
            $arrResult = array();
            $arrResult['search_string'] = isset($input_params["search_string"]) ? $input_params["search_string"] : "";
            if(false == empty($input_params["consumer_full_name"]))
            {
               $strConsumerFullName = isset($input_params["consumer_full_name"]) ? $input_params["consumer_full_name"] : "";  
            }
            if(false == empty($input_params["user_full_name"]))
            {
             $strConsumerFullName = isset($input_params["user_full_name"]) ? $input_params["user_full_name"] : "";
            }
            if(false == empty($strConsumerFullName)){
                $arrName = explode(' ', $strConsumerFullName);
                $arrResult['first_name'] = $arrName['0'];
                $arrResult['last_name'] = $arrName['1'];
            }           
            $arrResult['reviewer_id'] = isset($input_params["reviewer_id"]) ? $input_params["reviewer_id"] : "";
            $arrResult['page_number'] = isset($input_params["page_number"]) ? $input_params["page_number"] : "";
            $arrResult['email_address'] = isset($input_params["email_address"]) ? $input_params["email_address"] : "";  
            $arrResult['mobile_number'] = isset($input_params["mobile_number"]) ? $input_params["mobile_number"] : ""; 
            $arrResult['business_name']  = isset($input_params["business_name"]) ? $input_params["business_name"] : ""; 
             $arrResult['page_name']  = isset($input_params["page_name"]) ? $input_params["page_name"] : "";
             $arrResult['first_name']  = isset($input_params["first_name"]) ? $input_params["first_name"] : "";
            $arrResult['last_name']  = isset($input_params["last_name"]) ? $input_params["last_name"] : "";
            $arrResult['city']  = isset($input_params["city"]) ? $input_params["city"] : "";
            $arrResult['zipcode']  = isset($input_params["zipcode"]) ? $input_params["zipcode"] : "";
            $arrResult['state']  = isset($input_params["state"]) ? $input_params["state"] : "";
            
            //print_r($arrResult['updated_review_id']); exit;    
            $this->block_result = $this->reviews_model->get_review_details($arrResult);
            //print_r($this->block_result); exit;
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

                    $data = $data_arr["review_description"];
                    if (method_exists($this, "get_Limit_characters_feedback"))
                    {
                        $data = $this->get_Limit_characters_feedback($data, $result_arr[$data_key], $i, $input_params);
                    }
                    $result_arr[$data_key]["review_description"] = $data;
                  
                    
                    /*profile images */
                    if(false == empty($data_arr["consumer_profile_image"]))
                    {
                        $data = $data_arr["consumer_profile_image"];
                        //echo  $data;exit;
                        $image_arr = array();
                        $image_arr["image_name"] = $data;
                        $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                        
                        $image_arr["color"] = "FFFFFF";
                        $image_arr["no_img"] = FALSE;
                       
                        $p_key = ($data_arr["review_id"] != "") ? $data_arr["review_id"] : $input_params["review_id"];
                        $dest_path = "consumer_profile_image";
                        $image_arr["pk"] = $p_key;
                        $image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                        $data = $this->general->get_image($image_arr);

                        $result_arr[$data_key]["consumer_profile_image"] = $data;
                    }

                    if(false == empty($data_arr["user_profile_image"]))
                    {
                        $data = $data_arr["user_profile_image"];
                        $image_arr = array();
                        $image_arr["image_name"] = $data;
                        $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                        $image_arr["color"] = "FFFFFF";
                        $image_arr["no_img"] = FALSE;
                        $dest_path = "user_profile";
                        $image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                        $data = $this->general->get_image($image_arr);

                        $result_arr[$data_key]["user_profile_image"] = $data;
                    }
                    
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
        $input_params["get_all_reviews"] = $this->block_result["data"];
        
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
       return $input_params;
    }

    public function add_review($input){
        try
        {
        if(true == empty($input['review_id']))
        {
            $validation_res = $this->rules_add_review($input);
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
            
        }
        else
        {
            $input_params = $this->check_review_exist($input);
            
            if(false == $input_params['checkreviewexist']['status'])
            {
                $output_response = $this->user_review_finish_success_1($input_params);
                return $output_response;

            }
           
        }

            $output_response = array();
           
            $output_array = $func_array = array();

            $input_params = $this->set_review($input_params);

            $condition_res = $this->is_posted($input_params);

            if ($condition_res["success"])
            {
                $output_response = $this->user_review_finish_success($input_params);
                return $output_response;
            }

            else
            {

                $output_response = $this->user_review_finish_success_1($input_params);
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
     * set_store_review method is used to process review block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function set_review($input_params = array())
    {
        $this->block_result = array();
        try
        {
            $params_arr = array();
            if (isset($_FILES["profile_image"]["name"]) && isset($_FILES["profile_image"]["tmp_name"]))
            {
                $sent_file = $_FILES["profile_image"]["name"];
            }
            else
            {
                $sent_file = "";
            }
            if (!empty($sent_file))
            {
                list($file_name, $ext) = $this->general->get_file_attributes($sent_file);
                $images_arr["profile_image"]["ext"] = implode(',', $this->config->item('IMAGE_EXTENSION_ARR'));
                $images_arr["profile_image"]["size"] = "102400";
                if ($this->general->validateFileFormat($images_arr["profile_image"]["ext"], $_FILES["profile_image"]["name"]))
                {
                    if ($this->general->validateFileSize($images_arr["profile_image"]["size"], $_FILES["profile_image"]["size"]))
                    {
                        $images_arr["profile_image"]["name"] = $file_name;
                    }
                }
            }
            if (isset($input_params["timestamp"]))
            {
                $params_arr["_dtaddedat"] = $input_params["timestamp"];
            }else{
               $params_arr["_dtaddedat"] = "NOW()"; 
            }
            
            
            $params_arr["_estatus"] = "Active";
            $params_arr["is_claimed"] = "false";
            if (isset($input_params["user_id"]))
            {
                $params_arr["user_id"] = $input_params["user_id"];
            }
            if (isset($input_params["first_name"]))
            {
                $params_arr["first_name"] = $input_params["first_name"];
            }
            
            if (isset($input_params["last_name"]))
            {
                $params_arr["last_name"] = $input_params["last_name"];
            }
            if (isset($input_params["mobile_number"]))
            {
                $params_arr["mobile_number"] = $input_params["mobile_number"];
            }
            if (isset($input_params["email"]))
            {
                $params_arr["email"] = $input_params["email"];
            }
            if (isset($input_params["position"]))
            {
                $params_arr["position"] = $input_params["position"];
            }
            if (isset($input_params["street_address"]))
            {
                $params_arr["street_address"] = $input_params["street_address"];
            }
            if (isset($input_params["city"]))
            {
                $params_arr["city"] = $input_params["city"];
            }
            if (isset($input_params["state"]))
            {
                $params_arr["state"] = $input_params["state"];
            }
            if (isset($input_params["zipcode"]))
            {
                $params_arr["zipcode"] = $input_params["zipcode"];
            }
            if (isset($input_params["google_placeid"]))
            {
                $params_arr["google_placeid"] = $input_params["google_placeid"];
            }
            if (isset($input_params["business_name"]))
            {
                $params_arr["business_name"] = $input_params["business_name"];
            }
            if (isset($input_params["business_typeid"]))
            {
                $params_arr["business_typeid"] = $input_params["business_typeid"];
            }
            if (isset($input_params["review_stars"]))
            {
                $params_arr["review_stars"] = $input_params["review_stars"];
            }
            if (isset($input_params["description"]))
            {
                $params_arr["description"] = $input_params["description"];
            }
            if (isset($input_params["review_type"]))
            {
                $params_arr["review_type"] = $input_params["review_type"];
            }
            if (isset($input_params["latitude"]))
            {
                $params_arr["latitude"] = $input_params["latitude"];
            }
            if (isset($input_params["longitude"]))
            {
                $params_arr["longitude"] = $input_params["longitude"];
            }
            if (isset($images_arr["profile_image"]["name"]))
            {
                $params_arr["profile_image"] = $images_arr["profile_image"]["name"];
            }
            $this->block_result = $this->reviews_model->set_review($params_arr);

            if (!$this->block_result["success"])
            {
                throw new Exception("Insertion failed.");
            }
            $data_arr = $this->block_result["data"];
            $review_id=$data_arr["0"]["review_id"];
            $upload_path = $this->config->item("upload_path");
            if (!empty($images_arr["profile_image"]["name"]) && false == empty($review_id))
            {
                
               
                $folder_name="consumer_profile_image/".$review_id."/";
                $file_path = $upload_path.$folder_name;                
                $file_name = $images_arr["profile_image"]["name"];
                $file_tmp_path = $_FILES["profile_image"]["tmp_name"];
                $file_tmp_size = $_FILES["profile_image"]["size"];
                $valid_extensions = $images_arr["profile_image"]["ext"];
                $valid_max_size = $images_arr["profile_image"]["size"];                
                $upload_arr = $this->general->file_upload($file_path, $file_tmp_path, $file_name, $valid_extensions, $file_tmp_size, $valid_max_size);
                if ($upload_arr[0] == "")
                {
                    throw new Exception("File is not uploaded.");

                }
            }

            $input_params = $this->format_email_v1($input_params);
            $input_params = $this->custom_function($input_params);

            if(false == empty($input_params['registered_user_id']))
            {
                $params_arr['registered_user_id'] = $input_params['registered_user_id'];
                $params_arr["is_claimed"] = true;
                $params_arr["claimed_email"] = (false == empty($input_params["email"])) ? $input_params["email"] : '';
                $where_arr["review_id"] = $review_id;
                $params_arr["review_stars"] = (false == empty($input_params["review_stars"])) ? $input_params["review_stars"] : '';
                 $params_arr["_dtupdatedat"] = "NOW()"; 
                $this->block_result = $this->reviews_model->update_register_user_review($params_arr,$where_arr);
                if (!$this->block_result["success"])
                {
                    throw new Exception("Insertion failed.");
                }
               
                $this->block_result = $this->reviews_model->update_users_after_review_insert($params_arr,$where_arr);
               // print_r($this->block_result); exit;
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        //print_r($this->block_result["data"]); exit;
        $input_params["update_review"] = $this->block_result["data"];
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
            $cc_lo_0 = (is_array($input_params["review_id"])) ? count($input_params["review_id"]):$input_params["review_id"];
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
            $cc_lo_0 = $input_params["review_id"];
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
    public function user_review_finish_success($input_params = array())
    {
        $output_arr['settings']['success'] = "1";
        $output_arr['settings']['message'] = "Review added successfully";
        $output_arr['data'] = "";
        $responce_arr = $this->wsresponse->sendWSResponse($output_arr, array(), "add_review");

        return $responce_arr;
    }

    /**
     * user_review_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function user_review_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "user_review_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "add_review";
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
    public function get_review_finish_success($input_params = array())
    {
       //print_r($input_params); exit;
        $setting_fields = array(
            "success" => "1",
            "message" => "get_review_finish_success",
            "total_count"=> $input_params["get_all_reviews"]["total_count"]
        );
       if(true == isset($input_params["page_name"]) && "consumer_listing" == $input_params["page_name"])
        {
            $output_fields = array(
            "review_id",
            "user_name",
            "user_profile_image",
            "review_rating",            
            "review_type",
            "review_description",
            "review_adddate",
            "user_id",
            "consumer_full_name",
            "consumer_mobile_numer",
            "consumer_email_address",
            "consumer_business_name",
            "consumer_profile_image",
            "review_rating",
            "review_description",
            "review_adddate",
            "position",
            "state",
            "place_id",
            "latitude",
            "longitude",
            "city",
            "zip_code",
            "street_address",
            "business_type_name",
            "claimed_email",
            "total_star_count",
            "average_rating",
            "total_review_count",
            "is_claimed"
        );
        $output_keys = array(
            'get_all_reviews',
        );
        $ouput_aliases = array(
            "review_id"=>"review_id",
            "user_name" => "user_name",
            "user_profile_image"=>"user_profile_image",
            "review_rating" => "review_rating",
            "review_type"=>"review_type",
            "review_description" => "review_description",
            "review_adddate" => "review_adddate",
            "user_id" =>"user_id",
            "consumer_full_name" => "consumer_full_name",
            "consumer_mobile_numer"=>"consumer_mobile_numer",
            "consumer_email_address"=> "consumer_email_address",
            "consumer_business_name"=>"consumer_business_name",
            "consumer_profile_image" => "consumer_profile_image",
            "review_rating" => "review_rating",
            "review_description" => "review_description",
            "review_adddate" => "review_adddate",
            "position" => "position",
            "state" => "state_name",
            "place_id" => "place_id",
            "latitude" => "latitude",
            "longitude" => "longitude",            
            "city" => "city",
            "zip_code" => "zipcode",
            "street_address" => "street_address",
            "business_type_name" => "business_type_name",
            "claimed_email"=>"claimed_email",
            "total_star_count"=>"total_star_count",
            "average_rating"=>"average_rating",
            "total_review_count"=>"total_review_count",
            "is_claimed"=>"is_claimed"
        );

        }
        else
        {
            $output_fields = array(
            "review_id",
            "consumer_full_name",
            "consumer_mobile_numer",
            "consumer_email_address",
            "consumer_business_name",
            "consumer_profile_image",
            "review_rating",
            "review_type",
            "review_description",
            "review_adddate",
            "position",
            "state",
            "place_id",
            "latitude",
            "longitude",
            "city",
            "zip_code",
            "street_address",
            "business_type_name",
            "claimed_email",
            "user_id",
            "total_star_count",
            "average_rating",
            "total_review_count",
            "is_claimed"
            
        );
        $output_keys = array(
            'get_all_reviews',
        );
        $ouput_aliases = array(
            "review_id"=>"review_id",
            "consumer_full_name" => "consumer_full_name",
            "consumer_mobile_numer"=>"consumer_mobile_numer",
            "consumer_email_address"=> "consumer_email_address",
            "consumer_business_name"=>"consumer_business_name",
            "consumer_profile_image" => "consumer_profile_image",
            "review_rating" => "review_rating",
            "review_type"=>"review_type",
            "review_description" => "review_description",
            "review_adddate" => "review_adddate",
            "position" => "position",
            "state" => "state_name",
            "place_id" => "place_id",
            "latitude" => "latitude",
            "longitude" => "longitude",            
            "city" => "city",
            "zip_code" => "zipcode",
            "street_address" => "street_address",
            "business_type_name" => "business_type_name",            
            "claimed_email"=>"claimed_email",
            "user_id" =>"user_id",
            "total_star_count"=>"total_star_count",
            "average_rating"=>"average_rating",
            "total_review_count"=>"total_review_count",
            "is_claimed"=>"is_claimed"
        );

        }
        

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;
        //print_r($input_params);exit;

        $func_array["function"]["name"] = "get_review";
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
    public function get_review_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "get_review_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_review";
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
        $output_fields = array(
            "review_id",
            "consumer_full_name",
            "consumer_mobile_numer",
            "consumer_email_address",
            "consumer_business_name",
            "consumer_profile_image",
            "review_rating",
            "review_description",
            "review_adddate",
            "user_id",
            "claimed_email",
            "is_claimed",
            "total_star_count",
            "average_rating",
            "total_review_count",
            "is_claimed"
        );
        $output_keys = array(
            'get_all_reviews',
        );
        $ouput_aliases = array(
            "review_id"=>"review_id",
            "consumer_full_name" => "consumer_full_name",
            "consumer_mobile_numer"=>"consumer_mobile_numer",
            "consumer_email_address"=> "consumer_email_address",
            "consumer_business_name"=>"consumer_business_name",
            "consumer_profile_image" => "consumer_profile_image",
            "review_rating" => "review_rating",
            "review_description" => "review_description",
            "review_adddate" => "review_adddate",
            "user_id" =>"user_id",
            "claimed_email"=>"claimed_email",
            "is_claimed"=>"is_claimed",
            "total_star_count"=>"total_star_count",
            "average_rating"=>"average_rating",
            "total_review_count"=>"total_review_count",
            "is_claimed"=>"is_claimed"
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;
        //print_r($input_params);exit;

        $func_array["function"]["name"] = "update_review";
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

        $func_array["function"]["name"] = "update_review";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

     /**
     * get_deleted_review method is used to initiate api execution flow.
     * @created aditi billore | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function get_deleted_review($request_arr = array())
    {
      try
        {
           
            $output_response = array();
            $output_array = $func_array = array();
            $input_params = $request_arr;

            $input_params = $this->check_review_exist($input_params);
            
            if ($input_params["checkreviewexist"]["status"])
            {

               $input_params = $this->delete_review($input_params);

               if ($input_params["affected_rows"])
                {
                    $input_params = $this->format_email_v1($input_params);

                    $input_params = $this->custom_function($input_params);


                    $input_params = $this->get_updated_rating($input_params);
                    if (false == empty($input_params['get_all_reviews']))
                    {
                        $input_params = $this->get_updated_reviews($request_arr);
                        $output_response = $this->delete_review_finish_success($input_params);
                        return $output_response;
                    }

                    else
                    {
                        $output_response = $this->delete_review_finish_success_1($input_params);
                        return $output_response;
                    }
                }else{
                    $output_response = $this->delete_review_finish_success_1($input_params);
                    return $output_response;
                }
              
            }

            else
            {
                $output_response = $this->delete_review_finish_success_1($input_params);
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
     * delete review method is used to process review block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function delete_review($input_params = array())
    {
      $this->block_result = array();
        try
        {
            $arrResult = array();
           
            $arrResult['review_id']  = isset($input_params["review_id"]) ? $input_params["review_id"] : "";
            $arrResult['dtUpdatedAt']  = "NOW()";
            
            $this->block_result = $this->reviews_model->delete_review($arrResult);
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
        $input_params["delete_review"] = $this->block_result["data"];
        
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
    public function delete_review_finish_success($input_params = array())
    {
     $setting_fields = array(
            "success" => "1",
            "message" => "delete_review_finish_success"
        );
        $output_fields = array(
            "review_id",
            "consumer_full_name",
            "consumer_mobile_numer",
            "consumer_email_address",
            "consumer_business_name",
            "consumer_profile_image",
            "review_rating",
            "review_description",
            "review_adddate",
            "user_id",
            "claimed_email",
            "is_claimed",
            "total_star_count",
            "average_rating",
            "total_review_count",
            "is_claimed"
        );
        $output_keys = array(
            'get_all_reviews',
        );
        $ouput_aliases = array(
            "review_id"=>"review_id",
            "consumer_full_name" => "consumer_full_name",
            "consumer_mobile_numer"=>"consumer_mobile_numer",
            "consumer_email_address"=> "consumer_email_address",
            "consumer_business_name"=>"consumer_business_name",
            "consumer_profile_image" => "consumer_profile_image",
            "review_rating" => "review_rating",
            "review_description" => "review_description",
            "review_adddate" => "review_adddate",
            "user_id" =>"user_id",
            "claimed_email"=>"claimed_email",
            "is_claimed"=>"is_claimed",
            "total_star_count"=>"total_star_count",
            "average_rating"=>"average_rating",
            "total_review_count"=>"total_review_count",
            "is_claimed"=>"is_claimed"
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;
        //print_r($input_params);exit;

        $func_array["function"]["name"] = "update_review";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

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
    public function delete_review_finish_success_1($input_params = array())
    {
     $setting_fields = array(
            "success" => "0",
            "message" => "delete_review_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "delete_review";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
/**
     * format_email_v1 method is used to process custom function.
     * @created priyanka chillakuru | 07.11.2019
     * @modified saikumar anantham | 07.11.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function format_email_v1($input_params = array())
    {
        if (!method_exists($this->general, "format_email"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->general->format_email($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["format_email_v1"] = $format_arr;
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * custom_function method is used to process custom function.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function custom_function($input_params = array())
    {

        if (!method_exists($this, "checkUniqueUser"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->checkUniqueUser($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["custom_function"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

}
?>
