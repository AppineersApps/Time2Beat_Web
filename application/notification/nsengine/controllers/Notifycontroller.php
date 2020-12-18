<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of NS Engine Controller
 *
 * @category notification
 *            
 * @package nsengine
 * 
 * @subpackage controllers
 *  
 * @module NSEngine
 * 
 * @class Notifycontroller.php
 * 
 * @path application\notification\nsengine\controllers\Notifycontroller.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class NotifyController extends Cit_Controller
{

    protected $_debug_loop = array();
    protected $_debug_curr = array();
    protected $_debug_called = FALSE;

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        if (!$this->input->get_post("ns_debug", true)) {
            $this->db->db_debug = FALSE;
        }
        $this->load->model('nsengine/notify_schedule_model');
        $this->load->model('nsengine/notify_values_model');
        $this->load->library('notifyresponse');
    }

    /**
     * listNSMethods method is used to get all notifications list.
     */
    public function listNSMethods()
    {
        if ($_ENV['debug_action']) {
            $this->config->load('cit_notifications', TRUE);
            $all_methods = $this->config->item('cit_notifications');
        }
        $all_methods = empty($all_methods) ? array() : $all_methods;
        $render_arr = array(
            'all_methods' => $all_methods,
            'ns_url' => $this->config->item('site_url') . "NS/"
        );
        $this->smarty->assign($render_arr);
    }

    /**
     * executeNotifySchedule method is used to execute notifications.
     */
    public function executeNotifySchedule()
    {
        $this->executeOperationSchedule();
        $this->executeTimeSchedule();
        $ns_debug = $this->input->get_post("ns_debug", true);
        if ($ns_debug == 1 && $_ENV['debug_action']) {
            $arr['queries'] = $this->general->getDBQueriesList();
            $ret = json_encode($arr);
            header('Content-Type: application/json; charset=utf-8');
            echo $ret;
        } else {
            echo 1;
        }
        $this->skip_template_view();
    }

    /**
     * notifyExecuter method is used to text or debug notifications.
     * @param string $func_name func_name is the webservice name.
     * @return array $responce_arr returns webservice response. (json format)
     */
    public function notifyExecuter($func_name = '')
    {
        header('Access-Control-Allow-Origin: *');
        $this->config->load('cit_notifications', TRUE);
        $all_methods = $this->config->item('cit_notifications');
        if (empty($all_methods[$func_name])) {
            show_error('Notification code not found. Please save settings or update code.', 400);
        }
        $db_event_data = $all_methods[$func_name];
        $params_arr = $output_arr = array();
        if ($db_event_data['type'] == "Operation") {
            $extra_cond_arr = array();
            $extra_cond_arr[] = array("field" => "mns.eNotifyType", "value" => 'Operation');
            $extra_cond_arr[] = array("field" => "mns.eStatus", "value" => 'Pending');
            $extra_cond_arr[] = array("field" => "mns.vNotifyName", "value" => $func_name);

            $fields = "mns.iNotifyScheduleId, mns.vNotifyName, mns.eOperation";
            $db_notify_operation = $this->notify_schedule_model->getData($extra_cond_arr, $fields);

            if (!is_array($db_notify_operation) || count($db_notify_operation) == 0) {
                show_error('This notification does not have any input data. Please ' . implode("/", $db_event_data['operations']) . ' record to table "' . $db_event_data['table'] . '"', 400);
            }

            $notify_schedule_id = $db_notify_operation[0]['iNotifyScheduleId'];

            $extra_cond_arr = array();
            $extra_cond_arr[] = array("field" => "mns.eNotifyType", "value" => 'Operation');
            $extra_cond_arr[] = array("field" => "mns.eStatus", "value" => 'Pending');
            $extra_cond_arr[] = array("field" => "mns.iNotifyScheduleId", "value" => $notify_schedule_id);
            $fields = "mns.iNotifyScheduleId, mnop.vFieldName, mnop.tOldValue, mnop.tNewValue";
            $db_notify_values = $this->notify_values_model->getData($extra_cond_arr, $fields);

            foreach ((array) $db_notify_values as $n_key => $n_val) {
                $old_key = "OLD_" . $n_val['vFieldName'];
                $new_key = "NEW_" . $n_val['vFieldName'];
                $temp_arr = array();
                $temp_arr[$old_key] = $n_val['tOldValue'];
                $temp_arr[$new_key] = $n_val['tNewValue'];
                $params_arr = array_merge($params_arr, $temp_arr);
            }
            $params_arr['OPERATION'] = $db_notify_operation[0]['eOperation'];

            $output_arr = $this->fireNotification($func_name, $params_arr, TRUE);

            $this->updateOperBasedResponse($notify_schedule_id, $output_arr);
        } elseif ($db_event_data['type'] == "Time") {
            $output_arr = $this->fireNotification($func_name, $params_arr, TRUE);
            $this->insertTimeBasedResponse($func_name, $output_arr, TRUE);
        }
        //print output response
        if ($this->_debug_called == TRUE) {
            $this->notifyresponse->sendNSResponse($output_arr, $this->notifyresponse->ns_debug_params);
        } else {
            $this->notifyresponse->sendNSResponse($output_arr);
        }
    }

    /**
     * executeOperationSchedule method is used to execute operation based notifications.
     */
    public function executeOperationSchedule()
    {
        $db_notify_assoc_values = $output_arr = $notify_arr = array();

        $extra_cond_arr = array();
        $extra_cond_arr[] = array("field" => "mns.eNotifyType", "value" => 'Operation');
        $extra_cond_arr[] = array("field" => "mns.eStatus", "value" => 'Pending');
        $fields = "mns.iNotifyScheduleId, mns.vNotifyName, mns.eOperation";
        $limit = $this->config->item('NOTIFICATIONS_CHUNK_SIZE');
        $db_notify_operation = $this->notify_schedule_model->getData($extra_cond_arr, $fields, "mns.iNotifyScheduleId ASC", "", $limit);

        if (!is_array($db_notify_operation) || count($db_notify_operation) == 0) {
            return $output_arr;
        }

        foreach ($db_notify_operation as $key => $val) {
            $notify_arr[] = $val['iNotifyScheduleId'];
            $res = $this->notify_schedule_model->update(array("eStatus" => "Inprocess"), $val['iNotifyScheduleId']);
        }

        $extra_cond_arr = array();
        $extra_cond_arr[] = array("field" => "mns.eNotifyType", "value" => 'Operation');
        $extra_cond_arr[] = array("field" => "mns.eStatus", "value" => 'Inprocess');
        $extra_cond_arr[] = array("field" => "mns.iNotifyScheduleId", "value" => $notify_arr, "oper" => "in");
        $fields = "mns.iNotifyScheduleId, mnop.vFieldName, mnop.tOldValue, mnop.tNewValue";
        $db_notify_values = $this->notify_values_model->getData($extra_cond_arr, $fields);

        foreach ((array) $db_notify_values as $n_key => $n_val) {
            $old_key = "OLD_" . $n_val['vFieldName'];
            $new_key = "NEW_" . $n_val['vFieldName'];
            $arr[$old_key] = $n_val['tOldValue'];
            $arr[$new_key] = $n_val['tNewValue'];
            if (is_array($db_notify_assoc_values[$n_val['iNotifyScheduleId']])) {
                $db_notify_assoc_values[$n_val['iNotifyScheduleId']] = array_merge($db_notify_assoc_values[$n_val['iNotifyScheduleId']], $arr);
            } else {
                $db_notify_assoc_values[$n_val['iNotifyScheduleId']] = $arr;
            }
        }
        foreach ((array) $db_notify_operation as $n_key => $n_val) {
            $db_notify_assoc_values[$n_val['iNotifyScheduleId']]['OPERATION'] = $n_val['eOperation'];
            $output_arr = $this->fireNotification($n_val['vNotifyName'], $db_notify_assoc_values[$n_val['iNotifyScheduleId']]);
            $res = $this->updateOperBasedResponse($n_val['iNotifyScheduleId'], $output_arr);
        }
    }

    /**
     * executeTimeSchedule method is used to execute time based notifications.
     */
    public function executeTimeSchedule()
    {
        $output_arr = array();

        $this->config->load('cit_notifications', TRUE);
        $all_methods = $this->config->item('cit_notifications');
        if (!is_array($all_methods) || count($all_methods) == 0) {
            return $output_arr;
        }
        foreach ($all_methods as $key => $val) {
            if ($val['type'] == "Time" && $val['status'] == "Active") {
                $db_time_data[$key] = $val;
            }
        }
        if (!is_array($db_time_data) || count($db_time_data) == 0) {
            return $output_arr;
        }

        require_once ($this->config->item('third_party') . 'cronjob/vendor/autoload.php');
        foreach ((array) $db_time_data as $n_key => $n_val) {
            try {
                $event_function = $n_key;
                $start_date_time = ($n_val['start_date'] != "") ? date("Y-m-d H:i:s", strtotime($n_val['start_date'])) : "";
                $end_date_time = ( $n_val['end_date'] != "") ? date("Y-m-d H:i:s", strtotime($n_val['end_date'])) : "";
                $curr_date_time = date("Y-m-d H:i:s");
                $cron_format = $n_val['cron_format'];
                if ($end_date_time != "" && $curr_date_time >= $end_date_time) {
                    continue;
                }
                if ($start_date_time != "" && ($curr_date_time < $start_date_time)) {
                    continue;
                }

                $extra_cond = array(
                    array("field" => "mns.vNotifyName", "value" => $event_function),
                    array("field" => "mns.eNotifyType", "value" => 'Time')
                );
//                $fields = array(
//                    array("field" => "MAX(" . $this->db->protect("mns.dtExeDateTime") . ") AS dtExeDateTime", "escape" => TRUE)
//                );
//                $db_notify_data = $this->notify_schedule_model->getData($extra_cond, $fields, "mns.dtExeDateTime ASC", "mns.vNotifyName");
                $fields = "dtExeDateTime";
                $db_notify_data = $this->notify_schedule_model->getData($extra_cond, $fields, "mns.iNotifyScheduleId DESC", "", 1);
                $exe_date_time = $db_notify_data[0]['dtExeDateTime'];
                if (empty($exe_date_time) || $exe_date_time == "0000-00-00 00:00:00") {
                    $exe_date_time = FALSE;
                } else {
                    $exe_date_time = date("Y-m-d H:i:s", strtotime($exe_date_time));
                }

                $default_time_zone = date_default_timezone_get();
                date_default_timezone_set("UTC");
                $cron = Cron\CronExpression::factory($cron_format);
                if ($exe_date_time != FALSE) {
                    $cron_date_time = $cron->getNextRunDate($exe_date_time)->format('Y-m-d H:i:s');
                    $first_run = false;
                } else {
                    $cron_date_time = $cron->getPreviousRunDate()->format('Y-m-d H:i:s');
                    $first_run = true;
                }
                date_default_timezone_set($default_time_zone);
                if ($cron_date_time <= $curr_date_time) {
                    $output_arr = $this->notifySubmit($event_function);
                }
            } catch (Exception $e) {
                $e->getMessage();
            }
        }
        return $output_arr;
    }

    /**
     * notifySubmit method is used to submit time based notifications.
     * @param string $event_name event_name is execute specific notifications.
     * @return array $output_arr returns output data records array.
     */
    public function notifySubmit($event_name = '')
    {
        $notify_schedule_id = $this->insertTimeBasedResponse($event_name);
        $output_arr = $this->fireNotification($event_name);
        if (is_array($output_arr) && array_key_exists("settings", $output_arr)) {
            $this->updateTimeBasedResponse($notify_schedule_id, $output_arr);
        }
        return $output_arr;
    }

    /**
     * insertTimeBasedResponse method is used to insert time based notification flow.
     * @param string $event_name event_name is insert entry for specific notifications.
     * @param array $output_arr output_arr is array of output of notification flow.
     * @return bool $res returns notification inserted id.
     */
    public function insertTimeBasedResponse($event_name = '', $output_arr = array(), $after_exec = FALSE)
    {
        $insert_arr['vNotifyName'] = $event_name;
        $insert_arr['eNotifyType'] = "Time";
        //$insert_arr['eOperation'] = "";
        if ($after_exec == TRUE) {
            $insert_arr['tOutputJSON'] = (is_array($output_arr)) ? json_encode($output_arr) : "";
            $insert_arr['vSuccess'] = ($output_arr['settings']['success']) ? $output_arr['settings']['success'] : 0;
            $insert_arr['tMessage'] = ($output_arr['settings']['message']) ? $output_arr['settings']['message'] : '';
        }
        $insert_arr['dtAddDateTime'] = date("Y-m-d H:i:s");
        $insert_arr['dtExeDateTime'] = date("Y-m-d H:i:s");
        if ($after_exec == TRUE) {
            if ($this->db->getErrorFound() === TRUE) {
                $insert_arr['eStatus'] = "DBError";
            } else {
                $insert_arr['eStatus'] = "Executed";
            }
        } else {
            $insert_arr['eStatus'] = "Inprocess";
        }
        $notify_schedule_id = $this->notify_schedule_model->insert($insert_arr);
        return $notify_schedule_id;
    }

    /**
     * updateTimeBasedResponse method is used to update operation based notification flow.
     * @param integer $notify_schedule_id notify_schedule_id is update specific notifications.
     * @param array $output_arr output_arr is array of output of notification flow.
     * @return bool $res returns TRUE or FALSE.
     */
    public function updateTimeBasedResponse($notify_schedule_id = '', $output_arr = array())
    {
        $update_arr = array();
        $update_arr['tOutputJSON'] = (is_array($output_arr)) ? json_encode($output_arr) : "";
        $update_arr['vSuccess'] = ($output_arr['settings']['success']) ? $output_arr['settings']['success'] : 0;
        $update_arr['tMessage'] = ($output_arr['settings']['message']) ? $output_arr['settings']['message'] : '';
        $update_arr['dtExeDateTime'] = date("Y-m-d H:i:s");
        if ($this->db->getErrorFound() === true) {
            $update_arr['eStatus'] = "DBError";
        } else {
            $update_arr['eStatus'] = "Executed";
        }
        $res = $this->notify_schedule_model->update($update_arr, $notify_schedule_id);
        return $res;
    }

    /**
     * updateOperBasedResponse method is used to update operation based notification flow.
     * @param integer $notify_schedule_id notify_schedule_id is update specific notifications.
     * @param array $output_arr output_arr is array of output of notification flow.
     * @return bool $res returns TRUE or FALSE.
     */
    public function updateOperBasedResponse($notify_schedule_id = '', $output_arr = array())
    {
        $update_arr = array();
        $update_arr['tOutputJSON'] = (is_array($output_arr)) ? json_encode($output_arr) : "";
        $update_arr['vSuccess'] = ($output_arr['settings']['success']) ? $output_arr['settings']['success'] : 0;
        $update_arr['tMessage'] = ($output_arr['settings']['message']) ? $output_arr['settings']['message'] : '';
        $update_arr['dtExeDateTime'] = date("Y-m-d H:i:s");
        if ($this->db->getErrorFound() === true) {
            $update_arr['eStatus'] = "DBError";
        } else {
            $update_arr['eStatus'] = "Executed";
        }
        $res = $this->notify_schedule_model->update($update_arr, $notify_schedule_id);
        return $res;
    }

    /**
     * fireNotification method is used to call notification flow.
     * @param string $event_name event_name is execute specific notifications.
     * @param array $params params is array of inputs to notification flow.
     * @return array $output_arr returns output data records array.
     */
    public function fireNotification($event_name = '', $params = array(), $debug = FALSE)
    {
        $this->config->load('cit_notifications', TRUE);
        $all_methods = $this->config->item('cit_notifications');
        $db_event_data = $all_methods[$event_name];
        $this->load->module($db_event_data['folder'] . "/" . $event_name);
        if ($debug == TRUE) {
            //checking for notify method
            if (empty($all_methods[$event_name])) {
                show_error('Notification code not found. Please save settings or update code.', 400);
            }
            //checking for notify controller
            if (!is_object($this->$event_name)) {
                show_error('Notification code not found. Please save settings or update code.', 400);
            }
            //setup for debugger
            if (!is_null($this->input->get_post("ns_debug")) && !is_null($this->input->get_post("ns_ctrls"))) {
                //initiate debugger
                $output_arr = $this->NotifyDebugger($event_name, $params);
                $this->_debug_called = TRUE;
            } else {
                //initiate notification
                $start_method = "start_" . $event_name;
                $output_arr = $this->$event_name->$start_method($params);
            }
        } else {
            if (empty($all_methods[$event_name])) {
                return array();
            }
            if (!is_object($this->$event_name)) {
                return array();
            }
            $start_method = "start_" . $event_name;
            $output_arr = $this->$event_name->$start_method($params);
        }
        return $output_arr;
    }

    /**
     * NotifyDebugger method is used to debug notification.
     */
    public function NotifyDebugger($func_name = '', $request_arr = array())
    {
        $this->notifyresponse->ns_log_file = $this->input->get_post("ns_log");
        $debug_cache_dir = $this->config->item('ns_debug_log_path');
        if (!is_dir($debug_cache_dir)) {
            $this->general->createFolder($debug_cache_dir);
        }
        $next_flow = $loop_name = '';
        if ($this->notifyresponse->ns_log_file && is_file($debug_cache_dir . $this->notifyresponse->ns_log_file)) {
            $_log_params = file_get_contents($debug_cache_dir . $this->notifyresponse->ns_log_file);
            $_log_params = unserialize($_log_params);
            if (is_array($_log_params) && count($_log_params) > 0) {
                $this->notifyresponse->ns_debug_params = $_log_params['debug'];
                $next_flow = $_log_params['next_flow'];
                $loop_name = $_log_params['loop_name'];
                $this->_debug_loop = is_array($_log_params['debug_loop']) ? $_log_params['debug_loop'] : array();
            }
            $input_params = $_log_params['params'];
        } else {
            $input_params = $request_arr;
        }
        $this->config->load('cit_nsdebugger', TRUE);
        $all_debugger = $this->config->item('cit_nsdebugger');
        if (empty($all_debugger[$func_name])) {
            show_error('Notification code not found. Please save settings or update code.', 400);
        }
        $curr_debuger = $all_debugger[$func_name];
        if ($next_flow == "") {
            $flow_keys = array_keys($curr_debuger);
            $next_flow = $flow_keys[0];
            $this->notifyresponse->ns_log_file = md5("debug_" . date("YmdHis") . "_" . rand(1000, 9999));
        }
        $this->_debug_curr = $curr_debuger;
        return $this->NotifyLogRunner($func_name, $input_params, $next_flow, $loop_name);
    }

    /**
     * NotifyLogRunner method is used to store debuging log.
     */
    public function NotifyLogRunner($func_name = '', $input_params = array(), $curr_flow = '', $loop_name = '')
    {
        $exec_debuger = $this->_debug_curr[$curr_flow];
        if (empty($exec_debuger)) {
            show_error('Notification debugger having some problem to detect next flow. Please try again.', 400);
        }
        $_SESSION['__ci_exec_api_flow'] = $curr_flow;
        if ($exec_debuger['type'] == "startloop") {
            $_lp_tmp_arr = $_lp_tmp_dic = $_lp_org_arr = $_lp_loc_arr = array();
            $_lp_nam = $exec_debuger['loop'][0];
            if ($_lp_nam != '' && array_key_exists($_lp_nam, $input_params)) {
                $this->_debug_loop[] = $_lp_nam;
                $_lp_org_arr = $input_params[$_lp_nam];
                $_lp_loc_arr = &$input_params[$_lp_nam];
            } else {
                $this->_debug_loop[] = $curr_flow;
            }
            if ($exec_debuger['loop'][1] == "custom") {
                $_cus_ini = $exec_debuger['loop'][2];
                if (is_array($input_params[$_cus_ini])) {
                    $_lp_ini = count($_cus_ini);
                } else {
                    $_lp_ini = intval($_cus_ini);
                }
                $_cus_end = $exec_debuger['loop'][3];
                if (is_array($input_params[$_cus_end])) {
                    $_lp_end = count($_cus_end);
                } else {
                    $_lp_end = intval($_cus_end);
                }
                $_lp_stp = $exec_debuger['loop'][4];
                $_lp_opr = $exec_debuger['loop'][5];
            } else {
                $_lp_ini = 0;
                if ($exec_debuger['loop'][1] == "number") {
                    $_lp_end = intval($exec_debuger['loop'][2]);
                } else {
                    $_lp_end = count($input_params[$exec_debuger['loop'][0]]);
                }
                $_lp_stp = 1;
                $_lp_opr = 'lt';
            }
            $_block_result = array("start_point" => $_lp_ini, "end_point" => $_lp_end, "step" => $_lp_stp, "loop" => $_lp_nam);
            $this->notifyresponse->pushDebugParams($curr_flow, $_block_result, $input_params, $exec_debuger['next'], $loop_name, "", $this->_debug_loop);
            $_lp_tmp = (is_array($_lp_org_arr[0])) ? TRUE : FALSE;
            $_lp_cnd = $this->checkCondition($_lp_opr, $_lp_ini, $_lp_end);
            while ($_lp_cnd) {
                $_lp_inp = $input_params;
                unset($_lp_inp[$loop_name]);
                if ($_lp_tmp) {
                    if (is_array($_lp_org_arr[$_lp_ini])) {
                        $_lp_inp = $_lp_org_arr[$_lp_ini] + $input_params;
                    }
                } elseif ($_lp_nam != '') {
                    $_lp_inp[$_lp_nam] = $_lp_org_arr[$_lp_ini];
                    $_lp_org_arr[$i] = array();
                    $_lp_org_arr[$i][$_lp_nam] = $_lp_inp[$_lp_nam];
                }
                $_lp_inp['i'] = $_lp_ini;
                $_lp_inp['__dictionaries'] = $_lp_tmp_dic;
                $response = $this->NotifyDebugger($func_name, $_lp_inp, $exec_debuger['next'], $loop_name);
                if (is_array($response['__dictionaries'])) {
                    $_lp_tmp_dic = $response['__dictionaries'];
                    unset($response['__dictionaries']);
                }
                if (is_array($response['__variables'])) {
                    $input_params = $this->notifyresponse->grabLoopVariables($response['__variables'], $input_params);
                    unset($response['__variables']);
                }
                if ($_lp_tmp) {
                    $_lp_loc_arr[$_lp_ini] = $this->notifyresponse->filterLoopParams($response, $_lp_org_arr[$_lp_ini], $_lp_inp);
                } else {
                    $_lp_tmp_arr[$_lp_ini] = $this->notifyresponse->filterLoopParams($response, $_lp_org_arr[$_lp_ini], $_lp_inp);
                }
                if (isset($this->$func_name->break_continue)) {
                    if ($this->$func_name->break_continue === 1) {
                        $this->$func_name->break_continue = NULL;
                        break;
                    } elseif ($this->$func_name->break_continue === 2) {
                        $this->$func_name->break_continue = NULL;
                        $_lp_ini = $_lp_ini + ($_lp_stp);
                        $_lp_cnd = $this->checkCondition($_lp_opr, $_lp_ini, $_lp_end);
                        continue;
                    }
                }
                $_lp_ini = $_lp_ini + ($_lp_stp);
                $_lp_cnd = $this->checkCondition($_lp_opr, $_lp_ini, $_lp_end);
            }
            if ($_lp_nam != '') {
                $_lp_key = array_search($_lp_nam, $this->_debug_loop);
            } else {
                $_lp_key = array_search($curr_flow, $this->_debug_loop);
            }
            unset($this->_debug_loop[$_lp_key]);
            $this->_debug_loop = array_values($this->_debug_loop);
            if ($_lp_nam == '') {
                $input_params[$curr_flow] = $_lp_tmp_arr;
            } elseif (!is_array($_lp_org_arr[0])) {
                $input_params[$_lp_nam] = $_lp_tmp_arr;
            }
            if (is_array($_lp_tmp_dic)) {
                $input_params = array_merge($input_params, $_lp_tmp_dic);
            }
            $exec_debuger = $this->_debug_curr[$exec_debuger['end']];
        } elseif ($exec_debuger['type'] == "endloop") {
            $this->notifyresponse->pushDebugParams($curr_flow, array(), $input_params, $exec_debuger['next'], $loop_name, "", $this->_debug_loop);
            return $input_params;
        } elseif (method_exists($this->$func_name, $curr_flow)) {
            $output_arr = $this->$func_name->$curr_flow($input_params);
            if (in_array($exec_debuger['type'], array("condition", "break", "continue"))) {
                $_block_result = $output_arr;
                if (in_array($exec_debuger['type'], array("break", "continue"))) {
                    if (isset($this->$func_name->break_continue)) {
                        if ($this->$func_name->break_continue === 1 || $this->$func_name->break_continue === 2) {
                            $this->wsresponse->pushDebugParams($curr_flow, $_block_result, $input_params, $exec_debuger['next'], $loop_name, "", $this->_debug_loop);
                            return $input_params;
                        }
                    }
                }
            } else {
                if (in_array($exec_debuger['type'], array("query", "notifyemail", "pushnotify", "sms"))) {
                    $_block_result = $this->$func_name->block_result;
                } else {
                    $_block_result = $output_arr;
                }
                $input_params = $output_arr;
            }
        } else {
            show_error('Notification debugger having some problem to detect next flow. Please try again.', 400);
        }
        if ($exec_debuger['type'] == "finish") {
            $this->notifyresponse->pushDebugParams($curr_flow, $_block_result, $input_params);
            return $input_params;
        } elseif ($exec_debuger['type'] == "condition") {
            if ($output_arr['success']) {
                $next_flow = $exec_debuger['next'][1];
            } else {
                $next_flow = $exec_debuger['next'][0];
            }
        } else {
            $next_flow = $exec_debuger['next'];
        }
        $this->notifyresponse->pushDebugParams($curr_flow, $_block_result, $input_params, $next_flow, $loop_name, "", $this->_debug_loop);
        return $this->NotifyLogRunner($func_name, $input_params, $next_flow, $loop_name);
    }

    /**
     * checkCondition method is used to check the debugger conditions.
     * @param string $operator operator is to perform arithmetic operations.
     * @param string $operand_1 operand_1 is left portion of arithmetic operations.
     * @param string $operand_2 operand_2 is right portion of arithmetic operations.
     * @return boolean $flag returns TRUE or FALSE.
     */
    public function checkCondition($operator = '', $operand_1 = '', $operand_2 = '')
    {
        $operator = (in_array($operator, array("lt", "le", "gt", "ge"))) ? $operator : "lt";
        $flag = $this->general->compareDataValues($operator, $operand_1, $operand_2);
        return $flag;
    }
}
