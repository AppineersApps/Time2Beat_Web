<?php
defined('BASEPATH') || exit('No direct script access allowed');

//load the MX_Loader class 
require_once config_item('third_party') . "MX/Loader.php";

class Cit_Loader extends MX_Loader
{

    /** Load database * */
    public function database($params = '', $return = FALSE, $query_builder = NULL)
    {
        // Grab the super object
        $CI = & get_instance();

        // Do we even need to load the database class?
        if ($return === FALSE && $query_builder === NULL && isset($CI->db) && is_object($CI->db) && !empty($CI->db->conn_id)) {
            return FALSE;
        }

        require_once(APPPATH . 'database/DB.php');

        if ($return === TRUE) {
            return CIT_DB($params, $query_builder);
        }

        // Initialize the db variable. Needed to prevent
        // reference errors with some configurations
        $CI->db = '';

        // Load the DB class
        $CI->db = & CIT_DB($params, $query_builder);
        return $this;
    }

    /** Load module * */
    public function module($module, $params = NULL)	
	{
		if (is_array($module)) return $this->modules($module);
        $_alias = strtolower(basename($module));
		//CI::$APP->$_alias = Modules::load(array($module => $params));

        /* get the requested controller class name */
		$alias = strtolower(basename($module));

		/* create or return an existing controller from the registry */
		if ( ! isset(Modules::$registry[$alias])) 
		{
			/* find the controller */
			list($class) = CI::$APP->router->locate(explode('/', $module));
	
			/* controller cannot be located */
			if (empty($class)) return;
	
			/* set the module directory */
			$path = APPPATH.'controllers/'.CI::$APP->router->directory;
			
			/* load the controller class */
			$class = $class.CI::$APP->config->item('controller_suffix');
            $_class = ucfirst($class);
            Modules::load_file($_class, $path);

            // Related to extending current model from above model... releted to CIT operations.
            if (CI::$APP->config->item('cu_controller_prx') != "" && is_file($path . ucfirst(CI::$APP->config->item('cu_controller_prx')) . $_class . ".php")) {
                $_class = ucfirst(CI::$APP->config->item('cu_controller_prx')) . $_class;
                Modules::load_file($_class, $path);
            }
            /* create and register the new controller */
			$controller = $_class;	
			Modules::$registry[$alias] = new $controller($params);
		}
        
        CI::$APP->$_alias = Modules::$registry[$alias];
        
        return $this;
	}
    
    /** Load model * */
    public function model($model, $object_name = NULL, $connect = FALSE)
    {
        if (is_array($model))
            return $this->models($model);

        ($_alias = $object_name) || $_alias = basename($model);

        if (in_array($_alias, $this->_ci_models, TRUE))
            return $this;

        /* check module */
        list($path, $_model) = Modules::find(strtolower($model), $this->_module, 'models/');

        if ($path == FALSE) {
            /* check application & packages */
            parent::model($model, $object_name, $connect);
        } else {
            class_exists('CI_Model', FALSE) || load_class('Model', 'core');

            if ($connect !== FALSE && !class_exists('CI_DB', FALSE)) {
                if ($connect === TRUE)
                    $connect = '';
                $this->database($connect, FALSE, TRUE);
            }
            Modules::load_file($_model, $path);

            // Related to extending current model from above model... releted to CIT operations.
            if (CI::$APP->config->item('cu_model_prx') != "" && is_file($path . ucfirst(CI::$APP->config->item('cu_model_prx')) . $_model . ".php")) {
                $_model = ucfirst(CI::$APP->config->item('cu_model_prx')) . $_model;
                Modules::load_file($_model, $path);
            }

            $model = ucfirst($_model);
            CI::$APP->$_alias = new $model();

            $this->_ci_models[] = $_alias;
        }
        return $this;
    }
    
    public function unset_model($model_obj = '')
    {
        if (in_array($model_obj, $this->_ci_models)) {
            $CI = & get_instance();
            unset($CI->$model_obj);
            $ind = array_search($model_obj, $this->_ci_models);
            unset($this->_ci_models[$ind]);
            $this->_ci_models = array_values($this->_ci_models);
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

/* End of file Cit_Loader.php */
/* Location: ./application/core/Cit_Loader.php */