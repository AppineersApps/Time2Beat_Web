<?php


/**
 * Description of Users Management Extended Controller
 * 
 * @module Extended Users Management
 * 
 * @class Cit_Users_management.php
 * 
 * @path application\admin\basic_appineers_master\controllers\Cit_Users_management.php
 * 
 * @author CIT Dev Team
 * 
 * @date 01.10.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Users_management extends Users_management {
        public function __construct()
{
    parent::__construct();
      $this->load->model('cit_api_model');
}
public function ActiveUserInlineEdition($field_name = '', $value = '', $id = ''){

            if($value=='Active'){
                $data = array(
                        'eEmailVerified' => 'Yes',
                        'eStatus' => 'Active',
                        'dtUpdatedAt' => date('Y-m-d H:i:s'),
                        'dtDeletedAt'=>''
                   );
                
                $this->db->where('iUserId', $id);
                $this->db->update('users', $data);
                $ret_arr['success'] = true; 
                $ret_arr['value'] = $value;
            
            }else if($value=='Archived'){
                $data=array(
                        'eStatus' => 'Archived',
                        'dtDeletedAt' => date('Y-m-d H:i:s')
                   );
                $this->db->where('iUserId', $id);
                $this->db->update('users', $data);
                $ret_arr['success'] = true; 
                $ret_arr['value'] = $value;
            
            }else{
            $ret_arr['success'] = true; 
            $ret_arr['value'] = $value;
            }
            
            return $ret_arr;
    
}
public function ActiveUserAfterChangeStatus($mode = '', $id = '', $parID = ''){
     if($mode=='Active'){
      $count=count($id);
       if($count==1){
           $params = array("user_id" => $id);
           
           
           $resp_arr	= $this->cit_api_model->callAPI('admin_update_user_status_in_listing',$params);
       
           if($resp_arr['settings']['success']==1){
              
               $ret_arr['success'] = true; 
               $ret_arr['message'] = "Record updated successfully..!";
              
           }else{
               $ret_arr['success'] = false; 
           }
        }
        else if($count>1){
            foreach($id as $key=>$value){
               $params = array("user_id" => $value);
               $resp_arr	= $this->cit_api_model->callAPI('admin_update_user_status_in_listing',$params);
               if($resp_arr['settings']['success']==1){
                   $ret_arr['success'] = true; 
                   $ret_arr['message'] = "Record updated successfully..!";
                }
                else{
                  $ret_arr['success'] = false; 
                  
                }
            }
        }
    }
    else if($mode=='Archived'){
        
        $count=count($id);
       if($count==1){
           $params = array("user_id" => $id);
           $resp_arr	= $this->cit_api_model->callAPI('delete_account',$params);
       
           if($resp_arr['settings']['success']==1){
              
               $ret_arr['success'] = true; 
               $ret_arr['message'] = "Record updated successfully..!";
              
           }else{
               $ret_arr['success'] = false; 
           }
        }
        else if($count>1){
            foreach($id as $key=>$value){
               $params = array("user_id" => $value);
               $resp_arr	= $this->cit_api_model->callAPI('delete_account',$params);
               if($resp_arr['settings']['success']==1){
                   $ret_arr['success'] = true; 
                   $ret_arr['message'] = "Record updated successfully..!";
                }
                else{
                  $ret_arr['success'] = false; 
                  
                }
            }
        }
        
        
    }else{
        $count=count($id);
        if($count==1){
             $data=array('dtDeletedAt' => '',
                 'dtUpdatedAt' => date('Y-m-d H:i:s')
                );
            $this->db->where('iUserId', $id);
            $this->db->update('users', $data);
            $ret_arr['success'] = true; 
            $ret_arr['message'] = "Record updated successfully..!";
        }else if($count>1){
            $updateArray = array();
            foreach($id as $key=>$value){
                $updateArray[]=array(
                    'iUserId'=>$value,
                    'dtUpdatedAt' => date('Y-m-d H:i:s'),
                    'dtDeletedAt' => ''
                    );
            }
          $this->db->update_batch('users',$updateArray, 'iUserId'); 
           $ret_arr['success'] = true; 
           $ret_arr['message'] = "Record updated successfully..!";
            
        }
           
     
    }
    
    return $ret_arr;
    
}
public function updateDeletedAt($mode = '', $id = '', $parID = ''){
    $data=$this->input->post();
    if($data['u_status']=='Archived'){
        $data=array(
                        'dtDeletedAt' => date('Y-m-d H:i:s')
                    );
        $this->db->where('iUserId', $id);
        $this->db->update('users', $data);
        $ret_arr['success'] = true;
    }else{
        $data=array(
                        'dtDeletedAt' => ''
                    );
        $this->db->where('iUserId', $id);
        $this->db->update('users', $data);
        $ret_arr['success'] = true;
    }
    return $ret_arr;
   
}
}
