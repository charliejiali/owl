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

    $(".result-list").mCustomScrollbar({
        axis: "y",
        theme: "dark"
    });

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

    $("#resultTab .tab-item").on("click", function (e) {
        e.preventDefault();
        $(this).siblings().removeClass("active");
        $(this).addClass("active");

        console.log($(this).data("include"));
        // setResultList();
        var pageInclude = $(this).data("include");
        if (pageInclude != undefined) {

            var pageInfo = pageInclude.split("_");
            if (pageInfo.length == 2 && pageInfo[0] == "details") {

                var includeUrl = "inc_details.php?id=" + pageInfo[1];
                $.get(includeUrl, function (data, status) {
                    console.log(data);
                    $("#resultPage").html(data);
                });

            }

        }

    });
});
