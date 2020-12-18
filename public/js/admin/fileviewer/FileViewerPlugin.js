var FileViewerPlugin = {
    urls: [],
    pdfVars: {
        'instance': null,
        'current': 0,
        'total': 0,
        'progress': 0,
        'canvasEle': null,
        'canvasCtx': null,
    },
    slider: {
        'current': 0,
        'itemType': '',
        'itemURL': '',
        'itemDefault': ''
    },
    init: function (urls) {
        if (typeof urls == "array" || typeof urls == "object") {
            this.urls = urls;
        }
        this.initActions();
        this.bindEvents();
        this.startSlide();
    },
    initActions: function () {

    },
    startSlide: function () {
        if (this.urls.length > 0)
            renderSlide(0);
    },
    bindEvents: function () {

        // Previous Slide
        $(document).off("click", "#btn-prev");
        $(document).on("click", "#btn-prev", function () {
            FileViewerPlugin.slider.current--;
            if (FileViewerPlugin.slider.current < 0) {
                FileViewerPlugin.slider.current = 0;
                updateSlideNavigation("first");
                return;
            } else if (FileViewerPlugin.slider.current >= FileViewerPlugin.urls.length) {
                FileViewerPlugin.slider.current = FileViewerPlugin.urls.length - 1;
            }
            renderSlide();
        });

        // Next Slide
        $(document).off("click", "#btn-next");
        $(document).on("click", "#btn-next", function () {
            FileViewerPlugin.slider.current++;
            if (FileViewerPlugin.slider.current >= FileViewerPlugin.urls.length) {
                FileViewerPlugin.slider.current = FileViewerPlugin.urls.length - 1;
                updateSlideNavigation("last");
                return;
            }
            renderSlide();
        });

    }
};
function checkFileType(type) {
    var ret_type = type;
    if (typeof type == "undefined" || type == null || type == "") {
        ret_type = "text";
    }
    switch (ret_type) {
        case "xls":
        case "xlsx":
        case "doc":
        case "docx":
        case "ppt":
        case "pptx":
            ret_type = "googledocs";
            break;
        case "odt":
        case "ods":
        case "odp":
            ret_type = "openoffice";
            break;
        case "pdf":
            ret_type = "pdf";
            break;
        case "png":
        case "jpg":
        case "jpeg":
        case "bmp":
        case "gif":
            ret_type = "image";
            break;
    }
    return ret_type;
}
function toggleItemContainers(type) {
    var containerList = ["#imageFile", "#pdfFile", "#xslFile", "#csvFile", "#textFile", "#pdf-buttons", "#iframeFile", "#openOfficeFile"];
    var showList = [];
    switch (type) {
        case "csv":
            showList.push("#csvFile");
            break;
        case "pdf":
            showList.push("#pdfFile");
            showList.push("#pdf-buttons");
            break;
        case "image":
            showList.push("#imageFile");
            break;
    }
    for (var cont in containerList) {
        if ($.inArray(containerList[cont], showList) >= 0) {
            if ($(containerList[cont]).is(":hidden")) {
                $(containerList[cont]).show();
            }
        } else {
            $(containerList[cont]).hide();
        }
    }
}
function updateSlideNavigation(nav_mode) {
    if (typeof nav_mode == "undefined" || nav_mode == null || nav_mode == "") {
        nav_mode = "both";
    }
    switch (nav_mode) {
        case "first":
            $("#btn-prev").addClass("nav-disable");
            $("#btn-next").removeClass("nav-disable");
            break;
        case "last":
            $("#btn-prev").removeClass("nav-disable");
            $("#btn-next").addClass("nav-disable");
            break;
        default:
            $("#btn-next, #btn-prev").removeClass("nav-disable");
            break;
    }
}

function renderSlide(slide_index) {
    showloader();
    updateContainerScroll(".viewer-data", "remove");
    if (typeof slide_index != "undefined" && FileViewerPlugin.slider.current != null && !isNaN(slide_index)) {
        FileViewerPlugin.slider.current = slide_index;
    }
    if(FileViewerPlugin.slider.current <= 0){
         updateSlideNavigation("first");
    }else if(FileViewerPlugin.slider.current >= FileViewerPlugin.urls.length-1){
         updateSlideNavigation("last");
    }else{
        updateSlideNavigation();
    }
    FileViewerPlugin.slider.itemType = FileViewerPlugin.urls[FileViewerPlugin.slider.current]["extension"];
    FileViewerPlugin.slider.itemURL = FileViewerPlugin.urls[FileViewerPlugin.slider.current]["href"];
    var itemType = checkFileType(FileViewerPlugin.urls[FileViewerPlugin.slider.current]["extension"]);
    toggleItemContainers(itemType);
    FileViewerPlugin.slider.itemType = itemType;
    $("#page-title").text(FileViewerPlugin.urls[FileViewerPlugin.slider.current]["title"]).attr("title", FileViewerPlugin.urls[FileViewerPlugin.slider.current]["title"]);
    $("#page-title").next().attr({"href": FileViewerPlugin.slider.itemURL});

    if (itemType == "csv" || itemType == "tsv") {
        $("#iframeFile").empty();
        $.ajax({
            type: "GET",
            url: FileViewerPlugin.slider.itemURL,
            dataType: "text",
            success: function (response)
            {
                var csvopts = {};
                if (itemType == "tsv") {
                    csvopts["separator"] = "\t";
                }
                var data = $.csv.toArrays(response, csvopts);
                generateHtmlTable(data, "iframeFile");
            }
        });
        $("#iframeFile").show();
        hideloader();
        showPreview();
    } else if (itemType == "image") {
        $("#iframeFile").empty();
        $("#iframeFile").append($("<img/>").attr({"src": FileViewerPlugin.slider.itemURL}));
        $("#iframeFile").show();
        hideloader();
    } else if (itemType == "googledocs") {
        var src = "https://docs.google.com/viewer?url=" + FileViewerPlugin.slider.itemURL + "&embedded=true";
        removeFrames("iframeFile", true, src);
        $("#iframeFile").show();
    } else if (itemType == "openoffice") {
        var src = site_url + "/public/js/admin/fileviewer/ViewerJS/#" + FileViewerPlugin.slider.itemURL;
        removeFrames("iframeFile", true, src);
        $("#iframeFile").show();
    } else if (itemType == "pdf") {
        var src = site_url + "public/js/admin/fileviewer/pdfjs-2.0/web/viewer.html?file=" + FileViewerPlugin.slider.itemURL;
        removeFrames("iframeFile", true, src);
        $("#iframeFile").show();
    } else {
        var src = FileViewerPlugin.slider.itemURL;
        removeFrames("iframeFile", true, src);
        $("#iframeFile").show();
    }
    updateContainerScroll(".viewer-data", "add");
}
function updateContainerScroll(ele, type) {
    if (typeof ele == "undefined" || ele == null || ele == "") {
        return false;
    }
    if (type == 'add') {
        $(ele).niceScroll();
    } else if (type == 'remove') {
        $(ele).getNiceScroll().remove();
    }
}
function removeFrames(par_ele_id, recreate, src) {
    if (typeof par_ele_id != "undefined" && par_ele_id != null && $("#" + par_ele_id).length > 0) {
        $("#" + par_ele_id).find("iframe").remove();
        $("#" + par_ele_id).empty();
        if (recreate === true) {
            var ifram = document.createElement('iframe');
            ifram.id = par_ele_id + "_0";
            ifram.src = src;
            ifram.width = "100%";
            ifram.height = "100%";
            ifram.frameborder = 0;
            ifram.onload = function(a){
                hideloader();
            }
            $("#" + par_ele_id).append(ifram);
        }
    }
}
function showloader(){
    $('#fileViewerBox').addClass('loadstate');
}
function hideloader(){
    $('#fileViewerBox').removeClass('loadstate');
}
function showPreview() {}
function generateHtmlTable(data, ele) {
    var html = '<table  class="table table-condensed table-hover table-striped">';
    if (typeof (data[0]) === 'undefined') {
        return null;
    } else {
        $.each(data, function (index, row) {
            //bind header
            if (index == 0) {
                html += '<thead>';
                html += '<tr>';
                $.each(row, function (index, colData) {
                    html += '<th>';
                    html += colData ? colData : "";
                    html += '</th>';
                });
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
            } else {
                html += '<tr>';
                $.each(row, function (index, colData) {
                    html += '<td>';
                    html += colData ? colData : "";
                    html += '</td>';
                });
                html += '</tr>';
            }
        });
        html += '</tbody>';
        html += '</table>';

        tempHTML = html;

        $('#' + ele).append(html);
    }
}
function generateHtmlTableForXSL(data, ele) {
    var html = '<table>';
    if (typeof (data[0]) === 'undefined') {
        return null;
    } else {
        $.each(data, function (index, row) {
            //bind header
            if (index == 0) {
                html += '<thead>';
                html += '<tr>';
                $.each(row, function (index, colData) {
                    html += '<th>';
                    html += colData ? colData : "";
                    html += '</th>';
                });
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
            } else {
                html += '<tr>';
                $.each(row, function (index, colData) {
                    html += '<td>';
                    html += colData ? colData : "";
                    html += '</td>';
                });
                html += '</tr>';
            }
        });
        html += '</tbody>';
        html += '</table>';

        $('#' + ele).append(html);
    }
}
