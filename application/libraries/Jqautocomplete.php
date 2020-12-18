<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Jqautocomplete
{

    protected $CI = '';
    protected $js = '';

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function activate()
    {
        $this->CI->js->add_js('libraries/jqautocomplete/jquery.mockjax.js');
        $this->CI->js->add_js('libraries/jqautocomplete/jquery.autocomplete.js');
        $this->CI->css->add_css('libraries/jqautocomplete/jqautocomplete.css');
    }

    public function auto($idInput, $url, $params = null)
    {
        $extraparam = $this->generateParamsString($params);
        $a = 1;
        $this->js .= '
                var autocomplete = $("#' . $idInput . '").autocomplete({
                serviceUrl:"' . $url . '",
                ' . $extraparam . ' 	
                zIndex: 9999,
                onSelect: function(value){ 
                      console.log(value);  
                    ' . $params["onselect"] . '
                 
                },
             });
        ';
    }

    public function local($idInput, $local, $params = null)
    {
        $extraparam = $this->generateParamsString($params);
        $this->js .= '
                var autocomplete = $("#' . $idInput . '").autocomplete({
                lookup:' . $local . ',
                ' . $extraparam . ' 	
                zIndex: 9999,
                  onSelect: function(value){ 
                    ' . $params["onselect"] . '
                  },
             });
        ';
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
