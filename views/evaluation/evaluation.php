<?php
$this->registerJs(" var program_id='".$program_id."';",\yii\web\View::POS_HEAD);
$this->registerJsFile("/js/pages/evaluate.js");

$select_platforms = $platforms;
$select_properties = $properties;
$select_types = $types;
$select_times = $times;
//
?>
<?= $this->render('../module/head_tag'); ?>
<div class="wrap">
    <div class="owl-mode">
        <?= $this->render('../module/mode') ?>
    </div>
    <div class="owl-content">
        <?= $this->render('../module/header') ?>
        <div class="content">
            <div class="pure-u-1-6">
                <h3 class="title">筛选条件</h3></div>
            <div class="pure-u-1-3">
                <input type="text" class="input-label label-search" placeholder="请输入您要搜索的剧目名称" id="search_value">
                <button type="button" class="pure-btn btn-small btn-submit" name="btn_search"> 搜 索</button>
            </div>
            <div class="pure-u-1-3">
                <div class="control-item" id="searchType">
                    <div class="control-label active" value="program_name"><span class="ico-checkbox">剧目名称</span></div>
                    <div class="control-label" value="type"><span class="ico-checkbox">内容类型</span></div>
                </div>
            </div>
            <br class="clear">
            <div class="form-eval">
                <div class="filter-box">
                    <label>媒体平台：</label>
                    <div class="filter-group" id="select_platform">
                        <?php foreach ($select_platforms as $platform) { ?>
                            <div class="filter-item" value="<?php echo $platform["platform_name"]; ?>"><?php echo $platform["platform_name"]; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="filter-box">
                    <label>内容属性：</label>
                    <div class="filter-group" id="select_property">
                        <?php foreach ($select_properties as $property) { ?>
                            <div class="filter-item" value="<?php echo $property["property_name"]; ?>"><?php echo $property["property_name"]; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="filter-box">
                    <label>一级类型：</label>
                    <a href="#" class="filter-item-more">更多</a>
                    <div class="filter-group" id="select_type">
                        <?php foreach ($select_types["type1"] as $type) { ?>
                            <div class="filter-item" value="<?php echo $type; ?>"><?php echo $type; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="filter-box">
                    <label>二级类型：</label>
                    <a href="#" class="filter-item-more">更多</span></a>
                    <div class="filter-group" id="select_type2">
                        <?php foreach ($select_types["type2"] as $type) { ?>
                            <div class="filter-item" value="<?php echo $type; ?>"><?php echo $type; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="filter-box">
                    <label>开播时间：</label> <a href="#" class="filter-item-more">更多</a>
                    <div class="filter-group" id="select_time">
                        <?php foreach ($select_times as $time) { ?>
                            <div class="filter-item" value="<?php echo $time["start_play"]; ?>"><?php echo $time["start_play"]; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="text-center">
                    <br>
                    <br>
                    <button type="button" class="pure-btn btn-large btn-submit" name="btn_search"> 确 定</button>
                    <br>
                </div>
            </div>
        </div>
        <div class="content">
            <div id="has-result">
                <div class="pure-u-1-6">
                    <h3 class="title">可选剧目</h3></div>
                <div class="result-tools"><label for="">显示条目：</label>
                    <div class="pure-btn-group" role="group" id="listNum">
                        <button class="pure-btn pure-btn-active" value="10">TOP10</button>
                        <button class="pure-btn" value="20">TOP20</button>
                        <button class="pure-btn" value="">全部</button>
                    </div>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label for="">排序：</label>
                    <div class="pure-btn-group" role="group" id="listOrder">
                        <button class="pure-btn  pure-btn-active" value="score">得分</button>
                        <button class="pure-btn" value="time">开播时间</button>
                    </div>
                </div>
                <div class="result-tags clear">
                    <h4 class="pull-left">已选剧目</h4>
                    <div id="resultTags"></div>
                </div>
                <div class="result-list" id="resultList">
                    <div>
                        <table class="pure-table pure-table-none pure-table-line">
                            <tbody id="table_body">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="text-center">
                    <br>
                    <br>
                    <button type="button" class="pure-btn btn-large btn-submit" id="btn_weights"> 确 定</button>
                    <br>
                </div>
            </div>
            <div id="no-result" class="result-list tip-nothing" style="display:none">
                <p class="tip-ico-mark">根据您所选的限制条件，系统内暂时没有相对应的内容，请调整您的限制条件</p>
            </div>
        </div>
        <div class="content">
            <div class="control-box" id="controlBox" style="display:none;">
                <h3 class="title">权重配比</h3>
                <div class="control-num">当前权重：<span class="NUM">100%</span></div>
                <br><br>
                <div class="pure-g-c" id="weights">
                </div>
                <div class="clear"></div>
                <div class="control-btns">
                    <br>
                    <button type="button" class="pure-btn btn-large btn-submit" id="confirm">确认提交</button>
                </div>
            </div>
        </div>
        <?= $this->render('../module/footer') ?>
