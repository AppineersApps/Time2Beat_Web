<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

Class Url
{

    protected $CI;

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function make($defaults, $pass = '', $params = '', $relative = false)
    {
        if ($pass != "") {
            $pass_arr = explode("/", $pass);
        }

        $defaults_arr = explode("/", $defaults);
        $default_arr['module'] = $defaults_arr['0'];
        $default_arr['controller'] = $defaults_arr['1'];
        $default_arr['func'] = $defaults_arr['2'];

        if ($params != "") {
            $params_arr = explode(",", $params);
            foreach ($params_arr as $k => $v) {
                list($k1, $v1) = explode(":", $v);
                $data[$k1] = $v1;
            }
            $final_arr = array_merge($default_arr, $data);
        } else {
            $final_arr = $default_arr;
        }

        $all_routes = $this->CI->router->routes;
        $_routes = array();
        foreach ($all_routes as $key => $value) {
            $valuearr = explode('/', $value);
            $_routes[] = array(
                $key, '#^/' . $key . '[\/]*$#',
                array(),
                array(
                    'module' => $valuearr[0],
                    'controller' => $valuearr[1],
                    'func' => $valuearr[2]
                )
            );
        }

        foreach ($_routes as $key => $data) {
            $m_Array = $data[3];
            $diff = array_diff_assoc($final_arr, $m_Array);
            if (count($diff) == 0) {
                $passed[] = $_routes[$key];
            }
        }

        if (is_array($passed)) {
            foreach ($passed as $k => $v) {
                if (count($pass_arr) > 0) {
                    if (preg_match_all("|\(.*?\)|", $v[0], $ret)) {

                        $ret = $ret[0];
                        if (count($pass_arr) >= count($ret)) {
                            $seq = "prec_0";
                            $limit = count($pass_arr);
                            $prec[$seq][] = $ret;
                            $return_url = $v[0];
                            for ($k = 0; $k < $limit; $k++) {
                                if (preg_match("/" . $ret[$k] . "/", $pass_arr[$k], $r)) {
                                    $c = "1";
                                    $return_url = preg_replace("/" . preg_quote($ret[$k]) . "/", $pass_arr[$k], $return_url, "1", $c);
                                }
                            }
                        }
                    } else {
                        $return_url = $v[0];
                        $return_url = str_replace("/", "", $return_url);
                        $return_url = str_replace("*", "/", $return_url);
                    }
                } else {
                    if (!preg_match_all("|\(.*?\)|", $v[0], $ret)) {
                        $return_url = $v[0];
                        $return_url = str_replace("/", "", $return_url);
                        $return_url = str_replace("*", "/", $return_url);
                        break;
                    }
                }
            }

            $new_url = $return_url;
            if ($relative != true) {
                if ($this->CI->config->item('is_admin') == "1") {
                    $new_url = $this->CI->config->item('admin_url') . $new_url;
                } else {
                    $new_url = $this->CI->config->item('site_url') . $new_url;
                }
            }
        } else {
            $tmp_def_arr = explode("/", $defaults);
            if ($tmp_def_arr[0] == $tmp_def_arr[1]) {
                $tmp_def_arr[0] = "";
            }
            $defaults = $this->stripEscape(trim(implode("/", $tmp_def_arr), "/"));

            if ($relative == true) {
                $new_url = $this->stripEscape($defaults) . "/";
            } else {
                if ($this->CI->config->item('is_admin') == "1") {
                    $new_url = $this->CI->config->item('admin_url') . $this->stripEscape($defaults) . "/";
                } else {
                    $new_url = $this->CI->config->item('site_url') . $this->stripEscape($defaults) . "/";
                }
            }
        }

        if (is_array($pass_arr)) {
            $pass_arr = array_slice($pass_arr, $k);
        }

        if (count($pass_arr) > 0) {
            $new_url = trim($new_url, "/");
            $ret_link = $new_url . "/" . $this->stripEscape(implode("/", $pass_arr));
        } else {
            $ret_link = $new_url;
        }

        $new_url = trim($new_url, "/");
        return $ret_link;
    }

    public function stripEscape($param)
    {
        if (!is_array($param) || empty($param)) {
            if (is_bool($param)) {
                return $param;
            }
            $return = preg_replace('/^[\\t ]*(?:-!)+/', '', $param);
            return $return;
        }

        foreach ($param as $key => $value) {
            if (is_string($value)) {
                $return[$key] = preg_replace('/^[\\t ]*(?:-!)+/', '', $value);
            } else {
                foreach ($value as $array => $string) {
                    $return[$key][$array] = $this->stripEscape($string);
                }
            }
        }
        return $return;
    }
}
