<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of NS Engine Values Model
 *
 * @category notification
 * 
 * @package nsengine
 *  
 * @subpackage models
 * 
 * @module NS Engine
 * 
 * @class Notify_values_model.php
 * 
 * @path application\front\nsengine\models\Notify_values_model.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Notify_values_model extends CI_Model
{

    public $main_table;
    public $table_alias;

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->main_table = "mod_notify_operation_values";
        $this->table_alias = "mnop";
        $this->primary_key = "iOperationScheduleId";
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
    public function update($data = array(), $where = '')
    {
        if (is_numeric($where) > 0) {
            $this->db->where($this->primary_key, $where);
        } else {
            $this->db->where($where, FALSE, FALSE);
        }
        return $this->db->update($this->main_table, $data);
    }

    /**
     * delete method is used to delete data records from the database table.
     * @param string $where where is the query condition for deletion.
     * @return boolean $res returns TRUE or FALSE.
     */
    public function delete($where = "")
    {
        if (is_numeric($where) > 0) {
            $this->db->where($this->primary_key, $where);
        } else {
            $this->db->where($where, FALSE, FALSE);
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
    public function getData($extra_cond = "", $fields = "", $order_by = "", $group_by = "", $limit = "", $join = "Yes")
    {
        if (is_array($fields)) {
            for ($i = 0; $i < count($fields); $i++) {
                if (is_array($fields[$i])) {
                    $escape = (isset($fields[$i]['escape']) && $fields[$i]['escape'] === TRUE) ? FALSE : NULL;
                    $this->db->select($fields[$i]['field'], $escape);
                } else {
                    $this->db->select($fields[$i]);
                }
            }
        } elseif ($fields != "") {
            $this->db->select($fields);
        } else {
            $this->db->select($this->table_alias . ".*");
        }
        $this->db->from($this->main_table . " AS " . $this->table_alias);
        if ($join == "Yes") {
            $this->db->join("mod_notify_schedule AS mns", "mns.iNotifyScheduleId = mnop.iNotifyScheduleId", "inner");
        }
        if (is_array($extra_cond) && count($extra_cond) > 0) {
            for ($i = 0; $i < count($extra_cond); $i++) {
                $escape = (isset($extra_cond[$i]['escape']) && $extra_cond[$i]['escape'] === TRUE) ? FALSE : NULL;
                if (is_array($extra_cond[$i]['value'])) {
                    $this->db->where_in($extra_cond[$i]['field'], $extra_cond[$i]['value'], $escape);
                } else {
                    $this->db->where($extra_cond[$i]['field'], $extra_cond[$i]['value'], $escape);
                }
            }
        } elseif ($extra_cond != "") {
            if (is_numeric($extra_cond)) {
                $this->db->where($this->table_alias . "." . $this->primary_key, $extra_cond);
            } else {
                $this->db->where($extra_cond, FALSE, FALSE);
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
}
