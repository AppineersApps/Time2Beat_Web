<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Multilingual Controller
 *
 * @category admin
 *            
 * @package general
 * 
 * @subpackage controllers
 * 
 * @module Multilingual
 * 
 * @class Multilingual.php
 * 
 * @path application\admin\general\controllers\Multilingual.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Multilingual extends Cit_Controller
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
     * language_conversion method is used to get converted data from source language to destination(s) languages.
     */
    public function language_conversion()
    {
        $text_typ = $this->params_arr['type'];
        if ($text_typ == "html") {
            $text_val = $this->input->get_post("text");
        } else {
            $text_val = $this->params_arr['text'];
        }

        $src_lang = strtolower($this->params_arr['src']);
        $dest_lang_arr = $this->params_arr['dest'];
        $response_arr = array();
        if ($src_lang != "" && is_array($dest_lang_arr) && count($dest_lang_arr) > 0) {
            foreach ((array) $dest_lang_arr as $val) {
                $dest_lang = strtolower($val);
                $response = $this->general->languageTranslation($src_lang, $dest_lang, $text_val, $text_typ);
                $response_arr[$val] = $response;
            }
        }
        $enc_data = json_encode($response_arr);
        echo $enc_data;
        $this->skip_template_view();
    }

    /**
     * language_change method is used to change default language.
     */
    public function language_change()
    {
        $lang_val = $this->params_arr['langVal'];
        if ($lang_val != '') {
            $this->session->set_userdata('DEFAULT_LANG', $lang_val);
            echo 1;
        } else {
            echo 0;
        }
        $this->skip_template_view();
    }
}
