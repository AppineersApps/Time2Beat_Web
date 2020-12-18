<?php

   
/**
 * Description of Notification List Extended Controller
 * 
 * @module Extended Notification List
 * 
 * @class Cit_Notification_list.php
 * 
 * @path application\webservice\notifications\controllers\Cit_Notification_list.php
 * 
 * @author CIT Dev Team
 * 
 * @date 11.06.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Notification_list extends Notification_list {
        public function __construct()
{
    parent::__construct();
}
public function format_images(&$input_params)
{
   if(!empty($input_params['get_image'][0]['ui_image']))
   {
       $input_params['from_image'] = $input_params['get_image'][0]['ui_image'];
   }
}
}
