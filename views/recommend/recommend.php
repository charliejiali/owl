<?php
$this->registerJsFile("/js/pages/recommend.js");
?>
<?= $this->render('../module/head_tag'); ?>
<div class="wrap">
    <div class="owl-mode">
        <?= $this->render('../module/mode') ?>
    </div>
    <div class="owl-content">
        <?= $this->render('../module/header') ?>
        <!--  必选条件  -->
        <div class="content">
            <div class="control-box" id="controlBox">
                <h3 class="title">必选条件</h3>
                <div class="pure-g-c">
                    <div class="pure-u-1-4">
                        <div class="pure-control-group">
                            <label for="name">年龄选择 &nbsp;</label>
                            <input id="ageMin" type="text" placeholder="" size="4"> --
                            <input id="ageMax" type="text" placeholder="" size="4">
                        </div>
                    </div>
                    <div class="pure-u-1-4">
                        <div class="pure-control-group">
                            <label for="password">性别比例 &nbsp;</label>
                            <input id="sexMen" type="number" min="1" max="99" placeholder="" size="4">
                            --
                            <input id="sexWomen" type="number" min="1" max="99" placeholder="" size="4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- page01 End -->
        <div class="content">
            <h3 class="title">地域选择</h3>
            <div id="areaList">
                <div class="select-tags">
                    当前选中地域：
                    <span id="areaTags">
                        <span class="select-tag">全国</a>
                        <!-- <span class="select-tag">北京<a href="#" class="ico-close"></a></span><span class="select-tag">上海<a href="#" class="ico-close"></a></span> -->
                    </span> 
                </div>
                <div class="select-list">
                    <div class="list-group" id="list" style="height:430px;">
                        <a href="#" class="list-group-item" data-value="北京">北京</a>
                        <a href="#" class="list-group-item" data-value="天津">天津</a>
                        <a href="#" class="list-group-item" data-value="上海">上海</a>
                        <a href="#" class="list-group-item" data-value="重庆">重庆</a>
                        <a href="#" class="list-group-item" data-value="河北">河北</a>
                        <a href="#" class="list-group-item" data-value="山西">山西</a>
                        <a href="#" class="list-group-item" data-value="辽宁">辽宁</a>
                        <a href="#" class="list-group-item" data-value="吉林">吉林</a>
                        <a href="#" class="list-group-item" data-value="黑龙江">黑龙江</a>
                        <a href="#" class="list-group-item" data-value="江苏">江苏</a>
                        <a href="#" class="list-group-item" data-value="浙江">浙江</a>
                        <a href="#" class="list-group-item" data-value="安徽">安徽</a>
                        <a href="#" class="list-group-item" data-value="福建">福建</a>
                        <a href="#" class="list-group-item" data-value="江西">江西</a>
                        <a href="#" class="list-group-item" data-value="山东">山东</a>
                        <a href="#" class="list-group-item" data-value="河南">河南</a>
                        <a href="#" class="list-group-item" data-value="湖北">湖北</a>
                        <a href="#" class="list-group-item" data-value="湖南">湖南</a>
                        <a href="#" class="list-group-item" data-value="广东">广东</a>
                        <a href="#" class="list-group-item" data-value="海南">海南</a>
                        <a href="#" class="list-group-item" data-value="四川">四川</a>
                        <a href="#" class="list-group-item" data-value="贵州">贵州</a>
                        <a href="#" class="list-group-item" data-value="云南">云南</a>
                        <a href="#" class="list-group-item" data-value="陕西">陕西</a>
                        <a href="#" class="list-group-item" data-value="甘肃">甘肃</a>
                        <a href="#" class="list-group-item" data-value="青海">青海</a>
                        <a href="#" class="list-group-item" data-value="内蒙古">内蒙古</a>
                        <a href="#" class="list-group-item" data-value="广西">广西</a>
                        <a href="#" class="list-group-item" data-value="西藏">西藏</a>
                        <a href="#" class="list-group-item" data-value="宁夏">宁夏</a>
                        <a href="#" class="list-group-item" data-value="新疆">新疆</a>
                        <a href="#" class="list-group-item" data-value="香港">香港</a>
                        <a href="#" class="list-group-item" data-value="澳门">澳门</a>
                        <a href="#" class="list-group-item" data-value="台湾">台湾</a>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="control-btns">
                <br>
                <button type="button" class="pure-btn btn-large btn-submit" id="confirm">确认提交</button>
                <!-- <button type="reset" class="pure-btn btn-large btn-reset">&nbsp; 返 回 &nbsp;</button>-->
            </div>
        </div>
        <!-- page01 End -->
        <?= $this->render('../module/footer') ?>
