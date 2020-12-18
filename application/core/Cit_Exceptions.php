<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Cit_Exceptions extends CI_Exceptions
{

    public function __construct()
    {
        parent::__construct();
    }

    // --------------------------------------------------------------------

    public function show_exception($exception)
    {
        $templates_path = config_item('error_views_path');
        if (empty($templates_path)) {
            $templates_path = VIEWPATH . 'errors' . DIRECTORY_SEPARATOR;
        }

        $message = $exception->getMessage();
        if (empty($message)) {
            $message = '(null)';
        }

        if (is_cli()) {
            $template = 'cli' . DIRECTORY_SEPARATOR . 'error_exception';
        } else {
            set_status_header(500);
            $template = 'html' . DIRECTORY_SEPARATOR . 'error_exception_cit';
        }

        if (ob_get_level() > $this->ob_level + 1) {
            ob_end_flush();
        }

        $errors = ob_get_contents();
        if (ob_get_length() > 0) {
            ob_end_clean();
        }
        $errors = str_replace(realpath(FCPATH), "", $errors);

        ob_start();
        include_once($templates_path . $template . '.php');
        $buffer = ob_get_contents();
        ob_end_clean();
        echo $errors . $buffer;
    }
    // --------------------------------------------------------------------

    /**
     * Native PHP error handler
     *
     * @param	int	$severity	Error level
     * @param	string	$message	Error message
     * @param	string	$filepath	File path
     * @param	int	$line		Line number
     * @return	string	Error page output
     */
    public function show_php_error($severity, $message, $filepath, $line)
    {
        $templates_path = config_item('error_views_path');
        if (empty($templates_path)) {
            $templates_path = VIEWPATH . 'errors' . DIRECTORY_SEPARATOR;
        }

        $severity = isset($this->levels[$severity]) ? $this->levels[$severity] : $severity;

        // For safety reasons we don't show the full file path in non-CLI requests
        if (!is_cli()) {
            $filepath = str_replace('\\', '/', $filepath);
            if (FALSE !== strpos($filepath, '/')) {
                $x = explode('/', $filepath);
                $filepath = $x[count($x) - 2] . '/' . end($x);
            }

            $template = 'html' . DIRECTORY_SEPARATOR . 'error_php_cit';
        } else {
            $template = 'cli' . DIRECTORY_SEPARATOR . 'error_php';
        }

        if (ob_get_level() > $this->ob_level + 1) {
            ob_end_flush();
        }

        $errors = ob_get_contents();
        if (ob_get_length() > 0) {
            ob_end_clean();
        }
        $errors = str_replace(realpath(FCPATH), "", $errors);

        ob_start();
        include_once($templates_path . $template . '.php');
        $buffer = ob_get_contents();
        ob_end_clean();
        echo $errors . $buffer;
    }
}

/* End of file Cit_Exceptions.php */
/* Location: ./application/core/Cit_Exceptions.php */