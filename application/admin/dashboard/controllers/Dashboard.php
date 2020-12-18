<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Dashboard Controller
 *
 * @category admin
 *
 * @package dashboard
 *
 * @subpackage controllers
 *
 * @module Dashboard
 *
 * @class Dashboard.php
 *
 * @path application\admin\dashboard\controllers\Dashboard.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 06.09.2019
 */

class Dashboard extends Cit_Controller
{
    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('listing');
        $this->load->library('filter');
        $this->load->library('dropdown');
        $this->load->model('dashboard_model');
        $this->load->model('dashboardpages_model');
        $this->load->model('dashboardpagesblock_model');
        $this->get_arr = is_array($this->input->get(NULL, TRUE)) ? $this->input->get(NULL, TRUE) : array();
        $this->post_arr = is_array($this->input->post(NULL, TRUE)) ? $this->input->post(NULL, TRUE) : array();
        $this->params_arr = array_merge($this->get_arr, $this->post_arr);
        $this->folder_name = "dashboard";
        $this->module_name = "dashboard";
        $this->mod_enc_mode = $this->general->getCustomEncryptMode(TRUE);
        $this->module_config = array(
            'module_name' => $this->module_name,
            'folder_name' => $this->folder_name,
        );
        $this->dropdown_limit = $this->config->item('ADMIN_DROPDOWN_LIMIT');
        $this->chart_assoc = array(
            "pivot" => "Pivot Table",
            "bar" => "Bar Chart",
            "pie" => "Pie Chart",
            "donut" => "Donut Chart",
            "area" => "Area Chart",
            "line" => "Line Chart",
            "horizbar" => "Horiz. Bar Chart",
            "stackbar" => "Stacked Bar Chart",
            "stackhorizbar" => "Stacked Horiz. Bar Chart",
            "autoupdating" => "Auto Updating Chart",
        );
    }

    /**
     * index method is used to intialize sitemap listing page.
     */
    public function index()
    {
        try
        {
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
            {
                $sitemap_access = $this->filter->checkAccessCapability("home_sitemap", TRUE);
                if (!$sitemap_access)
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
                }
            }
            $total_arr = $this->systemsettings->getMenuArray("Sitemap");
            $render_arr = array(
                'total_arr' => $total_arr,
            );
            $this->smarty->assign($render_arr);
        }
        catch(Exception $e)
        {
            $render_arr['err_message'] = $e->getMessage();
            $this->smarty->assign($render_arr);
            $this->loadView($this->config->item('ADMIN_FORBIDDEN_TEMPLATE'));
        }
    }

    /**
     * sitemap method is used to load sitemap listing page.
     */
    public function sitemap()
    {
        try
        {
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
            {
                $sitemap_access = $this->filter->checkAccessCapability("home_sitemap", TRUE);
                if (!$sitemap_access)
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
                }
            }
            $total_arr = $this->systemsettings->getMenuArray("Sitemap");
            $render_arr = array(
                'total_arr' => $total_arr,
            );
            $this->smarty->assign($render_arr);
        }
        catch(Exception $e)
        {
            $render_arr['err_message'] = $e->getMessage();
            $this->smarty->assign($render_arr);
            $this->loadView($this->config->item('ADMIN_FORBIDDEN_TEMPLATE'));
        }
    }

    /**
     * _render_dashboard_page method is used to get data array for rendering dashboard pages
     * @param string $extra_cond extra_cond is used to set query extra where condition.
     * @param string $render_data render_data for rendering dashboard pages.
     * @return array $render_arr returns render data records array
     */
    protected function _render_dashboard_page($extra_cond = '', $render_data = array())
    {
        $where_cond = ($extra_cond != "") ? $extra_cond : "";
        $fields = "mad.iDashBoardId, mad.vBoardName, mad.vBoardCode, mad.vBoardIcon, mad.eBoardSource, mad.eDefaultChart, mad.eChartType, madpb.tLayoutJSON, madpb.iBlockOrder";
        $order_by = "madpb.iBlockOrder";
        $dashboard_data = $this->dashboardpagesblock_model->getData($where_cond, $fields, $order_by, "", "", "Yes");
        $db_pivot_data = $db_list_data = $db_view_data = $block_data_arr = $block_config_arr = array();
        foreach ((array) $dashboard_data as $val)
        {
            $board_id = $val['iDashBoardId'];
            $board_code = $val['vBoardCode'];
            $board_source = $val['eBoardSource'];
            $chart_type = $val['eChartType'];
            if ($chart_type == "Pivot")
            {
                if (in_array($val['eDefaultChart'], $this->chart_assoc))
                {
                    $key_chart = array_search($val['eDefaultChart'], $this->chart_assoc);
                }
                else
                {
                    $key_chart = "pivot";
                }
                $db_pivot_data[$board_id] = $render_data[$board_code];
                $db_pivot_data[$board_id]["defaultChart"] = $key_chart;
                $ajax_update = "No";
            }
            elseif ($chart_type == "Detail View")
            {
                if ($board_source == "Function")
                {
                    $db_view_data[$board_id] = $render_data[$board_code]['data'];
                }
                else
                {
                    if ($render_data[$board_code]['template'])
                    {
                        $parse_html = $this->parser->parse($render_data[$board_code]['template'], $render_data[$board_code], TRUE);
                        $db_view_data[$board_id] = $parse_html;
                    }
                }
                $ajax_update = "Yes";
            }
            elseif ($chart_type == "Grid List")
            {
                $db_list_data[$board_id] = $render_data[$board_code];
                $ajax_update = "No";
            }

            $tmp_config_arr['id'] = $board_id;
            $tmp_config_arr['chartType'] = $chart_type;
            $tmp_config_arr['ajaxUpdate'] = $ajax_update;
            $tmp_config_arr['autoUpdate'] = $render_data[$board_code]["autoUpdate"];
            $tmp_config_arr['dateFilter'] = $render_data[$board_code]["dateFilter"];
            $tmp_config_arr['filterField'] = $render_data[$board_code]["filterField"];
            $tmp_config_arr['filterValue'] = $render_data[$board_code]["filterValue"];
            $block_config_arr[$board_id] = $tmp_config_arr;

            $val['excludeHeader'] = $render_data[$board_code]["excludeHeader"];
            $val['attr'] = $this->listing->getDashboardAttributes($val);
            $block_data_arr[] = $val;
        }
        $db_list_data_json = $this->filter->getJSONEncodeJSFunction($db_list_data);
        $db_pivot_data_json = $this->filter->getJSONEncodeJSFunction($db_pivot_data);
        $block_config_json = json_encode($block_config_arr);

        return array('block_config_json' => $block_config_json, 'db_pivot_data_json' => $db_pivot_data_json, 'db_list_data_json' => $db_list_data_json, 'db_view_data' => $db_view_data, 'block_data_arr' => $block_data_arr, 'mod_enc_mode' => $this->mod_enc_mode, 'folder_name' => $this->folder_name, 'module_name' => $this->module_name);
    }

    protected function _parse_dashboard_block($code = '', $filters = array(), $json = TRUE)
    {
        $ret_data = $func_data = FALSE;

        $where_cond = $this->db->protect("mad.vBoardCode")." = ".$this->db->escape($code);
        $fields = "mad.iDashBoardId, mad.vBoardName, mad.vBoardCode, mad.vBoardIcon, mad.eBoardSource, mad.eDefaultChart, mad.eChartType";
        $dashboard_data = $this->dashboard_model->getData($where_cond, $fields);
        if (!is_array($dashboard_data) || count($dashboard_data) == 0)
        {
            return $ret_data;
        }
        switch ($code)
        {
            default:
                break;
        }
        $board_source = $dashboard_data[0]['eBoardSource'];
        $chart_type = $dashboard_data[0]['eChartType'];
        $default_chart = $dashboard_data[0]['eDefaultChart'];
        if ($chart_type == "Pivot")
        {
            if (in_array($default_chart, $this->chart_assoc))
            {
                $key_chart = array_search($default_chart, $this->chart_assoc);
            }
            else
            {
                $key_chart = "pivot";
            }
            $ret_data = $func_data;
            $ret_data['defaultChart'] = $key_chart;
        }
        elseif ($chart_type == "Detail View")
        {
            if ($board_source == "Function")
            {
                $ret_data = $func_data['data'];
            }
            else
            {
                if ($func_data['template'])
                {
                    $parse_html = $this->parser->parse($func_data['template'], $func_data, TRUE);
                    $ret_data = $parse_html;
                }
            }
        }
        elseif ($chart_type == "Grid List")
        {
            $ret_data = $func_data;
        }
        if ($json === TRUE && ($chart_type == "Pivot" || $chart_type == "Grid List"))
        {
            $ret_data = $this->filter->getJSONEncodeJSFunction($ret_data);
        }
        return $ret_data;
    }

    public function filter_dashboard_block()
    {
        $params_arr = $this->params_arr;
        $board_code = $params_arr['code'];
        $filt_field = $params_arr['field'];
        $from_date = $params_arr['from_date'];
        $to_date = $params_arr['to_date'];

        $filters = array();
        $filters[$filt_field]['start'] = $from_date;
        $filters[$filt_field]['end'] = $to_date;
        $data_set = $this->_parse_dashboard_block($board_code, $filters);
        echo $data_set;
        $this->skip_template_view();
    }

    public function autoload_dashboard_block()
    {
        $params_arr = $this->params_arr;
        $board_code = $params_arr['code'];
        $data_set = $this->_parse_dashboard_block($board_code);
        echo $data_set;
        $this->skip_template_view();
    }

    /**
     * dashboard_sequence_a method is used to save dahsboard page sequences
     */
    public function dashboard_sequence_a()
    {
        $params_arr = $this->params_arr;
        $type = $params_arr['type'];
        $page_id = intval($params_arr['id']);
        $tab_id = intval($params_arr['tab']);
        try
        {
            $page_cond = $this->db->protect("iDashBoardPageId")." = ".$this->db->escape($page_id);
            $data_arr = $this->dashboardpages_model->getData($page_cond);
            if ($this->config->item("ENABLE_ROLES_CAPABILITIES"))
            {
                $edit_access = $this->filter->checkAccessCapability("dashboard_".$data_arr[0]["vPageCode"]."_sequence", TRUE);
            }
            else
            {
                $edit_access = $this->filter->getModuleWiseAccess($data_arr[0]["vPageCode"], "Update", FALSE, TRUE, TRUE);
            }
            if (!$edit_access)
            {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }
            if ($type == 'block_sequence')
            {
                $data_str_arr = $params_arr['obj'];
                $data_str_cnt = count($data_str_arr);
                for ($i = 0, $j = 1; $i < $data_str_cnt; $i++, $j++)
                {
                    $board_id = intval($data_str_arr[$i]['chart_id']);
                    $extra_cond = $this->db->protect("iDashBoardId")." = ".$this->db->escape($board_id)." AND ".$this->db->protect("iDashBoardPageId")." = ".$this->db->escape($page_id)." AND ".$this->db->protect("iTabId")." = ".$this->db->escape($tab_id);
                    $update_arr['tLayoutJSON'] = json_encode($data_str_arr[$i]['chart_sequence']);
                    $update_arr['iBlockOrder'] = $j;
                    $result = $this->dashboardpagesblock_model->update($update_arr, $extra_cond);
                }
                if (!$result)
                {
                    throw new Exception('Error in updating the sequence');
                }
                $message = 'Sequence updated successfully';
            }
            elseif ($type == 'block_type')
            {
                $data_str_arr = $params_arr['obj'];
                $data_str_cnt = count($data_str_arr);
                for ($i = 0; $i < $data_str_cnt; $i++)
                {
                    $chart_type = $data_str_arr[$i]['chart_type'];
                    $chart_id = $data_str_arr[$i]['chart_id'];
                    $update_arr['eDefaultChart'] = $this->chart_assoc[$chart_type];
                    $result = $this->dashboard_model->update($update_arr, $chart_id);
                }
                if (!$result)
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_FALIURE_IN_UPDATING_C46_C46_C33'));
                }
                $message = $this->general->processMessageLabel('ACTION_RECORD_C40S_C41_UPDATED_SUCCESSFULLY_C46_C46_C33');
            }
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $ret_arr['success'] = $success;
        $ret_arr['message'] = $message;
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }
}
