<?php

   
/**
 * Description of Edit Profile Extended Controller
 * 
 * @module Extended Edit Profile
 * 
 * @class Cit_Edit_profile.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_Edit_profile.php
 * 
 * @author CIT Dev Team
 * 
 * @date 25.09.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Edit_profile extends Edit_profile {
        public function __construct()
{
    parent::__construct();
}
public function checkUniqueUser($input_params=array()){
    $return_arr['message']='';
    $return_arr['status']='1';
    if(!empty($input_params['mobile_number'])){
      $this->db->select('vMobileNo');
      $this->db->from('users');
      $this->db->where('vMobileNo',$input_params['mobile_number']);
      $this->db->where_not_in('iUserId',$input_params['user_id']);
      $mobile_number_data=$this->db->get()->result_array();
     if($mobile_number_data[0]['vMobileNo']==$input_params['mobile_number']){
         $return_arr['message']="Account with this mobile number already exists.";
         $return_arr['status'] = "0";
         return  $return_arr;
      }
     
    }
    if(!empty($input_params['user_name'])){
      $this->db->select('vUserName');
      $this->db->from('users');
      $this->db->where('vUserName',$input_params['user_name']);
      $this->db->where_not_in('iUserId',$input_params['user_id']);
      $user_name_data=$this->db->get()->result_array();
      if($user_name_data[0]['vUserName']==$input_params['user_name']){
          $return_arr['message']="Account with this username already exists.";
          $return_arr['status'] = "0";
          return  $return_arr;
      }
    }
   
   return  $return_arr; 
    
}
}
