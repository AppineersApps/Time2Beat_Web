<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Tinymce
{

    protected $CI;
    var $tinyconfig_arr;
    var $skin = "bootstrap";
    var $selector = "textarea";
    var $theme = "advanced";
    var $disabled_buttons = array(
        "bold",
        "italic",
        "underline",
        "strikethrough",
        "justifyleft",
        "justifycenter",
        "justifyright",
        "justifyfull",
        "bullist",
        "numlist",
        "outdent",
        "indent",
        "cut",
        "copy",
        "paste",
        "undo",
        "redo",
        "link",
        "unlink",
        "image",
        "cleanup",
        "help",
        "code",
        "hr",
        "removeformat",
        "fontselect",
        "fontsizeselect",
        "sub",
        "sup",
        "forecolor",
        "backcolor",
        "forecolorpicker",
        "backcolorpicker",
        "charmap",
        "visualaid",
        "anchor",
        "newdocument",
        "blockquote",
        "separator"
    );
    var $general_setting = array();
    var $plugin_setting = array();
    var $enabled_buttons = array();
    var $output_setting = array();

    public function __construct()
    {

        $this->CI = &get_instance();
    }
    /*

      $this->general_setting=array(
      "accessibility_warnings" =>  ,
      "auto_focus" =>  ,
      "browsers" =>  ,
      "class_filter" =>  ,
      "custom_shortcuts" =>  ,
      "dialog_type" =>  ,
      "directionality" =>  ,
      "editor_deselector" =>  ,
      "editor_selector" =>  ,
      "elements" =>  ,
      "gecko_spellcheck" =>  ,
      "keep_styles" =>  ,
      "language" =>  ,
      "mode" =>  ,
      "nowrap" =>  ,
      "object_resizing" =>  ,
      "plugins" =>  ,
      "readonly" =>  ,
      "selector" =>  ,
      "skin" =>  ,
      "skin_variant" =>  ,
      "table_inline_editing" =>  ,
      "theme" =>  ,
      "imagemanager_contextmenu" =>
      );

      $url_setting=array(
      "convert_urls"=> ,
      "relative_urls"=> ,
      "remove_script_host"=> ,
      "document_base_url"=>
      );

      $layout_setting=array(
      "body_id" =>  ,
      "body_class" =>  ,
      "constrain_menus" =>  ,
      "content_css" =>  ,
      "popup_css" =>  ,
      "popup_css_add" =>  ,
      "editor_css" =>  ,
      "width" =>  ,
      "height" =>
      );


      $output_setting=array(
      "apply_source_formatting"  =>  ,
      "convert_fonts_to_spans"  =>  ,
      "convert_newlines_to_brs"  =>  ,
      "custom_elements"  =>  ,
      "doctype"  =>  ,
      "element_format"  =>  ,
      "encoding"  =>  ,
      "entities"  =>  ,
      "entity_encoding"  =>  ,
      "extended_valid_elements"  =>  ,
      "fix_list_elements"  =>  ,
      "font_size_classes"  =>  ,
      "font_size_style_values"  =>  ,
      "force_p_newlines"  =>  ,
      "force_br_newlines"  =>  ,
      "force_hex_style_colors"  =>  ,
      "forced_root_block"  =>  ,
      "formats"  =>  ,
      "indentation"  =>  ,
      "inline_styles"  =>  ,
      "invalid_elements"  =>  ,
      "remove_linebreaks"  =>  ,
      "preformatted"  =>  ,
      "protect"  =>  ,
      "schema"  =>  ,
      "style_formats"  =>  ,
      "valid_children"  =>  ,
      "valid_elements"  =>  ,
      "verify_css_classes"  =>  ,
      "verify_html"  =>  ,
      "removeformat_selector"  =>
      );

      $trigger_setting=array(
      "add_form_submit_trigger" => "true" ,
      "add_unload_trigger" => "true" ,
      "submit_patch" => "true"
      );

      $advanced_setting=array(
      "theme_advanced_layout_manager" => "SimpleLayout|RowLayout|CustomLayout"   ,
      "theme_advanced_blockformats" =>   "p,address,pre,h1,h2,h3,h4,h5,h6"  ,
      "theme_advanced_styles" =>    ,
      "theme_advanced_source_editor_width" => 500  ,
      "theme_advanced_source_editor_height" => 400   ,
      "theme_advanced_source_editor_wrap" =>  "true"  ,
      "theme_advanced_toolbar_location" =>  "top|bottom|external"  ,
      "theme_advanced_toolbar_align" =>  "left|right|center"  ,
      "theme_advanced_statusbar_location" =>   "bottom|top|none"  ,
      "theme_advanced_disable" =>  "bold,italic"  ,
      "theme_advanced_resizing" =>   "true" ,
      "theme_advanced_resizing_min_width" =>  320  ,
      "theme_advanced_resizing_min_height" =>   240 ,
      "theme_advanced_resizing_max_width" =>   350 ,
      "theme_advanced_resizing_max_height" =>   240 ,
      "theme_advanced_resizing_use_cookie" =>   "true" ,
      "theme_advanced_resize_horizontal" =>  "true"  ,
      "theme_advanced_path" =>   "false"

      );

      $disabled_buttons=array(
      "bold",
      "italic",
      "underline",
      "strikethrough",
      "justifyleft",
      "justifycenter",
      "justifyright",
      "justifyfull",
      "bullist",
      "numlist",
      "outdent",
      "indent",
      "cut",
      "copy",
      "paste",
      "undo",
      "redo",
      "link",
      "unlink",
      "image",
      "cleanup",
      "help",
      "code",
      "hr",
      "removeformat",
      "fontselect",
      "fontsizeselect",

      "sub",
      "sup",
      "forecolor",
      "backcolor",
      "forecolorpicker",
      "backcolorpicker",
      "charmap",
      "visualaid",
      "anchor",
      "newdocument",
      "blockquote",
      "separator"
      );



      $flip_arr=array_flip($default_buttons);
      foreach ($flip_arr as $key => $value) {
      $flip_arr[$key]=$key;
      }

     */

    public function setTinyMCEConfig($tinymce_setting = array())
    {
        if (is_array($tinymce_setting)) {

            switch ($tinymce_setting['theme']) {
                case "simple":
                    $this->general_setting = array(
                        "selector" => "textarea",
                        "theme" => $this->theme,
                        "skin" => $this->theme == "advanced" ? $this->skin : 'default',
                        "add_form_submit_trigger" => true,
                        "theme_advanced_toolbar_align" => "left",
                        "theme_advanced_statusbar_location" => "bottom",
                        "theme_advanced_resizing" => false,
                        "theme_advanced_buttons1" => "bold,italic,underline,strikethrough,bullist,numlist,undo,redo,code",
                        "theme_advanced_buttons2" => "",
                        "theme_advanced_buttons3" => ""
                    );
                    $this->enabled_buttons = array("bold", "italic", "underline",
                        "strikethrough", "undo", "redo", "bullist", "numlist");
                    if (isset($tinymce_setting['extrabutton'])) {
                        $this->general_setting['theme_advanced_buttons1'] .= "," . $tinymce_setting['extrabutton'];
                        $temp_btn = explode(',', $tinymce_setting['extrabutton']);
                        foreach ($temp_btn as $button) {
                            array_push($this->enabled_buttons, $button);
                        }
                    }
                    //  isset($tinymce_setting['extrabutton'])?array_push($this->enabled_buttons,$tinymce_setting['extrabutton']):'';
                    isset($tinymce_setting['extraparam']) ? array_push($this->general_setting, $tinymce_setting['extraparam']) : '';
                    break;
                case "advanced":
                    $this->general_setting = array(
                        "selector" => "textarea",
                        "theme" => $this->theme,
                        "skin" => $this->theme == "advanced" ? $this->skin : 'default',
                        "add_form_submit_trigger" => true,
                        "theme_advanced_toolbar_align" => "left",
                        "theme_advanced_statusbar_location" => "bottom",
                        "theme_advanced_resizing" => false,
                        "plugins" => "paste",
                        "theme_advanced_buttons1" => "bold,italic,underline,strikethrough,|,formatselect,fontselect,fontsizeselect",
                        "theme_advanced_buttons2" => "undo,redo,|,link,unlink,anchor,image,|,cut,copy,paste,pastetext,pasteword,|,code,|,forecolor,backcolor",
                        "theme_advanced_buttons3" => ""
                    );
                    $this->enabled_buttons = array("bold", "italic", "underline", "forecolor", "backcolor",
                        "forecolorpicker", "backcolorpicker", "strikethrough", "cut", "copy", "fontselect", "fontsizeselect", "image",
                        "link", "anchor", "unlink", "undo", "code", "redo", "bullist", "numlist");
                    if (isset($tinymce_setting['extrabutton'])) {
                        $this->general_setting['theme_advanced_buttons3'] .= "," . $tinymce_setting['extrabutton'];
                        $temp_btn = explode(',', $tinymce_setting['extrabutton']);
                        foreach ($temp_btn as $button) {
                            array_push($this->enabled_buttons, $button);
                        }
                    }
                    isset($tinymce_setting['extraparam']) ? array_push($this->general_setting, $tinymce_setting['extraparam']) : '';



                    break;
                case "all":
                    $this->general_setting = array(
                        "selector" => "textarea",
                        "theme" => $this->theme,
                        "skin" => $this->theme == "advanced" ? $this->skin : 'default',
                        "plugins" => "autolink,lists,pagebreak,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",
                        "theme_advanced_buttons1" => "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect,|,bullist,numlist,|,outdent,indent,blockquote,|,search,replace",
                        "theme_advanced_buttons2" => "cut,copy,paste,pastetext,pasteword,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor,visualchars,nonbreaking,template,pagebreak,|,sub,sup,|,charmap,emotions,iespell",
                        "theme_advanced_buttons3" => "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,media,advhr,|,print,|,ltr,rtl,|,fullscreen,|,insertlayer,moveforward,movebackward,absolute,|,del,ins",
                        "theme_advanced_buttons4" => "styleprops,|,cite,abbr,acronym,attribs,|,restoredraft,visualblocks",
                        "theme_advanced_resizing" => "true",
                        "theme_advanced_resizing_use_cookie" => "true"
                    );
                    $this->enabled_buttons = array(
                        "bold", "italic", "underline", "strikethrough", "justifyleft", "justifycenter", "justifyright",
                        "justifyfull", "bullist", "numlist", "outdent", "indent", "cut", "copy", "paste", "undo", "redo",
                        "link", "unlink", "image", "cleanup", "help", "code", "hr", "removeformat", "fontselect", "fontsizeselect",
                        "sub", "sup", "forecolor", "backcolor", "forecolorpicker", "backcolorpicker", "charmap",
                        "visualaid", "anchor", "newdocument", "blockquote", "separator");
                    isset($tinymce_setting['extrabutton']) ? array_push($this->enabled_buttons, $tinymce_setting['extrabutton']) : '';
                    isset($tinymce_setting['extraparam']) ? array_push($this->general_setting, $tinymce_setting['extraparam']) : '';
                    break;
                case "ribbon": $this->general_setting = array(
                        "selector" => "textarea",
                        "theme" => "ribbon",
                        "skin" => 'cirkuit',
                        " plugins " => "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",
                        "theme_advanced_buttons1" => "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,	",
                        "theme_advanced_buttons2" => "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                        "theme_advanced_buttons3" => "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
                        "theme_advanced_buttons4" => "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
                        "theme_advanced_resizing" => "true",
                        "theme_advanced_resizing_use_cookie" => "true"
                    );
                    $this->enabled_buttons = array(
                        "bold", "italic", "underline", "strikethrough", "justifyleft", "justifycenter", "justifyright",
                        "justifyfull", "bullist", "numlist", "outdent", "indent", "cut", "copy", "paste", "undo", "redo",
                        "link", "unlink", "image", "cleanup", "help", "code", "hr", "removeformat", "fontselect", "fontsizeselect",
                        "sub", "sup", "forecolor", "backcolor", "forecolorpicker", "backcolorpicker", "charmap",
                        "visualaid", "anchor", "newdocument", "blockquote", "separator");
                    break;
                default :
                    $this->general_setting = array(
                        "selector" => "textarea",
                        "theme" => $this->theme,
                        "skin" => 'cirkuit'
                    );
                    $this->enabled_buttons = array(
                        "bold", "italic", "underline", "strikethrough", "justifyleft", "justifycenter", "justifyright",
                        "justifyfull", "bullist", "numlist", "outdent", "indent", "cut", "copy", "paste", "undo", "redo",
                        "link", "unlink", "image", "cleanup", "help", "code", "hr", "removeformat", "fontselect", "fontsizeselect",
                        "sub", "sup", "forecolor", "backcolor", "forecolorpicker", "backcolorpicker", "charmap",
                        "visualaid", "anchor", "newdocument", "blockquote", "separator");
                    isset($tinymce_setting['extrabutton']) ? array_push($this->enabled_buttons, $tinymce_setting['extrabutton']) : '';
                    isset($tinymce_setting['extraparam']) ? array_push($this->general_setting, $tinymce_setting['extraparam']) : '';
                    break;
            }
        }



        /*        $trigger_setting = array(
          "add_form_submit_trigger" => true,
          "add_unload_trigger" => true,
          "submit_patch" => true
          );


          /*      $advanced_setting = array(
          "theme_advanced_layout_manager" => "SimpleLayout",
          "theme_advanced_source_editor_wrap" => true,
          "theme_advanced_toolbar_location" => "top",
          "theme_advanced_toolbar_align" => "center",
          "theme_advanced_statusbar_location" => "bottom",
          "theme_advanced_resizing" => false,
          "theme_advanced_resizing_min_width" => 320,
          "theme_advanced_resizing_min_height" => 240,
          "theme_advanced_resizing_max_height" => 240,
          "theme_advanced_resizing_use_cookie" => true,
          "theme_advanced_resize_horizontal" => true,
          );

          $this->enabled_buttons = array(
          "bold",
          "italic",
          "underline",
          "cut",
          "copy",
          "paste"
          );

         */
        $tinyconfig_arr = array(
            'general_setting' => isset($this->general_setting) && is_array($this->general_setting) ? $this->general_setting : array(),
            'trigger_setting' => isset($trigger_setting) && is_array($trigger_setting) ? $trigger_setting : array(),
            'advanced_setting' => isset($advanced_setting) && is_array($advanced_setting) ? $advanced_setting : array(),
            'enabled_buttons' => isset($this->enabled_buttons) && is_array($this->enabled_buttons) ? $this->enabled_buttons : array(),
            'url_setting' => isset($url_setting) && is_array($url_setting) ? $url_setting : array(),
            'layout_setting' => isset($layout_setting) && is_array($layout_setting) ? $layout_setting : array(),
            'output_setting' => isset($output_setting) && is_array($output_setting) ? $output_setting : array()
        );

        $this->tinyconfig_arr = $tinyconfig_arr;
    }

    public function createtiny()
    {

        //$baseJs = $this->CI->config->item('base_url') . '/js';
        //'disabled_buttons' => isset($disabled_buttons) && is_array($disabled_buttons)?json_encode($disabled_buttons):''    
        //$this->general_setting=array(),$url_setting=array(),$layout_setting=array(),$output_setting=array(),$trigger_setting=array(),$advanced_setting=array(),$disabled_buttons=array()        

        extract($this->tinyconfig_arr);

        $this->disabled_buttons = $this->getKeyValueArr($this->disabled_buttons);

        $enabtn_flip_arr = $this->getKeyValueArr($this->enabled_buttons);
        $this->setEnableButtons($enabtn_flip_arr);

        $advanced_disable_arr = array(
            "theme_advanced_disable" => $this->getCommaStr($this->disabled_buttons)
        );

        $tinymce_config = array_merge($this->general_setting, $url_setting, $layout_setting, $output_setting, $trigger_setting, $advanced_setting, $advanced_disable_arr);

        $tinyjson = json_encode($tinymce_config);

        $tinyconfig_arr = array(
            'tinyjson' => $tinyjson
        );

        return $this->CI->parser->parse("libraries/tinymce.tpl", $tinyconfig_arr, true);
    }

    public function Textarea($FullCode = FALSE, $Method = "POST", $Action = '')
    {
        if ($FullCode === TRUE) {
            $mce = "<form action=\"$Action\" method=\"$Method\"></form>";
            $mce .= "<textarea name=\"TinyMCE\" cols=\"30\" rows=\"50\"></textarea>";
            $mce .= "<input name=\"Submit\" type=\"button\" value=\"Submit\">";
            $mce .= "</form>";
            return $mce; // Outputs to view file - String
        } else {
            $mce = "<textarea name=\"TinyMCE\" cols=\"30\" rows=\"50\"></textarea>";
            return $mce; // Outputs to view file - String
        }
    }

    public function getCommaStr($temp = array())
    {
        $commaStr = is_array($temp) ? implode(",", $temp) : "";

        return $commaStr;
    }

    public function getKeyValueArr($temp = array())
    {
        $flip_arr = array_flip($temp);
        foreach ($flip_arr as $key => $value) {
            $flip_arr[$key] = $key;
        }

        return $flip_arr;
    }

    public function setEnableButtons($enable_btn = array())
    {

        foreach ($enable_btn as $key => $value) {
            unset($this->disabled_buttons[$key]);
        }
    }
}