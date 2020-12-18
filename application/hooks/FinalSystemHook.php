<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Final System Hook
 *
 * @author Simhachalam G
 */
class FinalSystemHook
{

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function final_actions()
    {
        if ($this->CI->config->item('is_admin') == 1) {
            $this->CI->ci_local->complete($this->CI->session->userdata("iAdminId"));
        }
        if (isset($_ENV['debug_action']) && $_ENV['debug_action'] == 1) {
            $this->log_queries();
        }
    }

    public function log_queries()
    {
        if (!is_object($this->CI->db)) {
            return FALSE;
        }
        $queries = $this->CI->db->queries;
        $times = $this->CI->db->query_times;
        $modes = $this->CI->db->query_modes;

        $output = '';
        if (count($queries) == 0) {
            $output .= "no queries\n";
        } else {
            $skip_arr = array(
                "SELECT CASE WHEN (@@OPTIONS | 256) = @@OPTIONS THEN 1 ELSE 0 END AS qi",
                "SELECT  TOP " . $this->CI->config->item("db_max_limit") . " " . $this->CI->db->protect("vName") . ", " . $this->CI->db->protect("vValue") . " FROM " . $this->CI->db->protect("mod_setting"),
                "SELECT " . $this->CI->db->protect('vName') . ", " . $this->CI->db->protect('vValue') . " FROM " . $this->CI->db->protect('mod_setting'),
                "SELECT * FROM " . $this->CI->db->protect('mod_language') . " WHERE " . $this->CI->db->protect('eStatus') . " = 'Active'",
                "SELECT " . $this->CI->db->protect("ms.vName") . ", IF(" . $this->CI->db->protect("ms.eLang") . " = " . $this->CI->db->escape("Yes") . ", " . $this->CI->db->protect("msl.vValue") . ", " . $this->CI->db->protect("ms.vValue") . ") AS " . $this->CI->db->protect("lang_value") . " FROM " . $this->CI->db->protect("mod_setting") . " AS " . $this->CI->db->protect("ms"),
                "SELECT " . $this->CI->db->protect("vTableName") . ", " . $this->CI->db->protect("eExpireTime") . " FROM " . $this->CI->db->protect("mod_cache_tables"),
                "SELECT * FROM " . $this->CI->db->protect("mod_admin_menu") . " WHERE " . $this->CI->db->protect("iAdminMenuId") . " IN ",
                "SELECT " . $this->CI->db->protect("mgr.*") . " FROM " . $this->CI->db->protect("mod_group_rights") . " " . $this->CI->db->protect("mgr") . " JOIN " . $this->CI->db->protect("mod_admin_menu") . " " . $this->CI->db->protect("mam") . " ON " . $this->CI->db->protect("mam.iAdminMenuId") . " = " . $this->CI->db->protect("mgr.iAdminMenuId"),
                "SELECT " . $this->CI->db->protect("mgr.*") . " FROM " . $this->CI->db->protect("mod_group_rights") . " AS " . $this->CI->db->protect("mgr") . " LEFT JOIN " . $this->CI->db->protect("mod_admin_menu") . " AS " . $this->CI->db->protect("mam") . " ON " . $this->CI->db->protect("mam.iAdminMenuId") . " = " . $this->CI->db->protect("mgr.iAdminMenuId"),
                "SELECT " . $this->CI->db->protect("m.vMenuDisplay") . " AS " . $this->CI->db->protect("subMenu") . ", " . $this->CI->db->protect("m.vURL") . " AS " . $this->CI->db->protect("subURL") . ", " . $this->CI->db->protect("s.vMenuDisplay") . " AS " . $this->CI->db->protect("mainMenu") . ", " . $this->CI->db->protect("s.vURL") . " AS " . $this->CI->db->protect("mainURL") . " FROM " . $this->CI->db->protect("mod_admin_menu") . " AS " . $this->CI->db->protect("m"),
                "INSERT INTO " . $this->CI->db->protect("mod_admin_navigation_log"),
            );
            $date_str = date("Y-m-d H:i:s");
            $ip_addr = $this->CI->general->getHTTPRealIPAddr();
            $url_str = $this->CI->config->item("site_url") . $this->CI->uri->uri_string . "/";
            $method = $_SERVER['REQUEST_METHOD'];
            for ($i = count($queries) - 1; $i >= 0; $i--) {
                $query_str = $queries[$i];
                $query_str = str_replace(array("\n", "\r"), " ", $query_str);
                foreach ($skip_arr as $needle) {
                    if (strpos($query_str, $needle) === FALSE) {
                        continue 1;
                    } else {
                        continue 2;
                    }
                }

                $query_time = sprintf("%.3f", $times[$i] * 1000);
                $query_mode = $modes[$i];
                $output .= <<<EOD
{$query_str}~~~~{$query_time}~~~~{$ip_addr}~~~~{$query_mode}~~~~{$url_str}~~{$method}~~~~{$date_str}

EOD;
            }
        }

        if (empty($output)) {
            return;
        }

        $log_file_name = $this->CI->session->userdata('queryLogFile');
        $log_file_name = ($log_file_name != '') ? $log_file_name : md5($_SERVER['REQUEST_URI']);

        if ($this->CI->config->item('is_webservice') === true) {
            $log_folder = $this->CI->config->item('ws_query_log_path');
        } elseif ($this->CI->config->item('is_notification') === true) {
            $log_folder = $this->CI->config->item('ns_query_log_path');
        } elseif ($this->CI->config->item('is_citparseapi') === true) {
            $log_folder = $this->CI->config->item('parse_query_log_path');
        } elseif ($this->CI->config->item('is_admin') === true) {
            $log_folder = $this->CI->config->item('admin_query_log_path');
        } else {
            $log_folder = $this->CI->config->item('front_query_log_path');
        }

        if (!is_dir($log_folder)) {
            $this->CI->general->createFolder($log_folder);
        }
        $log_file_path = $log_folder . $log_file_name . ".html";

        $file_data = $output;
        if (is_file($log_file_path)) {
            $file_data .= file_get_contents($log_file_path);
        }
        $fp = fopen($log_file_path, 'w');
        fwrite($fp, $file_data);
        fclose($fp);
        return TRUE;
    }
}

/* End of file FinalSystemHook.php */
/* Location: ./application/hooks/FinalSystemHook.php */