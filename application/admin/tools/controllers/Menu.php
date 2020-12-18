<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Menu Controller
 *
 * @category admin
 *
 * @package tools
 *
 * @subpackage controllers
 *
 * @module Menu
 *
 * @class Menu.php
 *
 * @path application\admin\tools\controllers\Menu.php
 *
 * @version 4.0
 *
 * @author CIT Dev Team
 *
 * @since 09.01.2016
 */
class Menu extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->mod_url_cod = array(
            "menu_index",
            "menu_status_list",
            "menu_capability_list",
            "menu_save_menu_data"
        );
        $this->mod_enc_url = $this->general->getCustomEncryptURL($this->mod_url_cod, true);
        $this->load->library('filter');
        $this->load->model('menu_model');
    }

    /**
     * index method is used for listing the mod_admin_menu data.
     */
    public function index()
    {
        try {
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                $access_list = array(
                    "menu_view",
                    "menu_title_update",
                    "menu_capability_update",
                    "menu_status_update",
                );
                list($view_access, $edit_title, $edit_capability, $edit_status) = $this->filter->checkAccessCapability($access_list, TRUE);
            } else {
                list($view_access, $edit_title) = $this->filter->getModuleWiseAccess("Menu", array("View", "Update"), FALSE, TRUE);
                if ($edit_title) {
                    $edit_capability = $edit_status = TRUE;
                }
            }
            if (!$view_access) {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }

            $menu_data = array();
            $parent_cond = "parent";
            $parent_data = $this->menu_model->getCapabilityData($parent_cond);
            $child_cond = "child";
            $child_data = $this->menu_model->getCapabilityData($child_cond);

            if (is_array($parent_data) && count($parent_data) > 0) {
                $admin_menu_arr = array();
                foreach ($parent_data as $key => $val) {
                    $admin_menu_arr[$val['iAdminMenuId']] = array(
                        'parent_admin_menu_id' => $val['iAdminMenuId'],
                        'parent_menu_display' => $val['vMenuDisplay'],
                        'parent_id' => $val['iParentId'],
                        'parent_url' => $val['vURL'],
                        'parent_icon' => $val['vIcon'],
                        'parent_status' => $val['eStatus'],
                        'parent_sequence_order' => $val['iSequenceOrder'],
                        'parent_capability_name' => $val['vCapabilityName'],
                        'parent_capability_code' => $val['vCapabilityCode'],
                        'parent_capability_type' => $val['eCapabilityType'],
                        'parent_capability_mode' => $val['vCapabilityMode'],
                        'parent_capability_added_by' => $val['eAddedBy'],
                        'children_arr' => array()
                    );
                }
                if (is_array($child_data) && count($child_data) > 0) {
                    foreach ($child_data as $key => $val) {
                        $children_arr = array(
                            'child_admin_menu_id' => $val['iAdminMenuId'],
                            'child_menu_display' => $val['vMenuDisplay'],
                            'parent_id' => $val['iParentId'],
                            'child_url' => $val['vURL'],
                            'child_icon' => $val['vIcon'],
                            'child_status' => $val['eStatus'],
                            'child_sequence_order' => $val['iSequenceOrder'],
                            'child_capability_name' => $val['vCapabilityName'],
                            'child_capability_code' => $val['vCapabilityCode'],
                            'child_capability_type' => $val['eCapabilityType'],
                            'child_capability_mode' => $val['vCapabilityMode'],
                            'child_capability_added_by' => $val['eAddedBy']
                        );
                        $admin_menu_arr[$val['iParentId']]['children_arr'][] = $children_arr;
                    }
                }
                $menu_data = array_values($admin_menu_arr);
            }

            $render_arr = array(
                'menu_data' => $menu_data,
                'edit_title' => $edit_title,
                'edit_capability' => $edit_capability,
                'edit_status' => $edit_status,
                'mod_enc_url' => $this->mod_enc_url
            );
            $this->smarty->assign($render_arr);
            $this->loadView('menu');
        } catch (Exception $e) {
            $render_arr['err_message'] = $e->getMessage();
            $this->smarty->assign($render_arr);
            $this->loadView($this->config->item('ADMIN_FORBIDDEN_TEMPLATE'));
        }
    }

    /**
     * getStatusData method is used for status dropdown.
     */
    public function getStatusList()
    {
        $dropdown_arr = array(
            'Active' => $this->lang->line('GENERIC_MENU_ACTIVE'),
            'Inactive' => $this->lang->line('GENERIC_MENU_INACTIVE')
        );
        echo json_encode($dropdown_arr);
        $this->skip_template_view();
    }

    /**
     * getCapabilityDropdown method is used for Capability dropdown getting from mod_capability_master.
     */
    public function getCapabilityList()
    {
        $result = $this->menu_model->getCapabilityDropdownData();
        if (is_array($result) && count($result) > 0) {
            foreach ($result as $val) {
                $dropdown_arr[] = array(
                    $val['capability_id'] => $val['capability_code']
                );
            }
            echo json_encode($dropdown_arr);
            $this->skip_template_view();
        }
    }

    /**
     * inlineEditAction method is used for store the inline edit data in db.
     */
    public function inlineSaveMenuData()
    {
        try {
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES")) {
                $access_list = array(
                    "menu_view",
                    "menu_title_update",
                    "menu_capability_update",
                    "menu_status_update",
                );
                list($edit_title, $edit_capability, $edit_status) = $this->filter->checkAccessCapability($access_list, TRUE);
            } else {
                $edit_title = $this->filter->getModuleWiseAccess("Menu", "Update", FALSE, TRUE);
                if ($edit_title) {
                    $edit_capability = $edit_status = TRUE;
                }
            }
            $data_arr = $this->input->get_post(NULL, TRUE);
            $id = $data_arr['id'];
            $field_name = $data_arr['name'];
            $new_value = $data_arr['value'];
            $response = array();
            switch ($field_name) {
                case 'menu_status':
                    if (!$edit_status) {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                    }
                    $data = array('eStatus' => $new_value);
                    $response = $this->menu_model->saveMenuData($id, $data);
                    break;
                case 'menu_title':
                    if (!$edit_title) {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                    }
                    $data = array('vMenuDisplay' => $new_value);
                    $lang_label = $this->general->getDisplayLabel('Generic', trim($new_value), 'label');
                    $result = $this->menu_model->checkLangugeLable($lang_label);
                    if (!$result['success']) {
                        $new_label_arr = array(
                            'vLabel' => $lang_label,
                            'vModule' => 'Generic',
                            'vPage' => 'Menu',
                            'eStatus' => 'Active'
                        );
                        $this->menu_model->addNewLanguageLabel($new_label_arr, trim($new_value));
                    }
                    $response = $this->menu_model->saveMenuData($id, $data);
                    break;
                case 'menu_capability':
                    if (!$edit_capability) {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                    }
                    $code = $this->menu_model->getCapabilityCode($new_value);
                    if ($code['success']) {
                        $data = array(
                            'vCapabilityCode' => $code['capability_code'],
                            'iCapabilityId' => $new_value
                        );
                        $response = $this->menu_model->saveMenuData($id, $data);
                    } else {
                        throw new Exception($this->general->processMessageLabel('GENERIC_ERROR_IN_UPDATION'));
                    }
                    break;
            }
            if (!$response['success']) {
                throw new Exception($this->general->processMessageLabel('GENERIC_ERROR_IN_UPDATION'));
            }
            $ret_arr['success'] = 1;
        } catch (Exception $e) {
            $ret_arr['success'] = 0;
            $ret_arr['message'] = $e->getMessage();
        }
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }
}
