<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Filter Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module Filter
 * 
 * @class Filter.php
 * 
 * @path application\libraries\Filter.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Filter
{

    protected $CI;
    protected $default_values;
    protected $dropdown_limit;

    public function __construct()
    {
        $this->CI = & get_instance();
        $this->default_values = array();
        $this->dropdown_limit = $this->CI->config->item('ADMIN_DROPDOWN_LIMIT');
    }

    public function applyFilter($filters = '', $filter_config = array())
    {
        if ($filters == '' || !is_array($filters)) {
            return;
        }
        $dropArr = array("dropdown", "radio_buttons");
        $multiArr = array("multi_select_dropdown", "checkboxes");
        $rangeArr = array("date", "date_and_time");
        $noptArr = array("nu", "nn");
        $roptArr = array("bt", "nb");
        $dateArr = array("date", "datetime", "time", "timestamp");
        $fgroups = $filters['groups'];
        $frules = $filters['rules'];
        $fgroup = $filters['groupOp'];
        if (is_array($fgroups) && count($fgroups) > 0) {
            foreach ($fgroups as $inner_key => $inner_filter) {
                $loop_sql = $this->applyFilter($inner_filter, $filter_config);
                $loop_arr[] = "(" . $loop_sql . ")";
            }
            if (is_array($frules) && count($frules) > 0) {
                $search_sql .= implode(" " . $fgroup . " ", $loop_arr) . " " . $fgroup . " ";
            } else {
                $search_sql .= implode(" " . $fgroup . " ", $loop_arr);
            }
        }
        $module_config = $filter_config['module_config'];
        $list_config = $filter_config['list_config'];
        $form_config = $filter_config['form_config'];
        $table_name = $filter_config['table_name'];
        $table_alias = $filter_config['table_alias'];
        $search_arr = array();

        for ($i = 0; $i < count($frules); $i++) {

            $field = $frules[$i]['field'];
            $data_str = $frules[$i]['data'];
            $op = $frules[$i]['op'];
            $field_config = $list_config[$field];
            $source_config = array();

            $display_query = $field_config['display_query'];
            $entry_type = $field_config['entry_type'];
            $data_type = $field_config['data_type'];
            $sql_function = $field_config['sql_func'];
            $field_name = $field_config['field_name'];
            $field_table_alias = $field_config['table_alias'];
            $source_field = $field_config['source_field'];

            if ($table_alias == $field_table_alias) {
                $source_config = $form_config[$source_field];
                // for language tables fields
                if ($module_config['multi_lingual'] == "Yes" && $field_config['lang'] == "Yes" && is_array($source_config) && count($source_config) > 0) {
                    $display_query = $source_config['table_alias'] . "_lang." . $source_config['field_name'];
                }
            } elseif ($entry_type != "Custom") {
                if (isset($form_config[$source_field])) {
                    $source_config = $form_config[$source_field];
                } else {
                    $source_config = $list_config[$source_field];
                }
                if (is_array($source_config) && count($source_config) > 0) {
                    $display_query = $source_config['table_alias'] . "." . $source_config['field_name'];
                }
            }
            if ($entry_type != "Custom") {
                $display_query = $this->CI->db->protect($display_query);
            }
            if (strstr($display_query, $this->queryVariable()) !== FALSE) {
                $display_query = $this->parseSQLFunctionVars($sql_function, $display_query);
            }
            $type = $source_config['type'];
            if ($source_config['multi'] == "Yes") {
                $multiArr[] = "autocomplete";
            } else {
                $dropArr[] = "autocomplete";
            }
            if (in_array($type, $dropArr) && !in_array($op, $noptArr)) {
                $op = ($op == 'ni') ? 'ni' : 'in';
                $data_str = array($data_str);
            } elseif (in_array($type, $multiArr) && !in_array($op, $noptArr)) {
                $op = ($op == 'ni') ? 'nc' : 'cn';
                $data_str = (!is_array($data_str)) ? explode(",", $data_str) : $data_str;
            } elseif ($type == "phone_number" && !in_array($op, $noptArr)) {
                $data_str = $this->formatActionData($data_str, $source_config);
            } elseif (in_array($type, $rangeArr) && !in_array($op, $noptArr)) {
                $date_filter_arr = array();
                $date_format_arr = explode(" to ", $data_str);
                for ($k = 0; $k < count($date_format_arr); $k++) {
                    $date_filter_arr[] = $this->formatActionData($date_format_arr[$k], $source_config);
                }
                if (in_array($op, $roptArr)) {
                    $data_str = implode(" to ", $date_filter_arr);
                } else {
                    $data_str = $date_filter_arr[0];
                }
                if ($type == "date_and_time") {
                    $display_query = $this->CI->db->date_time_format($display_query);
                } else {
                    $display_query = $this->CI->db->date_format($display_query);
                }
            } elseif ($type == "time" && !in_array($op, $noptArr)) {
                $data_str = $this->formatActionData($data_str, $source_config);
                $display_query = $this->CI->db->time_format($display_query);
            } elseif (in_array($data_type, $dateArr) && !in_array($op, $noptArr)) {
                if (!in_array($op, $roptArr)) {
                    if ($data_type == 'date') {
                        $display_query = $this->CI->db->date_format($display_query);
                    } elseif ($data_type == 'time') {
                        $display_query = $this->CI->db->time_format($display_query);
                    } else {
                        $display_query = $this->CI->db->date_time_format($display_query);
                    }
                }
            } elseif (in_array($op, array("ni", "in"))) {
                $data_str = array($data_str);
            }
            $data_arr = (!is_array($data_str)) ? array($data_str) : $data_str;
            if (in_array($op, $noptArr)) {
                if ($op == "nu") {
                    $null_sql = $display_query . " IS NULL ";
                    $empty_sql = $display_query . " = '' ";
                    $temp_sql = "(" . $null_sql . " OR " . $empty_sql . ")";
                    $search_arr[] = $temp_sql;
                } elseif ($op == "nn") {
                    $null_sql = $display_query . " IS NOT NULL ";
                    $empty_sql = $display_query . " <> '' ";
                    $temp_sql = "(" . $null_sql . " AND " . $empty_sql . ")";
                    $search_arr[] = $temp_sql;
                }
            } else {
                $data_arr = (is_array($data_arr)) ? $data_arr : array();
                $tmp_search = array();
                foreach ((array) $data_arr as $key => $data) {
                    if ($data == '') {
                        continue;
                    }
                    if (is_string($data) && !in_array($op, array('in', 'in'))) {
                        if (in_array($data_type, array("tinyint", "smallint", "mediumint", "int", "bigint"))) {
                            $data = intval($data);
                        } else {
                            $data = trim($data);
                        }
                    }
                    $search_type = '';
                    switch ($op) {
                        case 'eq':
                            $search_type = "= " . $this->CI->db->escape($data);
                            break;
                        case 'ne':
                            $search_type = "<> " . $this->CI->db->escape($data);
                            break;
                        case 'lt':
                            $search_type = "< " . $this->CI->db->escape($data);
                            break;
                        case 'le':
                            $search_type = "<= " . $this->CI->db->escape($data);
                            break;
                        case 'gt':
                            $search_type = "> " . $this->CI->db->escape($data);
                            break;
                        case 'ge':
                            $search_type = ">= " . $this->CI->db->escape($data);
                            break;
                        case 'bw':
                            $search_type = $this->CI->db->get_like() . " '" . $this->CI->db->escape_like_str($data) . "%'";
                            break;
                        case 'bn':
                            $search_type = "NOT " . $this->CI->db->get_like() . " '" . $this->CI->db->escape_like_str($data) . "%'";
                            break;
                        case 'ew':
                            $search_type = $this->CI->db->get_like() . " '%" . $this->CI->db->escape_like_str($data) . "'";
                            break;
                        case 'en':
                            $search_type = "NOT " . $this->CI->db->get_like() . " '%" . $this->CI->db->escape_like_str($data) . "'";
                            break;
                        case 'cn':
                            $search_type = $this->CI->db->get_like() . " '%" . $this->CI->db->escape_like_str($data) . "%'";
                            break;
                        case 'nc':
                            $search_type = "NOT " . $this->CI->db->get_like() . " '%" . $this->CI->db->escape_like_str($data) . "%'";
                            break;
                        case 'mw':
                            $fld_arr = array(
                                array(
                                    "field" => $display_query,
                                    "word" => $field_config['fulltext']
                                )
                            );
                            $tmp_sql = $this->textFilterQuery($data, $fld_arr);
                            if ($tmp_sql != "") {
                                $tmp_search[] = $tmp_sql;
                            }
                            break;
                        case 'in':
                            $data = (is_array($data)) ? $data : explode(",", $data);
                            $esc_data = $this->escapeDBValues($data);
                            $search_type = "IN (" . implode(",", $esc_data) . ")";
                            break;
                        case 'ni':
                            $data = (is_array($data)) ? $data : explode(",", $data);
                            $esc_data = $this->escapeDBValues($data);
                            $search_type = "NOT IN (" . implode(",", $esc_data) . ")";
                            break;
                        case "bt" :
                            if (strpos($data, '..') !== FALSE || is_numeric($data)) {
                                $list = explode("..", $data);
                                if (is_numeric(trim($list[0])) && is_numeric(trim($list[1]))) {
                                    $search_type = "BETWEEN " . $this->CI->db->escape(trim($list[0])) . " AND " . $this->CI->db->escape(trim($list[1]));
                                } else {
                                    $search_type = ">= " . $this->CI->db->escape($data);
                                }
                            } else {
                                $data_arr = array_filter(explode(" to ", $data));
                                if ($type == "date_and_time") {
                                    $date_1 = date("Y-m-d H:i:s", strtotime($data_arr[0]));
                                    $date_2 = date("Y-m-d H:i:s", strtotime($data_arr[1]));
                                    if (!in_array($type, $rangeArr)) {
                                        $display_query = $this->CI->db->date_time_format($display_query);
                                    }
                                } else {
                                    $date_1 = date("Y-m-d", strtotime($data_arr[0]));
                                    $date_2 = date("Y-m-d", strtotime($data_arr[1]));
                                    if (!in_array($type, $rangeArr)) {
                                        $display_query = $this->CI->db->date_format($display_query);
                                    }
                                }
                                if (is_array($data_arr) && count($data_arr) > 1) {
                                    $search_type = "BETWEEN " . $this->CI->db->escape($date_1) . " AND " . $this->CI->db->escape($date_2);
                                } else {
                                    $search_type = "= " . $this->CI->db->escape($date_1);
                                }
                            }
                            break;
                        case "nb" :
                            if (strpos($data, '..') !== FALSE || is_numeric($data)) {
                                $list = explode("..", $data);
                                if (is_numeric(trim($list[0])) && is_numeric(trim($list[1]))) {
                                    $search_type = "NOT BETWEEN " . $this->CI->db->escape(trim($list[0])) . " AND " . $this->CI->db->escape(trim($list[1]));
                                } else {
                                    $search_type = "< " . $this->CI->db->escape($data);
                                }
                            } else {
                                $data_arr = array_filter(explode(" to ", $data));
                                if ($type == "date_and_time") {
                                    $date_1 = date("Y-m-d H:i:s", strtotime($data_arr[0]));
                                    $date_2 = date("Y-m-d H:i:s", strtotime($data_arr[1]));
                                    if (!in_array($type, $rangeArr)) {
                                        $display_query = $this->CI->db->date_time_format($display_query);
                                    }
                                } else {
                                    $date_1 = date("Y-m-d", strtotime($data_arr[0]));
                                    $date_2 = date("Y-m-d", strtotime($data_arr[1]));
                                    if (!in_array($type, $rangeArr)) {
                                        $display_query = $this->CI->db->date_format($display_query);
                                    }
                                }
                                if (is_array($data_arr) && count($data_arr) > 1) {
                                    $search_type = "NOT BETWEEN " . $this->CI->db->escape($date_1) . " AND " . $this->CI->db->escape($date_2);
                                } else {
                                    $search_type = "<> " . $this->CI->db->escape($date_1);
                                }
                            }
                            break;
                    }
                    if ($search_type != "") {
                        $tmp_search[] = $display_query . " " . $search_type;
                    }
                }
                if (is_array($tmp_search) && count($tmp_search) > 0) {
                    if (in_array($type, $multiArr)) {
                        $search_arr[] = "(" . implode(" OR ", $tmp_search) . ")";
                    } else {
                        $search_arr[] = implode(" AND ", $tmp_search);
                    }
                }
            }
        }
        if (is_array($search_arr) && count($search_arr) > 0) {
            $search_sql = implode(" " . $fgroup . " ", $search_arr);
        }
        return $search_sql;
    }

    public function applyLeftFilter($filters = '', $filter_config = array())
    {
        $dateArr = array("date", "datetime", "time", "timestamp");
        $search_sql = '';
        $left_entrys = $filters['entrys'];
        if (!is_array($left_entrys) || count($left_entrys) == 0) {
            return $search_sql;
        }
        $search_arr = array();
        $fgroup = $filters['groupOp'];
        $module_config = $filter_config['module_config'];
        $list_config = $filter_config['list_config'];
        $form_config = $filter_config['form_config'];
        $search_config = $filter_config['search_config'];
        $table_name = $filter_config['table_name'];
        $table_alias = $filter_config['table_alias'];

        foreach ($left_entrys as $key => $val) {
            $field = $key;
            $data_arr = $val;
            $field_config = $list_config[$field];
            $source_config = array();

            $display_query = $field_config['display_query'];
            $entry_type = $field_config['entry_type'];
            $data_type = $field_config['data_type'];
            $sql_function = $field_config['sql_func'];
            $field_name = $field_config['field_name'];
            $field_table_alias = $field_config['table_alias'];
            $source_field = $field_config['source_field'];

            if ($table_alias == $field_table_alias) {
                $source_config = $form_config[$source_field];
                // for language tables fields
                if ($module_config['multi_lingual'] == "Yes" && $field_config['lang'] == "Yes" && is_array($source_config) && count($source_config) > 0) {
                    $display_query = $source_config['table_alias'] . "_lang." . $source_config['field_name'];
                }
            } elseif ($entry_type != "Custom") {
                if (isset($form_config[$source_field])) {
                    $source_config = $form_config[$source_field];
                } else {
                    $source_config = $list_config[$source_field];
                }
                if (is_array($source_config) && count($source_config) > 0) {
                    $display_query = $source_config['table_alias'] . "." . $source_config['field_name'];
                }
            }
            if ($entry_type != "Custom") {
                $display_query = $this->CI->db->protect($display_query);
            }
            if (strstr($display_query, $this->queryVariable()) !== FALSE) {
                $display_query = $this->parseSQLFunctionVars($sql_function, $display_query);
            }

            $type = $source_config['type'];
            if (is_array($data_arr) && count($data_arr) > 0) {
                foreach ($data_arr as $data_key => $data_val) {
                    $final_val = $data_val;
                    if ($search_config[$field]['range'] == "Yes") {
                        $range_format_arr = explode(" to ", $data_val);
                        if (is_array($range_format_arr) && count($range_format_arr) > 1) {
                            if (in_array(trim($range_format_arr[1]), array("above", "below"))) {
                                if (trim($range_format_arr[1]) == "below") {
                                    $final_val = $display_query . " <= " . $this->CI->db->escape($range_format_arr[0]);
                                } else {
                                    $final_val = $display_query . " >= " . $this->CI->db->escape($range_format_arr[0]);
                                }
                            } else {
                                $final_val = $display_query . " >= " . $this->CI->db->escape($range_format_arr[0]) . " AND " . $display_query . " <= " . $this->CI->db->escape($range_format_arr[1]);
                            }
                        } else {
                            $final_val = $display_query . " = " . $this->CI->db->escape(trim($range_format_arr[0]));
                        }
                        $final_val = "(" . $final_val . ")";
                    } else {
                        if ($type == "phone_number" || $type == "time") {
                            $final_val = $this->formatActionData($data_val, $source_config);
                        } elseif ($type == "date" || $type == "date_and_time") {
                            $date_filter_arr = array();
                            $date_format_arr = explode(" to ", $data_val);
                            for ($i = 0; $i < count($date_format_arr); $i++) {
                                $date_filter_arr[] = $this->formatActionData($date_format_arr[$i], $source_config);
                            }
                            if ($type == "date") {
                                $display_query_fmt = $this->CI->db->date_format($display_query);
                            } elseif ($type == "date_and_time") {
                                $display_query_fmt = $this->CI->db->date_time_format($display_query);
                            }
                            if (is_array($date_filter_arr) && count($date_filter_arr) > 1) {
                                $final_val = $display_query_fmt . " BETWEEN " . $this->CI->db->escape(trim($date_filter_arr[0])) . " AND " . $this->CI->db->escape(trim($date_filter_arr[1]));
                            } else {
                                $final_val = $display_query_fmt . " = " . $this->CI->db->escape(trim($date_filter_arr[0]));
                            }
                            $final_val = "(" . $final_val . ")";
                        }
                    }
                    $data_arr[$data_key] = $final_val;
                }
                $date_time_query = '';
                if ($type == "date") {
                    $display_query = $this->CI->db->date_format($display_query);
                    $date_time_query = " OR " . $display_query . " = '0000-00-00'";
                } elseif ($type == "date_and_time") {
                    $display_query = $this->CI->db->date_time_format($display_query);
                    $date_time_query = " OR " . $display_query . " = '0000-00-00 00:00:00'";
                } elseif ($type == "time") {
                    $display_query = $this->CI->db->time_format($display_query);
                    $date_time_query = " OR " . $display_query . " = '00:00:00'";
                } elseif (in_array($data_type, $dateArr)) {
                    if ($data_type == 'date') {
                        $display_query = $this->CI->db->date_format($display_query);
                        $date_time_query = " OR " . $display_query . " = '0000-00-00'";
                    } elseif ($data_type == 'time') {
                        $display_query = $this->CI->db->time_format($display_query);
                        $date_time_query = " OR " . $display_query . " = '00:00:00'";
                    } else {
                        $display_query = $this->CI->db->date_time_format($display_query);
                        $date_time_query = " OR " . $display_query . " = '0000-00-00 00:00:00'";
                    }
                }
                $data = (is_array($data_arr)) ? $data_arr : explode(",", $data_arr);
                if (!is_array($data) || count($data) == 0) {
                    $temp_sql = "(" . $display_query . " IS NULL OR " . $display_query . " = '' " . $date_time_query . ")";
                } else {
                    if ($search_config[$field]['range'] == "Yes" || $type == "date_and_time" || $type == "date") {
                        $temp_sql = implode(' OR ', $data);
                    } else {
                        $apply_null_qr = FALSE;
                        foreach ($data as $d) {
                            if ($d == "") {
                                $apply_null_qr = TRUE;
                                break;
                            }
                        }
                        if ($apply_null_qr) {
                            $esc_data = $this->escapeDBValues($data);
                            $temp_sql = "(" . $display_query . " IN (" . implode(",", $esc_data) . ") OR " . $display_query . " IS NULL)";
                        } else {
                            if ($search_config[$field]['order'] == "TEXT") {
                                $data_str = trim($data_arr[0]);
                                $fld_arr = array(
                                    array(
                                        "field" => $display_query,
                                        "word" => $field_config['fulltext']
                                    )
                                );
                                $temp_sql = $this->numberFilterQuery($data_str, $fld_arr);
                                if ($temp_sql == FALSE) {
                                    $temp_sql = $this->textFilterQuery($data_str, $fld_arr);
                                }
                                //$temp_sql = $display_query . " " . $this->CI->db->get_like() . " '%" . $this->CI->db->escape_like_str($data_str) . "%'";
                            } else {
                                $esc_data = $this->escapeDBValues($data);
                                $temp_sql = $display_query . " IN (" . implode(",", $esc_data) . ")";
                            }
                        }
                    }
                }
                if ($temp_sql != "") {
                    $search_arr[] = $temp_sql;
                }
            }
        }
        if (is_array($search_arr) && count($search_arr) > 0) {
            $search_sql = implode(" " . $fgroup . " ", $search_arr);
        }
        return $search_sql;
    }

    public function applyRangeFilter($filters = '', $filter_config = array())
    {
        $search_sql = '';
        $search_arr = array();
        $range_key = $filters['range']['key'];
        $range_val = $filters['range']['val'];

        $fgroup = $filters['groupOp'];
        $module_config = $filter_config['module_config'];
        $list_config = $filter_config['list_config'];
        $form_config = $filter_config['form_config'];
        $search_config = $filter_config['search_config'];
        $global_filters = $filter_config['global_filters'];
        $table_name = $filter_config['table_name'];
        $table_alias = $filter_config['table_alias'];

        $field = trim($range_key);
        $data = is_array($range_val) ? $range_val : trim($range_val);

        if ($field == "_global") {
            $numberType = array("tinyint", "smallint", "mediumint", "int", "bigint", "float", "decimal", "double");
            $dateType = array("date", "datetime", "timestamp");
            $timeType = array("time");
            if (!is_array($global_filters) || count($global_filters) == 0 || empty($range_val)) {
                return $search_sql;
            }
            $tmp_search = $number_fields = $date_fields = $text_fields = array();
            foreach ($global_filters as $val) {
                if(!$val['escape']){
                    $val['field'] = $this->CI->db->protect($val['field']);
                }
                if (in_array($val['type'], $numberType)) {
                    $number_fields[] = $val;
                } elseif (in_array($val['type'], $dateType)) {
                    $date_fields[] = $val;
                } else {
                    $text_fields[] = $val;
                }
            }

//            foreach ($global_filters as $key => $val) {
//                $display_query = $val['field'];
//                $op = $val['oper'];
//                $search_type = '';
//                switch ($op) {
//                    case 'eq':
//                        $search_type = "= " . $this->CI->db->escape($data);
//                        break;
//                    case 'ne':
//                        $search_type = "<> " . $this->CI->db->escape($data);
//                        break;
//                    case 'lt':
//                        $search_type = "< " . $this->CI->db->escape($data);
//                        break;
//                    case 'le':
//                        $search_type = "<= " . $this->CI->db->escape($data);
//                        break;
//                    case 'gt':
//                        $search_type = "> " . $this->CI->db->escape($data);
//                        break;
//                    case 'ge':
//                        $search_type = ">= " . $this->CI->db->escape($data);
//                        break;
//                    case 'bw':
//                        $search_type = $this->CI->db->get_like() . " '" . $this->CI->db->escape_like_str($data) . "%'";
//                        break;
//                    case 'bn':
//                        $search_type = "NOT " . $this->CI->db->get_like() . " '" . $this->CI->db->escape_like_str($data) . "%'";
//                        break;
//                    case 'ew':
//                        $search_type = $this->CI->db->get_like() . " '%" . $this->CI->db->escape_like_str($data) . "'";
//                        break;
//                    case 'en':
//                        $search_type = "NOT " . $this->CI->db->get_like() . " '%" . $this->CI->db->escape_like_str($data) . "'";
//                        break;
//                    case 'cn':
//                        $search_type = $this->CI->db->get_like() . " '%" . $this->CI->db->escape_like_str($data) . "%'";
//                        break;
//                    case 'nc':
//                        $search_type = "NOT " . $this->CI->db->get_like() . " '%" . $this->CI->db->escape_like_str($data) . "%'";
//                        break;
//                    case 'in':
//                        $data = (is_array($data)) ? $data : explode(",", $data);
//                        $esc_data = $this->escapeDBValues($data);
//                        $search_type = "IN (" . implode(",", $esc_data) . ")";
//                        break;
//                    case 'ni':
//                        $data = (is_array($data)) ? $data : explode(",", $data);
//                        $esc_data = $this->escapeDBValues($data);
//                        $search_type = "NOT IN (" . implode(",", $esc_data) . ")";
//                        break;
//                    case 'default':
//                        $search_type = $this->CI->db->get_like() . " '%" . $this->CI->db->escape_like_str($data) . "%'";
//                }
//                if ($search_type != "") {
//                    $tmp_search[] = $display_query . " " . $search_type;
//                }
//            }

            if (strpos($data, ',') !== FALSE) {
                $list = explode(',', $data);
                foreach ($list as $item) {
                    $tmp_query = $this->applyGlobalSearch(trim($item), $filter_config, $text_fields, $number_fields, $date_fields);
                    if ($tmp_query != "") {
                        $tmp_search[] = "(" . $tmp_query . ")";
                    }
                }
                if (is_array($tmp_search) && count($tmp_search) > 0) {
                    $search_arr[] = implode(" AND ", $tmp_search);
                }
            } else {
                $tmp_query = $this->applyGlobalSearch($data, $filter_config, $text_fields, $number_fields, $date_fields);
                if ($tmp_query != "") {
                    $search_arr[] = $tmp_query;
                }
            }
        } else {
            $multiArr = array("dropdown", "multi_select_dropdown", "radio_buttons", "checkboxes", "autocomplete");
            $rangeArr = array("date", "date_and_time");
            $dateArr = array("date", "datetime", "time", "timestamp");

            $field_config = $list_config[$field];
            $source_config = array();
            if (!is_array($field_config) || count($field_config) == 0 || empty($range_val)) {
                return $search_sql;
            }

            $display_query = $field_config['display_query'];
            $entry_type = $field_config['entry_type'];
            $sql_function = $field_config['sql_func'];
            $field_name = $field_config['field_name'];
            $field_table_alias = $field_config['table_alias'];
            $source_field = $field_config['source_field'];

            if ($table_alias == $field_table_alias) {
                $source_config = $form_config[$source_field];
                // for language tables fields
                if ($module_config['multi_lingual'] == "Yes" && $field_config['lang'] == "Yes" && is_array($source_config) && count($source_config) > 0) {
                    $display_query = $source_config['table_alias'] . "_lang." . $source_config['field_name'];
                }
            } elseif ($entry_type != "Custom") {
                if (isset($form_config[$source_field])) {
                    $source_config = $form_config[$source_field];
                } else {
                    $source_config = $list_config[$source_field];
                }
                if (is_array($source_config) && count($source_config) > 0) {
                    $display_query = $source_config['table_alias'] . "." . $source_config['field_name'];
                }
            }
            if ($entry_type != "Custom") {
                $display_query = $this->CI->db->protect($display_query);
            }
            if (strstr($display_query, $this->queryVariable()) !== FALSE) {
                $display_query = $this->parseSQLFunctionVars($sql_function, $display_query);
            }

            $type = $source_config['type'];
            if ($type == "date") {
                $display_query = $this->CI->db->date_format($display_query);
            } elseif ($type == "date_and_time") {
                $display_query = $this->CI->db->date_time_format($display_query);
            } elseif ($type == "time") {
                $display_query = $this->CI->db->time_format($display_query);
            } elseif (in_array($data_type, $dateArr)) {
                if ($data_type == 'date') {
                    $display_query = $this->CI->db->date_format($display_query);
                } elseif ($data_type == 'time') {
                    $display_query = $this->CI->db->time_format($display_query);
                } else {
                    $display_query = $this->CI->db->date_time_format($display_query);
                }
            }
            if (in_array($type, $rangeArr)) {
                $date_filter_arr = array();
                $date_format_arr = explode(" to ", $data);
                for ($i = 0; $i < count($date_format_arr); $i++) {
                    $date_filter_arr[] = $this->formatActionData($date_format_arr[$i], $source_config);
                }
                if ($type == "date") {
                    $display_query_fmt = $this->CI->db->date_format($display_query);
                } elseif ($type == "date_and_time") {
                    $display_query_fmt = $this->CI->db->date_time_format($display_query);
                }
                if (is_array($date_filter_arr) && count($date_filter_arr) > 1) {
                    $final_val = $display_query_fmt . " BETWEEN " . $this->CI->db->escape(trim($date_filter_arr[0])) . " AND " . $this->CI->db->escape(trim($date_filter_arr[1]));
                } else {
                    $final_val = $display_query_fmt . " = " . $this->CI->db->escape(trim($date_filter_arr[0]));
                }
                $temp_sql = "(" . $final_val . ")";
            } elseif (in_array($type, $multiArr)) {
                $data_arr = (is_array($data)) ? $data : explode(",", $data);
                $esc_data = $this->escapeDBValues($data_arr);
                $temp_sql = $display_query . " IN (" . implode(",", $esc_data) . ")";
            } else {
                $fld_arr = array(
                    array(
                        "field" => $display_query,
                        "word" => $field_config['fulltext']
                    )
                );
                $temp_sql = $this->numberFilterQuery($data, $fld_arr);
                if ($temp_sql == FALSE) {
                    $temp_sql = $this->textFilterQuery($data, $fld_arr);
                }
                //$temp_sql = $display_query . " " . $this->CI->db->get_like() . " '" . $this->CI->db->escape_like_str($data) . "%'";
            }
            if ($temp_sql != "") {
                $search_arr[] = $temp_sql;
            }
        }

        if (is_array($search_arr) && count($search_arr) > 0) {
            $search_sql = implode(" " . $fgroup . " ", $search_arr);
        }
        return $search_sql;
    }

    public function applyGlobalSearch($value = '', $filter_config = array(), $text_fields = array(), $number_fields = array(), $date_fields = array())
    {
        $search_sql = '';
        $tmp_search = array();
        if (substr($value, 0, 5) == "text:") {
            $value = substr($value, 5);
            $tmp_sql = $this->textFilterQuery($value, $text_fields);
            if ($tmp_sql !== FALSE) {
                $search_sql = $tmp_sql;
            }
        } else if (substr($value, 0, 5) == "date:") {
            $tmp_sql = $this->dateFilterQuery($value, $date_fields);
            if ($tmp_sql !== FALSE) {
                $search_sql = $tmp_sql;
            }
        } else if (substr($value, 0, 7) == 'number:') {
            $value = substr($value, 7);
            $tmp_sql = $this->numberFilterQuery($value, $number_fields);
            if ($tmp_sql !== FALSE) {
                $search_sql = $tmp_sql;
            }
        } else if ($tmp_sql = $this->columnFilterQuery($value, $filter_config)) {
            $search_sql = $tmp_sql;
        } else if ($tmp_sql = $this->numberFilterQuery($value, $number_fields)) {
            $search_sql = $tmp_sql;
        } else if ($tmp_sql = $this->dateFilterQuery($value, $date_fields)) {
            $search_sql = $tmp_sql;
        } else {
            $tmp_sql = $this->textFilterQuery($value, $text_fields);
            if ($tmp_sql !== FALSE) {
                $search_sql = $tmp_sql;
            }
        }
        return $search_sql;
    }

    public function textFilterQuery($value = '', $text_fields = array())
    {
        $search_sql = FALSE;
        $tmp_search = array();
        if (!is_array($text_fields) || count($text_fields) == 0) {
            return $search_sql;
        }
        if (strpos($value, '+') !== FALSE) {
            $list = explode('+', $value);
            foreach ($text_fields as $tField) {
                $mid_search = array();
                foreach ($list as $tVal) {
                    $tVal = trim($tVal);
                    if ($tVal == "") {
                        continue;
                    }
                    if (substr($tVal, 0, 1) == '"' && substr($tVal, -1) == '"' && substr_count($tVal, '"') == 2) {
                        $query_text = trim($tVal, '"');
                        if ($tField['word'] == "Yes") {
                            $mid_search[] = "MATCH(" . $tField['field'] . ") AGAINST(" . $this->CI->db->escape($query_text) . ")";
                        } else {
                            //$mid_search[] = $tField['field'] . " " . $this->CI->db->get_like() . " '% " . $this->CI->db->escape_like_str($query_text) . " %'";
                            $mid_search[] = $tField['field'] . " REGEXP '[[:<:]]" . $this->CI->db->escape_str($query_text) . "[[:>:]]'";
                        }
                    } else {
                        $mid_search[] = $tField['field'] . " " . $this->CI->db->get_like() . " '%" . $this->CI->db->escape_like_str($tVal) . "%'";
                    }
                }
                $tmp_search[] = "(" . implode(" AND ", $mid_search) . ")";
            }
            $search_sql = "(" . implode(" OR ", $tmp_search) . ")";
        } elseif (strpos($value, '*') !== FALSE) {
            $list = explode('*', $value);
            foreach ($text_fields as $tField) {
                $mid_search = array();
                foreach ($list as $tKey => $tVal) {
                    $tVal = trim($tVal);
                    if ($tVal == "") {
                        continue;
                    }
                    if ($tKey == 0) {
                        if (substr($tVal, 0, 1) == '"' && substr($tVal, -1) == '"' && substr_count($tVal, '"') == 2) {
                            $query_text = trim($tVal, '"');
                            $mid_search[] = $tField['field'] . " " . $this->CI->db->get_like() . " '" . $this->CI->db->escape_like_str($query_text) . "% '";
                        } else {
                            $mid_search[] = $tField['field'] . " " . $this->CI->db->get_like() . " '" . $this->CI->db->escape_like_str($tVal) . "%'";
                        }
                    } elseif ($tKey == count($list) - 1) {
                        if (substr($tVal, 0, 1) == '"' && substr($tVal, -1) == '"' && substr_count($tVal, '"') == 2) {
                            $query_text = trim($tVal, '"');
                            //$mid_search[] = $tField['field'] . " " . $this->CI->db->get_like() . " '% " . $this->CI->db->escape_like_str($query_text) . "'";
                            $mid_search[] = $tField['field'] . " REGEXP '[[:<:]]" . $this->CI->db->escape_str($query_text) . "[[:>:]]'";
                        } else {
                            $mid_search[] = $tField['field'] . " " . $this->CI->db->get_like() . " '%" . $this->CI->db->escape_like_str($tVal) . "'";
                        }
                    } else {
                        if (substr($tVal, 0, 1) == '"' && substr($tVal, -1) == '"' && substr_count($tVal, '"') == 2) {
                            $query_text = trim($tVal, '"');
                            $mid_search[] = "MATCH(" . $tField['field'] . ") AGAINST(" . $this->CI->db->escape($query_text) . ")";
                        } else {
                            $mid_search[] = $tField['field'] . " " . $this->CI->db->get_like() . " '%" . $this->CI->db->escape_like_str($tVal) . "%'";
                        }
                    }
                }
                $tmp_search[] = "(" . implode(" AND ", $mid_search) . ")";
            }
            $search_sql = "(" . implode(" OR ", $tmp_search) . ")";
        } elseif (substr($value, 0, 1) == '"' && substr($value, -1) == '"' && substr_count($value, '"') == 2) {
            $query_text = trim($value, '"');
            foreach ($text_fields as $tField) {
                if ($tField['word'] == "Yes") {
                    $tmp_search[] = "MATCH(" . $tField['field'] . ") AGAINST(" . $this->CI->db->escape($query_text) . ")";
                } else {
                    //$tmp_search[] = $tField['field'] . " " . $this->CI->db->get_like() . " '% " . $this->CI->db->escape_like_str($query_text) . " %'";
                    $tmp_search[] = $tField['field'] . " REGEXP '[[:<:]]" . $this->CI->db->escape_str($query_text) . "[[:>:]]'";
                }
            }
            $search_sql = "(" . implode(" OR ", $tmp_search) . ")";
        } else {
            $list = explode(' ', $value);
            if (count($list) > 1) {
                $list = explode(' ', $value);
                foreach ($text_fields as $tField) {
                    $mid_search = array();
                    foreach ($list as $tVal) {
                        $tVal = trim($tVal);
                        if ($tVal == "") {
                            continue;
                        }
                        if (substr($tVal, 0, 1) == '"' && substr($tVal, -1) == '"' && substr_count($tVal, '"') == 2) {
                            $query_text = trim($tVal, '"');
                            if ($tField['word'] == "Yes") {
                                $mid_search[] = "MATCH(" . $tField['field'] . ") AGAINST(" . $this->CI->db->escape($query_text) . ")";
                            } else {
                                //$mid_search[] = $tField['field'] . " " . $this->CI->db->get_like() . " '% " . $this->CI->db->escape_like_str($query_text) . " %'";
                                $mid_search[] = $tField['field'] . " REGEXP '[[:<:]]" . $this->CI->db->escape_str($query_text) . "[[:>:]]'";
                            }
                        } else {
                            $mid_search[] = $tField['field'] . " " . $this->CI->db->get_like() . " '%" . $this->CI->db->escape_like_str($tVal) . "%'";
                        }
                    }
                    $tmp_search[] = "(" . implode(" OR ", $mid_search) . ")";
                }
                $search_sql = "(" . implode(" OR ", $tmp_search) . ")";
            } else {
                foreach ($text_fields as $tField) {
                    $tmp_search[] = $tField['field'] . " " . $this->CI->db->get_like() . " '%" . $this->CI->db->escape_like_str($value) . "%'";
                }
                $search_sql = "(" . implode(" OR ", $tmp_search) . ")";
            }
        }
        return $search_sql;
    }

    public function dateFilterQuery($value = '', $date_fields = array())
    {
        $search_sql = FALSE;
        $tmp_search = array();
        if (!is_array($date_fields) || count($date_fields) == 0) {
            return $search_sql;
        }

        $operator = '=';
        if (substr($value, 0, 2) == '>=') {
            $operator = '>=';
            $value = substr($value, 2);
        } elseif (substr($value, 0, 2) == '<=') {
            $operator = '<=';
            $value = substr($value, 2);
        } elseif (substr($value, 0, 1) == '>') {
            $operator = '>';
            $value = substr($value, 1);
        } elseif (substr($value, 0, 1) == '<') {
            $operator = '<';
            $value = substr($value, 1);
        }

        $sys_date_format = $this->CI->general->getAdminPHPFormats("date");
        $is_valid_date = _check_server_custom_date($sys_date_format, $value);
        if ($is_valid_date) {
            $query_date = _render_server_custom_date($sys_date_format, $value);
            foreach ($date_fields as $dField) {
                $tmp_search[] = $this->CI->db->date_format($dField['field']) . " " . $operator . " " . $this->CI->db->escape($query_date);
            }
            $search_sql = "(" . implode(" OR ", $tmp_search) . ")";
        } elseif (strpos($value, '..') !== FALSE) {
            $list = explode("..", $value);
            $is_valid_date_1 = _check_server_custom_date($sys_date_format, trim($list[0]));
            $is_valid_date_2 = _check_server_custom_date($sys_date_format, trim($list[1]));
            if ($is_valid_date_1 && $is_valid_date_2) {
                $query_date_1 = _render_server_custom_date($sys_date_format, trim($list[0]));
                $query_date_2 = _render_server_custom_date($sys_date_format, trim($list[1]));
                foreach ($date_fields as $dField) {
                    $tmp_search[] = "(" . $this->CI->db->date_format($dField['field']) . " BETWEEN " . $this->CI->db->escape($query_date_1) . " AND " . $this->CI->db->escape($query_date_2) . ")";
                }
                $search_sql = "(" . implode(" OR ", $tmp_search) . ")";
            }
        }
        return $search_sql;
    }

    public function numberFilterQuery($value = '', $number_fields = array())
    {
        $search_sql = FALSE;
        $tmp_search = array();
        if (!is_array($number_fields) || count($number_fields) == 0) {
            return $search_sql;
        }

        $operator = '=';
        if (substr($value, 0, 2) == '>=') {
            $operator = '>=';
            $value = substr($value, 2);
        } elseif (substr($value, 0, 2) == '<=') {
            $operator = '<=';
            $value = substr($value, 2);
        } elseif (substr($value, 0, 1) == '>') {
            $operator = '>';
            $value = substr($value, 1);
        } elseif (substr($value, 0, 1) == '<') {
            $operator = '<';
            $value = substr($value, 1);
        }

        if (is_numeric($value)) {
            foreach ($number_fields as $nField) {
                $tmp_search[] = $nField['field'] . " " . $operator . " " . $value;
            }
            $search_sql = "(" . implode(" OR ", $tmp_search) . ")";
        } elseif (strpos($value, '..') !== FALSE) {
            $list = explode("..", $value);
            if (is_numeric(trim($list[0])) && is_numeric(trim($list[1]))) {
                foreach ($number_fields as $nField) {
                    $tmp_search[] = "(" . $nField['field'] . " BETWEEN " . trim($list[0]) . " AND " . trim($list[1]) . ")";
                }
                $search_sql = "(" . implode(" OR ", $tmp_search) . ")";
            }
        }
        return $search_sql;
    }

    public function columnFilterQuery($value = '', $filter_config = array())
    {
        $search_sql = FALSE;
        $tmp_search = array();
        $columns_str = $this->CI->input->get_post("columns");
        $columns_arr = json_decode($columns_str, TRUE);
        if (!is_array($columns_arr) || count($columns_arr) == 0) {
            return $search_sql;
        }
        $search_col = $search_txt = '';
        for ($i = 1; $i < 100; $i++) {
            if (substr($value, 0, (strlen($i) + 2)) == "#" . $i . ":") {
                if (isset($columns_arr[$i])) {
                    $search_col = $columns_arr[$i - 1];
                    $search_txt = substr($value, (strlen($i) + 2));
                    break;
                }
            }
        }
        if (empty($search_col) || $search_txt == '') {
            return $search_sql;
        }
        $field_config = $filter_config['list_config'][$search_col];
        if (!is_array($field_config) || count($field_config) == 0) {
            return $search_sql;
        }

        $display_query = $field_config['display_query'];
        if ($field_config['entry_type'] != "Custom") {
            $display_query = $this->CI->db->protect($display_query);
        }
        if (strstr($display_query, $this->queryVariable()) !== FALSE) {
            $display_query = $this->parseSQLFunctionVars($sql_function, $display_query);
        }
        $fld_arr = array(
            array(
                "field" => $display_query,
                "word" => $field_config['fulltext']
            )
        );
        $tmp_sql = $this->applyGlobalSearch($search_txt, $filter_config, $fld_arr, $fld_arr, $fld_arr);
        if ($tmp_sql != "") {
            $search_sql = $tmp_sql;
        }
        return $search_sql;
    }

    public function applyDashBoardFilter($filters = '', $config_arr = array())
    {
        if ($filters == '' || !is_array($filters) || count($filters) == 0) {
            return;
        }
        $search_arr = array();
        $search_sql = '';
        $fgroup = 'AND';
        foreach ($filters as $key => $val) {
            $start = $val['start'];
            $end = $val['end'];
            if ($start == '' && $end == '') {
                continue;
            }
            $start_date = $this->formatActionData($start, $config_arr[$key]);
            $end_date = $this->formatActionData($end, $config_arr[$key]);

            $type = $config_arr[$key]['type'];
            $display_query = $config_arr[$key]['display_query'];
            if ($type == "date_amd_time") {
                $display_query = $this->CI->db->date_time_format($display_query);
            } elseif ($type == "date") {
                $display_query = $this->CI->db->date_format($display_query);
            }
            if ($type == "date_and_time" || $type == "date") {
                if ($start_date != "" || $end_date != '') {
                    $search_arr[] = $display_query . " BETWEEN " . $this->CI->db->escape(trim($start_date)) . " AND " . $this->CI->db->escape(trim($end_date));
                } elseif ($start_date == "") {
                    $search_arr[] = $display_query . " = " . $this->CI->db->escape(trim($end_date));
                } elseif ($end_date == "") {
                    $search_arr[] = $display_query . " = " . $this->CI->db->escape(trim($start_date));
                }
            }
        }
        if (is_array($search_arr) && count($search_arr) > 0) {
            $search_sql = implode(" " . $fgroup . " ", $search_arr);
        }
        return $search_sql;
    }

    public function queryVariable()
    {
        return "%q%";
    }

    public function parseSQLFunctionVars($func_str = '', $query_str = '')
    {
        if (strstr($func_str, $this->queryVariable()) !== FALSE) {
            $query_str = str_replace("%q%", $query_str, $func_str);
        }
        return $query_str;
    }

    public function makeArrayDropDown($data_array = array())
    {
        $combo_array = array();
        if (is_array($data_array) && count($data_array) > 0) {
            foreach ((array) $data_array as $key => $val) {
                if (is_array($val) && array_key_exists("id", $val) && array_key_exists("val", $val)) {
                    $id = $val['id'];
                    $val = $val['val'];
                    $combo_array[$id] = $val;
                }
            }
        }
        return $combo_array;
    }

    public function makeOPTDropdown($data_array = array())
    {
        $assoc_drop_arr = array();
        if (is_array($data_array) && count($data_array) > 0) {
            foreach ((array) $data_array as $key => $val) {
                if (is_array($val) && array_key_exists("id", $val) && array_key_exists("val", $val)) {
                    $op_id = $val['id'];
                    $op_val = $val['val'];
                    if (trim($val['grpVal']) != "") {
                        $assoc_drop_arr[$val['grpVal']][$op_id] = $op_val;
                    }
                }
            }
        }
        return $assoc_drop_arr;
    }

    public function getEditableAutoJSON($combo_arr = array(), $combo_config = array(), $ret_json = TRUE)
    {
        $opt_group = $combo_config["opt_group"];
        $json_arr = array();
        if (is_array($combo_arr) && count($combo_arr) > 0) {
            if ($opt_group == "Yes") {
                $assoc_drop_arr = $this->makeOPTDropdown($combo_arr);
                $i = 0;
                foreach ((array) $assoc_drop_arr as $ds_key => $ds_val) {
                    $childArr = array();
                    $j = 0;
                    foreach ((array) $ds_val as $dd_key => $dd_val) {
                        $childArr[$j]['value'] = $dd_key;
                        $childArr[$j]['text'] = $dd_val;
                        $j++;
                    }
                    $json_arr[$i]['text'] = $ds_key;
                    $json_arr[$i]['children'] = $childArr;
                    $i++;
                }
            } else {
                $k = 0;
                foreach ((array) $combo_arr as $combo_key => $combo_val) {
                    if (is_array($combo_val) && array_key_exists("id", $combo_val) && array_key_exists("val", $combo_val)) {
                        $json_arr[$k]['value'] = $combo_val['id'];
                        $json_arr[$k]['text'] = $combo_val['val'];
                        $k++;
                    }
                }
            }
        }

        if ($ret_json == TRUE) {
            $json_data = json_encode($json_arr);
            return $json_data;
        } else {
            return $json_arr;
        }
    }

    public function getChosenAutoJSON($combo_arr = array(), $combo_config = array(), $ret_json = TRUE, $type = "auto")
    {
        $opt_group = $combo_config["opt_group"];
        $json_arr = array();
        if (is_array($combo_arr) && count($combo_arr) > 0) {
            if ($opt_group == "Yes") {
                $assoc_drop_arr = $this->makeOPTDropdown($combo_arr);
                $i = 0;
                foreach ((array) $assoc_drop_arr as $ds_key => $ds_val) {
                    $childArr = array();
                    $j = 0;
                    foreach ((array) $ds_val as $dd_key => $dd_val) {
                        if ($type == "grid") {
                            $childArr[$j]['value'] = $dd_key;
                            $childArr[$j]['text'] = $dd_val;
                        } else {
                            $childArr[$j]['id'] = $dd_key;
                            $childArr[$j]['text'] = $dd_val;
                        }
                        $j++;
                    }
                    if ($type == "grid") {
                        $json_arr[$i]['text'] = $ds_key;
                        $json_arr[$i]['children'] = $childArr;
                    } else {
                        $json_arr[$i]['group'] = TRUE;
                        $json_arr[$i]['text'] = $ds_key;
                        $json_arr[$i]['items'] = $childArr;
                    }

                    $i++;
                }
            } else {
                $k = 0;
                foreach ((array) $combo_arr as $combo_key => $combo_val) {
                    if (is_array($combo_val) && array_key_exists("id", $combo_val) && array_key_exists("val", $combo_val)) {
                        if ($type == "grid") {
                            $json_arr[$k]['value'] = $combo_val['id'];
                            $json_arr[$k]['text'] = $combo_val['val'];
                        } else {
                            $json_arr[$k]['id'] = $combo_val['id'];
                            $json_arr[$k]['text'] = $combo_val['val'];
                        }
                        $k++;
                    }
                }
            }
        }

        if ($ret_json == TRUE) {
            $json_data = json_encode($json_arr);
            return $json_data;
        } else {
            return $json_arr;
        }
    }

    public function getTokenAutoJSON($combo_arr = array(), $combo_config = array(), $ret_json = TRUE)
    {
        $opt_group = $combo_config["opt_group"];
        $json_arr = array();
        if (is_array($combo_arr) && count($combo_arr) > 0) {
            if ($opt_group == "Yes") {
                $assoc_drop_arr = $this->makeOPTDropdown($combo_arr);
                $i = 0;
                foreach ((array) $assoc_drop_arr as $ds_key => $ds_val) {
                    $childArr = array();
                    $j = 0;
                    foreach ((array) $ds_val as $dd_key => $dd_val) {
                        $childArr[$j]['id'] = $dd_key;
                        $childArr[$j]['val'] = $dd_val;
                        $j++;
                    }
                    $json_arr[$i]['id'] = $ds_key;
                    $json_arr[$i]['val'] = $childArr;
                    $i++;
                }
            } else {
                $k = 0;
                foreach ((array) $combo_arr as $combo_key => $combo_val) {
                    $json_arr[$k]['id'] = $combo_val['id'];
                    $json_arr[$k]['val'] = $combo_val['val'];
                    $k++;
                }
            }
        }
        if ($ret_json == TRUE) {
            $json_data = json_encode($json_arr);
            return $json_data;
        } else {
            return $json_arr;
        }
    }

    public function getTableLevelDropdown($config_arr = array(), $id = '', $extra = '', $type = 'list')
    {
        $table_name = trim($config_arr['table_name']);
        $field_key = trim($config_arr['field_key']);
        $field_val = $config_arr['field_val'];
        $extra_cond = trim($config_arr['extra_cond']);
        $group_by = $config_arr['group_by'];
        $order_by = trim($config_arr['order_by']);
        $db_data = array();
        if (empty($table_name) || empty($field_key) || empty($field_val)) {
            return $db_data;
        }
        if ($type == "count") {
            $this->CI->db->select("COUNT(*) AS tot");
        } else {
            if (is_array($field_val)) {
                if (count($field_val) > 1) {
                    $field_val = $this->CI->db->concat($field_val);
                } else {
                    $field_val = $field_val[0];
                }
            }
            $this->CI->db->select($field_key . " AS id");
            $this->CI->db->select($field_val . " AS val", FALSE);
            if ($config_arr['opt_group'] == "Yes" && $config_arr['opt_group_field'] != "") {
                $this->CI->db->select($config_arr['opt_group_field'] . " AS grpVal");
            }
        }
        $this->CI->db->from($table_name);
        $this->CI->general->getPhysicalRecordWhere($table_name, "", "AR");
        if (trim($extra_cond) != "") {
            $this->CI->db->where($extra_cond, FALSE, FALSE);
        }
        if (trim($extra) != "") {
            $this->CI->db->where($extra, FALSE, FALSE);
        }
        if (!empty($group_by)) {
            $this->CI->db->group_by($group_by);
        }
        if ($type != "count") {
            if ($order_by != "") {
                $this->CI->db->order_by($order_by);
            } else {
                $this->CI->db->order_by("val");
            }
            if ($config_arr['auto'] == "Yes") {
                $this->CI->db->limit($this->dropdown_limit);
            }
        }
        $db_data_obj = $this->CI->db->get();
        $db_data = is_object($db_data_obj) ? $db_data_obj->result_array() : array();
        #echo $this->CI->db->last_query();
        return $db_data;
    }

    public function getTreeLevelDropdown($config_arr = array(), $id = '', $extra = '', $type = 'list')
    {
        $main_table = trim($config_arr['main_table']);
        $table_name = trim($config_arr['table_name']);
        $field_name = trim($config_arr['parent_field']);
        $field_key = trim($config_arr['field_key']);
        $field_val = $config_arr['field_val'];
        $extra_cond = trim($config_arr['extra_cond']);
        $group_by = $config_arr['group_by'];
        $order_by = trim($config_arr['order_by']);
        $db_data = array();
        if (empty($table_name) || empty($field_key) || empty($field_val)) {
            return $db_data;
        }
        if (is_array($field_val)) {
            if (count($field_val) > 1) {
                $field_val = $this->CI->db->concat($field_val);
            } else {
                $field_val = $field_val[0];
            }
        }
        $this->CI->db->select($field_key . " AS id");
        $this->CI->db->select($field_val . " AS val", FALSE);
        $this->CI->db->select($field_name . " AS parId");

        $this->CI->general->getPhysicalRecordWhere($table_name, "", "AR");

        if (intval($id) > 0 && $main_table == $table_name) {
            $this->CI->db->where($field_key . " <> ", $id);
        }
        if (trim($extra_cond) != "") {
            $this->CI->db->where($extra_cond, FALSE, FALSE);
        }
        if (trim($extra) != "") {
            $this->CI->db->where($extra, FALSE, FALSE);
        }
        if (!empty($group_by)) {
            $this->CI->db->group_by($group_by);
        }
        if ($type != "count") {
            if ($order_by != "") {
                $this->CI->db->order_by($order_by);
            } else {
                $this->CI->db->order_by("val");
            }
            if ($config_arr['auto'] == "Yes") {
                $this->CI->db->limit($this->dropdown_limit);
            }
        }
        $db_data = $this->CI->db->select_assoc($table_name, "parId");

        $temp_arr = $this->getRecursiveTreeDropdown($db_data, 0);
        return $temp_arr;
    }

    public function getRecursiveTreeDropdown($data_arr = array(), $id = '', $loop = 1, $old_str = "")
    {
        $db_data = $data_arr[$id];
        $n = (is_array($db_data)) ? count($db_data) : 0;
        if ($n > 0) {
            for ($i = 0; $i < $n; $i++) {
                if ($loop == 1) {
                    $val_value = " " . $old_str . "" . $db_data[$i]['val'];
                } else {
                    $val_value = " " . $old_str . " >> " . $db_data[$i]['val'];
                }
                $temp_arr[] = array('id' => $db_data[$i]["id"], 'val' => $val_value);
                $send_arr = $this->getRecursiveTreeDropdown($data_arr, $db_data[$i]["id"], $loop + 1, $old_str . "&nbsp;&nbsp;");
                $temp_arr = (is_array($send_arr)) ? array_merge($temp_arr, $send_arr) : $temp_arr;
            }
        } else {
            $temp_arr = array();
        }
        return $temp_arr;
    }

    public function getSwitchEncryptRec($switch_to_combo = array(), &$switch_to_data = array())
    {
        if (!is_array($switch_to_combo) || count($switch_to_combo) == 0) {
            return $switch_to_combo;
        }
        $ret_arr = array();
        foreach ($switch_to_combo as $key => $val) {
            $enc_key = $this->CI->general->getAdminEncodeURL($key);
            $ret_arr[$enc_key] = $val;
        }
        foreach ($switch_to_data as $key => $val) {
            $enc_key = $this->CI->general->getAdminEncodeURL($val['id']);
            $switch_to_data[$key]['id'] = $enc_key;
        }
        return $ret_arr;
    }

    public function getNextPrevRecords($id = '', $switch_to_array = array())
    {
        $prev_data_arr = $next_data_arr = array();
        for ($i = 0; $i < count($switch_to_array); $i++) {
            if ($switch_to_array[$i]['id'] == $id) {
                $prev_data_arr = ($i - 1 >= 0) ? $switch_to_array[$i - 1] : array();
                $next_data_arr = ($i + 1 < count($switch_to_array)) ? $switch_to_array[$i + 1] : array();
                break;
            }
        }
        $prev_enc_id = $next_enc_id = '';
        if ($prev_data_arr['id'] != "") {
            $prev_enc_id = $this->CI->general->getAdminEncodeURL($prev_data_arr['id']);
        }
        if ($next_data_arr['id'] != "") {
            $next_enc_id = $this->CI->general->getAdminEncodeURL($next_data_arr['id']);
        }
        $return_arr['prev']['id'] = $prev_data_arr['id'];
        $return_arr['prev']['enc_id'] = $prev_enc_id;
        $return_arr['prev']['val'] = $prev_data_arr['val'];
        $return_arr['next']['id'] = $next_data_arr['id'];
        $return_arr['next']['enc_id'] = $next_enc_id;
        $return_arr['next']['val'] = $next_data_arr['val'];
        return $return_arr;
    }

    public function getPageFlowURL(&$ret_arr = array(), $module_config = array(), $params_arr = array(), $id = '', $data_arr = array())
    {
        $mode = $params_arr['mode'];
        $ctrl_flow = $params_arr['ctrl_flow'];
        $ctrl_next_id = $params_arr['ctrl_next_id'];
        $ctrl_prev_id = $params_arr['ctrl_prev_id'];
        $module_name = $module_config['module_name'];
        $extra_hstr = '';
        if ($this->CI->input->get_post("ctrl_flow")) {
            if ($mode == 'Update') {
                $this->CI->ci_local->write($this->CI->general->getMD5EncryptString("FlowEdit", $module_name), $ctrl_flow, $this->CI->session->userdata('iAdminId'));
            } else {
                $this->CI->ci_local->write($this->CI->general->getMD5EncryptString("FlowAdd", $module_name), $ctrl_flow, $this->CI->session->userdata('iAdminId'));
            }
        }
        if ($ctrl_flow == 'Stay') {
            $ret_arr['red_type'] = 'Stay';
            $ret_arr['red_id'] = $this->CI->general->getAdminEncodeURL($id, 0);
            $ret_arr['red_mode'] = "Update";
            if ($mode == "Update") {
                $extra_hstr .= '|tLoadFP|' . time();
            }
        } elseif ($ctrl_flow == 'Prev') {
            if ($mode == 'Update') {
                if ($this->CI->input->get_post("ctrl_prev_id") && $ctrl_prev_id > 0) {
                    $ret_arr['red_type'] = 'Prev';
                    $ret_arr['red_id'] = $this->CI->general->getAdminEncodeURL($ctrl_prev_id, 0);
                    $ret_arr['red_mode'] = "Update";
                } else {
                    $ret_arr['red_type'] = "List";
                }
            } else {
                $ret_arr['red_type'] = "List";
            }
        } elseif ($ctrl_flow == 'Next') {
            $ret_arr['red_type'] = 'Next';
            if ($mode == 'Update') {
                if ($this->CI->input->get_post("ctrl_next_id") && $ctrl_next_id > 0) {
                    $ret_arr['red_id'] = $this->CI->general->getAdminEncodeURL($ctrl_next_id, 0);
                    $ret_arr['red_mode'] = "Update";
                } else {
                    $ret_arr['red_type'] = "List";
                }
            } else {
                $ret_arr['red_id'] = "";
                $ret_arr['red_mode'] = "Add";
                $extra_hstr .= '|tLoadFP|' . time();
            }
        }

        if ($this->CI->input->get_post("parMod") && $this->CI->input->get_post("parID")) {
            $extra_hstr .= '|parMod|' . $this->CI->general->getAdminEncodeURL($this->CI->input->get_post("parMod")) . '|parID|' . $this->CI->general->getAdminEncodeURL($this->CI->input->get_post("parID"));
        }
        if ($this->CI->input->get_post("tEditFP") == "true") {
            $extra_hstr .= '|tEditFP|true';
        }
        if ($this->CI->input->get_post("hideCtrl") == "true") {
            $extra_hstr .= '|hideCtrl|true';
        }
        $extra_hstr .= $this->CI->general->getHASHFilterParams($this->CI->input->get_post("extra_hstr"));
        $ret_arr['extra_hstr'] = $extra_hstr;
        if ($this->CI->input->get_post("rfMod") != "" && $this->CI->input->get_post("rfField") != "") {
            $ret_arr['popup_data'] = $this->getPopupAddedRecord($data_arr);
        } elseif ($this->CI->input->get_post("loadGrid") != "") {
            $ret_arr['load_grid'] = $this->CI->input->get_post("loadGrid");
        }
        return $ret_arr;
    }

    public function getPopupAddedRecord($data_arr = array())
    {
        $ret_arr = array();
        $rfMod = $this->CI->input->get_post("rfMod");
        $rfFod = $this->CI->input->get_post("rfFod");
        $rfField = $this->CI->input->get_post("rfField");
        $rfhtmlID = $this->CI->input->get_post("rfhtmlID");
        if ($rfMod != "" && $rfFod != "") {
            $folder_name = $rfFod;
            $module_name = $rfMod;
            $model_name = $rfMod . "_model";
            $this->CI->load->module($folder_name . "/" . $module_name);

            if (!is_object($this->CI->$module_name)) {
                return $ret_arr;
            }
            $combo_config = $this->CI->$module_name->dropdown_arr[$rfField];
            if (!is_array($combo_config) || count($combo_config) == 0) {
                return $ret_arr;
            }
            $this->CI->load->model($folder_name . "/" . $model_name);
            $field_config = $this->CI->$model_name->getFormConfiguration($rfField);

            if ($field_config['type'] == "multi_select_dropdown" || $field_config['multi'] == "Yes") {
                $is_multiple = "Yes";
            } else {
                $is_multiple = "No";
            }
            if ($combo_config['type'] == "phpfn") {
                $parent_src = $this->CI->input->get_post("parID");
                $phpfunc = $combo_config['function'];
                if (method_exists($this->CI->general, $phpfunc)) {
                    $combo_arr = $this->CI->general->$phpfunc($data_arr[$combo_config['field_key']], 'Fill', '', $data_arr, $parent_src, '');
                } elseif (substr($phpfunc, 0, 12) == 'controller::' && substr($phpfunc, 12) !== FALSE) {
                    $phpfunc = substr($phpfunc, 12);
                    if (method_exists($this->CI->$module_name, $phpfunc)) {
                        $combo_arr = $this->CI->$module_name->$phpfunc($data_arr[$combo_config['field_key']], 'Fill', '', $data_arr, $parent_src, '');
                    }
                } elseif (substr($phpfunc, 0, 7) == 'model::' && substr($phpfunc, 7) !== FALSE) {
                    $phpfunc = substr($phpfunc, 7);
                    if (method_exists($this->CI->$model_name, $phpfunc)) {
                        $combo_arr = $this->CI->$model_name->$phpfunc($data_arr[$combo_config['field_key']], 'Fill', '', $data_arr, $parent_src, '');
                    }
                }
            } else {
                $extra_cond = $combo_config['field_key'] . " = '" . $data_arr[$combo_config['field_key']] . "'";
                $combo_arr = $this->getTableLevelDropdown($combo_config, '', $extra_cond);
            }

            $ret_arr['id'] = $combo_arr[0]['id'];
            $ret_arr['val'] = $combo_arr[0]['val'];
            $ret_arr['type'] = $field_config['type'];
            $ret_arr['unique_name'] = $rfField;
            $ret_arr['html_id'] = $rfhtmlID;
            $ret_arr['is_multiple'] = $is_multiple;
        }
        return $ret_arr;
    }

    public function getJSONEncodeJSFunction($input = array(), $funcs = array(), $level = 0)
    {
        $removeQuotes = $conditionArr = $trueStringKeep = array();
        $trueStringKeep = array("resizable", "search", 'searchhidden', "sortable", "hidden", "frozen", "checkFunc");
        //$removeQuotes = array("formatDashBoardEditLink", "unformatDashBoardEditLink", "initSearchDSDatePicker","initSearchDSDateTimePicker","initSearchDSTimePicker");
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $ret = $this->getJSONEncodeJSFunction($value, $funcs, 1);
                $input[$key] = $ret[0];
                $funcs = $ret[1];
            } else {
                if (in_array($value, $removeQuotes) || in_array($key, $conditionArr) || substr($value, 0, 9) == "function(") {
                    $func_key = "#" . uniqid(rand()) . "#";
                    $funcs[$func_key] = trim($value, '"');
                    $input[$key] = $func_key;
                }
                if (in_array($key, $trueStringKeep)) {
                    $input[$key] = ($value == 1) ? true : $value;
                }
            }
        }
        if ($level == 1) {
            return array($input, $funcs);
        } else {
            $input_json = json_encode($input);
            foreach ($funcs as $key => $value) {
                $input_json = str_replace('"' . $key . '"', $value, $input_json);
            }
            return $input_json;
        }
    }

    public function getMySQLDefaultValue($name = '', $type = '', $default_value = '')
    {
        $unique_name = $name;
        $split_unique_name = explode("_", $unique_name);
        unset($split_unique_name[0]);
        $unique_name_next = implode("_", $split_unique_name);
        if (in_array($type, array("MySQL", "Server", 'Session', 'Request', 'System', 'Function')) && $default_value != "") {
            $vDefaultValue = $this->renderDefaultValue($type, $default_value, $unique_name);
        } elseif ($this->CI->input->get_post($unique_name)) {
            $vDefaultValue = $this->CI->input->get_post($unique_name);
        } elseif ($this->CI->input->get_post($unique_name_next)) {
            $vDefaultValue = $this->CI->input->get_post($unique_name_next);
        } else {
            $vDefaultValue = $default_value;
        }
        return $vDefaultValue;
    }

    public function getDefaultValue($name = '', $type = '', $default_value = '')
    {
        $unique_name = $name;
        $split_unique_name = explode("_", $unique_name);
        unset($split_unique_name[0]);
        $unique_name_next = implode("_", $split_unique_name);
        if (in_array($type, array("MySQL", "Server", 'Session', 'Request', 'System', 'Function')) && $default_value != "") {
            $vDefaultValue = $this->renderDefaultValue($type, $default_value, $unique_name);
        } elseif ($this->CI->input->get_post($unique_name)) {
            $vDefaultValue = $this->CI->input->get_post($unique_name);
        } elseif ($this->CI->input->get_post($unique_name_next)) {
            $vDefaultValue = $this->CI->input->get_post($unique_name_next);
        } else {
            $vDefaultValue = $default_value;
        }
        return $vDefaultValue;
    }

    public function renderDefaultValue($type = 'Text', $default_value = '', $unique_name = '')
    {
        $return_val = trim($default_value);
        switch ($type) {
            case 'MySQL':
                if ($default_value == "NULL" || $default_value == "null") {
                    $return_val = '';
                } elseif ($default_value != "") {
                    if (!empty($this->default_values[strtolower($default_value)])) {
                        $return_val = $this->default_values[strtolower($default_value)];
                    } else {
                        $sql_query = "SELECT (" . $default_value . ") AS default_value";
                        $sql_data_obj = $this->CI->db->query($sql_query);
                        $db_default_value = is_object($sql_data_obj) ? $sql_data_obj->result_array() : array();
                        $return_val = $db_default_value[0]['default_value'];
                        if (in_array(strtolower($default_value), array("now()", "curdate()"))) {
                            $this->default_values[strtolower($default_value)] = $return_val;
                        }
                    }
                }
                break;
            case 'Function':
                if (method_exists($this->CI->general, $default_value)) {
                    $return_val = $this->CI->general->$default_value($unique_name);
                } elseif (substr($default_value, 0, 12) == 'controller::' && substr($default_value, 12) !== FALSE) {
                    $default_value = substr($default_value, 12);
                    $ctrl_obj = $this->CI->general->getControllerObject();
                    if (method_exists($ctrl_obj, $default_value)) {
                        $return_val = $ctrl_obj->$default_value($unique_name);
                    }
                } elseif (substr($default_value, 0, 7) == 'model::' && substr($default_value, 7) !== FALSE) {
                    $default_value = substr($default_value, 7);
                    $model_obj = $this->CI->general->getModelObject();
                    if (method_exists($model_obj, $default_value)) {
                        $return_val = $model_obj->$default_value($unique_name);
                    }
                }
                break;
            case 'Server':
                $return_val = $_SERVER[$default_value];
                break;
            case 'Session':
                $return_val = $this->CI->session->userdata($default_value);
                break;
            case 'Request':
                $return_val = $this->CI->input->get_post($default_value);
                break;
            case 'System':
                $return_val = $this->CI->config->item($default_value);
                break;
        }
        return $return_val;
    }

    public function formatActionData($value = '', $config_arr = array())
    {
        $retData = $value;
        $type = $config_arr['type'];
        switch ($type) {
            case 'date':
                if (trim($value) == "" || $value == "0000-00-00" || $value == "0000-00-00 00:00:00") {
                    $retData = "0000-00-00";
                } else {
                    $retData = $this->CI->general->formatServerDate($config_arr['format'], $value);
                }
                break;
            case 'date_and_time':
                if (trim($value) == "" || $value == "0000-00-00" || $value == "0000-00-00 00:00:00") {
                    $retData = "0000-00-00 00:00:00";
                } else {
                    $retData = $this->CI->general->formatServerDateTime($config_arr['format'], $value);
                }
                break;
            case 'time':
                if (trim($value) == "") {
                    $retData = "00:00:00";
                } else {
                    $retData = date("H:i:s", strtotime($value));
                }
                break;
            case 'phone_number':
                if (trim($value) == "") {
                    $retData = "";
                } else {
                    $retData = $this->CI->general->getPhoneUnmaskedView($config_arr['format'], $value);
                }
                break;
            case in_array($type, array('checkboxes', 'multi_select_dropdown')):
                if (!is_array($value) && trim($value) == "") {
                    $retData = "";
                } else {
                    $retData = (is_array($value)) ? implode(",", $value) : $value;
                }
                break;
            default :
                if (!is_array($value) && trim($value) == "") {
                    $retData = "";
                }
                break;
        }
        return $retData;
    }

    public function getModuleWorkFlow($config_arr = array(), $post_rec_arr = array(), $db_rec_arr = array(), $id = '', $mode = "", $extra_hstr = '')
    {
        $admin_url = $this->CI->config->item("admin_url");
        $decryptArr = $this->CI->config->item("FRAMEWORK_ENCRYPTS");
        $return_link = "";
        $row_arr = $return_arr = array();
        $compare_arr = array(
            "Add" => "afterAdd",
            "Update" => "afterEdit",
            "InlineEdit" => "afterInlineEdit",
            "Delete" => "afterDelete"
        );
        $return_arr['success'] = 0;
        if (!is_array($config_arr) || count($config_arr) == 0) {
            return $return_arr;
        }

        if (is_array($post_rec_arr) && count($post_rec_arr) > 0) {
            foreach ($post_rec_arr as $key => $val) {
                $row_arr['NEW_' . $key] = $val;
                $row_arr[$key] = $val;
            }
        }
        if (is_array($db_rec_arr) && count($db_rec_arr) > 0) {
            foreach ($db_rec_arr as $key => $val) {
                $row_arr['OLD_' . $key] = $val;
            }
        }

        foreach ($config_arr as $flow_key => $flow_val) {
            if (!is_array($flow_val) || count($flow_val) == 0) {
                continue;
            }
            foreach ($flow_val as $inner_key => $inner_val) {
                if (!is_array($inner_val['actions']) || !in_array($compare_arr[$mode], $inner_val['actions'])) {
                    continue;
                }

                $return_link = $external_url = "";
                $extra_param_arr = $inner_val['extra_params'];
                $global_params_arr = $inner_val['global_params'];
                $custom_module_link = $inner_val['custom_link'];
                $module_name = $inner_val['module_name'];
                $folder_name = $inner_val['folder_name'];
                $module_type = $inner_val['module_type'];
                $module_page = $inner_val['module_page'];
                $apply_condition = $inner_val['apply'];
                $conditions_block = $inner_val['block'];
                $open_on = $inner_val['open'];

                if ($apply_condition == "Yes") {
                    $conditionflag = $this->CI->general->checkConditionalBlock($conditions_block, $row_arr, $id);
                    if (!$conditionflag) {
                        continue;
                    }
                }
                if ($module_type == "Module") {
                    if ($module_page == "Add" || $module_page == "Update") {
                        $return_link = $this->CI->general->getAdminEncodeURL($folder_name . "/" . $module_name . "/add", 0);
                    } else {
                        $return_link = $this->CI->general->getAdminEncodeURL($folder_name . "/" . $module_name . "/index", 0);
                    }
                } else {
                    $external_url = $this->CI->general->isExternalURL($custom_module_link);
                    $return_link = $custom_module_link;
                }
                if (is_array($extra_param_arr) && count($extra_param_arr) > 0) {
                    for ($i = 0; $i < count($extra_param_arr); $i++) {
                        $extra_var_val = $extra_param_arr[$i]['req_val'];
                        $extra_var_type = $extra_param_arr[$i]['req_mod'];
                        $extra_var = $extra_param_arr[$i]['req_var'];
                        if (trim($extra_var_val) == "") {
                            continue;
                        }
                        $req_val = $this->CI->general->parseConditionFieldValue($extra_var_type, $extra_var_val, $row_arr, $id);

                        if ($extra_var != "" && in_array($extra_var, $decryptArr))
                            $return_link .= "|" . $extra_var . "|" . $this->CI->general->getAdminEncodeURL($req_val, 0);
                        else
                            $return_link .= "|" . $extra_var . "|" . $req_val;
                    }
                }
                if (is_array($global_params_arr) && count($global_params_arr) > 0) {
                    for ($i = 0; $i < count($global_params_arr); $i++) {
                        $global_var_val = $global_params_arr[$i]['glb_val'];
                        $global_var_type = $global_params_arr[$i]['glb_mod'];
                        $global_var = $global_params_arr[$i]['glb_var'];
                        if (trim($global_var_val) == "") {
                            continue;
                        }
                        $global_val = $this->CI->general->parseConditionFieldValue($global_var_type, $global_var_val, $row_arr, $id);

                        if ($global_var != "" && in_array($global_var, $decryptArr))
                            $return_link .= "|" . $global_var . "|" . $this->CI->general->getAdminEncodeURL($global_val, 0);
                        else
                            $return_link .= "|" . $global_var . "|" . $global_val;
                    }
                }
                foreach ($row_arr as $key => $val) {
                    $find_array[] = "@" . $key . "@";
                    $replace_array[] = $val;
                }
                $return_link = str_replace($find_array, $replace_array, $return_link);
                if ($return_link != "") {
                    $return_link .= $extra_hstr;
                    $return_arr['link'] = $return_link;
                    $return_arr['success'] = ($external_url) ? 5 : ($open_on == "PageRedirect") ? 3 : 4;
                }
                break 2;
            }
        }
        return $return_arr;
    }

    public function processGeneralFunction($func = '', $data = '', $id = '')
    {
        $ret_str = '';
        if ($func == '') {
            return $ret_str;
        }
        if (method_exists($this->CI->general, $func)) {
            $ret_str = $this->CI->general->$func($data, $id);
        } elseif (substr($func, 0, 12) == 'controller::' && substr($func, 12) !== FALSE) {
            $func = substr($func, 12);
            $ctrl_obj = $this->CI->general->getControllerObject();
            if (method_exists($ctrl_obj, $func)) {
                $ret_str = $ctrl_obj->$func($data, $id);
            }
        } elseif (substr($func, 0, 7) == 'model::' && substr($func, 7) !== FALSE) {
            $func = substr($func, 7);
            $model_obj = $this->CI->general->getModelObject();
            if (method_exists($model_obj, $func)) {
                $ret_str = $model_obj->$func($data, $id);
            }
        }
        return $ret_str;
    }

    public function processModuleFunction($func = '', $ctrl_obj = '', $model_obj = '', $config_arr = array())
    {
        $ret_str = 1;
        if ($func == '') {
            return 1;
        }
        if (method_exists($this->CI->general, $func)) {
            $ret_str = $this->CI->general->$func($config_arr);
        } elseif (substr($func, 0, 12) == 'controller::' && substr($func, 12) !== FALSE) {
            $func = substr($func, 12);
            if (method_exists($ctrl_obj, $func)) {
                $ret_str = $ctrl_obj->$func($config_arr);
            }
        } elseif (substr($func, 0, 7) == 'model::' && substr($func, 7) !== FALSE) {
            $func = substr($func, 7);
            if (method_exists($model_obj, $func)) {
                $ret_str = $model_obj->$func($config_arr);
            }
        }
        return $ret_str;
    }

    public function escapeDBValues($org_data = array())
    {
        if (is_string($org_data)) {
            $org_data = array($org_data);
        }
        $esc_data = array();
        foreach ($org_data as $key => $val) {
            $esc_data[$key] = $this->CI->db->escape($val);
        }
        return $esc_data;
    }

    public function getLandingpageURL($admin_arr = array(), $last_url = '')
    {
        $ret_hash_url = ($last_url != '') ? "#" . $last_url : "#" . $this->CI->general->getAdminEncodeURL('dashboard/dashboard/sitemap');

        $group_id = $admin_arr["iGroupId"];
        if (!intval($group_id)) {
            return $ret_hash_url;
        }
        $this->CI->db->select("mgm.*");
        $this->CI->db->from("mod_group_master mgm");
        $this->CI->db->where("mgm.iGroupId", $group_id);
        $this->CI->general->getPhysicalRecordWhere("mod_group_master", "mgm", "AR");
        $db_data_obj = $this->CI->db->get();
        $db_data = is_object($db_data_obj) ? $db_data_obj->result_array() : array();

        if (!is_array($db_data) || count($db_data) == 0) {
            return $ret_hash_url;
        }
        $grouping_attr = unserialize($db_data[0]["vGroupingAttr"]);
        if (!is_array($grouping_attr) || count($grouping_attr) == 0) {
            return $ret_hash_url;
        }
        $php_func = $grouping_attr["phpFunc"];
        $menu_item = intval($grouping_attr["menuItem"]);
        if (trim($php_func) != "" && method_exists($this->CI->general, $php_func)) {
            $value_return = $this->CI->general->$php_func($db_data);
            $ret_hash_url = (trim($value_return) != "") ? $value_return : $ret_hash_url;
        } elseif ($menu_item > 0) {
            $this->CI->db->select("mam.vURL");
            if ($this->CI->config->item("ENABLE_ROLES_CAPABILITIES")) {
                $this->CI->db->select("mam.iCapabilityId", "mam.vCapabilityCode");
            }
            $this->CI->db->from("mod_admin_menu AS mam");
            $this->CI->db->where("mam.iAdminMenuId", $menu_item);
            $this->CI->general->getPhysicalRecordWhere("mod_admin_menu", "mam", "AR");
            $menu_data_obj = $this->CI->db->get();
            $menu_data_arr = is_object($menu_data_obj) ? $menu_data_obj->result_array() : array();

            if (trim($menu_data_arr[0]["vURL"]) != "") {
                if ($db_data[0]['vGroupCode'] == $this->CI->config->item('ADMIN_GROUP_NAME')) {
                    $ret_hash_url = $this->CI->general->getAdminEncodeURL($menu_data_arr[0]["vURL"]);
                } else {
                    $landing_url = $this->CI->general->getAdminEncodeURL($menu_data_arr[0]["vURL"]);
                    if ($this->CI->config->item("ENABLE_ROLES_CAPABILITIES")) {
                        $extra_cond = array(
                            "mgc.iGroupId" => $group_id,
                            "mgc.iCapabilityId" => $menu_data_arr[0]["iCapabilityId"],
                        );
                        $db_group_capability = $this->getGroupCapabilities($extra_cond);
                        if (is_array($db_group_capability) && count($db_group_capability) > 0) {
                            $ret_hash_url = '#' . $landing_url;
                        }
                    } else {
                        $extra_cond = array(
                            "mgr.iGroupId" => $group_id,
                            "mgr.iAdminMenuId" => $menu_item,
                        );
                        $db_group_rights = $this->getGroupAccessRights($extra_cond);
                        if ($db_group_rights[0]['eList'] == "Yes" || $db_group_rights[0]['eView'] == "Yes") {
                            $ret_hash_url = '#' . $landing_url;
                        }
                    }
                }
            }
        }
        return $ret_hash_url;
    }

    public function getModuleWiseAccess($unique_code = '', $access_mode = 'View', $is_module = FALSE, $res_return = FALSE, $is_dashboard = FALSE, $err_message = '')
    {
        if ($this->CI->config->item("ENABLE_ROLES_CAPABILITIES")) {
            $capability_code = $unique_code;
            if ($is_module == TRUE || $is_dashboard == TRUE) {
                if (is_array($access_mode) && count($access_mode) > 0) {
                    $capability_code = array();
                    for ($i = 0; $i < count($access_mode); $i++) {
                        $capability_code[] = strtolower($unique_code) . '_' . strtolower($access_mode[$i]);
                    }
                } else {
                    $capability_code = strtolower($unique_code) . '_' . strtolower($access_mode);
                }
            }
            $is_allowed = $this->checkAccessCapability($capability_code, $res_return, $err_message);
        } else {
            $group_code = $this->CI->session->userdata('vGroupCode');
            $is_allowed = FALSE;
            if ($group_code == $this->CI->config->item('ADMIN_GROUP_NAME')) {
                if (is_array($access_mode) && count($access_mode) > 0) {
                    for ($i = 0; $i < count($access_mode); $i++) {
                        $is_allowed[] = TRUE;
                    }
                } else {
                    $is_allowed = TRUE;
                }
            } else {
                $iGroupId = $this->CI->session->userdata('iGroupId');
                $extra_right_cond = $this->CI->db->protect("mgr.iGroupId") . "  = " . $this->CI->db->escape($iGroupId);
                if ($is_module) {
                    $extra_right_cond .= " AND " . $this->CI->db->protect("mam.vModuleName") . " = " . $this->CI->db->escape($unique_code);
                } elseif ($is_dashboard) {
                    $extra_right_cond .= " AND " . $this->CI->db->protect("mam.vDashBoardPage") . " = " . $this->CI->db->escape($unique_code);
                } else {
                    $extra_right_cond .= " AND " . $this->CI->db->protect("mam.vUniqueMenuCode") . " = " . $this->CI->db->escape($unique_code);
                }
                $db_group_rights = $this->getGroupAccessRights($extra_right_cond, "", "", "", "", "");
                $eList = $db_group_rights[0]['eList'];
                $eView = $db_group_rights[0]['eView'];
                $eAdd = $db_group_rights[0]['eAdd'];
                $eUpdate = $db_group_rights[0]['eUpdate'];
                $eDelete = $db_group_rights[0]['eDelete'];
                $eExport = $db_group_rights[0]['eExport'];
                $ePrint = $db_group_rights[0]['ePrint'];
                if (is_array($access_mode) && count($access_mode) > 0) {
                    $is_allowed = array();
                    for ($i = 0; $i < count($access_mode); $i++) {
                        switch ($access_mode[$i]) {
                            case 'Add' :
                                $allowed = ($eAdd == "Yes") ? TRUE : FALSE;
                                break;
                            case 'Update' :
                                $allowed = ($eUpdate == "Yes") ? TRUE : FALSE;
                                break;
                            case 'Delete' :
                                $allowed = ($eDelete == "Yes") ? TRUE : FALSE;
                                break;
                            case 'List' :
                                $allowed = ($eList == "Yes") ? TRUE : FALSE;
                                break;
                            case 'Export' :
                                $allowed = ($eExport == "Yes") ? TRUE : FALSE;
                                break;
                            case 'Print' :
                                $allowed = ($ePrint == "Yes") ? TRUE : FALSE;
                                break;
                            default :
                                $allowed = ($eView == "Yes") ? TRUE : FALSE;
                                break;
                        }
                        $is_allowed[] = $allowed;
                    }
                } else {
                    switch ($access_mode) {
                        case 'Add' :
                            $is_allowed = ($eAdd == "Yes") ? TRUE : FALSE;
                            break;
                        case 'Update' :
                            $is_allowed = ($eUpdate == "Yes") ? TRUE : FALSE;
                            break;
                        case 'Delete' :
                            $is_allowed = ($eDelete == "Yes") ? TRUE : FALSE;
                            break;
                        case 'List' :
                            $is_allowed = ($eList == "Yes") ? TRUE : FALSE;
                            break;
                        case 'Export' :
                            $is_allowed = ($eExport == "Yes") ? TRUE : FALSE;
                            break;
                        case 'Print' :
                            $is_allowed = ($ePrint == "Yes") ? TRUE : FALSE;
                            break;
                        default :
                            $is_allowed = ($eView == "Yes") ? TRUE : FALSE;
                            break;
                    }
                }
            }
        }

        $ret_allowed = $is_allowed;
        if ($res_return) {
            return $ret_allowed;
        } else {
            $forbid = FALSE;
            if (is_array($access_mode) && count($access_mode) > 0) {
                if (in_array(FALSE, $ret_allowed)) {
                    $forbid = TRUE;
                }
            } elseif (!$ret_allowed) {
                $forbid = TRUE;
            }
            if ($forbid) {
                $render_arr['err_message'] = $err_message;
                echo $this->CI->parser->parse($this->CI->config->item('ADMIN_FORBIDDEN_TEMPLATE') . ".tpl", $render_arr, TRUE);
                exit;
            }
            return $ret_allowed;
        }
    }

    public function getGroupAccessRights($extra_cond = "", $fields = "", $order_by = "", $group_by = "", $join_arr = array(), $assoc_field = "")
    {
        $fields = ($fields == "") ? "mgr.*" : $fields;
        $this->CI->db->select($fields, FALSE);
        $this->CI->db->from('mod_group_rights AS mgr');
        $this->CI->db->join('mod_admin_menu AS mam', 'mam.iAdminMenuId = mgr.iAdminMenuId', 'left');
        if (is_array($extra_cond) && count($extra_cond) > 0) {
            $this->CI->general->addWhereFields($extra_cond);
        } elseif ($extra_cond != "") {
            if (is_numeric($extra_cond)) {
                $this->CI->db->where('mgr.iGroupId', $extra_cond);
            } else {
                $this->CI->db->where($extra_cond, FALSE, FALSE);
            }
        }
        if ($group_by != "") {
            $this->CI->db->group_by($group_by);
        }
        if ($order_by != "") {
            $this->CI->db->order_by($order_by);
        }
        $list_data_obj = $this->CI->db->get();
        $list_data = is_object($list_data_obj) ? $list_data_obj->result_array() : array();
        if ($assoc_field != '') {
            foreach ($list_data as $val) {
                $final_array[$val[$assoc_field]][] = $val;
            }
            $list_data = $final_array;
        }
        #echo $this->CI->db->last_query();
        return $list_data;
    }

    public function checkAccessCapability($capability_code = '', $res_return = FALSE, $err_message = '')
    {
        $group_code = $this->CI->session->userdata('vGroupCode');
        if (is_array($capability_code) && count($capability_code) > 0) {
            $is_allowed = array();
            for ($i = 0; $i < count($capability_code); $i++) {
                $is_allowed[$i] = FALSE;
            }
        } else {
            $is_allowed = FALSE;
        }
        if ($group_code == $this->CI->config->item('ADMIN_GROUP_NAME')) {
            if (is_array($capability_code) && count($capability_code) > 0) {
                for ($i = 0; $i < count($capability_code); $i++) {
                    $is_allowed[$i] = TRUE;
                }
            } else {
                $is_allowed = TRUE;
            }
        } else {
            $group_id = $this->CI->session->userdata('iGroupId');
            $extra_cond = array(
                "mgc.iGroupId" => $group_id,
                "mcm.vCapabilityCode" => $capability_code,
                "mcm.eStatus" => "Active"
            );
            $field_arr = array(
                "mcm.vCapabilityName", "mcm.vCapabilityCode", "mcm.eCapabilityType", "mcm.vCapabilityMode", "mgc.iCapabilityId", "mgc.tCapabilities"
            );
            $db_group_access = $this->getGroupCapabilities($extra_cond, $field_arr);
            if (is_array($db_group_access) && count($db_group_access) > 0) {
                if (is_array($capability_code) && count($capability_code) > 0) {
                    foreach ($db_group_access as $val) {
                        $checking_code = $val['vCapabilityCode'];
                        $can_access = $this->checkCapabilityStatus($val);
                        $index = array_search($checking_code, $capability_code);
                        $is_allowed[$index] = $can_access;
                    }
                } else {
                    $is_allowed = $this->checkCapabilityStatus($db_group_access[0]);
                }
            }
        }

        $ret_allowed = $is_allowed;
        if ($res_return) {
            return $ret_allowed;
        } else {
            $forbid = FALSE;
            if (is_array($capability_code) && count($capability_code) > 0) {
                if (in_array(FALSE, $ret_allowed)) {
                    $forbid = TRUE;
                }
            } elseif (!$ret_allowed) {
                $forbid = TRUE;
            }
            if ($forbid) {
                $render_arr['err_message'] = $err_message;
                echo $this->CI->parser->parse($this->CI->config->item('ADMIN_FORBIDDEN_TEMPLATE') . ".tpl", $render_arr, TRUE);
                exit;
            }
            return $ret_allowed;
        }
    }

    public function getGroupCapabilities($extra_cond = "", $fields = "", $order_by = "", $group_by = "", $join_arr = array(), $assoc_field = "")
    {
        $fields = ($fields == "") ? "mgc.*" : $fields;
        $this->CI->db->select($fields, FALSE);
        $this->CI->db->from('mod_group_capabilities AS mgc');
        $this->CI->db->join('mod_capability_master AS mcm', 'mcm.iCapabilityId = mgc.iCapabilityId', 'inner');
        if (is_array($extra_cond) && count($extra_cond) > 0) {
            $this->CI->general->addWhereFields($extra_cond);
        } elseif ($extra_cond != "") {
            if (is_numeric($extra_cond)) {
                $this->CI->db->where('mgc.iGroupId', $extra_cond);
            } else {
                $this->CI->db->where($extra_cond, FALSE, FALSE);
            }
        }
        if ($group_by != "") {
            $this->CI->db->group_by($group_by);
        }
        if ($order_by != "") {
            $this->CI->db->order_by($order_by);
        }
        $list_data_obj = $this->CI->db->get();
        $list_data = is_object($list_data_obj) ? $list_data_obj->result_array() : array();
        if ($assoc_field != '') {
            foreach ($list_data as $val) {
                $final_array[$val[$assoc_field]][] = $val;
            }
            $list_data = $final_array;
        }
        #echo $this->CI->db->last_query();
        return $list_data;
    }

    public function checkCapabilityStatus($data = array())
    {
        $can_access = TRUE;
        $capable_type = $data['eCapabilityType'];
        if ($capable_type == "FormField" || !empty($data['tCapabilities'])) {
            $can_access = json_decode($data['tCapabilities'], TRUE);
            if (!is_array($can_access)) {
                $can_access = TRUE;
            }
        }
        return $can_access;
    }

    public function getListFieldCapability($field_name = '', $module_name = '')
    {
        $capability = $module_name . '_' . $field_name . '_list';
        return $capability;
    }

    public function getFormFieldCapability($field_name = '', $module_name = '', $mode = '')
    {
        $capability = $module_name . '_' . $field_name . '_' . strtolower($mode) . '_form';
        return $capability;
    }

    public function setListFieldCapability(&$codes = array(), $module_name = '')
    {
        if (!is_array($codes)) {
            return FALSE;
        }
        $list_codes = array();
        foreach ($codes as $key => $val) {
            if (!is_string($val['hidden'])) {
                continue;
            }
            if (in_array($val['hidden'], array("Yes", "No"))) {
                continue;
            }
            $list_codes[$key] = $val['hidden'];
        }
        if (count($list_codes) == 0) {
            return FALSE;
        }
        $list_fields = array_keys($list_codes);
        $access_keys = array_values($list_codes);
        $list = $this->checkAccessCapability($access_keys, TRUE);
        foreach ($list_fields as $key => $val) {
            if ($list[$key]) {
                $codes[$val]['hidden'] = 'No';
            } else {
                $codes[$val]['hidden'] = "Yes";
            }
        }
        return TRUE;
    }

    public function setFormFieldCapability(&$codes = array(), $module_name = '', $mode = '', $multi = FALSE)
    {
        if (!is_array($codes)) {
            return FALSE;
        }
        $iter_codes = $codes;
        if ($multi == TRUE) {
            $index_list = array_keys($codes);
            if (!is_array($index_list) || count($index_list) == 0) {
                return FALSE;
            }
            $iter_codes = $codes[$index_list[0]];
        }
        $form_codes = array();
        foreach ($iter_codes as $key => $val) {
            if (!is_string($val)) {
                continue;
            }
            $form_codes[$key] = $val;
        }
        if (count($form_codes) == 0) {
            return FALSE;
        }

        $iter_status = array();
        $form_fields = array_keys($form_codes);
        $access_keys = array_values($form_codes);
        $list = $this->checkAccessCapability($access_keys, TRUE);
        foreach ($form_fields as $key => $val) {
            if ($list[$key] === FALSE) {
                $status = 0;
            } else {
                if ($mode == "Add") {
                    if (strtolower($list[$key]['access_mode']) == "readonly") {
                        $status = 2;
                    } else {
                        $status = 1;
                    }
                } elseif ($mode == "Update") {
                    if (strtolower($list[$key]['access_mode']) == "readonly") {
                        $status = 2;
                    } else {
                        $status = 1;
                    }
                } else {
                    $status = 1;
                }
            }
            if ($multi == TRUE) {
                $iter_status[$val] = $status;
            } else {
                $codes[$val] = $status;
            }
        }
        if ($multi == TRUE) {
            foreach ($codes as $outKey => $outVal) {
                foreach ($outVal as $inrKey => $inrVal) {
                    if (isset($iter_status[$inrKey])) {
                        $codes[$outKey][$inrKey] = $iter_status[$inrKey];
                    }
                }
            }
        }
        return TRUE;
    }
}

/* End of file Filter.php */
/* Location: ./application/libraries/Filter.php */