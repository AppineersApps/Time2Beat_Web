<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Rest Model
 *
 * @category webservice
 *            
 * @package rest
 * 
 * @subpackage models
 * 
 * @module Rest
 * 
 * @class Rest_model.php
 * 
 * @path application\webservice\rest\models\Rest_model.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Rest_model extends CI_Model
{

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * insertToken method is used to insert data records to the database table.
     * @param array $data data array for insert into table.
     * @return numeric $insert_id returns last inserted id.
     */
    public function insertToken($data = array())
    {
        $this->db->insert("mod_ws_token", $data);
        return $this->db->insert_id();
    }

    /**
     * updateToken method is used to update data records to the database table.
     * @param array $data data array for update into table.
     * @param string $where where is the query condition for updating.
     * @return boolean $res returns TRUE or FALSE.
     */
    public function updateToken($data = array(), $where = '')
    {
        if (is_numeric($where)) {
            $this->db->where("iWSTokenId", $where);
        } elseif ($where) {
            $this->db->where($where, FALSE, FALSE);
        } else {
            return FALSE;
        }
        return $this->db->update("mod_ws_token", $data);
    }

    /**
     * getToken method is used to get data records for this module.
     * @param string $extra_cond extra_cond is the query condition for getting filtered data.
     * @param string $fields fields are either array or string.
     * @param string $order_by order_by is to append order by condition.
     * @param string $group_by group_by is to append group by condition.
     * @param string $limit limit is to append limit condition.
     * @param string $join join is to make joins with relation tables.
     * @return array $data_arr returns data records array.
     */
    public function getToken($extra_cond = "", $fields = "", $order_by = "", $group_by = "", $limit = "", $join = "No")
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
            $fields = array('mwt.vWSToken', 'mwt.vIPAddress', 'mwt.vUserAgent', 'mwt.dLastAccess', 'mwt.eStatus');
            $this->db->select($fields);
        }
        $this->db->from("mod_ws_token AS mwt");

        if (is_array($extra_cond) && count($extra_cond) > 0) {
            for ($i = 0; $i < count($extra_cond); $i++) {
                $escape = (isset($extra_cond[$i]['escape']) && $extra_cond[$i]['escape'] === TRUE) ? FALSE : NULL;
                if (is_array($extra_cond[$i]['value'])) {
                    $this->db->where_in($extra_cond[$i]['field'], $extra_cond[$i]['value'], $escape);
                } else {
                    $this->db->where($extra_cond[$i]['field'], $extra_cond[$i]['value'], $escape);
                }
            }
        } elseif (is_numeric($extra_cond)) {
            $this->db->where("mwt.iWSTokenId", intval($extra_cond));
        } elseif ($extra_cond) {
            $this->db->where($extra_cond, FALSE, FALSE);
        }

        if ($group_by != "") {
            $this->db->group_by($group_by);
        }
        if ($order_by != "") {
            $this->db->order_by($order_by);
        } else {
            $this->db->order_by("mwt.dLastAccess", "DESC");
        }
        if ($limit != "") {
            list($offset, $limit) = explode(",", $limit);
            $this->db->limit($offset, $limit);
        }
        $data_obj = $this->db->get();
        return (is_object($data_obj) ? $data_obj->result_array() : array());
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
            return false;
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
            $fields = array(
                "mpn.iPushNotifyId", "mpn.vUniqueId", "mpn.vDeviceId", "mpn.eNotifyCode", "mpn.vSound", "mpn.vBadge", "mpn.vTitle",
                "mpn.tError", "mpn.tMessage", "mpn.tVarsJSON", "mpn.dtAddDateTime", "mpn.dtExeDateTime", "mpn.eStatus"
            );
            $this->db->select($fields);
        }
        $this->db->from("mod_push_notifications AS mpn");

        if (is_array($extra_cond) && count($extra_cond) > 0) {
            for ($i = 0; $i < count($extra_cond); $i++) {
                $escape = (isset($extra_cond[$i]['escape']) && $extra_cond[$i]['escape'] === TRUE) ? FALSE : NULL;
                if (is_array($extra_cond[$i]['value'])) {
                    $this->db->where_in($extra_cond[$i]['field'], $extra_cond[$i]['value'], $escape);
                } else {
                    $this->db->where($extra_cond[$i]['field'], $extra_cond[$i]['value'], $escape);
                }
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
    /**
     * insertAccessLog method is used to insert data records for this module.
     * @param string $data contains acces log data.
     */
    public function insertAccessLog($data)
    {
        $rows = $this->db->insert_batch("mod_api_accesslog", $data);
        return $rows;
    }
    
    /**
     * deleteAccessLog method is used to delete data records of particular date from this module.
     * @param string $date contains acces log data.
     */
    public function deleteAccessLog($date)
    {
        $this->db->where("DATE(dAccessDate) = '$date'", FALSE, FALSE);
        $this->db->delete("mod_api_accesslog");
    }
}
