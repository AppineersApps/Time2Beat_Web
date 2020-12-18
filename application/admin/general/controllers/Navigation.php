<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Navigation Log Controller
 *
 * @category admin
 *            
 * @package general
 * 
 * @subpackage controllers
 * 
 * @module NavigationLog
 * 
 * @class Navigation.php
 * 
 * @path application\admin\general\controllers\Navigation.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Navigation extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('filter');
        $this->load->model('navigation_model');
        $this->module_name = "navigation";
        $this->module_hurl = $this->config->item('admin_url') . "#general/navigation/";
        $this->module_nurl = $this->config->item('admin_url') . "general/navigation/";
        $this->_request_params();
    }

    /**
     * _request_params method is used to set post/get/request params.
     */
    private function _request_params()
    {
        $this->get_arr = is_array($this->input->get(NULL, TRUE)) ? $this->input->get(NULL, TRUE) : array();
        $this->post_arr = is_array($this->input->post(NULL, TRUE)) ? $this->input->post(NULL, TRUE) : array();
        $this->params_arr = array_merge($this->get_arr, $this->post_arr);
        return $this->params_arr;
    }

    /**
     * index method is used to intialize navigation log listing page.
     */
    public function index()
    {
        $range = $this->params_arr['range'];
        $action = $this->params_arr['action'];
        $user_id = $this->params_arr['user_id'];
        $data_log_arr['today'] = "Today";
        $data_log_arr['yesterday'] = "Yesterday";
        $data_log_arr['last7days'] = "Last 7 Days";
        $data_log_arr['last30days'] = "Last 30 Days";
        $data_log_arr['thismonth'] = "This Month";
        $data_log_arr['lastmonth'] = "Last Month";
        $data_log_arr['last3months'] = "Last 3 Months";
        $data_log_arr['all'] = "All";
        $data_flush_arr['onehour'] = "the past hour";
        $data_flush_arr['today'] = "the today";
        $data_flush_arr['yesterday'] = "the past day";
        $data_flush_arr['last7days'] = "the past week";
        $data_flush_arr['last30days'] = "the last 30 days";
        $data_flush_arr['thismonth'] = "the present month";
        $data_flush_arr['lastmonth'] = "the last month";
        $data_flush_arr['last3months'] = "the last 3 months";
        $data_flush_arr['all'] = "the beginning of time";

        $time_stamp_pro = $this->db->protect("dTimeStamp");
        switch ($range) {
            case 'yesterday':
                $time_stamp = date("Y-m-d", strtotime('-1 day'));
                $extra_cond = " AND " . $this->db->date_format($time_stamp_pro) . " = " . $this->db->escape($time_stamp);
                break;
            case 'last7days':
                $start_date = date("Y-m-d", strtotime('-7 day'));
                $end_date = date("Y-m-d");
                $extra_cond = " AND " . $this->db->date_format($time_stamp_pro) . " BETWEEN " . $this->db->escape($start_date) . " AND " . $this->db->escape($end_date);
                break;
            case 'last30days':
                $start_date = date("Y-m-d", strtotime('-30 day'));
                $end_date = date("Y-m-d");
                $extra_cond = " AND " . $this->db->date_format($time_stamp_pro) . " BETWEEN " . $this->db->escape($start_date) . " AND " . $this->db->escape($end_date);
                break;
            case 'thismonth':
                $start_date = date("Y-m-01", mktime(0, 0, 0, date('m'), date('d'), date('Y')));
                $end_date = date("Y-m-d");
                $extra_cond = " AND " . $this->db->date_format($time_stamp_pro) . " BETWEEN " . $this->db->escape($start_date) . " AND " . $this->db->escape($end_date);
                break;
            case 'lastmonth':
                $start_date = date("Y-m-01", mktime(0, 0, 0, date('m') - 1, date('d'), date('Y')));
                $end_date = date("Y-m-d", mktime(0, 0, 0, date('m'), 0, date('Y')));
                $extra_cond = " AND " . $this->db->date_format($time_stamp_pro) . " BETWEEN " . $this->db->escape($start_date) . " AND " . $this->db->escape($end_date);
                break;
            case 'last3months':
                $start_date = date("Y-m-d", strtotime('-3 month'));
                $end_date = date("Y-m-d");
                $extra_cond = " AND " . $this->db->date_format($time_stamp_pro) . " BETWEEN " . $this->db->escape($start_date) . " AND " . $this->db->escape($end_date);
                break;
            case 'all':
                break;
            default :
                $time_stamp = date("Y-m-d");
                $extra_cond = " AND " . $this->db->date_format($time_stamp_pro) . " = " . $this->db->escape($time_stamp);
                break;
        }

        if ($this->session->userdata('vUserName') != $this->config->item("ADMIN_USER_NAME")) {
            $admin_user = "No";
        } else {
            $admin_user = "Yes";
            $user_data = $this->navigation_model->getUserData();
        }

        $db_action_arr = array(
            "Viewed" => "Viewed",
            "Added" => "Added",
            "Modified" => "Modified",
            "Deleted" => "Deleted",
        );

        if (in_array($action, array_keys($db_action_arr))) {
            $extra_cond .= " AND " . $this->db->protect("eNavigAction") . " = " . $this->db->escape($action);
        }

        $user_id = ($user_id > 0) ? $user_id : $this->session->userdata('iAdminId');
        $extra_condition = $this->db->protect("iAdminId") . " = " . $this->db->escape($user_id) . " " . $extra_cond;
        $db_navig_data = $this->navigation_model->getData($extra_condition, '', '', '', 1000);

        $render_arr = array(
            'db_navig_data' => $db_navig_data,
            'data_log_arr' => $data_log_arr,
            'data_flush_arr' => $data_flush_arr,
            'data_action_arr' => $db_action_arr,
            'action' => $action,
            'admin_user' => $admin_user,
            'user_data' => $user_data,
            'user_id' => $user_id
        );
        $this->smarty->assign($render_arr);
        $this->loadView("navigation_index");
    }

    /**
     * flush_record method is used to flush navigation log records.
     */
    public function flush_record()
    {
        $flush = $this->params_arr['flush'];
        $user_id = $this->params_arr['user_id'];
        $time_stamp_pro = $this->db->protect("dTimeStamp");
        if ($this->session->userdata('iAdminId') > 0) {
            switch ($flush) {
                case 'onehour' :
                    $time_stamp_flush = date("Y-m-d H:i:s", strtotime('-1 hours'));
                    $curr_date = date("Y-m-d H:i:s");
                    $extra_cond_flush = " AND dTimeStamp < " . $this->db->escape($curr_date) . " AND dTimeStamp > " . $this->db->escape($time_stamp_flush);
                    break;
                case 'yesterday':
                    $time_stamp_flush = date("Y-m-d", strtotime('-1 day'));
                    $extra_cond_flush = " AND " . $this->db->date_format($time_stamp_pro) . " = " . $this->db->escape($time_stamp_flush);
                    break;
                case 'last7days':
                    $start_date_flush = date("Y-m-d", strtotime('-7 day'));
                    $end_date_flush = date("Y-m-d");
                    $extra_cond_flush = " AND " . $this->db->date_format($time_stamp_pro) . " BETWEEN " . $this->db->escape($start_date_flush) . " AND " . $this->db->escape($end_date_flush);
                    break;
                case 'last30days':
                    $start_date_flush = date("Y-m-d", strtotime('-30 day'));
                    $end_date_flush = date("Y-m-d");
                    $extra_cond_flush = " AND " . $this->db->date_format($time_stamp_pro) . " BETWEEN " . $this->db->escape($start_date_flush) . " AND " . $this->db->escape($end_date_flush);
                    break;
                case 'thismonth':
                    $start_date_flush = date("Y-m-01", mktime(0, 0, 0, date('m'), date('d'), date('Y')));
                    $end_date_flush = date("Y-m-d");
                    $extra_cond_flush = " AND " . $this->db->date_format($time_stamp_pro) . " BETWEEN " . $this->db->escape($start_date_flush) . " AND " . $this->db->escape($end_date_flush);
                    break;
                case 'lastmonth':
                    $start_date_flush = date("Y-m-01", mktime(0, 0, 0, date('m') - 1, date('d'), date('Y')));
                    $end_date_flush = date("Y-m-d", mktime(0, 0, 0, date('m'), 0, date('Y')));
                    $extra_cond_flush = " AND " . $this->db->date_format($time_stamp_pro) . " BETWEEN " . $this->db->escape($start_date_flush) . " AND " . $this->db->escape($end_date_flush);
                    break;
                case 'last3months':
                    $start_date_flush = date("Y-m-d", strtotime('-3 month'));
                    $end_date_flush = date("Y-m-d");
                    $extra_cond_flush = " AND " . $this->db->date_format($time_stamp_pro) . " BETWEEN " . $this->db->escape($start_date_flush) . " AND " . $this->db->escape($end_date_flush);
                    break;
                case 'all':
                    break;
                default:
                    break;
            }
            $user_id = ($user_id > 0) ? $user_id : $this->session->userdata('iAdminId');
            $extra_cond = $this->db->protect("iAdminId") . " = " . $this->db->escape($user_id) . " " . $extra_cond_flush;
            $this->navigation_model->delete($extra_cond, 1000);
        }
        $this->index();
    }

    /**
     * error_log method is used to display query log records.
     */
    public function error_log()
    {
        echo __LINE__;exit;
        $page = $this->params_arr['page'];
        $log_file = $this->config->item('query_error_path') . $page . ".html";
        //echo __LINE__; exit; 
        if (is_file($log_file)) {
            $file_found = true;
        } else {
            $file_found = false;
        }
        $render_arr = array("error_log_file" => $log_file, "file_found" => $file_found);
        $this->smarty->assign($render_arr);
        $this->loadView("error_index");
    }

    /**
     * query_log method is used to display query log records.
     */
    public function query_log()
    {
        $query_log_file = $this->config->item('admin_query_log_path') . $this->session->userdata('queryLogFile') . ".html";
        $query_log_data = array();
        $file_found = false;
        if (is_file($query_log_file)) {
            $file_found = true;
            $handle = fopen($query_log_file, "r");
            while (($line = fgets($handle)) !== false) {
                $line_arr = explode("~~~~", $line);
                $tmp_data['query'] = $line_arr[0];
                $tmp_data['time'] = $line_arr[1];
                $tmp_data['ip'] = $line_arr[2];
                $tmp_data['mode'] = $line_arr[3];
                $query_log_data[] = $tmp_data;
            }
        }
        $count_log_files = $this->general->getQueryLogFiles();
        $render_arr = array(
            "log_files" => $count_log_files,
            "file_found" => $file_found,
            "query_log_data" => $query_log_data
        );
        $this->smarty->assign($render_arr);
        $this->loadView("query_index");
    }

    /**
     * query_log_page method is used to display query log different page records.
     */
    public function query_log_page()
    {
        $paging = $this->params_arr['type'];
        $page = $this->params_arr['page'];
        $admin_log_path = $this->config->item('admin_query_log_path');
        $count_log_files = $this->general->getQueryLogFiles();
        $log_curr_file = $count_log_files[$page - 1];
        $query_log_file = $admin_log_path . $log_curr_file;
        $file_found = false;
        $query_log_data = array();
        if (is_file($query_log_file)) {
            $file_found = true;
            $handle = fopen($query_log_file, "r");
            while (($line = fgets($handle)) !== false) {
                $line_arr = explode("~~~~", $line);
                $tmp_data['query'] = $line_arr[0];
                $tmp_data['time'] = $line_arr[1];
                $tmp_data['ip'] = $line_arr[2];
                $query_log_data[] = $tmp_data;
            }
        }
        $render_arr = array(
            "page" => $page,
            "paging" => $paging,
            "log_files" => $count_log_files,
            "file_found" => $file_found,
            "query_log_data" => $query_log_data
        );
        $this->smarty->assign($render_arr);
        $this->loadView("query_index");
    }

    /**
     * clear_query_log method is used to clear query log pages.
     */
    public function clear_query_log()
    {
        $flush_type = $this->params_arr['flush_type'];
        $flush_page = (intval($this->params_arr['flush_page'])) ? $this->params_arr['flush_page'] : 1;
        $admin_log_path = $this->config->item('admin_query_log_path');
        if ($this->session->userdata('iAdminId') > 0) {
            $total_files = $this->general->getQueryLogFiles();
            if ($flush_type == "All") {
                for ($i = 0; $i < count($total_files); $i++) {
                    $log_file_path = $admin_log_path . DS . $total_files[$i];
                    if (is_file($log_file_path)) {
                        $result = unlink($log_file_path);
                    }
                }
            } elseif ($flush_type == "First") {
                for ($i = 0, $j = 1; $i < count($total_files); $i++, $j++) {
                    if ($flush_page < $j) {
                        break;
                    }
                    $log_file_path = $admin_log_path . DS . $total_files[$i];
                    if (is_file($log_file_path)) {
                        $result = unlink($log_file_path);
                    }
                }
            } elseif ($flush_type == "Last") {
                for ($i = count($total_files) - 1, $j = 1; $i > 0; $i--, $j++) {
                    if ($flush_page < $j) {
                        break;
                    }
                    $log_file_path = $admin_log_path . DS . $total_files[$i];
                    if (is_file($log_file_path)) {
                        $result = unlink($log_file_path);
                    }
                }
            }
        } else {
            $result = 0;
        }
        $message = ($result) ? $this->lang->line('GENERIC_QUERY_LOG_DELETED_SUCCESSFULLY') : $this->lang->line('GENERIC_ERROR_IN_DELETING_QUERY_LOG');
        $ret_arr['success'] = ($result) ? 1 : 0;
        $ret_arr['message'] = $message;
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    /**
     * clear_query_cache method is used to clear query caching pages.
     */
    public function clear_query_cache()
    {
        if ($this->session->userdata('iAdminId') > 0) {
            $query_cache_path = $this->config->item('query_cache_path');
            if (is_dir($query_cache_path) && $this->db->_cache_init()) {
                $this->db->CACHE->delete_all();
                $result = 1;
            } else {
                $result = 0;
            }
            if ($this->config->item("ADMIN_ASSETS_APPCACHE") == 'Y') {
                $ci_target_appcache = $this->config->item("site_path") . $this->config->item('ADMIN_APPCACHE_FILE');
                $ci_target_contents = file($ci_target_appcache);
                if (stristr($ci_target_contents, "CACHE MANIFEST") !== FALSE) {
                    $curr_date = date("Y-m-d h:i:A");
                    $ci_target_contents[1] = '# version ' . $curr_date . ' v' . $this->config->item("PROJECT_LATEST_VERSION") . '
';
                    file_put_contents($ci_target_appcache, implode($ci_target_contents));

                    $ci_source_appcache = $this->config->item("admin_appcache_src_path") . $this->config->item('ADMIN_THEME_DISPLAY') . DS . $this->config->item('ADMIN_APPCACHE_FILE');
                    $ci_source_contents = file($ci_source_appcache);
                    $ci_source_contents[1] = '# version ' . $curr_date . ' v' . $this->config->item("PROJECT_LATEST_VERSION") . '
';
                    file_put_contents($ci_source_appcache, implode($ci_source_contents));
                }
                $result = 1;
            }
            if ($this->config->item('GRID_SEARCH_PREFERENCES') == "Y") {
                $result = 1;
            }
        } else {
            $result = 0;
        }

        $message = ($result) ? $this->lang->line('GENERIC_CACHE_CLEARED_SUCCESSFULLY') : $this->lang->line('GENERIC_ERROR_IN_CLEARING_CACHE');
        $ret_arr['success'] = $result;
        $ret_arr['message'] = $message;
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    public function change_preferences()
    {
        try {
            if ($this->session->userdata('vUserName') != $this->config->item("ADMIN_USER_NAME")) {
                throw new Exception($this->lang->line('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
            }
            $old_theme = $this->config->item("ADMIN_THEME_SETTINGS");
            
            $update_value = FALSE;
            if ($this->params_arr['type'] == "menu") {
                $update_field = 'NAVIGATION_BAR';
                $update_value = ($this->params_arr['value'] == "Left") ? 'Left' : 'Top';
            } else {
                $update_field = 'ADMIN_THEME_SETTINGS';
            }

            $this->load->model('general/systemsettings');
            $extra_cond = $this->db->protect("vName") . " = " . $this->db->escape($update_field);
            $db_settings_data = $this->systemsettings->getSettingsMaster('vValue', $extra_cond);

            if (!is_array($db_settings_data) || count($db_settings_data) == 0) {
                throw new Exception("System does not have these kind of settings");
            }

            if ($update_field == "NAVIGATION_BAR" && $db_settings_data[0]['vValue'] == $update_value) {
                throw new Exception("Please change menu to view changes");
            }

            if ($update_field == "ADMIN_THEME_SETTINGS") {
                $update_value = $this->fetch_preference($db_settings_data);
            }

            if ($update_value === FALSE) {
                throw new Exception($this->lang->line('GENERIC_FAILURE_IN_CHANGING_PREFERENCES'));
            }

            $update_arr = array('vValue' => $update_value);
            $update_cond = $this->db->protect("vName") . " = " . $this->db->escape($update_field);
            $res = $this->systemsettings->updateSetting($update_arr, $update_cond);
            
            if ($update_field == "ADMIN_THEME_SETTINGS") {
                $loader_src = $this->config->item("images_path") . "admin/loader-selected.svg";
                if(is_file($loader_src)){
                    $src_content = file_get_contents($loader_src);
                    $this->load->library("ci_theme");
                    $theme_arr = $this->ci_theme->themeDefaultColors();
                    $old_theme = explode("@", $old_theme);
                    $new_theme = explode("@", $update_value);
                    $old_theme_color = $old_theme[1];
                    $new_theme_color = $new_theme[1];
                    foreach($theme_arr['cit'] as $val){
                        if($val['file'] == $old_theme_color){
                            $old_color_code = $val['color'];
                        }
                        if($val['file'] == $new_theme_color){
                            $new_color_code = $val['color'];
                        }
                    }
                    if(!empty($old_color_code) && !empty($new_color_code)){
                       $new_src_content = str_replace($old_color_code, $new_color_code, $src_content);
                        file_put_contents($loader_src, $new_src_content);
                    }
                }
            }
            
            $res = 1;
            $msg = $this->lang->line('GENERIC_PREFERENCES_CHANGED_SUCCESSFULLY');
        } catch (Exception $e) {
            $res = 0;
            $msg = $e->getMessage();
        }

        $ret_arr['success'] = $res;
        $ret_arr['message'] = $msg;
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    protected function fetch_preference($db_settings_data = array())
    {
        $theme_arr = explode("@", $db_settings_data[0]['vValue']);
        $patte_arr = explode("||", $theme_arr[1]);
        $theme_val = $theme_arr[0];
        if ($this->params_arr['type'] == "theme") {
            $update_value = in_array($this->params_arr['value'], array("metronic", "cit")) ? $this->params_arr['value'] : 'supr';
            if ($update_value == $theme_val) {
                $update_value = FALSE;
            }
        } elseif (in_array($this->params_arr['type'], array("header", "sidebar", "body")) && $theme_val == "supr") {
            if ($this->params_arr['type'] == "header") {
                $update_value = $theme_val . "@" . $this->params_arr['value'] . "||" . $patte_arr[1] . "||" . $patte_arr[2] . "@" . $theme_arr[2];
            } elseif ($this->params_arr['type'] == "sidebar") {
                $update_value = $theme_val . "@" . $patte_arr[0] . "||" . $this->params_arr['value'] . "||" . $patte_arr[2] . "@" . $theme_arr[2];
            } elseif ($this->params_arr['type'] == "body") {
                $update_value = $theme_val . "@" . $patte_arr[0] . "||" . $patte_arr[1] . "||" . $this->params_arr['value'] . "@" . $theme_arr[2];
            } else {
                $update_value = FALSE;
            }
        } elseif ($this->params_arr['type'] == "color" && in_array($theme_val, array("metronic", "cit"))) {
            $update_value = $theme_val . "@" . $this->params_arr['value'] . "@" . $theme_arr[2];
        } elseif ($this->params_arr['type'] == "custom") {
            $update_value = $theme_val . "@" . $theme_arr[1] . "@" . $this->params_arr['value'];
        } else {
            $update_value = FALSE;
        }
        return $update_value;
    }
}
