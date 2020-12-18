<?php
$q_file = $_REQUEST['q_file'];
$q_type = $_REQUEST['q_type'];

$q_file = str_replace(array("../", "./"), "", $q_file);
$ext = strtolower(substr($q_file, strrpos($q_file, ".")));
$mime_types = array(
    '.txt' => 'text/plain',
    '.css' => 'text/css',
    '.htm' => 'text/html',
    '.html' => 'text/html',
    '.shtml' => 'text/html',
    '.php' => 'text/html',
    '.php5' => 'text/html',
    '.css' => 'text/css',
    '.js' => 'application/javascript',
    '.json' => 'application/json',
    '.xml' => 'application/xml',
    '.swf' => 'application/x-shockwave-flash',
    '.flv' => 'video/x-flv',
    // images
    '.png' => 'image/png',
    '.jpe' => 'image/jpeg',
    '.jpeg' => 'image/jpeg',
    '.jpg' => 'image/jpeg',
    '.gif' => 'image/gif',
    '.bmp' => 'image/bmp',
    '.ico' => 'image/x-icon',
    '.woff' => 'application/x-font-woff',
    '.woff2' => 'application/x-font-woff',
    '.ttf' => 'application/x-font-ttf',
    '.tiff' => 'image/tiff',
    '.tif' => 'image/tiff',
    '.svg' => 'image/svg+xml',
    '.svgz' => 'image/svg+xml',
    // video
    '.3gp' => 'video/3gpp',
    '.3g2' => 'video/3g2',
    '.avi' => 'video/avi',
    '.mp4' => 'video/mp4',
    '.asf' => 'video/asf',
    '.mov' => 'video/quicktime',
);

if (array_key_exists($ext, $mime_types)) {
    $mm_type = $mime_types[$ext];
} else {
    $mm_type = "application/octet-stream";
}
#####GENERATED_HANDLER_SETTINGS_START#####
if ($q_type == "js" || $q_type == "css") {
    $r_file = "application/cache/" . $q_file;
} elseif ($q_type == "admin_image") {
    $r_file = "public/images/admin/" . $q_file;
} elseif ($q_type == "front_image") {
    $r_file = "public/images/" . $q_file;
} elseif ($q_type == "font") {
    $r_file = "public/styles/fonts/" . $q_file;
} elseif ($q_type == "direct_font") {
    $r_file = "public/fonts/" . $q_file;
}
#####GENERATED_HANDLER_SETTINGS_END#####

if (is_file($r_file)) {
    $timestamp = filemtime($r_file);
} else {
    $timestamp = time() . rand(1, 100000);
}
$gmt_mtime = gmdate('r', $timestamp);

if (ob_get_length() > 0) {
    ob_end_clean();
}
if (strstr($q_file, '../') !== FALSE || strstr($q_file, './') !== FALSE) {
    header('HTTP/1.1 400 Bad request');
    exit;
}
if ((isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || isset($_SERVER['HTTP_IF_NONE_MATCH'])) &&
    ($_SERVER['HTTP_IF_MODIFIED_SINCE'] == $gmt_mtime || str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == md5($timestamp . $q_file))) {
    header('HTTP/1.1 304 Not Modified');
    exit;
}
if (in_array($mm_type, array('text/css', 'text/plain', 'text/html', 'application/javascript', 'application/json', 'application/xml'))) {
    if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== FALSE) {
        ob_start("ob_gzhandler");
        header('Content-Encoding: gzip');
    } else {
        ob_start();
    }
} else {
    ob_start();
    if (is_file($r_file)) {
        header("Content-Length: " . filesize($r_file));
    }
}
header("Access-Control-Allow-Origin: *");
header('ETag: "' . md5($timestamp . $r_file) . '"');
header('Last-Modified: ' . $gmt_mtime);
header("Content-Type: " . $mm_type);
if ($q_type == "admin_image" || $q_type == "front_image" || $q_type == "font") {
    header('Cache-Control: max-age=2592000, public');
} elseif ($q_type == "css") {
    header('Cache-Control: max-age=604800, public');
} elseif ($q_type == "js") {
    header('Cache-Control: max-age=216000, public');
} else {
    header('Cache-Control: max-age=600, public');
}
if (is_file($r_file)) {
    readfile($r_file);
}
exit;
