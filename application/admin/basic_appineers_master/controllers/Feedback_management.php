<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Feedback Management Controller
 *
 * @category admin
 *
 * @package basic_appineers_master
 *
 * @subpackage controllers
 *
 * @module Feedback Management
 *
 * @class Feedback_management.php
 *
 * @path application\admin\basic_appineers_master\controllers\Feedback_management.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 07.02.2020
 */

class Feedback_management extends Cit_Controller
{
    /**
     * __construct method is used to set controller preferences while controller object initialization.
     * @created priyanka chillakuru | 10.09.2019
     * @modified priyanka chillakuru | 07.02.2020
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('listing');
        $this->load->library('filter');
        $this->load->library('dropdown');
        $this->load->model('feedback_management_model');
        $this->folder_name = "basic_appineers_master";
        $this->module_name = "feedback_management";
        $this->mod_enc_url = $this->general->getGeneralEncryptList($this->folder_name, $this->module_name);
        $this->mod_enc_mode = $this->general->getCustomEncryptMode(TRUE);
        $this->_request_params();
        $this->module_config = array(
            'module_name' => $this->module_name,
            'folder_name' => $this->folder_name,
            'mod_enc_url' => $this->mod_enc_url,
            'mod_enc_mode' => $this->mod_enc_mode,
            'delete' => "No",
            'xeditable' => "No",
            'top_detail' => "No",
            "multi_lingual" => "No",
            "print_layouts" => array(),
            "workflow_modes" => array(),
            "physical_data_remove" => "Yes",
            "list_record_callback" => "",
        );
        $this->dropdown_arr = array(
            "uq_user_id" => array(
                "type" => "table",
                "table_name" => "users",
                "field_key" => "iUserId",
                "field_val" => array(
                    $this->db->protect("vFirstName"),
                    "' '",
                    $this->db->protect("vLastName")
                ),
                "order_by" => "val asc",
                "default" => "Yes",
            ),
            "uq_status" => array(
                "type" => "enum",
                "default" => "Yes",
                "values" => array(
                    array(
                        'id' => 'Pending',
                        'val' => $this->lang->line('FEEDBACK_MANAGEMENT_PENDING')
                    ),
                    array(
                        'id' => 'Resolved',
                        'val' => $this->lang->line('FEEDBACK_MANAGEMENT_RESOLVED')
                    ),
                    array(
                        'id' => 'Rejected',
                        'val' => $this->lang->line('FEEDBACK_MANAGEMENT_REJECTED')
                    )
                )
            )
        );
        $this->parMod = $this->params_arr["parMod"];
        $this->parID = $this->params_arr["parID"];
        $this->parRefer = array();
        $this->expRefer = array();

        $this->topRefer = array();
        $this->dropdown_limit = $this->config->item('ADMIN_DROPDOWN_LIMIT');
        $this->search_combo_limit = $this->config->item('ADMIN_SEARCH_COMBO_LIMIT');
        $this->switchto_limit = $this->config->item('ADMIN_SWITCH_DROPDOWN_LIMIT');
        $this->response_arr = array();
        $this->count_arr = array();
    }

    /**
     * _request_params method is used to set post/get/request params.
     */
    public function _request_params()
    {
        $this->get_arr = is_array($this->input->get(NULL, TRUE)) ? $this->input->get(NULL, TRUE) : array();
        $this->post_arr = is_array($this->input->post(NULL, TRUE)) ? $this->input->post(NULL, TRUE) : array();
        $this->params_arr = array_merge($this->get_arr, $this->post_arr);
        return $this->params_arr;
    }

    /**
     * index method is used to intialize grid listing page.
     */
    public function index()
    {
        $params_arr = $this->params_arr;
        $extra_qstr = $extra_hstr = '';
        try
        {
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
            {
                $access_list = array(
                    "feedback_management_list",
                    "feedback_management_view",
                    "feedback_management_add",
                    "feedback_management_update",
                    "feedback_management_delete",
                    "feedback_management_export",
                    "feedback_management_print",
                );
                list($list_access, $view_access, $add_access, $edit_access, $del_access, $expo_access, $print_access) = $this->filter->checkAccessCapability($access_list, TRUE);
            }
            else
            {
                $access_list = array(
                    "List",
                    "View",
                    "Add",
                    "Update",
                    "Delete",
                    "Export",
                    "Print",
                );
                list($list_access, $view_access, $add_access, $edit_access, $del_access, $expo_access, $print_access) = $this->filter->getModuleWiseAccess("feedback_management", $access_list, TRUE, TRUE);
            }
            if (!$list_access)
            {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }
            $enc_loc_module = $this->general->getMD5EncryptString("ListPrefer", "feedback_management");

            $status_array = array(
                'Pending',
                'Resolved',
                'Rejected',
            );
            $status_label = array(
                'js_lang_label.FEEDBACK_MANAGEMENT_PENDING',
                'js_lang_label.FEEDBACK_MANAGEMENT_RESOLVED',
                'js_lang_label.FEEDBACK_MANAGEMENT_REJECTED',
            );

            $list_config = $this->feedback_management_model->getListConfiguration();
            $this->processConfiguration($list_config, $add_access, $edit_access, TRUE);
            $this->general->trackModuleNavigation("Module", "List", "Viewed", $this->mod_enc_url["index"], "feedback_management");
            if (method_exists($this->filter, "setListFieldCapability"))
            {
                $this->filter->setListFieldCapability($list_config);
            }

            $extra_qstr .= $this->general->getRequestURLParams();
            $extra_hstr .= $this->general->getRequestHASHParams();
            $render_arr = array(

                'list_config' => $list_config,
                'count_arr' => $this->count_arr,
                'enc_loc_module' => $enc_loc_module,
                'status_array' => $status_array,
                'status_label' => $status_label,
                'view_access' => $view_access,
                'add_access' => $add_access,
                'edit_access' => $edit_access,
                'del_access' => $del_access,
                'expo_access' => $expo_access,
                'print_access' => $print_access,
                'folder_name' => $this->folder_name,
                'module_name' => $this->module_name,
                'mod_enc_url' => $this->mod_enc_url,
                'mod_enc_mode' => $this->mod_enc_mode,
                'extra_qstr' => $extra_qstr,
                'extra_hstr' => $extra_hstr,
                "capabilities" => array(
                    "hide_multi_select" => "No",
                    "subgrid" => "No",
                ),
                'default_filters' => $this->feedback_management_model->default_filters,
            );
            $this->smarty->assign($render_arr);
            if (!empty($render_arr['overwrite_view']))
            {
                $this->loadView($render_arr['overwrite_view']);
            }
            else
            {
                $this->loadView("feedback_management_index");
            }
        }
        catch(Exception $e)
        {
            $render_arr['err_message'] = $e->getMessage();
            $this->smarty->assign($render_arr);
            $this->loadView($this->config->item('ADMIN_FORBIDDEN_TEMPLATE'));
        }
    }

    /**
     * listing method is used to load listing data records in json format.
     */
    public function listing()
    {
        $params_arr = $this->params_arr;
        $page = $params_arr['page'];
        $rows = $params_arr['rows'];
        $sidx = $params_arr['sidx'];
        $sord = $params_arr['sord'];
        $sdef = $params_arr['sdef'];
        $filters = $params_arr['filters'];
        if (!trim($sidx) && !trim($sord))
        {
            $sdef = 'Yes';
        }
        $filters = json_decode($filters, TRUE);
        $list_config = $this->feedback_management_model->getListConfiguration();
        $form_config = $this->feedback_management_model->getFormConfiguration();
        $extra_cond = $this->feedback_management_model->extra_cond;
        $groupby_cond = $this->feedback_management_model->groupby_cond;
        $having_cond = $this->feedback_management_model->having_cond;
        $orderby_cond = $this->feedback_management_model->orderby_cond;

        $data_config = array();
        $data_config['page'] = $page;
        $data_config['rows'] = $rows;
        $data_config['sidx'] = $sidx;
        $data_config['sord'] = $sord;
        $data_config['sdef'] = $sdef;
        $data_config['filters'] = $filters;
        $data_config['module_config'] = $this->module_config;
        $data_config['list_config'] = $list_config;
        $data_config['form_config'] = $form_config;
        $data_config['dropdown_arr'] = $this->dropdown_arr;
        $data_config['extra_cond'] = $extra_cond;
        $data_config['group_by'] = $groupby_cond;
        $data_config['having_cond'] = $having_cond;
        $data_config['order_by'] = $orderby_cond;

        $data_recs = $this->feedback_management_model->getListingData($data_config);
        $data_recs['no_records_msg'] = $this->general->processMessageLabel('ACTION_NO_FEEDBACK_MANAGEMENT_DATA_FOUND_C46_C46_C33');

        $data_recs = $this->feedback_management_model->processCustomLinks($data_recs, $list_config);

        echo json_encode($data_recs);
        $this->skip_template_view();
    }

    /**
     * export method is used to export listing data records in csv or pdf formats.
     */
    public function export()
    {
        if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
        {
            $this->filter->checkAccessCapability("feedback_management_export");
        }
        else
        {
            $this->filter->getModuleWiseAccess("feedback_management", "Export", TRUE);
        }
        $params_arr = $this->params_arr;
        $page = $params_arr['page'];
        $rowlimit = $params_arr['rowlimit'];
        $sidx = $params_arr['sidx'];
        $sord = $params_arr['sord'];
        $sdef = $params_arr['sdef'];
        if (!trim($sidx) && !trim($sord))
        {
            $sdef = 'Yes';
        }
        $selected = $params_arr['selected'];
        $id = explode(",", $params_arr['id']);
        $export_type = $params_arr['export_type'];
        $export_mode = $params_arr['export_mode'];
        $filters = $params_arr['filters'];
        $filters = json_decode(base64_decode($filters), TRUE);
        $fields = json_decode(base64_decode($params_arr['fields']), TRUE);
        $list_config = $this->feedback_management_model->getListConfiguration();
        $form_config = $this->feedback_management_model->getFormConfiguration();
        $table_name = $this->feedback_management_model->table_name;
        $table_alias = $this->feedback_management_modeltable_alias;
        $primary_key = $this->feedback_management_model->primary_key;
        $extra_cond = $this->feedback_management_model->extra_cond;
        $groupby_cond = $this->feedback_management_model->groupby_cond;
        $having_cond = $this->feedback_management_model->having_cond;
        $orderby_cond = $this->feedback_management_model->orderby_cond;
        if (method_exists($this->filter, "setListFieldCapability"))
        {
            $this->filter->setListFieldCapability($list_config);
        }
        $export_config = array();
        if ($selected == "true")
        {
            $export_config['id'] = $id;
        }
        $export_config['page'] = $page;
        $export_config['rowlimit'] = $rowlimit;
        $export_config['sidx'] = $sidx;
        $export_config['sord'] = $sord;
        $export_config['sdef'] = $sdef;
        $export_config['filters'] = $filters;
        $export_config['export_mode'] = $export_mode;
        $export_config['module_config'] = $this->module_config;
        $export_config['list_config'] = $list_config;
        $export_config['form_config'] = $form_config;
        $export_config['dropdown_arr'] = $this->dropdown_arr;
        $export_config['table_name'] = $table_name;
        $export_config['table_alias'] = $table_alias;
        $export_config['primary_key'] = $primary_key;
        $export_config['extra_cond'] = $extra_cond;
        $export_config['group_by'] = $groupby_cond;
        $export_config['having_cond'] = $having_cond;
        $export_config['order_by'] = $orderby_cond;

        $db_recs = $this->feedback_management_model->getExportData($export_config);
        $db_recs = $this->listing->getDataForList($db_recs, $export_config, "GExport", array());
        if (!is_array($db_recs) || count($db_recs) == 0)
        {
            $this->session->set_flashdata('failure', $this->general->processMessageLabel('GENERIC_GRID_NO_RECORDS_TO_PROCESS'));
            redirect($_SERVER['HTTP_REFERER']);
        }

        require_once ($this->config->item('third_party').'Csv_export.php');
        require_once ($this->config->item('third_party').'Pdf_export.php');

        $tot_fields_arr = array_keys($db_recs[0]);
        if ($export_mode == "all" && is_array($tot_fields_arr))
        {
            if (($pr_key = array_search($primary_key, $tot_fields_arr)) !== FALSE)
            {
                unset($tot_fields_arr[$pr_key]);
            }
            $fields = array();
            if ($this->config->item("DISABLE_LIST_EXPORT_ALL"))
            {
                foreach ((array) $list_config as $key => $val)
                {
                    if (isset($val['export']) && $val['export'] == "Yes")
                    {
                        if (isset($val['hidecm']))
                        {
                            if (in_array($val['hidecm'], array("condition", "capability", "permanent")) && $val['hidden'] == "Yes")
                            {
                                continue;
                            }
                            if ($val['hideme'] == "Yes")
                            {
                                continue;
                            }
                        }
                        $fields[] = $key;
                    }
                }
            }
            else
            {
                $fields = array_values($tot_fields_arr);
            }
        }

        $misc_info = array();
        $misc_info['fields'] = $fields;
        $misc_info['heading'] = $this->lang->line('FEEDBACK_MANAGEMENT_FEEDBACK_MANAGEMENT');
        $misc_info['filename'] = "feedback_management_records_".count($db_recs);
        $misc_info['pdf_unit'] = PDF_UNIT;
        $misc_info['pdf_page_format'] = PDF_PAGE_FORMAT;
        $misc_info['pdf_page_orientation'] = (!empty($params_arr['orientation'])) ? $params_arr['orientation'] : PDF_PAGE_ORIENTATION;
        $misc_info['pdf_content_before_table'] = '';
        $misc_info['pdf_content_after_table'] = '';
        $misc_info['pdf_header_style'] = '';

        $fields = $misc_info['fields'];
        $heading = $misc_info['heading'];
        $filename = $misc_info['filename'];

        $numberOfColumns = count($fields);
        if ($export_type == 'pdf')
        {
            $pdf_style = "TCPDF";
            $columns = $aligns = $widths = $data = array();
            //Table headers info
            for ($i = 0; $i < $numberOfColumns; $i++)
            {
                $size = 10;
                $position = '';
                if (array_key_exists($fields[$i], $list_config))
                {
                    $label = $list_config[$fields[$i]]['label_lang'];
                    $position = $list_config[$fields[$i]]['align'];
                    $size = $list_config[$fields[$i]]['width'];
                }
                elseif (array_key_exists($fields[$i], $form_config))
                {
                    $label = $form_config[$fields[$i]]['label_lang'];
                }
                else
                {
                    $label = $fields[$i];
                }
                $columns[] = $label;
                $aligns[] = in_array($position, array('right', 'center')) ? $position : "left";
                $widths[] = $size;
            }

            //Table data info
            $db_rec_cnt = count($db_recs);
            for ($i = 0; $i < $db_rec_cnt; $i++)
            {
                foreach ((array) $db_recs[$i] as $key => $val)
                {
                    if (is_array($fields) && in_array($key, $fields))
                    {
                        $data[$i][$key] = $this->listing->dataForExportMode($val, "pdf", $pdf_style);
                    }
                }
            }

            $pdf = new PDF_Export($misc_info['pdf_page_orientation'], $misc_info['pdf_unit'], $misc_info['pdf_page_format'], TRUE, 'UTF-8', FALSE);
            if (method_exists($pdf, "setModule"))
            {
                $pdf->setModule("feedback_management_model");
            }
            if (method_exists($pdf, "setContent"))
            {
                $pdf->setContent($misc_info);
            }
            if (method_exists($pdf, "setController"))
            {
                $pdf->setController($this);
            }
            $pdf->initialize($heading);
            $pdf->writeGridTable($columns, $data, $widths, $aligns);
            $pdf->Output($filename.".pdf", 'D');
        }
        elseif ($export_type == 'csv')
        {
            $columns = $data = array();

            for ($i = 0; $i < $numberOfColumns; $i++)
            {
                if (array_key_exists($fields[$i], $list_config))
                {
                    $label = $list_config[$fields[$i]]['label_lang'];
                }
                elseif (array_key_exists($fields[$i], $form_config))
                {
                    $label = $form_config[$fields[$i]]['label_lang'];
                }
                else
                {
                    $label = $fields[$i];
                }
                $columns[] = $label;
            }
            $db_recs_cnt = count($db_recs);
            for ($i = 0; $i < $db_recs_cnt; $i++)
            {
                foreach ((array) $db_recs[$i] as $key => $val)
                {
                    if (is_array($fields) && in_array($key, $fields))
                    {
                        $data[$i][$key] = $this->listing->dataForExportMode($val, "csv");
                    }
                }
            }
            $export_array = array_merge(array($columns), $data);
            $csv = new CSV_Writer($export_array);
            $csv->headers($filename);
            $csv->output();
        }
        $this->skip_template_view();
    }

    /**
     * add method is used to add or update data records.
     */
    public function add()
    {
        $params_arr = $this->params_arr;
        $extra_qstr = $extra_hstr = '';
        $hideCtrl = $params_arr['hideCtrl'];
        $showDetail = $params_arr['showDetail'];
        $mode = (in_array($params_arr['mode'], array("Update", "View"))) ? "Update" : "Add";
        $viewMode = ($params_arr['mode'] == "View") ? TRUE : FALSE;
        $id = $params_arr['id'];
        $enc_id = $this->general->getAdminEncodeURL($id);
        try
        {
            $extra_cond = $this->feedback_management_model->extra_cond;
            if ($mode == "Update")
            {
                if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
                {
                    $access_list = array(
                        "feedback_management_list",
                        "feedback_management_view",
                        "feedback_management_update",
                        "feedback_management_delete",
                        "feedback_management_print",
                    );
                    list($list_access, $view_access, $edit_access, $del_access, $print_access) = $this->filter->checkAccessCapability($access_list, TRUE);
                }
                else
                {
                    $access_list = array(
                        "List",
                        "View",
                        "Update",
                        "Delete",
                        "Print",
                    );
                    list($list_access, $view_access, $edit_access, $del_access, $print_access) = $this->filter->getModuleWiseAccess("feedback_management", $access_list, TRUE, TRUE);
                }
                if (!$edit_access && !$view_access)
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
                }
            }
            else
            {
                if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
                {
                    $access_list = array(
                        "feedback_management_list",
                        "feedback_management_add",
                    );
                    list($list_access, $add_access) = $this->filter->checkAccessCapability($access_list, TRUE);
                }
                else
                {
                    $access_list = array(
                        "List",
                        "Add",
                    );
                    list($list_access, $add_access) = $this->filter->getModuleWiseAccess("feedback_management", $access_list, TRUE, TRUE);
                }
                if (!$add_access)
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_ADD_THESE_DETAILS_C46_C46_C33'));
                }
            }

            $data = $orgi = $func = $elem = array();
            if ($mode == 'Update')
            {
                $ctrl_flow = $this->ci_local->read($this->general->getMD5EncryptString("FlowEdit", "feedback_management"), $this->session->userdata('iAdminId'));
                $data_arr = $this->feedback_management_model->getData(intval($id));
                $data = $orgi = $data_arr[0];
                if ((!is_array($data) || count($data) == 0) && $params_arr['rmPopup'] != "true")
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_RECORDS_WHICH_YOU_ARE_TRYING_TO_ACCESS_ARE_NOT_AVAILABLE_C46_C46_C33'));
                }

                $switch_arr = $switch_combo = $switch_cit = array();

                $recName = $switch_combo[$id];
                $switch_enc_combo = $this->filter->getSwitchEncryptRec($switch_combo, $switch_arr);
                $this->dropdown->combo("array", "vSwitchPage", $switch_enc_combo, $enc_id, FALSE, "key_value", $switch_arr);
                $next_prev_records = $this->filter->getNextPrevRecords($id, $switch_arr);

                $this->general->trackModuleNavigation("Module", "Form", "Viewed", $this->mod_enc_url["add"], "feedback_management", $recName);
            }
            else
            {
                $recName = '';
                $ctrl_flow = $this->ci_local->read($this->general->getMD5EncryptString("FlowAdd", "feedback_management"), $this->session->userdata('iAdminId'));
                $this->general->trackModuleNavigation("Module", "Form", "Viewed", $this->mod_enc_url["add"], "feedback_management");
            }

            $opt_arr = $img_html = $auto_arr = $config_arr = array();
            if ($mode == "Update")
            {

                $main_data = $this->feedback_management_model->getData(intval($id), array('uq.iUserQueryId'));
            }
            else
            {
                $main_data = array();
            }
            $relation_module = $this->feedback_management_model->relation_modules["query_images"];
            $this->load->module("basic_appineers_master/query_images");
            $this->load->model("basic_appineers_master/query_images_model");
            $child_config = array();
            $child_config["parent_module"] = "feedback_management";
            $child_config["child_module"] = "query_images";
            $child_config["callback"] = "controller::getQueryImages";
            if ($mode == "Update")
            {
                $extra_cond = $this->db->protect("uqi.iUserQueryId")." = ".$this->db->escape($main_data[0]["iUserQueryId"]);
                if ($relation_module["extra_cond"] != "")
                {
                    $extra_cond .= " AND ".$relation_module["extra_cond"];
                }
                $parent_join_arr = array(
                    "joins" => array(
                        array(
                            "table_name" => "user_query",
                            "table_alias" => "uq",
                            "field_name" => "iUserQueryId",
                            "rel_table_name" => "user_query_images",
                            "rel_table_alias" => "uqi",
                            "rel_field_name" => "iUserQueryId",
                            "join_type" => "left",
                        )
                    )
                );
                $child_data = $this->query_images_model->getData($extra_cond, "", "", "uqi.iUserQueryImageId", "", $parent_join_arr);
                $child_config["mode"] = (is_array($child_data) && count($child_data) > 0) ? "Update" : "Add";
                $child_config["data"] = $child_data;
                $child_config["parent_data"] = $data;
            }
            else
            {
                $child_config["mode"] = "Add";
                $child_config["data"] = array();
            }
            $child_config["mode"] = (is_array($child_config["data"]) && count($child_config["data"]) > 0) ? "Update" : "Add";
            $child_config["multi_file"] = "Yes";
            $child_config["perform"] = array(
                "data",
                "options",
                "config",
            );

            $module_arr = $this->query_images->getRelationModule($child_config);
            $module_arr["config_arr"]["popup"] = $relation_module["popup"];
            $module_arr["config_arr"]["recMode"] = $child_config["mode"];
            $module_arr["config_arr"]["form_config"] = $module_arr["form_config"];
            $child_assoc_data["query_images"] = $module_arr["data"];
            $child_assoc_func["query_images"] = $module_arr["func"];
            $child_assoc_elem["query_images"] = $module_arr["elem"];
            $child_assoc_conf["query_images"] = $module_arr["config_arr"];
            $child_assoc_opt["query_images"] = $module_arr["opt_arr"];
            $child_assoc_img["query_images"] = $module_arr["img_html"];
            $child_assoc_auto["query_images"] = $module_arr["auto_arr"];
            $child_assoc_status["query_images"] = $module_arr["status"];
            $child_assoc_access["query_images"] = array(
                "add" => 1,
                "save" => 1,
                "delete" => 1,
                "actions" => 1,
                "labels" => array()
            );

            $form_config = $this->feedback_management_model->getFormConfiguration($config_arr);
            if (is_array($form_config) && count($form_config) > 0)
            {
                foreach ($form_config as $key => $val)
                {
                    if ($params_arr['rmPopup'] == "true" && $params_arr[$key] != "")
                    {
                        $data[$key] = $params_arr[$key];
                    }
                    elseif ($val["dfapply"] != "")
                    {
                        $val['default'] = (substr($val['default'], 0, 6) == "copy::") ? $orgi[substr($val['default'], 6)] : $val['default'];
                        if ($val["dfapply"] == "forceApply" || $val["entry_type"] == "Custom")
                        {
                            $data[$key] = $val['default'];
                        }
                        elseif ($val["dfapply"] == "addOnly")
                        {
                            if ($mode == "Add")
                            {
                                $data[$key] = $val['default'];
                            }
                        }
                        elseif ($val["dfapply"] == "everyUpdate")
                        {
                            if ($mode == "Update")
                            {
                                $data[$key] = $val['default'];
                            }
                        }
                        else
                        {
                            $data[$key] = (trim($data[$key]) != "") ? $data[$key] : $val['default'];
                        }
                    }
                    if ($val['encrypt'] == "Yes")
                    {
                        $data[$key] = $this->general->decryptDataMethod($data[$key], $val["enctype"]);
                    }
                    if ($val['function'] != "")
                    {
                        $fnctype = $val['functype'];
                        $phpfunc = $val['function'];
                        $tmpdata = '';
                        if (substr($phpfunc, 0, 12) == 'controller::' && substr($phpfunc, 12) !== FALSE)
                        {
                            $phpfunc = substr($phpfunc, 12);
                            if (method_exists($this, $phpfunc))
                            {
                                $tmpdata = $this->$phpfunc($mode, $data[$key], $data, $id, $key, $key, $this->module_name);
                            }
                        }
                        elseif (substr($phpfunc, 0, 7) == 'model::' && substr($phpfunc, 7) !== FALSE)
                        {
                            $phpfunc = substr($phpfunc, 7);
                            if (method_exists($this->feedback_management_model, $phpfunc))
                            {
                                $tmpdata = $this->feedback_management_model->$phpfunc($mode, $data[$key], $data, $id, $key, $key, $this->module_name);
                            }
                        }
                        elseif (method_exists($this->general, $phpfunc))
                        {
                            $tmpdata = $this->general->$phpfunc($mode, $data[$key], $data, $id, $key, $key, $this->module_name);
                        }
                        if ($fnctype == "input")
                        {
                            $elem[$key] = $tmpdata;
                        }
                        elseif ($fnctype == "status")
                        {
                            $func[$key] = $tmpdata;
                        }
                        else
                        {
                            $data[$key] = $tmpdata;
                        }
                    }
                    if ($val['field_status'] != "")
                    {
                        $status_type = $val['field_status'];
                        $fd_callback = $val['field_callback'];
                        if ($status_type == "capability" && $fd_callback != "")
                        {
                            $func[$key] = $this->filter->getFormFieldCapability($key, $this->module_name, $mode);
                        }
                        elseif ($status_type == "function")
                        {
                            $fd_status = 0;
                            if (substr($fd_callback, 0, 12) == 'controller::' && substr($fd_callback, 12) !== FALSE)
                            {
                                $fd_callback = substr($fd_callback, 12);
                                if (method_exists($this, $fd_callback))
                                {
                                    $fd_status = $this->$fd_callback($mode, $data[$key], $data, $id, $key, $key, $this->module_name);
                                }
                            }
                            elseif (substr($fd_callback, 0, 7) == 'model::' && substr($fd_callback, 7) !== FALSE)
                            {
                                $fd_callback = substr($fd_callback, 7);
                                if (method_exists($this->feedback_management_model, $fd_callback))
                                {
                                    $fd_status = $this->feedback_management_model->$fd_callback($mode, $data[$key], $data, $id, $key, $key, $this->module_name);
                                }
                            }
                            elseif (method_exists($this->general, $fd_callback))
                            {
                                $fd_status = $this->general->$fd_callback($mode, $data[$key], $data, $id, $key, $key, $this->module_name);
                            }
                            $func[$key] = $fd_status;
                        }
                    }
                    $source_field = $val['name'];
                    $combo_config = $this->dropdown_arr[$source_field];
                    if (is_array($combo_config) && count($combo_config) > 0)
                    {
                        if ($combo_config['auto'] == "Yes")
                        {
                            $combo_count = $this->getSourceOptions($source_field, $mode, $id, $data, '', 'count');
                            if ($combo_count[0]['tot'] > $this->dropdown_limit)
                            {
                                $auto_arr[$source_field] = "Yes";
                            }
                        }
                        $combo_arr = $this->getSourceOptions($source_field, $mode, $id, $data);
                        $final_arr = $this->filter->makeArrayDropdown($combo_arr);
                        if ($combo_config['opt_group'] == "Yes")
                        {
                            $display_arr = $this->filter->makeOPTDropdown($combo_arr);
                        }
                        else
                        {
                            $display_arr = $final_arr;
                        }
                        $this->dropdown->combo("array", $source_field, $display_arr, $data[$key]);
                        $opt_arr[$source_field] = $final_arr;
                    }
                }
            }
            if (method_exists($this->filter, "setFormFieldCapability"))
            {
                $this->filter->setFormFieldCapability($func, $this->module_name, $mode);
            }
            $extra_qstr .= $this->general->getRequestURLParams();
            $extra_hstr .= $this->general->getRequestHASHParams();

            /** access controls <<< **/
            $controls_allow = $prev_link_allow = $next_link_allow = $update_allow = $delete_allow = $backlink_allow = $switchto_allow = $discard_allow = $tabing_allow = TRUE;
            if ($mode == "Update")
            {
                if (!$del_access || $this->module_config["delete"] == "Yes")
                {
                    $delete_allow = FALSE;
                }
            }
            if (is_array($switch_combo) && count($switch_combo) > 0)
            {
                $prev_link_allow = ($next_prev_records['prev']['id'] != '') ? TRUE : FALSE;
                $next_link_allow = ($next_prev_records['next']['id'] != '') ? TRUE : FALSE;
            }
            else
            {
                $prev_link_allow = $next_link_allow = $switchto_allow = FALSE;
            }
            if (!$list_access)
            {
                $backlink_allow = $discard_allow = FALSE;
            }
            if ($hideCtrl == "true")
            {
                $controls_allow = $prev_link_allow = $next_link_allow = $delete_allow = $backlink_allow = $switchto_allow = $tabing_allow = FALSE;
            }
            /** access controls >>> **/
            $render_arr = array(

                "child_assoc_data" => $child_assoc_data,
                "child_assoc_func" => $child_assoc_func,
                "child_assoc_elem" => $child_assoc_elem,
                "child_assoc_conf" => $child_assoc_conf,
                "child_assoc_opt" => $child_assoc_opt,
                "child_assoc_img" => $child_assoc_img,
                "child_assoc_auto" => $child_assoc_auto,
                "child_assoc_status" => $child_assoc_status,
                "child_assoc_access" => $child_assoc_access,

                "edit_access" => $edit_access,
                "print_access" => $print_access,
                'controls_allow' => $controls_allow,
                'prev_link_allow' => $prev_link_allow,
                'next_link_allow' => $next_link_allow,
                'update_allow' => $update_allow,
                'delete_allow' => $delete_allow,
                'backlink_allow' => $backlink_allow,
                'switchto_allow' => $switchto_allow,
                'discard_allow' => $discard_allow,
                'tabing_allow' => $tabing_allow,
                'enc_id' => $enc_id,
                'id' => $id,
                'mode' => $mode,
                'data' => $data,
                'func' => $func,
                'elem' => $elem,
                'recName' => $recName,
                "opt_arr" => $opt_arr,
                "img_html" => $img_html,
                "auto_arr" => $auto_arr,
                'ctrl_flow' => $ctrl_flow,
                'switch_cit' => $switch_cit,
                "switch_arr" => $switch_arr,
                'switch_combo' => $switch_combo,
                'next_prev_records' => $next_prev_records,
                "form_config" => $form_config,
                'folder_name' => $this->folder_name,
                'module_name' => $this->module_name,
                'mod_enc_url' => $this->mod_enc_url,
                'mod_enc_mode' => $this->mod_enc_mode,
                'extra_qstr' => $extra_qstr,
                'extra_hstr' => $extra_hstr,
                'capabilities' => array()
            );
            $this->smarty->assign($render_arr);
            if (!empty($render_arr['overwrite_view']))
            {
                $this->loadView($render_arr['overwrite_view']);
            }
            else
            {
                if ($mode == "Update")
                {
                    if ($edit_access && $viewMode != TRUE)
                    {
                        $this->loadView("feedback_management_add");
                    }
                    else
                    {
                        $this->loadView("feedback_management_add_view");
                    }
                }
                else
                {
                    $this->loadView("feedback_management_add");
                }
            }
        }
        catch(Exception $e)
        {
            $render_arr['err_message'] = $e->getMessage();
            $this->smarty->assign($render_arr);
            $this->loadView($this->config->item('ADMIN_FORBIDDEN_TEMPLATE'));
        }
    }

    /**
     * addAction method is used to save data, which is posted through form.
     */
    public function addAction()
    {
        $params_arr = $this->params_arr;
        $mode = ($params_arr['mode'] == "Update") ? "Update" : "Add";
        $id = $params_arr['id'];
        try
        {
            $ret_arr = array();
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
            {
                if ($mode == "Update")
                {
                    $add_edit_access = $this->filter->checkAccessCapability("feedback_management_update", TRUE);
                }
                else
                {
                    $add_edit_access = $this->filter->checkAccessCapability("feedback_management_add", TRUE);
                }
            }
            else
            {
                $add_edit_access = $this->filter->getModuleWiseAccess("feedback_management", $mode, TRUE, TRUE);
            }
            if (!$add_edit_access)
            {
                if ($mode == "Update")
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                }
                else
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_ADD_THESE_DETAILS_C46_C46_C33'));
                }
            }

            $form_config = $this->feedback_management_model->getFormConfiguration();
            $params_arr = $this->_request_params();
            $uq_user_id = $params_arr["uq_user_id"];
            $uq_feedback = $params_arr["uq_feedback"];
            $uq_added_at = $params_arr["uq_added_at"];
            $uq_device_type = $params_arr["uq_device_type"];
            $uq_device_model = $params_arr["uq_device_model"];
            $uq_device_os = $params_arr["uq_device_os"];
            $uq_app_version = $params_arr["uq_app_version"];
            $uq_note = $params_arr["uq_note"];
            $uq_status = $params_arr["uq_status"];
            $uq_updated_at = $params_arr["uq_updated_at"];

            $data = $save_data_arr = $file_data = array();
            $data["iUserId"] = $uq_user_id;
            $data["tFeedback"] = $uq_feedback;
            $data["dtAddedAt"] = $this->filter->formatActionData($uq_added_at, $form_config["uq_added_at"]);
            $data["eDeviceType"] = $uq_device_type;
            $data["vDeviceModel"] = $uq_device_model;
            $data["vDeviceOS"] = $uq_device_os;
            $data["vAppVersion"] = $uq_app_version;
            $data["tNote"] = $uq_note;
            $data["eStatus"] = $uq_status;
            $data["dtUpdatedAt"] = $this->filter->formatActionData($uq_updated_at, $form_config["uq_updated_at"]);

            $save_data_arr["uq_user_id"] = $data["iUserId"];
            $save_data_arr["uq_feedback"] = $data["tFeedback"];
            $save_data_arr["uq_added_at"] = $data["dtAddedAt"];
            $save_data_arr["uq_device_type"] = $data["eDeviceType"];
            $save_data_arr["uq_device_model"] = $data["vDeviceModel"];
            $save_data_arr["uq_device_os"] = $data["vDeviceOS"];
            $save_data_arr["uq_app_version"] = $data["vAppVersion"];
            $save_data_arr["uq_note"] = $data["tNote"];
            $save_data_arr["uq_status"] = $data["eStatus"];
            $save_data_arr["uq_updated_at"] = $data["dtUpdatedAt"];
            if ($mode == 'Add')
            {
                $id = $this->feedback_management_model->insert($data);
                if (intval($id) > 0)
                {
                    $save_data_arr["iUserQueryId"] = $data["iUserQueryId"] = $id;
                    $msg = $this->general->processMessageLabel('ACTION_RECORD_ADDED_SUCCESSFULLY_C46_C46_C33');
                }
                else
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_ADDING_RECORD_C46_C46_C33'));
                }
                $track_cond = $this->db->protect("uq.iUserQueryId")." = ".$this->db->escape($id);
                $switch_combo = $this->feedback_management_model->getSwitchTo($track_cond);
                $recName = $switch_combo[0]["val"];
                $this->general->trackModuleNavigation("Module", "Form", "Added", $this->mod_enc_url["add"], "feedback_management", $recName, "mode|".$this->general->getAdminEncodeURL("Update")."|id|".$this->general->getAdminEncodeURL($id));
            }
            elseif ($mode == 'Update')
            {
                $res = $this->feedback_management_model->update($data, intval($id));
                if (intval($res) > 0)
                {
                    $save_data_arr["iUserQueryId"] = $data["iUserQueryId"] = $id;
                    $msg = $this->general->processMessageLabel('ACTION_RECORD_SUCCESSFULLY_UPDATED_C46_C46_C33');
                }
                else
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPDATING_OF_THIS_RECORD_C46_C46_C33'));
                }
                $track_cond = $this->db->protect("uq.iUserQueryId")." = ".$this->db->escape($id);
                $switch_combo = $this->feedback_management_model->getSwitchTo($track_cond);
                $recName = $switch_combo[0]["val"];
                $this->general->trackModuleNavigation("Module", "Form", "Modified", $this->mod_enc_url["add"], "feedback_management", $recName, "mode|".$this->general->getAdminEncodeURL("Update")."|id|".$this->general->getAdminEncodeURL($id));
            }
            $ret_arr['id'] = $id;
            $ret_arr['mode'] = $mode;
            $ret_arr['message'] = $msg;
            $ret_arr['success'] = 1;

            $params_arr = $this->_request_params();

            $childModuleArr = $params_arr["childModule"];
            $childPostArr = $params_arr["child"];
            if (is_array($childModuleArr) && count($childModuleArr) > 0)
            {
                $main_data = $this->feedback_management_model->getData(intval($id), array('uq.iUserQueryId'));
                foreach ((array) $childModuleArr as $mdKey => $mdVal)
                {
                    $child_module = $mdVal;
                    if ($params_arr["childModuleShowHide"][$child_module] == "No")
                    {
                        continue;
                    }
                    $child_module_post = $childPostArr[$child_module];
                    $child_id_arr = is_array($child_module_post["id"]) ? $child_module_post["id"] : array();
                    switch ($child_module)
                    {
                        case "query_images":
                            $relation_module = $this->feedback_management_model->relation_modules["query_images"];

                            $child_unique_name = $params_arr["childModuleSingle"][$child_module];
                            $child_unique_arr = $child_module_post[$child_unique_name];
                            $child_del_ids = array();
                            if (is_array($child_unique_arr) && count($child_unique_arr) > 0)
                            {
                                foreach ((array) $child_unique_arr as $chKey => $chVal)
                                {
                                    if (trim($chVal) == "")
                                    {
                                        continue;
                                    }
                                    if (is_array($child_module_post) && count($child_module_post) > 0)
                                    {
                                        foreach ($child_module_post as $elKey => $elVal)
                                        {
                                            if (isset($elVal[$chKey]))
                                            {
                                                $child_module_post[$elKey][0] = $elVal[$chKey];
                                            }
                                        }
                                    }
                                    $child_module_post[$child_unique_name][0] = $chVal;
                                    $child_save_arr["child_module"] = $child_module;
                                    $child_save_arr["index"] = 0;
                                    $child_save_arr["id"] = $child_id_arr[$chKey];
                                    $child_save_arr["data"] = $child_module_post;
                                    $child_save_arr["main_data"] = $main_data;
                                    $child_res = $this->childDataSave($child_save_arr, true);
                                    $child_del_ids[] = $child_res["id"];
                                }
                            }
                            $extra_del = $this->db->protect("uqi.iUserQueryId")." = ".$this->db->escape($main_data[0]["iUserQueryId"])." AND ".$this->db->protect("uqi.iUserQueryImageId")." NOT IN ('".implode("','", $child_del_ids)."')";
                            if ($relation_module["extra_cond"] != "")
                            {
                                $extra_del .= " AND ".$relation_module["extra_cond"];
                            }
                            $child_del_arr["child_module"] = $child_module;
                            $child_del_arr["extra_cond"] = $extra_del;
                            $res = $this->childDataDelete($child_del_arr, true);
                            break;
                        }
                    }
            }
        }
        catch(Exception $e)
        {
            $ret_arr["message"] = $e->getMessage();
            $ret_arr["success"] = 0;
        }
        $ret_arr['mod_enc_url']['add'] = $this->mod_enc_url['add'];
        $ret_arr['mod_enc_url']['index'] = $this->mod_enc_url['index'];
        $ret_arr['red_type'] = 'Stay';
        $this->filter->getPageFlowURL($ret_arr, $this->module_config, $params_arr, $id, $data);

        $this->response_arr = $ret_arr;
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    /**
     * inlineEditAction method is used to save inline editing data records, status field updation,
     * delete records either from grid listing or update form, saving inline adding records from grid
     */
    public function inlineEditAction()
    {
        $params_arr = $this->params_arr;
        $operartor = $params_arr['oper'];
        $all_row_selected = $params_arr['AllRowSelected'];
        $primary_ids = explode(",", $params_arr['id']);
        $primary_ids = count($primary_ids) > 1 ? $primary_ids : $primary_ids[0];
        $filters = $params_arr['filters'];
        $filters = json_decode($filters, TRUE);
        $extra_cond = '';
        $search_mode = $search_join = $search_alias = 'No';
        if ($all_row_selected == "true" && in_array($operartor, array("del", "status")))
        {
            $search_mode = ($operartor == "del") ? "Delete" : "Update";
            $search_join = $search_alias = "Yes";
            $config_arr['module_name'] = $this->module_name;
            $config_arr['list_config'] = $this->feedback_management_model->getListConfiguration();
            $config_arr['form_config'] = $this->feedback_management_model->getFormConfiguration();
            $config_arr['table_name'] = $this->feedback_management_model->table_name;
            $config_arr['table_alias'] = $this->feedback_management_model->table_alias;
            $filter_main = $this->filter->applyFilter($filters, $config_arr, $search_mode);
            $filter_left = $this->filter->applyLeftFilter($filters, $config_arr, $search_mode);
            $filter_range = $this->filter->applyRangeFilter($filters, $config_arr, $search_mode);
            if ($filter_main != "")
            {
                $extra_cond .= ($extra_cond != "") ? " AND (".$filter_main.")" : $filter_main;
            }
            if ($filter_left != "")
            {
                $extra_cond .= ($extra_cond != "") ? " AND (".$filter_left.")" : $filter_left;
            }
            if ($filter_range != "")
            {
                $extra_cond .= ($extra_cond != "") ? " AND (".$filter_range.")" : $filter_range;
            }
        }
        if ($search_alias == "Yes")
        {
            $primary_field = $this->feedback_management_model->table_alias.".".$this->feedback_management_model->primary_key;
        }
        else
        {
            $primary_field = $this->feedback_management_model->primary_key;
        }
        if (is_array($primary_ids))
        {
            $pk_condition = $this->db->protect($primary_field)." IN ('".implode("','", $primary_ids)."')";
        }
        elseif (intval($primary_ids) > 0)
        {
            $pk_condition = $this->db->protect($primary_field)." = ".$this->db->escape($primary_ids);
        }
        else
        {
            $pk_condition = FALSE;
        }
        if ($pk_condition)
        {
            $extra_cond .= ($extra_cond != "") ? " AND (".$pk_condition.")" : $pk_condition;
        }
        $data_arr = $save_data_arr = array();
        try
        {
            switch ($operartor)
            {
                case 'del':
                    $mode = "Delete";
                    if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
                    {
                        $del_access = $this->filter->checkAccessCapability("feedback_management_delete", TRUE);
                    }
                    else
                    {
                        $del_access = $this->filter->getModuleWiseAccess("feedback_management", "Delete", TRUE, TRUE);
                    }
                    if (!$del_access)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_DELETE_THESE_DETAILS_C46_C46_C33'));
                    }
                    if ($search_mode == "No" && $pk_condition == FALSE)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_DELETION_THIS_RECORD_C46_C46_C33'));
                    }
                    $params_arr = $this->_request_params();

                    $success = $this->feedback_management_model->delete($extra_cond, $search_alias, $search_join);
                    if (!$success)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_DELETION_THIS_RECORD_C46_C46_C33'));
                    }
                    $message = $this->general->processMessageLabel('ACTION_RECORD_C40S_C41_DELETED_SUCCESSFULLY_C46_C46_C33');
                    break;
                case 'edit':
                    $mode = "Update";
                    if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
                    {
                        $edit_access = $this->filter->checkAccessCapability("feedback_management_update", TRUE);
                    }
                    else
                    {
                        $edit_access = $this->filter->getModuleWiseAccess("feedback_management", "Update", TRUE, TRUE);
                    }
                    if (!$edit_access)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                    }
                    $post_name = $params_arr['name'];
                    $post_val = is_array($params_arr['value']) ? implode(",", $params_arr['value']) : $params_arr['value'];

                    $list_config = $this->feedback_management_model->getListConfiguration($post_name);
                    $form_config = $this->feedback_management_model->getFormConfiguration($list_config['source_field']);
                    if (!is_array($form_config) || count($form_config) == 0)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FORM_CONFIGURING_NOT_DONE_C46_C46_C33'));
                    }
                    if (in_array($form_config['type'], array("date", "date_and_time", "time", 'phone_number')))
                    {
                        $post_val = $this->filter->formatActionData($post_val, $form_config);
                    }
                    if ($form_config["encrypt"] == "Yes")
                    {
                        $post_val = $this->general->encryptDataMethod($post_val, $form_config["enctype"]);
                    }
                    $field_name = $form_config['field_name'];
                    $unique_name = $form_config['name'];

                    $data_arr[$field_name] = $post_val;
                    $success = $this->feedback_management_model->update($data_arr, intval($primary_ids));
                    $message = $this->general->processMessageLabel('ACTION_RECORD_SUCCESSFULLY_UPDATED_C46_C46_C33');
                    if (!$success)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPDATING_OF_THIS_RECORD_C46_C46_C33'));
                    }
                    break;
                case 'status':
                    $mode = "Status";
                    if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
                    {
                        $edit_access = $this->filter->checkAccessCapability("feedback_management_update", TRUE);
                    }
                    else
                    {
                        $edit_access = $this->filter->getModuleWiseAccess("feedback_management", "Update", TRUE, TRUE);
                    }
                    if (!$edit_access)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                    }
                    if ($search_mode == "No" && $pk_condition == FALSE)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_DELETION_THIS_RECORD_C46_C46_C33'));
                    }
                    $status_field = "eStatus";
                    if ($status_field == "")
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FORM_CONFIGURING_NOT_DONE_C46_C46_C33'));
                    }
                    if ($search_mode == "Yes" || $search_alias == "Yes")
                    {
                        $field_name = $this->feedback_management_model->table_alias.".eStatus";
                    }
                    else
                    {
                        $field_name = $status_field;
                    }
                    $data_arr[$field_name] = $params_arr['status'];
                    $success = $this->feedback_management_model->update($data_arr, $extra_cond, $search_alias, $search_join);
                    if (!$success)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_MODIFYING_THESE_RECORDS_C46_C46_C33'));
                    }
                    $message = $this->general->processMessageLabel('ACTION_RECORD_C40S_C41_MODIFIED_SUCCESSFULLY_C46_C46_C33');
                    break;
            }
            $ret_arr['success'] = "true";
            $ret_arr['message'] = $message;
        }
        catch(Exception $e)
        {
            $ret_arr["success"] = "false";
            $ret_arr["message"] = $e->getMessage();
        }
        $this->response_arr = $ret_arr;
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    /**
     * processConfiguration method is used to process add and edit permissions for grid intialization
     */
    protected function processConfiguration(&$list_config = array(), $isAdd = TRUE, $isEdit = TRUE, $runCombo = FALSE)
    {
        if (!is_array($list_config) || count($list_config) == 0)
        {
            return $list_config;
        }
        $count_arr = array();
        foreach ((array) $list_config as $key => $val)
        {
            if (!$isAdd)
            {
                $list_config[$key]["addable"] = "No";
            }
            if (!$isEdit)
            {
                $list_config[$key]["editable"] = "No";
            }

            $source_field = $val['source_field'];
            $dropdown_arr = $this->dropdown_arr[$source_field];
            if (is_array($dropdown_arr) && in_array($val['type'], array("dropdown", "radio_buttons", "checkboxes", "multi_select_dropdown")))
            {
                $count_arr[$key]['ajax'] = "No";
                $count_arr[$key]['json'] = "No";
                $count_arr[$key]['data'] = array();
                $combo_arr = FALSE;
                if ($dropdown_arr['auto'] == "Yes")
                {
                    $combo_arr = $this->getSourceOptions($source_field, "Search", '', array(), '', 'count');
                    if ($combo_arr[0]['tot'] > $this->dropdown_limit)
                    {
                        $count_arr[$key]['ajax'] = "Yes";
                    }
                }
                if ($runCombo == TRUE)
                {
                    if (in_array($dropdown_arr['type'], array("enum", "phpfn")))
                    {
                        $data_arr = $this->getSourceOptions($source_field, "Search");
                        $json_arr = $this->filter->makeArrayDropdown($data_arr);
                        $count_arr[$key]['json'] = "Yes";
                        $count_arr[$key]['data'] = json_encode($json_arr);
                    }
                    else
                    {
                        if ($dropdown_arr['opt_group'] != "Yes")
                        {
                            if ($combo_arr == FALSE)
                            {
                                $combo_arr = $this->getSourceOptions($source_field, "Search", '', array(), '', 'count');
                            }
                            if ($combo_arr[0]['tot'] < $this->search_combo_limit)
                            {
                                $data_arr = $this->getSourceOptions($source_field, "Search");
                                $json_arr = $this->filter->makeArrayDropdown($data_arr);
                                $count_arr[$key]['json'] = "Yes";
                                $count_arr[$key]['data'] = json_encode($json_arr);
                            }
                        }
                    }
                }
            }
        }
        $this->count_arr = $count_arr;
        return $list_config;
    }

    /**
     * getSourceOptions method is used to get data array of enum, table, token or php function input types
     * @param string $name unique name of form configuration field.
     * @param string $mode mode for add or update form.
     * @param string $id update record id of add or update form.
     * @param array $data data array of add or update record.
     * @param string $extra extra query condition for searching data array.
     * @param string $rtype type for getting either records list or records count.
     * @return array $data_arr returns data records array
     */
    public function getSourceOptions($name = '', $mode = 'Add', $id = '', $data = array(), $extra = '', $rtype = 'records')
    {
        $combo_config = $this->dropdown_arr[$name];
        $data_arr = array();
        if (!is_array($combo_config) || count($combo_config) == 0)
        {
            return $data_arr;
        }
        $type = $combo_config['type'];
        switch ($type)
        {
            case 'enum':
                $data_arr = is_array($combo_config['values']) ? $combo_config['values'] : array();
                break;
            case 'token':
                if ($combo_config['parent_src'] == "Yes" && in_array($mode, array("Add", "Update", "Auto")))
                {
                    $source_field = $combo_config['source_field'];
                    $target_field = $combo_config['target_field'];
                    if (in_array($mode, array("Update", "Auto")) || $data[$source_field] != "")
                    {
                        $parent_src = (is_array($data[$source_field])) ? $data[$source_field] : explode(",", $data[$source_field]);
                        $extra_cond = $this->db->protect($target_field)." IN ('".implode("','", $parent_src)."')";
                    }
                    elseif ($mode == "Add")
                    {
                        $extra_cond = $this->db->protect($target_field)." = ''";
                    }
                    $extra = (trim($extra) != "") ? $extra." AND ".$extra_cond : $extra_cond;
                }
                $data_arr = $this->filter->getTableLevelDropdown($combo_config, $id, $extra, $rtype);
                break;
            case 'table':
                if ($combo_config['auto'] == "Yes" && $mode == "Update")
                {
                    if (!empty($data[$name]))
                    {
                        $selected_rec = (is_array($data[$name])) ? $data[$name] : explode(",", $data[$name]);
                        $selected_rec = $this->general->escape_str($selected_rec);
                        $selected_str = implode(",", $selected_rec);
                        $combo_config['order_by'] = "FIELD(".$combo_config['field_key'].", ".$selected_str.") DESC, ".$combo_config['order_by'];
                    }
                }
                if ($combo_config['parent_src'] == "Yes" && in_array($mode, array("Add", "Update", "Auto")))
                {
                    $source_field = $combo_config['source_field'];
                    $target_field = $combo_config['target_field'];
                    if (in_array($mode, array("Update", "Auto")) || $data[$source_field] != "")
                    {
                        $parent_src = (is_array($data[$source_field])) ? $data[$source_field] : explode(",", $data[$source_field]);
                        $extra_cond = $this->db->protect($target_field)." IN ('".implode("','", $parent_src)."')";
                    }
                    elseif ($mode == "Add")
                    {
                        $extra_cond = $this->db->protect($target_field)." = ''";
                    }
                    $extra = (trim($extra) != "") ? $extra." AND ".$extra_cond : $extra_cond;
                }
                if ($combo_config['parent_child'] == "Yes" && $combo_config['nlevel_child'] == "Yes")
                {
                    $combo_config['main_table'] = $this->feedback_management_model->table_name;
                    $data_arr = $this->filter->getTreeLevelDropdown($combo_config, $id, $extra, $rtype);
                }
                else
                {
                    if ($combo_config['parent_child'] == "Yes" && $combo_config['parent_field'] != "")
                    {
                        $parent_field = $combo_config['parent_field'];
                        $extra_cond = "(".$this->db->protect($parent_field)." = '0' OR ".$this->db->protect($parent_field)." = '' OR ".$this->db->protect($parent_field)." IS NULL )";
                        if ($mode == "Update" || ($mode == "Search" && $id > 0))
                        {
                            $extra_cond .= " AND ".$this->db->protect($combo_config['field_key'])." <> ".$this->db->escape($id);
                        }
                        $extra = (trim($extra) != "") ? $extra." AND ".$extra_cond : $extra_cond;
                    }
                    $data_arr = $this->filter->getTableLevelDropdown($combo_config, $id, $extra, $rtype);
                }
                break;
            case 'phpfn':
                $phpfunc = $combo_config['function'];
                $parent_src = '';
                if ($combo_config['parent_src'] == "Yes" && in_array($mode, array("Add", "Update", "Auto")))
                {
                    $source_field = $combo_config['source_field'];
                    if (in_array($mode, array("Update", "Auto")) || $data[$source_field] != "")
                    {
                        $parent_src = $data[$source_field];
                    }
                }
                if (substr($phpfunc, 0, 12) == 'controller::' && substr($phpfunc, 12) !== FALSE)
                {
                    $phpfunc = substr($phpfunc, 12);
                    if (method_exists($this, $phpfunc))
                    {
                        $data_arr = $this->$phpfunc($data[$name], $mode, $id, $data, $parent_src, $this->term);
                    }
                }
                elseif (substr($phpfunc, 0, 7) == 'model::' && substr($phpfunc, 7) !== FALSE)
                {
                    $phpfunc = substr($phpfunc, 7);
                    if (method_exists($this->feedback_management_model, $phpfunc))
                    {
                        $data_arr = $this->feedback_management_model->$phpfunc($data[$name], $mode, $id, $data, $parent_src, $this->term);
                    }
                }
                elseif (method_exists($this->general, $phpfunc))
                {
                    $data_arr = $this->general->$phpfunc($data[$name], $mode, $id, $data, $parent_src, $this->term);
                }
                break;
        }
        return $data_arr;
    }

    /**
     * getSelfSwitchToPrint method is used to provide autocomplete for switchto dropdown, which is called through form.
     */
    public function getSelfSwitchTo()
    {
        $params_arr = $this->params_arr;

        $term = strtolower($params_arr['data']['q']);

        $switchto_fields = $this->feedback_management_model->switchto_fields;
        $extra_cond = $this->feedback_management_model->extra_cond;

        $concat_fields = $this->db->concat_cast($switchto_fields);
        $search_cond = "(LOWER(".$concat_fields.") LIKE '".$this->db->escape_like_str($term)."%' OR LOWER(".$concat_fields.") LIKE '% ".$this->db->escape_like_str($term)."%')";
        $extra_cond = ($extra_cond == "") ? $search_cond : $extra_cond." AND ".$search_cond;

        $switch_arr = $this->feedback_management_model->getSwitchTo($extra_cond);
        $html_arr = $this->filter->getChosenAutoJSON($switch_arr, array(), FALSE, "auto");

        $json_array['q'] = $term;
        $json_array['results'] = $html_arr;
        $html_str = json_encode($json_array);

        echo $html_str;
        $this->skip_template_view();
    }

    /**
     * getListOptions method is used to get  dropdown values searching or inline editing in grid listing (select options in html or json string)
     */
    public function getListOptions()
    {
        $params_arr = $this->params_arr;
        $alias_name = $params_arr['alias_name'];
        $rformat = $params_arr['rformat'];
        $id = $params_arr['id'];
        $mode = ($params_arr['mode'] == "Search") ? "Search" : (($params_arr['mode'] == "Update") ? "Update" : "Add");
        $config_arr = $this->feedback_management_model->getListConfiguration($alias_name);
        $source_field = $config_arr['source_field'];
        $combo_config = $this->dropdown_arr[$source_field];
        $data_arr = array();
        if ($mode == "Update")
        {
            $data_arr = $this->feedback_management_model->getData(intval($id));
        }
        $combo_arr = $this->getSourceOptions($source_field, $mode, $id, $data_arr[0]);
        if ($rformat == "json")
        {
            $html_str = $this->filter->getChosenAutoJSON($combo_arr, $combo_config, TRUE, "grid");
        }
        else
        {
            if ($combo_config['opt_group'] == "Yes")
            {
                $combo_arr = $this->filter->makeOPTDropdown($combo_arr);
            }
            else
            {
                $combo_arr = $this->filter->makeArrayDropdown($combo_arr);
            }
            $this->dropdown->combo("array", $source_field, $combo_arr, $id);
            $top_option = (in_array($mode, array("Add", "Update")) && $combo_config['default'] == 'Yes') ? "|||" : '';
            $html_str = $this->dropdown->display($source_field, $source_field, ' multiple=true ', $top_option);
        }
        echo $html_str;
        $this->skip_template_view();
    }

    /**
     * childDataAdd method is used to add more inline child module records (relation module records) for add or update form
     */
    public function childDataAdd()
    {

        try
        {
            $params_arr = $this->params_arr;
            $child_module = $params_arr["child_module"];
            $row_index = $params_arr["incNo"];
            $mode = ($params_arr["mode"] == "Update") ? "Update" : "Add";
            $recMode = ($params_arr["recMode"] == "Update") ? "Update" : "Add";
            $rmPopup = ($params_arr["rmPopup"] == "Yes") ? "Yes" : "No";
            $id = $params_arr["id"];
            $child_data = $child_func = $child_elem = array();
            switch ($child_module)
            {
                case "query_images":
                    $this->load->module("basic_appineers_master/query_images");
                    $this->load->model("basic_appineers_master/query_images_model");
                    $child_config["parent_module"] = "feedback_management";
                    $child_config["child_module"] = $child_module;
                    $child_config["mode"] = $recMode;
                    if ($recMode == "Update")
                    {
                        $child_mod_data = $this->query_images_model->getData(intval($id), "", "", "", "", "Yes");
                        $child_config["data"][$row_index] = $child_mod_data[0];
                    }
                    $child_config["row_index"] = $row_index;
                    $child_config["perform"] = array(
                        "data",
                        "options",
                        "config",
                    );
                    $module_arr = $this->query_images->getRelationModule($child_config);
                    $module_arr["config_arr"]["form_config"] = $module_arr["form_config"];
                    $child_data[0] = $module_arr["data"][$row_index];
                    $child_func[0] = $module_arr["func"][$row_index];
                    $child_elem[0] = $module_arr["elem"][$row_index];
                    $child_conf_arr = $module_arr["config_arr"];
                    $child_opt_arr = $module_arr["opt_arr"];
                    $child_img_html = $module_arr["img_html"];
                    $child_auto_arr = $module_arr["auto_arr"];
                    $child_access_arr = array(
                        "add" => 1,
                        "save" => 1,
                        "delete" => 1,
                        "actions" => 1,
                        "labels" => array()
                    );
                    $child_module_add_file = "feedback_management_query_images_add_more";
                    break;
            }
            $enc_child_id = $this->general->getAdminEncodeURL($id);
            $render_arr = array(
                "mod_enc_url" => $this->mod_enc_url,
                "mod_enc_mode" => $this->mod_enc_mode,
                "child_module_name" => $child_module,
                "row_index" => $row_index,
                "child_data" => $child_data,
                "child_func" => $child_func,
                "child_elem" => $child_elem,
                "child_conf_arr" => $child_conf_arr,
                "child_opt_arr" => $child_opt_arr,
                "child_img_html" => $child_img_html,
                "child_auto_arr" => $child_auto_arr,
                "child_access_arr" => $child_access_arr,
                "recMode" => $recMode,
                "popup" => $rmPopup,
                "mode" => $mode,
                "child_id" => $id,
                "enc_child_id" => $enc_child_id,
            );
            $parse_html = $this->parser->parse($child_module_add_file, $render_arr, true);
            echo $parse_html;
        }
        catch(Exception $e)
        {
            $success = 0;
            $msg = $e->getMessage();
            echo $msg;
        }
        $this->skip_template_view();
    }
    /**
     * childDataSave method is used to save inline child module records (relation module records) from add or update form
     * @param array $config_arr config array for saving child module data records.
     * @param boolean $called_func for setting flag to differ function call of url call.
     * @return array $res_arr returns saving data record response
     */
    public function childDataSave($config_arr = array(), $called_func = FALSE)
    {

        try
        {
            $params_arr = $this->params_arr;
            if ($called_func == TRUE)
            {
                $child_module = $config_arr["child_module"];
                $index = $config_arr["index"];
                $id = $config_arr["id"];
                $child_post = $config_arr["data"];
                $main_data = $config_arr["main_data"];
            }
            else
            {
                $child_module = $params_arr["child_module"];
                $index = $params_arr["index"];
                $id = $params_arr["id"];
                $child_post = $params_arr["child"][$child_module];
            }
            switch ($child_module)
            {
                case "query_images":
                    $this->load->module("basic_appineers_master/query_images");
                    $this->load->model("basic_appineers_master/query_images_model");

                    $child_config["parent_module"] = "feedback_management";
                    $child_config["perform"] = array(
                        "config",
                    );
                    $module_arr = $this->query_images->getRelationModule($child_config);
                    $form_config = $module_arr["form_config"];
                    $data_arr = $this->query_images_model->getData(intval($id));
                    $mode = (is_array($data_arr) && count($data_arr) > 0) ? "Update" : "Add";
                    if ($called_func == TRUE)
                    {
                        $params_arr["parID"] = $main_data[0]["iUserQueryId"];
                    }

                    $child_data = $save_data_arr = $file_data = array();
                    $uqi_user_query_id = $child_post["uqi_user_query_id"][$index];
                    $uqi_query_image = $child_post["uqi_query_image"][$index];
                    $uqi_added_at = $child_post["uqi_added_at"][$index];
                    $uqi_status = $child_post["uqi_status"][$index];

                    $child_data["iUserQueryId"] = $uqi_user_query_id;
                    $child_data["vQueryImage"] = $uqi_query_image;
                    $child_data["dtAddedAt"] = $this->filter->formatActionData($uqi_added_at, $form_config["uqi_added_at"]);
                    $child_data["eStatus"] = $uqi_status;

                    $child_data["iUserQueryId"] = $params_arr["parID"];
                    if ($mode == "Add")
                    {
                        $id = $res = $this->query_images_model->insert($child_data);
                        if (intval($id) > 0)
                        {
                            $data["iUserQueryImageId"] = $id;
                            $save_data_arr["iUserQueryImageId"] = $child_data["iUserQueryImageId"] = $id;
                            $msg = $this->general->processMessageLabel('ACTION_RECORD_ADDED_SUCCESSFULLY_C46_C46_C33');
                        }
                        else
                        {
                            throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_ADDING_RECORD_C46_C46_C33'));
                        }
                    }
                    elseif ($mode == "Update")
                    {
                        $res = $this->query_images_model->update($child_data, intval($id));
                        if (intval($res) > 0)
                        {
                            $data["iUserQueryImageId"] = $id;
                            $save_data_arr["iUserQueryImageId"] = $child_data["iUserQueryImageId"] = $id;
                            $msg = $this->general->processMessageLabel('ACTION_RECORD_SUCCESSFULLY_UPDATED_C46_C46_C33');
                        }
                        else
                        {
                            throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPDATING_OF_THIS_RECORD_C46_C46_C33'));
                        }
                    }

                    $success = 1;

                    $file_data["uqi_query_image"]["file_name"] = $uqi_query_image;
                    $file_data["uqi_query_image"]["old_file_name"] = $child_post["old_uqi_query_image"];
                    $file_data["uqi_query_image"]["unique_name"] = "uqi_query_image";
                    $file_data["uqi_query_image"]["primary_key"] = "iUserQueryImageId";
                    $file_keep = $form_config["uqi_query_image"]["file_keep"];
                    if ($file_keep != "" && $file_keep != "iUserQueryImageId")
                    {
                        $save_data_arr[$file_keep] = $child_data[$form_config[$file_keep]["field_name"]];
                    }
                    $this->listing->uploadFilesOnSaveForm($file_data, $form_config, $save_data_arr);
                    break;
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $msg = $e->getMessage();
        }
        $res_arr["success"] = $success;
        $res_arr["message"] = $msg;
        if ($called_func == TRUE)
        {
            $res_arr["id"] = $id;

            return $res_arr;
        }
        else
        {
            $enc_id = $this->general->getAdminEncodeURL($id);
            $res_arr["id"] = $enc_id;
            $res_arr["enc_id"] = $enc_id;

            echo json_encode($res_arr);
            $this->skip_template_view();
        }
    }
    /**
     * childDataDelete method is used to delete inline child module records (relation module records) from add or update form
     * @param array $config_arr config array for saving child module data records.
     * @param boolean $called_func for setting flag to differ function call of url call.
     * @return array $res_arr returns delete data record response
     */
    public function childDataDelete($config_arr = array(), $called_func = FALSE)
    {

        try
        {
            $params_arr = $this->params_arr;
            if ($called_func == TRUE)
            {
                $child_module = $config_arr["child_module"];
                $where_cond = $config_arr["extra_cond"];
            }
            else
            {
                $child_module = $params_arr["child_module"];
                $where_cond = $id = intval($params_arr["id"]);
            }
            switch ($child_module)
            {
                case "query_images":
                    $this->load->module("basic_appineers_master/query_images");
                    $this->load->model("basic_appineers_master/query_images_model");

                    $parent_join_arr = array(
                        "joins" => array(
                            array(
                                "table_name" => "user_query",
                                "table_alias" => "uq",
                                "field_name" => "iUserQueryId",
                                "rel_table_name" => "user_query_images",
                                "rel_table_alias" => "uqi",
                                "rel_field_name" => "iUserQueryId",
                                "join_type" => "left",
                            )
                        )
                    );
                    $res = $this->query_images_model->delete($where_cond, "Yes", $parent_join_arr);
                    if (!$res)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_DELETION_THIS_RECORD_C46_C46_C33'));
                    }
                    $msg = $this->general->processMessageLabel('ACTION_RECORD_C40S_C41_DELETED_SUCCESSFULLY_C46_C46_C33');
                    $success = 1;
                    break;
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $msg = $e->getMessage();
        }
        $res_arr["success"] = $success;
        $res_arr["message"] = $msg;
        if ($called_func == TRUE)
        {

            return $res_arr;
        }
        else
        {
            $this->response_arr = $res_arr;

            echo json_encode($res_arr);
            $this->skip_template_view();
        }
    }
}
