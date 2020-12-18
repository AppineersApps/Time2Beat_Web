<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Country Controller
 *
 * @category admin
 *
 * @package tools
 *
 * @subpackage controllers
 *
 * @module Country
 *
 * @class Country.php
 *
 * @path application\admin\tools\controllers\Country.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 24.09.2019
 */

class Country extends Cit_Controller
{
    /**
     * __construct method is used to set controller preferences while controller object initialization.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 24.09.2019
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('listing');
        $this->load->library('filter');
        $this->load->library('dropdown');
        $this->load->model('country_model');
        $this->folder_name = "tools";
        $this->module_name = "country";
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
            'top_detail' => "Yes",
            "multi_lingual" => "No",
            "print_layouts" => array(),
            "workflow_modes" => array(),
            "physical_data_remove" => "Yes",
            "list_record_callback" => "",
        );
        $this->dropdown_arr = array(
            "mc_status" => array(
                "type" => "enum",
                "default" => "Yes",
                "values" => array(
                    array(
                        'id' => 'Active',
                        'val' => $this->lang->line('COUNTRY_ACTIVE')
                    ),
                    array(
                        'id' => 'Inactive',
                        'val' => $this->lang->line('COUNTRY_INACTIVE')
                    )
                )
            )
        );
        $this->parMod = $this->params_arr["parMod"];
        $this->parID = $this->params_arr["parID"];
        $this->parRefer = array();
        $this->expRefer = array();

        $this->topRefer = array(
            "country" => array(
                "rel_source" => "iCountryId",
                "rel_target" => "iCountryId",
                "allow_editing" => "No",
                "extra_cond" => "",
                "type" => "dview",
            )
        );
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
                    "country_list",
                    "country_view",
                    "country_add",
                    "country_update",
                    "country_delete",
                    "country_export",
                    "country_print",
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
                list($list_access, $view_access, $add_access, $edit_access, $del_access, $expo_access, $print_access) = $this->filter->getModuleWiseAccess("country", $access_list, TRUE, TRUE);
            }
            if (!$list_access)
            {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }
            $enc_loc_module = $this->general->getMD5EncryptString("ListPrefer", "country");

            $status_array = array(
                'Active',
                'Inactive',
            );
            $status_label = array(
                'js_lang_label.COUNTRY_ACTIVE',
                'js_lang_label.COUNTRY_INACTIVE',
            );

            $list_config = $this->country_model->getListConfiguration();
            $this->processConfiguration($list_config, $add_access, $edit_access, TRUE);
            $this->general->trackModuleNavigation("Module", "List", "Viewed", $this->mod_enc_url["index"], "country");
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
                'default_filters' => $this->country_model->default_filters,
            );
            $this->smarty->assign($render_arr);
            if (!empty($render_arr['overwrite_view']))
            {
                $this->loadView($render_arr['overwrite_view']);
            }
            else
            {
                $this->loadView("country_index");
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
        $list_config = $this->country_model->getListConfiguration();
        $form_config = $this->country_model->getFormConfiguration();
        $extra_cond = $this->country_model->extra_cond;
        $groupby_cond = $this->country_model->groupby_cond;
        $having_cond = $this->country_model->having_cond;
        $orderby_cond = $this->country_model->orderby_cond;

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

        $data_recs = $this->country_model->getListingData($data_config);
        $data_recs['no_records_msg'] = $this->general->processMessageLabel('ACTION_NO_COUNTRY_DATA_FOUND_C46_C46_C33');

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
            $this->filter->checkAccessCapability("country_export");
        }
        else
        {
            $this->filter->getModuleWiseAccess("country", "Export", TRUE);
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
        $list_config = $this->country_model->getListConfiguration();
        $form_config = $this->country_model->getFormConfiguration();
        $table_name = $this->country_model->table_name;
        $table_alias = $this->country_modeltable_alias;
        $primary_key = $this->country_model->primary_key;
        $extra_cond = $this->country_model->extra_cond;
        $groupby_cond = $this->country_model->groupby_cond;
        $having_cond = $this->country_model->having_cond;
        $orderby_cond = $this->country_model->orderby_cond;
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

        $db_recs = $this->country_model->getExportData($export_config);
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
        $misc_info['heading'] = $this->lang->line('COUNTRY_COUNTRY');
        $misc_info['filename'] = "country_records_".count($db_recs);
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
                $pdf->setModule("country_model");
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
            $extra_cond = $this->country_model->extra_cond;
            if ($mode == "Update")
            {
                if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
                {
                    $access_list = array(
                        "country_list",
                        "country_view",
                        "country_update",
                        "country_delete",
                        "country_print",
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
                    list($list_access, $view_access, $edit_access, $del_access, $print_access) = $this->filter->getModuleWiseAccess("country", $access_list, TRUE, TRUE);
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
                        "country_list",
                        "country_add",
                    );
                    list($list_access, $add_access) = $this->filter->checkAccessCapability($access_list, TRUE);
                }
                else
                {
                    $access_list = array(
                        "List",
                        "Add",
                    );
                    list($list_access, $add_access) = $this->filter->getModuleWiseAccess("country", $access_list, TRUE, TRUE);
                }
                if (!$add_access)
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_ADD_THESE_DETAILS_C46_C46_C33'));
                }
            }

            $data = $orgi = $func = $elem = array();
            if ($mode == 'Update')
            {
                $ctrl_flow = $this->ci_local->read($this->general->getMD5EncryptString("FlowEdit", "country"), $this->session->userdata('iAdminId'));
                $data_arr = $this->country_model->getData(intval($id));
                $data = $orgi = $data_arr[0];
                if ((!is_array($data) || count($data) == 0) && $params_arr['rmPopup'] != "true")
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_RECORDS_WHICH_YOU_ARE_TRYING_TO_ACCESS_ARE_NOT_AVAILABLE_C46_C46_C33'));
                }

                $switch_arr = $switch_combo = $switch_cit = array();
                if (isset($this->params_arr['switchIDs']) && $this->params_arr['switchIDs'] != "")
                {
                    $switchIDs = explode(",", $this->params_arr['switchIDs']);
                    $switchIDs[] = $id;
                    $switchEsc = implode(",", $this->general->escape_str($switchIDs));
                    $switchCond = $this->db->protect($this->country_model->table_alias.".".$this->country_model->primary_key)." IN (".$switchEsc.")";
                    if ($extra_cond != "")
                    {
                        $extra_cond = ' AND '.$switchCond;
                    }
                    else
                    {
                        $extra_cond = $switchCond;
                    }
                }
                $switch_arr = $this->country_model->getSwitchTo($extra_cond, "records", $this->switchto_limit);
                $switch_combo = $this->filter->makeArrayDropDown($switch_arr);
                $switch_cit = array();
                $switch_tot = $this->country_model->getSwitchTo($extra_cond, "count");
                if ($this->switchto_limit > 0 && $switch_tot > $this->switchto_limit)
                {
                    $switch_cit['param'] = "true";
                    $switch_cit['url'] = $this->mod_enc_url['get_self_switch_to'];
                    if (!array_key_exists($id, $switch_combo))
                    {
                        $extra_cond = $this->db->protect($this->country_model->table_alias.".".$this->country_model->primary_key)." = ".$this->db->escape($id);
                        $switch_cur = $this->country_model->getSwitchTo($extra_cond, "records", 1);
                        if (is_array($switch_cur) && count($switch_cur) > 0)
                        {
                            $switch_arr[] = $switch_cur[0];
                            $switch_combo[$switch_cur[0]['id']] = $switch_cur[0]['val'];
                        }
                    }
                }
                $recName = $switch_combo[$id];
                $switch_enc_combo = $this->filter->getSwitchEncryptRec($switch_combo, $switch_arr);
                $this->dropdown->combo("array", "vSwitchPage", $switch_enc_combo, $enc_id, FALSE, "key_value", $switch_arr);
                $next_prev_records = $this->filter->getNextPrevRecords($id, $switch_arr);

                $this->general->trackModuleNavigation("Module", "Form", "Viewed", $this->mod_enc_url["add"], "country", $recName);
            }
            else
            {
                $recName = '';
                $ctrl_flow = $this->ci_local->read($this->general->getMD5EncryptString("FlowAdd", "country"), $this->session->userdata('iAdminId'));
                $this->general->trackModuleNavigation("Module", "Form", "Viewed", $this->mod_enc_url["add"], "country");
            }

            $opt_arr = $img_html = $auto_arr = $config_arr = array();

            $form_config = $this->country_model->getFormConfiguration($config_arr);
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
                            if (method_exists($this->country_model, $phpfunc))
                            {
                                $tmpdata = $this->country_model->$phpfunc($mode, $data[$key], $data, $id, $key, $key, $this->module_name);
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
                                if (method_exists($this->country_model, $fd_callback))
                                {
                                    $fd_status = $this->country_model->$fd_callback($mode, $data[$key], $data, $id, $key, $key, $this->module_name);
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
            if ($showDetail == "true" || $hideCtrl != "true")
            {

                $top_detail_view = array();
                if ($mode == "Update")
                {
                    $top_detail_view = $this->getDetailViewBlock($id, "current", "", $data);
                }
            }

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

                "top_detail_view" => $top_detail_view,
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
                        $this->loadView("country_add");
                    }
                    else
                    {
                        $this->loadView("country_add_view");
                    }
                }
                else
                {
                    $this->loadView("country_add");
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
                    $add_edit_access = $this->filter->checkAccessCapability("country_update", TRUE);
                }
                else
                {
                    $add_edit_access = $this->filter->checkAccessCapability("country_add", TRUE);
                }
            }
            else
            {
                $add_edit_access = $this->filter->getModuleWiseAccess("country", $mode, TRUE, TRUE);
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

            $form_config = $this->country_model->getFormConfiguration();
            $params_arr = $this->_request_params();
            $mc_country = $params_arr["mc_country"];
            $mc_country_code = $params_arr["mc_country_code"];
            $mc_country_code_i_s_o_3 = $params_arr["mc_country_code_i_s_o_3"];
            $mc_description = $params_arr["mc_description"];
            $mc_status = $params_arr["mc_status"];

            $unique_arr = array();
            $unique_arr["vCountryCode"] = $mc_country_code;

            $unique_exists = $this->country_model->checkRecordExists($this->country_model->unique_fields, $unique_arr, $id, $mode, $this->country_model->unique_type);
            if ($unique_exists)
            {
                $error_msg = $this->general->processMessageLabel('ACTION_RECORD_ALREADY_EXISTS_WITH_THESE_DETAILS_OF_COUNTRY_CODE_C46_C46_C33');
                if ($error_msg == "")
                {
                    $error_msg = "Record already exists with these details of Country Code";
                }
                throw new Exception($error_msg);
            }
            $data = $save_data_arr = $file_data = array();
            $data["vCountry"] = $mc_country;
            $data["vCountryCode"] = $mc_country_code;
            $data["vCountryCodeISO_3"] = $mc_country_code_i_s_o_3;
            $data["tDescription"] = $mc_description;
            $data["eStatus"] = $mc_status;

            $save_data_arr["mc_country"] = $data["vCountry"];
            $save_data_arr["mc_country_code"] = $data["vCountryCode"];
            $save_data_arr["mc_country_code_i_s_o_3"] = $data["vCountryCodeISO_3"];
            $save_data_arr["mc_description"] = $data["tDescription"];
            $save_data_arr["mc_status"] = $data["eStatus"];
            if ($mode == 'Add')
            {
                $id = $this->country_model->insert($data);
                if (intval($id) > 0)
                {
                    $save_data_arr["iCountryId"] = $data["iCountryId"] = $id;
                    $msg = $this->general->processMessageLabel('ACTION_RECORD_ADDED_SUCCESSFULLY_C46_C46_C33');
                }
                else
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_ADDING_RECORD_C46_C46_C33'));
                }
                $track_cond = $this->db->protect("mc.iCountryId")." = ".$this->db->escape($id);
                $switch_combo = $this->country_model->getSwitchTo($track_cond);
                $recName = $switch_combo[0]["val"];
                $this->general->trackModuleNavigation("Module", "Form", "Added", $this->mod_enc_url["add"], "country", $recName, "mode|".$this->general->getAdminEncodeURL("Update")."|id|".$this->general->getAdminEncodeURL($id));
            }
            elseif ($mode == 'Update')
            {
                $res = $this->country_model->update($data, intval($id));
                if (intval($res) > 0)
                {
                    $save_data_arr["iCountryId"] = $data["iCountryId"] = $id;
                    $msg = $this->general->processMessageLabel('ACTION_RECORD_SUCCESSFULLY_UPDATED_C46_C46_C33');
                }
                else
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPDATING_OF_THIS_RECORD_C46_C46_C33'));
                }
                $track_cond = $this->db->protect("mc.iCountryId")." = ".$this->db->escape($id);
                $switch_combo = $this->country_model->getSwitchTo($track_cond);
                $recName = $switch_combo[0]["val"];
                $this->general->trackModuleNavigation("Module", "Form", "Modified", $this->mod_enc_url["add"], "country", $recName, "mode|".$this->general->getAdminEncodeURL("Update")."|id|".$this->general->getAdminEncodeURL($id));
            }
            $ret_arr['id'] = $id;
            $ret_arr['mode'] = $mode;
            $ret_arr['message'] = $msg;
            $ret_arr['success'] = 1;

            $params_arr = $this->_request_params();
        }
        catch(Exception $e)
        {
            $ret_arr["message"] = $e->getMessage();
            $ret_arr["success"] = 0;
        }
        $ret_arr['mod_enc_url']['add'] = $this->mod_enc_url['add'];
        $ret_arr['mod_enc_url']['index'] = $this->mod_enc_url['index'];
        $ret_arr['red_type'] = 'List';
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
            $config_arr['list_config'] = $this->country_model->getListConfiguration();
            $config_arr['form_config'] = $this->country_model->getFormConfiguration();
            $config_arr['table_name'] = $this->country_model->table_name;
            $config_arr['table_alias'] = $this->country_model->table_alias;
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
            $primary_field = $this->country_model->table_alias.".".$this->country_model->primary_key;
        }
        else
        {
            $primary_field = $this->country_model->primary_key;
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
                        $del_access = $this->filter->checkAccessCapability("country_delete", TRUE);
                    }
                    else
                    {
                        $del_access = $this->filter->getModuleWiseAccess("country", "Delete", TRUE, TRUE);
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

                    $success = $this->country_model->delete($extra_cond, $search_alias, $search_join);
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
                        $edit_access = $this->filter->checkAccessCapability("country_update", TRUE);
                    }
                    else
                    {
                        $edit_access = $this->filter->getModuleWiseAccess("country", "Update", TRUE, TRUE);
                    }
                    if (!$edit_access)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                    }
                    $post_name = $params_arr['name'];
                    $post_val = is_array($params_arr['value']) ? implode(",", $params_arr['value']) : $params_arr['value'];

                    $list_config = $this->country_model->getListConfiguration($post_name);
                    $form_config = $this->country_model->getFormConfiguration($list_config['source_field']);
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

                    $unique_arr = array();

                    $unique_arr[$field_name] = $post_val;
                    if (in_array($field_name, $this->country_model->unique_fields))
                    {
                        $unique_exists = $this->country_model->checkRecordExists($this->country_model->unique_fields, $unique_arr, $primary_ids, "Update", $this->country_model->unique_type);
                        if ($unique_exists)
                        {
                            $error_msg = $this->general->processMessageLabel('ACTION_RECORD_ALREADY_EXISTS_WITH_THESE_DETAILS_OF_COUNTRY_CODE_C46_C46_C33');
                            if ($error_msg == "")
                            {
                                $error_msg = "Record already exists with these details of Country Code";
                            }
                            throw new Exception($error_msg);
                        }
                    }

                    $data_arr[$field_name] = $post_val;
                    $success = $this->country_model->update($data_arr, intval($primary_ids));
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
                        $edit_access = $this->filter->checkAccessCapability("country_update", TRUE);
                    }
                    else
                    {
                        $edit_access = $this->filter->getModuleWiseAccess("country", "Update", TRUE, TRUE);
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
                        $field_name = $this->country_model->table_alias.".eStatus";
                    }
                    else
                    {
                        $field_name = $status_field;
                    }
                    $data_arr[$field_name] = $params_arr['status'];
                    $success = $this->country_model->update($data_arr, $extra_cond, $search_alias, $search_join);
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
                    $combo_config['main_table'] = $this->country_model->table_name;
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
                    if (method_exists($this->country_model, $phpfunc))
                    {
                        $data_arr = $this->country_model->$phpfunc($data[$name], $mode, $id, $data, $parent_src, $this->term);
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

        $switchto_fields = $this->country_model->switchto_fields;
        $extra_cond = $this->country_model->extra_cond;

        $concat_fields = $this->db->concat_cast($switchto_fields);
        $search_cond = "(LOWER(".$concat_fields.") LIKE '".$this->db->escape_like_str($term)."%' OR LOWER(".$concat_fields.") LIKE '% ".$this->db->escape_like_str($term)."%')";
        $extra_cond = ($extra_cond == "") ? $search_cond : $extra_cond." AND ".$search_cond;

        $switch_arr = $this->country_model->getSwitchTo($extra_cond);
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
        $config_arr = $this->country_model->getListConfiguration($alias_name);
        $source_field = $config_arr['source_field'];
        $combo_config = $this->dropdown_arr[$source_field];
        $data_arr = array();
        if ($mode == "Update")
        {
            $data_arr = $this->country_model->getData(intval($id));
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
     * getSubgridBlock method is used to get expandable subgrid or detail view for grid listing
     */
    public function getSubgridBlock()
    {
        $params_arr = $this->params_arr;
        $id = $params_arr['SGID'];
        $SGridID = $params_arr['SGridID'];
        $SGRender = $params_arr['SGRender'];
        $parse_html = '';
        try
        {
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
            {
                $list_access = $this->filter->checkAccessCapability("country_list", TRUE);
            }
            else
            {
                $list_access = $this->filter->getModuleWiseAccess("country", "List", TRUE, TRUE);
            }
            if (!$list_access)
            {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }
            $fields = $this->country_model->table_alias.".iCountryId";
            $data_rec = $this->country_model->getData(intval($id), $fields);
            $this->load->module('tools/state');
            $grid_config_arr['subgrid_id'] = $SGridID;
            $grid_config_arr['expand_mod'] = "country";
            $grid_config_arr['expand_key'] = "country";
            $grid_config_arr['exp_id'] = $data_rec[0]["iCountryId"];
            $parse_html .= $this->state->expandSubgridList($grid_config_arr, $SGRender);
        }
        catch(Exception $e)
        {
            $parse_html = $e->getMessage();
        }
        echo $parse_html;
        $this->skip_template_view();
    }
    /**
     * getDetailViewBlock method is used to get top detail view for grid listing or add/update form
     * @param string $id id is to get single data record for details.
     * @param string $type type is used to differ parent module top detail (if any) or current module top detail.
     * @param string $parMod parMod is used to get parent module top detail view.
     * @return array $ret_arr returns top detail view contents
     */
    public function getDetailViewBlock($id = '', $type = "current", $parMod = '', $parRec = array())
    {
        $parse_html = '';
        try
        {
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
            {
                $view_access = $this->filter->checkAccessCapability("country_view", TRUE);
            }
            else
            {
                $view_access = $this->filter->getModuleWiseAccess("country", "View", TRUE, TRUE);
            }
            if (!$view_access)
            {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }
            if ($type == "parent")
            {
                switch ($parMod)
                {
                    default:
                        throw new Exception("Not found.");
                        break;
                }
            }
            else
            {
                $fields = $this->country_model->table_alias.".iCountryId";
                $data_rec = $this->country_model->getData(intval($id), $fields);
                $this->load->module('tools/country');
                $detail_config_arr['detail_mod'] = "country";
                $detail_config_arr['detail_key'] = "country";
                $detail_config_arr['detail_id'] = $data_rec[0]["iCountryId"];
                $detail_config_arr['parent_rec'] = $parRec;
                $render_arr = $this->country->topDetailView($detail_config_arr);
                $render_arr['_toggle_flag'] = $_COOKIE[$this->general->getMD5EncryptString("DetailView", "country")];

                $this->parser->addTemplateLocation(APPPATH.'admin/tools/views/');
                $parse_html = $this->parser->parse("country_topview", $render_arr, TRUE);
            }
            $toggle_flag = $_COOKIE[$this->general->getMD5EncryptString("DetailView", "country")];
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $toggle_flag = 0;
            $parse_html = $e->getMessage();
        }
        $ret_arr['exists'] = $success;
        $ret_arr['flag'] = $toggle_flag;
        $ret_arr['html'] = $parse_html;
        return $ret_arr;
    }

    /**
     * topDetailView method is used to get top detail view render array
     * @param array $config_arr config array for top detail view.
     * @return array $render_arr returns top detail view smarty render array
     */
    public function topDetailView($config_arr = array())
    {
        $extra_qstr = $extra_hstr = '';
        $detail_layout_view_id = "detail_view_block";
        $detail_id = $config_arr['detail_id'];
        $detail_key = $config_arr['detail_key'];
        $detail_mod = $config_arr['detail_mod'];
        $top_refer = $this->topRefer[$detail_key];
        $detail_field = $top_refer['rel_source'];
        $parent_rec = $top_refer['parent_rec'];
        if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
        {
            $edit_access = $this->filter->checkAccessCapability("country_update", TRUE);
        }
        else
        {
            $edit_access = $this->filter->getModuleWiseAccess("country", "Update", TRUE, TRUE);
        }
        $list_config = $this->country_model->getListConfiguration();
        $form_config = $this->country_model->getFormConfiguration();

        $top_extra_cond = $this->db->protect($this->country_model->table_alias.".".$detail_field)." = ".$this->db->escape($detail_id);
        if ($top_refer['extra_cond'] != "")
        {
            $top_extra_cond .= " AND ".$top_refer['extra_cond'];
        }
        $rec_arr = $this->country_model->getData($top_extra_cond, '', '', '', '', 'Yes', '', TRUE);
        $data = $data_rec = $rec_arr[0];
        $detail_primary_id = $data[$this->country_model->primary_key];
        foreach ((array) $list_config as $key => $val)
        {
            $name = $val['name'];
            $source_field = $val['source_field'];
            $source_config = $form_config[$source_field];
            $combo_config = $this->dropdown_arr[$source_field];
            $temp_data = $final_data = $data[$key];
            $alias_id = "dv_".$name;
            $view_html = '';
            if (!$edit_access || $top_refer["allow_editing"] == "No" || in_array($val['type'], array("code_markup_field", "google_maps", "wysiwyg")))
            {
                $list_config[$key]["viewedit"] = "No";
            }
            if ($val['file_upload'] == "Yes")
            {
                $source_config['htmlID'] = $alias_id;
                if ($list_config[$key]["viewedit"] == "Yes")
                {
                    $temp_html = $this->listing->parseFormFile($temp_data, $detail_primary_id, $data, $source_config, $this->module_config, "TDetail");
                }
                else
                {
                    $temp_html = $this->listing->parseListingFile($temp_data, $detail_primary_id, $data, $val, $this->module_config, "TDetail");
                }
            }
            else
            {
                if ($val['encrypt'] == "Yes")
                {
                    $final_data = $this->general->decryptDataMethod($temp_data, $val["enctype"]);
                }
                $temp_html = $final_data = $this->listing->formatListingData($final_data, $detail_primary_id, $data, $val, $source_config, $combo_config, "TDetail");
            }
            if ($val['type'] == "rating_master")
            {
                $temp_html = '<span id="rscore_'.$alias_id.'">'.$temp_html.'</span>';
                if ($list_config[$key]["viewedit"] == "Yes")
                {
                    $view_html .= '<div>'.$temp_html.'
                                        <a class="rating-edit-icon" href="javascript://" id="'.$alias_id.'">
                                            <span class="icon16 icomoon-icon-pencil-5"><b>Edit</b></span>
                                        </a>
                                    </div>';
                }
                else
                {
                    $view_html .= $temp_html;
                }
            }
            elseif ($val['file_upload'] == "Yes")
            {
                if ($list_config[$key]["viewedit"] == "Yes")
                {
                    $view_html .= '<div>'.$temp_html.'
                                        <a class="file-edit-icon" href="javascript://" id="'.$alias_id.'">
                                            <span class="icon16 icomoon-icon-pencil-5"><b>Edit</b></span>
                                        </a>
                                    </div>';
                }
                else
                {
                    $view_html .= $temp_html;
                }
            }
            elseif ($list_config[$key]["viewedit"] == "Yes")
            {
                $temp_html = $this->general->processQuotes($temp_html);
                $view_html .= '<div class="'.$val['class'].'">
                                    <a href="javascript://" id="'.$alias_id.'" data-value="'.$temp_html.'" aria-prev-value="'.$temp_html.'">'.$final_data.'</a>
                                </div>';
            }
            else
            {
                $view_html .= $temp_html;
            }
            $data[$name] = $view_html;
            if ($list_config[$key]["viewedit"] == "Yes")
            {
                $list_config[$key]["dbval"] = $this->general->processQuotes($final_data);
                $list_config[$key]["value"] = $this->general->processQuotes($final_data);
            }
            else
            {
                $list_config[$key]["dbval"] = '';
                $list_config[$key]["value"] = '';
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

        $enc_parMod = $this->general->getAdminEncodeURL($detail_mod);
        $enc_parID = $this->general->getAdminEncodeURL($detail_id);

        $extra_qstr .= "&parMod=".$enc_parMod."&parID=".$enc_parID."&parType=top";
        $extra_hstr .= "|parMod|".$enc_parMod."|parID|".$enc_parID."|parType|top";
        $extra_qstr .= $this->general->getRequestURLParams();
        $extra_hstr .= $this->general->getRequestHASHParams();
        $enc_detail_primary_id = $this->general->getAdminEncodeURL($detail_primary_id);
        $render_arr = array(
            'data' => $data,
            'data_rec' => $data_rec,
            'rec_arr' => $rec_arr,
            'parent_rec' => $parent_rec,
            'detail_primary_id' => $detail_primary_id,
            'enc_detail_primary_id' => $enc_detail_primary_id,
            'detail_layout_view_id' => $detail_layout_view_id,
            'top_module_name' => $detail_mod,
            'top_par_id' => $detail_id,
            'top_par_field' => $detail_field,
            'list_config' => $list_config,
            'count_arr' => $this->count_arr,
            'edit_access' => $edit_access,
            'folder_name' => $this->folder_name,
            'module_name' => $this->module_name,
            'mod_enc_url' => $this->mod_enc_url,
            'mod_enc_mode' => $this->mod_enc_mode,
            'extra_qstr' => $extra_qstr,
            'extra_hstr' => $extra_hstr,
        );
        return $render_arr;
    }
}
