<?php

/**
 * 
 * @author CIT Dev Team
 */
class CITExtentionHook
{

    public function init()
    {
        global $CFG, $RTR, $class;

        // Related to extending current controller from above controller ... releted to CIT operations.
        if ($CFG->config['cu_controller_prx'] != "" && is_file(APPPATH . 'controllers/' . $RTR->directory . ucfirst($CFG->config['cu_controller_prx']) . $class . '.php')) {
            require_once(APPPATH . 'controllers/' . $RTR->directory . ucfirst($CFG->config['cu_controller_prx']) . $class . '.php');
            $RTR->set_class($CFG->config['cu_controller_prx'] . $class);
            $class = ucfirst($RTR->class);
        }
    }
}
