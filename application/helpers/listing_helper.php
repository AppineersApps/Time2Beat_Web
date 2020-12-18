<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.2.4 or newer
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014, British Columbia Institute of Technology
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
 * @copyright	Copyright (c) 2014, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') || exit('No direct script access allowed');

/*
  | To change this template, choose Tools | Templates
  | and open the template in the editor.
 */

function getStartIndex($total, $page = 1, $recrod_limit = 20)
{
    $start_index = ($page - 1) * $recrod_limit;
    return intval($start_index);
}

function getTotalPages($total_records, $records_per_page)
{
    if ($records_per_page == 0) {
        return 1;
    }
    $total_pages = ceil($total_records / $records_per_page);
    return $total_pages;
}

function filterEmptyValues($tmp_str = '')
{
    $ret_arr = array();
    if (is_array($tmp_str)) {
        $tmp_arr = $tmp_str;
    } elseif (!is_null($tmp_str) && trim($tmp_str) != "") {
        $tmp_arr = explode(",", $tmp_str);
    } 
    if (is_array($tmp_arr)) {
        foreach ($tmp_arr as $key => $val) {
            if (!is_null($val) && trim($val) != "") {
                $ret_arr[] = $val;
            }
        }
    }
    return (is_array($ret_arr) && count($ret_arr) > 0) ? $ret_arr : FALSE;
}
/* End of file listing_helper.php */
/* Location: ./application/helpers/listing_helper.php */