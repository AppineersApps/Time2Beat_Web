<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Database Backup Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module Backup
 * 
 * @class Ci_backup.php
 * 
 * @path application\libraries\Ci_backup.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Ci_backup
{

    protected $db;
    public $_database_tables;
    public $_keep_drop_syntax;
    public $_extened_data_limit;
    public $_allow_tables;
    public $_allow_views;
    public $_allow_functions;
    public $_allow_procedures;
    public $_allow_triggers;
    public $_allow_events;
    public $_file_location;
    //
    protected $_new_line;
    protected $_delimiter;
    protected $_short_comment;
    protected $_long_comment;
    protected $_data_max_size;
    protected $_data_rec_limit;

    public function __construct()
    {
        $CI = & get_instance();
        $this->db = & $CI->db;

        // Don't drop tables by default.
        $this->setKeepDropTable(false);
        $this->setStructureOnly(false);
        $this->setExtenedDataLimit(500);
        $this->setAllowTables(true);
        $this->setAllowViews(false);
        $this->setAllowFunctions(false);
        $this->setAllowProcedures(false);
        $this->setAllowTriggers(false);

        $this->_new_line = '
';
        $this->_delimiter = '$$';
        $this->_short_comment = '--';
        $this->_long_comment = '--------------------------------------------------------';
        $this->_data_max_size = 1 * 1024 * 1024 * 5;
        $this->_data_rec_limit = 5000;
    }

    public function setDatabaseTables($dbtables)
    {
        $this->_database_tables = $dbtables;
    }

    public function setExtenedDataLimit($limit)
    {
        $this->_extened_data_limit = $limit;
    }

    public function setKeepDropTable($drop_bool)
    {
        $this->_keep_drop_syntax = $drop_bool;
    }

    public function setStructureOnly($struct_bool)
    {
        $this->_structure_only = $struct_bool;
    }

    public function setAllowTables($allow_bool)
    {
        $this->_allow_tables = $allow_bool;
    }

    public function setAllowViews($allow_bool)
    {
        $this->_allow_views = $allow_bool;
    }

    public function setAllowFunctions($allow_bool)
    {
        $this->_allow_functions = $allow_bool;
    }

    public function setAllowProcedures($allow_bool)
    {
        $this->_allow_procedures = $allow_bool;
    }

    public function setAllowTriggers($allow_bool)
    {
        $this->_allow_triggers = $allow_bool;
    }

    public function setAllowEvents($allow_bool)
    {
        $this->_allow_events = $allow_bool;
    }

    public function setFileLocations($file_location)
    {
        $this->_file_location = $file_location;
    }

    protected function getServerHost()
    {
        return $this->db->hostname;
    }

    protected function getDatabaseName()
    {
        return $this->db->database;
    }

    public function getDatabaseTables()
    {
        return $this->_database_tables;
    }

    public function getExtenedDataLimit()
    {
        return $this->_extened_data_limit;
    }

    public function getKeepDropTable()
    {
        return $this->_keep_drop_syntax;
    }

    public function getStructureOnly()
    {
        return $this->_structure_only;
    }

    public function getAllowTables()
    {
        return $this->_allow_tables;
    }

    public function getAllowViews()
    {
        return $this->_allow_views;
    }

    public function getAllowFunctions()
    {
        return $this->_allow_functions;
    }

    public function getAllowProcedures()
    {
        return $this->_allow_procedures;
    }

    public function getAllowTriggers()
    {
        return $this->_allow_triggers;
    }

    public function getAllowEvents()
    {
        return $this->_allow_events;
    }

    public function getFileLocations()
    {
        return $this->_file_location;
    }

    public function isAllowCreateTable($table = '')
    {
        if (is_array($this->_database_tables) && count($this->_database_tables) > 0) {
            if (!in_array($table, $this->_database_tables)) {
                return false;
            }
        }
        return true;
    }

    public function createDumpHeader()
    {
        // Set header
        $lf = $this->_new_line;
        $sL = $this->_short_comment;

        $output = $sL . $lf;
        $output .= $sL . " CIT SQL Dump" . $lf;
        $output .= $sL . " http://www.configure.it/" . $lf;

        $output .= $lf;
        $output .= $sL . " Host            : " . $this->getServerHost() . $lf;
        $output .= $sL . " Generation Time : " . date("M j, Y H:i:s") . $lf;
        $output .= $sL . " Server Version  : " . $this->db->conn_id->server_info . $lf;
        $output .= $sL . " PHP Version     : " . phpversion() . $lf;

        $output .= $lf;
        $output .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';" . $lf;
        $output .= "SET time_zone = '+00:00';" . $lf;
        $output .= $lf;

        $output .= $lf;
        $output .= "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;" . $lf;
        $output .= "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;" . $lf;
        $output .= "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;" . $lf;
        $output .= "/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;" . $lf;
        $output .= "/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;" . $lf;
        $output .= "/*!40101 SET NAMES utf8 */;" . $lf;
        $output .= $lf;

        $output .= $sL . $lf;
        $output .= $sL . " Database: `" . $this->getDatabaseName() . "`" . $lf;
        $output .= $sL . $lf;

        return $output;
    }

    public function createDumpFooter()
    {
        $lf = $this->_new_line;

        $output = $lf . $lf;
        $output .= "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;" . $lf;
        $output .= "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;" . $lf;
        $output .= "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;" . $lf;
        $output .= "/*!40101 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;" . $lf;
        $output .= "/*!40101 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;" . $lf;

        return $output;
    }

    public function createDBDump()
    {
        // Set header
        $output = $this->createDumpHeader();

        if (!$this->writeDBContentFile($output, "normal", true)) {
            return false;
        }

        if ($this->getAllowTables()) {
            $this->createTablesBackup();
        }
        if ($this->getAllowViews()) {
            $this->createViewsBackup();
        }
        if ($this->getAllowFunctions()) {
            $this->createFunctionBackup();
        }
        if ($this->getAllowProcedures()) {
            $this->createProceduresBackup();
        }
        if ($this->getAllowTriggers()) {
            $this->createTriggersBackup();
        }
        if ($this->getAllowEvents()) {
            $this->createEventsBackup();
        }

        $output = $this->createDumpFooter();
        $this->writeDBContentFile($output, "normal", true);

        return true;
    }

    public function createTablesBackup()
    {
        $lf = $this->_new_line;
        $dL = $this->_delimiter;
        $sL = $this->_short_comment;
        $lL = $this->_long_comment;
        $dRL = $this->_data_rec_limit;
        $tabl_script = '';

        $db_tables_obj = $this->db->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
        $db_tables = is_object($db_tables_obj) ? $db_tables_obj->result_array() : array();

        if (!is_array($db_tables) || count($db_tables) == 0) {
            return $tabl_script;
        }

        //\x08\\x09, not required
        $search = array("\x00", "\x0a", "\x0d", "\x1a");
        $replace = array('\0', '\n', '\r', '\Z');

        $extended_limit = $this->getExtenedDataLimit();

        foreach ($db_tables as $tKey => $tVal) {
            $table_name = $tVal['Tables_in_' . $this->getDatabaseName()];

            if (!$this->isAllowCreateTable($table_name)) {
                continue;
            }

            $db_create_table_obj = $this->db->query("SHOW CREATE TABLE `$table_name`");
            $db_create_table = is_object($db_create_table_obj) ? $db_create_table_obj->result_array() : array();

            $create_table = $db_create_table[0]['Create Table'];

            if ($create_table == "") {
                continue;
            }
            if ($this->writeDBContentFile($tabl_script, "tables")) {
                $tabl_script = '';
            }

            $tabl_script .= $lf . $lf . $sL . " " . $lL . $lf;
            $tabl_script .= $lf . $sL;
            $tabl_script .= $lf . $sL . " Table structure for table `$table_name`";
            $tabl_script .= $lf . $sL;

            if ($this->getKeepDropTable()) {
                $tabl_script .= $lf . "DROP TABLE IF EXISTS `$table_name`;" . $lf;
            }

            $tabl_script .= $lf . $create_table . ";" . $lf;
            $tabl_script .= $lf . $If;

            if (!$this->isAllowInsertRecord($table_name)) {
                continue;
            }
            $dRS = 0;
            $this->db->select("*");
            $this->db->from($table_name);
            $this->db->limit($dRL, $dRS);
            $db_result_obj = $this->db->get();
            $db_result = is_object($db_result_obj) ? $db_result_obj->result_array() : array();

            if (!is_array($db_result) || count($db_result) == 0) {
                continue;
            }
            $db_fields_obj = $this->db->query("SHOW FULL COLUMNS FROM `$table_name`");
            $db_table_fields = is_object($db_fields_obj) ? $db_fields_obj->result_array() : array();
            $table_keys_arr = $db_field_meta = array();
            foreach ($db_table_fields as $fKey => $fVal) {
                $table_keys_arr[] = '`' . $fVal['Field'] . '`';
                list($__typ, $__val) = $this->getTableFieldType($fVal);
                $db_field_meta[$fKey] = $fVal;
                $db_field_meta[$fKey]['__typ'] = $__typ;
                $db_field_meta[$fKey]['__val'] = $__val;
            }

            $tabl_script .= $lf . $sL;
            $tabl_script .= $lf . $sL . " Dumping data for table `$table_name`";
            $tabl_script .= $lf . $sL;
            $tabl_script .= $lf . $If;

            while (is_array($db_result) && count($db_result) > 0) {
                $extended_arr = array();
                foreach ((array) $db_result as $num => $row) {
                    $data_arr = array();
                    foreach ($row as $key => $value) {
                        $meta_info = $db_field_meta[$key];
                        if (is_null($value)) {
                            $data_arr[] = "NULL";
                        } elseif ($meta_info['__typ'] == "numeric") {
                            if (empty($value) && $meta_info['Key'] != 'PRI' && $meta_info['Extra'] != 'auto_increment') {
                                $data_arr[] = $meta_info['__val'];
                            } else {
                                $data_arr[] = $value;
                            }
                        } elseif ($meta_info['__typ'] == "date") {
                            if (empty($value)) {
                                $data_arr[] = "'" . $meta_info['__val'] . "'";
                            } else {
                                $data_arr[] = "'" . $value . "'";
                            }
                        } elseif ($meta_info['__typ'] == "bit") {
                            $data_arr[] = "b'" . $value . "'";
                        } else {
                            $data_arr[] = "'" . str_replace($search, $replace, $this->sqlAddSlashes($value)) . "'";
                        }
                    }
                    $extended_arr[] = "(" . implode(", ", $data_arr) . ")";
                    if (count($db_result) == ($num + 1) || count($extended_arr) >= $extended_limit) {
                        if ($this->writeDBContentFile($tabl_script, "tables")) {
                            $tabl_script = '';
                        }
                        $tabl_script .= $lf;
                        $tabl_script .= "INSERT INTO `$table_name` ";
                        $tabl_script .= "(" . implode(", ", $table_keys_arr) . ") VALUES ";
                        $tabl_script .= $lf;
                        $tabl_script .= implode(", " . $lf, $extended_arr) . ";";
                        $tabl_script .= $lf;
                        $extended_arr = array();
                    }
                }
                $dRS += $dRL;
                $this->db->select("*");
                $this->db->from($table_name);
                $this->db->limit($dRL, $dRS);
                $db_result_obj = $this->db->get();
                $db_result = is_object($db_result_obj) ? $db_result_obj->result_array() : array();
            }
        }

        $tabl_script .= $lf . $lf . $sL . " " . $lL . $lf;

        $this->writeDBContentFile($tabl_script, "tables", true);
    }

    public function createViewsBackup()
    {
        $lf = $this->_new_line;
        $dL = $this->_delimiter;
        $sL = $this->_short_comment;
        $lL = $this->_long_comment;
        $view_script = '';

        $db_views_obj = $this->db->query("SHOW FULL TABLES WHERE Table_type = 'VIEW'");
        $db_views = is_object($db_views_obj) ? $db_views_obj->result_array() : array();

        if (!is_array($db_views) || count($db_views) == 0) {
            return $view_script;
        }

        foreach ($db_views as $key => $val) {
            $view_name = $val['Tables_in_' . $this->getDatabaseName()];

            $db_create_view_obj = $this->db->query("SHOW CREATE VIEW `$view_name`");
            $db_create_view = is_object($db_create_view_obj) ? $db_create_view_obj->result_array() : array();

            $create_view = $db_create_view[0]['Create View'];

            $create_view = $this->replaceDefiners("CREATE", "VIEW", $create_view);

            if ($create_view == "") {
                continue;
            }
            if ($this->writeDBContentFile($view_script, "views")) {
                $view_script = '';
            }
            $view_script .= $lf . $lf . $sL . " " . $lL . $lf;
            $view_script .= $lf . $sL;
            $view_script .= $lf . $sL . " Stand-in structure for view `$view_name`";
            $view_script .= $lf . $sL;

            $view_script .= $lf . "DROP VIEW IF EXISTS `$view_name`;" . $lf;
            $view_script .= $lf . $create_view . ";" . $lf;
            $view_script .= $lf . $lf . $sL . " " . $lL . $lf;
        }
        $view_script .= $lf . $lf . $sL . " " . $lL . $lf;

        $this->writeDBContentFile($view_script, "views", true);
    }

    public function createFunctionBackup()
    {
        $lf = $this->_new_line;
        $dL = $this->_delimiter;
        $sL = $this->_short_comment;
        $lL = $this->_long_comment;
        $func_script = '';

        $this->db->select("isr.SPECIFIC_NAME, isr.ROUTINE_NAME");
        $this->db->where("isr.ROUTINE_TYPE", "FUNCTION");
        $this->db->where("isr.ROUTINE_SCHEMA", $this->getDatabaseName());
        $db_functions_obj = $this->db->get("INFORMATION_SCHEMA.ROUTINES as isr");
        $db_functions = is_object($db_functions_obj) ? $db_functions_obj->result_array() : array();

        if (!is_array($db_functions) || count($db_functions) == 0) {
            return $func_script;
        }

        $func_script .= $lf . $lf . $sL . " " . $lL . $lf;
        $func_script .= $sL . " Enabled Function Creators " . $lf;
        $func_script .= $lf . "SET GLOBAL log_bin_trust_function_creators = 1;" . $lf . $lf;
        $func_script .= 'DELIMITER $$' . $lf . $lf;
        $func_script .= $sL . $lf;
        $func_script .= $sL . " Functions" . $lf;
        $func_script .= $sL . $lf;

        foreach ($db_functions as $key => $val) {
            $func_name = $val['ROUTINE_NAME'];
            $db_create_func_obj = $this->db->query("SHOW CREATE FUNCTION `$func_name`");
            $db_create_func = is_object($db_create_func_obj) ? $db_create_func_obj->result_array() : array();
            if (!is_array($db_create_func) || count($db_create_func) == 0) {
                continue;
            }
            $create_func = $db_create_func[0]['Create Function'];
            $create_func = $this->replaceDefiners("CREATE", "FUNCTION", $create_func);
            if ($create_func == "") {
                continue;
            }
            if ($this->writeDBContentFile($func_script, "functions")) {
                $func_script = '';
            }
            $func_script .= $lf . 'DROP FUNCTION IF EXISTS `' . $func_name . '`' . $dL;
            $func_script .= $lf . $create_func . $dL;
            $func_script .= $lf;
        }
        $func_script .= $lf . "DELIMITER ;";
        $func_script .= $lf . $lf . $sL . " " . $lL . $lf;

        $this->writeDBContentFile($func_script, "functions", true);
    }

    public function createProceduresBackup()
    {
        $lf = $this->_new_line;
        $dL = $this->_delimiter;
        $sL = $this->_short_comment;
        $lL = $this->_long_comment;
        $proc_script = '';

        $this->db->select("isr.SPECIFIC_NAME, isr.ROUTINE_NAME");
        $this->db->where("isr.ROUTINE_TYPE", 'PROCEDURE');
        $this->db->where("isr.ROUTINE_SCHEMA", $this->getDatabaseName());
        $db_procedures_obj = $this->db->get("INFORMATION_SCHEMA.ROUTINES isr");
        $db_procedures = is_object($db_procedures_obj) ? $db_procedures_obj->result_array() : array();

        if (!is_array($db_procedures) || count($db_procedures) == 0) {
            return $proc_script;
        }

        $proc_script .= $lf . $lf . $sL . " " . $lL . $lf;
        $proc_script .= 'DELIMITER ' . $dL . $lf . $lf;
        $proc_script .= $sL . $lf;
        $proc_script .= $sL . " Procedures" . $lf;
        $proc_script .= $sL . $lf;

        foreach ($db_procedures as $key => $val) {
            $proc_name = $val['ROUTINE_NAME'];
            $db_create_proc_obj = $this->db->query("SHOW CREATE PROCEDURE `$proc_name`");
            $db_create_proc = is_object($db_create_proc_obj) ? $db_create_proc_obj->result_array() : array();
            if (!is_array($db_create_proc) || count($db_create_proc) == 0) {
                continue;
            }
            $create_proc = $db_create_proc[0]['Create Procedure'];
            $create_proc = $this->replaceDefiners("CREATE", "PROCEDURE", $create_proc);
            if ($create_proc == "") {
                continue;
            }
            if ($this->writeDBContentFile($proc_script, "procedures")) {
                $proc_script = '';
            }
            $proc_script .= $lf . 'DROP PROCEDURE IF EXISTS `' . $proc_name . '`' . $dL;
            $proc_script .= $lf . $create_proc . $dL;
            $proc_script .= $lf;
        }
        $proc_script .= $lf . "DELIMITER ;";
        $proc_script .= $lf . $lf . $sL . " " . $lL . $lf;

        $this->writeDBContentFile($proc_script, "procedures", true);
    }

    public function createTriggersBackup()
    {
        $lf = $this->_new_line;
        $dL = "//";
        $sL = $this->_short_comment;
        $lL = $this->_long_comment;
        $trig_script = '';

        $this->db->select("ist.TRIGGER_NAME, ist.EVENT_OBJECT_TABLE");
        $this->db->where("ist.TRIGGER_SCHEMA", $this->getDatabaseName());
        $db_triggers_obj = $this->db->get("INFORMATION_SCHEMA.TRIGGERS ist");
        $db_triggers = is_object($db_triggers_obj) ? $db_triggers_obj->result_array() : array();

        if (!is_array($db_triggers) || count($db_triggers) == 0) {
            return $trig_script;
        }
        $trig_script .= $lf . $lf . $sL . " " . $lL . $lf;
        foreach ($db_triggers as $key => $val) {
            $trig_name = $val['TRIGGER_NAME'];
            $tbl_name = $val['EVENT_OBJECT_TABLE'];
            $db_create_trig_obj = $this->db->query("SHOW CREATE TRIGGER `$trig_name`");
            $db_create_trig = is_object($db_create_trig_obj) ? $db_create_trig_obj->result_array() : array();
            if (!is_array($db_create_trig) || count($db_create_trig) == 0) {
                continue;
            }
            $create_trig = $db_create_trig[0]['SQL Original Statement'];
            $create_trig = $this->replaceDefiners("CREATE", "TRIGGER", $create_trig);
            if ($create_trig == "") {
                continue;
            }
            if ($this->writeDBContentFile($trig_script, "triggers")) {
                $trig_script = '';
            }
            $trig_script .= $sL . $lf;
            $trig_script .= $sL . " Triggers `" . $tbl_name . "`" . $lf;
            $trig_script .= $sL . $lf;

            $trig_script .= $lf . 'DROP TRIGGER IF EXISTS `' . $trig_name . '`;';
            $trig_script .= $lf . 'DELIMITER ' . $dL;

            $trig_script .= $lf . $create_trig . $lf . $dL;
            $trig_script .= $lf;
            $trig_script .= $lf . "DELIMITER ;";
        }
        $trig_script .= $lf . $lf . $sL . " " . $lL . $lf;

        $this->writeDBContentFile($trig_script, "triggers", true);
    }

    public function createEventsBackup()
    {
        $lf = $this->_new_line;
        $dL = $this->_delimiter;
        $sL = $this->_short_comment;
        $lL = $this->_long_comment;
        $event_script = '';

        $this->db->select("ise.EVENT_NAME");
        $this->db->where("ise.EVENT_SCHEMA", $this->getDatabaseName());
        $db_events_obj = $this->db->get("INFORMATION_SCHEMA.EVENTS ise");
        $db_events = is_object($db_events_obj) ? $db_events_obj->result_array() : array();

        if (!is_array($db_events) || count($db_events) == 0) {
            return $event_script;
        }

        $event_script .= $lf . $lf . $sL . " " . $lL . $lf;
        $event_script .= 'DELIMITER ' . $dL . $lf . $lf;
        $event_script .= $sL . $lf;
        $event_script .= $sL . " Events" . $lf;
        $event_script .= $sL . $lf;

        foreach ($db_events as $key => $val) {
            $event_name = $val['EVENT_NAME'];
//            if (!$this->isAllowCreateEvent($event_name)) {
//                continue;
//            }
            $db_create_event_obj = $this->db->query("SHOW CREATE EVENT `$event_name`");
            $db_create_event = is_object($db_create_event_obj) ? $db_create_event_obj->result_array() : array();
            if (!is_array($db_create_event) || count($db_create_event) == 0) {
                continue;
            }
            $create_event = $db_create_event[0]['Create Event'];
            $create_event = $this->replaceDefiners("CREATE", "EVENT", $create_event);
            if ($create_event == "") {
                continue;
            }
            if ($this->writeDBContentFile($event_script, "events")) {
                $event_script = '';
            }
            $event_script .= $lf . 'DROP EVENT IF EXISTS `' . $event_name . '`' . $dL;
            $event_script .= $lf . $create_event . $dL;
            $event_script .= $lf;
        }
        $event_script .= $lf . "DELIMITER ;";
        $event_script .= $lf . $lf . $sL . " " . $lL . $lf;

        $this->writeDBContentFile($event_script, "events", true);
    }

    public function isAllowInsertRecord($table = '')
    {
        if ($this->_structure_only === true) {
            return false;
        }
        return true;
    }

    public function getTableFieldType($field = array())
    {
        $int_arr = array("TINYINT", "SMALLINT", "MEDIUMINT", "INT", "BIGINT");
        $float_arr = array("DECIMAL", "FLOAT", "DOUBLE", "REAL");
        $date_arr = array("DATE", "DATETIME", "TIMESTAMP", "TIME", "YEAR");
        $char_arr = array("CHAR", "VARCHAR");
        $text_arr = array("TINYTEXT", "TEXT", "MEDIUMTEXT", "LONGTEXT");
        $enum_arr = array("ENUM", "SET");
        $bit_arr = array("BIT"); //"BOOLEAN", "SERIAL"

        $type_arr = explode("(", $field['Type']);
        $type = strtoupper($type_arr[0]);
        $default = $field['Type'];

        $ret_type = $ret_val = '';
        if (in_array($type, $int_arr) || in_array($type, $float_arr)) {
            $ret_type = "numeric";
            $ret_val = 0;
        } elseif (in_array($type, $date_arr)) {
            $ret_type = "date";
            if ($type == "DATE") {
                $ret_val = '0000-00-00';
            } elseif ($type == "DATETIME" || $type == "TIMESTAMP") {
                $ret_val = '0000-00-00 00:00:00';
            } elseif ($type == "TIME") {
                $ret_val = '00:00:00';
            } elseif ($type == "YEAR") {
                $ret_val = '0000';
            }
        } elseif (in_array($type, $char_arr) || in_array($type, $text_arr)) {
            $ret_type = "text";
        } elseif (in_array($type, $enum_arr)) {
            $ret_type = "enum";
        } elseif ($type == "BIT") {
            $ret_type = "bit";
        }
        return array($ret_type, $ret_val);
    }

    public function sqlAddSlashes($a_string = '')
    {
        $a_string = str_replace('\\', '\\\\', $a_string);
        $a_string = str_replace('\'', '\'\'', $a_string);
        return $a_string;
    }

    public function detectDataEncoding($data = '')
    {
        $dest = "UTF-8";
        $encode_arr = array(
            "ISO-8859-1", "ISO-8859-2", "ISO-8859-3", "ISO-8859-4", "ISO-8859-5", "ISO-8859-6", "ISO-8859-7", "ISO-8859-8", "ISO-8859-9", "ISO-8859-10",
            "ISO-8859-11", "ISO-8859-12", "ISO-8859-13", "ISO-8859-14", "ISO-8859-15", "Windows-1251", "Windows-1252", "KOI8-R", "BIG-5", "GB2312", "UTF-16", "UTF-8", "UTF-7"
        );
        if (strlen($data) != strlen(utf8_encode($data))) {
            $data = utf8_decode($data);
        }
        return $data;
//        if (mb_detect_encoding($data, $dest, true)) {
//            return $data;
//        }
//        foreach ($encode_arr as $key => $val) {
//            if (mb_detect_encoding($data, $val, true) !== false) {
//                $data = mb_convert_encoding($str, $dest, $val);
//                break;
//            }
//        }
//        return $data;
    }

    public function replaceDefiners($start_point = '', $end_point, $source = '')
    {
        return preg_replace('#(' . $start_point . ').*?(' . $end_point . ')#', '$1 $2', $source);
    }

    public function writeDBContentFile($data = '', $type = '', $last = false)
    {
        $file_arr = $this->getFileLocations();
        $file_path = $file_arr['file_path'];
        $file_name = $file_arr['file_name'];

        if (!is_dir($file_path)) {
            return false;
        }

        if (strlen($data) < $this->_data_max_size && $last == false) {
            return false;
        }

        $fp = fopen($file_path . $file_name, "a+");
        if ($fp) {
            fwrite($fp, $data);
            fclose($fp);
        }

        switch ($type) {
            case "tables":
                if ($file_arr['tables'] != "") {
                    $fp = fopen($file_path . $file_arr['tables'], "a+");
                    if ($fp) {
                        fwrite($fp, $data);
                        fclose($fp);
                    }
                }
                break;
            case "views":
                if ($file_arr['views'] != "") {
                    $fp = fopen($file_path . $file_arr['views'], "a+");
                    if ($fp) {
                        fwrite($fp, $data);
                        fclose($fp);
                    }
                }
                break;
            case "functions":
                if ($file_arr['functions'] != "") {
                    $fp = fopen($file_path . $file_arr['functions'], "a+");
                    if ($fp) {
                        fwrite($fp, $data);
                        fclose($fp);
                    }
                }
                break;
            case "procedures":
                if ($file_arr['procedures'] != "") {
                    $fp = fopen($file_path . $file_arr['procedures'], "a+");
                    if ($fp) {
                        fwrite($fp, $data);
                        fclose($fp);
                    }
                }
                break;
            case "triggers":
                if ($file_arr['triggers'] != "") {
                    $fp = fopen($file_path . $file_arr['triggers'], "a+");
                    if ($fp) {
                        fwrite($fp, $data);
                        fclose($fp);
                    }
                }
                break;
            case "events":
                if ($file_arr['events'] != "") {
                    $fp = fopen($file_path . $file_arr['events'], "a+");
                    if ($fp) {
                        fwrite($fp, $data);
                        fclose($fp);
                    }
                }
                break;
        }
        return true;
    }
}

/* End of file Ci_backup.php */
/* Location: ./application/libraries/Ci_backup.php */