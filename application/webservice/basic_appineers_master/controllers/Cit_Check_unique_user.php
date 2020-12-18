<?php

   
/**
 * Description of Check Unique User Extended Controller
 * 
 * @module Extended Check Unique User
 * 
 * @class Cit_Check_unique_user.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_Check_unique_user.php
 * 
 * @author CIT Dev Team
 * 
<<<<<<< HEAD
 * @date 25.10.2019
=======
 * @date 06.02.2020
>>>>>>> messages changes
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Check_unique_user extends Check_unique_user {
        public function __construct()
{
    parent::__construct();
}
public function checkUniqueUser($input_params=array()){
   
   $return_arr['message']='';
   $return_arr['status']='1';
   if($input_params['type']=='email'){
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
            
           
   }else if($input_params['type']=='phone'){
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
         
   }
   return  $return_arr;
   
}
}
