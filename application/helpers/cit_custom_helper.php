<?php
if (!function_exists('text2image')) {

    /**
     * text2image function
     *
     * @param	array	$params	String to print, font size, font file, font color etc..
     * 
     */
    function text2image($params = array())
    {
        /* initialize parameters */
        $text = $params['text'];
        $fontSize = !empty($params['font_size']) ? $params['font_size'] : 10;
        $font_file = !empty($params['font_file']) ? $params['font_file'] : "public/styles/fonts/roboto-light-webfont.ttf";
        $font_color = !empty($params['font_color']) ? $params['font_color'] : "#000";
        $upload_path = !empty($params['upload_path']) ? $params['upload_path'] : "application/cache/temp/";
        $blur_intensity = !empty($params['blur_intensity']) ? $params['blur_intensity'] : null;

        $size = imageTTFBBox($fontSize, 0, $font_file, $text);
        $im = imageCreateTrueColor(abs($size[4]) + abs($size[0]) + 10, abs($size[5]) + abs($size[1]) + 10);
        imageSaveAlpha($im, true);
        ImageAlphaBlending($im, false);
        $transparentColor = imagecolorallocatealpha($im, 200, 200, 200, 127);
        imagefill($im, 0, 0, $transparentColor);


        /* calculate Rgb val for font color */
        if (substr($font_color, 0, 1) == "#") {
            $font_color = substr($font_color, 1);
        }
        $R = substr($font_color, 0, 2);
        $G = substr($font_color, 2, 2);
        $B = substr($font_color, 4, 2);
        $R = hexdec($R);
        $G = hexdec($G);
        $B = hexdec($B);

        $text_color = imagecolorallocate($im, $R, $G, $B); //black text
        imagettftextblur($im, $fontSize, 0, 0, 20, $text_color, $font_file, $text, $blur_intensity);

        $image_name = md5($text) . '.png';

        imagepng($im, $upload_path . '/' . $image_name);
        return $upload_path . '/' . $image_name;
    }
}

if (!function_exists('imagettftextblur')) {

    /**
     * imagettftextblur function
     *
     * @return	string
     */
    function imagettftextblur(&$image, $size, $angle, $x, $y, $color, $fontfile, $text, $blur_intensity = null)
    {
        $blur_intensity = !is_null($blur_intensity) && is_numeric($blur_intensity) ? (int) $blur_intensity : 0;
        if ($blur_intensity > 0) {
            $text_shadow_image = imagecreatetruecolor(imagesx($image), imagesy($image));
            imagefill($text_shadow_image, 0, 0, imagecolorallocate($text_shadow_image, 0x00, 0x00, 0x00));
            imagettftext($text_shadow_image, $size, $angle, $x, $y, imagecolorallocate($text_shadow_image, 0xFF, 0xFF, 0xFF), $fontfile, $text);
            for ($blur = 1; $blur <= $blur_intensity; $blur++)
                imagefilter($text_shadow_image, IMG_FILTER_GAUSSIAN_BLUR);
            for ($x_offset = 0; $x_offset < imagesx($text_shadow_image); $x_offset++) {
                for ($y_offset = 0; $y_offset < imagesy($text_shadow_image); $y_offset++) {
                    $visibility = (imagecolorat($text_shadow_image, $x_offset, $y_offset) & 0xFF) / 255;
                    if ($visibility > 0)
                        imagesetpixel($image, $x_offset, $y_offset, imagecolorallocatealpha($image, ($color >> 16) & 0xFF, ($color >> 8) & 0xFF, $color & 0xFF, (1 - $visibility) * 127));
                }
            }
            imagedestroy($text_shadow_image);
        } else {
            return imagettftext($image, $size, $angle, $x, $y, $color, $fontfile, $text);
        }
    }
}

if (!function_exists('pr')) {

    /**
     * pr function
     *
     * @param	string	$data	String to print
     * @param	int	$num	Number to exit
     */
    function pr($var, $ex = 0)
    {
        echo "<pre>";

        print_r($var);

        echo "</pre>";

        echo get_caller_method();

        if ($ex == 1) {
            exit;
        }
    }
}

// ------------------------------------------------------------------------

if (!function_exists('get_caller_method')) {

    /**
     * get_caller_method function
     *
     * @return	string
     */
    function get_caller_method()
    {
        $traces = debug_backtrace();

        if (isset($traces[2])) {
            return $traces[2]['function'];
        }

        return null;
    }
}

if (!function_exists('is_valid_array')) {

    /**
     * is_valid_array function
     *
     * @return	boolean
     */
    function is_valid_array($array = array())
    {
        if (is_array($array) && count($array) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

if (!function_exists('get_page_html_class')) {

    /**
     * get_page_html_class function
     *
     * @return	string
     */
    function get_page_html_class($class_name = '', $suffix = 'page-body')
    {
        if (empty($class_name)) {
            return '';
        }
        if (substr($class_name, 0, 4) == "cwf_") {
            $class_name = substr($class_name, 4);
        }
        $html_class = strtolower(preg_replace('/[^A-Za-z0-9-]/', '', str_replace(array(' ', '_'), '-', trim($class_name))));
        if ($suffix != '') {
            $html_class = $html_class . "-" . $suffix;
        }
        return $html_class;
    }
}

if (!function_exists('get_file_icon_class')) {

    /**
     * get_file_icon_class function
     *
     * @return	string
     */
    function get_file_icon_class($file_name = '')
    {
        $icon_class = 'fa-file-text-o';
        if (empty($file_name)) {
            return $icon_class;
        }
        $extension_arr = array(
            "fa-file-image-o" => array('jpg', 'jpeg', 'jpe', 'png', 'gif', 'bmp', 'ico', 'svg'),
            "fa-file-audio-o" => array("mid", "midi", "mpga", "mp2", "mp3", "aif", "aiff", "aifc", "ram", "rm", "rpm", "ra", "wav", "aac", "ac3"),
            "fa-file-video-o" => array("rv", "mpeg", "mpg", "mpe", "qt", "mov", "avi", "movie", "3g2", "3gp", "mp4", "m4a", "f4v", "flv", "webm", "wmv", "ogg"),
            "fa-file-code-o" => array("js", "css", "html", "htm", "shtml", "json", "swf", "psd"),
            "fa-file-excel-o" => array("csv", "xl", "xls", "xlsx", "ods"),
            "fa-file-archive-o" => array("gtar", "gz", "gzip", "tar", "tgz", "z", "zip", "rar", "7zip", "jar"),
            "fa-file-pdf-o" => array("pdf", "ai"),
            "fa-file-powerpoint-o" => array("ppt", "pptx"),
            "fa-file-word-o" => array("doc", "docx", "dot", "dotx", "word")
        );
        $split_arr = explode(".", $file_name);
        foreach ($extension_arr as $key => $val) {
            if (in_array(end($split_arr), $val)) {
                $icon_class = $key;
                break;
            }
        }
        return $icon_class;
    }
}


if (!function_exists('format_uploaded_file')) {

    /**
     * format_uploaded_file function
     *
     * @return	string
     */
    function format_uploaded_file($file_name = '')
    {
        if (empty($file_name)) {
            return $file_name;
        }
        if (strstr($file_name, '20') !== FALSE) {
            $file_name = substr($file_name, 0, strrpos($file_name, '.', 0) - 21) . substr($file_name, strrpos($file_name, '.', 0));
        }
        return $file_name;
    }
}

if (!function_exists('parse_response_header')) {

    /**
     * parse_response_header function
     *
     * @return	string
     */
    function parse_response_header($headers)
    {
        $response = array();
        foreach ($headers as $k => $v) {
            $t = explode(':', $v, 2);
            if (isset($t[1]))
                $response[trim($t[0])] = trim($t[1]);
            else {
                $response[] = $v;
                if (preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $v, $out))
                    $response['reponse_code'] = intval($out[1]);
            }
        }
        return $response;
    }
}