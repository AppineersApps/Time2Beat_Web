<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of  Model
 *
 * @category webservice
 *
 * @package others
 *
 * @subpackage models
 *
 * @module
 *
 * @class _model.php
 *
 * @path application\webservice\others\models\_model.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 12.09.2019
 */

class _model extends CI_Model
{
    public $default_lang = 'EN';

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('listing');
        $this->default_lang = $this->general->getLangRequestValue();
    }

    /**
     * query method is used to execute database queries for User Sign Up Email API.
     * @created priyanka chillakuru | 12.09.2019
     * @modified ---
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function query($params_arr = array())
    {
        try
        {
            $result_arr = array();
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }
}
