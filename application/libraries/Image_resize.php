<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Image Resize Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module ImageResize
 * 
 * @class Image_resize.php
 * 
 * @path application\libraries\Image_resize.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
include_once config_item('third_party') . "Imagemagick.php";

class Image_resize
{

    protected $CI;
    protected $dest_folder = 'cache/temp';
    protected $picture = '';
    protected $resize_width = '';
    protected $resize_height = '';
    protected $bg_color = ''; //FFFFFF

    /**
     * Constructor
     *
     * @param   string
     * @return  void
     */

    public function __construct($props = array())
    {
        if (count($props) > 0) {
            $this->initialize($props);
        }

        log_message('debug', "Image Resize Class Initialized");
    }

    /**
     * initialize image preferences
     *
     * @access  public
     * @param   array
     * @return  bool
     */
    public function initialize($props = array())
    {
        $this->CI = & get_instance();
        $image_path = APPPATH . $this->dest_folder . '/';
        $this->CI->general->createFolder($image_path);
        /*
         * Convert array elements into class variables
         */
        if (count($props) > 0) {
            foreach ($props as $key => $val) {
                $this->$key = $val;
            }
        }

        if ($this->picture) {
            $pic = trim($this->picture);
            $pic = base64_decode($pic);
            $pic = str_replace(" ", "%20", $pic);
            $url = $pic;
            $url = str_replace(" ", "%20", $url);

            $md5_url = md5($url . serialize($props));
            $filename_path = $image_path . $md5_url;

            $size = getimagesize($pic);
            $ext = end(explode("/", $size['mime']));
            if (!in_array($ext, $this->config->item('IMAGE_EXTENSION_ARR'))) {
                exit;
            }
            if ($ext == 'jpeg') {
                $ext = 'jpg';
            }

            if (is_file($filename_path . "." . $ext)) {
                $this->setHeader($filename_path . "." . $ext);
                echo readfile($filename_path . "." . $ext);
                exit;
            }

            $image = file_get_contents($url);

            $handle = fopen($filename_path . ".cache", 'w+');
            fwrite($handle, $image);
            fclose($handle);

            $size = getimagesize($filename_path . ".cache");
            $newfile = $filename_path . "." . $ext;
            rename($filename_path . ".cache", $filename_path . "." . $ext);

            $imagemagickinstalldir = $this->CI->config->item('imagemagickinstalldir');
            $allowimageprocess = $this->CI->config->item('allowimageprocess');
            $target_dir = $temp_gallery = $image_path;

            /* image magic code */
            $imObj = new ImageMagick($imagemagickinstalldir, $temp_gallery);
            $imObj->loadByFilePath($newfile);
            $imObj->setVerbose(FALSE);
            $imObj->setTargetdir($target_dir);
            list($o_wd, $o_ht) = $imObj->GetSize();

            if (!$this->resize_width)
                $width = $o_wd;
            else
                $width = $this->resize_width;

            if (!$this->resize_height)
                $height = $o_ht;
            else
                $height = $this->resize_height;

            if ($o_wd > $o_ht) {
                $o_ht = ($width * $o_ht) / $o_wd;
                if ($o_ht > $height) {
                    $o_wd = ($width * $height) / $o_ht;
                    $o_ht = $height;
                } else {
                    $o_wd = $width;
                }
            } else {
                $o_wd = ($height * $o_wd) / $o_ht;
                if ($o_wd > $width) {
                    $o_ht = ($height * $width) / $o_wd;
                    $o_wd = $width;
                } else {
                    $o_ht = $height;
                }
            }

            $imObj->Resize($o_wd, $o_ht, 'fit');

            $src_x = (int) ($width - $o_wd) / 2;
            $src_y = (int) ($height - $o_ht) / 2;
            if ($this->bg_color != '') {
                $imObj->FrameCustom($src_x, $src_y, $this->bg_color);
            } else {
                $imObj->FrameCustom($src_x, $src_y, "eff6fb");
            }

            $filename = $imObj->Save();

            $imObj->CleanUp();

            $resizedimage = $target_dir . $filename;

            $this->setHeader($resizedimage);

            echo readfile($resizedimage);

            exit;
        }
    }

    public function setHeader($imagepath)
    {
        if (ob_get_length() > 0) {
            ob_end_clean();
        }
        ob_start();
        $imagepath = str_replace(" ", "%20", $imagepath);
        $imgInfo = getimagesize($imagepath);
        if ($imgInfo[2] == 1) {
            header('Content-Type: image/gif');
        } elseif ($imgInfo[2] == 2) {
            header('Content-Type: image/jpg');
        } elseif ($imgInfo[2] == 3) {
            header('Content-Type: image/png');
        } else {
            header('Content-Type: application/octet-stream');
        }
        $timestamp = filemtime($imagepath);
        $gmt_mtime = gmdate('r', $timestamp);
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
            if ($_SERVER['HTTP_IF_MODIFIED_SINCE'] == $gmt_mtime || str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == md5($timestamp . $imagepath)) {
                header('HTTP/1.1 304 Not Modified');
                exit;
            }
        }
        header_remove('Pragma');
        header("Access-Control-Allow-Origin: *");
        header('ETag: "' . md5($timestamp . $imagepath) . '"');
        header('Last-Modified: ' . $gmt_mtime);
        header('Cache-Control: max-age=2592000, public');
        header("Content-Type: " . $mm_type);
        header("Content-Length: " . filesize($imagepath));
    }
}

/* End of file Image_resize.php */
/* Location: ./application/libraries/Image_resize.php */