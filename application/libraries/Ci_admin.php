<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Admin Language Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module Language
 * 
 * @class Ci_admin.php
 * 
 * @path application\libraries\Ci_admin.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Ci_admin
{

    protected $CI;

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function addMultiLingualFile()
    {
        $multi_lingual_project = $this->CI->config->item('MULTI_LINGUAL_PROJECT');
        if ($multi_lingual_project == "Yes") {
            $default_lang = $this->CI->session->userdata("DEFAULT_LANG");
            $default_lang_folder = strtolower($default_lang);
            if (is_file(APPPATH . "language" . DS . $default_lang_folder . DS . "general_lang.php")) {
                $this->CI->lang->load('general', $default_lang_folder);
            } else {
                $this->CI->lang->load('general', "en");
            }
        } else {
            $this->CI->lang->load('general', "en");
        }
    }
}

/* End of file Ci_admin.php */
/* Location: ./application/libraries/Ci_admin.php */