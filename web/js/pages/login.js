window.onload=function(){
    var data={
        email:'',
        password:'',
        auto_login:false
    }
    new Vue({
        el: '#login',
        data:data,
        methods:{
            login:function(){
                axios
                    .get('/site/login',{params:data})
                    .then(function(json){
                        console.log(json)
                        var data=json.data;
                        if (data.r == 1) {
                            window.location.href = '/site/mode';
                        }else if(data.r==-1){
                            //itensyn_login();
                        } else {
                            __BDP.alertBox("提示",data.msg);
                        }
                    });
            }
        }
    })
}



//$('document').ready(function(){
//    // 回车键
//    $('input').on('keydown',function(e){
//        if(e.keyCode==13){
//            login();
//        }
//    });
//    // 用户登录
//    $('#confirm').on('click', function () {
//        login();
//    });
//
//    function login(){
//        var input = {};
//        input.email = $('#email').val();
//        input.password = $('#password').val();
//        input.auto_login=$('#remember').is(':checked')?1:0;
//
//        $.get('/site/login', input, function (json) {
//            if (json.r == 1) {
//                window.location.href = '/site/mode';
//            }else if(json.r==-1){
//                //itensyn_login();
//            } else {
//                __BDP.alertBox("提示",json.msg);
//            }
//        }, 'json');
//
//        function itensyn_login(){
//            $.ajax({
//                url:'http://www.itensyn.com/api/check_user.php',
//                dataType:'jsonp',
//                data:{
//                    email:$('#email').val(),
//                    password:$('#password').val(),
//                    system_id:2
//                },
//                success:function(json){
//                    console.log(json);return false;
//                    if(json.r==0){
//                        __BDP.alertBox("提示",json.msg);
//                    }else{
//                        $.get('/site/itensyn-login',{user_id:json.user_id,token:json.token},function(json){
//                            if (json.r == 1) {
//                                window.location.href = '/site/mode';
//                            } else {
//                                __BDP.alertBox("提示",json.msg);
//                            }
//                        },'json');
//                    }
//                }
//            });
//        }
//    }
//});
