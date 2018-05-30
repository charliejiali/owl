/**
 * Created by ewen on 2016/5/12.
 */
var win = {};

$(document).ready(function () {
    onResize();
    $(window).bind('resize', onResize);

    function onResize() {
        win.w = $(window).width();
        win.h = $(window).height();
    }

    $(".film-box-re .btn-compare").on("click", function (e) {
        e.preventDefault();
        var $filmBox = $(this).parents(".film-box-re");
        if (!$filmBox.hasClass("color-cancel")) {
            $filmBox.addClass("color-cancel");
            $(this).text("恢复对比");
        } else {
            $filmBox.removeClass("color-cancel");
            $(this).text("取消对比");
        }
    });
});
