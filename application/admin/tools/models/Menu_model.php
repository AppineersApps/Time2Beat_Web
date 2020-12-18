<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Menu Model
 *
 * @category admin
 *
 * @package tools
 *
 * @subpackage models
 *
 * @module Menu
 *
 * @class Menu_model.php
 *
 * @path application\admin\tools\models\Menu_model.php
 *
 * @version 4.0
 *
 * @author CIT Dev Team
 *
 * @date 09.01.2016
 */
class Menu_model extends CI_Model
{

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * getCapabilityData method is used to get whole mod_admin_menu data to display in index page.
     */
    public function getCapabilityData($extra_cond = array())
    {
        $this->db->select('mam.iAdminMenuId as iAdminMenuId');
        $this->db->select('mam.iParentId as iParentId');
        $this->db->select('mam.vMenuDisplay as vMenuDisplay');
        $this->db->select('mam.vURL as vURL');
        $this->db->select('mam.vIcon as vIcon');
        $this->db->select('mam.eStatus as eStatus');
        $this->db->select('mam.iSequenceOrder as iSequenceOrder');
        $this->db->select('mcm.vCapabilityName as vCapabilityName');
        $this->db->select('mcm.vCapabilityCode as vCapabilityCode');
        $this->db->select('mcm.eCapabilityType as eCapabilityType');
        $this->db->select('mcm.vCapabilityMode as vCapabilityMode');
        $this->db->select('mcm.eAddedBy as eAddedBy');

        $this->db->from('mod_admin_menu as mam');
        $this->db->join('mod_capability_master as mcm', 'mcm.iCapabilityId = mam.iCapabilityId', 'left');

        if ($extra_cond == "parent") {
            $this->db->where('mam.iParentId', '0');
        } elseif ($extra_cond == "child") {
            $this->db->where('mam.iParentId >', '0');
        } elseif (is_array($extra_cond) && count($extra_cond) > 0) {
            $this->listing->addWhereFields($extra_cond);
        } elseif ($extra_cond != "") {
            if (is_numeric($extra_cond)) {
                $this->db->where('iAdminMenuId', $extra_cond);
            } else {
                $this->db->where($extra_cond, FALSE, FALSE);
            }
        }
        $this->db->order_by("mam.iSequenceOrder", "asc");
        $result_obj = $this->db->get();
        $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
        return $result_arr;
    }

    /**
     * saveMenuData method is used to set inline edit data to mod_admin_menu table.
     */
    public function saveMenuData($id = '', $data = array())
    {
        $return_arr['success'] = FALSE;
        if ($id > 0 && is_array($data) && count($data) > 0) {
            $this->db->where('iAdminMenuId', $id);
            $this->db->update('mod_admin_menu', $data);
            $return_arr['success'] = TRUE;
        }
        return $return_arr;
    }

    /**
     * getCapabilityCode method is used to get the capability code to store in mod_admin_menu table
     */
    public function getCapabilityCode($id = '')
    {
        $return_arr['success'] = FALSE;
        if ($id > 0) {
            $this->db->select('vCapabilityCode');
            $this->db->where('iCapabilityId', $id);
            $result = $this->db->get('mod_capability_master')->row_array();

            $return_arr['capability_code'] = $result['vCapabilityCode'];
            $return_arr['success'] = TRUE;
        }
        return $return_arr;
    }

    /**
     * checkLangugeLable method is used  to check language label exists or not.
     */
    public function checkLangugeLable($lang_label = '')
    {
        $return_arr['success'] = FALSE;
        if (isset($lang_label)) {
            $this->db->select('iLanguageLabelId as language_label_id');
            $this->db->where('vLabel', $lang_label);
            $response = $this->db->get('mod_language_label')->row_array();
            if (isset($response['language_label_id']) && $response['language_label_id'] > 0) {
                $return_arr['labelid'] = $response['language_label_id'];
                $return_arr['success'] = TRUE;
            }
        }

        return $return_arr;
    }

    /**
     * addNewLanguageLabel method is used to store the new language label in db.
     */
    public function addNewLanguageLabel($data_arr = array(), $title = '')
    {
        if (is_array($data_arr) && count($data_arr) > 0) {
            $this->db->insert('mod_language_label', $data_arr);
            $insert_id = $this->db->insert_id();
            if ($insert_id > 0) {
                $label_lang_arr = array(
                    'iLanguageLabelId' => $insert_id,
                    'vLangCode' => 'EN',
                    'vTitle' => $title
                );
                $this->db->insert('mod_language_label_lang', $label_lang_arr);
            }
        }
    }

    /**
     * getCapabilityDropdownData method is used to get the capability dropdown data from mod_capability_master table.
     */
    public function getCapabilityDropdownData()
    {
        $this->db->select('iCapabilityId as capability_id');
        $this->db->select('vCapabilityName as capability_name');
        $this->db->select('vCapabilityCode as capability_code');
        $this->db->where('eStatus', 'Active');

        $this->db->group_start();
        $this->db->where_in("eCapabilityType", array('Custom', 'Dashboard'));

        $this->db->or_group_start();
        $this->db->where("eCapabilityType", 'Module');
        $this->db->where_in("vCapabilityMode", array('List', 'Add', 'General'));
        $this->db->group_end();

        $this->db->group_end();

        $result_obj = $this->db->get('mod_capability_master');
        $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
        return $result_arr;
    }
}
