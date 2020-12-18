<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Front Language Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module Language
 * 
 * @class Ci_front.php
 * 
 * @path application\libraries\Ci_front.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Ci_front
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
            $sess_lang_id = $this->CI->session->userdata("sess_lang_id");
            $sess_lang_folder = strtolower($sess_lang_id);
            if (is_file(APPPATH . "language" . DS . $sess_lang_folder . DS . "front_lang.php")) {
                $this->CI->lang->load('front', $sess_lang_folder);
            } elseif (is_file(APPPATH . "language" . DS . "en" . DS . "front_lang.php")) {
                $this->CI->lang->load('front', "en");
            }
        } elseif (is_file(APPPATH . "language" . DS . "en" . DS . "front_lang.php")) {
            $this->CI->lang->load('front', "en");
        }
    }
}

/* End of file Ci_front.php */
/* Location: ./application/libraries/Ci_front.php */