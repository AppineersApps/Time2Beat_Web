<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Archive Tables Schedule Model
 *
 * @category nsengine
 * 
 * @package nsengine
 *  
 * @subpackage models
 * 
 * @module NS Engine
 * 
 * @class Archive_schedule_model.php
 * 
 * @path application\front\nsengine\models\Archive_schedule_model.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 22.05.2018
 */
class Archive_schedule_model extends CI_Model
{

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * archiveTable method is used to archive the database table.
     * @param array $source source is the table name to archive.
     * @param array $target target is the table name to create & save.
     * @param array $condition condition is fetch the filtered data.
     * @return boolean $success returns TRUE or FALSE.
     */
    public function archiveTable($source = '', $target = '', $condition = '')
    {
        $this->db->trans_start();

        $this->createTarget($source, $target);

        $this->archiveSource($source, $target, $condition);

        $this->cleanupSource($source, $condition);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * createTarget method is used to create table if not exists.
     * @param array $source source is the table name for reference.
     * @param array $target target is the table name to create.
     * @return boolean $success returns TRUE or FALSE.
     */
    public function createTarget($source = '', $target = '')
    {
        if ($this->db->table_exists($target)) {
            return TRUE;
        }
        return $this->db->query("CREATE TABLE " . $target . " LIKE " . $source);
    }

    /**
     * archiveSource method is used to archive the table records.
     * @param array $source source is the table name to archive.
     * @param array $target target is the table name to create & save.
     * @param array $condition condition is fetch the filtered data.
     * @return boolean $success returns TRUE or FALSE.
     */
    public function archiveSource($source = '', $target = '', $condition = '')
    {
        $where_clause = '';
        if($condition != ""){
            $where_clause = 'WHERE '.$condition;
        }
        return $this->db->query("INSERT INTO " . $target . " SELECT * FROM " . $source . " " . $where_clause);
    }

    /**
     * cleanupSource method is used to cleanup the database table.
     * @param array $source source is the table name to delete records.
     * @param array $condition condition is fetch the filtered data.
     * @return boolean $success returns TRUE or FALSE.
     */
    public function cleanupSource($source = '', $condition = '')
    {
        $where_clause = '';
        if($condition != ""){
            $where_clause = 'WHERE '.$condition;
        }
        return $this->db->query("DELETE FROM " . $source . " " . $where_clause);
    }
}
