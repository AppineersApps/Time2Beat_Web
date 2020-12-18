<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Navigation Log Model
 *
 * @category admin
 * 
 * @package general
 *  
 * @subpackage models
 *  
 * @module NavigationLog
 * 
 * @class Navigation_model.php
 * 
 * @path application\admin\general\models\Navigation_model.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Navigation_model extends CI_Model
{

    public $main_table;
    public $table_alias;
    public $primary_key;
    public $ids_arr = array();

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('filter');
        $this->main_table = "mod_admin_navigation_log";
        $this->table_alias = 'manl';
        $this->primary_key = "iNavigationId";
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
     * @return boolean $res returns true or false.
     */
    public function update($data = array(), $where = '')
    {
        if (intval($where) > 0) {
            $this->db->where($this->primary_key, $where);
        } else {
            $this->db->where($where, FALSE, FALSE);
        }
        return $this->db->update($this->main_table, $data);
    }

    /**
     * delete method is used to delete data records from the database table.
     * @param string $where where is the query condition for deletion.
     * @return boolean $res returns true or false.
     */
    public function delete($where = "", $limit = '')
    {
        if (intval($where) > 0) {
            $this->db->where($this->primary_key, $where);
        } else {
            $this->db->where($where, FALSE, FALSE);
        }
        if (intval($limit) > 0) {
            $this->db->limit($limit);
        }
        return $this->db->delete($this->main_table);
    }

    /**
     * getData method is used to get data records for this module.
     * @param string $extra_cond extra_cond is the query condition for getting filtered data.
     * @param string $fields fields are either array or string.
     * @param string $order_by order_by is to append order by condition.
     * @param string $group_by group_by is to append group by condition.
     * @param string $limit limit is to append limit condition.
     * @param string $join join is to make joins with relation tables.
     * @return array $data_arr returns data records array.
     */
    public function getData($extracond = "", $fields = "", $order_by = "", $group_by = "", $limit = "", $join = "No")
    {
        if (is_array($fields)) {
            $this->general->addSelectFields($fields);
        } elseif ($fields != '') {
            $this->db->select($fields);
        } else {
            $this->db->select($this->table_alias . ".*");
        }

        $this->db->from($this->main_table . " AS " . $this->table_alias);

        if (is_array($extracond) && count($extracond) > 0) {
            foreach ($extracond as $val) {
                $this->db->where($val['field'], $val['value']);
            }
        } elseif ($extracond != "") {
            if (intval($extracond)) {
                $this->db->where($this->table_alias . "." . $this->primary_key, $extracond);
            } else {
                $this->db->where($extracond, FALSE, FALSE);
            }
        }
        if ($group_by != "") {
            $this->db->group_by($group_by);
        }
        if ($order_by != "") {
            $this->db->order_by($order_by);
        }
        if ($limit != "") {
            list($offset, $limit) = explode(",", $limit);
            $this->db->limit($offset, $limit);
        }
        $list_obj = $this->db->get();
        return (is_object($list_obj) ? $list_obj->result_array() : array());
    }

    public function getUserData()
    {
        $this->db->select("iAdminId,vName");
        $this->db->from('mod_admin');
        $user_obj = $this->db->get();
        return (is_object($user_obj) ? $user_obj->result_array() : array());
    }
}
