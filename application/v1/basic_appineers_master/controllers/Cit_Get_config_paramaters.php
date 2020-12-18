<?php

   
/**
 * Description of Get Config Paramaters Extended Controller
 * 
 * @module Extended Get Config Paramaters
 * 
 * @class Cit_Get_config_paramaters.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_Get_config_paramaters.php
 * 
 * @author CIT Dev Team
 * 
 * @date 23.12.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Get_config_paramaters extends Get_config_paramaters {
        public function __construct()
{
    parent::__construct();
}
public function returnConfigParams(&$input_params=array()){
     $return_arr['terms_conditions_updated']='';
     $return_arr['privacy_policy_updated']  ='';
     $return_arr['log_status_updated']  ='';
    //check for login user 
    
    $auth_header = $this->input->get_request_header('AUTHTOKEN');

    if ($auth_header != "") {
       $req_token = $auth_header;
    } else {
         $req_token = $input_params['user_access_token'];
     }
    if($req_token)
     {
                
                $access = $req_token;
                $this->db->select('iUserId');
                $this->db->from('users');
                $this->db->where('vAccessToken',$access);
                $this->db->where('eStatus','Active');
                $result = $this->db->get()->result_array();
                $userid = $result[0]['iUserId']; 
                   
     }
    if(!empty($userid)){
        $return_arr['terms_conditions_updated']=1;
        $return_arr['privacy_policy_updated']  =1;
        $this->db->select('vTermsConditionsVersion,vPrivacyPolicyVersion,eLogStatus');
        $this->db->from('users');
        $this->db->where('iUserId',$userid);
        $version_data=$this->db->get()->row_array();
        $terms_conditions_version=$version_data['vTermsConditionsVersion'];
        $privacy_policy_version  =$version_data['vPrivacyPolicyVersion'];
        $return_arr['log_status_updated']=$version_data['eLogStatus']; 
    }
   //terms and conditions
    $this->db->select('vVersion,vPageCode');
    $this->db->from('mod_page_settings');
    $this->db->where_in('vPageCode',termsconditions);
    $termsconditions_code_version=$this->db->get()->row_array();
    //privacy policy 
    $this->db->select('vVersion,vPageCode');
    $this->db->from('mod_page_settings');
    $this->db->where_in('vPageCode',privacypolicy);
    $privacypolicy_code_version=$this->db->get()->row_array();
    if($privacy_policy_version==$privacypolicy_code_version['vVersion']){
        $return_arr['privacy_policy_updated']=0;
    }
    if($terms_conditions_version==$termsconditions_code_version['vVersion']){
        $return_arr['terms_conditions_updated']=0;
    }
    
     //end 
    $message = $this->config->item('VERSION_CHECK_MESSAGE');
    $app_name=$this->config->item('COMPANY_NAME');
    if($this->config->item('VERSION_UPDATE_CHECK')=='Enabled'){
        $return_arr['version_update_check']=1;
    }else{
        $return_arr['version_update_check']=0;
    }
    if($this->config->item('VERSION_UPDATE_OPTIONAL')=='Enabled'){
        $return_arr['version_update_optional']=1;
    }else{
        $return_arr['version_update_optional']=0;
    }
    $return_arr['android_version_number'] =$this->config->item('ANDROID_VERSION_NUMBER');
    $return_arr['iphone_version_number']  =$this->config->item('IPHONE_VERSION_NUMBER');
    $return_arr['version_check_message']  = str_replace('|appname|',$app_name,$message);
    return $return_arr;
}
}
