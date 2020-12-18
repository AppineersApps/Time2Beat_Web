<?php

                                
/**
 * Description of Reset Password Extended Model
 * 
 * @module Extended Reset Password
 * 
 * @class Cit_Reset_password_model.php
 * 
 * @path application\webservice\basic_appineers_master\models\Cit_Reset_password_model.php
 * 
 * @author CIT Dev Team
 * 
 * @date 16.09.2019
 */
   
Class Cit_Reset_password_model extends Reset_password_model {
        public function __construct()
{
    parent::__construct();
}
public function getResetPasswordKey($reset_key){
    $this->db->select('vResetPasswordCode,iUserId');
    $this->db->from('users');
    $this->db->where('vResetPasswordCode',$reset_key);
    $data=$this->db->get()->result_array();
   
    return $data;
}
}
