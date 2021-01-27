<?php
defined('BASEPATH') || exit('No direct script access allowed');

use Aws\S3\S3Client;
use Pushok\AuthProvider;
use Pushok\Client;
use Pushok\Notification;
use Pushok\Payload;
use Pushok\Payload\Alert;

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
Class General
{

    protected $CI;
    protected $_email_subject;
    protected $_email_content;
    protected $_email_params;
    protected $_push_content;
    protected $_notify_error;
    protected $_expression_eval;
    protected $_aws_avial_obj;
    protected $_aws_avail_buckets;
    protected $_hmvc_module_paths;

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function logOutChecking()
    {
        $ret = FALSE;
        if (!$this->CI->session->userdata("isLoggedIn")) {
            $ret = TRUE;
        } elseif ($this->CI->session->userdata("timeOut") > 0) {
            $diffTime = (time() - ($this->CI->session->userdata('loggedAt'))) / 60;
            if ($diffTime > ($this->CI->session->userdata('timeOut'))) {
                $ret = TRUE;
            }
        }
        if ($ret === TRUE) {
            $currArr['hashVal'] = $_REQUEST['hashValue'];
            $this->logInOutEntry($this->CI->session->userdata("iAdminId"), 'Admin', $currArr);
            $this->CI->session->sess_destroy();
        }
        return $ret;
    }

    public function logInOutEntry($id = '', $user_type = 'Admin', $extra_arr = array())
    {
        $this->CI->load->model('user/loghistory_model');
        $this->CI->load->model('user/admin_model');
        $data = $log = array();
        $data["dLastAccess"] = date("Y-m-d H:i:s");
        $res_admin = $this->CI->admin_model->update($data, $id);
        if ($this->CI->session->userdata("iLogId") != '') {
            $log['vCurrentUrl'] = $extra_arr['hashVal'];
            $log['dLogoutDate'] = date("Y-m-d H:i:s");
            $res_log = $this->CI->loghistory_model->update($log, $this->CI->session->userdata("iLogId"));
        } else {
            $log['iUserId'] = $id;
            $log['vIP'] = $this->getHTTPRealIPAddr();
            $log['eUserType'] = $user_type;
            $log['dLoginDate'] = date("Y-m-d H:i:s");
            $log_id = $this->CI->loghistory_model->insert($log);
            $this->CI->session->set_userdata("iLogId", $log_id);
        }
    }

    public function getSessionExpire()
    {
        if ($this->CI->session->userdata("isLoggedIn") && intval($this->CI->session->userdata('iSessionExpire')) > 0) {
            $logoff_time = intval($this->CI->session->userdata('iSessionExpire'));
        } else {
            $logoff_time = (intval($this->CI->config->item('AUTO_LOGOFF_TIME')) > 0) ? $this->CI->config->item('AUTO_LOGOFF_TIME') : 15;
        }
        return $logoff_time;
    }

    public function closedFancyFrame()
    {
        if ($_REQUEST['hideCtrl'] == "true") {
            return "true";
        } else {
            return "false";
        }
    }

    public function getcopyrighttext()
    {
        $copyrighttext = str_replace("#CURRENT_YEAR#", date('Y'), $this->CI->systemsettings->getSettings('COPYRIGHTED_TEXT'));
        $copyrighttext = str_replace("#COMPANY_NAME#", $this->CI->systemsettings->getSettings('COMPANY_NAME'), $copyrighttext);
        return $copyrighttext;
    }

    /** Generalized functions :: START */
    public function getRandomNumber($len = 15)
    {
        $better_token = strtoupper(md5(uniqid(rand(), TRUE)));
        $better_token = substr($better_token, 1, $len);
        return $better_token;
    }

    public function getRandomString($len = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen($characters);
        $random_string = '';
        for ($i = 0; $i < $len; $i++) {
            $random_string .= $characters[rand(0, $characters_length - 1)];
        }
        return $random_string;
    }

    public function truncateChars($str = '', $len = 25)
    {
        if (trim($str) == '' || $len < 3) {
            return $str;
        }
        if (strlen($str) > $len) {
            return substr($str, 0, ($len - 3)) . "...";
        } else {
            return $str;
        }
    }

    public function getHTTPRealIPAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['HTTP_FORWARDED'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_FORWARDED'];
        } else { // return remote address
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function getDateOnly($date = '')
    {
        if ($date != '0000-00-00' && $date != '0000-00-00 00:00:00' && trim($date) != '') {
            return date("M d, Y", strtotime($date));
        } else {
            return '---';
        }
    }

    public function getDayOnly($date = '')
    {
        if ($date != '0000-00-00' && $date != '0000-00-00 00:00:00' && trim($date) != '') {
            return date("l", strtotime($date));
        } else {
            return '---';
        }
    }

    public function getTimeOnly($date = '')
    {
        if ($date != '0000-00-00' && $date != '0000-00-00 00:00:00' && trim($date) != '') {
            return date("h:i A", strtotime($date));
        } else {
            return '---';
        }
    }

    public function getDateTime($date, $format, $top = FALSE)
    {
        if ($date != '0000-00-00' && $date != '0000-00-00 00:00:00' && trim($date) != '') {
            return ($top) ? date($format, $date) : date($format, strtotime($date));
        } else {
            return '---';
        }
    }

    public function dateSystemFormat($value = '')
    {
        $format = $this->getAdminPHPFormats("date");
        $sys_date_arr = _unsupported_date_formats();
        if (in_array($format, $sys_date_arr)) {
            return $this->dateCustomFormat($format, $value);
        } else {
            return $this->dateDefinedFormat($format, $value);
        }
    }

    public function dateTimeSystemFormat($value = '')
    {
        $format = $this->getAdminPHPFormats("date_and_time");
        $sys_datetime_arr = _unsupported_date_time_formats();
        if (in_array($format, $sys_datetime_arr)) {
            return $this->dateTimeCustomFormat($format, $value);
        } else {
            return $this->dateTimeDefinedFormat($format, $value);
        }
    }

    public function timeSystemFormat($value = '')
    {
        $format = $this->getAdminPHPFormats("time");
        return $this->timeDefinedFormat($format, $value);
    }

    public function dateDefinedFormat($format = '', $value = '')
    {
        if ($format == '' || trim($value) == '' || $value == "0000-00-00" || $value == "0000-00-00 00:00:00") {
            return '';
        }
        return date($format, strtotime($value));
    }

    public function dateTimeDefinedFormat($format = '', $value = '')
    {
        if ($format == '' || trim($value) == '' || $value == "0000-00-00" || $value == "0000-00-00 00:00:00") {
            return '';
        }
        return date($format, strtotime($value));
    }

    public function timeDefinedFormat($format = '', $value = '')
    {
        if ($format == '' || $value == '') {
            return '';
        }
        return date($format, strtotime($value));
    }

    public function dateCustomFormat($format = '', $value = '')
    {
        if ($format == '' || trim($value) == '' || $value == "0000-00-00" || $value == "0000-00-00 00:00:00") {
            return '';
        }
        $value = _render_client_custom_date($format, $value);
        return $value;
    }

    public function dateTimeCustomFormat($format = '', $value = '')
    {
        if ($format == '' || trim($value) == '' || $value == "0000-00-00" || $value == "0000-00-00 00:00:00") {
            return '';
        }
        $value = _render_client_custom_date_time($format, $value);
        return $value;
    }

    public function formatServerDate($format = '', $value = '')
    {
        if ($format == '' || trim($value) == "" || $value == "0000-00-00" || $value == "0000-00-00 00:00:00") {
            return $value;
        }
        $return_date = _render_server_custom_date($format, $value);
        return $return_date;
    }

    public function formatServerDateTime($format = '', $value = '')
    {
        if ($format == '' || trim($value) == "" || $value == "0000-00-00" || $value == "0000-00-00 00:00:00") {
            return $value;
        }
        $return_date = _render_server_custom_date_time($format, $value);
        return $return_date;
    }

    public function formatClientDate($format = '', $value = '')
    {
        $sys_date_arr = _unsupported_date_formats();
        if (in_array($format, $sys_date_arr)) {
            return $this->dateCustomFormat($format, $value);
        } else {
            return $this->dateDefinedFormat($format, $value);
        }
    }

    public function formatClientDateTime($format = '', $value = '')
    {
        $sys_datetime_arr = _unsupported_date_time_formats();
        if (in_array($format, $sys_datetime_arr)) {
            return $this->dateTimeCustomFormat($format, $value);
        } else {
            return $this->dateTimeDefinedFormat($format, $value);
        }
    }

    public function isExternalURL($url = '')
    {
        $flag = FALSE;
        if ($url != "") {
            $url = strtolower(trim($url));
            if (substr($url, 0, 8) == 'https://' || substr($url, 0, 7) == 'http://') {
                $flag = TRUE;
            }
        }
        return $flag;
    }

    public function isJson($string = '')
    {
        if (empty($string)) {
            return FALSE;
        }
        json_decode($string, TRUE);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function isAssoc($arr = array())
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function forbidden_message($err_message = '')
    {
        $render_arr['err_message'] = $err_message;
        echo $this->CI->parser->parse($this->CI->config->item('ADMIN_FORBIDDEN_TEMPLATE') . ".tpl", $render_arr, TRUE);
        exit;
    }

    public function from_camel_case($str = '')
    {
        $str = substr($str, 1);
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "_" . strtolower($c[1]);');
        return preg_replace_callback('/([A-Z])/', $func, $str);
    }

    public function to_camel_case($str = '')
    {
        $str = substr($str, 1);
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "" . strtoupper($c[1]);');
        return preg_replace_callback('/(_)/', $func, $str);
    }

    public function concat_string($str_1 = '', $str_2 = '')
    {
        return $str_1 . $str_2;
    }

    public function escape_str($str = '')
    {
        if (is_array($str) && count($str) > 0) {
            $arr = array();
            foreach ($str as $key => $val) {
                $arr[$key] = $this->CI->db->escape($val);
            }
            return $arr;
        } else {
            return $this->CI->db->escape($str);
        }
    }

    public function escape_like_str($str = '')
    {
        if (is_array($str) && count($str) > 0) {
            $arr = array();
            foreach ($str as $key => $val) {
                $arr[$key] = $this->CI->db->escape_like_str($str);
            }
            return $arr;
        } else {
            return $this->CI->db->escape_like_str($str);
        }
    }

    public function resize_image($url = '', $width = 100, $height = 100, $color = '')
    {
        $enc_image_url = base64_encode($url);
        $resize_image_url = $this->CI->config->item('site_url') . 'WS/image_resize/?pic=' . $enc_image_url . '&height=' . $height . '&width=' . $width . "&color=" . $color;
        return $resize_image_url;
    }

    public function arraySingle($data = array(), $ind = '')
    {
        $result = array();
        if (!is_array($data) || count($data) == 0) {
            return $result;
        }
        foreach ($data as $arr) {
            if (isset($arr[$ind])) {
                $result[] = $arr[$ind];
            }
        }
        return $result;
    }

    public function arrayCombo($data = array(), $key = '', $val = '')
    {
        $result = array();
        if (!is_array($data) || count($data) == 0) {
            return $result;
        }
        foreach ($data as $arr) {
            if (isset($arr[$key])) {
                $result[$arr[$key]] = $arr[$val];
            }
        }
        return $result;
    }

    public function arrayAssoc($data = array(), $ind = '')
    {
        $result = array();
        if (!is_array($data) || count($data) == 0) {
            return $result;
        }
        foreach ($data as $arr) {
            if (isset($arr[$ind])) {
                $result[$arr[$ind]][] = $arr;
            }
        }
        return $result;
    }

    /** Generalized functions :: END */
    public function navigationDateTime($value = '')
    {
        if (trim($value) == '' || $value == "0000-00-00" || $value == "0000-00-00 00:00:00") {
            return '---';
        }
        return date("M d, y h:i:s a", strtotime($value));
    }

    public function getDateTimeToDisplay($date = '')
    {
        $retval = "---";
        if (trim($date) != "" && trim($date) != "0000-00-00 00:00:00" && trim($date) != "0000-00-00") {
            $retval = date("d/m/Y h:i A", strtotime($date));
        }
        return $retval;
    }

    public function getDateToDisplay($date = '')
    {
        $retval = "---";
        if (trim($date) != "" && trim($date) != "0000-00-00 00:00:00" && trim($date) != "0000-00-00") {
            $retval = date("d/m/Y", strtotime($date));
        }
        return $retval;
    }

    public function getPhoneMaskedView($format = '', $value = '')
    {
        if ($value == '') {
            return '';
        }
        $format = ($format != "") ? $format : $this->CI->config->item("ADMIN_PHONE_FORMAT");
        $splitFormat = str_split(trim($format));
        $splitValue = str_split(trim($value));
        $retPhone = '';
        for ($i = 0, $j = 0; $i < count($splitFormat); $i++) {
            if (ctype_alnum($splitFormat[$i]) || $splitFormat[$i] == "*") {
                $retPhone .= $splitValue[$j];
                $j++;
            } else {
                $retPhone .= $splitFormat[$i];
            }
        }
        return $retPhone;
    }

    public function getPhoneUnmaskedView($format = '', $value = '')
    {
        if ($value == '') {
            return '';
        }
        $format = ($format != "") ? $format : $this->CI->config->item("ADMIN_PHONE_FORMAT");
        $splitFormat = str_split(trim($format));
        $splitValue = str_split(trim($value));
        $retPhone = '';
        for ($i = 0; $i < count($splitValue); $i++) {
            if (ctype_alnum($splitValue[$i])) {
                $retPhone .= $splitValue[$i];
            }
        }
        return $retPhone;
    }

    public function renderAdminURL($link = '', $module = '')
    {
        $external_url = $this->isExternalURL($link);
        $admin_url = $this->CI->config->item("admin_url");
        if ($external_url) {
            $return_link = $string;
        } else {
            $return_link = $admin_url . "#" . $this->getAdminEncodeURL($link, 0);
        }
        return $return_link;
    }

    public function getCurlResponse($data_url = "")
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_URL, $data_url);
        $data = curl_exec($ch);
        if (in_array(intval(curl_errno($ch)), array(1, 2, 3, 5, 6, 7, 8))) {
            $data = file_get_contents($data_url);
        }
        curl_close($ch);
        return $data;
    }

    public function get_image($params = array())
    {
        if (isset($params['pk']) && $params['pk'] != "") {
            $folder_name = $params['pk'];
        } else {
            $folder_name = "";
        }
        $file_name = $params['image_name'];
        $ext = $params['ext'];
        $no_img = isset($params['no_img']) ? $params['no_img'] : TRUE;
        $default_image = $final_image_url = '';
        if ($no_img) {
            $default_arr = $this->CI->config->item('IMAGE_EXTENSION_ARR');
            $extension_arr = explode(",", $ext);
            $intersect_arr = array_intersect($default_arr, $extension_arr);
            if (is_array($intersect_arr) && count($intersect_arr) > 0) {
                $default_image = $this->getNoImageURL();
            }
        }
        if ($file_name != "" && filter_var($file_name, FILTER_VALIDATE_URL)) {
            if (trim($params['height']) != "" || trim($params['width']) != "") {
                $final_image_url = $this->resize_image($file_name, $params['width'], $params['height'], $color);
            } else {
                $final_image_url = $file_name;
            }
        } else {
            $path = $params['path'];
            $color = $params['color'];
            if ($path != "") {
                $image_path = $this->CI->config->item('upload_path') . $path . DS;
                $image_url = $this->CI->config->item('upload_url') . $path . "/";
                if ($folder_name != "") {
                    $image_final_path = $image_path . $folder_name . DS;
                    $image_final_url = $image_url . $folder_name . "/";
                } else {
                    $image_final_path = $image_path;
                    $image_final_url = $image_url;
                }
                if (is_file($image_final_path . $file_name) && $file_name != "") {
                    if (trim($params['height']) != "" || trim($params['width']) != "") {
                        $final_image_url = $this->resize_image($image_final_url . $file_name, $params['width'], $params['height'], $color);
                    } else {
                        $final_image_url = $image_final_url . $file_name;
                    }
                    return $final_image_url;
                }
            }
            if ($default_image != '' && (trim($params['height']) != "" || trim($params['width']) != "")) {
                $final_image_url = $this->resize_image($default_image, $params['width'], $params['height'], $color);
            } else {
                $final_image_url = $default_image;
            }
        }
        return $final_image_url;
    }

    public function get_image_server($params = array())
    {
        $folder_arr = $this->getServerUploadPathURL($params['path']);
        $ext = $params['ext'];
        $no_img = isset($params['no_img']) ? $params['no_img'] : TRUE;
        $default_image = $final_image_url = '';
        if ($no_img) {
            $default_arr = $this->CI->config->item('IMAGE_EXTENSION_ARR');
            $extension_arr = explode(",", $ext);
            $intersect_arr = array_intersect($default_arr, $extension_arr);
            if (is_array($intersect_arr) && count($intersect_arr) > 0) {
                $default_image = $this->getNoImageURL();
            }
        }
        if ($folder_arr['status']) {
            $original_path = $folder_arr['folder_path'];
            $original_url = $folder_arr['folder_url'];
            $file_name = $params['image_name'];
            if (isset($params['pk']) && $params['pk'] != "") {
                $image_final_path = $original_path . $params['pk'] . DS;
            } else {
                $image_final_path = $original_path;
            }
            $source_url = $original_url . md5($image_final_path) . "/" . $file_name;
            $file_found = $this->checkFileExistsOnServer($source_url);
            $color = $params['color'];

            if ($file_found && $file_name != '') {
                if ((trim($params['height']) != "" || trim($params['width']) != "")) {
                    $final_image_url = $this->resize_image($source_url, $params['width'], $params['height'], $color);
                } else {
                    $final_image_url = $source_url;
                }
            } elseif ($default_image != "" && (trim($params['height']) != "" || trim($params['width']) != "")) {
                $final_image_url = $this->resize_image($default_image, $params['width'], $params['height'], $color);
            } else {
                $final_image_url = $default_image;
            }
        } else {
            if ($default_image != "" && (trim($params['height']) != "" || trim($params['width']) != "")) {
                $final_image_url = $this->resize_image($default_image, $params['width'], $params['height'], $color);
            } else {
                $final_image_url = $default_image;
            }
        }
        return $final_image_url;
    }

    public function get_image_aws($params = array())
    {
        $folder_arr = $this->getAWSServerUploadPathURL($params['path']);
        $ext = $params['ext'];
        $no_img = isset($params['no_img']) ? $params['no_img'] : TRUE;
        $default_image = $final_image_url = '';
        if ($no_img) {
            $default_arr = $this->CI->config->item('IMAGE_EXTENSION_ARR');
            $extension_arr = explode(",", $ext);
            $intersect_arr = array_intersect($default_arr, $extension_arr);
            if (is_array($intersect_arr) && count($intersect_arr) > 0) {
                $default_image = $this->getNoImageURL();
            }
        }
        if ($folder_arr['status']) {
            if ($params['pk'] != "") {
                $original_url = $folder_arr['folder_url'] . $params['pk'] . '/';
                $file_path = $params['path'] . $params['pk'] . '/';
                $bucket_folder = $folder_arr['bucket_folder'] . '/' . $params['pk'];
            } else {
                $original_url = $folder_arr['folder_url'];
                $file_path = $params['path'];
                $bucket_folder = $folder_arr['bucket_folder'];
            }
            $file_name = $params['image_name'];
            $source_url = $original_url . $file_name;
            $file_found = $this->checkFileExistsOnAWSObject($folder_arr['bucket_files'], $file_name, $folder_arr['bucket_name'], $bucket_folder);
            $color = $params['color'];

            if ($file_found && $file_name != '') {
                if ((trim($params['height']) != "" || trim($params['width']) != "")) {
                    $final_image_url = $this->resize_image($source_url, $params['width'], $params['height'], $color);
                } else {
                    $final_image_url = $source_url;
                }
            } elseif ($default_image != "" && (trim($params['height']) != "" || trim($params['width']) != "")) {
                $final_image_url = $this->resize_image($default_image, $params['width'], $params['height'], $color);
            } else {
                $final_image_url = $default_image;
            }
        } else {
            if ($default_image != "" && (trim($params['height']) != "" || trim($params['width']) != "")) {
                $final_image_url = $this->resize_image($default_image, $params['width'], $params['height'], $color);
            } else {
                $final_image_url = $default_image;
            }
        }
        return $final_image_url;
    }

    public function rotate_image($photo = '')
    {
        if (function_exists('exif_read_data')) {
            $exif = exif_read_data($photo);
            if (!empty($exif['Orientation'])) {
                $imageResource = imagecreatefromjpeg($photo); // provided that the image is jpeg. Use relevant function otherwise
                switch ($exif['Orientation']) {
                    case 3:
                        $image = imagerotate($imageResource, 180, 0);
                        break;
                    case 6:
                        $image = imagerotate($imageResource, -90, 0);
                        break;
                    case 8:
                        $image = imagerotate($imageResource, 90, 0);
                        break;
                    default:
                        $image = $imageResource;
                }
                imagejpeg($image, $photo);
                imagedestroy($imageResource);
                imagedestroy($image);
            }
        }
    }

    public function file_upload($photopath = '', $vphoto = '', $vphoto_name = '', $vaild_ext = '', $photo_size = '', $valid_size = '')
    {
        $vphotofile = '';
        try {
            if (empty($vphoto_name) || !is_file($vphoto)) {
                throw new Exception("Upload file path not found.");
            }
            if (trim($vaild_ext) != "") {
                if (!$this->validateFileFormat($vaild_ext, $vphoto_name)) {
                    throw new Exception("File extension is not valid. Vaild extensions are " . $vaild_ext . ".");
                }
            }
            if (trim($photo_size) != "" && trim($valid_size) != "") {
                if (!$this->validateFileSize($valid_size, $photo_size)) {
                    throw new Exception("File size is not valid. Maximum upload file size is " . $valid_size . " KB.");
                }
            }
            $vphotofile = $vphoto_name;
            if (!is_dir($photopath)) {
                $this->createFolder($photopath);
            }
            $ftppath = $photopath . $vphotofile;
            if (!copy($vphoto, $ftppath)) {
                throw new Exception("Uploading file(s) is failed.");
            }
            $this->rotate_image($ftppath);
            $msg = "File(s) uploaded successfully.";
        } catch (Exception $e) {
            $vphotofile = '';
            $message = $e->getMessage();
        }
        $ret = array();
        $ret[0] = $vphotofile;
        $ret[1] = $message;
        return $ret;
    }

    public function image_upload($photo = '', $path = '', $filename = '')
    {
        $photo_name_str = base64_decode($photo);
        if (trim($filename) == '') {
            $filename = 'base-image-' . date('YmdHis') . rand(100000, 999999) . '.jpg';
        }
        if (!is_dir($path)) {
            $this->createFolder($path);
        }
        try {
            $filename_path = $path . $prefix . $filename;
            if (!($handle = fopen($filename_path, 'w'))) {
                throw new Exception("Cannot create file " . $filename);
            }
            if (!fwrite($handle, $photo_name_str)) {
                throw new Exception("Cannot write to file " . $filename);
            }
            fclose($handle);
        } catch (Exception $e) {
            $filename = '';
            $message = $e->getMessage();
        }
        return $filename;
    }

    public function imageupload_base64($photopath = '', $photo = '', $filename = '')
    {
        $cam_pic = str_replace(" ", "+", $photo);
        $cam_image = str_replace("data:image/jpeg;base64,", "", $cam_pic);
        $cam_image = str_replace("data:image/png;base64,", "", $cam_image);
        $photo_name_str = base64_decode($cam_image);
        if ($filename == '') {
            $filename = 'webcam-image-' . date('YmdHis') . rand(100000, 999999) . '.png';
        }
        $filename_path = $photopath . $filename;
        try {
            if (!($handle = fopen($filename_path, 'w'))) {
                throw new Exception("Cannot create file " . $filename);
            }
            if (!fwrite($handle, $photo_name_str)) {
                throw new Exception("Cannot write to file " . $filename);
            }
            fclose($handle);
        } catch (Exception $e) {
            $filename = '';
            $message = $e->getMessage();
        }
        $result = array();
        $result[0] = $filename;
        $result[1] = $message;
        return $result;
    }

    public function get_file_attributes($vphoto_name = '')
    {
       if (trim($vphoto_name) == "") {
            $extension = "jpg";

            //$file_name = "base-image-" . date("YmdHis") . rand(100000, 999999) . "." . $extension;
            $file_name = "base-image-" . uniqid() . "." . $extension;
            return array($file_name, $extension);
        }
        $file_arr = explode(".", $vphoto_name);
        $temp_arr = array();
        for ($i = 0; $i < count($file_arr) - 1; $i++) {
            $temp_arr[] = $file_arr[$i];
        }
        $file = implode("_", $temp_arr);
        $file = str_replace(" ", "_", $file);
        $file = preg_replace('/[^A-Za-z0-9@.-_]/', '', $file);
        $extension = $file_arr[count($file_arr) - 1];
        $file_name = $file . "-" . uniqid() . "." . $extension;
        return array($file_name, $extension);
    }

    public function do_image_replacement($photo_val = '')
    {
        $value_pic = str_replace(" ", "+", $photo_val);
        $value_photo = str_replace("data:image/jpeg;base64,", "", $value_pic);
        $value_photo = str_replace("data:image/png;base64,", "", $value_photo);
        return $value_photo;
    }

    public function do_image_mime_operations($value_photo = '')
    {
        $f = finfo_open();
        $mime_type = finfo_buffer($f, base64_decode($value_photo), FILEINFO_MIME_TYPE);
        if (strstr($mime_type, "image/")) {
            $filename = "base-image-" . date("YmdHis") . rand(100000, 999999) . "." . str_replace("image/", "", $mime_type);
        } else {
            $filename = "";
        }
        return $filename;
    }

    public function getTablePrimaryKey($table_name = '')
    {
        if ($table_name != "") {
            $tbl_fields = $this->CI->db->field_data($table_name);
            if (is_array($tbl_fields) && count($tbl_fields) > 0) {
                foreach ((array) $tbl_fields as $field) {
                    if ($field->primary_key) {
                        $pkkey = $field->name;
                        break;
                    }
                }
            }
        }
        return $pkkey;
    }

    public function getAdminUploadPathURL($folder_name = '')
    {
        $upload_path = $this->CI->config->item('upload_path');
        $upload_url = $this->CI->config->item('upload_url');
        $folder_name = trim($folder_name);
        $folder_orgi = $this->getImageNestedFolders($folder_name);
        $ret_arr['status'] = FALSE;
        if ($folder_name == "") {
            $ret_arr['status'] = FALSE;
        } else {
            $folder_path = $upload_path . $folder_orgi;
            if (is_dir($folder_path)) {
                $original_path = $upload_path . $folder_orgi . DS;
                $original_url = $upload_url . $folder_name . "/";
                $ret_arr['status'] = TRUE;
                $ret_arr['folder_path'] = $original_path;
                $ret_arr['folder_url'] = $original_url;
            }
        }
        return $ret_arr;
    }

    public function getServerUploadPathURL($folder_name = '')
    {
        $custom_file_path = $this->CI->config->item('CIS_FILE_PATH');
        $folder_name = trim($folder_name);
        $ret_arr['status'] = FALSE;
        if ($folder_name == "") {
            $ret_arr['status'] = FALSE;
        } else {
            $original_path = $custom_file_path . $folder_name . '/';
            $original_url = $this->CI->config->item('CIS_BASE_URL');
            $ret_arr['status'] = TRUE;
            $ret_arr['folder_path'] = $original_path;
            $ret_arr['folder_url'] = $original_url;
        }
        return $ret_arr;
    }

    public function getAWSServerUploadPathURL($folder_name = '')
    {
        $folder_name = trim($folder_name);
        $ret_arr['status'] = FALSE;
        $this->_aws_avail_buckets = "";
        if ($folder_name == "") {
            $ret_arr['status'] = FALSE;
        } else {
            $bucket_name = $this->CI->config->item('AWS_BUCKET_NAME');
            if (is_array($this->_aws_avail_buckets) && array_key_exists($bucket_name, $this->_aws_avail_buckets)) {
                $available_bucket = $this->_aws_avail_buckets[$bucket_name];
            } else {
                if ($this->CI->config->item('AWS_CHECK_BUCKET_STATUS')) {
                    $available_bucket = $this->isAWSBucketAvailable($bucket_name, $folder_name);
                } else {
                    $available_bucket = array();
                }
            }
            if ($available_bucket !== FALSE) {
                $this->_aws_avail_buckets[$bucket_name] = $available_bucket;
                $ret_arr = $this->getAWSServerAccessPathURL($folder_name);
                $ret_arr['bucket_name'] = $bucket_name;
                $ret_arr['bucket_folder'] = $folder_name;
                $ret_arr['bucket_files'] = $available_bucket;
            }
        }
        return $ret_arr;
    }

    public function getAWSServerAccessPathURL($folder_name = '')
    {
        $bucket_name = $this->CI->config->item('AWS_BUCKET_NAME');
        $folder_name = trim($folder_name);
        $ret_arr['status'] = FALSE;
        if ($folder_name == "") {
            $ret_arr['status'] = FALSE;
        } else {
            $aws_cdn_enable = $this->CI->config->item('AWS_CDN_ENABLE');
            $aws_cdn_domain = $this->CI->config->item('AWS_CDN_DOMAIN');
            $original_path = "";
            $aws_protocal = (is_https()) ? 'https' : 'http';
            if ($aws_cdn_enable == 'Y') {
                $original_url = $aws_protocal . "://" . $aws_cdn_domain . "/" . $folder_name . "/";
            } else {
                $original_url = $aws_protocal . "://" . $bucket_name . ".s3.amazonaws.com/" . $folder_name . "/";
            }
            $ret_arr['status'] = TRUE;
            $ret_arr['folder_path'] = $original_path;
            $ret_arr['folder_url'] = $original_url;
        }
        return $ret_arr;
    }

    public function isAWSBucketAvailable($bucket_name = '', $folder_name = '')
    {
        $bucket_available = array();
        $folder_name = (trim($folder_name) != "") ? $folder_name . "/" : NULL;
        try {
            $s3 = $this->getAWSConnectionObject();
            if (version_compare(PHP_VERSION, '5.5', '>=')) {
                try {
                    $get_bucket_config = array(
                        'Bucket' => $bucket_name
                    );
                    $result = $s3->headBucket($get_bucket_config);
                    $list_objects_config = array(
                        'Bucket' => $bucket_name,
                        'Prefix' => $folder_name
                    );
                    $result = $s3->listObjects($list_objects_config);
                    if (!empty($result['Contents'])) {
                        $contents = $result['Contents'];
                        if (is_array($contents) && count($contents) > 0) {
                            foreach ($contents as $key => $val) {
                                $bucket_available[] = $val['Key'];
                            }
                        }
                    }
                } catch (\Aws\S3\Exception\S3Exception $e) {
                    if ($e->getAwsErrorCode() == "NotFound") {
                        try {
                            $AWS_END_POINT = $this->CI->config->item('AWS_END_POINT');
                            $AWS_SERVER_REGION = (trim($AWS_END_POINT)) ? trim($AWS_END_POINT) : "us-east-1";
                            $set_bucket_config = array(
                                'ACL' => 'public-read',
                                'Bucket' => $bucket_name,
                                'CreateBucketConfiguration' => array(
                                    'LocationConstraint' => $AWS_SERVER_REGION
                                )
                            );
                            $result = $s3->createBucket($set_bucket_config);
                        } catch (\Aws\S3\Exception\S3Exception $e) {
                            $bucket_available = FALSE;
                        } catch (Exception $e) {
                            
                        }
                    }
                } catch (Exception $e) {
                    
                }
            } else {
                $res = $s3->getBucket($bucket_name, $folder_name);
                if (!is_array($res)) {
                    $created = $s3->putBucket($bucket_name, S3::ACL_PUBLIC_READ, $this->CI->config->item('AWS_END_POINT'));
                    if (!$created) {
                        $bucket_available = FALSE;
                    }
                } else {
                    $bucket_available = array_keys($res);
                }
            }
        } catch (Exception $e) {
            
        }
        return $bucket_available;
    }

    public function checkFileExistsOnAWSServer($folder_name = '', $file_name = '')
    {
        $bucket_name = $this->CI->config->item('AWS_BUCKET_NAME');
        try {
            $s3 = $this->getAWSConnectionObject();
            if (version_compare(PHP_VERSION, '5.5', '>=')) {
                try {
                    $object_config = array(
                        'Bucket' => $bucket_name,
                        'Key' => $folder_name . "/" . $file_name
                    );
                    //$result = $s3->getObject($object_config);
                    $res = $s3->headObject($object_config);
                } catch (\Aws\S3\Exception\S3Exception $e) {
                    $res = FALSE;
                } catch (Exception $e) {
                    
                }
            } else {
                $object_fodler = $bucket_name . "/" . $folder_name;
                $res = $s3->getObjectInfo($object_fodler, $file_name);
            }
        } catch (Exception $e) {
            
        }
        return $res;
    }

    public function getFileFromAWS($bucket_name='', $folder_name = '', $file_name = '')
    {
        $bucket_name = $this->CI->config->item('AWS_BUCKET_NAME');
        try {
            $s3 = $this->getAWSConnectionObject();
            if (version_compare(PHP_VERSION, '5.5', '>=')) {
                try {
                    $object_config = array(
                        'Bucket' => $bucket_name,
                        'Key' => $folder_name . "/" . $file_name
                    );
                    $res = $s3->getObject($object_config);
                    //$res = $s3->headObject($object_config);
                } catch (\Aws\S3\Exception\S3Exception $e) {
                    $res = FALSE;
                } catch (Exception $e) {
                    
                }
            } else {
                $object_fodler = $bucket_name . "/" . $folder_name;
                $res = $s3->getObjectInfo($object_fodler, $file_name);
            }
        } catch (Exception $e) {
            
        }
        return $res;
    }

    public function checkFileExistsOnAWSObject($object_arr = array(), $file_name = '', $bucket_name = '', $folder_name = '')
    {
        if (!$this->CI->config->item('AWS_CHECK_BUCKET_STATUS')) {
            return TRUE;
        }
        $res = FALSE;
        if (!is_array($object_arr) || count($object_arr) == 0 || $folder_name == '') {
            return $res;
        }
        $final_file = $folder_name . "/" . $file_name;
        if (in_array($final_file, $object_arr)) {
            return TRUE;
        }
        return $res;
    }

    public function uploadAWSData($temp_file = '', $folder_name = '', $file_name = '')
    {
        $folder_name = rtrim(trim($folder_name), "/");
        $bucket_name = $this->CI->config->item('AWS_BUCKET_NAME');
        try {
            $response = FALSE;
            if (trim($file_name) == "" || trim($bucket_name) == "" || trim($folder_name) == "") {
                return $response;
            }
            $s3 = $this->getAWSConnectionObject();
            if (version_compare(PHP_VERSION, '5.5', '>=')) {
                try {
                    $arrExp = explode('.', $file_name);
                    $ext = strtolower(end($arrExp));
                    if($ext == 'jpg' || $ext == 'jpeg'){
                        $content_type = "image/jpeg";
                    }
                    if($ext == 'png'){
                         $content_type = "image/png";
                    }
                    if($ext == 'log'){
                         $content_type = "text/plain";
                    }
                    if($ext == 'zip'){
                         $content_type = "application/zip";
                    }
                    if($ext == 'mp3'){
                         $content_type = "audio/mpeg";
                    }
                    if($ext == 'mp4'){
                         $content_type = "audio/mp4";
                    }
                    if($ext == 'wav'){
                         $content_type = "audio/wav";
                    }
                    if($ext == 'webm'){
                         $content_type = "audio/webm";
                    }
                    if($ext == 'pdf'){
                         $content_type = "application/pdf";
                    }
                    $object_config = array(
                        'ACL' => 'public-read',
                        'Bucket' => $bucket_name,
                        'Key' => $folder_name . '/' . $file_name,
                        'SourceFile' => $temp_file,
                        'ContentType'=>$content_type
                    );
                    //prepare the API log data => start
                    $log_data = array();
                    $log_data['accessed_time'] = microtime();
                    $log_data['request_func']  = "S3 Fileupload";
                    $log_data['request_url']   = "S3 Fileupload";
                    $object_config_log = $object_config;
                    $object_config_log['user_id'] = $this->session->userdata['iUserId'];
                    $log_data['input_params']  = $object_config_log;
                    $response = $s3->putObject($object_config);
                    //prepare the API log data => continue
                    
                    $log_data['executed_time']   = microtime();
                    $log_data['output_response'] = (array)$response;
                   
                    $this->insertApiLogger($log_data);
                    //prepare the API log data => end
                } catch (\Aws\S3\Exception\S3Exception $e) {
                    log_message('error', $e->getMessage());
                    //prepare the API log data => continue
                    $log_data['executed_time']   = microtime();
                    $log_data['output_response'] = $e->getMessage();
                    
                    $this->insertApiLogger($log_data);
                    //prepare the API log data => end               
                    
                } catch (Exception $e) {
                    log_message('error', $e->getMessage());                   
                }
            } else {
                $object_folder = $bucket_name . "/" . $folder_name;
                $response = $s3->putObjectFile($temp_file, $object_folder, $file_name, S3::ACL_PUBLIC_READ);
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            
        }
        return $response;
    }

    public function deleteAWSFileData($folder_name = '', $file_name = '')
    {
        $folder_name = rtrim(trim($folder_name), "/");
        $bucket_name = $this->CI->config->item('AWS_BUCKET_NAME');
        try {
            $response = FALSE;
            if (trim($file_name) == "" || trim($bucket_name) == "" || trim($folder_name) == "") {
                return $response;
            }
            $s3 = $this->getAWSConnectionObject();
            if (version_compare(PHP_VERSION, '5.5', '>=')) {
                try {
                    $object_config = array(
                        'Bucket' => $bucket_name,
                        'Key' => $folder_name . '/' . $file_name
                    );
                    $response = $s3->deleteObject($object_config);
                } catch (\Aws\S3\Exception\S3Exception $e) {
                    
                } catch (Exception $e) {
                    
                }
            } else {
                $object_fodler = $bucket_name . "/" . $folder_name;
                $response = $s3->deleteObject($object_fodler, $file_name);
            }
        } catch (Exception $e) {
            
        }
        return $response;
    }

    public function getAWSConnectionObject()
    {
        if (is_object($this->_aws_avial_obj)) {
            return $this->_aws_avial_obj;
        }
        $AWS_ACCESSKEY = $this->CI->config->item('AWS_ACCESSKEY');
        $AWS_SECRECTKEY = $this->CI->config->item('AWS_SECRECTKEY');
        $AWS_SSL_VERIFY = ($this->CI->config->item('AWS_SSL_VERIFY') == "Yes") ? TRUE : FALSE;
        $AWS_END_POINT = $this->CI->config->item('AWS_END_POINT');
        try {
            if (version_compare(PHP_VERSION, '5.5', '>=')) {
                $AWS_SERVER_REGION = (trim($AWS_END_POINT)) ? trim($AWS_END_POINT) : "us-east-1";
                require_once ($this->CI->config->item('third_party') . "aws_s3/vendor/autoload.php");
                $aws_config = array(
                    'version' => 'latest',
                    'region' => $AWS_SERVER_REGION,
                    'scheme' => ($AWS_SSL_VERIFY) ? 'https' : 'http',
                    'credentials' => array(
                        'key' => $AWS_ACCESSKEY,
                        'secret' => $AWS_SECRECTKEY
                    )
                );
                $this->_aws_avial_obj = new S3Client($aws_config);
            } else {
                $AWS_SERVER_REGION = (trim($AWS_END_POINT)) ? "s3-" . trim($AWS_END_POINT) . ".amazonaws.com" : FALSE;
                require_once ($this->CI->config->item('third_party') . "aws_s3/S3.php");
                if ($AWS_SERVER_REGION) {
                    $this->_aws_avial_obj = new S3($AWS_ACCESSKEY, $AWS_SECRECTKEY, $AWS_SSL_VERIFY, $AWS_SERVER_REGION);
                } else {
                    $this->_aws_avial_obj = new S3($AWS_ACCESSKEY, $AWS_SECRECTKEY, $AWS_SSL_VERIFY);
                }
            }
        } catch (Exception $e) {
            
        }
        return $this->_aws_avial_obj;
    }

    public function checkFileExistsOnServer($server_url = '')
    {
        $return_val = FALSE;
        $ch = curl_init($server_url);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE);
        curl_exec($ch);
        $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($retcode == 200) {
            $return_val = TRUE;
        }
        return $return_val;
    }

    public function uploadServerData($path = "public/", $file_src = '', $file_org = '')
    {
        $post_url = $this->CI->config->item('CIS_POST_URL');
        $auth_code = $this->CI->config->item('CIS_AUTH_CODE');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_URL, $post_url);
        $post_array = array(
            "folder_name" => $path,
            "folder_md5" => md5($path),
            "file_id" => 'sample',
            "file_name" => $file_org,
            "file_md5" => md5_file($file_src),
            "file_sha1" => sha1_file($file_src)
        );
        $auth = hash_hmac('md5', http_build_query($post_array), $auth_code);
        if ((version_compare(PHP_VERSION, '5.5') >= 0)) {
            $post_array['file'] = new CURLFile($file_src);
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, TRUE);
        } else {
            $post_array['file'] = "@" . $file_src;
        }
        $post_array['auth'] = $auth;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function createFolder($path = '')
    {
        $root_path = $this->CI->config->item('base_path');
        $pathfolder = explode(DS, str_replace($root_path, "", $path));
        $realpath = "";
        for ($p = 0; $p < count($pathfolder); $p++) {
            if ($pathfolder[$p] != '') {
                $realpath = $realpath . $pathfolder[$p] . DS;
                $makefolder = $root_path . DS . $realpath;
                if (!is_dir($makefolder)) {
                    $old_umask = umask(0);
                    mkdir($makefolder, 0777);
                    chmod($makefolder, 0777);
                    chown($makefolder, get_current_user());
                    umask($old_umask);
                }
            }
        }
        return $makefolder;
    }

    public function createPermission($file_name = '')
    {
        if (is_file($file_name)) {
            chmod($file_name, 0777);
            chown($file_name, get_current_user());
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function createUploadFolderIfNotExists($folder_name = '')
    {
        if ($folder_name == "") {
            return FALSE;
        }
        $upload_folder = $this->CI->config->item('upload_path') . $folder_name . DS;
        $this->createFolder($upload_folder);
        return TRUE;
    }

    public function uploadFilesOnSaveForm($file_arr = array(), $id = '')
    {
        if (!is_array($file_arr) || count($file_arr) == 0) {
            return;
        }
        foreach ($file_arr as $key => $val) {
            $file_name = $val['file_name'];
            $folder_name = $val['folder_name'];
            $id_wise = $val['id_wise'];
            $old_file = $val['old_file'];
            $temp_file_path = $this->CI->config->item('admin_upload_temp_path') . $file_name;
            if ($id_wise == 'Yes' && $id != '') {
                $this->createUploadFolderIfNotExists($folder_name . DS . $id);
                $dest_file_path = $this->CI->config->item('upload_path') . $folder_name . DS . $id . DS . $file_name;
                $old_file_path = $this->CI->config->item('upload_path') . $folder_name . DS . $id . DS . $old_file;
            } else {
                $this->createUploadFolderIfNotExists($folder_name);
                $dest_file_path = $this->CI->config->item('upload_path') . $folder_name . DS . $file_name;
                $old_file_path = $this->CI->config->item('upload_path') . $folder_name . DS . $old_file;
            }
            if (is_file($temp_file_path)) {
                if (copy($temp_file_path, $dest_file_path)) {
                    unlink($temp_file_path);
                    if (is_file($old_file_path) && $old_file != '') {
                        unlink($old_file_path);
                    }
                }
            }
        }
    }

    public function getImageNestedFolders($folder_name = '')
    {
        if (strpos($folder_name, '/') !== FALSE) {
            $folder_name_arr = explode("/", $folder_name);
            $folder_name = implode(DS, $folder_name_arr);
        }
        return $folder_name;
    }

    public function languageTranslation($src, $dest, $text, $type = 'plain')
    {
        $this->CI->load->library('translate');
        $dest = strtolower($dest);
        $src = strtolower($src);
        $options = array();
        if (!empty($this->CI->config->item('SYSTEM_LANG_APP_KEY'))) {
            $options['google']['key'] = $this->CI->config->item('SYSTEM_LANG_APP_KEY');
        }
        if (!empty($this->CI->config->item('SYSTEM_LANG_APP_ID'))) {
            $options['azure']['url'] = "https://api.cognitive.microsofttranslator.com/translate";
            $options['azure']['key'] = $this->CI->config->item('SYSTEM_LANG_APP_ID');
            $options['azure']['version'] = "3.0";
        }
        $trans = '';
        if (!empty($options)) {
            $this->CI->translate->setOptions($options);
            $trans = $this->CI->translate->tranlsate($src, $dest, $text, $type);
        }
        return $trans;
    }

    public function formatLangugeLabel($labels = array(), $type = '')
    {
        $format = array();
        foreach ($labels as $key => $val) {
            $val = trim($val);
            $string = $val;
            if ($type == "html") {
                $status = 1;
                $string = $this->skipLabelTranslation($string);
            } else {
                if (substr($string, 0, 1) == "#" && substr($string, -1) == "#") {
                    $status = 0;
                } else {
                    $string = trim(preg_replace('!\s+!', ' ', $string));
                    if (empty($string) || is_numeric($string)) {
                        $status = 0;
                        $string = $val;
                    } else {
                        $status = 1;
                        $string = $this->skipLabelTranslation($string);
                    }
                }
            }
            $format[$key]['status'] = $status;
            $format[$key]['string'] = $string;
        }
        return $format;
    }

    public function skipLabelTranslation($string = '')
    {
        if (trim($string) != '') {
            preg_match_all("/#[A-Za-z_0-9]+#/si", $string, $matches);
            if (is_array($matches[0]) && count($matches[0]) > 0) {
                foreach ($matches[0] as $key => $val) {
                    $new_val = '<span class="notranslate">' . $val . '</span>';
                    $string = str_replace($val, $new_val, $string);
                }
            }
            $replace_arr = array(
                "%s" => '<span class="notranslate">%s</span>',
                "%d" => '<span class="notranslate">%d</span>',
                "%f" => '<span class="notranslate">%f</span>',
            );
            $string = str_replace(array_keys($replace_arr), array_values($replace_arr), $string);
        }
        return $string;
    }

    public function processLangugeLabel($txt = '', $type = '')
    {
        if (trim($txt) != '') {
            if ($type == "html") {
                preg_match_all('/(?<=<span class="notranslate">)(.*?)(?=<\/span>)/', $txt, $matches);
                if (is_array($matches[0]) && count($matches[0]) > 0) {
                    foreach ($matches[0] as $val) {
                        $txt = str_replace('<span class="notranslate">' . $val . '</span>', $val, $txt);
                    }
                }
            } else {
                preg_match_all("/#[A-Za-z_]+\s#/si", $txt, $matches);
                if (is_array($matches[0]) && count($matches[0]) > 0) {
                    foreach ($matches[0] as $val) {
                        $str = str_replace(" #", "#", $val);
                        $txt = str_replace($val, $str, $txt);
                    }
                }
                $txt = strip_tags($txt);
            }
        }
        return $txt;
    }

    public function pushTestNotification($device_id = '', $notify_arr = array(), $device_type = '')
    {
        if (empty($device_id)) {
            return FALSE;
        }
        if ($device_type == "android" || strlen($device_id) > 70) {
            $success = $this->androidNotification($device_id, $notify_arr['message'], $notify_arr);
        } else {
            $success = $this->iOSNotification($device_id, $notify_arr);
        }
        return $success;
    }

    public function iOSNotification($device_id = '', $notify_arr = array())
    {
        try {
            if (empty($device_id)) {
                throw new Exception("Device token not found..!");
            }
            $cache_temp_path = $this->CI->config->item('admin_upload_cache_path');
            $upload_settings_path = $this->CI->config->item('settings_files_path');
            $protocal = $this->CI->config->item('PUSH_NOTIFY_IOS_PROTOCAL');
            //hb-1153
            $push_notify_sending_mode=$this->CI->config->item('PUSH_NOTIFY_SENDING_MODE');
            if($push_notify_sending_mode == "live"){
                $upload_pem_file = $this->CI->config->item('PUSH_NOTIFY_PEM_FILE_LIVE');
            }else{
                $upload_pem_file = $this->CI->config->item('PUSH_NOTIFY_PEM_FILE');
            }
            //hb-1153
            
            $pem_file = $upload_settings_path . $upload_pem_file;
            $badge = 0;
            $sound = 'received.caf';
            if ($protocal == "http2") {
                require_once ($this->CI->config->item('third_party') . "pushok/vendor/autoload.php");
                if ($upload_pem_file == "" || !is_file($upload_settings_path . $upload_pem_file)) {
                    $pem_file = $this->CI->config->item('site_path') . 'apns-dev.p8';
                }
                if (!is_file($pem_file)) {
                    throw new Exception("Certificates(.p8) file not found..!");
                }
                if ($this->CI->config->item('PUSH_NOTIFY_SENDING_MODE') == "sandbox") {
                    $production = FALSE;
                } else {
                    $production = TRUE;
                }
                $options = array(
                    'key_id' => $this->CI->config->item('PUSH_NOTIFY_IOS_KEY_ID'), // The Key ID obtained from Apple developer account
                    'team_id' => $this->CI->config->item('PUSH_NOTIFY_IOS_TEAM_ID'), // The Team ID obtained from Apple developer account
                    'app_bundle_id' => $this->CI->config->item('PUSH_NOTIFY_IOS_BUNDLE_ID'), // The bundle ID for app obtained from Apple developer account
                    'private_key_path' => $pem_file, // Path to private key
                    'private_key_secret' => $this->CI->config->item('PUSH_NOTIFY_IOS_KEY') // Private key secret
                );

                $authProvider = AuthProvider\Token::create($options);
                $alert = Alert::create()->setActionLocKey($notify_arr['title']);
                //$alert = $alert->setTitle($notify_arr['title']);
                $alert = $alert->setBody($notify_arr['message']);
                $payload = Payload::create()->setAlert($alert);

                if ($notify_arr['sound'] != "") {
                    $payload = $payload->setSound($notify_arr['sound']);
                } else {
                    //set notification sound to default
                    $payload = $payload->setSound($sound);
                }
                if ($notify_arr['badge'] != "") {
                    $payload = $payload->setBadge(intval($notify_arr['badge']));
                } else {
                    //set notification badge to 0
                    $payload = $payload->setBadge($badge);
                }
                if ($notify_arr['silent'] == "1") {
                    $payload = $payload->setContentAvailability(1);
                }
                //add custom value to your notification, needs to be customized
                if ($notify_arr['code']) {
                    $payload = $payload->setCustomValue('code', $notify_arr['code']);
                }
                if ($notify_arr['id']) {
                    $payload = $payload->setCustomValue('id', $notify_arr['id']);
                }
                if (is_array($notify_arr['others']) && count($notify_arr['others']) > 0) {
                    foreach ($notify_arr['others'] as $key => $val) {
                        $payload = $payload->setCustomValue($key, $val);
                    }
                }

                if (is_array($device_id)) {
                    $deviceToken = $device_id;
                } elseif (strstr($device_id, ",")) {
                    $deviceToken = explode(",", $device_id);
                } else {
                    $deviceToken = array($device_id);
                }

                $notifications = array();
                foreach ($deviceToken as $token) {
                    $notifications[] = new Notification($payload, $token);
                }
                $client = new Client($authProvider, $production);
                $client->addNotifications($notifications);
                $responses = $client->push(); // returns an array of ApnsResponseInterface (one Response per Notification)

                foreach ($responses as $response) {
                    //$response->getApnsId();
                    if ($response->getStatusCode() != 200) {
                        throw new Exception($response->getReasonPhrase());
                    }
                    //$response->getErrorReason();
                    //$response->getErrorDescription();
                }
            } else {
                $max_size = 2020;
                if ($upload_pem_file == "" || !is_file($upload_settings_path . $upload_pem_file)) {
                    $pem_file = $this->CI->config->item('site_path') . 'apns-dev.pem';
                }
                if (!is_file($pem_file)) {
                    throw new Exception("Certificates(.pem) file not found..!");
                }

                $data = array();
                if ($notify_arr['title']) {
                    $data['aps']['alert']['action-loc-key'] = $notify_arr['title'];
                    $data['aps']['alert']['title'] = $notify_arr['title'];
                }
                $data['aps']['badge'] = ($notify_arr['badge'] != "") ? intval($notify_arr['badge']) : intval($badge);
                $data['aps']['sound'] = ($notify_arr['sound'] != "") ? $notify_arr['sound'] : $sound;
                if ($notify_arr['silent'] == "1") {
                    $data['aps']['content-available'] = 1;
                }
                if ($notify_arr['code']) {
                    $data['code'] = $notify_arr['code'];
                }
                if ($notify_arr['id']) {
                    $data['id'] = $notify_arr['id'];
                }
                if (is_array($notify_arr['others']) && count($notify_arr['others']) > 0) {
                    $data = array_merge($data, $notify_arr['others']);
                }
                $data = $this->array_map_recursive("trim", $data);
                $data['aps']['badge'] = intval($data['aps']['badge']);
                $temp_src = json_encode($data);
                $allow_size = $max_size - (strlen($temp_src));

                if ($allow_size > 0) {
                    $body = htmlentities(trim($notify_arr['message']));
                    $body = str_replace(array("\n", "\r"), " ", $body);
                    if (strlen($body) > $allow_size) {
                        $body = substr($body, 0, ($allow_size - 3)) . "...";
                    }
                } else {
                    $body = '...';
                }
                $data['aps']['alert']['body'] = html_entity_decode($body);
                $payload = json_encode($data);
                $this->_push_content = $payload;

                if (strlen($payload) > 2048) {
                    throw new Exception("Payload length exceeds maximum characters(255)..!");
                }

                $ctx = stream_context_create();
                stream_context_set_option($ctx, 'ssl', 'local_cert', $pem_file);
                if ($this->CI->config->item('PUSH_NOTIFY_SENDING_MODE') == "sandbox") {
                    $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
                } else {
                    $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
                }
                if (!$fp) {
                    throw new Exception("Faliure while stream socket connection..!");
                }
                if (is_array($device_id)) {
                    $deviceToken = $device_id;
                } elseif (strstr($device_id, ",")) {
                    $deviceToken = explode(",", $device_id);
                } else {
                    $deviceToken = array($device_id);
                }
                foreach ($deviceToken as $token) {
                    $msg = chr(0) . pack("n", 32) . pack('H*', str_replace(' ', '', $token)) . pack("n", strlen($payload)) . $payload;
                    fwrite($fp, $msg);
                }
                fclose($fp);
            }
            $success = 1;
            $message = "Push notification send successfully..!";
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
            $this->_notify_error = $message;
        }
        if ($_ENV['debug_action']) {
            $f = fopen($cache_temp_path . 'ios_notification.html', 'a+');
            if ($f) {
                fwrite($f, '<br/>');
                fwrite($f, 'Date : ' . date('Y-m-d H:i:s') . '<br/>');
                fwrite($f, print_r("Device Token : " . $device_id, TRUE) . '<br/>');
                fwrite($f, print_r("Payload : " . $this->_push_content, TRUE) . '<br/>');
                fwrite($f, print_r("Status : " . $success, TRUE) . '<br/>');
                fwrite($f, print_r("Message : " . $message, TRUE) . '<br/>');
                fclose($f);
            }
        }
        return $success;
    }

    public function androidNotification($device_id = '', $message = '', $extra = array())
    {
        $result = '';
        try {
            if (empty($device_id)) {
                throw new Exception("Device token not found..!");
            }
            $cache_temp_path = $this->CI->config->item('admin_upload_cache_path');
            $apiType = $this->CI->config->item('PUSH_NOTIFY_ANDROID_TYPE');
            $apiKey = $this->CI->config->item('PUSH_NOTIFY_ANDROID_KEY');
            if (empty($apiKey)) {
                throw new Exception("Push notification authorization key not found.");
            }
            if ($apiType == "fcm") {
                // Set POST variables
                $url = 'https://fcm.googleapis.com/fcm/send';
                // Replace with real client registration IDs
                if (is_array($device_id)) {
                    $registrationIDs = $device_id;
                } elseif (strstr($device_id, ",")) {
                    $registrationIDs = explode(",", $device_id);
                } else {
                    $registrationIDs = array($device_id);
                }

                $message_arr = array("message" => $message);
                $data = array_merge($message_arr, $extra);

                $fields = array(
                    'registration_ids' => $registrationIDs,
                    'data' => $data
                );
//                $fields = array(
//                    'registration_ids' => $registrationIDs,
//                    'notification' => array(
//                        "body" => $message
//                    ),
//                    'priority' => 10
//                );
//                if (!empty($extra)) {
//                    $fields['data'] = $extra;
//                }
                $headers = array(
                    'Authorization: key=' . $apiKey,
                    'Content-Type: application/json'
                );
                $this->_push_content = json_encode($fields);
            } else {
                // Set POST variables
                $url = 'https://android.googleapis.com/gcm/send';
                // Replace with real client registration IDs
                if (is_array($device_id)) {
                    $registrationIDs = $device_id;
                } elseif (strstr($device_id, ",")) {
                    $registrationIDs = explode(",", $device_id);
                } else {
                    $registrationIDs = array($device_id);
                }

                $message_arr = array("message" => $message);
                $data = array_merge($message_arr, $extra);

                $fields = array(
                    'registration_ids' => $registrationIDs,
                    'data' => $data,
                );
                $headers = array(
                    'Authorization: key=' . $apiKey,
                    'Content-Type: application/json'
                );
                $this->_push_content = json_encode($fields);
            }

            if (!function_exists('curl_version')) {
                throw new Exception("CURL is not installed in this server..!");
            }

            $ch = curl_init();
            if (!$ch) {
                throw new Exception("CURL intialization fails..!");
            }
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);

            if (!$result) {
                $error = curl_error($ch) || "CURL execution fails..!";
                throw new Exception($error);
            }

            $success = 1;
            $message = "Push notification send successfully..!";
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
            $this->_notify_error = $message;
        }
        if ($_ENV['debug_action']) {
            $f = fopen($cache_temp_path . 'android_notification.html', 'a+');
            if ($f) {
                fwrite($f, '<br/>');
                fwrite($f, 'Date : ' . date('Y-m-d H:i:s') . '<br/>');
                fwrite($f, print_r("Device Token : " . json_encode($registrationIDs), TRUE) . '<br/>');
                fwrite($f, print_r("Payload : " . $this->_push_content, TRUE) . '<br/>');
                fwrite($f, print_r("Response : " . $result, TRUE) . '<br/>');
                fwrite($f, print_r("Status : " . $success, TRUE) . '<br/>');
                fwrite($f, print_r("Message : " . $message, TRUE) . '<br/>');
                fclose($f);
            }
        }
        return $success;
    }

    public function insertPushNotification($push_arr = array())
    {
        $unique_id = $this->getPushNotifyUnique();
        if (isset($push_arr['send_mode'])) {
            if ($push_arr['send_mode'] == 'runtime') {
                $send_type = "runtime";
            } elseif ($push_arr['send_mode'] == "cron") {
                $send_type = "cron";
            } else {
                $send_type = $this->CI->config->item('PUSH_NOTIFY_SENDING_TYPE');
            }
        } else {
            $send_type = $this->CI->config->item('PUSH_NOTIFY_SENDING_TYPE');
        }
        if ($send_type == "runtime") {
            $notify_arr = array();
            $notify_arr['message'] = $push_arr['message'];
            $notify_arr['title'] = $push_arr['title'];
            $notify_arr['badge'] = intval($push_arr['badge']);
            $notify_arr['sound'] = $push_arr['sound'];
            $notify_arr['code'] = $push_arr['code'];
            $notify_arr['id'] = $unique_id;
            $vars_arr = $push_arr['variables'];
            if (!is_array($vars_arr) && is_string($vars_arr)) {
                $vars_arr = json_decode($vars_arr, TRUE);
            }
            if (is_array($vars_arr) && count($vars_arr) > 0) {
                foreach ($vars_arr as $vk => $vv) {
                    if ($vv['key'] != "" && $vv['send'] == "Yes") {
                        $notify_arr['others'][$vv['key']] = $vv['value'];
                    }
                }
            }
            $success = $this->pushTestNotification($push_arr['device_id'], $notify_arr);
        }
        $this->CI->load->model('tools/push');
        $insert_arr = array();
        $insert_arr['vUniqueId'] = $unique_id;
        $insert_arr['vDeviceId'] = $push_arr['device_id'];
        $insert_arr['eMode'] = $this->CI->config->item('PUSH_NOTIFY_SENDING_MODE');
        $insert_arr['eNotifyCode'] = $push_arr['code'];
        $insert_arr['vSound'] = $push_arr['sound'];
        $insert_arr['vBadge'] = $push_arr['badge'];
        $insert_arr['vTitle'] = $push_arr['title'];
        $insert_arr['tMessage'] = $push_arr['message'];
        $insert_arr['tVarsJSON'] = $push_arr['variables'];
        if (strlen($push_arr['device_id']) > 70) {
            $insert_arr['eDeviceType'] = "Android";
        } else {
            $insert_arr['eDeviceType'] = "iOS";
        }
        $insert_arr['dtAddDateTime'] = date("Y-m-d H:i:s");
        if ($send_type == "runtime") {
            $insert_arr['tSendJSON'] = $this->getPushNotifyOutput("body");
            $insert_arr['dtExeDateTime'] = date("Y-m-d H:i:s");
            if ($success) {
                $insert_arr['eStatus'] = "Executed";
            } else {
                $insert_arr['tError'] = $this->getNotifyErrorOutput();
                $insert_arr['eStatus'] = 'Failed';
            }
        } else {
            $insert_arr['eStatus'] = 'Pending';
        }
        $pid = $this->CI->push->insertPushNotify($insert_arr);
        if ($send_type == "runtime") {
            if (!$success) {
                return FALSE;
            }
            return $success;
        } else {
            if (!$pid) {
                return FALSE;
            }
            return $pid;
        }
    }

    public function getPushNotifyUnique()
    {
//        $db_unique_arr = $this->CI->db->select_single("mod_push_notifications", "vUniqueId");
//        $unique_id = substr(md5(uniqid(rand(), TRUE)), 0, 5);
//        while (in_array($unique_id, $db_unique_arr)) {
//            $unique_id = substr(md5(uniqid(rand(), TRUE)), 0, 5);
//        }
        $unique_id = substr(md5(uniqid(rand(), TRUE)), 0, 5);
        return $unique_id;
    }

    public function array_map_recursive($callback = '', $array = array())
    {
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $key => $value) {
                if (is_array($array[$key])) {
                    $array[$key] = $this->array_map_recursive($callback, $array[$key]);
                } else {
                    $array[$key] = call_user_func($callback, $array[$key]);
                }
            }
        }
        return $array;
    }

    public function sendMail($data = array(), $code = "CONTACT_US", $params = array())
    {
        if (is_array($data) && count($data) > 0) {
            if (!empty($data['vEmail'])) {
                $to = $data['vEmail'];
            } else {
                $to = $this->CI->config->item('EMAIL_ADMIN');
            }
            $mailarr = $this->getSystemEmailData($code);
            $email_var = $this->getVariablesByTemplate($mailarr[0]['iEmailTemplateId']);
            $emailDataArr = $this->replaceEmailTemplate($mailarr[0], $email_var, $data);

            $from_name = $emailDataArr['vFromName'];
            $from = $emailDataArr['vFromEmail'];
            $subject = $emailDataArr['vEmailSubject'];
            $body = $this->setEmailHeaderFooter($emailDataArr['vEmailContent']);
            if (!empty($data['vCcEmail'])) {
                $cc = $data['vCcEmail'];
            } elseif (!empty($data['vCCEmail'])) {
                $cc = $data['vCCEmail'];
            } else {
                $cc = $mailarr[0]['vCcEmail'];
            }
            if (!empty($data['vBccEmail'])) {
                $bcc = $data['vBccEmail'];
            } elseif (!empty($data['vBCCEmail'])) {
                $bcc = $data['vBCCEmail'];
            } else {
                $bcc = $mailarr[0]['vBccEmail'];
            }
            $attach = null;
            if ($data['attachments']) {
                $attach = $data['attachments'];
            }

            $params['_email_data'] = $emailDataArr;
            $params['_email_code'] = $emailDataArr['vEmailCode'];
            $params['_email_params'] = $emailDataArr['_email_params'];

            $success = $this->CISendMail($to, $subject, $body, $from, $from_name, $cc, $bcc, $attach, $params);
            return $success;
        } else {
            return FALSE;
        }
    }

    public function getSystemEmailData($code = '')
    {
        $this->CI->db->select('iEmailTemplateId, vEmailCode, vEmailTitle, vFromName, vFromEmail, vCcEmail, vBccEmail, vEmailSubject, tEmailMessage, eStatus');
        $this->CI->db->where("vEmailCode", $code);
        $mail_data_obj = $this->CI->db->get('mod_system_email');
        $mail_data_arr = is_object($mail_data_obj) ? $mail_data_obj->result_array() : array();
        return $mail_data_arr;
    }

    public function getVariablesByTemplate($id = '')
    {
        $this->CI->db->select('vVarName, vVarDesc');
        $this->CI->db->where("iEmailTemplateId", $id);
        $mail_var_data_obj = $this->CI->db->get('mod_system_email_vars');
        $mail_var_data_arr = is_object($mail_var_data_obj) ? $mail_var_data_obj->result_array() : array();
        return $mail_var_data_arr;
    }

    public function getSearchReplaceArrays($config_arr = array())
    {
        $find_arr = $replace_arr = array();
        $return_arr['find'] = array();
        $return_arr['replace'] = array();
        if (!is_array($config_arr) || count($config_arr) == 0) {
            return $return_arr;
        }
        foreach ($config_arr as $key => $val) {
            $find_val = trim($key);
            if (substr($find_val, 0, 8) == "#SYSTEM.") {
                $temp_find_val = trim($find_val, "#");
                $temp_find_val = trim(substr_replace($temp_find_val, "", 0, 7));
                $replace_val = $this->CI->config->item($temp_find_val);
            } else {
                $replace_val = $val;
            }
            $find_arr[] = $find_val;
            $replace_arr[] = $replace_val;
        }
        $return_arr['find'] = $find_arr;
        $return_arr['replace'] = $replace_arr;
        return $return_arr;
    }

    public function replaceEmailTemplate($template_arr = array(), $variable_arr = array(), $data = array())
    {
        $vEmailCode = $template_arr['vEmailCode'];
        $vEmailTitle = $template_arr['vEmailTitle'];
        $subject = ($data['vSubject'] == "") ? $template_arr['vEmailSubject'] : $data['vSubject'];
        $tEmailMessage = stripslashes($template_arr['tEmailMessage']);

        $vFromEmail = $data['vFromEmail'];
        $vFromEmail = ($vFromEmail == "") ? $template_arr['vFromEmail'] : $vFromEmail;
        if ($vFromEmail == "") {
            $vFromEmail = $this->CI->config->item('NOTIFICATION_EMAIL');
        }

        $vFromName = $data['vFromName'];
        $vFromName = ($vFromName == "") ? $template_arr['vFromName'] : $vFromName;
        if ($vFromName == '') {
            $vFromName = $this->CI->config->item('COMPANY_NAME');
        }

        $config_arr = array();
        if (is_array($variable_arr) && count($variable_arr) > 0) {
            foreach ((array) $variable_arr as $key => $val) {
                $varName = $val['vVarName'];
                $config_arr[$varName] = $data[trim($varName, "#")];
            }
        }

        $list_array = $this->getSearchReplaceArrays($config_arr);
        $find_arr = $list_array['find'];
        $replace_arr = $list_array['replace'];

        $body = str_replace($find_arr, $replace_arr, $tEmailMessage);
        $subject = str_replace($find_arr, $replace_arr, $subject);
        $from_email = str_replace($find_arr, $replace_arr, $vFromEmail);
        $from_name = str_replace($find_arr, $replace_arr, $vFromName);

        $returnArr['vEmailCode'] = $vEmailCode;
        $returnArr['vEmailTitle'] = $vEmailTitle;
        $returnArr['vEmailSubject'] = $subject;
        $returnArr['vEmailContent'] = $body;
        $returnArr['vFromEmail'] = $from_email;
        $returnArr['vFromName'] = $from_name;
        $returnArr['_email_params'] = array(
            'find' => $find_arr,
            'replace' => $replace_arr
        );
        return $returnArr;
    }

    public function setEmailHeaderFooter($body = '')
    {
        $header = $footer = '';
        $content = str_replace(array("#MAIL_HEADER#", "#MAIL_FOOTER#"), array($header, $footer), $body);
        return $content;
    }

    public function CISendMail($to = '', $subject = '', $body = '', $from_email = '', $from_name = '', $cc = '', $bcc = '', $attach = array(), $params = array())
    {
        $success = FALSE;
        try {
            if (empty($to)) {
                throw new Exception("Receiver email address is missing..!");
            }
            if (empty($body) || trim($body) == "") {
                throw new Exception("Email body content is missing..!");
            }
            $this->_email_subject = $subject;
            $this->_email_content = $body;
            $this->_email_params = array(
                'from_name' => $from_name,
                'from_email' => $from_email,
            );

            $this->CI->load->library('email');
            $this->CI->email->from($from_email, $from_name);
            $this->CI->email->reply_to($from_email, $from_name);
            $this->CI->email->to($to);
            if (!empty($cc)) {
                $this->CI->email->cc($cc);
                $this->_email_params['cc'] = $cc;
            }
            if (!empty($bcc)) {
                $this->CI->email->bcc($bcc);
                $this->_email_params['bcc'] = $bcc;
            }
            $this->CI->email->subject($subject);
            $this->CI->email->message($body);
            if (is_array($attach) && count($attach) > 0) {
                foreach ($attach as $ak => $av) {
                    $this->CI->email->attach($av['filename'], $av['position'], $av['newname']);
                }
            }
            $success = $this->CI->email->send();
            if (is_array($attach) && count($attach) > 0) {
                $this->CI->email->clear(TRUE);
            }
            if (!$success) {
                throw new Exception($this->CI->email->print_debugger(array("subject")));
            }
            $message = "Email send successfully..!";
        } catch (Exception $e) {
            $message = $e->getMessage();
            $this->_notify_error = $message;
        }
        return $success;
    }

    public function sendSMSNotification($to_no = '', $message = '')
    {

        $active_api = $this->CI->config->item("SMS_ACTIVE_API");
        if ($active_api == "") {
            $this->_notify_error = "SMS API is not activated. Please configure SMS settings.";
            return FALSE;
        }
        $response = FALSE;
        $active_api = strtolower($active_api);
        if ($active_api == "nexmo") {
            $auth['api_key'] = $this->CI->config->item("SMS_NX_API_KEY");
            $auth['api_secret'] = $this->CI->config->item("SMS_NX_API_SECRET");
            $auth['from_no'] = $this->CI->config->item("SMS_FROM_NUMBER");
            $response = $this->sendSMSNexmo($auth, $to_no, $message['message']);
        } elseif ($active_api == "clickatell") {
            $auth['token'] = $this->CI->config->item("SMS_CA_API_TOKEN");
            $auth['from_no'] = $this->CI->config->item("SMS_FROM_NUMBER");
            $response = $this->sendSMSClickatell($auth, $to_no, $message['message']);
        } elseif ($active_api == "twilio") {
            $auth['sid'] = $this->CI->config->item("SMS_TW_API_SID");
            $auth['token'] = $this->CI->config->item("SMS_TW_API_TOKEN");
            $auth['from_no'] = $this->CI->config->item("SMS_FROM_NUMBER");
            $response = $this->sendSMSTwilio($auth, $to_no, $message['message']);
           
        }
       
        return $response;
    }

    public function sendSMSNexmo($auth = array(), $to = '', $message = '')
    {
        $this->CI->load->library('nexmo', $auth);
        $response = $this->CI->nexmo->sendMessage($to, $message);
        if ($response['success']) {
            return TRUE;
        } else {
            $this->_notify_error = $response['message'] || "SMS sending failed.";
            return FALSE;
        }
    }

    public function sendSMSClickatell($auth = array(), $to = '', $message = '')
    {
        $this->CI->load->library('clickatel', $auth);
        $response = $this->CI->clickatel->sendMessage($to, $message);
        if ($response['success']) {
            return TRUE;
        } else {
            $this->_notify_error = $response['message'] || "SMS sending failed.";
            return FALSE;
        }
    }

    public function sendSMSTwilio($auth = array(), $to = '', $message = '')
    {
        $this->CI->load->library('twilio', $auth);
        $response = $this->CI->twilio->sendMessage($to, $message);
     
        if ($response['success']) {
            return TRUE;
        } else {
            $this->_notify_error = $response['message'] || "SMS sending failed.";
            return FALSE;
        }
    }

    public function generateDesktopCustomLink($link_data_arr = array(), $input_params = array())
    {
        $return_link = "";
        $return_link_arr = $extra_attr_arr = array();
        if (is_array($link_data_arr) && count($link_data_arr) > 0) {
            foreach ($link_data_arr as $inner_key => $inner_val) {
                $extra_param_arr = $inner_val['extra_params'];
                $module_name = $inner_val['module_name'];
                $folder_name = $inner_val['folder_name'];
                $module_type = $inner_val['module_type'];
                $module_page = $inner_val['module_page'];
                $open_on = $inner_val['open'];
                $custom_module_link = $inner_val['custom_link'];
                $apply_condition = $inner_val['apply'];
                $conditions_block = $inner_val['block'];
                if ($apply_condition == "Yes") {
                    $conditionflag = $this->checkConditionalBlock($conditions_block, $input_params);
                    if (!$conditionflag) {
                        continue;
                    }
                }
                if ($module_type == "Module") {
                    if ($module_page == "Add" || $module_page == "Update" || $module_page == "View") {
                        $return_link = $admin_url . "#" . $this->getAdminEncodeURL($folder_name . "/" . $module_name . "/add", 0);
                    } else {
                        $return_link = $admin_url . "#" . $this->getAdminEncodeURL($folder_name . "/" . $module_name . "/index", 0);
                    }
                } else {
                    $external_url = $this->isExternalURL($custom_module_link);
                    if ($external_url) {
                        $return_link = $custom_module_link;
                    } else {
                        $return_link = $admin_url . "#" . $custom_module_link;
                    }
                }
                if (is_array($extra_param_arr) && count($extra_param_arr) > 0) {
                    for ($i = 0; $i < count($extra_param_arr); $i++) {
                        $extra_var_val = $extra_param_arr[$i]['req_val'];
                        $extra_var_type = $extra_param_arr[$i]['req_mod'];
                        $extra_var = $extra_param_arr[$i]['req_var'];
                        if (trim($extra_var_val) == "") {
                            continue;
                        }
                        $req_val = $this->parseConditionFieldValue($extra_var_type, $extra_var_val, $input_params);
                        if ($extra_var != "" && in_array($extra_var, $decryptArr))
                            $return_link .= "|" . $extra_var . "|" . $this->getAdminEncodeURL($req_val);
                        else
                            $return_link .= "|" . $extra_var . "|" . $req_val;
                    }
                }
                if (is_array($input_params) && count($input_params) > 0) {
                    foreach ($input_params as $key => $val) {
                        $find_array[] = "@" . $key . "@";
                        $replace_array[] = $val;
                    }
                }
                $return_link = str_replace($find_array, $replace_array, $return_link);
                if ($open_on == "Popup" && trim($return_link) != "") {
                    $return_link .= "|hideCtrl|true";
                }
                $extra_attr_arr['class'] = "inline-edit-link";
                $extra_attr_arr['target'] = "_self";
                if ($open_on == "NewPage") {
                    $extra_attr_arr['target'] = "_blank";
                } elseif ($open_on == "Popup") {
                    $extra_attr_arr['class'] = "inline-edit-link fancybox-hash-iframe";
                    $return_link .= "|hideCtrl|true";
                }
                break;
            }
        }
        $return_link_arr = $extra_attr_arr;
        $return_link_arr['link'] = $return_link;
        return $return_link_arr;
    }

    public function checkConditionalBlock($condition_data_arr = array(), $input_params = array())
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

                $value_1 = $this->parseConditionFieldValue($operand_1, $value_passed_1, $input_params);
                $value_2 = $this->parseConditionFieldValue($operand_2, $value_passed_2, $input_params);

                $value_1 = $this->getDataTypeWiseResult($type, $value_1, TRUE);
                $value_2 = $this->getDataTypeWiseResult($type, $value_2, FALSE);
                $result = $this->compareDataValues($operator, $value_1, $value_2);

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

    public function getDataTypeWiseResult($type = '', $value = '', $lf = FALSE)
    {
        $value = is_null($value) ? "" : $value;
        if (is_array($value)) {
            if ($type == "array_count" && $lf === TRUE) {
                return count($value);
            }
            return $value;
        }
        switch ($type) {
            case 'integer':
                $result = (int) $value;
                break;
            case 'float':
                $result = (float) $value;
                break;
            case 'date':
                if (trim($value) != "" && $value != "0000-00-00" && $value != "0000-00-00 00:00:00") {
                    $result = date("Y-m-d", strtotime($value));
                } else {
                    $result = "0000-00-00";
                }
                break;
            case 'date_and_time':
                if (trim($value) != "" && $value != "0000-00-00" && $value != "0000-00-00 00:00:00") {
                    $result = date("Y-m-d H:i:s", strtotime($value));
                } else {
                    $result = "0000-00-00 00:00:00";
                }
                break;
            case 'time':
                if (trim($value) != "") {
                    $result = date("H:i:s", strtotime($value));
                } else {
                    $result = "00:00:00";
                }
                break;
            default :
                $result = (string) $value;
                break;
        }
        return $result;
    }

    public function compareDataValues($operator = '', $value_1 = '', $value_2 = '')
    {
        $flag = FALSE;
        switch ($operator) {
            case 'ne':
                $flag = ($value_1 != $value_2) ? TRUE : FALSE;
                break;
            case 'nl'://old
                $flag = (strtolower($value_1) != strtolower($value_2)) ? TRUE : FALSE;
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
            case "lis"://old
            case "bw"://new
                $value_1 = strtolower($value_1);
                $value_2 = strtolower($value_2);
                $length_2 = strlen($value_2);
                $string_2 = substr($value_1, 0, $length_2);
                $flag = ($string_2 == $value_2) ? TRUE : FALSE;
                break;
            case "lie"://old
            case "ew"://new
                $value_1 = strtolower($value_1);
                $value_2 = strtolower($value_2);
                $length_2 = strlen($value_2);
                $string_2 = substr($value_1, -$length_2);
                $flag = ($string_2 == $value_2) ? TRUE : FALSE;
                break;
            case "lib"://old
            case "cn"://new
                $value_1 = strtolower($value_1);
                $value_2 = strtolower($value_2);
                $flag = (strpos($value_1, $value_2) !== FALSE) ? TRUE : FALSE;
                break;
            case "nle"://old
            case "bn"://new
                $value_1 = strtolower($value_1);
                $value_2 = strtolower($value_2);
                $length_2 = strlen($value_2);
                $string_2 = substr($value_1, 0, $length_2);
                $flag = ($string_2 != $value_2) ? TRUE : FALSE;
                break;
            case "nls"://old
            case "en"://new
                $value_1 = strtolower($value_1);
                $value_2 = strtolower($value_2);
                $length_2 = strlen($value_2);
                $string_2 = substr($value_1, -$length_2);
                $flag = ($string_2 != $value_2) ? TRUE : FALSE;
                break;
            case "nlb"://old
            case "nc"://new
                $value_1 = strtolower($value_1);
                $value_2 = strtolower($value_2);
                $flag = (strpos($value_1, $value_2) === FALSE) ? TRUE : FALSE;
                break;
            case 'in':
                $value2 = (is_array($value_2)) ? $value_2 : explode(",", $value_2);
                $flag = (in_array($value_1, $value2)) ? TRUE : FALSE;
                break;
            case 'ni':
                $value2 = (is_array($value_2)) ? $value_2 : explode(",", $value_2);
                $flag = (!(in_array($value_1, $value2))) ? TRUE : FALSE;
                break;
            case "nu":
            case "em":
                $value_1 = trim($value_1);
                $flag = (is_null($value_1) || empty($value_1) || $value_1 == '') ? TRUE : FALSE;
                break;
            case "nn":
            case "nem":
                $value_1 = trim($value_1);
                $flag = (!is_null($value_1) && !empty($value_1) && $value_1 != '') ? TRUE : FALSE;
                break;
            case 'ia':
                $flag = (is_array($value_1)) ? true : false;
                break;
            case 'na':
                $flag = (!(is_array($value_1))) ? true : false;
                break;
            case 'li'://old
                $flag = (strtolower($value_1) == strtolower($value_2)) ? TRUE : FALSE;
                break;
            default :
                $flag = ($value_1 == $value_2) ? TRUE : FALSE;
                break;
        }
        return $flag;
    }

    public function parseConditionFieldValue($operand = '', $value = '', $data = array(), $id = '')
    {
        $ret_val = $value;
        switch ($operand) {
            case "Variable":
                $ret_val = $data[$value];
                break;
            case "Request":
                $ret_val = $_REQUEST[$value];
                break;
            case "Server":
                $ret_val = $_SERVER[$value];
                break;
            case "Session":
                $ret_val = $this->CI->session->userdata($value);
                break;
            case "System":
                $ret_val = $this->CI->config->item($value);
                break;
            case "Function":
                if (method_exists($this, $value)) {
                    $ret_val = $this->$value($data, $id);
                } elseif (substr($value, 0, 12) == 'controller::' && substr($value, 12) !== FALSE) {
                    $value = substr($value, 12);
                    $ctrl_obj = $this->getControllerObject();
                    if (method_exists($ctrl_obj, $value)) {
                        $ret_val = $ctrl_obj->$value($data, $id);
                    }
                } elseif (substr($value, 0, 7) == 'model::' && substr($value, 7) !== FALSE) {
                    $value = substr($value, 7);
                    $model_obj = $this->getModelObject();
                    if (method_exists($model_obj, $value)) {
                        $ret_val = $model_obj->$value($data, $id);
                    }
                }
                break;
            default :
                $ret_val = $value;
                break;
        }
        return $ret_val;
    }

    public function getReplacedInputParams($message = '', $inputparams = array())
    {
        $message = $this->processSystemHashMatch($message);
        if (is_array($inputparams) && count($inputparams) > 0) {
            foreach ($inputparams as $key => $value) {
                if (is_array($value)) {
                    continue;
                }
                $hash_key = '#' . $key . '#';
                if (strstr($message, $hash_key)) {
                    $message = str_replace($hash_key, $value, $message);
                }
            }
        }
        return $message;
    }

    public function processRequestPregMatch($param = '', $input_params = array())
    {
        if ($param != "") {
            if (strstr($param, '{%REQUEST') !== FALSE) {
                preg_match_all("/{%REQUEST\.([a-zA-Z0-9_-]{1,})/i", $param, $preg_all_arr);
                if (isset($preg_all_arr[1]) && is_array($preg_all_arr[1]) && count($preg_all_arr[1]) > 0) {
                    foreach ((array) $preg_all_arr[1] as $key => $value) {
                        if (is_array($input_params[$value])) {
                            continue;
                        }
                        if (strstr($param, '{%REQUEST') !== FALSE) {
                            $param = str_replace("{%REQUEST." . $value . "%}", $input_params[$value], $param);
                        }
                    }
                }
            }
        }
        return $param;
    }

    public function processServerPregMatch($param = '', $input_params = array())
    {
        if ($param != "") {
            if (strstr($param, '{%SERVER') !== FALSE) {
                preg_match_all("/{%SERVER\.([a-zA-Z0-9_-]{1,})/i", $param, $preg_all_arr);
                if (isset($preg_all_arr[1]) && is_array($preg_all_arr[1]) && count($preg_all_arr[1]) > 0) {
                    foreach ((array) $preg_all_arr[1] as $key => $value) {
                        if (strstr($param, '{%SERVER') !== FALSE) {
                            $param = str_replace('{%SERVER.' . $value . '%}', $_SERVER[$value], $param);
                        }
                    }
                }
            }
        }
        return $param;
    }

    public function processSessionPregMatch($param = '', $input_params = array())
    {
        if ($param != "") {
            if (strstr($param, '{%SESSION') !== FALSE) {
                preg_match_all("/{%SESSION\.([a-zA-Z0-9_-]{1,})/i", $param, $preg_all_arr);
                if (isset($preg_all_arr[1]) && is_array($preg_all_arr[1]) && count($preg_all_arr[1]) > 0) {
                    foreach ((array) $preg_all_arr[1] as $key => $value) {
                        if (strstr($param, '{%SESSION') !== FALSE) {
                            $param = str_replace('{%SESSION.' . $value . '%}', $this->CI->session->userdata($value), $param);
                        }
                    }
                }
            }
        }
        return $param;
    }

    public function processSystemPregMatch($param = '', $input_params = array())
    {
        if ($param != "") {
            if (strstr($param, '{%SYSTEM') !== FALSE) {
                preg_match_all("/{%SYSTEM\.([a-zA-Z0-9_-]{1,})/i", $param, $preg_all_arr);
                if (isset($preg_all_arr[1]) && is_array($preg_all_arr[1]) && count($preg_all_arr[1]) > 0) {
                    foreach ((array) $preg_all_arr[1] as $key => $value) {
                        if (strstr($param, '{%SYSTEM') !== FALSE) {
                            $param = str_replace('{%SYSTEM.' . $value . '%}', $this->CI->config->item($value), $param);
                        }
                    }
                }
            }
        }
        return $param;
    }

    public function processSystemHashMatch($param = '', $input_params = array())
    {
        if ($param != "") {
            preg_match_all("/#SYSTEM\.([a-zA-Z0-9_-]{1,})/i", $param, $preg_all_arr);
            if (strstr($param, '#SYSTEM') !== FALSE) {
                if (isset($preg_all_arr[1]) && is_array($preg_all_arr[1]) && count($preg_all_arr[1]) > 0) {
                    foreach ((array) $preg_all_arr[1] as $key => $value) {
                        if (strstr($param, '#SYSTEM') !== FALSE) {
                            $param = str_replace('#SYSTEM.' . $value . '#', $input_params[$value], $param);
                        }
                    }
                }
            }
        }
        return $param;
    }

    public function getAdminExtraCondtion($table_name = '', $table_alias = '')
    {
        $table_arr = $this->getAdminTableDetails($table_name);
        if (!is_array($table_arr) || count($table_arr) == 0 || $table_name == "") {
            return;
        }
        if ($table_alias != "") {
            $where_cond = $this->CI->db->protect($table_alias . "." . $table_arr['admin_field']) . " <> " . $this->CI->db->escape($table_arr['admin_value']);
        } else {
            $where_cond = $this->CI->db->protect($table_arr['admin_field']) . " <> " . $this->CI->db->escape($table_arr['admin_value']);
        }
        return $where_cond;
    }

    public function isAdminDataRecord($id = '', $mode = '', $table_name = '', $field_name = '')
    {
        $retArr['success'] = 0;
        $table_arr = $this->getAdminTableDetails($table_name);
        try {
            switch ($table_name) {
                case 'mod_admin':
                    $this->CI->db->select("*");
                    $this->CI->db->from("mod_admin");
                    $this->CI->db->where($table_arr['admin_primary'], $id);
                    $admin_data_obj = $this->CI->db->get();
                    $admin_data = is_object($admin_data_obj) ? $admin_data_obj->result_array() : array();
                    if ($admin_data[0][$table_arr['admin_field']] == $table_arr['admin_value']) {
                        if ($mode == "Delete") {
                            $msg = "Can not delete admin ";
                            throw new Exception($msg);
                        } else {
                            if (in_array($field_name, $table_arr['restrict_field'])) {
                                $msg = "Can not edit admin ";
                                throw new Exception($msg);
                            }
                        }
                    } elseif ($admin_data[0][$table_arr['admin_primary']] == $table_arr['admin_id']) {
                        if ($mode == "Delete") {
                            $msg = "Can not delete your self ";
                            throw new Exception($msg);
                        } else {
                            if (in_array($field_name, $table_arr['session_field'])) {
                                $msg = "Can not edit your self ";
                                throw new Exception($msg);
                            }
                        }
                    }
                    break;
                case 'mod_group_master':
                    $this->CI->db->select("*");
                    $this->CI->db->from("mod_group_master");
                    $this->CI->db->where($table_arr['admin_primary'], $id);
                    $admin_data_obj = $this->CI->db->get();
                    $admin_data = is_object($admin_data_obj) ? $admin_data_obj->result_array() : array();
                    if ($admin_data[0][$table_arr['admin_field']] == $table_arr['admin_value']) {
                        if ($mode == "Delete") {
                            $msg = "Can not delete admin ";
                            throw new Exception($msg);
                        } else {
                            if (in_array($field_name, $table_arr['restrict_field'])) {
                                $msg = "Can not edit admin ";
                                throw new Exception($msg);
                            }
                        }
                    }
                    break;
            }
        } catch (Exception $e) {
            $msg = $e->getMessage();
            $retArr['success'] = 1;
            $retArr['message'] = $msg;
        }
        return $retArr;
    }

    public function getAdminDataRecords($table_name = '')
    {
        $table_arr = $this->getAdminTableDetails($table_name);
        $admin_records = array();
        switch ($table_name) {
            case 'mod_admin':
                $this->CI->db->select("*");
                $this->CI->db->from("mod_admin");
                $this->CI->db->where($table_arr['admin_primary'], $table_arr['admin_id']);
                $this->CI->db->or_where($table_arr['admin_field'], $table_arr['admin_value']);
                $db_admin_data_obj = $this->CI->db->get();
                $db_admin_data = is_object($db_admin_data_obj) ? $db_admin_data_obj->result_array() : array();
                break;
            case 'mod_group_master':
                $this->CI->db->select("*");
                $this->CI->db->from("mod_group_master");
                $this->CI->db->where($table_arr['admin_field'], $table_arr['admin_value']);
                $db_admin_data_obj = $this->CI->db->get();
                $db_admin_data = is_object($db_admin_data_obj) ? $db_admin_data_obj->result_array() : array();
                break;
        }
        if (is_array($db_admin_data) && count($db_admin_data) > 0) {
            foreach ((array) $db_admin_data as $key => $val) {
                $admin_records[] = $this->getAdminEncodeURL($val[$table_arr['admin_primary']]);
            }
        }
        return $admin_records;
    }

    public function getAdminTableDetails($table_name = '')
    {
        switch ($table_name) {
            case 'mod_admin':
                $table_info['admin_primary'] = "iAdminId";
                $table_info['admin_field'] = "vUserName";
                $table_info['admin_value'] = $this->CI->config->item("ADMIN_USER_NAME");
                $table_info['restrict_field'] = array("vUserName", "iGroupId", "eStatus");
                $table_info['session_field'] = array("eStatus");
                $table_info['admin_id'] = $this->CI->session->userdata('iAdminId');
                break;
            case 'mod_group_master':
                $table_info['admin_primary'] = "iGroupId";
                $table_info['admin_field'] = "vGroupCode";
                $table_info['admin_value'] = $this->CI->config->item("ADMIN_GROUP_NAME");
                $table_info['restrict_field'] = array("vGroupCode", "eStatus");
                break;
        }
        return $table_info;
    }

    public function deleteReferenceModules($del_modules = array(), $main_data = array(), $physical_data_remove = "")
    {
        if (!is_array($del_modules) || count($del_modules) == 0) {
            return FALSE;
        }
        if (!is_array($main_data) || count($main_data) == 0) {
            return FALSE;
        }
        $PHYSICAL_REC_DELETE = $this->CI->config->item('PHYSICAL_RECORD_DELETE');
        foreach ($main_data as $mKey => $mVal) {
            foreach ($del_modules as $dKey => $dVal) {
                $module_name = $dVal['module'];
                $folder_name = $dVal['folder'];
                $rel_source = $dVal['rel_source'];
                $rel_target = $dVal['rel_target'];
                $del_cond = $dVal['extra_cond'];
                $model_name = $module_name . "_model";
                if ($folder_name == "" || $module_name == "") {
                    continue;
                }
                $this->CI->load->model($folder_name . "/" . $model_name);
                $form_config = $this->CI->$model_name->getFormConfiguration();
                $file_arr = $data_arr = array();
                if (is_array($form_config) && count($form_config) > 0) {
                    foreach ($form_config as $fKey => $fVal) {
                        if ($fVal['type'] == "file" && $fVal['file_upload'] == 'Yes') {
                            $file_arr[] = $fVal;
                        }
                    }
                }
                $extra_cond = $this->CI->db->protect($this->CI->$model_name->table_alias . "." . $rel_source) . " = " . $this->CI->db->escape($mVal[$rel_target]);
                if ($del_cond != "") {
                    $extra_cond .= " AND " . $del_cond;
                }
                if (is_array($file_arr) && count($file_arr) > 0) {
                    $data_arr = $this->CI->$model_name->getData($extra_cond);
                }

                $this->CI->$model_name->physical_data_remove = $physical_data_remove;
                $res = $this->CI->$model_name->delete($extra_cond, "Yes");
                if ($res) {
                    if (is_array($file_arr) && count($file_arr) > 0) {
                        $this->deleteMediaFiles($file_arr, $data_arr, $physical_data_remove);
                    }
                }
            }
        }
        return $res;
    }

    public function deleteMediaFiles($config_arr = array(), $data_arr = array(), $physical_data_remove = "")
    {
        if (!is_array($config_arr) || count($config_arr) == 0) {
            return FALSE;
        }
        if (!is_array($data_arr) || count($data_arr) == 0) {
            return FALSE;
        }
        if ($this->CI->config->item('PHYSICAL_RECORD_DELETE') && $physical_data_remove == "No") {
            return FALSE;
        }
        foreach ($data_arr as $dKey => $dVal) {
            foreach ($config_arr as $cKey => $cVal) {
                if ($cVal['type'] == "file" && $cVal['file_upload'] == 'Yes') {
                    $folder_name = $cVal['file_folder'];
                    $file_name = $dVal[$cVal['vFieldName']];
                    if ($cVal['file_keep'] != '') {
                        $each_folder = $dVal[$cVal['file_keep']];
                        $file_path = $this->CI->config->item('upload_path') . $folder_name . DS . $each_folder . DS . $file_name . DS;
                    } else {
                        $file_path = $this->CI->config->item('upload_path') . $folder_name . DS . $file_name;
                    }
                    if (is_file($file_path) && $file_name != "") {
                        $res = unlink($file_path);
                    }
                }
            }
        }
        return $res;
    }

    public function getSingleColArray($data_arr = array(), $index = "")
    {
        $retArr = array();
        if (!is_array($data_arr) || count($data_arr) == 0 || $index == "") {
            return $retArr;
        }
        foreach ((array) $data_arr as $key => $val) {
            $retArr[] = $val[$index];
        }
        return $retArr;
    }

    public function getPhysicalRecordWhere($vTableName = '', $vTableAlias = '', $mode = "AR")
    {
        $extra_cond = '';
        if ($this->CI->config->item('PHYSICAL_RECORD_DELETE')) {
            if ($vTableAlias != "") {
                $extra_cond = $this->CI->db->protect($vTableAlias . ".iSysRecDeleted") . " <> " . $this->CI->db->escape(1);
            } else {
                $extra_cond = $this->CI->db->protect($vTableName . ".iSysRecDeleted") . " <> " . $this->CI->db->escape(1);
            }
        }
        if ($mode == "AR") {
            if ($extra_cond != "") {
                $this->CI->db->where($extra_cond, FALSE, FALSE);
            }
        } else {
            return $extra_cond;
        }
    }

    public function getPhysicalRecordUpdate($vTableAlias = '')
    {
        $update_arr = array();
        if ($vTableAlias != "") {
            $update_arr[$vTableAlias . ".iSysRecDeleted"] = 1;
        } else {
            $update_arr["iSysRecDeleted"] = 1;
        }
        return $update_arr;
    }

    public function getMD5EncryptString($type = '', $item = '')
    {
        $admin_url = $this->CI->config->item("admin_url");
        if (in_array($type, array('JavaScript', 'ListPrefer', 'FlowAdd', 'FlowEdit', 'DetailView'))) {
            $str = $admin_url . "_" . $this->CI->session->userdata('iAdminId');
        } else {
            $str = $admin_url;
        }
        switch ($type) {
            //local storage related
            case 'JavaScript':
                $suffix = "JS";
                break;
            case 'ListPrefer':
                $suffix = "LP";
                break;
            //file cache related
            case 'FlowAdd':
                $suffix = "FA";
                break;
            case 'FlowEdit':
                $suffix = "FE";
                break;
            case 'AppCache':
                $suffix = "AC";
                break;
            case 'AppCacheJS':
                $suffix = "AJ";
                break;
            case 'AppCacheCSS':
                $suffix = "AS";
                break;
            //cookie related
            case 'DetailView':
                $suffix = "DV";
                break;
            case 'RememberMe':
                $suffix = "RM";
                break;
            case 'DontAskMe':
                $suffix = "DM";
                break;
        }
        if ($suffix) {
            $str .= "_" . strtolower($suffix);
        }
        if ($item != "") {
            $str .= "_" . strtolower($item);
        }
        $enc_str = md5($str);
        return $enc_str;
    }

    public function trackModuleNavigation($type = "", $navigType = '', $navigAction = 'Viewed', $entityURL = '', $entityName = '', $recName = '', $extra_attr = '')
    {
        $NAVIGATION_LOG_REQ = $this->CI->config->item('NAVIGATION_LOG_REQ');
        $LogNavOn = (strtolower($NAVIGATION_LOG_REQ) == "y") ? TRUE : FALSE;
        $iAdminId = $this->CI->session->userdata('iAdminId');
        $query_str = '';
        if ($_REQUEST['hashValue'] != "") {
            $query_str = ltrim($_REQUEST['hashValue'], "#");
        } elseif ($_REQUEST['extra_hstr'] != "") {
            $query_str = ltrim($_REQUEST['extra_hstr'], "#");
        }
        if (!empty($iAdminId) && $LogNavOn && in_array($type, array("Dashboard", "Module"))) {
            $this->CI->load->model('general/navigation_model');
            if ($type == "Dashboard") {
                $this->CI->db->select("m.vMenuDisplay AS subMenu");
                $this->CI->db->select("m.vURL AS subURL");
                $this->CI->db->select("(SELECT " . $this->CI->db->protect("s.vMenuDisplay") . " FROM " . $this->CI->db->protect("mod_admin_menu") . " AS " . $this->CI->db->protect("s1") . " WHERE " . $this->CI->db->protect("s2.iAdminMenuId") . " = " . $this->CI->db->protect("m.iParentId") . ") AS " . $this->CI->db->protect("mainMenu"), FALSE);
                $this->CI->db->select("(SELECT " . $this->CI->db->protect("s.vURL") . " FROM " . $this->CI->db->protect("mod_admin_menu") . " AS " . $this->CI->db->protect("s2") . " WHERE " . $this->CI->db->protect("s2.iAdminMenuId") . " = " . $this->CI->db->protect("m.iParentId") . ") AS " . $this->CI->db->protect("mainURL"), FALSE);
                $this->CI->db->where("m.vDashBoardPage", $entityName);
                $this->CI->db->order_by("mainMenu", "DESC");
                $this->CI->db->limit(1);
                $db_data_obj = $this->CI->db->get("mod_admin_menu AS m");
                $db_data = is_object($db_data_obj) ? $db_data_obj->result_array() : array();

                $main_menu = $db_data[0]['mainMenu'];
                $sub_menu = $db_data[0]['subMenu'];
                $sup_str = $db_data[0]['mainURL'];
                $navig_str = $entityURL;
            } elseif ($type == "Module") {
                $this->CI->db->select("m.vMenuDisplay AS subMenu");
                $this->CI->db->select("m.vURL AS subURL");
                $this->CI->db->select("s.vMenuDisplay AS mainMenu");
                $this->CI->db->select("s.vURL AS mainURL");
                $this->CI->db->join('mod_admin_menu AS s', 's.iAdminMenuId = m.iParentId', 'left');
                $this->CI->db->where("m.vModuleName", $entityName);
                $this->CI->db->order_by("mainMenu", "DESC");
                $this->CI->db->limit(1);
                $db_data_obj = $this->CI->db->get("mod_admin_menu AS m");
                $db_data = is_object($db_data_obj) ? $db_data_obj->result_array() : array();

                $main_menu = $db_data[0]['mainMenu'];
                $sub_menu = $db_data[0]['subMenu'];
                $sup_str = $db_data[0]['mainURL'];
                $navig_str = $entityURL;
            }
            if ($query_str != "") {
                $navig_str .= "|" . $query_str;
            }
            if ($extra_attr != "") {
                $navig_str .= "|" . $extra_attr;
            }
            $sup_str_url = '';
            if ($sup_str != "") {
                $sup_str = trim($sup_str, "|");
                $sup_arr = explode("|", $sup_str);
                $sup_str_url = $this->getAdminEncodeURL($sup_arr[0]);
            }
            $insert_navig_arr['iAdminId'] = $iAdminId;
            $insert_navig_arr['vMainMenu'] = $main_menu;
            $insert_navig_arr['vSubMenu'] = $sub_menu;
            $insert_navig_arr['vRecordName'] = addslashes($recName);
            $insert_navig_arr['vSupQString'] = $sup_str_url;
            $insert_navig_arr['vNavigQString'] = $this->converNavigationVars($navig_str);
            $insert_navig_arr['eNavigAction'] = $navigAction;
            $insert_navig_arr['eNavigType'] = $navigType;
            $insert_navig_arr['dTimeStamp'] = date("Y-m-d H:i:s");
            $iNavigationId = $this->CI->navigation_model->insert($insert_navig_arr);
            return $iNavigationId;
        } else {
            return FALSE;
        }
    }

    public function trackCustomNavigation($navigType = "List", $navigAction = "Viewed", $entityURL = '', $menuCond = '', $recName = '', $extra_attr = '')
    {
        $NAVIGATION_LOG_REQ = $this->CI->config->item('NAVIGATION_LOG_REQ');
        $LogNavOn = (strtolower($NAVIGATION_LOG_REQ) == "y") ? TRUE : FALSE;
        $iAdminId = $this->CI->session->userdata('iAdminId');
        $query_str = '';
        if ($_REQUEST['hashValue'] != "") {
            $query_str = ltrim($_REQUEST['hashValue'], "#");
        } elseif ($_REQUEST['extra_hstr'] != "") {
            $query_str = ltrim($_REQUEST['extra_hstr'], "#");
        }
        if (!empty($iAdminId) && $LogNavOn && $menuCond != "") {
            $this->CI->load->model('general/navigation_model');

            $this->CI->db->select("m.vMenuDisplay AS subMenu");
            $this->CI->db->select("m.vURL AS subURL");
            $this->CI->db->select("s.vMenuDisplay AS mainMenu");
            $this->CI->db->select("s.vURL AS mainURL");
            $this->CI->db->join('mod_admin_menu AS s', 's.iAdminMenuId = m.iParentId', 'left');
            $this->CI->db->where($menuCond, FALSE, FALSE);

            $this->CI->db->order_by("mainMenu", "DESC");
            $this->CI->db->limit(2);
            $db_data_obj = $this->CI->db->get("mod_admin_menu AS m");
            $db_data = is_object($db_data_obj) ? $db_data_obj->result_array() : array();

            $main_menu = $db_data[0]['mainMenu'];
            $sub_menu = $db_data[0]['subMenu'];
            $sup_str = $db_data[0]['mainURL'];
            $navig_str = $entityURL;

            if ($query_str != "") {
                $navig_str .= "|" . $query_str;
            }
            if ($extra_attr != "") {
                $navig_str .= "|" . $extra_attr;
            }

            $sup_str_url = '';
            if ($sup_str != "") {
                $sup_str = trim($sup_str, "|");
                $sup_arr = explode("|", $sup_str);
                $sup_str_url = $this->getAdminEncodeURL($sup_arr[0]);
            }

            $insert_navig_arr['iAdminId'] = $iAdminId;
            $insert_navig_arr['vMainMenu'] = $main_menu;
            $insert_navig_arr['vSubMenu'] = $sub_menu;
            $insert_navig_arr['vRecordName'] = addslashes($recName);
            $insert_navig_arr['vSupQString'] = $sup_str;
            $insert_navig_arr['vNavigQString'] = $this->converNavigationVars($navig_str);
            $insert_navig_arr['eNavigAction'] = $navigAction;
            $insert_navig_arr['eNavigType'] = $navigType;
            $insert_navig_arr['dTimeStamp'] = date("Y-m-d H:i:s");
            $iNavigationId = $this->CI->navigation_model->insert($insert_navig_arr);
            return $iNavigationId;
        } else {
            return FALSE;
        }
    }

    public function converNavigationVars($navig_str = '', $flag = FALSE)
    {
        $navig_str = trim($navig_str, "|");
        $navig_arr = explode("|", $navig_str);
        if (!is_array($navig_arr) || count($navig_arr) == 0) {
            return $navig_str;
        }
        $decrypt_arr = $this->CI->config->item("FRAMEWORK_ENCRYPTS");
        $repeat_arr = $format_url = array();
        for ($i = 1; $i < count($navig_arr); $i += 2) {
            $key = $navig_arr[$i];
            $param = $navig_arr[$i + 1];
            if (in_array($key, $repeat_arr)) {
                continue;
            }
            $repeat_arr[] = $key;
            $format_url[$i] = $key;
            if (in_array($key, $decrypt_arr) && $param != "") {
                $format_url[$i + 1] = $this->getAdminEncodeURL($param);
            } else {
                $format_url[$i + 1] = $param;
            }
        }
        if ($flag === TRUE) {
            $final_str['module'] = $navig_arr[0];
            $final_str['params'] = implode("|", $format_url);
        } else {
            $final_str = $navig_arr[0] . "|" . implode("|", $format_url);
        }
        return $final_str;
    }

    public function allowStripSlashes()
    {
        if ((version_compare(PHP_VERSION, '5.3.0') >= 0)) {
            return TRUE;
        } elseif ((version_compare(PHP_VERSION, '5.3.0') < 0)) {
            if (!get_magic_quotes_gpc()) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getRequestURLParams($omit_flag = FALSE)
    {
        $FRAMEWORK_VARS = $this->CI->config->item('FRAMEWORK_VARS');
        $FRAMEWORK_OMITS = $this->CI->config->item('FRAMEWORK_OMITS');
        $request_query_url = '';
        if ($this->CI->input->get_post("rfMod") != "" && $this->CI->input->get_post("rfField") != "") {
            $request_query_url .= "&rfMod=" . $this->getAdminEncodeURL($this->CI->input->get_post("rfMod")) . "&rfFod=" . $this->getAdminEncodeURL($this->CI->input->get_post("rfFod")) . "&rfField=" . $this->CI->input->get_post("rfField") . "&rfhtmlID=" . $this->CI->input->get_post("rfhtmlID");
        } elseif ($this->CI->input->get_post("loadGrid") != "") {
            $request_query_url .= "&loadGrid=" . $this->CI->input->get_post("loadGrid");
        }
        if ($this->CI->input->get_post("rmPopup") == "true") {
            $request_query_url .= "&rmPopup=" . $this->CI->input->get_post("rmPopup") . "&rmNum=" . $this->CI->input->get_post("rmNum") . "&rmMode=" . $this->CI->input->get_post("rmMode");
        }
        if ($this->CI->input->get_post("tEditFP") == "true") {
            $request_query_url .= '&tEditFP=true';
        }
        if ($this->CI->input->get_post("hideCtrl") == "true") {
            $request_query_url .= '&hideCtrl=true';
        }
        $req_arr = (is_array($_GET)) ? $_GET : array();
        $req_arr = (is_array($_POST)) ? array_merge($req_arr, $_POST) : $req_arr;
        if (is_array($req_arr) && count($req_arr) > 0) {
            foreach ($req_arr as $g_key => $g_val) {
                if (!in_array($g_key, $FRAMEWORK_VARS) || ($omit_flag && in_array($g_key, $FRAMEWORK_OMITS))) {
                    $request_query_url .= '&' . $g_key . '=' . $g_val;
                }
            }
        }
        return $request_query_url;
    }

    public function getRequestHASHParams()
    {
        $FRAMEWORK_VARS = $this->CI->config->item('FRAMEWORK_VARS');
        $qString = $this->CI->config->item('qString');
        $request_hash_url = '';
        if ($this->CI->input->get_post("rfMod") != "" && $this->CI->input->get_post("rfField") != "") {
            $request_hash_url .= "|rfMod|" . $this->getAdminEncodeURL($this->CI->input->get_post("rfMod")) . "|rfFod|" . $this->getAdminEncodeURL($this->CI->input->get_post("rfFod")) . "|rfField|" . $this->CI->input->get_post("rfField") . "|rfhtmlID|" . $this->CI->input->get_post("rfhtmlID");
        } elseif ($this->CI->input->get_post("loadGrid") != "") {
            $request_hash_url .= "|loadGrid|" . $this->CI->input->get_post("loadGrid");
        }
        if ($this->CI->input->get_post("rmPopup") == "true") {
            $request_hash_url .= "|rmPopup|" . $this->CI->input->get_post("rmPopup") . "|rmNum|" . $this->CI->input->get_post("rmNum") . "|rmMode|" . $this->CI->input->get_post("rmMode");
        }
        if ($this->CI->input->get_post("tEditFP") == "true") {
            $request_hash_url .= '|tEditFP|true';
        }
        if ($this->CI->input->get_post("hideCtrl") == "true") {
            $request_hash_url .= '|hideCtrl|true';
        }
        $req_arr = (is_array($_GET)) ? $_GET : array();
        $req_arr = (is_array($_POST)) ? array_merge($req_arr, $_POST) : $req_arr;
        if ($qString == "false") {
            return $request_hash_url;
        }
        if (is_array($req_arr) && count($req_arr) > 0) {
            foreach ($req_arr as $g_key => $g_val) {
                if (!in_array($g_key, $FRAMEWORK_VARS)) {
                    $request_hash_url .= '|' . $g_key . '|' . $g_val;
                }
            }
        }
        return $request_hash_url;
    }

    public function getHASHFilterParams($hash_str = '')
    {
        $FRAMEWORK_VARS = $this->CI->config->item('FRAMEWORK_VARS');
        $request_hash_str = '';
        $hash_str = trim($hash_str, "|");
        $hash_arr = explode("|", $hash_str);
        if (is_array($hash_arr) && count($hash_arr) > 0) {
            for ($i = 0; $i < count($hash_arr); $i += 2) {
                if (!in_array(trim($hash_arr[$i]), $FRAMEWORK_VARS)) {
                    $request_hash_str .= '|' . trim($hash_arr[$i]) . '|' . $hash_arr[$i + 1];
                }
            }
        }
        return $request_hash_str;
    }

    public function displayKeyValueData($selected_val = "", $combo_arr = array(), $is_optgroup = FALSE)
    {
        $retStr = "";
        if ($is_optgroup == FALSE) {
            if (is_array($combo_arr) && count($combo_arr) > 0) {
                $str = "";
                foreach ($combo_arr as $key => $val) {
                    if (is_array($selected_val) && count($selected_val) > 0) {
                        if (in_array($key, $selected_val)) {
                            $str .= $val . ",";
                        }
                    } else {
                        if ($selected_val == $key) {
                            $str = $val;
                        }
                    }
                }
            }
            $retStr = rtrim($str, ',');
        } else {
            if (is_array($combo_arr) && count($combo_arr) > 0) {
                $str = "";
                foreach ($combo_arr as $key => $val) {
                    foreach ($val as $innerkey => $innerval) {
                        if (is_array($selected_val) && count($selected_val) > 0) {
                            if (in_array($innerkey, $selected_val)) {
                                $str .= $innerval . ",";
                            }
                        } else {
                            if ($selected_val == $innerkey) {
                                $str = $innerval;
                            }
                        }
                    }
                }
                $retStr = rtrim($str, ',');
            }
        }
        return $retStr;
    }

    public function getTokenKeyValueJSON($selected_val = '', $combo_arr = array())
    {
        $token_arr = $return_arr = array();
        $keys_arr = (is_array($combo_arr)) ? array_keys($combo_arr) : array();

        $select_arr = explode(',', $selected_val);
        $j = 0;
        for ($i = 0; $i < count($select_arr); $i++) {
            if (in_array($select_arr[$i], $keys_arr)) {
                $token_arr[$j]['id'] = $select_arr[$i];
                $token_arr[$j]['val'] = $combo_arr[$select_arr[$i]];
                $j++;
            }
        }
        $return_arr = $token_arr;
        $json_string = json_encode($return_arr);
        $json_string = htmlspecialchars($json_string, ENT_QUOTES & ~ENT_COMPAT, 'UTF-8');
        return $json_string;
    }

    public function getMinAndMaxYears($type = '')
    {
        $ret_year = "";
        if ($type == 'min') {
            $ret_year = date("Y") - 100;
        } elseif ($type == "Max") {
            $ret_year = date("Y") + 100;
        }
        return $ret_year;
    }

    public function getAdminLangFlagHTML($htmlID = '', $exlang_arr = array(), $lang_info = array())
    {
        $return_data = "";
        $show_all_text = $this->CI->lang->line('GENERIC_SHOW_ALL');
        $hide_all_text = $this->CI->lang->line('GENERIC_HIDE_ALL');
        if (is_array($exlang_arr) && count($exlang_arr) > 0) {
            foreach ($exlang_arr as $key => $val) {
                $lang_title = $lang_info[$val]['vLangTitle'];
                $return_data .= '<div class="lang-icon-container"><a href="javascript://" onclick="showAdminLanguageArea(this, \'single\', \'' . $htmlID . '\', \'' . $val . '\')" class="lang-anchor tip" title="' . $lang_title . '" tabindex="-1"><span class="multi-lingual-icon ' . $val . '"></span></a></div>';
            }
        }
        $return_data .= '<div class="lang-icon-container lang-empty"><strong>|</strong></div>';
        $return_data .= '<div class="lang-icon-container lang-all"><a href="javascript://" onclick="showAdminLanguageArea(this, \'all\', \'' . $htmlID . '\')" class="show-all-anchor tip" aria-show-text="' . $show_all_text . '" aria-hide-text="' . $hide_all_text . '" title="' . $show_all_text . '" aria-describedby="ui-tooltip-3" tabindex="-1" aria-display="hide"><span class="icon16 cut-icon-expand"></span></a></div>';
        return $return_data;
    }

    public function getMinMaxDateEntry($type = 'min', $date_fomat = '')
    {
        $start_date = date('Y-m-d');
        $return_time = "";
        if ($type == "min") {
            $minimum_date = $date_fomat;
            if (trim($minimum_date) != '') {
                $min_date_arr = explode("::", $minimum_date);
                $minDatestr = strtotime($min_date_arr[0] . " year " . $min_date_arr[1] . " month " . $min_date_arr[2] . " day", $start_date);
                $return_time = ceil(($minDatestr - $start_date) / 86400);
            }
        } elseif ($type == "max") {
            $maximum_date = $date_fomat;
            if (trim($maximum_date) != '') {
                $max_date_arr = explode("::", $maximum_date);
                $maxDatestr = strtotime($max_date_arr[0] . " year " . $max_date_arr[1] . " month " . $max_date_arr[2] . " day", $start_date);
                $return_time = ceil(($maxDatestr - $start_date) / 86400);
            }
        }
        return $return_time;
    }

    public function getCustomHashLink($redirect_link = "")
    {
        $admin_url = $this->CI->config->item('admin_url');
        $link = "";
        if ($redirect_link != "") {
            if ($this->isExternalURL($redirect_link)) {
                $link = $redirect_link;
            } else {
                $link = $admin_url . $redirect_link;
            }
        }
        return $link;
    }

    /**
     * logExecutedEmails method is used to make an record of notification emails sent.
     */
    public function logExecutedEmails($type = 'Admin', $email_vars = array(), $success = FALSE)
    {
        $log_arr = array();
        $log_arr['eEntityType'] = $type;
        $log_arr['vReceiver'] = is_array($email_vars["vEmail"]) ? implode(",", $email_vars["vEmail"]) : $email_vars["vEmail"];
        $log_arr['eNotificationType'] = "EmailNotify";
        $log_arr['vSubject'] = $this->getEmailOutput("subject");
        $log_arr['tContent'] = $this->getEmailOutput("content");
        $log_arr['tParams'] = json_encode($this->getEmailOutput("params"));
        if (!$success) {
            $log_arr['tError'] = $this->getNotifyErrorOutput();
        }
        $log_arr['dtSendDateTime'] = date('Y-m-d H:i:s');
        $log_arr['eStatus'] = ($success) ? "Executed" : "Failed";
        $this->insertExecutedNotify($log_arr);
        return TRUE;
    }

    /**
     * logExecutedSMS method is used to make an record of SMS's sent.
     */
    public function logExecutedSMS($type = 'Admin', $sms_vars = array(), $success = FALSE)
    {
        $log_arr = array();
        $log_arr['eEntityType'] = $type;
        $log_arr['vReceiver'] = $sms_vars['to'];
        $log_arr['eNotificationType'] = "SMS";
        $log_arr['tContent'] = $sms_vars['message'];
        if (!$success) {
            $log_arr['tError'] = $this->getNotifyErrorOutput();
        }
        $log_arr['dtSendDateTime'] = date('Y-m-d H:i:s');
        $log_arr['eStatus'] = ($success) ? "Executed" : "Failed";
        $this->insertExecutedNotify($log_arr);
        return TRUE;
    }

    public function insertExecutedNotify($insert_arr = array())
    {
        if (!isset($insert_arr['tParams'])) {
            $insert_arr['tParams'] = json_encode($this->getEmailOutput("params"));
        }
        $success = $this->CI->db->insert("mod_executed_notifications", $insert_arr);
        return $success;
    }

    public function getEmailOutput($type = 'subject')
    {
        if ($type == "subject") {
            $ret_str = $this->_email_subject;
            $this->_email_subject = '';
        } elseif ($type == "content") {
            $ret_str = $this->_email_content;
            $this->_email_content = '';
        } elseif ($type == "params") {
            $ret_str = $this->_email_params;
            $this->_email_params = array();
        }
        return $ret_str;
    }

    public function getPushNotifyOutput($type = 'body')
    {
        if ($type == "body") {
            $ret_str = $this->_push_content;
            $this->_push_content = '';
        }
        return $ret_str;
    }

    public function getNotifyErrorOutput()
    {
        $ret_str = $this->_notify_error;
        $this->_notify_error = '';
        return $ret_str;
    }

    public function getQueryLogFiles()
    {
        $admin_log_path = $this->CI->config->item('admin_query_log_path');
        $log_files = array();
        if (is_dir($admin_log_path)) {
            $handle = opendir($admin_log_path);
            while (FALSE !== ($file = readdir($handle))) {
                if (in_array($file, array(".", "..", ".svn"))) {
                    continue;
                }
                if (count($log_files) > 100) {
                    break;
                }
                if (is_file($admin_log_path . DS . $file)) {
                    $fdate = filemtime($admin_log_path . DS . $file);
                    $log_files[$fdate] = $file;
                }
            }
            krsort($log_files);
            $log_files = array_values($log_files);
        }
        return $log_files;
    }

    public function makeLableNameFromString($str = '')
    {
        // spcial chars code generation
        $special_char_find = array(
            "!", "#", "$", "%", "&", "(", ")", "*", "+",
            ",", "-", ".", "/", ":", ";", "<", "=", ">",
            "?", "@", "[", "]", "^", "{", "|", "}", "~"
        );
        $special_char_replace = array(
            "_c33", "_c35", "_c36", "_c37", "_c38", "_c40", "_c41", "_c42", "_c43",
            "_c44", "_c45", "_c46", "_c47", "_c58", "_c59", "_c60", "_c61", "_c62",
            "_c63", "_c64", "_c91", "_c93", "_c94", "_c123", "_c124", "_c125", "_c126"
        );
        $str = str_replace($special_char_find, $special_char_replace, $str);

        $str = strtolower(preg_replace("/[^A-Za-z0-9_]/", '', str_replace(' ', '_', trim($str))));
        $str = trim($str, "_");
        return $str;
    }

    public function getDisplayLabel($module = '', $label_text = '', $type = 'tpl')
    {
        $return_label = $label_text;
        if ($label_text != "") {
            $lablename = $this->makeLableNameFromString($label_text);
            $final_label = strtoupper($module) . "_" . strtoupper($lablename);
            if ($type == "label") {
                $return_label = $final_label;
            } elseif ($type == "php") {
                $return_label = "\$this->lang->line('" . $final_label . "')";
            } else {
                $return_label = "<%\$this->lang->line('" . $final_label . "')%>";
            }
        }

        return $return_label;
    }

    public function replaceDisplayLabel($label_text = '', $find_text = '', $repace_text = '')
    {
        $return_label = $label_text;
        if ($label_text != "") {
            $return_label = str_replace($find_text, $repace_text, $label_text);
        }
        return $return_label;
    }

    public function processMessageLabel($message_label = '', $data_arr = array())
    {
        $return_text = $message_label;
        if ($message_label != "") {
            $return_text = $this->CI->lang->line($message_label);
            $return_text = $this->renderMessageLabel($return_text, $data_arr);
        }
        return $return_text;
    }

    public function renderMessageLabel($message = '', $data_arr = array())
    {
        if (!$message || !is_array($data_arr) || count($data_arr) == 0 || (strstr($message, '#') !== FALSE)) {
            return $message;
        }
        foreach ($data_arr as $key => $value) {
            $hash_key = '#' . $key . '#';
            if (strstr($message, $hash_key)) {
                $message = str_replace($hash_key, $value, $message);
            }
        }
        return $message;
    }

    public function parseTPLMessage($message_label = '')
    {
        $return_text = $message_label;
        if ($message_label != "") {
            $return_text = $this->CI->lang->line($message_label);
            $return_text = str_replace('"', '\"', ($return_text));
        }
        return $return_text;
    }

    public function parseLabelMessage($search_label = '', $find_text = '', $repace_label = '')
    {
        $search_text = $this->CI->lang->line($search_label);
        $repace_text = $this->CI->lang->line($repace_label);
        if ($search_text != "" && $find_text != "") {
            $search_text = str_replace($find_text, $repace_text, $search_text);
        }
        return $search_text;
    }

    public function processQuotes($text = '', $type = 'double')
    {
        if ($text != "") {
            $text = str_replace('"', '\"', ($text));
            $text = str_replace(array("\r", "\n"), '', $text);
        }
        return $text;
    }

    public function validateFileSize($permitted_file_size = '', $upload_size = '')
    {
        $upload_size_kb = ceil($upload_size / 1024);
        if ($permitted_file_size >= $upload_size_kb) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function validateFileFormat($permitted_file_ext = '', $upload_file = '')
    {
        $ext_arr = explode('.', $upload_file);
        if (count($ext_arr) === 1) {
            return FALSE;
        }
        if (!is_array($permitted_file_ext) && trim($permitted_file_ext) == '*') {
            return TRUE;
        }
        if (!is_array($permitted_file_ext)) {
            $permitted_file_ext = explode(",", $permitted_file_ext);
        }
        $ext = strtolower(end($ext_arr));
        if (!in_array($ext, $permitted_file_ext)) {
            return FALSE;
        }
        return TRUE;
    }

    public function loadEncryptLibrary()
    {
        if (class_exists("Ci_encrypt", FALSE)) {
            $this->CI->ci_encrypt = new Ci_encrypt();
        } else {
            $this->CI->load->library("ci_encrypt");
        }
    }

    public function encryptDataMethod($data = '', $method = 'cit')
    {
        $enc_data = '';
        switch ($method) {
            case 'base64':
                if (trim($data) != "") {
                    $enc_data = base64_encode($data);
                }
                break;
            case 'password_hash':
                if ($data == "*****" || trim($data) == "") {
                    $enc_data = FALSE;
                } else {
                    $enc_data = password_hash($data, PASSWORD_DEFAULT);
                }
                break;
            case 'bcrypt':
                if ($data == "*****" || trim($data) == "") {
                    $enc_data = FALSE;
                } else {
                    $enc_data = password_hash($data, PASSWORD_BCRYPT);
                }
                break;
            case 'md5':
            case 'sha1':
            case 'sha256':
            case 'sha512':
                if ($data == "*****" || trim($data) == "") {
                    $enc_data = FALSE;
                } else {
                    $enc_data = hash($method, $data);
                }
                break;
            default:
                $this->CI->general->loadEncryptLibrary();
                if (trim($data) != "") {
                    $enc_data = $this->CI->ci_encrypt->dataEncrypt($data);
                }
                break;
        }
        return $enc_data;
    }

    public function decryptDataMethod($data = '', $method = 'cit')
    {
        $dec_data = '';
        switch ($method) {
            case 'base64':
                if (trim($data) != "") {
                    $dec_data = base64_decode($data);
                }
                break;
            case 'password_hash':
            case 'bcrypt':
            case 'md5':
            case 'sha1':
            case 'sha256':
            case 'sha512':
                if (trim($data) != "") {
                    $dec_data = "*****";
                }
                break;
            default:
                $this->CI->general->loadEncryptLibrary();
                if (trim($data) != "") {
                    $dec_data = $this->CI->ci_encrypt->dataDecrypt($data);
                }
                break;
        }
        return $dec_data;
    }

     public function verifyEncryptData($data = '', $enc_data = '', $method = 'cit')
    {
        switch ($method) {
            case 'base64':
                $dec_data = base64_decode($enc_data);
                if ($data == $dec_data) {
                    return TRUE;
                }
                break;
            case 'password_hash':
                if (password_verify($data, $enc_data)) {
                    return TRUE;
                }
                break;
            case 'bcrypt':
                if (password_verify($data, $enc_data)) {
                    return TRUE;
                }
                break;
            case 'md5':
            case 'sha1':
            case 'sha256':
            case 'sha512':
                if (hash($method, $data) == $enc_data) {
                    return TRUE;
                }
                break;
            default:
                $this->CI->general->loadEncryptLibrary();
                if (trim($data) != "" && trim($enc_data) != "") {
                    $dec_data = $this->CI->ci_encrypt->dataDecrypt($enc_data);
                    if ($data == $dec_data) {
                        return TRUE;
                    }
                }
                break;
        }
        return FALSE;
    }

    public function isAdminEncodeActive()
    {
        $this->loadEncryptLibrary();
        $is_active = $this->CI->ci_encrypt->isEncryptionActive();
        return $is_active;
    }

    public function getAdminEncodeURL($url = '', $ret_whole_url = 0, $is_url = FALSE)
    {
        $this->loadEncryptLibrary();
        $url = str_replace("\/", "/", $url);
        $url_t = $tURL = $url;
        $admin_url = $this->CI->config->item("admin_url");
        if ($this->CI->config->item("ADMIN_URL_ENCRYPTION") == 'Y') {
            if ($url != "") {
                $url_t = str_replace($admin_url, "", $url);
                if ($url_t != "") {
                    $url_t = $this->CI->ci_encrypt->encrypt($url_t, $is_url);
                }
            }
        }
        if ($ret_whole_url == 1) {
            $tURL = $admin_url . $url_t;
        } else {
            $tURL = $url_t;
        }
        return $tURL;
    }

    public function getAdminDecodeURL($url = '', $ret_whole_url = 0, $is_url = FALSE)
    {
        $this->loadEncryptLibrary();
        $url_t = $tURL = $url;
        $admin_url = $this->CI->config->item("admin_url");
        if ($this->CI->config->item("ADMIN_URL_ENCRYPTION") == 'Y') {
            if ($url != "") {
                $url_t = str_replace($admin_url, "", $url);
                if ($url_t != "") {
                    $url_t = $this->CI->ci_encrypt->decrypt($url_t, $is_url);
                }
            }
        }
        if ($ret_whole_url == 1) {
            $tURL = $admin_url . $url_t;
        } else {
            $tURL = $url_t;
        }
        return $tURL;
    }

    public function getCustomEncryptMode($ret = FALSE)
    {
        $ret_arr['Add'] = $this->getAdminEncodeURL("Add");
        $ret_arr['View'] = $this->getAdminEncodeURL("View");
        $ret_arr['Update'] = $this->getAdminEncodeURL("Update");
        $ret_arr['Search'] = $this->getAdminEncodeURL("Search");
        if ($ret === TRUE) {
            $ret_arr_enc = $ret_arr;
        } else {
            $ret_arr_enc = json_encode($ret_arr);
        }
        return $ret_arr_enc;
    }

    public function getCustomEncryptURL($code = '', $ret = FALSE)
    {
        $general_url = array();

        $general_url['dashboard_index'] = "dashboard/dashboard/sitemap";
        $general_url['dashboard_sitemap'] = "dashboard/dashboard/sitemap";
        $general_url['dashboard_sequence'] = "dashboard/dashboard/dashboard_sequence_a";
        $general_url['filter_dashboard'] = "dashboard/dashboard/filter_dashboard_block";
        $general_url['autoload_dashboard'] = "dashboard/dashboard/autoload_dashboard_block";

        $general_url['general_navigation_index'] = "general/navigation/index";
        $general_url['general_navigation_flush'] = "general/navigation/flush_record";
        $general_url['general_clear_query_log'] = "general/navigation/clear_query_log";
        $general_url['general_clear_query_cache'] = "general/navigation/clear_query_cache";
        $general_url['general_query_log_page'] = "general/navigation/query_log_page";
        $general_url['general_query_log'] = "general/navigation/query_log";
        $general_url['general_error_log'] = "general/navigation/error_log";
        $general_url['general_preferences_change'] = "general/navigation/change_preferences";
        $general_url['general_grid_render_action'] = "general/gridactions/grid_render_action";
        $general_url['general_grid_submit_action'] = "general/gridactions/grid_submit_action";
        $general_url['general_grid_save_search_action'] = "general/gridactions/grid_save_search";
        $general_url['general_grid_update_search_action'] = "general/gridactions/grid_update_search";
        $general_url['general_grid_delete_search_action'] = "general/gridactions/grid_delete_search";
        $general_url['general_form_save_draft_action'] = "general/gridactions/form_save_draft";

        $general_url['general_language_change'] = "general/multilingual/language_change";
        $general_url['general_language_conversion'] = "general/multilingual/language_conversion";

        $general_url['user_login_entry'] = "user/login/entry";
        $general_url['user_login_logout'] = "user/login/logout";

        $general_url['user_switch_group'] = "user/login/switch_group";
        $general_url['user_sess_expire'] = "user/login/sess_expire";
        $general_url['user_notify_events'] = "user/login/notify_events";
        $general_url['user_manifest'] = "user/login/manifest";
        $general_url['user_tbcontent'] = "user/login/tbcontent";
        $general_url['setup_google_auth'] = "user/login/setup_google_auth";
        $general_url['verify_google_auth'] = "user/login/verify_google_auth";
        $general_url['setup_multi_groups'] = "user/login/setup_multi_groups";
        $general_url['update_multi_groups'] = "user/login/update_multi_groups";
        $general_url['send_message'] = "user/login/send_message";

        $general_url['systememail_variables'] = "tools/systememails/getVariables";
        $general_url['resend_email'] = "notifications/notifications/resendMail";
        $general_url['spotlight_search'] = "tools/shortcuts/spotlightSearch";
        $general_url['admin_notifications_list'] = "user/admin_notifications/index";
        $general_url['admin_notifications_add'] = "user/admin_notifications/add";
        $general_url['admin_notifications_update'] = "user/admin_notifications/notification_update";
        $general_url['desktop_notifications_add'] = "notifications/notifications/viewContent";
        $general_url['desktop_notifications_list'] = "notifications/notifications/index";

        if ($ret === TRUE) {
            $general_url['otp_authentication'] = "user/login/otp_authentication";
            $general_url['google_verification'] = "user/login/google_verification";
            $general_url['otp_verification'] = "user/login/otp_verification";
            $general_url['resend_otp'] = "user/login/resend_otp";
            $general_url['try_another_way'] = "user/login/try_another_way";

            $general_url['switch_account'] = "user/login/switch_account";
            $general_url['return_account'] = "user/login/return_account";
            $general_url['user_admin_index'] = "user/admin/index";
            $general_url['user_admin_add'] = "user/admin/add";
            $general_url['user_login_index'] = "user/login/index";
            $general_url['user_login_entry_a'] = "user/login/entry_a";

            $general_url['user_forgot_password_action'] = "user/login/forgot_password_action";
            $general_url['user_changepassword'] = "user/login/changepassword";
            $general_url['user_changepassword_action'] = "user/login/changepassword_action";
            $general_url['user_resetpassword'] = "user/login/resetpassword";
            $general_url['user_resetpassword_action'] = "user/login/resetpassword_action";

            $general_url['settings_index'] = "tools/settings/index";
            $general_url['settings_action'] = "tools/settings/settings_action";
            $general_url['settings_upload_files'] = "tools/settings/uploadSettingFiles";

            $general_url['bulkmail_action'] = "tools/bulkemail/bulkmail_action";
            $general_url['bulkmail_sendto'] = "tools/bulkemail/ajax_bulk_mail";
            $general_url['bulkmail_variables'] = "tools/bulkemail/ajax_bulk_temp_variables";
            //Notify realtes urls
            $general_url['pushnotify_action'] = "tools/pushnotify/pushnotify_action";
            $general_url['pushnotify_variables'] = "tools/pushnotify/pushnotify_variables";
            $general_url['pushnotify_module_fields'] = "tools/pushnotify/pushnotify_module_fields";
            $general_url['pushnotify_select_fields'] = "tools/pushnotify/pushnotify_select_fields";
            //Backup related urls
            $general_url['backup_index'] = "tools/backup/index";
            $general_url['backup_table_backup'] = "tools/backup/table_backup";
            $general_url['backup_backup_form_a'] = "tools/backup/backup_form_a";
            $general_url['backup_create_backup'] = "tools/backup/create_backup";
            $general_url['backup_backup_delete'] = "tools/backup/backup_delete";
            $general_url['backup_backup_download_a'] = "tools/backup/backup_download_a";
            //Parse related urls
            $general_url['parse_index'] = "tools/parse/index";
            $general_url['parse_sync_data'] = "tools/parse/sync_data";
            $general_url['parse_sync_details'] = "tools/parse/sync_details";
            $general_url['parse_log_details'] = "tools/parse/log_details";
            //Import related urls
            $general_url['import_index'] = "tools/import/index";
            $general_url['import_upload'] = "tools/import/upload";
            $general_url['import_media'] = "tools/import/media";
            $general_url['import_read'] = "tools/import/read";
            $general_url['import_process'] = "tools/import/process";
            $general_url['import_info'] = "tools/import/import_info";
            $general_url['import_valid'] = "tools/import/import_valid";
            $general_url['import_history'] = "tools/import/import_history";
            $general_url['import_media_event'] = "tools/import/media_event";
            $general_url['import_media_sample'] = "tools/import/media_sample";
            $general_url['import_gdrive_manager'] = "tools/import/gdrive_manager";
            $general_url['import_gdrive_config'] = "tools/import/gdrive_config";
            $general_url['import_gdrive_auth'] = "tools/import/gdrive_auth";
            $general_url['import_get_gdrive_data'] = "tools/import/get_gdrive_data";
            $general_url['import_save_gdrive_data'] = "tools/import/save_gdrive_data";
            $general_url['import_get_weburl_data'] = "tools/import/get_weburl_data";
            $general_url['import_dropbox_auth'] = "tools/import/dropbox_auth";
            $general_url['import_get_dropbox_data'] = "tools/import/get_dropbox_data";
            $general_url['import_save_dropbox_data'] = "tools/import/save_dropbox_data";
            //Menu related urls
            $general_url['menu_index'] = "tools/menu/index";
            $general_url['menu_status_list'] = "tools/menu/getStatusList";
            $general_url['menu_capability_list'] = "tools/menu/getCapabilityList";
            $general_url['menu_save_menu_data'] = "tools/menu/inlineSaveMenuData";
        }
        $ret_arr = array();
        foreach ($general_url as $key => $val) {
            if (is_array($code) && count($code) > 0) {
                if (in_array($key, $code)) {
                    $ret_arr[$key] = $this->getAdminEncodeURL($val, 0, TRUE);
                }
            } elseif ($code != "") {
                if ($code == $key) {
                    $ret_arr[$key] = $this->getAdminEncodeURL($val, 0, TRUE);
                }
            } else {
                $ret_arr[$key] = $this->getAdminEncodeURL($val, 0, TRUE);
            }
        }
        if ($ret === TRUE) {
            $ret_arr_enc = $ret_arr;
        } else {
            $ret_arr_enc = json_encode($ret_arr);
        }
        return $ret_arr_enc;
    }

    public function getGeneralEncryptList($folder_name = '', $module_name = '')
    {
        $folder_name = trim($folder_name);
        $module_name = trim($module_name);
        $func_enc_arr = array();
        if ($folder_name == "" || $module_name == "") {
            return $func_enc_arr;
        }
        $func_dec_arr = $this->getGeneralEncryptFunc();
        if (!is_array($func_dec_arr) || count($func_dec_arr) == 0) {
            return $func_enc_arr;
        }
        $enc_module_str = $folder_name . "/" . $module_name . "/";
        foreach ($func_dec_arr as $key => $val) {
            $func_enc_arr[$key] = $this->getAdminEncodeURL($enc_module_str . $val);
        }
        return $func_enc_arr;
    }

    public function getGeneralEncryptFunc()
    {
        $gen_arr = array(
            "index" => "index",
            "listing" => "listing",
            "export" => "export",
            "add" => "add",
            "add_action" => "addAction",
            "inline_edit_action" => "inlineEditAction",
            "form_edit_action" => "formEditAction",
            "get_list_options" => "getListOptions",
            "get_form_options" => "getFormOptions",
            "get_source_options" => "getSourceOptions",
            "parent_source_options" => "parentSourceOptions",
            "get_chosen_auto_complete" => "getChosenAutoComplete",
            "get_token_auto_complete" => "getTokenAutoComplete",
            "get_search_auto_complete" => "getSearchAutoComplete",
            "get_left_search_content" => "getLeftSearchContent",
            "download_list_file" => "downloadListFile",
            "upload_form_file" => "uploadFormFile",
            "download_form_file" => "downloadFormFile",
            "delete_form_file" => "deleteFormFile",
            "get_tab_wise_block" => "getTabWiseBlock",
            "save_tab_wise_block" => "saveTabWiseBlock",
            "get_self_switch_to" => "getSelfSwitchTo",
            "get_parent_switch_to" => "getParentSwitchTo",
            "child_data_add" => "childDataAdd",
            "child_data_save" => "childDataSave",
            "child_data_delete" => "childDataDelete",
            "get_subgrid_block" => "getSubgridBlock",
            "get_detail_view_block" => "getDetailViewBlock", //NR
            "expand_subgrid_view" => "expandSubgridView", //NR
            "expand_subgrid_list" => "expandSubgridList", //NR
            "top_detail_view" => "topDetailView", //NR
            "get_relation_module" => "getRelationModule", //NR
            "add_action_popup" => "addActionPopup",
            "print_record" => "printRecord",
            "print_listing" => "printListing",
            "process_configuration" => "processConfiguration", //NR
        );
        return $gen_arr;
    }

    public function writeMultilingualStaticPages($data_arr = array(), $unique_name = '')
    {
        $prlang = $this->CI->config->item("PRIME_LANG");
        $exlang_arr = $this->CI->config->item("OTHER_LANG");
        $unique_data = $this->CI->input->get_post($unique_name);
        $this->writeStaticPageContent($data_arr['vPageCode'], $prlang, $unique_data);

        if (is_array($exlang_arr) && count($exlang_arr) > 0) {
            $lang_data = $this->CI->input->get_post("lang" . $unique_name);
            for ($i = 0; $i < count($exlang_arr); $i++) {
                if ($exlang_arr[$i]) {
                    $this->writeStaticPageContent($data_arr['vPageCode'], $exlang_arr[$i], $lang_data[$exlang_arr[$i]]);
                }
            }
        }
        return TRUE;
    }

    public function writeStaticPageContent($page_code = '', $lang_code = '', $data = '', $binary = FALSE)
    {
        $page_name = $page_code . '_' . strtolower($lang_code) . '.tpl';
        $file_path = $this->CI->config->item('static_pages_path') . $page_name;
        $handle = fopen($file_path, 'w');
        if ($binary) {
            fwrite($handle, $data);
        } else {
            fputs($handle, $data);
        }
        fclose($handle);
        return TRUE;
    }

    public function getCustomAddEditPageURL($module_name = '', $normal_url = '', $custom_url = '')
    {
        if (trim($custom_url) == "") {
            return $normal_url;
        } else {
            $return_arr = $this->converNavigationVars($custom_url, TRUE);
            if (trim($return_arr['module']) == "") {
                return $normal_url;
            }
            $params_str = '';
            if (isset($return_arr['params']) && trim($return_arr['params']) != '') {
                $params_str = '|' . trim($return_arr['params']);
            }
            $return_url = $this->getAdminEncodeURL($return_arr['module']) . $params_str;
        }
        return $return_url;
    }

    public function getModuleListDropdown()
    {
        $this->CI->load->library('ci_misc');
        $ret_arr = array();
        $lang_dashboard = $this->CI->lang->line("LANGUAGELABELS_DASHBOARD");
        $lang_generic = $this->CI->lang->line("LANGUAGELABELS_GENERIC");
        $lang_action = $this->CI->lang->line("LANGUAGELABELS_ACTION");
        $lang_front = $this->CI->lang->line("LANGUAGELABELS_FRONT");
        $db_app_list[$lang_dashboard] = array('Dashboard' => $lang_dashboard);
        $db_app_list[$lang_generic] = array('Generic' => $lang_generic);
        $db_app_list[$lang_action] = array('Action' => $lang_action);
        $db_app_list[$lang_front] = array('Front' => $lang_front);
        $module_array = $this->CI->ci_misc->getModuleArray();
        $db_app_list[$this->CI->lang->line("LANGUAGELABELS_MODULE")] = $module_array;
        if (is_array($db_app_list) && count($db_app_list) > 0) {
            foreach ($db_app_list as $key => $value) {
                foreach ($value as $inn_key => $inn_val) {
                    $ret_arr[] = array(
                        "id" => $inn_key,
                        "val" => $inn_val,
                        "grpVal" => $key
                    );
                }
            }
        }
        return $ret_arr;
    }

    public function checkUserAccountStatus()
    {
        if ($this->CI->config->item('ACCOUNT_CHECK_ENABLE')) {
            $user_acc_status = $this->CI->config->item('USER_ACC_STATUS_INFO');
            if (in_array($user_acc_status, array("Expired", "Closed"))) {
                $login_arr = $this->getCustomEncryptURL("user_login_entry", TRUE);
                $redir_uri = $this->CI->config->item("admin_url") . $login_arr["user_login_entry"] . "?_=" . time();
                if ($user_acc_status == "Closed") {
                    $err_msg = $this->processMessageLabel('ACTION_YOUR_ACCOUNT_CLOSED_PLEASE_TRY_AGAIN_OR_CONTACT_ADMINISTRATOR');
                } else {
                    $err_msg = $this->processMessageLabel('ACTION_YOUR_ACCOUNT_EXPIRED_PLEASE_TRY_AGAIN_OR_CONTACT_ADMINISTRATOR');
                }
                $this->CI->session->set_flashdata('failure', $err_msg);
                redirect($redir_uri);
            }
        }
        return;
    }

    public function makeNotificationLink($value = '', $id = '', $data = array())
    {
        if ($data['men_notification_type'] == 'EmailNotify' || $data['men_notification_type'] == 'SMS') {
            $ret_str = '<a title="Click Here" href="' . $this->CI->config->item('admin_url') . '#' . $this->getAdminEncodeURL("notifications/notifications/viewContent") . '|id|' . $this->getAdminEncodeURL($id) . '" class="fancybox-popup"> Click Here </a>';
            return $ret_str;
        } else {
            return $value;
        }
    }

    public function makeDBChangesLink($value = '', $id = '', $data = array())
    {
        if (!empty($value)) {
            $ret_str = '<a title="Click Here" href="' . $this->CI->config->item('admin_url') . '#' . $this->getAdminEncodeURL("notifications/db_change_log/viewChanges") . '|id|' . $this->getAdminEncodeURL($id) . '" class="fancybox-popup"> Click Here </a>';
            return $ret_str;
        } else {
            return $value;
        }
    }

    public function makeAPIParamsLink($value = '', $id = '', $data = array())
    {
        if (!empty($value)) {
            $ret_str = '<a title="Click Here" href="' . $this->CI->config->item('admin_url') . '#' . $this->getAdminEncodeURL("notifications/api_access_log/viewParams") . '|id|' . $this->getAdminEncodeURL($id) . '" class="fancybox-popup"> Click Here </a>';
            return $ret_str;
        } else {
            return $value;
        }
    }

    public function getReleaseNotesVersion($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        $ret_val = $value;
        if ($mode == "Add") {
            $this->CI->db->select("mrn.vVersionNumber AS mam_version_number");
            $this->CI->db->from("mod_release_notes AS mrn");
            $this->CI->db->order_by('mrn.iReleaseNotesId', 'DESC');
            $db_rec_obj = $this->CI->db->get();
            $db_rec = is_object($db_rec_obj) ? $db_rec_obj->result_array() : array();

            $version_id = $db_rec[0]['mam_version_number'];
            $version_arr = explode(".", $version_id);
            $last_digit = $version_arr[count($version_arr) - 1];
            $version_arr[count($version_arr) - 1] = $last_digit + 1;
            $new_version_id = implode(".", $version_arr);
            $ret_val = $new_version_id;
        }
        return $ret_val;
    }

    public function getMobileApplicationFile($mode = '', $value = '', $data = array(), $id = '', $field_name = '', $field_id = '')
    {
        $ret_val = $value;
        if ($mode == "Update") {
            if ($data['mav_application_url'] != "") {
                $ret_val = basename($data['mav_application_url']);
            }
        }
        return $ret_val;
    }

    public function updateMobileApplicationURL($mode = '', $id = '', $parID = '')
    {
        if ($this->CI->input->get_post("mav_version_type") == "File") {
            $application_file = $this->CI->input->get_post("sys_application_file");
            if ($application_file != "") {
                $mobile_app_path = $this->CI->config->item('upload_path') . "mobile_applications" . DS . $application_file;
                $mobile_app_url = $this->CI->config->item('upload_url') . "mobile_applications" . "/" . $application_file;
                if (is_file($mobile_app_path)) {
                    $update_arr["vApplicationUrl"] = $mobile_app_url;
                    $this->CI->db->where("iApplicationVersionId", $id);
                    $res = $this->CI->db->update("mod_application_version", $update_arr);
                }
            }
        }
        $ret_arr['success'] = true;
        return $ret_arr;
    }
    
    public function accessKeyGenerator($mode = '', $value = '', $data = array(), $id = '',$field_name = '', $field_id = ''){
        if($mode == "Add"){
            $alphabet = time();
            $alphabet .= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $key = array();
            $alphaLength = strlen($alphabet) - 1;
            for ($i = 0; $i < 24; $i++) {
                $n = rand(0, $alphaLength);
                $key[] = $alphabet[$n];
                $ret_val = implode($key);
            }
        }else{
            $ret_val = $value;
        }

        return $ret_val;
    }
    
    public function getAppCacheStatus()
    {
        $ret_val = "No";
        if ($this->CI->session->userdata('iAdminId') != '' && $this->CI->config->item("ADMIN_ASSETS_APPCACHE") == 'Y') {
            $cookie_str = $this->CI->ci_local->read($this->getMD5EncryptString("AppCache"), -1);
            $cookie_js = $this->CI->ci_local->read($this->getMD5EncryptString("AppCacheJS"), -1);
            $cookie_css = $this->CI->ci_local->read($this->getMD5EncryptString("AppCacheCSS"), -1);
            $common_js_dir = $this->CI->config->item('js_cache_path') . "compiled/" . $cookie_js . "/";
            $common_css_dir = $this->CI->config->item('css_cache_path') . "compiled/" . $cookie_css . "/";
            if ($cookie_str == "Yes" && is_dir($common_js_dir) && is_dir($common_css_dir)) {
                if (is_file($common_js_dir . "main_common.js") && is_file($common_css_dir . "main_common.css")) {
                    $ret_val = "Yes";
                }
            }
        }

        return $ret_val;
    }

    public function getJSLanguageLables()
    {
        return $this->CI->js->js_labels_src();
    }

    public function getServerThemeArr()
    {
        $this->CI->load->library("ci_theme");
        $this->CI->ci_theme->setServerThemeSettings();
        $theme_settings_arr = $this->CI->ci_theme->getServerThemeSettings();
        return $theme_settings_arr;
    }

    public function getClientThemeJSON($flag = FALSE)
    {
        $this->CI->load->library("ci_theme");
        $this->CI->ci_theme->setClientThemeSettings();
        $theme_settings_arr = $this->CI->ci_theme->getClientThemeSettings();
        if ($flag === TRUE) {
            return $theme_settings_arr;
        } else {
            return json_encode($theme_settings_arr);
        }
    }

    public function getAdminPHPFormats($type = '')
    {
        $this->CI->load->library('filter');
        switch ($type) {
            case 'date':
                $fmt = $this->CI->config->item('ADMIN_DATE_FORMAT');
                break;
            case 'date_and_time':
                $fmt = $this->CI->config->item('ADMIN_DATE_TIME_FORMAT');
                break;
            case 'time':
                $fmt = $this->CI->config->item('ADMIN_TIME_FORMAT');
                break;
            case 'phone':
                $phone_format = $this->CI->config->item('ADMIN_PHONE_FORMAT');
                $fmt = (trim($phone_format) != '') ? $phone_format : "(999) 999-9999";
                break;
        }
        if (in_array($type, array("date", "date_and_time", "time"))) {
            $format = _date_time_php_format($type, $fmt);
        } else {
            $format = $fmt;
        }
        return $format;
    }

    public function getAdminJSFormats($type = '', $key = '')
    {
        $this->CI->load->library('filter');
        switch ($type) {
            case 'date':
                $fmt_arr = _date_time_js_format('date', $this->CI->config->item('ADMIN_DATE_FORMAT'));
                break;
            case 'date_and_time':
                $fmt_arr = _date_time_js_format('date_and_time', $this->CI->config->item('ADMIN_DATE_TIME_FORMAT'));
                break;
            case 'time':
                $fmt_arr = _date_time_js_format('time', $this->CI->config->item('ADMIN_TIME_FORMAT'));
                break;
        }
        if ($key != '') {
            return $fmt_arr[$key];
        } else {
            return $fmt_arr;
        }
    }

    public function getAdminJSMoments($type = '', $key = '')
    {
        $this->CI->load->library('filter');
        if ($key != "") {
            switch ($type) {
                case 'date':
                    $fmt_arr = _date_time_js_format('date', $this->CI->config->item('ADMIN_DATE_FORMAT'));
                    break;
                case 'date_and_time':
                    $fmt_arr = _date_time_js_format('date_and_time', $this->CI->config->item('ADMIN_DATE_TIME_FORMAT'));
                    break;
                case 'time':
                    $fmt_arr = _date_time_js_format('time', $this->CI->config->item('ADMIN_TIME_FORMAT'));
                    break;
            }
            $fmt = $fmt_arr[$key];
        } else {
            switch ($type) {
                case 'date':
                    $fmt = _date_time_momentjs_format('date', $this->CI->config->item('ADMIN_DATE_FORMAT'));
                    break;
                case 'date_and_time':
                    $fmt = _date_time_momentjs_format('date_and_time', $this->CI->config->item('ADMIN_DATE_TIME_FORMAT'));
                    break;
                case 'time':
                    $fmt = _date_time_momentjs_format('time', $this->CI->config->item('ADMIN_TIME_FORMAT'));
                    break;
            }
        }
        return $fmt;
    }

    public function getAdminTPLFormats($flag = FALSE)
    {
        $this->CI->load->library('filter');
        $phone_format = $this->CI->config->item('ADMIN_PHONE_FORMAT');
        $thousand_sep = $this->CI->config->item('ADMIN_THOUSAND_SEPARATOR');
        $decimal_sep = $this->CI->config->item('ADMIN_DECIMAL_SEPARATOR');
        $decimal_plc = $this->CI->config->item('ADMIN_DECIMAL_PLACES');
        $currency_pre = $this->CI->config->item('ADMIN_CURRENCY_PREFIX');
        $currency_suf = $this->CI->config->item('ADMIN_CURRENCY_SUFFIX');
        $image_extensions = $this->CI->config->item('IMAGE_EXTENSION_ARR');

        $ret_arr = array();

        $ret_arr['date']['format'] = _date_time_js_format('date', $this->CI->config->item('ADMIN_DATE_FORMAT'));
        $ret_arr['date']['moment'] = _date_time_momentjs_format('date', $this->CI->config->item('ADMIN_DATE_FORMAT'));

        $ret_arr['date_and_time']['format'] = _date_time_js_format('date_and_time', $this->CI->config->item('ADMIN_DATE_TIME_FORMAT'));
        $ret_arr['date_and_time']['moment'] = _date_time_momentjs_format('date_and_time', $this->CI->config->item('ADMIN_DATE_TIME_FORMAT'));

        $ret_arr['time']['format'] = _date_time_js_format('time', $this->CI->config->item('ADMIN_TIME_FORMAT'));
        $ret_arr['time']['moment'] = _date_time_momentjs_format('time', $this->CI->config->item('ADMIN_TIME_FORMAT'));

        $ret_arr['phone_format'] = (trim($phone_format) != '') ? $phone_format : "(999) 999-9999";
        $ret_arr['thousand_seperator'] = ($thousand_sep == 'space' || $thousand_sep == 'none') ? ($thousand_sep == 'none' ? '' : ' ') : ',';
        $ret_arr['decimal_seperator'] = $decimal_sep == 'comma' ? ',' : '.';
        $ret_arr['decimal_places'] = $decimal_plc != '' ? $decimal_plc : '2';
        $ret_arr['currency_prefix'] = $currency_pre != '' ? $currency_pre : '';
        $ret_arr['currency_suffix'] = $currency_suf != '' ? $currency_suf : '';
        $ret_arr['image_extensions'] = is_array($image_extensions) ? implode(",", $image_extensions) : $image_extensions;

        if ($flag === TRUE) {
            return $ret_arr;
        } else {
            return json_encode($ret_arr);
        }
    }

    public function getDateTimeDropdownLabel($format = '', $value = '')
    {
        $return_val = _date_time_label_format($format, $value);
        return $return_val;
    }

    public function getExpressionEvalObject()
    {
        if (is_object($this->_expression_eval)) {
            return $this->_expression_eval;
        }
        require_once($this->CI->config->item('third_party') . 'eqEOS/eos.class.php');
        $this->_expression_eval = new eqEOS();
        return $this->_expression_eval;
    }

    public function evaluateMathExpression($expression = '')
    {
        if (trim($expression) == '') {
            return $expression;
        }
        $eos = $this->getExpressionEvalObject();
        $value = $eos->solveIF($expression);
        return $value;
    }

    public function getResizedLogoImage($path = '', $url = '')
    {
        if (!is_file($path) && !filter_var($path, FILTER_VALIDATE_URL)) {
            return FALSE;
        }
        $logo_max_width = 220;
        $logo_max_height = 57;
        $logo_bg_color = '01bbe4';
        $logo_image_info = getimagesize($path);
        $logo_height = $logo_width = '';
        if (intval($logo_image_info[0]) > $logo_max_width) {
            $logo_width = $logo_max_width;
        }
        if (intval($logo_image_info[1]) > $logo_max_height) {
            $logo_height = $logo_max_height;
        }
        if ($logo_height == '' && $logo_width == '') {
            return $url;
        }
        $logo_image_url = $this->resize_image($url, $logo_width, $logo_height, $logo_bg_color);
        return $logo_image_url;
    }

    public function getLangRequestValue()
    {
        $config_lang = $this->CI->config->item("SYSTEM_LANG_PARAM");
        $config_lang = ($config_lang != "") ? $config_lang : "lang_id";
        $lang_id = $this->CI->input->get_post($config_lang, TRUE);
        $lang_code = ($lang_id != "") ? $lang_id : "EN";
        return $lang_code;
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
        if (!is_array($fields) || count($fields) == 0) {
            return;
        }
        foreach ($fields as $key => $val) {
            if (!isset($val['field'])) {
                if ($group == 'OR') {
                    if (is_array($val)) {
                        $val = (count($val) > 0) ? $val : array('');
                        $this->CI->db->or_where_in($key, $val);
                    } else {
                        $this->CI->db->or_where($key, $val);
                    }
                } else {
                    if (is_array($val)) {
                        $val = (count($val) > 0) ? $val : array('');
                        $this->CI->db->where_in($key, $val);
                    } else {
                        $this->CI->db->where($key, $val);
                    }
                }
            } else {
                $field = $val['field'];
                $data = isset($val['value']) ? $val['value'] : FALSE;
                $oper = (isset($val['oper'])) ? $val['oper'] : "eq";
                $escape = (isset($val['escape']) && $val['escape'] === TRUE) ? FALSE : NULL;
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
                            if ($val['type'] == "date_and_time") {
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
                            if ($val['type'] == "date_and_time") {
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
    }

    public function addJoinTables($join_tables = array(), $type = 'AR')
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
            $join_table_name = $val['join_table'];
            $join_table_alias = $val['join_alias'];
            $join_field_name = $val['join_field'];
            $main_table_alias = $val['main_alias'];
            $main_field_name = $val['main_field'];
            $join_type = $val['join_type'];
            $extra_where = trim($val['extra_where']);

            $join_table_name_pro = $this->CI->db->protect($join_table_name);
            $join_table_alias_pro = $this->CI->db->protect($join_table_alias);
            $join_field_name_pro = $this->CI->db->protect($join_field_name);
            $main_table_alias_pro = $this->CI->db->protect($main_table_alias);
            $main_field_name_pro = $this->CI->db->protect($main_field_name);

            if ($extra_where != '') {
                $is_and_separator = substr($extra_where, 0, 4);
                $is_or_separator = substr($extra_where, 0, 3);
                if (strtoupper($is_or_separator) == "OR ") {
                    $extra_where = ltrim($extra_where, $is_or_separator);
                    $extra_operator = "OR";
                } else {
                    if (strtoupper($is_and_separator) == "AND ") {
                        $extra_where = ltrim($extra_where, $is_and_separator);
                    }
                    $extra_operator = "AND";
                }
                $extra_where = ' ' . $extra_operator . ' ' . $extra_where;
            }
            if ($type == "NR") {
                $join_condition = $join_table_alias_pro . "." . $join_field_name_pro . " = " . $main_table_alias_pro . "." . $main_field_name_pro . $extra_where;
                $join_type = (in_array($join_type, array("left", "right"))) ? strtoupper($join_type) . " JOIN" : "INNER JOIN";
                if (is_array($in_tables)) {
                    if (in_array($join_table_alias, $in_tables)) {
                        $ret_joins[] = $join_type . ' ' . $join_table_name_pro . ' AS ' . $join_table_alias_pro . ' ON ' . $join_condition;
                    }
                } else {
                    $ret_joins[] = $join_type . ' ' . $join_table_name_pro . ' AS ' . $join_table_alias_pro . ' ON ' . $join_condition;
                }
            } elseif ($type == "AR") {
                $join_type = (in_array($join_type, array("left", "right"))) ? strtolower($join_type) : "inner";
                if ($extra_where != "") {
                    $join_condition = $join_table_alias_pro . "." . $join_field_name_pro . " = " . $main_table_alias_pro . "." . $main_field_name_pro . $extra_where;
                    $escape = TRUE;
                } else {
                    $join_condition = $join_table_alias . "." . $join_field_name . " = " . $main_table_alias . "." . $main_field_name;
                    $escape = NULL;
                }
                if (is_array($in_tables)) {
                    if (in_array($join_table_alias, $in_tables)) {
                        $this->CI->db->join($join_table_name . ' AS ' . $join_table_alias, $join_condition, $join_type, $escape);
                    }
                } else {
                    $this->CI->db->join($join_table_name . ' AS ' . $join_table_alias, $join_condition, $join_type, $escape);
                }
            }
        }
        if ($type == "NR") {
            $ret_joins_str = implode(" ", $ret_joins);
            return $ret_joins_str;
        }
    }

    public function setAdminModulePath()
    {
        $marr = Modules::$locations;
        if (is_array($marr) && !array_key_exists(APPPATH . 'admin/', $marr)) {
            $this->_hmvc_module_paths[] = $marr;
            $narr = array(
                APPPATH . 'admin/' => '../admin/'
            );
            Modules::$locations = $narr;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function unsetAdminModulePath()
    {
        $marr = Modules::$locations;
        if (is_array($marr) && array_key_exists(APPPATH . 'admin/', $marr)) {
            $narr = end($this->_hmvc_module_paths);
            Modules::$locations = $narr;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function setFrontModulePath()
    {
        $marr = Modules::$locations;
        if (is_array($marr) && !array_key_exists(APPPATH . 'front/', $marr)) {
            $this->_hmvc_module_paths[] = $marr;
            $narr = array(
                APPPATH . 'front/' => '../front/'
            );
            Modules::$locations = $narr;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function unsetFrontModulePath()
    {
        $marr = Modules::$locations;
        if (is_array($marr) && array_key_exists(APPPATH . 'front/', $marr)) {
            $narr = end($this->_hmvc_module_paths);
            Modules::$locations = $narr;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function setWebserviceModulePath()
    {
        $marr = Modules::$locations;
        if (is_array($marr) && !array_key_exists(APPPATH . 'webservice/', $marr)) {
            $this->_hmvc_module_paths[] = $marr;
            $narr = array(
                APPPATH . 'webservice/' => '../webservice/'
            );
            Modules::$locations = $narr;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function unsetWebserviceModulePath()
    {
        $marr = Modules::$locations;
        if (is_array($marr) && array_key_exists(APPPATH . 'webservice/', $marr)) {
            $narr = end($this->_hmvc_module_paths);
            Modules::$locations = $narr;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function setNotificationModulePath()
    {
        $marr = Modules::$locations;
        if (is_array($marr) && !array_key_exists(APPPATH . 'notification/', $marr)) {
            $this->_hmvc_module_paths[] = $marr;
            $narr = array(
                APPPATH . 'notification/' => '../notification/'
            );
            Modules::$locations = $narr;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function unsetNotificationModulePath()
    {
        $marr = Modules::$locations;
        if (is_array($marr) && array_key_exists(APPPATH . 'notification/', $marr)) {
            $narr = end($this->_hmvc_module_paths);
            Modules::$locations = $narr;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getCompanyFavIconURL()
    {
        $upload_server = $this->CI->config->item('FILE_UPLOAD_SERVER_LOCATION');
        $upload_folder = $this->CI->config->item('upload_folder', 'settings_files_config');
        $aws_vars_list = $this->CI->config->item('aws_vars_list', 'settings_files_config');
        $favicon_val = trim($this->CI->config->item('COMPANY_FAVICON'));
        $favicon_url = $this->CI->config->item('images_url') . "favicon-icon.png";
        if ($favicon_val != "") {
            if ($upload_server == "amazon" && in_array("COMPANY_FAVICON", $aws_vars_list)) {
                $aws_path_arr = $this->getAWSServerAccessPathURL($upload_folder);
                $favicon_url = $aws_path_arr['folder_url'] . $favicon_val;
            } elseif (is_file($this->CI->config->item('settings_files_path') . $favicon_val)) {
                $favicon_url = $this->CI->config->item("settings_files_url") . $favicon_val;
            }
        }
        return $favicon_url;
    }

    public function getCompanyLogoURL()
    {
        $upload_server = $this->CI->config->item('FILE_UPLOAD_SERVER_LOCATION');
        $upload_folder = $this->CI->config->item('upload_folder', 'settings_files_config');
        $aws_vars_list = $this->CI->config->item('aws_vars_list', 'settings_files_config');
        $logo_val = trim($this->CI->config->item('COMPANY_LOGO'));
        $logo_url = $logo_path = '';
        if ($logo_val != "") {
            if ($upload_server == "amazon" && in_array("COMPANY_LOGO", $aws_vars_list)) {
                $aws_path_arr = $this->getAWSServerAccessPathURL($upload_folder);
                $logo_url = $aws_path_arr['folder_url'] . $logo_val;
                $logo_path = $logo_url;
            } elseif (is_file($this->CI->config->item('settings_files_path') . $logo_val)) {
                $logo_url = $this->CI->config->item("settings_files_url") . $logo_val;
                $logo_path = $this->CI->config->item("settings_files_path") . $logo_val;
            }
            if ($logo_path != "") {
                $logo_url = $this->getResizedLogoImage($logo_path, $logo_url);
            }
        }
        return $logo_url;
    }

    public function getNoImageURL()
    {
        $upload_server = $this->CI->config->item('FILE_UPLOAD_SERVER_LOCATION');
        $upload_folder = $this->CI->config->item('upload_folder', 'settings_files_config');
        $aws_vars_list = $this->CI->config->item('aws_vars_list', 'settings_files_config');
        $no_image_val = trim($this->CI->config->item('UPLOAD_NOIMAGE'));
        $no_image_url = $this->CI->config->item('images_url') . "noimage.gif";

        if ($no_image_val != "") {
            if ($upload_server == "amazon" && in_array("UPLOAD_NOIMAGE", $aws_vars_list)) {
                $aws_path_arr = $this->getAWSServerAccessPathURL($upload_folder);
                $no_image_url = $aws_path_arr['folder_url'] . $no_image_val;
            } elseif (is_file($this->CI->config->item('settings_files_path') . $no_image_val)) {
                $no_image_url = $this->CI->config->item("settings_files_url") . $no_image_val;
            }
        }
        return $no_image_url;
    }

    public function getNoImagePath()
    {
        $upload_server = $this->CI->config->item('FILE_UPLOAD_SERVER_LOCATION');
        $upload_folder = $this->CI->config->item('upload_folder', 'settings_files_config');
        $aws_vars_list = $this->CI->config->item('aws_vars_list', 'settings_files_config');
        $no_image_val = trim($this->CI->config->item('UPLOAD_NOIMAGE'));
        $no_image_path = $this->CI->config->item('images_path') . "noimage.gif";

        if ($no_image_val != "") {
            if ($upload_server == "amazon" && in_array("UPLOAD_NOIMAGE", $aws_vars_list)) {
                $aws_path_arr = $this->getAWSServerAccessPathURL($upload_folder);
                $no_image_path = $aws_path_arr['folder_url'] . $no_image_val;
            } elseif (is_file($this->CI->config->item('settings_files_path') . $no_image_val)) {
                $no_image_path = $this->CI->config->item("settings_files_path") . $no_image_val;
            }
        }
        return $no_image_path;
    }

    public function getWidgetData($widget = '')
    {
        $data = array();
        try {
            $this->CI->load->module("global/" . $widget);
            if (!is_object($this->CI->$widget)) {
                throw new Exception('Widget controller not found.');
            }
            $method = "index";
            if (!method_exists($this->CI->$widget, $method)) {
                throw new Exception('Index method not found.');
            }
            $data = $this->CI->$widget->$method();
        } catch (Exception $e) {
            
        }
        return $data;
    }

    public function getPagingBlock($api_settings = array(), $pg_crumbs = 5, $pg_ajax = 'Yes')
    {
        require_once ($this->CI->config->item('third_party') . "pagination/Pagination.class.php");
        try {
            $page = isset($api_settings['curr_page']) ? ((int) $api_settings['curr_page']) : 1;
            if ($page < 1) {
                $page = 1;
            }
            $total = $api_settings['count'];
            $url = $this->CI->config->item("site_url") . $this->CI->uri->uri_string;
            // instantiate; set current page; set number of records
            $pagination = (new Pagination());
            $pagination->setTarget($url);
            $pagination->setCurrent($page);
            $pagination->setTotal($total);
            if (isset($api_settings['per_page'])) {
                $pagination->setRPP($api_settings['per_page']);
            }
            if ($pg_crumbs > 0) {
                $pagination->setCrumbs($pg_crumbs);
            }
            if ($pg_ajax == "Yes") {
                $pagination->setAjax(TRUE);
            }
            $pagination->setPrevious("&laquo;");
            $pagination->setNext("&raquo;");
            // grab rendered/parsed pagination markup
            $markup = $pagination->parse();
            return $markup;
        } catch (Exception $e) {
            
        }
    }

    public function getPagingInfo($api_settings = array(), $pg_text = '', $pg_count = 0)
    {
        try {
            $text = $pg_text;
            if (empty($text)) {
                $text = 'Showing {from} to {to} of {total} items';
            }
            $count = $pg_count;
            if (empty($count)) {
                throw new Exception("Current count is required.");
            }
            $total = $api_settings['count'];
            if (empty($total)) {
                throw new Exception("Total count is required.");
            }
            if (!isset($api_settings['per_page'])) {
                throw new Exception("Records per page is required.");
            }

            $page = isset($api_settings['curr_page']) ? ((int) $api_settings['curr_page']) : 1;
            if ($page < 1) {
                $page = 1;
            }
            $per_page = (int) $api_settings['per_page'];

            if ($page > 1) {
                $start = ($page - 1) * $per_page;
                $from = $start + 1;
                $to = $start + $count;
            } else {
                $from = 1;
                $to = $count;
            }
            $info = str_replace(array("{from}", "{to}", "{total}"), array($from, $to, $total), $text);
            return $info;
        } catch (Exception $e) {
            
        }
    }

    public function makeComboDropDown($data_arr = array(), $key = '', $val = '')
    {
        $combo_arr = array();
        if (is_array($data_arr) && count($data_arr) > 0) {
            foreach ((array) $data_arr as $dKey => $dVal) {
                if (isset($dVal[$key]) && isset($dVal[$val])) {
                    $op_id = $dVal[$key];
                    $op_val = $dVal[$val];
                    $combo_arr[$op_id] = $op_val;
                }
            }
        }
        return $combo_arr;
    }

    public function makeGroupDropdown($data_arr = array(), $key = '', $val = '', $opt = '')
    {
        $group_arr = array();
        if (is_array($data_arr) && count($data_arr) > 0) {
            foreach ((array) $data_arr as $dKey => $dVal) {
                if (isset($dVal[$key]) && isset($dVal[$val])) {
                    $op_id = $dVal[$key];
                    $op_val = $dVal[$val];
                    $op_grp = $dVal[$opt];
                    $group_arr[$op_grp][$op_id] = $op_val;
                }
            }
        }
        return $group_arr;
    }

    public function getTempSessionData($key = '')
    {
        $ret_val = '';
        if (stristr($key, ".") !== FALSE) {
            $arr = explode(".", $key);
            $data = $this->CI->session->tempdata($arr[0]);
            if (is_valid_array($data)) {
                array_shift($arr);
                $rep = $data;
                foreach ($arr as $val) {
                    if (!isset($rep[$val])) {
                        $ret_val = '';
                        break;
                    }
                    $rep = $rep[$val];
                    $ret_val = $rep;
                }
            }
        } else {
            $ret_val = $this->CI->session->tempdata($key);
        }
        return $ret_val;
    }

    public function getFlashSessionData($key = '')
    {
        $ret_val = '';
        if (stristr($key, ".") !== FALSE) {
            $arr = explode(".", $key);
            $data = $this->CI->session->flashdata($arr[0]);
            if (is_valid_array($data)) {
                array_shift($arr);
                $rep = $data;
                foreach ($arr as $val) {
                    if (!isset($rep[$val])) {
                        $ret_val = '';
                        break;
                    }
                    $rep = $rep[$val];
                    $ret_val = $rep;
                }
            }
        } else {
            $ret_val = $this->CI->session->flashdata($key);
        }
        return $ret_val;
    }

    public function getSearchPreferences($code = '')
    {
        $search_data_arr = array();
        if (!empty($code) && $this->CI->config->item('GRID_SAVE_SEARCH_ENABLE') == "Y") {
            $this->CI->load->model('general/admin_preferences_model');
            $fields_arr = array("iAdminPreferencesId", "vName", "vSlug", "tValue", "eDefault", "tComment");
            $where_cond = array(
                "iAdminId" => $this->CI->session->userdata("iAdminId"),
                "iGroupId" => $this->CI->session->userdata("iGroupId"),
                "vCode" => $code
            );
            $order_cond = 'iAdminPreferencesId desc';
            $rec_limit = $this->CI->config->item('GRID_SAVE_SEARCH_LIMIT');
            $search_data_set = $this->CI->admin_preferences_model->getData($where_cond, $fields_arr, $order_cond, '', $rec_limit);

            if (is_array($search_data_set) && count($search_data_set) > 0) {
                foreach ($search_data_set as $val) {
                    $search_data_arr[] = array(
                        'id' => $val['iAdminPreferencesId'],
                        'name' => $val['vName'],
                        'slug' => $val['vSlug'],
                        'value' => json_decode($val['tValue'], TRUE),
                        'default' => $val['eDefault'],
                        'comment' => $val['tComment'],
                    );
                }
            }
        }
        return $search_data_arr;
    }

    public function getFormSaveDraftData($module_name = '', $render_data = array())
    {
        if (!empty($module_name)) {
            $this->CI->load->model('general/form_drafts_model');
            $fields_arr = array("iFormDraftsId", "tFormData");
            $where_cond = array(
                "iAdminId" => $this->CI->session->userdata("iAdminId"),
                "iGroupId" => $this->CI->session->userdata("iGroupId"),
                "vModule" => $module_name
            );
            if ($render_data['mode'] == "Update") {
                $where_cond['eMode'] = "Update";
                $where_cond['iRecId'] = $render_data['id'];
            } else {
                $where_cond['eMode'] = "Add";
            }
            $where_cond['eStatus'] = "Active";

            $order_cond = 'iFormDraftsId desc';
            $form_data_set = $this->CI->form_drafts_model->getData($where_cond, $fields_arr, $order_cond, '', 1);

            if (is_array($form_data_set) && !empty($form_data_set[0]['tFormData'])) {
                $form_data_arr = json_decode($form_data_set[0]['tFormData'], TRUE);
                if (is_array($form_data_arr) && count($form_data_arr) > 0) {
                    $render_data['data'] = array_merge($render_data['data'], $form_data_arr);
                    $render_data['draft_uniq_id'] = $form_data_set[0]['iFormDraftsId'];
                }
            }
        }
        return $render_data;
    }

    public function closeFormDraftEntry($module_name = '', $draft_id = '')
    {
        $result = false;
        if (!empty($module_name)) {
            $this->CI->load->model('general/form_drafts_model');
            $where_cond = array(
                "iFormDraftsId" => $draft_id,
                "iAdminId" => $this->CI->session->userdata("iAdminId"),
                "iGroupId" => $this->CI->session->userdata("iGroupId"),
                "vModule" => $module_name
            );
            $update_arr = array(
                "eStatus" => "Closed"
            );
            $result = $this->CI->form_drafts_model->update($update_arr, $where_cond);
        }
        return $result;
    }

    public function getAdminUserGroups()
    {
        $admin_id = $this->CI->session->userdata('iAdminId');
        $group_id = $this->CI->session->userdata('iGroupId');
        $this->CI->db->select('mgm.iGroupId,mgm.vGroupName,mgm.vGroupCode');
        $this->CI->db->join('mod_group_master mgm', 'mgm.iGroupId = mag.iGroupId', 'left');
        $this->CI->db->where('mag.iAdminId', $admin_id);
        $group_arr = $this->CI->db->get('mod_admin_group AS mag');
        $group_data = is_object($group_arr) ? $group_arr->result_array() : array();
        $group_assoc = array();
        if (is_array($group_data) && count($group_data) > 0) {
            $old_group_data = $this->CI->session->userdata('old_group_data');
            if (is_array($old_group_data) && $old_group_data['iGroupId'] > 0) {
                $group_id = $old_group_data['iGroupId'];
            }
            $this->CI->db->select('mgm.iGroupId,mgm.vGroupName,mgm.vGroupCode');
            $this->CI->db->where('mgm.iGroupId', $group_id);
            $default_arr = $this->CI->db->get('mod_group_master AS mgm');
            $default_data = is_object($default_arr) ? $default_arr->result_array() : array();
            if (is_array($default_data) && count($default_data) > 0) {
                $group_assoc[$default_data[0]['iGroupId']] = $default_data[0]['vGroupName'];
            }
            foreach ((array) $group_data as $key => $val) {
                $group_assoc[$val['iGroupId']] = $val['vGroupName'];
            }
        }
        return $group_assoc;
    }

    public function getShortcutsList()
    {
        $shortcuts_arr = array();
        if ($this->CI->config->item('ADMIN_SHORTCUT_ACTIVATE') == 'Y') {
            $this->CI->load->model('tools/shortcuts_model');
            $fields_str = 'vShortcutName as name, vShortcutKey as key, eShortcutType as type, tNavigateJSON as json, eStatus as status, ';
            $extra_cond = "eStatus = 'Active'";
            $shortcuts = $this->CI->shortcuts_model->getData($extra_cond, $fields_str);
            foreach ($shortcuts as $key => $val) {
//            $shortcuts_arr[$val['key']]['key'] = $val['key'];
                $shortcuts_arr[$val['key']]['title'] = $val['name'];
                $json = json_decode($val['json'], true);
                if ($val['type'] == 'General') {
                    $shortcuts_arr[$val['key']]['type'] = 'code';
                    $shortcuts_arr[$val['key']]['value'] = $json[$val['key']]['action_code'];
                } elseif ($val['type'] == 'Menu') {
                    $shortcuts_arr[$val['key']]['type'] = 'url';
                    $shortcuts_arr[$val['key']]['value'] = $this->getAdminEncodeURL($json[$val['key']]['menu_url']);
                } elseif ($val['type'] == 'Module') {
                    $shortcuts_arr[$val['key']]['type'] = 'url';
                    $shortcuts_arr[$val['key']]['value'] = $this->getAdminEncodeURL($json[$val['key']]['module_url']);
                } elseif ($val['type'] == 'Custom') {
                    $shortcuts_arr[$val['key']]['type'] = 'func';
                    $shortcuts_arr[$val['key']]['value'] = $json[$val['key']]['function_name'];
                }
            }
        }
        return json_encode($shortcuts_arr);
    }

    public function getNotifications($type = '')
    {

        $admin_id = $this->CI->session->userdata('iAdminId');
        $group_id = $this->CI->session->userdata('iGroupId');
        $limit = $this->CI->config->item('ADMIN_NOTIFICATIONS_LIMIT');
        $admin_url = $this->CI->config->item("admin_url");

        $urls = array(
            "admin_notifications_add",
            "admin_notifications_list",
            "desktop_notifications_add",
            "desktop_notifications_list"
        );
        $enc_urls = $this->getCustomEncryptURL($urls, TRUE);
        $listing_url = $enc_urls['admin_notifications_list'];
        $notify_url = $enc_urls['admin_notifications_add'];
        $desktop_url = $enc_urls['desktop_notifications_add'];
        $desktop_list = $enc_urls['desktop_notifications_list'];
        $enc_view = $this->getAdminEncodeURL('View');
        $notifications_active = "N";

        $notifications = array();

        if ($this->CI->config->item('ADMIN_NOTIFICATIONS_ACTIVATE') == 'Y') {

            $this->CI->db->select("iNotificationId as id, tMessage AS message, dAddedDate as date, eIsRead as is_read, eStatus as status");
            $this->CI->db->where('iAdminId', $admin_id);
            if ($type == 'Notify') {
                $this->CI->db->where('eStatus', 'Pending');
                $this->CI->db->limit(7);
            } else {
                $this->CI->db->limit($limit);
                $this->CI->db->where('eStatus', 'Executed');
            }
            $this->CI->db->order_by("dAddedDate", "DESC");
            $result_arr = $this->CI->db->get('mod_admin_notifications');
            $result = is_object($result_arr) ? $result_arr->result_array() : array();

            $this->CI->db->from('mod_admin_notifications');
            $this->CI->db->where('eIsRead', 'No');
            $this->CI->db->where('iAdminId', $admin_id);
            $messages = $this->CI->db->get();
            $total_messages = is_object($messages) ? $messages->num_rows() : 0;

            foreach ($result as $key => $value) {

                $value['time'] = $this->getTimeDiff($value['date']);
                $value['icon'] = "fa fa-bullhorn";
                $enc_id = $this->getAdminEncodeURL($value['id']);
                $value['url'] = $admin_url . "#" . $notify_url . "|mode|" . $enc_view . "|id|" . $enc_id . "|hideCtrl|true|width|50%|height|50%";
                $notifications['data'][] = $value;

                if ($type == 'Notify') {
                    $notify_ids[] = $value['id'];
                    $notify['type'] = 'success';
                    $notify['subject'] = '';
                    $notify['message'] = $value['message'];
                    $notifications['notify'][] = $notify;
                }
            }

            $notifications['count'] = $total_messages;
            $notifications['admin_listing_url'] = $listing_url;
            $notifications['pending_text'] = "Pending Notifications";
            $notifications['view_all_text'] = "View All";
            $notifications['admin_notifications_active'] = $this->CI->config->item('ADMIN_NOTIFICATIONS_ACTIVATE');
            $notifications_active = "Y";
        }
        if ($this->CI->config->item('ADMIN_DESKTOP_NOTIFICATIONS') == 'Y') {

            $this->CI->db->select("iExecutedNotificationId as id, vSubject AS subject, tContent as content, dtSendDateTime as date, eStatus as status");
            $this->CI->db->where('iEntityId', $admin_id);
            $this->CI->db->where('eNotificationType', 'DesktopNotify');
            $this->CI->db->limit($limit);
            $this->CI->db->order_by("dtSendDateTime", "DESC");
            $desktop_messages_obj = $this->CI->db->get('mod_executed_notifications');
            $desktop_messages = is_object($desktop_messages_obj) ? $desktop_messages_obj->result_array() : array();

            if ($type == 'Notify' && is_array($notify_ids) && count($notify_ids) > 0) {
                $this->CI->db->where_in('iNotificationId', $notify_ids);
                $this->CI->db->update('mod_admin_notifications', array('eStatus' => 'Executed'));
            }

            foreach ($desktop_messages as $key => $value) {
                $value['time'] = $this->getTimeDiff($value['date']);
                $value['icon'] = "fa fa-bell";
                $enc_id = $this->getAdminEncodeURL($value['id']);
                $value['url'] = $admin_url . "#" . $desktop_url . "|id|" . $enc_id . "|hideCtrl|true|width|50%|height|50%";
                if (empty($value['subject'])) {
                    $value['message'] = strip_tags($value['content']);
                } else {
                    $value['message'] = $value['subject'];
                }
                if (!empty($value['subject']) || !empty($value['content'])) {
                    $notifications['desktop'][] = $value;
                }
            }
            $notifications['desktop_listing_url'] = "#" . $desktop_list . "|men_notification_type|DesktopNotify";
            $notifications['desktop_notifications_active'] = $this->CI->config->item('ADMIN_DESKTOP_NOTIFICATIONS');
            $notifications_active = "Y";
        }
        if($notifications['admin_notifications_active'] == 'Y' && $notifications['desktop_notifications_active'] == 'Y'){
            $notifications['notifications_count'] = 2;
        }else{
            $notifications['notifications_count'] = 1;
        }
        $notifications['notifications_active'] = $notifications_active;

        return $notifications;
    }
    
    public function getTasks()
    {

        $admin_id = $this->CI->session->userdata('iAdminId');
        $admin_url = $this->CI->config->item("admin_url");

        if ($this->CI->config->item('ADMIN_NOTIFICATIONS_ACTIVATE') == 'Y' || 1) {

            $this->CI->db->select("*");
            $this->CI->db->where('iAssignedTo', $admin_id);
            $this->CI->db->limit(10);
            $this->CI->db->order_by("dAddedDate", "DESC");
            $result_arr = $this->CI->db->get('mod_admin_tasks');
            $result = is_object($result_arr) ? $result_arr->result_array() : array();
            
            if(is_array($result) && count($result) > 0 ){
                foreach($result as $key => $val){
                    $value['msg'] = $val['vSubject'];
                    $value['time'] = $this->getTimeDiff($val['dAddedDate']);
                    $value['icon'] = "fa fa-tasks";
                    $value['url'] = $admin_url . "#user/tasks_management/add|mode|View|id|".$val['iTaskId'];

                    $tasks[] = $value;
                }
            }else{
                $value['msg'] = "No Tasks assigned to you";
                $value['icon'] = "fa fa-tasks";
                $value['no_tasks'] = 1;
                $tasks[] = $value;
        }
        }
        return $tasks;
    }
    
    public function getNotificationTime($value = '',$id = '',$data = array()){
        if($value == '' || $value == '0000-00-00 00:00:00'){
            $value = "No Date";
        }else{
            $value = $this->getTimeDiff($value);
        }
        
        return $value;
    }
    public function getTimeDiff($timestamp, $reference = null)
    {
        $MINUTE = 60;
        $HOUR = 3600;
        $DAY = 86400;
        $WEEK = 604800;
        $MONTH = 2628000;
        $YEAR = 31536000;

        $formats = array(
            array(0.7 * $MINUTE, 'just now', 1),
            array(1.5 * $MINUTE, 'a minute', 1),
            array(60 * $MINUTE, '%d mins', $MINUTE),
            array(1.5 * $HOUR, 'an hour', 1),
            array($DAY, '%d hours', $HOUR),
            array(2 * $DAY, 'yesterday', 1),
            array(7 * $DAY, '%d days', $DAY),
            array(1.5 * $WEEK, 'a week', 1),
            array($MONTH, '%d weeks', $WEEK),
            array(1.5 * $MONTH, 'a month', 1),
            array($YEAR, '%d months', $MONTH),
            array(1.5 * $YEAR, 'a year', 1),
            array(PHP_INT_MAX, '%d years', $YEAR)
        );

        if ($reference === null) {
            $reference = time();
        }
        $diff = "error";
        $timestamp = $this->makeTimestamp($timestamp);
        $reference = $this->makeTimestamp($reference);

        $delta = $reference - $timestamp;

        if ($delta >= 0) {
            foreach ($formats as $format) {
                if ($delta < $format[0]) {
                    return sprintf($format[1], round($delta / $format[2]));
                }
            };
        } else {
            return "Unknown";
        }
    }

    public function makeTimestamp($something)
    {
        if ($something instanceof DateTime) {
            return $something->getTimestamp();
        }
        if (is_numeric($something)) {
            return (int) $something;
        }

        return strtotime($something);
    }

    public function getRouteClassName()
    {
        $ext_prefix = $this->CI->config->item('cu_controller_prx');
        $current_class = $this->CI->router->fetch_class();
        if (substr($current_class, 0, strlen($ext_prefix)) == $ext_prefix) {
            $current_class = substr($current_class, strlen($ext_prefix));
        }
        return strtolower($current_class);
    }

    public function getControllerObject()
    {
        global $CI;
        $current_class = $this->getRouteClassName();
        if (is_object($this->CI->$current_class)) {
            return $this->CI->$current_class;
        } else {
            return $CI;
        }
    }

    public function getModelObject()
    {
        $current_class = $this->getRouteClassName();
        $model_class = $current_class . "_model";
        return $this->CI->$model_class;
    }

    public function getDBQueriesList()
    {
        $db_queries = $this->CI->db->queries;
        $db_query_times = $this->CI->db->query_times;
        $db_query_modes = $this->CI->db->query_modes;
        $queries_log = array();
        $skip_arr = array(
            //skip queries
            "SELECT CASE WHEN (@@OPTIONS | 256) = @@OPTIONS THEN 1 ELSE 0 END AS qi",
            "SELECT  TOP " . $this->CI->config->item("db_max_limit") . " " . $this->CI->db->protect("vName") . ", " . $this->CI->db->protect("vValue") . " FROM " . $this->CI->db->protect("mod_setting"),
            "SELECT " . $this->CI->db->protect("vName") . ", " . $this->CI->db->protect("vValue") . " FROM " . $this->CI->db->protect("mod_setting"),
            "SELECT " . $this->CI->db->protect("mwt.*") . " FROM " . $this->CI->db->protect("mod_ws_token") . " AS " . $this->CI->db->protect("mwt") . " WHERE " . $this->CI->db->protect("mwt.vWSToken"),
            "UPDATE " . $this->CI->db->protect("mod_ws_token") . " SET " . $this->CI->db->protect("dLastAccess"),
            "INSERT INTO " . $this->CI->db->protect("mod_executed_notifications"),
            'SELECT  TOP 25000 "vName", "vValue" FROM "mod_setting"'
        );
        $total = 0;
        for ($i = 0, $j = 0; $i < count($db_queries); $i++) {
            $query = $db_queries[$i];
            $query = str_replace(array("\n", "\r"), " ", $query);
            foreach ($skip_arr as $needle) {
                if (strpos($query, $needle) === FALSE) {
                    continue 1;
                } else {
                    continue 2;
                }
            }
            $queries_log[$j]['query'] = $query;
            $queries_log[$j]['time(ms)'] = round(($db_query_times[$i] * 1000), 3);
            if ($db_query_modes[$i] == 'cache') {
                $queries_log[$j]['cache'] = "true";
            }
            $total += round(($db_query_times[$i] * 1000), 3);
            $j++;
        }
        $queries_log[0]['count'] = count($queries_log);
        $queries_log[0]['total(ms)'] = $total;
        return $queries_log;
    }

    public function logDBQueriesList()
    {
        if (!is_object($this->CI->db)) {
            return FALSE;
        }
        $queries = $this->CI->db->queries;
        $times = $this->CI->db->query_times;
        $modes = $this->CI->db->query_modes;

        $output = '';
        if (count($queries) == 0) {
            $output .= "no queries\n";
        } else {
            $skip_arr = array(
                "SELECT CASE WHEN (@@OPTIONS | 256) = @@OPTIONS THEN 1 ELSE 0 END AS qi",
                "SELECT  TOP " . $this->CI->config->item("db_max_limit") . " " . $this->CI->db->protect("vName") . ", " . $this->CI->db->protect("vValue") . " FROM " . $this->CI->db->protect("mod_setting"),
                "SELECT " . $this->CI->db->protect('vName') . ", " . $this->CI->db->protect('vValue') . " FROM " . $this->CI->db->protect('mod_setting'),
                "SELECT * FROM " . $this->CI->db->protect('mod_language') . " WHERE " . $this->CI->db->protect('eStatus') . " = 'Active'",
                "SELECT " . $this->CI->db->protect("ms.vName") . ", IF(" . $this->CI->db->protect("ms.eLang") . " = " . $this->CI->db->escape("Yes") . ", " . $this->CI->db->protect("msl.vValue") . ", " . $this->CI->db->protect("ms.vValue") . ") AS " . $this->CI->db->protect("lang_value") . " FROM " . $this->CI->db->protect("mod_setting") . " AS " . $this->CI->db->protect("ms"),
                "SELECT " . $this->CI->db->protect("vTableName") . ", " . $this->CI->db->protect("eExpireTime") . " FROM " . $this->CI->db->protect("mod_cache_tables"),
                "SELECT * FROM " . $this->CI->db->protect("mod_admin_menu") . " WHERE " . $this->CI->db->protect("iAdminMenuId") . " IN ",
                "SELECT " . $this->CI->db->protect("mgr.*") . " FROM " . $this->CI->db->protect("mod_group_rights") . " " . $this->CI->db->protect("mgr") . " JOIN " . $this->CI->db->protect("mod_admin_menu") . " " . $this->CI->db->protect("mam") . " ON " . $this->CI->db->protect("mam.iAdminMenuId") . " = " . $this->CI->db->protect("mgr.iAdminMenuId"),
                "SELECT " . $this->CI->db->protect("mgr.*") . " FROM " . $this->CI->db->protect("mod_group_rights") . " AS " . $this->CI->db->protect("mgr") . " LEFT JOIN " . $this->CI->db->protect("mod_admin_menu") . " AS " . $this->CI->db->protect("mam") . " ON " . $this->CI->db->protect("mam.iAdminMenuId") . " = " . $this->CI->db->protect("mgr.iAdminMenuId"),
                "SELECT " . $this->CI->db->protect("m.vMenuDisplay") . " AS " . $this->CI->db->protect("subMenu") . ", " . $this->CI->db->protect("m.vURL") . " AS " . $this->CI->db->protect("subURL") . ", " . $this->CI->db->protect("s.vMenuDisplay") . " AS " . $this->CI->db->protect("mainMenu") . ", " . $this->CI->db->protect("s.vURL") . " AS " . $this->CI->db->protect("mainURL") . " FROM " . $this->CI->db->protect("mod_admin_menu") . " AS " . $this->CI->db->protect("m"),
                "INSERT INTO " . $this->CI->db->protect("mod_admin_navigation_log"),
            );
            $date_str = date("Y-m-d H:i:s");
            $ip_addr = $this->CI->general->getHTTPRealIPAddr();
            $url_str = $this->CI->config->item("site_url") . $this->CI->uri->uri_string . "/";
            $method = $_SERVER['REQUEST_METHOD'];
            for ($i = count($queries) - 1; $i >= 0; $i--) {
                $query_str = $queries[$i];
                $query_str = str_replace(array("\n", "\r"), " ", $query_str);
                foreach ($skip_arr as $needle) {
                    if (strpos($query_str, $needle) === FALSE) {
                        continue 1;
                    } else {
                        continue 2;
                    }
                }

                $query_time = sprintf("%.3f", $times[$i] * 1000);
                $query_mode = $modes[$i];
                $output .= <<<EOD
{$query_str}~~~~{$query_time}~~~~{$ip_addr}~~~~{$query_mode}~~~~{$url_str}~~{$method}~~~~{$date_str}

EOD;
            }
        }

        if (empty($output)) {
            return;
        }

        $log_file_name = $this->CI->session->userdata('queryLogFile');
        $log_file_name = ($log_file_name != '') ? $log_file_name : md5($_SERVER['REQUEST_URI']);

        if ($this->CI->config->item('is_webservice') === true) {
            $log_folder = $this->CI->config->item('ws_query_log_path');
        } elseif ($this->CI->config->item('is_notification') === true) {
            $log_folder = $this->CI->config->item('ns_query_log_path');
        } elseif ($this->CI->config->item('is_citparseapi') === true) {
            $log_folder = $this->CI->config->item('parse_query_log_path');
        } elseif ($this->CI->config->item('is_admin') === true) {
            $log_folder = $this->CI->config->item('admin_query_log_path');
        } else {
            $log_folder = $this->CI->config->item('front_query_log_path');
        }

        if (!is_dir($log_folder)) {
            $this->CI->general->createFolder($log_folder);
        }
        $log_file_path = $log_folder . $log_file_name . ".html";

        $file_data = $output;
        if (is_file($log_file_path)) {
            $file_data .= file_get_contents($log_file_path);
        }
        $fp = fopen($log_file_path, 'w');
        fwrite($fp, $file_data);
        fclose($fp);
        return TRUE;
    }

      public function insertApiLogger($input_params = array())
    {
        $this->CI = &get_instance();

        $ip_addr = $this->getHTTPRealIPAddr();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $plat_form = $this->get_platform($user_agent);
        
        $browser = $this->get_browser($user_agent);

        $request_func = $this->CI->uri->segments[2];
        $request_url = !empty($input_params['request_url']) ? $input_params['request_url'] : "";
        $file_name_func = !empty($input_params['file_name_func']) ? $input_params['file_name_func'] : "";
        $input_array = !empty($input_params['input_params']) ? $input_params['input_params'] : "";
        $output_response = !empty($input_params['output_response']) ? $input_params['output_response'] : "";
        $accessed_time = !empty($input_params['accessed_time']) ? $input_params['accessed_time'] : "";
        $executed_time = !empty($input_params['executed_time']) ? $input_params['executed_time'] : "";

        // Input Params and Output Response file creation => start
        list($start_micro, $start_date) = explode(" ", $accessed_time);
        list($executed_micro, $execute_date) = explode(" ", $executed_time);

        $access_date = date("Y-m-d H:i:s", $start_date);
        $executed_date = date("Y-m-d H:i:s", $execute_date);

        $access_log_folder = $this->CI->config->item('admin_access_log_path');
        if (!is_dir($access_log_folder))
        {
            $this->createFolder($access_log_folder);
        }

        $log_folder_path = $access_log_folder."api_logs".DS;
        if (!is_dir($log_folder_path))
        {
            $this->createFolder($log_folder_path);
        }
        $log_file_ext = 'json';

        $file_name = $file_name_func."-".$start_date."-".mt_rand(1, 1000).".".$log_file_ext;

        $log_file_path = $log_folder_path.$file_name;

        $fileContents['input_params'] = $input_array;
        $fileContents['output_response'] = $output_response;

        $fp = fopen($log_file_path, 'a+');
        fwrite($fp, json_encode($fileContents));
        fclose($fp);

        // Input Params and Output Response file creation => end
        if (file_exists($log_file_path))
        {
            $data_array = array();
            $data_array['vIPAddress'] = $ip_addr;
            $data_array['vAPIName'] = $request_func;
            $data_array['vAPIURL'] = $request_url;
            $data_array['dAccessDate'] = $access_date;
            $data_array['vPlatform'] = $plat_form;
            $data_array['vBrowser'] = $browser;
            $data_array['vFileName'] = $file_name;
            $data_array['iPerformedBy'] = '0';
            $data_array['dtExecutedDate'] = $executed_date;
            $result = $this->CI->db->insert('api_accesslogs', $data_array);
            if (!empty($result))
            {
                $return_arr['success'] = true;
            }
            else
            {
                $return_arr['success'] = false;
            }
            return $return_arr;
        }
        //pr($return_arr,1);
    // Access Logs insertion => end
    }

    public function get_platform($user_agent = '')
    {
        $os_platform = "Unknown OS Platform";

        $os_array = array(
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile',
        );

        foreach ($os_array as $regex => $value)
        {
            if (preg_match($regex, $user_agent))
            {
                $os_platform = $value;
            }
        }
        return $os_platform;
    }                  

    public function get_browser($user_agent = '')
    {
        $browser = "Unknown Browser";

        $browser_array = array(
            '/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i' => 'Handheld Browser',
        );

        foreach ($browser_array as $regex => $value)
        {
            if (preg_match($regex, $user_agent))
            {
                $browser = $value;
            }
        }

        return $browser;
    }

    
}

/* End of file General.php */
/* Location: ./application/libraries/General.php */