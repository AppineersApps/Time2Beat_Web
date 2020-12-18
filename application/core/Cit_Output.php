<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * CI Smarty
 *
 * Smarty templating for Codeigniter
 *
 * Responsible for sending debug Smarty final output to browser (Smarty_Internal_Debug::display_debug) using debug console (pop-window)
 * (tks for Redn0x - http://www.smarty.net/docs/en/chapter.debugging.console.tpl)
 *
 * @package   CI Smarty
 * @subpackage Core
 * @author    Dwayne Charrington
 * @copyright 2015 Dwayne Charrington and Github contributors
 * @link      http://ilikekillnerds.com
 * @license   MIT
 * @version   3.0
 */
class Cit_Output extends CI_Output
{

    public function _display($output = '')
    {
        parent::_display($output);
        // If Smarty is active - NOTE: $this->output->enable_profiler(TRUE) active Smarty debug to simplify
        if (class_exists('CI_Controller') && class_exists('Smarty_Internal_Debug') && (config_item('smarty_debug') || $this->enable_profiler)) {
            $CI = & get_instance();
            Smarty_Internal_Debug::display_debug($CI->smarty);
        }
    }

    public function enableCompression()
    {
        $this->_compress_output = TRUE;
    }

    public function disableCompression()
    {
        $this->_compress_output = FALSE;
    }
}

/* End of file Cit_Output.php */
/* Location: ./application/core/Cit_Output.php */