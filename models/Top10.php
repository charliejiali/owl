<?php
namespace app\models;

use yii\base\Model;
use app\models\{Rookie1,Rookie2,Iteration1,Iteration2,Score};
//use frontend\models\Program;

class Top10 extends Model{
	private $type=array(
		0=>array(
			"name"=>"IP资源",
			"id"=>"ip"
		),
		1=>array(
			"name"=>"明星热度",
			"id"=>"starhot"
		),
		2=>array(
			"name"=>"内容品质",
			"id"=>"content"
		),
		3=>array(
			"name"=>"社会影响力",
			"id"=>"influence"
		),
		4=>array(
			"name"=>"营销推广",
			"id"=>"marketing"
		),
		5=>array(
			"name"=>"明星量级",
			"id"=>"starmagnitude"
		)
	);

	public function get_types(){
		return $this->type;
	}

	public function get_result($type){
		$class_program=new Program;
		$class_score=new Score;
		$r=0;
		$msg="";
		$data=array();

		do{
			if(trim($type)===""){
				$msg="未能获取top10类型id";
				break;
			}

			$sys_type=$this->type;
			$top10_type=intval($type);

			if(!isset($sys_type[$top10_type]["id"])){
				$msg="未能找到指定top10类型";
				break;
			}

			$programs=$class_program->get_list();
			$id=$sys_type[$top10_type]["id"];
			
			foreach($programs as $program){
				switch(trim($program["property_name"])){
					case "新秀自制综艺":
						$class=new Rookie1();
						break;
					case "新秀自制剧":
						$class=new Rookie2();
						break;
					case "迭代自制综艺":
						$class=new Iteration1();
						break;
					case "迭代自制剧":
						$class=new Iteration2();
						break;
				}

				$function_name="get_top10_".$id;
				$fields=$class->$function_name();

				$score_info=$class_score->make_score($fields,$program);

				$data[]=array(
					"program_id"=>$program["program_id"],
					"program_name"=>$program["tensyn_name"],
					"program_default_name"=>$program["program_default_name"],
					"platform_name"=>$program["platform_name"],
					"property_name"=>$program["property_name"],
					"type_name"=>$program["type_name"],
					"score"=>$score_info["score"]
				);

				$score_array[]=$score_info["score"];
				$name_array[]=iconv("UTF-8", "GB2312//IGNORE", $program["tensyn_name"]);
			}
			array_multisort($score_array,SORT_DESC,$name_array,SORT_STRING,$data);
			array_splice($data,10);

			for($i=0;$i<count($data);$i++){
				$program_default_name=$data[$i]["program_default_name"];
				$platform_name=$data[$i]["platform_name"];
				$data[$i]["program_pic"]=$class_program->get_poster_src($program_default_name,$platform_name);
			}

			$r=1;
			$msg="success";
		}while(false);

		return array(
			"r"=>$r,
			"msg"=>$msg,
			"type"=>$sys_type[$top10_type]["name"],
			"data"=>$data
		);
	}

}
