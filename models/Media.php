<?php
namespace app\models;

use Yii;
use yii\base\Model;

class Media extends Model{
    public function get_program_intro($program_default_name,$platform){
        return (new \yii\db\Query())
            ->select('intro')
            ->from('media_program')
            ->where(["program_default_name"=>$program_default_name,"platform"=>$platform])
            ->one();
    }
    public function get_list($filters){
        $programs=(new \yii\db\Query())
            ->select('t.tensyn_name')
            ->from('media_program m')
            ->innerJoin('tensyn_program_name t','m.platform=t.platform and m.program_default_name=t.program_default_name ')
        ;
        $programs=$this->_make_sql_where($programs,$filters);
        $programs=$programs->groupBy("tensyn_name")->all();

        return $programs;
    }
    public function get_list_default_name($tensyn_name){
        return (new \yii\db\Query())
            ->select('program_default_name')
            ->from('tensyn_program_name')
            ->where(["tensyn_name"=>$tensyn_name])
            ->all();
    }
    public function get_poster_url($program_default_names){
        return (new \yii\db\Query())
            ->select('url')
            ->from('media_attach')
            ->where(["in","program_default_name",$program_default_names])
            ->andWhere('type="poster"')
            ->all();
    }
    private function _make_sql_where($programs,$filters){
        if(count($filters)>0){
            foreach($filters as $k=>$v){
                $v=trim($v);
                if($v===""){continue;}
                switch($k){
                    case "platform_name": // 媒体平台
                    case "property_name": // 内容属性
                        $programs=$programs->andWhere([$k=>$v]);
                        break;
                    case "time": // 开播时间
                        $programs=$programs->andWhere(["like","start_play",$v."%",false]);
                        break;
                    case "program_name": // 开播时间
                        $programs=$programs->andWhere(["like","tensyn_name",$v]);
                        break;
                }
            }
        }
        return $programs;
    }

    public function get_program($platform_name,$tensyn_name){
        $programs=(new \yii\db\Query())
            ->select('*')
            ->from('media_program m')
            ->innerJoin('tensyn_program_name t','m.platform=t.platform and m.program_default_name=t.program_default_name ')
            ->where(["t.tensyn_name"=>$tensyn_name])
            ->orderBy('m.program_id')
            ->all();
        if(!$programs){return false;}

        $data=array();
        $media=array();
        $index=0;
        foreach($programs as $program){
            $user_id=$program["user_id"];
            $platform=$program["platform"];
            $program_id=$program["program_id"];
            if($index===0){
                $data=$program;
                $target_id=$program_id;
            }else{
                if($platform_name==$platform){
                    $data=$program;
                    $target_id=$program_id;
                }
            }

            $user=(new \yii\db\Query())
                ->select("u.user_id,u.company_name,l.url")
                ->from("media_user as u")
                ->innerJoin("media_logo as l","u.company_name=l.company_name")
                ->where(["u.user_id"=>$user_id])
                ->one();

            if($user){
                $media[]=array(
                    "name"=>$user["company_name"],
                    "url"=>"../".$user["url"],
                    "id"=>$user["user_id"]
                );
            }
            $index++;
        }

        $attach=array(
            "evaluation"=>array("name"=>"评估","url"=>""),
            "resource"=>array("name"=>"资源","url"=>""),
            "video"=>array("name"=>"视频","url"=>"")
        );

        $male="/tmps/1.jpg";
        $female="/tmps/2.jpg";
        $program_default_name=$data["program_default_name"];
        $platform=$data["platform"];

        $program=(new \yii\db\Query())
            ->select('*')
            ->from('tensyn_program')
            ->where(["program_default_name"=>$program_default_name,"platform"=>$platform])
            ->one();
        if($program){
            $program_id=$program["program_id"];
            $attachs=(new \yii\db\Query())
                ->select('*')
                ->from('tensyn_attach')
//                ->where(["program_id"=>$program_id])
                ->where(["program_default_name"=>$program_default_name,"platform"=>$platform])
                ->all();
            if($attachs){
                foreach($attachs as $a){
                    switch($a["type"]){
                        case "male":
                            $male=$a["url"];
                            break;
                        case "female":
                            $female=$a["url"];
                            break;
                    }
                }
            }
        }
        $program=(new \yii\db\Query())
            ->select("*")
            ->from("program")
            ->where(["program_default_name"=>$program_default_name,"platform_name"=>$platform])
            ->one();
        if($program){
            $attach["evaluation"]["url"]="/program/result?program_id=".$program["program_id"]."&mode_type=1";
        }

        $attachs=(new \yii\db\Query())
            ->select("*")
            ->from("media_attach")
//            ->where(["program_id"=>$target_id])
            ->where(["program_default_name"=>$program_default_name,"platform"=>$platform])
            ->all();
        foreach($attachs as $a){
            if($a["type"]=="poster"){
                $poster=Yii::getAlias('@upload').$a["url"];
            }else{
                $attach[$a["type"]]["url"]=Yii::getAlias('@upload').$a["url"];
            }
        }

        return array(
            "media"=>$media,
            "data"=>$data,
            "attach"=>$attach,
            "poster"=>$poster,
            "male"=>$male,
            "female"=>$female
        );
    }
}
