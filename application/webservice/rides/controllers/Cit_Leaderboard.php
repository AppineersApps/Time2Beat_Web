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
 
Class Cit_Leaderboard extends Leaderboard {
  public function __construct()
  {
      parent::__construct();
  }

     public function getRideInformation($input_params=array()){

        // print_r($input_params);exit;
      $return_arr['message']='';
      $return_arr['status']='1';
      //print_r($input_params); exit;
       if(false == empty($input_params['ride_id']))
       {
            $this->db->from("ride AS r");
            $this->db->select("r.iStartLocId AS start_loc_id");
            $this->db->select("r.iEndLocId AS end_loc_id");
            $this->db->where_in("iRideId", $input_params['ride_id']);
            $ride_data=$this->db->get()->result_array();
            // echo $this->db->last_query();exit;
            
            $input_params['start_loc_id']=$ride_data[0]['start_loc_id'];
            $input_params['end_loc_id']=$ride_data[0]['end_loc_id'];
            
        }
        return $input_params;
        
      }

}

?>
