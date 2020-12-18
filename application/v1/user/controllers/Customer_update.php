<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Customer Update Controller
 *
 * @category webservice
 *
 * @package user
 *
 * @subpackage controllers
 *
 * @module Customer Update
 *
 * @class Customer_update.php
 *
 * @path application\webservice\user\controllers\Customer_update.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 06.09.2019
 */

class Customer_update extends Cit_Controller
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
            "update_customer_data",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('customer_update_model');
        $this->load->model("user/customer_model");
    }

    /**
     * rules_customer_update method is used to validate api input params.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_customer_update($request_arr = array())
    {
        $valid_arr = array(
            "customer_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "customer_id_required",
                )
            ),
            "first_name" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "first_name_required",
                )
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "customer_update");

        return $valid_res;
    }

    /**
     * start_customer_update method is used to initiate api execution flow.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_customer_update($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_customer_update($request_arr);
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

            $input_params = $this->update_customer_data($input_params);

            $condition_res = $this->is_customer_updated($input_params);
            if ($condition_res["success"])
            {

                $output_response = $this->finish_customer_update_success($input_params);
                return $output_response;
            }

            else
            {

                $output_response = $this->finish_customer_update_failure($input_params);
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
     * update_customer_data method is used to process query block.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function update_customer_data($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = $where_arr = array();
            if (isset($input_params["customer_id"]))
            {
                $where_arr["customer_id"] = $input_params["customer_id"];
            }
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
            if (isset($images_arr["profile_image"]["name"]))
            {
                $params_arr["profile_image"] = $images_arr["profile_image"]["name"];
            }
            $this->block_result = $this->customer_model->update_customer_data($params_arr, $where_arr);
            if (!$this->block_result["success"])
            {
                throw new Exception("updation failed.");
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
        $input_params["update_customer_data"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * is_customer_updated method is used to process conditions.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_customer_updated($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["update_customer_data"]) ? 0 : 1);
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
     * finish_customer_update_success method is used to process finish flow.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_customer_update_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "finish_customer_update_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "customer_update";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(0);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * finish_customer_update_failure method is used to process finish flow.
     * @created  | 29.01.2016
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_customer_update_failure($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "finish_customer_update_failure",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "customer_update";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(0);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
