<?php

   
/**
 * Description of User Sign Up Email Extended Controller
 * 
 * @module Extended User Sign Up Email
 * 
 * @class Cit_User_sign_up_email.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_User_sign_up_email.php
 * 
 * @author CIT Dev Team
 * 
 * @date 10.02.2020
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_User extends User {
        public function __construct()
{
    parent::__construct();
}
public function checkUniqueUser($input_params=array()){
   
   $return_arr['message']='';
   $return_arr['status']='1';
   $return_arr['message']='';
   $return_arr['status']='1';
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
   
   return  $return_arr; 
}

public function checkuniqueusername($input_params=array()){

    $return_arr['message']='';
    $return_arr['status']='1';
    $auth_header = $this->input->get_request_header('AUTHTOKEN');


    if ($auth_header != "") {
        $req_token = $auth_header;
    } else {
        $req_token = $input_params['user_access_token'];
    }
    $userid=0;
    if($req_token)
    {
        
        $access = $req_token;
        $this->db->select('iUserId,eStatus');
        $this->db->from('users');
        $this->db->where('vAccessToken',$access);
        //$this->db->where('eStatus','Active');
        $result = $this->db->get()->result_array();
        $return_arr['user_id'] = $result[0]['iUserId']; 
        $return_arr['status'] = '1';    
    }
    if(!empty($return_arr['user_id']) && $return_arr['status'] =='Active'){         
        $request_arr['user_id']=$return_arr['user_id'];
    }else if(!empty($return_arr['user_id']) && $return_arr['status'] =='Inactive'){
        $return_arr['code'] = "401";
        $return_arr['message'] = "Your account is deactivated. Please contact administrator.";

    }else{
        $return_arr['code'] = "401";
        $return_arr['code'] = "Your session is expired.";
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
?>