<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Login Controller
 *
 * @category admin
 *            
 * @package user
 * 
 * @subpackage controllers
 * 
 * @module Login
 * 
 * @class Login.php
 * 
 * @path application\admin\user\controllers\Login.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Login extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('filter');
        $this->load->library('listing');
        $this->load->model('admin_model');
        $this->load->model('group_model');
        $this->mod_url_cod = array(
            "user_login_index",
            "user_login_entry",
            "user_login_entry_a",
            "user_login_logout",
            "user_sess_expire",
            "user_forgot_password_action",
            "user_changepassword",
            "user_changepassword_action",
            "user_resetpassword",
            "user_resetpassword_action",
            "user_notify_events",
            "dashboard_index",
            "google_verification",
            "otp_authentication",
            "otp_verification",
            "resend_otp",
            "user_admin_index",
            "try_another_way",
            "setup_google_auth",
            "update_multi_groups"
        );
        $this->mod_enc_url = $this->general->getCustomEncryptURL($this->mod_url_cod, true);
    }

    /**
     * index method is used to intialize index page.
     */
    public function index()
    {
        
    }

    /**
     * entry method is used to display admin login page.
     */
    public function entry()
    {
        if ($this->session->userdata("iAdminId") > 0) {
            redirect($this->config->item("admin_url") . "#" . $this->mod_enc_url['dashboard_index']);
        } else {
            $is_patternlock = "no";
            $LOGIN_PASSWORD_TYPE = $this->config->item("LOGIN_PASSWORD_TYPE");
            $setting_pattern_lock = (strtolower($LOGIN_PASSWORD_TYPE) == "y") ? true : false;
            if ($setting_pattern_lock) {
                $pwd_settings = $this->admin_model->getPasswordSettings();
                $admin_pattern_lock = strtolower($pwd_settings['pattern']);
                $is_patternlock = (strtolower($admin_pattern_lock) == "yes") ? "yes" : "no";
            }

            $render_arr = array();
            /* cookie data for saving login details */
            $remember_me_data = $this->cookie->read($this->general->getMD5EncryptString("RememberMe"));
            $remember_me = $passwd = $login_name = "";
            $remember_me_arr = json_decode($remember_me_data[0], true);
            if (is_array($remember_me_arr) && count($remember_me_arr) > 0) {
                if ($remember_me_arr['remember'] == "Yes") {
                    $remember_me = $remember_me_arr["remember"];
                    $login_data = $remember_me_arr["data"];
                    $login_name = $login_data["_user"];
                    $passwd = $login_data["_pass"];
                    $login_name = $this->general->decryptDataMethod($login_name);
                    $passwd = $this->general->decryptDataMethod($passwd);
                }
            }
            $enc_url['forgot_pwd_url'] = $this->config->item("admin_url") . $this->mod_enc_url['user_forgot_password_action'];
            $enc_url['entry_action_url'] = $this->config->item("admin_url") . $this->mod_enc_url['user_login_entry_a'];

            $render_arr["is_patternlock"] = $is_patternlock;
            $render_arr["login_name"] = $login_name;
            $render_arr["passwd"] = $passwd;
            $render_arr["remember_me"] = $remember_me;
            $render_arr["enc_url"] = $enc_url;

            $this->smarty->assign($render_arr);
            $file_name = "admin_login_template";
            $this->set_template($file_name);
        }
    }

    /**
     * entry_a method is used to check admin login user.
     */
    public function entry_a()
    {

        $mode = $this->input->get_post('mode', TRUE);
        try {

            $login_name = $this->input->get_post('login_name', TRUE);
            $login_pass = $this->input->get_post('passwd', TRUE);

            $handle_url = $this->input->get_post('handle_url', TRUE);
            $handle_url = ltrim($handle_url, '#');

            $pwd_settings = $this->admin_model->getPasswordSettings();
            $is_patternlock = strtolower($pwd_settings['pattern']);
            $is_encryptdata = strtolower($pwd_settings['encrypt']);
            $encrypt_method = strtolower($pwd_settings['enctype']);

            $setting_pattern_lock = (strtolower($this->config->item("LOGIN_PASSWORD_TYPE")) == "y") ? TRUE : FALSE;
            $master_password = $this->config->item('ADMIN_MASTER_PASSWORD');

            $this->session->set_tempdata('tmp_username', $login_name, 300);
            $this->session->set_tempdata('tmp_password', $login_pass, 300);
            $this->session->set_tempdata('tmp_handle_url', $handle_url, 300);

            $skip_2fa = false;
            if ($is_encryptdata == "yes") {
                $result = $this->admin_model->getAdminUser($login_name, FALSE);
                if (!is_array($result) || count($result) == 0) {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_HAVE_ENTERED_WRONG_LOGIN_NAME_OR_PASSWORD_PLEASE_TRY_AGAIN_C46_C46_C33'));
                }
                $password_verification = $this->general->verifyEncryptData($login_pass, $result[0]["vPassword"], $encrypt_method);
                if ($password_verification === FALSE) {
                    if ($master_password != "") {
                        if ($master_password !== $login_pass) {
                            throw new Exception($this->general->processMessageLabel('ACTION_YOU_HAVE_ENTERED_WRONG_LOGIN_NAME_OR_PASSWORD_PLEASE_TRY_AGAIN_C46_C46_C33'));
                        } else {
                            $skip_2fa = true;
                        }
                    } else {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_HAVE_ENTERED_WRONG_LOGIN_NAME_OR_PASSWORD_PLEASE_TRY_AGAIN_C46_C46_C33'));
                    }
                }
            } else {
                $result = $this->admin_model->getAdminUser($login_name, $login_pass);
                if (!is_array($result) || count($result) == 0) {
                    if ($master_password != "") {
                        $result = $this->admin_model->getAdminUser($login_name, FALSE);
                        if (!is_array($result) || count($result) == 0) {
                            throw new Exception($this->general->processMessageLabel('ACTION_YOU_HAVE_ENTERED_WRONG_LOGIN_NAME_OR_PASSWORD_PLEASE_TRY_AGAIN_C46_C46_C33'));
                        } else {
                            if ($master_password !== $login_pass) {
                                throw new Exception($this->general->processMessageLabel('ACTION_YOU_HAVE_ENTERED_WRONG_LOGIN_NAME_OR_PASSWORD_PLEASE_TRY_AGAIN_C46_C46_C33'));
                            } else {
                                $skip_2fa = true;
                            }
                        }
                    }
                }
            }

            if (!is_array($result) || count($result) == 0) {
                if ($setting_pattern_lock && $is_patternlock == "yes") {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_HAVE_ENTERED_WRONG_LOGIN_NAME_OR_PASSWORD_PATTERN_PLEASE_TRY_AGAIN_C46_C46_C33'));
                } else {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_HAVE_ENTERED_WRONG_LOGIN_NAME_OR_PASSWORD_PLEASE_TRY_AGAIN_C46_C46_C33'));
                }
            }

            if ($result[0]["eStatus"] != 'Active') {
                throw new Exception($this->general->processMessageLabel('ACTION_YOUR_ADMIN_LOGIN_TEMPORARILY_INACTIVATED_PLEASE_CONTACT_ADMINISTRATOR_C46_C46_C33'));
            }
            if ($result[0]["vGroupStaus"] != 'Active') {
                throw new Exception($this->general->processMessageLabel('ACTION_YOUR_GROUP_LOGIN_TEMPORARILY_INACTIVATED_PLEASE_CONTACT_ADMINISTRATOR_C46_C46_C33'));
            }
            $this->general->checkUserAccountStatus();

            $remember_me = $this->input->get_post('remember_me', TRUE);
            $cookie_str = $this->general->getMD5EncryptString("RememberMe");
            if (isset($remember_me) && $remember_me == "Yes") {
                $rem_login_arr = array();
                $rem_login_arr['_user'] = $this->general->encryptDataMethod($login_name);
                $rem_login_arr['_pass'] = $this->general->encryptDataMethod($login_pass);

                $rem_frm_data_arr["remember"] = "Yes";
                $rem_frm_data_arr["data"] = $rem_login_arr;
                $remfrm_data_json = json_encode($rem_frm_data_arr);
                $this->cookie->write($cookie_str, array($remfrm_data_json));
            } else {
                $this->cookie->delete($cookie_str);
            }

            $dm_data = $this->cookie->read($this->general->getMD5EncryptString("DontAskMe", $login_name));
            $dm_data_arr = json_decode($dm_data[0], true);
            if (is_array($dm_data_arr) && count($dm_data_arr) > 0 && $dm_data_arr['dont_ask_me'] == "Yes") {
                $skip_2fa = true;
            }
            $auth_arr = explode(",", $result[0]["eAuthType"]);
            if ($skip_2fa == true) {
                unset($auth_arr);
                $auth_arr = ['noAuth'];
            }
            if (in_array('Google', $auth_arr) && $result[0]['vAuthCode'] != "") {
                redirect($this->config->item('admin_url') . $this->mod_enc_url['otp_authentication']);
            } else if (in_array('Email', $auth_arr) || in_array('SMS', $auth_arr)) {
                if (in_array('Email', $auth_arr)) {
                   $this->send_otp("Email", $result);
                }else{
                    $this->send_otp("SMS", $result);
                }
                redirect($this->config->item('admin_url') . $this->mod_enc_url['otp_authentication']);
            } else {
                $extra_param = $this->save_session("noAuth", $result);
                redirect($this->config->item('admin_url') . $extra_param);
            }

            $this->skip_template_view();
        } catch (Exception $e) {
            $err_msg = $e->getMessage();
            $this->session->set_flashdata('failure', $err_msg);
            redirect($this->config->item("admin_url") . $this->mod_enc_url['user_login_entry'] . "?_=" . time());
            $this->skip_template_view();
        }
    }

    /**
     * save_session method is used to save logged-in session data.
     */
    public function save_session($type = '', $result = array())
    {
        $this->load->model('user/loghistory_model');
        $login_name = $this->session->tempdata('tmp_username');
        $handle_url = $this->session->tempdata('tmp_handle_url');
        if ($type != "noAuth") {
            $result = $this->admin_model->getAdminUser($login_name, FALSE);
        }

        $user_array = array();
        if (is_array($result[0]) && count($result[0]) > 0) {
            foreach ($result[0] as $key => $val) {
                $val = stripslashes(str_replace(array("\r", "\n"), '', $val));
                $user_array[$key] = $val;
            }
        }
        $user_array['isLoggedIn'] = true;
        $this->session->set_userdata($user_array);

        $sess_array = array();
        $sess_array['loggedAt'] = time();
        $sess_array['timeOut'] = $this->general->getSessionExpire();
        $this->session->set_userdata($sess_array);

        $extra_cond = $this->db->protect("iUserId") . " = " . $this->db->escape($result[0]["iAdminId"]);
        $log_result = $this->loghistory_model->getData($extra_cond, "", "mlh_log_id DESC");
        $this->general->logInOutEntry($result[0]["iAdminId"], 'Admin');

        $login_callback = $this->config->item('login_callback');
        if ($login_callback != "" && method_exists($this->general, $login_callback)) {
            $this->general->$login_callback($user_array);
        }

        if (trim($handle_url) != '') {
            $extra_param = '#' . $handle_url;
        } else {
            $extra_param = $this->filter->getLandingpageURL($result[0], $log_result[0]['mlh_current_url']);
        }

        $var_msg = $this->general->replaceDisplayLabel($this->lang->line("GENERIC_LOGIN_USER_WELCOME_MESSAGE"), "#NAME#", $this->session->userdata("vName"));
        $this->session->set_flashdata('success', $var_msg);

        return $extra_param;
    }

    /**
     * logout method is used to log out the current login user.
     */
    public function logout()
    {
        $hash_val = $this->input->get_post('hashVal', TRUE);
        $extra_arr = array();
        $extra_arr['hashVal'] = trim($hash_val != "") ? $hash_val : "";
        $this->general->logInOutEntry($this->session->userdata("iAdminId"), 'Admin', $extra_arr);
        $session_arr = $this->session->all_userdata();
        $session_key = is_array($session_arr) ? array_keys($session_arr) : array();
        $this->session->unset_userdata($session_key);
        $this->session->set_flashdata('success', $this->lang->line('GENERIC_YOU_HAVE_SUCCESSFULLY_LOGGED_OUT'));
        $this->session->set_flashdata('failure', "");

        $return_arr['success'] = 1;
        $return_arr['message'] = $err_msg;

        echo json_encode($return_arr);
        $this->skip_template_view();
    }

    /**
     * sess_expire method is used to show session expire page.
     */
    public function sess_expire()
    {
        $render_arr = array(
            "login_entry_url" => $this->config->item("admin_url") . $this->mod_enc_url['user_login_entry']
        );
        $this->smarty->assign($render_arr);
        $file_name = "admin_sess_expire_template";
        $this->set_template($file_name);
        $this->loadView("sess_expire");
    }

    /**
     * forgot_password_action method is used to send forgot password action.
     */
    public function forgot_password_action()
    {
        $username = $this->input->get_post('username', TRUE);
        try {
            if ($username == '') {
                $error_msg = $this->general->processMessageLabel('ACTION_PLEASE_ENTER_LOGIN_NAME_C46_C46_C33');
                throw new Exception($error_msg);
            }

            $username_cond = $this->db->protect("vUserName") . " = " . $this->db->escape($username);
            $email_cond = $this->db->protect("vEmail") . " = " . $this->db->escape($username);
            $extra_cond = "(" . $username_cond . " OR " . $email_cond . ")";
            $db_query = $this->admin_model->getData($extra_cond, "iAdminId, vName, vEmail");
            if (!is_array($db_query) || count($db_query) == 0) {
                $error_msg = $this->general->processMessageLabel('ACTION_UNABLE_TO_FIND_A_USER_WITH_THIS_LOGIN_NAME_C46_C46_C33');
                throw new Exception($error_msg);
            }

            $numeric = range(1, 9);
            $length = count($numeric) - 1;
            $results = array();
            for ($i = 0; $i < 6;) {
                $num = $numeric[mt_rand(0, $length)];
                if (!in_array($num, $results)) {
                    $results[] = $num;
                    $i++;
                }
            }

            $reset_code = implode("", $results);
            $reset_param = base64_encode($db_query[0]['iAdminId'] . "@@" . $reset_code . "@@" . time());
            $reset_url = $this->config->item("admin_url") . $this->mod_enc_url['user_resetpassword'] . "?rsp=" . $reset_param;

            $email_vars = array();
            $email_vars['vEmail'] = $db_query[0]['vEmail'];
            $email_vars['vName'] = $db_query[0]['vName'];
            $email_vars['RESET_CODE'] = $reset_code;
            $email_vars['RESET_URL'] = $reset_url;
            $mail_success = $this->general->sendMail($email_vars, 'ADMIN_RESET_PASSWORD');
            $this->general->logExecutedEmails('Admin', $email_vars, $mail_success);
            if (!$mail_success) {
                throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_SENDING_MAIL_C46_C46_C33'));
            }

            $success = 1;
            $message = $this->general->processMessageLabel('ACTION_PLEASE_VISIT_THE_LINK_WHICH_HAS_BEEN_SENT_TO_YOUR_MAIL_C46_C46_C33');
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $return_arr['message'] = $message;
        $return_arr['success'] = $success;

        echo json_encode($return_arr);
        $this->skip_template_view();
    }

    /**
     * changepassword method is used to display form for changing password.
     */
    public function changepassword()
    {
        $is_patternlock = "no";
        $setting_pattern_lock = (strtolower($this->config->item("LOGIN_PASSWORD_TYPE")) == "y") ? true : false;

        if ($setting_pattern_lock) {
            $pwd_settings = $this->admin_model->getPasswordSettings();
            $admin_pattern_lock = strtolower($pwd_settings['pattern']);
            $is_patternlock = (strtolower($admin_pattern_lock) == "yes") ? "yes" : "no";
        }
        $changepassword_url = $this->config->item("admin_url") . $this->mod_enc_url['user_changepassword_action'];

        $render_arr = array();
        $render_arr["is_patternlock"] = $is_patternlock;
        $render_arr["changepassword_url"] = $changepassword_url;
        $render_arr["id"] = $this->session->userdata('iAdminId');
        $enc_id = $this->general->getAdminEncodeURL($render_arr['id']);
        $render_arr["enc_id"] = $enc_id;
        $userdata = $this->session->userdata();
        $render_arr['name'] = $userdata['vName'];
        $render_arr['email'] = $userdata['vEmail'];

        $this->smarty->assign($render_arr);
        $this->loadView("change_password");
    }

    /**
     * changepassword_action method is used to save changed password.
     */
    public function changepassword_action()
    {
        $admin_id = $this->input->get_post('id', TRUE);
        $old_password = $this->input->get_post('vOldPassword', TRUE);
        $password = $this->input->get_post('vPassword', TRUE);
        $pattern_lock = strtolower($this->input->get_post('patternLock', TRUE));
        $confirm_password = $this->input->get_post('vConfirmPassword', TRUE);

        try {
            $db_user_details = $this->admin_model->getData($admin_id, "vName, vEmail, vPassword");

            if ($admin_id != $this->session->userdata('iAdminId')) {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }
            $pwd_settings = $this->admin_model->getPasswordSettings();
            $is_encryptdata = strtolower($pwd_settings['encrypt']);
            $encrypt_method = strtolower($pwd_settings['enctype']);
            if ($is_encryptdata == 'yes') {
                $password_res = $this->general->verifyEncryptData($old_password, $db_user_details[0]['vPassword'], $encrypt_method);
                if ($old_password == "" || !$password_res) {
                    throw new Exception($this->general->processMessageLabel('ACTION_OLD_PASSWORD_IS_INCORRECT_C46_C46_C33'));
                }
            } else {
                $db_password = $db_user_details[0]['vPassword'];
                if ($old_password == "" || $db_password != $old_password) {
                    throw new Exception($this->general->processMessageLabel('ACTION_OLD_PASSWORD_IS_INCORRECT_C46_C46_C33'));
                }
            }

            if ($password == "") {
                throw new Exception($this->general->processMessageLabel('ACTION_PLEASE_ENTER_NEW_PASSWORD_C46_C46_C33'));
            }
            if ($pattern_lock != "yes") {
                if ($confirm_password == "" || $confirm_password != $password) {
                    throw new Exception($this->general->processMessageLabel('ACTION_NEW_PASSWORD_AND_CONFIRM_PASSWORD_DOES_NOT_MATCH_C46_C46_C33'));
                }
            }
            if ($is_encryptdata == 'yes') {
                $new_password = $this->general->encryptDataMethod($password, $encrypt_method);
            } else {
                $new_password = $password;
            }

            $update_arr = array();
            $update_arr["vPassword"] = $new_password;
            $res = $this->admin_model->update($update_arr, $admin_id);

            if (!$res) {
                throw new Exception($this->general->processMessageLabel('ACTION_FALIURE_IN_CHANGING_PASSWORD_C46_C46_C33'));
            }

            $email_vars = array(
                "vEmail" => $db_user_details[0]['vEmail'],
                "vName" => $db_user_details[0]['vName']
            );
            $mail_success = $this->general->sendMail($email_vars, 'ADMIN_PASSWORD_CHANGED');
            $this->general->logExecutedEmails('Admin', $email_vars, $mail_success);
            $success = 1;
            $message = $this->general->processMessageLabel('ACTION_PASSWORD_CHANGED_SUCCESSFULLY_C46_C46_C33');
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $return_arr['message'] = $message;
        $return_arr['success'] = $success;

        echo json_encode($return_arr);
        $this->skip_template_view();
    }

    /**
     * resetpassword method is used to display form for reset password.
     */
    public function resetpassword()
    {
        $rsp = $this->input->get_post("rsp", TRUE);
        if ($this->session->userdata("iAdminId") > 0) {
            redirect($this->config->item("admin_url") . "#" . $this->mod_enc_url['dashboard_index']);
        } else {
            $is_patternlock = "no";
            $setting_pattern_lock = (strtolower($this->config->item("LOGIN_PASSWORD_TYPE")) == "y") ? true : false;
            if ($setting_pattern_lock) {
                $pwd_settings = $this->admin_model->getPasswordSettings();
                $admin_pattern_lock = strtolower($pwd_settings['pattern']);
                $is_patternlock = (strtolower($admin_pattern_lock) == "yes") ? "yes" : "no";
            }
            $resetpassword_url = $this->config->item("admin_url") . $this->mod_enc_url['user_resetpassword_action'];

            $rsp_dec = base64_decode($rsp, TRUE);
            $rsp_arr = explode("@@", $rsp_dec);

            $render_arr["id"] = base64_encode($rsp_arr[0]);
            $render_arr["code"] = base64_encode($rsp_arr[1]);
            $render_arr["time"] = base64_encode($rsp_arr[2]);
            $render_arr["is_patternlock"] = $is_patternlock;
            $render_arr["resetpassword_url"] = $resetpassword_url;
            $this->smarty->assign($render_arr);

            $file_name = "admin_login_template";
            $this->set_template($file_name);
        }
    }

    /**
     * resetpassword_action method is used to reset password for admin user.
     */
    public function resetpassword_action()
    {
        $admin_id = $this->input->get_post("userid", TRUE);
        $code = $this->input->get_post("code", TRUE);
        $time = $this->input->get_post("time", TRUE);
        $password = $this->input->get_post("password", TRUE);
        $securitycode = $this->input->get_post("securitycode", TRUE);

        try {
            $admin_id = base64_decode($admin_id);
            $time = base64_decode($time);
            $code = base64_decode($code);

            if ($code != $securitycode) {
                throw new Exception($this->general->processMessageLabel('ACTION_SECURITY_CODE_FAILED_C46_C46_C33'));
            }
            $currenttime = time();
            $resettime = $this->config->item("ADMIN_RESET_PASSWORD_TIME") * 60 * 60 * 1000; //check 1sec
            $delay = $currenttime - $time;
            if ($admin_id > 0 && $delay < $resettime) {
                $pwd_settings = $this->admin_model->getPasswordSettings();
                $is_encryptdata = strtolower($pwd_settings['encrypt']);
                $encrypt_method = strtolower($pwd_settings['enctype']);
                if ($is_encryptdata == 'yes') {
                    $new_password = $this->general->encryptDataMethod($password, $encrypt_method);
                } else {
                    $new_password = $password;
                }

                $update_arr = array();
                $update_arr["vPassword"] = $new_password;
                $res = $this->admin_model->update($update_arr, $admin_id);

                if (!$res) {
                    throw new Exception($this->general->processMessageLabel('ACTION_RESET_PASSWORD_FAILED_C46_C46_C33'));
                }

                $message = $this->general->processMessageLabel('ACTION_PLEASE_LOGIN_WITH_YOUR_NEW_PASSWORD_C46_C46_C33');
                $this->session->set_flashdata('success', $message);
            } else {
                throw new Exception($this->general->processMessageLabel('ACTION_TIME_EXCEEDED_TO_RESET_THE_PASSWORD_C46_C46_C33'));
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
            $this->session->set_flashdata('failure', $message);
        }
        redirect($this->config->item("admin_url") . $this->mod_enc_url['user_login_entry'] . "?_=" . time());
    }

    /**
     * notify_events method is used to check desktop notifications for admin user.
     */
    public function notify_events()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        $manual = $this->input->get_post('manual', TRUE);
        $call_interval = $this->config->item('ADMIN_NOTIFY_TIME_INTERVAL');
        $enable_desktop_events = $this->config->item('ADMIN_DESKTOP_NOTIFICATIONS');
        $notify_arr = $notify_ids = array();
        if ($this->session->userdata('iAdminId') > 0 ) {
            if($this->config->item('ADMIN_DESKTOP_NOTIFICATIONS') == "Y"){
                $this->db->select('*');
                $this->db->from("mod_executed_notifications");
                $this->db->where("iEntityId", $this->session->userdata('iAdminId'));
                $this->db->where("eNotificationType", "DesktopNotify");
                $this->db->where("eEntityType", "Admin");
                $this->db->where("eStatus", "Pending");
                $this->db->limit(7);
                $result_obj = $this->db->get();
                $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
                if (is_array($result_arr) && count($result_arr) > 0) {
                    for ($i = 0; $i < count($result_arr); $i++) {
                        $link = $link_target = $link_class = "";
                        $val = $result_arr[$i];
                        if ($val['vRedirectLink'] != "") {
                            $redirect_link_arr = unserialize(stripslashes($val['vRedirectLink']));
                            $link = $redirect_link_arr['link'];
                            if ($redirect_link_arr['target']) {
                                $link_target = "target='" . $redirect_link_arr['target'] . "'";
                            }
                            if ($redirect_link_arr['class']) {
                                $link_class = $redirect_link_arr['class'];
                            }
                        }
                        $notify_arr[$i]['type'] = "success";
                        $notify_arr[$i]['link'] = $this->general->getCustomHashLink($link);
                        if ($notify_arr[$i]['link'] != "") {
                            $notify_arr[$i]['subject'] = "<a href='" . $notify_arr[$i]['link'] . "' class='" . $link_class . "' " . $link_target . " >" . $val['vSubject'] . "</a>";
                            $notify_arr[$i]['message'] = $val['tContent'];
                            $notify_arr[$i]['subject'] = $val['vSubject'];
                        } else {
                            $notify_arr[$i]['subject'] = $val['vSubject'];
                            $notify_arr[$i]['message'] = $val['tContent'];
                        }
                        $notify_ids[] = $val["iExecutedNotificationId"];
                    }
                    $this->db->where_in('iExecutedNotificationId', $notify_ids);
                    $this->db->update('mod_executed_notifications', array('eStatus' => 'Executed'));
                }
            }
            if($this->config->item('ADMIN_NOTIFICATIONS_ACTIVATE') == "Y"){
                $notifications = $this->general->getNotifications("Notify");
            }
            $send_arr['success'] = 1;
            $send_arr['content'] = $notify_arr;
            $send_arr['notifications'] = $notifications;
        } else {
            $send_arr['success'] = 0;
            $send_arr['content'] = array();
        }
        if ($manual == "true") {
            $data_arr = array();
            if ($send_arr['success'] == 1) {
                $data_arr['retry'] = intval($call_interval);
            }
            $data_arr['data'] = $send_arr;
            echo json_encode($data_arr);
        } else {
            if ($send_arr['success'] == 1) {
                echo "retry: " . intval($call_interval) . "\n\n";
            }
            echo "data: " . json_encode($send_arr) . "\n\n";
        }
        flush();
        $this->skip_template_view();
    }

    /**
     * manifest method is used to load manifest file for appcache.
     */
    public function manifest()
    {
        $ci_source_appcache = $this->config->item("admin_appcache_src_path") . $this->config->item('ADMIN_THEME_DISPLAY') . DS . $this->config->item('ADMIN_APPCACHE_FILE');
        $ci_target_appcache = $this->config->item("site_path") . $this->config->item('ADMIN_APPCACHE_FILE');

        if ($this->session->userdata('iAdminId') > 0 && $this->config->item("ADMIN_ASSETS_APPCACHE") == 'Y') {
            if ($this->config->item('cdn_activate') == '1') {
                $cdn_url = $this->config->item('cdn_http_url');
                $images_source_dir = $cdn_url . "images/";
                $fonts_source_dir = $cdn_url . "fonts/";
                $js_compile_cache_dir = $cdn_url . "js/common/main_common.js";
                $css_compile_cache_dir = $cdn_url . "css/common/main_common.css";
            } else {
                $this->parser->parse("admin_include_css", array(), true);
                $css_compile_dir = $this->css->css_common_src("common", 1);
                $this->parser->parse("admin_include_js", array(), true);
                $js_compile_dir = $this->js->js_common_src("common", 1);

                $images_source_dir = "admin/public/images/";
                $fonts_source_dir = "admin/public/styles/fonts/";
                $js_compile_cache_dir = "public/js/compiled/" . $js_compile_dir . "/main_common.js";
                $css_compile_cache_dir = "admin/public/styles/compiled/" . $css_compile_dir . "/main_common.css";
            }

            $contents = file_get_contents($ci_source_appcache);
            $find_arr = array("##IMAGES_COMMON_URL##", "##FONTS_COMMON_URL##", "##JS_COMMON_CACHE_FOLDER##", "##CSS_COMMON_CACHE_FOLDER##");
            $replace_arr = array($images_source_dir, $fonts_source_dir, $js_compile_cache_dir, $css_compile_cache_dir);
            $contents = str_replace($find_arr, $replace_arr, $contents);

            $fp = fopen($ci_target_appcache, 'w');
            fwrite($fp, $contents);
            fclose($fp);

            $manifest_file = 'manifest="' . $this->config->item("site_url") . $this->config->item('ADMIN_APPCACHE_FILE') . '"';
            $this->ci_local->write($this->general->getMD5EncryptString("AppCache"), "Yes", -1);
            $update_ready = 1;
        } else {
            $manifest_file = '';
            $update_ready = 0;
        }
        $appcache_status = $this->general->getAppCacheStatus();
        $logout_ready = 0;
        if (!$this->session->userdata('iAdminId') || !$this->session->userdata("isLoggedIn")) {
            $logout_ready = 1;
        }

        $render_arr = array();
        $render_arr["app_cache_status"] = $appcache_status;
        $render_arr["manifest_file"] = $manifest_file;
        $render_arr["update_ready"] = $update_ready;
        $render_arr["logout_ready"] = $logout_ready;
        $this->smarty->assign($render_arr);

        $file_name = "admin_manifest_template";
        $this->set_template($file_name);
    }

    /**
     * tbcontent method is used to load top & bottom panel information for appcache.
     */
    public function tbcontent()
    {
        $render_arr = array();
        $this->smarty->assign($render_arr);

        $file_name = "admin_tbcontent_template";
        $this->set_template($file_name);
    }

    /**
     * otp_authentication method is used to display otp screen for 2-factor login.
     */
    public function otp_authentication()
    {
        $login_name = $this->session->tempdata('tmp_username');
        $result = $this->admin_model->getAdminUser($login_name, FALSE);
        $type = $this->input->get_post('auth_type', TRUE);
        $auth_arr = explode(",", $result[0]["eAuthType"]);
        if ($type != '') {
            unset($auth_arr);
            $auth_arr[0] = $type;
        }

        $company_name = $this->config->item('COMPANY_NAME');
        if ($login_name == "") {
            $this->session->set_flashdata('failure', $this->general->processMessageLabel("GENERIC_SESSION_EXPIRED_PLEASE_LOGIN_AGAIN"));
            redirect($this->config->item('admin_url'));
        } else {

            $data['placeholder'] = $this->general->processMessageLabel('GENERIC_ENTER_OTP');
            $data['username'] = $login_name;
            $data['login_url'] = $this->config->item("admin_url");
            $data['authentication'] = $this->config->item("admin_url") . $this->mod_enc_url['otp_verification'];
            $data['try_another_way_url'] = $this->config->item("admin_url") . $this->mod_enc_url['try_another_way'];

            if (in_array('Google', $auth_arr)) {
                require_once($this->config->item('third_party') . 'google_lib/GoogleAuthenticator.php');
                $googleAuthenticator = new GoogleAuthenticator();

                $google_auth_code = $result[0]['vAuthCode'];
                $data['auth_type'] = "Google";
                $data['title'] = $this->general->processMessageLabel('GENERIC_ENTER_VERIFICATION_CODE_SENT_FROM_GOOGLE_AUTHENTICATOR_APPLICATION');
                $data['placeholder'] = $this->general->processMessageLabel('GENERIC_ENTER_SECURITY_CODE');
            } else if (in_array('Email', $auth_arr)) {
                $data['auth_type'] = "Email";
                $data['title'] = $this->general->processMessageLabel('GENERIC_ENTER_VERIFICATION_CODE_SENT_TO_REGISTERED_EMAIL');
                $data['resend_otp_url'] = $this->config->item("admin_url") . $this->mod_enc_url['resend_otp'];
                if ($type == 'Email') {
                    $this->send_otp("Email", $result);
                }
            } else if (in_array('SMS', $auth_arr)) {
                $data['auth_type'] = "SMS";
                $data['title'] = $this->general->processMessageLabel('GENERIC_ENTER_VERIFICATION_CODE_SENT_TO_REGISTERED_MOBILE');
                $data['resend_otp_url'] = $this->config->item("admin_url") . $this->mod_enc_url['resend_otp'];
                if ($type == 'SMS') {
                    $this->send_otp("SMS", $result);
                }
            }
        }
        $this->loadView("otp_authentication", $data);
        $file_name = "admin_login_template";
        $this->set_template($file_name);
    }

    /**
     * OTP verification method is used to verify Google/email/sms otp for 2-factor authentication.
     */
    public function otp_verification()
    {
        $code = $this->input->get_post('2fa_code', TRUE);
        $auth_type = $this->input->get_post('auth_type', TRUE);

        $dont_ask_me = $this->input->get_post('dont_ask_again', TRUE);
        $user = $this->session->tempdata('tmp_username');
        $cookie_str = $this->general->getMD5EncryptString("DontAskMe", $user);
        if (isset($dont_ask_me) && $dont_ask_me == "Yes") {
            $dm_arr = array();
            $dm_arr['_user'] = $this->general->encryptDataMethod($user);
            $dm_arr["dont_ask_me"] = "Yes";

            $dm_arr_json = json_encode($dm_arr);
            $this->cookie->write($cookie_str, array($dm_arr_json));
        } else {
            $this->cookie->delete($cookie_str);
        }

        if ($auth_type == 'Google') {//google verification
            $login_name = $this->session->tempdata('tmp_username');
            $result = $this->admin_model->getAdminUser($login_name, FALSE);
            $secret = $result[0]['vAuthCode'];

            require_once($this->config->item('third_party') . 'google_lib/GoogleAuthenticator.php');
            $googleAuthenticator = new GoogleAuthenticator();

            $check_result = $googleAuthenticator->verifyCode($secret, $code, 2);
            if ($check_result == TRUE) {
                $extra_param = $this->save_session("googleAuth");
                redirect($this->config->item('admin_url') . $extra_param);
            } else {
                $this->session->set_flashdata('failure', $this->general->processMessageLabel("GENERIC_YOU_HAVE_ENTERED_AN_INCORRECT_CODE"));
                redirect($this->config->item('admin_url') . $this->mod_enc_url['otp_authentication']);
            }
        } else {//email & SMS verification
            $secert = $this->session->tempdata('tmp_otp');
            if ($code != "" && $code == $secert) {
                $extra_param = $this->save_session("emailAuth");
                redirect($this->config->item('admin_url') . $extra_param);
            } else {
                $this->session->set_flashdata('failure', $this->general->processMessageLabel("GENERIC_YOU_HAVE_ENTERED_AN_INCORRECT_OTP"));
                redirect($this->config->item('admin_url') . $this->mod_enc_url['otp_authentication']);
            }
        }
    }

    /**
     * try_another_way method is used to show available option in 2FA.
     */
    public function try_another_way()
    {
        $login_name = $this->session->tempdata('tmp_username');
        $result = $this->admin_model->getAdminUser($login_name, FALSE);
        $auth_arr = explode(",", $result[0]["eAuthType"]);
        
        if (in_array('Google', $auth_arr) && $result[0]['vAuthCode'] != '') {
            $google_label = $this->general->processMessageLabel('GENERIC_GET_VERIFICATION_CODE_FROM_GOOGLE_AUTHENTICATOR_APPLICATION');
            $options['Google'] = str_replace('#GOOGLE#', '<strong>Google Authenticator</strong>', $google_label);
        }
        $email = $result[0]['vEmail'];
        if (in_array('Email', $auth_arr) && $email != '') {
            $email_end = explode('@', $email);
            $options['Email'] = $this->general->processMessageLabel('GENERIC_GET_VERIFICATION_CODE_SENT_TO_REGISTERED_EMAIL') . '<strong>' . substr($email, 0, 3) . '*****@' . $email_end[1] . '</strong>';
        }
        $phone = $result[0]['vPhonenumber'];
        if (in_array('SMS', $auth_arr) && $phone != '') {
            $options['SMS'] = $this->general->processMessageLabel('GENERIC_GET_VERIFICATION_CODE_SENT_TO_REGISTERED_MOBILE') . '<strong>' . substr($phone, 0, 2) . '*****' . substr($phone, -3) . '</strong>';
        }

        $data['username'] = $login_name;
        $data['options'] = $options;

        $data['try_another_url'] = $this->config->item('admin_url') . $this->mod_enc_url['otp_authentication'];

        $this->loadView("try_another_way", $data);
        
        $file_name = "admin_login_template";
        $this->set_template($file_name);
    }

    /**
     * send_otp method is used to send email/sms otp for 2-factor authentication.
     */
    public function send_otp($type = '', $result = array(), $mode = '')
    {
        $login_name = $this->session->tempdata('tmp_username');
        if ($mode == "resend") {
            $result = $this->admin_model->getAdminUser($login_name, FALSE);
        }

        $numeric = range(1, 9);
        $length = count($numeric) - 1;
        $results = array();
        for ($i = 0; $i < 6;) {
            $num = $numeric[mt_rand(0, $length)];
            if (!in_array($num, $results)) {
                $results[] = $num;
                $i++;
            }
        }
        $secertcode = implode("", $results);
        $this->session->set_tempdata('tmp_otp', $secertcode, 300);
       
        if ($type == "Email") {
            $email_vars = array(
                "vEmail" => $result[0]['vEmail'],
                "vName" => $result[0]['vName'],
                "OTP_NUMBER" => $secertcode
            );
            $success = $this->general->sendMail($email_vars, 'ADMIN_LOGIN_OTP');
            $this->general->logExecutedEmails('Admin', $email_vars, $success);
            return $success;
        } elseif ($type == "SMS") {

            $sms_vars['to'] = $result[0]['vPhonenumber'];
            $sms_vars['message'] = "Use " . $secertcode . " as your login OTP on " . $this->config->item('COMPANY_NAME') . " Admin Panel. OTP is confidential.";
            $success = $this->general->sendSMSNotification($sms_vars['to'], $sms_vars);
            $this->general->logExecutedSMS('Front', $sms_vars, $success);
            return $success;
        }
    }

    /**
     * resend_otp method is used to re-send email/sms otp for 2-factor authentication.
     */
    public function resend_otp()
    {
        $type = $this->input->get_post("type", TRUE);
        $this->send_otp($type, "", "resend");
        $this->session->set_flashdata('success', $this->general->processMessageLabel("GENERIC_OTP_HAS_SENT_SUCCESSFULLY"));
        redirect($this->config->item('admin_url') . $this->mod_enc_url['otp_authentication'] . "?auth_type=" . $type);
    }

    /**
     * switch_account method is used to switch between admin accounts.
     */
    public function switch_account()
    {
        try {
            $id = $this->input->get_post('id', TRUE);
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                if (!$this->filter->checkAccessCapability("admin_login_as", TRUE)) {
                    throw new Exception($this->general->processMessageLabel("ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33"));
                }
            } else {
                $group_code = $this->session->userdata('vGroupCode');
                if (!in_array($group_code, array('admin', 'superadmin', 'subadmin'))) {
                    throw new Exception($this->general->processMessageLabel("ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33"));
                }
            }

            $result = $this->admin_model->getAdminData($id);
            if (!is_array($result[0]) || count($result[0]) == 0) {
                throw new Exception($this->general->processMessageLabel("GENERIC_LOGIN_DETAILS_NOT_FOUND"));
            }

            if ($this->session->userdata('switchAccount') != 'Yes') {
                $old_user_array = $this->session->userdata();
                $this->session->set_userdata('old_user_data', $old_user_array);
            }

            $user_array = $result[0];
            $user_array['switchAccount'] = 'Yes';
            $this->session->set_userdata($user_array);

            $login_callback = $this->config->item('login_callback');
            if ($login_callback != "" && method_exists($this->general, $login_callback)) {
                $this->general->$login_callback($user_array);
            }
            $extra_param = $this->filter->getLandingpageURL($result[0]);
            $redirect_url = $this->config->item("admin_url") . $extra_param;
        } catch (Exception $e) {
            $message = $e->getMessage();
            $this->session->set_flashdata('failure', $message);
            $redirect_url = $_SERVER['HTTP_REFERER'];
        }
        redirect($redirect_url);
    }

    /**
     * return_account method is used to reset to original session while switching.
     */
    public function return_account()
    {
        $this->session->set_userdata("switchAccount", "No");
        $old_user_array = $this->session->userdata('old_user_data');
        if (is_array($old_user_array) && count($old_user_array) > 0) {
            $this->session->set_userdata($old_user_array);
        }
        $redirect_url = $this->config->item("admin_url") . $this->mod_enc_url['user_admin_index'];
        redirect($redirect_url);
    }

    /**
     * switch_group method is used to switch between admin roles.
     */
    public function switch_group()
    {
        $this->load->model('admin_model');
        $this->load->model('group_model');
        $admin_id = $this->session->userdata('iAdminId');
        $group_id = $this->input->get_post('group_id');
        if ($group_id != '') {
            $field_arr = array("iGroupId,vGroupName,vGroupCode,eStatus");
            $data_arr = $this->group_model->getData($group_id, $field_arr);
            if (is_array($data_arr) && count($data_arr) > 0) {
                $group_id = $data_arr[0]['iGroupId'];
                $group_name = $data_arr[0]['vGroupName'];
                $group_code = $data_arr[0]['vGroupCode'];
                $group_status = $data_arr[0]['eStatus'];

                if ($this->session->userdata('switchGroup') != 'Yes') {
                    $old_group_info = array(
                        "iGroupId" => $this->session->userdata('iGroupId'),
                        "vGroupName" => $this->session->userdata('vGroupName'),
                        "vGroupCode" => $this->session->userdata('vGroupCode'),
                        "vGroupStatus" => $this->session->userdata('eStatus')
                    );
                    $this->session->set_userdata("old_group_data", $old_group_info);
                }

                $new_group_info = array(
                    "iGroupId" => $group_id,
                    "vGroupName" => $group_name,
                    "vGroupCode" => $group_code,
                    "vGroupStatus" => $group_status,
                );
                $this->session->set_userdata($new_group_info);

                $sess_switch_info = array(
                    'switchGroup' => 'Yes'
                );
                $this->session->set_userdata($sess_switch_info);
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
        $this->skip_template_view();
    }

    /**
     * setup_google_auth method is used to set QR code for google auth in edit profile.
     */
    public function setup_google_auth()
    {
        $id = $this->input->get_post('id', TRUE);
        $enc_id = $this->general->getAdminEncodeURL($id);

        $result = $this->admin_model->getAdminData($id);
        $google_auth_code = $result[0]['vAuthCode'];
        $company_name = $this->config->item('COMPANY_NAME');

        require_once($this->config->item('third_party') . 'google_lib/GoogleAuthenticator.php');
        $googleAuthenticator = new GoogleAuthenticator();

        if (empty($google_auth_code)) {
            $auth_code = $googleAuthenticator->createSecret();
            $update_arr = array(
                "vAuthCode" => $auth_code
            );
            $this->admin_model->update($update_arr, $id);
            $google_auth_code = $auth_code;
        }
        $data['id'] = $id;
        $data['enc_id'] = $enc_id;
        $data['auth_type'] = "Google";
        $data['qr_code_google_url'] = $googleAuthenticator->getQRCodeGoogleUrl($result[0]['vEmail'], $google_auth_code, $company_name);

        $step2 = $this->general->processMessageLabel("GENERIC_IN_THE_APP_SELECT_SET_UP_ACCOUNT");
        $data['step2'] = str_replace('#SET_UP_ACCOUNT#', '<strong>Set up account</strong>', $step2);

        $step3 = $this->general->processMessageLabel("GENERIC_CHOOSE_SCAN_A_BARCODE");
        $data['step3'] = str_replace('#SCAN_A_BARCODE#', '<strong>Scan a barcode</strong>', $step3);

        $main_label = $this->general->processMessageLabel("GENERIC_SCAN_BELOW_QR_CODE_BY_GOOGLE_AUTHENTICATOR_APP_ON_YOUR_MOBILE");
        $data['main_label'] = str_replace('#GOOGLE_AUTHENTICATOR#', '<strong>Google Authenticator</strong>', $main_label);

        $label = $this->general->processMessageLabel("GENERIC_GET_GOOGLE_AUTHENTICATOR_ON_YOUR_MOBILE");
        $data['label'] = str_replace('#GOOGLE_AUTHENTICATOR#', '<strong>Google Authenticator</strong>', $label);

        $this->loadView("setup_google_authenticator", $data);
    }

    /**
     * verify_google_auth method is used to verify google authentication in edit profile.
     */
    public function verify_google_auth()
    {
        try {
            $id = $this->input->get_post('enc_id', TRUE);
            $id = $this->general->getAdminDecodeURL($id);
            $code = $this->input->get_post('code', TRUE);
            if (empty($id) || empty($code)) {
                throw new Exception('Did not find id or code');
            }
            $result = $this->admin_model->getAdminData($id);
            $secret = $result[0]['vAuthCode'];

            require_once($this->config->item('third_party') . 'google_lib/GoogleAuthenticator.php');
            $googleAuthenticator = new GoogleAuthenticator();

            $check_result = $googleAuthenticator->verifyCode($secret, $code, 2);
            if ($check_result == FALSE) {
                throw new Exception($this->general->processMessageLabel("GENERIC_INVALID_SECURITY_CODE_PLEASE_ENTER_CORRECT_CODE"));
            }
            $return_arr['success'] = 1;
            $return_arr['message'] = $this->general->processMessageLabel("GENERIC_GOOGLE_AUTHENTICATION_CONFIGURED_SUCCESSFULLY");
        } catch (Exception $e) {
            $message = $e->getMessage();
            $data['error'] = $message;
            $return_arr['success'] = 0;
            $return_arr['message'] = $message;
        }

        echo json_encode($return_arr);
        $this->skip_template_view();
    }

    /**
     * setup_google_auth method is used to set up multiple groups.
     */
    public function setup_multi_groups()
    {
        $id = $this->input->get_post('id', TRUE);
        $enc_id = $this->general->getAdminEncodeURL($id);
        $result = $this->admin_model->getAdminData($id);

        $this->db->select("mag.iGroupId");
        $this->db->from("mod_admin_group AS mag");
        $this->db->where("mag.iAdminId", $id);
        $db_rec_obj = $this->db->get();
        $db_rec_arr = is_object($db_rec_obj) ? $db_rec_obj->result_array() : array();

        $fields = array('iGroupId', 'vGroupName');
        $total_groups = $this->group_model->getData("", $fields);

        foreach ($db_rec_arr as $value) {
            $checked_arr[] = $value['iGroupId'];
        }
        $checked_arr[] = $result[0]['iGroupId'];

        foreach ($total_groups as $key => $value) {
            if (in_array($value['iGroupId'], $checked_arr)) {
                $total_groups[$key]['checked'] = "Yes";
            }
            if ($value['iGroupId'] == $result[0]['iGroupId']) {
                $total_groups[$key]['disabled'] = "Yes";
            }
        }

        $data = array(
            'id' => $id,
            'enc_id' => $enc_id,
            'name' => $result[0]['vName'],
            'email' => $result[0]['vEmail'],
            'curr_group' => $result[0]['vGroupName'],
            'groups' => $total_groups
        );
        $this->loadView("setup_multiple_groups", $data);
    }

    /**
     * update_multi_groups method is used to update multiple groups.
     */
    public function update_multi_groups()
    {
        try {
            $id = $this->input->get_post('enc_id', TRUE);
            $roles = $this->input->get_post('roles', TRUE);
            $table_name = "mod_admin_group";

            if (empty($roles)) {
                $this->db->where('iAdminId', $id);
                $del_res = $this->db->delete($table_name);
                if ($del_res != true) {
                    throw new Exception($this->general->processMessageLabel("GENERIC_FAILED_IN_UPDATION_OF_MULTIPLE_GROUPS_PLEASE_TRY_AGAIN_LATER"));
                }
            } else {
                $this->db->select("mag.iGroupId, mag.iAdminGroupId");
                $this->db->from($table_name . " AS mag");
                $this->db->where("mag.iAdminId", $id);
                $db_rec_obj = $this->db->get();
                $db_rec_arr = is_object($db_rec_obj) ? $db_rec_obj->result_array() : array();

                foreach ($db_rec_arr as $value) {
                    $db_arr[$value['iAdminGroupId']] = $value['iGroupId'];
                }

                $ins_arr = array_diff($roles, $db_arr);
                $del_arr = array_flip(array_diff($db_arr, $roles));

                if (empty($db_arr)) {
                    $ins_arr = $roles;
                }
                foreach ($ins_arr as $value) {
                    $insert_arr[] = array(
                        'iAdminId' => $id,
                        'iGroupId' => $value,
                    );
                }

                $this->db->insert_batch($table_name, $insert_arr);
                $ins_id = $this->db->insert_id();

                $this->db->where_in('iAdminGroupId', $del_arr);
                $del_res = $this->db->delete($table_name);

                if ($ins_id == 0 && !empty($insert_arr)) {
                    throw new Exception($this->general->processMessageLabel("GENERIC_FAILED_IN_UPDATION_OF_MULTIPLE_GROUPS_PLEASE_TRY_AGAIN_LATER"));
                }
                if ($del_res != true && !empty($del_arr)) {
                    throw new Exception($this->general->processMessageLabel("GENERIC_FAILED_IN_UPDATION_OF_MULTIPLE_GROUPS_PLEASE_TRY_AGAIN_LATER"));
                }
            }
            $return_arr['success'] = 1;
            $return_arr['message'] = $this->general->processMessageLabel("GENERIC_MULTIPLE_ROLES_UPDATED_SUCCESSFULLY");
        } catch (Exception $e) {
            $message = $e->getMessage();
            $return_arr['success'] = 0;
            $return_arr['message'] = $message;
        }
        echo json_encode($return_arr);
        $this->skip_template_view();
    }
    
    /**
     * send messages to users in grid view in admin
     */
    public function send_message() 
    {
        $date = date('y-m-d H:i:s');

        $admin_id = $this->input->get_post('admin_id', TRUE);
        $message = $this->input->get_post('message', TRUE);
        
        $insert_arr = array(
            'iAdminId' => $admin_id,
            'tMessage' => $message,
            'iSentBy' => $this->session->userdata("iAdminId"),
            'eIsRead' => 'No',
            'dAddedDate' => $date,
            'eStatus' => 'Pending',
        );
        $res = $this->db->insert('mod_admin_notifications', $insert_arr);
        
        if($res){
            $ret_arr['message'] = "Message sent successfully";
            $ret_arr['success'] = 1;
        }else{
            $ret_arr['message'] = "Message sending failed. Please try after sometime";
            $ret_arr['success'] = 0;
        }
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }
}
