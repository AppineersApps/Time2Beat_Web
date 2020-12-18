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
 
Class Cit_Block_user extends Block_user {
	  public function __construct()
	  {
	      parent::__construct();
	  }
	public function PrepareHelperMessage($input_params=array()){
		// print_r($input_params);exit;
	    $this->db->select('nt.tMessage');
		$this->db->from('mod_push_notify_template as nt');
	    if(isset($input_params['timestamp'])){
	    	$this->db->where('nt.vTemplateCode','friend_request');	
	    }
	    if(isset($input_params['option'])){
	    	$this->db->where('nt.vTemplateCode','accept_request');	
	    }
	    
	    $notification_text=$this->db->get()->result_array();
	    $notification_text=$notification_text[0]['tMessage'];

	    $notification_text = str_replace("|sender_name|",ucfirst($input_params['get_user_details'][0]['s_name']), $notification_text);
	    $return_array['notification_message']=$notification_text;
	   // print_r($return_array);exit;
	    return $return_array;
	    
	}


  public function checkIsUserBlocked($input_params=array()){
      $return_arr['message']='';
      $return_arr['status']='1';
       if(false == empty($input_params['other_user_id']))
       {
            $this->db->from("blocked_user AS b");
            $this->db->select("b.iBlockUserId AS blocked_user_id");
            $this->db->where_in("iBlockedTo", $input_params['other_user_id']);
            $this->db->where_in("iBlockedBy", $input_params['user_id']);
            $block_data=$this->db->get()->result_array();
          if(true == empty($block_data)){
             $return_arr['checkuserblocked']['0']['message']="User not blocked";
             $return_arr['checkuserblocked']['0']['status'] = "0";
             return  $return_arr;
          }else{
          		$return_arr['blocked_user_id']=$block_data[0]['blocked_user_id'];	
          }
      }
      return $return_arr;
  }


  public function checkIsUserFriend($input_params=array()){
  	// print_r($input_params);exit;
      $return_arr['message']='';
      $return_arr['status']='1';
       if(false == empty($input_params['other_user_id']))
       {
            $this->db->from("friends AS f");
            $this->db->select("f.iFriendListId AS request_id");
            $this->db->where("(f.iUserId = '".$input_params['user_id']."' AND f.iFriendUserId = '".$input_params['other_user_id']."') OR (f.iUserId = '".$input_params['other_user_id']."' AND f.iFriendUserId = '".$input_params['user_id']."')");
            $friend_data=$this->db->get()->result_array();
          if(true == empty($friend_data)){
             $return_arr['getfriendrequestid']['0']['message']="No request available";
             $return_arr['getfriendrequestid']['0']['status'] = "0";
             return  $return_arr;
          }else{
          	// print_r($friend_data);exit;
          		$return_arr['request_id']=$friend_data[0]['request_id'];	
          	
            
          }
      }
      return $return_arr;
  }


}

?>
