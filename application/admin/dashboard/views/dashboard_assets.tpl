<%javascript%>
    $.jgrid.no_legacy_api = true, $.jgrid.useJSON = true;
    var DB_data_list_JSON = {}, DB_pivot_data_JSON = {};
    var DB_block_config_JSON = $.parseJSON('<%$block_config_json%>');
    <%if $db_pivot_data_json%>
        DB_pivot_data_JSON = <%$db_pivot_data_json%>;
    <%/if%>
    <%if $db_list_data_json%>
        DB_data_list_JSON = <%$db_list_data_json%>;
    <%/if%>
    initDashBoardSettings();
    for (var i in DB_pivot_data_JSON) {
        if (!DB_pivot_data_JSON[i]['dbID']) {
            continue;
        }
        callDashBoardPivotListing(DB_pivot_data_JSON[i]);
    }
    for (var i in DB_data_list_JSON) {
        if (!DB_data_list_JSON[i]['dbID']) {
            continue;
        }
        callDashBoardGridListing(DB_data_list_JSON[i]);
    }
    initDashBoardFilters();
<%/javascript%>