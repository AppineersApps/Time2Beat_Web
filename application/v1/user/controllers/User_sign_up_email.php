<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of User Sign Up Email Controller
 *
 * @category webservice
 *
 * @package user
 *
 * @subpackage controllers
 *
 * @module User Sign Up Email
 *
 * @class User_sign_up_email.php
 *
 * @path application\webservice\user\controllers\User_sign_up_email.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 06.09.2019
 */

class User_sign_up_email extends Cit_Controller
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
            "check_unique_user_email",
            "post_user_details",
            "get_user_details",
        );
        $this->multiple_keys = array(
            "custom_function",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('user_sign_up_email_model');
        $this->load->model("basic_appineers_master/users_model");
    }

    /**
     * rules_user_sign_up_email method is used to validate api input params.
     * @created priyanka chillakuru | 06.09.2019
     * @modified priyanka chillakuru | 06.09.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_user_sign_up_email($request_arr = array())
    {
        $valid_arr = array(
            "user_email" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "user_email_required",
                ),
                array(
                    "rule" => "email",
                    "value" => TRUE,
                    "message" => "user_email_email",
                )
            ),
            "first_name" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "first_name_required",
                ),
                array(
                    "rule" => "regex",
                    "value" => "/^[a-zA-Z ]+$/",
                    "message" => "first_name_alpha_with_spaces",
                )
            ),
            "last_name" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "last_name_required",
                ),
                array(
                    "rule" => "regex",
                    "value" => "/^[a-zA-Z ]+$/",
                    "message" => "last_name_alpha_with_spaces",
                )
            ),
            "user_password" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "user_password_required",
                )
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "user_sign_up_email");

        return $valid_res;
    }

    /**
     * start_user_sign_up_email method is used to initiate api execution flow.
     * @created priyanka chillakuru | 06.09.2019
     * @modified priyanka chillakuru | 06.09.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_user_sign_up_email($request_arr = array(), $inner_api = FALSE)
    {

        try
        {
            $validation_res = $this->rules_user_sign_up_email($request_arr);
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

            $input_params = $this->check_unique_user_email($input_params);


            $condition_res = $this->is_unique_user_exists($input_params);
            if ($condition_res["success"])
            {

                $output_response = $this->users_finish_success($input_params);
                return $output_response;
            }

            else
            {

                $input_params = $this->custom_function($input_params);

                $input_params = $this->post_user_details($input_params);

                $condition_res = $this->is_user_registered($input_params);
                if ($condition_res["success"])
                {

                    $input_params = $this->get_user_details($input_params);

                    $input_params = $this->email_notification($input_params);

                    $output_response = $this->users_finish_success_2($input_params);
                    return $output_response;
                }

                else
                {

                    $output_response = $this->users_finish_success_1($input_params);
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
     * check_unique_user_email method is used to process query block.
     * @created priyanka chillakuru | 06.09.2019
     * @modified priyanka chillakuru | 06.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function check_unique_user_email($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $user_email = isset($input_params["user_email"]) ? $input_params["user_email"] : "";
            $this->block_result = $this->users_model->check_unique_user_email($user_email);
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
        $input_params["check_unique_user_email"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * is_unique_user_exists method is used to process conditions.
     * @created priyanka chillakuru | 06.09.2019
     * @modified priyanka chillakuru | 06.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_unique_user_exists($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["check_unique_user_email"]) ? 0 : 1);
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
     * users_finish_success method is used to process finish flow.
     * @created priyanka chillakuru | 06.09.2019
     * @modified priyanka chillakuru | 06.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "users_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "user_sign_up_email";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * custom_function method is used to process custom function.
     * @created priyanka chillakuru | 06.09.2019
     * @modified priyanka chillakuru | 06.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function custom_function($input_params = array())
    {
        if (!method_exists($this, "generate_confirmation_link"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->generate_confirmation_link($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["custom_function"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * post_user_details method is used to process query block.
     * @created priyanka chillakuru | 06.09.2019
     * @modified priyanka chillakuru | 06.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function post_user_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = array();
            if (isset($_FILES["user_profile"]["name"]) && isset($_FILES["user_profile"]["tmp_name"]))
            {
                $sent_file = $_FILES["user_profile"]["name"];
            }
            else
            {
                $sent_file = "";
            }
            if (!empty($sent_file))
            {
                list($file_name, $ext) = $this->general->get_file_attributes($sent_file);
                $images_arr["user_profile"]["ext"] = implode(',', $this->config->item('IMAGE_EXTENSION_ARR'));
                $images_arr["user_profile"]["size"] = "102400";
                if ($this->general->validateFileFormat($images_arr["user_profile"]["ext"], $_FILES["user_profile"]["name"]))
                {
                    if ($this->general->validateFileSize($images_arr["user_profile"]["size"], $_FILES["user_profile"]["size"]))
                    {
                        $images_arr["user_profile"]["name"] = $file_name;
                    }
                }
            }
            if (isset($input_params["first_name"]))
            {
                $params_arr["first_name"] = $input_params["first_name"];
            }
            if (isset($input_params["last_name"]))
            {
                $params_arr["last_name"] = $input_params["last_name"];
            }
            if (isset($input_params["user_email"]))
            {
                $params_arr["user_email"] = $input_params["user_email"];
            }
            if (isset($input_params["phone_number"]))
            {
                $params_arr["phone_number"] = $input_params["phone_number"];
            }
            if (isset($input_params["user_password"]))
            {
                $params_arr["user_password"] = $input_params["user_password"];
            }
            if (isset($input_params["address"]))
            {
                $params_arr["address"] = $input_params["address"];
            }
            if (isset($input_params["city"]))
            {
                $params_arr["city"] = $input_params["city"];
            }
            if (isset($input_params["state_id"]))
            {
                $params_arr["state_id"] = $input_params["state_id"];
            }
            if (isset($input_params["latitude"]))
            {
                $params_arr["latitude"] = $input_params["latitude"];
            }
            if (isset($input_params["longitude"]))
            {
                $params_arr["longitude"] = $input_params["longitude"];
            }
            if (isset($input_params["zip_code"]))
            {
                $params_arr["zip_code"] = $input_params["zip_code"];
            }
            if (isset($input_params["dob"]))
            {
                $params_arr["dob"] = $input_params["dob"];
            }
            if (isset($input_params["email_confirmation_code"]))
            {
                $params_arr["email_confirmation_code"] = $input_params["email_confirmation_code"];
            }
            if (isset($images_arr["user_profile"]["name"]))
            {
                $params_arr["user_profile"] = $images_arr["user_profile"]["name"];
            }
            $params_arr["_eemailverified"] = "No";
            $params_arr["_estatus"] = "Inactive";
            if (isset($input_params["device_type"]))
            {
                $params_arr["device_type"] = $input_params["device_type"];
            }
            if (isset($input_params["device_token"]))
            {
                $params_arr["device_token"] = $input_params["device_token"];
            }
            $params_arr["_dtaddedat"] = "NOW()";
            $params_arr["_dtupdatedat"] = "NOW()";
            $this->block_result = $this->users_model->post_user_details($params_arr);
            if (!$this->block_result["success"])
            {
                throw new Exception("Insertion failed.");
            }
            $data_arr = $this->block_result["array"];
            $upload_path = $this->config->item("upload_path");
            if (!empty($images_arr["user_profile"]["name"]))
            {

                $folder_name = $this->general->getImageNestedFolders("user_profile");
                $file_path = $upload_path.$folder_name.DS;
                $this->general->createUploadFolderIfNotExists($folder_name);
                $file_name = $images_arr["user_profile"]["name"];
                $file_tmp_path = $_FILES["user_profile"]["tmp_name"];
                $file_tmp_size = $_FILES["user_profile"]["size"];
                $valid_extensions = $images_arr["user_profile"]["ext"];
                $valid_max_size = $images_arr["user_profile"]["size"];
                $upload_arr = $this->general->file_upload($file_path, $file_tmp_path, $file_name, $valid_extensions, $file_tmp_size, $valid_max_size);
                if ($upload_arr[0] == "")
                {
                    //file upload failed

                }
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["post_user_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * is_user_registered method is used to process conditions.
     * @created priyanka chillakuru | 06.09.2019
     * @modified priyanka chillakuru | 06.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_user_registered($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["post_user_details"]) ? 0 : 1);
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
     * get_user_details method is used to process query block.
     * @created priyanka chillakuru | 06.09.2019
     * @modified priyanka chillakuru | 06.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_user_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $insert_id = isset($input_params["insert_id"]) ? $input_params["insert_id"] : "";
            $this->block_result = $this->users_model->get_user_details($insert_id);
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
                    $image_arr["path"] = $this->general->getImageNestedFolders("user_profile");
                    $data = $this->general->get_image($image_arr);

                    $result_arr[$data_key]["u_profile_image"] = $data;

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
        $input_params["get_user_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * email_notification method is used to process email notification.
     * @created priyanka chillakuru | 06.09.2019
     * @modified priyanka chillakuru | 06.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function email_notification($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $email_arr["vEmail"] = $input_params["user_email"];

            $email_arr["vContent"] = "";
            $email_arr["vContent"] = $this->general->getReplacedInputParams($email_arr["vContent"], $input_params);

            $success = $this->general->CISendMail($email_arr["vEmail"], $email_arr["vSubject"], $email_arr["vContent"], $email_arr["vFromEmail"], $email_arr["vFromName"], $email_arr["vCCEmail"], $email_arr["vBCCEmail"], array(), $input_params);

            $log_arr = array();
            $log_arr['eEntityType'] = 'General';
            $log_arr['vReceiver'] = is_array($email_arr["vEmail"]) ? implode(",", $email_arr["vEmail"]) : $email_arr["vEmail"];
            $log_arr['eNotificationType'] = "EmailNotify";
            $log_arr['vSubject'] = $this->general->getEmailOutput("subject");
            $log_arr['tContent'] = $this->general->getEmailOutput("content");
            if (!$success)
            {
                $log_arr['tError'] = $this->general->getNotifyErrorOutput();
            }
            $log_arr['dtSendDateTime'] = date('Y-m-d H:i:s');
            $log_arr['eStatus'] = ($success) ? "Executed" : "Failed";
            $this->general->insertExecutedNotify($log_arr);
            if (!$success)
            {
                throw new Exception("Failure in sending mail.");
            }
            $success = 1;
            $message = "Email notification send successfully.";
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->block_result["success"] = $success;
        $this->block_result["message"] = $message;
        $input_params["email_notification"] = $this->block_result["success"];

        return $input_params;
    }

    /**
     * users_finish_success_2 method is used to process finish flow.
     * @created priyanka chillakuru | 06.09.2019
     * @modified priyanka chillakuru | 06.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success_2($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "users_finish_success_2",
        );
        $output_fields = array(
            'u_users_id',
            'u_first_name',
            'u_last_name',
            'u_email',
            'u_mobile_no',
            'u_profile_image',
            'u_address',
            'u_city',
            'u_state_id',
            'u_latitude',
            'u_longitude',
            'u_zip_code',
            'u_dob',
            'u_device_type',
            'u_device_token',
            'u_status',
            'u_email_verified',
            'u_push_notify',
            'u_added_at',
        );
        $output_keys = array(
            'get_user_details',
        );
        $ouput_aliases = array(
            "u_users_id" => "user_id",
            "u_first_name" => "first_name",
            "u_last_name" => "last_name",
            "u_email" => "email",
            "u_mobile_no" => "mobile_no",
            "u_profile_image" => "profile_image",
            "u_address" => "address",
            "u_city" => "city",
            "u_state_id" => "state_id",
            "u_latitude" => "latitude",
            "u_longitude" => "longitude",
            "u_zip_code" => "zip_code",
            "u_dob" => "dob",
            "u_device_type" => "device_type",
            "u_device_token" => "device_token",
            "u_status" => "status",
            "u_email_verified" => "email_verified",
            "u_push_notify" => "push_notify",
            "u_added_at" => "added_at",
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "user_sign_up_email";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * users_finish_success_1 method is used to process finish flow.
     * @created priyanka chillakuru | 06.09.2019
     * @modified priyanka chillakuru | 06.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "users_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "user_sign_up_email";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
