<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Archive Tables Controller
 *
 * @category nsengine
 *            
 * @package nsengine
 * 
 * @subpackage controllers
 *  
 * @module NSEngine
 * 
 * @class Archivecontroller.php
 * 
 * @path application\notification\nsengine\controllers\Archivecontroller.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 22.05.2018
 */
class Archivecontroller extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('nsengine/archive_schedule_model');
    }

    /**
     * executeArchiveTables method is used to archive specified tables on regular basis.
     */
    public function executeArchiveTables()
    {
        $archive_tabels = $this->config->item('ARCHIVE_TABLES_LIST');
        try {

            if (!is_array($archive_tabels) || count($archive_tabels) == 0) {
                throw new Exception("No tables found");
            }

            require_once ($this->config->item('third_party') . 'cronjob/vendor/autoload.php');
            foreach ((array) $archive_tabels as $n_val) {
                try {
                    $curr_date_str = date("Y_m_d");
                    $curr_date_time = date("Y-m-d H:i:s");
                    $cron_expression = $n_val['cron_expression'];
                    $default_time_zone = date_default_timezone_get();
                    date_default_timezone_set("UTC");
                    $cron = Cron\CronExpression::factory($cron_expression);
                    $cron_date_time = $cron->getPreviousRunDate()->format('Y-m-d H:i:s');
                    date_default_timezone_set($default_time_zone);
                    if ($cron_date_time <= $curr_date_time) {
                        $source = $n_val['archive_source'];
                        $target = $n_val['archive_target'];
                        if (strstr($target, "{{date}}") !== FALSE) {
                            $target = str_replace("{{date}}", $curr_date_str, $target);
                        }
                        $condition = $n_val['condition_stmt'];
                        $this->archive_schedule_model->archiveTable($source, $target, $condition);
                    }
                } catch (Exception $e) {
                    $e->getMessage();
                }
            }

            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
        $this->skip_template_view();
    }
}
