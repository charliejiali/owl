<?php
$this->registerJsFile('/js/pages/login.js');
?>
<?= $this->render('../module/head_tag'); ?>
<div class="wrap-full index">
    <div class="logo"></div>
    <div class="login-box">
        <h2 class="title">用户登录</h2>
        <div class="form-login pure-form">
            <!-- form 拷贝的原有标签 -->
                <input id="email" type="email" placeholder="用户名/邮箱/手机号" class="input-label label-email">
                <input id="password" type="password" placeholder="密码" class="input-label label-passwd">
                <label for="remember" class="pure-checkbox">
                    <input id="remember" type="checkbox"> 自动登录
                </label>
                <p class="text-center">
                    <button type="button" class="pure-btn btn-large btn-submit" id="confirm">登 录</button>
                </p>
        </div>
    </div>
<?= $this->render('../module/footer') ?>
