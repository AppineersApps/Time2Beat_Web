<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Database Cache Class
 *
 * @category	Database
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/database/
 */
require_once BASEPATH . 'database/DB_cache.php';

class CIT_DB_Cache extends CI_DB_Cache {

    /**
     * Constructor
     *
     * @param	object	&$db
     * @return	void
     */
	public function __construct(&$db)
	{
        parent::__construct($db);
        
        $this->CI->load->library('ci_cache');
    }

    // --------------------------------------------------------------------

    /**
     * Retrieve a cached query
     *
     * The URI being requested will become the name of the cache sub-folder.
     * An MD5 hash of the SQL statement will become the cache file name.
     *
     * @param	string	$sql
     * @return	string
     */
	public function read($sql)
	{
        $filename = md5($sql);
        
        if (FALSE === ($this->CI->ci_cache->getQueryCache($sql, $filename))) 
        {
            return FALSE;
        }

        $filepath = $this->db->cachedir . $filename;

        if (FALSE === ($cachedata = file_get_contents($filepath))) 
        {
            return FALSE;
        }

        return unserialize($cachedata);
    }

    // --------------------------------------------------------------------

    /**
     * Write a query to a cache file
     *
     * @param	string	$sql
     * @param	object	$object
     * @return	bool
     */
    public function write($sql, $object) 
    {
        $dir_path = $this->db->cachedir;
        
        $filename = md5($sql);

        if ( ! is_dir($dir_path) &&  ! mkdir($dir_path, 0750))
        {
            return FALSE;
        }

        if (FALSE === ($this->CI->ci_cache->setQueryCache($sql, $filename)))
        {
            return FALSE;
        }

        if (write_file($dir_path . $filename, serialize($object)) === FALSE)
        {
            return FALSE;
        }

        chmod($dir_path . $filename, 0640);
        
        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Delete cache files within a particular directory
     *
     * @param	string	$segment_one
     * @param	string	$segment_two
     * @return	void
     */
    public function delete($segment_one = '', $segment_two = '')
    {
        $sql = $segment_one;
        
        $filename = md5($sql);
        
        $file_path = $this->db->cachedir . $filename;
        
        $this->CI->ci_cache->clearQueryCache($sql);

        if (file_exists($file_path))
        {
            unlink($file_path);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Delete all existing cache files
     *
     * @return	void
     */
    public function delete_all()
    {
        $this->CI->ci_cache->clearCacheFolder();
        
        delete_files($this->db->cachedir, TRUE, TRUE);
    }

}