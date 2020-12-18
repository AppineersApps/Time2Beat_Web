<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Dashboard Pages Model
 * 
 * @category admin
 * 
 * @package dashboard
 *  
 * @subpackage models
 *
 * @module Dashboard
 * 
 * @class Dashboardpages_model.php
 * 
 * @path application\admin\dashboard\models\Dashboardpages_model.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Dashboardpages_model extends CI_Model
{

    public $table_name;
    public $table_alias;
    public $primary_key;
    public $primary_alias;
    public $rec_per_page;

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('listing');
        $this->load->library('filter');
        $this->table_name = "mod_admin_dashboard_pages";
        $this->table_alias = "madp";
        $this->primary_key = "iDashBoardPageId";
        $this->primary_alias = "madp_dash_board_page_id";
        $this->rec_per_page = $this->config->item('REC_LIMIT');
    }

    /**
     * insert method is used to insert data records to the database table.
     * @param array $data data array for insert into table.
     * @return numeric $insert_id returns last inserted id.
     */
    public function insert($data = array())
    {
        $this->db->insert($this->table_name, $data);
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
        if (is_numeric($where)) {
            $this->db->where($this->primary_key, $where);
        } else {
            $this->db->where($where, FALSE, FALSE);
        }
        return $this->db->update($this->table_name, $data);
    }

    /**
     * delete method is used to delete data records from the database table.
     * @param string $where where is the query condition for deletion.
     * @return boolean $res returns TRUE or FALSE.
     */
    public function delete($where = "")
    {
        if (is_numeric($where)) {
            $this->db->where($this->primary_key, $where);
        } else {
            $this->db->where($where, FALSE, FALSE);
        }
        return $this->db->delete($this->table_name);
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
    public function getData($extra_cond = "", $fields = "", $order_by = "", $group_by = "", $limit = "", $join = "No", $assoc_field = '')
    {
        if (is_array($fields)) {
            $this->listing->addSelectFields($fields);
        } elseif ($fields != "") {
            $this->db->select($fields);
        } else {
            $this->db->select($this->table_alias . ".*");
        }
        $this->db->from($this->table_name . " AS " . $this->table_alias);
        if (is_array($extra_cond) && count($extra_cond) > 0) {
            $this->listing->addWhereFields($extra_cond);
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
        $list_data = is_object($list_obj) ? $list_obj->result_array() : array();
        if ($assoc_field != '' && is_array($list_data) && count($list_data) > 0) {
            $final_array = array();
            foreach ($list_data as $data) {
                $final_array[$data[$assoc_field]][] = $data;
            }
            $list_data = $final_array;
        }
        return $list_data;
    }
}
