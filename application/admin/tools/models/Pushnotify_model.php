<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of PushNotify Model
 *
 * @category admin
 * 
 * @package tools
 *  
 * @subpackage models
 * 
 * @module PushNotify
 * 
 * @class Pushnotify_model.php
 * 
 * @path application\admin\tools\models\Pushnotify_model.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Pushnotify_model extends CI_Model
{

    public $table_name = "";

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'mod_push_notifications';
    }
}
