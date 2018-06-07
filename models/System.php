<?php
namespace app\models;

use yii\base\Model;
use yii\db\Query;

class System extends Model{
    private $system_weights=array(
        "play","channel","platform","make","resource","attention","IP","match","star","topic"
    );

    public function get_system_weights(){
        return $this->system_weights;
    }
    public function get_platforms(){
        return (new Query)
            ->select('platform_name')
            ->from('program')
            ->where(['and','platform_name!=-1',"platform_name!=''"])
            ->groupBy("platform_name")->all();
    }
    public function get_properties(){
        return (new Query)
            ->select('property_name')
            ->from('program')
            ->where(['and','property_name!=-1',"property_name!=''"])
            ->groupBy("property_name")->all();
    }
    public function get_types(){
        $type1=array();
        $type2=array();

        $results=(new Query)
            ->select('type_name')
            ->from('program')
            ->where(['and','type_name!=-1',"type_name!=''"])
            ->groupBy("type_name")->all();

        if(count($results)>0){
            foreach($results as $r){
                $temps=explode("/",$r["type_name"]);
                $count=count($temps);
                for($i=0;$i<$count;$i++){
                    $value=$temps[$i];
                    if($i===0){
                        if(!in_array($value,$type1)){$type1[]=$value;}
                    }else{
                        if(!in_array($value,$type2)){$type2[]=$value;}
                    }
                }
            }
        }
        return array(
            "type1"=>$type1,
            "type2"=>$type2
        );
    }
    public function get_times(){
        $exists=array();
        $data=array();

        $results=(new Query)
            ->select('start_play')
            ->from('program')
            ->where(['and','start_play!=-1',"start_play!=''"])
            ->groupBy("start_play")->all();

        if($results){
            foreach($results as $r){
                $value=$r["start_play"];
                $year=mb_substr($value,0,5);
                if(!in_array($year,$exists)){
                    $data[]=array("start_play"=>$year);
                    $exists[]=$year;
                }
                if(!in_array($value,$exists)){
                    $data[]=array("start_play"=>$value);
                    $exists[]=$value;
                }
            }
        }
        return $data;
    }
    public function get_default_weights($mode_type){
        return (new Query)
            ->select("*")
            ->from("system_weight")
            ->where(["type"=>$mode_type])
            ->andWhere('status=1')
            ->orderBy('sort_id asc')
            ->all();
    }
    public function check_weights_total($weights){
        $total_weight=0;
        foreach($weights as $k=>$v){
            $total_weight+=intval($v);
        }
        if($total_weight!==intval(100)){
            return false;
        }else{
            return true;
        }
    }
}
?>
