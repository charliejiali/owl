<?php
$this->registerJsFile("/js/pages/details.js");
$system_platforms=$platforms;
$system_properties=$properties;
$system_times=$times;
?>
<?= $this->render('../module/head_tag'); ?>
<div class="wrap">
    <div class="owl-mode">
        <?= $this->render('../module/mode') ?>
    </div>
    <div class="owl-content">
        <?= $this->render('../module/header') ?>
        <div class="content">
            <h3 class="title">筛选条件</h3>
            <div class="form-eval">
                <div class="pure-g">
                    <div class="pure-u-1-3">
                        <label for="name">平　　台</label>
                        <select class="form-control" id="select_platform">
                            <option value="">全部</option>
                            <?php foreach($system_platforms as $platform){ ?>
                                <option value="<?= $platform["platform_name"];?>"><?= $platform["platform_name"];?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="pure-u-1-3">
                        <label for="phone">属　　性 </label>
                        <select class="form-control" id="select_property">
                            <option value="">全部</option>
                            <?php foreach($system_properties as $property){ ?>
                                <option value="<?= $property["property_name"];?>"><?= $property["property_name"];?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="pure-u-1-3">
                        <label for="position">开播时间</label>
                        <select class="form-control" id="select_time">
                            <option value="">全部</option>
                            <?php foreach($system_times as $time){ ?>
                                <option value="<?= $time["start_play"];?>"><?= $time["start_play"];?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="pure-u-1-6">
                <h3 class="title">可选剧目</h3></div>
            <div class="pure-u-1-3">
                <input id="program_name" type="text" class="input-label label-search" placeholder="请输入您要搜索的剧目名称">
            </div>
            <div class="result-list" id="resultList">
                <div class="pure-g " id="list">
                </div>
            </div>

            <!--        result-list  没找找到结果的提示,添加 tip-nothing 样式 -->
            <div id="no-result" class="result-list tip-nothing" style="display:none;">
                <p class="tip-ico-mark">根据您所选的限制条件，系统内暂时没有相对应的内容，请调整您的限制条件</p>
            </div>

        </div>
        <?= $this->render('../module/footer') ?>
