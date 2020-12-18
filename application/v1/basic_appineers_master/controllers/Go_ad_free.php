<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Go Ad Free Controller
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage controllers
 *
 * @module Go Ad Free
 *
 * @class Go_ad_free.php
 *
 * @path application\webservice\basic_appineers_master\controllers\Go_ad_free.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 27.09.2019
 */

class Go_ad_free extends Cit_Controller
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
            "update_transaction_data",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('go_ad_free_model');
        $this->load->model("basic_appineers_master/users_model");
    }

    /**
     * rules_go_ad_free method is used to validate api input params.
     * @created priyanka chillakuru | 26.09.2019
     * @modified priyanka chillakuru | 27.09.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_go_ad_free($request_arr = array())
    {
        $valid_arr = array();
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "go_ad_free");

        return $valid_res;
    }

    /**
     * start_go_ad_free method is used to initiate api execution flow.
     * @created priyanka chillakuru | 26.09.2019
     * @modified priyanka chillakuru | 27.09.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_go_ad_free($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_go_ad_free($request_arr);
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

            $input_params = $this->update_transaction_data($input_params);

            $output_response = $this->users_finish_success($input_params);
            return $output_response;
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
    }

    /**
     * update_transaction_data method is used to process query block.
     * @created priyanka chillakuru | 26.09.2019
     * @modified priyanka chillakuru | 26.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function update_transaction_data($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = $where_arr = array();
            if (isset($input_params["user_id"]))
            {
                $where_arr["user_id"] = $input_params["user_id"];
            }
            $params_arr["_eonetimetransaction"] = "Yes";
            if (isset($input_params["one_time_transaction_data"]))
            {
                $params_arr["one_time_transaction_data"] = $input_params["one_time_transaction_data"];
            }
            $this->block_result = $this->users_model->update_transaction_data($params_arr, $where_arr);
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["update_transaction_data"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * users_finish_success method is used to process finish flow.
     * @created priyanka chillakuru | 26.09.2019
     * @modified priyanka chillakuru | 27.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "users_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "go_ad_free";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
