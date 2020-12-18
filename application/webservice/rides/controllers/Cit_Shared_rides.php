<?php
/**
 * Description of Resend Otp Extended Controller
 * 
 * @module Extended Resend Otp
 * 
 * @class Cit_Resend_otp.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_Resend_otp.php
 * 
 * @author CIT Dev Team
 * 
 * @date 18.09.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Shared_rides extends Shared_rides {
  public function __construct()
  {
      parent::__construct();
  }
  public function checkRideDetails($input_params=array()){

        // print_r($input_params);exit;
      $return_arr['message']='';
      $return_arr['status']='1';
      //print_r($input_params); exit;
       if(false == empty($input_params['shared_with_ids']))
       {
            $this->db->from("shared_rides AS sr");
            $this->db->select("sr.iShareRideId AS share_ride_id");
            $this->db->where_in("iRideId", $input_params['ride_id']);
            $this->db->where("iFriendUserId", $input_params['shared_with_ids']);
            $ride_data=$this->db->get()->result_array();
            //print_r($ride_data);exit;
            //echo $this->db->last_query();exit;
            if(true == empty($ride_data)){
               $return_arr['checkrideexist']['0']['message']="No ride available";
               $return_arr['checkrideexist']['0']['status'] = "0";
               return  $return_arr;
            }else{
              $return_arr['checkrideexist']['0']['share_ride_id']=$ride_data['0']['share_ride_id'];
               $return_arr['checkrideexist']['0']['status'] = "1";
              //$return_arr['status']='1';
              //$return_arr['share_ride_id']=$ride_data['0']['share_ride_id'];
            }
        }
        /*foreach ($return_arr as $value) {
          $return_arr = $value;
          $return_arr['status']='1';
        }*/
        return $return_arr;
        
      }

     public function checkRideExist($input_params=array()){

        // print_r($input_params);exit;
      $return_arr['message']='';
      $return_arr['status']='1';
      //print_r($input_params); exit;
       if(false == empty($input_params['ride_id']))
       {
            $this->db->from("ride AS r");
            $this->db->select("r.iRideId AS ride_id");
            $this->db->where_in("iRideId", $input_params['ride_id']);
            $ride_data=$this->db->get()->result_array();
            // echo $this->db->last_query();exit;
            if(true == empty($ride_data)){
               $return_arr['message']="No ride available";
               $return_arr['status'] = "0";
               return  $return_arr;
            }else{
             $return_arr['status']='1';
            }
        }
        /*foreach ($return_arr as $value) {
          $return_arr = $value;
          $return_arr['status']='1';
        }*/
        return $return_arr;
        
      }
      public function checkShareRideExist($input_params=array()){

        // print_r($input_params);exit;
      $return_arr['message']='';
      $return_arr['status']='1';
      //print_r($input_params); exit;
       if(false == empty($input_params['ride_id']))
       {
            $this->db->from("shared_rides AS r");
            $this->db->select("r.iRideId AS ride_id");
            $this->db->where_in("r.iRideId", $input_params['ride_id']);
            $ride_data=$this->db->get()->result_array();
           //echo $this->db->last_query();exit;
            if(true == empty($ride_data)){
               $return_arr['message']="No ride available";
               $return_arr['status'] = "0";
               return  $return_arr;
            }else{
             $return_arr['status']='1';
            }
        }
        /*foreach ($return_arr as $value) {
          $return_arr = $value;
          $return_arr['status']='1';
        }*/
        return $return_arr;
        
      }

       public function PrepareHelperMessage($input_params=array()){
    // print_r($input_params);exit;
      $this->db->select('nt.tMessage');
    $this->db->from('mod_push_notify_template as nt');
      if(isset($input_params['other_user_ids'])){
        $this->db->where('nt.vTemplateCode','share_ride');  
      }
      
      $notification_text=$this->db->get()->result_array();
      $notification_text=$notification_text[0]['tMessage'];

      $notification_text = str_replace("|sender_name|",ucfirst($input_params['get_user_details'][0]['s_name']), $notification_text);
     // $notification_text =str_replace("|sender_name|", '', $notification_text);
      $return_array['notification_message']=$notification_text;
     // print_r($return_array);exit;
      return $return_array;
      
  }

}

?>
