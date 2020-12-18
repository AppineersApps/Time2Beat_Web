<?php
defined('BASEPATH') || exit('No direct script access allowed');

#####GENERATED_DEBUG_SETTINGS_START#####

$config["change_mobile_number"] = array(
    "check_unique_mobile_number" => array(
        "type" => "query",
        "next" => "is_unique"
    ),
    "is_unique" => array(
        "type" => "condition",
        "next" => array("update_mobile_number", "users_finish_success")
    ),
    "users_finish_success" => array(
        "type" => "finish"
    ),
    "update_mobile_number" => array(
        "type" => "query",
        "next" => "check_number_updated"
    ),
    "check_number_updated" => array(
        "type" => "condition",
        "next" => array("users_finish_success_2", "users_finish_success_1")
    ),
    "users_finish_success_1" => array(
        "type" => "finish"
    ),
    "users_finish_success_2" => array(
        "type" => "finish"
    )
);
$config["rides"] = array(
    "set_ride" => array(
        "type" => "query",
        "next" => "get_ride_details"
    ),
    "get_ride_details" => array(
        "type" => "query",
        "next" => "is_posted"
    ),
    "is_posted" => array(
        "type" => "condition",
        "next" => array("get_ride_finish_success", "get_ride_finish_success_1")
    ),
    "get_ride_finish_success" => array(
        "type" => "finish"
    ),
    "get_ride_finish_success_1" => array(
        "type" => "finish"
    )
);
$config["shared_rides"] = array(
    "get_share_ride" => array(
        "type" => "query",
        "next" => "get_ride_details"
    ),
    "get_ride_details" => array(
        "type" => "query",
        "next" => "is_posted"
    ),
    "is_posted" => array(
        "type" => "condition",
        "next" => array("get_ride_finish_success", "get_ride_finish_success_1")
    ),
    "get_ride_finish_success" => array(
        "type" => "finish"
    ),
    "get_ride_finish_success_1" => array(
        "type" => "finish"
    )
);
$config["change_password"] = array(
    "check_password" => array(
        "type" => "function",
        "next" => "is_matches"
    ),
    "is_matches" => array(
        "type" => "condition",
        "next" => array("finish_success", "update_new_password")
    ),
    "update_new_password" => array(
        "type" => "query",
        "next" => "users_finish_success"
    ),
    "users_finish_success" => array(
        "type" => "finish"
    ),
    "finish_success" => array(
        "type" => "finish"
    )
);
$config["country_list"] = array(
    "get_country_list" => array(
        "type" => "query",
        "next" => "is_country_list_exists"
    ),
    "is_country_list_exists" => array(
        "type" => "condition",
        "next" => array("finish_country_list_failure", "finish_country_list_success")
    ),
    "finish_country_list_success" => array(
        "type" => "finish"
    ),
    "finish_country_list_failure" => array(
        "type" => "finish"
    )
);
$config["country_with_states"] = array(
    "get_country_data" => array(
        "type" => "query",
        "next" => "is_country_data_exists"
    ),
    "is_country_data_exists" => array(
        "type" => "condition",
        "next" => array("finish_country_data_failure", "country_start_loop")
    ),
    "country_start_loop" => array(
        "type" => "startloop",
        "next" => "get_state_list",
        "end" => "",
        "loop" => array("get_country_data", "array")
    ),
    "get_state_list" => array(
        "type" => "query",
        "next" => "country_end_loop"
    ),
    "country_end_loop" => array(
        "type" => "endloop",
        "next" => "finish_country_data_success"
    ),
    "finish_country_data_success" => array(
        "type" => "finish"
    ),
    "finish_country_data_failure" => array(
        "type" => "finish"
    )
);
$config["logout"] = array(
    "logout" => array(
        "type" => "query",
        "next" => "users_finish_success"
    ),
    "users_finish_success" => array(
        "type" => "finish"
    )
);
$config["post_a_feedback"] = array(
    "post_a_feedback" => array(
        "type" => "query",
        "next" => "is_posted"
    ),
    "is_posted" => array(
        "type" => "condition",
        "next" => array("user_query_finish_success_1", "user_query_finish_success")
    ),
    "user_query_finish_success" => array(
        "type" => "finish"
    ),
    "user_query_finish_success_1" => array(
        "type" => "finish"
    )
);
$config["reset_password"] = array(
    "check_user_exists" => array(
        "type" => "query",
        "next" => "is_user_found"
    ),
    "is_user_found" => array(
        "type" => "condition",
        "next" => array("users_finish_success", "update_password")
    ),
    "update_password" => array(
        "type" => "query",
        "next" => "users_finish_success_1"
    ),
    "users_finish_success_1" => array(
        "type" => "finish"
    ),
    "users_finish_success" => array(
        "type" => "finish"
    )
);
$config["static_pages"] = array(
    "get_page_details" => array(
        "type" => "query",
        "next" => "is_page_exists"
    ),
    "is_page_exists" => array(
        "type" => "condition",
        "next" => array("mod_page_settings_finish_success_1", "mod_page_settings_finish_success")
    ),
    "mod_page_settings_finish_success" => array(
        "type" => "finish"
    ),
    "mod_page_settings_finish_success_1" => array(
        "type" => "finish"
    )
);
$config["update_device_token"] = array(
    "update_device_token" => array(
        "type" => "query",
        "next" => "is_user_exists"
    ),
    "is_user_exists" => array(
        "type" => "condition",
        "next" => array("users_finish_success", "users_finish_success_1")
    ),
    "users_finish_success_1" => array(
        "type" => "finish"
    ),
    "users_finish_success" => array(
        "type" => "finish"
    )
);
$config["update_push_notification_settings"] = array(
    "update_notification" => array(
        "type" => "query",
        "next" => "users_finish_success"
    ),
    "users_finish_success" => array(
        "type" => "finish"
    )
);
$config["user_email_confirmation"] = array(
    "get_user" => array(
        "type" => "query",
        "next" => "is_user_found"
    ),
    "is_user_found" => array(
        "type" => "condition",
        "next" => array("check_for_activation", "users_finish_success_2")
    ),
    "users_finish_success_2" => array(
        "type" => "finish"
    ),
    "check_for_activation" => array(
        "type" => "condition",
        "next" => array("update_confirmation", "users_finish_success")
    ),
    "users_finish_success" => array(
        "type" => "finish"
    ),
    "update_confirmation" => array(
        "type" => "query",
        "next" => "users_finish_success_1"
    ),
    "users_finish_success_1" => array(
        "type" => "finish"
    )
);
$config["user_sign_up_email"] = array(
    "custom_function" => array(
        "type" => "function",
        "next" => "check_status"
    ),
    "check_status" => array(
        "type" => "condition",
        "next" => array("finish_success_1", "email_verification_code")
    ),
    "email_verification_code" => array(
        "type" => "function",
        "next" => "create_user"
    ),
    "create_user" => array(
        "type" => "query",
        "next" => "is_user_created"
    ),
    "is_user_created" => array(
        "type" => "condition",
        "next" => array("users_finish_success_1", "get_user_details")
    ),
    "get_user_details" => array(
        "type" => "query",
        "next" => "email_notification"
    ),
    "email_notification" => array(
        "type" => "notifyemail",
        "next" => "users_finish_success"
    ),
    "users_finish_success" => array(
        "type" => "finish"
    ),
    "users_finish_success_1" => array(
        "type" => "finish"
    ),
    "finish_success_1" => array(
        "type" => "finish"
    )
);#####GENERATED_DEBUG_SETTINGS_END#####
/* End of file cit_wsdebugger.php */
/* Location: ./application/config/cit_wsdebugger.php */