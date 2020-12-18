<?php
defined('BASEPATH') || exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | Hooks
  | -------------------------------------------------------------------------
  | This file lets you define "hooks" to extend CI without hacking the core
  | files.  Please see the user guide for info:
  |
  |	https://codeigniter.com/user_guide/general/hooks.html
  |
 */

if (isset($_ENV['access_log']) && $_ENV['access_log'] == 1) {
    $hook['pre_controller'][] = array(
        'class' => 'AccessLogHook',
        'function' => 'http_request_log',
        'filename' => 'AccessLogHook.php',
        'filepath' => 'hooks',
        'params' => array(
            "admin" => false,
            "front" => false,
            "parseapi" => true,
            "webservice" => true,
            "notification" => false,
            "admin_system_calls" => array(
                "user" => array(
                    "login" => array("sess_expire", "manifest", "tbcontent", "notify_events")
                )
            )
        )
    );
    $hook['pre_controller'][] = array(
        'class' => 'AuthCallbackHook',
        'function' => 'auth_custom_callback',
        'filename' => 'AuthCallbackHook.php',
        'filepath' => 'hooks'
    );
}
$hook['pre_controller'][] = array(
    'class' => 'CITExtentionHook',
    'function' => 'init',
    'filename' => 'CITExtentionHook.php',
    'filepath' => 'hooks',
);
$hook['post_controller'][] = array(
    'class' => 'FinalControllerHook',
    'function' => 'final_controller_actions',
    'filename' => 'FinalControllerHook.php',
    'filepath' => 'hooks'
);
$hook['post_system'][] = array(
    'class' => 'FinalSystemHook',
    'function' => 'final_actions',
    'filename' => 'FinalSystemHook.php',
    'filepath' => 'hooks'
);
$hook['post_controller_constructor'][] = array(
    'class' => 'ActivityLogHook',
    'function' => 'controller_constructor_actions',
    'filename' => 'ActivityLogHook.php',
    'filepath' => 'hooks'
);
$hook['post_controller'][] = array(
    'class' => 'ActivityLogHook',
    'function' => 'controller_complete_actions',
    'filename' => 'ActivityLogHook.php',
    'filepath' => 'hooks'
);
