<?php
$this->beginPage();
$this->head();
$this->registerJs("
    $('#reset_password').on('click',function(){
        var input={};

        input.old=$('#password').val();
        input.new=$('#passwordNew').val();
        input.confirm=$('#passwordNewR').val();

        $.post('/site/update-password',input,function(json){
            alert(json.msg);
            if(json.r==1){
                $.magnificPopup.close();
            }
        },'json')
    });
",\yii\web\View::POS_HEAD);
$this->beginBody();
$this->endBody();
$this->endPage();
?>
<div class="win win-form" id="pwdChange">
    <h3 class="heading">修改密码</h3>
    <div class="win-content">
        <div class="box-form">
            <p class="space">* 请输入你的当前密码和新密码！</p>
            <!-- form 拷贝的原有标签 -->
            <div class="pure-form pure-form-aligned" >
                <fieldset>
                    <div class="pure-control-group">
                        <label for="password">当前密码</label>
                        <input name="password" id="password" type="password" placeholder="" size="24" dataType="LimitB" require="true" min="6" max="20" msg="密码应为6-20位字符或数字组合，区分大小写">
                    </div>

                    <div class="pure-control-group">
                        <label for="passwordNew">新密码</label>
                        <input name="passwordNew" id="passwordNew" type="password" placeholder="" size="24" dataType="LimitB" require="true" min="6" max="20" msg="密码应为6-20位字符或数字组合，区分大小写">
                    </div>

                    <div class="pure-control-group">
                        <label for="passwordNewR">确认密码</label>
                        <input name="passwordNewR" id="passwordNewR" type="password" placeholder="" size="24" dataType="Repeat" to="passwordNew" require="true" min="6" max="20" msg="重复输入的新密码不一致">
                    </div>

                    <div class="pure-control-group" style="margin: 30px auto;">
                        <button type="button" class=" btn-large btn-red" id="reset_password">确定提交</button>
                    </div>
                    <div class="error-msg"></div>
                </fieldset>
            </div>
            <!-- form End -->
            <div class="clear"></div>
        </div>
    </div>
</div>

