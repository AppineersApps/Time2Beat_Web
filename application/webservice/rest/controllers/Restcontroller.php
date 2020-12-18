<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Rest Controller
 *
 * @category webservice
 *            
 * @package rest
 * 
 * @subpackage controllers
 *  
 * @module Rest
 * 
 * @class Restcontroller.php
 * 
 * @path application\webservice\rest\controllers\Restcontroller.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class RestController extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * create_token method is used to create token for webservices security.
     */
    public function create_token()
    {
        $this->load->library('wsresponse');
        $this->load->library('wschecker');
        $this->wsresponse->setOptionsResponse();
        
        $this->load->model('rest_model');
        $remote_addr = $this->wschecker->getHTTPRealIPAddr();
        $user_agent = $this->wschecker->getHTTPUserAgent();
        $prepare_str = $remote_addr . "@@" . $user_agent . "@@" . time();
        $ws_token = hash("SHA256", $this->wschecker->encrypt($prepare_str));

        $inser_arr['vWSToken'] = $ws_token;
        $inser_arr['vIPAddress'] = $remote_addr;
        $inser_arr['vUserAgent'] = $user_agent;
        $inser_arr['dLastAccess'] = date("Y-m-d H:i:s");
        $res = $this->rest_model->insertToken($inser_arr);
        if ($res) {
            $settings_arr['success'] = 1;
            $settings_arr['message'] = "Token generated successfully..!";
            $data_arr['ws_token'] = $ws_token;
        } else {
            $settings_arr['success'] = 0;
            $settings_arr['message'] = "Token generation failed..!";
            $data_arr = array();
        }
        $responce_arr['settings'] = $settings_arr;
        $responce_arr['data'] = $data_arr;
        $this->wsresponse->sendWSResponse($responce_arr);
    }

    /**
     * inactive_token method is used to inactivate token for webservices security.
     */
    public function inactive_token()
    {
        $this->load->library('wsresponse');
        $this->load->library('wschecker');
        $this->wsresponse->setOptionsResponse();
        
        $this->load->model('rest_model');
        $remote_addr = $this->wschecker->getHTTPRealIPAddr();
        $user_agent = $this->wschecker->getHTTPUserAgent();
        $ws_token = trim($this->input->get_post("ws_token"));

        if (empty($ws_token)) {
            $settings_arr['success'] = 0;
            $settings_arr['message'] = "Please send token to inactivate.!";
        } else {
            $update_arr['eStatus'] = "Inactive";
            $extra_cond = "vWSToken = '" . $ws_token . "'";
            $res = $this->rest_model->updateToken($update_arr, $extra_cond);
            if ($res) {
                $settings_arr['success'] = 1;
                $settings_arr['message'] = "Token inactivated successfully..!";
            } else {
                $settings_arr['success'] = 0;
                $settings_arr['message'] = "Token inactivation failed..!";
            }
        }
        $responce_arr['settings'] = $settings_arr;
        $responce_arr['data'] = array();
        $this->wsresponse->sendWSResponse($responce_arr);
    }

    /**
     * execute_notify_schedule method is used to get push notifications full data.
     */
    public function execute_notify_schedule()
    {
        $this->load->library('wsresponse');
        $this->wsresponse->setOptionsResponse();

        $this->load->model('rest_model');
        $limit = $this->config->item('WS_PUSH_LIMIT');
        try {
            $data = array();

            $extra_cond = $this->db->protect("mpn.eStatus") . " = " . $this->db->escape("Pending");
            $data_arr = $this->rest_model->getPushNotify($extra_cond, "", "", "", $limit);

            if (!is_array($data_arr) || count($data_arr) == 0) {
                throw new Exception("There are no notification found to execute.");
            }

            foreach ($data_arr as $key => $val) {
                $push_arr[] = $val['iPushNotifyId'];
                $res = $this->rest_model->updatePushNotify(array("eStatus" => "Inprocess"), $val['iPushNotifyId']);
            }
        
            foreach ($data_arr as $key => $val) {
                $update_arr = array();
                $push_time = $val['dtPushTime'];
                if (!empty($push_time) && $push_time != "0000-00-00 00:00:00") {
                    if ($push_time > date("Y-m-d H:i:s")) {
                        continue;
                    }
                }
                $expire_time = $val['dtExpireTime'];
                if (!empty($expire_time) && $expire_time != "0000-00-00 00:00:00") {
                    if ($expire_time < date("Y-m-d H:i:s")) {
                        $update_arr['dtExeDateTime'] = date("Y-m-d H:i:s");
                        $update_arr['eStatus'] = 'Expired';
                        $res = $this->rest_model->updatePushNotify($update_arr, $val['iPushNotifyId']);
                        continue;
                    }
                }

                $notify_arr = array();
                $vars_arr = json_decode($val['tVarsJSON'], true);
                if (is_array($vars_arr) && count($vars_arr) > 0) {
                    foreach ($vars_arr as $vk => $vv) {
                        if ($vv['key'] != "" && $vv['send'] == "Yes") {
                            $notify_arr['others'][$vv['key']] = $vv['value'];
                        }
                    }
                }
                $notify_arr['mode'] = $this->config->item('PUSH_NOTIFY_SENDING_MODE');
                $notify_arr['message'] = $val['tMessage'];
                $notify_arr['title'] = $val['vTitle'];
                $notify_arr['badge'] = intval($val['vBadge']);
                $notify_arr['sound'] = $val['vSound'];
                $notify_arr['code'] = $val['eNotifyCode'];
                $notify_arr['id'] = $val['vUniqueId'];

                $success = $this->general->pushTestNotification($val['vDeviceId'], $notify_arr);

                $update_arr['tSendJSON'] = $this->general->getPushNotifyOutput("body");
                $update_arr['dtExeDateTime'] = date("Y-m-d H:i:s");
                if ($success) {
                    $update_arr['eStatus'] = 'Executed';
                } else {
                    $update_arr['eStatus'] = 'Failed';
                }
                $res = $this->rest_model->updatePushNotify($update_arr, $val['iPushNotifyId']);

                $send_arr = $notify_arr;
                $send_arr['device_id'] = $val['vDeviceId'];
                $data[] = $send_arr;
            }

            $settings_arr['success'] = 1;
            $settings_arr['count'] = count($data_arr);
            $settings_arr['message'] = "Push notification(s) send successfully";
        } catch (Exception $e) {
            $settings_arr['success'] = 0;
            $settings_arr['message'] = $e->getMessage();
        }
        $responce_arr['settings'] = $settings_arr;
        $responce_arr['data'] = $data;
        $this->wsresponse->sendWSResponse($responce_arr);
    }
    
    /**
     * process_access_log method is used to save access log into DB via CRON.
     */
    public function process_access_log(){
        $this->load->model('rest_model');
        $today = date("Y-m-d");
        $access_log_folder = $this->config->item('admin_access_log_path');
        $record_log_file = $access_log_folder . 'api_access_log.txt';
        if (!is_file($record_log_file)) {
            $fp = fopen($record_log_file, 'a+');
            chmod($record_log_file, 0777);
            $end_date = date("Y-m-d", strtotime("-15 day", strtotime($today)));
        } else {
            $db_end_date = end(explode('~~', file_get_contents($record_log_file)));
        }
        if ($end_date < $today) {
            while (strtotime($end_date) < strtotime($today)) {
                $end_date = date("Y-m-d", strtotime("+1 day", strtotime($end_date)));
                $date_arr[] = $end_date;
            }
            $log_data = $this->get_access_log($date_arr);
            if(!empty($log_data[$today])){
                $deletion = $this->rest_model->deleteAccessLog($today);
            }
            foreach ($date_arr as $date_log) {
                if (!empty($log_data[$date_log])) {
                    $insertion = $this->rest_model->insertAccessLog($log_data[$date_log]);
                }
            }
        }
        $log_date = date("Y-m-d", strtotime("-1 day", strtotime($today)));
        file_put_contents($sql_file, "$end_date~~$log_date");
        echo 1;
        $this->skip_template_view();
    }
    
    /**
     * get_access_log method is used to get access log from files.
     */
    public function get_access_log($date_arr = array())
    {
        $log_array = array();
        $access_log_folder = $this->config->item('admin_access_log_path');
        foreach ($date_arr as $value) {
            $log_floder = $access_log_folder . $value."/";
            $file = $log_floder . "log-ws.txt";
            if (is_file($file)) {
                $file = fopen($file, "r");
                $i = 0;
                while (!feof($file)) {
                    $line[$i] = array_diff_key(explode('~~', fgets($file)), [2 => '', 4 => '', 5 => '']);

                    //assigning keys to log array
                    $flipped = array_flip($line[$i]);
                    foreach ($flipped as $k => $v) {
                        $flipped[$k] = ($v === 0 ? 'vIPAddress' : ($v === 1 ? 'vRequestUri' : ($v === 3 ? 'dAccessDate' : ($v === 6 ? 'vPlatform' : ($v === 7 ? 'vBrowser' : ($v === 8 ? 'tInputParams' : $v))))));
                    }
                    $corrected[] = array_flip($flipped);
                    $i++;
                }
                $log_array[$value] = array_filter(array_map('array_filter', $corrected));
                unset($corrected);
            }
        }
        return $log_array;
    }
    
    /**
     * get_push_notification method is used to get push notifications full data.
     */
    public function get_push_notification()
    {
        $this->load->library('wsresponse');
        $this->load->library('wschecker');
        $this->wsresponse->setOptionsResponse();

        $this->load->model('rest_model');
        $get_arr = is_array($this->input->get(NULL, TRUE)) ? $this->input->get(NULL, TRUE) : array();
        $post_arr = is_array($this->input->post(NULL, TRUE)) ? $this->input->post(NULL, TRUE) : array();
        $post_params = array_merge($get_arr, $post_arr);

        try {
            if ($this->config->item('WS_RESPONSE_ENCRYPTION') == "Y") {
                $post_params = $this->wschecker->decrypt_params($post_params);
            }
            $verify_res = $this->wschecker->verify_webservice($post_params);
            if ($verify_res['success'] != "1") {
                $this->wschecker->show_error_code($verify_res);
            }

            $unique_id = $post_params["unique_id"];
            $data = $temp = array();
            if (empty($unique_id)) {
                throw new Exception("Please send unique id for this notification");
            }
            $extra_cond = $this->db->protect("mpn.vUniqueId") . " = " . $this->db->escape($unique_id);
            $data_arr = $this->rest_model->getPushNotify($extra_cond);

            if (!is_array($data_arr) || count($data_arr) == 0) {
                throw new Exception("Data not found for this unique id");
            }
            $variables = json_decode($data_arr[0]['tVarsJSON'], true);
            if (is_array($variables) && count($variables) > 0) {
                foreach ($variables as $vk => $vv) {
                    if ($vv['key'] != "") {
                        $temp[$vv['key']] = $vv['value'];
                    }
                }
            }
            $temp['code'] = $data_arr[0]['eNotifyCode'];
            $temp['title'] = $data_arr[0]['vTitle'];
            $temp['body'] = $data_arr[0]['tMessage'];

            $data[0] = $temp;
            $settings_arr['success'] = 1;
            $settings_arr['message'] = "Push notification data found";
        } catch (Exception $e) {
            $settings_arr['success'] = 0;
            $settings_arr['message'] = $e->getMessage();
        }
        $responce_arr['settings'] = $settings_arr;
        $responce_arr['data'] = $data;
        $this->wsresponse->sendWSResponse($responce_arr);
    }

    /**
     * image_resize method is used to resize image for different sizes.
     */
    public function image_resize()
    {
        $url = $this->input->get('pic');
        $width = $this->input->get('width');
        $height = $this->input->get('height');
        $bgcolor = $this->input->get('color');
        $type = $this->input->get('type');
        $loc = $this->input->get('loc');
        if (empty($type)) {
            $type = 'fit'; // fit, fill
        }
        if (empty($loc)) {
            $loc = 'Center'; // NorthWest, North, NorthEast, West, Center, East, SouthWest, South, SouthEast
        }
        $strict = FALSE;
        if (substr($width, -1) == "*" && substr($height, -1) == "*") {
            $width = substr($width, 0, -1);
            $height = substr($height, 0, -1);
            $strict = TRUE;
        }

//        $bgcolor = (trim($bgcolor) == "") ? "FFFFFF" : $bgcolor;
//        $props = array(
//            'picture' => $url,
//            'resize_width' => $width,
//            'resize_height' => $height,
//            'bg_color' => $bgcolor
//        );
//
//        $this->load->library('Image_resize', $props);
//        $this->skip_template_view();

        $dest_folder = APPPATH . 'cache' . DS . 'temp' . DS;
        $this->general->createFolder($dest_folder);

        $pic = trim($url);
        $pic = base64_decode($pic);
        $pic = str_replace(" ", "%20", $pic);
        $url = $pic;
        $url = str_replace(" ", "%20", $url);
        $props = array(
            'picture' => $url,
            'resize_width' => $width,
            'resize_height' => $height,
            'bg_color' => $bgcolor
        );
        $md5_url = md5($url . serialize($props));
        $tmp_path = $tmp_file = $dest_folder . $md5_url;

        if (strpos($url, $this->config->item('site_url')) === FALSE && strpos($url, 's3.amazonaws') === FALSE) {
            $this->output->set_status_header(400);
            exit;
        }
        
        if (!is_file($tmp_path)) {
            $image_data = file_get_contents($url);
            if ($image_data == FALSE) {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($curl, CURLOPT_TIMEOUT, 600);
                curl_setopt($curl, CURLOPT_COOKIEJAR, "cookie.txt");
                curl_setopt($curl, CURLOPT_COOKIEFILE, "cookie.txt");
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
                //curl_setopt($curl, CURLOPT_HEADER, TRUE);
                $image_data = curl_exec($curl);

                if ($image_data == FALSE) {
                	
                    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    if ($httpCode != 200) {
                        $this->output->set_status_header($httpCode);
                        exit;
                    }
                }
                curl_close($curl);
//                $headers = parse_response_header($http_response_header);
//                if ($headers['reponse_code'] != 200) {
//                    $this->output->set_status_header($headers['reponse_code']);
//                    exit;
//                }
            }
            $handle = fopen($tmp_path, 'w+');
            fwrite($handle, $image_data);
            fclose($handle);

            $img_info = getimagesize($tmp_path);
            $img_ext = end(explode("/", $img_info['mime']));
            if ($img_ext == 'jpeg' || $img_ext == "pjpeg") {
                $img_ext = 'jpg';
            }
            if ($strict == TRUE && $img_info[0] < $width && $img_info[1] < $height) {
                $tmp_file = $tmp_path;
            } else {

                $this->load->library('image_lib');

                $image_process_tool = $this->config->item('imageprocesstool');
                $config['image_library'] = $image_process_tool;
                if ($image_process_tool == "imagemagick") {
                    $config['library_path'] = $this->config->item('imagemagickinstalldir');
                }
//            if ($img_ext == "jpg") {
//                $png_convert = $this->image_lib->convet_jpg_png($tmp_path, $tmp_path . ".png", $config['library_path']);
//                if ($png_convert) {
//                    unlink($tmp_path);
//                    rename($tmp_path . ".png", $tmp_path);
//                }
//            }

                if ($type == 'fill') {
                    $img_info = getimagesize($tmp_path);
                    $org_width = $img_info[0];
                    $org_height = $img_info[1];

                    $width_ratio = $width / $org_width;
                    $height_ratio = $height / $org_height;
                    if ($width_ratio > $height_ratio) {
                        $resize_width = $org_width * $width_ratio;
                        $resize_height = $org_height * $width_ratio;
                    } else {
                        $resize_width = $org_width * $height_ratio;
                        $resize_height = $org_height * $height_ratio;
                    }

                    $crop_width = $width;
                    $crop_height = $height;

                    $width = $resize_width;
                    $height = $resize_height;
                }

                $config['source_image'] = $tmp_path;
                $config['width'] = $width;
                $config['height'] = $height;
                $config['gravity'] = $loc; //center/West/East
                $config['bgcolor'] = (trim($bgcolor) != "") ? trim($bgcolor) : $this->config->item('imageresizebgcolor');
                $this->image_lib->initialize($config);
                $this->image_lib->resize();

                if ($type == 'fill') {
                    $config['source_image'] = $tmp_path;
                    $config['width'] = $crop_width;
                    $config['height'] = $crop_height;
                    $config['gravity'] = 'center';
                    $config['maintain_ratio'] = FALSE;

                    $this->image_lib->initialize($config);
                    $this->image_lib->crop();
                }
            }
        }

        $this->image_display($tmp_file);
    }

    protected function image_display($image_path = '')
    {
        //ob_end_clean();
        if (ob_get_length() > 0) {
            ob_end_clean();
        }
        ob_start();
        $image_path = str_replace(" ", "%20", $image_path);
        $img_info = getimagesize($image_path);
        if ($img_info[2] == 1) {
            header('Content-Type: image/gif');
        } elseif ($img_info[2] == 2) {
            header('Content-Type: image/jpg');
        } elseif ($img_info[2] == 3) {
            header('Content-Type: image/png');
        } else {
            header('Content-Type: application/octet-stream');
        }
        $timestamp = filemtime($image_path);
        $gmt_mtime = gmdate('r', $timestamp);
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
            if ($_SERVER['HTTP_IF_MODIFIED_SINCE'] == $gmt_mtime || str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == md5($timestamp . $image_path)) {
                header('HTTP/1.1 304 Not Modified');
                exit;
            }
        }
        header_remove('Pragma');
        header("Access-Control-Allow-Origin: *");
        header('ETag: "' . md5($timestamp . $image_path) . '"');
        header('Last-Modified: ' . $gmt_mtime);
        header('Cache-Control: max-age=2592000, public');
        header("Content-Length: " . filesize($image_path));
        echo readfile($image_path);
        exit;
    }
}
