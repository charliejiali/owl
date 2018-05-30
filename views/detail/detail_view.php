<?php
$media_list=$data["media"];
$program=$data["data"];
$attach=$data["attach"];
$poster_url=$data["poster"];
$template=array(
    "待播出"=>array(
        array("name"=>"剧目原名","field"=>"program_default_name"),
        array("name"=>"资源类型","field"=>"type"),
        array("name"=>"播出时间","field"=>"play_time"),
        array("name"=>"媒体平台","field"=>"platform"),
        array("name"=>"开播时间","field"=>"start_time"),
        array("name"=>"版权情况","field"=>"copyright"),
        array("name"=>"播出状态","field"=>"start_type"),
        array("name"=>"播出卫视","field"=>"satellite"),
        array("name"=>"主创/嘉宾","field"=>"creator"),
        array("name"=>"本季预估播放量（单位：亿）","field"=>"play1"),
        array("name"=>"集数/期数","field"=>"play3"),
        array("name"=>"本季预估单集播放量（万）","field"=>"play6"),
        array("name"=>"内容类型","field"=>"content_type"),
        array("name"=>"制作团队","field"=>"team")
    ),
    "播出中"=>array(
        array("name"=>"剧目原名","field"=>"program_default_name"),
        array("name"=>"资源类型","field"=>"type"),
        array("name"=>"播出时间","field"=>"play_time"),
        array("name"=>"媒体平台","field"=>"platform"),
        array("name"=>"开播时间","field"=>"start_time"),
        array("name"=>"版权情况","field"=>"copyright"),
        array("name"=>"播出状态","field"=>"start_type"),
        array("name"=>"播出卫视","field"=>"satellite"),
        array("name"=>"主创/嘉宾","field"=>"creator"),
        array("name"=>"累计播放量（单位：亿）","field"=>"play2"),
        array("name"=>"集数/期数","field"=>"play3"),
        array("name"=>"已播集数","field"=>"play4"),
        array("name"=>"实际单集播放量（万）","field"=>"play5"),
        array("name"=>"内容类型","field"=>"content_type"),
        array("name"=>"制作团队","field"=>"team")
    ),
    "已播完"=>array(
        array("name"=>"剧目原名","field"=>"program_default_name"),
        array("name"=>"资源类型","field"=>"type"),
        array("name"=>"播出时间","field"=>"play_time"),
        array("name"=>"媒体平台","field"=>"platform"),
        array("name"=>"开播时间","field"=>"start_time"),
        array("name"=>"版权情况","field"=>"copyright"),
        array("name"=>"播出状态","field"=>"start_type"),
        array("name"=>"播出卫视","field"=>"satellite"),
        array("name"=>"主创/嘉宾","field"=>"creator"),
        array("name"=>"本季预估播放量（单位：亿）","field"=>"play1"),
        array("name"=>"累计播放量（单位：亿）","field"=>"play2"),
        array("name"=>"集数/期数","field"=>"play3"),
        array("name"=>"实际单集播放量（万）","field"=>"play5"),
        array("name"=>"本季预估单集播放量（万）","field"=>"play6"),
        array("name"=>"内容类型","field"=>"content_type"),
        array("name"=>"制作团队","field"=>"team")
    )
);
$this->registerJsFile("/js/mediae/mediaelement-and-player.js");
$this->registerCssFile("/js/mediae/mediaelementplayer.js");
$this->registerJs(" var name='".$tensyn_name."';",\yii\web\View::POS_HEAD);
$this->registerJsFile("/js/pages/details_view.js",['depends'=>'yii\web\YiiAsset','position'=>\yii\web\View::POS_HEAD]);
echo $this->render('../module/head_tag');
?>

<!--    <link rel="stylesheet" type="text/css" href="js/mediae/mediaelementplayer.css">-->
<!--    <script src="js/mediae/mediaelement-and-player.js" type="text/javascript"></script>-->

    <style>
        #detailsBox{
            overflow: hidden;
            height: 280px;
        }

        .video-popup {
            position: relative;
            background: #000;
            padding: 0;
            width: 100%;
            max-width: 960px;
            margin: 0 auto; }

        .video-popup video {
            max-width: 960px;
            display: block; }

    </style>
<div class="wrap">
    <div class="owl-mode">
        <?= $this->render('../module/mode') ?>
    </div>
    <div class="owl-content">
        <?= $this->render('../module/header') ?>
        <div class="content-header">
            <?php
            $index=0;
            foreach($media_list as $m){ ?>
                <a data-name="<?php echo $m["name"];?>" name="media" href="javascript:;" class="tab-item <?php if(($index===0&&$platform==="")||$m["name"]==$platform){echo "active";}?>" ><img src="<?php echo $m["url"];?>" alt=""></a>
                <?php
                $index++;
            }
            ?>
            <!-- <a href="#" class="tab-item"><img src="images/clients/client_iqiyi.png" alt=""></a>
            <a href="#" class="tab-item active"><img src="images/clients/client_youku.png" alt=""></a>
            <a href="#" class="tab-item"><img src="images/clients/client_sohu.png" alt=""></a> -->
        </div>
        <div class="content">

            <div class="film-box-details">
                <div class="box-img-mask">
                    <?php if($poster_url==""){ ?>
                    <div class="box-img no-img">
                        <?php }else{ ?>
                        <div class="box-img" style="background-image:url('<?php echo $poster_url;?>') ">
                            <?php } ?>
                            <p><?php echo $program["program_name"];?></p></div>
                    </div>
                    <div class="box-content">
                        <p class="title">《<?php echo $program["program_name"];?>》</p>
                        <div class="imgs">
                            <div class="img-item">
                                <img src="<?php echo $data["male"];?>" alt="">
                                <!-- 王大陆 -->
                            </div>
                            <div class="img-item">
                                <img src="<?php echo $data["female"];?>" alt="">
                                <!-- 金晨 -->
                            </div>
                        </div>
                        <p>
                            <?php
                            foreach($attach as $k=>$v){
                                if($v["name"]=="视频"){
                                    if($v["url"]!=""){
                                        ?>
                                        <a href="#" class="pure-btn btn-red btn-small video-play-button" data-video="<?php echo $v["url"];?>"><?php echo $v["name"];?></a>
                                    <?php   }else{ ?>
                                        <a href="#" class="pure-btn btn-red btn-small" ><?php echo $v["name"];?></a>
                                        <?php
                                    }
                                }else{
                                    ?>
                                    <a href="javascript:<?php echo $v["url"]!=""?"window.open('".$v["url"]."')":";";?>" class="pure-btn btn-red btn-small"><?php echo $v["name"];?></a>
                                    <?php
                                }
                            }
                            ?>

                            <!-- <a href="#" class="pure-btn btn-red btn-small video-play-button" data-video="tmps/tmp_video.mp4">播放</a> -->
                        </p>
                    </div>
                    <div class="box-content" id="detailsBox">
                        <ul class="list-arrow">
                            <?php
                            $template=$template[$program["start_type"]];
                            foreach($template as $t){
                                ?>
                                <li><?php echo $t["name"]."：".$program[$t["field"]];?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>


            </div>

            <div class="content gray">

                <h3 class="title">剧目简介</h3>
                <div class="box-info"><?php echo $program["intro"];?>
                    <!-- 《鬼吹灯之牧野诡事》是根据天下霸唱同名小说改编，由爱奇艺、向上影业出品，华谊兄弟联合出品，赵氏兄弟（赵小鸥、赵小溪）执导，王大陆、金晨、王栎鑫等人主演的悬疑动作类网络季播剧
                    。该剧主要讲述胡八一的儿子胡天与冰轮、雷厉、王耀、小金牙进入千年古墓寻找胡天父母下落时发生的事。 -->
                </div>
            </div>
            <div class="content ">
                <div class="control-btns">
                    <br>
                    <a id="back" href="javascript:;" class="pure-btn btn-large btn-red"><span class="ico-arrow-l icon-tools"></span>返回上一步</a>
                </div>
            </div>
            <?= $this->render('../module/footer') ?>
    <script type="text/javascript">

    </script>
