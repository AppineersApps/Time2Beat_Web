<?php

   
/**
 * Description of Social Sign Up Extended Controller
 * 
 * @module Extended Social Sign Up
 * 
 * @class Cit_Social_sign_up.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_Social_sign_up.php
 * 
 * @author CIT Dev Team
 * 
 * @date 10.02.2020
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Social_sign_up extends Social_sign_up {
        public function __construct()
{
    parent::__construct();
}
public function checkUniqueUser($input_params=array()){
   $return_arr['message']='';
   $return_arr['status']='1';
   //check social signup unique user
    $this->db->select('eSocialLoginType,vSocialLoginId');
    $this->db->from('users');
    $this->db->where('eSocialLoginType',$input_params['social_login_type']);
    $this->db->where('vSocialLoginId',$input_params['social_login_id']);
    $social_data=$this->db->get()->result_array();
    if(!empty($social_data)){
         $return_arr['message']="This social account already registered, please try using different account.";
         $return_arr['status'] = "0";
         return  $return_arr;
    }
    //check unique email
    if(!empty($input_params['email'])){
        $this->db->select('vEmail');
        $this->db->from('users');
        $this->db->where('vEmail',$input_params['email']);
        $email_data=$this->db->get()->result_array();
        if($email_data[0]['vEmail']==$input_params['email']){
           $return_arr['message']="This email is already registered, please try using different email.";
           $return_arr['status'] = "0";
           return  $return_arr;
        }
    }
   //check unique mobile number
    if(!empty($input_params['mobile_number'])){
      $this->db->select('vMobileNo');
      $this->db->from('users');
      $this->db->where('vMobileNo',$input_params['mobile_number']);
      $mobile_number_data=$this->db->get()->result_array();
     if($mobile_number_data[0]['vMobileNo']==$input_params['mobile_number']){
         $return_arr['message']="This mobile number already registered, please try using different mobile number.";
         $return_arr['status'] = "0";
         return  $return_arr;
      }
     
    }
    
    //check unique username
  if(!empty($input_params['user_name'])){
      $this->db->select('vUserName');
      $this->db->from('users');
      $this->db->where('vUserName',$input_params['user_name']);
      $user_name_data=$this->db->get()->result_array();
      if($user_name_data[0]['vUserName']==$input_params['user_name']){
          $return_arr['message']="This username already registered, please try using different username.";
          $return_arr['status'] = "0";
          return  $return_arr;
      }
    }
   return  $return_arr; 
}
public function getTermsConditionVersion(){
    //get terms and conditions version
    $this->db->select('vVersion');
    $this->db->from('mod_page_settings');
    $this->db->where_in('vPageCode',termsconditions);
    $termsconditions_code_version=$this->db->get()->row_array();
    return $termsconditions_code_version['vVersion'];
   
    
}
public function getPrivacyPolicyVersion(){
   
    //get privacy policy version
    $this->db->select('vVersion');
    $this->db->from('mod_page_settings');
    $this->db->where_in('vPageCode',privacypolicy);
    $privacypolicy_code_version=$this->db->get()->row_array();
    return $privacypolicy_code_version['vVersion'];
    
}
}
