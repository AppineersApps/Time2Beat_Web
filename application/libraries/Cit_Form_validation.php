<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Extended Form validation Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module FormValidation
 * 
 * @class Cit_Form_validation.php
 * 
 * @path application\libraries\Cit_Form_validation.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Cit_Form_validation extends CI_Form_validation
{

    protected $CI;

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
    }

    public function recaptcha_matches()
    {
        $this->CI->load->library('recaptcha');

//        $this->CI->config->load('recaptchaconfig');
//        $public_key = $this->CI->config->item('recaptcha_public_key');
//        $private_key = $this->CI->config->item('recaptcha_private_key');
        $response_field = $this->CI->input->post('recaptcha_response_field');
        $challenge_field = $this->CI->input->post('recaptcha_challenge_field');

        $response = $this->CI->recaptcha->recaptcha_check_answer($_SERVER['REMOTE_ADDR'], $challenge_field, $response_field);

        if ($response['is_valid']) {
            return TRUE;
        } else {
            $this->recaptcha_error = $response['error'];
            $this->set_message('recaptcha_matches', 'The %s is incorrect. Please try again.');
            return FALSE;
        }
    }

    public function citcaptcha_matches()
    {
        $this->CI->load->library('captcha');


        $cit_captcha_input = $this->CI->input->post('cit_captcha_input');


        $response = $this->CI->captcha->valid($cit_captcha_input);

        if ($response['is_valid']) {
            return TRUE;
        } else {
            $this->captcha_error = $response['error'];
            $this->set_message('cit_captcha_matches', 'The %s is incorrect. Please try again.');
            return FALSE;
        }
    }

    public function error_array()
    {
        return $this->_error_array;
    }

    public function set_value($field, $default = '')
    {
        return $this->_field_data[$field]['postdata'];
    }
}

/* End of file Cit_Form_validation.php */
/* Location: ./application/libraries/Cit_Form_validation.php */