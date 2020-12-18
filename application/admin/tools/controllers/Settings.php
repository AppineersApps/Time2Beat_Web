<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Settings Controller
 *
 * @category admin
 *            
 * @package tools
 * 
 * @subpackage controllers
 * 
 * @module Settings
 * 
 * @class Settings.php
 * 
 * @path application\admin\tools\controllers\Settings.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Settings extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('filter');
        $this->date_format_config = array(
            "ADMIN_DATE_FORMAT",
            "ADMIN_DATE_TIME_FORMAT",
            "ADMIN_TIME_FORMAT"
        );
        $this->skip_xss_clean = array(
            "GOOGLE_ANALYTICS"
        );
        $this->file_upload_server = $this->config->item('FILE_UPLOAD_SERVER_LOCATION');
        $this->upload_folder = $this->config->item('upload_folder', 'settings_files_config');
        $this->aws_vars_list = $this->config->item('aws_vars_list', 'settings_files_config');
        $this->mod_url_cod = array(
            "settings_index",
            "settings_action",
            "settings_upload_files"
        );
        $this->mod_enc_arr = $this->general->getCustomEncryptURL($this->mod_url_cod, TRUE);
    }

    /**
     * index method is used to display settings update page.
     */
    public function index()
    {
        try {
            $type = $this->input->get_post('mode');
            if ($type == "") {
                $type = "Appearance";
            }

            if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                $access_list = array(
                    "settings_" . strtolower($type) . "_view",
                    "settings_" . strtolower($type) . "_update"
                );
                list($view_access, $edit_access) = $this->filter->checkAccessCapability($access_list, TRUE);
            } else {
                list($view_access, $edit_access) = $this->filter->getModuleWiseAccess("Settings", array("View", "Update"), FALSE, TRUE);
            }
            if (!$view_access && !$edit_access) {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }

            $extra_cond = $this->db->protect("m.vURL") . " = " . $this->db->escape("tools/settings/index|mode|" . $type);
            $this->general->trackCustomNavigation('List', 'Viewed', $this->mod_enc_arr['settings_index'], $extra_cond, $type, "mode|" . $this->general->getAdminEncodeURL($type));

            $render_arr = $aws_path_arr = $lang_fields = $curr_valid_arr = $curr_msg_arr = array();
            if ($this->file_upload_server == "amazon") {
                $aws_path_arr = $this->general->getAWSServerAccessPathURL($this->upload_folder);
            }
            $db_total = $this->systemsettings->getSettingsMaster("", "", $type, "eConfigType");

            $valid_str = '';
            if (is_array($db_total) && count($db_total) > 0) {
                foreach ((array) $db_total as $fld_key => $fld_val) {
                    if (!is_array($fld_val) || count($fld_val) == 0) {
                        continue;
                    }
                    foreach ((array) $fld_val as $vd_key => $vd_val) {
                        $value = trim($vd_val['vValue']);
                        $attrs = json_decode(trim($vd_val['tSettingAttr']), TRUE);
                        if ($vd_val["eDisplayType"] == 'selectbox') {
                            if ($vd_val["eSource"] == 'List') {
                                $source_value = explode(',', $vd_val['vSourceValue']);
                                $db_total[$fld_key][$vd_key]["_listSourceValue"] = $source_value;
                            } elseif ($vd_val["eSource"] == 'Query') {
                                $db_select_source_rs = $this->systemsettings->getQueryResult($vd_val['vSourceValue']);
                                $db_total[$fld_key][$vd_key]["_querySourceValue"] = $db_select_source_rs;
                            }
                            if ($vd_val["eSelectType"] == 'Multiple') {
                                $db_total[$fld_key][$vd_key]["_multiAttr"] = "multiple=true";
                                $db_total[$fld_key][$vd_key]["_nameAttr"] = $db_total[$fld_key][$vd_key]['vName'] . '[]';
                            } else {
                                $db_total[$fld_key][$vd_key]["_nameAttr"] = $db_total[$fld_key][$vd_key]['vName'];
                            }
                        } elseif ($vd_val["eDisplayType"] == 'file') {
                            $source_value = json_decode($vd_val['vSourceValue'], TRUE);
                            $file_size = ($source_value['FILE_SIZE']) ? $source_value['FILE_SIZE'] : 25600;
                            $file_ext_arr = array_filter(explode(",", $source_value['FILE_EXT']));
                            if (!is_array($file_ext_arr) || count($file_ext_arr) == 0) {
                                $file_ext_arr = $this->config->item('IMAGE_EXTENSION_ARR');
                            }
                            $file_ext = implode("|", $file_ext_arr);
                            $file_type = 'file';
                            $file_avail = 0;
                            $view_file_url = '';
                            if ($value != "") {
                                $ext_val = strtolower(end(explode(".", $value)));
                                if (in_array($ext_val, $this->config->item("IMAGE_EXTENSION_ARR"))) {
                                    $file_type = 'image';
                                }
                                if ($this->file_upload_server == "amazon" && in_array($vd_val['vName'], $this->aws_vars_list)) {
                                    $file_avail = 1;
                                    $view_file_url = $aws_path_arr['folder_url'] . $value;
                                } elseif (is_file($this->config->item("settings_files_path") . $value)) {
                                    $file_avail = 1;
                                    $view_file_url = $this->config->item("settings_files_url") . $value;
                                }
                            }
                            $db_total[$fld_key][$vd_key]["_fileData"] = array(
                                "name" => $vd_val['vName'],
                                "val" => $vd_val['vValue'],
                                "file_exist" => $file_avail,
                                "file_url" => $view_file_url,
                                "file_type" => $file_type,
                                "fwidth" => $this->config->item("ADMIN_DEFAULT_IMAGE_WIDTH"),
                                "fheight" => $this->config->item("ADMIN_DEFAULT_IMAGE_HEIGHT"),
                                "file_size" => $file_size,
                                "file_ext" => $file_ext
                            );
                        }
                        if (trim($vd_val['vValidateCode']) != "" && trim($vd_val['vValidateMessage']) != '') {
                            $rule = $vd_val['vName'];
                            $param_val = $vd_val['vValidateCode'];
                            $message = $vd_val['vValidateMessage'];
                            $curr_valid_arr[$rule] = $param_val;
                            $curr_msg_arr[$rule] = $message;
                            $valid_str .= " if (element.attr('name') == '" . $rule . "') { $('#" . $rule . "Err').html(error);} ";
                        }
                        if ($vd_val['eLang'] == 'Yes') {
                            $lang_fields[] = $vd_val['vName'];
                            $db_total[$fld_key][$vd_key]["_langAttribute"] = 'aria-multi-lingual="parent"';
                        }
                        if (is_array($attrs) && count($attrs) > 0) {
                            foreach ($attrs as $at_key => $at_val) {
                                if ($at_key == "class") {
                                    $db_total[$fld_key][$vd_key]["_fieldClass"] = $at_val;
                                } elseif ($at_key == "lang_class") {
                                    $db_total[$fld_key][$vd_key]["_langClass"] = $at_val;
                                } elseif ($at_key == "parent_class") {
                                    $db_total[$fld_key][$vd_key]["_parentClass"] = $at_val;
                                } else {
                                    $db_total[$fld_key][$vd_key]["_fieldAttr"] = $db_total[$fld_key][$vd_key]["_fieldAttr"] . " " . $at_key . '="' . $at_val . '"';
                                }
                            }
                        }
                    }
                }
            }

            $result_db_total = array();
            if (is_array($db_total[$type])) {
                $result_db_total = $this->general->arrayAssoc($db_total[$type], 'vGroupType');
            }

            $valid_rules = $valid_msg = array();
            foreach ($curr_valid_arr as $key => $val) {
                if (trim($val) != "") {
                    $valid_rules[] = "'" . $key . "' : " . $val;
                }
            }
            foreach ($curr_msg_arr as $key => $val) {
                if (trim($val) != "") {
                    $valid_msg[] = '"' . $key . '" :' . $val;
                }
            }

            if (is_array($valid_rules) && count($valid_rules) > 0) {
                $validate_rules = 'rules :{' . implode($valid_rules, ',') . '}, messages :{' . implode($valid_msg, ',') . '},';
                $validate_rules .= 'errorPlacement: function(error, element) { 
    		   ' . $valid_str . '
    		    }';
            }

            if (is_array($lang_fields) && count($lang_fields) > 0) {
                $extra_lang_cond = $this->db->protect("vName") . " IN ('" . implode("','", $lang_fields) . "')";
                $lang_data = $this->systemsettings->getLangData($extra_lang_cond);
                $render_arr['lang_fields'] = $lang_fields;
                $render_arr['lang_data'] = $lang_data;
                $render_arr['prlang'] = $this->config->item("PRIME_LANG");
                $render_arr['exlang_arr'] = $this->config->item("OTHER_LANG");
                $render_arr['lang_info'] = $this->config->item("LANG_INFO");
                $render_arr['dflang'] = $this->config->item("DEFAULT_LANG");
            }

            $render_arr['type'] = $type;
            $render_arr['db_total'] = $result_db_total;
            $render_arr['group_count'] = count($result_db_total);
            $render_arr['edit_access'] = $edit_access;
            $render_arr['validate_rules'] = $validate_rules;
            $render_arr["action_url"] = $this->mod_enc_arr["settings_action"];
            $render_arr["upload_url"] = $this->mod_enc_arr["settings_upload_files"];
            $render_arr['date_format_config'] = $this->date_format_config;

            $this->loadView("settings", $render_arr);
        } catch (Exception $e) {
            $render_arr['err_message'] = $e->getMessage();
            $this->smarty->assign($render_arr);
            $this->loadView($this->config->item('ADMIN_FORBIDDEN_TEMPLATE'));
        }
    }

    /**
     * settings_action method is save to project settings.
     */
    public function settings_action()
    {
        try {
            $type = $this->input->post('type');

            if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                $edit_access = $this->filter->checkAccessCapability("settings_" . strtolower($type) . "_update", TRUE);
            } else {
                $edit_access = $this->filter->getModuleWiseAccess("Settings", "Update", FALSE, TRUE);
            }

            if (!$edit_access) {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
            }

            $aws_path_arr = array();
            if ($this->file_upload_server == "amazon") {
                $aws_path_arr = $this->general->getAWSServerAccessPathURL($this->upload_folder);
            }

            $fields_arr = "vName, vValue, vDefValue, eDisplayType, eLang";
            $extra_cond = $this->db->protect("eDisplayType") . " <> " . $this->db->escape("readonly");
            $db_setting_rs = $this->systemsettings->getSettingsMaster($fields_arr, $extra_cond, $type);

            for ($i = 0; $i < count($db_setting_rs); $i++) {
                $lang_data = $update_arr = array();
                $field_name = $db_setting_rs[$i]["vName"];
                $def_value = $db_setting_rs[$i]["vDefValue"];
                if ($type == "Prices") {
                    $post_val = $this->input->get_post($field_name, TRUE);
                    $value = element('Value', $post_val);
                    $update_arr['vValue'] = $value;
                    $update_arr['eSelectType'] = element('eSelectType', $post_val);
                    $update_arr['eSource'] = element('eSource', $post_val);
                } else {
                    if (in_array($field_name, $this->skip_xss_clean)) {
                        $post_val = $this->input->get_post($field_name);
                    } else {
                        $post_val = $this->input->get_post($field_name, TRUE);
                    }
                    if ($db_setting_rs[$i]["eDisplayType"] == 'checkbox') {
                        $value = (isset($post_val) && $post_val != "") ? "Y" : "N";
                    } elseif ($db_setting_rs[$i]["eDisplayType"] == 'selectbox') {
                        $value = (is_array($post_val)) ? implode("|", $post_val) : $post_val;
                    } elseif ($db_setting_rs[$i]["eDisplayType"] == 'file') {
                        $value = trim($post_val);
                        $oldfield_name = "old_" . $field_name;
                        $old_value = trim($this->input->get_post($oldfield_name));
                        $tmp_path = $this->config->item('admin_upload_temp_path');
                        if ($old_value != $value && is_file($tmp_path . $value)) {
                            $file_upload = TRUE;
                            if ($this->file_upload_server == "amazon" && in_array($field_name, $this->aws_vars_list)) {
                                $file_result = $this->general->uploadAWSData($tmp_path . $value, $this->upload_folder, $value);
                                if ($file_result == FALSE) {
                                    $file_upload = FALSE;
                                }
                            } else {
                                $settings_files_path = $this->config->item("settings_files_path");
                                $this->general->createUploadFolderIfNotExists($this->upload_folder);
                                if (!copy($tmp_path . $value, $settings_files_path . $value)) {
                                    $file_upload = FALSE;
                                } else {
                                    if (is_file($settings_files_path . $old_value)) {
                                        unlink($settings_files_path . $old_value);
                                    }
                                }
                            }
                            if ($file_upload = TRUE && is_file($tmp_path . $value)) {
                                unlink($tmp_path . $value);
                            }
                        }
                    } elseif ($db_setting_rs[$i]["eDisplayType"] == 'editor') {
                        $value = $this->input->get_post($field_name);
                    } else {
                        $value = $post_val;
                    }
                    if (!($value != "" && $value != "-9")) {
                        $value = $def_value;
                    }
                    $update_arr['vValue'] = $value;
                    $lang_data['vValue'] = $value;
                    $lang_keys["vValue"] = $field_name;
                }
                $extra_cond = $this->db->protect("vName") . " = " . $this->db->escape($field_name);
                $db_update = $this->systemsettings->updateSetting($update_arr, $extra_cond);
                if (!$db_update) {
                    $var_msg = $this->general->processMessageLabel('ACTION_FALIURE_IN_UPDATING_OF_GENERAL_SETTINGS_C46_C46_C33');
                    throw new Exception($var_msg);
                }
                $eLang = $db_setting_rs[$i]["eLang"];
                if ($eLang == 'Yes' && is_array($lang_data) && count($lang_data) > 0) {
                    $extra_lang_cond = $this->db->protect("vName") . " = " . $this->db->escape($field_name);
                    $db_lang_data = $this->systemsettings->getLangData($extra_lang_cond);
                    $db_lang_data = (is_array($db_lang_data) && count($db_lang_data) > 0) ? $db_lang_data : array();
                    $prlang = $this->config->item("PRIME_LANG");
                    $exlang_arr = $this->config->item("OTHER_LANG");
                    // primary language operations
                    $primary_arr = $lang_data;
                    if (isset($db_lang_data[$prlang])) {
                        $extra_lang_cond = $this->db->protect("vName") . " = " . $this->db->escape($field_name) . " AND " . $this->db->protect("vLangCode") . " = " . $this->db->escape($prlang);
                        $this->systemsettings->updateLang($primary_arr, $extra_lang_cond);
                    } else {
                        $primary_arr["vName"] = $field_name;
                        $primary_arr["vLangCode"] = $prlang;
                        $this->systemsettings->insertLang($primary_arr);
                    }
                    // other language operations
                    foreach ((array) $exlang_arr as $mlKey => $mlVal) {
                        $other_arr = array();
                        foreach ((array) $lang_keys as $lfKey => $lfVal) {
                            $post_lang_data = $this->input->get_post("lang" . $lfVal);
                            $other_arr[$lfKey] = $post_lang_data[$mlVal];
                        }
                        if (isset($db_lang_data[$mlVal])) {
                            $extra_lang_cond = $this->db->protect("vName") . " = " . $this->db->escape($field_name) . " AND " . $this->db->protect("vLangCode") . " = " . $this->db->escape($mlVal);
                            $this->systemsettings->updateLang($other_arr, $extra_lang_cond);
                        } else {
                            $other_arr["vName"] = $field_name;
                            $other_arr["vLangCode"] = $mlVal;
                            $this->systemsettings->insertLang($other_arr);
                        }
                    }
                }
            }

            if ($file_upload === FALSE) {
                throw new Exception($this->general->processMessageLabel("ACTION_FAILURE_IN_UPLOADING_C46_C46_C33"));
            }

            $var_msg = $this->general->processMessageLabel('ACTION_GENERAL_SETTINGS_UPDATED_SUCCESSFULLY_C46_C46_C33');
            $ret_arr['success'] = 1;
            $ret_arr['type'] = $type;
        } catch (Exception $e) {
            $var_msg = $e->getMessage();
            $ret_arr['success'] = 0;
        }

        $extra_cond = $this->db->protect("m.vURL") . " = " . $this->db->escape("tools/settings/index|mode|" . $type);
        $this->general->trackCustomNavigation('List', 'Modified', $this->mod_enc_arr['settings_index'], $extra_cond, $type, "mode|" . $this->general->getAdminEncodeURL($type));
        $ret_arr['message'] = $var_msg;
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    /**
     * uploadSettingFiles method is used to upload settings files or images.
     */
    public function uploadSettingFiles()
    {
        $actionType = $this->input->get_post('actionType', TRUE);
        $setting_name = $this->input->get_post('vSettingName', TRUE);
        if ($actionType == "upload") {
            $this->load->library('upload');
            $this->general->createUploadFolderIfNotExists('__temp');
            $temp_folder_path = $this->config->item('admin_upload_temp_path');
            $temp_folder_url = $this->config->item('admin_upload_temp_url');

            $old_data = $this->input->get_post('oldFile');
            $upload_files = $_FILES['Filedata'];
            list($file_name, $ext) = $this->general->get_file_attributes($upload_files["name"]);

            $upload_config['upload_path'] = $temp_folder_path;
            $upload_config['allowed_types'] = '*';
            $upload_config['max_size'] = 30720;
            $upload_config['file_name'] = $file_name;
            $this->upload->initialize($upload_config);

            try {
                if ($upload_files['name'] == "") {
                    throw new Exception($this->general->processMessageLabel('ACTION_UPLOAD_FILE_NOT_FOUND_C46_C46_C33'));
                }
                if (in_array($ext, $this->config->item("IMAGE_EXTENSION_ARR"))) {
                    $fileType = "image";
                } else {
                    $fileType = "file";
                }
                if (!$this->upload->do_upload('Filedata')) {
                    $upload_error = $this->upload->display_errors('', '');
                    throw new Exception($upload_error);
                } else {
                    $data = $this->upload->data();
                    $res_msg = $error_code = "FILE_UPLOAD_OK";
                }

                $file_name = $data['file_name'];
                $ret_arr['success'] = 1;
                $ret_arr['message'] = $res_msg;
                $ret_arr['uploadfile'] = $file_name;
                $ret_arr['oldfile'] = $file_name;
                $ret_arr['fileType'] = $fileType;
                $ret_arr['width'] = $this->config->item("ADMIN_DEFAULT_IMAGE_WIDTH");
                $ret_arr['height'] = $this->config->item("ADMIN_DEFAULT_IMAGE_HEIGHT");
                $ret_arr['fileURL'] = $temp_folder_url . $file_name;
                $ret_arr['resized'] = 0;
                if($fileType == "image"){
                    $ret_arr['imgURL'] = $this->general->resize_image($temp_folder_url . $file_name, $ret_arr['width'], $ret_arr['height']);
                    $ret_arr['resized'] = 1;
                }
                
                if (is_file($temp_folder_path . $old_data) && trim($old_data) != "") {
                    unlink($temp_folder_path . $old_data);
                }
            } catch (Exception $e) {
                $ret_arr['success'] = 0;
                $ret_arr['message'] = $e->getMessage();
            }
        } else {
            $fields_arr = "vName, vValue, eConfigType";
            $extra_cond = $this->db->protect("vName") . " = " . $this->db->escape($setting_name);
            $db_setting_rs = $this->systemsettings->getSettingsMaster($fields_arr, $extra_cond, '');

            try {
                if (!is_array($db_setting_rs) || count($db_setting_rs) == 0) {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_FILE_DELETION_C46_C46_C33'));
                }

                if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                    $edit_access = $this->filter->checkAccessCapability("settings_" . strtolower($db_setting_rs[0]['eConfigType']) . "_update", TRUE);
                } else {
                    $edit_access = $this->filter->getModuleWiseAccess("Settings", "Update", FALSE, TRUE);
                }
                if (!$edit_access) {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_DELETE_THESE_DETAILS_C46_C46_C33'));
                }

                $value = $db_setting_rs[0]["vValue"];
                $del_filepath_tmp = $temp_folder_path . $value;
                if (is_file($del_filepath_tmp)) {
                    unlink($del_filepath_tmp);
                }
                $del_filepath = $this->config->item("settings_files_path") . $value;
                if (is_file($del_filepath)) {
                    unlink($del_filepath);
                }

                $update_arr = array();
                $update_arr['vValue'] = '';
                $extra_cond = $this->db->protect("vName") . " = " . $this->db->escape($setting_name);
                $res = $this->systemsettings->updateSetting($update_arr, $extra_cond);
                if (!$res) {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_FILE_DELETION_C46_C46_C33'));
                }

                $ret_arr['success'] = 1;
                $ret_arr['message'] = $this->general->processMessageLabel('ACTION_FILE_DELETED_SUCCESSFULLY_C46_C46_C33');
            } catch (Exception $e) {
                $ret_arr['success'] = 0;
                $ret_arr['message'] = $e->getMessage();
            }
        }

        echo json_encode($ret_arr);
        $this->skip_template_view();
    }
}
