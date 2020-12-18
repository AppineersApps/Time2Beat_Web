<?php

   
/**
 * Description of User Sign Up Email Extended Controller
 * 
 * @module Extended User Sign Up Email
 * 
 * @class Cit_User_sign_up_email.php
 * 
 * @path application\webservice\user\controllers\Cit_User_sign_up_email.php
 * 
 * @author CIT Dev Team
 * 
 * @date 06.09.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_User_sign_up_email extends User_sign_up_email {
        public function __construct()
{
    parent::__construct();
}
public function generate_confirmation_link(&$input_params=array()){
    $data = md5(time());
	$password = $input_params['user_password'];
    $encrypt_type = "bcrypt";
    $encrypt_password = $this->general->encryptDataMethod($password, $encrypt_type);
    $input_params['user_password'] = $encrypt_password;
   
	$return_arr[0]['email_confirmation_code'] = $data;
	$return_arr[0]['email_confirmation_link'] = $this->config->item('site_url').'confirmation?code='.$data;
	
	return $return_arr;
	
    
}
}
