<?php
defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Description of User Controller
 *
 * @category front
 *            
 * @package user
 * 
 * @subpackage controllers
 * 
 * @module User
 * 
 * @class User.php
 * 
 * @path application\front\user\controllers\User.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
 class Verified extends Cit_Controller
 {
 	public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('cit_api_model');
    }
      /**
     * index method is used to define home page content.
     */
    public function index()
    {
        $view_file = "welcome_message";
        $this->loadView($view_file);
    }
    //1153
    public function activate_user()
    {
    
        $message =  '';
        $error   =  0;

        $get_var_code = $this->input->get('code');

        if($get_var_code == '')
        {
           $error = 1;
           $message = 'No Account Found.'; 
        }else
        {
            
            $input_params['confirmation_code'] = $get_var_code;
            $result = $this->cit_api_model->callAPI("user_email_confirmation", $input_params);


            if($result['settings']['success'] == 1) {
                $message = $result['settings']['message'];
            } else {
                $error = 1;
                $message = $result['settings']['message'];
            }
        }
        
        $this->smarty->assign('error',$error);
        $this->smarty->assign('message',$message);
    }
    public function reset_password(){
         $get_code = $this->input->get('code');
         $input_params['reset_key'] = $get_code;
         $result = $this->cit_api_model->callAPI("reset_password_confirmation",$input_params);
         if($result['settings']['success']==1){
            $status=$result['settings']['success'];
            $message='';
         }else{
            $status=$result['settings']['success'];
            $message=$result['settings']['message'];
         }
        $this->smarty->assign('code',$get_code);
        $this->smarty->assign('status',$status);
        $this->smarty->assign('message',$message);
    }
    public function reset_password_action(){
        $post_values = $this->input->post();
       
        $output = array('message'=>'','success'=>'1');
        
        if(empty($post_values)) 
        {
             $this->session->set_flashdata('failure','Invalid Data');
             redirect($this->config->item('site_url')."resetpassword?code=".$post_values['code']);
        }else
        {
            
                $input_params['new_password'] =$post_values['new_password'];
                $input_params['reset_key'] = $post_values['code'];
               
                $result = $this->cit_api_model->callAPI("reset_password", $input_params);
                if($result['settings']['success'] == 0) 
                {    
                    $this->session->set_flashdata('failure','Problem while reseting password try again.');
                    redirect($this->config->item('site_url')."resetpassword?code=".$post_values['code']);   
                }else
                {
                    $this->session->set_flashdata('success','Password updated successfully.');
                    redirect($this->config->item('site_url'));
                }
            
        }
    }
 }