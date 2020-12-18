<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
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
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2016, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Database Driver Class
 *
 * This is the platform-independent base DB implementation class.
 * This class will not be called directly. Rather, the adapter
 * class for the specific database will extend and instantiate it.
 *
 * @package		CodeIgniter
 * @subpackage	Drivers
 * @category	Database
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/database/
 */
require_once BASEPATH . 'database/DB_driver.php';

abstract class CIT_DB_driver extends CI_DB_driver {

    /**
	 * Protect identifiers error flag
	 *
	 * @var	bool
	 */
	protected $_error_found		= FALSE;

	/**
	 * List of error messages
	 *
	 * @var	array[]
	 */
	protected $_error_messages	= array();
    
    /**
	 * Query modes
	 *
	 * A list of modes that queries took to fetch.
	 *
	 * @var	array
	 */
	public $query_modes		= array();
    
    // --------------------------------------------------------------------
    
    /**
     * Execute the query
     *
     * Accepts an SQL string as input and returns a result object upon
     * successful execution of a "read" type query. Returns boolean TRUE
     * upon successful execution of a "write" type query. Returns boolean
     * FALSE upon failure, and if the $db_debug variable is set to TRUE
     * will raise an error.
     *
     * @param	string	$sql
     * @param	array	$binds = FALSE		An array of binding data
     * @param	bool	$return_object = NULL
     * @return	mixed
     */
    public function query($sql, $binds = FALSE, $return_object = NULL, $_query_type = '', $_assoc_col = '')
    {
        if ($sql === '') 
        {
            log_message('error', 'Invalid query: '.$sql);
            $this->log_error('db_invalid_query', $sql);
            return ($this->db_debug) ? $this->display_error('db_invalid_query') : FALSE;
        }
        elseif ( ! is_bool($return_object))
        {
            $return_object = ! $this->is_write_type($sql);
        }

        // Verify table prefix and replace if necessary
        if ($this->dbprefix !== '' && $this->swap_pre !== '' && $this->dbprefix !== $this->swap_pre)
        {
			$sql = preg_replace('/(\W)'.$this->swap_pre.'(\S+?)/', '\\1'.$this->dbprefix.'\\2', $sql);
        }
        
        // Save the query for debugging
        if ($this->save_queries === TRUE)
        {
            $this->queries[] = $sql;
        }

        // Start the Query Timer
        $time_start = microtime(TRUE);

        // Is query caching enabled? If the query is a "read type"
        // we will load the caching class and return the previously
        // cached query if it exists
        if ($this->cache_on === TRUE && $return_object === TRUE && $this->_cache_init())
        {
            $this->load_rdriver();
            
            if (FALSE !== ($cache = $this->CACHE->read($sql)))
            {
                // Stop and aggregate the cache read results
                $time_end = microtime(TRUE);
                $this->benchmark += $time_end - $time_start;

                if ($this->save_queries === TRUE)
                {
                    $this->query_times[] = $time_end - $time_start;
                    
                    $this->query_modes[] = 'cache';
                }

                // Increment the query counter
                $this->query_count++;

                if ($this->return_type == 'obj')
                {
                    return $cache;
                }
                elseif ($this->is_insert_type($sql))
                {
                    return $cache->insert_id();
                }
                elseif ($this->is_select_count_type($sql))
                {
                    return $cache;
                }
                elseif ($this->is_select_single_type($sql))
                {
                    return $cache->result_single_array();
                }
                elseif ($this->is_select_combo_type($sql))
                {
                    return $cache->result_combo_array();
                }
                elseif ($_query_type == "Assoc")
                {
                    return $cache->result_assoc_array($_assoc_col);
                }
                elseif ($this->is_select_type($sql))
                {
                    return $cache;
                }
                else 
                {
                    return $cache;
                }
            }
        }

        // Compile binds if needed
        if ($binds !== FALSE) 
        {
            $sql = $this->compile_binds($sql, $binds);
        }

        // Run the Query
        if (FALSE === ($this->result_id = $this->simple_query($sql)))
        {
            if ($this->save_queries === TRUE)
            {
				$this->query_times[] = FALSE;
                
                $this->query_modes[] = 'database';
            }

            // This will trigger a rollback if transactions are being used
			if ($this->_trans_depth !== 0)
			{
                $this->_trans_status = FALSE;
			}

            // Grab the error now, as we might run some additional queries before displaying the error
            $error = $this->error();

            // Log errors
			log_message('error', 'Query error: '.$error['message'].' - Invalid query: '.$sql);

            $this->log_error(array('Error Number: ' . $error['code'], $error['message'], $sql));

            if ($this->db_debug)
            {
                // We call this function in order to roll-back queries
                // if transactions are enabled. If we don't call this here
                // the error message will trigger an exit, causing the
                // transactions to remain in limbo.
				while ($this->_trans_depth !== 0)
                {
                    $trans_depth = $this->_trans_depth;
                    $this->trans_complete();
                    if ($trans_depth === $this->_trans_depth)
                    {
                        log_message('error', 'Database: Failure during an automated transaction commit/rollback!');
                        break;
                    }
                }

                // Display errors
				return $this->display_error(array('Error Number: '.$error['code'], $error['message'], $sql));
            }

            return FALSE;
        }

        // Stop and aggregate the query time results
        $time_end = microtime(TRUE);
        $this->benchmark += $time_end - $time_start;

        if ($this->save_queries === TRUE)
        {
            $this->query_times[] = $time_end - $time_start;
            
            $this->query_modes[] = 'database';
        }

        // Increment the query counter
        $this->query_count++;

        // Will we have a result object instantiated? If not - we'll simply return TRUE
        if ($return_object !== TRUE)
        {
            // If caching is enabled we'll auto-cleanup any existing files related to this particular URI
            if ($this->cache_on === TRUE && $this->cache_autodel === TRUE && $this->_cache_init())
            {
				$this->CACHE->delete($sql);
            }
            //insert query will come here.
            if ($this->is_insert_type($sql))
            {
                return $this->conn_id->insert_id;
            } else {
                return TRUE;
            }

            return TRUE;
        }

        // Load and instantiate the result driver
		$driver		= $this->load_rdriver();
        $RES		= new $driver($this);

        $RET = $RES;

        if ($this->return_type == 'obj')
        {
            $RET = $RES;
        }
        elseif ($this->is_insert_type($sql))
        {
            $RET = $RES->insert_id();
        }
        elseif ($this->is_select_count_type($sql))
        {
            $RET = $RES;
        }
        elseif ($this->is_select_single_type($sql))
        {
            $RET = $RES->result_single_array();
        }
        elseif ($this->is_select_combo_type($sql))
        {
            $RET = $RES->result_combo_array();
        } 
        elseif ($_query_type == "Assoc")
        {
            $RET = $RES->result_assoc_array($_assoc_col);
        }
        elseif ($this->is_select_type($sql))
        {
            #$RET = $RES->result_array();
        }

        // Is query caching enabled? If so, we'll serialize the
        // result object and save it to a cache file.
        if ($this->cache_on === TRUE && $this->_cache_init())
        {
            // We'll create a new instance of the result object
            // only without the platform specific driver since
            // we can't use it with cached data (the query result
            // resource ID won't be any good once we've cached the
            // result object, so we'll have to compile the data
            // and save it)

            $CR = new CIT_DB_result($this);
            $CR->result_object = $RES->result_object();
            $CR->result_array = $RES->result_array();
            $CR->num_rows = $RES->num_rows();
            $CR->row_data = $RES->row_data;
            $CR->custom_result_object = $RES->custom_result_object;
            $CR->result_single_array = $RES->result_single_array;
            $CR->result_combo_array = $RES->result_combo_array;
            $CR->result_assoc_array = $RES->result_assoc_array;

            // Reset these since cached objects can not utilize resource IDs.
			$CR->conn_id		= NULL;
			$CR->result_id		= NULL;

            $this->CACHE->write($sql, $CR);
        }

		return $RET;
    }

    // --------------------------------------------------------------------

    /**
     * Load the result drivers
     *
     * @return	string	the name of the result class
     */
    public function load_rdriver()
    {
		$driver = 'CIT_DB_'.$this->dbdriver.'_result';

		if ( ! class_exists($driver, FALSE))
        {
            require_once(APPPATH.'database/DB_result.php');
			require_once(APPPATH.'database/drivers/'.$this->dbdriver.'/'.$this->dbdriver.'_result.php');
        }

        return $driver;
    }

	// --------------------------------------------------------------------

    /**
	 * Initialize the Cache Class
	 *
	 * @return	bool
	 */
    public function _cache_init()
    {
        if ( ! class_exists('CIT_DB_Cache', FALSE))
        {
            require_once(APPPATH . 'database/DB_cache.php');
        }
        elseif (is_object($this->CACHE))
        {
            return TRUE;
        }

        $this->CACHE = new CIT_DB_Cache($this); // pass db object to support multiple db connections and returned db objects
        return TRUE;
    }

    /******* Custom HB Created **/
    /**
     * Determines if a query is a "SELECT" type.
     *
     * @access	public
     * @param	string	An SQL query string
     * @return	boolean		
     */
    public function is_select_type($sql)
    {
        if ( ! preg_match('/^\s*"?(SELECT|)\s+/i', $sql))
        {
            return FALSE;
        }
        
        return TRUE;
    }

    /**
     * Determines if a query is a "SELECT COUNT" type.
     *
     * @access	public
     * @param	string	An SQL query string
     * @return	boolean		
     */
    public function is_select_count_type($sql)
    {
        if ( ! preg_match('/^SELECT COUNT\(\*\) AS numrows/i', $sql))
        {
            return FALSE;
        }
        
        return TRUE;
    }

    /**
     * Determines if a query is a "SELECT SINGLE" type.
     *
     * @access	public
     * @param	string	An SQL query string
     * @return	boolean		
     */
    public function is_select_single_type($sql)
    {
        if ( ! preg_match('/\s*AS single_col/i', $sql))
        {
            return FALSE;
        }
        
        return TRUE;
    }

    /**
     * Determines if a query is a "SELECT COMBO" type.
     *
     * @access	public
     * @param	string	An SQL query string
     * @return	boolean		
     */
    public function is_select_combo_type($sql)
    {
        if ( ! preg_match('/\s*AS combo/i', $sql))
        {
            return FALSE;
        }
        
        return TRUE;
    }

    /**
     * Determines if a query is a "INSERT" type.
     *
     * @access	public
     * @param	string	An SQL query string
     * @return	boolean		
     */
    public function is_insert_type($sql)
    {
        if ( ! preg_match('/^\s*"?(INSERT)\s+/i', $sql))
        {
            return FALSE;
        }
        
        return TRUE;
    }

    /**
     * Set result return type.
     *
     * @access	public
     * @param	string	A type to set string
     */
    public function set_return_type($type)
    {
        $this->return_type = $type;
    }

    public function log_error($error = '', $swap = '')
    {
        $LANG = & load_class('Lang', 'core');
        $LANG->load('db');

        $heading = $LANG->line('db_error_heading');
        $message = is_array($error) ? $error : array(str_replace('%s', $swap, $LANG->line($error)));

        // Find the most likely culprit of the error by going through
        // the backtrace until the source file is no longer in the
        // database folder.
        $trace = debug_backtrace();
        foreach ($trace as $call)
        {
            if (isset($call['file'], $call['class']))
            {
                // We'll need this on Windows, as APPPATH and BASEPATH will always use forward slashes
                if (DIRECTORY_SEPARATOR !== '/')
                {
                    $call['file'] = str_replace('\\', '/', $call['file']);
                }

                if (strpos($call['file'], BASEPATH . 'database') === FALSE && strpos($call['class'], 'Loader') === FALSE)
                {
                    // We'll need this on Windows, as APPPATH and BASEPATH will always use forward slashes
                    if (DIRECTORY_SEPARATOR !== '/')
                    {
                        $rep_app_path = str_replace('\\', '/', APPPATH);
                        $rep_base_path = BASEPATH;
                    } 
                    else 
                    {
                        $rep_app_path = APPPATH;
                        $rep_base_path = BASEPATH;
                    }
                    // Found it - use a relative path for safety
                    $message[] = 'Filename: ' . str_replace(array($rep_app_path, $rep_base_path), '', $call['file']);
                    $message[] = 'Line Number: ' . $call['line'];
                    break;
                }
            }
        }
        $this->setErrorFound(TRUE);
        $this->setErrorMessages($message);
    }
    
    public function setErrorFound($bool = TRUE)
    {
        $this->_error_found = $bool;
    }
    
    public function getErrorFound()
    {
        return $this->_error_found;
    }
    
    public function setErrorMessages($msg = array())
    {
        $this->_error_messages[] = $msg;
    }
    
    public function getErrorMessages()
    {
        return $this->_error_messages;
    }
    /******* Custom HB Created **/
}