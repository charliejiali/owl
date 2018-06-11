<?php
$this->registerJsFile("/echarts/build/dist/echarts.js");
$this->registerJsFile("/js/pages/program_compare_result.js",['depends'=>'yii\web\YiiAsset','position'=>\yii\web\View::POS_HEAD]);
?>
<?= $this->render('../module/head_tag'); ?>
<div class="wrap">
    <div class="owl-mode">
        <?= $this->render('../module/mode') ?>
    </div>
    <div class="owl-content">
        <?= $this->render('../module/header') ?>
        <div class="content-header" id="resultTab">
            <a href="#" class="tab-item active">详细结果</a>
        </div>
        <div class="content">
            <h3 class="title">对比结果</h3>
            <div class="pure-g " id="result_list"></div>
            <div class="control-btns">
                <a href="javascript:;" class="pure-btn btn-large btn-red" id="compare"> + 添加对比对象 </a>
            </div>
        </div>
        <div class="content">
            <h3 class="title">权重得分</h3>
            <div class="pure-g">
                <div class="pure-u-2-5 grade-box">
                    <table class="grade-table" style="width: 76%"></table>
                </div>
                <div id="main" style="height:348px;" class="pure-u-3-5 text-center">
                </div>
            </div>
        </div>
        <div class="content gray">
            <h3 class="title">点评</h3>
            <div>
                    <table class="pure-table pure-table-bordered" id="table_comment">
                        <thead>
                        <tr class="text-center" id="tr_program_name">
                            <th width="13%" class="column" >剧目名称</th>
                        </tr>
                        </thead>
                        <tbody id="table_tbody">
                        <tr class="text-center" id="td_recommend">
                            <td class="column">推荐等级</td>
                        </tr>
                        <tr class="text-center" id="td_score">
                            <td class="column">总得分</td>
                        </tr>
                        <tr class="text-center" id="td_platform">
                            <td class="column">播放平台</td>
                        </tr>
                        <tr class="text-center" id="td_start_play">
                            <td class="column">开播时间</td>
                        </tr>
                        <tr class="text-center" id="td_actor">
                            <td class="column">主演</td>
                        </tr>
                        <tr class="text-center" id="td_team">
                            <td class="column">制作团队</td>
                        </tr>
                        <tr class="text-center" id="td_play1">
                            <td class="column">预估单集播放量</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
        </div>
        <?= $this->render('../module/footer') ?>

