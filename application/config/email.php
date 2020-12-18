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
  | -------------------------------------------------------------------
  | EMAIL CONFIG SETTINGS
  | -------------------------------------------------------------------
 
  $config['protocol'] = 'smtp';
  $config['smtp_host'] = 'ssl://smtp.gmail.com';
  $config['smtp_port'] = '465';
  $config['smtp_timeout'] = '7';
  $config['smtp_user'] = 'cit.email001@gmail.com';
  $config['smtp_pass'] = 'email@001';
  $config['charset'] = 'utf-8';
  $config['newline'] = "\r\n";
  $config['mailtype'] = 'html'; // or html
  $config['validation'] = TRUE; // bool whether to validate email or not
*/

require_once(APPPATH . 'config/cit_bootstrap.php');
$db_instance = & CIT_DB();

$db_instance->select("vName, vValue");
$db_instance->where("eStatus", 'Active');
$db_instance->where_in("vName", array('USE_SMTP_SERVERPORT','USE_SMTP_SERVERHOST','USE_SMTP_SERVERUSERNAME','USE_SMTP_SERVERPASS', 'USE_SMTP_SERVERCRYPTO'));
$DB_SMTP_DATA = $db_instance->select_assoc("mod_setting", "vName");

$config['protocol'] = 'smtp';
$config['smtp_host'] = $DB_SMTP_DATA["USE_SMTP_SERVERHOST"][0]["vValue"];
$config['smtp_port'] = $DB_SMTP_DATA["USE_SMTP_SERVERPORT"][0]["vValue"];
$config['smtp_timeout'] = '7';
$config['smtp_user'] = $DB_SMTP_DATA["USE_SMTP_SERVERUSERNAME"][0]["vValue"];
$config['smtp_pass'] = $DB_SMTP_DATA["USE_SMTP_SERVERPASS"][0]["vValue"];
$config['smtp_crypto'] = $DB_SMTP_DATA["USE_SMTP_SERVERCRYPTO"][0]["vValue"];
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['mailtype'] = 'html'; // or html
$config['validation'] = TRUE; // bool whether to validate email or not

/* End of file email.php */
/* Location: ./application/config/email.php */