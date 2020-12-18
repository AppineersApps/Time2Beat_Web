<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Admin Local Storage Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module LocalStorage
 * 
 * @class Ci_local.php
 * 
 * @path application\libraries\Ci_local.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Ci_local
{

    protected $CI;
    protected $local_data_path = null;
    protected $local_gener_data = null;
    protected $local_admin_data = null;

    public function __construct()
    {
        $this->CI = & get_instance();
        if ($this->CI->config->item('is_admin') == 1) {
            $this->local_data_path = $this->CI->config->item('admin_upload_cache_path');
        }
    }

    public function load($id = '')
    {
        if (empty($id)) {
            return;
        }
        if ($id == -1) {
            if (is_array($this->local_gener_data)) {
                return TRUE;
            }
        } else {
            if (is_array($this->local_admin_data)) {
                return TRUE;
            }
        }
        $local_store_file = $this->local_data_path . md5($id);
        if (!is_file($local_store_file)) {
            return FALSE;
        }
        $local_store_ser = file_get_contents($local_store_file);
        $local_store_arr = unserialize($local_store_ser);
        if ($id == -1) {
            $this->local_gener_data = $local_store_arr;
        } else {
            $this->local_admin_data = $local_store_arr;
        }
        return TRUE;
    }

    public function read($item = '', $id = 0)
    {
        $this->load($id);
        if ($id == -1) {
            return isset($this->local_gener_data[$item]) ? $this->local_gener_data[$item] : '';
        } else {
            return isset($this->local_admin_data[$item]) ? $this->local_admin_data[$item] : '';
        }
    }

    public function write($item = '', $data = '', $id = 0)
    {
        $this->load($id);
        if ($id == -1) {
            $this->local_gener_data[$item] = $data;
        } else {
            $this->local_admin_data[$item] = $data;
        }
    }

    public function create($data = array(), $id = 0)
    {
        if (empty($id)) {
            return FALSE;
        }
        $content = serialize($data);
        $fp = fopen($this->local_data_path . md5($id), 'w+');
        if ($fp) {
            fwrite($fp, $content);
            fclose($fp);
        }
        return TRUE;
    }

    public function complete($id = 0)
    {
        if (is_array($this->local_gener_data)) {
            $this->create($this->local_gener_data, -1);
        }
        if (is_array($this->local_admin_data)) {
            $this->create($this->local_admin_data, $id);
        }
    }
}

/* End of file Ci_local.php */
/* Location: ./application/libraries/Ci_local.php */