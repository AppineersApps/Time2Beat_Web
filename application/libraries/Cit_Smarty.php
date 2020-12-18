<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Extended Smarty Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module Smarty
 * 
 * @class Cit_Smarty.php
 * 
 * @path application\libraries\Cit_Smarty.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Cit_Smarty extends CI_Smarty
{

    public function __construct()
    {
        parent::__construct();

        $this->left_delimiter = "<%";

        $this->right_delimiter = "%>";

        $this->assign("javascript_append", array());
    }
}
