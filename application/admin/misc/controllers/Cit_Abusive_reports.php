<?php
            
/**
 * Description of Abusive Reports Extended Controller
 * 
 * @module Extended Abusive Reports
 * 
 * @class Cit_Abusive_reports.php
 * 
 * @path application\admin\misc\controllers\Cit_Abusive_reports.php
 * 
 * @author CIT Dev Team
 * 
 * @date 02.05.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Abusive_reports extends Abusive_reports {
        public function __construct()
{
    parent::__construct();
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
}
?>
