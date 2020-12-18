<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Datepicker
{

    private $CI;

    public function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->js->add_js('jquery-ui-1.9.2.min.js');
        $this->CI->css->add_css("jqueryui/jquery-ui-1.9.2.custom.css");
    }

    public function datePicker()
    {
        $argument_list = func_get_args();
        $id = $argument_list[0];
        $params = $argument_list[1];
        $last_argument = $argument_list[count($argument_list) - 1];

        if (!is_array($params)) {
            $type = $params;
            $params = $argument_list[2];
        }

        if ($id == "custom") {
            return;
        }

        if ($type == "birthday") {
            if (isset($params["min_year"]) && ($params["max_year"] )) {
                $params['yearRange'] = ($params["min_year"]) . ":" . ($params["max_year"]);
            } else {
                $params['yearRange'] = (date("Y") - 100) . ":" . (date("Y") - 18);
                // $params['yearRange'] =  (date("Y")-18). ":" . (date("Y")-100);
            }
        }

        if ($type == 'daterange') {
            if (isset($params["min_year"]) && ($params["max_year"] )) {
                $params['yearRange'] = ($params["min_year"]) . ":" . ($params["max_year"]);
            }
        }

        if ($type == "fromto") {
            list($first_id, $second_id) = explode(",", $id);
            $id = "#$first_id, #$second_id";
            $this->CI->first_id = $first_id;
            $this->CI->second_id = $second_id;
        } else {
            $id = "#" . $id;
        }

        if (isset($params["selected_disable_date"])) {
            if (is_array($params["selected_disable_date"])) {
                $selected_disable_date_array = $params["selected_disable_date"];
                $params["beforeShowDay"][0] = "nationalDays";
                $params["beforeShowDay"][1] = "event";
                $en_selected_disable_date_array = json_encode($selected_disable_date_array);
                $this->CI->js->javascript_code .= 'var disabledDays = eval(' . $en_selected_disable_date_array . ');
                   ';
            }
        }

        if (isset($params["disable_future_date"])) {
            $disable_future_date = $params["disable_future_date"];
            if ($disable_future_date == "true") {
                $str_js11 = " new Date() < date";
                $str_js_if = " || " . $str_js11;
            }
            $params["beforeShowDay"][0] = "disableFutureDays";
            $params["beforeShowDay"][1] = "event";
            $this->CI->js->javascript_code .= '
                            var dateToday =  new Date();
                            function disableFutureDays(date) {
                                var m = date.getMonth();
                                var d = date.getDate();
                                var y = date.getFullYear();
                                if(' . $str_js11 . ') {
                                    return [false];
                                }
                                return [true];
                            }';
            $this->disable_futuredate_str = 'maxDate: dateToday,';
        } else {
            $this->disable_futuredate_str = '';
        }

        if (isset($params["disable_past_date"])) {
            $disable_past_date = $params["disable_past_date"];
            if ($disable_past_date == "true") {
                $str_js11 = " d > date";
                $str_js_if = " || " . $str_js11;
            }
            $params["beforeShowDay"][0] = "disablePastDays";
            $params["beforeShowDay"][1] = "event";
            $this->CI->js->javascript_code .= '
                            var dateToday =  new Date();
                            function disablePastDays(date) {
                                var m = date.getMonth();
                                var d = date.getDate();
                                var y = date.getFullYear();
                                d = new Date();
                                d = d.setDate(d.getDate() -1);
                                if(' . $str_js11 . ') {
                                    return [false];
                                }
                                return [true];
                            }';
            $this->disable_pastdate_str = 'minDate: dateToday,';
        } else {
            $this->disable_pastdate_str = '';
        }

        if (isset($params["beforeShowDay"])) {

            $this->CI->js->javascript_code .= '
                            function nationalDays(date) {
                                var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();
                                for (i = 0; i < disabledDays.length; i++) {
                                    if($.inArray((m+1) + \'-\' + d + \'-\' + y,disabledDays) != -1 ' . $str_js_if . ') {
                                        return [false];
                                    }
                                }
                                return [true];
                            }
                            function noWeekendsOrHolidays(date) {
                                    var noWeekend = jQuery.datepicker.noWeekends(date);
                                    return noWeekend[0] ? nationalDays(date) : noWeekend;
                            }';
        }

        if (isset($params["with_time"])) {
            $with_time = $params["with_time"];
            if ($with_time == "true") {
                $params["showTime"] = "true";
                $this->CI->js->add_js('libraries/datepicker/jquery-ui-timepicker-addon.js');
            } else {
                $params["showTime"] = "false";
            }
        }
        $this->normal_datepicker($type, $id, $params);
    }

    public function normal_datepicker($type = "", $id, $params)
    {
        $this->CI->js->javascript_code .= '$.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );';

        if ($params['yearRange'] == "") {
            $params['yearRange'] = (date("Y") - 10) . ":" . (date("Y") + 20);
        }

        if ($params["showTime"] == "") {
            if ($type == "fromto") {
                $this->CI->js->javascript_code .= '
                            var dates = $("' . $id . '").datepicker({
					';
            } else {
                $this->CI->js->javascript_code .= '
                            $("' . $id . '").datepicker({
                                        ';
            }
        } else if ($params["showTime"] == "true") {
            if ($type == "fromto") {
                $this->CI->js->javascript_code .= 'var dates = $("' . $id . '").datetimepicker({
					';
            } else
                $this->CI->js->javascript_code .= '$("' . $id . '").datetimepicker({
					';
        }

        foreach ($params as $key => $data) {
            if (is_array($data)) {
                if ($data[1] == "event")
                    $javascript_param_arr[] = $key . ":" . $data[0] . "";
            } else {
                $javascript_param_arr[] = $key . ":'" . $data . "'";
            }
        }

        $this->CI->js->javascript_code .= implode(",", $javascript_param_arr);

        if ($type == "fromto") {
            $this->CI->js->javascript_code .= ',defaultDate: new Date(),
                                            changeMonth: true,
                                            numberOfMonths: 3,
                                            onSelect: function( selectedDate ) {
                                            	var option = this.id == "' . $this->CI->second_id . '" ? "maxDate" : "minDate",
						instance = $( this ).data( "datepicker" );
					        date = $.datepicker.parseDate(
                                                    instance.settings.dateFormat ||
						    $.datepicker._defaults.dateFormat,
						    selectedDate, instance.settings
                                                );
                                                dates.not( this ).datepicker( "option", option, date );
                                            }';
        }
        if ($params['buttonImage'] == '') {
            $params['buttonImage'] = "public/images/admin/calendar.gif";
        }
        $this->CI->js->javascript_code .= ',changeMonth:true,
				    changeYear:true,
				    showOn: "both", 
				    buttonImage: "' . $params['buttonImage'] . '",
				    buttonImageOnly: true,
                                    showAnim: "slideDown",
                                    duration: "fast",
                                    ' . $this->disable_futuredate_str . '
                                    ' . $this->disable_pastdate_str . '
                                    showWeek: true,
                                    firstDay: 1,
                                    hideIfNoPrevNext: true,
				    beforeShow: function(input,inst) {						
					if($(input).val()=="") return;
					var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                                        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                                        //$(this).datepicker("setDate", new Date(year, month, 1));
				    }    
                                });
                              
                                ';
    }

    public function displayDate($name, $past_selected = FALSE)
    {
        $arr = func_get_args();
        $date_array = array(
            "name" => $name,
            "past_selected" => $past_selected
        );
        return $this->CI->parser->parse("libraries/datepicker", $date_array, true);
    }
}
