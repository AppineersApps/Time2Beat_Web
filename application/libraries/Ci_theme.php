<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Admin Theme Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module Theme
 * 
 * @class Ci_theme.php
 * 
 * @path application\libraries\Ci_theme.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Ci_theme
{

    protected $CI;
    protected $_server_theme_arr;
    protected $_client_theme_arr;

    public function __construct()
    {
        $this->CI = & get_instance();
    }
    /*
     * Code will be generated dynamically
     * Please do not write or change the content below this line
     * Five hashes must be there on either side of string.
     */

    public function setServerThemeSettings()
    {
        $theme_settings_arr = array();
        $theme_settings_arr = $this->themeWiseServerParams();
        
        #####GENERATED_SERVER_THEME_BACKGROUNDS_START#####
            
        $theme_settings_arr['theme_loader_class'] = 'default-qLbar loader-qLbar';
        $theme_settings_arr['theme_spinner_class'] = 'fa fa-circle-o-notch fa-spin fa-3x fa-fw';
        $theme_settings_arr['theme_notify_style'] = 'CIT';#####GENERATED_SERVER_THEME_BACKGROUNDS_END#####
        
        #####GENERATED_SERVER_THEME_SETTINGS_START#####
        $theme_settings_arr['menu_semicollapse'] = true;
        $theme_settings_arr['grid_left_search'] = 'show-left-search';
        $theme_settings_arr['grid_search_toolbar'] = 'hide-search-toolbar';
        //general form
        $theme_settings_arr['frm_gener_content_row'] = 'single-row-view'; //double-row-view
        $theme_settings_arr['frm_gener_border_view'] = ''; //border-row-view
        $theme_settings_arr['frm_gener_label_align'] = 'label-lt-align'; //label-rt-align
        $theme_settings_arr['frm_gener_titles_bar'] = 'frm-title-none'; //frm-title-bar//frm-title-none
        $theme_settings_arr['frm_gener_action_bar'] = ''; //frm-ctrls-bar
        $theme_settings_arr['frm_gener_action_btn'] = 'bot-btn-cen'; //bot-btn-cen//bot-btn-rtl
        $theme_settings_arr['frm_gener_ctrls_view'] = 'Yes'; //No
        // standard form
        $theme_settings_arr['frm_stand_content_row'] = 'single-row-view'; //double-row-view
        $theme_settings_arr['frm_stand_border_view'] = ''; //border-row-view
        $theme_settings_arr['frm_stand_label_align'] = 'label-lt-align'; //label-rt-align
        $theme_settings_arr['frm_stand_titles_bar'] = 'frm-title-none'; //frm-title-bar//frm-title-none
        $theme_settings_arr['frm_stand_action_bar'] = ''; //frm-ctrls-bar
        $theme_settings_arr['frm_stand_action_btn'] = 'bot-btn-cen'; //bot-btn-cen//bot-btn-rtl
        $theme_settings_arr['frm_stand_ctrls_view'] = 'Yes'; //No
        // split(two block) form
        $theme_settings_arr['frm_split_content_row'] = 'single-row-view'; //double-row-view
        $theme_settings_arr['frm_split_border_view'] = ''; //border-row-view
        $theme_settings_arr['frm_split_label_align'] = 'label-lt-align'; //label-rt-align
        $theme_settings_arr['frm_split_titles_bar'] = 'frm-title-none'; //frm-title-bar//frm-title-none
        $theme_settings_arr['frm_split_action_bar'] = ''; //frm-ctrls-bar
        $theme_settings_arr['frm_split_action_btn'] = 'bot-btn-cen'; //bot-btn-cen//bot-btn-rtl
        $theme_settings_arr['frm_split_ctrls_view'] = 'Yes'; //No
        // three block form
        $theme_settings_arr['frm_thblk_content_row'] = 'double-row-view'; //double-row-view
        $theme_settings_arr['frm_thblk_border_view'] = ''; //border-row-view
        $theme_settings_arr['frm_thblk_label_align'] = 'label-lt-align'; //label-rt-align
        $theme_settings_arr['frm_thblk_titles_bar'] = 'frm-title-none'; //frm-title-bar//frm-title-none
        $theme_settings_arr['frm_thblk_action_bar'] = ''; //frm-ctrls-bar
        $theme_settings_arr['frm_thblk_action_btn'] = 'bot-btn-cen'; //bot-btn-cen//bot-btn-rtl
        $theme_settings_arr['frm_thblk_ctrls_view'] = 'Yes'; //No
        // four block form
        $theme_settings_arr['frm_frblk_content_row'] = 'single-row-view'; //double-row-view
        $theme_settings_arr['frm_frblk_border_view'] = ''; //border-row-view
        $theme_settings_arr['frm_frblk_label_align'] = 'label-lt-align'; //label-rt-align
        $theme_settings_arr['frm_frblk_titles_bar'] = 'frm-title-none'; //frm-title-bar//frm-title-none
        $theme_settings_arr['frm_frblk_action_bar'] = ''; //frm-ctrls-bar
        $theme_settings_arr['frm_frblk_action_btn'] = 'bot-btn-cen'; //bot-btn-cen//bot-btn-rtl
        $theme_settings_arr['frm_frblk_ctrls_view'] = 'Yes'; //No
        // two column block form
        $theme_settings_arr['frm_twclm_content_row'] = 'single-row-view'; //double-row-view
        $theme_settings_arr['frm_twclm_border_view'] = ''; //border-row-view
        $theme_settings_arr['frm_twclm_label_align'] = 'label-lt-align'; //label-rt-align
        $theme_settings_arr['frm_twclm_titles_bar'] = 'frm-title-none'; //frm-title-bar//frm-title-none
        $theme_settings_arr['frm_twclm_action_bar'] = ''; //frm-ctrls-bar
        $theme_settings_arr['frm_twclm_action_btn'] = 'bot-btn-cen'; //bot-btn-cen//bot-btn-rtl
        $theme_settings_arr['frm_twclm_ctrls_view'] = 'Yes'; //No
        // three column block form
        $theme_settings_arr['frm_thclm_content_row'] = 'double-row-view'; //double-row-view
        $theme_settings_arr['frm_thclm_border_view'] = ''; //border-row-view'
        $theme_settings_arr['frm_thclm_label_align'] = 'label-lt-align'; //label-rt-align
        $theme_settings_arr['frm_thclm_titles_bar'] = 'frm-title-none'; //frm-title-bar//frm-title-none
        $theme_settings_arr['frm_thclm_action_bar'] = ''; //frm-ctrls-bar
        $theme_settings_arr['frm_thclm_action_btn'] = 'bot-btn-cen'; //bot-btn-cen//bot-btn-rtl
        $theme_settings_arr['frm_thclm_ctrls_view'] = 'Yes'; //No
        // custom view block form
        $theme_settings_arr['frm_custm_content_row'] = 'single-row-view'; //double-row-view
        $theme_settings_arr['frm_custm_border_view'] = ''; //border-row-view
        $theme_settings_arr['frm_custm_label_align'] = 'label-lt-align'; //label-rt-align
        $theme_settings_arr['frm_custm_titles_bar'] = 'frm-title-none'; //frm-title-bar//frm-title-none
        $theme_settings_arr['frm_custm_action_bar'] = ''; //frm-ctrls-bar
        $theme_settings_arr['frm_custm_action_btn'] = 'bot-btn-cen'; //bot-btn-cen//bot-btn-rtl
        $theme_settings_arr['frm_custm_ctrls_view'] = 'Yes'; //No
        #####GENERATED_SERVER_THEME_SETTINGS_END#####

        $this->_server_theme_arr = $theme_settings_arr;
        return $theme_settings_arr;
    }
    /*
     * Code will be generated dynamically
     * Please do not write or change the content below this line
     * Five hashes must be there on either side of string.
     */

    public function setClientThemeSettings()
    {
        $theme_settings_arr = array();
        $theme_settings_arr = $this->themeWiseClientParams();
        $theme_settings_arr['themes_list'] = array(
            "supr" => "Default",
            "metronic" => "Metronic",
            "cit" => "CIT"
        );
        $theme_settings_arr['themes_default'] = $this->themeDefaultColors();
        $theme_settings_arr['themes_custom'] = $this->themeCustomColors();

        #####GENERATED_CLIENT_THEME_SETTINGS_START#####
        //general
        $theme_settings_arr['frm_resizeblock'] = false;
        $theme_settings_arr['frm_rel_rec_pos'] = 'prepend';
        $theme_settings_arr['menu_semicollapse'] = true;
        //grid
        $theme_settings_arr['grid_pgnumbers'] = true;
        $theme_settings_arr['grid_pgnumlimit'] = 5;
        $theme_settings_arr['grid_pagingpos'] = "right";
        $theme_settings_arr['grid_searchopt'] = true;
        $theme_settings_arr['grid_filteropt'] = 'always';
        $theme_settings_arr['grid_icons'] = true;
        $theme_settings_arr['grid_icons_add'] = false;
        $theme_settings_arr['grid_icons_del'] = false;
        $theme_settings_arr['grid_icons_search'] = true;
        $theme_settings_arr['grid_icons_refresh'] = true;
        $theme_settings_arr['grid_icons_columns'] = true;
        $theme_settings_arr['grid_icons_export'] = true;
        $theme_settings_arr['grid_icons_print'] = true;
        $theme_settings_arr['grid_toolbar_search_icon'] = true;
        $theme_settings_arr['grid_view_listing_icon'] = true;
        //sub grid
        $theme_settings_arr['grid_sub_pgnumbers'] = true;
        $theme_settings_arr['grid_sub_pgnumlimit'] = 5;
        $theme_settings_arr['grid_sub_pagingpos'] = "right";
        $theme_settings_arr['grid_sub_searchopt'] = false;
        $theme_settings_arr['grid_sub_icons'] = true;
        $theme_settings_arr['grid_sub_icons_add'] = true;
        $theme_settings_arr['grid_sub_icons_del'] = true;
        $theme_settings_arr['grid_sub_icons_search'] = true;
        $theme_settings_arr['grid_sub_icons_refresh'] = true;
        $theme_settings_arr['grid_sub_icons_columns'] = true;
        $theme_settings_arr['grid_sub_icons_export'] = true;
        $theme_settings_arr['grid_sub_icons_print'] = true;
        // Chart settings colors
        $theme_settings_arr['chart_colors'] = array(
            '#88bbc8', '#ed7a53',
            '#9FC569', '#bbdce3',
            '#9a3b1b', '#5a8022',
            '#2c7282', '#49BFAE',
            '#34A8DB', '#428BCA'
        );
        // Number of records per page in dashboard
        $theme_settings_arr['pivot_number_of_records'] = '20';
        // Bar Chart
        $theme_settings_arr['bar_chart_show_legend'] = true;
        $theme_settings_arr['bar_chart_legend_position'] = 'ne';
        $theme_settings_arr['bar_chart_show_xaxis_label'] = false;
        $theme_settings_arr['bar_chart_show_yaxis_label'] = true;
        // Pie Chart
        $theme_settings_arr['pie_chart_show_legend'] = true;
        $theme_settings_arr['pie_chart_legend_position'] = 'ne';
        $theme_settings_arr['pie_chart_show_label'] = true;
        $theme_settings_arr['pie_chart_label_style'] = 's1';
        $theme_settings_arr['pie_chart_label_value'] = 'percent'; //percent or value
        // Donut Chart
        $theme_settings_arr['donut_chart_show_legend'] = false;
        $theme_settings_arr['donut_chart_legend_position'] = 'ne';
        $theme_settings_arr['donut_chart_show_label'] = true;
        $theme_settings_arr['donut_chart_label_style'] = 's1';
        $theme_settings_arr['donut_chart_label_value'] = 'percent'; //percent or value
        // Area Chart
        $theme_settings_arr['area_chart_show_legend'] = true;
        $theme_settings_arr['area_chart_legend_position'] = 'ne';
        $theme_settings_arr['area_chart_show_xaxis_label'] = false;
        $theme_settings_arr['area_chart_show_yaxis_label'] = true;
        // Line Chart
        $theme_settings_arr['line_chart_show_legend'] = true;
        $theme_settings_arr['line_chart_legend_position'] = 'ne';
        $theme_settings_arr['line_chart_show_xaxis_label'] = false;
        $theme_settings_arr['line_chart_show_yaxis_label'] = true;
        // Horizontal Bar
        $theme_settings_arr['horizontal_bar_show_legend'] = true;
        $theme_settings_arr['horizontal_bar_legend_position'] = 'ne';
        $theme_settings_arr['horizontal_bar_show_xaxis_label'] = true;
        $theme_settings_arr['horizontal_bar_show_yaxis_label'] = false;
        // Stacked Bar
        $theme_settings_arr['stacked_bar_show_legend'] = true;
        $theme_settings_arr['stacked_bar_legend_position'] = 'ne';
        $theme_settings_arr['stacked_bar_show_xaxis_label'] = false;
        $theme_settings_arr['stacked_bar_show_yaxis_label'] = true;
        // Stacked Horizontal Bar
        $theme_settings_arr['stacked_horizontal_bar_show_legend'] = true;
        $theme_settings_arr['stacked_horizontal_bar_legend_position'] = 'ne';
        $theme_settings_arr['stacked_horizontal_bar_show_xaxis_label'] = true;
        $theme_settings_arr['stacked_horizontal_bar_show_yaxis_label'] = false;
        // Auto Updating Chart
        $theme_settings_arr['auto_updating_chart_show_legend'] = true;
        $theme_settings_arr['auto_updating_chart_legend_position'] = 'ne';
        $theme_settings_arr['auto_updating_chart_show_xaxis_label'] = false;
        $theme_settings_arr['auto_updating_chart_show_yaxis_label'] = true;
        #####GENERATED_CLIENT_THEME_SETTINGS_END#####

        $this->_client_theme_arr = $theme_settings_arr;
        return $theme_settings_arr;
    }

    public function themeCustomColors()
    {
        $themes_custom_arr = array();

        #####GENERATED_THEME_CUSTOMIZATION_START#####
        #####GENERATED_THEME_CUSTOMIZATION_END#####

        return $themes_custom_arr;
    }

    public function themeDefaultColors()
    {
        $themes_default_arr = array();
        $themes_default_arr['metronic'] = array(
            array(
                "file" => "black", "color" => "#333438"
            ),
            array(
                "file" => "light", "color" => "#eee"
            ),
            array(
                "file" => "blue", "color" => "#124f94"
            ),
            array(
                "file" => "brown", "color" => "#623f18"
            ),
            array(
                "file" => "purple", "color" => "#701584"
            )
        );
        $themes_default_arr['cit'] = array(
            array(
                "file" => "default", "color" => "#01bbe4"
            ),
            array(
                "file" => "black", "color" => "#25313e"
            ),
            array(
                "file" => "purple", "color" => "#594f8d"
            ),
            array(
                "file" => "blue", "color" => "#428bca"
            ),
            array(
                "file" => "turquoise", "color" => "#1fb5ad"
            ),
            array(
                "file" => "brown", "color" => "#b49c83"
            ),
            array(
                "file" => "cloud", "color" => "#5e87b0"
            ),
            array(
                "file" => "dark_slate_blue", "color" => "#303753"
            ),
            array(
                "file" => "brown_thomas", "color" => "#ff6600"
            ),
            array(
                "file" => "atom", "color" => "#1e293d"
            ),
            array(
                "file" => "conquer", "color" => "#242527"
            ),
            array(
                "file" => "forest_green", "color" => "#27ae60"
            ),
            array(
                "file" => "indigo", "color" => "#4a3153"
            ),
            array(
                "file" => "lightblue", "color" => "#5ccdde"
            ),
            array(
                "file" => "maroon", "color" => "#fb5557"
            ),
            array(
                "file" => "darkblue", "color" => "#1c3e95"
            ),
            array(
                "file" => "green", "color" => "#009688"
            ),
            array(
                "file" => "gradient_green", "color" => "#20b8a5"
            ),
            array(
                "file" => "orange", "color" => "#F67E1F"
            ),
            array(
                "file" => "gradient_red", "color" => "#ff5252"
            ),
            array(
                "file" => "gradient_blue", "color" => "#003abc"
            ),
            array(
                "file" => "prussian_blue", "color" => "#0B4666"
            ),
            array(
                "file" => "seal_brown", "color" => "#401D1B"
            ),
            array(
                "file" => "gradient_bay", "color" => "#7f7cb9"
            ),
            array(
                "file" => "dodger_blue", "color" => "#2781E1"
            ),
            array(
                "file" => "midnight_express", "color" => "#0A2047"
            ),
            array(
                "file" => "paris_m", "color" => "#262160"
            ),
            array(
                "file" => "gradient_purple", "color" => "#955EB2"
            ),
            array(
                "file" => "gradient_cerulean", "color" => "#0080B3"
            ),
            array(
                "file" => "gradient_violet", "color" => "#FA71CD"
            ),
        );

        return $themes_default_arr;
    }

    public function getServerThemeSettings()
    {
        $theme_settings_arr = $this->_server_theme_arr;
        return $theme_settings_arr;
    }

    public function getClientThemeSettings()
    {
        $theme_settings_arr = $this->_client_theme_arr;
        return $theme_settings_arr;
    }

    public function themeWiseClientParams()
    {
        $setttings_arr = $extra_arr = array();
        $current_theme = $this->CI->config->item("ADMIN_THEME_DISPLAY");
        $admin_images_url = $this->CI->config->item("admin_images_url");
        $admin_theme_settings = $this->CI->config->item('ADMIN_THEME_SETTINGS');
        $theme_settings_arr = explode("@", $admin_theme_settings);
        switch ($current_theme) {
            case "cit":
                $extra_arr['grid_width_dec'] = 2 + 15;
                $extra_arr['grid_height_dec'] = 2 + 15;

                $theme_color = (trim($theme_settings_arr[1]) == "") ? "default" : $theme_settings_arr[1];
                $theme_custom = (trim($theme_settings_arr[2]) == "") ? "none" : $theme_settings_arr[2];
                $setttings_arr['theme'] = $current_theme;
                $setttings_arr['color'] = $theme_color;
                $setttings_arr['custom'] = $theme_custom;
                $extra_arr['theme_settings'] = $setttings_arr;
                break;
            case 'metronic':
                $extra_arr['grid_width_dec'] = 2;
                $extra_arr['grid_height_dec'] = 2;

                $theme_color = (trim($theme_settings_arr[1]) == "") ? "default" : $theme_settings_arr[1];
                $theme_custom = (trim($theme_settings_arr[2]) == "") ? "none" : $theme_settings_arr[2];
                $setttings_arr['theme'] = $current_theme;
                $setttings_arr['color'] = $theme_color;
                $setttings_arr['custom'] = $theme_custom;
                $extra_arr['theme_settings'] = $setttings_arr;
                break;
            default:
                $extra_arr['grid_width_dec'] = 2;
                $extra_arr['grid_height_dec'] = 2;

                $pattern_arr = explode("||", $theme_settings_arr[1]);
                $theme_custom = (trim($theme_settings_arr[2]) == "") ? "none" : $theme_settings_arr[2];
                $setttings_arr['theme'] = $current_theme;
                $setttings_arr['pattern_0'] = $pattern_arr[0];
                $setttings_arr['pattern_1'] = $pattern_arr[1];
                $setttings_arr['pattern_2'] = $pattern_arr[2];
                $setttings_arr['custom'] = $theme_custom;
                $extra_arr['theme_settings'] = $setttings_arr;
                break;
        }
        return $extra_arr;
    }

    public function themeWiseServerParams()
    {
        $admin_images_url = $this->CI->config->item("admin_images_url");
        $extra_arr = array(
            'gen_rating_master' => $admin_images_url
        );
        return $extra_arr;
    }
}

/* End of file Ci_theme.php */
/* Location: ./application/libraries/Ci_theme.php */