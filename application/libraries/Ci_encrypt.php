<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Encryption Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module Encrypt
 * 
 * @class Ci_encrypt.php
 * 
 * @path application\libraries\Ci_encrypt.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Ci_encrypt
{

    protected $CI;
    private $_decryptArr;
    private $_encCipher;
    private $_urlEnc;
    private $_urlKey;
    private $_urlIV;
    private $_dataEnc;
    private $_dataKey;
    private $_dataIV;

    public function __construct()
    {
        #$this->CI = & get_instance();
        $this->config = & load_class('Config', 'core');

        $this->_decryptArr = $this->config->item("FRAMEWORK_ENCRYPTS");
        $this->_encCipher = 'aes-128-cbc';

        $this->_urlEnc = $this->config->item("ADMIN_ENC_KEY");
        $this->_urlKey = md5($this->_urlEnc);

        $this->_dataEnc = $this->config->item("DATA_ENCRYPT_KEY");
        $this->_dataKey = md5($this->_dataEnc);

        if (extension_loaded("mcrypt")) {
            $this->_urlIV = str_repeat("\0", mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));
            $this->_dataIV = str_repeat("\0", mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));
        } elseif (extension_loaded("openssl")) {
            $this->_urlIV = str_repeat("\0", openssl_cipher_iv_length($this->_encCipher));
            $this->_dataIV = str_repeat("\0", openssl_cipher_iv_length($this->_encCipher));
        }
    }

    public function encrypt($string = '', $url = false)
    {
        if (!$this->isEncryptionActive()) {
            return $string;
        }
        if ($url == true && $this->isAllowEncURL()) {
            return $string;
        }

        if (extension_loaded("mcrypt")) {
            $crypted_text = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->_urlKey, $string, MCRYPT_MODE_ECB, $this->_urlIV);
        } elseif (extension_loaded("openssl")) {
            $crypted_text = openssl_encrypt($string, $this->_encCipher, $this->_urlKey, $options = OPENSSL_RAW_DATA, $this->_urlIV);
        }

        $enc_str = bin2hex($crypted_text);

        return $enc_str;
    }

    public function decrypt($string = '', $url = false)
    {
        if (!$this->isEncryptionActive()) {
            return $string;
        }
        if ($url == true && $this->isAllowEncURL()) {
            return $string;
        }

        $string = $this->hextobin($string);

        if (extension_loaded("mcrypt")) {
            $dec_str = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->_urlKey, $string, MCRYPT_MODE_ECB, $this->_urlIV);
        } elseif (extension_loaded("openssl")) {
            $dec_str = openssl_decrypt($string, $this->_encCipher, $this->_urlKey, $options = OPENSSL_RAW_DATA, $this->_urlIV);
        }

        return trim($dec_str);
    }

    public function convertEncryptedVars()
    {
        if (!$this->isEncryptionActive()) {
            return false;
        }
        if (is_array($_REQUEST) && count($_REQUEST) > 0) {
            $_REQUEST = $this->decryptKeyValuePairs($_REQUEST);
        }
        if (is_array($_GET) && count($_GET) > 0) {
            $_GET = $this->decryptKeyValuePairs($_GET);
        }
        if (is_array($_POST) && count($_POST) > 0) {
            $_POST = $this->decryptKeyValuePairs($_POST);
        }
        return true;
    }

    protected function decryptKeyValuePairs($arr = array())
    {
        if (!$this->isEncryptionActive()) {
            return $arr;
        }
        if (!is_array($arr) || count($arr) == 0) {
            return $arr;
        }
        foreach ($arr as $key => $val) {
            if (in_array($key, $this->_decryptArr) && $val != "") {
                if (in_array($key, array("id", "switchIDs")) && strstr($val, ",") !== FALSE) {
                    $list = explode(",", $val);
                    $item = array();
                    foreach ($list as $id) {
                        $item[] = $this->decrypt($id);
                    }
                    $arr[$key] = implode(",", $item);
                } else {
                    $arr[$key] = $this->decrypt($val);
                }
            }
        }
        return $arr;
    }

    public function isEncryptionActive()
    {
        if ($this->config->item("ADMIN_URL_ENCRYPTION") == 'Y') {
            return true;
        } else {
            return false;
        }
    }

    public function isAllowEncURL($string = '')
    {
        $url_arr = explode("/", $string);
        if (!is_array($url_arr) || count($url_arr) == 0) {
            return false;
        }
        $omit_urls = $this->config->item("FRAMEWORK_URLS");
        $module_arr = $omit_urls[$url_arr[0]];
        if (!is_array($module_arr) || count($module_arr) == 0) {
            return false;
        }
        $ctrl_arr = $module_arr[$url_arr[1]];
        if (!is_array($ctrl_arr) || count($ctrl_arr) == 0) {
            return false;
        }
        if (in_arrray($url_arr[2], $ctrl_arr)) {
            return true;
        }
        return false;
    }

    protected function hextobin($hexstr)
    {
        $n = strlen($hexstr);
        $sbin = "";
        $i = 0;
        while ($i < $n) {
            $a = substr($hexstr, $i, 2);
            $c = pack("H*", $a);
            if ($i == 0) {
                $sbin = $c;
            } else {
                $sbin.=$c;
            }
            $i+=2;
        }
        return $sbin;
    }

    public function dataEncrypt($string = '')
    {
        $string = trim($string);
        if ($string == "") {
            return $string;
        }

        if (extension_loaded("mcrypt")) {
            $crypted_text = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->_dataKey, $string, MCRYPT_MODE_ECB, $this->_dataIV);
        } elseif (extension_loaded("openssl")) {
            $crypted_text = openssl_encrypt($string, $this->_encCipher, $this->_dataKey, $options = OPENSSL_RAW_DATA, $this->_dataIV);
        }

        $enc_str = bin2hex($crypted_text);
        return $enc_str;
    }

    public function dataDecrypt($string = '')
    {
        $string = trim($string);
        if ($string == "") {
            return $string;
        }

        $string = $this->hextobin($string);

        if (extension_loaded("mcrypt")) {
            $dec_str = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->_dataKey, $string, MCRYPT_MODE_ECB, $this->_dataIV);
        } elseif (extension_loaded("openssl")) {
            $dec_str = openssl_decrypt($string, $this->_encCipher, $this->_dataKey, $options = OPENSSL_RAW_DATA, $this->_dataIV);
        }

        return trim($dec_str);
    }
}

/* End of file Ci_encrypt.php */
/* Location: ./application/libraries/Ci_encrypt.php */