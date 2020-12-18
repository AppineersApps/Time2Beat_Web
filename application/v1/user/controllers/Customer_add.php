<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Customer Add Controller
 *
 * @category webservice
 *
 * @package user
 *
 * @subpackage controllers
 *
 * @module Customer Add
 *
 * @class Customer_add.php
 *
 * @path application\webservice\user\controllers\Customer_add.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 06.09.2019
 */

class Customer_add extends Cit_Controller
{
    public $settings_params;
    public $output_params;
    public $single_keys;
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
            "check_reg_email_exists",
            "custom_email_verify_link",
            "insert_customer_data",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('customer_add_model');
        $this->load->model("user/customer_model");
    }

    /**
     * rules_customer_add method is used to validate api input params.
     * @created  | 28.01.2016
     * @modified ---
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_customer_add($request_arr = array())
    {
        $valid_arr = array(
            "first_name" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "first_name_required",
                )
            ),
            "last_name" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "last_name_required",
                )
            ),
            "email" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "email_required",
                )
            ),
            "username" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "username_required",
                )
            ),
            "password" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "password_required",
                )
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "customer_add");

        return $valid_res;
    }

    /**
     * start_customer_add method is used to initiate api execution flow.
     * @created  | 28.01.2016
     * @modified ---
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_customer_add($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_customer_add($request_arr);
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

            $input_params = $this->check_reg_email_exists($input_params);

            $condition_res = $this->is_email_available($input_params);
            if ($condition_res["success"])
            {

                $input_params = $this->custom_email_verify_link($input_params);

                $input_params = $this->insert_customer_data($input_params);

                $condition_res = $this->is_customer_added($input_params);
                if ($condition_res["success"])
                {

                    $input_params = $this->send_registration_email($input_params);

                    $output_response = $this->finish_customer_add_success($input_params);
                    return $output_response;
                }

                else
                {

                    $output_response = $this->finish_customer_add_failure($input_params);
                    return $output_response;
                }
            }

            else
            {

                $output_response = $this->finish_customer_reg_failure($input_params);
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
     * check_reg_email_exists method is used to process query block.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function check_reg_email_exists($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $email = isset($input_params["email"]) ? $input_params["email"] : "";
            $username = isset($input_params["username"]) ? $input_params["username"] : "";
            $this->block_result = $this->customer_model->check_reg_email_exists($email, $username);
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
        $input_params["check_reg_email_exists"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * is_email_available method is used to process conditions.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_email_available($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["check_reg_email_exists"]) ? 0 : 1);
            $cc_ro_0 = 0;

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
     * custom_email_verify_link method is used to process custom function.
     * @created  | 14.11.2018
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function custom_email_verify_link($input_params = array())
    {
        if (!method_exists($this->general, "getCustomerVerifyEmailLink"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->general->getCustomerVerifyEmailLink($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["custom_email_verify_link"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * insert_customer_data method is used to process query block.
     * @created  | 28.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function insert_customer_data($input_params = array())
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
                $images_arr["profile_image"]["size"] = "10240";
                if ($this->general->validateFileFormat($images_arr["profile_image"]["ext"], $_FILES["profile_image"]["name"]))
                {
                    if ($this->general->validateFileSize($images_arr["profile_image"]["size"], $_FILES["profile_image"]["size"]))
                    {
                        $images_arr["profile_image"]["name"] = $file_name;
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
            if (isset($input_params["email"]))
            {
                $params_arr["email"] = $input_params["email"];
            }
            if (isset($input_params["username"]))
            {
                $params_arr["username"] = $input_params["username"];
            }
            if (isset($input_params["password"]))
            {
                $params_arr["password"] = $input_params["password"];
            }
            if (method_exists($this->general, "encryptCustomerPassword"))
            {
                $params_arr["password"] = $this->general->encryptCustomerPassword($params_arr["password"], $input_params);
            }
            if (isset($images_arr["profile_image"]["name"]))
            {
                $params_arr["profile_image"] = $images_arr["profile_image"]["name"];
            }
            $params_arr["_eemailverified"] = "No";
            if (isset($input_params["verify_code"]))
            {
                $params_arr["verify_code"] = $input_params["verify_code"];
            }
            $params_arr["_dtregistereddate"] = "CURRENT_TIMESTAMP";
            $params_arr["_estatus"] = "Active";
            $this->block_result = $this->customer_model->insert_customer_data($params_arr);
            if (!$this->block_result["success"])
            {
                throw new Exception("Insertion failed.");
            }
            $data_arr = $this->block_result["array"];
            $upload_path = $this->config->item("upload_path");
            if (!empty($images_arr["profile_image"]["name"]))
            {

                $folder_name = $this->general->getImageNestedFolders("profile_image");
                $file_path = $upload_path.$folder_name.DS;
                $this->general->createUploadFolderIfNotExists($folder_name);
                $file_name = $images_arr["profile_image"]["name"];
                $file_tmp_path = $_FILES["profile_image"]["tmp_name"];
                $file_tmp_size = $_FILES["profile_image"]["size"];
                $valid_extensions = $images_arr["profile_image"]["ext"];
                $valid_max_size = $images_arr["profile_image"]["size"];
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
        $input_params["insert_customer_data"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * is_customer_added method is used to process conditions.
     * @created  | 28.01.2016
     * @modified ---
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_customer_added($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["insert_customer_data"]) ? 0 : 1);
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
     * send_registration_email method is used to process email notification.
     * @created  | 28.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function send_registration_email($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $email_arr["vEmail"] = $input_params["email"];

            $email_arr["vName"] = $input_params["full_name"];
            $email_arr["vUserEmail"] = $input_params["email"];
            $email_arr["vUsername"] = $input_params["username"];
            $email_arr["VERIFY_EMAIL"] = $input_params["verify_link"];

            $success = $this->general->sendMail($email_arr, "USER_REGISTER", $input_params);

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
        $input_params["send_registration_email"] = $this->block_result["success"];

        return $input_params;
    }

    /**
     * finish_customer_add_success method is used to process finish flow.
     * @created  | 28.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_customer_add_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "finish_customer_add_success",
        );
        $output_fields = array(
            'customer_id',
        );
        $output_keys = array(
            'insert_customer_data',
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "customer_add";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(0);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * finish_customer_add_failure method is used to process finish flow.
     * @created  | 28.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_customer_add_failure($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "finish_customer_add_failure",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "customer_add";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(0);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * finish_customer_reg_failure method is used to process finish flow.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_customer_reg_failure($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "finish_customer_reg_failure",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "customer_add";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
