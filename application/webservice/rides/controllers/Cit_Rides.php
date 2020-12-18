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
 
Class Cit_Rides extends Rides {
  public function __construct()
  {
      parent::__construct();
  }
  public function checkRideDetails($input_params=array()){

      $input_params["start_location_id"] = $this->checkLocation($input_params["start_location"]);
      $input_params["end_location_id"] = $this->checkLocation($input_params["end_location"]);
      return $input_params;
      
    }

    public function checkLocation($requestArr = array()){
      if (false == empty($requestArr))
      {
          $params_arr =json_decode($requestArr,true);
          if(true == empty($params_arr)){
           $params_arr =$input_params;
          }
      }
      // print_r($params_arr);exit;
      $insert_arr = array();
      $location_id = 0;
      if(false == empty($params_arr[0]["locationId"])){
        return $params_arr[0]["locationId"];
      }

      if(false == empty($params_arr[0]))
      {
          $insert_arr['dLatitude']=$params_arr[0]['lat'];
          $insert_arr['dLongitude']=$params_arr[0]['lon'];
          $insert_arr['tAddress']=$params_arr[0]['address'];
          $insert_arr['vCity']=$params_arr[0]['city'];
          $insert_arr['vState']=$params_arr[0]['state'];
          $insert_arr['vZipCode']=$params_arr[0]['zipcode'];
          $insert_arr['dtAddedAt'] = date('Y-m-d H:i:s');
          $this->db->select('iLocationId');
          $this->db->from('location');
          $this->db->where('tAddress', $params_arr[0]['address']);
          $this->db->where('vCity', $params_arr[0]['city']);
          $this->db->where('vState', $params_arr[0]['state']);
          $this->db->where('vZipCode', $params_arr[0]['zipcode']);
          $data=$this->db->get()->result_array();
         
          if(false == empty($data)){
            return $data[0]["iLocationId"];
          }
      }

     if(is_array($insert_arr) && !empty($insert_arr))
      {
        $this->db->insert("location",$insert_arr);
      }

      $location_id = $this->db->insert_id();
      // print_r($insert_id);exit;
      return $location_id;
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
               $return_arr['checkrideexist']['0']['message']="No ride available";
               $return_arr['checkrideexist']['0']['status'] = "0";
               return  $return_arr;
            }else{
              $return_arr['ride_id']=$ride_data;
            }
        }
        foreach ($return_arr as $value) {
          $return_arr = $value;
          $return_arr['status']='1';
        }
        return $return_arr;
        
      }

      public function prepareDistanceQuery($input_params=array()){
 
      $user_latitude    =   $input_params['latitude'];
      $user_longitude   =   $input_params['longitude'];
          if(!empty($user_longitude) && !empty($user_latitude))
          {

            $distance = "
                3959 * acos (
                  cos ( radians($user_latitude) )
                  * cos( radians( sl.dLatitude ) )
                  * cos( radians( sl.dLongitude ) - radians($user_longitude) )
                  + sin ( radians($user_latitude) )
                  * sin( radians( sl.dLatitude ) )
                )";
            
          }else{
               //distance filter
            $distance= 'IF(1=1,"","")'; 
          }
          
          $return_arr['distance']=$distance;
        
          return $return_arr;
    }

}

?>
