<?php
namespace app\controllers;

use Yii;
use app\models\Top10;

class Top10Controller extends UserAuthController{
    public $pageTitle = "TOP 10";
    public $pageNavId = 3;

    public function actionMain(){
        $get=Yii::$app->request->get();
        $type=$get["type"];

        return $this->render('top10',[
            "type"=>$type
        ]);
    }
    public function actionList(){
        $get=Yii::$app->request->get();
        $type=$get["type"];
        $top10=new Top10;
        return json_encode($top10->get_result($type));
    }
}
