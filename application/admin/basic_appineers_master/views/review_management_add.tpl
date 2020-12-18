<%if $this->input->is_ajax_request()%>
<%$this->js->clean_js()%>
<%/if%>
<div class="module-form-container">
<%include file="review_management_add_strip.tpl"%>
<div class="<%$module_name%>" data-form-name="<%$module_name%>">
<div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
<input type="hidden" id="projmod" name="projmod" value="review_management" />
<!-- Page Loader -->
<div id="ajax_qLoverlay"></div>
<div id="ajax_qLbar"></div>
<!-- Module Tabs & Top Detail View -->
<div class="top-frm-tab-layout" id="top_frm_tab_layout">
</div>
<!-- Middle Content -->
<div id="scrollable_content" class="scrollable-content popup-content top-block-spacing ">
<div id="review_management" class="frm-module-block frm-elem-block frm-stand-view">
<!-- Module Form Block -->
<form name="frmaddupdate" id="frmaddupdate" action="<%$admin_url%><%$mod_enc_url['add_action']%>?<%$extra_qstr%>" method="post"  enctype="multipart/form-data">
<!-- Form Hidden Fields Unit -->
<input type="hidden" id="id" name="id" value="<%$enc_id%>" />
<input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>" />
<input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>" />
<input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>" />
<input type="hidden" id="draft_uniq_id" name="draft_uniq_id" value="<%$draft_uniq_id%>" />
<input type="hidden" id="extra_hstr" name="extra_hstr" value="<%$extra_hstr%>" />
<input type="hidden" name="r_latitude" id="r_latitude" value="<%$data['r_latitude']|@htmlentities%>"  class='ignore-valid ' />
<input type="hidden" name="r_longitude" id="r_longitude" value="<%$data['r_longitude']|@htmlentities%>"  class='ignore-valid ' />
<input type="hidden" name="r_added_at" id="r_added_at" value="<%$this->general->dateSystemFormat($data['r_added_at'])%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date_and_time', 'dateFormat')%>'  aria-format-type='date_and_time' />
<input type="hidden" name="r_updated_at" id="r_updated_at" value="<%$this->general->dateSystemFormat($data['r_updated_at'])%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date_and_time', 'dateFormat')%>'  aria-format-type='date_and_time' />
<!-- Form Dispaly Fields Unit -->
<div class="main-content-block " id="main_content_block">
<div style="width:98%" class="frm-block-layout pad-calc-container">
<div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
<div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('REVIEW_MANAGEMENT_REVIEW_MANAGEMENT')%></h4></div>
<div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
<div class="form-row row-fluid " id="cc_sh_r_profile_image">
<label class="form-label span3 ">
<%$form_config['r_profile_image']['label_lang']%>
</label> 
<div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
<%if $mode eq "Add"%>
    <div  class='btn-uploadify frm-size-medium' >
        <input type="hidden" value="<%$data['r_profile_image']%>" name="old_r_profile_image" id="old_r_profile_image" />
        <input type="hidden" value="<%$data['r_profile_image']%>" name="r_profile_image" id="r_profile_image"  aria-extensions="gif,png,jpg,jpeg,jpe,bmp,ico" aria-valid-size="<%$this->lang->line('GENERIC_LESS_THAN')%> (<) 100 MB"/>
        <input type="hidden" value="<%$data['r_profile_image']%>" name="temp_r_profile_image" id="temp_r_profile_image"  />
        <div id="upload_drop_zone_r_profile_image" class="upload-drop-zone"></div>
        <div class="uploader upload-src-zone">
            <input type="file" name="uploadify_r_profile_image" id="uploadify_r_profile_image" title="<%$this->lang->line('REVIEW_MANAGEMENT_PROFILE_IMAGE')%>" />
            <span class="filename" id="preview_r_profile_image">
                <%if $data['r_profile_image'] neq ''%>
                    <%$data['r_profile_image']%>
                <%else%>
                    <%$this->lang->line('GENERIC_DROP_FILES_HERE_OR_CLICK_TO_UPLOAD')%>
                <%/if%>
            </span>
            <span class="action">Choose File</span>
        </div>
    </div>
<%else%>    
    <input type="hidden" value="<%$data['r_profile_image']%>" name="r_profile_image" id="r_profile_image"  />
<%/if%>
<div class='upload-image-btn'>
    <%$img_html['r_profile_image']%>
</div>
<span class="input-comment">
    <a href="javascript://" style="text-decoration: none;" class="tipR" title="<%$this->lang->line('GENERIC_VALID_EXTENSIONS')%> : gif, png, jpg, jpeg, jpe, bmp, ico.<br><%$this->lang->line('GENERIC_VALID_SIZE')%> : <%$this->lang->line('GENERIC_LESS_THAN')%> (<) 100 MB."><span class="icomoon-icon-help"></span></a>
</span>
<div class='clear upload-progress' id='progress_r_profile_image'>
    <div class='upload-progress-bar progress progress-striped active'>
        <div class='bar' id='practive_r_profile_image'></div>
    </div>
    <div class='upload-cancel-div'><a class='upload-cancel' href='javascript://'>Cancel</a></div>
    <div class='clear'></div>
</div>
<div class='clear'></div>
</div>
<div class="error-msg-form "><label class='error' id='r_profile_imageErr'></label></div>
</div>
<div class="form-row row-fluid " id="cc_sh_r_first_name">
<label class="form-label span3 ">
<%$form_config['r_first_name']['label_lang']%>
</label> 
<div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
<%if $mode eq "Update"%>
    <input type="hidden" class="ignore-valid" name="r_first_name" id="r_first_name" value="<%$data['r_first_name']|@htmlentities%>" />
    <span class="frm-data-label">
        <strong>
            <%if $data['r_first_name'] neq ""%>
                <%$data['r_first_name']%>
            <%else%>
            <%/if%>
        </strong></span>
    <%else%>
        <input type="text" placeholder="" value="<%$data['r_first_name']|@htmlentities%>" name="r_first_name" id="r_first_name" title="<%$this->lang->line('REVIEW_MANAGEMENT_FIRST_NAME')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
    <%/if%>
</div>
<div class="error-msg-form "><label class='error' id='r_first_nameErr'></label></div>
</div>
<div class="form-row row-fluid " id="cc_sh_r_last_name">
<label class="form-label span3 ">
    <%$form_config['r_last_name']['label_lang']%>
</label> 
<div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
    <%if $mode eq "Update"%>
        <input type="hidden" class="ignore-valid" name="r_last_name" id="r_last_name" value="<%$data['r_last_name']|@htmlentities%>" />
        <span class="frm-data-label">
            <strong>
                <%if $data['r_last_name'] neq ""%>
                    <%$data['r_last_name']%>
                <%else%>
                <%/if%>
            </strong></span>
        <%else%>
            <input type="text" placeholder="" value="<%$data['r_last_name']|@htmlentities%>" name="r_last_name" id="r_last_name" title="<%$this->lang->line('REVIEW_MANAGEMENT_LAST_NAME')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
        <%/if%>
    </div>
    <div class="error-msg-form "><label class='error' id='r_last_nameErr'></label></div>
</div>

    <div class="form-row row-fluid " id="cc_sh_r_email">
        <label class="form-label span3 ">
            <%$form_config['r_email']['label_lang']%>
        </label> 
        <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
            <%if $mode eq "Update"%>
                <input type="hidden" class="ignore-valid" name="r_email" id="r_email" value="<%$data['r_email']|@htmlentities%>" />
                <span class="frm-data-label">
                    <strong>
                        <%if $data['r_email'] neq ""%>
                            <%$data['r_email']%>
                        <%else%>
                        <%/if%>
                    </strong></span>
                <%else%>
                    <input type="text" placeholder="" value="<%$data['r_email']|@htmlentities%>" name="r_email" id="r_email" title="<%$this->lang->line('REVIEW_MANAGEMENT_EMAIL')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                <%/if%>
            </div>
            <div class="error-msg-form "><label class='error' id='r_emailErr'></label></div>
        </div>
        <div class="form-row row-fluid " id="cc_sh_r_mobile_no">
            <label class="form-label span3 ">
                <%$form_config['r_mobile_no']['label_lang']%>
            </label> 
            <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                <%if $mode eq "Update"%>
                    <input type="hidden" class="ignore-valid" name="r_mobile_no" id="r_mobile_no" value="<%$data['r_mobile_no']|@htmlentities%>" />
                    <span class="frm-data-label">
                        <strong>
                            <%if $data['r_mobile_no'] neq ""%>
                                <%$data['r_mobile_no']%>
                            <%else%>
                            <%/if%>
                        </strong></span>
                    <%else%>
                        <input type="text" placeholder="" value="<%$data['r_mobile_no']|@htmlentities%>" name="r_mobile_no" id="r_mobile_no" title="<%$this->lang->line('REVIEW_MANAGEMENT_MOBILE_NUMBER')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                    <%/if%>
                </div>
                <div class="error-msg-form "><label class='error' id='r_mobile_noErr'></label></div>
            </div>
            <div class="form-row row-fluid " id="cc_sh_r_address">
                    <label class="form-label span3 ">
                        <%$form_config['r_address']['label_lang']%> <em>*</em> 
                    </label> 
                    <div class="form-right-div  ">
                        <span>
                            <div>
                                <div class="frm-gmf-address-label">
                                    <span id="gmf_addr_label_r_address"><%$data['r_address']%></span>
                                    <!-- <textarea style="display:none;" class="ignore-valid" name="r_address" id="r_address"><%$data['r_address']%></textarea> -->
                                </div>
                                <span  class='frm-gmf-medium frm-gmf-height-tiny' >
                                    <!-- <textarea class="frm-gmf-address elastic" name="gmf_autocomplete_r_address" id="gmf_autocomplete_r_address" title="<%$this->lang->line('REVIEW_MANAGEMENT_ADDRESS')%>"><%$data['r_address']%></textarea> -->
                                </span>
                            </div>
                            <div class="frm-gmf-options">
                                <input type="radio" name="type_r_address" id="r_address-changetype-all" class="regular-radio" checked=true />
                                <label for="r_address-changetype-all">&nbsp;</label>
                                <label for="r_address-changetype-all">
                                    <%if $this->lang->line("GENERIC_ALL") neq ""%>
                                        <%$this->lang->line("GENERIC_ALL")%>
                                    <%else%>
                                        All
                                    <%/if%>
                                </label>&nbsp;&nbsp;
                                <input type="radio" name="type_r_address" id="r_address-changetype-establishment" class="regular-radio" />
                                <label for="r_address-changetype-establishment">&nbsp;</label>
                                <label for="r_address-changetype-establishment">
                                    <%if $this->lang->line("GENERIC_ESTABLISHMENTS") neq ""%>
                                        <%$this->lang->line("GENERIC_ESTABLISHMENTS")%>
                                    <%else%>
                                        Establishments
                                    <%/if%>
                                </label>&nbsp;&nbsp;
                                <input type="radio" name="type_r_address" id="r_address-changetype-geocode" class="regular-radio" />
                                <label for="r_address-changetype-geocode">&nbsp;</label>
                                <label for="r_address-changetype-geocode">
                                    <%if $this->lang->line("GENERIC_GEOCODES") neq ""%>
                                        <%$this->lang->line("GENERIC_GEOCODES")%>
                                    <%else%>
                                        Geocodes
                                    <%/if%>
                                </label>
                            </div>
                            <!-- <span class='canvas_map'><div id='map_canvas_r_address'  class='frm-gmf-medium frm-gmf-height-tiny' ></div></span> -->
                        </span>
                        <%assign var=temp_map_arr value=[['name'=>'r_address','lat'=>'r_latitude','lng'=>'r_longitude','load'=>'No']]%>
                        <%if $google_map_arr|@is_array%>
                            <%assign var=google_map_arr value=$google_map_arr|@array_merge:$temp_map_arr%>
                        <%else%>
                            <%assign var=google_map_arr value=$temp_map_arr%>
                        <%/if%>
                    </div>
                    <div class="error-msg-form "><label class='error' id='r_addressErr'></label></div>
                </div>
                <div class="form-row row-fluid " id="cc_sh_r_city">
                    <label class="form-label span3 ">
                        <%$form_config['r_city']['label_lang']%>
                    </label> 
                    <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                        <%if $mode eq "Update"%>
                            <input type="hidden" class="ignore-valid" name="r_city" id="r_city" value="<%$data['r_city']|@htmlentities%>" />
                            <span class="frm-data-label">
                                <strong>
                                    <%if $data['r_city'] neq ""%>
                                        <%$data['r_city']%>
                                    <%else%>
                                    <%/if%>
                                </strong></span>
                            <%else%>
                                <input type="text" placeholder="" value="<%$data['r_city']|@htmlentities%>" name="r_city" id="r_city" title="<%$this->lang->line('REVIEW_MANAGEMENT_CITY')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                            <%/if%>
                        </div>
                        <div class="error-msg-form "><label class='error' id='r_cityErr'></label></div>
                    </div>
                    <div class="form-row row-fluid " id="cc_sh_r_state_id">
                        <label class="form-label span3 ">
                            <%$form_config['r_state_id']['label_lang']%>
                        </label> 
                        <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                            <%assign var="opt_selected" value=$data['r_state_id']%>
                            <%if $mode eq "Update"%>
                                <input type="hidden" name="r_state_id" id="r_state_id" value="<%$data['r_state_id']%>" class="ignore-valid"/>
                                <%assign var="combo_arr" value=$opt_arr["r_state_id"]%>
                                <%assign var="opt_display" value=$this->general->displayKeyValueData($opt_selected, $combo_arr)%>
                                <span class="frm-data-label">
                                    <strong>
                                        <%if $opt_display neq ""%>
                                            <%$opt_display%>
                                        <%else%>
                                        <%/if%>
                                    </strong></span>
                                <%else%>
                                    <%$this->dropdown->display("r_state_id","r_state_id","  title='<%$this->lang->line('REVIEW_MANAGEMENT_STATE')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'REVIEW_MANAGEMENT_STATE')%>'  ", "|||", "", $opt_selected,"r_state_id")%>
                                <%/if%>
                            </div>
                            <div class="error-msg-form "><label class='error' id='r_state_idErr'></label></div>
                        </div>
                        <div class="form-row row-fluid " id="cc_sh_r_zip_code">
                            <label class="form-label span3 ">
                                <%$form_config['r_zip_code']['label_lang']%>
                            </label> 
                            <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                                <%if $mode eq "Update"%>
                                    <input type="hidden" class="ignore-valid" name="r_zip_code" id="r_zip_code" value="<%$data['r_zip_code']|@htmlentities%>" />
                                    <span class="frm-data-label">
                                        <strong>
                                            <%if $data['r_zip_code'] neq ""%>
                                                <%$data['r_zip_code']%>
                                            <%else%>
                                            <%/if%>
                                        </strong></span>
                                    <%else%>
                                        <input type="text" placeholder="" value="<%$data['r_zip_code']|@htmlentities%>" name="r_zip_code" id="r_zip_code" title="<%$this->lang->line('REVIEW_MANAGEMENT_ZIP_CODE')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                                    <%/if%>
                                </div>
                                <div class="error-msg-form "><label class='error' id='r_zip_codeErr'></label></div>
                            </div>
            <div class="form-row row-fluid " id="cc_sh_r_star">
            <label class="form-label span3 ">
                <%$form_config['r_star']['label_lang']%>
            </label> 
            <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                <%if $mode eq "Update"%>
                    <input type="hidden" class="ignore-valid" name="r_star" id="r_star" value="<%$data['r_star']|@htmlentities%>" />
                    <span class="frm-data-label">
                        <strong>
                            <%if $data['r_star'] neq ""%>
                                <%$data['r_star']%>
                            <%else%>
                            <%/if%>
                        </strong></span>
                <%else%>
                    <input type="text" placeholder="" value="<%$data['r_star']|@htmlentities%>" name="r_star" id="r_star" title="<%$this->lang->line('REVIEW_MANAGEMENT_STAR')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                <%/if%>
            </div>
             <div class="error-msg-form "><label class='error' id='r_starErr'></label></div>
            </div>
            
            <div class="form-row row-fluid " id="cc_sh_r_description">
            <label class="form-label span3 ">
                <%$form_config['r_description']['label_lang']%>
            </label> 
            <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                <%if $mode eq "Update"%>
                    <input type="hidden" class="ignore-valid" name="r_description" id="r_description" value="<%$data['r_description']|@htmlentities%>" />
                    <span class="frm-data-label">
                        <strong>
                            <%if $data['r_description'] neq ""%>
                                <%$data['r_description']%>
                            <%else%>
                            <%/if%>
                        </strong></span>
                <%else%>
                    <input type="text" placeholder="" value="<%$data['r_description']|@htmlentities%>" name="r_description" id="r_description" title="<%$this->lang->line('REVIEW_MANAGEMENT_DESCRIPTION')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                <%/if%>
            </div>
            <div class="error-msg-form "><label class='error' id='r_descriptionErr'></label></div>
            </div>
            
            
            <div class="form-row row-fluid " id="cc_sh_r_review_type">
            <label class="form-label span3 ">
                <%$form_config['r_review_type']['label_lang']%>
            </label> 
            <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                <%if $mode eq "Update"%>
                    <input type="hidden" class="ignore-valid" name="r_review_type" id="r_review_type" value="<%$data['r_review_type']|@htmlentities%>" />
                    <span class="frm-data-label">
                        <strong>
                            <%if $data['r_review_type'] neq ""%>
                                <%$data['r_review_type']%>
                            <%else%>
                            <%/if%>
                        </strong></span>
                <%else%>
                    <input type="text" placeholder="" value="<%$data['r_review_type']|@htmlentities%>" name="r_review_type" id="r_review_type" title="<%$this->lang->line('REVIEW_MANAGEMENT_REVIEW_TYPE')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                <%/if%>
            </div>
            <div class="error-msg-form "><label class='error' id='r_review_typeErr'></label></div>
            </div>

            
            <div class="form-row row-fluid " id="cc_sh_r_business_name">
            <label class="form-label span3 ">
                <%$form_config['r_business_name']['label_lang']%>
            </label> 
            <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                <%if $mode eq "Update"%>
                    <input type="hidden" class="ignore-valid" name="r_business_name" id="r_business_name" value="<%$data['r_business_name']|@htmlentities%>" />
                    <span class="frm-data-label">
                        <strong>
                            <%if $data['r_business_name'] neq ""%>
                                <%$data['r_business_name']%>
                            <%else%>
                            <%/if%>
                        </strong></span>
                <%else%>
                    <input type="text" placeholder="" value="<%$data['r_business_name']|@htmlentities%>" name="r_business_name" id="r_business_name" title="<%$this->lang->line('REVIEW_MANAGEMENT_BUSINESS_NAME')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                <%/if%>
            </div>
            <div class="error-msg-form "><label class='error' id='r_business_nameErr'></label></div>
            </div>


            <div class="form-row row-fluid " id="cc_sh_r_business_type">
            <label class="form-label span3 ">
                <%$form_config['r_business_type']['label_lang']%>
            </label> 
            <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                <%if $mode eq "Update"%>
                    <input type="hidden" class="ignore-valid" name="r_business_type" id="r_business_type" value="<%$data['r_business_type']|@htmlentities%>" />
                    <span class="frm-data-label">
                        <strong>
                            <%if $data['r_business_type'] neq ""%>
                                <%$data['r_business_type']%>
                            <%else%>
                            <%/if%>
                        </strong></span>
                <%else%>
                    <input type="text" placeholder="" value="<%$data['r_business_type']|@htmlentities%>" name="r_business_type" id="r_business_type" title="<%$this->lang->line('REVIEW_MANAGEMENT_BUSINESS_TYPE')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                <%/if%>
            </div>
            <div class="error-msg-form "><label class='error' id='r_business_typeErr'></label></div>
            </div>


            <div class="form-row row-fluid " id="cc_sh_r_position">
            <label class="form-label span3 ">
                <%$form_config['r_position']['label_lang']%>
            </label> 
            <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                <%if $mode eq "Update"%>
                    <input type="hidden" class="ignore-valid" name="r_position" id="r_position" value="<%$data['r_position']|@htmlentities%>" />
                    <span class="frm-data-label">
                        <strong>
                            <%if $data['r_position'] neq ""%>
                                <%$data['r_position']%>
                            <%else%>
                            <%/if%>
                        </strong></span>
                <%else%>
                    <input type="text" placeholder="" value="<%$data['r_position']|@htmlentities%>" name="r_position" id="r_position" title="<%$this->lang->line('REVIEW_MANAGEMENT_POSITION')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                <%/if%>
            </div>
            <div class="error-msg-form "><label class='error' id='r_positionErr'></label></div>
            </div>

            <div class="form-row row-fluid " id="cc_sh_r_claimed_email">
            <label class="form-label span3 ">
                <%$form_config['r_claimed_email']['label_lang']%>
            </label> 
            <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                <%if $mode eq "Update"%>
                    <input type="hidden" class="ignore-valid" name="r_claimed_email" id="r_claimed_email" value="<%$data['r_claimed_email']|@htmlentities%>" />
                    <span class="frm-data-label">
                        <strong>
                            <%if $data['r_claimed_email'] neq ""%>
                                <%$data['r_claimed_email']%>
                            <%else%>
                            <%/if%>
                        </strong></span>
                <%else%>
                    <input type="text" placeholder="" value="<%$data['r_claimed_email']|@htmlentities%>" name="r_claimed_email" id="r_claimed_email" title="<%$this->lang->line('REVIEW_MANAGEMENT_CLAIMED_EMAIL')%>"  data-ctrl-type='textbox'  class='frm-size-medium'  />
                <%/if%>
            </div>
            <div class="error-msg-form "><label class='error' id='r_claimed_emailErr'></label></div>
            </div>



                            <div class="form-row row-fluid " id="cc_sh_r_deleted_at">
                                        <label class="form-label span3 ">
                                            <%$form_config['r_deleted_at']['label_lang']%>
                                        </label> 
                                        <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%else%>input-append text-append-prepend<%/if%> ">
                                            <%if $mode eq "Update"%>
                                                <input type="hidden" name="r_deleted_at" id="r_deleted_at" value="<%$this->general->dateSystemFormat($data['r_deleted_at'])%>" class="ignore-valid view-label-only"  data-ctrl-type='date'  class='frm-datepicker ctrl-append-prepend frm-size-medium'  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date' />
                                                <%assign var="display_date" value=$this->general->dateSystemFormat($data['r_deleted_at'])%>
                                                <span class="frm-data-label">
                                                    <strong>
                                                        <%if $display_date neq ""%>
                                                            <%$display_date%>
                                                        <%else%>
                                                        <%/if%>
                                                    </strong></span>
                                                <%else%>
                                                    <input type="text" value="<%$this->general->dateSystemFormat($data['r_deleted_at'])%>" placeholder="" name="r_deleted_at" id="r_deleted_at" title="<%$this->lang->line('REVIEW_MANAGEMENT_DELETED_AT')%>"  data-ctrl-type='date'  class='frm-datepicker ctrl-append-prepend frm-size-medium'  aria-date-format='<%$this->general->getAdminJSFormats('date', 'dateFormat')%>'  aria-format-type='date'  />
                                                    <span class='add-on text-addon date-append-class icomoon-icon-calendar'></span>
                                                <%/if%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='r_deleted_atErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid " id="cc_sh_r_status">
                                            <label class="form-label span3 ">
                                                <%$form_config['r_status']['label_lang']%> <em>*</em> 
                                            </label> 
                                            <div class="form-right-div  ">
                                                <%assign var="opt_selected" value=$data['r_status']%>
                                                <%$this->dropdown->display("r_status","r_status","  title='<%$this->lang->line('REVIEW_MANAGEMENT_STATUS')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'REVIEW_MANAGEMENT_STATUS')%>'  ", "|||", "", $opt_selected,"r_status")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='r_statusErr'></label></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="frm-bot-btn <%$rl_theme_arr['frm_stand_action_bar']%> <%$rl_theme_arr['frm_stand_action_btn']%> popup-footer">
                                <%if $rl_theme_arr['frm_stand_ctrls_view'] eq 'No'%>
                                    <%assign var='rm_ctrl_directions' value=true%>
                                <%/if%>
                                <%include file="review_management_add_buttons.tpl"%>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Module Form Javascript -->
<%javascript%>

var el_form_settings = {}, elements_uni_arr = {}, child_rules_arr = {}, google_map_json = {}, pre_cond_code_arr = [];
el_form_settings['module_name'] = '<%$module_name%>'; 
el_form_settings['extra_hstr'] = '<%$extra_hstr%>';
el_form_settings['extra_qstr'] = '<%$extra_qstr%>';
el_form_settings['upload_form_file_url'] = admin_url+"<%$mod_enc_url['upload_form_file']%>?<%$extra_qstr%>";
el_form_settings['get_chosen_auto_complete_url'] = admin_url+"<%$mod_enc_url['get_chosen_auto_complete']%>?<%$extra_qstr%>";
el_form_settings['token_auto_complete_url'] = admin_url+"<%$mod_enc_url['get_token_auto_complete']%>?<%$extra_qstr%>";
el_form_settings['tab_wise_block_url'] = admin_url+"<%$mod_enc_url['get_tab_wise_block']%>?<%$extra_qstr%>";
el_form_settings['parent_source_options_url'] = "<%$mod_enc_url['parent_source_options']%>?<%$extra_qstr%>";
el_form_settings['jself_switchto_url'] =  admin_url+'<%$switch_cit["url"]%>';
el_form_settings['callbacks'] = [];

google_map_json = $.parseJSON('<%$google_map_arr|@json_encode%>');
child_rules_arr = {};

<%if $auto_arr|@is_array && $auto_arr|@count gt 0%>
setTimeout(function(){
<%foreach name=i from=$auto_arr item=v key=k%>
if($("#<%$k%>").is("select")){
$("#<%$k%>").ajaxChosen({
dataType: "json",
type: "POST",
url: el_form_settings.get_chosen_auto_complete_url+"&unique_name=<%$k%>&mode=<%$mod_enc_mode[$mode]%>&id=<%$enc_id%>"
},{
loadingImg: admin_image_url+"chosen-loading.gif"
});
}
<%/foreach%>
}, 500);
<%/if%>        
el_form_settings['jajax_submit_func'] = '';
el_form_settings['jajax_submit_back'] = '';
el_form_settings['jajax_action_url'] = '<%$admin_url%><%$mod_enc_url["add_action"]%>?<%$extra_qstr%>';
el_form_settings['save_as_draft'] = 'No';
el_form_settings['buttons_arr'] = [];
el_form_settings['message_arr'] = {
"delete_message" : "<%$this->general->processMessageLabel('ACTION_ARE_YOU_SURE_WANT_TO_DELETE_THIS_RECORD_C63')%>",
};

callSwitchToSelf();
<%/javascript%>
<%$this->js->add_js('admin/review_management_add_js.js')%>

<%$this->js->add_js("admin/custom/reviewManagement.js")%>
<%if $this->input->is_ajax_request()%>
<%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
<%$this->css->css_src()%>
<%/if%> 
<%javascript%>
Project.modules.review_management.callEvents();
<%/javascript%>