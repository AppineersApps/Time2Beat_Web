<?php
defined('BASEPATH') || exit('No direct script access allowed');

/*
 * Required Config Two Variable in CodeIgniter
 * $config['captcha_font'] = path for captcha font
 * $config['captcha_temp_path'] = path for temp image
 * temp_path needed for read & write operations
 * $params = array(
 *      "type" => "session" // session/google
 *      "format" => "png",
 *      "length" => 6,
 *      "height" => 75,
 *      "case" => false,
 *      "filters" => array(
 *          "noise" => 20,
 *          "blur" => 5
 *      ),
 *      "bg_color" => array(255, 232, 255),
 *      "text_color" => array(0, 134, 11)
 *  );
 * $this->captcha->init($params);
 * <img src="<%$this->captcha->show()%>" />
 */

class Captcha
{

    protected $CI;
    protected $fonts;
    protected $font_path;
    protected $length = 6;
    protected $case = false;
    protected $filters = array();
    protected $img_format = "png";
    protected $text_color = array(0, 0, 0);
    protected $bg_color = array(175, 214, 136);
    protected $captcha_type = 'session';
    protected $session_key = '_cit_captcha';
    protected $params = array();

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('session');
    }

    public function init($params = array())
    {
        if (is_array($params)) {
            $this->params = $params;
        }
        $this->session_key = isset($this->params['session_key']) ? $this->params['session_key'] : $this->session_key;
        $this->bg_color = isset($this->params['bg_color']) && is_array($this->params['bg_color']) && $this->_color($this->params['bg_color']) ? $this->params['bg_color'] : $this->bg_color;
        $this->text_color = isset($this->params['text_color']) && $this->_color($this->params['text_color']) ? $this->params['text_color'] : $this->text_color;
        if (isset($this->params['case']) && is_bool($this->params['case'])) {
            $this->case = $this->params['case'];
        }
        if (isset($this->params['filters']) && is_array($this->params['filters'])) {
            $this->filters = $this->params['filters'];
        }
        if (isset($this->params['format']) && is_string($this->params['format'])) {
            $this->img_format = $this->params['format'];
        }
        if (isset($this->params['type']) && is_string($this->params['type'])) {
            $this->setCaptchType($this->params['type']);
        }
    }

    public function show($type = '', $image = FALSE)
    {
        if ($type != "") {
            $this->setCaptchType($type);
        }
        if ($this->captcha_type == "google") {
            $site_key = $this->CI->config->item('GOOGLE_CAPTCHA_SITE_KEY');
            echo '<div class="g-recaptcha" data-sitekey="' . $site_key . '"></div>';
        } else {
            $this->font_path = $this->_getFontPath();
            $this->fonts = $this->_getFonts();
            $img_src = $this->_makeCaptcha();
            if ($image == TRUE) {
                echo '<img src="' . $img_src . '" class="cit-captcha-image" id="cit-captcha-image" />';
            } else {
                $box_width = isset($this->params['length']) && is_numeric($this->params['length']) ? $this->params['length'] * 25 + 16 : $this->length * 25 + 16;
                echo '
        <div class="cit-captcha-container" id="cit-captcha-container">
            <div class="cit-captcha-source">
                <img src="' . $img_src . '" class="cit-captcha-image" id="cit-captcha-image" />
                <a href="javascript://" id="cit-captcha-refresh" class="cit-captcha-refresh" title="Refresh">
                    <span class="glyphicon glyphicon-refresh" id="cit-captcha-icon" style="font-size:20px;"></span>
                </a>
            </div>
            <div class="cit-captcha-textbox">
                <input placeholder="Type here" type="text" class="form-control cit-captcha-response" id="cit-captcha-response" name="cit-captcha-response" maxlength="' . $this->length . '" style="width:' . $box_width . 'px;" />
            </div>
        </div>';
            }
        }
    }

    private function _generate($protect = FALSE)
    {
        if (!$protect) {
            if ($this->CI->session->userdata($this->session_key) == "") {
                $protect = FALSE;
            } else {
                $protect = TRUE;
            }
        }
        if ($protect) {
            $this->CI->session->set_userdata($this->session_key, $this->_stringGen());
        }
    }

    private function _getFontPath()
    {
        return $this->CI->config->item('captcha_font');
    }

    private function _getFonts()
    {
        $fonts = array();
        if ($handle = opendir($this->font_path)) {
            while (($file = readdir($handle)) !== FALSE) {
                $extension = strtolower(substr($file, strlen($file) - 3, 3));
                if ($extension == 'ttf') {
                    $fonts[] = $file;
                }
            }
            closedir($handle);
        } else {
            return null;
        }

        if (count($fonts) == 0) {
            return null;
        } else {
            return $fonts;
        }
    }

    private function _getRandFont()
    {
        return $this->font_path . $this->fonts[mt_rand(0, count($this->fonts) - 1)];
    }

    private function _stringGen()
    {
        $results = null;
        $uppercase = range('A', 'Z');
        $numeric = range(0, 9);
        $char_pool = array_merge($uppercase, $numeric);
        if ($this->case) {
            $lowercase = range('a', 'z');
            $char_pool = array_merge($char_pool, $lowercase);
        }
        $pool_length = count($char_pool) - 1;
        for ($i = 0; $i < $this->length; $i++) {
            $results .= $char_pool[mt_rand(0, $pool_length)];
        }

        return $results;
    }

    private function _makeCaptcha()
    {
        $this->_generate(true);
        $captcha_string = $this->CI->session->userdata($this->session_key);

        $imagelength = isset($this->params['length']) && is_numeric($this->params['length']) ? $this->params['length'] * 25 + 16 : $this->length * 25 + 16;
        $imageheight = isset($this->params['height']) && is_numeric($this->params['height']) ? $this->params['height'] : 75;
        //ob_clean();
        $image = imagecreate($imagelength, $imageheight);

        $bgcolor = imagecolorallocate($image, $this->bg_color[0], $this->bg_color[1], $this->bg_color[2]);
        $stringcolor = imagecolorallocate($image, $this->text_color[0], $this->text_color[1], $this->text_color[2]);

        $this->_signs($image, $this->_getRandFont());

        for ($i = 0; $i < strlen($captcha_string); $i++) {
            imagettftext($image, 25, mt_rand(-15, 15), $i * 25 + 10, mt_rand(30, 70), $stringcolor, $this->_getRandFont(), $captcha_string{$i});
        }

        if (isset($this->filters['noise']) && is_numeric($this->filters['noise'])) {
            $this->_noise($image, $this->filters['noise']);
        }

        if (isset($this->filters['blur']) && is_numeric($this->filters['blur'])) {
            $this->_blur($image, $this->filters['blur']);
        }

        $captcha_temp_path = $this->CI->config->item('captcha_temp_path');
        if (!is_dir($captcha_temp_path)) {
            mkdir($captcha_temp_path . $dir_name, 0777, TRUE);
        }
        $img_src = '';
        switch ($this->img_format) {

            case "png" :
                $filename = $captcha_temp_path . $captcha_string . time() . ".png";
                imagepng($image, $filename);
                //$picture = ob_get_clean();
                $fp = fopen($filename, "rb", 0);
                $picture_string = fread($fp, filesize($filename));
                fclose($fp);
                $base64 = chunk_split(base64_encode($picture_string));
                $img_src = 'data:image/png;base64,' . trim($base64);
                break;

            case "jpg" :
                $filename = $captcha_temp_path . $captcha_string . ".jpg";
                imagejpeg($image);
                //$picture = ob_get_clean();
                $fp = fopen($filename, "rb", 0);
                $picture_string = fread($fp, filesize($filename));
                fclose($fp);
                $base64 = chunk_split(base64_encode($picture_string));
                $img_src = 'data:image/jpg;base64,' . trim($base64);
                break;

            case "jpeg" :
                $filename = $captcha_temp_path . $captcha_string . ".jpeg";
                imagejpeg($image);
                //$picture = ob_get_clean();
                $fp = fopen($filename, "rb", 0);
                $picture_string = fread($fp, filesize($filename));
                fclose($fp);
                $base64 = chunk_split(base64_encode($picture_string));
                $img_src = 'data:image/jpg;base64,' . trim($base64);
                break;

            case "gif" :
                $filename = $captcha_temp_path . $captcha_string . ".gif";
                imagegif($image);
                //$picture = ob_get_clean();
                $fp = fopen($filename, "rb", 0);
                $picture_string = fread($fp, filesize($filename));
                fclose($fp);
                $base64 = chunk_split(base64_encode($picture_string));
                $img_src = 'data:image/gif;base64,' . trim($base64);
                break;

            default :
                $filename = $captcha_temp_path . $captcha_string . ".png";
                imagepng($image, $filename);
                //$picture = ob_get_clean();
                $fp = fopen($filename, "rb", 0);
                $picture_string = fread($fp, filesize($filename));
                fclose($fp);
                $base64 = chunk_split(base64_encode($picture_string));
                $img_src = 'data:image/png;base64,' . trim($base64);
                break;
        }
        imagedestroy($image);
        unlink($filename);
        return $img_src;
    }

    private function _noise(&$image, $runs = 30)
    {
        $w = imagesx($image);
        $h = imagesy($image);

        for ($n = 0; $n < $runs; $n++) {
            for ($i = 1; $i <= $h; $i++) {
                $randcolor = imagecolorallocate($image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
                imagesetpixel($image, mt_rand(1, $w), mt_rand(1, $h), $randcolor);
            }
        }
    }

    private function _signs(&$image, $font, $cells = 3)
    {
        $w = imagesx($image);
        $h = imagesy($image);

        for ($i = 0; $i < $cells; $i++) {
            $centerX = mt_rand(1, $w);
            $centerY = mt_rand(1, $h);
            $amount = mt_rand(1, 15);
            $stringcolor = imagecolorallocate($image, 175, 175, 175);
            for ($n = 0; $n < $amount; $n++) {
                $signs = range('A', 'Z');
                $sign = $signs[mt_rand(0, count($signs) - 1)];
                imagettftext($image, 25, mt_rand(-15, 15), $centerX + mt_rand(-50, 50), $centerY + mt_rand(-50, 50), $stringcolor, $font, $sign);
            }
        }
    }

    private function _blur(&$image, $radius = 3)
    {
        $radius = round(max(0, min($radius, 50)) * 2);

        $w = imagesx($image);
        $h = imagesy($image);

        $img_blur = imagecreate($w, $h);
        for ($i = 0; $i < $radius; $i++) {
            imagecopy($img_blur, $image, 0, 0, 1, 1, $w - 1, $h - 1);
            imagecopymerge($img_blur, $image, 1, 1, 0, 0, $w, $h, 50.0000);
            imagecopymerge($img_blur, $image, 0, 1, 1, 0, $w - 1, $h, 33.3333);
            imagecopymerge($img_blur, $image, 1, 0, 0, 1, $w, $h - 1, 25.0000);
            imagecopymerge($img_blur, $image, 0, 0, 1, 0, $w - 1, $h, 33.3333);
            imagecopymerge($img_blur, $image, 1, 0, 0, 0, $w, $h, 25.0000);
            imagecopymerge($img_blur, $image, 0, 0, 0, 1, $w, $h - 1, 20.0000);
            imagecopymerge($img_blur, $image, 0, 1, 0, 0, $w, $h, 16.6667);
            imagecopymerge($img_blur, $image, 0, 0, 0, 0, $w, $h, 50.0000);
            imagecopy($image, $img_blur, 0, 0, 0, 0, $w, $h);
        }
        imagedestroy($img_blur);
    }

    public function _color($color_arr = array())
    {
        if (count($color_arr) == 3) {
            if (is_numeric($color_arr[0]) && is_numeric($color_arr[1]) && is_numeric($color_arr[2])) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function setBGColor($r = 0, $g = 0, $b = 0)
    {
        $this->bg_color = array($r, $g, $b);
    }

    public function setTextColor($r = 0, $g = 0, $b = 0)
    {
        $this->text_color = array($r, $g, $b);
    }

    public function setCaptchType($type = '')
    {
        if ($type == "google") {
            $this->captcha_type = "google";
        } else {
            $this->captcha_type = "session";
        }
    }

    public function valid($type = '')
    {
        try {
            if ($type != "") {
                $this->setCaptchType($type);
            }
            if ($this->captcha_type == "google") {
                $post_value = $this->CI->input->get_post('g-recaptcha-response');
                if (is_null($post_value) || empty($post_value)) {
                    throw new Exception("Captcha not found.");
                }

                $secret = $this->CI->config->item('GOOGLE_CAPTCHA_SECRET_KEY');
                $content = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $post_value);
                $response = json_decode($content, TRUE);
                if (!$response['success']) {
                    throw new Exception("Captcha verification failed.");
                }
            } else {
                $post_value = $this->CI->input->get_post('cit-captcha-response');
                $session_key = $this->CI->session->userdata($this->session_key);
                if (is_null($post_value) || empty($post_value)) {
                    throw new Exception("Captcha not found.");
                }

                if (!$this->case) {
                    $post_value = strtolower($post_value);
                    $session_key = strtolower($session_key);
                }
                if ($post_value != $session_key) {
                    throw new Exception("Captcha verification failed.");
                }
            }
            $success = 1;
            $message = 'Captcha verification done.';
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $response['success'] = $success;
        $response['message'] = $message;

        return $response;
    }
}
