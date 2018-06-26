<?php
namespace app\controllers;

use Yii;
use app\models\Program;
use app\models\System;
use app\models\Score;

class EvaluationController extends UserAuthController{
    public $pageTitle = "评估模式";
    public $pageNavId = 1;
    private $mode_type=1;
    private $recommend_score_offset=0.3;

    public function actionMain(){
        $class_system=new System;
        $get=Yii::$app->request->get();
        $program_id=isset($get["program_id"])?$get["program_id"]:"";
        $platforms=$class_system->get_platforms();
        $properties=$class_system->get_properties();
        $types=$class_system->get_types();
        $times=$class_system->get_times();

        return $this->render('evaluation',[
            "pageTitle"=>$this->pageTitle,
            "pageNavId"=>$this->pageNavId,
            "program_id"=>$program_id,
            'platforms'=>$platforms,
            'properties'=>$properties,
            'types'=>$types,
            'times'=>$times,
        ]);
    }
    public function actionList(){
        $class_program=new Program;
        $class_system=new System;

        $data=array();
        $program_score=array();
        $time=array();

        $get=Yii::$app->request->get();
        $top=$get["top"];

        switch($top){
            case "top10":
                $top=10;
                break;
            case "top20":
                $top=20;
                break;
            case "topall":
                $top="";
                break;
        }

        $sort=$get["sort"];
        $filters=json_decode($get["filters"],true);

        $programs=$class_program->get_list($filters);
        $system_weights=$class_system->get_default_weights($this->mode_type);
        if(count($system_weights)===0){return false;}

        foreach($programs as $program){
            if($top!=""){
                $weights=$class_program->get_weights($program,$system_weights);
                if(count($weights)<5||$weights===false){continue;}
            }
            $data[]=array(
                "program_id"=>$program["program_id"],
                "program_name"=>$program["program_name"],
                "program_score"=>$program["score"],
                "time"=>$program["start_play"],
                "platform_name"=>$program["platform_name"]
            );
            $program_score[]=$program["score"];
            $time[]=$program["start_play"];

            $select[$program["program_id"]]=array("program_name"=>$program["program_name"],"check"=>false);
        }
        if(trim($top)!==""){
            array_multisort($program_score,SORT_DESC,$data);
            $data=array_slice($data,0,intval($top));
            $program_score=array();
            $time=array();
            foreach($data as $d){
                $program_score[]=$d["program_score"];
                $time[]=$d["time"];
            }
        }
        switch($sort){
            case "time":
                array_multisort($time,SORT_ASC,$program_score,SORT_DESC,$data);
                break;
            case "score":
                array_multisort($program_score,SORT_DESC,$time,SORT_ASC,$data);
                break;
        }
        return json_encode([
            "data"=>$data,
            "total"=>count($data),
        ]);
    }
    public function actionGetRecommendPrograms(){
        $class_program=new Program;
        $get=Yii::$app->request->get();
        $program_id=$get["id"];

        $data=array();

        $program=$class_program->get_list(["program_id"=>$program_id]);
        if(!$program){return false;}
        $program=$program[0];

        $score=floatval($program["score"]);
        $property_name=$program["property_name"];
        $score_offset=$this->recommend_score_offset;

        $score_max=$score+$score_offset;
        $score_min=$score-$score_offset;

        $results=$class_program->get_recommend_programs($program_id,$property_name,$score_min,$score_max);
        if(count($results)>0){
            $class_score=new Score;
            foreach($results as $result){
                $score=$result["score"];
                $program_level=$class_score->get_level($score);
                $data[]=array_merge($result,$program_level);
            }
        }
        return json_encode($data);
    }
    public function actionGetFilters(){
        $class_system=new System;

        $platforms=$class_system->get_platforms();
        $properties=$class_system->get_properties();
        $types=$class_system->get_types();
        $times=$class_system->get_times();

        echo json_encode(array(
            "platforms"=>$platforms,
            "properties"=>$properties,
            "types"=>$types,
            "times"=>$times
        ));
    }
}
