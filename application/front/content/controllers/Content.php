<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Content Controller
 *
 * @category front
 *            
 * @package content
 * 
 * @subpackage controllers
 * 
 * @module Content
 * 
 * @class Content.php
 * 
 * @path application\front\content\controllers\Content.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Content extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * index method is used to initialize index function.
     */
    public function index()
    {
        
    }

    /**
     * staticpage method is used to display static pages.
     */
    public function staticpage($page_code = '', $arg_lang = '')
    {
        $this->load->model('tools/staticpages');
        $fields = array("vPageTitle", "vPageCode", "vContent", "tMetaTitle", "tMetaKeyword", "tMetaDesc");
        $render_arr = array();
        $req_lang = $this->input->get('lang', TRUE);

        if (!is_null($req_lang) && !empty($req_lang)) {
            $lang = strtolower($req_lang);
        } elseif (!is_null($arg_lang) && !empty($arg_lang)) {
            $lang = strtolower($arg_lang);
        } else {
            $lang = "en";
            if ($this->config->item('MULTI_LINGUAL_PROJECT') == "Yes") {
                $sess_lang = $this->session->userdata('sess_lang_id');
                if (!is_null($sess_lang) && !empty($sess_lang)) {
                    $lang = strtolower($sess_lang);
                }
            }
        }
        if ($lang == "en") {
            $page_details = $this->staticpages->getStaticPageData($page_code, $fields);
        } else {
            $lang_fields = $this->staticpages->getLangTableFields();
            if (is_array($lang_fields) && count($lang_fields) > 0) {
                $lang_arr = array();
                foreach ($fields as $key => $val) {
                    if (in_array($val, $lang_fields)) {
                        $lang_arr[] = "mps_lang." . $val;
                    } else {
                        $lang_arr[] = "mps." . $val;
                    }
                }
                $page_details = $this->staticpages->getStaticPageLangData($lang, $page_code, $lang_arr);
            }
            if (!is_array($page_details) || count($page_details) == 0) {
                $page_details = $this->staticpages->getStaticPageData($page_code, $fields);
            }
        }
        $render_arr = array(
            "display_lang" => $lang,
            "page_code" => $page_code,
            "page_title" => $page_details[0]["vPageTitle"],
            "page_content" => $page_details[0]["vContent"],
            "meta_info" => array(
                "title" => $page_details[0]["tMetaTitle"],
                "description" => $page_details[0]["tMetaDesc"],
                "keywords" => $page_details[0]["tMetaKeyword"]
            )
        );
        $this->smarty->assign($render_arr);
        if ($this->config->item('static_page_template') != '') {
            $this->set_template($this->config->item('static_page_template'));
        }
        if ($this->config->item('static_page_view') != '') {
            $this->loadView($this->config->item('static_page_view'));
        }
    }

    /**
     * error method is used to display database connection errors.
     */
    public function error()
    {
        $file_name = "error_template";
        $this->set_template($file_name);
    }

    /**
     * captcha method is used to refresh captcha code.
     */
    public function captcha()
    {
        $this->load->library('captcha');
        $this->captcha->show('session', TRUE);
        $this->skip_template_view();
    }

    public function do_payment()
    {
        $this->load->library("payment");
        $this->load->model('cit_api_model');
        $order_id = $this->session->userdata('order_id');
        $redirect_url = $this->session->userdata('redirect_url');
        $api_params = array(
            "order_id" => $order_id
        );
        $api_data = $this->cit_api_model->callAPI("get_order_details", $api_params);

        if ($api_data['settings']['success'] == 1) {

            $result_arr = $api_data['data'][0];
            $params = array(
                'amount' => $result_arr['total_amount'],
                'description' => 'Order Payment',
                'returnUrl' => $this->config->item('site_url') . "payment-response.html?type=success",
                'cancelUrl' => $this->config->item('site_url') . "payment-response.html?type=cancel",
                'notifyUrl' => $this->config->item('site_url') . "payment-notify.html?order_id=" . base64_encode($order_id),
                'transactionId' => $result_arr['order_code'],
                'issuer' => 'ideal/RABONL2U',
                'iOrderId' => $result_arr['payment_transaction_id'],
                'currency' => 'USD'
            );
            $method = "PAYPAL_EXPRESS";
            $result = $this->payment->do_offsite_payment($method, $params);
            if (!$result || !$result['success']) {
                if ($result['message']) {
                    $this->session->set_flashdata('failure', $result['message']);
                } else {
                    $this->session->set_flashdata('failure', "Payment process has been failed.");
                }
            }
        } else {
            $this->session->set_flashdata('failure', $api_data['message']);
        }
        redirect($this->config->item("site_url") . 'profile-view.html');
    }

    public function payment_response()
    {
        $this->load->library("payment");
        $this->load->model('cit_api_model');
        $gateway_Obj = $this->payment->set_gateway("PAYPAL_EXPRESS");
        $token = urlencode($_GET['token']);

        $order_id = $this->session->userdata('order_id');
        $type = $this->input->get_post('type');
        switch ($type) {
            case "success":
                $api_params = array(
                    "order_id" => $order_id,
                    "order_status" => "PaymentDone"
                );
                break;

            case "cancel":
                $api_params = array(
                    "order_id" => $order_id,
                    "order_status" => "PaymentFailed"
                );
                break;

            default:
                $api_params = array(
                    "order_id" => $order_id,
                    "order_status" => "PaymentUnknown"
                );
                break;
        }
        $api_arr = $this->cit_api_model->callAPI("update_order_status", $api_params);

        if ($type == 'success') {
            $this->session->set_flashdata('success', "Order placed successfully.");
        } elseif ($type == 'cancel') {
            $this->session->set_flashdata('failure', "Payment process failed. Please contact administrator");
        } else {
            $this->session->set_flashdata('failure', "Something wrong with payment process. Please contact administrator");
        }
        if($redirect_url != ""){
            redirect($redirect_url);
        } elseif ($this->session->userdata('user_id') != '') {
            redirect($this->config->item("site_url") . 'dashboard.html');
        } else {
            redirect($this->config->item("site_url"));
        }
    }

    public function payment_notify()
    {
        echo "Coming Soon";
        $paypalpost = serialize($_REQUEST);
        $path = $this->config->item('upload_path') . "paypalnotify.txt";
        $fp = @fopen($path, "a+");
        @fwrite($fp, $paypalpost);
        @fclose($fp);
        $this->skip_template_view();
    }

    public function clear_cache()
    {
        $cache_path = $this->config->item('cache_path');
        $cache_folders = scandir($cache_path);
        for ($i = 0; $i < count($cache_folders); $i++) {
            if (in_array($cache_folders[$i], array("js", "styles", "smarty", "queries", "debug", "temp"))) {
                exec("rm -rf " . $cache_path . $cache_folders[$i]);
            }
        }
        echo "Cache cleared successfully.";
        $this->skip_template_view();
    }
}
