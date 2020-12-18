<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of User Model
 *
 * @category front
 * 
 * @package tools
 *  
 * @subpackage models
 *  
 * @module User
 * 
 * @class User_model.php
 * 
 * @path application\front\user\models\User_model.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class User_model extends CI_Model
{

    private $primary_key;
    private $main_table;
    private $table_alias;

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('listing');
        $this->main_table = "mod_customer";
        $this->table_alias = "mc";
        $this->load->helper('date');
        $this->primary_key = "iCustomerId";
    }

    /**
     * insert method is used to insert data records to the database table.
     * @param array $data data array for insert into table.
     * @return numeric $insert_id returns last inserted id.
     */
    public function insert($data = array())
    {
        $this->db->insert($this->main_table, $data);
        return $this->db->insert_id();
    }

    /**
     * update method is used to update data records to the database table.
     * @param array $data data array for update into table.
     * @param string $where where is the query condition for updating.
     * @return boolean $res returns TRUE or FALSE.
     */
    public function update($data = array(), $where)
    {
        if (is_numeric($where)) {
            $this->db->where($this->primary_key, $where);
        } elseif ($where) {
            $this->db->where($where, FALSE, FALSE);
        } else {
            return false;
        }
        return $this->db->update($this->main_table, $data);
    }

    /**
     * getData method is used to get data records for user.
     * @param string $extra_cond $email is the query condition for getting filtered data.
     * @param string $fields fields are either array or string.
     * @param string $order_by order_by is to append order by condition.
     * @param string $group_by group_by is to append group by condition.
     * @param string $limit limit is to append limit condition.
     * @return array $data_arr returns data records array.
     */
    public function getData($extra_cond = "", $fields = "", $order_by = "", $group_by = "", $limit = "")
    {
        if (is_array($fields)) {
            $this->general->addSelectFields($fields);
        } elseif ($fields != "") {
            $this->db->select($fields);
        } else {
            $this->db->select("iCustomerId,vFirstName,vLastName,vEmail,vUserName,vPassword,eStatus,eEmailVerified,eAuthType,vAuthCode,vPhonenumber");
        }
        $this->db->from($this->main_table . " AS " . $this->table_alias);

        if (is_array($extra_cond) && count($extra_cond) > 0) {
            foreach ($extra_cond as $key => $val) {
                $this->db->where($val['field'], $val['value']);
            }
        } elseif (is_numeric($extra_cond)) {
            $this->db->where($this->table_alias . "." . $this->primary_key, intval($extra_cond));
        } elseif ($extra_cond) {
            $this->db->where($extra_cond, FALSE, FALSE);
        }

        $this->general->getPhysicalRecordWhere($this->main_table, $this->table_alias, "AR");
        if ($group_by != "") {
            $this->db->group_by($group_by);
        }
        if ($order_by != "") {
            $this->db->order_by($order_by);
        }
        if ($limit != "") {
            if (is_numeric($limit)) {
                $this->db->limit($limit);
            } else {
                list($offset, $limit) = explode(",", $limit);
                $this->db->limit($offset, $limit);
            }
        }
        $data_obj = $this->db->get();
        return (is_object($data_obj) ? $data_obj->result_array() : array());
    }

    /**
     * checkUserExists method is used to check user email or username exists or not.
     * @param string $usertype usertype is to check whether email or username.
     * @param array $user_arr user_arr array for checking user info.
     * @return boolean flag returns true or false.
     */
    public function checkUserExists($usertype = '', $user_arr = array())
    {
        if ($usertype == '' || !isset($user_arr[$usertype]) || trim($user_arr[$usertype]) == '') {
            return false;
        }
        $value = trim($user_arr[$usertype]);
        $this->db->select($usertype);
        $this->db->from($this->main_table);
        $this->db->where($usertype, $value);
        if (isset($user_arr['userId'])) {
            $this->db->where($this->primary_key . " <>", $user_arr['userId']);
        }
        $user_obj = $this->db->get();
        $user_data = is_object($user_obj) ? $user_obj->result_array() : array();
        if (!is_array($user_data) || count($user_data) == 0) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * authenticate method is used to authenticate user.
     * @param string $user_name user_name is name passed for authentification.
     * @param string $password password is name passed for authentification.
     * @return array $data_arr returns success/failre status and related message array.
     */
    public function authenticate($user_name = '', $password = '')
    {
        $this->db->select('iCustomerId, vFirstName, vLastName, vEmail, vUserName, eStatus, vPassword, eAuthType');
        $this->db->from($this->main_table);
        $this->db->group_start();
        $this->db->where('vUserName', $user_name);
        $this->db->or_where('vEmail', $user_name);
        $this->db->group_end();
        if($password !== FALSE){
            $this->db->where('vPassword', $password);
        } 
        $result_obj = $this->db->get();
        return (is_object($result_obj) ? $result_obj->result_array() : array());
    }
}
