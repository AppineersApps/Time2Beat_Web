<?php


/**
 * Description of Review Management Extended Controller
 * 
 * @module Extended Review Management
 * 
 * @class Cit_Review_management.php
 * 
 * @path application\admin\basic_appineers_master\controllers\Cit_Review_management.php
 * 
 * @author CIT Dev Team
 * 
 * @date 01.10.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Review_management extends Review_management {
        public function __construct()
{
    parent::__construct();
      $this->load->model('cit_api_model');
}
public function ActiveReviewInlineEdition($field_name = '', $value = '', $id = ''){

            if($value=='Active'){
                $data = array(
                        'eEmailVerified' => 'Yes',
                        'eStatus' => 'Active',
                        'dtUpdatedAt' => date('Y-m-d H:i:s'),
                        'dtDeletedAt'=>''
                   );
                
                $this->db->where('iReviewId', $id);
                $this->db->update('review', $data);
                $ret_arr['success'] = true; 
                $ret_arr['value'] = $value;
            
            }else if($value=='Archived'){
                $data=array(
                        'eStatus' => 'Archived',
                        'dtDeletedAt' => date('Y-m-d H:i:s')
                   );
                $this->db->where('iReviewId', $id);
                $this->db->update('review', $data);
                $ret_arr['success'] = true; 
                $ret_arr['value'] = $value;
            
            }else{
            $ret_arr['success'] = true; 
            $ret_arr['value'] = $value;
            }
            
            return $ret_arr;
    
}
/*public function ActiveReviewAfterChangeStatus($mode = '', $id = '', $parID = ''){
     if($mode=='Active'){
      $count=count($id);
       if($count==1){
           $params = array("review_id" => $id);
           
           
           $resp_arr	= $this->cit_api_model->callAPI('admin_update_review_status_in_listing',$params);
       
           if($resp_arr['settings']['success']==1){
              
               $ret_arr['success'] = true; 
               $ret_arr['message'] = "Record updated successfully..!";
              
           }else{
               $ret_arr['success'] = false; 
           }
        }
        else if($count>1){
            foreach($id as $key=>$value){
               $params = array("review_id" => $value);
               $resp_arr	= $this->cit_api_model->callAPI('admin_update_review_status_in_listing',$params);
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
           $params = array("review_id" => $id);
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
               $params = array("review_id" => $value);
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
            $this->db->where('iReviewId', $id);
            $this->db->update('review', $data);
            $ret_arr['success'] = true; 
            $ret_arr['message'] = "Record updated successfully..!";
        }else if($count>1){
            $updateArray = array();
            foreach($id as $key=>$value){
                $updateArray[]=array(
                    'iReviewId'=>$value,
                    'dtUpdatedAt' => date('Y-m-d H:i:s'),
                    'dtDeletedAt' => ''
                    );
            }
          $this->db->update_batch('review',$updateArray, 'iReviewId'); 
           $ret_arr['success'] = true; 
           $ret_arr['message'] = "Record updated successfully..!";
            
        }
           
     
    }
    
    return $ret_arr;
    
}*/
/*public function updateDeletedAt($mode = '', $id = '', $parID = ''){
    $data=$this->input->post();
    if($data['u_status']=='Archived'){
        $data=array(
                        'dtDeletedAt' => date('Y-m-d H:i:s')
                    );
        $this->db->where('iReviewId', $id);
        $this->db->update('review', $data);
        $ret_arr['success'] = true;
    }else{
        $data=array(
                        'dtDeletedAt' => ''
                    );
        $this->db->where('iReviewId', $id);
        $this->db->update('review', $data);
        $ret_arr['success'] = true;
    }
    return $ret_arr;
   
}*/
}
?>
