<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Admin Model
 *
 * @category admin
 *
 * @package user
 *
 * @subpackage models
 *
 * @module Admin
 *
 * @class Admin_model.php
 *
 * @path application\admin\user\models\Admin_model.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @date 24.09.2019
 */

class Admin_model extends CI_Model
{
    public $table_name;
    public $table_alias;
    public $primary_key;
    public $primary_alias;
    public $insert_id;
    //
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
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 24.09.2019
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('listing');
        $this->load->library('filter');
        $this->load->library('dropdown');
        $this->module_name = "admin";
        $this->table_name = "mod_admin";
        $this->table_alias = "ma";
        $this->primary_key = "iAdminId";
        $this->primary_alias = "ma_admin_id";
        $this->physical_data_remove = "Yes";
        $this->grid_fields = array(
            "ma_name",
            "ma_email",
            "ma_phonenumber",
            "ma_user_name",
            "mgm_group_name",
            "ma_last_access",
            "login_as_btn",
            "ma_status",
            "extra_fields",
        );
        $this->join_tables = array(
            array(
                "table_name" => "mod_group_master",
                "table_alias" => "mgm",
                "field_name" => "iGroupId",
                "rel_table_name" => "mod_admin",
                "rel_table_alias" => "ma",
                "rel_field_name" => "iGroupId",
                "join_type" => "left",
                "extra_condition" => "",
            )
        );
        $this->extra_cond = "";
        $this->groupby_cond = array();
        $this->having_cond = "";
        $this->orderby_cond = array();
        $this->unique_type = "OR";
        $this->unique_fields = array(
            "vEmail",
            "vUserName",
        );
        $this->switchto_fields = array(
            $this->db->protect("ma.vName")
        );
        $this->switchto_options = array(
            $this->db->protect("ma.vName")." AS name1",
        );
        $this->default_filters = array();
        $this->global_filters = array();
        $this->search_config = array(
            "mgm_group_name" => array(
                "name" => "mgm_group_name",
                "label" => "Group Name",
                "label_lang" => $this->lang->line('ADMIN_GROUP_NAME'),
                "order" => "ASC",
                "key" => "ma.iGroupId",
                "type" => "dropdown",
                "count" => "",
            ),
            "ma_status" => array(
                "name" => "ma_status",
                "label" => "Status",
                "label_lang" => $this->lang->line('ADMIN_STATUS'),
                "order" => "ASC",
                "key" => "",
                "type" => "dropdown",
                "count" => "",
            )
        );
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
        $this->insert_id = $insert_id;
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
            $this->db->select("ma.vName AS ma_name");
            $this->db->select("ma.vEmail AS ma_email");
            $this->db->select("ma.vPhonenumber AS ma_phonenumber");
            $this->db->select("ma.vUserName AS ma_user_name");
            $this->db->select("mgm.vGroupName AS mgm_group_name");
            $this->db->select("ma.dLastAccess AS ma_last_access");
            $this->db->select("('Login As') AS login_as_btn", FALSE);
            $this->db->select("ma.eStatus AS ma_status");
            $this->db->select("('Extra Fields') AS extra_fields", FALSE);
        }
        else
        {
            $this->db->select("ma.iAdminId AS iAdminId");
            $this->db->select("ma.iAdminId AS ma_admin_id");
            $this->db->select("ma.vName AS ma_name");
            $this->db->select("ma.vEmail AS ma_email");
            $this->db->select("ma.vUserName AS ma_user_name");
            $this->db->select("ma.vPassword AS ma_password");
            $this->db->select("ma.eAuthType AS ma_auth_type");
            $this->db->select("ma.vPhonenumber AS ma_phonenumber");
            $this->db->select("ma.iGroupId AS ma_group_id");
            $this->db->select("ma.eStatus AS ma_status");
            $this->db->select("ma.vAuthCode AS ma_auth_code");
            $this->db->select("ma.dLastAccess AS ma_last_access");
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

        $this->db->stop_cache();
        if ((is_array($group_by) && count($group_by) > 0) || trim($having_cond) != "")
        {
            $total_records_arr = $this->db->get();
            $total_records = is_object($total_records_arr) ? $total_records_arr->num_rows() : 0;
        }
        else
        {
            $total_records = $this->db->count_all_results();
        }
        $total_pages = $this->listing->getTotalPages($total_records, $rec_per_page);

        $this->db->select($this->table_alias.".".$this->primary_key." AS ".$this->primary_key);
        if ($this->primary_alias != "")
        {
            $this->db->select($this->table_alias.".".$this->primary_key." AS ".$this->primary_alias);
        }
        $this->db->select("ma.vName AS ma_name");
        $this->db->select("ma.vEmail AS ma_email");
        $this->db->select("ma.vPhonenumber AS ma_phonenumber");
        $this->db->select("ma.vUserName AS ma_user_name");
        $this->db->select("mgm.vGroupName AS mgm_group_name");
        $this->db->select("ma.dLastAccess AS ma_last_access");
        $this->db->select("('Login As') AS login_as_btn", FALSE);
        $this->db->select("ma.eStatus AS ma_status");
        $this->db->select("('Extra Fields') AS extra_fields", FALSE);
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
        $this->db->select("ma.vName AS ma_name");
        $this->db->select("ma.vEmail AS ma_email");
        $this->db->select("ma.vPhonenumber AS ma_phonenumber");
        $this->db->select("ma.vUserName AS ma_user_name");
        $this->db->select("mgm.vGroupName AS mgm_group_name");
        $this->db->select("ma.dLastAccess AS ma_last_access");
        $this->db->select("('Login As') AS login_as_btn", FALSE);
        $this->db->select("ma.eStatus AS ma_status");
        $this->db->select("('Extra Fields') AS extra_fields", FALSE);
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
            "ma_name" => array(
                "name" => "ma_name",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "vName",
                "source_field" => "ma_name",
                "display_query" => "ma.vName",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "Name",
                "lang_code" => "ADMIN_NAME",
                "label_lang" => $this->lang->line('ADMIN_NAME'),
                "width" => 200,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "Yes",
                "viewedit" => "Yes",
                "edit_link" => "Yes",
            ),
            "ma_email" => array(
                "name" => "ma_email",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "vEmail",
                "source_field" => "ma_email",
                "display_query" => "ma.vEmail",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "Email",
                "lang_code" => "ADMIN_EMAIL",
                "label_lang" => $this->lang->line('ADMIN_EMAIL'),
                "width" => 200,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "Yes",
                "viewedit" => "Yes",
            ),
            "ma_phonenumber" => array(
                "name" => "ma_phonenumber",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "vPhonenumber",
                "source_field" => "ma_phonenumber",
                "display_query" => "ma.vPhonenumber",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "phone_number",
                "align" => "left",
                "label" => "Phonenumber",
                "lang_code" => "ADMIN_PHONENUMBER",
                "label_lang" => $this->lang->line('ADMIN_PHONENUMBER'),
                "width" => 150,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "Yes",
                "viewedit" => "Yes",
                "format" => $this->general->getAdminPHPFormats('phone')
            ),
            "ma_user_name" => array(
                "name" => "ma_user_name",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "vUserName",
                "source_field" => "ma_user_name",
                "display_query" => "ma.vUserName",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "User Name",
                "lang_code" => "ADMIN_USER_NAME",
                "label_lang" => $this->lang->line('ADMIN_USER_NAME'),
                "width" => 150,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "Yes",
                "viewedit" => "Yes",
            ),
            "mgm_group_name" => array(
                "name" => "mgm_group_name",
                "table_name" => "mod_group_master",
                "table_alias" => "mgm",
                "field_name" => "vGroupName",
                "source_field" => "ma_group_id",
                "display_query" => "mgm.vGroupName",
                "entry_type" => "Table",
                "data_type" => "int",
                "show_in" => "Both",
                "type" => "dropdown",
                "align" => "left",
                "label" => "Group Name",
                "lang_code" => "ADMIN_GROUP_NAME",
                "label_lang" => $this->lang->line('ADMIN_GROUP_NAME'),
                "width" => 180,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "Yes",
                "viewedit" => "Yes",
                "related" => "Yes",
            ),
            "ma_last_access" => array(
                "name" => "ma_last_access",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "dLastAccess",
                "source_field" => "ma_last_access",
                "display_query" => "ma.dLastAccess",
                "entry_type" => "Table",
                "data_type" => "datetime",
                "show_in" => "Both",
                "type" => "date_and_time",
                "align" => "left",
                "label" => "Last Access",
                "lang_code" => "ADMIN_LAST_ACCESS",
                "label_lang" => $this->lang->line('ADMIN_LAST_ACCESS'),
                "width" => 180,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "Yes",
                "viewedit" => "Yes",
                "format" => $this->general->getAdminPHPFormats('date_and_time')
            ),
            "login_as_btn" => array(
                "name" => "login_as_btn",
                "table_name" => "",
                "table_alias" => "",
                "field_name" => "",
                "source_field" => "",
                "display_query" => "Login As",
                "entry_type" => "Custom",
                "data_type" => "",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "center",
                "label" => "Login As",
                "lang_code" => "ADMIN_LOGIN_AS",
                "label_lang" => $this->lang->line('ADMIN_LOGIN_AS'),
                "width" => 150,
                "search" => "No",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "php_func" => "prepareAdminLoginAsBtn",
            ),
            "ma_status" => array(
                "name" => "ma_status",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "eStatus",
                "source_field" => "ma_status",
                "display_query" => "ma.eStatus",
                "entry_type" => "Table",
                "data_type" => "enum",
                "show_in" => "Both",
                "type" => "dropdown",
                "align" => "center",
                "label" => "Status",
                "lang_code" => "ADMIN_STATUS",
                "label_lang" => $this->lang->line('ADMIN_STATUS'),
                "width" => 150,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "Yes",
                "viewedit" => "Yes",
                "default" => $this->filter->getDefaultValue("ma_status",
                "Text",
                "Active")
            ),
            "extra_fields" => array(
                "name" => "extra_fields",
                "table_name" => "",
                "table_alias" => "",
                "field_name" => "",
                "source_field" => "",
                "display_query" => "Extra Fields",
                "entry_type" => "Custom",
                "data_type" => "",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "Extra Fields",
                "lang_code" => "ADMIN_EXTRA_FIELDS",
                "label_lang" => $this->lang->line('ADMIN_EXTRA_FIELDS'),
                "width" => 100,
                "search" => "No",
                "export" => "Yes",
                "sortable" => "No",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "hidden" => "Yes",
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
            "ma_name" => array(
                "name" => "ma_name",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "vName",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "Name",
                "lang_code" => "ADMIN_NAME",
                "label_lang" => $this->lang->line('ADMIN_NAME')
            ),
            "ma_email" => array(
                "name" => "ma_email",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "vEmail",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "Email",
                "lang_code" => "ADMIN_EMAIL",
                "label_lang" => $this->lang->line('ADMIN_EMAIL')
            ),
            "ma_user_name" => array(
                "name" => "ma_user_name",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "vUserName",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "User Name",
                "lang_code" => "ADMIN_USER_NAME",
                "label_lang" => $this->lang->line('ADMIN_USER_NAME')
            ),
            "ma_password" => array(
                "name" => "ma_password",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "vPassword",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "password",
                "label" => "Password",
                "lang_code" => "ADMIN_PASSWORD",
                "label_lang" => $this->lang->line('ADMIN_PASSWORD'),
                "encrypt" => "Yes",
                "enctype" => "bcrypt",
            ),
            "ma_auth_type" => array(
                "name" => "ma_auth_type",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "eAuthType",
                "entry_type" => "Table",
                "data_type" => "set",
                "show_input" => "Hidden",
                "type" => "checkboxes",
                "label" => "2-Factor Auth.",
                "lang_code" => "ADMIN_2_C45FACTOR_AUTH_C46",
                "label_lang" => $this->lang->line('ADMIN_2_C45FACTOR_AUTH_C46'),
                "default" => $this->filter->getDefaultValue("ma_auth_type",
                "Text",
                "noAuth"),
                "dfapply" => "forceApply",
            ),
            "ma_phonenumber" => array(
                "name" => "ma_phonenumber",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "vPhonenumber",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "phone_number",
                "label" => "Phone Number",
                "lang_code" => "ADMIN_PHONE_NUMBER",
                "label_lang" => $this->lang->line('ADMIN_PHONE_NUMBER'),
                "format" => $this->general->getAdminPHPFormats('phone')
            ),
            "ma_group_id" => array(
                "name" => "ma_group_id",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "iGroupId",
                "entry_type" => "Table",
                "data_type" => "int",
                "show_input" => "Hidden",
                "type" => "dropdown",
                "label" => "Group",
                "lang_code" => "ADMIN_GROUP",
                "label_lang" => $this->lang->line('ADMIN_GROUP')
            ),
            "ma_status" => array(
                "name" => "ma_status",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "eStatus",
                "entry_type" => "Table",
                "data_type" => "enum",
                "show_input" => "Both",
                "type" => "dropdown",
                "label" => "Status",
                "lang_code" => "ADMIN_STATUS",
                "label_lang" => $this->lang->line('ADMIN_STATUS'),
                "default" => $this->filter->getDefaultValue("ma_status",
                "Text",
                "Active"),
                "dfapply" => "addOnly",
            ),
            "ma_auth_code" => array(
                "name" => "ma_auth_code",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "vAuthCode",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Hidden",
                "type" => "textbox",
                "label" => "Auth Code",
                "lang_code" => "ADMIN_AUTH_CODE",
                "label_lang" => $this->lang->line('ADMIN_AUTH_CODE')
            ),
            "ma_last_access" => array(
                "name" => "ma_last_access",
                "table_name" => "mod_admin",
                "table_alias" => "ma",
                "field_name" => "dLastAccess",
                "entry_type" => "Table",
                "data_type" => "datetime",
                "show_input" => "Hidden",
                "type" => "date_and_time",
                "label" => "Last Access",
                "lang_code" => "ADMIN_LAST_ACCESS",
                "label_lang" => $this->lang->line('ADMIN_LAST_ACCESS'),
                "format" => $this->general->getAdminPHPFormats('date_and_time')
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
        if (is_array($this->switchto_options) && count($this->switchto_options) > 0)
        {
            foreach ($this->switchto_options as $option)
            {
                $fields_arr[] = array(
                    "field" => $option,
                    "escape" => TRUE,
                );
            }
        }
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
     * getPasswordSettings method is used to get password settings for login.
     */
    public function getPasswordSettings()
    {
        $pwd_setings = array(
            "encrypt" => "Yes",
            "enctype" => "bcrypt",
            "pattern" => "Yes",
        );
        return $pwd_setings;
    }

    /**
     * getAdminUser method is used to get user login details.
     * @param string $username username is the user name of admin.
     * @param string $password password is to password of admin.
     * @return array $db_rec_arr returns admin record array.
     */
    public function getAdminUser($username = '', $password = '')
    {
        $this->db->select("ma.*, mgm.vGroupCode, mgm.vGroupName, mgm.eStatus AS vGroupStaus");
        $this->db->from($this->table_name." AS ma");
        $this->db->join("mod_group_master AS mgm", "mgm.iGroupId = ma.iGroupId", "left");
        //$this->db->where("ma.vUserName", $username);
        $this->db->group_start();
        $this->db->where("ma.vUserName", $username);
        $this->db->or_where("ma.vEmail", $username);
        $this->db->group_end();
        //$this->db->where("ma.vPassword", $password);
        if ($password !== FALSE)
        {
            $this->db->where("ma.vPassword", $password);
        }
        $db_rec_obj = $this->db->get();
        $db_rec_arr = is_object($db_rec_obj) ? $db_rec_obj->result_array() : array();
        return $db_rec_arr;
    }

    /**
     * getAdminData method is used to get user details.
     * @param numeric $id id is the primary key of admin.
     * @return array $db_rec_arr returns admin record array.
     */
    public function getAdminData($id = 0)
    {
        $this->db->select("ma.*, mgm.vGroupCode, mgm.vGroupName, mgm.eStatus AS vGroupStaus");
        $this->db->from($this->table_name." AS ma");
        $this->db->join("mod_group_master AS mgm", "mgm.iGroupId = ma.iGroupId", "left");
        $this->db->where("ma.iAdminId", $id);
        $db_rec_obj = $this->db->get();
        $db_rec_arr = is_object($db_rec_obj) ? $db_rec_obj->result_array() : array();
        return $db_rec_arr;
    }
}
