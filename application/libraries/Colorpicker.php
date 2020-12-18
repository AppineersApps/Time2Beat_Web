<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Colorpicker
{

    protected $CI = '';
    protected $js = '';

    public function __construct($params = null)
    {
        $this->CI = & get_instance();
    }

    public function activate()
    {
        $this->CI->js->add_js('jquery-ui-1.10.js');
        $this->CI->js->add_js('libraries/colorpicker/colorpicker.js');
        $this->CI->js->add_js('libraries/colorpicker/pantone.js');
        $this->CI->css->add_css('libraries/colorpicker/colorpicker.css');
        $this->CI->css->add_css('jqueryui/jquery-ui-1.9.2.custom.css');
    }

    public function basic($id, $param = null)
    {
        $params = $this->generateParamsString($param);
        $render_arr = array(
            'param' => $params,
            'id' => $id
        );
        $this->js .= $this->CI->parser->parse("libraries/color.tpl", $render_arr, true);
    }

    public function full($id, $param = null)
    {
        $params = $this->generateParamsString($param);
        $extra = array("parts" => "full");
        $array = $this->generateParamsString($extra);
        $render_arr = array(
            'param' => $params,
            'array' => $array,
            'id' => $id
        );
        $this->js .= $this->CI->parser->parse("libraries/color.tpl", $render_arr, true);
    }

    public function websafe($id, $param = null)
    {
        $params = $this->generateParamsString($param);
        $extra = array("limit" => "websafe");
        $array = $this->generateParamsString($extra);
        $render_arr = array(
            'param' => $params,
            'array' => $array,
            'id' => $id
        );
        $this->js .= $this->CI->parser->parse("libraries/color.tpl", $render_arr, true);
    }

    public function applytofield($id, $field, $prop, $param = null)
    {
        $params = $this->generateParamsString($param);
        $array = $this->generateParamsString(array("altField" => "." . $field, "altProperties" => $prop, "altAlpha" => true, "alpha" => true));
        $render_arr = array(
            'param' => $params,
            'array' => $array,
            'id' => $id
        );
        $this->js .= $this->CI->parser->parse("libraries/color.tpl", $render_arr, true);
    }

    public function dialog($btnid, $divid, $param = null)
    {
        $params = $this->generateParamsString($param);
        $render_arr = array(
            'dialog' => true,
            'param' => $params,
            'array' => $array,
            'btnid' => $btnid,
            'divid' => $divid
        );
        $this->js .= $this->CI->parser->parse("libraries/color.tpl", $render_arr, true);
    }

    public function modal($id, $param = null)
    {
        $params = $this->generateParamsString($param);
        $array = $this->generateParamsString(array("parts" => "draggable", "showCloseButton" => "false", "modal" => true, "showCancelButton" => "false"));
        $render_arr = array(
            'param' => $params,
            'array' => $array,
            'id' => $id
        );
        $this->js .= $this->CI->parser->parse("libraries/color.tpl", $render_arr, true);
    }

    public function element($tag, $param = null)
    {
        $params = $this->generateParamsString($param);
        $render_arr = array(
            'param' => $params,
            'id' => "",
            'tag' => $tag
        );
        $this->js .= $this->CI->parser->parse("libraries/color.tpl", $render_arr, true);
    }

    public function hidden($id, $btnid, $param = null)
    {
        $params = $this->generateParamsString($param);
        $render_arr = array(
            'hidden' => true,
            'param' => $params,
            'id' => $id,
            'btnid' => $btnid
        );
        $this->js .= $this->CI->parser->parse("libraries/color.tpl", $render_arr, true);
    }

    public function getjs()
    {
        return $this->js;
    }

    public function generateParamsString($params_string)
    {
        $return_params_string = "";
        if (count($params_string) > 0) {
            foreach ($params_string as $key => $value) {
                if (is_numeric($value) || is_bool($value)) {
                    $return_params_string .= "'" . $key . "':" . $value . ",";
                } else {
                    $return_params_string .= "'" . $key . "':'" . $value . "',";
                }
            }
        }
        return $return_params_string;
    }
}
