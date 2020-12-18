<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Nexmo SMS Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module SMS
 * 
 * @class Nexmo.php
 * 
 * @path application\libraries\Nexmo.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Nexmo
{

    protected $CI;
    protected $client;
    protected $from_no;

    public function __construct($auth = array())
    {
        $this->CI = & get_instance();
        require_once($this->CI->config->item('third_party') . "nexmo/vendor/autoload.php");

        $credentails = new Nexmo\Client\Credentials\Basic($auth['api_key'], $auth['api_secret']);
        $this->client = new Nexmo\Client($credentails);

        if (!empty($auth['from_no'])) {
            $this->setFromNumber($auth['from_no']);
        }
    }

    public function setFromNumber($from_no = '')
    {
        $this->from_no = $from_no;
    }

    public function sendMessage($to_no = '', $message = '')
    {
        try {
            $result = $this->client->message()->send([
                'to' => $to_no,
                'from' => $this->from_no,
                'text' => $message
            ]);
            $success = 1;
            $message = 'Message sent.';
        } catch (Nexmo\Client\Exception\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        return array(
            'success' => $success,
            'message' => $message
        );
    }

    public function startVerification($to_no = '', $brand = '')
    {
        try {
            $data = array();
            $verification = $this->client->verify()->start([
                'number' => $to_no,
                'brand' => $brand
            ]);
            $data['request_id'] = $verification->getRequestId();
        } catch (Nexmo\Client\Exception\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        return array(
            'data' => $data,
            'success' => $success,
            'message' => $message
        );
    }

    public function checkVerification($request_id = '', $code = '')
    {
        try {
            $this->client->verify()->check($request_id, $code);
            $success = 1;
            $message = 'Verification done.';
        } catch (Nexmo\Client\Exception\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        return array(
            'success' => $success,
            'message' => $message
        );
    }

    public function cancelVerification($request_id = '')
    {
        try {
            $this->client->verify()->cancel($request_id);
            $success = 1;
            $message = 'Verification cancelled.';
        } catch (Nexmo\Client\Exception\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        return array(
            'success' => $success,
            'message' => $message
        );
    }

    public function resendVerification($request_id = '')
    {
        try {
            $this->client->verify()->trigger($request_id);
            $success = 1;
            $message = 'Verification resend.';
        } catch (Nexmo\Client\Exception\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        return array(
            'success' => $success,
            'message' => $message
        );
    }
}

/* End of file Nexmo.php */
/* Location: ./application/libraries/Nexmo.php */