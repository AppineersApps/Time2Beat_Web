<%if $with_attributes eq 1%>
    <%if $options_only eq 1%>
        <%if $data_type eq 'opt_group'%>
            <%foreach from=$data_array key=key item=val%>
                <%if $val|is_array && $val|count gt 0%>
                <optgroup label="<%$key%>">
                <%section name=j loop=$val%>
                    <%assign var="selected_attr" value=""%>
                    <%if $val[i]['id'] eq $combo_selected%>
                        <%assign var="selected_attr" value='selected="selected"'%>
                    <%/if%>
                    <option value="<%$val[i]['id']%>" <%$selected_attr%> <%$val[i]['attr']%>><%$val[i]['val']%></option>
                <%/section%>
                </optgroup>
                <%/if%>
            <%/foreach%>
        <%else%>
            <%section name=i loop=$data_array%>
                <%assign var="selected_attr" value=""%>
                <%if $data_array[i]['id'] eq $combo_selected%>
                    <%assign var="selected_attr" value='selected="selected"'%>
                <%/if%>
                <option value="<%$data_array[i]['id']%>" <%$selected_attr%> <%$data_array[i]['attr']%>><%$data_array[i]['val']%></option>
            <%/section%>
        <%/if%>
    <%else%>
        <select name="<%$combo_name%>" id="<%$combo_id%>" <%$combo_extra%> data-template=true>
            <%if $data_type eq 'opt_group'%>
                <%foreach from=$data_array key=key item=val%>
                    <%if $val|is_array && $val|count gt 0%>
                    <optgroup label="<%$key%>">
                    <%section name=j loop=$val%>
                        <%assign var="selected_attr" value=""%>
                        <%if $val[i]['id'] eq $combo_selected%>
                            <%assign var="selected_attr" value='selected="selected"'%>
                        <%/if%>
                        <option value="<%$val[i]['id']%>" <%$selected_attr%> <%$val[i]['attr']%>><%$val[i]['val']%></option>
                    <%/section%>
                    </optgroup>
                    <%/if%>
                <%/foreach%>
            <%else%>
                <%section name=i loop=$data_array%>
                    <%assign var="selected_attr" value=""%>
                    <%if $data_array[i]['id'] eq $combo_selected%>
                        <%assign var="selected_attr" value='selected="selected"'%>
                    <%/if%>
                    <option value="<%$data_array[i]['id']%>" <%$selected_attr%> <%$data_array[i]['attr']%>><%$data_array[i]['val']%></option>
                <%/section%>
            <%/if%>
        </select>
    <%/if%>
<%else%>
    <%if $options_only eq 1%>
        <%html_options options=$combo_array selected=$combo_selected%>
    <%else%>
        <select name="<%$combo_name%>" id="<%$combo_id%>" <%$combo_extra%>>
            <%html_options options=$combo_array selected=$combo_selected%>
        </select>
    <%/if%>
<%/if%>