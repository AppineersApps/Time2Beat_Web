<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Country With States Controller
 *
 * @category webservice
 *
 * @package tools
 *
 * @subpackage controllers
 *
 * @module Country With States
 *
 * @class Country_with_states.php
 *
 * @path application\webservice\tools\controllers\Country_with_states.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 18.09.2019
 */

class Country_with_states extends Cit_Controller
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
            "get_country_data",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('country_with_states_model');
        $this->load->model("tools/country_model");
        $this->load->model("tools/state_model");
    }

    /**
     * rules_country_with_states method is used to validate api input params.
     * @created  | 28.01.2016
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_country_with_states($request_arr = array())
    {
        $valid_arr = array();
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "country_with_states");

        return $valid_res;
    }

    /**
     * start_country_with_states method is used to initiate api execution flow.
     * @created  | 28.01.2016
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_country_with_states($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_country_with_states($request_arr);
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

            $input_params = $this->get_country_data($input_params);

            $condition_res = $this->is_country_data_exists($input_params);
            if ($condition_res["success"])
            {

                $input_params = $this->country_start_loop($input_params);

                $output_response = $this->finish_country_data_success($input_params);
                return $output_response;
            }

            else
            {

                $output_response = $this->finish_country_data_failure($input_params);
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
     * get_country_data method is used to process query block.
     * @created  | 28.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_country_data($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $country_id = isset($input_params["country_id"]) ? $input_params["country_id"] : "";
            $page_index = isset($input_params["page_index"]) ? $input_params["page_index"] : 1;
            $this->block_result = $this->country_model->get_country_data($country_id, $page_index, $this->settings_params);
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
        $input_params["get_country_data"] = $this->block_result["data"];

        return $input_params;
    }

    /**
     * is_country_data_exists method is used to process conditions.
     * @created  | 28.01.2016
     * @modified ---
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_country_data_exists($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["get_country_data"]) ? 0 : 1);
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
     * country_start_loop method is used to process loop flow.
     * @created  | 28.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function country_start_loop($input_params = array())
    {
        $this->iterate_country_start_loop($input_params["get_country_data"], $input_params);
        return $input_params;
    }

    /**
     * get_state_list method is used to process query block.
     * @created  | 28.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_state_list($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $mc_country_id = isset($input_params["mc_country_id"]) ? $input_params["mc_country_id"] : "";
            $this->block_result = $this->state_model->get_state_list($mc_country_id);
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
        $input_params["get_state_list"] = $this->block_result["data"];

        return $input_params;
    }

    /**
     * finish_country_data_success method is used to process finish flow.
     * @created  | 28.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_country_data_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "finish_country_data_success",
        );
        $output_fields = array(
            'mc_country_id',
            'mc_country',
            'mc_country_code',
            'mc_country_code_iso3',
            'mc_status',
            'get_state_list',
            'ms_state_id',
            'ms_state',
            'ms_state_code',
            'ms_status',
        );
        $output_keys = array(
            'get_country_data',
        );
        $inner_keys = array(
            'get_state_list',
        );

        $output_array["settings"] = array_merge($this->settings_params, $setting_fields);
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "country_with_states";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["inner_keys"] = $inner_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(0);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * finish_country_data_failure method is used to process finish flow.
     * @created  | 28.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_country_data_failure($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "finish_country_data_failure",
        );
        $output_fields = array();

        $output_array["settings"] = array_merge($this->settings_params, $setting_fields);
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "country_with_states";
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(0);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * iterate_country_start_loop method is used to iterate loop.
     * @created  | 28.01.2016
     * @modified ---
     * @param array $get_country_data_lp_arr get_country_data_lp_arr array to iterate loop.
     * @param array $input_params_addr $input_params_addr array to address original input params.
     */
    public function iterate_country_start_loop(&$get_country_data_lp_arr = array(), &$input_params_addr = array())
    {

        $input_params_loc = $input_params_addr;
        $_loop_params_loc = $get_country_data_lp_arr;
        $_lp_ini = 0;
        $_lp_end = count($_loop_params_loc);
        for ($i = $_lp_ini; $i < $_lp_end; $i += 1)
        {
            $get_country_data_lp_pms = $input_params_loc;

            unset($get_country_data_lp_pms["get_country_data"]);
            if (is_array($_loop_params_loc[$i]))
            {
                $get_country_data_lp_pms = $_loop_params_loc[$i]+$input_params_loc;
            }
            else
            {
                $get_country_data_lp_pms["get_country_data"] = $_loop_params_loc[$i];
                $_loop_params_loc[$i] = array();
                $_loop_params_loc[$i]["get_country_data"] = $get_country_data_lp_pms["get_country_data"];
            }

            $get_country_data_lp_pms["i"] = $i;
            $input_params = $get_country_data_lp_pms;

            $input_params = $this->get_state_list($input_params);

            $get_country_data_lp_arr[$i] = $this->wsresponse->filterLoopParams($input_params, $_loop_params_loc[$i], $get_country_data_lp_pms);
        }
    }
}
