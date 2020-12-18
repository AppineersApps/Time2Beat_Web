<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Listing Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module Plugins
 * 
 * @class Plugin_formatter.php
 * 
 * @path application\libraries\Plugin_formatter.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 03.01.2017
 */
class Plugin_formatter
{

    protected $CI;

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function autocomplete($result = array(), $keys = array(), $args = '')
    {
        $value_path = $suggestions = $data = array();

        if (isset($keys['value'])) {
            $value_path = explode('.', $keys['value']);
        }
        if (count($value_path)) {
            for ($i = 0; $i <= (count($value_path) - 2); $i++) {
                $data = ($i === 0) ? $result[$value_path[$i]] : $data[$value_path[$i]];
            }
            if (count($data) > 0) {
                $value_key = end($value_path);
                $data_path = explode('.', $keys['data']);
                $data_key = end($data_path);
                if (isset($keys['group']) && $keys['group'] != "") {
                    $group_path = explode('.', $keys['group']);
                    $group_key = end($group_path);
                }
                for ($i = 0; $i < count($data); $i++) {
                    $tmp_arr = array(
                        'value' => $data[$i][$value_key],
                        'data' => $data[$i][$data_key]
                    );
                    if (isset($keys['group']) && $keys['group'] != "") {
                        $tmp_arr['data'] = array(
                            'data' => $data[$i][$data_key],
                            'category' => $data[$i][$group_key]
                        );
                    }
                    $tmp_arr['_all'] = $data[$i];
                    $suggestions[] = $tmp_arr;
                }
            }
        }
        $response = array(
            'suggestions' => $suggestions
        );

        return $response;
    }

    public function dropzone($result = array(), $keys = array(), $args = '')
    {
        $response = $data = array();
        if (count($result) > 0 && $keys > 0) {
            foreach ($keys as $name => $path) {
                $data_path = explode('.', $path);
                $tmp_res = $result;
                foreach ($data_path as $val) {
                    if ($val == "data") {
                        $tmp_res = $tmp_res[$val][0];
                    } else {
                        $tmp_res = $tmp_res[$val];
                    }
                }
                $response[$name] = $tmp_res;
            }
        } else {
            $response = $result;
        }

        return $response;
    }
}

/* End of file Plugin_formatter.php */
/* Location: ./application/libraries/Plugin_formatter.php */