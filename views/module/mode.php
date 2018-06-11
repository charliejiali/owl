<?php
use app\models\Top10;

$pageNavId=$this->context->pageNavId;
?>
<a href="./" class="logo"></a>
<div class="pure-menu menu-mode">
    <ul class="pure-menu-list">
        <li class="pure-menu-item <?php if ($pageNavId == 1) {echo " pure-menu-selected";} ?>"><a href="/evaluation/main" class="pure-menu-link"><span class="icon-tools ico-eval"></span>评估模式</a></li>
        <li class="pure-menu-item <?php if ($pageNavId == 2) {echo " pure-menu-selected";} ?>"><a href="/recommend/match" class="pure-menu-link"><span class="icon-tools ico-recom"></span>推荐模式</a></li>
        <li class="pure-menu-item <?php if ($pageNavId == 3) {echo " pure-menu-selected";} ?>"><a href="/top10/main?type=0" class="pure-menu-link"><span class="icon-tools ico-top10"></span>TOP10排行榜</a></li>
        <li class="pure-menu-item top-ten <?php if ($pageNavId == 3) {echo " pure-menu-selected";} ?>">
            <?php
                $get=Yii::$app->request->get();
                $top10_type=$pageNavId == 3&&isset($get["type"])?trim($get["type"]):"";

                $class_top10=new Top10;
                $types=$class_top10->get_types();
                    foreach($types as $k=>$v){
            ?>
            <a href="/top10/main?type=<?php echo $k;?>" class="pure-menu-link <?php if($top10_type==$k){echo "active";}?>"><?php echo $v["name"];?></a>
            <?php 
                }
            ?>
        </li>
         <li class="pure-menu-item <?php if ($pageNavId == 4) {echo " pure-menu-selected";} ?>"><a href="/detail/main" class="pure-menu-link"><span class="icon-tools ico-details"></span>资源详情</a></li>
    </ul>
</div>
