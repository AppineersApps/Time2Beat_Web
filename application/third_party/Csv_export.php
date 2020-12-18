<?php
defined('BASEPATH') || exit('No direct script access allowed');

/* * *
 * Simple class to properly output CSV data to clients. PHP 5 has a built
 * in method to do the same for writing to files (fputcsv()), but many times
 * going right to the client is beneficial.
 *
 * @author Jon Gales
 */

class CSV_Writer
{

    public $data = array();
    public $name;
    public $deliminator;
    protected $CI;

    /**
     * Loads data and optionally a deliminator. Data is assumed to be an array
     * of associative arrays.
     *
     * @param array $data
     * @param string $deliminator
     */
    function __construct($data, $deliminator = ",")
    {
        if (!is_array($data)) {
            throw new Exception('CSV_Writer only accepts data as arrays');
        }
        $this->CI = & get_instance();
        $this->data = $data;
        $this->deliminator = $deliminator;
    }

    private function wrap_with_quotes($data)
    {
        $data = preg_replace('/"(.+)"/', '""$1""', $data);
        return sprintf('"%s"', $data);
    }

    /**
     * Echos the escaped CSV file with chosen delimeter
     *
     * @return void
     */
    public function output()
    {
        //$output = chr(239) . chr(187) . chr(191);
        $output = '';
        foreach ($this->data as $row) {
            $quoted_data = array_map(array('CSV_Writer', 'wrap_with_quotes'), $row);
            $output .= sprintf("%s\n", implode($this->deliminator, $quoted_data));
        }
//        echo $output;
        $name = $this->name;
        $name = str_replace(" ", "-", $name);
        $name = preg_replace('/[^A-Za-z0-9@.-_]/', '', $name);
        $name = $name . '-' . time() . '.csv';
        $temp = $this->CI->config->item('admin_upload_temp_path');
        if(!is_dir($temp)){
            mkdir($temp, 0777);
            chmod($temp, 0777);
        }
        $path = $temp . $name;
        $fp = fopen($path, 'w+');
        fwrite($fp, $output);
        fclose($fp);
        $this->download($path);
        unlink($path);
    }

    public function download($path = '')
    {
        $mimetype = get_mime_by_extension($path);
        if (ob_get_length() > 0) {
            ob_end_clean();
        }
        ob_start();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Cache-Control: private", FALSE);
        header("Content-Transfer-Encoding: binary");
        //if(!($this->CI->agent->browser() == "Safari" || $this->CI->agent->is_mobile())){
            header('Content-Disposition: attachment; filename=' . $this->name . '.csv');
            header("Content-Length: " . filesize($path));
        //}
        if ($mimetype) {
            header("Content-Type: " . $mimetype);
        }
        flush();
        readfile($path);
    }

    /**
     * Sets proper Content-Type header and attachment for the CSV outpu
     *
     * @param string $name
     * @return void
     */
    public function headers($name)
    {
        $this->name = $name;
//        header('Content-Type: application/csv; charset=utf-8');
//        header("Content-disposition: attachment; filename={$name}.csv");
    }
}
