<div class="win win-login" id="login">
    <!-- <div class="popup-close"></div>-->
    <div class="win-content">
        <h2>登 录</h2>
        <img src="images/user/login-icon.png" alt="" class="box-icon">
        <div class="login-form">
            <!-- form 拷贝的原有标签 -->
            <form class="pure-form" method="post" id="formLogin">
                <fieldset>
                    <div class="pure-control-group">
                        <input name="user" type="text" placeholder="手机 / 邮箱" size="36" dataType="Mobile" require="true">
                    </div>
                    <div class="pure-control-group">
                        <input name="password" type="password" placeholder="密 码" size="36" dataType="LimitB" require="true" min="6" max="20" msg="密码应为6-20位字符或数字组合，区分大小写">
                    </div>
                    <div class="pure-control-group">
                        <button type="submit" class="pure-btn btn-large btn-blue">登 录</button>
                    </div>
                    <div class="error-msg"></div>
                    <div class="pure-control-group" style="margin-top: 20px;">
                        新用户<span class="color-blue"><a href="javascript:__BDP_API.go_register();">注册</a></span> &nbsp; | &nbsp; <a href="javascript:__BDP_API.go_forgotPwd();">忘记密码</a>
                    </div>
                </fieldset>
            </form>
            <!-- form End -->
            <div class="clear"></div>
        </div>
    </div>
</div>