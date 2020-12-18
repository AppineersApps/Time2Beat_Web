<?php
defined('BASEPATH') || exit('No direct script access allowed');

#####GENERATED_CONFIG_SETTINGS_START#####

//Mysql installation directory
$config['mysqlexportinstalldir'] = "/usr/bin/mysqldump";
$config['mysqlimportinstalldir'] = "/usr/bin/mysql";
//ImageMagic installation directory
$config['imagemagickinstalldir'] = "/usr/bin/";
//Physical record delete activate flag
$config["PHYSICAL_RECORD_DELETE"] = TRUE;                    
//DB record limit - to avoid hitting the database server from large data sets fetching.
$config['db_max_limit'] = 0;
//Common JS/CSS Path
$config['cmn_js_path'] = '';
$config['cmn_css_path'] = '';
//CDN server details - For more info, please read this file ../public/cdn/readme.txt.
$config['cdn_activate'] = false;
$config['cdn_http_url'] = '';
if($config['cdn_activate'] === TRUE){
    $config['images_url'] = $config['cdn_http_url'] . $config['assets_folder'] . '/' . $config['images_folder'] . '/';
    $config['admin_images_url'] = $config['images_url'] . 'admin' . '/';
}
$config['login_callback'] = '';
$config['auth_callback'] = '';
$config['menu_callback'] = '';
#####GENERATED_CONFIG_SETTINGS_END#####

/* End of file config_custom.php */
/* Location: ./application/config/config_custom.php */