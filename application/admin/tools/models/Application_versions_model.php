<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Application Versions Model
 *
 * @category admin
 *
 * @package tools
 *
 * @subpackage models
 *
 * @module Application Versions
 *
 * @class Application_versions_model.php
 *
 * @path application\admin\tools\models\Application_versions_model.php
 *
 * @version 4.3
 *
 * @author CIT Dev Team
 *
 * @date 14.11.2018
 */

class Application_versions_model extends CI_Model
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
     * @modified CIT Admin | 14.11.2018
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('listing');
        $this->load->library('filter');
        $this->load->library('dropdown');
        $this->module_name = "application_versions";
        $this->table_name = "mod_application_version";
        $this->table_alias = "mav";
        $this->primary_key = "iApplicationVersionId";
        $this->primary_alias = "mav_application_version_id";
        $this->physical_data_remove = "Yes";
        $this->grid_fields = array(
            "mav_version_name",
            "mav_version_number",
            "mav_application_url",
            "mav_date_published",
            "mrn_version_number",
            "ma_name",
            "mav_status",
        );
        $this->join_tables = array(
            array(
                "table_name" => "mod_application_master",
                "table_alias" => "mam",
                "field_name" => "iApplicationMasterId",
                "rel_table_name" => "mod_application_version",
                "rel_table_alias" => "mav",
                "rel_field_name" => "iApplicationMasterId",
                "join_type" => "left",
                "extra_condition" => "",
            ),
            array(
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "iAdminId",
                "rel_table_name" => "mod_application_master",
                "rel_table_alias" => "mam",
                "rel_field_name" => "iAddedBy",
                "join_type" => "left",
                "extra_condition" => "",
            ),
            array(
                "table_name" => "mod_release_notes",
                "table_alias" => "mrn",
                "field_name" => "iReleaseNotesId",
                "rel_table_name" => "mod_application_version",
                "rel_table_alias" => "mav",
                "rel_field_name" => "iReleaseNotesId",
                "join_type" => "left",
                "extra_condition" => "",
            )
        );
        $this->extra_cond = "";
        $this->groupby_cond = array();
        $this->having_cond = "";
        $this->orderby_cond = array();
        $this->unique_type = "AND";
        $this->unique_fields = array(
            "iApplicationMasterId",
            "vVersionNumber",
        );
        $this->switchto_fields = array(
            $this->db->protect("mav.vVersionName"),
            "' [ '",
            $this->db->protect("mav.vVersionNumber"),
            "' ]'",
        );
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
        if ($alias == "Yes")
        {
            if ($join == "Yes")
            {
                $join_tbls = $this->addJoinTables("NR");
            }
            if (trim($join_tbls) != '')
            {
                $set_cond = array();
                foreach ($data as $key => $val)
                {
                    $set_cond[] = $this->db->protect($key)." = ".$this->db->escape($val);
                }
                if (is_numeric($where))
                {
                    $extra_cond = " WHERE ".$this->db->protect($this->table_alias.".".$this->primary_key)." = ".$this->db->escape($where);
                }
                elseif ($where)
                {
                    $extra_cond = " WHERE ".$where;
                }
                else
                {
                    return FALSE;
                }
                $update_query = "UPDATE ".$this->db->protect($this->table_name)." AS ".$this->db->protect($this->table_alias)." ".$join_tbls." SET ".implode(", ", $set_cond)." ".$extra_cond;
                $res = $this->db->query($update_query);
            }
            else
            {
                if (is_numeric($where))
                {
                    $this->db->where($this->table_alias.".".$this->primary_key, $where);
                }
                elseif ($where)
                {
                    $this->db->where($where, FALSE, FALSE);
                }
                else
                {
                    return FALSE;
                }
                $res = $this->db->update($this->table_name." AS ".$this->table_alias, $data);
            }
        }
        else
        {
            if (is_numeric($where))
            {
                $this->db->where($this->primary_key, $where);
            }
            elseif ($where)
            {
                $this->db->where($where, FALSE, FALSE);
            }
            else
            {
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
        if ($this->config->item('PHYSICAL_RECORD_DELETE') && $this->physical_data_remove == 'No')
        {
            if ($alias == "Yes")
            {
                if (is_array($join['joins']) && count($join['joins']))
                {
                    $join_tbls = '';
                    if ($join['list'] == "Yes")
                    {
                        $join_tbls = $this->addJoinTables("NR");
                    }
                    $join_tbls .= ' '.$this->listing->addJoinTables($join['joins'], "NR");
                }
                elseif ($join == "Yes")
                {
                    $join_tbls = $this->addJoinTables("NR");
                }
                $data = $this->general->getPhysicalRecordUpdate($this->table_alias);
                if (trim($join_tbls) != '')
                {
                    $set_cond = array();
                    foreach ($data as $key => $val)
                    {
                        $set_cond[] = $this->db->protect($key)." = ".$this->db->escape($val);
                    }
                    if (is_numeric($where))
                    {
                        $extra_cond = " WHERE ".$this->db->protect($this->table_alias.".".$this->primary_key)." = ".$this->db->escape($where);
                    }
                    elseif ($where)
                    {
                        $extra_cond = " WHERE ".$where;
                    }
                    else
                    {
                        return FALSE;
                    }
                    $update_query = "UPDATE ".$this->db->protect($this->table_name)." AS ".$this->db->protect($this->table_alias)." ".$join_tbls." SET ".implode(", ", $set_cond)." ".$extra_cond;
                    $res = $this->db->query($update_query);
                }
                else
                {
                    if (is_numeric($where))
                    {
                        $this->db->where($this->table_alias.".".$this->primary_key, $where);
                    }
                    elseif ($where)
                    {
                        $this->db->where($where, FALSE, FALSE);
                    }
                    else
                    {
                        return FALSE;
                    }
                    $res = $this->db->update($this->table_name." AS ".$this->table_alias, $data);
                }
            }
            else
            {
                if (is_numeric($where))
                {
                    $this->db->where($this->primary_key, $where);
                }
                elseif ($where)
                {
                    $this->db->where($where, FALSE, FALSE);
                }
                else
                {
                    return FALSE;
                }
                $data = $this->general->getPhysicalRecordUpdate();
                $res = $this->db->update($this->table_name, $data);
            }
        }
        else
        {
            if ($alias == "Yes")
            {
                $del_query = "DELETE ".$this->db->protect($this->table_alias).".* FROM ".$this->db->protect($this->table_name)." AS ".$this->db->protect($this->table_alias);
                if (is_array($join['joins']) && count($join['joins']))
                {
                    if ($join['list'] == "Yes")
                    {
                        $del_query .= $this->addJoinTables("NR");
                    }
                    $del_query .= ' '.$this->listing->addJoinTables($join['joins'], "NR");
                }
                elseif ($join == "Yes")
                {
                    $del_query .= $this->addJoinTables("NR");
                }
                if (is_numeric($where))
                {
                    $del_query .= " WHERE ".$this->db->protect($this->table_alias).".".$this->db->protect($this->primary_key)." = ".$this->db->escape($where);
                }
                elseif ($where)
                {
                    $del_query .= " WHERE ".$where;
                }
                else
                {
                    return FALSE;
                }
                $res = $this->db->query($del_query);
            }
            else
            {
                if (is_numeric($where))
                {
                    $this->db->where($this->primary_key, $where);
                }
                elseif ($where)
                {
                    $this->db->where($where, FALSE, FALSE);
                }
                else
                {
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
        if (is_array($fields))
        {
            $this->listing->addSelectFields($fields);
        }
        elseif ($fields != "")
        {
            $this->db->select($fields);
        }
        elseif ($list == TRUE)
        {
            $this->db->select($this->table_alias.".".$this->primary_key." AS ".$this->primary_key);
            if ($this->primary_alias != "")
            {
                $this->db->select($this->table_alias.".".$this->primary_key." AS ".$this->primary_alias);
            }
            $this->db->select("mav.vVersionName AS mav_version_name");
            $this->db->select("mav.vVersionNumber AS mav_version_number");
            $this->db->select("mav.vApplicationUrl AS mav_application_url");
            $this->db->select("mav.dDatePublished AS mav_date_published");
            $this->db->select("mrn.vVersionNumber AS mrn_version_number");
            $this->db->select("ma.vName AS ma_name");
            $this->db->select("mav.eStatus AS mav_status");
            $this->db->select("mrn.iReleaseNotesId AS mrn_release_notes_id");
        }
        else
        {
            $this->db->select("mav.iApplicationVersionId AS iApplicationVersionId");
            $this->db->select("mav.iApplicationVersionId AS mav_application_version_id");
            $this->db->select("mav.iApplicationMasterId AS mav_application_master_id");
            $this->db->select("mav.vVersionName AS mav_version_name");
            $this->db->select("mav.vVersionNumber AS mav_version_number");
            $this->db->select("mav.eVersionType AS mav_version_type");
            $this->db->select("mav.vApplicationUrl AS mav_application_url");
            $this->db->select("mav.dDatePublished AS mav_date_published");
            $this->db->select("mav.iReleaseNotesId AS mav_release_notes_id");
            $this->db->select("mav.eStatus AS mav_status");
            $this->db->select("mav.dDateAdded AS mav_date_added");
            $this->db->select("mav.iAddedBy AS mav_added_by");
            $this->db->select("mav.dDateUpdated AS mav_date_updated");
            $this->db->select("mav.iUpdatedBy AS mav_updated_by");
        }

        $this->db->from($this->table_name." AS ".$this->table_alias);
        if (is_array($join) && is_array($join['joins']) && count($join['joins']) > 0)
        {
            $this->listing->addJoinTables($join['joins']);
            if ($join["list"] == "Yes")
            {
                $this->addJoinTables("AR");
            }
        }
        else
        {
            if ($join == "Yes")
            {
                $this->addJoinTables("AR");
            }
        }
        if (is_array($extra_cond) && count($extra_cond) > 0)
        {
            $this->listing->addWhereFields($extra_cond);
        }
        elseif (is_numeric($extra_cond))
        {
            $this->db->where($this->table_alias.".".$this->primary_key, intval($extra_cond));
        }
        elseif ($extra_cond)
        {
            $this->db->where($extra_cond, FALSE, FALSE);
        }
        $this->general->getPhysicalRecordWhere($this->table_name, $this->table_alias, "AR");
        if ($group_by != "")
        {
            $this->db->group_by($group_by);
        }
        if ($having_cond != "")
        {
            $this->db->having($having_cond, FALSE, FALSE);
        }
        if ($order_by != "")
        {
            $this->db->order_by($order_by);
        }
        if ($limit != "")
        {
            if (is_numeric($limit))
            {
                $this->db->limit($limit);
            }
            else
            {
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
        $this->db->from($this->table_name." AS ".$this->table_alias);
        $this->addJoinTables("AR");
        if ($extra_cond != "")
        {
            $this->db->where($extra_cond, FALSE, FALSE);
        }
        $this->general->getPhysicalRecordWhere($this->table_name, $this->table_alias, "AR");
        if (is_array($group_by) && count($group_by) > 0)
        {
            $this->db->group_by($group_by);
        }
        if ($having_cond != "")
        {
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
        if ($filter_main != "")
        {
            $this->db->where("(".$filter_main.")", FALSE, FALSE);
        }
        if ($filter_left != "")
        {
            $this->db->where("(".$filter_left.")", FALSE, FALSE);
        }
        if ($filter_range != "")
        {
            $this->db->where("(".$filter_range.")", FALSE, FALSE);
        }

        $this->db->select($this->table_alias.".".$this->primary_key." AS ".$this->primary_key);
        if ($this->primary_alias != "")
        {
            $this->db->select($this->table_alias.".".$this->primary_key." AS ".$this->primary_alias);
        }
        $this->db->select("mav.vVersionName AS mav_version_name");
        $this->db->select("mav.vVersionNumber AS mav_version_number");
        $this->db->select("mav.vApplicationUrl AS mav_application_url");
        $this->db->select("mav.dDatePublished AS mav_date_published");
        $this->db->select("mrn.vVersionNumber AS mrn_version_number");
        $this->db->select("ma.vName AS ma_name");
        $this->db->select("mav.eStatus AS mav_status");
        $this->db->select("mrn.iReleaseNotesId AS mrn_release_notes_id");

        $this->db->stop_cache();
        if ((is_array($group_by) && count($group_by) > 0) || trim($having_cond) != "")
        {
            $this->db->select($this->table_alias.".".$this->primary_key);
            $total_records_arr = $this->db->get();
            $total_records = is_object($total_records_arr) ? $total_records_arr->num_rows() : 0;
        }
        else
        {
            $total_records = $this->db->count_all_results();
        }

        $total_pages = $this->listing->getTotalPages($total_records, $rec_per_page);
        if ($sdef == "Yes" && is_array($order_by) && count($order_by) > 0)
        {
            foreach ($order_by as $orK => $orV)
            {
                $sort_filed = $orV['field'];
                $sort_order = (strtolower($orV['order']) == "desc") ? "DESC" : "ASC";
                $this->db->order_by($sort_filed, $sort_order);
            }
        }
        if ($sidx != "")
        {
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

        $this->db->from($this->table_name." AS ".$this->table_alias);
        $this->addJoinTables("AR");
        if (is_array($id) && count($id) > 0)
        {
            $this->db->where_in($this->table_alias.".".$this->primary_key, $id);
        }
        if ($extra_cond != "")
        {
            $this->db->where($extra_cond, FALSE, FALSE);
        }
        $this->general->getPhysicalRecordWhere($this->table_name, $this->table_alias, "AR");
        if (is_array($group_by) && count($group_by) > 0)
        {
            $this->db->group_by($group_by);
        }
        if ($having_cond != "")
        {
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
        if ($filter_main != "")
        {
            $this->db->where("(".$filter_main.")", FALSE, FALSE);
        }
        if ($filter_left != "")
        {
            $this->db->where("(".$filter_left.")", FALSE, FALSE);
        }
        if ($filter_range != "")
        {
            $this->db->where("(".$filter_range.")", FALSE, FALSE);
        }

        $this->db->select($this->table_alias.".".$this->primary_key." AS ".$this->primary_key);
        if ($this->primary_alias != "")
        {
            $this->db->select($this->table_alias.".".$this->primary_key." AS ".$this->primary_alias);
        }
        $this->db->select("mav.vVersionName AS mav_version_name");
        $this->db->select("mav.vVersionNumber AS mav_version_number");
        $this->db->select("mav.vApplicationUrl AS mav_application_url");
        $this->db->select("mav.dDatePublished AS mav_date_published");
        $this->db->select("mrn.vVersionNumber AS mrn_version_number");
        $this->db->select("ma.vName AS ma_name");
        $this->db->select("mav.eStatus AS mav_status");
        if ($sdef == "Yes" && is_array($order_by) && count($order_by) > 0)
        {
            foreach ($order_by as $orK => $orV)
            {
                $sort_filed = $orV['field'];
                $sort_order = (strtolower($orV['order']) == "desc") ? "DESC" : "ASC";
                $this->db->order_by($sort_filed, $sort_order);
            }
        }
        if ($sidx != "")
        {
            $this->listing->addGridOrderBy($sidx, $sord, $config_arr['list_config']);
        }
        if ($rowlimit != "")
        {
            $offset = $rowlimit;
            $limit = ($rowlimit*$page-$rowlimit);
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
        if (!is_array($join_tables) || count($join_tables) == 0)
        {
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
            "mav_version_name" => array(
                "name" => "mav_version_name",
                "table_name" => "mod_application_version",
                "table_alias" => "mav",
                "field_name" => "vVersionName",
                "source_field" => "mav_version_name",
                "display_query" => "mav.vVersionName",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "Version Name",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_VERSION_NAME'),
                "width" => 100,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "edit_link" => "Yes",
            ),
            "mav_version_number" => array(
                "name" => "mav_version_number",
                "table_name" => "mod_application_version",
                "table_alias" => "mav",
                "field_name" => "vVersionNumber",
                "source_field" => "mav_version_number",
                "display_query" => "mav.vVersionNumber",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "Version Number",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_VERSION_NUMBER'),
                "width" => 75,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
            ),
            "mav_application_url" => array(
                "name" => "mav_application_url",
                "table_name" => "mod_application_version",
                "table_alias" => "mav",
                "field_name" => "vApplicationUrl",
                "source_field" => "mav_application_url",
                "display_query" => "mav.vApplicationUrl",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "Application URL",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_APPLICATION_URL'),
                "width" => 200,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "edit_link" => "Yes",
                "custom_link" => "Yes",
            ),
            "mav_date_published" => array(
                "name" => "mav_date_published",
                "table_name" => "mod_application_version",
                "table_alias" => "mav",
                "field_name" => "dDatePublished",
                "source_field" => "mav_date_published",
                "display_query" => "mav.dDatePublished",
                "entry_type" => "Table",
                "data_type" => "datetime",
                "show_in" => "Both",
                "type" => "date_and_time",
                "align" => "left",
                "label" => "Published On",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_PUBLISHED_ON'),
                "width" => 100,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "format" => $this->general->getAdminPHPFormats('date_and_time')
            ),
            "mrn_version_number" => array(
                "name" => "mrn_version_number",
                "table_name" => "mod_release_notes",
                "table_alias" => "mrn",
                "field_name" => "vVersionNumber",
                "source_field" => "mav_release_notes_id",
                "display_query" => "mrn.vVersionNumber",
                "entry_type" => "Table",
                "data_type" => "int",
                "show_in" => "Both",
                "type" => "dropdown",
                "align" => "left",
                "label" => "Release Notes",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_RELEASE_NOTES'),
                "width" => 100,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "related" => "Yes",
                "edit_link" => "Yes",
                "custom_link" => "Yes",
            ),
            "ma_name" => array(
                "name" => "ma_name",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "vName",
                "source_field" => "mav_added_by",
                "display_query" => "ma.vName",
                "entry_type" => "Table",
                "data_type" => "",
                "show_in" => "Both",
                "type" => "dropdown",
                "align" => "left",
                "label" => "Added By",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_ADDED_BY'),
                "width" => 100,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "related" => "Yes",
                "default" => $this->filter->getDefaultValue("mav_added_by",
                "Session",
                "iAdminId")
            ),
            "mav_status" => array(
                "name" => "mav_status",
                "table_name" => "mod_application_version",
                "table_alias" => "mav",
                "field_name" => "eStatus",
                "source_field" => "mav_status",
                "display_query" => "mav.eStatus",
                "entry_type" => "Table",
                "data_type" => "enum",
                "show_in" => "Both",
                "type" => "dropdown",
                "align" => "center",
                "label" => "Status",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_STATUS'),
                "width" => 75,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "default" => $this->filter->getDefaultValue("mav_status",
                "Text",
                "Active")
            ),
            "mrn_release_notes_id" => array(
                "name" => "mrn_release_notes_id",
                "table_name" => "mod_release_notes",
                "table_alias" => "mrn",
                "field_name" => "iReleaseNotesId",
                "source_field" => "mav_release_notes_id",
                "display_query" => "mrn.iReleaseNotesId",
                "entry_type" => "Table",
                "data_type" => "int",
                "show_in" => "None",
                "type" => "dropdown",
                "align" => "center",
                "label" => "Release Notes Id",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_RELEASE_NOTES_ID'),
                "width" => 50,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "related" => "Yes",
            )
        );

        $config_arr = array();
        if (is_array($name) && count($name) > 0)
        {
            $name_cnt = count($name);
            for ($i = 0; $i < $name_cnt; $i++)
            {
                $config_arr[$name[$i]] = $list_config[$name[$i]];
            }
        }
        elseif ($name != "" && is_string($name))
        {
            $config_arr = $list_config[$name];
        }
        else
        {
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
            "mav_application_master_id" => array(
                "name" => "mav_application_master_id",
                "table_name" => "mod_application_version",
                "table_alias" => "mav",
                "field_name" => "iApplicationMasterId",
                "entry_type" => "Table",
                "data_type" => "int",
                "type" => "dropdown",
                "label" => "Application Master",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_APPLICATION_MASTER')
            ),
            "mav_version_name" => array(
                "name" => "mav_version_name",
                "table_name" => "mod_application_version",
                "table_alias" => "mav",
                "field_name" => "vVersionName",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "type" => "textbox",
                "label" => "Version Name",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_VERSION_NAME')
            ),
            "mav_version_number" => array(
                "name" => "mav_version_number",
                "table_name" => "mod_application_version",
                "table_alias" => "mav",
                "field_name" => "vVersionNumber",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "type" => "textbox",
                "label" => "Version Number",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_VERSION_NUMBER')
            ),
            "mav_version_type" => array(
                "name" => "mav_version_type",
                "table_name" => "mod_application_version",
                "table_alias" => "mav",
                "field_name" => "eVersionType",
                "entry_type" => "Table",
                "data_type" => "enum",
                "type" => "dropdown",
                "label" => "Version Type",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_VERSION_TYPE'),
                "default" => $this->filter->getDefaultValue("mav_version_type",
                "Text",
                "URL"),
                "dfapply" => "addOnly",
            ),
            "mav_application_url" => array(
                "name" => "mav_application_url",
                "table_name" => "mod_application_version",
                "table_alias" => "mav",
                "field_name" => "vApplicationUrl",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "type" => "textbox",
                "label" => "Application URL",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_APPLICATION_URL')
            ),
            "sys_application_file" => array(
                "name" => "sys_application_file",
                "table_name" => "",
                "table_alias" => "",
                "field_name" => "sysCustomField_1",
                "entry_type" => "Custom",
                "data_type" => "",
                "type" => "file",
                "label" => "Application File",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_APPLICATION_FILE'),
                "function" => "getMobileApplicationFile",
                "functype" => "value",
                "file_upload" => "Yes",
                "file_server" => "local",
                "file_folder" => "mobile_applications",
                "file_width" => "50",
                "file_height" => "50",
                "file_format" => "apk,ipa,zip",
                "file_size" => "512000",
            ),
            "mav_date_published" => array(
                "name" => "mav_date_published",
                "table_name" => "mod_application_version",
                "table_alias" => "mav",
                "field_name" => "dDatePublished",
                "entry_type" => "Table",
                "data_type" => "datetime",
                "type" => "date_and_time",
                "label" => "Published On",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_PUBLISHED_ON'),
                "format" => $this->general->getAdminPHPFormats('date_and_time')
            ),
            "mav_release_notes_id" => array(
                "name" => "mav_release_notes_id",
                "table_name" => "mod_application_version",
                "table_alias" => "mav",
                "field_name" => "iReleaseNotesId",
                "entry_type" => "Table",
                "data_type" => "int",
                "type" => "dropdown",
                "label" => "Release Notes",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_RELEASE_NOTES')
            ),
            "mav_status" => array(
                "name" => "mav_status",
                "table_name" => "mod_application_version",
                "table_alias" => "mav",
                "field_name" => "eStatus",
                "entry_type" => "Table",
                "data_type" => "enum",
                "type" => "dropdown",
                "label" => "Status",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_STATUS'),
                "default" => $this->filter->getDefaultValue("mav_status",
                "Text",
                "Active"),
                "dfapply" => "addOnly",
            ),
            "mav_date_added" => array(
                "name" => "mav_date_added",
                "table_name" => "mod_application_version",
                "table_alias" => "mav",
                "field_name" => "dDateAdded",
                "entry_type" => "Table",
                "data_type" => "datetime",
                "type" => "date_and_time",
                "label" => "Date Added",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_DATE_ADDED'),
                "default" => $this->filter->getDefaultValue("mav_date_added",
                "MySQL",
                "NOW()"),
                "dfapply" => "addOnly",
                "format" => $this->general->getAdminPHPFormats('date_and_time')
            ),
            "mav_added_by" => array(
                "name" => "mav_added_by",
                "table_name" => "mod_application_version",
                "table_alias" => "mav",
                "field_name" => "iAddedBy",
                "entry_type" => "Table",
                "data_type" => "int",
                "type" => "dropdown",
                "label" => "Added By",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_ADDED_BY'),
                "default" => $this->filter->getDefaultValue("mav_added_by",
                "Session",
                "iAdminId"),
                "dfapply" => "addOnly",
            ),
            "mav_date_updated" => array(
                "name" => "mav_date_updated",
                "table_name" => "mod_application_version",
                "table_alias" => "mav",
                "field_name" => "dDateUpdated",
                "entry_type" => "Table",
                "data_type" => "datetime",
                "type" => "date_and_time",
                "label" => "Date Updated",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_DATE_UPDATED'),
                "default" => $this->filter->getDefaultValue("mav_date_updated",
                "MySQL",
                "NOW()"),
                "dfapply" => "everyUpdate",
                "format" => $this->general->getAdminPHPFormats('date_and_time')
            ),
            "mav_updated_by" => array(
                "name" => "mav_updated_by",
                "table_name" => "mod_application_version",
                "table_alias" => "mav",
                "field_name" => "iUpdatedBy",
                "entry_type" => "Table",
                "data_type" => "int",
                "type" => "dropdown",
                "label" => "Updated By",
                "label_lang" => $this->lang->line('APPLICATION_VERSIONS_UPDATED_BY'),
                "default" => $this->filter->getDefaultValue("mav_updated_by",
                "Session",
                "iAdminId"),
                "dfapply" => "forceApply",
            )
        );

        $config_arr = array();
        if (is_array($name) && count($name) > 0)
        {
            $name_cnt = count($name);
            for ($i = 0; $i < $name_cnt; $i++)
            {
                $config_arr[$name[$i]] = $form_config[$name[$i]];
            }
        }
        elseif ($name != "" && is_string($name))
        {
            $config_arr = $form_config[$name];
        }
        else
        {
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
        if (!is_array($field_arr) || count($field_arr) == 0)
        {
            return $exists;
        }
        foreach ((array) $field_arr as $key => $val)
        {
            $extra_cond_arr[] = $this->db->protect($this->table_alias.".".$field_arr[$key])." =  ".$this->db->escape(trim($field_val[$val]));
        }
        $extra_cond = "(".implode(" ".$con." ", $extra_cond_arr).")";
        if ($mode == "Add")
        {
            $data = $this->getData($extra_cond, "COUNT(*) AS tot");
            if ($data[0]['tot'] > 0)
            {
                $exists = TRUE;
            }
        }
        elseif ($mode == "Update")
        {
            $extra_cond = $this->db->protect($this->table_alias.".".$this->primary_key)." <> ".$this->db->escape($id)." AND ".$extra_cond;
            $data = $this->getData($extra_cond, "COUNT(*) AS tot");
            if ($data[0]['tot'] > 0)
            {
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
        if (!is_array($switchto_fields) || count($switchto_fields) == 0)
        {
            if ($type == "count")
            {
                return count($switch_data);
            }
            else
            {
                return $switch_data;
            }
        }
        $fields_arr = array();
        $fields_arr[] = array(
            "field" => $this->table_alias.".".$this->primary_key." AS id",
        );
        $fields_arr[] = array(
            "field" => $this->db->concat($switchto_fields)." AS val",
            "escape" => TRUE,
        );
        if (trim($this->extra_cond) != "")
        {
            $extra_cond = (trim($extra_cond) != "") ? $extra_cond." AND ".$this->extra_cond : $this->extra_cond;
        }
        $switch_data = $this->getData($extra_cond, $fields_arr, "val ASC", "", $limit, "Yes");
        #echo $this->db->last_query();
        if ($type == "count")
        {
            return count($switch_data);
        }
        else
        {
            return $switch_data;
        }
    }

    /**
     * processCustomLinks method is used to process grid custom link settings configuration.
     * @param array $data_arr data_arr is to process data records.
     * @param array $config_arr config_arr for process custom link settings.
     * @return array $data_arr returns data records array.
     */
    public function processCustomLinks($data_arr = array(), $config_arr = array())
    {
        $custom_link_config = array(
            "mav_application_url" => array(
                array(
                    "open" => "NewPage",
                    "apply" => "No",
                    "block" => array(
                        "oper" => "",
                        "conditions" => array()
                    ),
                    "module_type" => "Custom",
                    "module_name" => "",
                    "folder_name" => "",
                    "module_page" => "",
                    "custom_link" => "@mav_application_url@",
                    "extra_params" => array()
                )
            ),
            "mrn_version_number" => array(
                array(
                    "open" => "NewPage",
                    "apply" => "Yes",
                    "block" => array(
                        "oper" => "AND",
                        "conditions" => array(
                            array(
                                "mod_1" => "Variable",
                                "val_1" => "mav_version_name",
                                "oper" => "eq",
                                "type" => "string",
                                "mod_2" => "Variable",
                                "val_2" => "mav_version_name",
                            )
                        )
                    ),
                    "module_type" => "Custom",
                    "module_name" => "",
                    "folder_name" => "",
                    "module_page" => "",
                    "custom_link" => "tools/release_notes/preview",
                    "extra_params" => array(
                        array(
                            "req_var" => "release_id",
                            "req_val" => "mrn_release_notes_id",
                            "req_mod" => "Variable",
                        )
                    )
                )
            )
        );
        $grid_fields = $this->grid_fields;
        $listing_data = $this->listing_data;
        if (!is_array($listing_data) || count($listing_data) == 0)
        {
            return $data_arr;
        }
        $rows_arr = $data_arr['rows'];
        foreach ($listing_data as $dKey => $dVal)
        {
            $custom_links_arr = array();
            $id = $dVal["iApplicationVersionId"];
            foreach ($grid_fields as $gKey => $gVal)
            {
                if ($config_arr[$gVal]['custom_link'] == "Yes" && $config_arr[$gVal]['edit_link'] == "Yes")
                {
                    $custom_link_temp = $this->listing->getGridCustomEditLink($custom_link_config[$gVal], $dVal[$gVal], $dVal, $id);
                    if ($custom_link_temp['success'])
                    {
                        $data_arr['rows'][$dKey][$gVal] = $custom_link_temp['formated_link'];
                        $custom_links_arr[$gVal]['link'] = $custom_link_temp['actual_link'];
                        $custom_links_arr[$gVal]['extra_attr_str'] = $custom_link_temp['extra_attr_str'];
                    }
                }
            }
            if (is_array($custom_links_arr) && count($custom_links_arr) > 0)
            {
                $data_arr['links'][$this->general->getAdminEncodeURL($id, 0)] = $custom_links_arr;
            }
        }
        return $data_arr;
    }
}
