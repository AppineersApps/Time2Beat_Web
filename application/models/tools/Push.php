<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Push Notification Model
 *
 * @category models
 *            
 * @package tools
 *  
 * @module PushNotify
 * 
 * @class Push.php
 * 
 * @path application\models\tools\Push.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Push extends CI_Model
{

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * insertPushNotify method is used to insert data records to the database table.
     * @param array $data data array for insert into table.
     * @return numeric $insert_id returns last inserted id.
     */
    public function insertPushNotify($insert_arr = array())
    {
        $this->db->insert("mod_push_notifications", $insert_arr);
        return $this->db->insert_id();
    }

    /**
     * updatePushNotify method is used to update data records to the database table.
     * @param array $data data array for update into table.
     * @param string $where where is the query condition for updating.
     * @return boolean $res returns TRUE or FALSE.
     */
    public function updatePushNotify($data = array(), $where = '')
    {
        if (is_numeric($where)) {
            $this->db->where("iPushNotifyId", $where);
        } elseif ($where) {
            $this->db->where($where, FALSE, FALSE);
        } else {
            return FALSE;
        }
        return $this->db->update("mod_push_notifications", $data);
    }

    /**
     * getPushNotify method is used to get data records for this module.
     * @param string $extra_cond extra_cond is the query condition for getting filtered data.
     * @param string $fields fields are either array or string.
     * @param string $order_by order_by is to append order by condition.
     * @param string $group_by group_by is to append group by condition.
     * @param string $limit limit is to append limit condition.
     * @param string $join join is to make joins with relation tables.
     * @return array $data_arr returns data records array.
     */
    public function getPushNotify($extra_cond = "", $fields = "", $order_by = "", $group_by = "", $limit = "", $join = "No")
    {
        if (is_array($fields) || $fields != "") {
            $this->db->select($fields);
        } else {
            $this->db->select("mpn.*");
        }
        $this->db->from("mod_push_notifications AS mpn");

        if (is_array($extra_cond) && count($extra_cond) > 0) {
            foreach ($extra_cond as $key => $val) {
                $this->db->where($val['field'], $val['value']);
            }
        } elseif (is_numeric($extra_cond)) {
            $this->db->where("mpn.iPushNotifyId", intval($extra_cond));
        } elseif ($extra_cond) {
            $this->db->where($extra_cond, FALSE, FALSE);
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
        $data_obj = $this->db->get();
        return (is_object($data_obj) ? $data_obj->result_array() : array());
    }
}
