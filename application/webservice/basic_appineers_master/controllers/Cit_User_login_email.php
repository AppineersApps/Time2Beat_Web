<?php

   
/**
 * Description of User Login Email Extended Controller
 * 
 * @module Extended User Login Email
 * 
 * @class Cit_User_login_email.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_User_login_email.php
 * 
 * @author CIT Dev Team
 * 
 * @date 13.09.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_User_login_email extends User_login_email {
        public function __construct()
{
    parent::__construct();
}
public function helperPrepareWhere(&$input_params=array()){
    $return = array();
	$return[0]['status']=1;
	$return[0]['message']="";
	$return[0]['where_clause']='0=1';
    $where = array();
    	if($input_params['email']!='' && $input_params['password']!=''){
	    $this->db->select('iUserId,vPassword');
	    $this->db->from('users');
	    $this->db->where('vEmail', $input_params['email']);
	    $data=$this->db->get()->result_array();
	    $params['old_password']=$input_params['password'];
        $params['mc_password']=$data[0]['vPassword'];
        $result = $this->general->verifyCustomerPassword($params); 
        if($result[0]['is_matched']==1){
            $where[]="u.iUserId='".$data[0]['iUserId']."'";
        }
        else{
        	$where[]="u.iUserId=''";
        }
       // 		$where[]="u.vPassword='$k' ";
    // 		$where[]="u.vEmail='".trim($input_params['user_email'])."' ";	
	}else{
		$return[0]['status']=0;
		$return[0]['message']="Please provide login detail.";
	}

	$return[0]['where_clause']=implode("AND ",$where);
    return $return;
    
}
}
