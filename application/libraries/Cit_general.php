<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Extended General Library
 *
 * @category libraries
 *
 * @package libraries
 *
 * @module General
 *
 * @class Cit_general.php
 *
 * @path application\libraries\Cit_general.php
 *
 * @version 4.0
 *
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
include_once (APPPATH.'libraries'.DS.'General.php');

class Cit_general extends General
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Code will be generated dynamically
     * Please do not write or change the content below this line
     * Five hashes must be there on either side of string.
     */
    #####GENERATED_CUSTOM_FUNCTION_START#####


        
    public function getCustomerResetPasswordLink($input_params = array()) {
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

$reset_param = base64_encode($input_params['mc_customer_id'] . "@@" . $reset_code . "@@" . time());
$reset_url = $this->CI->config->item("site_url") . "reset-password.html?rsp=" . $reset_param;

$ret_arr = array();
$ret_arr[0]['reset_link'] = $reset_url;
$ret_arr[0]['reset_code'] = $reset_code;
$ret_arr[0]['full_name'] = $input_params['mc_first_name']." ".$input_params['mc_last_name'];

return $ret_arr;
                        } 
    
    public function getCustomerVerifyEmailLink($input_params = array()) {
                            $random_string = $this->CI->general->getRandomNumber(6);
	
$email = $input_params['email'];
$enc_str = urlencode(base64_encode($random_string.'@@'.$email));
$verify_link = $this->CI->config->item('site_url').'verify-email.html?e='.$enc_str;

$full_name = $input_params['first_name'].' '.$input_params['last_name'];

$return_arr[0]['verify_code'] = $random_string;
$return_arr[0]['verify_link'] = $verify_link;
$return_arr[0]['full_name'] = $full_name;
            
return $return_arr;
                        } 
    
    public function sendAdminRegistrationEmail($mode = '', $id = '', $parID = '') {
                            try
        {
            $success = 1;

            //Send welcome email on admin registration
            if ($mode == "Add")
            {
                $email_vars = array(
                    "vEmail" => $this->CI->input->get_post("ma_email",
                    TRUE),
                    "vName" => $this->CI->input->get_post("ma_name",
                    TRUE),
                    "vUserEmail" => $this->CI->input->get_post("ma_email",
                    TRUE),
                    "vUsername" => $this->CI->input->get_post("ma_user_name",
                    TRUE),
                );
                $success = $this->CI->general->sendMail($email_vars, 'ADMIN_REGISTER');
                if (!$success)
                {
                    $success = 2;
                    $message = $this->CI->lang->line("GENERIC_FAILURE_WHILE_SENDING_REGISTRATION_EMAIL");
                }
            }

            //Google 2-Factor Authentication secret code generation
            if ($this->CI->input->get_post("ma_auth_type", TRUE) == "Google")
            {
                if (empty($this->CI->input->get_post("ma_auth_code", TRUE)))
                {

                    require_once ($this->CI->config->item('third_party').'google_lib/GoogleAuthenticator.php');
                    $googleAuthenticator = new GoogleAuthenticator();

                    $update_arr = array(
                        "vAuthCode" => $googleAuthenticator->createSecret()
                    );
                    $this->CI->db->where("iAdminId", $id);
                    $this->CI->db->update("mod_admin", $update_arr);
                }
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $response = array();
        $response['success'] = $success;
        $response['message'] = $message;

        return $response;
                        } 
    
    public function prepareAdminLoginAsBtn($value = '', $id = '', $data = array()) {
                            $switch_account_url = $this->CI->general->getCustomEncryptURL('switch_account', TRUE);

        $login_as_url = $this->CI->config->item("admin_url").$switch_account_url['switch_account']."?id=".$this->CI->general->getAdminEncodeURL($data['iAdminId']);

        $action_html = "";
        if ($data['iAdminId'] != $this->CI->session->userdata('iAdminId') && $data['ma_user_name'] != $this->CI->config->item("ADMIN_USER_NAME"))
        {

            $action_html = '<a hijacked="yes" class="btn" href="'.$login_as_url.'" title="'.$this->CI->lang->line("GENERIC_LOGIN_AS").'">'.$this->CI->lang->line("GENERIC_LOGIN_AS").'</a>';
        }

        return $action_html;
                        } 
    
    public function verifyCustomerPassword ($input_params = array()) {
    $old_password = $input_params['old_password'];

$existing_pwd = $input_params['mc_password'];

$encrypt_type = "bcrypt";

$password_res = $this->CI->general->verifyEncryptData($old_password, $existing_pwd, $encrypt_type);
            
if($password_res)
{
	$return_arr[0]['is_matched'] = 1;
} 
else 
{
	$return_arr[0]['is_matched'] = 0;
}

return $return_arr;
} 
    
    public function encryptCustomerPassword ($value = '', $data_arr = array()) {
    $password = $value;

$encrypt_type = "bcrypt";

$encrypt_password = $this->CI->general->encryptDataMethod($password, $encrypt_type);
            
return $encrypt_password;
} 
    
    public function adminDataBeforeLoad($data_recs, $grid_fields, $list_config) {
                            $extra_fields_key = array_search('extra_fields', $grid_fields);
        
$this->CI->load->library("ci_theme");
$theme_arr = $this->CI->ci_theme->themeDefaultColors();
$theme_name = $this->CI->config->item("ADMIN_THEME_SETTINGS");
$theme_name = explode("@", $theme_name);
$theme_color = $theme_name[1];
foreach($theme_arr['cit'] as $val){
    if($val['file'] == $theme_color){
        $color_code = $val['color'];
    }
}

foreach ($data_recs['rows'] as $key => $val)
{
    $admin_id = $data_recs['data'][$key]['iAdminId'];

    $this->CI->db->from('mod_admin_notifications');
    $this->CI->db->where('eIsRead', 'No');
    $this->CI->db->where('iAdminId', $admin_id);
    $messages = $this->CI->db->get();
    $count = is_object($messages) ? $messages->num_rows() : 0;

    $extra_fields = array(
        "edit" => array(
            "icon" => "fa fa-pencil",
            "url" => "",
        ),
        "message" => array(
            "icon" => "fa fa-envelope",
            "url" => "",
        ),
        "notification" => array(
            "icon" => "fa fa-bell",
            "url" => "",
            "count" => $count,
        ),
        "login_as" => array(
            "icon" => "fa fa-sign-in",
            "url" => "",
        ),
    );
    $extra_fields['curr_user'] = '0';
    if($data_recs['data'][$key]['iAdminId'] == $this->CI->session->userdata('iAdminId')){
        $extra_fields['curr_user'] = '1';
        $extra_fields['notification_class'] = 'disabled';
        $extra_fields['login_as_class'] = 'disabled';
    }
    if($data_recs['data'][$key]['ma_user_name'] == $this->CI->config->item("ADMIN_USER_NAME")){
        $extra_fields['login_as_class'] = 'disabled';
        $listing_url = $this->getCustomEncryptURL('admin_notifications_list', TRUE);
        $extra_fields['notification_url'] = $this->CI->config->item("admin_url") . "#" . $listing_url['admin_notifications_list'];
    }
    
    $extra_fields['id'] = $data_recs['data'][$key]['iAdminId'];
    $extra_fields['theme_color'] = $color_code;
    $data_recs['rows'][$key]['cell'][$extra_fields_key] = $extra_fields;
}

return $data_recs;
                        } 
    
    public function prepareEmailVerificationCode ($input_params = array()) {
    $email                =$input_params['email'];
$time                 =time();
$verification_code    ="$email&$time";
$verification_code    =base64_encode($verification_code);
$return_arr[0]['email_confirmation_code']=$verification_code;
$return_arr[0]['email_confirmation_link'] = $this->CI->config->item('site_url').'confirmation?code='.$verification_code;

return $return_arr;
} 
    
    public function testblock ($input_params = array()) {
    pr($input_params);die;
} 
    
    public function generateAuthToken (&$input_params = array()) {
    $ret = array();

$ret[0]['auth_token'] = $this->encryptDataMethod(uniqid().time().rand(56));


return $ret;
} 
    
    public function add_query_format_output (&$input_params = array()) {
    if(!empty($input_params['query_images']))
{
    $image_array = array();
    foreach ($input_params['query_images'] as $key => $image)
    {
        array_push($image_array,$image['uqi_query_image']);
    }
    
    $input_params['get_query_details'][0]['images'] = $image_array;
}
} 
    
    public function generateResetPasswordLink ($user_email, $reset_key) {
    $site_url = $this->CI->config->item("site_url");
$verification_link = $site_url."resetpassword?code=". $reset_key;
return $verification_link;
} 
    
    public function generateOtp ($input_params = array()) {
    $length = 4;
$characters = '0123456789';
$characters_length = strlen($characters);
$random_string = '';
for ($i = 0; $i < $length; $i++) {
	$otp .= $characters[rand(0, $characters_length - 1)];
}
$return_array['otp']=$otp;
return $return_array;
} 
    
    public function prepareSMS ($input_params = array()) {
    $notification_text=$input_params['get_template'][0]['sms_text'];
$company_name=$this->CI->config->item('COMPANY_NAME');
switch ($input_params['template_code'])
        {
            case 'signup_otp':
              	$notification_text = str_replace("| OTP |", $input_params['otp'], $notification_text);
              	$notification_text = str_replace("| APPLICATION_NAME |",$company_name, $notification_text);
                $return_arr[0]['message'] =  $notification_text;
                $return_arr[0]['activity'] =  $input_params['template_code'];
                break;
			case 'forgot_password_otp':
				$notification_text = str_replace("| USERNAME |", $input_params['user_name'], $notification_text);
				$notification_text = str_replace("| OTP |", $input_params['otp'], $notification_text);
				$notification_text = str_replace("| APPLICATION_NAME |", $company_name, $notification_text);
			    $return_arr[0]['message'] =  $notification_text;
                $return_arr[0]['activity'] =  $input_params['template_code'];
				break;
			case 'change_mobile_number_otp';
				$notification_text = str_replace("| USERNAMER |", $input_params['user_name'], $notification_text);
				$notification_text = str_replace("| OTP |", $input_params['otp'], $notification_text);
                $return_arr[0]['message'] =  $notification_text;
                $return_arr[0]['activity'] =  $input_params['template_code'];
				break;
			
        }
return $return_arr;
} 
    
    public function addCountryCode ($input_params = array()) {
    $number=$this->CI->config->item('COUNTRY_CODE').$input_params['mobile_number'];
$return_arr=array();
$return_arr[0]['number']=$number;
return $return_arr;
} 
    
    public function format_email (&$input_params = array()) {
    $input_params['email']=strtolower($input_params['email']);
} 
    #####GENERATED_CUSTOM_FUNCTION_END#####

}

/* End of file Cit_General.php */
/* Location: ./application/libraries/Cit_general.php */
