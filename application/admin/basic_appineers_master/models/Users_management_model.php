<?php

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Users Management Model
 * 
 * @category admin
 *            
 * @package basic_appineers_master
 * 
 * @subpackage models 
 *  
 * @module Users Management
 * 
 * @class Users_management_model.php
 * 
 * @path application\admin\basic_appineers_master\models\Users_management_model.php
 * 
 * @version 4.4
 *
 * @author CIT Dev Team
 * 
 * @date 07.02.2020
 */
 
class Users_management_model extends CI_Model 
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
        $this->module_name = "users_management";
        $this->table_name = "users";
        $this->table_alias = "u";
        $this->primary_key = "iUserId";
        $this->primary_alias = "u_user_id";
        $this->physical_data_remove = "Yes";
        $this->grid_fields = array("u_profile_image", "u_first_name", "u_email", "u_mobile_no", "u_added_at", "u_status", "u_updated_at");
        $this->join_tables = array();
        $this->extra_cond = "";
        $this->groupby_cond = array();
        $this->having_cond = "";
        $this->orderby_cond = array(
                    array(
                        "field" => "u.dtAddedAt",
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
    public function update($data = array(), $where = '', $alias = "No", $join = "No")
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
    public function getData($extra_cond = "", $fields = "", $order_by = "", $group_by = "", $limit = "", $join = "No", $having_cond = '', $list = FALSE)
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
            $this->db->select("u.vProfileImage AS u_profile_image");
        $this->db->select("concat(u.vFirstName,\"\",u.vLastName) AS u_first_name");
        //$this->db->select("u.vUserName AS u_user_name");
        $this->db->select("u.vEmail AS u_email");
        $this->db->select("u.vMobileNo AS u_mobile_no");
        $this->db->select("u.dtAddedAt AS u_added_at");
        $this->db->select("u.eStatus AS u_status");
        $this->db->select("u.eLogStatus AS u_log_status_updated");
        $this->db->select("u.dtUpdatedAt AS u_updated_at");
        
        } else {
            $this->db->select("u.iUserId AS iUserId");
            $this->db->select("u.iUserId AS u_user_id");
            $this->db->select("u.vProfileImage AS u_profile_image");
            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vUserName AS u_user_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vMobileNo AS u_mobile_no");
            $this->db->select("u.dDob AS u_dob");
            $this->db->select("u.tAddress AS u_address");
            $this->db->select("u.vCity AS u_city");
            $this->db->select("u.iStateId AS u_state_id");
            $this->db->select("u.vStateName AS u_state_name");
            $this->db->select("u.vZipCode AS u_zip_code");
            $this->db->select("u.vTermsConditionsVersion AS u_terms_conditions_version");
            $this->db->select("u.vPrivacyPolicyVersion AS u_privacy_policy_version");
            $this->db->select("u.dtDeletedAt AS u_deleted_at");
            $this->db->select("u.eStatus AS u_status");
            $this->db->select("u.eLogStatus AS u_log_status_updated");
            $this->db->select("u.vPassword AS u_password");
            $this->db->select("u.dLatitude AS u_latitude");
            $this->db->select("u.dLongitude AS u_longitude");
            $this->db->select("u.ePushNotify AS u_push_notify");
            $this->db->select("u.tOneTimeTransaction AS u_one_time_transaction");
            $this->db->select("u.vAccessToken AS u_access_token");
            $this->db->select("u.vResetPasswordCode AS u_reset_password_code");
            $this->db->select("u.vEmailVerificationCode AS u_email_verification_code");
            $this->db->select("u.eEmailVerified AS u_email_verified");
            $this->db->select("u.eSocialLoginType AS u_social_login_type");
            $this->db->select("u.vSocialLoginId AS u_social_login_id");
            $this->db->select("u.eDeviceType AS u_device_type");
            $this->db->select("u.vDeviceToken AS u_device_token");
            $this->db->select("u.dtAddedAt AS u_added_at");
            $this->db->select("u.dtUpdatedAt AS u_updated_at");
            $this->db->select("u.eOneTimeTransaction AS u_one_time_transaction");
            $this->db->select("u.vDeviceModel AS u_device_model");
            $this->db->select("u.vDeviceOS AS u_device_os");
            
        }
        
        $this->db->from($this->table_name . " AS " . $this->table_alias);
        if(is_array($join) && is_array($join['joins']) && count($join['joins']) > 0){
            $this->listing->addJoinTables($join['joins']);
            
        } else {
            
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
        
        
        $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_key);
        if($this->primary_alias != ""){
            $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_alias);
        }
        $this->db->select("u.vProfileImage AS u_profile_image");
        $this->db->select("concat(u.vFirstName,\"\",u.vLastName) AS u_first_name");
        //$this->db->select("u.vUserName AS u_user_name");
        $this->db->select("u.vEmail AS u_email");
        $this->db->select("u.vMobileNo AS u_mobile_no");
        $this->db->select("u.dtAddedAt AS u_added_at");
        $this->db->select("u.eStatus AS u_status");
        $this->db->select("u.eLogStatus AS u_log_status_updated");
        $this->db->select("u.dtUpdatedAt AS u_updated_at");
        
        
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
        $this->db->select("u.vProfileImage AS u_profile_image");
        $this->db->select("concat(u.vFirstName,\"\",u.vLastName) AS u_first_name");
        //$this->db->select("u.vUserName AS u_user_name");
        $this->db->select("u.vEmail AS u_email");
        $this->db->select("u.vMobileNo AS u_mobile_no");
        $this->db->select("u.dtAddedAt AS u_added_at");
        $this->db->select("u.eStatus AS u_status");
        $this->db->select("u.eLogStatus AS u_log_status_updated");
        $this->db->select("u.dtUpdatedAt AS u_updated_at");
        
        
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
                "u_profile_image" => array(
                "name" => "u_profile_image",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vProfileImage",
                "source_field" => "u_profile_image",
                "display_query" => "u.vProfileImage",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "file",
                "align" => "center",
                "label" => "Profile Image",
                "lang_code" => "USERS_MANAGEMENT_PROFILE_IMAGE",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_PROFILE_IMAGE'),
                "width" => 50,
                "search" => "No",
                "export" => "Yes",
                "sortable" => "No",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "file_upload" => "Yes",
                "file_inline" => "Yes",
                "file_server" => "amazon",
                "file_folder" => "time_2_beat/user_profile",
                "file_width" => "80",
                "file_height" => "80"
            ),
                "u_first_name" => array(
                "name" => "u_first_name",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vFirstName",
                "source_field" => "u_first_name",
                "display_query" => "concat(u.vFirstName,\"\",u.vLastName)",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "Full Name",
                "lang_code" => "USERS_MANAGEMENT_FULL_NAME",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_FULL_NAME'),
                "width" => 50,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "edit_link" => "Yes"
            ),
                
                "u_email" => array(
                "name" => "u_email",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vEmail",
                "source_field" => "u_email",
                "display_query" => "u.vEmail",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "Email",
                "lang_code" => "USERS_MANAGEMENT_EMAIL",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_EMAIL'),
                "width" => 80,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No"
            ),
                "u_mobile_no" => array(
                "name" => "u_mobile_no",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vMobileNo",
                "source_field" => "u_mobile_no",
                "display_query" => "u.vMobileNo",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "Mobile Number",
                "lang_code" => "USERS_MANAGEMENT_MOBILE_NUMBER",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_MOBILE_NUMBER'),
                "width" => 50,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No"
            ),
                "u_added_at" => array(
                "name" => "u_added_at",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "dtAddedAt",
                "source_field" => "u_added_at",
                "display_query" => "u.dtAddedAt",
                "entry_type" => "Table",
                "data_type" => "datetime",
                "show_in" => "Both",
                "type" => "date",
                "align" => "left",
                "label" => "Created On",
                "lang_code" => "USERS_MANAGEMENT_CREATED_ON",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_CREATED_ON'),
                "width" => 50,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "format" => $this->general->getAdminPHPFormats('date')
            ),
                "u_status" => array(
                "name" => "u_status",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "eStatus",
                "source_field" => "u_status",
                "display_query" => "u.eStatus",
                "entry_type" => "Table",
                "data_type" => "enum",
                "show_in" => "Both",
                "type" => "dropdown",
                "align" => "center",
                "label" => "Status",
                "lang_code" => "USERS_MANAGEMENT_STATUS",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_STATUS'),
                "width" => 50,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "Yes",
                "viewedit" => "Yes"
            ),
                "u_updated_at" => array(
                "name" => "u_updated_at",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "dtUpdatedAt",
                "source_field" => "u_updated_at",
                "display_query" => "u.dtUpdatedAt",
                "entry_type" => "Table",
                "data_type" => "datetime",
                "show_in" => "Both",
                "type" => "date",
                "align" => "left",
                "label" => "Updated At",
                "lang_code" => "USERS_MANAGEMENT_UPDATED_AT",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_UPDATED_AT'),
                "width" => 50,
                "search" => "Yes",
                "export" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "hidden" => "Yes",
                "default" => $this->filter->getDefaultValue("u_updated_at","MySQL","NOW()"),
                "format" => $this->general->getAdminPHPFormats('date'),
                "sql_func" => "NOW()"
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
                "u_profile_image" => array(
                "name" => "u_profile_image",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vProfileImage",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "file",
                "label" => "Profile Image",
                "lang_code" => "USERS_MANAGEMENT_PROFILE_IMAGE",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_PROFILE_IMAGE'),
                "file_upload" => "Yes",
                "file_server" => "amazon",
                "file_folder" => "time_2_beat/user_profile",
                "file_width" => "80",
                "file_height" => "80",
                "file_format" => "gif,png,jpg,jpeg,jpe,bmp,ico",
                "file_size" => "102400",
                "file_label" => "Yes"
            ),
                "u_first_name" => array(
                "name" => "u_first_name",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vFirstName",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "First Name",
                "lang_code" => "USERS_MANAGEMENT_FIRST_NAME",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_FIRST_NAME')
            ),
                "u_last_name" => array(
                "name" => "u_last_name",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vLastName",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "Last Name",
                "lang_code" => "USERS_MANAGEMENT_LAST_NAME",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_LAST_NAME')
            ),
                "u_user_name" => array(
                "name" => "u_user_name",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vUserName",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "Username",
                "lang_code" => "USERS_MANAGEMENT_USERNAME",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_USERNAME')
            ),
                "u_email" => array(
                "name" => "u_email",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vEmail",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "Email",
                "lang_code" => "USERS_MANAGEMENT_EMAIL",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_EMAIL')
            ),
                "u_mobile_no" => array(
                "name" => "u_mobile_no",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vMobileNo",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "Mobile Number",
                "lang_code" => "USERS_MANAGEMENT_MOBILE_NUMBER",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_MOBILE_NUMBER')
            ),
                "u_dob" => array(
                "name" => "u_dob",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "dDob",
                "entry_type" => "Table",
                "data_type" => "date",
                "show_input" => "Both",
                "type" => "date",
                "label" => "Dob",
                "lang_code" => "USERS_MANAGEMENT_DOB",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_DOB'),
                "format" => $this->general->getAdminPHPFormats('date')
            ),
                "u_address" => array(
                "name" => "u_address",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "tAddress",
                "entry_type" => "Table",
                "data_type" => "text",
                "show_input" => "Both",
                "type" => "google_maps",
                "label" => "Address",
                "lang_code" => "USERS_MANAGEMENT_ADDRESS",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_ADDRESS')
            ),
                "u_city" => array(
                "name" => "u_city",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vCity",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "City",
                "lang_code" => "USERS_MANAGEMENT_CITY",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_CITY')
            ),
                "u_state_id" => array(
                "name" => "u_state_id",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "iStateId",
                "entry_type" => "Table",
                "data_type" => "int",
                "show_input" => "Both",
                "type" => "dropdown",
                "label" => "State",
                "lang_code" => "USERS_MANAGEMENT_STATE",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_STATE')
            ),

                "u_state_name" => array(
                "name" => "u_state_name",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vStateName",
                "entry_type" => "Table",
                "data_type" => "int",
                "show_input" => "Both",
                "type" => "text",
                "label" => "State",
                "lang_code" => "USERS_MANAGEMENT_STATE",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_STATE')
            ),
                "u_zip_code" => array(
                "name" => "u_zip_code",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vZipCode",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "Zip Code",
                "lang_code" => "USERS_MANAGEMENT_ZIP_CODE",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_ZIP_CODE')
            ),
                "u_terms_conditions_version" => array(
                "name" => "u_terms_conditions_version",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vTermsConditionsVersion",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "Terms Conditions Version",
                "lang_code" => "USERS_MANAGEMENT_TERMS_CONDITIONS_VERSION",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_TERMS_CONDITIONS_VERSION')
            ),
                "u_privacy_policy_version" => array(
                "name" => "u_privacy_policy_version",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vPrivacyPolicyVersion",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Both",
                "type" => "textbox",
                "label" => "Privacy Policy Version",
                "lang_code" => "USERS_MANAGEMENT_PRIVACY_POLICY_VERSION",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_PRIVACY_POLICY_VERSION')
            ),
                "u_deleted_at" => array(
                "name" => "u_deleted_at",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "dtDeletedAt",
                "entry_type" => "Table",
                "data_type" => "datetime",
                "show_input" => "Both",
                "type" => "date",
                "label" => "Deleted At",
                "lang_code" => "USERS_MANAGEMENT_DELETED_AT",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_DELETED_AT'),
                "format" => $this->general->getAdminPHPFormats('date')
            ),
                "u_status" => array(
                "name" => "u_status",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "eStatus",
                "entry_type" => "Table",
                "data_type" => "enum",
                "show_input" => "Both",
                "type" => "dropdown",
                "label" => "Status",
                "lang_code" => "USERS_MANAGEMENT_STATUS",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_STATUS')
            ),
                "u_log_status_updated" => array(
                "name" => "u_log_status_updated",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "eLogStatus",
                "entry_type" => "Table",
                "data_type" => "enum",
                "show_input" => "Both",
                "type" => "dropdown",
                "label" => "Log Status",
                "lang_code" => "USERS_MANAGEMENT_LOG_STATUS",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_LOG_STATUS')
            ),
                "u_password" => array(
                "name" => "u_password",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vPassword",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Hidden",
                "type" => "textbox",
                "label" => "Password",
                "lang_code" => "USERS_MANAGEMENT_PASSWORD",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_PASSWORD')
            ),
                "u_latitude" => array(
                "name" => "u_latitude",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "dLatitude",
                "entry_type" => "Table",
                "data_type" => "decimal",
                "show_input" => "Hidden",
                "type" => "textbox",
                "label" => "Latitude",
                "lang_code" => "USERS_MANAGEMENT_LATITUDE",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_LATITUDE')
            ),
                "u_longitude" => array(
                "name" => "u_longitude",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "dLongitude",
                "entry_type" => "Table",
                "data_type" => "decimal",
                "show_input" => "Hidden",
                "type" => "textbox",
                "label" => "Longitude",
                "lang_code" => "USERS_MANAGEMENT_LONGITUDE",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_LONGITUDE')
            ),
                "u_push_notify" => array(
                "name" => "u_push_notify",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "ePushNotify",
                "entry_type" => "Table",
                "data_type" => "enum",
                "show_input" => "Hidden",
                "type" => "dropdown",
                "label" => "Push Notify",
                "lang_code" => "USERS_MANAGEMENT_PUSH_NOTIFY",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_PUSH_NOTIFY'),
                "default" => $this->filter->getDefaultValue("u_push_notify","Text","Yes"),
                "dfapply" => "forceApply"
            ),
                "u_one_time_transaction" => array(
                "name" => "u_one_time_transaction",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "tOneTimeTransaction",
                "entry_type" => "Table",
                "data_type" => "text",
                "show_input" => "Hidden",
                "type" => "textbox",
                "label" => "One Time Transaction",
                "lang_code" => "USERS_MANAGEMENT_ONE_TIME_TRANSACTION",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_ONE_TIME_TRANSACTION')
            ),
                "u_access_token" => array(
                "name" => "u_access_token",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vAccessToken",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Hidden",
                "type" => "textbox",
                "label" => "Access Token",
                "lang_code" => "USERS_MANAGEMENT_ACCESS_TOKEN",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_ACCESS_TOKEN')
            ),
                "u_reset_password_code" => array(
                "name" => "u_reset_password_code",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vResetPasswordCode",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Hidden",
                "type" => "textbox",
                "label" => "Reset Password Code",
                "lang_code" => "USERS_MANAGEMENT_RESET_PASSWORD_CODE",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_RESET_PASSWORD_CODE')
            ),
                "u_email_verification_code" => array(
                "name" => "u_email_verification_code",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vEmailVerificationCode",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Hidden",
                "type" => "textbox",
                "label" => "Email Verification Code",
                "lang_code" => "USERS_MANAGEMENT_EMAIL_VERIFICATION_CODE",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_EMAIL_VERIFICATION_CODE')
            ),
                "u_email_verified" => array(
                "name" => "u_email_verified",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "eEmailVerified",
                "entry_type" => "Table",
                "data_type" => "enum",
                "show_input" => "Hidden",
                "type" => "dropdown",
                "label" => "Email Verified",
                "lang_code" => "USERS_MANAGEMENT_EMAIL_VERIFIED",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_EMAIL_VERIFIED'),
                "default" => $this->filter->getDefaultValue("u_email_verified","Text","Yes"),
                "dfapply" => "everyUpdate"
            ),
                "u_social_login_type" => array(
                "name" => "u_social_login_type",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "eSocialLoginType",
                "entry_type" => "Table",
                "data_type" => "enum",
                "show_input" => "Hidden",
                "type" => "dropdown",
                "label" => "Social Login Type",
                "lang_code" => "USERS_MANAGEMENT_SOCIAL_LOGIN_TYPE",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_SOCIAL_LOGIN_TYPE')
            ),
                "u_social_login_id" => array(
                "name" => "u_social_login_id",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vSocialLoginId",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Hidden",
                "type" => "textbox",
                "label" => "Social Login Id",
                "lang_code" => "USERS_MANAGEMENT_SOCIAL_LOGIN_ID",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_SOCIAL_LOGIN_ID')
            ),
                "u_device_type" => array(
                "name" => "u_device_type",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "eDeviceType",
                "entry_type" => "Table",
                "data_type" => "enum",
                "show_input" => "Hidden",
                "type" => "dropdown",
                "label" => "Device Type",
                "lang_code" => "USERS_MANAGEMENT_DEVICE_TYPE",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_DEVICE_TYPE')
            ),
                "u_device_token" => array(
                "name" => "u_device_token",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vDeviceToken",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Hidden",
                "type" => "textbox",
                "label" => "Device Token",
                "lang_code" => "USERS_MANAGEMENT_DEVICE_TOKEN",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_DEVICE_TOKEN')
            ),
                "u_added_at" => array(
                "name" => "u_added_at",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "dtAddedAt",
                "entry_type" => "Table",
                "data_type" => "datetime",
                "show_input" => "Hidden",
                "type" => "date",
                "label" => "Added At",
                "lang_code" => "USERS_MANAGEMENT_ADDED_AT",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_ADDED_AT'),
                "format" => $this->general->getAdminPHPFormats('date')
            ),
                "u_updated_at" => array(
                "name" => "u_updated_at",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "dtUpdatedAt",
                "entry_type" => "Table",
                "data_type" => "datetime",
                "show_input" => "Hidden",
                "type" => "date",
                "label" => "Updated At",
                "lang_code" => "USERS_MANAGEMENT_UPDATED_AT",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_UPDATED_AT'),
                "default" => $this->filter->getDefaultValue("u_updated_at","MySQL","NOW()"),
                "dfapply" => "forceApply",
                "format" => $this->general->getAdminPHPFormats('date')
            ),
                "u_one_time_transaction" => array(
                "name" => "u_one_time_transaction",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "eOneTimeTransaction",
                "entry_type" => "Table",
                "data_type" => "enum",
                "show_input" => "Hidden",
                "type" => "dropdown",
                "label" => "One Time Transaction",
                "lang_code" => "USERS_MANAGEMENT_ONE_TIME_TRANSACTION",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_ONE_TIME_TRANSACTION')
            ),
                "u_device_model" => array(
                "name" => "u_device_model",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vDeviceModel",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Hidden",
                "type" => "textbox",
                "label" => "Device Model",
                "lang_code" => "USERS_MANAGEMENT_DEVICE_MODEL",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_DEVICE_MODEL')
            ),
                "u_device_os" => array(
                "name" => "u_device_os",
                "table_name" => "users",
                "table_alias" => "u",
                "field_name" => "vDeviceOS",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_input" => "Hidden",
                "type" => "textbox",
                "label" => "Device Os",
                "lang_code" => "USERS_MANAGEMENT_DEVICE_OS",
                "label_lang" => $this->lang->line('USERS_MANAGEMENT_DEVICE_OS')
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