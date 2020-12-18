<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Static Pages Model
 *
 * @category models
 *            
 * @package tools
 * 
 * @module StaticPages
 * 
 * @class Staticpages.php
 * 
 * @path application\models\tools\Staticpages.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Staticpages extends CI_Model
{

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        $this->main_table = "mod_page_settings";
        $this->lang_table = "mod_page_settings_lang";
        $this->primary_key = "iPageId";
    }

    /**
     * insert method is used to insert data into the table.
     * 
     * @param array $data array of inserted data.
     * 
     * @return numeric returns last inserted id.
     */
    public function insert($data)
    {
        $this->db->insert($this->main_table, $data);
        return $this->db->insert_id();
    }

    /**
     * update method is used to update data into the table.
     * 
     * @param array $data array of updated data.
     * 
     * @param numeric $id primary id will be used.
     * 
     * @return numeric returns last updated id.
     */
    public function update($data, $id)
    {
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->main_table, $data);
    }

    /**
     * checkRecordAlreadyExists method is used to check the records that it will already exist or not.
     * 
     * @param string $field_name field name which will be use for check duplicacy.
     * 
     * @param string $field_value field value which will be use for check duplicacy.
     * 
     * @param numeric $id primary id will be passed
     * 
     * @param string $mode add or update will be used as a mode.
     * 
     * @return boolean $exists true or false will be return.
     */
    public function checkRecordAlreadyExists($field_name, $field_value, $id, $mode)
    {
        $exists = false;
        if ($mode == 'Add') {
            $this->db->select($this->primary_key);
            $this->db->where($field_name, $field_value);
            $data_obj = $this->db->get($this->main_table);
            $data = is_object($data_obj) ? $data_obj->result_array() : array();
            if ($data[0][$this->primary_key] > 0) {
                $exists = true;
            }
        } elseif ($mode == 'Update') {
            $this->db->select($this->primary_key);
            $this->db->where($field_name, $field_value);
            $this->db->where($this->primary_key . " !=", $id);
            $data_obj = $this->db->get($this->main_table);
            $data = is_object($data_obj) ? $data_obj->result_array() : array();
            if ($data[0][$this->primary_key] > 0) {
                $exists = true;
            }
        }
        return $exists;
    }

    /**
     * getStaticPagesList method is used to get static pages.
     * 
     * @param string $extracond extra_cond is the query condition for getting filtered data.
     * 
     * @param string $fields fields are either array or string.
     * 
     * @param string $orderby order_by is to append order by condition.
     * 
     * @param numeric $limit limit value will be used.
     * 
     * @param numeric $language_id language id will be used as a parameter.
     * 
     * @return array $list_data array of data will be returned.
     */
    public function getStaticPagesList($extracond = "", $fields = "", $orderby = "", $limit = "", $language_id = "")
    {
        if (empty($fields)) {
            $fields = array(
                "iPageId", "vPageTitle", "vPageCode", "vUrl", "vContent",
                "tMetaTitle", "tMetaKeyword", "tMetaDesc", "eStatus"
            );
        }
        $this->db->select($fields);
        $this->db->from($this->main_table);

        if ($extracond != "") {
            if (intval($extracond)) {
                $this->db->where($this->primary_key, $extracond);
            } else {
                $this->db->where($extracond);
            }
        }
        if ($orderby != "") {
            $this->db->order_by($orderby);
        }
        if ($limit != "") {
            list($offset, $limit) = explode(",", $limit);
            $this->db->limit($offset, $limit);
        }
        $list_data_obj = $this->db->get();
        return (is_object($list_data_obj) ? $list_data_obj->result_array() : array());
    }

    /**
     * getStaticPageData method is used to get static pages according to the page code.
     * 
     * @param string $pagecode page code will be used.
     * 
     * @return array $data array of resultant data will be return.
     */
    public function getStaticPageData($page_code = '', $fields = array())
    {
        if (empty($fields)) {
            $fields = array(
                "iPageId", "vPageTitle", "vPageCode", "vUrl", "vContent",
                "tMetaTitle", "tMetaKeyword", "tMetaDesc", "eStatus"
            );
        }
        $this->db->select($fields);
        $this->db->from($this->main_table);
        $this->db->where("vPageCode", $page_code);
        $data_obj = $this->db->get();
        return (is_object($data_obj) ? $data_obj->result_array() : array());
    }

    /**
     * getStaticPageData method is used to get static pages according to the page code.
     * 
     * @param string $pagecode page code will be used.
     * 
     * @return array $data array of resultant data will be return.
     */
    public function getStaticPageLangData($lang = '', $page_code = '', $fields = array())
    {
        $join_cond = $this->db->protect("mps." . $this->primary_key) . " = " . $this->db->protect("mps_lang." . $this->primary_key) . " AND " . $this->db->protect("mps_lang.vLangCode") . " = " . $this->db->escape($lang);
        if (empty($fields)) {
            $fields = array(
                "mps.iPageId", "mps.vPageTitle", "mps.vPageCode", "mps.vUrl", "mps.vContent",
                "mps.tMetaTitle", "mps.tMetaKeyword", "mps.tMetaDesc", "mps.eStatus"
            );
        }
        $this->db->select($fields);
        $this->db->from($this->main_table . " AS mps");
        $this->db->join($this->lang_table . ' AS mps_lang', $join_cond, "left");
        $this->db->where("mps.vPageCode", $page_code);
        $data_obj = $this->db->get();
        return (is_object($data_obj) ? $data_obj->result_array() : array());
    }

    public function getLangTableFields()
    {
        $lang_fields = array();
        if ($this->db->table_exists($this->lang_table)) {
            $lang_fields = $this->db->list_fields($this->lang_table);
        }
        return $lang_fields;
    }
}
