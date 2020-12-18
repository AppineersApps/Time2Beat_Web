<?php

                                
/**
 * Description of Reset Password Confirmation Extended Model
 * 
 * @module Extended Reset Password Confirmation
 * 
 * @class Cit_Reset_password_confirmation_model.php
 * 
 * @path application\webservice\basic_appineers_master\models\Cit_Reset_password_confirmation_model.php
 * 
 * @author CIT Dev Team
 * 
 * @date 03.10.2019
 */
   
Class Cit_Reset_password_confirmation_model extends Reset_password_confirmation_model {
        public function __construct()
{
    parent::__construct();
}
public function getResetPasswordKey($reset_key){
    $this->db->select('vResetPasswordCode');
    $this->db->from('users');
    $this->db->where('vResetPasswordCode',$reset_key);
    $data=$this->db->get()->row_array();
    return $data;
}
}
