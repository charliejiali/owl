<?php
$this->registerJsFile("/js/pages/program_result.js",['depends'=>'yii\web\YiiAsset','position'=>\yii\web\View::POS_HEAD]);
$this->registerJsFile("/echarts/build/dist/echarts.js");
echo $this->render('../module/head_tag');
?>
<!-- Header Start -->
<div class="wrap">
    <div class="owl-mode">
        <?= $this->render('../module/mode') ?>
    </div>
    <div class="owl-content">
        <?= $this->render('../module/header') ?>
        <div class="content-header" id="resultTab">
            <a href="javascript:;" class="tab-item active">详细结果</a>
        </div>
        <div class="content">
            <h3 class="title">评估结果</h3>
            <div class="film-box">
                <div class="box-img-mask">
                    <div class="box-img"><p name="program"></p></div>
                </div>
                <div class="box-content">
                    <p class="film-rate"><span class="NUM" id="level"></span>级</p>
                    <p class="title" name="program"></p>
                    <p class="note" id="recommend"></p>
                </div>
            </div>
            <div class="control-btns">
                <a id="compare" class="pure-btn btn-large btn-red">+ 添加对比对象</a>
            </div>
        </div>
        <div class="content">
            <h3 class="title">权重得分</h3>
            <div class="pure-g">
                <div class="pure-u-2-5 grade-box">
                    <table class="grade-table"></table>
                </div>
                <div class="pure-u-3-5 text-center" id="main" style="height:348px;">
                </div>
            </div>
        </div>
        <div class="content gray">
            <h3 class="title">点评</h3>
            <div>
                <ul class="list-arrow"></ul>
            </div>
        </div>
        <div class="content" id="score_recommend" style="display:none;">
            <h3 class="title" id="recommend_title">根据分数推荐</h3>
            <div class="recommend" id="recommend_list"></div>
        </div>
        <?= $this->render('../module/footer') ?>
