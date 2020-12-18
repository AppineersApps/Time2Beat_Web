<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of General Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module General
 * 
 * @class General.php
 * 
 * @path application\libraries\General.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
Class Front
{

    protected $CI;

    public function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->config->load('cit_frontauth', TRUE);
    }

    public function checkModuleAuthentication($input_params = array())
    {
        $current_controller = $input_params['controller'];
        $current_folder = $input_params['folder'];
        $allow_modules = $input_params['allow'];
        $all_methods = $this->CI->config->item('cit_frontauth');
        $final_result = 1;
        $redirection_url = '';
        if ((is_array($allow_modules) && in_array($current_folder, $allow_modules)) || !$this->CI->config->item('enable_frontauth')) {
            $final_result = -1;
        } else if (is_array($all_methods) && count($all_methods) > 0) {
            foreach ($all_methods as $key => $val) {
                $redirection_url = $val['redirection_url'];
                $redirection_message = $val['redirection_message'];
                $pages_list = $val['pages'];
                $conditions = $val['conditions'];
                $satisfy = $val['satisfy'];
                if (!is_array($pages_list) || count($pages_list) == 0) {
                    continue;
                }
                foreach ($pages_list as $page_key => $page_val) {
                    $page_arr = explode("@@", $page_val);
                    $folder_name = $page_arr[0];
                    $controller_name = $page_arr[1];
                    if ($controller_name == $current_controller && $folder_name == $current_folder) {
                        if (is_array($conditions) && count($conditions) > 0) {
                            foreach ($conditions as $inkey => $inval) {
                                $result = $this->getConditionResult($inval);
                                if ($satisfy == 'all') {
                                    if (!$result) {
                                        $final_result = 0;
                                        break;
                                    } else {
                                        $final_result = 1;
                                    }
                                } else {
                                    if ($result) {
                                        $final_result = 1;
                                        break;
                                    }
                                }
                            }
                        }
                        break;
                    }
                }
            }
        }
        return array($final_result, $redirection_url, $redirection_message);
    }

    public function getConditionResult($inval = array())
    {
        $operand_1 = $inval['operand_1'];
        $value_1 = $inval['value_1'];
        switch ($operand_1) {
            case 'request':
                $value_1 = $this->getRequestValue($value_1);
                break;
            case 'session':
                $value_1 = $this->getSessionValue($value_1);
                break;
            case 'server':
                $value_1 = $this->getServerValue($value_1);
                break;
        }
        $operand_2 = $inval['operand_2'];
        $value_2 = $inval['value_2'];
        switch ($operand_2) {
            case 'request':
                $value_2 = $this->getRequestValue($value_2);
                break;
            case 'session':
                $value_2 = $this->getSessionValue($value_2);
                break;
            case 'server':
                $value_2 = $this->getServerValue($value_2);
                break;
        }

        $operator = $inval['operator'];
        $condition_result = $this->CI->general->compareDataValues($operator, $value_1, $value_2);
        return $condition_result;
    }

    public function getRequestValue($value = "")
    {
        $ret_value = "";

        if ($value != "") {
            $ret_value = $this->CI->input->get_post($value, true);
        }

        return $ret_value;
    }

    public function getServerValue($value = "")
    {
        $ret_value = "";

        if ($value != "") {
            $ret_value = $_SERVER[$value];
        }

        return $ret_value;
    }

    public function getSessionValue($value = "")
    {
        $ret_value = "";

        if ($value != "") {
            $ret_value = $this->CI->session->userdata($value);
        }

        return $ret_value;
    }
}
