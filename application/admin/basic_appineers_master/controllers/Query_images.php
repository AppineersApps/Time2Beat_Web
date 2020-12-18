<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Query Images Controller
 *
 * @category admin
 *
 * @package basic_appineers_master
 *
 * @subpackage controllers
 *
 * @module Query Images
 *
 * @class Query_images.php
 *
 * @path application\admin\basic_appineers_master\controllers\Query_images.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 24.09.2019
 */

class Query_images extends Cit_Controller
{
    /**
     * __construct method is used to set controller preferences while controller object initialization.
     * @created priyanka chillakuru | 19.09.2019
     * @modified priyanka chillakuru | 24.09.2019
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('listing');
        $this->load->library('filter');
        $this->load->library('dropdown');
        $this->load->model('query_images_model');
        $this->folder_name = "basic_appineers_master";
        $this->module_name = "query_images";
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
            "uqi_user_query_id" => array(
                "type" => "phpfn",
                "function" => "",
                "default" => "Yes",
            ),
            "uqi_status" => array(
                "type" => "enum",
                "default" => "Yes",
                "values" => array(
                    array(
                        'id' => 'Active',
                        'val' => $this->lang->line('QUERY_IMAGES_ACTIVE')
                    ),
                    array(
                        'id' => 'Inactive',
                        'val' => $this->lang->line('QUERY_IMAGES_INACTIVE')
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
                    "query_images_list",
                    "query_images_view",
                    "query_images_add",
                    "query_images_update",
                    "query_images_delete",
                    "query_images_export",
                    "query_images_print",
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
                list($list_access, $view_access, $add_access, $edit_access, $del_access, $expo_access, $print_access) = $this->filter->getModuleWiseAccess("query_images", $access_list, TRUE, TRUE);
            }
            if (!$list_access)
            {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }
            $enc_loc_module = $this->general->getMD5EncryptString("ListPrefer", "query_images");

            $status_array = array(
                'Active',
                'Inactive',
            );
            $status_label = array(
                'js_lang_label.QUERY_IMAGES_ACTIVE',
                'js_lang_label.QUERY_IMAGES_INACTIVE',
            );

            $list_config = $this->query_images_model->getListConfiguration();
            $this->processConfiguration($list_config, $add_access, $edit_access, TRUE);
            $this->general->trackModuleNavigation("Module", "List", "Viewed", $this->mod_enc_url["index"], "query_images");
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
                'default_filters' => $this->query_images_model->default_filters,
            );
            $this->smarty->assign($render_arr);
            if (!empty($render_arr['overwrite_view']))
            {
                $this->loadView($render_arr['overwrite_view']);
            }
            else
            {
                $this->loadView("query_images_index");
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
        $list_config = $this->query_images_model->getListConfiguration();
        $form_config = $this->query_images_model->getFormConfiguration();
        $extra_cond = $this->query_images_model->extra_cond;
        $groupby_cond = $this->query_images_model->groupby_cond;
        $having_cond = $this->query_images_model->having_cond;
        $orderby_cond = $this->query_images_model->orderby_cond;

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

        $data_recs = $this->query_images_model->getListingData($data_config);
        $data_recs['no_records_msg'] = $this->general->processMessageLabel('ACTION_NO_QUERY_IMAGES_DATA_FOUND_C46_C46_C33');

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
            $this->filter->checkAccessCapability("query_images_export");
        }
        else
        {
            $this->filter->getModuleWiseAccess("query_images", "Export", TRUE);
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
        $list_config = $this->query_images_model->getListConfiguration();
        $form_config = $this->query_images_model->getFormConfiguration();
        $table_name = $this->query_images_model->table_name;
        $table_alias = $this->query_images_modeltable_alias;
        $primary_key = $this->query_images_model->primary_key;
        $extra_cond = $this->query_images_model->extra_cond;
        $groupby_cond = $this->query_images_model->groupby_cond;
        $having_cond = $this->query_images_model->having_cond;
        $orderby_cond = $this->query_images_model->orderby_cond;
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

        $db_recs = $this->query_images_model->getExportData($export_config);
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
        $misc_info['heading'] = $this->lang->line('QUERY_IMAGES_QUERY_IMAGES');
        $misc_info['filename'] = "query_images_records_".count($db_recs);
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
                $pdf->setModule("query_images_model");
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
            $extra_cond = $this->query_images_model->extra_cond;
            if ($mode == "Update")
            {
                if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
                {
                    $access_list = array(
                        "query_images_list",
                        "query_images_view",
                        "query_images_update",
                        "query_images_delete",
                        "query_images_print",
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
                    list($list_access, $view_access, $edit_access, $del_access, $print_access) = $this->filter->getModuleWiseAccess("query_images", $access_list, TRUE, TRUE);
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
                        "query_images_list",
                        "query_images_add",
                    );
                    list($list_access, $add_access) = $this->filter->checkAccessCapability($access_list, TRUE);
                }
                else
                {
                    $access_list = array(
                        "List",
                        "Add",
                    );
                    list($list_access, $add_access) = $this->filter->getModuleWiseAccess("query_images", $access_list, TRUE, TRUE);
                }
                if (!$add_access)
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_ADD_THESE_DETAILS_C46_C46_C33'));
                }
            }

            $data = $orgi = $func = $elem = array();
            if ($mode == 'Update')
            {
                $ctrl_flow = $this->ci_local->read($this->general->getMD5EncryptString("FlowEdit", "query_images"), $this->session->userdata('iAdminId'));
                $data_arr = $this->query_images_model->getData(intval($id));
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

                $this->general->trackModuleNavigation("Module", "Form", "Viewed", $this->mod_enc_url["add"], "query_images", $recName);
            }
            else
            {
                $recName = '';
                $ctrl_flow = $this->ci_local->read($this->general->getMD5EncryptString("FlowAdd", "query_images"), $this->session->userdata('iAdminId'));
                $this->general->trackModuleNavigation("Module", "Form", "Viewed", $this->mod_enc_url["add"], "query_images");
            }

            $opt_arr = $img_html = $auto_arr = $config_arr = array();

            $form_config = $this->query_images_model->getFormConfiguration($config_arr);
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
                            if (method_exists($this->query_images_model, $phpfunc))
                            {
                                $tmpdata = $this->query_images_model->$phpfunc($mode, $data[$key], $data, $id, $key, $key, $this->module_name);
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
                                if (method_exists($this->query_images_model, $fd_callback))
                                {
                                    $fd_status = $this->query_images_model->$fd_callback($mode, $data[$key], $data, $id, $key, $key, $this->module_name);
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
                    if ($val['file_upload'] == 'Yes')
                    {
                        $del_file = ($edit_access && $viewMode != TRUE && $func[$key] != 2) ? TRUE : FALSE;
                        $val['htmlID'] = $val['name'];
                        $img_html[$key] = $this->listing->parseFormFile($data[$key], $id, $data, $val, $this->module_config, "Form", $del_file);
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
            $wf_mode_arr = $this->module_config["workflow_modes"];
            if (is_array($wf_mode_arr) && in_array($mode, $wf_mode_arr))
            {
                $controls_allow = FALSE;
            }
            /** access controls >>> **/
            $render_arr = array(

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
                        $this->loadView("query_images_add");
                    }
                    else
                    {
                        $this->loadView("query_images_add_view");
                    }
                }
                else
                {
                    $this->loadView("query_images_add");
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
                    $add_edit_access = $this->filter->checkAccessCapability("query_images_update", TRUE);
                }
                else
                {
                    $add_edit_access = $this->filter->checkAccessCapability("query_images_add", TRUE);
                }
            }
            else
            {
                $add_edit_access = $this->filter->getModuleWiseAccess("query_images", $mode, TRUE, TRUE);
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

            $form_config = $this->query_images_model->getFormConfiguration();
            $params_arr = $this->_request_params();
            $uqi_user_query_id = $params_arr["uqi_user_query_id"];
            $uqi_query_image = $params_arr["uqi_query_image"];
            $uqi_added_at = $params_arr["uqi_added_at"];
            $uqi_status = $params_arr["uqi_status"];

            $data = $save_data_arr = $file_data = array();
            $data["iUserQueryId"] = $uqi_user_query_id;
            $data["vQueryImage"] = $uqi_query_image;
            $data["dtAddedAt"] = $this->filter->formatActionData($uqi_added_at, $form_config["uqi_added_at"]);
            $data["eStatus"] = $uqi_status;

            $save_data_arr["uqi_user_query_id"] = $data["iUserQueryId"];
            $save_data_arr["uqi_query_image"] = $data["vQueryImage"];
            $save_data_arr["uqi_added_at"] = $data["dtAddedAt"];
            $save_data_arr["uqi_status"] = $data["eStatus"];
            if ($mode == 'Add')
            {
                $id = $this->query_images_model->insert($data);
                if (intval($id) > 0)
                {
                    $save_data_arr["iUserQueryImageId"] = $data["iUserQueryImageId"] = $id;
                    $msg = $this->general->processMessageLabel('ACTION_RECORD_ADDED_SUCCESSFULLY_C46_C46_C33');
                }
                else
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_ADDING_RECORD_C46_C46_C33'));
                }
                $track_cond = $this->db->protect("uqi.iUserQueryImageId")." = ".$this->db->escape($id);
                $switch_combo = $this->query_images_model->getSwitchTo($track_cond);
                $recName = $switch_combo[0]["val"];
                $this->general->trackModuleNavigation("Module", "Form", "Added", $this->mod_enc_url["add"], "query_images", $recName, "mode|".$this->general->getAdminEncodeURL("Update")."|id|".$this->general->getAdminEncodeURL($id));
            }
            elseif ($mode == 'Update')
            {
                $res = $this->query_images_model->update($data, intval($id));
                if (intval($res) > 0)
                {
                    $save_data_arr["iUserQueryImageId"] = $data["iUserQueryImageId"] = $id;
                    $msg = $this->general->processMessageLabel('ACTION_RECORD_SUCCESSFULLY_UPDATED_C46_C46_C33');
                }
                else
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPDATING_OF_THIS_RECORD_C46_C46_C33'));
                }
                $track_cond = $this->db->protect("uqi.iUserQueryImageId")." = ".$this->db->escape($id);
                $switch_combo = $this->query_images_model->getSwitchTo($track_cond);
                $recName = $switch_combo[0]["val"];
                $this->general->trackModuleNavigation("Module", "Form", "Modified", $this->mod_enc_url["add"], "query_images", $recName, "mode|".$this->general->getAdminEncodeURL("Update")."|id|".$this->general->getAdminEncodeURL($id));
            }
            $ret_arr['id'] = $id;
            $ret_arr['mode'] = $mode;
            $ret_arr['message'] = $msg;
            $ret_arr['success'] = 1;

            $file_data["uqi_query_image"]["file_name"] = $uqi_query_image;
            $file_data["uqi_query_image"]["old_file_name"] = $params_arr["old_uqi_query_image"];
            $file_data["uqi_query_image"]["unique_name"] = "uqi_query_image";
            $file_data["uqi_query_image"]["primary_key"] = "iUserQueryImageId";

            $this->listing->uploadFilesOnSaveForm($file_data, $form_config, $save_data_arr);

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
            $config_arr['list_config'] = $this->query_images_model->getListConfiguration();
            $config_arr['form_config'] = $this->query_images_model->getFormConfiguration();
            $config_arr['table_name'] = $this->query_images_model->table_name;
            $config_arr['table_alias'] = $this->query_images_model->table_alias;
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
            $primary_field = $this->query_images_model->table_alias.".".$this->query_images_model->primary_key;
        }
        else
        {
            $primary_field = $this->query_images_model->primary_key;
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
                        $del_access = $this->filter->checkAccessCapability("query_images_delete", TRUE);
                    }
                    else
                    {
                        $del_access = $this->filter->getModuleWiseAccess("query_images", "Delete", TRUE, TRUE);
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
                    if (is_array($primary_ids))
                    {
                        $main_cond = $this->db->protect("uqi.iUserQueryImageId")." IN ('".implode("','", $primary_ids)."')";
                    }
                    else
                    {
                        $main_cond = $this->db->protect("uqi.iUserQueryImageId")." = ".$this->db->escape($primary_ids);
                    }
                    $main_data = $this->query_images_model->getData($main_cond, array('uqi.vQueryImage', 'uqi.iUserQueryId'));
                    $success = $this->query_images_model->delete($extra_cond, $search_alias, $search_join);
                    if (!$success)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_DELETION_THIS_RECORD_C46_C46_C33'));
                    }
                    $message = $this->general->processMessageLabel('ACTION_RECORD_C40S_C41_DELETED_SUCCESSFULLY_C46_C46_C33');

                    $form_config = $this->query_images_model->getFormConfiguration();
                    $this->general->deleteMediaFiles($form_config, $main_data, $this->module_config["physical_data_remove"]);
                    break;
                case 'edit':
                    $mode = "Update";
                    if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
                    {
                        $edit_access = $this->filter->checkAccessCapability("query_images_update", TRUE);
                    }
                    else
                    {
                        $edit_access = $this->filter->getModuleWiseAccess("query_images", "Update", TRUE, TRUE);
                    }
                    if (!$edit_access)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                    }
                    $post_name = $params_arr['name'];
                    $post_val = is_array($params_arr['value']) ? implode(",", $params_arr['value']) : $params_arr['value'];

                    $list_config = $this->query_images_model->getListConfiguration($post_name);
                    $form_config = $this->query_images_model->getFormConfiguration($list_config['source_field']);
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

                    $db_rec_arr = $this->query_images_model->getData(intval($primary_ids));

                    $data_arr[$field_name] = $post_val;
                    $success = $this->query_images_model->update($data_arr, intval($primary_ids));
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
                        $edit_access = $this->filter->checkAccessCapability("query_images_update", TRUE);
                    }
                    else
                    {
                        $edit_access = $this->filter->getModuleWiseAccess("query_images", "Update", TRUE, TRUE);
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
                        $field_name = $this->query_images_model->table_alias.".eStatus";
                    }
                    else
                    {
                        $field_name = $status_field;
                    }
                    $data_arr[$field_name] = $params_arr['status'];
                    $success = $this->query_images_model->update($data_arr, $extra_cond, $search_alias, $search_join);
                    if (!$success)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_MODIFYING_THESE_RECORDS_C46_C46_C33'));
                    }
                    $message = $this->general->processMessageLabel('ACTION_RECORD_C40S_C41_MODIFIED_SUCCESSFULLY_C46_C46_C33');
                    break;
            }
            $ret_arr['success'] = "true";
            $ret_arr['message'] = $message;
            if ($mode == "Update")
            {
                $save_data_arr = $db_rec_arr[0];
                $save_data_arr[$unique_name] = $post_val;
                if ($form_config["type"] == "file")
                {
                    $file_data[$unique_name]["file_name"] = $post_val;
                    $file_data[$unique_name]["old_file_name"] = $params_arr["old_file"];
                    $file_data[$unique_name]["unique_name"] = $unique_name;
                    $file_data[$unique_name]["primary_key"] = "iUserQueryImageId";
                    $this->listing->uploadFilesOnSaveForm($file_data, array($unique_name => $form_config), $save_data_arr);
                }
            }
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
                    $combo_config['main_table'] = $this->query_images_model->table_name;
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
                    if (method_exists($this->query_images_model, $phpfunc))
                    {
                        $data_arr = $this->query_images_model->$phpfunc($data[$name], $mode, $id, $data, $parent_src, $this->term);
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

        $switchto_fields = $this->query_images_model->switchto_fields;
        $extra_cond = $this->query_images_model->extra_cond;

        $concat_fields = $this->db->concat_cast($switchto_fields);
        $search_cond = "(LOWER(".$concat_fields.") LIKE '".$this->db->escape_like_str($term)."%' OR LOWER(".$concat_fields.") LIKE '% ".$this->db->escape_like_str($term)."%')";
        $extra_cond = ($extra_cond == "") ? $search_cond : $extra_cond." AND ".$search_cond;

        $switch_arr = $this->query_images_model->getSwitchTo($extra_cond);
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
        $config_arr = $this->query_images_model->getListConfiguration($alias_name);
        $source_field = $config_arr['source_field'];
        $combo_config = $this->dropdown_arr[$source_field];
        $data_arr = array();
        if ($mode == "Update")
        {
            $data_arr = $this->query_images_model->getData(intval($id));
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
     * uploadFormFile method is used to upload files or images through add or update form
     */
    public function uploadFormFile()
    {
        $this->load->library('upload');
        $params_arr = $this->params_arr;
        $unique_name = $params_arr['unique_name'];
        $id = $params_arr['id'];
        $old_file = $params_arr['oldFile'];
        $type = $params_arr['type'];
        $upload_files = $_FILES['Filedata'];
        list($file_name, $extension) = $this->general->get_file_attributes($upload_files['name']);
        $this->general->createUploadFolderIfNotExists('__temp');
        $config_arr = $this->query_images_model->getFormConfiguration($unique_name);

        $temp_folder_path = $this->config->item('admin_upload_temp_path');
        $temp_folder_url = $this->config->item('admin_upload_temp_url');
        try
        {
            if ($type == 'webcam')
            {
                $img_pic = $params_arr['newFile'];
                $res = $this->general->imageupload_base64($temp_folder_path, $img_pic);
                if ($res[0] == FALSE)
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPLOADING_C46_C46_C33'));
                }
                $file_name = $res[0];
            }
            else
            {
                $file_size = ($config_arr['file_size']) ? $config_arr['file_size'] : $this->config->item('ADMIN_MAX_UPLOAD_FILE_SIZE');
                $file_format = ($config_arr['file_format']) ? $config_arr['file_format'] : $this->config->item('IMAGE_EXTENSION_ARR');
                if ($config_arr['file_folder'] == "")
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_PLEASE_SPECIFY_THE_UPLOAD_FOLDER_NAME_C46_C46_C33'));
                }
                if ($upload_files['name'] == "")
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_UPLOAD_FILE_NOT_FOUND_C46_C46_C33'));
                }
                if (!$this->general->validateFileFormat($file_format, $file_name))
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_FILE_TYPE_IS_NOT_ACCEPTABLE'));
                }
                if (!$this->general->validateFileSize($file_size, $upload_files['size']))
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_FILE_SIZE_NOT_A_VALID_ONE_C46_C46_C33'));
                }
                $upload_config = array(
                    'upload_path' => $temp_folder_path,
                    'allowed_types' => '*',
                    'max_size' => $file_size,
                    'file_name' => $file_name,
                    'remove_space' => TRUE,
                    'overwrite' => FALSE,
                );
                $this->upload->initialize($upload_config);
                if (!$this->upload->do_upload('Filedata'))
                {
                    $upload_error = $this->upload->display_errors('', '');
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPLOADING_C46_C46_C33'));
                }
                $file_info = $this->upload->data();
                $file_name = $file_info['file_name'];
            }
            if (!$file_name)
            {
                throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPLOADING_C46_C46_C33'));
            }
            $image_valid_ext = explode('.', $file_name);
            $ret_arr['fileURL'] = $temp_folder_url.$file_name;
            if (in_array(strtolower(end($image_valid_ext)), $this->config->item('IMAGE_EXTENSION_ARR')))
            {
                $ret_arr['width'] = ($config_arr['file_width']) ? $config_arr['file_width'] : $this->config->item('ADMIN_DEFAULT_IMAGE_WIDTH');
                $ret_arr['height'] = ($config_arr['file_height']) ? $config_arr['file_height'] : $this->config->item('ADMIN_DEFAULT_IMAGE_HEIGHT');
                $ret_arr['imgURL'] = $this->general->resize_image($temp_folder_url.$file_name, $ret_arr['width'], $ret_arr['height']);
                $ret_arr['resized'] = 1;
                $icon_class = 'fa-file-image-o';
            }
            else
            {
                $icon_class = 'fa-file-text-o';
                $ret_arr['resized'] = 0;
            }
            $icon_file = $file_name;
            if (function_exists("get_file_icon_class"))
            {
                $icon_class = get_file_icon_class($file_name);
                $icon_file = format_uploaded_file($file_name);
            }
            $ret_arr['iconclass'] = $icon_class;
            $ret_arr['iconfile'] = $icon_file;
            $ret_arr['filename'] = $icon_file;
            if (is_file($temp_folder_path.$old_file) && $old_file != '')
            {
                unlink($temp_folder_path.$old_file);
            }

            $ret_arr['success'] = 1;
            $ret_arr['message'] = $this->general->processMessageLabel('ACTION_FILE_UPLOADED_SUCCESSFULLY_C46_C46_C33');
            $ret_arr['uploadfile'] = $file_name;
            $ret_arr['oldfile'] = $file_name;
        }
        catch(Exception $e)
        {
            $ret_arr['success'] = 0;
            $ret_arr['message'] = $e->getMessage();
        }
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }
    /**
     * downloadFormFile method is used to download add or update form files
     */
    public function downloadFormFile()
    {
        $params_arr = $this->params_arr;
        $unique_name = $params_arr['unique_name'];
        $folder = $params_arr['folder'];
        $id = $params_arr['id'];
        $config_arr = $this->query_images_model->getFormConfiguration($unique_name);
        try
        {
            if (!is_array($config_arr) || count($config_arr) == 0)
            {
                throw new Exception($this->general->processMessageLabel('ACTION_FORM_CONFIGURING_NOT_DONE_C46_C46_C33'));
            }
            if ($config_arr['entry_type'] == "Custom")
            {
                $file_name = $params_arr['file'];
            }
            else
            {
                $fields = $config_arr['table_alias'].".".$config_arr['field_name']." AS ".$unique_name;
                $data_arr = $this->query_images_model->getData(intval($id), $fields, '', '', '', 'Yes');
                if (!is_array($data_arr) || count($data_arr) == 0)
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_RECORDS_WHICH_YOU_ARE_TRYING_TO_ACCESS_ARE_NOT_AVAILABLE_C46_C46_C33'));
                }
                $file_name = $data_arr[0][$unique_name];
            }
            $this->listing->downloadFiles("Form", $config_arr, $file_name, $folder);
        }
        catch(Exception $e)
        {
            $ret_arr['success'] = 0;
            $ret_arr['message'] = $e->getMessage();
            echo json_encode($ret_arr);
        }
        $this->skip_template_view();
    }
    /**
     * deleteFormFile method is used to delete add or update form files
     */
    public function deleteFormFile()
    {
        $params_arr = $this->params_arr;
        $unique_name = $params_arr['unique_name'];
        $folder = $params_arr['folder'];
        $id = $params_arr['id'];
        $config_arr = $this->query_images_model->getFormConfiguration($unique_name);
        try
        {
            if (!is_array($config_arr) || count($config_arr) == 0)
            {
                throw new Exception($this->general->processMessageLabel('ACTION_FORM_CONFIGURING_NOT_DONE_C46_C46_C33'));
            }
            if ($config_arr['entry_type'] == "Custom")
            {
                //custom fields does not have delete from DB.
                $file_name = $params_arr['file'];
            }
            else
            {
                $field_name = $config_arr['field_name'];
                $fields = $config_arr['table_alias'].".".$field_name." AS ".$unique_name;
                $data_arr = $this->query_images_model->getData(intval($id), $fields, '', '', '', 'Yes');
                if (!is_array($data_arr) || count($data_arr) == 0)
                {
                    throw new Exception();
                }
                $fields = $config_arr['table_alias'].'.'.$field_name.' AS '.$unique_name;
                $data_arr = $this->query_images_model->getData(intval($id), $fields, '', '', '', 'Yes');
                if (!is_array($data_arr) || count($data_arr) == 0)
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_RECORDS_WHICH_YOU_ARE_TRYING_TO_ACCESS_ARE_NOT_AVAILABLE_C46_C46_C33'));
                }
                $fields = $config_arr['table_alias'].'.'.$field_name.' AS '.$unique_name;
                $data_arr = $this->query_images_model->getData(intval($id), $fields, '', '', '', 'Yes');
                if (!is_array($data_arr) || count($data_arr) == 0)
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_RECORDS_WHICH_YOU_ARE_TRYING_TO_ACCESS_ARE_NOT_AVAILABLE_C46_C46_C33'));
                }
                $update_arr[$field_name] = '';
                $res = $this->query_images_model->update($update_arr, intval($id));
                if (!$res)
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_FILE_DELETION_C46_C46_C33'));
                }
                $file_name = $data_arr[0][$unique_name];
            }
            $res = $this->listing->deleteFiles($config_arr, $file_name, $folder, "Form");
            if (!$res)
            {
                throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_FILE_DELETION_C46_C46_C33'));
            }
            $success = 1;
            $message = $this->general->processMessageLabel('ACTION_FILE_DELETED_SUCCESSFULLY_C46_C46_C33');
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $ret_arr['success'] = $success;
        $ret_arr['message'] = $message;
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    /**
     * getRelationModule method is used to get inline child module (relation module) data array formation
     * @param array $config_arr config array for data array settings.
     * @return array $return_arr returns data and config array for inline child module (relation module)
     */
    public function getRelationModule($config_arr = array())
    {
        $child_module = $config_arr['child_module'];
        $callback_func = $config_arr['callback'];
        $perform_arr = $config_arr['perform'];
        $row_index = ($config_arr['row_index']) ? $config_arr['row_index'] : 0;
        $lang_data = $config_arr['lang_data'];
        $data_arr = $config_arr['data'];
        $mode = $config_arr['mode'];
        $popup = $config_arr['popup'];

        $return_arr = $func_arr = $elem_arr = $opt_arr = $img_html = $auto_arr = array();
        $mode = ($config_arr['mode'] == "Update" && is_array($data_arr) && count($data_arr) > 0) ? "Update" : "Add";
        $list_config = $this->query_images_model->getListConfiguration();
        $form_config = $this->query_images_model->getFormConfiguration();
        $form_config = (is_array($form_config)) ? $form_config : array();

        $return_arr['status'] = 1;
        if ($callback_func != "")
        {
            $return_arr['status'] = $this->filter->processModuleFunction($callback_func, $this, $this->query_images_model, $config_arr);
        }
        if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
        {
            $edit_access = $this->filter->checkAccessCapability("query_images_update", TRUE);
        }
        else
        {
            $edit_access = $this->filter->getModuleWiseAccess("query_images", "Update", TRUE, TRUE);
        }
        if (in_array('data', $perform_arr) || in_array('options', $perform_arr))
        {
            if (!is_array($data_arr) || count($data_arr) == 0)
            {
                $data_arr[$row_index] = array();
            }
            foreach ($data_arr as $dKey => $dVal)
            {
                foreach ($form_config as $key => $val)
                {
                    $source_field = $val['name'];
                    $data = $dVal[$key];
                    $id = $dVal[$this->query_images_model->primary_key];
                    $combo_name = "child[".$child_module."][".$source_field."][]";
                    $combo_id = "child_".$child_module."_".$source_field."_".$dKey;
                    if ($val["dfapply"] != "")
                    {
                        $val['default'] = (substr($val['default'], 0, 6) == "copy::") ? $dVal[substr($val['default'], 6)] : $val['default'];
                        if ($val["dfapply"] == "forceApply" || $val["entry_type"] == "Custom")
                        {
                            $data = (isset($dVal[$key])) ? $dVal[$key] : $val['default'];
                        }
                        elseif ($val["dfapply"] == "addOnly")
                        {
                            if ($mode == "Add")
                            {
                                $data = (isset($dVal[$key])) ? $dVal[$key] : $val['default'];
                            }
                        }
                        elseif ($val["dfapply"] == "everyUpdate")
                        {
                            if ($mode == "Update")
                            {
                                $data = (isset($dVal[$key])) ? $dVal[$key] : $val['default'];
                            }
                        }
                        else
                        {
                            $data = (trim($data) != "") ? $data : $val['default'];
                        }
                    }
                    if ($val['encrypt'] == "Yes")
                    {
                        $data = $this->general->decryptDataMethod($data, $val["enctype"]);
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
                                $tmpdata = $this->$phpfunc($mode, $data, $dVal, $id, $source_field, $combo_id, $combo_name, $config_arr);
                            }
                        }
                        elseif (substr($phpfunc, 0, 7) == 'model::' && substr($phpfunc, 7) !== FALSE)
                        {
                            $phpfunc = substr($phpfunc, 7);
                            if (method_exists($this->query_images_model, $phpfunc))
                            {
                                $tmpdata = $this->query_images_model->$phpfunc($mode, $data, $dVal, $id, $source_field, $combo_id, $combo_name, $config_arr);
                            }
                        }
                        elseif (method_exists($this->general, $phpfunc))
                        {
                            $tmpdata = $this->general->$phpfunc($mode, $data, $dVal, $id, $source_field, $combo_id, $combo_name, $config_arr);
                        }
                        if ($fnctype == "input")
                        {
                            $elem_arr[$dKey][$key] = $tmpdata;
                        }
                        elseif ($fnctype == "status")
                        {
                            $func_arr[$dKey][$key] = $tmpdata;
                        }
                        else
                        {
                            $data = $tmpdata;
                        }
                    }
                    if ($val['field_status'] != "")
                    {
                        $status_type = $val['field_status'];
                        $fd_callback = $val['field_callback'];
                        if ($status_type == "capability" && $fd_callback != "")
                        {
                            $func_arr[$dKey][$key] = $this->filter->getFormFieldCapability($key, $child_module, $mode);
                        }
                        elseif ($status_type == "function")
                        {
                            $fd_status = 0;
                            if (substr($fd_callback, 0, 12) == 'controller::' && substr($fd_callback, 12) !== FALSE)
                            {
                                $fd_callback = substr($fd_callback, 12);
                                if (method_exists($this, $fd_callback))
                                {
                                    $fd_status = $this->$fd_callback($mode, $data, $dVal, $id, $source_field, $combo_id, $combo_name, $config_arr);
                                }
                            }
                            elseif (substr($fd_callback, 0, 7) == 'model::' && substr($fd_callback, 7) !== FALSE)
                            {
                                $fd_callback = substr($fd_callback, 7);
                                if (method_exists($this->query_images_model, $fd_callback))
                                {
                                    $fd_status = $this->query_images_model->$fd_callback($mode, $data, $dVal, $id, $source_field, $combo_id, $combo_name, $config_arr);
                                }
                            }
                            elseif (method_exists($this->general, $fd_callback))
                            {
                                $fd_status = $this->general->$fd_callback($mode, $data, $dVal, $id, $source_field, $combo_id, $combo_name, $config_arr);
                            }
                            $func_arr[$dKey][$key] = $fd_status;
                        }
                    }
                    $combo_config = $this->dropdown_arr[$source_field];
                    if (is_array($combo_config) && count($combo_config) > 0)
                    {
                        if ($combo_config['auto'] == "Yes")
                        {
                            $combo_count = $this->getSourceOptions($source_field, $mode, $id, $dVal, '', 'count');
                            if ($combo_count[0]['tot'] > $this->dropdown_limit)
                            {
                                $auto_arr[$dKey][$source_field] = "Yes";
                            }
                        }
                        $combo_arr = $this->getSourceOptions($source_field, $mode, $id, $dVal);
                        $final_arr = $this->filter->makeArrayDropdown($combo_arr);
                        if ($combo_config['opt_group'] == "Yes")
                        {
                            $display_arr = $this->filter->makeOPTDropdown($combo_arr);
                        }
                        else
                        {
                            $display_arr = $final_arr;
                        }
                        $this->dropdown->combo("array", $combo_id, $display_arr, $data);
                        $opt_arr[$combo_id] = $final_arr;
                    }
                    if ($val['file_upload'] == 'Yes')
                    {
                        $del_file = ($edit_access && $return_arr['status'] != 2 && $func_arr[$dKey][$key] != 2) ? TRUE : FALSE;
                        $val['htmlID'] = $combo_id;
                        $img_html[$dKey][$key] = $this->listing->parseFormFile($data, $id, $dVal, $val, $this->module_config, "Form", $del_file, $config_arr['multi_file']);
                    }
                    $data_arr[$dKey][$key] = $data;
                }
            }
        }
        if (method_exists($this->filter, "setFormFieldCapability"))
        {
            $this->filter->setFormFieldCapability($func_arr, $child_module, $mode, TRUE);
        }
        if ($mode == "Add" && $config_arr['multi_file'] == "Yes")
        {
            $img_html = array();
        }

        $return_arr['config_arr'] = $this->module_config;
        if (in_array('data', $perform_arr))
        {
            $return_arr['data'] = $data_arr;
            $return_arr['func'] = $func_arr;
            $return_arr['elem'] = $elem_arr;
            $return_arr['img_html'] = $img_html;
            $return_arr['lang_data'] = $lang_data;
        }
        if (in_array('options', $perform_arr))
        {
            $return_arr['opt_arr'] = $opt_arr;
            $return_arr['auto_arr'] = $auto_arr;
        }
        if (in_array('config', $perform_arr))
        {
            $return_arr['form_config'] = $form_config;
            $return_arr['list_config'] = $list_config;
        }
        return $return_arr;
    }
    /**
     * addActionPopup method is used to save and get inline popup child module (relation module) data
     */
    public function addActionPopup()
    {
        $params_arr = $this->params_arr;
        $parMod = $params_arr['parMod'];
        $parID = $params_arr['parID'];
        $rmMode = $params_arr['rmMode'];
        $rmNum = $params_arr['rmNum'];
        $incNo = ($rmNum != "") ? intval($rmNum) : 0;
        $mode = ($params_arr['mode'] == "Update") ? "Update" : "Add";
        $id = $params_arr['id'];
        $extra_hstr = '';
        /*{*parent module condition*}*/
        try
        {
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
            {
                if ($mode == "Update")
                {
                    $add_edit_access = $this->filter->checkAccessCapability("query_images_update", TRUE);
                }
                else
                {
                    $add_edit_access = $this->filter->checkAccessCapability("query_images_add", TRUE);
                }
            }
            else
            {
                $add_edit_access = $this->filter->getModuleWiseAccess("query_images", $mode, TRUE, TRUE);
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
            $form_config = $this->query_images_model->getFormConfiguration();
            $uqi_user_query_id = $params_arr["uqi_user_query_id"];
            $uqi_query_image = $params_arr["uqi_query_image"];
            $uqi_added_at = $params_arr["uqi_added_at"];
            $uqi_status = $params_arr["uqi_status"];

            $data = $save_data_arr = array();
            $data["iUserQueryId"] = $uqi_user_query_id;
            $data["vQueryImage"] = $uqi_query_image;
            $data["dtAddedAt"] = $this->filter->formatActionData($uqi_added_at, $form_config["uqi_added_at"]);
            $data["eStatus"] = $uqi_status;

            $save_data_arr["uqi_user_query_id"] = $data["iUserQueryId"];
            $save_data_arr["uqi_query_image"] = $data["vQueryImage"];
            $save_data_arr["uqi_added_at"] = $data["dtAddedAt"];
            $save_data_arr["uqi_status"] = $data["eStatus"];
            if ($mode == 'Update')
            {
                $save_data_arr[$this->query_images_model->primary_key] = $id;
                $save_data_arr[$this->query_images_model->primary_alias] = $id;
            }

            $data_rec[$incNo] = $save_data_arr;

            $child_config["child_module"] = $this->module_name;
            $child_config["row_index"] = $incNo;
            $child_config["mode"] = $rmMode;
            $child_config["popup"] = "Yes";
            $child_config["data"] = $data_rec;
            $child_config["perform"] = array(
                "data",
                "options",
                "config",
            );
            $module_arr = $this->getRelationModule($child_config);
            $module_arr["config_arr"]["form_config"] = $module_arr["form_config"];
            $child_data[0] = $module_arr["data"][$incNo];
            $child_func[0] = $module_arr["func"][$incNo];
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
            switch ($parMod)
            {
                case "feedback_management":
                    $this->parser->addTemplateLocation(APPPATH."admin/basic_appineers_master/views/");
                    $child_module_add_file = "feedback_management_query_images_add_more";
                    break;
            }
            if ($child_module_add_file == "")
            {
                throw new Exception($this->general->processMessageLabel('ACTION_MODULE_CONFIGURATION_NOT_DONE_C46_C46_C33'));
            }
            $enc_child_id = $this->general->getAdminEncodeURL($id);
            $render_arr = array(
                "child_module_name" => $this->module_name,
                "row_index" => $incNo,
                "child_data" => $child_data,
                "child_func" => $child_func,
                "child_conf_arr" => $child_conf_arr,
                "child_opt_arr" => $child_opt_arr,
                "child_img_html" => $child_img_html,
                "child_auto_arr" => $child_auto_arr,
                "child_auto_arr" => $child_auto_arr,
                "child_access_arr" => $child_access_arr,
                "recMode" => $mode,
                "popup" => "Yes",
                "mode" => $rmMode,
                "child_id" => $id,
                "enc_child_id" => $enc_child_id,
            );
            $parse_html = $this->parser->parse($child_module_add_file, $render_arr, TRUE);

            $ret_arr['message'] = $msg;
            $ret_arr['success'] = 1;
            $ret_arr['child_module'] = $this->module_name;
            $ret_arr['rmContent'] = $parse_html;
            $ret_arr['rmPopup'] = "true";
            $ret_arr['rmNum'] = $incNo;
        }
        catch(Exception $e)
        {
            $ret_arr['message'] = $e->getMessage();
            $ret_arr['success'] = 0;
        }
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }
}
