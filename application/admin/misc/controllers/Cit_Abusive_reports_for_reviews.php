<?php


/**
 * Description of Abusive Reports For Reviews Extended Controller
 * 
 * @module Extended Abusive Reports For Reviews
 * 
 * @class Cit_Abusive_reports_for_reviews.php
 * 
 * @path application\admin\misc\controllers\Cit_Abusive_reports_for_reviews.php
 * 
 * @author CIT Dev Team
 * 
 * @date 17.06.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Abusive_reports_for_reviews extends Abusive_reports_for_reviews {
        public function __construct()
{
    parent::__construct();
}
public function showStatusButton($id='',$arr=array())
{     
        $url = $this->general->getAdminEncodeURL('misc/abusive_reports_for_reviews/add').'|mode|'.$this->general->getAdminEncodeURL('Update').'|id|'.$this->general->getAdminEncodeURL($arr);
       return '<button type="button" data-id='.$arr.' class="btn btn-success operBut" data-url='.$url.' >Edit</button>';
      
}
public function setLimitForDescription($value = '',$id = '',$data = array()){
    $text  = $value;
	$length=70;
	
  if(strlen($text)<=$length)
  {
    return $text;
  }
  else
  {
    $y=substr($text,0,$length) . '....';
    return $y;
  }
    
}
public function decodePostTitle($value = '',$id = '',$data = array()){
   return base64_decode($value);
    
}
public function decodePostTitleInForm($mode = '', $value = '', $data = array(), $id = '',$field_name = '', $field_id = ''){
  
    $this->db->select('vDescription');
    $this->db->from('add_review');
    $this->db->where('iUserId',$value);
    $post_data=$this->db->get()->result_array();
    return base64_decode($post_data[0]['vDescription']);
  
}
}
?>
