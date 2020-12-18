<div id="google_translate_element" class="google-translate-element"></div>
<style>
    body{top:0px!important;min-height:auto!important;}
    body > .skiptranslate{position:fixed;top:0px!important;}
    .translate-box-top-menu{float:left;padding-top:10px;}
    .goog-te-gadget img{display:none;}
    .goog-te-gadget-simple{border:0;background-color:#01bbe4;font-size:14px;position:relative;top:8px;}
    .goog-te-gadget-simple .goog-te-menu-value{color:#fff;text-decoration:none;}
    .goog-te-gadget-simple .goog-te-menu-value span{border-left:0!important;color:#fff!important;margin-left:2px;}
</style>
<script type='text/javascript'>
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            //pageLanguage: 'en', 
            includedLanguages: "<%$this->config->item('PAGE_TRANSLATION_LANGUAGES')%>", 
            autoDisplay: false,
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
        }, 'google_translate_element');
    }
</script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
