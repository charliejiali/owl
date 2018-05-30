$.get('/top10/list',{type:type},function(json){
    if(json.r==1){
        var data=json.data;
        for(var i in data){
            var d=data[i];
            var div_pic=d.program_pic==''?'<div class="box-img no-img">':'<div class="box-img" style="background-image:url('+d.program_pic+') ">';

            $('#list').append(
                '<div class="pure-u-1-2">'
                +'        <div class="film-box-ten">'
                +'            <a href="javascript:;" id="'+d.program_id+'">'
                +'                <div class="box-img-mask">'
                +div_pic+'<p>'+d.program_name+'</p></div>'
                +'                </div>'
                +'                <div class="box-rate-c">'
                +'                    <p class="rate"><span class="NUM">'+(parseInt(i)+1)+'</span></p>'
                +'                    <p class="title">《'+d.program_name+'》</p>'
                +'                    <p class="note">'+d.platform_name+'-'+d.property_name+'</p>'
                +'                </div>'
                +'            </a>'
                +'        </div>'
                +'    </div>'
            );
        }
        $('h3.title').html(' '+json.type);
        $('.top-ten-title').html('<b>'+new Date().getFullYear()+' TOP10</b> '+(new Date().getMonth()+1)+'月排行榜');
    }else{
        __BDP.alertBox("提示",json.msg);
    }
},'json');

$('#list').on('click','a',function(){
    var program_id=$(this).attr('id');
    window.location.href='/program/result?mode_type=1&program_id='+program_id;
});
