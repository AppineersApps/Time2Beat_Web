<?php
defined('BASEPATH') || exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	https://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There are three reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router which controller/method to use if those
  | provided in the URL cannot be matched to a valid route.
  |
  |	$route['translate_uri_dashes'] = FALSE;
  |
  | This is not exactly a route, but allows you to automatically route
  | controller and method names that contain dashes. '-' isn't a valid
  | class or method name character, so it requires translation.
  | When you set this option to TRUE, it will replace ALL dashes in the
  | controller and method URI segments.
  |
  | Examples:	my-controller/index	-> my_controller/index
  |		my-controller/my-method	-> my_controller/my_method
 */
$route['default_controller'] = "content/content/index";
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['admin'] = "dashboard/dashboard/sitemap";
$route['admin/(:any)'] = "$1";

$route['user.html'] = "user/user/index";
$route['index.html'] = "content/content/index";
$route['signup.html'] = "user/user/register";
$route['profile.html'] = "user/user/profile";
$route['login.html'] = "user/user/login";
$route['logout.html'] = "user/user/logout";
$route['dashboard.html'] = "user/user/dashboard";
$route['forgot-password.html'] = "user/user/forgotpassword";
$route['reset-password.html'] = "user/user/resetpassword";
$route['verify-email.html'] = "user/user/verifyemail";
$route['error.html'] = "content/content/error";
$route['captcha.html'] = "content/content/captcha";
$route['two-factor.html'] = "user/user/twofactor";
$route['try-another.html'] = "user/user/tryanother";
$route['resend.html'] = "user/user/resend_otp";
$route['auth_setup.html'] = "user/user/setup_google_auth";
$route['auth_verify.html'] = "user/user/verify_google_auth";

// webservices
$route['WS'] = "wsengine/wscontroller/listWSMethods";
$route['WS/(:any)'] = "wsengine/wscontroller/WSExecuter/$1";
$route['WS/(:any)/(:any)'] = "wsengine/wscontroller/WSExecuter/$1/$2";
$route['WS/execute'] = "rest/restcontroller/execute_notify_schedule";
$route['WS/savelog'] = "rest/restcontroller/process_access_log";
$route['WS/image_resize'] = "rest/restcontroller/image_resize";
$route['WS/create_token'] = "rest/restcontroller/create_token";
$route['WS/inactive_token'] = "rest/restcontroller/inactive_token";
$route['WS/get_push_notification'] = "rest/restcontroller/get_push_notification";
$route['WS/regenerate_token'] = "wsengine/wscontroller/regenerateJWTToken";



// third-party login
$route['WS/facebook/login'] = "wsengine/third_party/facebook";
$route['WS/twitter/login'] = "wsengine/third_party/twitter";
$route['WS/salesforce/login'] = "wsengine/third_party/salesforce";

// notifications
$route['NS'] = "nsengine/notifycontroller/listNSMethods";
$route['NS/(:any)'] = "nsengine/notifycontroller/notifyExecuter/$1";
$route['NS/execute'] = "nsengine/notifycontroller/executeNotifySchedule";
$route['NS/archive'] = "nsengine/archivecontroller/executeArchiveTables";

// CIT Parse APIs
//Parse users module
$route['PS'] = "psengine/pscontroller/viewPSConsole"; // API-Console
$route['PS/users'] = "users/users/users"; //GET-POST
$route['PS/users/me'] = "users/users/mine"; //GET
$route['PS/users/([a-zA-Z0-9]+)'] = "users/users/user/$1"; //GET-PUT-DELETE
$route['PS/login'] = "users/users/login"; //GET
$route['PS/logout'] = "users/users/logout"; //POST
$route['PS/requestPasswordReset'] = "users/users/req_pwd_reset"; //POST
//Parse session module
$route['PS/sessions'] = "sessions/sessions/sessions"; //GET-POST
$route['PS/sessions/me'] = "sessions/sessions/mine"; //GET-PUT
$route['PS/sessions/([a-zA-Z0-9]+)'] = "sessions/sessions/session/$1"; //GET-PUT-DELETE
//Parse inatllation module
$route['PS/installations'] = "installations/installations/installations"; //GET-POST
$route['PS/installations/([a-zA-Z0-9]+)'] = "installations/installations/installation/$1"; //GET-PUT-DELETE
//Parse roles module
$route['PS/roles'] = "roles/roles/roles"; //POST
$route['PS/roles/([a-zA-Z0-9]+)'] = "roles/roles/role/$1"; //GET-PUT-DELETE
//Parse files module
$route['PS/files/(:any)'] = "files/files/file/$1"; //GET-POST
//Parse push module
$route['PS/push'] = "push/push/push"; //POST
//Parse batch module
$route['PS/batch'] = "batch/batch/batch"; //POST
//Parse classes module
$route['PS/classes/([a-zA-Z0-9_]+)'] = "classes/classes/classes/$1"; //GET-POST
$route['PS/classes/([a-zA-Z0-9_]+)/([a-zA-Z0-9]+)'] = "classes/classes/class/$1/$2"; //GET-PUT-DELETE

$route['content/(:any)'] = "content/content/staticpage/$1";
$route['content/(:any)/(:any)'] = "content/content/staticpage/$1/$2";
$route['clear-cache.html'] = "content/content/clear_cache";
$route['do-payment.html'] = "content/content/do_payment";
$route['payment-response.html'] = "content/content/payment_response";
$route['payment-notify.html'] = "content/content/payment_notify";
/**
 * Loading Some of the front routes file to specify custom key-values
 * 
 */
require_once 'routes_custom.php';

require_once 'routes_front.php';

if (($this->uri->segments[1] == "WS" && $this->uri->segments[2] == "image_resize") ||
    ($this->uri->segments[1] == "error.html")) {
    //skip for image resize and database connection errors
    $GLOBALS['_DB_LIBRARY_NOT_REQ_'] = TRUE;
} else {
    // Database initialization

    $db = & CIT_DB();

    if ($db === FALSE) {
        //redirecting to installation page
        if (is_dir(FCPATH . "installer")) {
            $site_installer_url = (is_https() ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']) . 'installer/';
            header("Location:" . $site_installer_url);
            exit;
        } else {
            show_error('No database connection settings were found in the database config file.');
        }
    }

    if (!$db->conn_id) {
        header("Location:" . $this->config->item('site_url') . "error.html");
        exit;
    }

    $static_pages_obj = $db->select("vPageCode, vUrl, vPageTitle")->where('eStatus', 'Active')->get('mod_page_settings');
    $static_pages = is_object($static_pages_obj) ? $static_pages_obj->result_array() : array();
    foreach ($static_pages as $i => $route_arr) {
        $route[$route_arr['vUrl']] = "content/content/staticpage/" . $route_arr['vPageCode'];
    }
    if ($this->config->item('is_admin') == 1) {
        $db->select("vName, vValue");
        $db->where("eStatus", 'Active');
        $db->where_in("vName", array('ADMIN_URL_ENCRYPTION', 'ADMIN_ENC_KEY'));
        $uri_enc_router = $db->select_assoc("mod_setting", "vName");
        $this->config->set_item("ADMIN_ENC_KEY", $uri_enc_router['ADMIN_ENC_KEY'][0]['vValue']);
        $this->config->set_item("ADMIN_URL_ENCRYPTION", $uri_enc_router['ADMIN_URL_ENCRYPTION'][0]['vValue']);
    }
}