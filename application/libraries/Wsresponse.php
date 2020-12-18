<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of API Response Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module APIResponse
 * 
 * @class Wsresponse.php
 * 
 * @path application\libraries\Wsresponse.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Wsresponse
{

    protected $CI;
    protected $_res_format = array(
        'json' => 'application/json',
        'xml' => 'application/xml',
        'csv' => 'application/csv',
        'html' => 'text/html',
        'serialized' => 'application/vnd.php.serialized'
    );
    public $ws_debug_params;
    public $ws_log_file;

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function outputResponse($output_array = array(), $func_array = array())
    {
        $data_array = $output_array['data'];
        $ouput_fields = $output_array['settings']['fields'];
        $output_keys = $func_array['function']['output_keys'];
        $output_alias = $func_array['function']['output_alias'];
        $output_objects = $func_array['function']['output_objects'];
        $inner_keys = $func_array['function']['inner_keys'];
        $single_keys = $func_array['function']['single_keys'];
        $multiple_keys = $func_array['function']['multiple_keys'];
        $custom_keys = $func_array['function']['custom_keys'];

        $this->makeUniqueParams($ouput_fields);
        $this->makeUniqueParams($output_keys);
        $this->makeUniqueParams($single_keys);
        $this->makeUniqueParams($multiple_keys);
        $this->makeUniqueParams($custom_keys);

        $output_keys = is_array($output_keys) ? $output_keys : array();
        $output_alias = is_array($output_alias) ? $output_alias : array();
        $inner_keys = is_array($inner_keys) ? $inner_keys : array();

        $array_data = $this->getFilteredArray($data_array, $ouput_fields, $output_keys, $inner_keys, $custom_keys);
        $array_data = $this->makeAliasArray($array_data, $output_alias, $custom_keys, $output_objects);
        $output_data = $this->finalResponseArray($array_data, $output_objects, $output_alias, $single_keys, $multiple_keys, $custom_keys);

        $settings_fields = $this->makeFieldsArray($ouput_fields, $output_alias);
        $this->makeUniqueParams($settings_fields);

        $output_array['data'] = $output_data;
        $output_array['settings']['fields'] = $settings_fields;
        $output_array['settings']['message'] = $this->getWSLanguageMessage($output_array['settings']["message"], $func_array['function']['name'], "Flow", $data_array);

        return $output_array;
    }

    protected function getFilteredArray($data_arr = array(), $output_fields = array(), $output_keys = array(), $inner_keys = array(), $custom_keys = array())
    {
        if (!is_array($output_keys)) {
            return $data_arr;
        }
        $output_array = array();
        for ($i = 0; $i < count($output_keys); $i++) {
            $assoc_key = $output_keys[$i];
            if (is_array($data_arr) && array_key_exists($assoc_key, $data_arr)) {
                $data = $data_arr[$assoc_key];
                if (is_array($custom_keys) && in_array((string) $assoc_key, $custom_keys)) {
                    $output_array[$assoc_key] = $data;
                } else {
                    if (is_array($data) && count($data) > 0) {
                        $filter_arr = $this->getSpecifiedFields($data, $output_fields, $inner_keys);
                        $output_array[$assoc_key] = (is_array($filter_arr) && count($filter_arr) > 0) ? $filter_arr : array();
                    } else {
                        $output_array[$assoc_key] = $data;
                    }
                }
            }
        }
        return $output_array;
    }

    protected function OldgetSpecifiedFields($data_arr = array(), $output_fields = array(), $inner_keys = array())
    {
        if (is_array($data_arr) && count($data_arr) > 0) {
            foreach ((array) $data_arr as $da_key => $da_val) {
                if (is_array($da_val)) {
                    if (is_array($inner_keys) && in_array((string) $da_key, $inner_keys)) {
                        $temp_arr = $this->getSpecifiedFields($da_val, $output_fields);
                        if (is_array($temp_arr)) {
                            $output_arr[$da_key] = $temp_arr;
                        }
                    } else {
                        if (is_array($output_fields) && in_array((string) $da_key, $output_fields)) {
                            $output_arr[$da_key] = $da_val;
                        } else {
                            $output_arr[$da_key] = $this->getSpecifiedFields($da_val, $output_fields);
                        }
                    }
                } else {
                    if (is_array($output_fields) && in_array((string) $da_key, $output_fields)) {
                        $output_arr[$da_key] = $da_val;
                    }
                }
            }
        }
        return $output_arr;
    }
    
    protected function getSpecifiedFields($data_arr = array(), $output_fields = array(), $inner_keys = array())
    {
        if (is_array($data_arr) && count($data_arr) > 0) {
            foreach ((array) $data_arr as $da_key => $da_val) {
                if (is_string($da_key) && !in_array($da_key, $output_fields)) {
                    continue;
                }
                if (is_array($da_val)) {
                    if (is_array($inner_keys) && in_array((string) $da_key, $inner_keys)) {
                        $temp_arr = $this->getSpecifiedFields($da_val, $output_fields);
                        if (is_array($temp_arr)) {
                            $output_arr[$da_key] = $temp_arr;
                        }
                    } else {
                        if (is_array($output_fields) && in_array((string) $da_key, $output_fields)) {
                            if (is_array($da_val) && count($da_val) > 0) {
                                if (array_keys($da_val) === range(0, count($da_val) - 1)) {
                                    $output_arr[$da_key] = $da_val;
                                } else {
                                    $output_arr[$da_key] = $this->getSpecifiedFields($da_val, $output_fields);
                                }
                            } else {
                                $output_arr[$da_key] = $da_val;
                            }
                        } else {
                            $output_arr[$da_key] = $this->getSpecifiedFields($da_val, $output_fields);
                        }
                    }
                } else {
                    if (is_array($output_fields) && in_array((string) $da_key, $output_fields)) {
                        $output_arr[$da_key] = $da_val;
                    }
                }
            }
        }
        return $output_arr;
    }

    protected function finalResponseArray($data_arr = array(), $output_objects = array(), $output_alias = array(), $single_keys = array(), $multiple_keys = array(), $custom_keys = array())
    {
        if (!is_array($data_arr) || count($data_arr) == 0) {
            return $data_arr;
        }
        //$data_arr = array_filter($data_arr);
        $flag = 1;
        $ret_arr = array();
        foreach ((array) $data_arr as $key => $val) {
            if (is_array($val) && count($val) > 1) {
                $val_arr = array_values($val);
                if (is_array($val_arr[1])) {
                    $flag = 0;
                    break;
                }
            }
            if (is_array($multiple_keys) && count($multiple_keys) > 1 &&
                (in_array((string) $key, $multiple_keys) || in_array((string) $key, $output_alias))) {
                $flag = 0;
                break;
            }
            if (is_array($multiple_keys) && count($multiple_keys) > 0 && is_array($single_keys) && count($single_keys) > 0 &&
                (in_array((string) $key, $multiple_keys) || in_array((string) $key, $output_alias))) {
                $flag = 0;
                break;
            }
            if (is_array($custom_keys) && in_array((string) $key, $custom_keys)) {
                $flag = 0;
                break;
            }
            $val_arr = is_array($val) ? array_values($val) : $val;
            if (is_array($val_arr[0])) {
                $ret_arr = array_merge($ret_arr, $val_arr[0]);
            } else {
                $ret_arr = is_array($val) ? array_merge($ret_arr, $val) : $ret_arr;
            }
            $objkey = $key;
            if (is_array($output_alias) && in_array((string) $key, $output_alias)) {
                $objkey = array_search((string) $key, $output_alias);
            }
            if (!is_array($output_objects) || !in_array($objkey, $output_objects)) {
                $flag = 2;
            }
        }
        if ($flag) {
            if ($flag == 1) {
                $send_arr = $ret_arr;
            } else {
                if (is_array($ret_arr) && count($ret_arr) > 0) {
                    $send_arr = array($ret_arr);
                } else {
                    $send_arr = $ret_arr;
                }
            }
        } else {
            if (count($data_arr) == 1) {
                $data_arr = array_values($data_arr);
                $send_arr = $data_arr[0];
            } else {
                $send_arr = $data_arr;
            }
        }
        return $send_arr;
    }

    protected function makeAliasArray($data = array(), $alias = array(), $keep_alias = array(), $objects = array())
    {
        if (!is_array($data) || count($data) == 0) {
            return $data;
        }
        if (is_array($objects) && count($objects) > 0) {
            $tmp = $data;
            foreach ($data as $key => $val) {
                if (in_array($key, $objects) && array_key_exists(0, $data[$key])) {
                    $tmp[$key] = $data[$key][0];
                }
            }
            $data = $tmp;
        }
        if (!is_array($alias) || count($alias) == 0) {
            return $data;
        }
        $send = $data;
        foreach ($data as $key => $val) {
            if (is_array($val) && count($val) > 0) {
                if (is_array($keep_alias) && in_array((string) $key, $keep_alias)) {
                    continue;
                }
                $temp_val = $this->makeAliasArray($val, $alias, $keep_alias, $objects);
            } else {
                $temp_val = $val;
            }
            if (is_array($alias) && $alias[$key]) {
                unset($send[$key]);
                $send[$alias[$key]] = $temp_val;
            } else {
                $send[$key] = $temp_val;
            }
        }
        return $send;
    }

    protected function makeFieldsArray($fields = array(), $alias = array())
    {
        if (!is_array($alias) || count($alias) == 0) {
            return $fields;
        }
        if (!is_array($fields) || count($fields) == 0) {
            return $fields;
        }
        foreach ($alias as $key => $val) {
            if (is_array($fields) && in_array((string) $key, $fields)) {
                $ind = array_search($key, $fields);
                $fields[$ind] = $val;
            }
        }
        return $fields;
    }

    public function assignAppendRecord($input_params = array(), $output_data = array(), $mapping_arr = array())
    {
        if (!is_array($output_data) || count($output_data) == 0) {
            return $input_params;
        }
        if (!is_array($input_params) || count($input_params) == 0) {
            return $output_data;
        }
        if (is_array($mapping_arr) && count($mapping_arr) > 0) {
            $output_data = $this->makeAliasArray($output_data, $mapping_arr);
        }
        $input_params = $output_data + $input_params;
        return $input_params;
    }

    public function assignFunctionResponse($output_data = array(), $mapping_arr = array())
    {
        $return_data = array();
        if (is_array($output_data['data']) && count($output_data['data']) > 0) {
            $return_data = $output_data['data'];
        }
        if (is_array($mapping_arr) && count($mapping_arr) > 0) {
            $return_data = $this->makeAliasArray($return_data, $mapping_arr);
        }
        return $return_data;
    }

    public function unsetAppendRecord($input_params = array(), $output_data = array(), $unset_keys = array())
    {
        if (!is_array($output_data) || count($output_data) == 0) {
            return $input_params;
        }
        if (!is_array($input_params) || count($input_params) == 0) {
            return $output_data;
        }
        if (!is_array($unset_keys) || count($unset_keys) == 0) {
            $input_params = $input_params + $output_data;
            return $input_params;
        }

        foreach ($unset_keys as $key => $val) {
            unset($input_params[$val]);
        }
        if (!is_array($input_params) || count($input_params) == 0) {
            return $output_data;
        }
        $input_params = $input_params + $output_data;
        return $input_params;
    }

    public function assignSingleRecord($input_params = array(), $output_data = array())
    {
        if (is_array($output_data)) {
            if (is_array($output_data) && array_key_exists(0, $output_data)) {
                $input_params = array_merge($input_params, $output_data[0]);
            } else {
                $input_params = array_merge($input_params, $output_data);
            }
        }
        return $input_params;
    }

    public function assignOtherWSRecord($input_params = array(), $output_data = array())
    {
        if (is_array($output_data)) {
            if (is_array($output_data) && array_key_exists(0, $output_data)) {
                $input_params = array_merge($input_params, $output_data[0]);
            } else {
                $input_params = array_merge($input_params, $output_data);
            }
        }
        return $input_params;
    }

    public function filterLoopParams($output_data = array(), $loop_arr = array(), $input_params = array())
    {
        $temp_arr = $output_data;
        $send_arr = $unset_arr = array();
        $extra_keys = array_diff_assoc($output_data, $input_params);

        foreach ($extra_keys as $key => $val) {
            $send_arr[$key] = $val;
            if (is_array($val) && array_key_exists(0, $val)) {
                $unset_arr = is_array($val[0]) ? array_keys($val[0]) : $unset_arr;
            }
        }

        foreach ($unset_arr as $key => $val) {
            if (is_array($send_arr) && array_key_exists($val, $send_arr)) {
                unset($send_arr[$val]);
            }
        }

        if (is_array($loop_arr) && count($loop_arr) > 0) {
            foreach ($loop_arr as $key => $val) {
                if (is_array($temp_arr) && array_key_exists($key, $temp_arr)) {
                    $send_arr[$key] = $temp_arr[$key];
                }
            }
        }
        return $send_arr;
    }

    public function grabLoopVariables($loop_vars = array(), $input_params = array())
    {
        if (!is_array($loop_vars) || count($loop_vars) == 0) {
            return $input_params;
        }
        foreach ($loop_vars as $key => $val) {
            if (!is_array($val) || count($val) == 0) {
                continue;
            }
            $input_params = array_merge($input_params, $val);
        }
        return $input_params;
    }

    public function makeUniqueParams(&$params = array())
    {
        $params = (is_array($params)) ? array_values(array_unique($params)) : array();
    }

    public function makeFilterParams(&$params = array())
    {
        $params = (is_array($params)) ? array_values(array_unique(array_filter($params))) : array();
    }

    public function validateInputParams($param_array = array(), $request_arr = array(), $ws_func = '', $message_arr = array())
    {
        if (is_array($request_arr) && array_key_exists("_", $request_arr)) {
            unset($request_arr['_']);
        }
        if (is_array($request_arr) && array_key_exists("ws_debug", $request_arr)) {
            unset($request_arr['ws_debug']);
        }
        if (is_array($request_arr) && array_key_exists("ws_ctrls", $request_arr)) {
            unset($request_arr['ws_ctrls']);
        }
        if (is_array($request_arr) && array_key_exists("ws_log", $request_arr)) {
            unset($request_arr['ws_log']);
        }
        if (is_array($request_arr) && array_key_exists("no_cache", $request_arr)) {
            unset($request_arr['no_cache']);
        }
        $this->CI->load->library('validator');
        try {
            $input_params = array();
            if (is_array($_FILES) && count($_FILES) > 0) {
                foreach ($_FILES as $fKey => $fVal) {
                    if (is_array($request_arr) && !array_key_exists($fKey, $request_arr)) {
                        $request_arr[$fKey] = $_FILES[$fKey]['name'];
                    }
                }
            }
            foreach ((array) $param_array as $prKey => $prVal) {
                if (is_array($prVal) && count($prVal) > 0) {
                    $validRuleArr = $validaData = $ruleArr = $msgArr = array();
                    $isRequired = false;
                    foreach ((array) $prVal as $ruKey => $ruVal) {
                        if ($ruVal['rule'] != "regex") {
                            $ruleArr[$ruVal['rule']] = $ruVal['value'];
                            $msgArr[$ruVal['rule']] = $ruVal['message'];
                            if (in_array($ruVal['rule'], array("minlength", "maxlength", "rangelength"))) {
                                $request_arr[$prKey] = (string) $request_arr[$prKey];
                            }
                        } else {
                            $ruleArr['regex'][] = $ruVal['value'];
                            $msgArr['regex'][] = $ruVal['message'];
                        }
                        if ($ruVal['rule'] == 'required') {
                            $isRequired = true;
                        }
                        $validRuleArr['rules'][$prKey] = $ruleArr;
                        $validRuleArr['messages'][$prKey] = $msgArr;
                    }
                    $validator_php = new validator($validRuleArr);
                    $validaData[$prKey] = $request_arr[$prKey];

                    $responseArr = $validator_php->validate($validaData);

                    if (is_array($responseArr) && count($responseArr) > 0) {
                        $msg_code = $responseArr[$prKey];
                        $send_msg = $this->getWSLanguageMessage($msg_code, $ws_func, "WebService", $request_arr);
                        $defaultMsg = ($send_msg != "") ? $send_msg : "Please enter valid data for " . $prKey . "";
                        if (strstr($defaultMsg, '#FIELD#') !== false) {
                            $defaultMsg = str_replace('#FIELD#', $prKey, $defaultMsg);
                        }
                        throw new Exception($defaultMsg);
                    } else {
                        if ($isRequired || isset($request_arr[$prKey])) {
                            $input_params[$prKey] = $request_arr[$prKey];
                        }
                    }
                } else {
                    if (isset($request_arr[$prKey])) {
                        $input_params[$prKey] = $request_arr[$prKey];
                    }
                }
            }
            //get the remaining get vars as well
            $rem_input = $request_arr;
            if (is_array($rem_input)) {
                $remaining_array = array_diff_assoc($rem_input, $input_params);
                $input_params = array_merge($input_params, $remaining_array);
            }
            $returnArr['success'] = "1";
            $returnArr['input_params'] = $input_params;
        } catch (Exception $e) {
            $returnArr['success'] = "-5";
            $returnArr['message'] = $e->getMessage();
        }
        return $returnArr;
    }

    public function makeValidationResponse($res_arr = array())
    {
        if ($res_arr['success'] == "-5") {
            $res_arr['success'] = "0";
        }
        $settings_arr['success'] = (string) $res_arr['success'];
        $settings_arr['message'] = $res_arr['message'];
        $settings_arr['fields'] = array();
        $final_arr['settings'] = $settings_arr;
        $final_arr['data'] = array();
        return $final_arr;
    }

    public function sendValidationResponse($res_arr = array())
    {
        if ($res_arr['success'] == "-5") {
            $res_arr['success'] = "0";
        }
        $settings_arr['success'] = (string) $res_arr['success'];
        $settings_arr['message'] = $res_arr['message'];
        $settings_arr['fields'] = array();
        $final_arr['settings'] = $settings_arr;
        $final_arr['data'] = array();
        $this->sendWSResponse($final_arr);
    }

    public function getWSLanguageMessage($msg_code = "", $ws_func = '', $type = '', $params = array())
    {
        $lang_code = $this->CI->general->getLangRequestValue();
        $lang_folder = strtolower($lang_code);
        if (is_file(APPPATH . "language" . DS . $lang_folder . DS . "webservice_lang.php")) {
            $this->CI->lang->load('webservice', $lang_folder);
        } else {
            $this->CI->lang->load('webservice', "en");
        }
        $ws_msg_arr = $this->CI->lang->line($ws_func);
        $send_msg = $ws_msg_arr[$msg_code];
        if ($send_msg == "") {
            $send_msg = $msg_code;
        }
        if (strstr($send_msg, "#")) {
            $send_msg = $this->CI->general->getReplacedInputParams($send_msg, $params);
        }
        if (strstr($send_msg, "{%REQUEST")) {
            $send_msg = $this->CI->general->processRequestPregMatch($send_msg, $params);
        }
        if (strstr($send_msg, "{%SERVER")) {
            $send_msg = $this->CI->general->processServerPregMatch($send_msg, $params);
        }
        if (strstr($send_msg, "{%SYSTEM")) {
            $send_msg = $this->CI->general->processSystemPregMatch($send_msg, $params);
        }
        $send_msg = stripslashes($send_msg);
        return $send_msg;
    }

    public function filterNullValues($tmp_arr)
    {
        if (is_array($tmp_arr)) {
            foreach ($tmp_arr as $key => $val) {
                $tmp_arr[$key] = $this->filterNullValues($val);
            }
            return $tmp_arr;
        } elseif (is_object($tmp_arr)) {
            return $tmp_arr;
        } else {
            if (is_null($tmp_arr)) {
                return '';
            } else {
                return (string) $tmp_arr;
            }
        }
    }

    public function setOptionsResponse()
    {
        $method = $this->CI->input->method(TRUE);
        if ($method == "OPTIONS") {
            $api_key_name = $this->CI->config->item('REST_KEY_NAME');
            $api_version_name = $this->CI->config->item('REST_VERSION_NAME');
            $api_auth_token_name = $this->CI->config->item('REST_AUTHTOKEN_NAME');

            $extra_auth_headers = ($api_key_name != '') ? ", " . $api_key_name : ", APIKEY";
            $extra_auth_headers .= ($api_version_name != '') ? ", " . $api_version_name : ", APIVERSION";
            $extra_auth_headers .= ($api_auth_token_name != '') ? ", " . $api_auth_token_name : ", AUTHTOKEN";

            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json");
            header("Access-Control-Allow-Headers: Origin, Accept, Pragma, Authorization, Content-Type, Cache-Control, X-Requested-With, Access-Control-Request-Method" . $extra_auth_headers);
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
            $this->setResponseStatus(204);
            exit;
        }
    }

    public function setResponseStatus($status_code = 200)
    {
        if (intval($status_code) > 0) {
            $this->CI->output->set_status_header($status_code);
        }
    }

    public function setWSContentType($res_type = '')
    {
        if (isset($this->_res_format[$res_type])) {
            $this->CI->output->set_content_type($this->_res_format[$res_type], strtolower($this->CI->config->item('charset')));
        } else {
            $this->CI->output->set_content_type("application/json", strtolower($this->CI->config->item('charset')));
        }
    }

    public function sendWSResponse($arr = array(), $debug = array(), $res_format = 'json', $token = "")
    {
       //1153
        $header_code = $arr['settings']['success'];
        $set_header_flag = false;
        if($header_code > 99){
            $set_header_flag = true;
        }
       //1153

        $ws_debug = $this->CI->input->get_post("ws_debug", true);
        $arr = $this->filterNullValues($arr);
        if ($ws_debug == 1 && $_ENV['debug_action']) {
            $arr['queries'] = $this->CI->general->getDBQueriesList();
            if (!is_null($this->CI->input->get_post("ws_ctrls"))) {
                $arr['debug'] = $debug;
            }
            $res_format = "json";
        }
        if ($token != "") {
            $arr['settings']['access_token'] = $token;
        }
        $exec_data = $this->CI->config->item("_WS_EXEC_DATA");
        if (is_array($exec_data) && count($exec_data) > 0) {
            $this->logExecutionTime($exec_data);
        }
        if ($this->CI->config->item('WS_RESPONSE_ENCRYPTION') == "Y") {
            $output = $this->json_safe_encode($arr);
            $this->CI->load->library('wschecker');
            $output = $this->CI->wschecker->encryptData($output);
            $this->setWSContentType("html");
        } else {
            if ($this->CI->config->item("MULTI_LINGUAL_PROJECT") == "Yes") {
                $content_type = "html";
            } else {
                $content_type = $res_format;
            }
            $this->setWSContentType($content_type);
            switch ($res_format) {
                case 'xml':
                    $this->CI->load->library('format');
                    $output = $this->CI->format->factory($arr)->to_xml();
                    break;
                case 'csv':
                    $this->CI->load->library('format');
                    header('Content-Disposition: attachment; filename=' . $this->CI->uri->segments[2]);
                    $output = $this->CI->format->factory($arr['data'])->to_csv();
                    break;
                case 'html':
                    $this->CI->load->library('format');
                    $output = $this->CI->format->factory($arr['data'])->to_html();
                    break;
                case 'serialized':
                    $this->CI->load->library('format');
                    header('Content-Disposition: attachment; filename=' . $this->CI->uri->segments[2]);
                    $output = $this->CI->format->factory($arr)->to_serialized();
                    break;
                case 'json':
                    $output = $this->json_safe_encode($arr);
                    break;
                default:
                    $output = $this->json_safe_encode($arr);
                    break;
            }
        }
        //1153


        // insert API accesslog response => Start
        if (is_array($exec_data) && count($exec_data) > 0 ) {

            $this->CI->db->select('vFileName');
            $this->CI->db->where('iAccessLogId', $exec_data['api_log_id']);
            $db_rec_obj = $this->CI->db->from('api_accesslogs')->get();
            $result = is_object($db_rec_obj) ? $db_rec_obj->row_array() : array();
            
            if(!empty($result)){
                $access_log_folder = $this->CI->config->item('admin_access_log_path');
                $log_folder_path = $access_log_folder . "api_logs" . DS ;
                $log_file_path = $log_folder_path . $result['vFileName'];

                $json_data = file_get_contents($log_file_path);
                $input_params = json_decode($json_data, true);
                
                $fileContents['input_params'] = $input_params['input_params'];
                if(empty($arr['queries']))
                {
                    $arr['queries'] = $this->CI->general->getDBQueriesList();    
                }
                $fileContents['output_response'] = $arr;

                $fp = fopen($log_file_path, 'w');
                fwrite($fp, json_encode($fileContents));
                fclose($fp);
            }
            if($exec_data['api_log_id'] > 0){
                $updateArr = array();
                if($this->CI->session->userdata('iUserId')){
                    $updateArr['iPerformedBy'] = $this->CI->session->userdata('iUserId');
                }else{
                    $updateArr['iPerformedBy'] = NULL;
                }
                $updateArr['dtExecutedDate'] = date('Y-m-d H:i:s');
                $this->CI->db->where('iAccessLogId', $exec_data['api_log_id']);
                $this->CI->db->update('api_accesslogs', $updateArr);
            }
        }
        // insert API accesslog response => End
        
        $this->CI->general->logDBQueriesList();
        $this->CI->output->set_header('Access-Control-Allow-Origin: *');
        $this->CI->output->set_output($output);
        $this->CI->output->_display();
        exit;



        
        $this->CI->general->logDBQueriesList();
        $this->CI->output->set_header('Access-Control-Allow-Origin: *');
        $this->CI->output->set_output($output);
        if($set_header_flag){
              $this->CI->output->set_status_header($header_code);
            }

        $this->CI->output->_display();
        exit;
        //1153
    }

    protected function json_safe_encode($var)
    {
        if ((version_compare(PHP_VERSION, '5.4.0') >= 0) && $this->CI->input->get_post("ws_debug", true) == '1') {
            $encode_str = json_encode($this->json_fix_cyr($var), JSON_PRETTY_PRINT);
        } else {
            $encode_str = json_encode($this->json_fix_cyr($var));
        }
        return $encode_str;
    }

    protected function json_fix_cyr($var)
    {
        if (is_array($var)) {
            $new = array();
            foreach ($var as $k => $v) {
                $new[$this->json_fix_cyr($k)] = $this->json_fix_cyr($v);
            }
            $var = $new;
        } elseif (is_object($var)) {
            $vars = get_object_vars($var);
            foreach ($vars as $m => $v) {
                $var->$m = $this->json_fix_cyr($v);
            }
        } elseif (is_string($var)) {
            if (!preg_match('!!u', $var)) {
                $var = utf8_encode($var);
            }
        }
        return $var;
    }

    protected function logExecutionTime($data = array())
    {
        $ip_addr = $data['ip_addr'];
        $method = $data['method'];
        $start = $data['start'];
        $path = $data['path'];
        $end = microtime();

        list($start_micro, $start_date) = explode(" ", $start);
        list($end_micro, $end_date) = explode(" ", $end);

        $start_time = date("Y-m-d H:i:s", $start_date) . "." . round($start_micro * 1000);
        $end_time = date("Y-m-d H:i:s", $end_date) . "." . round($end_micro * 1000);
        $diff_time = round((($end - $start) * 1000), 3);

        $exe_str = <<<EOD
{$ip_addr}~~{$method}~~{$start_time}~~{$end_time}~~{$diff_time}

EOD;

        $fp = fopen($path, 'a+');
        fwrite($fp, $exe_str);
        fclose($fp);
    }

    public function pushDebugParams($key = '', $arr = array(), $tot = array(), $next = '', $start_loop = '', $end_loop = '', $debug_loop = array())
    {
        if (is_null($this->CI->input->get_post("ws_debug")) || is_null($this->CI->input->get_post("ws_ctrls"))) {
            return;
        }
        $this->ws_debug_params['ws_ctrls'][] = $key;
        if (!array_key_exists($key, $this->ws_debug_params)) {
            $this->ws_debug_params[$key] = $arr;
        } elseif ($start_loop != '') {
            if (is_array($this->ws_debug_params[$key])) {
                $this->ws_debug_params[$key] = array_merge($this->ws_debug_params[$key], $arr);
            } else {
                $this->ws_debug_params[$key] = $arr;
            }
        }
        $ctrls = $this->CI->input->get_post("ws_ctrls");
        $ctrls = ($ctrls) ? explode(",", $ctrls) : $ctrls;
        if (is_array($ctrls) && count($ctrls) > 0) {
            $_log_arr['flow_name'] = $key;
            $_log_arr['next_flow'] = $next;
            $_log_arr['start_loop'] = $start_loop;
            $_log_arr['end_loop'] = $end_loop;
            $_log_arr['debug_loop'] = $debug_loop;
            $_log_arr['params'] = $tot;
            $_log_arr['debug'] = $this->ws_debug_params;
            $fp = fopen($this->CI->config->item('ws_debug_log_path') . $this->ws_log_file, 'w+');
            if ($fp) {
                fwrite($fp, serialize($_log_arr));
                fclose($fp);
            }
        }
        if (is_array($ctrls) && in_array($key, $ctrls)) {
            $output_debug_params = $this->ws_debug_params;
            unset($output_debug_params['ws_ctrls']);
            $this->ws_debug_params['ws_log'] = $this->ws_log_file;
            $this->sendWSResponse($output_debug_params, $this->ws_debug_params);
        }
    }
}

/* End of file Wsresponse.php */
/* Location: ./application/libraries/Wsresponse.php */