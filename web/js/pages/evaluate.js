/**
 * Created by zhangjiali on 2018/6/15.
 */

var url=new URL(window.location.toString());
var compare_ids=url.searchParams.get('program_id');
compare_ids=compare_ids!==null?compare_ids.split(','):[];
console.log(compare_ids)

var filter_data={
    platforms:[],
    properties:[],
    type1:[],
    type2:[],
    times:[],
    filter_open:{
        type1:false,
        type2:false,
        times:false
    },
    selected:{
        platforms:{},
        properties:{},
        type1:{},
        type2:{},
        times:{},
    },
    name_type:{
        program_name:true,
        type_name:false,
        value:''
    }
};
var list_data={
    list:[],
    total:0,
    selected:{}
};
var top_data={
    top10:compare_ids===null?true:false,
    top20:false,
    topall:compare_ids===null?false:true,
}
var sort_data={
    score:true,
    time:false,
};
var params={
    top:compare_ids===null?"10":"",
    sort:"score",
    filters: {
        program_name:"",
        type_name:"",
        platform_name: "",
        property_name: "",
        type1: "",
        type2: "",
        start_play: "",
    }
};

document.addEventListener('DOMContentLoaded',function(){
    onResize();
    $(window).bind('resize', onResize);

    // 获取标签选项
    axios.get('/evaluation/get-filters',{params:{}})
        .then(function(json){
            var data=json.data;
            // 初始化标签
            filter_data.platforms=data.platforms;
            filter_data.properties=data.properties;
            filter_data.type1=data.types.type1;
            filter_data.type2=data.types.type2;
            filter_data.times=data.times;
            for(var i in data.platforms){
                filter_data.selected.platforms[data.platforms[i].platform_name]=false;
            }
            for(var i in data.properties){
                filter_data.selected.properties[data.properties[i].property_name]=false;
            }
            for(var i in data.types.type1){
                filter_data.selected.type1[data.types.type1[i]]=false;
            }
            for(var i in data.types.type2){
                filter_data.selected.type2[data.types.type2[i]]=false;
            }
            for(var i in data.times){
                filter_data.selected.times[data.times[i].start_play]=false;
            }
            // 生成标签
            new Vue({
                el:"#table_filter",
                data:filter_data,
                methods:{
                    open_box:function(param){
                        this.filter_open[param]=this.filter_open[param]===false?true:false;
                    },
                    select:function(type,name){
                        this.selected[type][name]=this.selected[type][name]?false:true;
                    },
                    select_type:function(name){
                        if(name=='program_name'){
                            this.name_type.program_name=true;
                            this.name_type.type_name=false;
                        }else{
                            this.name_type.program_name=false;
                            this.name_type.type_name=true;
                        }
                    },
                    get_list:function(){
                        get_program_list();
                    }
                }
            });
            get_program_list(compare_ids);
        });
    // 列表
    window.table_list=new Vue({
        el:"#table_list",
        data:list_data,
        methods:{
            intro:function(id){
                axios.get('/program/get-intro',{params:{
                    program_id:id
                }}).then(function(json){
                    __BDP.alertBox("内容简介", json.data.msg);
                });
            },
            select:function(id,name){
                $('#weights').empty();
                $('#controlBox').hide();

                if(!this.selected.hasOwnProperty(id)&&Object.keys(this.selected).length===3){
                    __BDP.alertBox("提示","最多只能选三个剧目");
                }else{
                    if(!this.selected.hasOwnProperty(id)){
                        Vue.set(this.selected,id,name);
                    }else{
                        if(!compare_ids.includes(id)) {
                            Vue.delete(this.selected, id);
                        }
                    }
                }
            },
            delete_tag:function(id){
                if(!compare_ids.includes(id)) {
                    $('#weights').empty();
                    $('#controlBox').hide();
                    Vue.delete(this.selected, id);
                }
            }
        }
    });
    // 排序
    // top
    window.table_top=new Vue({
        el:'#table_top',
        data:top_data,
        methods:{
            get_list:function(top){
                for(var i in top_data){
                    top_data[i]=top==i?true:false;
                }
                switch(top){
                    case 'top10':
                       params.top='10';
                        break;
                    case 'top20':
                        params.top='20';
                        break;
                    case 'topall':
                        params.top='';
                        break;
                }
                get_program_list();
            }
        }
    });
    // 时间/得分
    window.table_sort=new Vue({
        el:"#table_sort",
        data:sort_data,
        methods:{
            get_list:function(sort){
                for(var i in sort_data){
                    sort_data[i]=sort==i?true:false;
                }
                params.sort=i;
                get_program_list();
            }
        }
    });


    // 剧目列表滚动条
    $(".result-list").mCustomScrollbar({
        axis: "y",
        theme: "dark"
    });
    // 获取剧目权重
    $('#btn_weights').on('click',function(){
        $('#weights').empty();

        var program_ids=[];
        for(var i in list_data.selected){
            program_ids.push(i);
        }
        program_ids=program_ids.join(',');

        $.get('/program/get-weights',{
            mode_type:1,
            ids: program_ids
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
                });
                $('html, body').animate({
                    scrollTop: $("#controlBox").offset().top
                }, 500);
            } else {
                $('#controlBox').hide();
                __BDP.alertBox("提示", '未能获取有效的权重');
            }
        }, 'json');
    });
    // 权重radio
    $("#controlBox").on("click", '.control-label', function (e) {
        e.preventDefault();
        if (!$(this).parent().hasClass("disabled")) {
            if ($(this).hasClass("active")) {
                $(this).removeClass("active");
                var box = $(this).parent().find('.control-slider');
                var name = box.find('input').attr('name');
                box.empty();
                box.append('<input class="bubble-slider" name="' + name + '" placeholder="0 - 100" type="number" min="0" max="100" value="0">');
                box.find('input[name="' + name + '"]').bubbleSlider();
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
    });
    // 确认
    $('#confirm').on('click', function () {
        var program_ids=[];
        for(var i in list_data.selected){
            program_ids.push(i);
        }

        var input={};
        input.mode_type=1;
        input.program_id = program_ids.join(',');

        $("#controlBox .control-item").each(function (i, j) {
            if ($(this).find(".control-label").eq(0).hasClass("active")) {
                input[$(this).find("input.bubble-slider").eq(0).attr('name')] = $(this).find("input.bubble-slider").eq(0).val();
            }
        });

        if(program_ids.length===1){
            window.open('/program/result?'+$.param(input));
        }else{
            window.open('/program/compare-result?'+$.param(input));
        }
    });
},false);

var win={};
function onResize() {
    win.w = $(window).width();
    win.h = $(window).height();
}
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
// 获取剧目列表
function get_program_list(){
    var arg=arguments[0];
    console.log(arg)

    var p={}
    for(var i in filter_data.selected){
        p[i]=[];
        var data=filter_data.selected[i];
        for(var j in data){
            if(data[j]==true){
                p[i].push(j);
            }
        }
    }

    params.filters.platform_name= p.platforms.join(',');
    params.filters.property_name= p.properties.join(',');
    params.filters.type1= p.type1.join(',');
    params.filters.type2= p.type2.join(',');
    params.filters.start_play= p.times.join(',');

    if(filter_data.name_type.program_name==true){
        params.filters.program_name=filter_data.name_type.value;
        params.filters.type_name='';
    }else{
        params.filters.type_name=filter_data.name_type.value;
        params.filters.program_name='';
    }

    axios.get('/evaluation/list',{params:params}).then(function(json){
        list_data.list=json.data.data;
        list_data.total=json.data.total;
        if(json.data.data.length==0){
            $('#has-result').hide();
            $('#no-result').show();
        }else{
            $('#has-result').show();
            $('#no-result').hide();
        }

        if(arg.length>0){
            for(var i in list_data.list){
                if(arg.includes(list_data.list[i].program_id)){
                    list_data.selected[list_data.list[i].program_id]=list_data.list[i].program_name;
                }
            }
        }
    });
}

function checkControlNum() {
    var tmpNum = 0;
    $("#controlBox .control-item").each(function (i, j) {
        if ($(this).find(".control-label").eq(0).hasClass("active")) {
            tmpNum += parseInt($(this).find("input.bubble-slider").eq(0).val());
        }
    });
    $("#controlBox .NUM").html(tmpNum + "%");
}

