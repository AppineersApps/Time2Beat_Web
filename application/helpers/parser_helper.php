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

/**
 * CI Smarty
 *
 * Smarty templating for Codeigniter
 *
 * @package   CI Smarty
 * @author      Dwayne Charrington
 * @copyright  2014 Dwayne Charrington and Github contributors
 * @link           http://ilikekillnerds.com
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 * @version     2.0
 */
/**
 * Theme URL
 *
 * A helper function for getting the current theme URL
 * in a web friendly format.
 *
 * @param string $location
 * @return mixed
 */
function theme_url($location = '')
{
    $CI =& get_instance();
    return $CI->parser->theme_url($location);
}
/**
 * CSS
 *
 * A helper function for getting the current theme CSS embed code
 * in a web friendly format
 *
 * @param $file
 * @param $attributes
 */
if ( ! function_exists('css') )
{
    function css($file, $attributes = array())
    {
        $CI =& get_instance();
        echo $CI->parser->css($file, $attributes);
    }
}
/**
 * JS
 *
 * A helper function for getting the current theme JS embed code
 * in a web friendly format
 *
 * @param $file
 * @param $attributes
 */
if ( ! function_exists('js') )
{
    function js($file, $attributes = array())
    {
        $CI =& get_instance();
        echo $CI->parser->js($file, $attributes);
    }
}
/**
 * IMG
 *
 * A helper function for getting the current theme IMG embed code
 * in a web friendly format
 *
 * @param $file
 * @param $attributes
 */
if ( ! function_exists('image') )
{
    function image($file, $attributes = array())
    {
        $CI =& get_instance();
        echo $CI->parser->img($file, $attributes);
    }
}
/**
 * Session
 *
 * A helper function for getting a session variable alias of $this->session->userdata($name)
 *
 * @param $name
 */
if ( ! function_exists('userdata') )
{
    function userdata($name)
    {
        $CI =& get_instance();
        return $CI->session->userdata($name);
    }
}
/**
 * Uri segment
 *
 * A helper function for getting a uri segment alias of $this->uri->segment(n)
 *
 * @param $segnum
 */
if ( ! function_exists('uriseg') )
{
    function uriseg($segnum)
    {
        $CI =& get_instance();
        return $CI->uri->segment($segnum);
    }
}
/**
 * Flashdata
 *
 * A helper function for getting a flash message alias of $this->session->flashdata($name)
 *
 * @param $name
 */
if ( ! function_exists('flashdata') )
{
    function flashdata($name)
    {
        $CI =& get_instance();
        return $CI->session->flashdata($name);
    }
}

/* End of file parser_helper.php */
/* Location: ./application/helpers/parser_helper.php */