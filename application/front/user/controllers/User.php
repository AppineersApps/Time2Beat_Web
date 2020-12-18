<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of User Controller
 *
 * @category front
 *            
 * @package user
 * 
 * @subpackage controllers
 * 
 * @module User
 * 
 * @class User.php
 * 
 * @path application\front\user\controllers\User.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class User extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    /**
     * index method is used to define home page content.
     */
    public function index()
    {
        $view_file = "welcome_message";
        $this->loadView($view_file);
    }

    /**
     * dashboard method is used to define dashboard data after user logged in.
     */
    public function dashboard()
    {
        
    }

    /**
     * login method is used to display login page.
     */
    public function login()
    {
        if ($this->session->userdata('iUserId')) {
            redirect($this->config->item("site_url") . 'dashboard.html');
        }
        $view_file = "login";
        $this->loadView($view_file);
    }

    /**
     * login_action method is used to process login page for authentification.
     */
    public function login_action()
    {
        $username = $this->input->get_post('username');
        $password = $this->input->get_post('password');

        $cookie_data = $this->cookie->read('remember_me');

        if (is_array($cookie_data) && $cookie_data['username'] != '') {
            $username = $cookie_data['username'];
            $password = $cookie_data['password'];
        }

        $stay_signed = $this->input->get_post('remember_me');
        $ajaxcall = $this->input->get_post('ajaxcall');
        $skip_2fa = false;
        try {
            $record = $this->user_model->authenticate($username, FALSE);
            if (!is_array($record) || count($record) == 0) {
                
            }
            if ($record[0]['eStatus'] != 'Active') {
                throw new Exception($this->lang->line("FRONT_TEMPORARILY_INACTIVATED"));
            }
            $encrypt_password = $record[0]['vPassword'];
            $encrypt_type = "bcrypt";

            $password_verification = $this->general->verifyEncryptData($password, $encrypt_password, $encrypt_type);
            if ($password_verification === FALSE) {
                throw new Exception($this->lang->line("FRONT_WRONG_USERNAME_PASSWORD"));
            }

            $cookie_prefix = $this->config->item("sess_cookie_name");
            if (strtolower($stay_signed) == 'yes') {
                $cookie_data = array(
                    $cookie_prefix . '_username' => $username,
                    $cookie_prefix . '_password' => $password
                );
                $this->cookie->write('remember_me', $cookie_data);
            } else {
                $this->cookie->delete('remember_me');
            }
            $dm_data = $this->cookie->read($this->general->getMD5EncryptString("DontAskMe", $record[0]["vUserName"]));
            $dm_data_arr = json_decode($dm_data[0], true);
            if (is_array($dm_data_arr) && count($dm_data_arr) > 0 && $dm_data_arr['dont_ask_me'] == "Yes") {
                $skip_2fa = true;
            }
            
            if ($record[0]["eAuthType"] != '' && $skip_2fa == false) {
                $this->session->set_tempdata('tmp_email', $record[0]["vEmail"], 300);
                $this->session->set_tempdata('tmp_username', $record[0]["vUserName"], 300);
                $this->session->set_tempdata('tmp_id', $record[0]["iCustomerId"], 300);
                redirect($this->config->item("site_url") . 'two-factor.html');
            } else {
                $this->save_session($username);
                $var_msg = $this->lang->line("FRONT_WELCOME") . " " . $this->session->userdata("vFirstName") . " " . $this->session->userdata("vLastName") . ", " . $this->lang->line("FRONT_SUCCESSFUL_LOGIN");
                $this->session->set_flashdata('success', $var_msg);
                $this->smarty->assign('alldata', $this->session->all_userdata());
                redirect($this->config->item("site_url") . 'dashboard.html');
            }
        } catch (Exception $e) {
            $var_msg = $e->getMessage();
            $this->session->set_flashdata('failure', $var_msg);
            redirect($this->config->item("site_url") . 'login.html');
        }
    }

    /**
     * logout method is used to log out the current login user.
     */
    public function logout()
    {
        $this->load->model('tools/loghistory');
        $log_id = $this->session->userdata('iLogId');
        $this->loghistory->updateLogoutUser($log_id);
        $sess_cookie_name = $this->config->item("sess_cookie_name");
        $cookiedata = array(
            $sess_cookie_name . 'username' => '',
            $sess_cookie_name . 'password' => ''
        );

        $this->cookie->write('userarray', $cookiedata);
        $this->session->sess_destroy();

        redirect($this->config->item("site_url") . 'index.html');
    }

    /**
     * register method is used to display register page.
     */
    public function register()
    {
        if ($this->session->userdata('iUserId')) {
            redirect($this->config->item("site_url") . 'dashboard.html');
        }
        $data['heading'] = "Register";
        $data['type'] = "register";
        $data['user'] = array('firstname' => '', 'lastname' => '', 'email' => '');
        $this->loadView('register', $data);
    }

    /**
     * register_action method is used to process register page for adding customer record.
     */
    public function register_action()
    {

        $user_arr = array();
        $user_arr['vFirstName'] = $this->input->get_post('first_name');
        $user_arr['vLastName'] = $this->input->get_post('last_name');
        $user_arr['vEmail'] = $this->input->get_post('email');
        $user_arr['vUserName'] = $this->input->get_post('username');
        $password = $this->input->get_post('password');
        $encrypt_type = "bcrypt";
        $encrypt_password = $this->general->encryptDataMethod($password, $encrypt_type);
        $user_arr['vPassword'] = $encrypt_password;
        $user_arr['dtRegisteredDate'] = date('Y-m-d H:i:s');
        $user_arr['vVerificationCode'] = $this->random_code();

        $user_id = $this->user_model->insert($user_arr);

        if (!$user_id) {
            $this->session->set_flashdata('failure', $this->lang->line("FRONT_ERROR_REGISTER"));
        } else {
            $verify_param = base64_encode($user_arr['vVerificationCode'] . "@@" . $user_arr['vEmail']);
            $verify_email = $this->config->item("site_url") . "verify-email.html?e=" . $verify_param;

            $email_vars = array();
            $email_vars['vName'] = $user_arr['vFirstName'] . " " . $user_arr['vLastName'];
            $email_vars['vUsername'] = $user_arr['vUserName'];
            $email_vars['vEmail'] = $user_arr['vEmail'];
            $email_vars['vUserEmail'] = $user_arr['vEmail'];
            $email_vars['VERIFY_EMAIL'] = $verify_email;
            $response = $this->general->sendMail($email_vars, 'USER_REGISTER');
            $this->general->logExecutedEmails('Front', $email_vars, $response);
            $this->session->set_flashdata('success', $this->lang->line("FRONT_SUCCESSFUL_REGISTER"));
        }

        redirect($this->config->item("site_url") . 'index.html');
    }

    /**
     * check_user_email method is used to check wether username or email already exist in data base.
     */
    public function check_user_email()
    {
        $user_arr["vEmail"] = $this->input->get_post('email');
        $user_arr["vUserName"] = $this->input->get_post('username');

        if (isset($user_arr["vEmail"])) {
            $status = $this->user_model->checkUserExists('vEmail', $user_arr);
        }
        if (isset($user_arr["vUserName"])) {
            $status = $this->user_model->checkUserExists('vUserName', $user_arr);
        }
        if (!$status) {
            echo "false";
        } else {
            echo "true";
        }
        $this->skip_template_view();
    }

    /**
     * forgotpassword method is used to display forgot password page.
     */
    public function forgotpassword()
    {
        $view_file = "forgotpassword";
        $this->loadView($view_file);
    }

    /**
     * forgotpassword_action method is used to send forgot password action.
     */
    public function forgotpassword_action()
    {
        $user_name = $this->input->get_post('user_name');
        $where_cond = "(" . $this->db->protect("vUserName") . " = " . $this->db->escape($user_name) . " OR " . $this->db->protect("vEmail") . " = " . $this->db->escape($user_name) . ")";
        $user_details = $this->user_model->getData($where_cond);
        $this->load->model('tools/emailer');
        if (is_array($user_details) && count($user_details) > 0) {

            $reset_code = $this->random_code();

            $reset_param = base64_encode($user_details[0]['iCustomerId'] . "@@" . $reset_code . "@@" . time());
            $reset_url = $this->config->item("site_url") . "reset-password.html?rsp=" . $reset_param;

            $email_vars = array();
            $email_vars['RESET_CODE'] = $reset_code;
            $email_vars['vName'] = $user_details[0]['vFirstName'] . " " . $user_details[0]['vLastName'];
            $email_vars['vEmail'] = $user_details[0]['vEmail'];
            $email_vars['RESET_URL'] = $reset_url;

            $response = $this->general->sendMail($email_vars, 'USER_RESET_PASSWORD');
            $this->general->logExecutedEmails('Front', $email_vars, $response);
            if ($response) {
                $this->session->set_flashdata('success', $this->lang->line("FRONT_FORGOT_PASSWORD_EMAIL_SUCCESS"));
                redirect($this->config->item("site_url") . 'login.html');
            } else {
                $this->session->set_flashdata('failure', $this->lang->line("FRONT_FORGOT_PASSWORD_EMAIL_FAILURE"));
                redirect($this->config->item("site_url") . 'forgot-password.html');
            }
        } else {
            $this->session->set_flashdata('failure', $this->lang->line("FRONT_UNABLE_TO_FIND_USERNAME_EMAIL"));
        }
    }

    public function resetpassword()
    {
        $rsp = $this->input->get_post("rsp", TRUE);
        $rsp_dec = base64_decode($rsp, TRUE);
        $rsp_arr = explode("@@", $rsp_dec);
        $render_arr["rsp"] = $rsp;
        $render_arr["id"] = base64_encode($rsp_arr[0]);
        $render_arr["code"] = base64_encode($rsp_arr[1]);
        $render_arr["time"] = base64_encode($rsp_arr[2]);
        $this->smarty->assign($render_arr);

        $view_file = "resetpassword";
        $this->loadView($view_file);
    }

    public function resetpassword_action()
    {
        $customer_id = $this->input->get_post("userid", TRUE);
        $code = $this->input->get_post("code", TRUE);
        $time = $this->input->get_post("time", TRUE);
        $new_password = $this->input->get_post("new_password", TRUE);
        $encrypt_type = "bcrypt";
        $password = $this->general->encryptDataMethod($new_password, $encrypt_type);
        $reset_code = $this->input->get_post('reset_code');
        $rsp = $this->input->get_post("rsp", TRUE);
        try {
            $customer_id = base64_decode($customer_id);
            $time = base64_decode($time);
            $code = base64_decode($code);

            if ($code != $reset_code) {
                throw new Exception($this->lang->line("FRONT_RESET_CODE_DOESNOT_MATCH"));
            }
            $currenttime = time();
            $resettime = $this->config->item("ADMIN_RESET_PASSWORD_TIME") * 60 * 60 * 1000; //check 1sec
            $delay = $currenttime - $time;
            if ($customer_id > 0 && $delay < $resettime) {
                $update_arr = array();
                $update_arr["vPassword"] = $password;
                $res = $this->user_model->update($update_arr, $customer_id);
            } else {
                throw new Exception($this->lang->line("FRONT_RESET_PASSWORD_LINK_EXPIRED"));
            }
            if (!$res) {
                throw new Exception($this->lang->line("FRONT_PASSWORD_UPDATION_FAILED"));
            }
            $message = $this->lang->line("FRONT_PASSWORD_UPDATED_SUCCESSFULLY");
            $this->session->set_flashdata('success', $message);
            redirect($this->config->item("site_url") . 'login.html');
        } catch (Exception $e) {
            $message = $e->getMessage();
            $this->session->set_flashdata('failure', $message);
            redirect($this->config->item("site_url") . 'reset-password.html?rsp=' . $rsp);
        }
    }

    /**
     * profile method is used to display and  update customer page.
     */
    public function profile()
    {
        $user_id = $this->session->userdata('iUserId');
        if (!$user_id) {
            $this->session->set_flashdata('failure', $this->lang->line("FRONT_LOGIN_TO_UPDATE_PROFILE"));
            redirect($this->config->item("site_url") . 'login.html');
        } else {
            if ($this->input->post()) {
                $user_arr = array();
                $user_arr['vFirstName'] = $this->input->get_post('first_name');
                $user_arr['vLastName'] = $this->input->get_post('last_name');
                $authtype['google'] = $this->input->get_post('googleAuth');
                $authtype['email'] = $this->input->get_post('emailAuth');
                $authtype['sms'] = $this->input->get_post('smsAuth');
                $user_arr['eAuthType'] = implode(",", array_filter($authtype));
                $password = $this->input->get_post('password');
                if ($password != "*****" && $password != "") {
                    $encrypt_type = "bcrypt";
                    $encrypt_password = $this->general->encryptDataMethod($password, $encrypt_type);
                    $user_arr['vPassword'] = $encrypt_password;
                    $email_vars['vEmail'] = $this->input->get_post('email');
                    $email_vars['vName'] = $user_arr['vFirstName'] . " " . $user_arr['vLastName'];
                    $response = $this->general->sendMail($email_vars, 'USER_PASSWORD_CHANGED');
                    $this->general->logExecutedEmails('Front', $email_vars, $response);
                }

                $res = $this->user_model->update($user_arr, $this->input->post('userId'));

                if (!$res) {
                    $this->session->set_flashdata('failure', $this->lang->line("FRONT_ERROR_UPDATING_PROFILE"));
                } else {
                    $this->session->set_flashdata('success', $this->lang->line("FRONT_PROFILE_UPDATED_SUCCESSFULLY"));
                }
                redirect($this->config->item("site_url") . 'profile.html');
            }
            $where = $this->db->protect("iCustomerId") . " = " . $this->db->escape($user_id);
            $user = $this->user_model->getData($where);
            if (!is_array($user) || count($user) == 0) {
                $this->session->set_flashdata('failure', "User profile not found.");
                redirect($this->config->item("site_url") . 'logout.html');
            }
            $auth_arr = explode(",", $user[0]['eAuthType']);
            $google = $email = $sms = FALSE;
            if (in_array('Google', $auth_arr)) {
                $google_auth = TRUE;
            }
            if (in_array('Email', $auth_arr)) {
                $email_auth = TRUE;
            }
            if (in_array('SMS', $auth_arr)) {
                $sms_auth = TRUE;
            }
            $data['user'] = array(
                'id' => $user_id,
                'firstname' => $user[0]['vFirstName'],
                'lastname' => $user[0]['vLastName'],
                'email' => $user[0]['vEmail'],
                'username' => $user[0]['vUserName'],
                'password' => '*****',
                'google_auth' => $google_auth,
                'email_auth' => $email_auth,
                'sms_auth' => $sms_auth
            );
            $data['heading'] = "User Profile";
            $data['type'] = "profile";
        }
        $this->loadView("register", $data);
    }

    public function verifyemail()
    {
        $e = $this->input->get_post("e", TRUE);
        $e_dec = base64_decode($e, TRUE);
        $e_arr = explode("@@", $e_dec);
        $code = $e_arr[0];
        $email = $e_arr[1];
        if (is_array($e_arr)) {
            $where_cond = "(" . $this->db->protect("vEmail") . " = " . $this->db->escape($email) . " AND " . $this->db->protect("vVerificationCode") . " = " . $this->db->escape($code) . ")";
            $user_details = $this->user_model->getData($where_cond);

            if (!is_array($user_details) || count($user_details) == 0) {
                $render_arr['status'] = 0;
            } elseif ($user_details[0]['eEmailVerified'] == "Yes") {
                $render_arr['status'] = 2;
            } else {
                $customer_id = $user_details[0]['iCustomerId'];
                $update_arr['eEmailVerified'] = "Yes";
                $res = $this->user_model->update($update_arr, $customer_id);
                $render_arr['status'] = 1;
            }
        }
        $this->smarty->assign($render_arr);
        $this->loadView("verifyemail");
    }

    protected function random_code()
    {
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
        return $reset_code;
    }

    function save_session($username)
    {
        $record = $this->user_model->authenticate($username, FALSE);
        $this->session->set_userdata("iUserId", $record[0]["iCustomerId"]);
        $this->session->set_userdata("vFirstName", $record[0]["vFirstName"]);
        $this->session->set_userdata("vLastName", $record[0]["vLastName"]);
        $this->session->set_userdata("vEmail", $record[0]["vEmail"]);
        $this->session->set_userdata("vUserName", $record[0]["vUserName"]);
        $this->session->set_userdata("eStatus", $record[0]["eStatus"]);

        $session_log['iUserId'] = $record[0]['iCustomerId'];
        $session_log['eUserType'] = 'Member';
        $session_log['vIP'] = $this->input->ip_address();
        $session_log['dLoginDate'] = date('Y-m-d H:i:s', now());

        $this->load->model('tools/loghistory');
        $log_id = $this->loghistory->insert($session_log);
        $this->session->set_userdata("iLogId", $log_id);
    }

    public function twofactor()
    {
        $type = $this->input->get_post("type", TRUE);
        $username = $this->session->tempdata('tmp_username');
        $where_cond = "(" . $this->db->protect("vUserName") . " = " . $this->db->escape($username) . ")";
        $user_details = $this->user_model->getData($where_cond);
        $auth_arr = explode(",", $user_details[0]['eAuthType']);

        if ($type == '') {
            if (in_array('Google', $auth_arr)) {
                $type = 'Google';
            } else if (in_array('Email', $auth_arr)) {
                $type = 'Email';
            } else if (in_array('SMS', $auth_arr)) {
                $type = 'SMS';
            }
        }
        if ($type == 'Email') {
            $this->send_otp('Email', $user_details);
        }
        $ren_arr['type'] = $type;
        $ren_arr['username'] = $username;

        $this->smarty->assign('ren_arr', $ren_arr);
        $this->loadView('two_factor_auth');
    }

    public function tryanother()
    {
        $id = $this->session->tempdata('tmp_id');
        $user_details = $this->user_model->getData($id);
        $auth_arr = explode(",", $user_details[0]['eAuthType']);

        if (in_array('Google', $auth_arr)) {
            $options['Google'] = "Get verification code from Google Authentication App";
        }
        if (in_array('Email', $auth_arr)) {
            $email_end = explode('@', $user_details[0]['vEmail']);
            $options['Email'] = 'Get verification code sent to registered Email ' . substr($user_details[0]['vEmail'], 0, 3) . '*****@' . $email_end[1] . '</strong>';
        }
        if (in_array('SMS', $auth_arr)) {
            $phone = $user_details[0]['vPhonenumber'];
            $options['SMS'] = 'Get verification code sent to registered Mobile ' . '<strong>' . substr($phone, 0, 2) . '*****' . substr($phone, -3) . '</strong>';
        }
        $ren_arr['options'] = $options;
        $this->smarty->assign('ren_arr', $ren_arr);
        $this->loadView('try_another_way');
    }

    public function twofactor_verification()
    {
        $id = $this->session->tempdata('tmp_id');
        $username = $this->session->tempdata('tmp_username');
        $user_details = $this->user_model->getData($id);

        $code = $this->input->get_post("2faCode", TRUE);
        $auth_type = $this->input->get_post("auth_type", TRUE);
        $access = 0;
        
        $dont_ask_me = $this->input->get_post('dont_ask_again', TRUE);
        $cookie_str = $this->general->getMD5EncryptString("DontAskMe", $username);
        if (isset($dont_ask_me) && $dont_ask_me == "Yes") {
            $dm_arr = array();
            $dm_arr['_user'] = $this->general->encryptDataMethod($username);
            $dm_arr["dont_ask_me"] = "Yes";

            $dm_arr_json = json_encode($dm_arr);
            $this->cookie->write($cookie_str, array($dm_arr_json));
        } else {
            $this->cookie->delete($cookie_str);
        }
        
        if ($auth_type == 'Google') {

            $secret = $user_details[0]['vAuthCode'];
            require_once($this->config->item('third_party') . 'google_lib/GoogleAuthenticator.php');
            $googleAuthenticator = new GoogleAuthenticator();

            $check_result = $googleAuthenticator->verifyCode($secret, $code, 2);
            if ($check_result == TRUE) {
                $access = 1;
            }
        } else if ($auth_type == 'Email' || $auth_type == 'SMS') {
            $secert = $this->session->tempdata('tmp_otp');
            if ($code != "" && $code == $secert) {
                $access = 1;
            }
        }
        if ($access == 1) {
            $this->save_session($username);
            $var_msg = $this->lang->line("FRONT_WELCOME") . " " . $this->session->userdata("vFirstName") . " " . $this->session->userdata("vLastName") . ", " . $this->lang->line("FRONT_SUCCESSFUL_LOGIN");
            $this->session->set_flashdata('success', $var_msg);
            $this->smarty->assign('alldata', $this->session->all_userdata());
            redirect($this->config->item("site_url") . 'dashboard.html');
        } else {
            $this->session->set_flashdata('failure', 'Incorrect code. Please enter corrrect one...!');
            redirect($this->config->item("site_url") . 'two-factor.html');
        }
    }

    /**
     * send_otp method is used to send email/sms otp for 2-factor authentication.
     */
    public function send_otp($type = '', $result = array(), $mode = '')
    {
        $login_name = $this->session->tempdata('tmp_username');
        if ($mode == "resend") {
            $id = $this->session->tempdata('tmp_id');
            $result = $this->user_model->getData($id);
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
                "vName" => $result[0]['vUserName'],
                "OTP_NUMBER" => $secertcode
            );
            $success = $this->general->sendMail($email_vars, 'FRONT_LOGIN_OTP');
            $this->general->logExecutedEmails('Front', $email_vars, $success);
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
        $ren_arr['type'] = $type = $this->input->get_post("type", TRUE);
        $ren_arr['username'] = $this->session->tempdata('tmp_username');

        $this->send_otp($type, "", "resend");
        $this->session->set_flashdata('success', $this->general->processMessageLabel("GENERIC_OTP_HAS_SENT_SUCCESSFULLY"));

        $this->smarty->assign('ren_arr', $ren_arr);
        $this->loadView('two_factor_auth');
    }

    /**
     * setup_google_auth method is used to set QR code for google auth in edit profile.
     */
    public function setup_google_auth()
    {
        $id = $this->input->get_post('userId', TRUE);
        $enc_id = $this->general->getAdminEncodeURL($id);
        $result = $this->user_model->getData($id);
        $google_auth_code = $result[0]['vAuthCode'];
        $company_name = $this->config->item('COMPANY_NAME');
        
        require_once($this->config->item('third_party') . 'google_lib/GoogleAuthenticator.php');
        $googleAuthenticator = new GoogleAuthenticator();

        if (empty($google_auth_code)) {
            $auth_code = $googleAuthenticator->createSecret();
            $update_arr = array(
                "vAuthCode" => $auth_code
            );
            $this->user_model->update($update_arr, $id);
            $google_auth_code = $auth_code;
        }
        $data['id'] = $id;
        $data['enc_id'] = $enc_id;
        $data['auth_type'] = "Google";
        $data['qr_code_google_url'] = $googleAuthenticator->getQRCodeGoogleUrl($result[0]['vEmail'], $google_auth_code, $company_name);

        $this->loadView("setup_front_google_authenticator", $data);
    }

    /**
     * verify_google_auth method is used to verify google authentication in edit profile.
     */
    public function verify_google_auth()
    {
        try {
            $id = $this->input->get_post('id', TRUE);
            $code = $this->input->get_post('code', TRUE);
            
            if (empty($id) || empty($code)) {
                throw new Exception('Did not find id or code');
            }
            $result = $this->user_model->getData($id);
            
            $secret = $result[0]['vAuthCode'];

            require_once($this->config->item('third_party') . 'google_lib/GoogleAuthenticator.php');
            $googleAuthenticator = new GoogleAuthenticator();

            $check_result = $googleAuthenticator->verifyCode($secret, $code, 2);
            if ($check_result == FALSE) {
                throw new Exception("Invalid Code. Please enter correct one...!");
            }
            $return_arr['success'] = 1;
            $return_arr['message'] = "Successfully Verified";
        } catch (Exception $e) {
            $message = $e->getMessage();
            $data['error'] = $message;
            $return_arr['success'] = 0;
            $return_arr['message'] = $message;
        }

        echo json_encode($return_arr);
        $this->skip_template_view();
    }
}
