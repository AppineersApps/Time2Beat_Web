<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of BulkEmail Model
 *
 * @category admin
 * 
 * @package tools
 *  
 * @subpackage models
 * 
 * @module BulkEmail
 * 
 * @class Bulkemail_model.php
 * 
 * @path application\admin\tools\models\Bulkemail_model.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Bulkemail_model extends CI_Model
{

    public $table_name = "";

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'mod_group_master';
    }

    /**
     * getGroupDetails method is used to get all group details.
     * @return array $db_email returns group data records array.
     */
    public function getGroupDetails()
    {
        $this->db->select('iGroupId AS Id, vGroupName AS Val');
        $db_email_obj = $this->db->get($this->table_name);
        return (is_object($db_email_obj) ? $db_email_obj->result_array() : array());
    }

    /**
     * getEmailTemplateVariables method is used to get system email template variables.
     * @return array $temp_var_arr returns variables data records array.
     */
    public function getEmailTemplateVariables($email_code = "")
    {
        $this->db->select('msev.vVarName, msev.vVarDesc');
        $this->db->join('mod_system_email AS mse', 'mse.iEmailTemplateId = msev.iEmailTemplateId', 'inner');
        $this->db->where('mse.vEmailCode', $email_code);
        $temp_var_obj = $this->db->get('mod_system_email_vars AS msev');
        return (is_object($temp_var_obj) ? $temp_var_obj->result_array() : array());
    }
}
