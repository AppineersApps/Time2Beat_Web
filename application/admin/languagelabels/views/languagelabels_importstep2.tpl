<%if $this->input->is_ajax_request()%>
<%$this->js->clean_js()%>
<%/if%>
<style type="text/css">
    .cs-valid{background-color: #E2FAC4;}
    .cs-exist{background-color: #FDE0E0;}
    .cs-modified{background-color: #E0E6FD;}
    .cs-valid td:nth-child(1), .cs-exist td:nth-child(1), .cs-modified td:nth-child(1){width:50%; word-break:break-all;}
    .cs-valid td:nth-child(2), .cs-exist td:nth-child(2), .cs-modified td:nth-child(2){width:30%;}
    .cs-valid td:nth-child(3), .cs-exist td:nth-child(3), .cs-modified td:nth-child(3){width:10%;}
    .cs-valid td:nth-child(4), .cs-exist td:nth-child(4), .cs-modified td:nth-child(4){width:10%;}
</style>
<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <h3>
            <div class="screen-title">
                <%$this->lang->line('LANGUAGELABELS_LANGUAGE_LABELS')%>
            </div>        
        </h3>
        <div class="header-right-drops"></div>
    </div>
</div>
<div id="ajax_content_div" class="ajax-content-div top-frm-spacing box gradient">
    <input type="hidden" id="projmod" name="projmod" value="languagelabels">
    <div id="ajax_qLoverlay"></div>
    <div id="ajax_qLbar"></div>
    <div class="top-frm-tab-layout" id="top_frm_tab_layout"></div>
    <div id="scrollable_content" class="scrollable-content top-block-spacing">
        <div class="grid-data-container pad-calc-container">
            <form name="frmimportaction" id="frmimportaction" action="<%$admin_url%>languagelabels/languagelabels/importStep2Action" method="post">
                <div class="main-content-block" id="main_content_block">
                    <div class="box gradient" style="width:99%;">
                        <table class="grid-table-view " width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td id="grid_data_col">
                                    <table cellpadding="5" style="width: 98%;table-layout: fixed;position: relative;">
                                        <tr>
                                            <th width="50%">Label</th>
                                            <th width="30%">Value</th>
                                            <th width="10%">Module</th>
                                            <th width="10%">Status</th>
                                        </tr>
                                    </table>
                                    <div style="width: 100%;overflow-y: scroll; height: 500px;">
                                        <table cellpadding="5" width="100%">
                                            <%foreach from=$data key=lk item=lv%>
                                            <%assign var="errclass" value="cs-valid"%>
                                            <%if $lv['vLabel']|array_key_exists:$label_data neq false%>
                                            <%assign var="errclass" value="cs-exist"%>
                                            <%if $lv['vValue'] neq $label_data[$lv['vLabel']][0]['vValue']%>
                                            <%assign var="errclass" value="cs-modified"%>
                                            <%/if%>
                                            <%/if%>
                                            <tr class="<%$errclass%>">
                                                <td><%$lv['vLabel']%></td>
                                                <td><%$lv['vValue']%></td>
                                                <td><%$lv['vModule']%></td>
                                                <td><%$lv['eStatus']%></td>
                                            </tr>
                                            <%/foreach%>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="action-btn-align" id="action_btn_container">
                    <input value="Start Import" name="ctrlimport" type="submit" id="frmbtn_import" class='btn btn-info'/>
                    <input value="<%$this->lang->line('GENERIC_DISCARD')%>" name="ctrldiscard" type="button" id="frmbtn_discard" class='btn' onclick="return loadAdminModuleListing('languagelabels/languagelabels/index', '<%$extra_hstr%>')">
                </div>
            </form>
        </div>
    </div>
</div>
<%$this->js->add_js("admin/admin/js_languagelabels_import.js")%>
<%if $this->input->is_ajax_request()%>
<%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
<%$this->css->css_src()%>
<%/if%>