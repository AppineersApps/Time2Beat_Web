<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Release Notes Details Model
 *
 * @category admin
 *
 * @package tools
 *
 * @subpackage models
 *
 * @module Release Notes Details
 *
 * @class Release_notes_details_model.php
 *
 * @path application\admin\tools\models\Release_notes_details_model.php
 *
 * @version 4.3
 *
 * @author CIT Dev Team
 *
 * @date 02.11.2018
 */
class Release_notes_details_model extends CI_Model
{

    public $table_name;
    public $table_alias;
    public $primary_key;
    public $primary_alias;
    public $grid_fields;
    public $join_tables;
    public $extra_cond;
    public $groupby_cond;
    public $orderby_cond;
    public $unique_type;
    public $unique_fields;
    public $switchto_fields;
    public $default_filters;
    public $global_filters;
    public $search_config;
    public $relation_modules;
    public $deletion_modules;
    public $print_rec;
    public $print_list;
    public $multi_lingual;
    public $physical_data_remove;
    //
    public $listing_data;
    public $rec_per_page;
    public $message;

    /**
     * __construct method is used to set model preferences while model object initialization.
     * @created CIT Admin | 01.11.2018
     * @modified CIT Admin | 02.11.2018
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('listing');
        $this->load->library('filter');
        $this->load->library('dropdown');
        $this->module_name = "release_notes_details";
        $this->table_name = "mod_release_note_details";
        $this->table_alias = "mrnd";
        $this->primary_key = "iReleaseNoteDetailsId";
        $this->primary_alias = "mrnd_release_note_details_id";
        $this->physical_data_remove = "Yes";
        $this->grid_fields = array("mrnd_title", "mrnd_description", "mrnd_version_status", "ma_name");
        $this->join_tables = array(
            array(
                "table_name" => "mod_release_notes",
                "table_alias" => "mrn",
                "field_name" => "iReleaseNotesId",
                "rel_table_name" => "mod_release_note_details",
                "rel_table_alias" => "mrnd",
                "rel_field_name" => "iReleaseNotesId",
                "join_type" => "left",
                "extra_condition" => "",
            ),
            array(
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "iAdminId",
                "rel_table_name" => "mod_release_note_details",
                "rel_table_alias" => "mrnd",
                "rel_field_name" => "iAddedBy",
                "join_type" => "left",
                "extra_condition" => "",
            )
        );
        $this->extra_cond = "";
        $this->groupby_cond = array();
        $this->having_cond = "";
        $this->orderby_cond = array();
        $this->unique_type = "OR";
        $this->unique_fields = array();
        $this->switchto_fields = array();
        $this->default_filters = array();
        $this->global_filters = array();
        $this->search_config = array();
        $this->relation_modules = array();
        $this->deletion_modules = array();
        $this->print_rec = "No";
        $this->print_list = "No";
        $this->multi_lingual = "No";

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
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * update method is used to update data records to the database table.
     * @param array $data data array for update into table.
     * @param string $where where is the query condition for updating.
     * @param string $alias alias is to keep aliases for query or not.
     * @param string $join join is to make joins while updating records.
     * @return boolean $res returns TRUE or FALSE.
     */
    public function update($data = array(), $where = '', $alias = "No", $join = "No")
    {
        if ($alias == "Yes") {
            if ($join == "Yes") {
                $join_tbls = $this->addJoinTables("NR");
            }
            if (trim($join_tbls) != '') {
                $set_cond = array();
                foreach ($data as $key => $val) {
                    $set_cond[] = $this->db->protect($key) . " = " . $this->db->escape($val);
                }
                if (is_numeric($where)) {
                    $extra_cond = " WHERE " . $this->db->protect($this->table_alias . "." . $this->primary_key) . " = " . $this->db->escape($where);
                } elseif ($where) {
                    $extra_cond = " WHERE " . $where;
                } else {
                    return FALSE;
                }
                $update_query = "UPDATE " . $this->db->protect($this->table_name) . " AS " . $this->db->protect($this->table_alias) . " " . $join_tbls . " SET " . implode(", ", $set_cond) . " " . $extra_cond;
                $res = $this->db->query($update_query);
            } else {
                if (is_numeric($where)) {
                    $this->db->where($this->table_alias . "." . $this->primary_key, $where);
                } elseif ($where) {
                    $this->db->where($where, FALSE, FALSE);
                } else {
                    return FALSE;
                }
                $res = $this->db->update($this->table_name . " AS " . $this->table_alias, $data);
            }
        } else {
            if (is_numeric($where)) {
                $this->db->where($this->primary_key, $where);
            } elseif ($where) {
                $this->db->where($where, FALSE, FALSE);
            } else {
                return FALSE;
            }
            $res = $this->db->update($this->table_name, $data);
        }
        return $res;
    }

    /**
     * delete method is used to delete data records from the database table.
     * @param string $where where is the query condition for deletion.
     * @param string $alias alias is to keep aliases for query or not.
     * @param string $join join is to make joins while deleting records.
     * @return boolean $res returns TRUE or FALSE.
     */
    public function delete($where = "", $alias = "No", $join = "No")
    {
        if ($this->config->item('PHYSICAL_RECORD_DELETE') && $this->physical_data_remove == 'No') {
            if ($alias == "Yes") {
                if (is_array($join['joins']) && count($join['joins'])) {
                    $join_tbls = '';
                    if ($join['list'] == "Yes") {
                        $join_tbls = $this->addJoinTables("NR");
                    }
                    $join_tbls .= ' ' . $this->listing->addJoinTables($join['joins'], "NR");
                } elseif ($join == "Yes") {
                    $join_tbls = $this->addJoinTables("NR");
                }
                $data = $this->general->getPhysicalRecordUpdate($this->table_alias);
                if (trim($join_tbls) != '') {
                    $set_cond = array();
                    foreach ($data as $key => $val) {
                        $set_cond[] = $this->db->protect($key) . " = " . $this->db->escape($val);
                    }
                    if (is_numeric($where)) {
                        $extra_cond = " WHERE " . $this->db->protect($this->table_alias . "." . $this->primary_key) . " = " . $this->db->escape($where);
                    } elseif ($where) {
                        $extra_cond = " WHERE " . $where;
                    } else {
                        return FALSE;
                    }
                    $update_query = "UPDATE " . $this->db->protect($this->table_name) . " AS " . $this->db->protect($this->table_alias) . " " . $join_tbls . " SET " . implode(", ", $set_cond) . " " . $extra_cond;
                    $res = $this->db->query($update_query);
                } else {
                    if (is_numeric($where)) {
                        $this->db->where($this->table_alias . "." . $this->primary_key, $where);
                    } elseif ($where) {
                        $this->db->where($where, FALSE, FALSE);
                    } else {
                        return FALSE;
                    }
                    $res = $this->db->update($this->table_name . " AS " . $this->table_alias, $data);
                }
            } else {
                if (is_numeric($where)) {
                    $this->db->where($this->primary_key, $where);
                } elseif ($where) {
                    $this->db->where($where, FALSE, FALSE);
                } else {
                    return FALSE;
                }
                $data = $this->general->getPhysicalRecordUpdate();
                $res = $this->db->update($this->table_name, $data);
            }
        } else {
            if ($alias == "Yes") {
                $del_query = "DELETE " . $this->db->protect($this->table_alias) . ".* FROM " . $this->db->protect($this->table_name) . " AS " . $this->db->protect($this->table_alias);
                if (is_array($join['joins']) && count($join['joins'])) {
                    if ($join['list'] == "Yes") {
                        $del_query .= $this->addJoinTables("NR");
                    }
                    $del_query .= ' ' . $this->listing->addJoinTables($join['joins'], "NR");
                } elseif ($join == "Yes") {
                    $del_query .= $this->addJoinTables("NR");
                }
                if (is_numeric($where)) {
                    $del_query .= " WHERE " . $this->db->protect($this->table_alias) . "." . $this->db->protect($this->primary_key) . " = " . $this->db->escape($where);
                } elseif ($where) {
                    $del_query .= " WHERE " . $where;
                } else {
                    return FALSE;
                }
                $res = $this->db->query($del_query);
            } else {
                if (is_numeric($where)) {
                    $this->db->where($this->primary_key, $where);
                } elseif ($where) {
                    $this->db->where($where, FALSE, FALSE);
                } else {
                    return FALSE;
                }
                $res = $this->db->delete($this->table_name);
            }
        }
        return $res;
    }

    /**
     * getData method is used to get data records for this module.
     * @param string $extra_cond extra_cond is the query condition for getting filtered data.
     * @param string $fields fields are either array or string.
     * @param string $order_by order_by is to append order by condition.
     * @param string $group_by group_by is to append group by condition.
     * @param string $limit limit is to append limit condition.
     * @param string $join join is to make joins with relation tables.
     * @param boolean $having_cond having cond is the query condition for getting conditional data.
     * @param boolean $list list is to differ listing fields or form fields.
     * @return array $data_arr returns data records array.
     */
    public function getData($extra_cond = "", $fields = "", $order_by = "", $group_by = "", $limit = "", $join = "No", $having_cond = '', $list = FALSE)
    {
        if (is_array($fields)) {
            $this->listing->addSelectFields($fields);
        } elseif ($fields != "") {
            $this->db->select($fields);
        } elseif ($list == TRUE) {
            $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_key);
            if ($this->primary_alias != "") {
                $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_alias);
            }
            $this->db->select("mrnd.vTitle AS mrnd_title");
            $this->db->select("mrnd.tDescription AS mrnd_description");
            $this->db->select("mrnd.eVersionStatus AS mrnd_version_status");
            $this->db->select("ma.vName AS ma_name");
        } else {
            $this->db->select("mrnd.iReleaseNoteDetailsId AS iReleaseNoteDetailsId");
            $this->db->select("mrnd.iReleaseNoteDetailsId AS mrnd_release_note_details_id");
            $this->db->select("mrnd.iReleaseNotesId AS mrnd_release_notes_id");
            $this->db->select("mrnd.vTitle AS mrnd_title");
            $this->db->select("mrnd.tDescription AS mrnd_description");
            $this->db->select("mrnd.eVersionStatus AS mrnd_version_status");
            $this->db->select("mrnd.dDateAdded AS mrnd_date_added");
            $this->db->select("mrnd.iAddedBy AS mrnd_added_by");
            $this->db->select("mrnd.dDateUpdated AS mrnd_date_updated");
            $this->db->select("mrnd.iUpdatedBy AS mrnd_updated_by");
        }

        $this->db->from($this->table_name . " AS " . $this->table_alias);
        if (is_array($join) && is_array($join['joins']) && count($join['joins']) > 0) {
            $this->listing->addJoinTables($join['joins']);
            if ($join["list"] == "Yes") {
                $this->addJoinTables("AR");
            }
        } else {
            if ($join == "Yes") {
                $this->addJoinTables("AR");
            }
        }
        if (is_array($extra_cond) && count($extra_cond) > 0) {
            $this->listing->addWhereFields($extra_cond);
        } elseif (is_numeric($extra_cond)) {
            $this->db->where($this->table_alias . "." . $this->primary_key, intval($extra_cond));
        } elseif ($extra_cond) {
            $this->db->where($extra_cond, FALSE, FALSE);
        }
        $this->general->getPhysicalRecordWhere($this->table_name, $this->table_alias, "AR");
        if ($group_by != "") {
            $this->db->group_by($group_by);
        }
        if ($having_cond != "") {
            $this->db->having($having_cond, FALSE, FALSE);
        }
        if ($order_by != "") {
            $this->db->order_by($order_by);
        }
        if ($limit != "") {
            if (is_numeric($limit)) {
                $this->db->limit($limit);
            } else {
                list($offset, $limit) = explode(",", $limit);
                $this->db->limit($offset, $limit);
            }
        }
        $data_obj = $this->db->get();
        $data_arr = is_object($data_obj) ? $data_obj->result_array() : array();
        #echo $this->db->last_query();
        return $data_arr;
    }

    /**
     * getListingData method is used to get grid listing data records for this module.
     * @param array $config_arr config_arr for grid listing settigs.
     * @return array $listing_data returns data records array for grid.
     */
    public function getListingData($config_arr = array())
    {
        $page = $config_arr['page'];
        $rows = $config_arr['rows'];
        $sidx = $config_arr['sidx'];
        $sord = $config_arr['sord'];
        $sdef = $config_arr['sdef'];
        $filters = $config_arr['filters'];

        $extra_cond = $config_arr['extra_cond'];
        $group_by = $config_arr['group_by'];
        $having_cond = $config_arr['having_cond'];
        $order_by = $config_arr['order_by'];

        $page = ($page != '') ? $page : 1;
        $rec_per_page = (intval($rows) > 0) ? intval($rows) : $this->rec_per_page;
        $extra_cond = ($extra_cond != "") ? $extra_cond : "";

        $this->db->start_cache();
        $this->db->from($this->table_name . " AS " . $this->table_alias);
        $this->addJoinTables("AR");
        if ($extra_cond != "") {
            $this->db->where($extra_cond, FALSE, FALSE);
        }
        $this->general->getPhysicalRecordWhere($this->table_name, $this->table_alias, "AR");
        if (is_array($group_by) && count($group_by) > 0) {
            $this->db->group_by($group_by);
        }
        if ($having_cond != "") {
            $this->db->having($having_cond, FALSE, FALSE);
        }
        $filter_config = array();
        $filter_config['module_config'] = $config_arr['module_config'];
        $filter_config['list_config'] = $config_arr['list_config'];
        $filter_config['form_config'] = $config_arr['form_config'];
        $filter_config['dropdown_arr'] = $config_arr['dropdown_arr'];
        $filter_config['search_config'] = $this->search_config;
        $filter_config['global_filters'] = $this->global_filters;
        $filter_config['table_name'] = $this->table_name;
        $filter_config['table_alias'] = $this->table_alias;
        $filter_config['primary_key'] = $this->primary_key;
        $filter_config['grid_fields'] = $this->grid_fields;

        $filter_main = $this->filter->applyFilter($filters, $filter_config, "Select");
        $filter_left = $this->filter->applyLeftFilter($filters, $filter_config, "Select");
        $filter_range = $this->filter->applyRangeFilter($filters, $filter_config, "Select");
        if ($filter_main != "") {
            $this->db->where("(" . $filter_main . ")", FALSE, FALSE);
        }
        if ($filter_left != "") {
            $this->db->where("(" . $filter_left . ")", FALSE, FALSE);
        }
        if ($filter_range != "") {
            $this->db->where("(" . $filter_range . ")", FALSE, FALSE);
        }

        $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_key);
        if ($this->primary_alias != "") {
            $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_alias);
        }
        $this->db->select("mrnd.vTitle AS mrnd_title");
        $this->db->select("mrnd.tDescription AS mrnd_description");
        $this->db->select("mrnd.eVersionStatus AS mrnd_version_status");
        $this->db->select("ma.vName AS ma_name");

        $this->db->stop_cache();
        if ((is_array($group_by) && count($group_by) > 0) || trim($having_cond) != "") {
            $this->db->select($this->table_alias . "." . $this->primary_key);
            $total_records_arr = $this->db->get();
            $total_records = is_object($total_records_arr) ? $total_records_arr->num_rows() : 0;
        } else {
            $total_records = $this->db->count_all_results();
        }

        $total_pages = $this->listing->getTotalPages($total_records, $rec_per_page);
        if ($sdef == "Yes" && is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $orK => $orV) {
                $sort_filed = $orV['field'];
                $sort_order = (strtolower($orV['order']) == "desc") ? "DESC" : "ASC";
                $this->db->order_by($sort_filed, $sort_order);
            }
        }
        if ($sidx != "") {
            $this->listing->addGridOrderBy($sidx, $sord, $config_arr['list_config']);
        }
        $limit_offset = $this->listing->getStartIndex($total_records, $page, $rec_per_page);
        $this->db->limit($rec_per_page, $limit_offset);
        $return_data_obj = $this->db->get();
        $return_data = is_object($return_data_obj) ? $return_data_obj->result_array() : array();
        $this->db->flush_cache();
        $listing_data = $this->listing->getDataForJqGrid($return_data, $filter_config, $page, $total_pages, $total_records);
        $this->listing_data = $return_data;
        #echo $this->db->last_query();
        return $listing_data;
    }

    /**
     * getExportData method is used to get grid export data records for this module.
     * @param array $config_arr config_arr for grid export settigs.
     * @return array $export_data returns data records array for export.
     */
    public function getExportData($config_arr = array())
    {
        $page = $config_arr['page'];
        $id = $config_arr['id'];
        $rows = $config_arr['rows'];
        $rowlimit = $config_arr['rowlimit'];
        $sidx = $config_arr['sidx'];
        $sord = $config_arr['sord'];
        $sdef = $config_arr['sdef'];
        $filters = $config_arr['filters'];

        $extra_cond = $config_arr['extra_cond'];
        $group_by = $config_arr['group_by'];
        $having_cond = $config_arr['having_cond'];
        $order_by = $config_arr['order_by'];

        $page = ($page != '') ? $page : 1;
        $extra_cond = ($extra_cond != "") ? $extra_cond : "";

        $this->db->from($this->table_name . " AS " . $this->table_alias);
        $this->addJoinTables("AR");
        if (is_array($id) && count($id) > 0) {
            $this->db->where_in($this->table_alias . "." . $this->primary_key, $id);
        }
        if ($extra_cond != "") {
            $this->db->where($extra_cond, FALSE, FALSE);
        }
        $this->general->getPhysicalRecordWhere($this->table_name, $this->table_alias, "AR");
        if (is_array($group_by) && count($group_by) > 0) {
            $this->db->group_by($group_by);
        }
        if ($having_cond != "") {
            $this->db->having($having_cond, FALSE, FALSE);
        }
        $filter_config = array();
        $filter_config['module_config'] = $config_arr['module_config'];
        $filter_config['list_config'] = $config_arr['list_config'];
        $filter_config['form_config'] = $config_arr['form_config'];
        $filter_config['dropdown_arr'] = $config_arr['dropdown_arr'];
        $filter_config['search_config'] = $this->search_config;
        $filter_config['global_filters'] = $this->global_filters;
        $filter_config['table_name'] = $this->table_name;
        $filter_config['table_alias'] = $this->table_alias;
        $filter_config['primary_key'] = $this->primary_key;

        $filter_main = $this->filter->applyFilter($filters, $filter_config, "Select");
        $filter_left = $this->filter->applyLeftFilter($filters, $filter_config, "Select");
        $filter_range = $this->filter->applyRangeFilter($filters, $filter_config, "Select");
        if ($filter_main != "") {
            $this->db->where("(" . $filter_main . ")", FALSE, FALSE);
        }
        if ($filter_left != "") {
            $this->db->where("(" . $filter_left . ")", FALSE, FALSE);
        }
        if ($filter_range != "") {
            $this->db->where("(" . $filter_range . ")", FALSE, FALSE);
        }

        $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_key);
        if ($this->primary_alias != "") {
            $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_alias);
        }
        $this->db->select("mrnd.vTitle AS mrnd_title");
        $this->db->select("mrnd.tDescription AS mrnd_description");
        $this->db->select("mrnd.eVersionStatus AS mrnd_version_status");
        $this->db->select("ma.vName AS ma_name");
        if ($sdef == "Yes" && is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $orK => $orV) {
                $sort_filed = $orV['field'];
                $sort_order = (strtolower($orV['order']) == "desc") ? "DESC" : "ASC";
                $this->db->order_by($sort_filed, $sort_order);
            }
        }
        if ($sidx != "") {
            $this->listing->addGridOrderBy($sidx, $sord, $config_arr['list_config']);
        }
        if ($rowlimit != "") {
            $offset = $rowlimit;
            $limit = ($rowlimit * $page - $rowlimit);
            $this->db->limit($offset, $limit);
        }
        $export_data_obj = $this->db->get();
        $export_data = is_object($export_data_obj) ? $export_data_obj->result_array() : array();
        #echo $this->db->last_query();
        return $export_data;
    }

    /**
     * addJoinTables method is used to make relation tables joins with main table.
     * @param string $type type is to get active record or join string.
     * @param boolean $allow_tables allow_table is to restrict some set of tables.
     * @return string $ret_joins returns relation tables join string.
     */
    public function addJoinTables($type = 'AR', $allow_tables = FALSE)
    {
        $join_tables = $this->join_tables;
        if (!is_array($join_tables) || count($join_tables) == 0) {
            return '';
        }
        $ret_joins = $this->listing->addJoinTables($join_tables, $type, $allow_tables);
        return $ret_joins;
    }

    /**
     * getListConfiguration method is used to get listing configuration array.
     * @param string $name name is to get specific field configuration.
     * @return array $config_arr returns listing configuration array.
     */
    public function getListConfiguration($name = "")
    {
        $list_config = array(
            "mrnd_title" => array(
                "name" => "mrnd_title",
                "table_name" => "mod_release_note_details",
                "table_alias" => "mrnd",
                "field_name" => "vTitle",
                "source_field" => "mrnd_title",
                "display_query" => "mrnd.vTitle",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "Title",
                "label_lang" => $this->lang->line('RELEASE_NOTES_DETAILS_TITLE'),
                "width" => 150,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "edit_link" => "Yes",
            ),
            "mrnd_description" => array(
                "name" => "mrnd_description",
                "table_name" => "mod_release_note_details",
                "table_alias" => "mrnd",
                "field_name" => "tDescription",
                "source_field" => "mrnd_description",
                "display_query" => "mrnd.tDescription",
                "entry_type" => "Table",
                "data_type" => "text",
                "show_in" => "Both",
                "type" => "wysiwyg",
                "align" => "left",
                "label" => "Description",
                "label_lang" => $this->lang->line('RELEASE_NOTES_DETAILS_DESCRIPTION'),
                "width" => 200,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
            ),
            "mrnd_version_status" => array(
                "name" => "mrnd_version_status",
                "table_name" => "mod_release_note_details",
                "table_alias" => "mrnd",
                "field_name" => "eVersionStatus",
                "source_field" => "mrnd_version_status",
                "display_query" => "mrnd.eVersionStatus",
                "entry_type" => "Table",
                "data_type" => "enum",
                "show_in" => "Both",
                "type" => "dropdown",
                "align" => "center",
                "label" => "Type",
                "label_lang" => $this->lang->line('RELEASE_NOTES_DETAILS_TYPE'),
                "width" => 100,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "default" => $this->filter->getDefaultValue("mrnd_version_status", "Text", "New Feature")
            ),
            "ma_name" => array(
                "name" => "ma_name",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "vName",
                "source_field" => "mrnd_added_by",
                "display_query" => "ma.vName",
                "entry_type" => "Table",
                "data_type" => "",
                "show_in" => "Both",
                "type" => "dropdown",
                "align" => "left",
                "label" => "Added By",
                "label_lang" => $this->lang->line('RELEASE_NOTES_DETAILS_ADDED_BY'),
                "width" => 100,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "related" => "Yes",
                "default" => $this->filter->getDefaultValue("mrnd_added_by", "Session", "iAdminId")
            )
        );

        $config_arr = array();
        if (is_array($name) && count($name) > 0) {
            $name_cnt = count($name);
            for ($i = 0; $i < $name_cnt; $i++) {
                $config_arr[$name[$i]] = $list_config[$name[$i]];
            }
        } elseif ($name != "" && is_string($name)) {
            $config_arr = $list_config[$name];
        } else {
            $config_arr = $list_config;
        }
        return $config_arr;
    }

    /**
     * getFormConfiguration method is used to get form configuration array.
     * @param string $name name is to get specific field configuration.
     * @return array $config_arr returns form configuration array.
     */
    public function getFormConfiguration($name = "")
    {
        $form_config = array(
            "mrnd_release_notes_id" => array(
                "name" => "mrnd_release_notes_id",
                "table_name" => "mod_release_note_details",
                "table_alias" => "mrnd",
                "field_name" => "iReleaseNotesId",
                "entry_type" => "Table",
                "data_type" => "int",
                "type" => "dropdown",
                "label" => "Release Notes",
                "label_lang" => $this->lang->line('RELEASE_NOTES_DETAILS_RELEASE_NOTES')
            ),
            "mrnd_title" => array(
                "name" => "mrnd_title",
                "table_name" => "mod_release_note_details",
                "table_alias" => "mrnd",
                "field_name" => "vTitle",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "type" => "textbox",
                "label" => "Title",
                "label_lang" => $this->lang->line('RELEASE_NOTES_DETAILS_TITLE')
            ),
            "mrnd_description" => array(
                "name" => "mrnd_description",
                "table_name" => "mod_release_note_details",
                "table_alias" => "mrnd",
                "field_name" => "tDescription",
                "entry_type" => "Table",
                "data_type" => "text",
                "type" => "wysiwyg",
                "label" => "Description",
                "label_lang" => $this->lang->line('RELEASE_NOTES_DETAILS_DESCRIPTION')
            ),
            "mrnd_version_status" => array(
                "name" => "mrnd_version_status",
                "table_name" => "mod_release_note_details",
                "table_alias" => "mrnd",
                "field_name" => "eVersionStatus",
                "entry_type" => "Table",
                "data_type" => "enum",
                "type" => "dropdown",
                "label" => "Type",
                "label_lang" => $this->lang->line('RELEASE_NOTES_DETAILS_TYPE'),
                "default" => $this->filter->getDefaultValue("mrnd_version_status", "Text", "New Feature"),
                "dfapply" => "addOnly"
            ),
            "mrnd_date_added" => array(
                "name" => "mrnd_date_added",
                "table_name" => "mod_release_note_details",
                "table_alias" => "mrnd",
                "field_name" => "dDateAdded",
                "entry_type" => "Table",
                "data_type" => "datetime",
                "type" => "date",
                "label" => "Date Added",
                "label_lang" => $this->lang->line('RELEASE_NOTES_DETAILS_DATE_ADDED'),
                "default" => $this->filter->getDefaultValue("mrnd_date_added", "MySQL", "NOW()"),
                "dfapply" => "addOnly",
                "format" => $this->general->getAdminPHPFormats('date')
            ),
            "mrnd_added_by" => array(
                "name" => "mrnd_added_by",
                "table_name" => "mod_release_note_details",
                "table_alias" => "mrnd",
                "field_name" => "iAddedBy",
                "entry_type" => "Table",
                "data_type" => "int",
                "type" => "dropdown",
                "label" => "Added By",
                "label_lang" => $this->lang->line('RELEASE_NOTES_DETAILS_ADDED_BY'),
                "default" => $this->filter->getDefaultValue("mrnd_added_by", "Session", "iAdminId"),
                "dfapply" => "addOnly",
            ),
            "mrnd_date_updated" => array(
                "name" => "mrnd_date_updated",
                "table_name" => "mod_release_note_details",
                "table_alias" => "mrnd",
                "field_name" => "dDateUpdated",
                "entry_type" => "Table",
                "data_type" => "datetime",
                "type" => "date",
                "label" => "Date Updated",
                "label_lang" => $this->lang->line('RELEASE_NOTES_DETAILS_DATE_UPDATED'),
                "default" => $this->filter->getDefaultValue("mrnd_date_updated", "MySQL", "NOW()"),
                "dfapply" => "forceApply",
                "format" => $this->general->getAdminPHPFormats('date')
            ),
            "mrnd_updated_by" => array(
                "name" => "mrnd_updated_by",
                "table_name" => "mod_release_note_details",
                "table_alias" => "mrnd",
                "field_name" => "iUpdatedBy",
                "entry_type" => "Table",
                "data_type" => "int",
                "type" => "dropdown",
                "label" => "Updated By",
                "label_lang" => $this->lang->line('RELEASE_NOTES_DETAILS_UPDATED_BY'),
                "default" => $this->filter->getDefaultValue("mrnd_updated_by", "Session", "IAdminId"),
                "dfapply" => "forceApply",
            )
        );

        $config_arr = array();
        if (is_array($name) && count($name) > 0) {
            $name_cnt = count($name);
            for ($i = 0; $i < $name_cnt; $i++) {
                $config_arr[$name[$i]] = $form_config[$name[$i]];
            }
        } elseif ($name != "" && is_string($name)) {
            $config_arr = $form_config[$name];
        } else {
            $config_arr = $form_config;
        }
        return $config_arr;
    }

    /**
     * checkRecordExists method is used to check duplication of records.
     * @param array $field_arr field_arr is having fields to check.
     * @param array $field_val field_val is having values of respective fields.
     * @param numeric $id id is to avoid current records.
     * @param string $mode mode is having either Add or Update.
     * @param string $con con is having either AND or OR.
     * @return boolean $exists returns either TRUE of FALSE.
     */
    public function checkRecordExists($field_arr = array(), $field_val = array(), $id = '', $mode = '', $con = 'AND')
    {
        $exists = FALSE;
        if (!is_array($field_arr) || count($field_arr) == 0) {
            return $exists;
        }
        foreach ((array) $field_arr as $key => $val) {
            $extra_cond_arr[] = $this->db->protect($this->table_alias . "." . $field_arr[$key]) . " =  " . $this->db->escape(trim($field_val[$val]));
        }
        $extra_cond = "(" . implode(" " . $con . " ", $extra_cond_arr) . ")";
        if ($mode == "Add") {
            $data = $this->getData($extra_cond, "COUNT(*) AS tot");
            if ($data[0]['tot'] > 0) {
                $exists = TRUE;
            }
        } elseif ($mode == "Update") {
            $extra_cond = $this->db->protect($this->table_alias . "." . $this->primary_key) . " <> " . $this->db->escape($id) . " AND " . $extra_cond;
            $data = $this->getData($extra_cond, "COUNT(*) AS tot");
            if ($data[0]['tot'] > 0) {
                $exists = TRUE;
            }
        }
        return $exists;
    }

    /**
     * getSwitchTo method is used to get switch to dropdown array.
     * @param string $extra_cond extra_cond is the query condition for getting filtered data.
     * @return array $switch_data returns data records array.
     */
    public function getSwitchTo($extra_cond = '', $type = 'records', $limit = '')
    {
        $switchto_fields = $this->switchto_fields;
        $switch_data = array();
        if (!is_array($switchto_fields) || count($switchto_fields) == 0) {
            if ($type == "count") {
                return count($switch_data);
            } else {
                return $switch_data;
            }
        }
        $fields_arr = array();
        $fields_arr[] = array("field" => $this->table_alias . "." . $this->primary_key . " AS id");
        $fields_arr[] = array("field" => $this->db->concat($switchto_fields) . " AS val", "escape" => TRUE);

        if (trim($this->extra_cond) != "") {
            $extra_cond = (trim($extra_cond) != "") ? $extra_cond . " AND " . $this->extra_cond : $this->extra_cond;
        }
        $switch_data = $this->getData($extra_cond, $fields_arr, "val ASC", "", $limit, "Yes");
        #echo $this->db->last_query();
        if ($type == "count") {
            return count($switch_data);
        } else {
            return $switch_data;
        }
    }
}
