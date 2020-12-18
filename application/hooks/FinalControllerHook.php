<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Final Controller Hook
 *
 * @author Simhachalam G
 */
class FinalControllerHook
{

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function final_controller_actions()
    {
        if (isset($_ENV['debug_action']) && $_ENV['debug_action'] == 1) {
            if (is_object($this->CI->db) && $this->CI->db->getErrorFound() === TRUE) {
                $efile = $this->db_errors();
                $this->CI->output->set_header("Cit-db-error: 1");
                $this->CI->output->set_header("Cit-db-efile: " . $efile);
            }
        }
    }

    public function db_errors()
    {
        $queries = $this->CI->db->getErrorMessages();
        $output = '';
        $ip = $this->CI->general->getHTTPRealIPAddr();
        if (count($queries) == 0) {
            $output .= "no db errors\n";
        } else {
            for ($i = count($queries) - 1; $i >= 0; $i--) {
                $query = "<div>" . implode("</div><div>", $queries[$i]) . "</div>";
                $output .= <<<EOD
                        
    <tr class="query-error">
        <td style="display:table-cell">
            {$query}
        </td>
        <td>{$remote_addr}</td>
    </tr>
EOD;
            }
        }

        $log_file_name = md5(time());
        $log_folder = $this->CI->config->item('query_error_path');
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

        return $log_file_name;
    }
}

/* End of file FinalControllerHook.php */
/* Location: ./application/hooks/FinalControllerHook.php */