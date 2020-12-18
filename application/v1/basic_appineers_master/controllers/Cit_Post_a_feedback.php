<?php

   
/**
 * Description of Post a Feedback Extended Controller
 * 
 * @module Extended Post a Feedback
 * 
 * @class Cit_Post_a_feedback.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_Post_a_feedback.php
 * 
 * @author CIT Dev Team
 * 
 * @date 16.09.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Post_a_feedback extends Post_a_feedback {
        public function __construct()
{
    parent::__construct();
}
public function uploadQueryImages($input_params=array()){
    $user_id=$input_params['user_id'];
    $img_name="image_";
    $query_id=$input_params['query_id'];
    $folder_name="public/upload/query_images/".$query_id."/";
  
    $return_arr = array();
    $insert_arr = array();
    $temp_var   = 0;
    $upper_limit = 3;
    
	if($input_params['images_count'] > 0)
	{
		$upper_limit = $input_params['images_count'];
	}
	
   
    for($i=1; $i<=$upper_limit; $i++)
    {
        $new_file_name=$img_name.$i;
		
		if($_FILES[$new_file_name]['name']!='')
		{
		    
		    $temp_file 		= $_FILES[$new_file_name]['tmp_name'];
			$image_name 	= $_FILES[$new_file_name]['name'];
			list($file_name, $extension) 	= $this->general->get_file_attributes($image_name);
			$res = $this->general->file_upload($folder_name, $temp_file, $file_name);
	
			if($res)
			{
			    $insert_arr[$temp_var]['iUserQueryId']=$query_id;
			    $insert_arr[$temp_var]['vQueryImage']=$file_name;
			    $insert_arr[$temp_var]['dtAddedAt']=date('Y-m-d H:i:s');
			    $insert_arr[$temp_var]['eStatus']="Active";
			    $temp_var++;
			}
		
		}
	}

   if(is_array($insert_arr) && !empty($insert_arr))
	{
		$this->db->insert_batch("user_query_images",$insert_arr);
	}
	
	
	$return["success"]	= true;
	return $return;
    
}
}
