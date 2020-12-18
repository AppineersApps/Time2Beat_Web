<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of City Model
 *
 * @category webservice
 *
 * @package tools
 *
 * @subpackage models
 *
 * @module City
 *
 * @class City_model.php
 *
 * @path application\webservice\tools\models\City_model.php
 *
 * @version 4.2
 *
 * @author CIT Dev Team
 *
 * @since 23.02.2017
 */

class City_model extends CI_Model
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
     * get_city_list method is used to execute database queries for Country With States API.
     * @created CIT Admin | 19.12.2016
     * @modified CIT Admin | 19.12.2016
     * @param string $ms_state_id ms_state_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_city_list($ms_state_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("mod_city AS mc");

            $this->db->select("mc.iCityId AS mc_city_id");
            $this->db->select("mc.vCity AS mc_city");
            $this->db->select("mc.vCityCode AS mc_city_code");
            if (isset($ms_state_id) && $ms_state_id != "")
            {
                $this->db->where("mc.iStateId =", $ms_state_id);
            }

            $this->db->limit(1);

            $result_obj = $this->db->get();
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            if (!is_array($result_arr) || count($result_arr) == 0)
            {
                throw new Exception('No records found.');
            }
            $success = 1;
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
