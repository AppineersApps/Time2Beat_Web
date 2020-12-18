<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of PushNotify Controller
 *
 * @category admin
 *            
 * @package tools
 * 
 * @subpackage controllers
 * 
 * @module PushNotify
 * 
 * @class Pushnotify.php
 * 
 * @path application\admin\tools\controllers\Pushnotify.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class PushNotify extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->folder_name = "tools";
        $this->module_name = "pushnotify";
        $this->mod_url_cod = array(
            "pushnotify_action",
            "pushnotify_variables",
            "pushnotify_module_fields",
            "pushnotify_select_fields"
        );
        $this->get_arr = is_array($this->input->get(NULL, TRUE)) ? $this->input->get(NULL, TRUE) : array();
        $this->post_arr = is_array($this->input->post(NULL, TRUE)) ? $this->input->post(NULL, TRUE) : array();
        $this->params_arr = array_merge($this->get_arr, $this->post_arr);
        $this->mod_enc_url = $this->general->getCustomEncryptURL($this->mod_url_cod, TRUE);
        $this->load->model('pushnotify_model');
        $this->load->library('filter');
        $this->load->library('ci_misc');
    }

    /**
     * index method is used to display bulk mail form to send emails.
     */
    public function index()
    {
        try {
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                $view_access = $this->filter->checkAccessCapability("push_notification", TRUE);
            } else {
                $view_access = $this->filter->getModuleWiseAccess("PushNotify", "Add", FALSE, TRUE);
            }
            if (!$view_access) {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }
            
            $db_module_data = $this->ci_misc->getPushNotifyModules();
            $db_notify_code = array(
                "USER" => "USER",
                "ALERT" => "ALERT"
            );
            if (is_array($db_module_data) && ($db_module_data) > 0) {
                $db_module['Module'] = $db_module_data;
            }
            $sound_arr = $this->config->item('PUSH_NOTIFY_SOUND_ARR');
            $field_arr = $this->ci_misc->getPushNotifyModuleListFields();
            $render_arr = array(
                'db_module' => $db_module,
                'db_notify_code' => $db_notify_code,
                'sound_arr' => $sound_arr,
                'mod_enc_url' => $this->mod_enc_url,
                'field_arr' => $field_arr
            );
            $this->smarty->assign($render_arr);
            $this->loadView('pushnotify');
        } catch (Exception $e) {
            $render_arr['err_message'] = $e->getMessage();
            $this->smarty->assign($render_arr);
            $this->loadView($this->config->item('ADMIN_FORBIDDEN_TEMPLATE'));
        }
    }

    /**
     * pushnotify_variables method is used to add additional variables for notification.
     */
    public function pushnotify_variables()
    {
        $params_arr = $this->params_arr;
        $row_id = $params_arr['row_id'];
        $dis_no = $params_arr['dis_no'];
        $device_id = $params_arr['device_id'];
        $send_details = explode('@@', $device_id);
        $type = $send_details[0];
        $value = $send_details[1];
        $field_arr = $this->ci_misc->getPushNotifyModuleListFields($value);
        $render_arr = array(
            "row_id" => $row_id,
            'dis_no' => $dis_no,
            'field_arr' => $field_arr,
            'select_fields' => 'No',
        );
        $this->smarty->assign($render_arr);
        $this->loadView("ajax_pushnotify_variables");
    }

    /**
     * pushnotify_module_fields method is used to render module dropdown data.
     */
    public function pushnotify_module_fields()
    {
        $sent_id = $this->input->get_post('sent_id');
        if (trim($sent_id) == 'Other') {
            $sent_type = "Other";
        } else {
            $sent_idarr = explode("@@", $sent_id);
            $sent_type = "Modules";
            $id = $sent_idarr[1];
            $params_arr['module_name'] = $id;
            $email_arr = $this->ci_misc->getPushNotifyModuleFields($params_arr);
        }
        $render_arr = array(
            'sent_id' => $sent_id,
            'sent_type' => $sent_type,
            'email_arr' => $email_arr
        );
        $this->smarty->assign($render_arr);
        $this->loadView('ajax_push_notify_modules');
    }

    /**
     * pushnotify_select_fields method is used to render each module related fields.
     */
    public function pushnotify_select_fields()
    {
        $params_arr = $this->params_arr;
        $device_id = $params_arr['device_id'];
        $send_details = explode('@@', $device_id);
        $type = $send_details[0];
        $value = $send_details[1];
        $field_arr = $this->ci_misc->getPushNotifyModuleListFields($value);
        $render_arr = array(
            'select_fields' => 'Yes',
            'field_arr' => $field_arr
        );
        $this->smarty->assign($render_arr);
        $this->loadView("ajax_pushnotify_variables");
    }

    /**
     * pushnotify_action method is used to send push notifications to the specfied receivers.
     */
    public function pushnotify_action()
    {
        try {
            $this->load->model('tools/push');

            $send_to = $this->input->post('vSendTo');
            $device_ids = $this->input->post('iDeviceId');
            $device_field_name = trim($this->input->post('vFieldName'));
            $code = $this->input->post('vCode');
            $sound = $this->input->post('vSound');
            $badge = $this->input->post('vBadge');
            $title = $this->input->post('vButtonTitle');
            $message = $this->input->post('vMessage');

            $push_notify_variable_arr = $this->input->post('push_notify_variable');
            $push_notify_value_arr = $this->input->post('push_notify_value');
            $push_notify_other_arr = $this->input->post('push_notify_value_other');
            $push_notify_comp_arr = $this->input->post('push_notify_value_compulsory');

            $device_var_arr = array();
            if ($send_to == 'Other') {
                $device_ids_arr = explode(',', $device_ids);
            } else {
                $send_details = explode('@@', $send_to);
                $data_arr = $this->ci_misc->getPushNotifyModuleData($send_details[1]);
                if (is_array($data_arr) && count($data_arr) > 0) {
                    for ($i = 0; $i < count($data_arr); $i++) {
                        $device_token = $data_arr[$i][$device_field_name];
                        $device_ids_arr[] = $device_token;
                        $device_var_arr[$device_token] = $data_arr[$i];
                    }
                }
            }
            $succ = $fail = $cron = 0;
            if (!is_array($device_ids_arr) || count($device_ids_arr) == 0) {
                throw new Exception("Device tokens are missing..!");
            }
            for ($k = 0, $j = 1; $k < count($device_ids_arr); $k++, $j++) {
                $to = $device_ids_arr[$k];
                $row_arr = $device_var_arr[$to];
                $send_vars = $pair_vars = $notify_arr = array();
                if (is_array($push_notify_variable_arr) && count($push_notify_variable_arr) > 0) {
                    foreach ($push_notify_variable_arr as $key => $val) {
                        $param = $val;
                        if ($push_notify_value_arr[$key] == "Other") {
                            $value = $push_notify_other_arr[$key];
                        } else {
                            $value = $row_arr[$push_notify_value_arr[$key]];
                        }
                        $tmp_arr = array();
                        $tmp_arr['key'] = $param;
                        $tmp_arr['value'] = $value;
                        $tmp_arr['send'] = ($push_notify_comp_arr[$key] == "Yes") ? "Yes" : "No";
                        $send_vars[] = $tmp_arr;
                        if ($tmp_arr['send'] == "Yes") {
                            $pair_vars[$param] = $value;
                        }
                    }
                }
                $push_msg = $this->general->getReplacedInputParams($message, $row_arr);
                $unique_id = $this->general->getPushNotifyUnique();

                $insert_arr = $notify_arr = array();

                $notify_arr['message'] = $push_msg;
                $notify_arr['title'] = $title;
                $notify_arr['badge'] = intval($badge);
                $notify_arr['sound'] = $sound;
                $notify_arr['code'] = $code;
                $notify_arr['id'] = $unique_id;
                $notify_arr['others'] = $pair_vars;
                $success = $this->general->pushTestNotification($to, $notify_arr);

                $insert_arr['vUniqueId'] = $unique_id;
                $insert_arr['vDeviceId'] = $to;
                $insert_arr['eMode'] = $this->config->item('PUSH_NOTIFY_SENDING_MODE');
                $insert_arr['eNotifyCode'] = $code;
                $insert_arr['vSound'] = $sound;
                $insert_arr['vBadge'] = intval($badge);
                $insert_arr['vTitle'] = $title;
                $insert_arr['tMessage'] = $push_msg;
                $insert_arr['tVarsJSON'] = json_encode($send_vars);
                if (strlen($to) < 70) {
                    $insert_arr['eDeviceType'] = "Android";
                } else {
                    $insert_arr['eDeviceType'] = "iOS";
                }
                $insert_arr['dtAddDateTime'] = date("Y-m-d H:i:s");
                $insert_arr['dtExeDateTime'] = date("Y-m-d H:i:s");
                if ($success) {
                    $insert_arr['eStatus'] = 'Executed';
                    $succ++;
                } else {
                    $insert_arr['tError'] = $this->general->getNotifyErrorOutput();
                    $insert_arr['eStatus'] = 'Failed';
                    $fail++;
                }
                $this->push->insertPushNotify($insert_arr);
            }

            $ret_arr['success'] = 1;
            $ret_msg = '';
            if ($succ == 0 && $cron == 0 && $fail == 0) {
                $ret_msg = 'No notifications sent..!';
                $ret_arr['success'] = 0;
            } else {
                if ($succ > 0) {
                    $ret_msg .= 'Sent notifications(' . $succ . '). ';
                }
                if ($fail > 0) {
                    $ret_msg .= 'Failed notifications(' . $fail . ')';
                }
                $ret_arr['success'] = 1;
            }
            $ret_arr['message'] = $ret_msg;
        } catch (Exception $e) {
            $ret_arr['success'] = 0;
            $ret_arr['message'] = $msg;
        }
        $ret_arr['red_type'] = 'Stay';
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }
}
