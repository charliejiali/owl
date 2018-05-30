<?php
$this->registerJs(" var type='".$type."';",\yii\web\View::POS_HEAD);
$this->registerJsFile("/js/pages/top10.js");
?>
<?= $this->render('../module/head_tag'); ?>
<div class="wrap">
    <div class="owl-mode">
        <?= $this->render('../module/mode') ?>
    </div>
    <div class="owl-content">
        <?= $this->render('../module/header') ?>
        <div class="content">
            <h3 class="title"></h3>
            <div class="top-ten-title"></div>
            <div class="pure-g " id="list"></div>
            <div class="control-btns"></div>
        </div>
        <?= $this->render('../module/footer') ?>
