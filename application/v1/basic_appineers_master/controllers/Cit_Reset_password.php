<?php

   
/**
 * Description of Reset Password Extended Controller
 * 
 * @module Extended Reset Password
 * 
 * @class Cit_Reset_password.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_Reset_password.php
 * 
 * @author CIT Dev Team
 * 
 * @date 16.09.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Reset_password extends Reset_password {
        public function __construct()
{
    parent::__construct();
    $this->load->model('Cit_Reset_password_model');
}
public function checkUserExistsWithResetKey($input_params=array()){
    if(empty($input_params['reset_key'])){
        $return_arr['status']=0;
        $return_arr['message']="Your reset password link is expired. Please try forgot password again.";
        $return_arr['user_id']='';
    }else{
        $reset_key=$input_params['reset_key'];
        $user_data=$this->Cit_Reset_password_model->getResetPasswordKey($reset_key);
        $user_id=$user_data[0]['iUserId'];
      if($reset_key==$user_data[0]['vResetPasswordCode']){
            $rsp_dec      =base64_decode($reset_code);
            $rsp_arr      =explode('&',$reset_code);
            $rsp_time    =$rsp_arr[1];
            $expiration_hrs=$this->config->item('RESET_PASSWORD_EXPIRATION_TIME');
            $expiration_time = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." +".$expiration_hrs. "hour"));
            $datetime1       = new DateTime($rsp_time);
            $datetime2       = new DateTime($expiration_time);
            $interval        = $datetime1->diff($datetime2);
            $elapsed         = $interval->format('%H');
            if($elapsed <= 01){
                $return_arr['status']=1;
                $return_arr['message']="success";
                 $return_arr['user_id']=$user_id;
             
            }else{
                $return_arr['status']=0;
                $return_arr['message']="Your reset password link is expired. Please try forgot password again.";
                $return_arr['user_id']='';
            }
        }else{
                $return_arr['status']=0;
                $return_arr['message']="Your reset password link is expired. Please try forgot password again.";
                $return_arr['user_id']='';
        }
    }
    return $return_arr;
}
}
