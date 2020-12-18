<?php
if (!function_exists('_date_time_php_format')) {

    /**
     * dateTimePHPFormats function
     *
     * @return	php format
     */
    function _date_time_php_format($type = '', $fmt = '')
    {
        //date formats
        $date_time_arr['dfmt_1'] = "Y-m-d";
        $date_time_arr['dfmt_2'] = "m-d-Y";
        $date_time_arr['dfmt_3'] = "d-m-Y";
        $date_time_arr['dfmt_4'] = "m/d/Y";
        $date_time_arr['dfmt_5'] = "d/m/Y";
        $date_time_arr['dfmt_6'] = "Y/m/d";
        $date_time_arr['dfmt_7'] = "d.m.Y";
        $date_time_arr['dfmt_8'] = "Y.m.d";
        $date_time_arr['dfmt_9'] = "m.d.Y";
        $date_time_arr['dfmt_10'] = "M d, Y";
        $date_time_arr['dfmt_11'] = "d M, Y";
        $date_time_arr['dfmt_12'] = "d.M.Y";
        $date_time_arr['dfmt_13'] = "d/M/Y";
        $date_time_arr['dfmt_14'] = "d-M-Y";
        $date_time_arr['dfmt_15'] = "F d, Y";
        $date_time_arr['dfmt_16'] = "d F, Y";
        $date_time_arr['dfmt_17'] = "D M d, Y";
        $date_time_arr['dfmt_18'] = "D d M, Y";
        $date_time_arr['dfmt_19'] = "l, F d, Y";
        $date_time_arr['dfmt_20'] = "l, d F, Y";

        //date time formats
        $date_time_arr['dtfmt_1'] = "Y-m-d H:i:s";
        $date_time_arr['dtfmt_2'] = "Y-m-d h:i A";
        $date_time_arr['dtfmt_3'] = "Y-m-d H:i";
        $date_time_arr['dtfmt_4'] = "m-d-Y H:i:s";
        $date_time_arr['dtfmt_5'] = "m-d-Y h:i A";
        $date_time_arr['dtfmt_6'] = "m-d-Y H:i";
        $date_time_arr['dtfmt_7'] = "d-m-Y H:i:s";
        $date_time_arr['dtfmt_8'] = "d-m-Y h:i A";
        $date_time_arr['dtfmt_9'] = "d-m-Y H:i";
        $date_time_arr['dtfmt_10'] = "m/d/Y H:i:s";
        $date_time_arr['dtfmt_11'] = "m/d/Y h:i A";
        $date_time_arr['dtfmt_12'] = "m/d/Y H:i";
        $date_time_arr['dtfmt_13'] = "d/m/Y H:i:s";
        $date_time_arr['dtfmt_14'] = "d/m/Y h:i A";
        $date_time_arr['dtfmt_15'] = "d/m/Y H:i";
        $date_time_arr['dtfmt_16'] = "Y/m/d H:i:s";
        $date_time_arr['dtfmt_17'] = "Y/m/d h:i A";
        $date_time_arr['dtfmt_18'] = "Y/m/d H:i";
        $date_time_arr['dtfmt_19'] = "d.m.Y H:i:s";
        $date_time_arr['dtfmt_20'] = "d.m.Y h:i A";
        $date_time_arr['dtfmt_21'] = "d.m.Y H:i";
        $date_time_arr['dtfmt_22'] = "Y.m.d H:i:s";
        $date_time_arr['dtfmt_23'] = "Y.m.d h:i A";
        $date_time_arr['dtfmt_24'] = "Y.m.d H:i";
        $date_time_arr['dtfmt_25'] = "m.d.Y H:i:s";
        $date_time_arr['dtfmt_26'] = "m.d.Y h:i A";
        $date_time_arr['dtfmt_27'] = "m.d.Y H:i";
        $date_time_arr['dtfmt_28'] = "M d, Y H:i:s";
        $date_time_arr['dtfmt_29'] = "M d, Y h:i A";
        $date_time_arr['dtfmt_30'] = "M d, Y H:i";
        $date_time_arr['dtfmt_31'] = "d M, Y H:i:s";
        $date_time_arr['dtfmt_32'] = "d M, Y h:i A";
        $date_time_arr['dtfmt_33'] = "d M, Y H:i";
        $date_time_arr['dtfmt_34'] = "d.M.Y H:i:s";
        $date_time_arr['dtfmt_35'] = "d.M.Y h:i A";
        $date_time_arr['dtfmt_36'] = "d.M.Y H:i";
        $date_time_arr['dtfmt_37'] = "d/M/Y H:i:s";
        $date_time_arr['dtfmt_38'] = "d/M/Y h:i A";
        $date_time_arr['dtfmt_39'] = "d/M/Y H:i";
        $date_time_arr['dtfmt_40'] = "d-M-Y H:i:s";
        $date_time_arr['dtfmt_41'] = "d-M-Y h:i A";
        $date_time_arr['dtfmt_42'] = "d-M-Y H:i";
        $date_time_arr['dtfmt_43'] = "F d, Y H:i:s";
        $date_time_arr['dtfmt_44'] = "F d, Y h:i A";
        $date_time_arr['dtfmt_45'] = "F d, Y H:i";
        $date_time_arr['dtfmt_46'] = "d F, Y H:i:s";
        $date_time_arr['dtfmt_47'] = "d F, Y h:i A";
        $date_time_arr['dtfmt_48'] = "d F, Y H:i";
        $date_time_arr['dtfmt_49'] = "D M d, Y H:i:s";
        $date_time_arr['dtfmt_50'] = "D M d, Y h:i A";
        $date_time_arr['dtfmt_51'] = "D M d, Y H:i";
        $date_time_arr['dtfmt_52'] = "D d M, Y H:i:s";
        $date_time_arr['dtfmt_53'] = "D d M, Y h:i A";
        $date_time_arr['dtfmt_54'] = "D d M, Y H:i";
        $date_time_arr['dtfmt_55'] = "l, F d, Y H:i:s";
        $date_time_arr['dtfmt_56'] = "l, F d, Y h:i A";
        $date_time_arr['dtfmt_57'] = "l, F d, Y H:i";
        $date_time_arr['dtfmt_58'] = "l, d F, Y H:i:s";
        $date_time_arr['dtfmt_59'] = "l, d F, Y h:i A";
        $date_time_arr['dtfmt_60'] = "l, d F, Y H:i";

        //time formats
        $date_time_arr['tfmt_1'] = "h:i A";
        $date_time_arr['tfmt_2'] = "H:i:s";
        $date_time_arr['tfmt_3'] = "H:i";

        if ($fmt == '') {
            switch ($type) {
                case 'date':
                    $fmt = 'dfmt_1';
                    break;
                case 'date_and_time':
                    $fmt = 'dtfmt_1';
                    break;
                case 'time':
                    $fmt = 'tfmt_1';
                    break;
            }
        }
        return $date_time_arr[trim($fmt)];
    }
}

if (!function_exists('_date_time_js_format')) {

    /**
     * dateTimeJSFormats function
     *
     * @return	js format
     */
    function _date_time_js_format($type = '', $fmt = '')
    {
        //date formats
        $formats_arr['date']['dfmt_1']['dateFormat'] = "yy-mm-dd";
        $formats_arr['date']['dfmt_2']['dateFormat'] = "mm-dd-yy";
        $formats_arr['date']['dfmt_3']['dateFormat'] = "dd-mm-yy";
        $formats_arr['date']['dfmt_4']['dateFormat'] = "mm/dd/yy";
        $formats_arr['date']['dfmt_5']['dateFormat'] = "dd/mm/yy";
        $formats_arr['date']['dfmt_6']['dateFormat'] = "yy/mm/dd";
        $formats_arr['date']['dfmt_7']['dateFormat'] = "dd.mm.yy";
        $formats_arr['date']['dfmt_8']['dateFormat'] = "yy.mm.dd";
        $formats_arr['date']['dfmt_9']['dateFormat'] = "mm.dd.yy";
        $formats_arr['date']['dfmt_10']['dateFormat'] = "M d, yy";
        $formats_arr['date']['dfmt_11']['dateFormat'] = "d M, yy";
        $formats_arr['date']['dfmt_12']['dateFormat'] = "d.M.yy";
        $formats_arr['date']['dfmt_13']['dateFormat'] = "d/M/yy";
        $formats_arr['date']['dfmt_14']['dateFormat'] = "d-M-yy";
        $formats_arr['date']['dfmt_15']['dateFormat'] = "MM d, yy";
        $formats_arr['date']['dfmt_16']['dateFormat'] = "d MM, yy";
        $formats_arr['date']['dfmt_17']['dateFormat'] = "D M d, yy";
        $formats_arr['date']['dfmt_18']['dateFormat'] = "D d M, yy";
        $formats_arr['date']['dfmt_19']['dateFormat'] = "DD, MM d, yy";
        $formats_arr['date']['dfmt_20']['dateFormat'] = "DD, d MM, yy";

        //date time formats
        $formats_arr['date_and_time']['dtfmt_1']['dateFormat'] = "yy-mm-dd";
        $formats_arr['date_and_time']['dtfmt_1']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_1']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_1']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_2']['dateFormat'] = "yy-mm-dd";
        $formats_arr['date_and_time']['dtfmt_2']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_2']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_2']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_3']['dateFormat'] = "yy-mm-dd";
        $formats_arr['date_and_time']['dtfmt_3']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_3']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_3']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_4']['dateFormat'] = "mm-dd-yy";
        $formats_arr['date_and_time']['dtfmt_4']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_4']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_4']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_5']['dateFormat'] = "mm-dd-yy";
        $formats_arr['date_and_time']['dtfmt_5']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_5']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_5']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_6']['dateFormat'] = "mm-dd-yy";
        $formats_arr['date_and_time']['dtfmt_6']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_6']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_6']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_7']['dateFormat'] = "dd-mm-yy";
        $formats_arr['date_and_time']['dtfmt_7']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_7']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_7']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_8']['dateFormat'] = "dd-mm-yy";
        $formats_arr['date_and_time']['dtfmt_8']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_8']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_8']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_9']['dateFormat'] = "dd-mm-yy";
        $formats_arr['date_and_time']['dtfmt_9']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_9']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_9']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_10']['dateFormat'] = "mm/dd/yy";
        $formats_arr['date_and_time']['dtfmt_10']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_10']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_10']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_11']['dateFormat'] = "mm/dd/yy";
        $formats_arr['date_and_time']['dtfmt_11']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_11']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_11']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_12']['dateFormat'] = "mm/dd/yy";
        $formats_arr['date_and_time']['dtfmt_12']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_12']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_12']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_13']['dateFormat'] = "dd/mm/yy";
        $formats_arr['date_and_time']['dtfmt_13']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_13']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_13']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_14']['dateFormat'] = "dd/mm/yy";
        $formats_arr['date_and_time']['dtfmt_14']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_14']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_14']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_15']['dateFormat'] = "dd/mm/yy";
        $formats_arr['date_and_time']['dtfmt_15']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_15']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_15']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_16']['dateFormat'] = "yy/mm/dd";
        $formats_arr['date_and_time']['dtfmt_16']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_16']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_16']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_17']['dateFormat'] = "yy/mm/dd";
        $formats_arr['date_and_time']['dtfmt_17']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_17']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_17']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_18']['dateFormat'] = "yy/mm/dd";
        $formats_arr['date_and_time']['dtfmt_18']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_18']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_18']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_19']['dateFormat'] = "dd.mm.yy";
        $formats_arr['date_and_time']['dtfmt_19']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_19']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_19']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_20']['dateFormat'] = "dd.mm.yy";
        $formats_arr['date_and_time']['dtfmt_20']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_20']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_20']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_21']['dateFormat'] = "dd.mm.yy";
        $formats_arr['date_and_time']['dtfmt_21']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_21']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_21']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_22']['dateFormat'] = "yy.mm.dd";
        $formats_arr['date_and_time']['dtfmt_22']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_22']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_22']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_23']['dateFormat'] = "yy.mm.dd";
        $formats_arr['date_and_time']['dtfmt_23']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_23']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_23']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_24']['dateFormat'] = "yy.mm.dd";
        $formats_arr['date_and_time']['dtfmt_24']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_24']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_24']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_25']['dateFormat'] = "mm.dd.yy";
        $formats_arr['date_and_time']['dtfmt_25']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_25']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_25']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_26']['dateFormat'] = "mm.dd.yy";
        $formats_arr['date_and_time']['dtfmt_26']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_26']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_26']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_27']['dateFormat'] = "mm.dd.yy";
        $formats_arr['date_and_time']['dtfmt_27']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_27']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_27']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_28']['dateFormat'] = "M d, yy";
        $formats_arr['date_and_time']['dtfmt_28']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_28']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_28']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_29']['dateFormat'] = "M d, yy";
        $formats_arr['date_and_time']['dtfmt_29']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_29']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_29']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_30']['dateFormat'] = "M d, yy";
        $formats_arr['date_and_time']['dtfmt_30']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_30']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_30']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_31']['dateFormat'] = "d M, yy";
        $formats_arr['date_and_time']['dtfmt_31']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_31']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_31']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_32']['dateFormat'] = "d M, yy";
        $formats_arr['date_and_time']['dtfmt_32']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_32']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_32']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_33']['dateFormat'] = "d M, yy";
        $formats_arr['date_and_time']['dtfmt_33']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_33']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_33']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_34']['dateFormat'] = "d.M.yy";
        $formats_arr['date_and_time']['dtfmt_34']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_34']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_34']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_35']['dateFormat'] = "d.M.yy";
        $formats_arr['date_and_time']['dtfmt_35']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_35']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_35']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_36']['dateFormat'] = "d.M.yy";
        $formats_arr['date_and_time']['dtfmt_36']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_36']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_36']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_37']['dateFormat'] = "d/M/yy";
        $formats_arr['date_and_time']['dtfmt_37']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_37']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_37']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_38']['dateFormat'] = "d/M/yy";
        $formats_arr['date_and_time']['dtfmt_38']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_38']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_38']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_39']['dateFormat'] = "d/M/yy";
        $formats_arr['date_and_time']['dtfmt_39']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_39']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_39']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_40']['dateFormat'] = "d-M-yy";
        $formats_arr['date_and_time']['dtfmt_40']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_40']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_40']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_41']['dateFormat'] = "d-M-yy";
        $formats_arr['date_and_time']['dtfmt_41']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_41']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_41']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_42']['dateFormat'] = "d-M-yy";
        $formats_arr['date_and_time']['dtfmt_42']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_42']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_42']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_43']['dateFormat'] = "MM d, yy";
        $formats_arr['date_and_time']['dtfmt_43']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_43']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_43']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_44']['dateFormat'] = "MM d, yy";
        $formats_arr['date_and_time']['dtfmt_44']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_44']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_44']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_45']['dateFormat'] = "MM d, yy";
        $formats_arr['date_and_time']['dtfmt_45']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_45']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_45']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_46']['dateFormat'] = "d MM, yy";
        $formats_arr['date_and_time']['dtfmt_46']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_46']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_46']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_47']['dateFormat'] = "d MM, yy";
        $formats_arr['date_and_time']['dtfmt_47']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_47']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_47']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_48']['dateFormat'] = "d MM, yy";
        $formats_arr['date_and_time']['dtfmt_48']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_48']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_48']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_49']['dateFormat'] = "D M d, yy";
        $formats_arr['date_and_time']['dtfmt_49']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_49']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_49']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_50']['dateFormat'] = "D M d, yy";
        $formats_arr['date_and_time']['dtfmt_50']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_50']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_50']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_51']['dateFormat'] = "D M d, yy";
        $formats_arr['date_and_time']['dtfmt_51']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_51']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_51']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_52']['dateFormat'] = "D d M, yy";
        $formats_arr['date_and_time']['dtfmt_52']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_52']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_52']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_53']['dateFormat'] = "D d M, yy";
        $formats_arr['date_and_time']['dtfmt_53']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_53']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_53']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_54']['dateFormat'] = "D d M, yy";
        $formats_arr['date_and_time']['dtfmt_54']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_54']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_54']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_55']['dateFormat'] = "DD, MM d, yy";
        $formats_arr['date_and_time']['dtfmt_55']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_55']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_55']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_56']['dateFormat'] = "DD, MM d, yy";
        $formats_arr['date_and_time']['dtfmt_56']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_56']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_56']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_57']['dateFormat'] = "DD, MM d, yy";
        $formats_arr['date_and_time']['dtfmt_57']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_57']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_57']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_58']['dateFormat'] = "DD, d MM, yy";
        $formats_arr['date_and_time']['dtfmt_58']['timeFormat'] = "HH:mm:ss";
        $formats_arr['date_and_time']['dtfmt_58']['showSecond'] = TRUE;
        $formats_arr['date_and_time']['dtfmt_58']['ampm'] = FALSE;

        $formats_arr['date_and_time']['dtfmt_59']['dateFormat'] = "DD, d MM, yy";
        $formats_arr['date_and_time']['dtfmt_59']['timeFormat'] = "h:mm TT";
        $formats_arr['date_and_time']['dtfmt_59']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_59']['ampm'] = TRUE;

        $formats_arr['date_and_time']['dtfmt_60']['dateFormat'] = "DD, d MM, yy";
        $formats_arr['date_and_time']['dtfmt_60']['timeFormat'] = "HH:mm";
        $formats_arr['date_and_time']['dtfmt_60']['showSecond'] = FALSE;
        $formats_arr['date_and_time']['dtfmt_60']['ampm'] = FALSE;

        //time formats
        $formats_arr['time']['tfmt_1']['timeFormat'] = "h:mm TT";
        $formats_arr['time']['tfmt_1']['showSecond'] = FALSE;
        $formats_arr['time']['tfmt_1']['ampm'] = TRUE;

        $formats_arr['time']['tfmt_2']['timeFormat'] = "HH:mm:ss";
        $formats_arr['time']['tfmt_2']['showSecond'] = TRUE;
        $formats_arr['time']['tfmt_2']['ampm'] = FALSE;

        $formats_arr['time']['tfmt_3']['timeFormat'] = "HH:mm";
        $formats_arr['time']['tfmt_3']['showSecond'] = FALSE;
        $formats_arr['time']['tfmt_3']['ampm'] = FALSE;

        if ($fmt == '') {
            switch ($type) {
                case 'date':
                    $fmt = 'dfmt_1';
                    break;
                case 'date_and_time':
                    $fmt = 'dtfmt_1';
                    break;
                case 'time':
                    $fmt = 'tfmt_1';
                    break;
            }
        }
        return $formats_arr[$type][$fmt];
    }
}

if (!function_exists('_date_time_momentjs_format')) {

    /**
     * dateTimeJSMoments function
     *
     * @return	js format for moment.
     */
    function _date_time_momentjs_format($type = '', $fmt = '')
    {
        //date formats
        $moments_arr['date']['dfmt_1'] = "YYYY-MM-DD";
        $moments_arr['date']['dfmt_2'] = "MM-DD-YYYY";
        $moments_arr['date']['dfmt_3'] = "DD-MM-YYYY";
        $moments_arr['date']['dfmt_4'] = "MM/DD/YYYY";
        $moments_arr['date']['dfmt_5'] = "DD/MM/YYYY";
        $moments_arr['date']['dfmt_6'] = "YYYY/MM/DD";
        $moments_arr['date']['dfmt_7'] = "DD.MM.YYYY";
        $moments_arr['date']['dfmt_8'] = "YYYY.MM.DD";
        $moments_arr['date']['dfmt_9'] = "MM.DD.YYYY";
        $moments_arr['date']['dfmt_10'] = "MMM DD, YYYY";
        $moments_arr['date']['dfmt_11'] = "DD MMM, YYYY";
        $moments_arr['date']['dfmt_12'] = "DD.MMM.YYYY";
        $moments_arr['date']['dfmt_13'] = "DD/MMM/YYYY";
        $moments_arr['date']['dfmt_14'] = "DD-MMM-YYYY";
        $moments_arr['date']['dfmt_15'] = "MMMM DD, YYYY";
        $moments_arr['date']['dfmt_16'] = "DD MMMM, YYYY";
        $moments_arr['date']['dfmt_17'] = "ddd MMM DD, YYYY";
        $moments_arr['date']['dfmt_18'] = "ddd DD MMM, YYYY";
        $moments_arr['date']['dfmt_19'] = "dddd, MMMM DD, YYYY";
        $moments_arr['date']['dfmt_20'] = "dddd, DD MMMM, YYYY";

        //date time formats
        $moments_arr['date_and_time']['dtfmt_1'] = "YYYY-MM-DD HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_2'] = "YYYY-MM-DD hh:mm A";
        $moments_arr['date_and_time']['dtfmt_3'] = "YYYY-MM-DD HH:mm";
        $moments_arr['date_and_time']['dtfmt_4'] = "MM-DD-YYYY HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_5'] = "MM-DD-YYYY hh:mm A";
        $moments_arr['date_and_time']['dtfmt_6'] = "Myy.mm.ddM-DD-YYYY HH:mm";
        $moments_arr['date_and_time']['dtfmt_7'] = "DD-MM-YYYY HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_8'] = "DD-MM-YYYY hh:mm A";
        $moments_arr['date_and_time']['dtfmt_9'] = "DD-MM-YYYY HH:mm";
        $moments_arr['date_and_time']['dtfmt_10'] = "MM/DD/YYYY HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_11'] = "MM/DD/YYYY hh:mm A";
        $moments_arr['date_and_time']['dtfmt_12'] = "MM/DD/YYYY HH:mm";
        $moments_arr['date_and_time']['dtfmt_13'] = "DD/MM/YYYY HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_14'] = "DD/MM/YYYY hh:mm A";
        $moments_arr['date_and_time']['dtfmt_15'] = "DD/MM/YYYY HH:mm";
        $moments_arr['date_and_time']['dtfmt_16'] = "YYYY/MM/DD HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_17'] = "YYYY/MM/DD hh:mm A";
        $moments_arr['date_and_time']['dtfmt_18'] = "YYYY/MM/DD HH:mm";
        $moments_arr['date_and_time']['dtfmt_19'] = "DD.MM.YYYY HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_20'] = "DD.MM.YYYY hh:mm A";
        $moments_arr['date_and_time']['dtfmt_21'] = "DD.MM.YYYY HH:mm";
        $moments_arr['date_and_time']['dtfmt_22'] = "YYYY.MM.DD HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_23'] = "YYYY.MM.DD hh:mm A";
        $moments_arr['date_and_time']['dtfmt_24'] = "YYYY.MM.DD HH:mm";
        $moments_arr['date_and_time']['dtfmt_25'] = "MM.DD.YYYY HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_26'] = "MM.DD.YYYY hh:mm A";
        $moments_arr['date_and_time']['dtfmt_27'] = "MM.DD.YYYY HH:mm";
        $moments_arr['date_and_time']['dtfmt_28'] = "MMM DD, YYYY HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_29'] = "MMM DD, YYYY hh:mm A";
        $moments_arr['date_and_time']['dtfmt_30'] = "MMM DD, YYYY HH:mm";
        $moments_arr['date_and_time']['dtfmt_31'] = "DD MMM, YYYY HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_32'] = "DD MMM, YYYY hh:mm A";
        $moments_arr['date_and_time']['dtfmt_33'] = "DD MMM, YYYY HH:mm";
        $moments_arr['date_and_time']['dtfmt_34'] = "DD.MMM.YYYY HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_35'] = "DD.MMM.YYYY hh:mm A";
        $moments_arr['date_and_time']['dtfmt_36'] = "DD.MMM.YYYY HH:mm";
        $moments_arr['date_and_time']['dtfmt_37'] = "DD/MMM/YYYY HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_38'] = "DD/MMM/YYYY hh:mm A";
        $moments_arr['date_and_time']['dtfmt_39'] = "DD/MMM/YYYY HH:mm";
        $moments_arr['date_and_time']['dtfmt_40'] = "DD-MMM-YYYY HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_41'] = "DD-MMM-YYYY hh:mm A";
        $moments_arr['date_and_time']['dtfmt_42'] = "DD-MMM-YYYY HH:mm";
        $moments_arr['date_and_time']['dtfmt_43'] = "MMMM DD, YYYY HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_44'] = "MMMM DD, YYYY hh:mm A";
        $moments_arr['date_and_time']['dtfmt_45'] = "MMMM DD, YYYY HH:mm";
        $moments_arr['date_and_time']['dtfmt_46'] = "DD MMMM, YYYY HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_47'] = "DD MMMM, YYYY hh:mm A";
        $moments_arr['date_and_time']['dtfmt_48'] = "DD MMMM, YYYY HH:mm";
        $moments_arr['date_and_time']['dtfmt_49'] = "ddd MMM DD, YYYY HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_50'] = "ddd MMM DD, YYYY hh:mm A";
        $moments_arr['date_and_time']['dtfmt_51'] = "ddd MMM DD, YYYY HH:mm";
        $moments_arr['date_and_time']['dtfmt_52'] = "ddd DD MMM, YYYY HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_53'] = "ddd DD MMM, YYYY hh:mm A";
        $moments_arr['date_and_time']['dtfmt_54'] = "ddd DD MMM, YYYY HH:mm";
        $moments_arr['date_and_time']['dtfmt_55'] = "dddd, MMMM DD, YYYY HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_56'] = "dddd, MMMM DD, YYYY hh:mm A";
        $moments_arr['date_and_time']['dtfmt_57'] = "dddd, MMMM DD, YYYY HH:mm";
        $moments_arr['date_and_time']['dtfmt_58'] = "dddd, DD MMMM, YYYY HH:mm:ss";
        $moments_arr['date_and_time']['dtfmt_59'] = "dddd, DD MMMM, YYYY hh:mm A";
        $moments_arr['date_and_time']['dtfmt_60'] = "dddd, DD MMMM, YYYY HH:mm";

        //time formats
        $moments_arr['time']['tfmt_1'] = "h:mm A";
        $moments_arr['time']['tfmt_2'] = "HH:mm:ss";
        $moments_arr['time']['tfmt_3'] = "HH:mm";

        if ($fmt == '') {
            switch ($type) {
                case 'date':
                    $fmt = 'dfmt_1';
                    break;
                case 'date_and_time':
                    $fmt = 'dtfmt_1';
                    break;
                case 'time':
                    $fmt = 'tfmt_1';
                    break;
            }
        }
        return $moments_arr[$type][$fmt];
    }
}

if (!function_exists('_date_time_label_format')) {

    /**
     * getDateTimeFormatDropdown function
     *
     * @return	format for dropdown.
     */
    function _date_time_label_format($format_type = "", $value = "")
    {
        $ret_val = "";
        if ($format_type != "") {
            if ($format_type == "ADMIN_DATE_FORMAT") {

                $format_date_arr['dfmt_1'] = "2011-11-30 (yy-mm-dd)";
                $format_date_arr['dfmt_2'] = "04-30-2016 (mm-dd-yy)";
                $format_date_arr['dfmt_3'] = "30-04-2016 (dd-mm-yy)";
                $format_date_arr['dfmt_4'] = "11/30/2011 (mm/dd/yy)";
                $format_date_arr['dfmt_5'] = "30/11/2011 (dd/mm/yy)";
                $format_date_arr['dfmt_6'] = "2016/04/30 (yy/mm/dd)";
                $format_date_arr['dfmt_7'] = "30.08.2011 (dd.mm.yy)";
                $format_date_arr['dfmt_8'] = "2016.04.30 (yy.mm.dd)";
                $format_date_arr['dfmt_9'] = "04.30.2016 (mm.dd.yy)";
                $format_date_arr['dfmt_10'] = "Nov 30, 2011";
                $format_date_arr['dfmt_11'] = "30 Apr, 2016";
                $format_date_arr['dfmt_12'] = "30.Apr.2016";
                $format_date_arr['dfmt_13'] = "30/Apr/2016";
                $format_date_arr['dfmt_14'] = "30-Apr-2016";
                $format_date_arr['dfmt_15'] = "November 30, 2011";
                $format_date_arr['dfmt_16'] = "30 April, 2016";
                $format_date_arr['dfmt_17'] = "Wed Nov 30, 2011";
                $format_date_arr['dfmt_18'] = "Sat 30 Apr, 2016";
                $format_date_arr['dfmt_19'] = "Wednesday, November 30, 2011";
                $format_date_arr['dfmt_20'] = "Saturday, 30 April, 2016";

                $ret_val = $format_date_arr[$value];
            } elseif ($format_type == "ADMIN_DATE_TIME_FORMAT") {

                $format_date_time_arr['dtfmt_1'] = "2011-11-30 20:00:00 (yy-mm-dd hh:mm:ss)";
                $format_date_time_arr['dtfmt_2'] = "2011-11-30 8:00 PM (yy-mm-dd hh:mm am/pm)";
                $format_date_time_arr['dtfmt_3'] = "2011-11-30 20:00 (yy-mm-dd hh:mm)";
                $format_date_time_arr['dtfmt_4'] = "04-30-2016 19:40:00 (mm-dd-yy hh:mm:ss)";
                $format_date_time_arr['dtfmt_5'] = "04-30-2016 5:44 PM (mm-dd-yy hh:mm am/pm)";
                $format_date_time_arr['dtfmt_6'] = "04-30-2016 18:42 (mm-dd-yy hh:mm)";
                $format_date_time_arr['dtfmt_7'] = "30-04-2016 16:43:00 (dd-mm-yy hh:mm:ss)";
                $format_date_time_arr['dtfmt_8'] = "30-04-2016 1:51 PM (dd-mm-yy hh:mm am/pm)";
                $format_date_time_arr['dtfmt_9'] = "24-04-2016 16:31 (dd-mm-yy hh:mm)";
                $format_date_time_arr['dtfmt_10'] = "04/30/2016 14:53:00 (mm/dd/yy hh:mm:ss)";
                $format_date_time_arr['dtfmt_11'] = "11/30/2011 8:00 PM (mm/dd/yy hh:mm am/pm)";
                $format_date_time_arr['dtfmt_12'] = "04/24/2016 19:38 (mm/dd/yy hh:mm)";
                $format_date_time_arr['dtfmt_13'] = "30/04/2016 19:40:00 (dd/mm/yy hh:mm:ss)";
                $format_date_time_arr['dtfmt_14'] = "30/11/2011 8:00 PM (dd/mm/yy hh:mm am/pm)";
                $format_date_time_arr['dtfmt_15'] = "30/04/2016 19:41 (dd/mm/yy hh:mm)";
                $format_date_time_arr['dtfmt_16'] = "2016/04/30 21:34:00 (yy/mm/dd hh:mm:ss)";
                $format_date_time_arr['dtfmt_17'] = "2016/04/29 6:41 PM (yy/mm/dd hh:mm am/pm)";
                $format_date_time_arr['dtfmt_18'] = "2016/04/24 20:48 (yy/mm/dd hh:mm)";
                $format_date_time_arr['dtfmt_19'] = "29.04.2016 16:51:00 (dd.mm.yy hh:mm:ss)";
                $format_date_time_arr['dtfmt_20'] = "27.04.2016 7:37 PM (dd.mm.yy hh:mm am/pm)";
                $format_date_time_arr['dtfmt_21'] = "30.08.2011 20:00 (dd.mm.yy h:m)";
                $format_date_time_arr['dtfmt_22'] = "2016.04.22 16:29:00 (yy.mm.dd hh:mm:ss)";
                $format_date_time_arr['dtfmt_23'] = "2016.04.25 8:46 PM (yy.mm.dd hh:mm am/pm)";
                $format_date_time_arr['dtfmt_24'] = "2016.04.28 21:32 (yy.mm.dd hh:mm)";
                $format_date_time_arr['dtfmt_25'] = "04.23.2016 20:47:00 (mm.dd.yy hh:mm:ss)";
                $format_date_time_arr['dtfmt_26'] = "04.19.2016 7:46 PM (mm.dd.yy hh:mm am/pm)";
                $format_date_time_arr['dtfmt_27'] = "04.17.2016 21:40 (mm.dd.yy hh:mm)";
                $format_date_time_arr['dtfmt_28'] = "Apr 22, 2016 19:41:00";
                $format_date_time_arr['dtfmt_29'] = "Apr 30, 2016 8:31 PM";
                $format_date_time_arr['dtfmt_30'] = "Apr 16, 2016 15:29";
                $format_date_time_arr['dtfmt_31'] = "12 Apr, 2016 16:44:00";
                $format_date_time_arr['dtfmt_32'] = "18 Apr, 2016 8:34 PM";
                $format_date_time_arr['dtfmt_33'] = "23 Apr, 2016 16:34";
                $format_date_time_arr['dtfmt_34'] = "25.Apr.2016 18:33:00";
                $format_date_time_arr['dtfmt_35'] = "20.Apr.2016 8:30 PM";
                $format_date_time_arr['dtfmt_36'] = "29.Apr.2016 18:33";
                $format_date_time_arr['dtfmt_37'] = "24/Apr/2016 20:44:00";
                $format_date_time_arr['dtfmt_38'] = "23/Apr/2016 10:34 PM";
                $format_date_time_arr['dtfmt_39'] = "25/Apr/2016 17:43";
                $format_date_time_arr['dtfmt_40'] = "28-Apr-2016 18:31:00";
                $format_date_time_arr['dtfmt_41'] = "23-Apr-2016 6:35 PM";
                $format_date_time_arr['dtfmt_42'] = "30-Apr-2016 18:40";
                $format_date_time_arr['dtfmt_43'] = "April 30, 2016 08:43:00";
                $format_date_time_arr['dtfmt_44'] = "April 23, 2016 10:45 AM";
                $format_date_time_arr['dtfmt_45'] = "April 16, 2016 02:21";
                $format_date_time_arr['dtfmt_46'] = "23 April, 2016 05:36:00";
                $format_date_time_arr['dtfmt_47'] = "23 April, 2016 8:37 AM";
                $format_date_time_arr['dtfmt_48'] = "30 April, 2016 15:44";
                $format_date_time_arr['dtfmt_49'] = "Fri Apr 29, 2016 17:22:00";
                $format_date_time_arr['dtfmt_50'] = "Sat Apr 30, 2016 6:32 PM";
                $format_date_time_arr['dtfmt_51'] = "Sat Apr 30, 2016 10:46";
                $format_date_time_arr['dtfmt_52'] = "Tue 26 Apr, 2016 18:27:00";
                $format_date_time_arr['dtfmt_53'] = "Sat 30 Apr, 2016 12:29 PM";
                $format_date_time_arr['dtfmt_54'] = "Sat 30 Apr, 2016 14:25";
                $format_date_time_arr['dtfmt_55'] = "Saturday, April 30, 2016 11:28:00";
                $format_date_time_arr['dtfmt_56'] = "Saturday, April 30, 2016 11:46 AM";
                $format_date_time_arr['dtfmt_57'] = "Saturday, April 30, 2016 09:42";
                $format_date_time_arr['dtfmt_58'] = "Saturday, 30 April, 2016 17:29:00";
                $format_date_time_arr['dtfmt_59'] = "Saturday, 30 April, 2016 4:29 PM";
                $format_date_time_arr['dtfmt_60'] = "Saturday, 30 April, 2016 12:30";

                $ret_val = $format_date_time_arr[$value];
            } elseif ($format_type = "ADMIN_TIME_FORMAT") {
                switch ($value) {
                    case 'tfmt_1':
                        $ret_val = "8:00 AM";
                        break;
                    case 'tfmt_2':
                        $ret_val = "16:00:00";
                        break;
                    case 'tfmt_3':
                        $ret_val = "16:00";
                        break;
                }
            }
        }
        return $ret_val;
    }
}

if (!function_exists('_unsupported_date_formats')) {

    /**
     * _unsupported_date_formats function
     *
     * @return	unsupported date formats
     */
    function _unsupported_date_formats()
    {
        $date_arr = array('m-d-Y', 'd/m/Y', 'Y.m.d', 'm.d.Y', 'd/M/Y');
        return $date_arr;
    }
}

if (!function_exists('_unsupported_date_time_formats')) {

    /**
     * _unsupported_date_time_formats function
     *
     * @return	unsupported date time formats
     */
    function _unsupported_date_time_formats()
    {
        $date_time_arr = array(
            'm-d-Y H:i:s', 'm-d-Y h:i A', 'm-d-Y H:i', 'd/m/Y H:i:s', 'd/m/Y h:i A', 'd/m/Y H:i',
            'Y.m.d H:i:s', 'Y.m.d h:i A', 'Y.m.d H:i', 'm.d.Y H:i:s', 'm.d.Y h:i A', 'm.d.Y H:i',
            'd M, Y H:i:s', 'd M, Y h:i A', 'd M, Y H:i', 'd/M/Y H:i:s', 'd/M/Y h:i A', 'd/M/Y H:i',
            'd F, Y H:i:s', 'd F, Y h:i A', 'd F, Y H:i', 'D d M, Y H:i:s', 'D d M, Y h:i A',
            'D d M, Y H:i', 'l, d F, Y H:i:s', 'l, d F, Y h:i A', 'l, d F, Y H:i'
        );
        return $date_time_arr;
    }
}

if (!function_exists('_render_client_custom_date')) {

    /**
     * _render_client_custom_date function
     *
     * @return	client custom date value
     */
    function _render_client_custom_date($format = '', $value = '')
    {
        switch ($format) {
            case 'm-d-Y':
                $format = 'm/d/Y';
                $old_opt = "-";
                $new_opt = "/";
                break;
            case 'd/m/Y':
                $format = 'd.m.Y';
                $old_opt = "/";
                $new_opt = ".";
                break;
            case 'Y.m.d':
                $format = 'Y/m/d';
                $old_opt = ".";
                $new_opt = "/";
                break;
            case 'm.d.Y':
                $format = 'm/d/Y';
                $old_opt = ".";
                $new_opt = "/";
                break;
            case 'd/M/Y':
                $format = 'd.M.Y';
                $old_opt = "/";
                $new_opt = ".";
                break;
        }
        $value = date($format, strtotime($value));
        $value = str_replace($new_opt, $old_opt, $value);
        return $value;
    }
}

if (!function_exists('_render_client_custom_date_time')) {

    /**
     * _render_client_custom_date_time function
     *
     * @return	client custom date time value
     */
    function _render_client_custom_date_time($format = '', $value = '')
    {
        switch ($format) {
            case 'm-d-Y H:i:s':
                $format = 'm/d/Y H:i:s';
                $old_opt = "-";
                $new_opt = "/";
                break;
            case 'm-d-Y h:i A':
                $format = 'm/d/Y h:i A';
                $old_opt = "-";
                $new_opt = "/";
                break;
            case 'm-d-Y H:i':
                $format = 'm/d/Y H:i';
                $old_opt = "-";
                $new_opt = "/";
                break;
            case 'd/m/Y H:i:s':
                $format = 'd.m.Y H:i:s';
                $old_opt = "/";
                $new_opt = ".";
                break;
            case 'd/m/Y h:i A':
                $format = 'd.m.Y h:i A';
                $old_opt = "/";
                $new_opt = ".";
                break;
            case 'd/m/Y H:i':
                $format = 'd.m.Y H:i';
                $old_opt = "/";
                $new_opt = ".";
                break;
            case 'Y.m.d H:i:s':
                $format = 'Y/m/d H:i:s';
                $old_opt = ".";
                $new_opt = "/";
                break;
            case 'Y.m.d h:i A':
                $format = 'Y/m/d h:i A';
                $old_opt = ".";
                $new_opt = "/";
                break;
            case 'Y.m.d H:i':
                $format = 'Y/m/d H:i';
                $old_opt = ".";
                $new_opt = "/";
                break;
            case 'm.d.Y H:i:s':
                $format = 'm/d/Y H:i:s';
                $old_opt = ".";
                $new_opt = "/";
                break;
            case 'm.d.Y h:i A':
                $format = 'm/d/Y h:i A';
                $old_opt = ".";
                $new_opt = "/";
                break;
            case 'm.d.Y H:i':
                $format = 'm/d/Y H:i';
                $old_opt = ".";
                $new_opt = "/";
                break;
            case 'd M, Y H:i:s':
                $format = 'M d, Y H:i:s';
                break;
            case 'd M, Y h:i A':
                $format = 'M d, Y h:i A';
                break;
            case 'd M, Y H:i':
                $format = 'M d, Y H:i';
                break;
            case 'd/M/Y H:i:s':
                $format = 'd.M.Y H:i:s';
                $old_opt = "/";
                $new_opt = ".";
                break;
            case 'd/M/Y h:i A':
                $format = 'd.M.Y h:i A';
                $old_opt = "/";
                $new_opt = ".";
                break;
            case 'd/M/Y H:i':
                $format = 'd.M.Y H:i';
                $old_opt = "/";
                $new_opt = ".";
                break;
            case 'd F, Y H:i:s':
                $format = 'F d, Y H:i:s';
                break;
            case 'd F, Y h:i A':
                $format = 'F d, Y h:i A';
                break;
            case 'd F, Y H:i':
                $format = 'F d, Y H:i';
                break;
            case 'D d M, Y H:i:s':
                $format = 'D M d, Y H:i:s';
                break;
            case 'D d M, Y h:i A':
                $format = 'D M d, Y h:i A';
                break;
            case 'D d M, Y H:i':
                $format = 'D M d, Y H:i';
                break;
            case 'l, d F, Y H:i:s':
                $format = 'l, F d, Y H:i:s';
                break;
            case 'l, d F, Y h:i A':
                $format = 'l, F d, Y h:i A';
                break;
            case 'l, d F, Y H:i':
                $format = 'l, F d, Y H:i';
                break;
        }
        $format1_arr = array(
            'd M, Y H:i:s', 'd M, Y h:i A', 'd M, Y H:i',
            'd F, Y H:i:s', 'd F, Y h:i A', 'd F, Y H:i'
        );
        $format2_arr = array(
            'D d M, Y H:i:s', 'D d M, Y h:i A', 'D d M, Y H:i',
            'l, d F, Y H:i:s', 'l, d F, Y h:i A', 'l, d F, Y H:i'
        );
        if (in_array($format, $format1_arr)) {
            $value = date($format, strtotime($value));
            $temp1_arr = explode(",", $value);
            $temp2_arr = explode(" ", $temp1_arr[0]);
            $value = $temp2_arr[1] . " " . $temp2_arr[0] . "," . $temp1_arr[1];
        } elseif (in_array($format, $format2_arr)) {
            $value = date($format, strtotime($value));
            $temp1_arr = explode(",", $value);
            $temp2_arr = explode(" ", $temp1_arr[0]);
            $value = $temp2_arr[0] . " " . $temp2_arr[2] . " " . $temp2_arr[1] . "," . $temp1_arr[1];
        } else {
            $value = date($format, strtotime($value));
            $value = str_replace($new_opt, $old_opt, $value);
        }
        return $value;
    }
}

if (!function_exists('_render_server_custom_date')) {

    /**
     * _render_server_custom_date function
     *
     * @return	server custom date value
     */
    function _render_server_custom_date($format = '', $value = '')
    {
        switch ($format) {
            case 'm-d-Y':
                $value = str_replace("-", "/", $value);
                break;
            case 'd/m/Y':
            case 'd/M/Y':
                $value = str_replace("/", ".", $value);
                break;
            case 'Y.m.d':
            case 'm.d.Y':
                $value = str_replace(".", "/", $value);
                break;
            case 'd M, Y':
            case 'd F, Y':
                $value = str_replace(",", " ", $value);
                break;
        }
        $value = (strtotime($value)) ? date("Y-m-d", strtotime($value)) : "";
        return $value;
    }
}

if (!function_exists('_render_server_custom_date_time')) {

    /**
     * _render_server_custom_date_time function
     *
     * @return	server custom date time value
     */
    function _render_server_custom_date_time($format = '', $value = '')
    {
        switch ($format) {
            case 'm-d-Y H:i:s':
            case 'm-d-Y h:i A':
            case 'm-d-Y H:i':
                $value = str_replace("-", "/", $value);
                break;
            case 'd/m/Y H:i:s':
            case 'd/m/Y h:i A':
            case 'd/m/Y H:i':
            case 'd/M/Y H:i:s':
            case 'd/M/Y h:i A':
            case 'd/M/Y H:i':
                $value = str_replace("/", ".", $value);
                break;
            case 'Y.m.d H:i:s':
            case 'Y.m.d h:i A':
            case 'Y.m.d H:i':
            case 'm.d.Y H:i:s':
            case 'm.d.Y h:i A':
            case 'm.d.Y H:i':
                $value = str_replace(".", "/", $value);
                break;
            case 'd M, Y H:i:s':
            case 'd M, Y h:i A':
            case 'd M, Y H:i':
            case 'd F, Y H:i:s':
            case 'd F, Y h:i A':
            case 'd F, Y H:i':
                $temp1_arr = explode(",", $value);
                $temp2_arr = explode(" ", $temp1_arr[0]);
                $value = $temp2_arr[1] . " " . $temp2_arr[0] . "," . $temp1_arr[1];
                break;
            case 'D d M, Y H:i:s':
            case 'D d M, Y h:i A':
            case 'D d M, Y H:i':
            case 'l, d F, Y H:i:s':
            case 'l, d F, Y h:i A':
            case 'l, d F, Y H:i':
                $temp1_arr = explode(",", $value);
                $temp2_arr = explode(" ", $temp1_arr[0]);
                $value = $temp2_arr[0] . " " . $temp2_arr[2] . " " . $temp2_arr[1] . "," . $temp1_arr[1];
                break;
        }
        $value = (strtotime($value)) ? date("Y-m-d H:i:s", strtotime($value)) : "";
        return $value;
    }
}

if (!function_exists('_check_server_custom_date')) {

    /**
     * _check_server_custom_date function
     *
     * @return boolean either TRUE or FALSE
     */
    function _check_server_custom_date($format = '', $value = '')
    {
        switch ($format) {
            case 'm-d-Y':
                $format = "m/d/Y";
                $value = str_replace("-", "/", $value);
                break;
            case 'd/m/Y':
                $format = "d.m.Y";
                $value = str_replace("/", ".", $value);    
                break;
            case 'd/M/Y':
                $format = "d.M.Y";
                $value = str_replace("/", ".", $value);                 
                break;
            case 'Y.m.d':
                $format = "Y/m/d";
                $value = str_replace(".", "/", $value);
                break;
            case 'm.d.Y':
                $format = "m/d/Y";
                $value = str_replace(".", "/", $value);
                break;
            case 'd M, Y':
                $format = "d M  Y";
                $value = str_replace(",", " ", $value);
                break;
            case 'd F, Y':
                $format = "d F  Y";
                $value = str_replace(",", " ", $value);
                break;
        }
        $d = DateTime::createFromFormat($format, $value);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $value;
    }
}