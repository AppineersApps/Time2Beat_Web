$(function () {

    $(".scrollbars").each(function () {
        var id = $(this).attr("id");
        $("#" + id).niceScroll({
            cursoropacitymax: 0.7,
            cursorborderradius: 6,
            cursorwidth: "4px"
        });
    });

});