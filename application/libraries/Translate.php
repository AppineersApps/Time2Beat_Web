<?php

use Google\Cloud\Translate\TranslateClient;

/**
 * This class is useful for the language translation using google / azure  API
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module Translate
 * 
 * @class Translate.php
 * 
 * @path application\third_party\translate\Translate.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @date 01.01.2019
 */
Class Translate
{

    protected $CI;
    public $options;

    public function __construct($options = array())
    {
        $this->CI = & get_instance();
        $this->options = $options;
    }

    public function setOptions($options = array())
    {
        $this->options = $options;
    }

    public function tranlsate($src, $dst, $txt, $type = 'plain')
    {
        $result = array();
        try {
            if (!is_array($txt)) {
                $labels = array($txt);
            } else {
                $labels = $txt;
            }

            $srclist = $dstlist = array();
            $formated = $this->CI->general->formatLangugeLabel($labels, $type);
            foreach ($formated as $key => $val) {
                if ($val['status'] == 1) {
                    $srclist[] = $val['string'];
                }
                $result[$key] = $val['string'];
            }
            if (is_array($srclist) && count($srclist) > 0) {
                if (empty($dstlist) && !empty($this->options['google']['key'])) {
                    $response = $this->translateByGoogle($src, $dst, $srclist, $type);
                    if ($response['success']) {
                        $dstlist = $response['dstlist'];
                    }
                }

                if (empty($dstlist) && !empty($this->options['azure']['key'])) {
                    $response = $this->translateByAzure($src, $dst, $srclist, $type);
                    if ($response['success']) {
                        $dstlist = $response['dstlist'];
                    }
                }

                foreach ($srclist as $key => $val) {
                    $rind = array_search($val, $result);
                    $dstval = $this->CI->general->processLangugeLabel($dstlist[$key], $type);
                    $result[$rind] = isset($dstlist[$key]) ? $dstval : '';
                }
            }
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        if (!is_array($txt)) {
            $text = $result[0];
        } else {
            $text = $result;
        }

        return $text;
    }

    public function translateByGoogle($src, $dst, $labels = array(), $type = 'plain')
    {
        $result = array();
        try {
            if (!is_array($labels) || count($labels) <= 0) {
                throw new Exception("Input labels should be array.");
            }
            $toLanguage = strtolower($dst);
            $fromLanguage = strtolower($src);

            require_once($this->CI->config->item('third_party') . 'translate/google/vendor/autoload.php');

            $tr = new TranslateClient([
                'key' => $this->options['google']['key']
            ]);

            $translated = FALSE;
            $last_req_exception = '';
            $labels = array_values($labels);

            foreach ($labels as $key => $val) {

                try {
                    $options = array(
                        'source' => $src,
                        'target' => $dst
                    );
                    if ($type == "html") {
                        $options['format'] = $type;
                    }

                    $txt = $tr->translate($val, $options);
                    if (empty($txt['text'])) {
                        $last_req_exception = "There is no translation available";
                        $result[$key] = '';
                        continue;
                    } else {
                        $result[$key] = $txt['text'];
                    }
                } catch (Exception $e) {
                    $last_req_exception = $e->getMessage();
                    $req_exception_data = json_decode($last_req_exception, TRUE);
                    if (in_array($req_exception_data['error']['code'], array(401, 403, 404, 409))) {
                        $last_req_exception = $req_exception_data['error']['message'];
                    }
                    continue;
                }

                $translated = TRUE;

                sleep(1);
            }

            if (!$translated) {
                throw new Exception($last_req_exception);
            }

            $success = 1;
            $message = "Translation done.";

            $result = array_combine(array_keys($labels), array_values($result));
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        return array(
            'success' => $success,
            'message' => $message,
            'srclist' => $labels,
            'dstlist' => $result
        );
    }

    public function translateByAzure($src, $dst, $labels = array(), $type = 'plain')
    {
        $result = array();
        try {
            if (!is_array($labels) || count($labels) <= 0) {
                throw new Exception("Input labels should be array.");
            }
            $toLanguage = strtolower($dst);
            $fromLanguage = strtolower($src);

            $translated = FALSE;
            $last_req_exception = '';
            $labels = array_values($labels);

            foreach ($labels as $key => $val) {

                try {
                    $header_params = array(
                        'Ocp-Apim-Subscription-Key:' . $this->options['azure']['key'],
                        'Content-Type:application/json'
                    );
                    $get_params = array(
                        'api-version' => $this->options['azure']['version'],
                        'from' => $src,
                        'to' => $dst
                    );
                    if ($type == "html") {
                        $get_params['textType'] = $type;
                    }
                    $url = $this->options['azure']['url'];
                    if (strstr($url, "?") === false) {
                        $url .= '?';
                    }
                    $url .= http_build_query($get_params);

                    $post_params = array(
                        array("Text" => $val)
                    );

                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_POST, TRUE);
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_params));
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
                    curl_setopt($curl, CURLOPT_TIMEOUT, 600);
                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($curl, CURLOPT_VERBOSE, true);
                    if (is_array($header_params) && count($header_params) > 0) {
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $header_params);
                    }
                    $contents = curl_exec($curl);
                    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    curl_close($curl);

                    $result = json_decode($contents, true);
                    if (empty($result[0]['translations'][0]['text'])) {
                        $last_req_exception = "There is no translation available";
                        $result[$key] = '';
                        continue;
                    } else {
                        $result[$key] = $result[0]['translations'][0]['text'];
                    }
                } catch (Exception $e) {
                    $last_req_exception = $e->getMessage();
                    continue;
                }

                if (in_array($http_status, array(401, 403, 408, 429, 500, 503))) {
                    throw new Exception("Status: " . $http_status . ". Failure in text translation");
                }

                $translated = TRUE;

                sleep(1);
            }

            if (!$translated) {
                throw new Exception($last_req_exception);
            }

            $success = 1;
            $message = "Translation done.";

            $result = array_combine(array_keys($labels), array_values($result));
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        return array(
            'success' => $success,
            'message' => $message,
            'srclist' => $labels,
            'dstlist' => $result
        );
    }
}
