<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Fancybox
{

    protected $CI = '';
    protected $js = '';
    protected $tag = 'a';
    protected $tagclass = '';
    protected $imageclass = '';

    public function __construct($params = null)
    {
        $this->CI = & get_instance();
        $this->activate();
    }

    public function activate()
    {
        $this->CI->js->add_js('libraries/fancybox/jquery.mousewheel-3.0.6.pack.js');
        $this->CI->js->add_js('libraries/fancybox/jquery.fancybox.js');
        $this->CI->js->add_js('libraries/fancybox/jquery.fancybox-buttons.js');
        $this->CI->js->add_js('libraries/fancybox/jquery.fancybox-thumbs.js');
        $this->CI->js->add_js('libraries/fancybox/jquery.fancybox-media.js');
        $this->CI->js->add_js('libraries/fancybox/javascript.js');
        $this->CI->css->add_css('libraries/fancybox/jquery.fancybox.css');
        $this->CI->css->add_css('libraries/fancybox/jquery.fancybox-buttons.css');
        $this->CI->css->add_css('libraries/fancybox/jquery.fancybox-thumbs.css');
        $this->CI->css->add_css('libraries/fancybox/jquery.fancybox-media.css');
        $this->CI->css->add_css('libraries/fancybox/mystle.css');
    }

    public function imagegallary($imgarr, $params = null)
    {
        if ($params['tag'] != "")
            $this->tag = $params['tag'];
        if ($params['tagclass'] != null)
            $this->tagclass .= $params['tagclass'];
        if ($params['imageclass'] != "")
            $this->imageclass .= $params['imageclass'];
        if ($params['group'] == null)
            $params['group'] = "gallery";
        if ($this->tag != "a" && $params['tag'] != "li")
            $this->tag = "a";
        $render_arr = array(
            'imgarr' => $imgarr,
            'tagclass' => $this->tagclass,
            'imageclass' => $this->imageclass,
            'class' => $params['group'],
            'group' => $params['group']
        );
        return $this->CI->parser->parse("libraries/" . $this->tag . ".tpl", $render_arr, true);
    }

    public function media($urls, $id, $params = null)
    {
        $extraparam = $this->generateParamsString($params);
        $this->js .= '$("#' . $id . '")
				.attr("rel", "media-gallery")
				.fancybox({
					openEffect : "fade",
					closeEffect : "fade",
					href:"' . $urls . '",
					' . $extraparam . '	
					prevEffect : "none",
					nextEffect : "none",
					arrows : false,
					autoDimensions: false,
					helpers : {
						media : {},
						buttons : {}
					},
                                        
				});';
    }

    public function mediagallary($urls, $params = null)
    {
        $extraparam = $this->generateParamsString($params);
        $this->js .= "$('.fancybox-media')
				.attr('rel', 'media-gallery')
				.fancybox({
					openEffect : 'fade',
					closeEffect : 'fade',
					prevEffect : 'none',
					nextEffect : 'none',
					arrows : false,
					autoDimensions: false,
					" . $extraparam . "
					helpers : {
						media : {},
						buttons : {}
					}
				});";
        $render_arr = array(
            'params' => $urls,
            'class' => $params['tagclass']
        );
        return $this->CI->parser->parse("libraries/media.tpl", $render_arr, true);
    }

    public function iframes($url, $id, $params = null)
    {
        $extraparam = $this->generateParamsString($params);
        $this->js .= '$("#' . $id . '").fancybox({"autoDimensions":"false",
                                "href":"' . $url . '",
                                 ' . $extraparam . '  
                                "autoSize":"false",
                                "closeBtn":"true",
                                "type":"iframe"});';
    }

    public function ajax($url, $id, $params = null)
    {
        $extraparam = $this->generateParamsString($params);
        $this->js .= "$('#$id').fancybox({
			autoDimensions:false,
			$extraparam
			href:'$url',	
			autoSize : false,
			closeBtn  : true,
			type:'ajax'
		});";
    }

    public function inline($id, $idd, $params = null)
    {

        $extraparam = $this->generateParamsString($params);
        $this->js .= "$('#$id').fancybox({
			autoDimensions:false,
			$extraparam
			href:'#$idd',	
			autoSize : false,
			closeBtn  : true,
		});";
    }

    public function manual($id, $url)
    {
        $this->js .= "$('#$id').fancybox({
			href:'$url'	
		});";
    }

    public function getjs()
    {
        return $this->js;
    }

    public function generateParamsString($params_string)
    {
        $return_params_string = "";
        if (count($params_string) > 0) {
            foreach ($params_string as $key => $value) {
                if (is_numeric($value) || is_bool($value)) {
                    $return_params_string .= "'" . $key . "':" . $value . ",";
                } else {
                    $return_params_string .= "'" . $key . "':'" . $value . "',";
                }
            }
        }
        return $return_params_string;
    }
}
