<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Report Abusive User Controller
 *
 * @category webservice
 *
 * @package misc
 *
 * @subpackage controllers
 *
 * @module Report Abusive User
 *
 * @class Report_abusive_user.php
 *
 * @path application\webservice\misc\controllers\Report_abusive_user.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 03.05.2019
 */

class Report_abusive_user extends Cit_Controller
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
            "insert_report",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('report_abusive_user_model');
    }

    /**
     * rules_report_abusive_user method is used to validate api input params.
     * @created  | 02.05.2019
     * @modified priyanka chillakuru | 02.05.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_report_abusive_user($request_arr = array())
    {
        $valid_arr = array(
            "user_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "user_id_required",
                )
            ),
            "report_on" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "report_on_required",
                )
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "report_abusive_user");

        return $valid_res;
    }

    /**
     * start_report_abusive_user method is used to initiate api execution flow.
     * @created  | 02.05.2019
     * @modified priyanka chillakuru | 02.05.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_report_abusive_user($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_report_abusive_user($request_arr);
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

            $input_params = $this->insert_report($input_params);

            $condition_res = $this->check_insereted($input_params);
            if ($condition_res["success"])
            {

                $output_response = $this->abusive_reports_finish_success($input_params);
                return $output_response;
            }

            else
            {

                $output_response = $this->abusive_reports_finish_success_1($input_params);
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
     * insert_report method is used to process query block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 02.05.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function insert_report($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = array();
            if (isset($input_params["user_id"]))
            {
                $params_arr["user_id"] = $input_params["user_id"];
            }
            if (isset($input_params["message"]))
            {
                $params_arr["message"] = $input_params["message"];
            }
            if (isset($input_params["report_on"]))
            {
                $params_arr["report_on"] = $input_params["report_on"];
            }
            $params_arr["_dtaddedat"] = "NOW()";
            $this->block_result = $this->report_abusive_user_model->insert_report($params_arr);
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["insert_report"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * check_insereted method is used to process conditions.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 02.05.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function check_insereted($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["insert_report"]) ? 0 : 1);
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
     * abusive_reports_finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 02.05.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function abusive_reports_finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "abusive_reports_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "report_abusive_user";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * abusive_reports_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 02.05.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function abusive_reports_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "abusive_reports_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "report_abusive_user";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
?>
