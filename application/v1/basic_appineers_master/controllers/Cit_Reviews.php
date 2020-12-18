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
 
Class Cit_Reviews extends Reviews {
  public function __construct()
  {
      parent::__construct();
  }
  public function checkReviewExist($input_params=array()){
      $return_arr['message']='';
     	$return_arr['status']='1';
      //print_r($input_params); exit;
     	 if(false == empty($input_params['review_id']))
     	 {
            $this->db->from("review AS r");
           // $this->db->select("r.iReviewId AS review_id");
            $this->db->select("r.vFirstName AS first_name");
            $this->db->select("r.vLastName AS last_name");
            $this->db->select("r.vMobileNo AS mobile_number");            
            $this->db->select("r.vEmail AS email"); 
            $this->db->select("r.vProfileImage AS profile_image");            
            $this->db->select("r.iStateId AS state"); 
            $this->db->select("r.vPlaceId AS place_id");
            $this->db->select("r.dLatitude AS latitude");
            $this->db->select("r.dLongitude AS longitude");
            $this->db->select("r.vCity AS city");
            $this->db->select("r.vZipCode AS zipcode");
            $this->db->select("r.tAddress AS street_address");
            $this->db->select("r.vBussinessName AS business_name");
            $this->db->select("r.vReviewType AS review_type");
            $this->db->select("r.vPosition AS position");
            $this->db->select("r.iBussinessType AS business_typeid");
            $this->db->where_in("iReviewId", $input_params['review_id']);
            $review_data=$this->db->get()->result_array();
          if(true == empty($review_data)){
             $return_arr['checkreviewexist']['0']['message']="No reviews available";
             $return_arr['checkreviewexist']['0']['status'] = "0";
             return  $return_arr;
          }else{
          	$return_arr['review_id']=$review_data;
          }
      }
      foreach ($return_arr as $value) {
        $return_arr = $value;
        $return_arr['status']='1';
      }
      //print_r($return_arr); exit;
      return $return_arr;
    
  }

  public function checkUniqueUser($input_params=array()){
    $return_arr['message']='';
     $return_arr['status']='0';
     $conditions ='';
     
     if(false == empty($input_params['claimed_email']))
     {
      $input_params["claimed_email"] = explode(",",$input_params["claimed_email"]);
      if (count($input_params["claimed_email"]) > 0) {
        $conditions = "'" . implode("', '", $input_params["claimed_email"]) . "'";
      }

     }
     $strEmail = (false == empty($conditions))? $conditions : "'" .$input_params['email']. "'";
     
    if(false == empty($strEmail)){
       $strSql="SELECT u.vEmail AS email, u.iUserId AS registered_user_id
                        FROM users AS u
                        WHERE u.vEmail IN ($strEmail)";

      $result_obj =  $this->db->query($strSql);
      $arrResult = is_object($result_obj) ? $result_obj->result_array() : array();
      if(count( $arrResult)>1)
      {
        
        $result_arr['vEmail'] = array();
        $result_arr['status'] = array();
        $result_arr['registered_user_id'] = array();
        $result_arr['message'] = array();
         foreach ($arrResult as $data_key => $data_arr)
          {
            
              array_push($result_arr['vEmail'],$data_arr['email']);
              array_push($result_arr['status'],'1');
              array_push($result_arr['message'],"This email is already registered, please try using different email.");
              array_push($result_arr['registered_user_id'],$data_arr['registered_user_id']);
          }

      }else{
        
        if(strpos($strEmail,$arrResult[0]['email'] ) !== false){
           $return_arr['message']="This email is already registered, please try using different email.";
           $return_arr['status'] = "1";
           $return_arr['registered_user_id'] = $arrResult[0]['registered_user_id'];

      }
    }
    //print_r($return_arr); exit;

    return  $return_arr;
   
  }
}
}
?>
