<?php

   
/**
 * Description of Reset Password Confirmation Extended Controller
 * 
 * @module Extended Reset Password Confirmation
 * 
 * @class Cit_Reset_password_confirmation.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_Reset_password_confirmation.php
 * 
 * @author CIT Dev Team
 * 
 * @date 06.02.2020
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Reset_password_confirmation extends Reset_password_confirmation {
        public function __construct()
{
    parent::__construct();
    $this->load->model('Cit_Reset_password_confirmation_model');
}
public function checkUserExistsWithResetKey($input_params=array()){
    $reset_key=$input_params['reset_key'];
    if(empty($reset_key)){
        $return_arr['status']=0;
        $return_arr['message']="Your reset password link is expired. Please try forgot password again.";
       
    }else{
        $reset_code=$this->Cit_Reset_password_confirmation_model->getResetPasswordKey($reset_key);
       
      if($reset_key==$reset_code['vResetPasswordCode']){
            $rsp_dec      =base64_decode($reset_key);
            $rsp_arr      =explode('&',$rsp_dec);
            $rsp_time    =$rsp_arr[1];
            $expiration_hrs=$this->config->item('RESET_PASSWORD_EXPIRATION_TIME');
            $expiration_time = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." +".$expiration_hrs. "hour"));
            $datetime1       = new DateTime(date('Y-m-d H:i:s',$rsp_time ));
            $datetime2       = new DateTime($expiration_time);
            $interval        = $datetime1->diff($datetime2);
            $elapsed         = $interval->format('%H');
            if($elapsed <= 01){
                $return_arr['status']=1;
                $return_arr['message']="success";
                 
            }else{
                $return_arr['status']=0;
                $return_arr['message']="Your reset password link is expired. Please try forgot password again.";
               
            }
        }else{
                $return_arr['status']=0;
                $return_arr['message']="Your reset password link is expired. Please try forgot password again.";
               
        }
    }
  return $return_arr;
}
}
