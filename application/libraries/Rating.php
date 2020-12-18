<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Rating
{

    /**
     *  radio
     *  @category function
     *  @access public
     *
     */
    private $CI;

    public function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->js->add_js('libraries/rating/jquery.MetaData.js');
        $this->CI->js->add_js('libraries/rating/jquery.rating.js');
    }

    public function displaydata()
    {
        $argument_list = func_get_args();
        $radio_name = $argument_list[0];

        $readonly = $argument_list[1];
        $params = $argument_list[2];

        if (isset($params['split'])) {
            $split = $params['split'];
        } else {
            $split = 1;
        }

        if ($readonly == "") {
            $readonly = 'read';
        }

        if ($params["number_of_radios"] != "") {
            $number_of_radios = $params["number_of_radios"];
        } else {
            $number_of_radios = 5;
        }

        if ($params['average'] != "") {
            $avg = $params['average'];
        } else {
            $avg = 0;
        }
        if ($params['ajax'] == "") {
            $params['ajax'] = 'false';
        }
        $class_radios = $radio_name;
        $number = $number_of_radios * $split;

        if ($readonly == 'edit') {
            $radio_arr = array(
                "number_radios" => $number_radios,
                "class_radios" => $class_radios,
                "number" => $number,
                "split" => $split,
                "select" => $avg,
                "readonly" => $readonly,
                "user" => $params['username'],
                "rating_for" => $params["rating_for"],
                "ajax" => $params["ajax"]
            );
        } else if ($readonly == 'read') {
            $radio_arr = array(
                "number_radios" => $number_radios,
                "class_radios" => $class_radios,
                "number" => $number,
                "split" => $split,
                "select" => $avg,
                "readonly" => $readonly,
                "user" => $params['username'],
                "rating_for" => $params["rating_for"]
            );
        }
        return $this->CI->parser->parse("libraries/rate.tpl", $radio_arr, true);
    }
}
