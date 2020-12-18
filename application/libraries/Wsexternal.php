<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of External API Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module ExternalAPI
 * 
 * @class Wsexternal.php
 * 
 * @path application\libraries\Wsexternal.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Wsexternal
{

    protected $CI;
    protected $input_params;

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    /**
     * execute_external_api method is used to perform exteranl api urls
     * @param array $api_details to external api inputs
     * @param array $input_params to input params
     * @return array 
     */
    public function execute_external_api($api_details = array(), $input_params = array())
    {
        require_once($this->CI->config->item('third_party') . 'oauth/vendor/autoload.php');
        require_once($this->CI->config->item('third_party') . 'XML2Array/xml2Array.php');

        $this->CI->oauth_client = new oauth_client_class;

        $this->input_params = $input_params;
        $api_url = $api_details['api_url'];
        $api_type = $api_details['api_type'];
        $api_method = $api_details['api_method'];
        $input_type = $api_details['input_type'];
        $api_name = $api_details['api_name'];
        $api_code = strtolower($api_details['api_code']);
        $request_header = $api_details['request_header'];
        $request_params = $api_details['request_params'];
        $post_file_keys = $api_details['post_file_keys'];
        $success_case = $api_details['success_case'];
        $failure_case = $api_details['failure_case'];
        $api_response_format = '';

        $oauth_details = array();
        if ($api_code != '') {
            require_once($this->CI->config->item('third_party') . 'oauth/oauth_config.php');
            $oauth_details = $oauth_config[$api_code];
            if (is_array($oauth_details) && count($oauth_details) > 0) {
                $base_url = $oauth_details['base_url'];
                if (!empty($base_url)) {
                    $api_url = str_replace($base_url, "", $api_url);
                    $api_url = $base_url . $api_url;
                }
            }
        }

        $noauth = (empty($oauth_details['api_name']) || $oauth_details['auth_type'] == 'noauth') ? true : false;
        if (!$noauth) {
            $this->CI->oauth_client->scope = $oauth_details['scope'];
            $this->CI->oauth_client->server = $oauth_details['api_name'];
            $this->CI->oauth_client->client_id = $oauth_details['client_id'];
            $this->CI->oauth_client->client_secret = $oauth_details['client_secret'];
            $this->CI->oauth_client->configuration_file = $this->CI->config->item('third_party') . 'oauth/oauth_configuration.json';
        }

        $header_params = array();
        if (is_array($request_header) && count($request_header) > 0) {
            foreach ((array) $request_header as $key => $val) {
                if (intval($oauth_details['auth_version']) == 1 && $key == "Authorization") {
                    //avoiding for 1.0, 1.0a oauth versions
                } else {
                    $header_params[$key] = $val;
                }
            }
        }
        if (is_array($request_params) && count($request_params) > 0) {
            foreach ((array) $request_params as $key => $val) {
                if (is_string($val) && trim($val) === "") {
                    unset($request_params[$key]);
                }
            }
        }

        try {

            if ($api_method == "GET") {
                if (is_array($request_params) && count($request_params) > 0) {
                    $post_query = http_build_query($request_params);
                    if (stristr($api_url, "?") === false) {
                        $api_url .= "?" . $post_query;
                    } else {
                        $api_url .= "&" . $post_query;
                    }
                }
                $options = array('FailOnAccessError' => true);
                if ($noauth) {
                    $success = $this->CI->oauth_client->CallAPICustom($api_url, $api_method, array(), $options, $header_params, $upload);
                } else {
                    if (($success = $this->CI->oauth_client->Initialize())) {
                        $this->CI->oauth_client->access_token = $request_params[$oauth_details['access_token_variable']];
                        $this->CI->oauth_client->access_token_secret = $request_params["access_token_secret"];
                        $this->CI->oauth_client->oauth_version = $oauth_details['auth_version'];
                        $this->CI->oauth_client->dialog_url = $oauth_details['dialogue_url'];
                        $this->CI->oauth_client->access_token_url = $oauth_details['access_token_url'];
                        $this->CI->oauth_client->url_parameters = $oauth_details['url_parameters'];
                        $this->CI->oauth_client->authorization_header = $oauth_details['authorization_header'];
                        $this->CI->oauth_client->request_token_url = $oauth_details['request_token_url'];
                        $this->CI->oauth_client->token_request_method = $oauth_details['token_request_method'];
                        $this->CI->oauth_client->access_token_authentication = $oauth_details['access_token_authentication'];
                        $this->CI->oauth_client->instance_url_parameter = $oauth_details['instance_url'];
                        if (intval($this->CI->oauth_client->oauth_version) == 1) {
//                            $oauth_token_str = $request_header['Authorization'];
//                            $oauth_token_arr = explode(',', $oauth_token_str);
//                            foreach ($oauth_token_arr as $key => $value) {
//                                if (!empty($value) && (strstr($value, $oauth_details['access_token_variable']) || strstr($value, 'oauth_token'))) {
//                                    $token_arr = explode('=', $value);
//                                    $auth_token = trim($token_arr[1], '"');
//                                }
//                            }
//                            $auth_token_secret = $request_params["access_token_secret"];
//                            if (empty($auth_token_secret)) {
//                                $auth_token_secret = $oauth_details['access_token_secret'];
//                            }
                            $auth_token = $request_params[$oauth_details['access_token_variable']];
                            $auth_token_secret = $request_params[$oauth_details["access_token_secret"]];
                            $access_token = array(
                                "value" => $auth_token,
                                "secret" => $auth_token_secret,
                                "authorized" => 1
                            );
                        } else {
                            $auth_token = $request_params[$oauth_details['access_token_variable']];
                            $access_token = array(
                                "value" => $auth_token,
                                "authorized" => 1
                            );
                        }
                        $this->CI->oauth_client->StoreAccessToken($access_token);
                        //$this->CI->oauth_client->Process();
                        $this->CI->oauth_client->access_token = $access_token['value'];
                        if (intval($this->CI->oauth_client->oauth_version) == 1) {
                            $this->CI->oauth_client->access_token_secret = $access_token['secret'];
                        }
                        $options['AccessTokenAuthentication'] = $oauth_details['access_token_authentication'];
                        if (strlen($this->CI->oauth_client->access_token)) {
                            $success = $this->CI->oauth_client->CallAPI($api_url, $api_method, array(), $options, $upload);
                        }
                        //$success = $this->CI->oauth_client->Finalize($success);
                    }
                }
                if (is_array($upload) || is_object($upload)) {
                    if (is_object($upload)) {
                        $upload = (array) $upload;
                    }
                    $result_str = json_encode($upload);
                    $api_response_format = "JSON";
                } elseif (strpos($upload, '<') === 0) {
                    $result_str = $upload;
                    $api_response_format = "XML";
                } elseif (is_string($upload)) {
                    $result_str = $upload;
                }
            } elseif ($api_method == "JSONPOST" || $api_method == "XMLPOST") {
                $options = array('FailOnAccessError' => true);
                if ($api_method == "XMLPOST") {
                    $options['RequestBody'] = $request_params;
                    $options['RequestContentType'] = 'application/xml';
                    if (isset($header_params['Content-Type'])) {
                        $options['RequestContentType'] = $header_params['Content-Type'];
                    } elseif (isset($header_params['content-type'])) {
                        $options['RequestContentType'] = $header_params['content-type'];
                    }
                    $request_params = array();
                } else if ($api_method == "JSONPOST") {
                    $options['RequestContentType'] = 'application/json';
                }
                
                if ($noauth) {
                    $api_method = "POST";
                    $success = $this->CI->oauth_client->CallAPICustom($api_url, $api_method, $request_params, $options, $header_params, $upload);
                } else {
                    if (($success = $this->CI->oauth_client->Initialize())) {
                        $this->CI->oauth_client->access_token = $request_params[$oauth_details['access_token_variable']];
                        $this->CI->oauth_client->access_token_secret = $request_params["access_token_secret"];
                        $this->CI->oauth_client->oauth_version = $oauth_details['auth_version'];
                        $this->CI->oauth_client->dialog_url = $oauth_details['dialogue_url'];
                        $this->CI->oauth_client->access_token_url = $oauth_details['access_token_url'];
                        $this->CI->oauth_client->url_parameters = $oauth_details['url_parameters'];
                        $this->CI->oauth_client->authorization_header = $oauth_details['authorization_header'];
                        $this->CI->oauth_client->request_token_url = $oauth_details['request_token_url'];
                        $this->CI->oauth_client->token_request_method = $oauth_details['token_request_method'];
                        $this->CI->oauth_client->access_token_authentication = $oauth_details['access_token_authentication'];
                        $this->CI->oauth_client->instance_url_parameter = $oauth_details['instance_url'];
                        if (intval($this->CI->oauth_client->oauth_version) == 1) {
//                            $oauth_token_str = $request_header['Authorization'];
//                            $oauth_token_arr = explode(',', $oauth_token_str);
//                            foreach ($oauth_token_arr as $key => $value) {
//                                if (!empty($value) && strstr($value, $oauth_details['access_token_variable'])) {
//                                    $token_arr = explode('=', $value);
//                                    $auth_token = trim($token_arr[1], '"');
//                                }
//                            }
//                            $auth_token_secret = $request_params["access_token_secret"];
//                            if (empty($auth_token_secret)) {
//                                $auth_token_secret = $oauth_details['access_token_secret'];
//                            }
                            $auth_token = $request_params[$oauth_details['access_token_variable']];
                            $auth_token_secret = $request_params[$oauth_details["access_token_secret"]];
                            $access_token = array(
                                "value" => $auth_token,
                                "secret" => $auth_token_secret,
                                "authorized" => 1
                            );
                        } else {
                            $auth_token = $request_params[$oauth_details['access_token_variable']];
                            $access_token = array(
                                "value" => $auth_token,
                                "authorized" => 1
                            );
                        }

                        $this->CI->oauth_client->StoreAccessToken($access_token);
                        //$this->CI->oauth_client->Process();
                        $this->CI->oauth_client->access_token = $access_token['value'];
                        if (intval($this->CI->oauth_client->oauth_version) == 1) {
                            $this->CI->oauth_client->access_token_secret = $access_token['secret'];
                        }
                        $options['AccessTokenAuthentication'] = $oauth_details['access_token_authentication'];
                        if (strlen($this->CI->oauth_client->access_token)) {
                            $success = $this->CI->oauth_client->CallAPI($api_url, $api_method, $request_params, $options, $upload);
                        }
                        //$success = $this->CI->oauth_client->Finalize($success);
                    }
                }
                if (is_array($upload) || is_object($upload)) {
                    if (is_object($upload)) {
                        $upload = (array) $upload;
                    }
                    $result_str = json_encode($upload);
                    $api_response_format = "JSON";
                } elseif (strpos($upload, '<') === 0) {
                    $result_str = $upload;
                    $api_response_format = "XML";
                } elseif (is_string($upload)) {
                    $result_str = $upload;
                }
            } else {
                //POST
                $options = array('FailOnAccessError' => true);
                if ($input_type == 'raw') {
                    if (isset($request_header['Content-Type'])) {
                        $options['RequestContentType'] = $request_header['Content-Type'];
                    } elseif ($request_header['content-type']) {
                        $options['RequestContentType'] = $request_header['content-type'];
                    }
                } else {
                    $file_array = array();
                    if (!empty($post_file_keys)) {
                        foreach ($post_file_keys as $key => $val) {
                            if (isset($request_params[$val]) && !empty($request_params[$val])) {
                                $api_file_arr = $request_params[$val];
                                $api_upload_path = $this->CI->config->item('admin_upload_temp_path');
                                list($api_file_name, $api_file_ext) = $this->CI->general->get_file_attributes($api_file_arr['name']);
                                $this->CI->general->file_upload($api_upload_path, $api_file_arr['tmp_name'], $api_file_name);
                                $request_params[$val] = $api_upload_path . $api_file_name;
                                $file_array['Files'][$val] = array();
                            }
                        }
                    }
                    $options = array_merge($options, $file_array);
                }
                if ($noauth) {
                    $success = $this->CI->oauth_client->CallAPICustom($api_url, $api_method, $request_params, $options, $header_params, $upload);
                } else {
                    if (($success = $this->CI->oauth_client->Initialize())) {
                        $this->CI->oauth_client->access_token = $request_params[$oauth_details['access_token_variable']];
                        $this->CI->oauth_client->access_token_secret = $request_params["access_token_secret"];
                        $this->CI->oauth_client->oauth_version = $oauth_details['auth_version'];
                        $this->CI->oauth_client->dialog_url = $oauth_details['dialogue_url'];
                        $this->CI->oauth_client->access_token_url = $oauth_details['access_token_url'];
                        $this->CI->oauth_client->url_parameters = $oauth_details['url_parameters'];
                        $this->CI->oauth_client->authorization_header = $oauth_details['authorization_header'];
                        $this->CI->oauth_client->request_token_url = $oauth_details['request_token_url'];
                        $this->CI->oauth_client->token_request_method = $oauth_details['token_request_method'];
                        $this->CI->oauth_client->access_token_authentication = $oauth_details['access_token_authentication'];
                        $this->CI->oauth_client->instance_url_parameter = $oauth_details['instance_url'];
                        if (intval($this->CI->oauth_client->oauth_version) == 1) {
//                            $oauth_token_str = $request_header['Authorization'];
//                            $oauth_token_arr = explode(',', $oauth_token_str);
//                            foreach ($oauth_token_arr as $key => $value) {
//                                if (!empty($value) && (strstr($value, $oauth_details['access_token_variable']) || strstr($value, 'oauth_token'))) {
//                                    $token_arr = explode('=', $value);
//                                    $auth_token = trim($token_arr[1], '"');
//                                }
//                            }
//                            $auth_token_secret = $request_params["access_token_secret"];
//                            if (empty($auth_token_secret)) {
//                                $auth_token_secret = $oauth_details['access_token_secret'];
//                            }
                            $auth_token = $request_params[$oauth_details['access_token_variable']];
                            $auth_token_secret = $request_params[$oauth_details["access_token_secret"]];
                            $access_token = array(
                                "value" => $auth_token,
                                "secret" => $auth_token_secret,
                                "authorized" => 1
                            );
                        } else {
                            $auth_token = $request_params[$oauth_details['access_token_variable']];
                            $access_token = array(
                                "value" => $auth_token,
                                "authorized" => 1
                            );
                        }
                        $this->CI->oauth_client->StoreAccessToken($access_token);
                        //$this->CI->oauth_client->Process();
                        $this->CI->oauth_client->access_token = $access_token['value'];
                        if (intval($this->CI->oauth_client->oauth_version) == 1) {
                            $this->CI->oauth_client->access_token_secret = $access_token['secret'];
                        }
                        $options['AccessTokenAuthentication'] = $oauth_details['access_token_authentication'];
                        if (strlen($this->CI->oauth_client->access_token)) {
                            $success = $this->CI->oauth_client->CallAPI($api_url, $api_method, $request_params, $options, $upload);
                        }
                        //$success = $this->CI->oauth_client->Finalize($success);
                    }
                }
                if (is_array($upload) || is_object($upload)) {
                    if (is_object($upload)) {
                        $upload = (array) $upload;
                    }
                    $result_str = json_encode($upload);
                    $api_response_format = "JSON";
                } elseif (strpos($upload, '<') === 0) {
                    $result_str = $upload;
                    $api_response_format = "XML";
                } elseif (is_string($upload)) {
                    $result_str = $upload;
                }
            }


            if ($api_response_format == "JSON") {
                $output_array_temp = json_decode($result_str, TRUE);
            } elseif ($api_response_format == "XML") {
                if (!is_array($output_array_temp)) {
                    $output_array_temp = XML2Array::createArray($result_str);
                }
            } else {
                if ($this->CI->general->isJSON($result_str)) {
                    $output_array_temp = json_decode($result_str, TRUE);
                } else {
                    parse_str($result_str, $output_array_temp);
                }
            }

            //loading log controller
            $log_api_name = "log_external_api";
            $this->CI->load->module("misc/" . $log_api_name);
            //checking for webservice controller
            if (is_object($this->CI->$log_api_name)) {
                //checking for webservice start method
                $start_method = "start_" . $log_api_name;
                if (method_exists($this->CI->$log_api_name, $start_method)) {
                    $log_headers = is_array($header_params) ? json_encode($header_params, JSON_PRETTY_PRINT) : $header_params;
                    if ($api_details['api_method'] == "JSONPOST") {
                        $tmp_request_params = json_decode($request_params, TRUE);
                        $log_postdata = is_array($tmp_request_params) ? json_encode($tmp_request_params, JSON_PRETTY_PRINT) : $request_params;
                    } else {
                        $log_postdata = is_array($request_params) ? json_encode($request_params, JSON_PRETTY_PRINT) : $request_params;
                    }
                    $log_response = is_array($output_array_temp) ? json_encode($output_array_temp, JSON_PRETTY_PRINT) : $output_array_temp;
                    $log_request = array(
                        'type' => $this->CI->uri->uri_string,
                        'method' => $api_details['api_method'],
                        'url' => $api_url,
                        'headers' => $log_headers,
                        'postdata' => $log_postdata,
                        'response' => $log_response
                    );
                    $log_response = $this->CI->$log_api_name->$start_method($log_request, TRUE);
                }
            }

            //attempt success case
            $is_true = $is_fail = NULL;
            if (is_array($success_case['condition'])) {
                $succ_condition = $success_case['condition'];
                $cond_type = $succ_condition['type'];
                $cond_oper = $succ_condition['oper'];
                $cond_val = $succ_condition['val'];
                if ($cond_type == "response") {
                    $cond_key = $succ_condition['key'];
                    $match = $this->findReponseKeyVal($output_array_temp, $cond_key, $success_case['separator']);
                    $is_true = $this->checkCondition($cond_oper, $match, $cond_val);
                } elseif ($cond_type == "status") {
                    $is_true = $this->checkCondition($cond_oper, $this->CI->oauth_client->response_status, $cond_val);
                }
                if ($is_true === TRUE) {
                    if (isset($success_case['target']) && $success_case['target'] != "") {
                        $prepare_data = $this->findReponseKeyVal($output_array_temp, $success_case['target'], $success_case['separator']);
                        $rmindex = $success_case['target'];
                    } else {
                        $prepare_data = $output_array_temp;
                        $rmindex = '';
                    }
                    if (!isset($success_case['output']) || !is_array($success_case['output']) || empty($success_case['output'])) {
                        $output_array_send = $prepare_data;
                    } else {
                        $output_array_send = $this->prepareReturnData($prepare_data, $success_case['output'], $success_case['separator'], $rmindex);
                    }
                }
            }
            //attempt failure case
            if ($is_true !== TRUE) {
                if (is_array($failure_case['condition'])) {
                    $fail_condition = $failure_case['condition'];
                    $cond_type = $fail_condition['type'];
                    $cond_oper = $fail_condition['oper'];
                    $cond_val = $fail_condition['val'];
                    if ($cond_type == "response") {
                        $cond_key = $succ_condition['key'];
                        $match = $this->findReponseKeyVal($output_array_temp, $cond_key, $failure_case['separator']);
                        $is_fail = $this->checkCondition($cond_oper, $match, $cond_val);
                    } elseif ($cond_type == "status") {
                        $is_fail = $this->checkCondition($cond_oper, $this->CI->oauth_client->response_status, $cond_val);
                    }
                    if ($is_fail === TRUE) {
                        if (isset($failure_case['target']) && $failure_case['target'] != "") {
                            $prepare_data = $this->findReponseKeyVal($output_array_temp, $failure_case['target'], $failure_case['separator']);
                            $rmindex = $failure_case['target'];
                        } else {
                            $prepare_data = $output_array_temp;
                            $rmindex = '';
                        }
                        if (!isset($failure_case['output']) || !is_array($failure_case['output']) || empty($failure_case['output'])) {
                            $output_array_send = $prepare_data;
                        } else {
                            $output_array_send = $this->prepareReturnData($prepare_data, $failure_case['output'], $failure_case['separator'], $rmindex);
                        }
                    }
                }
            }
            if ($is_true !== TRUE && $is_fail !== TRUE) {
                $output_array_send = $output_array_temp;
            }

            $success = 1;
            $message = "API executed successfully.";
        } catch (Exception $e) {
            $success = 0;
            $message = 'Something goes wrong. ' . $e->getMessage();
            $f_output_array = array();
        }

        $status_array = array();
        if (!empty($output_array_temp['error']['message'])) {
            $status_array['error_message'] = $output_array_temp['error']['message'];
            $status_array['error_type'] = $output_array_temp['error']['type'];
            $status_array['error_code'] = $output_array_temp['error']['code'];
        } elseif (!empty($this->CI->oauth_client->error)) {
            $status_array['error_message'] = $this->CI->oauth_client->error;
        }
        $status_array['status_code'] = $this->CI->oauth_client->response_status;

        $ret_arr["success"] = $success;
        $ret_arr["message"] = $message;
        $ret_arr["status"] = $status_array;
        $ret_arr["data"] = $output_array_send;

        return $ret_arr;
    }

    public function findReponseKeyVal($data = array(), $key = '', $separator = '')
    {
        if (!is_array($data) || count($data) == 0) {
            return FALSE;
        }
        if (empty($key) || trim($key) == "") {
            return FALSE;
        }
        $separator = ($separator) ? $separator : ".";
        $key_arr = explode($separator, $key);
        $match = $data;
        foreach ($key_arr as $rKey => $rVal) {
            $match = $match[$rVal];
        }
        return $match;
    }

    public function checkCondition($oper = '', $value_1 = '', $value_2 = '')
    {
        switch ($oper) {
            case 'ne':
                $flag = ($value_1 != $value_2) ? TRUE : FALSE;
                break;
            case 'lt':
                $flag = ($value_1 < $value_2) ? TRUE : FALSE;
                break;
            case 'le':
                $flag = ($value_1 <= $value_2) ? TRUE : FALSE;
                break;
            case 'gt':
                $flag = ($value_1 > $value_2) ? TRUE : FALSE;
                break;
            case 'ge':
                $flag = ($value_1 >= $value_2) ? TRUE : FALSE;
                break;
            case 'in':
                $value2 = (is_array($value_2)) ? $value_2 : explode(",", $value_2);
                $flag = (in_array($value_1, $value2)) ? TRUE : FALSE;
                break;
            case 'ni':
                $value2 = (is_array($value_2)) ? $value_2 : explode(",", $value_2);
                $flag = (!(in_array($value_1, $value2))) ? TRUE : FALSE;
                break;
            case 'nu':
                $value_1 = trim($value_1);
                $flag = (is_null($value_1) || empty($value_1) || $value_1 == '') ? TRUE : FALSE;
                break;
            case 'nn':
                $value_1 = trim($value_1);
                $flag = (!is_null($value_1) && !empty($value_1) && $value_1 != '') ? TRUE : FALSE;
                break;
            default:
                $flag = ($value_1 == $value_2) ? TRUE : FALSE;
                break;
        }
        return $flag;
    }

    public function prepareReturnData($data = array(), $output = array(), $separator = '', $rmindex = '')
    {
        $result = array();
        if (!is_array($data) || count($data) == 0) {
            return $result;
        }
        $separator = ($separator) ? $separator : ".";
        $is_assoc = $this->CI->general->isAssoc($data);
        if ($is_assoc) {
            foreach ($output as $oKey => $oVal) {
                $temp = $data;
                list($temp, $found) = $this->checkReturnData($oKey, $temp, $separator, $rmindex);
                if ($found) {
                    if (is_array($oVal['children'])) {
                        $result[$oVal['key_name']] = $this->prepareReturnData($temp, $oVal['children'], $separator);
                    } else {
                        if (isset($oVal['php_func']) && $oVal['php_func'] != "") {
                            $result[$oVal['key_name']] = $this->call_php_function($oVal['php_func'], $temp, $data);
                        } else {
                            $result[$oVal['key_name']] = $temp;
                        }
                    }
                }
            }
        } else {
            foreach ($data as $dKey => $dVal) {
                foreach ($output as $oKey => $oVal) {
                    $temp = $dVal;
                    list($temp, $found) = $this->checkReturnData($oKey, $temp, $separator, $rmindex);
                    if ($found) {
                        if (is_array($oVal['children'])) {
                            $result[$dKey][$oVal['key_name']] = $this->prepareReturnData($temp, $oVal['children'], $separator);
                        } else {
                            if (isset($oVal['php_func']) && $oVal['php_func'] != "") {
                                $result[$dKey][$oVal['key_name']] = $this->call_php_function($oVal['php_func'], $temp, $dVal, $dKey);
                            } else {
                                $result[$dKey][$oVal['key_name']] = $temp;
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function checkReturnData($key = '', $data = array(), $separator = '.', $rmindex = '')
    {
        if ($rmindex != '') {
            $key = substr_replace($key, "", 0, strlen($rmindex . $separator));
        }
        $found = FALSE;
        $key_arr = explode($separator, $key);
        foreach ($key_arr as $rKey => $rVal) {
            if (!is_array($data)) {
                continue;
            }
            if (array_key_exists($rVal, $data) || (is_array($data[0]) && array_key_exists($rVal, $data[0]))) {
                $data = $data[$rVal];
                $found = TRUE;
            }
        }
        return array($data, $found);
    }

    /**
     * call_php_function method is used to perform php function on external api params
     * @param array $php_func to perform function execution on params
     * @param array $val to perform function execution on params
     * @param array $data to specified data set
     * @param string $key to send extra key information
     * @return array 
     */
    public function call_php_function($php_func = '', $val = array(), $data = array(), $key = '')
    {
        $ret_val = $val;
        if (function_exists($php_func)) {
            $ret_val = call_user_func($php_func, $val);
        } elseif (method_exists($this->CI->general, $php_func)) {
            $ret_val = $this->CI->general->$php_func($val, $data, $key);
        }
        return $ret_val;
    }

    /**
     * process_external_api method is used to perform exteranl api urls
     * @param array $ws_details to webservices inputs
     * @param array $input_params to input params
     * @param array $ws_header_input_arr to header input params
     * @param array $ws_url_input_arr to url input params
     * @return array 
     */
    public function process_external_api($ws_details = array(), $input_params = array(), $ws_header_input_arr = array(), $ws_url_input_arr = array(), $ws_static_params = array(), $ws_flow_id = 0)
    {
        require_once($this->CI->config->item('third_party') . 'api_oauth/Oauth_client.php');
        require_once($this->CI->config->item('third_party') . 'api_oauth/Http.php');
        require_once($this->CI->config->item('third_party') . 'XML2Array/xml2Array.php');
        require_once($this->CI->config->item('third_party') . 'hash/Hash.php');

        $this->CI->oauth_client = new Oauth_client();
        $this->CI->http = new http();

        if (empty($ws_url_input_arr)) {
            $ws_url_input_arr = $input_params;
        }
        $ws_url = $ws_details['api_url'];
        $ws_url_type = $ws_details['api_format'];
        $api_method = $ws_details['api_method'];
        $input_type = $ws_details['input_type'];
        $specific_key = $ws_details['loopingkey'];
        $array_type = $ws_details['selectkey'];
        $results_json = $ws_details['mainjson'];
        $sub_results_json = $ws_details['parentchildjson'];
        $json_post = $ws_details['json_post'];
        $full_response = $ws_details['full_response'];
        $request_fields = $ws_details["request_fields"];
        $function_arr = $ws_details['php_function'];
        $api_details = $ws_details['api_detail'];
        $static_values = $ws_details['static_values'];
        $oauth_ver = $api_details['vOauthVersion'];

        $results = json_decode($results_json, true);
        $sub_results = json_decode($sub_results_json, true);
        $sub_results = is_array($sub_results) ? array_filter($sub_results) : array();

        if (is_array($ws_url_input_arr) && count($ws_url_input_arr) > 0) {
            foreach ((array) $ws_url_input_arr as $key => $val) {
                $ws_url = str_replace("{%REQUEST." . $key . "%}", urlencode(trim($ws_url_input_arr[$key])), $ws_url);
                $ws_url = str_replace("{%QSTRING." . $key . "%}", trim($ws_url_input_arr[$key]), $ws_url);
            }
        }
        $ws_url = $this->CI->general->processSystemPregMatch($ws_url);
        $extra_headers = array();
        if (is_array($ws_header_input_arr) && count($ws_header_input_arr) > 0) {
            foreach ((array) $ws_header_input_arr as $k => $v) {
                preg_match_all('/"(.*?)"/', $v, $matches);
                for ($j = 0; $j < count($matches[1]); $j++) {
                    $v = str_replace($matches[1][$j], urlencode(trim($matches[1][$j])), $v);
                }
                $extra_headers[] = $k . ":" . $v;
            }
        }

        try {
            if ($api_method == "GET") {

                if (is_array($input_params) && count($input_params) > 0) {
                    foreach ($input_params as $key => $val) {
                        if (is_array($request_fields) && count($request_fields) > 0) {
                            $request_key_arr = array_keys($request_fields);
                            if (in_array($key, $request_key_arr) && $val != '') {
                                $post_query .= "&" . $key . "=" . urlencode(trim($val));
                            }
                        }
                    }
                }
                if ($post_query != "") {
                    $post_query = utf8_encode(trim($post_query, "&"));
                    if (stristr($ws_url, "?") === false) {
                        $post_query = "?" . $post_query;
                    } else {
                        $post_query = "&" . $post_query;
                    }
                }
                $ws_url .= $post_query;

                if (empty($api_details)) {
                    if (!empty($ws_header_input_arr)) {
                        foreach ($ws_header_input_arr as $key => $val) {
                            $ws_header_input_arr[$key] = urlencode($val);
                        }
                    }
                    $success = $this->CI->oauth_client->CallAPICustom($ws_url, $api_method, array(), array('FailOnAccessError' => true), $ws_header_input_arr, $upload);

                    $success = $this->CI->oauth_client->Finalize($success);
                    if (is_array($upload) || is_object($upload)) {
                        if (is_object($upload)) {
                            $upload = (array) $upload;
                        }
                        $result_str = json_encode($upload);
                        if ($ws_url_type == '') {
                            $ws_url_type = "JSON";
                        }
                    } elseif (strpos($upload, '<') === 0) {
                        $result_str = $upload;
                        if ($ws_url_type == '') {
                            $ws_url_type = "XML";
                        }
                    }
                } else {

                    if ($api_details['vAuthType'] == 'noauth') {
                        // no auth versions
                        $result_str = $this->curl_get($ws_url, $extra_headers); //GET
                    } else {
                        // regular request
                        $this->CI->oauth_client->server = $ws_details['api_detail']['vApiName'];
                        $this->CI->oauth_client->client_id = $api_details['vClientId'];
                        $this->CI->oauth_client->client_secret = $api_details['vClientSecret'];
                        if (($success = $this->CI->oauth_client->Initialize($api_details))) {

                            if (intval($oauth_ver) == 1) {
                                $oauthtokenstr = $ws_header_input_arr['Authorization'];
                                $oauthtokenstrarr = explode(',', $oauthtokenstr);
                                foreach ($oauthtokenstrarr as $key => $value) {
                                    if (!empty($value) && (strstr($value, $api_details['vAccessTokenVariable']) || strstr($value, 'oauth_token'))) {
                                        $new_val = explode('=', $value);
                                        $auth_token = trim($new_val[1], '"');
                                    }
                                }
                                $access_token = array(
                                    "value" => $auth_token,
                                    "secret" => $ws_details['api_secret_key'],
                                    "authorized" => 1
                                );
                            } else {
                                $access_token = array(
                                    "value" => $input_params[$api_details['vAccessTokenVariable']],
                                    "authorized" => 1
                                );
                            }

                            $this->CI->oauth_client->StoreAccessToken($access_token);
                            if (($success = $this->CI->oauth_client->Process())) {
                                if (strlen($this->CI->oauth_client->access_token)) {
                                    $success = $this->CI->oauth_client->CallAPI($ws_url, $api_method, array(), array('FailOnAccessError' => true, 'AccessTokenAuthentication' => $api_details['vAccessTokenAuthentication']), $upload);
                                }
                            }
                            $success = $this->CI->oauth_client->Finalize($success);

                            if (is_array($upload) || is_object($upload)) {
                                if (is_object($upload)) {
                                    $upload = (array) $upload;
                                }
                                $result_str = json_encode($upload);
                                if ($ws_url_type == '') {
                                    $ws_url_type = "JSON";
                                }
                            } elseif (strpos($upload, '<') === 0) {
                                $result_str = $upload;
                                if ($ws_url_type == '') {
                                    $ws_url_type = "XML";
                                }
                            }
                        }
                    }
                }
            } elseif ($api_method == "JSONPOST" || $api_method == "XMLPOST") {

                if (is_array($_REQUEST) && count($_REQUEST) > 0) {
                    foreach ((array) $_REQUEST as $k => $v) {
                        $json_post = str_replace("{%REQUEST." . $k . "%}", $v, $json_post);
                    }
                }
                $post_params = $json_post;
                if ($ws_details['api_detail']['vApiName'] == '' || $ws_details['api_detail']['vAuthType'] == 'noauth') {
                    $success = $this->CI->oauth_client->CallAPICustom($ws_url, $api_method, $post_params, array_merge(array('FailOnAccessError' => true), $file_array), $ws_header_input_arr, $upload);
                } else {
                    $this->CI->oauth_client->server = $ws_details['api_detail']['vApiName'];
                    $this->CI->oauth_client->client_id = $api_details['vClientId'];
                    $this->CI->oauth_client->client_secret = $api_details['vClientSecret'];
                    if (($success = $this->CI->oauth_client->Initialize($api_details))) {
                        if (intval($oauth_ver) == 1) {
                            $oauthtokenstr = $_REQUEST['token'];
                            $oauthtokenstrarr = explode(',', $oauthtokenstr);
                            foreach ($oauthtokenstrarr as $key => $value) {
                                if (!empty($value) && strstr($value, $api_details['vAccessTokenVariable'])) {
                                    $new_val = explode('=', $value);
                                    $auth_token = trim($new_val[1], '"');
                                }
                            }
                            $access_token = array(
                                "value" => $auth_token,
                                "secret" => $ws_details['api_secret_key'],
                                "authorized" => 1
                            );
                            $this->CI->oauth_client->StoreAccessToken($access_token);
                            $this->CI->oauth_client->Process();
                            $this->CI->oauth_client->access_token = $auth_token;
                        } else {
                            $access_token = array(
                                "value" => $input_params[$api_details['vAccessTokenVariable']],
                                "authorized" => 1
                            );
                            $this->CI->oauth_client->StoreAccessToken($access_token);
                            $this->CI->oauth_client->Process();
                        }

                        if (strlen($this->CI->oauth_client->access_token)) {
                            $success = $this->CI->oauth_client->CallAPI($ws_url, $api_method, $post_params, array_merge(array('FailOnAccessError' => true, 'AccessTokenAuthentication' => $api_details['vAccessTokenAuthentication']), (array) $file_array), $upload);
                        }
                    }
                }

                if (is_array($upload) || is_object($upload)) {
                    if (is_object($upload)) {
                        $upload = (array) $upload;
                    }
                    $result_str = json_encode($upload);
                    if ($ws_url_type == '') {
                        $ws_url_type = "JSON";
                    }
                } elseif (strpos($upload, '<') === 0) {
                    $result_str = $upload;
                    if ($ws_url_type == '') {
                        $ws_url_type = "XML";
                    }
                }
            } else {
                /* POST CHANGES */
                $tokenstring = $input_params['access_token'];
                unset($input_params['access_token']);

                if ($input_type == 'raw') {
                    if (is_array($_REQUEST) && count($_REQUEST) > 0) {
                        foreach ((array) $_REQUEST as $k => $v) {
                            $json_post = str_replace("{%REQUEST." . $k . "%}", $v, $json_post);
                        }
                    }
                    $input_params = $json_post;
                    $RequestContentType = $ws_header_input_arr['Content-Type'];
                    $options = array('FailOnAccessError' => true, 'RequestContentType' => urldecode($RequestContentType));
                } else {
                    foreach ($ws_static_params as $key => $value) {
                        if ($value != '') {
                            preg_match_all("/{%REQUEST\.([a-zA-Z0-9_-]{1,})/i", $value, $preg_all_arr);
                            if (strstr($value, '{%REQUEST') !== false) {
                                if (isset($preg_all_arr[1]) && is_array($preg_all_arr[1]) && count($preg_all_arr[1]) > 0) {
                                    $file_parameter = $preg_all_arr[1][0];
                                    if (isset($_FILES[$file_parameter])) {
                                        $_FILES[$key] = $_FILES[$file_parameter];
                                        if ($file_parameter != $key) {
                                            unset($_FILES[$file_parameter]);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $file_params = $_FILES;

                    foreach ($file_params as $key => $val) {
                        if (isset($input_params[$key])) {
                            $input_params[$key] = $file_params[$key]['tmp_name'];
                        }
                    }

                    $file_array = array();
                    if (isset($file_params) && !empty($file_params)) {

                        $file_array['Files'] = array(
                            key($file_params) => array()
                        );
                    }
                    $input_params = array_filter($input_params);
                    $options = array_merge(array('FailOnAccessError' => true), $file_array);
                }

                if ($ws_details['api_detail']['vApiName'] == '' || $ws_details['api_detail']['vAuthType'] == 'noauth') {
                    foreach ($ws_header_input_arr as $key => $val) {
                        if ($key == 'Authorization') {
                            $ws_header_input_arr[$key] = urlencode($val);
                        }
                    }
                    $success = $this->CI->oauth_client->CallAPICustom($ws_url, $api_method, $input_params, $options, $ws_header_input_arr, $upload);
                } else {
                    $this->CI->oauth_client->server = $ws_details['api_detail']['vApiName'];
                    $this->CI->oauth_client->client_id = $api_details['vClientId'];
                    $this->CI->oauth_client->client_secret = $api_details['vClientSecret'];
                    if (($success = $this->CI->oauth_client->Initialize($api_details))) {

                        if (intval($oauth_ver) == 1) {
                            $oauthtokenstr = $ws_header_input_arr['Authorization'];
                            $oauthtokenstrarr = explode(',', $oauthtokenstr);
                            foreach ($oauthtokenstrarr as $key => $value) {
                                if (!empty($value) && (strstr($value, $api_details['vAccessTokenVariable']) || strstr($value, 'oauth_token'))) {
                                    $new_val = explode('=', $value);
                                    $auth_token = trim($new_val[1], '"');
                                }
                            }
                            $access_token = array(
                                "value" => $auth_token,
                                "secret" => $ws_details['api_secret_key'],
                                "authorized" => 1
                            );
                        } else {
                            if (strstr($tokenstring, $api_details['vAccessTokenVariable']) || strstr($tokenstring, 'oauth_token')) {
                                $new_val = explode('=', $tokenstring);
                                $auth_token = trim($new_val[1], '"');
                            }
                            $access_token = array(
                                "value" => $auth_token,
                                "authorized" => 1
                            );
                        }

                        $this->CI->oauth_client->StoreAccessToken($access_token);
                        $this->CI->oauth_client->Process();

                        $oauthtokenstr = $_REQUEST['token'];
                        $oauthtokenstrarr = explode(',', $oauthtokenstr);
                        foreach ($oauthtokenstrarr as $key => $value) {
                            if (!empty($value) && (strstr($value, $api_details['vAccessTokenVariable']) || strstr($value, 'oauth_token'))) {
                                $new_val = explode('=', $value);
                                $auth_token = trim($new_val[1], '"');
                            }
                        }
                        $this->CI->oauth_client->access_token = $auth_token;
                        if (strlen($this->CI->oauth_client->access_token)) {
                            $success = $this->CI->oauth_client->CallAPI($ws_url, $api_method, $input_params, array_merge(array('FailOnAccessError' => true, 'AccessTokenAuthentication' => $api_details['vAccessTokenAuthentication']), (array) $file_array), $upload);
                        }
                    }
                    /* POST CHANGES */
                }

                $success = $this->CI->oauth_client->Finalize($success);
                if (is_array($upload) || is_object($upload)) {
                    if (is_object($upload)) {
                        $upload = (array) $upload;
                    }
                    $result_str = json_encode($upload);
                    if ($ws_url_type == '') {
                        $ws_url_type = "JSON";
                    }
                } elseif (strpos($upload, '<') === 0) {
                    $result_str = $upload;
                    if ($ws_url_type == '') {
                        $ws_url_type = "XML";
                    }
                }
            }


            if ($ws_url_type == "JSON") {
                $output_array_temp = json_decode($result_str, 1);
            } elseif ($ws_url_type == "XML") {
                if (!is_array($output_array_temp)) {
                    $output_array_temp = XML2Array::createArray($result_str);
                }
            } elseif ($ws_url_type == "OTHER") {
                parse_str($result_str, $output_array_temp);
            }

            if ($full_response == "Yes") {
                if ($array_type == "multiple") {
                    $specific_key = str_replace("HBMPMASTER#*#", "", $specific_key);
                    if (trim($specific_key)) {
                        $specific_arr = explode("#*#", $specific_key);
                        $fetching_arr = $output_array_temp;
                        for ($i = 0; $i < count($specific_arr); $i++) {
                            $fetching_arr = $fetching_arr[$specific_arr[$i]];
                            if (!is_array($fetching_arr)) {
                                break;
                            }
                        }
                        $f_output_array = $fetching_arr;
                    } else {
                        $f_output_array = $output_array_temp;
                    }
                } else {
                    $f_output_array = $output_array_temp;
                }
            } else {
                if ($array_type == "multiple") {
                    $output_array['HBMPMASTER'] = $output_array_temp;
                    $specific_key = str_replace("#*#", ".", $specific_key);
                    $output_array1 = Hash::extract($output_array, $specific_key);
                    $output_array['HBMPMASTER'] = $output_array1;
                } else {
                    $output_array['HBMPMASTER'] = $output_array_temp;
                }
                $ser_output_array = Hash::flatten($output_array);
                $f_output_array = $inner_array = array();

                if ($array_type == "multiple") {
                    if (is_array($ser_output_array) && count($ser_output_array) > 0) {
                        foreach ($ser_output_array as $k => $v) {
                            $k = str_replace("HBMPMASTER.", "", $k);
                            foreach ((array) $results as $key => $val) {
                                $old_val = $val;
                                $last_key = end(explode(".", $key));
                                $first_key = current(explode(".", $val));
                                $first_k = current(explode(".", $k));

                                $first_arr = explode(".", $k);
                                $first_key_arr = explode(".", $val);

                                $val = preg_replace('/' . $first_key . '/', $first_k, $val, 1);
                                $key = preg_replace('/' . $first_key . '/', $first_k, $key, 1);

                                if ($sub_results[$old_val] == "yes") {
                                    foreach ($sub_results as $nk => $nv) {
                                        if ($nk == $old_val) {
                                            break;
                                        }
                                        if (strpos($val, $nk) === 0) {
                                            $ink = current(explode(".", str_replace($nk . ".", "", $k)));
                                            $rpk = current(explode(".", str_replace($nk . ".", "", $val)));
                                            $val = str_replace($nk . '.' . $rpk, $nk . "." . $ink, $val);
                                            $inner_array[$nk . '.0'] = $nk . "." . $ink;
                                        }
                                    }
                                    $looping_key = $val;
                                    $last_looping_key = end(explode(".", $val));
                                }
                                foreach ($inner_array as $ik => $iv) {
                                    if (strpos($val, $ik) === 0) {
                                        $val = $key = str_replace($ik, $iv, $val);
                                    }
                                }
                                if (strpos($k, $looping_key) === 0) {
                                    $new_k = str_replace($looping_key . ".", "", $k);
                                    $new_k_a = current(explode(".", $new_k));
                                    $val = str_replace($last_looping_key . ".0", $last_looping_key . "." . $new_k_a, $val);
                                    $key = str_replace($last_looping_key . ".0", $last_looping_key . "." . $new_k_a, $key);
                                }
                                if ($k === $val) {
                                    $val = "HBMPMASTER." . $val;
                                    $hm_arr = Hash::extract($output_array, $val);
                                    $hm_key = key($hm_arr);
                                    $current_key_arr = explode(".", $key);
                                    array_pop($current_key_arr);
                                    $new_key = implode(".", $current_key_arr);
                                    $temp_str = Hash::extract($f_output_array, $new_key);
                                    if (!is_array($temp_str[0]) && $temp_str[0] != "") {
                                        $inner_curr_key = array_pop($current_key_arr);
                                        $temp_arr = array();
                                        $temp_arr[$inner_curr_key] = $temp_str[0];
                                        $f_output_array = Hash::insert($f_output_array, $new_key, $temp_arr);
                                    }
                                    $current_key_arr = explode(".", $key);
                                    $last_key = end($current_key_arr);
                                    if (is_numeric($last_key)) {
                                        array_pop($current_key_arr);
                                        $new_key = implode(".", $current_key_arr);
                                        $ret_hm_arr = $this->apply_php_function($hm_arr, $function_arr);
                                        $f_output_array = Hash::insert($f_output_array, $new_key, $ret_hm_arr);
                                    } else {
                                        $ret_hm_arr = $this->apply_php_function($hm_arr, $function_arr, $last_key);
                                        $f_output_array = Hash::insert($f_output_array, $key, $ret_hm_arr[$hm_key]);
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if (is_array($results) && count($results) > 0) {
                        foreach ((array) $results as $key => $val) {
                            if (is_array($ser_output_array) && count($ser_output_array) > 0) {
                                foreach ((array) $ser_output_array as $k => $v) {
                                    $old_val = $val;
                                    $new_k = str_replace("HBMPMASTER.", "", $k);
                                    $first_k = current(explode(".", $new_k));
                                    $first_key = current(explode(".", $val));

                                    $first_arr = explode(".", $new_k);
                                    $first_key_arr = explode(".", $val);

                                    foreach ($results as $k1 => $v1) {
                                        if ($sub_results[$v1] == "yes") {
                                            if (strpos($val, $v1) === 0) {
                                                if (strpos($new_k, $v1) === 0) {
                                                    $llop = str_replace($v1 . ".", "", $new_k);
                                                    $llop_elem = current(explode(".", $llop));
                                                    $llop1 = str_replace($v1 . ".", "", $val);
                                                    $llop_elem1 = current(explode(".", $llop1));
                                                    $val = str_replace($v1 . "." . $llop_elem1, $v1 . "." . $llop_elem, $val);
                                                    $key = str_replace($k1 . "." . $llop_elem1, $k1 . "." . $llop_elem, $key);
                                                }
                                            }
                                        }
                                    }
                                    $new_val = $val;
                                    $new_key = $key;
                                    if ($sub_results[$old_val] == "yes") {
                                        $looping_key = $val;
                                        $looping_left_key = $key;
                                        if (strpos($val, '.') !== false) {
                                            $last_looping_key = end(explode(".", $val));
                                        } else {
                                            $last_looping_key = $val;
                                        }
                                    }

                                    $new_val_t = str_replace("^", ".", $new_val);

                                    if ($new_k === $new_val_t) {
                                        $val_temp = "HBMPMASTER." . $new_val;

                                        $hm_arr = Hash::extract($output_array, $val_temp);
                                        if (count($hm_arr) > 0) {
                                            $hm_key = key($hm_arr);
                                            $current_key_arr = explode(".", $key);
                                            array_pop($current_key_arr);
                                            $new_key_1 = implode(".", $current_key_arr);
                                            $temp_str = Hash::extract($f_output_array, $new_key_1);
                                            if (!is_array($temp_str[0]) && $temp_str[0] != "") {
                                                $inner_curr_key = array_pop($current_key_arr);
                                                $temp_arr = array();
                                                $temp_arr[$inner_curr_key] = $temp_str[0];
                                                $f_output_array = Hash::insert($f_output_array, $new_key_1, $temp_arr);
                                            }
                                            $current_key_arr = explode(".", $new_key);
                                            $last_key = end($current_key_arr);
                                            if (is_numeric($last_key)) {
                                                array_pop($current_key_arr);
                                                $new_key_2 = implode(".", $current_key_arr);
                                                $ret_hm_arr = $this->apply_php_function($hm_arr, $function_arr);
                                                $f_output_array = Hash::insert($f_output_array, $new_key_2, $ret_hm_arr);
                                            } else {
                                                $ret_hm_arr = $this->apply_php_function($hm_arr, $function_arr, $last_key);
                                                $f_output_array = Hash::insert($f_output_array, $new_key, $ret_hm_arr[$hm_key]);
                                            }
                                        } else {
                                            $val_temp = str_replace("^", ".", $val_temp);
                                            $hm_value = $ser_output_array[$val_temp];
                                            $hm_arr[] = $hm_value;

                                            $ret_hm_arr = $this->apply_php_function($hm_arr, $function_arr, $last_key);
                                            $f_output_array = Hash::insert($f_output_array, $new_key, $ret_hm_arr);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (is_array($f_output_array[0]) && count($f_output_array[0]) > 0) {
                        $f_output_array = $f_output_array[0];
                    }
                }
            }

            $success = 1;
            $message = "API executed successfully.";
        } catch (Exception $e) {
            $success = 0;
            $message = 'Something goes wrong. ' . $e->getMessage();
            $f_output_array = array();
        }
        $status_array = array();
        if (!empty($output_array_temp['error']['message'])) {
            $f_output_array[0]['error_message'] = $status_array['error_message'] = $output_array_temp['error']['message'];
            $f_output_array[0]['error_type'] = $status_array['error_type'] = $output_array_temp['error']['type'];
            $f_output_array[0]['error_code'] = $status_array['error_code'] = $output_array_temp['error']['code'];
        } elseif (!empty($this->CI->oauth_client->error)) {
            $f_output_array[0]['error_message'] = $status_array['error_code'] = $this->CI->oauth_client->error;
        }
        $status_array['status_code'] = $this->CI->oauth_client->response_status;

        $ret_arr["success"] = $success;
        $ret_arr["message"] = $message;
        $ret_arr["status"] = $status_array;
        $ret_arr["data"] = $f_output_array;

        return $ret_arr;
    }

    /**
     * apply_php_function method is used to perform php function on external api params
     * @param array $arr to perform function execution on params
     * @param array $function_arr to specifiy respected functions
     * @param string $key to send extra key information
     * @return array 
     */
    protected function apply_php_function($arr = array(), $function_arr = array(), $key = '')
    {
        if (is_array($arr) && count($arr) > 0) {
            foreach ((array) $arr as $k => $v) {
                if ($key != '') {
                    $func_key = $key;
                } else {
                    $func_key = $k;
                }
                if (isset($function_arr[$func_key])) {
                    $php_function = $function_arr[$func_key];
                    if (function_exists($php_function)) {
                        $fvalue[$k] = call_user_func($php_function, $v);
                    } elseif (method_exists($this->CI->general, $php_function)) {
                        $fvalue[$k] = $this->CI->general->$php_function($v);
                    }
                } else {
                    $fvalue = $arr;
                }
            }
        }
        return $fvalue;
    }

    /**
     * curl_post method is used to send a POST request using cURL 
     * @param string $url to request specified url
     * @param array $post values to send post request params
     * @param array $headers to send extra header information
     * @param string $method to process different post formats
     * @param array $options for cURL execution
     * @return string 
     */
    public function curl_post($url = '', $post = array(), $headers = array(), $method = '', $options = array())
    {
        if (is_array($post) && count($post) > 0) {
            $post_query = http_build_query($post);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        if ($method == "JSONPOST" || $method == "XMLPOST") {
            if (is_array($post) && count($post) > 0) {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl, CURLOPT_POST, TRUE);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post_query);
                if ($method == "JSONPOST") {
                    $str_val = 'application/json';
                } else {
                    $str_val = 'text/xml';
                }
                $headers = (is_array($headers)) ? $headers : array();
                $headers = array_merge($headers, array(
                    'Content-Type: ' . $str_val,
                    'Content-Length: ' . strlen($post_query))
                );
            }
        } elseif ($ws_method == "PUT") {
            if (is_array($post) && count($post) > 0) {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post_query);
            }
        } elseif ($ws_method == "DELETE") {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        } else {
            if (is_array($post) && count($post) > 0) {
                curl_setopt($curl, CURLOPT_POST, TRUE);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post_query);
            }
        }
        //curl_setopt($curl, CURLOPT_PROXY, $proxy);
        //curl_setopt($curl, CURLOPT_USERPWD, $userpass);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (http://www.googlebot.com/bot.html)');
        curl_setopt($curl, CURLOPT_REFERER, "http://www.google.com/bot.html");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 100);
        curl_setopt($curl, CURLOPT_COOKIEJAR, "cookie.txt");
        curl_setopt($curl, CURLOPT_COOKIEFILE, "cookie.txt");
        //curl_setopt($curl, CURLOPT_MAXREDIRS, 5);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, TRUE);
        curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);
        if (is_array($headers) && count($headers) > 0) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        $contents = curl_exec($curl);
        if ($ws_method == "DELETE" || $ws_method == "PUT") {
            if (trim($contents) == "") {
                $contents_arr["success"] = "1";
                $contents_arr["response"] = "success";
                $contents = json_encode($contents_arr);
            }
        } elseif (!$contents) {
            $contents_arr["success"] = "0";
            $contents_arr['message'] = curl_error($curl);
            $contents = json_encode($contents_arr);
        } else {
            $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $header_response = substr($contents, 0, $header_size);
            $contents = substr($contents, $header_size);
            $header_parse_arr = $this->get_headers_from_curl_response($header_response);
            if ($header_parse_arr['Content-Encoding'] == "gzip") {
                $contents = gzdecode($contents);
            } elseif ($header_parse_arr['Content-Encoding'] == "deflate") {
                $contents = gzinflate($contents);
            }
        }
        curl_close($curl);
        return $contents;
    }

    /**
     * curl_get method is used to send a GET request using cURL 
     * @param string $url to request specified url
     * @param array $get values to send get request params
     * @param array $headers to send extra header information
     * @param array $options for cURL execution
     * @return string 
     */
    public function curl_get($url = '', $get = array(), $headers = array(), $options = array())
    {
        if (is_array($get) && count($get) > 0) {
            $get_query = http_build_query($get);
            if (stristr($url, "?") === false) {
                $url .= "?" . $get_query;
            } else {
                $url .= "&" . $get_query;
            }
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        //curl_setopt($curl, CURLOPT_PROXY, $proxy);
        //curl_setopt($curl, CURLOPT_USERPWD, $userpass);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (http://www.googlebot.com/bot.html)');
        curl_setopt($curl, CURLOPT_REFERER, "http://www.google.com/bot.html");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 100);
        curl_setopt($curl, CURLOPT_COOKIEJAR, "cookie.txt");
        curl_setopt($curl, CURLOPT_COOKIEFILE, "cookie.txt");
        //curl_setopt($curl, CURLOPT_MAXREDIRS, 5);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, TRUE);
        curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);
        if (is_array($headers) && count($headers) > 0) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        if (!$contents = curl_exec($curl)) {
            $contents = file_get_contents($url);
        } else {
            $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $header_response = substr($contents, 0, $header_size);
            $contents = substr($contents, $header_size);
            $header_parse_arr = $this->get_headers_from_curl_response($header_response);
            if ($header_parse_arr['Content-Encoding'] == "gzip") {
                $contents = gzdecode($contents);
            } elseif ($header_parse_arr['Content-Encoding'] == "deflate") {
                $contents = gzinflate($contents);
            }
        }
        curl_close($curl);
        return $contents;
    }

    public function get_headers_from_curl_response($response = '')
    {
        $headers = array();
        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
        foreach (explode("\r\n", $header_text) as $i => $line) {
            if ($i === 0) {
                $headers['http_code'] = $line;
            } else {
                list ($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
        }
        return $headers;
    }
}

/* End of file Wsexternal.php */
/* Location: ./application/libraries/Wsexternal.php */
