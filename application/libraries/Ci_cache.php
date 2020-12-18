<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Cache Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module Cache
 * 
 * @class Ci_cache.php
 * 
 * @path application\libraries\Ci_cache.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Ci_cache
{

    protected $CI;
    protected $_CACHE_FOLDER = '';
    protected $_CACHE_FILE = '';
    protected $_CACHE_QUERY = '';
    protected $_EXPIRE_TIMES = array();
    protected $_ALLOW_TABLES = array();
    protected $_CACHE_TABLES = array();

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function resetCacheTables()
    {
        $this->_ALLOW_TABLES = $this->CI->config->item("CACHE_ALLOW_TABLES");
        $this->_EXPIRE_TIMES = $this->CI->config->item("CACHE_EXPIRE_TIMES");
    }

    public function setQueryCache($query = '', $cache_file = '')
    {
        $this->resetCacheTables();
        $this->_CACHE_QUERY = trim($query);
        $this->cacheXMLTables();
        if (!$this->isCacheQueryTable()) {
            return FALSE;
        }
        $this->getCacheFolder();
        $this->_CACHE_FILE = $cache_file;
        $this->pushCacheXMLFile();
        return TRUE;
    }

    public function getQueryCache($query = '', $cache_file = '')
    {
        $this->resetCacheTables();
        $this->_CACHE_QUERY = trim($query);
        $this->cacheXMLTables();
        if (!$this->isCacheQueryTable()) {
            return FALSE;
        }
        $this->getCacheFolder();
        $this->_CACHE_FILE = $cache_file;
        if (!$this->checkExpiredCache()) {
            return FALSE;
        }
        return TRUE;
    }

    public function clearQueryCache($query = '')
    {
        $this->_CACHE_QUERY = trim($query);
        $this->cacheXMLTables();
        $res = $this->clearCacheTables();
        return $res;
    }

    public function clearCacheFolder()
    {
        $this->getCacheFolder();
        $cache_dir = $this->_CACHE_FOLDER;
        if (!is_dir($cache_dir)) {
            return FALSE;
        }
        $handle = opendir($cache_dir);
        while (FALSE !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                $res = $this->clearQueryResults($file);
            }
        }
        if ($res) {
            $this->writeCacheXMLData();
        }
        return $res;
    }

    private function getCacheFolder()
    {
        $this->_CACHE_FOLDER = $this->CI->db->cachedir;
        return $this->_CACHE_FOLDER;
    }

    private function isCacheQueryTable()
    {
        $sql = $this->_CACHE_QUERY;
        if (!$sql) {
            return FALSE;
        }
        $cache_tbls = is_array($this->_CACHE_TABLES) ? $this->_CACHE_TABLES : array();
        $allow_tbls = is_array($this->_ALLOW_TABLES) ? $this->_ALLOW_TABLES : array();

        $final_tbls = array_intersect($cache_tbls, $allow_tbls);
        if (is_array($final_tbls) && count($final_tbls) > 0) {
            $cacheQuery = TRUE;
        } else {
            $cacheQuery = FALSE;
        }
        return $cacheQuery;
    }

    private function getQueryResults()
    {
        $file = $this->_CACHE_FILE;
        if (!is_file($this->_CACHE_FOLDER . $file) || $file == "") {
            return FALSE;
        }
        $results = unserialize(file_get_contents($this->_CACHE_FOLDER . $file));
        if (!$results || empty($results) || !is_array($results) || count($results) == 0) {
            return FALSE;
        }
        return $results;
    }

    private function clearCacheTables()
    {
        $table_arr = $this->_CACHE_TABLES;
        if (!is_array($table_arr) || count($table_arr) == 0) {
            return FALSE;
        }
        if (!$this->isCacheQueryTable()) {
            return FALSE;
        }
        $this->popCacheXMLFile();
    }

    private function clearQueryResults($file = '')
    {
        if (!is_file($this->_CACHE_FOLDER . $file)) {
            return FALSE;
        }
        $result = unlink($this->_CACHE_FOLDER . $file);
        return $result;
    }

    private function cacheXMLTables()
    {
        $sql = $this->_CACHE_QUERY;
        $query_tbls = $this->parseQueryTables($sql);
        $query_tbls = (is_array($query_tbls)) ? array_unique(array_filter($query_tbls)) : array();
        $this->_CACHE_TABLES = $query_tbls;
        return $query_tbls;
    }

    private function parseQueryTables($sql = '', $flag = TRUE)
    {
        require_once($this->CI->config->item('third_party') . "php-sql-parser/PHPSQLParser.php");
        $query_tbl = $subquery_tbl = array();
        $flag = ($flag == FALSE) ? FALSE : TRUE;
        try {
            $parse_arr = new PHPSQLParser($sql, $flag);
            if (!$parse_arr || !$parse_arr->parsed) {
                throw new Exception("Query cache parser failed.");
            }
            $query_arr = $parse_arr->parsed;
            if (!is_array($query_arr)) {
                throw new Exception("Query cache list not found.");
            }
            $query_keys = array_keys($query_arr);
            switch (TRUE) {
                case (in_array("SELECT", $query_keys)):
                    $query_tbl = $this->parseSelectTables($query_arr['FROM']);
                    $subquery_tbl = $this->parseSubQueryTables($query_arr['SELECT']);
                    $query_tbl = array_merge($query_tbl, $subquery_tbl);
                    break;
                case (in_array("INSERT", $query_keys)):
                    $query_tbl = $this->parseInsertTables($query_arr['INSERT']);
                    break;
                case (in_array("UPDATE", $query_keys)):
                    $query_tbl = $this->parseUpdateTables($query_arr['UPDATE']);
                    break;
                case (in_array("DELETE", $query_keys)):
                    $query_tbl = $this->parseDeleteTables($query_arr['FROM']);
                    break;
            }
        } catch (Exception $e) {
            $query_tbl = array();
        }
        return $query_tbl;
    }

    private function parseSubQueryTables($arr = array())
    {
        $ret_tbl = array();
        if (!is_array($arr) || count($arr) == 0) {
            return $ret_tbl;
        }
        foreach ($arr as $key => $val) {
            if ($val['expr_type'] == "colref") {
                continue;
            } elseif ($val['expr_type'] == "subquery") {
                if (is_array($val['sub_tree'])) {
                    $temp_tbl = $this->parseSelectTables($val['sub_tree']['FROM']);
                    $ret_tbl = (is_array($ret_tbl)) ? array_merge($ret_tbl, $temp_tbl) : $ret_tbl;
                    $temp_tbl = $this->parseSubQueryTables($val['sub_tree']['SELECT']);
                    $ret_tbl = (is_array($ret_tbl)) ? array_merge($ret_tbl, $temp_tbl) : $ret_tbl;
                }
            } else {
                if (is_array($val['sub_tree'])) {
                    $temp_tbl = $this->parseSubQueryTables($val['sub_tree']);
                    $ret_tbl = (is_array($ret_tbl)) ? array_merge($ret_tbl, $temp_tbl) : $ret_tbl;
                }
            }
        }
        return $ret_tbl;
    }

    private function parseSelectTables($arr = array())
    {
        $ret_tbl = array();
        if (!is_array($arr) || count($arr) == 0) {
            return $ret_tbl;
        }
        foreach ($arr as $key => $val) {
            if ($val['expr_type'] == "table") {
                $ret_tbl[] = trim($val['table'], '`');
            } else {
                if (is_array($val['sub_tree'])) {
                    $temp_tbl = $this->parseSelectTables($val['sub_tree']);
                    $ret_tbl = (is_array($ret_tbl)) ? array_merge($ret_tbl, $temp_tbl) : $ret_tbl;
                }
            }
        }
        return $ret_tbl;
    }

    private function parseInsertTables($arr = array())
    {
        $ret_tbl = array();
        if (!is_array($arr) || count($arr) == 0) {
            return $ret_tbl;
        }
        foreach ($arr as $key => $val) {
            $ret_tbl[] = trim($val['table'], '`');
        }
        return $ret_tbl;
    }

    private function parseUpdateTables($arr = array())
    {
        $ret_tbl = array();
        if (!is_array($arr) || count($arr) == 0) {
            return $ret_tbl;
        }
        foreach ($arr as $key => $val) {
            if ($val['expr_type'] == "table") {
                $ret_tbl[] = trim($val['table'], '`');
            } else {
                if (is_array($val['sub_tree'])) {
                    $temp_tbl = $this->parseUpdateTables($val['sub_tree']);
                    $ret_tbl = (is_array($ret_tbl)) ? array_merge($ret_tbl, $temp_tbl) : $ret_tbl;
                }
            }
        }
        return $ret_tbl;
    }

    private function parseDeleteTables($arr = array())
    {
        $ret_tbl = array();
        if (!is_array($arr) || count($arr) == 0) {
            return $ret_tbl;
        }
        foreach ($arr as $key => $val) {
            if ($val['expr_type'] == "table") {
                $ret_tbl[] = trim($val['table'], '`');
            } else {
                if (is_array($val['sub_tree'])) {
                    $temp_tbl = $this->parseDeleteTables($val['sub_tree']);
                    $ret_tbl = (is_array($ret_tbl)) ? array_merge($ret_tbl, $temp_tbl) : $ret_tbl;
                }
            }
        }
        return $ret_tbl;
    }

    private function pushCacheXMLFile()
    {
        $query_tbls = $this->_CACHE_TABLES;
        if (!is_array($query_tbls) || count($query_tbls) == 0) {
            return FALSE;
        }
        $query_file = trim($this->_CACHE_FILE);
        $cache_arr = $this->readCacheXMLData();

        foreach ($query_tbls as $key => $val) {
            $cache_arr[$val][] = $query_file;
        }
        $cache_arr = array_map('array_filter', $cache_arr);
        $this->writeCacheXMLData($cache_arr);
    }

    private function popCacheXMLFile()
    {
        $query_tbls = $this->_CACHE_TABLES;
        if (!is_array($query_tbls) || count($query_tbls) == 0) {
            return FALSE;
        }
        $cache_arr = $this->readCacheXMLData();
        foreach ($query_tbls as $key => $val) {
            $target_tbls = $cache_arr[$val];
            if (is_array($target_tbls) && count($target_tbls) > 0) {
                for ($i = 0; $i < count($target_tbls); $i++) {
                    $file = $target_tbls[$i];
                    $res = $this->clearQueryResults($file);
                }
            }
            unset($cache_arr[$val]);
        }
        $cache_arr = array_map('array_filter', $cache_arr);
        $this->writeCacheXMLData($cache_arr);
    }

    private function readCacheXMLData()
    {
        $cache_xml = $this->getCacheXMLFile();
        $doc = new DOMDocument();
        $doc->load($cache_xml);
        
        $cachetbls = $doc->getElementsByTagName("cache");
        $arr = array();
        if (!$cachetbls) {
            return $arr;
        }
        foreach ($cachetbls as $tableobj) {
            $temp = array();
            $tablenode = $tableobj->getElementsByTagName("table");
            $storenode = $tableobj->getElementsByTagName("store");
            $tblname = $tablenode->item(0)->nodeValue;
            foreach ($storenode as $trackobj) {
                $tracknode = $trackobj->getElementsByTagName("track");
                foreach ($tracknode as $tracks) {
                    $track = $tracks->nodeValue;
                    $temp[] = $track;
                }
            }
            if (count($temp) > 0) {
                $arr[$tblname] = $temp;
            }
        }
        return $arr;
    }

    private function writeCacheXMLData($arr = array())
    {
        $cache_xml = $this->getCacheXMLFile();
        $doc = new DOMDocument();
        $doc->formatOutput = TRUE;
        if (!is_dir($this->_CACHE_FOLDER)) {
            $this->getCacheFolder();
        }

        $cachingnode = $doc->createElement("caching");
        $doc->appendChild($cachingnode);
        if (is_array($arr) && count($arr) > 0) {
            $arr = array_map('array_filter', $arr);
            foreach ($arr as $table => $track) {
                if (!is_array($track) || count($track) == 0) {
                    continue;
                }
                $cachenode = $doc->createElement("cache");

                $tablenode = $doc->createElement("table");
                $tablenode->appendChild($doc->createTextNode(trim($table)));
                $storenode = $doc->createElement("store");
                foreach ($track as $key => $file) {
                    $tracknode = $doc->createElement("track");
                    $tracknode->appendChild($doc->createTextNode(trim($file)));
                    $storenode->appendChild($tracknode);
                }
                $cachenode->appendChild($tablenode);
                $cachenode->appendChild($storenode);

                $cachingnode->appendChild($cachenode);
            }
        }
        $doc->save($cache_xml);
    }

    private function getCacheXMLFile()
    {
        $cache_xml = $this->CI->config->item('admin_upload_cache_path') . 'cache.xml';
        if (!is_file($cache_xml)) {
            $fp = fopen($cache_xml, "w");
            if ($fp) {
                fclose($fp);
            }
        }
        return $cache_xml;
    }

    private function checkExpiredCache()
    {
        $table_arr = $this->_CACHE_TABLES;
        $expire_arr = $this->_EXPIRE_TIMES;

        $file = $this->_CACHE_FILE;
        if (!is_array($table_arr) || count($table_arr) == 0) {
            return FALSE;
        }

        if (!is_file($this->_CACHE_FOLDER . $file) || $file == "") {
            return FALSE;
        }
        $tCreation = (time() - filemtime($this->_CACHE_FOLDER . $file));
        $temp = TRUE;
        for ($i = 0; $i < count($table_arr); $i++) {
            if (array_key_exists($table_arr[$i], $expire_arr)) {
                $tExpires = $expire_arr[$table_arr[$i]];
                if ($tCreation > intval($tExpires) && $tExpires != "FOREVER") {
                    $temp = FALSE;
                    break;
                }
            }
        }
        return $temp;
    }
}

/* End of file Ci_cache.php */
/* Location: ./application/libraries/Ci_cache.php */