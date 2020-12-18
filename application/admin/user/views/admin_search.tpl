<table id="tbl_left_search" class="tbl-left-search" width="100%" cellpadding="0" cellspacing="0">
    <!-- Left Search UI -->
    <%if $search_arr|@is_array && $search_arr|@count gt 0%>
        <%foreach from=$search_arr item=sVal key=sKey%>
            <%assign var="search_rec" value=$sVal["records"]%>
            <tr>
                <th class="search-header">
                    <strong><%$sVal["label_lang"]%></strong>
                    <span class="search-show-hide"><a href="javascript://" aria-search-table="<%$sKey%>" class="search-icon minimize-search"><span class="icon16 search-font"></span></a></span>
                </th>
            </tr>
            <tr id="tbl_search_records_<%$sKey%>">
                <td>
                    <table class="tbl-search-records" width="100%" cellpadding="2" cellspacing="1"> 
                    <%if $sVal["range"] eq "Yes"%>
                        <!-- Range Slider -->
                        <tr>
                            <td colspan="2">
                                <div align="center" class="search-range-slider" id="search_range_slider_<%$sKey%>" aria-range-max="<%$sVal['range_max']%>" aria-range-min="<%$sVal['range_min']%>" aria-range-key="<%$sVal['name']%>"></div>
                                <div>
                                    <input type="text" class="range-input-prefer" name="lsrange_min_<%$sVal['name']%>" id="lsrange_min_<%$sVal['name']%>" value="<%$sVal['range_min']%>" />
                                    -
                                    <input type="text" class="range-input-prefer" name="lsrange_max_<%$sVal['name']%>" id="lsrange_max_<%$sVal['name']%>" value="<%$sVal['range_max']%>" />
                                    <span><a href="javascript://" aria-search-field="<%$sKey%>" class="search-range-icon"><span class="icon14 eco-search"></span></a></span>
                                </div>
                            </td>
                        </tr>
                    <%elseif $sVal["auto"] eq "Yes"%>
                        <!-- Date & Time Related -->
                        <%assign var="extra_attr" value=''%>
                        <%assign var="search_mode" value='auto'%>
                        <%if $sVal["type"] eq "date" %>
                            <%assign var="extra_attr" value='aria-date-format="'|@cat:$sVal['format']|@cat:'"'%>
                        <%elseif $sVal["type"] eq "date_and_time" %>
                            <%assign var="extra_attr" value='aria-date-format="'|@cat:$sVal['format']|@cat:'" aria-enable-time="'|@cat:$sVal['time']|@cat:'"'%>
                        <%elseif $sVal["type"] eq "time" %>
                            <%assign var="extra_attr" value='aria-time-format="'|@cat:$sVal['format']|@cat:'" aria-enable-sec="'|@cat:$sVal['sec']|@cat:'" aria-enable-ampm="'|@cat:$sVal['ampm']|@cat:'"'%>
                        <%/if%>
                        <%if $sVal["order"] eq "TEXT"%>
                            <%assign var="search_mode" value='text'%>
                        <%/if%>
                        <tr>
                            <td colspan="2">
                                <input type="text" aria-search-mode="<%$search_mode%>" aria-search-field="<%$sVal['name']%>" aria-field-type="<%$sVal['type']%>" aria-list-id="<%$sVal['name']%>" name="lsac_<%$sVal['name']%>" id="lsac_<%$sVal['name']%>" class="lsac-input-left-filter" <%$extra_attr%> />
                            </td>
                        </tr>
                    <%/if%>
                    <%if $sVal["range"] eq "Yes"%>
                        <!-- Range Values -->
                        <%assign var="range_values" value=$sVal["values"]%>
                        <%section name=i loop=$range_values%>
                        <%if $search_rec[i]['tot'] gt 0 || $range_values[i]['show'] eq 'Yes'%>
                            <tr class="left-data-row">
                                <td width="75%" class="data-left-align">
                                    <a href="javascript://" aria-search-field="<%$sVal['name']%>" aria-search-type="range" aria-search-min="<%$search_rec[i]['min']%>" aria-search-max="<%$search_rec[i]['max']%>" aria-search-level="<%$search_rec[i]['level']%>" class="data-left-anchor">
                                        <%$range_values[i]['label']%>
                                    </a>
                                </td>
                                <td width="25%" class="data-right-align">
                                    <a href="javascript://" aria-search-field="<%$sVal['name']%>" aria-search-type="range" aria-search-min="<%$search_rec[i]['min']%>" aria-search-max="<%$search_rec[i]['max']%>" aria-search-level="<%$search_rec[i]['level']%>" class="data-left-anchor">
                                        <%$search_rec[i]['tot']%>
                                    </a>
                                </td>
                            </tr> 
                        <%/if%>
                        <%/section%>
                    <%elseif $search_rec|@is_array && $search_rec|@count gt 0 %>
                        <!-- General Values -->
                        <%if $sVal["dropdown"] eq "Yes"%>
                            <tr>
                                <td colspan="2">
                                    <select aria-search-mode="dropdown" aria-search-field="<%$sVal['name']%>" aria-field-type="<%$sVal['type']%>" aria-list-id="<%$sVal['name']%>" name="lsac_<%$sVal['name']%>" id="lsac_<%$sVal['name']%>" class="lsac-select-left-filter chosen-select" data-placeholder=" " multiple=true>
                                        <%section name=i loop=$search_rec%>
                                            <option value="<%$search_rec[i]['id']%>" data-count="<%$search_rec[i]['tot']%>"><%$search_rec[i]["val"]%></option>
                                        <%/section%>
                                    </select>
                                </td>
                            </tr>
                        <%else%>
                            <%section name=i loop=$search_rec%>
                                <tr class="left-data-row">
                                    <td width="75%" class="data-left-align">
                                        <a href="javascript://" aria-search-type="normal" aria-search-field="<%$sVal['name']%>" aria-search-value="<%$search_rec[i]['id']%>" title="<%$search_rec[i]['val']%>" class="data-left-anchor">
                                            <%if $search_rec[i]['val']|@trim eq ''%>
                                                <span class="errormsg">N/A</span>
                                            <%else%>
                                                <%$search_rec[i]["val"]%>
                                            <%/if%>
                                        </a>
                                    </td>
                                    <td width="25%" class="data-right-align">
                                        <a href="javascript://" aria-search-type="normal" aria-search-field="<%$sVal['name']%>" aria-search-value="<%$search_rec[i]['id']%>" title="<%$search_rec[i]['tot']%>" class="data-left-anchor">
                                            <%if $sVal["count"] eq "No"%>
                                                <span class="entypo-icon-search-2 left-filter-row-icon"></span>
                                            <%else%>
                                                <%$search_rec[i]['tot']%>
                                            <%/if%>
                                        </a>
                                    </td>
                                </tr>
                            <%/section%>
                        <%/if%>
                    <%elseif $sVal["auto"] neq "Yes" %>
                        <tr class="left-data-row"><td class="errormsg" colspan="2" class="data-left-align"><%$this->lang->line("GENERIC_NONE")%></td></tr>
                    <%/if%>
                    </table>
                </td>
            </tr>
        <%/foreach%>
    <%/if%>
</table>