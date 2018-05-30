$(document).ready(function(){
    var program_id,mode_type,match_weights,system_weights;

    $.get('/program/get-result'+window.location.search,{

    },function(json){
        program_id=json.program_id;
        mode_type=json.mode_type;
        system_weights=json.system_weights;

        var data = json.data[0];
        $('#level').html(data.level1.level.level);
        $('#resultTab').append('<a href="javascript:;" class="tab-item" name="tab" data-name="'+data.tensyn_name+'" data-platform="'+data.platform_name+'">'+data.program_name+'</a>');

        var strRecommend = __BDP_C.strCut(data.platform_name + ' / ' + data.property_name + ' / ' + data.type_name, 35);
        $('#recommend').html(data.level1.level.recommend + ' ' + data.level1.score + '分<br>' + strRecommend);
        $('p[name="program"]').html('《'+data.program_name+'》');
        var img_url = $.trim(data.program_pic_src) != '' ?data.program_pic_src : '';
        if (img_url != '') {
            $('.box-img').attr('style', 'background-image:url("' + img_url + '") ')
        } else {
            $('.box-img').addClass("no-img");
        }
        create_level2(data.level2);
        create_image(data.level2);
        create_comment(data.level2);
        if(mode_type==1){
            $('#score_recommend').show();
            create_score_recommend(data.program_id, data.property_name, data.level1.score);
        }else{
            match_weights=json.match_weights
        }
    },'json');

    $('#resultTab').on('click','a[name="tab"]',function(){
        window.open('/detail/view?name='+$(this).attr('data-name')+'&platform='+$(this).attr('data-platform'));
    })

    // 对比
    $('#compare').on('click',function(){
        if(mode_type==1){
            window.location.href='/evaluation/main?program_id='+program_id;
        }else if(mode_type==2){
            var params=match_weights;
            params.program_id=program_id
            window.location.href='/recommend/main?'+$.param(params);
        }else{}
    })

    // 跳转到推荐剧目结果页面
    $('#recommend_list').on('click', 'a[id^="program_"]', function () {
        var id = $(this).attr('id').split('_')[1];
        window.location.href='/program/result?mode_type=1&program_id='+id;
    });
});

// 设置“根据分数推荐”模块
function set_recommend_module(show){
    if(show){
        $('#recommend_title').show();
        $('#recommend_list').show();
    }else{
        $('#recommend_title').hide();
        $('#recommend_list').hide();
    }
}
// 生成权重得分
function create_level2(data) {
    for (var i in data) {
        var d = data[i];

        $('.grade-table').append(
            '<tr>'
            + '    <td>' + d.name + ' :</td>'
            + '    <td>' + d.score + ' 分</td>'
            + '    <td>' + d.level.level + ' 级</td>'
            + '</tr>'
        );
    }
}
// 生成评语
function create_comment(data) {
    for (var i in data) {
        var _comment = data[i].comment;
        _comment = _comment.replace(/^　{1,3}/g, "");
        if (_comment != '') {
            $('.list-arrow').append('<li>' + _comment + '</li>');
        }
    }
    $('.list-arrow').append('<li>本次评估结果仅供参考，系统数据将定期更新，请及时关注评估结果变化。</li>')
}
// 生成雷达图
function create_image(data) {
    var indicator = [];
    var value = [];
    for (var i in data) {
        var d = data[i];

        var input = {};
        input.text = d.name;
        input.max = 4;
        indicator.push(input);
        value.push(d.score)
    }

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

            var option = {
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
                        data: [
                            {
                                name: "权重得分",
                                value: value,
                            }
                        ]
                    }
                ],
                tooltip: {
                    trigger: 'axis'
                },
                toolbox: {
                    show: true
                },
                color: ['#7577ca', '#ff7f50', '#87cefa', '#da70d6', '#32cd32', '#6495ed',
                    '#ff69b4', '#ba55d3', '#cd5c5c', '#ffa500', '#40e0d0',
                    '#1e90ff', '#ff6347', '#7b68ee', '#00fa9a', '#ffd700',
                    '#6b8e23', '#ff00ff', '#3cb371', '#b8860b', '#30e0e0']

            };
            // 为echarts对象加载数据
            myChart.setOption(option);
        }
    );
}
// 生成推荐剧目
function create_score_recommend(program_id) {
    $.get('/evaluation/get-recommend-programs', {id: program_id}, function (json) {
        if (!$.isEmptyObject(json)) {
            var data = json;

            for (var i in data) {
                var d = data[i];

                if (i == 0) {
                    $('#recommend_list').append(
                        '<a class="film-box-r a" id="program_' + d.program_id + '">'
                        + '    <div class="box-rate">'
                        + '        <p class="film-rate"><span class="NUM">' + d.level + '</span>级</p>'
                        + '    </div>'
                        + '    <div class="box-content">'
                        + '        <p class="title">《' + d.program_name + '》</p>'
                        + '        <p class="note">' + d.recommend + ' ' + d.score + ' 分 <br>'
                        + '            ' + d.platform_name + ' / ' + d.property_name + ' / ' + d.type_name + '</p>'
                        + '    </div>'
                        + '</a>'
                    );
                } else {
                    $('#recommend_list').append(
                        '<div class="film-box-space"></div>'
                        + '<a class="film-box-r" id="program_' + d.program_id + '">'
                        + '    <div class="box-rate">'
                        + '        <p class="film-rate"><span class="NUM">' + d.level + '</span>级</p>'
                        + '    </div>'
                        + '    <div class="box-content">'
                        + '        <p class="title">《' + d.program_name + '》</p>'
                        + '        <p class="note">' + d.recommend + ' ' + d.score + ' 分 <br>'
                        + '            ' + d.platform_name + ' / ' + d.property_name + ' / ' + d.type_name + '</p>'
                        + '    </div>'
                        + '</a>'
                    );
                }
            }
        } else {
            set_recommend_module(false);
        }
    }, 'json');
}
