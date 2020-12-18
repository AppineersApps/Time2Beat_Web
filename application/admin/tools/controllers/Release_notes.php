<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Release Notes Controller
 *
 * @category admin
 *
 * @package tools
 *
 * @subpackage controllers
 *
 * @module Release Notes
 *
 * @class Release_notes.php
 *
 * @path application\admin\tools\controllers\Release_notes.php
 *
 * @version 4.3
 *
 * @author CIT Dev Team
 *
 * @since 01.11.2018
 */
class Release_notes extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     * @created CIT Admin | 01.11.2018
     * @modified CIT Admin | 01.11.2018
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('listing');
        $this->load->library('filter');
        $this->load->library('dropdown');
        $this->load->model('release_notes_model');
        $this->_request_params();
        $this->folder_name = "tools";
        $this->module_name = "release_notes";
        $this->mod_enc_url = $this->general->getGeneralEncryptList($this->folder_name, $this->module_name);
        $this->mod_enc_mode = $this->general->getCustomEncryptMode(TRUE);
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
            "mrn_release_status" => array(
                "type" => "enum",
                "default" => "Yes",
                "values" => array(
                    array(
                        'id' => 'Release',
                        'val' => $this->lang->line('RELEASE_NOTES_RELEASE')
                    ),
                    array(
                        'id' => 'Draft',
                        'val' => $this->lang->line('RELEASE_NOTES_SAVE_DRAFT')
                    ),
                    array(
                        'id' => 'Discard',
                        'val' => $this->lang->line('RELEASE_NOTES_DISCARD')
                    )
                )
            ),
            "mrn_added_by" => array(
                "type" => "table",
                "table_name" => "mod_admin",
                "field_key" => "iAdminId",
                "field_val" => array(
                    $this->db->protect("vName")
                ),
                "order_by" => "val asc",
                "default" => "Yes",
            ),
            "mrn_updated_by" => array(
                "type" => "table",
                "table_name" => "mod_admin",
                "field_key" => "iAdminId",
                "field_val" => array(
                    $this->db->protect("vName")
                ),
                "order_by" => "val asc",
                "default" => "Yes",
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
        try {
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                $access_list = array(
                    "release_notes_list",
                    "release_notes_view",
                    "release_notes_add",
                    "release_notes_update",
                    "release_notes_delete",
                    "release_notes_export",
                    "release_notes_print",
                );
                list($list_access, $view_access, $add_access, $edit_access, $del_access, $expo_access, $print_access) = $this->filter->checkAccessCapability($access_list, TRUE);
            } else {
                $access_list = array(
                    "List",
                    "View",
                    "Add",
                    "Update",
                    "Delete",
                    "Export",
                    "Print",
                );
                list($list_access, $view_access, $add_access, $edit_access, $del_access, $expo_access, $print_access) = $this->filter->getModuleWiseAccess("release_notes", $access_list, TRUE, TRUE);
            }
            if (!$list_access) {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }
            $enc_loc_module = $this->general->getMD5EncryptString("ListPrefer", "release_notes");

            $status_array = array(
                'Release',
                'Draft',
                'Discard',
            );
            $status_label = array(
                'js_lang_label.RELEASE_NOTES_RELEASE',
                'js_lang_label.RELEASE_NOTES_SAVE_DRAFT',
                'js_lang_label.RELEASE_NOTES_DISCARD',
            );

            $list_config = $this->release_notes_model->getListConfiguration();
            $this->processConfiguration($list_config, $add_access, $edit_access, TRUE);
            $this->general->trackModuleNavigation("Module", "List", "Viewed", $this->mod_enc_url["index"], "release_notes");

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
                'default_filters' => $this->release_notes_model->default_filters,
            );
            $this->smarty->assign($render_arr);
            $this->loadView("release_notes_index");
        } catch (Exception $e) {
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
        if (!trim($sidx) && !trim($sord)) {
            $sdef = 'Yes';
        }
        if ($this->general->allowStripSlashes()) {
            $filters = stripslashes($filters);
        }
        $filters = json_decode($filters, TRUE);
        $list_config = $this->release_notes_model->getListConfiguration();
        $form_config = $this->release_notes_model->getFormConfiguration();
        $extra_cond = $this->release_notes_model->extra_cond;
        $groupby_cond = $this->release_notes_model->groupby_cond;
        $having_cond = $this->release_notes_model->having_cond;
        $orderby_cond = $this->release_notes_model->orderby_cond;

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

        $data_recs = $this->release_notes_model->getListingData($data_config);
        $data_recs['no_records_msg'] = $this->general->processMessageLabel('ACTION_NO_RELEASE_NOTES_DATA_FOUND_C46_C46_C33');

        echo json_encode($data_recs);
        $this->skip_template_view();
    }

    /**
     * export method is used to export listing data records in csv or pdf formats.
     */
    public function export()
    {
        if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
            $this->filter->checkAccessCapability("release_notes_export");
        } else {
            $this->filter->getModuleWiseAccess("release_notes", "Export", TRUE);
        }
        $params_arr = $this->params_arr;
        $page = $params_arr['page'];
        $rowlimit = $params_arr['rowlimit'];
        $sidx = $params_arr['sidx'];
        $sord = $params_arr['sord'];
        $sdef = $params_arr['sdef'];
        if (!trim($sidx) && !trim($sord)) {
            $sdef = 'Yes';
        }
        $selected = $params_arr['selected'];
        $id = explode(",", $params_arr['id']);
        $export_type = $params_arr['export_type'];
        $export_mode = $params_arr['export_mode'];
        $filters = $params_arr['filters'];
        if ($this->general->allowStripSlashes()) {
            $filters = stripslashes($filters);
        }
        $filters = json_decode(base64_decode($filters), TRUE);
        $fields = json_decode(base64_decode($params_arr['fields']), TRUE);
        $list_config = $this->release_notes_model->getListConfiguration();
        $form_config = $this->release_notes_model->getFormConfiguration();
        $table_name = $this->release_notes_model->table_name;
        $table_alias = $this->release_notes_modeltable_alias;
        $primary_key = $this->release_notes_model->primary_key;
        $extra_cond = $this->release_notes_model->extra_cond;
        $groupby_cond = $this->release_notes_model->groupby_cond;
        $having_cond = $this->release_notes_model->having_cond;
        $orderby_cond = $this->release_notes_model->orderby_cond;

        $export_config = array();
        if ($selected == "true") {
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

        $db_recs = $this->release_notes_model->getExportData($export_config);
        $db_recs = $this->listing->getDataForList($db_recs, $export_config, "GExport", array());
        if (!is_array($db_recs) || count($db_recs) == 0) {
            $this->session->set_flashdata('failure', $this->general->processMessageLabel('GENERIC_GRID_NO_RECORDS_TO_PROCESS'));
            redirect($_SERVER['HTTP_REFERER']);
        }

        require_once ($this->config->item('third_party') . 'Csv_export.php');
        require_once ($this->config->item('third_party') . 'Pdf_export.php');

        $tot_fields_arr = array_keys($db_recs[0]);
        if ($export_mode == "all" && is_array($tot_fields_arr)) {
            if (($pr_key = array_search($primary_key, $tot_fields_arr)) !== FALSE) {
                unset($tot_fields_arr[$pr_key]);
            }
            $fields = array();
            if ($this->config->item("DISABLE_LIST_EXPORT_ALL")) {
                foreach ((array) $list_config as $key => $val) {
                    if (isset($val['export']) && $val['export'] == "Yes") {
                        $fields[] = $key;
                    }
                }
            } else {
                $fields = array_values($tot_fields_arr);
            }
        }

        $misc_info = array();
        $misc_info['fields'] = $fields;
        $misc_info['heading'] = $this->lang->line('RELEASE_NOTES_DETAILS_RELEASE_NOTES');
        $misc_info['filename'] = "release_notes_records_" . count($db_recs);
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
        if ($export_type == 'pdf') {
            $pdf_style = "TCPDF";
            $columns = $aligns = $widths = $data = array();
            //Table headers info
            for ($i = 0; $i < $numberOfColumns; $i++) {
                $size = 10;
                $position = '';
                if (array_key_exists($fields[$i], $list_config)) {
                    $label = $list_config[$fields[$i]]['label_lang'];
                    $position = $list_config[$fields[$i]]['align'];
                    $size = $list_config[$fields[$i]]['width'];
                } elseif (array_key_exists($fields[$i], $form_config)) {
                    $label = $form_config[$fields[$i]]['label_lang'];
                } else {
                    $label = $fields[$i];
                }
                $columns[] = $label;
                $aligns[] = in_array($position, array('right', 'center')) ? $position : "left";
                $widths[] = $size;
            }

            //Table data info
            $db_rec_cnt = count($db_recs);
            for ($i = 0; $i < $db_rec_cnt; $i++) {
                foreach ((array) $db_recs[$i] as $key => $val) {
                    if (is_array($fields) && in_array($key, $fields)) {
                        $data[$i][$key] = $this->listing->dataForExportMode($val, "pdf", $pdf_style);
                    }
                }
            }

            $pdf = new PDF_Export($misc_info['pdf_page_orientation'], $misc_info['pdf_unit'], $misc_info['pdf_page_format'], TRUE, 'UTF-8', FALSE);
            if (method_exists($pdf, "setModule")) {
                $pdf->setModule("release_notes_model");
            }
            if (method_exists($pdf, "setContent")) {
                $pdf->setContent($misc_info);
            }
            if (method_exists($pdf, "setController")) {
                $pdf->setController($this);
            }
            $pdf->initialize($heading);
            $pdf->writeGridTable($columns, $data, $widths, $aligns);
            $pdf->Output($filename . ".pdf", 'D');
        } elseif ($export_type == 'csv') {
            $columns = $data = array();

            for ($i = 0; $i < $numberOfColumns; $i++) {
                if (array_key_exists($fields[$i], $list_config)) {
                    $label = $list_config[$fields[$i]]['label_lang'];
                } elseif (array_key_exists($fields[$i], $form_config)) {
                    $label = $form_config[$fields[$i]]['label_lang'];
                } else {
                    $label = $fields[$i];
                }
                $columns[] = $label;
            }
            $db_recs_cnt = count($db_recs);
            for ($i = 0; $i < $db_recs_cnt; $i++) {
                foreach ((array) $db_recs[$i] as $key => $val) {
                    if (is_array($fields) && in_array($key, $fields)) {
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
        try {
            $extra_cond = $this->release_notes_model->extra_cond;

            $parMod = $params_arr["parMod"];
            $parID = $params_arr["parID"];
            if ($parMod != "") {
                $enc_parMod = $this->general->getAdminEncodeURL($parMod);
                $enc_parID = $this->general->getAdminEncodeURL($parID);
                $extra_qstr .= "&parMod=" . $enc_parMod . "&parID=" . $enc_parID . "&parType=parent";
                $extra_hstr .= "|parMod|" . $enc_parMod . "|parID|" . $enc_parID . "|parType|parent";
                $parent_switch_combo = $this->getParentSwitchTo($parMod, $parID, TRUE);
                $parent_refer = $this->parRefer[$parMod];
                $parent_extra_cond = "";
                if ($parent_refer["rel_source"] != "") {
                    $parent_extra_cond = $this->db->protect("mrn." . $parent_refer["rel_source"]) . " = " . $this->db->escape($parID);
                }
                if ($parent_refer["extra_cond"] != "") {
                    $parent_extra_cond .= " AND " . $parent_refer["extra_cond"];
                }
                if ($parent_extra_cond != "") {
                    $extra_cond = ($extra_cond != "") ? $extra_cond . " AND " . $parent_extra_cond : $parent_extra_cond;
                }
            }
            if ($mode == "Update") {
                if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                    $access_list = array(
                        "release_notes_list",
                        "release_notes_view",
                        "release_notes_update",
                        "release_notes_delete",
                        "release_notes_print",
                    );
                    list($list_access, $view_access, $edit_access, $del_access, $print_access) = $this->filter->checkAccessCapability($access_list, TRUE);
                } else {
                    $access_list = array(
                        "List",
                        "View",
                        "Update",
                        "Delete",
                        "Print",
                    );
                    list($list_access, $view_access, $edit_access, $del_access, $print_access) = $this->filter->getModuleWiseAccess("release_notes", $access_list, TRUE, TRUE);
                }
                if (!$edit_access && !$view_access) {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
                }
            } else {
                if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                    $access_list = array(
                        "release_notes_list",
                        "release_notes_add",
                    );
                    list($list_access, $add_access) = $this->filter->checkAccessCapability($access_list, TRUE);
                } else {
                    $access_list = array(
                        "List",
                        "Add",
                    );
                    list($list_access, $add_access) = $this->filter->getModuleWiseAccess("release_notes", $access_list, TRUE, TRUE);
                }
                if (!$add_access) {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_ADD_THESE_DETAILS_C46_C46_C33'));
                }
            }

            $data = $orgi = $func = $elem = array();
            if ($mode == 'Update') {
                $ctrl_flow = $this->ci_local->read($this->general->getMD5EncryptString("FlowEdit", "release_notes"), $this->session->userdata('iAdminId'));
                $data_arr = $this->release_notes_model->getData(intval($id));
                $data = $orgi = $data_arr[0];
                if ((!is_array($data) || count($data) == 0) && $params_arr['rmPopup'] != "true") {
                    throw new Exception($this->general->processMessageLabel('ACTION_RECORDS_WHICH_YOU_ARE_TRYING_TO_ACCESS_ARE_NOT_AVAILABLE_C46_C46_C33'));
                }
                $switch_arr = $this->release_notes_model->getSwitchTo($extra_cond, "records", $this->switchto_limit);
                $switch_combo = $this->filter->makeArrayDropDown($switch_arr);
                $switch_cit = array();
                $switch_tot = $this->release_notes_model->getSwitchTo($extra_cond, "count");
                if ($this->switchto_limit > 0 && $switch_tot > $this->switchto_limit) {
                    $switch_cit['param'] = "true";
                    $switch_cit['url'] = $this->mod_enc_url['get_self_switch_to'];
                    if (!array_key_exists($id, $switch_combo)) {
                        $extra_cond = $this->db->protect($this->release_notes_model->table_alias . "." . $this->release_notes_model->primary_key) . " = " . $this->db->escape($id);
                        $switch_cur = $this->release_notes_model->getSwitchTo($extra_cond, "records", 1);
                        if (is_array($switch_cur) && count($switch_cur) > 0) {
                            $switch_combo[$switch_cur[0]['id']] = $switch_cur[0]['val'];
                        }
                    }
                }
                $recName = $switch_combo[$id];
                $switch_enc_combo = $this->filter->getSwitchEncryptRec($switch_combo);
                $this->dropdown->combo("array", "vSwitchPage", $switch_enc_combo, $enc_id);
                $next_prev_records = $this->filter->getNextPrevRecords($id, $switch_arr);

                $this->general->trackModuleNavigation("Module", "Form", "Viewed", $this->mod_enc_url["add"], "release_notes", $recName);
            } else {
                $recName = '';
                $ctrl_flow = $this->ci_local->read($this->general->getMD5EncryptString("FlowAdd", "release_notes"), $this->session->userdata('iAdminId'));
                $this->general->trackModuleNavigation("Module", "Form", "Viewed", $this->mod_enc_url["add"], "release_notes");
            }

            $opt_arr = $img_html = $auto_arr = $config_arr = array();

            $main_data = $this->release_notes_model->getData(intval($id), array('mrn.iReleaseNotesId'));

            $relation_module = $this->release_notes_model->relation_modules["release_notes_details"];
            $this->load->module("tools/release_notes_details");
            $this->load->model("tools/release_notes_details_model");
            $child_config = array();
            $child_config["parent_module"] = "release_notes";
            $child_config["child_module"] = "release_notes_details";
            if ($mode == "Update") {
                $extra_cond = $this->db->protect("mrnd.iReleaseNotesId") . " = " . $this->db->escape($main_data[0]["iReleaseNotesId"]);
                if ($relation_module["extra_cond"] != "") {
                    $extra_cond .= " AND " . $relation_module["extra_cond"];
                }
                $parent_join_arr = array(
                    "joins" => array(
                        array(
                            "table_name" => "mod_release_notes",
                            "table_alias" => "mrn",
                            "field_name" => "iReleaseNotesId",
                            "rel_table_name" => "mod_release_note_details",
                            "rel_table_alias" => "mrnd",
                            "rel_field_name" => "iReleaseNotesId",
                            "join_type" => "left",
                        )
                    )
                );
                $child_data = $this->release_notes_details_model->getData($extra_cond, "", "", "mrnd.iReleaseNoteDetailsId", "", $parent_join_arr);
                $child_config["mode"] = (is_array($child_data) && count($child_data) > 0) ? "Update" : "Add";
                $child_config["data"] = $child_data;
                $child_config["parent_data"] = $data;
            } else {
                $child_config["mode"] = "Add";
                $child_config["data"] = array();
            }
            $child_config["perform"] = array(
                "data",
                "options",
                "config",
            );

            $module_arr = $this->release_notes_details->getRelationModule($child_config);
            $module_arr["config_arr"]["popup"] = $relation_module["popup"];
            $module_arr["config_arr"]["recMode"] = $child_config["mode"];
            $module_arr["config_arr"]["form_config"] = $module_arr["form_config"];
            $child_assoc_data["release_notes_details"] = $module_arr["data"];
            $child_assoc_func["release_notes_details"] = $module_arr["func"];
            $child_assoc_elem["release_notes_details"] = $module_arr["elem"];
            $child_assoc_conf["release_notes_details"] = $module_arr["config_arr"];
            $child_assoc_opt["release_notes_details"] = $module_arr["opt_arr"];
            $child_assoc_img["release_notes_details"] = $module_arr["img_html"];
            $child_assoc_auto["release_notes_details"] = $module_arr["auto_arr"];
            $child_assoc_status["release_notes_details"] = $module_arr["status"];
            $child_assoc_access["release_notes_details"] = array(
                "add" => 1,
                "save" => 1,
                "delete" => 1,
                "actions" => 1,
                "labels" => array()
            );

            $form_config = $this->release_notes_model->getFormConfiguration($config_arr);
            if (is_array($form_config) && count($form_config) > 0) {
                foreach ($form_config as $key => $val) {
                    if ($params_arr['rmPopup'] == "true" && $params_arr[$key] != "") {
                        $data[$key] = $params_arr[$key];
                    } elseif ($val["dfapply"] != "") {
                        $val['default'] = (substr($val['default'], 0, 6) == "copy::") ? $orgi[substr($val['default'], 6)] : $val['default'];
                        if ($val["dfapply"] == "forceApply" || $val["entry_type"] == "Custom") {
                            $data[$key] = $val['default'];
                        } elseif ($val["dfapply"] == "addOnly") {
                            if ($mode == "Add") {
                                $data[$key] = $val['default'];
                            }
                        } elseif ($val["dfapply"] == "everyUpdate") {
                            if ($mode == "Update") {
                                $data[$key] = $val['default'];
                            }
                        } else {
                            $data[$key] = (trim($data[$key]) != "") ? $data[$key] : $val['default'];
                        }
                    }
                    if ($val['encrypt'] == "Yes") {
                        $data[$key] = $this->general->decryptDataMethod($data[$key], $val["enctype"]);
                    }
                    if ($val['function'] != "") {
                        $fnctype = $val['functype'];
                        $phpfunc = $val['function'];
                        $tmpdata = '';
                        if (substr($phpfunc, 0, 12) == 'controller::' && substr($phpfunc, 12) !== FALSE) {
                            $phpfunc = substr($phpfunc, 12);
                            if (method_exists($this, $phpfunc)) {
                                $tmpdata = $this->$phpfunc($mode, $data[$key], $data, $id, $key, $key, $this->module_name);
                            }
                        } elseif (substr($phpfunc, 0, 7) == 'model::' && substr($phpfunc, 7) !== FALSE) {
                            $phpfunc = substr($phpfunc, 7);
                            if (method_exists($this->release_notes_model, $phpfunc)) {
                                $tmpdata = $this->release_notes_model->$phpfunc($mode, $data[$key], $data, $id, $key, $key, $this->module_name);
                            }
                        } elseif (method_exists($this->general, $phpfunc)) {
                            $tmpdata = $this->general->$phpfunc($mode, $data[$key], $data, $id, $key, $key, $this->module_name);
                        }
                        if ($fnctype == "input") {
                            $elem[$key] = $tmpdata;
                        } elseif ($fnctype == "status") {
                            $func[$key] = $tmpdata;
                        } else {
                            $data[$key] = $tmpdata;
                        }
                    }
                    if ($val['field_status'] != "") {
                        $status_type = $val['field_status'];
                        $fd_callback = $val['field_callback'];
                        if ($status_type == "capability" && $fd_callback != "") {
                            $func[$key] = $this->filter->getFormFieldCapability($key, $this->module_name, $mode);
                        } elseif ($status_type == "function") {
                            $fd_status = 0;
                            if (substr($fd_callback, 0, 12) == 'controller::' && substr($fd_callback, 12) !== FALSE) {
                                $fd_callback = substr($fd_callback, 12);
                                if (method_exists($this, $fd_callback)) {
                                    $fd_status = $this->$fd_callback($mode, $data[$key], $data, $id, $key, $key, $this->module_name);
                                }
                            } elseif (substr($fd_callback, 0, 7) == 'model::' && substr($fd_callback, 7) !== FALSE) {
                                $fd_callback = substr($fd_callback, 7);
                                if (method_exists($this->release_notes_model, $fd_callback)) {
                                    $fd_status = $this->release_notes_model->$fd_callback($mode, $data[$key], $data, $id, $key, $key, $this->module_name);
                                }
                            } elseif (method_exists($this->general, $fd_callback)) {
                                $fd_status = $this->general->$fd_callback($mode, $data[$key], $data, $id, $key, $key, $this->module_name);
                            }
                            $func[$key] = $fd_status;
                        }
                    }
                    $source_field = $val['name'];
                    $combo_config = $this->dropdown_arr[$source_field];
                    if (is_array($combo_config) && count($combo_config) > 0) {
                        if ($combo_config['auto'] == "Yes") {
                            $combo_count = $this->getSourceOptions($source_field, $mode, $id, $data, '', 'count');
                            if ($combo_count[0]['tot'] > $this->dropdown_limit) {
                                $auto_arr[$source_field] = "Yes";
                            }
                        }
                        $combo_arr = $this->getSourceOptions($source_field, $mode, $id, $data);
                        $final_arr = $this->filter->makeArrayDropdown($combo_arr);
                        if ($combo_config['opt_group'] == "Yes") {
                            $display_arr = $this->filter->makeOPTDropdown($combo_arr);
                        } else {
                            $display_arr = $final_arr;
                        }
                        $this->dropdown->combo("array", $source_field, $display_arr, $data[$key]);
                        $opt_arr[$source_field] = $final_arr;
                    }
                }
            }
            $extra_qstr .= $this->general->getRequestURLParams();
            $extra_hstr .= $this->general->getRequestHASHParams();

            /** access controls <<< * */
            $controls_allow = $prev_link_allow = $next_link_allow = $update_allow = $delete_allow = $backlink_allow = $switchto_allow = $discard_allow = $tabing_allow = TRUE;
            if ($mode == "Update") {
                if (!$del_access || $this->module_config["delete"] == "Yes") {
                    $delete_allow = FALSE;
                }
            }
            if (is_array($switch_combo) && count($switch_combo) > 0) {
                $prev_link_allow = ($next_prev_records['prev']['id'] != '') ? TRUE : FALSE;
                $next_link_allow = ($next_prev_records['next']['id'] != '') ? TRUE : FALSE;
            } else {
                $prev_link_allow = $next_link_allow = $switchto_allow = FALSE;
            }
            if (!$list_access) {
                $backlink_allow = $discard_allow = FALSE;
            }
            if ($hideCtrl == "true") {
                $controls_allow = $prev_link_allow = $next_link_allow = $delete_allow = $backlink_allow = $switchto_allow = $tabing_allow = FALSE;
            }
            /** access controls >>> * */
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
            if ($mode == "Update") {
                if ($edit_access && $viewMode != TRUE) {
                    $this->loadView("release_notes_add");
                } else {
                    $this->loadView("release_notes_add_view");
                }
            } else {
                $this->loadView("release_notes_add");
            }
        } catch (Exception $e) {
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
        try {
            $ret_arr = array();
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                if ($mode == "Update") {
                    $add_edit_access = $this->filter->checkAccessCapability("release_notes_update", TRUE);
                } else {
                    $add_edit_access = $this->filter->checkAccessCapability("release_notes_add", TRUE);
                }
            } else {
                $add_edit_access = $this->filter->getModuleWiseAccess("release_notes", $mode, TRUE, TRUE);
            }
            if (!$add_edit_access) {
                if ($mode == "Update") {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                } else {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_ADD_THESE_DETAILS_C46_C46_C33'));
                }
            }

            $form_config = $this->release_notes_model->getFormConfiguration();
            $params_arr = $this->_request_params();
            $mrn_version_number = $params_arr["mrn_version_number"];
            $mrn_release_date = $params_arr["mrn_release_date"];
            $mrn_release_status = $params_arr["mrn_release_status"];
            $mrn_date_added = $params_arr["mrn_date_added"];
            $mrn_added_by = $params_arr["mrn_added_by"];
            $mrn_date_updated = $params_arr["mrn_date_updated"];
            $mrn_updated_by = $params_arr["mrn_updated_by"];

            $unique_arr = array();
            $unique_arr["vVersionNumber"] = $mrn_version_number;

            $unique_exists = $this->release_notes_model->checkRecordExists($this->release_notes_model->unique_fields, $unique_arr, $id, $mode, $this->release_notes_model->unique_type);
            if ($unique_exists) {
                $error_msg = $this->general->processMessageLabel('ACTION_RECORD_ALREADY_EXISTS_WITH_THESE_DETAILS_OF_VERSION_NUMBER_C46_C46_C33');
                if ($error_msg == "") {
                    $error_msg = "Record already exists with these details of Version Number";
                }
                throw new Exception($error_msg);
            }
            $data = $save_data_arr = $file_data = array();
            $data["vVersionNumber"] = $mrn_version_number;
            $data["dReleaseDate"] = $this->filter->formatActionData($mrn_release_date, $form_config["mrn_release_date"]);
            $data["eReleaseStatus"] = $mrn_release_status;
            $data["dDateAdded"] = $this->filter->formatActionData($mrn_date_added, $form_config["mrn_date_added"]);
            $data["iAddedBy"] = $mrn_added_by;
            $data["dDateUpdated"] = $this->filter->formatActionData($mrn_date_updated, $form_config["mrn_date_updated"]);
            $data["iUpdatedBy"] = $mrn_updated_by;

            $save_data_arr["mrn_version_number"] = $data["vVersionNumber"];
            $save_data_arr["mrn_release_date"] = $data["dReleaseDate"];
            $save_data_arr["mrn_release_status"] = $data["eReleaseStatus"];
            $save_data_arr["mrn_date_added"] = $data["dDateAdded"];
            $save_data_arr["mrn_added_by"] = $data["iAddedBy"];
            $save_data_arr["mrn_date_updated"] = $data["dDateUpdated"];
            $save_data_arr["mrn_updated_by"] = $data["iUpdatedBy"];
            if ($mode == 'Add') {
                $id = $this->release_notes_model->insert($data);
                if (intval($id) > 0) {
                    $save_data_arr["iReleaseNotesId"] = $data["iReleaseNotesId"] = $id;
                    $msg = $this->general->processMessageLabel('ACTION_RECORD_ADDED_SUCCESSFULLY_C46_C46_C33');
                } else {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_ADDING_RECORD_C46_C46_C33'));
                }
                $track_cond = $this->db->protect("mrn.iReleaseNotesId") . " = " . $this->db->escape($id);
                $switch_combo = $this->release_notes_model->getSwitchTo($track_cond);
                $recName = $switch_combo[0]["val"];
                $this->general->trackModuleNavigation("Module", "Form", "Added", $this->mod_enc_url["add"], "release_notes", $recName, "mode|" . $this->general->getAdminEncodeURL("Update") . "|id|" . $this->general->getAdminEncodeURL($id));
            } elseif ($mode == 'Update') {
                $res = $this->release_notes_model->update($data, intval($id));
                if (intval($res) > 0) {
                    $save_data_arr["iReleaseNotesId"] = $data["iReleaseNotesId"] = $id;
                    $msg = $this->general->processMessageLabel('ACTION_RECORD_SUCCESSFULLY_UPDATED_C46_C46_C33');
                } else {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPDATING_OF_THIS_RECORD_C46_C46_C33'));
                }
                $track_cond = $this->db->protect("mrn.iReleaseNotesId") . " = " . $this->db->escape($id);
                $switch_combo = $this->release_notes_model->getSwitchTo($track_cond);
                $recName = $switch_combo[0]["val"];
                $this->general->trackModuleNavigation("Module", "Form", "Modified", $this->mod_enc_url["add"], "release_notes", $recName, "mode|" . $this->general->getAdminEncodeURL("Update") . "|id|" . $this->general->getAdminEncodeURL($id));
            }
            $ret_arr['id'] = $id;
            $ret_arr['mode'] = $mode;
            $ret_arr['message'] = $msg;
            $ret_arr['success'] = 1;

            $params_arr = $this->_request_params();

            $childModuleArr = $params_arr["childModule"];
            $childPostArr = $params_arr["child"];
            if (is_array($childModuleArr) && count($childModuleArr) > 0) {
                $main_data = $this->release_notes_model->getData(intval($id), array('mrn.iReleaseNotesId'));
                foreach ((array) $childModuleArr as $mdKey => $mdVal) {
                    $child_module = $mdVal;
                    if ($params_arr["childModuleShowHide"][$child_module] == "No") {
                        continue;
                    }
                    $child_module_post = $childPostArr[$child_module];
                    $child_id_arr = is_array($child_module_post["id"]) ? $child_module_post["id"] : array();
                    switch ($child_module) {
                        case "release_notes_details":
                            $relation_module = $this->release_notes_model->relation_modules["release_notes_details"];

                            $extra_del = $this->db->protect("mrnd.iReleaseNotesId") . " = " . $this->db->escape($main_data[0]["iReleaseNotesId"]) . " AND " . $this->db->protect("mrnd.iReleaseNoteDetailsId") . " NOT IN ('" . implode("','", $child_id_arr) . "')";
                            if ($relation_module["extra_cond"] != "") {
                                $extra_del .= " AND " . $relation_module["extra_cond"];
                            }
                            $child_del_arr["child_module"] = $child_module;
                            $child_del_arr["extra_cond"] = $extra_del;
                            $res = $this->childDataDelete($child_del_arr, true);
                            foreach ((array) $child_id_arr as $chKey => $chVal) {
                                $child_save_arr["child_module"] = $child_module;
                                $child_save_arr["index"] = $chKey;
                                $child_save_arr["id"] = $chVal;
                                $child_save_arr["data"] = $child_module_post;
                                $child_save_arr["main_data"] = $main_data;
                                $child_res = $this->childDataSave($child_save_arr, true);
                                if (!$child_res["success"]) {
                                    $ret_arr["message"] = $child_res["message"];
                                    $ret_arr["success"] = 2;
                                }
                            }
                            break;
                    }
                }
            }
        } catch (Exception $e) {
            $ret_arr["message"] = $e->getMessage();
            $ret_arr["success"] = 0;
        }
        $ret_arr['mod_enc_url']['add'] = $this->mod_enc_url['add'];
        $ret_arr['mod_enc_url']['index'] = $this->mod_enc_url['index'];
        $ret_arr['red_type'] = 'List';
        $this->filter->getPageFlowURL($ret_arr, $this->module_config, $params_arr, $id, $data);

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
        if ($this->general->allowStripSlashes()) {
            $filters = stripslashes($filters);
        }
        $filters = json_decode($filters, TRUE);
        $extra_cond = '';
        $search_mode = $search_join = $search_alias = 'No';
        if ($all_row_selected == "true" && in_array($operartor, array("del", "status"))) {
            $search_mode = ($operartor == "del") ? "Delete" : "Update";
            $search_join = $search_alias = "Yes";
            $config_arr['module_name'] = $this->module_name;
            $config_arr['list_config'] = $this->release_notes_model->getListConfiguration();
            $config_arr['form_config'] = $this->release_notes_model->getFormConfiguration();
            $config_arr['table_name'] = $this->release_notes_model->table_name;
            $config_arr['table_alias'] = $this->release_notes_model->table_alias;
            $filter_main = $this->filter->applyFilter($filters, $config_arr, $search_mode);
            $filter_left = $this->filter->applyLeftFilter($filters, $config_arr, $search_mode);
            $filter_range = $this->filter->applyRangeFilter($filters, $config_arr, $search_mode);
            if ($filter_main != "") {
                $extra_cond .= ($extra_cond != "") ? " AND (" . $filter_main . ")" : $filter_main;
            }
            if ($filter_left != "") {
                $extra_cond .= ($extra_cond != "") ? " AND (" . $filter_left . ")" : $filter_left;
            }
            if ($filter_range != "") {
                $extra_cond .= ($extra_cond != "") ? " AND (" . $filter_range . ")" : $filter_range;
            }
        }
        if ($search_alias == "Yes") {
            $primary_field = $this->release_notes_model->table_alias . "." . $this->release_notes_model->primary_key;
        } else {
            $primary_field = $this->release_notes_model->primary_key;
        }
        if (is_array($primary_ids)) {
            $pk_condition = $this->db->protect($primary_field) . " IN ('" . implode("','", $primary_ids) . "')";
        } elseif (intval($primary_ids) > 0) {
            $pk_condition = $this->db->protect($primary_field) . " = " . $this->db->escape($primary_ids);
        } else {
            $pk_condition = FALSE;
        }
        if ($pk_condition) {
            $extra_cond .= ($extra_cond != "") ? " AND (" . $pk_condition . ")" : $pk_condition;
        }
        $data_arr = $save_data_arr = array();
        try {
            switch ($operartor) {
                case 'del':
                    $mode = "Delete";
                    if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                        $del_access = $this->filter->checkAccessCapability("release_notes_delete", TRUE);
                    } else {
                        $del_access = $this->filter->getModuleWiseAccess("release_notes", "Delete", TRUE, TRUE);
                    }
                    if (!$del_access) {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_DELETE_THESE_DETAILS_C46_C46_C33'));
                    }
                    if ($search_mode == "No" && $pk_condition == FALSE) {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_DELETION_THIS_RECORD_C46_C46_C33'));
                    }
                    $params_arr = $this->_request_params();

                    $success = $this->release_notes_model->delete($extra_cond, $search_alias, $search_join);
                    if (!$success) {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_DELETION_THIS_RECORD_C46_C46_C33'));
                    }
                    $message = $this->general->processMessageLabel('ACTION_RECORD_C40S_C41_DELETED_SUCCESSFULLY_C46_C46_C33');
                    break;
                case 'edit':
                    $mode = "Update";
                    if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                        $edit_access = $this->filter->checkAccessCapability("release_notes_update", TRUE);
                    } else {
                        $edit_access = $this->filter->getModuleWiseAccess("release_notes", "Update", TRUE, TRUE);
                    }
                    if (!$edit_access) {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                    }
                    $post_name = $params_arr['name'];
                    $post_val = is_array($params_arr['value']) ? implode(",", $params_arr['value']) : $params_arr['value'];

                    $list_config = $this->release_notes_model->getListConfiguration($post_name);
                    $form_config = $this->release_notes_model->getFormConfiguration($list_config['source_field']);
                    if (!is_array($form_config) || count($form_config) == 0) {
                        throw new Exception($this->general->processMessageLabel('ACTION_FORM_CONFIGURING_NOT_DONE_C46_C46_C33'));
                    }
                    if (in_array($form_config['type'], array("date", "date_and_time", "time", 'phone_number'))) {
                        $post_val = $this->filter->formatActionData($post_val, $form_config);
                    }
                    if ($form_config["encrypt"] == "Yes") {
                        $post_val = $this->general->encryptDataMethod($post_val, $form_config["enctype"]);
                    }
                    $field_name = $form_config['field_name'];
                    $unique_name = $form_config['name'];

                    $unique_arr = array();

                    $unique_arr[$field_name] = $post_val;
                    if (in_array($field_name, $this->release_notes_model->unique_fields)) {
                        $unique_exists = $this->release_notes_model->checkRecordExists($this->release_notes_model->unique_fields, $unique_arr, $primary_ids, "Update", $this->release_notes_model->unique_type);
                        if ($unique_exists) {
                            $error_msg = $this->general->processMessageLabel('ACTION_RECORD_ALREADY_EXISTS_WITH_THESE_DETAILS_OF_VERSION_NUMBER_C46_C46_C33');
                            if ($error_msg == "") {
                                $error_msg = "Record already exists with these details of Version Number";
                            }
                            throw new Exception($error_msg);
                        }
                    }

                    $data_arr[$field_name] = $post_val;
                    $success = $this->release_notes_model->update($data_arr, intval($primary_ids));
                    $message = $this->general->processMessageLabel('ACTION_RECORD_SUCCESSFULLY_UPDATED_C46_C46_C33');
                    if (!$success) {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPDATING_OF_THIS_RECORD_C46_C46_C33'));
                    }
                    break;
                case 'status':
                    $mode = "Status";
                    if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                        $edit_access = $this->filter->checkAccessCapability("release_notes_update", TRUE);
                    } else {
                        $edit_access = $this->filter->getModuleWiseAccess("release_notes", "Update", TRUE, TRUE);
                    }
                    if (!$edit_access) {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                    }
                    if ($search_mode == "No" && $pk_condition == FALSE) {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_DELETION_THIS_RECORD_C46_C46_C33'));
                    }
                    $status_field = "";
                    if ($status_field == "") {
                        throw new Exception($this->general->processMessageLabel('ACTION_FORM_CONFIGURING_NOT_DONE_C46_C46_C33'));
                    }
                    if ($search_mode == "Yes" || $search_alias == "Yes") {
                        $field_name = $this->release_notes_model->table_alias . ".";
                    } else {
                        $field_name = $status_field;
                    }
                    $data_arr[$field_name] = $params_arr['status'];
                    $success = $this->release_notes_model->update($data_arr, $extra_cond, $search_alias, $search_join);
                    if (!$success) {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_MODIFYING_THESE_RECORDS_C46_C46_C33'));
                    }
                    $message = $this->general->processMessageLabel('ACTION_RECORD_C40S_C41_MODIFIED_SUCCESSFULLY_C46_C46_C33');
                    break;
            }
            $ret_arr['success'] = "true";
            $ret_arr['message'] = $message;
        } catch (Exception $e) {
            $ret_arr["success"] = "false";
            $ret_arr["message"] = $e->getMessage();
        }
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    /**
     * processConfiguration method is used to process add and edit permissions for grid intialization
     */
    protected function processConfiguration(&$list_config = array(), $isAdd = TRUE, $isEdit = TRUE, $runCombo = FALSE)
    {
        if (!is_array($list_config) || count($list_config) == 0) {
            return $list_config;
        }
        $count_arr = array();
        foreach ((array) $list_config as $key => $val) {
            if (!$isAdd) {
                $list_config[$key]["addable"] = "No";
            }
            if (!$isEdit) {
                $list_config[$key]["editable"] = "No";
            }

            $source_field = $val['source_field'];
            $dropdown_arr = $this->dropdown_arr[$source_field];
            if (is_array($dropdown_arr) && in_array($val['type'], array("dropdown", "radio_buttons", "checkboxes", "multi_select_dropdown"))) {
                $count_arr[$key]['ajax'] = "No";
                $count_arr[$key]['json'] = "No";
                $count_arr[$key]['data'] = array();
                $combo_arr = FALSE;
                if ($dropdown_arr['auto'] == "Yes") {
                    $combo_arr = $this->getSourceOptions($source_field, "Search", '', array(), '', 'count');
                    if ($combo_arr[0]['tot'] > $this->dropdown_limit) {
                        $count_arr[$key]['ajax'] = "Yes";
                    }
                }
                if ($runCombo == TRUE) {
                    if (in_array($dropdown_arr['type'], array("enum", "phpfn"))) {
                        $data_arr = $this->getSourceOptions($source_field, "Search");
                        $json_arr = $this->filter->makeArrayDropdown($data_arr);
                        $count_arr[$key]['json'] = "Yes";
                        $count_arr[$key]['data'] = json_encode($json_arr);
                    } else {
                        if ($dropdown_arr['opt_group'] != "Yes") {
                            if ($combo_arr == FALSE) {
                                $combo_arr = $this->getSourceOptions($source_field, "Search", '', array(), '', 'count');
                            }
                            if ($combo_arr[0]['tot'] < $this->search_combo_limit) {
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
        if (!is_array($combo_config) || count($combo_config) == 0) {
            return $data_arr;
        }
        $type = $combo_config['type'];
        switch ($type) {
            case 'enum':
                $data_arr = is_array($combo_config['values']) ? $combo_config['values'] : array();
                break;
            case 'token':
                if ($combo_config['parent_src'] == "Yes" && in_array($mode, array("Add", "Update", "Auto"))) {
                    $source_field = $combo_config['source_field'];
                    $target_field = $combo_config['target_field'];
                    if (in_array($mode, array("Update", "Auto")) || $data[$source_field] != "") {
                        $parent_src = (is_array($data[$source_field])) ? $data[$source_field] : explode(",", $data[$source_field]);
                        $extra_cond = $this->db->protect($target_field) . " IN ('" . implode("','", $parent_src) . "')";
                    } elseif ($mode == "Add") {
                        $extra_cond = $this->db->protect($target_field) . " = ''";
                    }
                    $extra = (trim($extra) != "") ? $extra . " AND " . $extra_cond : $extra_cond;
                }
                $data_arr = $this->filter->getTableLevelDropdown($combo_config, $id, $extra, $rtype);
                break;
            case 'table':
                if ($combo_config['parent_src'] == "Yes" && in_array($mode, array("Add", "Update", "Auto"))) {
                    $source_field = $combo_config['source_field'];
                    $target_field = $combo_config['target_field'];
                    if (in_array($mode, array("Update", "Auto")) || $data[$source_field] != "") {
                        $parent_src = (is_array($data[$source_field])) ? $data[$source_field] : explode(",", $data[$source_field]);
                        $extra_cond = $this->db->protect($target_field) . " IN ('" . implode("','", $parent_src) . "')";
                    } elseif ($mode == "Add") {
                        $extra_cond = $this->db->protect($target_field) . " = ''";
                    }
                    $extra = (trim($extra) != "") ? $extra . " AND " . $extra_cond : $extra_cond;
                }
                if ($combo_config['parent_child'] == "Yes" && $combo_config['nlevel_child'] == "Yes") {
                    $combo_config['main_table'] = $this->release_notes_model->table_name;
                    $data_arr = $this->filter->getTreeLevelDropdown($combo_config, $id, $extra, $rtype);
                } else {
                    if ($combo_config['parent_child'] == "Yes" && $combo_config['parent_field'] != "") {
                        $parent_field = $combo_config['parent_field'];
                        $extra_cond = "(" . $this->db->protect($parent_field) . " = '0' OR " . $this->db->protect($parent_field) . " = '' OR " . $this->db->protect($parent_field) . " IS NULL )";
                        if ($mode == "Update" || ($mode == "Search" && $id > 0)) {
                            $extra_cond .= " AND " . $this->db->protect($combo_config['field_key']) . " <> " . $this->db->escape($id);
                        }
                        $extra = (trim($extra) != "") ? $extra . " AND " . $extra_cond : $extra_cond;
                    }
                    $data_arr = $this->filter->getTableLevelDropdown($combo_config, $id, $extra, $rtype);
                }
                break;
            case 'phpfn':
                $phpfunc = $combo_config['function'];
                $parent_src = '';
                if ($combo_config['parent_src'] == "Yes" && in_array($mode, array("Add", "Update", "Auto"))) {
                    $source_field = $combo_config['source_field'];
                    if (in_array($mode, array("Update", "Auto")) || $data[$source_field] != "") {
                        $parent_src = $data[$source_field];
                    }
                }
                if (substr($phpfunc, 0, 12) == 'controller::' && substr($phpfunc, 12) !== FALSE) {
                    $phpfunc = substr($phpfunc, 12);
                    if (method_exists($this, $phpfunc)) {
                        $data_arr = $this->$phpfunc($data[$name], $mode, $id, $data, $parent_src, $this->term);
                    }
                } elseif (substr($phpfunc, 0, 7) == 'model::' && substr($phpfunc, 7) !== FALSE) {
                    $phpfunc = substr($phpfunc, 7);
                    if (method_exists($this->release_notes_model, $phpfunc)) {
                        $data_arr = $this->release_notes_model->$phpfunc($data[$name], $mode, $id, $data, $parent_src, $this->term);
                    }
                } elseif (method_exists($this->general, $phpfunc)) {
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

        $switchto_fields = $this->release_notes_model->switchto_fields;
        $extra_cond = $this->release_notes_model->extra_cond;

        $concat_fields = $this->db->concat_cast($switchto_fields);
        $search_cond = "(LOWER(" . $concat_fields . ") LIKE '" . $this->db->escape_like_str($term) . "%' OR LOWER(" . $concat_fields . ") LIKE '% " . $this->db->escape_like_str($term) . "%')";
        $extra_cond = ($extra_cond == "") ? $search_cond : $extra_cond . " AND " . $search_cond;

        $switch_arr = $this->release_notes_model->getSwitchTo($extra_cond);
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
        $config_arr = $this->release_notes_model->getListConfiguration($alias_name);
        $source_field = $config_arr['source_field'];
        $combo_config = $this->dropdown_arr[$source_field];
        $data_arr = array();
        if ($mode == "Update") {
            $data_arr = $this->release_notes_model->getData(intval($id));
        }
        $combo_arr = $this->getSourceOptions($source_field, $mode, $id, $data_arr[0]);
        if ($rformat == "json") {
            $html_str = $this->filter->getChosenAutoJSON($combo_arr, $combo_config, TRUE, "grid");
        } else {
            if ($combo_config['opt_group'] == "Yes") {
                $combo_arr = $this->filter->makeOPTDropdown($combo_arr);
            } else {
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

        try {
            $params_arr = $this->params_arr;
            $child_module = $params_arr["child_module"];
            $row_index = $params_arr["incNo"];
            $mode = ($params_arr["mode"] == "Update") ? "Update" : "Add";
            $recMode = ($params_arr["recMode"] == "Update") ? "Update" : "Add";
            $rmPopup = ($params_arr["rmPopup"] == "Yes") ? "Yes" : "No";
            $id = $params_arr["id"];
            $child_data = $child_func = $child_elem = array();
            switch ($child_module) {
                case "release_notes_details":
                    $this->load->module("tools/release_notes_details");
                    $this->load->model("tools/release_notes_details_model");
                    $child_config["parent_module"] = "release_notes";
                    $child_config["child_module"] = $child_module;
                    $child_config["mode"] = $recMode;
                    if ($recMode == "Update") {
                        $child_mod_data = $this->release_notes_details_model->getData(intval($id), "", "", "", "", "Yes");
                        $child_config["data"][$row_index] = $child_mod_data[0];
                    }
                    $child_config["row_index"] = $row_index;
                    $child_config["perform"] = array(
                        "data",
                        "options",
                        "config",
                    );
                    $module_arr = $this->release_notes_details->getRelationModule($child_config);
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
                    $child_module_add_file = "release_notes_release_notes_details_add_more";
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
        } catch (Exception $e) {
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

        try {
            $params_arr = $this->params_arr;
            $raw_post_data = $this->input->get_post(NULL, FALSE);
            if ($called_func == TRUE) {
                $child_module = $config_arr["child_module"];
                $index = $config_arr["index"];
                $id = $config_arr["id"];
                $child_post = $config_arr["data"];
                $main_data = $config_arr["main_data"];
            } else {
                $child_module = $params_arr["child_module"];
                $index = $params_arr["index"];
                $id = $params_arr["id"];
                $child_post = $params_arr["child"][$child_module];
            }
            switch ($child_module) {
                case "release_notes_details":
                    $this->load->module("tools/release_notes_details");
                    $this->load->model("tools/release_notes_details_model");
                    $child_config["parent_module"] = "release_notes";
                    $child_config["perform"] = array(
                        "config",
                    );
                    $module_arr = $this->release_notes_details->getRelationModule($child_config);
                    $form_config = $module_arr["form_config"];
                    $data_arr = $this->release_notes_details_model->getData(intval($id));
                    $mode = (is_array($data_arr) && count($data_arr) > 0) ? "Update" : "Add";
                    if ($called_func == TRUE) {
                        $params_arr["parID"] = $main_data[0]["iReleaseNotesId"];
                    }

                    $child_data = $save_data_arr = $file_data = array();
                    $mrnd_release_notes_id = $child_post["mrnd_release_notes_id"][$index];
                    $mrnd_title = $child_post["mrnd_title"][$index];
                    $mrnd_description = $child_post["mrnd_description"][$index];
                    $mrnd_version_status = $child_post["mrnd_version_status"][$index];
                    $mrnd_date_added = $child_post["mrnd_date_added"][$index];
                    $mrnd_added_by = $child_post["mrnd_added_by"][$index];
                    $mrnd_date_updated = $child_post["mrnd_date_updated"][$index];
                    $mrnd_updated_by = $child_post["mrnd_updated_by"][$index];

                    $child_data["iReleaseNotesId"] = $mrnd_release_notes_id;
                    $child_data["vTitle"] = $mrnd_title;
                    $child_data["tDescription"] = $raw_post_data["child"][$child_module]["mrnd_description"][$index];
                    $child_data["eVersionStatus"] = $mrnd_version_status;
                    $child_data["dDateAdded"] = $this->filter->formatActionData($mrnd_date_added, $form_config["mrnd_date_added"]);
                    $child_data["iAddedBy"] = $mrnd_added_by;
                    $child_data["dDateUpdated"] = $this->filter->formatActionData($mrnd_date_updated, $form_config["mrnd_date_updated"]);
                    $child_data["iUpdatedBy"] = $mrnd_updated_by;

                    $child_data["iReleaseNotesId"] = $params_arr["parID"];
                    if ($mode == "Add") {
                        $id = $res = $this->release_notes_details_model->insert($child_data);
                        if (intval($id) > 0) {
                            $data["iReleaseNoteDetailsId"] = $id;
                            $save_data_arr["iReleaseNoteDetailsId"] = $child_data["iReleaseNoteDetailsId"] = $id;
                            $msg = $this->general->processMessageLabel('ACTION_RECORD_ADDED_SUCCESSFULLY_C46_C46_C33');
                        } else {
                            throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_ADDING_RECORD_C46_C46_C33'));
                        }
                    } elseif ($mode == "Update") {
                        $res = $this->release_notes_details_model->update($child_data, intval($id));
                        if (intval($res) > 0) {
                            $data["iReleaseNoteDetailsId"] = $id;
                            $save_data_arr["iReleaseNoteDetailsId"] = $child_data["iReleaseNoteDetailsId"] = $id;
                            $msg = $this->general->processMessageLabel('ACTION_RECORD_SUCCESSFULLY_UPDATED_C46_C46_C33');
                        } else {
                            throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPDATING_OF_THIS_RECORD_C46_C46_C33'));
                        }
                    }

                    $success = 1;
                    break;
            }
        } catch (Exception $e) {
            $success = 0;
            $msg = $e->getMessage();
        }
        $res_arr["success"] = $success;
        $res_arr["message"] = $msg;
        if ($called_func == TRUE) {
            $res_arr["id"] = $id;
            return $res_arr;
        } else {
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

        try {
            $params_arr = $this->params_arr;
            if ($called_func == TRUE) {
                $child_module = $config_arr["child_module"];
                $where_cond = $config_arr["extra_cond"];
            } else {
                $child_module = $params_arr["child_module"];
                $where_cond = $id = intval($params_arr["id"]);
            }
            switch ($child_module) {
                case "release_notes_details":
                    $this->load->module("tools/release_notes_details");
                    $this->load->model("tools/release_notes_details_model");

                    $parent_join_arr = array(
                        "joins" => array(
                            array(
                                "table_name" => "mod_release_notes",
                                "table_alias" => "mrn",
                                "field_name" => "iReleaseNotesId",
                                "rel_table_name" => "mod_release_note_details",
                                "rel_table_alias" => "mrnd",
                                "rel_field_name" => "iReleaseNotesId",
                                "join_type" => "left",
                            )
                        )
                    );
                    $res = $this->release_notes_details_model->delete($where_cond, "Yes", $parent_join_arr);
                    if (!$res) {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_DELETION_THIS_RECORD_C46_C46_C33'));
                    }
                    $msg = $this->general->processMessageLabel('ACTION_RECORD_C40S_C41_DELETED_SUCCESSFULLY_C46_C46_C33');
                    $success = 1;
                    break;
            }
        } catch (Exception $e) {
            $success = 0;
            $msg = $e->getMessage();
        }
        $res_arr["success"] = $success;
        $res_arr["message"] = $msg;
        if ($called_func == TRUE) {
            return $res_arr;
        } else {
            echo json_encode($res_arr);
            $this->skip_template_view();
        }
    }
    
    /**
     * printRecord method is used to print current record from grid or add/update form
     */
    public function printRecord()
    {
        $params_arr = $this->params_arr;
        $id = $params_arr['id'];
        $layout = $params_arr['layout'];
        $pPage = $params_arr['pPage'];
        $data = array();
        $data_arr = $this->release_notes_model->processPrintData(intval($id), '', '', '', '', 'Yes');

        $extra_cond = $this->db->protect("mrn.iReleaseNotesId")." = ".$this->db->escape($id);
        $switch_arr = $this->release_notes_model->getSwitchTo($extra_cond);
        $recName = $switch_arr[0]['val'];

        $list_config = $this->release_notes_model->getListConfiguration();
        $form_config = $this->release_notes_model->getFormConfiguration();
        $data_config['module_config'] = $this->module_config;
        $data_config['list_config'] = $list_config;
        $data_config['form_config'] = $form_config;
        $data_config['dropdown_arr'] = $this->dropdown_arr;
        $data_config['table_name'] = $this->release_notes_model->table_name;
        $data_config['table_alias'] = $this->release_notes_model->table_alias;
        $data_config['primary_key'] = $this->release_notes_model->primary_key;

        $data_arr = $this->listing->getDataForList($data_arr, $data_config, "PExport");
        $data = $data_arr[0];

        $type = 'record';
        $layout = ($layout != "") ? $layout : "defrec";
        $layout_combo = $this->module_config['print_layouts']['record'];
        $this->dropdown->combo("array", "vSwitchPrint", $layout_combo, $layout);

        $extra_qstr .= $this->general->getRequestURLParams(TRUE);
        $extra_hstr .= $this->general->getRequestHASHParams();

        $render_arr = array(
            'id' => $id,
            'data' => $data,
            'type' => $type,
            'pPage' => $pPage,
            'recName' => $recName,
            'layout' => $layout,

            "relation_data" => $relation_data,
            'layout_combo' => $layout_combo,
            'folder_name' => $this->folder_name,
            'module_name' => $this->module_name,
            'mod_enc_url' => $this->mod_enc_url,
            'mod_enc_mode' => $this->mod_enc_mode,
            'extra_qstr' => $extra_qstr,
            'extra_hstr' => $extra_hstr,
        );
        if ($params_arr['layout'] != "")
        {
            $parse_html .= $this->parser->parse("release_notes_print_layout_".$layout.".tpl", $render_arr, TRUE);
            echo $parse_html;
            $this->skip_template_view();
        }
        else
        {
            $this->smarty->assign($render_arr);
            $this->loadView("release_notes_print");
        }
    }
    /**
     * printListing method is used to print listing records from grid
     */
    public function printListing()
    {
        $extra_qstr = $extra_hstr = '';
        $params_arr = $this->params_arr;
        $layout = $params_arr['layout'];
        $pPage = $params_arr['pPage'];

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
        $list_config = $this->release_notes_model->getListConfiguration();
        $form_config = $this->release_notes_model->getFormConfiguration();
        $table_name = $this->release_notes_model->table_name;
        $table_alias = $this->release_notes_modeltable_alias;
        $primary_key = $this->release_notes_model->primary_key;
        $extra_cond = $this->release_notes_model->extra_cond;
        $groupby_cond = $this->release_notes_model->groupby_cond;
        $having_cond = $this->release_notes_model->having_cond;
        $orderby_cond = $this->release_notes_model->orderby_cond;

        $print_config = array();
        if ($selected == "true")
        {
            $print_config['id'] = $id;
        }
        $print_config['page'] = $page;
        $print_config['rowlimit'] = $rowlimit;
        $print_config['sidx'] = $sidx;
        $print_config['sord'] = $sord;
        $print_config['sdef'] = $sdef;
        $print_config['filters'] = $filters;
        $print_config['export_mode'] = $export_mode;
        $print_config['module_config'] = $this->module_config;
        $print_config['list_config'] = $list_config;
        $print_config['form_config'] = $form_config;
        $print_config['dropdown_arr'] = $this->dropdown_arr;
        $print_config['table_name'] = $table_name;
        $print_config['table_alias'] = $table_alias;
        $print_config['primary_key'] = $primary_key;
        $print_config['extra_cond'] = $extra_cond;
        $print_config['group_by'] = $groupby_cond;
        $print_config['having_cond'] = $having_cond;
        $print_config['order_by'] = $orderby_cond;

        $data = $this->release_notes_model->getPrintData($print_config);
        $data = $this->listing->getDataForList($data, $print_config, "PExport", array());

        $type = 'listing';
        $layout = ($layout != "") ? $layout : "deflist";
        $layout_combo = $this->module_config['print_layouts']['listing'];
        $this->dropdown->combo("array", "vSwitchPrint", $layout_combo, $layout);

        $extra_qstr .= $this->general->getRequestURLParams(TRUE);
        $extra_hstr .= $this->general->getRequestHASHParams();

        $render_arr = array(
            'data' => $data,
            'type' => $type,
            'pPage' => $pPage,
            'layout' => $layout,
            'layout_combo' => $layout_combo,
            'folder_name' => $this->folder_name,
            'module_name' => $this->module_name,
            'mod_enc_url' => $this->mod_enc_url,
            'mod_enc_mode' => $this->mod_enc_mode,
            'mod_enc_url' => $this->mod_enc_url,
            'extra_qstr' => $extra_qstr,
            'extra_hstr' => $extra_hstr,
        );
        if ($params_arr['layout'] != "")
        {
            $parse_html .= $this->parser->parse("release_notes_print_layout_".$layout.".tpl", $render_arr, TRUE);
            echo $parse_html;
            $this->skip_template_view();
        }
        else
        {
            $this->smarty->assign($render_arr);
            $this->loadView("release_notes_print_list");
        }
    }
    
    public function preview()
    {
        $this->release_notes_model->printReleaseNotesList();
        try {
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                $list_access = $this->filter->checkAccessCapability("release_notes_list", TRUE);
            } else {
                $list_access = $this->filter->getModuleWiseAccess("release_notes", "List", TRUE, TRUE);
            }
            if (!$list_access) {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }

            $release_data = array();
            $release_notes = $this->release_notes_model->getReleaseNotes();
            if (is_array($release_notes) && count($release_notes) > 0) {
                foreach ($release_notes as $note) {
                    $release_data['release_notes'][$note['version_no']] = array(
                        'notes_id' => $note['notes_id'],
                        'version_number' => $note['version_no'],
                        'release_status' => $note['release_status'],
                        'release_date' => $this->general->dateSystemFormat($note['release_date'])
                    );
                }
                foreach ($release_notes as $details) {
                    $classname = 'tag-other';
                    switch ($details['version_status']) {
                        case 'New Feature':
                            $classname = 'tag-new';
                            break;
                        case 'Improvement':
                            $classname = 'tag-changed';
                            break;
                        case 'Bug Fixes':
                            $classname = 'tag-fixed';
                            break;
                        default:
                            $classname = 'tag-' . strtolower(str_replace(" ", "-", $details['version_status']));
                            break;
                    }
                    $release_data['release_details'][$details['version_no']][] = array(
                        'notes_id' => $details['notes_id'],
                        'title' => $details['title'],
                        'status' => $details['version_status'],
                        'description' => $details['description'],
                        'class_name' => $classname
                    );
                }
            }
            $versions_data = $this->release_notes_model->getMobileVersions();
            foreach ($versions_data as $v) {
                $release_data['release_notes'][$v['vVersionNumber']]['app_versions'][] = $v;
            }
            $render_data['release_id'] = $this->params_arr['release_id'];
            $render_data['module_name'] = $this->module_name;
            $render_data['release_notes'] = $release_data['release_notes'];
            $render_data['release_details'] = $release_data['release_details'];
            $this->smarty->assign($render_data);
            $this->loadView("release_notes_preview");
        } catch (Exception $e) {
            $render_arr['err_message'] = $e->getMessage();
            $this->smarty->assign($render_arr);
            $this->loadView($this->config->item('ADMIN_FORBIDDEN_TEMPLATE'));
        }
    }
}
