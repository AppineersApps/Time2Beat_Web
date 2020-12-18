<?php

   
/**
 * Description of Change Password Extended Controller
 * 
 * @module Extended Change Password
 * 
 * @class Cit_Change_password.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_Change_password.php
 * 
 * @author CIT Dev Team
 * 
 * @date 08.10.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Change_password extends Change_password {
        public function __construct()
{
    parent::__construct();
}
public function checkPasswordMatch($input_params=array()){
  
    $this->db->select('iUserId,vPassword');
	$this->db->from('users');
	$this->db->where('iUserId', $input_params['user_id']);
	$data=$this->db->get()->result_array();
	
    $params['old_password']=$input_params['old_password'];
    $params['mc_password']=$data[0]['vPassword'];
    $result = $this->general->verifyCustomerPassword($params); 

    if($result[0]['is_matched']==1){
        $return_array['matched']=1;
    }
    else{
         $return_array['matched']=0;
    }
    
    return $return_array;
	
}
}
