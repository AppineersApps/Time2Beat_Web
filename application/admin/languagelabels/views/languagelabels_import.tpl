<%if $this->input->is_ajax_request()%>
<%$this->js->clean_js()%>
<%/if%>
<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <h3>
            <div class="screen-title">
                <%$this->lang->line('GENERIC_IMPORT_DATA')%> :: <%$this->lang->line('LANGUAGELABELS_LANGUAGE_LABELS')%>
            </div>
        </h3>
        <div class="header-right-btns">
            <%if $backlink_allow eq true%>
            <div class="frm-back-to">
                <a hijacked="yes" href="<%$admin_url%>#<%$mod_enc_url['index']%><%$extra_hstr%>"class="backlisting-link" title="<%$this->lang->line('LANGUAGELABELS_BACK_TO_LANGUAGE_LABELS_LISTING')%>">
                    <span class="icon16 minia-icon-arrow-left"></span>
                </a>
            </div>
            <%/if%>
            <div class="clear"></div>
        </div>
        <span style="display: none;position:inherit;" id="ajax_lang_loader">
            <img src="<%$this->config->item('admin_images_url')%>loaders/circular/020.gif">
        </span>
    </div>
</div>
<div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
    <input type="hidden" id="projmod" name="projmod" value="languagelabels">
    <div id="ajax_qLoverlay"></div>
    <div id="ajax_qLbar"></div>
    <div class="top-frm-tab-layout" id="top_frm_tab_layout"></div>
    <div id="scrollable_content" class="scrollable-content top-block-spacing">
        <div id="languagelabels" class="frm-elem-block frm-stand-view"> 
            <form name="frmlblimport" id="frmlblimport" action="<%$admin_url%>languagelabels/languagelabels/importAction" method="post">
                <input type="hidden" id="extra_hstr" name="extra_hstr" value="<%$extra_hstr%>">
                <div class="main-content-block" id="main_content_block">
                    <div style="width:98%" class = "frm-block-layout pad-calc-container">
                        <div class="box gradient <%$rl_theme_arr['frm_gener_content_row']%> <%$rl_theme_arr['frm_gener_border_view']%>">
                            <div class="title <%$rl_theme_arr['frm_gener_titles_bar']%>"><h4><%$this->lang->line('LANGUAGELABELS_LANGUAGE_LABELS')%></h4></div>
                            <div class="content <%$rl_theme_arr['frm_gener_label_align']%>">
                                <div class="form-row row-fluid" id="cc_sh_mllt_label">
                                    <label class="form-label span3"><%$this->lang->line('LANGUAGELABELS_LANGUAGE_LABEL')%> <em>*</em> </label> 
                                    <div class="form-right-div  frm-elements-div ">
                                        <div>
                                            <div class="btn-uploadify frm-size-small">
                                                <input type='hidden' value='' name='importfile' id='importfile' />
                                                <input type='hidden' value='' name='old_importfile' id='old_importfile' />
                                                <input type='hidden' value='' name='temp_importfile' id='temp_importfile' />
                                                <div id="upload_drop_zone_importfile" class="upload-drop-zone"></div>
                                                <div class="uploader upload-src-zone">
                                                    <input type='file' name='uploadify_importfile' id='uploadify_importfile' title='' />
                                                    <span class="filename" id="preview_importfile"></span>
                                                    <span class="action">Choose File</span>
                                                </div>
                                            </div>
                                            <div class="upload-image-btn">
                                                <div id="img_buttons_importfile" class="img-inline-display">
                                                    <div id="img_view_importfile" class="img-view-section">
                                                    </div>
                                                    <div id="img_del_importfile" class="img-del-section"></div>
                                                </div>
                                            </div>
                                            <div class="clear upload-progress" id="progress_importfile">
                                                <div class="upload-progress-bar progress progress-striped active">
                                                    <div class="bar" id="practive_importfile"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='importfileErr'></label></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="action-btn-align" id="action_btn_container">
                        <input value="Upload" name="ctrlupload" type="button" id="frmbtn_upload" class='btn btn-info'/>
                        <input value="<%$this->lang->line('GENERIC_DISCARD')%>" name="ctrldiscard" type="button" id="frmbtn_discard" class='btn' onclick="return loadAdminModuleListing('languagelabels/languagelabels/index', '<%$extra_hstr%>')">
                    </div>
                </div>
                <div class="clear"></div>
            </form>
        </div>
    </div>
</div>
<%$this->js->add_js("admin/admin/js_languagelabels_import.js")%>
<%javascript%>
var $upload_form_file = admin_url+'<%$upload_url%>';
<%/javascript%>
<%if $this->input->is_ajax_request()%>
<%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
<%$this->css->css_src()%>
<%/if%>