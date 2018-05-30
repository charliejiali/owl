<?php
namespace app\controllers;

use app\models\{Program,Media,System};
use Yii;
use app\models\Match;

class RecommendController extends UserAuthController{
    private $mode_type=2;
    public $pageTitle = "推荐模式";
    public $pageNavId = 2;


    public function actionMatch(){
        return $this->render('recommend');
    }
    public function actionMain(){
        $class_match=new Match;
        $class_system=new System;
        $get=Yii::$app->request->get();
        $system_match_weights=$class_match->get_match_weights();

        foreach($get as $k=>$v){
            if(in_array($k,$system_match_weights)){
                $match_weights[$k]=$v;
            }
        }

        $program_id=$get["program_id"]??"";
        $platforms=$class_system->get_platforms();
        $properties=$class_system->get_properties();
        $types=$class_system->get_types();
        $times=$class_system->get_times();

        return $this->render('recommend_list',[
            "program_id"=>$program_id,
            'platforms'=>$platforms,
            'properties'=>$properties,
            'types'=>$types,
            'times'=>$times,
            'match_weights'=>$match_weights
        ]);
    }
    public function actionList(){
        $class_system=new System;
        $class_match=new Match;
        $class_program=new Program;
        $r=0;
        $msg="";

        $get=Yii::$app->request->get();
        $top=$get["top"];
        $sort=$get["sort"];
        $filters=$get["filters"];
        $match_weights=$get["match_weights"];
        $match_score_limit=$class_match->get_score_limit();

        if(
            $check=$class_match->check_age($match_weights["age_min"],$match_weights["age_max"])!==true
            ||$check=$class_match->check_sex($match_weights["male"],$match_weights["female"])!==true
            ||$check=$class_match->filter_province($match_weights["province"])!==true
        ){
            $msg=$check;
        }else{
            $system_weights=$class_system->get_default_weights($this->mode_type);
            if(count($system_weights)===0){return false;}
            $programs=$class_program->get_list($filters);

            $data=array();
            $match_score=array();
            $program_score=array();
            $time=array();

            foreach($programs as $program){
                $weights=$class_program->get_weights($program,$system_weights);
                if(count($weights)<5||$weights===false){continue;}

                $program_score_info=$class_program->get_result($this->mode_type,$program,$weights,$match_weights);
                $score_match=$program_score_info["match_info"]["score"];
                $score_program=$program_score_info["level1"]["score"];
                $start_play=$program_score_info["start_play"];
                if($score_match>=$match_score_limit&&$score_program>2){
                    $data[]=array(
                        "program_id"=>$program_score_info["program_id"],
                        "program_name"=>$program_score_info["program_name"],
                        "match_score"=>$score_match,
                        "program_score"=>$score_program,
                        "time"=>$start_play,
                        "platform_name"=>$program_score_info["platform_name"]
                    );
                    $match_score[]=$score_match;
                    $program_score[]=$score_program;
                    $time[]=$start_play;
                }
            }
            if(trim($top)!==""){
                array_multisort($program_score,SORT_DESC,$data);
                $data=array_slice($data,0,intval($top));
                $match_score=array();
                $program_score=array();
                $time=array();
                foreach($data as $d){
                    $match_score[]=$d["match_score"];
                    $program_score[]=$d["program_score"];
                    $time[]=$d["time"];
                }
            }
            switch($sort){
                case "time":
                    array_multisort($time,SORT_ASC,$program_score,SORT_DESC,$data);
                    break;
                case "match":
                    array_multisort($match_score,SORT_DESC,$program_score,SORT_DESC,$data);
                    break;
                case "score":
                    array_multisort($program_score,SORT_DESC,$match_score,SORT_DESC,$data);
                    break;
            }
        }
        $r=1;
        return json_encode([
            "r"=>$r,
            "msg"=>$msg,
            "data"=>$data
        ]);
    }
}
