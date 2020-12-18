<?php


/**
 * Description of Businesstype Management Extended Controller
 * 
 * @module Extended Businesstype Management
 * 
 * @class Cit_Businesstype_management.php
 * 
 * @path application\admin\basic_appineers_master\controllers\Cit_Businesstype_management.php
 * 
 * @author CIT Dev Team
 * 
 * @date 01.10.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Businesstype_management extends Businesstype_management {
        public function __construct()
{
    parent::__construct();
      $this->load->model('cit_api_model');
}
public function checkUniqueBussinessType($value = ''){
    $return_arr='1';
    if(false == empty($value)){
      $this->db->select('iReviewId ');
      $this->db->from('review');
      $this->db->where_in('iBussinessType ', $value);
      $review_data=$this->db->get()->result_array();
     if(true == empty($review_data)){
         $return_arr = "0";
         return  $return_arr;
      }     
    } 
   return  $return_arr; 
    
}
public function ActiveBusinesstypeInlineEdition($field_name = '', $value = '', $id = ''){

            if($value=='Active'){
                $data = array(
                        'eStatus' => 'Active',
                        'dtUpdatedAt' => date('Y-m-d H:i:s'),
                        'dtDeletedAt'=>''
                   );
                
                $this->db->where('iBusinessTypeId', $id);
                $this->db->update('business_type', $data);
                $ret_arr['success'] = true; 
                $ret_arr['value'] = $value;
            
            }else if($value=='Inactive'){
                $data=array(
                        'eStatus' => 'Active',
                        'dtDeletedAt' => date('Y-m-d H:i:s')
                   );
                $this->db->where('iBusinessTypeId', $id);
                $this->db->update('business_type', $data);
                $ret_arr['success'] = true; 
                $ret_arr['value'] = $value;
            
            }else{
            $ret_arr['success'] = true; 
            $ret_arr['value'] = $value;
            }
            
            return $ret_arr;
    
}
/*public function ActiveBusinesstypeAfterChangeStatus($mode = '', $id = '', $parID = ''){
     if($mode=='Active'){
      $count=count($id);
       if($count==1){
           $params = array("business_type_id" => $id);
           
           
           $resp_arr	= $this->cit_api_model->callAPI('admin_update_business_type_status_in_listing',$params);
       
           if($resp_arr['settings']['success']==1){
              
               $ret_arr['success'] = true; 
               $ret_arr['message'] = "Record updated successfully..!";
              
           }else{
               $ret_arr['success'] = false; 
           }
        }
        else if($count>1){
            foreach($id as $key=>$value){
               $params = array("business_type_id" => $value);
               $resp_arr	= $this->cit_api_model->callAPI('admin_update_business_type_status_in_listing',$params);
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
           $params = array("business_type_id" => $id);
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
               $params = array("business_type_id" => $value);
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
            $this->db->where('iBusinessTypeId', $id);
            $this->db->update('business_type', $data);
            $ret_arr['success'] = true; 
            $ret_arr['message'] = "Record updated successfully..!";
        }else if($count>1){
            $updateArray = array();
            foreach($id as $key=>$value){
                $updateArray[]=array(
                    'iBusinessTypeId'=>$value,
                    'dtUpdatedAt' => date('Y-m-d H:i:s'),
                    'dtDeletedAt' => ''
                    );
            }
          $this->db->update_batch('business_type',$updateArray, 'iBusinessTypeId'); 
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
        $this->db->where('iBusinessTypeId', $id);
        $this->db->update('business_type', $data);
        $ret_arr['success'] = true;
    }else{
        $data=array(
                        'dtDeletedAt' => ''
                    );
        $this->db->where('iBusinessTypeId', $id);
        $this->db->update('business_type', $data);
        $ret_arr['success'] = true;
    }
    return $ret_arr;
   
}*/
}
?>
