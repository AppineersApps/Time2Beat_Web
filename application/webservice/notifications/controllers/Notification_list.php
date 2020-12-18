<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Notification List Controller
 *
 * @category webservice
 *
 * @package notifications
 *
 * @subpackage controllers
 *
 * @module Notification List
 *
 * @class Notification_list.php
 *
 * @path application\webservice\notifications\controllers\Notification_list.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 27.06.2019
 */

class Notification_list extends Cit_Controller
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
            "read_notifications",
        );
        $this->multiple_keys = array(
            "get_notification_details",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('notification_list_model');
        $this->load->model("notifications/notification_model");
        // $this->load->model("user/user_images_ws_model");
    }

    /**
     * rules_notification_list method is used to validate api input params.
     * @created priyanka chillakuru | 04.06.2019
     * @modified Devangi Nirmal | 27.06.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_notification_list($request_arr = array())
    {
        $valid_arr = array(
            "user_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "user_id_required",
                )
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "notification_list");

        return $valid_res;
    }

    /**
     * start_notification_list method is used to initiate api execution flow.
     * @created priyanka chillakuru | 04.06.2019
     * @modified Devangi Nirmal | 27.06.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_notification_list($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_notification_list($request_arr);
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

            $input_params = $this->get_notification_details($input_params);

            $condition_res = $this->is_notify_found($input_params);
            if ($condition_res["success"])
            {

                $input_params = $this->start_loop($input_params);

                $input_params = $this->read_notifications($input_params);

                $output_response = $this->notification_finish_success_1($input_params);
                return $output_response;
            }

            else
            {

                $output_response = $this->notification_finish_success_3($input_params);
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
     * get_notification_details method is used to process query block.
     * @created CIT Dev Team
     * @modified Devangi Nirmal | 11.06.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_notification_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $user_id = isset($input_params["user_id"]) ? $input_params["user_id"] : "";
            $page_index = isset($input_params["page_index"]) ? $input_params["page_index"] : 1;
            $this->block_result = $this->notification_model->get_notification_details($user_id, $page_index, $this->settings_params);
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
        $input_params["get_notification_details"] = $this->block_result["data"];

        return $input_params;
    }

    /**
     * is_notify_found method is used to process conditions.
     * @created CIT Dev Team
     * @modified ---
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_notify_found($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["get_notification_details"]) ? 0 : 1);
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
     * start_loop method is used to process loop flow.
     * @created Devangi Nirmal | 11.06.2019
     * @modified Devangi Nirmal | 11.06.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function start_loop($input_params = array())
    {
        $this->iterate_start_loop($input_params["get_notification_details"], $input_params);
        return $input_params;
    }

    /**
     * get_image method is used to process query block.
     * @created Devangi Nirmal | 11.06.2019
     * @modified Devangi Nirmal | 27.06.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_image($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $from_user_id = isset($input_params["from_user_id"]) ? $input_params["from_user_id"] : "";
            // $this->block_result = $this->user_images_ws_model->get_image($from_user_id);
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

                    $data = $data_arr["ui_image"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $p_key = ($data_arr["ui_users_id"] != "") ? $data_arr["ui_users_id"] : $input_params["ui_users_id"];
                    $image_arr["pk"] = $p_key;
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = $this->general->getImageNestedFolders("user_images");
                    $data = $this->general->get_image($image_arr);

                    $result_arr[$data_key]["ui_image"] = $data;

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
        $input_params["get_image"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * get_images method is used to process custom function.
     * @created Devangi Nirmal | 11.06.2019
     * @modified Devangi Nirmal | 11.06.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_images($input_params = array())
    {
        if (!method_exists($this, "format_images"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->format_images($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["get_images"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * read_notifications method is used to process query block.
     * @created CIT Dev Team
     * @modified Devangi Nirmal | 04.06.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function read_notifications($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = $where_arr = array();
            if (isset($input_params["user_id"]))
            {
                $where_arr["user_id"] = $input_params["user_id"];
            }
            $params_arr["_estatus"] = "Read";
            $this->block_result = $this->notification_model->read_notifications($params_arr, $where_arr);
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["read_notifications"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * notification_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified Devangi Nirmal | 11.06.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function notification_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "notification_finish_success_1",
        );
        $output_fields = array(
            'notification_id',
            'message',
            'notification_type',
            'from_first_name',
            'from_last_name',
            'from_user_id',
            'notification_datetime',
            'from_image',
        );
        $output_keys = array(
            'get_notification_details',
        );

        $output_array["settings"] = array_merge($this->settings_params, $setting_fields);
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "notification_list";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * notification_finish_success_3 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified Devangi Nirmal | 04.06.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function notification_finish_success_3($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "notification_finish_success_3",
        );
        $output_fields = array();

        $output_array["settings"] = array_merge($this->settings_params, $setting_fields);
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "notification_list";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * iterate_start_loop method is used to iterate loop.
     * @created Devangi Nirmal | 11.06.2019
     * @modified Devangi Nirmal | 11.06.2019
     * @param array $get_notification_details_lp_arr get_notification_details_lp_arr array to iterate loop.
     * @param array $input_params_addr $input_params_addr array to address original input params.
     */
    public function iterate_start_loop(&$get_notification_details_lp_arr = array(), &$input_params_addr = array())
    {

        $input_params_loc = $input_params_addr;
        $_loop_params_loc = $get_notification_details_lp_arr;
        $_lp_ini = 0;
        $_lp_end = count($_loop_params_loc);
        for ($i = $_lp_ini; $i < $_lp_end; $i += 1)
        {
            $get_notification_details_lp_pms = $input_params_loc;

            unset($get_notification_details_lp_pms["get_notification_details"]);
            if (is_array($_loop_params_loc[$i]))
            {
                $get_notification_details_lp_pms = $_loop_params_loc[$i]+$input_params_loc;
            }
            else
            {
                $get_notification_details_lp_pms["get_notification_details"] = $_loop_params_loc[$i];
                $_loop_params_loc[$i] = array();
                $_loop_params_loc[$i]["get_notification_details"] = $get_notification_details_lp_pms["get_notification_details"];
            }

            $get_notification_details_lp_pms["i"] = $i;
            $input_params = $get_notification_details_lp_pms;

            $input_params = $this->get_image($input_params);

            $input_params = $this->get_images($input_params);

            $get_notification_details_lp_arr[$i] = $this->wsresponse->filterLoopParams($input_params, $_loop_params_loc[$i], $get_notification_details_lp_pms);
        }
    }
}
