$(document).ready(function(){
    var match_weights,mode_type,system_weights;
    var radar_indicator=[];
    var radar_data=[];
    var radar_color=['#d92942', '#00b1f4', '#5247bd'];

    $.get('/program/get-result'+window.location.search,{

    },function(json){
        mode_type=json.mode_type;
        if(mode_type==2){match_weights=json.match_weights;}
        for(var i in json.data){
            var data = json.data[i];
            var alphabet= String.fromCharCode(97 + parseInt(i));
            create_compare_result(alphabet,data);
        }
        create_radar(get_radar_option(radar_indicator,radar_data,radar_color));
    },'json');

    // 恢复/取消对比按钮
    $("#result_list").on("click",".btn-compare", function (e) {
        e.preventDefault();
        var $filmBox = $(this).parents(".film-box-re");
        var color=$filmBox.attr('data')
        if (!$filmBox.hasClass("color-cancel")) {
            $filmBox.addClass("color-cancel");
            $(this).text("恢复对比");
            set_compare_disable(color,true);
        } else {
            $filmBox.removeClass("color-cancel");
            $(this).text("取消对比");
            set_compare_disable(color,false);
        }
    });

    $('#resultTab').on('click','a[name="tab"]',function(){
        window.open('/detail/view?name='+$(this).attr('data-name')+'&platform='+$(this).attr('data-platform'));
    })

    $('#compare').on('click',function(){
        var id=[];
        var params={};

        $('#result_list').find('div.film-box-re').not('.color-cancel').each(function(){
            id.push($(this).attr('p_id'));
        });
        if(id.length==0){
            __BDP.alertBox("提示","请至少选择一个剧目");
        }else if(id.length==3){
            __BDP.alertBox("提示","剧目数量不能超过3个");
        }else{
            id=id.join(',');
            if(mode_type==1){
                params.program_id=id;
                window.location.href='/evaluation/main?'+$.param(params);
            }else if(mode_type==2){
                params=match_weights;
                params.program_id=id;
                window.location.href='recommend_list.php?'+$.param(params);
            }
        }
    });
    // 设置对比显隐
    function set_compare_disable(color,disable){
        if(disable){
            $('.grade-table td.'+color).hide();
            $('.list-arrow li.'+color).hide();
        }else{
            $('.grade-table td.'+color).show();
            $('.list-arrow li.'+color).show();
        }
        disable_table_list(color,disable);

        var _data=make_new_array(radar_data);
        var _color=make_new_array(radar_color);
        $('.film-box-re').each(function(){
            if($(this).hasClass('color-cancel')){
                var index=$(this).parent().index();
                delete _data[index];
                delete _color[index];
            }
        });
        var _d=make_new_array(_data);
        var _c=make_new_array(_color);
        create_radar(get_radar_option(radar_indicator,_d,_c));
    }
    // 生成新数组
    function make_new_array(data){
        var re=[];
        for(var i in data){
            re.push(data[i]);
        }
        return re;
    }
    // 生成雷达图选项
    function get_radar_option(indicator,data,color){
        return {
            polar: [
                {
                    indicator: indicator
                }
            ],
            series: [
                {
                    type: 'radar',
                    itemStyle: {
                        normal: {
                            areaStyle: {
                                type: 'default'
                            },
                            lineStyle: {width: 0}
                        }
                    },
                    data: data
                }
            ],
            tooltip: {
                trigger: 'axis'
            },
            toolbox: {
                show: true
            },
            color: color

        };
    }
    // 生成对比内容
    function create_compare_result(a,d){
        create_tab(d);
        create_result(a,d);
        create_level2(a,d.level2);
        create_radar_data(d.program_name,d.level2);
        create_table_list(a,d);
    }
    function disable_table_list(color,disable){
        if(disable){
            $('#table_comment').find('th[name="'+color+'"]').hide();
            $('#table_comment').find('td[name="'+color+'"]').hide();
        }else{
            $('#table_comment').find('th[name="'+color+'"]').show();
            $('#table_comment').find('td[name="'+color+'"]').show();
        }
    }
    function create_tab(data){
        $('#resultTab').append('<a href="javascript:;" class="tab-item" name="tab" data-name="'+data.tensyn_name+'" data-platform="'+data.platform_name+'">'+data.program_name+'</a>');
    }
    function create_table_list(color,data){
        console.log(data);
        color='color-'+color;
        $('#tr_program_name').append('<th name="'+color+'" width="29%">'+data.program_name+'</th>');
        $('#td_recommend').append('<td name="'+color+'">'+data.level1.level.level+'：'+data.level1.level.recommend+'</td>');
        $('#td_score').append('<td name="'+color+'">总得分：'+data.level1.score+'</td>');
        $('#td_platform').append('<td name="'+color+'">'+data.platform_name+'</td>');
        $('#td_start_play').append('<td name="'+color+'">'+data.start_play+'</td>');
        $('#td_actor').append('<td name="'+color+'">'+data.actor+'</td>');
        $('#td_team').append('<td name="'+color+'">'+data.team+'</td>');
        $('#td_play1').append('<td name="'+color+'">'+data.play1+'</td>');

        var level2=data.level2;
        for(var i in level2){
            var _d=level2[i];
            if($('#table_tbody').find('tr[data="'+i+'"]').length==0){
                $('#table_tbody').append(
                    '<tr data="'+i+'">'
                    +'<td class="column">'+_d.name+'</td>'
                    +'<td name="'+color+'" class="desc-c">'
                    +'<div class="score">'+_d.score+'</div>'
                    +_d.comment
                    +'</td>'
                    +'</tr>'
                );
            }else{
                $('#table_tbody').find('tr[data="'+i+'"]').append(
                    '<td name="'+color+'" class="desc-c">'
                    +'<div class="score">'+_d.score+'</div>'
                    +_d.comment
                    +'</td>'
                );
            }
        }
    }
    // 生成剧目
    function create_result(a,d){
        var div_pic=$.trim(d.program_pic_src) != '' ?'<div class="box-img" style="background-image:url('+d.program_pic_src+')">'  : '<div class="box-img no-img">';
        $('#result_list').append(
            '<div class="pure-u-1-3">'
            +'    <div class="film-box-re color-'+a+'" data="color-'+a+'" p_id="'+d.program_id+'">'
            +'        <div class="box-img-mask">'
            +div_pic+     '<p>《'+d.program_name+'》</p></div>'
            +'        </div>'
            +'        <div class="box-rate-c">'
            +'            <p class="rate"><span class="NUM">'+d.level1.level.level+'</span>级</p>'
            +'            <p class="note"><span class="NUM">'+d.level1.score+'</span>分<br> '+d.level1.level.recommend+'</p>'
            +'            <p class="title">《'+d.program_name+'》</p>'
            +'        </div>'
            +'        <div class="box-content">'
            +'            <p class="info">'+d.platform_name+' / '+d.property_name+' / '+d.type_name+'</p>'
            +'            <p class="info"><a class="pure-btn btn-compare">取消对比</a></p>'
            +'        </div>'
            +'    </div>'
            +'</div>');
    }
    // 生成权重得分
    function create_level2(a,data) {
        for(var i in data){
            var d=data[i];
            if($('#'+i).length==0){
                $('.grade-table').append(
                    '<tr id="'+i+'">'
                    +'    <td>'+d.name+' :</td>'
                    +'    <td class="color-'+a+'">'+d.score+'</td><td class="color-'+a+'">'+d.level.level+'级 </td>'
                    +'</tr>'
                );
            }else{
                $('#'+i).append('<td> &nbsp; &nbsp; </td><td class="color-'+a+'">'+d.score+'</td><td class="color-'+a+'">'+d.level.level+'级 </td>');
            }
        }
    }
    // 生成雷达图数据
    function create_radar_data(program_name,data){
        if(radar_indicator.length==0){
            for (var i in data) {
                var d = data[i];
                var input = {};
                input.text = d.name;
                input.max = 4;
                radar_indicator.push(input);
            }
        }
        var value=[];
        for (var i in data) {
            var d = data[i];
            value.push(d.score);
        }
        var input={};
        input.value=value;
        input.name=program_name;
        console.log(input)
        radar_data.push(input);
    }
    // 生成雷达图
    function create_radar(option) {
        // 路径配置
        require.config({
            paths: {
                echarts: 'http://echarts.baidu.com/build/dist'
            }
        });
        // 使用
        require(
            [
                'echarts',
                'echarts/chart/radar'
            ],
            function (ec) {
                // 基于准备好的dom，初始化echarts图表
                var myChart = ec.init(document.getElementById('main'));
                // 为echarts对象加载数据
                myChart.setOption(option);
            }
        );
    }
})
