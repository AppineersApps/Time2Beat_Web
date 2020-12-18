//window resize events
$(window).resize(function () {
    //get the window size
    var wsize = $(window).width();
    if (wsize > 980) {
        $('.shortcuts.hided').removeClass('hided').attr("style", "");
        $('.sidenav.hided').removeClass('hided').attr("style", "");
    }
});
$(window).load(function () {
    var wheight = $(window).height();
    $('#sidebar.scrolled').css('height', wheight - 63 + 'px');
});
// document ready function
$(document).ready(function () {
    //prevent font flickering in some browsers 
    (function () {
        //if firefox 3.5+, hide content till load (or 3 seconds) to prevent FOUT
        var d = document, e = d.documentElement, s = d.createElement('style');
        if (e.style.MozTransform === '') { // gecko 1.9.1 inference
            s.textContent = 'body{visibility:hidden}';
            //e.firstChild.appendChild(s);
            function f() {
                s.parentNode && s.parentNode.removeChild(s);
            }
            addEventListener('load', f, false);
            setTimeout(f, 3000);
        }
    })();

    //Disable certain links
    $("a[href='#']").click(function (e) {
        e.preventDefault()
    })

    if (!isFancyBoxActive()) {
        $(function () {
            if (el_tpl_settings.is_app_cache_active == "No") {
                navigLeftMenuEvents();
                if (el_tpl_settings.is_admin_theme_create == '1') {
                    createThemeSettings();
                }
                if (el_tpl_settings.is_admin_shortcut_access == 'Y') {
                    createShortcutList();
                }
            }
        });
    }

    //Hide and show sidebar btn
    $(function () {
        //var pages = ['grid.html','charts.html'];
        var pages = [];
        for (var i = 0, j = pages.length; i < j; i++) {
            if (getLocalStore(el_tpl_settings.enc_usr_var + "_mp") == pages[i]) {
                var cBtn = $('#collapse_btn.leftbar');
                cBtn.children('a').attr('title', js_lang_label.GENERIC_SHOW_SIDEBAR);
                cBtn.addClass('shadow hide');
                cBtn.css({
                    'top': '20px',
                    'left': '200px'
                });
                $('#sidebarbg').css('margin-left', '-299' + 'px');
                $('#sidebar').css('margin-left', '-299' + 'px');
                if ($('#content').length) {
                    $('#content').css('margin-left', '0');
                }
                if ($('#content-two').length) {
                    $('#content-two').css('margin-left', '0');
                }
            }
        }
    });

    $(document).on("click", "#collapse_btn", function () {
        var $this = $(this);
        //left sidbar clicked
        if (el_theme_settings.menu_semicollapse || el_general_settings.mobile_platform) {
            semiCollapseLeftMenu($this);
        } else {
            fullCollapseLeftMenu($this);
        }
        adjustLeftMenuScrollBar();
    });

    //------------- widget box magic -------------//
    var widget = $('div.box');
    var widgetOpen = $('div.box').not('div.box.closed');
    var widgetClose = $('div.box.closed');
    //close all widgets with class "closed"
    widgetClose.find('div.content').hide();
    widgetClose.find('.title>.minimize').removeClass('minimize').addClass('maximize');

    widget.find('.title>a').on('click', function (event) {
        event.preventDefault();
        var $this = $(this);
        if ($this.hasClass('minimize')) {
            //minimize content
            $this.removeClass('minimize').addClass('maximize');
            $this.parent('div').addClass('min');
            cont = $this.parent('div').next('div.content')
            cont.slideUp(500, 'easeOutExpo'); //change effect if you want :)

        } else if ($this.hasClass('maximize')) {
            //minimize content
            $this.removeClass('maximize').addClass('minimize');
            $this.parent('div').removeClass('min');
            cont = $this.parent('div').next('div.content');
            cont.slideDown(500, 'easeInExpo'); //change effect if you want :)
        }
    });

    //show minimize and maximize icons
    widget.on('hover', function () {
        $(this).find('.title>a').show(50);
    }, function () {
        $(this).find('.title>a').hide();
    });

    //add shadow if hover box
    widget.on('hover', function () {
        $(this).addClass('hover');
    }, function () {
        $(this).removeClass('hover');
    });

    //------------- placeholder fallback  -------------//
    $('input[placeholder], textarea[placeholder]').placeholder();

    //------------- To top plugin  -------------//
    $().UItoTop({
        //containerID: 'toTop', // fading element id
        //containerHoverID: 'toTopHover', // fading element hover id
        //scrollSpeed: 1200,
        easingType: 'easeOutQuart'
    });

    //------------- Tooltips -------------//

    //top tooltip
    $('.tip').qtip({
        content: false,
        position: {
            my: 'bottom center',
            at: 'top center',
            viewport: $(window)
        },
        style: {
            classes: 'ui-tooltip-tipsy'
        }
    });

    //tooltip in right
    $('.tipR').qtip({
        content: false,
        position: {
            my: 'left center',
            at: 'right center',
            viewport: $(window)
        },
        style: {
            classes: 'ui-tooltip-tipsy'
        }
    });

    //tooltip in bottom
    $('.tipB').qtip({
        content: false,
        position: {
            my: 'top center',
            at: 'bottom center',
            viewport: $(window)
        },
        style: {
            classes: 'ui-tooltip-tipsy'
        }
    });

    //tooltip in left
    $('.tipL').qtip({
        content: false,
        position: {
            my: 'right center',
            at: 'left center',
            viewport: $(window)
        },
        style: {
            classes: 'ui-tooltip-tipsy'
        }
    });

    //remove loadstate class from body and show the page
    setTimeout('$("html").removeClass("loadstate")', 500);

    $(document).on('touchmove', function () {
        if (!$(this).hasClass("hasUl")) {
            hideTouchMoveMenu();
        }
    });
    $(document).on('click', '.semi-left-menu-items .menu-child-anchor', function () {
        if (!$(this).hasClass("hasUl")) {
            $('.semi-item-show').addClass('semi-item-none');
            $('.semi-item-none').removeClass('semi-item-show');
            $('.semi-item-none').removeClass('expand');
        }
    });
    $(document).on('touchend', '.semi-left-menu-items .menu-child-anchor', function () {
        if (!$(this).hasClass("hasUl")) {
            window.location.href = $(this).attr("href");
            hideTouchMoveMenu();
        }
    });
    $("#navTopMenu .parent-menu-li").on('hover', function () {
        var num_levels = $(this).find("ul.sub").length;
        var menu_item_width = num_levels * 179 - num_levels + 1;
        var ele_right_space = $(window).width() - $(this).offset().left;
        if (ele_right_space < menu_item_width) {
            var arr = [], width1, width2, width3, i = 0;
            width1 = $(this).width() - 179;
            width2 = width1 - 176;
            width3 = width2 - 176;
            arr.push(width1);
            arr.push(width2);
            arr.push(width3);
            $(this).find("ul.sub").each(function () {
                $(this).addClass("change-left-width-" + i).css("margin-left", arr[i]);
                i++;
            });
        }
    });
    if($('.left-profile').length)
    {
        $('.left-profile').initial();
    }
    if($('.profile').length)
    {
        $('.profile').initial();
    }
    $('.top-notification-heading-right').click(function() {
        $('.top-notification-heading div').removeClass('selected');
        $('.top-notification-heading-right').addClass('selected');
        $('.top-notification-content').hide();
        $('.top-notification-desktop').show();
    });
    $('.top-notification-heading-left').click(function() {
        $('.top-notification-heading-left').removeClass('selected');
        $('.top-notification-heading-left').addClass('selected');
        $('.top-notification-content').show();
        $('.top-notification-desktop').hide();
    });
});
function hideTouchMoveMenu() {
    if ($('.semi-item-show').length) {
        $('.semi-item-show').addClass('semi-item-none');
        $('.semi-item-none').removeClass('semi-item-show');
        $('.semi-item-none').removeClass('expand');
    }
}
//related to navigation menu
function navigLeftMenuEvents() {
    //------------- Navigation -------------//
    var local_mc = getLocalStore(el_tpl_settings.enc_usr_var + "_mc");
    var href_mc;
    if ($(".top-menu").length) {
        mainNavUl = $(".top-menu ul");
        mainNav = $('.top-menu>ul>li');
        mainNav.find('ul').siblings().addClass('hasUl');
        mainNavLink = mainNav.find('a').not('.sub a');
        mainNavLinkAll = mainNav.find('a');
        mainNavSubLink = mainNav.find('.sub a').not('.sub li .sub a');
        mainNavCurrent = mainNav.find('a.current');
        mainNavActive = mainNav.find('a.active');
        //remove current class if have
        mainNavCurrent.removeClass('current');
        mainNavActive.removeClass('active');
        //set the seleceted menu element
        if (local_mc && local_mc != "javascript://") {
            var fflag = false;
            mainNavLinkAll.each(function (index) {
                if ($(this).attr('href') == document.location.href) {
                    //set new current class
                    $(this).addClass('current');
                    ulElem = $(this).closest('ul');
                    if (ulElem.hasClass('sub')) {
                        //its a part of sub menu need to expand this menu
                        aElem = ulElem.prev('a.hasUl').addClass('active');
                    }
                    //create new cookie
                    setLocalStore(el_tpl_settings.enc_usr_var + "_mp", $(this).attr('href'))
                    fflag = true;
                    return false;
                }
            });
            if (!fflag) {
                mainNavLinkAll.each(function (index) {
                    href_mc = $(this).attr("href");
                    if (isMenuURLMatched(href_mc, local_mc)) {
                        //set new current class
                        $(this).addClass('current');
                        ulElem = $(this).closest('ul');
                        if (ulElem.hasClass('sub')) {
                            //its a part of sub menu need to expand this menu
                            aElem = ulElem.prev('a.hasUl').addClass('active');
                        }
                        //create new cookie
                        setLocalStore(el_tpl_settings.enc_usr_var + "_mp", $(this).attr('href'));
                        fflag = true;
                        return false;
                    }
                });
            }
        } else {
            mainNavUl.find("[rel='home']").parents("ul.sub").show();
        }
        //hover magic add blue color to icons when hover - remove or change the class if not you like.
        //click magic
        mainNavLinkAll.click(function (event) {
            var $this = $(this);
            mainNavUl.find("li a.hasUl").removeClass("active");
            $("#navTopMenu").find(".top .sub li a").removeClass("current");
            if ($this.hasClass('hasUl')) {
                $this.addClass("active");
            } else {
                //has no ul so store a cookie for change class.
                $(this).closest(".parent-menu-li").find("a.hasUl").addClass("active");
                $(this).addClass("current");
            }
            setLocalStore(el_tpl_settings.enc_usr_var + "_mc", $this.attr('href'));
        });

        $('.parent-menu-li .child-menu-li').each(function () {
            if ($(this).find("ul.nes").length) {
                var nes_offset = $(this).closest(".parent-menu-li").offset().left;
                var win_width = $(window).width();
                if (win_width <= nes_offset + 350) {
                    $(this).find("ul.nes").addClass("rtl");
                }
            }
        });
    } else {
        if (el_theme_settings.menu_semicollapse || el_general_settings.mobile_platform) {
            hideLeftPanelSemiMenu();
        }
        mainNavUl = $("#left_mainnav ul");
        mainNav = $('#left_mainnav>ul>li');
        mainNav.find('ul').siblings().addClass('hasUl').append('<span class="hasDrop icon16 icomoon-icon-arrow-right-2"></span>');
        mainNavLink = mainNav.find('a').not('.sub a');
        mainNavLinkAll = mainNav.find('a');
        mainNavSubLink = mainNav.find('.sub a').not('.sub li .sub a');
        mainNavCurrent = mainNav.find('a.current');
        mainNavActive = mainNav.find('a.active');
        //remove current class if have
        mainNavCurrent.removeClass('current');
        mainNavActive.removeClass('active');
        //set the seleceted menu element
        if (local_mc && local_mc != "javascript://") {
            var fflag = false;
            mainNavLinkAll.each(function (index) {
                if ($(this).attr('href') == document.location.href) {
                    //set new current class
                    $(this).addClass('current');
                    $(this).parents('ul.sub').prev('.hasUl').find('span.hasDrop').removeClass('icomoon-icon-arrow-right-2');
                    $(this).parents('ul.sub').prev('.hasUl').find('span.hasDrop').addClass('icomoon-icon-arrow-down-2');
                    ulElem = $(this).closest('ul');
                    if (ulElem.hasClass('sub')) {
                        //its a part of sub menu need to expand this menu
                        aElem = ulElem.prev('a.hasUl').addClass('drop').addClass('active');
                        ulElem.addClass('expand');
                    }
                    //create new cookie
                    setLocalStore(el_tpl_settings.enc_usr_var + "_mp", $(this).attr('href'))
                    fflag = true;
                    return false;
                }
            });
            if (!fflag) {
                mainNavLinkAll.each(function (index) {
                    href_mc = $(this).attr("href");
                    if (isMenuURLMatched(href_mc, local_mc)) {
                        //set new current class
                        $(this).addClass('current');
                        $(this).parents('ul.sub').prev('.hasUl').find('span.hasDrop').removeClass('icomoon-icon-arrow-right-2');
                        $(this).parents('ul.sub').prev('.hasUl').find('span.hasDrop').addClass('icomoon-icon-arrow-down-2');
                        ulElem = $(this).closest('ul');
                        if (ulElem.hasClass('sub')) {
                            //its a part of sub menu need to expand this menu
                            aElem = ulElem.prev('a.hasUl').addClass('drop').addClass('active');
                            ulElem.addClass('expand');
                        }
                        //create new cookie
                        setLocalStore(el_tpl_settings.enc_usr_var + "_mp", $(this).attr('href'))
                        fflag = true;
                        return false;
                    }
                });
            }
        } else {
            mainNavUl.find("[rel='home']").parents("ul.sub").show();
        }
        //hover magic add blue color to icons when hover - remove or change the class if not you like.
        //click magic
        mainNavLink.off('click');
        mainNavLink.click(function (event) {
            var $this = $(this);
            if ($this.hasClass('hasUl')) {
                event.preventDefault();
                if ($this.hasClass('drop')) {
                    $(this).find('span.hasDrop').removeClass('icomoon-icon-arrow-down-2');
                    $(this).find('span.hasDrop').addClass('icomoon-icon-arrow-right-2');
                    $(this).siblings('ul.sub').slideUp(500, 'swing').siblings().removeClass('drop');
                } else {
                    mainNavUl.find('span.hasDrop').removeClass('icomoon-icon-arrow-down-2');
                    mainNavUl.find('span.hasDrop').addClass('icomoon-icon-arrow-right-2');
                    mainNavUl.find('ul.sub').slideUp(500, 'swing').siblings().removeClass('drop');

                    $(this).find('span.hasDrop').removeClass('icomoon-icon-arrow-right-2');
                    $(this).find('span.hasDrop').addClass('icomoon-icon-arrow-down-2');
                    $(this).siblings('ul.sub').slideDown(500, 'swing').siblings().addClass('drop');
                }
                setTimeout(function () {
                    adjustLeftMenuScrollBar();
                }, 501);
            } else {
                //has no ul so store a cookie for change class.
                setLocalStore(el_tpl_settings.enc_usr_var + "_mc", $this.attr('href'));
                hideLeftScrollBar();
            }
        });
        mainNavSubLink.off('click');
        mainNavSubLink.click(function (event) {
            var $this = $(this);
            if ($this.hasClass('hasUl')) {
                event.preventDefault();
                if ($this.hasClass('drop')) {
                    $(this).find('span.hasDrop').removeClass('icomoon-icon-arrow-down-2');
                    $(this).find('span.hasDrop').addClass('icomoon-icon-arrow-right-2');
                    $(this).siblings('ul.sub').slideUp(500, 'swing').siblings().removeClass('drop');
                } else {
                    $(this).closest("ul.sub").find('span.hasDrop').removeClass('icomoon-icon-arrow-down-2');
                    $(this).closest("ul.sub").find('span.hasDrop').addClass('icomoon-icon-arrow-right-2');
                    $(this).closest("ul.sub").find('ul.sub').slideUp(500, 'swing').siblings().removeClass('drop');

                    $(this).find('span.hasDrop').removeClass('icomoon-icon-arrow-right-2');
                    $(this).find('span.hasDrop').addClass('icomoon-icon-arrow-down-2');
                    $(this).siblings('ul.sub').slideDown(500, 'swing').siblings().addClass('drop');
                }
            } else {
                //has no ul so store a cookie for change class.
                mainNavUl.find('ul.sub a').removeClass('current');
                $(this).addClass('current');
                mainNavUl.find("li a.hasUl").removeClass("drop").removeClass("active");
                $(this).closest(".parent-menu-li").find("a.hasUl").addClass("drop").addClass("active");
                setLocalStore(el_tpl_settings.enc_usr_var + "_mc", $this.attr('href'));
            }
        });
    }
    // for site map page 
    $("a.nav-active-link").off('click');
    $(document).on("click", "a.nav-active-link", function (event) {
        var $this = $(this);
        //has no ul so store a cookie for change class.
        var nav_code = $this.attr("aria-nav-code"), navUl, navLi, navPa;
        if ($(".top-menu").length) {
            navUl = $(".top-menu>ul");
            navLi = $(navUl).find("[aria-nav-code='" + nav_code + "']");
            navPa = navLi.closest(".parent-menu-li").find(".hasUl");
            if (!$(navPa).hasClass('active')) {
                $(navPa).trigger("click");
            }
            navUl.find('ul.sub a').removeClass('current');
            navLi.addClass("current");
        } else {
            navUl = $("#left_mainnav>ul");
            navLi = $(navUl).find("[aria-nav-code='" + nav_code + "']");
            navPa = navLi.closest(".parent-menu-li").find(".menu-parent-anchor");
            if (!$(navPa).hasClass('drop')) {
                $(navPa).trigger("click");
            }
            navUl.find('ul.sub a').removeClass('current');
            navLi.addClass("current");
        }
        setLocalStore(el_tpl_settings.enc_usr_var + "_mc", $this.attr('href'));
    });
    $("a.left-menu-hide").off('click');
    $(document).on("click", "a.left-menu-hide", function () {
        var $this = $(this);
        $(this).mouseout();
        //has no ul so store a cookie for change class.
        if ($("#collapse_btn").hasClass("hide")) {
            setLocalStore(el_tpl_settings.enc_usr_var + "_sm", '0');
        } else {
            setLocalStore(el_tpl_settings.enc_usr_var + "_sm", '1');
        }
    });
    if (getLocalStore(el_tpl_settings.enc_usr_var + "_sm") == "1" || el_general_settings.mobile_platform) {
        $("#collapse_btn").click();
    }
    if (!el_tpl_settings.page_animation) {
        $("#content").css({"transition-duration": "0ms", "-webkit-transition-duration": "0ms"});
        $("#content_slide").css({"transition-duration": "0ms", "-webkit-transition-duration": "0ms"});
    }
    adjustLeftMenuScrollBar();
}
function isMenuURLMatched(current, stored) {
    if (current == stored) {
        return true;
    }
    var mc_ssa = [], mc_csa = [], mc_ssp = [], mc_csp = [];
    mc_ssa = current.split("#");
    mc_csa = stored.split("#");
    if (!mc_ssa[1]) {
        return false;
    }
    if (!mc_csa[1]) {
        return false;
    }
    if (mc_csp[2] != "index") {
        return false;
    }
    mc_ssp = mc_ssa[1].split("/");
    mc_csp = mc_csa[1].split("/");
    if (mc_ssa[0] == mc_csa[0] && mc_ssp[0] == mc_csp[0] && mc_ssp[1] == mc_csp[1]) {
        return true
    }
}
function removeThemeSettings() {
    $("#switchBtn").remove();
    $("#switcher").remove();
}
function createShortcutList() {
    var list = '';
    $.each(cus_shortcuts_json, function (key, value) {
        list += '<tr><td class="shortcut-quicklist-td1">' + key + '</td><td class="shortcut-quicklist-td2">' + value.title + '</td></tr>';
    });
    $('body').append('<a href="javascript://" id="shortcut-btn" class="shortcut-btn" title="Shortcuts (ctrl+/)"><span class="icon13 icomoon-icon-rocket"></span></a>');
    $('body').append('\
            <div id="shortcut-quicklist" class="shortcut-quicklist-block">\n\
                <h4 class="shortcut-quicklist-title">Shortcut List</h4>\n\
                <div class="list-position">\n\
                    <table class="shortcut-quicklist-table" cellspacing=0 ><tbody><thead></thead>' + list + '</tbody></table>\n\
                </div>\n\
            </div>\n\
        ');

    $('#shortcut-btn').on('click', function () {
        if ($(this).hasClass('toggle')) {
            //hide switcher
            $(this).removeClass('toggle').css('right', '-1px');
            $('#shortcut-quicklist').css('display', 'none');

        } else {
            //expand switcher
            $(this).animate({
                right: '330'
            }, 200, function () {
                // Animation complete.
                $('#shortcut-quicklist').css('display', 'block');
                $(this).addClass('toggle');
                $('#switchBtn').css('z-index', '7999');
            });
        }
    });
}
//    z-index: 7999;
function createThemeSettings() {
    //------------- Switcher code ( Remove it in production site ) -------------//
    (function () {
        supr_switcher = {
            create: function () {
                removeThemeSettings();
                var theme_arr = el_theme_settings.themes_list;
                var themes_default = el_theme_settings.themes_default;
                var theme_custom = el_theme_settings.themes_custom;
                var js_theme_settings = el_theme_settings.theme_settings;
                var themes_default_arr = {}, theme_drop_str = '', theme_custom_str = '', theme_list_str = '', def_str = '', brk_str = '', bk = 0;
                var js_theme_color = '', js_theme_custom = '', js_theme_pattern_0 = '', js_theme_pattern_1 = '', js_theme_pattern_2 = '';
                if (js_theme_settings.theme) {
                    switch (js_theme_settings.theme) {
                        case 'metronic':
                            js_theme_color = js_theme_settings.color;
                            break;
                        case 'cit':
                            js_theme_color = js_theme_settings.color;
                            break;
                        default:
                            js_theme_pattern_0 = js_theme_settings.pattern_0;
                            js_theme_pattern_1 = js_theme_settings.pattern_1;
                            js_theme_pattern_2 = js_theme_settings.pattern_2;
                            break;
                    }
                    js_theme_custom = js_theme_settings.custom;
                }
                if (themes_default && $.isPlainObject(themes_default)) {
                    for (var i in themes_default) {
                        def_str = '';
                        bk = 1;
                        for (var j in themes_default[i]) {
                            brk_str = '';
                            if (bk % 5 == 0 && themes_default[i].length != bk) {
                                brk_str = '<br/>';
                            }
                            def_str += '<li class="theme-color-li"><a href="javascript://" style="background:' + themes_default[i][j]['color'] + '" aria-color="' + themes_default[i][j]['file'] + '" class="color-default ' + ((js_theme_color == themes_default[i][j]['file']) ? 'active' : '') + '"></a></li>' + brk_str + '\n';
                            bk++;
                        }
                        themes_default_arr[i] = def_str;
                    }
                }
                for (var i in theme_arr) {
                    theme_drop_str += '<option value="' + i + '">' + theme_arr[i] + '</option>';
                }
                if (theme_custom && theme_custom.length) {
                    bk = 1;
                    for (var i in theme_custom) {
                        brk_str = '';
                        if (bk % 5 == 0 && theme_custom.length != bk) {
                            brk_str = '<br/>';
                        }
                        theme_list_str += '<li class="custom-color-li"><a href="javascript://" style="background:' + theme_custom[i]['color'] + '" aria-color="' + theme_custom[i]['file'] + '" class="color-default ' + ((js_theme_custom == theme_custom[i]['file']) ? 'active' : '') + '"></a></li>' + brk_str + '\n';
                        bk++;
                    }
                    theme_custom_str = '<div id="_theme_custom_patterns" class="custom-theme-patterns">\n\
        <h4>Theme Customize<i class="icon minia-icon-close-2" id="_custom_color_remove"></i></h4>\n\
        <div class="custom-theme-colors">\n\
            <ul>\n\
                ' + theme_list_str + '\n\
                <li><button class="btn btn-success btn-switch" id="_custom_color_save" title="Save"><span class="icon16 icomoon-icon-checkmark-2 white"></span></button></li>\n\
            </ul>\n\
        </div>\n\
    </div>';
                }
                //create switcher and inject into html
                $('body').append('<a href="javascript://" id="switchBtn" class="switch-btn" title="Theme"><span class="icon24 icomoon-icon-cogs"></span></a>');
                $('body').append('\
<div id="switcher" class="switcher-block">\n\
    <h4>' + js_lang_label.GENERIC_MENU_POSITION + '</h4>\n\
    <div class="menu-position">\n\
        <ul>\n\
            <li><input type="radio" name="_theme_menu_postion" id="_theme_menu_left" value="Left" class="regular-radio"/><label for="_theme_menu_left">&nbsp;</label><label for="_theme_menu_left">' + js_lang_label.GENERIC_LEFT + ' </label>&nbsp;</li>\n\
            <li><input type="radio" name="_theme_menu_postion" id="_theme_menu_top" value="Top" class="regular-radio"/><label for="_theme_menu_top">&nbsp;</label><label for="_theme_menu_top">' + js_lang_label.GENERIC_TOP + ' </label></li>\n\
            <li><button class="btn btn-success btn-switch" id="_theme_menu_save" title="Save"><span class="icon16 icomoon-icon-checkmark-2 white"></span></button></li>\n\
        </ul>\n\
    </div>\n\
    <h4>' + js_lang_label.GENERIC_CHANGE_THEME + ' </h4>\n\
    <div class="theme-position">\n\
        <ul>\n\
            <li>\n\
                <select name="_theme_change_select" id="_theme_change_select">\n\
                    ' + theme_drop_str + '\n\
                </select>\n\
                &nbsp;\n\
            </li>\n\
            <li><button class="btn btn-success btn-switch" id="_theme_change_save" title="Save"><span class="icon16 icomoon-icon-checkmark-2 white"></span></button></li>\n\
        </ul>\n\
    </div>\n\
    <div id="_theme_supr_patterns" class="supr-theme-patterns">\n\
        <h4>' + js_lang_label.GENERIC_HEADER_PATTERNS + '</h4>\n\
        <div class="header-patterns">\n\
            <ul>\n\
                <li><a href="javascript://" class="hpat0 hpat-default ' + ((js_theme_pattern_0 == '' || js_theme_pattern_0 == 'default') ? 'active' : '') + '"></a></li>\n\
                <li class="hpat_bedge_grunge"><a href="javascript://" class="hpat1 hpat-default ' + ((js_theme_pattern_0 == 'bedge_grunge') ? 'active' : '') + '"></a></li>\n\
                <li class="hpat_grid"><a href="javascript://" class="hpat2 hpat-default ' + ((js_theme_pattern_0 == 'grid') ? 'active' : '') + '"></a></li>\n\
                <li class="hpat_nasty_fabric"><a href="javascript://" class="hpat3 hpat-default ' + ((js_theme_pattern_0 == 'nasty_fabric') ? 'active' : '') + '"></a></li>\n\
                <li class="hpat_natural_paper"><a href="javascript://" class="hpat4 hpat-default ' + ((js_theme_pattern_0 == 'natural_paper') ? 'active' : '') + '"></a></li>\n\
                <li><button class="btn btn-success btn-switch" id="_theme_header_save" title="Save"><span class="icon16 icomoon-icon-checkmark-2 white"></span></button></li>\n\
            </ul>\n\
        </div>\n\
        <h4>' + js_lang_label.GENERIC_SIDEBAR_PATTERNS + '</h4>\n\
        <div class="sidebar-patterns">\n\
            <ul>\n\
                <li><a href="javascript://" class="spat0 spat-default ' + ((js_theme_pattern_1 == '' || js_theme_pattern_1 == 'default') ? 'active' : '') + '"></a></li>\n\
                <li class="hpat_az_subtle"><a href="javascript://" class="spat1 spat-default ' + ((js_theme_pattern_1 == 'az_subtle') ? 'active' : '') + '"></a></li>\n\
                <li class="hpat_billie_holiday"><a href="javascript://" class="spat2 spat-default ' + ((js_theme_pattern_1 == 'billie_holiday') ? 'active' : '') + '"></a></li>\n\
                <li class="hpat_grey"><a href="javascript://" class="spat3 spat-default ' + ((js_theme_pattern_1 == 'grey') ? 'active' : '') + '"></a></li>\n\
                <li class="hpat_noise_lines"><a href="javascript://" class="spat4 spat-default ' + ((js_theme_pattern_1 == 'noise_lines') ? 'active' : '') + '"></a></li>\n\
                <li><button class="btn btn-success btn-switch" id="_theme_sidebar_save" title="Save"><span class="icon16 icomoon-icon-checkmark-2 white"></span></button></li>\n\
            </ul>\n\
        </div>\n\
        <h4>' + js_lang_label.GENERIC_BODY_PATTERNS + '</h4>\n\
        <div class="body-patterns">\n\
            <ul>\n\
                <li><a href="javascript://" class="bpat0 bpat-default ' + ((js_theme_pattern_2 == '' || js_theme_pattern_2 == 'default') ? 'active' : '') + '"></a></li>\n\
                <li class="hpat_cream_dust"><a href="javascript://" class="bpat1 bpat-default ' + ((js_theme_pattern_2 == 'cream_dust') ? 'active' : '') + '"></a></li>\n\
                <li class="hpat_dust"><a href="javascript://" class="bpat2 bpat-default ' + ((js_theme_pattern_2 == 'dust') ? 'active' : '') + '"></a></li>\n\
                <li class="hpat_grey"><a href="javascript://" class="bpat3 bpat-default ' + ((js_theme_pattern_2 == 'grey') ? 'active' : '') + '"></a></li>\n\
                <li class="hpat_subtle_dots"><a href="javascript://" class="bpat4 bpat-default ' + ((js_theme_pattern_2 == 'subtle_dots') ? 'active' : '') + '"></a></li>\n\
                <li><button class="btn btn-success btn-switch" id="_theme_body_save" title="Save"><span class="icon16 icomoon-icon-checkmark-2 white"></span></button></li>\n\
            </ul>\n\
        </div>\n\
    </div>\n\
    <div id="_theme_metronic_patterns" class="metronic-theme-patterns">\n\
        <h4>' + js_lang_label.GENERIC_THEME_COLOR + '</h4>\n\
        <div class="metronic-theme-colors">\n\
            <ul>\n\
                ' + themes_default_arr['metronic'] + '\n\
                <li><button class="btn btn-success btn-switch" id="_metronic_color_save" title="Save"><span class="icon16 icomoon-icon-checkmark-2 white"></span></button></li>\n\
            </ul>\n\
        </div>\n\
    </div>\n\
    <div id="_theme_cit_patterns" class="cit-theme-patterns">\n\
        <h4>Theme Color</h4>\n\
        <div class="cit-theme-colors">\n\
            <ul>\n\
                ' + themes_default_arr['cit'] + '\n\
                <li><button class="btn btn-success btn-switch" id="_cit_color_save" title="Save"><span class="icon16 icomoon-icon-checkmark-2 white"></span></button></li>\n\
            </ul>\n\
        </div>\n\
    </div>\n\
    ' + theme_custom_str + '\n\
</div>');
            },
            toggle: function () {
                if (el_tpl_settings.admin_theme == "metronic") {
                    $("#_theme_supr_patterns").hide();
                    $("#_theme_metronic_patterns").show();
                    $("#_theme_cit_patterns").hide();
                } else if (el_tpl_settings.admin_theme == "cit") {
                    $("#_theme_supr_patterns").hide();
                    $("#_theme_metronic_patterns").hide();
                    $("#_theme_cit_patterns").show();
                } else {
                    $("#_theme_supr_patterns").show();
                    $("#_theme_metronic_patterns").hide();
                    $("#_theme_cit_patterns").hide();
                }
            },
            apply: function (type, value) {
                var change_preferences = admin_url + "" + cus_enc_url_json["general_preferences_change"];
                if (confirm(js_lang_label.GENERIC_ARE_YOU_SURE_WANT_TO_APPLY_THESE_CHANGES)) {
                    $.ajax({
                        url: change_preferences,
                        type: 'POST',
                        data: {
                            'type': type,
                            'value': value
                        },
                        success: function (response) {
                            var res_arr = $.parseJSON(response);
                            var jmgcls = 1;
                            if (res_arr.success == "0") {
                                jmgcls = 0;
                            }
                            Project.setMessage(res_arr.message, jmgcls);
                            if (res_arr.success == '1') {
//                                if (confirm(js_lang_label.GENERIC_ARE_YOU_SURE_WANT_TO_RELOAD)) {
                                    document.location.reload();
//                                }
                            }
                        }
                    });
                }
            },
            init: function () {
                supr_switcher.create();
                if (el_tpl_settings.menu_poistion == "Top") {
                    $("#_theme_menu_top").attr("checked", true);
                } else {
                    $("#_theme_menu_left").attr("checked", true);
                }
                $("#_theme_change_select").val(el_tpl_settings.admin_theme);
                supr_switcher.toggle();

                $("#_theme_change_select").change(function () {
                    if ($(this).val() == el_tpl_settings.admin_theme) {
                        supr_switcher.toggle();
                    } else {
                        $("#_theme_supr_patterns").hide();
                        $("#_theme_metronic_patterns").hide();
                        $("#_theme_cit_patterns").hide();
                    }
                });

                $('#switcher .supr-theme-patterns a').on('click', function () {
                    if ($(this).hasClass('hpat-default')) {
                        $('#switcher .supr-theme-patterns a.hpat-default').removeClass('active');
                        $(this).addClass('active');
                        $('.top-bg').removeClass("bedge_grunge").removeClass("grid").removeClass("nasty_fabric").removeClass("natural_paper");
                        $("#_theme_header_save").attr("aria-pattern", "default");
                        if ($(this).hasClass('hpat1')) {
                            $('.top-bg').addClass("bedge_grunge");
                            $("#_theme_header_save").attr("aria-pattern", "bedge_grunge");
                        } else if ($(this).hasClass('hpat2')) {
                            $('.top-bg').addClass("grid");
                            $("#_theme_header_save").attr("aria-pattern", "grid");
                        } else if ($(this).hasClass('hpat3')) {
                            $('.top-bg').addClass("nasty_fabric");
                            $("#_theme_header_save").attr("aria-pattern", "nasty_fabric");
                        } else if ($(this).hasClass('hpat4')) {
                            $('.top-bg').addClass("natural_paper");
                            $("#_theme_header_save").attr("aria-pattern", "natural_paper");
                        }
                    } else if ($(this).hasClass('spat-default')) {
                        $('#switcher .supr-theme-patterns a.spat-default').removeClass('active');
                        $(this).addClass('active');
                        $('#sidebarbg').removeClass("az_subtle").removeClass("billie_holiday").removeClass("grey").removeClass("noise_lines");
                        $("#_theme_sidebar_save").attr("aria-pattern", "default");
                        if ($(this).hasClass('spat1')) {
                            $('#sidebarbg').addClass("az_subtle");
                            $("#_theme_sidebar_save").attr("aria-pattern", "az_subtle");
                        } else if ($(this).hasClass('spat2')) {
                            $('#sidebarbg').addClass("billie_holiday");
                            $("#_theme_sidebar_save").attr("aria-pattern", "billie_holiday");
                        } else if ($(this).hasClass('spat3')) {
                            $('#sidebarbg').addClass("grey");
                            $("#_theme_sidebar_save").attr("aria-pattern", "grey");
                        } else if ($(this).hasClass('spat4')) {
                            $('#sidebarbg').addClass("noise_lines");
                            $("#_theme_sidebar_save").attr("aria-pattern", "noise_lines");
                        }
                    } else if ($(this).hasClass('bpat-default')) {
                        $('#switcher .supr-theme-patterns a.bpat-default').removeClass('active');
                        $(this).addClass('active');
                        $('#content').removeClass("cream_dust").removeClass("dust").removeClass("grey").removeClass("subtle_dots");
                        $('#content_slide').removeClass("cream_dust").removeClass("dust").removeClass("grey").removeClass("subtle_dots");
                        $("#_theme_body_save").attr("aria-pattern", "default");
                        if ($(this).hasClass('bpat1')) {
                            $('#content').addClass("cream_dust");
                            $('#content_slide').addClass("cream_dust");
                            $("#_theme_body_save").attr("aria-pattern", "cream_dust");
                        } else if ($(this).hasClass('bpat2')) {
                            $('#content').addClass("dust");
                            $('#content_slide').addClass("dust");
                            $("#_theme_body_save").attr("aria-pattern", "dust");
                        } else if ($(this).hasClass('bpat3')) {
                            $('#content').addClass("grey");
                            $('#content_slide').addClass("grey");
                            $("#_theme_body_save").attr("aria-pattern", "grey");
                        } else if ($(this).hasClass('bpat4')) {
                            $('#content').addClass("subtle_dots");
                            $('#content_slide').addClass("subtle_dots");
                            $("#_theme_body_save").attr("aria-pattern", "subtle_dots");
                        }
                    }
                });
                $('#switcher .metronic-theme-patterns a').on('click', function () {
                    $('#switcher .metronic-theme-patterns a').removeClass('active');
                    $(this).addClass('active');
                    $("[aria-theme-style='metronic']").remove();
                    if ($(this).attr('aria-color')) {
                        var cus_clr = $(this).attr('aria-color')
                        var css_link = $("<link rel='stylesheet' aria-theme-style='metronic' type='text/css' href='" + style_url + "theme/metronic/theme_" + cus_clr + ".css'>");
                        $("body").append(css_link);
                        $("#_metronic_color_save").attr("aria-theme-color", cus_clr);
                    }
                });
                $('#switcher .cit-theme-patterns a').on('click', function () {
                    $('#switcher .cit-theme-patterns a').removeClass('active');
                    $(this).addClass('active');
                    $("[aria-theme-style='cit']").remove();
                    if ($(this).attr('aria-color')) {
                        var cus_clr = $(this).attr('aria-color')
                        var css_link = $("<link rel='stylesheet' aria-theme-style='cit' type='text/css' href='" + style_url + "theme/cit/theme_" + cus_clr + ".css'>");
                        $("body").append(css_link);
                        $("#_cit_color_save").attr("aria-theme-color", cus_clr);
                    }
                });
                $('#switcher .custom-theme-patterns a').on('click', function () {
                    $('#switcher .custom-theme-patterns a').removeClass('active');
                    $(this).addClass('active');
                    $("[aria-theme-style='custom']").remove();
                    if ($(this).attr('aria-color')) {
                        var cus_clr = $(this).attr('aria-color')
                        var css_link = $("<link rel='stylesheet' aria-theme-style='custom' type='text/css' href='" + style_url + "theme/" + cus_clr + ".css'>");
                        $("body").append(css_link);
                        $("#_custom_color_save").attr("aria-custom-color", cus_clr);
                    }
                });

                $('#switchBtn').on('click', function () {
                    if ($(this).hasClass('toggle')) {
                        //hide switcher
                        $(this).removeClass('toggle').css('right', '-1px');
                        $('#switcher').css('display', 'none');

                    } else {
                        //expand switcher
                        $(this).animate({
                            right: '210'
                        }, 200, function () {
                            // Animation complete.
                            $('#switcher').css('display', 'block');
                            $(this).addClass('toggle');
                            $('#shortcut-btn').css('z-index', '7999');
                        });
                    }
                });
                $("#_theme_menu_save").on('click', function () {
                    supr_switcher.apply("menu", $("input[type='radio'][name='_theme_menu_postion']:checked").val());
                });
                $("#_theme_change_save").on('click', function () {
                    supr_switcher.apply("theme", $("#_theme_change_select").val());
                });
                $("#_theme_header_save").on('click', function () {
                    supr_switcher.apply("header", $(this).attr("aria-pattern"));
                });
                $("#_theme_sidebar_save").on('click', function () {
                    supr_switcher.apply("sidebar", $(this).attr("aria-pattern"));
                });
                $("#_theme_body_save").on('click', function () {
                    supr_switcher.apply("body", $(this).attr("aria-pattern"));
                });
                $("#_metronic_color_save").on('click', function () {
                    supr_switcher.apply("color", $(this).attr("aria-theme-color"));
                });
                $("#_cit_color_save").on('click', function () {
                    supr_switcher.apply("color", $(this).attr("aria-theme-color"));
                });
                $("#_custom_color_save").on('click', function () {
                    supr_switcher.apply("custom", $(this).attr("aria-custom-color"));
                });
                $("#_custom_color_remove").on('click', function () {
                    supr_switcher.apply("custom", '');
                });
            }
        }
        supr_switcher.init();
    })();
}
function fullCollapseLeftMenu($this) {
    var mjson = animateSidePanel(false);
    if ($this.hasClass('hide')) {
        $this.css("z-index", "1000");
        //show sidebar
        $('#sidebarbg').animate({
            marginLeft: '0px'
        }, 500);
        $('#sidebar').animate({
            left: '0',
            marginLeft: '0px'
        }, 500);
        $('#main_content_div').animate({
            marginLeft: '210px'
        }, 250);
        $('#collapse_btn.leftbar').animate({
            left: mjson.mlef,
            top: mjson.mtop
        }, 500).removeClass('shadow');
        $("#left_mainnav").find(".menu-parent-anchor.active").addClass("drop");
        $this.removeClass('hide');
        $this.children('a').attr('title', js_lang_label.GENERIC_HIDE_SIDEBAR);
        initializeMenuCollpaseEvents();
        setTimeout(function () {
            resizeGridWidth();
            resizeDSGridWidth();
            adjustLeftMenuScrollBar();
            initNiceScrollBar();
        }, 501);
    } else {
        //hide sidebar
        var mjson = animateSidePanel(true);
        $this.css("z-index", "1000");
        $('#sidebarbg').animate({
            marginLeft: '-299px'
        }, 500);
        $('#sidebar').animate({
            marginLeft: '-299px'
        }, 500);
        $('#main_content_div').animate({
            marginLeft: '0px'
        }, 250);
        $('#collapse_btn.leftbar').animate({
            left: mjson.mlef,
            top: mjson.mtop
        }, 500).addClass('shadow');
        $this.addClass('hide');
        $this.children('a').attr('title', js_lang_label.GENERIC_SHOW_SIDEBAR);

        initializeMenuCollpaseEvents();
        hideLeftScrollBar();
        setTimeout(function () {
            resizeGridWidth();
            resizeDSGridWidth();
        }, 501);
    }
}
function semiCollapseLeftMenu($this) {
    if ($this.hasClass('hide')) {
        var mjson = animateSidePanel(false);
        $("#left_mainnav").removeClass('semi-collapse-border');
        $('#left_mainnav ul sub').removeClass('semi-item-show');
        $('#left_mainnav ul li').removeClass('semi-left-menu-items');
        $('#sidebar').removeClass("semi-collapse-menu");
        $("#sidebar_widget").find(".sidebar-navigation").css({'visibility': 'visible'});
        $this.css("z-index", "1000");
        //show sidebar
        $('#sidebar').animate({
            left: '0',
            marginLeft: '0px'
        }, 500);
        $('#sidebarbg').animate({
            marginLeft: '0px'
        }, 500);
        $('#sidebar_widget').animate({
            width: '100%'
        }, 150);
        $("#left_mainnav").animate({
            width: '210px'
        }, 500)
        $('#main_content_div').animate({
            marginLeft: '210px'
        }, 500);
        $('#collapse_btn.leftbar').animate({
            left: mjson.mlef,
            top: mjson.mtop
        }, 500).removeClass('shadow');

        $("#left_mainnav ul a").siblings().removeClass('semi-item-none');
        $('#left_mainnav ul li').removeClass('semi-left-menu-items');
        $("#left_mainnav").find(".menu-parent-anchor.active").addClass("drop");
        $this.removeClass('hide');
        $this.children('a').attr('title', js_lang_label.GENERIC_HIDE_SIDEBAR);
        initializeMenuCollpaseEvents();
        setTimeout(function () {
            resizeGridWidth();
            resizeDSGridWidth();
            adjustLeftMenuScrollBar();
            initNiceScrollBar();
        }, 501);
        $(document).off('mouseover', '.parent-menu-li.semi-left-menu-items');
    } else {
        var mjson = animateSidePanel(true);
        $("#left_mainnav").addClass('semi-collapse-border');
        $('#left_mainnav ul li.parent-menu-li').addClass('semi-left-menu-items');
        $("#left_mainnav ul a.menu-parent-anchor").siblings().addClass('semi-item-none');
        $('#sidebar').addClass("semi-collapse-menu");
        $("#sidebar_widget").find(".sidebar-navigation").css({'visibility': 'hidden'});
        $this.css("z-index", "1000");
        $('#sidebarbg').animate({
            marginLeft: '-299px'
        }, 500);
        $('#sidebar_widget').animate({
            width: '50px'
        }, 500);
        $("#left_mainnav").animate({
            width: '50px'
        }, 500);
        $('#main_content_div').animate({
            marginLeft: '50px'
        }, 500);
        $('#collapse_btn.leftbar').animate({
            left: mjson.mlef,
            top: mjson.mtop
        }, 500).addClass('shadow');
        $this.addClass('hide');
        $this.children('a').attr('title', js_lang_label.GENERIC_SHOW_SIDEBAR);
        initializeMenuCollpaseEvents();
        getLeftPanelSemiMenu();
        setTimeout(function () {
            resizeGridWidth();
            resizeDSGridWidth();
            adjustLeftMenuScrollBar();
        }, 501);
    }

}
function getLeftPanelSemiMenu() {
    if ($("#collapse_btn.leftbar").hasClass('hide')) {
        $(document).off('mouseover', '.parent-menu-li.semi-left-menu-items');
        $(document).on('mouseover', '.parent-menu-li.semi-left-menu-items', function () {
            if ($(this).find('a.menu-parent-anchor').hasClass('hasUl')) {
                var lm_top = $(this).offset().top - $(window).scrollTop();
                $(this).find('a.menu-parent-anchor').siblings().addClass('expand').addClass('semi-item-show').removeClass('semi-item-none');
                var lm_height = $(this).find('a').siblings().outerHeight();
                var lm_tot = lm_top + lm_height + 26, li_top, li_height;

                li_top = lm_top - 60;
                $(this).find('a.menu-parent-anchor').siblings().css({top: li_top + "px"});

                if (lm_tot > $(window).height() && $("#left_mainnav").hasClass("semi-collapse-border")) {
                    li_height = $(window).height() - lm_top - 27;
                    $(this).find('a.menu-parent-anchor').siblings().css({height: li_height + "px"});
                    scrollSubMenuContent();
                    //li_top = lm_top - lm_height - 11;
                } else {
                    $(this).find('a.menu-parent-anchor').siblings().css({height: "auto"});
                    $(".parent-menu-li>.sub").getNiceScroll().remove();
                    //li_top = lm_top - 60;
                }
                $("#left_mainnav").find('a.menu-parent-anchor.hasUl').removeClass("semi-menu-active");
                $(this).find('a.menu-parent-anchor.hasUl').addClass("semi-menu-active");
            }
        })
        hideLeftPanelSemiMenu();
    }
}
function hideLeftPanelSemiMenu() {
    $(document).off('mouseout', '.parent-menu-li.semi-left-menu-items');
    $(document).on('mouseout', '.parent-menu-li.semi-left-menu-items', function () {
        $('.semi-left-menu-items').find('a.menu-parent-anchor').siblings().removeClass('expand').addClass('semi-item-none').removeClass('semi-item-show');
        $('.semi-left-menu-items').find('a.menu-parent-anchor.hasUl').removeClass("semi-menu-active");
    });
}
function animateSidePanel(a) {
    var animate_json = {};
    switch (el_tpl_settings.admin_theme) {
        case "cit":
            if (a == true) {
                animate_json['mtop'] = 68;
                animate_json['mlef'] = 10;
            } else {
                animate_json['mtop'] = 68;
                animate_json['mlef'] = 175;
            }
            break;
        case 'metronic':
        default:
            if (a == true) {
                animate_json['mtop'] = 66;
                animate_json['mlef'] = 10;
            } else {
                animate_json['mtop'] = 66;
                animate_json['mlef'] = 175;
            }
            break;
    }
    return animate_json;
}
function getLocalStore(key, check) {
    if (!localStorage) {
        return '';
    }
    var data;
    if (check == true) {
        data = localStorage.getItem(key);
        try {
            data = checkStorageTimeStamp($.parseJSON(data), key);
        } catch (e) {
            data = data;
        }
    } else {
        data = localStorage.getItem(key);
    }
    return data;
}
function setLocalStore(key, data, check) {
    if (!localStorage) {
        return false;
    }
    var tmp, kmp;
    if (check == true) {
        tmp = localStorage.getItem(key)
        try {
            tmp = $.parseJSON(tmp);
        } catch (e) {
            tmp = tmp;
        }
        try {
            kmp = $.parseJSON(data);
            if (!$.isPlainObject(tmp) || !tmp.__timestamp) {
                data = addStorageTimeStamp($.parseJSON(data));
            } else {
                kmp.__timestamp = tmp.__timestamp;
                data = JSON.stringify(kmp);
            }
        } catch (e) {
            data = kmp;
        }
    }
    localStorage.setItem(key, data);
    return true;
}
function isLocalStorageAllow() {
    if (!el_tpl_settings.grid_search_prefers) {
        return false;
    } else {
        return true;
    }
}
function addStorageTimeStamp(data) {
    if (!$.isPlainObject(data)) {
        return data;
    }
    data.__timestamp = new Date().getTime();
    data = JSON.stringify(data);
    return data;
}
function checkStorageTimeStamp(data, key) {
    if (!$.isPlainObject(data)) {
        return data;
    }
    if (data.__timestamp) {
        var tdiff = moment(new Date().getTime()).diff(data.__timestamp);
        var tmins = moment.duration(tdiff).asMinutes();
        var texp = parseInt(el_tpl_settings.grid_search_expires);
        texp = $.isNumeric(texp) ? texp : 1440;
        if (tmins > texp) {
            data = {};
            localStorage.removeItem(key);
        }
    }
    data = JSON.stringify(data);
    return data;
}
function clearLocalStoreCache() {
    if (!localStorage) {
        return false;
    }
    var rem_arr = ['_sh', '_cw', '_cp', '_cs', '_sv', '_sp', '_sg_cw', '_sg_cp', '_sg_cs', '_ng_cw', '_ng_cp', '_ng_cs'];
    var pic_arr = Object.keys(localStorage);
    for (var i = 0; i < pic_arr.length; i++) {
        var key = pic_arr[i];
        if ($.inArray(key.slice(-3), rem_arr) != -1 || $.inArray(key.slice(-6), rem_arr) != -1) {
            localStorage.removeItem(key);
        }
    }
    return true;
}
function scrollSubMenuContent() {
    $(".parent-menu-li>.sub.semi-item-show").niceScroll({
        cursoropacitymax: 0.7,
        cursorborderradius: 8,
        cursorwidth: "2px",
        cursorcolor: "#636669"
    });
}
function adjustLeftMenuScrollBar() {
    if (!$("#left_mainnav").hasClass("semi-collapse-border")) {
        $(".parent-menu-li>.sub").getNiceScroll().remove();
        $(".parent-menu-li>.sub").css({height: "auto"});
        initLeftScrollBar();
    } else {
        hideLeftScrollBar();
    }
}
function jqueryUIdialogBox(label_elem, label_text, option_params) {
    var basic_params = {
        title: "Alert",
        autoOpen: true,
        bgiframe: true,
        modal: true,
        open: function (button_icons_arr) {
            applyUIButtonCSS();
        }
    }
    var final_params = $.extend({}, basic_params, option_params);
    $(label_elem).html(label_text).dialog(final_params);
}
function applyUIButtonCSS() {
    $('.ui-dialog-buttonset').find(':button').each(function () {
        $(this).addClass('fm-button ui-state-default ui-corner-all ui-dialog-button-hover');
        var bt_type = $(this).attr('bt_type'), icon_class;
        if (bt_type) {
            switch (bt_type) {
                case 'ok':
                case 'apply':
                case 'submit':
                    icon_class = 'ui-icon-check';
                    break;
                case 'cancel':
                    icon_class = 'ui-icon-cancel';
                    break;
                case 'delete':
                    icon_class = 'ui-icon-scissors';
                    break;
                case 'backup':
                    icon_class = 'ui-icon-disk';
                    break;
                case 'download':
                    icon_class = 'ui-icon-arrowthickstop-1-s';
                    break;
                case 'continue':
                    icon_class = 'ui-icon-arrowthickstop-1-e';
                    break;
                default:
                    icon_class = '';
                    break;
            }
            $(this).addClass('fm-button-icon-left');
            $(this).append('<span class="ui-button-icon-primary ui-icon ' + icon_class + '"></span>');
        }
    });
}

function closeWindow() {
    if (isFancyBoxActive()) {
        parent.$.fancybox.close();
    } else {
        window.location.hash = cus_enc_url_json['dashboard_sitemap'];
    }
}