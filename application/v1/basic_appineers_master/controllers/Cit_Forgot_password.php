<?php

   
/**
 * Description of Forgot Password Extended Controller
 * 
 * @module Extended Forgot Password
 * 
 * @class Cit_Forgot_password.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_Forgot_password.php
 * 
 * @author CIT Dev Team
 * 
 * @date 16.09.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Forgot_password extends Forgot_password {
        public function __construct()
{
    parent::__construct();
}
public function generateLink($input='',$arr = array()){
    $length = 6;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters_length = strlen($characters);
    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
	$random_string .= $characters[rand(0, $characters_length - 1)];
    }
    $time                 =time();
    $date=  date('Y-m-d',$time);
    $reset_key="$random_string&$time";
    $reset_key=base64_encode($reset_key);
    $return_arr[0]['reset_key'] = $reset_key;
	$reset_password_link = $this->general->generateResetPasswordLink($input_params['email'],$reset_key);
    $return_arr[0]['reset_link'] = $reset_password_link;
    return $return_arr;
	
}
}
