<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of JS Combine Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module Js
 * 
 * @class Js.php
 * 
 * @path application\libraries\Js.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Js
{

    protected $CI;
    protected $js_file_array = array();
    protected $js_common_array = array();
    protected $js_local_array = array();
    protected $js_key_pairs = array();
    protected $js_template_code = '';
    public $javascript_code = "";

    public function __construct()
    {
        $this->CI = & get_instance();
        if ($this->CI->config->item('is_admin')) {
            $this->js_common_array = array("jquery/jquery.min.js", "admin/basic/project.js");
            if ($this->CI->input->get_post('iframe') != "true") {
                $this->js_file_array = array("jquery/jquery.min.js", "admin/basic/project.js");
            }
            $this->js_local_array = array("admin/jqGrid/jquery-jqgrid-formatter.js");
        } else {
            $this->js_file_array = array("jquery/jquery.min.js", "project.js");
        }
        $this->js_key_pairs = array(
            "common" => "main_common.js",
            "print" => "main_print.js",
            "login" => "main_login.js",
        );
        $this->javascript_basic_code = "
Project.modules.processAjax = {
    init: function () {
        #JS_CODE#
    }
}";
    }

    public function add_common_js()
    {
        $add_js_array = array();
        $args = func_get_args();

        for ($i = 0, $ni = count($args); $i < $ni; $i++) {
            if ($args[$i] != '') {
                $tmp_js_arr = array();
                if (stristr(",", $args[$i])) {
                    $tmp_js_arr = explode(",", $args[$i]);
                    for ($j = 0, $nj = count($js_arr); $j < $nj; $j++) {
                        $js_arr[$j] = trim($js_arr[$j]);
                    }
                } else {
                    $tmp_js_arr = array($args[$i]);
                }
                $add_js_array = array_merge($add_js_array, $tmp_js_arr);
            }
        }
        $this->js_common_array = array_merge($this->js_common_array, $add_js_array);
    }

    public function js_common_src($type = 'common', $_app_parse = '0')
    {
        $js_cmn_path = $this->CI->config->item('cmn_js_path');
        $js_file_path = $this->CI->config->item('js_path');
        $js_cache_path = $this->CI->config->item('js_cache_path');
        $js_common_array = $this->js_common_array;

        $cache_js_module = md5('common_cache_folder');

        $mtime_array = array();
        //get the filemtime
        for ($i = 0; $i < count($js_common_array); $i++) {
            if (is_file($js_file_path . $js_common_array[$i])) {
                $mtime_array[] = filemtime($js_file_path . $js_common_array[$i]);
            }
        }
        $cache_js_folder = md5(serialize($js_common_array) . serialize($mtime_array) . $cache_js_module);
        $js_enc_key = $this->CI->general->getMD5EncryptString("AppCacheJS");
        $cookie_str = $this->CI->ci_local->read($js_enc_key, -1);

        $common_file_name = $this->js_key_pairs[$type];
        $dir_name = "compiled/" . $cache_js_folder . "/";
        $common_dir_name = $js_cache_path . $dir_name;

        $common_file_path = $common_dir_name . $common_file_name;
        $create_flag = TRUE;
        if (!is_dir($common_dir_name)) {
            mkdir($common_dir_name, 0777, TRUE);
        } elseif (is_file($common_file_path)) {
            if ($cookie_str == $cache_js_folder) {
                $create_flag = FALSE;
            }
            $this->CI->ci_local->write($js_enc_key, $cache_js_folder, -1);
        }

        if ($create_flag) {
            $js_code = '';
            for ($i = 0; $i < count($js_common_array); $i++) {
                if (is_file($js_file_path . $js_common_array[$i])) {
                    $js_code .= '
';
                    $js_code .= file_get_contents($js_file_path . $js_common_array[$i]);

                    $js_code .= ';';
                } elseif (!empty($js_cmn_path) && is_file($js_cmn_path . $js_common_array[$i]) && !in_array($js_common_array[$i], $this->js_local_array)) {
                    $js_code .= '
';
                    $js_code .= file_get_contents($js_cmn_path . $js_common_array[$i]);

                    $js_code .= ';';
                }
            }

            $final_js = $js_code;
            $cfp = fopen($common_file_path, 'w+');
            fwrite($cfp, $final_js);
            fclose($cfp);
        }

        $this->js_common_array = array();
        if ($_app_parse == '1') {
            return $cache_js_folder;
        }
        $common_file_url = $this->CI->config->item('js_url') . $dir_name . $common_file_name;
        if ($this->CI->config->item("cdn_activate") == TRUE && $this->CI->config->item("cdn_http_url") != "") {
            $cdn_file_url = str_replace($this->CI->config->item("site_url"), $this->CI->config->item("cdn_http_url"), $common_file_url);
            $out_put = '<script type="text/javascript" src="' . $cdn_file_url . '"></script>';
        } else {
            $out_put = '<script type="text/javascript" src="' . $common_file_url . '"></script>';
        }

        return $out_put;
    }

    public function clean_common_js()
    {
        $this->js_common_array = array();
    }

    public function add_js()
    {
        $add_js_array = array();
        $args = func_get_args();

        for ($i = 0, $ni = count($args); $i < $ni; $i++) {
            if ($args[$i] != '') {
                $tmp_js_arr = array();
                if (stristr(",", $args[$i])) {
                    $tmp_js_arr = explode(",", $args[$i]);
                    for ($j = 0, $nj = count($js_arr); $j < $nj; $j++) {
                        $js_arr[$j] = trim($js_arr[$j]);
                    }
                } else {
                    $tmp_js_arr = array($args[$i]);
                }
                $add_js_array = array_merge($add_js_array, $tmp_js_arr);
            }
        }
        $this->js_file_array = array_merge($this->js_file_array, $add_js_array);
    }

    public function clean_js()
    {
        $this->js_file_array = array();
    }

    public function set_js_code($js_arr)
    {
        for ($i = 0; $i < count($js_arr); $i++) {
            foreach ((array) $js_arr[$i] as $key => $val) {
                if ($val != '') {
                    $this->js_template_code .= $val;
                }
            }
        }
    }

    public function get_js_code()
    {
        return $this->js_template_code;
    }

    /**
     * js_src
     *
     * @desc generate combine all javascript into one file
     *
     * @category function
     * @access public
     * @return string
     */
    public function js_src($type = '')
    {
        $template_js = '';
        $javascript_append_arr = $this->CI->smarty->getTemplateVars('javascript_append');
        $this->set_js_code($javascript_append_arr);
        $template_js = $this->get_js_code();

        $js_cmn_path = $this->CI->config->item('cmn_js_path');
        $js_file_path = $this->CI->config->item('js_path');
        $js_cache_path = $this->CI->config->item('js_cache_path');

        $js_file_array = $this->js_file_array;
        $js_file_array = array_values(array_unique($js_file_array));

        if (!is_array($js_file_array) || count($js_file_array) == 0) {
            $this->js_template_code = '';
            $this->js_file_array = array();
            return $template_js;
        }

        $mtime_array = array();
        //get the filemtime
        for ($i = 0; $i < count($js_file_array); $i++) {
            if (is_file($js_file_path . $js_file_array[$i])) {
                $mtime_array[] = filemtime($js_file_path . $js_file_array[$i]);
            }
        }

        $js_code_str = md5(serialize($this->CI->router->fetch_module() . $this->CI->router->class . $this->CI->router->method));
        $fname = md5(serialize($js_file_array) . serialize($mtime_array) . $js_code_str);
        $dir_name = "compiled/" . $fname . "/";
        if (!is_dir($js_cache_path . $dir_name)) {
            mkdir($js_cache_path . $dir_name, 0777, TRUE);
        }
        $file_flag = TRUE;
        $combine_file_name = ($this->js_key_pairs[$type] != '') ? $this->js_key_pairs[$type] : "main_combine.js";
        $final_file_name = $js_cache_path . $dir_name . $combine_file_name;
        if (!is_file($final_file_name)) {
            $js_code = '';
            $jq_flag = FALSE;
            for ($i = 0, $ni = count($js_file_array); $i < $ni; $i++) {
                $base_name = basename($js_file_array[$i]);
                if (in_array($base_name, array("jquery.js", "jquery.min.js"))) {
                    if ($jq_flag == TRUE) {
                        continue;
                    }
                    $jq_flag = TRUE;
                }
                if (is_file($js_file_path . $js_file_array[$i])) {
                    $js_code .= '
';
                    $js_code .= file_get_contents($js_file_path . $js_file_array[$i]);
                    $js_code .= ';';
                } elseif (!empty($js_cmn_path) && is_file($js_cmn_path . $js_file_array[$i]) && !in_array($js_file_array[$i], $this->js_local_array)) {
                    $js_code .= '                                      
';
                    $js_code .= file_get_contents($js_cmn_path . $js_file_array[$i]);
                    $js_code .= ';';
                }
            }
            if ($this->javascript_code != '') {
                $js_code .= str_replace("#JS_CODE#", $this->javascript_code, $this->javascript_basic_code);
            }
            if (trim($js_code) != '') {
                $final_js = $js_code;
                $Nfp = fopen($final_file_name, 'w+');
                fwrite($Nfp, $final_js);
                fclose($Nfp);
            } else {
                $file_flag = FALSE;
            }
        }
        $out_put = '';
        if ($file_flag == TRUE) {
            if ($this->CI->config->item('is_admin')) {
                $combine_file_url = $this->CI->config->item('js_url') . $dir_name . $combine_file_name;
                if (array_key_exists($type, $this->js_key_pairs) && $this->CI->config->item("cdn_activate") == TRUE && $this->CI->config->item("cdn_http_url") != "") {
                    $cdn_file_url = str_replace($this->CI->config->item("site_url"), $this->CI->config->item("cdn_http_url"), $combine_file_url);
                    $out_put = '<script type="text/javascript" src="' . $cdn_file_url . '"></script>';
                } else {
                    $out_put = '<script type="text/javascript" src="' . $combine_file_url . '"></script>';
                }
            } else {
                $combine_file_url = $this->CI->config->item('js_url') . $dir_name . $combine_file_name;
                if ($this->CI->config->item("cdn_activate") == TRUE && $this->CI->config->item("cdn_http_url") != "") {
                    $cdn_file_url = str_replace($this->CI->config->item("site_url"), $this->CI->config->item("cdn_http_url"), $combine_file_url);
                    $out_put = '<script type="text/javascript" src="' . $cdn_file_url . '"></script>';
                } else {
                    $out_put = '<script type="text/javascript" src="' . $combine_file_url . '"></script>';
                }
            }
        }
        $out_put .= $template_js;

        $this->js_template_code = '';
        $this->js_file_array = array();

        return $out_put;
    }

    public function js_labels_src()
    {
        if ($this->CI->config->item('is_admin') == 1) {
            $lang_mode = "admin";
        } else {
            $lang_mode = "front";
        }
        if ($this->CI->config->item('MULTI_LINGUAL_PROJECT') == "Yes") {
            if ($this->CI->config->item('is_front') == 1) {
                $lang = strtolower($this->CI->session->userdata("sess_lang_id"));
            } else {
                $lang = strtolower($this->CI->session->userdata("DEFAULT_LANG"));
            }
        }
        if (empty($lang)) {
            $lang = "en";
        }
        $cache_js_labels = md5('common_cache_labels_' . $lang);
        $js_file_path = $this->CI->config->item('js_path');
        $js_cache_path = $this->CI->config->item('js_cache_path');
        $lang_library = $this->CI->lang;
        if (!empty($lang_library->is_loaded[0])) {
            $file_name = $lang_library->is_loaded[0];
        } else {
            if ($this->CI->config->item('is_front') == 1) {
                $file_name = "front_lang.php";
            } else {
                $file_name = "general_lang.php";
            }
        }
        $lang_file_name = APPPATH . 'language/' . $lang . '/' . $file_name;
        if (is_file($lang_file_name)) {
            $lang_file_mtime = filemtime($lang_file_name);
        } else {
            $lang_file_mtime = rand(1, 100000);
        }

        $cache_js_folder = md5($lang_file_mtime . $lang_mode . $cache_js_labels);

        $dir_name = "compiled/" . $cache_js_folder . "/";
        $common_dir_name = $js_cache_path . $dir_name;

        $common_file_url = $this->CI->config->item('js_url') . $dir_name . 'main_labels.js';
        $common_file_path = $common_dir_name . 'main_labels.js';

        if (!is_dir($common_dir_name)) {
            mkdir($common_dir_name, 0777, TRUE);
        }

        if (!is_file($common_file_path)) {
            $final_js = '';
            //language labels data
            $lang_arr = $lang_library->language;
            if (is_array($lang_arr) && count($lang_arr) > 0) {
                $json_str = json_encode($lang_arr);
                $send_json = 'js_lang_label = ' . $json_str . ';';
            }
            $final_js .= $send_json;

            $Nfp = fopen($common_file_path, 'w+');
            fwrite($Nfp, $final_js);
            fclose($Nfp);
        }

        $combine_file_url = $this->CI->config->item('js_url') . $dir_name . "main_labels.js";
        if ($this->CI->config->item("cdn_activate") == TRUE && $this->CI->config->item("cdn_http_url") != "") {
            $cdn_file_url = str_replace($this->CI->config->item("site_url"), $this->CI->config->item("cdn_http_url"), $combine_file_url);
            $out_put = '<script type="text/javascript" src="' . $cdn_file_url . '"></script>';
        } else {
            $out_put = '<script type="text/javascript" src="' . $combine_file_url . '"></script>';
        }
        return $out_put;
    }
}

/* End of file Js.php */
/* Location: ./application/libraries/Js.php */