<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Admin Language Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module Payment
 * 
 * @class Payment.php
 * 
 * @path application\libraries\Payment.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
require_once(APPPATH . 'third_party/omnipay/vendor/autoload.php');

use Omnipay\Omnipay;

class Payment extends Omnipay
{

    var $payment_methods = array();
    var $gateway;

    public function __construct($rules = array())
    {
        $this->CI = & get_instance();
        $this->CI->config->load('payment');
        $this->payment_methods = array("PAYPAL_PRO" => "PayPal_Pro", "STRIPE" => "Stripe", "AUTHORIZE_NET" => "AuthorizeNet_AIM", "PAYFLOW_PRO" => "Payflow_Pro", "PAYPAL_EXPRESS" => "PayPal_Express", "AUTHORIZENET_SIM" => 'AuthorizeNet_SIM');
    }

    /**
     * set_gateway method is used to initialize required payment method and returns object to access its methods like authorize, capture etc..
     * @param string $payment_method payment_method is used to specify the payment method to be set
     * @param array $this->payment_methods this->payment_methods is inbuilt array of supported methods
     * @return object $this->gateway returns object to access its methods
     */
    public function set_gateway($payment_method)
    {
        if (!array_key_exists($payment_method, $this->payment_methods)) {

            return false;
        }

        $org_payment_method = $this->payment_methods[$payment_method];

        $this->gateway = Omnipay::create($org_payment_method);

        $payment_arr = $this->get_method_params($payment_method);

        $this->gateway->initialize($payment_arr);

        return $this->gateway;
    }

    /**
     * do_onsite_payment method is used to initialize required payment method and returns object to access its methods like authorize, capture etc..
     * @param string $payment_method payment_method is used to specify the payment method to be set
     * @param array $user_params user_params contains all the required parameters like amount, credit card details, product code, Order ID,etc..
     * @return array $return_response returns array containing success, failure and other response fetched after making a payment call
     */
    public function do_onsite_payment($payment_method, $user_params)
    {

        $return = array();

        try {

            if (!array_key_exists($payment_method, $this->payment_methods)) {

                throw new Exception("Method is not supported");
            }

            $org_payment_method = $this->payment_methods[$payment_method];

            $this->gateway = Omnipay::create($org_payment_method);

            $payment_arr = $this->get_method_params($payment_method);

            $this->gateway->initialize($payment_arr);

            $response = $this->gateway->purchase($user_params)->send();

            if ($response->isSuccessful()) {

                $return['success'] = $response->isSuccessful();

                $return['response'] = $response->getData();
            } else {

                throw new Exception($response->getMessage());
            }

            $return['message'] = $response->getMessage();
        } catch (Exception $e) {

            $return['success'] = 0;

            $return['message'] = $e->getMessage();
        }

        if ($response->isRedirect()) {

            $response->redirect();
        } else {

            return $return;
        }
    }

    /**
     * do_offsite_payment method is used to initialize required payment method and redirects to payment page of the gateway.
     * @param string $payment_method payment_method is used to specify the payment method to be set
     * @param array $user_params user_params contains all the required parameters like amount, credit card details, product code, Order ID,etc..
     * @return link the function redirects to the payment page of the gateway
     */
    public function do_offsite_payment($payment_method, $user_params)
    {
        $return = array();

        try {

            if (!array_key_exists($payment_method, $this->payment_methods)) {

                throw new Exception("Method is not supported");
            }

            $org_payment_method = $this->payment_methods[$payment_method];

            $this->gateway = Omnipay::create($org_payment_method);

            $payment_arr = $this->get_method_params($payment_method);

            $this->gateway->initialize($payment_arr);

            $response = $this->gateway->purchase($user_params)->send();

            if ($response->isSuccessful()) {

                $return['success'] = $response->isSuccessful();

                $return['response'] = $response->getData();
            } else {

                throw new Exception($response->getMessage());
            }

            $return['message'] = $response->getMessage();
        } catch (Exception $e) {

            $return['success'] = 0;

            $return['message'] = $e->getMessage();
        }

        if ($response->isRedirect()) {

            $response->redirect();
        } else {

            return $return;
        }
    }

    /**
     * get_method_params method is used to fetch initializing data and credentials of the gateway. The details are fetched from config
     * @param string $payment_method payment_method is used to specify the payment method to be set
     * @return array $return_arr returns the credentials of the specified gateway
     */
    public function get_method_params($payment_method)
    {
        if ($payment_method == "PAYPAL_PRO") {
            $return_arr = array(
                'username' => $this->CI->config->item('OMNIPAY_PAYPAL_PRO_USERNAME'),
                'password' => $this->CI->config->item('OMNIPAY_PAYPAL_PRO_PASSWORD'),
                'signature' => $this->CI->config->item('OMNIPAY_PAYPAL_PRO_SIGNATURE'),
                'testMode' => $this->CI->config->item('OMNIPAY_PAYPAL_PRO_TESTMODE')
            );
        }
        if ($payment_method == "STRIPE") {
            $return_arr = array(
                'apiKey' => $this->CI->config->item('OMNIPAY_STRIPE_APIKEY'),
                'password' => $this->CI->config->item('OMNIPAY_STRIPE_PASSWORD'),
                'signature' => $this->CI->config->item('OMNIPAY_STRIPE_SIGNATURE'),
                'testMode' => $this->CI->config->item('OMNIPAY_STRIPE_TESTMODE')
            );
        }
        if ($payment_method == "AUTHORIZE_NET") {
            $return_arr = array(
                'apiLoginId' => $this->CI->config->item('OMNIPAY_AUTHORIZE_NET_APILOGIN_ID'),
                'transactionKey' => $this->CI->config->item('OMNIPAY_AUTHORIZE_NET_TRANSACTION_KEY'),
                'developerMode' => $this->CI->config->item('OMNIPAY_AUTHORIZE_NET_DEVELOPER_MODE'),
                'testMode' => $this->CI->config->item('OMNIPAY_AUTHORIZE_NET_TESTMODE'),
            );
        }
        if ($payment_method == "PAYFLOW_PRO") {
            $return_arr = array(
                'username' => $this->CI->config->item('OMNIPAY_PAYFLOW_PRO_USERNAME'),
                'password' => $this->CI->config->item('OMNIPAY_PAYFLOW_PRO_PASSWORD'),
                'vendor' => $this->CI->config->item('OMNIPAY_PAYFLOW_PRO_VENDOR'),
                'partner' => $this->CI->config->item('OMNIPAY_PAYFLOW_PRO_PARTNER'),
                'testMode' => $this->CI->config->item('OMNIPAY_PAYFLOW_PRO_TESTMODE'),
            );
        }
        if ($payment_method == "PAYPAL_EXPRESS") {
            $return_arr = array(
                'username' => $this->CI->config->item('OMNIPAY_PAYPAL_EXPRESS_USERNAME'),
                'password' => $this->CI->config->item('OMNIPAY_PAYPAL_EXPRESS_PASSWORD'),
                'signature' => $this->CI->config->item('OMNIPAY_PAYPAL_EXPRESS_SIGNATURE'),
                'solutionType' => $this->CI->config->item('OMNIPAY_PAYPAL_EXPRESS_SOLUTION_TYPE'),
                'landingPage' => $this->CI->config->item('OMNIPAY_PAYPAL_EXPRESS_LANDING_PAGE'),
                'brandName' => $this->CI->config->item('OMNIPAY_PAYPAL_EXPRESS_BRAND_NAME'),
                'headerImageUrl' => $this->CI->config->item('OMNIPAY_PAYPAL_EXPRESS_HEADER_IMG_URL'),
                'logoImageUrl' => $this->CI->config->item('OMNIPAY_PAYPAL_EXPRESS_LOGO_IMG_URL'),
                'borderColor' => $this->CI->config->item('OMNIPAY_PAYPAL_EXPRESS_BORDER_COLOR'),
                'testMode' => $this->CI->config->item('OMNIPAY_PAYPAL_EXPRESS_TESTMODE'),
            );
        }
        if ($payment_method == "AUTHORIZENET_SIM") {
            $return_arr = array(
                'apiLoginId' => $this->CI->config->item('OMNIPAY_AUTHORIZENET_SIM_APILOGIN_ID'),
                'transactionKey' => $this->CI->config->item('OMNIPAY_AUTHORIZENET_SIM_TRANSACTION_KEY'),
                'hashSecret' => $this->CI->config->item('OMNIPAY_AUTHORIZENET_SIM_HASH_SECRET'),
                'developerMode' => $this->CI->config->item('OMNIPAY_AUTHORIZENET_SIM_DEVELOPER_MODE'),
                'testMode' => $this->CI->config->item('OMNIPAY_AUTHORIZENET_SIM_TESTMODE'),
            );
        }
        return $return_arr;
    }
}
