<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Notification Response Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module Notification
 * 
 * @class Notifyresponse.php
 * 
 * @path application\libraries\Notifyresponse.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Notifyresponse
{

    protected $CI;
    public $ns_debug_params;
    public $ns_log_file;

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
        $array_data = $this->makeAliasArray($array_data, $output_alias, $custom_keys);
        $output_data = $this->finalResponseArray($array_data, $single_keys, $multiple_keys, $custom_keys);

        $settings_fields = $this->makeFieldsArray($ouput_fields, $output_alias);
        $this->makeUniqueParams($settings_fields);

        $output_array['data'] = $output_data;
        $output_array['settings']['fields'] = $settings_fields;

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

    protected function getSpecifiedFields($data_arr = array(), $output_fields = array(), $inner_keys = array())
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

    protected function finalResponseArray($data_arr = array(), $single_keys = array(), $multiple_keys = array(), $custom_keys = array())
    {
        if (!is_array($data_arr) || count($data_arr) == 0) {
            return $data_arr;
        }
        //$data_arr = array_filter($data_arr);
        $flag = true;
        $ret_arr = array();
        foreach ((array) $data_arr as $key => $val) {
            if (is_array($val) && count($val) > 1) {
                $val_arr = array_values($val);
                if (is_array($val_arr[1])) {
                    $flag = false;
                    break;
                }
            }
            if (is_array($multiple_keys) && count($multiple_keys) > 1 && in_array((string) $key, $multiple_keys)) {
                $flag = false;
                break;
            }
            if (is_array($multiple_keys) && count($multiple_keys) > 0 && is_array($single_keys) && count($single_keys) > 0 && in_array((string) $key, $multiple_keys)) {
                $flag = false;
                break;
            }
            if (is_array($custom_keys) && in_array((string) $key, $custom_keys)) {
                $flag = false;
                break;
            }
            $val_arr = is_array($val) ? array_values($val) : $val;
            if (is_array($val_arr[0])) {
                $ret_arr = array_merge($ret_arr, $val_arr[0]);
            } else {
                $ret_arr = is_array($val) ? array_merge($ret_arr, $val) : $ret_arr;
            }
        }
        if ($flag) {
            if (is_array($ret_arr) && count($ret_arr) > 0) {
                $send_arr = array($ret_arr);
            } else {
                $send_arr = $ret_arr;
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

    protected function makeAliasArray($data = array(), $alias = array(), $keep_alias = array())
    {
        if (!is_array($data) || count($data) == 0) {
            return $data;
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
                $temp_val = $this->makeAliasArray($val, $alias);
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

    public function assignAppendRecord($input_params = array(), $output_data = array())
    {
        if (!is_array($output_data) || count($output_data) == 0) {
            return $input_params;
        }
        if (!is_array($input_params) || count($input_params) == 0) {
            return $output_data;
        }
        $input_params = $output_data + $input_params;
        return $input_params;
    }

    public function assignFunctionResponse($output_data = array())
    {
        $return_data = array();
        if ((is_array($output_data['data']) && count($output_data['data']) > 0) || (is_array($output_data['settings']['fields']) && count($output_data['settings']['fields']) > 0)) {
            $return_data = $output_data['data'];
        } elseif (is_array($output_data['settings'])) {
            $return_data = $output_data['settings']['success'];
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

    public function assignOtherNSRecord($input_params = array(), $output_data = array())
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

    public function filterNullValues($tmp_arr)
    {
        if (is_array($tmp_arr)) {
            foreach ($tmp_arr as $key => $val) {
                $tmp_arr[$key] = $this->filterNullValues($val);
            }
            return $tmp_arr;
        } else {
            if (is_null($tmp_arr)) {
                return '';
            } else {
                return (string) $tmp_arr;
            }
        }
    }

    public function sendNSResponse($arr = array(), $debug = array())
    {
        $ns_debug = $this->CI->input->get_post("ns_debug", true);
        $arr = $this->filterNullValues($arr);
        if ($ns_debug == 1 && $_ENV['debug_action']) {
            $arr['queries'] = $this->CI->general->getDBQueriesList();
            if (!is_null($this->CI->input->get_post("ns_ctrls"))) {
                $arr['debug'] = $debug;
            }
        }
        $response_type = "json";
        switch ($response_type) {
            case 'xml':
                $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><response></response>");
                $this->array_to_xml($arr, $xml);
                $ret = $xml->asXML();
                break;
            case 'json':
            default:
                $ret = $this->json_safe_encode($arr);
                break;
        }
        header('Access-Control-Allow-Origin: *');
        if ($this->CI->config->item("MULTI_LINGUAL_PROJECT") == "Yes") {
            header('Content-Type: text/html; charset=utf-8');
        } else {
            header('Content-Type: application/json; charset=utf-8');
        }
        echo $ret;
        exit;
    }

    protected function json_safe_encode($var)
    {
        if ((version_compare(PHP_VERSION, '5.4.0') >= 0) && $this->CI->input->get_post("ns_debug", true) == '1') {
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

    protected function array_to_xml($student_info = array(), &$xml_student_info)
    {
        foreach ($student_info as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subnode = $xml_student_info->addChild("row");
                    $this->array_to_xml($value, $subnode);
                } else {
                    $this->array_to_xml($value, $xml_student_info);
                }
            } else {
                $xml_student_info->addChild("$key", "$value");
            }
        }
    }

    public function pushDebugParams($key = '', $arr = array(), $tot = array(), $next = '', $start_loop = '', $end_loop = '', $debug_loop = array())
    {
        if (is_null($this->CI->input->get_post("ns_debug")) || is_null($this->CI->input->get_post("ns_ctrls"))) {
            return;
        }
        $this->ns_debug_params['ns_ctrls'][] = $key;
        if (!array_key_exists($key, $this->ns_debug_params)) {
            $this->ns_debug_params[$key] = $arr;
        } elseif ($start_loop != '') {
            if (is_array($this->ns_debug_params[$key])) {
                $this->ns_debug_params[$key] = array_merge($this->ns_debug_params[$key], $arr);
            } else {
                $this->ns_debug_params[$key] = $arr;
            }
        }
        $ctrls = $this->CI->input->get_post("ns_ctrls");
        $ctrls = ($ctrls) ? explode(",", $ctrls) : $ctrls;
        if (is_array($ctrls) && count($ctrls) > 0) {
            $_log_arr['flow_name'] = $key;
            $_log_arr['next_flow'] = $next;
            $_log_arr['start_loop'] = $start_loop;
            $_log_arr['end_loop'] = $end_loop;
            $_log_arr['debug_loop'] = $debug_loop;
            $_log_arr['params'] = $tot;
            $_log_arr['debug'] = $this->ns_debug_params;
            $fp = fopen($this->CI->config->item('ns_debug_log_path') . $this->ns_log_file, 'w+');
            if ($fp) {
                fwrite($fp, serialize($_log_arr));
                fclose($fp);
            }
        }
        if (is_array($ctrls) && in_array($key, $ctrls)) {
            $output_debug_params = $this->ns_debug_params;
            unset($output_debug_params['ns_ctrls']);
            $this->ns_debug_params['ns_log'] = $this->ns_log_file;
            $this->sendNSResponse($output_debug_params, $this->ns_debug_params);
        }
    }
}

/* End of file Notifyresponse.php */
/* Location: ./application/libraries/Notifyresponse.php */