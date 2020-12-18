<?php
defined('BASEPATH') || exit('No direct script access allowed');

//load the MX_Controller class
require_once config_item('third_party') . "MX/Controller.php";

class Cit_Controller extends MX_Controller
{

    protected $include_script_template = "";
    protected $main_template_file = "";
    protected $load_view_file = "";
    protected $skip_load_view = false;
    public $scheduled_action = array();
    public $scheduled_filter = array();

    public function __construct()
    {
        parent::__construct();
    }

    public function _remap($method, $args)
    {
        // Call before action
        $this->before();
        $this->callFirst();
        $this->set_template_file();
        if (!method_exists($this, $method)) {
            show_error('We can not find the page you are looking for.', 400);
        }
        call_user_func_array(array($this, $method), $args);

        $this->set_smarty_val();
        // Call after action
        $this->callLast();
        $this->after();
        if ($this->skip_load_view == false) {
            $this->load_main_view();
        }
    }

    public function __call($name, $arguments)
    {
        
    }

    public function set_smarty_val()
    {
        $render_arr = array(
            'site_url' => $this->config->item('site_url'),
            'admin_url' => $this->config->item('admin_url'),
            'page_html_class' => get_page_html_class($this->router->fetch_class())
        );
        if ($this->config->item('is_admin') === true) {
            $render_arr['rl_theme_arr'] = $this->general->getServerThemeArr();
        }
        $this->smarty->assign($render_arr);
    }

    private function load_main_view()
    {
        $main_template_file = $this->get_template();
        $this->process_view();
        $include_script_template = $this->get_script_template();
        if ($include_script_template) {
            $this->smarty->assign('include_script_template', $include_script_template);
        }
        if ($this->input->is_ajax_request()) {
            $this->parser->parse($include_script_template);
        } else {
            if ($main_template_file != '') {
                $this->parser->parse($main_template_file);
            } elseif ($include_script_template != '') {
                $this->parser->parse($include_script_template);
            }
        }
    }

    protected function set_template_file($file_name = "")
    {
        if ($file_name == '') {
            $file_name = $this->config->item('view_layout') . '.tpl';
        }
        $main_template_file = $file_name;
        if ($this->config->item('is_admin') === true) {
            if ($this->input->get_post('iframe') == "true") {
                $main_template_file = $this->config->item('admin_frame_layout') . '.tpl';
            } else {
                $main_template_file = $this->config->item('admin_view_layout') . '.tpl';
            }
        }
        $this->main_template_file = $main_template_file;
    }

    protected function skip_template_view()
    {
        $this->skip_load_view = true;
    }

    protected function set_template($file_name)
    {
        $this->main_template_file = $file_name;
    }

    protected function get_template()
    {
        return $this->main_template_file;
    }

    protected function get_script_template()
    {
        return $this->include_script_template;
    }

    protected function set_script_template($include_script_template)
    {
        $this->include_script_template = $include_script_template;
    }

    protected function loadView($view_file, $render_arr = array())
    {
        $this->load_view_file = $view_file;
        if (is_array($render_arr) && count($render_arr) > 0) {
            $this->smarty->assign($render_arr);
        }
    }

    private function process_view()
    {
        if ($this->load_view_file != '') {
            $view_file = $this->load_view_file;
            if (strstr($view_file, '/')) {
                $file_arr = explode('/', $view_file);
                if ($this->config->item('is_admin') === true) {
                    $module_str = $this->config->item('admin_folder');
                } elseif ($this->config->item('is_webservice') === true) {
                    $module_str = $this->config->item('webservice_folder');
                } elseif ($this->config->item('is_notification') === true) {
                    $module_str = $this->config->item('notification_folder');
                } else {
                    $module_str = $this->config->item('front_folder');
                }
                $this->smarty->addTemplateDir(APPPATH . $module_str . '/' . $file_arr[0] . '/views');
                $this->parser->addTemplateLocation(APPPATH . $module_str . '/' . $file_arr[0] . '/views');
                $template_str = $file_arr[1] . '.tpl';
            } else {
                $template_str = $this->load_view_file . '.tpl';
            }
        } else {
            $template_str = $this->router->method . '.tpl';
        }
        $this->set_script_template($template_str);
    }

    protected function before()
    {
        if ($this->config->item('is_admin') === true && $this->session->userdata("iAdminId") > 0) {
            if ($this->input->get_post("newRequest") == "true") {
                $this->session->set_userdata('queryLogFile', md5(time()));
            }
            if ($this->router->method != 'sess_expire') {
                if ($this->general->logOutChecking() === true) {
                    if ($this->input->is_ajax_request()) {
                        $this->output->set_header("Cit-auth-requires: 1");
                    } else {
                        redirect($this->config->item("admin_url") . $this->general->getAdminEncodeURL("user/login/sess_expire"));
                    }
                } elseif ($this->router->method != 'notify_events') {
                    $sess_array = array();
                    $sess_array['loggedAt'] = time();
                    $sess_array['timeOut'] = $this->general->getSessionExpire();
                    $this->session->set_userdata($sess_array);
                }
            }
        }
        return;
    }

    protected function callFirst()
    {
        $controller = $this->router->fetch_class();
        $model = $controller . "_model";
        $method = $this->router->fetch_method();
        if($method != 'addAction'){
            return false;
        }
        $data_arr = array();
        if (is_object($this->$model)) {
            $id = $this->input->get_post('id', TRUE);
            $data_arr = $this->$model->getData($id, '*');
        }
        return $data_arr;
    }

    protected function callLast()
    {
        $controller = $this->router->fetch_class();
        $model = $controller . "_model";
        $method = $this->router->fetch_method();
        if($method != 'addAction'){
            return false;
        }
        $data_arr = array();
        if (is_object($this->$model)) {
            $res_arr = $this->response_arr;
            $id = $res_arr['id'];
            $data_arr = $this->$model->getData($id, '*');
        } 
        return $data_arr;
    }

    protected function after()
    {
        return;
    }

    public function do_action($tag)
    {
        if (!isset($this->scheduled_action[$tag])) {
            return;
        }
        foreach ($this->scheduled_action[$tag] as $callable) {
            $callable();
        }
    }

    public function apply_filter($tag, $data)
    {
        if (!isset($this->scheduled_filter[$tag])) {
            return $data;
        }
        foreach ($this->scheduled_filter[$tag] as $callable) {
            $data = $callable($data);
        }
        return $data;
    }

    public function add_filter($tag, $callback)
    {
        $this->scheduled_filter[$tag][] = $callback;
    }

    public function add_action($tag, $callback)
    {
        $this->scheduled_action[$tag][] = $callback;
    }
}

/* End of file Cit_Controller.php */
/* Location: ./application/core/Cit_Controller.php */