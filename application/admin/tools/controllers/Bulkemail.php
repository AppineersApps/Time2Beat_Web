<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of BulkEmail Controller
 *
 * @category admin
 *            
 * @package tools
 * 
 * @subpackage controllers
 * 
 * @module BulkEmail
 * 
 * @class Bulkemail.php
 * 
 * @path application\admin\tools\controllers\Bulkemail.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Bulkemail extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->folder_name = "tools";
        $this->module_name = "bulkemail";
        $this->mod_url_cod = array(
            "bulkmail_action",
            "bulkmail_sendto",
            "bulkmail_variables"
        );
        $this->get_arr = is_array($this->input->get(NULL, TRUE)) ? $this->input->get(NULL, TRUE) : array();
        $this->post_arr = is_array($this->input->post(NULL, TRUE)) ? $this->input->post(NULL, TRUE) : array();
        $this->params_arr = array_merge($this->get_arr, $this->post_arr);
        $this->mod_enc_url = $this->general->getCustomEncryptURL($this->mod_url_cod, TRUE);
        $this->load->model('bulkemail_model');
        $this->load->model('user/admin_model');
        $this->load->model('tools/systememails_model');
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
                $view_access = $this->filter->checkAccessCapability("bulk_email", TRUE);
            } else {
                $view_access = $this->filter->getModuleWiseAccess("BulkEmail", "Add", FALSE, TRUE);
            }
            if (!$view_access) {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }

            $db_email['Groups'] = $this->bulkemail_model->getGroupDetails();
            $db_module_data = $this->ci_misc->getBulkEmailModules();
            $email_cond = $this->systememails_model->extra_cond;
            $email_temp_data = $this->systememails_model->getData($email_cond, "mse.vEmailCode, mse.vEmailTitle");
            if (is_array($db_module_data) && ($db_module_data) > 0) {
                $db_module['Module'] = $db_module_data;
            }
            $render_arr = array(
                'db_email' => $db_email,
                'db_module' => $db_module,
                'email_temp_data' => $email_temp_data,
                'mod_enc_url' => $this->mod_enc_url
            );
            $this->smarty->assign($render_arr);
            $this->loadView('bulkemail');
        } catch (Exception $e) {
            $render_arr['err_message'] = $e->getMessage();
            $this->smarty->assign($render_arr);
            $this->loadView($this->config->item('ADMIN_FORBIDDEN_TEMPLATE'));
        }
    }

    /**
     * bulkmail_action method is used to send emails to the specfied receivers.
     */
    public function bulkmail_action()
    {
        try {
            $parameter_name_arr = $parameter_other_arr = array();
            $send_to = $this->params_arr['vSendTo'];
            $user_ids = $this->params_arr['vUser'];
            $email_template = trim($this->params_arr['vEmailTemplate']);
            $email_subject = $this->params_arr['vEmailSubject'];
            $from_email = $this->params_arr['vFromEmail'];
            $email_content = $this->input->get_post('vEmailContent');
            $email_content = stripslashes($email_content);
            $email_address = $this->params_arr['vEmailAddress'];
            $mode = $this->params_arr['mode'];
            $parameter_name_arr = $this->params_arr['vParameterFieldName'];
            $parameter_other_arr = $this->params_arr['vParameterFieldNameOther'];
            if ($email_template != "") {
                $email_temp_data = $this->systememails_model->getData($this->db->protect("mse.vEmailCode") . " = " . $this->db->escape($email_template));
                $email_temp_vars = $this->systememails_model->getVariableData($email_temp_data[0]['iEmailTemplateId']);
            }

            if ($send_to == 'Other') {
                $email_address_arr = explode(',', $email_address);
            } else {
                $send_details = explode('@@', $send_to);
                $type = $send_details[0];
                $mail_var_arr = array();
                if ($type == "Grp") {
                    $email_address_arr = $user_ids;
                    $data_arr = $this->ci_misc->getBulkEmailModuleData($send_details[1]);
                } elseif ($type == "Mod") {
                    $field_name = $this->input->get_post('vFieldName', TRUE);
                    $data_arr = $this->ci_misc->getBulkEmailModuleData($send_details[1]);
                }
                if (is_array($data_arr) && count($data_arr) > 0) {
                    for ($i = 0; $i < count($data_arr); $i++) {
                        if ($type == "Mod") {
                            $email_address = $data_arr[$i][$field_name];
                            $email_address_arr[] = $email_address;
                        }
                        $mail_var_arr[$email_address] = $data_arr[$i];
                    }
                }
            }

            if (is_array($email_address_arr) && count($email_address_arr) > 0) {
                for ($k = 0; $k < count($email_address_arr); $k++) {
                    $to = $email_address_arr[$k];
                    if ($email_template != "") {
                        $data = array();
                        if (is_array($email_temp_vars) && count($email_temp_vars) > 0) {
                            foreach ($email_temp_vars as $key => $val) {
                                $innerkey = trim($val['vVarName'], "#");
                                if ($parameter_name_arr[$innerkey] == 'Other') {
                                    $data[$innerkey] = $parameter_other_arr[$innerkey];
                                } else {
                                    $data[$innerkey] = $mail_var_arr[$to][$parameter_name_arr[$innerkey]];
                                }
                            }
                        }
                        $data['vEmail'] = $to;
                        $data['vSubject'] = $email_subject;
                        $data['vFromEmail'] = $from_email;
                        $success = $this->general->Sendmail($data, $email_template);
                    } else {
                        $success = $this->general->CISendMail($to, $email_subject, $email_content, $from_email, $from_email);
                    }
                }
            }
            if (!$success) {
                $msg = $this->general->getNotifyErrorOutput();
                $msg = ($msg) ? $msg : "Mail sending failed.";
                throw new Exception($msg);
            }
            $msg = 'Mail sent successfully';
            $ret_arr['success'] = 1;
            $ret_arr['message'] = $msg;
        } catch (Exception $e) {
            $ret_arr['success'] = 0;
            $ret_arr['message'] = $e->getMessage();
        }
        $ret_arr['red_type'] = 'Stay';
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    /**
     * ajax_bulk_mail method is used to get group wise or module wise bulk email receivers.
     */
    public function ajax_bulk_mail()
    {
        $sent_id = $this->params_arr['sent_id'];
        if (trim($sent_id) == 'Other') {
            $sent_type = "Other";
        } else {
            $sent_idarr = explode("@@", $sent_id);
            if ($sent_idarr[0] == "Grp") {
                $sent_type = "Groups";
                $id = $sent_idarr[1];
                $extra_cond = $this->db->protect("ma.iGroupId") . " = " . $this->db->escape($id);
                $email_arr = $this->admin_model->getData($extra_cond, "ma.vEmail", "", "", "", "Yes");
            } elseif ($sent_idarr[0] == "Mod") {
                $sent_type = "Modules";
                $id = $sent_idarr[1];
                $params_arr['module_name'] = $id;
                $email_arr = $this->ci_misc->getBulkEmailModuleFields($params_arr);
            }
        }
        $render_arr = array(
            'sent_id' => $sent_id,
            'sent_type' => $sent_type,
            'email_arr' => $email_arr
        );
        $this->smarty->assign($render_arr);
        $this->loadView('ajax_bulk_mail');
    }

    /**
     * ajax_bulk_temp_variables method is used to map email template variables.
     */
    public function ajax_bulk_temp_variables()
    {
        $field_arr = array();
        $from_id = $this->params_arr['from_id'];
        $email_template = $this->params_arr['sent_template'];
        $send_details = explode('@@', $from_id);
        $type = $send_details[0];
        $value = $send_details[1];
        if ($email_template != '') {
            $email_temp_arr = $this->bulkemail_model->getEmailTemplateVariables($email_template);
        }
        if ($type == 'Mod') {
            $field_arr = $this->ci_misc->getBulkEmailModuleListFields($value);
        } elseif ($type == 'Grp') {
            $field_arr = $this->ci_misc->getBulkEmailModuleListFields('admin');
        }
        $render_arr = array(
            'sent_type' => $type,
            'email_temp_arr' => $email_temp_arr,
            'field_arr' => $field_arr
        );
        $this->smarty->assign($render_arr);
        $this->loadView('ajax_bulk_temp_variables');
    }
}
