<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of AuthCallbackHook Controller
 *
 * @author Simhachalam G
 */
class AuthCallbackHook
{

    public function __construct()
    {
        $this->CI = & get_instance();
        $this->params = array();
    }

    public function auth_custom_callback($params = array())
    {
        $this->params = $params;

        $auth_callback = $this->CI->config->item('auth_callback');
        if ($auth_callback != "" && method_exists($this->CI->general, $auth_callback)) {
            if ($this->CI->config->item("is_admin") == 1) {
                if ($this->CI->input->is_ajax_request()) {
                    $this->CI->general->$auth_callback('admin', 'ajax');
                } elseif ($this->CI->input->get_post('iframe', TRUE) == 'true') {
                    $this->CI->general->$auth_callback('admin', 'iframe');
                } else {
                    $this->CI->general->$auth_callback('admin', 'page');
                }
            } elseif ($this->CI->uri->segments[1] && in_array($this->CI->uri->segments[1], array("PS"))) {
                $this->CI->general->$auth_callback('parse', 'request');
            } elseif ($this->CI->uri->segments[1] && in_array($this->CI->uri->segments[1], array("WS"))) {
                $this->CI->general->$auth_callback('webservice', 'request');
            } elseif ($this->CI->uri->segments[1] && in_array($this->CI->uri->segments[1], array("NS"))) {
                $this->CI->general->$auth_callback('notification', 'request');
            } else {
                if ($auth_callback != "" && method_exists($this->CI->general, $auth_callback)) {
                    if ($this->CI->input->is_ajax_request()) {
                        $this->CI->general->$auth_callback('front', 'ajax');
                    } else {
                        $this->CI->general->$auth_callback('front', 'page');
                    }
                }
            }
        }
    }
}

/* End of file AuthCallbackHook.php */
/* Location: ./application/hooks/AuthCallbackHook.php */