<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Listing Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module Listing
 * 
 * @class Listing.php
 * 
 * @path application\libraries\Listing.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Listing
{

    protected $CI;

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function getStartIndex($total, $page = 1, $recrod_limit = 20)
    {

        $start_index = ($page - 1) * $recrod_limit;
        return intval($start_index);
    }

    public function getTotalPages($total_records, $records_per_page)
    {
        if ($records_per_page == 0) {
            return 1;
        }
        $total_pages = ceil($total_records / $records_per_page);
        return $total_pages;
    }

    public function getDataForJqGrid(&$list_data = array(), $config_arr = array(), $page = '', $total_pages = '', $total_records = '')
    {
        $return_array = array();
        $module_config = $config_arr['module_config'];
        $list_config = $config_arr['list_config'];
        $form_config = $config_arr['form_config'];
        $dropdown_arr = $config_arr['dropdown_arr'];
        $table_name = $config_arr['table_name'];
        $table_alias = $config_arr['table_alias'];
        $primary_key = $config_arr['primary_key'];
        $grid_fields = $config_arr['grid_fields'];
        $print_rec = $config_arr['print_rec'];

        if ($print_rec == "Yes") {
            $this->CI->load->library('filter');
            if ($this->CI->config->item("ENABLE_ROLES_CAPABILITIES")) {
                $print_access = $this->CI->filter->checkAccessCapability($module_config['module_name'] . "_print", TRUE);
            } else {
                $print_access = $this->CI->filter->getModuleWiseAccess($module_config['module_name'], "Print", TRUE, TRUE);
            }
        }

        $return_array['data'] = $list_data;
        $return_array['page'] = $page;
        $return_array['total'] = $total_pages;
        $return_array['records'] = $total_records;
        for ($i = 0; $i < count($list_data); $i++) {
            $tmp_arr = $cell_arr = $func_arr = array();
            $id = $list_data[$i][$primary_key];
            $data_arr = $list_data[$i];
            if ($print_rec == "Yes") {
                $print_str = $this->getPrintRecordURL($module_config, $id, $print_access);
                $cell_arr[] = $print_str;
                $func_arr[] = $print_str;
            }

            for ($j = 0; $j < count($grid_fields); $j++) {
                $name = $grid_fields[$j];
                $field_config = $list_config[$name];
                $source_field = $field_config['source_field'];
                $source_config = $form_config[$source_field];
                $combo_config = $dropdown_arr[$source_field];

                $temp_val = $list_data[$i][$name];
                if ($field_config['file_upload'] == "Yes") {
                    $temp_val = $this->parseListingFile($temp_val, $id, $data_arr, $field_config, $module_config, "MGrid");
                }
                if ($field_config['encrypt'] == 'Yes') {
                    $temp_val = $this->CI->general->decryptDataMethod($temp_val, $field_config['enctype']);
                }
                $temp_val = $this->formatListingData($temp_val, $id, $data_arr, $field_config, $source_config, $combo_config, "MGrid", $i, $return_array, $module_config);
                $cell_arr[] = $temp_val;
                $list_data[$i][$name] = $temp_val;
            }

            if ($module_config['list_record_callback'] != "") {
                $php_func = $module_config['list_record_callback'];
                $ret_data = array();
                if (method_exists($this->CI->general, $php_func)) {
                    $ret_data = $this->CI->general->$php_func($id, $list_data[$i], $data_arr, $return_array);
                } elseif (substr($php_func, 0, 12) == 'controller::' && substr($php_func, 12) !== FALSE) {
                    $php_func = substr($php_func, 12);
                    $ctrl_obj = $this->CI->general->getControllerObject();
                    if (method_exists($ctrl_obj, $php_func)) {
                        $ret_data = $ctrl_obj->$php_func($id, $list_data[$i], $data_arr, $return_array);
                    }
                } elseif (substr($php_func, 0, 7) == 'model::' && substr($php_func, 7) !== FALSE) {
                    $php_func = substr($php_func, 7);
                    $model_obj = $this->CI->general->getModelObject();
                    if (method_exists($model_obj, $php_func)) {
                        $ret_data = $model_obj->$php_func($id, $list_data[$i], $data_arr, $return_array);
                    }
                }
                if (is_array($ret_data) && count($ret_data) > 0) {
                    $k = ($print_rec == "Yes") ? 1 : 0;
                    for ($j = 0; $j < count($grid_fields); $j++, $k++) {
                        $name = $grid_fields[$j];
                        if (isset($ret_data[$name])) {
                            $func_arr[] = $ret_data[$name];
                        } else {
                            $func_arr[] = $cell_arr[$k];
                        }
                    }
                    $cell_arr = $func_arr;
                }
            }
            $tmp_arr['id'] = $this->CI->general->getAdminEncodeURL($id);
            $tmp_arr['cell'] = $cell_arr;
            $return_array['rows'][] = $tmp_arr;
        }
        return $return_array;
    }

    public function getPrintRecordURL($module_config = array(), $id = '', $print_access = TRUE)
    {
        if (!$print_access) {
            $ret_str = '<a href="javascript://" class="print-rec-restrict">
                        <span class="icon16 icomoon-icon-printer"></span>
                    </a>';
        } else {
            $ret_str = '<a href="' . $this->CI->config->item('admin_url') . '#' . $this->CI->general->getAdminEncodeURL($module_config['folder_name'] . '/' . $module_config['module_name'] . '/printRecord') . '|id|' . $this->CI->general->getAdminEncodeURL($id) . '" class="fancybox-popup print-rec-row">
                        <span class="icon16 icomoon-icon-printer"></span>
                    </a>';
        }
        return $ret_str;
    }

    public function formatListingData($value = '', $id = '', $data_arr = array(), $field_config = array(), $source_config = array(), $combo_config = array(), $mode = "MGrid", $index = 0, $info = array(), $module_config = array())
    {
        $type = $field_config['type'];
        $php_func = $field_config['php_func'];
        $date_format = trim($field_config['php_date']);
        $format_arr = array("date", "date_and_time", "phone_number", "time");
        $ret_data = $value;
        if ($combo_config['type'] == "enum") {
            $ret_data = $this->getEnumDisplayVal($combo_config['values'], $ret_data);
        } elseif ($combo_config['type'] == 'phpfn') {
            $ret_data = $this->getFunctionDisplayVal($ret_data, $id, $data_arr, $combo_config, $field_config);
        }
        if ($php_func != "") {
            if (function_exists($php_func)) {
                $ret_data = call_user_func($php_func, $ret_data);
            } elseif (method_exists($this->CI->general, $php_func)) {
                $ret_data = $this->CI->general->$php_func($ret_data, $id, $data_arr, $index, $field_config['name'], $info, $module_config);
            } elseif (substr($php_func, 0, 12) == 'controller::' && substr($php_func, 12) !== FALSE) {
                $php_func = substr($php_func, 12);
                $ctrl_obj = $this->CI->general->getControllerObject();
                if (method_exists($ctrl_obj, $php_func)) {
                    $ret_data = $ctrl_obj->$php_func($ret_data, $id, $data_arr, $index, $field_config['name'], $info, $module_config);
                }
            } elseif (substr($php_func, 0, 7) == 'model::' && substr($php_func, 7) !== FALSE) {
                $php_func = substr($php_func, 7);
                $model_obj = $this->CI->general->getModelObject();
                if (method_exists($model_obj, $php_func)) {
                    $ret_data = $model_obj->$php_func($ret_data, $id, $data_arr, $index, $field_config['name'], $info, $module_config);
                }
            }
        } elseif ($date_format != "") {
            if ($ret_data != "" && $ret_data != "0000-00-00 00:00:00" && $ret_data != "00:00:00" && $ret_data != "0000-00-00") {
                $ret_data = date($date_format, strtotime($ret_data));
            }
        } elseif (in_array($type, $format_arr)) {
            $format = $field_config['format'];
            switch ($type) {
                case 'date' :
                    if ($format == "d/m/Y") {
                        $ret_data = $this->CI->general->dateCustomFormat($format, $ret_data);
                    } else {
                        $ret_data = $this->CI->general->dateDefinedFormat($format, $ret_data);
                    }
                    break;
                case 'date_and_time' :
                    if ($format == "d/m/Y h:i A") {
                        $ret_data = $this->CI->general->dateTimeCustomFormat($format, $ret_data);
                    } else {
                        $ret_data = $this->CI->general->dateTimeDefinedFormat($format, $ret_data);
                    }
                    break;
                case 'time' :
                    $ret_data = $this->CI->general->timeDefinedFormat($format, $ret_data);
                    break;
                case 'phone_number' :
                    $ret_data = $this->CI->general->getPhoneMaskedView($format, $ret_data);
                    break;
            }
        }
        if ($type == 'textarea' && $mode != "GExport") {
            $ret_data = nl2br($ret_data);
        }

        return $ret_data;
    }

    public function getEnumDisplayVal($opt_arr = array(), $value = '')
    {
        if (!is_array($opt_arr) || count($opt_arr) == 0) {
            return $value;
        }
        $ret_arr = explode(",", $value);
        if (is_array($ret_arr) && count($ret_arr) > 0) {
            foreach ($ret_arr as $key => $val) {
                $ret_arr[$key] = trim($val);
            }
        }
        $ret_arr = array_filter($ret_arr, function($val) {
            return ($val !== NULL && $val !== FALSE && $val !== '');
        });
        $find_arr = array();
        foreach ($opt_arr as $key => $val) {
            if (in_array(trim($val['id']), $ret_arr)) {
                $find_arr[] = $val['val'];
                if (count($ret_arr) == count($find_arr)) {
                    break;
                }
            }
        }
        $ret_data = (is_array($find_arr) && count($find_arr) > 0) ? implode(", ", $find_arr) : $value;
        return $ret_data;
    }

    public function getFunctionDisplayVal($value = '', $id = '', $data_arr = array(), $combo_config = array(), $field_config = array())
    {
        $php_func = $combo_config['function'];
        $opt_arr = $find_arr = array();
        $ret_arr = explode(",", $value);
        if (is_array($ret_arr) && count($ret_arr) > 0) {
            foreach ($ret_arr as $key => $val) {
                $ret_arr[$key] = trim($val);
            }
        }
        $ret_arr = array_filter($ret_arr, function($val) {
            return ($val !== NULL && $val !== FALSE && $val !== '');
        });
        $parent_src = $this->CI->input->get_post("parID");
        if (method_exists($this->CI->general, $php_func)) {
            $opt_arr = $this->CI->general->$php_func($value, "View", $id, $data_arr, $parent_src, '');
        } elseif (substr($php_func, 0, 12) == 'controller::' && substr($php_func, 12) !== FALSE) {
            $php_func = substr($php_func, 12);
            $ctrl_obj = $this->CI->general->getControllerObject();
            if (method_exists($ctrl_obj, $php_func)) {
                $opt_arr = $ctrl_obj->$php_func($value, "View", $id, $data_arr, $parent_src, '');
            }
        } elseif (substr($php_func, 0, 7) == 'model::' && substr($php_func, 7) !== FALSE) {
            $php_func = substr($php_func, 7);
            $model_obj = $this->CI->general->getModelObject();
            if (method_exists($model_obj, $php_func)) {
                $opt_arr = $model_obj->$php_func($value, "View", $id, $data_arr, $parent_src, '');
            }
        }
        foreach ($opt_arr as $key => $val) {
            if (in_array(trim($val['id']), $ret_arr)) {
                $find_arr[] = $val['val'];
                if (count($ret_arr) == count($find_arr)) {
                    break;
                }
            }
        }
        $ret_data = (is_array($find_arr) && count($find_arr) > 0) ? implode(", ", $find_arr) : $value;
        return $ret_data;
    }

    public function getGroupGrandTotal($value = '', $config_arr = array())
    {
        $group_oper = strtolower($config_arr['group_attr']['oper']);
        $ret_value = $value;
        if (in_array($group_oper, array("sum", "avg", "min", "max"))) {
            if ($value == "") {
                $ret_value = 0;
            } elseif (is_int($value)) {
                $ret_value = intval($value);
            } elseif (is_float($value)) {
                $ret_value = round(floatval($value), 2);
            }
        }
        return $ret_value;
    }

    public function getGrandCalcBlock($list_arr = array(), $group_arr = array(), $assoc_setting = array(), $config_arr = array())
    {
        $oper = strtolower($group_arr['oper']);
        switch ($oper) {
            case 'count':
                $user_val = intval(count($list_arr));
                break;
            case 'sum':
                $user_val = round(array_sum($list_arr), 2);
                break;
            case 'avg':
                $userSum = array_sum($list_arr);
                $userCnt = intval(count($list_arr));
                if ($userCnt > 0) {
                    $user_val = round(($userSum / $userCnt), 2);
                } else {
                    $user_val = 0;
                }
                break;
            case 'max':
                $user_val = round(max($list_arr), 2);
                break;
            case 'min':
                $user_val = round(min($list_arr), 2);
                break;
        }
        $price_arr = (is_array($group_arr['prices'])) ? array_filter($group_arr['prices']) : array();

        $retArr[0]['type'] = "sub";
        $retArr[0]['value'] = $user_val;
        $retArr[0]['text'] = number_format($user_val, 2);
        $final_val = $user_val;
        if ($group_arr['calc'] == "Yes" && count($price_arr) > 0) {
            $remAddVal = 0;
            $j = 1;
            $flag_calc = FALSE;
            foreach ((array) $price_arr as $prKey => $prVal) {
                $glb_price_arr = $assoc_setting[$prVal][0];
                if (!is_array($glb_price_arr) || count($glb_price_arr) == 0) {
                    continue;
                }

                $pr_desc = trim($glb_price_arr['vDesc']);
                $pr_value = trim($glb_price_arr['vValue']);
                $pr_type = strtolower($glb_price_arr['eSource']);
                $pr_inc_dec = strtolower($glb_price_arr['eSelectType']);
                if (!is_numeric($pr_value)) {
                    continue;
                }
                $price_lab = 0;
                if (in_array($pr_type, array("percent", "value"))) {
                    $actVal = ($pr_type == "percent") ? $final_val * ($pr_value / 100) : $pr_value;
                    $final_val = ($pr_inc_dec == "minus") ? ($final_val - $actVal) : ($final_val + $actVal);
                    if ($pr_type == "percent") {
                        $price_lab = ($pr_inc_dec == "minus") ? "-" . $pr_value . "%" : "+" . $pr_value . "%";
                    } else {
                        $price_lab = ($pr_inc_dec == "minus") ? "-" . $actVal : "+" . $actVal;
                    }
                } elseif ($pr_type == "function") {
                    if (method_exists($this->CI->general, $pr_value)) {
                        list($price_lab, $final_val) = $this->CI->general->$pr_value($final_val, $user_val);
                    }
                }
                $retArr[$j]['type'] = "calc";
                $retArr[$j]['title'] = $pr_desc;
                $retArr[$j]['value'] = $price_lab;
                $retArr[$j]['text'] = number_format($price_lab, 2);
                $flag_calc = TRUE;
                $j++;
            }
            if ($flag_calc) {
                $final_val = round($final_val, 2);
                $retArr[$j]['type'] = "final";
                $retArr[$j]['value'] = $final_val;
                $retArr[$j]['text'] = number_format($final_val, 2);
            }
        }
        $ret_data = $retArr;
        if ($group_arr['php_func'] != "") {
            $php_func = $group_arr['php_func'];
            if (method_exists($this->CI->general, $php_func)) {
                $ret_data = $this->CI->general->$php_func($retArr, $config_arr, $assoc_setting);
            } elseif (substr($php_func, 0, 12) == 'controller::' && substr($php_func, 12) !== FALSE) {
                $php_func = substr($php_func, 12);
                $ctrl_obj = $this->CI->general->getControllerObject();
                if (method_exists($ctrl_obj, $php_func)) {
                    $ret_data = $ctrl_obj->$php_func($retArr, $config_arr, $assoc_setting);
                }
            } elseif (substr($php_func, 0, 7) == 'model::' && substr($php_func, 7) !== FALSE) {
                $php_func = substr($php_func, 7);
                $model_obj = $this->CI->general->getModelObject();
                if (method_exists($model_obj, $php_func)) {
                    $ret_data = $model_obj->$php_func($retArr, $config_arr, $assoc_setting);
                }
            }
        }
        return $ret_data;
    }

    public function adminLocalEncrypt($data = '')
    {
        $this->CI->general->loadEncryptLibrary();
        $enc_data = '';
        if (trim($data) != "") {
            $enc_data = $this->CI->ci_encrypt->dataEncrypt($data);
        }
        return $enc_data;
    }

    public function adminLocalDecrypt($data = '')
    {
        $this->CI->general->loadEncryptLibrary();
        $dec_data = '';
        if (trim($data) != "") {
            $dec_data = $this->CI->ci_encrypt->dataDecrypt($data);
        }
        return $dec_data;
    }

    public function getResizedImage($url = '', $file = '', $width = '', $height = '', $color = '', $type = '')
    {
        $site_url = $this->CI->config->item('site_url');
        $dec_image_url = $url . $file;
        $width = intval($width);
        $height = intval($height);
        if ($width > 0 || $height > 0) {
            $enc_image_url = base64_encode($dec_image_url);
            $image_url = $site_url . 'WS/image_resize/?pic=' . $enc_image_url . '&height=' . $height . '&width=' . $width;
            if ($color != '') {
                $image_url .= "&color=" . $color;
            }
            if ($type != '') {
                $image_url .= "&type=" . $type;
            }
        } else {
            $image_url = $dec_image_url;
        }
        return $image_url;
    }

    public function downloadFiles($view_type = '', $config_arr = array(), $file_name = '', $folder = '')
    {
        $file_server = $config_arr['file_server'];
        $file_found = FALSE;
        switch ($file_server) {
            case 'custom':
                $folder_arr = $this->CI->general->getServerUploadPathURL($config_arr['file_folder']);
                if ($folder_arr['status']) {
                    $original_path = $folder_arr['folder_path'];
                    $original_url = $folder_arr['folder_url'];
                    if ($config_arr['file_keep']) {
                        $original_path = $original_path . $folder;
                    }
                    $file_url = $original_url . md5($original_path) . "/" . $file_name; //url will returned as path
                    $file_found = $this->CI->general->checkFileExistsOnServer($file_url);
                    if ($file_found) {
                        $original_file_data = file_get_contents($file_url);
                        $temp_file_name = $this->CI->config->item('admin_upload_temp_path') . $file_name;
                        $fp = fopen($temp_file_name, 'w+');
                        fwrite($fp, $original_file_data);
                        fclose($fp);
                        $file_path = $temp_file_name;
                    }
                    $modified_filename = end(explode("/", $file_name));
                }
                break;
            case 'amazon':
                $folder_arr = $this->CI->general->getAWSServerUploadPathURL($config_arr['file_folder']);
                if ($folder_arr['status']) {
                    $original_path = $folder_arr['folder_path'];
                    $original_url = $folder_arr['folder_url'];
                    $file_folder = $config_arr['file_folder'];
                    if ($config_arr['file_keep']) {
                        $original_url = $original_url . $folder . '/';
                        $bucket_folder = $file_folder . "/" . $folder;
                    } else {
                        $bucket_folder = $file_folder;
                    }
                    $file_found = $this->CI->general->checkFileExistsOnAWSObject($folder_arr['bucket_files'], $file_name, $folder_arr['bucket_name'], $bucket_folder);
                    if ($file_found) {
                        $original_file_path = $original_url . $file_name;
                        $original_file_data = file_get_contents($original_file_path);
                        $temp_file_name = $this->CI->config->item('admin_upload_temp_path') . $file_name;
                        $fp = fopen($temp_file_name, 'w+');
                        fwrite($fp, $original_file_data);
                        fclose($fp);
                        $file_path = $temp_file_name;
                    }
                    $modified_filename = $file_name;
                }
                break;
            default :
                $folder_arr = $this->CI->general->getAdminUploadPathURL($config_arr['file_folder']);
                if ($folder_arr['status']) {
                    if ($config_arr['file_keep']) {
                        $original_path = $folder_arr['folder_path'] . $folder . DS;
                        $original_url = $folder_arr['folder_url'] . $folder . '/';
                    } else {
                        $original_path = $folder_arr['folder_path'];
                        $original_url = $folder_arr['folder_url'];
                    }
                    $file_path = $original_path . $file_name; //path will returned
                    $file_found = is_file($file_path);
                    $modified_filename = $file_name;
                }
                break;
        }
        $filename = $modified_filename;
        if ($file_found) {
            //$this->CI->load->helper('download');
            //force_download($file_path, '', TRUE);
            $mimetype = get_mime_by_extension($file_path);
            if (ob_get_length() > 0) {
                ob_end_clean();
            }
            ob_start();
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Cache-Control: private", FALSE);
            header('Content-Disposition: attachment; filename=' . $filename);
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . filesize($file_path));
            if ($mimetype) {
                header("Content-Type: " . $mimetype);
            }
            flush();
            readfile($file_path);
        }
        exit;
    }

    public function deleteFiles($config_arr = array(), $file_name = '', $inner_folder = '', $view_type = '')
    {
        if ($config_arr['file_server'] == 'custom') {
            return TRUE;
        } elseif ($config_arr['file_server'] == 'amazon') {
            $file_folder = $config_arr['file_folder'];
            if ($file_folder != "" && $file_name != "") {
                $dest_file_name = $file_name;
                if ($config_arr['file_keep']) {
                    $dest_file_name = $inner_folder . "/" . $file_name;
                }
                $this->CI->general->deleteAWSFileData($file_folder, $dest_file_name);
            }
        } elseif (trim($file_name) != '') {
            $folder_arr = $this->CI->general->getAdminUploadPathURL($config_arr['file_folder']);
            if ($folder_arr['status']) {
                if ($config_arr['file_keep']) {
                    $original_path = $folder_arr['folder_path'] . $inner_folder . DS;
                    $original_url = $folder_arr['folder_url'] . $inner_folder . '/';
                } else {
                    $original_path = $folder_arr['folder_path'];
                    $original_url = $folder_arr['folder_url'];
                }
            }
            if (is_dir($original_path) && is_file($original_path . $file_name)) {
                unlink($original_path . $file_name);
            }
        }
        return TRUE;
    }

    public function uploadFilesOnSaveForm($file_arr = array(), $config_arr = array(), $data_arr = array())
    {
        if (!is_array($file_arr) || count($file_arr) == 0) {
            return;
        }
        foreach ((array) $file_arr as $key => $val) {
            $file_data = $val;
            if ($val['unique_name'] != "") {
                $file_config = $config_arr[$val['unique_name']];
            } else {
                $file_config = $config_arr[$key];
            }
            $file_server = $file_config['file_server'];
            switch ($file_server) {
                case 'custom':
                    $dest_url = $this->saveFileUploadServerData($file_data, $data_arr, $file_config);
                    break;
                case 'amazon':
                    $dest_url = $this->saveFileUploadAWSData($file_data, $data_arr, $file_config);
                    break;
                case 'local':
                default :
                    $dest_url = $this->saveFileUploadAdminData($file_data, $data_arr, $file_config);
                    break;
            }
        }
        return $dest_url;
    }

    public function saveFileUploadAWSData($file_data = array(), $data_arr = array(), $file_config = array())
    {
        $dest_url = '';
        $file_name = $file_data['file_name'];
        $old_file_name = $file_data['old_file_name'];
        $folder_arr = $this->CI->general->getAWSServerUploadPathURL($file_config['file_folder']);
        if ($folder_arr['status']) {
            $folder_path = $folder_arr['folder_path'];
            $folder_url = $folder_arr['folder_url'];
        }
        if ($file_name != "") {
            $temp_file = $this->CI->config->item('admin_upload_temp_path') . $file_name;
            if (is_file($temp_file)) {
                $dest_file_name = str_replace(" ", "_", $file_name);
                $folder_id = $data_arr[$file_config["file_keep"]];
                $delete_old_path = $old_file_name;
                if ($file_config["file_keep"] && $folder_id) {
                    $folder_name = trim($file_config['file_folder']) . "/" . $folder_id;
                    $response = $this->CI->general->uploadAWSData($temp_file, $folder_name, $dest_file_name);
                    if ($response) {
                        $dest_url = $folder_url . $folder_id . "/" . $dest_file_name;
                        $return_arr['file'] = $dest_file_name;
                        if ($delete_old_path != "") {
                            $delete_old_path = $folder_id . "/" . $delete_old_path;
                        }
                    }
                } else {
                    $folder_name = trim($file_config['file_folder']);
                    $response = $this->CI->general->uploadAWSData($temp_file, $folder_name, $dest_file_name);
                    if ($response) {
                        $dest_url = $folder_url . $dest_file_name;
                        $return_arr['file'] = $dest_file_name;
                    }
                }
                unlink($temp_file);
                $primary_key = $file_data['primary_key'];
                if (trim($return_arr['file']) && $data_arr[$primary_key] > 0 && $file_config['entry_type'] != "Custom") {
                    /* remove old file first and then upload */
                    if ($delete_old_path != "" && $old_file_name != $file_name) {
                        $this->CI->general->deleteAWSFileData($bucketname, $delete_old_path);
                    }
                    $table_name = $file_config['table_name'];
                    $field_name = $file_config['field_name'];
                    $update_arr[$field_name] = $return_arr['file'];
                    $this->CI->db->where($primary_key, $data_arr[$primary_key]);
                    $res = $this->CI->db->update($table_name, $update_arr);
                }
            }
        }
        return $dest_url;
    }

    public function saveFileUploadServerData($file_data = array(), $data_arr = array(), $file_config = array())
    {
        $dest_url = '';
        $file_name = $file_data['file_name'];
        $old_file_name = $file_data['old_file_name'];
        $folder_arr = $this->CI->general->getServerUploadPathURL($file_config['file_folder']);
        if ($folder_arr['status']) {
            $folder_path = $folder_arr['folder_path'];
            $folder_url = $folder_arr['folder_url'];
        }
        if ($file_name != "") {
            $temp_file = $this->CI->config->item('admin_upload_temp_path') . $file_name;
            if (is_file($temp_file)) {
                $dest_file_name = str_replace(" ", "_", $file_name);
                $folder_id = $data_arr[$file_config["file_keep"]];
                if ($file_config["file_keep"] && $folder_id) {
                    $folder_path_id = $folder_path . $folder_id;
                    $return_string = $this->CI->general->uploadServerData($folder_path_id, $temp_file, $dest_file_name);
                    $return_arr = json_decode($return_string, TRUE);
                    $dest_url = $folder_url . md5($folder_path_id) . '/' . $return_arr['file'];
                } else {
                    $return_string = $this->CI->general->uploadServerData($folder_path, $temp_file, $dest_file_name);
                    $return_arr = json_decode($return_string, TRUE);
                    $dest_url = $folder_url . md5($folder_path_id) . '/' . $return_arr['file'];
                }
                unlink($temp_file);
                $primary_key = $file_data['primary_key'];
                if (trim($return_arr['file']) && $data_arr[$primary_key] > 0) {
                    $table_name = $file_config['table_name'];
                    $field_name = $file_config['field_name'];
                    $update_arr[$field_name] = $return_arr['file'];
                    $this->CI->db->where($primary_key, $data_arr[$primary_key]);
                    $res = $this->CI->db->update($table_name, $update_arr);
                }
            }
        }
        return $dest_url;
    }

    public function saveFileUploadAdminData($file_data = array(), $data_arr = array(), $file_config = array())
    {
        $dest_url = '';
        $file_name = $file_data['file_name'];
        $old_file_name = $file_data['old_file_name'];
        $temp_file_path = $this->CI->config->item('admin_upload_temp_path') . $file_name;
        if (is_file($temp_file_path)) {
            $folder_name = $file_config['file_folder'];
            $folder_id = $data_arr[$file_config['file_keep']];
            $folder_name = $this->CI->general->getImageNestedFolders($folder_name);
            $dest_file_name = str_replace(" ", "_", $file_name);
            if ($file_config['file_keep'] && $folder_id != '') {
                $this->CI->general->createUploadFolderIfNotExists($folder_name . DS . $folder_id);
                $dest_file_path = $this->CI->config->item('upload_path') . $folder_name . DS . $folder_id . DS . $dest_file_name;
                $dest_url = $this->CI->config->item('upload_url') . $folder_name . '/' . $folder_id . '/' . $dest_file_name;
                $old_file_path = $this->CI->config->item('upload_path') . $folder_name . DS . $folder_id . DS . $old_file_name;
            } else {
                $this->CI->general->createUploadFolderIfNotExists($folder_name);
                $dest_file_path = $this->CI->config->item('upload_path') . $folder_name . DS . $dest_file_name;
                $dest_url = $this->CI->config->item('upload_url') . $folder_name . '/' . $dest_file_name;
                $old_file_path = $this->CI->config->item('upload_path') . $folder_name . DS . $old_file_name;
            }
            if (copy($temp_file_path, $dest_file_path)) {
                unlink($temp_file_path);
                if (is_file($old_file_path) && $old_file_name != '' && $old_file_name != $file_name) {
                    unlink($old_file_path);
                }
            }
        }

        return $dest_url;
    }

    public function checkHideColumn($conditions_arr = array())
    {
        $admin_url = $this->CI->config->item("admin_url");

        $conditionflag = TRUE;
        $condition_type = $conditions_arr['oper'];
        $conditions_array = $conditions_arr['conditions'];
        if (is_array($conditions_array) && count($conditions_array) > 0) {
            if ($condition_type == "AND") {
                $conditionflag = TRUE;
            } else {
                $conditionflag = FALSE;
            }
            for ($i = 0; $i < count($conditions_array); $i++) {
                $type = $conditions_array[$i]['type'];
                $operator = $conditions_array[$i]['oper'];
                $value_1 = $conditions_array[$i]['val_1'];
                $value_2 = $conditions_array[$i]['val_2'];

                $value_1 = $this->CI->general->getDataTypeWiseResult($type, $value_1, TRUE);
                $value_2 = $this->CI->general->getDataTypeWiseResult($type, $value_2, FALSE);
                $result = $this->CI->general->compareDataValues($operator, $value_1, $value_2);

                if ($condition_type == "OR") {
                    $conditionflag = $conditionflag || $result;
                    if ($conditionflag) {
                        break;
                    }
                } else {
                    $conditionflag = $conditionflag && $result;
                    if (!$conditionflag) {
                        break;
                    }
                }
            }
        }
        $returnflag = ($conditionflag == TRUE) ? 'Yes' : 'No';
        return $returnflag;
    }

    public function getGridCustomEditLink($link_data_arr = array(), $value = '', $row_arr = array(), $id = '')
    {
        $site_url = $this->CI->config->item("site_url");
        $admin_url = $this->CI->config->item("admin_url");
        $decryptArr = $this->CI->config->item("FRAMEWORK_ENCRYPTS");
        $return_link = "";
        $extra_attribute_arr = $params_arr = array();
        $return_arr['success'] = FALSE;

        if (!is_array($link_data_arr) || count($link_data_arr) == 0 || !is_array($row_arr) || count($row_arr) == 0) {
            return $return_arr;
        }
        $params_arr = $extra_attribute_arr = array();
        foreach ($link_data_arr as $inner_key => $inner_val) {
            $temp_arr = array();
            $external_url = FALSE;
            $return_link = "";
            $extra_param_arr = $inner_val['extra_params'];
            $module_name = $inner_val['module_name'];
            $folder_name = $inner_val['folder_name'];
            $module_type = $inner_val['module_type'];
            $module_page = $inner_val['module_page'];
            $open_on = $inner_val['open'];
            $custom_module_link = $inner_val['custom_link'];
            $apply_condition = $inner_val['apply'];
            $conditions_block = $inner_val['block'];
            $extra_attribute_arr['class'] = "inline-edit-link";
            if ($open_on == "NewPage") {
                $extra_attribute_arr['target'] = "_blank";
            } elseif ($open_on == "Popup") {
                $extra_attribute_arr['class'] = "inline-edit-link fancybox-hash-iframe";
            }
            if ($apply_condition == "Yes") {
                $conditionflag = $this->checkModuleConditionalBlock($conditions_block, $row_arr, $id);
                if (!$conditionflag) {
                    continue;
                }
            }

            $is_site_url = FALSE;
            if ($module_type == "Module") {
                if ($module_page == "Add" || $module_page == "Update" || $module_page == "View") {
                    $return_link = $admin_url . "#" . $this->CI->general->getAdminEncodeURL($folder_name . "/" . $module_name . "/add");
                } elseif ($module_page == "Print") {
                    $return_link = $admin_url . "#" . $this->CI->general->getAdminEncodeURL($folder_name . "/" . $module_name . "/printRecord");
                } else {
                    $return_link = $admin_url . "#" . $this->CI->general->getAdminEncodeURL($folder_name . "/" . $module_name . "/index");
                }
            } elseif (substr($custom_module_link, 0, 1) == "@" && substr($custom_module_link, -1) == "@") {
                $return_link = $custom_module_link;
            } else {
                $external_url = $this->CI->general->isExternalURL($custom_module_link);
                if ($external_url) {
                    $return_link = $custom_module_link;
                } else {
                    if (substr($custom_module_link, 0, 17) == "#SYSTEM.site_url#") {
                        $return_link = $site_url . substr($custom_module_link, 17);
                        $is_site_url = TRUE;
                    } else {
                        $custom_link_parts = explode("/", $custom_module_link);
                        if (count($custom_link_parts) == 3) {
                            $extra_params = '';
                            if (strstr($custom_link_parts[2], "|")) {
                                $method_parts = explode($custom_link_parts[2], "|", 2);
                                $custom_link_parts[2] = $method_parts[0];
                                if (!empty($method_parts[2])) {
                                    $extra_params = "|" . $method_parts[2];
                                }
                            } elseif (strstr($custom_link_parts[2], "?")) {
                                $method_parts = explode($custom_link_parts[2], "?", 2);
                                $custom_link_parts[2] = $method_parts[0];
                                if (!empty($method_parts[2])) {
                                    $extra_params = "?" . $method_parts[2];
                                }
                            }
                            $return_link = $admin_url . "#" . $this->CI->general->getAdminEncodeURL($custom_link_parts[0] . "/" . $custom_link_parts[1] . "/" . $custom_link_parts[2]) . $extra_params;
                        } else {
                            $return_link = $admin_url . "#" . $custom_module_link;
                        }
                    }
                }
            }

            $params_list = array();
            if (is_array($extra_param_arr) && count($extra_param_arr) > 0) {
                for ($i = 0; $i < count($extra_param_arr); $i++) {
                    $extra_var_val = $extra_param_arr[$i]['req_val'];
                    $extra_var_type = $extra_param_arr[$i]['req_mod'];
                    $extra_var = $extra_param_arr[$i]['req_var'];
                    if (trim($extra_var_val) == "") {
                        continue;
                    }
                    $req_val = $this->CI->general->parseConditionFieldValue($extra_var_type, $extra_var_val, $row_arr, $id);
                    if (!$is_site_url && $extra_var != "" && in_array($extra_var, $decryptArr)) {
                        $params_list[$extra_var] = $this->CI->general->getAdminEncodeURL($req_val);
                    } else {
                        $params_list[$extra_var] = $req_val;
                    }
                }
            }

            if ($open_on == "Popup") {
                if (trim($return_link) != "") {
                    $params_list['hideCtrl'] = "true";
                }
                if (isset($inner_val['width']) && $inner_val['width'] != "") {
                    $params_list['width'] = $inner_val['width'];
                }
                if (isset($inner_val['height']) && $inner_val['height'] != "") {
                    $params_list['height'] = $inner_val['height'];
                }
            }

            if (is_array($params_list) && count($params_list) > 0) {
                foreach ($params_list as $pkey => $pval) {
                    if ($is_site_url) {
                        $return_link .= '&' . $pkey . "=" . $pval;
                    } else {
                        $return_link .= '|' . $pkey . "|" . $pval;
                    }
                }
            }

            foreach ($row_arr as $key => $val) {
                $find_array[] = "@" . $key . "@";
                $replace_array[] = $val;
            }
            $return_link = str_replace($find_array, $replace_array, $return_link);

            break;
        }

        if (trim($return_link) != "") {
            $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
            if (!preg_match_all("/$regexp/", $value, $matches)) {
                $extra_attr_str = "";
                if (is_array($extra_attribute_arr) && count($extra_attribute_arr) > 0) {
                    foreach ($extra_attribute_arr as $attr_key => $attr_val) {
                        $extra_attr_str .= $attr_key . ' = "' . $attr_val . '" ';
                    }
                }
                $return_arr['formated_link'] = "<a  href='" . $return_link . "' " . $extra_attr_str . " >" . $value . "</a>";
                $return_arr['actual_link'] = $return_link;
                $return_arr['extra_attr_str'] = $extra_attr_str;
                $return_arr['success'] = TRUE;
            }
        }
        return $return_arr;
    }

    public function checkModuleConditionalBlock($condition_data_arr = array(), $row_arr = array(), $id = "")
    {
        $conditionflag = FALSE;
        $condition_type = $condition_data_arr['oper'];
        $conditions_array = $condition_data_arr['conditions'];
        if (is_array($conditions_array) && count($conditions_array) > 0) {

            if ($condition_type == "AND") {
                $conditionflag = TRUE;
            } else {
                $conditionflag = FALSE;
            }
            for ($i = 0; $i < count($conditions_array); $i++) {
                $type = $conditions_array[$i]['type'];
                $operator = $conditions_array[$i]['oper'];
                $operand_1 = $conditions_array[$i]['mod_1'];
                $value_passed_1 = $conditions_array[$i]['val_1'];
                $operand_2 = $conditions_array[$i]['mod_2'];
                $value_passed_2 = $conditions_array[$i]['val_2'];

                $value_1 = $this->CI->general->parseConditionFieldValue($operand_1, $value_passed_1, $row_arr, $id);
                $value_2 = $this->CI->general->parseConditionFieldValue($operand_2, $value_passed_2, $row_arr, $id);

                $value_1 = $this->CI->general->getDataTypeWiseResult($type, $value_1, TRUE);
                $value_2 = $this->CI->general->getDataTypeWiseResult($type, $value_2, FALSE);
                $result = $this->CI->general->compareDataValues($operator, $value_1, $value_2);

                if ($condition_type == "OR") {
                    $conditionflag = $conditionflag || $result;
                    if ($conditionflag) {
                        break;
                    }
                } else {
                    $conditionflag = $conditionflag && $result;
                    if (!$conditionflag) {
                        break;
                    }
                }
            }
        }
        return $conditionflag;
    }

    public function getGridRowColors($color_arr = array(), $row_arr = array(), $id = '')
    {
        if (!is_array($color_arr) || count($color_arr) == 0) {
            return;
        }
        $return_arr = array();
        foreach ($color_arr as $outer_key => $outer_val) {
            if (!is_array($outer_val) || count($outer_val) == 0) {
                continue;
            }
            foreach ($outer_val as $inner_key => $inner_val) {
                $temp_arr = array();
                $apply_condition = $inner_val['apply'];
                if ($apply_condition == "Yes") {
                    $type = $inner_val['type'];
                    $operator = $inner_val['oper'];
                    $operand_1 = $inner_val['mod_1'];
                    $operand_2 = $inner_val['mod_2'];
                    $value_passed_1 = $inner_val['val_1'];
                    $value_passed_2 = $inner_val['val_2'];

                    $value_1 = $this->CI->general->parseConditionFieldValue($operand_1, $value_passed_1, $row_arr, $id);
                    $value_2 = $this->CI->general->parseConditionFieldValue($operand_2, $value_passed_2, $row_arr, $id);

                    if (is_null($value_1) || is_null($value_2)) {
                        continue;
                    }
                    $value_1 = $this->CI->general->getDataTypeWiseResult($type, $value_1, TRUE);
                    $value_2 = $this->CI->general->getDataTypeWiseResult($type, $value_2, FALSE);
                    $result = $this->CI->general->compareDataValues($operator, $value_1, $value_2);
                    if (!$result) {
                        continue;
                    }
                }
                if ($inner_val['fill_color'] == "Cell") {
                    $temp_arr['fill'] = "cell";
                    $temp_arr['cell'] = $inner_val['cell_name'];
                } elseif ($inner_val['fill_color'] == "Text") {
                    $temp_arr['fill'] = "text";
                    $temp_arr['cell'] = $inner_val['cell_name'];
                } else {
                    $temp_arr['fill'] = "row";
                }
                $temp_arr['color'] = ($inner_val['color_code'] == "Variable") ? $row_arr[$inner_val['color_value']] : $inner_val['color_value'];
                break;
            }
            if (is_array($temp_arr) && count($temp_arr) > 0) {
                $return_arr[] = $temp_arr;
            }
        }
        return $return_arr;
    }

    public function parseListingFile($file_name = '', $id = '', $data_arr = array(), $config_arr = array(), $module_config = array(), $view_type = '')
    {
        $img_width = ($config_arr['file_width']) ? $config_arr['file_width'] : $this->CI->config->item('ADMIN_DEFAULT_IMAGE_WIDTH');
        $img_height = ($config_arr['file_height']) ? $config_arr['file_height'] : $this->CI->config->item('ADMIN_DEFAULT_IMAGE_HEIGHT');
        $alias_name = $config_arr['name'];
        $module_name = $module_config['module_name'];
        $folder_name = $module_config['folder_name'];
        $download_list_file = $module_config['mod_enc_url']['download_list_file'];
        $admin_url = $this->CI->config->item('admin_url');
        $download_text = $this->CI->lang->line('GENERIC_FILE_DOWNLOAD');
        $view_text = $this->CI->lang->line('GENERIC_FILE_VIEW');

        $enc_id = $this->CI->general->getAdminEncodeURL($id);
        $align_class = "cell-" . $config_arr['align'];
        if ($file_name != "") {
            $file_server = $config_arr['file_server'];
            $file_tooltip = $config_arr['file_tooltip'];
            $tooltip_class_img = $tooltip_class_anc = "";
            if ($file_tooltip == "Yes") {
                $tooltip_class_img = "inline-image-jip";
                $tooltip_class_anc = "anc-image-jip";
            }
            $export_type_arr = array("jpeg", "jpg", "png", "gif");
            $folder_id = '';
            switch ($file_server) {
                case 'custom':
                    $folder_arr = $this->CI->general->getServerUploadPathURL($config_arr['file_folder']);
                    if ($folder_arr['status']) {
                        $original_path = $folder_arr['folder_path'];
                        $original_url = $folder_arr['folder_url'];
                        if ($config_arr['file_keep']) {
                            $folder_id = $data_arr[$config_arr['file_keep']];
                            $original_path = $original_path . $folder_id;
                        }
                    }
                    break;
                case 'amazon':
                    $folder_arr = $this->CI->general->getAWSServerUploadPathURL($config_arr['file_folder']);
                    if ($folder_arr['status']) {
                        $original_path = $folder_arr['folder_path'];
                        $original_url = $folder_arr['folder_url'];
                        if ($config_arr['file_keep']) {
                            $folder_id = $data_arr[$config_arr['file_keep']];
                            $original_path = $original_path . $folder_id . "/";
                            $original_url = $original_url . $folder_id . '/';
                        }
                    }
                    break;
                default :
                    $folder_arr = $this->CI->general->getAdminUploadPathURL($config_arr['file_folder']);
                    if ($folder_arr['status']) {
                        $original_path = $folder_arr['folder_path'];
                        $original_url = $folder_arr['folder_url'];
                        if ($config_arr['file_keep']) {
                            $folder_id = $data_arr[$config_arr['file_keep']];
                            $original_path = $original_path . $folder_id . DS;
                            $original_url = $original_url . $folder_id . '/';
                        }
                        $file_path = $original_path . $file_name;
                    }
                    break;
            }
            $export_image = 0;
            $is_inline = $config_arr['file_inline'];
            $edit_link = $config_arr['edit_link'];
            $classes = " list-image " . $alias_name;
            if ($file_server == 'custom') {
                $source_url = $original_url . md5($original_path) . "/" . $file_name;
                $source_path = $original_path . md5($original_path) . "/" . $file_name;
                $image_valid_ext = explode('.', $file_name);
                if ($view_type == "GExport") {
                    $headers = get_headers($source_url);
                    if (strpos($headers[0], '200') === FALSE) {
                        $export_url = $this->CI->general->getNoImageURL();
                        $export_path = $this->CI->general->getNoImagePath();
                        $export_image = 1;
                    } else {
                        if (in_array(strtolower(end($image_valid_ext)), $export_type_arr)) {
                            $export_url = $source_url;
                            $export_path = $source_path;
                            $export_image = 1;
                        } else {
                            $export_url = $source_url;
                            $export_path = $source_path;
                            $export_image = 2;
                        }
                    }
                } else {
                    if (in_array(strtolower(end($image_valid_ext)), $this->CI->config->item('IMAGE_EXTENSION_ARR'))) {
                        if ($is_inline == "Yes") {
                            if ($edit_link == "Yes") {
                                $buttons_html .= "<img src='" . $source_url . "' width='" . $img_width . "' height='" . $img_height . "' class='" . $tooltip_class_img . $classes . "' onerror='imageLoadingError(this)' />";
                            } else {
                                $buttons_html .= "<a hijacked='yes' title='" . $file_name . "' href='" . $source_url . "' class='fancybox-image list-image-anchor'><img src='" . $source_url . "' width='" . $img_width . "' height='" . $img_height . "' class='" . $tooltip_class_img . $classes . "' onerror='imageLoadingError(this)' /></a>";
                            }
                        } else {
                            $buttons_html .= "<a hijacked='yes' title='" . $file_name . "' href='" . $source_url . "' class='fancybox-image " . $tooltip_class_anc . " list-image-view'>" . $view_text . "</a>";
                        }
                    } else {
                        $buttons_html .= "<a hijacked='yes' class='list-file-view tip' href='" . $admin_url . $download_list_file . "?&id=" . $enc_id . "&folder=" . $folder_id . "&alias_name=" . $alias_name . "' title='" . format_uploaded_file($file_name) . "'><i class='icon26 entypo-icon-download'></i></a>";
                    }
                }
            } elseif ($file_server == 'amazon') {
                $source_url = $original_url . $file_name;
                $source_path = $original_path . $file_name;
                $image_valid_ext = explode('.', $file_name);
                if ($folder_id) {
                    $bucket_folder = $folder_arr['bucket_folder'] . "/" . $folder_id;
                } else {
                    $bucket_folder = $folder_arr['bucket_folder'];
                }
                $file_found = $this->CI->general->checkFileExistsOnAWSObject($folder_arr['bucket_files'], $file_name, $folder_arr['bucket_name'], $bucket_folder);
                if ($view_type == "GExport") {
                    if ($file_found) {
                        if (in_array(strtolower(end($image_valid_ext)), $export_type_arr)) {
                            $export_url = $source_url;
                            $export_path = $source_path;
                            $export_image = 1;
                        } else {
                            $export_url = $source_url;
                            $export_path = $source_path;
                            $export_image = 2;
                        }
                    } else {
                        $export_url = $this->CI->general->getNoImageURL();
                        $export_path = $this->CI->general->getNoImagePath();
                        $export_image = 1;
                    }
                } else {
                    if ($file_found) {
                        if (in_array(strtolower(end($image_valid_ext)), $this->CI->config->item('IMAGE_EXTENSION_ARR'))) {
                            if ($is_inline == "Yes") {
                                if ($edit_link == "Yes") {
                                    $buttons_html .= "<img src='" . $source_url . "' width='" . $img_width . "' height='" . $img_height . "' class='" . $tooltip_class_img . $classes . "' onerror='imageLoadingError(this)' />";
                                } else {
                                    $buttons_html .= "<a hijacked='yes' title='" . $file_name . "' href='" . $source_url . "' class='fancybox-image list-image-anchor'><img src='" . $source_url . "' width='" . $img_width . "' height='" . $img_height . "' class='" . $tooltip_class_img . $classes . "' onerror='imageLoadingError(this)' /></a>";
                                }
                            } else {
                                $buttons_html .= "<a hijacked='yes' title='" . $file_name . "' href='" . $source_url . "' class='fancybox-image " . $tooltip_class_anc . " list-view-view'>" . $view_text . "</a>";
                            }
                        } else {
                            $buttons_html .= "<a hijacked='yes' class='list-file-view tip' href='" . $admin_url . $download_list_file . "?&id=" . $enc_id . "&folder=" . $folder_id . "&alias_name=" . $alias_name . "' title='" . format_uploaded_file($file_name) . "'><i class='icon26 entypo-icon-download'></i></a>";
                        }
                    } else {
                        if ($img_width > 50) {
                            $buttons_html .= "<div class='noimage-icon list-no-image " . $align_class . " " . $alias_name . "'></div>";
                        } else {
                            $buttons_html .= "<div class='noimage-icon-small list-no-image " . $align_class . " " . $alias_name . "'></div>";
                        }
                    }
                }
            } else {
                $image_valid_ext = explode('.', $file_name);
                if ($view_type == "GExport") {
                    if (is_file($file_path)) {
                        if (in_array(strtolower(end($image_valid_ext)), $export_type_arr)) {
                            $export_url = $original_url . $file_name;
                            $export_path = $original_path . $file_name;
                            $export_image = 1;
                        } else {
                            $export_url = $original_url . $file_name;
                            $export_path = $original_path . $file_name;
                            $export_image = 2;
                        }
                    } else {
                        $export_url = $this->CI->general->getNoImageURL();
                        $export_path = $this->CI->general->getNoImagePath();
                        $export_image = 1;
                    }
                } else {
                    if (is_file($file_path)) {
                        if (in_array(strtolower(end($image_valid_ext)), $this->CI->config->item('IMAGE_EXTENSION_ARR'))) {
                            if ($this->CI->config->item('allowimageprocess') == 'Yes') {
                                $source_url = $this->getResizedImage($original_url, $file_name, $img_width, $img_height);
                                $image_params = " width='" . $img_width . "' height='" . $img_height . "' ";
                            } else {
                                $source_url = $original_url . $file_name;
                                $image_params = " width='" . $img_width . "' height='" . $img_height . "' ";
                            }
                            if ($is_inline == "Yes") {
                                if ($edit_link == "Yes") {
                                    $buttons_html .= "<img src='" . $source_url . "' class='" . $tooltip_class_img . $classes . "' " . $image_params . " />";
                                } else {
                                    $buttons_html .= "<a hijacked='yes' title='" . $file_name . "' href='" . $original_url . $file_name . "' class='fancybox-image list-image-anchor'><img src='" . $source_url . "' class='" . $tooltip_class_img . $classes . "' " . $image_params . "/></a>";
                                }
                            } else {
                                $buttons_html .= "<a hijacked='yes' title='" . $file_name . "' href='" . $original_url . $file_name . "' class='fancybox-image " . $tooltip_class_anc . " list-image-view'>" . $view_text . "</a>";
                            }
                        } else {
                            $buttons_html .= "<a class='list-file-view tip' href='" . $admin_url . $download_list_file . "?&id=" . $enc_id . "&folder=" . $folder_id . "&alias_name=" . $alias_name . "' title='" . format_uploaded_file($file_name) . "'><i class='icon26 entypo-icon-download'></i></a>";
                        }
                    } else {
                        if ($img_width > 50) {
                            $buttons_html .= "<div class='noimage-icon list-no-image " . $align_class . " " . $alias_name . "'></div>";
                        } else {
                            $buttons_html .= "<div class='noimage-icon-small list-no-image " . $align_class . " " . $alias_name . "'></div>";
                        }
                    }
                }
            }
        } else {
            if ($view_type == "GExport") {
                $export_url = $this->CI->general->getNoImageURL();
                $export_path = $this->CI->general->getNoImagePath();
                $export_image = 1;
            } else {
                if ($img_width > 50) {
                    $buttons_html .= "<div class='noimage-icon list-no-image " . $align_class . " " . $alias_name . "'></div>";
                } else {
                    $buttons_html .= "<div class='noimage-icon-small list-no-image " . $align_class . " " . $alias_name . "'></div>";
                }
            }
        }
        if ($view_type == "GExport") {
            return array($export_url, $export_path, $export_image, $img_width, $img_height);
        } else {
            return $buttons_html;
        }
    }

    public function parseFormFile($file_name = '', $id = '', $data_arr = array(), $config_arr = array(), $module_config = array(), $view_type = '', $del = TRUE, $ret_flag = "No")
    {
        $view_html = $del_html = $title_html = $download_html = $hover_html = $noimg_html = '';
        $img_width = ($config_arr['file_width']) ? $config_arr['file_width'] : $this->CI->config->item('ADMIN_DEFAULT_IMAGE_WIDTH');
        $img_height = ($config_arr['file_height']) ? $config_arr['file_height'] : $this->CI->config->item('ADMIN_DEFAULT_IMAGE_HEIGHT');
        $unique_name = $config_arr['name'];
        $module_name = $module_config['module_name'];
        $folder_name = $module_config['folder_name'];
        $entry_type = $config_arr['entry_type'];
        $download_text = $this->CI->lang->line('GENERIC_FILE_DOWNLOAD');
        $admin_url = $this->CI->config->item('admin_url');
        $delete_form_file = $module_config['mod_enc_url']['delete_form_file'];
        $download_form_file = $module_config['mod_enc_url']['download_form_file'];
        if (isset($config_arr['lang_val'])) {
            $htmlID = "lang" . $config_arr['htmlID'] . "_" . $config_arr['lang_val'];
            $langID = $config_arr['lang_val'];
        } else {
            $htmlID = $config_arr['htmlID'];
            $langID = $this->CI->config->item("PRIME_LANG");
        }

        $enc_id = $this->CI->general->getAdminEncodeURL($id);
        $display_class = '';
        if ($file_name != "") {
            $file_server = $config_arr['file_server'];
            $file_tooltip = $config_arr['file_tooltip'];
            $tooltip_class_img = $tooltip_class_anc = "";
            if ($file_tooltip == "Yes") {
                $tooltip_class_img = "inline-image-jip";
                $tooltip_class_anc = "anc-image-jip";
            }
            if ($config_arr['file_label'] == "Yes") {
                $del = FALSE;
            }
            $folder_id = $file_id = $file_arg = '';
            switch ($file_server) {
                case 'custom':
                    $folder_arr = $this->CI->general->getServerUploadPathURL($config_arr['file_folder']);
                    if ($folder_arr['status']) {
                        $original_path = $folder_arr['folder_path'];
                        $original_url = $folder_arr['folder_url'];
                        if ($config_arr['file_keep']) {
                            $folder_id = $data_arr[$config_arr['file_keep']];
                            $original_path = $original_path . $folder_id;
                        }
                    }
                    break;
                case 'amazon':
                    $folder_arr = $this->CI->general->getAWSServerUploadPathURL($config_arr['file_folder']);
                    if ($folder_arr['status']) {
                        $original_path = $folder_arr['folder_path'];
                        $original_url = $folder_arr['folder_url'];
                        if ($config_arr['file_keep']) {
                            $folder_id = $data_arr[$config_arr['file_keep']];
                            $original_path = $original_path . $folder_id . "/";
                            $original_url = $original_url . $folder_id . '/';
                        }
                        $file_path = $original_path . $file_name;
                    }
                    break;
                default :
                    $folder_arr = $this->CI->general->getAdminUploadPathURL($config_arr['file_folder']);
                    if ($folder_arr['status']) {
                        $original_path = $folder_arr['folder_path'];
                        $original_url = $folder_arr['folder_url'];
                        if ($config_arr['file_keep']) {
                            $folder_id = $data_arr[$config_arr['file_keep']];
                            $original_path = $original_path . $folder_id . DS;
                            $original_url = $original_url . $folder_id . '/';
                        }
                        $file_path = $original_path . $file_name;
                    }
                    break;
            }
            if ($entry_type == "Custom") {
                $file_id = '&file=' . $file_name;
                $file_arg = $file_name;
            }

            $classes = " form-image " . $unique_name;
            if ($file_server == 'custom') {
                $source_url = $original_url . md5($original_path) . "/" . $file_name;
                $image_valid_ext = explode('.', $file_name);
                if (in_array(strtolower(end($image_valid_ext)), $this->CI->config->item('IMAGE_EXTENSION_ARR'))) {
                    $view_html .= "<a hijacked='yes' class='fancybox-image form-image-anchor viewer-item-src' data-item-url='" . $source_url . "' href='" . $source_url . "' id='anc_imgview_" . $htmlID . "' title='" . $file_name . "'><img src='" . $source_url . "' alt='Image' width='" . $img_width . "' height='" . $img_height . "' class='" . $tooltip_class_img . $classes . "' onerror='imageLoadingError(this)' /></a>";
                } else {
                    $icon_class = 'fa-file-text-o';
                    if (function_exists("get_file_icon_class")) {
                        $icon_class = get_file_icon_class($file_name);
                    }
                    $view_html .= "<a hijacked='yes' class='form-image-view viewer-item-src' data-item-url='" . $source_url . "' href='" . $admin_url . $download_form_file . "?folder=" . $folder_id . "&unique_name=" . $unique_name . "&id=" . $enc_id . "" . $file_id . "' id='anc_imgview_" . $htmlID . "'><i class='fa " . $icon_class . " fa-3x file-icon-view'></i></a>";
                    $download_html = "<div title='" . format_uploaded_file($file_name) . "' class='tip'><a hijacked='yes' class='form-image-view' href='" . $admin_url . $download_form_file . "?folder=" . $folder_id . "&unique_name=" . $unique_name . "&id=" . $enc_id . "" . $file_id . "'><i class='icon18 minia-icon-download no-margin'></i></a></div>";
                    $hover_html = '<div id="img_hover_' . $htmlID . '" class="img-hover-section">' . $download_html . '</div>';
                    $display_class = 'file-inline-display';
                }
                if ($del) {
                    $del_html .= "<a title='Delete' href='javascript://' onclick='deleteFileTypeDocs(\"" . $enc_id . "\", \"" . $unique_name . "\", \"" . $delete_form_file . "\", \"" . $folder_id . "\", \"" . $htmlID . "\", \"" . $langID . "\", \"" . $file_arg . "\")' id='anc_imgdel_" . $htmlID . "' ><i class='icon16 entypo-icon-close icon-red no-margin'></i></a>";
                }
            } elseif ($file_server == 'amazon') {
                $source_url = $original_url . $file_name;
                $image_valid_ext = explode('.', $file_name);
                if ($folder_id) {
                    $bucket_folder = $folder_arr['bucket_folder'] . "/" . $folder_id;
                } else {
                    $bucket_folder = $folder_arr['bucket_folder'];
                }
                $file_found = $this->CI->general->checkFileExistsOnAWSObject($folder_arr['bucket_files'], $file_name, $folder_arr['bucket_name'], $bucket_folder);
                if ($file_found) {
                    if (in_array(strtolower(end($image_valid_ext)), $this->CI->config->item('IMAGE_EXTENSION_ARR'))) {
                        $view_html .= "<a hijacked='yes' class='fancybox-image form-image-anchor viewer-item-src' data-item-url='" . $source_url . "' href='" . $source_url . "' id='anc_imgview_" . $htmlID . "' title='" . $file_name . "'><img src='" . $source_url . "' alt='Image' width='" . $img_width . "' height='" . $img_height . "' class='" . $tooltip_class_img . $classes . "' onerror='imageLoadingError(this)' /></a>";
                    } else {
                        $icon_class = 'fa-file-text-o';
                        if (function_exists("get_file_icon_class")) {
                            $icon_class = get_file_icon_class($file_name);
                        }
                        $view_html .= "<a hijacked='yes' class='form-image-view viewer-item-src' data-item-url='" . $source_url . "' href='" . $admin_url . $download_form_file . "?folder=" . $folder_id . "&unique_name=" . $unique_name . "&id=" . $enc_id . "" . $file_id . "' id='anc_imgview_" . $htmlID . "'><i class='fa " . $icon_class . " fa-3x file-icon-view'></i></a>";
                        $download_html = "<div title='" . format_uploaded_file($file_name) . "' class='tip'><a hijacked='yes' class='form-image-view' href='" . $admin_url . $download_form_file . "?folder=" . $folder_id . "&unique_name=" . $unique_name . "&id=" . $enc_id . "" . $file_id . "'><i class='icon18 minia-icon-download no-margin'></i></a></div>";
                        $hover_html = '<div id="img_hover_' . $htmlID . '" class="img-hover-section">' . $download_html . '</div>';
                        $display_class = 'file-inline-display';
                    }
                    if ($del) {
                        $del_html .= "<a title='Delete' href='javascript://' onclick='deleteFileTypeDocs(\"" . $enc_id . "\", \"" . $unique_name . "\", \"" . $delete_form_file . "\", \"" . $folder_id . "\", \"" . $htmlID . "\", \"" . $langID . "\", \"" . $file_arg . "\")' id='anc_imgdel_" . $htmlID . "' ><i class='icon16 entypo-icon-close icon-red no-margin'></i></a>";
                    }
                } else {
                    if ($ret_flag == "Yes") {
                        return '';
                    } else {
                        if ($img_width > 50) {
                            //$noimg_html = "<div class='noimage-icon form-no-image'></div>";
                        } else {
                            //$noimg_html = "<div class='noimage-icon-small form-no-image'></div>";
                        }
                        //$view_html .= "<span>" . $noimg_html . "</span>";
                    }
                }
            } else {
                if (is_file($file_path)) {
                    $image_valid_ext = explode('.', $file_name);
                    if (in_array(strtolower(end($image_valid_ext)), $this->CI->config->item('IMAGE_EXTENSION_ARR'))) {
                        if ($this->CI->config->item('allowimageprocess') == 'Yes') {
                            $source_url = $this->getResizedImage($original_url, $file_name, $img_width, $img_height, '', 'fill');
                            $image_params = " width='" . $img_width . "' height='" . $img_height . "' ";
                        } else {
                            $source_url = $original_url . $file_name;
                            $image_params = " width='" . $img_width . "' height='" . $img_height . "' ";
                        }
                        $view_html .= "<a hijacked='yes' class='fancybox-image form-image-anchor viewer-item-src' data-item-url='" . $original_url . $file_name . "' href='" . $original_url . $file_name . "' id='anc_imgview_" . $htmlID . "' title='" . $file_name . "'><img src='" . $source_url . "' alt='Image' " . $image_params . " class='" . $tooltip_class_img . $classes . "'/></a>";
                    } else {
                        $icon_class = 'fa-file-text-o';
                        if (function_exists("get_file_icon_class")) {
                            $icon_class = get_file_icon_class($file_name);
                        }
                        $view_html .= "<a hijacked='yes' class='form-image-view viewer-item-src' data-item-url='" . $original_url . $file_name . "' href='" . $admin_url . $download_form_file . "?folder=" . $folder_id . "&unique_name=" . $unique_name . "&id=" . $enc_id . "" . $file_id . "' id='anc_imgview_" . $htmlID . "'><i class='fa " . $icon_class . " fa-3x file-icon-view'></i></a>";
                        $download_html = "<div title='" . format_uploaded_file($file_name) . "' class='tip'><a hijacked='yes' class='form-image-view' href='" . $admin_url . $download_form_file . "?folder=" . $folder_id . "&unique_name=" . $unique_name . "&id=" . $enc_id . "" . $file_id . "'><i class='icon18 minia-icon-download no-margin'></i></a></div>";
                        $hover_html = '<div id="img_hover_' . $htmlID . '" class="img-hover-section">' . $download_html . '</div>';
                        $display_class = 'file-inline-display';
                    }
                    if ($del) {
                        $del_html .= "<a title='Delete' href='javascript://' onclick='deleteFileTypeDocs(\"" . $enc_id . "\", \"" . $unique_name . "\", \"" . $delete_form_file . "\", \"" . $folder_id . "\", \"" . $htmlID . "\", \"" . $langID . "\", \"" . $file_arg . "\")' id='anc_imgdel_" . $htmlID . "' ><i class='icon16 entypo-icon-close icon-red no-margin'></i></a>";
                    }
                } else {
                    if ($ret_flag == "Yes") {
                        return '';
                    } else {
                        if ($img_width > 50) {
                            //$noimg_html = "<div class='noimage-icon form-no-image'></div>";
                        } else {
                            //$noimg_html = "<div class='noimage-icon-small form-no-image'></div>";
                        }
                        //$view_html .= "<span>" . $noimg_html . "</span>";
                    }
                }
            }
        } else {
            if ($ret_flag == "Yes") {
                return '';
            } else {
                if ($img_width > 50) {
                    //$noimg_html = "<div class='noimage-icon form-no-image'></div>";
                } else {
                    //$noimg_html = "<div class='noimage-icon-small form-no-image'></div>";
                }
                //$view_html .= "<span>" . $noimg_html . "</span>";
            }
        }

        $view_html_str = "
                <div id='img_view_" . $htmlID . "' class='img-view-section'>
                    " . $view_html . "
                </div>";
        $del_html_str = "
                <div id='img_del_" . $htmlID . "'  class='img-del-section'>
                    " . $del_html . "
                </div>";

        $final_html = "<div id='img_buttons_" . $htmlID . "' class='img-inline-display " . $display_class . "'>" . $view_html_str . $del_html_str . $hover_html . "<div class='clear'></div></div>";

        return $final_html;
    }

    public function getDataForList($list_data = array(), $config_arr = array(), $type = "", $except_arr = array())
    {
        $return_array = array();
        $module_config = $config_arr['module_config'];
        $list_config = $config_arr['list_config'];
        $form_config = $config_arr['form_config'];
        $dropdown_arr = $config_arr['dropdown_arr'];
        $table_name = $config_arr['table_name'];
        $table_alias = $config_arr['table_alias'];
        $primary_key = $config_arr['primary_key'];
        $except_arr = is_array($except_arr) ? $except_arr : array();
        for ($i = 0; $i < count($list_data); $i++) {
            $id = $list_data[$i][$primary_key];
            $data_arr = $list_data[$i];
            if (is_array($data_arr) && count($data_arr) > 0) {
                foreach ($data_arr as $j => $val) {
                    $name = $j;
                    $field_config = $list_config[$name];
                    $source_field = $field_config['source_field'];
                    $source_config = $form_config[$source_field];
                    $combo_config = $dropdown_arr[$source_field];

                    $temp_val = $val;
                    if (!in_array("file", $except_arr) && $field_config['file_upload'] == "Yes") {
                        list($temp_val, $temp_path, $is_file, $width, $height) = $this->parseListingFile($temp_val, $id, $data_arr, $field_config, $module_config, $type);
                        if ($is_file) {
                            $img_arr = array();
                            $img_arr['file'] = $is_file;
                            $img_arr['data'] = $temp_val;
                            $img_arr['path'] = $temp_path;
                            $img_arr['width'] = $width;
                            $img_arr['height'] = $height;
                            $list_data[$i][$j] = $img_arr;
                        } else {
                            $list_data[$i][$j] = $temp_val;
                        }
                    } else {
                        if (!in_array("encrypt", $except_arr)) {
                            if ($field_config['encrypt'] == 'Yes') {
                                $temp_val = $this->CI->general->decryptDataMethod($temp_val, $field_config['enctype']);
                            }
                        }
                        if (!in_array("format", $except_arr)) {
                            $temp_val = $this->formatListingData($temp_val, $id, $data_arr, $field_config, $source_config, $combo_config, $type, $i);
                        }
                        $list_data[$i][$j] = $temp_val;
                    }
                }
            }
        }
        return $list_data;
    }

    public function formatDashboardData($value = '', $id = '', $data_arr = array(), $field_config = array(), $module_config = array(), $type = '', $index = '')
    {
        $php_func = $field_config['php_func'];
        $date_format = trim($field_config['php_date']);
        $edit_link = $field_config['edit_link'];
        $custom_link = $field_config['custom_link'];
        $custom_attr = $field_config['custom_attr'];
        $ret_data = $value;
        if ($field_config['file_upload'] == "Yes") {
            $ret_data = $this->parseListingFile($ret_data, $id, $data_arr, $field_config, $module_config, "DGrid");
        }
        if ($php_func != "") {
            if (function_exists($php_func)) {
                $ret_data = call_user_func($php_func, $ret_data);
            } elseif (method_exists($this->CI->general, $php_func)) {
                $ret_data = $this->CI->general->$php_func($ret_data, $id, $data_arr, $index, $field_config);
            } elseif (substr($php_func, 0, 12) == 'controller::' && substr($php_func, 12) !== FALSE) {
                $php_func = substr($php_func, 12);
                $ctrl_obj = $this->CI->general->getControllerObject();
                if (method_exists($ctrl_obj, $php_func)) {
                    $ret_data = $ctrl_obj->$php_func($ret_data, $id, $data_arr, $index, $field_config);
                }
            } elseif (substr($php_func, 0, 7) == 'model::' && substr($php_func, 7) !== FALSE) {
                $php_func = substr($php_func, 7);
                $model_obj = $this->CI->general->getModelObject();
                if (method_exists($model_obj, $php_func)) {
                    $ret_data = $model_obj->$php_func($ret_data, $id, $data_arr, $index, $field_config);
                }
            }
        } elseif ($date_format != "") {
            $ret_data = date($date_format, strtotime($ret_data));
        }
        if ($type == "Pivot" || $type == "Grid") {
            $parse_data = $ret_data;
        }
        if ($edit_link == "Yes") {
            if ($custom_link == "Yes" && is_array($custom_attr)) {
                $custom_link_temp = $this->getGridCustomEditLink($custom_attr, $ret_data, $data_arr, $id);
                if ($custom_link_temp['success']) {
                    $ret_data = $custom_link_temp['formated_link'];
                }
            } else {
                $folder_name = $module_config['folder_name'];
                $module_name = $module_config['module_name'];
                $edit_link_text = $this->CI->config->item("admin_url") . "#" . $this->CI->general->getAdminEncodeURL($folder_name . "/" . $module_name . "/add") . "|mode|" . $this->CI->general->getAdminEncodeURL("Update") . "|id|" . $this->CI->general->getAdminEncodeURL($id);
                $ret_data = '<a class="inline-edit-link" href="' . $edit_link_text . '" >' . $ret_data . '</a>';
            }
        }
        if ($type == "Pivot" || $type == "Grid") {
            return array($parse_data, $ret_data);
        } else {
            return $ret_data;
        }
    }

    public function addSlashesForArray($arr = array(), $restrict_arr = array())
    {
        if (!is_array($arr) || count($arr) == 0) {
            return $arr;
        }
        foreach ($arr as $key => $val) {
            $arr[$key] = $this->addSlashesRecursive($val, $restrict_arr);
        }
        return $arr;
    }

    public function addSlashesRecursive($arr = '', $omit_arr = array())
    {
        $ret_arr = $arr;
        if (is_array($arr) && count($arr) > 0) {
            foreach ($arr as $key => $val) {
                if (is_array($omit_arr) && in_array($key, $omit_arr)) {
                    continue;
                }
                $ret_arr[$key] = $this->addSlashesRecursive($val);
            }
        } elseif ($arr) {
            $ret_arr = addslashes($arr);
        }
        return $ret_arr;
    }

    public function dataForExportMode($data = '', $export_type = 'csv', $pdf_type = "FPDF")
    {
        if ($export_type == "pdf" && $pdf_type == "TCPDF") {
            $value = $data;
        } else {
            if (is_array($data) && $data['file']) {
                $value = $data['data'];
            } else {
                $value = $data;
            }
        }
        if ($export_type == "csv") {
            $value = str_replace('"', "'", $value);
        }
        return $value;
    }

    public function getDashboardAttributes($arr = array())
    {
        $attr = json_decode($arr['tLayoutJSON'], TRUE);
        if (!is_array($attr) || count($attr) == 0) {
            $order = $arr['iBlockOrder'];
            if ($order % 2 == 0) {
                $size_x = 3;
                $size_y = 5;
                $col = 4;
                $rowc = $order / 2;
                $row = ($rowc - 1) * 5 + ($rowc - 1);
                $row = ($row) ? $row : 1;
            } else {
                $size_x = 3;
                $size_y = 5;
                $col = 1;
                $rowc = ($order + 1) / 2;
                $row = ($rowc - 1) * 5 + ($rowc - 1);
                $row = ($row) ? $row : 1;
            }
            $prop = 'data-row="' . $row . '" data-col="' . $col . '" data-sizex="' . $size_x . '" data-sizey="' . $size_y . '"';
        } else {
            $prop = 'data-row="' . $attr['row'] . '" data-col="' . $attr['col'] . '" data-sizex="' . $attr['size_x'] . '" data-sizey="' . $attr['size_y'] . '"';
        }
        return $prop;
    }

    public function getDashboardAPIData($api_name = '', $params = array())
    {
        $ret_arr = array();
        if ($api_name == "") {
            return $ret_arr;
        }
        $this->CI->general->setWebserviceModulePath();
        try {
            $this->CI->config->load('cit_webservices', TRUE);
            $all_methods = $this->CI->config->item('cit_webservices');
            if (empty($all_methods[$api_name])) {
                throw new Exception('API code not found. Please save settings or update code.');
            }
            $multi_lingual = $this->CI->config->item('MULTI_LINGUAL_PROJECT');
            if ($multi_lingual == "Yes") {
                if (empty($params['lang_id'])) {
                    $params['lang_id'] = $this->CI->config->item('DEFAULT_LANG');
                }
                $_POST['lang_id'] = $params['lang_id'];
            }
            $this->CI->load->module($all_methods[$api_name]['folder'] . "/" . $api_name);
            if (!is_object($this->CI->$api_name)) {
                throw new Exception("API source not found..!");
            }
            $start_method = "start_" . $api_name;
            if (!method_exists($this->CI->$api_name, $start_method)) {
                throw new Exception('API init method not found. Please save settings or update code.');
            }
            $api_arr = $this->CI->$api_name->$start_method($params, TRUE);
            if (!is_array($api_arr) || count($api_arr) == 0) {
                throw new Exception("Faliure in loading API data..!");
            }
            if (isset($api_arr['success']) && $api_arr['success'] == "-5") {
                throw new Exception($api_arr['message']);
            }
            $ret_arr['success'] = $api_arr['settings']['success'];
            $ret_arr['message'] = $api_arr['settings']['message'];
            $ret_arr['data'] = (is_array($api_arr['data'])) ? $api_arr['data'] : array();
        } catch (Exception $e) {
            $ret_arr['success'] = 0;
            $ret_arr['message'] = $e->getMessage();
            $ret_arr['data'] = array();
        }
        $this->CI->general->unsetWebserviceModulePath();
        return $ret_arr;
    }

    public function callModuleAPIMethod($api_name = '', $map_params = array(), $post_params = array(), $db_params = array())
    {
        $ret_arr = array();
        if ($api_name == "") {
            return $ret_arr;
        }
        $this->CI->general->setWebserviceModulePath();
        try {
            $params = $this->mapModuleAPIParams($map_params, $post_params, $db_params);
            $this->CI->config->load('cit_webservices', TRUE);
            $all_methods = $this->CI->config->item('cit_webservices');
            if (empty($all_methods[$api_name])) {
                throw new Exception('API code not found. Please save settings or update code.');
            }
            $multi_lingual = $this->CI->config->item('MULTI_LINGUAL_PROJECT');
            if ($multi_lingual == "Yes") {
                if (empty($params['lang_id'])) {
                    $params['lang_id'] = $this->CI->config->item('DEFAULT_LANG');
                }
                $_POST['lang_id'] = $params['lang_id'];
            }
            $this->CI->load->module($all_methods[$api_name]['folder'] . "/" . $api_name);
            if (!is_object($this->CI->$api_name)) {
                throw new Exception("API source not found..!");
            }
            $start_method = "start_" . $api_name;
            if (!method_exists($this->CI->$api_name, $start_method)) {
                throw new Exception('API init method not found. Please save settings or update code.');
            }
            $api_arr = $this->CI->$api_name->$start_method($params, TRUE);
            if (!is_array($api_arr) || count($api_arr) == 0) {
                throw new Exception("Faliure in loading API data..!");
            }
            if (isset($api_arr['success']) && $api_arr['success'] == "-5") {
                throw new Exception($api_arr['message']);
            }
            $ret_arr['success'] = $api_arr['settings']['success'];
            $ret_arr['message'] = $api_arr['settings']['message'];
        } catch (Exception $e) {
            $ret_arr['success'] = 0;
            $ret_arr['message'] = $e->getMessage();
        }
        $this->CI->general->unsetWebserviceModulePath();
        return $ret_arr;
    }

    public function callGridAPIMethod($api_name = '', $params = array())
    {
        $ret_arr = array();
        if ($api_name == "") {
            return $ret_arr;
        }
        $this->CI->general->setWebserviceModulePath();
        try {
            $this->CI->config->load('cit_webservices', TRUE);
            $all_methods = $this->CI->config->item('cit_webservices');
            if (empty($all_methods[$api_name])) {
                throw new Exception('API code not found. Please save settings or update code.');
            }
            $multi_lingual = $this->CI->config->item('MULTI_LINGUAL_PROJECT');
            if ($multi_lingual == "Yes") {
                if (empty($params['lang_id'])) {
                    $params['lang_id'] = $this->CI->config->item('DEFAULT_LANG');
                }
                $_POST['lang_id'] = $params['lang_id'];
            }
            $this->CI->load->module($all_methods[$api_name]['folder'] . "/" . $api_name);
            if (!is_object($this->CI->$api_name)) {
                throw new Exception("API source not found..!");
            }
            $start_method = "start_" . $api_name;
            if (!method_exists($this->CI->$api_name, $start_method)) {
                throw new Exception('API init method not found. Please save settings or update code.');
            }
            $api_arr = $this->CI->$api_name->$start_method($params, TRUE);
            if (!is_array($api_arr) || count($api_arr) == 0) {
                throw new Exception("Faliure in loading API data..!");
            }
            if (isset($api_arr['success']) && $api_arr['success'] == "-5") {
                throw new Exception($api_arr['message']);
            }
            $ret_arr['success'] = $api_arr['settings']['success'];
            $ret_arr['message'] = $api_arr['settings']['message'];

            if (!empty($api_arr['data'])) {
                if (array_key_exists(0, $api_arr['data']) && count($api_arr['data']) == 1) {
                    $ret_arr = array_merge($ret_arr, $api_arr['data'][0]);
                } else {
                    $ret_arr = array_merge($ret_arr, $api_arr['data']);
                }
            }
        } catch (Exception $e) {
            $ret_arr['success'] = 0;
            $ret_arr['message'] = $e->getMessage();
        }
        $this->CI->general->unsetWebserviceModulePath();
        return $ret_arr;
    }

    public function mapModuleAPIParams($map_params = array(), $post_params = array(), $db_params = array())
    {
        $params = array();
        if (!is_array($map_params) || count($map_params) == 0) {
            return $params;
        }
        foreach ($map_params as $key => $val) {
            if (substr($val, 0, 5) == "POST_") {
                $params[$key] = $post_params[substr($val, 5)];
            } elseif (substr($val, 0, 3) == "DB_") {
                $params[$key] = $db_params[0][substr($val, 3)];
            } else {
                $params[$key] = $val;
            }
        }
        return $params;
    }

    public function addSelectFields($fields = array())
    {
        for ($i = 0; $i < count($fields); $i++) {
            if (is_array($fields[$i])) {
                $escape = (isset($fields[$i]['escape']) && $fields[$i]['escape'] === TRUE) ? FALSE : NULL;
                $this->CI->db->select($fields[$i]['field'], $escape);
            } else {
                $this->CI->db->select($fields[$i]);
            }
        }
    }

    public function addWhereFields($fields = array(), $group = "AND")
    {
        for ($i = 0; $i < count($fields); $i++) {
            $field = $fields[$i]['field'];
            $data = isset($fields[$i]['value']) ? $fields[$i]['value'] : FALSE;
            $oper = (isset($fields[$i]['oper'])) ? $fields[$i]['oper'] : "eq";
            $escape = (isset($fields[$i]['escape']) && $fields[$i]['escape'] === TRUE) ? FALSE : NULL;
            if ($data === FALSE) {
                if ($group == 'OR') {
                    $this->CI->db->or_where($field, FALSE, FALSE);
                } else {
                    $this->CI->db->where($field, FALSE, FALSE);
                }
            } else {
                switch ($oper) {
                    case 'ne':
                        if ($group == 'OR') {
                            $this->CI->db->or_where($field . " <>", $data, $escape);
                        } else {
                            $this->CI->db->where($field . " <>", $data, $escape);
                        }
                        break;
                    case 'lt':
                        if ($group == 'OR') {
                            $this->CI->db->or_where($field . " <", $data, $escape);
                        } else {
                            $this->CI->db->where($field . " <", $data, $escape);
                        }
                        break;
                    case 'le':
                        if ($group == 'OR') {
                            $this->CI->db->or_where($field . " <=", $data, $escape);
                        } else {
                            $this->CI->db->where($field . " <=", $data, $escape);
                        }
                        break;
                    case 'gt':
                        if ($group == 'OR') {
                            $this->CI->db->or_where($field . " >", $data, $escape);
                        } else {
                            $this->CI->db->where($field . " >", $data, $escape);
                        }
                        break;
                    case 'ge':
                        if ($group == 'OR') {
                            $this->CI->db->or_where($field . " >=", $data, $escape);
                        } else {
                            $this->CI->db->where($field . " >=", $data, $escape);
                        }
                        break;
                    case 'bw':
                        if ($group == 'OR') {
                            $this->CI->db->or_like($field, $data, 'after', $escape);
                        } else {
                            $this->CI->db->like($field, $data, 'after', $escape);
                        }
                        break;
                    case 'bn':
                        if ($group == 'OR') {
                            $this->CI->db->or_not_like($field, $data, 'after', $escape);
                        } else {
                            $this->CI->db->not_like($field, $data, 'after', $escape);
                        }
                        break;
                    case 'ew':
                        if ($group == 'OR') {
                            $this->CI->db->or_like($field, $data, 'before', $escape);
                        } else {
                            $this->CI->db->like($field, $data, 'before', $escape);
                        }
                        break;
                    case 'en':
                        if ($group == 'OR') {
                            $this->CI->db->or_not_like($field, $data, 'before', $escape);
                        } else {
                            $this->CI->db->not_like($field, $data, 'before', $escape);
                        }
                        break;
                    case 'cn':
                        if ($group == 'OR') {
                            $this->CI->db->or_like($field, $data, 'both', $escape);
                        } else {
                            $this->CI->db->like($field, $data, 'both', $escape);
                        }
                        break;
                    case 'nc':
                        if ($group == 'OR') {
                            $this->CI->db->or_not_like($field, $data, 'both', $escape);
                        } else {
                            $this->CI->db->not_like($field, $data, 'both', $escape);
                        }
                        break;
                    case 'in':
                        $data = (is_array($data)) ? $data : explode(",", $data);
                        $data = (count($data) > 0) ? $data : array('');
                        if ($group == 'OR') {
                            $this->CI->db->or_where_in($field, $data, $escape);
                        } else {
                            $this->CI->db->where_in($field, $data, $escape);
                        }
                        break;
                    case 'ni':
                        $data = (is_array($data)) ? $data : explode(",", $data);
                        $data = (count($data) > 0) ? $data : array('');
                        if ($group == 'OR') {
                            $this->CI->db->or_where_not_in($field, $data, $escape);
                        } else {
                            $this->CI->db->where_not_in($field, $data, $escape);
                        }
                        break;
                    case "bt" :
                        $data_arr = array_filter(explode(" to ", $data));
                        if ($escape === NULL) {
                            $field = $this->CI->db->protect($field);
                        }
                        if ($fields[$i]['type'] == "date_and_time") {
                            $date_1 = date("Y-m-d H:i:s", strtotime($data_arr[0]));
                            $date_2 = date("Y-m-d H:i:s", strtotime($data_arr[1]));
                            $field = $this->CI->db->date_time_format($field);
                        } else {
                            $date_1 = date("Y-m-d", strtotime($data_arr[0]));
                            $date_2 = date("Y-m-d", strtotime($data_arr[1]));
                            $field = $this->CI->db->date_format($field);
                        }
                        if (is_array($data_arr) && count($data_arr) > 1) {
                            if ($group == 'OR') {
                                $this->CI->db->or_where($field . " BETWEEN " . $this->CI->db->escape($date_1) . " AND " . $this->CI->db->escape($date_2), FALSE, FALSE);
                            } else {
                                $this->CI->db->where($field . " BETWEEN " . $this->CI->db->escape($date_1) . " AND " . $this->CI->db->escape($date_2), FALSE, FALSE);
                            }
                        } else {
                            if ($group == 'OR') {
                                $this->CI->db->like($field, $date_1, 'both', $escape);
                            } else {
                                $this->CI->db->or_like($field, $date_1, 'both', $escape);
                            }
                        }
                        break;
                    case "nb" :
                        $data_arr = array_filter(explode(" to ", $data));
                        if ($escape === NULL) {
                            $field = $this->CI->db->protect($field);
                        }
                        if ($fields[$i]['type'] == "date_and_time") {
                            $date_1 = date("Y-m-d H:i:s", strtotime($data_arr[0]));
                            $date_2 = date("Y-m-d H:i:s", strtotime($data_arr[1]));
                            $field = $this->CI->db->date_time_format($field);
                        } else {
                            $date_1 = date("Y-m-d", strtotime($data_arr[0]));
                            $date_2 = date("Y-m-d", strtotime($data_arr[1]));
                            $field = $this->CI->db->date_format($field);
                        }
                        if (is_array($data_arr) && count($data_arr) > 1) {
                            if ($group == 'OR') {
                                $this->CI->db->or_where($field . " NOT BETWEEN " . $this->CI->db->escape($date_1) . " AND " . $this->CI->db->escape($date_2), FALSE, FALSE);
                            } else {
                                $this->CI->db->where($field . " NOT BETWEEN " . $this->CI->db->escape($date_1) . " AND " . $this->CI->db->escape($date_2), FALSE, FALSE);
                            }
                        } else {
                            if ($group == 'OR') {
                                $this->CI->db->or_not_like($field, $date_1, 'both', $escape);
                            } else {
                                $this->CI->db->not_like($field, $date_1, 'both', $escape);
                            }
                        }
                        break;
                    default:
                        if ($group == 'OR') {
                            $this->CI->db->or_where($field, $data, $escape);
                        } else {
                            $this->CI->db->where($field, $data, $escape);
                        }
                        break;
                }
            }
        }
    }

    public function addJoinTables($join_tables = array(), $type = 'AR', $in_tables = FALSE)
    {
        $ret_joins = array();
        $ret_joins_str = '';
        if (!is_array($join_tables) || count($join_tables) == 0) {
            if ($type == "NR") {
                return $ret_joins_str;
            } else {
                return;
            }
        }
        foreach ($join_tables as $key => $val) {
            $table_name = $val['table_name'];
            $table_alias = $val['table_alias'];
            $field_name = $val['field_name'];
            $rel_table_alias = $val['rel_table_alias'];
            $rel_field_name = $val['rel_field_name'];
            $join_type = $val['join_type'];
            $extra_condition = trim($val['extra_condition']);

            $table_name_pro = $this->CI->db->protect($table_name);
            $table_alias_pro = $this->CI->db->protect($table_alias);
            $field_name_pro = $this->CI->db->protect($field_name);
            $rel_table_alias_pro = $this->CI->db->protect($rel_table_alias);
            $rel_field_name_pro = $this->CI->db->protect($rel_field_name);

            if ($extra_condition != '') {
                $is_and_separator = substr($extra_condition, 0, 4);
                $is_or_separator = substr($extra_condition, 0, 3);
                if (strtoupper($is_or_separator) == "OR ") {
                    $extra_condition = ltrim($extra_condition, $is_or_separator);
                    $extra_operator = "OR";
                } else {
                    if (strtoupper($is_and_separator) == "AND ") {
                        $extra_condition = ltrim($extra_condition, $is_and_separator);
                    }
                    $extra_operator = "AND";
                }
                $extra_condition = ' ' . $extra_operator . ' ' . $extra_condition;
            }
            if ($type == "NR") {
                $join_condition = $table_alias_pro . "." . $field_name_pro . " = " . $rel_table_alias_pro . "." . $rel_field_name_pro . $extra_condition;
                $join_type = (in_array($join_type, array("left", "right"))) ? strtoupper($join_type) . " JOIN" : "INNER JOIN";
                if (is_array($in_tables)) {
                    if (in_array($table_alias, $in_tables)) {
                        $ret_joins[] = $join_type . ' ' . $table_name_pro . ' AS ' . $table_alias_pro . ' ON ' . $join_condition;
                    }
                } else {
                    $ret_joins[] = $join_type . ' ' . $table_name_pro . ' AS ' . $table_alias_pro . ' ON ' . $join_condition;
                }
            } elseif ($type == "AR") {
                $join_type = (in_array($join_type, array("left", "right"))) ? strtolower($join_type) : "inner";
                if ($extra_condition != "") {
                    $join_condition = $table_alias_pro . "." . $field_name_pro . " = " . $rel_table_alias_pro . "." . $rel_field_name_pro . $extra_condition;
                    $escape = TRUE;
                } else {
                    $join_condition = $table_alias . "." . $field_name . " = " . $rel_table_alias . "." . $rel_field_name;
                    $escape = NULL;
                }
                if (is_array($in_tables)) {
                    if (in_array($table_alias, $in_tables)) {
                        $this->CI->db->join($table_name . ' AS ' . $table_alias, $join_condition, $join_type, $escape);
                    }
                } else {
                    $this->CI->db->join($table_name . ' AS ' . $table_alias, $join_condition, $join_type, $escape);
                }
            }
        }
        if ($type == "NR") {
            $ret_joins_str = implode(" ", $ret_joins);
            return $ret_joins_str;
        }
    }

    public function addGridOrderBy($sidx = '', $sord = '', $config = array())
    {
        $sort_arr = array_filter(explode(",", $sidx));
        $order_arr = array_filter(explode(",", $sord));
        foreach ((array) $sort_arr as $key => $val) {
            $sort_field = $sort_arr[$key];
            $sort_order = (strtolower($order_arr[$key]) == "desc") ? "DESC" : "ASC";
            if (in_array($this->CI->db->dbdriver, array("sqlsrv", "mssql"))) {
                $sort_field = $config[$sort_field]['display_query'];
                if ($config['entry_type'] == "Custom") {
                    $this->CI->db->order_by($sort_field, $sort_order, TRUE);
                } else {
                    $this->CI->db->order_by($sort_field, $sort_order);
                }
            } else {
                $this->CI->db->order_by($sort_field, $sort_order);
            }
        }
    }
}

/* End of file Listing.php */
/* Location: ./application/libraries/Listing.php */