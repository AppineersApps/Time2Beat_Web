<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Clickatel API Settings
  |--------------------------------------------------------------------------
  |
  | This file contains the configuration parameters that are used by the
  | Clickate library. Please create an account on Clickatel and use the provided
  | information here.
  |
  | http://www.clickatel.com
  |
  | User          => Your account username.
  | Password      => Your account password.
  | API ID        => Upon registration, Clickatel will provide you an API key.
  |
  $config['clickatel'] = array(
  'api_id' => '3431046',
  'user' => 'sharma',
  'password' => 'IbPTAMSXQfSQgL'
  );
 */

require_once(APPPATH . 'config/cit_bootstrap.php');
$db_instance = & CIT_DB();

$db_instance->select("vName, vValue");
$db_instance->where("eStatus", 'Active');
$db_instance->where_in("vName", array('SMS_CA_API_ID','SMS_CA_API_USER','SMS_CA_API_PWD'));
$DB_CLICKATELL_DATA = $db_instance->select_assoc("mod_setting", "vName");

$config['clickatel'] = array(
    'api_id' => $DB_CLICKATELL_DATA['SMS_CA_API_ID'][0]['vValue'],
    'user' => $DB_CLICKATELL_DATA['SMS_CA_API_USER'][0]['vValue'],
    'password' => $DB_CLICKATELL_DATA['SMS_CA_API_PWD'][0]['vValue']
);

/* End of file clickatel.php */
/* Location: ./application/config/clickatel.php */