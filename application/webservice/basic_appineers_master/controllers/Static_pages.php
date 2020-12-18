<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Static Pages Controller
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage controllers
 *
 * @module Static Pages
 *
 * @class Static_pages.php
 *
 * @path application\webservice\basic_appineers_master\controllers\Static_pages.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 18.09.2019
 */

class Static_pages extends Cit_Controller
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
            "get_page_details",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('static_pages_model');
        $this->load->model("tools/page_settings_model");
    }

    /**
     * rules_static_pages method is used to validate api input params.
     * @created priyanka chillakuru | 09.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_static_pages($request_arr = array())
    {
        $valid_arr = array(
            "page_code" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "page_code_required",
                )
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "static_pages");

        return $valid_res;
    }

    /**
     * start_static_pages method is used to initiate api execution flow.
     * @created priyanka chillakuru | 09.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_static_pages($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_static_pages($request_arr);
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

            $input_params = $this->get_page_details($input_params);

            $condition_res = $this->is_page_exists($input_params);
            if ($condition_res["success"])
            {

                $output_response = $this->mod_page_settings_finish_success($input_params);
                return $output_response;
            }

            else
            {

                $output_response = $this->mod_page_settings_finish_success_1($input_params);
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
     * get_page_details method is used to process query block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 09.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_page_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $page_code = isset($input_params["page_code"]) ? $input_params["page_code"] : "";
            $this->block_result = $this->page_settings_model->get_page_details($page_code);
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
        $input_params["get_page_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * is_page_exists method is used to process conditions.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 09.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_page_exists($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["get_page_details"]) ? 0 : 1);
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
     * mod_page_settings_finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 09.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function mod_page_settings_finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "mod_page_settings_finish_success",
        );
        $output_fields = array(
            'mps_content',
            'mps_page_title',
        );
        $output_keys = array(
            'get_page_details',
        );
        $ouput_aliases = array(
            "get_page_details" => "page_details",
            "mps_content" => "page_content",
            "mps_page_title" => "page_title",
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "static_pages";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * mod_page_settings_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 09.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function mod_page_settings_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "mod_page_settings_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "static_pages";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
