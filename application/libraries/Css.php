<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of CSS Combine Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module CSS
 * 
 * @class Css.php
 * 
 * @path application\libraries\Css.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Css
{

    protected $CI;
    protected $css_file_array = array();
    protected $css_common_array = array();
    protected $css_local_array = array();
    protected $css_key_pairs = array();

    public function __construct()
    {
        $this->CI = & get_instance();
        $this->css_file_array = array();
        $this->css_common_array = array();
        $this->css_key_pairs = array(
            "common" => "main_common.css",
            "print" => "main_print.css",
            "login" => "main_login.css",
        );
    }

    public function add_common_css()
    {
        $add_css_array = array();
        $args = func_get_args();

        for ($i = 0, $ni = count($args); $i < $ni; $i++) {
            if ($args[$i] != '') {
                $tmp_css_arr = array();
                if (stristr(",", $args[$i])) {
                    $tmp_css_arr = explode(",", $args[$i]);
                    for ($j = 0, $nj = count($css_arr); $j < $nj; $j++) {
                        $css_arr[$j] = trim($css_arr[$j]);
                    }
                } else {
                    $tmp_css_arr = array($args[$i]);
                }
                $add_css_array = array_merge($add_css_array, $tmp_css_arr);
            }
        }
        $this->css_common_array = array_merge($this->css_common_array, $add_css_array);
    }

    public function css_common_src($type = 'common', $_app_parse = '0')
    {
        $css_cmn_path = $this->CI->config->item('cmn_css_path');
        $css_file_path = $this->CI->config->item('css_path');
        $css_cache_path = $this->CI->config->item('css_cache_path');
        $css_common_array = $this->css_common_array;

        $cache_css_module = md5('common_cache_folder');

        $mtime_array = array();
        //get the filemtime
        for ($i = 0; $i < count($css_common_array); $i++) {
            if (is_file($css_file_path . $css_common_array[$i])) {
                $mtime_array[] = filemtime($css_file_path . $css_common_array[$i]);
            }
        }

        $cache_css_folder = md5(serialize($css_common_array) . serialize($mtime_array) . $cache_css_module);
        $css_enc_key = $this->CI->general->getMD5EncryptString("AppCacheCSS");
        $cookie_str = $this->CI->ci_local->read($css_enc_key, -1);

        $common_file_name = $this->css_key_pairs[$type];
        $dir_name = "compiled/" . $cache_css_folder . "/";
        $common_dir_name = $css_cache_path . $dir_name;
        $common_file_path = $common_dir_name . $common_file_name;

        $create_flag = TRUE;

        if (!is_dir($common_dir_name)) {
            mkdir($common_dir_name, 0777, TRUE);
        } elseif (is_file($common_file_path)) {
            if ($cookie_str == $cache_css_folder) {
                $create_flag = FALSE;
            }
            $this->CI->ci_local->write($css_enc_key, $cache_css_folder, -1);
        }

        if ($create_flag) {
            $css_code = '';
            for ($i = 0; $i < count($css_common_array); $i++) {
                if (is_file($css_file_path . $css_common_array[$i])) {
                    $css_code .= '
';
                    $css_code .= file_get_contents($css_file_path . $css_common_array[$i]);
                } else if (!empty($css_cmn_path) != "" && is_file($css_cmn_path . $css_common_array[$i]) && !in_array($css_common_array[$i], $this->css_local_array)) {
                    $css_code .= '
';
                    $css_code .= file_get_contents($css_cmn_path . $css_common_array[$i]);
                }
            }

            $final_css = $css_code;
            $cfp = fopen($common_file_path, 'w+');
            fwrite($cfp, $css_code);
            fclose($cfp);
        }

        $this->css_common_array = array();
        if ($_app_parse == '1') {
            return $cache_css_folder;
        }
        $common_file_url = $this->CI->config->item('admin_css_url') . $dir_name . $common_file_name;
        if ($this->CI->config->item("cdn_activate") == TRUE && $this->CI->config->item("cdn_http_url") != "") {
            $cdn_file_url = str_replace($this->CI->config->item("site_url"), $this->CI->config->item("cdn_http_url"), $common_file_url);
            $out_put = '<link href="' . $cdn_file_url . '" rel="stylesheet"  type="text/css" media="screen" crossorigin="anonymous" />';
            $theme_css_file = $this->css_theme_src();
            if ($theme_css_file != FALSE) {
                $out_put .= '
                        <link href="' . $this->CI->config->item('admin_css_url') . $theme_css_file . '" rel="stylesheet"  type="text/css" media="screen" crossorigin="anonymous" />';
            }
        } else {
            $out_put = '<link href="' . $common_file_url . '" rel="stylesheet"  type="text/css" media="screen" crossorigin="anonymous" />';
        }

        return $out_put;
    }

    /**
     * add_css
     *
     * @desc
     *
     * @category function
     * @access   public
     */
    public function add_css()
    {
        $add_css_array = array();
        $args = func_get_args();

        for ($i = 0, $ni = count($args); $i < $ni; $i++) {
            if ($args[$i] != '') {
                $tmp_css_arr = array();
                if (stristr(",", $args[$i])) {
                    $tmp_css_arr = explode(",", $args[$i]);
                    for ($j = 0, $nj = count($css_arr); $j < $nj; $j++) {
                        $css_arr[$j] = trim($css_arr[$j]);
                    }
                } else {
                    $tmp_css_arr = Array($args[$i]);
                }
                $add_css_array = array_merge($add_css_array, $tmp_css_arr);
            }
        }
        $this->css_file_array = array_merge($this->css_file_array, $add_css_array);
    }

    public function css_src($type = '')
    {
        $css_cmn_path = $this->CI->config->item('cmn_css_path');
        $css_file_path = $this->CI->config->item('css_path');
        $css_cache_path = $this->CI->config->item('css_cache_path');
        $css_cache_url = $this->CI->config->item('css_cache_url');

        $css_file_array = $this->css_file_array;
        $css_file_array = array_values(array_unique($css_file_array));

        $mtime_array = array();
        //get the filemtime
        clearstatcache();
        for ($i = 0; $i < count($css_file_array); $i++) {
            if (is_file($css_file_path . $css_file_array[$i])) {
                $mtime_array[] = filemtime($css_file_path . $css_file_array[$i]);
            }
        }

        $fname = md5(serialize($css_file_array) . serialize($mtime_array));
        $dir_name = "compiled/" . $fname . "/";
        if (!is_dir($css_cache_path . $dir_name)) {
            mkdir($css_cache_path . $dir_name, 0777, TRUE);
        }
        $file_flag = TRUE;
        $combine_file_name = ($this->css_key_pairs[$type] != '') ? $this->css_key_pairs[$type] : "main_combine.css";
        $final_file_name = $css_cache_path . $dir_name . $combine_file_name;
        if (!is_file($final_file_name)) {
            $css_code = '';
            for ($i = 0; $i < count($css_file_array); $i++) {
                if (is_file($css_file_path . $css_file_array[$i])) {
                    $css_code .= '
';
                    $css_code .= file_get_contents($css_file_path . $css_file_array[$i]);
                } elseif (!empty($css_cmn_path) != "" && is_file($css_cmn_path . $css_file_array[$i]) && !in_array($css_file_array[$i], $this->css_local_array)) {
                    $css_code .= '
';
                    $css_code .= file_get_contents($css_cmn_path . $css_file_array[$i]);
                }
            }

            if (trim($css_code) != '') {
                $fp = fopen($final_file_name, 'w+');
                fwrite($fp, $css_code);
                fclose($fp);
            } else {
                $file_flag = FALSE;
            }
        }
        $out_put = '';
        if ($file_flag == TRUE) {
            if ($this->CI->config->item('is_admin')) {
                $combine_file_url = $this->CI->config->item('admin_css_url') . $dir_name . $combine_file_name;
                if (array_key_exists($type, $this->css_key_pairs) && $this->CI->config->item("cdn_activate") == TRUE && $this->CI->config->item("cdn_http_url") != "") {
                    $cdn_file_url = str_replace($this->CI->config->item("site_url"), $this->CI->config->item("cdn_http_url"), $combine_file_url);
                    $out_put = '<link href="' . $cdn_file_url . '" rel="stylesheet"  type="text/css" media="screen" crossorigin="anonymous" />';
                    $theme_css_file = $this->css_theme_src();
                    if ($theme_css_file != FALSE) {
                        $out_put .= '
                        <link href="' . $this->CI->config->item('admin_css_url') . $theme_css_file . '" rel="stylesheet"  type="text/css" media="screen" crossorigin="anonymous" />';
                    }
                } else {
                    $out_put = '<link href="' . $combine_file_url . '" rel="stylesheet"  type="text/css" media="screen" crossorigin="anonymous" />';
                }
            } else {
                $combine_file_url = $this->CI->config->item('css_url') . $dir_name . $combine_file_name;
                if ($this->CI->config->item("cdn_activate") == TRUE && $this->CI->config->item("cdn_http_url") != "") {
                    $cdn_file_url = str_replace($this->CI->config->item("site_url"), $this->CI->config->item("cdn_http_url"), $combine_file_url);
                    $out_put = '<link href="' . $cdn_file_url . '" rel="stylesheet"  type="text/css" media="screen" crossorigin="anonymous" />';
                } else {
                    $out_put = '<link href="' . $combine_file_url . '" rel="stylesheet"  type="text/css" media="screen" crossorigin="anonymous" />';
                }
            }
        }
        $this->css_file_array = array();

        return $out_put;
    }

    public function css_theme_src()
    {
        $css_file_path = $this->CI->config->item('css_path');
        $css_cache_path = $this->CI->config->item('css_cache_path');
        $theme_custom_arr = array(
            "theme/" . $this->CI->config->item('ADMIN_THEME_DISPLAY') . "/" . $this->CI->config->item('ADMIN_THEME_PATTERN'),
            "theme/" . $this->CI->config->item('ADMIN_THEME_CUSTOMIZE')
        );
        $custom_gen_css = "admin/cform_generate.css";

        $custom_array = array();
        for ($i = 0; $i < count($theme_custom_arr); $i++) {
            if (is_file($css_file_path . $theme_custom_arr[$i])) {
                $custom_array[] = filemtime($css_file_path . $theme_custom_arr[$i]);
            }
        }
        $fname = md5(serialize($theme_custom_arr) . serialize($custom_array));
        $dir_name = "compiled/" . $fname . "/";
        if (!is_dir($css_cache_path . $dir_name)) {
            mkdir($css_cache_path . $dir_name, 0777, TRUE);
        }
        $theme_css_dir = $dir_name . "main_theme.css";
        $theme_file_name = $css_cache_path . $dir_name . $theme_css_dir;
        $theme_code = TRUE;
        if (!is_file($theme_file_name)) {
            $css_code = '';
            for ($i = 0; $i < count($theme_custom_arr); $i++) {
                if (is_file($css_file_path . $theme_custom_arr[$i])) {
                    $gen_css = file_get_contents($css_file_path . $theme_custom_arr[$i]);
                    if (trim($gen_css) == "") {
                        continue;
                    }
                    $css_code .= '
';
                    $css_code .= $gen_css;
                }
            }

            if (trim($css_code) != '') {
                $cus_css = file_get_contents($css_file_path . $custom_gen_css);
                $css_code .= '
';
                $css_code .= $cus_css;

                $fp = fopen($theme_file_name, 'w+');
                fwrite($fp, $css_code);
                fclose($fp);

                return $theme_css_dir;
            } else {
                return FALSE;
            }
        } else {
            return $theme_css_dir;
        }
    }
}

/* End of file Css.php */
/* Location: ./application/libraries/Css.php */