<div class="win win-login" id="login">
    <!-- <div class="popup-close"></div>-->
    <div class="win-content">
        <h2>找回密码</h2>
        <!--        <img src="images/user/login-icon.png" alt="" class="box-icon">-->
        <div class="login-form">
            <p class="space">* 请输入你需要找回登录密码的账户名</p>
            <!-- form 拷贝的原有标签 -->
            <form class="pure-form" method="post" id="formLogin">
                <fieldset>
                    <div class="pure-control-group">
                        <input name="user" type="text" placeholder="手机 / 邮箱" size="36" dataType="Mobile" require="true">
                    </div>
                    <!--div class="pure-control-group">
                        <input name="authcode" type="text" placeholder="输入验证码" size="36" dataType="LimitB" require="true" min="6" max="6" msg="验证码应为6位字符或数字组合">
                    </div-->
                    <div class="pure-control-group" style="margin-top: 25px;">
                        <button type="submit" class="pure-btn btn-large btn-blue">确 定</button>
                    </div>
                    <div class="error-msg"></div>

                    <div class="pure-control-group" style="margin-top: 20px;">
                        <br>
                    </div>
                </fieldset>
            </form>
            <!-- form End -->
            <div class="clear"></div>
        </div>
    </div>
</div>