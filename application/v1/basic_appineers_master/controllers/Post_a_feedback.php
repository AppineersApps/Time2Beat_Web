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
 * @module Post a Feedback
 *
 * @class Post_a_feedback.php
 *
 * @path application\webservice\basic_appineers_master\controllers\Post_a_feedback.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 11.12.2019
 */

class Post_a_feedback extends Cit_Controller
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
            "post_a_feedback",
            "get_query_details",
        );
        $this->multiple_keys = array(
            "custom_function",
            "query_images",
            "formatting_images",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('post_a_feedback_model');
        $this->load->model("basic_appineers_master/user_query_model");
        $this->load->model("basic_appineers_master/user_query_images_model");
    }

    /**
     * rules_post_a_feedback method is used to validate api input params.
     * @created priyanka chillakuru | 09.09.2019
     * @modified priyanka chillakuru | 11.12.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_post_a_feedback($request_arr = array())
    {
        $valid_arr = array(
            "device_type" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "device_type_required",
                )
            ),
            "device_model" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "device_model_required",
                )
            ),
            "device_os" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "device_os_required",
                )
            ),
            "images_count" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "images_count_required",
                )
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "post_a_feedback");

        return $valid_res;
    }

    /**
     * start_post_a_feedback method is used to initiate api execution flow.
     * @created priyanka chillakuru | 09.09.2019
     * @modified priyanka chillakuru | 11.12.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_post_a_feedback($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_post_a_feedback($request_arr);
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

            $input_params = $this->post_a_feedback($input_params);

            $condition_res = $this->is_posted($input_params);
            if ($condition_res["success"])
            {

                $input_params = $this->custom_function($input_params);

                $input_params = $this->get_query_details($input_params);

                $input_params = $this->query_images($input_params);

                $input_params = $this->formatting_images($input_params);

                $output_response = $this->user_query_finish_success($input_params);
                return $output_response;
            }

            else
            {

                $output_response = $this->user_query_finish_success_1($input_params);
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
     * post_a_feedback method is used to process query block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 11.12.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function post_a_feedback($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = array();
            $params_arr["_dtaddedat"] = "NOW()";
            $params_arr["_estatus"] = "Pending";
            if (isset($input_params["user_id"]))
            {
                $params_arr["user_id"] = $input_params["user_id"];
            }
            if (isset($input_params["feedback"]))
            {
                $params_arr["feedback"] = $input_params["feedback"];
            }
            $params_arr["_dtupdatedat"] = "''";
            if (isset($input_params["device_type"]))
            {
                $params_arr["device_type"] = $input_params["device_type"];
            }
            if (isset($input_params["device_model"]))
            {
                $params_arr["device_model"] = $input_params["device_model"];
            }
            if (isset($input_params["device_os"]))
            {
                $params_arr["device_os"] = $input_params["device_os"];
            }
            if (isset($input_params["app_version"]))
            {
                $params_arr["app_version"] = $input_params["app_version"];
            }
            $this->block_result = $this->user_query_model->post_a_feedback($params_arr);
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["post_a_feedback"] = $this->block_result["data"];
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

            $cc_lo_0 = $input_params["query_id"];
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
     * custom_function method is used to process custom function.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 31.10.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function custom_function($input_params = array())
    {
        if (!method_exists($this, "uploadQueryImages"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->uploadQueryImages($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["custom_function"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * get_query_details method is used to process query block.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 11.12.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_query_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $query_id = isset($input_params["query_id"]) ? $input_params["query_id"] : "";
            $this->block_result = $this->user_query_model->get_query_details($query_id);
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

                    $data = $data_arr["uq_note"];
                    if (method_exists($this, "get_Limit_characters_feedback"))
                    {
                        $data = $this->get_Limit_characters_feedback($data, $result_arr[$data_key], $i, $input_params);
                    }
                    $result_arr[$data_key]["uq_note"] = $data;

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
        $input_params["get_query_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * query_images method is used to process query block.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function query_images($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $query_id = isset($input_params["query_id"]) ? $input_params["query_id"] : "";
            $this->block_result = $this->user_query_images_model->query_images($query_id);
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

                    $data = $data_arr["uqi_query_image"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $p_key = ($data_arr["uqi_user_query_id"] != "") ? $data_arr["uqi_user_query_id"] : $input_params["uqi_user_query_id"];
                    $image_arr["pk"] = $p_key;
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $dest_path = "query_images";
                    $image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $data = $this->general->get_image($image_arr);

                    $result_arr[$data_key]["uqi_query_image"] = $data;

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
        $input_params["query_images"] = $this->block_result["data"];

        return $input_params;
    }

    /**
     * formatting_images method is used to process custom function.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function formatting_images($input_params = array())
    {
        if (!method_exists($this->general, "add_query_format_output"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->general->add_query_format_output($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["formatting_images"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * user_query_finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 11.12.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function user_query_finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "user_query_finish_success",
        );
        $output_fields = array(
            'uq_feedback',
            'uq_user_id',
            'uq_note',
            'uq_device_type',
            'uq_device_model',
            'uq_device_os',
            'uq_status',
            'uq_added_at',
            'uq_updated_at',
            'images',
            'uq_app_version',
        );
        $output_keys = array(
            'get_query_details',
        );
        $ouput_aliases = array(
            "uq_feedback" => "feedback",
            "uq_user_id" => "user_id",
            "uq_note" => "note",
            "uq_device_type" => "device_type",
            "uq_device_model" => "device_model",
            "uq_device_os" => "device_os",
            "uq_status" => "status",
            "uq_added_at" => "added_at",
            "uq_updated_at" => "updated_at",
            "uq_app_version" => "app_version",
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "post_a_feedback";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * user_query_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function user_query_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "user_query_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "post_a_feedback";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
