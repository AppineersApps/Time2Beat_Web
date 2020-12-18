<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Customer Login Controller
 *
 * @category webservice
 *
 * @package user
 *
 * @subpackage controllers
 *
 * @module Customer Login
 *
 * @class Customer_login.php
 *
 * @path application\webservice\user\controllers\Customer_login.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 06.09.2019
 */

class Customer_login extends Cit_Controller
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
            "get_customer_login_details",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('customer_login_model');
        $this->load->model("user/customer_model");
    }

    /**
     * rules_customer_login method is used to validate api input params.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_customer_login($request_arr = array())
    {
        $valid_arr = array(
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
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "customer_login");

        return $valid_res;
    }

    /**
     * start_customer_login method is used to initiate api execution flow.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_customer_login($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_customer_login($request_arr);
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

            $input_params = $this->get_customer_login_details($input_params);

            $condition_res = $this->is_login_found($input_params);
            if ($condition_res["success"])
            {

                $output_response = $this->finish_customer_login_success($input_params);
                return $output_response;
            }

            else
            {

                $output_response = $this->finish_customer_login_failure($input_params);
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
     * get_customer_login_details method is used to process query block.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_customer_login_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $username = isset($input_params["username"]) ? $input_params["username"] : "";
            $password = isset($input_params["password"]) ? $input_params["password"] : "";
            $this->block_result = $this->customer_model->get_customer_login_details($username, $password);
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
                    $image_arr["path"] = $this->general->getImageNestedFolders("profile_image");
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
        $input_params["get_customer_login_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * is_login_found method is used to process conditions.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_login_found($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["get_customer_login_details"]) ? 0 : 1);
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
     * finish_customer_login_success method is used to process finish flow.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_customer_login_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "finish_customer_login_success",
        );
        $output_fields = array(
            'u_customer_id',
            'u_first_name',
            'u_last_name',
            'u_email',
            'u_user_name',
            'u_profile_image',
            'u_email_verified',
            'u_registered_date',
            'u_status',
        );
        $output_keys = array(
            'get_customer_login_details',
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "customer_login";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * finish_customer_login_failure method is used to process finish flow.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_customer_login_failure($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "finish_customer_login_failure",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "customer_login";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
