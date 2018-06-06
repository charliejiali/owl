<?php
namespace app\controllers;

use Yii;
use app\models\Media;
use app\models\System;

class DetailController extends UserAuthController
{
    public $pageTitle = "资源详情";
    public $pageNavId = 4;

    public function actionView(){
        $get=Yii::$app->request->get();
        $platform=isset($get["platform"])?$get["platform"]:"";
        $name=isset($get["name"])?$get["name"]:"";

        $class_media=new Media;
        $program=$class_media->get_program($platform,$name);

        return $this->render('detail_view',[
            "platform"=>$platform,
            "tensyn_name"=>$name,
            "data"=>$program
        ]);
    }
    public function actionMain(){
        $class_system=new System;

        $platforms=$class_system->get_platforms();
        $properties=$class_system->get_properties();
        $times=$class_system->get_times();

        return $this->render('detail',[
            "platforms"=>$platforms,
            'properties'=>$properties,
            'times'=>$times
        ]);
    }
    public static function actionGetList()
    {
        $data=[];
        $get=Yii::$app->request->get();
        $class_media=new Media;
        $programs=$class_media->get_list($get);
        if(count($programs)>0){
            foreach ($programs as $program) {
                $re = array();
                $name_array = array();
                $tensyn_name = $program["tensyn_name"];
                $olds=$class_media->get_list_default_name($tensyn_name);
                foreach ($olds as $old) {
                    $name_array[] =$old["program_default_name"];
                }
                $attachs=$class_media->get_poster_url($name_array);

                if (!$attachs) {
                    $re["src"] = "";
                } else {
                    $re["src"] = Yii::getAlias('@upload').$attachs[0]["url"];
                }
                $re["program_name"] = $tensyn_name;
                $data[] = $re;
            }
        }
        return json_encode($data);
    }
}
