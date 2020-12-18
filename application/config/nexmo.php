<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Nexmo API Settings
  |--------------------------------------------------------------------------
  |
  | This file contains the configuration parameters that are used by the
  | Nexmo library. Please create an account on Nexmo and use the provided
  | information here.
  |
  | http://www.nexmo.com
  |
  | api_key         => Your account api key.
  | api_secret      => Your account api secret.
  |
  $config['api_key'] = 'be0d32dd';
  $config['api_secret'] = '7ba4c1ad';
*/

require_once(APPPATH . 'config/cit_bootstrap.php');
$db_instance = & CIT_DB();

$db_instance->select("vName, vValue");
$db_instance->where("eStatus", 'Active');
$db_instance->where_in("vName", array('SMS_NX_API_KEY','SMS_NX_API_SECRET'));
$DB_NEXMO_DATA = $db_instance->select_assoc("mod_setting", "vName");

$config['api_key'] = $DB_NEXMO_DATA['SMS_NX_API_KEY'][0]['vValue'];
$config['api_secret'] = $DB_NEXMO_DATA['SMS_NX_API_SECRET'][0]['vValue'];

// please get the following api on Nexmo.
/* End of file nexmo.php */