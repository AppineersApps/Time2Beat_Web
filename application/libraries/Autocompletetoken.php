<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Autocompletetoken
{

    protected $CI = '';
    protected $js = '';

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function activate()
    {
        $this->CI->js->add_js('libraries/autocomplete_token/jquery.tokeninput.js');
        $this->CI->css->add_css('libraries/autocomplete_token/token-input.css');
        $this->CI->css->add_css('libraries/autocomplete_token/token-input-facebook.css');
        $this->CI->css->add_css('libraries/autocomplete_token/token-input-mac.css');
    }

    public function auto($id, $url, $property, $params = null)
    {
        $extraparam = $this->generateParamsString($params);
        $this->js .= "$('#$id').tokenInput('$url',{
		        $extraparam
                 propertyToSearch:'$property'
		 });";
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