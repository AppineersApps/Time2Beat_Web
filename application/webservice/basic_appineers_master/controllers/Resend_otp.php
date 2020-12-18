<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Resend Otp Controller
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage controllers
 *
 * @module Resend Otp
 *
 * @class Resend_otp.php
 *
 * @path application\webservice\basic_appineers_master\controllers\Resend_otp.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 18.09.2019
 */

class Resend_otp extends Cit_Controller
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
            "get_user_by_mobile_number_v1",
        );
        $this->multiple_keys = array(
            "otp_generation",
            "call_get_message_api",
            "call_send_sms_api",
            "format_forgot_response",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('resend_otp_model');
        $this->load->model("basic_appineers_master/users_model");
    }

    /**
     * rules_resend_otp method is used to validate api input params.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_resend_otp($request_arr = array())
    {
        $valid_arr = array(
            "mobile_number" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "mobile_number_required",
                ),
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
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "resend_otp");

        return $valid_res;
    }

    /**
     * start_resend_otp method is used to initiate api execution flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_resend_otp($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_resend_otp($request_arr);
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

            $input_params = $this->get_user_by_mobile_number_v1($input_params);

            $condition_res = $this->is_user_exists($input_params);
            if ($condition_res["success"])
            {

                $input_params = $this->otp_generation($input_params);

                $input_params = $this->call_get_message_api($input_params);

                $input_params = $this->call_send_sms_api($input_params);

                $input_params = $this->format_forgot_response($input_params);

                $output_response = $this->users_finish_success_2($input_params);
                return $output_response;
            }

            else
            {

                $condition_res = $this->check_for_user_inactive($input_params);
                if ($condition_res["success"])
                {

                    $output_response = $this->users_finish_success($input_params);
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
     * get_user_by_mobile_number_v1 method is used to process query block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_user_by_mobile_number_v1($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $mobile_number = isset($input_params["mobile_number"]) ? $input_params["mobile_number"] : "";
            $this->block_result = $this->users_model->get_user_by_mobile_number_v1($mobile_number);
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
        $input_params["get_user_by_mobile_number_v1"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * is_user_exists method is used to process conditions.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_user_exists($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["get_user_by_mobile_number_v1"]) ? 0 : 1);
            $cc_ro_0 = 1;

            $cc_fr_0 = ($cc_lo_0 == $cc_ro_0) ? TRUE : FALSE;
            if (!$cc_fr_0)
            {
                throw new Exception("Some conditions does not match.");
            }
            $cc_lo_1 = $input_params["u_status"];
            $cc_ro_1 = "Active";

            $cc_fr_1 = ($cc_lo_1 == $cc_ro_1) ? TRUE : FALSE;
            if (!$cc_fr_1)
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
     * otp_generation method is used to process custom function.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function otp_generation($input_params = array())
    {
        if (!method_exists($this->general, "generateOtp"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->general->generateOtp($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["otp_generation"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * call_get_message_api method is used to process custom function.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function call_get_message_api($input_params = array())
    {

        $this->load->module("basic_appineers_master/get_template_message");
        $api_params = array();

        $api_params["template_code"] = "forgot_password_otp";
        if (array_key_exists("msg_user_name", $input_params))
        {
            $api_params["user_name"] = $input_params["msg_user_name"];
        }
        if (array_key_exists("otp", $input_params))
        {
            $api_params["otp"] = $input_params["otp"];
        }
        $maping_arr = array();
        $result_arr = $this->get_template_message->start_get_template_message($api_params, TRUE);
        $result_keys = is_array($result_arr) ? array_keys($result_arr) : array();
        if ($result_arr["success"] == "-5")
        {
            $input_params["call_get_message_api_success"] = $result_arr["success"];
            $input_params["call_get_message_api_message"] = $result_arr["message"];
            $result_arr["data"] = array();
        }
        else
        {
            $input_params["call_get_message_api_success"] = $result_arr["settings"]["success"];
            $input_params["call_get_message_api_message"] = $result_arr["settings"]["message"];
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr, $maping_arr);
        $input_params["call_get_message_api"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * call_send_sms_api method is used to process custom function.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function call_send_sms_api($input_params = array())
    {

        $this->load->module("basic_appineers_master/send_sms");
        $api_params = array();
        if (array_key_exists("mobile_number", $input_params))
        {
            $api_params["mobile_number"] = $input_params["mobile_number"];
        }
        if (array_key_exists("message", $input_params))
        {
            $api_params["message"] = $input_params["message"];
        }
        $maping_arr = array();
        $result_arr = $this->send_sms->start_send_sms($api_params, TRUE);
        $result_keys = is_array($result_arr) ? array_keys($result_arr) : array();
        if ($result_arr["success"] == "-5")
        {
            $input_params["call_send_sms_api_success"] = $result_arr["success"];
            $input_params["call_send_sms_api_message"] = $result_arr["message"];
            $result_arr["data"] = array();
        }
        else
        {
            $input_params["call_send_sms_api_success"] = $result_arr["settings"]["success"];
            $input_params["call_send_sms_api_message"] = $result_arr["settings"]["message"];
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr, $maping_arr);
        $input_params["call_send_sms_api"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * format_forgot_response method is used to process custom function.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function format_forgot_response($input_params = array())
    {
        if (!method_exists($this, "formatForgotPhoneResponse"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->formatForgotPhoneResponse($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["format_forgot_response"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * users_finish_success_2 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 18.09.2019
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
            'otp_final',
            'reset_key_final',
        );
        $output_keys = array(
            'format_forgot_response',
        );
        $ouput_aliases = array(
            "otp_final" => "otp",
            "reset_key_final" => "reset_key",
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = array_merge($this->output_params, $output_fields);
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "resend_otp";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * check_for_user_inactive method is used to process conditions.
     * @created CIT Dev Team
     * @modified ---
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function check_for_user_inactive($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = $input_params["u_status"];
            $cc_ro_0 = "Inactive";

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
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 18.09.2019
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
        $output_array["settings"]["fields"] = array_merge($this->output_params, $output_fields);
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "resend_otp";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * users_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified ---
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
        $output_array["settings"]["fields"] = array_merge($this->output_params, $output_fields);
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "resend_otp";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
