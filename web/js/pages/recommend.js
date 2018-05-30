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

    // $('.form-eval select').each(function (i, e) {
    //     selectbox(this);
    // });


    $(".select-list").mCustomScrollbar({
        axis: "y",
        theme: "dark"
    });

    $("#areaList").on("click", ".list-group-item", function (e) {
        e.preventDefault();

        if ($(this).hasClass("active")) {
            $(this).removeClass("active");
        } else {
            if (selectAreaArr.length >= 300) {
                return;
            }
            $(this).addClass("active");
        }
        checkAreaArr();
    });

    $("#areaList").on("click", ".ico-close", function (e) {
        e.preventDefault();
        var curTagValue = $(this).parent(".select-tag").data("value");
        var tagIndex = $.inArray(curTagValue, selectAreaArr);
        if (tagIndex >= 0) {
            selectAreaArr.splice(tagIndex, 1);
        }
        $("#areaList .list-group-item").each(function (i, j) {
            if ($(this).hasClass("active") && $(this).data("value") == curTagValue) {
                $(this).removeClass("active");
            }
        });
        checkAreaArr();
    });

    // 确认
    $('#confirm').on('click',function(){
        var input={};
        input.age_min=$('#ageMin').val();
        input.age_max=$('#ageMax').val();
        input.male=$('#sexMen').val();
        input.female=$('#sexWomen').val();
        input.province=selectAreaArr.length===0?'全国':selectAreaArr.join(',');

        //window.location.href='recommend_list.php?'+$.param(input);

        window.location.href='/recommend/main?'+ $.param(input);

        // $.get('ajax/recommend_get_program_list_url.php',{
        //     platform_name:$('#select_platform option:selected').val(),
        //     property_name:$('#select_property option:selected').val(),
        //     type_name:$('#select_type option:selected').val(),
        //     age_min:$('div[name="age_min"] input').val(),
        //     age_max:$('div[name="age_max"] input').val(),
        //     male:$('div[name="sex_male"] input').val(),
        //     female:$('div[name="sex_female"] input').val(),
        //     province:selectAreaArr.length===0?'全国':selectAreaArr.join(',')
        // },function(json){
        //   if(json.r==0){
        //     __BDP.alertBox("提示", json.msg);
        //   }else{
        //     window.location.href=json.url;
        //   }
        // },'json');
    });

});

var urlBase = 'ajax/';

var selectAreaArr = [];

function checkAreaArr() {
    selectAreaArr = [];
    var tagHtml = "";
    var tagValue = "";
    $("#areaList .list-group-item").each(function (i, j) {
        if ($(this).hasClass("active")) {
            tagValue = $(this).data("value");
            selectAreaArr.push(tagValue);
            tagHtml += '<span class="select-tag" data-value="' + tagValue + '">' + tagValue + '<a href="#" class="ico-close"></a></span>';
        }
    });
    if(selectAreaArr.length==0){
        tagHtml='<span class="select-tag">全国</a>';
    }
    console.log(selectAreaArr);

    $("#areaTags").html(tagHtml);
}
