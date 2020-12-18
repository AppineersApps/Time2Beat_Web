<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Third party Controller
 *
 * @category webservice
 *            
 * @package wsengine
 * 
 * @subpackage controllers
 * 
 * @module Third Party Controller
 * 
 * @class Third_party.php
 * 
 * @path application\webservice\wsengine\controllers\Third_party.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Third_party extends Cit_Controller
{

    protected $oauth_client;
    protected $oauth_config;

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('wsresponse');
        require_once($this->config->item('third_party') . 'oauth/vendor/autoload.php');
        $this->oauth_client = new oauth_client_class;
        require_once($this->config->item('third_party') . 'oauth/oauth_config.php');
        $this->oauth_config = $oauth_config;
    }

    public function setOAuthConfig($server, $mode = 'before')
    {
        $oauth_details = $this->oauth_config[$server];
        if ($mode == "after") {
            $this->oauth_client->oauth_version = $oauth_details['auth_version'];
            $this->oauth_client->dialog_url = $oauth_details['dialogue_url'];
            $this->oauth_client->access_token_url = $oauth_details['access_token_url'];
            $this->oauth_client->url_parameters = $oauth_details['url_parameters'];
            $this->oauth_client->authorization_header = $oauth_details['authorization_header'];
            $this->oauth_client->request_token_url = $oauth_details['request_token_url'];
            $this->oauth_client->token_request_method = $oauth_details['token_request_method'];
            $this->oauth_client->access_token_authentication = $oauth_details['access_token_authentication'];
            $this->oauth_client->instance_url_parameter = $oauth_details['instance_url'];
        } else {
            $this->oauth_client->debug = false;
            $this->oauth_client->debug_http = true;
            $this->oauth_client->server = $oauth_details['api_code'];
            $this->oauth_client->client_id = $oauth_details['client_id'];
            $this->oauth_client->client_secret = $oauth_details['client_secret'];
            $this->oauth_client->redirect_uri = $oauth_details['redirect_uri'];
            $this->oauth_client->configuration_file = $this->config->item('third_party') . 'oauth/oauth_configuration.json';
        }
    }

    public function facebook()
    {
        $settings_arr = $data = array();
        try {
            $server = "facebook";
            $this->setOAuthConfig($server, "before");
            if (strlen($this->oauth_client->client_id) == 0 || strlen($this->oauth_client->client_secret) == 0) {
                throw new Exception('Please go to Facebook Graph APIs console page ' .
                'https://developers.facebook.com/apps/ in the API access link, ' .
                'create a new client ID, and set the client_id to Client ID and client_secret. ' .
                'The callback URL must be ' . $this->oauth_client->redirect_uri . ' but make sure ' .
                'the domain is valid and can be resolved by a public DNS.');
            }
            if ($success = $this->oauth_client->Initialize()) {
                $this->setOAuthConfig($server, "after");
                if ($success = $this->oauth_client->Process()) {
                    if (strlen($this->oauth_client->authorization_error)) {
                        throw new Exception($this->oauth_client->authorization_error);
                    }
                }
                $success = $this->oauth_client->Finalize($success);
            }
            $settings_arr['success'] = 1;
            $settings_arr['message'] = "Token generated successfully.";
            $data['access_token'] = $this->oauth_client->access_token;
        } catch (Exception $e) {
            $settings_arr['success'] = 0;
            $settings_arr['message'] = $e->getMessage();
        }
        //need to add JS code
        $responce_arr['settings'] = $settings_arr;
        $responce_arr['data'] = $data;
        $this->wsresponse->sendWSResponse($responce_arr);
    }

    public function twitter()
    {
        $settings_arr = $data = array();
        try {
            $this->setOAuthConfig("twitter", "before");
            if (strlen($this->oauth_client->client_id) == 0 || strlen($this->oauth_client->client_secret) == 0) {
                throw new Exception('Please go to Twitter APIs console page ' .
                'https://apps.twitter.com/ in the API access link, ' .
                'create a new client ID, and set the client_id to Client ID and client_secret. ' .
                'The callback URL must be ' . $this->oauth_client->redirect_uri . ' but make sure ' .
                'the domain is valid and can be resolved by a public DNS.');
            }

            if ($success = $this->oauth_client->Initialize()) {
                $this->setOAuthConfig("twitter", "after");
                if ($success = $this->oauth_client->Process()) {
                    if (strlen($this->oauth_client->authorization_error)) {
                        throw new Exception($this->oauth_client->authorization_error);
                    }
                }
                $success = $this->oauth_client->Finalize($success);
            }
            $settings_arr['success'] = 1;
            $settings_arr['message'] = "Token generated successfully.";
            $data['access_token'] = $this->oauth_client->access_token;
        } catch (Exception $e) {
            $settings_arr['success'] = 0;
            $settings_arr['message'] = $e->getMessage();
        }
        
        //need to add JS code
        $responce_arr['settings'] = $settings_arr;
        $responce_arr['data'] = $data;
        $this->wsresponse->sendWSResponse($responce_arr);
    }
    
    public function salesforce()
    {
        $settings_arr = $data = array();
        try {
            $server = "salesforce";
            $this->setOAuthConfig($server, "before");
            if (strlen($this->oauth_client->client_id) == 0 || strlen($this->oauth_client->client_secret) == 0) {
                throw new Exception('Please go to Salesforce APIs console page ' .
                'https://ap2.salesforce.com/ in the API access link, ' .
                'create a new client ID, and set the client_id to Client ID and client_secret. ' .
                'The callback URL must be ' . $this->oauth_client->redirect_uri . ' but make sure ' .
                'the domain is valid and can be resolved by a public DNS.');
            }
            if ($success = $this->oauth_client->Initialize()) {
                $this->setOAuthConfig($server, "after");
                if ($success = $this->oauth_client->Process()) {
                    if (strlen($this->oauth_client->authorization_error)) {
                        throw new Exception($this->oauth_client->authorization_error);
                    }
                }
                $success = $this->oauth_client->Finalize($success);
            }
            $settings_arr['success'] = 1;
            $settings_arr['message'] = "Token generated successfully.";
            $data['access_token'] = $this->oauth_client->access_token;
        } catch (Exception $e) {
            $settings_arr['success'] = 0;
            $settings_arr['message'] = $e->getMessage();
        }
        //need to add JS code
        $responce_arr['settings'] = $settings_arr;
        $responce_arr['data'] = $data;
        $this->wsresponse->sendWSResponse($responce_arr);
    }
}
