<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Flowplayer {

    protected $CI;
    var $videoPlayer;
    var $thumbnailPATH;
    var $urlData = array();
    var $playerPATH;
    var $controlPATH;
    var $htmlplayerPATH;
    var $playerdirPATH;
    var $flashvideo = "";
    var $flashaudio = "";
    var $playerClass;
    var $pWidth = 600;
    var $pHeight = 336;
    var $audioPlayer;
    var $audioplayerPATH;
    var $htmlvideo = "";
    var $htmlPlayer;
    var $video_format = array(
        "mp4" => "video/mp4",
        "webm" => "video/webm",
        "ogv" => "video/ogg",
        "m3u8" => "application/x-mpegurl",
        "ts" => "video/mp2t",
        "flv" => "video/flash"
    );

    function __construct() {
        $this->CI = & get_instance();

        //$this->thumbnailPATH = site_url() . "application/cache/captcha/";
        //   $this->CI->load->library('js');
        //  $this->CI->js->add_js('flowplayer/flowplayer-3.2.12.min.js');
//        $this->playerdirPATH = site_url() . 'public/js/flowplayer/';
//        $this->playerPATH = $this->playerdirPATH . 'flowplayer-3.2.16.swf';
//        $this->controlPATH = $this->playerdirPATH . 'flowplayer.controls-3.2.15.swf';
//        $this->htmlplayerPATH = $this->playerdirPATH . 'flowplayerhtml5/flowplayer.swf';
//        $this->audioplayerPATH = $this->playerdirPATH . 'flowplayer.audio-3.2.10.swf';
    }

    public function setPath($path = "") {
        $this->playerdirPATH = $path;
        $this->playerPATH = $this->playerdirPATH . 'flowplayer-3.2.16.swf';
        $this->controlPATH = $this->playerdirPATH . 'flowplayer.controls-3.2.15.swf';
        $this->htmlplayerPATH = $this->playerdirPATH . 'flowplayerhtml5/flowplayer.swf';
        $this->audioplayerPATH = $this->playerdirPATH . 'flowplayer.audio-3.2.10.swf';
    }

//http://pseudo01.hddn.com/vod/demo.flowplayervod/flowplayer-700.flv
//  <%$this->js->add_js("flowplayer/flowplayerhtml5/flowplayer.min.js" )%> 
//        <%$this->css->add_css("flowplayer/skin/minimalist.css")%>    
    
    public function setUrlData($urlData = array()) {
        $this->urlData = $urlData;
        $this->playerClass = $this->urlData['playerclass'];

        if (isset($this->urlData['media_urls']) && is_array($this->urlData['media_urls'])) {
            $this->setFlashMedia($this->urlData['media_urls']);
        }

        if (isset($this->urlData['audio_urls']) && is_array($this->urlData['audio_urls'])) {
            $this->setFlashAudio($this->urlData['audio_urls']);
        }

        if (isset($this->urlData['html_urls']) && is_array($this->urlData['html_urls'])) {
            $this->setHTMLMedia($this->urlData['html_urls']);
        }

        $this->pWidth = isset($this->urlData['width']) ? $this->urlData['width'] : $this->pWidth;
        $this->pHeight = isset($this->urlData['height']) ? $this->urlData['height'] : $this->pHeight;
    }

    public function getFlashVideoplayer() {

        //  $this->videoPlayer = $this->flashvideo;
        foreach ($this->urlData['media_urls'] as $key => $value) {

            if ((!isset($this->urlData['media_urls'][$key]['controls']['url']) && is_array($this->urlData['media_urls'][$key]['controls']))) {
                $this->urlData['media_urls'][$key]['controls']['url'] = $this->controlPATH;
            }
        }
        $flash_arr = array(
            'media_arr' => (isset($this->urlData['media_urls']) && is_array($this->urlData['media_urls'])) ? $this->urlData['media_urls'] : array(),
            'pWidth' => $this->pWidth,
            'pHeight' => $this->pHeight,
            'playerPATH' => $this->playerPATH,
            'controlPATH' => $this->controlPATH
        );
        //$this->CI->smarty->fetch();
        // echo ''.json_encode($this->urlData['media_urls'][0]['controls']);exit;
        return $this->CI->parser->parse("libraries/flashflowplayer.tpl", $flash_arr, true);



        //  echo $this->videoPlayer;
        //  exit;
    }

    public function getHTMLFlowplayer() {

        $this->htmlPlayer = $this->htmlvideo;

        $html_arr = array(
            'html_arr' => (isset($this->urlData['html_urls']) && is_array($this->urlData['html_urls'])) ? $this->urlData['html_urls'] : array(),
            'pWidth' => $this->pWidth,
            'pHeight' => $this->pHeight,
            'htmlplayerPATH' => $this->htmlplayerPATH,
            'controlPATH' => $this->controlPATH,
            'playerClass' => $this->playerClass
        );

        return $this->CI->parser->parse("libraries/htmlflowplayer.tpl", $html_arr, true);
    }

    function getFlowplayerAudio() {

        foreach ($this->urlData['audio_urls'] as $key => $value) {

            if ((!isset($this->urlData['audio_urls'][$key]['controls']['url']) && is_array($this->urlData['audio_urls'][$key]['controls']))) {
                $this->urlData['audio_urls'][$key]['controls']['url'] = $this->controlPATH;
            }
            $this->urlData['audio_urls'][$key]['controls']['fullscreen'] = false;
            $this->urlData['audio_urls'][$key]['controls']['autoHide'] = false;

            $this->urlData['audio_urls'][$key]['controls']['height'] = 30;
            $this->urlData['audio_urls'][$key]['clip']['provider'] = "audio";
        }

        //  pr($this->urlData['audio_urls']);exit;

        $audio_arr = array(
            'audio_arr' => (isset($this->urlData['audio_urls']) && is_array($this->urlData['audio_urls'])) ? $this->urlData['audio_urls'] : array(),
            'pWidth' => $this->pWidth,
            'pHeight' => $this->pHeight,
            'playerPATH' => $this->playerPATH,
            'audioplayerPATH' => $this->audioplayerPATH,
            'controlPATH' => $this->controlPATH
        );
        //$this->CI->smarty->fetch();
        return $this->CI->parser->parse("libraries/audioflowplayer.tpl", $audio_arr, true);
    }

    public function setFlashMedia($media_arr = array()) {
        $anchors = "";
        $i = 0;
        if (isset($media_arr) && is_array($media_arr)) {
            foreach ($media_arr as $row) {
                // $anchors.='<a class="' . $row['playerclass'] . '"
                //        href="' . $row['mediaurl'] . '"></a>';

                $anchors.=' <a href="' . $row['mainurl'] . '" style=" display:block;width: ' . $this->pWidth . 'px;height:' . $this->pHeight . 'px;text-align:center;
                    margin:15px;float:left;border-radius:5px; border:5px solid #999;"  
                            id="hbflash' . $i . '"> 
                              
		</a>
                <script>
			$f("hbflash' . $i . '", "' . $this->playerPATH . '",{
                   plugins: {
                  volume: true,
                  controls: {
                     playlist: ' . (count($row['playlist']) > 1 ? "true" : "false") . ',
        // location of the controlbar plugin
                url:"' . $this->controlPATH . '",
               timeColor: "#980118",
                opacity: 0.95,
                tooltips: { 
                buttons: true
                            }
              
                }
                   },
                    
                   playlist: ' . (isset($row['playlist']) ? json_encode($row['playlist']) : "[]") . '
            ,  

                   clip: {
                   autoPlay: ' . (isset($row['autoplay']) ? $row['autoplay'] : "false") . ',
                   autoBuffering: ' . (isset($row['autobuffer']) ? $row['autoplay'] : "false") . '
                   }
                   

                });
		</script>

                ';
                $i++;
            }

            $this->flashvideo = $anchors;
        }
    }

    public function setFlashAudio($audio_arr = array()) {
        $anchors = "";
        $i = 0;
        if (isset($audio_arr) && is_array($audio_arr)) {
            foreach ($audio_arr as $row) {
                $anchors.=' <a id="hbaudio' . $i . '" style="display:block;width:648px;height:30px;"
               href="' . $row['mainurl'] . '"></a>
              <script> 
              $f("hbaudio' . $i . '", "' . $this->playerPATH . '", {
 
                // fullscreen button not needed here
                plugins: {
                 audio: {
            url: "' . $this->audioplayerPATH . '"
                },
                    controls: {
                        url:"' . $this->controlPATH . '",
                        playlist: ' . (count($row['playlist']) > 0 ? "true" : "false") . ',
                        fullscreen: false,
                        height: 30,
                        autoHide: false,
                        tooltips: { 
                           buttons: true
                            }
 
                        
                    }
                },
                playlist: ' . (isset($row['playlist']) ? json_encode($row['playlist']) : "[]") . ',
                clip: {
                    autoPlay: ' . (isset($row['autoplay']) ? $row['autoplay'] : "false") . ',
                   autoBuffering: ' . (isset($row['autobuffer']) ? $row['autoplay'] : "false") . ',
                  
                    // optional: when playback starts close the first audio playback
                    onBeforeBegin: function() {
                        $f("hbaudio' . $i . '").close();
                    }

                }

            });

              </script><br>';


                $i++;
            }
            $this->flashaudio = $anchors;
        }
    }

    public function setHTMLMedia($html_arr = array()) {
        $players = "";
        $i = 0;
        if (isset($html_arr) && is_array($html_arr)) {
            foreach ($html_arr as $row) {

                $type = $this->getFormatType($row['mainurl']);
//style="width:' . $this->pWidth . 'px;height:' . $this->pHeight . 'px;"
                $players.='<div   data-ratio="0.5625" data-engine="flash" style="width:' . $this->pWidth . 'px;height:' . $this->pHeight . 'px;" class="' . $this->playerClass . '">
                            <video>
                                <source type="' . $type . '" src="' . $row['mainurl'] . '">
                            </video>';
                if (isset($row['playlist']) && is_array($row['playlist'])) {
                    $players.='<a class="fp-prev" >PREV</a>
                                <a class="fp-next">NEXT</a>
                                <div class="fp-playlist">';
                    foreach ($row['playlist'] as $key => $value) {
                        $players.='<a href="' . $value . '"></a>';
                    }
                    $players.=' </div>';
                }
                $players.=' </div>';
                $players.='<script>
                
                $(function () {

                   
                   $(".' . $this->playerClass . '").flowplayer({ swf: "' . $this->htmlplayerPATH . '" });
                  
                });
                </script>';
            }

            $this->htmlvideo = $players;
        }
    }

    public function getFormatType($url = "") {
        $type = "";
        $mainurl = $url;
        $length = strlen($mainurl);
        //   echo '<br>Length '.$length;
        //     echo '<br>Main URL '.$mainurl;
        $pos = strrpos($mainurl, '.');
        //     echo '<br>Post '.$pos;
        if ($pos >= 0 && $length > 0) {
            $exe = substr($mainurl, $pos + 1, $length);
            $type = $this->video_format[$exe];
            //  echo '<br>exe '.$exe;
        }
        //     echo '<br>Video Type '.$type;
        return $type;
    }

    public function getThumnails($videoUrl = "", $width = 0, $height = 0, $token = "") {
        if ($width === 0 || $height === 0) {
            $width = $this->pWidth;
            $height = $this->pHeight;
        }
        $md5name = md5($videoUrl);
        $filename = $token . $md5name . ".png";
        $filepath = $this->CI->config->item('captcha_temp_path') . $filename;

        $command = "ffmpeg  -ss 2  -i " . $videoUrl . " -vcodec png -vframes 1 -an -f rawvideo -s " . $width . "x" . $height . " " . $filepath;

        if (!is_file($filepath)) {
            exec($command);
        }
        //unlink($command);
        $temppath = $this->thumbnailPATH . $filename;

        return $temppath;
    }

}

?>