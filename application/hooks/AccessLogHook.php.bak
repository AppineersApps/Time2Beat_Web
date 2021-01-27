<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of AccessLogHook Controller
 *
 * @author Simhachalam G
 */
class AccessLogHook
{

    public function __construct()
    {
        $this->CI = & get_instance();
        $this->params = array();
        $this->_data_max_size = 1 * 1024 * 1024 * 5; // 5MB
    }

    public function http_request_log($params = array())
    {

        $this->params = $params;

        if ($this->CI->config->item("is_admin") == 1) {
            if (!$this->params['admin']) {
                return FALSE;
            }
            if (!$this->CI->uri->segments[2]) {
                return FALSE;
            }
            if (is_array($this->params['admin_system_calls'])) {
                $system_calls = $this->params['admin_system_calls'];
                $ctrl_arr = $system_calls[$this->CI->uri->segments[2]][$this->CI->uri->segments[3]];
                if (is_array($ctrl_arr) && in_array($this->CI->uri->segments[4], $ctrl_arr)) {
                    return FALSE;
                }
            }
            $type = "Admin";
            $request_func = '';
            if ($this->CI->uri->segments[2]) {
                $request_func = $this->CI->uri->segments[2] . "/" . $this->CI->uri->segments[3] . "/" . $this->CI->uri->segments[4];
            }
            $file_suffix = "admin";
        } elseif ($this->CI->uri->segments[1] && in_array($this->CI->uri->segments[1], array("PS"))) {
            if (!$this->params['parseapi']) {
                return FALSE;
            }
            if (!$this->CI->uri->segments[2]) {
                return FALSE;
            }
            $type = "ParseAPI";
            $uris = $this->CI->uri->segments;
            unset($uris[1]);
            $request_func = implode("~~", $uris);
            $file_suffix = "ps";
        } elseif ($this->CI->uri->segments[1] && in_array($this->CI->uri->segments[1], array("WS"))) {
            if (!$this->CI->uri->segments[2]) {
                return FALSE;
            }
            if ($this->CI->uri->segments[2] == "image_resize") {
                return FALSE;
            }
            if (!$this->params['webservice']) {
                return FALSE;
            }
            $type = "Webservice";
            $request_func = $this->CI->uri->segments[2];
            $file_suffix = "ws";
        } elseif ($this->CI->uri->segments[1] && in_array($this->CI->uri->segments[1], array("NS"))) {
            if (!$this->params['notification']) {
                return FALSE;
            }
            if (!$this->CI->uri->segments[2]) {
                return FALSE;
            }
            $type = "Notification";
            $request_func = $this->CI->uri->segments[2];
            $file_suffix = "ns";
        } else {
            if (!$this->params['front']) {
                return FALSE;
            }
            $type = "Front";
            $request_func = ($this->CI->uri->segments[1]) ? "" : $this->CI->uri->segments[1];
            $file_suffix = "front";
        }
        $this->params['type'] = $type;
        $this->params['function'] = $request_func;

        $access_log_folder = $this->CI->config->item('admin_access_log_path');
        if (!is_dir($access_log_folder)) {
            $this->CI->general->createFolder($access_log_folder);
        }

        $log_date_format = $this->params['folder_date_format'];
        if (!$log_date_format) {
            $log_date_format = "Y-m-d";
        }
        $log_folder_name = date($log_date_format);

        $log_folder_path = $access_log_folder . $log_folder_name . DS;
        if (!is_dir($log_folder_path)) {
            $this->CI->general->createFolder($log_folder_path);
        }

        $log_file_name = $this->params['file_name'];
        if (!$log_file_name) {
            $log_file_name = "log";
        }

        $log_file_ext = $this->params['file_extension'];
        if (!$log_file_ext) {
            $log_file_ext .= "txt";
        }

        $exe_file_name = $this->params['exec_name'];
        if (!$exe_file_name) {
            $exe_file_name = "exe";
        }

        $exe_file_ext = $this->params['exec_extension'];
        if (!$exe_file_ext) {
            $exe_file_ext .= "txt";
        }

        $log_file_path = $log_folder_path . $log_file_name . "-" . $file_suffix . "." . $log_file_ext;
        $exe_file_path = $log_folder_path . $exe_file_name . "-" . $file_suffix . "." . $exe_file_ext;

        list($log_data, $exe_data) = $this->get_logging_data();

        $fp = fopen($log_file_path, 'a+');
        fwrite($fp, $log_data);
        fclose($fp);

        if (in_array($type, array("Webservice", "Notification"))) {
            $exe_data['path'] = $exe_file_path;
            if (!is_file($exe_file_path)) {
                $fp = fopen($exe_file_path, 'a+');
                fclose($fp);
            }
            if ($type == "Webservice") {
                $this->CI->config->set_item("_WS_EXEC_DATA", $exe_data);
            } elseif ($type == "Notification") {
                $this->CI->config->set_item("_NS_EXEC_DATA", $exe_data);
            }
        }
    }

    public function get_logging_data()
    {
        $params_arr = array();
        $date_format = $this->params['log_date_format'];
        $request_type = $this->params['type'];
        $request_func = $this->params['function'];
        if (!$date_format) {
            $date_format = "Y-m-d H:i:s";
        }
        $date_str = date($date_format);
        $url = $this->CI->config->item("site_url") . $this->CI->uri->uri_string . "/";
        $ip_addr = $this->get_http_real_ip_addr();
        $user_agent = $this->get_http_user_agent();
        $plat_form = $this->get_platform($user_agent);
        $bowser = $this->get_browser($user_agent);
        $input_params_arr = $this->get_http_request_params();
        $input_params_str = (is_array($input_params_arr) && count($input_params_arr) > 0) ? serialize($input_params_arr) : "";

        $log_str = <<<EOD
{$ip_addr}~~{$request_func}~~{$request_type}~~{$date_str}~~{$url}~~{$user_agent}~~{$plat_form}~~{$bowser}~~{$input_params_str}

EOD;

        $start_time = microtime();
        $exe_arr = array(
            "ip_addr" => $ip_addr,
            "method" => $request_func,
            "start" => $start_time
        );

        return array($log_str, $exe_arr);
    }

    public function get_http_real_ip_addr()
    {
        $ip = $this->CI->general->getHTTPRealIPAddr();
        return $ip;
    }

    public function get_http_user_agent()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        return $user_agent;
    }

    public function get_platform($user_agent = '')
    {

        $os_platform = "Unknown OS Platform";

        $os_array = array(
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile'
        );

        foreach ($os_array as $regex => $value) {

            if (preg_match($regex, $user_agent)) {
                $os_platform = $value;
            }
        }
        return $os_platform;
    }

    public function get_browser($user_agent = '')
    {

        $browser = "Unknown Browser";

        $browser_array = array(
            '/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i' => 'Handheld Browser'
        );

        foreach ($browser_array as $regex => $value) {

            if (preg_match($regex, $user_agent)) {
                $browser = $value;
            }
        }

        return $browser;
    }

    public function get_http_request_params()
    {
        $get_arr = $this->CI->input->get(NULL);
        $post_arr = $this->CI->input->post(NULL);
        $stream_arr = $this->CI->input->input_stream(NULL);
        $headers_arr = $this->CI->input->request_headers();
        $get_arr = is_array($get_arr) ? $get_arr : array();
        $post_arr = is_array($post_arr) ? $post_arr : array();
        $stream_arr = is_array($stream_arr) ? $stream_arr : array();
        $headers_arr = is_array($headers_arr) ? $headers_arr : array();

        $req_params = array();
        $req_params['method'] = $_SERVER['REQUEST_METHOD'];
        if (is_array($headers_arr) && count($headers_arr) > 0) {
            $unset_headers = array(
                "Host", "Connection", "Cache-Control", "Upgrade-Insecure-Requests",
                "User-Agent", "Accept", "Accept-Encoding", "Accept-Language", "Cookie"
            );
            foreach ($unset_headers as $key => $val) {
                unset($headers_arr[$key]);
            }
        }
        if (is_array($get_arr) && count($get_arr) > 0) {
            $req_params['get'] = $get_arr;
        }
        if (is_array($post_arr) && count($post_arr) > 0) {
            $req_params['post'] = $post_arr;
        }
        if (is_array($stream_arr) && count($stream_arr) > 0) {
            $req_params['stream'] = $stream_arr;
        }

        return $req_params;
    }

    public function is_data_exceeds_limit($data = '')
    {
        if (strlen($data) > $this->_data_max_size) {
            return TRUE;
        }
        return FALSE;
    }
}

/* End of file AccessLogHook.php */
/* Location: ./application/hooks/AccessLogHook.php */