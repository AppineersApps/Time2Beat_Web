<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Extended Session Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module Session
 * 
 * @class Cit_Session.php
 * 
 * @path application\libraries\Cit_Session.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 23.01.2016
 */
class Cit_Session extends CI_Session
{

    protected $CI;

    public function __construct()
    {
        parent::__construct();

        $this->CI = &get_instance();
    }

    public function userdata($key = NULL)
    {
        $prefix = $this->session_prefix();

        if ($prefix == FALSE) {

            return parent::userdata($key);
        }

        if (isset($key)) {

            $cit_key = $prefix . $key;

            return isset($_SESSION[$cit_key]) ? $_SESSION[$cit_key] : NULL;
        } elseif (empty($_SESSION)) {

            return array();
        }

        $userdata = array();

        $_exclude = array_merge(
            array('__ci_vars'), $this->get_flash_keys(), $this->get_temp_keys()
        );

        foreach (array_keys($_SESSION) as $key) {

            if (!in_array($key, $_exclude, TRUE)) {

                if (substr($key, 0, strlen($prefix)) == $prefix) {

                    $cit_key = substr($key, strlen($prefix));

                    $userdata[$cit_key] = $_SESSION[$key];
                }
            }
        }

        return $userdata;
    }

    public function set_userdata($data, $value = NULL)
    {
        $prefix = $this->session_prefix();

        if ($prefix == FALSE) {

            return parent::set_userdata($data, $value);
        }

        if (is_array($data)) {

            foreach ($data as $key => &$value) {

                $cit_key = $prefix . $key;

                $_SESSION[$cit_key] = $value;
            }

            return;
        }

        $cit_key = $prefix . $data;

        $_SESSION[$cit_key] = $value;
    }

    public function unset_userdata($key)
    {
        $prefix = $this->session_prefix();

        if ($prefix == FALSE) {

            return parent::unset_userdata($key);
        }

        if (is_array($key)) {

            foreach ($key as $k) {

                $cit_key = $prefix . $k;

                unset($_SESSION[$cit_key]);
            }

            return;
        }

        $cit_key = $prefix . $key;

        unset($_SESSION[$cit_key]);
    }

    public function all_userdata()
    {
        $prefix = $this->session_prefix();

        if ($prefix == FALSE) {

            return parent::all_userdata();
        }

        return $this->userdata();
    }

    public function has_userdata($key)
    {
        $prefix = $this->session_prefix();

        if ($prefix == FALSE) {

            return parent::has_userdata($key);
        }

        $cit_key = $prefix . $key;

        return isset($_SESSION[$cit_key]);
    }

    public function flashdata($key = NULL)
    {
        $prefix = $this->session_prefix();

        if ($prefix == FALSE) {

            return parent::flashdata($key);
        }

        if (isset($key)) {

            $cit_key = $prefix . $key;

            return (isset($_SESSION['__ci_vars'], $_SESSION['__ci_vars'][$cit_key], $_SESSION[$cit_key]) &&
                !is_int($_SESSION['__ci_vars'][$cit_key])) ? $_SESSION[$cit_key] : NULL;
        }

        $flashdata = array();

        if (!empty($_SESSION['__ci_vars'])) {

            foreach ($_SESSION['__ci_vars'] as $key => &$value) {

                $cit_key = substr($key, strlen($prefix));

                is_int($value) OR $flashdata[$cit_key] = $_SESSION[$key];
            }
        }

        return $flashdata;
    }

    public function set_flashdata($data, $value = NULL)
    {
        $prefix = $this->session_prefix();

        if ($prefix == FALSE) {

            return parent::set_flashdata($data, $value);
        }

        $this->set_userdata($data, $value);

        $data_keys = is_array($data) ? array_keys($data) : array($data);

        $mark_keys = array();

        foreach ($data_keys as $val) {

            $mark_keys[] = $prefix . $val;
        }

        $this->mark_as_flash($mark_keys);
    }

    public function keep_flashdata($key)
    {
        $prefix = $this->session_prefix();
        
        $keep_keys = is_array($key) ? $key : array($key);

        $mark_keys = array();

        foreach ($keep_keys as $val) {

            $mark_keys[] = $prefix . $val;
        }

        $this->mark_as_flash($mark_keys);
    }
    
    public function unmark_flash($key)
	{
		if (empty($_SESSION['__ci_vars']))
		{
			return;
		}
        
        $prefix = $this->session_prefix();

        if ($prefix == FALSE) {

            return parent::unmark_flash($key);
        }

		is_array($key) OR $key = array($key);
        
		foreach ($key as $k)
		{
            $cit_key = $prefix . $k;
            
			if (isset($_SESSION['__ci_vars'][$cit_key]) && ! is_int($_SESSION['__ci_vars'][$cit_key]))
			{
				unset($_SESSION['__ci_vars'][$cit_key]);
			}
		}

		if (empty($_SESSION['__ci_vars']))
		{
			unset($_SESSION['__ci_vars']);
		}
	}

    public function tempdata($key = NULL)
    {
        $prefix = $this->session_prefix();

        if ($prefix == FALSE) {

            return parent::tempdata($key);
        }

        if (isset($key)) {

            $cit_key = $prefix . $key;

            return (isset($_SESSION['__ci_vars'], $_SESSION['__ci_vars'][$cit_key], $_SESSION[$cit_key]) &&
                is_int($_SESSION['__ci_vars'][$cit_key])) ? $_SESSION[$cit_key] : NULL;
        }

        $tempdata = array();

        if (!empty($_SESSION['__ci_vars'])) {

            foreach ($_SESSION['__ci_vars'] as $key => &$value) {

                $cit_key = substr($key, strlen($prefix));

                is_int($value) && $tempdata[$cit_key] = $_SESSION[$key];
            }
        }

        return $tempdata;
    }

    public function set_tempdata($data, $value = NULL, $ttl = 300)
    {
        $prefix = $this->session_prefix();

        if ($prefix == FALSE) {

            return parent::set_tempdata($data, $value, $ttl);
        }

        $this->set_userdata($data, $value);

        $data_keys = is_array($data) ? array_keys($data) : array($data);

        $mark_keys = array();

        foreach ($data_keys as $val) {

            $mark_keys[] = $prefix . $val;
        }

        $this->mark_as_temp($mark_keys, $ttl);
    }

    public function unset_tempdata($key)
    {
        $prefix = $this->session_prefix();

        if ($prefix == FALSE) {

            return parent::unset_tempdata($key);
        }

        $this->unmark_temp($prefix . $key);
    }

    public function unmark_temp($key)
	{
		if (empty($_SESSION['__ci_vars']))
		{
			return;
		}

        $prefix = $this->session_prefix();

        if ($prefix == FALSE) {

            return parent::unmark_temp($key);
        }
        
		is_array($key) OR $key = array($key);

		foreach ($key as $k)
		{
            $cit_key = $prefix . $key;
            
			if (isset($_SESSION['__ci_vars'][$cit_key]) && is_int($_SESSION['__ci_vars'][$cit_key]))
			{
				unset($_SESSION['__ci_vars'][$cit_key]);
			}
		}

		if (empty($_SESSION['__ci_vars']))
		{
			unset($_SESSION['__ci_vars']);
		}
	}
    
    public function session_prefix()
    {
        $sess_prefix = $this->CI->config->item('sess_prefix');

        if (empty($sess_prefix) || trim($sess_prefix) == "") {

            return FALSE;
        } else {

            return trim($sess_prefix);
        }
    }
}
