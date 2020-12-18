<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Grid Actions Controller
 *
 * @category admin
 *            
 * @package general
 * 
 * @subpackage controllers
 * 
 * @module Gridactions
 * 
 * @class Gridactions.php
 * 
 * @path application\admin\general\controllers\Gridactions.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Gridactions extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->_request_params();
    }

    /**
     * _request_params method is used to set post/get/request params.
     */
    private function _request_params()
    {
        $this->get_arr = is_array($this->input->get(NULL, TRUE)) ? $this->input->get(NULL, TRUE) : array();
        $this->post_arr = is_array($this->input->post(NULL, TRUE)) ? $this->input->post(NULL, TRUE) : array();
        $this->params_arr = array_merge($this->get_arr, $this->post_arr);
        return $this->params_arr;
    }

    /**
     * index method is used to intialize index page.
     */
    public function index()
    {
        
    }

    /**
     * grid_render_action method is used to get data from grid render settings.
     */
    public function grid_render_action()
    {
        $render_module = $this->general->getAdminDecodeURL($this->params_arr['render_module']);
        $render_type = $this->params_arr['render_type'];
        $render_value = $this->params_arr['render_value'];

        $render_html = $this->lang->line("GENERIC_INVALID_CONFIGURATION_SETTINGS");
        try {
            if ($render_type == "general") {
                $render_html = $this->general->$render_value($this->params_arr);
            } elseif ($render_type == "extended") {
                $render_module_arr = explode("/", $render_module);
                $module_folder = trim($render_module_arr[0]);
                $module_ctrl = trim($render_module_arr[1]);

                if (empty($module_folder) || empty($module_ctrl)) {
                    throw new Exception($this->lang->line("GENERIC_INVALID_CONFIGURATION_SETTINGS"));
                }

                $ctrl_prefix = $this->config->item("cu_controller_prx");
                $extend_ctrl = $ctrl_prefix . ucfirst($module_ctrl);
                $extend_init = strtolower($extend_ctrl);

                $this->load->module($module_folder . "/" . $module_ctrl);
                $this->load->module($module_folder . "/" . $extend_ctrl);
                $this->parser->addTemplateLocation(APPPATH . "admin/" . $module_folder . "/views/");

                if (is_object($this->$extend_init)) {
                    if (!method_exists($this->$extend_init, $render_value)) {
                        throw new Exception($this->lang->line("GENERIC_INVALID_CONFIGURATION_SETTINGS"));
                    }
                    $render_html = $this->$extend_init->$render_value($this->params_arr);
                } else {
                    if (!method_exists($this->$module_ctrl, $render_value)) {
                        throw new Exception($this->lang->line("GENERIC_INVALID_CONFIGURATION_SETTINGS"));
                    }
                    $render_html = $this->$module_ctrl->$render_value($this->params_arr);
                }
            }
        } catch (Exception $e) {
            $render_html = $e->getMessage();
        }
        echo $render_html;
        $this->skip_template_view();
    }

    /**
     * grid_submit_action method is used to perform grid settings action.
     */
    public function grid_submit_action()
    {
        $this->load->library('listing');
        $action_module = $this->general->getAdminDecodeURL($this->params_arr['action_module']);
        $action_type = $this->params_arr['action_type'];
        $action_value = $this->params_arr['action_value'];

        $action_arr = array(
            "success" => 0,
            "message" => $this->lang->line("GENERIC_INVALID_CONFIGURATION_SETTINGS")
        );
        try {
            if ($action_type == "general") {
                $action_arr = $this->general->$action_value($this->params_arr);
            } elseif ($action_type == "extended") {
                $action_module_arr = explode("/", $action_module);
                $module_folder = trim($action_module_arr[0]);
                $module_ctrl = trim($action_module_arr[1]);

                if (empty($module_folder) || empty($module_ctrl)) {
                    throw new Exception($this->lang->line("GENERIC_INVALID_CONFIGURATION_SETTINGS"));
                }

                $ctrl_prefix = $this->config->item("cu_controller_prx");
                $extend_ctrl = $ctrl_prefix . ucfirst($module_ctrl);
                $extend_init = strtolower($extend_ctrl);
                $this->load->module($module_folder . "/" . $module_ctrl);
                $this->load->module($module_folder . "/" . $extend_ctrl);
                $this->parser->addTemplateLocation(APPPATH . "admin/" . $module_folder . "/views/");

                if (is_object($this->$extend_init)) {
                    if (!method_exists($this->$extend_init, $action_value)) {
                        throw new Exception($this->lang->line("GENERIC_INVALID_CONFIGURATION_SETTINGS"));
                    }
                    $action_arr = $this->$extend_init->$action_value($this->params_arr);
                } else {
                    if (!method_exists($this->$module_ctrl, $action_value)) {
                        throw new Exception($this->lang->line("GENERIC_INVALID_CONFIGURATION_SETTINGS"));
                    }
                    $action_arr = $this->$module_ctrl->$action_value($this->params_arr);
                }
            } elseif ($action_type == "api") {
                $action_arr = $this->listing->callGridAPIMethod($action_value, $this->params_arr);
            }
        } catch (Exception $e) {
            $action_arr["success"] = 0;
            $action_arr['message'] = $e->getMessage();
        }
        echo json_encode($action_arr);
        $this->skip_template_view();
    }

    /**
     * grid_save_search method is used to perform grid save search.
     */
    public function grid_save_search()
    {
        $search_code = $this->params_arr['search_code'];
        $save_search_title = $this->params_arr['save_search_title'];
        $save_search_comments = $this->params_arr['save_search_comments'];
        $save_search_default = $this->params_arr['save_search_default'];
        $save_search_preferences = $this->params_arr['save_search_preferences'];

        $action_arr = array(
            "success" => 0,
            "message" => $this->lang->line("GENERIC_FAIELD_TO_SAVE_SEARCH_PREFERENCES")
        );
        try {

            if ($this->config->item('GRID_SAVE_SEARCH_ENABLE') != "Y") {
                throw new Exception($this->lang->line("GENERIC_SAVE_SEARCH_NOT_ENABLED_YET"));
            }

            $this->load->model('general/admin_preferences_model');
            $dup_check = array(
                'iAdminId' => $this->session->userdata("iAdminId"),
                'iGroupId' => $this->session->userdata("iGroupId"),
                'vCode' => $search_code,
                'vName' => $save_search_title
            );
            $rec_count = $this->admin_preferences_model->getRecordCount($dup_check);
            if ($rec_count > 0) {
                throw new Exception($this->lang->line("GENERIC_SEARCH_TITLE_ALREADY_EXISTS"));
            }

            $search_slug = url_title($save_search_title, "-", TRUE);
            $search_arr = array(
                'iAdminId' => $this->session->userdata("iAdminId"),
                'iGroupId' => $this->session->userdata("iGroupId"),
                'vCode' => $search_code,
                'vName' => $save_search_title,
                'vSlug' => $search_slug,
                'tValue' => $save_search_preferences,
                'eDefault' => ($save_search_default == "Yes") ? "Yes" : "No",
                'tComment' => $save_search_comments,
                'dtAddedDate' => date('Y-m-d H:i:s'),
                'dtModifiedDate' => date('Y-m-d H:i:s')
            );
            $search_id = $this->admin_preferences_model->insert($search_arr);

            if ($search_id) {

                if ($save_search_default == "Yes") {
                    $where_cond = array(
                        'iAdminId' => $this->session->userdata("iAdminId"),
                        'iGroupId' => $this->session->userdata("iGroupId"),
                        'vCode' => $search_code,
                        "iAdminPreferencesId" => array(
                            "field" => "iAdminPreferencesId",
                            "value" => $search_id,
                            "oper" => "ne"
                        )
                    );
                    $update_arr = array("eDefault" => "No");
                    $success = $this->admin_preferences_model->update($update_arr, $where_cond);
                }

                $action_arr['data'] = array(
                    'slug' => $search_slug
                );
                $action_arr['success'] = 1;
                $action_arr['message'] = $this->lang->line("GENERIC_SEARCH_PREFERENCES_SAVED_SUCESSFULLY");
            }
        } catch (Exception $e) {
            $action_arr["success"] = 0;
            $action_arr['message'] = $e->getMessage();
        }
        echo json_encode($action_arr);
        $this->skip_template_view();
    }

    /**
     * grid_update_search method is used to perform grid update search.
     */
    public function grid_update_search()
    {
        $search_code = $this->params_arr['search_code'];
        $search_id = $this->params_arr['search_id'];
        $value = $this->params_arr['value'];
        $type = $this->params_arr['type'];

        $action_arr = array(
            "success" => 0,
            "message" => $this->lang->line("GENERIC_FAIELD_TO_UPDATE_SAVED_SEARCH")
        );
        try {

            if ($this->config->item('GRID_SAVE_SEARCH_ENABLE') != "Y") {
                throw new Exception($this->lang->line("GENERIC_SAVE_SEARCH_NOT_ENABLED_YET"));
            }

            $this->load->model('general/admin_preferences_model');

            if ($type == "default") {
                $def_value = ($value == "No") ? "No" : "Yes";
                $where_cond = array(
                    'iAdminId' => $this->session->userdata("iAdminId"),
                    'iGroupId' => $this->session->userdata("iGroupId"),
                    'iAdminPreferencesId' => $search_id
                );
                $update_arr = array("eDefault" => $def_value);
                $success = $this->admin_preferences_model->update($update_arr, $where_cond);

                if ($success) {
                    if ($def_value == "Yes") {
                        $where_cond = array(
                            'iAdminId' => $this->session->userdata("iAdminId"),
                            'iGroupId' => $this->session->userdata("iGroupId"),
                            'vCode' => $search_code,
                            "iAdminPreferencesId" => array(
                                "field" => "iAdminPreferencesId",
                                "value" => $search_id,
                                "oper" => "ne"
                            )
                        );
                        $update_arr = array("eDefault" => "No");
                        $success = $this->admin_preferences_model->update($update_arr, $where_cond);
                    }
                    $action_arr['success'] = 1;
                    $action_arr['message'] = $this->lang->line("GENERIC_SEARCH_SETTINGS_UPDATED_SUCCESSFULLY");
                }
            }
        } catch (Exception $e) {
            $action_arr["success"] = 0;
            $action_arr['message'] = $e->getMessage();
        }
        echo json_encode($action_arr);
        $this->skip_template_view();
    }

    /**
     * grid_delete_search method is used to perform grid delete search.
     */
    public function grid_delete_search()
    {
        $search_code = $this->params_arr['search_code'];
        $search_id = $this->params_arr['search_id'];

        $action_arr = array(
            "success" => 0,
            "message" => $this->lang->line("GENERIC_FAIELD_TO_DELETE_SAVED_SEARCH")
        );
        try {

            if ($this->config->item('GRID_SAVE_SEARCH_ENABLE') != "Y") {
                throw new Exception($this->lang->line("GENERIC_SAVE_SEARCH_NOT_ENABLED_YET"));
            }

            $this->load->model('general/admin_preferences_model');

            $where_cond = array(
                'iAdminId' => $this->session->userdata("iAdminId"),
                'iGroupId' => $this->session->userdata("iGroupId"),
                'iAdminPreferencesId' => $search_id
            );
            $success = $this->admin_preferences_model->delete($where_cond);

            if ($success) {
                $action_arr['success'] = 1;
                $action_arr['message'] = $this->lang->line("GENERIC_SAVED_SEARCH_DELETED_SUCESSFULLY");
            }
        } catch (Exception $e) {
            $action_arr["success"] = 0;
            $action_arr['message'] = $e->getMessage();
        }
        echo json_encode($action_arr);
        $this->skip_template_view();
    }

    /**
     * form_save_draft method is used to perform form save as draft
     */
    public function form_save_draft()
    {
        $draft_module = $this->params_arr['draft_module'];
        $form_data_arr = $this->input->get_post('form_data');
        $mode = $this->params_arr['mode'];
        $id = $this->params_arr['id'];

        $action_arr = array(
            "success" => 0,
            "message" => $this->lang->line("GENERIC_REQUEST_FAIELD")
        );
        try {
            $mode = ($mode == "Update") ? "Update" : "Add";
            $omit_form_items = array("id", "mode", "ctrl_prev_id", "ctrl_next_id", "extra_hstr", "ctrl_flow");
            $form_data_obj = array();
            if (is_array($form_data_arr) && count($form_data_arr) > 0) {
                foreach ($form_data_arr as $key => $val) {
                    if (!in_array($val['name'], $omit_form_items)) {
                        $form_data_obj[$val['name']] = $val['value'];
                    }
                }
            }
            $this->load->model('general/form_drafts_model');
            $dup_check = array(
                'mfd.iAdminId' => $this->session->userdata("iAdminId"),
                'mfd.iGroupId' => $this->session->userdata("iGroupId"),
                'mfd.vModule' => $draft_module,
                'mfd.eMode' => $mode
            );
            if ($mode == "Update") {
                $dup_check['mfd.iRecId'] = $id;
            }
            $dup_check['mfd.eStatus'] = 'Active';

            $dup_record = $this->form_drafts_model->getData($dup_check, array("mfd.iFormDraftsId"));

            $form_data_json = json_encode($form_data_obj);
            if (is_array($dup_record) && count($dup_record) > 0) {
                $update_draft_arr = array(
                    'tFormData' => $form_data_json,
                    'dtModifiedDate' => date('Y-m-d H:i:s')
                );
                $result = $this->form_drafts_model->update($update_draft_arr, $dup_record[0]['iFormDraftsId']);
                $draft_id = $dup_record[0]['iFormDraftsId'];
            } else {
                $insert_draft_arr = array(
                    'iAdminId' => $this->session->userdata("iAdminId"),
                    'iGroupId' => $this->session->userdata("iGroupId"),
                    'vModule' => $draft_module,
                    'eMode' => $mode,
                    'iRecId' => $id,
                    'tFormData' => $form_data_json,
                    'dtAddedDate' => date('Y-m-d H:i:s'),
                    'dtModifiedDate' => date('Y-m-d H:i:s'),
                    'eStatus' => 'Active'
                );
                $result = $draft_id = $this->form_drafts_model->insert($insert_draft_arr);
            }
            if ($result) {
                $action_arr['success'] = 1;
                $action_arr['message'] = $this->lang->line("GENERIC_REQUEST_COMPLETED");
                $action_arr['draft_id'] = $draft_id;
            }
        } catch (Exception $e) {
            $action_arr["success"] = 0;
            $action_arr['message'] = $e->getMessage();
        }
        echo json_encode($action_arr);
        $this->skip_template_view();
    }
}
