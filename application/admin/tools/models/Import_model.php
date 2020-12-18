<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Import Model
 *
 * @category admin
 * 
 * @package tools
 *  
 * @subpackage models
 *
 * @module Import
 * 
 * @class Import_model.php
 * 
 * @path application\admin\tools\models\Import_model.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Import_model extends CI_Model
{

    public $main_table = "";

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * getTableRowCount method is used to get total records count of table.
     * @param array $table table name to get count.
     * @return integer $result returns integer response.
     */
    public function getTableRowCount($table = '')
    {
        return $this->db->count_all($table);
    }

    /**
     * truncateTable method is used to import records into database.
     * @param array $table table name to insert data.
     * @return array $result returns array data.
     */
    public function getTableUniqueRows($table = '', $unique = array())
    {
        $this->db->from($table);
        $this->db->select($unique);
        $data = $this->db->get();
        return (is_object($data) ? $data->result_array() : array());
    }

    /**
     * getTableLookupRows method is used to import records into database.
     * @param array $table table name to get data.
     * @param array $field field name to lookup field.
     * @return array $result returns array data.
     */
    public function getTableLookupRows($table = '', $key_field = '', $val_field = '')
    {
        $data = $this->db->select_combo($table, $key_field, $val_field);
        return (is_object($data) ? $data->result_combo_array() : array());
    }

    /**
     * copyTable method is used to import records into database.
     * @param array $table table name to copy data.
     * @param array $temp temp name to insert data.
     * @return array $result returns boolean response, either TRUE or FALSE.
     */
    public function copyTable($table = '', $temp = '')
    {
        $result = FALSE;
        if (!$this->db->table_exists($temp)) {
            if ($this->db->query("CREATE TABLE " . $this->db->protect($temp) . " LIKE " . $this->db->protect($table))) {
                if ($this->db->query("INSERT " . $this->db->protect($temp) . " SELECT * FROM " . $this->db->protect($table))) {
                    $result = $this->truncateTable($table);
                }
            }
        }
        return $result;
    }

    /**
     * truncateTable method is used to import records into database.
     * @param array $table table name to insert data.
     * @return array $result returns boolean response, either TRUE or FALSE.
     */
    public function truncateTable($table = '')
    {
        return $this->db->query("TRUNCATE TABLE " . $this->db->protect($table));
    }

    /**
     * truncateTable method is used to import records into database.
     * @param array $table table name to insert data.
     * @return array $result returns boolean response, either TRUE or FALSE.
     */
    public function revertTable($table = '', $temp = '')
    {
        $this->db->query("INSERT " . $this->db->protect($table) . " SELECT * FROM " . $this->db->protect($temp));
        $this->db->query("DROP TABLE " . $this->db->protect($temp));
        return TRUE;
    }

    /**
     * getPrimaryMaximum method is used to get max value of primary key.
     * @param array $table table name to fetch data.
     * @param array $primary primary key to fetch data.
     * @return integer $result returns integer response.
     */
    public function getPrimaryMaximum($table = '', $primary = '')
    {
        $this->db->from($table);
        $this->db->select_max($primary, 'max_auto');
        $data = $this->db->get();
        $result = (is_object($data)) ? $data->result_array() : array();
        if ($result[0]['max_auto'] > 0) {
            $result = $result[0]['max_auto'];
        } else {
            $result = 1;
        }
        return $result;
    }

    /**
     * resetAutoIncrement method is used to import records into database.
     * @param array $table table name to reset auto increment.
     * @param array $value value to set auto increment value.
     * @return array $result returns boolean response, either TRUE or FALSE.
     */
    public function resetAutoIncrement($table = '', $value = '')
    {
        $result = FALSE;
        if ($this->db->dbdriver == "mysqli") {
            $auto_inc = intval($value);
            if ($auto_inc > 0) {
                $result = $this->db->query("ALTER TABLE " . $this->db->protect($table) . " AUTO_INCREMENT = " . $auto_inc);
            }
        }
        return $result;
    }

    /**
     * startTransaction method is used to start transaction in database.
     */
    public function startTransaction()
    {
        $this->db->trans_begin();
    }

    /**
     * rollbackTransaction method is used to roolback transaction in database.
     */
    public function rollbackTransaction()
    {
        $this->db->trans_rollback();
    }

    /**
     * commitTransaction method is used to commit transaction in database.
     */
    public function commitTransaction()
    {
        $this->db->trans_commit();
    }

    /**
     * completeTransaction method is used to complete transaction in database.
     */
    public function completeTransaction()
    {
        if ($this->db->trans_status() === FALSE) {
            $this->rollbackTransaction();
        } else {
            $this->commitTransaction();
        }
    }

    /**
     * importBatch method is used to import records into database.
     * @param array $table table name to insert data.
     * @param array $data data array for insert into table.
     * @return array $result returns affected rows response, returns -1 on failure.
     */
    public function importBatch($table = '', $data = array())
    {
        return $this->db->insert_batch($table, $data);
    }

    /**
     * updateTable method is used to import records into database.
     * @param array $table table name to insert data.
     * @param array $data data array for insert into table.
     * @return array $result returns affected rows response, returns -1 on failure.
     */
    public function updateTable($table = '', $data = array(), $where = array())
    {
        if (!is_array($where) || count($where) == 0) {
            return FALSE;
        }
        foreach ($where as $key => $val) {
            $this->db->where($key, $val);
        }
        return $this->db->update($table, $data);
    }
}
