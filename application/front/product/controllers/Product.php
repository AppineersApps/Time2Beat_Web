<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Product Controller
 *
 * @category front
 *            
 * @package product
 * 
 * @subpackage controllers
 *  
 * @module Product
 * 
 * @class Product.php
 * 
 * @path application\front\product\controllers\Product.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Product extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    /**
     * index method is used to define home page content.
     */
    public function index()
    {
        $view_file = "user/welcome_message";
        $this->loadView($view_file);
    }
}
