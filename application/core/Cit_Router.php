<?php
defined('BASEPATH') || exit('No direct script access allowed');

// load the MX_Router class 
require_once config_item('third_party') . "MX/Router.php";

class Cit_Router extends MX_Router
{

    public function __construct($routing = NULL)
    {
        parent::__construct($routing);
    }

    protected function _parse_routes()
    {
        // Turn the segment array into a URI string
        $uri = implode('/', $this->uri->segments);

        // Get HTTP verb
        $http_verb = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';

        // Is there a literal match?  If so we're done
        if (isset($this->routes[$uri])) {
            // Check default routes format
            if (is_string($this->routes[$uri])) {
                $this->_set_request(explode('/', $this->routes[$uri]));
                return;
            }
            // Is there a matching http verb?
            elseif (is_array($this->routes[$uri]) && isset($this->routes[$uri][$http_verb])) {
                $this->_set_request(explode('/', $this->routes[$uri][$http_verb]));
                return;
            }
        }

        // decryption of module/controller/function name in admin
        if ($this->config->item('is_admin') == 1) {
            if ($this->config->item('ADMIN_URL_ENCRYPTION') == 'Y') {
                $uri_t = str_replace('admin/', '', $uri);
                if ($uri_t != "") {
                    require_once(APPPATH . '/libraries/Ci_encrypt.php');
                    $CI_Enc = new Ci_encrypt();
                    $uri_t_decode = $CI_Enc->decrypt($uri_t, true);
                    $CI_Enc->convertEncryptedVars();
                }
                $uri = 'admin/' . $uri_t_decode;
            }
        }
        // Loop through the route array looking for wildcards
        foreach ($this->routes as $key => $val) {
            // Check if route format is using http verb
            if (is_array($val)) {
                if (isset($val[$http_verb])) {
                    $val = $val[$http_verb];
                } else {
                    continue;
                }
            }

            // Convert wildcards to RegEx
            #$key = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $key);
            $key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));

            // Does the RegEx match?
            if (preg_match('#^' . $key . '$#', $uri, $matches)) {
                // Are we using callbacks to process back-references?
                if (!is_string($val) && is_callable($val)) {
                    // Remove the original string from the matches array.
                    array_shift($matches);

                    // Execute the callback using the values in matches as its parameters.
                    $val = call_user_func_array($val, $matches);
                }
                // Are we using the default routing method for back-references?
                elseif (strpos($val, '$') !== FALSE && strpos($key, '(') !== FALSE) {
                    $val = preg_replace('#^' . $key . '$#', $val, $uri);
                }

                $this->_set_request(explode('/', $val));
                return;
            }
        }

        // If we got this far it means we didn't encounter a
        // matching route so we'll set the site default route
        $this->_set_request(array_values($this->uri->segments));
    }

    protected function _set_404override_controller()
    {
        if ($this->routes['404_override'] != "") {
            $this->_set_request(explode("/", $this->routes['404_override']));
        } else {
            show_404();
        }
    }
}

/* End of file Cit_Router.php */
/* Location: ./application/core/Cit_Router.php */