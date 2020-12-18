<?php

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Review Management Model
 * 
 * @category admin
 *            
 * @package basic_appineers_master
 * 
 * @subpackage models 
 *  
 * @module review Management
 * 
 * @class review_management_model.php
 * 
 * @path application\admin\basic_appineers_master\models\Review_management_model.php
 * 
 * @version 4.4
 *
 * @author CIT Dev Team
 * 
 * @date 07.02.2020
 */
 
class Review_management_model extends CI_Model 
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
     * @created priyanka chillakuru | 10.09.2019
     * @modified priyanka chillakuru | 07.02.2020
     */
    public function __construct() 
    {
        parent::__construct();
        $this->load->library('listing');
        $this->load->library('filter');
        $this->load->library('dropdown');
        $this->module_name = "review_management";
        $this->table_name = "review";
        $this->table_alias = "r";
        $this->primary_key = "iReviewId";
        $this->primary_alias = "r_review_id";
        $this->physical_data_remove = "Yes";
        $this->grid_fields = array("r_first_name",
                                    "r_email",
                                    "r_mobile_no", 
                                    "r_star", 
                                    "r_description", 
                                    "r_review_type" ,
                                    "r_status"
                                );
        $this->join_tables = array(
            array(
                "table_name" => "business_type",
                "table_alias" => "b",
                "field_name" => "iBusinessTypeId",
                "rel_table_name" => "review",
                "rel_table_alias" => "r",
                "rel_field_name" => "iBussinessType",
                "join_type" => "left",
                "extra_condition" => "",
            )
        );
        $this->extra_cond = "";
        $this->groupby_cond = array();
        $this->having_cond = "";
        $this->orderby_cond = array(
                    array(
                        "field" => "r.dtUpdatedAt",
                        "order" => "DESC"
                    ));
        $this->unique_type = "OR";
        $this->unique_fields = array();
        $this->switchto_fields = array();
        $this->switchto_options = array();
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
    public function update($data = array(), $where = '', $alias = "No", $join = "Yes")
    {
        if($alias == "Yes"){
            if($join == "Yes"){
                $join_tbls = $this->addJoinTables("NR");
            }
            if(trim($join_tbls) != ''){
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
                } elseif($where){
                    $this->db->where($where, FALSE, FALSE);
                } else {
                    return FALSE;
                }
                $res = $this->db->update($this->table_name . " AS " . $this->table_alias, $data);
            }
        } else {
            if (is_numeric($where)) {
                $this->db->where($this->primary_key, $where);
            } elseif($where){
                $this->db->where($where, FALSE, FALSE);
            } else {
                return FALSE;
            }
            $res = $this->db->update($this->table_name, $data);
        }
        #echo $this->db->last_query();exit;
        return $res;
    }
    
    /**
     * delete method is used to delete data records from the database table.
     * @param string $where where is the query condition for deletion.
     * @param string $alias alias is to keep aliases for query or not.
     * @param string $join join is to make joins while deleting records.
     * @return boolean $res returns TRUE or FALSE.
     */
    public function delete($where = "", $alias = "No", $join = "Yes")
    {
        if($this->config->item('PHYSICAL_RECORD_DELETE') && $this->physical_data_remove == 'No') {
            if($alias == "Yes"){
                if(is_array($join['joins']) && count($join['joins'])){
                    $join_tbls = '';
                    if($join['list'] == "Yes"){
                        $join_tbls = $this->addJoinTables("NR");
                    }
                    $join_tbls .= ' ' . $this->listing->addJoinTables($join['joins'], "NR");
                } elseif($join == "Yes"){
                    $join_tbls = $this->addJoinTables("NR");
                }
                $data = $this->general->getPhysicalRecordUpdate($this->table_alias);
                if(trim($join_tbls) != ''){
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
                    } elseif($where){
                        $this->db->where($where, FALSE, FALSE);
                    } else {
                        return FALSE;
                    }
                    $res = $this->db->update($this->table_name . " AS " . $this->table_alias, $data);
                }
            } else {
                if (is_numeric($where)) {
                    $this->db->where($this->primary_key, $where);
                } elseif($where){
                    $this->db->where($where, FALSE, FALSE);
                } else {
                    return FALSE;
                }
                $data = $this->general->getPhysicalRecordUpdate();
                $res = $this->db->update($this->table_name, $data);
            }
        } else {
            if($alias == "Yes"){
                $del_query = "DELETE ".$this->db->protect($this->table_alias) . ".* FROM ".$this->db->protect($this->table_name)." AS ".$this->db->protect($this->table_alias);
                if(is_array($join['joins']) && count($join['joins'])){
                    if($join['list'] == "Yes"){
                        $del_query .= $this->addJoinTables("NR");
                    }
                    $del_query .= ' ' . $this->listing->addJoinTables($join['joins'], "NR");
                } elseif($join == "Yes"){
                    $del_query .= $this->addJoinTables("NR");
                }
                if (is_numeric($where)) {
                    $del_query .= " WHERE " . $this->db->protect($this->table_alias) . "." . $this->db->protect($this->primary_key) . " = " . $this->db->escape($where);
                } elseif($where){
                    $del_query .= " WHERE " . $where;
                } else {
                    return FALSE;
                }
                $res = $this->db->query($del_query);
            } else {
                if (is_numeric($where)) {
                    $this->db->where($this->primary_key, $where);
                } elseif($where){
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
    public function getData($extra_cond = "", $fields = "", $order_by = "", $group_by = "", $limit = "", $join = "Yes", $having_cond = '', $list = FALSE)
    {
        if(is_array($fields)){
        
            $this->listing->addSelectFields($fields);
        
        } elseif($fields != ""){
            $this->db->select($fields);
        } elseif($list == TRUE){
            $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_key);
            if($this->primary_alias != ""){
                $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_alias);
            }
             $this->db->select("r.iReviewId AS iReviewId");
            $this->db->select("r.vProfileImage AS r_profile_image");
            $this->db->select("concat(r.vFirstName,\"\",r.vLastName) AS r_first_name");
            $this->db->select("r.vEmail AS r_email");
            $this->db->select("r.vMobileNo AS r_mobile_no");
            $this->db->select("r.iStars AS r_star");
            $this->db->select("r.vDescription AS r_description");
            $this->db->select("r.vReviewType AS r_review_type");
            $this->db->select("r.dtUpdatedat AS r_updated_at");
            $this->db->select("r.eStatus AS r_status");
        
        } else {
            $this->db->select("r.iReviewId AS iReviewId");
            $this->db->select("r.vProfileImage AS r_profile_image");
            $this->db->select("r.vEmail AS r_email");
            $this->db->select("r.vMobileNo AS r_mobile_no");
            $this->db->select("r.iStars AS r_star");
            $this->db->select("r.dAddedAt AS r_added_at");
            $this->db->select("r.vDescription AS r_description");
            $this->db->select("r.vFirstName AS r_first_name");
            $this->db->select("r.vLastName AS r_last_name");
            $this->db->select("r.vReviewType AS r_review_type");
            $this->db->select("r.dtUpdatedAt AS r_updated_at");
            $this->db->select("r.dtDeletedAt AS r_deleted_at");
            $this->db->select("r.eStatus AS r_status");
            $this->db->select("r.vBussinessName AS r_business_name");
            $this->db->select("b.vName AS r_business_type");
            $this->db->select("r.vPosition AS r_position");
            $this->db->select("r.tAddress AS r_address");
            $this->db->select("r.vCity AS r_city");
            $this->db->select("r.iStateId AS r_state_id");
            $this->db->select("r.vZipCode AS r_zip_code");
            $this->db->select("r.vPlaceId AS r_place_id");
            $this->db->select("r.vClaimedEmail AS r_claimed_email");
            $this->db->select("r.bIsClaimed AS r_is_claimed");
            $this->db->select("r.dLatitude AS r_latitude");
            $this->db->select("r.dLongitude AS r_longitude");
            
        }
        
        $this->db->from($this->table_name . " AS " . $this->table_alias);
        if(is_array($join) && is_array($join['joins']) && count($join['joins']) > 0){
            $this->listing->addJoinTables($join['joins']);
            if($join["list"] == "Yes"){
                $this->addJoinTables("AR");
            }
            
        } else {
            if($join == "Yes"){
                $this->addJoinTables("AR");
            }
        }
        
        if (is_array($extra_cond) && count($extra_cond) > 0) {
            $this->listing->addWhereFields($extra_cond);
        } elseif(is_numeric($extra_cond)) {
            $this->db->where($this->table_alias . "." . $this->primary_key, intval($extra_cond));
        } elseif($extra_cond){
            $this->db->where($extra_cond, FALSE, FALSE);
        }
        $this->general->getPhysicalRecordWhere($this->table_name,$this->table_alias,"AR");
        
        if($group_by != ""){
            $this->db->group_by($group_by);
        }
        
        if ($having_cond != "") {
            $this->db->having($having_cond, FALSE, FALSE);
        }
        
        if ($order_by != "") {
            $this->db->order_by($order_by);
        }
        
        if ($limit != "") {
            if(is_numeric($limit)){
                $this->db->limit($limit);
            } else {
                list($offset, $limit) = explode(",", $limit);
                $this->db->limit($offset, $limit);
            }
        }
        $data_obj = $this->db->get();
        $data_arr = is_object($data_obj) ? $data_obj->result_array() : array();
        #echo $this->db->last_query();exit;
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
        $this->general->getPhysicalRecordWhere($this->table_name,$this->table_alias,"AR");
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
        if($filter_main != ""){
            $this->db->where("(" . $filter_main . ")", FALSE, FALSE);
        }
        if($filter_left != ""){
            $this->db->where("(" . $filter_left . ")", FALSE, FALSE);
        }
        if($filter_range != ""){
            $this->db->where("(" . $filter_range . ")", FALSE, FALSE);
        }
        
        $this->db->stop_cache();
        if ((is_array($group_by) && count($group_by) > 0) || trim($having_cond) != "") {
            $total_records_arr = $this->db->get();
            $total_records = is_object($total_records_arr) ? $total_records_arr->num_rows() : 0;
        } else {
            $total_records = $this->db->count_all_results();
        }
        $total_pages = $this->listing->getTotalPages($total_records, $rec_per_page);
        #echo $this->db->last_query();exit;
        
        
        $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_key);
        if($this->primary_alias != ""){
            $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_alias);
        }
        $this->db->select("r.iReviewId AS iReviewId");
        $this->db->select("r.vProfileImage AS r_profile_image");
        $this->db->select("concat(r.vFirstName,\"\",r.vLastName) AS r_first_name");
        $this->db->select("r.vEmail AS r_email");
        $this->db->select("r.vMobileNo AS r_mobile_no");
        $this->db->select("r.iStars AS r_star");
        $this->db->select("r.vDescription AS r_description");
        $this->db->select("r.vReviewType AS r_review_type");
        $this->db->select("r.dtUpdatedAt AS r_updated_at");
        $this->db->select("r.eStatus AS r_status");
        
        
        if($sdef == "Yes"){
            if(is_array($order_by) && count($order_by) > 0){
                foreach($order_by as $orK => $orV){
                    $sort_filed = $orV['field'];
                    $sort_order = (strtolower($orV['order']) == "desc") ? "DESC" : "ASC";
                    $this->db->order_by($sort_filed, $sort_order);
                }
            } else if (!empty($order_by) && is_string($order_by)) {
                $this->db->order_by($order_by);
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
        $this->general->getPhysicalRecordWhere($this->table_name,$this->table_alias,"AR");
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
        if($filter_main != ""){
            $this->db->where("(" . $filter_main . ")", FALSE, FALSE);
        }
        if($filter_left != ""){
            $this->db->where("(" . $filter_left . ")", FALSE, FALSE);
        }
        if($filter_range != ""){
            $this->db->where("(" . $filter_range . ")", FALSE, FALSE);
        }
        
        $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_key);
        if($this->primary_alias != ""){
            $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_alias);
        }
        $this->db->select("r.iReviewId AS iReviewId");
        $this->db->select("r.vProfileImage AS r_profile_image");
        $this->db->select("concat(r.vFirstName,\"\",r.vLastName) AS r_first_name");
        $this->db->select("r.vEmail AS r_email");
        $this->db->select("r.vMobileNo AS r_mobile_no");
        $this->db->select("r.iStars AS r_star");
        $this->db->select("r.vDescription AS r_description");
        $this->db->select("r.vReviewType AS r_review_type");
        $this->db->select("r.dtUpdatedAt AS r_updated_at");
        $this->db->select("r.eStatus AS r_status");

        
        if($sdef == "Yes"){
            if(is_array($order_by) && count($order_by) > 0){
                foreach($order_by as $orK => $orV){
                    $sort_filed = $orV['field'];
                    $sort_order = (strtolower($orV['order']) == "desc") ? "DESC" : "ASC";
                    $this->db->order_by($sort_filed, $sort_order);
                }
            } else if (!empty($order_by) && is_string($order_by)) {
                $this->db->order_by($order_by);
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
        
        if(!is_array($join_tables) || count($join_tables) == 0){
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
            "r_profile_image" => array(
                "name" => "r_profile_image",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "vProfileImage",
                "source_field" => "r_profile_image",
                "display_query" => "r.vProfileImage",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "file",
                "align" => "center",
                "label" => "Profile Image",
                "lang_code" => "REVIEW_MANAGEMENT_PROFILE_IMAGE",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_PROFILE_IMAGE'),
                "width" => 50,
                "search" => "No",
                "export" => "Yes",
                "sortable" => "No",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "file_upload" => "Yes",
                "file_inline" => "Yes",
                "file_server" => "local",
                "file_folder" => "consumer_profile_image",
                "file_width" => "80",
                "file_height" => "80",
                "file_keep" => "iReviewId",
                ),
                
            "r_first_name" => array(
                "name" => "r_first_name",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "vFirstName",
                "source_field" => "r_first_name",
                "display_query" => "concat(r.vFirstName,\"\",r.vLastName)",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "Full Name",
                "lang_code" => "REVIEW_MANAGEMENT_FULL_NAME",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_FULL_NAME'),
                "width" => 50,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "edit_link" => "Yes"
            ),
            "r_email" => array(
                "name" => "r_email",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "vEmail",
                "source_field" => "r_email",
                "display_query" => "r.vEmail",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "Email",
                "lang_code" => "REVIEW_MANAGEMENT_EMAIL",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_EMAIL'),
                "width" => 50,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "edit_link" => "Yes"
            ),
            "r_mobile_no" => array(
                "name" => "r_mobile_no",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "vMobileNo",
                "source_field" => "r_mobile_no",
                "display_query" => "r.vMobileNo",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "Mobile Number",
                "lang_code" => "REVIEW_MANAGEMENT_MOBILE_NUMBER",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_MOBILE_NUMBER'),
                "width" => 50,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "edit_link" => "Yes"
            ),
            "r_star" => array(
                "name" => "r_star",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "iStars",
                "source_field" => "r_star",
                "display_query" => "r.iStars",
                "entry_type" => "Table",
                "data_type" => "int",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "Stars Given",
                "lang_code" => "REVIEW_MANAGEMENT_STAR",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_STAR'),
                "width" => 80,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No"
            ),
            "r_description" => array(
                "name" => "r_description",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "vDescription",
                "source_field" => "r_description",
                "display_query" => "r.vDescription",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "Stars Given",
                "lang_code" => "REVIEW_MANAGEMENT_DESCRIPTION",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_DESCRIPTION'),
                "width" => 80,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No"
            ),
            "r_review_type" => array(
                "name" => "r_review_type",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "vReviewType",
                "source_field" => "r_review_type",
                "display_query" => "r.vReviewType",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "Stars Given",
                "lang_code" => "REVIEW_MANAGEMENT_REVIEW_TYPE",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_REVIEW_TYPE'),
                "width" => 80,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No"
            ),
               
                "r_updated_at" => array(
                "name" => "r_updated_at",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "dtUpdatedAt",
                "source_field" => "r_updated_at",
                "display_query" => "r.dtUpdatedAt",
                "entry_type" => "Table",
                "data_type" => "datetime",
                "show_in" => "Both",
                "type" => "date_and_time",
                "align" => "left",
                "label" => "Created On",
                "lang_code" => "REVIEW_MANAGEMENT_UPDATED_AT",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_UPDATED_AT'),
                "width" => 50,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "format" => $this->general->getAdminPHPFormats('date_and_time')
            ),
                "r_status" => array(
                "name" => "r_status",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "eStatus",
                "source_field" => "r_status",
                "display_query" => "r.eStatus",
                "entry_type" => "Table",
                "data_type" => "enum",
                "show_in" => "Both",
                "type" => "dropdown",
                "align" => "center",
                "label" => "Status",
                "lang_code" => "REVIEW_MANAGEMENT_STATUS",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_STATUS'),
                "width" => 50,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "Yes",
                "viewedit" => "Yes"
            )
        );
        
            $config_arr = array();
            if(is_array($name) && count($name) > 0){
                $name_cnt = count($name);
                for($i = 0;$i < $name_cnt; $i++){
                    $config_arr[$name[$i]] = $list_config[$name[$i]];
                }
            } elseif($name != "" && is_string($name)){
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
            
            "r_star" => array(
                "name" => "r_star",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "iStars",
                "entry_type" => "Table",
                "data_type" => "int",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "Stars Given",
                "lang_code" => "REVIEW_MANAGEMENT_STAR",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_STAR')
            ),
            "r_description" => array(
                "name" => "r_description",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "vDescription",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "Review",
                "lang_code" => "REVIEW_MANAGEMENT_DESCRIPTION",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_DESCRIPTION')
            ),
                "r_first_name" => array(
                "name" => "r_first_name",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "vFirstName",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "First Name",
                "lang_code" => "REVIEW_MANAGEMENT_FIRST_NAME",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_FIRST_NAME')
            ),
                "r_last_name" => array(
                "name" => "r_last_name",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "vLastName",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "Last Name",
                "lang_code" => "REVIEW_MANAGEMENT_LAST_NAME",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_LAST_NAME')
            ),
            "r_profile_image" => array(
            "name" => "r_profile_image",
            "table_name" => "review",
            "table_alias" => "r",
            "field_name" => "vProfileImage",
            "entry_type" => "Table",
            "data_type" => "varchar",
            "show_input" => "Both",
            "type" => "file",
            "label" => "Profile Image",
            "lang_code" => "REVIEW_MANAGEMENT_PROFILE_IMAGE",
            "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_PROFILE_IMAGE'),
            "file_upload" => "Yes",
            "file_inline" => "Yes",
            "file_server" => "local",
            "file_folder" => "consumer_profile_image",
            "file_width" => "80",
            "file_height" => "80",
            "file_keep" => "iReviewId",
            "file_format" => "gif,png,jpg,jpeg,jpe,bmp,ico",
            "file_size" => "102400"
        ),
               
                "r_email" => array(
                "name" => "r_email",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "vEmail",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "Email",
                "lang_code" => "REVIEW_MANAGEMENT_EMAIL",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_EMAIL')
            ),
                "r_mobile_no" => array(
                "name" => "r_mobile_no",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "vMobileNo",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "Mobile Number",
                "lang_code" => "REVIEW_MANAGEMENT_MOBILE_NUMBER",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_MOBILE_NUMBER')
            ),
                "r_address" => array(
                "name" => "r_address",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "tAddress",
                "entry_type" => "Table",
                "data_type" => "text",
                "show_input" => "Both",
                "type" => "google_maps",
                "label" => "Address",
                "lang_code" => "REVIEW_MANAGEMENT_ADDRESS",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_ADDRESS')
            ),
                "r_city" => array(
                "name" => "r_city",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "vCity",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "City",
                "lang_code" => "REVIEW_MANAGEMENT_CITY",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_CITY')
            ),
                "r_state_id" => array(
                "name" => "r_state_id",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "iStateId",
                "entry_type" => "Table",
                "data_type" => "int",
                "show_input" => "Both",
                "type" => "dropdown",
                "label" => "State",
                "lang_code" => "REVIEW_MANAGEMENT_STATE",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_STATE')
            ),
                "r_zip_code" => array(
                "name" => "r_zip_code",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "vZipCode",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "Zip Code",
                "lang_code" => "REVIEW_MANAGEMENT_ZIP_CODE",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_ZIP_CODE')
            ),
                "r_review_type" => array(
                "name" => "r_review_type",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "vReviewType",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "Review Type",
                "lang_code" => "REVIEW_MANAGEMENT_REVIEW_TYPE",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_REVIEW_TYPE')
            ),
                "r_user_id" => array(
                "name" => "r_user_id",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "iUserId",
                "entry_type" => "Table",
                "data_type" => "int",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "User ID",
                "lang_code" => "REVIEW_MANAGEMENT_USER_ID",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_USER_ID')
            ),
            "r_added_at" => array(
            "name" => "r_added_at",
            "table_name" => "review",
            "table_alias" => "r",
            "field_name" => "dAddedAt",
            "entry_type" => "Table",
            "data_type" => "datetime",
            "show_input" => "Hidden",
            "type" => "date_and_time",
            "label" => "Added At",
            "lang_code" => "REVIEW_MANAGEMENT_ADDED_AT",
            "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_ADDED_AT'),
            "format" => $this->general->getAdminPHPFormats('date_and_time')
        ),
            "r_updated_at" => array(
            "name" => "r_updated_at",
            "table_name" => "review",
            "table_alias" => "r",
            "field_name" => "dtUpdatedAt",
            "entry_type" => "Table",
            "data_type" => "datetime",
            "show_input" => "Hidden",
            "type" => "date_and_time",
            "label" => "Updated At",
            "lang_code" => "REVIEW_MANAGEMENT_UPDATED_AT",
            "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_UPDATED_AT'),
            "default" => $this->filter->getDefaultValue("r_updated_at","MySQL","NOW()"),
            "dfapply" => "forceApply",
            "format" => $this->general->getAdminPHPFormats('date_and_time')
        ),

                "r_deleted_at" => array(
                "name" => "r_deleted_at",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "dtDeletedAt",
                "entry_type" => "Table",
                "data_type" => "datetime",
                "show_input" => "Both",
                "type" => "date_and_time",
                "label" => "Deleted At",
                "lang_code" => "REVIEW_MANAGEMENT_DELETED_AT",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_DELETED_AT'),
                "format" => $this->general->getAdminPHPFormats('date')
            ),
                "r_status" => array(
                "name" => "r_status",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "eStatus",
                "entry_type" => "Table",
                "data_type" => "enum",
                "show_input" => "Both",
                "type" => "dropdown",
                "label" => "Status",
                "lang_code" => "REVIEW_MANAGEMENT_STATUS",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_STATUS')
            ),
                "r_business_name" => array(
                "name" => "r_business_name",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "vBussinessName",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Hidden",
                "type" => "textbox",
                "label" => "Business Name",
                "lang_code" => "REVIEW_MANAGEMENT_BUSINESS_NAME",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_BUSINESS_NAME')
            ),
            "r_business_type" => array(
            "name" => "r_business_type",
            "table_name" => "business_type",
            "table_alias" => "b",
            "field_name" => "vName",
            "entry_type" => "Table",
            "data_type" => "varchar",
            "show_input" => "Hidden",
            "type" => "textbox",
            "label" => "Business Type",
            "lang_code" => "REVIEW_MANAGEMENT_BUSINESS_TYPE",
            "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_BUSINESS_TYPE')
            ),
            "r_position" => array(
            "name" => "r_position",
            "table_name" => "review",
            "table_alias" => "r",
            "field_name" => "vPosition",
            "entry_type" => "Table",
            "data_type" => "varchar",
            "show_input" => "Hidden",
            "type" => "textbox",
            "label" => "Position",
            "lang_code" => "REVIEW_MANAGEMENT_POSITION",
            "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_POSITION')
            ),
            "r_place_id" => array(
            "name" => "r_place_id",
            "table_name" => "review",
            "table_alias" => "r",
            "field_name" => "vPlaceId",
            "entry_type" => "Table",
            "data_type" => "varchar",
            "show_input" => "Hidden",
            "type" => "textbox",
            "label" => "Place ID",
            "lang_code" => "REVIEW_MANAGEMENT_PLACE_ID",
            "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_PLACE_ID')
            ),
            "r_claimed_email" => array(
            "name" => "r_claimed_email",
            "table_name" => "review",
            "table_alias" => "r",
            "field_name" => "vClaimedEmail",
            "entry_type" => "Table",
            "data_type" => "varchar",
            "show_input" => "Hidden",
            "type" => "textbox",
            "label" => "Claimed Email",
            "lang_code" => "REVIEW_MANAGEMENT_CLAIMED_EMAIL",
            "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_CLAIMED_EMAIL')
            ),
            "r_is_claimed" => array(
            "name" => "r_is_claimed",
            "table_name" => "review",
            "table_alias" => "r",
            "field_name" => "bIsClaimed",
            "entry_type" => "Table",
            "data_type" => "varchar",
            "show_input" => "Hidden",
            "type" => "textbox",
            "label" => "Position",
            "lang_code" => "REVIEW_MANAGEMENT_IS_CLAIMED",
            "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_IS_CLAIMED')
            ),
                "r_latitude" => array(
                "name" => "r_latitude",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "dLatitude",
                "entry_type" => "Table",
                "data_type" => "decimal",
                "show_input" => "Hidden",
                "type" => "textbox",
                "label" => "Latitude",
                "lang_code" => "REVIEW_MANAGEMENT_LATITUDE",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_LATITUDE')
            ),
                "r_longitude" => array(
                "name" => "r_longitude",
                "table_name" => "review",
                "table_alias" => "r",
                "field_name" => "dLongitude",
                "entry_type" => "Table",
                "data_type" => "decimal",
                "show_input" => "Hidden",
                "type" => "textbox",
                "label" => "Longitude",
                "lang_code" => "REVIEW_MANAGEMENT_LONGITUDE",
                "label_lang" => $this->lang->line('REVIEW_MANAGEMENT_LONGITUDE')
            )
        );
        
            $config_arr = array();
            if(is_array($name) && count($name) > 0){
                $name_cnt = count($name);
                for($i = 0;$i < $name_cnt; $i++){
                    $config_arr[$name[$i]] = $form_config[$name[$i]];
                }
            } elseif($name != "" && is_string($name)){
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
        if(!is_array($field_arr) || count($field_arr) == 0){
            return $exists;
        }
        foreach((array)$field_arr as $key => $val){
            $extra_cond_arr[] = $this->db->protect($this->table_alias . "." . $field_arr[$key]) . " =  " . $this->db->escape(trim($field_val[$val]));
        }
        $extra_cond = "(" . implode(" " . $con . " ", $extra_cond_arr) . ")";
        if ($mode == "Add") {
            $data = $this->getData($extra_cond, "COUNT(*) AS tot");
            if ($data[0]['tot'] > 0) {
                $exists = TRUE;
            }
        } elseif($mode == "Update") {
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
        if(!is_array($switchto_fields) || count($switchto_fields) == 0){
            if($type == "count"){
                return count($switch_data);
            } else {
                return $switch_data;
            }
        }
        $fields_arr = array();
        $fields_arr[] = array("field" => $this->table_alias . "." . $this->primary_key . " AS id");
        $fields_arr[] = array("field" => $this->db->concat($switchto_fields) . " AS val", "escape" => TRUE);
        if(is_array($this->switchto_options) && count($this->switchto_options) > 0){
            foreach($this->switchto_options as $option){
                $fields_arr[] = array(
                    "field" => $option,
                    "escape" => TRUE,
                );
            }
        }
        if(trim($this->extra_cond) != ""){
            $extra_cond = (trim($extra_cond) != "") ? $extra_cond." AND ".$this->extra_cond : $this->extra_cond;
        }
        $switch_data = $this->getData($extra_cond, $fields_arr, "val ASC", "",$limit, "Yes");
        #echo $this->db->last_query();
        if($type == "count"){
            return count($switch_data);
        } else {
            return $switch_data;
        }
    }    
    
}
?>