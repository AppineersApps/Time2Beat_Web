<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Customer Detail Controller
 *
 * @category webservice
 *
 * @package user
 *
 * @subpackage controllers
 *
 * @module Customer Detail
 *
 * @class Customer_detail.php
 *
 * @path application\webservice\user\controllers\Customer_detail.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 06.09.2019
 */

class Customer_detail extends Cit_Controller
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
            "get_customer_detail",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('customer_detail_model');
        $this->load->model("user/customer_model");
    }

    /**
     * rules_customer_detail method is used to validate api input params.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_customer_detail($request_arr = array())
    {
        $valid_arr = array(
            "customer_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "customer_id_required",
                )
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "customer_detail");

        return $valid_res;
    }

    /**
     * start_customer_detail method is used to initiate api execution flow.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_customer_detail($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_customer_detail($request_arr);
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

            $input_params = $this->get_customer_detail($input_params);

            $condition_res = $this->is_customer_found($input_params);
            if ($condition_res["success"])
            {

                $output_response = $this->finish_customer_info_success($input_params);
                return $output_response;
            }

            else
            {

                $output_response = $this->finish_customer_info_failure($input_params);
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
     * get_customer_detail method is used to process query block.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_customer_detail($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $customer_id = isset($input_params["customer_id"]) ? $input_params["customer_id"] : "";
            $this->block_result = $this->customer_model->get_customer_detail($customer_id);
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

                    $data = $data_arr["mc_profile_image"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["path"] = $this->general->getImageNestedFolders("profile_image");
                    $data = $this->general->get_image($image_arr);

                    $result_arr[$data_key]["mc_profile_image"] = $data;

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
        $input_params["get_customer_detail"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * is_customer_found method is used to process conditions.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_customer_found($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["get_customer_detail"]) ? 0 : 1);
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
     * finish_customer_info_success method is used to process finish flow.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_customer_info_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "finish_customer_info_success",
        );
        $output_fields = array(
            'mc_customer_id',
            'mc_first_name',
            'mc_last_name',
            'mc_email',
            'mc_user_name',
            'mc_profile_image',
            'mc_email_verified',
            'mc_registered_date',
            'mc_status',
        );
        $output_keys = array(
            'get_customer_detail',
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "customer_detail";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * finish_customer_info_failure method is used to process finish flow.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_customer_info_failure($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "finish_customer_info_failure",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "customer_detail";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(0);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
