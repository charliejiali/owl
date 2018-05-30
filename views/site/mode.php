<?= $this->render('../module/head_tag'); ?>
<div class="wrap-full index mode">
    <div class="logo"></div>
    <div class="pure-menu pure-menu-horizontal menu-user">
        <ul class="pure-menu-list">
            <li class="pure-menu-item pure-menu-selected"><div href="#" class="pure-menu-link"><span class="icon-tools ico-avatar"></span><?php echo $this->context->user_name ;?></div>
            </li>
            <li class="pure-menu-item"><span class="nav-line">&nbsp;</span></li>
            <li class="pure-menu-item"><a href="#" class="pure-menu-link btn-pwd-change">密码修改</a></li>
            <li class="pure-menu-item"><a href="#" class="pure-menu-link btn-logout"><span class="icon-tools ico-logout"></span>退出</a></li>
        </ul>
    </div>
    <div class="login-box">
        <a href="/evaluation/main"><img src="/images/btn_mode_eva.png" alt=""></a><br><br>
        <a href="/recommend/match"><img src="/images/btn_mode_rem.png" alt=""></a>
    </div>
<?= $this->render('../module/footer') ?>
