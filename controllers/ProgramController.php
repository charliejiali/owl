<?php
namespace app\controllers;

use Yii;
use app\models\{Program,System,Media};
use app\models\Match;

class ProgramController extends UserAuthController{
    public $pageTitle;
    public $pageNavId;

    public function actionResult(){
        $this->_set_page_info();
        return $this->render('program_result');
    }
    public function actionCompareResult(){
        $this->_set_page_info();
        return $this->render('program_compare_result');
    }
    public function actionGetWeights(){
        $class_system=new System;
        $class_program=new Program;

        $weights=[];
        $check=false;
        $get=Yii::$app->request->get();
        $program_ids=$get["ids"];
        $mode_type=$get["mode_type"];

        if(trim($program_ids)===""){return false;}

        $system_weights=$class_system->get_default_weights($mode_type);
        if(count($system_weights)===0){return false;}

        foreach($system_weights as $sw){
            $usable_weights[]=$sw["html_id"];
        }

        $programs=$class_program->get_list(["program_id"=>$program_ids]);
        if(count($programs)>0){
            foreach($programs as $program){
                $valid_weights=$class_program->get_valid_weights($program);
                if(count($valid_weights)>0){
                    $check=true;
                    $usable_weights=array_intersect($usable_weights,$valid_weights);
                }
            }
            if($check){
                $weights=$class_program->remake_weights($usable_weights,$system_weights);
            }
        }
        return json_encode($weights);
    }
    public function actionGetResult(){
        $class_match=new Match;
        $class_system=new System;
        $class_program=new Program;

        $data=[];
        $system_weights=[];
        $match_weights=[];

        $get=Yii::$app->request->get();

        $sys_weights=$class_system->get_system_weights();
        $mat_weights=$class_match->get_match_weights();

        foreach($get as $k=>$v){
            if(in_array($k,$sys_weights)){
                $system_weights[$k]=$v;
            }elseif(in_array($k,$mat_weights)){
                $match_weights[$k]=$v;
            }else{}
        }
        $mode_type=$get["mode_type"];
        $program_id=$get["program_id"];

        $programs=$class_program->get_list(["program_id"=>$program_id]);
        if(count($programs)>0){
            foreach($programs as $program){
                $result=$class_program->get_result($mode_type,$program,$system_weights,$match_weights);
                $data[]=$result;
            }
        }
        return json_encode([
            "program_id"=>$program_id,
            "mode_type"=>$mode_type,
            "match_weights"=>$match_weights,
            "system_weights"=>$system_weights,
            "data"=>$data
        ]);
    }
    public function actionGetIntro(){
        $class_media=new Media;
        $class_program=new Program;
        $msg="";
        $get=Yii::$app->request->get();
        $program_id=$get["program_id"];
        $programs=$class_program->get_list(["program_id"=>$program_id]);
        if(count($programs)>0){
            $program=$programs[0];
            $program_default_name=$program["program_default_name"];
            $platform=$program["platform_name"];

            $info=$class_media->get_program_intro($program_default_name,$platform);
            if(!$info){
                $msg="暂无";
            }else{
                $msg=$info["intro"];
            }
        }else{
            $msg="暂无";
        }
        return json_encode(["msg"=>$msg]);
    }

    private function _set_page_info(){
        $get=Yii::$app->request->get();
        $mode_type=$get["mode_type"];
        switch($mode_type){
            case "1":
                $this->pageTitle = "评估模式";
                $this->pageNavId = 1;
                break;
            case "2":
                $this->pageTitle = "推荐模式";
                $this->pageNavId = 2;
                break;
        }
    }
}
