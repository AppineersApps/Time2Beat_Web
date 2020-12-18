<?php

   
/**
 * Description of Resend Otp Extended Controller
 * 
 * @module Extended Resend Otp
 * 
 * @class Cit_Resend_otp.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_Resend_otp.php
 * 
 * @author CIT Dev Team
 * 
 * @date 18.09.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Resend_otp extends Resend_otp {
        public function __construct()
{
    parent::__construct();
}
public function prepareResetPasswordKey($input_params=array()){
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
    $return_arr['reset_key'] = $reset_key;
    return $return_arr;
    
}
public function formatForgotPhoneResponse($input_params=array()){
   $return_arr[0]['otp_final']=$input_params['otp'];
   $return_arr[0]['reset_key_final']=$input_params['reset_key'];
   return $return_arr;
    
}
}
