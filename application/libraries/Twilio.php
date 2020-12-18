<?php
defined('BASEPATH') || exit('No direct script access allowed');

use Twilio\Rest\Client;

/**
 * Description of Twilio SMS Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module SMS
 * 
 * @class Twilio.php
 * 
 * @path application\libraries\Twilio.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Twilio
{

    protected $CI;
    protected $client;
    protected $from_no;

    public function __construct($auth = array())
    {
        $this->CI = & get_instance();
        require_once($this->CI->config->item('third_party') . "twilio/vendor/autoload.php");

        $this->client = new Client($auth['sid'], $auth['token']);

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
            $sid = '';
            $args = array(
                'From' => $this->from_no,
                'Body' => $message
            );
            $result = $this->client->messages->create($to_no, $args);
            if ($result->sid == '') {
                throw new Exception("SMS sending failed");
            }
            $sid = $result->sid;
            $success = 1;
            $message = 'Message sent.';
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        return array(
            'sid' => $sid,
            'success' => $success,
            'message' => $message
        );
    }
}

/* End of file Nexmo.php */
/* Location: ./application/libraries/Nexmo.php */