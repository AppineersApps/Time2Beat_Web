$(function () {

    $(window).scroll(function () {
        if ($(this).scrollTop() > 0) {
            $('#notes-sidebar').addClass("content_fixed");
        } else {
            $('#notes-sidebar').removeClass("content_fixed");
        }
    });

    $('#left-content-list').niceScroll("#left-content-block", {
        cursoropacitymax: 0.7,
        cursorborderradius: 6,
        cursorwidth: "4px"
    });
    $('.left-content-item .left-content-anc').click(function () {
        var id = $(this).attr('data-note-id');
        if (id != '' && $("#note-content-" + id).length) {
            $(".left-content-anc").removeClass("active");
            $(this).addClass("active");
            $("html, body").animate({
                scrollTop: $("#note-content-" + id).offset().top - 110
            }, 1000);
        }
    });

    setTimeout(function () {
        var default_id = $("#left-content-block").attr("data-default-id");
        if (default_id && $("#note-content-" + default_id).length) {
            $(".left-content-anc").removeClass("active");
            $(".left-content-anc[data-note-id=" + default_id + "]").addClass("active");
            $("html, body").animate({
                scrollTop: $("#note-content-" + default_id).offset().top - 110
            }, 1000);
        }
    }, 300);

});