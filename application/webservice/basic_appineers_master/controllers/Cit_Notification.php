<?php
            
/**
 * Description of Send Message Extended Controller
 * 
 * @module Extended Send Message
 * 
 * @class Cit_Send_message.php
 * 
 * @path application\webservice\friends\controllers\Cit_Send_message.php
 * 
 * @author CIT Dev Team
 * 
 * @date 30.05.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Notification extends Notification {

   public function __construct()
    {
        parent::__construct();
    }

  public function checkNotificationExist($input_params=array()){
      $return_arr['message']='';
      $return_arr['status']='1';
         if(false == empty($input_params['notification_id']))
         {
            $this->db->from("notification");
            $this->db->select("iNotificationId AS notification_id");
             $this->db->where("iNotificationId", $input_params['notification_id']);
            $arrNotification=$this->db->get()->result_array();
            //print_r($arrNotification);exit;
          if(true == empty($arrNotification)){
             $return_arr['message']="No notification available";
             $return_arr['status'] = "0";
             return  $return_arr;
          }else{
            $return_arr['notification_id']=$arrNotification['0']['notification_id'];
          }
      }
      return $return_arr;
    
  }
}
