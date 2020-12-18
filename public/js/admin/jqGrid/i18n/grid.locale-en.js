;
(function ($) {
    $.jgrid = $.jgrid || {};
    $.extend($.jgrid, {
        captions: {
            success: js_lang_label.GENERIC_GRID_SUCCESS_MESSAGE
        },
        defaults: {
            recordtext: js_lang_label.GENERIC_GRID_VIEW + " {0} - {1} " + js_lang_label.GENERIC_GRID_OF + " {2}",
            emptyrecords: js_lang_label.GENERIC_GRID_NO_RECORDS_TO_VIEW,
            loadtext: js_lang_label.GENERIC_GRID_LOADING + "...",
            pgtext: js_lang_label.GENERIC_GRID_PAGE + " {0} " + js_lang_label.GENERIC_GRID_OF + " {1}",
            lvtext: {
                table: js_lang_label.GENERIC_GRID_TABLE_VIEW,
                view: js_lang_label.GENERIC_GRID_LIST_VIEW,
                grid: js_lang_label.GENERIC_GRID_GRID_VIEW
            },
            quicktext: js_lang_label.GENERIC_GRID_QUICK_SEARCH,
            savesearch: js_lang_label.GENERIC_GRID_SAVE_SEARCH,
            searchlist: js_lang_label.GENERIC_GRID_SEARCH_LIST,
            chviewtext: js_lang_label.GENERIC_GRID_CHANGE_VIEW,
            chsorttext: js_lang_label.GENERIC_GRID_CHANGE_SORTING,
            chgrouptext: js_lang_label.GENERIC_GRID_CHANGE_GROUPING,
            dfiltertext: js_lang_label.GENERIC_GRID_SELECT_DATES_TO_FILTER_DATA
        },
        search: {
            caption: js_lang_label.GENERIC_GRID_SEARCH + "...",
            Find: js_lang_label.GENERIC_GRID_FIND,
            Reset: js_lang_label.GENERIC_GRID_RESET,
            odata: [
                {oper: 'bt', text: (js_lang_label.GENERIC_BETWEEN ? js_lang_label.GENERIC_BETWEEN : "between")},
                {oper: 'nb', text: (js_lang_label.GENERIC_NOT_BETWEEN ? js_lang_label.GENERIC_NOT_BETWEEN : "not between")},
                {oper: 'bw', text: (js_lang_label.GENERIC_BEGINS_WITH ? js_lang_label.GENERIC_BEGINS_WITH : "begins with")},
                {oper: 'bn', text: (js_lang_label.GENERIC_DOES_NOT_BEGIN_WITH ? js_lang_label.GENERIC_DOES_NOT_BEGIN_WITH : "does not begins with")},
                {oper: 'ew', text: (js_lang_label.GENERIC_ENDS_WITH ? js_lang_label.GENERIC_ENDS_WITH : "ends with")},
                {oper: 'en', text: (js_lang_label.GENERIC_DOES_NOT_END_WITH ? js_lang_label.GENERIC_DOES_NOT_END_WITH : "does not end with")},
                {oper: 'cn', text: (js_lang_label.GENERIC_CONTAINS ? js_lang_label.GENERIC_CONTAINS : "contains")},
                {oper: 'nc', text: (js_lang_label.GENERIC_DOES_NOT_CONTAIN ? js_lang_label.GENERIC_DOES_NOT_CONTAIN : "does not contain")},
                {oper: 'mw', text: (js_lang_label.GENERIC_MATCH_WITH ? js_lang_label.GENERIC_MATCH_WITH : "match with")},
                {oper: 'eq', text: (js_lang_label.GENERIC_EQUAL ? js_lang_label.GENERIC_EQUAL : "equal")},
                {oper: 'ne', text: (js_lang_label.GENERIC_NOT_EQUAL ? js_lang_label.GENERIC_NOT_EQUAL : "not equal")},
                {oper: 'lt', text: (js_lang_label.GENERIC_LESS ? js_lang_label.GENERIC_LESS : "less")},
                {oper: 'le', text: (js_lang_label.GENERIC_LESS_OR_EQUAL ? js_lang_label.GENERIC_LESS_OR_EQUAL : "less or equal")},
                {oper: 'gt', text: (js_lang_label.GENERIC_GREATER ? js_lang_label.GENERIC_GREATER : "greater")},
                {oper: 'ge', text: (js_lang_label.GENERIC_GREATER_OR_EQUAL ? js_lang_label.GENERIC_GREATER_OR_EQUAL : "greater or equal")},
                {oper: 'in', text: (js_lang_label.GENERIC_IS_IN ? js_lang_label.GENERIC_IS_IN : "is in")},
                {oper: 'ni', text: (js_lang_label.GENERIC_IS_NOT_IN ? js_lang_label.GENERIC_IS_NOT_IN : "is not in")},
                {oper: 'nu', text: (js_lang_label.GENERIC_IS_EMPTY ? js_lang_label.GENERIC_IS_EMPTY : "is empty")},
                {oper: 'nn', text: (js_lang_label.GENERIC_IS_NOT_EMPTY ? js_lang_label.GENERIC_IS_NOT_EMPTY : "is not empty")}
            ],
            groupOps: [
                {op: "AND", text: js_lang_label.GENERIC_GRID_ALL},
                {op: "OR", text: js_lang_label.GENERIC_GRID_ANY}
            ],
            operandTitle: js_lang_label.GENERIC_CLICK_TO_SELECT_SEARCH_OPERATION,
            resetTitle: js_lang_label.GENERIC_RESET_SEARCH_VALUE
        },
        edit: {
            addCaption: js_lang_label.GENERIC_GRID_ADD_RECORD,
            editCaption: js_lang_label.GENERIC_GRID_EDIT_RECORD,
            bSubmit: js_lang_label.GENERIC_GRID_SUBMIT,
            bCancel: js_lang_label.GENERIC_GRID_ADD_CANCEL,
            bClose: js_lang_label.GENERIC_GRID_CLOSE,
            saveData: js_lang_label.GENERIC_GRID_GENERIC_GRID_DATA_HAS_BEEN_CHANGED_SAVE_CHANGES,
            bYes: js_lang_label.GENERIC_GRID_YES,
            bNo: js_lang_label.GENERIC_GRID_NO,
            bExit: js_lang_label.GENERIC_GRID_CANCEL,
            msg: {
                required: js_lang_label.GENERIC_GRID_FIELD_IS_REQUIRED,
                number: js_lang_label.GENERIC_GRID_PLEASE_ENTER_VALID_NUMBER,
                minValue: js_lang_label.GENERIC_GRID_VALUE_MUST_BE_GREATER_THAN_OR_EQUAL_TO,
                maxValue: js_lang_label.GENERIC_GRID_VALUE_MUST_BE_LESS_THAN_OR_EQUAL_TO,
                email: " " + js_lang_label.GENERIC_GRID_IS_NOT_A_VALID_EMAIL,
                integer: js_lang_label.GENERIC_GRID_PLEASE_ENTER_VALID_INTEGER_VALUE,
                date: js_lang_label.GENERIC_GRID_PLEASE_ENTER_VALID_DATE_VALUE,
                url: js_lang_label.GENERIC_GRID_IS_NOT_A_VALID_URL_PREFIX_REQUIRED_HTTP_OR_HTTPS,
                nodefined: " " + js_lang_label.GENERIC_GRID_IS_NOT_DEFINED,
                novalue: " " + js_lang_label.GENERIC_GRID_RETURN_VALUE_IS_REQUIRED,
                customarray: js_lang_label.GENERIC_GRID_CUSTOM_FUNCTION_SHOULD_RETURN_ARRAY,
                customfcheck: js_lang_label.CUSTOM_FUNCTION_SHOULD_BE_PRESENT_IN_CASE_OF_CUSTOM_CHECKING

            }
        },
        view: {
            caption: js_lang_label.GENERIC_GRID_VIEW_RECORD,
            bClose: js_lang_label.GENERIC_GRID_CLOSE
        },
        del: {
            caption: js_lang_label.GENERIC_GRID_DELETE,
            msg: js_lang_label.GENERIC_GRID_DELETE_SELECTED_RECORDS,
            bSubmit: js_lang_label.GENERIC_GRID_DELETE,
            bCancel: js_lang_label.GENERIC_GRID_CANCEL
        },
        nav: {
            edittext: "",
            edittitle: js_lang_label.GENERIC_GRID_EDIT_SELECTED_ROW,
            addtext: "",
            addtitle: js_lang_label.GENERIC_GRID_ADD_NEW_ROW,
            deltext: "",
            deltitle: js_lang_label.GENERIC_GRID_DELETE_SELECTED_ROW,
            searchtext: "",
            searchtitle: js_lang_label.GENERIC_GRID_FIND_RECORDS,
            refreshtext: "",
            refreshtitle: js_lang_label.GENERIC_GRID_RELOAD_GRID,
            alertcap: js_lang_label.GENERIC_GRID_WARNING,
            alerttext: js_lang_label.GENERIC_GRID_PLEASE_SELECT_ROW,
            viewtext: "",
            viewtitle: js_lang_label.GENERIC_GRID_VIEW_SELECTED_ROW
        },
        col: {
            caption: js_lang_label.GENERIC_GRID_SELECT_COLUMNS,
            bSubmit: (js_lang_label.GENERIC_GRID_APPLY ? js_lang_label.GENERIC_GRID_APPLY : js_lang_label.GENERIC_GRID_OK),
            bCancel: js_lang_label.GENERIC_GRID_CANCEL
        },
        errors: {
            errcap: js_lang_label.GENERIC_GRID_ERROR,
            nourl: js_lang_label.GENERIC_GRID_NO_URL_IS_SET,
            norecords: js_lang_label.GENERIC_GRID_NO_RECORDS_TO_PROCESS,
            model: js_lang_label.GENERIC_GRID_LENGTH_OF_COLNAMES_DOES_NOT_MATCH_WITH_COLMODEL
        },
        formatter: {
            integer: {
                thousandsSeparator: el_tpl_settings.admin_formats.thousand_seperator,
                defaultValue: '0'
            },
            number: {
                decimalSeparator: el_tpl_settings.admin_formats.decimal_seperator,
                thousandsSeparator: el_tpl_settings.admin_formats.thousand_seperator,
                decimalPlaces: parseInt(el_tpl_settings.admin_formats.decimal_places),
                defaultValue: '0.00'
            },
            currency: {
                decimalSeparator: el_tpl_settings.admin_formats.decimal_seperator,
                thousandsSeparator: el_tpl_settings.admin_formats.thousand_seperator,
                decimalPlaces: parseInt(el_tpl_settings.admin_formats.decimal_places),
                prefix: el_tpl_settings.admin_formats.currency_prefix,
                suffix: el_tpl_settings.admin_formats.currency_suffix,
                defaultValue: '0.00'
            },
            date: {
                dayNames: [
                    "Sun", "Mon", "Tue", "Wed", "Thr", "Fri", "Sat",
                    "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
                ],
                monthNames: [
                    "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
                    "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
                ],
                AmPm: ["am", "pm", "AM", "PM"],
                S: function (j) {
                    return j < 11 || j > 13 ? ['st', 'nd', 'rd', 'th'][Math.min((j - 1) % 10, 3)] : 'th';
                },
                srcformat: 'Y-m-d',
                newformat: 'n/j/Y',
                parseRe: /[#%\\\/:_;.,\t\s-]/,
                masks: {
                    // see http://php.net/manual/en/function.date.php for PHP format used in jqGrid
                    // and see http://docs.jquery.com/UI/Datepicker/formatDate
                    // and https://github.com/jquery/globalize#dates for alternative formats used frequently
                    // one can find on https://github.com/jquery/globalize/tree/master/lib/cultures many
                    // information about date, time, numbers and currency formats used in different countries
                    // one should just convert the information in PHP format
                    ISO8601Long: "Y-m-d H:i:s",
                    ISO8601Short: "Y-m-d",
                    // short date:
                    //    n - Numeric representation of a month, without leading zeros
                    //    j - Day of the month without leading zeros
                    //    Y - A full numeric representation of a year, 4 digits
                    // example: 3/1/2012 which means 1 March 2012
                    ShortDate: "n/j/Y", // in jQuery UI Datepicker: "M/d/yyyy"
                    // long date:
                    //    l - A full textual representation of the day of the week
                    //    F - A full textual representation of a month
                    //    d - Day of the month, 2 digits with leading zeros
                    //    Y - A full numeric representation of a year, 4 digits
                    LongDate: "l, F d, Y", // in jQuery UI Datepicker: "dddd, MMMM dd, yyyy"
                    // long date with long time:
                    //    l - A full textual representation of the day of the week
                    //    F - A full textual representation of a month
                    //    d - Day of the month, 2 digits with leading zeros
                    //    Y - A full numeric representation of a year, 4 digits
                    //    g - 12-hour format of an hour without leading zeros
                    //    i - Minutes with leading zeros
                    //    s - Seconds, with leading zeros
                    //    A - Uppercase Ante meridiem and Post meridiem (AM or PM)
                    FullDateTime: "l, F d, Y g:i:s A", // in jQuery UI Datepicker: "dddd, MMMM dd, yyyy h:mm:ss tt"
                    // month day:
                    //    F - A full textual representation of a month
                    //    d - Day of the month, 2 digits with leading zeros
                    MonthDay: "F d", // in jQuery UI Datepicker: "MMMM dd"
                    // short time (without seconds)
                    //    g - 12-hour format of an hour without leading zeros
                    //    i - Minutes with leading zeros
                    //    A - Uppercase Ante meridiem and Post meridiem (AM or PM)
                    ShortTime: "g:i A", // in jQuery UI Datepicker: "h:mm tt"
                    // long time (with seconds)
                    //    g - 12-hour format of an hour without leading zeros
                    //    i - Minutes with leading zeros
                    //    s - Seconds, with leading zeros
                    //    A - Uppercase Ante meridiem and Post meridiem (AM or PM)
                    LongTime: "g:i:s A", // in jQuery UI Datepicker: "h:mm:ss tt"
                    SortableDateTime: "Y-m-d\\TH:i:s",
                    UniversalSortableDateTime: "Y-m-d H:i:sO",
                    // month with year
                    //    Y - A full numeric representation of a year, 4 digits
                    //    F - A full textual representation of a month
                    YearMonth: "F, Y" // in jQuery UI Datepicker: "MMMM, yyyy"
                },
                reformatAfterEdit: false
            },
            baseLinkUrl: '',
            showAction: '',
            target: '',
            checkbox: {disabled: true},
            idName: 'id'
        }
    });
})(jQuery);
