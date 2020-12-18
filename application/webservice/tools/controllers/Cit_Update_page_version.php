<?php

   
/**
 * Description of Update Page Version Extended Controller
 * 
 * @module Extended Update Page Version
 * 
 * @class Cit_Update_page_version.php
 * 
 * @path application\webservice\tools\controllers\Cit_Update_page_version.php
 * 
 * @author CIT Dev Team
 * 
 * @date 23.12.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Update_page_version extends Update_page_version {
        public function __construct()
{
    parent::__construct();
}
public function pageVersionUpdate($input_params=array()){
    $page_type=$input_params['page_type'];
    $user_id  =$input_params['user_id'];
    $return_arr['success']=0;
    $return_arr['message']='';
    if($page_type=='termsconditions'){
        
        //get termsconditions version
        $this->db->select('vVersion');
        $this->db->from('mod_page_settings');
        $this->db->where('vPageCode','termsconditions');
        $termsconditions_version=$this->db->get()->row_array();
        //end
        if(!empty($termsconditions_version['vVersion']) && $termsconditions_version['vVersion']!=''){
            //update user version
            $this->db->set('vTermsConditionsVersion',$termsconditions_version['vVersion']);
            $this->db->set('dtUpdatedAt',date('Y-m-d H:i:s'));
            $this->db->where('iUserId',$user_id);
            $this->db->update('users');
            if($this->db->affected_rows()>0){
                $return_arr['success']=1;
                $return_arr['message']='Terms & Conditions version update successfully';
                
            }
        }
    }else if($page_type=='privacypolicy'){
        //get privacy policy version
        $this->db->select('vVersion');
        $this->db->from('mod_page_settings');
        $this->db->where('vPageCode','privacypolicy');
        $privacypolicy_version=$this->db->get()->row_array();
        //end
        if(!empty($privacypolicy_version['vVersion']) && $privacypolicy_version['vVersion']!=''){
            //update user version
            $this->db->set('vPrivacyPolicyVersion',$privacypolicy_version['vVersion']);
            $this->db->set('dtUpdatedAt',date('Y-m-d H:i:s'));
            $this->db->where('iUserId',$user_id);
            $this->db->update('users');
            if($this->db->affected_rows()>0){
                $return_arr['success']=1;
                $return_arr['message']='Privacy Policy version update successfully';
                
            }
        }
        
    }
    return $return_arr;
}
}
