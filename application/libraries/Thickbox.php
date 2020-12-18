<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Thickbox
{

    public function __construct()
    {
        $this->CI = & get_instance();

        $css_file_path = $this->CI->config->item('css_path') . "libraries/thickbox/thickbox.css";
        $js_file_path = $this->CI->config->item('js_path') . "libraries/thickbox/thickbox.js";

        try {
            if (is_file($js_file_path)) {
                $this->CI->js->add_js("libraries/thickbox/thickbox.js");
            } else {
                throw new MainException();
            }
        } catch (MainException $e) {
            $message = "uploadify," . $js_file_path;
            $e->handleError($message, "JS", "File");
        }

        try {
            if (is_file($css_file_path)) {
                $this->CI->css->add_css("libraries/thickbox/thickbox.css");
            } else {
                throw new MainException();
            }
        } catch (MainException $e) {
            $message = "uploadify.css," . $css_file_path;
            $e->handleError($message, "CSS", "File");
        }
    }

    public function inline_content()
    {
        $arg_list = func_get_args();
        $idElement = $arg_list[0];
        $idInput = $arg_list[1];
        $display_name = $arg_list[2];
        $params_string = $arg_list[3];

        if ($params_string != "") {
            $params_array = explode(",", $params_string);
            foreach ($params_array as $key => $value) {
                list($v1, $v2) = explode(":", $value);
                $$v1 = $v2;
            }
        }


        if ($height == "") {
            $height = "300";
        }
        if ($width == "") {
            $width = "500";
        }
        if ($type != "button" && $type != "anchor") {
            $type = "anchor";
        }
        /* $render_arr = array(

          'idElement' => $idElement,
          'idInput' => $idInput,
          'display_name' => $display_name,
          'height' => $height,
          'width' => $width

          ); */
        if ($type == "anchor") {
            $return_string = '<a href="javascript:void(0);" rel="#TB_inline?height=' . $height . '&width=' . $width . '&inlineId=' . $idInput . '" id="' . $idElement . '">' . $display_name . '</a>';
        } else if ($type == "button") {
            $return_string = '<input type="button" alt="#TB_inline?height=' . $height . '&width=' . $width . '&inlineId=' . $idInput . '" rel="thickinline" id="' . $idElement . '" value="' . $display_name . '"/>';
        }
        $this->CI->js->javascript_code .= '$("#' . $idElement . '").click(function(){
                                                var url = this.alt || this.rel;
                                                tb_show("' . $title . '", url);
                                                this.blur();
                                                return false;
                                          });';
        return $return_string;
    }

    public function iFrame()
    {
        $arg_list = func_get_args();
        $idElement = $arg_list[0];
        $display_name = $arg_list[1];
        $url = $arg_list[2];
        $params_string = $arg_list[3];

        if ($params_string != "") {

            $params_array_1 = explode(",", $params_string);
            foreach ($params_array_1 as $key => $value) {
                list($v1, $v2) = explode(":", $value);
                $$v1 = $v2;
            }
        }
        if ($height == "") {
            $height = "300";
        }
        if ($width == "") {
            $width = "500";
        }
        if ($type != "button" && $type != "anchor") {
            $type = "anchor";
        }
        if ($type == "anchor") {

            $return_string = '<a href="javascript:void(0);" rel="' . $url . '?KeepThis=true&TB_iframe=true&height=' . $height . '&width=' . $width . '" id="' . $idElement . '">' . $display_name . '</a>';
        } else if ($type == "button") {
            $return_string = '<input type="button" alt="' . $url . '?KeepThis=true&TB_iframe=true&height=' . $height . '&width=' . $width . '" rel="thickinline" id="' . $idElement . '" value="' . $display_name . '"/>';
        }
        $this->CI->js->javascript_code .= '$("#' . $idElement . '").click(function(){
                                                var url = this.alt || this.rel;
                                                tb_show("' . $title . '", url );
                                                this.blur();
                                                return false;
                                          });';
        return $return_string;
    }

    public function ajax()
    {
        $arg_list = func_get_args();
        $idElement = $arg_list[0];
        $display_name = $arg_list[1];
        $url = $arg_list[2];
        $params_string = $arg_list[3];

        if ($params_string != "") {
            $params_array_1 = explode(",", $params_string);
            foreach ($params_array_1 as $key => $value) {
                list($v1, $v2) = explode(":", $value);
                $$v1 = $v2;
            }
        }

        if (!isset($callback)) {
            $callback = '""';
        }

        if ($height == "") {
            $height = "300";
        }
        if ($width == "") {
            $width = "500";
        }
        if ($type != "button" && $type != "anchor") {
            $type = "anchor";
        }
        if ($type == "anchor") {
            $return_string = '<a href="javascript:void(0);" rel="' . $url . '?modal=' . $modal . '&height=' . $height . '&width=' . $width . '" id="' . $idElement . '">' . $display_name . '</a>';
        } else if ($type == "button") {
            $return_string = '<input type="button" alt="' . $url . '?modal=' . $modal . '&height=' . $height . '&width=' . $width . '" id="' . $idElement . '" value="' . $display_name . '"/>';
        }

        $this->CI->js->javascript_code .= '$("#' . $idElement . '").click(function(){
                                                var url = this.alt || this.rel;
                                                tb_show("' . $title . '", url, "", ' . $callback . ', "' . $funcparams . '");
                                                this.blur();
                                                return false;
                                          });';
        return $return_string;
    }
}
