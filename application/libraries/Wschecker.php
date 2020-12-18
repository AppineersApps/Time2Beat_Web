<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of API Authentication Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module APIAuth
 * 
 * @class Wschecker.php
 * 
 * @path application\libraries\Wschecker.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Wschecker
{

    protected $CI;
    private $_timeLimit;
    private $_timeFormat;
    private $_encryptKey;
    private $_encCipher;
    private $_MD5Key;
    private $_MD5IV;
    private $_apiParams;

    public function __construct()
    {
        $this->CI = & get_instance();
        $this->_timeFormat = "m"; //in minutes
        $this->_timeLimit = $this->CI->config->item("WS_TIME_LIMIT");
        $this->_encryptKey = $this->CI->config->item("WS_ENC_KEY");
        $this->_encCipher = 'aes-128-cbc';

        $this->_MD5Key = substr(md5($this->_encryptKey), 0, 16);
        if (extension_loaded("mcrypt")) {
            $this->_MD5IV = str_repeat("\0", mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));
        } elseif (extension_loaded("openssl")) {
            $this->_MD5IV = str_repeat("\0", openssl_cipher_iv_length($this->_encCipher));
        }

        $this->_apiParams = array('ws_checksum', 'ws_debug', 'ws_cache', 'ws_ctrls', 'ws_log', 'ws_preview_type', '_');
    }

    public function encrypt($sValue = '')
    {
        $block = 16;
        $pad = $block - (strlen($sValue) % $block);
        $sValue .= str_repeat(chr($pad), $pad);

        if (extension_loaded("mcrypt")) {
            $str_output = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->_MD5Key, $sValue, MCRYPT_MODE_CBC, $this->_MD5IV));
        } elseif (extension_loaded("openssl")) {
            $str_output = base64_encode(openssl_encrypt($sValue, $this->_encCipher, $this->_MD5Key, $options = OPENSSL_RAW_DATA, $this->_MD5IV));
        }

        $str_output = str_replace(array('+', '/', '='), array('-', '_', '.'), $str_output);
        return $str_output;
    }

    public function decrypt($sValue = '')
    {
        //$sValue = str_replace('~','+',$sValue);
        $sValue = str_replace(array('-', '_', '.'), array('+', '/', '='), $sValue);

        if (extension_loaded("mcrypt")) {
            $sValue = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->_MD5Key, base64_decode($sValue), MCRYPT_MODE_CBC, $this->_MD5IV);
        } elseif (extension_loaded("openssl")) {
            $sValue = openssl_decrypt(base64_decode($sValue), $this->_encCipher, $this->_MD5Key, $options = OPENSSL_RAW_DATA, $this->_MD5IV);
        }

        $block = 16;
        $pad = ord($sValue[($len = strlen($sValue)) - 1]);
        $len = strlen($sValue);
        $pad = ord($sValue[$len - 1]);
        $str_output = substr($sValue, 0, strlen($sValue) - $pad);
        return $str_output;
    }

    public function encryptData($sValue = '')
    {
        $block = 16;
        $pad = $block - (strlen($sValue) % $block);
        $sValue .= str_repeat(chr($pad), $pad);

        if (extension_loaded("mcrypt")) {
            $str_output = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->_MD5Key, $sValue, MCRYPT_MODE_CBC, $this->_MD5IV));
        } elseif (extension_loaded("openssl")) {
            $str_output = base64_encode(openssl_encrypt($sValue, $this->_encCipher, $this->_MD5Key, $options = OPENSSL_RAW_DATA, $this->_MD5IV));
        }

        return $str_output;
    }

    public function decryptData($sValue = '')
    {
        if (extension_loaded("mcrypt")) {
            $str_output = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->_MD5Key, base64_decode($sValue), MCRYPT_MODE_CBC, $this->_MD5IV);
        } elseif (extension_loaded("openssl")) {
            $str_output = openssl_decrypt(base64_decode($sValue), $this->_encCipher, $this->_MD5Key, $options = OPENSSL_RAW_DATA, $this->_MD5IV);
        }

        $block = 16;
        $pad = ord($str_output[($len = strlen($str_output)) - 1]);
        $len = strlen($str_output);
        $pad = ord($str_output[$len - 1]);
        $str_output = substr($str_output, 0, strlen($str_output) - $pad);
        return $str_output;
    }

    public function getHTTPRealIPAddr()
    {
        $ip = $this->CI->general->getHTTPRealIPAddr();
        return $ip;
    }

    public function getHTTPUserAgent()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        return $user_agent;
    }

    public function decrypt_params($request_arr = array())
    {
        if (!is_array($request_arr) || count($request_arr) == 0) {
            return $request_arr;
        }
        $apiParams = $this->_apiParams;
        $apiParams[] = "ws_token";
        foreach ($request_arr as $key => $val) {
            if (!in_array($key, $apiParams)) {
                $param_val = str_replace(' ', '+', $val);
                $request_arr[$key] = $this->decryptData($param_val);
            }
        }
        return $request_arr;
    }

    public function verify_webservice($request_arr = array())
    {
        $res_arr['success'] = '1';
        if ($this->CI->config->item('WS_CHECKSUM_ENCRYPTION') == "Y") {
            $res_arr = $this->validate_checksum($request_arr);
        }
        if ($res_arr['success'] == "1") {
            if ($this->CI->config->item('WS_TOKEN_ENCRYPTION') == "Y") {
                $res_arr = $this->validate_token($request_arr);
            }
        }
        return $res_arr;
    }

    public function validate_checksum($request_arr = array())
    {
        $res_arr['success'] = 1;
        if (!is_array($request_arr) || count($request_arr) == 0) {
            return $res_arr;
        }
        $params_arr = array();
        if (is_array($_FILES) && count($_FILES) > 0) {
            foreach ($_FILES as $key => $val) {
                $request_arr[$key] = '';
            }
        }
        ksort($request_arr);
        foreach ($request_arr as $sField => $sValue) {
            if (trim($sField) != "" && !in_array($sField, $this->_apiParams)) {
                $params_arr[] = $sField . "=" . $sValue;
            }
        }

        if (count($params_arr) == 0 && empty($request_arr['ws_checksum'])) {
            $res_arr['success'] = '1';
            $res_arr['message'] = "Checksum optional..!";
        } else {
            $params_string = implode("", $params_arr);
            $return_hash = sha1($params_string);
            if (empty($request_arr['ws_checksum'])) {
                $res_arr['success'] = '-100';
                $res_arr['message'] = "Checksum not found..!";
            } elseif ($return_hash != $request_arr['ws_checksum']) {
                $res_arr['success'] = '-101';
                $res_arr['message'] = "Checksum failed..!";
            } else {
                $res_arr['success'] = '1';
                $res_arr['message'] = "Checksum successful..!";
            }
        }
        return $res_arr;
    }

    public function validate_token($request_arr = array())
    {
        $res_arr['success'] = '1';
        $this->CI->load->model("rest/rest_model");
        $enc_token = $request_arr['ws_token'];
        try {
            if ($enc_token == "") {
                throw new Exception("-200");
            }
            $extra_cond = $this->CI->db->protect("mwt.vWSToken") . " = " . $this->CI->db->escape($enc_token) . " AND " . $this->CI->db->protect("mwt.eStatus") . " IN ('Active','Inactive')";
            $result_data = $this->CI->rest_model->getToken($extra_cond, "", "", "", 1);
            if (!is_array($result_data) || count($result_data) == 0) {
                throw new Exception("-201");
            }

            if ($result_data[0]['eStatus'] == "Inactive") {
                throw new Exception("-400");
            }

            $last_access = $this->CI->general->dateTimeDefinedFormat("Y-m-d H:i:s", $result_data[0]['dLastAccess']);
            $update_cond = $this->CI->db->protect("vWSToken") . " = " . $this->CI->db->escape($enc_token) . " AND " . $this->CI->db->protect("eStatus") . " = " . $this->CI->db->escape('Active');

            if ($last_access == "") {
                $update_data['eStatus'] = "Expired";
                $res = $this->CI->rest_model->updateToken($update_data, $update_cond);
                throw new Exception("-300");
            }
            $time_exceeds = $this->check_time_limit($last_access);
            if ($time_exceeds == true) {
                $update_data['eStatus'] = "Expired";
                $res = $this->CI->rest_model->updateToken($update_data, $update_cond);
                throw new Exception("-301");
            } else {
                $remote_addr = $this->getHTTPRealIPAddr();
                $user_agent = $this->getHTTPUserAgent();
                if (empty($result_data[0]['vIPAddress'])) {
                    throw new Exception("-500");
                }
                if ($result_data[0]['vIPAddress'] != $remote_addr) {
                    throw new Exception("-501");
                }
                if (empty($result_data[0]['vUserAgent'])) {
                    throw new Exception("-502");
                }
                if ($result_data[0]['vUserAgent'] != $user_agent) {
                    throw new Exception("-503");
                }
                $update_data['dLastAccess'] = date("Y-m-d H:i:s");
                $res = $this->CI->rest_model->updateToken($update_data, $update_cond);
            }
        } catch (Exception $e) {
            $code = $e->getMessage();
            switch ($code) {
                case "-200" :
                case "-201" :
                    $res_arr['success'] = '-200';
                    if ($code == "-201") {
                        $res_arr['message'] = 'Invalid token.';
                    } else {
                        $res_arr['message'] = 'Token not found.';
                    }
                    break;
                case "-300" :
                case "-301" :
                    $res_arr['success'] = '-300';
                    $res_arr['message'] = 'Token time limit expired.';
                    break;
                case "-400" :
                    $res_arr['success'] = '-400';
                    $res_arr['message'] = 'Token inactivated externally.';
                    break;
                case "-500" :
                case "-501" :
                case "-502" :
                case "-503" :
                    $res_arr['success'] = '-500';
                    $res_arr['message'] = 'Invalid token.';
                    break;
            }
        }
        return $res_arr;
    }

    public function check_time_limit($time_1 = '', $time_2 = 'now')
    {
        if (!$this->_timeLimit) {
            return false;
        }
        if ($time_2 == "now") {
            $time_2 = date("Y-m-d H:i:s");
        }
        if ($time_1 == "" || $time_2 == "") {
            return true;
        }

        $time_1 = strtotime($time_1);
        $time_2 = strtotime($time_2);

        switch ($this->_timeFormat) {
            case "d":
                $limit = round(abs($time_2 - $time_1) / 60 / 60 / 24);
                break;
            case "h":
                $limit = round(abs($time_2 - $time_1) / 60 / 60);
                break;
            default :
                $limit = round(abs($time_2 - $time_1) / 60);
                break;
        }

        if ($limit > $this->_timeLimit) {
            return true;
        } else {
            return false;
        }
    }

    public function show_error_code($res_arr = array())
    {
        $this->CI->load->library('wsresponse');
        if (in_array($res_arr['success'], array("-1", "-100", "-101", "-200", "-300", "-400", "-500"))) {
            $responce_arr['settings'] = $res_arr;
            $responce_arr['data'] = array();
            $this->CI->wsresponse->sendWSResponse($responce_arr);
        }
//        if ($res_arr['success'] == "-100") {
//            show_error('Oh god you should not try to check this. Bad request found.!', 400);
//        } elseif ($res_arr['success'] == "-200") {
//            show_error('Oh god you should not try to check this. Authentication failed.!', 403);
//        } elseif ($res_arr['success'] == "-300") {
//            show_error('Oh god you should not try to check this. Authentication failed.!', 403);
//        } elseif ($res_arr['success'] == "-400") {
//            show_error('Oh god you should not try to check this. Unauthorized access.!', 401);
//        } elseif ($res_arr['success'] == "-500") {
//            show_error('Oh god you should not try to check this. Unauthorized access.!', 401);
//        }
    }
}

/* End of file Wschecker.php */
/* Location: ./application/libraries/Wschecker.php */
