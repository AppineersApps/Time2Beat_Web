<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of CIT API Model
 *
 * @category models
 *
 * @package models
 *
 * @module CITAPI
 *
 * @class Cit_api_model.php
 *
 * @path application\models\Cit_api_model.php
 *
 * @version 4.0
 *
 * @author CIT Dev Team
 *
 * @date 03.02.2016
 */
class Cit_api_model extends CI_Model
{

    public $unset_paths = array();

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * setAPIModulePath method is used to set webservice module paths.
     * @return boolean $res returns TRUE or FALSE.
     */
    public function setAPIModulePath()
    {
        $marr = Modules::$locations;
        if (is_array($marr) && !array_key_exists(APPPATH . 'webservice/', $marr)) {
            $this->unset_paths = $marr;
            $narr = array(
                APPPATH . 'webservice/' => '../webservice/'
            );
            Modules::$locations = $narr;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * unsetAPIModulePath method is used to unset webservice module paths and sets previous module paths.
     * @return boolean $res returns TRUE or FALSE.
     */
    public function unsetAPIModulePath()
    {
        $marr = Modules::$locations;
        if (is_array($marr) && array_key_exists(APPPATH . 'webservice/', $marr)) {
            if(!empty($this->unset_paths)){
                $narr = $this->unset_paths;
                Modules::$locations = $narr;
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * callAPI method is used to get response from API.
     * @param string $api_name api_name is the API name to execute partiular API flow.
     * @param array $params params is the input parameters for the API.
     * @return array $response returns API response in array.
     */
    public function callAPI($api_name = '', $params = array())
    {
        $response = array();
        $this->setAPIModulePath();
        try {
            //fetching webservice config details
            $this->config->load('cit_webservices', TRUE);
            $all_methods = $this->config->item('cit_webservices');
            if (empty($all_methods[$api_name])) {
                throw new Exception('API code not found. Please save settings or update code.');
            }

            if (isset($params['lang_id']) && $params['lang_id'] != "") {
                $_POST['lang_id'] = $params['lang_id'];
            } else {
                $multi_lingual = $this->config->item('MULTI_LINGUAL_PROJECT');
                if ($multi_lingual == "Yes") {
                    $params['lang_id'] = "en";
                    $_POST['lang_id'] = "en";
                }
            }

            //loading for webservice controller
            $this->load->module($all_methods[$api_name]['folder'] . "/" . $api_name);

            //checking for webservice controller
            if (!is_object($this->$api_name)) {
                throw new Exception('API code not found. Please save settings or update code.');
            }

            //checking for webservice start method
            $start_method = "start_" . $api_name;
            if (!method_exists($this->$api_name, $start_method)) {
                throw new Exception('API init method not found. Please save settings or update code.');
            }

            //initializing for webservice start method
            $response = $this->$api_name->$start_method($params, TRUE);
            if ($response['success'] == -5) {
                $response = array(
                    'settings' => array(
                        "status" => 200,
                        "success" => 0,
                        "message" => $response['message']
                    ),
                    'data' => array()
                );
            }
        } catch (Exception $e) {
            $response = array(
                'settings' => array(
                    "status" => 400,
                    "success" => 0,
                    "message" => $e->getMessage()
                ),
                'data' => array()
            );
        }
        $this->unsetAPIModulePath();
        return $response;
    }
}
