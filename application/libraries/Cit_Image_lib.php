<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Extended Image Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module General
 * 
 * @class Cit_Image_lib.php
 * 
 * @path application\libraries\Cit_Image_lib.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Cit_Image_lib extends CI_Image_lib
{

    private $_bgcolor;
    private $_fwidth;
    private $_fheight;

    public function __construct()
    {
        parent::__construct();
    }

    public function initialize($props = array())
    {
        parent::initialize($props);

        $this->_bgcolor = $props['bgcolor'];

        $this->_fwidth = $props['width'];

        $this->_fheight = $props['height'];

        $this->_gravity = $props['gravity'];
    }

    public function image_process_imagemagick($action = 'resize')
    {
        // Do we have a vaild library path?
//        if ($this->library_path === '') {
//            $this->set_error('imglib_libpath_invalid');
//            return FALSE;
//        }

        if ($this->library_path != '' && !preg_match('/convert$/i', $this->library_path)) {
            $this->library_path = rtrim($this->library_path, '/') . '/convert';
        } else {
            $this->library_path = 'convert';
        }

        // Execute the command
        $cmd = $this->library_path . ' -quality ' . $this->quality;

        if ($action === 'crop') {
            //$cmd .= ' -crop ' . $this->width . 'x' . $this->height . '+' . $this->x_axis . '+' . $this->y_axis . ' "' . $this->full_src_path . '" "' . $this->full_dst_path . '" 2>&1';
            $cmd .= ' -gravity ' . $this->_gravity . ' -crop ' . $this->width . 'x' . $this->height . '+' . $this->x_axis . '+' . $this->y_axis . ' "' . $this->full_src_path . '" "' . $this->full_dst_path . '" 2>&1';
        } elseif ($action === 'rotate') {
            $angle = ($this->rotation_angle === 'hor' || $this->rotation_angle === 'vrt') ? '-flop' : '-rotate ' . $this->rotation_angle;

            $cmd .= ' ' . $angle . ' "' . $this->full_src_path . '" "' . $this->full_dst_path . '" 2>&1';
        } else { // Resize
//            if ($this->maintain_ratio === TRUE) {
//                $cmd .= ' -resize ' . $this->width . 'x' . $this->height . ' "' . $this->full_src_path . '" "' . $this->full_dst_path . '" 2>&1';
//            } else {
//                $cmd .= ' -resize ' . $this->width . 'x' . $this->height . '\! "' . $this->full_src_path . '" "' . $this->full_dst_path . '" 2>&1';
//            }
            $bgcolor = '';
            if ($this->_bgcolor != "") {
                if ($this->_bgcolor == 'transparent') {
                    $bgcolor = '-background transparent';
                } else {
                    $bgcolor = '-background "#' . $this->_bgcolor . '"';
                }
            }
            $extent_opt = '-compose Copy -gravity ' . $this->_gravity . ' -extent ' . $this->_fwidth . 'x' . $this->_fheight;
            if ($this->maintain_ratio === TRUE) {
                $cmd .= ' -resize ' . $this->width . 'x' . $this->height . ' ' . $bgcolor . '  ' . $extent_opt . ' "' . $this->full_src_path . '" "' . $this->full_dst_path . '" 2>&1';
            } else {
                $cmd .= ' -resize ' . $this->width . 'x' . $this->height . '\! ' . $bgcolor . ' ' . $extent_opt . ' "' . $this->full_src_path . '" "' . $this->full_dst_path . '" 2>&1';
            }
        }

        $retval = 1;
        // exec() might be disabled
        if (function_usable('exec')) {
            exec($cmd, $output, $retval);
        }

        // Did it work?
        if ($retval > 0) {
            $this->set_error('imglib_image_process_failed');
            return FALSE;
        }

        chmod($this->full_dst_path, $this->file_permissions);

        return TRUE;
    }

    public function convet_jpg_png($src = '', $dest = '', $path = '')
    {
        // Do we have a vaild library path?
//        if ($path === '') {
//            $this->set_error('imglib_libpath_invalid');
//            return FALSE;
//        }
        
        if ($path != '' && !preg_match('/convert$/i', $path)) {
            $path = rtrim($path, '/') . '/convert';
        } else {
            $path = 'convert';
        }

        // Execute the command
        $cmd = $path . " -transparent white " . $src . "  " . $dest;

        $retval = 1;
        if (function_usable('exec')) {
            exec($cmd, $output, $retval);
        }

        // Did it work?
        if ($retval > 0) {
            $this->set_error('imglib_image_process_failed');
            return FALSE;
        }

        return TRUE;
    }
}

/* End of file Cit_Image_lib.php */
/* Location: ./application/libraries/Cit_Image_lib.php */