<?php
defined('BASEPATH') || exit('No direct script access allowed');

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

/**
 * Description of CSV/XLS Impiort Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module CSVImport
 * 
 * @class Csv_import.php
 * 
 * @path application\libraries\Csv_import.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
Class Csv_import
{

    protected $CI;
    public $outStream;
    public $maxCols = 0;
    public $chunkRows = 50000;
    public $rowSize = 500000;
    public $colSize = 100;
    public $tmpFile = '';

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function setChunkRows($chunkRows = 5)
    {
        $this->chunkRows = $chunkRows;
    }

    public function setRowSize($rowSize = 500000)
    {
        $this->rowSize = $rowSize;
    }

    public function setColSize($colSize = 100)
    {
        $this->colSize = $colSize;
    }

    public function identify($input_file = '')
    {
        $pathinfo = pathinfo($input_file);
        $extension = '';
        switch (strtolower($pathinfo['extension'])) {
            case 'xlsx':
            case 'xlsm':
            case 'xltx':
            case 'xltm':
                $extension = 'xlsx';
                break;
            case 'xls':
            case 'xlt':
                $extension = 'xls';
                break;
            case 'ods':
            case 'ots':
                $extension = 'ods';
                break;
            case 'csv':
                $extension = 'csv';
                break;
        }
        return $extension;
    }

    public function getReader($input_file = '', $extension = '', $input_attr = array())
    {
        switch ($extension) {
            case 'xlsx':
                require_once $this->CI->config->item('third_party') . 'Excel/Spout/Autoloader/autoload.php';
                $reader = ReaderFactory::create(Type::XLSX); // for XLSX files
                $reader->open($input_file);
                break;
            case 'xls':
                require_once $this->CI->config->item('third_party') . 'Excel/XLSReader/XLSReader.php';
                $reader = new XLSReader($input_file);
                break;
            case 'ods':
                require_once $this->CI->config->item('third_party') . 'Excel/Spout/Autoloader/autoload.php';
                $reader = ReaderFactory::create(Type::ODS); // for ODS files
                $reader->open($input_file);
                break;
            case 'csv':
                require_once $this->CI->config->item('third_party') . 'Excel/Spout/Autoloader/autoload.php';
                $reader = ReaderFactory::create(Type::CSV); // for CSV files
                if (isset($input_attr['separator'])) {
                    switch ($input_attr['separator']) {
                        case "tab":
                            $reader->setFieldDelimiter("\t");
                            break;
                        case "semicolon":
                            $reader->setFieldDelimiter(";");
                            break;
                        case "space":
                            $reader->setFieldDelimiter(" ");
                            break;
                    }
                }
                if (isset($input_attr['delimiter'])) {
                    if ($input_attr['delimiter'] == "single") {
                        $reader->setFieldEnclosure("'");
                    }
                }
                $reader->open($input_file);
                break;
        }
        return $reader;
    }

    public function readExcelContent($input_file = '', $input_attr = array(), $page = 1)
    {
        $ret_arr = $data = array();
        try {
            if (!is_file($input_file)) {
                throw new Exception($this->CI->general->processMessageLabel('ACTION_FILE_NOT_FOUND_C46_C46_C33'));
            }
            //including third party library for reading
            $extension = $this->identify($input_file);
            $index = (intval($input_attr['sheet']) > 0) ? $input_attr['sheet'] : 0;
            $skip = (intval($input_attr['skip']) > 0) ? $input_attr['skip'] : 0;
            $startIndex = intval(($page - 1) * $this->chunkRows);
            $startIndex++;
            if ($page == 1 && $skip > 0) {
                $startIndex = $startIndex + $skip;
            }
            $stopIndex = ($startIndex + $this->chunkRows);
            if ($extension == "") {
                throw new Exception("File type not accepted.");
            }
            $r = $nextPage = 0;
            if ($extension == "xls") {
                $reader = $this->getReader($input_file, $extension, $input_attr);
                $reader->ChangeSheet($index);
                foreach ($reader as $key => $row) {
                    $r++;
                    if ($r > $this->rowSize) {
                        break;
                    }
                    if ($r < $startIndex) {
                        continue;
                    }
                    if ($r >= $stopIndex) {
                        $nextPage = $page + 1;
                        break;
                    }
                    $data[] = array_slice($row, 0, $this->colSize);
                    $this->maxCols = max($this->maxCols, count($row));
                }
            } elseif ($extension == "csv") {
                $reader = $this->getReader($input_file, $extension, $input_attr);
                foreach ($reader->getSheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $row) {
                        $r++;
                        if ($r > $this->rowSize) {
                            break;
                        }
                        if ($r < $startIndex) {
                            continue;
                        }
                        if ($r >= $stopIndex) {
                            $nextPage = $page + 1;
                            break;
                        }
                        $data[] = array_slice($row, 0, $this->colSize);
                        $this->maxCols = max($this->maxCols, count($row));
                    }
                    break;
                }
                $reader->close();
            } else {
                $reader = $this->getReader($input_file, $extension, $input_attr);
                foreach ($reader->getSheetIterator() as $sheet) {
                    $sheet_index = ($extension == "ods") ? $sheet->getName() : $sheet->getIndex();
                    if ($sheet_index == $index) {
                        foreach ($sheet->getRowIterator() as $row) {
                            $r++;
                            if ($r > $this->rowSize) {
                                break;
                            }
                            if ($r < $startIndex) {
                                continue;
                            }
                            if ($r >= $stopIndex) {
                                $nextPage = $page + 1;
                                break;
                            }
                            $data[] = array_slice($row, 0, $this->colSize);
                            $this->maxCols = max($this->maxCols, count($row));
                        }
                        break;
                    }
                }
                $reader->close();
            }

            if (!is_array($data) || count($data) == 0) {
                throw new Exception("No records found.");
            }
            $success = 1;
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        $ret_arr['success'] = $success;
        $ret_arr['message'] = $message;
        $ret_arr['data'] = $data;
        $ret_arr['next'] = $nextPage;
        return $ret_arr;
    }

    public function callCurlGet($url = '', $header_params = array(), $xml_to_arr = FALSE)
    {
        $vch = curl_init();
        curl_setopt($vch, CURLOPT_URL, $url);
        curl_setopt($vch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($vch, CURLOPT_SSL_VERIFYPEER, false);
        if (is_array($header_params) && count($header_params) > 0) {
            curl_setopt($vch, CURLOPT_HTTPHEADER, $header_params);
        }
        $f_out = curl_exec($vch);
        curl_close($vch);
        if ($xml_to_arr === TRUE) {
            $f_out = $this->xml2array($f_out);
        }
        return $f_out;
    }

    public function callCurlGetFile($url = '', $header_params = array(), $outStream = '')
    {
        $this->outStream = $outStream;
        $vch = curl_init();
        curl_setopt($vch, CURLOPT_URL, $url);
        curl_setopt($vch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($vch, CURLOPT_SSL_VERIFYPEER, false);
        if (is_array($header_params) && count($header_params) > 0) {
            curl_setopt($vch, CURLOPT_HTTPHEADER, $header_params);
        }
        curl_setopt($vch, CURLOPT_WRITEFUNCTION, array($this, 'writeData'));
        $f_out = curl_exec($vch);
        curl_close($vch);
        return $f_out;
    }

    public function prepareRows($f_out = array(), $type = 'cells', $row_cnt = 0)
    {
        $rows = $f_out['feed']['entry'];
        if ($type == 'list') {
            $records = $rec_header = array();
            foreach ($rows as $k => $v) {
                if (count($rec_header) == 0) {
                    $rec_header = $this->getColumns($v);
                    $records[] = $rec_header;
                }
                $record = array();
                foreach ($rec_header as $rv) {
                    $record[] = $v["gsx:" . $rv];
                }
                if ($row_cnt > 0 && count($records) >= $row_cnt - 1)
                    break;
                $records[] = $record;
            }
        } else {
            $rec_header = array();
            $visited_row = 0;
            foreach ($rows as $k => $v) {
                if ($v['gs:cell_attr']['row'] > $visited_row && $visited_row != 0)
                    break;
                $visited_row = $v['gs:cell_attr']['row'];
                $rec_header[] = $v['content']; //gs:cell
            }
            $column_count = count($rec_header);
            $records[] = $rec_header;
//            Don't remove below commented code
//            foreach ($rows as $k => $v) {
//                if ($k < $column_count)
//                    continue;
//                if ($k % $column_count == 0) {
//                    if ($row_cnt > 0 && count($records) >= $row_cnt - 1)
//                        break;
//                    if (count($record) > 0) {
//                        $records[] = $record;
//                    }
//                    $record = array();
//                }
//                $record[] = $v['content'];
//            }
//            $records[] = $record;
            $ck_diff = 1;
            for ($ck = 0; $ck < count($rows); $ck++) {
                if ($ck < $column_count)
                    continue;
                if (count($record) == $column_count) {
                    $records[] = $record;
                    $record = array();
                }
                if ($ck_diff < $rows[$ck]['gs:cell_attr']['col']) {
                    $kl = $rows[$ck]['gs:cell_attr']['col'] - $ck_diff;
                    for ($kd = 0; $kd < $kl; $kd++) {
                        $record[] = "";
                        $ck_diff++;
                    }
                }
                if (count($record) == $column_count) {
                    $records[] = $record;
                    $record = array();
                }
                if ($ck_diff == $rows[$ck]['gs:cell_attr']['col'])
                    $record[] = $rows[$ck]['content'];
                $ck_diff++;
                if ($ck_diff > $column_count)
                    $ck_diff = 1;
            }
            $records[] = $record;
        }
        return $records;
    }

    public function getColumns($arr = array(), $prefix = "")
    {
        $prefix = ($prefix != "") ? $prefix : "gsx:";
        $keys = array_keys($arr);
        $ret_keys = array();
        foreach ($keys as $ak) {
            if (strpos($ak, $prefix) === 0) {
                $ret_keys[] = substr($ak, strlen($prefix));
            }
        }
        return $ret_keys;
    }

    public function getArrayvalueForKeyPath($data_arr = array(), $key = '')
    {
        $retValue = "";
        $key_arr = explode(".", $key);
        $tmp_data = $data_arr;
        foreach ($key_arr as $kk => $kv) {
            $tmp_data = (isset($tmp_data[$kv])) ? $tmp_data[$kv] : NULL;
        }
        $retValue = $tmp_data;
        return $retValue;
    }

    public function prepareFiles($file_json = '', $json_dec = FALSE, $ext_allow = array())
    {
        $ret_arr = array();
        if (!is_array($ext_allow) || count($ext_allow) == 0) {
            $ext_allow = array(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.oasis.opendocument.spreadsheet',
                'text/csv',
                'application/vnd.ms-excel'
            );
        }
        if ((trim($file_json) == "" && $json_dec == TRUE) || (!is_array($file_json)))
            return $ret_arr;

        $file_arr = $file_json;
        if ($json_dec == TRUE)
            $file_arr = json_decode($file_arr, TRUE);

        $exist_arr = array();
        foreach ($file_arr as $fk => $fv) {
            if ($fv['is_dir'] == true)
                continue;
            if (!in_array($fv['mime_type'], $ext_allow))
                continue;
            if (in_array($fv['path'], $exist_arr))
                continue;
            $ret_arr[] = array(
                'fileName' => basename($fv['path']),
                'path' => $fv['path'],
                'rev' => $fv['rev']
            );
            $exist_arr[] = $fv['path'];
        }
        return $ret_arr;
    }

    public function writeData($ch, $data)
    {
        fwrite($this->outStream, $data);
        return strlen($data);
    }

    public function callMultiCurlGet($params = array(), $json_dec = FALSE)
    {
        $ch_arr = array();
        foreach ($params as $p) {
            $ch_o1 = curl_init($p['url']);
            curl_setopt($ch_o1, CURLOPT_RETURNTRANSFER, true);
            $headers = $p['headers'];
            if (is_array($headers) && count($headers) > 0) {
                curl_setopt($ch_o1, CURLOPT_HTTPHEADER, $headers);
            }
            $ch_arr[] = array('url' => $p['url'], 'obj' => $ch_o1);
        }
        // with curl_multi, you only have to wait for the longest-running request
        // build the individual requests as above, but do not execute them
        $mh = curl_multi_init();
        foreach ($ch_arr as $chobj) {
            curl_multi_add_handle($mh, $chobj['obj']);
        }
        // execute all queries simultaneously, and continue when all are complete
        $running = null;
        do {
            curl_multi_exec($mh, $running);
        } while ($running);

        // all of our requests are done, we can now access the results
        $response_arr = array();
        foreach ($ch_arr as $chobj) {
            $resp = curl_multi_getcontent($chobj['obj']);
            if ($json_dec == TRUE)
                $resp = json_decode($resp, TRUE);
            $response_arr = array_merge($response_arr, $resp);
        }
        return $response_arr;
    }

    public function prepareExcelData($input_file = '', $req_rows = -1, $req_index = -1)
    {
        $ret_arr = array();
        if (!is_file($input_file)) {
            return $ret_arr;
        }
        $extension = $this->identify($input_file);
        if ($extension == "") {
            return $ret_arr;
        }
        if ($extension == "xls") {
            $sheet_index = 0;
            do {
                $r = 0;
                $reader = $this->getReader($input_file, $extension);
                $reader->ChangeSheet($sheet_index);
                $sheet_info = $reader->Sheets();
                $sheet_index++;
                if (empty($sheet_info)) {
                    $nextSheet = false;
                    break;
                } else {
                    if ($req_index >= 0) {
                        if ($req_index == $sheet_index) {
                            $nextSheet = false;
                        } else {
                            continue;
                        }
                    } else {
                        $nextSheet = true;
                    }
                }
                $temp_arr = array();
                $temp_arr['title'] = $sheet_info['worksheetName'];
                $temp_arr['rows'] = array();
                foreach ($reader as $key => $row) {
                    if ($r > $this->rowSize) {
                        break;
                    }
                    if ($req_rows > 0 && $req_rows < $r) {
                        break;
                    }
                    $temp_arr['rows'][] = array_slice($row, 0, $this->colSize);
                    $r++;
                }
                $ret_arr[] = $temp_arr;
            } while ($nextSheet);
        } elseif ($extension == "csv") {
            $reader = $this->getReader($input_file, $extension);
            foreach ($reader->getSheetIterator() as $sheet) {
                $r = 0;
                $temp_arr = array();
                $temp_arr['title'] = $sheet->getName();
                $temp_arr['rows'] = array();
                foreach ($sheet->getRowIterator() as $row) {
                    if ($r > $this->rowSize) {
                        break;
                    }
                    if ($req_rows > 0 && $req_rows < $r) {
                        break;
                    }
                    $temp_arr['rows'][] = array_slice($row, 0, $this->colSize);
                    $r++;
                }
                $ret_arr[] = $temp_arr;
                break;
            }
            $reader->close();
        } else {
            $reader = $this->getReader($input_file, $extension);
            foreach ($reader->getSheetIterator() as $sheet) {
                $r = 0;
                $sheet_index = ($extension == "ods") ? $sheet->getName() : $sheet->getIndex();
                $temp_arr = array();
                $temp_arr['title'] = ($extension == "ods") ? $sheet->getIndex() : $sheet->getName();
                $temp_arr['rows'] = array();
                if ($req_index >= 0) {
                    if ($req_index != $sheet_index) {
                        continue;
                    }
                }
                foreach ($sheet->getRowIterator() as $row) {
                    if ($r > $this->rowSize) {
                        break;
                    }
                    if ($req_rows > 0 && $req_rows < $r) {
                        break;
                    }
                    $temp_arr['rows'][] = array_slice($row, 0, $this->colSize);
                    $r++;
                }
                $ret_arr[] = $temp_arr;
                if ($req_index >= 0) {
                    break;
                }
            }
            $reader->close();
        }
        return $ret_arr;
    }

    public function importDefaultValue($default = array(), $data = array(), $config = array(), $current = array())
    {
        $return_val = $default_val = trim($default[0]);
        switch ($default[1]) {
            case 'MySQL':
                if ($default_val == "NULL" || $default_val == "null") {
                    $return_val = '';
                } elseif ($default_val != "") {
                    $sql_query = "SELECT (" . $default_val . ") AS default_value";
                    $sql_data_obj = $this->CI->db->query($sql_query);
                    $db_default_value = is_object($sql_data_obj) ? $sql_data_obj->result_array() : array();
                    $return_val = $db_default_value[0]['default_value'];
                }
                break;
            case 'Server':
                $return_val = $_SERVER[$default_val];
                break;
            case 'Session':
                $return_val = $this->CI->session->userdata($default_val);
                break;
            case 'System':
                $return_val = $this->CI->config->item($default_val);
                break;
            case 'Function':
                $module = $current['module'];
                $folder = $current['folder'];
                $phpfunc = $config['lookup']['func'];
                if (substr($phpfunc, 0, 12) == 'controller::' && substr($phpfunc, 12) !== FALSE) {
                    $this->CI->load->module("{$folder}/{$module}");
                    if (is_object($this->CI->$module)) {
                        $phpfunc = substr($phpfunc, 12);
                        if (method_exists($this->CI->$module, $phpfunc)) {
                            $return_val = $this->CI->$module->$phpfunc('', "Import", '', $data);
                        }
                    }
                } elseif (substr($phpfunc, 0, 7) == 'model::' && substr($phpfunc, 7) !== FALSE) {
                    $model = $module . "_model";
                    $this->CI->load->model("{$folder}/{$model}");
                    $phpfunc = substr($phpfunc, 7);
                    if (is_object($this->CI->$model)) {
                        if (method_exists($this->CI->$model, $phpfunc)) {
                            $return_val = $this->CI->$model->$phpfunc('', "Import", '', $data);
                        }
                    }
                } elseif (method_exists($this->CI->general, $phpfunc)) {
                    $return_val = $this->CI->general->$phpfunc('', "Import", '', $data);
                }
                break;
        }
        return $return_val;
    }

    public function convertPriceToFloat($price = '')
    {
        $price = trim($price);
        if (preg_match("~^([0-9]+|(?:(?:[0-9]{1,3}([.,' ]))+[0-9]{3})+)(([.,])[0-9]{1,2})?$~", $price, $r)) {
            if (!empty($r['2'])) {
                $pre = preg_replace("~[" . $r['2'] . "]~", "", $r['1']);
            } else {
                $pre = $r['1'];
            }
            if (!empty($r['4'])) {
                $post = "." . preg_replace("~[" . $r['4'] . "]~", "", $r['3']);
            } else {
                $post = FALSE;
            }
            $form_price = $pre . $post;
            return ($form_price !== FALSE) ? $form_price : $price;
        }
        return $price;
    }

    public function convertToDate($val = '')
    {
        return date("Y-m-d", strtotime($val));
    }

    public function convertToDateTime($val = '')
    {
        return date("Y-m-d H:i:s", strtotime($val));
    }

    public function convertToTime($val = '')
    {
        return date("H:i:s", strtotime($val));
    }

    public function isDecimalType($type = '')
    {
        $decimal_arr = array('float', 'decimal', 'double');

        return (in_array($type, array($decimal_arr))) ? TRUE : FALSE;
    }

    public function isIntegerType($type = '')
    {
        $integer_arr = array('tinyint', 'smallint', 'mediumint', 'int', 'bignit', 'boolean');

        return (in_array($type, array($integer_arr))) ? TRUE : FALSE;
    }

    public function isDateTimeType($type = '')
    {
        $integer_arr = array('datetime', 'timestamp');

        return (in_array($type, array($integer_arr))) ? TRUE : FALSE;
    }

    public function isDateType($type = '')
    {
        $integer_arr = array('date');

        return (in_array($type, array($integer_arr))) ? TRUE : FALSE;
    }

    public function isTimeType($type = '')
    {
        $integer_arr = array('time');

        return (in_array($type, array($integer_arr))) ? TRUE : FALSE;
    }

    public function check_required($val = '', $rule = '')
    {
        return ($val == "") ? FALSE : TRUE;
    }

    public function check_email($val = '', $rule = '')
    {
        return filter_var($val, FILTER_VALIDATE_EMAIL) ? TRUE : FALSE;
    }

    public function check_url($val = '', $rule = '')
    {
        return filter_var($val, FILTER_VALIDATE_URL) ? TRUE : FALSE;
    }

    public function check_date($val = '', $rule = '')
    {
        $currentSec = strtotime($val);
        $actualSec = strtotime(date("Y-m-d", strtotime($val)));
        return ($currentSec == $actualSec && $currentSec !== FALSE) ? TRUE : FALSE;
    }

    public function check_datetime($val = '', $rule = '')
    {
        $currentSec = strtotime($val);
        $actualSec = strtotime(date("Y-m-d H:i:s", strtotime($sValue)));
        return ($currentSec == $actualSec && $currentSec !== FALSE) ? TRUE : FALSE;
    }

    public function check_time($val = '', $rule = '')
    {
        $currentSec = strtotime($val);
        $actualSec = strtotime(date("H:i:s", strtotime($sValue)));
        return ($currentSec == $actualSec && $currentSec !== FALSE) ? TRUE : FALSE;
    }

    public function check_digits($val = '', $rule = '')
    {
        return (preg_match('~^[0-9]+$~', $val) === 1) ? TRUE : FALSE;
    }

    public function check_number($val = '', $rule = '')
    {
        return is_numeric($val) ? TRUE : FALSE;
    }

    public function check_max($val = '', $rule = '')
    {
        return (is_numeric($val) && $val <= $rule) ? TRUE : FALSE;
    }

    public function check_maxlength($val = '', $rule = '')
    {
        return (isset($val{$rule}) === FALSE) ? TRUE : FALSE;
    }

    public function check_min($val = '', $rule = '')
    {
        return (is_numeric($val) && $val >= $rule) ? TRUE : FALSE;
    }

    public function check_minlength($val = '', $rule = '')
    {
        return (isset($val{ --$rule})) ? TRUE : FALSE;
    }

    public function check_range($val = '', $rule = '')
    {
        return $this->_check_rangelength(intval($val), $rule);
    }

    public function check_rangelength($val = '', $rule = '')
    {
        if (is_array($val)) {
            $count = count($val);
        } elseif (is_numeric($val)) {
            $count = $val;
        } else {
            $count = strlen($val);
        }
        return ($count >= $rule[0] && $count <= $rule[1]) ? TRUE : FALSE;
    }

    public function autoDetectMapping($col = '', $arr = array())
    {
        if (!is_array($arr) || count($arr) == 0) {
            return '';
        }
        //vFirstName
        if (array_key_exists($col, $arr)) {
            return $col;
        }
        //First Name
        if (in_array($col, $arr)) {
            return array_search($col, $arr);
        }
        //first name
        $con = strtolower($col);
        if (in_array($con, $arr)) {
            return array_search($con, $arr);
        }
        //FIRST NAME
        $con = strtoupper($col);
        if (in_array($con, $arr)) {
            return array_search($con, $arr);
        }
        //First name
        $con = ucfirst(strtolower($col));
        if (in_array($con, $arr)) {
            return array_search($con, $arr);
        }

        /* without spaces */
        //FirstName
        $cow = str_replace(" ", "", $col);
        $con = $cow;
        if (in_array($con, $arr)) {
            return array_search($con, $arr);
        }
        //firstname
        $con = strtolower($cow);
        if (in_array($con, $arr)) {
            return array_search($con, $arr);
        }
        //FIRSTNAME
        $con = strtolower($cow);
        if (in_array($con, $arr)) {
            return array_search($con, $arr);
        }
        //Firstname
        $con = ucfirst(strtolower($cow));
        if (in_array($con, $arr)) {
            return array_search($con, $arr);
        }

        $cop = explode(" ", $col);
        $ret = '';
        foreach ($arr as $key => $val) {
            $val_arr = explode(" ", $val);
            if ($val == $cop[0] || $val_arr[0] == $cop[0]) {
                $ret = $key;
                break;
            }
        }
        return $ret;
    }

    public function uploadMediaFiles($arr = array(), $media = '', $log = '', $chk_size = "Yes")
    {
        $dir = $arr['dir'];
        $cols = $arr['cols'];
        $data = $arr['data'];
        $count = $arr['count'];
        if (!is_array($cols) || count($cols) == 0) {
            return FALSE;
        }
        $this->sessionClose();
        $this->tmpFile = $arr['folder'];
        $this->_logUploadActivity($log, "total", $count);
        foreach ($cols as $key => $val) {
            $file_folder = trim($val['server'][0]);
            $file_server = trim($val['server'][1]);
            $file_size = intval($val['limits'][1]);
            $files = $data[$key];
            if (!is_array($files) || count($files) == 0) {
                $this->_logUploadActivity($log, "data_not_found", $key, "Data not found.", $val);
                continue;
            }
            if ($file_folder == "") {
                $this->_logUploadActivity($log, "folder_not_found", $key, "Folder not found.", $val);
                continue;
            }
            foreach ($files as $fk => $fv) {
                $src = $fv['src'];
                $dst = $fv['dst'];
                if ($dst == "") {
                    $this->_logUploadActivity($log, "extension_not_valid", 1, "Extension is not valid.", $val);
                    continue;
                }
                $ext = "No";
                if ($this->CI->general->isExternalURL($src)) {
                    if ($chk_size == "Yes") {
                        $act_size = $this->_fetchRemoteMediaSize($src);
                        if ($file_size > 0 && $act_size > $file_size) {
                            $this->_logUploadActivity($log, "size_not_valid", 1, "Size is not valid.", $val);
                            continue;
                        }
                    }
                    $ext = "Yes";
                    $file_src = $src;
                } else {
                    $temp_folder_path = $this->CI->config->item('admin_upload_temp_path');
                    $file_src = $this->_fetchLocalMediaPath($temp_folder_path . $dir . DS, $src, $file_folder);
                    if ($file_src == '' || !is_file($file_src)) {
                        $this->_logUploadActivity($log, "file_not_valid", 1, "File not found.", $val);
                        continue;
                    }
                    if ($chk_size == "Yes") {
                        $act_size = $this->_fetchLocalMediaSize($file_src);
                        if ($file_size > 0 && $act_size > $file_size) {
                            $this->_logUploadActivity($log, "size_not_valid", 1, "Size is not valid.", $val);
                            continue;
                        }
                    }
                }
                switch ($file_server) {
                    case 'custom':
                        $result = $this->_doCustomServerFiles($file_src, $dst, $file_folder, $ext);
                        break;
                    case 'amazon':
                        $result = $this->_doAWSServerFiles($file_src, $dst, $file_folder, $ext);
                        break;
                    case 'local':
                    default :
                        $result = $this->_doLocalServerFiles($file_src, $dst, $file_folder, $ext);
                        break;
                }
                if (!$result) {
                    $this->_logUploadActivity($log, "upload_failed", 1, "File upload failed.", $val);
                } else {
                    $this->_logUploadActivity($log, "upload_success", 1, "File upload succes.", $val);
                }
            }
        }
        $this->_logUploadActivity($log, "done");
    }

    private function _logUploadActivity($log = '', $type = '', $val = '', $msg = '', $cols = array())
    {
        $import_folder_path = $this->CI->config->item('import_files_path');
        $logfile = $import_folder_path . $log;
        $data = file_get_contents($logfile);
        $arr = json_decode($data, TRUE);
        $arr = is_array($arr) ? $arr : array();
        switch ($type) {
            case 'total':
                $arr['total'] = $val;
                $arr['done'] = 0;
                $arr['fail'] = 0;
                $arr['comp'] = 0;
                $arr['cols'] = array();
                break;
            case 'data_not_found':
            case 'folder_not_found':
                $tmp = array();
                $tmp['name'] = $cols['name'];
                $tmp['done'] = 0;
                $tmp['fail'] = 0;
                $tmp['message'] = $msg;
                $arr['cols'][$val] = $tmp;
                break;
            case 'extension_not_valid':
            case 'size_not_valid':
            case 'file_not_valid':
            case 'upload_failed':
                if (!is_array($arr['cols']) || !is_array($arr['cols'][$val])) {
                    $tmp = array();
                    $tmp['name'] = $cols['name'];
                    $tmp['done'] = 0;
                    $tmp['fail'] = 0;
                    $arr['cols'][$val] = $tmp;
                }
                $arr['cols'][$val]['fail'] ++;
                $arr['fail'] ++;
                break;
            case 'upload_success':
                if (!is_array($arr['cols']) || !is_array($arr['cols'][$val])) {
                    $tmp = array();
                    $tmp['name'] = $cols['name'];
                    $tmp['done'] = 0;
                    $tmp['fail'] = 0;
                    $arr['cols'][$val] = $tmp;
                }
                $arr['cols'][$val]['done'] ++;
                $arr['done'] ++;
                break;
            case 'done':
                $arr['comp'] = 1;
                break;
        }
        $str = json_encode($arr);
        $fp = fopen($logfile, 'w+');
        if ($fp) {
            fwrite($fp, $str);
            fclose($fp);
        }
    }

    private function _fetchLocalMediaPath($dir = '', $src = '', $folder = '')
    {
        $path = '';
        if (trim($src) == "") {
            return $path;
        }
        $avoid_arr = array(".", "..", ".svn", ".git", ".quarantine", ".tmb", "._.DS_Store", ".DS_Store");
        $list = scandir($dir);
        for ($i = 0; $i < count($list); $i++) {
            $item = $list[$i];
            if (in_array($item, $avoid_arr)) {
                continue;
            }
            if (is_file($dir . $src)) {
                $path = $dir . $src;
                break;
            }
            if (is_dir($dir . $item)) {
                $tmp = $dir . $item . DS;
                if (is_file($tmp . $src)) {
                    $path = $tmp . $src;
                    break;
                }
                $tmp = $dir . $item . DS . $folder . DS;
                if (is_dir($tmp)) {
                    if (is_file($tmp . $src)) {
                        $path = $tmp . $src;
                        break;
                    }
                }
                $tmp = $dir . $item . DS . $item . DS;
                if (is_dir($tmp)) {
                    if (is_file($tmp . $src)) {
                        $path = $tmp . $src;
                        break;
                    }
                }
                $tmp = $dir . $item . DS . $item . DS . $folder . DS;
                if (is_dir($tmp)) {
                    if (is_file($tmp . $src)) {
                        $path = $tmp . $src;
                        break;
                    }
                }
            }
        }
        return $path;
    }

    private function _fetchLocalMediaSize($src = '')
    {
        $size = filesize($src);
        if ($size > 0) {
            $result = ($size / 1024);
        } else {
            $result = 0;
        }
        return $result;
    }

    private function _fetchRemoteMediaSize($src = '')
    {
        $ch = curl_init($src);
        // Issue a HEAD request and follow any redirects.
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        curl_close($ch);
        if ($size > 0) {
            $result = ($size / 1024);
        } else {
            $result = 0;
        }
        return $result;
    }

    private function _fetchRemoteMediaData($src = '', $dst = '')
    {
        set_time_limit(0);
        $fp = fopen($dst, 'w+');
        $ch = curl_init(str_replace(" ", "%20", $src));
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch); // get curl response
        curl_close($ch);
        fclose($fp);
        if (in_array(intval(curl_errno($ch)), array(1, 2, 3, 5, 6, 7, 8))) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    private function _doLocalServerFiles($src = '', $dst = '', $folder = '', $ext = '')
    {
        $temp = $this->tmpFile;
        $folder = $this->CI->general->getImageNestedFolders($folder);
        $this->CI->general->createUploadFolderIfNotExists($folder);
        $tar = $this->CI->config->item('upload_path') . $folder . DS . $dst;
        if ($ext == "Yes") {
            $result = $this->_fetchRemoteMediaData($src, $tar);
        } else {
            if (copy($src, $tar)) {
                unlink($src);
                $result = TRUE;
            } else {
                $result = FALSE;
            }
        }
        return $result;
    }

    private function _doCustomServerFiles($src = '', $dst = '', $folder = '', $ext = '')
    {
        $temp = $this->tmpFile;
        if ($ext == "Yes") {
            $loc = $temp . $dst;
            $result = $this->_fetchRemoteMediaData($src, $loc);
            if (!$result) {
                return FALSE;
            }
        } else {
            $loc = $src;
        }
        $arr = $this->CI->general->getServerUploadPathURL($folder);
        $path = $arr['folder_path'];
        $return_string = $this->CI->general->uploadServerData($path, $loc, $dst);
        $return_arr = json_decode($return_string, TRUE);
        if ($return_arr['success']) {
            unlink($src);
            $result = TRUE;
        } else {
            $result = FALSE;
        }
        return $result;
    }

    private function _doAWSServerFiles($src = '', $dst = '', $folder = '', $ext = '')
    {
        $temp = $this->tmpFile;
        if ($ext == "Yes") {
            $loc = $temp . $dst;
            $result = $this->_fetchRemoteMediaData($src, $loc);
            if (!$result) {
                return FALSE;
            }
        } else {
            $loc = $src;
        }
        $arr = $this->CI->general->getAWSServerUploadPathURL($folder);
        $path = $arr['folder_path'];
        $response = $this->CI->general->uploadAWSData($loc, $folder, $dst);
        if ($response) {
            unlink($src);
            $result = TRUE;
        } else {
            $result = FALSE;
        }
        return $result;
    }

    public function sessionClose()
    {
        session_write_close();
    }

    public function getLogActivity($arr = array())
    {
        $ret = array();
        if (!is_array($arr['media'])) {
            return $ret;
        }
        $logger = $arr['media']['logger'];
        $import_files_path = $this->CI->config->item('import_files_path');
        if (!is_file($import_files_path . $logger)) {
            return $ret;
        }
        $arr = file_get_contents($import_files_path . $logger);
        $arr = json_decode($arr, TRUE);
        $total = intval($arr['total']);
        if ($total <= 0) {
            return $ret;
        }
        $done = intval($arr['done']);
        $fail = intval($arr['fail']);

        $percent = round(($done / $total) * 100);
        if ($arr['comp'] == 1) {
            if ($done < $total) {
                $status = "icon14 entypo-icon-warning";
            } else {
                $status = "icon15 minia-icon-checkmark-2";
            }
        } else {
            $status = "icon16 icomoon-icon-loading-5";
        }
//        $each = $arr['cols'];
//        $cols = array();
//        if (is_array($each) && count($each) > 0) {
//            foreach ($each as $key => $val) {
//                $tmp = array();
//                $title = $this->lang->line($val['name']);
//                $tmp['title'] = ($title) ? $title : $val['name'];
//                if ($val['done'] == 0 && $val['fail'] == 0) {
//                    $tmp['status'] = 0;
//                    $tmp['message'] = $val['message'];
//                } else {
//                    $tmp['status'] = 1;
//                    $tmp['done'] = $val['done'];
//                    $tmp['fail'] = $val['fail'];
//                }
//                $cols[] = $tmp;
//            }
//        }
        $ret['percent'] = $percent;
        $ret['status'] = $status;
        $ret['total'] = $total;
        $ret['done'] = $done;
        $ret['fail'] = $fail;
        $ret['cols'] = $cols;
        return $ret;
    }

    public function xml2array($contents, $get_attributes = 1, $priority = 'tag')
    {
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);
        if (!$xml_values)
            return; //Hmm...
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();
        $current = & $xml_array;
        $repeated_tag_index = array();
        if (is_array($xml_values) && count($xml_values) > 0) {
            foreach ($xml_values as $data) {
                unset($attributes, $value);
                extract($data);
                $result = array();
                $attributes_data = array();
                if (isset($value)) {
                    if ($priority == 'tag')
                        $result = $value;
                    else
                        $result['value'] = $value;
                }
                if (isset($attributes) && $get_attributes) {
                    foreach ($attributes as $attr => $val) {
                        if ($priority == 'tag')
                            $attributes_data[$attr] = $val;
                        else
                            $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                    }
                }
                if ($type == "open") {
                    $parent[$level - 1] = & $current;
                    if (!is_array($current) || (!in_array($tag, array_keys($current)))) {
                        $current[$tag] = $result;
                        if ($attributes_data)
                            $current[$tag . '_attr'] = $attributes_data;
                        $repeated_tag_index[$tag . '_' . $level] = 1;
                        $current = & $current[$tag];
                    }
                    else {
                        if (isset($current[$tag][0])) {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                            $repeated_tag_index[$tag . '_' . $level] ++;
                        } else {
                            $current[$tag] = array(
                                $current[$tag],
                                $result
                            );
                            $repeated_tag_index[$tag . '_' . $level] = 2;
                            if (isset($current[$tag . '_attr'])) {
                                $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                                unset($current[$tag . '_attr']);
                            }
                        }
                        $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                        $current = & $current[$tag][$last_item_index];
                    }
                } elseif ($type == "complete") {
                    if (!isset($current[$tag])) {
                        $current[$tag] = $result;
                        $repeated_tag_index[$tag . '_' . $level] = 1;
                        if ($priority == 'tag' && $attributes_data)
                            $current[$tag . '_attr'] = $attributes_data;
                    }
                    else {
                        if (isset($current[$tag][0]) && is_array($current[$tag])) {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                            if ($priority == 'tag' && $get_attributes && $attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                            }
                            $repeated_tag_index[$tag . '_' . $level] ++;
                        } else {
                            $current[$tag] = array(
                                $current[$tag],
                                $result
                            );
                            $repeated_tag_index[$tag . '_' . $level] = 1;
                            if ($priority == 'tag' && $get_attributes) {
                                if (isset($current[$tag . '_attr'])) {
                                    $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                                    unset($current[$tag . '_attr']);
                                }
                                if ($attributes_data) {
                                    $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                                }
                            }
                            $repeated_tag_index[$tag . '_' . $level] ++; //0 and 1 index is already taken
                        }
                    }
                } elseif ($type == 'close') {
                    $current = & $parent[$level - 1];
                }
            }
        }
        return ($xml_array);
    }
}

/* End of file Csv_import.php */
/* Location: ./application/libraries/Csv_import.php */