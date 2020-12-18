<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Log History Model
 *
 * @category models
 *            
 * @package tools 
 * 
 * @module Log History
 * 
 * @class loghistory.php
 * 
 * @path application\models\tools\loghistory.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Loghistory extends CI_Model
{

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * insert method is used to insert data records to the database table.
     * @param array $data data array for insert into table.
     * @return numeric $insert_id returns last inserted id.
     */
    public function insert($data = array())
    {
        $insertId = $this->db->insert('mod_log_history', $data);
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
            $this->db->where('iLogId', $where);
        } else {
            $this->db->where($where, FALSE, FALSE);
        }
        return $this->db->update('mod_log_history', $data);
    }

    /**
     * getLogHistory method is used to get user log history.
     * @param string $admin_id admin_id is the query condition for filtering.
     * @return array $data_arr returns data records array.
     */
    public function getLogHistory($admin_id = '')
    {
        $this->db->select('iUserId, vIP, eUserType, dLoginDate, dLogoutDate');
        $this->db->from('mod_log_history');
        $this->db->where('mod_log_history.iUserId', $admin_id);
        $this->db->order_by('dLoginDate', 'DESC');
        $this->db->limit(1);
        $data_obj = $this->db->get();
        return (is_object($data_obj) ? $data_obj->result_array() : array());
    }

    /**
     * updateLogoutUser method is used to update data records to the database table.
     * @param integer $id id is log id for updating history.
     * @return boolean $res returns true or false.
     */
    public function updateLogoutUser($id = '')
    {
        if ($id > 0) {
            $this->db->set('dLogoutDate', date('Y-m-d H:i:s'));
            $this->db->where('iLogId', $id);
            return $this->db->update('mod_log_history');
        } else {
            return FALSE;
        }
    }
}
