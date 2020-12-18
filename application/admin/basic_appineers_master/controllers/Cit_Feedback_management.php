<?php


/**
 * Description of Feedback Management Extended Controller
 * 
 * @module Extended Feedback Management
 * 
 * @class Cit_Feedback_management.php
 * 
 * @path application\admin\basic_appineers_master\controllers\Cit_Feedback_management.php
 * 
 * @author CIT Dev Team
 * 
 * @date 01.02.2020
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Feedback_management extends Feedback_management {
        public function __construct()
{
    parent::__construct();
}
public function get_limit_characters($value = '',$id = '',$data = array()){
	$text=$value;
	$length=60;
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
public function showStatusButton($id='',$arr=array())
{	    
        $url = $this->general->getAdminEncodeURL('basic_appineers_master/feedback_management/add').'|mode|'.$this->general->getAdminEncodeURL('Update').'|id|'.$this->general->getAdminEncodeURL($arr);
       return '<button type="button" data-id='.$arr.' class="btn btn-success operBut" data-url='.$url.' >Edit</button>';
  		
}
public function format_queryText($mode = '', $value = '', $data = array(), $id = '',$field_name = '', $field_id = ''){
	return nl2br(trim($value));
}
public function get_Limit_characters_feedback($value = '',$id = '',$data = array()){
    $text=$value;
	$length=30;
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
public function replaceNull($value = '',$id = '',$data = array()){
    if(empty($value)){
        return "";
    }else{
        return $value;
    }
 
}
}
