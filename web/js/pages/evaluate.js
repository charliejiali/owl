/**
 * Created by ewen on 2016/5/12.
 */
var win = {};

$(document).ready(function () {
    var selectResultArr = [];
    var mode_type=1;

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


    // 权重radio
    $("#controlBox").on("click", '.control-label', function (e) {
        e.preventDefault();
        if (!$(this).parent().hasClass("disabled")) {
            if ($(this).hasClass("active")) {
                $(this).removeClass("active");
                var box=$(this).parent().find('.control-slider');  
                var name=box.find('input').attr('name');
                box.empty();
                box.append('<input class="bubble-slider" name="' + name + '" placeholder="0 - 100" type="number" min="0" max="100" value="0">');
                box.find('input[name="'+name+'"]').bubbleSlider(); 
            } else {
                $(this).addClass("active");
            }
            checkControlNum();
        }
    });
    
    // 权重滚动条
    $("#controlBox").on("mouseup click", '.control-slider', function (e) {
        checkControlNum();
    });
    $('body').not('.control-slider').on('mouseup', function () {
        checkControlNum();
    })

    $("#resultList").on("click",'.list-item', function (e) {
        e.preventDefault();
        if($(e.target).hasClass('item-more')){return false;}

        var id=$(this).attr('data-id');

        if ($(this).hasClass("active")) {
            if(program_id!=''&&id==program_id){return false;}
            $(this).removeClass("active");
            del_tag(id);
        } else {
            if (selectResultArr.length >= 3) {
                return;
            }
            $(this).addClass("active");
            add_tag(id,$(this).find('td .title').text());
        }
        $('#weights').empty();
        $('#controlBox').hide();
    });

    function add_tag(id,name){
        selectResultArr.push(id);
        $("#resultTags").append('<span class="result-tag" data-id="' + id + '">' + name + '<a href="#" class="ico-close"></a></span>');
    }
    function del_tag(id){
        del_id(selectResultArr,id);
        $('#resultTags span[data-id="'+id+'"]').remove();
    }
    function del_id(arr, val) {
      for(var i=0; i<arr.length; i++) {
        if(arr[i] == val) {
          arr.splice(i, 1);
          break;
        }
      }
    }

    $("#resultList .item-more").on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
    });


    $("#resultTags").on("click", ".ico-close", function (e) {
        e.preventDefault();

        var curTagID = $(this).parent(".result-tag").data("id");
        // var tagIndex = $.inArray(curTagID, selectResultArr);

        del_tag(curTagID);
        $("#resultList .list-item").each(function (i, j) {
            if ($(this).hasClass("active") && $(this).data("id") == curTagID) {
                // if(program_id!=''&&curTagID==program_id){return false;}
                $(this).removeClass("active"); 
            }
        });
        $('#weights').empty();
        $('#controlBox').hide();

        console.log(selectResultArr)
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


    $("button[name='btn_search']").on("click", function (e) {
        e.preventDefault();
        getFilterData();
        get_program_list();
    });

    $(".pure-btn-group .pure-btn").on("click", function (e) {
        e.preventDefault();
        $(this).siblings().removeClass("pure-btn-active");
        $(this).addClass("pure-btn-active");

        setResultList();
        get_program_list();
    });

    //设置过滤参数
    function getFilterData() {
        var select_platform = getFilterItem("select_platform");
        var select_property = getFilterItem("select_property");
        var select_type = getFilterItem("select_type");
        var select_type2 = getFilterItem("select_type2");
        var select_time = getFilterItem("select_time");
        var select_score = getFilterItem("select_score");
        console.log("=============过滤参数==============");
        console.log("播出平台：", select_platform);
        console.log("内容属性：", select_property);
        console.log("一级类型：", select_type);
        console.log("二级类型：", select_type2);
        console.log("开播时间：", select_time);
        console.log("得分：", select_score);
        console.log("============== END =============");
    }

    function getFilterItem(id) {
        var valueArr = [];
        $("#" + id).find(".filter-item.on").each(function (i, e) {
            valueArr.push($(this).attr("value"));
        });
        return valueArr.join(",")
    }


    //设置输出结果
    function setResultList() {

        var listNum = getGroupVal("listNum");
        var listOrder = getGroupVal("listOrder");

        console.log("输出结果设置：", listNum, listOrder);

    }

    function getGroupVal(id) {
        return $("#" + id).find(".pure-btn-active").attr("value");
    }

    $('#table_body').on('click','.item-more', function (e) {
        e.preventDefault();

        $.get('/program/get-intro',{
            program_id:$(this).attr('data-id')
        },function(json){
            __BDP.alertBox("内容简介", json.msg);
        },'json');
    });


    $("#searchType .control-label").on("click", function (e) {
        e.preventDefault();
        $(this).siblings().removeClass("active");
        $(this).addClass("active");
    });

    var compare_id=[];
    if(program_id!=''){
        compare_id=program_id.split(',');
        $('#listNum').find('button.pure-btn-active').removeClass('pure-btn-active');
        $('#listNum').find('button[value=""]').addClass('pure-btn-active');
    }

    get_program_list();

    $('#search_value').on('keypress',function(e){
        if(e.keyCode==13){
            get_program_list();
        }
    }); 

    function get_program_list(){
        var filters={};

        if($('#searchType').find('div.active').attr('value')=="program_name"){
            filters.program_name=$('#search_value').val();
        }else{
            filters.type_name=$('#search_value').val();
        }

        filters.platform_name = getFilterItem("select_platform");
        filters.property_name = getFilterItem("select_property");
        filters.type1 = getFilterItem("select_type");
        filters.type2 = getFilterItem("select_type2");
        filters.start_play = getFilterItem("select_time");

        $.get('/evaluation/list',{
            top:getGroupVal("listNum"),
            sort:getGroupVal("listOrder"),
            filters:filters
        },function(json){
            make_program_list(json.data);

            if(compare_id.length>0){
                for(var i in compare_id){
                    $('#table_body tr[data-id="' + compare_id[i] + '"]').trigger('click');
                }
                compare_id=[];
            }

            $('#resultTags .result-tag').each(function(){
                var id=$(this).attr('data-id');
                if(!$('#table_body tr[data-id="'+id+'"]').hasClass('active')){
                    $('#table_body tr[data-id="'+id+'"]').addClass('active');
                }
            });
            
        },'json');
    }

    function make_program_list(data){
        $('#table_body').empty();

        if(data.length==0){
            $('#no-result').show();
            $('#has-result').hide();
        }else{
            $('#has-result').show();
            $('#no-result').hide();
            for(var i in data){
                var d=data[i];
                $('#table_body').append(
                    '<tr class="list-item" data-id="'+d.program_id+'">'
                    +    '<td>'+(parseInt(i)+1)+'、《<span class="title">'+d.program_name+'</span>》</td>'
                    +    '<td>'+d.platform_name+'</td>'
                    +    '<td>总评得分：'+d.program_score+'</td>'
                    +    '<td>'+d.time+'</td>'
                    +    '<td><a href="#" class="item-more" data-id="'+d.program_id+'">内容简介</a></td>'
                    +'</tr>'
                );
            }
        }
    }
    $('#btn_weights').on('click',function(){
        $('#weights').empty();
        $.get('/program/get-weights',{
            mode_type:mode_type,
            ids: selectResultArr.join(',')
        }, function (json) {
            if (!$.isEmptyObject(json)) {
                    
                $('#controlBox').show();

                make_regular_row(json);

                $('#weights').append(
                    '<div class="pure-u-1-4">'
                    + '<div class="txt-comment">'
                    + '<br> &nbsp; 提示：权重总和只限100%'
                    + '</div>'
                    + '</div>'
                );

                $('input.bubble-slider').not('.no-use').each(function () {
                    $(this).bubbleSlider();
                });
                $('input.bubble-slider.no-use').each(function () {
                    $(this).bubbleSlider({
                        toggleBubble: false,
                        thumbScale: 0
                    });
                });

                checkControlNum();

                $('div.bubble-slider-wrap[style*="margin"]').each(function () {
                    $(this).attr('style', '');
                })
            } else {
                $('#controlBox').hide();
                __BDP.alertBox("提示", '未能获取有效的权重');
            }
        }, 'json');
    });
    // 正常生成权重
    function make_regular_row(json) {
        for (var i in json) {
            var d = json[i];

            var active='active';
            var value=d.value;
            var disabled='';
            var no_use='';
 
            if(value==''){
                active='';
                disabled='disabled';
                value='0';
                no_use='no-use';
            }
        
            $('#weights').append(
                '<div class="pure-u-1-4">'
                + '<div class="control-item '+disabled+'">'
                + '<div class="control-label '+active+'"><span class="ico-checkbox">' + d.name + '</span></div>'
                + '<div class="control-slider">'
                + '<input class="bubble-slider '+no_use+'" name="' + d.html_id + '" placeholder="0 - 100" type="number" min="0" max="100" value="' + value + '">'
                + '</div>'
                + '</div>'
                + '</div>'
            );
        }
    }
    // 确认
    $('#confirm').on('click', function () {
        var input={};
        input.mode_type=mode_type;
        input.program_id = selectResultArr.join(',');

        $("#controlBox .control-item").each(function (i, j) {
            if ($(this).find(".control-label").eq(0).hasClass("active")) {
                input[$(this).find("input.bubble-slider").eq(0).attr('name')] = $(this).find("input.bubble-slider").eq(0).val();
            }
        });

        if(selectResultArr.length===1){
            //window.open('program_result.php?'+$.param(input));
            window.open('/program/result?'+$.param(input));
        }else{
            window.open('/program/compare-result?'+$.param(input));
        }
    });
});

var urlBase = 'ajax/';
//var urlBase = 'http://www.itensyn.com/ajax/';

// var selectResultArr = [];

function checkControlNum() {
    var tmpNum = 0;
    $("#controlBox .control-item").each(function (i, j) {
        if ($(this).find(".control-label").eq(0).hasClass("active")) {
            tmpNum += parseInt($(this).find("input.bubble-slider").eq(0).val());
        }
    });
    $("#controlBox .NUM").html(tmpNum + "%");
}

function checkResultArr() {
    selectResultArr = [];
    var tagHtml = "";
    var tagID = 0;
    $("#resultList .list-item").each(function (i, j) {
        if ($(this).hasClass("active")) {
            tagID = $(this).data("id");
            selectResultArr.push(tagID);
            tagHtml += '<span class="result-tag" data-id="' + tagID + '">' + $(this).find(".title").text() + '<a href="#" class="ico-close"></a></span>';
        }
    });
    console.log(selectResultArr);

    $("#resultTags").html(tagHtml);
}
