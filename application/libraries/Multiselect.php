<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Multiselect
{

    public function __construct()
    {
        $this->CI = & get_instance();

        $css_file_path = $this->CI->config->item('css_path') . "libraries/multiselect" . '/' . "ui.multiselect.css";

        $js_file_path = $this->CI->config->item('js_path') . "libraries/multiselect" . '/' . "ui.multiselect.js";
        $js_scroll_file_path = $this->CI->config->item('js_path') . "libraries/multiselect" . '/' . "jquery.scrollTo.js";
        $js_multi = $this->CI->config->item('js_path') . "libraries/multiselect" . '/' . "jquery.localisation-min.js";

        try {
            if (is_file($js_file_path)) {
                $this->CI->js->add_js("libraries/multiselect/ui.multiselect.js");
            } else {
                throw new MainException();
            }
        } catch (MainException $e) {
            $message = "multiselect," . $js_file_path;
            $e->handleError($message, "JS", "File");
        }


        try {
            if (is_file($css_file_path)) {

                $this->CI->css->add_css("libraries/multiselect/ui.multiselect.css");
            } else {
                throw new MainException();
            }
        } catch (MainException $e) {
            $message = "multiselect.css," . $css_file_path;
            $e->handleError($message, "CSS", "File");
        }


        try {
            if (is_file($js_scroll_file_path)) {
                $this->CI->js->add_js("libraries/multiselect/jquery.scrollTo.js");
            } else {
                throw new MainException();
            }
        } catch (MainException $e) {
            $message = "multiselect," . $js_scroll_file_path;
            $e->handleError($message, "JS", "File");
        }

        try {
            if (is_file($js_multi)) {
                $this->CI->js->add_js("libraries/multiselect/jquery.localisation-min.js");
            } else {
                throw new MainException();
            }
        } catch (MainException $e) {
            $message = "multiselect," . $js_multi;
            $e->handleError($message, "JS", "File");
        }
    }

    public function display()
    {
        $render_arr = array();
        return $this->CI->parser->parse("libraries/multiselect.tpl", $render_arr, true);
    }
}