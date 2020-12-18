<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Uploadify
{

    public function __construct($params)
    {
        //$this->name = $params['name'];
        $this->CI = & get_instance();

        $css_file_path = $this->CI->config->item('css_path') . "libraries/uploadify" . '/' . "uploadify.css";
        $css_related_file_path = $this->CI->config->item('css_path') . "libraries/uploadify" . '/' . "related.css";
        $js_file_path = $this->CI->config->item('js_path') . "libraries/uploadify" . '/' . "jquery.uploadify.js";


        try {
            if (is_file($js_file_path)) {
                $this->CI->js->add_js("libraries/uploadify/jquery.uploadify.js");
            } else {
                throw new MainException();
            }
        } catch (MainException $e) {
            $message = "uploadify," . $js_file_path;
            $e->handleError($message, "JS", "File");
        }

        try {
            if (is_file($css_file_path)) {

                $this->CI->css->add_css("libraries/uploadify/uploadify.css");
            } else {
                throw new MainException();
            }
        } catch (MainException $e) {
            $message = "uploadify.css," . $css_file_path;
            $e->handleError($message, "CSS", "File");
        }

        try {
            if (is_file($css_related_file_path)) {

                $this->CI->css->add_css("libraries/uploadify/related.css");
            } else {
                throw new MainException();
            }
        } catch (MainException $e) {
            $message = "related.css," . $css_related_file_path;
            $e->handleError($message, "CSS", "File");
        }
    }

    public function get_upload()
    {

        $arg_list = func_get_args();
        $input_id = $arg_list[0];
        $params_string = $arg_list[1];
        $height = $arg_list[2];
        $width = $arg_list[3];
        $title = "";
        $ext_string = '';

        if ($params_string != "") {
            $params_array_1 = explode(",", $params_string);
            foreach ($params_array_1 as $key => $value) {
                list($v1, $v2) = explode(":", $value);
                $paramArr[$v1] = $v2;
                $v2 = str_replace('|', ';', $v2);
                $ext_string .= "'$v1':$v2";
                $ext_string .= ",";
            }
        }

        $css_url = $this->CI->config->item('css_url');
        $js_url = $this->CI->config->item('js_url');
        $base = $this->CI->config->item('site_url');

        $render_arr = array(
            'input_id' => $input_id,
            'base' => $base,
            'css_url' => $css_url,
            'ext_string' => $ext_string,
            'height' => $height,
            'width' => $width
        );

        return $this->CI->parser->parse("libraries/uploadify.tpl", $render_arr, true);
    }
}
