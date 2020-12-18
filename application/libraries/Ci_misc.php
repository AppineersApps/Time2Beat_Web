<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Admin Misc Library
 *
 * @category libraries
 *
 * @package libraries
 *
 * @module Misc
 *
 * @class Ci_misc.php
 *
 * @path application\libraries\Ci_misc.php
 *
 * @version 4.0
 *
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Ci_misc
{

    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }
    /*
     * Code will be generated dynamically
     * Please do not write or change the content below this line
     * Five hashes must be there on either side of string.
     */

    public function getModuleArray()
    {
        $db_app_list = array();
        #####GENERATED_LANGMODULES_FUNCTION_START#####
       $db_app_list = array(
                "admin"=>$this->CI->lang->line("ADMIN_ADMIN"),
                "country"=>$this->CI->lang->line("COUNTRY_COUNTRY"),
                "feedback_management"=>$this->CI->lang->line("FEEDBACK_MANAGEMENT_FEEDBACK_MANAGEMENT"),
                "group"=>$this->CI->lang->line("GROUP_GROUP"),
                "loghistory"=>$this->CI->lang->line("LOGHISTORY_LOG_HISTORY"),
                "sms_template"=>$this->CI->lang->line("SMS_TEMPLATE_SMS_TEMPLATE"),
                "state"=>$this->CI->lang->line("STATE_STATE"),
                "staticpages"=>$this->CI->lang->line("STATICPAGES_STATIC_PAGES"),
                "systememails"=>$this->CI->lang->line("SYSTEMEMAILS_SYSTEM_EMAILS"),
                "users_management"=>$this->CI->lang->line("USERS_MANAGEMENT_USERS_MANAGEMENT")); 
            #####GENERATED_LANGMODULES_FUNCTION_END#####
        return $db_app_list;
    }
    /*
     * Code will be generated dynamically
     * Please do not write or change the content below this line
     * Five hashes must be there on either side of string.
     */

    public function getBulkEmailModules()
    {
        $module_arr = array();
        #####GENERATED_BULKEMAIL_MODULES_START#####
$module_arr["admin"] = $this->CI->lang->line("ADMIN_ADMIN");
        #####GENERATED_BULKEMAIL_MODULES_END#####
        return $module_arr;
    }

    public function getBulkEmailModuleFields($params_arr = array())
    {
        $module_name = $params_arr["module_name"];
        $field_arr = array();
        switch ($module_name) {
            #####GENERATED_BULKEMAIL_FIELDS_START#####

                    case "admin" :
                        break;
                            #####GENERATED_BULKEMAIL_FIELDS_END#####
        }
        return $field_arr;
    }

    public function getBulkEmailModuleData($module_name = "")
    {
        $data = array();
        switch ($module_name) {
            #####GENERATED_BULKEMAIL_DATA_START#####

        case "admin" :
            $this->CI->load->model("user/admin_model");
            $extra_cond = $this->CI->admin_model->extra_cond;
            $data = $this->CI->admin_model->getData($extra_cond, "", "", "", "", "Yes");
            break;
            #####GENERATED_BULKEMAIL_DATA_END#####
        }
        return $data;
    }

    public function getBulkEmailModuleListFields($module_name = "")
    {
        $data = array();
        switch ($module_name) {
            #####GENERATED_BULKEMAIL_LIST_FIELD_START#####

        case "admin" :
            $this->CI->load->model("user/admin_model");
            $data = $this->CI->admin_model->getListConfiguration();
            break;
            #####GENERATED_BULKEMAIL_LIST_FIELD_END#####
        }
        return $data;
    }

    public function getPushNotifyModules($params_arr = array())
    {
        $module_arr = array();
        #####GENERATED_PUSHNOTIFY_MODULES_START#####
#####GENERATED_PUSHNOTIFY_MODULES_END#####
        return $module_arr;
    }

    public function getPushNotifyModuleListFields($module_name = "")
    {
        $data = array();
        switch ($module_name) {
            #####GENERATED_PUSHNOTIFY_LIST_FIELD_START#####
#####GENERATED_PUSHNOTIFY_LIST_FIELD_END#####
        }
        return $data;
    }

    public function getPushNotifyModuleFields($params_arr = array())
    {
        $module_name = $params_arr["module_name"];
        $field_arr = array();
        switch ($module_name) {
            #####GENERATED_PUSHNOTIFY_FIELDS_START#####
#####GENERATED_PUSHNOTIFY_FIELDS_END#####
        }
        return $field_arr;
    }

    public function getPushNotifyModuleData($module_name = "")
    {
        $data = array();
        switch ($module_name) {
            #####GENERATED_PUSHNOTIFY_DATA_START#####
#####GENERATED_PUSHNOTIFY_DATA_END#####
        }
        return $data;
    }
}

/* End of file Ci_misc.php */
/* Location: ./application/libraries/Ci_misc.php */